<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//  ESTE FORMATO SE IMPRIME EN Bs Y EN BsF. SEGUN LO SELECCIONADO POR EL USUARIO
	//  MODIFICADO POR: ING.YOZELIN BARRAGAN         FECHA DE MODIFICACION : 28/08/2007
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
	function uf_print_encabezado_pagina($as_titulo,$ad_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   ad_fecha // Fecha 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->saveState();
		$io_pdf->line(50,40,950,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=504-($li_tm/2);
		$io_pdf->addText($tm,550,11,"<b>".$as_titulo."</b>"); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$ad_fecha);
		$tm=504-($li_tm/2);
		$io_pdf->addText(750,535,11,""); // Agregar la fecha
		$io_pdf->addText($tm,535,11,$ad_fecha); // Agregar la fecha
		$io_pdf->addText(750,555,11,""); // Agregar la fecha
		$io_pdf->addText(800,555,11,""); // Agregar la fecha
		$io_pdf->addText(908,570,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(914,563,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codemp,$as_nomemp,$as_codact,$as_denact,$as_maract,$as_modact,$ad_fecmpact,$ai_costo,$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codemp   // codigo de empresa
		//	    		   as_nomemp   // nombre de empresa
		//	    		   as_codact   // codigo de activo
		//	    		   as_denact   // denominacion de activo
		//	    		   as_maract   // marca del activo
		//	    		   as_modact   // modelo del activo
		//	    		   ad_fecmpact // fecha de compra del activo
		//	    		   ai_costo    // costo del activo
		//	    		   io_pdf      // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>Organismo:</b>  '.$as_codemp." - ".$as_nomemp.''),
					   array ('name'=>'<b>Activo:</b>  '.$as_codact." - ".$as_denact.''),
					   array ('name'=>'<b>Marca:</b>  '.$as_maract."    <b>Modelo:</b> ".$as_modact.''),
					   array ('name'=>'<b>Fecha de Compra:</b>  '.$ad_fecmpact."    <b>Costo:</b> ".$ai_costo.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar Líneas
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900); // Ancho Máximo de la tabla
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
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tipoformato;
		if($ls_tipoformato==0)
		{
		  $ls_titulo=" Bs.";
		}
		elseif($ls_tipoformato==1)
		{
		  $ls_titulo=" Bs.F.";
		}
		$la_columna=array('codact'=>'<b>Código</b>',
						  'denact'=>'<b>Denominación</b>',
						  'resuso'=>'<b>Responsable Uso</b>',
						  'maract'=>'<b>Marca</b>',
						  'modact'=>'<b>Modelo</b>',
						  'feccmpact'=>'<b>Fecha de Compra</b>',
						  'costo'=>'<b>Costo '.$ls_titulo.'</b>',
						  'catalogo'=>'<b>Catálogo</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codact'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'denact'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la columna
						 			   'resuso'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna
									   'maract'=>array('justification'=>'left','width'=>125), // Justificación y ancho de la columna
						 			   'modact'=>array('justification'=>'left','width'=>125), // Justificación y ancho de la columna
						 			   'feccmpact'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'costo'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'catalogo'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ai_montot,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera
		//		   Access: private 
		//	    Arguments: ai_montot // Total movimiento
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barrgan
		// Fecha Creación: 03/09/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('total'=>'TOTAL','monto'=>$ai_montot,'sigecof'=>''));
		$la_columna=array('total'=>'','monto'=>'','sigecof'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>2, // Mostrar Líneas
						 'fontSize' => 8, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>900, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>700), // Justificación y ancho de la columna
						               'monto'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
									   'sigecof'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_funciones_activos.php");
	$io_fun_activos=new class_funciones_activos();
	$ls_tipoformato=$io_fun_activos->uf_obtenervalor_get("tipoformato",0);
	global $ls_tipoformato;
	if($ls_tipoformato==1)
	{
		require_once("sigesp_saf_class_reportbsf.php");
		$io_report=new sigesp_saf_class_reportbsf();
		$ls_titulo_report="Bs.F.";
	}
	else
	{
		require_once("sigesp_saf_class_report.php");
		$io_report=new sigesp_saf_class_report();
		$ls_titulo_report="Bs.";
	}	
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ld_desde=$io_fun_activos->uf_obtenervalor_get("desde","");
	$ld_hasta=$io_fun_activos->uf_obtenervalor_get("hasta","");

	$ls_titulo="Listado de Activos ".$ls_titulo_report."";
	$ld_fecha="";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_nomemp=$arre["nombre"];
	$li_ordenact=$io_fun_activos->uf_obtenervalor_get("ordenact","");
	$ls_coddesde=$io_fun_activos->uf_obtenervalor_get("coddesde","");
	$ls_codhasta=$io_fun_activos->uf_obtenervalor_get("codhasta","");
	$ls_codresuso=$io_fun_activos->uf_obtenervalor_get("codresuso","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_saf_load_defactivos($ls_codemp,$li_ordenact,$ld_desde,$ld_hasta,$ls_coddesde,$ls_codhasta,$ls_codresuso); // Cargar el DS con los datos de la cabecera del reporte
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		/////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////
		$ls_desc_event="Generó un reporte de Listado de Activo. Desde el Activo   ".$ls_coddesde." hasta   ".$ls_codhasta;
		$io_fun_activos->uf_load_seguridad_reporte("SAF","sigesp_saf_r_defactivo.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		$io_pdf->ezStartPageNumbers(940,50,10,'','',1); // Insertar el número de página
		uf_print_encabezado_pagina($ls_titulo,$ld_fecha,$io_pdf);
		$li_totrow=$io_report->ds->getRowCount("codact");
		$i=0;
		$ld_total_costo=0;
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
	        $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$ls_codact=$io_report->ds->data["codact"][$li_i];
			$ls_denact=$io_report->ds->data["denact"][$li_i];
			$ls_nomresuso= $io_report->ds->data["nomres"][$li_i].",".$io_report->ds->data["aperes"][$li_i];
			$ls_maract=$io_report->ds->data["maract"][$li_i];
			$ls_modact=$io_report->ds->data["modact"][$li_i];
			$ls_estact=$io_report->ds->data["estact"][$li_i];
			$ls_catalogo=$io_report->ds->data["catalogo"][$li_i];
			$ld_fecmpact=$io_report->ds->data["feccmpact"][$li_i];
			$ld_fecmpactaux=$io_funciones->uf_convertirfecmostrar($ld_fecmpact);
			$li_costo=$io_report->ds->data["costo"][$li_i];
			if ($ls_estact=='D')
			   {
			     $li_costo = $li_costo*(-1);
			   }
			$ld_total_costo=$ld_total_costo+$li_costo;	
			$li_costo = number_format($li_costo,2,',','.');		
			$la_data[$li_i]=array('codact'=>$ls_codact,'denact'=>$ls_denact,'resuso'=>$ls_nomresuso,'maract'=>$ls_maract,
			                      'modact'=>$ls_modact,'feccmpact'=>$ld_fecmpactaux,'costo'=>$li_costo,
								  'catalogo'=>$ls_catalogo);
		}
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		$ld_total_costo=$io_fun_activos->uf_formatonumerico($ld_total_costo);
		uf_print_pie_cabecera($ld_total_costo,$io_pdf);
		unset($la_data);			
		if($lb_valido)
		{
			$io_pdf->ezStopPageNumbers(1,1);
			$io_pdf->ezStream();
		}
		else
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}		
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 