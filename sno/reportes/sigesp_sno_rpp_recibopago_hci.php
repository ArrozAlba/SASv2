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

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo,$ai_tipo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Arreglo de las variables de seguridad
		//	    		   as_desnom // Arreglo de las variables de seguridad
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_recibopago.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_hrecibopago.php",$ls_descripcion,$ls_codnom);
		}
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina1($as_titulo,$as_descripcion,$ad_fechasper,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina1
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_descripcion // Descripción de la nómina
		//	    		   ad_fechasper // Fecha de Finalización del Período
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->addJpegFromFile('../../shared/imagebank/logo_hci_recibo.jpg',50,745,320,30); // Agregar Logo
		$io_pdf->ezSety(770);		
		$la_data[1]=array('tiponomina'=>'Tipo de Personal:                  ', 'fecha'=>'Para el:       ');
		$la_data[2]=array('tiponomina'=>'<b>'.$as_descripcion.'</b>', 'fecha'=>'<b>'.$ad_fechasper.'</b>');
		$la_columna=array('tiponomina'=>'','fecha'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'xPos'=>480,
						 'fontSize' => 7, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>180, // Ancho de la tabla
						 'maxWidth'=>180, // Ancho Máximo de la tabla
						 'cols'=>array('tiponomina'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>60))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$io_pdf->ezSetDy(-3);		
		$la_data[1]=array('titulo'=>$as_titulo);
		$la_columna=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>7, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>525, // Ancho de la tabla
						 'maxWidth'=>525, // Ancho Máximo de la tabla
						 'xPos'=>308,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>525))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina1
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina2($as_titulo,$as_descripcion,$ad_fechasper,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina2
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_descripcion // Descripción de la nómina
		//	    		   ad_fechasper // Fecha de Finalización del Período
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->addJpegFromFile('../../shared/imagebank/logo_hci_recibo.jpg',50,325,320,30); // Agregar Logo
		$io_pdf->ezSety(350);		
		$la_data[1]=array('tiponomina'=>'Tipo de Personal:                  ', 'fecha'=>'Para el:       ');
		$la_data[2]=array('tiponomina'=>'<b>'.$as_descripcion.'</b>', 'fecha'=>'<b>'.$ad_fechasper.'</b>');
		$la_columna=array('tiponomina'=>'','fecha'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'xPos'=>480,
						 'fontSize' => 7, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>180, // Ancho de la tabla
						 'maxWidth'=>180, // Ancho Máximo de la tabla
						 'cols'=>array('tiponomina'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>60))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$io_pdf->ezSetDy(-3);		
		$la_data[1]=array('titulo'=>$as_titulo);
		$la_columna=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>7, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>525, // Ancho de la tabla
						 'maxWidth'=>525, // Ancho Máximo de la tabla
						 'xPos'=>308,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>525))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina2
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera1($as_cedper,$as_nomper,$as_descar,$as_desuniadm,$ai_sueper,$ad_fecingper,$as_nomban,$as_codcueban,&$io_cabecera,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera1
		//		   Access: private 
		//	    Arguments: as_cedper // Cédula del personal
		//	    		   as_nomper // Nombre del personal
		//	    		   as_descar // Decripción del cargo
		//	    		   as_desuniadm // Descripción de la unidad administrativa
		//	    		   ai_sueper // Sueldo
		//	    		   ad_fecingper // Fecha de Ingreso
		//	    		   io_cabecera // objeto cabecera
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/05/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSety(730.5);
		$la_data[1]=array('unidad'=>'Unidad de Origen / Dirección:', 'ocupacion'=>'Ocupación:');
		$la_data[2]=array('unidad'=>'<b>			'.$as_desuniadm.'</b>', 'ocupacion'=>'<b>			'.$as_descar.'</b>');
		$la_columna=array('unidad'=>'','ocupacion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>7, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>525, // Ancho de la tabla
						 'maxWidth'=>525, // Ancho Máximo de la tabla
						 'rowGap' => 0,
						 'xPos'=>308,
						 'cols'=>array('unidad'=>array('justification'=>'left','width'=>300), // Justificación y ancho de la columna
						 			   'ocupacion'=>array('justification'=>'left','width'=>225))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('nombre'=>'Apellidos y Nombres:', 'cedula'=>'Cédula N°:          ', 'sueldo'=>'Sueldo Mensual:   ', 'fecha'=>'Fecha de Ingreso:');
		$la_data[2]=array('nombre'=>'<b>				'.$as_nomper.'</b>', 'cedula'=>'<b>'.$as_cedper.'</b>', 'sueldo'=>'<b>'.$ai_sueper.'</b>', 'fecha'=>'<b>'.$ad_fecingper.'</b>');
		$la_columna=array('nombre'=>'','cedula'=>'','sueldo'=>'','fecha'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>7, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>525, // Ancho de la tabla
						 'maxWidth'=>525, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'xPos'=>308,
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>300), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'sueldo'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>75))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('banco'=>'Entidad Financiera:', 'cuenta'=>'Número de Cuenta:');
		$la_data[2]=array('banco'=>'<b>			'.$as_nomban.'</b>', 'cuenta'=>'<b>			'.$as_codcueban.'</b>');
		$la_columna=array('banco'=>'','cuenta'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>7, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>525, // Ancho de la tabla
						 'maxWidth'=>525, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'xPos'=>308,
						 'cols'=>array('banco'=>array('justification'=>'left','width'=>300), // Justificación y ancho de la columna
						 			   'cuenta'=>array('justification'=>'left','width'=>225))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('denomasig'=>'<b>CONCEPTOS</b>', 'valorasig'=>'<b>REMUNERACIONES</b>', 'denomdedu'=>'<b>CONCEPTO</b>','valordedu'=>'<b>DEDUCCIONES</b>'));
		$la_columna=array('denomasig'=>'',
						  'valorasig'=>'',
						  'denomdedu'=>'',
						  'valordedu'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>6, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>525, // Ancho de la tabla
						 'maxWidth'=>525, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'xPos'=>308,
						 'cols'=>array('denomasig'=>array('justification'=>'left','width'=>182.5), // Justificación y ancho de la columna
						 			   'valorasig'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'denomdedu'=>array('justification'=>'left','width'=>182.5), // Justificación y ancho de la columna
						 			   'valordedu'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
	}// end function uf_print_cabecera1
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera2($as_cedper,$as_nomper,$as_descar,$as_desuniadm,$ai_sueper,$ad_fecingper,$as_nomban,$as_codcueban,&$io_cabecera,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_cedper // Cédula del personal
		//	    		   as_nomper // Nombre del personal
		//	    		   as_descar // Decripción del cargo
		//	    		   as_desuniadm // Descripción de la unidad administrativa
		//	    		   ai_sueper // Sueldo
		//	    		   ad_fecingper // Fecha de Ingreso
		//	    		   io_cabecera // objeto cabecera
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/05/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSety(311);		
		$la_data[1]=array('unidad'=>'Unidad de Origen / Dirección:', 'ocupacion'=>'Ocupación:');
		$la_data[2]=array('unidad'=>'<b>			'.$as_desuniadm.'</b>', 'ocupacion'=>'<b>			'.$as_descar.'</b>');
		$la_columna=array('unidad'=>'','ocupacion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>525, // Ancho de la tabla
						 'maxWidth'=>525, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'xPos'=>308,
						 'cols'=>array('unidad'=>array('justification'=>'left','width'=>300), // Justificación y ancho de la columna
						 			   'ocupacion'=>array('justification'=>'left','width'=>225))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('nombre'=>'Apellidos y Nombres:', 'cedula'=>'Cédula N°:          ', 'sueldo'=>'Sueldo Mensual:   ', 'fecha'=>'Fecha de Ingreso:');
		$la_data[2]=array('nombre'=>'<b>				'.$as_nomper.'</b>', 'cedula'=>'<b>'.$as_cedper.'</b>', 'sueldo'=>'<b>'.$ai_sueper.'</b>', 'fecha'=>'<b>'.$ad_fecingper.'</b>');
		$la_columna=array('nombre'=>'','cedula'=>'','sueldo'=>'','fecha'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>525, // Ancho de la tabla
						 'maxWidth'=>525, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'xPos'=>308,
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>300), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'sueldo'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>75))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('banco'=>'Entidad Financiera:', 'cuenta'=>'Número de Cuenta:');
		$la_data[2]=array('banco'=>'<b>			'.$as_nomban.'</b>', 'cuenta'=>'<b>			'.$as_codcueban.'</b>');
		$la_columna=array('banco'=>'','cuenta'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>525, // Ancho de la tabla
						 'maxWidth'=>525, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'xPos'=>308,
						 'cols'=>array('banco'=>array('justification'=>'left','width'=>300), // Justificación y ancho de la columna
						 			   'cuenta'=>array('justification'=>'left','width'=>225))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('denomasig'=>'<b>CONCEPTOS</b>', 'valorasig'=>'<b>REMUNERACIONES</b>', 'denomdedu'=>'<b>CONCEPTO</b>','valordedu'=>'<b>DEDUCCIONES</b>'));
		$la_columna=array('denomasig'=>'',
						  'valorasig'=>'',
						  'denomdedu'=>'',
						  'valordedu'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>525, // Ancho de la tabla
						 'maxWidth'=>525, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'xPos'=>308,
						 'cols'=>array('denomasig'=>array('justification'=>'left','width'=>182.5), // Justificación y ancho de la columna
						 			   'valorasig'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'denomdedu'=>array('justification'=>'left','width'=>182.5), // Justificación y ancho de la columna
						 			   'valordedu'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/05/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('denomasig'=>'',
						  'valorasig'=>'',
						  'denomdedu'=>'',
						  'valordedu'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>525, // Ancho de la tabla
						 'maxWidth'=>525, // Ancho Máximo de la tabla
						 'xPos'=>308,
						 'cols'=>array('denomasig'=>array('justification'=>'left','width'=>182.5), // Justificación y ancho de la columna
						 			   'valorasig'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'denomdedu'=>array('justification'=>'left','width'=>182.5), // Justificación y ancho de la columna
						 			   'valordedu'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera1($ai_toting,$ai_totded,$ai_totnet,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera1
		//		   Access: private 
		//	    Arguments: ai_toting // Total Ingresos
		//	   			   ai_totded // Total Deducciones
		//	   			   ai_totnet // Total Neto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/05/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares, $io_monedabsf, $ls_tiporeporte;
		
		$io_piepagina=$io_pdf->openObject(); // Creamos el objeto pie de página
		$io_pdf->saveState();
		$io_pdf->ezSety(480);
		$la_data[1]=array('denominacion'=>'', 
		                  'asignaciones'=>'Total Asignaciones:    ', 
						  'deducciones'=>'Total Deducciones:    ',
						  'neto'=>'Neto a Pagar '.$ls_bolivares.':            ');
		$la_data[2]=array('denominacion'=>'<b>TOTAL '.$ls_bolivares.' EMPLEADO EN LA QUINCENA</b>', 
		                  'asignaciones'=>'<b>'.$ai_toting.'</b>', 
						  'deducciones'=>'<b>'.$ai_totded.'</b>',
						  'neto'=>'<b>'.$ai_totnet.'</b>');
		$la_columna=array('denominacion'=>'',
						  'asignaciones'=>'',
						  'deducciones'=>'',
						  'neto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>525, // Ancho de la tabla
						 'maxWidth'=>525, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'xPos'=>308,
						 'cols'=>array('denominacion'=>array('justification'=>'center','width'=>285), // Justificación y ancho de la columna
						 			   'asignaciones'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'deducciones'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'neto'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('conforme'=>'','espacio'=>'');
		$la_data[2]=array('conforme'=>'<b>Recibi Conforme:</b>_________________________________','espacio'=>'');
		$la_columna=array('conforme'=>'','espacio'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>525, // Ancho de la tabla
						 'maxWidth'=>525, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'xPos'=>308,
						 'cols'=>array('conforme'=>array('justification'=>'left','width'=>325),
						 		 	   'espacio'=>array('justification'=>'left','width'=>200))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_piepagina,'all');
		$io_pdf->stopObject($io_piepagina); // Detener el objeto pie de página
	}// end function uf_print_pie_cabecera1
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera2($ai_toting,$ai_totded,$ai_totnet,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera
		//		   Access: private 
		//	    Arguments: ai_toting // Total Ingresos
		//	   			   ai_totded // Total Deducciones
		//	   			   ai_totnet // Total Neto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/05/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares, $io_monedabsf, $ls_tiporeporte;
		
		$io_piepagina=$io_pdf->openObject(); // Creamos el objeto pie de página
		$io_pdf->saveState();
		$io_pdf->ezSety(85);
		$la_data[1]=array('denominacion'=>'', 
		                  'asignaciones'=>'Total Asignaciones:    ', 
						  'deducciones'=>'Total Deducciones:    ',
						  'neto'=>'Neto a Pagar '.$ls_bolivares.':            ');
		$la_data[2]=array('denominacion'=>'<b>TOTAL '.$ls_bolivares.' EMPLEADO EN LA QUINCENA</b>', 
		                  'asignaciones'=>'<b>'.$ai_toting.'</b>', 
						  'deducciones'=>'<b>'.$ai_totded.'</b>',
						  'neto'=>'<b>'.$ai_totnet.'</b>');
		$la_columna=array('denominacion'=>'',
						  'asignaciones'=>'',
						  'deducciones'=>'',
						  'neto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>525, // Ancho de la tabla
						 'maxWidth'=>525, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'xPos'=>308,
						 'cols'=>array('denominacion'=>array('justification'=>'center','width'=>285), // Justificación y ancho de la columna
						 			   'asignaciones'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'deducciones'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'neto'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('conforme'=>'','espacio'=>'');
		$la_data[2]=array('conforme'=>'<b>Recibi Conforme:</b>_________________________________','espacio'=>'');
		$la_columna=array('conforme'=>'','espacio'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>525, // Ancho de la tabla
						 'maxWidth'=>525, // Ancho Máximo de la tabla
						 'rowGap' => 0.5,
						 'xPos'=>308,
						 'cols'=>array('conforme'=>array('justification'=>'left','width'=>325),
						 		 	   'espacio'=>array('justification'=>'left','width'=>200))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_piepagina,'all');
		$io_pdf->stopObject($io_piepagina); // Detener el objeto pie de página
	}// end function uf_print_pie_cabecera2
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	$ls_tiporeporte="0";
	$ls_bolivares ="Bs.";
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
	require_once("../../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
	$io_monedabsf=new sigesp_c_reconvertir_monedabsf();				
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$li_adelanto=$_SESSION["la_nomina"]["adenom"];
	$ls_tipnom=$_SESSION["la_nomina"]["tipnom"];
	$ls_descripcion="";
	switch($ls_tipnom)
	{
		case "1":
			$ls_descripcion="Empleado Fijo";
			break;
		case "2":
			$ls_descripcion="Empleado Contratado";
			break;
		case "3":
			$ls_descripcion="Obrero Fijo";
			break;
		case "4":
			$ls_descripcion="Obrero Contratado";
			break;
		case "5":
			$ls_descripcion="Docente Fijo";
			break;
		case "6":
			$ls_descripcion="Docente Contratado";
			break;
		case "7":
			$ls_descripcion="Jubilado";
			break;
		case "8":
			$ls_descripcion="Comision de Servicios";
			break;
		case "9":
			$ls_descripcion="Libre Nombramiento";
			break;
		case "10": // Militar
			$ls_descripcion="Militar";
			break;
		case "11": // Honorarios Profesionales
			$ls_descripcion="Honorarios Profesionales";
			break;
		case "12": // Pensionado
			$ls_descripcion="Pensionado";
			break;
		case "13": // Suplente
			$ls_descripcion="Suplente";
			break;
		case "14": // Contratado
			$ls_descripcion="Contratado";
			break;
		case "15": // Incapacitados
			$ls_descripcion="Incapacitados";
			break;
	}
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$ls_titulo="<b>COMPROBANTE DE PAGO</b>";
	$ls_periodo="Periodo: <b>".$ls_peractnom."</b> del <b>".$ld_fecdesper."</b> al <b>".$ld_fechasper."</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_coduniadm=$io_fun_nomina->uf_obtenervalor_get("coduniadm","");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_conceptop2=$io_fun_nomina->uf_obtenervalor_get("conceptop2","");
	$ls_conceptoreporte=$io_fun_nomina->uf_obtenervalor_get("conceptoreporte","");
	$ls_tituloconcepto=$io_fun_nomina->uf_obtenervalor_get("tituloconcepto","");
	$ls_quincena=$io_fun_nomina->uf_obtenervalor_get("quincena","-");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
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
		$lb_valido=$io_report->uf_recibopago_personal($ls_codperdes,$ls_codperhas,$ls_coduniadm,$ls_conceptocero,$ls_conceptop2,
													  $ls_conceptoreporte,$ls_codubifis,$ls_codpai,$ls_codest,$ls_codmun,$ls_codpar,
													  $ls_subnomdes,$ls_subnomhas,$ls_orden); // Cargar el DS con los datos de la cabecera del reporte
	}
	if(($lb_valido==false) || ($io_report->rs_data->RecordCount()==0)) // Existe algún error ó no hay registros
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
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(1,1,1,2); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina1($ls_titulo,$ls_descripcion,$ld_fechasper,$io_pdf); // Imprimimos el encabezado de la página
		uf_print_encabezado_pagina2($ls_titulo,$ls_descripcion,$ld_fechasper,$io_pdf); // Imprimimos el encabezado de la página
		$li_totrow=$io_report->rs_data->RecordCount();
		$li_reg=1;
		$li_i=1;
		while((!$io_report->rs_data->EOF)&&($lb_valido))
		{
			$li_toting=0;
			$li_totded=0;
			$ls_codper=$io_report->rs_data->fields["codper"];
			$ls_cedper=$io_report->rs_data->fields["cedper"];
			$ls_nomper=$io_report->rs_data->fields["apeper"].", ".$io_report->rs_data->fields["nomper"];
			$ls_descar=$io_report->rs_data->fields["descar"];
			$ls_desuniadm=$io_report->rs_data->fields["desuniadm"];
			$ls_nomban=$io_report->rs_data->fields["banco"];
			$ls_codcueban=$io_report->rs_data->fields["codcueban"];
			$li_sueper=number_format($io_report->rs_data->fields["sueper"],2,",",".");
			$ld_fecingper=$io_funciones->uf_convertirfecmostrar($io_report->rs_data->fields["fecingper"]);
			$li_total=$io_report->rs_data->fields["total"];
			$io_cabecera=$io_pdf->openObject(); // Creamos el objeto cabecera
			$lb_valido=$io_report->uf_recibopago_conceptopersonal($ls_codper,$ls_conceptocero,$ls_conceptop2,
																  $ls_conceptoreporte,$ls_tituloconcepto,$ls_quincena); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->rs_data_detalle->RecordCount();
				$li_asig=0;
				$li_dedu=0;
				if($li_adelanto==1)// Utiliza el adelanto de quincena
				{
					switch($ls_quincena)
					{
						case "1": // primera quincena;
							$li_asig=$li_asig+1;
							$ls_codconc="----------";
							$ls_nomcon="ADELANTO 1ra QUINCENA";
							$li_valsal=round($li_total/2,2);
							$li_toting=$li_toting+$li_valsal;
							$li_valsal=$io_fun_nomina->uf_formatonumerico($li_valsal);
							$la_data_a[$li_asig]=array('denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
							break;
							
						case "2": // segunda quincena;
							while(!$io_report->rs_data_detalle->EOF)
							{
								$ls_tipsal=rtrim($io_report->DS_detalle->data["tipsal"][$li_s]);
								if(($ls_tipsal=="A") || ($ls_tipsal=="V1") || ($ls_tipsal=="V2") || ($ls_tipsal=="R")) // Buscamos las asignaciones
								{
									$li_asig=$li_asig+1;
									$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
									$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
									if ($ls_tipsal!="R")
									{
										$li_toting=$li_toting+abs($io_report->DS_detalle->data["valsal"][$li_s]);
									}
									$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data_detalle->fields["valsal"]));
									$la_data_a[$li_asig]=array('denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
								}
								else // Buscamos las deducciones y aportes
								{
									$li_dedu=$li_dedu+1;
									$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
									$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
									$li_totded=$li_totded+abs($io_report->rs_data_detalle->fields["valsal"]);
									$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data_detalle->fields["valsal"]));
									$la_data_d[$li_dedu]=array('denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
								}
								$io_report->rs_data_detalle->MoveNext();
							}
							$li_dedu=$li_dedu+1;
							$ls_codconc="----------";
							$ls_nomcon="ADELANTO 1ra QUINCENA";
							$li_valsal=round($li_total/2,2);
							$li_totded=$li_totded+$li_valsal;
							$li_valsal=$io_fun_nomina->uf_formatonumerico($li_valsal);
							$la_data_d[$li_dedu]=array('denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
							break;
							
						case "3": // Mes Completo;
							while(!$io_report->rs_data_detalle->EOF)
							{
								$ls_tipsal=rtrim($io_report->DS_detalle->data["tipsal"][$li_s]);
								if(($ls_tipsal=="A") || ($ls_tipsal=="V1") || ($ls_tipsal=="V2") || ($ls_tipsal=="R")) // Buscamos las asignaciones
								{
									$li_asig=$li_asig+1;
									$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
									$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
									if ($ls_tipsal!="R")
									{
										$li_toting=$li_toting+abs($io_report->rs_data_detalle->fields["valsal"]);
									}
									$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data_detalle->fields["valsal"]));
									$la_data_a[$li_asig]=array('denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
								}
								else // Buscamos las deducciones y aportes
								{
									$li_dedu=$li_dedu+1;
									$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
									$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
									$li_totded=$li_totded+abs($io_report->rs_data_detalle->fields["valsal"]);
									$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data_detalle->fields["valsal"]));
									$la_data_d[$li_dedu]=array('denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
								}
								$io_report->rs_data_detalle->MoveNext();
							}
							break;
					}
				}
				else// No utiliza adelanto de quincena
				{
					while(!$io_report->rs_data_detalle->EOF)
					{
						$ls_tipsal=rtrim($io_report->rs_data_detalle->fields["tipsal"]);
						if(($ls_tipsal=="A") || ($ls_tipsal=="V1") || ($ls_tipsal=="V2") || ($ls_tipsal=="R")) // Buscamos las asignaciones
						{
							$li_asig=$li_asig+1;
							$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
							$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
							if ($ls_tipsal!="R")
							{
								$li_toting=$li_toting+abs($io_report->rs_data_detalle->fields["valsal"]);
							}
							$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data_detalle->fields["valsal"]));
							$la_data_a[$li_asig]=array('denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
						}
						else // Buscamos las deducciones y aportes
						{
							$li_dedu=$li_dedu+1;
							$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
							$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
							$li_totded=$li_totded+abs($io_report->rs_data_detalle->fields["valsal"]);
							$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data_detalle->fields["valsal"]));
							$la_data_d[$li_dedu]=array('denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
						}
						$io_report->rs_data_detalle->MoveNext();
					}
				}
				if($li_asig<=$li_dedu)
				{
					$li_total=$li_dedu;
				}
				else
				{
					$li_total=$li_asig;
				}
				for($li_s=1;$li_s<=$li_total;$li_s++) 
				{
					$la_valores["denomasig"]="";
					$la_valores["valorasig"]="";
					$la_valores["denomdedu"]="";
					$la_valores["valordedu"]="";
					if($li_s<=$li_asig)
					{
						$la_valores["denomasig"]=$la_data_a[$li_s]["denominacion"];
						$la_valores["valorasig"]=$la_data_a[$li_s]["valor"];
					}
					if($li_s<=$li_dedu)
					{
						$la_valores["denomdedu"]=$la_data_d[$li_s]["denominacion"];
						$la_valores["valordedu"]=$la_data_d[$li_s]["valor"];
					}
					$la_data[$li_s]=$la_valores;
				}
				$li_totnet=$li_toting-$li_totded;
				$li_toting=$io_fun_nomina->uf_formatonumerico($li_toting);
				$li_totded=$io_fun_nomina->uf_formatonumerico($li_totded);
				$li_totnet=$io_fun_nomina->uf_formatonumerico($li_totnet);
				if($li_reg==1)
				{
					uf_print_cabecera1($ls_cedper,$ls_nomper,$ls_descar,$ls_desuniadm,$li_sueper,$ld_fecingper,$ls_nomban,$ls_codcueban,$io_cabecera,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					uf_print_pie_cabecera1($li_toting,$li_totded,$li_totnet,$io_pdf); // Imprimimos pie de la cabecera
				}
				else
				{
					uf_print_cabecera2($ls_cedper,$ls_nomper,$ls_descar,$ls_desuniadm,$li_sueper,$ld_fecingper,$ls_nomban,$ls_codcueban,$io_cabecera,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					uf_print_pie_cabecera2($li_toting,$li_totded,$li_totnet,$io_pdf); // Imprimimos pie de la cabecera
				}
				unset($la_data_a);
				unset($la_data_d);
				unset($la_data);
				$io_pdf->stopObject($io_cabecera); // Detener el objeto cabecera
				if(($li_i<$li_totrow)&&($li_reg==2))
				{
					$io_pdf->ezNewPage(); // Insertar una nueva página
					$li_reg=1;
				}
				else
				{
					$li_reg=2;
				}
			}
			$li_i++;
			$io_report->rs_data->MoveNext();
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
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 