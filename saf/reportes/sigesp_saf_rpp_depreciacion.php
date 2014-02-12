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
	function uf_print_encabezado_pagina($as_titulo,$as_cmpmov,$ad_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_cmpmov // numero de comprobante de movimiento
		//	    		   ad_fecha // Fecha 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->setStrokeColor(0.9,0.9,0.9);
		$io_pdf->saveState();
		$io_pdf->line(20,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=312-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$io_pdf->addText(510,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(516,743,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codemp,$as_nomemp,$as_codact,$as_denact,$as_ideact,$ai_vidautil,$ad_fecmpact,$ad_feincact,$ai_costo,
							   $ai_cossal,$ai_vidautil,$ai_mondep,$ai_depmen,$ai_depanu,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codemp   // codigo de empresa
		//	    		   as_nomemp   // nombre de empresa
		//	    		   as_codact   // codigo de activo
		//	    		   as_ideact   // identificador del activo
		//	    		   ai_vidautil // vida util del activo
		//	    		   ad_fecmpact // fecha de compra del activo
		//	    		   ad_feincact // fecha de incorporacion del activo
		//	    		   ai_costo    // costo del activo
		//	    		   ai_cossal   // costo de salvamento (valor de rescate)
		//	    		   ai_vidautil // vida util
		//	    		   ai_mondep   // monto a depreciar
		//	    		   ai_depmen   // depreciacion mensual
		//	    		   ai_depanu   // depreciacion anual
		//	    		   io_pdf      // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
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
		$la_data=array(array('name'=>'<b>Organismo:</b>  '.$as_codemp." - ".$as_nomemp.''),
					   array ('name'=>'<b>Activo:</b>  '.$as_codact." - ".$as_denact.''),
					   array ('name'=>'<b>Identificador:</b>  '.$as_ideact.''),
					   array ('name'=>'<b>Fecha de Compra:</b>  '.$ad_fecmpact."                      <b>Fecha de Incorporación:</b> ".$ad_feincact.''),
					   array ('name'=>'<b>Vida Util:</b>  '.$ai_vidautil." Meses                                   <b>Valor de Rescate ".$ls_titulo.":</b> ".$ai_cossal.''),
					   array ('name'=>'<b>Costo '.$ls_titulo.':</b>  '.$ai_costo."                                 <b>Monto a Depreciar ".$ls_titulo.":</b> ".$ai_mondep.''),
					   array ('name'=>'<b>Depreciación Anual '.$ls_titulo.':</b>  '.$ai_depanu."           <b>Depreciación Mensual ".$ls_titulo.":</b> ".$ai_depmen.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar Líneas
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>2	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550); // Ancho Máximo de la tabla
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
		$io_pdf->ezSetDy(-5);
		global $ls_tipoformato;
		if($ls_tipoformato==0)
		{
		  $ls_titulo=" Bs.";
		}
		elseif($ls_tipoformato==1)
		{
		  $ls_titulo=" Bs.F.";
		}
		$la_columna=array('fecdep'=>'<b>Fecha de Depreciación</b>',
						  'mondepmen'=>'<b>Depreciación Mensual '.$ls_titulo.'</b>',
						  'mondepano'=>'<b>Depreciación Anual '.$ls_titulo.'</b>',
						  'mondepacu'=>'<b>Depreciación Acumulada '.$ls_titulo.'</b>',
						  'valcon'=>'<b>Valor Contable '.$ls_titulo.'</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 //'colGap'=>0.5, // separacion entre tablas
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('fecdep'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
						 			   'mondepmen'=>array('justification'=>'right','width'=>110), // Justificación y ancho de la columna
						 			   'mondepano'=>array('justification'=>'right','width'=>110), // Justificación y ancho de la columna
						 			   'mondepacu'=>array('justification'=>'right','width'=>110), // Justificación y ancho de la columna
						 			   'valcon'=>array('justification'=>'right','width'=>110))); // Justificación y ancho de la columna
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
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('total'=>""));
		$la_columna=array('total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 8, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
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

	$ls_titulo="<b>Reporte de Depreciación de Activos en ".$ls_titulo_report."</b>";
	$ld_fecha="";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_nomemp=$arre["nombre"];
	$ls_ordenact=$io_fun_activos->uf_obtenervalor_get("ordenact","");
	$ls_coddesde=$io_fun_activos->uf_obtenervalor_get("coddesde","");
	$ls_codhasta=$io_fun_activos->uf_obtenervalor_get("codhasta","");
	$ls_status=$io_fun_activos->uf_obtenervalor_get("status","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_saf_load_depactivos($ls_codemp,$ls_ordenact,$ls_coddesde,$ls_codhasta); // Cargar el DS con los datos de la cabecera del reporte
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		/////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////
		$ls_desc_event="Generó el reporte de Depreciacion de Activos. Desde el Activo   ".$ls_coddesde." hasta   ".$ls_codhasta;
		$io_fun_activos->uf_load_seguridad_reporte("SAF","sigesp_saf_r_depreciacion.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////////
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,"",$ld_fecha,$io_pdf); // Imprimimos el encabezado de la página
		$li_totrow=$io_report->ds->getRowCount("codact");
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
	        $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$ls_codact=$io_report->ds->data["codact"][$li_i];
			$ls_denact=$io_report->ds->data["denact"][$li_i];
			$ls_ideact=$io_report->ds->data["ideact"][$li_i];
			$li_vidautil=$io_report->ds->data["vidautil"][$li_i];
			$ld_fecmpact=$io_report->ds->data["feccmpact"][$li_i];
			$ld_feincact=$io_report->ds->data["fecincact"][$li_i];
			$ld_fecmpactaux=$io_funciones->uf_convertirfecmostrar($ld_fecmpact);
			$ld_feincactaux=$io_funciones->uf_convertirfecmostrar($ld_feincact);
			$li_costo=$io_report->ds->data["costo"][$li_i];
			$li_cossal=$io_report->ds->data["cossal"][$li_i];
			$li_vidautil=($li_vidautil * 12);
			$li_mondep=($li_costo-$li_cossal);
			$li_depmen=($li_mondep/$li_vidautil);
			$li_depanu=round($li_depmen*12);
			$li_costoaux=$io_fun_activos->uf_formatonumerico($li_costo);
			$li_cossalaux=$io_fun_activos->uf_formatonumerico($li_cossal);
			$li_vidautil=$io_fun_activos->uf_formatonumerico($li_vidautil);
			$li_mondep=$io_fun_activos->uf_formatonumerico($li_mondep);
			$li_depmen=$io_fun_activos->uf_formatonumerico($li_depmen);
			$li_depanu=$io_fun_activos->uf_formatonumerico($li_depanu);
			uf_print_cabecera($ls_codemp,$ls_nomemp,$ls_codact,$ls_denact,$ls_ideact,$li_vidautil,$ld_fecmpactaux,$ld_feincactaux,$li_costoaux,
							  $li_cossalaux,$li_vidautil,$li_mondep,$li_depmen,$li_depanu,$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_saf_select_dt_depactivo($ls_codemp,$ls_codact,$ls_ideact); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_montot=0;
				$li_totrow_det=$io_report->ds_detalle->getRowCount("ideact");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ld_fecdep=    $io_report->ds_detalle->data["fecdep"][$li_s];
					$li_mondepmen= $io_report->ds_detalle->data["mondepmen"][$li_s];
					$li_mondepano= $io_report->ds_detalle->data["mondepano"][$li_s];
					$li_mondepacu= $io_report->ds_detalle->data["mondepacu"][$li_s];
					$li_valcont=($li_costo-$li_mondepacu);
					$ld_fecdep=$io_funciones->uf_convertirfecmostrar($ld_fecdep);
					$li_mondepmen=$io_fun_activos->uf_formatonumerico($li_mondepmen);
					$li_mondepano=$io_fun_activos->uf_formatonumerico($li_mondepano);
					$li_mondepacuaux=$io_fun_activos->uf_formatonumerico($li_mondepacu);
					$li_valcon=$io_fun_activos->uf_formatonumerico($li_valcont);
					$la_data[$li_s]=array('fecdep'=>$ld_fecdep,'mondepmen'=>$li_mondepmen,'mondepano'=>$li_mondepano,'mondepacu'=>$li_mondepacuaux,'valcon'=>$li_valcon);
				}
               /*if($li_valcont!=$li_cossal)
				{
					$li_mondepmen=($li_valcont-$li_cossal);
					$li_mondepacu=($li_mondepacu+$li_mondepmen);
					$li_valcont=($li_valcont-$li_mondepmen);
					$ls_dia=substr($ld_feincactaux,0,6);
					$ls_annio=substr($ld_feincactaux,6,4);
					$li_annios=($li_vidautil/12);
					$ls_lastyear=($ls_annio+$li_annios);
					$ld_lastdate=$ls_dia.$ls_lastyear;
					$li_mondepanoaux= str_replace(".","",$li_mondepano);
					$li_mondepanoaux= str_replace(",",".",$li_mondepanoaux);

					$ld_lastdateaux=$io_funciones->uf_convertirdatetobd($ld_lastdate);
					$io_report->uf_saf_select_last_date($ls_codemp,$ls_codact,$ls_ideact,$ld_lastdateaux,$li_mondepmen,$li_mondepanoaux,
														$li_mondepacu);
					$ld_fecdep=$io_funciones->uf_convertirfecmostrar($ld_fecdep);
					$li_mondepmen=$io_fun_activos->uf_formatonumerico($li_mondepmen);
					$li_mondepacu=$io_fun_activos->uf_formatonumerico($li_mondepacu);
					$li_valcon=$io_fun_activos->uf_formatonumerico($li_valcont);
					$la_data[$li_s+1]=array('fecdep'=>$ld_lastdate,'mondepmen'=>$li_mondepmen,'mondepano'=>$li_mondepano,'mondepacu'=>$li_mondepacu,'valcon'=>$li_valcon);
				
				}*/
				$li_montot=$io_fun_activos->uf_formatonumerico($li_montot);
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				uf_print_pie_cabecera($li_montot,$io_pdf); // Imprimimos pie de la cabecera
				if($li_numpag==1)
				{
					$io_pdf->transaction('commit');
				}
				elseif ($io_pdf->ezPageCount>1)
				{
					if ($io_pdf->ezPageCount==$li_numpag)
					{// Hacemos el commit de los registros que se desean imprimir
						$io_pdf->transaction('commit');
					}
					else
					{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
						$io_pdf->transaction('rewind');
						$io_pdf->ezNewPage(); // Insertar una nueva página
						uf_print_cabecera($ls_codemp,$ls_nomemp,$ls_codact,$ls_denact,$ls_ideact,$li_vidautil,$ld_fecmpactaux,$ld_feincactaux,$li_costoaux,
										  $li_cossalaux,$li_vidautil,$li_mondep,$li_depmen,$li_depanu,$io_pdf); // Imprimimos la cabecera del registro
						uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
						uf_print_pie_cabecera($li_montot,$io_pdf); // Imprimimos pie de la cabecera
					}
				}
			}
			unset($la_data);			
		}
		if($lb_valido)
		{
			$io_pdf->ezStopPageNumbers(1,1);
			$io_pdf->ezStream();
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 