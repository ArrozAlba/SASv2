<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//    REPORTE: Formato de salida  de Solicitud de Ejecucion Presupuestaria
	//  ORGANISMO: INAMUJER
	//  ESTE FORMATO SE IMPRIME EN Bs Y EN BsF. SEGUN LO SELECCIONADO POR EL USUARIO
	//  MODIFICADO POR: ING.YOZELIN BARRAGAN         FECHA DE MODIFICACION : 14/08/2007
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
		global $io_fun_sep;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_sep->uf_load_seguridad_reporte("SEP","sigesp_sep_p_solicitud.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_numsol,$ad_fecregsol,$as_consol,&$io_pdf)
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
		// Modificado Por: Ing. Yozelin Barragan. 
		// Fecha Creación: 11/03/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->line(480,700,480,760);
		$io_pdf->line(480,730,585,730);
        $io_pdf->Rectangle(15,700,570,60);
		//$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=250-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$io_pdf->addText(485,740,9,"No. ".$as_numsol); // Agregar el título
		$io_pdf->addText(485,710,9,"Fecha ".$ad_fecregsol); // Agregar el título
		$io_pdf->addText(540,770,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(546,764,6,date("h:i a")); // Agregar la Hora
     	$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_numsol,$as_dentipsol,$as_denuniadm,$as_denfuefin,$as_coduniadm,$as_nombre,$as_consol,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_numsol    // numero de la solicitud de ejecucion presupuestaria
		//	   			   as_dentipsol // Denominacion del tipo de solicitud
		//	   			   as_denuniadm // Denominacion de la Unidad Ejecutora solicitante
		//	   			   as_denfuefin // Denominacion de la fuente de financiamiento
		//	   			   as_coduniadm // Codigo de la Unidad Ejecutora solicitante
		//	   			   as_nombre    // Nombre del Proveedor / Beneficiario
		//	   			   as_consol    // Concepto
		//	    		   io_pdf       // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 17/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('titulo'=>'<b> Tipo</b>','contenido'=>$as_dentipsol);
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
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'left','width'=>450))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$ls_uniadm=$as_coduniadm." - ".$as_denuniadm;
		$la_data[1]=array('titulo'=>'<b> Dependencia Solicitante</b>','contenido'=>$ls_uniadm);
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
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'left','width'=>450))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$io_pdf->ezSetDy(-5);
		$ls_dentipsol="DETALLE DE LOS MATERIALES,SUMINISTROS O SERVICIOS REQUERIDOS";
		$la_data[1]=array('titulo'=>'<b>'.$ls_dentipsol.'</b>');
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
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('codigo'=>'<b>Código</b>',
						   'denominacion'=>'<b>Denominacion</b>',
						   'cantidad'=>'<b>Cant.</b>',
						   'cosuni'=>'<b>Precio</b>',
						   'baseimp'=>'<b>Sub-Total</b>',
						   'cargo'=>'<b>Cargo</b>',
						   'montot'=>'<b>Total</b>');
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
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>115), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>125), // Justificación y ancho de la columna
						 			   'cantidad'=>array('justification'=>'left','width'=>50), // Justificación y ancho de la columna
						 			   'cosuni'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'baseimp'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'cargo'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'montot'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_cargos($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cargos
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_datatit[1]=array('titulo'=>'<b> Detalle de Cargos </b>');
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
		$la_columnas=array('codigo'=>'<b>Código</b>',
						   'dencar'=>'<b>Denominación</b>',
						   'monbasimp'=>'<b>Base Imp.</b>',
						   'monimp'=>'<b>Cargo</b>',
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
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>115), // Justificación y ancho de la columna
						 			   'dencar'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'monbasimp'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'monimp'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>95))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_cuentas($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
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
		$la_columnas=array('codestpro'=>'<b>'.$ls_titcuentas.'</b>',
						   'cuenta'=>'<b>Cuenta</b>',
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
						 'cols'=>array('codestpro'=>array('justification'=>'center','width'=>270), // Justificación y ancho de la columna
						 			   'cuenta'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($li_subtot,$li_totcar,$li_montot,$ls_monlet,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		    Acess: private 
		//	    Arguments: li_subtot // Subtotal del articulo
		//	    		   li_totcar  //  Total cargos
		//	    		   li_montot  // Monto total
		//	    		   ls_monlet   //Monto en letras
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los totales
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 17/03/07
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tipoformato;
		if($ls_tipoformato==1)
		{
		   $ls_titsub="Bs.F.";
		   $ls_titcar="Bs.F.";
		   $ls_tittot="Bs.F.";
		}
		else
		{
		   $ls_titsub="Bs.";
		   $ls_titcar="Bs.";
		   $ls_tittot="Bs.";
		}	
		$la_data[1]=array('titulo'=>'<b>Sub Total  '.$ls_titsub.'</b>','contenido'=>$li_subtot,);
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
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->ezSetDy(-2);
		$la_data[1]=array('titulo'=>'<b>Cargos  '.$ls_titcar.'</b>','contenido'=>$li_totcar,);
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
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('titulo'=>'<b>Total  '.$ls_tittot.'</b>','contenido'=>$li_montot,);
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
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_firmas_autorizadas($la_data,$la_data2,$la_data3,$as_consol,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////																					 
		$io_pdf->ezSetCmMargins(16,2,3,3); // Configuración de los margenes en centímetros
		$la_data_titulo[1]=array('titulo'=>$as_consol);
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>540))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_titulo,$la_columnas,'EXPOSICION DE MOTIVOS',$la_config);
		unset($la_data_titulo);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->ezSetDy(-2);
		$la_columna=array('director'=>'<b>DIRECTOR O DIRECTORA SOLICITANTE</b>',
 		                  'firma'=>'<b>FIRMA</b>');
						  
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('director'=>array('justification'=>'center','width'=>340), // Justificación y ancho de la columna
 						               'firma'=>array('justification'=>'center','width'=>200))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		
		//***********************************************************************************************
		$la_columna=array('apenom'=>'<b>APELLIDOS Y NOMBRES DEL RECEPTOR</b>',
 		                  'fecha'=>'<b>FECHA</b>',
						  'firma'=>'<b>FIRMA</b>');
						  
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('apenom'=>array('justification'=>'center','width'=>270), // Justificación y ancho de la columna
 						               'fecha'=>array('justification'=>'center','width'=>130),
									   'firma'=>array('justification'=>'center','width'=>140))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data2,$la_columna,'RECEPCION EN LA OFICINA DE GESTION ADMINISTRATIVA',$la_config);
		
		$la_data1=array(array('name'=>'APROBACION DE LA OFICINA DE GESTION ADMINISTRATIVA','name1'=>'RECEPCION AREA DE COMPRAS'));				
		$la_columna=array('name'=>'','name1'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('name'=>array('justification'=>'center','width'=>270),
						               'name1'=>array('justification'=>'center','width'=>270))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);	
		unset($la_data1);		
		unset($la_columna);		
		unset($la_config);
		$io_pdf->ezSetDy(-2);
        $la_columna=array('firma'=>'<b>FIRMA</b>',
 		                  'fecha'=>'<b>FECHA</b>',
						  'firma1'=>'<b>FIRMA</b>',
 		                  'fecha1'=>'<b>FECHA</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('firma'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna
 						               'fecha'=>array('justification'=>'center','width'=>70),
									   'firma1'=>array('justification'=>'center','width'=>200),
									   'fecha1'=>array('justification'=>'center','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data3,$la_columna,'',$la_config);	
		$io_pdf->ezSetCmMargins(3.6,2.5,3,3); // Configuración de los margenes en centímetros
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_sep.php");
	$io_fun_sep=new class_funciones_sep();
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	if($ls_estmodest==1)
	{
		$ls_titcuentas="Estructura Presupuestaria";
	}
	else
	{
		$ls_titcuentas="Estructura Programatica";
	}
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	 $ls_numsol=$io_fun_sep->uf_obtenervalor_get("numsol","");
	 $ls_tipoformato=$io_fun_sep->uf_obtenervalor_get("tipoformato",0);
	//--------------------------------------------------------------------------------------------------------------------------------
	 global $ls_tipoformato;
	 if($ls_tipoformato==1)
	 {
		require_once("sigesp_sep_class_reportbsf.php");
		$io_report=new sigesp_sep_class_reportbsf();
	 }
	 else
	 {
		require_once("sigesp_sep_class_report.php");
		$io_report=new sigesp_sep_class_report();
  	 }	
	 //Instancio a la clase de conversión de numeros a letras.
	 include("../../shared/class_folder/class_numero_a_letra.php");
	 $numalet= new class_numero_a_letra();
	 //imprime numero con los valore por defecto
	 //cambia a minusculas
	 $numalet->setMayusculas(1);
	 //cambia a femenino
	 $numalet->setGenero(1);
	 //cambia moneda
	 if($ls_tipoformato==1)
	 {
		 $numalet->setMoneda("Bolivares Fuerte");
	     $ls_moneda="EN Bs.F.";
	 }
	 else
	 {
		 $numalet->setMoneda("Bolivares");
	     $ls_moneda="EN Bs.";
  	 }	
	 //cambia prefijo
	 $numalet->setPrefijo("***");
	 //cambia sufijo
	 $numalet->setSufijo("***");
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	 $ls_titulo='<b>REQUISICION DE MATERIALES O SERVICIOS Y EXPOSICION DE MOTIVOS  '.$ls_moneda.'</b>';
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_select_solicitud($ls_numsol); // Cargar el DS con los datos del reporte
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
			$io_pdf->ezSetCmMargins(3.6,2.5,3,3); // Configuración de los margenes en centímetros
			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página
			$li_totrow=$io_report->DS->getRowCount("numsol");
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_numsol=$io_report->DS->data["numsol"][$li_i];
				$ls_dentipsol=$io_report->DS->data["dentipsol"][$li_i];
				$ls_coduniadm=$io_report->DS->data["coduniadm"][$li_i];
				$ls_denuniadm=$io_report->DS->data["denuniadm"][$li_i];
				$ls_denfuefin=$io_report->DS->data["denfuefin"][$li_i];
				$ls_codpro=$io_report->DS->data["cod_pro"][$li_i];
				$ls_cedbene=$io_report->DS->data["ced_bene"][$li_i];
				$ls_nombre=$io_report->DS->data["nombre"][$li_i];
				$ld_fecregsol=$io_report->DS->data["fecregsol"][$li_i];
				$ls_consol=$io_report->DS->data["consol"][$li_i];
				$li_monto=$io_report->DS->data["monto"][$li_i];
				$li_monbasimptot=$io_report->DS->data["monbasinm"][$li_i];
				$li_montotcar=$io_report->DS->data["montotcar"][$li_i];
				$numalet->setNumero($li_monto);
				$ls_monto= $numalet->letra();
				$li_monto=number_format($li_monto,2,",",".");
				$li_monbasimptot=number_format($li_monbasimptot,2,",",".");
				$li_montotcar=number_format($li_montotcar,2,",",".");
				$ld_fecregsol=$io_funciones->uf_convertirfecmostrar($ld_fecregsol);
				if($ls_codpro!="----------")
				{
					$ls_codigo=$ls_codpro;
				}
				else
				{
					$ls_codigo=$ls_cedbene;
				}						
				uf_print_encabezado_pagina($ls_titulo,$ls_numsol,$ld_fecregsol,$ls_consol,&$io_pdf);
				uf_print_cabecera($ls_numsol,$ls_dentipsol,$ls_denuniadm,$ls_denfuefin,$ls_coduniadm,$ls_nombre,$ls_consol,&$io_pdf);
				$io_report->ds_detalle->reset_ds();
				$lb_valido=$io_report->uf_select_dt_solicitud($ls_numsol); // Cargar el DS con los datos del reporte
				if($lb_valido)
				{
					$li_totrowdet=$io_report->ds_detalle->getRowCount("codigo");
					$la_data="";
					for($li_s=1;$li_s<=$li_totrowdet;$li_s++)
					{
						$ls_codigo=$io_report->ds_detalle->data["codigo"][$li_s];
						$ls_tipo=$io_report->ds_detalle->data["tipo"][$li_s];
						$ls_denominacion=$io_report->ds_detalle->data["denominacion"][$li_s];
						$ls_unidad=$io_report->ds_detalle->data["unidad"][$li_s];
						$li_cantidad=$io_report->ds_detalle->data["cantidad"][$li_s];
						$li_cosuni=$io_report->ds_detalle->data["monpre"][$li_s];
						$li_basimp=$li_cosuni*$li_cantidad;
						$li_monart=$io_report->ds_detalle->data["monto"][$li_s];
						
						if(($ls_tipo=="B")&&($ls_unidad=="M"))
						{
							$li_unidad=$io_report->uf_select_dt_unidad($ls_codigo);
							$li_basimp=$li_cosuni*($li_cantidad*$li_unidad);
						}
						$li_monart=number_format($li_monart,2,".","");
						$li_basimp=number_format($li_basimp,2,".","");
						$li_cargos=($li_monart-$li_basimp);
						if($ls_unidad=="M")
						{
							$ls_unidad="MAYOR";
						}
						else
						{
							$ls_unidad="DETAL";
						}
						
						$li_cosuni=number_format($li_cosuni,2,",",".");
						$li_basimp=number_format($li_basimp,2,",",".");
						$li_monart=number_format($li_monart,2,",",".");
						$li_cargos=number_format($li_cargos,2,",",".");
						$la_data[$li_s]=array('codigo'=>$ls_codigo,'denominacion'=>$ls_denominacion,'cantidad'=>$li_cantidad,
											  'cosuni'=>$li_cosuni,'baseimp'=>$li_basimp,'cargo'=>$li_cargos,'montot'=>$li_monart);
					}
					uf_print_detalle($la_data,&$io_pdf);
					unset($la_data);
					$lb_valido=$io_report->uf_select_dt_cargos($ls_numsol); // Cargar el DS con los datos del reporte
					if($lb_valido)
					{
						$li_totrowcargos=$io_report->ds_cargos->getRowCount("codigo");
						$la_data="";
						for($li_s=1;$li_s<=$li_totrowcargos;$li_s++)
						{
							$ls_codigo=$io_report->ds_cargos->data["codcar"][$li_s];
							$ls_dencar=$io_report->ds_cargos->data["dencar"][$li_s];
							$li_monbasimp=$io_report->ds_cargos->data["monbasimp"][$li_s];
							$li_monimp=$io_report->ds_cargos->data["monimp"][$li_s];
							$li_montocar=$io_report->ds_cargos->data["monto"][$li_s];
							$li_monbasimp=number_format($li_monbasimp,2,",",".");
							$li_monimp=number_format($li_monimp,2,",",".");
							$li_montocar=number_format($li_montocar,2,",",".");
							$la_data[$li_s]=array('codigo'=>$ls_codigo,'dencar'=>$ls_dencar,'monbasimp'=>$li_monbasimp,
												  'monimp'=>$li_monimp,'monto'=>$li_montocar);
						}	
						//uf_print_detalle_cargos($la_data,&$io_pdf);
						//unset($la_data);
						$lb_valido=$io_report->uf_select_dt_spgcuentas($ls_numsol); // Cargar el DS con los datos del reporte
						if($lb_valido)
						{
							$li_totrowcuentas=$io_report->ds_cuentas->getRowCount("codestpro1");
							$la_data="";
							for($li_s=1;$li_s<=$li_totrowcuentas;$li_s++)
							{
								$ls_codestpro1=trim($io_report->ds_cuentas->data["codestpro1"][$li_s]);
								$ls_codestpro2=trim($io_report->ds_cuentas->data["codestpro2"][$li_s]);
								$ls_codestpro3=trim($io_report->ds_cuentas->data["codestpro3"][$li_s]);
								$ls_codestpro4=trim($io_report->ds_cuentas->data["codestpro4"][$li_s]);
								$ls_codestpro5=trim($io_report->ds_cuentas->data["codestpro5"][$li_s]);
								$ls_spgcuenta=$io_report->ds_cuentas->data["spg_cuenta"][$li_s];
								if($ls_estmodest==1)
								{
									$ls_codestpro=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3;
								}
								else
								{
									$ls_codestpro=$ls_codestpro1." - ".$ls_codestpro2." - ".$ls_codestpro3." - ".$ls_codestpro4." - ".$ls_codestpro5;
								}
								
								$li_montocta=$io_report->ds_cuentas->data["monto"][$li_s];
								$li_montocta=number_format($li_montocta,2,",",".");
								
								$la_data[$li_s]=array('codestpro'=>$ls_codestpro,'cuenta'=>$ls_spgcuenta,'monto'=>$li_montocta);
							}	
							//uf_print_detalle_cuentas($la_data,&$io_pdf);
							//unset($la_data);
						}
					}
				}
			}
		}
		uf_print_piecabecera($li_monbasimptot,$li_montotcar,$li_monto,$ls_monto,&$io_pdf);
		$ls_varvacia="";	
		$li_datafir=2;
		for($li_i=1;$li_i<=$li_datafir;$li_i++)
		{ 
		   $la_datafir[$li_i]= array('director'=>$ls_varvacia,'firma'=>$ls_varvacia);	
		   
		   $la_datanom[$li_i]= array('apenom'=>$ls_varvacia,'fecha'=>$ls_varvacia,'firma'=>$ls_varvacia);	
		   
		   $la_datafim[$li_i]= array('firma'=>$ls_varvacia,'fecha'=>$ls_varvacia,'firma1'=>$ls_varvacia,'fecha1'=>$ls_varvacia);							   
		}
		uf_print_detalle_firmas_autorizadas($la_datafir,$la_datanom,$la_datafim,$ls_consol,&$io_pdf);					   										
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
