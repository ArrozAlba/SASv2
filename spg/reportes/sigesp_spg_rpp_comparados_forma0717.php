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
	function uf_print_encabezado_pagina($as_titulo,$as_nombre_empresa,$as_bs,&$io_pdf)
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
		// Fecha Creación: 29/08/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,30,1000,30);
		
		$io_pdf->rectangle(10,480,988,110);
		//$io_pdf->addJpegFromFile('../../shared/imagebank/logo.jpg',10,525,80); // Agregar Logo
		$io_pdf->addText(15,580,11,"<b>OFICINA NACIONAL DE PRESUPUESTO (ONAPRE)</b>"); // Agregar la Fecha
		$io_pdf->addText(15,565,11,"<b>OFICINA DE PLANIFICACIÓN DEL SECTOR UNIVERSITARIO (OPSU)</b>"); // Agregar la Fecha
		
		$li_tm=$io_pdf->getTextWidth(12,$as_nombre_empresa);
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,530,12,$as_nombre_empresa); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(12,$as_titulo);
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,515,12,$as_titulo); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(12,$as_bs);
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,500,12,$as_bs); // Agregar el título
		
		$io_pdf->addText(900,550,10,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(900,540,10,date("h:i a")); // Agregar la hora
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_titulo_reporte($ai_ano,$as_meses_trimestre,$as_etiqueta,&$io_pdf)
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
		// Fecha Creación: 29/08/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetY(520);
		if($as_etiqueta=="Mensual")
		{
		   $ls_etiqueta="Mes";
		}
		if($as_etiqueta=="Bi-Mensual")
		{
		   $ls_etiqueta="Bimestre";
		}
		if($as_etiqueta=="Trimestral")
		{
		   $ls_etiqueta="Trimestre";
		}
		if($as_etiqueta=="Semestral")
		{
		   $ls_etiqueta="Semestre";
		}
		$la_data=array(array('name'=>'<b>Presupuesto   </b> '.'<b>'.$ai_ano.'</b>'),
		               array('name'=>'<b>'.$ls_etiqueta.'   </b>'.'<b>'.$as_meses_trimestre.'</b>'));
		$la_columna=array('name'=>'','name'=>'','name'=>'');
		$la_config =array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 9,       // Tamaño de Letras
						 'titleFontSize' => 9, // Tamaño de Letras de los títulos
						 'showLines'=>0,        // Mostrar Líneas
						 'shaded'=>0,           // Sombra entre líneas
						 'xPos'=>265,//65
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>500),
									   'name'=>array('justification'=>'left','width'=>500)));
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_titulo($as_etiqueta,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_titulo
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 29/08/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetY(475);
		if($as_etiqueta=="Mensual")
		{
		   $ls_etiqueta="Mes";
		}
		if($as_etiqueta=="Bi-Mensual")
		{
		   $ls_etiqueta="Bimestre";
		}
		if($as_etiqueta=="Trimestral")
		{
		   $ls_etiqueta="Trimestre";
		}
		if($as_etiqueta=="Semestral")
		{
		   $ls_etiqueta="Semestre";
		}
		$la_data=array(array('name1'=>'<b>PARTIDAS</b>','name2'=>'<b>HASTA EL '.strtoupper($ls_etiqueta).'</b>',
		                     'name3'=>'<b>VARIACION</b>','name4'=>'<b>REVISION PROXIMO '.strtoupper($ls_etiqueta).'</b>'));
		$la_columna=array('name1'=>'','name2'=>'','name3'=>'','name4'=>'');
		$la_config =array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 9,       // Tamaño de Letras
						 'titleFontSize' => 9, // Tamaño de Letras de los títulos
						 'showLines'=>1,        // Mostrar Líneas
						 'shaded'=>0,           // Sombra entre líneas
						 'xPos'=>509,
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900,
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>390),// Justificación y ancho de la columna
						               'name2'=>array('justification'=>'center','width'=>250),// Justificación y ancho de la columna
									   'name3'=>array('justification'=>'center','width'=>220),// Justificación y ancho de la columna
									   'name4'=>array('justification'=>'center','width'=>130))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');

	}// end function uf_print_titulo
	//--------------------------------------------------------------------------------------------------------------------------------	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_etiqueta,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 29/08/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		if($as_etiqueta=="Mensual")
		{
		   $ls_etiqueta="Mes";
		}
		if($as_etiqueta=="Bi-Mensual")
		{
		   $ls_etiqueta="Bimestre";
		}
		if($as_etiqueta=="Trimestral")
		{
		   $ls_etiqueta="Trimestre";
		}
		if($as_etiqueta=="Semestral")
		{
		   $ls_etiqueta="Semestre";
		}
		$la_data=array(array('cuenta'=>'<b>Cuenta</b>','denominacion'=>'<b>Denominación</b>','programado'=>'<b>Programado</b>',
		                     'ejecutado'=>'<b>Ejecutado</b>','absoluta'=>'<b>Absoluta</b>','porcentaje'=>'<b>Porcentaje (%)</b>',
							 'prevision'=>''));
		$la_columna=array('cuenta'=>'','denominacion'=>'','programado'=>'','ejecutado'=>'','absoluta'=>'','porcentaje'=>'',
		                  'prevision'=>'');
		$la_config=array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 9,       // Tamaño de Letras
						 'titleFontSize' => 9, // Tamaño de Letras de los títulos
						 'showLines'=>2,        // Mostrar Líneas
						 'shaded'=>0,           // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990,
						 'colGap'=>0,
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'center','width'=>300), // Justificación y ancho de la columna
						 			   'programado'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
						 			   'ejecutado'=>array('justification'=>'center','width'=>130), // Justificación y ancho de la columna
									   'absoluta'=>array('justification'=>'center','width'=>130), // Justificación y ancho de la columna
									   'porcentaje'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
									   'prevision'=>array('justification'=>'center','width'=>130))); // Justificación y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');
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
		// Fecha Creación: 29/08/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>300), // Justificación y ancho de la columna
						 			   'programado'=>array('justification'=>'right','width'=>120), // Justificación y ancho de la columna
						 			   'ejecutado'=>array('justification'=>'right','width'=>130), // Justificación y ancho de la columna
									   'absoluta'=>array('justification'=>'right','width'=>130), // Justificación y ancho de la columna
									   'porcentaje'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
									   'prevision'=>array('justification'=>'right','width'=>130))); // Justificación y ancho de la columna
		
		$la_columnas=array('cuenta'=>'<b></b>',
						   'denominacion'=>'<b></b>',
						   'programado'=>'<b></b>',
						   'ejecutado'=>'<b></b>',
						   'absoluta'=>'<b></b>',
						   'porcentaje'=>'<b></b>',
						   'prevision'=>'<b></b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("sigesp_spg_reporte_comparado_0717.php");
		$io_report = new sigesp_spg_reporte_comparado_0717();
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();			
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
		$ls_tipoformato=$_GET["tipoformato"];
//-----------------------------------------------------------------------------------------------------------------------------
		global $ls_tipoformato;
        global $la_data;
		global $la_data_tot;
		 if($ls_tipoformato==1)
		 {
			require_once("sigesp_spg_reporte_comparado_0717_bsf.php");
		    $io_report = new sigesp_spg_reporte_comparado_0717_bsf();
		 }
		 else
		 {
			require_once("sigesp_spg_reporte_comparado_0717.php");
		    $io_report = new sigesp_spg_reporte_comparado_0717();
		 }	
		 	
		
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$ls_nombre=$_SESSION["la_empresa"]["nombre"];
		$li_ano=substr($ldt_periodo,0,4);
		
		$ls_etiqueta=$_GET["txtetiqueta"];
		if($ls_etiqueta=="Mensual")
		{
			$ls_combo=$_GET["combo"];
			$ls_combomes=$_GET["combomes"];
			$li_mesdes=substr($ls_combo,0,2);
			$li_meshas=substr($ls_combomes,0,2); 
			$ls_cant_mes=1;
            $ls_meses=$io_report->uf_nombre_mes_desde_hasta($li_mesdes,$li_meshas);
			$ls_combo=$ls_combo.$ls_combomes;
		}
		else
		{
			$ls_combo=$_GET["combo"];
			$li_mesdes=substr($ls_combo,0,2);
			$li_meshas=substr($ls_combo,2,2); 
			if($ls_etiqueta=="Bi-Mensual")
			{
				$ls_cant_mes=2;
				$ls_meses=$io_report->uf_nombre_mes_desde_hasta($li_mesdes,$li_meshas);
			}
			if($ls_etiqueta=="Trimestral")
			{
				$ls_cant_mes=3;
				$ls_meses=$io_report->uf_nombre_mes_desde_hasta($li_mesdes,$li_meshas);
			}
			if($ls_etiqueta=="Semestral")
			{
				$ls_cant_mes=6;
				$ls_meses=$io_report->uf_nombre_mes_desde_hasta($li_mesdes,$li_meshas);
			}
		}
		$ls_mesdes=substr($ls_combo,0,2);
		$ls_meshas=substr($ls_combo,2,4);
		$ls_diades="01";
		$ls_diahas=$io_fecha->uf_last_day($ls_meshas,$li_ano);
		$ldt_fecdes=$ls_diades."/".$ls_mesdes."/".$li_ano;
		$ldt_fechas=$ls_diahas;
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
		$ls_nombre_empresa="<b>".$ls_nombre."</b>";
		$ls_titulo=" <b>PRESUPUESTO DE CAJA (FORMA 0717)</b>";    
		$ls_bs="<b>(En Bolívares)</b>"  ; 
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
     $lb_valido=$io_report->uf_spg_reportes_comparados_presupuesto_de_caja($ldt_fecdes,$ldt_fechas,$ls_etiqueta);
	 if($lb_valido==false) // Existe algún error ó no hay registros
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		//print(" close();");
		print("</script>");
	 }
	 else // Imprimimos el reporte
	 {
	    error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		if($ls_etiqueta=="Mensual")
		{
			$io_pdf->ezSetCmMargins(5.9,3,3,3); // Configuración de los margenes en centímetros
		}
		else
		{
			$io_pdf->ezSetCmMargins(6.2,3,3,3); // Configuración de los margenes en centímetros
		}
		uf_print_encabezado_pagina($ls_titulo,$ls_nombre_empresa,$ls_bs,$io_pdf); // Imprimimos el encabezado de la página
 	    $io_pdf->ezStartPageNumbers(980,40,10,'','',1); // Insertar el número de página
		$li_total=$io_report->dts_reporte_final->getRowCount("spg_cuenta");
		for($z=1;$z<=$li_total;$z++)
		{
			$thisPageNum=$io_pdf->ezPageCount;
			$ls_spg_cuenta=$io_report->dts_reporte_final->data["spg_cuenta"][$z];
			$ls_denominacion=trim($io_report->dts_reporte_final->data["denominacion"][$z]);
			$li_nivel=$io_report->dts_reporte_final->data["nivel"][$z];
			$ls_tipo_cuenta=$io_report->dts_reporte_final->data["tipo_cuenta"][$z];
			$ls_status=$io_report->dts_reporte_final->data["status"][$z];
			$ld_monto_programado=$io_report->dts_reporte_final->data["monto_programado"][$z];
			$ld_monto_ejecutado=$io_report->dts_reporte_final->data["monto_ejecutado"][$z];
			$ld_previsto=$io_report->dts_reporte_final->data["previsto"][$z];
			$ld_tipo=$io_report->dts_reporte_final->data["tipo"][$z];
			$ld_variacion_absoluta=abs($ld_monto_programado-$ld_monto_ejecutado);
			if(($ld_monto_ejecutado==0)or($ld_monto_programado==0))
			{
				$ld_porcentaje_variacion=0;
			}//if
			else
			{
				$ld_producto=$ld_monto_ejecutado*100;
				$ld_porcentaje_variacion=$ld_producto/$ld_monto_programado;
			}//else
			$ld_monto_programado=number_format($ld_monto_programado,2,",",".");
			$ld_monto_ejecutado=number_format($ld_monto_ejecutado,2,",",".");
			$ld_variacion_absoluta=number_format($ld_variacion_absoluta,2,",",".");
			$ld_porcentaje_variacion=number_format($ld_porcentaje_variacion,2,",",".");
			$ld_previsto=number_format($ld_previsto,2,",",".");
			
			$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,'programado'=>$ld_monto_programado,
							   'ejecutado'=>$ld_monto_ejecutado,'absoluta'=>$ld_variacion_absoluta,
							   'porcentaje'=>$ld_porcentaje_variacion,'prevision'=>$ld_previsto);
		}//for
		uf_print_titulo_reporte($li_ano,$ls_meses,$ls_etiqueta,$io_pdf);
		uf_print_titulo($ls_etiqueta,$io_pdf);
		uf_print_cabecera($ls_etiqueta,$io_pdf);
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		unset($la_data);
		if($z<$li_total)
		{
		 $io_pdf->ezNewPage(); // Insertar una nueva página
		}
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