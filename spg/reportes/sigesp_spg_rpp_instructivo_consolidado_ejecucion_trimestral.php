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
							 'compromiso'=>'<b>COMPROMISO</b>',
							 'causado'=>'<b>CAUSADO</b>',
							 'pagado'=>'<b>PAGADO</b>',
							 'programado_acumulado'=>'<b>PROGRAMADO ACUMULADO</b>',
							 'compromiso_acumulado'=>'<b>COMPROMISO ACUMULADO</b>',
							 'causado_acumulado'=>'<b>CAUSADO ACUMULADO</b>',
							 'pagado_acumulado'=>'<b>PAGADO ACUMULADO</b>',
							 'disponibilidad'=>'<b>DISPONIBILIDAD</b>'));
		$la_columna=array('cuenta'=>'','denominacion'=>'','presupuesto'=>'',
		                  'presupuesto_modificado'=>'','programado'=>'','compromiso'=>'',
						  'causado'=>'','pagado'=>'','programado_acumulado'=>'','compromiso_acumulado'=>'',
						  'causado_acumulado'=>'','pagado_acumulado'=>'',
						  'disponibilidad'=>'');
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
									   'compromiso'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'causado'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'pagado'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'programado_acumulado'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'compromiso_acumulado'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'causado_acumulado'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'pagado_acumulado'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'disponibilidad'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna
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
						 			   'denominacion'=>array('justification'=>'left','width'=>130), // Justificación y ancho de la columna
						 			   'presupuesto'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'presupuesto_modificado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'programado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'compromiso'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'causado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'pagado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'programado_acumulado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'compromiso_acumulado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'causado_acumulado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'pagado_acumulado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'disponibilidad'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$la_columnas=array('cuenta'=>'',
						   'denominacion'=>'',
						   'presupuesto'=>'',
						   'presupuesto_modificado'=>'',
						   'programado'=>'',
						   'compromiso'=>'',
						   'causado'=>'',
						   'pagado'=>'',
						   'programado_acumulado'=>'',
						   'compromiso_acumulado'=>'',
						   'causado_acumulado'=>'',
						   'pagado_acumulado'=>'',
						   'disponibilidad'=>'');
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
									   'compromiso'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'causado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'pagado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'programado_acumulado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'compromiso_acumulado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'causado_acumulado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'pagado_acumulado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'disponibilidad'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		
		$la_columnas=array('total'=>'',
		                   'presupuesto'=>'',
						   'presupuesto_modificado'=>'',
						   'programado'=>'',
						   'compromiso'=>'',
						   'causado'=>'',
						   'pagado'=>'',
						   'programado_acumulado'=>'',
						   'compromiso_acumulado'=>'',
						   'causado_acumulado'=>'',
						   'pagado_acumulado'=>'',
						   'disponibilidad'=>'');
		$io_pdf->ezTable($la_data_tot,$la_columnas,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();	
		require_once("sigesp_spg_funciones_reportes.php");
		$io_function_report=new sigesp_spg_funciones_reportes();	
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
		require_once("sigesp_spg_class_reportes_instructivos.php");
		$io_report = new sigesp_spg_class_reportes_instructivos();
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
			$ls_titulo="<b>CONSOLIDADO DE EJECUCIÓN FINANCIERA TRIMESTRAL DE PROYECTOS Y ACCIONES CENTRALIZADAS POR PARTIDAS</b>";       
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
     $lb_valido=$io_report->uf_spg_reporte_consolidado_de_ejecucion_trimestral($ldt_fecdes,$ldt_fechas,$ls_mesdes,$ls_meshas);
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
		$li_total=$io_report->dts_reporte->getRowCount("spg_cuenta");
	    $ld_asignado_total=0;
	    $ld_asignado_modificado_total=0;
	    $ld_programado_trimestral_total=0;
	    $ld_comprometer_total=0;
	    $ld_causado_total=0;
	    $ld_pagado_total=0;
	    $ld_programado_acumulado_total=0;
	    $ld_comprometer_acumulado_total=0;
	    $ld_causado_acumulado_total=0;
	    $ld_pagado_acumulado_total=0;
	    $ld_disponibilidad_total=0;
		for($z=1;$z<=$li_total;$z++)
	    {
			  $thisPageNum=$io_pdf->ezPageCount;
			  $ls_spg_cuenta=substr(trim($io_report->dts_reporte->data["spg_cuenta"][$z]),0,3);
			  $ls_denominacion=trim($io_report->dts_reporte->data["denominacion"][$z]);
			  $ld_asignado=$io_report->dts_reporte->data["asignado"][$z];
			  $ld_asignado_modificado=$io_report->dts_reporte->data["asignado_modificado"][$z];
			  $ld_programado_trimestral=$io_report->dts_reporte->data["programado"][$z];
			  $ld_comprometer=$io_report->dts_reporte->data["compromiso"][$z];
			  $ld_causado=$io_report->dts_reporte->data["causado"][$z];
			  $ld_pagado=$io_report->dts_reporte->data["pagado"][$z];
			  $ld_programado_acumulado=$io_report->dts_reporte->data["programado_acumulado"][$z];
			  $ld_comprometer_acumulado=$io_report->dts_reporte->data["compromiso_acumulado"][$z];
			  $ld_causado_acumulado=$io_report->dts_reporte->data["causado_acumulado"][$z];
			  $ld_pagado_acumulado=$io_report->dts_reporte->data["pagado_acumulado"][$z];
			  $ld_disponibilidad=$io_report->dts_reporte->data["disponibilidad"][$z];
			  
			  $ld_asignado_total=$ld_asignado_total+$ld_asignado;
			  $ld_asignado_modificado_total=$ld_asignado_modificado_total+$ld_asignado_modificado;
			  $ld_programado_trimestral_total=$ld_programado_trimestral_total+$ld_programado_trimestral;
			  $ld_comprometer_total=$ld_comprometer_total+$ld_comprometer;
			  $ld_causado_total=$ld_causado_total+$ld_causado;
			  $ld_pagado_total=$ld_pagado_total+$ld_pagado;
			  $ld_programado_acumulado_total=$ld_programado_acumulado_total+$ld_programado_acumulado;
			  $ld_comprometer_acumulado_total=$ld_comprometer_acumulado_total+$ld_comprometer_acumulado;
			  $ld_causado_acumulado_total=$ld_causado_acumulado_total+$ld_causado_acumulado;
			  $ld_pagado_acumulado_total=$ld_pagado_acumulado_total+$ld_pagado_acumulado;
			  $ld_disponibilidad_total=$ld_disponibilidad_total+$ld_disponibilidad;
			  
			  $ld_asignado=number_format($ld_asignado,2,",",".");
			  $ld_asignado_modificado=number_format($ld_asignado_modificado,2,",",".");
			  $ld_programado_trimestral=number_format($ld_programado_trimestral,2,",",".");
			  $ld_comprometer=number_format($ld_comprometer,2,",",".");
			  $ld_causado=number_format($ld_causado,2,",",".");
			  $ld_pagado=number_format($ld_pagado,2,",",".");
			  $ld_programado_acumulado=number_format($ld_programado_acumulado,2,",",".");
			  $ld_comprometer_acumulado=number_format($ld_comprometer_acumulado,2,",",".");
			  $ld_causado_acumulado=number_format($ld_causado_acumulado,2,",",".");
			  $ld_pagado_acumulado=number_format($ld_pagado_acumulado,2,",",".");
			  $ld_disponibilidad=number_format($ld_disponibilidad,2,",",".");
			  
			  $la_data[$z]=array('cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,'presupuesto'=>$ld_asignado,
			                     'presupuesto_modificado'=>$ld_asignado_modificado,'programado'=>$ld_programado_trimestral,
								 'compromiso'=>$ld_comprometer,'causado'=>$ld_causado,'pagado'=>$ld_pagado,
								 'programado_acumulado'=>$ld_programado_acumulado,'compromiso_acumulado'=>$ld_comprometer_acumulado,
								 'causado_acumulado'=>$ld_causado_acumulado,'pagado_acumulado'=>$ld_pagado_acumulado,
								 'disponibilidad'=>$ld_disponibilidad);
		}
	    $ld_asignado_total=number_format($ld_asignado_total,2,",",".");
	    $ld_asignado_modificado_total=number_format($ld_asignado_modificado_total,2,",",".");
	    $ld_programado_trimestral_total=number_format($ld_programado_trimestral_total,2,",",".");
	    $ld_comprometer_total=number_format($ld_comprometer_total,2,",",".");
	    $ld_causado_total=number_format($ld_causado_total,2,",",".");
	    $ld_pagado_total=number_format($ld_pagado_total,2,",",".");
	    $ld_programado_acumulado_total=number_format($ld_programado_acumulado_total,2,",",".");
	    $ld_comprometer_acumulado_total=number_format($ld_comprometer_acumulado_total,2,",",".");
	    $ld_causado_acumulado_total=number_format($ld_causado_acumulado_total,2,",",".");
	    $ld_pagado_acumulado_total=number_format($ld_pagado_acumulado_total,2,",",".");
	    $ld_disponibilidad_total=number_format($ld_disponibilidad_total,2,",",".");
	  
	    $la_data_totales[$z]=array('total'=>'<b>TOTALES Bs.</b>',
		                           'presupuesto'=>$ld_asignado_total,
		                           'presupuesto_modificado'=>$ld_asignado_modificado_total,
							       'programado'=>$ld_programado_trimestral_total,
							       'compromiso'=>$ld_comprometer_total,
							       'causado'=>$ld_causado_total,
							       'pagado'=>$ld_pagado_total,
							       'programado_acumulado'=>$ld_programado_acumulado_total,
							       'compromiso_acumulado'=>$ld_comprometer_acumulado_total,
							       'causado_acumulado'=>$ld_causado_acumulado_total,
							       'pagado_acumulado'=>$ld_pagado_acumulado_total,
							       'disponibilidad'=>$ld_disponibilidad_total);
							   
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