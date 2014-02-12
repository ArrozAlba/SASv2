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
	function uf_insert_seguridad($as_titulo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 13/06/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_personal_beneficiario.php",$ls_descripcion);
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
		// Fecha Creación: 14/08/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(27,40,585,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],40,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$io_pdf->addText(548,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(552,740,7,date("h:i a")); // Agregar la Hora
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
		// Fecha Creación: 14/08/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSety(710);  
		$io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(22,680,570,$io_pdf->getFontHeight(24));
        $io_pdf->setColor(0,0,0);     
		$la_data[1]=array('cedben'=>'<b>Cédula del Beneficiario o Afiliado</b>',
						  'nomben'=>'<b>Apellidos y Nombres del Beneficario o Afiliado</b>',
						  'nexben'=>'<b>Parentesco</b>',
		                  'cedper'=>'<b>Cédula del Pensionado</b>',
						  'nomper'=>'<b>Apellidos y Nombres del Pensionado</b>',						  
						  'monto'=>'<b>Monto Neto</b>');
		$la_columna=array('cedben'=>'',
						  'nomben'=>'',
						  'nexben'=>'',
		                  'cedper'=>'',
						  'nomper'=>'',						  
						  'monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas												 
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos' => 312,
						 'cols'=>array('cedben'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'nomben'=>array('justification'=>'center','width'=>170), // Justificación y ancho de la columna
						 			   'nexben'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						               'cedper'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'nomper'=>array('justification'=>'center','width'=>170), // Justificación y ancho de la columna
						 			   
						 			   'monto'=>array('justification'=>'center','width'=>65))); // Justificación y ancho de la columna
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
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 13/06/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6.3, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas												 
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos' => 312,
						 'cols'=>array('cedben'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'nomben'=>array('justification'=>'left','width'=>170), // Justificación y ancho de la columna
						 			   'nexben'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						               'cedper'=>array('justification'=>'right','width'=>50), // Justificación y ancho de la columna
						 			   'nomper'=>array('justification'=>'left','width'=>170), // Justificación y ancho de la columna
						 			   
						 			   'monto'=>array('justification'=>'right','width'=>65))); // Justificación y ancho de la columna

		$io_pdf->ezTable($la_data,'','',$la_config);
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
	$ls_titulo="<b>Listado de Beneficiarios</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_cedbenedes=$io_fun_nomina->uf_obtenervalor_get("cedbenedes",""); 
	$ls_cedbenehas=$io_fun_nomina->uf_obtenervalor_get("cedbenehas","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");	
	$rs_data="";
//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_beneficiario_personal($ls_codperdes, $ls_codperhas, 
		                                                $ls_cedbenedes, $ls_cedbenehas, $ls_orden,$rs_data); // Obtenemos el detalle del reporte
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
		$io_pdf->ezSetCmMargins(3.9,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(580,50,7,'','',1); // Insertar el número de página	
		$li_totrow=$io_report->DS->getRowCount("codper");
		uf_print_cabecera($io_pdf);
		$li_numrowtot=$io_report->io_sql->num_rows($rs_data);
		$i=0;
		if ($li_numrowtot>0)
		{
			while ($row=$io_report->io_sql->fetch_row($rs_data))
			{    $i++;
			 	 $ls_cedper=$row["cedper"];
				 $ls_cedper=number_format($ls_cedper,0,",",".");
				 $ls_nomper=$row["apeper"].", ".$row["nomper"];
				 $ls_cedbene=$row["cedben"];
				 $ls_cedbene=number_format($ls_cedbene,0,",",".");
				 $ls_nombene=$row["apeben"].", ".$row["nomben"];
				 $ls_nexben=$row["nexben"];
				 switch ($ls_nexben)
				 {
				 	case "-":
						$ls_parentesco="Sin parentesco";
					break;
					case "C":
						$ls_parentesco="Conyuge";
					break;
					case "H":
						$ls_parentesco="Hijo";
					break;
					case "P":
						$ls_parentesco="Progenitor";
					break;
					case "E":
						$ls_parentesco="Hermano";
					break;
				 }
				 $ls_monto=$row["monpagben"];
				 $ls_data[$i]=array('cedben'=>$ls_cedbene,
				                    'nomben'=>$ls_nombene,'nexben'=>$ls_parentesco,
									'cedper'=>$ls_cedper,'nomper'=>$ls_nomper,
									'monto'=>$io_fun_nomina->uf_formatonumerico($ls_monto));
			}//fin del while	
			uf_print_detalle($ls_data,&$io_pdf);
			unset($ls_data);
	    }
		$io_report->io_sql->free_result($rs_data);
		if (($lb_valido)&&($li_numrowtot>0)) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");		
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 