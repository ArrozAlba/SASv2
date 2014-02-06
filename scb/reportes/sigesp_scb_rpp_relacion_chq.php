<?php
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "opener.document.form1.submit();";
		print "close();";
		print "</script>";		
	}
	ini_set('memory_limit','2048M');
	ini_set('max_execution_time ','0');	
	//--------------------------------------------------------------------------------------------------------------------------------	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$ad_fecdes,$ad_fechas,$ls_denban,$ls_ctaban,$ls_dencta,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,550,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,770,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		
		$io_pdf->addText($tm,775,12,$as_titulo); // Agregar el título
	    $io_pdf->addText(225,760,10,'<b>Desde: </b>'.$ad_fecdes.'<b> - Hasta: </b>'.$ad_fechas); // Agregar el título
		$io_pdf->Rectangle(177,747,270,45);
		$io_pdf->addText(45,722,10,'<b> Banco:</b>  '.$ls_denban); // Agregar la Fecha
		$io_pdf->addText(45,710,10,'<b>Cuenta:</b>  '.$ls_ctaban."  -  ".$ls_dencta); // Agregar la Fecha
		$io_pdf->addText(540,820,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(546,814,6,date("h:i a")); // Agregar la Hora
		
		$io_pdf->ezSetDy(-40);
		$io_pdf->setStrokeColor(0,0,0);
		$la_data[1]=array('titulo'=>'<c:uline><b>Detalle Cheques</b></c:uline>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xPos'=>305, // Orientación de la tabla
				         'outerLineThickness'=>1,
						 'innerLineThickness' =>1,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: $la_data
		//	    		   io_pdf // 
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$io_pdf->ezSetY(700);
		$io_pdf->ezSetCmMargins(5,3,3,3);
		$la_columna=array('fecha'=>'<b>Fecha</b>','documento'=>'<b>Documento</b>','proveedor'=>'<b>Proveedor/Beneficiario</b>','monto'=>'<b>Monto</b>','estmov'=>'<b>Estatus</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
 						 'shadeCol'=>array(0.95,0.95,0.95), // Color de la sombra
						 'shadeCol2'=>array(1.5,1.5,1.5), // Color de la sombra
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>740, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Justificación y ancho de la columna
						 'cols'=>array('fecha'=>array('justification'=>'center','width'=>70),'documento'=>array('justification'=>'center','width'=>90),
						 			   'proveedor'=>array('justification'=>'left','width'=>170),'monto'=>array('justification'=>'right','width'=>110),
									   'estmov'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$io_pdf->ezSetCmMargins(3.5,3,3,3);		
	
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($ad_debitos,$ad_creditos,$ad_total/*,$ad_subtotori,$ad_subtotanu*/,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: as_numdoc // Número del documento
		//	    		   as_conmov // concepto del documento
		//	    		   as_nomproben // nombre del proveedor beneficiario
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
		$la_data[1]=array('name'=>'<b>Total Cheques:</b>', 'monto'=>$ad_creditos);
		$la_data[2]=array('name'=>'<b>Total Anulados:</b>', 'monto'=>$ad_debitos);
		$la_data[3]=array('name'=>'<b>Total Saldo:</b>', 'monto'=>$ad_total);
		
		$la_columna=array('name'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>200, // Ancho de la tabla
						 'maxWidth'=>200, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Justificación y ancho de la columna
						 'xPos'=>460,
						 'cols'=>array('name'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("sigesp_scb_class_report.php");

	$sig_inc   = new sigesp_include();
	$con       = $sig_inc->uf_conectar();
	$io_report = new sigesp_scb_class_report($con);
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
    $lb_valido      = true;
	$ls_codban      = $_GET["codban"];
	$ls_ctaban      = $_GET["ctaban"];
	$ls_denban      = $_GET["hidnomban"];
	$ls_dencta      = $_GET["dencta"];
	$ls_documentos  = $_GET["documentos"];
	$ls_fechas      = $_GET["fechas"];
	$ld_fecdes      = $_GET["fecdesde"];
	$ld_fechas      = $_GET["fechasta"];
	$ls_operaciones = $_GET["operaciones"];
	$ls_tipbol      = 'Bs.';
	$ls_tiporeporte = 0;
	$ls_tiporeporte = $_GET["tiporeporte"];
	global $ls_tiporeporte;
	if ($ls_tiporeporte==1)
	   {
		 require_once("sigesp_scb_class_reportbsf.php");
		 $io_report = new sigesp_scb_class_reportbsf($con);
		 $ls_tipbol = 'Bs.F.';
	   }
	$ls_titulo      = "<b>Relación de Cheques $ls_tipbol</b>";
	//Descompongo la cadena de documentos en un arreglo tomando como separación el ','
	$arr_documentos = split(",",$ls_documentos);
	$li_totdoc		= count($arr_documentos);
	//Descompongo la cadena de documentos en un arreglo tomando como separación el '-'
	$arr_fecmov = split("-",$ls_fechas);
	$li_totfec  = count($arr_fecmov);
   //Descompongo la cadena de documentos en un arreglo tomando como separación el '-'
	$arr_operaciones = split("-",$ls_operaciones);
	$li_totdoc	= count($arr_operaciones);
	$io_report->uf_cargar_documentos_relacion($arr_documentos,$arr_fecmov,$arr_operaciones,$ls_codban,$ls_ctaban);
	$li_total   = $io_report->ds_documentos->getRowCount("numdoc");
	if ($li_total>0)
	   {
	     $ld_totdeb  = 0;
	     $ld_totcre  = 0;
	     $ldec_saldo = 0;
	     error_reporting(E_ALL);
		 set_time_limit(1800);
		 $io_pdf=new Cezpdf('A4','portrait'); // Instancia de la clase PDF
		 $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		 $io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		 uf_print_encabezado_pagina($ls_titulo,$ld_fecdes,$ld_fechas,$ls_denban,$ls_ctaban,$ls_dencta,$io_pdf); // Imprimimos el encabezado de la página
		 $io_pdf->ezStartPageNumbers(750,50,10,'','',1); // Insertar el número de página
		 $io_pdf->transaction('start'); // Iniciamos la transacción
		 $li_numpag	= $io_pdf->ezPageCount; // Número de página
		 for ($i=1;$i<=$li_total;$i++)
		     {
			   $ls_numdoc	 = $io_report->ds_documentos->getValue("numdoc",$i);
			   $ldec_monto   = $io_report->ds_documentos->getValue("monto",$i);
			   $ld_fecmov	 = $io_report->ds_documentos->getValue("fecmov",$i);
			   $ld_fecmov	 = $io_report->fun->uf_convertirfecmostrar($ld_fecmov);
			   $ls_nomproben = $io_report->ds_documentos->getValue("nomproben",$i);
			   $ls_estmov	 = $io_report->ds_documentos->getValue("estmov",$i);
		       switch($ls_estmov){
		   		  case 'C':
			 		$ls_estmov = 'Contabilizado';//Haber
				    $ld_totcre = ($ld_totcre+$ldec_monto);
		   		  break;
			      case 'N':
				    $ls_estmov = 'No Contabilizado';//Haber
				    $ld_totcre = ($ld_totcre+$ldec_monto);
			      break;
			      case 'L':
				    $ls_estmov = 'No Contabilizable';//Haber
				    $ld_totcre = ($ld_totcre+$ldec_monto);
			      break;
			      case 'A':
				    $ls_estmov = 'Anulado';//Debe
				    $ld_totdeb = ($ld_totdeb+$ldec_monto);
			      break;					
			      case 'O':
				    $ls_estmov = 'Original';//Haber
				    $ld_totcre = ($ld_totcre+$ldec_monto);
			      break;										
		       }	
		       $ld_mon		= number_format($ldec_monto,2,",",".");
		       $la_data[$i] = array('fecha'=>$ld_fecmov,'documento'=>$ls_numdoc,'proveedor'=>$ls_nomproben,'monto'=>$ld_mon,'estmov'=>$ls_estmov);
	         }	   
		  uf_print_detalle($la_data,&$io_pdf);
		  $ldec_saldo = $ld_totcre-$ld_totdeb;//Calculo del saldo total para todas las cuentas
		  $ld_totcre  = number_format($ld_totcre,2,",",".");
		  $ld_totdeb  = number_format($ld_totdeb,2,",",".");
		  $ldec_saldo = number_format($ldec_saldo,2,",",".");
		  uf_print_totales($ld_totdeb,$ld_totcre,$ldec_saldo,&$io_pdf);
		  if ($lb_valido) // Si no ocurrio ningún error
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
		  unset($io_pdf);
	   }
	else
	   {
		 print("<script language=JavaScript>");
		 print(" alert('No hay nada que Reportar');"); 
		 print(" close();");
		 print("</script>");
	   }
?> 