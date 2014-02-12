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
		$ls_descripcion="Generó el Reporte ".$as_titulo." Forma 0506";
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_comparado0506.php",$ls_descripcion);
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
		// Fecha Creación: 19/01/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		//----------------------------------------------Primer Encabezado-------------------------------------------------------------
		$io_pdf->line(30,590,970,590);
		$io_pdf->line(30,520,970,520);
		$io_pdf->line(30,590,30,520);
		$io_pdf->line(970,590,970,520);
		$io_pdf->addText(38,580,8,"<b>".$_SESSION["la_empresa"]["codorgsig"]." - ".$_SESSION["la_empresa"]["nombre"]."</b>");
		$li_tm=$io_pdf->getTextWidth(12,"RECURSOS HUMANOS");		
		$tm=504-($li_tm/2);
		$io_pdf->addText($tm,555,12,"<b>RECURSOS HUMANOS</b>"); // Agregar el título
		$io_pdf->addText(38,530,8,"<b>".$_SESSION["la_empresa"]["nomorgads"]."</b>");
		$io_pdf->line(890,560,950,560);
		$io_pdf->line(890,573,890,560);
		$io_pdf->line(910,570,910,560);
		$io_pdf->line(930,570,930,560);
		$io_pdf->line(950,573,950,560);
		$io_pdf->addText(910,580,7,"Fecha"); 
		$io_pdf->addText(895,563,9,date("d")); // Agregar el día
		$io_pdf->addText(915,563,9,date("m")); // Agregar el mes
		$io_pdf->addText(935,563,9,substr(date("Y"),2,2)); // Agregar el año
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado($as_titulo,$as_periodo,$as_rango,&$io_clasificacion,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo // Período
		//	    		   as_rango // Rango de Meses
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 19/01/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_prefijo="Bs.F.";
		}
		else
		{
			$ls_prefijo="Bs.";
		}
		$io_pdf->saveState();
		//----------------------------------------------Segundo Encabezado----------------------------------------------------------
		$io_pdf->line(30,513,970,513);
		$io_pdf->line(30,500,970,500);
		$io_pdf->line(30,450,970,450);
		$io_pdf->line(30,513,30,450);
		$io_pdf->line(380,475,970,475);
		$io_pdf->line(970,513,970,450);
		$io_pdf->addText(600,503,8,"CLASIFICACIÓN DE PERSONAL");

		$io_pdf->addText(50,475,8,"COD"); // 60 pts
		$io_pdf->line(90,500,90,450); 
		$io_pdf->addText(200,475,8,"DENOMINACIÓN");  // 290 pts

		$io_pdf->line(380,500,380,450); 		
		$io_pdf->addText(410,490,7,"PROGRAMADO AL FINAL DEL MES ".$ls_prefijo.""); 
		$li_tm=$io_pdf->getTextWidth(7,strtoupper($as_rango));		
		$tm=92.5-($li_tm/2);
		$tm=380 + $tm;
		$io_pdf->addText($tm,480,7,strtoupper($as_rango)); // Agregar el título
		$io_pdf->line(455,475,455,450); 
		$io_pdf->addText(397,465,7,"   NÚMERO");   // 75 pts
		$io_pdf->addText(397,455,7,"DE CARGOS"); 
		$io_pdf->addText(485,460,7,"COSTO TOTAL");   // 110 pts
		
		$io_pdf->line(565,500,565,450);
		$io_pdf->addText(615,490,7,"REAL AL FINAL DEL MES ".$ls_prefijo.""); 
		$li_tm=$io_pdf->getTextWidth(7,strtoupper($as_rango));		
		$tm=92.5-($li_tm/2);
		$tm=565 + $tm;
		$io_pdf->addText($tm,480,7,strtoupper($as_rango)); // Agregar el título
		$io_pdf->line(640,475,640,450); 
		$io_pdf->addText(582,465,7,"   NÚMERO");   // 75 pts
		$io_pdf->addText(582,455,7,"DE CARGOS"); 
		$io_pdf->addText(670,460,7,"COSTO TOTAL");   // 110 pts
		 		
		$io_pdf->line(750,500,750,450); 		
		$io_pdf->addText(764,490,7,"VARIACIÓN DE CARGOS"); 
		$li_tm=$io_pdf->getTextWidth(7,strtoupper($as_rango));		
		$tm=55-($li_tm/2);
		$tm=750 + $tm;
		$io_pdf->addText($tm,480,7,strtoupper($as_rango)); // Agregar el título
		$io_pdf->line(820,475,820,450); 
		$io_pdf->addText(770,460,7,"ABSOLUTA"); // 80 pts		
		$io_pdf->addText(837,460,7,"%"); // 30 pts		

		$io_pdf->line(860,500,860,450); // 875
		$io_pdf->addText(867,490,7,"VARIACIÓN DE COSTOS ".$ls_prefijo.""); 
		$li_tm=$io_pdf->getTextWidth(7,strtoupper($as_rango));		
		$tm=55-($li_tm/2);
		$tm=860 + $tm;
		$io_pdf->addText($tm,480,7,strtoupper($as_rango)); // Agregar el título
		$io_pdf->line(930,475,930,450); 
		$io_pdf->addText(880,460,7,"ABSOLUTA"); // 80 pts		
		$io_pdf->addText(947,460,7,"%"); // 30 pts		

		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_clasificacion,'all');
	}// end function uf_print_encabezado
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
		// Fecha Creación: 22/01/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(452);
		$la_columna=array('codigo'=>'',
						  'descripcion'=>'',
						  'cargoprogramado'=>'',
						  'montoprogramado'=>'',
						  'cargoreal'=>'',
						  'montoreal'=>'',
						  'cargoabsoluta'=>'',
						  'cargoporcentaje'=>'',
						  'montoabsoluta'=>'',
						  'montoporcentaje'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>505, // Ancho de la tabla
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'descripcion'=>array('justification'=>'left','width'=>290), // Justificación y ancho de la columna
						 			   'cargoprogramado'=>array('justification'=>'center','width'=>75), // Justificación y ancho de la columna
						 			   'montoprogramado'=>array('justification'=>'right','width'=>110), // Justificación y ancho de la columna
						 			   'cargoreal'=>array('justification'=>'center','width'=>75), // Justificación y ancho de la columna
						 			   'montoreal'=>array('justification'=>'right','width'=>110), // Justificación y ancho de la columna
						 			   'cargoabsoluta'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'cargoporcentaje'=>array('justification'=>'right','width'=>40), // Justificación y ancho de la columna
						 			   'montoabsoluta'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'montoporcentaje'=>array('justification'=>'right','width'=>40))); // Justificación y ancho de la columna
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
		// Fecha Creación: 22/01/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('total'=>'',
						  'cargoprogramado'=>'',
						  'montoprogramado'=>'',
						  'cargoreal'=>'',
						  'montoreal'=>'',
						  'cargoabsoluta'=>'',
						  'cargoporcentaje'=>'',
						  'montoabsoluta'=>'',
						  'montoporcentaje'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>505, // Ancho de la tabla
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'center','width'=>350), // Justificación y ancho de la columna
						 			   'cargoprogramado'=>array('justification'=>'center','width'=>75), // Justificación y ancho de la columna
						 			   'montoprogramado'=>array('justification'=>'right','width'=>110), // Justificación y ancho de la columna
						 			   'cargoreal'=>array('justification'=>'center','width'=>75), // Justificación y ancho de la columna
						 			   'montoreal'=>array('justification'=>'right','width'=>110), // Justificación y ancho de la columna
						 			   'cargoabsoluta'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'cargoporcentaje'=>array('justification'=>'right','width'=>40), // Justificación y ancho de la columna
						 			   'montoabsoluta'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'montoporcentaje'=>array('justification'=>'right','width'=>40))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina_presupuesto($as_titulo,$as_periodo,$as_rango,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina_presupuesto
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo // Período
		//	    		   as_rango // Rango de Meses
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 19/01/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetY(513);
		$la_data[0]=array('titulo'=>'GASTOS DE PERSONAL (En Bolivares)');
		$la_columna=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>505, // Ancho de la tabla
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>940))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data[0]=array('codigo'=>'COD',
						  'descripcion'=>'DENOMINACIÓN',
						  'programado'=>'PROGRAMADO AL FINAL DEL MES '.strtoupper($as_rango),
						  'real'=>'REAL AL FINAL DEL MES '.strtoupper($as_rango),
						  'absoluta'=>'ABSOLUTA',
						  'relativa'=>'RELATIVA');
		$la_columna=array('codigo'=>'',
						  'descripcion'=>'',
						  'programado'=>'',
						  'real'=>'',
						  'absoluta'=>'',
						  'relativa'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>505, // Ancho de la tabla
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'descripcion'=>array('justification'=>'center','width'=>290), // Justificación y ancho de la columna
						 			   'programado'=>array('justification'=>'center','width'=>185), // Justificación y ancho de la columna
						 			   'real'=>array('justification'=>'center','width'=>185), // Justificación y ancho de la columna
						 			   'absoluta'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
						 			   'relativa'=>array('justification'=>'center','width'=>110))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina_presupuesto
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cuentas()
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_load_cuentas
		//		   Access: private 
		//	    Arguments: 
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/01/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_report;
		
		$io_report->DS->reset_ds();
		$io_report->DS->insertRow("codigo","401010000");
		$io_report->DS->insertRow("denominacion","SUELDOS Y SALARIOS Y OTRAS RETRIBUCIONES");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401010100");
		$io_report->DS->insertRow("denominacion","SUELDOS BÁSICO PERSONAL FIJO A TIEMPO COMPLETO");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401010400");
		$io_report->DS->insertRow("denominacion","SUELDO AL PERSONAL EN TRAMITE DE NOMBRAMIENTO");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401010900");
		$io_report->DS->insertRow("denominacion","REMUNERACIONES AL PERSONAL EN PERIODO DE DISPONIBILIDAD");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401011800");
		$io_report->DS->insertRow("denominacion","REMUNERACIONES AL PERSONAL CONTRATADO");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401011000");
		$io_report->DS->insertRow("denominacion","SALARIOS A OBREROS EN PUESTOS PERMANENTES A TIEMPO COMPLETO");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401011200");
		$io_report->DS->insertRow("denominacion","SALARIOS A OBREROS EN PUESTOS NO PERMANENTES");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","000040101");
		$io_report->DS->insertRow("denominacion","OTROS");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401019900");
		$io_report->DS->insertRow("denominacion","OTRAS RETRIBUCIONES");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		
		
		$io_report->DS->insertRow("codigo","401020000");
		$io_report->DS->insertRow("denominacion","COMPENSACIONES PREVISTAS EN LAS ESCALAS DE SUELDOS Y SALARIOS");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401020100");
		$io_report->DS->insertRow("denominacion","COMPENSACIONES PREVISTAS EN LA ESCALA DE SUELDOS AL PERSONAL EMPLEADO FIJO A TIEMPO COMPLETO");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401020200");
		$io_report->DS->insertRow("denominacion","COMPENSACIONES PREVISTAS EN LA ESCALA DE SUELDOS AL PERSONAL EMPLEADO FIJO A TIEMPO PARCIAL");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401020300");
		$io_report->DS->insertRow("denominacion","COMPENSACIONES PREVISTAS EN LA ESCALA DE SALARIOS AL PERSONAL OBRERO FIJO A TIEMPO COMPLETO");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");

		$io_report->DS->insertRow("codigo","401030000");
		$io_report->DS->insertRow("denominacion","PRIMAS A EMPLEADOS, OBREROS, PERSONAL MILITAR Y PARLAMENTARIO");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");

		$io_report->DS->insertRow("codigo","401040000");
		$io_report->DS->insertRow("denominacion","COMPLEMENTOS DE SUELDOS Y SALARIOS");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401040100");
		$io_report->DS->insertRow("denominacion","COMPLEMENTOS A EMPLEADOS POR HORAS EXTRAORDINARIAS Ó POR SOBRETIEMPO A EMPLEADOS ");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401050300");
		$io_report->DS->insertRow("denominacion","BONO VACACIONAL A EMPLEADOS");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401040700");
		$io_report->DS->insertRow("denominacion","BONIFICACIÓN A EMPLEADOS");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401040900");
		$io_report->DS->insertRow("denominacion","BONO COMPENSATORIO DE TRANSPORTE A EMPLEADOS");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401040800");
		$io_report->DS->insertRow("denominacion","BONO COMPENSATORIO DE ALIMENTACIÓN A EMPLEADOS");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401040600");
		$io_report->DS->insertRow("denominacion","COMPLEMENTO A EMPLEADOS POR COMISIÓN DE SERVICIOS");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401050000");
		$io_report->DS->insertRow("denominacion","AGUINALDOS, UTILIDADES Ó BONIFICACIÓN LEGAL Y BONO VACACIONAL EMPLEADOS, OBREROS, CONTRATADOS, PERSONAL MILITAR Y PARLAMENTARIOS ");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");

		$io_report->DS->insertRow("codigo","401060000");
		$io_report->DS->insertRow("denominacion","APORTES PATRONALES POR EMPLEADOS, OBREROS, PERSONAL MILITAR Y PARLAMENTARIOS");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401060400");
		$io_report->DS->insertRow("denominacion","SEGURO DE PARO FORZOSO");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401060800");
		$io_report->DS->insertRow("denominacion","SEGURO DE PARO FORZOSO");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401060500");
		$io_report->DS->insertRow("denominacion","FONDO DE AHORRO HABITACIONAL OBLIGATORIO");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401061300");
		$io_report->DS->insertRow("denominacion","FONDO DE AHORRO HABITACIONAL OBLIGATORIO");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401060100");
		$io_report->DS->insertRow("denominacion","APORTE AL IVSS");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401061000");
		$io_report->DS->insertRow("denominacion","APORTE AL IVSS");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401060300");
		$io_report->DS->insertRow("denominacion","APORTE AL FONDO DE JUBILACIÓN");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","401061900");
		$io_report->DS->insertRow("denominacion","APORTE AL FONDO DE JUBILACIÓN");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		$io_report->DS->insertRow("codigo","000040106");
		$io_report->DS->insertRow("denominacion","OTROS APORTES");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");

		$io_report->DS->insertRow("codigo","401070000");
		$io_report->DS->insertRow("denominacion","ASISTENCIA SOCIO-ECON. A EMPLEADOS, OBREROS, CONTRATADOS, PERSONAL MILITAR Y PARLAMENTARIOS");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");

		$io_report->DS->insertRow("codigo","401080000");
		$io_report->DS->insertRow("denominacion","PRESTACIONES SOCIALES E INDEMNIZACIONES A EMPLEADOS, OBREROS, CONTRATADOS, PERSONAL MILITAR Y PARLAMENTARIO");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		
		$io_report->DS->insertRow("codigo","401090000");
		$io_report->DS->insertRow("denominacion","CAPACITACIÓN Y ADRIESTRAMIENTO REALIZADO POR PERSONAL DEL ORGANISMO");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");

		$io_report->DS->insertRow("codigo","401960000");
		$io_report->DS->insertRow("denominacion","OTROS GASTOS DEL PERSONAL EMPLEADO");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");

		$io_report->DS->insertRow("codigo","401970000");
		$io_report->DS->insertRow("denominacion","OTROS GASTOS DEL PERSONAL OBRERO");
		$io_report->DS->insertRow("programado","0");
		$io_report->DS->insertRow("real","0");
		$io_report->DS->insertRow("absoluta","0");
		$io_report->DS->insertRow("porcentaje","0");
		
	}// end function uf_load_cuentas
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_presupuesto($la_data,$as_rango,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_presupuesto
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/01/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('codigo'=>'COD',
						  'descripcion'=>'DENOMINACIÓN',
						  'programado'=>'PROGRAMADO AL FINAL DEL MES '.strtoupper($as_rango),
						  'real'=>'REAL AL FINAL DEL MES '.strtoupper($as_rango),
						  'absoluta'=>'ABSOLUTA',
						  'porcentaje'=>'%');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>505, // Ancho de la tabla
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'descripcion'=>array('justification'=>'left','width'=>290), // Justificación y ancho de la columna
						 			   'programado'=>array('justification'=>'right','width'=>185), // Justificación y ancho de la columna
						 			   'real'=>array('justification'=>'right','width'=>185), // Justificación y ancho de la columna
						 			   'absoluta'=>array('justification'=>'right','width'=>110), // Justificación y ancho de la columna
						 			   'porcentaje'=>array('justification'=>'right','width'=>110))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle_presupuesto
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales_presupuesto($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales_presupuesto
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los totales
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/01/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('total'=>'',
						  'programado'=>'',
						  'real'=>'',
						  'absoluta'=>'',
						  'porcentaje'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>505, // Ancho de la tabla
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'center','width'=>350), // Justificación y ancho de la columna
						 			   'programado'=>array('justification'=>'right','width'=>185), // Justificación y ancho de la columna
						 			   'real'=>array('justification'=>'right','width'=>185), // Justificación y ancho de la columna
						 			   'absoluta'=>array('justification'=>'right','width'=>110), // Justificación y ancho de la columna
						 			   'porcentaje'=>array('justification'=>'right','width'=>110))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_totales_presupuesto
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
	$ls_titulo="RECURSOS HUMANOS";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_rango=$io_fun_nomina->uf_obtenervalor_get("rango","");
	$ls_periodo=$io_fun_nomina->uf_obtenervalor_get("periodo","");
	$ls_tiporeporte=$io_fun_nomina->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_snorh_class_reportbsf.php");
		$io_report=new sigesp_snorh_class_reportbsf();
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
		/*case "2": // Bi-Mensual
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
			break;*/
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_comparado0506_programado($ls_rango); // Obtenemos el detalle del reporte
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
		$io_pdf->ezSetCmMargins(4.35,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_desperiodo,$ls_desrango,$io_pdf); // Imprimimos el encabezado de la página

		/////////////////////////////////////    CLASIFICACIÓN DEL PERSONAL   ///////////////////////////////////////////////////////////////////
		$io_clasificacion=$io_pdf->openObject();
		uf_print_encabezado($ls_titulo,$ls_desperiodo,$ls_desrango,&$io_clasificacion,&$io_pdf);
		$li_totrow=$io_report->DS->getRowCount("codrep");
		$li_totalcargoprog=0;
		$li_totalcargoreal=0;
		$li_totalcargoabsoluta=0;
		$li_totalcargoporcentaje=0;
		$li_totalmontoprog=0;
		$li_totalmontoreal=0;
		$li_totalmontoabsoluta=0;
		$li_totalmontoporcentaje=0;
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
			$lb_valido=$io_report->uf_comparado0506_real($ls_rango,$ls_codded,$ls_codtipper,$li_cargoreal,$li_montoreal); // Obtenemos los valores reales
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
				$la_data[$li_i]=array('codigo'=>$ls_codigo,'descripcion'=>$ls_descripcion,'cargoprogramado'=>$li_cargoprog,
									  'montoprogramado'=>$li_montoprog,'cargoreal'=>$li_cargoreal,'montoreal'=>$li_montoreal,
									  'cargoabsoluta'=>$li_cargoabsoluta,'cargoporcentaje'=>$li_cargoporcentaje,
									  'montoabsoluta'=>$li_montoabsoluta,'montoporcentaje'=>$li_montoporcentaje);
			}
		}
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		unset($la_data);			
		$li_totalcargoporcentaje=$io_fun_nomina->uf_formatonumerico($li_totalcargoporcentaje);
		$li_totalmontoporcentaje=$io_fun_nomina->uf_formatonumerico($li_totalmontoporcentaje);
		$li_totalmontoabsoluta=$io_fun_nomina->uf_formatonumerico($li_totalmontoabsoluta);
		$li_totalmontoprog=$io_fun_nomina->uf_formatonumerico($li_totalmontoprog);
		$li_totalmontoreal=$io_fun_nomina->uf_formatonumerico($li_totalmontoreal);
		if($ls_tiporeporte==1)
		{
			$ls_prefijo="Bs.F.";
		}
		else
		{
			$ls_prefijo="Bs.";
		}
		$la_data[1]=array('total'=>'<b>Total '.$ls_prefijo.'</b>','cargoprogramado'=>$li_totalcargoprog,'montoprogramado'=>$li_totalmontoprog,
						  'cargoreal'=>$li_totalcargoreal,'montoreal'=>$li_totalmontoreal,
						   'cargoabsoluta'=>$li_totalcargoabsoluta,'cargoporcentaje'=>$li_totalcargoporcentaje,
						  'montoabsoluta'=>$li_totalmontoabsoluta,'montoporcentaje'=>$li_totalmontoporcentaje);
		uf_print_totales($la_data,$io_pdf); // Imprimimos el detalle 
		unset($la_data);			
		$io_report->DS->resetds("codrep");
		$io_pdf->stopObject($io_clasificacion); // Detener el objeto cabecera
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		/////////////////////////////////////        GASTOS DEL PERSONAL      ///////////////////////////////////////////////////////////////////
		$la_cuentas[1]="40101";
		$la_cuentas[2]="40102";
		$la_cuentas[3]="40103";
		$la_cuentas[4]="40104";
		$la_cuentas[5]="40105";
		$la_cuentas[6]="40106";
		$la_cuentas[7]="40107";
		$la_cuentas[8]="40108";
		$la_cuentas[9]="40109";
		$la_cuentas[10]="40196";
		$la_cuentas[11]="40197";
		uf_load_cuentas();
		$li_total_programado=0;
		$li_total_real=0;
		for($li_i=1;($li_i<=11)&&($lb_valido);$li_i++)
		{
			$ls_cuenta=$la_cuentas[$li_i];
			$lb_valido=$io_report->uf_comparado0506_gasto_real($ls_rango,$ls_cuenta);
			$li_totrow=$io_report->DS_detalle->getRowCount("anocur");
			$li_total_cuenta=0;
			$li_total_otros=0;
			for($li_j=1;(($li_j<=$li_totrow)&&($lb_valido));$li_j++)
			{
				$ls_cueprecon=substr($io_report->DS_detalle->data["cuenta"][$li_j],0,7);
				$li_real=abs($io_report->DS_detalle->data["total"][$li_j]);
				$li_columna = $io_report->DS->find("codigo",$ls_cueprecon."00");
				if($li_columna<>0)
				{
					$li_valor=$io_report->DS->getValue("real",$li_columna);
					$li_valor=$li_valor+$li_real;
					$io_report->DS->updateRow("real",$li_valor,$li_columna);
				}
				else
				{
					$li_total_otros=$li_total_otros+$li_real;
				}
				$li_total_cuenta=$li_total_cuenta+$li_real;
				if($li_j==$li_totrow)
				{
					$li_columna = $io_report->DS->find("codigo",$ls_cuenta."0000");
					$io_report->DS->updateRow("real",$li_total_cuenta,$li_columna);
					$li_total_real=$li_total_real+$li_total_cuenta;
					$li_columna = $io_report->DS->find("codigo","0000".$ls_cuenta);
					$io_report->DS->updateRow("real",$li_total_otros,$li_columna);
				}
			}
			$io_report->DS_detalle->resetds("anocur");
		}
		for($li_i=1;($li_i<=11)&&($lb_valido);$li_i++)
		{
			$ls_cuenta=$la_cuentas[$li_i];
			$lb_valido=$io_report->uf_comparado0506_gasto_programado($ls_rango,$ls_cuenta);
			$li_totrow=$io_report->DS_detalle->getRowCount("anocur");
			$li_total_cuenta=0;
			$li_total_otros=0;
			for($li_j=1;(($li_j<=$li_totrow)&&($lb_valido));$li_j++)
			{
				$ls_cueprecon=substr($io_report->DS_detalle->data["cuenta"][$li_j],0,7);
				$li_programado=abs($io_report->DS_detalle->data["programado"][$li_j]);
				$ls_status=$io_report->DS_detalle->data["status"][$li_j];
				$li_columna = $io_report->DS->find("codigo",$ls_cueprecon."00");
				if($li_columna<>0)
				{
					$li_valor=$io_report->DS->getValue("programado",$li_columna);
					$li_valor=$li_valor+$li_programado;
					$io_report->DS->updateRow("programado",$li_valor,$li_columna);
					$li_total_cuenta=$li_total_cuenta+$li_programado;
				}
				else
				{
					$li_total_otros=$li_total_otros+$li_programado;
				}
				if($ls_status=="S")
				{
					$li_total_programado=$li_total_programado+$li_programado;
				}
				if($li_j==$li_totrow)
				{
					//$li_columna = $io_report->DS->find("codigo",$ls_cuenta."0000");
					//$io_report->DS->updateRow("programado",$li_total_cuenta,$li_columna);
					//$li_total_programado=$li_total_programado+$li_programado;
					
					$li_columna = $io_report->DS->find("codigo","0000".$ls_cuenta);
					$io_report->DS->updateRow("programado",$li_total_otros,$li_columna);
				}
			}
			$io_report->DS_detalle->resetds("anocur");
		}
		$li_totrow=$io_report->DS->getRowCount("codigo");
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
			$ls_codigo=$io_report->DS->data["codigo"][$li_i];
			$ls_descripcion=$io_report->DS->data["denominacion"][$li_i];
			$li_programado=$io_report->DS->data["programado"][$li_i];
			$li_real=$io_report->DS->data["real"][$li_i];
			$li_absoluta=$li_programado-$li_real;
			$li_porcentaje=0;
			if($li_programado>0)
			{
				$li_porcentaje=($li_real*100)/$li_programado;
			}
			$li_programado=$io_fun_nomina->uf_formatonumerico($li_programado);
			$li_real=$io_fun_nomina->uf_formatonumerico($li_real);
			$li_absoluta=$io_fun_nomina->uf_formatonumerico($li_absoluta);
			$li_porcentaje=$io_fun_nomina->uf_formatonumerico($li_porcentaje);
			if(substr(trim($ls_codigo),0,4)=="0000")
			{
				$ls_codigo="";
			}
			$la_data[$li_i]=array('codigo'=>$ls_codigo,'descripcion'=>$ls_descripcion,'programado'=>$li_programado,
								  'real'=>$li_real,'absoluta'=>$li_absoluta,'porcentaje'=>$li_porcentaje);
		}
		$io_pdf->ezNewPage(); 
		uf_print_encabezado_pagina_presupuesto($ls_titulo,$ls_desperiodo,$ls_desrango,$io_pdf); // Imprimimos el encabezado de la página
		uf_print_detalle_presupuesto($la_data,$ls_desrango,$io_pdf);
		unset($la_data);
		$li_total_absoluta=$li_total_programado-$li_total_real;
		$li_total_porcentaje=0;
		if($li_total_programado>0)
		{
			$li_total_porcentaje=($li_total_real*100)/$li_total_programado;
		}
		$li_total_programado=$io_fun_nomina->uf_formatonumerico($li_total_programado);
		$li_total_real=$io_fun_nomina->uf_formatonumerico($li_total_real);
		$li_total_absoluta=$io_fun_nomina->uf_formatonumerico($li_total_absoluta);
		$li_total_porcentaje=$io_fun_nomina->uf_formatonumerico($li_total_porcentaje);
		$la_data[1]=array('total'=>'TOTAL GASTOS DEL PERSONAL','programado'=>$li_total_programado,
							  'real'=>$li_total_real,'absoluta'=>$li_total_absoluta,'porcentaje'=>$li_total_porcentaje);
		uf_print_totales_presupuesto($la_data,&$io_pdf);
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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