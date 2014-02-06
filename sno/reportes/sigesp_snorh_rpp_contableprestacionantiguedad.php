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
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/05/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo." Para la Nómina ".$as_desnom." Periodo".$as_periodo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_afectacionprestacionantiguedad.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
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
		// Fecha Creación: 12/05/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,720,11,$as_periodo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(10,$as_desnom);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,710,10,$as_desnom); // Agregar el título
		$io_pdf->addText(500,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(506,743,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_presupuesto(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_presupuesto
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera para el detalle presupuestario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>Afectación Presupuestaria</b>'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera_presupuesto
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_contable(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_contable
		//		   Access: private 
		//	    Arguments: io_pdf //Instancia de objeto pdf
		//    Description: función que imprime la cabecera para el detalle contable
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>Afectación Contable</b>'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera_contable
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_presupuesto($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_presupuesto
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle presupuestario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-3);
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		$ls_titulo="";
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$ls_titulo="Estructura Presupuestaria";
				break;
				
			case "2": // Modalidad por Presupuesto
				$ls_titulo="Estructura Programática  ";
				break;
		}
		$la_columna=array('programatica'=>'<b>'.$ls_titulo.'</b>',
						  'estadisticos'=>'<b>Estadístico</b>',
						  'denominacion'=>'<b>                             Descripción</b>',
						  'total'=>'<b>Total                 </b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('programatica'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'estadisticos'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle_presupuesto
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_contable($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_contable
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle contable
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-3);
		$la_columna=array('cuenta'=>'<b>Cuenta</b>',
						  'denominacion'=>'<b>                                Descripción</b>',
						  'debe'=>'<b>Debe               </b>',
						  'haber'=>'<b>Haber               </b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle_contable
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera_presupuesto($ai_total,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera_presupuesto
		//		   Access: private 
		//	    Arguments: ai_total // Total del presupuesto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera para el detalle presupuestario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$la_data=array(array('name'=>'<b>Totales '.$ls_bolivares.'</b>','total'=>$ai_total));
		$la_columna=array('name'=>'','total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('name'=>array('justification'=>'right','width'=>400), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera_presupuesto
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera_contable($ai_debe,$ai_haber,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera_contable
		//		   Access: private 
		//	    Arguments: ai_debe // Total por el Debe
		//	               ai_haber // Total por el Haber
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera para los detalles contables
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$la_data=array(array('name'=>'<b>Totales '.$ls_bolivares.'</b>','debe'=>$ai_debe,'haber'=>$ai_haber));
		$la_columna=array('name'=>'','debe'=>'','haber'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('name'=>array('justification'=>'right','width'=>300), // Justificación y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>100),
						 			   'haber'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera_contable
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	$ls_bolivares ="Bs.";
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_codnom=$io_fun_nomina->uf_obtenervalor_get("codnom","");
	$ls_desnom=$io_fun_nomina->uf_obtenervalor_get("desnom","");
	$ls_anocurper=$io_fun_nomina->uf_obtenervalor_get("anocurper","");
	$ls_mescurper=$io_fun_nomina->uf_obtenervalor_get("mescurper","");
	$ls_desmesper=$io_fun_nomina->uf_obtenervalor_get("desmesper","");
	$ls_titulo="<b>Resumen Contable Presupuestario de Prestacion Antiguedad</b>";
	$ls_periodo="<b>Período ".$ls_desmesper." - ".$ls_anocurper."</b>";
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo); // Seguridad de Reporte
	if($lb_valido) // Buscamos la información que afecta el presupuesto
	{
		$lb_valido=$io_report->uf_prestacionantiguedad_afectacionpresupuestaria($ls_codnom,$ls_anocurper,$ls_mescurper);
	}
	if($lb_valido) // Buscamos la información que afecta contabilidad por el debe
	{
		$lb_valido=$io_report->uf_prestacionantiguedad_afectacioncontable($ls_codnom,$ls_anocurper,$ls_mescurper);
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
		$io_pdf->ezSetCmMargins(3.3,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_desnom,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		
  	    //--------------------------------------------- Imprimir el detalle Presupuestario------------------------------------------------	
		$li_totrow=$io_report->DS->getRowCount("spg_cuenta");
		$li_totalpresupuesto=0;
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_codest1=$io_report->DS->data["codestpro1"][$li_i];
			$ls_codest2=$io_report->DS->data["codestpro2"][$li_i];
			$ls_codest3=$io_report->DS->data["codestpro3"][$li_i];
			$ls_codest4=$io_report->DS->data["codestpro4"][$li_i];
			$ls_codest5=$io_report->DS->data["codestpro5"][$li_i];
			$ls_programatica=$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5;
			$io_fun_nomina->uf_formato_estructura($ls_programatica,$ls_codest1,$ls_codest2,$ls_codest3,$ls_codest4,$ls_codest5);
			$ls_programatica=$ls_codest1.'-'.$ls_codest2.'-'.$ls_codest3;
			switch($ls_modalidad)
			{
				case "2": // Modalidad por Programa
					
					$ls_programatica=$ls_codest1.'-'.$ls_codest2.'-'.$ls_codest3.'-'.$ls_codest4.'-'.$ls_codest5;
					break;
			}
			$ls_cueprecon=$io_report->DS->data["spg_cuenta"][$li_i];
			$ls_denominacion=$io_report->DS->data["denominacion"][$li_i];
			$li_total=$io_report->DS->data["monto"][$li_i];
			$li_totalpresupuesto=$li_totalpresupuesto+$li_total;
			$li_total=$io_fun_nomina->uf_formatonumerico($li_total);
			$la_data[$li_i]=array('programatica'=>$ls_programatica,'estadisticos'=>$ls_cueprecon,
								  'denominacion'=>$ls_denominacion,'total'=>$li_total);
		}
		$io_report->DS->resetds("spg_cuenta");
		if($li_totrow>0)
		{
			uf_print_cabecera_presupuesto($io_pdf); // Imprimimos la cabecera de presupuesto
			uf_print_detalle_presupuesto($la_data,$io_pdf); // Imprimimos el detalle presupuestario
			$li_totalpresupuesto=$io_fun_nomina->uf_formatonumerico($li_totalpresupuesto);
			uf_print_pie_cabecera_presupuesto($li_totalpresupuesto,$io_pdf); // imprimimos los totales presupuestario
			unset($la_data);			
		}
		//-------------------------------------------------------------------------------------------------------------------------------	
		
		//--------------------------------------------- Imprimir el detalle Contable------------------------------------------------	
		$li_i=0;
		$li_totrow=$io_report->DS_detalle->getRowCount("sc_cuenta");
		$li_totalcontadebe=0;
		$li_totalcontahaber=0;
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_cueconpatcon=trim($io_report->DS_detalle->data["sc_cuenta"][$li_i]);
			$ls_denominacion=$io_report->DS_detalle->data["denominacion"][$li_i];
			$ls_operacion=$io_report->DS_detalle->data["debhab"][$li_i];
			if($ls_operacion=="D")
			{
				$li_debe=abs($io_report->DS_detalle->data["monto"][$li_i]);
				$li_haber=0;
				$li_totalcontadebe=$li_totalcontadebe+$li_debe;
				$li_totalcontahaber=$li_totalcontahaber+$li_haber;
				$li_debe=$io_fun_nomina->uf_formatonumerico($li_debe);
				$li_haber=$io_fun_nomina->uf_formatonumerico($li_haber);
				$la_data[$li_i]=array('cuenta'=>$ls_cueconpatcon,'denominacion'=>$ls_denominacion,'debe'=>$li_debe,'haber'=>$li_haber);
			}
		}
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_cueconpatcon=trim($io_report->DS_detalle->data["sc_cuenta"][$li_i]);
			$ls_denominacion=$io_report->DS_detalle->data["denominacion"][$li_i];
			$ls_operacion=$io_report->DS_detalle->data["debhab"][$li_i];
			if($ls_operacion=="H")
			{
				$li_debe=0;
				$li_haber=abs($io_report->DS_detalle->data["monto"][$li_i]);
				$li_totalcontadebe=$li_totalcontadebe+$li_debe;
				$li_totalcontahaber=$li_totalcontahaber+$li_haber;
				$li_debe=$io_fun_nomina->uf_formatonumerico($li_debe);
				$li_haber=$io_fun_nomina->uf_formatonumerico($li_haber);
				$la_data[$li_i]=array('cuenta'=>$ls_cueconpatcon,'denominacion'=>$ls_denominacion,'debe'=>$li_debe,'haber'=>$li_haber);
			}
		}
		$io_report->DS_detalle->resetds("sc_cuenta");
		if($li_totrow>0)
		{
			uf_print_cabecera_contable($io_pdf);// Imprimimos la cabecera contable
			uf_print_detalle_contable($la_data,$io_pdf); // Imprimimos el detalle contable
			$li_totalcontadebe=$io_fun_nomina->uf_formatonumerico($li_totalcontadebe);
			$li_totalcontahaber=$io_fun_nomina->uf_formatonumerico($li_totalcontahaber);
			uf_print_pie_cabecera_contable($li_totalcontadebe,$li_totalcontahaber,$io_pdf); // imprimimos los totales contable
			unset($la_data);
		}		
		//-------------------------------------------------------------------------------------------------------------------------------	
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