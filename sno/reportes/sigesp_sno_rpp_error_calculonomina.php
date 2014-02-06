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
	ini_set('memory_limit','1024M');
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
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_p_calcularnomina.php",$ls_descripcion,$ls_codnom);		
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
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
		// Fecha Creación: 26/04/2006 
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
		$io_pdf->addText(512,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(518,743,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('codigo'=>'<b>Código</b>',
						   'nombre'=>'<b>Nombre Personal</b>',
						   'asignacion'=>'<b>Total Asignaciones</b>',
						   'deduccion'=>'<b>Deducción</b>',
						   'aporte'=>'<b>Aporte Patronal</b>',
						   'totalded'=>'<b>Total Deducciones</b>',
						   'neto'=>'<b>Diferencia   (Asig-Deduc)</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>110), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'aporte'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'totalded'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'neto'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($ai_totalasignacion,$ai_totaldeduccion,$ai_totalaporte,$ai_total_deduc,$ai_total_neto,$as_total_personas,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		   Access: private 
		//	    Arguments: ai_totalasignacion // Total Asignación
		//	   			   ai_totaldeduccion // Total Deduccción
		//	   			   ai_totalaporte // Total aporte
		//	   			   ai_total_neto // Total Neto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$la_data=array(array('totales'=>'<b>Totales '.number_format($as_total_personas,0,"",".").'</b>','asignacion'=>$ai_totalasignacion,'deduccion'=>$ai_totaldeduccion,
							 'aporte'=>$ai_totalaporte,'totalded'=>$ai_total_deduc,'neto'=>$ai_total_neto));
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('totales'=>array('justification'=>'right','width'=>170), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'aporte'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'totalded'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'neto'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,'','',$la_config);
		
	}// end function uf_print_piecabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_sno_class_report.php");
	$io_report=new sigesp_sno_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//-----------------------------------------------------  Parametros del Reporte  ------------------------------------------------
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	//----------------------------------------------------  Parámetros del encabezado  -------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$ls_titulo="<b>Reporte Errores Cálculo de Nómina</b>";
	$ls_periodo="<b>Período Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."</b>";	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_select_personal_deduccion_mayor_asignacion($ls_codperdes,$ls_codperhas);
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
		uf_print_encabezado_pagina($ls_titulo,$ls_desnom,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$io_pdf->FitWindow=true;				
		$ls_totalasignacion=0;
		$ls_totaldeduccion=0;
		$ls_totalaporte=0;
		$ls_total_diferencia=0;	
		$ls_total_deduccion_aporte=0;
		$ls_total_personas=0;	
		$li_s=0;
		while((!$io_report->rs_data->EOF)&&($lb_valido))
		{
			$ls_codper=$io_report->rs_data->fields["codper"];
			$ls_nomper=$io_report->rs_data->fields["nomper"]." ".$io_report->rs_data->fields["apeper"];
			$lb_valido=$io_report->uf_select_deduccion_mayor_asignacion ($ls_codper);
			while(!$io_report->rs_data_detalle->EOF)
			{
				$ls_totalasi=0;
				$ls_totalded=0;
				$ls_totalapor=0;
				$ls_totgenasi=0;
				$ls_totgended=0;
				$ls_totgenapo=0;
				$ls_difrencia=0; 
				
				$ls_totalasi=$ls_totalasi+$io_report->rs_data_detalle->fields["asignacion"];							  
				$ls_totalded=$ls_totalded+$io_report->rs_data_detalle->fields["deduccion"];							  
				$ls_totalapor=$ls_totalapor+$io_report->rs_data_detalle->fields["aporte"];
				
				$ls_totdeduc=$ls_totalded+$ls_totalapor;
				if (($ls_totalasi < $ls_totdeduc) || ($ls_totalasi==0))
				{
					$ls_totgenasi=$ls_totgenasi + $ls_totalasi;
					$ls_totgended=$ls_totgended + $ls_totalded;
					$ls_totgenapo=$ls_totgenapo + $ls_totalapor;	
					$ls_total_personas++;				
					$ls_difrencia=abs($ls_totalasi - $ls_totdeduc);
					
					$ls_totalasignacion=$ls_totalasignacion+$ls_totgenasi;
					$ls_totaldeduccion=$ls_totaldeduccion+$ls_totgended;
					$ls_totalaporte=$ls_totalaporte+$ls_totgenapo;
					$ls_total_deduccion_aporte=$ls_total_deduccion_aporte+$ls_totdeduc;
					$ls_total_diferencia=$ls_total_diferencia+$ls_difrencia;
					$li_s++;
					$la_data[$li_s]=array('codigo'=>$ls_codper,'nombre'=>$ls_nomper,'asignacion'=>number_format($ls_totalasi,2,",","."),'deduccion'=>number_format($ls_totalded,2,",","."),
										  'aporte'=>number_format($ls_totalapor,2,",","."),'totalded'=>number_format($ls_totdeduc,2,",","."),'neto'=>number_format($ls_difrencia,2,",","."));
				}
				$io_report->rs_data_detalle->MoveNext();
			}
			$io_report->rs_data->MoveNext();
		}
		if(!$lb_valido) // Si no ocurrio ningún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		else
		{
			if ($li_s==0)
			{
					print("<script language=JavaScript>");
					print(" alert('No hay Personal con Monto de Deducciones Mayor a las Asignaciones');"); 
					print(" close();");
					print("</script>");
			}
			else
			{
				uf_print_detalle($la_data,$io_pdf);
				$ls_totalasignacion=number_format($ls_totalasignacion,2,",",".");
				$ls_totaldeduccion=number_format($ls_totaldeduccion,2,",",".");
				$ls_totalaporte=number_format($ls_totalaporte,2,",",".");
				$ls_total_diferencia=number_format($ls_total_diferencia,2,",",".");
				$ls_total_deduccion_aporte=number_format($ls_total_deduccion_aporte,2,",",".");
				uf_print_piecabecera($ls_totalasignacion,$ls_totaldeduccion,$ls_totalaporte,$ls_total_deduccion_aporte,$ls_total_diferencia,$ls_total_personas,$io_pdf);
		
				$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
				$io_pdf->ezStream(); // Mostramos el reporte
			}
	}
}
	unset ($la_data);
	unset($io_pdf);
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 