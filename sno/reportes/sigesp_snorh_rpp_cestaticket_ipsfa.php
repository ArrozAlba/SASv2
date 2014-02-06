<?php
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	ini_set('memory_limit','256M');
	ini_set('max_execution_time','0');	
	error_reporting(E_ALL);
	set_time_limit(1800);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";		
		print "</script>";		
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
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
		// Fecha Creación: 07/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_cestaticket.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_titulo2,$as_desnom,$as_periodo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,755,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(9,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,570,9,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(9,$as_titulo2);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,560,9,$as_titulo2); // Agregar el título2
		$li_tm=$io_pdf->getTextWidth(9,$as_desnom);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,550,9,$as_desnom); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(9,$as_periodo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,540,9,$as_periodo); // Agregar el título
		$io_pdf->addText(712,560,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(718,553,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_coduniadm,$as_desuniadm,&$io_cabecera,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_coduniadm // Código de Unidad Administrativa
		//	   			   as_desuniadm // Nombre de Unidad Administrativa
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-10);
		$la_data=array(array('name'=>'<b>GERENCIA:</b> '.$as_coduniadm.' - '.$as_desuniadm.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 6,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						  'shadeCol' => array(1,1,0.8),
						 'shadeCol2' => array(1,1,0.8),
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>600, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						
						 'cols'=>array('name'=>array('justification'=>'left','width'=>610))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
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
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('nro'=>'<b>N°</b>',
						   'cedula'=>'<b>CÉDULA</b>',
						   'nombre'=>'<b>APELLIDOS Y NOMBRES</b>',
						   'personal'=>'<b>TIPO</b>',
						   'ticket1'=>'<b>DIA LAB</b>',
						   'diario1'=>'<b>MONTO DIARIO</b>',
						   'valor1'=>'<b>MONTO MENSUAL</b>',
						   'ticket2'=>'<b>DIA COM</b>',
						   'diario2'=>'<b>MONTO DIARIO</b>',
						   'valor2'=>'<b>MONTO MENSUAL</b>',
						   'sentencia'=>'<b>DTO. SENT.</b>',						   
						   'total'=>'<b>TOTAL</b>'
						   );
						   
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 6,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol' => array(0.9,0.9,0.9),
						 'shadeCol2' => array(1,1,1),
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'Titulo_Color'  => 'si', // Para poner color de fondo y de letra al titulo
						 'TituloCol' => array(0,0,0.5), //Color de fondo del titulo
						 'Letra_Titulo' => array(1,1,1),  //Color de letra del titulo
						 'cols'=>array('nro'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna
						 			   'personal'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'ticket1'=>array('justification'=>'center','width'=>40), // Justificación y ancho de la columna
						 			   'diario1'=>array('justification'=>'right','width'=>40), // Justificación y ancho de la columna
						 			   'valor1'=>array('justification'=>'right','width'=>40),
									   'ticket2'=>array('justification'=>'center','width'=>40), // Justificación y ancho de la columna
						 			   'diario2'=>array('justification'=>'right','width'=>40), // Justificación y ancho de la columna
						 			   'valor2'=>array('justification'=>'right','width'=>40),
						 			   'sentencia'=>array('justification'=>'center','width'=>40), // Justificación y ancho de la columna						 			   					   
									   'total'=>array('justification'=>'right','width'=>40)
									   ),
						'cabecera_cols'=>array('nro'=>array('justification'=>'center'), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center'), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center'), // Justificación y ancho de la columna
						 			   'personal'=>array('justification'=>'center'), // Justificación y ancho de la columna
						 			   'ticket1'=>array('justification'=>'center'), // Justificación y ancho de la columna
						 			   'diario1'=>array('justification'=>'center'), // Justificación y ancho de la columna
						 			   'valor1'=>array('justification'=>'center'),
									   'ticket2'=>array('justification'=>'center'), // Justificación y ancho de la columna
						 			   'diario2'=>array('justification'=>'center'), // Justificación y ancho de la columna
						 			   'valor2'=>array('justification'=>'center'),
						 			   'sentencia'=>array('justification'=>'center'), // Justificación y ancho de la columna						 			   					   
									   'total'=>array('justification'=>'center')
									   )		   
									   ); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($ai_total,$ai_personas,$as_desuniadm,$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		   Access: private 
		//	    Arguments: ai_total // Total 
		//	   			   ai_ticket // Ticket
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>   TOTAL POR UBICACIÓN: </b>'.$as_desuniadm.'  ('.$ai_personas.')  ','ticket'=>'','total'=>$ai_total));
		$la_columna=array('name'=>'','ticket'=>'','total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 6,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						  'shaded'=>2, // Sombra entre líneas
						  'shadeCol' => array(0.8,1,1),
						 'shadeCol2' => array(0.8,1,1),
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>610, // Ancho de la tabla
						 'maxWidth'=>610, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla						  
						 'cols'=>array('name'=>array('justification'=>'left','width'=>250), // Justificación y ancho de la columna
						 			   'ticket'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>250))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}
	function uf_imprime_totales($ai_total_personas,$ai_monto_total,$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_imprime_totales
		//		   Access: private 
		//	    Arguments: $ai_total_personas // Total Personas
		//	   			   $ai_monto_total// Monto Total de Cestatickets
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los totales
		//	   Creado Por: Lic. Edgar A. Quintero
		// Fecha Creación: 17/02/2009
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		global $deducciones_tot,$cesta_tot,$io_fun_nomina;
		
		$la_data=array(array('name'=>'<b>   TOTAL DE PERSONAS: </b>'.$ai_total_personas,'ticket'=>'','total'=>'<b>Asignaciones:</b> '.$io_fun_nomina->uf_formatonumerico($cesta_tot).'    <b>Deducciones:</b> '.$io_fun_nomina->uf_formatonumerico($deducciones_tot).'  <b>Total:</b> '.$ai_monto_total));
		$la_columna=array('name'=>'','ticket'=>'','total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						  'shadeCol' => array(1,1,0.8),
						 'shadeCol2' => array(1,1,0.8),
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>610, // Ancho de la tabla
						 'maxWidth'=>610, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla						  
						 'cols'=>array('name'=>array('justification'=>'left','width'=>250), // Justificación y ancho de la columna
						 			   'ticket'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>250))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf_plus/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>INSTITUTO DE PREVISION SOCIAL DE LA FUERZA ARMADA</b>";
	$ls_titulo2="<b>REPORTE DE PERSONAL PARA EL CONTROL DEL PROGRAMA DE ALIMENTACIÓN</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	$ls_codconcdes=$io_fun_nomina->uf_obtenervalor_get("codconcdes","");
	$ls_codconchas=$io_fun_nomina->uf_obtenervalor_get("codconchas","");
	$ls_ano=$io_fun_nomina->uf_obtenervalor_get("ano","");
	$ls_mes=$io_fun_nomina->uf_obtenervalor_get("mes","");
	$ls_codperi=$io_fun_nomina->uf_obtenervalor_get("codperi","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_orden="5".trim($ls_orden);
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_rango= "Nómina Desde: ".$ls_codnomdes." Nómina Hasta: ".$ls_codnomhas;
	$ls_periodo= "Año: ".$ls_ano." Mes: ".$io_fecha->uf_load_nombre_mes($ls_mes);
	$ls_tiporeporte=$io_fun_nomina->uf_obtenervalor_get("tiporeporte",0);
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("codsubnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("codsubnomhas","");
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_snorh_class_reportbsf.php");
		$io_report=new sigesp_snorh_class_reportbsf();
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_rango,$ls_periodo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_cestaticket_personal($ls_codnomdes,$ls_codnomhas,$ls_ano,$ls_mes,$ls_codperi,$ls_codconcdes,
														$ls_codconchas,$ls_conceptocero,$ls_subnomdes,$ls_subnomhas,$ls_orden,&$rs_data,$record_set='si'); // Cargar el DS con los datos del reporte
	}
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
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf_plus/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.2,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_titulo2,$ls_rango,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(750,50,10,'','',1); // Insertar el número de página		
		$li_totrow=$rs_data->RecordCount();
		$ls_coduniadmact="";
		$ls_desuniadmact="";
		$ls_desuniadm="";
		$ls_coduniadm="";
		$li_contador=0;
		$li_contador2=0;
		$li_codperact="";
	 	$contador_personas = 0;
		$cesta_tot = 0;
		$deduciones_parcial = $io_fun_nomina->uf_formatonumerico(0);
		$monto_total = $io_fun_nomina->uf_formatonumerico(0);
		$deducciones_tot = $io_fun_nomina->uf_formatonumerico(0);
		while(!$rs_data->EOF){
						
						$li_numpag=$io_pdf->ezPageCount;
						$ls_coduniadm=$rs_data->fields["minorguniadm"].$rs_data->fields["ofiuniadm"].$rs_data->fields["uniuniadm"].$rs_data->fields["depuniadm"].$rs_data->fields["prouniadm"];
						$ls_desuniadm=$rs_data->fields["desuniadm"];
						
						if($ls_coduniadm!=$ls_coduniadmact){			
							
							if($li_contador!=0){
							
									uf_print_detalle($la_data,$io_pdf);																		
									uf_print_piecabecera($io_fun_nomina->uf_formatonumerico($li_total),$li_contador,$ls_desuniadmact,$io_pdf);
									$monto_total = $monto_total + $li_total;									
									$la_data = array(); //reinicializamos el array
									
							}
							
							$li_numpag=$io_pdf->ezPageCount;
							$io_pdf->transaction('start');//COMENZAMOS UNA TRANSACCIÓN QUE PODAMOS DEVOLVER SI QUEREMOS
							
							uf_print_cabecera($ls_coduniadm,$ls_desuniadm,&$io_cabecera,&$io_pdf);
							//AGREGAMOS DOS LINEAS ADICIONALES A VER SI CAMBIA DE PÁGINA
							$io_pdf->ezText('   ',12,array('justification' => 'left'));
							$io_pdf->ezText('   ',12,array('justification' => 'full'));
							if ($io_pdf->ezPageCount==$li_numpag)
							{//SI NO CAMBIA DE PÁGINA DEVOLVEMOS, QUITAMOS LAS LINEAS E IMPRIMIMOS LA CABECERA
								$io_pdf->transaction('rewind');
								uf_print_cabecera($ls_coduniadm,$ls_desuniadm,&$io_cabecera,&$io_pdf);
								$io_pdf->transaction('commit');
							}
							else
							{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
								$io_pdf->transaction('rewind');
								$io_pdf->ezNewPage(); // Insertar una nueva página
								uf_print_cabecera($ls_coduniadmact,$ls_desuniadmact,&$io_cabecera,&$io_pdf); // Imprimimos la cabecera del registro
						    }
							$ls_coduniadmact=$ls_coduniadm;
							$ls_desuniadmact=$ls_desuniadm;
						    $li_contador=0;
							$li_total=0;
							
													
						}
						
						$ls_cedper=$rs_data->fields["cedper"];
						$ls_nomper=$rs_data->fields["apeper"].", ".$rs_data->fields["nomper"];
						$ls_desnom=$rs_data->fields["tipopersonal"];
						$ls_ubicacion=$rs_data->fields["dendep"];
						$ls_codper=$rs_data->fields["codper"];
						$ls_cod_concepto = $rs_data->fields["codconc"];
						
						
						//$li_total=$li_total+$rs_data->fields["valsal"];
						$ls_tipcom=$rs_data->fields["tipsal"];
						
						if ($ls_cod_concepto=='0000000093')
						{
						  $li_valsal2=abs($rs_data->fields["valsal"]);
						  $li_ticket2=number_format(abs($rs_data->fields["valsal"])/abs($rs_data->fields["mondesdia"]),0);
						  $li_moncestic2=$io_fun_nomina->uf_formatonumerico($rs_data->fields["mondesdia"]);
						  $deducciones_tot = $deducciones_tot + $li_valsal2;
						}
						else if($ls_cod_concepto=='0000000094'){
						
								$li_valsal_sentencia = $io_fun_nomina->uf_formatonumerico(abs($rs_data->fields["valsal"]));
								$deducciones_tot = $deducciones_tot + $li_valsal_sentencia;
						
						}
						else if($ls_cod_concepto=='0000000090')
						{
						  $li_valsal=$rs_data->fields["valsal"];
						  $li_ticket=number_format(abs($rs_data->fields["valsal"])/abs($rs_data->fields["moncestic"]),0);
						  $li_moncestic=$io_fun_nomina->uf_formatonumerico($rs_data->fields["moncestic"]);
						   $cesta_tot = $cesta_tot + $li_valsal;
						}
						
						
						//echo $ls_nomper.' --> '.$ls_desuniadm.'<br>';
						$li_contador2++;
						$rs_data->MoveNext();
						//echo $ls_codper.' '.$rs_data->fields["codper"].'<br>';
						
						if($ls_codper!=$rs_data->fields["codper"]){
													
								
								
								if(!isset($li_ticket)){$li_ticket=0;}
								if(!isset($li_moncestic)){$li_moncestic=0;}
								if(!isset($li_valsal)){$li_valsal=0;}
								if(!isset($li_ticket2)){$li_ticket2=0;}
								if(!isset($li_moncestic2)){$li_moncestic2=0;}
								if(!isset($li_valsal2)){$li_valsal2=0;}
								if(!isset($li_valsal_sentencia)){$li_valsal_sentencia=0;}
								
								$li_contador++;
								
								$la_data[$li_contador]=array('nro'=>$li_contador,
															 'cedula'=>$ls_cedper,
															 'nombre'=>$ls_nomper,
															 'personal'=>$ls_desnom,
															 'ticket1'=>$li_ticket,
															 'diario1'=>$li_moncestic,
															 'valor1'=>$li_valsal,
															 'ticket2'=>$li_ticket2,
															 'diario2'=>$li_moncestic2,
															 'valor2'=>$li_valsal2,
															 'sentencia'=>$li_valsal_sentencia,													
															 'total'=>$io_fun_nomina->uf_formatonumerico($li_valsal-($li_valsal2+$io_fun_nomina->uf_formatonumerico($li_valsal_sentencia)))
															 );
															 															
							    $li_total=$li_total + ($li_valsal-($li_valsal2+$io_fun_nomina->uf_formatonumerico($li_valsal_sentencia)));
								
								$ls_codper = $li_codperact;
								$contador_personas++;
								
								//LIMPIAMOS LAS VARIABLES
								$li_ticket=0;
								$li_moncestic=0;
								$li_valsal=0;
								$li_ticket2=0;
								$li_moncestic2=0;
								$li_valsal2=0;
								$li_valsal_sentencia=0;


						}
						
						
						
				
		
		}
			
		
		 // Imprimimos el detalle 
		uf_print_detalle($la_data,$io_pdf);		
		uf_print_piecabecera($io_fun_nomina->uf_formatonumerico($li_total),$li_contador,$ls_desuniadmact,$io_pdf);
		$monto_total = $monto_total + $li_total;		
		$ultima_linea = $io_pdf->y;
		$io_pdf->filledRectangle(96,$ultima_linea-10,600,1); // Imprimimos el fin del reporte
		$io_pdf->ezText('   ',12,array('justification' => 'full'));
		uf_imprime_totales($contador_personas,$io_fun_nomina->uf_formatonumerico($cesta_tot-$deducciones_tot) ,$io_pdf);
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
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 
