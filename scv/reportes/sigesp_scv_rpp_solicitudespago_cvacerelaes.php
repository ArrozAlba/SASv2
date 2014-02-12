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
	ini_set('memory_limit','2048M');
	ini_set('max_execution_time ','0');	
//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_codsolvia,$ad_fecsolvia,&$io_encabezado,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_codsolvia // Código de Solicitud de Viaticos
		//	    		   ad_fecsolvia // Fecha de Solicitud de Viaticos
		//	    		   io_encabezado // Instancia del encabezado
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();				
		$ad_fecsolvia=$io_funciones->uf_convertirfecmostrar($ad_fecsolvia);
		$io_pdf->saveState();
		$io_pdf->line(35,40,570,40);
		$io_pdf->line(30,725,570,725);
		$io_pdf->line(30,640,570,640);
		$io_pdf->line(30,725,30,640);
		//$io_pdf->line(150,785,150,700);
		$io_pdf->line(450,725,450,640);
		$io_pdf->line(570,725,570,640);
		$io_pdf->line(450,703.75,570,703.75);
		$io_pdf->line(450,682.5,570,682.5);
		$io_pdf->line(450,661.25,570,661.25);
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(451,704.75,118,$io_pdf->getFontHeight(16.8));
        $io_pdf->filledRectangle(451,662.25,118,$io_pdf->getFontHeight(16.8));
        $io_pdf->setColor(0,0,0);		
		$io_pdf->addText(493,710,11," FECHA"); // Agregar FECHA
		$io_pdf->addText(483,688,11,$ad_fecsolvia); // Agregar FECHA
		$io_pdf->addText(468,667,11," N° DE CONTROL"); // Agregar NRO DE CONTROL
		$io_pdf->addText(487,646,11,$as_codsolvia); // Agregar NRO DE CONTROL
		$io_pdf->addText(165,680,12,"<b>SOLICITUD PAGO DE VIATICOS</b>"); // Agregar NRO DE CONTROL
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],19,730,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_cedper,$as_nomper,$as_desuniadm,$as_codcueban,$as_tipcuebanper,$as_desded,$as_destipper,
							   $as_codclavia,$ad_fecsalvia,$ad_fecregvia,$ai_numdiavia,$as_denmis,$ai_acompanante,$as_cargo,$as_obssolvia,
							   &$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: $as_cedper  // Cédula de personal
		//	    		   $as_nomper     // Nombre del personal
		//	    		   $as_desuniadm     // descripción de la unidad administrativa
		//	    		   $as_codcueban     // codigo cuenta de banco
		//	    		   $as_tipcuebanper     // tipo de cuenta de banco
		//	    		   $as_desded  // Descripción de la dedicación
		//	    		   $as_destipper  // Descripción del tipo de personal
		//	    		   $as_codclavia  // Clasificación del viaticos
		//	    		   $ad_fecsalvia  // fecha de salida del viatico
		//	    		   $ad_fecregvia  // fecha de regreso del viatico
		//	    		   $ai_numdiavia     // numero de dias
		//	    		   $as_denmis  // Denominación de las misiones
		//	    		   $as_obssolvia  // Observacion de la solicitud
		//	    		   io_pdf         // Instancia del objeto pdf
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/11/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();				
		$ad_fecsalvia=$io_funciones->uf_convertirfecmostrar($ad_fecsalvia);
		$ad_fecregvia=$io_funciones->uf_convertirfecmostrar($ad_fecregvia);
		$ai_numdiavia=number_format($ai_numdiavia,2,",",".");
        $io_pdf->setColor(0,0,0);		
		$la_data=array(array('titulo'=>'<b>DATOS DEL FUNCIONARIO ACTUALIZADO PARA LA MISION</b>'));
		$la_columna=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>540))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$io_pdf->ezSetDy(-2);
		$la_data=array(array('nombre'=>$as_nomper,'cedula'=>$as_cedper,'cargo'=>$as_cargo,'unidad'=>$as_desuniadm,'categoria'=>$as_desded.' '.$as_destipper));
		$la_columna=array('nombre'=>'<b> Nombre del Funcionario</b>','cedula'=>'<b> Cédula de Identidad N°</b>',
						  'cargo'=>'<b> Denominación del Cargo</b>','unidad'=>'<b> Ubicación Administrativa</b>','categoria'=>'<b> Categoria</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0	, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('nombre'=>array('justification'=>'center','width'=>110),
						       		   'cedula'=>array('justification'=>'center','width'=>100),
									   'cargo'=>array('justification'=>'center','width'=>100),
									   'unidad'=>array('justification'=>'center','width'=>100),
									   'categoria'=>array('justification'=>'center','width'=>130))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		switch($as_tipcuebanper)
		{
			case "A": //Ahorro
				$ls_ahorro="X";
				$ls_corriente="";
				break;
			case "C": // Corriente
				$ls_ahorro="";
				$ls_corriente="X";
				break;
			case "": // Ninguna
				$ls_ahorro="";
				$ls_corriente="";
				break;
		}
		$la_data=array(array('titulo'=>'<b> N° de Cuenta Nómina</b>','corriente'=>$ls_corriente,'titulocorriente'=>'Corriente',
							 'ahorro'=>$ls_ahorro,'tituloahorro'=>'Ahorro','cuenta'=>$as_codcueban));
		$la_columna=array('titulo'=>'','corriente'=>'','titulocorriente'=>'','ahorro'=>'','tituloahorro'=>'','cuenta'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0	, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>110),
						       		   'corriente'=>array('justification'=>'center','width'=>20),
									   'titulocorriente'=>array('justification'=>'center','width'=>80),
									   'ahorro'=>array('justification'=>'center','width'=>20),
									   'tituloahorro'=>array('justification'=>'center','width'=>80),
									   'cuenta'=>array('justification'=>'left','width'=>230))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('titulo'=>'<b>DESCRIPCIÓN DE LA SOLICITUD</b>'));
		$la_columna=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>540))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$io_pdf->ezSetDy(-1.08);
		$ls_acompanante = "";
		if($ai_acompanante==1)
		{
			$ls_acompanante = "NO";
		}
		if($ai_acompanante>1)
		{
			$ls_acompanante = "SI";
		}
		$la_data=array(array('dependencia'=>'<b> Dependencia Solicitante:</b> '.$as_desuniadm));
		$la_columna=array('dependencia'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0	, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('dependencia'=>array('justification'=>'left','width'=>540))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('desde'=>'<b> Periodo Desde:</b> '.$ad_fecsalvia,'hasta'=>'<b>Periodo Hasta:</b> '.$ad_fecregvia,'dias'=>'<b> Total Días:</b> '.$ai_numdiavia));
		$la_columna=array('desde'=>'','hasta'=>'','dias'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0	, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('desde'=>array('justification'=>'left','width'=>200),
						       		   'hasta'=>array('justification'=>'left','width'=>200),
									   'dias'=>array('justification'=>'left','width'=>140))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('mision'=>'<b> Conceptos de la Misión:</b> '.$as_denmis));
		$la_columna=array('mision'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0	, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('mision'=>array('justification'=>'left','width'=>540))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		
		$la_data=array(array('mision'=>'<b> Observaciones:</b> '.$as_obssolvia));
		$la_columna=array('mision'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0	, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('mision'=>array('justification'=>'left','width'=>540))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		
		$la_data=array(array('titulo'=>'<b>ASIGNACIONES</b>'));
		$la_columna=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>540))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('titulo'=>'<b>(NO SE RECONOCERAN CONCEPTOS DISTINTOS)</b>'));
		$la_columna=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>540))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('codigo'=>'<b>Código</b>','descripcion'=>'<b>Descripción</b>','tarifa'=>'<b>Tarifa</b>',
							 'dias'=>'<b>Días</b>','subtotal'=>'<b>Subtotal</b>'));
		$la_columna=array('codigo'=>'','descripcion'=>'','tarifa'=>'','dias'=>'','subtotal'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>60),
						               'descripcion'=>array('justification'=>'center','width'=>220),
									   'tarifa'=>array('justification'=>'center','width'=>100),
									   'dias'=>array('justification'=>'center','width'=>60),
									   'subtotal'=>array('justification'=>'center','width'=>100))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_asignaciones($la_data,$ai_total,$as_totalletras,&$io_pdf)
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
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo="Total Misión Bs.F.:";
		}
		else
		{
			$ls_titulo="Total Misión Bs.:";
		}
		$la_columna=array('codigo'=>'','descripcion'=>'','tarifa'=>'','dias'=>'','subtotal'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'rowGap' => 1,
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>60),
						               'descripcion'=>array('justification'=>'left','width'=>220),
									   'tarifa'=>array('justification'=>'right','width'=>100),
									   'dias'=>array('justification'=>'center','width'=>60),
									   'subtotal'=>array('justification'=>'right','width'=>100))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('titulo'=>'<b>'.$ls_titulo.'</b>              ','total'=>$ai_total));
		$la_columna=array('titulo'=>'','total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'rowGap' => 1,
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>440),
									   'total'=>array('justification'=>'right','width'=>100))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('letra'=>'<b> Son:</b> '.$as_totalletras));
		$la_columna=array('letra'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'rowGap' => 1,
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>540))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	
/*/	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_presupuestario($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_presupuestario
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_datos=array(array('titulo'=>'<b>CONTROL PRESUPUESTARIO</b>'));
		$la_columna=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>540))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);	
		unset($la_columna);
		unset($la_config);
		$io_pdf->ezSetDy(-2);
		$la_columna=array('ano'=>'<b> Año</b>',
   						  'proyecto'=>'<b> '.$_SESSION["la_empresa"]["nomestpro1"].'</b>',
						  'especifica'=>'<b> '.$_SESSION["la_empresa"]["nomestpro2"].'</b>',
						  'cuenta'=>'<b> Partida Presupuestaria</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'rowGap' => 1,
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('ano'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'proyecto'=>array('justification'=>'center','width'=>190), // Justificación y ancho de la columna
						 			   'especifica'=>array('justification'=>'center','width'=>190), // Justificación y ancho de la columna
						 			   'cuenta'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	/*///--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_firmas(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_firmas
		//		   Access: private 
		//	    Arguments: io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('elaborado'=>'<b> Elaborado Por:</b>','revisado'=>'<b> Revisado Por:</b>','autorizado'=>'<b> Autorizado Por:</b>'),
					   array('elaborado'=>'','revisado'=>'','autorizado'=>''),
					   array('elaborado'=>'','revisado'=>'','autorizado'=>''),
					   array('elaborado'=>'','revisado'=>'','autorizado'=>''),
					   array('elaborado'=>'','revisado'=>'','autorizado'=>''),
					   array('elaborado'=>'','revisado'=>'','autorizado'=>''),
					   array('elaborado'=>'Nombre y Apellido / Firma','revisado'=>'Firma / Sello','autorizado'=>'Firma / Sello'));
		$la_columna=array('elaborado'=>'',
   						  'revisado'=>'',
						  'autorizado'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'rowGap' => 1,
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('elaborado'=>array('justification'=>'center','width'=>180), // Justificación y ancho de la columna
						 			   'revisado'=>array('justification'=>'center','width'=>180), // Justificación y ancho de la columna
						 			   'autorizado'=>array('justification'=>'center','width'=>180))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('titulo'=>'<b>DATOS DEL BENEFICIARIO EN CONFORMIDAD DE RECEPCION DEL PAGO</b>'));
		$la_columna=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>540))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('nombre'=>'<b> Nombre y Apellido:</b>','cedula'=>'<b> C.I. No:</b>','firma'=>'<b> Firma:</b>','fecha'=>'<b> Fecha:</b>'),
					   array('nombre'=>'','cedula'=>'','firma'=>'','fecha'=>''),
					   array('nombre'=>'','cedula'=>'','firma'=>'','fecha'=>''),
					   array('nombre'=>'','cedula'=>'','firma'=>'','fecha'=>''),
					   array('nombre'=>'','cedula'=>'','firma'=>'','fecha'=>''));
		$la_columna=array('nombre'=>'',
   						  'cedula'=>'',
   						  'firma'=>'',
						  'fecha'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'rowGap' => 1,
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('nombre'=>array('justification'=>'center','width'=>135), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>135), // Justificación y ancho de la columna
						 			   'firma'=>array('justification'=>'center','width'=>135), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>135))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_firmas
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();				
	require_once("../class_folder/class_funciones_viaticos.php");
	$io_fun_viaticos=new class_funciones_viaticos();
	include("../../shared/class_folder/class_numero_a_letra.php");
	$io_numero_letra= new class_numero_a_letra();
	//imprime numero con los valore por defecto
	//cambia a minusculas
	$io_numero_letra->setMayusculas(1);
	//cambia a femenino
	$io_numero_letra->setGenero(1);
	//cambia moneda
	$io_numero_letra->setMoneda("Bolivares");
	//cambia prefijo
	$io_numero_letra->setPrefijo("");
	//cambia sufijo
	$io_numero_letra->setSufijo("");
	//imprime numero con los cambios
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------

	$ld_desde=$io_fun_viaticos->uf_obtenervalor_get("desde","");
	$ld_hasta=$io_fun_viaticos->uf_obtenervalor_get("hasta","");
	$ls_titulo="<b> SOLICITUD PAGO DE VIATICOS </b>";
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
	$ls_codsoldes=$io_fun_viaticos->uf_obtenervalor_get("codsoldes","");
	$ls_codsolhas=$io_fun_viaticos->uf_obtenervalor_get("codsolhas","");
	$ls_tiporeporte=$io_fun_viaticos->uf_obtenervalor_get("tiporeporte",0);
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
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_select_solicitudviaticos($ls_codemp,"",$ld_desde,$ld_hasta,$ls_codsoldes,$ls_codsolhas,"","","","","","","","",$li_orden);
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		$li_totrowsol=$io_report->ds->getRowCount("codsolvia");
		$li_k=0;
		$li_totrowtot=0;
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(6,3,3,3); // Configuración de los margenes en centímetros
		$io_pdf->ezStartPageNumbers(545,25,10,'','',1); // Insertar el número de página
		for($li_z=1;$li_z<=$li_totrowsol;$li_z++)
		{
			$ls_codsolvia= $io_report->ds->data["codsolvia"][$li_z];
			$lb_valido=$io_report->uf_select_solicitudpago_personal($ls_codemp,$ls_codsoldes,$ls_codsolhas,$ld_desde,$ld_hasta,$li_orden,$ls_codsolvia); // Cargar el DS con los datos de la cabecera del reporte
			if($lb_valido==false) // Existe algún error ó no hay registros
			{
				print("<script language=JavaScript>");
				print(" alert('No hay nada que Reportar');"); 
				print(" close();");
				print("</script>");
			}
			else // Imprimimos el reporte
			{
				$li_totrow=$io_report->ds_solicitud->getRowCount("cedper");
				$li_totrowtot=$li_totrowtot+$li_totrow;
				//print $li_totrow;
				for($li_i=1;$li_i<=$li_totrow;$li_i++)
				{
					$li_k=$li_k+1;
					$ls_codsolvia= $io_report->ds_solicitud->data["codsolvia"][$li_i];
					$io_encabezado=$io_pdf->openObject();
					$ld_fecsolvia= $io_report->ds_solicitud->data["fecsolvia"][$li_i];
					uf_print_encabezado_pagina($ls_codsolvia,$ld_fecsolvia,$io_encabezado,$io_pdf); // Imprimimos el encabezado de la página
					$ls_cedper= $io_report->ds_solicitud->data["cedper"][$li_i];
					$ls_nomper= $io_report->ds_solicitud->data["nomper"][$li_i]." ".$io_report->ds_solicitud->data["apeper"][$li_i];
					$ls_cargo= $io_report->ds_solicitud->data["cargo"][$li_i];
					$ls_obssolvia= $io_report->ds_solicitud->data["obssolvia"][$li_i];
					$ls_desuniadm= $io_report->ds_solicitud->data["desuniadm"][$li_i];
					$ls_codcueban = $io_report->ds_solicitud->data["codcueban"][$li_i];
					$ls_tipcuebanper= $io_report->ds_solicitud->data["tipcuebanper"][$li_i];
					$ls_desded= $io_report->ds_solicitud->data["desded"][$li_i];
					$ls_destipper= $io_report->ds_solicitud->data["destipper"][$li_i];			
					$ls_codclavia= $io_report->ds_solicitud->data["codclavia"][$li_i];
					$ld_fecsalvia= $io_report->ds_solicitud->data["fecsalvia"][$li_i];
					$ld_fecregvia= $io_report->ds_solicitud->data["fecregvia"][$li_i];
				//	$li_numdiavia= $io_report->ds_solicitud->data["numdiavia"][$li_i];
					$li_numdiavia=$io_fecha->uf_restar_fechas($ld_fecsalvia,$ld_fecregvia);
					$li_numdiavia=$li_numdiavia+1;
					$ls_denmis= $io_report->ds_solicitud->data["denmis"][$li_i];
					$li_acompanante= $io_report->ds_solicitud->data["acompanante"][$li_i];
					uf_print_cabecera($ls_cedper,$ls_nomper,$ls_desuniadm,$ls_codcueban,$ls_tipcuebanper,$ls_desded,$ls_destipper,
									  $ls_codclavia,$ld_fecsalvia,$ld_fecregvia,$li_numdiavia,$ls_denmis,$li_acompanante,$ls_cargo,
									  $ls_obssolvia,&$io_pdf); // Imprimimos la cabecera del registro
					$lb_valido=$io_report->uf_select_solicitudpago_asignaciones($ls_codemp,$ls_codsolvia);
					if($lb_valido)
					{
						$li_totrow_det=$io_report->ds_detalle->getRowCount("codasi");
						$li_total=0;
						for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
						{
							$ls_codasi= $io_report->ds_detalle->data["codasi"][$li_s];
							$ls_denasi= $io_report->ds_detalle->data["denasi"][$li_s];
							$li_canasi= $io_report->ds_detalle->data["canasi"][$li_s];
							$li_monto= $io_report->ds_detalle->data["monto"][$li_s];
							$li_subtotal = $li_monto*$li_canasi;
							$li_total=$li_total+$li_subtotal;
							$li_canasi=number_format($li_canasi,2,",",".");
							$li_monto=number_format($li_monto,2,",",".");
							$li_subtotal=number_format($li_subtotal,2,",",".");
							$la_data[$li_s]=array('codigo'=>$ls_codasi,'descripcion'=>$ls_denasi,'tarifa'=>$li_monto,'dias'=>$li_canasi,'subtotal'=>$li_subtotal);
						}
						$io_numero_letra->setNumero($li_total);
						$ls_totalletras=$io_numero_letra->letra();
						$li_total=number_format($li_total,2,",",".");
						/*/
						for($li=$li_s+1;$li<=120;$li++)

						   {
						
							 $la_data[$li]=array('codigo'=>$ls_codasi,'descripcion'=>$ls_denasi,'tarifa'=>$li_monto,
							 'dias'=>$li_canasi,'subtotal'=>$li_subtotal);
						
						   }
						/*/
						
						uf_print_detalle_asignaciones($la_data,$li_total,$ls_totalletras,$io_pdf); // Imprimimos el detalle 
						unset($la_data);
						$io_report->ds_detalle->reset_ds();
						$lb_existe=$io_report->uf_select_solicitudpago_spg($ls_codemp,$ls_codsolvia);
						if($lb_existe)
						{
							$li_totrow_detpres=$io_report->ds_detpresup->getRowCount("spg_cuenta");
							$ls_ano=substr($_SESSION["la_empresa"]["periodo"],0,4);
							for($li_j=1;$li_j<=$li_totrow_detpres;$li_j++)
							{
								$ls_spgcuenta=$io_report->ds_detpresup->data["spg_cuenta"][$li_j];
								$ls_denestpro1=$io_report->ds_detpresup->data["denestpro1"][$li_j];
								$ls_denestpro2=$io_report->ds_detpresup->data["denestpro2"][$li_j];
								$la_data[$li_j]=array('ano'=>$ls_ano,'proyecto'=>$ls_denestpro1,'especifica'=>$ls_denestpro2,'cuenta'=>$ls_spgcuenta);
							}
							//uf_print_detalle_presupuestario($la_data,$io_pdf); // Imprimimos el detalle 
							unset($la_data);
						}
					}
					uf_print_firmas($io_pdf);
					$io_pdf->stopObject($io_encabezado); // Detener el objeto cabecera
					if($li_z<$li_totrowsol)
					{
						$io_pdf->ezNewPage(); // Insertar una nueva página
					}
				}
			}
		}
		if($lb_valido)
		{
			$io_pdf->ezStopPageNumbers(1,1);
			$io_pdf->ezStream();
		}
		unset($io_pdf);
	}
	unset($io_encabezado);
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_viaticos);
?> 