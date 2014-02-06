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
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
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
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(12,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$io_pdf->addText(500,730,10,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_nomban,$as_tipcta,$as_ctaban,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_nomban // Nombre del banco
		//	    		   as_tipcta // tipo de cuenta Banacaria
		//	    		   as_ctaban // número de cuenta banacaria
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('banco'=>'<b>Banco</b> '.$as_nomban,'cuenta'=>'<b>Tipo Cuenta</b> '.$as_tipcta);
		$la_columna=array('banco'=>'','cuenta'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Justificación y ancho de la columna
						 'cols'=>array('banco'=>array('justification'=>'left','width'=>250), // Justificación y ancho de la columna
						 			   'cuenta'=>array('justification'=>'left','width'=>250))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('cuenta'=>'<b>Cuenta</b> '.$as_ctaban);
		$la_columna=array('cuenta'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Justificación y ancho de la columna
						 'cols'=>array('cuenta'=>array('justification'=>'left','width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($as_numdoc,$ls_codope,$ad_fecmov,$as_nomproben,$adec_monto,$as_estmov,$as_conmov,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: as_numdoc // Número del documento
		//	    		   ad_fecmov // fecha del movimiento
		//	    		   as_nomproben // nombre del proveedor
		//	    		   adec_monto // monto
		//	    		   as_estmov // estatus
		//	    		   as_conmov //concepto
		//	    		   io_pdf // objeto pdf
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('documento'=>$as_numdoc,'codope'=>$ls_codope,'fecha'=>$ad_fecmov,'beneficiario'=>$as_nomproben,
						  'monto'=>$adec_monto,'status'=>$as_estmov);
		$la_columna=array('documento'=>'<b>Documento</b>','codope'=>'<b>Operacion</b>','fecha'=>'<b>Fecha</b>','beneficiario'=>'<b>Beneficiario</b>',
						  'monto'=>'<b>Monto</b>','status'=>'<b>Estatus</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'rowGap' => 0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Justificación y ancho de la columna
						 'cols'=>array('documento'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'codope'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
									   'fecha'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'beneficiario'=>array('justification'=>'left','width'=>100), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>120), // Justificación y ancho de la columna
						 			   'status'=>array('justification'=>'center','width'=>50))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('concepto'=>'<b>Concepto</b>    '.$as_conmov);
		$la_columna=array('concepto'=>'<b>Concepto</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Justificación y ancho de la columna
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
		$la_columna=array('cuenta'=>'<b>Cuenta</b>','debe'=>'<b>Debe</b>','haber'=>'<b>Haber</b>',
						  'descripcion'=>'<b>Descripción</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'rowGap' => 0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Justificación y ancho de la columna
						 'cols'=>array('cuenta'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'descripcion'=>array('justification'=>'left','width'=>260))); // Justificación y ancho de la columna
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
		$arr=$_SESSION["la_empresa"];
		$as_estmodest=$arr["estmodest"];
		if($as_estmodest==1)
			$ls_cadena = "Estructura Presupuestaria";
		else
			$ls_cadena = "Programática";
		$la_columna=array('programatica'=>'<b>'.$ls_cadena.'</b>','cuenta'=>'<b>Cuenta</b>','monto'=>'<b>Monto</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' =>8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'rowGap' => 0.5,
						 'width'=>400, // Ancho de la tabla
						 'maxWidth'=>400, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Justificación y ancho de la columna
						 'cols'=>array('programatica'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'cuenta'=>array('justification'=>'left','width'=>100), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		
		$io_pdf->ezTable($la_data,$la_columna,'<b>Detalle Presupuestario de Gasto</b>',$la_config);	
	}// end function uf_print_detalle_presupuestario
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_ingreso($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_ingreso
		//		   Access: private 
		//	    Arguments: la_data // arreglo con la data a imprimir
		//	    		   io_pdf // objeto pdf
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_estpreing = $_SESSION["la_empresa"]["estpreing"];
		if ($li_estpreing==1)
		   {
			 $la_columna = array('codestpro'=>'<b>Estructura Presupuestaria</b>','cuenta'=>'<b>Cuenta</b>','monto'=>'<b>Monto</b>');
			 $la_config  = array('showHeadings'=>1, // Mostrar encabezados
							     'fontSize' =>8, // Tamaño de Letras
							     'titleFontSize' => 8,  // Tamaño de Letras de los títulos
							     'showLines'=>0, // Mostrar Líneas
							     'shaded'=>0, // Sombra entre líneas
							     'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
							     'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
							     'rowGap' => 0.5,
							     'width'=>400, // Ancho de la tabla
							     'maxWidth'=>400, // Ancho Máximo de la tabla
							     'xOrientation'=>'center', // Justificación y ancho de la columna
							     'cols'=>array('codestpro'=>array('justification'=>'left','width'=>200),
										       'cuenta'=>array('justification'=>'left','width'=>100),
										       'monto'=>array('justification'=>'right','width'=>100)));
		   
		   }
		else
		   {
			 $la_columna = array('cuenta'=>'<b>Cuenta</b>','monto'=>'<b>Monto</b>');
			 $la_config  = array('showHeadings'=>1, // Mostrar encabezados
							     'fontSize' =>8, // Tamaño de Letras
							     'titleFontSize' => 8,  // Tamaño de Letras de los títulos
							     'showLines'=>0, // Mostrar Líneas
							     'shaded'=>0, // Sombra entre líneas
							     'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
							     'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
							     'rowGap' => 0.5,
							     'width'=>400, // Ancho de la tabla
							     'maxWidth'=>400, // Ancho Máximo de la tabla
							     'xOrientation'=>'center', // Justificación y ancho de la columna
							     'cols'=>array('cuenta'=>array('justification'=>'left','width'=>300), // Justificación y ancho de la columna
										       'monto'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		   }
		$io_pdf->ezTable($la_data,$la_columna,'<b>Detalle Presupuestario de Ingreso</b>',$la_config);	
	}// end function uf_print_detalle_ingreso
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_fin_detalle(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_fin_detalle
		//		   Access: private 
		//	    Arguments: as_numdoc // Número del documento
		//	    		   ad_fecmov // fecha del movimiento
		//	    		   as_nomproben // nombre del proveedor
		//	    		   adec_monto // monto
		//	    		   as_estmov // estatus
		//	    		   as_conmov //concepto
		//	    		   io_pdf // objeto pdf
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('raya'=>'_________________________________________________________________________________________________');
		$la_columna=array('raya'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_fin_detalle
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
		$la_data[1]=array('name'=>'', 'monto'=>'');
		$la_data[2]=array('name'=>'<b>Total:</b>', 'monto'=>$ad_total);
		$la_columna=array('name'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Justificación y ancho de la columna
						 'cols'=>array('name'=>array('justification'=>'right','width'=>400), // Justificación y ancho de la columna
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

	$sig_inc		= new sigesp_include();
	$con			= $sig_inc->uf_conectar();
	$io_report	    = new sigesp_scb_class_report($con);
	$ds_movimientos = new class_datastore();
	$ds_dt_scg		= new class_datastore();
	$ds_dt_spg		= new class_datastore();
	$ds_dt_spi		= new class_datastore();
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ld_fecdesde	= $_GET["fecdes"];
	$ld_fechasta	= $_GET["fechas"];
	$ls_codban		= $_GET["codban"];
	$ls_ctaban		= $_GET["ctaban"];
	$ls_codope		= $_GET["codope"];
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
	if ($ls_codope=='T')
	   {
	     $ls_codope="";
	   }
	$ls_titulo="<b>Listado de Registros Contables $ls_tipbol</b>";
	$lb_valido=true;
	$data_movimientos=$io_report->uf_cargar_movimientos($ld_fecdesde,$ld_fechasta,$ls_codban,$ls_ctaban,$ls_codope,$ls_orden);
	$ds_movimientos->data=$data_movimientos;
	$ldec_total=0;
	$li_total=$ds_movimientos->getRowCount("numdoc");
	if($li_total>0)
	{
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$ls_nomban1="";
		$ls_tipcta1="";
		$ls_ctaban1="";
		for($i=1;$i<=$li_total;$i++)
		{
			$li_contscg   = 0;
			$li_contspg   = 0;
			$ls_numdoc    = $ds_movimientos->getValue("numdoc",$i);
			$ls_codban    = $ds_movimientos->getValue("codban",$i);
			$ls_codope	  = $ds_movimientos->getValue("codope",$i);
			$ldec_monto	  = $ds_movimientos->getValue("monto",$i);
			$ld_fecmov	  = $io_report->fun->uf_formatovalidofecha($ds_movimientos->getValue("fecmov",$i));
			$ld_fecmov	  = $io_report->fun->uf_convertirfecmostrar($ld_fecmov);
			$ls_nomproben = $ds_movimientos->getValue("nomproben",$i);
			$ls_conmov    = $ds_movimientos->getValue("conmov",$i);
			$ls_dencta	  = $ds_movimientos->getValue("dencta",$i);	
			$ls_estmov	  = $ds_movimientos->getValue("estmov",$i);
			$ls_nomban	  = $ds_movimientos->getValue("nomban",$i);
			$ls_tipcta	  = $ds_movimientos->getValue("nomtipcta",$i);
			$ls_ctaban	  = $ds_movimientos->getValue("ctaban",$i);
			if(($ls_nomban1=="")&&($ls_tipcta1=="")&&($ls_ctaban1==""))
			{
				uf_print_cabecera($ls_nomban,$ls_tipcta,$ls_ctaban,$io_pdf);
				$ls_nomban1=$ls_nomban;
				$ls_tipcta1=$ls_tipcta;
				$ls_ctaban1=$ls_ctaban;
			}
			if(($ls_nomban1!=$ls_nomban)&&($ls_tipcta1!=$ls_tipcta)&&($ls_ctaban1!=$ls_ctaban))
			{
				uf_print_cabecera($ls_nomban,$ls_tipcta,$ls_ctaban,$io_pdf);
				$ls_nomban1=$ls_nomban;
				$ls_tipcta1=$ls_tipcta;
				$ls_ctaban1=$ls_ctaban;
			}

			//Obtengo el detalle presupuestario del movimiento.
			unset($ds_dt_spg->data);			
			$ds_dt_spg->data=$io_report->uf_cargar_dt_spg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope,$ls_estmov,&$lb_acceso_estructuras);
			if($lb_acceso_estructuras)//Verifico que tenga detalle presupuestario y que tenga acceso a las estructuras (Caso de no tener detalle presupuestario asumo true, caso de tener verifico el acceso a las estructuras)
			{
				uf_print_detalle($ls_numdoc,$ls_codope,$ld_fecmov,$ls_nomproben,number_format($ldec_monto,2,",","."),$ls_estmov,$ls_conmov,$io_pdf);
				unset($ds_dt_scg->data);
				//Obtengo el detalle contable del documento
				$ds_dt_scg->data=$io_report->uf_cargar_dt_scg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope,$ls_estmov);
				$li_totscg=$ds_dt_scg->getRowCount("scg_cuenta");
				if($li_totscg>0)
				{
					unset($la_data);
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
						$la_data[$li_a]=array('cuenta'=>$ds_dt_scg->getValue("scg_cuenta",$li_a),'debe'=>$ldec_mondeb,
											  'haber'=>$ldec_monhab, 'descripcion'=>$ds_dt_scg->getValue("desmov",$li_a));
					}
					uf_print_detalle_contable($la_data,$io_pdf);
				}
				
				//Recorro el detalle presupuestario en caso de tener	
				$li_totspg=$ds_dt_spg->getRowCount("spg_cuenta");
				if($li_totspg>0)		
				{
					unset($la_data);
					for($li_b=1;$li_b<=$li_totspg;$li_b++)
					{
						$la_data[$li_b]=array('programatica'=>$ds_dt_spg->getValue("estpro",$li_b),
											  'cuenta'=>$ds_dt_spg->getValue("spg_cuenta",$li_b),
											  'monto'=>number_format($ds_dt_spg->getValue("monto",$li_b),2,",","."));
					}				
					uf_print_detalle_presupuestario($la_data,$io_pdf);
				}
				//Obtengo el detalle presupuestario de Ingresos del movimiento.
				$ds_dt_spi = $io_report->uf_cargar_dt_spi($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope,$ls_estmov);
				$li_totspi = count($ds_dt_spi);
				if ($li_totspi>0)		
				   {
					 $li_i = 0;
					 $li_estpreing     = $_SESSION["la_empresa"]["estpreing"];
					 $li_estmodest     = $_SESSION["la_empresa"]["estmodest"];
					 $li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
					 $li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
					 $li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
					 $li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
					 $li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
					 unset($la_data);
					 for ($li_b=0;$li_b<$li_totspi;$li_b++)
						 {				
						   $li_i++;
						   $ls_spicta     = $ds_dt_spi[$li_b]["spi_cuenta"];
						   $ld_totmonspi  = $ds_dt_spi[$li_b]["monto"];
						   if ($li_estpreing==1)
							  {
								$ls_codestpro1 = substr($ds_dt_spi[$li_b]["codestpro1"],-$li_loncodestpro1);
								$ls_codestpro2 = substr($ds_dt_spi[$li_b]["codestpro2"],-$li_loncodestpro2);
								$ls_codestpro3 = substr($ds_dt_spi[$li_b]["codestpro3"],-$li_loncodestpro3);
								$ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3; 
								if ($li_estmodest==2)
								   {
									 $ls_codestpro4 = substr($ds_dt_spi[$li_b]["codestpro4"],-$li_loncodestpro4);
									 $ls_codestpro5 = substr($ds_dt_spi[$li_b]["codestpro5"],-$li_loncodestpro5);
									 $ls_codestpro  = $ls_codestpro.'-'.$ls_codestpro4.'-'.$ls_codestpro5;
								   }
								$la_data[$li_i] = array('codestpro'=>$ls_codestpro, 
														'cuenta'=>$ls_spicta,
														'monto'=>number_format($ld_totmonspi,2,",","."));
							  }
						   else
							  {
								$la_data[$li_i] = array('cuenta'=>$ls_spicta,
														'monto'=>number_format($ld_totmonspi,2,",","."));
							  }
						 }
					 uf_print_detalle_ingreso($la_data,$io_pdf);
				   }
				$ldec_total=$ldec_total+$ldec_monto;
				uf_print_fin_detalle(&$io_pdf);	
			}
		}
		uf_print_totales(number_format($ldec_total,2,",","."),&$io_pdf);
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
		print(" alert('No hay nada que Reportar,para los parametros de Busqueda seleccionados');"); 
		print(" close();");
		print("</script>");
	}
?>