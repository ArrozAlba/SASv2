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

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_listadopersonalegresado.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_titulo2,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,950,40);		
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=420-($li_tm/2);
		$io_pdf->addText($tm,550,18,$as_titulo); // Agregar el título
		$io_pdf->addText(350,525,16,$as_titulo2); // Agregar el título
		$io_pdf->addText(912,560,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(918,553,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera(&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/07/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSety(520);
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(55,496,890,$io_pdf->getFontHeight(19));
        $io_pdf->setColor(0,0,0);
		$la_data[1]=array('nombre'=>'<b>Apellidos y Nombres</b>',
						  'cedula'=>'<b>Cédula</b>',
						  'unidad'=>'<b>Unidad Administartiva</b>',
						  'departamento'=>'<b>Departamento</b>',
						  'cargo'=>'<b>Cargo</b>',	
						  'codigo'=>'<b>Código RAC Único</b>',
						  'sueldo'=>'<b>Sueldo</b>',		
						  'fechaegr'=>'<b>Fecha de Egreso</b>',
						  'causa'=>'<b>Causa de Egreso</b>',
						  'obs'=>'<b>Observación</b>');
		$la_columna=array('nombre'=>'',
						  'cedula'=>'',
						  'unidad'=>'',
						  'departamento'=>'',
						  'cargo'=>'',	
						  'codigo'=>'',
						  'sueldo'=>'',		
						  'fechaegr'=>'',
						  'causa'=>'',
						  'obs'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla				         
						 'cols'=>array('nombre'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
									   'cedula'=>array('justification'=>'center','width'=>60),
						 			   'unidad'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
									   'departamento'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
									   'cargo'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
									   
									   'codigo'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna						 		
									   'sueldo'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'fechaegr'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'causa'=>array('justification'=>'center','width'=>90),
									   'obs'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('nombre'=>'',
						  'cedula'=>'',
						  'unidad'=>'',
						  'departamento'=>'',
						  'cargo'=>'',	
						  'codigo'=>'',
						  'sueldo'=>'',		
						  'fechaegr'=>'',
						  'causa'=>'',
						  'obs'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla				         
						 'cols'=>array('nombre'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
									   'cedula'=>array('justification'=>'center','width'=>60),
						 			   'unidad'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
									   'departamento'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
									   'cargo'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
									   
									   'codigo'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna						 		
									   'sueldo'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'fechaegr'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'causa'=>array('justification'=>'center','width'=>90),
									   'obs'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna

		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
//-------------------------------------------------------------------------------------------------------------------------------------- 
///------------------------------------------------------------------------------------------------------------------------------------
    function uf_print_firmas(&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_firmas
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/08/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $io_pdf->ezSetY(200);
	   $la_data1[1]=array('titulo'=>'<b>OBSERVACIONES:</b>');
	   $la_data1[2]=array('titulo'=>'');
	   $la_data1[3]=array('titulo'=>'');	  
	   $la_columna=array('titulo'=>'');
	   $la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas						 
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla						 
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>900))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);  
		
	   $la_data2[1]=array('titulo'=>'','titulo2'=>'');	 
	   $la_data2[2]=array('titulo'=>'','titulo2'=>'');	 
	   $la_data2[3]=array('titulo'=>'','titulo2'=>'');	
	   $la_data2[4]=array('titulo'=>'','titulo2'=>'');  
	   $la_data2[5]=array('titulo'=>'<b>GERENTE DE RECURSOS HUMANOS</b>','titulo2'=>'PRESIDENTE JUNTA ADMINISTRADORA');
	   $la_columna=array('titulo'=>'','titulo2'=>'');
	   $la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas						 
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla						 
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>450),
						               'titulo2'=>array('justification'=>'center','width'=>450))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data2,$la_columna,'',$la_config);            
	}// end function uf_print_cabecera
//--------------------------------------------------------------------------------------------------------------------------------------

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
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");		
	$ls_masculino=$io_fun_nomina->uf_obtenervalor_get("masculino","");
	$ls_femenino=$io_fun_nomina->uf_obtenervalor_get("femenino","");
	$ls_fecdes=$io_fun_nomina->uf_obtenervalor_get("fecdes","");  
	$ls_fechas=$io_fun_nomina->uf_obtenervalor_get("fechas","");
	$ls_titulo="<b>Listado de Relación del Personal Egresado</b>";
	$ls_titulo2="<b>Desde: </b>".$io_funciones->uf_convertirfecmostrar($ls_fecdes)."<b> Hasta: </b>".$io_funciones->uf_convertirfecmostrar($ls_fechas);	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{  
		$lb_valido=$io_report->uf_listado_personalegresado($ls_codperdes,$ls_codperhas,$ls_codnomdes,$ls_codnomhas,$ls_femenino,$ls_masculino,$ls_fecdes,$ls_fechas,$ls_orden);
	}
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		error_reporting(E_ALL);
		set_time_limit(1800);		
		$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3,3,3,3); // Configuración de los margenes en centímetros		
		uf_print_encabezado_pagina($ls_titulo,$ls_titulo2,$io_pdf); // Imprimimos el encabezado de la página	  
		$io_pdf->ezStartPageNumbers(950,50,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->DS->getRowCount("codper");
		uf_print_cabecera($io_pdf);	
		for ($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
			$ls_cedper=$io_report->DS->data["cedper"][$li_i];
			$ls_nacper=$io_report->DS->data["nacper"][$li_i];
			$ls_nomper=$io_report->DS->data["nomper"][$li_i];
			$ls_apeper=$io_report->DS->data["apeper"][$li_i];
			$ls_unidad=$io_report->DS->data["desuni"][$li_i];
			$ls_depto=$io_report->DS->data["desdep"][$li_i];
			$ls_dencar=$io_report->DS->data["descar"][$li_i];
			$ls_denasicar=$io_report->DS->data["desasicar"][$li_i];
			if ($ls_dencar!="Sin Cargo")
			{
				$ls_cargo=$ls_dencar;
			}
			else
			{
				$ls_cargo=$ls_denasicar;
			}
			$ls_codunico=$io_report->DS->data["codunirac"][$li_i];
			$ls_sueldo=$io_report->DS->data["sueper"][$li_i];				
			$ls_fechaegr=$io_report->DS->data["fecegrper"][$li_i];
			$ls_causa=$io_report->DS->data["cauegrper"][$li_i];			
			$ls_causaegr="";
			switch($ls_causa)
			{
				case "": 
					$ls_causaegr="N/A";		
				break;
				
				case "N": 
					$ls_causaegr="Ninguno";		
				break;
				
				case "D": 
					$ls_causaegr="Despido";		
				break;
				
				case "1": 
					$ls_causaegr="Despido 102";		
				break;
				
				case "2": 
					$ls_causaegr="Despido 125";		
				break;
				
				case "P": 
					$ls_causaegr="Pensionado";		
				break;
				
				case "R": 
					$ls_causaegr="Renuncia";		
				break;
				
				case "T": 
					$ls_causaegr="Traslado";		
				break;
				
				case "J": 
					$ls_causaegr="Jubilado";		
				break;
				
				case "F": 
					$ls_causaegr="Fallecido";		
				break;
			}
			$ls_observacion=$io_report->DS->data["obsegrper"][$li_i];	
			$ls_data[$li_i]=array('nombre'=>$ls_apeper,", ".$ls_nomper,
						  'cedula'=>$ls_nacper." ".number_format($ls_cedper,0,",","."),
						  'unidad'=>$ls_unidad,
						  'departamento'=>$ls_depto,
						  'cargo'=>$ls_cargo,	
						  'codigo'=>$ls_codunico,
						  'sueldo'=> number_format($ls_sueldo,2,",","."),		
						  'fechaegr'=>$io_funciones->uf_convertirfecmostrar($ls_fechaegr),
						  'causa'=>$ls_causaegr,
						  'obs'=>$ls_observacion);					
		}// fin del for
		uf_print_detalle($ls_data,&$io_pdf);
		uf_print_firmas(&$io_pdf);			
		if($lb_valido)// Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que reportar');"); 
			print(" close();");
			print("</script>");		
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 