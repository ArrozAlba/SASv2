<?php

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Constancia de Inscripcion  a Concurso
//  ORGANISMO: 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//-----------------------------------------------------------------------------------------------------------------------------------
///Elaborado por: Ing. María Beatriz Unda
//-----------------------------------------------------------------------------------------------------------------------------------
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
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/09/2008		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_srh;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("SRH","sigesp_srh_p_inscripcion_concurso.php",$ls_descripcion);
		return $lb_valido;
	}
	
//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_print_encabezado_pagina(&$io_pdf)
	    {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/09/2008		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		
		$io_pdf->addText(540,770,6,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(546,764,6,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
		 $io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		
		$io_pdf->restoreState();
	    $io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina	

//-------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_concurso($as_titulo,$as_descon,$as_codcon, $as_codcar,&$io_pdf)
	{
								  
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_concurso
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el título y detalle del concurso
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/09/2008
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(700);
		$la_data=array(array('titulo1'=>'<b>'.$as_descon.'</b>'));					
		$la_columnas=array('titulo1'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('titulo1'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data=array(array('titulo1'=>'<b>'.($as_titulo).'</b>'));					
		$la_columnas=array('titulo1'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('titulo1'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->ezSetY(650);
		$la_data[1]=array('codcar'=>'<b>CARGO</b>',
		                  'codcar2'=>$as_codcar);	
		$la_columnas=array('codcar'=>'',
		                   'codcar2'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>200, // Ancho de la tabla
						 'maxWidth'=>200, // Ancho Máximo de la tabla
						 'xPos'=>'450', // Orientación de la tabla
				      	 'cols'=>array('codcar'=>array('justification'=>'rigth','width'=>50),
						               'codcar2'=>array('justification'=>'left','width'=>150))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('codcon'=>'<b>CÓDIGO</b>',
		                 'codcon2'=>$as_codcon);	
		$la_columnas=array('codcon'=>'',
		                   'codcon2'=>'');				
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>200, // Ancho de la tabla
						 'maxWidth'=>200, // Ancho Máximo de la tabla
						 'xPos'=>'450', // Orientación de la tabla
				      	 'cols'=>array('codcon'=>array('justification'=>'rigth','width'=>50),
						               'codcon2'=>array('justification'=>'left','width'=>150))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
	}// end function uf_print_encabezado_pagina	

	
//-------------------------------------------------------------------------------------------------------------------------------//
	function uf_print_datos_personales($as_apeper,$as_nomper,$as_codper,$as_fecnacper,$as_lugarnac,$as_nacper,$as_sexper,
			                           $as_edocivper,$as_dirper,$as_telhabper,$as_telmovper,&$io_pdf)
	{
								  
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_datos_personales
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los datos personales del concursante
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/09/2008
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('titulo1'=>'<b>DATOS PERSONALES</b>'));					
		$la_columnas=array('titulo1'=>'');					
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('titulo1'=>array('justification'=>'left','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('name'=>'<b>APELLIDO</b>',
		                  'name2'=>'<b>NOMRE</b>');	
		$la_columnas=array('name'=>'',
		                   'name2'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'left','width'=>250),
						               'name2'=>array('justification'=>'left','width'=>250))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('name'=>$as_apeper,
		                  'name2'=>$as_nomper);	
		$la_columnas=array('name'=>'',
		                   'name2'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'lef','width'=>250),
						               'name2'=>array('justification'=>'left','width'=>250))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('name'=>'<b>CÉDULA DE IDENTIDAD</b>',
		                  'name2'=>'<b>FECHA DE NACIMIENTO</b>', 
						  'name3'=>'<b>LUGAR DE NACIMIENTO</b>');	
		$la_columnas=array('name'=>'',
		                   'name2'=>'',
						   'name3'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'left','width'=>150),
						               'name2'=>array('justification'=>'left','width'=>150),
									   'name3'=>array('justification'=>'left','width'=>200),)); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('name'=>$as_codper,
		                  'name2'=>$as_fecnacper, 
						  'name3'=>$as_lugarnac);	
		$la_columnas=array('name'=>'',
		                   'name2'=>'',
						   'name3'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'left','width'=>150),
						               'name2'=>array('justification'=>'left','width'=>150),
									   'name3'=>array('justification'=>'left','width'=>200),)); // Justificación y ancho de la 
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
			$la_data[1]=array('name'=>'<b>NACIONALIDAD</b>',
		                  'name2'=>'<b>GÉNERO</b>', 
						  'name3'=>'<b>ESTADO CIVIL</b>');	
		$la_columnas=array('name'=>'',
		                   'name2'=>'',
						   'name3'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'left','width'=>150),
						               'name2'=>array('justification'=>'left','width'=>150),
									   'name3'=>array('justification'=>'left','width'=>200),)); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('name'=>$as_nacper,
		                  'name2'=>$as_sexper, 
						  'name3'=>$as_edocivper);	
		$la_columnas=array('name'=>'',
		                   'name2'=>'',
						   'name3'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'left','width'=>150),
						               'name2'=>array('justification'=>'left','width'=>150),
									   'name3'=>array('justification'=>'left','width'=>200),)); // Justificación y ancho de la 
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('name'=>'<b>DIRECCIÓN</b>');	
		$la_columnas=array('name'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'left','width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('name'=>$as_dirper);	
		$la_columnas=array('name'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'lef','width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
		$la_data[1]=array('name'=>'<b>Nº TELÉFONO HABITACIÓN</b>',
		                  'name2'=>'<b>Nº TELÉFONO MÓVIL</b>');	
		$la_columnas=array('name'=>'',
		                   'name2'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'left','width'=>250),
						               'name2'=>array('justification'=>'left','width'=>250))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('name'=>$as_telhabper,
		                  'name2'=>$as_telmovper);	
		$la_columnas=array('name'=>'',
		                   'name2'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'lef','width'=>250),
						               'name2'=>array('justification'=>'left','width'=>250))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
	}// end function uf_print_encabezado_pagina	

//---------------------------------------------------------------------------------------------------------------------------------
function uf_print_datos_estudios($aa_data,&$io_pdf)
	{
								  
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_datos_estudios
		//		   Access: private 
		//	    Arguments: aa_data // Arreglo con la data de los estudios
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los datos personales del concursante
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/09/2008
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
		$la_data=array(array('titulo1'=>'<b>FORMACIÓN ACADÉMICA / PROFESIONAL</b>'));					
		$la_columnas=array('titulo1'=>'');					
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('titulo1'=>array('justification'=>'left','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('name'=>'<b>NIVEL</b>',
		                  'name2'=>'<b>CARRERA</b>',
						  'name3'=>'<b>INSTITUTO</b>',
						  'name4'=>'<b>AÑO FINALIZACIÓN  </b>',
						  'name5'=>'<b>AÑOS APROBADOS</b>',
						  'name6'=>'<b>TITULADO</b>');	
		$la_columnas=array('name'=>'',
		                  'name2'=>'',
						  'name3'=>'',
						  'name4'=>'',
						  'name5'=>'',
						  'name6'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'center','width'=>85),
						               'name2'=>array('justification'=>'center','width'=>108),
									   'name3'=>array('justification'=>'center','width'=>108),
									   'name4'=>array('justification'=>'center','width'=>72),
									   'name5'=>array('justification'=>'center','width'=>71),
									   'name6'=>array('justification'=>'center','width'=>57))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
			
		$la_columnas=array('nivel'=>'',
		                  'carrera'=>'',
						  'instituto'=>'',
						  'anofin'=>'',
						  'anoapr'=>'',
						  'titulo'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('nivel'=>array('justification'=>'left','width'=>85),
						               'carrera'=>array('justification'=>'left','width'=>108),
									   'instituto'=>array('justification'=>'left','width'=>108),
									   'anofin'=>array('justification'=>'center','width'=>72),
									   'anoapr'=>array('justification'=>'center','width'=>71),
									   'titulo'=>array('justification'=>'center','width'=>57))); // Justificación y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas,'',$la_config);
        unset($aa_data);
		unset($la_columnas);
		unset($la_config);
 } 
 //---------------------------------------------------------------------------------------------------------------------------------
function uf_print_datos_cursos($aa_data,&$io_pdf)
	{
								  
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_datos_cursos
		//		   Access: private 
		//	    Arguments: aa_data // Arreglo con la data de los cursos
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los datos personales del concursante
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/09/2008
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
		$la_data=array(array('titulo1'=>'<b>EDUCACIÓN INFORMAL (ÚLTIMOS REALIZADOS)</b>'));					
		$la_columnas=array('titulo1'=>'');					
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('titulo1'=>array('justification'=>'left','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('name'=>'<b>CURSOS</b>',
		                  'name2'=>'<b>MÁS DE 200 HORAS</b>',
						  'name3'=>'<b>ENTRE 151-200 HORAS</b>',
						  'name4'=>'<b>ENTRE 101-150 HORAS</b>',
						  'name5'=>'<b>ENTRE  51-100 HORAS</b>',
						  'name6'=>'<b>ENTRE  10-50  HORAS</b>',
						  'name7'=>'<b>OTRO</b>');	
		$la_columnas=array('name'=>'',
		                  'name2'=>'',
						  'name3'=>'',
						  'name4'=>'',
						  'name5'=>'',
						  'name6'=>'',
						  'name7'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'center','width'=>200),
						               'name2'=>array('justification'=>'center','width'=>50),
									   'name3'=>array('justification'=>'center','width'=>50),
									   'name4'=>array('justification'=>'center','width'=>50),
									   'name5'=>array('justification'=>'center','width'=>50),
									   'name6'=>array('justification'=>'center','width'=>50),
									   'name7'=>array('justification'=>'center','width'=>50))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
			
		$la_columnas=array('curso'=>'',
		                  'curso1'=>'',
						  'curso2'=>'',
						  'curso3'=>'',
						  'curso4'=>'',
						  'curso5'=>'',
						  'curso6'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('curso'=>array('justification'=>'left','width'=>200),
						               'curso1'=>array('justification'=>'center','width'=>50),
									   'curso2'=>array('justification'=>'center','width'=>50),
									   'curso3'=>array('justification'=>'center','width'=>50),
									   'curso4'=>array('justification'=>'center','width'=>50),
									   'curso5'=>array('justification'=>'center','width'=>50),
									   'curso6'=>array('justification'=>'center','width'=>50))); // Justificación y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas,'',$la_config);
        unset($aa_data);
		unset($la_columnas);
		unset($la_config);
 }
//---------------------------------------------------------------------------------------------------------------------------------
function uf_print_datos_trabajos($aa_data,&$io_pdf)
	{
								  
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_datos_trabajos
		//		   Access: private 
		//	    Arguments: aa_data // Arreglo con la data de los cursos
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los datos personales del concursante
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/09/2008
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
		$la_data=array(array('titulo1'=>'<b>EXPERIENCIA LABORAL</b>'));					
		$la_columnas=array('titulo1'=>'');					
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('titulo1'=>array('justification'=>'left','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('name'=>'<b>EMPRESA O INSTITUCIÓN</b>',
		                  'name2'=>'<b>CARGO DESEMPEÑADO</b>',
						  'name3'=>'<b>            FECHA                 (DESDE - HASTA)</b>');	
		$la_columnas=array('name'=>'',
		                  'name2'=>'',
						  'name3'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'center','width'=>180),
						               'name2'=>array('justification'=>'center','width'=>180),
									   'name3'=>array('justification'=>'center','width'=>140))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
			
		$la_columnas1=array('trab'=>'',
		                  'trab1'=>'',
						  'trab2'=>'');					
		$la_config1=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('trab'=>array('justification'=>'left','width'=>180),
						               'trab1'=>array('justification'=>'left','width'=>180),
									   'trab2'=>array('justification'=>'center','width'=>140))); // Justificación y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas1,'',$la_config1);
        unset($aa_data);
		unset($la_columnas1);
		unset($la_config1);
 }
//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_datos_familiares($aa_data,&$io_pdf)
	{
								  
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_datos_familiares
		//		   Access: private 
		//	    Arguments: aa_data // Arreglo con la data de los familiares
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los datos personales del concursante
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/09/2008
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
		$la_data=array(array('titulo1'=>'<b>CARGA FAMILIAR</b>'));					
		$la_columnas=array('titulo1'=>'');					
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('titulo1'=>array('justification'=>'left','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('name'=>'<b>APELLIDOS Y NOMBRES</b>',
		                  'name2'=>'<b>FECHA DE NACIMIENTO</b>',
						  'name3'=>'<b>PARENTESCO</b>');	
		$la_columnas=array('name'=>'',
		                  'name2'=>'',
						  'name3'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'center','width'=>300),
						               'name2'=>array('justification'=>'center','width'=>100),
									   'name3'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
			
		$la_columnas=array('nombre'=>'',
		                  'fecnac'=>'',
						  'nexo'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('nombre'=>array('justification'=>'left','width'=>300),
						               'fecnac'=>array('justification'=>'center','width'=>100),
									   'nexo'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas,'',$la_config);
        unset($aa_data);
		unset($la_columnas);
		unset($la_config);
 }

//---------------------------------------------------------------------------------------------------------------------------------
function uf_print_final($as_nombre, $as_cedula,$as_descon,&$io_pdf)
	{
								  
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_final
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime la declaración final de la contantcia
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/09/2008
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
		$la_data=array(array('texto'=>'<b>Yo, '.$as_nombre.'</b> titular de la Cédula de Identidad número <b>V- '.$as_cedula.'</b> por medio de la presente declaro lo siguiente: 1) Que conozco en su totalidad la Ley del Servicio Exterior; 2) Que cumplo con los requisitos exigidos para el cargo para el cual estoy concursando; 3) Que los datos suministrados son verdaderos y cualquier declaración falsa o engañosa puede dar como resultados la denegación a concursar; y 4) Que no recae sobre mi persona inhabilitación o incompatibilidad alguna que me impida el ingreso o desempeño de un cargo en la función pública en la República Bolivariana de Venezuela'));					
		$la_columnas=array('texto'=>'');					
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('texto'=>array('justification'=>'left','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);	
		
		$la_data1=array(array('texto1'=>'<b>ESTA PLANILLA NO GARANTIZA SU PARTICIPACIÓN EN EL '.$as_descon.', PUES QUEDA SUJETO A VERIFICACIÓN DE LOS DOCUMENTOS Y A LA EVALUACIÓN</b>'));					
		$la_columnas=array('texto1'=>'');					
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>300, // Ancho de la tabla
						 'maxWidth'=>300, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('texto1'=>array('justification'=>'center','width'=>300))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data1,$la_columnas,'',$la_config);
		unset($la_data1);
		unset($la_columnas);
		unset($la_config);		
 }

//--------------------------------------------------------------------------------------------------------------------------------
   function uf_print_datos_requisitos($aa_data,$as_descon,&$io_pdf)
	{
								  
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_datos_requisitos
		//		   Access: private 
		//	    Arguments: aa_data // Arreglo con la data de los requisitos del concurso
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los datos personales del concursante
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/09/2008
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
		$la_data=array(array('titulo1'=>'<b>REQUISITOS PARA '.$as_descon.'</b>'));					
		$la_columnas=array('titulo1'=>'');					
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('titulo1'=>array('justification'=>'left','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('name'=>'<b>RECAUDOS</b>',
		                  'name2'=>'<b>ENTREGA</b>',
						  'name3'=>'<b>CANTIDAD</b>');	
		$la_columnas=array('name'=>'',
		                  'name2'=>'',
						  'name3'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'center','width'=>340),
						               'name2'=>array('justification'=>'center','width'=>80),
									   'name3'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
			
		$la_columnas=array('req'=>'',
		                  'req1'=>'',
						  'req2'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('req'=>array('justification'=>'left','width'=>340),
						               'req1'=>array('justification'=>'center','width'=>80),
									   'req2'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas,'',$la_config);
        unset($aa_data);
		unset($la_columnas);
		unset($la_config);
 }

//---------------------------------------------------------------------------------------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/utilidades/class_funciones_srh.php");
	$io_fun_srh=new class_funciones_srh('../../');
	require_once("class_folder/sigesp_srh_class_report_2.php");
	$io_report=new sigesp_srh_class_report_2();
//----------------------------------------------------  Parámetros del encabezado  -------------------------------------------
	$ls_titulo="PLANILLA DE INSCRIPCIÓN";
	
//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------	
	$ls_tiporeporte=$io_fun_srh->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
 	$ls_codper=$io_fun_srh->uf_obtenervalor_get("codper","");
	$ls_descon=$io_fun_srh->uf_obtenervalor_get("descon","");
	$ls_descon=strtoupper($ls_descon);	
	$ls_codcon=$io_fun_srh->uf_obtenervalor_get("codcon","");	
	$ls_codcar=$io_fun_srh->uf_obtenervalor_get("codcar","");	
//---------------------------------------------------------------------------------------------------------------------------------
   
    $lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
        $lb_valido=$io_report->uf_select_concursante($ls_codcon,$ls_codper);
		if ($lb_valido==false)
		{
		    print("<script language=JavaScript>");
			print(" alert('No hay nada que reportar');"); 
			print(" close();");
			print("</script>");
		}		   
		else  // Imprimimos el reporte
		{       
		    error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(3.6,5,3,3); // Configuración de los margenes en centímetros
			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página			
			uf_print_encabezado_pagina(&$io_pdf);			
			uf_print_detalle_concurso ($ls_titulo,$ls_descon,$ls_codcon,$ls_codcar,$io_pdf);
		    
			$ls_apeper=$io_report->DS->data["apeper"][1];
			$ls_nomper=$io_report->DS->data["nomper"][1];
			$ls_cedper=$io_report->DS->data["codper"][1];
			$ls_fecnacper=$io_report->DS->data["fecnacper"][1];
			$ls_fecnacper=$io_funciones->uf_formatovalidofecha($ls_fecnacper);
			$ls_fecnacper=$io_funciones->uf_convertirfecmostrar($ls_fecnacper);
			$ls_desest=$io_report->DS->data["desest"][1];
			$ls_despais=$io_report->DS->data["despai"][1];
			$ls_lugarnac= $ls_desest." - ".$ls_despais;
			$ls_nacper=$io_report->DS->data["nacper"][1];
			if ($ls_nacper=='V')
			{
				$ls_nacper='VENEZOLANO';
			}
			elseif ($ls_nacper=='E')
			{
				$ls_nacper='EXTRANJERO';
			}
			$ls_sexper=$io_report->DS->data["sexper"][1];
			if ($ls_sexper=='F')
			{
				$ls_sexper='FEMENINO';
			}
			elseif ($ls_sexper=='M')
			{
				$ls_sexper='MASCULINO';
			}
			$ls_edocivper=$io_report->DS->data["edocivper"][1];
			switch ($ls_edocivper)
			{
				case "S":
					$ls_edocivper="SOLTERO";
					break;
				
				case "C":
					$ls_edocivper="CASADO";
					break;
				
				case "D":
					$ls_edocivper="DIVORCIADO";
					break;
				
				case "V":
					$ls_edocivper="VIUDO";
					break;
				
				case "K":
					$ls_edocivper="CONCUBINO";
					break;
			}
			$ls_dirper=$io_report->DS->data["dirper"][1];
			$ls_telhabper=$io_report->DS->data["telhabper"][1];
			$ls_telmovper=$io_report->DS->data["telmovper"][1];
			$ls_tipper=$io_report->DS->data["tipper"][1];
										  
			uf_print_datos_personales ($ls_apeper,$ls_nomper,$ls_codper,$ls_fecnacper,$ls_lugarnac,$ls_nacper,$ls_sexper,
			                           $ls_edocivper,$ls_dirper,$ls_telhabper,$ls_telmovper,$io_pdf);

///////////////////////// DATOS DE FORMACION ACADEMICA	//////////////////////////////////
		
			if ($ls_tipper=='E')
			{
				$lb_valido_fam=$io_report->uf_select_esrudios_concursante($ls_codcon,$ls_codper);
				if ($lb_valido_fam)
				{
				   $li_totrow=$io_report->DS2->getRowCount("codper");				   
				   for($li_i=1;$li_i<=$li_totrow;$li_i++)
				   {
						$ls_nivest=$io_report->DS2->data["nivestper"][$li_i];							
						switch($ls_nivest)
						{							
							case "":
								$ls_nivest="NINGUNO";
								break;
							case "0":
								$ls_nivest="NINGUNO";
								break;
							case "1":
								$ls_nivest="PRIMARIA";
								break;
							case "2":
								$ls_nivest="BACHILLER";
								break;
							case "3":
								$ls_nivest="TÉCNICO SUPERIOR";
								break;
						   case "4":
								$ls_nivest="UNIVERSITARIO";
								break;
						   case "5":
								$ls_nivest="MAESTRÍA";
								break;
						  case "6":
								$ls_nivest="POSTGRADO";
								break;
						  case "7":
								$ls_nivest="DOCTORADO";
								break;
						}
						$ls_carest=$io_report->DS2->data["carestper"][$li_i];
						$ls_insest=$io_report->DS2->data["insestper"][$li_i];
						$li_anofinest=$io_report->DS2->data["anofinestper"][$li_i];
						$li_anoaprest=$io_report->DS2->data["anoaprestper"][$li_i];
						$ls_titest=$io_report->DS2->data["titestper"][$li_i];
						if ($ls_titest=='1')
						{
							$ls_titest='SI';					
						}
						else
						{
							$ls_titest='NO';
						}
						
						$la_data1[$li_i]=array('nivel'=>$ls_nivest,'carrera'=>$ls_carest,'instituto'=>$ls_insest,
											  'anofin'=>$li_anofinest,'anoapr'=>$li_anoaprest,'titulo'=>$ls_titest); 
					   }
					   uf_print_datos_estudios($la_data1,$io_pdf);
					   unset($la_data1);
					}
			} // Fin si el tipo es externo
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////// DATOS DE CURSOS	//////////////////////////////////
		
		if ($ls_tipper=='E')
		{ 
		    $io_pdf->ezNewPage(); // Insertar una nueva página
			$lb_valido_cur=$io_report->uf_select_cursos_concursante($ls_codcon,$ls_codper);
			if ($lb_valido_cur)
			{
			   $li_totrow=$io_report->DS2->getRowCount("codper");
			   for($li_i=1;$li_i<=$li_totrow;$li_i++)
			   {
					$ls_descur=$io_report->DS2->data["descurper"][$li_i];	
					$ls_horcur=$io_report->DS2->data["horcurper"][$li_i];							
					switch($ls_horcur)
					{							
						case "0":
							$ls_hora1="X";
							$ls_hora2="";
							$ls_hora3="";
							$ls_hora4="";
							$ls_hora5="";
							$ls_hora6="";
						break;
						case "1":
							$ls_hora1="";
							$ls_hora2="X";
							$ls_hora3="";
							$ls_hora4="";
							$ls_hora5="";
							$ls_hora6="";
						break;
						case "2":
							$ls_hora1="";
							$ls_hora2="";
							$ls_hora3="X";
							$ls_hora4="";
							$ls_hora5="";
							$ls_hora6="";
						break;
						case "3":
							$ls_hora1="";
							$ls_hora2="";
							$ls_hora3="";
							$ls_hora4="X";
							$ls_hora5="";
							$ls_hora6="";
						break;
						case "4":
							$ls_hora1="";
							$ls_hora2="";
							$ls_hora3="";
							$ls_hora4="";
							$ls_hora5="X";
							$ls_hora6="";
						break;
					   case "5":
							$ls_hora1="";
							$ls_hora2="";
							$ls_hora3="";
							$ls_hora4="";
							$ls_hora5="";
							$ls_hora6="X";
						break;
					}
					$la_data2[$li_i]=array('curso'=>$ls_descur,'curso1'=>$ls_hora1,'curso2'=>$ls_hora2,'curso3'=>$ls_hora3,
											  'curso4'=>$ls_hora4,'curso5'=>$ls_hora5,'curso6'=>$ls_hora6); 
				   }
				   uf_print_datos_cursos($la_data2,$io_pdf);
				   unset($la_data2);
				}
				
		} // Fin si el tipo es externo
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////// DATOS DE EXPERICIA LABORAL	//////////////////////////////////
		if ($ls_tipper=='E')
		{
			$lb_valido_trab=$io_report->uf_select_trabajos_concursante($ls_codcon,$ls_codper);
			if ($lb_valido_trab)
			{
			   $li_totrow=$io_report->DS2->getRowCount("codper");
			   for($li_i=1;$li_i<=$li_totrow;$li_i++)
			   {
					$ls_empresa=$io_report->DS2->data["emptraper"][$li_i];	
					$ls_cargo=$io_report->DS2->data["cartraant"][$li_i];
					$ld_fecini=$io_report->DS2->data["fecingtraper"][$li_i];
					$$ld_fecini=$io_funciones->uf_formatovalidofecha($ld_fecini);
					$ld_fecini=$io_funciones->uf_convertirfecmostrar($ld_fecini);
					$ld_fecfin=$io_report->DS2->data["fecegrtraper"][$li_i];
					$ld_fecfin=$io_funciones->uf_formatovalidofecha($ld_fecfin);
					$ld_fecfin=$io_funciones->uf_convertirfecmostrar($ld_fecfin);
					$ld_fecha= $ld_fecini." - ".$ld_fecfin;
					
					$la_data3[$li_i]=array('trab'=>$ls_empresa,'trab1'=>$ls_cargo,'trab2'=>$ld_fecha); 
				 }
				 uf_print_datos_trabajos($la_data3,$io_pdf);
				 unset($la_data3);
			  }
				
		} // Fin si el tipo es externo
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	///////////////////////// DATOS DE FAMILIARES//////////////////////////////////
		if ($ls_tipper=='E')
		{
			$lb_valido_fam=$io_report->uf_select_familiares_concursante($ls_codcon,$ls_codper);
			if ($lb_valido_fam)
			{
			   $li_totrow=$io_report->DS2->getRowCount("codper");
			   for($li_i=1;$li_i<=$li_totrow;$li_i++)
			   {
					$ls_nomfam=$io_report->DS2->data["nomfamper"][$li_i];	
					$ls_apefam=$io_report->DS2->data["apefamper"][$li_i];
					$ls_nombre=$ls_apefam." ".$ls_nomfam;
					$ld_fecnacfam=$io_report->DS2->data["fecnacfamper"][$li_i];
					$ld_fecnacfam=$io_funciones->uf_formatovalidofecha($ld_fecnacfam);
					$ld_fecnacfam=$io_funciones->uf_convertirfecmostrar($ld_fecnacfam);
					$ls_nexo=$io_report->DS2->data["nexfamper"][$li_i];					
					switch ($ls_nexo) 
					{
					  case 'C' :
						$ls_nexo='CONYUGE';
					  break;
					  case 'H' :
						$ls_nexo='HIJO';
						break;
					  case 'P' :
						$ls_nexo='PROGENITO';
						break;
					 case 'E' :
						$ls_nexo='HERMANO';
						break;	
				   }
					$la_data4[$li_i]=array('nombre'=>$ls_nombre,'fecnac'=>$ld_fecnacfam,'nexo'=>$ls_nexo); 
			   }
			   uf_print_datos_familiares($la_data4,$io_pdf);
			   unset($la_data4);
			}
			 
		} // Fin si el tipo es externo
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		$ls_nombre_completo=strtoupper(($ls_apeper." ".$ls_nomper));
		uf_print_final($ls_nombre_completo,$ls_codper,$ls_descon,$io_pdf);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////// REQUISITOS	//////////////////////////////////
		$io_pdf->ezNewPage(); // Insertar una nueva página
		$lb_valido_req=$io_report->uf_select_requisitos_concursante($ls_codcon,$ls_codper);
		if ($lb_valido_req)
		{
		   $li_totrow=$io_report->DS2->getRowCount("codper");
		   for($li_i=1;$li_i<=$li_totrow;$li_i++)
		   {
				$ls_reqcon=$io_report->DS2->data["desreqcon"][$li_i];	
				$ls_entreqcon=$io_report->DS2->data["entreqcon"][$li_i];
				if ($ls_entreqcon==1)
				{
					$ls_entreqcon="SI";
				}
				else
				{
					$ls_entreqcon="NO";
				}
				$ls_canentreqcon=$io_report->DS2->data["canentreqcon"][$li_i];							
				
				$la_data5[$li_i]=array('req'=>$ls_reqcon,'req1'=>$ls_entreqcon,'req2'=>$ls_canentreqcon); 
			}
			 uf_print_datos_requisitos($la_data5,$ls_descon,$io_pdf);
			 unset($la_data5);		  
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

   }
 
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
	
		
	}	
	
?>	