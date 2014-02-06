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
	function uf_print_encabezado_pagina($as_titulo,$as_fecha,$as_fecha2,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 25/08/2008
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(10,$as_titulo);
		$tm=314-($li_tm/2);
		$io_pdf->addText($tm,730,10,$as_titulo); // Agregar el título		
		$li_tm=$io_pdf->getTextWidth(10,$as_fecha);
		$tm=314-($li_tm/2);
		$io_pdf->addText($tm,715,10,$as_fecha); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(10,$as_fecha2);
		$tm=314-($li_tm/2);
		$io_pdf->addText($tm,700,10,$as_fecha2); // Agregar el título
		$io_pdf->addText(500,730,9,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(500,720,9,date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: privates
		//	    Arguments: as_programatica // programatica del comprobante
		//	    		   as_denestpro5 // denominacion de la programatica del comprobante
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 25/08/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 7, // Tamaño de Letras
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9),
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,$as_show,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 25/08/2008
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$la_config=array('showHeadings'=>$as_show, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas 						 
						 'colGap'=>1, // separacion entre tablas
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>302, // Orientación de la tabla
						 'cols'=>array('numdoc'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la 
						 			   'fecmov'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la 
									   'feccad'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la 
						 			   'beneficiario'=>array('justification'=>'left','width'=>240), // Justificación y ancho de la 
						 			   'monto'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la 
		$la_columnas=array('numdoc'=>'<b>Número Documento</b>',
		                   'fecmov'=>'<b>Fecha Emisión</b>',
						   'feccad'=>'<b>Fecha Caducidad</b>',
						   'beneficiario'=>'<b>Beneficiario</b>',
						   'monto'=>'<b>Monto Bs.</b>');
						   
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_total_monto,$ad_total_cheques,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 25/08/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		
		
		$la_data[1]=array('name1'=>'<b>Total Cheques Caducados:    '.$ad_total_cheques.'</b>',
		                   'name2'=>'<b>Monto Total Cheques Caducados Bs.:    '.$ad_total_monto.'</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>2, // separacion entre tablas
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>303, // Orientación de la tabla
						 'cols'=>array('name1'=>array('justification'=>'right','width'=>240), // Justificación y ancho de la 
						               'name2'=>array('justification'=>'right','width'=>320))); // Justificación y ancho de la 
		$io_pdf->ezTable($la_data,'','',$la_config);
		
	}// end function uf_print_pie_cabecera
//--------------------------------------------------------------------------------------------------------------------------------
		require_once("sigesp_scb_class_report.php");
		require_once('../../shared/class_folder/class_pdf.php');
		require_once("../../shared/class_folder/class_fecha.php");
		require_once("../../shared/class_folder/sigesp_include.php");
        require_once("../../shared/class_folder/class_funciones.php");

		$sig_inc     = new sigesp_include();
		$con         = $sig_inc->uf_conectar();
		$io_report   = new sigesp_scb_class_report($con);
		$io_function = new class_funciones() ;
		$io_fecha    = new class_fecha();
//--------------------------------------------------  Parámetros para Filtar el Reporte  --------------------------------------
		$ldt_mesdes     = $_GET["mesdes"];
		$ldt_meshas     = $_GET["meshas"];	
		$ls_codban      = $_GET["codban"];
		$ls_ctaban      = $_GET["ctaban"];
        $ls_denban      = $_GET["denban"];
		$ls_dencta      = $_GET["dencta"];
		$ldt_feccad     = $_GET["feccad"];
		$ls_feccad =$io_function->uf_convertirfecmostrar($ldt_feccad );
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		
		$ls_fechades='01/'.$ldt_mesdes.'/'.$li_ano;
		$ls_fechahas=$io_fecha->uf_last_day($ldt_meshas,$li_ano);
		
		switch ($ldt_mesdes)
		{
		  case '01': $ls_mesdes='ENERO';
		  break; 
		  case '02': $ls_mesdes='FEBRERO';
		  break;
		  case '03': $ls_mesdes='MARZO';
		  break;
		  case '04': $ls_mesdes='ABRIL';
		  break;
		  case '05': $ls_mesdes='MAYO';
		  break;
		  case '06': $ls_mesdes='JUNIO';
		  break;
		  case '07': $ls_mesdes='JULIO';
		  break;
		  case '08': $ls_mesdes='AGOSTO';
		  break;
		  case '09': $ls_mesdes='SEPTIEMBRE';
		  break;
		  case '10': $ls_mesdes='OCTUBRE';
		  break;
		  case '11': $ls_mesdes='NOVIEMBRE';
		  break;
		  case '12': $ls_mesdes='DICIEMBRE';
		  break;
		}
		switch ($ldt_meshas)
		{
		  case '01': $ls_meshas='ENERO';
		  break; 
		  case '02': $ls_meshas='FEBRERO';
		  break;
		  case '03': $ls_meshas='MARZO';
		  break;
		  case '04': $ls_meshas='ABRIL';
		  break;
		  case '05': $ls_meshas='MAYO';
		  break;
		  case '06': $ls_meshas='JUNIO';
		  break;
		  case '07': $ls_meshas='JULIO';
		  break;
		  case '08': $ls_meshas='AGOSTO';
		  break;
		  case '09': $ls_meshas='SEPTIEMBRE';
		  break;
		  case '10': $ls_meshas='OCTUBRE';
		  break;
		  case '11': $ls_meshas='NOVIEMBRE';
		  break;
		  case '12': $ls_meshas='DICIEMBRE';
		  break;
		}
	    
	//----------------------------------------------------  Parámetros del encabezado  ----------------------------------------------
		$ls_titulo="<b>LISTADO DE CHEQUES CADUCADOS</b> "; 
		$ls_fecha="<b> EMITIDOS DESDE ".$ls_mesdes.' '.$li_ano."   HASTA ".$ls_meshas.' '.$li_ano." </b>";    
		$ls_fecha2="<b>CON FECHA TOPE DE CADUCIDAD ".$ls_feccad."</b>";  
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
     $lb_valido=$io_report->uf_find_bancos($ls_codban,$ls_ctaban);
	
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
		$io_pdf=new class_pdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(4.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$ls_fecha2,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_tot=$io_report->ds_bancos->getRowCount("codban");
        $ld_total_monto_general=0;
		$ls_spg_cuenta_ant="";
		$ld_total_monto=0;
		for($z=1;$z<=$li_tot;$z++)
		{
			$li_tmp=($z+1);
			$io_pdf->transaction('start'); // Iniciamos la transacción
			$thisPageNum=$io_pdf->ezPageCount;
		    $ls_codban=$io_report->ds_bancos->getValue("codban",$z);  	  
		    $ls_nomban=$io_report->ds_bancos->getValue("nomban",$z);  	  
		    $ls_ctaban=$io_report->ds_bancos->getValue("ctaban",$z);  	  
		    $ls_dencta=$io_report->ds_bancos->getValue("dencta",$z);  	  
			$la_data[1]=array('name'=>$ls_nomban);
			$la_data[2]=array('name'=>$ls_ctaban."  ".$ls_dencta);
			$la_data[3]=array('name'=>'');
			$li_altousado=$io_pdf->get_alto_usado();
			$lb_valido2=$io_report->uf_cargar_cheques_caducados($ls_fechades,$ls_fechahas,$ls_codban,$ls_ctaban,$ldt_feccad,$ls_num);	 
	    	$li_total=$io_report->ds_data->getRowCount("codban");
			if ($ls_num==0)
			{
				print("<script language=JavaScript>");
				print(" alert('La empresa no tiene configurado el número de días de caducidad de los cheques.');"); 
				print(" close();");
				print("</script>");
			}			
			else
			{
				if($li_total>0)
				{
					uf_print_cabecera($la_data,$io_pdf); // Imprimimos el detalle 		
					$ld_total_monto=0;
					unset($la_data);
					for($y=1;$y<=$li_total;$y++)
					{
						
						$ls_numdoc = $io_report->ds_data->getValue("numdoc",$y);
						$ls_nomproben = $io_report->ds_data->getValue("nomproben",$y);
						$ldec_monto   = $io_report->ds_data->getValue("monto",$y);					
						$ld_fecha      = $io_report->ds_data->getValue("fecmov",$y);			
						$ld_feccad     =$io_report->ds_data->getValue("feccad",$y);
						$ld_total_monto=$ld_total_monto+$ldec_monto;
						$la_data[1]    =array('numdoc'=>$ls_numdoc,'fecmov'=>$ld_fecha,'feccad'=>$ld_feccad,'beneficiario'=>$ls_nomproben,'monto'=>number_format($ldec_monto,2,",","."));
						
						if ($y==1)
						{
							$ls_show=1;
						}
						else
						{
							$ls_show=0;
						}
						
						uf_print_detalle($la_data,$ls_show,$io_pdf);
						
					}	
					$ld_total_monto=number_format($ld_total_monto,2,",",".");
					uf_print_pie_cabecera($ld_total_monto,$li_total,$io_pdf);						
					unset($la_data);
				}
				else
				{
					$lb_valido2=false;
				}
			}
		}//for	
		
		if ($lb_valido2)
		{
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
		}
		else
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
	unset($io_function_report);
?> 