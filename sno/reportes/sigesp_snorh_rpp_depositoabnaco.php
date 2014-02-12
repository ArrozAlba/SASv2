<?PHP
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

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//	    		   as_desnom // descripción de la nómina
		//	    		   as_periodo // período actual de la nómina
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_depositobanco.php",$ls_descripcion);
		return $lb_valido;
	}		
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_periodo,&$io_pdf)
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
		// Fecha Creación: 03/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,755,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,520,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,550,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,535,11,$as_periodo); // Agregar el título		
		$io_pdf->addText(712,560,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(718,553,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_nomina($as_codnom, $as_desnom,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_nomban // Nombre del Banco
		//	    		   io_cabecera // Objeto cabecera
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera por banco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $la_dato_nomina[1]=array('codigo'=>$as_codnom,'nombre'=>$as_desnom);
		$la_columna=array('codigo'=>'','nombre'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codigo'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>590))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_dato_nomina,$la_columna,'',$la_config);	
	}// uf_print_cabecera_nomina
	//--------------------------------------------------------------------------------------------------------------------------------
    function uf_print_cabecera_banco($as_codbanco, $as_nomban,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_nomban // Nombre del Banco
		//	    		   io_cabecera // Objeto cabecera
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera por banco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $la_dato_banco[1]=array('codigo'=>$as_codbanco,'nombre'=>$as_nomban);
		$la_columna=array('codigo'=>'','nombre'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codigo'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>560))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_dato_banco,$la_columna,'',$la_config);	
		
		$la_dato_titulos[1]=array('cuenta'=>'Cuenta',
		                          'monto1'=>'Monto Neto Ahorro.',
								  'priquinc1'=>'1era. Quincena Ahorro',
								  'segquinc1'=>'2da. Quincena Ahorro',
								  'monto2'=>'Monto Neto Corriente.',
								  'priquinc2'=>'1era. Quincena Corriente',
								  'segquinc2'=>'2da. Quincena Corriente');
		$la_columna=array('cuenta'=>'',
		                          'monto1'=>'',
								  'priquinc1'=>'',
								  'segquinc1'=>'',
								  'monto2'=>'',
								  'priquinc2'=>'',
								  'segquinc2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas						 
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
						 			   'monto1'=>array('justification'=>'center','width'=>80),
									   'priquinc1'=>array('justification'=>'center','width'=>100),
									   'segquinc1'=>array('justification'=>'center','width'=>100),
									   'monto2'=>array('justification'=>'center','width'=>100),
									   'priquinc2'=>array('justification'=>'center','width'=>100),
									   'segquinc2'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_dato_titulos,$la_columna,'',$la_config);	
	}// uf_print_cabecera_nomina
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($as_cuenta,$as_monto1,$as_priquin1,$as_segquin1,$as_monto2,$as_priquin2,$as_segquin2,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle por banco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_dato_montos[1]=array('cuenta'=>$as_cuenta,
		                          'monto1'=>$as_monto1,
								  'priquinc1'=>$as_priquin1,
								  'segquinc1'=>$as_segquin1,
								  'monto2'=>$as_monto2,
								  'priquinc2'=>$as_priquin2,
								  'segquinc2'=>$as_segquin2);
		$la_columna=array('cuenta'=>'',
		                          'monto1'=>'',
								  'priquinc1'=>'',
								  'segquinc1'=>'',
								  'monto2'=>'',
								  'priquinc2'=>'',
								  'segquinc2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas						 
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('cuenta'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna
						 			   'monto1'=>array('justification'=>'right','width'=>80),
									   'priquinc1'=>array('justification'=>'right','width'=>100),
									   'segquinc1'=>array('justification'=>'right','width'=>100),
									   'monto2'=>array('justification'=>'right','width'=>100),
									   'priquinc2'=>array('justification'=>'right','width'=>100),
									   'segquinc2'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_dato_montos,$la_columna,'',$la_config);		
	}// end function uf_print_detalle
	//------------------------------------------------------------------------------------------------------------------
  //-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");	
	$ls_bolivares="";
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();					
    $ls_bolivares ="Bs.";
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>Depósitos al Banco</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	$ls_des_periodo=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_dhas_periodo=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_bancdes=$io_fun_nomina->uf_obtenervalor_get("codbandes","");
	$ls_banchas=$io_fun_nomina->uf_obtenervalor_get("codbanhas","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","");
	$ls_rango= "Nómina Desde: ".$ls_codnomdes." Nómina Hasta: ".$ls_codnomhas;
	$ls_periodo= "Periodo Desde: ".$ls_des_periodo." - Período Hasta: ".$ls_dhas_periodo;
	
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_rango,$ls_periodo); // Seguridad de Reporte
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_depositos_bancarios($ls_codnomdes,$ls_codnomhas,$ls_bancdes,$ls_banchas,$ls_des_periodo,$ls_dhas_periodo,$ls_orden); // Cargar el DS con los datos de la cabecera del reporte
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
		$io_pdf->ezSetCmMargins(3.55,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(750,50,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->DS_depositos->getRowCount("codnom");
		$ls_nom_aux="";
		$ls_banco_aux="";
		for ($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
		   $ls_nomina=$io_report->DS_depositos->data["codnom"][$li_i];	
		   $ls_desnom=$io_report->DS_depositos->data["desnom"][$li_i];
		   $ls_banco=$io_report->DS_depositos->data["codban"][$li_i];
		   $ls_cuenta=$io_report->DS_depositos->data["codcueban"][$li_i];
		   $ls_desban=$io_report->DS_depositos->data["nomban"][$li_i];
		   $ls_momto_neto_ahorro=$io_report->DS_depositos->data["monto_neto_ahorro"][$li_i];	
		   $ls_priquin_ahorro=$io_report->DS_depositos->data["priquinahorro"][$li_i];	
		   $ls_segquin_ahorro=$io_report->DS_depositos->data["segquinahorro"][$li_i];
		   $ls_momto_neto_corriente=$io_report->DS_depositos->data["monto_neto_corriente"][$li_i];	
		   $ls_priquin_corriente=$io_report->DS_depositos->data["priquincorriente"][$li_i];	
		   $ls_segquin_corriente=$io_report->DS_depositos->data["segquincorriente"][$li_i];	
		   if ($ls_nomina!=$ls_nom_aux)
		   {
		     $ls_nom_aux=$ls_nomina;
			 $ls_banco_aux="";
			 $io_pdf->ezSetDy(-10);
		     uf_print_cabecera_nomina($ls_nomina,$ls_desnom,&$io_pdf);
			 if ($ls_banco!=$ls_banco_aux)
			 {
			   $ls_banco_aux=$ls_banco;
			   uf_print_cabecera_banco($ls_banco,$ls_desban,&$io_pdf);			   
			 }	
			 uf_print_detalle($ls_cuenta,$io_fun_nomina->uf_formatonumerico($ls_momto_neto_ahorro),
			                             $io_fun_nomina->uf_formatonumerico($ls_priquin_ahorro),
										 $io_fun_nomina->uf_formatonumerico($ls_segquin_ahorro),
										 $io_fun_nomina->uf_formatonumerico($ls_momto_neto_corriente),
										 $io_fun_nomina->uf_formatonumerico($ls_priquin_corriente),
										 $io_fun_nomina->uf_formatonumerico($ls_segquin_corriente),&$io_pdf);	
		   }
		   else
		   {		     
			 if ($ls_banco!=$ls_banco_aux)
			 {
			   $ls_banco_aux=$ls_banco;
			   uf_print_cabecera_banco($ls_banco, $ls_desban,&$io_pdf);
			 }	
			 uf_print_detalle($ls_cuenta,$io_fun_nomina->uf_formatonumerico($ls_momto_neto_ahorro),
			                             $io_fun_nomina->uf_formatonumerico($ls_priquin_ahorro),
										 $io_fun_nomina->uf_formatonumerico($ls_segquin_ahorro),
										 $io_fun_nomina->uf_formatonumerico($ls_momto_neto_corriente),
										 $io_fun_nomina->uf_formatonumerico($ls_priquin_corriente),
										 $io_fun_nomina->uf_formatonumerico($ls_segquin_corriente),&$io_pdf);	
		   }
		   
		}///fin del for
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