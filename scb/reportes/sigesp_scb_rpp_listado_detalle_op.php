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
	function uf_print_encabezado_pagina($as_titulo,$ad_fecdesde,$ad_fechasta,&$io_pdf)
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
		$io_pdf->line(50,40,790,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=370-($li_tm/2);
		$io_pdf->addText($tm,550,11,$as_titulo); // Agregar el título
		$io_pdf->addText(680,550,10,date("d/m/Y")); // Agregar la Fecha
		if((!empty($ld_fecdesde))&&(!empty($ld_fechasta)))
		{
		$li_tm=$io_pdf->getTextWidth(11,"Periodo ".$ad_fecdesde." - ".$ad_fechasta);
		$tm=370-($li_tm/2);
		$io_pdf->addText($tm,530,11,"Periodo ".$ad_fecdesde." - ".$ad_fechasta);
		}
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
		$la_data1[1]=array('raya'=>'_________________________________________________________________________________________________________________________________________________');
		$la_columna=array('raya'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>740, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
		$la_columna=array('documento'=>'<b>Documento</b>','proveedor'=>'<b>Proveedor</b>','banco'=>'<b>Banco</b>','cuenta'=>'<b>Cuenta</b>','operacion'=>'<b>Operacion</b>','fecha'=>'<b>Fecha</b>','monto'=>'<b>Monto</b>','status'=>'<b>Estatus</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>740, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Justificación y ancho de la columna
						 'cols'=>array('documento'=>array('justification'=>'center','width'=>100),'proveedor'=>array('justification'=>'left','width'=>100),
						 			   'banco'=>array('justification'=>'left','width'=>110),'cuenta'=>array('justification'=>'center','width'=>150),
									   'operacion'=>array('justification'=>'center','width'=>60),'fecha'=>array('justification'=>'center','width'=>70),
									   'monto'=>array('justification'=>'right','width'=>100),'status'=>array('justification'=>'center','width'=>50))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		
	
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_contable($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_contable
		//		   Access: private 
		//	    Arguments: la_data // arreglo con la data a imprimir
		//	    		   io_pdf // objeto pdf
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data2[1]=array('raya'=>'_____________________________________________________________________________________________________________________________________');
		$la_columna2=array('raya'=>'');
		$la_config2=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>740, // Ancho Máximo de la tabla
						 'xOrientation'=>'center',
						 'cols'=>array('raya'=>array('justification'=>'center','width'=>740))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data2,$la_columna2,'',$la_config2);
		$la_columna=array('cuenta'=>'<b>Cuenta</b>','debe'=>'<b>Debe</b>','haber'=>'<b>Haber</b>',
						  'descripcion'=>'<b>Descripción</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.91,0.91,0.91), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
						 'rowGap' => 0.5,
						 'width'=>650, // Ancho de la tabla
						 'maxWidth'=>650, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Justificación y ancho de la columna
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'descripcion'=>array('justification'=>'left','width'=>410))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'<b>Detalle Contabilidad</b>',$la_config);	
	}// end function uf_print_detalle_contable
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_presupuestario($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_presupuestario
		//		   Access: private 
		//	    Arguments: la_data // arreglo con la data a imprimir
		//	    		   io_pdf // objeto pdf
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
		$la_data2[1]=array('raya'=>'_____________________________________________________________________________________________________________________________________');
		$la_columna=array('raya'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>740, // Ancho Máximo de la tabla
						 'xOrientation'=>'center',
						 'cols'=>array('raya'=>array('justification'=>'center','width'=>740))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data2,$la_columna,'',$la_config);
		$la_columna=array('programatica'=>'<b>Programática</b>','cuenta'=>'<b>Cuenta</b>','monto'=>'<b>Monto</b>','descripcion'=>'<b>Descripción</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' =>8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
						 'rowGap' => 0.5,
						 'width'=>650, // Ancho de la tabla
						 'maxWidth'=>650, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Justificación y ancho de la columna
						 'cols'=>array('programatica'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'cuenta'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>80),
									   'descripcion'=>array('justification'=>'left','width'=>340))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'<b>Detalle Presupuestario de Gasto</b>',$la_config);	
	}// end function uf_print_detalle_presupuestario
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
		$la_data[1]=array('raya'=>'_________________________________________________________________________________________________________________________________________________');
		$la_columna=array('raya'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>740, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
		$la_data[1]=array('name'=>'<b>Total Créditos:</b>', 'monto'=>$ad_debitos);
		$la_data[2]=array('name'=>'<b>Total Débitos:</b>', 'monto'=>$ad_creditos);
		$la_data[3]=array('name'=>'<b>Total Saldo:</b>', 'monto'=>$ad_total);
		$la_columna=array('name'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>200, // Ancho de la tabla
						 'maxWidth'=>200, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Justificación y ancho de la columna
						 'xPos'=>680,
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
	require_once("../../shared/class_folder/class_datastore.php");

	$sig_inc	  = new sigesp_include();
	$con		  = $sig_inc->uf_conectar();
	$io_report = new sigesp_scb_class_report($con);
	$ds_dt_scg	  = new class_datastore();
	$ds_dt_spg	  = new class_datastore();
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ld_fecdesde	= $_GET["fecdes"];
	$ld_fechasta	= $_GET["fechas"];
	$ls_codope		= $_GET["codope"];
	$ls_codban		= $_GET["codban"];
	$ls_ctaban		= $_GET["ctaban"];
	$ls_codconcep   = $_GET["codconcep"];
	$ls_orden		= $_GET["orden"];
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

	$ls_titulo="<b>Listado Detallado de Ordenes de Pago Directa $ls_tipbol</b>";
	$io_report->uf_cargar_documentos_op($ls_codope,$ld_fecdesde,$ld_fechasta,$ls_codban,$ls_ctaban,$ls_codconcep,$ls_orden);
	$ldec_totaldebitos=0;
	$ldec_totalcreditos=0;
	$ldec_saldo=0;
	$lb_valido=true;
	$li_total=$io_report->ds_documentos->getRowCount("codban");
	if($li_total>0)
	{
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('A4','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ld_fecdesde,$ld_fechasta,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(750,50,10,'','',1); // Insertar el número de página
		for($i=1;$i<=$li_total;$i++)
		{
	        $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag	  = $io_pdf->ezPageCount; // Número de página
			$ls_nomban	  = $io_report->ds_documentos->getValue("nomban",$i);
			$ls_numdoc	  = $io_report->ds_documentos->getValue("numdoc",$i);
			$ls_codban	  = $io_report->ds_documentos->getValue("codban",$i);
			$ls_ctaban	  = $io_report->ds_documentos->getValue("ctaban",$i);
			$ldec_monto   = $io_report->ds_documentos->getValue("monto",$i);
			$ld_fecmov    =	$io_report->ds_documentos->getValue("fecmov",$i);
			$ld_fecmov	  = $io_report->fun->uf_convertirfecmostrar($ld_fecmov);
			$ls_nomproben = $io_report->ds_documentos->getValue("nomproben",$i);
			$ls_codope    = $io_report->ds_documentos->getValue("codope",$i);
			$ls_conmov    = $io_report->ds_documentos->getValue("conmov",$i);
			$ls_estmov    = $io_report->ds_documentos->getValue("estmov",$i);
			if(strlen($ls_conmov)>48)
			{
				$ls_conmov=substr($ls_conmov,0,46)."..";
			}
			if($ls_codope=="OP")
			{
				$ldec_totaldebitos=$ldec_totaldebitos+$ldec_monto;				
			}			
			$ld_mon=number_format($ldec_monto,2,",",".");
			$la_data[$i]=array('documento'=>$ls_numdoc,'proveedor'=>$ls_nomproben,'banco'=>$ls_nomban,'cuenta'=>$ls_ctaban,'operacion'=>$ls_codope,'fecha'=>$ld_fecmov,'monto'=>$ld_mon,'status'=>$ls_estmov);
			
			uf_print_detalle($la_data,&$io_pdf);	
			//Obtengo el detalle contable del movimiento.
			unset($la_data);
			unset($ds_dt_scg->data);
			$ds_dt_scg->data=$io_report->uf_cargar_dt_scg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope,$ls_estmov);
			$li_totscg=$ds_dt_scg->getRowCount("scg_cuenta");
			if($li_totscg>0)
			{
				for($li_a=1;$li_a<=$li_totscg;$li_a++)
				{
					$ls_debhab=$ds_dt_scg->getValue("debhab",$li_a);
					if($ls_debhab=="D")
					{
						$ldec_mondeb=number_format($ds_dt_scg->getValue("monto",$li_a),2,",",".");
						$ldec_monhab="";
					}
					else
					{
						$ldec_monhab=number_format($ds_dt_scg->getValue("monto",$li_a),2,",",".");
						$ldec_mondeb="";
					}
					$la_data[$li_a]=array('cuenta'=>$ds_dt_scg->getValue("scg_cuenta",$li_a),'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab,
										  'descripcion'=>$ds_dt_scg->getValue("desmov",$li_a));
				}
				uf_print_detalle_contable($la_data,$io_pdf);				
			}
			unset($la_data);
			//Obtengo el detalle presupuestario del movimiento.
			unset($ds_dt_spg->data);
			$ds_dt_spg->data=$io_report->uf_cargar_dt_spg_op($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope,$ls_estmov);
			$li_totspg=$ds_dt_spg->getRowCount("spg_cuenta");
			if($li_totspg>0)		
			{
				for($li_b=1;$li_b<=$li_totspg;$li_b++)
				{
					$la_data[$li_b]=array('programatica'=>$ds_dt_spg->getValue("estpro",$li_b),
										  'cuenta'=>$ds_dt_spg->getValue("spg_cuenta",$li_b),
										  'monto'=>number_format($ds_dt_spg->getValue("monto",$li_b),2,",","."),
										  'descripcion'=>$ds_dt_spg->getValue("desmov",$li_b));
				}				
				uf_print_detalle_presupuestario($la_data,$io_pdf);				
			}
			unset($la_data);
		}
		$ldec_saldo=$ldec_totalcreditos-$ldec_totaldebitos;//Calculo del saldo total para todas las cuentas
		$ldec_totalcreditos=number_format($ldec_totalcreditos,2,",",".");
		$ldec_totaldebitos=number_format($ldec_totaldebitos,2,",",".");
		$ldec_saldo=number_format($ldec_saldo,2,",",".");
		uf_print_totales($ldec_totaldebitos,$ldec_totalcreditos,$ldec_saldo,&$io_pdf);
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			//print(" close();");
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