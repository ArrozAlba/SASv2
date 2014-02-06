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
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 01/04/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		//$io_pdf->addJpegFromFile('../../shared/imagebank/logo_hci.jpg',40,715,550,50); // Agregar Logo
	    $io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],40,715,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,695,10,$as_titulo); // Agregar el título
		
		$io_pdf->rectangle(40,60,550,120);
		$io_pdf->line(40,160,590,160);	// HORIZONTAL	
		$io_pdf->line(40,120,450,120);	// HORIZONTAL	
		$io_pdf->line(40,100,450,100);	// HORIZONTAL	
		$io_pdf->line(250,60,250,180);	// VERTICAL	
		$io_pdf->line(450,60,450,180);	// VERTICAL	
		$io_pdf->addText(100,165,8,"UNIDAD SOLICITANTE"); // Agregar el t?ulo				
		$io_pdf->addText(260,165,8,"OFICINA DE PLANIFICACION Y PRESUPUESTO"); // Agregar el t?ulo
		$io_pdf->addText(490,165,8,"PRESIDENCIA"); // Agregar el t?ulo
		$io_pdf->addText(100,105,8,"UNIDAD EJECUTORA"); // Agregar el t?ulo
		$io_pdf->addText(300,105,8,"DIRECCION GENERAL"); // Agregar el t?ulo		
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($io_encabezado,$as_comprobante,$as_descripcion,$adt_fecha,$as_proc,$as_programatica,&$io_pdf)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_programatica // programatica del comprobante
		//	    		   as_denestpro5 // denominacion de la programatica del comprobante
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 01/04/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(690);
		$io_pdf->saveState();
		$la_datatitulos= array(array('tipo'=>"<b>TIPO DE MODIFICACION</b>",'fecha'=>"<b>FECHA</b>",'numero'=>"<b>NUMERO</b>"));
				
		$la_columna=array('tipo'=>'<b>TIPO DE MODIFICACION</b>',
						  'fecha'=>'<b>FECHA</b>',
						  'numero'=>'<b>NUMERO</b>');
						  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>320, // Orientación de la tabla
						 'cols'=>array('tipo'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>170), // Justificación y ancho de la columna
									   'numero'=>array('justification'=>'center','width'=>180))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatitulos,$la_columna,'',$la_config);
		unset($la_colunma);
		unset($la_config);
		$la_columna=array('tipo'=>'<b>TIPO DE MODIFICACION</b>',
						  'fecha'=>'<b>FECHA</b>',
						  'numero'=>'<b>NUMERO</b>');
						  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>320, // Orientación de la tabla
						 'cols'=>array('tipo'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>170), // Justificación y ancho de la columna
									   'numero'=>array('justification'=>'center','width'=>180))); // Justificación y ancho de la columna
		$la_data=array(array('tipo'=>$as_proc,'fecha'=>$adt_fecha,'numero'=>$as_comprobante));
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
    
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabezera_detalle(&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabezera_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 01/04/2007 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$la_datatitulos= array(array('cuenta'=>"<b>CODIGO</b>",'denominacion'=>"<b>DENOMINACION</b>",
		                             'disminucion'=>"<b>CEDENTE</b>",'aumento'=>"<b>RECEPTORA</b>"));
				
		$la_columna=array('cuenta'=>'<b>CODIGO</b>',
						  'denominacion'=>'<b>DENOMINACION</b>',
						  'disminucion'=>'<b>CEDENTE</b>',
						  'aumento'=>'<b>RECEPTORA</b>');
						  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>320, // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'center','width'=>170), // Justificación y ancho de la columna
						 			   'disminucion'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
									   'aumento'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatitulos,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabezera_detalle
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
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 01/04/2007 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetCmMargins(5,6.5,3,3); // Configuración de los margenes en centímetros
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>320, // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>170), // Justificación y ancho de la columna
						 			   'disminucion'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
									   'aumento'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		
		$la_columnas=array('cuenta'=>'<b>CODIGO</b>',
						   'denominacion'=>'<b>DENOMINACION</b>',
						   'disminucion'=>'<b>CEDENTE</b>',
						   'aumento'=>'<b>RECEPTORA</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$io_pdf->ezSetCmMargins(5,3,3,3); // Configuración de los margenes en centímetros
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
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 01/04/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('total'=>'<b>Total</b>','disminucion'=>$ad_totaldismi,'aumento'=>$ad_totalaumento));
		$la_columna=array('total'=>'','disminucion'=>'','aumento'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Sombra entre líneas
						 'width'=>550, // Ancho Máximo de la tabla
						 'fontSize' => 9, // Tamaño de Letras
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>320, // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>370), // Justificación y ancho de la columna
						 			   'disminucion'=>array('justification'=>'right','width'=>90),  // Justificación y ancho de la columna
									   'aumento'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
						 			  
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
		
		$li_estmodest   = $_SESSION["la_empresa"]["estmodest"];
		
		/////////////////////////////////         SEGURIDAD               ///////////////////////////////////
		$ls_desc_event="Solicitud de Reporte Modificaciones Presupuestarias Aprobadas desde la fecha ".$fecdes." hasta ".$fechas.", Comprobante  ".$ls_comprobante." ,Procede  ".$ls_procede." ,Fecha del Comprobante  ".$ldt_fecha;
		$io_function_report->uf_load_seguridad_reporte("SPG","sigesp_spg_r_modificaciones_presupuestarias_aprobadas.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               ///////////////////////////////////
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
		$ls_titulo=" <b>MODIFICACIONES PRESUPUESTARIAS APROBADAS</b> ";       
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
     $lb_valido=$io_report->uf_spg_reporte_modificaciones_presupuestarias($ls_ckbrect,$ls_ckbtras,$ls_ckbinsu,$ls_ckbcre,
	                                                                      $ldt_fecdes,$ldt_fechas,$ls_comprobante,$ls_procede,
																		  $ldt_fecha);
 
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
		//$io_pdf->ezSetCmMargins(4,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,8,'','',1); // Insertar el número de página
		$io_report->dts_reporte->group_noorder("procomp");
		$li_tot=$io_report->dts_reporte->getRowCount("spg_cuenta");
		$ld_totalaumento=0;
		$ld_totaldismi=0;
		$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
		$ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
		$ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
		$ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
		$ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
		for($z=1;$z<=$li_tot;$z++)
		{
		    $li_tmp=($z+1);
			$io_pdf->transaction('start'); // Iniciamos la transacción
			$thisPageNum=$io_pdf->ezPageCount;
			$ls_procede=$io_report->dts_reporte->data["procede"][$z];
			$ls_procomp=$io_report->dts_reporte->data["procomp"][$z];
			$ls_comprobante=$io_report->dts_reporte->data["comprobante"][$z];
			
			if ($z<$li_tot)
		    {
				$ls_procomp_next=$io_report->dts_reporte->data["procomp"][$li_tmp];     
		    }
		    elseif($z=$li_tot)
		    {
				$ls_procomp_next='no_next';
		    }
			if(!empty($ls_procomp))
			{
			  $ls_procomp_ant=$io_report->dts_reporte->data["procomp"][$z];
			}
			
			$ls_descripcion=trim($io_report->dts_reporte->data["cmp_descripcion"][$z]);
			$ls_programatica=$io_report->dts_reporte->data["programatica"][$z];
			$ls_spg_cuenta=$io_report->dts_reporte->data["spg_cuenta"][$z];
			$ls_spg_cuenta=substr($ls_spg_cuenta,0,9);
			if ($li_estmodest=='1')
			{
			  $ls_codestpro1 = substr($ls_programatica,0,25);
			  $ls_codestpro2 = substr($ls_programatica,25,25);
			  $ls_codestpro3 = substr($ls_programatica,50,25);
			  $ls_codestpro=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3);
			}
		    else
			{
			  $ls_codestpro1 = substr($ls_programatica,0,25);
			  $ls_codestpro2 = substr($ls_programatica,25,25);
			  $ls_codestpro3 = substr($ls_programatica,50,25);
			  $ls_codestpro4 = substr($ls_programatica,75,25);
			  $ls_codestpro5 = substr($ls_programatica,100,25);
			  $ls_codestpro=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3)."-".substr($ls_codestpro4,-$ls_loncodestpro4)."-".substr($ls_codestpro5,-$ls_loncodestpro5);
			}
			$ls_codestpro1=substr($ls_codestpro1,-$ls_loncodestpro1);
			$ls_codestpro2=substr($ls_codestpro2,-$ls_loncodestpro2);
			
			$ls_spg_cuenta=$ls_codestpro1."-".$ls_codestpro2."-".$ls_spg_cuenta;
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
			
			if (!empty($ls_procomp))
		    {
				$ld_aumento=number_format($ld_aumento,2,",",".");
				$ld_disminucion=number_format($ld_disminucion,2,",",".");
			   
				$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,
				                   'disminucion'=>$ld_disminucion,'aumento'=>$ld_aumento);
			   
				$ld_aumento=str_replace('.','',$ld_aumento);
				$ld_aumento=str_replace(',','.',$ld_aumento);		
				$ld_disminucion=str_replace('.','',$ld_disminucion);
				$ld_disminucion=str_replace(',','.',$ld_disminucion);		
			}
			else
			{
				$ld_aumento=number_format($ld_aumento,2,",",".");
				$ld_disminucion=number_format($ld_disminucion,2,",",".");
				$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,
				                   'disminucion'=>$ld_disminucion,'aumento'=>$ld_aumento);
				$ld_aumento=str_replace('.','',$ld_aumento);
				$ld_aumento=str_replace(',','.',$ld_aumento);		
				$ld_disminucion=str_replace('.','',$ld_disminucion);
				$ld_disminucion=str_replace(',','.',$ld_disminucion);		
			}
			if (!empty($ls_procomp_next))
			{
				$ld_aumento=number_format($ld_aumento,2,",",".");
				$ld_disminucion=number_format($ld_disminucion,2,",",".");
				$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,
				                   'disminucion'=>$ld_disminucion,'aumento'=>$ld_aumento);
                $io_encabezado=$io_pdf->openObject();
				uf_print_cabecera($io_encabezado,$ls_comprobante,$ls_descripcion,$ldt_fecha,$ls_proc,$ls_programatica,$io_pdf);
				uf_print_cabezera_detalle($io_pdf);
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				$ld_totalaumento=number_format($ld_totalaumento,2,",",".");
				$ld_totaldismi=number_format($ld_totaldismi,2,",",".");
				uf_print_pie_cabecera($ld_totalaumento,$ld_totaldismi,$io_pdf);				
				$ld_totalaum=$ld_totalaumento;
				$ld_totaldis=$ld_totaldismi;
				$ld_totalaumento=0;
				$ld_totaldismi=0;
			    $io_pdf->stopObject($io_encabezado);
				if($z<$li_tot)
				{
				   $io_pdf->ezNewPage(); // Insertar una nueva página
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
