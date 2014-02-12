<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//    REPORTE: Retencion de Impuestos Municipales
	//  ORGANISMO: I.P.S.F.A
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";		
		print "</script>";		
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 15/07/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_retencionesmunicipales.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 04/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->Rectangle(50,515,690,65);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],55,520,60,50); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(20,$as_titulo);
		$tm=420-($li_tm/2);
		$io_pdf->addText($tm,540,20,$as_titulo); // Agregar el título
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_numcon,$ad_fecrep,$as_agenteret,$as_rifagenteret,$as_perfiscal,$as_licagenteret,$as_diragenteret,
							   $as_nomsujret,$as_rif,$as_numlic,$ai_estcmpret,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_numcon // Número de Comprobante
		//	    		   ad_fecrep // Fecha del comprobante
		//	    		   as_agenteret // agente de Retención
		//	    		   as_rifagenteret // Rif del Agente de Retención
		//	    		   as_perfiscal // Período Fiscal
		//	    		   as_licagenteret // Número de licencia de agente de retención
		//	    		   as_diragenteret // Dirección del agente de retención
		//	    		   as_nomsujret // Nombre del sujeto retenido
		//	    		   as_rif // Rif del sujeto retenido
		//	    		   as_numlic // Número de Licencia del sujeto retenido
		//	    		   ai_estcmpret // Estatus del comprobante
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 17/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-4);
	 	if($ai_estcmpret==2)
		{
		    $io_pdf->Rectangle(45,495,180,30);		
			$io_pdf->addText(90,505,15,"<b> ANULADO </b>"); 
		}	
		$la_data[1]=array('name'=>'<b>LEY DE TIMBRE FISCAL</b>');		
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 12, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'colGap'=>1,
						 'width'=>690, // Ancho de la tabla						 
						 'maxWidth'=>690,
						 'cols'=>array('name'=>array('justification'=>'center','width'=>690))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('agen_ret'=>'<b>Nº DE R.I.F. Agente de Retencion </b>',
		                  'ubic'=>'<b>DISTRITO METROPOLITANO DE CARACAS</b>',
						  'correlativo'=>'');				
		$la_columna=array('agen_ret'=>'',
		                  'ubic'=>'',
						  'correlativo'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>690, // Ancho de la tabla	
						 'colGap'=>1,					 
						 'maxWidth'=>690,
						 'cols'=>array('agen_ret'=>array('justification'=>'center','width'=>150),
						               'ubic'=>array('justification'=>'center','width'=>390),
						               'correlativo'=>array('justification'=>'center','width'=>150)));
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		
		$io_pdf->addText(635,487,9,date("d/m/Y")); // Agregar la Fecha	
		$la_data[1]=array('agen_ret'=>$as_rifagenteret,
		                  'ubic'=>'D.M.C: Providencia administrativa Nº DRTI-2004-0022, de fecha 13 de Abril 2004, mediante la cual se designan a los agentes de retención del Impuesto 1x1000, consagrado en el art. Nº 9 de la Ordenanza de Timbre Fiscal del Distrito Metropolitano de Caracas',
						  'correlativo'=>'<b>CORRELATIVO: </b>'.$as_numcon);				
		$la_columna=array('agen_ret'=>'',
		                  'ubic'=>'',
						  'correlativo'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>690, // Ancho de la tabla	
						 'colGap'=>1,					 
						 'maxWidth'=>690,
						 'cols'=>array('agen_ret'=>array('justification'=>'center','width'=>150),
						               'ubic'=>array('justification'=>'left','width'=>390),
						               'correlativo'=>array('justification'=>'center','width'=>150)));
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('name'=>'<b>DATOS DEL BENEFICIARIO                                                                                                                 I.P.S.F.A</b>');		
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>690, // Ancho de la tabla
						 'colGap'=>1,						 
						 'maxWidth'=>690,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>690))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('name'=>'<b>NOMRE: '.$as_nomsujret.'</b>');
		$la_data[2]=array('name'=>'<b>Nº DE RIF: '.$as_rif.'</b>');		
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>690, // Ancho de la tabla
						 'colGap'=>1,						 
						 'maxWidth'=>690,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>690))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);						 								 
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------			
			
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,$ai_totbasimp,$ai_totmonimp,$ai_totmoniva,$as_rifagenteret,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: la_data // Arreglo de datos a imprimir
		//	    		   ai_totbasimp // Total de la base imponible
		//	    		   ai_totmonimp // Total monto imponible
		//                 ai_totmoniva // Total monto iva
		//	    		   as_rifagenteret // Rif del Agente de Retención
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		//     Modificado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 14/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data1[1]=array('fecfac'=>'<b>Fecha Factura</b>',
		                  'numfac'=>'<b>Nº Factura</b>',
  						  'numref'=>'<b>Nº Control</b>',		
						  'baseimp'=>'<b>Base Imponible</b>',
						  'iva_ret'=>'<b>Monto Ret.</b>',
						  'porimp'=>'<b>Retención     </b><b>1 x 1000</b>',  
						  'totimp'=>'<b>Total Monto Cheque</b>',
						  'numsop'=>'<b>Orden de Pago Nº</b>');
		$la_columna=array('fecfac'=>'<b>Fecha Factura</b>',
		                  'numfac'=>'<b>Nº Factura</b>',
  						  'numref'=>'<b>Nº Control</b>',		
						  'baseimp'=>'<b>Base Imponible</b>',
						  'iva_ret'=>'<b>Monto Ret.</b>',
						  'porimp'=>'<b>Retención 1 x 1000</b>',  
						  'totimp'=>'<b>Total Monto Cheque</b>',
						  'numsop'=>'<b>Orden de Pago Nº</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>690, // Ancho de la tabla
						 'maxWidth'=>690, // Ancho Mínimo de la tabla
						 'colGap'=>1,
						 'cols'=>array('fecfac'=>array('justification'=>'center','width'=>70),
						 			   'numfac'=>array('justification'=>'center','width'=>85), // Justificacion y ancho de la columna
						 			   'numref'=>array('justification'=>'center','width'=>85), // Justificacion y ancho de la columna
									   'baseimp'=>array('justification'=>'center','width'=>100), // Justificacion y ancho de la columna
						 			   'iva_ret'=>array('justification'=>'center','width'=>100),
						 			   'porimp'=>array('justification'=>'center','width'=>55),
   						 			   'totimp'=>array('justification'=>'center','width'=>95),
									   'numsop'=>array('justification'=>'center','width'=>100))); 
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		$la_columna=array('fecfac'=>'<b>Fecha Factura</b>',
		                  'numfac'=>'<b>Numero de Factura</b>',
  						  'numref'=>'<b>Nº Control</b>',		
						  'baseimp'=>'<b>Base Imponible</b>',
						  'iva_ret'=>'<b>Monto Ret.</b>',
						  'porimp'=>'<b>Retención 1 x 1000</b>',  
						  'totimp'=>'<b>Total Monto Cheque</b>',
						  'numsop'=>'<b>Orden de Pago Nº</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>690, // Ancho de la tabla
						 'colGap'=>1,
						 'maxWidth'=>690, // Ancho Mínimo de la tabla
						 'cols'=>array('fecfac'=>array('justification'=>'center','width'=>70),
						 			   'numfac'=>array('justification'=>'center','width'=>85), // Justificacion y ancho de la columna
						 			   'numref'=>array('justification'=>'center','width'=>85), // Justificacion y ancho de la columna
									   'baseimp'=>array('justification'=>'right','width'=>100), // Justificacion y ancho de la columna
						 			   'iva_ret'=>array('justification'=>'right','width'=>100),
						 			   'porimp'=>array('justification'=>'center','width'=>55),
   						 			   'totimp'=>array('justification'=>'right','width'=>95),
									   'numsop'=>array('justification'=>'center','width'=>100))); 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		$la_data1[1]=array('total'=>'<b>Total Monto Retenido:</b>',
		                   'monto'=>'<b>'.$ai_totbasimp.'</b>',
		                   'iva'=>'<b>'.$ai_totmoniva.'</b>',
						   'ret'=>'',		
						   'imponible'=>'<b>'.$ai_totmonimp.'</b>',
						   'orpag'=>'');
		$la_columna=array('total'=>'',
		                  'monto'=>'',
		                  'iva'=>'',
						  'ret'=>'',		
						  'imponible'=>'',
						  'orpag'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>690, // Ancho de la tabla
						 'colGap'=>1,
						 'maxWidth'=>690, // Ancho Mínimo de la tabla
						 'cols'=>array('total'=>array('justification'=>'center','width'=>240), // Justificacion y ancho de la columna
   						 			   'monto'=>array('justification'=>'right','width'=>100),
									   'iva'=>array('justification'=>'right','width'=>100),
									   'ret'=>array('justification'=>'right','width'=>55),
   						 			   'imponible'=>array('justification'=>'right','width'=>95),
									   'orpag'=>array('justification'=>'right','width'=>100))); 
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_detalle

	function uf_print_sello(&$io_pdf)
	{
	    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_sello
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Jennifer Rivero
		//     Modificado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 13/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $la_data[1]=array('name1'=>'<b>ELABORADO POR</b>',
	                    'name2'=>'<b>JEFE DE LA UNIDAD</b>',
						'name3'=>'<b>TESORERO I.P.S.F.A</b>');	
        $la_columna=array('name1'=>'','name2'=>'','name3'=>'');
		$la_config= array('showHeadings'=>0, // Mostrar encabezados
						  'fontSize' => 11, // Tamaño de Letras
						  'showLines'=>2, // Mostrar Líneas
						  'shaded'=>0, // Sombra entre líneas
						  'shadeCol'=>array(0.9,0.9,0.9),
						  'shadeCol2'=>array(0.9,0.9,0.9),
						  'xOrientation'=>'center', // Orientación de la tabla
						  'colGap'=>1,
						  'width'=>690,
						  'cols'=>array('name1'=>array('justification'=>'center','width'=>240),						                
										'name2'=>array('justification'=>'center','width'=>200),
										'name3'=>array('justification'=>'center','width'=>250))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config); 		
		 
	    $la_data[1]=array('name1'=>'','name2'=>'','name3'=>'');
		$la_data[2]=array('name1'=>'','name2'=>'','name3'=>'');	
		$la_data[3]=array('name1'=>'','name2'=>'','name3'=>'');	
		$la_data[4]=array('name1'=>'','name2'=>'','name3'=>'');	
		$la_data[5]=array('name1'=>'ARMINDA FLORES','name2'=>'LIC. ROSA ARVELO','name3'=>'CNEL (EJ) MARCO ANTONIO ROJAS T');		
        $la_columna=array('name1'=>'','name2'=>'','name3'=>'');
		$la_config= array('showHeadings'=>0, // Mostrar encabezados					  
						  'shaded'=>0, // Sombra entre líneas
						  'shadeCol'=>array(0.9,0.9,0.9),
						  'shadeCol2'=>array(0.9,0.9,0.9),
						  'xOrientation'=>'center', // Orientación de la tabla
						  'colGap'=>1,
						  'width'=>530,
						  'cols'=>array('name1'=>array('justification'=>'center','width'=>240),						                
										'name2'=>array('justification'=>'center','width'=>200),
										'name3'=>array('justification'=>'center','width'=>250))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config); 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $la_data2[1]=array('name1'=>'<b>RECIBE CONFORME</b>',
	                       'name2'=>'<b>SELLO I.P.S.F.A</b>');	
        $la_columna=array('name1'=>'','name2'=>'');
		$la_config= array('showHeadings'=>0, // Mostrar encabezados
						  'fontSize' => 11, // Tamaño de Letras
						  'showLines'=>2, // Mostrar Líneas
						  'shaded'=>0, // Sombra entre líneas
						  'shadeCol'=>array(0.9,0.9,0.9),
						  'shadeCol2'=>array(0.9,0.9,0.9),
						  'xOrientation'=>'center', // Orientación de la tabla
						  'colGap'=>1,
						  'width'=>530,
						  'cols'=>array('name1'=>array('justification'=>'center','width'=>440),						                
										'name2'=>array('justification'=>'center','width'=>250))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data2,$la_columna,'',$la_config); 		
			    
		$la_data3[1]=array('name1'=>'','name2'=>'','name3'=>'');
		$la_data3[2]=array('name1'=>'<b> Nombre y Apellido:                               ________________________________</b>','name2'=>'');	
		$la_data3[3]=array('name1'=>'','name2'=>'');	
		$la_data3[4]=array('name1'=>'<b> Cédula de Identidad:                            ________________________________</b>','name2'=>'');	
		$la_data3[5]=array('name1'=>'','name2'=>'');	
		$la_data3[6]=array('name1'=>'<b> Fecha en se que Recibe Comprobante:                       ___________________</b>','name2'=>'');	
		$la_data3[7]=array('name1'=>'','name2'=>'');
		
        $la_columna=array('name1'=>'','name2'=>'');
		$la_config= array('showHeadings'=>0, // Mostrar encabezados					  
						  'shaded'=>0, // Sombra entre líneas
						  'shadeCol'=>array(0.9,0.9,0.9),
						  'shadeCol2'=>array(0.9,0.9,0.9),
						  'xOrientation'=>'center', // Orientación de la tabla
						  'colGap'=>1,
						  'width'=>530,
						  'cols'=>array('name1'=>array('justification'=>'left','width'=>440),						                
										'name2'=>array('justification'=>'center','width'=>250))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data3,$la_columna,'',$la_config); 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------

	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_cxp_class_report.php");
	$io_report=new sigesp_cxp_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_cxp_class_reportbsf.php");
		$io_report=new sigesp_cxp_class_reportbsf();
	}
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo= "INSTITUTO DE PREVISION SOCIAL DE LA FUERZA ARMADA";
    $ls_agente=$_SESSION["la_empresa"]["nombre"];
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_comprobantes=$io_fun_cxp->uf_obtenervalor_get("comprobantes","");
	$ls_mes=$io_fun_cxp->uf_obtenervalor_get("mes","");
	$ls_anio=$io_fun_cxp->uf_obtenervalor_get("anio","");
	$ls_agenteret=$_SESSION["la_empresa"]["nombre"];
	$ls_rifagenteret=$_SESSION["la_empresa"]["rifemp"];
	$ls_diragenteret=$_SESSION["la_empresa"]["direccion"];
	$ls_licagenteret=$_SESSION["la_empresa"]["numlicemp"];
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$la_comprobantes=split('-',$ls_comprobantes);
		$la_datos=array_unique($la_comprobantes);
		$li_totrow=count($la_datos);
		sort($la_datos,SORT_STRING);
		if($li_totrow<=0)
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}
		else
		{
			error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf = new Cezpdf("LETTER","landscape");
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
			$io_pdf->ezSetCmMargins(3.2,2.5,3,3);
			$lb_valido=true;
			$ls_numcomant = "";
			for ($li_z=0;($li_z<$li_totrow)&&($lb_valido);$li_z++)
			{
				uf_print_encabezado_pagina($ls_titulo,$io_pdf);
				$ls_numcom=$la_datos[$li_z];
				$lb_valido=$io_report->uf_retencionesmunicipales_proveedor($ls_numcom,$ls_mes,$ls_anio);
				if($lb_valido)
				{
					$li_total=$io_report->DS->getRowCount("numcom");
					for($li_i=1;$li_i<=$li_total;$li_i++)
					{
						$ls_numcon=$io_report->DS->data["numcom"][$li_i];		 								
						$ls_codret=$io_report->DS->data["codret"][$li_i];			   
						$ls_fecrep=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecrep"][$li_i]);
						$ls_perfiscal=$io_report->DS->data["perfiscal"][$li_i];						
						$ls_codsujret=$io_report->DS->data["codsujret"][$li_i];			     
						$ls_nomsujret=$io_report->DS->data["nomsujret"][$li_i];	
						$ls_rif=$io_report->DS->data["rif"][$li_i];	
						$ls_dirsujret=$io_report->DS->data["dirsujret"][$li_i];		
						$li_estcmpret=$io_report->DS->data["estcmpret"][$li_i];	
						$ls_numlic=$io_report->DS->data["numlic"][$li_i];									
						if ($ls_numcom!=$ls_numcomant)
					   {
					    if ($li_z>=1)
						   {
							 $io_pdf->ezNewPage();  
						   }
					     uf_print_cabecera($ls_numcon,$ls_fecrep,$ls_agenteret,$ls_rifagenteret,$ls_perfiscal,$ls_licagenteret,
									  $ls_diragenteret,$ls_nomsujret,$ls_rif,$ls_numlic,$li_estcmpret,&$io_pdf);
						 $ls_numcomant=$ls_numcom;
					   }
					}											
					$lb_valido=$io_report->uf_retencionesmunicipales_detalles($ls_numcom);
					if($lb_valido)
					{
						$li_totalbaseimp=0;
						$li_totalmontoimp=0;
						$li_totmontoiva=0;
						$li_totmontotdoc=0;
						$li_total=$io_report->ds_detalle->getRowCount("numfac");			   
						for($li_i=1;$li_i<=$li_total;$li_i++)
						{
							$li_montotdoc=$io_report->uf_retencionesmunicipales_monfact($ls_numcon);
							$ls_numsop=$io_report->ds_detalle->data["numsop"][$li_i];					
							$ld_fecfac=$io_funciones->uf_convertirfecmostrar($io_report->ds_detalle->data["fecfac"][$li_i]);	
							$ls_numfac=$io_report->ds_detalle->data["numfac"][$li_i];	
							$ls_numref=$io_report->ds_detalle->data["numcon"][$li_i];	              
							$li_baseimp=$io_report->ds_detalle->data["basimp"][$li_i];
							$li_iva_ret=$io_report->ds_detalle->data["iva_ret"][$li_i];	
							$li_porimp=$io_report->ds_detalle->data["porimp"][$li_i];	
							$li_totimp=$io_report->ds_detalle->data["totimp"][$li_i];	

							$li_totalbaseimp=$li_totalbaseimp + $li_baseimp ;	
							$li_totalmontoimp=$li_totalmontoimp + $li_totimp;
							$li_totmontotdoc=$li_totmontotdoc+$li_montotdoc;
							$li_totmontoiva=$li_totmontoiva+$li_iva_ret;
							$li_iva_ret=number_format($li_iva_ret,2,",",".");	
							$li_baseimp=number_format($li_baseimp,2,",",".");			
							$li_porimp=number_format($li_porimp,4,",",".");			
							$li_totimp=number_format($li_totimp,2,",",".");							
							$li_montotdoc=number_format($li_montotdoc,2,",",".");							
							$la_data[$li_i]=array('fecfac'=>$ld_fecfac,'numfac'=>$ls_numfac,
												  'numref'=>$ls_numref,'baseimp'=>$li_baseimp,'iva_ret'=>$li_iva_ret,'porimp'=>$li_porimp,'totimp'=>$li_montotdoc,'numsop'=>$ls_numsop, );														
						  }																		 																						  
  						  $li_totalbaseimp= number_format($li_totalbaseimp,2,",","."); 
  						  $li_totalmontoimp= number_format($li_totmontotdoc,2,",","."); 
						  $li_totmontoiva= number_format($li_totmontoiva,2,",","."); 
						  uf_print_detalle($la_data,$li_totalbaseimp,$li_totalmontoimp,$li_totmontoiva,$ls_rifagenteret,&$io_pdf);
						  uf_print_sello($io_pdf);
						  unset($la_data);							 
						  
					}
				}
				$io_report->DS->reset_ds();
			}
			if($lb_valido) // Si no ocurrio ningún error
			{
				$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
				$io_pdf->ezStream(); // Mostramos el reporte
			}
			else  // Si hubo algún error
			{
				print("<script language=JavaScript>");
				print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
				//print(" close();");
				print("</script>");		
			}
			unset($io_pdf);
		}
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_cxp);
?> 