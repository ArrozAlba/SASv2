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
	function uf_print_encabezado_pagina($as_titulo,$as_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_fecha // Fecha 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
//		$io_pdf->line(20,40,730,40);
//		$io_pdf->rectangle(150,530,590,40);
//		$io_pdf->line(560,530,560,570);
//		$io_pdf->line(560,550,740,550);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],35,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,550,11,"<b>".$as_titulo."</b>"); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_fecha);
		$io_pdf->addText(700,550,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(706,543,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_nomemp,$as_codsis,$as_nomsis,$as_codusu,$as_nomusu,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_nomemp    // nombre de la empresa
		//	    		   as_codsis    // codigo de sistema
		//	    		   as_nomsis    // nombre de sistema
		//	    		   as_codusu    // codigo de usuario
		//	    		   as_nomusu    // nombre de usuario
		//	    		   io_pdf       // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>Empresa</b>  '.$as_nomemp.''),
					   array ('name'=>'<b>Usuario</b>    '.$as_codusu." - ".$as_nomusu.''),
					   array ('name'=>'<b>Sistema</b>   '.$as_codsis." - ".$as_nomsis.''),
					   );
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 11, // Tamaño de Letras
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar Líneas
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>710, // Ancho de la tabla
						 'maxWidth'=>710); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
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
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_columna=array('pantalla'=>'<b>Pantalla</b>',
						  'acceso'=>'<b>Acceso</b>',
						  'leer'=>'<b>Buscar</b>',
						  'incluir'=>'<b>Incluir</b>',
						  'cambiar'=>'<b>Modificar</b>',
						  'eliminar'=>'<b>Eliminar</b>',
						  'ejecutar'=>'<b>Procesar</b>',
						  'imprimir'=>'<b>Imprimir</b>',
						  'anular'=>'<b>Anular</b>',
						  'grupos'=>'<b>Grupo</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>730, // Ancho de la tabla
						 'maxWidth'=>730, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('pantalla'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la columna
						 			   'acceso'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'leer'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'incluir'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'cambiar'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'eliminar'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'ejecutar'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'imprimir'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'anular'=>array('justification'=>'center','width'=>50),
									   'grupos'=>array('justification'=>'center','width'=>165))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ai_totent,$ai_totsal,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera
		//		   Access: private 
		//	    Arguments: ai_totent // Total Entradas
		//	   			   ai_totsal // Total Salidas
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>''));
//		$la_data=array(array('name'=>'_______________________________________________________________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>730); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$la_data=array(array('total'=>''));
		$la_columna=array('total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>730, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>500), // Justificación y ancho de la columna
						 			   'entradas'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'salidas'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>730, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_sss_class_report.php");
	$io_report=new sigesp_sss_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_funciones_seguridad.php");
	$io_fun_inventario=new class_funciones_seguridad();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="Reporte de Permisos de Usuarios";
	$ls_fecha="";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
	$ls_codusu=$io_fun_inventario->uf_obtenervalor_get("codusu","");
	$ls_codsis=$io_fun_inventario->uf_obtenervalor_get("codsis","");
	$li_orden=$io_fun_inventario->uf_obtenervalor_get("orden","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_sss_select_permisos_usuario($ls_codemp,$ls_codusu,$ls_codsis,$li_orden,&$rs_data); // Cargar el DS con los datos de la cabecera del reporte
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
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(700,50,10,'','',1); // Insertar el número de página
		
		//$li_totrow=$io_report->ds->getRowCount("codsis");
		while($row=$io_report->io_sql->fetch_row($rs_data))
		{				
			$io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$li_totent=0;
			$li_totsal=0;
			$ls_codsis=  $row["codsis"];
			$ls_nomsis=  $row["nomsis"];
			$ls_codusu=  $row["codusu"];
			$ls_nomusu=  $row["nomusu"]." ".$row["apeusu"];			
			uf_print_cabecera($ls_nomemp,$ls_codsis,$ls_nomsis,$ls_codusu,$ls_nomusu,$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_sss_select_dt_permisos_usuario($ls_codemp,$ls_codusu,$ls_codsis); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->ds_detalle->getRowCount("nomven");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$li_enabled=  $io_report->ds_detalle->data["enabled"][$li_s];
					$li_leer=     $io_report->ds_detalle->data["leer"][$li_s];
					$li_incluir=  $io_report->ds_detalle->data["incluir"][$li_s];
					$li_cambiar=  $io_report->ds_detalle->data["cambiar"][$li_s];
					$li_eliminar= $io_report->ds_detalle->data["eliminar"][$li_s];
					$li_imprimir= $io_report->ds_detalle->data["imprimir"][$li_s];
					$li_anular=   $io_report->ds_detalle->data["anular"][$li_s];
					$li_ejecutar= $io_report->ds_detalle->data["ejecutar"][$li_s];
					$ls_pantalla= $io_report->ds_detalle->data["titven"][$li_s];
					$ls_grupo   = trim($io_report->ds_detalle->data["grupo"][$li_s]);
					if($li_enabled=="1"){$ls_enabled="Sí";}else{$ls_enabled="No";}
					if($li_leer=="1"){$ls_leer="Sí";}else{$ls_leer="No";}
					if($li_incluir=="1"){$ls_incluir="Sí";}else{$ls_incluir="No";}
					if($li_cambiar=="1"){$ls_cambiar="Sí";}else{$ls_cambiar="No";}
					if($li_eliminar=="1"){$ls_eliminar="Sí";}else{$ls_eliminar="No";}
					if($li_imprimir=="1"){$ls_imprimir="Sí";}else{$ls_imprimir="No";}
					if($li_anular=="1"){$ls_anular="Sí";}else{$ls_anular="No";}
					if($li_ejecutar=="1"){$ls_ejecutar="Sí";}else{$ls_ejecutar="No";}
					$la_data[$li_s]=array('pantalla'=>$ls_pantalla,'acceso'=>$ls_enabled,'leer'=>$ls_leer,'incluir'=>$ls_incluir,'cambiar'=>$ls_cambiar,
										  'eliminar'=>$ls_eliminar,'imprimir'=>$ls_imprimir,'anular'=>$ls_anular,'ejecutar'=>$ls_ejecutar,'grupos'=>$ls_grupo);
				}
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				uf_print_pie_cabecera("","",$io_pdf); // Imprimimos pie de la cabecera
				if ($io_pdf->ezPageCount==$li_numpag)
				{// Hacemos el commit de los registros que se desean imprimir
					$io_pdf->transaction('commit');
				}
				else
				{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
					$io_pdf->transaction('rewind');
					if($li_numpag>1)
					{
						$io_pdf->ezNewPage(); // Insertar una nueva página
					}
					uf_print_cabecera($ls_nomemp,$ls_codsis,$ls_nomsis,$ls_codusu,$ls_nomusu,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					uf_print_pie_cabecera("","",$io_pdf); // Imprimimos pie de la cabecera
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
?> 