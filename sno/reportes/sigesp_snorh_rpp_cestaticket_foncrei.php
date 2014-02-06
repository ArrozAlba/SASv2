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

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//	    		   as_desnom // descripción de la nómina
		//	    		   as_periodo // período actual de la nómina
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_cestaticket.php",$ls_descripcion);
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
		// Fecha Creación: 27/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_desnom);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,720,11,$as_desnom); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,710,11,$as_periodo); // Agregar el título
		$io_pdf->addText(512,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(518,743,7,date("h:i a")); // Agregar la Hora
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
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$io_cabecera=$io_pdf->openObject();
		$io_pdf->saveState();
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(50,690,501.5,$io_pdf->getFontHeight(14));
        $io_pdf->setColor(0,0,0);
		$io_pdf->addText(55,695,11,'<b>Concepto</b>  '.$as_codconc.' - '.$as_nomcon.''); // Agregar el título
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
		// Fecha Creación: 08/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulobs="Bs.F.";
		}
		else
		{
			$ls_titulobs="Bs.";
		}
		$io_pdf->ezSety(688);
		$la_columnas=array('nro'=>'<b>Nro</b>',
						   'cedula'=>'<b>Cédula</b>',
						   'nombre'=>'<b>            Apellidos y Nombres</b>',
						   'estado1'=>'<b>Estado 1</b>',
						   'estado2'=>'<b>Estado 2</b>',
						   'ticket'=>'<b>Tickets</b>',
						   'valor'=>'<b>Monto '.$ls_titulobs.'    </b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('nro'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>160), // Justificación y ancho de la columna
						 			   'estado1'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'estado2'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'ticket'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'valor'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($ai_total,$ai_ticket,$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		   Access: private 
		//	    Arguments: ai_total // Total 
		//	   			   ai_ticket // Ticket
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>Total Cesta Ticket</b>','ticket'=>$ai_ticket,'total'=>$ai_total));
		$la_columna=array('name'=>'','ticket'=>'','total'=>'');
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
						 'cols'=>array('name'=>array('justification'=>'right','width'=>350), // Justificación y ancho de la columna
						 			   'ticket'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
        $io_pdf->setColor(0,0,0);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_firmas(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_firmas
		//		   Access: private 
		//    Description: función que imprime las firmas
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[0]=array('firma1'=>'','firma2'=>'','firma3'=>'');
		$la_data[1]=array('firma1'=>'','firma2'=>'','firma3'=>'');
		$la_data[2]=array('firma1'=>'ANALISTA RESPONSABLE','firma2'=>'CONTROL PREVIO','firma3'=>'APROBADO');
		$la_data[3]=array('firma1'=>'','firma2'=>'','firma3'=>'');
		$la_data[4]=array('firma1'=>'','firma2'=>'','firma3'=>'');
		$la_data[5]=array('firma1'=>'____________________________','firma2'=>'____________________________','firma3'=>'____________________________');
		$la_data[6]=array('firma1'=>''.$_SESSION['la_apeusu'].','.$_SESSION['la_nomusu'].'','firma2'=>'Gilman Enrique Rivas','firma3'=>'Rafael Rivas Cabrera');
		$la_data[7]=array('firma1'=>'Oficina Recursos Humanos','firma2'=>'Apoyo Técnico','firma3'=>'Gerente de Recursos Humanos');
		$la_data[8]=array('firma1'=>'C.I  Nº. '.$_SESSION['la_cedusu'].'','firma2'=>'C.I. Nº. 10.400.238','firma3'=>'C.I. Nº. 3.254.864');
		$la_columna=array('firma1'=>'','firma2'=>'','firma3'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>600, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('firma1'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna
						 			   'firma2'=>array('justification'=>'center','width'=>200),
						 			   'firma3'=>array('justification'=>'center','width'=>200))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_firmas
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>Cesta Ticket</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	$ls_codconcdes=$io_fun_nomina->uf_obtenervalor_get("codconcdes","");
	$ls_codconchas=$io_fun_nomina->uf_obtenervalor_get("codconchas","");
	$ls_ano=$io_fun_nomina->uf_obtenervalor_get("ano","");
	$ls_mes=$io_fun_nomina->uf_obtenervalor_get("mes","");
	$ls_codperi=$io_fun_nomina->uf_obtenervalor_get("codperi","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_tiporeporte=$io_fun_nomina->uf_obtenervalor_get("tiporeporte",0);
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("codsubnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("codsubnomhas","");
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_snorh_class_reportbsf.php");
		$io_report=new sigesp_snorh_class_reportbsf();
	}
	$ls_rango= "Nómina Desde: ".$ls_codnomdes." Nómina Hasta: ".$ls_codnomhas;
	$ls_periodo= "Año: ".$ls_ano." Mes: ".$io_fecha->uf_load_nombre_mes($ls_mes);
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_rango,$ls_periodo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_cestaticket_personal($ls_codnomdes,$ls_codnomhas,$ls_ano,$ls_mes,$ls_codperi,$ls_codconcdes,
														$ls_codconchas,$ls_conceptocero,$ls_subnomdes,$ls_subnomhas,$ls_orden); // Cargar el DS con los datos del reporte
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
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.60,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_rango,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->DS->getRowCount("cedper");
		$ls_codconcact="";
		$ls_nomcon="";
		$ls_codconc="";
		$li_contador=0;
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
			$ls_codconc=$io_report->DS->data["codconc"][$li_i];
			$ls_nomcon=$io_report->DS->data["nomcon"][$li_i];
			if($ls_codconc!=$ls_codconcact)
			{
				if($li_i>1)
				{
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					unset($la_data);
					$li_total=$io_fun_nomina->uf_formatonumerico($li_total);
					$li_totalticket=$io_fun_nomina->uf_formatonumerico($li_totalticket);
					uf_print_piecabecera($li_total,$li_totalticket,$io_pdf); // Imprimimos el fin del reporte
					$io_pdf->stopObject($io_cabecera); // Detener el objeto cabecera
					$io_pdf->ezNewPage(); // Insertar una nueva página
				}
				$io_cabecera=$io_pdf->openObject(); // Creamos el objeto cabecera
				$ls_codconcact=$ls_codconc;
				uf_print_cabecera($ls_codconc,$ls_nomcon,$io_cabecera,$io_pdf); // Imprimimos la cabecera del registro
				$li_contador=0;
				$li_total=0;
				$li_totalticket=0;
			}
			$li_contador=$li_contador+1;
			$ls_cedper=$io_report->DS->data["cedper"][$li_i];
			$ls_nomper=$io_report->DS->data["apeper"][$li_i].", ".$io_report->DS->data["nomper"][$li_i];
			$ls_estado1=$io_report->DS->data["est1cestic"][$li_i];
			$ls_estado2=$io_report->DS->data["est2cestic"][$li_i];
			$li_ticket=abs($io_report->DS->data["valsal"][$li_i])/abs($io_report->DS->data["moncestic"][$li_i]);
			$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS->data["valsal"][$li_i]));
			$li_total=$li_total+abs($io_report->DS->data["valsal"][$li_i]);
			$li_totalticket=$li_totalticket+$li_ticket;
			$li_ticket=$io_fun_nomina->uf_formatonumerico($li_ticket);
			$la_data[$li_contador]=array('nro'=>$li_contador,'cedula'=>$ls_cedper,'nombre'=>$ls_nomper,'estado1'=>$ls_estado1,'estado2'=>$ls_estado2,'ticket'=>$li_ticket,'valor'=>$li_valsal);
		}
		$io_report->DS->resetds("cedper");
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		$li_total=$io_fun_nomina->uf_formatonumerico($li_total);
		$li_totalticket=$io_fun_nomina->uf_formatonumerico($li_totalticket);
		uf_print_piecabecera($li_total,$li_totalticket,$io_pdf); // Imprimimos el fin del reporte
		uf_print_firmas(&$io_pdf);
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
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 
