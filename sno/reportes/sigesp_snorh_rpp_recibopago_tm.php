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
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Arreglo de las variables de seguridad
		//	    		   as_desnom // Arreglo de las variables de seguridad
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/09/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_descripcion="Generó el Reporte Consolidado ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_recibopago.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera1($as_cedper,$as_nomper,$as_descar,$as_descripcion,&$io_cabecera,&$io_pdf)
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
		// Fecha Creación: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0,1);
		$io_pdf->line(30,765,580,765);
		$io_pdf->line(30,765,30,448);
		$io_pdf->line(130,690,130,462);
		$io_pdf->line(370,690,370,462);
		$io_pdf->line(475,690,475,462);
		$io_pdf->line(580,765,580,448);
		$io_pdf->line(30,690,580,690);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(10,$as_nomper);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,740,10,$as_nomper); // Agregar el Nombre
		$li_tm=$io_pdf->getTextWidth(8,$as_descar);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,8,$as_descar); // Agregar la Descripción del cargo
		$io_pdf->addText(520,755,8,$as_cedper); // Agregar la Cédula
		$io_pdf->line(30,715,580,715);
		$io_pdf->ezSetY(715);
		$la_data=array(array('titulo'=>'<b>'.$as_descripcion.'</b>', 'descripcion'=>'<b>DESCRIPCIÓN</b>', 'asignacion'=>'<b>ASIGNACIONES</b>','deduccion'=>'<b>DEDUCCIONES</b>'));
		$la_columna=array('titulo'=>'',
						  'descripcion'=>'',
						  'asignacion'=>'',
						  'deduccion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'descripcion'=>array('justification'=>'center','width'=>240), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->addText(33,650,6,'HE RECIBIDO CONFORME DEL');
		$io_pdf->addText(33,642,6,'SISTEMA     TELEFÉRICO     DE');
		$io_pdf->addText(33,634,6,'MÉRIDA  LA   CANTIDAD  NETA');
		$io_pdf->addText(33,626,6,'INDICADA     EN    PAGO    POR');
		$io_pdf->addText(33,618,6,'SERVICIOS     PRESTADOS    A');
		$io_pdf->addText(33,610,6,'ESTA     EMPRESA,     EN      EL');
		$io_pdf->addText(33,602,6,'PERÍODO    ESPECIFICADO   Y');
		$io_pdf->addText(33,594,6,'DE ACUERDO  A LA RELACIÓN');
		$io_pdf->addText(33,586,6,'DEL      PRESENTE       RECIBO');
		$io_pdf->addText(33,526,6,'  _________________________');
		$io_pdf->addText(33,512,6,'                    FIRMA');
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera2($as_cedper,$as_nomper,$as_descar,$as_descripcion,&$io_cabecera,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_cedper // Cédula del personal
		//	    		   as_nomper // Nombre del personal
		//	    		   as_descar // Decripción del cargo
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
		$io_pdf->line(580,345,580,28);
		$io_pdf->line(130,270,130,42);
		$io_pdf->line(370,270,370,42);
		$io_pdf->line(475,270,475,42);
		$io_pdf->line(30,270,580,270);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,300,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(10,$as_nomper);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,320,10,$as_nomper); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(8,$as_descar);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,310,8,$as_descar); // Agregar el título
		$io_pdf->addText(520,335,8,$as_cedper); // Agregar la Cédula
		$io_pdf->line(30,295,580,295);
		$io_pdf->ezSetY(295);
		$la_data=array(array('titulo'=>'<b>'.$as_descripcion.'</b>', 'descripcion'=>'<b>DESCRIPCIÓN</b>', 'asignacion'=>'<b>ASIGNACIONES</b>','deduccion'=>'<b>DEDUCCIONES</b>'));
		$la_columna=array('titulo'=>'',
						  'descripcion'=>'',
						  'asignacion'=>'',
						  'deduccion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'descripcion'=>array('justification'=>'center','width'=>240), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->addText(33,230,6,'HE RECIBIDO CONFORME DEL');
		$io_pdf->addText(33,222,6,'SISTEMA     TELEFÉRICO     DE');
		$io_pdf->addText(33,214,6,'MÉRIDA  LA   CANTIDAD  NETA');
		$io_pdf->addText(33,206,6,'INDICADA     EN    PAGO    POR');
		$io_pdf->addText(33,198,6,'SERVICIOS     PRESTADOS    A');
		$io_pdf->addText(33,190,6,'ESTA     EMPRESA,     EN      EL');
		$io_pdf->addText(33,182,6,'PERÍODO    ESPECIFICADO   Y');
		$io_pdf->addText(33,174,6,'DE ACUERDO  A LA RELACIÓN');
		$io_pdf->addText(33,166,6,'DEL      PRESENTE       RECIBO');
		$io_pdf->addText(33,106,6,'  _________________________');
		$io_pdf->addText(33,92,6,'                    FIRMA');
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
		$la_columna=array('texto'=>'',
						  'denominacion'=>'',
						  'asignacion'=>'',
						  'deduccion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('texto'=>array('justification'=>'left','width'=>100), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>240), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
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
		// Fecha Creación: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares, $io_monedabsf, $ls_tiporeporte;
		
		$io_piepagina=$io_pdf->openObject(); // Creamos el objeto pie de página
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0,1);
		$io_pdf->line(30,462,580,462);
		$io_pdf->line(30,448,580,448);
		$io_pdf->addText(130,475,'10','---------------------------------------------------------------------------------------------------------------------------------------');
		$io_pdf->ezSety(480);
		$la_data=array(array('texto'=>'', 'descripcion'=>'<b>TOTALES '.$ls_bolivares.'</b>', 'asignacion'=>$ai_toting,'deduccion'=>$ai_totded));
		$la_columna=array('texto'=>'',
						  'descripcion'=>'',
						  'asignacion'=>'',
						  'deduccion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('texto'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'descripcion'=>array('justification'=>'left','width'=>240), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('neto'=>'<b>NETO A COBRAR  '.$ls_bolivares.'</b>  '.$ai_totnet.'                      '));
		$la_columna=array('neto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('neto'=>array('justification'=>'right','width'=>540))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		/*if($ls_tiporeporte==0)
		{
			$ai_totnet=str_replace(".","",$ai_totnet);
			$ai_totnet=str_replace(",",".",$ai_totnet);
			$li_montobsf=$io_monedabsf->uf_convertir_monedabsf($ai_totnet,$_SESSION["la_empresa"]["candeccon"],$_SESSION["la_empresa"]["tipconmon"],1000,$_SESSION["la_empresa"]["redconmon"]);
			$li_montobsf=number_format($li_montobsf,2,",",".");
			$io_pdf->addText(385,440,'9','<b>Neto a Cobrar Bs.F. :</b>  '.$li_montobsf);
		}*/
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_piepagina,'all');
		$io_pdf->stopObject($io_piepagina); // Detener el objeto pie de página
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera2($ai_toting,$ai_totded,$ai_totnet,$as_codcueban,&$io_pdf)
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
		$io_pdf->setStrokeColor(0,0,0,1);
		$io_pdf->line(30,42,580,42);
		$io_pdf->line(30,28,580,28);
		$io_pdf->addText(130,55,'10','---------------------------------------------------------------------------------------------------------------------------------------');
		$io_pdf->ezSety(60);
		$la_data=array(array('texto'=>'', 'descripcion'=>'<b>TOTALES '.$ls_bolivares.'</b>', 'asignacion'=>$ai_toting,'deduccion'=>$ai_totded));
		$la_columna=array('texto'=>'',
						  'descripcion'=>'',
						  'asignacion'=>'',
						  'deduccion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('texto'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'descripcion'=>array('justification'=>'left','width'=>240), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('neto'=>'<b>NETO A COBRAR  '.$ls_bolivares.'</b>  '.$ai_totnet.'                      '));
		$la_columna=array('neto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('neto'=>array('justification'=>'right','width'=>540))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		/*if($ls_tiporeporte==0)
		{
			$ai_totnet=str_replace(".","",$ai_totnet);
			$ai_totnet=str_replace(",",".",$ai_totnet);
			$li_montobsf=$io_monedabsf->uf_convertir_monedabsf($ai_totnet,$_SESSION["la_empresa"]["candeccon"],$_SESSION["la_empresa"]["tipconmon"],1000,$_SESSION["la_empresa"]["redconmon"]);
			$li_montobsf=number_format($li_montobsf,2,",",".");
			$io_pdf->addText(385,20,'9','<b>Neto a Cobrar Bs.F. :</b>  '.$li_montobsf);
		}*/
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_piepagina,'all');
		$io_pdf->stopObject($io_piepagina); // Detener el objeto pie de página
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	$ls_tiporeporte="0";
	$ls_bolivares="";
	if (array_key_exists("tiporeporte",$_GET))
	{
		$ls_tiporeporte=$_GET["tiporeporte"];
	}
	switch($ls_tiporeporte)
	{
		case "0":
			require_once("sigesp_snorh_class_report.php");
			$io_report=new sigesp_snorh_class_report();
			$ls_bolivares ="Bs.";
			break;

		case "1":
			require_once("sigesp_snorh_class_reportbsf.php");
			$io_report=new sigesp_snorh_class_reportbsf();
			$ls_bolivares ="Bs.F.";
			break;
	}
	require_once("../../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
	$io_monedabsf=new sigesp_c_reconvertir_monedabsf();				
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codnom=$io_fun_nomina->uf_obtenervalor_get("codnom","");
	$ls_desnom="<b>".$io_fun_nomina->uf_obtenervalor_get("desnom","")."</b>";
	$ls_codperides=$io_fun_nomina->uf_obtenervalor_get("codperides","");
	$ls_codperihas=$io_fun_nomina->uf_obtenervalor_get("codperihas","");
	$ld_fecdesper=$io_fun_nomina->uf_obtenervalor_get("fecdesper","");
	$ld_fechasper=$io_fun_nomina->uf_obtenervalor_get("fechasper","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_coduniadm=$io_fun_nomina->uf_obtenervalor_get("coduniadm","");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_conceptop2=$io_fun_nomina->uf_obtenervalor_get("conceptop2","");
	$ls_conceptoreporte=$io_fun_nomina->uf_obtenervalor_get("conceptoreporte","");
	$ls_tituloconcepto=$io_fun_nomina->uf_obtenervalor_get("tituloconcepto","");
	$ls_quincena=$io_fun_nomina->uf_obtenervalor_get("quincena","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("codsubnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("codsubnomhas","");
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>COMPROBANTE DE PAGO</b>";
	$ls_periodo="Periodos: <b>".$ls_codperides." - ".$ls_codperihas."</b> del <b>".$ld_fecdesper."</b> al <b>".$ld_fechasper."</b>";
	if(substr($ld_fecdesper,0,2)=="01")
	{
		$ls_descripcion="1ra QUINCENA ";
	}
	else
	{
		$ls_descripcion="2da QUINCENA ";
	}
	$ls_descripcion=$ls_periodo;
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_recibopago_personal($ls_codnom,$ld_fecdesper,$ld_fechasper,$ls_codperdes,$ls_codperhas,$ls_coduniadm,
													  $ls_conceptocero,$ls_conceptop2,$ls_conceptoreporte,$ls_subnomdes,$ls_subnomhas,$ls_orden); // Cargar el DS con los datos de la cabecera del reporte*/
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
		$io_pdf->ezSetCmMargins(1,1,1,1); // Configuración de los margenes en centímetros
		$li_totrow=$io_report->DS->getRowCount("codper");
		$li_reg=1;
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$li_toting=0;
			$li_totded=0;
			$ls_codper=$io_report->DS->data["codper"][$li_i];
			$ls_nacper=$io_report->DS->data["nacper"][$li_i];
			$ls_cedper=$ls_nacper."- ".$io_report->DS->data["cedper"][$li_i];
			$ls_nomper=$io_report->DS->data["apeper"][$li_i].", ".$io_report->DS->data["nomper"][$li_i];
			$ls_descar=$io_report->DS->data["descar"][$li_i];
			$ls_codcueban=$io_report->DS->data["codcueban"][$li_i];
			$li_total=$io_report->DS->data["total"][$li_i];
			$li_adelanto=$io_report->DS->data["adenom"][$li_i];
			$li_racnom=$io_report->DS->data["racnom"][$li_i];
			if($li_racnom==1)
			{
				$ls_descar=$io_report->DS->data["denasicar"][$li_i];
			}
			$io_cabecera=$io_pdf->openObject(); // Creamos el objeto cabecera
			if($li_reg==1)
			{
				uf_print_cabecera1($ls_cedper,$ls_nomper,$ls_descar,$ls_descripcion,$io_cabecera,$io_pdf); // Imprimimos la cabecera del registro
			}
			else
			{
				uf_print_cabecera2($ls_cedper,$ls_nomper,$ls_descar,$ls_descripcion,$io_cabecera,$io_pdf); // Imprimimos la cabecera del registro
			}
			$lb_valido=$io_report->uf_recibopago_conceptopersonal($ls_codnom,$ld_fecdesper,$ld_fechasper,$ls_codper,
																  $ls_conceptocero,$ls_conceptop2,$ls_conceptoreporte,
																  $ls_tituloconcepto); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->DS_detalle->getRowCount("codconc");
				$li_asig=0;
				$li_dedu=0;
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++) 
				{
					$ls_tipsal=rtrim($io_report->DS_detalle->data["tipsal"][$li_s]);
					if(($ls_tipsal=="A") || ($ls_tipsal=="V1") || ($ls_tipsal=="W1") ) // Buscamos las asignaciones
					{
						$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_s];
						$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_s];
						$li_toting=$li_toting+abs($io_report->DS_detalle->data["valsal"][$li_s]);
						$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valsal"][$li_s]));
						$la_data[$li_s]=array('texto'=>'','denominacion'=>$ls_nomcon,'asignacion'=>$li_valsal,'deduccion'=>'');
					}
					else // Buscamos las deducciones y aportes
					{
						$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_s];
						$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_s];
						$li_totded=$li_totded+abs($io_report->DS_detalle->data["valsal"][$li_s]);
						$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valsal"][$li_s]));
						$la_data[$li_s]=array('texto'=>'','denominacion'=>$ls_nomcon,'asignacion'=>'','deduccion'=>$li_valsal);
					}
				}
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				$li_totnet=$li_toting-$li_totded;
				$li_toting=$io_fun_nomina->uf_formatonumerico($li_toting);
				$li_totded=$io_fun_nomina->uf_formatonumerico($li_totded);
				$li_totnet=$io_fun_nomina->uf_formatonumerico($li_totnet);
				if($li_reg==1)
				{
					uf_print_pie_cabecera1($li_toting,$li_totded,$li_totnet,$ls_codcueban,$io_pdf); // Imprimimos pie de la cabecera
				}
				else
				{
					uf_print_pie_cabecera2($li_toting,$li_totded,$li_totnet,$ls_codcueban,$io_pdf); // Imprimimos pie de la cabecera
				}
				$io_report->DS_detalle->resetds("codconc");
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
		}
		$io_report->DS->resetds("codper");
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