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
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 26/05/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,715,11,$as_periodo); // Agregar el título		
		$io_pdf->addText(500,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(506,743,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_nomina($as_codnom, $as_desnom, &$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_nomban // Nombre del Banco
		//	    		   io_cabecera // Objeto cabecera
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera por banco
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 26/05/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $io_pdf->ezSetDy(-7);
		$la_dato_nomina[1]=array('codigo'=>"<b>".$as_codnom."</b>",'nombre'=>"<b>".$as_desnom."</b>");
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
						 'cols'=>array('codigo'=>array('justification'=>'right','width'=>30), 
						 			   'nombre'=>array('justification'=>'left','width'=>520))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_dato_nomina,$la_columna,'',$la_config);
		
		 $la_dato_titulos[1]=array('unidad'=>'<b>UNIDAD</b>',
		                          'numero'=>'<b>CANTIDAD</b>',
								  'monto'=>'<b>MONTO</b>');
		$la_columna=array('unidad'=>'',
		                  'numero'=>'',
						  'monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7.5, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas						 
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('unidad'=>array('justification'=>'center','width'=>350),
						 			   'numero'=>array('justification'=>'center','width'=>60),
									   'monto'=>array('justification'=>'center','width'=>140))); // Justificación y ancho de la columna
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
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 26/05/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('unidad'=>'',
		                  'numero'=>'',
						  'monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7.5, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas						 
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('unidad'=>array('justification'=>'left','width'=>350),
						 			   'numero'=>array('justification'=>'center','width'=>60),
									   'monto'=>array('justification'=>'right','width'=>140))); // Justificación y ancho de la 
		$io_pdf->ezTable($as_data,$la_columna,'',$la_config);		
	}// end function uf_print_detalle
	///---------------------------------------------------------------------------------------------------------------------------
	  function uf_totales_nominas($total1,$total2,$io_pdf)
	  {
	    $la_data_total[1]=array('total'=>'<b>TOTAL POR NOMINA</b>',
		                        'total1'=>$total2,
						        'total2'=>$total1);
	    $la_columna=array('total'=>'',
		                  'total1'=>'',
						  'total2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7.5, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas						 
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('total'=>array('justification'=>'center','width'=>350),
						 			   'total1'=>array('justification'=>'center','width'=>60),
									   'total2'=>array('justification'=>'right','width'=>140))); // Justificación y ancho de la 
		$io_pdf->ezTable($la_data_total,$la_columna,'',$la_config);		
	  
	  }
	
	//----------------------------------------------------------------------------------------------------------------------------
	 function uf_totales_unidad($as_data_unidad,$cantidad,$total,$io_pdf)
	  {
	    $io_pdf->ezSetDy(-30);
		$la_data_resumen[1]=array('resumen'=>'<b>RESUMEN POR UNIDAD</b>');
	    $la_columna=array('resumen'=>'');
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
						 'cols'=>array('resumen'=>array('justification'=>'center','width'=>550))); // Justificación y ancho de la 
		$io_pdf->ezTable($la_data_resumen,$la_columna,'',$la_config);	
									   
	    $la_data_total[1]=array('unidad'=>'<b>UNIDAD</b>',
		                        'total1'=>'<b>CANTIDAD</b>',
						        'total2'=>'<b>MONTO</b>');
	    $la_columna=array('unidad'=>'',
		                  'total1'=>'',
						  'total2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7.5, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas						 
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('unidad'=>array('justification'=>'center','width'=>350),
						 			   'total1'=>array('justification'=>'center','width'=>60),
									   'total2'=>array('justification'=>'center','width'=>140))); // Justificación y ancho de la 
		$io_pdf->ezTable($la_data_total,$la_columna,'',$la_config);	
		
		$la_columna=array('unidad'=>'',
		                  'total1'=>'',
						  'total2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7.5, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas						 
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('unidad'=>array('justification'=>'left','width'=>350),
						 			   'total1'=>array('justification'=>'center','width'=>60),
									   'total2'=>array('justification'=>'right','width'=>140))); // Justificación y ancho de la 
		$io_pdf->ezTable($as_data_unidad,$la_columna,'',$la_config);
		
		$la_data_totales[1]=array('unidad'=>'<b>TOTALES POR UNIDAD</b>',
		                        'total1'=>$cantidad,
						        'total2'=>$total);
	    $la_columna=array('unidad'=>'',
		                  'total1'=>'',
						  'total2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7.5, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas						 
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('unidad'=>array('justification'=>'center','width'=>350),
						 			   'total1'=>array('justification'=>'center','width'=>60),
									   'total2'=>array('justification'=>'right','width'=>140))); // Justificación y ancho de la 
		$io_pdf->ezTable($la_data_totales,$la_columna,'',$la_config);				
	  
	  }
	
	//----------------------------------------------------------------------------------------------------------------------------

     
  //-----------------------------------------------------  Instancia de las clases ------------------------------------------------
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
	$ls_titulo="<b>Resumen de Pagos por Unidad Administrativa</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	$ls_des_periodo=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_dhas_periodo=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_unidaddes=$io_fun_nomina->uf_obtenervalor_get("codunides","");
	$ls_unidadhas=$io_fun_nomina->uf_obtenervalor_get("codunihas","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","");
	$ls_rango= "Nómina Desde: ".$ls_codnomdes." Nómina Hasta: ".$ls_codnomhas;
	$ls_periodo= "Periodo Desde: ".$ls_des_periodo." - Período Hasta: ".$ls_dhas_periodo;
	
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_rango,$ls_periodo); // Seguridad de Reporte
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_seleccionar_nominaunidad($ls_codnomdes,$ls_codnomhas, $ls_des_periodo, $ls_dhas_periodo,
		                                                  $ls_orden); 
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
		$io_pdf->ezSetCmMargins(3.6,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página	
		$li_nomina=$io_report->DS->getRowCount("codnom"); ///print_r ($io_report->DS);			
		for ($li_i=1;$li_i<=$li_nomina;$li_i++)
		{  
		   $ls_codnom=$io_report->DS->data["codnom"][$li_i];
		   $ls_desnom=$io_report->DS->data["desnom"][$li_i];		  
		   uf_print_cabecera_nomina($ls_codnom, $ls_desnom, &$io_pdf);	
		   $io_report->uf_pagos_unidad($ls_codnom, $ls_des_periodo, $ls_dhas_periodo,$ls_unidaddes,$ls_unidadhas);
		   $li_unidad=$io_report->DS_detalle->getRowCount("codnom");
		   $total_monto=0;
		   $total_cantidad=0;
		   for ($li_j=1;$li_j<=$li_unidad;$li_j++)
		   {  
		      $ls_desuniadm=$io_report->DS_detalle->data["desuniadm"][$li_j];			  
			  $ls_monto=$io_report->DS_detalle->data["monnetres"][$li_j];			  
			  $total_monto=$total_monto+$ls_monto;
			  $ls_uni1=$io_report->DS_detalle->data["minorguniadm"][$li_j];	
			  $ls_uni2=$io_report->DS_detalle->data["ofiuniadm"][$li_j];	
			  $ls_uni3=$io_report->DS_detalle->data["uniuniadm"][$li_j];	
			  $ls_uni4=$io_report->DS_detalle->data["depuniadm"][$li_j];	
			  $ls_uni5=$io_report->DS_detalle->data["prouniadm"][$li_j];	
			  $io_report->uf_contar_unidad($ls_codnom,$ls_des_periodo,$ls_dhas_periodo,$ls_uni1,$ls_uni2,
			                               $ls_uni3,$ls_uni4,$ls_uni5);
			  $li_contar=$io_report->ds_componente->getRowCount("codnom");
			  $ls_cantidad=0;
			  $aux_corper="";
			  for ($li_l=1;$li_l<=$li_contar;$li_l++)
		      { 
			     $ls_persona=$io_report->ds_componente->data["codper"][$li_l];
				 if ($ls_persona!=$aux_corper) 
				 {
				   $aux_corper=$ls_persona;
				   $ls_cantidad=$ls_cantidad+1;
				 }	
			  }			  
			  $total_cantidad=$total_cantidad+$ls_cantidad;			  
			  $ls_data[$li_j]=array('unidad'=>$ls_desuniadm,'numero'=>$ls_cantidad, 
			                        'monto'=>$io_fun_nomina->uf_formatonumerico($ls_monto));		      
		   }///fin del for
		   if ($li_unidad>0)
		   {
				uf_print_detalle($ls_data,&$io_pdf);
				uf_totales_nominas($io_fun_nomina->uf_formatonumerico($total_monto),$total_cantidad,$io_pdf);	
				unset($ls_data);
				$io_report->uf_pagos_unidad_totales($ls_codnomdes,$ls_codnomhas, $ls_des_periodo,
												   $ls_dhas_periodo,$ls_unidaddes,$ls_unidadhas);						   
		   }		  
		  
		}///fin del for
		
		    $li_total_unidad=$io_report->DS_nominas->getRowCount("codnom");
			$ls_cant_t=0;
			$ls_tot_uni=0;
			for ($li_k=1;$li_k<=$li_total_unidad;$li_k++)
			   {
			     $ls_uni=$io_report->DS_nominas->data["desuniadm"][$li_k];
				 $ls_mon=$io_report->DS_nominas->data["monnetres"][$li_k];
				  $ls_uni1=$io_report->DS_nominas->data["minorguniadm"][$li_k];	
				  $ls_uni2=$io_report->DS_nominas->data["ofiuniadm"][$li_k];	
				  $ls_uni3=$io_report->DS_nominas->data["uniuniadm"][$li_k];	
				  $ls_uni4=$io_report->DS_nominas->data["depuniadm"][$li_k];	
				  $ls_uni5=$io_report->DS_nominas->data["prouniadm"][$li_k];
				   $io_report->uf_contar_unidad($ls_codnom,$ls_des_periodo,$ls_dhas_periodo,$ls_uni1,$ls_uni2,
			                               $ls_uni3,$ls_uni4,$ls_uni5);										   
				  $li_contar=$io_report->ds_componente->getRowCount("codnom");
				  $ls_cant=0;
				  $aux_corper="";
				  for ($li_l=1;$li_l<=$li_contar;$li_l++)
				  { 
					 $ls_persona=$io_report->ds_componente->data["codper"][$li_l];
					 if ($ls_persona!=$aux_corper) 
					 {
					   $aux_corper=$ls_persona;
					   $ls_cant=$ls_cant+1;
					 }	
				  }				 
				 //$ls_cant=$io_report->DS_nominas->data["cantidad"][$li_k];
				 $ls_cant_t=$ls_cant_t+$ls_cant;
			     $ls_tot_uni=$ls_tot_uni+$ls_mon;
				 $ls_data_unidad[$li_k]=array('unidad'=>$ls_uni,
											  'total2'=>$io_fun_nomina->uf_formatonumerico($ls_mon),
											  'total1'=>$ls_cant);
			   
			   }
			if ($li_total_unidad>0)
			{								   
			  uf_totales_unidad($ls_data_unidad,$ls_cant_t,
			                  $io_fun_nomina->uf_formatonumerico($ls_tot_uni),$io_pdf);	
			}
			
			if(($lb_valido)&&($li_unidad>0)&&($li_total_unidad>0)) // Si no ocurrio ningún error
				{
					$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
					$io_pdf->ezStream(); // Mostramos el reporte
				}
			else  // Si hubo algún error
				{
					print("<script language=JavaScript>");
					print(" alert('No hay nada que reportar');"); 
					print(" close();");
					print("</script>");		
				}
			unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 