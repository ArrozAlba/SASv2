<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Reporte de Cuentas por Pagar
//  ORGANISMO: Ninguno en particular
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_solicitudes.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_fechadesde,$as_fechahasta,$as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_fechadesde // Intervalo de Busqueda
		//	    		   as_fechahasta // Intervalo de Busqueda
		//	    		   as_titulo     // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno Ing. Luis Lang
		// Fecha Creación: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_denominacion="<b> en Bs.F.</b>";
		}
		else
		{
			$ls_denominacion="<b> en Bs.</b>";
		}
		$ls_periodo="<b>Del: </b>".$as_fechadesde."   "."<b>Al: </b>".$as_fechahasta;	
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,520,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo.$ls_denominacion);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,535,11,$as_titulo.$ls_denominacion); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$ls_periodo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,525,11,$ls_periodo); // Agregar el título
		$io_pdf->addText(740,570,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(746,564,6,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codigo,$as_nombre,$ad_saldo_anterior,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codigo         // Codigo del Proveedor/Beneficiario
		//	    		   as_nombre         // Nombre del Proveedor/Beneficiario
		//	    		   ad_saldo_anterior // Saldo hasta la fecha de inicio del Intervalo
		//	    		   io_pdf            // Instancia de objeto pdf
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno Ing. Luis Lang
		// Fecha Creación: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data    = array(array('codigo'=>'<b>Código:</b>','codproben'=>$as_codigo));
		$la_columna = array('codigo'=>'','codproben'=>'');
		$la_config  = array('showHeadings'=>0, // Mostrar encabezados
							'fontSize' => 10,  // Tamaño de Letras
							'showLines'=>0,    // Mostrar Líneas
							'shaded'=>0,       // Sombra entre líneas
							'xOrientation'=>'center', // Orientación de la tabla
							'colGap'=>1,
							'width'=>530,
							'cols'=>array('codigo'=>array('justification'=>'left','width'=>60),
										  'codproben'=>array('justification'=>'left','width'=>670))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data    = array(array('nombre'=>'<b>Nombre:</b>','nomproben'=>$as_nombre,'saldo'=>'<b>Saldo Anterior:</b> '.$ad_saldo_anterior));
		$la_columna = array('nombre'=>'','nomproben'=>'','saldo'=>'');
		$la_config  = array('showHeadings'=>0, // Mostrar encabezados
							'fontSize' => 10,  // Tamaño de Letras
							'showLines'=>0,    // Mostrar Líneas
							'shaded'=>0,       // Sombra entre líneas
							'xOrientation'=>'center', // Orientación de la tabla
							'colGap'=>1,
							'width'=>530,
							'cols'=>array('nombre'=>array('justification'=>'left','width'=>60),
										  'nomproben'=>array('justification'=>'left','width'=>470),
										  'saldo'=>array('justification'=>'left','width'=>200))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_solicitudes_actuales($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_solicitudes_actuales
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_datatit= array(array('subtitulo'=>'<b>Solicitudes</b>'));
		$la_columna= array('subtitulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('subtitulo'=>array('justification'=>'left','width'=>730))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_datatit,$la_columna,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		$la_columna = array('numsol'=>'<b>Documento</b>','consol'=>'<b>Concepto</b>','procedencia'=>'<b>Procedencia</b>','fecha'=>'<b>F. Emisión</b>','debe'=>'<b>Debe</b>','haber'=>'<b>Haber</b>','saldo'=>'<b>Saldo</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('numsol'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'consol'=>array('justification'=>'left','width'=>280),
									   'procedencia'=>array('justification'=>'center','width'=>60),
									   'fecha'=>array('justification'=>'center','width'=>60),
									   'debe'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'haber'=>array('justification'=>'right','width'=>80),
									   'saldo'=>array('justification'=>'right','width'=>85))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end uf_print_detalle_solicitudes_actuales
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_ndnc_actuales($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_ndnc_actuales
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_datatit= array(array('subtitulo'=>'<b>Notas Debito/Credito</b>'));
		$la_columna= array('subtitulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('subtitulo'=>array('justification'=>'left','width'=>730))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_datatit,$la_columna,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		$la_columna = array('numsol'=>'<b>Documento</b>','consol'=>'<b>Concepto</b>','procedencia'=>'Procedencia','fecha'=>'<b>F. Emisión</b>','debe'=>'<b>Debe</b>','haber'=>'<b>Haber</b>','saldo'=>'<b>Saldo</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('numsol'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'consol'=>array('justification'=>'left','width'=>280),
									   'procedencia'=>array('justification'=>'center','width'=>60),
									   'fecha'=>array('justification'=>'center','width'=>60),
									   'debe'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'haber'=>array('justification'=>'right','width'=>80),
									   'saldo'=>array('justification'=>'right','width'=>85))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_detalle_ndnc_actuales
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_pagos_actuales($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_pagos_actuales
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_datatit= array(array('subtitulo'=>'<b>Pagos</b>'));
		$la_columna= array('subtitulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('subtitulo'=>array('justification'=>'left','width'=>730))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_datatit,$la_columna,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		$la_columna = array('numsol'=>'<b>Documento</b>','consol'=>'<b>Concepto</b>','procedencia'=>'Procedencia','fecha'=>'<b>F. Emisión</b>','debe'=>'<b>Debe</b>','haber'=>'<b>Haber</b>','saldo'=>'<b>Saldo</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('numsol'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'consol'=>array('justification'=>'left','width'=>280),
									   'procedencia'=>array('justification'=>'center','width'=>60),
									   'fecha'=>array('justification'=>'center','width'=>60),
									   'debe'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'haber'=>array('justification'=>'right','width'=>80),
									   'saldo'=>array('justification'=>'right','width'=>85))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_detalle_pagos_actuales
	//--------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_totales($ai_totaldebe,$ai_totalhaber,$ai_totalsaldo,&$io_pdf)
	 {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: ai_totaldebe  // Total de la Columna Debe
		//	   			   ai_totalhaber // Total de la Columna Haber
		//	   			   ai_totalsaldo // Total de la Columna Saldo
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los totales por proveedor/beneficiario
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $la_data=array(array('name'=>'__________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xPos'=>715); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]= array('total'=>'<b>TOTALES:</b>','debe'=>$ai_totaldebe,'haber'=>$ai_totalhaber,'saldo'=>$ai_totalsaldo);
		$la_columna = array('total'=>'','debe'=>'','haber'=>'','saldo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>480),
									   'debe'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'haber'=>array('justification'=>'right','width'=>80),
									   'saldo'=>array('justification'=>'right','width'=>85))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	 }// end function uf_print_totales
	
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_totales_generales($ai_totgendeb,$ai_totgenhab,$ai_totgensal,&$io_pdf)
	 {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: ai_totaldebe  // Total de la Columna Debe
		//	   			   ai_totalhaber // Total de la Columna Haber
		//	   			   ai_totalsaldo // Total de la Columna Saldo
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los totales por proveedor/beneficiario
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totgendeb= number_format($ai_totgendeb,2,',','.');
		$ai_totgenhab= number_format($ai_totgenhab,2,',','.');
		if(doubleval($ai_totgensal)>0)
		{
			$ai_totgensal= "(".number_format($ai_totgensal,2,',','.').")";
		}
		else
		{
			$ai_totgensal= abs($ai_totgensal);
			$ai_totgensal= number_format($ai_totgensal,2,',','.');
		}
	   
	   
	    $la_data=array(array('name'=>'__________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xPos'=>715); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]= array('total'=>'<b>TOTAL GENERAL:</b>','debe'=>$ai_totgendeb,'haber'=>$ai_totgenhab,'saldo'=>$ai_totgensal);
		$la_columna = array('total'=>'','debe'=>'','haber'=>'','saldo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>480),
									   'debe'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'haber'=>array('justification'=>'right','width'=>80),
									   'saldo'=>array('justification'=>'right','width'=>85))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	 }// end function uf_print_totales
	
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_datastore.php");
	require_once("sigesp_cxp_class_report.php");
	$io_report=new sigesp_cxp_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$io_dsctasxpagar= new class_datastore();
	$io_dsctasxpagar = new class_datastore();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo= "<b>Reporte de Cuentas Por Pagar</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_proben=$io_fun_cxp->uf_obtenervalor_get("tipproben","");
	$ls_codprobendes=$io_fun_cxp->uf_obtenervalor_get("codprobendes","");
	$ls_codprobenhas=$io_fun_cxp->uf_obtenervalor_get("codprobenhas","");
	$ld_fecemides=$io_fun_cxp->uf_obtenervalor_get("fecemides","");
	$ld_fecemihas=$io_fun_cxp->uf_obtenervalor_get("fecemihas","");
	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_cxp_class_reportbsf.php");
		$io_report=new sigesp_cxp_class_reportbsf();
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	$ls_estretiva=$_SESSION["la_empresa"]["estretiva"];
	$lb_valido= $io_report->uf_select_solicitudes($ls_proben,$ls_codprobendes,$ls_codprobenhas,$ld_fecemides,$ld_fecemihas);
	if (!$lb_valido) // Existe algún error ó no hay registros.
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar ');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','landscape');                       // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3);                            // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ld_fecemides,$ld_fecemihas,$ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(750,50,8,'','',1); // Insertar el número de página
		$li_totrow= $io_report->DS->getRowCount('tipproben');
		$li_totgendeb=0;
		$li_totgenhab=0;
		$li_totgensal=0;
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
			$li_salsol=0;
			$li_totaldebe=0;
			$li_totalhaber=0;
			$li_totalsaldo=0;
			$ls_tipproben= $io_report->DS->getValue('tipproben',$li_i);
			$ls_cedbene= $io_report->DS->getValue('ced_bene',$li_i);
			$ls_codpro= $io_report->DS->getValue('cod_pro',$li_i);
			$ls_nombre= $io_report->DS->getValue('nombre',$li_i);
			if($ls_tipproben=="B")
			{
				$ls_codigo=$ls_cedbene;
			}
			else
			{
				$ls_codigo=$ls_codpro;
			}
			if($lb_valido)
			{
				$li_monsolpre=0;
//////////////////////////////////        SALDO PREVIO        //////////////////////////////////
				$lb_valido= $io_report->uf_select_solicitudes_previas($ls_tipproben,$ls_codpro,$ls_cedbene,$ld_fecemides,
																	  $ld_fecemihas);
				if($lb_valido)
				{
					$li_solcont=0;
					$li_solanul=0;
					$li_totsolprevias= $io_report->ds_solprevias->getRowCount('monsol');
					for($li_j=1;$li_j<=$li_totsolprevias;$li_j++)
					{
						$li_numsolprevias= $io_report->ds_solprevias->getRowCount('numsol');
						$ls_estatus= $io_report->ds_solprevias->getValue('estatus',$li_j);
						$li_monsol= $io_report->ds_solprevias->getValue('monsol',$li_j);
						$ls_numsolp= $io_report->ds_solprevias->getValue('numsol',$li_j);
						if($ls_estretiva=="B")
						{
							$li_monretiva=$io_report->uf_select_det_deducciones_solpag($ls_numsolp);
							$li_monsol=$li_monsol+$li_monretiva;
						}
						switch ($ls_estatus)
						{
							case "C":
								$li_solcont=($li_solcont+$li_monsol);
							break;
							case "A":
								$li_solanul=($li_solanul+$li_monsol);
							break;
						}
					}
					$li_monsolpre=($li_solcont-$li_solanul);
				}
				$li_monpagpre=0;
				$lb_valido= $io_report->uf_select_pagosprevios($ls_tipproben,$ls_codpro,$ls_cedbene,$ld_fecemides,$ld_fecemihas,
															   $li_monpagpre,$li_monretpre);
				if($ls_estretiva=="B")
				{ 
					$li_monpagpre=$li_monpagpre+$li_monretpre;
				}
				$li_monsalant=($li_monsolpre-$li_monpagpre);
				if($li_monsalant>0)
				{
					$ls_saldoanterior= number_format($li_monsalant,2,',','.');
					$ls_saldoanterior="(".$ls_saldoanterior.")";
				}
				else
				{
					$ls_saldoanterior= abs($li_monsalant);
					$ls_saldoanterior= number_format($ls_saldoanterior,2,',','.');
				}
				uf_print_cabecera($ls_codigo,$ls_nombre,$ls_saldoanterior,$io_pdf);
//////////////////////////////////        SALDO PREVIO        //////////////////////////////////
//////////////////////////////////    SOLICITUDES ACTUALES    //////////////////////////////////
				$lb_valido= $io_report->uf_select_solicitudesactualescxp($ls_tipproben,$ls_cedbene,$ls_codpro,$ld_fecemides,
																	     $ld_fecemihas);
				if($lb_valido)
				{
					$li_salsol=$li_monsalant;
					$li_totsolact= $io_report->ds_solactuales->getRowCount('numsol');
					for($li_j=1;$li_j<=$li_totsolact;$li_j++)
					{
						$ls_numsol= $io_report->ds_solactuales->getValue('numsol',$li_j);
						$ls_estprodoc= $io_report->ds_solactuales->getValue('estprodoc',$li_j);
						$ls_consol= $io_report->ds_solactuales->getValue('consol',$li_j);
						$li_monsol= $io_report->ds_solactuales->getValue('monsol',$li_j); //Monto de la Solicitudes de Pago actuales.
						$ld_fecsol= $io_report->ds_solactuales->getValue('fecha',$li_j);
						if($ls_estretiva=="B")
						{
							$li_monretiva=$io_report->uf_select_det_deducciones_solpag($ls_numsol);
							$li_monsol=$li_monsol+$li_monretiva;
						}
						$li_salsol= $li_salsol+$li_monsol;
						$li_totalhaber=$li_totalhaber+$li_monsol;
						$ld_fecsol=$io_funciones->uf_convertirfecmostrar($ld_fecsol);
						$ls_monto= number_format($li_monsol,2,',','.');
						$ls_salsol= "(".number_format($li_salsol,2,',','.').")";
						$la_datasol[$li_j]= array('numsol'=>$ls_numsol,'consol'=>$ls_consol,'procedencia'=>"",'fecha'=>$ld_fecsol,'debe'=>"0,00",'haber'=>$ls_monto,'saldo'=>$ls_salsol);
					}
					if($li_totsolact>0)
					{
						uf_print_detalle_solicitudes_actuales($la_datasol,&$io_pdf);
					}
					$io_report->ds_solactuales->reset_ds();
					unset($la_datasol);
//////////////////////////////////    SOLICITUDES ACTUALES    //////////////////////////////////
//////////////////////////////////    NOTAS DEBITO/CREDITO    //////////////////////////////////
					$lb_valido=$io_report->uf_select_informacionndnc($ls_tipproben,$ls_codigo,$ld_fecemides,$ld_fecemihas,"");
					if($lb_valido)
					{
						$li_totndnc= $io_report->ds_detndnc->getRowCount('numsol');
						for($li_j=1;$li_j<=$li_totndnc;$li_j++)
						{
							$ls_numdc= $io_report->ds_detndnc->getValue('numdc',$li_j);
							$ls_codope= $io_report->ds_detndnc->getValue('codope',$li_j);
							$ls_desope= $io_report->ds_detndnc->getValue('desope',$li_j);
							$li_monto= $io_report->ds_detndnc->getValue('monto',$li_j); //Monto de la Solicitudes de Pago actuales.
							$ld_fecope= $io_report->ds_detndnc->getValue('fecope',$li_j);
							if($ls_codope=="ND")
							{
								$li_salsol= $li_salsol+$li_monto;
								$li_debe=0;
								$li_haber=$li_monto;
								$ls_procedencia="Debito";
								$li_totalhaber=$li_totalhaber+$li_monto;
							}
							else
							{
								$li_salsol= $li_salsol-$li_monto;
								$li_debe=$li_monto;
								$li_haber=0;
								$ls_procedencia="Credito";
								$li_totaldebe=$li_totaldebe+$li_monto;
							}
							$ld_fecope=$io_funciones->uf_convertirfecmostrar($ld_fecope);
							$li_debe= number_format($li_debe,2,',','.');
							$li_haber= number_format($li_haber,2,',','.');
							$li_salsol=round($li_salsol,2);
							if(doubleval($li_salsol)>0)
							{
								$ls_salsol= "(".number_format($li_salsol,2,',','.').")";
							}
							else
							{
								$ls_salsol= abs($li_salsol);
								$ls_salsol= number_format($ls_salsol,2,',','.');
							}
							$la_datandnc[$li_j]= array('numsol'=>$ls_numdc,'consol'=>$ls_desope,'procedencia'=>$ls_procedencia,'fecha'=>$ld_fecope,'debe'=>$li_debe,'haber'=>$li_haber,'saldo'=>$ls_salsol);
						}
						if($li_totndnc>0)
						{
							uf_print_detalle_ndnc_actuales($la_datandnc,&$io_pdf);
						}
					}
//////////////////////////////////    NOTAS DEBITO/CREDITO    //////////////////////////////////
//////////////////////////////////       PAGOS ACTUALES       //////////////////////////////////
					unset($la_datapag);
					$lb_valido=$io_report->uf_select_informacionpagoscxp($ls_tipproben,$ls_cedbene,$ls_codpro,$ld_fecemides,
																		 $ld_fecemihas);
					if($lb_valido)
					{
						$li_totpagact= $io_report->ds_pagactuales->getRowCount('numsol');
						for($li_j=1;$li_j<=$li_totpagact;$li_j++)
						{
							$ls_salsol="";
							$ls_numsol= $io_report->ds_pagactuales->getValue('numsol',$li_j);
							$ls_codope= $io_report->ds_pagactuales->getValue('codope',$li_j);
							$ls_conmov= $io_report->ds_pagactuales->getValue('conmov',$li_j);
							$li_monto= $io_report->ds_pagactuales->getValue('monto',$li_j); //Monto de la Solicitudes de Pago actuales.
							$ld_fecmov= $io_report->ds_pagactuales->getValue('fecmov',$li_j);
							if($ls_estretiva=="B")
							{
								$li_monretiva=$io_report->uf_select_det_deducciones_solpag($ls_numsol);
								$li_monto=$li_monto+$li_monretiva;
							}
							$li_salsol= $li_salsol-$li_monto;
							$li_totaldebe=$li_totaldebe+$li_monto;
							$ld_fecmov=$io_funciones->uf_convertirfecmostrar($ld_fecmov);
							$li_salsol=round($li_salsol,2);
							if(doubleval($li_salsol)>0)
							{
								$ls_salsol= "(".number_format($li_salsol,2,',','.').")";
							}
							else
							{
								$ls_salsol= abs($li_salsol);
								$ls_salsol= number_format($ls_salsol,2,',','.');
							}
							$ls_procedencia="";
							if($ls_codope=="CH")
							{
								$ls_procedencia="Cheque";
							}
							$ls_monto= number_format($li_monto,2,',','.');
							$la_datapag[$li_j]= array('numsol'=>$ls_numsol,'consol'=>$ls_conmov,'procedencia'=>$ls_procedencia,'fecha'=>$ld_fecmov,'debe'=>$ls_monto,'haber'=>"0,00",'saldo'=>$ls_salsol);
						}
						if($li_totpagact>0)
						{
							uf_print_detalle_pagos_actuales($la_datapag,&$io_pdf);
						}
//////////////////////////////////       PAGOS ACTUALES       //////////////////////////////////
					}

				}
			}
			else
			{
				break;
			}
			$li_totalsaldo=$li_salsol;
			$li_totgendeb=$li_totgendeb+$li_totaldebe;
			$li_totgenhab=$li_totgenhab+$li_totalhaber;
			$li_totgensal=$li_totgensal+$li_totalsaldo;
			if(doubleval($li_totalsaldo)>0)
			{
				$li_totalsaldo= "(".number_format($li_totalsaldo,2,',','.').")";
			}
			else
			{
				$li_totalsaldo= abs($li_totalsaldo);
				$li_totalsaldo= number_format($li_totalsaldo,2,',','.');
			}
			$li_totalhaber= number_format($li_totalhaber,2,',','.');
			$li_totaldebe= number_format($li_totaldebe,2,',','.');
			uf_print_totales($li_totaldebe,$li_totalhaber,$li_totalsaldo,&$io_pdf);
			if(!$lb_valido)
			{break;}
		}// fin for uf_select_solicitudes
		uf_print_totales_generales($li_totgendeb,$li_totgenhab,$li_totgensal,&$io_pdf);
		
		if($lb_valido)
		{
			$io_pdf->ezStopPageNumbers(1,1);
			$io_pdf->ezStream();
			unset($io_pdf);
			unset($io_report);
		}
		else
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generarse el Reporte');"); 
			print(" close();");
			print("</script>");
		}
	}
?>