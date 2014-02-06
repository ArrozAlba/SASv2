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
	function uf_print_encabezado_pagina($as_titulo,$as_fecha,$as_numorddes,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo    // Título del Reporte
		//	    		   as_numorddes // Numero de Orden de despacho
		//	    		   as_fecha     // Fecha 
		//	    		   io_pdf       // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->saveState();
		$io_pdf->rectangle(576,530,150,40);
		$io_pdf->line(576,550,726,550);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],55,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=320;
		$io_pdf->addText($tm,545,11,"<b>".$as_titulo."</b>"); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_fecha);
		$tm=490;
		$io_pdf->addText(580,535,11,"Fecha:"); // Agregar la fecha
		$io_pdf->addText(620,535,11,$as_fecha); // Agregar la fecha
		$io_pdf->addText(580,555,11,"No.:"); // Agregar la fecha
		$io_pdf->addText(620,555,11,$as_numorddes); // Agregar la fecha
		$io_pdf->addText(685,580,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(691,573,7,date("h:i a")); // Agregar la Hora
		// cuadro inferior

		$io_pdf->Rectangle(55,100,670,70);
		$io_pdf->line(55,115,725,115);		
		$io_pdf->line(55,155,725,155);		
		$io_pdf->line(280,100,280,170);		
		$io_pdf->line(510,100,510,170);		
		$io_pdf->addText(135,160,7,"ELABORADO POR"); // Agregar el título
		$io_pdf->addText(145,105,7,"ALMACÉN"); // Agregar el título
		$io_pdf->addText(355,160,7,"AUTORIZADO POR"); // Agregar el título
		$io_pdf->addText(355,105,7,"JEFE DE COMPRAS"); // Agregar el título
		$io_pdf->addText(580,160,7,"MATERIALES RECIBIDOS"); // Agregar el título
		$io_pdf->addText(585,105,7,"FIRMA, SELLO, FECHA"); // Agregar el título
		
		$io_pdf->Rectangle(55,50,670,40);
		$io_pdf->addText(57,75,10,"<b>NOTA: En caso de salida de materiales a dependencias de otras sedes dar conformidad de la Unidad de Seguridad y Resguardo.</b>"); // Agregar el título
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_numorddes,$as_numsol,$as_coduniadm,$as_denunidam,$as_obsdes,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_numtra    // numero de transaccion
		//	    		   as_codalmori // codigo de almacen origen
		//	    		   as_codalmdes // codigo de almacen destino
		//	    		   as_nomfisori // nombre fiscal de almacen origen
		//	    		   as_nomfisdes // nombre fiscal de almacen destino
		//	    		   as_obstra    // observaciones de la transferencia
		//	    		   ad_fecemi    // fecha de emision
		//	    		   io_pdf       // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$io_pdf->line(160,468,160,511);
		$la_data=array(array('name'=>'<b>Solicitud</b>                             '.$as_numsol.''),
					   array('name'=>'<b>Unidad Administrativa</b>      '.$as_denunidam.''),
					   array ('name'=>'<b>Observaciones</b>                  '.$as_obsdes.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar Líneas
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>670, // Ancho de la tabla
						 'maxWidth'=>670); // Ancho Máximo de la tabla
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
						  'almacen'=>'<b>Almacén</b>',
						  'unidad'=>'<b>Unidad</b>',
						  'solicitada'=>'<b>Solicitada</b>',
						  'despachada'=>'<b>Despachada</b>',
						  'precio'=>'<b>Precio Unitario</b>',
						  'total'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>670, // Ancho de la tabla
						 'maxWidth'=>670, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('articulo'=>array('justification'=>'left','width'=>190), // Justificación y ancho de la columna
						 			   'almacen'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'unidad'=>array('justification'=>'left','width'=>45), // Justificación y ancho de la columna
						 			   'solicitada'=>array('justification'=>'right','width'=>62), // Justificación y ancho de la columna
						 			   'despachada'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'precio'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>82))); // Justificación y ancho de la columna
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
						  'totsol'=>'',
						  'totart'=>'',
						  'vacio'=>'',
						  'totmon'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>670, // Ancho de la tabla
						 'maxWidth'=>670, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>375), // Justificación y ancho de la columna
						 			   'totsol'=>array('justification'=>'right','width'=>62), // Justificación y ancho de la columna
						 			   'totart'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'vacio'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'totmon'=>array('justification'=>'right','width'=>82))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detallecontable($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detallecontable
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('denartc'=>'<b>Artículo</b>',
		                  'cuenta'=>'<b>Cuenta Contable</b>',
						  'debhab'=>'<b>Debe/Haber</b>',
						  'monto'=>'<b>Monto</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('denartc'=>array('justification'=>'left','width'=>319), // Justificación y ancho de la columna
						               'cuenta'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la columna
						 			   'debhab'=>array('justification'=>'center','width'=>75), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>125))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'<b>Detalle Contable</b>',$la_config);
	}// end function uf_print_detallecontable
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totalescontable($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totalescontable
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('total'=>'',
						  'debe'=>'',
						  'haber'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>469),
						 			   'debe'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
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
	$ld_fecdes= $io_fun_inventario->uf_obtenervalor_get("fecdes","");

	$ls_titulo="Órden de Despacho";
	$ls_fecha=$ld_fecdes;
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_numorddes= $io_fun_inventario->uf_obtenervalor_get("numorddes","");
	$ld_desde="";
	$ld_hasta="";
	$li_ordenfec=0;
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_select_despachos($ls_codemp,$ls_numorddes,$ld_desde,$ld_hasta,$li_ordenfec)	; // Cargar el DS con los datos de la cabecera del reporte
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
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,6,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$ls_numorddes,$io_pdf); // Imprimimos el encabezado de la página
		//$io_pdf->ezStartPageNumbers(730,50,10,'','',1); // Insertar el número de página
		$li_totrow=1;//$io_report->ds->getRowCount("codper");
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
	        $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$li_total=0;
			$li_totcanart=0;
			$li_totcansol=0;
			$ls_numsol=$io_report->ds->data["numsol"][$li_i];
			$ls_coduniadm=$io_report->ds->data["coduniadm"][$li_i];
			$ls_denunidam=$io_report->ds->data["denuniadm"][$li_i];
			$ls_obsdes=$io_report->ds->data["obsdes"][$li_i];
			uf_print_cabecera($ls_numorddes,$ls_numsol,$ls_coduniadm,$ls_denunidam,$ls_obsdes,&$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_select_dt_despacho($ls_codemp,$ls_numorddes,$ld_desde,$ld_hasta,$li_ordenfec); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->ds_detalle->getRowCount("codart");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_codart=     $io_report->ds_detalle->data["codart"][$li_s];
					$ls_denart=     $io_report->ds_detalle->data["denart"][$li_s];
					$ls_nomfisalm=  $io_report->ds_detalle->data["nomfisalm"][$li_s];
					$li_canart=     $io_report->ds_detalle->data["canart"][$li_s];
					$li_cansol=     $io_report->ds_detalle->data["canorisolsep"][$li_s];
					$li_preuniart=  $io_report->ds_detalle->data["preuniart"][$li_s];
					$li_montotart=  $io_report->ds_detalle->data["montotart"][$li_s];						
					$ls_unidad=     $io_report->ds_detalle->data["unidad"][$li_s];
					$li_total=$li_total + $li_montotart;
					$li_totcanart=$li_totcanart + $li_canart;
					$li_totcansol=$li_totcansol + $li_cansol;
					if($ls_unidad=="D"){$ls_unidad="Detal";}
					else{$ls_unidad="Mayor";}

					$li_canart=number_format($li_canart,2,",",".");
					$li_cansol=number_format($li_cansol,2,",",".");
					$li_preuniart=number_format($li_preuniart,2,",",".");
					$li_montotart=number_format($li_montotart,2,",",".");

					$la_data[$li_s]=array('articulo'=>$ls_denart,'almacen'=>$ls_nomfisalm,'unidad'=>$ls_unidad,'solicitada'=>$li_cansol,'despachada'=>$li_canart,'precio'=>$li_preuniart,'total'=>$li_montotart);
				}
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				$li_total=number_format($li_total,2,",",".");
				$li_totcanart=number_format($li_totcanart,2,",",".");
				$li_totcansol=number_format($li_totcansol,2,",",".");
				$la_data1[1]=array('total'=>'<b>Total</b>','totsol'=>$li_totcansol,'totart'=>$li_totcanart,'vacio'=>'--','totmon'=>$li_total);
				uf_print_totales($la_data1,$io_pdf); // Imprimimos el detalle 
				$ld_fechaaux=$io_funciones->uf_convertirdatetobd($ls_fecha);
				$lb_existe=$io_report->uf_siv_load_dt_contable($ls_codemp,$ls_numorddes,$ld_fechaaux); // Obtenemos el detalle del reporte
				if($lb_existe)
				{
					$li_montotdeb=0;
					$li_montothab=0;
					$li_totrow_det=$io_report->ds_detcontable->getRowCount("sc_cuenta");
					for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
					{
						$ls_denartc=   $io_report->ds_detcontable->data["denart"][$li_s];
						$ls_cuenta=    $io_report->ds_detcontable->data["sc_cuenta"][$li_s]; 
						$ls_debhab=    $io_report->ds_detcontable->data["debhab"][$li_s]; 
						$li_monto=     $io_report->ds_detcontable->data["monto"][$li_s]; 
						if($ls_debhab=="D")
						{$li_montotdeb=$li_montotdeb+$li_monto;}
						else
						{$li_montothab=$li_montothab+$li_monto;}
						$li_monto=$io_fun_inventario->uf_formatonumerico($li_monto);
						$la_data2[$li_s]=array('denartc'=>$ls_denartc,'cuenta'=>$ls_cuenta,'debhab'=>$ls_debhab,'monto'=>$li_monto);
					}
					$li_montotdeb=$io_fun_inventario->uf_formatonumerico($li_montotdeb);
					$li_montothab=$io_fun_inventario->uf_formatonumerico($li_montothab);
					$la_datatc[1]=array('total'=>"Total",'debe'=>"Debe ".$li_montotdeb,'haber'=>"Haber ".$li_montothab);
					uf_print_detallecontable($la_data2,$io_pdf); // Imprimimos el detalle 
					uf_print_totalescontable($la_datatc,&$io_pdf);
				}
			}
			unset($la_data);			
			unset($la_datac);			
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
?> 