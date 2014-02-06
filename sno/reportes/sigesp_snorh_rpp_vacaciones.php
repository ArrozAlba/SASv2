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

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/08/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_vacaciones.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/08/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$io_pdf->addText(512,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(518,743,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codper,$as_apenomper,$ad_fecingper,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // cédula del personal
		//	    		   as_apenomper // apellidos y nombre del personal
		//	    		   ad_fecingper // fecha de ingreso
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/08/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('personal'=>'<b>Personal</b> '.$as_codper.' - '.$as_apenomper,'fecha'=>'<b>Fecha Ingreso</b> '.$ad_fecingper));
		$la_columnas=array('personal'=>'','fecha'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('personal'=>array('justification'=>'left','width'=>390), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'left','width'=>110))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);	
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($ai_codvac,$ad_fecvenvac,$ad_fecdisvac,$ad_fecreivac,$ai_diavac,$ai_stavac,$ai_sueintvac,
							  $as_obsvac,$ai_dianorvac,$ai_diaadivac,$ai_diabonvac,$ai_diaadibon,$ai_diafer,$ai_sabdom,$as_sueint,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: ai_codvac  // código de la vacación
		//				   ad_fecvenvac // Fecha de Vencimiento 
		//				   ad_fecdisvac // Fecha de Disfrute
		//				   ad_fecreivac // Fecha de Reintegro
		//				   ai_diavac // Dias de Vacaciones
		//				   ai_stavac // Estatus 
		//				   ai_sueintvac // Sueldo integral
		//				   as_obsvac // Observación
		//				   ai_dianorvac // días Normales
		//				   ai_diaadivac // Días Adicinales
		//				   ai_diabonvac // Días de Bono 
		//				   ai_diaadibon // Días adicionales de bono
		//				   ai_diafer // días Feiados
		//				   ai_sabdom // Sábados y Domingos
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/08/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($as_sueint=="")
		{
			$ls_titulo="Sueldo Integral de Vacaciones Bs.";
		}
		else
		{
			$ls_titulo=$as_sueint." de Vacaciones Bs.";
		}
		$la_data[0]=array('vacacion'=>'<b>Vacación Número</b> '.$ai_codvac,
						   'estatus'=>'<b>Estatus</b> '.$ai_stavac);
		$la_columnas=array('vacacion'=>'<b>Número de Vacación</b>',
						   'estatus'=>'<b>Estatus</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array((224/255),(224/255),(224/255)), // Color de la sombra
						 'shadeCol'=>array(1,1,1), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('vacacion'=>array('justification'=>'left','width'=>400), // Justificación y ancho de la columna
						 			   'estatus'=>array('justification'=>'left','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		$la_data[1]=array('vencimiento'=>'<b>Fecha de Vencimiento</b> '.$ad_fecvenvac,
						  'disfrute'=>'<b>Fecha de Disfrute</b> '.$ad_fecdisvac,'reintegro'=>'<b>Fecha de Reintegro</b> '.$ad_fecreivac);
		$la_columnas=array('vencimiento'=>'<b>Fecha de Vencimiento</b>',
						   'disfrute'=>'<b>Fecha de Disfrute</b>',
						   'reintegro'=>'<b>Fecha de Reintegro</b>');
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
						 'cols'=>array('vencimiento'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'disfrute'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'reintegro'=>array('justification'=>'left','width'=>220))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		$la_data[0]=array('diasvac'=>'<b>Días de Vacación</b> '.$ai_diavac,
						  'diasadivac'=>'<b>Días Adicionales de Vacación</b> '.$ai_diaadivac,
						  'feriados'=>'<b>Días Feriados</b> '.$ai_diafer);
		$la_columnas=array('diasvac'=>'<b>Días de Vacación</b>',
						   'diasadivac'=>'<b>Días Adicionales de Vacación</b>',
						   'feriados'=>'<b>Días Feriados</b>');
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
						 'cols'=>array('diasvac'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'diasadivac'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'feriados'=>array('justification'=>'left','width'=>220))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		$la_data[0]=array('sabdom'=>'<b>Sabados y Domingos </b>'.$ai_sabdom,
						  'total'=>'<b>Total Días de Vacación </b>'.$ai_dianorvac,'sueldo'=>'<b>'.$ls_titulo.'</b> '.$ai_sueintvac);
		$la_columnas=array('sabdom'=>'',
						   'total'=>'',
						   'sueldo'=>'');
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
						 'cols'=>array('sabdom'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'sueldo'=>array('justification'=>'left','width'=>220))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		$la_data[0]=array('diasbono'=>'<b>Días de Bono Vacacional</b> '.$ai_diabonvac,'diasadibono'=>'<b>Días Adicionales de Bono Vacacional</b> '.$ai_diaadibon);
		$la_columnas=array('diasbono'=>'<b>Días de Bono Vacacional</b>',
						   'diasadibono'=>'<b>Días Adicionales de Bono Vacacional</b>');
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
						 'cols'=>array('diasbono'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'diasadibono'=>array('justification'=>'left','width'=>360))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		$la_data[0]=array('observacion'=>'<b>Observación </b>'.$as_obsvac);
		$la_columnas=array('observacion'=>'<b>Observación</b>');
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
						 'cols'=>array('observacion'=>array('justification'=>'left','width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
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
	$ls_titulo="<b>Reporte de Vacaciones</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_vencida=$io_fun_nomina->uf_obtenervalor_get("vencida","");
	$ls_programada=$io_fun_nomina->uf_obtenervalor_get("programada","");
	$ls_vacacion=$io_fun_nomina->uf_obtenervalor_get("vacacion","");
	$ls_disfrutada=$io_fun_nomina->uf_obtenervalor_get("disfrutada","");
	$ls_tiporeporte=$io_fun_nomina->uf_obtenervalor_get("tiporeporte",0);
	$ls_sueint=$io_fun_nomina->uf_obtenervalor_get("sueint",0);
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_snorh_class_reportbsf.php");
		$io_report=new sigesp_snorh_class_reportbsf();
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_vacaciones_personal($ls_codperdes,$ls_codperhas,$ls_vencida,$ls_programada,$ls_vacacion,
													  $ls_disfrutada,$ls_orden); // Cargar el DS con los datos de la cabecera del reporte
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
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->DS->getRowCount("codper");
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_codper=$io_report->DS->data["codper"][$li_i];
			$ls_apenomper=$io_report->DS->data["apeper"][$li_i].", ". $io_report->DS->data["nomper"][$li_i];
			$ld_fecingper=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecingper"][$li_i]);
			uf_print_cabecera($ls_codper,$ls_apenomper,$ld_fecingper,$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_vacaciones_vacacion($ls_codper,$ls_vencida,$ls_programada,$ls_vacacion,$ls_disfrutada,$ls_orden); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_res=$io_report->DS_detalle->getRowCount("codvac");
				for($li_s=1;$li_s<=$li_totrow_res;$li_s++)
				{	
					$io_pdf->transaction('start'); // Iniciamos la transacción
					$li_numpag=$io_pdf->ezPageCount; // Número de página
					if($li_s==1)
					{
						$io_pdf->ezSetDy(-2);
					}
					else
					{
						$io_pdf->ezSetDy(-12);
					}
					$li_codvac=$io_report->DS_detalle->data["codvac"][$li_s];
					$ld_fecvenvac=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["fecvenvac"][$li_s]);
					$ld_fecdisvac=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["fecdisvac"][$li_s]);
					$ld_fecreivac=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["fecreivac"][$li_s]);
					$li_diavac=$io_report->DS_detalle->data["diavac"][$li_s];
					$li_stavac=$io_report->DS_detalle->data["stavac"][$li_s];
					$li_sueintvac=$io_fun_nomina->uf_formatonumerico($io_report->DS_detalle->data["sueintvac"][$li_s]);
					$ls_obsvac=$io_report->DS_detalle->data["obsvac"][$li_s];
					$li_dianorvac=$io_report->DS_detalle->data["dianorvac"][$li_s];
					$li_diaadivac=$io_report->DS_detalle->data["diaadivac"][$li_s];
					$li_diabonvac=$io_report->DS_detalle->data["diabonvac"][$li_s];
					$li_diaadibon=$io_report->DS_detalle->data["diaadibon"][$li_s];
					$li_diafer=$io_report->DS_detalle->data["diafer"][$li_s];
					$li_sabdom=$io_report->DS_detalle->data["sabdom"][$li_s];
					switch($li_stavac)
					{
						case "1":
							$li_stavac="Vencida";
							break;
						case "2":
							$li_stavac="Programada";
							break;
						case "3":
							$li_stavac="En Vacación";
							break;
						case "4":
							$li_stavac="Disfrutadas";
							break;
					}
  			   		uf_print_detalle($li_codvac,$ld_fecvenvac,$ld_fecdisvac,$ld_fecreivac,$li_diavac,$li_stavac,$li_sueintvac,
									 $ls_obsvac,$li_dianorvac,$li_diaadivac,$li_diabonvac,$li_diaadibon,$li_diafer,$li_sabdom,
									 $ls_sueint,$io_pdf); // Imprimimos el detalle  
					if ($io_pdf->ezPageCount==$li_numpag)
					{// Hacemos el commit de los registros que se desean imprimir
						$io_pdf->transaction('commit');
					}
					else
					{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
						$io_pdf->transaction('rewind');
						$io_pdf->ezNewPage(); // Insertar una nueva página
						uf_print_cabecera($ls_codper,$ls_apenomper,$ld_fecingper,$io_pdf); // Imprimimos la cabecera del registro
						uf_print_detalle($li_codvac,$ld_fecvenvac,$ld_fecdisvac,$ld_fecreivac,$li_diavac,$li_stavac,$li_sueintvac,
										 $ls_obsvac,$li_dianorvac,$li_diaadivac,$li_diabonvac,$li_diaadibon,$li_diafer,$li_sabdom, 
										 $ls_sueint,$io_pdf); // Imprimimos el detalle 
					}
				}
				$io_report->DS_detalle->resetds("codvac");
			}
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