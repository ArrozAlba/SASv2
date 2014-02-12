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
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_antiguedadpersonal.php",$ls_descripcion);
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
		$io_pdf->ezSety(720);
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(42,706,518,$io_pdf->getFontHeight(10));
        $io_pdf->setColor(0,0,0);
		$la_data[1]=array('codigo'=>'<b>Código</b>',
						  'nombre'=>'<b>Apellidos y Nombres</b>',
						  'cargo'=>'<b>Cargo</b>',
						  'fecha'=>'<b>Fecha Ingreso</b>',
						  'ano'=>'<b>Año</b>',
						  'mes'=>'<b>Mes</b>',
						  'dia'=>'<b>Día</b>');
		$la_columna=array('codigo'=>'',
						  'nombre'=>'',
						  'cargo'=>'',
						  'fecha'=>'',
						  'ano'=>'',
						  'mes'=>'',
						  'dia'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xOrientation'=>'center',
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>160), // Justificación y ancho de la columna
						 			   'cargo'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'ano'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 			   'mes'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 			   'dia'=>array('justification'=>'center','width'=>25))); // Justificación y ancho de la columna
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
						  'cargo'=>'<b>Cargo</b>',
						  'fecha'=>'<b>Fecha de Ingreso</b>',
						  'ano'=>'<b>Año</b>',
						  'mes'=>'<b>Mes</b>',
						  'dia'=>'<b>Día</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xOrientation'=>'center',
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>160), // Justificación y ancho de la columna
						 			   'cargo'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'ano'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 			   'mes'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 			   'dia'=>array('justification'=>'center','width'=>25))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>Listado de Antiguedad del Personal</b>";
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
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_antiguedadpersonal_personal($ls_codnomdes,$ls_codnomhas,$ls_codperdes,$ls_codperhas,$ls_activo,
														      $ls_egresado,$ls_causaegreso,$ls_activono,$ls_vacacionesno,
														      $ls_suspendidono,$ls_egresadono,$ls_orden); // Obtenemos el detalle del reporte
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
		uf_print_cabecera($io_pdf);
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_codper=$io_report->DS->data["codper"][$li_i];
			$ls_nomper=$io_report->DS->data["apeper"][$li_i].", ".$io_report->DS->data["nomper"][$li_i];
			$ld_fecingper=$io_report->DS->data["fecingper"][$li_i];
			$ls_racnom=$io_report->DS->data["racnom"][$li_i];
			$ls_cargo="";
			switch($ls_racnom)
			{
				case "0": // Nó utiliza rac
					$ls_cargo=$io_report->DS->data["descar"][$li_i];
					break;
					
				case "1": // Utiliza rac
					$ls_cargo=$io_report->DS->data["denasicar"][$li_i];
					break;
			}
			$li_anoact=date('Y');
			$li_mesact=date('m');
			$li_diaact=date('d');
			$li_anoing=substr($ld_fecingper,0,4);
			$li_mesing=substr($ld_fecingper,5,2);
			$li_diaing=substr($ld_fecingper,8,2);
			$li_ano=0;
			$li_mes=0;
			$li_dia=0;
			$li_ano=$li_anoact-$li_anoing;
			if($li_mesact<$li_mesing)
			{
				$li_ano=$li_ano-1;
				$li_mes=(12-$li_mesing)+$li_mesact;
				if($li_diaact<$li_diaing)
				{
					$li_dia=(30-$li_diaing)+$li_diaact;
				}
				else
				{
					$li_dia=$li_diaact-$li_diaing;
				}
			}
			else
			{
				$li_mes=$li_mesact-$li_mesing;
				if($li_mesact==$li_mesing)
				{
					$li_mes=0;
					if($li_diaact<$li_diaing)
					{
						$li_dia=(30-$li_diaing)+$li_diaact;
						$li_mes=11;
						$li_ano=$li_ano-1;
					}
					else
					{
						$li_dia=$li_diaact-$li_diaing;
					}
				}
				else
				{
					if($li_diaact<$li_diaing)
					{
						$li_dia=(30-$li_diaing)+$li_diaact;
						$li_mes=$li_mes-1;
					}
					else
					{
						$li_dia=$li_diaact-$li_diaing;
					}
				}
			}
			$ld_fecingper=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecingper"][$li_i]);
			$la_data[$li_i]=array('codigo'=>$ls_codper,'nombre'=>$ls_nomper,'cargo'=>$ls_cargo,'fecha'=>$ld_fecingper,
								  'ano'=>$li_ano,'mes'=>$li_mes,'dia'=>$li_dia);
		}
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
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