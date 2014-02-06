<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//  ESTE FORMATO SE IMPRIME EN Bs Y EN BsF. SEGUN LO SELECCIONADO POR EL USUARIO
	//  MODIFICADO POR: ING.YOZELIN BARRAGAN         FECHA DE MODIFICACION : 28/08/2007
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
	function uf_print_encabezado_pagina($as_titulo,$as_cmpmov,$ad_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_cmpmov // numero de comprobante de movimiento
		//	    		   ad_fecha // Fecha 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->rectangle(261,530,250,25);
		$io_pdf->rectangle(290,535,10,10);
		$io_pdf->line(290,535,300,545);		
		$io_pdf->line(290,545,300,535);		
		$io_pdf->addText(305,535,10,"Bienes Muebles"); // Agregar texto
		$io_pdf->rectangle(420,535,10,10);
		$io_pdf->addText(435,535,10,"Materiales"); // Agregar texto
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],17,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=386-($li_tm/2);
		$io_pdf->addText($tm,560,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$ad_fecha);
		$tm=312-($li_tm/2);
		$io_pdf->addText($tm,535,10,$ad_fecha); // Agregar el título
		$io_pdf->addText(670,550,8,"No.:  ".$as_cmpmov); // Agregar la Fecha
		$io_pdf->addText(660,530,8,"Fecha:  ".date("d/m/Y")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codemp,$as_nomemp,$ls_coduniadm,$ls_denuniadm,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codemp     // codigo de empresa
		//	    		   as_nomemp     // nombre de empresa
		//	    		   ls_coduniadm  // codigo de unidad administrativas
		//	    		   ls_denuniadm  // denominacion de unidad administrativas
		//	    		   ls_codres     // codigo de responsable
		//	    		   ls_nomres     // nombre de responsable
		//	    		   io_pdf        // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->rectangle(17,456,748,66);
		$io_pdf->addText(45,511,9,"<b>Organismo</b>"); // Agregar texto
		$io_pdf->addText(50,495,9,$as_codemp); // Agregar texto
		$io_pdf->addText(100,495,9,$as_nomemp); // Agregar texto
		$io_pdf->line(95,508,95,492);
		$io_pdf->line(17,508,765,508);
		$io_pdf->line(17,490,765,490);
		$io_pdf->addText(45,479,9,"<b>Unidad Administradora</b>"); // Agregar texto
		$io_pdf->addText(30,461,9,$ls_coduniadm); // Agregar texto
		$io_pdf->addText(100,461,9,$ls_denuniadm); // Agregar texto
		$io_pdf->line(95,475,95,456);
		$io_pdf->line(17,475,765,475);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
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
		global $ls_tipoformato;
		if($ls_tipoformato==0)
		{
		  $ls_titulo=" Bs.";
		}
		elseif($ls_tipoformato==1)
		{
		  $ls_titulo=" Bs.F.";
		}
		$la_columna=array('cantidad'=>'<b>Cantidad</b>',
						  'catalogo'=>'<b>Código del Catálogo</b>',
						  'codact'=>'<b>Numero de Inventario</b>',
						  'denact'=>'<b>Descripción</b>',
						  'codcau'=>'<b>Incorporación</b>',
						  'costo'=>'<b>Costo '.$ls_titulo.'</b>',
						  'total'=>'<b>Total '.$ls_titulo.'</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cantidad'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna
						 			   'catalogo'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'codact'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'denact'=>array('justification'=>'left','width'=>173), // Justificación y ancho de la columna
						 			   'codcau'=>array('justification'=>'left','width'=>230), // Justificación y ancho de la columna
						 			   'costo'=>array('justification'=>'right','width'=>75), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>85))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($as_nomres,$as_cedres,$as_cargo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: as_nomres // nombre del responsable
		//	   			   as_cedres // cedula del responsable
		//	   			   as_cargo  // cargo del responsable
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funcion que imprime el cuadro inferior del responsable
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 27/09/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->rectangle(17,30,748,50);
		$io_pdf->addText(22,70,9,"<b>Responsable Patrimonial Primario</b>"); // Agregar texto
		$io_pdf->line(17,67,765,67);
		$io_pdf->line(17,55,765,55);
		$io_pdf->addText(47,58,9,"<b>Cédula de Identidad</b>"); // Agregar texto
		$io_pdf->line(170,30,170,67);
		$io_pdf->addText(65,40,9,$as_cedres); // Agregar texto
		$io_pdf->addText(280,58,9,"<b>Apellidos y Nombres</b>"); // Agregar texto
		$io_pdf->addText(200,40,9,$as_nomres); // Agregar texto
		$io_pdf->line(500,30,500,67);
		$io_pdf->addText(620,58,9,"<b>Cargo</b>"); // Agregar texto
		$io_pdf->addText(530,40,9,$as_cargo); // Agregar texto
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_funciones_activos.php");
	$io_fun_activos=new class_funciones_activos();
	$ls_tipoformato=$io_fun_activos->uf_obtenervalor_get("tipoformato",0);
	global $ls_tipoformato;
	if($ls_tipoformato==1)
	{
		require_once("sigesp_saf_class_reportbsf.php");
		$io_report=new sigesp_saf_class_reportbsf();
		$ls_titulo_report="Bs.F.";
	}
	else
	{
		require_once("sigesp_saf_class_report.php");
		$io_report=new sigesp_saf_class_report();
		$ls_titulo_report="Bs.";
	}	
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_coduniadm=$io_fun_activos->uf_obtenervalor_get("coduniadm","");
	$ls_codres=$io_fun_activos->uf_obtenervalor_get("codres","");
	$ls_titulo="<b>Comprobante de Incorporaciones  ".$ls_titulo_report."</b>";
	$ls_fecha="";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_nomemp=$arre["nombre"];
	$ls_cmpmov=$io_fun_activos->uf_obtenervalor_get("cmpmov","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_saf_load_dt_compmovimiento($ls_codemp,$ls_cmpmov,$ls_codres); // Cargar el DS con los datos de la cabecera del reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_saf_load_unidadadministrativas($ls_codemp,$ls_coduniadm,$ls_denuniadm);
		if($lb_valido)
		{
			if($ls_codres!="")
			{
				$lb_valido=$io_report->uf_saf_load_responsable($ls_codemp,$ls_codres,$ls_nomres,$ls_cedres,$ls_cargo);
			}
			else
			{
				$ls_nomres="";
				$ls_cedres="";
				$ls_cargo="";
			}
		}
	}
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		//print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		/////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////
		$ls_desc_event=" Generó el reporte de Comprobante de Incorporaciones. ";
		$io_fun_activos->uf_load_seguridad_reporte("SAF","sigesp_saf_r_compincorporacion.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////////
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(5.6,3,3,3); // Configuración de los margenes en centímetros
		$ld_fecha=$io_report->ds_detalle->data["feccmp"][1];
		$ld_fecha=$io_funciones->uf_convertirfecmostrar($ld_fecha);
		uf_print_encabezado_pagina($ls_titulo,$ls_cmpmov,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->transaction('start'); // Iniciamos la transacción
		$li_numpag=$io_pdf->ezPageCount; // Número de página
		$ls_cmpmov=$io_report->ds_detalle->data["cmpmov"][1];
		$ls_codcau=$io_report->ds_detalle->data["codcau"][1];
		$ls_dencau=$io_report->ds_detalle->data["dencau"][1];
		uf_print_cabecera($ls_codemp,$ls_nomemp,$ls_coduniadm,$ls_denuniadm,$io_pdf); // Imprimimos la cabecera del registro
		uf_print_totales($ls_nomres,$ls_cedres,$ls_cargo,$io_pdf);
		if($lb_valido)
		{
			$li_aux=0;
			$li_totrow_det=$io_report->ds_detalle->getRowCount("codact");
			//print_r($io_report->ds_detalle->data)."<br>";
			for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
			{
				$ls_auxcoduniadm= $io_report->ds_detalle->data["coduniadm"][$li_s];
				$ls_codart=       $io_report->ds_detalle->data["codact"][$li_s];
				$ls_denart=       $io_report->ds_detalle->data["denact"][$li_s];
				$ls_catalogo=     $io_report->ds_detalle->data["catalogo"][$li_s];
				$li_ideact=       $io_report->ds_detalle->data["ideact"][$li_s];
				$ls_codcau=       $io_report->ds_detalle->data["codcau"][$li_s];
				$ls_dencau=       $io_report->ds_detalle->data["dencau"][$li_s];
				$li_costo=        $io_report->ds_detalle->data["costo"][$li_s];
				$li_cantidad=     $io_report->ds_detalle->data["cantidad"][$li_s];
				$li_total=($li_costo * $li_cantidad);
				$li_cantidad=$io_fun_activos->uf_formatonumerico($li_cantidad);
				$li_costo=$io_fun_activos->uf_formatonumerico($li_costo);
				$li_total=$io_fun_activos->uf_formatonumerico($li_total);
				if($ls_auxcoduniadm==$ls_coduniadm)
				{
					$li_aux=$li_aux + 1;
					$la_data[$li_aux]=array('cantidad'=>$li_cantidad,'catalogo'=>$ls_catalogo,'codact'=>$ls_codart,'denact'=>$ls_denart,
										  'codcau'=>$ls_codcau." ".$ls_dencau,'costo'=>$li_costo,'total'=>$li_total);
				}
			}
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		}
		unset($la_data);			
		unset($la_datat);			
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