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
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 20/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],38,730,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,14,$as_titulo); // Agregar el título
		
		$io_pdf->addText(500,730,9,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(500,720,9,date("h:i a")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($io_cabecera,$as_comprobante,$as_procede,$adt_fecha,$as_nomprobene,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_programatica // programatica del comprobante
		//	    		   as_denestpro5 // denominacion de la programatica del comprobante
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 20/09/2006 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSetY(700);
		$la_data=array(array('name'=>'<b>COMPROMISO    </b>  '.$as_procede.' --- '.$as_comprobante.' --- '.$adt_fecha.' '),
		               array('name'=>'<b>BENEFICIARIO  </b> '.$as_nomprobene.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'fontSize' => 8, // Tamaño de Letras
						 'colGap'=>1, // separacion entre tablas
						 'shadeCol'=>array(0.9,0.9,0.9),
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>302, // Orientación de la tabla
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
	}// end function uf_print_cabecera
//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_cuenta($io_cabecera,$as_spg_cuenta,$as_programatica,$as_denestpro,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_programatica // programatica del comprobante
		//	    		   as_denestpro5 // denominacion de la programatica del comprobante
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 20/09/2006 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		//$io_pdf->ezSetY(650);
		$la_data=array(array('name'=>'<b>CUENTA    </b>  '.$as_spg_cuenta.''),
		               array('name'=>'<b>PROGRAMATICA  </b>  '.$as_programatica.''),
					   array('name'=>'<b></b> '.$as_denestpro.''),);
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'fontSize' => 8, // Tamaño de Letras
						 'colGap'=>1, // separacion entre tablas
						 'shadeCol'=>array(0.9,0.9,0.9),
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>302, // Orientación de la tabla
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
	}// end function uf_print_cabecera
//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_detalle($io_encabezado,&$io_pdf)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 20/09/2006 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
        //$io_pdf->ezSetDy(-0.5);
		$la_data=array(array('comprobante'=>'<b>Comprobante</b>','fecha'=>'<b>Fecha</b>','comprometido'=>'<b>Comprometido</b>',
		                     'causado'=>'<b>Causado</b>','pagado'=>'<b>pagado</b>'));
		$la_columnas=array('comprobante'=>'','fecha'=>'','comprometido'=>'','causado'=>'','pagado'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xPos'=>302, // Orientación de la tabla
						 'cols'=>array('comprobante'=>array('justification'=>'center','width'=>150), // Justificación y ancho  
						 			   'fecha'=>array('justification'=>'center','width'=>80), // Justificación y ancho 
						 			   'comprometido'=>array('justification'=>'center','width'=>100), // Justificación y ancho  
						 			   'causado'=>array('justification'=>'center','width'=>100), // Justificación y ancho 
									   'pagado'=>array('justification'=>'center','width'=>100))); // Justificación y ancho  
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabecera_detalle
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
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 20/09/2006 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xPos'=>302, // Orientación de la tabla
						 'cols'=>array('comprobante'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la 
						 			   'fecha'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la 
						 			   'comprometido'=>array('justification'=>'right','width'=>100), // Justificación 
						 			   'causado'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la 
									   'pagado'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la 
		$la_columnas=array('comprobante'=>'<b>Comprobante</b>',
						   'fecha'=>'<b>Fecha</b>',
						   'comprometido'=>'<b>Comprometido</b>',
						   'causado'=>'<b>Causado</b>',
						   'pagado'=>'<b>Pagado</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total_programatica($ad_total_compromiso,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_total_programatica
		//		    Acess : private
		//	    Arguments : ad_totalprogramatica // Total Programatica
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 20/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $la_data=array(array('total'=>'<b>SubTotal </b>','monto'=>$ad_total_compromiso));
		$la_columna=array('total'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'fontSize' => 9, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xPos'=>302, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>415), // Justificación y ancho de la columna
						 	           'monto'=>array('justification'=>'right','width'=>115))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_total_programatica
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_total,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 20/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('total'=>'<b>Total</b>','monto'=>$ad_total));
		$la_columna=array('total'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'fontSize' => 9, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xPos'=>302, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>415), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>115))); // Justificación y ancho de la 

		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
//-----------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("sigesp_spg_reportes_class.php");
		$io_report = new sigesp_spg_reportes_class();
		require_once("sigesp_spg_funciones_reportes.php");
		$io_function_report = new sigesp_spg_funciones_reportes();
        require_once("../../shared/class_folder/class_funciones.php");
		$io_function=new class_funciones() ;
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
//--------------------------------------------------  Parámetros para Filtar el Reporte  -------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		$ls_comprobante  = $_GET["txtcomprobante"];
		$ls_procede  = $_GET["txtprocede"];
		$ldt_fecha  = $_GET["txtfecha"];
		$ldt_fecdes = $_GET["txtfecdes"];
		$ldt_fechas = $_GET["txtfechas"];	
//----------------------------------------------------  Parámetros del encabezado  --------------------------------------------
		$ls_titulo=" <b>EJECUCION DE COMPROMISOS</b> ";       
//------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
     $lb_valido=$io_report->uf_spg_select_reportes_ejecucion_compromiso($ls_procede,$ls_comprobante,$ldt_fecha,$ldt_fecdes,                                                                        $ldt_fechas);
 
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
		$io_pdf->ezSetCmMargins(4.4,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_tot=$io_report->dts_cab->getRowCount("programatica");
	    $ld_total_comprometer=0;
	    $ld_total_causado=0;
	    $ld_total_pagado=0;
		for($li_i=1;$li_i<=$li_tot;$li_i++)
		{
			$thisPageNum=$io_pdf->ezPageCount;
			$ls_programatica=$io_report->dts_cab->data["programatica"][$li_i];
		   
		    $ls_codestpro1=substr($ls_programatica,0,20);
		    $ls_denestpro1="";
		    $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro1($ls_codestpro1,$ls_denestpro1);
		    if($lb_valido)
		    {
			  $ls_denestpro1=$ls_denestpro1;
		    }
		    $ls_codestpro2=substr($ls_programatica,20,6);
		    if($lb_valido)
		    {
			  $ls_denestpro2="";
			  $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro2($ls_codestpro1,$ls_codestpro2,$ls_denestpro2);
			  $ls_denestpro2=$ls_denestpro2;
		    }
		    $ls_codestpro3=substr($ls_programatica,26,3);
		    if($lb_valido)
		    {
			  $ls_denestpro3="";
			  $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro3($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,                                                                               $ls_denestpro3);
			  $ls_denestpro3=$ls_denestpro3;
		    }
		    $ls_spg_cuenta=$io_report->dts_cab->data["spg_cuenta"][$li_i];
		    $ls_comprobante=$io_report->dts_cab->data["comprobante"][$li_i];
		    $ls_procede=$io_report->dts_cab->data["procede"][$li_i];
		    $ldt_fecha=$io_report->dts_cab->data["fecha"][$li_i];
			$ls_fecha=$io_function->uf_convertirfecmostrar($ldt_fecha);
			$ls_nombene=$io_report->dts_cab->data["nombene"][$li_i];
			$ls_nompro=$io_report->dts_cab->data["nompro"][$li_i];
			$ls_tipo_destino=$io_report->dts_cab->data["tipo_destino"][$li_i];
		    if($ls_tipo_destino=="P")
		    {
			    $ls_nomprobene=$ls_nompro;
		    }
			if($ls_tipo_destino=="B")
			{
				$ls_nomprobene=$ls_nombene;
			}
			if($ls_tipo_destino=="-")
			{
				$ls_nomprobene="";
			}
			$ls_denestpro=$ls_denestpro1." , ".$ls_denestpro2." , ".$ls_denestpro3;
		    
			$io_cabecera=$io_pdf->openObject();
            uf_print_cabecera($io_cabecera,$ls_comprobante,$ls_procede,$ls_fecha,$ls_nomprobene,$io_pdf);
            //uf_print_cabecera_cuenta($io_cabecera,$ls_spg_cuenta,$ls_programatica,$ls_denestpro,$io_pdf);			
		   
		    $lb_valido=$io_report->uf_spg_reportes_ejecucion_compromiso($ls_procede,$ls_comprobante,$ldt_fecha,$ldt_fecdes,                                                                        $ldt_fechas,$ls_spg_cuenta);
			$ld_sub_total_comprometer=0;
			$ld_sub_total_causado=0;
			$ld_sub_total_pagado=0;
			if($lb_valido)
			{
				$li_totrow_det=$io_report->dts_reporte->getRowCount("spg_cuenta");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
				  $li_tmp=($li_s+1);
				  $ls_spg_cuenta=$io_report->dts_reporte->data["spg_cuenta"][$li_s];
				  $ls_procede=$io_report->dts_reporte->data["procede"][$li_s];
				  $ls_comprobante=$io_report->dts_reporte->data["comprobante"][$li_s];
				  $ldt_fecha=$io_report->dts_reporte->data["fecha"][$li_s]; 
			      $ldt_fecha=$io_function->uf_convertirfecmostrar($ldt_fecha);
				   
				  $ld_comprometer=$io_report->dts_reporte->data["compromiso"][$li_s];  
				  $ld_causado=$io_report->dts_reporte->data["causado"][$li_s];  
				  $ld_pagado=$io_report->dts_reporte->data["pagado"][$li_s];  	  
				  $ls_proc_comp=$ls_procede."---".$ls_comprobante;
				  
				  $ld_total_comprometer=$ld_total_comprometer+$ld_comprometer;
				  $ld_total_causado=$ld_total_causado+$ld_causado;
				  $ld_total_pagado=$ld_total_pagado+$ld_pagado;
				  
				  $ld_sub_total_comprometer=$ld_sub_total_comprometer+$ld_comprometer;
				  $ld_sub_total_causado=$ld_sub_total_causado+$ld_causado;
				  $ld_sub_total_pagado=$ld_sub_total_pagado+$ld_pagado;
				  
				  $ld_comprometer=number_format($ld_comprometer,2,",",".");
				  $ld_causado=number_format($ld_causado,2,",",".");
				  $ld_pagado=number_format($ld_pagado,2,",",".");
				  
				  $la_data[$li_s]=array('comprobante'=>$ls_proc_comp,'fecha'=>$ldt_fecha,'comprometido'=>$ld_comprometer,
										'causado'=>$ld_causado,'pagado'=>$ld_pagado);
				  $ld_comprometer=str_replace('.','',$ld_comprometer);
				  $ld_comprometer=str_replace(',','.',$ld_comprometer);	
				  $ld_causado=str_replace('.','',$ld_causado);
				  $ld_causado=str_replace(',','.',$ld_causado);	
				  $ld_pagado=str_replace('.','',$ld_pagado);
				  $ld_pagado=str_replace(',','.',$ld_pagado);	
				}
				uf_print_cabecera_cuenta($io_cabecera,$ls_spg_cuenta,$ls_programatica,$ls_denestpro,$io_pdf);					                $io_encabezado=$io_pdf->openObject();
                uf_print_cabecera_detalle($io_encabezado,$io_pdf);
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
			    ///$ld_totalprogramatica=number_format($ld_totalprogramatica,2,",",".");
			    //uf_print_total_programatica($ld_totalprogramatica,$io_pdf); // Imprimimos el total programatica
				//$io_pdf->stopObject($io_encabezado);
			}
		     $io_pdf->stopObject($io_cabecera);
		     $io_pdf->stopObject($io_encabezado);
			/*if($li_i==$li_tot)
			{
			  $ld_total=number_format($ld_total,2,",",".");
			  uf_print_pie_cabecera($ld_total,$io_pdf); // Imprimimos pie de la cabecera
			}*/
			unset($la_data);
			/*if($li_i<$li_tot)
			{
			 $io_pdf->ezNewPage(); // Insertar una nueva página
			}*/ 
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
?> 