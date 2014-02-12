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
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/05/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;

		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_cuadreprestacionantiguedad.php",$ls_descripcion);
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
		// Fecha Creación: 24/05/2008 
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
	function uf_print_cabecera_presupuesto($as_descripcion,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_presupuesto
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera para el detalle presupuestario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/05/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>Afectación Presupuestaria - '.$as_descripcion.' </b>'));
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
	function uf_print_detalle_presupuesto($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_presupuesto
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle presupuestario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/05/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-3);
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
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
	function uf_print_pie_cabecera_presupuesto($ai_total,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera_presupuesto
		//		   Access: private 
		//	    Arguments: ai_total // Total del presupuesto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera para los detalles presupuestarios
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/05/2008 
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

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	$ls_bolivares ="Bs.";
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
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
	$ls_titulo="<b>Cuadre de Prestación Anitugedad</b>";
	$ls_periodo="<b>Período ".$ls_desmesper." - ".$ls_anocurper."</b>";
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo); // Seguridad de Reporte
	if($lb_valido) // Buscamos la información que afecta el presupuesto de los aportes
	{
		$lb_valido=$io_report->uf_cuadreprestacionantiguedad($ls_codnom,$ls_mescurper);
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
		$lb_print=false;
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		//--------------------------------------------- Imprimir el detalle Presupuestario ------------------------------------------------	
		$li_totrow=$io_report->DS->getRowCount("spg_cuenta");
		$li_totalpresupuesto=0;
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_programatica=$io_report->DS->data["programatica"][$li_i];
			$ls_codest1="";
			$ls_codest2="";
			$ls_codest3="";
			$ls_codest4="";
			$ls_codest5="";
			$io_fun_nomina->uf_formato_estructura($ls_programatica,$ls_codest1,$ls_codest2,$ls_codest3,$ls_codest4,$ls_codest5);
			$ls_programatica=$ls_codest1.'-'.$ls_codest2.'-'.$ls_codest3;
			switch($ls_modalidad)
			{
				case "2": // Modalidad por Programa
					
					$ls_programatica=$ls_codest1.'-'.$ls_codest2.'-'.$ls_codest3.'-'.$ls_codest4.'-'.$ls_codest5;
					break;
			}
			$ls_spg_cuenta=$io_report->DS->data["spg_cuenta"][$li_i];
			$ls_denominacion=$io_report->DS->data["denominacion"][$li_i];
			$li_total=abs($io_report->DS->data["total"][$li_i]);
			$li_totalpresupuesto=$li_totalpresupuesto+$li_total;
			$li_total=$io_fun_nomina->uf_formatonumerico($li_total);
			$la_data[$li_i]=array('programatica'=>$ls_programatica,'estadisticos'=>$ls_spg_cuenta,
								  'denominacion'=>$ls_denominacion,'total'=>$li_total);
		}
		$io_report->DS->resetds("spg_cuenta");
		if($li_totrow>0)
		{
			$lb_print=true;
			uf_print_cabecera_presupuesto("Aportes",$io_pdf); // Imprimimos la cabecera del Presupuesto
			uf_print_detalle_presupuesto($la_data,$io_pdf); // Imprimimos el detalle presupuestario
			$li_totalpresupuesto=$io_fun_nomina->uf_formatonumerico($li_totalpresupuesto);
			uf_print_pie_cabecera_presupuesto($li_totalpresupuesto,$io_pdf); // imprimimos los totales presupuestario
			unset($la_data);			
		}
		//-------------------------------------------------------------------------------------------------------------------------------	

		if($lb_valido) // Si no ocurrio ningún error
		{
			if($lb_print)
			{
				$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
				$io_pdf->ezStream(); // Mostramos el reporte
			}
			else
			{
				print("<script language=JavaScript>");
				print(" alert('La información con respecto a las cuentas presupuestarias está correcta.');"); 
				print(" close();");
				print("</script>");		
			}
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