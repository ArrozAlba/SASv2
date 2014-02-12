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
	function uf_print_encabezado_pagina($as_titulo,$as_moneda,&$io_pdf)
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
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,30,1000,30);
		$io_pdf->rectangle(10,440,990,150);
		
		$li_tm=$io_pdf->getTextWidth(16,$as_titulo);
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,500,16,$as_titulo); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(16,'<b>'.$as_moneda.'</b>');
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,480,16,'<b>'.$as_moneda.'</b>'); // Agregar el título
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_titulo_reporte($io_encabezado,&$io_pdf)
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
		$io_pdf->ezSetY(590);
		$ls_codemp    = $_SESSION["la_empresa"]["codemp"];
		$ls_nombre    = $_SESSION["la_empresa"]["nombre"];
		$ls_nomorgads = $_SESSION["la_empresa"]["nomorgads"];
		$ls_codasiona = $_SESSION['la_empresa']['codasiona'];
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones = new class_funciones();	
		$ls_periodo   = $io_funciones->uf_convertirfecmostrar(substr($_SESSION['la_empresa']['periodo'],0,10));
		$la_data=array(array('name'=>'<b>CODIGO PRESUPUESTARIO DEL ENTE:     </b>'.'<b>'.$ls_codasiona.'</b>'),
		               array('name'=>'<b>DENOMINACION:    </b>'.'<b>'.$ls_nombre.'</b>'),
					   array('name'=>'<b>ORGANO DE ADSCRIPCION:    </b>'.'<b>'.$ls_nomorgads.'</b>'),
		               array('name'=>'<b>PERIODO PRESUPUESTARIO:    </b>'.'<b>'.$ls_periodo.'</b>'));
		$la_columna=array('name'=>'','name'=>'','name'=>'','name'=>'');
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
		$io_pdf->ezSetDy(-5); // para  el rectangulo
		$io_pdf->ezSetCmMargins(6.5,3,3,3);
		$la_data=array(array('name1'=>'',
		                     'name2'=>'<b>EJECUTADO EN EL TRIMESTRE</b>',
		                     'name3'=>'<b>ACUMULADO AL TRIMESTRE</b>',
		                     'name4'=>''));
		$la_columna=array('name1'=>'','name2'=>'','name3'=>'','name4'=>'');
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
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>410),// Justificación y ancho de la columna
						               'name2'=>array('justification'=>'center','width'=>210),// Justificación y ancho de la columna
						               'name3'=>array('justification'=>'center','width'=>280),// Justificación y ancho de la columna
									   'name4'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_titulo,'all');
	}// end function uf_print_titulo
	//--------------------------------------------------------------------------------------------------------------------------------	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($io_cabecera,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 26/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$la_data=array(array('cuenta'=>'<b>PARTIDA</b>',
		                     'denominacion'=>'<b>DENOMINACION</b>',
							 'presupuesto'=>'<b>PRESUPUESTO APROBADO</b>',
		                     'presupuesto_modificado'=>'<b>PRESUPUESTO MODIFICADO</b>',
							 'programado'=>'<b>PROGRAMADO EN EL TRIMESTRE</b>',
							 'devengado'=>'<b>DEVENGADO</b>',
							 'liquidado'=>'<b>LIQUIDADO</b>',
							 'recaudado'=>'<b>RECAUDADO</b>',
							 'programado_acumulado'=>'<b>PROGRAMADO</b>',
							 'devengado_acumulado'=>'<b>DEVENGADO</b>',
							 'liquidado_acumulado'=>'<b>LIQUIDADO</b>',
							 'recaudado_acumulado'=>'<b>RECAUDADO</b>',
							 'ingresosxrecibir'=>'<b>INGRESOS POR RECIBIR</b>'));
		$la_columna=array('cuenta'=>'','denominacion'=>'','presupuesto'=>'',
		                  'presupuesto_modificado'=>'','programado'=>'','devengado'=>'',
						  'liquidado'=>'','recaudado'=>'','programado_acumulado'=>'','devengado_acumulado'=>'',
						  'liquidado_acumulado'=>'','recaudado_acumulado'=>'',
						  'ingresosxrecibir'=>'');
		$la_config=array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 7,       // Tamaño de Letras
						 'titleFontSize' => 7, // Tamaño de Letras de los títulos
						 'showLines'=>2,        // Mostrar Líneas
						 'shaded'=>0,           // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990,
						 'colGap'=>0,
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'center','width'=>130), // Justificación y ancho de la columna
						 			   'presupuesto'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'presupuesto_modificado'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'programado'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'devengado'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'liquidado'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'recaudado'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'programado_acumulado'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'devengado_acumulado'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'liquidado_acumulado'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'recaudado_acumulado'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'ingresosxrecibir'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_cabecera,'all');
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
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
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'center','width'=>130), // Justificación y ancho de la columna
						 			   'presupuesto'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'presupuesto_modificado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'programado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'devengado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'liquidado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'recaudado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'programado_acumulado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'devengado_acumulado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'liquidado_acumulado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'recaudado_acumulado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'ingresosxrecibir'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$la_columnas=array('cuenta'=>'',
						   'denominacion'=>'',
						   'presupuesto'=>'',
						   'presupuesto_modificado'=>'',
						   'programado'=>'',
						   'devengado'=>'',
						   'liquidado'=>'',
						   'recaudado'=>'',
						   'programado_acumulado'=>'',
						   'devengado_acumulado'=>'',
						   'liquidado_acumulado'=>'',
						   'recaudado_acumulado'=>'',
						   'ingresosxrecibir'=>'');
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
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 26/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>190),// Justificación y ancho de la columna
						 			   'presupuesto'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'presupuesto_modificado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'programado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'devengado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'liquidado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'recaudado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'programado_acumulado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'devengado_acumulado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'liquidado_acumulado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'recaudado_acumulado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'ingresosxrecibir'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$la_columnas=array('total'=>'',
						   'presupuesto'=>'',
						   'presupuesto_modificado'=>'',
						   'programado'=>'',
						   'devengado'=>'',
						   'liquidado'=>'',
						   'recaudado'=>'',
						   'programado_acumulado'=>'',
						   'devengado_acumulado'=>'',
						   'liquidado_acumulado'=>'',
						   'recaudado_acumulado'=>'',
						   'ingresosxrecibir'=>'');
		$io_pdf->ezTable($la_data_tot,$la_columnas,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones = new class_funciones();	
		require_once("sigesp_spi_funciones_reportes.php");
		$io_function_report = new sigesp_spi_funciones_reportes();	
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
		require_once("sigesp_spi_class_reportes_instructivos.php");
		$io_report = new sigesp_spi_class_reportes_instructivos();
	//-----------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];

		$ls_trimestre=$_GET["trimestre"];
		$li_mesdes=substr($ls_trimestre,0,2);
		$ldt_fecdes=$li_ano."-".$li_mesdes."-01";
		$li_meshas=substr($ls_trimestre,2,2);
		$ldt_ult_dia=$io_fecha->uf_last_day($li_meshas,$li_ano);
		$fechas=$ldt_ult_dia;
		$ldt_fechas=$io_funciones->uf_convertirdatetobd($fechas);
		$ls_mesdes=$io_fecha->uf_load_nombre_mes($li_mesdes);
		$ls_meshas=$io_fecha->uf_load_nombre_mes($li_meshas);
	//----------------------------------------------------  Parámetros del encabezado  ---------------------------------------------
		$ls_titulo="<b>CONSOLIDADO DE EJECUCIÓN TRIMESTRAL DE INGRESOS FINANCIEROS</b>";       
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
     $lb_valido=$io_report->uf_spi_reporte_consolidado_de_ejecucion_trimestral($ldt_fecdes,$ldt_fechas,$ls_mesdes,$ls_meshas);
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
		uf_print_encabezado_pagina($ls_titulo,'(Bolivares)',$io_pdf); // Imprimimos el encabezado de la página
 	    $io_pdf->ezStartPageNumbers(980,40,10,'','',1); // Insertar el número de página
		$li_total=$io_report->dts_reporte->getRowCount("spi_cuenta");
	    $ld_previsto_total=0;
	    $ld_previsto_modificado_total=0;
	    $ld_programado_trimestral_total=0;
	    $ld_cobrado_anticipado_total=0;
	    $ld_cobrado_total=0;
	    $ld_devengado_total=0;
	    $ld_programado_acumulado_total=0;
	    $ld_cobrado_anticipado_acumulado_total=0;
	    $ld_cobrado_acumulado_total=0;
	    $ld_devengado_acumulado_total=0;
	    $ld_ingresosxrecibir_total=0;
		for($z=1;$z<=$li_total;$z++)
	    {
			  $thisPageNum=$io_pdf->ezPageCount;
			  $ls_spi_cuenta=substr(trim($io_report->dts_reporte->data["spi_cuenta"][$z]),0,3);
			  $ls_denominacion=trim($io_report->dts_reporte->data["denominacion"][$z]);
			  $ld_previsto=$io_report->dts_reporte->data["previsto"][$z];
			  $ld_previsto_modificado=$io_report->dts_reporte->data["previsto_modificado"][$z];
			  $ld_programado_trimestral=$io_report->dts_reporte->data["programado"][$z];
			  //$ld_cobrado_anticipado=$io_report->dts_reporte->data["cobrado_anticipado"][$z];
			  $ld_cobrado_anticipado=0;
			  $ld_cobrado=$io_report->dts_reporte->data["cobrado"][$z];
			  $ld_devengado=$io_report->dts_reporte->data["devengado"][$z];
			  $ld_programado_acumulado=$io_report->dts_reporte->data["programado_acumulado"][$z];
			  $ld_cobrado_anticipado_acumulado=$io_report->dts_reporte->data["cobrado_anticipado_acumulado"][$z];
			  $ld_cobrado_acumulado=$io_report->dts_reporte->data["cobrado_acumulado"][$z];
			  $ld_devengado_acumulado=$io_report->dts_reporte->data["devengado_acumulado"][$z];
			  $ld_ingresosxrecibir=$io_report->dts_reporte->data["ingresosxrecibir"][$z];
			  
			  $ld_previsto_total=$ld_previsto_total+$ld_previsto;
			  $ld_previsto_modificado_total=$ld_previsto_modificado_total+$ld_previsto_modificado;
			  $ld_programado_trimestral_total=$ld_programado_trimestral_total+$ld_programado_trimestral;
			  $ld_cobrado_anticipado_total=$ld_cobrado_anticipado_total+$ld_cobrado_anticipado;
			  $ld_cobrado_total=$ld_cobrado_total+$ld_cobrado;
			  $ld_devengado_total=$ld_devengado_total+$ld_devengado;
			  $ld_programado_acumulado_total=$ld_programado_acumulado_total+$ld_programado_acumulado;
			  $ld_cobrado_anticipado_acumulado_total=$ld_cobrado_anticipado_acumulado_total+$ld_cobrado_anticipado_acumulado;
			  $ld_cobrado_acumulado_total=$ld_cobrado_acumulado_total+$ld_cobrado_acumulado;
			  $ld_devengado_acumulado_total=$ld_devengado_acumulado_total+$ld_devengado_acumulado;
			  $ld_ingresosxrecibir_total=$ld_ingresosxrecibir_total+$ld_ingresosxrecibir;
			  
			  $ld_previsto=number_format($ld_previsto,2,",",".");
			  $ld_previsto_modificado=number_format($ld_previsto_modificado,2,",",".");
			  $ld_programado_trimestral=number_format($ld_programado_trimestral,2,",",".");
			  $ld_cobrado_anticipado=number_format($ld_cobrado_anticipado,2,",",".");
			  $ld_cobrado=number_format($ld_cobrado,2,",",".");
			  $ld_devengado=number_format($ld_devengado,2,",",".");
			  $ld_programado_acumulado=number_format($ld_programado_acumulado,2,",",".");
			  $ld_cobrado_anticipado_acumulado=number_format($ld_cobrado_anticipado_acumulado,2,",",".");
			  $ld_cobrado_acumulado=number_format($ld_cobrado_acumulado,2,",",".");
			  $ld_devengado_acumulado=number_format($ld_devengado_acumulado,2,",",".");
			  $ld_ingresosxrecibir=number_format($ld_ingresosxrecibir,2,",",".");
			  
			  $la_data[$z]=array('cuenta'=>$ls_spi_cuenta,
			                     'denominacion'=>$ls_denominacion,
			                     'presupuesto'=>$ld_previsto,
			                     'presupuesto_modificado'=>$ld_previsto_modificado,
								 'programado'=>$ld_programado_trimestral,
								 'devengado'=>$ld_devengado,
								 'liquidado'=>$ld_cobrado,
								 'recaudado'=>$ld_cobrado_anticipado,
								 'programado_acumulado'=>$ld_programado_acumulado,
								 'devengado_acumulado'=>$ld_cobrado_anticipado_acumulado,
								 'liquidado_acumulado'=>$ld_cobrado_anticipado_acumulado,
								 'recaudado_acumulado'=>$ld_devengado_acumulado,
								 'ingresosxrecibir'=>$ld_ingresosxrecibir);
		}
		
	    $ld_previsto_total=number_format($ld_previsto_total,2,",",".");
	    $ld_previsto_modificado_total=number_format($ld_previsto_modificado_total,2,",",".");
	    $ld_programado_trimestral_total=number_format($ld_programado_trimestral_total,2,",",".");
	    $ld_devengado_total=number_format($ld_devengado_total,2,",",".");
	    $ld_cobrado_anticipado_total=number_format($ld_cobrado_anticipado_total,2,",",".");
	    $ld_cobrado_total=number_format($ld_cobrado_total,2,",",".");
	    $ld_programado_acumulado_total=number_format($ld_programado_acumulado_total,2,",",".");
	    $ld_cobrado_anticipado_acumulado_total=number_format($ld_cobrado_anticipado_acumulado_total,2,",",".");
	    $ld_cobrado_acumulado_total=number_format($ld_cobrado_acumulado_total,2,",",".");
	    $ld_devengado_acumulado_total=number_format($ld_devengado_acumulado_total,2,",",".");
	    $ld_ingresosxrecibir_total=number_format($ld_ingresosxrecibir_total,2,",",".");
	  
	    $la_data_totales[$z]=array('total'=>'<b>TOTALES Bs.</b>',
		                           'presupuesto'=>$ld_previsto_total,
		                           'presupuesto_modificado'=>$ld_previsto_modificado_total,
							       'programado'=>$ld_programado_trimestral_total,
							       'devengado'=>$ld_devengado_total,
								   'liquidado'=>$ld_cobrado_total,
								   'recaudado'=>$ld_cobrado_anticipado_total,
								   'programado_acumulado'=>$ld_programado_acumulado_total,
								   'devengado_acumulado'=>$ld_cobrado_anticipado_acumulado_total,
								   'liquidado_acumulado'=>$ld_cobrado_anticipado_acumulado_total,
								   'recaudado_acumulado'=>$ld_devengado_acumulado_total,
								   'ingresosxrecibir'=>$ld_ingresosxrecibir_total);
							   
		$io_encabezado=$io_pdf->openObject();
		uf_print_titulo_reporte($io_encabezado,$io_pdf);
		$io_titulo=$io_pdf->openObject();
		uf_print_titulo($io_titulo,$io_pdf);
		$io_cabecera=$io_pdf->openObject();
		uf_print_cabecera($io_cabecera,$io_pdf);
		$io_pdf->ezSetCmMargins(7.6,3,3,3);
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		uf_print_pie_cabecera($la_data_totales,$io_pdf);
		$io_pdf->stopObject($io_encabezado);
		$io_pdf->stopObject($io_titulo);
		$io_pdf->stopObject($io_cabecera);
		unset($la_data);
		unset($la_data_totales);
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
	unset($io_report);
	unset($io_funciones);
?> 