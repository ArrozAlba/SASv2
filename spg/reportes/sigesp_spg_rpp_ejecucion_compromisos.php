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
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 20/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],40,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,694,11,$as_titulo); // Agregar el título
		
		$io_pdf->addText(500,720,10,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(500,710,9,date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_procede,$as_comprobante,$as_nomprobene,$as_tipo_destino,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_procede // procede
		//	    		   as_comprobante // comprobante
		//                 as_nomprobene   // nombre del proveedor
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 20/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($as_tipo_destino=="P")
		{
			$ls_titulo="Proveedor";
		}
		if($as_tipo_destino=="B")
		{
			$ls_titulo="Beneficiario";
		}
		if($as_tipo_destino=="-")
		{
			$ls_titulo="Ninguno";
		}
		$la_data=array(array('name'=>'<b>Compromiso</b>  '.$as_procede.'---'.$as_comprobante.''),
		               array('name'=>'<b>'.$ls_titulo.'</b>  '.$as_nomprobene.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 9, // Tamaño de Letras
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(0.8,0.8,0.8),
						 'shadeCol2'=>array(0.8,0.8,0.8), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xPos'=>299); // Orientación de la tabla 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
    
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_programatica($as_spg_cuenta,$as_programatica,$as_denestpro,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_programatica // programatica del comprobante
		//	    		   as_denestpro5 // denominacion de la programatica del comprobante
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 20/09/2006 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($_SESSION["la_empresa"]["estmodest"] == 2)
	    {
		 $la_data=array(array('name'=>'<b>Cuenta  </b>  '.$as_spg_cuenta.'            <b>Programatica    </b>'.$as_programatica.'' ),
		                array('name'=>'<b></b>'.$as_denestpro.''));
		}
		else
		{
		 $ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
	 	 $ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
	 	 $ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
		 $la_data=array(array('name'=>'<b>Cuenta  </b>  '.$as_spg_cuenta.'            <b>Estructura Presupuestaria    </b>'),
		                array('name'=>'                                                  '.substr($as_programatica,0,$ls_loncodestpro1).'    '.$as_denestpro[0]),
						array('name'=>'                                                  '.substr($as_programatica,$ls_loncodestpro1,$ls_loncodestpro2).'     '.$as_denestpro[1]),
						array('name'=>'                                                  '.substr($as_programatica,$ls_loncodestpro1+$ls_loncodestpro2,$ls_loncodestpro3).'     '.$as_denestpro[2]));
		}				
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 9, // Tamaño de Letras
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9),
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xPos'=>299); // Orientación de la tabla 
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
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>2, // separacion entre tablas
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>299, // Orientación de la tabla
						 'cols'=>array('comprobante'=>array('justification'=>'center','width'=>140), // Justificación y ancho de la 
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
	function uf_print_total_programatica($ad_totalcomprometer,$ad_totalcausado,$ad_totalpagado,&$io_pdf)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_total_programatica
		//		    Acess : private
		//	    Arguments : ad_totalprogramatica // Total Programatica
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por : Ing. Yozelin Barragan
		// Fecha Creación : 20/09/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_datat=array(array('name'=>'____________________________________________________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>300, // Orientación de la tabla
						 'width'=>530); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_datat,$la_columna,'',$la_config);
		
		$la_data[]=array('comprobante'=>' ','fecha'=>'<b>SubTotal</b> ','comprometido'=>$ad_totalcomprometer,
		                  'causado'=>$ad_totalcausado,'pagado'=>$ad_totalpagado);
		$la_columnas=array('comprobante'=>'','fecha'=>'','comprometido'=>'','causado'=>'','pagado'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>2, // separacion entre tablas
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>299, // Orientación de la tabla
						 'cols'=>array('comprobante'=>array('justification'=>'center','width'=>140), // Justificación y ancho de la 
						 			   'fecha'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la 
						 			   'comprometido'=>array('justification'=>'right','width'=>100), // Justificación 
						 			   'causado'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la 
									   'pagado'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la 
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_total_programatica
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_total_comprometer,$ad_total_causado,$ad_total_pagado,&$io_pdf,$ls_titulo)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por : Ing. Yozelin Barragan
		// Fecha Creación : 20/09/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_datat=array(array('name'=>'____________________________________________________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>300, // Orientación de la tabla
						 'width'=>530); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_datat,$la_columna,'',$la_config);
		
		$la_data[1]=array('comprobante'=>' ','fecha'=>'<b>'.$ls_titulo.'</b> ','comprometido'=>$ad_total_comprometer,
		                  'causado'=>$ad_total_causado,'pagado'=>$ad_total_pagado);
		$la_columnas=array('comprobante'=>'','fecha'=>'','comprometido'=>'','causado'=>'','pagado'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>2, // separacion entre tablas
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>299, // Orientación de la tabla
						 'cols'=>array('comprobante'=>array('justification'=>'center','width'=>140), // Justificación y ancho de la 
						 			   'fecha'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la 
						 			   'comprometido'=>array('justification'=>'right','width'=>100), // Justificación 
						 			   'causado'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la 
									   'pagado'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la 
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
    //-----------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("sigesp_spg_funciones_reportes.php");
		$io_function_report = new sigesp_spg_funciones_reportes();
        require_once("../../shared/class_folder/class_funciones.php");
		$io_function=new class_funciones() ;
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
	//-----------------------------------------------------------------------------------------------------------------------------
		$ls_tipoformato=$_GET["tipoformato"];
		global $ls_tipoformato;
		global $la_data_tot_bsf;
		global $la_data_tot;
		if($ls_tipoformato==1)
		{
			require_once("sigesp_spg_reportes_class_bsf.php");
			$io_report = new sigesp_spg_reportes_class_bsf();
		}
		else
		{
			require_once("sigesp_spg_reportes_class.php");
			$io_report = new sigesp_spg_reportes_class();
		}	
		require_once("../../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
		$io_rcbsf= new sigesp_c_reconvertir_monedabsf();
		$li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$li_redconmon=$_SESSION["la_empresa"]["redconmon"];
		 
	//------------------------------------------------------------------------------------------------------------------------------		

    //--------------------------------------------------  Parámetros para Filtar el Reporte  --------------------------------------
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		$ls_comprobante  = $_GET["txtcomprobante"];
		$ls_procede  = $_GET["txtprocede"];
		$ldt_fecha  = $_GET["txtfecha"];
		$ldt_fecdes = $_GET["txtfecdes"];
		$ldt_fechas = $_GET["txtfechas"];	
		
	 /////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_desc_event="Solicitud de Reporte Ejecucion de Compromisos  Comprobante  ".$ls_comprobante." ,Procede  ".$ls_procede." , Fecha ".$ldt_fecha;
	 $io_function_report->uf_load_seguridad_reporte("SPG","sigesp_spg_r_ejecucion_compromisos.php",$ls_desc_event);
	////////////////////////////////         SEGURIDAD               ///////////////////////////////////////////////////////////////////////////////////////////////////
   //----------------------------------------------------  Parámetros del encabezado  --------------------------------------------
		$ldt_fecdes_cab=$io_function->uf_convertirfecmostrar($ldt_fecdes);
		$ldt_fechas_cab=$io_function->uf_convertirfecmostrar($ldt_fechas);
		
		$ls_titulo=" <b>EJECUCION DE COMPROMISOS</b> ";       
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
	 $lb_valido=$io_report->uf_spg_select_reportes_ejecucion_compromiso($ls_procede,$ls_comprobante,$ldt_fecha,$ldt_fecdes,$ldt_fechas);
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
		$io_pdf->ezSetCmMargins(4.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_tot=$io_report->dts_cab->getRowCount("programatica");
	    $ld_total_comprometer=0;
	    $ld_total_causado=0;
	    $ld_total_pagado=0;
		$ls_procede_next="";
		$ls_comprobante_next="";
		$ls_nomprobene_next="";
		$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
		$ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
		$ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
		$ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
		$ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
		for($li_i=1;$li_i<=$li_tot;$li_i++)
		{
	        //$io_pdf->transaction('start'); // Iniciamos la transacción
			$thisPageNum=$io_pdf->ezPageCount;
		    $ls_programatica=$io_report->dts_cab->data["programatica"][$li_i];
		    $ls_spg_cuenta=$io_report->dts_cab->data["spg_cuenta"][$li_i];
		    $ls_comprobante=$io_report->dts_cab->data["comprobante"][$li_i];
		    $ls_procede=$io_report->dts_cab->data["procede"][$li_i];
		    $ldt_fecha=$io_report->dts_cab->data["fecha"][$li_i];
			$ls_fecha=$io_function->uf_convertirfecmostrar($ldt_fecha);
			$ls_nombene=$io_report->dts_cab->data["nombene"][$li_i];
			$ls_nompro=$io_report->dts_cab->data["nompro"][$li_i];
			$ls_tipo_destino=$io_report->dts_cab->data["tipo_destino"][$li_i];
		    $ls_codban=$io_report->dts_cab->data["codban"][$li_i];
		    $ls_ctaban=$io_report->dts_cab->data["ctaban"][$li_i];
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
			if(($ls_procede_next!=$ls_procede)&&($ls_comprobante_next!=$ls_comprobante)&&($ls_nomprobene_next!=$ls_nomprobene))
			{
			    uf_print_cabecera($ls_procede,$ls_comprobante,$ls_nomprobene,$ls_tipo_destino,$io_pdf); // Imprimimos la cabecera 
			}	
			$ls_procede_next=$ls_procede;
			$ls_comprobante_next=$ls_comprobante;
			$ls_nomprobene_next=$ls_nomprobene;
			$lb_valido=$io_report->uf_spg_reportes_ejecucion_compromiso($ls_procede,$ls_comprobante,$ldt_fecha,$ldt_fecdes,
			                                                            $ldt_fechas,$ls_spg_cuenta,$ls_codban,$ls_ctaban,$ls_programatica);
            if($lb_valido)
			{
				$ld_sub_total_comprometer=0;
				$ld_sub_total_causado=0;
				$ld_sub_total_pagado=0;
				$li_totrow_det=$io_report->dts_reporte->getRowCount("spg_cuenta");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_programatica=$io_report->dts_reporte->data["programatica"][$li_s];
					$ls_estcla=substr($ls_programatica,-1);
					$ls_codestpro1=substr($ls_programatica,0,25);
					$ls_denestpro1="";
					$lb_valido=$io_function_report->uf_spg_reporte_select_denestpro1($ls_codestpro1,$ls_denestpro1,$ls_estcla);
					if($lb_valido)
					{
					  $ls_denestpro1=$ls_denestpro1;
					}
					$ls_codestpro2=substr($ls_programatica,25,25);
					if($lb_valido)
					{
					  $ls_denestpro2="";
					  $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro2($ls_codestpro1,$ls_codestpro2,$ls_denestpro2,$ls_estcla);
					  $ls_denestpro2=$ls_denestpro2;
					}
					$ls_codestpro3=substr($ls_programatica,50,25);
					if($lb_valido)
					{
					  $ls_denestpro3="";
					  $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro3($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_denestpro3,$ls_estcla);
					  $ls_denestpro3=$ls_denestpro3;
					}
					if($li_estmodest==2)
					{
						$ls_codestpro4=substr($ls_programatica,75,25);
						if($lb_valido)
						{
						  $ls_denestpro4="";
						  $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro4($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_denestpro4,$ls_estcla);
						  $ls_denestpro4=$ls_denestpro4;
						}
						$ls_codestpro5=substr($ls_programatica,100,25);
						if($lb_valido)
						{
						  $ls_denestpro5="";
						  $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro5($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_denestpro5,$ls_estcla);
						  $ls_denestpro5=$ls_denestpro5;
						}
						$ls_denestpro=trim($ls_denestpro1)." , ".trim($ls_denestpro2)." , ".trim($ls_denestpro3)." , ".trim($ls_denestpro4)." , ".trim($ls_denestpro5);
						$ls_programatica=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3)."-".substr($ls_codestpro4,-$ls_loncodestpro4)."-".substr($ls_codestpro5,-$ls_loncodestpro5);
					}
					else
				    {
						$ls_denestpro = array();
						//$ls_denestpro=$ls_denestpro1." , ".$ls_denestpro2." , ".$ls_denestpro3;
						$ls_denestpro[0]=$ls_denestpro1;
						$ls_denestpro[1]=$ls_denestpro2;
						$ls_denestpro[2]=$ls_denestpro3;
						$ls_programatica=substr($ls_codestpro1,-$ls_loncodestpro1).substr($ls_codestpro2,-$ls_loncodestpro2).substr($ls_codestpro3,-$ls_loncodestpro3);
					}
					//print $ls_programatica."<br>";
					$ls_spg_cuenta=trim($io_report->dts_reporte->data["spg_cuenta"][$li_s]);
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
			//	print "ciclo ->".$ls_programatica."<br>";
				}
			//	print "fuera ->".$ls_programatica."<br>";
				// Imprimimos la cabecera
			    uf_print_cabecera_programatica($ls_spg_cuenta,$ls_programatica,$ls_denestpro,$io_pdf); 
			    uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
				$ld_subtotal_comprometer=$ld_sub_total_comprometer;
				$ld_subtotal_causado=$ld_sub_total_causado;
				$ld_subtotal_pagado=$ld_sub_total_pagado;
				$ld_sub_total_comprometer=number_format($ld_sub_total_comprometer,2,",",".");
				$ld_sub_total_causado=number_format($ld_sub_total_causado,2,",",".");
				$ld_sub_total_pagado=number_format($ld_sub_total_pagado,2,",",".");
			    uf_print_total_programatica($ld_sub_total_comprometer,$ld_sub_total_causado,$ld_sub_total_pagado,$io_pdf);
				//unset($la_data);			
 			}
          	/*if ($io_pdf->ezPageCount==$thisPageNum)
			{// Hacemos el commit de los registros que se desean imprimir
            	$io_pdf->transaction('commit');
          	}
			elseif($thisPageNum>1)
			{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
				$io_pdf->transaction('rewind');
				$io_pdf->ezNewPage(); // Insertar una nueva página
			    //uf_print_cabecera($ls_procede,$ls_comprobante,$ls_nomprobene,$ls_tipo_destino,$io_pdf); 
			    uf_print_cabecera_programatica($ls_spg_cuenta,$ls_programatica,$ls_denestpro,$io_pdf); 
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
				$ld_subtotal_comprometer=number_format($ld_subtotal_comprometer,2,",",".");
				$ld_subtotal_causado=number_format($ld_subtotal_causado,2,",",".");
				$ld_subtotal_pagado=number_format($ld_subtotal_pagado,2,",",".");
				uf_print_total_programatica($ld_subtotal_comprometer,$ld_subtotal_causado,$ld_subtotal_pagado,$io_pdf); 
			}*/
			if($li_i==$li_tot)
			{
			   // Imprimimos pie de la cabecera
				if($ls_tipoformato==1)
				{
				  $ld_total_comprometer=number_format($ld_total_comprometer,2,",",".");
				  $ld_total_causado=number_format($ld_total_causado,2,",",".");
				  $ld_total_pagado=number_format($ld_total_pagado,2,",",".");
				  uf_print_pie_cabecera($ld_total_comprometer,$ld_total_causado,$ld_total_pagado,$io_pdf,"Total Bs.F.");
				} 
				else
				{
                  $ld_total_comprometer_bsf = $io_rcbsf->uf_convertir_monedabsf($ld_total_comprometer, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
				  $ld_total_causado_bsf = $io_rcbsf->uf_convertir_monedabsf($ld_total_causado, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
				  $ld_total_pagado_bsf = $io_rcbsf->uf_convertir_monedabsf($ld_total_pagado, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
				  
				  $ld_total_comprometer_bsf=number_format($ld_total_comprometer_bsf,2,",",".");
				  $ld_total_causado_bsf=number_format($ld_total_causado_bsf,2,",",".");
				  $ld_total_pagado_bsf=number_format($ld_total_pagado_bsf,2,",",".");
				  
				  $ld_total_comprometer=number_format($ld_total_comprometer,2,",",".");
				  $ld_total_causado=number_format($ld_total_causado,2,",",".");
				  $ld_total_pagado=number_format($ld_total_pagado,2,",",".");
				  //Bolivares 
				  uf_print_pie_cabecera($ld_total_comprometer,$ld_total_causado,$ld_total_pagado,$io_pdf,"Total Bs.");
				  //Bolivar Fuerte
				 // uf_print_pie_cabecera($ld_total_comprometer_bsf,$ld_total_causado_bsf,$ld_total_pagado_bsf,$io_pdf,"Total Bs.F.");
				}
			}
			unset($la_data);			
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
	unset($io_fecha);
?> 