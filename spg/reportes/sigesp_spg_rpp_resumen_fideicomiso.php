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
	ini_set('memory_limit','1024M');
	ini_set('max_execution_time ','0');
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 21/09/2007
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,10,$as_titulo); // Agregar el título
		
		$io_pdf->addText(500,730,9,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(500,720,9,date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 21/09/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetY(710);
		$la_data=array(array('spg_cuenta'=>'<b>Codigo</b>','denominacion'=>'<b>Denominación</b>','monto'=>'<b>Monto</b>'));
		$la_columna=array('spg_cuenta'=>'','denominacion'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'cols'=>array('spg_cuenta'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la 
						 			   'denominacion'=>array('justification'=>'center','width'=>280), // Justificación y ancho de la 
						 			   'monto'=>array('justification'=>'center','width'=>120))); // Justificación y ancho de la 
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 21/09/2007
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$io_pdf->ezSetDy(-1);
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'cols'=>array('spg_cuenta'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la 
						 			   'denominacion'=>array('justification'=>'left','width'=>280), // Justificación y ancho de la 
						 			   'monto'=>array('justification'=>'right','width'=>120))); // Justificación y ancho de la 
		$la_columnas=array('spg_cuenta'=>'<b>Codigo</b>',
						   'denominacion'=>'<b>Denominacion</b>',
						   'monto'=>'<b>Monto</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_totalcomprometer,$ad_totalcausado,$ad_totalpagado,$ad_totaldeuda,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 22/09/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_datat=array(array('name'=>'___________________________________________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>560); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_datat,$la_columna,'',$la_config);
		
		$la_data[]=array('codigo'=>'','provbene'=>'<b>Total</b> ','comprometido'=>$ad_totalcomprometer,
		                 'causado'=>$ad_totalcausado,'pagado'=>$ad_totalpagado,'deuda'=>$ad_totaldeuda);
		$la_columnas=array('codigo'=>'','provbene'=>'','comprometido'=>'','causado'=>'','pagado'=>'','deuda'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>2, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la 
						 			   'provbene'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la 
						 			   'comprometido'=>array('justification'=>'right','width'=>85), // Justificación 
						 			   'causado'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la 
									   'pagado'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la 
									   'deuda'=>array('justification'=>'right','width'=>85))); // Justificación y ancho de la 
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
//--------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("sigesp_spg_reportes_class.php");
		$io_report = new sigesp_spg_reportes_class();
		require_once("sigesp_spg_funciones_reportes.php");
		$io_function_report = new sigesp_spg_funciones_reportes();
        require_once("../../shared/class_folder/class_funciones.php");
		$io_function=new class_funciones() ;
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
//--------------------------------------------------  Parámetros para Filtar el Reporte  --------------------------------------
	  $ls_codfuefindes = $_GET["txtcodfuefindes"];
  	//  $ls_codfuefinhas = $_GET["txtcodfuefinhas"];

	 /////////////////////////////////         SEGURIDAD               ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_desc_event=" Resumen Fideicomiso ".$ls_codfuefindes;
	 $io_function_report->uf_load_seguridad_reporte("SPG","sigesp_spg_r_resumen_fideicomiso.php",$ls_desc_event);
	////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//----------------------------------------------------  Parámetros del encabezado  --------------------------------------------------------------------------------------------------------------------------------------
		$ls_denfuefin="";
		$lb_valido=$io_report->uf_select_denominacion_fuentefideicomiso($ls_codfuefindes,&$ls_denfuefin);
		$ls_titulo="<b>CUADRO RESUMEN  ".$ls_denfuefin."</b>"; 
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
     $lb_valido=$io_report->uf_spg_reporte_select_resumen_fideicomiso($ls_codfuefindes);
	 if($lb_valido==false) // Existe algún error ó no hay registros
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		//print(" close();");
		print("</script>");
	 }
	 else // Imprimimos el reporte
	 {
	    error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.4,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_tot=$io_report->dts_reporte->getRowCount("spg_cuenta");
		for($z=1;$z<=$li_tot;$z++)
		{
			$io_pdf->transaction('start'); // Iniciamos la transacción
			$thisPageNum=$io_pdf->ezPageCount;
			$ls_spg_cuenta=trim($io_report->dts_reporte->data["spg_cuenta"][$z]);
			$ls_denominacion=trim($io_report->dts_reporte->data["denominacion"][$z]);
			$ld_monto=$io_report->dts_reporte->data["monto"][$z];  
		   
		    $ld_monto=number_format($ld_monto,2,",",".");
			  
			$la_data[$z]=array('spg_cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,'monto'=>$ld_monto);
			//print_r($la_data);
			  
			$ld_monto=str_replace('.','',$ld_monto);
			$ld_monto=str_replace(',','.',$ld_monto);	
	    }//for
		uf_print_cabecera($io_pdf); // Imprimimos el cabecera detalle 
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		// $ld_sub_total_deuda=number_format($ld_sub_total_deuda,2,",",".");
		 //uf_print_pie_cabecera($ld_sub_total_comprometer,$ld_sub_total_causado,$ld_sub_total_pagado,
			//				   $ld_sub_total_deuda,$io_pdf);	
		 if ($io_pdf->ezPageCount==$thisPageNum)
		 {// Hacemos el commit de los registros que se desean imprimir
			$io_pdf->transaction('commit');
		 }
		 elseif($thisPageNum<>1)
		 {// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
			$io_pdf->transaction('rewind');
			$io_pdf->ezNewPage(); // Insertar una nueva página
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
			/*$ld_subtotal_comprometer=number_format($ld_subtotal_comprometer,2,",",".");
			$ld_subtotal_causado=number_format($ld_subtotal_causado,2,",",".");
			$ld_subtotal_pagado=number_format($ld_subtotal_pagado,2,",",".");
			$ld_subtotal_deuda=number_format($ld_subtotal_deuda,2,",",".");
			uf_print_pie_cabecera($ld_subtotal_comprometer,$ld_subtotal_causado,$ld_subtotal_pagado,
								  $ld_subtotal_deuda,$io_pdf);	*/
		 }
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
	unset($io_function_report);
	unset($io_fecha);
?> 