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
	ini_set('memory_limit','256M');
	ini_set('max_execution_time','0');

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo,$ai_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_pagonomina.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_hpagonomina.php",$ls_descripcion,$ls_codnom);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_desnom,$as_periodo,$as_descripcion,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/05/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,750,40);
		$io_pdf->addText(50,30,7,$_SESSION["la_empresa"]["nombre"]); // Agregar la empresa
		$io_pdf->addJpegFromFile('../../shared/imagebank/logo_hci_recibo.jpg',50,560,320,40); // Agregar Logo
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],680,520,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,545,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(10,$as_periodo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,535,10,$as_periodo); // Agregar el título
		$io_pdf->addText(620,580,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(626,573,7,date("h:i a")); // Agregar la Hora
		$io_pdf->ezSety(535);		
		$la_data[1]=array('tipo'=>'<b>Tipo Nómina</b>','nombre'=>'<b>'.$as_descripcion.'</b>');
		$la_columnas=array('tipo'=>'',
						   'nombre'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>420, // Ancho de la tabla
						 'maxWidth'=>420, // Ancho Máximo de la tabla
						 'xPos'=>243.5,
						 'cols'=>array('tipo'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>340))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('cedula'=>'','concepto'=>'<b>CONCEPTO</b>','asignacion'=>'<b>ASIGNACIONES</b>',
						  'deduccion'=>'<b>DEDUCCIONES</b>','neto'=>'<b>NETO</b>','sueldo'=>'<b>SUELDO MENSUAL</b>');
		$la_columnas=array('cedula'=>'',
						   'concepto'=>'',
						   'asignacion'=>'',
						   'deduccion'=>'',
						   'neto'=>'',
						   'sueldo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cedula'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						 			   'concepto'=>array('justification'=>'left','width'=>375),
						 			   'asignacion'=>array('justification'=>'center','width'=>65),
						 			   'deduccion'=>array('justification'=>'center','width'=>65),
						 			   'neto'=>array('justification'=>'center','width'=>65),
						 			   'sueldo'=>array('justification'=>'center','width'=>75))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);	
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_cedper,$as_apenomper,$as_descar,$ad_fecingper,$ai_totalasignacion,$ai_totaldeduccion,
							   $ai_total_neto,$ai_sueper,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_cedper // cédula del personal
		//	    		   as_apenomper // apellidos y nombre del personal
		//	    		   as_descar // descripción del cargo
		//	    		   as_desuniadm // descripción de la unidad administrativa
		//	    		   ad_fecingper // fecha de ingreso
		//	    		   as_codcueban // código de lla cuenta bancaria
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('cedula'=>$as_cedper,'nombre'=>$as_apenomper,'fecha'=>$ad_fecingper,'cargo'=>$as_descar,
						  'asignacion'=>$ai_totalasignacion,'deduccion'=>$ai_totaldeduccion,'neto'=>$ai_total_neto,
						  'sueldo'=>$ai_sueper);
		$la_columnas=array('cedula'=>'',
						   'nombre'=>'',
						   'fecha'=>'',
						   'cargo'=>'',
						   'asignacion'=>'',
						   'deduccion'=>'',
						   'neto'=>'',
						   'sueldo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cedula'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>165), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>45),
									   'cargo'=>array('justification'=>'left','width'=>165),
						 			   'asignacion'=>array('justification'=>'right','width'=>65),
						 			   'deduccion'=>array('justification'=>'right','width'=>65),
						 			   'neto'=>array('justification'=>'right','width'=>65),
						 			   'sueldo'=>array('justification'=>'right','width'=>75))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);	
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
		//    Description: función que imprime el detalle del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columnas=array('codigo'=>'',
						   'nombre'=>'',
						   'asignacion'=>'',
						   'deduccion'=>'',
						   'neto'=>'',
						   'sueldo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>375), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>65),
						 			   'deduccion'=>array('justification'=>'right','width'=>65),
						 			   'neto'=>array('justification'=>'right','width'=>65),
						 			   'sueldo'=>array('justification'=>'right','width'=>75))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);	
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	$ls_tiporeporte="0";
	if($_SESSION["la_nomina"]["tiponomina"]=="NORMAL")
	{
		require_once("sigesp_sno_class_report.php");
		$io_report=new sigesp_sno_class_report();
		$li_tipo=1;
	}
	if($_SESSION["la_nomina"]["tiponomina"]=="HISTORICA")
	{
		require_once("sigesp_sno_class_report_historico.php");
		$io_report=new sigesp_sno_class_report_historico();
		$li_tipo=2;
	}	
	$ls_bolivares ="Bs.";
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$ls_titulo="<b>DETALLES DE NÓMINA EN ".$ls_bolivares."</b>";
	$ls_periodo="<b>".substr($ld_fechasper,0,2)." de ".$io_fecha->uf_load_nombre_mes(substr($ld_fechasper,3,2))." de ".substr($ld_fechasper,6,4)."</b>";
	$ls_tipnom=$_SESSION["la_nomina"]["tipnom"];
	$ls_descripcion="";
	switch($ls_tipnom)
	{
		case "1":
			$ls_descripcion="Empleado Fijo";
			break;
		case "2":
			$ls_descripcion="Empleado Contratado";
			break;
		case "3":
			$ls_descripcion="Obrero Fijo";
			break;
		case "4":
			$ls_descripcion="Obrero Contratado";
			break;
		case "5":
			$ls_descripcion="Docente Fijo";
			break;
		case "6":
			$ls_descripcion="Docente Contratado";
			break;
		case "7":
			$ls_descripcion="Jubilado";
			break;
		case "8":
			$ls_descripcion="Comision de Servicios";
			break;
		case "9":
			$ls_descripcion="Libre Nombramiento";
			break;
	}
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_tituloconcepto=$io_fun_nomina->uf_obtenervalor_get("tituloconcepto","");
	$ls_conceptoreporte=$io_fun_nomina->uf_obtenervalor_get("conceptoreporte","");
	$ls_conceptop2=$io_fun_nomina->uf_obtenervalor_get("conceptop2","");
	$ls_codubifis=$io_fun_nomina->uf_obtenervalor_get("codubifis","");
	$ls_codpai=$io_fun_nomina->uf_obtenervalor_get("codpai","");
	$ls_codest=$io_fun_nomina->uf_obtenervalor_get("codest","");
	$ls_codmun=$io_fun_nomina->uf_obtenervalor_get("codmun","");
	$ls_codpar=$io_fun_nomina->uf_obtenervalor_get("codpar","");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_pagonomina_personal($ls_codperdes,$ls_codperhas,$ls_conceptocero,$ls_conceptoreporte,$ls_conceptop2,
													  $ls_codubifis,$ls_codpai,$ls_codest,$ls_codmun,$ls_codpar,$ls_subnomdes,$ls_subnomhas,$ls_orden); // Cargar el DS con los datos de la cabecera del reporte
	}
	$li_totrow=$io_report->rs_data->RecordCount();
	if(($lb_valido==false) || ($li_totrow == 0) )// Existe algún error ó no hay registros
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
		$io_pdf->ezSetCmMargins(3.6,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_desnom,$ls_periodo,$ls_descripcion,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(750,30,8,'','',1); // Insertar el número de página
		$io_pdf->FitWindow=true;
		$li_totrow=$io_report->rs_data->RecordCount();
		$li_totasi=0;
		$li_totded=0;
		$li_totapo=0;
		$li_totgeneral=0;
		while((!$io_report->rs_data->EOF)&&($lb_valido))
		{
	        $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$li_totalasignacion=0;
			$li_totaldeduccion=0;
			$li_totalaporte=0;
			$li_total_neto=0;
			$ls_codper=$io_report->rs_data->fields["codper"];
			$ls_cedper=$io_report->rs_data->fields["cedper"];
			$ls_apenomper=$io_report->rs_data->fields["apeper"].", ". $io_report->rs_data->fields["nomper"];
			$ls_descar=$io_report->rs_data->fields["descar"];
			$ls_desuniadm=$io_report->rs_data->fields["desuniadm"];
			$ld_fecingper=$io_funciones->uf_convertirfecmostrar($io_report->rs_data->fields["fecingper"]);
			$ls_codcueban=$io_report->rs_data->fields["codcueban"];
			$li_sueper=$io_fun_nomina->uf_formatonumerico($io_report->rs_data->fields["sueper"]);
			$lb_valido=$io_report->uf_pagonomina_conceptopersonal($ls_codper,$ls_conceptocero,$ls_tituloconcepto,$ls_conceptoreporte,$ls_conceptop2); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_res=$io_report->rs_data_detalle->RecordCount();
				$li_s=1;
				while (!$io_report->rs_data_detalle->EOF)
				{
					$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
					$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
					$ls_tipsal=rtrim($io_report->rs_data_detalle->fields["tipsal"]);
					$li_valsal=abs($io_report->rs_data_detalle->fields["valsal"]);
					switch($ls_tipsal)
					{
						case "A":
							$li_totalasignacion=$li_totalasignacion + $li_valsal;
							$li_asignacion=$io_fun_nomina->uf_formatonumerico($li_valsal);
							$li_deduccion=""; 
							$li_aporte=""; 
							$ls_nomcon="A - ".$ls_nomcon;
							break;
							
						case "V1":
							$li_totalasignacion=$li_totalasignacion + $li_valsal;
							$li_asignacion=$io_fun_nomina->uf_formatonumerico($li_valsal);
							$li_deduccion=""; 
							$li_aporte=""; 
							$ls_nomcon="A - ".$ls_nomcon;
							break;
							
						case "W1":
							$li_totalasignacion=$li_totalasignacion + $li_valsal;
							$li_asignacion=$io_fun_nomina->uf_formatonumerico($li_valsal);
							$li_deduccion=""; 
							$li_aporte=""; 
							$ls_nomcon="A - ".$ls_nomcon;
							break;
							
						case "D":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=$io_fun_nomina->uf_formatonumerico($li_valsal);
							$li_aporte=""; 
							$ls_nomcon="D - ".$ls_nomcon;
							break;
							
						case "V2":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=$io_fun_nomina->uf_formatonumerico($li_valsal);
							$li_aporte=""; 
							$ls_nomcon="D - ".$ls_nomcon;
							break;
							
						case "W2":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=$io_fun_nomina->uf_formatonumerico($li_valsal);
							$li_aporte=""; 
							$ls_nomcon="D - ".$ls_nomcon;
							break;

						case "P1":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=$io_fun_nomina->uf_formatonumerico($li_valsal);
							$li_aporte=""; 
							$ls_nomcon="D - ".$ls_nomcon;
							break;

						case "V3":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=$io_fun_nomina->uf_formatonumerico($li_valsal);
							$li_aporte=""; 
							$ls_nomcon="D - ".$ls_nomcon;
							break;

						case "W3":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=$io_fun_nomina->uf_formatonumerico($li_valsal);
							$li_aporte=""; 
							$ls_nomcon="D - ".$ls_nomcon;
							break;

						case "P2":
							$li_totalaporte=$li_totalaporte + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=""; 
							$li_aporte=$io_fun_nomina->uf_formatonumerico($li_valsal);
							break;

						case "V4":
							$li_totalaporte=$li_totalaporte + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=""; 
							$li_aporte=$io_fun_nomina->uf_formatonumerico($li_valsal);
							break;

						case "W4":
							$li_totalaporte=$li_totalaporte + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=""; 
							$li_aporte=$io_fun_nomina->uf_formatonumerico($li_valsal);
							break;

						case "R":
							$li_asignacion=$io_fun_nomina->uf_formatonumerico($li_valsal);
							$li_deduccion=""; 
							$li_aporte="";
							$ls_nomcon="R - ".$ls_nomcon;
							break;
					}
					$la_data[$li_s]=array('codigo'=>'','nombre'=>$ls_nomcon,'asignacion'=>$li_asignacion,
										  'deduccion'=>$li_deduccion,'neto'=>'','sueldo'=>'');
					$li_s++;
					$io_report->rs_data_detalle->MoveNext();					  
				}
				$li_total_neto=$li_totalasignacion-$li_totaldeduccion;
				$li_totasi=$li_totasi+$li_totalasignacion;
				$li_totded=$li_totded+$li_totaldeduccion;
				$li_totapo=$li_totapo+$li_totalaporte;
				$li_totgeneral=$li_totgeneral+$li_total_neto;
				$li_totalasignacion=$io_fun_nomina->uf_formatonumerico($li_totalasignacion);
				$li_totaldeduccion=$io_fun_nomina->uf_formatonumerico($li_totaldeduccion);
				$li_totalaporte=$io_fun_nomina->uf_formatonumerico($li_totalaporte);
				$li_total_neto=$io_fun_nomina->uf_formatonumerico($li_total_neto);
				uf_print_cabecera($ls_cedper,$ls_apenomper,$ls_descar,$ld_fecingper,$li_totalasignacion,$li_totaldeduccion,
								  $li_total_neto,$li_sueper,$io_pdf); // Imprimimos la cabecera del registro
  			    uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle  
				if ($io_pdf->ezPageCount==$li_numpag)
				{// Hacemos el commit de los registros que se desean imprimir
					$io_pdf->transaction('commit');
				}
				else
				{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
					$io_pdf->transaction('rewind');
					$io_pdf->ezNewPage(); // Insertar una nueva página
					uf_print_cabecera($ls_cedper,$ls_apenomper,$ls_descar,$ld_fecingper,$li_totalasignacion,$li_totaldeduccion,
									  $li_total_neto,$li_sueper,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				}
			}
			unset($la_data);			
			$io_report->rs_data->MoveNext();			
		}
		$li_totasi=$io_fun_nomina->uf_formatonumerico($li_totasi);
		$li_totded=$io_fun_nomina->uf_formatonumerico($li_totded);
		$li_totapo=$io_fun_nomina->uf_formatonumerico($li_totapo);
		$li_totgeneral=$io_fun_nomina->uf_formatonumerico($li_totgeneral);
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