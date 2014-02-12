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
	}	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 04/06/2008 		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_clasificacionobrero.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 04/06/2008		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,695,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título			
		$io_pdf->addText(500,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(506,743,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_anovig,$as_nrogac,&$io_pdf)
	{		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 04/06/2008 
		////////////
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_grupo[1]=array('anovig'=>'',
						   'gaceta'=>'');
	    $la_grupo[2]=array('anovig'=>'<b>Año: '.$as_anovig.'</b>',
						   'gaceta'=>'<b>Nro. de Gaceta: '.$as_nrogac.'</b>');
		$la_columna=array('anovig'=>'',
						  'gaceta'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas						
						 'shadeCol'=>array(0.9,0.9,0.9),						
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('anovig'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la columna
						 			   'gaceta'=>array('justification'=>'left','width'=>340)));  // Justificación y ancho de la columna
		$io_pdf->ezTable($la_grupo,$la_columna,'',$la_config);	
		unset($la_grupo);
		unset($la_columna);
		unset($la_config);
		//-------------------------------tabal de la cabeza del detalle------------------------------------------------------				
		$la_data_c[1]=array('grado'=>'<b>Grado</b>',
						    'suemin'=>'<b>Sueldo Mínimo</b>',
						    'suemax'=>'<b>Sueldo Máximo</b>',
							'tipo'=>'<b>Tipo</b>',
						    'obscal'=>'<b>Observación</b>');
		$la_columna=array('grado'=>'',
		                  'suemin'=>'',
						  'suemax'=>'',
						  'tipo'=>'',
			              'obscal'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('grado'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'suemin'=>array('justification'=>'center','width'=>90),
									   'suemax'=>array('justification'=>'center','width'=>90),
									   'tipo'=>array('justification'=>'center','width'=>100),
									   'obscal'=>array('justification'=>'center','width'=>150)));  // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_c,$la_columna,'',$la_config);	
		unset($la_data_c);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_cabecera
	//---------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 04/06/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('grado'=>'',
		                  'suemin'=>'',
						  'suemax'=>'',
						  'tipo'=>'',
			              'obscal'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('grado'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'suemin'=>array('justification'=>'right','width'=>90),
									   'suemax'=>array('justification'=>'right','width'=>90),
									   'tipo'=>array('justification'=>'center','width'=>100),
									   'obscal'=>array('justification'=>'left','width'=>150)));  // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>Reporte de Clasificación de Obreros</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	
	if($lb_valido)
	{
	   $lb_valido=$io_report->uf_clasificacion_obrero($ls_orden);
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
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.2,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página		
		$li_totrow=$io_report->DS->getRowCount("grado");
		$ano_aux="";
		$gaceta_aux="";		
		for ($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
		 	$ls_grado=$io_report->DS->data["grado"][$li_i];
			$ls_suemin=$io_report->DS->data["suemin"][$li_i];
			$ls_suemax=$io_report->DS->data["suemax"][$li_i];
			$ls_observacion=$io_report->DS->data["obscla"][$li_i];	
			$ls_anovig=$io_report->DS->data["anovig"][$li_i];
			$ls_nrogac=$io_report->DS->data["nrogac"][$li_i];			
			$ls_tipo=$io_report->DS->data["tipcla"][$li_i];
			$ls_ticla="";
			switch ($ls_tipo)
		    {
				case "01":
					$ls_ticla="No Calificado";
				break;
				case "02":
					$ls_ticla="Calificado";
				break;
				case "03":
					$ls_ticla="Supervisor";
				break;
			}
			
			if (($ano_aux==$ls_anovig)&&($gaceta_aux==$ls_nrogac))
			{
				$ls_data[$li_i]=array('grado'=>$ls_grado,'suemin'=>$io_fun_nomina->uf_formatonumerico($ls_suemin),
				                      'suemax'=>$io_fun_nomina->uf_formatonumerico($ls_suemax),
			                          'obscal'=>$ls_observacion,'tipo'=>$ls_ticla);		     							
			}
			else
			{
			    uf_print_cabecera($ls_anovig,$ls_nrogac,&$io_pdf);
			    $ls_data[$li_i]=array('grado'=>$ls_grado,'suemin'=>$io_fun_nomina->uf_formatonumerico($ls_suemin),
				                      'suemax'=>$io_fun_nomina->uf_formatonumerico($ls_suemax),
			                          'obscal'=>$ls_observacion,'tipo'=>$ls_ticla);						
				$ano_aux=$ls_anovig;
				$gaceta_aux=$ls_nrogac;											  
		    }
		    uf_print_detalle($ls_data,&$io_pdf);
			unset($ls_data);		
		}
			
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algún error
		{
			print("<script language=JavaScript>");
		    print(" alert('No hay nada que Reportar');"); 
		    print(" close();");
		    print("</script>");	
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 