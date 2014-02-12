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
	ini_set('memory_limit','512M');
	ini_set('max_execution_time','0');

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/05/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo."";
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_credencialespersonal.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo // Período
		//	    		   as_rango // Rango de Meses
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/05/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->addJpegFromFile('../../shared/imagebank/logo_hci_recibo.jpg',60,560,340,40); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=504-($li_tm/2);
		$io_pdf->addText($tm,570,11,$as_titulo); // Agregar el título
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_cedper,$as_nomper,$ad_fecingper,$ad_fecnacper,$as_turper,$as_horper,$as_cargo,$as_trabajador,
							   $as_evaluacion,$ad_fechaevaluacion,$ai_anoservpreper,$as_unidad,$as_accion,&$io_cabecera,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_cedper // Cédula del personal
		//	   			   as_nomper // Nombre del personal
		//	   			   ad_fecingper // Fecha de Ingreso
		//	   			   ad_fecnacper // Fecha de Nacimiento
		//	   			   as_turper // Turno del Personal
		//	   			   as_horper // Horario del Personal
		//	   			   as_cargo // cargo para el cual se postula
		//	   			   as_trabajador // Tipo de Trabajador
		//	   			   as_evaluacion // Fecha de Evaluación
		//	   			   ad_fechaevaluacion // Fecha de Evaluación
		//	   			   ai_anoservpreper // Años de Servicios Previos
		//	   			   as_unidad // Unidad Administrativa
		//	   			   as_accion // Accion
		//	    		   io_cabecera // objeto cabecera
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por persona
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 09/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSetY(560);
		$la_data[1]=array('titulo'=>'<b>I. DATOS DE IDENTIFICACIÓN</b>');
		$la_columna=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>525, // Ancho de la tabla
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>940))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('organismo'=>'Organismo                                                                                                       ',
						  'ingreso'=>'Fecha de Ingreso',
						  'accion'=>'Acción                                                                            ',
						  'ubicacion'=>'Ubicación Administrativa                                               ',
						  'cargo'=>'Cargo para el cual se evalua                                       ',
						  'trabajo'=>'Tipo de Trabajador');
		$la_data[2]=array('organismo'=>$_SESSION["la_empresa"]["nombre"],
						  'ingreso'=>$ad_fecingper,
						  'accion'=>$as_accion,
						  'ubicacion'=>$as_unidad,
						  'cargo'=>$as_cargo,
						  'trabajo'=>$as_trabajador);
		$la_columna=array('organismo'=>'',
						  'ingreso'=>'',
						  'accion'=>'',
						  'ubicacion'=>'',
						  'cargo'=>'',
						  'trabajo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>525, // Ancho de la tabla
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('organismo'=>array('justification'=>'center','width'=>250), // Justificación y ancho de la columna
						 			   'ingreso'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'accion'=>array('justification'=>'center','width'=>180), // Justificación y ancho de la columna
						 			   'ubicacion'=>array('justification'=>'center','width'=>180), // Justificación y ancho de la columna
						 			   'cargo'=>array('justification'=>'center','width'=>180), // Justificación y ancho de la columna
						 			   'trabajo'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('nombre'=>'Apellidos y Nombres del Aspirante                                                                                                                        ',
						  'cedula'=>'Cédula de Identidad  ',
						  'nacimiento'=>'Fecha de Nacimiento ',
						  'antiguedad'=>'Antiguedad',
						  'tipo'=>'Tipo de Evaluación                      ',
						  'evaluacion'=>'Fecha de Evaluación',
						  'turno'=>'Turno                     ',
						  'horario'=>'Horario                                        ');
		$la_data[2]=array('nombre'=>$as_nomper,
						  'cedula'=>$as_cedper,
						  'nacimiento'=>$ad_fecnacper,
						  'antiguedad'=>$ai_anoservpreper,
						  'tipo'=>$as_evaluacion,
						  'evaluacion'=>$ad_fechaevaluacion,
						  'turno'=>$as_turper,
						  'horario'=>$as_horper);
		$la_columna=array('nombre'=>'',
						  'cedula'=>'',
						  'nacimiento'=>'',
						  'antiguedad'=>'',
						  'tipo'=>'',
						  'evaluacion'=>'',
						  'turno'=>'',
						  'horario'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>525, // Ancho de la tabla
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('nombre'=>array('justification'=>'center','width'=>360), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'nacimiento'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'antiguedad'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'tipo'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
						 			   'evaluacion'=>array('justification'=>'center','width'=>75), // Justificación y ancho de la columna
						 			   'turno'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'horario'=>array('justification'=>'center','width'=>115))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_educacionformal($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_educacionformal
		//		   Access: private 
		//	    Arguments: la_data // arreglo de la data de educación formal
		//	    		   ai_totano // Total Años
		//	    		   ai_totmes // Total Mes
		//	    		   ai_totdia // Total Días
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los datos de la Educación formal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 09/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_datos[1]=array('titulo'=>'');
		$la_datos[2]=array('titulo'=>'<b>II. DATOS DE EDUCACIÓN FORMAL</b>');
		$la_columna=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>525, // Ancho de la tabla
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>940))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);
		unset($la_datos);
		unset($la_columna);
		unset($la_config);
		$la_datos[1]=array('etapa'=>'Etapa',
						   'institucion'=>'Institución',
						   'carrera'=>'Carrera / Título Obtenido',
						   'aprobado'=>'           Aprobado                 Si                     No        ',
						   'duracion'=>'                         Duración                                   Desde                       Hasta    ',
						   'ano'=>'Ultimo      Año Aprobado');
		$la_columna=array('etapa'=>'',
						  'institucion'=>'',
						  'carrera'=>'',
						  'aprobado'=>'',
						  'duracion'=>'',
						  'ano'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>455, // Ancho de la tabla
						 'width'=>800, // Ancho de la tabla
						 'maxWidth'=>800, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'cols'=>array('etapa'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'institucion'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna
						 			   'carrera'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna
						 			   'aprobado'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'duracion'=>array('justification'=>'center','width'=>140), // Justificación y ancho de la columna
						 			   'ano'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);
		unset($la_datos);
		unset($la_columna);
		unset($la_config);
		$la_columna=array('etapa'=>'',
						  'institucion'=>'',
						  'carrera'=>'',
						  'si'=>'',
						  'no'=>'',
						  'desde'=>'',
						  'hasta'=>'',
						  'ano'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>455, // Ancho de la tabla
						 'width'=>800, // Ancho de la tabla
						 'maxWidth'=>800, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'cols'=>array('etapa'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
						 			   'institucion'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'carrera'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'si'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'no'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'desde'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'hasta'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'ano'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_educacionformal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_educacioninformal($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_educacioninformal
		//		   Access: private 
		//	    Arguments: la_data // arreglo de la data de educación formal
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los detalles de la Educación Informal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_datos[1]=array('titulo'=>'');
		$la_datos[2]=array('titulo'=>'<b>III. DATOS DE EDUCACIÓN INFORMAL</b>');
		$la_columna=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>525, // Ancho de la tabla
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>940))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);
		unset($la_datos);
		unset($la_columna);
		unset($la_config);
		$la_datos[1]=array('etapa'=>'Etapa',
						   'institucion'=>'Institución',
						   'carrera'=>'Descripción',
						   'aprobado'=>'           Aprobado                 Si                     No        ',
						   'duracion'=>'                                           Duración                                           Desde                         Hasta                             Horas              ');
		$la_columna=array('etapa'=>'',
						  'institucion'=>'',
						  'carrera'=>'',
						  'aprobado'=>'',
						  'duracion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>455, // Ancho de la tabla
						 'width'=>800, // Ancho de la tabla
						 'maxWidth'=>800, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'cols'=>array('etapa'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'institucion'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna
						 			   'carrera'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna
						 			   'aprobado'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'duracion'=>array('justification'=>'right','width'=>220))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);
		unset($la_datos);
		unset($la_columna);
		unset($la_config);
		$la_columna=array('etapa'=>'',
						  'institucion'=>'',
						  'carrera'=>'',
						  'si'=>'',
						  'no'=>'',
						  'desde'=>'',
						  'hasta'=>'',
						  'hora'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>455, // Ancho de la tabla
						 'width'=>800, // Ancho de la tabla
						 'maxWidth'=>800, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'cols'=>array('etapa'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
						 			   'institucion'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'carrera'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'si'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'no'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'desde'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'hasta'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'hora'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_educacioninformal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_trabajoanterior($la_data,$ai_totano,$ai_totmes,$ai_totdia,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_trabajoanterior
		//		   Access: private 
		//	    Arguments: la_data // arreglo de la data de educación formal
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los detalles de los trabajos anteriores
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_datos[1]=array('titulo'=>'');
		$la_datos[2]=array('titulo'=>'<b>IV. DATOS DE LA EXPERIENCIA LABORAL</b>');
		$la_columna=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>525, // Ancho de la tabla
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>940))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);
		unset($la_datos);
		unset($la_columna);
		unset($la_config);
		$la_datos[1]=array('empresa'=>'Organismo / Empresa',
						   'cargo'=>'Cargo Ocupado',
						   'tipo'=>'Tipo de Experiencia',
						   'duracion'=>'                         Duración                                   Desde                       Hasta    ',
						   'antiguedad'=>'    Antiguedad     Años    Meses    Días');
		$la_columna=array('empresa'=>'',
						  'cargo'=>'',
						  'tipo'=>'',
						  'duracion'=>'',
						  'antiguedad'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>455, // Ancho de la tabla
						 'width'=>800, // Ancho de la tabla
						 'maxWidth'=>800, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'cols'=>array('empresa'=>array('justification'=>'center','width'=>240), // Justificación y ancho de la columna
						 			   'cargo'=>array('justification'=>'center','width'=>240), // Justificación y ancho de la columna
						 			   'tipo'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'duracion'=>array('justification'=>'center','width'=>140), // Justificación y ancho de la columna
						 			   'antiguedad'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);
		unset($la_datos);
		unset($la_columna);
		unset($la_config);
		$la_columna=array('empresa'=>'',
						  'cargo'=>'',
						  'tipo'=>'',
						  'desde'=>'',
						  'hasta'=>'',
						  'ano'=>'',
						  'mes'=>'',
						  'dia'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>455, // Ancho de la tabla
						 'width'=>800, // Ancho de la tabla
						 'maxWidth'=>800, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'cols'=>array('empresa'=>array('justification'=>'left','width'=>240), // Justificación y ancho de la columna
						 			   'cargo'=>array('justification'=>'left','width'=>240), // Justificación y ancho de la columna
						 			   'tipo'=>array('justification'=>'left','width'=>100), // Justificación y ancho de la columna
						 			   'desde'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'hasta'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'ano'=>array('justification'=>'center','width'=>26), // Justificación y ancho de la columna
						 			   'mes'=>array('justification'=>'center','width'=>27), // Justificación y ancho de la columna
						 			   'dia'=>array('justification'=>'center','width'=>27))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_datos);
		unset($la_columna);
		unset($la_config);
		$la_datos[1]=array('blanco'=>'',
						   'total'=>'Total',
						   'ano'=>$ai_totano,
						   'mes'=>$ai_totmes,
						   'dia'=>$ai_totdia);
		$la_columna=array('blanco'=>'',
						  'total'=>'',
						  'ano'=>'',
						  'mes'=>'',
						  'dia'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>455, // Ancho de la tabla
						 'width'=>800, // Ancho de la tabla
						 'maxWidth'=>800, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'cols'=>array('blanco'=>array('justification'=>'left','width'=>650), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'ano'=>array('justification'=>'center','width'=>26), // Justificación y ancho de la columna
						 			   'mes'=>array('justification'=>'center','width'=>27), // Justificación y ancho de la columna
						 			   'dia'=>array('justification'=>'center','width'=>27))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);
	}// end function uf_print_trabajoanterior
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cargarfamiliar($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cargarfamiliar
		//		   Access: private 
		//	    Arguments: la_data // arreglo de la data de educación formal
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los detalles de la carga familiar
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_datos[1]=array('titulo'=>'');
		$la_datos[2]=array('titulo'=>'<b>V. DATOS DE LA CARGA FAMILIAR (Solamente hijos menores de 18 años)</b>');
		$la_columna=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>525, // Ancho de la tabla
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>940))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);
		unset($la_datos);
		unset($la_columna);
		unset($la_config);
		$la_datos[1]=array('parentesco'=>'Parentesco',
						   'cedula'=>'Cédula',
						   'sexo'=>'Género',
						   'nombre'=>'Apellidos y Nombres',
						   'fecha'=>'Fecha de Nacimiento',
						   'edad'=>'Edad');
		$la_columna=array('parentesco'=>'',
						  'cedula'=>'',
						  'sexo'=>'',
						  'nombre'=>'',
						  'fecha'=>'',
						  'edad'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>455, // Ancho de la tabla
						 'width'=>800, // Ancho de la tabla
						 'maxWidth'=>800, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'cols'=>array('parentesco'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'sexo'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>400), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'edad'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);
		unset($la_datos);
		unset($la_columna);
		unset($la_config);
		$la_columna=array('parentesco'=>'',
						  'cedula'=>'',
						  'sexo'=>'',
						  'nombre'=>'',
						  'fecha'=>'',
						  'edad'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>455, // Ancho de la tabla
						 'width'=>800, // Ancho de la tabla
						 'maxWidth'=>800, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'cols'=>array('parentesco'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'sexo'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>400), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'edad'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_cargarfamiliar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_resultados($ai_sueldo,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_resultados
		//		   Access: private 
		//	    Arguments: ai_sueldo // Sueldo Propuesto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los resultados de la evaluación
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_datos[1]=array('titulo'=>'');
		$la_datos[2]=array('titulo'=>'<b>VI. RESULTADOS DE LA EVALUACIÓN REQUISITOS MÍNIMOS</b>');
		$la_columna=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>525, // Ancho de la tabla
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>940))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);
		unset($la_datos);
		unset($la_columna);
		unset($la_config);
		$la_datos[1]=array('decision'=>'Decisión Tomada',
						   'alternativas'=>'Alternativas',
						   'conclusion'=>'Conclusión del Análisis',
						   'responsable'=>'Apellidos y Nombres del analista responsable de la Evaluación',
						   'firma'=>'Firma de la Coordinación de Recursos');
		$la_columna=array('decision'=>'',
						  'alternativas'=>'',
						  'conclusion'=>'',
						  'responsable'=>'',
						  'firma'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>525, // Ancho de la tabla
						 'width'=>950, // Ancho de la tabla
						 'maxWidth'=>950, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'cols'=>array('decision'=>array('justification'=>'center','width'=>170), // Justificación y ancho de la columna
						 			   'alternativas'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
						 			   'conclusion'=>array('justification'=>'center','width'=>260), // Justificación y ancho de la columna
						 			   'responsable'=>array('justification'=>'center','width'=>210), // Justificación y ancho de la columna
						 			   'firma'=>array('justification'=>'center','width'=>180))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);
		unset($la_datos);
		unset($la_columna);
		unset($la_config);
		$la_datos[1]=array('decision1'=>'Reune los Recaudos',
						   'decision2'=>'____________',
						   'alternativa1'=>'A',
						   'alternativa2'=>'___________',
						   'conclusion'=>'',
						   'responsable'=>'',
						   'firma'=>'');
		$la_datos[2]=array('decision1'=>'',
						   'decision2'=>'',
						   'alternativa1'=>'B',
						   'alternativa2'=>'___________',
						   'conclusion'=>'',
						   'responsable'=>'',
						   'firma'=>'');
		$la_datos[3]=array('decision1'=>'No Reune los Recaudos',
						   'decision2'=>'____________',
						   'alternativa1'=>'C',
						   'alternativa2'=>'___________',
						   'conclusion'=>'',
						   'responsable'=>'',
						   'firma'=>'');
		$la_datos[4]=array('decision1'=>'',
						   'decision2'=>'',
						   'alternativa1'=>'D',
						   'alternativa2'=>'___________',
						   'conclusion'=>'',
						   'responsable'=>'',
						   'firma'=>'');
		$la_columna=array('decision1'=>'',
						  'decision2'=>'',
						  'alternativa1'=>'',
						  'alternativa2'=>'',
						  'conclusion'=>'',
						  'responsable'=>'',
						  'firma'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>525, // Ancho de la tabla
						 'width'=>950, // Ancho de la tabla
						 'maxWidth'=>950, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'cols'=>array('decision1'=>array('justification'=>'center','width'=>95), // Justificación y ancho de la columna
						 			   'decision2'=>array('justification'=>'center','width'=>75), // Justificación y ancho de la columna
						 			   'alternativa1'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'alternativa2'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'conclusion'=>array('justification'=>'center','width'=>260), // Justificación y ancho de la columna
						 			   'responsable'=>array('justification'=>'center','width'=>210), // Justificación y ancho de la columna
						 			   'firma'=>array('justification'=>'center','width'=>180))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);
		unset($la_datos);
		unset($la_columna);
		unset($la_config);
		$io_pdf->ezSety(496.5);
		$la_datos[1]=array('titulo'=>'<b>Observaciones</b>');
		$la_datos[2]=array('titulo'=>'');
		$la_datos[3]=array('titulo'=>'');
		$la_datos[4]=array('titulo'=>'');
		$la_datos[5]=array('titulo'=>'');
		$la_datos[6]=array('titulo'=>'');
		$la_datos[7]=array('titulo'=>'');
		$la_datos[8]=array('titulo'=>'');
		$la_datos[9]=array('titulo'=>'');
		$la_datos[10]=array('titulo'=>'');
		$la_datos[11]=array('titulo'=>'');
		$la_datos[12]=array('titulo'=>'');
		$la_datos[13]=array('titulo'=>'');
		$la_columna=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>930, // Ancho de la tabla
						 'width'=>130, // Ancho de la tabla
						 'maxWidth'=>130, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>130))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);
		unset($la_datos);
		unset($la_columna);
		unset($la_config);
		$io_pdf->ezSetDy(-15);
		$la_datos[1]=array('titulo'=>'<b>Sueldo Propuesto</b>');
		$la_datos[2]=array('titulo'=>'');
		$la_datos[3]=array('titulo'=>$ai_sueldo);
		$la_datos[4]=array('titulo'=>'');
		$la_columna=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>930, // Ancho de la tabla
						 'width'=>130, // Ancho de la tabla
						 'maxWidth'=>130, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>130))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);
	}// end function uf_print_resultados
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>EVALUACIÓN DE CREDENCIALES</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_activo=$io_fun_nomina->uf_obtenervalor_get("activo","");
	$ls_egresado=$io_fun_nomina->uf_obtenervalor_get("egresado","");
	$ls_causaegreso=$io_fun_nomina->uf_obtenervalor_get("causaegreso","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_masculino=$io_fun_nomina->uf_obtenervalor_get("masculino","");
	$ls_femenino=$io_fun_nomina->uf_obtenervalor_get("femenino","");
	$ls_cargo=$io_fun_nomina->uf_obtenervalor_get("codcar","");
	$ls_trabajador=$io_fun_nomina->uf_obtenervalor_get("tiptrab","");
	$ls_evaluacion=$io_fun_nomina->uf_obtenervalor_get("tipeva","");
	$ld_fechaevaluacion=$io_fun_nomina->uf_obtenervalor_get("feceva","");
	$li_sueldo=$io_fun_nomina->uf_obtenervalor_get("sueldo","");
	$ls_unidad=$io_fun_nomina->uf_obtenervalor_get("unidad","");
	$ls_accion=$io_fun_nomina->uf_obtenervalor_get("accion","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_credencialespersonal_personal($ls_codperdes,$ls_codperhas,$ls_activo,$ls_egresado,$ls_causaegreso,
														 		$ls_masculino,$ls_femenino,$ls_orden); //Obtenemos el detalle del reporte
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
		$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(4.35,1,1,1); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(950,50,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->DS->getRowCount("codper");
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$io_cabecera=$io_pdf->openObject(); // Creamos el objeto cabecera
			$ls_codper=$io_report->DS->data["codper"][$li_i];
			$ls_cedper=$io_report->DS->data["cedper"][$li_i];
			$ls_nomper=$io_report->DS->data["apeper"][$li_i].", ".$io_report->DS->data["nomper"][$li_i];
			$ld_fecingper=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecingper"][$li_i]);
			$ld_fecnacper=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecnacper"][$li_i]);
			$ls_turper=$io_report->DS->data["turper"][$li_i];
			$ls_horper=$io_report->DS->data["horper"][$li_i];
			$li_anoservpreper=$io_report->DS->data["anoservpreper"][$li_i];
			uf_print_cabecera($ls_cedper,$ls_nomper,$ld_fecingper,$ld_fecnacper,$ls_turper,$ls_horper,$ls_cargo,$ls_trabajador,
							  $ls_evaluacion,$ld_fechaevaluacion,$li_anoservpreper,$ls_unidad,$ls_accion,&$io_cabecera,&$io_pdf);
			$lb_valido=$io_report->uf_credencialespersonal_educacionformal($ls_codper); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_formal=$io_report->DS_detalle->getRowCount("codestrea");
				for($li_j=1;($li_j<=$li_formal);$li_j++)
				{
					$ls_tipestrea=$io_report->DS_detalle->data["tipestrea"][$li_j];
					$ls_insestrea=$io_report->DS_detalle->data["insestrea"][$li_j];
					$ls_titestrea=$io_report->DS_detalle->data["titestrea"][$li_j];
					$ld_feciniact=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["feciniact"][$li_j]);
					$ld_fecfinact=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["fecfinact"][$li_j]);
					$ls_aprestrea=$io_report->DS_detalle->data["aprestrea"][$li_j];
					$ls_anoaprestrea=$io_report->DS_detalle->data["anoaprestrea"][$li_j];
					switch($ls_tipestrea)
					{
						case "0": 
							$ls_etapa="PRIMARIA";
							break;
						case "1": 
							$ls_etapa="CICLO BASICO";
							break;
						case "2": 
							$ls_etapa="CICLO DIVERSIFICADO";
							break;
						case "3": 
							$ls_etapa="PREGRADO";
							break;
						case "4": 
							$ls_etapa="ESPECIALIZACIÓN";
							break;
						case "5": 
							$ls_etapa="MAESTRIA";
							break;
						case "6": 
							$ls_etapa="POSTGRADO";
							break;
						case "7": 
							$ls_etapa="DOCTORADO";
							break;
					}
					switch($ls_aprestrea)
					{
						case "0": 
							$ls_si="";
							$ls_no="X";
							break;
						case "1": 
							$ls_si="X";
							$ls_no="";
							break;
					}
					$la_data[$li_j]=array('etapa'=>$ls_etapa,'institucion'=>$ls_insestrea,'carrera'=>$ls_titestrea,
										  'si'=>$ls_si,'no'=>$ls_no,'desde'=>$ld_feciniact,'hasta'=>$ld_fecfinact,
										  'ano'=>$ls_anoaprestrea);
				}
				for($li_j=$li_formal+1;($li_j<=1);$li_j++)
				{
					$la_data[$li_j]=array('etapa'=>'','institucion'=>'','carrera'=>'','si'=>'','no'=>'','desde'=>'','hasta'=>'','ano'=>'');
				}
				uf_print_educacionformal($la_data,&$io_pdf);
				unset($la_data);
			}
			$io_report->DS_detalle->reset_ds();
			$lb_valido=$io_report->uf_credencialespersonal_educacioninformal($ls_codper); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_informal=$io_report->DS_detalle->getRowCount("codestrea");
				for($li_j=1;($li_j<=$li_informal);$li_j++)
				{
					$ls_tipestrea=$io_report->DS_detalle->data["tipestrea"][$li_j];
					$ls_insestrea=$io_report->DS_detalle->data["insestrea"][$li_j];
					$ls_titestrea=$io_report->DS_detalle->data["titestrea"][$li_j];
					$ld_feciniact=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["feciniact"][$li_j]);
					$ld_fecfinact=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["fecfinact"][$li_j]);
					$ls_aprestrea=$io_report->DS_detalle->data["aprestrea"][$li_j];
					$ls_horestrea=$io_report->DS_detalle->data["horestrea"][$li_j];
					switch($ls_tipestrea)
					{
						case "8": 
							$ls_etapa="TALLER";
							break;
						case "9": 
							$ls_etapa="CURSO";
							break;
					}
					switch($ls_aprestrea)
					{
						case "0": 
							$ls_si="";
							$ls_no="X";
							break;
						case "1": 
							$ls_si="X";
							$ls_no="";
							break;
					}
					$la_data[$li_j]=array('etapa'=>$ls_etapa,'institucion'=>$ls_insestrea,'carrera'=>$ls_titestrea,
										  'si'=>$ls_si,'no'=>$ls_no,'desde'=>$ld_feciniact,'hasta'=>$ld_fecfinact,
										  'hora'=>$ls_horestrea);
				}
				for($li_j=$li_informal+1;($li_j<=1);$li_j++)
				{
					$la_data[$li_j]=array('etapa'=>'','institucion'=>'','carrera'=>'','si'=>'','no'=>'','desde'=>'','hasta'=>'','hora'=>'');
				}
				uf_print_educacioninformal($la_data,&$io_pdf);
				unset($la_data);
			}
			$io_report->DS_detalle->reset_ds();
			$lb_valido=$io_report->uf_credencialespersonal_trabajosanterior($ls_codper); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_trabajo=$io_report->DS_detalle->getRowCount("codtraant");
				$li_totano=0;
				$li_totmes=0;
				$li_totdia=0;
				for($li_j=1;($li_j<=$li_trabajo);$li_j++)
				{
					$ls_emptraant=$io_report->DS_detalle->data["emptraant"][$li_j];
					$ls_ultcartraant=$io_report->DS_detalle->data["ultcartraant"][$li_j];
					$ls_emppubtraant=$io_report->DS_detalle->data["emppubtraant"][$li_j];
					$ld_fecingtraant=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["fecingtraant"][$li_j]);
					$ld_fecrettraant=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["fecrettraant"][$li_j]);
					$li_anolab=$io_report->DS_detalle->data["anolab"][$li_j];
					$li_meslab=$io_report->DS_detalle->data["meslab"][$li_j];
					$li_dialab=$io_report->DS_detalle->data["dialab"][$li_j];
					$li_totano=$li_totano+$li_anolab;
					$li_totmes=$li_totano+$li_meslab;
					$li_totdia=$li_totano+$li_dialab;
					switch($ls_emppubtraant)
					{
						case "0":
							$ls_emppubtraant="EMPRESA PRIVADA";
							break;
						case "1":
							$ls_emppubtraant="EMPRESA PUBLICA";
							break;
					}
					$la_data[$li_j]=array('empresa'=>$ls_emptraant,'cargo'=>$ls_ultcartraant,'tipo'=>$ls_emppubtraant,
										  'desde'=>$ld_fecingtraant,'hasta'=>$ld_fecrettraant,'ano'=>$li_anolab,
										  'mes'=>$li_meslab,'dia'=>$li_dialab);
				}
				for($li_j=$li_trabajo+1;($li_j<=1);$li_j++)
				{
					$la_data[$li_j]=array('empresa'=>'','cargo'=>'','tipo'=>'','desde'=>'','hasta'=>'','ano'=>'','mes'=>'','dia'=>'');
				}
				uf_print_trabajoanterior($la_data,$li_totano,$li_totmes,$li_totdia,&$io_pdf);
				unset($la_data);
			}
			$io_report->DS_detalle->reset_ds();
			$lb_valido=$io_report->uf_credencialespersonal_cargafamiliar($ls_codper); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_familiar=$io_report->DS_detalle->getRowCount("cedfam");
				$li_hijos=0;
				for($li_j=1;($li_j<=$li_familiar);$li_j++)
				{
					$ls_cedfam=$io_report->DS_detalle->data["cedfam"][$li_j];
					$ls_nomfam=$io_report->DS_detalle->data["apefam"][$li_j].", ".$io_report->DS_detalle->data["nomfam"][$li_j];
					$ls_sexfam=$io_report->DS_detalle->data["sexfam"][$li_j];
					$ld_fecnacfam=$io_report->DS_detalle->data["fecnacfam"][$li_j];
					$ld_hoy=date('Y');
					$ld_fecha=substr($ld_fecnacfam,0,4);
					$li_edad=$ld_hoy-$ld_fecha;
					$ls_parentesco="";
					$ls_sexo="";		
					switch($ls_sexfam)
					{
						case "F":
							$ls_parentesco="Hija";
							$ls_sexo="Femenino";
							break;
						case "M":
							$ls_parentesco="Hijo";
							$ls_sexo="Masculino";
							break;
					}			
					if(intval(date('m'))<intval(substr($ld_fecnacfam,5,2)))
					{
						$li_edad=$li_edad-1;
					}
					else
					{
						if(intval(date('m'))==intval(substr($ld_fecnacfam,5,2)))
						{
							if(intval(date('d'))<intval(substr($ld_fecnacfam,8,2)))
							{
								$li_edad=$li_edad-1;
							}
						}
					}
					$ld_fecnacfam=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["fecnacfam"][$li_j]);
					$la_data[$li_j]=array('parentesco'=>$ls_parentesco,'cedula'=>$ls_cedfam,'sexo'=>$ls_sexo,'nombre'=>$ls_nomfam,
										  'fecha'=>$ld_fecnacfam,'edad'=>$li_edad);
				}
				for($li_j=$li_familiar+1;($li_j<=1);$li_j++)
				{
					$la_data[$li_j]=array('parentesco'=>'','cedula'=>'','sexo'=>'','nombre'=>'','fecha'=>'','edad'=>'');
				}
				uf_print_cargarfamiliar($la_data,&$io_pdf);
				unset($la_data);
			}
			$io_report->DS_detalle->reset_ds();
			uf_print_resultados($li_sueldo,&$io_pdf);
			$io_pdf->stopObject($io_cabecera); // Detener el objeto cabecera
			if($li_i<$li_totrow)
			{
				$io_pdf->ezNewPage(); // Insertar una nueva página
			}
			unset($io_cabecera);
			//unset($la_data);
		}
		if($lb_valido) // Si no ocurrio ningún error
		{
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