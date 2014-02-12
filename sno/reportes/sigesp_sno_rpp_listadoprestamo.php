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

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo,$ai_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/12/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_listadoprestamo.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_hlistadoprestamo.php",$ls_descripcion,$ls_codnom);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_desnom,$as_periodo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/12/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,755,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,540,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,530,11,$as_periodo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(10,$as_desnom);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,520,10,$as_desnom); // Agregar el título
		$io_pdf->addText(712,560,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(718,553,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codconc,$as_nomcon,&$io_cabecera,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codconc // Código de Concepto
		//	   			   as_nomcon // Nombre de Concepto
		//	    		   io_cabecera // objeto cabecera
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/12/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(50,500,700,$io_pdf->getFontHeight(14));
        $io_pdf->setColor(0,0,0);
		$io_pdf->addText(55,505,11,'<b>Concepto</b>  '.$as_codconc.' - '.$as_nomcon.''); // Agregar el título
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('codigo'=>'<b>Código</b>',
						   'nombre'=>'<b>       Apellidos y Nombre</b>',
						   'tipo'=>'<b>     Tipo de Prestamo</b>',
						   'estatus'=>'<b>Estatus</b>',
						   'fecha'=>'<b>Fecha</b>',
						   'monto'=>'<b>Monto '.$ls_bolivares.'  </b>',
						   'amortizado'=>'<b>Amortizado     '.$ls_bolivares.'        </b>',
						   'cuotas'=>'<b>Cuotas Faltantes</b>',
						   'saldo'=>'<b>Saldo '.$ls_bolivares.'  </b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'xPos'=>405,
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>135), // Justificación y ancho de la columna
						 			   'tipo'=>array('justification'=>'left','width'=>125), // Justificación y ancho de la columna
						 			   'estatus'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'amortizado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'cuotas'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'saldo'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($ai_tottra,$ai_totprestamo,$ai_totamortizado,$ai_totcuota,$ai_totsaldo,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		   Access: private 
		//	    Arguments: ai_tottra // Total de Trabajadores
		//	   			   ai_totprestamo // Monto total de prestamo
		//	   			   ai_totamortizado // Monto total amortizado
		//	   			   ai_totcuota // Total de cuotas
		//	   			   ai_totsaldo // Monto total Saldo
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por conceptos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/12/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('total'=>'<b>Total Trabajadores</b>'.' '.$ai_tottra.'','monto'=>$ai_totprestamo,
							 'amortizado'=>$ai_totamortizado,'cuotas'=>$ai_totcuota,'saldo'=>$ai_totsaldo));
		$la_columna=array('total'=>'','monto'=>'','amortizado'=>'','cuotas'=>'','saldo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'xPos'=>405,
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>440), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'amortizado'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'cuotas'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'saldo'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

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
			$ls_bolivares ="Bs.";
			break;

		case "1":
			if($_SESSION["la_nomina"]["tiponomina"]=="NORMAL")
			{
				require_once("sigesp_sno_class_reportbsf.php");
				$io_report=new sigesp_sno_class_reportbsf();
				$li_tipo=1;
			}
			if($_SESSION["la_nomina"]["tiponomina"]=="HISTORICA")
			{
				require_once("sigesp_sno_class_report_historicobsf.php");
				$io_report=new sigesp_sno_class_report_historicobsf();
				$li_tipo=2;
			}	
			$ls_bolivares ="Bs.F.";
			break;
	}
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$ls_titulo="<b>Listado de Prestamos</b>";
	$ls_periodo="<b>Período Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codconcdes=$io_fun_nomina->uf_obtenervalor_get("codconcdes","");
	$ls_codconchas=$io_fun_nomina->uf_obtenervalor_get("codconchas","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_codtippredes=$io_fun_nomina->uf_obtenervalor_get("codtippredes","");
	$ls_codtipprehas=$io_fun_nomina->uf_obtenervalor_get("codtipprehas","");
	$ls_estatus=$io_fun_nomina->uf_obtenervalor_get("estatus","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_listadoprestamo_conceptos($ls_codconcdes,$ls_codconchas,$ls_codperdes,$ls_codperhas,
															$ls_codtippredes,$ls_codtipprehas,$ls_subnomdes,$ls_subnomhas,
															$ls_estatus); // Cargar el DS con los datos de la cabecera del reporte
	}
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
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(4,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_desnom,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(750,50,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->DS->getRowCount("codconc");
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_codconc=$io_report->DS->data["codconc"][$li_i];
			$ls_nomcon=$io_report->DS->data["nomcon"][$li_i];
			$li_totprestamo=0;
			$li_totamortizado=0;
			$li_totcuota=0;
			$li_totsaldo=0;
			$li_tottra=0;
			$io_cabecera=$io_pdf->openObject(); // Creamos el objeto cabecera
			uf_print_cabecera($ls_codconc,$ls_nomcon,$io_cabecera,$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_listadoprestamo_personalconcepto($ls_codconc,$ls_codperdes,$ls_codperhas,$ls_codtippredes,
																	   $ls_codtipprehas,$ls_estatus,$ls_subnomdes,$ls_subnomhas,$ls_orden); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->DS_detalle->getRowCount("codper");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_codper=$io_report->DS_detalle->data["codper"][$li_s];
					$ls_apenomper=$io_report->DS_detalle->data["apeper"][$li_s].", ". $io_report->DS_detalle->data["nomper"][$li_s];
					$ls_destippre=$io_report->DS_detalle->data["destippre"][$li_s];
					$li_numcuopre=$io_report->DS_detalle->data["numcuopre"][$li_s];
					$ls_stapre=$io_report->DS_detalle->data["stapre"][$li_s];
					switch($ls_stapre)
					{
						case "1": 
							$ls_stapre="Activo";
							break;
						case "2": 
							$ls_stapre="Suspendido";
							break;
						case "3": 
							$ls_stapre="Cancelado";
							break;
					}
					$li_saldo=($io_report->DS_detalle->data["monpre"][$li_s]-$io_report->DS_detalle->data["monamopre"][$li_s]);
					$li_saldo=$io_fun_nomina->uf_formatonumerico($li_saldo);
					$ld_fecpre=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["fecpre"][$li_s]);
					$li_monpre=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["monpre"][$li_s]));
					$li_monamopre=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["monamopre"][$li_s]));
					$la_data[$li_s]=array('codigo'=>$ls_codper,'nombre'=>$ls_apenomper,'tipo'=>$ls_destippre,'estatus'=>$ls_stapre,
										  'fecha'=>$ld_fecpre,'monto'=>$li_monpre,'amortizado'=>$li_monamopre,'cuotas'=>$li_numcuopre,
										  'saldo'=>$li_saldo);
					$li_totprestamo=$li_totprestamo+$io_report->DS_detalle->data["monpre"][$li_s];
					$li_totamortizado=$li_totamortizado+$io_report->DS_detalle->data["monamopre"][$li_s];
					$li_totcuota=$li_totcuota+$io_report->DS_detalle->data["numcuopre"][$li_s];
					$li_totsaldo=$li_totsaldo+($io_report->DS_detalle->data["monpre"][$li_s]-$io_report->DS_detalle->data["monamopre"][$li_s]);
					$li_tottra=$li_tottra+1;
				}
				$io_report->DS_detalle->resetds("codper");
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				$li_totprestamo=$io_fun_nomina->uf_formatonumerico(abs($li_totprestamo));
				$li_totamortizado=$io_fun_nomina->uf_formatonumerico(abs($li_totamortizado));
				$li_totcuota=abs($li_totcuota);
				$li_totsaldo=$io_fun_nomina->uf_formatonumerico(abs($li_totsaldo));
				uf_print_piecabecera($li_tottra,$li_totprestamo,$li_totamortizado,$li_totcuota,$li_totsaldo,$io_pdf); // Imprimimos el pie de la cabecera
				$io_pdf->stopObject($io_cabecera); // Detener el objeto cabecera
				if($li_i<$li_totrow)
				{
					$io_pdf->ezNewPage(); // Insertar una nueva página
				}
				unset($io_cabecera);
				unset($la_data);
			}
		}
		$io_report->DS->resetds("codconc");
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