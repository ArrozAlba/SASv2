<?php
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "</script>";		
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_fecha // Fecha 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(20,40,578,40);
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->rectangle(150,710,400,40);
		$io_pdf->line(400,750,400,710);
		$io_pdf->line(400,730,550 ,730);
		$io_pdf->addJpegFromFile('../../shared/imagebank/logo.jpg',50,700,80); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=185;
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_fecha);
		$tm=230;
		$io_pdf->addText($tm,706,11,$as_fecha); // Agregar el título
		$io_pdf->addText(420,715,10,"Fecha: ".date("d/m/Y")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_nomemp,$as_nomfisalm,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_nomemp    // nombre de la empresa
		//	    		   as_nomfisalm // nombre fiscal de la empresa
		//	    		   io_pdf       // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>Empresa</b>  '.$as_nomemp.''),
					   array ('name'=>'<b>Almacén</b>  '.$as_nomfisalm.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('codigo'=>'<b>Código</b>',
						  'articulo'=>'<b>Denominación</b>',
						  'existencia'=>'<b>Existencia (Mayor)</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'articulo'=>array('justification'=>'left','width'=>260), // Justificación y ancho de la columna
						 			   'existencia'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ai_totprenom,$ai_totant,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera
		//		   Access: private 
		//	    Arguments: ai_totprenom // Total Prenómina
		//	   			   ai_totant // Total Anterior
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'_______________________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$la_data=array(array('total'=>''));
		$la_columna=array('total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>300), // Justificación y ancho de la columna
						 			   'prenomina'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'anterior'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_siv_class_report.php");
	$io_report=new sigesp_siv_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_funciones_inventario.php");
	$io_fun_inventario=new class_funciones_inventario();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_fecrec=$io_fun_inventario->uf_obtenervalor_get("fecrec","");

	$ls_titulo="<b> Niveles de Existencia de Artículos </b>";
	$ls_fecha="";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
	$ls_codart="";
	$ls_codarti=$io_fun_inventario->uf_obtenervalor_get("codart","");
	$ls_codalm=$io_fun_inventario->uf_obtenervalor_get("codalm","");
	$li_ordenalm=$io_fun_inventario->uf_obtenervalor_get("ordenalm",0);
	$li_ordenart=$io_fun_inventario->uf_obtenervalor_get("ordenart",0);
	//$li_ordenalm=0;
	//$li_ordenart=1;
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_select_almacen($ls_codemp,$ls_codalm,$ls_codart,$li_ordenalm); // Cargar el DS con los datos de la cabecera del reporte
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
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->ds->getRowCount("codalm");
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
		    $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$li_totprenom=0;
			$li_totant=0;
			$ls_codalm=$io_report->ds->data["codalm"][$li_i];
			$ls_nomfisalm=$io_report->ds->data["nomfisalm"][$li_i];
			uf_print_cabecera($ls_nomemp,$ls_nomfisalm,$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_select_articuloxalmacen($ls_codemp,$ls_codalm,$ls_codarti,$li_ordenalm,$li_ordenart); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->ds_detalle->getRowCount("codart");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_codart= $io_report->ds_detalle->data["codart"][$li_s];
					$ls_denart= $io_report->ds_detalle->data["denart"][$li_s];
					$li_existencia= $io_report->ds_detalle->data["existencia"][$li_s];
					$li_unidad=     $io_report->ds_detalle->data["unidades"][$li_s];
					$li_existencia= ($li_existencia/$li_unidad);
					$li_existencia=number_format($li_existencia,2,",",".");
					$la_data[$li_s]=array('codigo'=>$ls_codart,'articulo'=>$ls_denart,'existencia'=>$li_existencia);
				}
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				uf_print_pie_cabecera("","",$io_pdf); // Imprimimos pie de la cabecera
				if ($io_pdf->ezPageCount==$li_numpag)
				{// Hacemos el commit de los registros que se desean imprimir
					$io_pdf->transaction('commit');
				}
				else
				{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
					$io_pdf->transaction('rewind');
					$io_pdf->ezNewPage(); // Insertar una nueva página
					uf_print_cabecera($ls_nomemp,$ls_nomfisalm,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					uf_print_pie_cabecera("","",$io_pdf); // Imprimimos pie de la cabecera
				}
			}
			unset($la_data);			
		}
		if($lb_valido)
		{
			$io_pdf->ezStopPageNumbers(1,1);
			$io_pdf->ezStream();
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 