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
		// Fecha Creación: 03/07/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_relacionvacaciones.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_hrelacionvacaciones.php",$ls_descripcion,$ls_codnom);
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
		// Fecha Creación: 26/04/2006 
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
	function uf_print_cabecera($as_cedper,$as_nomper,$ad_fecingper,$as_desuniadm,$ai_sueintvac,
							   $as_codvac,$as_descar,$ai_sueintdia,$as_sueint,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_cedper // Cédula del personal 
		//	   			   as_nomcon // Nombre del personal
		//	    		   ad_fecingper // fecha de ingreso del personal
		//	    		   as_desuniadm // Descripción de la unidad adinistrativa
		//	    		   ai_sueintvac // sueldo integral de vacaciones
		//	    		   ad_fecdisvac // fecha de disfrute de las vacaciones
		//	    		   ad_fecreivac // fecha de reintegro de las vacaciones
		//	    		   ai_diavac // días hábiles de vacaciones
		//	    		   as_codvac // código de vacaciones
		//	    		   as_descar // descripción del cargo
		//	    		   ai_sueintdia // Sueldo integral diario
		//                 as_sueint // denominación de sueldo integral
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/07/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($as_sueint=="")
		{
			$titulo1="Sueldo Integral de Vacaciones";
			$titulo2="Sueldo Diario Integral";
		}
		else
		{
			$titulo1=$as_sueint." de Vacaciones";
			$titulo2=$as_sueint." Diario";
		}
		
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
		$la_data[1]=array('nombre'=>'<b>Apellidos y Nombres</b>','cedula'=>'<b>Cédula de Identidad</b>',);
		$la_data[2]=array('nombre'=>$as_nomper,'cedula'=>$as_cedper);
		$la_columnas=array('nombre'=>'','cedula'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>365), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>135))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		$la_data[1]=array('cargo'=>'<b>Cargo</b>','sueldovac'=>'<b>'.$titulo1.'</b>','sueldodia'=>'<b>'.$titulo2.'</b>');
		$la_data[2]=array('cargo'=>$as_descar,'sueldovac'=>$ai_sueintvac,'sueldodia'=>$ai_sueintdia);
		$la_columnas=array('cargo'=>'','sueldovac'=>'','sueldodia'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('cargo'=>array('justification'=>'left','width'=>225),
						               'sueldovac'=>array('justification'=>'center','width'=>140),
						 			   'sueldodia'=>array('justification'=>'center','width'=>135))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		$la_data[1]=array('uniadm'=>'<b>Unidad Administrativa</b>',
		                  'fecha'=>'<b>Fecha de Ingreso a la Institución</b>',
						  'anoservicio'=>'<b>Años de Servicio</b>');
		$la_data[2]=array('uniadm'=>$as_desuniadm,'fecha'=>$ad_fecingper,'anoservicio'=>$as_codvac);
		$la_columnas=array('uniadm'=>'','fecha'=>'','anoservicio'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('uniadm'=>array('justification'=>'left','width'=>225),
						               'fecha'=>array('justification'=>'center','width'=>140), 
						 			   'anoservicio'=>array('justification'=>'center','width'=>135))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------
	
//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_datos_vacaciones($ad_fecdisvac,$ad_fecreivac,$ai_diavac,$as_dianorvac,$as_persalvac,
							           $as_peringvac,$as_quisalvac,$as_quireivac,$ai_diabonvac,$ai_sabdom,
									   $ai_diafer,$as_obsvac,$ai_diapenvac,$ai_diapervac,$ai_diaadivac,$ai_diaadibon,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_cedper // Cédula del personal 
		//	   			   as_nomcon // Nombre del personal
		//	    		   ad_fecingper // fecha de ingreso del personal
		//	    		   as_desuniadm // Descripción de la unidad adinistrativa
		//	    		   ai_sueintvac // sueldo integral de vacaciones
		//	    		   ad_fecdisvac // fecha de disfrute de las vacaciones
		//	    		   ad_fecreivac // fecha de reintegro de las vacaciones
		//	    		   ai_diavac // días hábiles de vacaciones
		//	    		   as_codvac // código de vacaciones
		//	    		   as_descar // descripción del cargo
		//	    		   ai_sueintdia // Sueldo integral diario
		//                 as_sueint // denominación de sueldo integral
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/07/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_titulo[1]=array('titulo'=>'');
		$la_titulo[2]=array('titulo'=>'');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_titulo,$la_columnas,'',$la_config);
		unset($la_titulo);
		unset($la_columnas);
		
		$la_data[1]=array('titulo'=>'<b>Detalle de las Vacaciones</b>');
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
		
		$la_data[1]=array('c1'=>'<b>Fecha Salida</b>','c2'=>'<b>Fecha Ingreso</b>','c3'=>'<b>Periodo Salida</b>','c4'=>'<b>Periodo Ingreso</b>','c5'=>'<b>Quincena Salida</b>','c6'=>'<b>Quincena Ingreso</b>');
		$la_data[2]=array('c1'=>$ad_fecdisvac,'c2'=>$ad_fecreivac,'c3'=>$as_persalvac,'c4'=>$as_peringvac,'c5'=>$as_quisalvac,'c6'=>$as_quireivac);
		$la_columnas=array('c1'=>'','c2'=>'','c3'=>'','c4'=>'','c5'=>'','c6'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('c1'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'c2'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 		 	   'c3'=>array('justification'=>'center','width'=>80),
									   'c4'=>array('justification'=>'center','width'=>80),
									   'c5'=>array('justification'=>'center','width'=>80),
									   'c6'=>array('justification'=>'center','width'=>80),)); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
	$la_data[1]=array('c1'=>'<b>Nº Dias</b>','c2'=>'<b>Nº Dias Adicionales</b>','c3'=>'<b>Nº Dias Bono</b>','c4'=>'<b>Nº Dias Adicionales de Bono</b>');
		$la_data[2]=array('c1'=>$as_dianorvac,'c2'=>$ai_diaadivac,'c3'=>$ai_diabonvac,'c4'=>$ai_diaadibon);
		$la_columnas=array('c1'=>'','c2'=>'','c3'=>'','c4'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('c1'=>array('justification'=>'center','width'=>125), // Justificación y ancho de la columna
						 			   'c2'=>array('justification'=>'center','width'=>125), // Justificación y ancho de la columna
						 		 	   'c3'=>array('justification'=>'center','width'=>125),
									   'c4'=>array('justification'=>'center','width'=>125))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('c1'=>'<b>Nº Dias Feriados</b>','c2'=>'<b>Nº Sábados y Domingos</b>','c3'=>'<b>Nº Dias Permisos Descontables</b>','c4'=>'<b>Nº Dias Pendientes</b>');
		$la_data[2]=array('c1'=>$ai_diafer,'c2'=>$ai_sabdom,'c3'=>$ai_diapervac,'c4'=>$ai_diapenvac);
		$la_columnas=array('c1'=>'','c2'=>'','c3'=>'','c4'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('c1'=>array('justification'=>'center','width'=>125), // Justificación y ancho de la columna
						 			   'c2'=>array('justification'=>'center','width'=>125), // Justificación y ancho de la columna
						 		 	   'c3'=>array('justification'=>'center','width'=>125),
									   'c4'=>array('justification'=>'center','width'=>125))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('c1'=>'<b>Total Dias Vacaciones</b>','c2'=>'<b>Observación</b>');
		$la_data[2]=array('c1'=>$ai_diavac,'c2'=>$as_obsvac);
		$la_columnas=array('c1'=>'','c2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('c1'=>array('justification'=>'center','width'=>125), // Justificación y ancho de la columna
						 			   'c2'=>array('justification'=>'left','width'=>375))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($as_descripcion,$la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: as_descripcion // Descripción si es un reporte de salida ó de reintegro
		//	    		   la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/07/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_titulo[1]=array('titulo'=>'');
		$la_titulo[2]=array('titulo'=>'');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_titulo,$la_columnas,'',$la_config);
		unset($la_titulo);
		unset($la_columnas);
		$la_titulo[1]=array('titulo'=>'<b>'.$as_descripcion.'</b>',
						    'asignacion'=>'<b>ASIGNACIÓN</b>',
						    'deduccion'=>'<b>DEDUCCIÓN</b>',
						    'aporte'=>'<b>APORTE PATRONAL</b>');
		$la_columnas=array('titulo'=>'',
						   'asignacion'=>'',
						   'deduccion'=>'',
						   'aporte'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'aporte'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_titulo,$la_columnas,'',$la_config);
		unset($la_titulo);
		unset($la_columnas);
		$la_columnas=array('codigo'=>'',
						   'nombre'=>'',
						   'asignacion'=>'',
						   'deduccion'=>'',
						   'aporte'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>140), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'aporte'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total($ai_totasig,$ai_totdedu,$ai_totapor,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_total
		//		   Access: private 
		//	    Arguments: ai_totasig // Total Asignación
		//	   			   ai_totdedu // Total Deducción
		//	   			   ai_totapor // Total Aporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por conceptos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/07/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		$la_data[1]=array('total'=>'<b>Total '.$ls_bolivares.'</b>','asignacion'=>$ai_totasig,'deduccion'=>$ai_totdedu,'aporte'=>$ai_totapor);
		$la_columna=array('total'=>'','asignacion'=>'','deduccion'=>'','aporte'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>200), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'aporte'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

	}// end function uf_print_total
	//---------------------------------------------------------------------------------------------------------------------------------	
	function uf_print_frimas (&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_frimas
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime las firmas
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 07/07/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-180);

		$la_data1=array(array('name1'=>'<b>Elaborado Por</b>','name2'=>'<b>Revisado Por</b>', 'name3'=>'<b>Aprobado Por</b>'));	
		$la_columna1=array('name1'=>'','name2'=>'','name3'=>'');
		$la_config1=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('name1'=>array('justification'=>'center','width'=>167), // Justificación y ancho de la columna
						               'name2'=>array('justification'=>'center','width'=>167), // Justificación y ancho de la columna
						 			   'name3'=>array('justification'=>'center','width'=>167))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data1,$la_columna1,'',$la_config1);
		unset($la_data1);
		unset($la_columnas1);
		unset($la_config1);

		
		$la_data2[1]=array('name1'=>'','name2'=>'', 'name3'=>'');
		$la_data2[2]=array('name1'=>'','name2'=>'', 'name3'=>'');
		$la_data2[3]=array('name1'=>'','name2'=>'', 'name3'=>'');	
		$la_data2[4]=array('name1'=>'','name2'=>'', 'name3'=>'');	
		$la_columna2=array('name1'=>'','name2'=>'','name3'=>'');
		$la_config2=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('name1'=>array('justification'=>'center','width'=>167), // Justificación y ancho de la columna
						               'name2'=>array('justification'=>'center','width'=>167), // Justificación y ancho de la columna
						 			   'name3'=>array('justification'=>'center','width'=>167))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data2,$la_columna2,'',$la_config2);
		unset($la_data2);
		unset($la_columnas2);
		unset($la_config2);
	
		
	}// end function uf_print_frimas

	//--------------------------------------------------------------------------------------------------------------------------------


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
	$ls_titulo="<b>Liquidación de Vacaciones</b>";
	$ls_periodo="<b>Período Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codper=$io_fun_nomina->uf_obtenervalor_get("codper","");
	$ls_codvac=$io_fun_nomina->uf_obtenervalor_get("codvac","");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_tituloconcepto=$io_fun_nomina->uf_obtenervalor_get("tituloconcepto","");
	$ls_sueint=$io_fun_nomina->uf_obtenervalor_get("sueint","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_relacionvacacion_personal($ls_codper,$ls_codvac,$ls_conceptocero,$rs_data); // Cargar el DS con los datos de la cabecera del reporte
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
		
		while((!$rs_data->EOF)&&($lb_valido))
		{
			$ls_codper=$rs_data->fields["codper"];
			$ls_cedper=$rs_data->fields["cedper"];
			$ls_nomper=$rs_data->fields["apeper"].", ".$rs_data->fields["nomper"];
			$ld_fecingper=$io_funciones->uf_convertirfecmostrar($rs_data->fields["fecingper"]);
			$ls_desuniadm=$rs_data->fields["desuniadm"];
			$li_sueintvac=$io_fun_nomina->uf_formatonumerico($rs_data->fields["sueintvac"]);
			$li_sueintdia=($rs_data->fields["sueintvac"]/30);
			$li_sueintdia=$io_fun_nomina->uf_formatonumerico($li_sueintdia);
			$ld_fecdisvac=$io_funciones->uf_convertirfecmostrar($rs_data->fields["fecdisvac"]);
			$ld_fecreivac=$io_funciones->uf_convertirfecmostrar($rs_data->fields["fecreivac"]);
			$li_diavac=$rs_data->fields["diavac"];
			$ls_codvac=$rs_data->fields["codvac"];
			$ls_descar=$rs_data->fields["descar"];			
			$li_dianorvac=$rs_data->fields["dianorvac"];
			$ls_persalvac=$rs_data->fields["persalvac"];
			$ls_peringvac=$rs_data->fields["peringvac"];
			$ls_quisalvac=$rs_data->fields["quisalvac"];
			$ls_quireivac=$rs_data->fields["quireivac"];
			$li_diabonvac=$rs_data->fields["diabonvac"];
			$li_sabdom=$rs_data->fields["sabdom"];
			$li_diafer=$rs_data->fields["diafer"];
			$ls_obsvac=$rs_data->fields["obsvac"];
			$li_diapenvac=$rs_data->fields["diapenvac"];
			$li_diapervac=$rs_data->fields["diapervac"];
			$li_diaadivac=$rs_data->fields["diaadivac"];
			$li_diaadibon=$rs_data->fields["diaadibon"];
			
			uf_print_cabecera($ls_cedper,$ls_nomper,$ld_fecingper,$ls_desuniadm,$li_sueintvac,
							  $ls_codvac,$ls_descar,$li_sueintdia,$ls_sueint,$io_pdf); 
			uf_print_datos_vacaciones($ld_fecdisvac,$ld_fecreivac,$li_diavac,$li_dianorvac,$ls_persalvac,
							          $ls_peringvac,$ls_quisalvac,$ls_quireivac,$li_diabonvac,$li_sabdom,
									  $li_diafer,$ls_obsvac,$li_diapenvac,$li_diapervac,$li_diaadivac,$li_diaadibon,$io_pdf);
							  
			$lb_valido=$io_report->uf_relacionvacacion_concepto($ls_codper,$ls_codvac,$ls_conceptocero,$ls_tituloconcepto); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totasig=0;
				$li_totdedu=0;
				$li_totapor=0;
				$li_totrow_det=$io_report->DS_detalle->getRowCount("codconc");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_s];
					$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_s];
					$ls_tipsal=rtrim($io_report->DS_detalle->data["tipsal"][$li_s]);
					$li_asig=$io_fun_nomina->uf_formatonumerico(0);
					$li_dedu=$io_fun_nomina->uf_formatonumerico(0);
					$li_apor=$io_fun_nomina->uf_formatonumerico(0);
					$ls_persalvac=$io_report->DS_detalle->data["persalvac"][$li_s];
					$ls_peringvac=$io_report->DS_detalle->data["peringvac"][$li_s];
					$ls_descripcion="CONCEPTOS DE SALIDA DE VACACIONES";
					if($ls_peringvac==$_SESSION["la_nomina"]["peractnom"])
					{
						$ls_descripcion="CONCEPTOS DE REINTEGRO DE VACACIONES";
					}
					switch($ls_tipsal)
					{
						case "V1":
							$li_asig=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valsal"][$li_s]));
							$li_totasig=$li_totasig+$io_report->DS_detalle->data["valsal"][$li_s];
							break;
							
						case "W1":
							$li_asig=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valsal"][$li_s]));
							$li_totasig=$li_totasig+$io_report->DS_detalle->data["valsal"][$li_s];
							break;
							
						case "V2":
							$li_dedu=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valsal"][$li_s]));
							$li_totdedu=$li_totdedu+$io_report->DS_detalle->data["valsal"][$li_s];
							break;

						case "W2":
							$li_dedu=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valsal"][$li_s]));
							$li_totdedu=$li_totdedu+$io_report->DS_detalle->data["valsal"][$li_s];
							break;
							
						case "V3":
							$li_dedu=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valsal"][$li_s]));
							$li_totdedu=$li_totdedu+$io_report->DS_detalle->data["valsal"][$li_s];
							break;

						case "W3":
							$li_dedu=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valsal"][$li_s]));
							$li_totdedu=$li_totdedu+$io_report->DS_detalle->data["valsal"][$li_s];
							break;

						case "V4":
							$li_apor=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valsal"][$li_s]));
							$li_totapor=$li_totapor+$io_report->DS_detalle->data["valsal"][$li_s];
							break;
							
						case "W4":
							$li_apor=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valsal"][$li_s]));
							$li_totapor=$li_totapor+$io_report->DS_detalle->data["valsal"][$li_s];
							break;
					}
					$la_data[$li_s]=array('codigo'=>$ls_codconc,'nombre'=>$ls_nomcon,'asignacion'=>$li_asig,
										  'deduccion'=>$li_dedu,'aporte'=>$li_apor);
				}
				$io_report->DS_detalle->resetds("codconc");
				uf_print_detalle($ls_descripcion,$la_data,$io_pdf); // Imprimimos el detalle 
				$li_totasig=$io_fun_nomina->uf_formatonumerico($li_totasig);
				$li_totdedu=$io_fun_nomina->uf_formatonumerico($li_totdedu);
				$li_totapor=$io_fun_nomina->uf_formatonumerico($li_totapor);
				uf_print_total($li_totasig,$li_totdedu,$li_totapor,$io_pdf); // Imprimimos el pie de la cabecera
			}
			$rs_data->MoveNext();
		}
		uf_print_frimas ($io_pdf);
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