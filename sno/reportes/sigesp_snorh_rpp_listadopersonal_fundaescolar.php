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

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_listadopersonal.php",$ls_descripcion);
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
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(30,40,970,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=504-($li_tm/2);
		$io_pdf->addText($tm,540,11,$as_titulo); // Agregar el título
		$io_pdf->addText(942,560,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(948,553,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera(&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/07/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSety(520);
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(30,496,940,$io_pdf->getFontHeight(19));
        $io_pdf->setColor(0,0,0);
		$la_data[1]=array('codigo'=>'<b>Código</b>',
						  'nombre'=>'<b>Apellidos y Nombres</b>',
						  'fecha'=>'<b>Fecha Ingreso</b>',
						  'estatus'=>'<b>Estatus</b>',
						  'nomina'=>'<b>Nómina</b>',
						  'fechano'=>'<b>Fecha Ing. Nómina</b>',
						  'estatusno'=>'<b>Estatus Nómina</b>',
						  'nivel'=>'<b>Nivel Académico</b>',
						  'profesion'=>'<b>Profesión</b>',
						  'ubicacion'=>'<b>Ubicación</b>',
						  'municipio'=>'<b>Municipio</b>',
						  'parroquia'=>'<b>Parroquia</b>');
		$la_columna=array('codigo'=>'',
						  'nombre'=>'',
						  'fecha'=>'',
						  'estatus'=>'',
						  'nomina'=>'',
						  'fechano'=>'',
						  'estatusno'=>'',
						  'nivel'=>'',
						  'profesion'=>'',
						  'ubicacion'=>'',
						  'municipio'=>'',
						  'parroquia'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos' => 505,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>160), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						 			   'estatus'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'nomina'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'fechano'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						 			   'estatusno'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'nivel'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'profesion'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'ubicacion'=>array('justification'=>'center','width'=>85), // Justificación y ancho de la columna
						 			   'municipio'=>array('justification'=>'center','width'=>85), // Justificación y ancho de la columna
						 			   'parroquia'=>array('justification'=>'center','width'=>85))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('codigo'=>'<b>Código</b>',
						  'nombre'=>'<b>Apellidos y Nombres</b>',
						  'fecha'=>'<b>Fecha Ingreso</b>',
						  'estatus'=>'<b>Estatus</b>',
						  'nomina'=>'<b>Nómina</b>',
						  'fechano'=>'<b>Fecha Ing. Nómina</b>',
						  'estatusno'=>'<b>Estatus Nómina</b>',
						  'nivel'=>'<b>Nivel Académico</b>',
						  'profesion'=>'<b>Profesión</b>',
						  'ubicacion'=>'<b>Ubicación</b>',
						  'municipio'=>'<b>Municipio</b>',
						  'parroquia'=>'<b>Parroquia</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos' => 505,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>160), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						 			   'estatus'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'nomina'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'fechano'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						 			   'estatusno'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'nivel'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'profesion'=>array('justification'=>'left','width'=>100), // Justificación y ancho de la columna
						 			   'ubicacion'=>array('justification'=>'left','width'=>85), // Justificación y ancho de la columna
						 			   'municipio'=>array('justification'=>'left','width'=>85), // Justificación y ancho de la columna
						 			   'parroquia'=>array('justification'=>'left','width'=>85))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piepagina($ai_total,&$io_pdf)
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
		
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>940); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$la_data=array(array('titulo'=>'<b>Total de Personas </b>','total'=>$ai_total));
		$la_columna=array('titulo'=>'','total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((224/255),(224/255),(224/255)), // Color de la sombra
						 'shadeCol2'=>array((224/255),(224/255),(224/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>860), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'left','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>Listado de Personal</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_activo=$io_fun_nomina->uf_obtenervalor_get("activo","");
	$ls_egresado=$io_fun_nomina->uf_obtenervalor_get("egresado","");
	$ls_causaegreso=$io_fun_nomina->uf_obtenervalor_get("causaegreso","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_activono=$io_fun_nomina->uf_obtenervalor_get("activono","");
	$ls_vacacionesno=$io_fun_nomina->uf_obtenervalor_get("vacacionesno","");
	$ls_suspendidono=$io_fun_nomina->uf_obtenervalor_get("suspendidono","");
	$ls_egresadono=$io_fun_nomina->uf_obtenervalor_get("egresadono","");
	$ls_masculino=$io_fun_nomina->uf_obtenervalor_get("masculino","");
	$ls_femenino=$io_fun_nomina->uf_obtenervalor_get("femenino","");
	$ls_codubifis=$io_fun_nomina->uf_obtenervalor_get("codubifis","");
	$ls_codpai=$io_fun_nomina->uf_obtenervalor_get("codpai","");
	$ls_codest=$io_fun_nomina->uf_obtenervalor_get("codest","");
	$ls_codmun=$io_fun_nomina->uf_obtenervalor_get("codmun","");
	$ls_codpar=$io_fun_nomina->uf_obtenervalor_get("codpar","");

	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_listadopersonal_personal($ls_codnomdes,$ls_codnomhas,$ls_codperdes,$ls_codperhas,$ls_activo,$ls_egresado,
														   $ls_causaegreso,$ls_activono,$ls_vacacionesno,$ls_suspendidono,$ls_egresadono,
														   $ls_masculino,$ls_femenino,$ls_codubifis,$ls_codpai,$ls_codest,$ls_codmun,
														   $ls_codpar,$ls_orden); // Obtenemos el detalle del reporte
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
		$io_pdf->ezSetCmMargins(4,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(960,50,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->DS->getRowCount("codper");
		uf_print_cabecera($io_pdf);
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_codper=$io_report->DS->data["codper"][$li_i];
			$ls_nomper=$io_report->DS->data["apeper"][$li_i].", ".$io_report->DS->data["nomper"][$li_i];
			$ld_fecingper=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecingper"][$li_i]);
			$ls_nivacaper=$io_report->DS->data["nivacaper"][$li_i];
			$ls_estper=$io_report->DS->data["estper"][$li_i];
			$ls_estnom=$io_report->DS->data["estnom"][$li_i];
			$ls_despro=$io_report->DS->data["despro"][$li_i];
			$ls_nomina=$io_report->DS->data["desnom"][$li_i];
			$ls_desubifis=$io_report->DS->data["desubifis"][$li_i];
			$ls_desmun=$io_report->DS->data["desmun"][$li_i];
			$ls_despar=$io_report->DS->data["despar"][$li_i];

			$ld_fechano=$io_report->DS->data["fecingnom"][$li_i];
			if($ld_fechano!="---")
			{
				$ld_fechano=$io_funciones->uf_convertirfecmostrar($ld_fechano);
			}
			switch ($ls_estper)
			{
				case "0":
					$ls_estper="Pre-Ingreso";
					break;
				case "1":
					$ls_estper="Activo";
					break;
				case "2":
					$ls_estper="N/A";
					break;
				case "3":
					$ls_estper="Egresado";
					break;
			}
			switch ($ls_estnom)
			{
				case "0":
					$ls_estnom="N/A";
					break;
				case "1":
					$ls_estnom="Activo";
					break;
				case "2":
					$ls_estnom="Vacaciones";
					break;
				case "3":
					$ls_estnom="Egresado";
					break;
				case "4":
					$ls_estnom="Suspendido";
					break;
			}
			switch ($ls_nivacaper)
			{
				case "0":
					$ls_nivacaper="Ninguno";
					break;
				case "1":
					$ls_nivacaper="Primaria";
					break;
				case "2":
					$ls_nivacaper="Bachiller";
					break;
				case "3":
					$ls_nivacaper="Técnico Superior";
					break;
				case "4":
					$ls_nivacaper="Universitario";
					break;
				case "5":
					$ls_nivacaper="Maestria";
					break;
				case "6":
					$ls_nivacaper="PostGrado";
					break;
				case "7":
					$ls_nivacaper="Doctorado";
					break;
			}
			$la_data[$li_i]=array('codigo'=>$ls_codper,'nombre'=>$ls_nomper,'fecha'=>$ld_fecingper,
								  'estatus'=>$ls_estper,'nomina'=>$ls_nomina,'fechano'=>$ld_fechano,
								  'estatusno'=>$ls_estnom,'nivel'=>$ls_nivacaper,'profesion'=>$ls_despro,
								  'ubicacion'=>$ls_desubifis,'municipio'=>$ls_desmun,'parroquia'=>$ls_despar);
		}
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		uf_print_piepagina($li_totrow,&$io_pdf);
		unset($la_data);			
		$io_report->DS->resetds("codper");
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