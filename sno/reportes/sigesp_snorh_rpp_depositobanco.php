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
	function uf_print_cabecera_nomina($as_codnom, $as_desnom, $as_banco, $as_nomban, $as_cuenta, &$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_nomban // Nombre del Banco
		//	    		   io_cabecera // Objeto cabecera
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera por banco
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 22/05/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $io_pdf->ezSetDy(-7);
		$la_dato_nomina[1]=array('codigo'=>"<b>".$as_codnom."</b>",'nombre'=>"<b>".$as_desnom."</b>",
		                         'codban'=>"<b>".$as_banco."</b>",
		                         'nomban'=>"<b>".$as_nomban."</b>",'cuenta'=>'<b>CTA: </b>'."<b>".$as_cuenta."</b>");
		$la_columna=array('codigo'=>'','nombre'=>'',
		                  'codban'=>'',
		                  'nomban'=>'','cuenta'=>'');
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
						 'cols'=>array('codigo'=>array('justification'=>'right','width'=>30), 
						 			   'nombre'=>array('justification'=>'left','width'=>220),
									   'codban'=>array('justification'=>'right','width'=>50),
									   'nomban'=>array('justification'=>'left','width'=>100),
									   'cuenta'=>array('justification'=>'left','width'=>200))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_dato_nomina,$la_columna,'',$la_config);
		
		 $la_dato_titulos[1]=array('monto mensual'=>'<b>DEP-Mensual</b>',
		                          'monto1'=>'<b>DEP. 1RA.-Q</b>',
								  'monto2'=>'<b>DEP. 2DA.-Q</b>',
								  'cheque1'=>'<b>1RA. Q. Cheque</b>',
								  'cheque2'=>'<b>2DA. Q. Cheque</b>',
								  'priquinc2'=>'<b>1RA. Q. Corriente</b>',
								  'segquinc2'=>'<b>2DA. Q. Corriente</b>',
								  'priquinc1'=>'<b>1RA. Q. Ahorro</b>',
								  'segquinc1'=>'<b>2DA. Q. Ahorro</b>');
		$la_columna=array('monto mensual'=>'',
		                          'monto1'=>'',
								  'monto2'=>'',
								  'cheque1'=>'',
								  'cheque2'=>'',								  								  
								  'priquinc2'=>'',
								  'segquinc2'=>'',
								  'priquinc1'=>'',
								  'segquinc1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7.5, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas						 
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('monto mensual'=>array('justification'=>'center','width'=>65),
						 			   'monto1'=>array('justification'=>'center','width'=>65),
									   'monto2'=>array('justification'=>'center','width'=>65),
									   'cheque1'=>array('justification'=>'center','width'=>69),
									   'cheque2'=>array('justification'=>'center','width'=>69),
									   'priquinc2'=>array('justification'=>'center','width'=>75),
									   'segquinc2'=>array('justification'=>'center','width'=>75),								   
									   'priquinc1'=>array('justification'=>'center','width'=>65),
									   'segquinc1'=>array('justification'=>'center','width'=>65))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_dato_titulos,$la_columna,'',$la_config);		
	}// uf_print_cabecera_nomina
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($as_data,&$io_pdf)
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
		$la_columna=array(	  'monto mensual'=>'',
		                          'monto1'=>'',
								  'monto2'=>'',
								  'cheque1'=>'0.00',
								  'cheque2'=>'0.00',
								  'priquinc2'=>'',
								  'segquinc2'=>'',								  
								  'priquinc1'=>'',
								  'segquinc1'=>'');
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
						 'cols'=>array('monto mensual'=>array('justification'=>'center','width'=>65),
						 			   'monto1'=>array('justification'=>'center','width'=>65),
									   'monto2'=>array('justification'=>'center','width'=>65),
									   'cheque1'=>array('justification'=>'center','width'=>69),
									   'cheque2'=>array('justification'=>'center','width'=>69),
									   'priquinc2'=>array('justification'=>'center','width'=>65),
									   'segquinc2'=>array('justification'=>'center','width'=>65),								   
									   'priquinc1'=>array('justification'=>'center','width'=>75),
									   'segquinc1'=>array('justification'=>'center','width'=>75))); // Justificación y ancho de la columna
		$io_pdf->ezTable($as_data,$la_columna,'',$la_config);		
	}// end function uf_print_detalle

//-------------------------------------------------------------------------------------------------------------------------------
     function uf_total_mes_nomina($as_nom, $as_total_mes, &$io_pdf)
	 {
	    $io_pdf->ezSetDy(-5);
	    $la_dato_tit_nom[1]=array('$as_nom'=>'<b>TOTAL </b>'."<b>".$as_nom."</b>");
		$la_dato_tit_nom[2]=array('$as_nom'=>'      '.$as_total_mes);
		$la_dato_tit_nom[3]=array('$as_nom'=>'-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------');
		$la_columna=array('$as_nom'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.95,0.95,0.95), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('$as_nom'=>array('justification'=>'left','width'=>610))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_dato_tit_nom,$la_columna,'',$la_config);		
	 }
//-------------------------------------------------------------------------------------------------------------------------------
     function uf_total_banco($as_banco, $monto_mes_ban, $priquin, $segquin, $as_priquiC,$as_segquiC,$as_priquiA,$as_segquiA,&$io_pdf)
	 {
	    $la_dato_titban[1]=array('total'=>'<b>TOTAL DEPOSITOS EN BANCO   </b>'."<b>".$as_banco."</b>");
		$la_columna=array('total'=>'');
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
						 'cols'=>array('total'=>array('justification'=>'left','width'=>600))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_dato_titban,$la_columna,'',$la_config);
		
			    
	    $la_dato_banco[1]=array('monto mensual'=>'<b>DEP-Mensual</b>',
		                          'monto1'=>'<b>DEP. 1RA.-Q</b>',
								  'monto2'=>'<b>DEP. 2DA.-Q</b>',
								  'cheque1'=>'<b>1RA. Q. Cheque</b>',
								  'cheque2'=>'<b>2DA. Q. Cheque</b>',
								  'priquinc2'=>'<b>1RA. Q. Corriente</b>',
								  'segquinc2'=>'<b>2DA. Q. Corriente</b>',
								  'priquinc1'=>'<b>1RA. Q. Ahorro</b>',
								  'segquinc1'=>'<b>2DA. Q. Ahorro</b>');
		$la_dato_banco[2]=array('monto mensual'=>$monto_mes_ban,
		                          'monto1'=>$priquin,
								  'monto2'=>$segquin,
								  'cheque1'=>'0.00',
								  'cheque2'=>'0.00',
								  'priquinc2'=>$as_priquiC,
								  'segquinc2'=>$as_segquiC,
								  'priquinc1'=>$as_priquiA,
								  'segquinc1'=>$as_segquiA);	
		$la_columna=array('monto mensual'=>'',
		                          'monto1'=>'',
								  'monto2'=>'',
								  'cheque1'=>'',
								  'cheque2'=>'',								  								  
								  'priquinc2'=>'',
								  'segquinc2'=>'',
								  'priquinc1'=>'',
								  'segquinc1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7.5, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas						 
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('monto mensual'=>array('justification'=>'center','width'=>65),
						 			   'monto1'=>array('justification'=>'center','width'=>65),
									   'monto2'=>array('justification'=>'center','width'=>65),
									   'cheque1'=>array('justification'=>'center','width'=>69),
									   'cheque2'=>array('justification'=>'center','width'=>69),
									   'priquinc2'=>array('justification'=>'center','width'=>75),
									   'segquinc2'=>array('justification'=>'center','width'=>75),								   
									   'priquinc1'=>array('justification'=>'center','width'=>65),
									   'segquinc1'=>array('justification'=>'center','width'=>65))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_dato_banco,$la_columna,'',$la_config);	
		
		$la_dato_linea[1]=array('linea'=>'=================================================================================================================================');
		$la_columna=array('linea'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('linea'=>array('justification'=>'left','width'=>620))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_dato_linea,$la_columna,'',$la_config);		
	 }
//-------------------------------------------------------------------------------------------------------------------------------
     function uf_total_nomina($as_total, &$io_pdf)
	 {
	    $la_dato_total_nomina[1]=array('total nomina'=>'<b>TOTAL NOMINA INSTITUTO:</b>');
		$la_dato_total_nomina[2]=array('total nomina'=>'      '.$as_total);	
		$la_columna=array('total nomina'=>'');
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
						 'cols'=>array('total nomina'=>array('justification'=>'left','width'=>620))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_dato_total_nomina,$la_columna,'',$la_config);	
		
		$la_dato_linea[1]=array('linea'=>'=================================================================================================================================');
		$la_columna=array('linea'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('linea'=>array('justification'=>'left','width'=>620))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_dato_linea,$la_columna,'',$la_config);	
	 }
//-------------------------------------------------------------------------------------------------------------------------------
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
	$ls_titulo="<b>Relación de Depósitos al Banco</b>";
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
		$lb_valido=$io_report->uf_seleccionar_nominabanco($ls_codnomdes,$ls_codnomhas, $ls_des_periodo, $ls_dhas_periodo,
		                                                  $ls_bancdes,$ls_banchas, $ls_orden); 
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
		$li_totrow=$io_report->DS_nominas->getRowCount("codnom");
		$ls_nomina_aux= $ls_nomina=$io_report->DS_nominas->data["codnom"][1];	;
		$ls_total_mes=0;
		for ($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
		   $ls_nomina=$io_report->DS_nominas->data["codnom"][$li_i];	
		   $ls_desnom=$io_report->DS_nominas->data["desnom"][$li_i];
		   $ls_banco=$io_report->DS_nominas->data["codban"][$li_i];
		   $ls_cuenta=$io_report->DS_nominas->data["codcueban"][$li_i];
		   $ls_desban=$io_report->DS_nominas->data["nomban"][$li_i];		   
		   $lb_valido=$io_report->uf_depositos_bancarios($ls_nomina,$ls_banco, $ls_des_periodo, $ls_dhas_periodo,$ls_cuenta);
		   $li_depositos=$io_report->DS_depositos->getRowCount("codnom");
		   $ls_monto_mes=0;	
		   if ($ls_nomina_aux!=$ls_nomina)
		     {
				$ls_nomina_aux=$ls_nomina;
				uf_total_mes_nomina($io_report->DS_nominas->data["desnom"][$li_i-1],
				                    $io_fun_nomina->uf_formatonumerico($ls_total_mes), &$io_pdf);		   
				$ls_total_mes=0;
			 } 		    
		        for ($li_j=1;$li_j<=$li_depositos;$li_j++)
				{				    
				    $ls_momto_neto_ahorro=$io_report->DS_depositos->data["monnetresahorro"][$li_j];	
				    $ls_priquin_ahorro=$io_report->DS_depositos->data["priquiresahorro"][$li_j];	
				    $ls_segquin_ahorro=$io_report->DS_depositos->data["segquiresahorro"][$li_j];
				    $ls_momto_neto_corriente=$io_report->DS_depositos->data["monnetrescorriente"][$li_j];	
				    $ls_priquin_corriente=$io_report->DS_depositos->data["priquirescorriente"][$li_j];	
				    $ls_segquin_corriente=$io_report->DS_depositos->data["segquirescorriente"][$li_j];
					$ls_monto_mes=$ls_momto_neto_ahorro+$ls_momto_neto_corriente;
					$ls_monto_priqui=$ls_priquin_ahorro+$ls_priquin_corriente;
					$ls_monto_segqui=$ls_segquin_ahorro+$ls_segquin_corriente;
					$ls_total_mes=$ls_total_mes+$ls_monto_mes;
					$ls_data[$li_j]=array('monto mensual'=>$io_fun_nomina->uf_formatonumerico($ls_monto_mes),
		                          'monto1'=>$io_fun_nomina->uf_formatonumerico($ls_monto_priqui),
								  'monto2'=>$io_fun_nomina->uf_formatonumerico($ls_monto_segqui),
								  'cheque1'=>'0.00',
								  'cheque2'=>'0.00',
								  'priquinc1'=>$io_fun_nomina->uf_formatonumerico($ls_priquin_ahorro),
								  'segquinc1'=>$io_fun_nomina->uf_formatonumerico($ls_segquin_ahorro),								  
								  'priquinc2'=>$io_fun_nomina->uf_formatonumerico($ls_priquin_corriente),
								  'segquinc2'=>$io_fun_nomina->uf_formatonumerico($ls_segquin_corriente));
				}
				uf_print_cabecera_nomina($ls_nomina, $ls_desnom, $ls_banco, $ls_desban, $ls_cuenta, &$io_pdf);
		        uf_print_detalle($ls_data,&$io_pdf);				 				
		}	
		uf_total_mes_nomina($ls_desnom, $io_fun_nomina->uf_formatonumerico($ls_total_mes), &$io_pdf);
		
		$lb_valido=$io_report->uf_total_depositos_bancarios($ls_codnomdes,$ls_codnomhas,$ls_bancdes,
		                                                    $ls_banchas,$ls_des_periodo,$ls_dhas_periodo); 
			if($lb_valido)
			{
			  $li_bancos=$io_report->DS_depositos2->getRowCount("codban");
			  $io_pdf->ezSetDy(-5);
			  $total_nomina=0;
			  for ($li=1;$li<=$li_bancos;$li++)
				{   
				    $monto_mes_ban=0;
					$priquin=0;
					$segquin=0;
				    $ls_priquinA=$io_report->DS_depositos2->data["priquiresahorro"][$li];	
				    $ls_segquinA=$io_report->DS_depositos2->data["segquiresahorro"][$li];				   	
				    $ls_priquinC=$io_report->DS_depositos2->data["priquirescorriente"][$li];	
				    $ls_segquinC=$io_report->DS_depositos2->data["segquirescorriente"][$li];
					$ls_codban=$io_report->DS_depositos2->data["codban"][$li];
					$ls_momto_netoA=$io_report->DS_depositos2->data["monnetresahorro"][$li];
					$ls_momto_netoC=$io_report->DS_depositos2->data["monnetrescorriente"][$li];	
					$monto_mes_ban=$ls_momto_netoA+	$ls_momto_netoC;
					$priquin=$ls_priquinA+$ls_priquinC;
					$segquin=$ls_segquinA+$ls_segquinC;
					$total_nomina=$total_nomina+$monto_mes_ban;
			        uf_total_banco($ls_codban, $io_fun_nomina->uf_formatonumerico($monto_mes_ban), 
					                           $io_fun_nomina->uf_formatonumerico($priquin),
											   $io_fun_nomina->uf_formatonumerico($segquin),
											   $io_fun_nomina->uf_formatonumerico($ls_priquinC),
											   $io_fun_nomina->uf_formatonumerico($ls_segquinC),
											   $io_fun_nomina->uf_formatonumerico($ls_priquinA),
											   $io_fun_nomina->uf_formatonumerico($ls_segquinA),&$io_pdf);
				}			
			}
			uf_total_nomina($io_fun_nomina->uf_formatonumerico($total_nomina), &$io_pdf);	
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