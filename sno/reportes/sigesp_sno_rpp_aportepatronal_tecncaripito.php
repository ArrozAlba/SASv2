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
		//	    Arguments: as_titulo // Título del reporte
		//	    		   as_desnom // descripción de la nómina
		//	    		   as_periodo // período actual de la nómina
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_aportepatronal.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_haportepatronal.php",$ls_descripcion,$ls_codnom);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_desnom,$as_quincena,$as_periodo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_quincena // Descripción de la quincena
		//	    		   as_periodo // Descripción del período
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/05/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,"República Bolivariana de Venezuela");
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,760,12,"República Bolivariana de Venezuela"); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(12,"Ministerio de Educación Superior");
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,748,12,"Ministerio de Educación Superior"); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(12,"Instituto Universitario de Tecnología Caripito");
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,736,12,"Instituto Universitario de Tecnología Caripito"); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(12,"Oficina de Recursos Humanos");
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,724,12,"Oficina de Recursos Humanos"); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(12,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,712,12,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(12,$as_desnom);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,692,12,$as_desnom); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_quincena);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,672,11,$as_quincena); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,662,11,$as_periodo); // Agregar el título
		$io_pdf->addText(500,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(506,743,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codconc,$as_nomcon,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codconc // Código de Concepto
		//	   			   as_nomcon // Nombre de Concepto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/05/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_cabecera=$io_pdf->openObject();
		$io_pdf->saveState();
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(50,640,500,$io_pdf->getFontHeight(14));
        $io_pdf->setColor(0,0,0);
		$io_pdf->addText(55,645,11,'<b>Concepto</b>  '.$as_codconc.' - '.$as_nomcon.''); // Agregar el título
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
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('nro'=>'<b>Nro</b>',
						   'cedula'=>'<b>Cédula</b>',
						   'nombre'=>'<b>              Apellidos y Nombres</b>',
						   'personal'=>'<b>Empleado     </b>',
						   'patron'=>'<b>Patrón          </b>',
						   'total'=>'<b>Total         </b>');
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
						 			   'cedula'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>160), // Justificación y ancho de la columna
						 			   'personal'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'patron'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($ai_personal,$ai_patron,$ai_total,$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		   Access: private 
		//	    Arguments: ai_personal // Total por personal
		//	   			   ai_patron // Total por patrón
		//	   			   ai_total // Total 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		$la_data=array(array('name'=>'<b>Total Aporte '.$ls_bolivares.'</b>','personal'=>$ai_personal,'aporte'=>$ai_patron,'total'=>$ai_total));
		$la_columna=array('name'=>'','personal'=>'','aporte'=>'','total'=>'');
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
						 'cols'=>array('name'=>array('justification'=>'right','width'=>260), // Justificación y ancho de la columna
						 			   'personal'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'aporte'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
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
		$la_data[1]=array('firma1'=>'','firma2'=>'','firma3'=>'');
		$la_data[2]=array('firma1'=>'','firma2'=>'','firma3'=>'');
		$la_data[3]=array('firma1'=>'','firma2'=>'','firma3'=>'');
		$la_data[4]=array('firma1'=>'','firma2'=>'','firma3'=>'');
		$la_data[5]=array('firma1'=>'Aprobado Por:','firma2'=>'','firma3'=>'');
		$la_columna=array('firma1'=>'','firma2'=>'','firma3'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>510, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('firma1'=>array('justification'=>'left','width'=>170), // Justificación y ancho de la columna
						 			   'firma2'=>array('justification'=>'center','width'=>170),
						 			   'firma3'=>array('justification'=>'center','width'=>170))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('firma1'=>'<b>Jefe Dpto. R.R.H.H.</b>',
						  'firma2'=>'<b>Jefe Dpto. Control Previo.</b>',
						  'firma3'=>'<b>Presupuesto</b>',
						  'firma4'=>'<b>Sub. Dir. Admin.</b>',
						  'firma5'=>'<b>Direccion</b>',
						  'firma6'=>'<b>Contraloria</b>');
		$la_data[2]=array('firma1'=>'<b>Abo. Francisca Tortello</b>',
						  'firma2'=>'<b>Lic. Miglenys Adrian</b>',
						  'firma3'=>'<b>Lic. Ceryol Villaroel</b>',
						  'firma4'=>'<b>Pro. Alberto Boutto</b>',
						  'firma5'=>'<b>Ing. Heli S. Sulbaran</b>',
						  'firma6'=>'<b>Lic. Petra Salgado</b>');
		$la_columna=array('firma1'=>'','firma2'=>'','firma3'=>'','firma4'=>'','firma5'=>'','firma6'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>6, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>510, // Ancho de la tabla
						 'maxWidth'=>510, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'rowGap' => 0.5,
						 'cols'=>array('firma1'=>array('justification'=>'center','width'=>85), // Justificación y ancho de la columna
						 			   'firma2'=>array('justification'=>'center','width'=>85), // Justificación y ancho de la columna
						 			   'firma3'=>array('justification'=>'center','width'=>85), // Justificación y ancho de la columna
						 			   'firma4'=>array('justification'=>'center','width'=>85), // Justificación y ancho de la columna
						 			   'firma5'=>array('justification'=>'center','width'=>85), // Justificación y ancho de la columna
						 			   'firma6'=>array('justification'=>'center','width'=>85))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('firma1'=>'Fecha:        /          /  ',
						  'firma2'=>'Fecha:        /          /  ',
						  'firma3'=>'Fecha:        /          /  ',
						  'firma4'=>'Fecha:        /          /  ',
						  'firma5'=>'Fecha:        /          /  ',
						  'firma6'=>'Fecha:        /          /  ');
		$la_columna=array('firma1'=>'','firma2'=>'','firma3'=>'','firma4'=>'','firma5'=>'','firma6'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>7, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>510, // Ancho de la tabla
						 'maxWidth'=>510, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'rowGap' => 0.5,
						 'cols'=>array('firma1'=>array('justification'=>'left','width'=>85), // Justificación y ancho de la columna
						 			   'firma2'=>array('justification'=>'left','width'=>85), // Justificación y ancho de la columna
						 			   'firma3'=>array('justification'=>'left','width'=>85), // Justificación y ancho de la columna
						 			   'firma4'=>array('justification'=>'left','width'=>85), // Justificación y ancho de la columna
						 			   'firma5'=>array('justification'=>'left','width'=>85), // Justificación y ancho de la columna
						 			   'firma6'=>array('justification'=>'left','width'=>85))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data[1]=array('firma1'=>'Firma:','firma2'=>'Firma:','firma3'=>'Firma:','firma4'=>'Firma:','firma5'=>'Firma:','firma6'=>'Firma:');
		$la_data[2]=array('firma1'=>'','firma2'=>'','firma3'=>'','firma4'=>'','firma5'=>'','firma6'=>'');
		$la_data[3]=array('firma1'=>'','firma2'=>'','firma3'=>'','firma4'=>'','firma5'=>'','firma6'=>'');
		$la_data[4]=array('firma1'=>'','firma2'=>'','firma3'=>'','firma4'=>'','firma5'=>'','firma6'=>'');
		$la_data[5]=array('firma1'=>'','firma2'=>'','firma3'=>'','firma4'=>'','firma5'=>'','firma6'=>'');
		$la_data[5]=array('firma1'=>'','firma2'=>'','firma3'=>'','firma4'=>'','firma5'=>'','firma6'=>'');
		$la_columna=array('firma1'=>'','firma2'=>'','firma3'=>'','firma4'=>'','firma5'=>'','firma6'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>7, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>510, // Ancho de la tabla
						 'maxWidth'=>510, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'rowGap' => 0.5,
						 'cols'=>array('firma1'=>array('justification'=>'left','width'=>85), // Justificación y ancho de la columna
						 			   'firma2'=>array('justification'=>'left','width'=>85), // Justificación y ancho de la columna
						 			   'firma3'=>array('justification'=>'left','width'=>85), // Justificación y ancho de la columna
						 			   'firma4'=>array('justification'=>'left','width'=>85), // Justificación y ancho de la columna
						 			   'firma5'=>array('justification'=>'left','width'=>85), // Justificación y ancho de la columna
						 			   'firma6'=>array('justification'=>'left','width'=>85))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_firmas
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
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_desnom="<b>".$_SESSION["la_nomina"]["desnom"]."</b>";
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$ls_titulo="<b>Retenciones y Aportes Patronales</b>";
	if(intval(substr($ld_fecdesper,0,2))<=15)
	{
		$ls_quincena="Primera Quincena del mes de ".$io_fecha->uf_load_nombre_mes(substr($ld_fecdesper,3,2))." del ".substr($ld_fecdesper,6,4);
	}
	else
	{
		$ls_quincena="Segunda Quincena del mes de ".$io_fecha->uf_load_nombre_mes(substr($ld_fecdesper,3,2))." del ".substr($ld_fecdesper,6,4);
	}
	$ls_periodo="Del ".$ld_fecdesper." al ".$ld_fechasper."</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codconc=$io_fun_nomina->uf_obtenervalor_get("codconc","");
	$ls_nomcon=$io_fun_nomina->uf_obtenervalor_get("nomcon","");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_aportepatronal_personal($ls_codconc,$ls_conceptocero,$ls_subnomdes,$ls_subnomhas,$ls_orden); // Cargar el DS con los datos del reporte
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
		$io_pdf->ezSetCmMargins(5.4,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_desnom,$ls_quincena,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		uf_print_cabecera($ls_codconc,$ls_nomcon,$io_pdf); // Imprimimos la cabecera del registro
		$li_totrow=$io_report->DS->getRowCount("cedper");
		$li_totper=0;
		$li_totpat=0;
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
			$ls_cedper=$io_report->DS->data["cedper"][$li_i];
			$ls_nomper=$io_report->DS->data["apeper"][$li_i].", ".$io_report->DS->data["nomper"][$li_i];
			$li_personal=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS->data["personal"][$li_i]));
			$li_patron=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS->data["patron"][$li_i]));
			$li_total=abs($io_report->DS->data["personal"][$li_i]+$io_report->DS->data["patron"][$li_i]);
			$li_total=$io_fun_nomina->uf_formatonumerico($li_total);
			$li_totper=$li_totper+abs($io_report->DS->data["personal"][$li_i]);
			$li_totpat=$li_totpat+abs($io_report->DS->data["patron"][$li_i]);
			$li_totalgeneral=$li_totper+$li_totpat;
			$la_data[$li_i]=array('nro'=>$li_i,'cedula'=>$ls_cedper,'nombre'=>$ls_nomper,'personal'=>$li_personal,'patron'=>$li_patron,'total'=>$li_total);
		}
		$io_report->DS->resetds("cedper");
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		unset($la_data);
		$li_totper=$io_fun_nomina->uf_formatonumerico($li_totper);
		$li_totpat=$io_fun_nomina->uf_formatonumerico($li_totpat);
		$li_totalgeneral=$io_fun_nomina->uf_formatonumerico($li_totalgeneral);
		uf_print_piecabecera($li_totper,$li_totpat,$li_totalgeneral,$io_pdf); // Imprimimos el fin del reporte
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