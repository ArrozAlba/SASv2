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
		$io_pdf->rectangle(20,650,558,30);
		$io_pdf->rectangle(20,40,558,640);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,660,11,$as_titulo); // Agregar el título
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado($as_agente,$as_nomsujret,$as_rif,$as_nit,$as_direccion,&$io_pdf)
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
		$io_pdf->ezSetY(630);
		
		$la_data=array(array('name'=>'<b>DATOS DEL AGENTE DE RETENCION</b>'),
					   array('name'=>'NOMBRE O RAZON SOCIAL:'."  ".'<b>'.$as_agente.'</b>'),
					   array('name'=>'RIF:'."  ".$_SESSION["la_empresa"]["rifemp"]."                                                        NIT: ".$_SESSION["la_empresa"]["nitemp"]),
					   array('name'=>'DOMICILIO FISCAL:'."  ".$_SESSION["la_empresa"]["direccion"]));
		
		 
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras						 
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas						 
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'lef','width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		
		
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data=array(array('name'=>'<b>DATOS DEL AGENTE SUJETO A RETENCION</b>'),
					   array('name'=>'NOMBRE O RAZON SOCIAL: '."  ".'<b>'.$as_nomsujret.'</b>'),
					   array('name'=>'RIF:'."  ".$as_rif."                                                        NIT:  ".$as_nit),
					   array('name'=>'DOMICILIO FISCAL:'."  ".$as_direccion));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras						 
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas						 
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'lef','width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_encabezado
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($ai_monto,$ai_cantidad,$ai_porcentaje,$as_tiporet, $ai_totalret, $as_numche,$ad_fecha,&$io_pdf)
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
      	$io_pdf->ezSetY(500);
		
		$la_data1[1]=array('c1'=>'<b>MONTO PAGADO O ABONADO EN CUENTA</b>',
						  'c2'=>'<b>CANTIDAD OBJETO DE RETENCION</b>',
						  'c3'=>'<b>% APLICADO</b>',
						  'c4'=>'<b>TIPO DE RETENCION</b>',
  						  'c5'=>'<b>TOTAL IMPUESTO RETENIDO Bs.</b>');
		$la_columna=array('c1'=>'',
						  'c2'=>'',
						  'c3'=>'',
						  'c4'=>'',
  						  'c5'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Mínimo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('c1'=>array('justification'=>'center','width'=>120), // Justificacion y ancho de la columna
									   'c2'=>array('justification'=>'center','width'=>100),
						 			   'c3'=>array('justification'=>'center','width'=>60), // Justificacion y ancho de la columna
						 			   'c4'=>array('justification'=>'center','width'=>120), // Justificacion y ancho de la columna
									   'c5'=>array('justification'=>'center','width'=>100))); 
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		
		
		$la_data1[1]=array('c1'=>$ai_monto,
						  'c2'=>$ai_cantidad,
						  'c3'=>$ai_porcentaje,
						  'c4'=>$as_tiporet,
  						  'c5'=>$ai_totalret);
		$la_columna=array('c1'=>'',
						  'c2'=>'',
						  'c3'=>'',
						  'c4'=>'',
  						  'c5'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Mínimo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('c1'=>array('justification'=>'center','width'=>120), // Justificacion y ancho de la columna
									   'c2'=>array('justification'=>'center','width'=>100),
						 			   'c3'=>array('justification'=>'center','width'=>60), // Justificacion y ancho de la columna
						 			   'c4'=>array('justification'=>'center','width'=>120), // Justificacion y ancho de la columna
									   'c5'=>array('justification'=>'center','width'=>100))); 
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		
		$la_data1[1]=array('c1'=>'<b>NUMERO DE CHEQUE:</b>    '.$as_numche,
						  'c2'=>'<b>FECHA:</b>    '.$ad_fecha);
		$la_columna=array('c1'=>'',
						  'c2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Mínimo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('c1'=>array('justification'=>'left','width'=>250), // Justificacion y ancho de la columna
									   'c2'=>array('justification'=>'left','width'=>250))); 
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		
	
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_firmas($as_agente,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_firmas
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por recepción
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 05/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(260);
		
		
		$la_data[0]=array('firma1'=>'','firma2'=>'');
		$la_data[1]=array('firma1'=>'','firma2'=>'');
		$la_data[2]=array('firma1'=>'____________________________','firma2'=>'____________________________');
		$la_data[3]=array('firma1'=>'RECIBIDO CONFORME','firma2'=>'FIRMA Y SELLO');
		$la_data[4]=array('firma1'=>'','firma2'=>$as_agente);
		$la_columna=array('firma1'=>'','firma2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('firma1'=>array('justification'=>'center','width'=>250), // Justificación y ancho de la columna
						 			   'firma2'=>array('justification'=>'center','width'=>250))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_firmas
	//--------------------------------------------------------------------------------------------------------------------------------

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
	
	$ls_titulo="<b>RETENCION 1 X 1.000</b>";
	
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
			$io_pdf=new Cezpdf('LETTER','portrait');
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
			$io_pdf->ezSetCmMargins(7,4,3,3);
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
						$ls_numcon=$io_report->DS->data["numcom"][$li_i];		 								
						$ls_codret=$io_report->DS->data["codret"][$li_i];			   
						$ls_nomsujret=$io_report->DS->data["nomsujret"][$li_i];	
						$ls_rif=$io_report->DS->data["rif"][$li_i];	
						$ls_nit=$io_report->DS->data["nit"][$li_i];	
						$ls_dirsujret=$io_report->DS->data["dirsujret"][$li_i];		
						$li_estcmpret=$io_report->DS->data["estcmpret"][$li_i];	
						$ls_numlic=$io_report->DS->data["numlic"][$li_i];									
					}											
					uf_print_encabezado($ls_agente,$ls_nomsujret,$ls_rif,$ls_nit,$ls_dirsujret,&$io_pdf);
					$lb_valido=$io_report->uf_retencionesmunicipales_detalles($ls_numcom);
					if($lb_valido)
					{
						$li_totalbaseimp=0;
						$li_totalmontoimp=0;
						$li_total=$io_report->ds_detalle->getRowCount("numfac");			   
						for($li_i=1;($li_i<=$li_total)&&($lb_valido);$li_i++)
						{
							$ls_numsop=$io_report->ds_detalle->data["numsop"][$li_i];					
							$li_baseimp=$io_report->ds_detalle->data["basimp"][$li_i];	
							$li_porimp='RETENCION 1 X 1.000';	
							$li_porcentaje='0,001';
							$li_totimp=$io_report->ds_detalle->data["iva_ret"][$li_i];	
							$lb_valido=$io_report->uf_select_datos_cheque_retencion($ls_numsop,$ls_nummov,$ld_fecmov,$li_monto);
							$li_totalbaseimp=$li_totalbaseimp + $li_baseimp ;	
							$li_totalmontoimp=$li_totalmontoimp + $li_totimp;	
							$li_baseimp=number_format($li_baseimp,2,",",".");			
							$li_totimp=number_format($li_totimp,2,",",".");							
							$li_monto=number_format($li_monto,2,",",".");		
							$ld_fecmov=$io_funciones->uf_convertirfecmostrar($ld_fecmov);
																					
						  }																		 																						  						  if($lb_valido) // Si no ocurrio ningún error
						  {
							  $li_totalbaseimp= number_format($li_totalbaseimp,2,",","."); 
							  $li_totalmontoimp= number_format($li_totalmontoimp,2,",","."); 
							  uf_print_detalle($li_monto,$li_baseimp,$li_porcentaje,$li_porimp, $li_totimp, $ls_nummov,
											   $ld_fecmov,&$io_pdf);
							  uf_print_firmas($ls_agente,&$io_pdf);
						  }
						  							 
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