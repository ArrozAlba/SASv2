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
		$io_pdf->line(69,380,69,460);
		$io_pdf->line(199,380,199,460);
		$io_pdf->line(279,380,279,430); // Linea entre Mensual y Acumulado
		$io_pdf->line(349,380,349,460);
		$io_pdf->line(419,380,419,405);
		$io_pdf->line(489,380,489,430);
		$io_pdf->line(559,380,559,405);
		$io_pdf->line(350,405,629,405);
		$io_pdf->line(629,380,629,460);
		$io_pdf->line(699,380,699,430);
		$io_pdf->line(769,380,769,460);
		$io_pdf->line(900,380,900,460);
		$io_pdf->line(200,430,769,430);
		$io_pdf->addText(25,400,7,"CODIGO");
		$io_pdf->addText(105,400,7,"DENOMINACION");
		$io_pdf->addText(250,435,7,"PROGRAMADO");
		$io_pdf->addText(215,405,7,"MENSUAL");
		$io_pdf->addText(290,405,7,"ACUMULADO");
		$io_pdf->addText(405,410,7,"MENSUAL");
		$io_pdf->addText(540,410,7,"ACUMULADO");
		$io_pdf->addText(358,390,7,"COMPROMISO");
		$io_pdf->addText(470,435,7,"EJECUTADO ");
		$io_pdf->addText(435,390,7,"CAUSADO");
		$io_pdf->addText(500,390,7,"COMPROMISO");
		$io_pdf->addText(575,390,7,"CAUSADO");
		$io_pdf->addText(670,435,7,"VARIACION MES");
		$io_pdf->addText(635,405,7,"% COMPROMISO");
		$io_pdf->addText(710,405,7,"% CAUSADO");
		$io_pdf->addText(800,415,7,"RESPONSABLE DE");
		$io_pdf->addText(805,405,7,"LA EJECUCION");
		$io_pdf->addText(935,415,7,"PREVISION");
		$io_pdf->addText(930,405,7,"ACTUALIZADA");
		$io_pdf->addText(930,395,7,"PROXIMO MES");
		$io_pdf->rectangle(10,460,990,120);
		$io_pdf->rectangle(10,382,990,78);
		
		$li_tm=$io_pdf->getTextWidth(16,$as_titulo);
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,500,16,$as_titulo); // Agregar el título
		
		// Fecha
		$io_pdf->line(900,500,900,520);
		$io_pdf->line(900,500,970,500);
		$io_pdf->line(920,500,920,520);
		$io_pdf->line(940,500,940,520);
		$io_pdf->line(970,500,970,520);
		$io_pdf->addText(915,525,10,"FECHA");
		$io_pdf->addText(905,505,10,date("d"));
		$io_pdf->addText(925,505,10,date("m"));
		$io_pdf->addText(945,505,10,date("Y"));
		
		//$io_pdf->addText(900,550,10,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_titulo_reporte($io_cabecera,$as_programatica,$ai_ano,$as_mes,$as_denestpro,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 14/10/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSetY(570);
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_nombre=$_SESSION["la_empresa"]["nombre"];
		$ls_nomorgads=$_SESSION["la_empresa"]["nomorgads"];
		$ls_codasiona   = $_SESSION['la_empresa']['codasiona'];
		
		$la_data=array(array('name'=>'<b>CODIGO DEL ORGANO:     </b>'.'<b>'.$ls_codasiona.'</b>'),
		               array('name'=>'<b>DENOMINACION:    </b>'.'<b>'.$ls_nombre.'</b>'),
					   array('name'=>'<b>PROYECTO/ACCION:    </b>'.'<b>'.$as_denestpro.'</b>'),
					   array('name'=>'<b>MES:    </b>'.'<b>'.$as_mes." ".$ai_ano.'</b>'));
		$la_columna=array('name'=>'');
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
		$io_pdf->addObject($io_cabecera,'all');
		
	}// end function uf_print_titulo_reporte
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
		$la_data=array(array('codigo'=>'',
							 'denominacion'=>'',
							 'programado_mensual'=>'',
							 'programado_acumulado'=>'',
							 'ejecutado_mens_comp'=>'',
							 'ejecutado_mens_caus'=>'',
							 'ejecutado_acum_comp'=>'',
							 'ejecutado_acum_caus'=>'',
							 'variacion_comp'=>'',
							 'variacion_caus'=>'',
							 'responsable_ejec'=>'',
							 'prevision_prox_mes'=>''));
							 
		$la_columna=array(   'codigo'=>'',
							 'denominacion'=>'',
							 'programado_mensual'=>'',
							 'programado_acumulado'=>'',
							 'ejecutado_mens_comp'=>'',
							 'ejecutado_mens_caus'=>'',
							 'ejecutado_acum_comp'=>'',
							 'ejecutado_acum_caus'=>'',
							 'variacion_comp'=>'',
							 'variacion_caus'=>'',
							 'responsable_ejec'=>'',
							 'prevision_prox_mes'=>'');
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
						 'cols'=>array('codigo'=>         array('justification'=>'center','width'=>60),
							           'denominacion'=>    array('justification'=>'center','width'=>130),
							           'programado_mensual'=>        array('justification'=>'center','width'=>80),
							           'programado_acumulado'=>      array('justification'=>'center','width'=>70),
							           'ejecutado_mens_comp'=>      array('justification'=>'center','width'=>70),
							           'ejecutado_mens_caus'=>      array('justification'=>'center','width'=>70),
							           'ejecutado_acum_comp'=>         array('justification'=>'center','width'=>70),
							           'ejecutado_acum_caus'=>          array('justification'=>'center','width'=>70),
							           'variacion_comp'=> array('justification'=>'center','width'=>70),
							           'variacion_caus'=> array('justification'=>'center','width'=>70),
							           'responsable_ejec'=>    array('justification'=>'center','width'=>70),
							           'prevision_prox_mes'=>     array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_titulo,'all');

	}// end function uf_print_titulo
	//--------------------------------------------------------------------------------------------------------------------------------	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------	//--------------------------------------------------------------------------------------------------------------------------------

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
						 'cols'=>array('codigo'=>         array('justification'=>'center','width'=>60),
							           'denominacion'=>    array('justification'=>'left','width'=>130),
							           'programado_mensual'=>        array('justification'=>'right','width'=>80),
							           'programado_acumulado'=>      array('justification'=>'right','width'=>70),
							           'ejecutado_mens_comp'=>      array('justification'=>'right','width'=>70),
							           'ejecutado_mens_caus'=>      array('justification'=>'right','width'=>70),
							           'ejecutado_acum_comp'=>         array('justification'=>'right','width'=>70),
							           'ejecutado_acum_caus'=>          array('justification'=>'right','width'=>70),
							           'variacion_comp'=> array('justification'=>'right','width'=>70),
							           'variacion_caus'=> array('justification'=>'right','width'=>70),
							           'responsable_ejec'=>    array('justification'=>'center','width'=>131),
							           'prevision_prox_mes'=>     array('justification'=>'right','width'=>99))); // Justificación y ancho de la columna
		$la_columnas=array(  'codigo'=>'',
							 'denominacion'=>'',
							 'programado_mensual'=>'',
							 'programado_acumulado'=>'',
							 'ejecutado_mens_comp'=>'',
							 'ejecutado_mens_caus'=>'',
							 'ejecutado_acum_comp'=>'',
							 'ejecutado_acum_caus'=>'',
							 'variacion_comp'=>'',
							 'variacion_caus'=>'',
							 'responsable_ejec'=>'',
							 'prevision_prox_mes'=>'');
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
						 			   'programado_mensual'=>        array('justification'=>'right','width'=>80),
							           'programado_acumulado'=>      array('justification'=>'right','width'=>70),
							           'ejecutado_mens_comp'=>      array('justification'=>'right','width'=>70),
							           'ejecutado_mens_caus'=>      array('justification'=>'right','width'=>70),
							           'ejecutado_acum_comp'=>         array('justification'=>'right','width'=>70),
							           'ejecutado_acum_caus'=>          array('justification'=>'right','width'=>70),
							           'variacion_comp'=> array('justification'=>'right','width'=>70),
							           'variacion_caus'=> array('justification'=>'right','width'=>70),
							           'responsable_ejec'=>    array('justification'=>'center','width'=>131),
							           'prevision_prox_mes'=>     array('justification'=>'right','width'=>99))); // Justificación y ancho de la columna
		$la_columnas=array('totales'=>'',
						   'programado_mensual'=>'',
						   'programado_acumulado'=>'',
						   'ejecutado_mens_comp'=>'',
						   'ejecutado_mens_caus'=>'',
						   'ejecutado_acum_comp'=>'',
						   'ejecutado_acum_caus'=>'',
						   'variacion_comp'=>'',
						   'variacion_caus'=>'',
						   'responsable_ejec'=>'',
						   'prevision_prox_mes'=>'');
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
//-----------------------------------------------------------------------------------------------------------------------------
		global $la_data_tot;
		require_once("sigesp_spg_class_reportes_instructivo_06.php");
		$io_report = new sigesp_spg_class_reportes_instructivo_06();
		
		/* $li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		 $li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		 $li_redconmon=$_SESSION["la_empresa"]["redconmon"]; */
		 
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];

		$ls_cmbmes=$_GET["mes"];
		switch($ls_cmbmes)
		{
		 case '01': $ls_mes = "ENERO";
		 break;
		 
		 case '02': $ls_mes = "FEBRERO";
		 break;
		 
		 case '03': $ls_mes = "MARZO";
		 break;
		 
		 case '04': $ls_mes = "ABRIL";
		 break;
		 
		 case '05': $ls_mes = "MAYO";
		 break;
		 
		 case '06': $ls_mes = "JUNIO";
		 break;
		 
		 case '07': $ls_mes = "JULIO";
		 break;
		 
		 case '08': $ls_mes = "AGOSTO";
		 break;
		 
		 case '09': $ls_mes = "SEPTIEMBRE";
		 break;
		 
		 case '10': $ls_mes = "OCTUBRE";
		 break;
		 
		 case '11': $ls_mes = "NOVIEMBRE";
		 break;
		 
		 case '12': $ls_mes = "DICIEMBRE";
		 break;
		
		}
		$li_mesdes=substr($ls_cmbmes,0,2);
		$ldt_fecdes=$li_ano."-".$ls_cmbmes."-01";
		$li_meshas=substr($ls_cmbmes,2,2);
		$ldt_ult_dia=$io_fecha->uf_last_day($ls_cmbmes,$li_ano);
		$fechas=$ldt_ult_dia;
		$ldt_fechas=$io_funciones->uf_convertirdatetobd($fechas);	
		
//----------------------------------------------------  Parámetros del encabezado  ---------------------------------------------
		$ls_titulo=" <b>EJECUCIÓN FINANCIERA DE LAS ACCIONES ESPECÍFICAS DEL ORGANO</b>";       
//--------------------------------------------------------------------------------------------------------------------------------
   
     $lb_valido=$io_report->uf_spg_reporte_ejecucion_financiera_acc_esp($ldt_fecdes,$ldt_fechas);

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
		uf_print_encabezado_pagina($ls_titulo,"",$ls_mes,$io_pdf); // Imprimimos el encabezado de la página
 	    $io_pdf->ezStartPageNumbers(980,40,10,'','',1); // Insertar el número de página
		$io_pdf->transaction('start'); // Iniciamos la transacción
		$thisPageNum=$io_pdf->ezPageCount;
		$li_tot=$io_report->dts_reporte->getRowCount("codigo");
	    $ld_total_programado_mensual   = 0;
		$ld_total_programado_acumulado = 0;
		$ld_total_ejecutado_mens_comp  = 0;
		$ld_total_ejecutado_mens_caus  = 0;
		$ld_total_ejecutado_acum_comp  = 0;
		$ld_total_ejecutado_acum_caus  = 0;
		$ld_total_prevision_prox_mes   = 0;
		$ls_mesdes = "";	
		//$thisPageNum=$io_pdf->ezPageCount;
		for($z=1;$z<=$li_tot;$z++)
		{		
			$li_tmp=($z+1);
			$thisPageNum=$io_pdf->ezPageCount;
			$ls_codigo="";
			$ls_denominacion="";
			$ls_denestpro2="";
			$ls_denpryacc = "";
			$ls_responsable_ejec = "";
			$ld_programado_mensual   = 0;
		    $ld_programado_acumulado = 0;
		    $ld_ejecutado_mens_comp  = 0;
		    $ld_ejecutado_mens_caus  = 0;
			$ld_ejecutado_acum_comp  = 0;
		    $ld_ejecutado_acum_caus  = 0;
			$ld_variacion_comp       = 0;
			$ld_variacion_caus       = 0;
			$ld_prevision_prox_mes   = 0;
			

				  $ls_pry_acc                = trim($io_report->dts_reporte->data["codigo"][$z]);
				  $ls_codigo                 = trim($io_report->dts_reporte->data["codestpro2"][$z]);
				  $ls_denpryacc				 = trim($io_report->dts_reporte->data["denominacion"][$z]);
				  $ls_denominacion           = trim($io_report->dts_reporte->data["denestpro2"][$z]);
				  $ld_programado_mensual     = $io_report->dts_reporte->data["programado_mensual"][$z];
				  $ld_programado_acumulado   = $io_report->dts_reporte->data["programado_acumulado"][$z];
				  $ld_ejecutado_mens_comp    = $io_report->dts_reporte->data["ejecutado_mens_comp"][$z];
				  $ld_ejecutado_mens_caus    = $io_report->dts_reporte->data["ejecutado_mens_caus"][$z];
				  $ld_ejecutado_acum_comp    = $io_report->dts_reporte->data["ejecutado_acum_comp"][$z];
				  $ld_ejecutado_acum_caus    = $io_report->dts_reporte->data["ejecutado_acum_caus"][$z];
				  $ld_prevision_prox_mes     = $io_report->dts_reporte->data["prevision_prox_mes"][$z];
				  if ($z<$li_tot)
				  {
					$ls_pry_acc_next=$io_report->dts_reporte->data["codigo"][$li_tmp]; 
				  }
				  elseif($z=$li_tot)
				  {
					$ls_pry_acc_next='no_next';
				  }
				  if(!empty($ls_pry_acc))
			      {
			       $ls_pry_acc_ant = $ls_pry_acc;
				   $ls_denominacion_ant = $ls_denominacion;
				   $ld_total_programado_mensual   = 0;
				   $ld_total_programado_acumulado = 0;
				   $ld_total_ejecutado_mens_comp  = 0;
				   $ld_total_ejecutado_mens_caus  = 0;
				   $ld_total_ejecutado_acum_comp  = 0;
				   $ld_total_ejecutado_acum_caus  = 0;
				   $ld_total_prevision_prox_mes   = 0;
				   if($ld_programado_mensual > 0)
				   {
					 $ld_variacion_comp         = ($ld_ejecutado_mens_comp*100)/$ld_programado_mensual;
				   }
				   else
				   {
					 $ld_variacion_comp = 0;
				   }
				   if ($ld_ejecutado_mens_comp >0)
				   {
				    $ld_variacion_caus         = ($ld_ejecutado_mens_caus*100)/$ld_ejecutado_mens_comp;
				   }
				   else
				   {
				    $ld_variacion_caus = 0;
				   }  
				  $ld_total_programado_mensual   = $ld_total_programado_mensual + $ld_programado_mensual;
				  $ld_total_programado_acumulado = $ld_total_programado_acumulado + $ld_programado_acumulado;
				  $ld_total_ejecutado_mens_comp  = $ld_total_ejecutado_mens_comp + $ld_ejecutado_mens_comp;
				  $ld_total_ejecutado_mens_caus  = $ld_total_ejecutado_mens_caus + $ld_ejecutado_mens_caus;
				  $ld_total_ejecutado_acum_comp  = $ld_total_ejecutado_acum_comp + $ld_ejecutado_acum_comp;
				  $ld_total_ejecutado_acum_caus  = $ld_total_ejecutado_acum_caus + $ld_ejecutado_acum_caus;
				  $ld_total_prevision_prox_mes   = $ld_total_prevision_prox_mes + $ld_prevision_prox_mes;
				  
				  $ld_programado_mensual   = number_format($ld_programado_mensual,2,",",".");
				  $ld_programado_acumulado = number_format($ld_programado_acumulado,2,",",".");
				  $ld_ejecutado_mens_comp  = number_format($ld_ejecutado_mens_comp,2,",",".");
				  $ld_ejecutado_mens_caus  = number_format($ld_ejecutado_mens_caus,2,",",".");
				  $ld_ejecutado_acum_comp  = number_format($ld_ejecutado_acum_comp,2,",",".");
				  $ld_ejecutado_acum_caus  = number_format($ld_ejecutado_acum_caus,2,",",".");
				  $ld_variacion_comp       = number_format($ld_variacion_comp,2,",",".");
				  $ld_variacion_caus       = number_format($ld_variacion_caus,2,",",".");
				  $ld_prevision_prox_mes   = number_format($ld_prevision_prox_mes,2,",",".");
				  
				  $la_data[$z]=array('codigo'=>$ls_codigo,
									 'denominacion'=>$ls_denominacion,
									 'programado_mensual'=>$ld_programado_mensual,
									 'programado_acumulado'=>$ld_programado_acumulado,
									 'ejecutado_mens_comp'=>$ld_ejecutado_mens_comp,
									 'ejecutado_mens_caus'=>$ld_ejecutado_mens_caus,
									 'ejecutado_acum_comp'=>$ld_ejecutado_acum_comp,
									 'ejecutado_acum_caus'=>$ld_ejecutado_acum_caus,
									 'variacion_comp'=>$ld_variacion_comp,
									 'variacion_caus'=>$ld_variacion_caus,
									 'responsable_ejec'=>'',
									 'prevision_prox_mes'=>$ld_prevision_prox_mes);
			      }
				  else
				  {
				    $la_data[$z]=array('codigo'=>$ls_codigo,
									 'denominacion'=>$ls_denominacion,
									 'programado_mensual'=>$ld_programado_mensual,
									 'programado_acumulado'=>$ld_programado_acumulado,
									 'ejecutado_mens_comp'=>$ld_ejecutado_mens_comp,
									 'ejecutado_mens_caus'=>$ld_ejecutado_mens_caus,
									 'ejecutado_acum_comp'=>$ld_ejecutado_acum_comp,
									 'ejecutado_acum_caus'=>$ld_ejecutado_acum_caus,
									 'variacion_comp'=>$ld_variacion_comp,
									 'variacion_caus'=>$ld_variacion_caus,
									 'responsable_ejec'=>'',
									 'prevision_prox_mes'=>$ld_prevision_prox_mes);
				  
				  }
				  
				  if(!empty($ls_pry_acc_next))
				  {
				    $la_data[$z]=array('codigo'=>$ls_codigo,
									 'denominacion'=>$ls_denominacion,
									 'programado_mensual'=>$ld_programado_mensual,
									 'programado_acumulado'=>$ld_programado_acumulado,
									 'ejecutado_mens_comp'=>$ld_ejecutado_mens_comp,
									 'ejecutado_mens_caus'=>$ld_ejecutado_mens_caus,
									 'ejecutado_acum_comp'=>$ld_ejecutado_acum_comp,
									 'ejecutado_acum_caus'=>$ld_ejecutado_acum_caus,
									 'variacion_comp'=>$ld_variacion_comp,
									 'variacion_caus'=>$ld_variacion_caus,
									 'responsable_ejec'=>'',
									 'prevision_prox_mes'=>$ld_prevision_prox_mes);				 
				  }				 
					  							 						   
			  $ld_total_programado_mensual   = number_format($ld_total_programado_mensual,2,",",".");
			  $ld_total_programado_acumulado = number_format($ld_total_programado_acumulado,2,",",".");
			  $ld_total_ejecutado_mens_comp  = number_format($ld_total_ejecutado_mens_comp,2,",",".");
			  $ld_total_ejecutado_mens_caus  = number_format($ld_total_ejecutado_mens_caus,2,",",".");
			  $ld_total_ejecutado_acum_comp  = number_format($ld_total_ejecutado_acum_comp,2,",",".");
			  $ld_total_ejecutado_acum_caus  = number_format($ld_total_ejecutado_acum_caus,2,",",".");
			  $ld_total_prevision_prox_mes   = number_format($ld_total_prevision_prox_mes,2,",",".");
			
			$la_data_tot[1]=array('totales'=>"TOTALES",
								  'programado_mensual'=>$ld_total_programado_mensual,
								  'programado_acumulado'=>$ld_total_programado_acumulado,
								  'ejecutado_mens_comp'=>$ld_total_ejecutado_mens_comp,
								  'ejecutado_mens_caus'=>$ld_total_ejecutado_mens_caus,
								  'ejecutado_acum_comp'=>$ld_total_ejecutado_acum_comp,
								  'ejecutado_acum_caus'=>$ld_total_ejecutado_acum_caus,
								  'variacion_comp'=>'',
								  'variacion_caus'=>'',
								  'responsable_ejec'=>'',
								  'prevision_prox_mes'=>$ld_total_prevision_prox_mes);
 
			 $io_cabecera=$io_pdf->openObject();
			 uf_print_titulo_reporte($io_cabecera,"",$li_ano,$ls_mes,$ls_pry_acc." - ".$ls_denpryacc,$io_pdf);
			 $io_pdf->stopObject($io_cabecera);
			 $io_pdf->ezSetCmMargins(8.0125,3,3,3);
			 uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
			 uf_print_pie_cabecera($la_data_tot,$io_pdf);
			 unset($la_data);
			 unset($la_data_tot);
			 
			 if ((!empty($ls_pry_acc))&&($z<$li_tot))
			 {
				 $io_pdf->ezNewPage(); // Insertar una nueva página
			 }
		}//for			
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