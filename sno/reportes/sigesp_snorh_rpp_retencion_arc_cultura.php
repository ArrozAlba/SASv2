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
	ini_set('memory_limit','64M');
	ini_set('max_execution_time','0');

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo,$as_titulo2)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/02/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo." ".$as_titulo2;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_retencion_arc.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/02/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulobs="Bolívares Fuertes";
		}
		else
		{
			$ls_titulobs="Bolívares";
		}
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$ls_titulobs);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,720,10,$ls_titulobs); // Agregar el título

		$io_pdf->addText(512,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(518,743,7,date("h:i a")); // Agregar la Hora
		$io_pdf->line(400,90,555,90);
		$io_pdf->addText(430,80,8,"AGENTE DE RETENCIÓN"); // Agregar la Fecha
		$io_pdf->addText(420,70,8,"Soc.EINSTEIN PAREJO ABREU"); // Agregar la Fecha
		$io_pdf->addText(407,60,8,"DIRECTOR DE RECURSOS HUMANOS"); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_nacper,$as_cedper,$as_nomper,$as_titulo2,&$io_cabecera,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_nacper // nacionalidad del Personal
		//	    		   as_cedper // Cédula del Personal
		//	   			   as_nomper // Nombre del Personal
		//	    		   io_cabecera // objeto cabecera
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/02/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		// Periodo de Retención
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(50,700,500,$io_pdf->getFontHeight(11));
        $io_pdf->setColor(0,0,0);
		$li_tm=$io_pdf->getTextWidth(9,'RELACIÓN DE INGRESOS Y RETENCIONES');
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,703,9,'<b>RELACIÓN DE INGRESOS Y RETENCIONES</b>'); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(8,$as_titulo2);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,690,8,$as_titulo2); // Agregar el título
		// Datos de Trabajador
	    $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(50,670,500,$io_pdf->getFontHeight(12));
        $io_pdf->setColor(0,0,0);
		$li_tm=$io_pdf->getTextWidth(9,'DATOS DEL TRABAJADOR');
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,673,9,'<b>DATOS DEL TRABAJADOR</b>'); // Agregar el título
	    $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(51,650,500,$io_pdf->getFontHeight(11));
        $io_pdf->setColor(0,0,0);
		$la_data[0]=array('nombre'=>'APELLIDOS Y NOMBRES','cedula'=>'CÉDULA DE IDENTIDAD','rif'=>'RIF.' );
		$la_data[1]=array('nombre'=>$as_nomper,'cedula'=>$as_cedper,'rif'=>'');
		$la_columnas=array('nombre'=>'',
						   'cedula'=>'',
						   'rif'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('nombre'=>array('justification'=>'center','width'=>280), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
						 			   'rif'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezSetDy(-25);
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		// Datos del Funcionario Autorizado
	    $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(50,615,500,$io_pdf->getFontHeight(12));
        $io_pdf->setColor(0,0,0);
		$li_tm=$io_pdf->getTextWidth(9,'DATOS DEL FUNCIONARIO AUTORIZADO PARA HACER LA RETENCIÓN');
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,618,9,'<b>DATOS DEL FUNCIONARIO AUTORIZADO PARA HACER LA RETENCIÓN</b>'); // Agregar el título
	    $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(51,595,500,$io_pdf->getFontHeight(11.3));
        $io_pdf->setColor(0,0,0);
		$la_data[0]=array('nombre'=>'APELLIDOS Y NOMBRES','cedula'=>'CÉDULA DE IDENTIDAD','rif'=>'RIF.' );
		$la_data[1]=array('nombre'=>'PAREJO ABREU EINSTEIN','cedula'=>'4.216.636','rif'=>'4216636');
		$la_columnas=array('nombre'=>'',
						   'cedula'=>'',
						   'rif'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('nombre'=>array('justification'=>'center','width'=>280), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
						 			   'rif'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezSetDy(-28);
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		// Datos del Agente de Retención
	    $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(50,561,500,$io_pdf->getFontHeight(12));
        $io_pdf->setColor(0,0,0);
		$li_tm=$io_pdf->getTextWidth(9,'DATOS DEL AGENTE DE RETENCIÓN');
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,564,9,'<b>DATOS DEL AGENTE DE RETENCIÓN</b>'); // Agregar el título
	    $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(51,541,500,$io_pdf->getFontHeight(11));
        $io_pdf->setColor(0,0,0);
		$la_data[0]=array('nombre'=>'PERSONA JURÍDICA -- NOMBRE');
		$la_data[1]=array('nombre'=>$_SESSION["la_empresa"]["nombre"]);
		$la_columnas=array('nombre'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('nombre'=>array('justification'=>'center','width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezSetDy(-28);
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	    $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(51,514,500,$io_pdf->getFontHeight(11.3));
        $io_pdf->setColor(0,0,0);
		$la_data[0]=array('direccion'=>'DIRECCIÓN DEL AGENTE DE RETENCIÓN','rif'=>'RIF.');
		$la_data[1]=array('direccion'=>$_SESSION["la_empresa"]["direccion"],'rif'=>$_SESSION["la_empresa"]["rifemp"]);
		$la_columnas=array('direccion'=>'','rif'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('direccion'=>array('justification'=>'center','width'=>430),
						 			   'rif'=>array('justification'=>'center','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	    $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(51,488,500,$io_pdf->getFontHeight(11));
        $io_pdf->setColor(0,0,0);
		$la_data[0]=array('telefono'=>'TELÉFONO','zona'=>'ZONA POSTAL','ciudad'=>'CIUDAD','estado'=>'ESTADO / DISTRITO');
		$la_data[1]=array('telefono'=>$_SESSION["la_empresa"]["telemp"],'zona'=>$_SESSION["la_empresa"]["zonpos"],
		                  'ciudad'=>$_SESSION["la_empresa"]["ciuemp"],'estado'=>$_SESSION["la_empresa"]["estemp"]);
		$la_columnas=array('telefono'=>'','zona'=>'','ciudad'=>'','estado'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('telefono'=>array('justification'=>'center','width'=>130),
						 			   'zona'=>array('justification'=>'center','width'=>100),
									   'ciudad'=>array('justification'=>'center','width'=>135),
						 			   'estado'=>array('justification'=>'center','width'=>135))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
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
		// Fecha Creación: 04/08/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(51,441,500,$io_pdf->getFontHeight(21));
        $io_pdf->setColor(0,0,0);
		$io_pdf->ezSetY(465);
		$la_columnas=array('mes'=>'<b>MES</b>',
						   'arc'=>'<b>DEVENGADO         </b>',
						   'arcacum'=>'<b>DEVENGADO         ACUMULADO         </b>',
						   'porcentaje'=>'<b>PORCENTAJE   </b>',
						   'retencion'=>'<b>RETENIDO           </b>',
						   'retencionacum'=>'<b>RETENIDO            ACUMULADO         </b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('mes'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 			   'arc'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'arcacum'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'porcentaje'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'retencion'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'retencionacum'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_aporte($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_aporte
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-10);
		$la_columnas=array('codigo'=>'<b>Código</b>',
						   'nombre'=>'<b>                                   Nombre</b>',
						   'monto'=>'<b>Monto               </b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>360, // Ancho de la tabla
						 'maxWidth'=>360, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'Aporte Patronal',$la_config);
	}// end function uf_print_detalle_aporte
	//-----------------------------------------------------------------------------------------------------------------------------------

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
	$ls_titulo="<b>DIRECCIÓN GENERAL DE RECURSOS HUMANOS</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$li_total=$io_fun_nomina->uf_obtenervalor_get("total","0");
	for($li_i=1;$li_i<=$li_total;$li_i++)
	{
		$la_nominas[$li_i]=$io_fun_nomina->uf_obtenervalor_get("codnom".$li_i,"");
	}
	$ls_codperdes      = $io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas      = $io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_ano=$io_fun_nomina->uf_obtenervalor_get("ano","");
	$ls_conceptoaporte=$io_fun_nomina->uf_obtenervalor_get("conceptoaporte","");
	$ls_excluir=$io_fun_nomina->uf_obtenervalor_get("excluir","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_titulo2="PERÍODO:				 01/01/".$ls_ano." 		AL		 31/12/".$ls_ano;
	$ls_tiporeporte=$io_fun_nomina->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_snorh_class_reportbsf.php");
		$io_report=new sigesp_snorh_class_reportbsf();
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_titulo2); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_retencionarc_personal($la_nominas,$li_total,$ls_ano,$ls_orden,$ls_codperdes,$ls_codperhas); // Cargar el DS con los datos del reporte
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
		$io_pdf->ezSetCmMargins(3.6,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->DS->getRowCount("codper");
		$lb_cabecera=false;
		$lb_print=false;
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_codper=$io_report->DS->data["codper"][$li_i];
			$ls_nomper=$io_report->DS->data["apeper"][$li_i].", ".$io_report->DS->data["nomper"][$li_i];
			$ls_nacper=$io_report->DS->data["nacper"][$li_i];
			$ls_cedper=$io_report->DS->data["cedper"][$li_i];
			$lb_valido=$io_report->uf_retencionarc_meses($ls_codper,$la_nominas,$li_total,$ls_ano); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->DS_detalle->getRowCount("codper");
				$li_arcacum=0;
				$li_islracum=0;
				$lb_arc=false;
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_codisr=$io_report->DS_detalle->data["codisr"][$li_s];
					$li_porisr=$io_fun_nomina->uf_formatonumerico($io_report->DS_detalle->data["porisr"][$li_s]*100);
					$li_arcacum=$li_arcacum+abs($io_report->DS_detalle->data["arc"][$li_s]);
					$li_islracum=$li_islracum+abs($io_report->DS_detalle->data["islr"][$li_s]);
					$li_arc=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["arc"][$li_s]));
					$li_islr=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["islr"][$li_s]));
					$li_arc_acumulado=$io_fun_nomina->uf_formatonumerico($li_arcacum);
					$li_isl_acumulado=$io_fun_nomina->uf_formatonumerico($li_islracum);
					$ls_mes=strtoupper(substr($io_fecha->uf_load_nombre_mes($ls_codisr),0,3));
					if($li_arc<>"0,00")
					{
						$lb_arc=true;
					}
					$la_data[$li_s]=array('mes'=>$ls_mes,'arc'=>$li_arc,'arcacum'=>$li_arc_acumulado,'porcentaje'=>$li_porisr,
										  'retencion'=>$li_islr,'retencionacum'=>$li_isl_acumulado);
				}
				$io_report->DS_detalle->resetds("codper");
				if($li_totrow_det==0)
				{
					$lb_arc=false;
					for($li_s=1;$li_s<=12;$li_s++)
					{
						$ls_codisr=str_pad($li_s,2,"0",0);
						$li_porisr=$io_fun_nomina->uf_formatonumerico(0);
						$li_arcacum=0;
						$li_islracum=0;
						$li_arc=$io_fun_nomina->uf_formatonumerico(0);
						$li_islr=$io_fun_nomina->uf_formatonumerico(0);
						$li_arc_acumulado=$io_fun_nomina->uf_formatonumerico(0);
						$li_isl_acumulado=$io_fun_nomina->uf_formatonumerico(0);
						$ls_mes=strtoupper(substr($io_fecha->uf_load_nombre_mes($ls_codisr),0,3));
						$la_data[$li_s]=array('mes'=>$ls_mes,'arc'=>$li_arc,'arcacum'=>$li_arc_acumulado,'porcentaje'=>$li_porisr,
											  'retencion'=>$li_islr,'retencionacum'=>$li_isl_acumulado);
					}
				}
				if($ls_excluir=="1")
				{
					if($lb_arc)
					{				
						$io_cabecera=$io_pdf->openObject(); // Creamos el objeto cabecera
						uf_print_cabecera($ls_nacper,$ls_cedper,$ls_nomper,$ls_titulo2,&$io_cabecera,&$io_pdf); // Imprimimos la cabecera del registro
						$lb_cabecera=true;
						$lb_print=true;
						uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
						unset($la_data);
					}
				}
				else
				{				
					$io_cabecera=$io_pdf->openObject(); // Creamos el objeto cabecera
					uf_print_cabecera($ls_nacper,$ls_cedper,$ls_nomper,$ls_titulo2,&$io_cabecera,&$io_pdf); // Imprimimos la cabecera del registro
					$lb_cabecera=true;
					$lb_print=true;
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					unset($la_data);
					$lb_arc=true;
				}
				if(($lb_valido)&&($lb_arc))// Si no ocurrio ningún error
				{
					if($ls_conceptoaporte=="1") // Si solicita que se muestren los conceptos de aporte
					{
						$lb_valido=$io_report->uf_retencionarc_aporte($ls_codper,$la_nominas,$li_total,$ls_ano);
						$li_totrow_det=$io_report->DS_detalle->getRowCount("codconc");
						for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
						{
							$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_s];
							$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_s];
							$li_monto=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["monto"][$li_s]));
							$la_data[$li_s]=array('codigo'=>$ls_codconc,'nombre'=>$ls_nomcon,'monto'=>$li_monto);
						}
						$io_report->DS_detalle->resetds("codconc");
						if($li_totrow_det>0)
						{
							uf_print_detalle_aporte($la_data,$io_pdf);
						}
					}
				}
				if($lb_cabecera)
				{						
					$io_pdf->stopObject($io_cabecera); // Detener el objeto cabecera
					$lb_cabecera=false;
				}
				if(($li_i<$li_totrow)&&($lb_arc))
				{
					$io_pdf->ezNewPage(); // Insertar una nueva página
				}
				unset($io_cabecera);
				unset($la_data);
			}
		}
		$io_report->DS->resetds("codper");
		if($lb_valido) // Si no ocurrio ningún error
		{
			if($lb_print)
			{
				$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
				$io_pdf->ezStream(); // Mostramos el reporte
			}
			else
			{
				print("<script language=JavaScript>");
				print(" alert('no hay nada que reportar.');"); 
				print(" close();");
				print("</script>");		
			}
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
	unset($io_fecha);
?>