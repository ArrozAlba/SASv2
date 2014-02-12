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
	function uf_print_encabezado_pagina($as_titulo,$as_moneda,$as_trimestre,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//                 $as_moneda // Moneda
		//	    		   as_trimestre // Nro. del Trimestre
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 26/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(24,380,24,450);
		$io_pdf->line(39,380,39,450);
		$io_pdf->line(54,380,54,450);
		$io_pdf->line(69,380,69,460);
		$io_pdf->line(10,450,70,450);
		$io_pdf->line(199,380,199,460);
		$io_pdf->line(279,380,279,460);
		$io_pdf->line(349,380,349,460);
		$io_pdf->line(419,380,419,450);
		$io_pdf->line(489,380,489,430);
		$io_pdf->line(559,380,559,430);
		$io_pdf->line(629,380,629,450);
		$io_pdf->line(699,380,699,430);
		$io_pdf->line(769,380,769,430);
		$io_pdf->line(839,380,839,430);
		$io_pdf->line(919,380,919,460);
		$io_pdf->line(349,450,919,450);
		$io_pdf->line(419,430,919,430);
		$io_pdf->addText(22,384,7,"RAMO",270);
		$io_pdf->addText(37,384,7,"SUB-RAMO",270);
		$io_pdf->addText(52,384,7,"ESPECIFICA",270);
		$io_pdf->addText(67,384,7,"SUB-ESPECÍFICA",270);
		$io_pdf->addText(105,400,7,"DENOMINACION");
		$io_pdf->addText(210,400,7,"PRESUPUESTO");
		$io_pdf->addText(215,390,7,"APROBADO");
		$io_pdf->addText(285,400,7,"PRESUPUESTO");
		$io_pdf->addText(290,390,7,"MODIFICADO");
		$io_pdf->addText(352,410,7,"PROGRAMADO EN ");
		$io_pdf->addText(358,400,7,"EL TRIMESTRE");
		$io_pdf->addText(373,390,7,"No. ".$as_trimestre);
		$io_pdf->addText(457,435,7,"EJECUTADO EN EL TRIMESTRE No. ".$as_trimestre);
		$io_pdf->addText(430,390,7,"DEVENGADO");
		$io_pdf->addText(505,390,7,"LIQUIDADO");
		$io_pdf->addText(575,390,7,"RECAUDADO");
		$io_pdf->addText(710,435,7,"ACUMULADO AL TRIMESTRE No. ".$as_trimestre);
		$io_pdf->addText(640,390,7,"PROGRAMADO");
		$io_pdf->addText(710,390,7,"DEVENGADO");
		$io_pdf->addText(785,390,7,"LIQUIDADO");
		$io_pdf->addText(860,390,7,"RECAUDADO");
		$io_pdf->addText(935,400,7,"INGRESOS POR");
		$io_pdf->addText(950,390,7,"RECIBIR");
		$io_pdf->rectangle(10,460,990,120);
		$io_pdf->rectangle(10,382,990,78);
		
		$li_tm=$io_pdf->getTextWidth(16,$as_titulo);
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,500,16,$as_titulo); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(10,'<b>'.$as_moneda.'</b>');
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,480,10,'<b>'.$as_moneda.'</b>'); // Agregar el título		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_titulo_reporte($io_encabezado,$as_programatica,$ai_ano,$as_mes,$as_codestpro1,$as_denestpro1,$as_trimestre,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 26/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSetY(570);
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_nombre=$_SESSION["la_empresa"]["nombre"];
		$ls_nomorgads=$_SESSION["la_empresa"]["nomorgads"];
		$ls_codasiona   = $_SESSION['la_empresa']['codasiona'];
		
		$la_data=array(array('name'=>'<b>CODIGO PRESUPUESTARIO DEL ENTE:     </b>'.'<b>'.$ls_codasiona.'</b>'),
		               array('name'=>'<b>DENOMINACION DEL ENTE:    </b>'.'<b>'.$ls_nombre.'</b>'),
					   array('name'=>'<b>ORGANO DE ADSCRIPCION:    </b>'.'<b>'.$ls_nombre.'</b>'),
					   array('name'=>'<b>'.strtoupper($_SESSION["la_empresa"]["nomestpro1"]).':    </b>'.'<b>'.$as_denestpro1.' - '.$as_codestpro1.'</b>'),
		               array('name'=>'<b>PERIODO PRESUPUESTARIO:    </b>'.'<b> Trimestre '.$as_trimestre.' - '.$ai_ano.'</b>'));
		$la_columna=array('name'=>'','name'=>'','name'=>'','name'=>'','name'=>'','name'=>'');
		$la_config =array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 8,       // Tamaño de Letras
						 'titleFontSize' => 8, // Tamaño de Letras de los títulos
						 'showLines'=>0,        // Mostrar Líneas
						 'shaded'=>0,           // Sombra entre líneas
						 'xPos'=>465,//65
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900);
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_titulo($io_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_titulo
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 26/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSetDy(-100); // para  el rectangulo 
		$la_data=array(array('ramo'=>'',
		                     'subramo'=>'',
		                     'especifica'=>'',  
		                     'subespecifica'=>'',
							 'denominacion'=>'',
							 'previsto'=>'',
							 'modificado'=>'',
							 'programado'=>'',
							 'devengado'=>'',
							 'liquidado'=>'',
							 'recaudado'=>'',
							 'programado_acum'=>'',
							 'devengado_acum'=>'',
							 'liquidado_acum'=>'',
							 'recaudado_acum'=>'',
							 'ingresos_recibir'=>''));
							 
		$la_columna=array(   'ramo'=>'',
		                     'subramo'=>'',
		                     'especifica'=>'',  
		                     'subespecifica'=>'',
							 'denominacion'=>'',
							 'previsto'=>'',
							 'modificado'=>'',
							 'programado'=>'',
							 'devengado'=>'',
							 'liquidado'=>'',
							 'recaudado'=>'',
							 'programado_acum'=>'',
							 'devengado_acum'=>'',
							 'liquidado_acum'=>'',
							 'recaudado_acum'=>'',
							 'ingresos_recibir'=>'');
		$la_config =array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 7,       // Tamaño de Letras
						 'titleFontSize' => 7, // Tamaño de Letras de los títulos
						 'showLines'=>1,        // Mostrar Líneas
						 'shaded'=>0,           // Sombra entre líneas
						 'xPos'=>504,
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990,
						 'colGap'=>0,
						 'cols'=>array('ramo'=>         array('justification'=>'center','width'=>15),
		                     		   'subramo'=>        array('justification'=>'center','width'=>15),
		                               'especifica'=>      array('justification'=>'center','width'=>15),  
		                               'subespecifica'=>   array('justification'=>'center','width'=>15),
							           'denominacion'=>    array('justification'=>'center','width'=>130),
							           'previsto'=>        array('justification'=>'center','width'=>80),
							           'modificado'=>      array('justification'=>'center','width'=>70),
							           'programado'=>      array('justification'=>'center','width'=>70),
							           'devengado'=>      array('justification'=>'center','width'=>70),
							           'liquidado'=>         array('justification'=>'center','width'=>70),
							           'recaudado'=>          array('justification'=>'center','width'=>70),
							           'programado_acum'=> array('justification'=>'center','width'=>70),
							           'devengado_acum'=> array('justification'=>'center','width'=>70),
							           'liquidado_acum'=>    array('justification'=>'center','width'=>70),
							           'recaudado_acum'=>     array('justification'=>'center','width'=>80),
							           'ingresos_recibir'=>      array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_titulo,'all');

	}// end function uf_print_titulo
	//--------------------------------------------------------------------------------------------------------------------------------	//--------------------------------------------------------------------------------------------------------------------------------
	
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 26/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('ramo'=>array('justification'=>'center','width'=>15),
						 
						               'subramo'=>array('justification'=>'center','width'=>15),
									   
									   'especifica'=>array('justification'=>'center','width'=>15),
									   
									   'subesp'=>array('justification'=>'center','width'=>15), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>130), // Justificación y ancho de la columna
						 			   'previsto'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'modificado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'programado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'devengado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'liquidado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'recaudado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'programado_acum'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'devengado_acum'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									  'liquidado_acum'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'recaudado_acum'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'ingresos_recibir'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$la_columnas=array('ramo'=>'',
						   'subramo'=>'',
		                   'especifica'=>'',
		                   'subesp'=>'',
				           'denominacion'=>'',
						   'previsto'=>'',
						   'modificado'=>'',
						   'programado'=>'',
						   'devengado'=>'',
						   'liquidado'=>'',
						   'recaudado'=>'',
						   'programado_acum'=>'',
						   'devengado_acum'=>'',
						   'liquidado_acum'=>'',
						   'recaudado_acum'=>'',
						   'ingresos_recibir'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($la_data_tot,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private 
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing. Arnaldo USárez
		// Fecha Creación: 10/06/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('totales'=>array('justification'=>'center','width'=>190), // Justificación y ancho de la columna
						 			   'previsto'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'modificado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'programado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'devengado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'liquidado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'recaudado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'programado_acum'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'devengado_acum'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									  'liquidado_acum'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'recaudado_acum'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'ingresos_recibir'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$la_columnas=array('totales'=>'',
						   'previsto'=>'',
						   'modificado'=>'',
						   'programado'=>'',
						   'devengado'=>'',
						   'liquidado'=>'',
						   'recaudado'=>'',
						   'programado_acum'=>'',
						   'devengado_acum'=>'',
						   'liquidado_acum'=>'',
						   'recaudado_acum'=>'',
						   'ingresos_recibir'=>'');
		$io_pdf->ezTable($la_data_tot,$la_columnas,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();	
		require_once("sigesp_spi_funciones_reportes.php");
		$io_function_report=new sigesp_spi_funciones_reportes();	
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
		require_once("sigesp_spi_reporte.php");
		$io_spirep = new sigesp_spi_reporte();
//-----------------------------------------------------------------------------------------------------------------------------
		global $la_data_tot;
		require_once("sigesp_spi_class_reportes_instructivos.php");
		$io_report = new sigesp_spi_class_reportes_instructivos();
		
		$li_estpreing = $_SESSION["la_empresa"]["estpreing"];
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		$ls_cmbmes=$_GET["cmbmes"];
		if ($li_estpreing==1)
		   {
		     $ls_codestpro1 = $_GET["codestpro1"];
			 $ls_codestpro2 = $_GET["codestpro2"];
			 $ls_codestpro3 = $_GET["codestpro3"];
			 $ls_codestpro4 = $_GET["codestpro4"];
			 $ls_codestpro5 = $_GET["codestpro5"];
			 $ls_codestpro1h = $_GET["codestpro1h"];
			 $ls_codestpro2h = $_GET["codestpro2h"];
			 $ls_codestpro3h = $_GET["codestpro3h"];
			 $ls_codestpro4h = $_GET["codestpro4h"];
			 $ls_codestpro5h = $_GET["codestpro5h"];
			 $ls_estclades   = $_GET["estclades"];
			 $ls_estclahas   = $_GET["estclahas"];
		   }
		else
		   {
		     $ls_codestpro1 = "";
			 $ls_codestpro2 = "";
			 $ls_codestpro3 = "";
			 $ls_codestpro4 = "";
			 $ls_codestpro5 = "";
			 $ls_codestpro1h = "";
			 $ls_codestpro2h = "";
			 $ls_codestpro3h = "";
			 $ls_codestpro4h = "";
			 $ls_codestpro5h = "";
			 $ls_estclades   = "";
			 $ls_estclahas   = "";
		   }
		switch($ls_cmbmes)
		{
		 case '0103': $ls_trimestre = "01";
		 break;
		 
		 case '0406': $ls_trimestre = "02";
		 break;
		 
		 case '0709': $ls_trimestre = "03";
		 break;
		 
		 case '1012': $ls_trimestre = "04";
		 break;
		
		}
		$li_mesdes=substr($ls_cmbmes,0,2);
		$ldt_fecdes=$li_ano."-".$li_mesdes."-01";
		$li_meshas=substr($ls_cmbmes,2,2);
		$ldt_ult_dia=$io_fecha->uf_last_day($li_meshas,$li_ano);
		$fechas=$ldt_ult_dia;
		$ldt_fechas=$io_funciones->uf_convertirdatetobd($fechas);
		$ls_mesdes=$io_fecha->uf_load_nombre_mes($li_mesdes);
		$ls_meshas=$io_fecha->uf_load_nombre_mes($li_meshas);
	
		
//----------------------------------------------------  Parámetros del encabezado  ---------------------------------------------
		$ls_titulo=" <b>EJECUCION TRIMESTRAL DE INGRESOS Y FUENTES FINANCIERAS</b>";       
//--------------------------------------------------------------------------------------------------------------------------------
   
     $lb_valido=$io_report->uf_spi_reportes_ejecucion_trimestral($ldt_fecdes,$ldt_fechas,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
	 															 $ls_codestpro4,$ls_codestpro5,$ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,
																 $ls_codestpro4h,$ls_codestpro5h,$ls_estclades,$ls_estclahas);
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
		uf_print_encabezado_pagina($ls_titulo,'(En Bolivares Fuertes)',$ls_trimestre,$io_pdf); // Imprimimos el encabezado de la página
 	    $io_pdf->ezStartPageNumbers(980,40,10,'','',1); // Insertar el número de página
		$io_pdf->transaction('start'); // Iniciamos la transacción
		$thisPageNum=$io_pdf->ezPageCount;
		$li_tot=$io_report->dts_reporte->getRowCount("spi_cuenta");
	    $ld_total_previsto = $li_i = 0;
		$ld_total_modificado=0;
		$ld_total_programado=0;
		$ld_total_devengado=0;
		$ld_total_liquidado=0;
		$ld_total_recaudado=0;
		$ld_total_programado_acum=0;
		$ld_total_devengado_acum=0;
		$ld_total_liquidado_acum=0;
		$ld_total_recaudado_acum=0;
		$ld_total_ingresos_recibir=0;
		
		$ld_montotpre = 0;
		$ld_montotmod = 0;
	    $ld_montotpro = 0;
	    $ld_montotdev = 0;
	    $ld_montotliq = 0;
	    $ld_montotrec = 0;
	    $ld_montotpac = 0;
	    $ld_montotdac = 0;
	    $ld_montotlac = 0;
	    $ld_montotrac = 0;
	    $ld_montotire = 0;
				   		
		$thisPageNum=$io_pdf->ezPageCount;
		$io_encabezado=$io_pdf->openObject();
		if ($ls_codestpro1=="")
		   {
		     $ls_denestpro1 = " TODAS";
		   }
		else
		   {
			 $io_spirep->uf_spg_reporte_select_denestpro1(str_pad($ls_codestpro1,25,0,0),$ls_denestpro1,$ls_estclades);
		   }		
		uf_print_titulo_reporte($io_encabezado,"",$li_ano,$ls_mesdes,$ls_codestpro1,$ls_denestpro1,$ls_trimestre,$io_pdf);
		$io_pdf->ezSetCmMargins(8.0125,3,3,3);
		$ls_partida_aux="";
		for ($z=1;$z<=$li_tot;$z++)
		    {		
			  $ld_previsto=0;
			  $ld_modificado=0;
			  $ld_programado=0;
			  $ld_devengado=0;
			  $ld_liquidado=0;
			  $ld_recaudado=0;
			  $ld_programado_acum=0;
			  $ld_devengado_acum=0;
			  $ld_liquidado_acum=0;
			  $ld_recaudado_acum=0;
			  $ld_ingresos_recibir=0;
			  $ls_ramo="";
			  $ls_subramo="";
			  $ls_especifica="";
			  $ls_subesp="";
			  $ls_status="";

			  $ls_spi_cuenta       = trim($io_report->dts_reporte->data["spi_cuenta"][$z]);
			  $io_function_report->uf_get_spi_cuenta($ls_spi_cuenta,$ls_ramo,$ls_subramo,$ls_especifica,$ls_subesp);
			  $ls_denominacion     = trim($io_report->dts_reporte->data["denominacion"][$z]);
			  $ld_previsto         = $io_report->dts_reporte->data["previsto"][$z];
			  $ld_modificado       = $io_report->dts_reporte->data["modificado"][$z];
			  $ld_programado       = $io_report->dts_reporte->data["programado"][$z].'<br>';
			  $ld_devengado        = $io_report->dts_reporte->data["devengado"][$z];
			  $ld_liquidado        = $io_report->dts_reporte->data["liquidado"][$z];
			  $ld_recaudado        = $io_report->dts_reporte->data["recaudado"][$z];
			  $ld_programado_acum  = $io_report->dts_reporte->data["programado_acum"][$z];
			  $ld_devengado_acum   = $io_report->dts_reporte->data["devengado_acum"][$z];
			  $ld_liquidado_acum   = $io_report->dts_reporte->data["liquidado_acum"][$z];
			  $ld_recaudado_acum   = $io_report->dts_reporte->data["recaudado_acum"][$z];
			  $ld_ingresos_recibir = $io_report->dts_reporte->data["ingresos_recibir"][$z];
			  $ls_status           = $io_report->dts_reporte->data["status"][$z];
			  if ($ls_status=="C")
			     {
				   $ld_montotpre += $ld_previsto;
				   $ld_montotmod += $ld_modificado;
				   $ld_montotpro += $ld_programado;
				   $ld_montotdev += $ld_devengado;				   
				   $ld_montotliq += $ld_liquidado;
				   $ld_montotrec += $ld_recaudado;
				   $ld_montotpac += $ld_programado_acum;
				   $ld_montotdac += $ld_devengado_acum;
				   $ld_montotlac += $ld_liquidado_acum;
				   $ld_montotrac += $ld_recaudado_acum;
				   $ld_montotire += $ld_ingresos_recibir;
				 }			  
			  if ($ls_partida_aux=="")
				 {
				   $ls_partida_aux=$ls_ramo;
				 }
			  elseif($ls_partida_aux==$ls_ramo)
				 {
				   if ($ls_status=="C")
				      {
					    $ld_total_previsto         += $ld_previsto;
					    $ld_total_modificado       += $ld_modificado;
					    $ld_total_programado       += $ld_programado;
					    $ld_total_devengado        += $ld_devengado;
					    $ld_total_liquidado        += $ld_liquidado;
					    $ld_total_recaudado        += $ld_recaudado;
					    $ld_total_programado_acum  += $ld_programado_acum;
					    $ld_total_devengado_acum   += $ld_devengado_acum;
					    $ld_total_liquidado_acum   += $ld_liquidado_acum;
					    $ld_total_recaudado_acum   += $ld_recaudado_acum;
					    $ld_total_ingresos_recibir += $ld_ingresos_recibir;
				      }  
				 }
			  else
			     {
				   $la_data_tot[1]=array('totales'=>"TOTALES ".$ls_partida_aux,
								         'previsto'=>number_format($ld_total_previsto,2,",","."),
								         'modificado'=>number_format($ld_total_modificado,2,",","."),
										 'programado'=>number_format($ld_total_programado,2,",","."),
										 'devengado'=>number_format($ld_total_devengado,2,",","."),
										 'liquidado'=>number_format($ld_total_liquidado,2,",","."),
										 'recaudado'=>number_format($ld_total_recaudado,2,",","."),
										 'programado_acum'=>number_format($ld_total_programado_acum,2,",","."),
										 'devengado_acum'=>number_format($ld_total_devengado_acum,2,",","."),
										 'liquidado_acum'=>number_format($ld_total_liquidado_acum,2,",","."),
										 'recaudado_acum'=>number_format($ld_total_recaudado_acum,2,",","."),
										 'ingresos_recibir'=>number_format($ld_total_ingresos_recibir,2,",","."));
 
				   uf_print_detalle($la_data,$io_pdf);
				   uf_print_pie_cabecera($la_data_tot,$io_pdf);
				   unset($la_data,$la_data_tot);
				   $li_i = 0;
                   $ld_total_previsto=$ld_total_modificado=$ld_total_programado=$ld_total_devengado=0;
				   $ld_total_liquidado=$ld_total_recaudado=$ld_total_programado_acum=0;
				   $ld_total_devengado_acum=$ld_total_liquidado_acum=$ld_total_recaudado_acum=$ld_total_ingresos_recibir=0;				   $ls_partida_aux		= $ls_ramo;
				   $io_pdf->ezNewPage();
				 }					  							 						   
			  $ld_previsto         = number_format($ld_previsto,2,",",".");
			  $ld_modificado       = number_format($ld_modificado,2,",",".");
			  $ld_programado       = number_format($ld_programado,2,",",".");
			  $ld_devengado        = number_format($ld_devengado,2,",",".");
			  $ld_liquidado        = number_format($ld_liquidado,2,",",".");
			  $ld_recaudado        = number_format($ld_recaudado,2,",",".");
			  $ld_programado_acum  = number_format($ld_programado_acum,2,",",".");
			  $ld_devengado_acum   = number_format($ld_devengado_acum,2,",",".");
			  $ld_liquidado_acum   = number_format($ld_liquidado_acum,2,",",".");
			  $ld_recaudado_acum   = number_format($ld_recaudado_acum,2,",",".");
			  $ld_ingresos_recibir = number_format($ld_ingresos_recibir,2,",",".");
				   
			  $li_i++;
			  $la_data[$li_i]=array('ramo'=>$ls_ramo,
			                        'subramo'=>$ls_subramo,
									'especifica'=>$ls_especifica,
				                    'subesp'=>$ls_subesp,
									'denominacion'=>$ls_denominacion,
									'previsto'=>$ld_previsto,
									'modificado'=>$ld_modificado,
									'programado'=>$ld_programado,
									'devengado'=>$ld_devengado,
									'liquidado'=>$ld_liquidado,
									'recaudado'=>$ld_recaudado,
									'programado_acum'=>$ld_programado_acum,
									'devengado_acum'=>$ld_devengado_acum,
									'liquidado_acum'=>$ld_liquidado_acum,
									'recaudado_acum'=>$ld_recaudado_acum,
									'ingresos_recibir'=>$ld_ingresos_recibir);
			
			  if ($z==$li_tot)
			     {
				   if (isset($la_data_tot))
				      {
					    unset($la_data_tot);
					  }
				   $la_data_tot[1]=array('totales'=>"TOTALES ".$ls_partida_aux,
								         'previsto'=>number_format($ld_total_previsto,2,",","."),
								         'modificado'=>number_format($ld_total_modificado,2,",","."),
										 'programado'=>number_format($ld_total_programado,2,",","."),
										 'devengado'=>number_format($ld_total_devengado,2,",","."),
										 'liquidado'=>number_format($ld_total_liquidado,2,",","."),
										 'recaudado'=>number_format($ld_total_recaudado,2,",","."),
										 'programado_acum'=>number_format($ld_total_programado_acum,2,",","."),
										 'devengado_acum'=>number_format($ld_total_devengado_acum,2,",","."),
										 'liquidado_acum'=>number_format($ld_total_liquidado_acum,2,",","."),
										 'recaudado_acum'=>number_format($ld_total_recaudado_acum,2,",","."),
										 'ingresos_recibir'=>number_format($ld_total_ingresos_recibir,2,",","."));				   
				   uf_print_detalle($la_data,$io_pdf);
				   uf_print_pie_cabecera($la_data_tot,$io_pdf);
				   //Impresión del Total General.
				   unset($la_data_tot);
				   $la_data_tot[1]=array('totales'=>"TOTAL GENERAL ",
										 'previsto'=>number_format($ld_montotpre,2,",","."),
										 'modificado'=>number_format($ld_montotmod,2,",","."),
										 'programado'=>number_format($ld_montotpro,2,",","."),
										 'devengado'=>number_format($ld_montotdev,2,",","."),
										 'liquidado'=>number_format($ld_montotliq,2,",","."),
										 'recaudado'=>number_format($ld_montotrec,2,",","."),
										 'programado_acum'=>number_format($ld_montotpac,2,",","."),
										 'devengado_acum'=>number_format($ld_montotdac,2,",","."),
										 'liquidado_acum'=>number_format($ld_montotlac,2,",","."),
										 'recaudado_acum'=>number_format($ld_montotrac,2,",","."),
										 'ingresos_recibir'=>number_format($ld_montotire,2,",","."));
				   uf_print_pie_cabecera($la_data_tot,$io_pdf);
				   unset($la_data);
				 }
			}//for			
			unset($la_data,$la_data_tot);
	 	    $io_pdf->ezStopPageNumbers(1,1);
		    if (isset($d) && $d)
		       {
			     $ls_pdfcode = $io_pdf->ezOutput(1);
			     $ls_pdfcode = str_replace("\n","\n<br>",htmlspecialchars($ls_pdfcode));
			     echo '<html><body>';
			     echo trim($ls_pdfcode);
			     echo '</body></html>';
	 	       }
		    else			
			   {
			     $io_pdf->ezStream();
			   }
		unset($io_pdf);
	}//else
	unset($io_report,$io_funciones);
?> 