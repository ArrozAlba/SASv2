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
	function uf_print_encabezado_pagina($as_titulo,$ad_fecha,$as_codemp,$as_nomemp,$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   ad_fecha // Fecha 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 17/12/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(15,40,585,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$io_pdf->addText(540,770,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(546,764,6,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
				$la_data=array(array('name'=>'<b>ORGANISMO: '.$as_codemp." - ".$as_nomemp.'</b>'),
					   array('name'=>'<b>UNIDAD DE BIENES</b>'),
					   array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar Líneas
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codemp   // codigo de empresa
		//	    		   as_nomemp   // nombre de empresa
		//	    		   io_pdf      // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 17/12/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $la_datatit[0]=array('codgru'=>'<b>Grupo</b>','codsubgru'=>'<b>SubGrupo</b>','codsec'=>'<b>Seccion</b>','codact'=>'<b>Codigo</b>',
				             'ideact'=>'<b>Identificacion</b>','denact'=>'<b>Descripcion</b>','feccmp'=>'<b>Fecha</b>','cantidad'=>'<b>Cant.</b>','costo'=>'<b>Costo</b>');
		$la_columna=array('codgru'=>'',
						  'codsubgru'=>'',
						  'codsec'=>'',
						  'codact'=>'',
						  'ideact'=>'',
						  'denact'=>'',
						  'feccmp'=>'',
						  'cantidad'=>'',
						  'costo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codgru'=>array('justification'=>'center','width'=>35), // Justificación y ancho de la columna
						 			   'codsubgru'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna
									   'codsec'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna
									   'codact'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'ideact'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
									   'denact'=>array('justification'=>'center','width'=>140), // Justificación y ancho de la columna
									   'feccmp'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
									   'cantidad'=>array('justification'=>'center','width'=>40), // Justificación y ancho de la columna
									   'costo'=>array('justification'=>'center','width'=>60))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------


    function uf_print_detctacon($as_ctacon,$as_denctacon,$io_pdf)
	{
	    $io_pdf->ezSetDy(-5);
		$la_datactacon=array(array('sc_cuenta'=>$as_ctacon,'denominacion'=>$as_denctacon));
		$la_columna=array('sc_cuenta'=>'',
						  'denominacion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('sc_cuenta'=>array('justification'=>'left','width'=>100), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>440))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datactacon,$la_columna,'',$la_config);	
	}

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 17/12/2007
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
		
		$la_columna=array('codgru'=>'',
						  'codsubgru'=>'',
						  'codsec'=>'',
						  'codact'=>'',
						  'ideact'=>'',
						  'denact'=>'',
						  'feccmp'=>'',
						  'cantidad'=>'',
						  'costo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codgru'=>array('justification'=>'center','width'=>35), // Justificación y ancho de la columna
						 			   'codsubgru'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna
									   'codsec'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna
									   'codact'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'ideact'=>array('justification'=>'center','width'=>65),  // Justificación y ancho de la columna
									   'denact'=>array('justification'=>'left','width'=>140),   // Justificación y ancho de la columna
									   'feccmp'=>array('justification'=>'center','width'=>50),  // Justificación y ancho de la columna
									   'cantidad'=>array('justification'=>'center','width'=>40),  // Justificación y ancho de la columna
									   'costo'=>array('justification'=>'right','width'=>60))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ai_cantotact,$ai_montotact,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera
		//		   Access: private 
		//	    Arguments: ai_montot // Total movimiento
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing.Arnaldo Suárez
		// Fecha Creación: 17/12/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('total'=>'TOTAL','cant_act'=>$ai_cantotact,'monto_act'=>$ai_montotact));
		$la_columna=array('total'=>'','cant_act'=>'','monto_act'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 8, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>440),  // Justificación y ancho de la columna
						               'cant_act'=>array('justification'=>'center','width'=>40),// Justificación y ancho de la columna
						               'monto_act'=>array('justification'=>'right','width'=>60))); // Justificación y ancho de la columna
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

	$ls_titulo= "REPORTE DE BIENES MUEBLES POR CUENTA CONTABLE DETALLADO EN ".$ls_titulo_report."";
	$ld_fecha="";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_nomemp=$arre["nombre"];
	$li_ordenact=$io_fun_activos->uf_obtenervalor_get("ordenact","");
	$ls_coddesde=$io_fun_activos->uf_obtenervalor_get("coddesde","");
	$ls_codhasta=$io_fun_activos->uf_obtenervalor_get("codhasta","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_saf_load_biemuectacont($ls_codemp,$li_ordenact,$ld_desde,$ld_hasta,$ls_coddesde,$ls_codhasta); // Cargar el DS con los datos de la cabecera del reporte
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print("alert('No hay nada que Reportar');"); 
		print("close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		/////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////
		$ls_desc_event="Generó un reporte de Bienes Muebles por Cuenta Contable Detallado";
		$io_fun_activos->uf_load_seguridad_reporte("SAF","sigesp_saf_r_biemuectacont.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////
		error_reporting(E_ALL);
		set_time_limit(3600);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.6,4.8,3,3); // Configuración de los margenes en centímetros
		$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página
		uf_print_encabezado_pagina($ls_titulo,$ld_fecha,$ls_codemp,$ls_nomemp,$io_pdf); // Imprimimos el encabezado de la página
		$li_totrow=$io_report->ds->getRowCount("sc_cuenta");
		$i=0;
		$li_cos_total = 0.00;
		$li_act_total  = 0;
		  for($li_i=1;$li_i<=$li_totrow;$li_i++)
		  {
			$la_data = "";
			$la_datactacon = "";
			$li_tot_act_ctacon = 0;
		    $li_tot_cos_ctacon = 0.00;
			$io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$ls_ctacon=$io_report->ds->data["sc_cuenta"][$li_i];
			$ls_denctacon=$io_report->ds->data["denominacion"][$li_i];
		    uf_print_detctacon($ls_ctacon,$ls_denctacon,$io_pdf);
			uf_print_cabecera($io_pdf);
		    $lb_valido=$io_report->uf_saf_load_dt_biemuectacont($ls_codemp,$li_ordenact,$ld_desde,$ld_hasta,$ls_ctacon); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->ds_detalle->getRowCount("ideact");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_codgru =  $io_report->ds_detalle->data["codgru"][$li_s];
					$ls_codsubgru =  $io_report->ds_detalle->data["codsubgru"][$li_s];
					$ls_codsec =  $io_report->ds_detalle->data["codsec"][$li_s];
					$ls_codact =  $io_report->ds_detalle->data["codact"][$li_s];
					$ls_ideact =  $io_report->ds_detalle->data["ideact"][$li_s];
					$ls_denact =  $io_report->ds_detalle->data["denact"][$li_s];
					$ld_feccmp =  $io_report->ds_detalle->data["fecha"][$li_s];
					$ld_feccmp =  $io_funciones->uf_convertirfecmostrar($ld_feccmp);
					$li_cantidad= $io_report->ds_detalle->data["cantidad"][$li_s];
					$li_costo= $io_report->ds_detalle->data["costo"][$li_s];
					$li_tot_act_ctacon = $li_tot_act_ctacon + $li_cantidad;
					$li_tot_cos_ctacon= $li_tot_cos_ctacon + $li_costo;			   
					$li_costo = $io_fun_activos->uf_formatonumerico($li_costo);
				$la_data[$li_s]=array('codgru'=>$ls_codgru,'codsubgru'=>$ls_codsubgru,'codsec'=>$ls_codsec,
					                  'codact'=>$ls_codact,'ideact'=>$ls_ideact,'denact'=>$ls_denact,'feccmp'=>$ld_feccmp,
									  'cantidad'=>$li_cantidad,'costo'=>$li_costo);				  	
				} // for interno
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
				$li_tot_cos_ctacon = $io_fun_activos->uf_formatonumerico($li_tot_cos_ctacon);
				uf_print_pie_cabecera($li_tot_act_ctacon,$li_tot_cos_ctacon,$io_pdf);
			}		
		  }// end for principal			
		if(($lb_valido))
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
		unset($la_data);
	    unset($la_datactacon);			
		unset($io_pdf);
	}
		 
	unset($io_report);
	unset($io_funciones);
?> 