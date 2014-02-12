<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Formato de salida  de Solicitud de Pago
//  ORGANISMO: Foncrei
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";		
		print "</script>";		
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_p_solicitudpago.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_numsol,$ad_fecregsol,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_numsol // numero de la solicitud
		//	    		   ad_fecregsol // fecha de registro de la solicitud
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 11/03/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
        //$io_pdf->Rectangle(15,700,570,60);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->addText(375,690,11,$as_titulo); // Agregar el título
		$io_pdf->addText(485,690,9,"No. ".$as_numsol); // Agregar el título
		$io_pdf->addText(485,700,9,"Fecha ".$ad_fecregsol); // Agregar el título
		// cuadro inferior
		$ls_usuario=strtoupper($_SESSION['la_apeusu'].','.$_SESSION['la_nomusu']);
		$io_pdf->setColor(0,0,0);
		$io_pdf->Rectangle(14,40,581,80);
		$io_pdf->line(14,107,594,107);		
		$io_pdf->line(14,94,594,94);		
		$io_pdf->addText(80,110,7,"ELABORADO"); 
		$io_pdf->addText(20,96,7,"FECHA :            /               /");
		$io_pdf->addText(20,85,7,"FIRMA:");
		$io_pdf->addText(65,50,7,'<b>'.$ls_usuario.'</b>'); 
		$io_pdf->addText(80,42,7,"");
		$io_pdf->line(200,40,200,120);		
		$io_pdf->addText(250,110,7,"SUB-GERENCIA TESORERIA");
		$io_pdf->addText(210,96,7,"FECHA :            /               /");
        $io_pdf->addText(210,85,7,"FIRMA:");
		$io_pdf->addText(270,50,7,"YORQUIS VIVAS");
		$io_pdf->addText(275,42,7,"C.I. 6.893.685");
		$io_pdf->line(400,40,400,120);		
		$io_pdf->addText(450,110,7,"GERENTE ADMINISTRACION");
		$io_pdf->addText(410,96,7,"FECHA :            /               /");
	    $io_pdf->addText(410,85,7,"FIRMA:");
		$io_pdf->addText(468,50,7,"");
		$io_pdf->addText(478,42,7,"");
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_numsol,$as_codigo,$as_nombre,$as_denfuefin,$ad_fecemisol,$as_consol,$as_obssol,
							   $ai_monsol,$as_monto,$as_rifpro,$as_dentipdoc,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_numsol    // Numero de la Solicitud de Pago
		//	   			   as_codigo    // Codigo del Proveedor / Beneficiario
		//	   			   as_nombre    // Nombre del Proveedor / Beneficiario
		//	   			   as_denfuefin // Denominacion de la fuente de financiamiento
		//	   			   ad_fecemisol // Fecha de Emision de la Solicitud
		//	   			   as_consol    // Concepto de la Solicitud
		//	   			   as_obssol    // Observaciones de la Solicitud
		//	   			   ai_monsol    // Monto de la Solicitud
		//	   			   as_monto     // Monto de la Solicitud en letras
		//	   			   as_rifpro    // RIF del proveedor/beneficiario
		//	   			   as_dentipdoc // Denominacion del tipo de documento
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera 
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 17/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_rifemp=$_SESSION["la_empresa"]["rifemp"];
		
		$la_data[1]=array('titulo'=>'<b> RIF:</b>'.$ls_rifemp.'');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$la_data[1]=array('titulo'=>'<b> Beneficiario</b>                                                                                                                                                 <b>RIF: </b>'.$as_rifpro.'');
		$la_data[2]=array('titulo'=>"                            ".$as_nombre);
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('titulo'=>'<b> Concepto:          </b>'.$as_consol);
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$la_data[1]=array('titulo'=>'<b>Monto en Letras:   </b>'.$as_monto,);
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo=" Bs.F.";
		}
		else
		{
			$ls_titulo=" Bs.";
		}

		$la_data[1]=array('titulo'=>'<b>'.$ls_titulo.'</b>','contenido'=>$ai_monsol,);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>400), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'center','width'=>170))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$la_data[1]=array('titulo'=>'<b>Tipo de Documento:   </b>'.$as_dentipdoc,);
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_recepcion($la_data,$ai_totsubtot,$ai_tottot,$ai_totcar,$ai_totded,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				   ai_totsubtot // acumulado del subtotal
		//				   ai_tottot // acumulado del total
		//				   ai_totcar // acumulado de los cargos
		//				   ai_totded // acumulado de las deducciones
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo=" Bs.F.";
		}
		else
		{
			$ls_titulo=" Bs.";
		}
		$io_pdf->ezSetDy(-2);
		$la_datatit[1]=array('numrecdoc'=>'<b>Factura</b>','compromiso'=>'<b>Compromiso</b>','fecemisol'=>'<b>Fecha</b>','subtotdoc'=>'<b>Monto</b>',
							 'moncardoc'=>'<b>Cargos</b>','mondeddoc'=>'<b>Deducciones</b>','montotdoc'=>'<b>Total</b>');
		$la_columnas=array('numrecdoc'=>'<b>Factura</b>',
						   'compromiso'=>'<b>Compromiso</b>',
						   'fecemisol'=>'<b>Fecha</b>',
						   'subtotdoc'=>'<b>Monto</b>',
						   'moncardoc'=>'<b>Cargos</b>',
						   'mondeddoc'=>'<b>Deducciones</b>',
						   'montotdoc'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numrecdoc'=>array('justification'=>'center','width'=>102), // Justificación y ancho de la columna
						 			   'compromiso'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'fecemisol'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'subtotdoc'=>array('justification'=>'center','width'=>83), // Justificación y ancho de la columna
						 			   'moncardoc'=>array('justification'=>'center','width'=>75), // Justificación y ancho de la columna
						 			   'mondeddoc'=>array('justification'=>'center','width'=>75), // Justificación y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'center','width'=>83))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);

		$la_columnas=array('numrecdoc'=>'<b>Factura</b>',
						   'compromiso'=>'<b>Compromiso</b>',
						   'fecemisol'=>'<b>Fecha</b>',
						   'subtotdoc'=>'<b>Monto</b>',
						   'moncardoc'=>'<b>Cargos</b>',
						   'mondeddoc'=>'<b>Deducciones</b>',
						   'montotdoc'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numrecdoc'=>array('justification'=>'center','width'=>102), // Justificación y ancho de la columna
						 			   'compromiso'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'fecemisol'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'subtotdoc'=>array('justification'=>'right','width'=>83), // Justificación y ancho de la columna
						 			   'moncardoc'=>array('justification'=>'right','width'=>75), // Justificación y ancho de la columna
						 			   'mondeddoc'=>array('justification'=>'right','width'=>75), // Justificación y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'right','width'=>83))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$la_datatot[1]=array('numrecdoc'=>'<b>Totales</b>','subtotdoc'=>$ai_totsubtot,
							 'moncardoc'=>$ai_totcar,'mondeddoc'=>$ai_totded,'montotdoc'=>$ai_tottot);
		$la_columnas=array('numrecdoc'=>'<b>Factura</b>',
						   'subtotdoc'=>'<b>Monto</b>',
						   'moncardoc'=>'<b>Cargos</b>',
						   'mondeddoc'=>'<b>Deducciones</b>',
						   'montotdoc'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numrecdoc'=>array('justification'=>'right','width'=>252), // Justificación y ancho de la columna
						 			   'subtotdoc'=>array('justification'=>'right','width'=>83), // Justificación y ancho de la columna
						 			   'moncardoc'=>array('justification'=>'right','width'=>75), // Justificación y ancho de la columna
						 			   'mondeddoc'=>array('justification'=>'right','width'=>75), // Justificación y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'right','width'=>83))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatot,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_spg($aa_data,$ai_totpre,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: aa_data // arreglo de información
		//	    		   ai_totpre // monto total de presupuesto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle presupuestario
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		global $ls_estmodest;
		global $ls_tiporeporte;
		if($ls_estmodest==1)
		{
			$ls_titcuentas="Estructura Presupuestaria";
		}
		else
		{
			$ls_titcuentas="Estructura Programatica";
		}
		if($ls_tiporeporte==1)
		{
			$ls_titulo=" Bs.F.";
		}
		else
		{
			$ls_titulo=" Bs.";
		}
		$la_datatit[1]=array('titulo'=>'<b> Detalle de Presupuesto </b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('codestpro'=>'<b>'.$ls_titcuentas.'</b>',
						   'spg_cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Deniminacion</b>',
						   'monto'=>'<b>Monto</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codestpro'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna
						 			   'spg_cuenta'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas,'',$la_config);
		$la_datatot[1]=array('titulo'=>'<b></b>','totpre'=>'______________');
		$la_datatot[2]=array('titulo'=>'<b>Totales</b>','totpre'=>$ai_totpre);
		$la_columnas=array('titulo'=>'<b>Factura</b>',
						   'totpre'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>480), // Justificación y ancho de la columna
						 			   'totpre'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatot,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_scg($aa_data,$ai_totdeb,$ai_tothab,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: aa_data // arreglo de información
		//	    		   si_totdeb // total monto debe
		//	    		   si_tothab // total monto haber
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle contable
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo=" Bs.F.";
		}
		else
		{
			$ls_titulo=" Bs.";
		}
		$la_datatit[1]=array('titulo'=>'<b> CUENTAS CONTABLES </b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->ezSetDy(-2);
		$la_data[1]=array('sc_cuenta'=>'<b>Cuenta</b>','denominacion'=>'<b>Denominacion</b>','mondeb'=>'<b>Debe</b>',
						   'monhab'=>'<b>Haber</b>');
		$la_columnas=array('sc_cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominacion</b>',
						   'mondeb'=>'<b>Debe</b>',
						   'monhab'=>'<b>Haber</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('sc_cuenta'=>array('justification'=>'center','width'=>140), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'center','width'=>250), // Justificación y ancho de la columna
						 			   'mondeb'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'monhab'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		$la_columnas=array('sc_cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominacion</b>',
						   'mondeb'=>'<b>Debe</b>',
						   'monhab'=>'<b>Haber</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('sc_cuenta'=>array('justification'=>'center','width'=>140), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>250), // Justificación y ancho de la columna
						 			   'mondeb'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'monhab'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas,'',$la_config);
		$la_datatot[1]=array('titulo'=>'<b></b>','totdeb'=>'______________','tothab'=>'______________');
		$la_datatot[2]=array('titulo'=>'<b>Totales</b>','totdeb'=>$ai_totdeb,'tothab'=>$ai_tothab);
		$la_columnas=array('titulo'=>'<b>Factura</b>',
						   'totdeb'=>'<b>Deducciones</b>',
						   'tothab'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>390), // Justificación y ancho de la columna
						 			   'totdeb'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'tothab'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatot,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_observacion($as_obssol,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_observacion
		//		   Access: private 
		//	    Arguments: as_obssol    // Observaciones de la Solicitud de Pago
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime las observaciones de la Solicitud de Pago
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 27/08/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('titulo'=>'<b> Observaciones:</b>'.$as_obssol.'');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

		//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total_bsf($ai_monsolaux,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: ai_monsolaux // Monto Auxiliar en Bs.F.
		//	    		   io_pdf       // Instancia de objeto pdf
		//    Description: Funcion que imprime el monto total de la solicitud en Bs.F.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_datatot[1]=array('titulo'=>'<b>Total Bs.F.</b>','monto'=>$ai_monsolaux);
		$la_columnas=array('titulo'=>'<b>Factura</b>',
						   'monto'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>480), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatot,$la_columnas,'',$la_config);
	}// end function uf_print_total_bsf
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	require_once("../../shared/class_folder/class_datastore.php");
	$ds_dentipdoc    = new class_datastore();
	//Instancio a la clase de conversión de numeros a letras.
	include("../../shared/class_folder/class_numero_a_letra.php");
	$numalet= new class_numero_a_letra();
	//imprime numero con los valore por defecto
	//cambia a minusculas
	$numalet->setMayusculas(1);
	//cambia a femenino
	$numalet->setGenero(1);
	//cambia moneda
	$numalet->setMoneda("Bolivares");
	//cambia prefijo
	$numalet->setPrefijo("***");
	//cambia sufijo
	$numalet->setSufijo("***");
		
	if($ls_estmodest==1)
	{
		$ls_titcuentas="Estructura Presupuestaria";
	}
	else
	{
		$ls_titcuentas="Estructura Programatica";
	}
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>ORDEN DE PAGO</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_numsol=$io_fun_cxp->uf_obtenervalor_get("numsol","");
	$ls_tiporeporte=0;//$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	require_once("../../shared/ezpdf/class.ezpdf.php");
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_cxp_class_reportbsf.php");
		$io_report=new sigesp_cxp_class_reportbsf();
	}
	else
	{
		require_once("sigesp_cxp_class_report.php");
		$io_report=new sigesp_cxp_class_report();
	}	
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_select_solicitud_tipodocumento($ls_numsol); // Cargar el DS con los datos del reporte
		if($lb_valido==false) // Existe algún error ó no hay registros
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}
		else  // Imprimimos el reporte
		{
			error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(4,2.5,3,3); // Configuración de los margenes en centímetros
			$li_totrow=$io_report->DS->getRowCount("numsol");
			if($li_totrow>1)
			{
				 for($li_i=1;$li_i<=$li_totrow;$li_i++)
				 {
					  $ls_denominacion= $io_report->DS->data["dentipdoc"][$li_i];
					  $ds_dentipdoc->insertRow("dentipsol",$ls_denominacion);
				 }
				 $ds_dentipdoc->group("dentipsol");
				 $li_fila=$ds_dentipdoc->getRowCount("dentipsol");
				 for($li_i=1;$li_i<=$li_fila;$li_i++)
				 {
					$ls_denominacion=$ds_dentipdoc->getValue("dentipsol",$li_i);   
					if($ls_denominacion!="")
					{
						if($li_i==1)
						{
							$ls_dentipdoc=$ls_denominacion;
						}
						else
						{
							$ls_dentipdoc=$ls_dentipdoc.", ".$ls_denominacion;
						}
					}				  
				 }
			}
			else
			{
				$ls_dentipdoc   = $io_report->DS->data["dentipdoc"][1];
			}
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_numsol=$io_report->DS->data["numsol"][$li_i];
				$ls_codpro=$io_report->DS->data["cod_pro"][$li_i];
				$ls_cedbene=$io_report->DS->data["ced_bene"][$li_i];
				$ls_denfuefin=$io_report->DS->data["denfuefin"][$li_i];
				$ls_nombre=$io_report->DS->data["nombre"][$li_i];
				$ls_rifpro=$io_report->DS->data["rifpro"][$li_i];
				$ld_fecemisol=$io_report->DS->data["fecemisol"][$li_i];
				$ls_consol=$io_report->DS->data["consol"][$li_i];
				$ls_obssol=$io_report->DS->data["obssol"][$li_i];
				$li_monsol=$io_report->DS->data["monsol"][$li_i];
				$numalet->setNumero($li_monsol);
				$ls_monto= $numalet->letra();
				$li_monsol=number_format($li_monsol,2,",",".");
				$ld_fecemisol=$io_funciones->uf_convertirfecmostrar($ld_fecemisol);
				if($ls_codpro!="----------")
				{
					$ls_codigo=$ls_codpro;
				}
				else
				{
					$ls_codigo=$ls_cedbene;
				}						
				if($ls_tiporeporte==1)
				{
					$li_monsolaux=$io_report->DS->data["monsolaux"][$li_i];
					$li_monsolaux=number_format($li_monsolaux,2,",",".");
				}
				uf_print_encabezado_pagina($ls_titulo,$ls_numsol,$ld_fecemisol,&$io_pdf);
				uf_print_cabecera($ls_numsol,$ls_codigo,$ls_nombre,$ls_denfuefin,$ld_fecemisol,$ls_consol,$ls_obssol,
								  $li_monsol,$ls_monto,$ls_rifpro,$ls_dentipdoc,&$io_pdf);
//////////////////////////  GRID RECEPCIONES DE DOCUMENTOS		//////////////////////////////////////
				$io_report->ds_detalle->reset_ds();
				$lb_valido=$io_report->uf_select_rec_doc_solicitud($ls_numsol); // Cargar el DS con los datos del reporte
				if($lb_valido)
				{
					$li_totrowdet=$io_report->ds_detalle_rec->getRowCount("numrecdoc");
					$la_data="";
					$li_totsubtot=0;
					$li_tottot=0;
					$li_totcar=0;
					$li_totded=0;
					for($li_s=1;$li_s<=$li_totrowdet;$li_s++)
					{
						$ls_numrecdoc=$io_report->ds_detalle_rec->data["numrecdoc"][$li_s];
						$ld_fecemidoc=$io_report->ds_detalle_rec->data["fecemidoc"][$li_s];
						$ls_numdoccomspg=$io_report->ds_detalle_rec->data["numdoccomspg"][$li_s];
						$li_mondeddoc=$io_report->ds_detalle_rec->data["mondeddoc"][$li_s];
						$li_moncardoc=$io_report->ds_detalle_rec->data["moncardoc"][$li_s];
						$li_montotdoc=$io_report->ds_detalle_rec->data["montotdoc"][$li_s];
						$ls_procededoc=$io_report->ds_detalle_rec->data["procede_docspg"][$li_s];
						if($ls_procededoc=="")
						{
							$ls_procededoc=$io_report->ds_detalle_rec->data["procede_docscg"][$li_s];
						}				  
						$ls_documento=$io_report->ds_detalle_rec->data["numdoccomspg"][$li_s];
						if($ls_documento=="")
						{
							$ls_documento=$io_report->ds_detalle_rec->data["numdoccomscg"][$li_s];
						}	
						$ls_compromiso=$ls_procededoc." - ".$ls_documento;
						$li_subtotdoc=($li_montotdoc-$li_moncardoc+$li_mondeddoc);
						$li_totsubtot=$li_totsubtot + $li_subtotdoc;
						$li_tottot=$li_tottot + $li_montotdoc;
						$li_totcar=$li_totcar + $li_moncardoc;
						$li_totded=$li_totded + $li_mondeddoc;

						$ld_fecemidoc=$io_funciones->uf_convertirfecmostrar($ld_fecemidoc);
						$li_mondeddoc=number_format($li_mondeddoc,2,",",".");
						$li_moncardoc=number_format($li_moncardoc,2,",",".");
						$li_montotdoc=number_format($li_montotdoc,2,",",".");
						$li_subtotdoc=number_format($li_subtotdoc,2,",",".");
						$la_data[$li_s]=array('numrecdoc'=>$ls_numrecdoc,'compromiso'=>$ls_compromiso,'fecemisol'=>$ld_fecemidoc,'mondeddoc'=>$li_mondeddoc,
											  'moncardoc'=>$li_moncardoc,'montotdoc'=>$li_montotdoc,'subtotdoc'=>$li_subtotdoc);
					}
					$li_totsubtot=number_format($li_totsubtot,2,",",".");
					$li_tottot=number_format($li_tottot,2,",",".");
					$li_totcar=number_format($li_totcar,2,",",".");
					$li_totded=number_format($li_totded,2,",",".");
					uf_print_detalle_recepcion($la_data,$li_totsubtot,$li_tottot,$li_totcar,$li_totded,&$io_pdf);
					unset($la_data);
//////////////////////////  GRID RECEPCIONES DE DOCUMENTOS		//////////////////////////////////////
//////////////////////////   GRID DETALLE PRESUPUESTARIO		//////////////////////////////////////
					$lb_valido=$io_report->uf_select_detalle_spg($ls_numsol); // Cargar el DS con los datos del reporte
					if($lb_valido)
					{
						$li_totrowspg=$io_report->ds_detalle_spg->getRowCount("codestpro");
						$la_data="";
						$li_totpre=0;
						for($li_s=1;$li_s<=$li_totrowspg;$li_s++)
						{
							$ls_codestpro=$io_report->ds_detalle_spg->data["codestpro"][$li_s];
							$ls_spgcuenta=$io_report->ds_detalle_spg->data["spg_cuenta"][$li_s];
							$ls_denominacion=$io_report->ds_detalle_spg->data["denominacion"][$li_s];
							$li_monto=$io_report->ds_detalle_spg->data["monto"][$li_s];
							$li_totpre=$li_totpre+$li_monto;
							$li_monto=number_format($li_monto,2,",",".");
							$io_fun_cxp->uf_formatoprogramatica($ls_codestpro,&$ls_programatica);
							$la_data[$li_s]=array('codestpro'=>$ls_programatica,'spg_cuenta'=>$ls_spgcuenta,
												  'denominacion'=>$ls_denominacion,'monto'=>$li_monto);
						}	
						$li_totpre=number_format($li_totpre,2,",",".");
						uf_print_detalle_spg($la_data,$li_totpre,&$io_pdf);
						unset($la_data);
					}
//////////////////////////      GRID DETALLE PRESUPUESTARIO		//////////////////////////////////////
//////////////////////////         GRID DETALLE CONTABLE	    //////////////////////////////////////
					$lb_valido=$io_report->uf_select_detalle_scg($ls_numsol); // Cargar el DS con los datos del reporte
					if($lb_valido)
					{
						$li_totrowscg=$io_report->ds_detalle_scg->getRowCount("sc_cuenta");
						$la_data="";
						$li_totdeb=0;
						$li_tothab=0;
						for($li_s=1;$li_s<=$li_totrowscg;$li_s++)
						{
							$ls_sccuenta=trim($io_report->ds_detalle_scg->data["sc_cuenta"][$li_s]);
							$ls_debhab=trim($io_report->ds_detalle_scg->data["debhab"][$li_s]);
							$ls_denominacion=trim($io_report->ds_detalle_scg->data["denominacion"][$li_s]);
							$li_monto=$io_report->ds_detalle_scg->data["monto"][$li_s];
							if($ls_debhab=="D")
							{
								$li_montodebe=$li_monto;
								$li_montohab="";
								$li_totdeb=$li_totdeb+$li_montodebe;
								$li_montodebe=number_format($li_montodebe,2,",",".");
							}
							else
							{
								$li_montodebe="";
								$li_montohab=$li_monto;
								$li_tothab=$li_tothab+$li_montohab;
								$li_montohab=number_format($li_montohab,2,",",".");
							}
							$la_data[$li_s]=array('sc_cuenta'=>$ls_sccuenta,'denominacion'=>$ls_denominacion,
												  'mondeb'=>$li_montodebe,'monhab'=>$li_montohab);
						}	
						$li_totdeb=number_format($li_totdeb,2,",",".");
						$li_tothab=number_format($li_tothab,2,",",".");
						uf_print_detalle_scg($la_data,$li_totdeb,$li_tothab,&$io_pdf);
						unset($la_data);
					}
//////////////////////////      GRID DETALLE CONTABLE	   	//////////////////////////////////////
					uf_print_observacion($ls_obssol,&$io_pdf);
				}
			}
		}
		if($ls_tiporeporte==1)
		{
			uf_print_total_bsf($li_monsolaux,&$io_pdf);
		}
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		
	}

?>
