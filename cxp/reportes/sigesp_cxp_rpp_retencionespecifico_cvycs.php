<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//    REPORTE: Retencion Especifico
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
		// Fecha Creación: 10/07/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_retencionesespecifico.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_fecdes,$as_fechas,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 08/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(20,40,578,40);
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,510,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,530,11,$as_titulo); // Agregar el título
		$ls_periodo = "<b>Del :</b>".$as_fecdes."   "."<b>Al :</b>".$as_fechas;	
		$li_tm=$io_pdf->getTextWidth(11,$ls_periodo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,515,11,$ls_periodo); // Agregar el título
		$io_pdf->addText(700,550,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(706,543,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codded,$as_dended,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_codded // Código de Deduccion
		//	    		   as_dended // Deenominación de Deduccion
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 10/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo=" Bs.F.";
		}
		else
		{
			$ls_titulo=" Bs.";
		}
		$la_data   =array(array('retencion'=>'<b><i>Retención:<i></b>','codigo'=>$as_codded,'denominacion'=>$as_dended));
		$la_columna=array('retencion'=>'','codigo'=>'','denominacion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'titleFontSize' =>10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2,
						 'shadeCol2'=>array(0.86,0.86,0.86),
						 'colGap'=>1,
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('retencion'=>array('justification'=>'left','width'=>60),
						               'codigo'=>array('justification'=>'left','width'=>50), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>610))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);			
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('beneficiario'=>'<b>Beneficiario</b>','fecha'=>'<b>Fecha</b>','solicitud'=>'<b>Monto Orden de Pago</b>','factura'=>'<b>Factura</b>','referencia'=>'<b>Numero Control</b>','monto'=>'<b>Monto Objeto Retencion','porded'=>'<b>Alicuota de Retencion (%) </b>','retencion'=>'<b>Monto Retenido</b>'));
		$la_columna=array('beneficiario'=>'','fecha'=>'','factura'=>'','referencia'=>'','solicitud'=>'','monto'=>'','porded'=>'','retencion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'titleFontSize' =>10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0,
						 'shadeCol2'=>array(0.86,0.86,0.86),
						 'colGap'=>1,
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('beneficiario'=>array('justification'=>'left','width'=>170),
						 			   'fecha'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'factura'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'referencia'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						               'solicitud'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'porded'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'retencion'=>array('justification'=>'center','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: la_data // Arreglo con todos los datos 
		//				   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 10/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' =>8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>1,
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('beneficiario'=>array('justification'=>'left','width'=>170),
						 			   'fecha'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'factura'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'referencia'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						               'solicitud'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'porded'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'retencion'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$la_columna=array('beneficiario'=>'','fecha'=>'','factura'=>'','referencia'=>'','solicitud'=>'','monto'=>'','porded'=>'','retencion'=>'');
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_totales($ai_filas,$ai_total,$ai_totbase,$ai_totmonsol,&$io_pdf)
	 {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: ai_filas // Total de Filas
		//				   ai_total // Monto total retenido
		//				   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los totales
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 10/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo=" Bs.F.";
		}
		else
		{
			$ls_titulo=" Bs.";
		}
	    $la_data[1]=array('name'=>'____________________________________________________________________________________________________________________________');
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>412, // Orientación de la tabla
						 'width'=>700); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('cantidad'=>'<b>Total de Retenciones :</b>','filas'=>$ai_filas,'totales'=>'<b>Total Montos </b>','solicitud'=>$ai_totmonsol,'base'=>$ai_totbase,'vacio'=>'','monto'=>$ai_total);
	    $la_columna=array('cantidad'=>'','filas'=>'','totales'=>'','solicitud'=>'','base'=>'','vacio'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' =>8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>1,
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cantidad'=>array('justification'=>'right','width'=>170),
						 			   'filas'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'totales'=>array('justification'=>'right','width'=>180), // Justificación y ancho de la columna
						               'solicitud'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'base'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'vacio'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
	    $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	 }// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------

	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_cxp_class_report.php");
	$io_report=new sigesp_cxp_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>LISTADO DE RETENCIONES</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codded=$io_fun_cxp->uf_obtenervalor_get("codded","");
	$ls_coddedhas=$io_fun_cxp->uf_obtenervalor_get("coddedhas","");
	//$ls_dended=$io_fun_cxp->uf_obtenervalor_get("dended","");
	$ls_tipproben=$io_fun_cxp->uf_obtenervalor_get("tipproben","");
	$ls_codprobendes=$io_fun_cxp->uf_obtenervalor_get("codprobendes","");
	$ls_codprobenhas=$io_fun_cxp->uf_obtenervalor_get("codprobenhas","");
	$ld_fecdes=$io_fun_cxp->uf_obtenervalor_get("fecdes","");
	$ld_fechas=$io_fun_cxp->uf_obtenervalor_get("fechas","");
	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	$ls_tipded=$io_fun_cxp->uf_obtenervalor_get("tipded","");
	$ls_tipper=$io_fun_cxp->uf_obtenervalor_get("tipper","");
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
		$lb_valido=$io_report->uf_select_retenciones($ls_codded,$ls_coddedhas,$ls_tipded);
	}
	if($lb_valido===false)
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
		$io_pdf=new Cezpdf('LETTER','landscape');
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
		$io_pdf->ezSetCmMargins(4.4,3,3,3);                          
		uf_print_encabezado_pagina($ls_titulo,$ld_fecdes,$ld_fechas,&$io_pdf);
		$li_rowcargos=$io_report->DS->getRowCount("codded");//print"s";
		$io_report->DS->sortData("codded");
		$lb_existe=false;
		for($li_j=1;$li_j<=$li_rowcargos;$li_j++)
		{
			$ls_codded= $io_report->DS->data["codded"][$li_j];
			$ls_dended= $io_report->DS->data["dended"][$li_j];
			$li_islr= $io_report->DS->data["islr"][$li_j];
			$li_iva= $io_report->DS->data["iva"][$li_j];
			$li_estretmun= $io_report->DS->data["estretmun"][$li_j];
			$li_retaposol= $io_report->DS->data["retaposol"][$li_j];
			$lb_valido=$io_report->uf_retencionesespecifico($ls_codded,$ls_coddedhas,$ls_tipproben,$ls_codprobenhas,$ls_codprobendes,$ld_fecdes,$ld_fechas,$ls_tipper);
			$li_totbase=0;
			$li_totcargos=0;
			$li_totmonsol=0;
			$li_totrow=$io_report->ds_detalle->getRowCount("numsol");
			for ($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_codded= $io_report->ds_detalle->data["codded"][$li_i];
				$ls_numsol= $io_report->ds_detalle->data["numsol"][$li_i];
				$ls_nombre= $io_report->ds_detalle->data["nombre"][$li_i];
				$ls_numcomiva= $io_report->ds_detalle->data["numcomiva"][$li_i];
				$ls_numcommun= $io_report->ds_detalle->data["numcommun"][$li_i];
				$ls_numcomapo= $io_report->ds_detalle->data["numcomapo"][$li_i];
				$li_porded= $io_report->ds_detalle->data["porded"][$li_i];
				$ls_numrecdoc= trim($io_report->ds_detalle->data["numrecdoc"][$li_i]);
				$ls_numref= trim($io_report->ds_detalle->data["numref"][$li_i]);
				$ld_fecemisol= $io_funciones->uf_convertirfecmostrar($io_report->ds_detalle->data["fecemisol"][$li_i]);
				$li_monsol= number_format($io_report->ds_detalle->data["monsol"][$li_i],2,',','.');
				$li_mon_obj_ret= number_format($io_report->ds_detalle->data["mon_obj_ret"][$li_i],2,',','.');
				$li_monret= number_format($io_report->ds_detalle->data["monret"][$li_i],2,',','.');
				$li_totbase= $li_totbase+$io_report->ds_detalle->data["mon_obj_ret"][$li_i];
				$li_totcargos= $li_totcargos+$io_report->ds_detalle->data["monret"][$li_i];
				$li_totmonsol=$li_totmonsol+$io_report->ds_detalle->data["monsol"][$li_i];
				$ls_numcom="";
				if($li_iva==1)
				{$ls_numcom=$ls_numcomiva;}
				if($li_estretmun==1)
				{$ls_numcom=$ls_numcommun;}
				if($li_retaposol==1)
				{$ls_numcom=$ls_numcomapo;}
				$la_data[$li_i]=array('beneficiario'=>$ls_nombre,'fecha'=>$ld_fecemisol,'solicitud'=>$li_monsol,'factura'=>$ls_numrecdoc,'referencia'=>$ls_numref,'monto'=>$li_mon_obj_ret,
									  'porded'=>$li_porded,'retencion'=>$li_monret);
			}
			if($li_i>1)
			{  
				$lb_existe=true;
				uf_print_cabecera($ls_codded,$ls_dended,&$io_pdf);
				uf_print_detalle($la_data,&$io_pdf); // Imprimimos el detalle  
				$li_totbase=number_format($li_totbase,2,',','.');
				$li_totcargos=number_format($li_totcargos,2,',','.');
				$li_totmonsol=number_format($li_totmonsol,2,',','.');
				uf_print_totales($li_totrow,$li_totcargos,$li_totbase,$li_totmonsol,$io_pdf);
				unset($la_data);
			}
		}
		if(!$lb_existe)
		{
			$lb_valido=false;
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");		
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
	//	unset($io_pdf);
	}
//	unset($io_report);
//	unset($io_funciones);
//	unset($io_fun_cxp);
?> 