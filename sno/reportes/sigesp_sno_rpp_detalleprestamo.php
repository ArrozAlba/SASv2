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
		// Fecha Creación: 04/12/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_detalleprestamo.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_hdetalleprestamo.php",$ls_descripcion,$ls_codnom);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_desnom,$as_periodo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/12/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,720,11,$as_periodo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(10,$as_desnom);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,710,10,$as_desnom); // Agregar el título
		$io_pdf->addText(500,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(506,743,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_cedper,$as_nomper,$as_codtippre,$as_destippre,$as_codconc,$as_nomcon,$ai_monpre,
							   $ai_numcuopre,$ai_monamopre,$as_stapre,$ad_fecpre,$as_perinipre,$ad_fecingper,
							   $ai_numpre,$ai_saldo,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_cedper // Cédula del personal 
		//	   			   as_nomper // Nombre del personal
		//	    		   as_codtippre // Código del Tipo de Prestamo
		//	    		   as_destippre // Descripción del Tipo de Prestamo
		//	    		   as_codconc // Código del Concepto
		//	    		   as_nomcon // Nombre del Concepto
		//	    		   ai_monpre // Monto del Prestamo
		//	    		   ai_numcuopre // Número de Cuotas del Prestamo
		//	    		   ai_monamopre // Monto Amortizado del Prestamo
		//	    		   as_stapre // Estatus del Prestamo
		//	    		   ad_fecpre // Fecha del Prestamo
		//	    		   as_perinipre // Periodo Inicial del Prestamo
		//	    		   ad_fecingper // Fecha de Ingreso del Prestamo
		//	    		   ai_numpre // Número del Prestamo
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/12/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$la_data[1]=array('titulo'=>'<b>Identificación del Empleado</b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		$la_data[1]=array('titulo1'=>'<b>Apellidos y Nombres</b>','nombre'=>$as_cedper.' - '.$as_nomper,'titulo2'=>'<b>Fecha de Ingreso</b>',
						  'fechaingreso'=>$ad_fecingper);
		$la_columnas=array('titulo1'=>'','nombre'=>'','titulo2'=>'','fechaingreso'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo1'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>260), // Justificación y ancho de la columna
						 		 	   'titulo2'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 		 	   'fechaingreso'=>array('justification'=>'left','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		$la_data[1]=array('titulo1'=>'<b>Nro de Prestamo</b>','nombre'=>str_pad($ai_numpre,15,"0",0));
		$la_data[2]=array('titulo1'=>'<b>Tipo de Prestamo</b>','nombre'=>$as_codtippre.' - '.$as_destippre);
		$la_data[3]=array('titulo1'=>'<b>Concepto</b>','nombre'=>$as_codconc.' - '.$as_nomcon);
		$la_columnas=array('titulo1'=>'','nombre'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo1'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>410))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		$la_data[1]=array('titulo1'=>'<b>Fecha del Prestamo</b>','valor1'=>$ad_fecpre,
						  'titulo2'=>'<b>Periodo Inicial</b>','valor2'=>$as_perinipre,
						  'titulo3'=>'<b>Estatus</b>','valor3'=>$as_stapre);
		$la_columnas=array('titulo1'=>'','valor1'=>'','titulo2'=>'','valor2'=>'','titulo3'=>'','valor3'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo1'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'valor1'=>array('justification'=>'left','width'=>90), // Justificación y ancho de la columna
						 			   'titulo2'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'valor2'=>array('justification'=>'left','width'=>90), // Justificación y ancho de la columna
						 			   'titulo3'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'valor3'=>array('justification'=>'left','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		$la_data[1]=array('titulo1'=>'<b>Monto del Prestamo </b>','valor1'=>$ai_monpre,
						  'titulo2'=>'<b>Monto Amortizado </b>','valor2'=>$ai_monamopre,
						  'titulo3'=>'<b>Saldo </b>','valor3'=>$ai_saldo,
						  'titulo4'=>'<b>Nro Cuotas</b>','valor4'=>$ai_numcuopre);
		$la_columnas=array('titulo1'=>'','valor1'=>'','titulo2'=>'','valor2'=>'','titulo3'=>'','valor3'=>'','titulo4'=>'','valor4'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo1'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'valor1'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'titulo2'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'valor2'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'titulo3'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'valor3'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'titulo4'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'valor4'=>array('justification'=>'left','width'=>20))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_cuota(&$la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuota
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/12/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		$la_data1[1]=array('titulo'=>'<b>Detalle de Cuotas</b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data1,$la_columnas,'',$la_config);
		unset($la_data1);
		unset($la_columnas);
		$la_columnas=array('numero'=>'Nro Cuota','periodo'=>'Período','inicio'=>'Inicio','fin'=>'Fin','monto'=>'Monto '.$ls_bolivares.'','estatus'=>'Estatus');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('numero'=>array('justification'=>'center','width'=>90),
						 			   'periodo'=>array('justification'=>'center','width'=>90),
									   'inicio'=>array('justification'=>'center','width'=>90),
									   'fin'=>array('justification'=>'center','width'=>90),
									   'monto'=>array('justification'=>'center','width'=>70),
									   'estatus'=>array('justification'=>'center','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezSetDy(-2);
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
	}// end function uf_print_detalle_cuota
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_amortizado(&$la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_amortizado
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/12/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		$la_data1[1]=array('titulo'=>'<b>Detalle de Amortización</b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data1,$la_columnas,'',$la_config);
		unset($la_data1);
		unset($la_columnas);
		$la_columnas=array('numero'=>'Nro Amortizado','periodo'=>'Período','fecha'=>'Fecha',
						   'monto'=>'Monto '.$ls_bolivares.'','descripcion'=>'                        Observación');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('numero'=>array('justification'=>'center','width'=>80),
						 			   'periodo'=>array('justification'=>'center','width'=>80),
									   'fecha'=>array('justification'=>'center','width'=>80),
									   'monto'=>array('justification'=>'center','width'=>70),
									   'descripcion'=>array('justification'=>'left','width'=>190))); // Justificación y ancho de la columna
		$io_pdf->ezSetDy(-2);
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
	}// end function uf_print_detalle_amortizado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	$ls_tiporeporte="0";
	$ls_bolivares="";
	if (array_key_exists("tiporeporte",$_GET))
	{
		$ls_tiporeporte=$_GET["tiporeporte"];
	}
	switch($ls_tiporeporte)
	{
		case "0":
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
			break;

		case "1":
			if($_SESSION["la_nomina"]["tiponomina"]=="NORMAL")
			{
				require_once("sigesp_sno_class_reportbsf.php");
				$io_report=new sigesp_sno_class_reportbsf();
				$li_tipo=1;
			}
			if($_SESSION["la_nomina"]["tiponomina"]=="HISTORICA")
			{
				require_once("sigesp_sno_class_report_historicobsf.php");
				$io_report=new sigesp_sno_class_report_historicobsf();
				$li_tipo=2;
			}	
			$ls_bolivares ="Bs.F.";
			break;
	}
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$ls_titulo="<b>Detalle de Prestamo</b>";
	$ls_periodo="<b>Período Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codconcdes=$io_fun_nomina->uf_obtenervalor_get("codconcdes","");
	$ls_codconchas=$io_fun_nomina->uf_obtenervalor_get("codconchas","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_codtippredes=$io_fun_nomina->uf_obtenervalor_get("codtippredes","");
	$ls_codtipprehas=$io_fun_nomina->uf_obtenervalor_get("codtipprehas","");
	$ls_estatus=$io_fun_nomina->uf_obtenervalor_get("estatus","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_detalleprestamo_personal($ls_codconcdes,$ls_codconchas,$ls_codperdes,$ls_codperhas,
															$ls_codtippredes,$ls_codtipprehas,$ls_estatus,$ls_subnomdes,
															$ls_subnomhas,$ls_orden); // Cargar el DS con los datos de la cabecera del reporte
	}
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else  // Imprimimos el reporte
	{
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.1,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_desnom,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->DS->getRowCount("cedper");
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_codper=$io_report->DS->data["codper"][$li_i];
			$li_numpre=$io_report->DS->data["numpre"][$li_i];
			$ls_cedper=$io_report->DS->data["cedper"][$li_i];
			$ls_nomper=$io_report->DS->data["apeper"][$li_i].", ".$io_report->DS->data["nomper"][$li_i];
			$ls_codtippre=$io_report->DS->data["codtippre"][$li_i];
			$ls_destippre=$io_report->DS->data["destippre"][$li_i];
			$ls_codconc=$io_report->DS->data["codconc"][$li_i];
			$ls_nomcon=$io_report->DS->data["nomcon"][$li_i];
			$li_monpre=$io_fun_nomina->uf_formatonumerico($io_report->DS->data["monpre"][$li_i]);
			$li_numcuopre=$io_report->DS->data["numcuopre"][$li_i];
			$li_monamopre=$io_fun_nomina->uf_formatonumerico($io_report->DS->data["monamopre"][$li_i]);
			$li_saldo=($io_report->DS->data["monpre"][$li_i]-$io_report->DS->data["monamopre"][$li_i]);
			$li_saldo=$io_fun_nomina->uf_formatonumerico($li_saldo);
			$ls_stapre=$io_report->DS->data["stapre"][$li_i];
			switch($ls_stapre)
			{
				case "1": 
					$ls_stapre="Activo";
					break;
				case "2": 
					$ls_stapre="Suspendido";
					break;
				case "3": 
					$ls_stapre="Cancelado";
					break;
			}
			$ld_fecpre=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecpre"][$li_i]);
			$ld_fecingper=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecingper"][$li_i]);
			$ls_perinipre=substr($io_report->DS->data["fecpre"][$li_i],0,4)." - ".$io_report->DS->data["perinipre"][$li_i];
			uf_print_cabecera($ls_cedper,$ls_nomper,$ls_codtippre,$ls_destippre,$ls_codconc,$ls_nomcon,$li_monpre,
							  $li_numcuopre,$li_monamopre,$ls_stapre,$ld_fecpre,$ls_perinipre,$ld_fecingper,
							  $li_numpre,$li_saldo,$io_pdf); // Imprimimos la cabecera del registro
			$lb_ok=$io_report->uf_detalleprestamo_cuotas($ls_codper,$li_numpre,$ls_codtippre); // Obtenemos el detalle del reporte
			if($lb_ok)
			{
				$li_totrow_det=$io_report->DS_detalle->getRowCount("numcuo");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_numcuo=$io_report->DS_detalle->data["numcuo"][$li_s];
					$ls_percob=$io_report->DS_detalle->data["percob"][$li_s];
					$ld_feciniper=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["feciniper"][$li_s]);
					$ld_fecfinper=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["fecfinper"][$li_s]);
					$li_moncuo=$io_fun_nomina->uf_formatonumerico($io_report->DS_detalle->data["moncuo"][$li_s]);
					$ls_estcuo=$io_report->DS_detalle->data["estcuo"][$li_s];
					switch($ls_estcuo)
					{
						case "1": 
							$ls_estcuo="Cancelada";
							break;
						case "0": 
							$ls_estcuo="Por Cancelar";
							break;
					}
					$la_data[$li_s]=array('numero'=>$ls_numcuo,'periodo'=>$ls_percob,'inicio'=>$ld_feciniper,
										  'fin'=>$ld_fecfinper,'monto'=>$li_moncuo,'estatus'=>$ls_estcuo);
				}
				$io_report->DS_detalle->resetds("numcuo");
				uf_print_detalle_cuota($la_data,$io_pdf); // Imprimimos el detalle 
				unset($la_data);
			}
			$lb_ok=$io_report->uf_detalleprestamo_amortizado($ls_codper,$li_numpre,$ls_codtippre); // Obtenemos el detalle del reporte
			if($lb_ok)
			{
				$li_totrow_det=$io_report->DS_detalle->getRowCount("numamo");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_numamo=$io_report->DS_detalle->data["numamo"][$li_s];
					$ls_peramo=substr($io_report->DS_detalle->data["fecamo"][$li_s],0,4)." - ".$io_report->DS_detalle->data["peramo"][$li_s];
					$ld_fecamo=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["fecamo"][$li_s]);
					$li_monamo=$io_fun_nomina->uf_formatonumerico($io_report->DS_detalle->data["monamo"][$li_s]);
					$ls_desamo=$io_report->DS_detalle->data["desamo"][$li_s];
					$la_data[$li_s]=array('numero'=>$ls_numamo,'periodo'=>$ls_peramo,'fecha'=>$ld_fecamo,
										  'monto'=>$li_monamo,'descripcion'=>$ls_desamo);
				}
				$io_report->DS_detalle->resetds("numamo");
				uf_print_detalle_amortizado($la_data,$io_pdf); // Imprimimos el detalle 
				unset($la_data);
			}

			if($li_i<$li_totrow)
			{
				$io_pdf->ezNewPage(); // Insertar una nueva página
			}
		}
		$io_report->DS->resetds("cedper");
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
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 