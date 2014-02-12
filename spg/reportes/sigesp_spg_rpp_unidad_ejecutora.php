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
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 13/09/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_detalle(&$io_pdf)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 13/09/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('coduniadm'=>'<b>Codigo Unidad Administrativas</b>','denuniadm'=>'<b>Denominación</b>'));
		$la_columna=array('coduniadm'=>'','denuniadm'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>299, // Orientación de la tabla
						 'cols'=>array('coduniadm'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la 
						 			   'denuniadm'=>array('justification'=>'center','width'=>400))); // Justificación y ancho 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_cabecera_detalle
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
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 13/09/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>299, // Orientación de la tabla
						 'cols'=>array('coduniadm'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la 
						 			   'denuniadm'=>array('justification'=>'left','width'=>400))); // Justificación y ancho 

		$la_columnas=array('coduniadm'=>'<b>Codigo Unidad Administrativas</b>',
						   'denuniadm'=>'<b>Denominación</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("sigesp_spg_reportes_class.php");
		$io_report = new sigesp_spg_reportes_class();
		require_once("sigesp_spg_funciones_reportes.php");
		$io_function_report = new sigesp_spg_funciones_reportes();
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
   //--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
		$li_estmodest       = $_SESSION["la_empresa"]["estmodest"];
		$ls_codestpro1_min  = $_GET["codestpro1"];
		$ls_codestpro2_min  = $_GET["codestpro2"];
		$ls_codestpro3_min  = $_GET["codestpro3"];
	    $ls_estclades       = $_GET["estclades"];
	    $ls_estclahas       = $_GET["estclahas"];
		if($li_estmodest==1)
		{
			$ls_codestpro4_min = "0000000000000000000000000";
			$ls_codestpro5_min = "0000000000000000000000000";		
		}
		elseif($li_estmodest==2)
		{
			$ls_codestpro4_min = $_GET["codestpro4"];
			$ls_codestpro5_min = $_GET["codestpro5"];			
		}
		
		$ls_coduniadm_des=$_GET["txtcoduniadmdes"];
	    $ls_coduniadm_has=$_GET["txtcoduniadmhas"];
		
		$li_estemireq=$_GET["chkemireq"];
		$ls_ckq_unidad=$_GET["chkunidad"];
		
	   $ls_codfuefindes=$_GET["txtcodfuefindes"];
	   $ls_codfuefinhas=$_GET["txtcodfuefinhas"];
	   if (($ls_codfuefindes=='')&&($ls_codfuefindes==''))
	   {
		  if($io_function_report->uf_spg_select_fuentefinanciamiento(&$ls_minfuefin,&$ls_maxfuefin))
		  {
		     $ls_codfuefindes=$ls_minfuefin;
		     $ls_codfuefinhas=$ls_maxfuefin;
		  } 
	   }
	   
	  if (($ls_coduniadm_des=='')&&($ls_coduniadm_has==''))
	  {
	   if($io_function_report->uf_spg_select_unidadadministrativa(&$ls_minuniadm,&$ls_maxuniadm))
		  {
		     $ls_coduniadm_des=$ls_minuniadm;
		     $ls_coduniadm_has=$ls_maxuniadm;
		  } 
	  }
//----------------------------------------------------  Parámetros del encabezado  ------------------------------------------------------------------------------------------------------------------------------------------------
		$ls_titulo="<b> UNIDADES EJECUTORAS </b> "; 
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
     $lb_valido=$io_report->uf_spg_reportes_unidades_ejecutoras($ls_codestpro1_min,$ls_codestpro2_min,$ls_codestpro3_min,$ls_codestpro4_min,
	                                                            $ls_codestpro5_min,&$ls_coduniadm_des,&$ls_coduniadm_has,$li_estemireq,
																$ls_ckq_unidad,$ls_codfuefindes,$ls_codfuefinhas,$ls_estclades,
																$ls_estclahas);
	/* if(($ls_coduniadm_des=="")&&($ls_coduniadm_has==""))
	 {
		    $lb_valido=$io_function_report->uf_spg_reporte_select_min_coduniadm($ls_codestpro1_min,$ls_codestpro2_min,$ls_codestpro3_min,
			                                                                    $ls_codestpro4_min,$ls_codestpro5_min,$ls_coduniadm_des);
		    $ls_coduniadm_des=$ls_coduniadm_des;
			if($lb_valido)
			{
               $lb_valido=$io_function_report->uf_spg_reporte_select_max_coduniadm($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
			                                                                       $ls_codestpro4,$ls_codestpro5,$ls_coduniadm_has);			
			} 
		}*/
 	 /////////////////////////////////         SEGURIDAD               ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_programatica_desde=$ls_codestpro1_min.$ls_codestpro2_min.$ls_codestpro3_min.$ls_codestpro4_min.$ls_codestpro5_min;
	 //$ls_programatica_hasta=$ls_codestpro1h.$ls_codestpro2h.$ls_codestpro3h.$ls_codestpro4h.$ls_codestpro5h;
	 
	 $ls_desc_event="Solicitud de Reporte Unidades Ejecutoras para la Programatica  ".$ls_programatica_desde."  , Desde la unidad ejecutora ".$ls_coduniadm_des."  hasta  ".$ls_coduniadm_has;
	 $io_function_report->uf_load_seguridad_reporte("SPG","sigesp_spg_r_unidades_ejecutoras.php",$ls_desc_event);
	 ////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
		$io_pdf->ezSetCmMargins(3,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_tot=$io_report->dts_reporte->getRowCount("coduniadm");
		if($li_tot==0)
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}
		$a=0;
		for($z=1;$z<=$li_tot;$z++)
		{
			if($z==1)
			{
				$ls_anterior="";
			}
			$ls_actual=$io_report->dts_reporte->getValue("programatica",$z);
			$ls_codestpro1=$io_report->dts_reporte->getValue("codestpro1",$z);
			$ls_codestpro2=$io_report->dts_reporte->getValue("codestpro2",$z);
			$ls_codestpro3=$io_report->dts_reporte->getValue("codestpro3",$z);
			$ls_denestpro1=$io_report->dts_reporte->getValue("denestpro1",$z);
			$ls_denestpro2=$io_report->dts_reporte->getValue("denestpro2",$z);
			$ls_denestpro3=$io_report->dts_reporte->getValue("denestpro3",$z);
			if($ls_anterior!=$ls_actual)
			{
				$a++;
				if ($li_estmodest == 2)
				{
				 $la_data[$a]=array('coduniadm'=>" \n ",'denuniadm'=>"\n <b>Estructura Presupuestaria: ".$ls_actual."</b>");
				}
				else
				{
				 $la_data[$a]=array('coduniadm'=>" ",'denuniadm'=>"<b>Estructura Presupuestaria: </b>");
				 $a++;
				 $la_data[$a]=array('coduniadm'=>" ",'denuniadm'=>"".$ls_codestpro1." ".$ls_denestpro1);
				 $a++;
				 $la_data[$a]=array('coduniadm'=>" ",'denuniadm'=>"".$ls_codestpro2." ".$ls_denestpro2);
				 $a++;
				 $la_data[$a]=array('coduniadm'=>"\n",'denuniadm'=>"".$ls_codestpro3." ".$ls_denestpro3);
				 
				}				
			}
			$a++;
			$ls_coduniadm=trim($io_report->dts_reporte->data["coduniadm"][$z]);
			$ls_denominacion=trim($io_report->dts_reporte->data["denuniadm"][$z]);
	
			$la_data[$a]=array('coduniadm'=>$ls_coduniadm."\n",'denuniadm'=>$ls_denominacion);
	 		$ls_anterior=$io_report->dts_reporte->getValue("programatica",$z);
	    }//for
		uf_print_cabecera_detalle($io_pdf);
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		if ((!empty($ls_programatica_next))&&($z<$li_tot))
		{
		 $io_pdf->ezNewPage(); // Insertar una nueva página
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
	}//else
	unset($io_pdf);
	unset($io_report);
	unset($io_funciones);
	unset($io_function_report);
	unset($io_fecha);
?> 