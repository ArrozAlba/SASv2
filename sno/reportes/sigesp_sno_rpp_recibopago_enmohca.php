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
	ini_set('memory_limit','256M');
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
		// Fecha Creación: 05/05/2006 
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
	function uf_print_cabecera1($as_cedper,$as_nomper,$as_descar,$as_desuniadm,$ad_fecingper,&$io_cabecera,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera1
		//		   Access: private 
		//	    Arguments: as_cedper // Cédula del personal
		//	    		   as_nomper // Nombre del personal
		//	    		   as_descar // Decripción del cargo
		//	    		   as_desuniadm // Decripción de la unidad administrativa
		//	    		   ad_fecingper // Fecha de Ingreso del Personal
		//	    		   io_cabecera // objeto cabecera
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/11/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0,1);
		$io_pdf->line(30,765,580,765);
		$io_pdf->line(30,765,30,448);
		$io_pdf->line(150,753,580,753);
		$io_pdf->line(150,725,580,725);
		$io_pdf->line(150,713,580,713);
		$io_pdf->line(150,765,150,690);
		$io_pdf->line(360,765,360,690);
		$io_pdf->line(485,765,485,725);
		$io_pdf->addText(195,755,10,"APELLIDOS Y NOMBRES");
		$io_pdf->addText(365,755,10,"CÉDULA DE IDENTIDAD");
		$io_pdf->addText(505,755,10,"FECHA ING.");
		$io_pdf->addText(240,715,10,"CARGO");
		$io_pdf->addText(428,715,10,"COORDINACIÓN");
		$io_pdf->line(580,765,580,448);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],45,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->line(30,448,580,448);
		$li_tm=$io_pdf->getTextWidth(9,$as_nomper);
		$li_tm=(105-($li_tm/2));
		$tm=152;
		if($li_tm>0)
		{
			$tm=150+$li_tm;
		}
		$io_pdf->addTextWrap($tm,735,208,9,$as_nomper,0,0); // Agregar el Nombre
		$li_tm=$io_pdf->getTextWidth(9,$as_cedper);
		$li_tm=(62.5-($li_tm/2));
		$tm=360+$li_tm;
		$io_pdf->addText($tm,735,9,$as_cedper); // Agregar la Cédula	
		$li_tm=$io_pdf->getTextWidth(9,$ad_fecingper);
		$li_tm=(47.5-($li_tm/2));
		$tm=485+$li_tm;
		$io_pdf->addText($tm,735,9,$ad_fecingper); // Agregar la Fecha de Ingreso
		$li_tm=$io_pdf->getTextWidth(8,$as_descar);
		$li_tm=(105-($li_tm/2));
		$tm=152;
		if($li_tm>0)
		{
			$tm=150+$li_tm;
		}
		$io_pdf->addTextWrap($tm,700,208,8,$as_descar); // Agregar la Descripción del cargo

		$li_tm=$io_pdf->getTextWidth(8,$as_desuniadm);
		$li_tm=(110-($li_tm/2));
		$tm=362;
		if($li_tm>0)
		{
			$tm=360+$li_tm;
		}
		$io_pdf->addTextWrap($tm,700,218,8,$as_desuniadm); // Agregar la Descripción de la unidad administrativa
		$io_pdf->ezSetY(691);		
		$la_data=array(array('concepto'=>'<b>CONCEPTO</b>', 'descripcion'=>'<b>DESCRIPCIÓN DEL CONCEPTO</b>', 'asignacion'=>'<b>ASIGNACIONES</b>','deduccion'=>'<b>DEDUCCIONES</b>'));
		$la_columna=array('concepto'=>'',
						  'descripcion'=>'',
						  'asignacion'=>'',
						  'deduccion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'xPos' =>310,
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('concepto'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'descripcion'=>array('justification'=>'center','width'=>270), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera2($as_cedper,$as_nomper,$as_descar,$as_desuniadm,$ad_fecingper,&$io_cabecera,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_cedper // Cédula del personal
		//	    		   as_nomper // Nombre del personal
		//	    		   as_descar // Decripción del cargo
		//	    		   as_desuniadm // Descripción de la unidad administrativa
		//	    		   ad_fecingper // fecha de Ingreso
		//	    		   io_cabecera // objeto cabecera
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0,1);
		$io_pdf->line(30,345,580,345);
		$io_pdf->line(30,345,30,28);
		$io_pdf->line(150,333,580,333);
		$io_pdf->line(150,305,580,305);
		$io_pdf->line(150,293,580,293);
		$io_pdf->line(150,345,150,270);
		$io_pdf->line(360,345,360,270);
		$io_pdf->line(485,345,485,305);
		$io_pdf->addText(195,335,10,"APELLIDOS Y NOMBRES");
		$io_pdf->addText(365,335,10,"CÉDULA DE IDENTIDAD");
		$io_pdf->addText(505,335,10,"FECHA ING.");
		$io_pdf->addText(240,295,10,"CARGO");
		$io_pdf->addText(428,295,10,"COORDINACIÓN");
		$io_pdf->line(580,345,580,28);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],45,280,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->line(30,28,580,28);
		$li_tm=$io_pdf->getTextWidth(9,$as_nomper);
		$li_tm=(105-($li_tm/2));
		$tm=152;
		if($li_tm>0)
		{
			$tm=150+$li_tm;
		}
		$io_pdf->addTextWrap($tm,315,208,9,$as_nomper,0,0); // Agregar el Nombre
		$li_tm=$io_pdf->getTextWidth(9,$as_cedper);
		$li_tm=(62.5-($li_tm/2));
		$tm=360+$li_tm;
		$io_pdf->addText($tm,315,9,$as_cedper); // Agregar la Cédula	
		$li_tm=$io_pdf->getTextWidth(9,$ad_fecingper);
		$li_tm=(47.5-($li_tm/2));
		$tm=485+$li_tm;
		$io_pdf->addText($tm,315,9,$ad_fecingper); // Agregar la Fecha de Ingreso
		$li_tm=$io_pdf->getTextWidth(8,$as_descar);
		$li_tm=(105-($li_tm/2));
		$tm=152;
		if($li_tm>0)
		{
			$tm=150+$li_tm;
		}
		$io_pdf->addTextWrap($tm,280,208,8,$as_descar); // Agregar la Descripción del cargo

		$li_tm=$io_pdf->getTextWidth(8,$as_desuniadm);
		$li_tm=(110-($li_tm/2));
		$tm=362;
		if($li_tm>0)
		{
			$tm=360+$li_tm;
		}
		$io_pdf->addTextWrap($tm,280,218,8,$as_desuniadm); // Agregar la Descripción de la unidad administrativa
		$io_pdf->ezSetY(271);		
		$la_data=array(array('concepto'=>'<b>CONCEPTO</b>', 'descripcion'=>'<b>DESCRIPCIÓN DEL CONCEPTO</b>', 'asignacion'=>'<b>ASIGNACIONES</b>','deduccion'=>'<b>DEDUCCIONES</b>'));
		$la_columna=array('concepto'=>'',
						  'descripcion'=>'',
						  'asignacion'=>'',
						  'deduccion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'xPos' =>310,
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('concepto'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'descripcion'=>array('justification'=>'center','width'=>270), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
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
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('codigo'=>'',
						  'denominacion'=>'',
						  'asignacion'=>'',
						  'deduccion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'xPos' =>310,
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>270), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera1($ai_toting,$ai_totded,$ai_totnet,$as_codcueban,$ad_fecdesper,$ad_fechasper,$as_banco,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera1
		//		   Access: private 
		//	    Arguments: ai_toting // Total Ingresos
		//	   			   ai_totded // Total Deducciones
		//	   			   ai_totnet // Total Neto
		//	   			   as_codcueban // Codigo cuenta bancaria
		//	   			   ad_fecdesper // Fecha Desde
		//	   			   ad_fechasper // Fecha Hasta
		//	   			   as_banco // Banco por el que se le está pagando
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares, $io_monedabsf, $ls_tiporeporte;
		
		$io_piepagina=$io_pdf->openObject(); // Creamos el objeto pie de página
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0,0);
		$io_pdf->line(30,500,580,500);
		$io_pdf->line(350,485,580,485);
		$io_pdf->line(350,500,350,448);
		$io_pdf->line(470,500,470,485);
		$io_pdf->line(30,448,580,448);
		$io_pdf->line(355,458,450,458);
		$io_pdf->line(480,458,575,458);
		$io_pdf->ezSety(518);
		$la_data=array(array('descripcion'=>'<b>TOTALES '.$ls_bolivares.'</b>                 ', 'asignacion'=>$ai_toting,'deduccion'=>$ai_totded));
		$la_columna=array('descripcion'=>'',
						  'asignacion'=>'',
						  'deduccion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('descripcion'=>array('justification'=>'right','width'=>350), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->ezSety(500);
		$ls_texto = "HE RECIBIDO CONFORME A TRAVES DE ABONO EN MI CUENTA N° ".$as_codcueban." DE ".$as_banco.", ".
					"EL SALDO INDICADO EN ESTE RECIBO, POR CONCEPTO DE LOS SERVICIOS PRESTADOS DESDE EL ".$ad_fecdesper." ".
					"HASTA EL ".$ad_fechasper.", EN EL CUAL ESTÁN INCLUIDOS LOS DÍAS DE DESCANSO Y FERIADOS. ";
		$la_data=array(array('texto'=>$ls_texto));
		$la_columna=array('texto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'xPos' =>195,
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>315, // Ancho de la tabla
						 'maxWidth'=>315,
						 'cols'=>array('texto'=>array('justification'=>'full','width'=>315))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->addText(380,490,8,"<b>TOTAL PAGADO ".$ls_bolivares."</b>");
		$io_pdf->addText(500,490,8,'<b>'.$ai_totnet.'</b>');
		$io_pdf->addText(435,478,7,"<b>RECIBO CONFORME</b>");
		$io_pdf->addText(355,450,7,"<b>FIRMA</b>");
		$io_pdf->addText(480,450,7,"<b>C.I. N°</b>");
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_piepagina,'all');
		$io_pdf->stopObject($io_piepagina); // Detener el objeto pie de página
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera2($ai_toting,$ai_totded,$ai_totnet,$as_codcueban,$ad_fecdesper,$ad_fechasper,$as_banco,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera
		//		   Access: private 
		//	    Arguments: ai_toting // Total Ingresos
		//	   			   ai_totded // Total Deducciones
		//	   			   ai_totnet // Total Neto
		//	   			   as_codcueban // Codigo cuenta bancaria
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares, $io_monedabsf, $ls_tiporeporte;
		
		$io_piepagina=$io_pdf->openObject(); // Creamos el objeto pie de página
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0,0);
		$io_pdf->line(30,80,580,80);
		$io_pdf->line(350,65,580,65);
		$io_pdf->line(350,80,350,28);
		$io_pdf->line(470,80,470,65);
		$io_pdf->line(30,28,580,28);
		$io_pdf->line(355,38,450,38);
		$io_pdf->line(480,38,575,38);
		$io_pdf->ezSety(98);
		$la_data=array(array('descripcion'=>'<b>TOTALES '.$ls_bolivares.'</b>                 ', 'asignacion'=>$ai_toting,'deduccion'=>$ai_totded));
		$la_columna=array('descripcion'=>'',
						  'asignacion'=>'',
						  'deduccion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('descripcion'=>array('justification'=>'right','width'=>350), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->ezSety(80);
		$ls_texto = "HE RECIBIDO CONFORME A TRAVES DE ABONO EN MI CUENTA N° ".$as_codcueban." DE ".$as_banco.", ".
					"EL SALDO INDICADO EN ESTE RECIBO, POR CONCEPTO DE LOS SERVICIOS PRESTADOS DESDE EL ".$ad_fecdesper." ".
					"HASTA EL ".$ad_fechasper.", EN EL CUAL ESTÁN INCLUIDOS LOS DÍAS DE DESCANSO Y FERIADOS. ";
		$la_data=array(array('texto'=>$ls_texto));
		$la_columna=array('texto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'xPos' =>195,
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>315, // Ancho de la tabla
						 'maxWidth'=>315,
						 'cols'=>array('texto'=>array('justification'=>'full','width'=>315))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->addText(380,70,8,"<b>TOTAL PAGADO ".$ls_bolivares."</b>");
		$io_pdf->addText(500,70,8,'<b>'.$ai_totnet.'</b>');
		$io_pdf->addText(435,58,7,"<b>RECIBO CONFORME</b>");
		$io_pdf->addText(355,30,7,"<b>FIRMA</b>");
		$io_pdf->addText(480,30,7,"<b>C.I. N°</b>");
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_piepagina,'all');
		$io_pdf->stopObject($io_piepagina); // Detener el objeto pie de página
	}// end function uf_print_pie_cabecera
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
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$li_adelanto=$_SESSION["la_nomina"]["adenom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	if(substr($ld_fecdesper,0,2)=="01")
	{
		$ls_descripcion="1ra QUINCENA ";
	}
	else
	{
		$ls_descripcion="2da QUINCENA ";
	}
	$ls_descripcion=$ls_descripcion."MES DE ".strtoupper($io_fecha->uf_load_nombre_mes(substr($ld_fecdesper,3,2)))." ".substr($ld_fecdesper,6,4);
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
	$ls_titulo="<b>COMPROBANTE DE PAGO</b>";
	$ls_periodo="Periodo: <b>".$ls_peractnom."</b> del <b>".$ld_fecdesper."</b> al <b>".$ld_fechasper."</b>";
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_recibopago_personal($ls_codperdes,$ls_codperhas,$ls_coduniadm,$ls_conceptocero,$ls_conceptop2,
													  $ls_conceptoreporte,$ls_codubifis,$ls_codpai,$ls_codest,$ls_codmun,$ls_codpar,
													  $ls_subnomdes,$ls_subnomhas,$ls_orden); // Cargar el DS con los datos de la cabecera del reporte
	}
	if(($lb_valido==false) || ($io_report->rs_data->RecordCount()==0)) // Existe algún error ó no hay registros
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
		$io_pdf->ezSetCmMargins(1,1,1,1); // Configuración de los margenes en centímetros
		$li_totrow=$io_report->rs_data->RecordCount();
		$li_reg=1;
		$li_i=1;
		while((!$io_report->rs_data->EOF)&&($lb_valido))
		{
			$li_toting=0;
			$li_totded=0;
			$ls_codper=$io_report->rs_data->fields["codper"];
			$ls_cedper=$io_report->rs_data->fields["cedper"];
			$ls_nomper=$io_report->rs_data->fields["apeper"].", ".$io_report->rs_data->fields["nomper"];
			$ls_descar=$io_report->rs_data->fields["descar"];
			$ls_codcueban=$io_report->rs_data->fields["codcueban"];
			$ld_fecingper=$io_funciones->uf_convertirfecmostrar($io_report->rs_data->fields["fecingper"]);
			$ls_desuniadm=$io_report->rs_data->fields["desuniadm"];
			$ls_banco=$io_report->rs_data->fields["banco"];
			$li_total=$io_report->rs_data->fields["total"];
			$io_cabecera=$io_pdf->openObject(); // Creamos el objeto cabecera
			if($li_reg==1)
			{
				uf_print_cabecera1($ls_cedper,$ls_nomper,$ls_descar,$ls_desuniadm,$ld_fecingper,$io_cabecera,$io_pdf); // Imprimimos la cabecera del registro
			}
			else
			{
				uf_print_cabecera2($ls_cedper,$ls_nomper,$ls_descar,$ls_desuniadm,$ld_fecingper,$io_cabecera,$io_pdf); // Imprimimos la cabecera del registro
			}

			$lb_valido=$io_report->uf_recibopago_conceptopersonal($ls_codper,$ls_conceptocero,$ls_conceptop2,
																  $ls_conceptoreporte,$ls_tituloconcepto,$ls_quincena); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->rs_data_detalle->RecordCount();
				$li_asig=0;
				$li_dedu=0;
				$li_s=1;
				while(!$io_report->rs_data_detalle->EOF)
				{
					$ls_tipsal=rtrim($io_report->rs_data_detalle->fields["tipsal"]);
					if(($ls_tipsal=="A") || ($ls_tipsal=="V1") || ($ls_tipsal=="W1") ) // Buscamos las asignaciones
					{
						$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
						$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
						$li_toting=$li_toting+abs($io_report->rs_data_detalle->fields["valsal"]);
						$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data_detalle->fields["valsal"]));
						$la_data[$li_s]=array('codigo'=>$ls_codconc,'denominacion'=>$ls_nomcon,'asignacion'=>$li_valsal,'deduccion'=>'');
					}
					else // Buscamos las deducciones y aportes
					{
						$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
						$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
						$li_totded=$li_totded+abs($io_report->rs_data_detalle->fields["valsal"]);
						$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data_detalle->fields["valsal"]));
						$la_data[$li_s]=array('codigo'=>$ls_codconc,'denominacion'=>$ls_nomcon,'asignacion'=>'','deduccion'=>$li_valsal);
					}
					$li_s++;
					$io_report->rs_data_detalle->MoveNext();
				}
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				$li_totnet=$li_toting-$li_totded;
				$li_toting=$io_fun_nomina->uf_formatonumerico($li_toting);
				$li_totded=$io_fun_nomina->uf_formatonumerico($li_totded);
				$li_totnet=$io_fun_nomina->uf_formatonumerico($li_totnet);
				if($li_reg==1)
				{
					uf_print_pie_cabecera1($li_toting,$li_totded,$li_totnet,$ls_codcueban,$ld_fecdesper,$ld_fechasper,$ls_banco,$io_pdf); // Imprimimos pie de la cabecera
				}
				else
				{
					uf_print_pie_cabecera2($li_toting,$li_totded,$li_totnet,$ls_codcueban,$ld_fecdesper,$ld_fechasper,$ls_banco,$io_pdf); // Imprimimos pie de la cabecera
				}
				unset($la_data);
				$io_pdf->stopObject($io_cabecera); // Detener el objeto cabecera
				if(($li_i<$li_totrow)&&($li_reg==2))
				{
					$io_pdf->ezNewPage(); // Insertar una nueva página
					$li_reg=1;
				}
				else
				{
					$li_reg=2;
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