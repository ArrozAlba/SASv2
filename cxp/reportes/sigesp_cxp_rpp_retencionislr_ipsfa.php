<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//    REPORTE: Retencion de ISLR
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
		// Fecha Creación: 03/07/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_retencionesislr.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$ls_rif_agente,&$io_pdf)
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
		$io_pdf->line(20,40,900,40);
		$io_pdf->setStrokeColor(0,0,0);		
		$io_pdf->rectangle(60,480,880,80);		
		$li_tm1=$io_pdf->getTextWidth(11,'INSTITUTO DE PREVISION SOCIAL DE LA FUERZA ARMADA');
		$tm1=450-($li_tm1/2);
		$io_pdf->addText($tm1,515,18,'INSTITUTO DE PREVISION SOCIAL DE LA FUERZA ARMADA'); // Agregar el título
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],100,485,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->rectangle(60,455,880,25);
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=500-($li_tm/2);		
		$io_pdf->addText($tm,465,11,$as_titulo); // Agregar el título
		$io_pdf->rectangle(60,425,880,30);
		$io_pdf->addText(850,445,9,date("d/m/Y")); // Agregar la Fecha	
		$io_pdf->addText(80,443,11,"<b>Nro. de R.I.F Agente de Retención</b>"); 
		$io_pdf->addText(80,430,11,$ls_rif_agente); 
		$io_pdf->addText(360,435,16,'DECRETO 1.808 ARTICULO NRO 9'); // Agregar la Fecha			
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado($as_agente,$as_nombre,$as_rif,$as_nit,$as_telefono,$as_direccion,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado
		//		   Access: private 
		//	    Arguments: as_agente // Nombre del agente de retención
		//	    		   as_nombre // Nombre del proveedor ó beneficiario
		//	    		   as_rif // Rif del proveedor ó beneficiario
		//	    		   as_nit // nit del proveedor ó beneficiario
		//	    		   as_telefono // Telefono del proveedor ó beneficiario
		//	    		   as_direccion // Dirección del proveedor ó beneficiario
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por recepción
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 05/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('name'=>'<b><i>DATOS DEL BENEFICIARIO</i></b>'."  ");
		$la_data[2]=array('name'=>'<b><i>NOMBRE:</i></b>'."  ".$as_nombre);
		$la_data[3]=array('name'=>'<b><i>NRO. RIF:</i></b>'."  ".$as_rif);		
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>890,
						 'xPos'=>505,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>881))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_encabezado
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($as_numdoc,$as_concepto,$as_fechapago,$ad_monto,$ad_monret,$ad_porcentaje,$as_numcon,
	                          $la_montotdoc,$ls_numsol, $ls_correlativo, &$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: as_numsol // Número de recepción
		//	    		   as_concepto // Concepto de la solicitud
		//	    		   as_fechapago // Fecha de la recepción
		//	    		   ad_monto // monto de la recepción
		//	    		   ad_monret // monto retenido
		//	    		   ad_porcentaje // porcentaje de retención
		//	    		   as_numcon // numero de referencia
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por recepción
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 05/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      	$ls_corrlativo[1]=array('correlativo'=>'<b>CORRELATIVO: </b>'.$ls_correlativo);	
		$la_columna=array('correlativo'=>'');	
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
					     'fontSize' => 10, // Tamaño de Letras
					     'showLines'=>2, // Mostrar Líneas
					     'shaded'=>2, // Sombra entre líneas
					     'shadeCol'=>array(0.9,0.9,0.9),
					     'shadeCol2'=>array(0.9,0.9,0.9),
					     'xOrientation'=>'center', // Orientación de la tabla
					     'colGap'=>1,
					     'width'=>500,
						 'xPos'=>501,
					     'cols'=>array('correlativo'=>array('justification'=>'right','width'=>880)));
		$io_pdf->ezTable($ls_corrlativo,$la_columna,'',$la_config);		
		unset($ls_corrlativo);
		unset($la_columna);
		unset($la_config);
		
		$la_data[1]=array('fecha'=>'<b>Fecha de Pago</b>',
		                  'solicitud'=>'<b>Nro Factura</b>',		                  
						  'control'=>'<b>Nro Control </b>',
		                  'monto'=>'<b>Base Imponible</b>',	
						  'porcentaje'=>'<b>% Retención</b>',						 
						  'monret'=>'<b>ISLR Retenido</b>',
						  'montoche'=>'<b>Monto del Cheque</b>',
						  'orden'=>'<b>Orden de Pago</b>');	
		$la_columna=array('fecha'=>'',
		                  'solicitud'=>'',
						  'control'=>'',
		                  'monto'=>'',
						  'porcentaje'=>'',						  
						  'monret'=>'',
						  'montoche'=>'',
						  'orden'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
					     'fontSize' => 10, // Tamaño de Letras
					     'showLines'=>2, // Mostrar Líneas
					     'shaded'=>2, // Sombra entre líneas
					     'shadeCol'=>array(0.9,0.9,0.9),
					     'shadeCol2'=>array(0.9,0.9,0.9),
					     'xOrientation'=>'center', // Orientación de la tabla
					     'colGap'=>1,
					     'width'=>500,
						 'xPos'=>501,
					     'cols'=>array('fecha'=>array('justification'=>'center','width'=>100),
						               'solicitud'=>array('justification'=>'center','width'=>100),
						  			   'control'=>array('justification'=>'center','width'=>150),							   
									   'monto'=>array('justification'=>'center','width'=>100),
									   'porcentaje'=>array('justification'=>'center','width'=>100),
									   'monret'=>array('justification'=>'center','width'=>80),
									   'montoche'=>array('justification'=>'center','width'=>100),
									   'orden'=>array('justification'=>'center','width'=>150)));
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);		
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('fecha'=>$as_fechapago,
		                  'solicitud'=>$as_numdoc,
						  'control'=>$as_numcon,
		                  'monto'=>$ad_monto,
						  'porcentaje'=>$ad_porcentaje,
						  'monret'=>$ad_monret,
						  'montoche'=>$la_montotdoc,						  
						  'orden'=>$ls_numsol);	
	  	$la_columna=array('fecha'=>'',
		                  'solicitud'=>'',
						  'control'=>'',
		                  'monto'=>'',
						  'porcentaje'=>'',						  
						  'monret'=>'',
						  'montoche'=>'',
						  'orden'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
					      'fontSize' => 10, // Tamaño de Letras
					      'showLines'=>2, // Mostrar Líneas
					      'shaded'=>0, // Sombra entre líneas
					      'shadeCol'=>array(0.9,0.9,0.9),
						  'shadeCol2'=>array(0.9,0.9,0.9),
						  'xOrientation'=>'center', // Orientación de la tabla
					      'colGap'=>1,
						  'width'=>500,
						  'xPos'=>501,
						  'cols'=>array('fecha'=>array('justification'=>'center','width'=>100),
						               'solicitud'=>array('justification'=>'center','width'=>100),
						  			   'control'=>array('justification'=>'center','width'=>150),
									   'monto'=>array('justification'=>'center','width'=>100),
									   'porcentaje'=>array('justification'=>'center','width'=>100),									   
									   'monret'=>array('justification'=>'center','width'=>80),
									   'montoche'=>array('justification'=>'center','width'=>100),
									   'orden'=>array('justification'=>'center','width'=>150)));
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);		
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_firmas(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_firmas
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por recepción
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 05/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data_1[1]=array('firma1'=>'ELABORADO POR ','firma2'=>'JEFE DE LA UNIDAD ','firma3'=>'TESORERIA I.P.S.F.A');
		$la_columna=array('firma1'=>'',
		                  'firma2'=>'',
						  'firma3'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>505,
				 		 'cols'=>array('firma1'=>array('justification'=>'center','width'=>350), 
						 			   'firma2'=>array('justification'=>'center','width'=>200),
									   'firma3'=>array('justification'=>'center','width'=>330))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_1,$la_columna,'',$la_config);
		unset ($la_data_1);
		unset ($la_columna);
		unset ($la_config);
		
		$la_data_3[1]=array('firma1'=>' ','firma2'=>'','firma3'=>'');
		$la_data_3[2]=array('firma1'=>' ','firma2'=>'','firma3'=>'');
		$la_data_3[3]=array('firma1'=>'','firma2'=>'','firma3'=>'');
		$la_data_3[4]=array('firma1'=>'','firma2'=>'','firma3'=>'');
		$la_columna=array('firma1'=>'',
		                  'firma2'=>'',
						  'firma3'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>505,
				 		 'cols'=>array('firma1'=>array('justification'=>'center','width'=>350), 
						 			   'firma2'=>array('justification'=>'center','width'=>200),
									   'firma3'=>array('justification'=>'center','width'=>330))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_3,$la_columna,'',$la_config);
		unset ($la_data_3);
		unset ($la_columna);
		unset ($la_config);
		
		$la_data_2[1]=array('firma1'=>'RECIBE CONFORME','firma2'=>'SELLO I.P.S.F.A');
		$la_columna=array('firma1'=>'','firma2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>505,
				 		 'cols'=>array('firma1'=>array('justification'=>'center','width'=>550), // Justificación y ancho de la columna
						 			   'firma2'=>array('justification'=>'center','width'=>330))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_2,$la_columna,'',$la_config);
        unset ($la_data_2);
		unset ($la_columna);
		unset ($la_config);
		
		$la_data[1]=array('firma1'=>'','firma2'=>'');
		$la_data[2]=array('firma1'=>'NOMBRE Y APELLIDO:____________________________','firma2'=>'');
		$la_data[3]=array('firma1'=>'CEDULA DE IDENTIDAD:__________________________','firma2'=>'');
		$la_data[4]=array('firma1'=>'FECHA EN QUE SE RECIBE COMPROBANTE:__________________________','firma2'=>'');
		$la_data[5]=array('firma1'=>'FIRMA:__________________________','firma2'=>'');
		$la_columna=array('firma1'=>'','firma2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>505,
				 		 'cols'=>array('firma1'=>array('justification'=>'rigth','width'=>550), // Justificación y ancho de la columna
						 			   'firma2'=>array('justification'=>'rigth','width'=>330))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
        unset ($la_data);
		unset ($la_columna);
		unset ($la_config);

		/*$io_pdf->rectangle(450,60,110,90); 
		$io_pdf->addText(485,66,10,'<b>SELLO</b>');*/
	}// end function uf_print_firmas
	//--------------------------------------------------------------------------------------------------------------------------------

	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_cxp_class_report.php");
	$io_report=new sigesp_cxp_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>COMPROBANTE DE RETENCION DE IMPUESTO SOBRE LA RENTA</b>";
    $ls_agente=$_SESSION["la_empresa"]["nombre"];
	$ls_rif_agente=$_SESSION["la_empresa"]["rifemp"]; 
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_comprobantes=$io_fun_cxp->uf_obtenervalor_get("comprobantes","");
	$ls_procedencias=$io_fun_cxp->uf_obtenervalor_get("procedencias","");
	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_cxp_class_reportbsf.php");
		$io_report=new sigesp_cxp_class_reportbsf();
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$la_procedencias=split('<<<',$ls_procedencias);
		$la_comprobantes=split('<<<',$ls_comprobantes);
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
			$io_pdf=new Cezpdf('LEGAL','landscape');
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
			$io_pdf->ezSetCmMargins(6.55,4,3,3);
			$lb_valido=true;
			$ls_codigoant="";
			for ($li_z=0;($li_z<$li_totrow)&&($lb_valido);$li_z++)
			{
				uf_print_encabezado_pagina($ls_titulo,$ls_rif_agente,$io_pdf);
				$ls_numsol=$la_datos[$li_z];
				$ls_procede=$la_procedencias[$li_z];  
				switch ($ls_procede)
				{
					case "SCBBCH":
						$lb_valido= $io_report->uf_retencionesislr_scb($ls_numsol);  
					break;
					case "INT":
						$lb_valido= $io_report->uf_retencionesislr_int($ls_numsol);
					break;
					default:
						$lb_valido= $io_report->uf_retencionesislr_cxp($ls_numsol);
					break;
				}
				if($lb_valido)
				{
					$li_total=$io_report->DS->getRowCount("numdoc");
					for($li_i=1;($li_i<=$li_total);$li_i++)
					{
						$ls_codpro=$io_report->DS->data["cod_pro"][$li_i];
						$ls_cedbene=$io_report->DS->data["ced_bene"][$li_i];
						if($ls_codpro!="----------")
						{
							$ls_tipproben="P";
						}
						else
						{
							$ls_tipproben="B";
						}
						if($ls_tipproben=="P")
						{
							$ls_codigo=$io_report->DS->data["cod_pro"][$li_i];
							$ls_nombre=$io_report->DS->data["proveedor"][$li_i];
							$ls_telefono=$io_report->DS->data["telpro"][$li_i];
							$ls_direccion=$io_report->DS->data["dirpro"][$li_i];
							$ls_rif=$io_report->DS->data["rifpro"][$li_i];
						}
						else
						{
							$ls_codigo=$io_report->DS->data["ced_bene"][$li_i];
							$ls_nombre=$io_report->DS->data["beneficiario"][$li_i];
							$ls_telefono=$io_report->DS->data["telbene"][$li_i];
							$ls_direccion=$io_report->DS->data["dirbene"][$li_i];
							$ls_rif=$io_report->DS->data["rifben"][$li_i];
							$ls_numsol=$io_report->DS->data["numsol"][$li_i];
						}						 
						$ls_nit=$io_report->DS->data["nit"][$li_i];
						$ls_consol=$io_report->DS->data["consol"][$li_i];
						$ls_numdoc=$io_report->DS->data["numdoc"][$li_i];// numero de la orden de pago
						$ls_numref=$io_report->DS->data["numref"][$li_i];
						$ld_fecemidoc=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecemidoc"][$li_i]);
						$li_montotdoc=number_format($io_report->DS->data["montotdoc"][$li_i],2,',','.');  
						$li_monobjret=number_format($io_report->DS->data["monobjret"][$li_i],2,',','.');    
						$li_retenido=number_format($io_report->DS->data["retenido"][$li_i],2,',','.');  
						$li_porcentaje=number_format($io_report->DS->data["porcentaje"][$li_i],2,',','.');
						$ls_correlativo=$io_report->DS->data["numcmpislr"][$li_i];						
						if($ls_codigo!=$ls_codigoant)
						{
							if($li_z>=1)
							{
								uf_print_firmas($io_pdf);
								$io_pdf->ezNewPage();  
							}
							uf_print_encabezado($ls_agente,$ls_nombre,$ls_rif,$ls_nit,$ls_telefono,$ls_direccion,$io_pdf);
							$ls_codigoant=$ls_codigo;							
						}
						uf_print_detalle($ls_numdoc,$ls_consol,$ld_fecemidoc,$li_monobjret,$li_retenido,
						                 $li_porcentaje,$ls_numref,$li_montotdoc, $ls_numsol, $ls_correlativo,$io_pdf);
					}
				}
			}
			uf_print_firmas($io_pdf);			  
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