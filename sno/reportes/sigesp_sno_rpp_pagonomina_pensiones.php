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
	ini_set('memory_limit','2048M');
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
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_pagonomina.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_hpagonomina.php",$ls_descripcion,$ls_codnom);
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
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,750,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=407-($li_tm/2);
		$io_pdf->addText($tm,580,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=407-($li_tm/2);
		$io_pdf->addText($tm,565,11,$as_periodo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(10,$as_desnom);
		$tm=407-($li_tm/2);
		$io_pdf->addText($tm,550,10,$as_desnom); // Agregar el título
		$io_pdf->addText(700,580,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(705,573,7,date("h:i a")); // Agregar la Hora
		
		$io_pdf->ezSetDy(35);
		
		$la_data_datos[1]=array('datos'=>'<b> DATOS DEL PENSIONADO</b>');
		$la_columnas=array('datos'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 //'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('datos'=>array('justification'=>'center','width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_datos,$la_columnas,'',$la_config);	
		unset($la_data_datos);
		unset($la_columnas);
		unset($la_config);
		$la_data=array(array('cedula'=>'<b>CÉDULA</b>',
		                     'nombre'=>'<b>NOMBRE</b>',
							 'edad'=>'<b>EDAD</b>',
							 'ano'=>'<b>AÑO DE SERV.</b>',
							 'situacion'=>'<b>SITUACIÓN</b>',
							 'fecha'=>'<b>FECHA DE LA SITUACIÓN</b>',
							 'causal'=>'<b>CAUSAL</b>',
							 'dencom'=>'<b>FUERZA</b>',
							 'denran'=>'<b>GRADO</b>'));
		$la_columnas=array('cedula'=>'',
						   'nombre'=>'',
						   'edad'=>'',
						   'ano'=>'',						   
						   'situacion'=>'',
						   'fecha'=>'',
						   'causal'=>'',
						   'dencom'=>'','denran'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('cedula'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
									   'edad'=>array('justification'=>'center','width'=>40),
									   'ano'=>array('justification'=>'center','width'=>50),
						 			  
									   'situacion'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>90),									   
									   'causal'=>array('justification'=>'center','width'=>100),
									   'dencom'=>array('justification'=>'center','width'=>90),
						 			   'denran'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('name'=>'*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************');
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 4, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>715); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);			
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_cedper,$as_apenomper,$as_descar,$as_desuniadm,$as_fechasitu,$as_codcueban,$as_descom,
	                           $as_desran,$as_situacion, $as_causales,$as_edadper, $as_ano, &$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_cedper // cédula del personal
		//	    		   as_apenomper // apellidos y nombre del personal
		//	    		   as_descar // descripción del cargo
		//	    		   as_desuniadm // descripción de la unidad administrativa
		//	    		   ad_fecingper // fecha de ingreso
		//	    		   as_codcueban // código de lla cuenta bancaria
		//	    		   as_dencom // descripción del componente
		//				   as_desran // descripción del rango militar
		//				   as_situacion // situación del personal  
		//                 as_causales // descripciòn del causal
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('cedula'=>$as_cedper,
		                     'nombre'=>$as_apenomper,
							 'edad'=>$as_edadper,
							 'ano'=>$as_ano,	
							 'situacion'=>$as_situacion,
							 'fecha'=>$as_fechasitu,								  
							 'causal'=>$as_causales,
							 'dencom'=>$as_descom,
							 'denran'=>$as_desran));
		$la_columnas=array('cedula'=>'',
						   'nombre'=>'',
						   'edad'=>'',	
						   'ano'=>'',					   
						   'situacion'=>'',
						   'fecha'=>'',
						   'causal'=>'',
						   'dencom'=>'','denran'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cedula'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
									   'edad'=>array('justification'=>'center','width'=>40),
									   'ano'=>array('justification'=>'center','width'=>50),						 			   
									   'situacion'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>90),									   
									   'causal'=>array('justification'=>'center','width'=>100),
									   'dencom'=>array('justification'=>'center','width'=>90),
						 			   'denran'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_nomina_oficiales($as_prima,$as_pocentaje,$as_prima2,$as_prima3,$as_sueldo_base,$as_monto,&$io_pdf)
	{			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_nomina_oficiales
		//		   Access: private 
		//	    Arguments: 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el informaciòn básica de la nómina de los pensionados
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 28/07/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data_titulo1[1]=array('porcentaje'=>'<b> % PENSIÓN</b>',
								  'prima'=>'<b>PRIMA POR DESCENDENCIA</b>',
								  'prima2'=>'<b>PRIMA POR NO ASCENSO</b>',
								  'prima3'=>'<b>PRIMA ESPECIAL</b>',
								  'prima4'=>'<b>PENSIÓN BASE</b>',
								  'monto'=>'<b>MONTO BS.');
		$la_columnas=array('porcentaje'=>'',
		                   'prima'=>'',
						   'prima2'=>'',
						   'prima3'=>'',
						   'prima4'=>'',
						   'monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('porcentaje'=>array('justification'=>'center','width'=>130),
						               'prima'=>array('justification'=>'center','width'=>120),
									   'prima2'=>array('justification'=>'center','width'=>100),
									   'prima3'=>array('justification'=>'center','width'=>115),
									   'prima4'=>array('justification'=>'center','width'=>120),
									   'monto'=>array('justification'=>'center','width'=>130))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_titulo1,$la_columnas,'',$la_config);	
		unset($la_data_titulo1);
		unset($la_columnas);
		unset($la_config);
		$la_data_titulo2[1]=array('porcentaje'=>$as_pocentaje,
								  'prima'=>$as_prima,
								  'prima2'=>$as_prima2,
		                          'prima3'=>$as_prima3,
								  'prima4'=>$as_sueldo_base,
								  'monto'=>$as_monto);
		$la_columnas=array('porcentaje'=>'',
						   'prima'=>'',
						   'prima2'=>'',
						   'prima3'=>'',
						   'prima4'=>'',
						   'monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('porcentaje'=>array('justification'=>'center','width'=>130),
						               'prima'=>array('justification'=>'center','width'=>120),
									   'prima2'=>array('justification'=>'center','width'=>100),
									   'prima3'=>array('justification'=>'center','width'=>115),
									   'prima4'=>array('justification'=>'center','width'=>120),
									   'monto'=>array('justification'=>'center','width'=>130))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_titulo2,$la_columnas,'',$la_config);
		unset($la_data_titulo2);
		unset($la_columnas);
		unset($la_config);		
	}
      
//------------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera2($as_cedula,$as_nombre,$as_banco,$as_cta,$as_porcentaje,$ls_nex_ben,$ls_ano,&$io_pdf)
	{		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera2 por personal
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 28/07/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/*$la_data_datos[1]=array('datos'=>'<b> DATOS DEL(OS) SOBREVIVIENTE(S)</b>');
		$la_columnas=array('datos'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 //'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('datos'=>array('justification'=>'center','width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_datos,$la_columnas,'',$la_config);	
		unset($la_data_datos);
		unset($la_columnas);
		unset($la_config);*/
		$la_data_titulo1[1]=array('cedula'=>'<b>CÉDULA</b>',
		                          'nombre'=>'<b>NOMBRE</b>',
								  'edad'=>'<b>EDAD</b>',
								  'nexo'=>'<b>PARENTESCO CON EL PENSIONADO</b>',
								  'porcentaje'=>'<b>% DE PENSIÓN</b>',
								  'banco'=>'<b>BANCO</b>',
								  'cta'=>'<b>CTA. BANCARIA</b>');
		$la_columnas=array('cedula'=>'',		                   
		                   'nombre'=>'',
						   'edad'=>'',
						   'nexo'=>'',
						   'porcentaje'=>'',
						   'banco'=>'',
						   'cta'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('cedula'=>array('justification'=>'center','width'=>80),
						 			   'nombre'=>array('justification'=>'center','width'=>130),
									   'edad'=>array('justification'=>'center','width'=>80),
									   'nexo'=>array('justification'=>'center','width'=>120),
									   'porcentaje'=>array('justification'=>'center','width'=>80),
						               'banco'=>array('justification'=>'center','width'=>110),
									   'cta'=>array('justification'=>'center','width'=>115))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_titulo1,$la_columnas,'',$la_config);	
		unset($la_data_titulo1);
		unset($la_columnas);
		unset($la_config);
		
		$la_data_titulo2[1]=array('cedula'=>$as_cedula,
		                          'nombre'=>$as_nombre,
								  'edad'=>$ls_ano,
								  'nexo'=>$ls_nex_ben,
								  'porcentaje'=>$as_porcentaje,
								  'banco'=>$as_banco,
								  'cta'=>$as_cta);
		$la_columnas=array('cedula'=>'',
		                   'nombre'=>'',
						   'edad'=>'',
						   'nexo'=>'',
						   'porcentaje'=>'',
						   'banco'=>'',
						   'cta'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('cedula'=>array('justification'=>'center','width'=>80),
						 			   'nombre'=>array('justification'=>'center','width'=>130),
									   'edad'=>array('justification'=>'center','width'=>80),
									   'nexo'=>array('justification'=>'center','width'=>120),
									   'porcentaje'=>array('justification'=>'center','width'=>80),
						               'banco'=>array('justification'=>'center','width'=>110),
									   'cta'=>array('justification'=>'center','width'=>115))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_titulo2,$la_columnas,'',$la_config);	
		unset($la_data_titulo2);
		unset($la_columnas);
		unset($la_config);
	}
//-------------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/*$la_data_datos[1]=array('datos'=>'<b> DETALLE DE LA NÓMINA</b>');
		$la_columnas=array('datos'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 //'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('datos'=>array('justification'=>'center','width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_datos,$la_columnas,'',$la_config);	
		unset($la_data_datos);
		unset($la_columnas);
		unset($la_config);*/
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('codigo'=>'<b>Código</b>',
		                   'nombre'=>'<b>Denominación</b>',						  
						   'cuota'=>'<b>Cuota / Plazo </b>',
						   'asignacion'=>'<b>Asignación</b>',
						   'deduccion'=>'<b>Deducción</b>',						   
						   'neto'=>'<b>Neto</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'nombre'=>array('justification'=>'left','width'=>265), // Justificación y ancho de la columna
									   'cuota'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   
						 			   'asignacion'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna						 			   
						 			   'neto'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($ai_totalasignacion,$ai_totaldeduccion,$ai_totalaporte,$ai_total_neto,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		   Access: private 
		//	    Arguments: ai_totalasignacion // Total Asignación
		//	   			   ai_totaldeduccion // Total Deduccción
		//	   			   ai_totalaporte // Total aporte
		//	   			   ai_total_neto // Total Neto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$la_data=array(array('totales'=>'<b>Totales '.$ls_bolivares.'</b>','asignacion'=>$ai_totalasignacion,'deduccion'=>$ai_totaldeduccion,
							 'neto'=>$ai_total_neto));
		$la_columna=array('totales'=>'','asignacion'=>'','deduccion'=>'','neto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('totales'=>array('justification'=>'right','width'=>415), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna						 			
						 			   'neto'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		/*$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);*/
	}// end function uf_print_piecabecera
	//-----------------------------------------------------------------------------------------------------------------------------------
function uf_print_linea(&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piepagina
		//		   Access: private 
		//	    Arguments: ai_totasi // Total de Asignaciones
		//	   			   ai_totded // Total de Deducciones
		//	   			   ai_totapo // Total de Aportes
		//	   			   ai_totgeneral // Total de Neto a Pagar
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$la_data[1]=array('name'=>'**************************************************************************************************************************************************************************************************************************************************************************************************************');
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>715); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);			
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}
//------------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piepagina($ai_totasi,$ai_totded,$ai_totapo,$ai_totgeneral,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piepagina
		//		   Access: private 
		//	    Arguments: ai_totasi // Total de Asignaciones
		//	   			   ai_totded // Total de Deducciones
		//	   			   ai_totapo // Total de Aportes
		//	   			   ai_totgeneral // Total de Neto a Pagar
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		uf_print_linea($io_pdf);
		/*$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	*/
		$la_data=array(array('titulo'=>'<b>Total Nómina '.$ls_bolivares.': </b>','asignacion'=>$ai_totasi,
							 'deduccion'=>$ai_totded,'neto'=>$ai_totgeneral));
		$la_columna=array('titulo'=>'','asignacion'=>'','deduccion'=>'','neto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((224/255),(224/255),(224/255)), // Color de la sombra
						 'shadeCol2'=>array((224/255),(224/255),(224/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>415), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			  'neto'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);	
	}
//------------------------------------------------------------------------------------------------------------------------------------
	function calcular_anos_servicioas($fecha_ingreso,$fecha_egreso)
	  {  
		  $c = date("Y",$fecha_ingreso);	   
		  $b = date("m",$fecha_ingreso);	  
		  $a = date("d",$fecha_ingreso); 	  
		  $anos = date("Y",$fecha_egreso)-$c; 
	   
			  if(date("m",$fecha_egreso)-$b > 0){
		  
			  }elseif(date("m",$fecha_egreso)-$b == 0){
		 
			  if(date("d",$fecha_egreso)-$a <= 0)
			  {		  
			     $anos = $anos-1;	  
			  }
		  
			  }else{		  
			         $anos = $anos-1;		  
			       }  
		  return $anos;	 
      } //FIN DE calcular_anos_servicioas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	$ls_tiporeporte="0";
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
	$ls_titulo="<b>Reporte General de Pago</b>";
	$ls_periodo="<b>Período Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_tituloconcepto=$io_fun_nomina->uf_obtenervalor_get("tituloconcepto","");
	$ls_conceptoreporte=$io_fun_nomina->uf_obtenervalor_get("conceptoreporte","");
	$ls_conceptop2=$io_fun_nomina->uf_obtenervalor_get("conceptop2","");
	$ls_codubifis=$io_fun_nomina->uf_obtenervalor_get("codubifis","");
	$ls_codpai=$io_fun_nomina->uf_obtenervalor_get("codpai","");
	$ls_codest=$io_fun_nomina->uf_obtenervalor_get("codest","");
	$ls_codmun=$io_fun_nomina->uf_obtenervalor_get("codmun","");
	$ls_codpar=$io_fun_nomina->uf_obtenervalor_get("codpar","");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_pagonomina_personal_pensionado($ls_codperdes,$ls_codperhas,$ls_conceptocero,$ls_conceptoreporte,$ls_conceptop2,
													  $ls_codubifis,$ls_codpai,$ls_codest,$ls_codmun,$ls_codpar,$ls_subnomdes,$ls_subnomhas,$ls_orden); // Cargar el DS con los datos de la cabecera del reporte
	}
	if(($lb_valido==false)||(($io_report->rs_data->RecordCount()==0))) // Existe algún error ó no hay registros
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
		$io_pdf->ezSetCmMargins(4,2,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_desnom,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(750,50,10,'','',1); // Insertar el número de página
		$io_pdf->FitWindow=true;
		$li_totrow=$io_report->rs_data->RecordCount();
		$li_totasi=0;
		$li_totded=0;
		$li_totapo=0;
		$li_totgeneral=0;
        $li_i=0;
		while((!$io_report->rs_data->EOF)&&($lb_valido))
		{
	      	$li_i++;	
			$li_totalasignacion=0;
			$li_totaldeduccion=0;
			$li_totalaporte=0;
			$li_total_neto=0;
			$ls_codper=$io_report->rs_data->fields["codper"];
			$ls_cedper=$io_report->rs_data->fields["cedper"];
			$ls_apenomper=$io_report->rs_data->fields["apeper"].", ". $io_report->rs_data->fields["nomper"];
			$ls_descar=$io_report->rs_data->fields["descar"];
			$ls_desuniadm=$io_report->rs_data->fields["desuniadm"];
			$ld_fecingper=$io_funciones->uf_convertirfecmostrar($io_report->rs_data->fields["fecingper"]);
			$ls_codcueban=$io_report->rs_data->fields["codcueban"];
			$ls_descom=$io_report->rs_data->fields["dencom"];
			$ls_desran=$io_report->rs_data->fields["denran"];
			$ls_situacion=$io_report->rs_data->fields["situacion"];			
			$ls_causales=$io_report->rs_data->fields["dencausa"];
			$ls_fecha_I=$io_report->rs_data->fields["fecingper"];
			$ls_fechasitu=$io_funciones->uf_convertirfecmostrar($io_report->rs_data->fields["fecsitu"]);
			$ls_fecha_E=$io_report->rs_data->fields["fecingper"];
			$ls_fecingnom=$io_report->rs_data->fields["fecingnom"];	
				
			$ls_ano=calcular_anos_servicioas(strtotime($ls_fecha_I),strtotime($ls_fecingnom));
			$fecha_actual=date("Y/m/d"); 
			$ls_fecnacper=$io_report->rs_data->fields["fecnacper"];
			if ($ls_fecnacper!="")
			{
				$ls_edadper=calcular_anos_servicioas(strtotime($ls_fecnacper),strtotime($fecha_actual));
			}
			else
			{
				$ls_edadper=0;
			}
			if ($ls_ano<0)
			{
				$ls_ano=0;
			}
			switch($ls_situacion)
			{
				  case "1":
					$ls_situacion="Ninguno";
				  break;
				  case "2":
					$ls_situacion="Fallecido";
				  break;
				  case "3":
					$ls_situacion="Pensionado";
				  break;
				  case "4":
					$ls_situacion="Jubilado";
				  break;
				  case "5":
					$ls_situacion="Retirado";
				  break;				  		  
			}
			if ($li_i > 1)
			{
				uf_print_linea($io_pdf);
			}			
			uf_print_cabecera($ls_cedper,$ls_apenomper,$ls_descar,$ls_desuniadm,$ls_fechasitu,$ls_codcueban,$ls_descom,
			                  $ls_desran, $ls_situacion, $ls_causales, $ls_edadper, $ls_ano, $io_pdf); // Imprimimos la cabecera del registro
			
			$lb_valido1=$io_report->uf_recibo_nomina_oficiales($ls_codper);
			$li_pension=$io_report->rs_data_detalle->RecordCount();
			if (($li_pension>0)&&($lb_valido1))
			{
				while (!$io_report->rs_data_detalle->EOF)
				{
					$ls_prima=$io_report->rs_data_detalle->fields["pridesper"]; //prima por descendencia
					$ls_pocentaje=$io_report->rs_data_detalle->fields["porpenper"]; // porcentaje
					$ls_prima_NA=$io_report->rs_data_detalle->fields["prinoascper"]; //prima por no ascenso
					$ls_prima_Esp=$io_report->rs_data_detalle->fields["priespper"]; //prima especial
					$ls_sueldo_base=$io_report->rs_data_detalle->fields["suebasper"]; //sueldo base*/
					$ls_monto=number_format($io_report->rs_data_detalle->fields["monpenper"],2,",","."); //monto en bs
					
					$io_report->rs_data_detalle->MoveNext();
				}			
				uf_nomina_oficiales($ls_prima,$ls_pocentaje,$ls_prima_NA,$ls_prima_Esp,$ls_sueldo_base,$ls_monto,&$io_pdf);
			}
			$li_pension=0;	
			$lb_valido2=$io_report->uf_buscar_beneficiarios('', '',$ls_codper,$ls_codper);
			$li_bene=$io_report->rs_data_detalle2->RecordCount(); 
			if (($li_bene>0)&&($lb_valido2))
			{
				while (!$io_report->rs_data_detalle2->EOF)
				{
					$ls_ced_ben=$io_report->rs_data_detalle2->fields["cedben"]; 
					$ls_nombre_ben=$io_report->rs_data_detalle2->fields["apeben"].", ".$io_report->rs_data_detalle2->fields["nomben"]; 
					$ls_porcentaje_ben=$io_report->rs_data_detalle2->fields["porpagben"];
					$ls_banco_ben=$io_report->rs_data_detalle2->fields["banco"];				
					$ls_cta_ben=$io_report->rs_data_detalle2->fields["ctaban"];
					$ls_nex_ben=trim($io_report->rs_data_detalle2->fields["nexben"]);
					$ls_fecnacben=$io_report->rs_data_detalle2->fields["fecnacben"];
					 
					$fecha_actual=date("Y/m/d"); 
					$ls_ano=calcular_anos_servicioas(strtotime($ls_fecnacben),strtotime($fecha_actual));
					switch($ls_nex_ben)
					{
						case "-":
						     $ls_nex_ben="Niguno";
						break;
						case "C":
						     $ls_nex_ben="Conyugue";
						break;
						case "H":
						     $ls_nex_ben="Hijo";
						break;
						case "P":
						     $ls_nex_ben="Progenitor";
						break;
						case "C":
						     $ls_nex_ben="Hermano";
						break;
					}
					$io_report->rs_data_detalle2->MoveNext();
				}			
				uf_print_cabecera2($ls_ced_ben,$ls_nombre_ben,$ls_banco_ben,$ls_cta_ben,$ls_porcentaje_ben,$ls_nex_ben,
				                   $ls_ano,&$io_pdf);
				
			}	
			$li_bene=0;
			$lb_valido=$io_report->uf_pagonomina_conceptopersonal($ls_codper,$ls_conceptocero,$ls_tituloconcepto,$ls_conceptoreporte,$ls_conceptop2); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_res=$io_report->rs_data_detalle->RecordCount();
				$li_s=1;
				while(!$io_report->rs_data_detalle->EOF)
				{
					$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
					$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
					$ls_tipsal=rtrim($io_report->rs_data_detalle->fields["tipsal"]);
					$li_valsal=abs($io_report->rs_data_detalle->fields["valsal"]);
					
					$ls_repconsunicon=$io_report->rs_data_detalle->fields["repconsunicon"];
					$ls_consunicon=$io_report->rs_data_detalle->fields["consunicon"];
					$ls_cuota="";
					if (($ls_repconsunicon=='1')&&($ls_consunicon!=""))
					{
						$io_report->uf_buscar_cuotas($ls_consunicon,$ls_codper,$ls_cuota);
					}
					
					switch($ls_tipsal)
					{
						case "A":
							$li_totalasignacion=$li_totalasignacion + $li_valsal;
							$li_asignacion=number_format($li_valsal,2,",",".");
							$li_deduccion=""; 
							$li_aporte=""; 
							break;
							
						case "V1":
							$li_totalasignacion=$li_totalasignacion + $li_valsal;
							$li_asignacion=number_format($li_valsal,2,",",".");
							$li_deduccion=""; 
							$li_aporte=""; 
							break;
							
						case "W1":
							$li_totalasignacion=$li_totalasignacion + $li_valsal;
							$li_asignacion=number_format($li_valsal,2,",",".");
							$li_deduccion=""; 
							$li_aporte=""; 
							break;
							
						case "D":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=number_format($li_valsal,2,",",".");
							$li_aporte=""; 
							break;
							
						case "V2":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=number_format($li_valsal,2,",",".");
							$li_aporte=""; 
							break;
							
						case "W2":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=number_format($li_valsal,2,",",".");
							$li_aporte=""; 
							break;

						case "P1":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=number_format($li_valsal,2,",",".");
							$li_aporte=""; 
							break;

						case "V3":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=number_format($li_valsal,2,",",".");
							$li_aporte=""; 
							break;

						case "W3":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=number_format($li_valsal,2,",",".");
							$li_aporte=""; 
							break;

						case "P2":
							$li_totalaporte=$li_totalaporte + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=""; 
							$li_aporte=number_format($li_valsal,2,",",".");
							break;

						case "V4":
							$li_totalaporte=$li_totalaporte + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=""; 
							$li_aporte=number_format($li_valsal,2,",",".");
							break;

						case "W4":
							$li_totalaporte=$li_totalaporte + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=""; 
							$li_aporte=number_format($li_valsal,2,",",".");
							break;

						case "R":
							$li_asignacion=number_format($li_valsal,2,",",".");
							$li_deduccion=""; 
							$li_aporte="";
							break;
					}
					$la_data[$li_s]=array('codigo'=>$ls_codconc,'nombre'=>$ls_nomcon,'cuota'=>$ls_cuota,
					                      'asignacion'=>$li_asignacion,
										  'deduccion'=>$li_deduccion,'neto'=>'');
					$li_s++;					  
					$io_report->rs_data_detalle->MoveNext();					  
				}
  			    uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle  
				$li_total_neto=$li_totalasignacion-$li_totaldeduccion;
				$li_totasi=$li_totasi+$li_totalasignacion;
				$li_totded=$li_totded+$li_totaldeduccion;
				$li_totapo=$li_totapo+$li_totalaporte;
				$li_totgeneral=$li_totgeneral+$li_total_neto;
				$li_totalasignacion=number_format($li_totalasignacion,2,",",".");
				$li_totaldeduccion=number_format($li_totaldeduccion,2,",",".");
				$li_totalaporte=number_format($li_totalaporte,2,",",".");
				$li_total_neto=number_format($li_total_neto,2,",",".");
				uf_print_piecabecera($li_totalasignacion,$li_totaldeduccion,$li_totalaporte,$li_total_neto,$io_pdf); 			
			}
			unset($la_data);
			$io_report->rs_data->MoveNext();				
		}
		$li_totasi=number_format($li_totasi,2,",",".");
		$li_totded=number_format($li_totded,2,",",".");
		$li_totapo=number_format($li_totapo,2,",",".");
		$li_totgeneral=number_format($li_totgeneral,2,",",".");
		uf_print_piepagina($li_totasi,$li_totded,$li_totapo,$li_totgeneral,$io_pdf);
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
