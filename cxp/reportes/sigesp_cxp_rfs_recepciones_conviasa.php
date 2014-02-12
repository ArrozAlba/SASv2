<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Formato de salida  de Recepciones de Documentos
//  ORGANISMO: Ninguno en particular
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
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_p_recepcioncontable.php",$ls_descripcion);
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
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->saveState();
		$io_pdf->line(15,40,585,40);
		$io_pdf->line(480,700,480,760);
		$io_pdf->line(480,730,585,730);
        $io_pdf->Rectangle(15,700,570,60);
		$io_pdf->addJpegFromFile('../../shared/imagebank/conviasa.jpg',16,722,460,37); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=296-($li_tm/2);
		$io_pdf->addText($tm,711,11,$as_titulo); // Agregar el título
		$io_pdf->addText(485,740,9,"No. ".$as_numsol); // Agregar el título
		$io_pdf->addText(485,710,9,"Fecha ".$ad_fecregsol); // Agregar el título
		$io_pdf->addText(540,770,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(546,764,6,date("h:i a")); // Agregar la Hora
		// cuadro inferior
		$io_pdf->setStrokeColor(0,0,0);
        $io_pdf->Rectangle(15,60,570,70);
		$io_pdf->line(15,73,585,73);		
		$io_pdf->line(15,117,585,117);		
		$io_pdf->line(130,60,130,130);		
		$io_pdf->line(240,60,240,130);		
		$io_pdf->line(380,60,380,130);		
		$io_pdf->addText(40,122,7,"ELABORADO POR"); // Agregar el título
		$io_pdf->addText(42,63,7,"FIRMA / SELLO"); // Agregar el título
		$io_pdf->addText(157,122,7,"VERIFICADO POR"); // Agregar el título
		$io_pdf->addText(145,63,7,"FIRMA / SELLO / FECHA"); // Agregar el título
		$io_pdf->addText(275,122,7,"AUTORIZADO POR"); // Agregar el título
		$io_pdf->addText(257,63,7,"ADMINISTRACIÓN Y FINANZAS"); // Agregar el título
		$io_pdf->addText(440,122,7,"CONTRALORIA INTERNA"); // Agregar el título
		$io_pdf->addText(445,63,7,"FIRMA / SELLO / FECHA"); // Agregar el título
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_numrecdoc,$as_dentipdoc,$as_nombre,$as_proben,$ad_fecemidoc,$ad_fecrecdoc,$as_dencondoc,$as_codrecdoc,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_numrecdoc // Numero de la Recepcion de Documentos
		//	   			   as_dentipdoc // Denominacion de tipo de documento
		//	   			   as_nombre    // Nombre del Proveedor / Beneficiario
		//	   			   as_proben    // Indica si es  Proveedor / Beneficiario
		//	   			   ad_fecemidoc // Fecha de Emision de la Factura
		//	   			   ad_fecrecdoc // Fecha de recepcion del documento
		//	   			   as_dencondoc // Concepto de la Recepcion de Documentos
		//	   			   as_codrecdoc // Numero consecutivo de Recepcion de Documentos
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 22/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('titulo'=>'<b>Numero de Control Interno:</b>','contenido'=>$as_codrecdoc);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>90), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'left','width'=>480))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		if($as_proben=="P")
		{
			$la_data[1]=array('titulo'=>'<b>Proveedor:</b>','contenido'=>$as_nombre);
		}
		else
		{
			$la_data[1]=array('titulo'=>'<b>Beneficiario:</b>','contenido'=>$as_nombre);
		}
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>90), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'left','width'=>480))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('titulo'=>'<b>Documento:</b>','contenido'=>$as_dentipdoc);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>90), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'left','width'=>480))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$la_data[1]=array('titulo'=>'<b>Fecha de Emision:</b>','contenido'=>$ad_fecemidoc);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>90), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'left','width'=>480))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$la_data[1]=array('titulo'=>'<b>Concepto:</b>','contenido'=>$as_dencondoc,);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>90), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'left','width'=>480))); // Justificación y ancho de la columna
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
		//    Description: función que imprime el detalle de la recepcion de documentos
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);
		$la_datatit[1]=array('numrecdoc'=>'<b>Factura</b>','fecemisol'=>'<b>Fecha</b>','subtotdoc'=>'<b>Monto</b>',
							 'moncardoc'=>'<b>Cargos</b>','mondeddoc'=>'<b>Deducciones</b>','montotdoc'=>'<b>Total</b>');
		$la_columnas=array('numrecdoc'=>'<b>Factura</b>',
						   'fecemisol'=>'<b>Fecha</b>',
						   'subtotdoc'=>'<b>Monto</b>',
						   'moncardoc'=>'<b>Cargos</b>',
						   'mondeddoc'=>'<b>Deducciones</b>',
						   'montotdoc'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numrecdoc'=>array('justification'=>'center','width'=>130), // Justificación y ancho de la columna
						 			   'fecemisol'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'subtotdoc'=>array('justification'=>'center','width'=>92), // Justificación y ancho de la columna
						 			   'moncardoc'=>array('justification'=>'center','width'=>92), // Justificación y ancho de la columna
						 			   'mondeddoc'=>array('justification'=>'center','width'=>92), // Justificación y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'center','width'=>92))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);

		$la_columnas=array('numrecdoc'=>'<b>Factura</b>',
						   'fecemisol'=>'<b>Fecha</b>',
						   'subtotdoc'=>'<b>Monto</b>',
						   'moncardoc'=>'<b>Cargos</b>',
						   'mondeddoc'=>'<b>Deducciones</b>',
						   'montotdoc'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numrecdoc'=>array('justification'=>'center','width'=>130), // Justificación y ancho de la columna
						 			   'fecemisol'=>array('justification'=>'left','width'=>70), // Justificación y ancho de la columna
						 			   'subtotdoc'=>array('justification'=>'right','width'=>92), // Justificación y ancho de la columna
						 			   'moncardoc'=>array('justification'=>'right','width'=>92), // Justificación y ancho de la columna
						 			   'mondeddoc'=>array('justification'=>'right','width'=>92), // Justificación y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'right','width'=>92))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$la_datatot[1]=array('numrecdoc'=>'<b>Totales</b>','subtotdoc'=>$ai_totsubtot,
							 'moncardoc'=>$ai_totcar,'mondeddoc'=>$ai_totded,'montotdoc'=>$ai_tottot);
		$la_columnas=array('numrecdoc'=>'<b>Factura</b>',
						   'subtotdoc'=>'<b>Monto</b>',
						   'moncardoc'=>'<b>Cargos</b>',
						   'mondeddoc'=>'<b>Deducciones</b>',
						   'montotdoc'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numrecdoc'=>array('justification'=>'right','width'=>200), // Justificación y ancho de la columna
						 			   'subtotdoc'=>array('justification'=>'right','width'=>92), // Justificación y ancho de la columna
						 			   'moncardoc'=>array('justification'=>'right','width'=>92), // Justificación y ancho de la columna
						 			   'mondeddoc'=>array('justification'=>'right','width'=>92), // Justificación y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'right','width'=>92))); // Justificación y ancho de la columna
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
		if($ls_estmodest==1)
		{
			$ls_titcuentas="Estructura Presupuestaria";
		}
		else
		{
			$ls_titcuentas="Estructura Programatica";
		}
		$la_datatit[1]=array('titulo'=>'<b> Detalle de Presupuesto </b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
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
		$la_columnas=array('numrecdoc'=>'<b>Compromiso</b>',
						   'codestpro'=>'<b>'.$ls_titcuentas.'</b>',
						   'spg_cuenta'=>'<b>Cuenta</b>',
						   'monto'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numrecdoc'=>array('justification'=>'center','width'=>160), // Justificación y ancho de la columna
									   'codestpro'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna
						 			   'spg_cuenta'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>110))); // Justificación y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas,'',$la_config);
		$la_datatot[1]=array('titulo'=>'<b>Totales</b>','totpre'=>$ai_totpre);
		$la_columnas=array('titulo'=>'<b>Factura</b>',
						   'totpre'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>460), // Justificación y ancho de la columna
						 			   'totpre'=>array('justification'=>'right','width'=>110))); // Justificación y ancho de la columna
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
		$la_datatit[1]=array('titulo'=>'<b> Detalle de Contable </b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
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
		$la_columnas=array('numrecdoc'=>'<b>Compromiso</b>',
						   'sc_cuenta'=>'<b>Cuenta</b>',
						   'mondeb'=>'<b>Debe</b>',
						   'monhab'=>'<b>Haber</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numrecdoc'=>array('justification'=>'center','width'=>160), // Justificación y ancho de la columna
						 			   'sc_cuenta'=>array('justification'=>'center','width'=>190), // Justificación y ancho de la columna
						 			   'mondeb'=>array('justification'=>'right','width'=>110), // Justificación y ancho de la columna
						 			   'monhab'=>array('justification'=>'right','width'=>110))); // Justificación y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas,'',$la_config);
		$la_datatot[1]=array('titulo'=>'<b>Totales</b>',
							 'totdeb'=>$ai_totdeb,
							 'tothab'=>$ai_tothab);
		$la_columnas=array('titulo'=>'<b>Factura</b>',
						   'totdeb'=>'<b>Deducciones</b>',
						   'tothab'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>350), // Justificación y ancho de la columna
						 			   'totdeb'=>array('justification'=>'right','width'=>110), // Justificación y ancho de la columna
						 			   'tothab'=>array('justification'=>'right','width'=>110))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatot,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($ai_montotdoc,$ai_mondeddoc,$ai_moncardoc,$ai_monsubdoc,$ai_montotcar,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: ai_montotdoc // Monto Total del Documento
		//	   			   ai_mondeddoc // Monto Deduccion del Documento
		//	   			   ai_moncardoc // Monto Cargos del Documento
		//	   			   ai_monsubdoc // Monto Sub-Total (Sin Cargos ni Deducciones)
		//	   			   ai_montotcar // Monto Sub-Total Incluyendo Cargos
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los montos totales del documento
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 22/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);
		$la_data[1]=array('titulo'=>'<b>Sub-Total</b>','contenido'=>$ai_monsubdoc);
		$la_data[2]=array('titulo'=>'<b>Otros Creditos</b>','contenido'=>$ai_moncardoc);
		$la_data[3]=array('titulo'=>'<b>Total</b>','contenido'=>$ai_montotcar);
		$la_data[4]=array('titulo'=>'<b>Deducciones</b>','contenido'=>$ai_mondeddoc);
		$la_data[5]=array('titulo'=>'<b>Total General</b>','contenido'=>$ai_montotdoc);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
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
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>460), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>110))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

	}// end function uf_print_totales
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_retenciones_iva($ai_monretiva,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_retenciones_iva
		//		   Access: private 
		//	    Arguments: ai_monretiva // Monto Total de Retenciones de IVA
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los montos totales del documento
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 22/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-10);
		$la_data[1]=array('titulo'=>'<b>NOTA:</b> El documento tiene un monto de Retencion de I.V.A. de: <b>'.$ai_monretiva. '</b> el cual se reflejara en el modulo de Banco');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
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
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>380))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

	}// end function uf_print_totales
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_cxp_class_report.php");
	$io_report=new sigesp_cxp_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
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
	$ls_titulo="<b>RECEPCION DE DOCUMENTOS</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_numrecdoc=$io_fun_cxp->uf_obtenervalor_get("numrecdoc","");
	$ls_codpro=$io_fun_cxp->uf_obtenervalor_get("codpro","");
	$ls_cedben=$io_fun_cxp->uf_obtenervalor_get("cedben","");
	$ls_codtipdoc=$io_fun_cxp->uf_obtenervalor_get("codtipdoc","");
	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_cxp_class_report.php");
	$io_report=new sigesp_cxp_class_report();
	$ls_estretiva=$_SESSION["la_empresa"]["estretiva"];
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_select_recepcion($ls_numrecdoc,$ls_codpro,$ls_cedben,$ls_codtipdoc); // Cargar el DS con los datos del reporte
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
			$io_pdf->ezSetCmMargins(3.6,5,3,3); // Configuración de los margenes en centímetros
			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página
			$li_totrow=$io_report->DS->getRowCount("numrecdoc");
			$li_monretiva=0;
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_numrecdoc=$io_report->DS->data["numrecdoc"][$li_i];
				$ls_codrecdoc=$io_report->DS->data["codrecdoc"][$li_i];
				$ls_dentipdoc=$io_report->DS->data["dentipdoc"][$li_i];
				$ls_nombre=$io_report->DS->data["nombre"][$li_i];
				$ld_fecemidoc=$io_report->DS->data["fecemidoc"][$li_i];
				$ld_fecrecdoc=$io_report->DS->data["fecregdoc"][$li_i];
				$ls_dencondoc=$io_report->DS->data["dencondoc"][$li_i];
				$li_montotdoc=$io_report->DS->data["montotdoc"][$li_i];
				$li_mondeddoc=$io_report->DS->data["mondeddoc"][$li_i];
				$li_moncardoc=$io_report->DS->data["moncardoc"][$li_i];
				$li_monsubdoc=($li_montotdoc-$li_moncardoc+$li_mondeddoc);
				$li_montotcar=($li_montotdoc+$li_mondeddoc);
				$ld_fecemidoc=$io_funciones->uf_convertirfecmostrar($ld_fecemidoc);
				$ld_fecrecdoc=$io_funciones->uf_convertirfecmostrar($ld_fecrecdoc);
				if($ls_estretiva=="B")
				{
					$li_monretiva=$io_report->uf_select_det_deducciones($ls_numrecdoc,$ls_codpro,$ls_cedben,$ls_codtipdoc);
				}
				uf_print_encabezado_pagina($ls_titulo,$ls_numrecdoc,$ld_fecrecdoc,&$io_pdf);
				if($ls_codpro!="----------")
				{
					$ls_codigo=$ls_codpro;
					uf_print_cabecera($ls_numrecdoc,$ls_dentipdoc,$ls_nombre,"P",$ld_fecemidoc,$ld_fecrecdoc,$ls_dencondoc,$ls_codrecdoc,&$io_pdf);
				}
				else
				{
					$ls_codigo=$ls_cedben;
					uf_print_cabecera($ls_numrecdoc,$ls_dentipdoc,$ls_nombre,"B",$ld_fecemidoc,$ld_fecrecdoc,$ls_dencondoc,$ls_codrecdoc,&$io_pdf);
				}						
//////////////////////////   GRID DETALLE PRESUPUESTARIO		//////////////////////////////////////
				$lb_valido=$io_report->uf_select_detalle_recepcionspg($ls_numrecdoc,$ls_codpro,$ls_cedben,$ls_codtipdoc); // Cargar el DS con los datos del reporte
				if($lb_valido)
				{
					$li_totrowspg=$io_report->ds_detalle_spg->getRowCount("codestpro");
					$la_data="";
					$li_totpre=0;
					for($li_s=1;$li_s<=$li_totrowspg;$li_s++)
					{
						$ls_codestpro=trim($io_report->ds_detalle_spg->data["codestpro"][$li_s]);
						$ls_spgcuenta=trim($io_report->ds_detalle_spg->data["spg_cuenta"][$li_s]);
						$ls_numrecdoc=trim($io_report->ds_detalle_spg->data["numrecdoc"][$li_s]);
						$ls_numdoccom=trim($io_report->ds_detalle_spg->data["numdoccom"][$li_s]);
						$li_monto=$io_report->ds_detalle_spg->data["monto"][$li_s];
						$li_totpre=$li_totpre+$li_monto;
						$li_monto=number_format($li_monto,2,",",".");
						$io_fun_cxp->uf_formatoprogramatica($ls_codestpro,&$as_programatica);
						$la_data[$li_s]=array('numrecdoc'=>$ls_numdoccom,'codestpro'=>$as_programatica,
											  'spg_cuenta'=>$ls_spgcuenta,'monto'=>$li_monto);
					}	
					$li_totpre=number_format($li_totpre,2,",",".");
					uf_print_detalle_spg($la_data,$li_totpre,&$io_pdf);
					unset($la_data);
				}
//////////////////////////   GRID DETALLE PRESUPUESTARIO		//////////////////////////////////////
//////////////////////////      GRID DETALLE CONTABLE	   	//////////////////////////////////////
				$lb_valido=$io_report->uf_select_detalle_recepcionscg($ls_numrecdoc,$ls_codpro,$ls_cedben,$ls_codtipdoc); // Cargar el DS con los datos del reporte
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
						$ls_numrecdoc=trim($io_report->ds_detalle_scg->data["numrecdoc"][$li_s]);
						$li_monto=$io_report->ds_detalle_scg->data["monto"][$li_s];
						$ls_numdoccom=$io_report->ds_detalle_scg->data["numdoccom"][$li_s];
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
						$la_data[$li_s]=array('numrecdoc'=>$ls_numdoccom,'sc_cuenta'=>$ls_sccuenta,
											  'mondeb'=>$li_montodebe,'monhab'=>$li_montohab);
					}	
					$li_totdeb=number_format($li_totdeb,2,",",".");
					$li_tothab=number_format($li_tothab,2,",",".");
					uf_print_detalle_scg($la_data,$li_totdeb,$li_tothab,&$io_pdf);
					unset($la_data);
				}
				$li_mondeddoc=$li_mondeddoc-$li_monretiva;
				$li_montotdoc=$li_montotdoc+$li_monretiva;
				$li_montotdoc=number_format($li_montotdoc,2,",",".");
				$li_mondeddoc=number_format($li_mondeddoc,2,",",".");
				$li_moncardoc=number_format($li_moncardoc,2,",",".");
				$li_monsubdoc=number_format($li_monsubdoc,2,",",".");
				$li_montotcar=number_format($li_montotcar,2,",",".");
				uf_print_totales($li_montotdoc,$li_mondeddoc,$li_moncardoc,$li_monsubdoc,$li_montotcar,&$io_pdf);
				if($ls_estretiva=="B")
				{
					$li_monretiva=number_format($li_monretiva,2,",",".");
					uf_print_retenciones_iva($li_monretiva,$io_pdf);
				}
			}
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
