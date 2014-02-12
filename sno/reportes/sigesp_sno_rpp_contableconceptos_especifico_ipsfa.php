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
	function uf_insert_seguridad($as_titulo,$as_titulo2,$as_desnom,$as_periodo,$ai_tipo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Generó el Reporte ".$as_titulo." ".$as_titulo2.". Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_contableconceptos.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_hcontableconceptos.php",$ls_descripcion,$ls_codnom);
		}
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_titulo2,$as_titulo3,$as_titulo4,$as_desnom,$as_periodo,$as_denestpro1,$as_denestpro2,$as_denestpro3,$as_denestpro4,$as_denestpro5,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_titulo2 // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo2);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,720,11,$as_titulo2); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo3);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,710,11,$as_titulo3); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo4);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,700,11,$as_titulo4); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(11,$as_denestpro1);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,690,11,"<b>".$as_denestpro1."</b>"); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(11,$as_denestpro2);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,680,11,"<b>".$as_denestpro2."</b>"); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(11,$as_denestpro3);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,670,11,"<b>".$as_denestpro3."</b>"); // Agregar el título
		
		
		
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,660,11,$as_periodo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(10,$as_desnom);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,650,10,$as_desnom); // Agregar el título
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
		$io_pdf->ezSetY(640);
		$la_data=array(array('name'=>'<b>Afectación Presupuestaria</b>'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550); // Ancho Máximo de la tabla
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
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle presupuestario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-3);
		$la_columna=array('partida'=>'<b>Partida</b>',
						  'denominacion'=>'<b>                              Denominación</b>',
						  'personal'=>'<b>                 Tipo de Personal</b>',
						  'cantidad'=>'<b>Cantidad</b>',
						  'total'=>'<b>Total            </b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('partida'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'personal'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la columna
						 			   'cantidad'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle_presupuesto
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera_presupuesto($ai_total,$ai_totalpersonal,&$io_pdf)
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
		
		$la_data=array(array('name'=>'<b>Total </b>','total'=>$ai_total));
		$la_columna=array('name'=>'','total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('name'=>array('justification'=>'right','width'=>470), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera_presupuesto
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	$li_tipo=0;
	$ls_bolivares="";
	if($_SESSION["la_nomina"]["tiponomina"]=="NORMAL")
	{
		require_once("sigesp_sno_class_report_contables.php");
		$io_report=new sigesp_sno_class_report_contables();
		$li_tipo=1;
	}
	if($_SESSION["la_nomina"]["tiponomina"]=="HISTORICA")
	{
		require_once("sigesp_sno_class_report_historico_contables.php");
		$io_report=new sigesp_sno_class_report_historico_contables();
		$li_tipo=2;
	}	
	$ls_bolivares ="Bs.";
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ls_conpronom=$_SESSION["la_nomina"]["conpronom"];
	$ls_codestpro1=str_pad(trim($io_fun_nomina->uf_obtenervalor_get("codestpro1","")),25,"0",0);
	$ls_codestpro2=str_pad(trim($io_fun_nomina->uf_obtenervalor_get("codestpro2","")),25,"0",0);
	$ls_codestpro3=str_pad(trim($io_fun_nomina->uf_obtenervalor_get("codestpro3","")),25,"0",0);
	$ls_codestpro4=str_pad(trim($io_fun_nomina->uf_obtenervalor_get("codestpro4","")),25,"0",0);
	$ls_codestpro5=str_pad(trim($io_fun_nomina->uf_obtenervalor_get("codestpro5","")),25,"0",0);
	$ls_denestpro1=trim($io_fun_nomina->uf_obtenervalor_get("denestpro1",""));
	$ls_denestpro2=trim($io_fun_nomina->uf_obtenervalor_get("denestpro2",""));
	$ls_denestpro3=trim($io_fun_nomina->uf_obtenervalor_get("denestpro3",""));
	$ls_denestpro4=trim($io_fun_nomina->uf_obtenervalor_get("denestpro4",""));
	$ls_denestpro5=trim($io_fun_nomina->uf_obtenervalor_get("denestpro5",""));
	$ls_estcla=trim($io_fun_nomina->uf_obtenervalor_get("estcla",""));
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$li_len1=0;
	$li_len2=0;
	$li_len3=0;
	$li_len4=0;
	$li_len5=0;
	$ls_tituloprog="";
	$ls_programatica="";
	$io_fun_nomina->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_tituloprog);
	$io_fun_nomina->uf_formatoprogramatica($ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5,&$ls_programatica);
	$ls_titulo="<b>Resumen Contable</b>";
	$ls_titulo2="<b>Presupuestario de Conceptos</b>";
	$ls_titulo3="<b>".$ls_tituloprog."</b>";
	$ls_titulo4="<b>".$ls_programatica."</b>";
	$ls_periodo="<b>Período Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."</b>";
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_titulo2,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido) // Buscamos la información que afecta el presupuesto
	{
		switch($ls_conpronom)
		{
			case "1":
				$lb_valido=$io_report->uf_contableconceptos_especifico_presupuesto_proyecto($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
				                                                                   			$ls_codestpro4,$ls_codestpro5,$ls_estcla,
																							$ls_subnomdes,$ls_subnomhas);
				break;
				
			default:
				$lb_valido=$io_report->uf_contableconceptos_especifico_presupuesto($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
				                                                                   $ls_codestpro4,$ls_codestpro5,$ls_estcla,
																				   $ls_subnomdes,$ls_subnomhas);
				break;
		}
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
		$io_pdf->ezSetCmMargins(3.5,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_titulo2,$ls_titulo3,$ls_titulo4,$ls_desnom,$ls_periodo,$ls_denestpro1,$ls_denestpro2,$ls_denestpro3,$ls_denestpro4,$ls_denestpro5,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		
  	    //--------------------------------------------- Imprimir el detalle Presupuestario------------------------------------------------	
		$li_totrow=$io_report->DS->getRowCount("cueprecon");
		$li_totalpresupuesto=0;
		$li_totalpersonal=0;
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_cueprecon=trim($io_report->DS->data["cueprecon"][$li_i]);
			$ls_denominacion=$io_report->DS->data["denominacion"][$li_i];
			$ls_desded=$io_report->DS->data["desded"][$li_i];
			$ls_destipper=$io_report->DS->data["destipper"][$li_i];
			$li_total=$io_report->DS->data["total"][$li_i];
			$li_personal=$io_report->DS->data["totalpersonal"][$li_i];
			$li_totalpresupuesto=$li_totalpresupuesto+$li_total;
			$li_totalpersonal=$li_totalpersonal+$li_personal;
			$li_total=$io_fun_nomina->uf_formatonumerico($li_total);
			$li_personal=number_format($li_personal,0,"","");
			$la_data[$li_i]=array('partida'=>$ls_cueprecon,'denominacion'=>$ls_denominacion,
								  'personal'=>$ls_desded.' - '.$ls_destipper,'cantidad'=>$li_personal,'total'=>$li_total);
		}
		$io_report->DS->resetds("cueprecon");
		if($li_totrow>0)
		{
			uf_print_cabecera_presupuesto($io_pdf); // Imprimimos la cabecera de presupuesto
			uf_print_detalle_presupuesto($la_data,$io_pdf); // Imprimimos el detalle presupuestario
			$li_totalpresupuesto=$io_fun_nomina->uf_formatonumerico($li_totalpresupuesto);
			$li_totalpersonal=number_format($li_totalpersonal,0,"","");
			uf_print_pie_cabecera_presupuesto($li_totalpresupuesto,$li_totalpersonal,$io_pdf); // imprimimos los totales presupuestario
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