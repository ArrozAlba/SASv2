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
		// Fecha Creación: 29/10/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,440,1000,440);  // Linea Proyecto/Accion
		$io_pdf->line(130,420,1000,420); // Linea SUBPARTIDA, COMPROMISO, CAUSADO Y PAGADO
		$io_pdf->line(400,405,480,405);  // Linea horizontal Variacion de Compromiso 
		$io_pdf->line(660,405,740,405);  // Linea horizontal Variacion de Causado 
		$io_pdf->line(920,405,1000,405); // Linea horizontal Variacion de Pagado 
		$io_pdf->line(45,380,45,440);    // Linea entre Codigo y Denominacion
		$io_pdf->line(130,380,130,440);  // Linea entre Denominacion y Partida
		$io_pdf->line(150,380,150,440);  // Partida
		$io_pdf->line(170,380,170,420);  // Generica
		$io_pdf->line(190,380,190,420);  // Específica
		$io_pdf->line(220,380,220,460);  // SubEspecífica
		$io_pdf->line(265,380,265,420);  // Compromiso Programado Mensual
		$io_pdf->line(310,380,310,420);  // Compromiso Programado Acumulado Mensual
		$io_pdf->line(355,380,355,420);  // Compromiso Ejecutado Mensual
		$io_pdf->line(400,380,400,420);  // Compromiso Ejecutado Acumulado Mensual
		$io_pdf->line(435,380,435,405);  // Compromiso Variacion Absoluta Mensual
		$io_pdf->addText(435,390,5,"%");
		$io_pdf->line(440,380,440,420);  // % Compromiso Variacion Absoluta Mensual
		$io_pdf->line(475,380,475,405);  // Compromiso Variacion Absoluta Acumulada
		$io_pdf->addText(475,390,5,"%");
		$io_pdf->line(480,380,480,440);  // % Compromiso Variacion Absoluta Acumulada
		$io_pdf->line(525,380,525,420);  // Causado Programado Mensual
		$io_pdf->line(570,380,570,420);  // Causado Programado Acumulado Mensual
		$io_pdf->line(615,380,615,420);  // Causado Ejecutado Mensual
		$io_pdf->line(660,380,660,420);  // Causado Ejecutado Acumulado Mensual
		$io_pdf->line(695,380,695,405);  // Causado Variacion Absoluta Mensual
		$io_pdf->addText(695,390,5,"%");
		$io_pdf->line(700,380,700,420);  // % Causado Variacion Absoluta Mensual
		$io_pdf->line(735,380,735,405);  // Causado Variacion Absoluta Acumulada
		$io_pdf->addText(735,390,5,"%");
		$io_pdf->line(740,380,740,440);  // % Causado Variacion Absoluta Acumulada
		$io_pdf->line(785,380,785,420);  // Pagado Programado Mensual
		$io_pdf->line(830,380,830,420);  // Pagado Programado Acumulado Mensual
		$io_pdf->line(875,380,875,420);  // Pagado Ejecutado Mensual
		$io_pdf->line(920,380,920,420);  // Pagado Ejecutado Acumulado Mensual
		$io_pdf->line(955,380,955,405);  // Pagado Variacion Absoluta Mensual
		$io_pdf->addText(955,390,5,"%");
		$io_pdf->line(960,380,960,420);  // % Pagado Variacion Absoluta Mensual
		$io_pdf->line(995,380,995,405);  // Pagado Variacion Absoluta Acumulada
		$io_pdf->addText(995,390,5,"%");
		
		
		$io_pdf->addText(12,400,7,"CODIGO");
		$io_pdf->addText(60,400,7,"DENOMINACION");
		$io_pdf->addText(50,450,7,"<b>PROYECTO O ACCION CENTRALIZADA<b>");
		$io_pdf->addText(600,450,7,"<b>MONTO<b>");
		$io_pdf->addText(165,425,7,"SUB-PART");
		$io_pdf->addText(320,425,7,"COMPROMISO");
		$io_pdf->addText(590,425,7,"CAUSADO");
		$io_pdf->addText(850,425,7,"PAGADO");
		$io_pdf->addText(132,400,6,"PART");
		$io_pdf->addText(152,400,6,"GEN");
		$io_pdf->addText(172,400,6,"ESP");
		$io_pdf->addText(192,400,6,"SUB-ESP");
		// COMPROMISO
		$io_pdf->addText(221,400,6,"PROGRAMADO");
		$io_pdf->addText(228,390,6,"MENSUAL");
		$io_pdf->addText(266,400,6,"PROGRAMADO");
		$io_pdf->addText(268,390,6,"ACUMULADO");
		$io_pdf->addText(315,400,6,"EJECUTADO");
		$io_pdf->addText(318,390,6,"MENSUAL");
		$io_pdf->addText(360,400,6,"EJECUTADO");
		$io_pdf->addText(358,390,6,"ACUMULADO");
		$io_pdf->addText(403,407,6,"VARIACION");
		$io_pdf->addText(403,395,5,"ABSOLUTA");
		$io_pdf->addText(404,388,5,"MENSUAL");
		$io_pdf->addText(442,407,6,"VARIACION");
		$io_pdf->addText(442,395,5,"ABSOLUTA");
		$io_pdf->addText(441,388,5,"ACUMULADA");
		// CAUSADO
		$io_pdf->addText(481,400,6,"PROGRAMADO");
		$io_pdf->addText(488,390,6,"MENSUAL");
		$io_pdf->addText(526,400,6,"PROGRAMADO");
		$io_pdf->addText(528,390,6,"ACUMULADO");
		$io_pdf->addText(575,400,6,"EJECUTADO");
		$io_pdf->addText(578,390,6,"MENSUAL");
		$io_pdf->addText(620,400,6,"EJECUTADO");
		$io_pdf->addText(618,390,6,"ACUMULADO");
		$io_pdf->addText(663,407,6,"VARIACION");
		$io_pdf->addText(663,395,5,"ABSOLUTA");
		$io_pdf->addText(664,388,5,"MENSUAL");
		$io_pdf->addText(702,407,6,"VARIACION");
		$io_pdf->addText(702,395,5,"ABSOLUTA");
		$io_pdf->addText(701,388,5,"ACUMULADA");
		// PAGADO
		$io_pdf->addText(741,400,6,"PROGRAMADO");
		$io_pdf->addText(748,390,6,"MENSUAL");
		$io_pdf->addText(788,400,6,"PROGRAMADO");
		$io_pdf->addText(790,390,6,"ACUMULADO");
		$io_pdf->addText(837,400,6,"EJECUTADO");
		$io_pdf->addText(838,390,6,"MENSUAL");
		$io_pdf->addText(880,400,6,"EJECUTADO");
		$io_pdf->addText(878,390,6,"ACUMULADO");
		$io_pdf->addText(923,407,6,"VARIACION");
		$io_pdf->addText(923,395,5,"ABSOLUTA");
		$io_pdf->addText(924,388,5,"MENSUAL");
		$io_pdf->addText(962,407,6,"VARIACION");
		$io_pdf->addText(962,395,5,"ABSOLUTA");
		$io_pdf->addText(961,388,5,"ACUMULADA");
		
		$io_pdf->rectangle(10,460,990,120);
		$io_pdf->rectangle(10,382,990,78);
		
		$li_tm=$io_pdf->getTextWidth(16,$as_titulo);
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,500,16,$as_titulo); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(16,$as_moneda);
		$tm=490-($li_tm/2);
		$io_pdf->addText($tm,485,10,$as_moneda); // Agregar el título
		
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
					   array('name'=>'<b>MES:    </b>'.'<b>'.$as_mes." ".$ai_ano.'</b>'));
		$la_columna=array('name'=>'','name'=>'','name'=>'');
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
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 28/10/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 5, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array(	 'codigo'=>array('justification'=>'left','width'=>37),
										 'denominacion'=>array('justification'=>'left','width'=>85),
										 'partida'=>array('justification'=>'center','width'=>20),
										 'generica'=>array('justification'=>'center','width'=>20),
										 'especifica'=>array('justification'=>'center','width'=>20),
										 'subespecifica'=>array('justification'=>'center','width'=>30),
										 'comp_prog_mensual'=>array('justification'=>'right','width'=>45),
										 'comp_prog_acum'=>array('justification'=>'right','width'=>45),
										 'comp_eje_mens'=>array('justification'=>'right','width'=>45),
										 'comp_eje_acum'=>array('justification'=>'right','width'=>45),
										 'comp_vari_abs_mens'=>array('justification'=>'right','width'=>35),
										 'comp_porc_vari_abs_mens'=>array('justification'=>'center','width'=>5),
										 'comp_vari_abs_acum'=>array('justification'=>'right','width'=>35),
										 'comp_porc_vari_abs_acum'=>array('justification'=>'center','width'=>5),
										 'caus_prog_mensual'=>array('justification'=>'right','width'=>45),
										 'caus_prog_acum'=>array('justification'=>'right','width'=>45),
										 'caus_eje_mens'=>array('justification'=>'right','width'=>45),
										 'caus_eje_acum'=>array('justification'=>'right','width'=>45),
										 'caus_vari_abs_mens'=>array('justification'=>'right','width'=>35),
										 'caus_porc_vari_abs_mens'=>array('justification'=>'center','width'=>5),
										 'caus_vari_abs_acum'=>array('justification'=>'right','width'=>35),
										 'caus_porc_vari_abs_acum'=>array('justification'=>'center','width'=>5),
										 'paga_prog_mensual'=>array('justification'=>'right','width'=>45),
										 'paga_prog_acum'=>array('justification'=>'right','width'=>45),
										 'paga_eje_mens'=>array('justification'=>'right','width'=>45),
										 'paga_eje_acum'=>array('justification'=>'right','width'=>45),
										 'paga_vari_abs_mens'=>array('justification'=>'right','width'=>35),
										 'paga_porc_vari_abs_mens'=>array('justification'=>'center','width'=>5),
										 'paga_vari_abs_acum'=>array('justification'=>'right','width'=>35),
										 'paga_porc_vari_abs_acum'=>array('justification'=>'center','width'=>5))); // Justificación y ancho de la columna
		$la_columnas=array(  'codigo'=>'',
							 'denominacion'=>'',
							 'partida'=>'',
							 'generica'=>'',
							 'especifica'=>'',
							 'subespecifica'=>'',
							 'comp_prog_mensual'=>'',
							 'comp_prog_acum'=>'',
							 'comp_eje_mens'=>'',
							 'comp_eje_acum'=>'',
							 'comp_vari_abs_mens'=>'',
							 'comp_porc_vari_abs_mens'=>'',
							 'comp_vari_abs_acum'=>'',
							 'comp_porc_vari_abs_acum'=>'',
							 'caus_prog_mensual'=>'',
							 'caus_prog_acum'=>'',
							 'caus_eje_mens'=>'',
							 'caus_eje_acum'=>'',
							 'caus_vari_abs_mens'=>'',
							 'caus_porc_vari_abs_mens'=>'',
							 'caus_vari_abs_acum'=>'',
							 'caus_porc_vari_abs_acum'=>'',
							 'paga_prog_mensual'=>'',
							 'paga_prog_acum'=>'',
							 'paga_eje_mens'=>'',
							 'paga_eje_acum'=>'',
							 'paga_vari_abs_mens'=>'',
							 'paga_porc_vari_abs_mens'=>'',
							 'paga_vari_abs_acum'=>'',
							 'paga_porc_vari_abs_acum'=>'');
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
						 'fontSize' => 5, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array(  'totales'=>array('justification'=>'center','width'=>212), // Justificación y ancho de la columna
						 			     'comp_prog_mensual'=>array('justification'=>'right','width'=>45),
										 'comp_prog_acum'=>array('justification'=>'right','width'=>45),
										 'comp_eje_mens'=>array('justification'=>'right','width'=>45),
										 'comp_eje_acum'=>array('justification'=>'right','width'=>45),
										 'comp_vari_abs_mens'=>array('justification'=>'right','width'=>35),
										 'comp_porc_vari_abs_mens'=>array('justification'=>'center','width'=>5),
										 'comp_vari_abs_acum'=>array('justification'=>'right','width'=>35),
										 'comp_porc_vari_abs_acum'=>array('justification'=>'center','width'=>5),
										 'caus_prog_mensual'=>array('justification'=>'right','width'=>45),
										 'caus_prog_acum'=>array('justification'=>'right','width'=>45),
										 'caus_eje_mens'=>array('justification'=>'right','width'=>45),
										 'caus_eje_acum'=>array('justification'=>'right','width'=>45),
										 'caus_vari_abs_mens'=>array('justification'=>'right','width'=>35),
										 'caus_porc_vari_abs_mens'=>array('justification'=>'center','width'=>5),
										 'caus_vari_abs_acum'=>array('justification'=>'right','width'=>35),
										 'caus_porc_vari_abs_acum'=>array('justification'=>'center','width'=>5),
										 'paga_prog_mensual'=>array('justification'=>'right','width'=>45),
										 'paga_prog_acum'=>array('justification'=>'right','width'=>45),
										 'paga_eje_mens'=>array('justification'=>'right','width'=>45),
										 'paga_eje_acum'=>array('justification'=>'right','width'=>45),
										 'paga_vari_abs_mens'=>array('justification'=>'right','width'=>35),
										 'paga_porc_vari_abs_mens'=>array('justification'=>'center','width'=>5),
										 'paga_vari_abs_acum'=>array('justification'=>'right','width'=>35),
										 'paga_porc_vari_abs_acum'=>array('justification'=>'center','width'=>5))); // Justificación y ancho de la columna
		$la_columnas=array(  'totales'=>'',
						   	 'comp_prog_mensual'=>'',
							 'comp_prog_acum'=>'',
							 'comp_eje_mens'=>'',
							 'comp_eje_acum'=>'',
							 'comp_vari_abs_mens'=>'',
							 'comp_porc_vari_abs_mens'=>'',
							 'comp_vari_abs_acum'=>'',
							 'comp_porc_vari_abs_acum'=>'',
							 'caus_prog_mensual'=>'',
							 'caus_prog_acum'=>'',
							 'caus_eje_mens'=>'',
							 'caus_eje_acum'=>'',
							 'caus_vari_abs_mens'=>'',
							 'caus_porc_vari_abs_mens'=>'',
							 'caus_vari_abs_acum'=>'',
							 'caus_porc_vari_abs_acum'=>'',
							 'paga_prog_mensual'=>'',
							 'paga_prog_acum'=>'',
							 'paga_eje_mens'=>'',
							 'paga_eje_acum'=>'',
							 'paga_vari_abs_mens'=>'',
							 'paga_porc_vari_abs_mens'=>'',
							 'paga_vari_abs_acum'=>'',
							 'paga_porc_vari_abs_acum'=>'');
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
		 
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];

		$ls_cmbmes=$_GET["mes"];
		$ls_tipo=$_GET["tipo"];
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
		$ls_titulo=" <b> INFORMACION MENSUAL DE LA EJECUCION FINANCIERA </b>";       
//--------------------------------------------------------------------------------------------------------------------------------
   
     $lb_valido=$io_report->uf_spg_reporte_informacion_mensual_eje_fin($ldt_fecdes,$ldt_fechas,$ls_tipo);

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
		uf_print_encabezado_pagina($ls_titulo,"(En Bolivar Fuerte)",$ls_mes,$io_pdf); // Imprimimos el encabezado de la página
 	    $io_pdf->ezStartPageNumbers(980,40,10,'','',1); // Insertar el número de página
		$io_pdf->transaction('start'); // Iniciamos la transacción
		$thisPageNum=$io_pdf->ezPageCount;
		$li_tot=$io_report->dts_reporte->getRowCount("codigo");
	    $ld_total_programado_mensual   = 0;
		$ld_total_programado_acumulado = 0;
		$ld_total_ejecutado_mens_comp  = 0;
		$ld_total_ejecutado_mens_caus  = 0;
		$ld_total_ejecutado_mens_paga  = 0;
		$ld_total_ejecutado_acum_comp  = 0;
		$ld_total_ejecutado_acum_caus  = 0;
		$ld_total_ejecutado_acum_paga  = 0;
		$ld_total_variacion_mens_comp  = 0;
	    $ld_total_variacion_acum_comp  = 0;
	    $ld_total_variacion_mens_caus  = 0;
	    $ld_total_variacion_acum_caus  = 0;
	    $ld_total_variacion_mens_paga  = 0;
	    $ld_total_variacion_acum_paga  = 0;
		$ls_mesdes = "";	
		$thisPageNum=$io_pdf->ezPageCount;
		for($z=1;$z<=$li_tot;$z++)
		{		
			
			$ls_codigo="";
			$ls_denominacion="";
			$ls_spg_cuenta = "";
	        $ls_partida="";
			$ls_generica="";
			$ls_especifica="";
			$ls_subesp="";
			$ls_status="";
			$ld_programado_mensual   = 0;
		    $ld_programado_acumulado = 0;
		    $ld_ejecutado_mens_comp  = 0;
		    $ld_ejecutado_mens_caus  = 0;
			$ld_ejecutado_mens_paga  = 0;
			$ld_ejecutado_acum_comp  = 0;
		    $ld_ejecutado_acum_caus  = 0;
			$ld_ejecutado_acum_paga  = 0;
			$ld_variacion_mens_comp  = 0;
			$ld_variacion_acum_comp  = 0;
			$ld_variacion_mens_caus  = 0;
			$ld_variacion_acum_caus  = 0;
			$ld_variacion_mens_paga  = 0;
			$ld_variacion_acum_paga  = 0;
			

				  $ls_codigo                 = trim($io_report->dts_reporte->data["codigo"][$z]);
				  $ls_denominacion           = trim($io_report->dts_reporte->data["denominacion"][$z]);
				  $ls_spg_cuenta             = trim($io_report->dts_reporte->data["spg_cuenta"][$z]);
				  $io_function_report->uf_get_spg_cuenta($ls_spg_cuenta,$ls_partida,$ls_generica,$ls_especifica,$ls_subesp);
				  $ls_status                 = trim($io_report->dts_reporte->data["status"][$z]);
				  $ld_programado_mensual     = $io_report->dts_reporte->data["programado_mensual"][$z];
				  $ld_programado_acumulado   = $io_report->dts_reporte->data["programado_acumulado"][$z];
				  $ld_ejecutado_mens_comp    = $io_report->dts_reporte->data["ejecutado_mens_comp"][$z];
				  $ld_ejecutado_mens_caus    = $io_report->dts_reporte->data["ejecutado_mens_caus"][$z];
				  $ld_ejecutado_mens_paga    = $io_report->dts_reporte->data["ejecutado_mens_paga"][$z];
				  $ld_ejecutado_acum_comp    = $io_report->dts_reporte->data["ejecutado_acum_comp"][$z];
				  $ld_ejecutado_acum_caus    = $io_report->dts_reporte->data["ejecutado_acum_caus"][$z];
				  $ld_ejecutado_acum_paga    = $io_report->dts_reporte->data["ejecutado_acum_paga"][$z];
				  $ld_variacion_mens_comp    = abs($ld_programado_mensual - $ld_ejecutado_mens_comp);
				  $ld_variacion_acum_comp    = abs($ld_programado_acumulado - $ld_ejecutado_acum_comp);
				  $ld_variacion_mens_caus    = abs($ld_programado_mensual - $ld_ejecutado_mens_caus);
				  $ld_variacion_acum_caus    = abs($ld_programado_acumulado - $ld_ejecutado_acum_caus);
				  $ld_variacion_mens_paga    = abs($ld_programado_mensual - $ld_ejecutado_mens_paga);
				  $ld_variacion_acum_paga    = abs($ld_programado_acumulado - $ld_ejecutado_acum_caus);
				  
				  if (($ls_status == "C")&&($ls_tipo == "D"))
				  {
				   $ld_total_programado_mensual   = $ld_total_programado_mensual + $ld_programado_mensual;
				   $ld_total_programado_acumulado = $ld_total_programado_acumulado + $ld_programado_acumulado;
				   $ld_total_ejecutado_mens_comp  = $ld_total_ejecutado_mens_comp + $ld_ejecutado_mens_comp;
				   $ld_total_ejecutado_mens_caus  = $ld_total_ejecutado_mens_caus + $ld_ejecutado_mens_caus;
				   $ld_total_ejecutado_mens_paga  = $ld_total_ejecutado_mens_paga + $ld_ejecutado_mens_paga;
				   $ld_total_ejecutado_acum_comp  = $ld_total_ejecutado_acum_comp + $ld_ejecutado_acum_comp;
			       $ld_total_ejecutado_acum_caus  = $ld_total_ejecutado_acum_caus + $ld_ejecutado_acum_caus;
				   $ld_total_ejecutado_acum_paga  = $ld_total_ejecutado_acum_paga + $ld_ejecutado_acum_paga;
				   $ld_total_variacion_mens_comp  = $ld_total_variacion_mens_comp + $ld_variacion_mens_comp;
				   $ld_total_variacion_acum_comp  = $ld_total_variacion_acum_comp + $ld_variacion_acum_comp;
				   $ld_total_variacion_mens_caus  = $ld_total_variacion_mens_caus + $ld_variacion_mens_caus;
				   $ld_total_variacion_acum_caus  = $ld_total_variacion_acum_caus + $ld_variacion_acum_caus;
				   $ld_total_variacion_mens_paga  = $ld_total_variacion_mens_paga + $ld_variacion_mens_paga;
				   $ld_total_variacion_acum_paga  = $ld_total_variacion_acum_paga + $ld_variacion_acum_paga;
				  }
				  elseif(($ls_status == "S")&&($ls_tipo == "C"))
				  {
				   $ld_total_programado_mensual   = $ld_total_programado_mensual + $ld_programado_mensual;
				   $ld_total_programado_acumulado = $ld_total_programado_acumulado + $ld_programado_acumulado;
				   $ld_total_ejecutado_mens_comp  = $ld_total_ejecutado_mens_comp + $ld_ejecutado_mens_comp;
				   $ld_total_ejecutado_mens_caus  = $ld_total_ejecutado_mens_caus + $ld_ejecutado_mens_caus;
				   $ld_total_ejecutado_mens_paga  = $ld_total_ejecutado_mens_paga + $ld_ejecutado_mens_paga;
				   $ld_total_ejecutado_acum_comp  = $ld_total_ejecutado_acum_comp + $ld_ejecutado_acum_comp;
			       $ld_total_ejecutado_acum_caus  = $ld_total_ejecutado_acum_caus + $ld_ejecutado_acum_caus;
				   $ld_total_ejecutado_acum_paga  = $ld_total_ejecutado_acum_paga + $ld_ejecutado_acum_paga;
				   $ld_total_variacion_mens_comp  = $ld_total_variacion_mens_comp + $ld_variacion_mens_comp;
				   $ld_total_variacion_acum_comp  = $ld_total_variacion_acum_comp + $ld_variacion_acum_comp;
				   $ld_total_variacion_mens_caus  = $ld_total_variacion_mens_caus + $ld_variacion_mens_caus;
				   $ld_total_variacion_acum_caus  = $ld_total_variacion_acum_caus + $ld_variacion_acum_caus;
				   $ld_total_variacion_mens_paga  = $ld_total_variacion_mens_paga + $ld_variacion_mens_paga;
				   $ld_total_variacion_acum_paga  = $ld_total_variacion_acum_paga + $ld_variacion_acum_paga;
				  } 
				  
			      

				  $ld_programado_mensual   = number_format($ld_programado_mensual,2,",",".");
				  $ld_programado_acumulado = number_format($ld_programado_acumulado,2,",",".");
				  $ld_ejecutado_mens_comp  = number_format($ld_ejecutado_mens_comp,2,",",".");
				  $ld_ejecutado_mens_caus  = number_format($ld_ejecutado_mens_caus,2,",",".");
				  $ld_ejecutado_mens_paga  = number_format($ld_ejecutado_mens_paga,2,",",".");
				  $ld_ejecutado_acum_comp  = number_format($ld_ejecutado_acum_comp,2,",",".");
				  $ld_ejecutado_acum_caus  = number_format($ld_ejecutado_acum_caus,2,",",".");
				  $ld_ejecutado_acum_paga  = number_format($ld_ejecutado_acum_paga,2,",",".");
				  $ld_variacion_mens_comp  = number_format($ld_variacion_mens_comp,2,",",".");
				  $ld_variacion_acum_comp  = number_format($ld_variacion_acum_comp,2,",",".");
				  $ld_variacion_mens_caus  = number_format($ld_variacion_mens_caus,2,",",".");
				  $ld_variacion_acum_caus  = number_format($ld_variacion_acum_caus,2,",",".");
				  $ld_variacion_mens_paga  = number_format($ld_variacion_mens_paga,2,",",".");
				  $ld_variacion_acum_paga  = number_format($ld_variacion_acum_paga,2,",",".");
				
				  $la_data[$z]=array('codigo'=>$ls_codigo,
									 'denominacion'=>$ls_denominacion,
									 'partida'=>$ls_partida,
									 'generica'=>$ls_generica,
									 'especifica'=>$ls_especifica,
									 'subespecifica'=>$ls_subesp,
									 'comp_prog_mensual'=>$ld_programado_mensual,
									 'comp_prog_acum'=>$ld_programado_acumulado,
									 'comp_eje_mens'=>$ld_ejecutado_mens_comp,
									 'comp_eje_acum'=>$ld_ejecutado_acum_comp,
									 'comp_vari_abs_mens'=>$ld_variacion_mens_comp,
									 'comp_porc_vari_abs_mens'=>'',
									 'comp_vari_abs_acum'=>$ld_variacion_acum_comp,
									 'comp_porc_vari_abs_acum'=>'',
									 'caus_prog_mensual'=>$ld_programado_mensual,
									 'caus_prog_acum'=>$ld_programado_acumulado,
									 'caus_eje_mens'=>$ld_ejecutado_mens_caus,
									 'caus_eje_acum'=>$ld_ejecutado_acum_caus,
									 'caus_vari_abs_mens'=>$ld_variacion_mens_caus,
									 'caus_porc_vari_abs_mens'=>'',
									 'caus_vari_abs_acum'=>$ld_variacion_acum_caus,
									 'caus_porc_vari_abs_acum'=>'',
									 'paga_prog_mensual'=>$ld_programado_mensual,
									 'paga_prog_acum'=>$ld_programado_acumulado,
									 'paga_eje_mens'=>$ld_ejecutado_mens_paga,
									 'paga_eje_acum'=>$ld_ejecutado_acum_paga,
									 'paga_vari_abs_mens'=>$ld_variacion_mens_paga,
									 'paga_porc_vari_abs_mens'=>'',
									 'paga_vari_abs_acum'=>$ld_variacion_acum_paga,
									 'paga_porc_vari_abs_acum'=>'');
					  							 						   
			}//for
			  $ld_total_programado_mensual   = number_format($ld_total_programado_mensual,2,",",".");
			  $ld_total_programado_acumulado = number_format($ld_total_programado_acumulado,2,",",".");
			  $ld_total_ejecutado_mens_comp  = number_format($ld_total_ejecutado_mens_comp,2,",",".");
			  $ld_total_ejecutado_mens_caus  = number_format($ld_total_ejecutado_mens_caus,2,",",".");
			  $ld_total_ejecutado_mens_paga  = number_format($ld_total_ejecutado_mens_paga,2,",",".");
			  $ld_total_ejecutado_acum_comp  = number_format($ld_total_ejecutado_acum_comp,2,",",".");
			  $ld_total_ejecutado_acum_caus  = number_format($ld_total_ejecutado_acum_caus,2,",",".");
			  $ld_total_ejecutado_acum_paga  = number_format($ld_total_ejecutado_acum_paga,2,",",".");
			  $ld_total_variacion_mens_comp  = number_format($ld_total_variacion_mens_comp,2,",",".");
			  $ld_total_variacion_acum_comp  = number_format($ld_total_variacion_acum_comp,2,",",".");
			  $ld_total_variacion_mens_caus  = number_format($ld_total_variacion_mens_caus,2,",",".");
			  $ld_total_variacion_acum_caus  = number_format($ld_total_variacion_acum_caus,2,",",".");
			  $ld_total_variacion_mens_paga  = number_format($ld_total_variacion_mens_paga,2,",",".");
			  $ld_total_variacion_acum_paga  = number_format($ld_total_variacion_acum_paga,2,",",".");
			
			$la_data_tot[1]=array(	 'totales'=>"TOTALES",
								  	 'comp_prog_mensual'=>$ld_total_programado_mensual,
									 'comp_prog_acum'=>$ld_total_programado_acumulado,
									 'comp_eje_mens'=>$ld_total_ejecutado_mens_comp,
									 'comp_eje_acum'=>$ld_total_ejecutado_acum_comp,
									 'comp_vari_abs_mens'=>$ld_total_variacion_mens_comp,
									 'comp_porc_vari_abs_mens'=>'',
									 'comp_vari_abs_acum'=>$ld_total_variacion_acum_comp,
									 'comp_porc_vari_abs_acum'=>'',
									 'caus_prog_mensual'=>$ld_total_programado_mensual,
									 'caus_prog_acum'=>$ld_total_programado_acumulado,
									 'caus_eje_mens'=>$ld_total_ejecutado_mens_caus,
									 'caus_eje_acum'=>$ld_total_ejecutado_acum_caus,
									 'caus_vari_abs_mens'=>$ld_total_variacion_mens_caus,
									 'caus_porc_vari_abs_mens'=>'',
									 'caus_vari_abs_acum'=>$ld_total_variacion_acum_caus,
									 'caus_porc_vari_abs_acum'=>'',
									 'paga_prog_mensual'=>$ld_total_programado_mensual,
									 'paga_prog_acum'=>$ld_total_programado_acumulado,
									 'paga_eje_mens'=>$ld_total_ejecutado_mens_paga,
									 'paga_eje_acum'=>$ld_total_ejecutado_acum_paga,
									 'paga_vari_abs_mens'=>$ld_total_variacion_mens_paga,
									 'paga_porc_vari_abs_mens'=>'',
									 'paga_vari_abs_acum'=>$ld_total_variacion_acum_paga,
									 'paga_porc_vari_abs_acum'=>'');

            $io_encabezado=$io_pdf->openObject();
			uf_print_titulo_reporte($io_encabezado,"",$li_ano,$ls_mes,"",$io_pdf);
			$io_pdf->ezSetCmMargins(8.1,3,3,3);
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		    uf_print_pie_cabecera($la_data_tot,$io_pdf);
			unset($la_data);
			unset($la_data_tot);		
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