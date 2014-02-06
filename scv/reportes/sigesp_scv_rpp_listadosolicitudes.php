<?PHP
	//-----------------------------------------------------------------------------------------------------------------------------------
	//Reporte Modificado para aceptar Bs. y Bs.F.
	//Modificado por: Ing. Luis Anibal Lang  08/08/2007	
	//-----------------------------------------------------------------------------------------------------------------------------------
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
	function uf_insert_seguridad($as_titulo,$ad_fecregdes,$ad_fecreghas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo    // Título del Reporte
		//	    		   ad_fecregdes // Inicio del Intervalo de Fecha del Reporte
		//	    		   ad_fecreghas // Fin del Intervalo de Fecha del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 08/06/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_viaticos;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Desde ".$ad_fecregdes.". Hasta ".$ad_fecreghas;
		$lb_valido=$io_fun_viaticos->uf_load_seguridad_reporte("SCV","sigesp_scv_r_listadosolicitudes.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$ad_fecregdes,$ad_fecreghas,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   ad_fecregdes // Inicio del Intervalo de Fecha del Reporte
		//	    		   ad_fecreghas // Fin del Intervalo de Fecha del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 08/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
//		$io_pdf->rectangle(10,710,580,60);
//		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],15,540,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,560,11,$as_titulo); // Agregar el título
		$ls_periodo="Periodo ".$ad_fecregdes." - ".$ad_fecreghas;
		$li_tm=$io_pdf->getTextWidth(11,$ls_periodo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,550,11,$ls_periodo); // Agregar el título
		$io_pdf->addText(730,580,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(736,573,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($aa_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: aa_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 08/07/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo="Monto Bs.F.";
		}
		else
		{
			$ls_titulo="Monto Bs.";
		}
		$io_pdf->ezSetDy(-2);
		$la_data[1]=array('numsolvia'=>"<b>Solicitud</b>",'cedula'=>"<b>Cédula</b>",'nombre'=>"<b>Nombre Personal/Beneficiario</b>",
							  'fecsalvia'=>"<b>Fecha Salida</b>",'fecregvia'=>"<b>Fecha Retorno</b>",'desrut'=>"<b>Ruta</b>",'monto'=>"<b>".$ls_titulo."</b>");
		$la_columnas=array('numsolvia'=>'<b>Solicitud</b>',
						   'cedula'=>'<b>Cédula</b>',
						   'nombre'=>'<b>Beneficiario</b>',
						   'fecsalvia'=>'<b>Fecha Salida</b>',
						   'fecregvia'=>'<b>Fecha Retorno</b>',
						   'desrut'=>'<b>Ruta</b>',
						   'monto'=>'<b>Monto</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numsolvia'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>170), // Justificación y ancho de la columna
						 			   'fecsalvia'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
						 			   'fecregvia'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
						 			   'desrut'=>array('justification'=>'center','width'=>225), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'center','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$la_columnas=array('numsolvia'=>'<b>Solicitud</b>',
						   'cedula'=>'<b>Cédula</b>',
						   'nombre'=>'<b>Beneficiario</b>',
						   'fecsalvia'=>'<b>Fecha Salida</b>',
						   'fecregvia'=>'<b>Fecha Retorno</b>',
						   'desrut'=>'<b>Ruta</b>',
						   'monto'=>'<b>Monto</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numsolvia'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'left','width'=>65), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>170), // Justificación y ancho de la columna
						 			   'fecsalvia'=>array('justification'=>'left','width'=>65), // Justificación y ancho de la columna
						 			   'fecregvia'=>array('justification'=>'left','width'=>65), // Justificación y ancho de la columna
						 			   'desrut'=>array('justification'=>'left','width'=>225), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($ai_total,$ai_montot,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		   Access: private 
		//	    Arguments: ai_total // Total de Trabajadores
		//	   			   ai_montot // Monto total por concepto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por conceptos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('total'=>'<b>Total Registros</b>'.' '.$ai_total.'','monto'=>$ai_montot));
		$la_columna=array('total'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>650), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_viaticos.php");
	$io_fun_viaticos=new class_funciones_viaticos();
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------

	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ld_fecregdes=$io_fun_viaticos->uf_obtenervalor_get("desde","");
	$ld_fecreghas=$io_fun_viaticos->uf_obtenervalor_get("hasta","");
	$ls_coduniadm=$io_fun_viaticos->uf_obtenervalor_get("coduniadm","");
	$ls_tiporeporte=$io_fun_viaticos->uf_obtenervalor_get("tiporeporte",0);
	$ls_orden=$io_fun_viaticos->uf_obtenervalor_get("orden","scv_solicitudes.codsolvia");
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>Listado de Solicitudes de Viaticos</b>";
	global $ls_tiporeporte;
	require_once("../../shared/ezpdf/class.ezpdf.php");
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_scv_class_reportbsf.php");
		$io_report=new sigesp_scv_class_reportbsf();
	}
	else
	{
		require_once("sigesp_scv_class_report.php");
		$io_report=new sigesp_scv_class_report();
	}	
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ld_fecregdes,$ld_fecreghas); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_select_listadosolicitudes($ld_fecregdes,$ld_fecreghas,$ls_coduniadm,$ls_orden); // Cargar el DS con los datos de la cabecera del reporte
	}
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		//print(" close();");
		print("</script>");
	}
	else  // Imprimimos el reporte
	{
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.6,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ld_fecregdes,$ld_fecreghas,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(750,50,10,'','',1); // Insertar el número de página
//		$li_totrow=$io_report->ds_solicitud->getRowCount("numsolvia");
		$li_totrow=$io_report->ds_solicitud->getRowCount("codsolvia");
		$li_montot=0;
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_numsolvia=$io_report->ds_solicitud->data["codsolvia"][$li_i];
			$ls_nombre=$io_report->ds_solicitud->data["nombre"][$li_i];
			$ls_cedula=$io_report->ds_solicitud->data["cedula"][$li_i];
			$ls_desrut=$io_report->ds_solicitud->data["desrut"][$li_i];
			$li_monto=$io_report->ds_solicitud->data["monto"][$li_i];
			$ld_fecsalvia=$io_report->ds_solicitud->data["fecsalvia"][$li_i];
			$ld_fecregvia=$io_report->ds_solicitud->data["fecregvia"][$li_i];
			$li_montot=$li_montot+$li_monto;
			$li_monto=number_format($li_monto,2,',','.');
			$ld_fecsalvia=$io_funciones->uf_convertirfecmostrar($ld_fecsalvia);
			$ld_fecregvia=$io_funciones->uf_convertirfecmostrar($ld_fecregvia);
			$la_data[$li_i]=array('numsolvia'=>$ls_numsolvia,'cedula'=>$ls_cedula,'nombre'=>$ls_nombre,'fecsalvia'=>$ld_fecsalvia,
								  'fecregvia'=>$ld_fecregvia,'desrut'=>$ls_desrut,'monto'=>$li_monto);
		}
		$li_montot=number_format($li_montot,2,',','.');
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		uf_print_piecabecera($li_totrow,$li_montot,$io_pdf); // Imprimimos el pie de la cabecera
		unset($io_cabecera);
		unset($la_data);
		$io_report->ds_solicitud->resetds("numsolvia");
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
	unset($io_fun_viaticos);
?> 