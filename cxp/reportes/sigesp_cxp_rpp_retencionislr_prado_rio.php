<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//    REPORTE: Retencion de ISLR
	//  ORGANISMO: Hotel Prado Río - Edo. Mérida.
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
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,630,11,$as_titulo); // Agregar el título
		$io_pdf->addText(500,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(506,743,7,date("h:i a")); // Agregar la Hora
		$io_pdf->addText(250,660,13,"<b>".$_SESSION["la_empresa"]["nombre"]."</b>"); // Agregar el título
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado($as_agente,$as_nombre,$as_rif,$as_direccion,$as_consol,&$io_pdf)
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
		$la_data[1]=array('name'=>'<b>DATOS DEL AGENTE DE RETENCIÓN</b>');
		$la_data[2]=array('name'=>'<b>Nombre o Razón Social: </b>'.$as_agente);
		$la_data[3]=array('name'=>'<b>Domicilio Fiscal: </b>'.$as_direccion);
		$la_data[4]=array('name'=>'<b>RIF: </b>'.$_SESSION["la_empresa"]["rifemp"]);
		$la_data[5]=array('name'=>'');
		$la_data[6]=array('name'=>'');
		$la_data[7]=array('name'=>'<b>DATOS DEL AGENTE SUJETO A RETENCIÓN</b>');
		$la_data[8]=array('name'=>'<b>Concepto: </b>'.$as_consol);
		$la_data[9]=array('name'=>'<b>Nombre o Razón Social: </b>'.$as_nombre);
		$la_data[10]=array('name'=>'<b>RIF: </b>'.$as_rif);
	    
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>500))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_encabezado
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($as_fechapago,$ad_montotdoc,$ad_monobjret,$ad_montotret,$ad_porcentaje,$as_dended,$as_numche,$as_numfac,&$io_pdf)
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

		$la_data[1]=array('monto'=>'Monto Pagado o Abonado a la Cuenta',
		                  'cantidad'=>'Cantidad Objeto de Retención',
						  'porcentaje'=>'% Aplicado',
						  'tipo'=>'Tipo de Retención',
						  'retencion'=>'Retención ISLR Bs.');
		$la_columna=array('monto'=>'','cantidad'=>'','porcentaje'=>'','tipo'=>'','retencion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
					     'fontSize'=>9,  // Tamaño de Letras
					     'showLines'=>2,    // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
					     'width'=>550,     // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('monto'=>array('justification'=>'center','width'=>100),
						 			   'cantidad'=>array('justification'=>'center','width'=>100),
									   'porcentaje'=>array('justification'=>'center','width'=>50),
									   'tipo'=>array('justification'=>'center','width'=>190),
									   'retencion'=>array('justification'=>'center','width'=>110))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);		       
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		
		$la_data[1]=array('monto'=>$ad_montotdoc,'cantidad'=>$ad_monobjret,'porcentaje'=>$ad_porcentaje,'tipo'=>$as_dended,'retencion'=>$ad_montotret);
		$la_columna=array('monto'=>'','cantidad'=>'','porcentaje'=>'','tipo'=>'','retencion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
					     'fontSize'=>9,  // Tamaño de Letras
					     'showLines'=>2,    // Mostrar Líneas
					     'shaded'=>0,       // Sombra entre líneas
					     'width'=>550,     // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('monto'=>array('justification'=>'right','width'=>100),
						 			   'cantidad'=>array('justification'=>'right','width'=>100),
									   'porcentaje'=>array('justification'=>'right','width'=>50),
									   'tipo'=>array('justification'=>'left','width'=>190),
									   'retencion'=>array('justification'=>'right','width'=>110))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);		       
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	
		$la_data[1]=array('cheque'=>'Número de Cheque:'.$as_numche,'fecha'=>'Fecha: '.$as_fechapago,'facturas'=>'Facturas: '.$as_numfac);
		$la_columna=array('cheque'=>'','fecha'=>'','facturas'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
					     'fontSize'=>8,  // Tamaño de Letras
					     'showLines'=>2,    // Mostrar Líneas
					     'shaded'=>0,       // Sombra entre líneas
					     'width'=>550,     // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('cheque'=>array('justification'=>'left','width'=>200),
						 			   'fecha'=>array('justification'=>'left','width'=>100),
									   'facturas'=>array('justification'=>'left','width'=>250))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);		       
		unset($la_data);
		unset($la_columna);
		unset($la_config);

		$la_data[1]=array('column'=>'');
		$la_columna=array('column'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
					     'fontSize'=>9,  // Tamaño de Letras
					     'showLines'=>0,    // Mostrar Líneas
					     'shaded'=>0,       // Sombra entre líneas
					     'width'=>550,     // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('column'=>array('justification'=>'left','width'=>550))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);		       
		unset($la_data);
		unset($la_columna);
		unset($la_config);
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
		$la_data[0]=array('firma1'=>'');
		$la_data[1]=array('firma1'=>'');
		$la_data[2]=array('firma1'=>'____________________________');
		$la_data[3]=array('firma1'=>'FIRMA Y SELLO');
		$la_data[4]=array('firma1'=>'');
		$la_columna=array('firma1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('firma1'=>array('justification'=>'center','width'=>250))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
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
	$ls_titulo="<b>CONSTANCIA DE RETENCIÓN (ISLR)</b>";
    $ls_agente=$_SESSION["la_empresa"]["nombre"];
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
			$io_pdf=new Cezpdf('LETTER','portrait');
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
			$io_pdf->ezSetCmMargins(7,4,3,3);
			$lb_valido=true;
			$ls_codigoant="";
			for ($li_z=0;($li_z<$li_totrow)&&($lb_valido);$li_z++)
			{
				uf_print_encabezado_pagina($ls_titulo,$io_pdf);
				$ls_numsol=$la_datos[$li_z];
				$ls_procede=$la_procedencias[$li_z];  
				if($ls_procede=="SCBBCH")
				{
					$lb_valido=$io_report->uf_retencionesislr_scb($ls_numsol);  
				}
				else
				{
					$lb_valido=$io_report->uf_retencionesislr_cxp($ls_numsol);
				}
				if($lb_valido)
				{
					$li_total=$io_report->DS->getRowCount("numdoc");
					for($li_i=1;($li_i<=$li_total);$li_i++)
					{
						$ls_tipproben=$io_report->DS->data["tipproben"][$li_i];
						if($ls_tipproben=="P")
						{
							$ls_codigo=$io_report->DS->data["cod_pro"][$li_i];
							$ls_nombre=$io_report->DS->data["proveedor"][$li_i];
							$ls_direccion=$io_report->DS->data["dirpro"][$li_i];
							$ls_rif=$io_report->DS->data["rifpro"][$li_i];
						}
						else
						{
							$ls_codigo=$io_report->DS->data["ced_bene"][$li_i];
							$ls_nombre=$io_report->DS->data["beneficiario"][$li_i];
							$ls_direccion=$io_report->DS->data["dirbene"][$li_i];
							$ls_rif=$io_report->DS->data["rifben"][$li_i];
						}						 
						$ls_consol     = $io_report->DS->data["consol"][$li_i];
						$ld_fecemidoc  = $io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecemidoc"][$li_i]);
						$li_montotdoc  = number_format($io_report->DS->data["montotdoc"][$li_i],2,',','.');  
						$li_monobjret  = number_format($io_report->DS->data["monobjret"][$li_i],2,',','.');    
						$li_retenido   = number_format($io_report->DS->data["retenido"][$li_i],2,',','.');  
						$li_porcentaje = number_format($io_report->DS->data["porcentaje"][$li_i],2,',','.');
						$ls_dended     = $io_report->DS->data["dended"][$li_i];
						$ls_numche     = $io_report->DS->data["cheque"][$li_i];
						$ls_numfac     = $io_report->DS->data["numdoc"][$li_i];
						if($ls_codigo!=$ls_codigoant)
						{
							if($li_z>=1)
							{
								uf_print_firmas($io_pdf);
								$io_pdf->ezNewPage();  
							}
							uf_print_encabezado($ls_agente,$ls_nombre,$ls_rif,$ls_direccion,$ls_consol,$io_pdf);
							$ls_codigoant=$ls_codigo;
						}
						uf_print_detalle($ld_fecemidoc,$li_montotdoc,$li_monobjret,$li_retenido,$li_porcentaje,$ls_dended,$ls_numche,$ls_numfac,$io_pdf);
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