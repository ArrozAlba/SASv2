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
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
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
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->line(20,40,550,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],51,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,725,11,$as_titulo); // Agregar el título
		$tm=312-($li_tm/2);
		$io_pdf->addText(510,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(516,743,7,date("h:i a")); // Agregar la Hora
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
		$io_pdf->ezSety(713);
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(50,500,500,$io_pdf->getFontHeight(11));
        $io_pdf->setColor(0,0,0);
		$la_data[1]=array('codigo'=>'<b>Código</b>',
						  'nombre'=>'<b>Nombre Fiscal</b>',
						  'telefono'=>'<b>Teléfono</b>',
						  'ubicacion'=>'<b>Ubicación</b>',
						  'responsable'=>'<b>Responsable</b>');
		$la_columna=array('codigo'=>'<b>Código</b>',
						  'nombre'=>'<b>Nombre Fiscal</b>',
						  'telefono'=>'<b>Teléfono</b>',
						  'ubicacion'=>'<b>Ubicación</b>',
						  'responsable'=>'<b>Responsable</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>730, // Ancho de la tabla
						 'maxWidth'=>730, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'left','width'=>75), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'telefono'=>array('justification'=>'left','width'=>85), // Justificación y ancho de la columna
						 			   'ubicacion'=>array('justification'=>'left','width'=>240), // Justificación y ancho de la columna
						 			   'responsable'=>array('justification'=>'left','width'=>130))); // Justificación y ancho de la columna
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
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('catalogo'=>'<b>Código</b>',
						  'dencat'=>'<b>Denominación</b>',
						  'spg_cuenta'=>'<b>Cuenta Presupuestaria</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('catalogo'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'dencat'=>array('justification'=>'left','width'=>300), // Justificación y ancho de la columna
						 			   'spg_cuenta'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ai_totent,$ai_totsal,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera
		//		   Access: private 
		//	    Arguments: ai_totent // Total Entradas
		//	   			   ai_totsal // Total Salidas
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	$la_data=array(array('name'=>'_________________________________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$la_data=array(array('total'=>''));
		$la_columna=array('total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>730, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>500), // Justificación y ancho de la columna
						 			   'entradas'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'salidas'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>730, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_saf_class_report.php");
	$io_report=new sigesp_saf_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_funciones_activos.php");
	$io_fun_activos=new class_funciones_activos();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>Listado de Catálogo SIGECOF</b>";
	$ls_fecha="";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
	$ls_coddesde=$io_fun_activos->uf_obtenervalor_get("coddesde","");
	$ls_codhasta=$io_fun_activos->uf_obtenervalor_get("codhasta","");
	$li_orden=$io_fun_activos->uf_obtenervalor_get("orden","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=true;//$io_report->uf_select_articulos($ls_codemp,$ls_codalm,$ls_codart,$ld_desde,$ld_hasta,$li_total,$li_ordenart); // Cargar el DS con los datos de la cabecera del reporte
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		/////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////
		$ls_desc_event="Generó el reporte Listado de Catalogo SIGECOF. Desde el Activo   ".$ls_coddesde." hasta   ".$ls_codhasta;
		$io_fun_activos->uf_load_seguridad_reporte("SAF","sigesp_saf_r_sigecof.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////////
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(510,50,10,'','',1); // Insertar el número de página
		$li_totrow=1;//$io_report->ds->getRowCount("codart");
		//uf_print_cabecera($io_pdf);
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
		    $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$li_totent=0;
			$li_totsal=0;
			//$ls_codmov=     $io_report->ds->data["nummov"][$li_i];
			//$ls_codart=     $io_report->ds->data["codart"][$li_i];
			//$ls_denart=     $io_report->ds->data["denart"][$li_i];
			$lb_valido=$io_report->uf_saf_load_sigecof($ls_codemp,$li_orden,$ls_coddesde,$ls_codhasta); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->ds->getRowCount("catalogo");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_catalogo=  $io_report->ds->data["catalogo"][$li_s];
					$ls_dencat=    $io_report->ds->data["dencat"][$li_s];
					$ls_spgcuenta= $io_report->ds->data["spg_cuenta"][$li_s];
					$la_data[$li_s]=array('catalogo'=>$ls_catalogo,'dencat'=>$ls_dencat,'spg_cuenta'=>$ls_spgcuenta);
				}
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				//uf_print_pie_cabecera("","",$io_pdf); // Imprimimos pie de la cabecera
                /*if ($io_pdf->ezPageCount==$li_numpag)
				{// Hacemos el commit de los registros que se desean imprimir
					$io_pdf->transaction('commit');
				}
				else
				{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
					$io_pdf->transaction('rewind');
					if($li_numpag>1)
					{
						$io_pdf->ezNewPage(); // Insertar una nueva página
					}
				//	uf_print_cabecera($ls_nomemp,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					uf_print_pie_cabecera("","",$io_pdf); // Imprimimos pie de la cabecera
				}*/
			}
			unset($la_data);			
		}
		if($lb_valido)
		{
			$io_pdf->ezStopPageNumbers(1,1);
			$io_pdf->ezStream();
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 