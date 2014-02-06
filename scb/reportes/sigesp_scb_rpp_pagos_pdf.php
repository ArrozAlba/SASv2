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
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf,$ls_tipproben,$ls_tiprep,$ls_probendesde,$ls_probenhasta,$ld_fecdesde,$ld_fechasta,$ls_nomban,$ls_ctaban,$as_tipbol)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(20,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],40,515,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		if($ls_tiprep=='E')
		{
			$as_titulo=$as_titulo."Especificos";
		}
		$as_titulo = $as_titulo.' '.$as_tipbol;
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=370-($li_tm/2);
		$io_pdf->addText($tm,570,15,"<b>".$as_titulo."</b>"); // Agregar el título
		$io_pdf->addText(670,580,10,date("d/m/Y")); // Agregar la Fecha
		$ls_fechas="<b>Fechas :</b>     ".$ld_fecdesde." <b>Hasta</b> ".$ld_fechasta;
		$li_tm=$io_pdf->getTextWidth(9,$ls_fechas);
		$tm=306-($li_tm/2);
		$io_pdf->addText(320,555,9,$ls_fechas); // Agregar el título
		if($ls_tipproben=='P')
		{
			$ls_den="<b>Proveedor:</b> ".$ls_probendesde." <b>al</b> ".$ls_probenhasta;
			$io_pdf->addText(320,540,9,$ls_den); // Agregar el título
		}
		if($ls_tipproben=='B')
		{
			$ls_den="<b>Beneficiario:</b> ".$ls_probendesde." al ".$ls_probenhasta;
			$io_pdf->addText(320,540,9,$ls_den); // Agregar el título
		}
		if(($ls_nomban!="")&&($ls_ctaban!=""))		
		{
			$ls_den="<b>BANCO :</b>       ".$ls_nomban."                                  <b>CUENTA: </b>".$ls_ctaban;
			$io_pdf->addText(260,525,9,$ls_den); // Agregar el título	
		}
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,$as_tiprep,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: as_numdoc // Número del documento
		//	    		   as_conmov // concepto del documento
		//	    		   as_nomproben // nombre del proveedor beneficiario
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		if ($as_tiprep=='E') 
		   {
		     $la_columna=array('beneficiario'=>'<b>Proveedor \ Benef.</b>','solicitud'=>'<b>Solicitud</b>', 'conmov'=>'<b>Concepto</b>',
						       'documento'=>'<b>Documento</b>','operacion'=>'<b>Operación</b>', 'fecha'=>'<b>Fecha</b>','monto'=>'<b>Monto</b>','monret'=>'<b>Retenido</b>');
				$la_config=array('showHeadings'=>1, // Mostrar encabezados
								 'fontSize' => 8, // Tamaño de Letras
								 'showLines'=>0, // Mostrar Líneas
								 'shaded'=>2, // Sombra entre líneas
								 'shadeCol2'=>array(0.95,0.95,0.95), // Color de la sombra
								 'shadeCol'=>array(1.5,1.5,1.5), // Color de la sombra
								 'width'=>580, // Ancho de la tabla
								 'maxWidth'=>580, // Ancho Máximo de la tabla
								 'xOrientation'=>'center', // Justificación y ancho de la columna
								 'cols'=>array('beneficiario'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la columna
											   'solicitud'=>array('justification'=>'center','width'=>77), // Justificación y ancho de la columna
											   'conmov'=>array('justification'=>'center','width'=>130),
											   'documento'=>array('justification'=>'center','width'=>77), // Justificación y ancho de la columna
											   'operacion'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
											   'fecha'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
											   'monto'=>array('justification'=>'right','width'=>80),
											   'monret'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		   }
		else
		   {
		     $la_columna=array('beneficiario'=>'<b>Proveedor \ Benef.</b>','solicitud'=>'<b>Solicitud</b>', 'cuenta'=>'<b>Cuenta</b>',
						       'documento'=>'<b>Documento</b>','operacion'=>'<b>Operación</b>', 'fecha'=>'<b>Fecha</b>','monto'=>'<b>Monto</b>','monret'=>'<b>Retenido</b>');
			 $la_config=array('showHeadings'=>1, // Mostrar encabezados
				 'fontSize' => 8, // Tamaño de Letras
				 'showLines'=>0, // Mostrar Líneas
				 'shaded'=>2, // Sombra entre líneas
				 'shadeCol2'=>array(0.95,0.95,0.95), // Color de la sombra
				 'shadeCol'=>array(1.5,1.5,1.5), // Color de la sombra
				 'width'=>580, // Ancho de la tabla
				 'maxWidth'=>580, // Ancho Máximo de la tabla
				 'xOrientation'=>'center', // Justificación y ancho de la columna
				 'cols'=>array('beneficiario'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la columna
							   'solicitud'=>array('justification'=>'center','width'=>77), // Justificación y ancho de la columna
							   'cuenta'=>array('justification'=>'center','width'=>130),
							   'documento'=>array('justification'=>'center','width'=>77), // Justificación y ancho de la columna
							   'operacion'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
							   'fecha'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
							   'monto'=>array('justification'=>'right','width'=>80),
							   'monret'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna

		   }
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($ad_total,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: ad_total // monto total 
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('name'=>'_________________________________________________________________________________________________________________________________________');
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('name'=>'<b>Total:</b>', 'monto'=>$ad_total);
		$la_columna=array('name'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Justificación y ancho de la columna
						 'cols'=>array('name'=>array('justification'=>'right','width'=>440), // Justificación y ancho de la columna
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
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/sigesp_include.php");

	$sig_inc   = new sigesp_include();
	$con       = $sig_inc->uf_conectar();
	$io_report = new sigesp_scb_class_report($con);
	$io_sql    = new class_sql($con);
	$ls_titulo = "Listado de Pagos ";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ld_fecdesde    = $_GET["fecdes"];
	$ld_fechasta    = $_GET["fechas"];
	$ls_tiprep	    = $_GET["tiprep"];
	$ls_orden	    = $_GET["orden"];
	$ls_codope	    = $_GET["operacion"];
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
	if($ls_tiprep=="E")
	{
		$ls_probendesde = $_GET["probendes"];
		$ls_probenhasta = $_GET["probenhas"];
		$ls_tipproben   = $_GET["tipproben"];
		$ls_codban      = $_GET["codban"];
		$ls_ctaban      = $_GET["ctaban"];
		$ls_nomban		= $_GET["nomban"];
		$ls_dencta      = $_GET["dencta"];
	}
	else
	{
		$ls_probendesde="";
		$ls_probenhasta="";
		$ls_tipproben="";
		$ls_codban="";
		$ls_ctaban="";
		$ls_nomban="";
		$ls_dencta="";
	}
	$rs_data    = $io_report->uf_find_pagos($ls_tipproben,$ls_probendesde,$ls_probenhasta,$ld_fecdesde,$ld_fechasta,$ls_codban,$ls_ctaban,$ls_tiprep,$ls_orden,$ls_codope);
	$ldec_total = 0;
	$lb_valido  = true;
	$li_total   = $io_sql->num_rows($rs_data);
	if($li_total>0)
	{
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf,$ls_tipproben,$ls_tiprep,$ls_probendesde,$ls_probenhasta,$ld_fecdesde,$ld_fechasta,$ls_nomban,$ls_ctaban,$ls_tipbol); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(735,50,10,'','',1); // Insertar el número de página
		$i=0;
		$ld_acumulador =0;$ld_monto_anulado =0;
		while ($row=$io_sql->fetch_row($rs_data))
		      {
			    $i++;
				$ls_numdoc     = $row["numdoc"];
				$ls_ctaban	   = $row["ctaban"];
				$ldec_monto	   = $row["monto"];
				$ld_acumulador = ($ld_acumulador+$ldec_monto);
				$ldec_monsol   = $row["monsol"];
				$ld_fecmov	   = $io_report->fun->uf_formatovalidofecha($row["fecmov"]);
				$ld_fecmov	   = $io_report->fun->uf_convertirfecmostrar($ld_fecmov);
				$ls_nomproben  = $row["nomproben"];
				$ls_numsol     = $row["numsol"];
				$ls_conmov	   = $row["conmov"];
				$ls_tipoope	   = $row["codope"];
				$ls_estbpd	   = $row["estbpd"];
				$ls_estmov     = $row["estmov"];
			    $ld_monret     = $row["monret"];
				if ($ls_estmov=="A")
		 	       {
			 	     $ld_monto_anulado = ($ld_monto_anulado+$ldec_monto);
					 $ldec_monto=$ldec_monto * (-1);
			       }
			    if ($ls_tipoope=="CH")
				     $ls_tipoope="CHEQUE";
			    elseif(($ls_tipoope=="ND") && ($ls_estbpd=="T"))
				    $ls_tipoope="CARTA ORDEN";
			    else
				    $ls_tipoope="NOTA DE DEBITO";
				$ldec_total=$ldec_total+$ldec_monto;	
				if (strlen($ls_conmov)>48)
			       {
				     $ls_conmov=substr($ls_conmov,0,46)."..";
			       }
			    $la_data[$i]=array('beneficiario'=>$ls_nomproben,'solicitud'=>$ls_numsol,'cuenta'=>$ls_ctaban,'documento'=>$ls_numdoc,'operacion'=>$ls_tipoope,
							       'fecha'=>$ld_fecmov,'monto'=>number_format($ldec_monto,2,",","."),'monret'=>number_format($ld_monret,2,",","."),'conmov'=>$ls_conmov);
		      }
		uf_print_detalle($la_data,$ls_tiprep,&$io_pdf);
		$ldec_total=number_format($ldec_total,2,",",".");
		uf_print_totales($ldec_total,&$io_pdf);
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
		print(" close();");
		print("</script>");
	}
?> 