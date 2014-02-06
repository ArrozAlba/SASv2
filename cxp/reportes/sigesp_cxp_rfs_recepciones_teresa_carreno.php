<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Formato de salida  de Recepciones de Documentos
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
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_p_recepcion.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_numrecdoc,$ad_fecregrec,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_numsol // numero de la solicitud
		//	    		   ad_fecregsol // fecha de registro de la solicitud
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 11/03/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		
	    $io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,715,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->addText(210,730,11,$as_titulo);
		$io_pdf->addText(430,735,11,"No.: ".$as_numrecdoc);
		$io_pdf->addText(420,715,10,"Fecha: ".$ad_fecregrec);
		$io_pdf->addText(515,760,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(520,752,7,date("h:i a")); // Agregar la Hora
		
		// cuadro inferior
        // cuadro inferior
        $io_pdf->Rectangle(50,40,500,70);
		$io_pdf->line(50,53,550,53);		
		$io_pdf->line(50,97,550,97);		
//		$io_pdf->line(130,40,130,110);		
		$io_pdf->line(300,40,300,110);		
//		$io_pdf->line(380,40,380,110);		
		$io_pdf->addText(140,102,7,"ELABORADO POR"); // Agregar el título
		$io_pdf->addText(150,43,7,"ANALISTA"); // Agregar el título
//		$io_pdf->addText(157,102,7,"VERIFICADO POR"); // Agregar el título
//		$io_pdf->addText(160,43,7,"PRESUPUESTO"); // Agregar el título
		$io_pdf->addText(390,102,7,"AUTORIZADO POR"); // Agregar el título
		$io_pdf->addText(380,43,7,"JEFE DE CONTABILIDAD"); // Agregar el título
//		$io_pdf->addText(440,102,7,"PROVEEDOR"); // Agregar el título
//		$io_pdf->addText(405,43,7,"FIRMA AUTOGRAFA, SELLO, FECHA"); // Agregar el título
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codtipdoc,$as_codproben,$as_nomproben,$as_tipproben,$as_numref,$as_fecemirec,$as_fecvenrec,$as_concepto,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_numrecdoc // Numero de la Recepcion de Documentos
		//	   			   as_dentipdoc // Denominacion de tipo de documento
		//	   			   as_nombre    // Nombre del Proveedor / Beneficiario
		//	   			   as_proben    // Indica si es  Proveedor / Beneficiario
		//	   			   ad_fecemidoc // Fecha de Emision de la Factura
		//	   			   ad_fecrecdoc // Fecha de recepcion del documento
		//	   			   as_dencondoc // Concepto de la Recepcion de Documentos
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 22/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		if ($as_tipproben=="P")
		   {
		     $ls_titproben = 'Proveedor';
		   }
		else
		   {
		     $ls_titproben = 'Beneficiario';
		   }
		
		$io_cabecera=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetY(700);
		
		$la_data=array(array('name'=>'<b>Tipo de Documento</b>    '.$as_codtipdoc.''),
					   array ('name'=>'<b>'.$ls_titproben.'</b>                    '.$as_codproben." - ".$as_nomproben.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>350, // Ancho de la tabla
						 'maxWidth'=>350); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		
		$la_data=array(array('name'=>'<b>Número de Referencia:</b>     '.$as_numref.''),
					   array ('name'=>'<b>Fecha de Emisión:</b>            '.$as_fecemirec.''),
					   array ('name'=>'<b>Fecha de Vencimiento:</b>    '.$as_fecvenrec.''),
					   array ('name'=>'<b>Concepto:</b>                          '.$as_concepto.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>8, // Tamaño de Letras
						 'titleFontSize'=>9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'<b>Datos del Documento</b>',$la_config);	
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_spg($aa_data,$ai_totpre,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: aa_data // arreglo de información
		//	    		   ai_totpre // monto total de presupuesto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle presupuestario
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);

		$la_datatit[1]=array('titulo'=>'<b>Detalle Presupuestario</b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>8, // Tamaño de Letras
						 'titleFontSize'=>8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('numrecdoc'=>'<b>Nro. Compromiso</b>',
						   'codestpro'=>'<b>Código Programático</b>',
						   'spg_cuenta'=>'<b>Código Estadístico</b>',
						   'monto'=>'<b>Monto</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize'=>8, // Tamaño de Letras
						 'titleFontSize'=>8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numrecdoc'=>array('justification'=>'center','width'=>160), // Justificación y ancho de la columna
									   'codestpro'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna
						 			   'spg_cuenta'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_scg($aa_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: aa_data // arreglo de información
		//	    		   si_totdeb // total monto debe
		//	    		   si_tothab // total monto haber
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle contable
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		
		$la_datatit[1]=array('titulo'=>'<b>Detalles de Contables</b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>8, // Tamaño de Letras
						 'titleFontSize'=>8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('numrecdoc'=>'<b>Nro. Compromiso</b>',
						   'sc_cuenta'=>'<b>Código Contable</b>',
						   'debhab'=>'<b>Debe / Haber</b>',
						   'monto'=>'<b>Monto</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize'=>8, // Tamaño de Letras
						 'titleFontSize'=>8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numrecdoc'=>array('justification'=>'center','width'=>160), // Justificación y ancho de la columna
						 			   'sc_cuenta'=>array('justification'=>'center','width'=>190), // Justificación y ancho de la columna
						 			   'debhab'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>110))); // Justificación y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($ai_montotdoc,$ai_mondeddoc,$ai_moncardoc,$ai_monsubdoc,$ai_montotcar,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: ai_montotdoc // Monto Total del Documento
		//	   			   ai_mondeddoc // Monto Deduccion del Documento
		//	   			   ai_moncardoc // Monto Cargos del Documento
		//	   			   ai_monsubdoc // Monto Sub-Total (Sin Cargos ni Deducciones)
		//	   			   ai_montotcar // Monto Sub-Total Incluyendo Cargos
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los montos totales del documento
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 22/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_pdf->ezSetDy(-2);
		$la_datalin = array(array('name'=>'___________________________________________________________________________________________________________'));
		$la_columna = array('name'=>'');
		$la_config  = array('showHeadings'=>0, // Mostrar encabezados
						    'fontSize' => 9, // Tamaño de Letras
						    'showLines'=>0, // Mostrar Líneas
						    'shaded'=>0, // Sombra entre líneas
						    'xPos'=>330, // Orientación de la tabla
						    'width'=>570); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_datalin,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$la_data[1]=array('titulo'=>'<b>Sub-Total</b>','contenido'=>$ai_monsubdoc);
		$la_data[2]=array('titulo'=>'<b>Otros Creditos</b>','contenido'=>$ai_moncardoc);
		$la_data[3]=array('titulo'=>'<b>Total</b>','contenido'=>$ai_montotcar);
		$la_data[4]=array('titulo'=>'<b>Deducciones</b>','contenido'=>$ai_mondeddoc);
		$la_data[5]=array('titulo'=>'<b>Total General</b>','contenido'=>$ai_montotdoc);
		$la_columnas=array('titulo'=>'','contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>460), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>110))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_totales
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("sigesp_cxp_class_report.php");
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../class_folder/class_funciones_cxp.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/class_numero_a_letra.php");
		
	$io_report    = new sigesp_cxp_class_report();
	$io_funciones = new class_funciones();				
	$io_fun_cxp	  = new class_funciones_cxp();
	$numalet	  = new class_numero_a_letra();
	$ls_estmodest = $_SESSION["la_empresa"]["estmodest"];

	//cambia a minusculas
	$numalet->setMayusculas(1);
	//cambia a femenino
	$numalet->setGenero(1);
	//cambia moneda
	$numalet->setMoneda("Bolivares");
	//cambia prefijo
	$numalet->setPrefijo("***");
	//cambia sufijo
	$numalet->setSufijo("***");
		
	if($ls_estmodest==1)
	{
		$ls_titcuentas="Estructura Presupuestaria";
	}
	else
	{
		$ls_titcuentas="Estructura Programatica";
	}
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>RECEPCION DE DOCUMENTOS</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_numrecdoc	= $io_fun_cxp->uf_obtenervalor_get("numrecdoc","");
	$ls_codpro		= $io_fun_cxp->uf_obtenervalor_get("codpro","");
	$ls_cedben		= $io_fun_cxp->uf_obtenervalor_get("cedben","");
	$ls_codtipdoc	= $io_fun_cxp->uf_obtenervalor_get("codtipdoc","");
	$ls_tiporeporte = $io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	require_once("../../shared/ezpdf/class.ezpdf.php");
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_cxp_class_reportbsf.php");
		$io_report=new sigesp_cxp_class_reportbsf();
	}
	else
	{
		require_once("sigesp_cxp_class_report.php");
		$io_report=new sigesp_cxp_class_report();
	}	
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_select_recepcion($ls_numrecdoc,$ls_codpro,$ls_cedben,$ls_codtipdoc); // Cargar el DS con los datos del reporte
		if($lb_valido==false) // Existe algún error ó no hay registros
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}
		else  // Imprimimos el reporte
		{
			error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		    $io_pdf->ezSetCmMargins(8,3,3,3);// Configuración de los margenes en centímetros
			$li_totrow=$io_report->DS->getRowCount("numrecdoc");
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_numrecdoc = $io_report->DS->data["numrecdoc"][$li_i];
				$ls_codtipdoc = $io_report->DS->data["codtipdoc"][$li_i];
				$ls_nomproben = $io_report->DS->data["nombre"][$li_i];
				$ld_fecemidoc = $io_report->DS->data["fecemidoc"][$li_i];
				$ld_fecrecdoc = $io_report->DS->data["fecregdoc"][$li_i];
				$ls_dencondoc = $io_report->DS->data["dencondoc"][$li_i];
				$li_montotdoc = $io_report->DS->data["montotdoc"][$li_i];
				$li_mondeddoc = $io_report->DS->data["mondeddoc"][$li_i];
				$li_moncardoc = $io_report->DS->data["moncardoc"][$li_i];
				$ls_numrefrec = $io_report->DS->data["numref"][$li_i];
				$li_monsubdoc = ($li_montotdoc-$li_moncardoc+$li_mondeddoc);
				$li_montotcar = ($li_montotdoc+$li_mondeddoc);
				$ld_fecemidoc = $io_funciones->uf_convertirfecmostrar($ld_fecemidoc);
				$ld_fecrecdoc = $io_funciones->uf_convertirfecmostrar($ld_fecrecdoc);
				$ls_fenvenrec = $io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecvendoc"][$li_i]);
				uf_print_encabezado_pagina($ls_titulo,$ls_numrecdoc,$ld_fecrecdoc,&$io_pdf);
				if($ls_codpro!="----------")
				{
					$ls_codigo=$ls_codpro;
					uf_print_cabecera($ls_codtipdoc,$ls_codigo,$ls_nomproben,"P",$ls_numrefrec,$ld_fecemidoc,$ls_fenvenrec,$ls_dencondoc,&$io_pdf);
				}
				else
				{
					$ls_codigo=$ls_cedben;
					uf_print_cabecera($ls_codtipdoc,$ls_codigo,$ls_nomproben,"B",$ls_numrefrec,$ld_fecemidoc,$ls_fenvenrec,$ls_dencondoc,&$io_pdf);
				}						
				//////////////////////////   GRID DETALLE PRESUPUESTARIO		//////////////////////////////////////
				$lb_valido=$io_report->uf_select_detalle_recepcionspg($ls_numrecdoc,$ls_codpro,$ls_cedben,$ls_codtipdoc); // Cargar el DS con los datos del reporte
				if($lb_valido)
				{
					$li_totrowspg=$io_report->ds_detalle_spg->getRowCount("codestpro");
					$la_data="";
					$li_totpre=0;
					for($li_s=1;$li_s<=$li_totrowspg;$li_s++)
					{
						$ls_codestpro = $io_report->ds_detalle_spg->data["codestpro"][$li_s];
						$ls_spgcuenta = $io_report->ds_detalle_spg->data["spg_cuenta"][$li_s];
						$ls_numrecdoc = $io_report->ds_detalle_spg->data["numrecdoc"][$li_s];
						$ls_numdoccom = $io_report->ds_detalle_spg->data["numdoccom"][$li_s];
						$li_monto     = $io_report->ds_detalle_spg->data["monto"][$li_s];
						$li_totpre	  = $li_totpre+$li_monto;
						$li_monto	  = number_format($li_monto,2,",",".");
						$io_fun_cxp->uf_formatoprogramatica($ls_codestpro,&$as_programatica);
						$la_data[$li_s]=array('numrecdoc'=>$ls_numdoccom,'codestpro'=>$as_programatica,
											  'spg_cuenta'=>$ls_spgcuenta,'monto'=>$li_monto);
					}	
					$li_totpre=number_format($li_totpre,2,",",".");
					uf_print_detalle_spg($la_data,$li_totpre,&$io_pdf);
					unset($la_data);
				}
				//////////////////////////   GRID DETALLE PRESUPUESTARIO		//////////////////////////////////////
				//////////////////////////      GRID DETALLE CONTABLE	   	//////////////////////////////////////
				$lb_valido=$io_report->uf_select_detalle_recepcionscg($ls_numrecdoc,$ls_codpro,$ls_cedben,$ls_codtipdoc); // Cargar el DS con los datos del reporte
				if ($lb_valido)
				   {
					 $li_totrowscg=$io_report->ds_detalle_scg->getRowCount("sc_cuenta");
					 $la_data="";
					 $ld_totdeb=0;
					 $ld_tothab=0;
					 for ($li_s=1;$li_s<=$li_totrowscg;$li_s++)
					     {
						   $ls_sccuenta	 = trim($io_report->ds_detalle_scg->data["sc_cuenta"][$li_s]);
						   $ls_debhab	 = trim($io_report->ds_detalle_scg->data["debhab"][$li_s]);
						   $ls_numrecdoc = trim($io_report->ds_detalle_scg->data["numrecdoc"][$li_s]);
					  	   $ld_monto	 = $io_report->ds_detalle_scg->data["monto"][$li_s];
						   $ls_numdoccom = $io_report->ds_detalle_scg->data["numdoccom"][$li_s];
						   if ($ls_debhab=="D")
						      {
							    $ld_montodebe = $ld_monto;
							    $ld_montohab  = 0;
							    $ld_totdeb	  = $ld_totdeb+$ld_montodebe;
							    $ld_monto     = number_format($ld_montodebe,2,",",".");
						      }
						   else
							  {
							    $ld_montodebe = 0;
								$ld_montohab  = $ld_monto;
								$ld_tothab    = $ld_tothab+$ld_montohab;
								$ld_monto     = number_format($ld_montohab,2,",",".");
							  }
						   $la_data[$li_s]=array('numrecdoc'=>$ls_numdoccom,
						                         'sc_cuenta'=>$ls_sccuenta,
												 'debhab'=>$ls_debhab,
												 'monto'=>$ld_monto);
					}	
					uf_print_detalle_scg($la_data,&$io_pdf);
					unset($la_data);
				}
				$ld_montotdoc = number_format($li_montotdoc,2,",",".");
				$ld_mondeddoc = number_format($li_mondeddoc,2,",",".");
				$ld_moncardoc = number_format($li_moncardoc,2,",",".");
				$ld_monsubdoc = number_format($li_monsubdoc,2,",",".");
				$ld_montotcar = number_format($li_montotcar,2,",",".");
				uf_print_totales($ld_montotdoc,$ld_mondeddoc,$ld_moncardoc,$ld_monsubdoc,$ld_montotcar,&$io_pdf);
			}
		}
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
	}
?>