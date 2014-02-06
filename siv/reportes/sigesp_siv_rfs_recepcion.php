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
	function uf_print_encabezado_pagina($as_titulo,$as_numconrec,$ad_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_numconrec // numero de recepcion
		//	    		   ad_fecha // Fecha 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->rectangle(420,710,130,40);
		$io_pdf->line(420,730,550,730);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$io_pdf->addText(423,735,11,"No.:");      // Agregar texto
		$io_pdf->addText(457,735,11,$as_numconrec); // Agregar Numero de la solicitud
		$io_pdf->addText(423,715,10,"Fecha:"); // Agregar texto
		$io_pdf->addText(457,715,10,$ad_fecha); // Agregar la Fecha
		$io_pdf->addText(510,760,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(516,753,7,date("h:i a")); // Agregar la Hora
		// cuadro inferior
        $io_pdf->Rectangle(50,40,500,70);
		$io_pdf->line(50,53,550,53);		
		$io_pdf->line(50,97,550,97);		
		$io_pdf->line(130,40,130,110);		
		$io_pdf->line(240,40,240,110);		
		$io_pdf->line(380,40,380,110);		
		$io_pdf->addText(60,102,7,"ELABORADO POR"); // Agregar el título
		$io_pdf->addText(70,43,7,"ALMACÉN"); // Agregar el título
		$io_pdf->addText(157,102,7,"VERIFICADO POR"); // Agregar el título
		$io_pdf->addText(160,43,7,"PRESUPUESTO"); // Agregar el título
		$io_pdf->addText(280,102,7,"AUTORIZADO POR"); // Agregar el título
		//$io_pdf->addText(257,43,7,"ADMINISTRACIÓN Y FINANZAS"); // Agregar el título
		$io_pdf->addText(440,102,7,"PROVEEDOR"); // Agregar el título
		$io_pdf->addText(405,43,7,"FIRMA AUTOGRAFA, SELLO, FECHA"); // Agregar el título
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_numconrec,$as_numordcom,$as_codpro,$as_denpro,$as_codalm,$as_nomfisalm,$as_obsrec,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_numconrec // numero consecutivo de recepcion
		//	    		   as_numordcom // Numero de la orden de conpra/factura
		//	    		   as_codpro    // codigo del proveedor
		//	    		   as_denpro    // denominacion del proveedor
		//	    		   as_codalm    // codigo de almacen
		//	    		   as_nomfisalm // nombre fiscal de almacen
		//	    		   as_obsrec    // observaciones de la recepcion
		//	    		   io_pdf       // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>Ord. de Compra/Factura:</b>  '.$as_numordcom.''),
					   array ('name'=>'<b>Almacén:</b>                            '.$as_codalm." - ".$as_nomfisalm.''),
					   array ('name'=>'<b>Proveedor</b>                          '.$as_codpro." - ".$as_denpro.''),
					   array ('name'=>'<b>Observaciones:</b>                 '.$as_obsrec.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar Líneas
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_columna=array('articulo'=>'<b>Artículo</b>',
						  'unidad'=>'<b>Unidad</b>',
						  'cantidad'=>'<b>Cantidad</b>',
						  'pendiente'=>'<b>Pendiente</b>',
						  'precio'=>'<b>Costo Unitario</b>',
						  'total'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('articulo'=>array('justification'=>'left','width'=>160), // Justificación y ancho de la columna
						 			   'unidad'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'cantidad'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'pendiente'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'precio'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('total'=>'',
						  'totcan'=>'',
						  'totpen'=>'',
						  'vacio'=>'',
						  'totmon'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>220), // Justificación y ancho de la columna
						 			   'totcan'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'totpen'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'vacio'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'totmon'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_siv_class_report.php");
	$io_report=new sigesp_siv_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_funciones_inventario.php");
	$io_fun_inventario=new class_funciones_inventario();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_fecrec=$io_fun_inventario->uf_obtenervalor_get("fecrec","");

	$ls_titulo="<b>Entrada de Suministros a Almacén</b>";
	$ls_fecha=$ls_fecrec;
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_numconrec=$io_fun_inventario->uf_obtenervalor_get("numconrec","");
	$ls_numordcom=$io_fun_inventario->uf_obtenervalor_get("numordcom","");
	$ls_codpro=$io_fun_inventario->uf_obtenervalor_get("codpro","");
	$ls_denpro=$io_fun_inventario->uf_obtenervalor_get("denpro","");
	$ls_codalm=$io_fun_inventario->uf_obtenervalor_get("codalm","");
	$ls_nomfisalm=$io_fun_inventario->uf_obtenervalor_get("denalm","");
	$ls_obsrec=$io_fun_inventario->uf_obtenervalor_get("obsrec","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_select_recepcion($ls_codemp,$ls_numconrec,"","",""); // Cargar el DS con los datos de la cabecera del reporte
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
		$io_pdf->ezSetCmMargins(3.5,4,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_numconrec,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la página
		$li_totrow=1;//$io_report->DS->getRowCount("codper");
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
	        $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$li_totcan=0;
			$li_totpen=0;
			$li_total=0;
			$ls_numconrec=$io_report->ds->data["numconrec"][$li_i];
			$ls_numordcom=$io_report->ds->data["numordcom"][$li_i];
			$ls_codpro=$io_report->ds->data["cod_pro"][$li_i];
			$ls_denpro=$io_report->ds->data["nompro"][$li_i];
			$ls_codalm=$io_report->ds->data["codalm"][$li_i];
			$ls_nomfisalm=$io_report->ds->data["nomfisalm"][$li_i];
			$ls_obsrec=$io_report->ds->data["obsrec"][$li_i];
			uf_print_cabecera($ls_numconrec,$ls_numordcom,$ls_codpro,$ls_denpro,$ls_codalm,$ls_nomfisalm,$ls_obsrec,$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_select_dt_recepcion($ls_codemp,$ls_numordcom,$ls_numconrec); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->ds_detalle->getRowCount("codart");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_codart=     $io_report->ds_detalle->data["codart"][$li_s];
					$ls_denart=     $io_report->ds_detalle->data["denart"][$li_s];
					$li_canart=     $io_report->ds_detalle->data["canart"][$li_s];
					$li_penart=     $io_report->ds_detalle->data["penart"][$li_s];
					$li_preuniart=  $io_report->ds_detalle->data["preuniart"][$li_s];
					$li_montotart=  $io_report->ds_detalle->data["montotart"][$li_s];						
					$ls_unidad=     $io_report->ds_detalle->data["unidad"][$li_s];
					$li_unidad=     $io_report->ds_detalle->data["unidades"][$li_s];
					if($ls_unidad=="D")
					{
						$ls_unidad="Detal";
					}
					else
					{
						$ls_unidad="Mayor";
						$li_canart=($li_canart / $li_unidad);
						//$li_preuniart=($li_preuniart * $li_unidad);
					}
					$li_totcan=$li_totcan + $li_canart;
					$li_totpen=$li_totpen + $li_penart;
					$li_total=$li_total + $li_montotart;
					$li_canart=number_format($li_canart,2,",",".");
					$li_penart=number_format($li_penart,2,",",".");
					$li_preuniart=number_format($li_preuniart,2,",",".");
					$li_montotart=number_format($li_montotart,2,",",".");
					$la_data[$li_s]=array('articulo'=>$ls_denart,'unidad'=>$ls_unidad,'cantidad'=>$li_canart,'pendiente'=>$li_penart,'precio'=>$li_preuniart,'total'=>$li_montotart);
				}
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				$li_totcan=number_format($li_totcan,2,",",".");
				$li_totpen=number_format($li_totpen,2,",",".");
				$li_total=number_format($li_total,2,",",".");
				$la_data1[1]=array('total'=>'<b>Total</b>','totcan'=>$li_totcan,'totpen'=>$li_totpen,'vacio'=>'--','totmon'=>$li_total);
				uf_print_totales($la_data1,$io_pdf); // Imprimimos el detalle 
				if ($io_pdf->ezPageCount==$li_numpag)
				{// Hacemos el commit de los registros que se desean imprimir
					$io_pdf->transaction('commit');
				}
				else
				{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
					$io_pdf->transaction('rewind');
					if($li_numpag!=1)
					{
						$io_pdf->ezNewPage(); // Insertar una nueva página
					}
					uf_print_cabecera($ls_numconrec,$ls_numordcom,$ls_codpro,$ls_denpro,$ls_codalm,$ls_nomfisalm,$ls_obsrec,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					uf_print_totales($la_data1,$io_pdf); // Imprimimos el detalle 
				}
			}
			unset($la_data);			
		}
		if($lb_valido)
		{
			$io_pdf->ezStopPageNumbers(1,1);
			$io_pdf->ezStream();
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 