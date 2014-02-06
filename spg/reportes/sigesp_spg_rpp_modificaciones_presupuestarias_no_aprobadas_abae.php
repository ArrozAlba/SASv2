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
	function uf_print_cabecera_detalle($io_encabezado,$as_descripcion,$as_numdoc,$as_fecmov,$as_tipmodpre,&$io_pdf)
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
	    $io_pdf->saveState();
	    $io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],40,715,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->addText(50,680,10,"<b>Tipo Modificación:</b> ".$as_tipmodpre);
		$ls_texto_1 = "<b>MODIFICACIONES PRESUPUESTARIAS NO APROBADAS</b>";

		$ls_texto_3 = "<b>Descripción:</b>";
		$as_descripcion2 = $io_pdf->addTextWrap(46,655,350,9,$as_descripcion);
	    $as_descripcion3 = $io_pdf->addTextWrap(46,645,350,9,$as_descripcion2);
		$io_pdf->addTextWrap(46,635,350,9,$as_descripcion3);


		$io_pdf->addText(45,665,10,$ls_texto_3);

		$li_tm=$io_pdf->getTextWidth(14,$ls_texto_1);
		$tm=296-($li_tm/2);
		$io_pdf->addText($tm,698,14,$ls_texto_1); // Agregar el título

		$io_pdf->addText(415,660,10,'N°: '.$as_numdoc);
		$io_pdf->addText(415,640,10,'Fecha Registro     : '.$as_fecmov);
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->line(410,675,410,620);
		//$io_pdf->rectangle(410,675,180,30);
		$io_pdf->rectangle(40,625,550,50);

	    $io_pdf->ezSetY(626.8);
	    $la_datatitulos= array(array('estructura'=>"<b>Est. Presup.</b>",'especifica'=>"<b>ESPECIFICA</b>",
		                             'denominacion'=>"<b>DENOMINACION</b>",'cedente'=>"<b>CEDENTE</b>",
									 'receptora'=>"<b>RECEPTORA</b>"));

		$la_columna=array('estructura'=>'<b>Est. Presup.</b>',
						  'especifica'=>'<b>ESPECIFICA</b>',
						  'denominacion'=>'<b>CEDENTE</b>',
						  'cedente'=>'<b>CEDENTE</b>',
						  'receptora'=>'<b>RECEPTORA</b>');

		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>320, // Orientación de la tabla
						 'cols'=>array('estructura'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'especifica'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'center','width'=>210), // Justificación y ancho de la columna
									   'cedente'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
									   'receptora'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna
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
		//$io_pdf->ezSetCmMargins(5,6.5,3,3); // Configuración de los margenes en centímetros
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>320, // Orientación de la tabla
						 'cols'=>array('estructura'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'especifica'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>210),
									   'cedente'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
									   'receptora'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna

		$la_columnas=array('estructura'=>'',
						   'especifica'=>'<b>ESPECIFICA</b>',
						   'denominacion'=>'<b>DENOMINACION</b>',
						   'cedente'=>'<b>CEDENTE</b>',
						   'receptora'=>'<b>RECEPTORA</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_totalaumento,$ad_totaldismi,$as_denominacion,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 01/04/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('total'=>'<b>Total   '.$as_denominacion.'</b>','disminucion'=>$ad_totaldismi,'aumento'=>$ad_totalaumento));
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
	
	function uf_print_pie_de_pagina(&$io_pdf)
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////
	//	   Function: uf_print_pie_de_pagina
	//	     Access: public
	//	    Returns: vacio	 
	//	Description: Método que imprime el pie de pagina de Forma 0301 De Modificaciones Presupuestarias. 
	//////////////////////////////////////////////////////////////////////////////////////////////////////
   
        $io_pdf->Rectangle(19,80,570,30);
        $io_pdf->addText(240,103,7,"POR LA INSTITUCIÓN:"); // Agregar el título
        $io_pdf->Rectangle(19,40,570,60);
		//$io_pdf->line(250,80,250,80);		
		//$io_pdf->line(205,40,205,100);		
		//$io_pdf->line(400,40,400,100);	
		$io_pdf->line(120,40,120,100);	
		$io_pdf->line(220,40,220,100);	
		$io_pdf->line(325,40,325,100);	
		$io_pdf->line(445,40,445,110);	
		//$io_pdf->line(480,40,480,100);	
		$io_pdf->addText(25,90,7,"ELABORADO POR:"); // Agregar el título
		$io_pdf->addText(55,50,6,"Egly Palacios");
		$io_pdf->addText(40,44,6,"Analista de Presupuesto");
		$io_pdf->addText(140,50,6,"Carlos Matos");
		$io_pdf->addText(140,44,6,"Administrador");
		$io_pdf->addText(240,44,6,"Planificación y Presupuesto");
		$io_pdf->addText(350,50,6,"Marilú López");
		$io_pdf->addText(340,44,6,"Gerente Administrativo");
		$io_pdf->addText(470,50,6,"Nuris D Orihuela G.");
		$io_pdf->addText(480,44,6,"Presidente");
		
		//$io_pdf->addText(110,90,7,"REVISADO POR:(PLANIFICACIÓN Y PRESUPUESTO"); // Agregar el título
		$io_pdf->addText(130,90,7,"REVISADO POR:"); // Agregar el título
		$io_pdf->addText(230,90,7,"REVISADO POR:"); // Agregar el título
		$io_pdf->addText(330,90,7,"CONFIRMADO POR:"); // Agregar el título
		$io_pdf->addText(450,90,7,"APROBADO POR:"); // Agregar el título
		//$io_pdf->addText(298,90,7,"APROBADO POR GERENTE"); // Agregar el título
		//$io_pdf->addText(300,83,7,"GENERAL O PRESIDENTE"); // Agregar el título
		//$io_pdf->addText(415,90,7,"JEFE SECTOR"); // Agregar el título
		//$io_pdf->addText(490,90,7,"DIRECTOR G. SECTORIAL"); // Agregar el título
	}// end function uf_print_encabezadopagina
	
	
	
	
	
	
	
	
	
	
	
	//--------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("../../shared/class_folder/class_funciones.php");
		require_once("../../shared/class_folder/class_fecha.php");
		require_once("sigesp_spg_funciones_reportes.php");
		$io_funciones		= new class_funciones();
		$io_fecha 			= new class_fecha();
		$io_function_report = new sigesp_spg_funciones_reportes();
		$ls_tipoformato=$_GET["tipoformato"];
	//-----------------------------------------------------------------------------------------------------------------------------
		 global $ls_tipoformato;
		 if($ls_tipoformato==1)
		 {
			require_once("sigesp_spg_reporte_bsf.php");
			$io_report = new sigesp_spg_reporte_bsf();
			//print('cargo BsF.');
		 }
		 else
		 {
			require_once("sigesp_spg_reporte.php");
		    $io_report = new sigesp_spg_reporte();
		 }

		 require_once("../../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");

		 $io_rcbsf= new sigesp_c_reconvertir_monedabsf();
		 $li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		 $li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		 $li_redconmon=$_SESSION["la_empresa"]["redconmon"];
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
        $ls_ckbrect		= $_GET["ckbrect"];
        $ls_ckbtras		= $_GET["ckbtras"];
        $ls_ckbinsu		= $_GET["ckbinsu"];
        $ls_ckbcre		= $_GET["ckbcre"];
		$ls_comprobante = $_GET["txtcomprobante"];
		$ls_procede  	= $_GET["txtprocede"];
		$ldt_fecha  	= $_GET["txtfecha"];
		$fecdes			= $_GET["txtfecdes"];
		$ldt_fecdes		= $io_funciones->uf_convertirdatetobd($fecdes);
		$fechas			= $_GET["txtfechas"];
		$ldt_fechas		= $io_funciones->uf_convertirdatetobd($fechas);
		$ldt_fecha		= $io_funciones->uf_convertirdatetobd($ldt_fecha);
		$li_estmodest   = $_SESSION["la_empresa"]["estmodest"];
		/////////////////////////////////         SEGURIDAD               ///////////////////////////////////
		$ls_desc_event="Solicitud de Reporte Modificaciones Presupuestarias Aprobadas desde la fecha ".$fecdes." hasta ".$fechas.", Comprobante  ".$ls_comprobante." ,Procede  ".$ls_procede." ,Fecha del Comprobante  ".$ldt_fecha;
		$io_function_report->uf_load_seguridad_reporte("SPG","sigesp_spg_r_modificaciones_presupuestarias_aprobadas.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               ///////////////////////////////////
        // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )
	    $lb_valido=$io_report->uf_spg_reporte_modificaciones_presupuestarias_no_aprobadas($ls_ckbrect,$ls_ckbtras,$ls_ckbinsu,$ls_ckbcre,$ldt_fecdes,
																		                  $ldt_fechas,$ls_comprobante,$ls_procede,$ldt_fecha);
		if ($lb_valido==false) // Existe algún error ó no hay registros
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
			 $io_pdf->ezSetCmMargins(6.3,3,3,3); // Configuración de los margenes en centímetros
			 $io_pdf->ezStartPageNumbers(550,25,8,'','',1); // Insertar el número de página
			 $io_report->dts_reporte->group_noorder("procomp");
			 $li_tot		  = $io_report->dts_reporte->getRowCount("spg_cuenta");
			 $ld_totalaumento = 0;
			 $ld_totaldismi   = 0;
			 $ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
		     $ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
		     $ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
		     $ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
		     $ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
			 for ($z=1;$z<=$li_tot;$z++)
				 {
				   $io_pdf->transaction('start'); // Iniciamos la transacción
		    	   $li_tmp=($z+1);
				   $thisPageNum	   = $io_pdf->ezPageCount;
				   $ls_procede	   = $io_report->dts_reporte->data["procede"][$z];
				   $ls_procomp     = $io_report->dts_reporte->data["procomp"][$z];
				   $ls_comprobante = $io_report->dts_reporte->data["comprobante"][$z];
				   if ($z<$li_tot)
		              {
					    $ls_procomp_next=$io_report->dts_reporte->data["procomp"][$li_tmp];
		    	      }
		    	   elseif($z=$li_tot)
		    		  {
						$ls_procomp_next='no_next';
		              }
			       if (!empty($ls_procomp))
				  	  {
					    $ls_procomp_ant=$io_report->dts_reporte->data["procomp"][$z];
					  }
			       $ls_descripcion  = trim($io_report->dts_reporte->data["cmp_descripcion"][$z]);
			       $ls_codestpro    = $io_report->dts_reporte->data["programatica"][$z];
		 	       if ($li_estmodest=='1')
			          {
					    $ls_codestpro1 = substr($ls_codestpro,0,25);
						$ls_codestpro2 = substr($ls_codestpro,25,25);
						$ls_codestpro3 = substr($ls_codestpro,50,25);
						//$ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
						$ls_codestpro=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3);
			          }
				   else
					  {
					    $ls_codestpro1 = substr($ls_codestpro,0,25);
						$ls_codestpro2 = substr($ls_codestpro,25,25);
						$ls_codestpro3 = substr($ls_codestpro,50,25);
						$ls_codestpro4 = substr($ls_codestpro,75,25);
						$ls_codestpro5 = substr($ls_codestpro,100,25);
						//$ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3.'-'.$ls_codestpro4.'-'.$ls_codestpro5;
						$ls_codestpro=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3)."-".substr($ls_codestpro4,-$ls_loncodestpro4)."-".substr($ls_codestpro5,-$ls_loncodestpro5);
					  }
			       $ls_especifica   = $io_report->dts_reporte->data["spg_cuenta"][$z];
				   $ls_denominacion = trim($io_report->dts_reporte->data["denominacion"][$z]);
				   $ls_documento	= $io_report->dts_reporte->data["documento"][$z];
				   $ldt_fecha_bd	= $io_report->dts_reporte->data["fecha"][$z];
				   $ldt_fecha_bd=date("Y-m-d",strtotime($ldt_fecha_bd));
				   $ldt_fecha		= $io_funciones->uf_convertirfecmostrar($ldt_fecha_bd);
				   $ld_aumento		= $io_report->dts_reporte->data["aumento"][$z];
				   $ld_disminucion  = $io_report->dts_reporte->data["disminucion"][$z];
			       if ($ls_procede=="SPGREC")
			          {
			            $ls_proc="RECTIFICACIONES";
			          }
			       if ($ls_procede=="SPGINS")
			          {
			            $ls_proc="INSUBSISTENCIAS";
			          }
			       if ($ls_procede=="SPGTRA")
					  {
					    $ls_proc="TRASPASOS";
					  }
			       if ($ls_procede=="SPGCRA")
					  {
					    $ls_proc="CREDITOS/INGRESOS ADICIONALES";
					  }
		           $ld_totalaumento = ($ld_totalaumento+$ld_aumento);
		           $ld_totaldismi   = ($ld_totaldismi+$ld_disminucion);
				   if (!empty($ls_procomp))
		              {
						$la_data[$z]  = array('estructura'=>$ls_codestpro,
											  'especifica'=>$ls_especifica,
											  'denominacion'=>$ls_denominacion,
											  'cedente'=>number_format($ld_disminucion,2,',','.'),
											  'receptora'=>number_format($ld_aumento,2,',','.'));
			          }
			       else
			          {
						$la_data[$z]=array('estructura'=>$ls_codestpro,
										   'especifica'=>$ls_especifica,
										   'denominacion'=>$ls_denominacion,
										   'cedente'=>number_format($ld_disminucion,2,',','.'),
										   'receptora'=>number_format($ld_aumento,2,',','.'));
					  }
		 	       if (!empty($ls_procomp_next))
			          {
						 $la_data[$z]=array('estructura'=>$ls_codestpro,
										    'especifica'=>$ls_especifica,
										    'denominacion'=>$ls_denominacion,
										    'cedente'=>number_format($ld_disminucion,2,',','.'),
										    'receptora'=>number_format($ld_aumento,2,',','.'));
						 $io_encabezado=$io_pdf->openObject();
						 uf_print_cabecera_detalle($io_encabezado,$ls_descripcion,$ls_comprobante,$ldt_fecha,$ls_proc,$io_pdf);
						 uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
						 if($ls_tipoformato==1)
						 {
						 $ld_totalaumento=number_format($ld_totalaumento,2,",",".");
						 $ld_totaldismi=number_format($ld_totaldismi,2,",",".");
						 uf_print_pie_cabecera($ld_totalaumento,$ld_totaldismi,'Bs.F.',$io_pdf);
						 }
						 else
						 {
						 	 //$ld_totalaumento_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_totalaumento, $li_candeccon,$li_tipconmon,1000,$li_redconmon);
						     //$ld_totaldismi_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_totaldismi, $li_candeccon,$li_tipconmon,1000,$li_redconmon);

							 $ld_totalaumento=number_format($ld_totalaumento,2,",",".");
						     $ld_totaldismi=number_format($ld_totaldismi,2,",",".");
							 uf_print_pie_cabecera($ld_totalaumento,$ld_totaldismi,'Bs.',$io_pdf);

							 //$ld_totalaumento_bsf=number_format($ld_totalaumento_bsf,2,",",".");
						     //$ld_totaldismi_bsf=number_format($ld_totaldismi_bsf,2,",",".");
							 //uf_print_pie_cabecera($ld_totalaumento_bsf,$ld_totaldismi_bsf,'Bs.F.',$io_pdf);
						 }
						 uf_print_pie_de_pagina(&$io_pdf);
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
			          }
	             }
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