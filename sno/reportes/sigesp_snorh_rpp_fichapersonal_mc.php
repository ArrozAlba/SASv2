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
		// Fecha Creación: 26/04/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_fichapersonal.php",$ls_descripcion);
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
		// Fecha Creación: 26/04/2007
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
	function uf_print_ubicacion($as_empresa,$as_desuniadm,$as_descar,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_ubicacion
		//		   Access: private 
		//	    Arguments: as_empresa // Nombre de la empresa
		//	    		   as_desuniadm // Descripción de la unidad administrativa
		//	    		   as_descar // descripción del cargo
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la ubicación
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('titulo'=>'<b>Ubicación del Beneficiario</b>'));
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 12, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>690, // Ancho de la tabla
						 'maxWidth'=>90, // Ancho Máximo de la tabla
						 'rowGap'=>4,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>690))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('nombre'=>'<b>Ministerio al que Pertenecene</b>','descripcion'=>$as_empresa);
		$la_data[2]=array('nombre'=>'<b>Deparamento</b>','descripcion'=>$as_desuniadm);
		$la_data[3]=array('nombre'=>'<b>Cargo</b>','descripcion'=>$as_descar);
		$la_columna=array('nombre'=>'','descripcion'=>'');
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
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>170), // Justificación y ancho de la columna
						 			   'descripcion'=>array('justification'=>'left','width'=>520))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_personal($as_cedper,$as_nomper,$as_apeper,$as_dirper,$as_desest,$as_denmun,$as_coreleper,$as_sexper,
			                   $as_telhabper,$as_telmovper,$ad_fecnacper,$as_nacper,$as_edocivper,$ai_numhijper,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_personal
		//		   Access: private 
		//	    Arguments: as_cedper // Cédula del Personal
		//	    		   as_nomper // Nombre del Personal
		//	    		   as_apeper // Apellido del Personal
		//	    		   as_dirper // Dirección del Personal
		//	    		   as_desest // Estado donde se ubico el Personal
		//	    		   as_denmun // Municipio donde se ubico el Personal
		//	    		   as_coreleper // Correo electronico del Personal
		//	    		   as_sexper // Sexo del Personal
		//	    		   as_telhabper // Teléfono de Habitación del Personal
		//	    		   as_telmovper // Teléfono Móvil del Personal
		//	    		   ad_fecnacper // Fecha de Nacimiento del Personal
		//	    		   as_nacper // Nacionalidad del Personal
		//	    		   as_edocivper // Estado Civil del Personal
		//	    		   ai_numhijper // Número de Hijos
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los datos del Personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('titulo'=>'');
		$la_data[2]=array('titulo'=>'<b>Datos Personales</b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 12, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>1, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>690, // Ancho de la tabla
						 'maxWidth'=>90, // Ancho Máximo de la tabla
						 'rowGap'=>4,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>690))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);	
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
						 'cols'=>array('titulo1'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'left','width'=>100), // Justificación y ancho de la columna
						 			   'titulo2'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>205), // Justificación y ancho de la columna
						 			   'titulo3'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'apellido'=>array('justification'=>'left','width'=>205))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('titulo1'=>'<b>Dirección</b>','direccion'=>$as_dirper);
		$la_columna=array('titulo1'=>'','direccion'=>'');
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
						 'cols'=>array('titulo1'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'direccion'=>array('justification'=>'left','width'=>630))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('titulo1'=>'<b>Estado</b>','estado'=>$as_desest,
						  'titulo2'=>'<b>Municipio</b>','municipio'=>$as_denmun,
						  'titulo3'=>'<b>Email</b>','email'=>$as_coreleper,
						  'titulo4'=>'<b>Género</b>','sexo'=>$as_sexper);
		$la_columna=array('titulo1'=>'','estado'=>'','titulo2'=>'','municipio'=>'','titulo3'=>'','email'=>'','titulo4'=>'','sexo'=>'');
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
						 			   'estado'=>array('justification'=>'left','width'=>130), // Justificación y ancho de la columna
						 			   'titulo2'=>array('justification'=>'left','width'=>70), // Justificación y ancho de la columna
						 			   'municipio'=>array('justification'=>'left','width'=>130), // Justificación y ancho de la columna
						 			   'titulo3'=>array('justification'=>'left','width'=>50), // Justificación y ancho de la columna
						 			   'email'=>array('justification'=>'left','width'=>190), // Justificación y ancho de la columna
						 			   'titulo4'=>array('justification'=>'left','width'=>40), // Justificación y ancho de la columna
						 			   'sexo'=>array('justification'=>'left','width'=>30))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('titulo1'=>'<b>Teléfono</b>','telefono'=>$as_telhabper,
						  'titulo2'=>'<b>Celular</b>','celular'=>$as_telmovper,
						  'titulo3'=>'<b>Fecha de Nacimiento</b>','fecha'=>$ad_fecnacper);
		$la_columna=array('titulo1'=>'','telefono'=>'','titulo2'=>'','celular'=>'','titulo3'=>'','fecha'=>'');
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
						 'cols'=>array('titulo1'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'telefono'=>array('justification'=>'left','width'=>190), // Justificación y ancho de la columna
						 			   'titulo2'=>array('justification'=>'left','width'=>55), // Justificación y ancho de la columna
						 			   'celular'=>array('justification'=>'left','width'=>190), // Justificación y ancho de la columna
						 			   'titulo3'=>array('justification'=>'left','width'=>125), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'left','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('titulo1'=>'<b>Nacionalidad</b>','nacionalidad'=>$as_nacper,
						  'titulo2'=>'<b>Estado Civil</b>','estado'=>$as_edocivper,
						  'titulo3'=>'<b>Número de Hijos</b>','hijos'=>$ai_numhijper);
		$la_columna=array('titulo1'=>'','nacionalidad'=>'','titulo2'=>'','estado'=>'','titulo3'=>'','hijos'=>'');
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
						 'cols'=>array('titulo1'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
						 			   'nacionalidad'=>array('justification'=>'left','width'=>170), // Justificación y ancho de la columna
						 			   'titulo2'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
						 			   'estado'=>array('justification'=>'left','width'=>170), // Justificación y ancho de la columna
						 			   'titulo3'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna
						 			   'hijos'=>array('justification'=>'left','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);


	}// end function uf_print_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_datos($as_despro,$ad_fecingper,$ad_fecegrper,$as_estper,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_datos
		//		   Access: private 
		//	    Arguments: as_despro // Descripción de la Profesión
		//	    		   ad_fecingper // Fecha de Ingreso
		//	    		   ad_fecegrper // Fecha de Egreso
		//	    		   as_estper // Estatus
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los datos del Personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('titulo'=>'');
		$la_data[2]=array('titulo'=>'<b>Datos Laborales</b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 12, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>1, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>690, // Ancho de la tabla
						 'maxWidth'=>90, // Ancho Máximo de la tabla
						 'rowGap'=>4,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>690))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('titulo1'=>'<b>Profesión</b>','profesion'=>$as_despro);
		$la_columna=array('titulo1'=>'','profesion'=>'');
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
						 'cols'=>array('titulo1'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'profesion'=>array('justification'=>'left','width'=>630))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('titulo1'=>'<b>Fecha de Ingreso</b>','ingreso'=>$ad_fecingper,
						  'titulo2'=>'<b>Fecha de Egreso</b>','egreso'=>$ad_fecegrper,
						  'titulo3'=>'<b>Estatus</b>','estatus'=>$as_estper);
		$la_columna=array('titulo1'=>'','ingreso'=>'','titulo2'=>'','egreso'=>'','titulo3'=>'','estatus'=>'');
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
						 'cols'=>array('titulo1'=>array('justification'=>'left','width'=>130), // Justificación y ancho de la columna
						 			   'ingreso'=>array('justification'=>'left','width'=>100), // Justificación y ancho de la columna
						 			   'titulo2'=>array('justification'=>'left','width'=>130), // Justificación y ancho de la columna
						 			   'egreso'=>array('justification'=>'left','width'=>100), // Justificación y ancho de la columna
						 			   'titulo3'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
						 			   'estatus'=>array('justification'=>'left','width'=>150))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_datos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>Ficha de Personal</b>";
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
		$lb_valido=$io_report->uf_fichapersonal_personal($ls_codnomdes,$ls_codnomhas,$ls_codperdes,$ls_codperhas,$ls_activo,
														 $ls_egresado,$ls_causaegreso,$ls_activono,$ls_vacacionesno,
														 $ls_suspendidono,$ls_egresadono,$ls_masculino,$ls_femenino,$ls_orden); // Obtenemos el detalle del reporte
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
		$io_pdf->ezSetCmMargins(3.5,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(750,50,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->DS->getRowCount("codper");
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_empresa=$_SESSION["la_empresa"]["nombre"];
			$ls_desuniadm=$io_report->DS->data["desuniadm"][$li_i];
			$ls_denasicar=rtrim($io_report->DS->data["denasicar"][$li_i]);
			$ls_descar=rtrim($io_report->DS->data["descar"][$li_i]);
			$ls_cedper=$io_report->DS->data["cedper"][$li_i];
			$ls_nomper=$io_report->DS->data["nomper"][$li_i];
			$ls_apeper=$io_report->DS->data["apeper"][$li_i];
			$ls_dirper=$io_report->DS->data["dirper"][$li_i];
			$ls_desest=$io_report->DS->data["desest"][$li_i];
			$ls_denmun=$io_report->DS->data["denmun"][$li_i];
			$ls_coreleper=$io_report->DS->data["coreleper"][$li_i];
			$ls_sexper=$io_report->DS->data["sexper"][$li_i];
			$ls_telhabper=$io_report->DS->data["telhabper"][$li_i];
			$ls_telmovper=$io_report->DS->data["telmovper"][$li_i];
			$ld_fecnacper=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecnacper"][$li_i]);
			$ls_nacper=$io_report->DS->data["nacper"][$li_i];
			$ls_edocivper=$io_report->DS->data["edocivper"][$li_i];
			$li_numhijper=$io_report->DS->data["numhijper"][$li_i];
			$ls_despro=$io_report->DS->data["despro"][$li_i];
			$ld_fecingper=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecingper"][$li_i]);
			$ld_fecegrper=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecegrper"][$li_i]);
			$ls_estper=$io_report->DS->data["estper"][$li_i];
			if($ld_fecegrper=="01/01/1900")
			{
				$ld_fecegrper="";
			}
			if($ls_nacper=="V")
			{
				$ls_nacper="Venezolano";
			}
			else
			{
				$ls_nacper="Extranjero";
			}
			switch ($ls_edocivper)
			{
				case "S":
					$ls_edocivper="Soltero";
					break;
				
				case "C":
					$ls_edocivper="Casado";
					break;
				
				case "D":
					$ls_edocivper="Divorciado";
					break;
				
				case "V":
					$ls_edocivper="Viudo";
					break;
				
				case "K":
					$ls_edocivper="Concubino";
					break;
			}
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
			$ls_cargo="";
			if($ls_denasicar=="")
			{
				$ls_cargo=$ls_descar;
			}
			else
			{
				$ls_cargo=$ls_denasicar;
			}
			uf_print_ubicacion($ls_empresa,$ls_desuniadm,$ls_descar,&$io_pdf);
			uf_print_personal($ls_cedper,$ls_nomper,$ls_apeper,$ls_dirper,$ls_desest,$ls_denmun,$ls_coreleper,$ls_sexper,
			                  $ls_telhabper,$ls_telmovper,$ld_fecnacper,$ls_nacper,$ls_edocivper,$li_numhijper,&$io_pdf);
			uf_print_datos($ls_despro,$ld_fecingper,$ld_fecegrper,$ls_estper,&$io_pdf);
			if($li_i<$li_totrow)
			{
				$io_pdf->ezNewPage(); // Insertar una nueva página
			}
		}
		$io_report->DS->resetds("codper");
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