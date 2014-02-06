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
		// Fecha Creación: 23/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_listadoestudios.php",$ls_descripcion);
		if($lb_valido==false)
		{
			print("<script language=JavaScript>");
			print(" close();");
			print("</script>");
		}
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
		// Fecha Creación: 23/07/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,755,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(13,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,540,13,$as_titulo); // Agregar el título
		$io_pdf->addText(712,560,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(718,553,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_cedper,$as_nomper,$as_apeper,$as_estper,$as_dirper,$as_telhabper,$as_telmovper,$as_coreleper,
							   &$io_cabecera,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_cedper // Cédula del Personal
		//	    		   as_nomper // Nombre del Personal
		//	    		   as_apeper // Apellido del Personal
		//	    		   as_estper // Estatus del Personal
		//	    		   as_dirper // Dirección del Personal
		//	    		   as_telhabper // Teléfono de Habitación del Personal
		//	    		   as_telmovper // Teléfono Móvil del Personal
		//	    		   as_coreleper // Correo Electrónico del Personal
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los datos del Personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/07/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(40,505,705,$io_pdf->getFontHeight(16));
        $io_pdf->setColor(0,0,0);
		$io_pdf->addText(45,510,12,'<b>Datos del Personal</b>'); // Agregar el título
		$io_pdf->ezSetY(500);
		$la_data[1]=array('titulo1'=>'<b>Cédula</b>','cedula'=>$as_cedper,
						  'titulo2'=>'<b>Nombres</b>','nombre'=>$as_nomper,
						  'titulo3'=>'<b>Apellidos</b>','apellido'=>$as_apeper);
		$la_columna=array('titulo1'=>'','cedula'=>'','titulo2'=>'','nombre'=>'','titulo3'=>'','apellido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 11, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>690, // Ancho de la tabla
						 'maxWidth'=>690, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'rowGap'=>4,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo1'=>array('justification'=>'left','width'=>50), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
						 			   'titulo2'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>220), // Justificación y ancho de la columna
						 			   'titulo3'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'apellido'=>array('justification'=>'left','width'=>220))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('titulo1'=>'<b>Teléfono Hab.</b>','habitacion'=>$as_telhabper,
						  'titulo2'=>'<b>Teléfono Mov.</b>','movil'=>$as_telmovper,
						  'titulo3'=>'<b>Email</b>','email'=>$as_coreleper);
		$la_columna=array('titulo1'=>'','habitacion'=>'','titulo2'=>'','movil'=>'','titulo3'=>'','email'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 11, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>690, // Ancho de la tabla
						 'maxWidth'=>690, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'rowGap'=>4,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo1'=>array('justification'=>'left','width'=>90), // Justificación y ancho de la columna
						 			   'habitacion'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'titulo2'=>array('justification'=>'left','width'=>90), // Justificación y ancho de la columna
						 			   'movil'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'titulo3'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'email'=>array('justification'=>'left','width'=>170))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('titulo1'=>'<b>Dirección</b>','direccion'=>$as_dirper);
		$la_columna=array('titulo1'=>'','direccion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>690, // Ancho de la tabla
						 'maxWidth'=>690, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'rowGap'=>4,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo1'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'direccion'=>array('justification'=>'left','width'=>630))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_datos[1]=array('codestrea'=>'<b>Nro</b>','tipestrea'=>'<b>Tipo</b>','insestrea'=>'<b>Instituto</b>',
						   'desestrea'=>'<b>Descripción</b>','titestrea'=>'<b>Titulo Obtenido</b>','calestrea'=>'<b>Calf.</b>',
						   'feciniact'=>'<b>Fecha Inicio</b>','fecfinact'=>'<b>Fecha Fin</b>','fecgraestrea'=>'<b>Fecha Grado</b>');
		$la_columna=array('codestrea'=>'','tipestrea'=>'','insestrea'=>'','desestrea'=>'','titestrea'=>'','calestrea'=>'',
						  'feciniact'=>'','fecfinact'=>'', 'fecgraestrea'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 11, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>690, // Ancho de la tabla
						 'maxWidth'=>90, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codestrea'=>array('justification'=>'center','width'=>35),
						 			   'tipestrea'=>array('justification'=>'center','width'=>70),
									   'insestrea'=>array('justification'=>'center','width'=>130),
									   'desestrea'=>array('justification'=>'center','width'=>140),
									   'titestrea'=>array('justification'=>'center','width'=>105),
									   'calestrea'=>array('justification'=>'center','width'=>40),
									   'feciniact'=>array('justification'=>'center','width'=>60),
									   'fecfinact'=>array('justification'=>'center','width'=>60),
									   'fecgraestrea'=>array('justification'=>'center','width'=>60))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);	
		unset($la_datos);
		unset($la_columna);
		unset($la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_datos($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_datos
		//		   Access: private 
		//	    Arguments: la_data // Arreglo con loa datos
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los datos del Personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/07/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-1);
		$la_columna=array('codestrea'=>'','tipestrea'=>'','insestrea'=>'','desestrea'=>'','titestrea'=>'','calestrea'=>'',
						  'feciniact'=>'','fecfinact'=>'','fecgraestrea'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>690, // Ancho de la tabla
						 'maxWidth'=>90, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codestrea'=>array('justification'=>'center','width'=>35),
						 			   'tipestrea'=>array('justification'=>'center','width'=>70),
									   'insestrea'=>array('justification'=>'left','width'=>130),
									   'desestrea'=>array('justification'=>'left','width'=>140),
									   'titestrea'=>array('justification'=>'left','width'=>105),
									   'calestrea'=>array('justification'=>'center','width'=>40),
									   'feciniact'=>array('justification'=>'center','width'=>60),
									   'fecfinact'=>array('justification'=>'center','width'=>60),
									   'fecgraestrea'=>array('justification'=>'center','width'=>60))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_datos
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
	$ls_titulo="<b>Estudios Realizados por Personal</b>";
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
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_estudiospersonal_personal($ls_codnomdes,$ls_codnomhas,$ls_codperdes,$ls_codperhas,$ls_activo,
															$ls_egresado,$ls_causaegreso,$ls_activono,$ls_vacacionesno,
															$ls_suspendidono,$ls_egresadono,$ls_masculino,$ls_femenino,$ls_orden); // Obtenemos el detalle del reporte
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
		$io_pdf->ezSetCmMargins(7.15,2.2,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(750,50,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->DS->getRowCount("codper");
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_codper=$io_report->DS->data["codper"][$li_i];
			$ls_cedper=$io_report->DS->data["cedper"][$li_i];
			$ls_nomper=$io_report->DS->data["nomper"][$li_i];
			$ls_apeper=$io_report->DS->data["apeper"][$li_i];
			$ls_estper=$io_report->DS->data["estper"][$li_i];
			$ls_dirper=$io_report->DS->data["dirper"][$li_i];
			$ls_telhabper=$io_report->DS->data["telhabper"][$li_i];
			$ls_telmovper=$io_report->DS->data["telmovper"][$li_i];
			$ls_coreleper=$io_report->DS->data["coreleper"][$li_i];
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
			$io_cabecera=$io_pdf->openObject(); // Creamos el objeto cabecera
			uf_print_cabecera($ls_cedper,$ls_nomper,$ls_apeper,$ls_estper,$ls_dirper,$ls_telhabper,$ls_telmovper,$ls_coreleper,
							  &$io_cabecera,&$io_pdf);
			$lb_valido=$io_report->uf_estudiospersonal_estudios($ls_codper); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_total=$io_report->DS_detalle->getRowCount("codestrea");
				for($li_j=1;(($li_j<=$li_total)&&($lb_valido));$li_j++)
				{
					$li_codestrea=$io_report->DS_detalle->data["codestrea"][$li_j];
					$ls_tipestrea=$io_report->DS_detalle->data["tipestrea"][$li_j];
					$ls_insestrea=$io_report->DS_detalle->data["insestrea"][$li_j];
					$ls_desestrea=$io_report->DS_detalle->data["desestrea"][$li_j];
					$ls_titestrea=$io_report->DS_detalle->data["titestrea"][$li_j];
					$li_calestrea=number_format($io_report->DS_detalle->data["calestrea"][$li_j],2,",",".");
					$ld_fecgraestrea=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["fecgraestrea"][$li_j]);
					$ld_feciniact=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["feciniact"][$li_j]);
					$ld_fecfinact=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["fecfinact"][$li_j]);
					switch($ls_tipestrea)
					{
						case "0":
							$ls_tipestrea="Primaria";
							break;

						case "1":
							$ls_tipestrea="Ciclo Básico";
							break;						

						case "2":
							$ls_tipestrea="Ciclo Diversificado";
							break;

						case "3":
							$ls_tipestrea="Pregrado";
							break;						

						case "4":
							$ls_tipestrea="Especialización";
							break;

						case "5":
							$ls_tipestrea="Maestria";
							break;						

						case "6":
							$ls_tipestrea="Postgrado";
							break;

						case "7":
							$ls_tipestrea="Doctorado";
							break;						

						case "8":
							$ls_tipestrea="Taller";
							break;

						case "9":
							$ls_tipestrea="Curso";
							break;						
					}
					$la_data[$li_j]=array('codestrea'=>$li_codestrea,'tipestrea'=>$ls_tipestrea,'insestrea'=>$ls_insestrea,
										  'desestrea'=>$ls_desestrea,'titestrea'=>$ls_titestrea,'calestrea'=>$li_calestrea,
										  'feciniact'=>$ld_feciniact,'fecfinact'=>$ld_fecfinact,'fecgraestrea'=>$ld_fecgraestrea);
				}
				uf_print_datos($la_data,&$io_pdf);
				unset($la_data);
			}
			$io_pdf->stopObject($io_cabecera); // Detener el objeto cabecera
			if($li_i<$li_totrow)
			{
				$io_pdf->ezNewPage(); // Insertar una nueva página
			}
		}
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