<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Reporte de Amonestaciones por persona
//  ORGANISMO: IPSFA
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
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 05/03/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_srh;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_relacionsolicitudes.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo// Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 06/03/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(15,40,585,40);
       
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		

		$io_pdf->addText(540,770,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(546,764,6,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');

	    $io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetY(670);
		$la_data=array(array('titulo1'=>'<b>'.$as_titulo.'</b>'));
					
		$la_columnas=array('titulo1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('titulo1'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
	
		$io_pdf->restoreState();
	    $io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
     }// end function uf_print_encabezado_pagina
//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_datos_trabajador($la_data,$as_codigoper,$as_nombreper,$as_codcargotrab,$as_coduniad,$as_denuniad,&$io_pdf)
 	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_datos_trabajador
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el los datos del trabajador.
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 06/03/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(645);
		$la_data[1]=array('datosper'=>'<b>Datos del Personal</b>');
		$la_columnas=array('datosper'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('datosper'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->ezSetY(630);
		$la_data[2]=array('codigo'=>'<b>Código del Personal</b>',
		                     'nombre'=>'<b>Nombre y Apellido</b>',
							 'cargo'=>'<b>Cargo del Trabajador</b>',
							 'unidad'=>'<b>Unidad Administrativa</b>');
		$la_columnas=array('codigo'=>'',
						   'nombre'=>'',
						   'cargo'=>'',
						   'unidad'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>270), // Justificación y ancho de la columna
						 			   'cargo'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'unidad'=>array('justification'=>'center','width'=>160))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->ezSetY(605);
		$la_data[3]=array('codigo'=>'<b>'.$as_codigoper.'</b>',
		                     'nombre'=>'<b>'.$as_nombreper.'</b>',
							 'cargo'=>'<b>'.$as_codcargotrab.'</b>',
							 'unidad'=>'<b>'.$as_coduniad.'</b>');
		$la_columnas=array('codigo'=>'',
						   'nombre'=>'',
						   'cargo'=>'',
						   'unidad'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>270), // Justificación y ancho de la columna
						 			   'cargo'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'unidad'=>array('justification'=>'center','width'=>160))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_datos_trabajador
   //-----------------------------------------------------------------------------------------------------------------------------------
   //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_datos_supervisor($la_data,$ls_codsup,$ls_nomsup,$ls_codcarsup,&$io_pdf)
 	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_datos_supervisor
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el los datos del supervisor.
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 06/03/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $io_pdf->ezSetY(560);
		$la_data[4]=array('datosup'=>'<b>Datos del Supervisor</b>');
		$la_columnas=array('datosup'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('datosup'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->ezSetY(545);
		$la_data[5]=array('codigo'=>'<b>Código del Personal</b>',
		                     'nombre'=>'<b>Nombre y Apellido</b>',
							 'cargo'=>'<b>Cargo</b>');
		$la_columnas=array('codigo'=>'',
						   'nombre'=>'',
						   'cargo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>270), // Justificación y ancho de la columna
						 			   'cargo'=>array('justification'=>'center','width'=>230))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->ezSetY(520);
		$la_data[6]=array('codigosup'=>'<b>'.$ls_codsup.'</b>',
		                     'nombresup'=>'<b>'.$ls_nomsup.'</b>',
							 'cargosup'=>'<b>'.$ls_codcarsup.'</b>');
		$la_columnas=array('codigosup'=>'',
						   'nombresup'=>'',
						   'cargosup'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigosup'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'nombresup'=>array('justification'=>'left','width'=>270), // Justificación y ancho de la columna
						 			   'cargosup'=>array('justification'=>'center','width'=>230))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_datos_supervisor
    //-----------------------------------------------------------------------------------------------------------------------------------
   function uf_print_datos_amonestacion($la_data,$as_numeroamo,$as_asuntoamo,$as_fechaamo,$as_descrpamo,&$io_pdf)
 	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_datos_amonestación
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el los datos de la amonestación.
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 06/03/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $io_pdf->ezSetY(480);
		$la_data[7]=array('datosup'=>'<b>Datos de la Amonestación</b>');
		$la_columnas=array('datosup'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' =>12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('datosup'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->ezSetY(465);
		$la_data[8]=array('numeroreg'=>'<b>Número Registro</b>',
		                     'asunto'=>'<b>Asunto</b>',
							 'fecha'=>'<b>Fecha</b>',
							 'motivo'=>'<b>Motivo</b>');
		$la_columnas=array('numeroreg'=>'',
						   'asunto'=>'',
						   'fecha'=>'',
						   'motivo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('numeroreg'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'asunto'=>array('justification'=>'center','width'=>270), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>70),
									   'motivo'=>array('justification'=>'center','width'=>160))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->ezSetY(440);
		$la_data[9]=array('nroreg'=>'<b>'.$as_numeroamo.'</b>',
		                     'asuntoamo'=>'<b>'.$as_asuntoamo.'</b>',
							 'fechaamone'=>'<b>'.$as_fechaamo.'</b>',
							 'descripcion'=>'<b>'.$as_descrpamo.'</b>');
		$la_columnas=array('nroreg'=>'',
						   'asuntoamo'=>'',
						   'fechaamone'=>'',
						   'descripcion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('nroreg'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'asuntoamo'=>array('justification'=>'left','width'=>270), // Justificación y ancho de la columna
						 			   'fechaamone'=>array('justification'=>'center','width'=>70),
									   'descripcion'=>array('justification'=>'left','width'=>160))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
    require_once("../../shared/ezpdf/class.ezpdf.php");	
	require_once("class_folder/sigesp_srh_class_report.php");
	$io_report=new sigesp_srh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/utilidades/class_funciones_srh.php");
	$io_fun_srh=new class_funciones_srh('../../');
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
       $ls_titulo="<b>REPORTE DE AMONESTACIÓN</b>"; 
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_numeroamo=$io_fun_srh->uf_obtenervalor_get("numeroamo","");
	$ls_asuntoamo=$io_fun_srh->uf_obtenervalor_get("asuntoamo","");  
	$ls_fechaamo=$io_fun_srh->uf_obtenervalor_get("fechaamo","");
	$ls_codigoper=$io_fun_srh->uf_obtenervalor_get("codigoper","");
	$ls_nombreper=$io_fun_srh->uf_obtenervalor_get("nombreper","");
	$ls_codcargotrab=$io_fun_srh->uf_obtenervalor_get("codcargotrab","");   
	$ls_coduniad=$io_fun_srh->uf_obtenervalor_get("coduniad","");  
	$ls_denuniad=$io_fun_srh->uf_obtenervalor_get("denuniad","");
	$ls_codsup=$io_fun_srh->uf_obtenervalor_get("codsup","");	
	$ls_nomsup=$io_fun_srh->uf_obtenervalor_get("nomsup","");
	$ls_codcarsup=$io_fun_srh->uf_obtenervalor_get("codcarsup","");
	$ls_descrpamo=$io_fun_srh->uf_obtenervalor_get("descipamo","");		

	//-----------------------------------------------------------------------------------------------------------------------------------
	global $la_data;
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
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
			$io_pdf->ezSetCmMargins(4,3,3,3); // Configuración de los margenes en centímetros
			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página

			uf_print_encabezado_pagina($ls_titulo,$io_pdf);
			uf_print_datos_trabajador($la_data,$ls_codigoper,$ls_nombreper,$ls_codcargotrab,$ls_coduniad,$ls_denuniad,&$io_pdf);
			uf_print_datos_supervisor($la_data,$ls_codsup,$ls_nomsup,$ls_codcarsup,&$io_pdf);
			uf_print_datos_amonestacion($la_data,$ls_numeroamo,$ls_asuntoamo,$ls_fechaamo,$ls_descrpamo,&$io_pdf);
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
        } // fin del else
	}
?>


