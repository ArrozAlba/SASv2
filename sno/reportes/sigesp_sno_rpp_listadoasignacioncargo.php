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

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo,$ai_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_listadoasignacioncargo.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_hlistadoasignacioncargo.php",$ls_descripcion,$ls_codnom);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_desnom,$as_periodo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,750,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,500,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=390-($li_tm/2);
		$io_pdf->addText($tm,540,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=390-($li_tm/2);
		$io_pdf->addText($tm,525,11,$as_periodo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(10,$as_desnom);
		$tm=390-($li_tm/2);
		$io_pdf->addText($tm,510,10,$as_desnom); // Agregar el título
		$io_pdf->addText(712,540,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(718,530,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	function uf_print_cabecera_2(&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_2
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/07/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSety(500);
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(45,484,709,$io_pdf->getFontHeight(12));
        $io_pdf->setColor(0,0,0);
		$la_data[1]=array('codigo'=>'<b>Código</b>',
						  'unidad'=>'<b>Unidad Adm.</b>',
						  'cargo'=>'<b>Denominación del Cargo</b>',						  
						  'grado'=>'<b>Grado</b>',						  
						  'vacantes'=>'<b>Vacantes</b>',
						  'ocupado'=>'<b>Ocupados</b>',
						  'disponibles'=>'<b>Disponibles</b>');
		$la_columna=array('codigo'=>'',
						  'unidad'=>'',
						  'cargo'=>'',						  
						  'grado'=>'',
						  'vacantes'=>'',
						  'ocupado'=>'',
						  'disponibles'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos' => 405,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'unidad'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'cargo'=>array('justification'=>'center','width'=>210), // Justificación y ancho de la columna
						 			   'grado'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'vacantes'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'ocupado'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'disponibles'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabecera_
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
		$io_pdf->ezSety(500);
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(37,484,725,$io_pdf->getFontHeight(12));
        $io_pdf->setColor(0,0,0);
		$la_data[1]=array('codigo'=>'<b>Código</b>',
						  'unidad'=>'<b>Unidad Adm.</b>',
						  'cargo'=>'<b>Denominación del Cargo</b>',
						  'tabulador'=>'<b>Tabulador</b>',
						  'grado'=>'<b>Grado</b>',
						  'paso'=>'<b>Paso</b>',
						  'vacantes'=>'<b>Vacantes</b>',
						  'ocupado'=>'<b>Ocupados</b>',
						  'disponibles'=>'<b>Disponibles</b>');
		$la_columna=array('codigo'=>'',
						  'unidad'=>'',
						  'cargo'=>'',
						  'tabulador'=>'',
						  'grado'=>'',
						  'paso'=>'',
						  'vacantes'=>'',
						  'ocupado'=>'',
						  'disponibles'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos' => 405,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'unidad'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'cargo'=>array('justification'=>'center','width'=>190), // Justificación y ancho de la columna
						 			   'tabulador'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
						 			   'grado'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'paso'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'vacantes'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'ocupado'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'disponibles'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabecera
	
	//-----------------------------------------------------------------------------------------------------------------------------------
function uf_print_detalle_2($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_2
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		//$io_pdf->ezSetDy(-2);
		$la_columna=array('codasi'=>'',
						  'unidad'=>'',
						  'denasi'=>'',						  
						  'grado'=>'',
						  'vacantes'=>'',
						  'ocupado'=>'',
						  'disponible'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos' => 405,
						 'cols'=>array('codasi'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'unidad'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'denasi'=>array('justification'=>'center','width'=>210), // Justificación y ancho de la columna
						 			  
						 			   'grado'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   
						 			   'vacantes'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'ocupado'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'disponible'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle_2
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		//$io_pdf->ezSetDy(-2);
		$la_columna=array('codasi'=>'',
						  'unidad'=>'',
						  'denasi'=>'',
						  'tabulador'=>'',
						  'grado'=>'',
						  'paso'=>'',
						  'vacantes'=>'',
						  'ocupado'=>'',
						  'disponible'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos' => 405,
						 'cols'=>array('codasi'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'unidad'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'denasi'=>array('justification'=>'center','width'=>190), // Justificación y ancho de la columna
						 			   'tabulador'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
						 			   'grado'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'paso'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'vacantes'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'ocupado'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'disponible'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

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
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];		
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$li_rac=$_SESSION["la_nomina"]["racnom"];
	$ls_tipnom=$_SESSION["la_nomina"]["tipnom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$ls_titulo="<b>Listado de Asignación de Cargo</b>";
	$ls_periodo="<b>Período Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codasides=$_GET["codasignades"]; 
	$ls_codasihas=$_GET["codasignahas"];	
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_listado_asignaciocargo($ls_codasides,$ls_codasihas,$ls_orden);
	}
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else  // Imprimimos el reporte
	{
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(4,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_desnom,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(750,50,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->DS_asigna->getRowCount("codasicar");
		if (($li_rac==1) && (($ls_tipnom==3)||($ls_tipnom==4)))
		{
			uf_print_cabecera_2(&$io_pdf);
		}
		else
		{
			uf_print_cabecera(&$io_pdf);
			
		}
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_codasi=$io_report->DS_asigna->data["codasicar"][$li_i];
			$ls_cod1=$io_report->DS_asigna->data["minorguniadm"][$li_i];
			$ls_cod2=$io_report->DS_asigna->data["ofiuniadm"][$li_i];
			$ls_cod3=$io_report->DS_asigna->data["uniuniadm"][$li_i];
			$ls_cod4=$io_report->DS_asigna->data["depuniadm"][$li_i];
			$ls_cod5=$io_report->DS_asigna->data["prouniadm"][$li_i];
			$ls_coduni=$ls_cod1.$ls_cod2.$ls_cod3.$ls_cod4.$ls_cod5;
			$ls_denuni= $io_report->DS_asigna->data["desuniadm"][$li_i];
			$ls_unidad=$ls_coduni." ".$ls_denuni;
			$ls_denasicar= $io_report->DS_asigna->data["denasicar"][$li_i];
			$ls_tabulador= $io_report->DS_asigna->data["codtab"][$li_i];
			$ls_grado= $io_report->DS_asigna->data["codgra"][$li_i];
			$ls_grado_obrero= $io_report->DS_asigna->data["grado"][$li_i];
			$ls_paso= $io_report->DS_asigna->data["codpas"][$li_i];
			$ls_vacantes= $io_report->DS_asigna->data["numvacasicar"][$li_i];
			$ls_ocupados= number_format($io_report->DS_asigna->data["ocupado"][$li_i],0,".",""); ////OJO----ver esto mejor						
			$ls_disponibles= $ls_vacantes-($ls_ocupados);
			if ($ls_disponibles<0)
			{
				$ls_disponibles=0;
			}
			
			if (($ls_grado_obrero=="")||($ls_grado_obrero=='0000'))
			{
				$la_data[$li_i]=array('codasi'=>$ls_codasi,'unidad'=>$ls_coduni,'denasi'=>$ls_denasicar,
			                          'tabulador'=>$ls_tabulador,'grado'=>$ls_grado,'paso'=>$ls_paso,
								       'vacantes'=>$ls_vacantes,'ocupado'=>$ls_ocupados,'disponible'=>$ls_disponibles);
					     
							
			}
			else
			{
				$la_data[$li_i]=array('codasi'=>$ls_codasi,'unidad'=>$ls_coduni,'denasi'=>$ls_denasicar,
			                          'grado'=>$ls_grado_obrero,'vacantes'=>$ls_vacantes,'ocupado'=>$ls_ocupados,
								      'disponible'=>$ls_disponibles);	
				
			}			
		}
		
	
		
		if (($li_rac==1) && (($ls_tipnom==3)||($ls_tipnom==4)))
		{
			
			uf_print_detalle_2($la_data,&$io_pdf);
		}
		else
		{
			
			uf_print_detalle($la_data,&$io_pdf);
			
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