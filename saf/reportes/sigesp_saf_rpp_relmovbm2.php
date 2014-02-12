<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//  ESTE FORMATO SE IMPRIME EN Bs Y EN BsF. SEGUN LO SELECCIONADO POR EL USUARIO
	//  MODIFICADO POR: ING.YOZELIN BARRAGAN         FECHA DE MODIFICACION : 27/08/2007
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
	}//--------------------------------------------------------------------------------------------------------------------------------
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
		//     Modificado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->saveState();
		$io_pdf->line(50,40,950,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],22,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=504-($li_tm/2);
		$io_pdf->addText($tm,550,11,"<b>".$as_titulo."</b>"); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$ad_fecha);
		$tm=504-($li_tm/2);
		$io_pdf->addText(750,535,11,""); // Agregar la fecha
		$io_pdf->addText($tm,535,11,$ad_fecha); // Agregar la fecha
		$io_pdf->addText(750,555,11,""); // Agregar la fecha
		$io_pdf->addText(800,555,11,""); // Agregar la fecha
		$io_pdf->addText(928,570,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(934,563,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_estado,$as_municipio,$as_diremp,$as_codemp,$as_nomemp,$as_denunisrv,$as_denunidep,$as_periodo,$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: 
		//                 as_estado     //nombre del estado 
		//                 as_municipio  // nombre del municipio
		//                 as_diremp     // direccion de la empresa
		//                 as_codemp     // codigo de empresa
		//	    		   as_nomemp     // nombre de empresa
		//                 as_denunisrv  // nombre de la Unidad de Servicio
		//                 as_denunidep  // nombre de la Unidad de Trabajo o Dependencia
		//                 as_periodo    //  periodo en el que se hace el reporte
		//	    		   io_pdf        // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 04/12/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tipoformato;
		if($ls_tipoformato==0)
		{
		  $ls_titulo="Costo Bs.:";
		}
		elseif($ls_tipoformato==1)
		{
		  $ls_titulo="Costo Bs.F.:";
		}
		$la_data=array(array('name'=>'<b>1. ESTADO:</b>  '.$as_estado.''),
					   array('name'=>'<b>2. MUNICIPIO:</b> '.$as_municipio.''),
					   array('name'=>'<b>3. DIRECCION O LUGAR:</b>  '.$as_diremp.''),
					   array('name'=>'<b>4. DEPENDENCIA O UNIDAD PRIMARIA:</b>  '.$as_codemp."-".$as_nomemp.''),
					   array('name'=>'<b>5. SERVICIO:</b>  '.$as_denunisrv.''),
					   array('name'=>'<b>6. UNIDAD DE TRABAJO O DEPENDENCIA:</b> '.$as_denunidep.''),
					   array('name'=>'<b>7. PERIODO DE LA CUENTA: (MES Y AÑO)</b>  '.$as_periodo.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar Líneas
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>954, // Ancho de la tabla
						 'maxWidth'=>954); // Ancho Máximo de la tabla
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
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 04/12/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_columna=array('codgru'=>'<b>Grupo</b>',
						  'codsubgru'=>'<b>SubGrupo</b>',
						  'codsec'=>'<b>Seccion</b>',
						  'codcau'=>'<b>Cod. Mov.</b>',
						  'ideact'=>'<b>Identificador</b>',
						  'cantidad'=>'<b>Cantidad</b>',
						  'denact'=>'<b>Descripcion</b>',
						  'cmpmov'=>'<b>Comprobante</b>',
						  'feccmp'=>'<b>Fecha</b>',
						  'descmp'=>'<b>Detalle</b>',
						  'total_inc'=>'<b>Incorporacion Bs.</b>',
						  'total_desinc'=>'<b>Desincorporacion Bs.</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codgru'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna
						 			   'codsubgru'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'codsec'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna
						 			   'codcau'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna
						 			   'ideact'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'cantidad'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'denact'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'cmpmov'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'feccmp'=>array('justification'=>'center','width'=>70),// Justificación y ancho de la columna
						 			   'descmp'=>array('justification'=>'left','width'=>135),// Justificación y ancho de la columna
						 			   'total_inc'=>array('justification'=>'right','width'=>80),// Justificación y ancho de la columna
						 			   'total_desinc'=>array('justification'=>'right','width'=>95))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ai_moninc,$ai_mondesinc,$li_montot,&$io_pdf)
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
		$io_pdf->ezSetDy(-20);
		$la_data=array(array('name'=>'<b>TOTAL DE DEPARTAMENTO:</b>                                                                   '.$ai_moninc.'               '.$ai_mondesinc.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'right', // Orientación de la tabla
						 'width'=>480); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$la_data=array(array('total'=>'<b>TOTAL GENERAL:</b>                                                                          '.$ai_moninc.'          '.$ai_mondesinc.''));
		$la_columna=array('total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 11, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>480, // Ancho Máximo de la tabla
						 'xOrientation'=>'right'); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera


	function uf_print_firmas(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_firmas
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime las firmas
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 06/12/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$io_pdf->addText(50,150,9,"                JEFE DE LA UNIDAD DE TRABAJO              ");	
	$io_pdf->addText(50,120,9,"Nombre y Apellido:______________________________");
	$io_pdf->addText(50,100,9,"Firma:                    ______________________________");
	$io_pdf->addText(50,80,9,"C.I.:                        ______________________________");
	
	$io_pdf->addText(350,150,9,"            RESPONSABLE DE BIENES EN LA UNIDAD DE TRABAJO              ");	
	$io_pdf->addText(350,120,9,"Nombre y Apellido:______________________________");
	$io_pdf->addText(350,100,9,"Firma:                    ______________________________");
	$io_pdf->addText(350,80,9,"C.I.:                        ______________________________");	
	
	$io_pdf->addText(700,150,9,"                             UNIDAD DE BIENES              ");	
	$io_pdf->addText(700,120,9,"Nombre y Apellido:______________________________");
	$io_pdf->addText(700,100,9,"Firma:                    ______________________________");
	$io_pdf->addText(700,80,9,"C.I.:                        ______________________________");	
	}// end function uf_print_firmas	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_funciones_activos.php");
	$io_fun_activos=new class_funciones_activos();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ld_desde=$io_fun_activos->uf_obtenervalor_get("desde","");
	$ld_hasta=$io_fun_activos->uf_obtenervalor_get("hasta","");
	$ld_fecha="";
	$ls_titulo="<b>RELACION DEL MOVIMIENTO DE BIENES MUEBLES</b>";
	if(($ld_desde!="")&&($ld_hasta!=""))
	{
	    $periodo = substr($ld_desde,3,2).'-'.substr($ld_desde,6,4);
		$ld_fecha="Desde:".$ld_desde."  Hasta:".$ld_hasta."";
	}
	else
	{
	 $periodo = "NO DEFINIDO";
	 $ld_fecha="RANGO DE FECHA NO DEFINIDA";
	}
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_nomemp=$arre["nombre"];
	$ls_estemp=$arre["estemp"];
	$ls_direccion=$arre["direccion"];
	$ls_ordenact=$io_fun_activos->uf_obtenervalor_get("ordenact","");
	$ls_coddesde=$io_fun_activos->uf_obtenervalor_get("coddesde","");
	$ls_codhasta=$io_fun_activos->uf_obtenervalor_get("codhasta","");
	$ls_coduniadm=$io_fun_activos->uf_obtenervalor_get("coduniadm","");
	$ls_denuniadm=$io_fun_activos->uf_obtenervalor_get("denuniadm","");
	$ls_codgru=$io_fun_activos->uf_obtenervalor_get("codgru","");
	$ls_codsubgru=$io_fun_activos->uf_obtenervalor_get("codsubgru","");
	$ls_codsec=$io_fun_activos->uf_obtenervalor_get("codsec","");
	$ls_tipoformato=$io_fun_activos->uf_obtenervalor_get("tipoformato",0);
	$ls_orden=$io_fun_activos->uf_obtenervalor_get("orden",0);
	global $ls_tipoformato;
	if($ls_tipoformato==1)
	{
		require_once("sigesp_saf_class_reportbsf.php");
		$io_report=new sigesp_saf_class_reportbsf();
	}
	else
	{
		require_once("sigesp_saf_class_report.php");
		$io_report=new sigesp_saf_class_report();
	}	
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_saf_load_dt_relmovbienes($ls_codemp,$ls_coduniadm,$ld_desde,$ld_hasta,$ls_coddesde,$ls_codhasta, $ls_ordenact,$ls_codgru,$ls_codsubgru,$ls_codsec,$ls_orden); // Cargar el DS con los datos de la cabecera del reporte
	
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
		$ls_desc_event="Generó un reporte de Activo. Desde el activo   ".$ls_coddesde." hasta   ".$ls_codhasta;
		//$io_fun_activos->uf_load_seguridad_reporte("SAF","sigesp_saf_r_relmovbm2.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		$io_pdf->ezStartPageNumbers(940,50,10,'','',1); // Insertar el número de página
		uf_print_encabezado_pagina($ls_titulo,"",$ld_fecha,$io_pdf); // Imprimimos el encabezado de la página
		$li_totrow=$io_report->ds->getRowCount("codact");
		$i=0;
	        $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount;
			$lb_valido=$io_report->uf_saf_load_dt_relmovbienes($ls_codemp,$ls_coduniadm,$ld_desde,$ld_hasta,$ls_coddesde,$ls_codhasta,$ls_codgru,$ls_codsubgru,$ls_codsec,$ls_orden); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_moninc=0.00;
				$li_mondesinc=0.00;
				$li_montot=0.00;
				$li_totrow_det=$io_report->ds_detalle->getRowCount("codact");
				$la_data="";
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
				    $ls_estatus=$io_report->ds_detalle->data["estatus"][$li_s];
					$ls_codgru=$io_report->ds_detalle->data["codgru"][$li_s];
					$ls_codsubgru=$io_report->ds_detalle->data["codsubgru"][$li_s];
					$ls_codsec=$io_report->ds_detalle->data["codsec"][$li_s];
					$ls_codcau=$io_report->ds_detalle->data["codcau"][$li_s];
					$ls_ideact=$io_report->ds_detalle->data["ideact"][$li_s];
					$ls_cantidad=$io_report->ds_detalle->data["cantidad"][$li_s];
					$ls_denact=$io_report->ds_detalle->data["denact"][$li_s];
					$ls_cmpmov= $io_report->ds_detalle->data["cmpmov"][$li_s];
					$ld_feccmp=$io_report->ds_detalle->data["feccmp"][$li_s];
					$ld_feccmp=$io_funciones->uf_convertirfecmostrar($ld_feccmp);
					$ls_descmp=$io_report->ds_detalle->data["descmp"][$li_s];
					$li_total=$io_report->ds_detalle->data["total"][$li_s];
//					$li_total=$io_fun_activos->uf_formatonumerico($li_total);
					if ($ls_estatus == 'I')
					{
					 $li_totinc = $io_fun_activos->uf_formatonumerico($li_total);
					 $li_totdesinc = '';
					 $li_moninc = $li_moninc +  $li_total;
					}
					else
					{
					 $li_totinc = '';
					 $li_totdesinc = $io_fun_activos->uf_formatonumerico($li_total);
					 $li_mondesinc = $li_mondesinc +  $li_total;
					}
					$la_data[$li_s]=array('codgru'=>$ls_codgru,'codsubgru'=>$ls_codsubgru,'codsec'=>$ls_codsec,'codcau'=>$ls_codcau,										  'ideact'=>$ls_ideact,'cantidad'=>$ls_cantidad,'denact'=>$ls_denact,
										  'cmpmov'=>$ls_cmpmov,'feccmp'=>$ld_feccmp,'descmp'=>$ls_descmp,'total_inc'=>$li_totinc,'total_desinc'=>$li_totdesinc);
				}
				//$li_montot=$io_fun_activos->uf_formatonumerico($li_montot);
				if($la_data!="")
				{
					$i=$i +1;
					uf_print_cabecera($ls_estemp,'',$ls_direccion,$ls_codemp,$ls_nomemp,$ls_denuniadm,'',$periodo,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					//uf_print_pie_cabecera($li_montot,$io_pdf); // Imprimimos pie de la cabecera
					if ($io_pdf->ezPageCount==$li_numpag)
					{// Hacemos el commit de los registros que se desean imprimir
						$io_pdf->transaction('commit');
						$li_montot = $li_moninc + $li_mondesinc;
						$li_montot = $io_fun_activos->uf_formatonumerico($li_montot);
						$li_moninc= $io_fun_activos->uf_formatonumerico($li_moninc);
						$li_mondesinc = $io_fun_activos->uf_formatonumerico($li_mondesinc);
						uf_print_pie_cabecera($li_moninc,$li_mondesinc,$li_montot,$io_pdf); // Imprimimos pie de la cabecera
					}
					else
					{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
						$io_pdf->transaction('rewind');
						$io_pdf->ezNewPage(); // Insertar una nueva página
					uf_print_cabecera($ls_estemp,'',$ls_direccion,$ls_codemp,$ls_nomemp,$ls_denuniadm,'','',$io_pdf);  // Imprimimos la cabecera del registro
						uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					}
				}
			  unset($la_data);
			}
		uf_print_firmas(&$io_pdf);	
		if(($lb_valido)&&($i>0))
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