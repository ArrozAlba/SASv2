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
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,10,$as_titulo); // Agregar el título
		
		$io_pdf->addText(500,730,9,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(500,720,9,date("h:i a")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_comprobante,$as_descripcion,$adt_fecha,$as_proc,$as_programatica,&$io_pdf)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_programatica // programatica del comprobante
		//	    		   as_denestpro5 // denominacion de la programatica del comprobante
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>Comprobante</b> '.$as_comprobante.'  -----  '.$as_descripcion.'  ------  '.$adt_fecha.'  --------  '.$as_proc.''),
		               array('name'=>'<b>Programatica</b> '.$as_programatica.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'fontSize' => 7, // Tamaño de Letras
						 'shadeCol'=>array(0.9,0.9,0.9),
						 'xOrientation'=>'center', // Orientación de la tabla
						 'colGap'=>4, // separacion entre tablas
						 'xPos'=>305, // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
    
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $io_pdf->ezSetDy(-1);
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>4, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>110), // Justificación y ancho de la columna
						 			   'documento'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
						 			   'descripcion'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna
						 			   'aumento'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
									   'disminucion'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		
		$la_columnas=array('cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominacion</b>',
						   'documento'=>'<b>Documento</b>',
						   'descripcion'=>'<b>Descripción</b>',
						   'aumento'=>'<b>Aumento</b>',
						   'disminucion'=>'<b>Disminución</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_totalaumento,$ad_totaldismi,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private 
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por : Ing. Yesenia Moreno
		// Fecha Creación : 18/02/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('total'=>'<b>Total</b>','aumento'=>$ad_totalaumento,'disminucion'=>$ad_totaldismi));
		$la_columna=array('total'=>'','aumento'=>'','disminucion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho Máximo de la tabla
						 'fontSize' => 7, // Tamaño de Letras
						 'colGap'=>4, // separacion entre tablas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>305, // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>370), // Justificación y ancho de la columna
						 			   'aumento'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
									   'disminucion'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
						 			  
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
        $io_pdf->ezSetDy(-30);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("sigesp_spg_reporte.php");
		$io_report = new sigesp_spg_reporte();
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();			
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
		require_once("sigesp_spg_funciones_reportes.php");
		$io_function_report = new sigesp_spg_funciones_reportes();
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
        $ls_ckbrect=$_GET["ckbrect"];
        $ls_ckbtras=$_GET["ckbtras"];
        $ls_ckbinsu=$_GET["ckbinsu"];
        $ls_ckbcre=$_GET["ckbcre"];
		$ls_comprobante  = $_GET["txtcomprobante"];
		$ls_procede  = $_GET["txtprocede"];
		$ldt_fecha  = $_GET["txtfecha"];

		$fecdes=$_GET["txtfecdes"];
		$ldt_fecdes=$io_funciones->uf_convertirdatetobd($fecdes);

		$fechas=$_GET["txtfechas"];
		$ldt_fechas=$io_funciones->uf_convertirdatetobd($fechas);
		$ldt_fecha=$io_funciones->uf_convertirdatetobd($ldt_fecha);
		/////////////////////////////////         SEGURIDAD               ///////////////////////////////////
		$ls_desc_event="Solicitud de Reporte Modificaciones Presupuestarias No Aprobadas desde la fecha ".$fecdes." hasta ".$fechas.", Comprobante  ".$ls_comprobante." ,Procede  ".$ls_procede." ,Fecha del Comprobante  ".$ldt_fecha;
		$io_function_report->uf_load_seguridad_reporte("SPG","sigesp_spg_r_modificaciones_presupuestarias.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               ///////////////////////////////////
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
		$ls_titulo=" <b>MODIFICACIONES PRESUPUESTARIAS NO APROBADAS</b> ";       
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
     $lb_valido=$io_report->uf_spg_reporte_modificaciones_presupuestarias_no_aprobadas($ls_ckbrect,$ls_ckbtras,$ls_ckbinsu,
	                                                                                   $ls_ckbcre,$ldt_fecdes,$ldt_fechas,
																					   $ls_comprobante,$ls_procede,$ldt_fecha);
 
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
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$io_report->dts_reporte->group("comprobante");
		$li_tot=$io_report->dts_reporte->getRowCount("spg_cuenta");
		$ld_totalaumento=0;
		$ld_totaldismi=0;
		for($z=1;$z<=$li_tot;$z++)
		{
		    $li_tmp=($z+1);
			$io_pdf->transaction('start'); // Iniciamos la transacción
			$thisPageNum=$io_pdf->ezPageCount;
			$ls_comprobante=$io_report->dts_reporte->data["comprobante"][$z];
		    if ($z<$li_tot)
		    {
				$ls_comprobante_next=$io_report->dts_reporte->data["comprobante"][$li_tmp];     
		    }
		    elseif($z=$li_tot)
		    {
				$ls_comprobante_next='no_next';
		    }
			if(!empty($ls_comprobante))
			{
			  $ls_comprobante_ant=$io_report->dts_reporte->data["comprobante"][$z];
			}
			$ls_descripcion=trim($io_report->dts_reporte->data["cmp_descripcion"][$z]);
			$ls_programatica=$io_report->dts_reporte->data["programatica"][$z];
			$ls_spg_cuenta=$io_report->dts_reporte->data["spg_cuenta"][$z];
			$ls_denominacion=trim($io_report->dts_reporte->data["denominacion"][$z]);
			$ls_documento=$io_report->dts_reporte->data["documento"][$z];
			$ldt_fecha_bd=$io_report->dts_reporte->data["fecha"][$z];
			$ldt_fecha=$io_funciones->uf_convertirfecmostrar($ldt_fecha_bd);
			$ld_aumento=$io_report->dts_reporte->data["aumento"][$z];
			$ld_disminucion=$io_report->dts_reporte->data["disminucion"][$z];
			$ls_doc_autor=$io_report->dts_reporte->data["doc_autor"][$z];
			$ls_autorizante=$io_report->dts_reporte->data["autorizante"][$z];
			$ldt_fecha_aut=$io_report->dts_reporte->data["fecha_aut"][$z];
			$ls_observacion=$io_report->dts_reporte->data["observacion"][$z];
			$ls_procede=$io_report->dts_reporte->data["procede"][$z];
			if($ls_procede=="SPGREC")
			{
			   $ls_proc="RECTIFICACIONES";
			}
			if($ls_procede=="SPGINS")
			{
			   $ls_proc="INSUBSISTENCIAS";
			}
			if($ls_procede=="SPGTRA")
			{
			   $ls_proc="TRASPASOS";
			}
			if($ls_procede=="SPGCRA")
			{
			   $ls_proc="CREDITOS/INGRESOS ADICIONALES";
			}
		    $ld_totalaumento=$ld_totalaumento+$ld_aumento;
		    $ld_totaldismi=$ld_totaldismi+$ld_disminucion;
			
			if (!empty($ls_comprobante))
		    {
				$ld_aumento=number_format($ld_aumento,2,",",".");
				$ld_disminucion=number_format($ld_disminucion,2,",",".");
			   
				$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,'documento'=>$ls_documento,
								   'descripcion'=>$ls_descripcion,'aumento'=>$ld_aumento,'disminucion'=>$ld_disminucion);
			   
				$ld_aumento=str_replace('.','',$ld_aumento);
				$ld_aumento=str_replace(',','.',$ld_aumento);		
				$ld_disminucion=str_replace('.','',$ld_disminucion);
				$ld_disminucion=str_replace(',','.',$ld_disminucion);		
			}
			else
			{
				$ld_aumento=number_format($ld_aumento,2,",",".");
				$ld_disminucion=number_format($ld_disminucion,2,",",".");
				$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,'documento'=>$ls_documento,
								   'descripcion'=>$ls_descripcion,'aumento'=>$ld_aumento,'disminucion'=>$ld_disminucion);
				$ld_aumento=str_replace('.','',$ld_aumento);
				$ld_aumento=str_replace(',','.',$ld_aumento);		
				$ld_disminucion=str_replace('.','',$ld_disminucion);
				$ld_disminucion=str_replace(',','.',$ld_disminucion);		
			}
			if (!empty($ls_comprobante_next))
			{
				$ld_aumento=number_format($ld_aumento,2,",",".");
				$ld_disminucion=number_format($ld_disminucion,2,",",".");
				$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,'documento'=>$ls_documento,
								   'descripcion'=>$ls_descripcion,'aumento'=>$ld_aumento,'disminucion'=>$ld_disminucion);
                uf_print_cabecera($ls_comprobante_ant,$ls_descripcion,$ldt_fecha,$ls_proc,$ls_programatica,$io_pdf);
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				$ld_totalaumento=number_format($ld_totalaumento,2,",",".");
				$ld_totaldismi=number_format($ld_totaldismi,2,",",".");
				uf_print_pie_cabecera($ld_totalaumento,$ld_totaldismi,$io_pdf);				
				$ld_totalaum=$ld_totalaumento;
				$ld_totaldis=$ld_totaldismi;
				$ld_totalaumento=0;
				$ld_totaldismi=0;
				if ($io_pdf->ezPageCount==$thisPageNum)
				{// Hacemos el commit de los registros que se desean imprimir
					$io_pdf->transaction('commit');
				}
   			    elseif($thisPageNum<>1)
				{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
					$io_pdf->transaction('rewind');
					$io_pdf->ezNewPage(); // Insertar una nueva página
					uf_print_cabecera($ls_comprobante_ant,$ls_descripcion,$ldt_fecha,$ls_proc,$ls_programatica,$io_pdf);
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					uf_print_pie_cabecera($ld_totalaum,$ld_totaldis,$io_pdf);				
					$ld_totalaum=0;
					$ld_totaldis=0;
				}
			    unset($la_data);			
			}//if
	    }//for
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
?> 
