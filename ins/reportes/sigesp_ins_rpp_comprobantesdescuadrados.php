<?PHP
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
	function uf_insert_seguridad($as_titulo)
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
		global $io_fun_ins;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_ins->uf_load_seguridad_reporte("INS","sigesp_ins_r_solicitudpago.php",$ls_descripcion);
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
		//	    		   ad_fecregdes // Inicio del Intervalo de Fecha del Reporte
		//	    		   ad_fecreghas // Fin del Intervalo de Fecha del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 08/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->rectangle(25,690,550,80);
		$io_pdf->line(15,40,585,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,695,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,720,11,$as_titulo); // Agregar el título
		$io_pdf->addText(535,780,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(541,773,7,date("h:i a")); // Agregar la Hora
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
		$io_pdf->ezSetDy(-2);
		$la_data[1]=array('procede'=>"<b>Procede</b>",'comprobante'=>"<b>Comprobante</b>",'fecha'=>"<b>Fecha</b>",'debe'=>"<b>Debe</b>",'haber'=>"<b>Haber</b>");
		$la_columnas=array('procede'=>'<b>Solicitud</b>',
						   'comprobante'=>'<b>Proveedor / Beneficiario</b>',
						   'fecha'=>'<b>Fecha Emisión</b>',
						   'debe'=>'<b>Fecha Emisión</b>',
						   'haber'=>'<b>Monto</b>');
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
						 'cols'=>array('procede'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'comprobante'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'debe'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'haber'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$la_columnas=array('procede'=>'<b>Solicitud</b>',
						   'comprobante'=>'<b>Proveedor / Beneficiario</b>',
						   'fecha'=>'<b>Fecha Emisión</b>',
						   'debe'=>'<b>Fecha Emisión</b>',
						   'haber'=>'<b>Monto</b>');
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
						 'cols'=>array('procede'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'comprobante'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_ins_class_report.php");
	$io_report=new sigesp_ins_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	/*require_once("../class_folder/class_funciones_ins.php");
	$io_fun_ins=new class_funciones_ins("../../");*/
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	if(array_key_exists("procede",$_GET))
	{$ls_procede=$_GET["procede"];}
	else
	{$ls_procede="";}
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>Listado de Comprobantes Descuadrados</b>";
	//--------------------------------------------------------------------------------------------------------------------------------
	//$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_select_comprobantes($ls_procede); // Cargar el DS con los datos del reporte
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
		$io_pdf->ezStartPageNumbers(750,50,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->ds->getRowCount("procede");
		$li_z=0;
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_procede=$io_report->ds->data["procede"][$li_i];
			$ls_comprobante=$io_report->ds->data["comprobante"][$li_i];
			$ld_fecha=$io_report->ds->data["fecha"][$li_i];
			$ls_codban=$io_report->ds->data["codban"][$li_i];
			$ls_ctaban=$io_report->ds->data["ctaban"][$li_i];
			$lb_valido=$io_report->uf_select_detalles($ls_procede,$ls_comprobante,$ld_fecha,$ls_codban,$ls_ctaban);
			if($lb_valido)
			{ 
				$li_totrowdet=$io_report->ds_detalle->getRowCount("procede");
				$li_montodeb=0;
				$li_montohab=0;
				for($li_j=1;(($li_j<=$li_totrowdet)&&($lb_valido));$li_j++)
				{   
					$ls_debhab=$io_report->ds_detalle->data["debhab"][$li_j];
					$li_monto=$io_report->ds_detalle->data["monto"][$li_j];
					if($ls_debhab=="D")
					{
						$li_montodeb=$li_montodeb + $li_monto;
					}
					else
					{
						$li_montohab=$li_montohab + $li_monto;
					}
				}				
				if($li_montodeb!=$li_montohab)
				{
					$li_z++;
					$ld_fecha=$io_funciones->uf_convertirfecmostrar($ld_fecha);
					$li_montodeb=number_format($li_montodeb,2,',','.');
					$li_montohab=number_format($li_montohab,2,',','.');
					$la_data[$li_z]=array('procede'=>$ls_procede,'comprobante'=>$ls_comprobante,'fecha'=>$ld_fecha,
										  'debe'=>$li_montodeb,'haber'=>$li_montohab);
				}
			
			}
		}
		if ($li_z>0)
		{
		 	 uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		  	 unset($la_data);
			 $io_report->ds->resetds("procede");
			 $io_report->ds_detalle->resetds("procede");
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
		}
		else
		{
		    print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");	
		    unset($io_pdf);
		}		
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_ins);
?> 