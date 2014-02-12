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
	function uf_print_encabezado_pagina($as_titulo,$as_codsolvia,$ad_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: $as_titulo    // Título del Reporte
		//	    		   $as_codsolvia // codigo de la solicitud de viaticos
		//	    		   $ad_fecha     // Fecha 
		//	    		   $io_pdf       // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 30/11/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->rectangle(440,710,110,40);
		$io_pdf->line(440,730,550,730);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$io_pdf->addText(443,735,11,"No.:");      // Agregar texto
		$io_pdf->addText(477,735,11,$as_codsolvia); // Agregar Numero de la solicitud
		$io_pdf->addText(443,715,10,"Fecha:"); // Agregar texto
		$io_pdf->addText(477,715,10,$ad_fecha); // Agregar la Fecha
		$io_pdf->addText(510,760,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(516,753,7,date("h:i a")); // Agregar la Hora
		// cuadro inferior
        $io_pdf->Rectangle(50,40,500,70);
		$io_pdf->line(50,53,550,53);		
		$io_pdf->line(50,97,550,97);		
		$io_pdf->line(175,40,175,110);		
		$io_pdf->line(300,40,300,110);		
		$io_pdf->line(425,40,425,110);		
		$io_pdf->addText(82,102,7,"ELABORADO POR"); // Agregar el título
		$io_pdf->addText(70,43,7,"Nombre y Apellido / Cargo"); // Agregar el título
		$io_pdf->addText(205,102,7,"VERIFICADO POR"); // Agregar el título
		$io_pdf->addText(185,43,7,"Nombre y Apellido / Cargo / Sello"); // Agregar el título
		$io_pdf->addText(330,102,7,"AUTORIZADO POR"); // Agregar el título
		$io_pdf->addText(310,43,7,"Nombre y Apellido / Cargo / Sello"); // Agregar el título
		$io_pdf->addText(460,102,7,"RECIBIDO POR"); // Agregar el título
		$io_pdf->addText(435,43,7,"Nombre y Apellido / Cargo / Sello"); // Agregar el título
		$io_pdf->addText(50,30,7,"<b>TVS:</b> Tarifas de Viaticos  <b>TRP:</b> Tarifa de Transporte  <b>TDS:</b> Tarifa de Distancias  <b>TOA:</b> Otras Asignaciones "); // Agregar el título
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codsolvia,$as_codmis,$as_denmis,$as_codrut,$as_denrut,$as_coduniadm,$as_denunidam,
							   $ad_fecsalvia,$ad_fecregvia,$ai_numdia,$as_obssolvia,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: $as_codsolvia  // codigo de solicitud de viaticos
		//	    		   $as_codmis     // codigo de mision
		//	    		   $as_denmis     // denominacion de mision
		//	    		   $as_codrut     // codigo de ruta
		//	    		   $as_denrut     // denominacion de ruta
		//	    		   $as_coduniadm  // codigo de unidad administrativa
		//	    		   $as_denunidam  // denominacion de unidad administrativa
		//	    		   $ad_fecsalvia  // fecha de salida del viatico
		//	    		   $ad_fecregvia  // fecha de regreso del viatico
		//	    		   $ai_numdia     // numero de dias
		//	    		   $as_obssolvia  // observaciones
		//	    		   io_pdf         // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/11/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();				
		$ad_fecsalvia=$io_funciones->uf_convertirfecmostrar($ad_fecsalvia);
		$ad_fecregvia=$io_funciones->uf_convertirfecmostrar($ad_fecregvia);
		$la_data=array(array('name'=>'<b>Mision</b>      '.$as_denmis.''),
					   array('name'=>'<b>Ruta</b>         '.$as_denrut.''),
					   array('name'=>'<b>Unidad</b>     '.$as_denunidam.''),
					   array('name'=>'<b>Salida</b>      '.$ad_fecsalvia.'       <b>Nro. dias</b> '.$ai_numdia.''),
					   array('name'=>'<b>Retorno</b>   '.$ad_fecregvia.''),
					   array ('name'=>'<b>Observaciones</b>  '.$as_obssolvia.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar Líneas
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
	function uf_print_detalle_asignaciones($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_asignaciones
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_columna=array('proasi'=>'<b>Procedencia</b>',
						  'codasi'=>'<b>Codigo</b>',
						  'denasi'=>'<b>Concepto</b>',
						  'canasi'=>'<b>Cantidad</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'rowGap' => 1,
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('proasi'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'codasi'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'denasi'=>array('justification'=>'left','width'=>330), // Justificación y ancho de la columna
						 			   'canasi'=>array('justification'=>'right','width'=>60))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'Asignaciones',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_personal($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_personal
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_columna=array('codper'=>'<b>Código</b>',
						  'nomper'=>'<b>Nombre</b>',
						  'cedper'=>'<b>Cédula</b>',
						  'cargo'=>'<b>Cargo</b>',
						  'codclavia'=>'<b>Categoria</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'rowGap' => 1,
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codper'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'nomper'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'cedper'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'cargo'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'codclavia'=>array('justification'=>'center','width'=>50))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'Personal',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_presupuestario($la_data,&$io_pdf,$as_titest)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_personal
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_columna=array('codestpro'=>'<b>'.$as_titest.'</b>',
						  'spg_cuenta'=>'<b>Cuenta</b>',
						  'monto'=>'<b>Monto</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'rowGap' => 1,
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codestpro'=>array('justification'=>'center','width'=>260), // Justificación y ancho de la columna
						 			   'spg_cuenta'=>array('justification'=>'center','width'=>140), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'Detalle Presupuestario',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_contable($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_personal
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_columna=array('sc_cuenta'=>'<b>Cuenta</b>',
						  'debhab'=>'<b>Debe/Haber</b>',
						  'monto'=>'<b>Monto</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'rowGap' => 1,
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('sc_cuenta'=>array('justification'=>'center','width'=>260), // Justificación y ancho de la columna
						 			   'debhab'=>array('justification'=>'center','width'=>140), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'Detalle Contable',$la_config);
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
	require_once("sigesp_scv_class_report.php");
	$io_report=new sigesp_scv_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_viaticos.php");
	$io_fun_inventario=new class_funciones_viaticos();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ld_fecsolvia= $io_fun_inventario->uf_obtenervalor_get("fecsolvia","");

	$ls_titulo="<b>Solicitud de Viáticos</b>";
	$ls_fecha="";
	$ls_modalidad= $_SESSION["la_empresa"]["estmodest"];
	switch($ls_modalidad)
	{
		case "1": // Modalidad por Proyecto
			$ls_titest="Estructura Presupuestaria ";
			break;
			
		case "2": // Modalidad por Presupuesto
			$ls_titest="Estructura Programática ";
			break;
	}
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_codsolvia= $io_fun_inventario->uf_obtenervalor_get("codsolvia","");
	$ld_desde="";
	$ld_hasta="";
	$li_orden=0;
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_select_solicitudviaticos($ls_codemp,$ls_codsolvia,$ld_desde,$ld_hasta,"","","","","","","","","","",$li_orden); // Cargar el DS con los datos de la cabecera del reporte
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
		uf_print_encabezado_pagina($ls_titulo,$ls_codsolvia,$ld_fecsolvia,$io_pdf); // Imprimimos el encabezado de la página
		$li_totrow=1;//$io_report->DS->getRowCount("codper");
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
	        $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$li_totcan=0;
			$li_totpen=0;
			$li_total=0;
			$ls_codsolvia= $io_report->ds->data["codsolvia"][$li_i];
			$ls_codmis= $io_report->ds->data["codmis"][$li_i];
			$ls_denmis= $io_report->ds->data["denmis"][$li_i];
			$ls_codrut= $io_report->ds->data["codrut"][$li_i];
			$ls_denrut= $io_report->ds->data["desrut"][$li_i];
			$ls_coduniadm= $io_report->ds->data["coduniadm"][$li_i];
			$ls_denunidam= $io_report->ds->data["denuniadm"][$li_i];
			$ld_fecsalvia= $io_report->ds->data["fecsalvia"][$li_i];
			$ld_fecregvia= $io_report->ds->data["fecregvia"][$li_i];
			$li_numdia= $io_report->ds->data["numdiavia"][$li_i];
			$ls_obssolvia= $io_report->ds->data["obssolvia"][$li_i];
			$li_numdia= number_format($li_numdia,2,",",".");
			uf_print_cabecera($ls_codsolvia,$ls_codmis,$ls_denmis,$ls_codrut,$ls_denrut,$ls_coduniadm,$ls_denunidam,
							  $ld_fecsalvia,$ld_fecregvia,$li_numdia,$ls_obssolvia,&$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_select_dt_asignaciones($ls_codemp,$ls_codsolvia,$ld_desde,$ld_hasta,$li_orden);
			if($lb_valido)
			{
				$li_totrow_det=$io_report->ds_detalle->getRowCount("codasi");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_proasi= $io_report->ds_detalle->data["proasi"][$li_s];
					$ls_codasi= $io_report->ds_detalle->data["codasi"][$li_s];
					$ls_denasi= $io_report->ds_detalle->data["denasi"][$li_s];
					$li_canasi= $io_report->ds_detalle->data["canasi"][$li_s];

					$li_canasi=number_format($li_canasi,2,",",".");
					$la_data[$li_s]=array('proasi'=>$ls_proasi,'codasi'=>$ls_codasi,'denasi'=>$ls_denasi,'canasi'=>$li_canasi);
				}
				uf_print_detalle_asignaciones($la_data,$io_pdf); // Imprimimos el detalle 
				$lb_valido=$io_report->uf_select_dt_personal($ls_codemp,$ls_codsolvia,$ld_desde,$ld_hasta,$li_orden,$lb_personal);
				if($lb_valido)
				{
					$li_totrow_det=$io_report->ds_detpersonal->getRowCount("codper");
					for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
					{
						if($lb_personal)
						{
							$ls_codper=    $io_report->ds_detpersonal->data["codper"][$li_s];
							$ls_cedper=    $io_report->ds_detpersonal->data["cedper"][$li_s];
							$ls_nomper=    $io_report->ds_detpersonal->data["nomper"][$li_s]." ".$io_report->ds_detpersonal->data["apeper"][$li_s];
							$ls_codcar=    $io_report->ds_detpersonal->data["cargo"][$li_s];				
							$ls_codclavia= $io_report->ds_detpersonal->data["codclavia"][$li_s];			
						}
						else
						{
							$ls_codper=$io_report->ds_detpersonal->data["codper"][$li_s];
							$ls_cedper=$io_report->ds_detpersonal->data["ced_bene"][$li_s];
							$ls_nomper=$io_report->ds_detpersonal->data["nombene"][$li_s]." ".$io_report->ds_detpersonal->data["apebene"][$li_s];
							$ls_codcar="";				
							$ls_codclavia="";			
						}
						$la_data[$li_s]=array('codper'=>$ls_codper,'cedper'=>$ls_cedper,'nomper'=>$ls_nomper,'cargo'=>$ls_codcar,
											  'codclavia'=>$ls_codclavia);
					}
					uf_print_detalle_personal($la_data,$io_pdf); // Imprimimos el detalle 
					$lb_existe=$io_report->uf_select_dt_spg($ls_codemp,$ls_codsolvia);
					if($lb_existe)
					{
						$li_totrow_detpres=$io_report->ds_detpresup->getRowCount("spg_cuenta");
						for($li_j=1;$li_j<=$li_totrow_detpres;$li_j++)
						{
							$ls_spgcuenta=  $io_report->ds_detpresup->data["spg_cuenta"][$li_j];
							$ls_codestpro1= $io_report->ds_detpresup->data["codestpro1"][$li_j];
							$ls_codestpro2= $io_report->ds_detpresup->data["codestpro2"][$li_j];
							$ls_codestpro3= $io_report->ds_detpresup->data["codestpro3"][$li_j];
							$ls_codestpro4= $io_report->ds_detpresup->data["codestpro4"][$li_j];
							$ls_codestpro5= $io_report->ds_detpresup->data["codestpro5"][$li_j];
							$li_monto= $io_report->ds_detpresup->data["monto"][$li_j];
		
							$li_monto=number_format($li_monto,2,",",".");
							if($ls_modalidad==1)
							{
								$ls_codestpro=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3;
							}
							if($ls_modalidad==2)
							{
								$ls_codestpro=substr($ls_codestpro1,18,2).substr($ls_codestpro2,4,2).substr($ls_codestpro3,1,2).$ls_codestpro4.$ls_codestpro5;
							}
							$la_datac[$li_j]=array('codestpro'=>$ls_codestpro,'spg_cuenta'=>$ls_spgcuenta,'monto'=>$li_monto);
						}
						uf_print_detalle_presupuestario($la_datac,$io_pdf,$ls_titest); // Imprimimos el detalle 
						$lb_valido=$io_report->uf_select_dt_scg($ls_codemp,$ls_codsolvia);
						if($lb_valido)
						{
							$li_totrow_detcont=$io_report->ds_detcontable->getRowCount("sc_cuenta");
							for($li_j=1;$li_j<=$li_totrow_detcont;$li_j++)
							{
								$ls_sccuenta=  $io_report->ds_detcontable->data["sc_cuenta"][$li_j];
								$ls_debhab=    $io_report->ds_detcontable->data["debhab"][$li_j];
								$li_monto=     $io_report->ds_detcontable->data["monto"][$li_j];
								if($ls_debhab=="D")
									$ls_debhab="Debe";
								else
									$ls_debhab="Haber";								
								$li_monto=number_format($li_monto,2,",",".");
								$la_datac[$li_j]=array('sc_cuenta'=>$ls_sccuenta,'debhab'=>$ls_debhab,'monto'=>$li_monto);
							}
							uf_print_detalle_contable($la_datac,$io_pdf); // Imprimimos el detalle 
						}
					}
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
					}
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