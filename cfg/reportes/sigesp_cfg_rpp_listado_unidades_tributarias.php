<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Reporte de Listado de Unidades Tributarias
//  ORGANISMO: 
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
	function uf_print_encabezado_pagina($as_titulo,$io_pdf)
	{
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(15,40,585,40);
        $io_pdf->Rectangle(16,690,570,90);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		//$as_titulo1="INSTITUTO DE PREVISIÓN SOCIAL DE LAS" ;	
		$as_titulo2="LISTADO DE UNIDADES TRIBUTARIAS";
		/*$as_titulo3="GERENCIA DE RECURSOS HUMANOS";	
		$as_titulo4="SECCION RECLUTAMIENTO Y SELECCIÓN";*/
		$li_tm2=$io_pdf->getTextWidth(11,$as_titulo2);
		$tm2=306-($li_tm2/2);
		/*$li_tm3=$io_pdf->getTextWidth(11,$as_titulo3);
		$tm3=306-($li_tm3/2);
		$li_tm4=$io_pdf->getTextWidth(11,$as_titulo4);
		$tm4=306-($li_tm4/2);*/
		//$io_pdf->addText($tm1,750,11,$as_titulo1); // Agregar el título
		$io_pdf->addText($tm2,735,11,$as_titulo2); // Agregar el título
		/*$io_pdf->addText($tm3,720,11,$as_titulo3); // Agregar el título
		$io_pdf->addText($tm4,705,11,$as_titulo4); // Agregar el título
		*/
		$io_pdf->addText(540,770,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(546,764,6,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	    $io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetY(691.5);
			
	   /* $io_pdf->ezSetY(667);
		$la_data=array(array('titulo1'=>'<b>'.$as_titulo.'</b>'));
					
		$la_columnas=array('titulo1'=>'');
					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('titulo1'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);*/
		
	    $io_pdf->ezSetY(670);
		$la_data[1]=array('codigo'=>'<b>Código U.T.</b>',
		                     'anno'=>'<b>Año</b>',
							 'fecentvig'=>'<b>Fecha Ent. Vigencia</b>',
							 'gacofi'=>'<b>Gaceta Oficial</b>',
							 'fecpubgac'=>'<b>Fecha Publicación</b>',
							 'decnro'=>'<b>Decreto Nº</b>',
							 'fecdec'=>'<b>Fecha Decreto</b>',
							 'valunitri'=>'<b>Valor U.T.</b>'
							  );
		$la_columnas=array('codigo'=>'',
						   'anno'=>'',
						   'fecentvig'=>'',
						   'gacofi'=>'',
						   'fecpubgac'=>'',
						   'decnro'=>'',
						   'fecdec'=>'',
						   'valunitri'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 14,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,						 
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'anno'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'fecentvig'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'gacofi'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'fecpubgac'=>array('justification'=>'center','width'=>70),
									   'decnro'=>array('justification'=>'center','width'=>80),
									   'fecdec'=>array('justification'=>'center','width'=>70),
									   'valunitri'=>array('justification'=>'center','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	
		$io_pdf->restoreState();
	    $io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
    }// end function uf_print_encabezado_pagina
	 //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
 	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte.
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 11/02/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(640);
		$la_columnas=array('codigo'=>'',
						   'anno'=>'',
						   'fecentvig'=>'',
						   'gacofi'=>'',
						   'fecpubgac'=>'',
						   'decnro'=>'',
						   'fecdec'=>'',
						   'valunitri'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'anno'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'fecentvig'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'gacofi'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'fecpubgac'=>array('justification'=>'center','width'=>70),
									   'decnro'=>array('justification'=>'center','width'=>80),
									   'fecdec'=>array('justification'=>'center','width'=>70),
									   'valunitri'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
    require_once("../../shared/ezpdf/class.ezpdf.php");	
	require_once("sigesp_cfg_class_report.php");
	$io_report=new sigesp_cfg_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
       $ls_titulo="<b>LISTADO DE UNIDADES TRIBUTARIAS</b>"; 

	//-----------------------------------------------------------------------------------------------------------------------------------
	global $la_data;
	$lb_valido=$io_report->uf_select_unidad_tributaria(); // Cargar el DS con los datos del reporte
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
			 $io_pdf->ezSetCmMargins(4,3,3,3); // Configuración de los margenes en centímetros
			 $io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página
			
			
			$li_totrow=$io_report->dts_reporte->getRowCount("codunitri"); 
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_codigo    =$io_report->dts_reporte->data["codunitri"][$li_i];
				$ls_anno      =$io_report->dts_reporte->data["anno"][$li_i];
				$ls_fecentvig =$io_report->dts_reporte->data["fecentvig"][$li_i];
				$ls_gacofi    =$io_report->dts_reporte->data["gacofi"][$li_i];
				$ls_fecpubgac =$io_report->dts_reporte->data["fecpubgac"][$li_i];
				$ls_decnro    =$io_report->dts_reporte->data["decnro"][$li_i];
				$ls_fecdec    =$io_report->dts_reporte->data["fecdec"][$li_i];
				$ls_valunitri =$io_report->dts_reporte->data["valunitri"][$li_i];
				$ls_valunitri =number_format($ls_valunitri,3,",",".");							   	
				$ls_fecentvig=$io_funciones->uf_formatovalidofecha($ls_fecentvig);
				$ls_fecentvig=$io_funciones->uf_convertirfecmostrar($ls_fecentvig);
				$ls_fecpubgac=$io_funciones->uf_formatovalidofecha($ls_fecpubgac);
				$ls_fecpubgac=$io_funciones->uf_convertirfecmostrar($ls_fecpubgac);
				$ls_fecdec=$io_funciones->uf_formatovalidofecha($ls_fecdec);
				$ls_fecdec=$io_funciones->uf_convertirfecmostrar($ls_fecdec);
				
				$la_data[$li_i]=array('codigo'=>$ls_codigo,'anno'=>$ls_anno,'fecentvig'=>$ls_fecentvig,
				                      'gacofi'=>$ls_gacofi,'fecpubgac'=>$ls_fecpubgac,'decnro'=>$ls_decnro,'fecdec'=>$ls_fecdec,
									  'valunitri'=>$ls_valunitri);
			}
			uf_print_encabezado_pagina($ls_titulo,$io_pdf);
			uf_print_detalle($la_data,$io_pdf);
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


