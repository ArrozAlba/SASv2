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
	function uf_print_encabezado_pagina($as_codsolvia,&$io_encabezado,$ad_fecsolvia,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_codsolvia // Código de Solicitud de Viaticos
		//	    		   io_encabezado // Instancia del encabezado
		//	    		   ad_fecsolvia // Fecha de la solicitud de viaticos
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
		$io_pdf->line(30,785,570,785);
		$io_pdf->line(30,700,570,700);
		$io_pdf->line(30,785,30,700);
		$io_pdf->line(150,785,150,700);
		$io_pdf->line(450,785,450,700);
		$io_pdf->line(570,785,570,700);
		$io_pdf->line(450,763.75,570,763.75);
		$io_pdf->line(450,742.5,570,742.5);
		$io_pdf->line(450,721.25,570,721.25);
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(451,764.75,118,$io_pdf->getFontHeight(16.8));
        $io_pdf->filledRectangle(451,722.25,118,$io_pdf->getFontHeight(16.8));
        $io_pdf->setColor(0,0,0);		
		$io_pdf->addText(493,770,11,"1. FECHA"); // Agregar FECHA
		$io_pdf->addText(483,748,11,$ad_fecsolvia); // Agregar FECHA
		$io_pdf->addText(468,727,11,"N° DE CONTROL"); // Agregar NRO DE CONTROL
		$io_pdf->addText(487,706,11,$as_codsolvia); // Agregar NRO DE CONTROL
		$io_pdf->addText(180,740,11,"<b>SOLICITUD Y APROBACION DE VIATICOS Y</b>"); // Agregar NRO DE CONTROL
		$io_pdf->addText(240,725,11,"<b>BOLETOS AEREOS</b>"); // Agregar NRO DE CONTROL
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_cedper,$as_nomper,$as_desuniadm,$as_codcueban,$as_tipcuebanper,$as_desded,$as_destipper,
							  $as_codclavia,$ad_fecsalvia,$ad_fecregvia,$ai_numdiavia,$as_denmis,$ai_acompanante,$as_cargo,
							  $as_telefono,$ai_sueper,$as_denestpro1,$as_denestpro2,$as_desrut,$ai_solviaext,$as_obssolvia,&$io_pdf)
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
		//	    		   $as_telefono  // Telefono de personal
		//	    		   $ai_sueper  // Sueldo del personal
		//	    		   $as_denestpro1  // Denominacion estructuta programatica nivel 1
		//	    		   $as_denestpro2  // Denominacion estructuta programatica nivel 2
		//	    		   $as_desrut  // Denominacion de la ruta
		//	    		   $ai_solviaext  // Indica si el viatico es para el exterior
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
		if($ai_sueper==0)
		{
			$ai_sueper="";
		}
		$la_data=array(array('nombre'=>$as_nomper,'cedula'=>$as_cedper,'cargo'=>$as_cargo,'unidad'=>$ai_sueper,'categoria'=>$as_telefono));
		$la_columna=array('nombre'=>'<b>2. Nombre del Funcionario</b>','cedula'=>'<b>3. Cédula de Identidad N°</b>',
						  'cargo'=>'<b>4. Denominación del Cargo</b>','unidad'=>'<b>5. Sueldo</b>','categoria'=>'<b>6. Telefono Oficina / Celular</b>');
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
		$la_data=array(array('titulo'=>'<b>7. N° de Cuenta Nómina</b>','corriente'=>$ls_corriente,'titulocorriente'=>'Corriente',
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
		$la_data=array(array('titulo'=>'<b>DATOS DE LA MISIÓN</b>'));
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
		$la_data=array(array('dependencia'=>'<b>8. Dependencia Solicitante:</b> '.$as_desuniadm));
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
		$la_data=array(array('estpro1'=>'<b>9. Proyecto / Acción Centralizada:</b> '.$as_denestpro1,'estpro2'=>'<b>10. Acción Específica:</b> '.$as_denestpro2));
		$la_columna=array('estpro1'=>'','estpro2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0	, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('estpro1'=>array('justification'=>'left','width'=>270),
						       		   'estpro2'=>array('justification'=>'left','width'=>270))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('ruta'=>'<b>11. Ruta:</b> '.$as_desrut));
		$la_columna=array('ruta'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0	, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('ruta'=>array('justification'=>'left','width'=>540))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('desde'=>'<b>13. Tiempo Estimado        Desde:</b> '.$ad_fecsalvia,'hasta'=>'<b>Hasta:</b> '.$ad_fecregvia,'dias'=>'<b>14. Total Días:</b> '.$ai_numdiavia));
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
		$la_data=array(array('mision'=>'<b>15. Conceptos de la Misión:</b> '.$as_denmis));
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
		$ai_numdiavia=number_format($ai_numdiavia,2,".","");
		if($ai_numdiavia>1)
		{
			$ls_pernoctasi="X";
			$ls_pernoctano="";
		}
		else
		{
			$ls_pernoctasi="";
			$ls_pernoctano="X";
		}
		if($ai_solviaext==1)
		{
			$ls_extsi="X";
			$ls_extno="";
		}
		else
		{
			$ls_extsi="";
			$ls_extno="X";
		}
		
		$la_data=array(array('titulo'=>'<b>VIATICOS</b>'));
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
		$la_data=array(array('desde'=>'<b>16. Tipo de Viático:            Nacional    '.$ls_extno.'               Exterior    '.$ls_extsi.'</b> ','dias'=>'<b>17. Pernocta:                 Si '.$ls_pernoctasi.'            No '.$ls_pernoctano.'</b> '));
		$la_columna=array('desde'=>'','dias'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0	, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('desde'=>array('justification'=>'left','width'=>270),
									   'dias'=>array('justification'=>'left','width'=>270))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('desde'=>'<b>18. Vehiculo Oficial:</b>           Si      No','hasta'=>'<b>19. Pasaje Terrestre</b>            Si      No','dias'=>'<b>20. Vehiculo Propio</b>          Si      No');
		$la_data[2]=array('desde'=>'Kilometraje _________','hasta'=>'Ruta _________','dias'=>'Kilometraje _________');
		$la_columna=array('desde'=>'','hasta'=>'','dias'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0	, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('desde'=>array('justification'=>'left','width'=>180),
						       		   'hasta'=>array('justification'=>'left','width'=>180),
									   'dias'=>array('justification'=>'left','width'=>180))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('titulo'=>'<b>BOLETOS AEREOS</b>'));
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
		$la_data=array(array('titulo'=>'21. Pasaje Aéreo                       Si         No                                  Ruta:'));
		$la_columna=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>540))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('titulo'=>'<b>IMPUTACION PRESUPUESTARIA</b>'));
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
		$la_data=array(array('titulo'=>'22. Código:                              Nacional  4.03.09.01.00     <b>'.$ls_extno.'</b>                       Extranjero   4.03.09.02.00     <b>'.$ls_extsi.'</b>'));
		$la_columna=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>540))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('titulo'=>'<b>OBSERVACIONES</b>'));
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
		$la_data=array(array('titulo'=>'23. '.$as_obssolvia));
		$la_columna=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>540))); // Ancho Máximo de la tabla
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
		$la_data=array(array('titulo'=>'<b>Total Misión Bs.:</b>              ','total'=>$ai_total));
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
		$la_data=array(array('letra'=>'<b>Son:</b> '.$as_totalletras));
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
	
	//--------------------------------------------------------------------------------------------------------------------------------
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
		$la_columna=array('ano'=>'<b>Año</b>',
   						  'proyecto'=>'<b>'.$_SESSION["la_empresa"]["nomestpro1"].'</b>',
						  'especifica'=>'<b>'.$_SESSION["la_empresa"]["nomestpro2"].'</b>',
						  'cuenta'=>'<b>Cuenta</b>');
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
	//--------------------------------------------------------------------------------------------------------------------------------
	
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
		$la_data=array(array('titulo'=>'<b>FIRMAS</b>'));
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
		$la_data=array(array('elaborado'=>'<b>24. Solicitado Por:</b>','revisado'=>'<b>25. Responsable del Proyecto o Acción Centralizada:</b>','autorizado'=>'<b>26. Oficina de Administración y Servicios:</b>'),
					   array('elaborado'=>'','revisado'=>'','autorizado'=>''),
					   array('elaborado'=>'','revisado'=>'','autorizado'=>''),
					   array('elaborado'=>'','revisado'=>'','autorizado'=>''),
					   array('elaborado'=>'','revisado'=>'','autorizado'=>''),
					   array('elaborado'=>'','revisado'=>'','autorizado'=>''),
					   array('elaborado'=>'Nombre y Apellido / Cargo','revisado'=>'Nombre y Apellido / Cargo / Sello','autorizado'=>'Nombre y Apellido / Cargo / Sello / Fecha'));
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
	}// end function uf_print_firmas
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_scv_class_report.php");
	$io_report=new sigesp_scv_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_viaticos.php");
	$io_fun_viaticos=new class_funciones_viaticos();
	include("../../shared/class_folder/class_numero_a_letra.php");
	$io_numero_letra= new class_numero_a_letra();
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_sql.php");	
	$in           = new sigesp_include();
	$con          = $in->uf_conectar();
	$io_sql       = new class_sql($con);				
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
	$ls_codsoldes=$io_fun_viaticos->uf_obtenervalor_get("codsoldes","");
	$ls_codsolhas=$io_fun_viaticos->uf_obtenervalor_get("codsolhas","");
	$ld_desde=$io_fun_viaticos->uf_obtenervalor_get("desde","");
	$ld_hasta=$io_fun_viaticos->uf_obtenervalor_get("hasta","");

	$ls_titulo="<b> SOLICITUD Y APROBACION DE VIATICOS Y </b>";
	$ls_titulo1="<b> BOLETOS AEREOS </b>";
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
	$ls_codsolvia=$io_fun_viaticos->uf_obtenervalor_get("codsolvia","");
	$li_orden="";
	$lb_tipben="P";
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_select_solicitudpago_personal($ls_codemp,$ls_codsoldes,$ls_codsolhas,$ld_desde,$ld_hasta,$li_orden,$ls_codsolvia,$rs_data); // Cargar el DS con los datos de la cabecera del reporte
	if(!$lb_valido)
	{
		$lb_valido=$io_report->uf_select_solicitudpago_beneficiario($ls_codemp,$ls_codsoldes,$ls_codsolhas,$ld_desde,$ld_hasta,$li_orden,$ls_codsolvia);
	}
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
		$io_pdf->ezStartPageNumbers(545,25,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->ds_solicitud->getRowCount("cedper");
		$li_totrow=$io_sql->num_rows($rs_data);	
		//print $li_totrow;
		$li_i=0;
		while($row=$io_sql->fetch_row($rs_data))
		//for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
			/*$ls_codsolvia= $io_report->ds_solicitud->data["codsolvia"][$li_i];
			$io_encabezado=$io_pdf->openObject();
			$ls_cedper= $io_report->ds_solicitud->data["cedper"][$li_i];
			$ls_nomper= $io_report->ds_solicitud->data["nomper"][$li_i]." ".$io_report->ds_solicitud->data["apeper"][$li_i];
			$ls_cargo= $io_report->ds_solicitud->data["cargo"][$li_i];
			$ls_desuniadm= $io_report->ds_solicitud->data["desuniadm"][$li_i];
			$ls_codcueban = $io_report->ds_solicitud->data["codcueban"][$li_i];
			$ls_tipcuebanper= $io_report->ds_solicitud->data["tipcuebanper"][$li_i];
			$ls_desded= $io_report->ds_solicitud->data["desded"][$li_i];
			$ls_destipper= $io_report->ds_solicitud->data["destipper"][$li_i];			
			$ls_codclavia= $io_report->ds_solicitud->data["codclavia"][$li_i];
			$ld_fecsalvia= $io_report->ds_solicitud->data["fecsalvia"][$li_i];
			$ld_fecregvia= $io_report->ds_solicitud->data["fecregvia"][$li_i];
			$ld_fecsolvia= $io_report->ds_solicitud->data["fecsolvia"][$li_i];
//			$li_numdiavia= $io_report->ds_solicitud->data["numdiavia"][$li_i];
			$li_numdiavia=$io_fecha->uf_restar_fechas($ld_fecsalvia,$ld_fecregvia);
			$li_numdiavia=$li_numdiavia+1;
			$ls_denmis= $io_report->ds_solicitud->data["denmis"][$li_i];
			$li_acompanante= $io_report->ds_solicitud->data["acompanante"][$li_i];
			$ls_telefono= $io_report->ds_solicitud->data["telmovper"][$li_i];
			$li_sueper= $io_report->ds_solicitud->data["sueper"][$li_i];
			$li_solviaext= $io_report->ds_solicitud->data["solviaext"][$li_i];
			$ls_obssolvia= $io_report->ds_solicitud->data["obssolvia"][$li_i];*/
			if ($li_totrow>0)
			{
			    $li_i++;
				$ls_codsolvia= $row["codsolvia"];
				$io_encabezado=$io_pdf->openObject();
				$ls_cedper= $row["cedper"];
				$ls_nomper= $row["nomper"]." ".$row["apeper"];
				$ls_cargo= $row["cargo"];
				$ls_desuniadm= $row["desuniadm"];
				$ls_codcueban = $row["codcueban"];
				$ls_tipcuebanper=$row["tipcuebanper"];
				$ls_desded= $row["desded"];
				$ls_destipper= $row["destipper"];			
				$ls_codclavia= $row["codclavia"];
				$ld_fecsalvia=$row["fecsalvia"];
				$ld_fecregvia= $row["fecregvia"];
				$ld_fecsolvia= $row["fecsolvia"];
	//			$li_numdiavia= $row["numdiavia"];
				$li_numdiavia=$io_fecha->uf_restar_fechas($ld_fecsalvia,$ld_fecregvia);
				$li_numdiavia=$li_numdiavia+1;
				$ls_denmis= $row["denmis"];
				$li_acompanante= $row["acompanante"];
				$ls_telefono= $row["telmovper"];
				$li_sueper= $row["sueper"];
				$li_solviaext= $row["solviaext"];
				$ls_obssolvia= $row["obssolvia"];
				$li_sueper=number_format($li_sueper,2,",",".");
			}
			uf_print_encabezado_pagina($ls_codsolvia,$io_encabezado,$ld_fecsolvia,$io_pdf); // Imprimimos el encabezado de la página
			$lb_existe=$io_report->uf_select_solicitudpago_spg($ls_codemp,$ls_codsolvia);
			$ls_denestpro1="";
			$ls_denestpro2="";
			if($lb_existe)
			{
				$li_totrow_detpres=$io_report->ds_detpresup->getRowCount("spg_cuenta");
				$ls_ano=substr($_SESSION["la_empresa"]["periodo"],0,4);
				for($li_j=1;$li_j<=$li_totrow_detpres;$li_j++)
				{
					$ls_denestpro1=$io_report->ds_detpresup->data["denestpro1"][1];
					$ls_denestpro2=$io_report->ds_detpresup->data["denestpro2"][1];
				}
			}
			$ls_desrut=$io_report->uf_select_ruta($ls_codemp,$ls_codsolvia);
			uf_print_cabecera($ls_cedper,$ls_nomper,$ls_desuniadm,$ls_codcueban,$ls_tipcuebanper,$ls_desded,$ls_destipper,
							  $ls_codclavia,$ld_fecsalvia,$ld_fecregvia,$li_numdiavia,$ls_denmis,$li_acompanante,$ls_cargo,
							  $ls_telefono,$li_sueper,$ls_denestpro1,$ls_denestpro2,$ls_desrut,$li_solviaext,$ls_obssolvia,&$io_pdf); // Imprimimos la cabecera del registro
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
//				uf_print_detalle_asignaciones($la_data,$li_total,$ls_totalletras,$io_pdf); // Imprimimos el detalle 
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
//					uf_print_detalle_presupuestario($la_data,$io_pdf); // Imprimimos el detalle 
					unset($la_data);
				}
			}
			uf_print_firmas($io_pdf);
			$io_pdf->stopObject($io_encabezado); // Detener el objeto cabecera
			unset($io_encabezado);
			if($li_i<$li_totrow)
			{
				$io_pdf->ezNewPage(); // Insertar una nueva página
			}
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