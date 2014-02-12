<?php
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
	ini_set('memory_limit','256M');
	ini_set('max_execution_time','0');

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo,$ai_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_resumenconceptounidad.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_hresumenconceptounidad.php",$ls_descripcion,$ls_codnom);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_desnom,$as_periodo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,755,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,545,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,535,11,$as_periodo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(10,$as_desnom);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,525,10,$as_desnom); // Agregar el título
		$io_pdf->addText(712,560,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(718,553,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_coduniadm,$as_desuniadm,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_coduniadm // Código de la unidad administrativa
		//	    		   as_desuniadm // Descripción de la unidad administrativa
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por unidad administrativa
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>Unidad Administrativa</b> '.$as_coduniadm.' - '.$as_desuniadm));
		$la_columnas=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);	
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por unidad administrativa
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-4);
		$la_columnas=array('codigo'=>'<b>Código</b>',
						   'nombre'=>'<b>                                 Concepto</b>',
					       'partida'=>'<b>Partida</b>',
					       'personal'=>'<b>Nro Personas</b>',
						   'asignacion'=>'<b>Asignación    </b>',
						   'deduccion'=>'<b>Deducción     </b>',
						   'aporte'=>'<b>Aporte       Patronal     </b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>250), // Justificación y ancho de la columna
						 			   'partida'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'personal'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'aporte'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piedetalle($ai_totalasignacion,$ai_totaldeduccion,$ai_totalaporte,$ai_total_neto,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piedetalle
		//		   Access: private 
		//	    Arguments: ai_totalasignacion // Total Asignación
		//	   			   ai_totaldeduccion // Total Deduccción
		//	   			   ai_totalaporte // Total aporte
		//	   			   ai_total_neto // Total Neto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por unidad administrativa
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$la_data=array(array('totales'=>'<b>Totales Unidad '.$ls_bolivares.'</b>','asignacion'=>$ai_totalasignacion,'deduccion'=>$ai_totaldeduccion,
							 'aporte'=>$ai_totalaporte));
		$la_columna=array('totales'=>'','asignacion'=>'','deduccion'=>'','aporte'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('totales'=>array('justification'=>'right','width'=>490), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'aporte'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>'<b>Neto Unidad '.$ls_bolivares.'</b> '.$ai_total_neto));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('name'=>array('justification'=>'center'))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_piedetalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($ai_totasi,$ai_totded,$ai_totapo,$ai_totgeneral,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		   Access: private 
		//	    Arguments: ai_totasi // Total de Asignaciones
		//	   			   ai_totded // Total de Deducciones
		//	   			   ai_totapo // Total de Aportes
		//	   			   ai_totgeneral // Total de Neto a Pagar
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por todos los registros
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>700); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$la_data=array(array('asignacion'=>'<b>Asignaciones: </b>'.$ai_totasi,'deduccion'=>'<b>Deducciones: </b>'.$ai_totded,
							 'aporte'=>'<b>Aportes: </b>'.$ai_totapo));
		$la_columna=array('asignacion'=>'','deduccion'=>'','aporte'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>600, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('asignacion'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna
						 			   'aporte'=>array('justification'=>'center','width'=>200))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$la_data=array(array('name'=>'<b>Neto a Pagar '.$ls_bolivares.': </b>','total'=>$ai_totgeneral));
		$la_columna=array('name'=>'','total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=> 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>600, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('name'=>array('justification'=>'right','width'=>300), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'left','width'=>300))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	if($_SESSION["la_nomina"]["tiponomina"]=="NORMAL")
	{
		require_once("sigesp_sno_class_report.php");
		$io_report=new sigesp_sno_class_report();
		$li_tipo=1;
	}
	if($_SESSION["la_nomina"]["tiponomina"]=="HISTORICA")
	{
		require_once("sigesp_sno_class_report_historico.php");
		$io_report=new sigesp_sno_class_report_historico();
		$li_tipo=2;
	}	
	$ls_bolivares ="Bs.";
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$ls_titulo="<b>Resumen de Conceptos</b>";
	$ls_periodo="<b>Período Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codconcdes=$io_fun_nomina->uf_obtenervalor_get("codconcdes","");
	$ls_codconchas=$io_fun_nomina->uf_obtenervalor_get("codconchas","");
	$ls_coduniadm=$io_fun_nomina->uf_obtenervalor_get("coduniadm","");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_resumenconceptounidad_unidad($ls_codconcdes,$ls_codconchas,$ls_coduniadm,$ls_conceptocero,$ls_subnomdes,$ls_subnomhas); // Cargar el DS con los datos de la cabecera del reporte
	}
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.3,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_desnom,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(750,50,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->DS->getRowCount("minorguniadm");
		$li_totasignacion=0;
		$li_totdeduccion=0;
		$li_totaporte=0;		
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
	        $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$ls_minorguniadm=$io_report->DS->data["minorguniadm"][$li_i];
			$ls_ofiuniadm=$io_report->DS->data["ofiuniadm"][$li_i];
			$ls_uniuniadm=$io_report->DS->data["uniuniadm"][$li_i];
			$ls_depuniadm=$io_report->DS->data["depuniadm"][$li_i];
			$ls_prouniadm=$io_report->DS->data["prouniadm"][$li_i];
			$ls_coduniadm=$ls_minorguniadm."-".$ls_ofiuniadm."-".$ls_uniuniadm."-".$ls_depuniadm."-".$ls_prouniadm;
			$ls_desuniadm=$io_report->DS->data["desuniadm"][$li_i];
			uf_print_cabecera($ls_coduniadm,$ls_desuniadm,$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_resumenconceptounidad_concepto($ls_codconcdes,$ls_codconchas,$ls_coduniadm,$ls_conceptocero,$ls_subnomdes,$ls_subnomhas,$ls_orden); // Obtenemos el detalle del reporte
			$li_totasi=0;
			$li_totded=0;
			$li_totapo=0;
			if($lb_valido)
			{
				$li_totrow_res=$io_report->DS_detalle->getRowCount("codconc");
				for($li_s=1;$li_s<=$li_totrow_res;$li_s++)
				{
					$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_s];
					$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_s];
					$ls_tipsal=rtrim($io_report->DS_detalle->data["tipsal"][$li_s]);
					$li_monto=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["monto"][$li_s]));
					$li_totalpersonal=number_format($io_report->DS_detalle->data["total"][$li_s],0,"",".");
					$ls_cueprecon=rtrim($io_report->DS_detalle->data["cueprecon"][$li_s]);
					$ls_cueprepatcon=rtrim($io_report->DS_detalle->data["cueprepatcon"][$li_s]);
					switch($ls_tipsal)
					{
						case "A": // Asignación
							$li_totasi=$li_totasi+abs($io_report->DS_detalle->data["monto"][$li_s]);
							$la_data[$li_s]=array('codigo'=>$ls_codconc,'nombre'=>$ls_nomcon,'partida'=>$ls_cueprecon,'personal'=>$li_totalpersonal,'asignacion'=>$li_monto,'deduccion'=>'','aporte'=>'');
							break;
		
						case "V1": // Asignación
							$li_totasi=$li_totasi+abs($io_report->DS_detalle->data["monto"][$li_s]);
							$la_data[$li_s]=array('codigo'=>$ls_codconc,'nombre'=>$ls_nomcon,'partida'=>$ls_cueprecon,'personal'=>$li_totalpersonal,'asignacion'=>$li_monto,'deduccion'=>'','aporte'=>'');
							break;
		
						case "W1": // Asignación
							$li_totasi=$li_totasi+abs($io_report->DS_detalle->data["monto"][$li_s]);
							$la_data[$li_s]=array('codigo'=>$ls_codconc,'nombre'=>$ls_nomcon,'partida'=>$ls_cueprecon,'personal'=>$li_totalpersonal,'asignacion'=>$li_monto,'deduccion'=>'','aporte'=>'');
							break;
		
						case "D": // Deducción
							$li_totded=$li_totded+abs($io_report->DS_detalle->data["monto"][$li_s]);
							$la_data[$li_s]=array('codigo'=>$ls_codconc,'nombre'=>$ls_nomcon,'partida'=>$ls_cueprecon,'personal'=>$li_totalpersonal,'asignacion'=>'','deduccion'=>$li_monto,'aporte'=>'');
							break;
		
						case "V2": // Deducción
							$li_totded=$li_totded+abs($io_report->DS_detalle->data["monto"][$li_s]);
							$la_data[$li_s]=array('codigo'=>$ls_codconc,'nombre'=>$ls_nomcon,'partida'=>$ls_cueprecon,'personal'=>$li_totalpersonal,'asignacion'=>'','deduccion'=>$li_monto,'aporte'=>'');
							break;
		
						case "W2": // Deducción
							$li_totded=$li_totded+abs($io_report->DS_detalle->data["monto"][$li_s]);
							$la_data[$li_s]=array('codigo'=>$ls_codconc,'nombre'=>$ls_nomcon,'partida'=>$ls_cueprecon,'personal'=>$li_totalpersonal,'asignacion'=>'','deduccion'=>$li_monto,'aporte'=>'');
							break;
		
						case "P1": // Aporte Empleado
							$li_totded=$li_totded+abs($io_report->DS_detalle->data["monto"][$li_s]);
							$la_data[$li_s]=array('codigo'=>$ls_codconc,'nombre'=>$ls_nomcon,'partida'=>$ls_cueprecon,'personal'=>$li_totalpersonal,'asignacion'=>'','deduccion'=>$li_monto,'aporte'=>'');
							break;
		
						case "V3": // Aporte Empleado
							$li_totded=$li_totded+abs($io_report->DS_detalle->data["monto"][$li_s]);
							$la_data[$li_s]=array('codigo'=>$ls_codconc,'nombre'=>$ls_nomcon,'partida'=>$ls_cueprecon,'personal'=>$li_totalpersonal,'asignacion'=>'','deduccion'=>$li_monto,'aporte'=>'');
							break;
		
						case "W3": // Aporte Empleado
							$li_totded=$li_totded+abs($io_report->DS_detalle->data["monto"][$li_s]);
							$la_data[$li_s]=array('codigo'=>$ls_codconc,'nombre'=>$ls_nomcon,'partida'=>$ls_cueprecon,'personal'=>$li_totalpersonal,'asignacion'=>'','deduccion'=>$li_monto,'aporte'=>'');
							break;
		
						case "P2": // Aporte Patrón
							$li_totapo=$li_totapo+abs($io_report->DS_detalle->data["monto"][$li_s]);
							$la_data[$li_s]=array('codigo'=>$ls_codconc,'nombre'=>$ls_nomcon,'partida'=>$ls_cueprepatcon,'personal'=>$li_totalpersonal,'asignacion'=>'','deduccion'=>'','aporte'=>$li_monto);
							break;
		
						case "V4": // Aporte Patrón
							$li_totapo=$li_totapo+abs($io_report->DS_detalle->data["monto"][$li_s]);
							$la_data[$li_s]=array('codigo'=>$ls_codconc,'nombre'=>$ls_nomcon,'partida'=>$ls_cueprepatcon,'personal'=>$li_totalpersonal,'asignacion'=>'','deduccion'=>'','aporte'=>$li_monto);
							break;
		
						case "W4": // Aporte Patrón
							$li_totapo=$li_totapo+abs($io_report->DS_detalle->data["monto"][$li_s]);
							$la_data[$li_s]=array('codigo'=>$ls_codconc,'nombre'=>$ls_nomcon,'partida'=>$ls_cueprepatcon,'personal'=>$li_totalpersonal,'asignacion'=>'','deduccion'=>'','aporte'=>$li_monto);
							break;
					}
				}
				$io_report->DS_detalle->resetds("codconc");
  			    uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle  
				$li_totnet=$li_totasi-$li_totded;
				$li_totasignacion=$li_totasignacion+$li_totasi;
				$li_totdeduccion=$li_totdeduccion+$li_totded;
				$li_totaporte=$li_totaporte+$li_totapo;		
				$li_totasi=$io_fun_nomina->uf_formatonumerico($li_totasi);
				$li_totded=$io_fun_nomina->uf_formatonumerico($li_totded);
				$li_totapo=$io_fun_nomina->uf_formatonumerico($li_totapo);
				$li_totnet=$io_fun_nomina->uf_formatonumerico($li_totnet);
				uf_print_piedetalle($li_totasi,$li_totded,$li_totapo,$li_totnet,$io_pdf); // Imprimimos el pie de la cabecera
				if ($io_pdf->ezPageCount==$li_numpag)
				{// Hacemos el commit de los registros que se desean imprimir
					$io_pdf->transaction('commit');
				}
				else
				{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
					$io_pdf->transaction('rewind');
					$io_pdf->ezNewPage(); // Insertar una nueva página
					uf_print_cabecera($ls_coduniadm,$ls_desuniadm,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					uf_print_piedetalle($li_totasi,$li_totded,$li_totapo,$li_totnet,$io_pdf); // Imprimimos el pie del detalle
				}
			}
			unset($la_data);
		}
		$li_totneto=$li_totasignacion-$li_totdeduccion;
		$li_totasignacion=$io_fun_nomina->uf_formatonumerico($li_totasignacion);
		$li_totdeduccion=$io_fun_nomina->uf_formatonumerico($li_totdeduccion);
		$li_totaporte=$io_fun_nomina->uf_formatonumerico($li_totaporte);
		$li_totneto=$io_fun_nomina->uf_formatonumerico($li_totneto);
		uf_print_piecabecera($li_totasignacion,$li_totdeduccion,$li_totaporte,$li_totneto,$io_pdf); // Imprimimos el pie de la cabecera
		$io_report->DS->resetds("minorguniadm");
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 