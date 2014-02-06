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
	ini_set('memory_limit','512M');
	ini_set('max_execution_time','0');

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: 
		// Fecha Creación: 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_srh;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("SHR","sigesp_srh_rpp_listadoevaluacioneficiencia.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_titulo2,$as_titulo3,$as_titulo4,$as_fecahdes,$as_fechahast,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		    Acess: private 
		//	    Arguments: 
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: 
		// Fecha Creación: 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(20,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],35,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=300-($li_tm/2);
		$io_pdf->addText($tm,750,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo2);
		$tm=300-($li_tm/2);
		$io_pdf->addText($tm,735,11,$as_titulo2); // Agregar el título		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo3);
		$tm=300-($li_tm/2);
		$io_pdf->addText($tm,720,11,$as_titulo3); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo4);
		$tm=300-($li_tm/2);
		$io_pdf->addText($tm,680,10,$as_titulo4); // Agregar el título			
		$io_pdf->addText(480,670,8,"FECHA: ".date("d/m/Y")); // Agregar la Fecha
		$tm=295-($li_tm/2);					
		$io_pdf->addText($tm,650,10,"Lapso Desde: ".$as_fecahdes); // Agregar la Fecha			
		$tm=420-($li_tm/2);					
		$io_pdf->addText($tm,650,10," Hasta: ".$as_fechahast); // Agregar la Fecha	
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 18/05/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$la_data_titulo[1]=array('nroeval'=>'<b>Nro. De Evaluación</b>',
		                               'fecha'=>'<b>Fecha de Registro de la Eval.</b>',
						               'codper'=>'<b>Código</b>',
						               'cedper'=>'<b>Cedula</b>',
						               'nombre'=>'<b>Nombre</b>',						   
						               'suma'=>'<b>Resulatdo de la Evaluación</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas						 						
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla						
						 'cols'=>array('nroeval'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'codper'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						 			   'cedper'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
									   'nombre'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna						 			   
						 			   'suma'=>array('justification'=>'center','width'=>50))); // Justificación y ancho de la columna

		$la_columnas=array('nroeval'=>'<b>Nro. De Evaluación</b>',
		                   'fecha'=>'<b>Fecha de Registro de la Eval.</b>',
						   'codper'=>'<b>Código</b>',
						   'cedper'=>'<b>Cedula</b>',
						   'nombre'=>'<b>Nombre</b>',						   
						   'suma'=>'<b>Resulatdo de la Evaluación</b>');
		$io_pdf->ezTable($la_data_titulo,$la_columnas,'',$la_config);
			
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas						
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla						
						 'cols'=>array('nroeval'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'codper'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						 			   'cedper'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
									   'nombre'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna						 			   
						 			   'suma'=>array('justification'=>'right','width'=>50))); // Justificación y ancho de la columna

		$la_columnas=array('nroeval'=>'',
		                   'fecha'=>'',
						   'codper'=>'',
						   'cedper'=>'',
						   'nombre'=>'',						   
						   'suma'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle

	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha = new class_fecha();	
	require_once("class_folder/sigesp_srh_class_report.php");
	$io_report  = new sigesp_srh_class_report();
	require_once("../class_folder/utilidades/class_funciones_srh.php");
	$io_fun_srh=new class_funciones_srh("../../");	
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];	
	$ls_nombre=$_SESSION["la_empresa"]["nombre"];	
	$ls_titulo1=$ls_nombre;
	$ls_titulo2="";
	$ls_titulo3="";
	$ls_titulo4="";
	
	//--------------variable que se toman de sigesp_srh_r_listado_evaluacioneficiencia.php------------------------------------------
	 $ls_fechades=$_GET["fechades"]; 
	 $ls_fechahas=$_GET["fechahas"];
	 $ls_codperdes=$_GET["codperdes"];
	 $ls_codperhas=$_GET["codperhas"];
	 $ls_orden=$_GET["ls_orden"];
	//------------------------------------------------------------------------------------------------------------------------------

	$lb_valido=uf_insert_seguridad("<b>Listado de Evaluación de Eficiencia</b>"); // Seguridad de Reporte
		
	if ($lb_valido)
	{
	 $lb_valido=$io_report->uf_lista_evaluacion_eficiencia($ls_fechades,$ls_fechahas,$ls_codperdes,$ls_codperhas,$ls_orden);	
	}		
	
	if($lb_valido==false) // Existe algún error ó no hay registros
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	 }
	 else
	 {
		error_reporting(E_ALL);
		//set_time_limit(1800);		
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(6,3.5,3.5,3.5); // Configuración de los margenes en centímetros
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		uf_print_encabezado_pagina($ls_titulo1,$ls_titulo2,$ls_titulo3,$ls_titulo4,$ls_fechades,$ls_fechahas,&$io_pdf);
		$li_total=$io_report->DS->getRowCount("codemp");
		//------------------------------------------------------------------------------------------------------------------
		for($li_i=1;$li_i<=$li_total;$li_i++)
		{	    
		    $io_pdf->transaction('start'); // Iniciamos la transacción
			$thisPageNum=$io_pdf->ezPageCount;
			$ls_nroeval=$io_report->DS->getValue("nroeval",$li_i);
			$ls_codper=$io_report->DS->getValue("codper",$li_i);			
			$ls_tipo=$io_report->DS->getValue("tipo",$li_i);			
			$ls_cedper=$io_report->DS->getValue("cedper",$li_i);
			$ls_cedper=number_format($ls_cedper,0,",",".");
			$ls_nombre=$io_report->DS->getValue("nombre",$li_i);
			$ls_fecha=$io_report->DS->getValue("fecha",$li_i);
			$ls_fecha=$io_funciones->uf_convertirfecmostrar($ls_fecha);
			$ls_suma=$io_report->DS->getValue("suma",$li_i);
			$ls_suma=number_format($ls_suma,2,",",".");
			
			if ($ls_tipo=='P')
			{
			 $la_data[$li_i]=array('nroeval'=>$ls_nroeval,'codper'=>$ls_codper,'cedper'=>$ls_cedper,'nombre'=>$ls_nombre,
			                       'fecha'=>$ls_fecha,'suma'=>$ls_suma);		
			}
			
	    }//fo
		//------------------------------------------------------------------------------------------------------------------			
	    if  ($li_total!=0) {
		uf_print_detalle($la_data,&$io_pdf);
		$io_pdf->transaction('commit');
		$io_pdf->ezStopPageNumbers(1,1);
		$io_pdf->ezStream();
		unset($io_pdf);
		}
	}
	unset($class_report);
	unset($io_funciones);
?> 