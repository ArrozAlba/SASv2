<?php
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=+JavaScript>";
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
		$io_pdf->line(69,380,69,460);
		$io_pdf->line(279,380,279,460); 
		$io_pdf->line(369,380,369,460); 
		$io_pdf->line(459,380,459,460); 
		$io_pdf->line(549,380,549,430);
		$io_pdf->line(639,380,639,460);
		$io_pdf->line(729,380,729,430);
		$io_pdf->line(819,380,819,460);
		$io_pdf->line(909,380,909,430);
		$io_pdf->line(460,430,999,430);
		$io_pdf->addText(13,400,8,"Ramos/Partidas");
		$io_pdf->addText(150,400,8,"Denominacion");
		$io_pdf->addText(305,400,8,"Presupuesto");
		$io_pdf->addText(310,390,8,"Aprobado");
		$io_pdf->addText(395,400,8,"Presupuesto");
		$io_pdf->addText(400,390,8,"Modificado");
		$io_pdf->addText(510,440,8,"TRIMESTRE No: ".$as_trimestre);
		$io_pdf->addText(480,400,8,"Programado");
		$io_pdf->addText(575,400,8,"Ejecutado");
		$io_pdf->addText(680,445,8,"VARIACION EJECUTADO-");
		$io_pdf->addText(660,435,8,"PROGRAMADO TRIMESTRE No. ".$as_trimestre);
		$io_pdf->addText(667,400,8,"Absoluto");
		$io_pdf->addText(755,400,8,"Porcentual");
		$io_pdf->addText(840,440,8,"TOTAL ACUMULADO AL TRIMESTRE");
		$io_pdf->addText(845,400,8,"Programado");
		$io_pdf->addText(935,400,8,"Ejecutado");
		$io_pdf->rectangle(10,460,990,120);
		$io_pdf->rectangle(10,382,990,78);
		
		$li_tm=$io_pdf->getTextWidth(16,$as_titulo);
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,500,16,$as_titulo); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(10,'<b>'.$as_moneda.'</b>');
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,480,10,'<b>'.$as_moneda.'</b>'); // Agregar el título
		
		//$io_pdf->addText(900,550,10,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_titulo_reporte($io_encabezado,$as_programatica,$ai_ano,$as_mes,$as_denestpro,&$io_pdf)
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
					   array('name'=>'<b>ORGANO DE ADSCRIPCION:    </b>'.'<b>'."".'</b>'),
		               array('name'=>'<b>PERIODO PRESUPUESTARIO:    </b>'.'<b>'.$ai_ano.'</b>'),
		               array('name'=>''.'<b>'.trim($as_denestpro).'</b>'));
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
		$la_data=array(array('cuenta'=>'',
							 'denominacion'=>'',
							 'asignado'=>'',
							 'modificado'=>'',
							 'programado'=>'',
							 'ejecutado'=>'',
							 'absoluto'=>'',
							 'porcentual'=>'',
							 'programado_acum'=>'',
							 'ejecutado_acum'=>''));
							 
		$la_columna=array(   'cuenta'=>'',
							 'denominacion'=>'',
							 'asignado'=>'',
							 'modificado'=>'',
							 'programado'=>'',
							 'ejecutado'=>'',
							 'absoluto'=>'',
							 'porcentual'=>'',
							 'programado_acum'=>'',
							 'ejecutado_acum'=>'');
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
						 'cols'=>array('cuenta'=>         array('justification'=>'center','width'=>60),
							           'denominacion'=>    array('justification'=>'center','width'=>210),
							           'asignado'=>        array('justification'=>'center','width'=>90),
							           'modificado'=>      array('justification'=>'center','width'=>90),
							           'programado'=>      array('justification'=>'center','width'=>90),
							           'ejecutado'=>      array('justification'=>'center','width'=>90),
							           'absoluto'=>         array('justification'=>'center','width'=>90),
							           'porcentual'=>          array('justification'=>'center','width'=>90),
							           'programado_acum'=> array('justification'=>'center','width'=>90),
							           'ejecutado_acum'=> array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna
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
		$la_data=array(array('cuenta'=>'<b>Cuenta</b>','denominacion'=>'<b>Denominación</b>','pres_anual'=>'<b>Presupuesto Anual</b>',
		                     'programado'=>'<b>Trimestre</b>','programado_acum'=>'<b>Acumulado</b>','compromiso'=>'<b>Compromiso</b>','causado'=>'<b>Causado</b>',
							 'pagado'=>'<b>Pagado</b>','porc_comprometer'=>'<b> Compromiso</b>','porc_causado'=>'<b>Causado</b>',
							 'porc_pagado'=>'<b>Pagado</b>','disp_trim_ant'=>'<b>Trimestre Anterior</b>',
							 'disp_fecha'=>'<b>A la Fecha</b>'));
		$la_columna=array('cuenta'=>'','denominacion'=>'','pres_anual'=>'','programado'=>'','programado_acum'=>'','compromiso'=>'','causado'=>'',
		                  'pagado'=>'','porc_comprometer'=>'','porc_causado'=>'','porc_pagado'=>'','disp_trim_ant'=>'','disp_fecha'=>'');
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
						 			   'pres_anual'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'programado'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'programado_acum'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'compromiso'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'causado'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'pagado'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'porc_comprometer'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'porc_causado'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'porc_pagado'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'disp_trim_ant'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'disp_fecha'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
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
						 			   'denominacion'=>array('justification'=>'left','width'=>210), // Justificación y ancho de la columna
						 			   'asignado'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'modificado'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'programado'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
									   'ejecutado'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
									   'absoluto'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
									   'porcentual'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
									   'programado_acum'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
									   'ejecutado_acum'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$la_columnas=array(  'cuenta'=>'',
							 'denominacion'=>'',
							 'asignado'=>'',
							 'modificado'=>'',
							 'programado'=>'',
							 'ejecutado'=>'',
							 'absoluto'=>'',
							 'porcentual'=>'',
							 'programado_acum'=>'',
							 'ejecutado_acum'=>'');
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
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>210), // Justificación y ancho de la columna
						 			   'asignado'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'modificado'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'programado'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
									   'ejecutado'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
									   'absoluto'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
									   'porcentual'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
									   'programado_acum'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
									   'ejecutado_acum'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$la_columnas=array(  'cuenta'=>'',
						     'denominacion'=>'',
							 'asignado'=>'',
							 'modificado'=>'',
							 'programado'=>'',
							 'ejecutado'=>'',
							 'absoluto'=>'',
							 'porcentual'=>'',
							 'programado_acum'=>'',
							 'ejecutado_acum'=>'');
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
//-----------------------------------------------------------------------------------------------------------------------------
		global $la_data_tot;
		require_once("sigesp_spi_class_reportes_instructivos.php");
		$io_report = new sigesp_spi_class_reportes_instructivos();
		
		 $li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		 $li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		 $li_redconmon=$_SESSION["la_empresa"]["redconmon"];
		 
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		$ls_cmbtri=$_GET["trimestre"];
		
		switch($ls_cmbtri)
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
		$li_mesdes=substr($ls_cmbtri,0,2);
		$ldt_fecdes=$li_ano."-".$li_mesdes."-01";
		$li_meshas=substr($ls_cmbtri,2,2);
		$ldt_ult_dia=$io_fecha->uf_last_day($li_meshas,$li_ano);
		$fechas=$ldt_ult_dia;
		$ldt_fechas=$io_funciones->uf_convertirdatetobd($fechas);
		$ls_mesdes=$io_fecha->uf_load_nombre_mes($li_mesdes);
		$ls_meshas=$io_fecha->uf_load_nombre_mes($li_meshas);
		
		
//----------------------------------------------------  Parámetros del encabezado  ---------------------------------------------
		$ls_titulo=" <b>PRESUPUESTO DE CAJA</b>";       
//--------------------------------------------------------------------------------------------------------------------------------
   
      $lb_valido=$io_report->uf_spg_reportes_presupuesto_de_caja($ldt_fecdes,$ldt_fechas,"",$ls_mesdes,$ls_meshas);
	 //$lb_valido=true;
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
		$li_tot=$io_report->dts_reporte->getRowCount("cuenta");
		//$li_tot=79;
	    $ld_total_asignado=0;
		$ld_total_modificado=0;
		$ld_total_programado=0;
		$ld_total_ejecutado=0;
		$ld_total_absoluto=0;
		$ld_total_porcentual=0;
		$ld_total_programado_acum=0;
		$ld_total_ejecutado_acum=0;
	
		$thisPageNum=$io_pdf->ezPageCount;
		for($z=1;$z<=$li_tot;$z++)
		{		
			$ls_cuenta = "";
			$ls_denominacion = "";
			$ld_asignado=0;
			$ld_modificado=0;
			$ld_programado=0;
			$ld_ejecutado=0;
			$ld_programado_acum=0;
			$ld_ejecutado_acum=0;
			$ld_absoluto=0;
			$ld_porcentual=0;
				  
				$ls_cuenta          =  $io_report->dts_reporte->data["cuenta"][$z];
				$ls_denominacion    =  $io_report->dts_reporte->data["denominacion"][$z];
				$ld_asignado        =  $io_report->dts_reporte->data["asignado"][$z];
				$ld_modificado      =  $io_report->dts_reporte->data["modificado"][$z];
				$ld_programado      =  $io_report->dts_reporte->data["programado"][$z];
				$ld_ejecutado       =  $io_report->dts_reporte->data["ejecutado"][$z];
				$ld_absoluto        =  $io_report->dts_reporte->data["absoluto"][$z];
				$ld_porcentual      =  $io_report->dts_reporte->data["porcentual"][$z];
				$ld_programado_acum =  $io_report->dts_reporte->data["programado_acumulado"][$z];
				$ld_ejecutado_acum  =  $io_report->dts_reporte->data["ejecutado_acumulado"][$z];
		
				  if(($z == 1)||($z == 2))
				  {
				   $ld_total_asignado         = $ld_total_asignado + $ld_asignado;
				   $ld_total_modificado       = $ld_total_modificado + $ld_modificado;
		           $ld_total_programado       = $ld_total_programado + $ld_programado;
		           $ld_total_ejecutado        = $ld_total_ejecutado + $ld_ejecutado;
		           $ld_total_programado_acum  = $ld_total_programado_acum + $ld_programado_acum;
		           $ld_total_ejecutado_acum  =  $ld_total_ejecutado_acum + $ld_ejecutado_acum;
				  }
				  
				  if($ls_cuenta == "400000000")
				  {
				   $ld_total_asignado         = $ld_total_asignado -$ld_asignado;
				   $ld_total_modificado       = $ld_total_modificado - $ld_modificado;
		           $ld_total_programado       = $ld_total_programado - $ld_programado;
		           $ld_total_ejecutado        = $ld_total_ejecutado - $ld_ejecutado;
		           $ld_total_programado_acum  = $ld_total_programado_acum - $ld_programado_acum;
		           $ld_total_ejecutado_acum  =  $ld_total_ejecutado_acum - $ld_ejecutado_acum;
				  } 
				  
				  $ld_asignado               = number_format($ld_asignado,2,",",".");
				  $ld_modificado             = number_format($ld_modificado,2,",",".");
				  $ld_programado             = number_format($ld_programado,2,",",".");
				  $ld_ejecutado              = number_format($ld_ejecutado,2,",",".");
				  $ld_absoluto             = number_format($ld_absoluto,2,",",".");
				  $ld_porcentual                = number_format($ld_porcentual,2,",",".");
				  $ld_programado_acum        = number_format($ld_programado_acum,2,",",".");
				  $ld_ejecutado_acum        = number_format($ld_ejecutado_acum,2,",",".");
				
				  $la_data[$z]=array('cuenta'=>$ls_cuenta,
				                     'denominacion'=>$ls_denominacion,
									 'asignado'=>$ld_asignado,
									 'modificado'=>$ld_modificado,
									 'programado'=>$ld_programado,
									 'ejecutado'=>$ld_ejecutado,
									 'absoluto'=>$ld_absoluto,
									 'porcentual'=>$ld_porcentual,
									 'programado_acum'=>$ld_programado_acum,
									 'ejecutado_acum'=>$ld_ejecutado_acum);
					  							 						   
			}//for
		    $ld_total_absoluto           = abs($ld_total_ejecutado - $ld_total_programado);
			if ($ld_total_programado > 0)
			{
			 $ld_total_porcentual = ($ld_total_ejecutado/$ld_total_programado)*100;
			}
			else
			{
			 $ld_total_porcentual = 0;
			}
			
			$ld_total_asignado           = number_format($ld_total_asignado,2,",",".");
			$ld_total_modificado         = number_format($ld_total_modificado,2,",",".");
		    $ld_total_programado         = number_format($ld_total_programado,2,",",".");
		    $ld_total_ejecutado          = number_format($ld_total_ejecutado,2,",",".");
		    $ld_total_absoluto           = number_format($ld_total_absoluto,2,",",".");
		    $ld_total_porcentual         = number_format($ld_total_porcentual,2,",",".");
		    $ld_total_programado_acum    = number_format($ld_total_programado_acum,2,",",".");
		    $ld_total_ejecutado_acum     = number_format($ld_total_ejecutado_acum,2,",",".");
		  
			
			$la_data_tot[1]=array('cuenta'=>"",
			                      'denominacion'=>" SALDO FINAL",
								  'asignado'=>$ld_total_asignado,
								  'modificado'=>$ld_total_modificado,
								  'programado'=>$ld_total_programado,
								  'ejecutado'=>$ld_total_ejecutado,
								  'absoluto'=>$ld_total_absoluto,
								  'porcentual'=>$ld_total_porcentual,
								  'programado_acum'=>$ld_total_programado_acum,
								  'ejecutado_acum'=>$ld_total_ejecutado_acum);

            $io_encabezado=$io_pdf->openObject();
			uf_print_titulo_reporte($io_encabezado,"",$li_ano,$ls_mesdes,"",$io_pdf);
//            $io_titulo=$io_pdf->openObject();
//			uf_print_titulo($io_titulo,$io_pdf);
//		    $io_cabecera=$io_pdf->openObject();
//			uf_print_cabecera($io_cabecera,$io_pdf);
			$io_pdf->ezSetCmMargins(8.0125,3,3,3);
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		    uf_print_pie_cabecera($la_data_tot,$io_pdf);
//			$io_pdf->stopObject($io_encabezado);
//			$io_pdf->stopObject($io_titulo);
//			$io_pdf->stopObject($io_cabecera);
			unset($la_data);
			unset($la_data_tot);
			if($z<$li_tot)
/*			{
			 $io_pdf->ezNewPage(); // Insertar una nueva página
			} 	*/		
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