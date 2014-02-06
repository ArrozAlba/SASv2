<?php
	//-----------------------------------------------------------------------------------------------------------------------------------
	//Reporte Modificado para aceptar Bs. y Bs.F.
	//Modificado por: Ing. Luis Anibal Lang  08/08/2007	
	//-----------------------------------------------------------------------------------------------------------------------------------
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
	function uf_print_encabezado_pagina($as_titulo,$ad_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   ad_fecha // Fecha 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(35,40,570,40);
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$ad_fecha);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,715,10,$ad_fecha); // Agregar la Fecha
		$io_pdf->addText(510,740,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(516,733,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codsolvia,$as_codmis,$as_denmis,$as_codrut,$as_denrut,$as_coduniadm,$as_denunidam,
							   $ad_fecsolvia,$ad_fecsalvia,$ad_fecregvia,$ai_numdia,$as_obssolvia,&$io_pdf)
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
		//	    		   $ad_fecsolvia  // fecha de solicitud del viatico
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
		$ad_fecsolvia=$io_funciones->uf_convertirfecmostrar($ad_fecsolvia);
		$ad_fecsalvia=$io_funciones->uf_convertirfecmostrar($ad_fecsalvia);
		$ad_fecregvia=$io_funciones->uf_convertirfecmostrar($ad_fecregvia);
		$la_data=array(array('name'=>'<b>Código</b>     '.$as_codsolvia.''),
					   array('name'=>'<b>Mision</b>      '.$as_denmis.''),
					   array('name'=>'<b>Ruta</b>         '.$as_denrut.''),
					   array('name'=>'<b>Unidad</b>     '.$as_denunidam.''),
					   array('name'=>'<b>Solicitud</b>  '.$ad_fecsolvia.''),
					   array('name'=>'<b>Nro. dias</b>  '.$ai_numdia.''),
					   array('name'=>'<b>Observaciones</b>  '.$as_obssolvia.''));
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
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo="Monto Bs.F.";
		}
		else
		{
			$ls_titulo="Monto Bs.";
		}
		$io_pdf->ezSetDy(-5);
		$la_columna=array('codestpro'=>'<b>'.$as_titest.'</b>',
						  'spg_cuenta'=>'<b>Cuenta</b>',
						  'monto'=>'<b>'.$ls_titulo.'</b>');
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
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo="Monto Bs.F.";
		}
		else
		{
			$ls_titulo="Monto Bs.";
		}
		$io_pdf->ezSetDy(-5);
		$la_columna=array('sc_cuenta'=>'<b>Cuenta</b>',
						  'debhab'=>'<b>Debe/Haber</b>',
						  'monto'=>'<b>'.$ls_titulo.'</b>');
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
	function uf_print_detalle_totales(&$io_pdf)
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
		$la_data="";
		$la_columna=array('sc_cuenta'=>'',
						  'monto'=>'');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'rowGap' => 1,
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('sc_cuenta'=>array('justification'=>'center','width'=>500), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_viaticos.php");
	$io_fun_viaticos=new class_funciones_viaticos();
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();				
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ld_desde=$io_fun_viaticos->uf_obtenervalor_get("desde","");
	$ld_hasta=$io_fun_viaticos->uf_obtenervalor_get("hasta","");
	$ls_tiporeporte=$io_fun_viaticos->uf_obtenervalor_get("tiporeporte",0);
	//----------------------------------------------------  Parámetros de Tipo de Moneda  -----------------------------------------------
	global $ls_tiporeporte;
	require_once("../../shared/ezpdf/class.ezpdf.php");
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_scv_class_reportbsf.php");
		$io_report=new sigesp_scv_class_reportbsf();
	}
	else
	{
		require_once("sigesp_scv_class_report.php");
		$io_report=new sigesp_scv_class_report();
	}	

	$ls_titulo="<b> Reporte de Solicitudes de Viáticos </b>";
	$ls_fecha="Periodo ".$ld_desde." - ".$ld_hasta;
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
	$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
	$ls_codsolvia="";
	$li_orden=$io_fun_viaticos->uf_obtenervalor_get("ordenfec","");
	$ls_coduniadm=$io_fun_viaticos->uf_obtenervalor_get("coduniadm","");
	$ls_codestpro1=$io_fun_viaticos->uf_obtenervalor_get("codestpro1","");
	$ls_codestpro2=$io_fun_viaticos->uf_obtenervalor_get("codestpro2","");
	$ls_codestpro3=$io_fun_viaticos->uf_obtenervalor_get("codestpro3","");
	$ls_codestpro4=$io_fun_viaticos->uf_obtenervalor_get("codestpro4","");
	$ls_codestpro5=$io_fun_viaticos->uf_obtenervalor_get("codestpro5","");
	$ls_estcla=$io_fun_viaticos->uf_obtenervalor_get("estcla","");
	
	$ls_codben=$io_fun_viaticos->uf_obtenervalor_get("codben","");
	if($ls_codben!="")
	{
		$lb_valido=$io_report->uf_scv_load_codigopersonal($ls_codemp,$ls_codben,&$as_codper);
		if($lb_valido)
		{
			$ls_codben=$as_codper;
		}
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_select_solicitudviaticos($ls_codemp,$ls_codsolvia,$ld_desde,$ld_hasta,"","",$ls_coduniadm,$ls_codben,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$li_orden); // Cargar el DS con los datos de la cabecera del reporte
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
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(545,25,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->ds->getRowCount("codsolvia");
	
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
		    $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$li_total=0;
			$li_totcanart=0;
			$li_totcansol=0;
			$ls_codsolvia= $io_report->ds->data["codsolvia"][$li_i];
			$ls_codmis= $io_report->ds->data["codmis"][$li_i];
			$ls_denmis= $io_report->ds->data["denmis"][$li_i];
			$ls_codrut= $io_report->ds->data["codrut"][$li_i];
			$ls_denrut= $io_report->ds->data["desrut"][$li_i];
			$ls_coduniadm= $io_report->ds->data["coduniadm"][$li_i];
			$ls_denunidam= $io_report->ds->data["denuniadm"][$li_i];
			$ld_fecsalvia= $io_report->ds->data["fecsalvia"][$li_i];
			$ld_fecsolvia= $io_report->ds->data["fecsolvia"][$li_i];
			$ld_fecregvia= $io_report->ds->data["fecregvia"][$li_i];
//			$li_numdia= $io_report->ds->data["numdiavia"][$li_i];
			$li_numdia=$io_fecha->uf_restar_fechas($ld_fecsalvia,$ld_fecregvia);
			$li_numdia=$li_numdia+1;
			$ls_obssolvia= $io_report->ds->data["obssolvia"][$li_i];
			$li_numdia= number_format($li_numdia,2,",",".");
			uf_print_cabecera($ls_codsolvia,$ls_codmis,$ls_denmis,$ls_codrut,$ls_denrut,$ls_coduniadm,$ls_denunidam,
							  $ld_fecsolvia,$ld_fecsalvia,$ld_fecregvia,$li_numdia,$ls_obssolvia,&$io_pdf); // Imprimimos la cabecera del registro
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
					$la_data_asig[$li_s]=array('proasi'=>$ls_proasi,'codasi'=>$ls_codasi,'denasi'=>$ls_denasi,'canasi'=>$li_canasi);
				}
				uf_print_detalle_asignaciones($la_data_asig,$io_pdf); // Imprimimos el detalle 
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
							$ls_codper= $io_report->ds_detpersonal->data["codper"][$li_s];
							$ls_cedper= $io_report->ds_detpersonal->data["ced_bene"][$li_s];
							$ls_nomper= $io_report->ds_detpersonal->data["nombene"][$li_s]." ".$io_report->ds_detpersonal->data["apebene"][$li_s];
							$ls_codcar="";				
							$ls_codclavia="";			
						}
						$la_data_pers[$li_s]=array('codper'=>$ls_codper,'cedper'=>$ls_cedper,'nomper'=>$ls_nomper,'cargo'=>$ls_codcar,
											       'codclavia'=>$ls_codclavia);
					}
					uf_print_detalle_personal($la_data_pers,$io_pdf); // Imprimimos el detalle 
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
							$la_data_pres[$li_j]=array('codestpro'=>$ls_codestpro,'spg_cuenta'=>$ls_spgcuenta,'monto'=>$li_monto);
						}
						uf_print_detalle_presupuestario($la_data_pres,$io_pdf,$ls_titest); // Imprimimos el detalle 
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
								$la_data_cont[$li_j]=array('sc_cuenta'=>$ls_sccuenta,'debhab'=>$ls_debhab,'monto'=>$li_monto);
							}
							uf_print_detalle_contable($la_data_cont,$io_pdf); // Imprimimos el detalle 
						}
					}
					uf_print_detalle_totales($io_pdf);
					uf_print_detalle_totales($io_pdf);
					if ($io_pdf->ezPageCount==$li_numpag)
					{// Hacemos el commit de los registros que se desean imprimir
						$io_pdf->transaction('commit');
					}
					else
					{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
						$io_pdf->transaction('rewind');
//						if($li_numpag!=1)
//						{
							$io_pdf->ezNewPage(); // Insertar una nueva página
//						}
						uf_print_cabecera($ls_codsolvia,$ls_codmis,$ls_denmis,$ls_codrut,$ls_denrut,$ls_coduniadm,$ls_denunidam,
										  $ld_fecsolvia,$ld_fecsalvia,$ld_fecregvia,$li_numdia,$ls_obssolvia,&$io_pdf); // Imprimimos la cabecera del registro
						uf_print_detalle_asignaciones($la_data_asig,$io_pdf); // Imprimimos el detalle 
						uf_print_detalle_personal($la_data_pers,$io_pdf); // Imprimimos el detalle 
						if($lb_existe)
						{
							uf_print_detalle_presupuestario($la_data_pres,$io_pdf,$ls_titest); // Imprimimos el detalle 
							uf_print_detalle_contable($la_data_cont,$io_pdf); // Imprimimos el detalle 
						}
						uf_print_detalle_totales($io_pdf);
						uf_print_detalle_totales($io_pdf);
					}
				}
			}
			unset($la_data_asig);			
			unset($la_data_pers);			
			unset($la_data_pres);			
			unset($la_data_cont);			
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
	unset($io_fun_viaticos);
?> 