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
		// Fecha Creación: 22/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_familiar.php",$ls_descripcion);
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
		// Fecha Creación: 22/09/2006 
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
	function uf_print_cabecera($as_codper,$as_apenomper,$ad_fecnacper,$ai_edad,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // cédula del personal
		//	    		   as_apenomper // apellidos y nombre del personal
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('personal'=>'<b>Personal</b> '.$as_codper.' - '.$as_apenomper,
							 'nacimiento'=>'<b>Fecha de Nac.</b> '.$ad_fecnacper.'','edad'=>'<b>Edad</b> '.$ai_edad.''));
		$la_columnas=array('personal'=>'','nacimiento'=>'','edad'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center',
						 'cols'=>array('personal'=>array('justification'=>'left','width'=>320), // Justificación y ancho de la columna
						 			   'nacimiento'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
						 			   'edad'=>array('justification'=>'rigth','width'=>60))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);	
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data  // Arreglo de Datos
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/08/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('cedula'=>'<b>Cédula</b>',
						   'apellido'=>'<b>                                    Apellidos y Nombre</b>',
						   'sexo'=>'<b>Género</b>',
						   'nexo'=>'<b>Nexo</b>',
						   'nacimiento'=>'<b>Fecha de Nacimiento</b>',
						   'edad'=>'<b>Edad</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('cedula'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'apellido'=>array('justification'=>'left','width'=>250), // Justificación y ancho de la columna
						 			   'sexo'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'nexo'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'nacimiento'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'edad'=>array('justification'=>'center','width'=>30))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$io_pdf->ezSetDy(-15);
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
	$ls_titulo="<b>Reporte de Familiares</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_conyuge=$io_fun_nomina->uf_obtenervalor_get("conyuge","");
	$ls_progenitor=$io_fun_nomina->uf_obtenervalor_get("progenitor","");
	$ls_hijo=$io_fun_nomina->uf_obtenervalor_get("hijo","");
	$ls_hermano=$io_fun_nomina->uf_obtenervalor_get("hermano","");
	$ls_masculino=$io_fun_nomina->uf_obtenervalor_get("masculino","");
	$ls_femenino=$io_fun_nomina->uf_obtenervalor_get("femenino","");
	$li_edaddesde=$io_fun_nomina->uf_obtenervalor_get("edaddesde","");
	$li_edadhasta=$io_fun_nomina->uf_obtenervalor_get("edadhasta","");
	$ls_activo=$io_fun_nomina->uf_obtenervalor_get("activo","");
	$ls_egresado=$io_fun_nomina->uf_obtenervalor_get("egresado","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_activono=$io_fun_nomina->uf_obtenervalor_get("activono","");
	$ls_vacacionesno=$io_fun_nomina->uf_obtenervalor_get("vacacionesno","");
	$ls_suspendidono=$io_fun_nomina->uf_obtenervalor_get("suspendidono","");
	$ls_egresadono=$io_fun_nomina->uf_obtenervalor_get("egresadono","");
	$ls_personalmasculino=$io_fun_nomina->uf_obtenervalor_get("personalmasculino","");
	$ls_personalfemenino=$io_fun_nomina->uf_obtenervalor_get("personalfemenino","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_familiar_personal($ls_codperdes,$ls_codperhas,$ls_conyuge,$ls_progenitor,$ls_hijo,
													$ls_hermano,$ls_masculino,$ls_femenino,$li_edaddesde,$li_edadhasta,
													$ls_codnomdes,$ls_codnomhas,$ls_activo,$ls_egresado,$ls_activono,
													$ls_vacacionesno,$ls_suspendidono,$ls_egresadono,$ls_personalmasculino,
													$ls_personalfemenino,$ls_orden); // Cargar el DS con los datos de la cabecera del reporte
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
			$io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$ls_codper=$io_report->DS->data["codper"][$li_i];
			$ls_apenomper=$io_report->DS->data["apeper"][$li_i].", ". $io_report->DS->data["nomper"][$li_i];
			$ld_fecnacper=$io_report->DS->data["fecnacper"][$li_i];
			$ld_hoy=date('Y');
			$ld_fecha=substr($ld_fecnacper,0,4);
			$li_edad=$ld_hoy-$ld_fecha;					
			if(intval(date('m'))<intval(substr($ld_fecnacper,5,2)))
			{
				$li_edad=$li_edad-1;
			}
			else
			{
				if(intval(date('m'))==intval(substr($ld_fecnacper,5,2)))
				{
					if(intval(date('d'))<intval(substr($ld_fecnacper,8,2)))
					{
						$li_edad=$li_edad-1;
					}
				}
			}
			$ld_fecnacper=$io_funciones->uf_convertirfecmostrar($ld_fecnacper);
			uf_print_cabecera($ls_codper,$ls_apenomper,$ld_fecnacper,$li_edad,$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_familiar_familiar($ls_codper,$ls_conyuge,$ls_progenitor,$ls_hijo,$ls_hermano,$ls_masculino,$ls_femenino,$li_edaddesde,$li_edadhasta); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_res=$io_report->DS_detalle->getRowCount("cedfam");
				for($li_s=1;$li_s<=$li_totrow_res;$li_s++)
				{	
					$ls_cedfam=$io_report->DS_detalle->data["cedfam"][$li_s];
					$ls_cedula=trim($io_report->DS_detalle->data["cedula"][$li_s]);
					if ($ls_cedula!='')
					{
						$ls_cedfam=$ls_cedula;
					}
					$ls_apenomfam=$io_report->DS_detalle->data["apefam"][$li_s].", ". $io_report->DS_detalle->data["nomfam"][$li_s];
					$ls_sexfam=$io_report->DS_detalle->data["sexfam"][$li_s];
					switch($ls_sexfam)
					{
						case "M":
							$ls_sexfam="Masculino";
							break;
						case "F":
							$ls_sexfam="Femenino";
							break;
					}
					$ls_nexfam=$io_report->DS_detalle->data["nexfam"][$li_s];
					switch($ls_nexfam)
					{
						case "C":
							$ls_nexfam="Conyuge";
							break;
						case "H":
							$ls_nexfam="Hijo";
							break;
						case "P":
							$ls_nexfam="Progenitor";
							break;
						case "E":
							$ls_nexfam="Hermano";
							break;
					}
					$ld_fecnacfam=$io_report->DS_detalle->data["fecnacfam"][$li_s];
					$ld_hoy=date('Y');
					$ld_fecha=substr($ld_fecnacfam,0,4);
					$li_edad=$ld_hoy-$ld_fecha;					
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
					$ld_fecnacfam=$io_funciones->uf_convertirfecmostrar($ld_fecnacfam);
					$la_data[$li_s]=array('cedula'=>$ls_cedfam,'apellido'=>$ls_apenomfam,'sexo'=>$ls_sexfam,
						  				  'nexo'=>$ls_nexfam,'nacimiento'=>$ld_fecnacfam,'edad'=>$li_edad);					
				}
				$io_report->DS_detalle->resetds("codvac");
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle  
			}
			if ($io_pdf->ezPageCount==$li_numpag)
			{// Hacemos el commit de los registros que se desean imprimir
				$io_pdf->transaction('commit');
			}
			else
			{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
				$io_pdf->transaction('rewind');
				$io_pdf->ezNewPage(); // Insertar una nueva página
				uf_print_cabecera($ls_codper,$ls_apenomper,$ld_fecnacper,$li_edad,$io_pdf); // Imprimimos la cabecera del registro
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle  
			}
			unset($la_data);
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