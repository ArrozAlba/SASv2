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
	function uf_print_encabezado_pagina($as_titulo,$as_fecha,&$io_pdf)
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
		// Fecha Creación: 27/09/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=330-($li_tm/2);
		$io_pdf->addText($tm,730,10,$as_titulo); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(11,$as_fecha);
		$tm=330-($li_tm/2);
		$io_pdf->addText($tm,720,10,$as_fecha); // Agregar el título

		$io_pdf->addText(500,730,9,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(500,720,9,date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_cod_pro,$as_nomprobene,$as_ced_bene,$as_tipo_destino,$io_encabezado,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: privates
		//	    Arguments: as_programatica // programatica del comprobante
		//	    		   as_denestpro5 // denominacion de la programatica del comprobante
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 27/09/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSetY(710);
		if($as_tipo_destino=="P")
		{
			$ls_titulo="Proveedor";
			$ls_codigo=$as_cod_pro;
		}
		elseif($as_tipo_destino=="B")
		{
			$ls_titulo="Beneficiario";
			$ls_codigo=$as_ced_bene;
		}
		else
		{
			$ls_titulo="Ninguno";
			$ls_codigo="----------";
		}
		$la_data=array(array('name'=>'<b>Codigo</b> '.$ls_codigo.''),
		               array('name'=>'<b>'.$ls_titulo.'</b> '.$as_nomprobene.'' ));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'fontSize' => 9, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'textCol' =>array(0.1,0.1,0.1), // color del texto
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550); // Ancho Máximo de la tabla
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
		// Fecha Creación: 27/09/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'cols'=>array('comprobante'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la 
						 			   'fecha'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la 
						 			   'comprometido'=>array('justification'=>'right','width'=>85), // Justificación 
						 			   'causado'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la 
									   'pagado'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la 
									   'deuda'=>array('justification'=>'right','width'=>85))); // Justificación y ancho de la 
		$la_columnas=array('comprobante'=>'<b>Comprobante</b>',
						   'fecha'=>'<b>Fecha</b>',
						   'comprometido'=>'<b>Comprometido</b>',
						   'causado'=>'<b>Causado</b>',
						   'pagado'=>'<b>Pagado</b>',
						   'deuda'=>'<b>Deuda</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_totalcomprometer,$ad_totalcausado,$ad_totalpagado,$ad_totaldeuda,&$io_pdf,$as_titulo)
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
		
		$la_data[]=array('comprobante'=>'','fecha'=>'<b>'.$as_titulo.'</b> ','comprometido'=>$ad_totalcomprometer,
		                 'causado'=>$ad_totalcausado,'pagado'=>$ad_totalpagado,'deuda'=>$ad_totaldeuda);
		$la_columnas=array('comprobante'=>'','fecha'=>'','comprometido'=>'','causado'=>'','pagado'=>'','deuda'=>'');
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
						 'cols'=>array('comprobante'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la 
						 			   'fecha'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la 
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
		$ldt_fecdes = $_GET["txtfecdes"];
		$ldt_fechas = $_GET["txtfechas"];	
	    $ls_fechades=$io_function->uf_convertirfecmostrar($ldt_fecdes);
	    $ls_fechahas=$io_function->uf_convertirfecmostrar($ldt_fechas);
		$ls_provbenedes = $_GET["txtcodproben"];
		$ls_provbenehas = $_GET["txtcodprobenhas"];	
		$ls_nombre = $_GET["txtnombre"];
		$ls_nombrehas = $_GET["txtnombrehas"];	
		$ls_tipo = $_GET["rbtipo"];	
		$ls_orden = $_GET["rborden"];
		if($ls_tipo=="PC")
		{
		  $ls_nomprobene="Proveedor";
		}
		elseif($ls_tipo=="B")
		{
		  $ls_nomprobene="Beneficiario";
		}
	 /////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_desc_event="Solicitud de Reporte Resumen del Proveedor/beneficiario Detalle desde la  Fecha ".$ls_fechades."  hasta ".$ls_fechahas." Desde el ".$ls_nomprobene."  ".$ls_provbenedes."  hasta ".$ls_provbenehas;
	 $io_function_report->uf_load_seguridad_reporte("SPG","sigesp_spg_r_resumen_prov_bene.php",$ls_desc_event);
	////////////////////////////////         SEGURIDAD               ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------------------------------------------------------------------------------------
		$ls_titulo="<b>RESUMEN DE PROVEEDOR/BENEFICIARIO</b> "; 
		$ls_fecha="<b> DESDE  ".$ls_fechades."   HASTA LA FECHA  ".$ls_fechahas." </b>";      
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
     $lb_valido=$io_report->uf_spg_reportes_resumen_provee_bene_contrat_detalle($ldt_fecdes,$ldt_fechas,$ls_provbenedes,$ls_provbenehas,$ls_tipo);

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
		$io_pdf->ezSetCmMargins(5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$io_report->dts_reporte_final->group_noorder("cod_pro");
		$li_tot=$io_report->dts_reporte_final->getRowCount("spg_cuenta");
		$ld_total_comprometer=0;
	    $ld_total_causado=0;
	    $ld_total_pagado=0;
	    $ld_total_deuda=0;
		$ld_sub_total_comprometer=0;
		$ld_sub_total_causado=0;
		$ld_sub_total_pagado=0;
		$ld_sub_total_deuda=0;
		$ls_cod_pro_ant="";
		for($z=1;$z<=$li_tot;$z++)
		{
		    $li_tmp=($z+1);
			$io_pdf->transaction('start'); // Iniciamos la transacción
			$thisPageNum=$io_pdf->ezPageCount;
			$ls_spg_cuenta=$io_report->dts_reporte_final->data["spg_cuenta"][$z];
			$ls_procede=$io_report->dts_reporte_final->data["procede"][$z];
			$ls_comprobante=$io_report->dts_reporte_final->data["comprobante"][$z];
			$ldt_fecha=$io_report->dts_reporte_final->data["fecha"][$z]; 
			$ldt_fecha=$io_function->uf_convertirfecmostrar($ldt_fecha);
			$ld_comprometer=$io_report->dts_reporte_final->data["compromiso"][$z];  
			$ld_causado=$io_report->dts_reporte_final->data["causado"][$z];  
			$ld_pagado=$io_report->dts_reporte_final->data["pagado"][$z];  	  
			$ls_proc_comp=$ls_procede."---".$ls_comprobante;
			$ls_nombene=$io_report->dts_reporte_final->data["nombene"][$z];
			$ls_nompro=$io_report->dts_reporte_final->data["nompro"][$z];
			$ls_cod_pro=$io_report->dts_reporte_final->data["cod_pro"][$z];
			$ls_ced_bene=$io_report->dts_reporte_final->data["ced_bene"][$z];
			$ls_tipo_destino=$io_report->dts_reporte_final->data["tipo_destino"][$z];
			$ld_deuda=$ld_causado-$ld_pagado;
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
				$ls_nomprobene="Ninguno";
			}
		    if ($z<$li_tot)
		    {
				$ls_cod_pro_next=$io_report->dts_reporte_final->data["cod_pro"][$li_tmp]; 
		    }
		    elseif($z=$li_tot)
		    {
				$ls_cod_pro_next='no_next';
		    }
			if(empty($ls_cod_pro_next)&&(!empty($ls_cod_pro)))
			{
			   $ls_cod_pro_ant=$io_report->dts_reporte_final->data["cod_pro"][$z];
			}
			if($li_tot==1)
			{
			   $ls_cod_pro_ant=$io_report->dts_reporte_final->data["cod_pro"][$z];
			}
			$ld_total_comprometer=$ld_total_comprometer+$ld_comprometer;
			$ld_total_causado=$ld_total_causado+$ld_causado;
			$ld_total_pagado=$ld_total_pagado+$ld_pagado;
			$ld_total_deuda=$ld_total_deuda+$ld_deuda;
		  
			$ld_sub_total_comprometer=$ld_sub_total_comprometer+$ld_comprometer;
			$ld_sub_total_causado=$ld_sub_total_causado+$ld_causado;
			$ld_sub_total_pagado=$ld_sub_total_pagado+$ld_pagado;
			$ld_sub_total_deuda=$ld_sub_total_deuda+$ld_deuda;
			
			if (!empty($ls_cod_pro))
		    {
				  $ld_comprometer=number_format($ld_comprometer,2,",",".");
				  $ld_causado=number_format($ld_causado,2,",",".");
				  $ld_pagado=number_format($ld_pagado,2,",",".");
				  $ld_deuda=number_format($ld_deuda,2,",",".");
				  
				  $la_data[$z]=array('comprobante'=>$ls_proc_comp,'fecha'=>$ldt_fecha,'comprometido'=>$ld_comprometer,
				                     'causado'=>$ld_causado,'pagado'=>$ld_pagado,'deuda'=>$ld_deuda);
				  
				  $ld_comprometer=str_replace('.','',$ld_comprometer);
				  $ld_comprometer=str_replace(',','.',$ld_comprometer);	
				  $ld_causado=str_replace('.','',$ld_causado);
				  $ld_causado=str_replace(',','.',$ld_causado);	
				  $ld_pagado=str_replace('.','',$ld_pagado);
				  $ld_pagado=str_replace(',','.',$ld_pagado);	
				  $ld_deuda=str_replace('.','',$ld_deuda);
				  $ld_deuda=str_replace(',','.',$ld_deuda);	
			}
			else
			{
				  $ld_comprometer=number_format($ld_comprometer,2,",",".");
				  $ld_causado=number_format($ld_causado,2,",",".");
				  $ld_pagado=number_format($ld_pagado,2,",",".");
				  $ld_deuda=number_format($ld_deuda,2,",",".");
				  
				  $la_data[$z]=array('comprobante'=>$ls_proc_comp,'fecha'=>$ldt_fecha,'comprometido'=>$ld_comprometer,
				                     'causado'=>$ld_causado,'pagado'=>$ld_pagado,'deuda'=>$ld_deuda);
				  
				  $ld_comprometer=str_replace('.','',$ld_comprometer);
				  $ld_comprometer=str_replace(',','.',$ld_comprometer);	
				  $ld_causado=str_replace('.','',$ld_causado);
				  $ld_causado=str_replace(',','.',$ld_causado);	
				  $ld_pagado=str_replace('.','',$ld_pagado);
				  $ld_pagado=str_replace(',','.',$ld_pagado);	
				  $ld_deuda=str_replace('.','',$ld_deuda);
				  $ld_deuda=str_replace(',','.',$ld_deuda);	
			}
			if (!empty($ls_cod_pro_next))
			{
				  $ld_comprometer=number_format($ld_comprometer,2,",",".");
				  $ld_causado=number_format($ld_causado,2,",",".");
				  $ld_pagado=number_format($ld_pagado,2,",",".");
				  $ld_deuda=number_format($ld_deuda,2,",",".");
				  
				  $la_data[$z]=array('comprobante'=>$ls_proc_comp,'fecha'=>$ldt_fecha,'comprometido'=>$ld_comprometer,
				                     'causado'=>$ld_causado,'pagado'=>$ld_pagado,'deuda'=>$ld_deuda);
				 $io_encabezado=$io_pdf->openObject();
			     uf_print_cabecera($ls_cod_pro_ant,$ls_nomprobene,$ls_ced_bene,$ls_tipo_destino,$io_encabezado,$io_pdf);
 				 uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				 $ld_subtotal_comprometer=$ld_sub_total_comprometer;
				 $ld_subtotal_causado=$ld_sub_total_causado;
				 $ld_subtotal_pagado=$ld_sub_total_pagado;
				 $ld_subtotal_deuda=$ld_sub_total_deuda;
				 $ld_sub_total_comprometer=number_format($ld_sub_total_comprometer,2,",",".");
				 $ld_sub_total_causado=number_format($ld_sub_total_causado,2,",",".");
				 $ld_sub_total_pagado=number_format($ld_sub_total_pagado,2,",",".");
				 $ld_sub_total_deuda=number_format($ld_sub_total_deuda,2,",",".");
				 if($ls_tipoformato==1)
				 {
					 uf_print_pie_cabecera($ld_sub_total_comprometer,$ld_sub_total_causado,$ld_sub_total_pagado,
										   $ld_sub_total_deuda,$io_pdf,'Total Bs.F.');	
				 }
				 else
				 {
					 uf_print_pie_cabecera($ld_sub_total_comprometer,$ld_sub_total_causado,$ld_sub_total_pagado,
										   $ld_sub_total_deuda,$io_pdf,'Total Bs.');	
				 }
                 $io_pdf->stopObject($io_encabezado);
				 if ($z<$li_tot)
				    {
				      $io_pdf->ezNewPage();
				    }
				 $ld_sub_total_comprometer=0;
				 $ld_sub_total_causado=0;
				 $ld_sub_total_pagado=0;
				 $ld_sub_total_deuda=0;
				 //$io_pdf->ezNewPage(); // Insertar una nueva página
				 /*if ($io_pdf->ezPageCount==$thisPageNum)
				 {// Hacemos el commit de los registros que se desean imprimir
					$io_pdf->transaction('commit');
				 }
				 elseif($thisPageNum<>1)
				 {// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
					$io_pdf->transaction('rewind');
					$io_pdf->ezNewPage(); // Insertar una nueva página
		            $io_encabezado=$io_pdf->openObject();
					uf_print_cabecera($ls_cod_pro_ant,$ls_nomprobene,$ls_ced_bene,$ls_tipo_destino,$io_encabezado,$io_pdf);
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					$ld_subtotal_comprometer=number_format($ld_subtotal_comprometer,2,",",".");
					$ld_subtotal_causado=number_format($ld_subtotal_causado,2,",",".");
					$ld_subtotal_pagado=number_format($ld_subtotal_pagado,2,",",".");
					$ld_subtotal_deuda=number_format($ld_subtotal_deuda,2,",",".");
					uf_print_pie_cabecera($ld_subtotal_comprometer,$ld_subtotal_causado,$ld_subtotal_pagado,
					                      $ld_subtotal_deuda,$io_pdf);	
                    $io_pdf->stopObject($io_encabezado);
					$ld_subtotal_comprometer=0;
					$ld_subtotal_causado=0;
					$ld_subtotal_pagado=0;
					$ld_subtotal_deuda=0;
				 }*/
				 if($z==$li_tot)
				 {
				   // Imprimimos pie de la cabecera
					if($ls_tipoformato==1)
					{
					  $ld_total_comprometer=number_format($ld_total_comprometer,2,",",".");
					  $ld_total_causado=number_format($ld_total_causado,2,",",".");
					  $ld_total_pagado=number_format($ld_total_pagado,2,",",".");
					  $ld_total_deuda=number_format($ld_total_deuda,2,",",".");
					  uf_print_pie_cabecera($ld_total_comprometer,$ld_total_causado,$ld_total_pagado,
											$ld_total_deuda,$io_pdf,'Total Bs.F.');
				    }						
				    else
				    {
						 $ld_total_comprometer_bsf = $io_rcbsf->uf_convertir_monedabsf($ld_total_comprometer, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
						 $ld_total_causado_bsf = $io_rcbsf->uf_convertir_monedabsf($ld_total_causado, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
						 $ld_total_pagado_bsf = $io_rcbsf->uf_convertir_monedabsf($ld_total_pagado, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
						 $ld_total_deuda_bsf = $io_rcbsf->uf_convertir_monedabsf($ld_total_deuda, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
				   
						 $ld_total_comprometer_bsf=number_format($ld_total_comprometer_bsf,2,",",".");
						 $ld_total_causado_bsf=number_format($ld_total_causado_bsf,2,",",".");
						 $ld_total_pagado_bsf=number_format($ld_total_pagado_bsf,2,",",".");
						 $ld_total_deuda_bsf=number_format($ld_total_deuda_bsf,2,",",".");
						 
						 $ld_total_comprometer=number_format($ld_total_comprometer,2,",",".");
						 $ld_total_causado=number_format($ld_total_causado,2,",",".");
						 $ld_total_pagado=number_format($ld_total_pagado,2,",",".");
						 $ld_total_deuda=number_format($ld_total_deuda,2,",",".");
						 //Bolivares
						 uf_print_pie_cabecera($ld_total_comprometer,$ld_total_causado,$ld_total_pagado,
											   $ld_total_deuda,$io_pdf,'Total Bs.');
						 //Bolivar Fuerte
						// uf_print_pie_cabecera($ld_total_comprometer_bsf,$ld_total_causado_bsf,$ld_total_pagado_bsf,
						//					   $ld_total_deuda_bsf,$io_pdf,'Total Bs.F.');
											   
				    }
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
	unset($io_fecha);
?> 