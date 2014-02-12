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
	function uf_print_encabezado_pagina($as_titulo,$ls_titulo_estatus,$ls_fecdesde,$ls_fechasta,$ls_denban,$ls_ctaban,$ls_dencta,&$io_pdf)
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
		$io_pdf->line(40,40,550,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],40,770,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,780,11,$as_titulo); // Agregar el título
		$io_pdf->addText(510,780,9,date("d/m/Y")); // Agregar la Fecha
		$li_tm=$io_pdf->getTextWidth(11,$ls_titulo_estatus);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,768,10,$ls_titulo_estatus); // Agregar el título
		$io_pdf->addText(40,758,9,"Desde ".$ls_fecdesde." hasta ".$ls_fechasta) ; // Agregar la Fecha
		$io_pdf->addText(40,748,9,'<b>Banco:</b>'.$ls_denban); // Agregar la Fecha
		$io_pdf->addText(40,738,9,'<b>Cuenta:</b>'.$ls_ctaban."   ".$ls_dencta); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		$io_pdf->ezSetY(715);
		$la_columna=array('fecha'=>'<b>Fecha</b>','documento'=>'<b>Documento</b>','operacion'=>'<b>Operacion</b>','proveedor'=>'<b>Proveedor</b>','monto'=>'<b>Monto</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
					 'fontSize' => 9, // Tamaño de Letras
					 'showLines'=>0, // Mostrar Líneas
					 'shaded'=>2, // Sombra entre líneas
					 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
					 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
					 'width'=>740, // Ancho de la tabla
					 'maxWidth'=>740, // Ancho Máximo de la tabla
					 'xOrientation'=>'center', // Justificación y ancho de la columna
					 'cols'=>array('fecha'=>array('justification'=>'center','width'=>70),'documento'=>array('justification'=>'center','width'=>100),
								   'operacion'=>array('justification'=>'center','width'=>60),'proveedor'=>array('justification'=>'left','width'=>170),
								   'monto'=>array('justification'=>'right','width'=>130))); // Justificación y ancho de la columna
		$la_data[1]=array('fecha'=>'<b>Fecha</b>','documento'=>'<b>Documento</b>','operacion'=>'<b>Operacion</b>','proveedor'=>'<b>Proveedor</b>','monto'=>'<b>Monto</b>');
		$la_data[2]=array('fecha'=>'<b>Concepto</b>','documento'=>' ','operacion'=>'  ','proveedor'=>'   ','monto'=>' '  );
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data_con,$la_columna_con,$la_config_con,&$io_pdf)
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
		
		$io_pdf->ezTable($la_data_con,$la_columna_con,'',$la_config_con);			
	
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($ad_debitos,$ad_creditos,$ad_total,&$io_pdf)
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
		$la_data[1]=array('name'=>'<b>Total Créditos:</b>', 'monto'=>$ad_debitos);
		$la_data[2]=array('name'=>'<b>Total Débitos:</b>', 'monto'=>$ad_creditos);
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
	require_once("sigesp_scb_class_report.php");
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	$sig_inc	  = new sigesp_include();
	$con		  = $sig_inc->uf_conectar();
	$io_report = new sigesp_scb_class_report($con);
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_fecdesde = $_GET["fecdesde"];
	$ls_fechasta = $_GET["fechasta"];
	$ls_codban   = $_GET["codban"];
	$ls_ctaban   = $_GET["ctaban"];
	$ls_denban   = $_GET["denban"];
	$ls_dencta   = $_GET["dencta"];
	$ls_codope   = $_GET["codope"];
	$ls_estatus  = $_GET["estatus"];
	$ls_orden    = $_GET["orden"];
	if($ls_estatus!="")
	{
		$arr_estatus=split("-",$ls_estatus);
		$li_totestatus=count($arr_estatus);
	}	
	else
	{
		$li_totestatus=0;
	}
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
	$ls_aux="";
	$ls_titulo_estatus="";
	if($li_totestatus>0)
	{
		for($li_x=0;$li_x<$li_totestatus;$li_x++)
		{
			if($ls_aux=="")
			{
				$ls_aux=" AND (estmov='".$arr_estatus[$li_x]."'";	
			}
			else
			{
				$ls_aux=$ls_aux." OR estmov='".$arr_estatus[$li_x]."'";	
			}
			switch($arr_estatus[$li_x]){
				case 'C':
					$ls_titulo_estatus="Contabilizados";
					break;
				case 'N':
					if($ls_titulo_estatus!="")
					{
						$ls_titulo_estatus=$ls_titulo_estatus.",No Contabilizados";
					}
					else
					{
						$ls_titulo_estatus="No Contabilizados";
					}
					break;
				case 'A':
					if($ls_titulo_estatus!="")
					{
						$ls_titulo_estatus=$ls_titulo_estatus.",Anulados";
					}
					else
					{
						$ls_titulo_estatus="Anulados";
					}
					break;		
			}
		}
	}
	if($ls_aux!="")
	{
		$ls_aux=$ls_aux." )";	
	}
	if($ls_codope!="T")
	{
		$ls_aux=$ls_aux." AND codope='".$ls_codope."' ";
	}
	$ls_titulo="<b>Listado de Documentos Conciliados $ls_tipbol</b>";
	$io_report->uf_cargar_documentos_conciliados($ls_fecdesde,$ls_fechasta,$ls_codban,$ls_ctaban,$ls_aux,$ls_orden);
	$ldec_totaldebitos=0;
	$ldec_totalcreditos=0;
	$ldec_saldo=0;
	$lb_valido=true;
	$li_total=$io_report->ds_documentos->getRowCount("codban");
	if($li_total>0)
	{
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('A4','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_titulo_estatus,$ls_fecdesde,$ls_fechasta,$ls_denban,$ls_ctaban,$ls_dencta,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(750,50,10,'','',1); // Insertar el número de página
		for($i=1;$i<=$li_total;$i++)
		{
	        $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag	  = $io_pdf->ezPageCount; // Número de página
			$ls_numdoc	  = $io_report->ds_documentos->getValue("numdoc",$i);
			$ls_ctaban	  = $io_report->ds_documentos->getValue("ctaban",$i);
			$ldec_monto	  = $io_report->ds_documentos->getValue("monto",$i);
			$ld_fecmov	  = $io_report->ds_documentos->getValue("fecmov",$i);
			$ld_fecmov	  = $io_report->fun->uf_convertirfecmostrar($ld_fecmov);
			$ls_nomproben = $io_report->ds_documentos->getValue("nomproben",$i);
			$ls_codope    = $io_report->ds_documentos->getValue("codope",$i);
			$ls_conmov    = $io_report->ds_documentos->getValue("conmov",$i);
			$ls_estmov    = $io_report->ds_documentos->getValue("estmov",$i);
			if(strlen($ls_conmov)>48)
			{
				$ls_conmov=substr($ls_conmov,0,46)."..";
			}
			if((($ls_codope=="CH")||($ls_codope=="ND")||($ls_codope=="RE")))
			{
				$ldec_totaldebitos=$ldec_totaldebitos+$ldec_monto;				
			}
			////Acumuladores de movimientos que generan un crédito.
			if((($ls_codope=="DP")||($ls_codope=="NC")))
			{
				$ldec_totalcreditos=$ldec_totalcreditos+$ldec_monto;
			}
			$ld_mon=number_format($ldec_monto,2,",",".");
			$la_columna=array('fecha'=>'<b>Fecha</b>','documento'=>'<b>Documento</b>','operacion'=>'<b>Operacion</b>','proveedor'=>'<b>Proveedor</b>','monto'=>'<b>Monto</b>');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
 						 'shadeCol2'=>array(0.95,0.95,0.95), // Color de la sombra
						 'shadeCol'=>array(1.5,1.5,1.5), // Color de la sombra
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>740, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Justificación y ancho de la columna
						 'cols'=>array('fecha'=>array('justification'=>'center','width'=>70),'documento'=>array('justification'=>'center','width'=>100),
						 			   'operacion'=>array('justification'=>'center','width'=>60),'proveedor'=>array('justification'=>'left','width'=>170),
						 			   'monto'=>array('justification'=>'right','width'=>130))); // Justificación y ancho de la columna
			$la_data[1]=array('fecha'=>$ld_fecmov,'documento'=>$ls_numdoc,'operacion'=>$ls_codope,'proveedor'=>$ls_nomproben,'monto'=>$ld_mon);
			uf_print_detalle($la_data,$la_columna,$la_config,$io_pdf);
			unset($la_data);
			$la_data_con[1]=array('concepto'=>$ls_conmov." ");
			$la_columna_con=array('concepto'=>' ');
			$la_config_con=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
 						 'shadeCol'=>array(0.95,0.95,0.95), // Color de la sombra
						 'shadeCol2'=>array(1.5,1.5,1.5), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560,
						 'fontSize'=>6,
						 'cols'=>array('concepto'=>array('justification'=>'left','width'=>540))); // Ancho Máximo de la tabla
			uf_print_detalle($la_data_con,$la_columna_con,$la_config_con,&$io_pdf);
		}
		$ldec_saldo=$ldec_totalcreditos-$ldec_totaldebitos;//Calculo del saldo total para todas las cuentas
		$ldec_totalcreditos=number_format($ldec_totalcreditos,2,",",".");
		$ldec_totaldebitos=number_format($ldec_totaldebitos,2,",",".");
		$ldec_saldo=number_format($ldec_saldo,2,",",".");
		uf_print_totales($ldec_totaldebitos,$ldec_totalcreditos,$ldec_saldo,$io_pdf);
		if($lb_valido) // Si no ocurrio ningún error
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
		//print(" close();");
		print("</script>");
	}
?> 