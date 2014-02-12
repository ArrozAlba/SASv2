<?php
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

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_listadopermisos.php",$ls_descripcion);
		if($lb_valido==false)
		{
			print("<script language=JavaScript>");
			print(" close();");
			print("</script>");
		}
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/07/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,755,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(13,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,540,13,$as_titulo); // Agregar el título
		$io_pdf->addText(712,560,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(718,553,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_cedper,$as_nomper,$as_apeper,$as_estper,$as_dirper,$as_telhabper,$as_telmovper,$as_coreleper,
							   &$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_cedper // Cédula del Personal
		//	    		   as_nomper // Nombre del Personal
		//	    		   as_apeper // Apellido del Personal
		//	    		   as_estper // Estatus del Personal
		//	    		   as_dirper // Dirección del Personal
		//	    		   as_telhabper // Teléfono de Habitación del Personal
		//	    		   as_telmovper // Teléfono Móvil del Personal
		//	    		   as_coreleper // Correo Electrónico del Personal
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los datos del Personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/07/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('titulo1'=>'<b>Datos Personales</b>');
		$la_columna=array('titulo1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas						 
						 'width'=>690, // Ancho de la tabla
						 'maxWidth'=>690, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'rowGap'=>4,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo1'=>array('justification'=>'left','width'=>700))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);	
		
		$la_data[1]=array('titulo1'=>'<b>Cédula</b>','cedula'=>$as_cedper,
						  'titulo2'=>'<b>Nombres</b>','nombre'=>$as_nomper,
						  'titulo3'=>'<b>Apellidos</b>','apellido'=>$as_apeper);
		$la_columna=array('titulo1'=>'','cedula'=>'','titulo2'=>'','nombre'=>'','titulo3'=>'','apellido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 11, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>690, // Ancho de la tabla
						 'maxWidth'=>690, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'rowGap'=>4,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo1'=>array('justification'=>'left','width'=>50), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
						 			   'titulo2'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>220), // Justificación y ancho de la columna
						 			   'titulo3'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'apellido'=>array('justification'=>'left','width'=>220))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('titulo1'=>'<b>Teléfono Hab.</b>','habitacion'=>$as_telhabper,
						  'titulo2'=>'<b>Teléfono Mov.</b>','movil'=>$as_telmovper,
						  'titulo3'=>'<b>Email</b>','email'=>$as_coreleper);
		$la_columna=array('titulo1'=>'','habitacion'=>'','titulo2'=>'','movil'=>'','titulo3'=>'','email'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 11, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>690, // Ancho de la tabla
						 'maxWidth'=>690, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'rowGap'=>4,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo1'=>array('justification'=>'left','width'=>90), // Justificación y ancho de la columna
						 			   'habitacion'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'titulo2'=>array('justification'=>'left','width'=>90), // Justificación y ancho de la columna
						 			   'movil'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'titulo3'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'email'=>array('justification'=>'left','width'=>170))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('titulo1'=>'<b>Dirección</b>','direccion'=>$as_dirper);
		$la_columna=array('titulo1'=>'','direccion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>690, // Ancho de la tabla
						 'maxWidth'=>690, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'rowGap'=>4,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo1'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'direccion'=>array('justification'=>'left','width'=>630))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_datos[1]=array('numper'=>'<b>Nro</b>','feciniper'=>'<b>Inicio</b>',
		                   'fecfinper'=>'<b>Fin</b>','numdiaper'=>'<b>Nro de Días</b>',
						   'numhoras'=>'<b>Nro de Horas</b>',	
						   'afevacper'=>'<b>Afecta Vacaciones</b>','remper'=>'<b>Remunerado</b>',
						   'tipper'=>'<b>Tipo</b>','obsper'=>'<b>Observación</b>');
		$la_columna=array('numper'=>'','feciniper'=>'','fecfinper'=>'',
		                  'numdiaper'=>'','numhoras'=>'','afevacper'=>'',
						  'remper'=>'','tipper'=>'','obsper'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 11, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>690, // Ancho de la tabla
						 'maxWidth'=>90, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('numper'=>array('justification'=>'center','width'=>40),
						 			   'feciniper'=>array('justification'=>'center','width'=>60),
									   'fecfinper'=>array('justification'=>'center','width'=>60),
									   'numdiaper'=>array('justification'=>'center','width'=>50),
									   'numhoras'=>array('justification'=>'center','width'=>50),
									   'afevacper'=>array('justification'=>'center','width'=>80),
									   'remper'=>array('justification'=>'center','width'=>80),
									   'tipper'=>array('justification'=>'center','width'=>80),
									   'obsper'=>array('justification'=>'center','width'=>200))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);	
		unset($la_datos);
		unset($la_columna);
		unset($la_config);		
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	function uf_print_cabecera_unidad($as_denominacion, $as_cod1, $as_cod2, $as_cod3, $as_cod4, $as_cod5, &$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_unidad
		//		   Access: private 
		//	    Arguments: as_denominacion // denominacion de la unidad
		//	    		   as_cod1 // 
		//	    		   as_cod2 // 
		//	    		   as_cod3 // 
		//	    		   as_cod4 // 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera de la unidad administrativa
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 21/07/2008		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $io_pdf->ezSetDy(-15);
		$la_data[1]=array('denominacion'=>'<b>'.$as_cod1.$as_cod2.$as_cod3.$as_cod4.$as_cod5.'</b>'." ".'<b>'.$as_denominacion.'</b>');
		$la_columnas=array('denominacion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.7,0.7,0.7), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center',
						 'cols'=>array('denominacion'=>array('justification'=>'left','width'=>700))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);	
	}// end uf_print_cabecera_unidad


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_datos($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_datos
		//		   Access: private 
		//	    Arguments: la_data // Arreglo con loa datos
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los datos del Personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/07/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-1);
		$la_columna=array('numper'=>'','feciniper'=>'','fecfinper'=>'',
		                  'numdiaper'=>'','numhoras'=>'','afevacper'=>'',
						  'remper'=>'','tipper'=>'','obsper'=>'', 'numhoras'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>690, // Ancho de la tabla
						 'maxWidth'=>90, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('numper'=>array('justification'=>'center','width'=>40),
						 			   'feciniper'=>array('justification'=>'center','width'=>60),
									   'fecfinper'=>array('justification'=>'center','width'=>60),
									   'numhoras'=>array('justification'=>'center','width'=>50),
									   'numdiaper'=>array('justification'=>'center','width'=>50),
									   'afevacper'=>array('justification'=>'center','width'=>80),
									   'remper'=>array('justification'=>'center','width'=>80),
									   'tipper'=>array('justification'=>'center','width'=>80),
									   'obsper'=>array('justification'=>'left','width'=>200))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_datos
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($as_total1, $as_total2, $as_total3, $as_total4,$as_total5, $as_total6,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_datos
		//		   Access: private 
		//	    Arguments: la_data // Arreglo con loa datos
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los totales de las horas y dias de permisos
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 230/07/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-1);
		$la_data[1]=array('denominacion'=>'<b>TOTAL REMUNERADO</b>','total1'=>$as_total1.' DIA(S)', 'total2'=>$as_total2.' HORA(S)');
		$la_data[2]=array('denominacion'=>'<b>TOTAL NO REMUNERADO</b>','total1'=>$as_total3.' DIA(S)', 'total2'=>$as_total4.' HORA(S)');
		$la_data[3]=array('denominacion'=>'<b>TOTAL DESC. VACAC</b>','total1'=>$as_total5.' DIA(S)', 'total2'=>$as_total6.' HORA(S)');
		$la_columna=array('denominacion'=>'','total1'=>'','total2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>690, // Ancho de la tabla
						 'maxWidth'=>90, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('denominacion'=>array('justification'=>'center','width'=>500),
						 			   'total1'=>array('justification'=>'center','width'=>100),
									   'total2'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end uf_print_totales
//------------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>Permisos por Personal</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_activo=$io_fun_nomina->uf_obtenervalor_get("activo","");
	$ls_egresado=$io_fun_nomina->uf_obtenervalor_get("egresado","");
	$ls_causaegreso=$io_fun_nomina->uf_obtenervalor_get("causaegreso","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_activono=$io_fun_nomina->uf_obtenervalor_get("activono","");
	$ls_vacacionesno=$io_fun_nomina->uf_obtenervalor_get("vacacionesno","");
	$ls_suspendidono=$io_fun_nomina->uf_obtenervalor_get("suspendidono","");
	$ls_egresadono=$io_fun_nomina->uf_obtenervalor_get("egresadono","");
	$ls_masculino=$io_fun_nomina->uf_obtenervalor_get("masculino","");
	$ls_femenino=$io_fun_nomina->uf_obtenervalor_get("femenino","");

	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_personalunidadadm($ls_codnomdes,$ls_codnomhas,
		                                            $ls_codperdes,$ls_codperhas,
													$ls_activo,$ls_egresado,
										 		    $ls_activono,
													$ls_vacacionesno,$ls_suspendidono,$ls_egresadono,
										 		    $ls_masculino,$ls_femenino);	
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
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3,2.2,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(750,50,10,'','',1); // Insertar el número de página		
		$li_unidad=$io_report->DS_nominas->getRowCount("codper");
		$unidadaux=""; 
		for($li_j=1;(($li_j<=$li_unidad)&&($lb_valido));$li_j++)
		{
		   $ls_codperdes=$io_report->DS_nominas->data["codper"][$li_j];
		   $ls_codperhas=$io_report->DS_nominas->data["codper"][$li_j];
		   $ls_denuniadm=$io_report->DS_nominas->data["desuniadm"][$li_j];
		   $ls_codminorguniadm=$io_report->DS_nominas->data["minorguniadm"][$li_j];
		   $ls_codofiuniadm=$io_report->DS_nominas->data["ofiuniadm"][$li_j];
		   $ls_coduniuniadm=$io_report->DS_nominas->data["uniuniadm"][$li_j];
		   $ls_coddepuniadm=$io_report->DS_nominas->data["depuniadm"][$li_j];
		   $ls_codprouniadm=$io_report->DS_nominas->data["prouniadm"][$li_j];
		   $io_report->uf_permisospersonal_personal($ls_codnomdes,$ls_codnomhas,$ls_codperdes,$ls_codperhas,$ls_activo,
													$ls_egresado,$ls_causaegreso,$ls_activono,$ls_vacacionesno,
													$ls_suspendidono,$ls_egresadono,$ls_masculino,$ls_femenino,$ls_orden); 
		   $li_totrow=$io_report->DS->getRowCount("codper");	
		   if (($unidadaux!=$ls_denuniadm)&&($li_totrow>0))
		    {
		        $unidadaux=$ls_denuniadm;
		    	uf_print_cabecera_unidad($ls_denuniadm,$ls_codminorguniadm,$ls_codofiuniadm, 
		                            $ls_coduniuniadm, $ls_coddepuniadm, $ls_codprouniadm,$io_pdf);
		    }
			
			for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
			{
				$ls_codper=$io_report->DS->data["codper"][$li_i];
				$ls_cedper=$io_report->DS->data["cedper"][$li_i];
				$ls_nomper=$io_report->DS->data["nomper"][$li_i];
				$ls_apeper=$io_report->DS->data["apeper"][$li_i];
				$ls_estper=$io_report->DS->data["estper"][$li_i];
				$ls_dirper=$io_report->DS->data["dirper"][$li_i];
				$ls_telhabper=$io_report->DS->data["telhabper"][$li_i];
				$ls_telmovper=$io_report->DS->data["telmovper"][$li_i];
				$ls_coreleper=$io_report->DS->data["coreleper"][$li_i];
				switch ($ls_estper)
				{
					case "0":
						$ls_estper="Pre-Ingreso";
						break;
					case "1":
						$ls_estper="Activo";
						break;
					case "2":
						$ls_estper="N/A";
						break;
					case "3":
						$ls_estper="Egresado";
						break;
				}				
				uf_print_cabecera($ls_cedper,$ls_nomper,$ls_apeper,$ls_estper,$ls_dirper,$ls_telhabper,$ls_telmovper,$ls_coreleper,
								  &$io_pdf);
				$lb_valido=$io_report->uf_permisospersonal_permiso($ls_codper); // Obtenemos el detalle del reporte
				if($lb_valido)
				{
					$li_total=$io_report->DS_detalle->getRowCount("numper");
					$ls_totdia_rem=0; 
					$ls_tothoras_rem=0; 
					$ls_totdia_no_rem=0; 
					$ls_tothoras_no_rem=0; 
					$ls_totdia_des_vac=0; 
					$ls_tothoras_des_vac=0; 
					for($li_k=1;(($li_k<=$li_total)&&($lb_valido));$li_k++)
					{  
						$li_numper=$io_report->DS_detalle->data["numper"][$li_k];
						$ld_feciniper=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["feciniper"][$li_k]);
						$ld_fecfinper=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["fecfinper"][$li_k]);
						$li_numdiaper=$io_report->DS_detalle->data["numdiaper"][$li_k];
						$ls_afevacper=$io_report->DS_detalle->data["afevacper"][$li_k];
						$ls_numhoras=$io_report->DS_detalle->data["tothorper"][$li_k];
						switch($ls_afevacper)
						{
							case "1":
								$ls_afevacper="NO";
								break;
							
							default:
								$ls_afevacper="SI";
								$ls_totdia_des_vac=$ls_totdia_des_vac+$li_numdiaper;  
								$ls_tothoras_des_vac=$ls_tothoras_des_vac+$ls_numhoras;   
								break;
						}
						$ls_remper=$io_report->DS_detalle->data["remper"][$li_k];
						switch($ls_remper)
						{
							case "1":
								$ls_remper="SI";
								$ls_totdia_rem=$ls_totdia_rem+$li_numdiaper;
								$ls_tothoras_rem=$ls_tothoras_rem+$ls_numhoras;
								break;
							
							default:
								$ls_remper="NO";
								$ls_totdia_no_rem=$ls_totdia_no_rem+$li_numdiaper; 
								$ls_tothoras_no_rem=$ls_tothoras_no_rem+$ls_numhoras;
								break;
						}
						$ls_tipper=$io_report->DS_detalle->data["tipper"][$li_k];
						switch($ls_tipper)
						{
							case "1":
								$ls_tipper="Estudio";
								break;
							
							case "2":
								$ls_tipper="Médico";
								break;
							case "3":
								$ls_tipper="Tramites";
								break;
	
							case "4":
								$ls_tipper="Otro";
								break;
							
							default:
								$ls_tipper="";
								break;
						}
						$ls_obsper=rtrim($io_report->DS_detalle->data["obsper"][$li_k]);
						$la_data[$li_k]=array('numper'=>$li_numper,'feciniper'=>$ld_feciniper,'fecfinper'=>$ld_fecfinper,
											  'numdiaper'=>$li_numdiaper,'afevacper'=>$ls_afevacper,'remper'=>$ls_remper,
											  'tipper'=>$ls_tipper,'obsper'=>$ls_obsper,'numhoras'=>$ls_numhoras);
					}
					uf_print_datos($la_data,&$io_pdf);
					uf_print_totales($ls_totdia_rem,$ls_tothoras_rem,$ls_totdia_no_rem,$ls_tothoras_no_rem,
					                 $ls_totdia_des_vac,$ls_tothoras_des_vac,&$io_pdf);
					unset($la_data);
					$io_report->DS_detalle->resetds("numper");
					
				}							
			}// fin del for de permisos
			$io_report->DS->resetds("codper");		
		}// fin del for (unidad)	
		
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 