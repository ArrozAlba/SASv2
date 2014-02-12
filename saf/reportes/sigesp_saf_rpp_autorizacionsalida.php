<?php
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
	ini_set('memory_limit','24M');
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 05/02/2007
		// Fecha Modificación: 11/04/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,695,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,680,11,$as_titulo); // Agregar el título
	
		$io_pdf->addText(500,740,12,"Pág."); // Agregar texto
		//$io_pdf->addText(500,710,9,date("d/m/Y")); // Agregar la Fecha
		//$io_pdf->addText(500,700,9,date("h:i a")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_cabecera($ls_denuniadmcede,$ls_codprov,$ls_nomprov,$ls_cedrepre,$ls_nomrepre,
		                 $ls_concepto,$ld_fecent,$ld_fecdevo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_cabecera
		//		    Acess: private
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: Ing. Yozelin Barragán
		// Fecha Creación: 05/02/2007
		//////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-10);
		/*$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();*/
		$ls_fecha=date("d/m/Y");
	    $la_data=array(array('name'=>'   Dependencia: '.$ls_denuniadmcede.'     '),
				       array('name'=>'   Entregado A:  '.$ls_nomrepre.',     C.I  Nº: '.$ls_cedrepre.''),
					   array('name'=>'   Representante de la Empresa: '.$ls_nomprov.''),
					   array('name'=>'   Por Concepto de : '.$ls_concepto.''),
					   array('name'=>'   Fecha de Entrega: '.$ld_fecent.'       Fecha de Devolución: '.$ld_fecdevo.''));
		
		$la_columnas=array('name'=>'','name'=>'','name'=>'','name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 12, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.5,0.5,0.5),// Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>306); // Justificación y ancho de la 
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	/*	$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');*/
	}// end function uf_cabecera_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 11/04/2008
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(650);
		$la_columna=array('cantidad'=>'<b>Cantidad</b>',
						  'codact'=>'<b>            Código</b>',
						  'ideact'=>'<b>                     Identificación</b>',
						  'denact'=>'<b>             Descripción</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cantidad'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'codact'=>array('justification'=>'left','width'=>100), // Justificación y ancho de la columna
						 			   'ideact'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'denact'=>array('justification'=>'left','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_detalle($ls_obser,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el pie del detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 05/02/2007
		// Fecha Modificación: 11/04/2008
		//////////////////////////////////////////////////////////////////////////////////////////
		//$io_pdf->ezSetY(350);
		/*$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();*/
		$io_pdf->ezSetDy(-10);
	    $la_data=array(array('name'=>'   observación: '.$ls_obser.''));
					   		
		$la_columnas=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 12, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.5,0.5,0.5),//array($r,$g,$b), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>306); // Orientación de la tabla 
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	/*	$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');*/
	}// end function uf_print_pie_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ls_denuniadmcede,$ls_nomprov,$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	 
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por : Ing. Yesenia Moreno
		//  Modificado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 11/04/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->addText(255,300,10,"<b>Firmas y Sellos</b>"); 
        $io_pdf->addText(30,255,10,"____________________________________");   
        $io_pdf->addText(45,240,9,"<b>DEPENDENCIA QUE ENTREGA</b>");  
		$ls_denadmcede=$io_pdf->addTextWrap(40,230,200,9,$ls_denuniadmcede);
		$io_pdf->addText(40,220,9,$ls_denadmcede);
		//$io_pdf->addText(40,230,8,$ls_denadmcede);  
        $io_pdf->addText(30,170,10,"____________________________________");   
        $io_pdf->addText(40,155,9,"<b>COORDINACIÓN DE BIENES</b>");  
        $io_pdf->addText(70,140,9,"<b>NACIONALES</b>"); 
        $io_pdf->addText(340,255,10,"____________________________________");
		$io_pdf->addText(370,240,9,"<b>DEPENDENCIA QUE RECIBE</b>"); 
		$ls_nompro=$io_pdf->addTextWrap(370,230,200,9,$ls_nomprov);
		$io_pdf->addText(370,220,9,$ls_nompro);
		//$io_pdf->addText(370,230,8,$ls_nomprov);   
        $io_pdf->addText(350,170,10,"____________________________________");   
        $io_pdf->addText(355,155,9,"<b>DIVISIÓN DE ADMINISTRACIÓN Y</b>");  
        $io_pdf->addText(410,140,9,"<b>SERVICIO</b>");
        $io_pdf->addText(200,100,10,"_________________________________________");   
        $io_pdf->addText(210,85,9,"<b>DPTO. SEGURIDAD Y ORDEN PÚBLICO</b>"); 
		$io_pdf->addText(30,55,9,"<b>NOTA: Anexar oficio de Solicitud</b>"); 
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');

	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../class_funciones_activos.php");
		$io_fun_activo=new class_funciones_activos("../../");
		$ls_tipoformato=$io_fun_activo->uf_obtenervalor_get("tipoformato",0);
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
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -------------------------------------
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_nomemp=$arre["nombre"];
	$ls_estemp=$arre["estemp"];
	$ls_periodo=$arre["periodo"];
	//----------------------------------------------------  Parámetros del encabezado  --------------------------------------------
	$ls_titulo="<b>AUTORIZACIÓN DE SALIDA</b>";   
	$ls_cmpsal=$io_fun_activo->uf_obtenervalor_get("ls_cmpsal",""); 
	$ls_coduniadmcede=$io_fun_activo->uf_obtenervalor_get("ls_coduniadmcede","");  
	$ls_denuniadmcede=$io_fun_activo->uf_obtenervalor_get("ls_denuniadmcede","");
	$ld_fechauto=$io_fun_activo->uf_obtenervalor_get("ld_fechauto",""); 
	$ls_codprov=$io_fun_activo->uf_obtenervalor_get("ls_codprov",""); 
	$ls_nomprov=$io_fun_activo->uf_obtenervalor_get("ls_nomprov",""); 
	$ls_cedrepre=$io_fun_activo->uf_obtenervalor_get("ls_cedrepre","");   
	$ls_nomrepre=$io_fun_activo->uf_obtenervalor_get("ls_nomrepre",""); 
	$ls_concepto=$io_fun_activo->uf_obtenervalor_get("ls_concepto",""); 
	$ld_fecent=$io_fun_activo->uf_obtenervalor_get("ld_fecent","");   
	$ld_fecdevo=$io_fun_activo->uf_obtenervalor_get("ld_fecdevo",""); 
	$ls_obser=$io_fun_activo->uf_obtenervalor_get("ls_obser","");     
	//--------------------------------------------------------------------------------------------------------------------------------
   
	$lb_valido=$io_report->uf_saf_buscar_autorización($ls_codemp,$ls_cmpsal,$ls_coduniadmcede,$ld_fechauto,$ls_codprov,
	                                     $ls_cedrepre,$ls_concepto,$ld_fecent,$ld_fecdevo,$ls_obser); // Cargar el DS con los datos de la cabecera del reporte
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	 else // Imprimimos el reporte
	 {
		/////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////
		$ls_desc_event="Se Genero la autorización de salida ".$ls_cmpsal." ";
		$io_fun_activo->uf_load_seguridad_reporte("SAF","sigesp_saf_p_autorizacionsalida.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Times-Roman.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,9,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,740,10,'','',1); // Insertar el número de página
		$io_pdf->transaction('start'); // Iniciamos la transacción
		$li_numpag=$io_pdf->ezPageCount; // Número de página
	   
		if($lb_valido)
		{
		 	$li_totrow_det=$io_report->ds_detalle->getRowCount("codact");
			for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
			{
				$ls_codart=$io_report->ds_detalle->data["codact"][$li_s];
				$li_cantidad=$io_report->ds_detalle->data["cantidad"][$li_s];
				$ls_denart=$io_report->ds_detalle->data["denact"][$li_s];
				$ls_ideact=$io_report->ds_detalle->data["ideact"][$li_s];
	     		$la_data[$li_s]=array('cantidad'=>$li_cantidad,'codact'=>$ls_codart,
									  'ideact'=>$ls_ideact,'denact'=>$ls_denart);
			}
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		}
		unset($la_data);
		uf_cabecera($ls_denuniadmcede,$ls_codprov,$ls_nomprov,$ls_cedrepre,$ls_nomrepre,
		            $ls_concepto,$ld_fecent,$ld_fecdevo,&$io_pdf);			
		uf_print_pie_detalle($ls_obser,$io_pdf); 
		uf_print_pie_cabecera($ls_denuniadmcede,$ls_nomprov,$io_pdf);
		unset($la_data);
		$io_pdf->ezStopPageNumbers(1,1);
		if (isset($d) && $d)
		{
			$ls_pdfcode = $io_pdf->ezOutput(1);
		  	$ls_pdfcode = str_replace("\n","\n<br>",htmlspecialchars($ls_pdfcode));
		  	echo '<html><body>';
		  	echo trim($ls_pdfcode);
		  	echo '</body></html>';
		}
		else
		{
			$io_pdf->ezStream();
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_activo);
?> 