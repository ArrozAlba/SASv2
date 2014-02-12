<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//    REPORTE: Retencion de ISLR
	//  ORGANISMO: SATA
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
	function uf_print_encabezado_pagina($as_titulo,$as_agente,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_agente // Nombre de la Empresa
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 04/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_agente=strtoupper($as_agente);
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->rectangle(20,40,565,640);
		$io_pdf->setStrokeColor(0,0,0);
//		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->addJpegFromFile('../../shared/imagebank/logo_sata2.jpg',30,700,550,50); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(12,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText(30,685,10,$ls_agente); 
		$io_pdf->addText($tm,655,12,$as_titulo); // Agregar el título
		$io_pdf->line(20,640,585,640);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado($as_agente,$as_nombre,$as_rif,$as_nit,$as_telefono,$as_direccion,$as_consol,&$io_pdf)
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
		//	    		   as_consol    // Concepto de la retencion
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por recepción
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 05/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    	$ls_diremp=$_SESSION["la_empresa"]["direccion"];
    	$ls_rifemp=$_SESSION["la_empresa"]["rifemp"];
		$la_data[1]=array('name'=>'<b>DATOS DEL AGENTE DE RETENCIÓN</b>');
		$la_data[2]=array('name'=>'NOMBRE O RAZÓN SOCIAL:'."  ".$as_agente);
		$la_data[3]=array('name'=>'DOMICILIO FISCAL:'."  ".$ls_diremp);
		$la_data[4]=array('name'=>'RIF:'."  ".$ls_rifemp);
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>560))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->line(20,565,585,565);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$io_pdf->ezSetY(565);
		$la_data[1]=array('name'=>'<b>DATOS DEL AGENTE SUJETO A RETENCIÓN</b>');
		$la_data[2]=array('name'=>'CONCEPTO: '.$as_consol);
		$la_data[3]=array('name'=>'NOMBRE O RAZÓN SOCIAL: '.$as_nombre);
		$la_data[4]=array('name'=>'RIF: '.$as_rif.'                                                                              NIT: '.$as_nit);
		$la_data[5]=array('name'=>'');
		$la_data[6]=array('name'=>'');
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>560))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->line(20,480,585,480);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_encabezado
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($as_numsol,$as_concepto,$as_fechapago,$ad_monto,$ad_monret,$ad_porcentaje,$as_numcon,$ai_montotdoc,
							  $as_dended,$as_cheque,&$io_pdf)
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
		//	    		   ai_montotdoc // Monto Total del Documento
		//	    		   as_dended // Denominacion de la Deduccion
		//	    		   as_cheque //Numero de Cheque
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por recepción
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 05/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$io_pdf->ezSetY(475);
 /*     	$la_data[1]=array('solicitud'=>'<b><i>Factura:</i></b>'."  ".$as_numsol,'control'=>'<b><i>Nro Control: </i></b>'.$as_numcon);	
		$la_columna=array('solicitud'=>'','control'=>'');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
					     'fontSize' => 10,  // Tamaño de Letras
					     'showLines'=>0,    // Mostrar Líneas
					     'shaded'=>0,       // Sombra entre líneas
					     'width'=>530,     // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('solicitud'=>array('justification'=>'left','width'=>250),
						 			   'control'=>array('justification'=>'left','width'=>250))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);		       
		unset($la_data);
		unset($la_columna);
		unset($la_config);*/
		$la_data[1]=array('montotdoc'=>'<b>Monto Pagado o Abonado a la Cuenta</b>','monto'=>'<b>Cantidad Objeto de Retención</b>',
						  'porcentaje'=>'<b>% Aplicado</b>','dended'=>'<b>Tipo de Retencion</b>','retenido'=>'<b>Retencion I.S.L.R</b>');	
		$la_columna=array('montotdoc'=>'','monto'=>'','porcentaje'=>'','dended'=>'','retenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500,
					     'cols'=>array('montotdoc'=>array('justification'=>'center','width'=>120),
									   'monto'=>array('justification'=>'center','width'=>100),
									   'porcentaje'=>array('justification'=>'center','width'=>60),
									   'dended'=>array('justification'=>'center','width'=>190),
									   'retenido'=>array('justification'=>'center','width'=>80)));
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);		
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('montotdoc'=>$ai_montotdoc,'monto'=>$ad_monto,'porcentaje'=>$ad_porcentaje,'dended'=>$as_dended,'retenido'=>$ad_monret);	
	  	$la_columna=array('montotdoc'=>'','monto'=>'','porcentaje'=>'','dended'=>'','retenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500,
						  'cols'=>array('montotdoc'=>array('justification'=>'right','width'=>120),
						                'monto'=>array('justification'=>'right','width'=>100),
										'porcentaje'=>array('justification'=>'center','width'=>60),
									    'dended'=>array('justification'=>'left','width'=>190),
										'retenido'=>array('justification'=>'right','width'=>80)));
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);		
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('cheque'=>'Numero de Cheque: '.$as_cheque,'fecha'=>'Fecha: '.$as_fechapago);
	  	$la_columna=array('cheque'=>'','fecha'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500,
						  'cols'=>array('cheque'=>array('justification'=>'left','width'=>280),
										'fecha'=>array('justification'=>'left','width'=>270)));
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);		
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_firmas($as_nombre,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_firmas
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por recepción
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 05/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[0]=array('firma1'=>'','firma2'=>'');
		$la_data[1]=array('firma1'=>'','firma2'=>'');
		$la_data[2]=array('firma1'=>'','firma2'=>'');
		$la_data[3]=array('firma1'=>$as_nombre,'firma2'=>'');
		$la_data[4]=array('firma1'=>'','firma2'=>'');
		$la_data[5]=array('firma1'=>'____________________________','firma2'=>'____________________________');
		$la_data[6]=array('firma1'=>'Recibido Conforme','firma2'=>'Firma y Sello');
		$la_data[7]=array('firma1'=>'','firma2'=>'');
		$la_columna=array('firma1'=>'','firma2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('firma1'=>array('justification'=>'center','width'=>285), // Justificación y ancho de la columna
						 			   'firma2'=>array('justification'=>'center','width'=>285))); // Justificación y ancho de la columna
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
	$ls_titulo="<b>COMPROBANTE DE RETENCION</b>";
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
			$io_pdf->ezSetCmMargins(5.4,4,3,3);
			$lb_valido=true;
			$ls_codigoant="";
			for ($li_z=0;($li_z<$li_totrow)&&($lb_valido);$li_z++)
			{
				uf_print_encabezado_pagina($ls_titulo,$ls_agente,$io_pdf);
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
						}						 
						$ls_nit=$io_report->DS->data["nit"][$li_i];
						$ls_consol=$io_report->DS->data["consol"][$li_i];
						$ls_numdoc=$io_report->DS->data["numdoc"][$li_i];
						$ls_numref=$io_report->DS->data["numref"][$li_i];
						$ls_consol=$io_report->DS->data["consol"][$li_i];
						$ls_dended=$io_report->DS->data["dended"][$li_i];
						$ls_cheque=$io_report->DS->data["cheque"][$li_i];
						$ld_fecemidoc=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecemidoc"][$li_i]);
						$li_montotdoc=number_format($io_report->DS->data["montotdoc"][$li_i],2,',','.');  
						$li_monobjret=number_format($io_report->DS->data["monobjret"][$li_i],2,',','.');    
						$li_retenido=number_format($io_report->DS->data["retenido"][$li_i],2,',','.');  
						$li_porcentaje=number_format($io_report->DS->data["porcentaje"][$li_i],2,',','.');
						if($ls_codigo!=$ls_codigoant)
						{
							if($li_z>=1)
							{
								uf_print_firmas($ls_nombre,$io_pdf);
								$io_pdf->ezNewPage();  
							}
							uf_print_encabezado($ls_agente,$ls_nombre,$ls_rif,$ls_nit,$ls_telefono,$ls_direccion,$ls_consol,$io_pdf);
							$ls_codigoant=$ls_codigo;
						}
						uf_print_detalle($ls_numdoc,$ls_consol,$ld_fecemidoc,$li_monobjret,$li_retenido,$li_porcentaje,$ls_numref,
										 $li_montotdoc,$ls_dended,$ls_cheque,$io_pdf);
					}
				}	
			}
			if($lb_valido) // Si no ocurrio ningún error
			{
				uf_print_firmas($ls_nombre,$io_pdf);			  
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