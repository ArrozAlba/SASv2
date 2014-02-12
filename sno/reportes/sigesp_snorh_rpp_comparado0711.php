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
		// Fecha Creación: 27/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo." Forma 0711";
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_comparado0711.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_periodo,$as_rango,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo // Período
		//	    		   as_rango // Rango de Meses
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		//----------------------------------------------Primer Encabezado-------------------------------------------------------------
		$io_pdf->line(30,590,970,590);
		$io_pdf->line(30,500,970,500);
		$io_pdf->line(30,590,30,500);
		$io_pdf->line(970,590,970,500);
		$io_pdf->addText(38,580,8,"<b>OFICINA NACIONAL DE PRESUPUESTO (ONAPRE)</b>");
		$io_pdf->addText(38,570,8,"<b>OFICINA DE PLANIFICACIÓN DEL SECTOR UNIVERSITARIO (OPSU)</b>"); 
		$li_tm=$io_pdf->getTextWidth(8,$_SESSION["la_empresa"]["nombre"]);		
		$tm=504-($li_tm/2);
		$io_pdf->addText($tm,550,8,"<b>".$_SESSION["la_empresa"]["nombre"]."</b>");
		$li_tm=$io_pdf->getTextWidth(8,"RECURSOS HUMANOS CLASIFICADOS POR TIPO DE CARGOS");		
		$tm=504-($li_tm/2);
		$io_pdf->addText($tm,540,8,"<b>RECURSOS HUMANOS CLASIFICADOS POR TIPO DE CARGOS</b>"); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(8,"(En ".$ls_bolivares.")");		
		$tm=504-($li_tm/2);
		$io_pdf->addText($tm,530,8,"<b>(En ".$ls_bolivares.")</b>"); 
		$io_pdf->addText(38,520,8,"<b>PRESUPUESTO AÑO:</b> ".substr($_SESSION["la_empresa"]["periodo"],0,4));
		$io_pdf->addText(38,510,8,"<b>".$as_periodo.":</b> ".$as_rango);	
		$io_pdf->line(880,509,940,509);
		$io_pdf->line(880,520,880,509);
		$io_pdf->line(940,520,940,509);
		$io_pdf->line(900,515,900,509);
		$io_pdf->line(920,515,920,509);
		$ld_fecha=date("d/m/Y");
		$io_pdf->addText(900,522,7,"Fecha"); 
		$io_pdf->addText(885,512,9,substr($ld_fecha,0,2)); // Agregar el día
		$io_pdf->addText(905,512,9,substr($ld_fecha,3,2)); // Agregar el mes
		$io_pdf->addText(925,512,9,substr($ld_fecha,8,2)); // Agregar el año
		//----------------------------------------------Segundo Encabezado----------------------------------------------------------
		$io_pdf->line(30,490,970,490);
		$io_pdf->line(30,420,970,420);
		$io_pdf->line(30,490,30,420);
		$io_pdf->line(970,490,970,420);
		$io_pdf->addText(33,455,8,"COD"); // 25 pts
		$io_pdf->line(55,490,55,420); 
		$io_pdf->addText(128,455,8,"DENOMINACIÓN");  // 220 pts
		$io_pdf->line(275,490,275,420); 
		$io_pdf->line(275,460,970,460);
		$io_pdf->addText(335,470,8,"NÚMERO DE CARGOS"); 
		$io_pdf->line(335,460,335,420); 
		$io_pdf->addText(280,448,7,"PROGRAMADO");  // 60 PTS
		$io_pdf->addText(280,438,7,"AL FINAL DEL"); 
		$li_tm=$io_pdf->getTextWidth(7,$as_periodo);		
		$tm=30-($li_tm/2);
		$tm=275+$tm;
		$io_pdf->addText($tm,428,7,$as_periodo); 
		$io_pdf->line(395,460,395,420); 
		$io_pdf->addText(338,445,7,"REAL AL FINAL"); // 60 pts		
		$li_tm=$io_pdf->getTextWidth(7,"DEL ".$as_periodo);		
		$tm=30-($li_tm/2);
		$tm=335+$tm;
		$io_pdf->addText($tm,435,7,"DEL ".$as_periodo); 
		$io_pdf->addText(415,450,7,"VARIACIÓN"); 		
		$io_pdf->line(395,447,475,447);
		$io_pdf->line(435,447,435,420); 
		$io_pdf->addText(397,432,7,"ABSOLUTA"); // 40 pts		
		$io_pdf->addText(450,432,7,"%"); // 40 pts			
		$io_pdf->line(475,490,475,420);
		$io_pdf->addText(598,475,8,"MONTO TOTAL"); 
		$io_pdf->addText(508,465,7,"<b>(Incluye los conceptos de sueldos,salarios,compensaciones y primas)</b>"); 
		$io_pdf->addText(479,443,7,"PROGRAMADO AL FINAL");  // 90 PTS
		$li_tm=$io_pdf->getTextWidth(7,"DEL ".$as_periodo);		
		$tm=45-($li_tm/2);
		$tm=475+$tm;
		$io_pdf->addText($tm,433,7,"DEL ".$as_periodo); 
		$io_pdf->line(565,460,565,420); 
		$io_pdf->addText(578,443,7,"REAL AL FINAL DEL"); // 90 pts		
		$li_tm=$io_pdf->getTextWidth(7,$as_periodo);		
		$tm=45-($li_tm/2);
		$tm=565+$tm;
		$io_pdf->addText($tm,433,7,$as_periodo); 
		$io_pdf->line(655,460,655,420); 
		$io_pdf->line(735,447,735,420); 
		$io_pdf->addText(675,432,7,"ABSOLUTA"); // 80 pts		
		$io_pdf->addText(752,432,7,"%"); // 40 pts			
		$io_pdf->addText(695,450,7,"VARIACIÓN"); 		
		$io_pdf->line(655,447,775,447);
		$io_pdf->line(775,490,775,420); 
		$io_pdf->addText(815,475,8,"PREVISION PARA EL PRÓXIMO"); 
		$io_pdf->line(870,460,870,420); 
		$io_pdf->addText(780,438,8,"NÚMERO DE CARGOS"); // 95 pts
		$io_pdf->addText(875,438,8,"MONTO EN BOLÍVARES"); // 100 pts
		$li_tm=$io_pdf->getTextWidth(8,$as_periodo);		
		$tm=98-($li_tm/2);
		$tm=775+$tm;
		$io_pdf->addText($tm,465,8,$as_periodo); 
		$io_pdf->addText(30,50,7,"FORMA 0711");

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
		// Fecha Creación: 27/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$la_columna=array('codigo'=>'',
						  'descripcion'=>'',
						  'cargoprogramado'=>'',
						  'cargoreal'=>'',
						  'cargoabsoluta'=>'',
						  'cargoporcentaje'=>'',
						  'montoprogramado'=>'',
						  'montoreal'=>'',
						  'montoabsoluta'=>'',
						  'montoporcentaje'=>'',
						  'cargoproximo'=>'',
						  'montoproximo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>505, // Ancho de la tabla
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>25), // Justificación y ancho de la columna
						 			   'descripcion'=>array('justification'=>'left','width'=>220), // Justificación y ancho de la columna
						 			   'cargoprogramado'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'cargoreal'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'cargoabsoluta'=>array('justification'=>'center','width'=>40), // Justificación y ancho de la columna
						 			   'cargoporcentaje'=>array('justification'=>'right','width'=>40), // Justificación y ancho de la columna
						 			   'montoprogramado'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'montoreal'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'montoabsoluta'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'montoporcentaje'=>array('justification'=>'right','width'=>40), // Justificación y ancho de la columna
						 			   'cargoproximo'=>array('justification'=>'center','width'=>95), // Justificación y ancho de la columna
						 			   'montoproximo'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
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
		//    Description: función que imprime los totales
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('total'=>'',
						  'cargoprogramado'=>'',
						  'cargoreal'=>'',
						  'cargoabsoluta'=>'',
						  'cargoporcentaje'=>'',
						  'montoprogramado'=>'',
						  'montoreal'=>'',
						  'montoabsoluta'=>'',
						  'montoporcentaje'=>'',
						  'cargoproximo'=>'',
						  'montoproximo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>505, // Ancho de la tabla
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'center','width'=>245), // Justificación y ancho de la columna
						 			   'cargoprogramado'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'cargoreal'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'cargoabsoluta'=>array('justification'=>'center','width'=>40), // Justificación y ancho de la columna
						 			   'cargoporcentaje'=>array('justification'=>'right','width'=>40), // Justificación y ancho de la columna
						 			   'montoprogramado'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'montoreal'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'montoabsoluta'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'montoporcentaje'=>array('justification'=>'right','width'=>40), // Justificación y ancho de la columna
						 			   'cargoproximo'=>array('justification'=>'center','width'=>95), // Justificación y ancho de la columna
						 			   'montoproximo'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="RECURSOS HUMANOS CLASIFICADOS POR TIPO DE CARGOS";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_rango=$io_fun_nomina->uf_obtenervalor_get("rango","");
	$ls_periodo=$io_fun_nomina->uf_obtenervalor_get("periodo","");
	$ls_tiporeporte=$io_fun_nomina->uf_obtenervalor_get("tiporeporte",0);
	$ls_bolivares="Bolívares";
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_snorh_class_reportbsf.php");
		$io_report=new sigesp_snorh_class_reportbsf();
		$ls_bolivares="Bolívares Fuertes";
	}
	$ls_desperiodo="";
	$ls_desrango="";
	switch($ls_periodo)
	{
		case "1": // Mensual
			$ls_desperiodo="MES";
			$ls_mes=substr($ls_rango,0,2);
			$ls_desrango=$io_fecha->uf_load_nombre_mes($ls_mes);
			break;
		case "2": // Bi-Mensual
			$ls_desperiodo="BIMESTRE";
			$ls_mes=substr($ls_rango,0,2);
			$ls_desrango=$io_fecha->uf_load_nombre_mes($ls_mes);
			$ls_mes=substr($ls_rango,3,2);
			$ls_desrango=$ls_desrango."-".$io_fecha->uf_load_nombre_mes($ls_mes);
			break;
		case "3": // Trimestral
			$ls_desperiodo="TRIMESTRE";
			$ls_mes=substr($ls_rango,0,2);
			$ls_desrango=$io_fecha->uf_load_nombre_mes($ls_mes);
			$ls_mes=substr($ls_rango,3,2);
			$ls_desrango=$ls_desrango."-".$io_fecha->uf_load_nombre_mes($ls_mes);
			break;
		case "4": // Semestral
			$ls_desperiodo="SEMESTRE";
			$ls_mes=substr($ls_rango,0,2);
			$ls_desrango=$io_fecha->uf_load_nombre_mes($ls_mes);
			$ls_mes=substr($ls_rango,3,2);
			$ls_desrango=$ls_desrango."-".$io_fecha->uf_load_nombre_mes($ls_mes);
			break;
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_comparado0711_programado($ls_rango); // Obtenemos el detalle del reporte
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
		$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(6.7,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_desperiodo,$ls_desrango,$io_pdf); // Imprimimos el encabezado de la página
		$li_totrow=$io_report->DS->getRowCount("codrep");
		$li_totalcargoprog=0;
		$li_totalcargoreal=0;
		$li_totalcargoabsoluta=0;
		$li_totalcargoporcentaje=0;
		$li_totalmontoprog=0;
		$li_totalmontoreal=0;
		$li_totalmontoabsoluta=0;
		$li_totalmontoporcentaje=0;
		$li_totalcargoproximo=0;
		$li_totalmontoproximo=0;
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_codded=$io_report->DS->data["codded"][$li_i];
			$ls_codtipper=$io_report->DS->data["codtipper"][$li_i];
			$ls_desded=$io_report->DS->data["desded"][$li_i];
			$ls_destipper=$io_report->DS->data["destipper"][$li_i];
			$li_cargoprog=0;
			$li_cargoreal=0;
			$li_cargoabsoluta=0;
			$li_cargoporcentaje=0;
			$li_montoprog=0;
			$li_montoreal=0;
			$li_montoabsoluta=0;
			$li_montoporcentaje=0;
			$li_cargoproximo=0;
			$li_montoproximo=0;
			$lb_valido=$io_report->uf_comparado0711_real($ls_rango,$ls_codded,$ls_codtipper,$li_cargoreal,$li_montoreal); // Obtenemos los valores reales
			if($lb_valido)
			{
				$li_desde=intval(substr($ls_rango,0,2));
				$li_hasta=intval(substr($ls_rango,3,2));
				for($li_s=$li_desde;$li_s<=$li_hasta;$li_s++)
				{
					switch($li_s)
					{
						case 1;
							$li_cargoprog=$li_cargoprog+round($io_report->DS->data["carene"][$li_i],2);
							$li_montoprog=$li_montoprog+round($io_report->DS->data["monene"][$li_i],2);
							 break;
						case 2;
							$li_cargoprog=$li_cargoprog+round($io_report->DS->data["carfeb"][$li_i],2);
							$li_montoprog=$li_montoprog+round($io_report->DS->data["monfeb"][$li_i],2);
							 break;
						case 3;
							$li_cargoprog=$li_cargoprog+round($io_report->DS->data["carmar"][$li_i],2);
							$li_montoprog=$li_montoprog+round($io_report->DS->data["monmar"][$li_i],2);
							 break;
						case 4;
							$li_cargoprog=$li_cargoprog+round($io_report->DS->data["carabr"][$li_i],2);
							$li_montoprog=$li_montoprog+round($io_report->DS->data["monabr"][$li_i],2);
							 break;
						case 5;
							$li_cargoprog=$li_cargoprog+round($io_report->DS->data["carmay"][$li_i],2);
							$li_montoprog=$li_montoprog+round($io_report->DS->data["monmay"][$li_i],2);
							 break;
						case 6;
							$li_cargoprog=$li_cargoprog+round($io_report->DS->data["carjun"][$li_i],2);
							$li_montoprog=$li_montoprog+round($io_report->DS->data["monjun"][$li_i],2);
							 break;
						case 7;
							$li_cargoprog=$li_cargoprog+round($io_report->DS->data["carjul"][$li_i],2);
							$li_montoprog=$li_montoprog+round($io_report->DS->data["monjul"][$li_i],2);
							 break;
						case 8;
							$li_cargoprog=$li_cargoprog+round($io_report->DS->data["carago"][$li_i],2);
							$li_montoprog=$li_montoprog+round($io_report->DS->data["monago"][$li_i],2);
							 break;
						case 9;
							$li_cargoprog=$li_cargoprog+round($io_report->DS->data["carsep"][$li_i],2);
							$li_montoprog=$li_montoprog+round($io_report->DS->data["monsep"][$li_i],2);
							 break;
						case 10;
							$li_cargoprog=$li_cargoprog+round($io_report->DS->data["caroct"][$li_i],2);
							$li_montoprog=$li_montoprog+round($io_report->DS->data["monoct"][$li_i],2);
							 break;
						case 11;
							$li_cargoprog=$li_cargoprog+round($io_report->DS->data["carnov"][$li_i],2);
							$li_montoprog=$li_montoprog+round($io_report->DS->data["monnov"][$li_i],2);
							 break;
						case 12;
							$li_cargoprog=$li_cargoprog+round($io_report->DS->data["cardic"][$li_i],2);
							$li_montoprog=$li_montoprog+round($io_report->DS->data["mondic"][$li_i],2);
							 break;
					}
				}
				$li_desde=intval(substr($ls_rango,0,2)+1);
				$li_hasta=intval(substr($ls_rango,3,2)+1);
				for($li_s=$li_desde;$li_s<=$li_hasta;$li_s++)
				{
					switch($li_s)
					{
						case 1;
							$li_cargoproximo=$li_cargoproximo+round($io_report->DS->data["carene"][$li_i],2);
							$li_montoproximo=$li_montoproximo+round($io_report->DS->data["monene"][$li_i],2);
							 break;
						case 2;
							$li_cargoproximo=$li_cargoproximo+round($io_report->DS->data["carfeb"][$li_i],2);
							$li_montoproximo=$li_montoproximo+round($io_report->DS->data["monfeb"][$li_i],2);
							 break;
						case 3;
							$li_cargoproximo=$li_cargoproximo+round($io_report->DS->data["carmar"][$li_i],2);
							$li_montoproximo=$li_montoproximo+round($io_report->DS->data["monmar"][$li_i],2);
							 break;
						case 4;
							$li_cargoproximo=$li_cargoproximo+round($io_report->DS->data["carabr"][$li_i],2);
							$li_montoproximo=$li_montoproximo+round($io_report->DS->data["monabr"][$li_i],2);
							 break;
						case 5;
							$li_cargoproximo=$li_cargoproximo+round($io_report->DS->data["carmay"][$li_i],2);
							$li_montoproximo=$li_montoproximo+round($io_report->DS->data["monmay"][$li_i],2);
							 break;
						case 6;
							$li_cargoproximo=$li_cargoproximo+round($io_report->DS->data["carjun"][$li_i],2);
							$li_montoproximo=$li_montoproximo+round($io_report->DS->data["monjun"][$li_i],2);
							 break;
						case 7;
							$li_cargoproximo=$li_cargoproximo+round($io_report->DS->data["carjul"][$li_i],2);
							$li_montoproximo=$li_montoproximo+round($io_report->DS->data["monjul"][$li_i],2);
							 break;
						case 8;
							$li_cargoproximo=$li_cargoproximo+round($io_report->DS->data["carago"][$li_i],2);
							$li_montoproximo=$li_montoproximo+round($io_report->DS->data["monago"][$li_i],2);
							 break;
						case 9;
							$li_cargoproximo=$li_cargoproximo+round($io_report->DS->data["carsep"][$li_i],2);
							$li_montoproximo=$li_montoproximo+round($io_report->DS->data["monsep"][$li_i],2);
							 break;
						case 10;
							$li_cargoproximo=$li_cargoproximo+round($io_report->DS->data["caroct"][$li_i],2);
							$li_montoproximo=$li_montoproximo+round($io_report->DS->data["monoct"][$li_i],2);
							 break;
						case 11;
							$li_cargoproximo=$li_cargoproximo+round($io_report->DS->data["carnov"][$li_i],2);
							$li_montoproximo=$li_montoproximo+round($io_report->DS->data["monnov"][$li_i],2);
							 break;
						case 12;
							$li_cargoproximo=$li_cargoproximo+round($io_report->DS->data["cardic"][$li_i],2);
							$li_montoproximo=$li_montoproximo+round($io_report->DS->data["mondic"][$li_i],2);
							 break;
					}
				}
				$li_cargoabsoluta=$li_cargoprog-$li_cargoreal;
				if($li_cargoprog>0)
				{
					$li_cargoporcentaje=($li_cargoabsoluta*100)/$li_cargoprog;
				}
				$li_montoabsoluta=$li_montoprog-$li_montoreal;
				if($li_montoprog>0)
				{
					$li_montoporcentaje=($li_montoabsoluta*100)/$li_montoprog;
				}
				if($ls_codtipper=="0000")
				{
					$ls_codigo="<b>".$ls_codded."</b>";
					$ls_descripcion="<b>".$ls_desded."</b>";
					$li_totalcargoprog=$li_totalcargoprog+$li_cargoprog;
					$li_totalcargoreal=$li_totalcargoreal+$li_cargoreal;
					$li_totalcargoabsoluta=$li_totalcargoabsoluta+$li_cargoabsoluta;
					$li_totalcargoporcentaje=$li_totalcargoporcentaje+$li_cargoporcentaje;
					$li_totalmontoprog=$li_totalmontoprog+$li_montoprog;
					$li_totalmontoreal=$li_totalmontoreal+$li_montoreal;
					$li_totalmontoabsoluta=$li_totalmontoabsoluta+$li_montoabsoluta;
					$li_totalmontoporcentaje=$li_totalmontoporcentaje+$li_montoporcentaje;
					$li_totalcargoproximo=$li_totalcargoproximo+$li_cargoproximo;
					$li_totalmontoproximo=$li_totalmontoproximo+$li_montoproximo;
				}
				else
				{
					$ls_codigo=substr($ls_codtipper,1,3);
					$ls_descripcion="					".$ls_destipper;
				}				
				$li_cargoporcentaje=$io_fun_nomina->uf_formatonumerico($li_cargoporcentaje);
				$li_montoporcentaje=$io_fun_nomina->uf_formatonumerico($li_montoporcentaje);
				$li_montoprog=$io_fun_nomina->uf_formatonumerico($li_montoprog);
				$li_montoabsoluta=$io_fun_nomina->uf_formatonumerico($li_montoabsoluta);
				$li_montoreal=$io_fun_nomina->uf_formatonumerico($li_montoreal);
				$li_montoproximo=$io_fun_nomina->uf_formatonumerico($li_montoproximo);
				$la_data[$li_i]=array('codigo'=>$ls_codigo,'descripcion'=>$ls_descripcion,'cargoprogramado'=>$li_cargoprog,
									  'cargoreal'=>$li_cargoreal,'cargoabsoluta'=>$li_cargoabsoluta,'cargoporcentaje'=>$li_cargoporcentaje,
									  'montoprogramado'=>$li_montoprog,'montoreal'=>$li_montoreal,'montoabsoluta'=>$li_montoabsoluta,
									  'montoporcentaje'=>$li_montoporcentaje,'cargoproximo'=>$li_cargoproximo,'montoproximo'=>$li_montoproximo);
			}
		}
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		unset($la_data);			
		$li_totalcargoporcentaje=$io_fun_nomina->uf_formatonumerico($li_totalcargoporcentaje);
		$li_totalmontoporcentaje=$io_fun_nomina->uf_formatonumerico($li_totalmontoporcentaje);
		$li_totalmontoabsoluta=$io_fun_nomina->uf_formatonumerico($li_totalmontoabsoluta);
		$li_totalmontoprog=$io_fun_nomina->uf_formatonumerico($li_totalmontoprog);
		$li_totalmontoreal=$io_fun_nomina->uf_formatonumerico($li_totalmontoreal);
		$li_totalmontoproximo=$io_fun_nomina->uf_formatonumerico($li_totalmontoproximo);
		$la_data[1]=array('total'=>'<b>Total</b>','cargoprogramado'=>$li_totalcargoprog,'cargoreal'=>$li_totalcargoreal,
						  'cargoabsoluta'=>$li_totalcargoabsoluta,'cargoporcentaje'=>$li_totalcargoporcentaje,'montoprogramado'=>$li_totalmontoprog,
						  'montoreal'=>$li_totalmontoreal,'montoabsoluta'=>$li_totalmontoabsoluta,'montoporcentaje'=>$li_totalmontoporcentaje,
						  'cargoproximo'=>$li_totalcargoproximo,'montoproximo'=>$li_totalmontoproximo);
		uf_print_totales($la_data,$io_pdf); // Imprimimos el detalle 
		unset($la_data);			
		$io_report->DS->resetds("codrep");
		if($lb_valido) // Si no ocurrio ningún error
		{
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