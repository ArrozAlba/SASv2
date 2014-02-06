<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//    REPORTE: Retencion Municipales
	//  ORGANISMO: Ninguno en particular
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
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(14,$as_titulo);
		$tm=365-($li_tm/2);
		$io_pdf->addText($tm,565,14,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(9,"ARTICULO 114 ORDENANZA SOBRE ACTIVIDADES ECONOMICAS");
		$tm=290-($li_tm/2);
		$io_pdf->addText($tm,555,9,"ARTICULO 114 ORDENANZA SOBRE ACTIVIDADES ECONOMICAS"); // Agregar el título

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
		$io_pdf->setStrokeColor(0,0,0);
		if($ai_estcmpret==2)
		{
		    $io_pdf->Rectangle(45,495,180,30);		
			$io_pdf->addText(90,505,15,"<b> ANULADO </b>"); 
		}	
		$io_pdf->ezSetY(552);
		$la_data[1]=array('name'=>'<b>NRO COMPROBANTE </b>');
		$la_data[2]=array('name'=>$as_numcon);				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>527, // Orientación de la tabla
						 'width'=>140, // Ancho de la tabla						 
						 'maxWidth'=>140,
						 'cols'=>array('name'=>array('justification'=>'center','width'=>140))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);								 

		$io_pdf->ezSetY(552);
		$la_data[1]=array('name'=>'<b>FECHA</b>');
		$la_data[2]=array('name'=>date("d/m/Y"));				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>670, // Orientación de la tabla
						 'width'=>90, // Ancho de la tabla						 
						 'maxWidth'=>90,
						 'cols'=>array('name'=>array('justification'=>'center','width'=>90))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);

		$io_pdf->ezSetY(515);
		$la_data[1]=array('name'=>'<b>NOMBRE Ó RAZÓN SOCIAL DEL AGENTE DE RETENCIÓN</b>');
		$la_data[2]=array('name'=>$as_agenteret);				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>190, // Orientación de la tabla
						 'width'=>310, // Ancho de la tabla						 
						 'maxWidth'=>310,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>310))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);								 

		$io_pdf->ezSetY(515);
		$la_data[1]=array('name'=>'<b>REGISTRO DE INFORMACIÓN FISCAL DEL AGENTE DE RETENCIÓN (R.I.F.)</b>');
		$la_data[2]=array('name'=>$as_rifagenteret);				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>515, // Orientación de la tabla
						 'width'=>320, // Ancho de la tabla						 
						 'maxWidth'=>320,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>320))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);								 

		$io_pdf->ezSetY(515);
		$la_data[1]=array('name'=>'<b>PERÍODO FISCAL</b>');
		$la_data[2]=array('name'=>$as_perfiscal);				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>730, // Orientación de la tabla
						 'width'=>90, // Ancho de la tabla						 
						 'maxWidth'=>90,
						 'cols'=>array('name'=>array('justification'=>'center','width'=>90))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);								 

		$io_pdf->ezSetY(480);
		$la_data[1]=array('name'=>'<b>LICENCIA FUNCIONAMIENTO AGENTE DE RETENCIÓN</b>');
		$la_data[2]=array('name'=>$as_licagenteret);				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>190, // Orientación de la tabla
						 'width'=>310, // Ancho de la tabla						 
						 'maxWidth'=>310,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>310))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);								 

		$io_pdf->ezSetY(445);
		$la_data[1]=array('name'=>'<b>DIRECCIÓN FISCAL DEL AGENTE DE RETENCIÓN</b>');
		$la_data[2]=array('name'=>$as_diragenteret);				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>405, // Orientación de la tabla
						 'width'=>740, // Ancho de la tabla						 
						 'maxWidth'=>740,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>740))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);								 

		$io_pdf->ezSetY(410);
		$la_data[1]=array('name'=>'<b>NOMBRE Ó RAZÓN SOCIAL DEL SUJETO RETENIDO</b>');
		$la_data[2]=array('name'=>$as_nomsujret);				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>190, // Orientación de la tabla
						 'width'=>310, // Ancho de la tabla						 
						 'maxWidth'=>310,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>310))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);								 

		$io_pdf->ezSetY(410);
		$la_data[1]=array('name'=>'<b>REGISTRO DE INFORMACIÓN FISCAL DEL SUJETO RETENIDO (R.I.F.)</b>');
		$la_data[2]=array('name'=>$as_rif);				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>515, // Orientación de la tabla
						 'width'=>320, // Ancho de la tabla						 
						 'maxWidth'=>320,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>320))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);								 

		$io_pdf->ezSetY(375);
		$la_data[1]=array('name'=>'<b>LICENCIA FUNCIONAMIENTO SUJETO RETENIDO</b>');
		$la_data[2]=array('name'=>$as_numlic);				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>190, // Orientación de la tabla
						 'width'=>310, // Ancho de la tabla						 
						 'maxWidth'=>310,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>310))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);								 
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------			
			
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,$ai_totbasimp,$ai_totmonimp,$as_rifagenteret,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: la_data // Arreglo de datos a imprimir
		//	    		   ai_totbasimp // Total de la base imponible
		//	    		   ai_totmonimp // Total monto imponible
		//	    		   as_rifagenteret // Rif del Agente de Retención
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 14/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(340);
		$la_data1[1]=array('numero'=>'<b>Nº</b>',
						  'numsop'=>'<b>Orden de Pago</b>',
						  'fecfac'=>'<b>Fecha Factura</b>',
						  'numfac'=>'<b>Número de Factura</b>',
  						  'numref'=>'<b>Número Control de Factura</b>',		
						  'baseimp'=>'<b>Monto de la Operación</b>',
						  'porimp'=>'<b>Alícuota</b>',
						  'totimp'=>'<b>Impuesto Retenido</b>');
		$la_columna=array('numero'=>'','numsop'=>'','fecfac'=>'','numfac'=>'','numref'=>'','baseimp'=>'','porimp'=>'','totimp'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>740, // Ancho Mínimo de la tabla
						 'xPos'=>405, // Orientación de la tabla
						 'cols'=>array('numero'=>array('justification'=>'center','width'=>50), // Justificacion y ancho de la columna
									   'numsop'=>array('justification'=>'center','width'=>110),
						 			   'fecfac'=>array('justification'=>'center','width'=>110), // Justificacion y ancho de la columna
						 			   'numfac'=>array('justification'=>'center','width'=>110), // Justificacion y ancho de la columna
									   'numref'=>array('justification'=>'center','width'=>110), // Justificacion y ancho de la columna
						 			   'baseimp'=>array('justification'=>'center','width'=>100),
						 			   'porimp'=>array('justification'=>'center','width'=>50),
   						 			   'totimp'=>array('justification'=>'center','width'=>100))); 
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		
		$la_columna=array('numero'=>'','numsop'=>'','fecfac'=>'','numfac'=>'','numref'=>'','baseimp'=>'','porimp'=>'','totimp'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>740, // Ancho Mínimo de la tabla
						 'xPos'=>405, // Orientación de la tabla
						 'cols'=>array('numero'=>array('justification'=>'center','width'=>50), // Justificacion y ancho de la columna
									   'numsop'=>array('justification'=>'center','width'=>110),
						 			   'fecfac'=>array('justification'=>'center','width'=>110), // Justificacion y ancho de la columna
						 			   'numfac'=>array('justification'=>'center','width'=>110), // Justificacion y ancho de la columna
									   'numref'=>array('justification'=>'center','width'=>110), // Justificacion y ancho de la columna
						 			   'baseimp'=>array('justification'=>'right','width'=>100),
						 			   'porimp'=>array('justification'=>'right','width'=>50),
   						 			   'totimp'=>array('justification'=>'right','width'=>100))); 
		$io_pdf->ezSetDy(-0.5);
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		
		$la_data1[1]=array('total'=>'<b>Totales</b>','monto'=>'<b>'.$ai_totbasimp.'</b>','imponible'=>'<b>'.$ai_totmonimp.'</b>');
		$la_columna=array('total'=>'','monto'=>'','imponible'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>360, // Ancho de la tabla
						 'maxWidth'=>360, // Ancho Mínimo de la tabla
						 'xPos'=>595, // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'center','width'=>110), // Justificacion y ancho de la columna
   						 			   'monto'=>array('justification'=>'right','width'=>100),
   						 			   'imponible'=>array('justification'=>'right','width'=>150))); 
		$io_pdf->ezSetDy(-0.5);
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		
		$la_data1[1]=array('firma'=>'_________________________________________________');	
		$la_data1[2]=array('firma'=>'FIRMA Y SELLO DEL AGENTE DE RETENCION');	
		$la_data1[3]=array('firma'=>'RIF: '.$as_rifagenteret);	
		$la_columna=array('firma'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>200, // Ancho de la tabla
						 'maxWidth'=>200, // Ancho Mínimo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('firma'=>array('justification'=>'center','width'=>250))); 
		$io_pdf->ezSetDy(-100);
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_detalle
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
	if($ls_tiporeporte==1)
	{
		$ls_titulo="<b>COMPROBANTE DE RETENCION DEL IMPUESTO MUNICIPAL Bs.F.</b>";
	}
	else
	{
		$ls_titulo="<b>COMPROBANTE DE RETENCION DEL IMPUESTO MUNICIPAL Bs.</b>";
	}
    $ls_agente=$_SESSION["la_empresa"]["nombre"];
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_comprobantes = $io_fun_cxp->uf_obtenervalor_get("comprobantes","");
	$ls_mes          = $io_fun_cxp->uf_obtenervalor_get("mes","");
	$ls_anio         = $io_fun_cxp->uf_obtenervalor_get("anio","");
	$ls_agenteret    = $_SESSION["la_empresa"]["nombre"];
	$ls_rifagenteret = $_SESSION["la_empresa"]["rifemp"];
	$ls_diragenteret = $_SESSION["la_empresa"]["direccion"];
	$ls_licagenteret = $_SESSION["la_empresa"]["numlicemp"];
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
			$io_pdf->ezSetCmMargins(7,4,3,3);
			$io_pdf->ezStartPageNumbers(406,30,10,'','',1);//Insertar el número de página.
			$lb_valido=true;
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
						$ls_numcon    = $io_report->DS->data["numcom"][$li_i];		 								
						$ls_codret    = $io_report->DS->data["codret"][$li_i];			   
						$ls_fecrep    = $io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecrep"][$li_i]);
						$ls_perfiscal = $io_report->DS->data["perfiscal"][$li_i];						
						$ls_codsujret = $io_report->DS->data["codsujret"][$li_i];			     
						$ls_nomsujret = $io_report->DS->data["nomsujret"][$li_i];	
						$ls_rif       = $io_report->DS->data["rif"][$li_i];	
						$ls_dirsujret = $io_report->DS->data["dirsujret"][$li_i];		
						$li_estcmpret = $io_report->DS->data["estcmpret"][$li_i];	
						$ls_numlic    = $io_report->DS->data["numlic"][$li_i];									
					}											
					uf_print_cabecera($ls_numcon,$ls_fecrep,$ls_agenteret,$ls_rifagenteret,$ls_perfiscal,$ls_licagenteret,
									  $ls_diragenteret,$ls_nomsujret,$ls_rif,$ls_numlic,$li_estcmpret,&$io_pdf);
					$lb_valido=$io_report->uf_retencionesmunicipales_detalles($ls_numcom);
					if($lb_valido)
					{
						$li_totalbaseimp=0;
						$li_totalmontoimp=0;
						$li_total=$io_report->ds_detalle->getRowCount("numfac");			   
						for($li_i=1;$li_i<=$li_total;$li_i++)
						{
							$ls_numsop  = $io_report->ds_detalle->data["numsop"][$li_i];					
							$ld_fecfac  = $io_funciones->uf_convertirfecmostrar($io_report->ds_detalle->data["fecfac"][$li_i]);	
							$ls_numfac  = $io_report->ds_detalle->data["numfac"][$li_i];	
							$ls_numref  = $io_report->ds_detalle->data["numcon"][$li_i];	              
							$li_baseimp = $io_report->ds_detalle->data["basimp"][$li_i];	
							$li_porimp  = $io_report->ds_detalle->data["porimp"][$li_i];	
							$li_totimp  = $io_report->ds_detalle->data["totimp"][$li_i];	

							$li_totalbaseimp  = $li_totalbaseimp + $li_baseimp ;	
							$li_totalmontoimp = $li_totalmontoimp + $li_totimp;	
							$li_baseimp       = number_format($li_baseimp,2,",",".");			
							$li_porimp		  = number_format($li_porimp,4,",",".");			
							$li_totimp		  = number_format($li_totimp,2,",",".");							
							$la_data[$li_i]   = array('numero'=>$li_i,'numsop'=>$ls_numsop, 'fecfac'=>$ld_fecfac,'numfac'=>$ls_numfac,
												  'numref'=>$ls_numref,'baseimp'=>$li_baseimp,'porimp'=>$li_porimp,'totimp'=>$li_totimp);														
						  }																		 																						  
  						  $li_totalbaseimp  = number_format($li_totalbaseimp,2,",","."); 
  						  $li_totalmontoimp = number_format($li_totalmontoimp,2,",","."); 
						  uf_print_detalle($la_data,$li_totalbaseimp,$li_totalmontoimp,$ls_rifagenteret,&$io_pdf); 						 						  
						  unset($la_data);							 
					}
				}
				$io_report->DS->reset_ds();
				if($li_z<($li_totrow-1))
				{
					$io_pdf->ezNewPage(); 					  
				}		

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
				print(" close();");
				print("</script>");		
			}
			unset($io_pdf);
		}
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_cxp);
?> 