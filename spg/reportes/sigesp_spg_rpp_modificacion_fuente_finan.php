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
	ini_set('memory_limit','1024M');
	ini_set('max_execution_time','0');
//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_titulo2,$as_denmoneda,&$io_pdf)
	{
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_encabezadopagina
	//		    Acess: private 
	//	    Arguments: as_titulo // Título del Reporte
	//	    		   as_periodo_comp // Descripción del periodo del comprobante
	//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
	//	    		   io_pdf // Instancia de objeto pdf
	//    Description: función que imprime los encabezados por página
	//	   Creado Por: Ing. Yozelin Barragán
	// Fecha Creación: 10/05/2006 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$io_encabezado=$io_pdf->openObject();
	$io_pdf->saveState();
	$io_pdf->line(10,30,1000,30);
	$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,533,$_SESSION["ls_width"],$_SESSION["ls_height"]); 
	//-----------------------------------------------------------------------------
	$io_pdf->addText(50,528,8,"<b>Dirección de Presupuesto y Organización</b>"); // Agregar el título
	$io_pdf->addText(50,520,8,"<b>División de Formulación Presupuestaria</b>"); // Agregar el título
	//-----------------------------------------------------------------------------
	$li_tm=$io_pdf->getTextWidth(16,$as_titulo);
	$tm=505-($li_tm/2);
	$io_pdf->addText($tm,520,14,$as_titulo); // Agregar el título
	$tm=300-($li_tm/2);
	$io_pdf->addText($tm,500,14,$as_titulo2."<b> En ".$as_denmoneda.'</b>'); // Agregar el título
	
	$io_pdf->addText(900,550,10,date("d/m/Y")); // Agregar la Fecha
	$io_pdf->addText(900,540,10,date("h:i a")); // Agregar la hora
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');
	
	}// end function uf_print_encabezadopagina
//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data, &$io_pdf)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_detalle
	//		    Acess: private 
	//	    Arguments: la_data // arreglo de información
	//	   			   io_pdf // Objeto PDF
	//    Description: función que imprime el detalle
	//	   Creado Por: Ing. Jennifer Rivero
	// Fecha Creación: 12/11/2008 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		$io_pdf->ezSetDy(-5);
		$ls_data_tt[1]=array('titulo'=>'<b>MODIFICACIONES PRESUPUESTARIAS</b>'); 
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>685, // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>480))); 		
		$io_pdf->ezTable($ls_data_tt,'','',$la_config);
		
		$ls_data_t[1]=array('ingresos'=>'<b>Ingresos Ordinarios</b>',    								   
							'otros'=>'<b> Otros</b>'); 
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>685, // Orientación de la tabla
						 'cols'=>array('ingresos'=>array('justification'=>'center','width'=>240),
									   'otros'=>array('justification'=>'center','width'=>240))); 		
		$io_pdf->ezTable($ls_data_t,'','',$la_config);
		
		$ls_data_tit[1]=array('traspaso'=>'<b>Traspaso</b>',    								   
							  'adicionales'=>'<b>R. Adicionales</b>',
							  'vacio'=>'',
							  'adicionales2'=>'<b>R. Adicionales</b>', 
							  'trapaso2'=>'<b>Traspaso</b>'); 
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>685, // Orientación de la tabla
						 'cols'=>array('traspaso'=>array('justification'=>'center','width'=>120),
									   'adicionales'=>array('justification'=>'center','width'=>60), 
									   'vacio'=>array('justification'=>'center','width'=>60), 
									   'adicionales2'=>array('justification'=>'center','width'=>60),
									   'trapaso2'=>array('justification'=>'center','width'=>180))); 		
		$io_pdf->ezTable($ls_data_tit,'','',$la_config);
		
		
		$ls_data_titulo[1]=array('cuenta'=>'<b>Partidas</b>',
								 'denomincacion'=>'<b>Denominación</b>', 
								 'asignacion'=>'<b>Prespuesto Ley (1)</b>',
								 'cedente1'=>'<b>Cedentre (2)</b>',    								   
								 'traspaso1'=>'<b>Receptoras (3)</b>',
								 'incremento1'=>'<b>Incrementos (4)</b>', 
								 'total1'=>'<b>Sub-Total Ingresos Ord. (5)=(-2+3+4)</b>', 
								 'incremento2'=>'<b>Incrementos (6)</b>',
								 'cedente2'=>'<b>Cedentes (7)</b>', 
								 'traspaso2'=>'<b>Receptoras (8)</b>',
								 'total2'=>'<b>Sub-Total Otros. (9)=(6-7+8)</b>',
								 'total'=>'<b>Presupuesto Modificacdo. (10)=(1+5+9)</b>'); 
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>505, // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>60),
									   'denomincacion'=>array('justification'=>'center','width'=>300), 
									   'asignacion'=>array('justification'=>'center','width'=>60),
									   'cedente1'=>array('justification'=>'center','width'=>60),   								   
									   'traspaso1'=>array('justification'=>'center','width'=>60),
									   'incremento1'=>array('justification'=>'center','width'=>60), 
									   'total1'=>array('justification'=>'center','width'=>60), 
									   'incremento2'=>array('justification'=>'center','width'=>60),
									   'cedente2'=>array('justification'=>'center','width'=>60), 
									   'traspaso2'=>array('justification'=>'center','width'=>60),
									   'total2'=>array('justification'=>'center','width'=>60), 
									   'total'=>array('justification'=>'center','width'=>60))); 		
		$io_pdf->ezTable($ls_data_titulo,'','',$la_config);
		
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>505, // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>60),
									   'denomincacion'=>array('justification'=>'left','width'=>300), 
									   'asignacion'=>array('justification'=>'right','width'=>60),
									   'cedente1'=>array('justification'=>'right','width'=>60),    								   
									   'traspaso1'=>array('justification'=>'right','width'=>60),
									   'incremento1'=>array('justification'=>'right','width'=>60),
									   'total1'=>array('justification'=>'right','width'=>60), 
									   'incremento2'=>array('justification'=>'right','width'=>60),
									   'cedente2'=>array('justification'=>'right','width'=>60), 
									   'traspaso2'=>array('justification'=>'right','width'=>60),
									   'total2'=>array('justification'=>'right','width'=>60), 
									   'total'=>array('justification'=>'right','width'=>60)));  
		
		
		$io_pdf->ezTable($la_data,'','',$la_config);
		unset($la_data);
		unset($la_config);
	}// end function uf_print_detalle
//---------------------------------------------------------------------------------------------------------------------------------------
    function uf_print_totales($as_tot1,$as_tot2,$as_tot3,$as_tot4,$as_tot5,$as_tot6,$as_tot7,$as_tot8,$as_tot9,$as_tot10,&$io_pdf)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_totales
	//		    Acess: private 
	//	    Arguments: la_data // arreglo de información
	//	   			   io_pdf // Objeto PDF
	//    Description: función que imprime el detalle
	//	   Creado Por: Ing. Jennifer Rivero
	// Fecha Creación: 12/11/2008 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		$la_data[1]=array('titulo'=>'<b>Total</b>',
						  'asignacion'=>$as_tot1,
						  'cedente1'=>$as_tot2,    								   
						  'traspaso1'=>$as_tot3,
						  'incremento1'=>$as_tot4, 
						  'total1'=>$as_tot5, 
						  'incremento2'=>$as_tot6,
						  'cedente2'=>$as_tot7, 
						  'traspaso2'=>$as_tot8,
						  'total2'=>$as_tot9,
						  'total'=>$as_tot10); 	
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>505, // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>360),									  
									   'asignacion'=>array('justification'=>'right','width'=>60),
									   'cedente1'=>array('justification'=>'right','width'=>60),    								   
									   'traspaso1'=>array('justification'=>'right','width'=>60),
									   'incremento1'=>array('justification'=>'right','width'=>60),
									   'total1'=>array('justification'=>'right','width'=>60), 
									   'incremento2'=>array('justification'=>'right','width'=>60),
									   'cedente2'=>array('justification'=>'right','width'=>60), 
									   'traspaso2'=>array('justification'=>'right','width'=>60),
									   'total2'=>array('justification'=>'right','width'=>60), 
									   'total'=>array('justification'=>'right','width'=>60)));  
		
		
		$io_pdf->ezTable($la_data,'','',$la_config);
		unset($la_data);
		unset($la_config);
	}// end function uf_print_detalle
//----------------------------------------------------------------------------------------------------------------------------------------
    function uf_print_cabecera( $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
		                        $ls_denestpro1,$ls_denestpro2,$ls_denestpro3,$ls_denestpro4,$ls_denestpro5,
								$ls_unidad,$ls_denunidad, $io_pdf)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_cabecera
	//		   Access: private 
	//	    Arguments: as_programatica // programatica del comprobante
	//	    		   as_denestpro5 // denominacion de la programatica del comprobante
	//	    		   io_pdf // Objeto PDF
	//    Description: función que imprime la cabecera de cada página
	//	   Creado Por: Ing.Yozelin Barragán
	// Fecha Creación: 21/04/2006 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_estmodest  = $_SESSION["la_empresa"]["estmodest"];
		$li_nomestpro1 = $_SESSION["la_empresa"]["nomestpro1"];
		$li_nomestpro2 = $_SESSION["la_empresa"]["nomestpro2"];
		$li_nomestpro3 = $_SESSION["la_empresa"]["nomestpro3"];
		$li_nomestpro4 = $_SESSION["la_empresa"]["nomestpro4"];
		$li_nomestpro5 = $_SESSION["la_empresa"]["nomestpro5"];
		$li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
	    $li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
	    $li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
	    $li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
	    $li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
		
		$ls_codestpro1    = trim(substr($ls_codestpro1,-$li_loncodestpro1));
		$ls_codestpro2    = trim(substr($ls_codestpro2,-$li_loncodestpro2));
		$ls_codestpro3    = trim(substr($ls_codestpro3,-$li_loncodestpro3));
		$ls_codestpro4    = trim(substr($ls_codestpro4,-$li_loncodestpro4));
		$ls_codestpro5    = trim(substr($ls_codestpro5,-$li_loncodestpro5));
		
		if ($ls_estmodest==1)
		{
			$ls_datat1[1]=array('nombre'=>'<b>'.$li_nomestpro1.":</b> ",'codestpro'=>$ls_codestpro1,'denom'=>$ls_denestpro1);
			$ls_datat1[2]=array('nombre'=>'<b>'.$li_nomestpro2.":</b> ",'codestpro'=>$ls_codestpro2,'denom'=>$ls_denestpro2);
			$ls_datat1[3]=array('nombre'=>'<b>'.$li_nomestpro3.":</b> ",'codestpro'=>$ls_codestpro3,'denom'=>$ls_denestpro3);
			$ls_datat1[4]=array('nombre'=>'<b>Código Interno:</b>','codestpro'=>$ls_unidad,'denom'=>$ls_denunidad);
			
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'fontSize' => 7, // Tamaño de Letras
							 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
							 'showLines'=>1, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'colGap'=>1, // separacion entre tablas
							 'width'=>990, // Ancho de la tabla
							 'maxWidth'=>990, // Ancho Máximo de la tabla
							 'xOrientation'=>'center', // Orientación de la tabla
							 'xPos'=>505, // Orientación de la tabla
							 'cols'=>array('nombre'=>array('justification'=>'left','width'=>150),									  
										   'codestpro'=>array('justification'=>'right','width'=>50),
										   'denom'=>array('justification'=>'left','width'=>760)));		
			$io_pdf->ezTable($ls_datat1,'','',$la_config);
		}
		else
		{
			$ls_datat1[1]=array('nombre'=>'<b>'.$li_nomestpro1.":</b> ",'codestpro'=>$ls_codestpro1,'denom'=>$ls_denestpro1);
			$ls_datat1[2]=array('nombre'=>'<b>'.$li_nomestpro2.":</b> ",'codestpro'=>$ls_codestpro2,'denom'=>$ls_denestpro2);
			$ls_datat1[3]=array('nombre'=>'<b>'.$li_nomestpro3.":</b> ",'codestpro'=>$ls_codestpro3,'denom'=>$ls_denestpro3);
			$ls_datat1[4]=array('nombre'=>'<b>'.$li_nomestpro4.":</b> ",'codestpro'=>$ls_codestpro4,'denom'=>$ls_denestpro4);
			$ls_datat1[5]=array('nombre'=>'<b>'.$li_nomestpro5.":</b> ",'codestpro'=>$ls_codestpro5,'denom'=>$ls_denestpro5);
			$ls_datat1[6]=array('nombre'=>'<b>Código Interno:</b>','codestpro'=>$ls_unidad,'denom'=>$ls_denunidad);
			
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>505, // Orientación de la tabla
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>150),									  
									   'codestpro'=>array('justification'=>'right','width'=>50),
									   'denom'=>array('justification'=>'left','width'=>760)));		
		   $io_pdf->ezTable($ls_datat1,'','',$la_config);	
		}
		unset($ls_datat1);
		unset($la_config);			
	}// end function uf_print_cabecera
//----------------------------------------------------------------------------------------------------------------------------------------
	
//--------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("sigesp_spg_reporte.php");
		$io_report = new sigesp_spg_reporte();
		require_once("sigesp_spg_funciones_reportes.php");
		$io_function_report = new sigesp_spg_funciones_reportes();
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();			
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
//--------------------------------------------------  Parámetros para Filtar el Reporte  ---------------------------------------
		
		
		$ldt_periodo		= $_SESSION["la_empresa"]["periodo"];
		$li_ano				= substr($ldt_periodo,0,4);
		$li_estmodest		= $_SESSION["la_empresa"]["estmodest"];
		$ls_codestpro1_min  = $_GET["codestpro1"];
		$ls_codestpro2_min  = $_GET["codestpro2"];
		$ls_codestpro3_min  = $_GET["codestpro3"];
		$ls_codestpro1h_max = $_GET["codestpro1h"];
		$ls_codestpro2h_max = $_GET["codestpro2h"];
		$ls_codestpro3h_max = $_GET["codestpro3h"];
	    $ls_estclades       = $_GET["estclades"];
	    $ls_estclahas       = $_GET["estclahas"];
		$ls_unidad          = $_GET["unidad"];
		$ls_codfuefindes    = $_GET["txtcodfuefindes"];
		$ls_codfuefinhas    = $_GET["txtcodfuefinhas"];
		$ls_denunidad       = $_GET["denunidad"];
		$ls_codmoneda       = $_GET["codmoneda"];
		$ls_denmoneda       = $_GET["denmoneda"];
		$ls_cuentades       = $_GET["txtcuentades"];
		$ls_cuentahas       = $_GET["txtcuentahas"];     
		$ls_tipoformato=1;
		
//---------------------------------------------------------------------------------------------------------------------------
		require_once("sigesp_spg_reporte.php");
		$io_report = new sigesp_spg_reporte();		 	
		require_once("../../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
		$io_rcbsf= new sigesp_c_reconvertir_monedabsf();
		$li_candeccon = $_SESSION["la_empresa"]["candeccon"];
		$li_tipconmon = $_SESSION["la_empresa"]["tipconmon"];
		$li_redconmon = $_SESSION["la_empresa"]["redconmon"];
//----------------------------------------------------------------------------------------------------------------------------    
		if($li_estmodest==1)
		{
			$ls_codestpro4_min =  "0000000000000000000000000";
			$ls_codestpro5_min =  "0000000000000000000000000";
			$ls_codestpro4h_max = "0000000000000000000000000";
			$ls_codestpro5h_max = "0000000000000000000000000";
			if(($ls_codestpro1_min=="")&&($ls_codestpro2_min=="")&&($ls_codestpro3_min==""))
			{
			  if($io_function_report->uf_spg_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,
			                                                                 $ls_codestpro3_min,$ls_codestpro4_min,
			                                                                 $ls_codestpro5_min,$ls_estclades))
			  {
					$ls_codestpro1  = trim($ls_codestpro1_min);
					$ls_codestpro2  = trim($ls_codestpro2_min);
					$ls_codestpro3  = trim($ls_codestpro3_min);
					$ls_codestpro4  = trim($ls_codestpro4_min);
					$ls_codestpro5  = trim($ls_codestpro5_min);
			  }
			}
			else
			{
			 if($ls_codestpro1_min<>"")
		     {	
			  $ls_codestpro1_min=$io_funciones->uf_cerosizquierda($ls_codestpro1_min,25);
			  $ls_codestpro1= $ls_codestpro1_min;	
			 }
		     else
		     {	
			  $io_function_report->uf_spg_reporte_select_min_codestpro1(&$ls_codestpro1_min,&$ls_estclades);
			  $ls_codestpro1=$ls_codestpro1_min;
		     }
			 
			 if($ls_codestpro2_min<>"")
		     {	
			  $ls_codestpro2_min=$io_funciones->uf_cerosizquierda($ls_codestpro2_min,25);
			  $ls_codestpro2= $ls_codestpro2_min;	
			 }
		     else
		     {	
			  $io_function_report->uf_spg_reporte_select_min_codestpro2($ls_codestpro1_min,&$ls_codestpro2_min,$ls_estclades);
			  $ls_codestpro1=$ls_codestpro1_min;
		     }
			 
			 if($ls_codestpro3_min<>"")
		     {	
			  $ls_codestpro3_min=$io_funciones->uf_cerosizquierda($ls_codestpro3_min,25);
			  $ls_codestpro3= $ls_codestpro3_min;	
			 }
		     else
		     {	
			  $io_function_report->uf_spg_reporte_select_min_codestpro3($ls_codestpro1_min,$ls_codestpro2_min,&$ls_codestpro3_min,$ls_estclades);
			  $ls_codestpro3=$ls_codestpro3_min;
		     }
			 
					$ls_codestpro1  = trim($ls_codestpro1_min);
					$ls_codestpro2  = trim($ls_codestpro2_min);
					$ls_codestpro3  = trim($ls_codestpro3_min);
					$ls_codestpro4  = trim($ls_codestpro4_min);
					$ls_codestpro5  = trim($ls_codestpro5_min);
			}
			if(($ls_codestpro1h_max=="")&&($ls_codestpro2h_max=="")&&($ls_codestpro3h_max==""))
			{
			  if($io_function_report->uf_spg_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
																			 $ls_codestpro3h_max,$ls_codestpro4h_max,
																			 $ls_codestpro5h_max,$ls_estclahas))
			  {
					$ls_codestpro1h  = trim(str_pad($ls_codestpro1h_max,25,0,0));
					$ls_codestpro2h  = trim(str_pad($ls_codestpro2h_max,25,0,0));
					$ls_codestpro3h  = trim(str_pad($ls_codestpro3h_max,25,0,0));
					$ls_codestpro4h  = trim(str_pad($ls_codestpro4h_max,25,0,0));
					$ls_codestpro5h  = trim(str_pad($ls_codestpro5h_max,25,0,0));
			  }
			}
			else
			{
					$ls_codestpro1h  = trim(str_pad($ls_codestpro1h_max,25,0,0));
					$ls_codestpro2h  = trim(str_pad($ls_codestpro2h_max,25,0,0));
					$ls_codestpro3h  = trim(str_pad($ls_codestpro3h_max,25,0,0));
					$ls_codestpro4h  = trim(str_pad($ls_codestpro4h_max,25,0,0));
					$ls_codestpro5h  = trim(str_pad($ls_codestpro5h_max,25,0,0));
			}
		}
		elseif($li_estmodest==2)
		{
			$ls_codestpro4_min  = $_GET["codestpro4"];
			$ls_codestpro5_min  = $_GET["codestpro5"];
			$ls_codestpro4h_max = $_GET["codestpro4h"];
			$ls_codestpro5h_max = $_GET["codestpro5h"];
			
			if(($ls_codestpro1_min=='**') ||($ls_codestpro1_min==''))
			{
				$ls_codestpro1_min='';
			}
			else
			{
			    $ls_codestpro1_min  = $io_funciones->uf_cerosizquierda($ls_codestpro1_min,25);
			}
			if(($ls_codestpro2_min=='**') ||($ls_codestpro2_min==''))
			{
				$ls_codestpro2_min='';
			}
			else
			{
				$ls_codestpro2_min  = $io_funciones->uf_cerosizquierda($ls_codestpro2_min,25);
			
			}
			if(($ls_codestpro3_min=='**')||($ls_codestpro3_min==''))
			{
				$ls_codestpro3_min='';
			}
			else
			{
			
				$ls_codestpro3_min  = $io_funciones->uf_cerosizquierda($ls_codestpro3_min,25);
			}
			if(($ls_codestpro4_min=='**') ||($ls_codestpro4_min==''))
			{
				$ls_codestpro4_min='';
			}
			else
			{
				$ls_codestpro4_min  = $io_funciones->uf_cerosizquierda($ls_codestpro4_min,25);
			}
			if(($ls_codestpro5_min=='**') ||($ls_codestpro5_min==''))
			{
				$ls_codestpro5_min='';
			}
			else
			{
					$ls_codestpro5_min  = $io_funciones->uf_cerosizquierda($ls_codestpro5_min,25);
			}
			
			
			if(($ls_codestpro1h_max=='**')||($ls_codestpro1h_max==''))
			{
				$ls_codestpro1h_max='';
			}
			else
			{
				$ls_codestpro1h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro1h_max,25);
			}
			if(($ls_codestpro2h_max=='**') ||($ls_codestpro2h_max==''))
			{
				$ls_codestpro2h_max='';
			}else
			{
				$ls_codestpro2h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro2h_max,25);
			}
			if(($ls_codestpro3h_max=='**') ||($ls_codestpro3h_max==''))
			{
				$ls_codestpro3h_max='';
			}else
			{
				$ls_codestpro3h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro3h_max,25);
			}
			if(($ls_codestpro4h_max=='**')  ||($ls_codestpro4h_max==''))
			{
				$ls_codestpro4h_max='';
			}else
			{
				$ls_codestpro4h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro4h_max,25);
			}
			if(($ls_codestpro5h_max=='**')  || ($ls_codestpro5h_max==''))
			{
				$ls_codestpro5h_max='';
			}else
			{
				$ls_codestpro5h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro5h_max,25);
			}
			
			
			if(($ls_codestpro1_min=="")||($ls_codestpro2_min=="")||($ls_codestpro3_min=="")||($ls_codestpro4_min=="")||($ls_codestpro5_min==""))
			{
			  if($io_function_report->uf_spg_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,
			                                                                 $ls_codestpro3_min,$ls_codestpro4_min,
																			 $ls_codestpro5_min,$ls_estclades))
			  {
					$ls_codestpro1  = $ls_codestpro1_min;
					$ls_codestpro2  = $ls_codestpro2_min;
					$ls_codestpro3  = $ls_codestpro3_min;
					$ls_codestpro4  = $ls_codestpro4_min;
					$ls_codestpro5  = $ls_codestpro5_min;
			  }
			}
			else
			{
					$ls_codestpro1  = $ls_codestpro1_min;
					$ls_codestpro2  = $ls_codestpro2_min;
					$ls_codestpro3  = $ls_codestpro3_min;
					$ls_codestpro4  = $ls_codestpro4_min;
					$ls_codestpro5  = $ls_codestpro5_min;
			}
			if(($ls_codestpro1h_max=="")||($ls_codestpro2h_max=="")||($ls_codestpro3h_max=="")||($ls_codestpro4h_max=="")||($ls_codestpro5h_max==""))
			{
			  if($io_function_report->uf_spg_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
			                                                                 $ls_codestpro3h_max,$ls_codestpro4h_max,
																			 $ls_codestpro5h_max,$ls_estclahas))
			  {
				$ls_codestpro1h  = str_pad($ls_codestpro1h_max,25,0,0);
				$ls_codestpro2h  = str_pad($ls_codestpro2h_max,25,0,0);
				$ls_codestpro3h  = str_pad($ls_codestpro3h_max,25,0,0);
				$ls_codestpro4h  = str_pad($ls_codestpro4h_max,25,0,0);
				$ls_codestpro5h  = str_pad($ls_codestpro5h_max,25,0,0);
			  }
			}
			else
			{
				$ls_codestpro1h  = str_pad($ls_codestpro1h_max,25,0,0);
				$ls_codestpro2h  = str_pad($ls_codestpro2h_max,25,0,0);
				$ls_codestpro3h  = str_pad($ls_codestpro3h_max,25,0,0);
				$ls_codestpro4h  = str_pad($ls_codestpro4h_max,25,0,0);
				$ls_codestpro5h  = str_pad($ls_codestpro5h_max,25,0,0);
			}
		}	
		
	    $ls_cuentades_min=$_GET["txtcuentades"];
	    $ls_cuentahas_max=$_GET["txtcuentahas"];
		if($ls_cuentades_min=="")
		{
		   if($io_function_report->uf_spg_reporte_select_min_cuenta($ls_cuentades_min))
		   {
		     $ls_cuentades=$ls_cuentades_min;
		   } 
		   else
		   {
				print("<script language=JavaScript>");
				print(" alert('No hay cuentas presupuestraias');"); 
				print(" close();");
				print("</script>");
		   }
		}
		else
		{
		    $ls_cuentades=$ls_cuentades_min;
		}
		if($ls_cuentahas_max=="")
		{
		   if($io_function_report->uf_spg_reporte_select_max_cuenta($ls_cuentahas_max))
		   {
		     $ls_cuentahas=$ls_cuentahas_max;
		   } 
		   else
		   {
				print("<script language=JavaScript>");
				print(" alert('No hay cuentas presupuestraias');"); 
				print(" close();");
				print("</script>");
		   }
		}
		else
		{
		    $ls_cuentahas=$ls_cuentahas_max;
		}
        $fecdes=$_GET["txtfecdes"];
	    $ldt_fecdes=$io_funciones->uf_convertirdatetobd($fecdes);
        $fechas=$_GET["txtfechas"];
	    $ldt_fechas=$io_funciones->uf_convertirdatetobd($fechas);
		$ls_orden=$_GET["rborden"];
	    $ls_codfuefindes=$_GET["txtcodfuefindes"];
	    $ls_codfuefinhas=$_GET["txtcodfuefinhas"];
		if (($ls_codfuefindes=='')&&($ls_codfuefindes==''))
		{
		   if($io_function_report->uf_spg_select_fuentefinanciamiento(&$ls_minfuefin,&$ls_maxfuefin))
		   {
		     $ls_codfuefindes=$ls_minfuefin;
		     $ls_codfuefinhas=$ls_maxfuefin;
		   } 
		}
		$ls_programatica_desde=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
		$ls_programatica_hasta=$ls_codestpro1h.$ls_codestpro2h.$ls_codestpro3h.$ls_codestpro4h.$ls_codestpro5h;
		/////////////////////////////////         SEGURIDAD               ///////////////////////////////////
		$ls_desc_event="Solicitud de Reporte Modificacion Presupuestaria detallado desde la fecha ".$fecdes." hasta ".$fechas." ,Desde la programatica ".$ls_programatica_desde."  hasta ".$ls_programatica_hasta." , Desde la Cuenta ".$ls_cuentades." hasta la ".$ls_cuentahas;
		$io_function_report->uf_load_seguridad_reporte("SPG","sigesp_spg_r_modif_fuente_finan.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               ///////////////////////////////////
//----------------------------------------------------  Parámetros del encabezado  ---------------------------------------------
		$ls_titulo=" <b>EJERCICO FISCAL ".$li_ano."</b>"; 
		$ls_titulo2=" <b>PRESUPUESTO MODIFICADO DETALLADO POR FUENTES DE FINANCIAMIENTO </b>";
//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
 	 $ls_codestpro1  = str_pad($ls_codestpro1_min,25,0,0);
	 $ls_codestpro2  = str_pad($ls_codestpro2_min,25,0,0);
	 $ls_codestpro3  = str_pad($ls_codestpro3_min,25,0,0);
	 $ls_codestpro4  = str_pad($ls_codestpro4_min,25,0,0);
	 $ls_codestpro5  = str_pad($ls_codestpro5_min,25,0,0);
		
	 $ls_codestpro1h  = str_pad($ls_codestpro1h_max,25,0,0);
	 $ls_codestpro2h  = str_pad($ls_codestpro2h_max,25,0,0);
	 $ls_codestpro3h  = str_pad($ls_codestpro3h_max,25,0,0);
	 $ls_codestpro4h  = str_pad($ls_codestpro4h_max,25,0,0);
	 $ls_codestpro5h  = str_pad($ls_codestpro5h_max,25,0,0);
	
	 $lb_valido=$io_report->select_estructuras($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
											   $ls_codestpro5,$ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,
											   $ls_codestpro4h,$ls_codestpro5h,$ls_estclades,$ls_estclahas);
	 
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
		set_time_limit(3600);
		$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(4.5,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_titulo2,$ls_denmoneda,$io_pdf); // Imprimimos el encabezado de la página
        $io_pdf->ezStartPageNumbers(980,40,10,'','',1); // Insertar el número de página
		$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
		$ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
		$ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
		$ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
		$ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
		$li_totfila=$io_report->data_est->getRowCount("codestpro1");
		
		for ($j=1;$j<=$li_totfila;$j++)
		{
		
		    $ls_codestpro1 = trim($io_report->data_est->data["codestpro1"][$j]);
			$ls_codestpro2 = trim($io_report->data_est->data["codestpro2"][$j]);
			$ls_codestpro3 = trim($io_report->data_est->data["codestpro3"][$j]);
			$ls_codestpro4 = trim($io_report->data_est->data["codestpro4"][$j]);
			$ls_codestpro5 = trim($io_report->data_est->data["codestpro5"][$j]);
			$ls_estcla	   = trim($io_report->data_est->data["estcla"][$j]);
			
			$ls_codestpro1h = trim($io_report->data_est->data["codestpro1"][$j]);
			$ls_codestpro2h = trim($io_report->data_est->data["codestpro2"][$j]);
			$ls_codestpro3h = trim($io_report->data_est->data["codestpro3"][$j]);
			$ls_codestpro4h = trim($io_report->data_est->data["codestpro4"][$j]);
			$ls_codestpro5h = trim($io_report->data_est->data["codestpro5"][$j]);
			$ls_estclahas   = trim($io_report->data_est->data["estcla"][$j]);
			
			$lb_valido=$io_report->uf_modificacion_por_fuente_finan($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
																 $ls_codestpro5,$ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,
																 $ls_codestpro4h,$ls_codestpro5h,$ls_estclades,$ls_estclahas,
																 $ldt_fecdes,$ldt_fechas, $ls_codfuefindes, $ls_codfuefinhas,
																 $ls_codmoneda, $ls_cuentades, $ls_cuentahas);
			$li_total=0;
			if ($lb_valido)
			{	
			    $la_items = array('0'=>'cuenta');
				$la_suma  = array('0'=>'denominacion2');												 
//				$io_report->data_mod->group_by($la_items,$la_suma,'cuenta');
					
				$io_report->data_mod->sortData("cuenta");
				$li_total=0;		
				$li_total=$io_report->data_mod->getRowCount("cuenta");
				$ls_cuent_aux="";
				$total_cedente1=0;
				$total_traspaso1=0;
				$total_incremento1=0;
				
				$total_cedente2=0;
				$total_traspaso2=0;
				$total_incremento2=0;
				$total_asignado=0;
				$total_general1=0;
				$total_general2=0;
				$total=0;
				$ls_denestpro1="";
				$ls_denestpro2="";
				$ls_denestpro3="";
				$ls_denestpro4="";
				$ls_denestpro5="";
				for ($i=1;$i<=$li_total;$i++)
				{
					$ls_total1=0;
					$ls_total2=0;
					$ls_total_global=0;
					$ls_cuenta      = trim($io_report->data_mod->data["cuenta"][$i]);			
					$ls_cuenta      = substr($ls_cuenta,0,3)." ".substr($ls_cuenta,3,2)." ".substr($ls_cuenta,5,2)." ".substr($ls_cuenta,7,2);			
					$ls_denom       = trim($io_report->data_mod->data["denominacion"][$i]);
					$ls_cedente1    = trim($io_report->data_mod->data["cedente1"][$i]);
					$ls_traspaso1   = trim($io_report->data_mod->data["traspaso1"][$i]);
					$ls_incremento1 = trim($io_report->data_mod->data["incremento1"][$i]);
					
					$ls_codestpro1 = trim($io_report->data_mod->data["codestpro1"][$i]);
					$ls_codestpro2 = trim($io_report->data_mod->data["codestpro2"][$i]);
					$ls_codestpro3 = trim($io_report->data_mod->data["codestpro3"][$i]);
					$ls_codestpro4 = trim($io_report->data_mod->data["codestpro4"][$i]);
					$ls_codestpro5 = trim($io_report->data_mod->data["codestpro5"][$i]);
					$ls_estcla     = trim($io_report->data_mod->data["estcla"][$i]);				
					
					$lb_valido=$io_report->uf_spg_reporte_select_denestpro1($ls_codestpro1,$ls_denestpro1,$ls_estcla);			
					if($lb_valido)
					{
						$ls_denestpro1=$ls_denestpro1; 
					}			
					if($lb_valido)
					{
						  $ls_denestpro2="";
						  $lb_valido=$io_report->uf_spg_reporte_select_denestpro2($ls_codestpro1,$ls_codestpro2,
																				  $ls_denestpro2,$ls_estcla);
						  $ls_denestpro2=$ls_denestpro2;
					}
					if($lb_valido)
					{
						  $ls_denestpro3="";
						  $lb_valido=$io_report->uf_spg_reporte_select_denestpro3($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																				  $ls_denestpro3,$ls_estcla);
						  $ls_denestpro3=$ls_denestpro3;
					}
					if($li_estmodest==2)
					{
						if($lb_valido)
						{
						  $ls_denestpro4="";
						  $lb_valido=$io_report->uf_spg_reporte_select_denestpro4($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																				  $ls_codestpro4,$ls_denestpro4,$ls_estcla);
						  $ls_denestpro4=$ls_denestpro4;
						}
						
						if($lb_valido)
						{
						  $ls_denestpro5="";
						  $lb_valido=$io_report->uf_spg_reporte_select_denestpro5($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																				  $ls_codestpro4,$ls_codestpro5,$ls_denestpro5,
																				  $ls_estcla);
						  $ls_denestpro5=$ls_denestpro5;
						}			
					}			
					$ls_total1		   = -$ls_cedente1+($ls_traspaso1+$ls_incremento1);
					$total_general1    = $total_general1+$ls_total1;
					$total_cedente1    = $total_cedente1+$ls_cedente1;
					$total_traspaso1   = $total_traspaso1+$ls_traspaso1;
					$total_incremento1 = $total_incremento1+$ls_incremento1;
					
					$ls_cedente2    = trim($io_report->data_mod->data["cedente2"][$i]);
					$ls_traspaso2   = trim($io_report->data_mod->data["traspaso2"][$i]);
					$ls_incremento2 = trim($io_report->data_mod->data["incremento2"][$i]);
					
					$ls_total2         = abs(($ls_traspaso2+$ls_incremento2)-$ls_cedente2);
					$total_general2    = $total_general2+$ls_total2;
					$total_cedente2    = $total_cedente2+$ls_cedente2;
					$total_traspaso2   = $total_traspaso2+$ls_traspaso2;
					$total_incremento2 = $total_incremento2+$ls_incremento2; 
					
					$ls_asignacion  = trim($io_report->data_mod->data["asignado"][$i]);
					$total_asignado = $total_asignado+$ls_asignacion;
					
					
					$ls_total_global = $ls_asignacion+$ls_total1+$ls_total2;
					$total=$total+$ls_total_global;
					
					if ($ls_cuent_aux!=$ls_cuenta)
					{				    
						$ls_cuent_aux=$ls_cuenta;				
						$ls_data[$i]=array('cuenta'=>$ls_cuenta,'denomincacion'=>$ls_denom,
										   'asignacion'=>number_format($ls_asignacion,2,",","."),
										   'cedente1'=>number_format($ls_cedente1,2,",","."),
										   'traspaso1'=>number_format($ls_traspaso1,2,",","."),
										   'incremento1'=>number_format($ls_incremento1,2,",","."),
										   'total1'=>'<b>'.number_format($ls_total1,2,",",".").'</b>',
										   'incremento2'=>number_format($ls_incremento2,2,",","."),
										   'cedente2'=>number_format($ls_cedente2,2,",","."),
										   'traspaso2'=>number_format($ls_traspaso2,2,",","."),
										   'total2'=>'<b>'.number_format($ls_total2,2,",",".").'</b>',
										   'total'=>'<b>'.number_format($ls_total_global,2,",",".").'</b>');	
					}
					else
					{			   
						if ($ls_cedente1!='0.00')
						{
							$ls_cedente1=$ls_cedente1;
						}
						else
						{
							$ls_cedente1= trim($io_report->data_mod->data["cedente1"][$i-1]);
						}
						if ($ls_traspaso1!='0.00')
						{
							$ls_traspaso1=$ls_traspaso1;
						}
						else
						{
							$ls_traspaso1= trim($io_report->data_mod->data["traspaso1"][$i-1]);
						}
						if ($ls_incremento1!='0.00')
						{
							$ls_incremento1=$ls_incremento1;
						}
						else
						{
							$ls_incremento1= trim($io_report->data_mod->data["incremento1"][$i-1]);
						}
						
						if ($ls_incremento2!='0.00')
						{
							$ls_incremento2=$ls_incremento2;
						}
						else
						{
							$ls_incremento2= trim($io_report->data_mod->data["incremento2"][$i-1]);
						}
						if ($ls_cedente2!='0.00')
						{
							$ls_cedente2=$ls_cedente2;
						}
						else
						{
							$ls_cedente2= trim($io_report->data_mod->data["cedente2"][$i-1]);
						}
						if ($ls_traspaso2!='0.00')
						{
							$ls_traspaso2=$ls_traspaso2;
						}
						else
						{
							$ls_traspaso2= trim($io_report->data_mod->data["traspaso2"][$i-1]);
						}
						
						$ls_total1= -$ls_cedente1+($ls_traspaso1+$ls_incremento1);
						
						$ls_total2= abs(($ls_traspaso2+$ls_incremento2)-$ls_cedente2);		
						
						$ls_total_global= $ls_asignacion+$ls_total1+$ls_total2;
					   
											
						$ls_data[$i-1]=array('cuenta'=>$ls_cuenta,'denomincacion'=>$ls_denom,
										     'asignacion'=>number_format($ls_asignacion,2,",","."),
										     'cedente1'=>number_format($ls_cedente1,2,",","."),
										     'traspaso1'=>number_format($ls_traspaso1,2,",","."),
										     'incremento1'=>number_format($ls_incremento1,2,",","."),
										     'total1'=>'<b>'.number_format($ls_total1,2,",",".").'</b>',
										     'incremento2'=>number_format($ls_incremento2,2,",","."),
										     'cedente2'=>number_format($ls_cedente2,2,",","."),
										     'traspaso2'=>number_format($ls_traspaso2,2,",","."),
										     'total2'=>'<b>'.number_format($ls_total2,2,",",".").'</b>',
										     'total'=>'<b>'.number_format($ls_total_global,2,",",".").'</b>');
					}					
						
				}// fin del for
			}
			if ($li_total>0)
			{
				uf_print_cabecera($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
		                  $ls_denestpro1,$ls_denestpro2,$ls_denestpro3,$ls_denestpro4,$ls_denestpro5,
						  $ls_unidad,$ls_denunidad, $io_pdf);
				uf_print_detalle($ls_data, &$io_pdf);
				uf_print_totales(number_format($total_asignado,2,",","."),
							     number_format($total_cedente1,2,",","."),
							     number_format($total_traspaso1,2,",","."),
								 number_format($total_incremento1,2,",","."),
								 number_format($total_general1,2,",","."),
								 number_format($total_incremento2,2,",","."),
								 number_format($total_cedente2,2,",","."),
								 number_format($total_traspaso2,2,",","."),						 
								 number_format($total_general2,2,",","."),
								 number_format($total,2,",","."),&$io_pdf);
			}
		 }// fin del for		
		if (($li_totfila>0)&&($li_total>0))
		{
			$io_pdf->ezStream();
		}
		else
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}
		
		unset($io_pdf);
	} //else
	unset($io_report);
	unset($io_funciones);	
	unset($io_function_report);		
?> 