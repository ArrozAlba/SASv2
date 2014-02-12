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
		// Fecha Creación: 03/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_prestacionantiguedad.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_desnom,$as_anocurper,$as_desmesperdes,$as_desmesperhas,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_anocurper // Año en curso
		//	    		   as_desmesper // Mes en curso
		//	    		   io_pdf // Instancia de objetso pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(trim($as_desmesperdes)==trim($as_desmesperhas))
		{
			$ls_mes=" Mes: ".$as_desmesperdes;
		}
		else
		{
			$ls_mes=" Meses: ".$as_desmesperdes." - ".$as_desmesperhas;
		}
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$ls_periodo="Año: ".$as_anocurper." ".$ls_mes;
		$li_tm=$io_pdf->getTextWidth(11,$ls_periodo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,720,11,$ls_periodo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_desnom);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,710,11,$as_desnom); // Agregar el título
		$io_pdf->addText(512,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(518,743,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
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
		// Fecha Creación: 06/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('periodo'=>'<b>N°</b>',
						  'cedula'=>'<b>Cédula</b>',
						  'nombre'=>'<b>Apellidos y Nombres</b>',	
						  'aporte'=>'<b>Monto a Depositar</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol' => array(1,1,0.8),
						 'shadeCol2' => array(1,1,1),
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						  'Titulo_Color'  => 'si', // Para poner color de fondo y de letra al titulo
						 'TituloCol' => array(0,0,0.5), //Color de fondo del titulo
						 'Letra_Titulo' => array(1,1,1),  //Color de letra del titulo
						 'cols'=>array('periodo'=>array('justification'=>'center','width'=>40), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>170), // Justificación y ancho de la columna
						 			   'aporte'=>array('justification'=>'center','width'=>60))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('total'=>'',						  
						  'aporte'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>260), // Justificación y ancho de la columna
						 			   'aporte'=>array('justification'=>'right','width'=>60))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf_plus/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>Listado de Prestación de Antiguedad</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_desnomdes=$io_fun_nomina->uf_obtenervalor_get("desnomdes","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	$ls_desnomhas=$io_fun_nomina->uf_obtenervalor_get("desnomhas","");
	$ls_anocurperdes=$io_fun_nomina->uf_obtenervalor_get("anocurperdes","");
	$ls_mescurperdes=$io_fun_nomina->uf_obtenervalor_get("mescurperdes","");
	$ls_desmesperdes=$io_fun_nomina->uf_obtenervalor_get("desmesperdes","");
	$ls_anocurperhas=$io_fun_nomina->uf_obtenervalor_get("anocurperhas","");
	$ls_mescurperhas=$io_fun_nomina->uf_obtenervalor_get("mescurperhas","");
	$ls_desmesperhas=$io_fun_nomina->uf_obtenervalor_get("desmesperhas","");
	$ls_sueint=$io_fun_nomina->uf_obtenervalor_get("sueint","");
	$ls_tiporeporte=0;
	if ($ls_codnomdes==$ls_codnomhas)
	{
		$ls_desnom=$ls_desnomdes;
	}
	else
	{
		$ls_desnom=$ls_desnomdes." - ".$ls_desnomhas;
	}
		//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_prestacionantiguedad_personal($ls_codnomdes,$ls_codnomhas,$ls_anocurperdes,$ls_mescurperdes,$ls_anocurperhas,$ls_mescurperhas); // Obtenemos el detalle del reporte
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
		$io_pdf->selectFont('../../shared/ezpdf_plus/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.9,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_desnom,$ls_anocurperdes,$ls_desmesperdes,$ls_desmesperhas,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_totalsueintper=0;
		$li_totalbonvacper=0;
		$li_totalbonfinper=0;
		$li_totalapoper=0;
		$li_totrow=$io_report->DS->getRowCount("cedper");
		//uf_print_cabecera($io_pdf,$ls_sueint);
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$li_numper=str_pad($li_i,2,"0",0);
			$ls_cedper=$io_report->DS->data["cedper"][$li_i];
			$ls_nomper=$io_report->DS->data["apeper"][$li_i].", ".$io_report->DS->data["nomper"][$li_i];
			$ls_periodo=str_pad($io_report->DS->data["mescurper"][$li_i],2,"0",0)." - ".$io_report->DS->data["anocurper"][$li_i];
			$li_totalsueintper=$li_totalsueintper+$io_report->DS->data["sueintper"][$li_i];
			$li_totalbonvacper=$li_totalbonvacper+$io_report->DS->data["bonvacper"][$li_i];
			$li_totalbonfinper=$li_totalbonfinper+$io_report->DS->data["bonfinper"][$li_i];
			$li_totalapoper=$li_totalapoper+$io_report->DS->data["apoper"][$li_i];
			$li_sueintper=$io_fun_nomina->uf_formatonumerico($io_report->DS->data["sueintper"][$li_i]);
			$li_bonvacper=$io_fun_nomina->uf_formatonumerico($io_report->DS->data["bonvacper"][$li_i]);
			$li_bonfinper=$io_fun_nomina->uf_formatonumerico($io_report->DS->data["bonfinper"][$li_i]);
			$li_apoper=$io_fun_nomina->uf_formatonumerico($io_report->DS->data["apoper"][$li_i]);
			$la_data[$li_i]=array('periodo'=>$li_i,'cedula'=>$ls_cedper,'nombre'=>$ls_nomper,'aporte'=>$li_apoper);
		}
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		unset($la_data);
		$li_totalsueintper=$io_fun_nomina->uf_formatonumerico($li_totalsueintper);
		$li_totalbonvacper=$io_fun_nomina->uf_formatonumerico($li_totalbonvacper);
		$li_totalbonfinper=$io_fun_nomina->uf_formatonumerico($li_totalbonfinper);
		$li_totalapoper=$io_fun_nomina->uf_formatonumerico($li_totalapoper);
		if($ls_tiporeporte==1)
		{
			$ls_titulobs="Bs.F.";
		}
		else
		{
			$ls_titulobs="Bs.";
		}
		$la_data[1]=array('total'=>'<b>Total '.$ls_titulobs.'</b>','sueldointegral'=>$li_totalsueintper,'bonovacacional'=>$li_totalbonvacper,
						  'bonofin'=>$li_totalbonfinper,'aporte'=>$li_totalapoper);
		uf_print_totales($la_data,$io_pdf); // Imprimimos el detalle 
		unset($la_data);
		$io_report->DS->resetds("cedper");
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