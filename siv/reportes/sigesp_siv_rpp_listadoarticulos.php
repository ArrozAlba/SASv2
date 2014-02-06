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
	function uf_print_encabezado_pagina($as_titulo,$ad_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   ad_fecha  // Fecha 
		//	    		   io_pdf    // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(45,40,730,40);
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],40,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,550,11,"<b>".$as_titulo."</b>"); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$ad_fecha);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,720,11,$ad_fecha); // Agregar el título
		$io_pdf->addText(690,550,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(696,543,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codtipart,$as_dentipart,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codtipart    // codigo de tipo de articulo
		//	    		   as_dentipart    // denominacion del tipo de articulo
		//	    		   io_pdf       // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$as_nomfisalm=substr($as_nomfisalm,0,35);
		//$as_denpro=substr($as_denpro,0,25);
		$la_data=array(array('name'=>'<b>Tipo de Artículo</b>  '.$as_dentipart.''));
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
		$la_columna=array('codart'=>'<b>Codigo </b>',
						  'denart'=>'<b>Artículo</b>',
						  'dentipart'=>'<b>Tipo</b>',
						  'nomfisalm'=>'<b>Almacén</b>',
						  'codcatsig'=>'<b>SIGECOF</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codart'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna
						 			   'denart'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'dentipart'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la columna
						 			   'nomfisalm'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'codcatsig'=>array('justification'=>'left','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalleg
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
						  'canart'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>610), // Justificación y ancho de la columna
						 			   'canart'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_totales
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
	$ls_coddesde=$io_fun_inventario->uf_obtenervalor_get("coddesde","");
	$ls_codhasta=$io_fun_inventario->uf_obtenervalor_get("codhasta","");

	$ls_titulo="Listado de Artículos";
	if($ls_coddesde!="")
	{$ls_fecha="Rango ".$ls_coddesde." - ".$ls_codhasta;}
	else
	{$ls_fecha="";}
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
	$ls_codsigecof= $io_fun_inventario->uf_obtenervalor_get("codsigecof","");
	$ls_codalm=     $io_fun_inventario->uf_obtenervalor_get("codalm","");
	$ls_codtipart=  $io_fun_inventario->uf_obtenervalor_get("codtipart","");
	$li_orden=      $io_fun_inventario->uf_obtenervalor_get("orden","2");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_select_listadoarticulos($ls_codemp,$ls_coddesde,$ls_codhasta,$li_orden,$ls_codalm,$ls_codtipart,$ls_codsigecof); // Cargar el DS con los datos de la cabecera del reporte
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		/////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////
		$ls_desc_event="Generó el reporte Listado de Artículos Desde ".$ls_coddesde." hasta ".$ls_codhasta;
		$io_fun_inventario->uf_load_seguridad_reporte("SIV","sigesp_siv_r_listadoarticulos.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               ///////////////////////////////////////////////////
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(720,50,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->ds->getRowCount("codart");
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
		    $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$li_total=0;
			$ls_codart= $io_report->ds->data["codart"][$li_i];
			$ls_denart= $io_report->ds->data["denart"][$li_i];
			$ls_dentipart= $io_report->ds->data["dentipart"][$li_i];
			$ls_nomfisalm= $io_report->ds->data["nomfisalm"][$li_i];
			$ls_codcatsig= $io_report->ds->data["codcatsig"][$li_i];
			$la_data[$li_i]=array('codart'=>$ls_codart,'denart'=>$ls_denart,'dentipart'=>$ls_dentipart,'nomfisalm'=>$ls_nomfisalm,
								  'codcatsig'=>$ls_codcatsig);
		}
		if($li_totrow>0)
		{
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
			$li_totart=number_format($li_totrow,2,",",".");
			$la_datat[1]=array('total'=>'<b>Total de Artículos</b>','canart'=>$li_totart);
			uf_print_totales($la_datat,$io_pdf); // Imprimimos el detalle
		}
		else
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}	

		unset($la_data);			
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