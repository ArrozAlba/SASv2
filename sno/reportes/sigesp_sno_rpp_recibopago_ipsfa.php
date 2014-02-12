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
	ini_set('memory_limit','512M');
	ini_set('max_execution_time','0');

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo,$ai_tipo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Arreglo de las variables de seguridad
		//	    		   as_desnom // Arreglo de las variables de seguridad
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/09/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_recibopago.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_hrecibopago.php",$ls_descripcion,$ls_codnom);
		}
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina1($as_titulo,$as_desnom,$as_periodo,$as_desuniadm,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina1
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/09/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->Rectangle(35,660,550,100);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,680,$_SESSION["ls_width"],$_SESSION["ls_height"]);
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);			
		$tm=366-($li_tm/2);
		$as_titulo1='MINISTERIO DEL PODER POPULAR PARA LA DEFENSA';
		$io_pdf->addText($tm,750,7,$as_titulo1); // Agregar el título
		$tm=390-($li_tm/2);
		$as_titulo2='INSTITUTO DE PREVISIÓN SOCIAL DE LA';
		$io_pdf->addText($tm,740,7,$as_titulo2); // Agregar el título
		$tm=425-($li_tm/2);
		$as_titulo3='FUERZA ARMADA';
		$io_pdf->addText($tm,730,7,$as_titulo3); // Agregar el título
		$tm=425-($li_tm/2);
		$io_pdf->addText(300,705,7,'UBICACIÓN'); // Agregar el título	
		$io_pdf->Rectangle(230,700,200,14);
		$io_pdf->addText(445,705,7,'FECHA'); // Agregar el título	
		$io_pdf->Rectangle(430,700,50,14);
		$io_pdf->addText(490,705,7,'NÚMERO'); // Agregar el título	
		$io_pdf->Rectangle(480,700,50,14);
		$io_pdf->addText(235,690,7,$as_desuniadm); // Agregar el título
		$io_pdf->Rectangle(230,686,200,14);
		$io_pdf->addText(440,690,7,date("d/m/Y")); // Agregar el título
		$io_pdf->Rectangle(430,686,50,14);
		$io_pdf->Rectangle(480,686,50,14);	
		$tm=420-($li_tm/2);	
		$io_pdf->addText($tm,665,8,'DATOS DEL PERSONALES'); // Agregar el título			
	}// end function uf_print_encabezado_pagina1

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera1($as_codper,$as_cedper,$as_nomper,$as_descar,$as_codcueban,$as_desuniadm,$ad_fecingper,$ai_sueper,
							    $as_desnom,$as_tipo,$as_banco,$as_compensacion,$as_decreto_4446,$as_decreto_6660,$as_prima,
								$total,$as_agencia,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera1
		//		   Access: private 
		//	    Arguments: as_cedper // Cédula del personal
		//	    		   as_nomper // Nombre del personal
		//	    		   as_descar // Decripción del cargo
		//	    		   io_cabecera // objeto cabecera
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/09/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$io_pdf->saveState();
		$io_pdf->ezSetDy(-30);
		//-------------------------------------------------------------------------------------------------------------------
		$la_data_1[1]=array('cedula'=>'CÉDULA',
						    'nombre'=>'APELLIDOS Y NOMBRES', 
						    'cargo'=>'CARGO');
		$la_columna=array('cedula'=>'',
						        'nombre'=>'', 
						        'cargo'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'xPos'=>315,
						 'fontSize' => 7, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'rowGap' => 0.5 ,		 
						 'cols'=>array('cedula'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>225),
									   'cargo'=>array('justification'=>'center','width'=>225))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_1,$la_columna,'',$la_config);	
		unset($la_data_1);
		unset($la_columna);
		unset($la_config);	
		//-------------------------------------------------------------------------------------------------------------------
		$la_data_2[1]=array('cedula'=>$as_cedper,
						  'nombre'=>$as_nomper,
						  'cargo'=>$as_descar);
		$la_columna=array('cedula'=>'',
						        'nombre'=>'', 
						        'cargo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'xPos'=>315,
						 'fontSize' => 7, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'rowGap' => 0.5 ,
						 'cols'=>array('cedula'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>225),
									   'cargo'=>array('justification'=>'center','width'=>225))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_2,$la_columna,'',$la_config);	
		unset($la_data_2);
		unset($la_columna);
		unset($la_config);
		
		$la_data_3[1]=array('dato'=>'');
		$la_data_3[2]=array('dato'=>'DATOS DE PAGO');
		$la_data_3[3]=array('dato'=>'');
		$la_columna=array('dato'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'xPos'=>315,
						 'fontSize' => 7, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'rowGap' => 0.5 ,
						 'cols'=>array('dato'=>array('justification'=>'center','width'=>550))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_3,$la_columna,'',$la_config);	
		
		unset($la_data_3);
		unset($la_columna);
		unset($la_config);
		
		$la_data_4[1]=array('banco'=>'BANCO',
						    'sucursal'=>'SUCURSAL',
						    'cuenta'=>'NÚMERO DE CUENTA',
							'tipo'=>'TIPO DE CUENTA');
		$la_columna=  array('banco'=>'',
						    'sucursal'=>'',
						    'cuenta'=>'',
							'tipo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'xPos'=>315,
						 'fontSize' => 7, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'rowGap' => 0.5 ,
						 'cols'=>array('banco'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'sucursal'=>array('justification'=>'center','width'=>190),
									   'cuenta'=>array('justification'=>'center','width'=>110),
									   'tipo'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_4,$la_columna,'',$la_config);	
		unset($la_data_4);
		unset($la_columna);
		unset($la_config);
		
		$la_data_5[1]=array('banco'=>$as_banco,
						    'sucursal'=>$as_agencia,
						    'cuenta'=>$as_codcueban,
							'tipo'=>$as_tipo);
		$la_columna=  array('banco'=>'',
						    'sucursal'=>'',
						    'cuenta'=>'',
							'tipo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'xPos'=>315,
						 'fontSize' => 7, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'rowGap' => 0.5 ,
						 'cols'=>array('banco'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'sucursal'=>array('justification'=>'center','width'=>190),
									   'cuenta'=>array('justification'=>'center','width'=>110),
									   'tipo'=>array('justification'=>'center','width'=>100)));// Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_5,$la_columna,'',$la_config);	
		unset($la_data_5);
		unset($la_columna);
		unset($la_config);
		
		$la_data_6[1]=array('dato'=>'');
		$la_data_6[2]=array('dato'=>$as_desnom);
		$la_data_6[3]=array('dato'=>'');
		$la_columna=array('dato'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'xPos'=>315,
						 'fontSize' => 7, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'rowGap' => 0.5 ,
						 'cols'=>array('dato'=>array('justification'=>'center','width'=>550))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_6,$la_columna,'',$la_config);	
		unset($la_data_6);
		unset($la_columna);
		unset($la_config);
		
		$la_data_7[1]=array('sueldo'=>'SUELDO BASE',
						    'ajuste1'=>'AJUSTE 2902',
						    'ajuste2'=>'AJUSTE 6660',
							'compensacion'=>'COMPENSACIÓN',
							'otros'=>'OTROS',
							'total'=>'TOTAL');
		$la_columna= array('sueldo'=>'',
						    'ajuste1'=>'',
						    'ajuste2'=>'',
							'compensacion'=>'',
							'otros'=>'',
							'total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'xPos'=>315,
						 'fontSize' => 7, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'rowGap' => 0.5 ,
						 'cols'=>array('sueldo'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'ajuste1'=>array('justification'=>'center','width'=>90),
									   'ajuste2'=>array('justification'=>'center','width'=>90),
									   'compensacion'=>array('justification'=>'center','width'=>100),
									   'otros'=>array('justification'=>'center','width'=>90),
									   'total'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_7,$la_columna,'',$la_config);	
		unset($la_data_7);
		unset($la_columna);
		unset($la_config);
		$la_data_8[1]=array('sueldo'=>$ai_sueper,
						    'ajuste1'=>$as_decreto_4446,
						    'ajuste2'=>$as_decreto_6660,
							'compensacion'=>$as_compensacion,
							'otros'=>$as_prima,
							'total'=>$total);
		$la_columna= array('sueldo'=>'',
						    'ajuste1'=>'',
						    'ajuste2'=>'',
							'compensacion'=>'',
							'otros'=>'',
							'total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'xPos'=>315,
						 'fontSize' => 7, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'rowGap' => 0.5 ,
						 'cols'=>array('sueldo'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'ajuste1'=>array('justification'=>'center','width'=>90),
									   'ajuste2'=>array('justification'=>'center','width'=>90),
									   'compensacion'=>array('justification'=>'center','width'=>100),
									   'otros'=>array('justification'=>'center','width'=>90),
									   'total'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_8,$la_columna,'',$la_config);	
		unset($la_data_8);
		unset($la_columna);
		unset($la_config);
		$io_pdf->ezSetDy(-5);				
		$la_data[1]=array('codigo'=>'<b>CÓDIGO</b>', 
						  'denominacion'=>'<b>DESCRIPCIÓN DEL CONCEPTO</b>',
						  'cuotas'=>'<b>CUOTAS / PLAZO</b>', 
						  'asignacion'=>'<b>ASIGNACIONES</b>',
						  'deduccion'=>'<b>DEDUCCIONES</b>' );
		$la_columna=array('codigo'=>'<b>CÓDIGO</b>', 
						  'denominacion'=>'<b>DENOMINACIÓN</b>',
						  'cuotas'=>'<b>CUOTAS</b>', 
						  'asignacion'=>'<b>ASIGNACIÓN</b>',
						  'deduccion'=>'<b>DEDUCCIÓN</b>' );
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xPos'=>315,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>205), // Justificación y ancho de la columna
									   'cuotas'=>array('justification'=>'center','width'=>100),
						 			   'asignacion'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);		
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/09/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('codigo'=>'<b>CÓDIGO</b>', 
						  'denominacion'=>'<b>DENOMINACIÓN</b>',
						  'cuotas'=>'<b>CUOTAS</b>', 
						  'asignacion'=>'<b>ASIGNACIÓN</b>',
						  'deduccion'=>'<b>DEDUCCIÓN</b>' );
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xPos'=>315,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>205), // Justificación y ancho de la columna
									   'cuotas'=>array('justification'=>'center','width'=>100),
						 			   'asignacion'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera1($ai_toting,$ai_totded,$ai_totnet,$as_codcueban,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera1
		//		   Access: private 
		//	    Arguments: ai_toting // Total Ingresos
		//	   			   ai_totded // Total Deducciones
		//	   			   ai_totnet // Total Neto
		//	   			   as_codcueban // Codigo cuenta bancaria
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/09/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares, $io_monedabsf, $ls_tiporeporte;
		
		$la_data[1]=array('total'=>'TOTALES',
						  'asignacion'=>$ai_toting,
						  'deduccion'=>$ai_totded);
		$la_columna=array('total'=>'', 
						  'asignacion'=>'',
						  'deduccion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xPos'=>315,
						 'cols'=>array('total'=>array('justification'=>'center','width'=>370), // Justificación y ancho de la columna						 			   
						 			   'asignacion'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		
		if($ls_tiporeporte==0)
		{
			$ai_totnet=str_replace(".","",$ai_totnet);
			$ai_totnet=str_replace(",",".",$ai_totnet);
			$li_montobsf=$io_monedabsf->uf_convertir_monedabsf($ai_totnet,$_SESSION["la_empresa"]["candeccon"],$_SESSION["la_empresa"]["tipconmon"],1000,$_SESSION["la_empresa"]["redconmon"]);
			$li_montobsf=number_format($li_montobsf,2,",",".");
		}
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
   function uf_print_quincenas($as_priqui,$as_sequi,&$io_pdf)
   {
        $io_pdf->ezSetDy(-30); 
		$la_data_q[1]=array('priqui'=>'NETO 1RA. QUINCENA:                                                   '.$as_priqui,
						    'segpri'=>'NETO 2DA. QUINCENA:                                                   '.$as_sequi);
		$la_columna=array('priqui'=>'',
						  'segpri'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xPos'=>315,
						 'cols'=>array('priqui'=>array('justification'=>'left','width'=>275), // Justificación y ancho de la columna
						 			   'segpri'=>array('justification'=>'left','width'=>275))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_q,$la_columna,'',$la_config);
		unset($la_data_q);
		unset($la_columna);
		unset($la_config);  
   }

//--------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	$ls_tiporeporte="0";
	$ls_bolivares ="Bs.";
	if($_SESSION["la_nomina"]["tiponomina"]=="NORMAL")
	{
		require_once("sigesp_sno_class_report.php");
		$io_report=new sigesp_sno_class_report();
		$li_tipo=1;
	}
	if($_SESSION["la_nomina"]["tiponomina"]=="HISTORICA")
	{
		require_once("sigesp_sno_class_report_historico.php");
		$io_report=new sigesp_sno_class_report_historico();
		$li_tipo=2;
	}	
	require_once("../../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
	$io_monedabsf=new sigesp_c_reconvertir_monedabsf();				
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"]; 
	$li_adelanto=$_SESSION["la_nomina"]["adenom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$lsrepublica="<b>REPUBLICA BOLIVARIANA DE VENEZUELA</b>";
	$lsrepublica2="<b>INSTITUTO UNIVERSITARIO DE TECNOLOGIA CARIPITO</b>";
	$ls_titulo="<b>".$_SESSION["la_empresa"]["nombre"]."</b>";
	$ls_periodo="Periodo: <b>".$ls_peractnom."</b> del <b>".$ld_fecdesper."</b> al <b>".$ld_fechasper."</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_coduniadm=$io_fun_nomina->uf_obtenervalor_get("coduniadm","");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_conceptop2=$io_fun_nomina->uf_obtenervalor_get("conceptop2",""); 
	$ls_conceptoreporte=$io_fun_nomina->uf_obtenervalor_get("conceptoreporte",""); 
	$ls_tituloconcepto=$io_fun_nomina->uf_obtenervalor_get("tituloconcepto","");
	$ls_quincena=$io_fun_nomina->uf_obtenervalor_get("quincena","-");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_codubifis=$io_fun_nomina->uf_obtenervalor_get("codubifis","");
	$ls_codpai=$io_fun_nomina->uf_obtenervalor_get("codpai","");
	$ls_codest=$io_fun_nomina->uf_obtenervalor_get("codest","");
	$ls_codmun=$io_fun_nomina->uf_obtenervalor_get("codmun","");
	$ls_codpar=$io_fun_nomina->uf_obtenervalor_get("codpar","");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_recibopago_personal($ls_codperdes,$ls_codperhas,$ls_coduniadm,$ls_conceptocero,$ls_conceptop2,
													  $ls_conceptoreporte,$ls_codubifis,$ls_codpai,$ls_codest,$ls_codmun,$ls_codpar,
													  $ls_subnomdes,$ls_subnomhas,$ls_orden); // Cargar el DS con los datos de la cabecera del reporte
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
		$io_pdf->ezSetCmMargins(4,1,1,2); // Configuración de los margenes en centímetros		
		$li_totrow=$io_report->rs_data->RecordCount();
		$li_reg=1;
		$ls_tipo="";
		$li_i=1;
		while((!$io_report->rs_data->EOF)&&($lb_valido))
		{  
		    $li_toting=0;
			$li_totded=0;
			$ls_priqui="";
			$ls_segqui="";
			$ls_codper=$io_report->rs_data->fields["codper"];
			$ls_cedper=$io_report->rs_data->fields["cedper"];
			$ls_nomper=$io_report->rs_data->fields["apeper"].", ".$io_report->rs_data->fields["nomper"];
			$ls_descar=$io_report->rs_data->fields["descar"];
			$ls_codcar=$io_report->rs_data->fields["codcar"];
			$ls_descasicar=$io_report->rs_data->fields["descasicar"];
			
			if ($ls_descasicar!="")
			{
				$ls_descar=$ls_codcar." ".$ls_descasicar;
			}
			else
			{
				$ls_descar=$ls_codcar." ".$ls_descar;
			}
			
			$ls_codcueban=$io_report->rs_data->fields["codcueban"];
			$ls_banco=$io_report->rs_data->fields["banco"];
			$ls_tipoctaban=$io_report->rs_data->fields["tipcuebanper"];
			if ($ls_tipoctaban=="A")
			{
			  $ls_tipo="CTA. AHORRO";
			}
			elseif ($ls_tipoctaban=="A")
			{
			 $ls_tipo='CTA. CORRIENTE';
			}
			
			$ls_desuniadm=$io_report->rs_data->fields["desuniadm"];
			$ld_fecingper=$io_funciones->uf_convertirfecmostrar($io_report->rs_data->fields["fecingper"]);
			$ls_tiponom=$io_report->rs_data->fields["tiponom"];
			if ($ls_tiponom =='3' || $ls_tiponom =='4')
			{
				$li_sueper=$io_report->rs_data->fields["sueobr"];
			}
			else
			{
				$li_sueper=$io_report->rs_data->fields["sueper"];
			}
			
			$ls_pagbanper=$io_report->rs_data->fields["pagbanper"];
			$ls_pagefeper=$io_report->rs_data->fields["pagefeper"];
			$li_total=$io_report->rs_data->fields["total"];
			$ls_agencia=$io_report->rs_data->fields["agencia"];
			//---------------------------CONCEPTOS--------------------------------------------------------------------------------
			     $io_report->uf_obtener_valor_concepto($ls_codper,'0000000003',&$ls_valor);//////compensación
				 $ls_compensacion=$ls_valor;
				 $io_report->uf_obtener_valor_concepto($ls_codper,'0000000012',&$ls_valor);//////decreto 5318
				 $ls_prima_prof=$ls_valor;
				 $io_report->uf_obtener_valor_concepto($ls_codper,'0000000005',&$ls_valor);//////prima antiguedad empleado
				 $ls_prima_ant=$ls_valor;
				 $io_report->uf_obtener_valor_concepto($ls_codper,'0000000013',&$ls_valor);//////prima antiguedad empleado
				 $ls_concepto13=$ls_valor;
				 $total=$li_sueper+$ls_compensacion+$ls_prima_prof+$ls_prima_ant+$ls_concepto13;					 
			//--------------------------------------------------------------------------------------------------------------------
			uf_print_encabezado_pagina1($ls_titulo,$ls_desnom,$ls_periodo,$ls_desuniadm,$io_pdf); // Imprimimos el encabezado de la página
			if($li_reg==1)
			{   
				uf_print_cabecera1($ls_codper,$ls_cedper,$ls_nomper,$ls_descar,$ls_codcueban,$ls_desuniadm,$ld_fecingper,
								   number_format($li_sueper,2,",","."),$ls_desnom,$ls_tipo,$ls_banco,
								   $io_fun_nomina->uf_formatonumerico(abs($ls_compensacion)),
								   $io_fun_nomina->uf_formatonumerico(abs(0)),
								   $io_fun_nomina->uf_formatonumerico(abs($ls_prima_prof)),
								   $io_fun_nomina->uf_formatonumerico(abs($ls_prima_ant+$ls_concepto13)),
								   $io_fun_nomina->uf_formatonumerico(abs($total)),
								   $ls_agencia,$io_pdf); // Imprimimos la cabecera del registro
			}			
			
			$lb_valido=$io_report->uf_recibopago_conceptopersonal($ls_codper,$ls_conceptocero,$ls_conceptop2,
																  $ls_conceptoreporte,$ls_tituloconcepto,$ls_quincena); // Obtenemos el detalle del reporte
			
			if($lb_valido)
			{
				$li_totrow_det=$io_report->rs_data_detalle->RecordCount();
				$li_asig=0;
				$li_dedu=0;
				$li_apor=0;
				if($li_adelanto==1)// Utiliza el adelanto de quincena
				{
					switch($ls_quincena)
					{
						case "1": // primera quincena;
							$li_asig=$li_asig+1;
							$ls_codconc="----------";
							$ls_nomcon="ADELANTO 1ra QUINCENA";
							$li_valsal=round($li_total/2,2);
							$li_toting=$li_toting+$li_valsal;
							$li_valsal=$io_fun_nomina->uf_formatonumerico($li_valsal);
							$la_data_a[$li_asig]=array('codigo'=>$ls_codconc,'denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
							break;
							
						case "2": // segunda quincena;
							while(!$io_report->rs_data_detalle->EOF)
							{
								$ls_tipsal=rtrim($io_report->rs_data_detalle->fields["tipsal"]);
								if(($ls_tipsal=="A") || ($ls_tipsal=="V1") || ($ls_tipsal=="V2") || ($ls_tipsal=="R")) // Buscamos las asignaciones
								{
									$li_asig=$li_asig+1;
									$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
									$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
									if ($ls_tipsal!="R")
									{
										$li_toting=$li_toting+abs($io_report->rs_data_detalle->fields["valsal"]);
									}									
									$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valsal"]));
									$ls_repconsunicon=$io_report->rs_data_detalle->fields["repconsunicon"];
									$ls_consunicon=$io_report->rs_data_detalle->fields["consunicon"];
									$ls_cuota="";
									if (($ls_repconsunicon=='1')&&($ls_consunicon!=""))
									{
										$io_report->uf_buscar_cuotas($ls_consunicon,$ls_codper,$ls_cuota);
									}
									
									$la_data_a[$li_asig]=array('codigo'=>$ls_codconc,'denominacion'=>$ls_nomcon,
									                           'valor'=>$li_valsal,'cuota'=>$ls_cuota);
								}
								if(($ls_tipsal=="D") || ($ls_tipsal=="V2") || ($ls_tipsal=="W2") ||
								   ($ls_tipsal=="P1") || ($ls_tipsal=="V3") || ($ls_tipsal=="W3") ) // Buscamos las deducciones
								{
									$li_dedu=$li_dedu+1;
									$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
									$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
									$li_totded=$li_totded+abs($io_report->DS_detalle->data["valsal"]);
									$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valsal"]));
									$ls_repconsunicon=$io_report->rs_data_detalle->fields["repconsunicon"];
									$ls_consunicon=$io_report->rs_data_detalle->fields["consunicon"];
									$ls_cuota="";
									if (($ls_repconsunicon=='1')&&($ls_consunicon!=""))
									{
										$io_report->uf_buscar_cuotas($ls_consunicon,$ls_codper,$ls_cuota);
									}
									$la_data_d[$li_dedu]=array('codigo'=>$ls_codconc,'denominacion'=>$ls_nomcon,
									                           'valor'=>$li_valsal,'cuota'=>$ls_cuota);
								}
								if(($ls_tipsal=="P2") || ($ls_tipsal=="V4") || ($ls_tipsal=="W4") ) // Buscamos los aportes
								{
									$li_apor=$li_apor+1;
									$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
									$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];									
									$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valsal"]));
									$ls_repconsunicon=$io_report->rs_data_detalle->fields["repconsunicon"];
									$ls_consunicon=$io_report->rs_data_detalle->fields["consunicon"];
									$ls_cuota="";
									if (($ls_repconsunicon=='1')&&($ls_consunicon!=""))
									{
										$io_report->uf_buscar_cuotas($ls_consunicon,$ls_codper,$ls_cuota);
									}
									
									$la_data_p[$li_apor]=array('codigo'=>$ls_codconc,'denominacion'=>$ls_nomcon,'valor'=>$li_valsal,'cuota'=>$ls_cuota);
								}
								$io_report->rs_data_detalle->MoveNext();
							}
							$li_dedu=$li_dedu+1;
							$ls_codconc="----------";
							$ls_nomcon="ADELANTO 1ra QUINCENA";
							$li_valsal=round($li_total/2,2);
							$li_totded=$li_totded+$li_valsal;
							$li_valsal=$io_fun_nomina->uf_formatonumerico($li_valsal);
							$la_data_d[$li_dedu]=array('codigo'=>$ls_codconc,'denominacion'=>$ls_nomcon,'valor'=>$li_valsal);
							break;
							
						case "3": // Mes Completo;
							while(!$io_report->rs_data_detalle->EOF)
							{
								$ls_tipsal=rtrim($io_report->rs_data_detalle->fields["tipsal"]);
								if(($ls_tipsal=="A") || ($ls_tipsal=="V1") || ($ls_tipsal=="V2") || ($ls_tipsal=="R")) // Buscamos las asignaciones
								{
									$li_asig=$li_asig+1;
									$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
									$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
									if ($ls_tipsal!="R")
									{
										$li_toting=$li_toting+abs($io_report->rs_data_detalle->fields["valsal"]);
									}									
									$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data_detalle->fields["valsal"]));
									$ls_repconsunicon=$io_report->rs_data_detalle->fields["repconsunicon"];
									$ls_consunicon=$io_report->rs_data_detalle->fields["consunicon"];
									$ls_cuota="";
									if (($ls_repconsunicon=='1')&&($ls_consunicon!=""))
									{
										$io_report->uf_buscar_cuotas($ls_consunicon,$ls_codper,$ls_cuota);
									}
									
									$la_data_a[$li_asig]=array('codigo'=>$ls_codconc,'denominacion'=>$ls_nomcon,
									                           'valor'=>$li_valsal,'cuota'=>$ls_cuota);
								}
								if(($ls_tipsal=="D") || ($ls_tipsal=="V2") || ($ls_tipsal=="W2") || 
									($ls_tipsal=="P1") || ($ls_tipsal=="V3") || ($ls_tipsal=="W3") ) // Buscamos las deducciones
								{
									$li_dedu=$li_dedu+1;
									$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
									$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
									$li_totded=$li_totded+abs($io_report->DS_detalle->data["valsal"]);
									$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data_detalle->fields["valsal"]));
									$ls_repconsunicon=$io_report->rs_data_detalle->fields["repconsunicon"];
									$ls_consunicon=$io_report->rs_data_detalle->fields["consunicon"];
									$ls_cuota="";
									if (($ls_repconsunicon=='1')&&($ls_consunicon!=""))
									{
										$io_report->uf_buscar_cuotas($ls_consunicon,$ls_codper,$ls_cuota);
									}
									
									$la_data_d[$li_dedu]=array('codigo'=>$ls_codconc,'denominacion'=>$ls_nomcon,
									                           'valor'=>$li_valsal,'cuota'=>$ls_cuota);
								}
								if(($ls_tipsal=="P2") || ($ls_tipsal=="V4") || ($ls_tipsal=="W4") ) // Buscamos los aportes
								{
									$li_apor=$li_apor+1;
									$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
									$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
									$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data_detalle->fields["valsal"]));
									$ls_repconsunicon=$io_report->rs_data_detalle->fields["repconsunicon"];
									$ls_consunicon=$io_report->rs_data_detalle->fields["consunicon"];
									$ls_cuota="";
									if (($ls_repconsunicon=='1')&&($ls_consunicon!=""))
									{
										$io_report->uf_buscar_cuotas($ls_consunicon,$ls_codper,$ls_cuota);
									}
									
									$la_data_p[$li_apor]=array('codigo'=>$ls_codconc,'denominacion'=>$ls_nomcon,
									                           'valor'=>$li_valsal,'cuota'=>$ls_cuota);
								}
								$io_report->rs_data_detalle->MoveNext();
							}
							break;
					}
				}
				else// No utiliza adelanto de quincena
				{
					while(!$io_report->rs_data_detalle->EOF) 
					{
						$ls_tipsal=rtrim($io_report->rs_data_detalle->fields["tipsal"]);
						if(($ls_tipsal=="A") || ($ls_tipsal=="V1") || ($ls_tipsal=="V2") || ($ls_tipsal=="R")) // Buscamos las asignaciones
						{
							$li_asig=$li_asig+1;
							$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
							$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
							if ($ls_tipsal!="R")
							{
								$li_toting=$li_toting+abs($io_report->rs_data_detalle->fields["valsal"]);
							}									
							$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data_detalle->fields["valsal"]));
							$ls_repconsunicon=$io_report->rs_data_detalle->fields["repconsunicon"];
							$ls_consunicon=$io_report->rs_data_detalle->fields["consunicon"];
							$ls_cuota="";
							if (($ls_repconsunicon=='1')&&($ls_consunicon!=""))
							{
								$io_report->uf_buscar_cuotas($ls_consunicon,$ls_codper,$ls_cuota);
							}
							$la_data_a[$li_asig]=array('codigo'=>$ls_codconc,'denominacion'=>$ls_nomcon,
							                           'valor'=>$li_valsal,'cuota'=>$ls_cuota);
						}
						if(($ls_tipsal=="D") || ($ls_tipsal=="V2") || ($ls_tipsal=="W2") ||
						   ($ls_tipsal=="P1") || ($ls_tipsal=="V3") || ($ls_tipsal=="W3") ) // Buscamos las deducciones
						{
							$li_dedu=$li_dedu+1;
							$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
							$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
							$li_totded=$li_totded+abs($io_report->rs_data_detalle->fields["valsal"]);
							$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data_detalle->fields["valsal"]));
							$ls_repconsunicon=$io_report->rs_data_detalle->fields["repconsunicon"];
							$ls_consunicon=$io_report->rs_data_detalle->fields["consunicon"];
							$ls_cuota="";
							if (($ls_repconsunicon=='1')&&($ls_consunicon!=""))
							{
								$io_report->uf_buscar_cuotas($ls_consunicon,$ls_codper,$ls_cuota);
							}
							$la_data_d[$li_dedu]=array('codigo'=>$ls_codconc,'denominacion'=>$ls_nomcon,
							'valor'=>$li_valsal,'cuota'=>$ls_cuota);
						}
						if(($ls_tipsal=="P2") || ($ls_tipsal=="V4") || ($ls_tipsal=="W4") ) // Buscamos los aportes
						{
							$li_apor=$li_apor+1;
							$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
							$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
							$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data_detalle->fields["valsal"]));
							$ls_repconsunicon=$io_report->rs_data_detalle->fields["repconsunicon"];
							$ls_consunicon=$io_report->rs_data_detalle->fields["consunicon"];
							$ls_cuota="";
							if (($ls_repconsunicon=='1')&&($ls_consunicon!=""))
							{
								$io_report->uf_buscar_cuotas($ls_consunicon,$ls_codper,$ls_cuota);
							}
							$la_data_p[$li_apor]=array('codigo'=>$ls_codconc,'denominacion'=>$ls_nomcon,
							                           'valor'=>$li_valsal,'cuota'=>$ls_cuota);
						}
						$io_report->rs_data_detalle->MoveNext();
					}
				}
				$li_count=0;
				for($li_s=1;$li_s<=$li_asig;$li_s++) 
				{
					$li_count++;
					$la_data[$li_count]=array('order'=>$la_data_a[$li_s]["codigo"],
											  'codigo'=>$la_data_a[$li_s]["codigo"], 
									          'denominacion'=>$la_data_a[$li_s]["denominacion"], 
									          'asignacion'=>$la_data_a[$li_s]["valor"],
									          'deduccion'=>'',
									          'aporte'=>'','cuotas'=>$la_data_a[$li_s]["cuota"]);
				}
				for($li_s=1;$li_s<=$li_dedu;$li_s++) 
				{
					$li_count++;
					$la_data[$li_count]=array('order'=>$la_data_d[$li_s]["codigo"]."0",
											  'codigo'=>$la_data_d[$li_s]["codigo"], 
									          'denominacion'=>$la_data_d[$li_s]["denominacion"], 
									          'asignacion'=>'',
									          'deduccion'=>$la_data_d[$li_s]["valor"],
									          'aporte'=>'','cuotas'=>$la_data_d[$li_s]["cuota"]);
				}
				for($li_s=1;$li_s<=$li_apor;$li_s++) 
				{
					$li_count++;
					$la_data[$li_count]=array('order'=>$la_data_p[$li_s]["codigo"]."2",
											  'codigo'=>$la_data_p[$li_s]["codigo"], 
									          'denominacion'=>$la_data_p[$li_s]["denominacion"], 
									          'asignacion'=>'',
									          'deduccion'=>'',
									          'aporte'=>$la_data_p[$li_s]["valor"],
											  'cuotas'=>$la_data_p[$li_s]["cuota"]);
				}
				sort($la_data);
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 							
				$li_totnet=$li_toting-$li_totded;
				$li_toting=$io_fun_nomina->uf_formatonumerico($li_toting);
				$li_totded=$io_fun_nomina->uf_formatonumerico($li_totded);
				$li_totnet=$io_fun_nomina->uf_formatonumerico($li_totnet);
				if($li_reg==1)
				{
					uf_print_pie_cabecera1($li_toting,$li_totded,$li_totnet,$ls_codcueban,$io_pdf); // Imprimimos pie de la cabecera
					$io_report->uf_seleccionar_quincenas($ls_codper,&$ls_priqui,&$ls_segqui);
				    uf_print_quincenas($io_fun_nomina->uf_formatonumerico($ls_priqui),
					                   $io_fun_nomina->uf_formatonumerico($ls_segqui),&$io_pdf);	
				}
				unset($la_data_a);
				unset($la_data_d);
				unset($la_data_p);
				unset($la_data);
				if($li_i<$li_totrow)
				{
					$io_pdf->ezNewPage(); // Insertar una nueva página
					$li_reg=1;
				}				
			}
			$li_i++;
			$io_report->rs_data->MoveNext();
		}
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
