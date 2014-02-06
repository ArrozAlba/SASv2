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
function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
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
	// Fecha Creación: 28/09/2006 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$io_encabezado=$io_pdf->openObject();
	$io_pdf->saveState();
	$io_pdf->line(10,30,1000,30);
	$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],10,550,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
	
	$li_tm=$io_pdf->getTextWidth(16,$as_titulo);
	$tm=505-($li_tm/2);
	$io_pdf->addText($tm,550,16,$as_titulo); // Agregar el título
	
	$io_pdf->addText(920,560,10,date("d/m/Y")); // Agregar la Fecha
	$io_pdf->addText(920,545,10,date("h:i a")); // Agregar la hora
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');
	
}// end function uf_print_encabezadopagina
//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_encabezado_pagina2($as_titulo,$as_titulo1,&$io_pdf)
{
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_encabezadopagina2
	//		    Acess: private 
	//	    Arguments: as_titulo // Título del Reporte
	//	    		   as_periodo_comp // Descripción del periodo del comprobante
	//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
	//	    		   io_pdf // Instancia de objeto pdf
	//    Description: función que imprime los encabezados por página
	//	   Creado Por: Ing. Yozelin Barragán
	// Fecha Creación: 28/09/2006 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$io_encabezado=$io_pdf->openObject();
	$io_pdf->saveState();
	$io_pdf->line(10,30,1000,30);
	$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],10,550,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
	
	$li_tm=$io_pdf->getTextWidth(16,$as_titulo);
	$tm=505-($li_tm/2);
	$io_pdf->addText($tm,550,16,$as_titulo); // Agregar el título
	$li_tm=$io_pdf->getTextWidth(16,$as_titulo1);
	$tm=505-($li_tm/2);
	$io_pdf->addText($tm,530,16,$as_titulo1); // Agregar el título
	
	$io_pdf->addText(920,560,10,date("d/m/Y")); // Agregar la Fecha
	$io_pdf->addText(920,545,10,date("h:i a")); // Agregar la hora
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');
	
}// end function uf_print_encabezadopagina2

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_cabecera_detalle($as_spg_cuenta,$as_denominacion,&$io_pdf)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_cabecera
	//		   Access: private 
	//	    Arguments: as_spg_cuenta //cuenta
	//	    		   as_denominacion // denominacion 
	//	    		   io_pdf // Objeto PDF
	//    Description: función que imprime la cabecera de cada página
	//	   Creado Por: Ing. Yozelin Barragán
	// Fecha Creación: 28/09/2006 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//$io_pdf->ezSetDy(-10);
	$la_data=array(array('name'=>'<b>Cuenta</b>  '.$as_spg_cuenta.'  ---  '.$as_denominacion.''));
	$la_columna=array('name'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
					 'showLines'=>0, // Mostrar Líneas
					 'shaded'=>2, // Sombra entre líneas
					 'fontSize' => 8, // Tamaño de Letras
					 'shadeCol'=>array(0.9,0.9,0.9),
					 'shadeCol2'=>array(0.9,0.9,0.9),
					 'xOrientation'=>'center', // Orientación de la tabla
					 'xPos'=>510, // Orientación de la tabla
					 'width'=>990, // Ancho de la tabla
					 'maxWidth'=>990); // Ancho Máximo de la tabla
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
}// end function uf_print_cabecera_detalle
//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_detalle($la_data,&$io_pdf)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_detalle
	//		    Acess: private 
	//	    Arguments: la_data // arreglo de información
	//	   			   io_pdf // Objeto PDF
	//    Description: función que imprime el detalle
	//	   Creado Por: Ing. Yozelin Barragán
	// Fecha Creación: 28/09/2006 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$ls_titulo="Monto Bs.";

	$la_config=array('showHeadings'=>1, // Mostrar encabezados
					 'fontSize' => 7, // Tamaño de Letras
					 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
					 'showLines'=>0, // Mostrar Líneas
					 'shaded'=>0, // Sombra entre líneas
					 'colGap'=>1, // separacion entre tablas
					 'width'=>990, // Ancho de la tabla
					 'maxWidth'=>990, // Ancho Máximo de la tabla
					 'xOrientation'=>'center', // Orientación de la tabla
					 'xPos'=>502, // Orientación de la tabla
					 'cols'=>array('fecha'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
								   'comprobante'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
								   'documento'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
								   'detalle'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna
								   'previsto'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
								   'aumento'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
								   'disminución'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
								   'montoactualizado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
								   'devengado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
								   'cobrado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
								   'cobrado_anticipado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la
								   'porcobrar'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la 
	
	$la_columnas=array('fecha'=>'<b>Fecha</b>',
					   'comprobante'=>'<b>Comprobante</b>',
					   'documento'=>'<b>Documento</b>',
					   'detalle'=>'<b>Detalle</b>',
					   'previsto'=>'<b>Previsto</b>',
					   'aumento'=>'<b>Aumento</b>',
					   'disminución'=>'<b>Disminución</b>',
					   'montoactualizado'=>'<b>Monto Actualizado</b>',
					   'devengado'=>'<b>Devengado</b>',
					   'cobrado'=>'<b>Cobrado</b>',
					   'cobrado_anticipado'=>'<b>Cobrado Anticipado</b>',
					   'porcobrar'=>'<b>Por Cobrar</b>');
	$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
}// end function uf_print_detalle
//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_saldo_por_devengar,$ad_total_previsto,$ad_total_aumento,$ad_total_disminución,                                   $ad_total_monto_actualizado,$ad_total_monto_devengado,$ad_total_monto_cobrado,                                   $ad_total_monto_cobrado_anticipado,$ad_total_monto_por_cobrar,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 29/09/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_datat=array(array('name'=>'__________________________________________________________________________________________________________________________________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>520, // Orientación de la tabla
						 'width'=>990); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_datat,$la_columna,'',$la_config);
		
		$la_data[]=array('comprobante'=>'','documento'=>'<b>SALDO POR DEVENGAR</b>','detalle'=>$ad_saldo_por_devengar,
		                 'previsto'=>$ad_total_previsto,'aumento'=>$ad_total_aumento,'disminución'=>$ad_total_disminución,
						 'montoactualizado'=>$ad_total_monto_actualizado,'devengado'=>$ad_total_monto_devengado,
						 'cobrado'=>$ad_total_monto_cobrado,'cobrado_anticipado'=>$ad_total_monto_cobrado_anticipado,
						 'porcobrar'=>$ad_total_monto_por_cobrar);
		$la_columnas=array('comprobante'=>'','documento'=>'','detalle'=>'','previsto'=>'','aumento'=>'',
		                   'disminución'=>'','montoactualizado'=>'','devengado'=>'','cobrado'=>'','cobrado_anticipado'=>'',
						   'porcobrar'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>502, // Orientación de la tabla
						 'cols'=>array('comprobante'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la 
						               'documento'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la 
									   'detalle'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la 
									   'previsto'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
									   'aumento'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
									   'disminución'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
									   'montoactualizado'=>array('justification'=>'right','width'=>80), // Justificación y   
									   'devengado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
									   'cobrado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
									   'cobrado_anticipado'=>array('justification'=>'right','width'=>80), // Justificación y ancho 
									   'porcobrar'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la 
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
		//--------------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_cabecera_estructura( $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
		                        $ls_denestpro1,$ls_denestpro2,$ls_denestpro3,$ls_denestpro4,$ls_denestpro5,$io_pdf)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_cabecera
	//		   Access: private 
	//	    Arguments: as_programatica // programatica del comprobante
	//	    		   as_denestpro5 // denominacion de la programatica del comprobante
	//	    		   io_pdf // Objeto PDF
	//    Description: función que imprime la cabecera de cada página
	//	   Creado Por: Ing. Jennifer Rivero
	// Fecha Creación: 17/11/2008 
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
			
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'fontSize' =>7, // Tamaño de Letras
							 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'colGap'=>1, // separacion entre tablas
							 'width'=>990, // Ancho de la tabla
							 'maxWidth'=>990, // Ancho Máximo de la tabla
							 'xOrientation'=>'center', // Orientación de la tabla
							 'xPos'=>390, // Orientación de la tabla
							 'cols'=>array('nombre'=>array('justification'=>'right','width'=>130),									  
										   'codestpro'=>array('justification'=>'center','width'=>100),
										   'denom'=>array('justification'=>'left','width'=>420)));		
			$io_pdf->ezTable($ls_datat1,'','',$la_config);
		}
		else
		{
			$ls_datat1[1]=array('nombre'=>'<b>'.$li_nomestpro1.":</b> ",'codestpro'=>$ls_codestpro1,'denom'=>$ls_denestpro1);
			$ls_datat1[2]=array('nombre'=>'<b>'.$li_nomestpro2.":</b> ",'codestpro'=>$ls_codestpro2,'denom'=>$ls_denestpro2);
			$ls_datat1[3]=array('nombre'=>'<b>'.$li_nomestpro3.":</b> ",'codestpro'=>$ls_codestpro3,'denom'=>$ls_denestpro3);
			$ls_datat1[4]=array('nombre'=>'<b>'.$li_nomestpro4.":</b> ",'codestpro'=>$ls_codestpro4,'denom'=>$ls_denestpro4);
			$ls_datat1[5]=array('nombre'=>'<b>'.$li_nomestpro5.":</b> ",'codestpro'=>$ls_codestpro5,'denom'=>$ls_denestpro5);			
			
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'fontSize' => 6, // Tamaño de Letras
							 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'colGap'=>1, // separacion entre tablas
							 'width'=>990, // Ancho de la tabla
							 'maxWidth'=>990, // Ancho Máximo de la tabla
							 'xOrientation'=>'center', // Orientación de la tabla
							 'xPos'=>302, // Orientación de la tabla
							 'cols'=>array('nombre'=>array('justification'=>'left','width'=>150),									  
										   'codestpro'=>array('justification'=>'right','width'=>60),
										   'denom'=>array('justification'=>'left','width'=>320)));			
		   $io_pdf->ezTable($ls_datat1,'','',$la_config);	
		}
		unset($ls_datat1);
		unset($la_config);			
	}// end function uf_print_cabecera
//--------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("sigesp_spi_reporte.php");
		$io_report = new sigesp_spi_reporte();
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();	
		require_once("../../shared/class_folder/class_datastore.php");
		$ds_reporteaux=new class_datastore();	
		require_once("../class_funciones_ingreso.php");
		$io_fun_ingreso=new class_funciones_ingreso();			
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
		require_once("sigesp_spi_funciones_reportes.php");
		$io_function_report = new sigesp_spi_funciones_reportes();
//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
    	$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
	    $ls_estpreing=$_SESSION["la_empresa"]["estpreing"];
		 if ($ls_estpreing==1)
	    {
			$ls_codestpro1_min  = $_GET["codestpro1"]; 
			$ls_codestpro2_min  = $_GET["codestpro2"]; 
			$ls_codestpro3_min  = $_GET["codestpro3"];
			$ls_codestpro1h_max = $_GET["codestpro1h"];
			$ls_codestpro2h_max = $_GET["codestpro2h"]; 
			$ls_codestpro3h_max = $_GET["codestpro3h"];  
			$ls_estclades       = $_GET["estclades"];
			$ls_estclahas       = $_GET["estclahas"];
			$ls_loncodestpro1   = $_SESSION["la_empresa"]["loncodestpro1"];
			$ls_loncodestpro2   = $_SESSION["la_empresa"]["loncodestpro2"];
			$ls_loncodestpro3   = $_SESSION["la_empresa"]["loncodestpro3"];
			$ls_loncodestpro4   = $_SESSION["la_empresa"]["loncodestpro4"];
			$ls_loncodestpro5   = $_SESSION["la_empresa"]["loncodestpro5"];
	
			if($ls_modalidad==1)
			{
				$ls_codestpro4_min =  "0000000000000000000000000";
				$ls_codestpro5_min =  "0000000000000000000000000";
				$ls_codestpro4h_max = "0000000000000000000000000";
				$ls_codestpro5h_max = "0000000000000000000000000";
				if(($ls_codestpro1_min=="")&&($ls_codestpro2_min=="")&&($ls_codestpro3_min==""))
				{
				  if($io_function_report->uf_spi_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,
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
				if(($ls_codestpro1h_max=="")&&($ls_codestpro2h_max=="")&&($ls_codestpro3h_max==""))
				{
				  if($io_function_report->uf_spi_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
																				 $ls_codestpro3h_max,$ls_codestpro4h_max,
																				 $ls_codestpro5h_max,$ls_estclahas))
				  {
						$ls_codestpro1h  = $ls_codestpro1h_max;
						$ls_codestpro2h  = $ls_codestpro2h_max;
						$ls_codestpro3h  = $ls_codestpro3h_max;
						$ls_codestpro4h  = $ls_codestpro4h_max;
						$ls_codestpro5h  = $ls_codestpro5h_max;
				  }
				}
				else
				{
						$ls_codestpro1h  = $ls_codestpro1h_max;
						$ls_codestpro2h  = $ls_codestpro2h_max;
						$ls_codestpro3h  = $ls_codestpro3h_max;
						$ls_codestpro4h  = $ls_codestpro4h_max;
						$ls_codestpro5h  = $ls_codestpro5h_max;
				}
			}
			elseif($ls_modalidad==2)
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
				  if($io_function_report->uf_spi_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,
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
				  if($io_function_report->uf_spi_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
																				 $ls_codestpro3h_max,$ls_codestpro4h_max,
																				 $ls_codestpro5h_max,$ls_estclahas))
				  {
					$ls_codestpro1h  = $ls_codestpro1h_max;
					$ls_codestpro2h  = $ls_codestpro2h_max;
					$ls_codestpro3h  = $ls_codestpro3h_max;
					$ls_codestpro4h  = $ls_codestpro4h_max;
					$ls_codestpro5h  = $ls_codestpro5h_max;
				  }
				}
				else
				{
					$ls_codestpro1h  = $ls_codestpro1h_max;
					$ls_codestpro2h  = $ls_codestpro2h_max;
					$ls_codestpro3h  = $ls_codestpro3h_max;
					$ls_codestpro4h  = $ls_codestpro4h_max;
					$ls_codestpro5h  = $ls_codestpro5h_max;
				}
			}
			
			$ls_programatica_desde=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
			$ls_programatica_hasta=$ls_codestpro1h.$ls_codestpro2h.$ls_codestpro3h.$ls_codestpro4h.$ls_codestpro5h;
			if($ls_modalidad==1)
			{
				if (($ls_codestpro1<>"")&&($ls_codestpro2=="")&&($ls_codestpro3==""))
				{
				 $ls_programatica_desde1=substr($ls_codestpro1,-$ls_loncodestpro1);
				 $ls_programatica_hasta1=substr($ls_codestpro1h,-$ls_loncodestpro1);
				}
				elseif(($ls_codestpro1<>"")&&($ls_codestpro2<>"")&&($ls_codestpro3==""))
				{
				 $ls_programatica_desde1=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2);
				 $ls_programatica_hasta1=substr($ls_codestpro1h,-$ls_loncodestpro1)."-".substr($ls_codestpro2h,-$ls_loncodestpro2);
				}
				elseif(($ls_codestpro1<>"")&&($ls_codestpro2<>"")&&($ls_codestpro3<>""))
				{
				 $ls_programatica_desde1=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3);
				 $ls_programatica_hasta1=substr($ls_codestpro1h,-$ls_loncodestpro1)."-".substr($ls_codestpro2h,-$ls_loncodestpro2)."-".substr($ls_codestpro3h,-$ls_loncodestpro3);
				}
				else
				{
				 $ls_programatica_desde1="";
				 $ls_programatica_hasta1="";
				}
			}
			else
			{
				$ls_programatica_desde1=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3)."-".substr($ls_codestpro4,-$ls_loncodestpro4)."-".substr($ls_codestpro5,-$ls_loncodestpro5)."-".$ls_estclades;
				$ls_programatica_hasta1=substr($ls_codestpro1h,-$ls_loncodestpro1)."-".substr($ls_codestpro2h,-$ls_loncodestpro2)."-".substr($ls_codestpro3h,-$ls_loncodestpro3)."-".substr($ls_codestpro4h,-$ls_loncodestpro4)."-".substr($ls_codestpro5h,-$ls_loncodestpro5)."-".$ls_estclahas;
			}
        }
		$ls_cuentades_min=$_GET["txtcuentades"];
		$ls_cuentahas_max=$_GET["txtcuentahas"];
			if($ls_cuentades_min=="")
			{
			   if($io_function_report->uf_spi_reporte_select_min_cuenta($ls_cuentades_min))
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
			   if($io_function_report->uf_spi_reporte_select_max_cuenta($ls_cuentahas_max))
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
		
		/////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////////////////////////////////////////////
		$ls_desc_event="Solicitud de Reporte Mayor Analitico desde la Cuenta ".$ls_cuentades." hasta ".$ls_cuentahas." y desde la fecha ".$fecdes." hasta ".$fechas;
		$io_fun_ingreso->uf_load_seguridad_reporte("SPI","sigesp_spi_r_mayor_analitico.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////////////////////////////////////////////
//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
		$ls_titulo=" <b>MAYOR ANALITICO  DESDE  ".$fecdes."  AL  ".$fechas." </b>";
		if ($ls_estpreing==1)
	    {
		  $ls_titulo1="<b> DESDE LA PROGRAMATICA  ".$ls_programatica_desde1."  HASTA  ".$ls_programatica_hasta1." </b>";
		}
		require_once("../../shared/ezpdf/class.ezpdf.php");
		
//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )
	$ls_estpreing=$_SESSION["la_empresa"]["estpreing"];
	if ($ls_estpreing==1)
	{
		$ls_codestpro1  = $io_funciones->uf_cerosizquierda($ls_codestpro1_min,25);
		$ls_codestpro2  = $io_funciones->uf_cerosizquierda($ls_codestpro2_min,25);
		$ls_codestpro3  = $io_funciones->uf_cerosizquierda($ls_codestpro3_min,25);
		$ls_codestpro4  = $io_funciones->uf_cerosizquierda($ls_codestpro4_min,25);
		$ls_codestpro5  = $io_funciones->uf_cerosizquierda($ls_codestpro5_min,25);
			
		$ls_codestpro1h  = $io_funciones->uf_cerosizquierda($ls_codestpro1h_max,25);
		$ls_codestpro2h  = $io_funciones->uf_cerosizquierda($ls_codestpro2h_max,25);
		$ls_codestpro3h  = $io_funciones->uf_cerosizquierda($ls_codestpro3h_max,25);
		$ls_codestpro4h  = $io_funciones->uf_cerosizquierda($ls_codestpro4h_max,25);
		$ls_codestpro5h  = $io_funciones->uf_cerosizquierda($ls_codestpro5h_max,25);
	}
	$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
	
	$li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
	$li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
	$li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
	$li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
	$li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
    error_reporting(E_ALL);
	set_time_limit(1800);
	$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
	$ld_total_previsto = 0;
	$ld_total_aumento  = 0;		  
	$ld_total_disminucion = 0;		 
	$ld_total_devengado = 0;		 		   
	$ld_total_cobrado = 0;		 		   		  
	$ld_total_cobrado_anticipado = 0;
	$ld_total_monto_actualizado=0;
	$ld_total_por_cobrar=0;
	$ld_sub_total_previsto=0;
	$ld_sub_total_aumento=0;
	$ld_sub_total_disminucion=0;
	$ld_sub_total_devengado=0;
	$ld_sub_total_cobrado=0;
	$ld_sub_total_cobrado_anticipado=0;
	$ld_sub_total_monto_actualizado=0;
	$ld_sub_total_por_cobrar=0; 
    if ($ls_estpreing==1)
	{	
												   
			 uf_print_encabezado_pagina2($ls_titulo,$ls_titulo1,$io_pdf); // Imprimimos el encabezado de la página
			 $lb_valido=$io_report->select_estructuras_spi($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
														$ls_codestpro5,$ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,
														$ls_codestpro4h,$ls_codestpro5h,$ls_estclades,$ls_estclahas);
			 $li_totfila=$io_report->data_est->getRowCount("programatica");
			 $ls_codestpro1aux=""; 
			 $ls_codestpro2aux="";
			 $ls_codestpro3aux="";
			 $ls_codestpro4aux="";
			 $ls_codestpro5aux="";
			
			for ($j=1;(($j<=$li_totfila)&&($lb_valido));$j++)
			{
				  $ls_codestpro1=trim($io_report->data_est->data["codestpro1"][$j]); 
				  $ls_codestpro2=trim($io_report->data_est->data["codestpro2"][$j]);
				  $ls_codestpro3=trim($io_report->data_est->data["codestpro3"][$j]);
				  $ls_codestpro4=trim($io_report->data_est->data["codestpro4"][$j]);
				  $ls_codestpro5=trim($io_report->data_est->data["codestpro5"][$j]);
				  $ls_estcla=trim($io_report->data_est->data["estcla"][$j]);
				  $ls_estclades=trim($io_report->data_est->data["estcla"][$j]);
				
				  $ls_codestpro1h=trim($io_report->data_est->data["codestpro1"][$j]);
				  $ls_codestpro2h=trim($io_report->data_est->data["codestpro2"][$j]);
				  $ls_codestpro3h=trim($io_report->data_est->data["codestpro3"][$j]);
				  $ls_codestpro4h=trim($io_report->data_est->data["codestpro4"][$j]);
				  $ls_codestpro5h=trim($io_report->data_est->data["codestpro5"][$j]);
				  $ls_estclahas=trim($io_report->data_est->data["estcla"][$j]);

				  $lb_valido=$io_report->uf_spi_reporte_mayor_analitico2($ldt_fecdes,$ldt_fechas,$ls_cuentades,
																   $ls_cuentahas,$ls_orden,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
																   $ls_codestpro5,$ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,
																   $ls_codestpro4h,$ls_codestpro5h,$ls_estclades,$ls_estclahas);
																   
																   
				 if ($lb_valido)
				 {
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
							  $ls_denestpro4="";
							  $ls_denestpro5="";
						} 
						if($ls_modalidad==2)
						{
							$ls_codestpro4=substr($ls_programatica,75,25);
							if($lb_valido)
							{
							 $ls_denestpro4="";
							  $lb_valido=$io_report->uf_spg_reporte_select_denestpro4($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																					  $ls_codestpro4,$ls_denestpro4,$ls_estcla);
							  $ls_denestpro4=$ls_denestpro4;
							}
							$ls_codestpro5=substr($ls_programatica,100,25);
							if($lb_valido)
							{
							  $ls_denestpro5="";
							  $lb_valido=$io_report->uf_spg_reporte_select_denestpro5($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																					  $ls_codestpro4,$ls_codestpro5,$ls_denestpro5,
																					  $ls_estcla);
							  $ls_denestpro5=$ls_denestpro5;
							}			
						}																   
					  $li_totrow_det=$io_report->dts_reporte->getRowCount("spi_cuenta"); 
					  if ($li_totrow_det>0)
					  {
						uf_print_cabecera_estructura($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
											   $ls_denestpro1,$ls_denestpro2,$ls_denestpro3,$ls_denestpro4,$ls_denestpro5,$io_pdf);
					  }
					  for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
						{ 
						  $li_tmp=($li_s+1);
						  $io_pdf->transaction('start'); // Iniciamos la transacción
						  $thisPageNum=$io_pdf->ezPageCount;
						  $ls_spi_cuenta=$io_report->dts_reporte->data["spi_cuenta"][$li_s]; 
						  
						  if ($li_s<$li_totrow_det)
						  {
								$ls_spi_cuenta_next=$io_report->dts_reporte->data["spi_cuenta"][$li_tmp]; //print "cuenta next ".$ls_spi_cuenta_next."<br>";  
						  }
						  elseif($li_s==$li_totrow_det)
						  {
								$ls_spi_cuenta_next='no_next';
						  }
						  if(empty($ls_spi_cuenta_next)&&(!empty($ls_spi_cuenta)))
						  {
							 $ls_spi_cuenta_ant=$io_report->dts_reporte->data["spi_cuenta"][$li_s]; 
						  }
						  else
						  {
							 $ls_spi_cuenta_ant=$io_report->dts_reporte->data["spi_cuenta"][$li_s]; 
						  }
						  if($li_totrow_det==1)
						  {
							 $ls_spi_cuenta_ant=$io_report->dts_reporte->data["spi_cuenta"][$li_s];// print "cuenta ant ".$ls_spi_cuenta_ant."<br>";
						  }
						  
									  
						  $ls_denominacion=$io_report->dts_reporte->data["denominacion"][$li_s];
						  $fecha=$io_report->dts_reporte->data["fecha"][$li_s];
						  $ls_fecha=$io_funciones->uf_convertirfecmostrar($fecha);
						  $ls_procede=$io_report->dts_reporte->data["procede"][$li_s];
						  $ls_procede_doc=$io_report->dts_reporte->data["procede_doc"][$li_s];
						  $ls_comprobante=$io_report->dts_reporte->data["comprobante"][$li_s];
						  $ls_documento=$io_report->dts_reporte->data["documento"][$li_s];
						  $ls_descripcion=$io_report->dts_reporte->data["descripcion"][$li_s];
						  $ld_previsto=$io_report->dts_reporte->data["previsto"][$li_s];
						  $ld_aumento=$io_report->dts_reporte->data["aumento"][$li_s];
						  $ld_disminucion=$io_report->dts_reporte->data["disminucion"][$li_s];
						  $ld_devengado=$io_report->dts_reporte->data["devengado"][$li_s];
						  $ld_cobrado=$io_report->dts_reporte->data["cobrado"][$li_s];
						  $ld_cobrado_anticipado=$io_report->dts_reporte->data["cobrado_anticipado"][$li_s];
						  $ls_tipo_destino=$io_report->dts_reporte->data["tipo_destino"][$li_s];
						  $ls_cod_pro=$io_report->dts_reporte->data["cod_pro"][$li_s];
						  $ls_nompro=$io_report->dts_reporte->data["nompro"][$li_s];
						  $ls_nombene=$io_report->dts_reporte->data["nombene"][$li_s];
						  $ls_operacion=$io_report->dts_reporte->data["operacion"][$li_s];
						  $ld_monto_actualizado=($ld_previsto+$ld_aumento-$ld_disminucion)-$ld_devengado;
						  $ld_monto_actualizado_aux=$ld_monto_actualizado;
						  if(($ls_operacion=="DEV")or($ls_operacion=="COB")or($ls_operacion=="DC"))
						  {
							  $ld_monto_actualizado=0;
						  }
						  $ld_por_cobrar=$ld_devengado-$ld_cobrado;
						  
						  $ld_total_previsto=$ld_total_previsto+$ld_previsto;
						  $ld_total_aumento=$ld_total_aumento+$ld_aumento;
						  $ld_total_disminucion=$ld_total_disminucion+$ld_disminucion;
						  $ld_total_devengado=$ld_total_devengado+$ld_devengado;
						  $ld_total_cobrado=$ld_total_cobrado+$ld_cobrado;
						  $ld_total_cobrado_anticipado=$ld_total_cobrado_anticipado+$ld_cobrado_anticipado;
						  $ld_total_monto_actualizado=$ld_total_monto_actualizado+$ld_monto_actualizado;
						  $ld_total_por_cobrar=$ld_total_por_cobrar+$ld_por_cobrar;
						 
						  $ld_sub_total_previsto=$ld_sub_total_previsto+$ld_previsto;
						  $ld_sub_total_aumento=$ld_sub_total_aumento+$ld_aumento;
						  $ld_sub_total_disminucion=$ld_sub_total_disminucion+$ld_disminucion;
						  $ld_sub_total_devengado=$ld_sub_total_devengado+$ld_devengado;
						  $ld_sub_total_cobrado=$ld_sub_total_cobrado+$ld_cobrado;
						  $ld_sub_total_cobrado_anticipado=$ld_sub_total_cobrado_anticipado+$ld_cobrado_anticipado;
						  $ld_sub_total_monto_actualizado=$ld_sub_total_monto_actualizado+$ld_monto_actualizado_aux;
						  $ld_sub_total_por_cobrar=$ld_sub_total_por_cobrar+$ld_por_cobrar;
						  
						  
						 if (!empty($ls_spi_cuenta))
						  {
							  $la_data1[$li_s]=array('fecha'=>$ls_fecha,'comprobante'=>$ls_comprobante,'documento'=>$ls_documento,
													'detalle'=>$ls_descripcion,'previsto'=>number_format($ld_previsto,2,',','.'),'aumento'=>number_format($ld_aumento,2,',','.'),
													'disminución'=>number_format($ld_disminucion,2,',','.'),'montoactualizado'=>number_format($ld_monto_actualizado,2,',','.'),
													'devengado'=>number_format($ld_devengado,2,',','.'),'cobrado'=>number_format($ld_cobrado,2,',','.'),
													'cobrado_anticipado'=>number_format($ld_cobrado_anticipado,2,',','.'),'porcobrar'=>number_format($ld_por_cobrar,2,',','.'));
							 
						  }
						  else
						  {
							  $la_data1[$li_s]=array('fecha'=>$ls_fecha,'comprobante'=>$ls_comprobante,'documento'=>$ls_documento,
													'detalle'=>$ls_descripcion,'previsto'=>number_format($ld_previsto,2,',','.'),'aumento'=>number_format($ld_aumento,2,',','.'),
													'disminución'=>number_format($ld_disminucion,2,',','.'),'montoactualizado'=>number_format($ld_monto_actualizado,2,',','.'),
													'devengado'=>number_format($ld_devengado,2,',','.'),'cobrado'=>number_format($ld_cobrado,2,',','.'),
													'cobrado_anticipado'=>number_format($ld_cobrado_anticipado,2,',','.'),'porcobrar'=>number_format($ld_por_cobrar,2,',','.'));
						  }
						  if (!empty($ls_spi_cuenta_next))
						  {
							  $la_data1[$li_s]=array('fecha'=>$ls_fecha,'comprobante'=>$ls_comprobante,'documento'=>$ls_documento,
													'detalle'=>$ls_descripcion,'previsto'=>number_format($ld_previsto,2,',','.'),'aumento'=>number_format($ld_aumento,2,',','.'),
													'disminución'=>number_format($ld_disminucion,2,',','.'),'montoactualizado'=>number_format($ld_monto_actualizado,2,',','.'),
													'devengado'=>number_format($ld_devengado,2,',','.'),'cobrado'=>number_format($ld_cobrado,2,',','.'),
													'cobrado_anticipado'=>number_format($ld_cobrado_anticipado,2,',','.'),'porcobrar'=>number_format($ld_por_cobrar,2,',','.'));
									
							if (($li_totrow_det>0) &&($ls_spi_cuenta!=$ls_spi_cuenta_next))
							{
								 $io_pdf->ezSetDy(-10);					
								  $io_pdf->ezSetDy(-8);
								 uf_print_cabecera_detalle($ls_spi_cuenta_ant,$ls_denominacion,$io_pdf);
								 uf_print_detalle($la_data1,$io_pdf); // Imprimimos el detalle 
								 $ld_por_devengar=$ld_sub_total_previsto+$ld_sub_total_aumento+$ld_sub_total_disminucion;
								 $ld_saldo_por_devengar=$ld_por_devengar-$ld_sub_total_devengado;
								 $ld_subtotal_previsto=$ld_sub_total_previsto;
								 $ld_subtotal_aumento=$ld_sub_total_aumento;
								 $ld_subtotal_disminucion=$ld_sub_total_disminucion;
								 $ld_subtotal_devengado=$ld_sub_total_devengado;
								 $ld_subtotal_cobrado=$ld_sub_total_cobrado;
								 $ld_subtotal_cobrado_anticipado=$ld_sub_total_cobrado_anticipado;
								 $ld_subtotal_monto_actualizado=$ld_sub_total_monto_actualizado;
								 $ld_subtotal_por_cobrar=$ld_sub_total_por_cobrar;
								 
								 uf_print_pie_cabecera(number_format($ld_saldo_por_devengar,2,',','.'),
													   number_format($ld_sub_total_previsto,2,',','.'),
													   number_format($ld_sub_total_aumento,2,',','.'),
													   number_format($ld_sub_total_disminucion,2,',','.'),
													   number_format($ld_sub_total_monto_actualizado,2,',','.'),
													   number_format($ld_sub_total_devengado,2,',','.'),
													   number_format($ld_sub_total_cobrado,2,',','.'),
													   number_format($ld_sub_total_cobrado_anticipado,2,',','.'),
													   number_format($ld_sub_total_por_cobrar,2,',','.'),$io_pdf);
								
								$ld_sub_total_previsto=0;
								$ld_sub_total_aumento=0;
								$ld_sub_total_disminucion=0;
								$ld_sub_total_devengado=0;
								$ld_sub_total_cobrado=0;
								$ld_sub_total_cobrado_anticipado=0;
								$ld_sub_total_monto_actualizado=0;
								$ld_sub_total_por_cobrar=0;
								if ($io_pdf->ezPageCount==$thisPageNum)
								 {// Hacemos el commit de los registros que se desean imprimir
									$io_pdf->transaction('commit');
								 }
								 elseif($thisPageNum>1)
								 {// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
									 $io_pdf->transaction('rewind');
									 $io_pdf->ezNewPage(); // Insertar una nueva página
									 $io_pdf->ezSetDy(-10);							
									 $io_pdf->ezSetDy(-8);
									 uf_print_cabecera_detalle($ls_spi_cuenta_ant,$ls_denominacion,$io_pdf);
									 uf_print_detalle($la_data1,$io_pdf); // Imprimimos el detalle 
									 $ld_por_devengar=$ld_sub_total_previsto+$ld_sub_total_aumento+$ld_sub_total_disminucion;
									 $ld_saldo_por_devengar=$ld_por_devengar-$ld_sub_total_devengado;
									 uf_print_pie_cabecera(number_format($ld_saldo_por_devengar,2,',','.'),
														   number_format($ld_subtotal_previsto,2,',','.'),
														   number_format($ld_subtotal_aumento,2,',','.'),
														   number_format($ld_subtotal_disminucion,2,',','.'),
														   number_format($ld_subtotal_monto_actualizado,2,',','.'),
														   number_format($ld_subtotal_devengado,2,',','.'),
														   number_format($ld_subtotal_cobrado,2,',','.'),
														   number_format($ld_subtotal_cobrado_anticipado,2,',','.'),
														   number_format($ld_subtotal_por_cobrar,2,',','.'),$io_pdf);
									
									$ld_subtotal_previsto=0;
									$ld_subtotal_aumento=0;
									$ld_subtotal_disminucion=0;
									$ld_subtotal_devengado=0;
									$ld_subtotal_cobrado=0;
									$ld_subtotal_cobrado_anticipado=0;
									$ld_subtotal_monto_actualizado=0;
									$ld_subtotal_por_cobrar=0;
								 }
								unset($la_data1);
							 } // fin del if 			
						  }//if		
						}//for
					$ls_codestpro1aux=$ls_codestpro1; 
					$ls_codestpro2aux=$ls_codestpro2;
					$ls_codestpro3aux=$ls_codestpro3;
					$ls_codestpro4aux=$ls_codestpro4;
					$ls_codestpro5aux=$ls_codestpro5;

			}
			else
			{
				print("<script language=JavaScript>");
				print(" alert('Ocurrio un error al generar el Reporte.');"); 
				print(" close();");
				print("</script>");
			}
			
		}// fin del for	
		$io_pdf->ezStopPageNumbers(1,1);											   
		if (isset($d) && $d)
		{
			$ls_pdfcode = $io_pdf->ezOutput(1);
		  	$ls_pdfcode = str_replace("\n","\n<br>",htmlspecialchars($ls_pdfcode));
		  	echo '<html><body>';
		  	echo trim($ls_pdfcode);
		  	echo '</body></html>';
		}
		else
		{
			$io_pdf->ezStream();
		}
		unset($io_pdf);										
	}
    else // Imprimimos el reporte
	 { 
 	    uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
        $io_pdf->ezStartPageNumbers(980,40,10,'','',1); // Insertar el número de página
		$lb_valido=$io_report->uf_spi_reporte_mayor_analitico($ldt_fecdes,$ldt_fechas,$ls_cuentades,
															   $ls_cuentahas,$ls_orden);
		$ds_reporteaux->data=$io_report->dts_reporte->data;
		//$io_report->dts_reporte->group_noorder("spi_cuenta");
		$ds_reporteaux->group_by(array('0'=>'spi_cuenta'),array('0'=>'previsto'),'previsto');
		$li_totrow_detaux=$ds_reporteaux->getRowCount("spi_cuenta");
		$li_totrow_det=$io_report->dts_reporte->getRowCount("spi_cuenta");
		for($li_z=1;$li_z<=$li_totrow_detaux;$li_z++)
		{
			$ls_cuentaimprimir=$ds_reporteaux->data["spi_cuenta"][$li_z];
			$li_h=0;
			$ld_sub_total_previsto=0;
			$ld_sub_total_aumento=0;
			$ld_sub_total_disminucion=0;
			$ld_sub_total_devengado=0;
			$ld_sub_total_cobrado=0;
			$ld_sub_total_cobrado_anticipado=0;
			$ld_sub_total_monto_actualizado=0;
			$ld_sub_total_por_cobrar=0;
			unset($la_data);
			for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
			{
				$ls_cuentaaux=$io_report->dts_reporte->getValue("spi_cuenta",$li_s);
				if($ls_cuentaimprimir==$ls_cuentaaux)
				{
				      $li_h++;
					  $ls_denominacion=$io_report->dts_reporte->data["denominacion"][$li_s];
					  $fecha=$io_report->dts_reporte->data["fecha"][$li_s];
					  $ls_fecha=$io_funciones->uf_convertirfecmostrar($fecha);
					  $ls_procede=$io_report->dts_reporte->data["procede"][$li_s];
					  $ls_procede_doc=$io_report->dts_reporte->data["procede_doc"][$li_s];
					  $ls_comprobante=$io_report->dts_reporte->data["comprobante"][$li_s];
					  $ls_documento=$io_report->dts_reporte->data["documento"][$li_s];
					  $ls_descripcion=$io_report->dts_reporte->data["descripcion"][$li_s];
					  $ld_previsto=$io_report->dts_reporte->data["previsto"][$li_s];
					  $ld_aumento=$io_report->dts_reporte->data["aumento"][$li_s];
					  $ld_disminucion=$io_report->dts_reporte->data["disminucion"][$li_s];
					  $ld_devengado=$io_report->dts_reporte->data["devengado"][$li_s];
					  $ld_cobrado=$io_report->dts_reporte->data["cobrado"][$li_s];
					  $ld_cobrado_anticipado=$io_report->dts_reporte->data["cobrado_anticipado"][$li_s];
					  $ls_tipo_destino=$io_report->dts_reporte->data["tipo_destino"][$li_s];
					  $ls_cod_pro=$io_report->dts_reporte->data["cod_pro"][$li_s];
					  $ls_nompro=$io_report->dts_reporte->data["nompro"][$li_s];
					  $ls_nombene=$io_report->dts_reporte->data["nombene"][$li_s];
					  $ls_operacion=$io_report->dts_reporte->data["operacion"][$li_s];
					  $ld_monto_actualizado=($ld_previsto+$ld_aumento-$ld_disminucion)-$ld_devengado;
					  $ld_monto_actualizado_aux=$ld_monto_actualizado;
					  if(($ls_operacion=="DEV")or($ls_operacion=="COB")or($ls_operacion=="DC"))
					  {
						  $ld_monto_actualizado=0;
					  }
					  $ld_por_cobrar=$ld_devengado-$ld_cobrado;
					  
					  $ld_total_previsto=$ld_total_previsto+$ld_previsto;
					  $ld_total_aumento=$ld_total_aumento+$ld_aumento;
					  $ld_total_disminucion=$ld_total_disminucion+$ld_disminucion;
					  $ld_total_devengado=$ld_total_devengado+$ld_devengado;
					  $ld_total_cobrado=$ld_total_cobrado+$ld_cobrado;
					  $ld_total_cobrado_anticipado=$ld_total_cobrado_anticipado+$ld_cobrado_anticipado;
					  $ld_total_monto_actualizado=$ld_total_monto_actualizado+$ld_monto_actualizado;
					  $ld_total_por_cobrar=$ld_total_por_cobrar+$ld_por_cobrar;
					 
					  $ld_sub_total_previsto=$ld_sub_total_previsto+$ld_previsto;
					  $ld_sub_total_aumento=$ld_sub_total_aumento+$ld_aumento;
					  $ld_sub_total_disminucion=$ld_sub_total_disminucion+$ld_disminucion;
					  $ld_sub_total_devengado=$ld_sub_total_devengado+$ld_devengado;
					  $ld_sub_total_cobrado=$ld_sub_total_cobrado+$ld_cobrado;
					  $ld_sub_total_cobrado_anticipado=$ld_sub_total_cobrado_anticipado+$ld_cobrado_anticipado;
					  $ld_sub_total_monto_actualizado=$ld_sub_total_monto_actualizado+$ld_monto_actualizado_aux;
					  $ld_sub_total_por_cobrar=$ld_sub_total_por_cobrar+$ld_por_cobrar;
					  $la_data[$li_h]=array('fecha'=>$ls_fecha,'comprobante'=>$ls_comprobante,'documento'=>$ls_documento,
											'detalle'=>$ls_descripcion,'previsto'=>number_format($ld_previsto,2,',','.'),'aumento'=>number_format($ld_aumento,2,',','.'),
											'disminución'=>number_format($ld_disminucion,2,',','.'),'montoactualizado'=>number_format($ld_monto_actualizado,2,',','.'),
											'devengado'=>number_format($ld_devengado,2,',','.'),'cobrado'=>number_format($ld_cobrado,2,',','.'),
											'cobrado_anticipado'=>number_format($ld_cobrado_anticipado,2,',','.'),'porcobrar'=>number_format($ld_por_cobrar,2,',','.'));
					 $ld_por_devengar=$ld_sub_total_previsto+$ld_sub_total_aumento+$ld_sub_total_disminucion;
					 $ld_saldo_por_devengar=$ld_por_devengar-$ld_sub_total_devengado;
					 $ld_subtotal_previsto=$ld_sub_total_previsto;
					 $ld_subtotal_aumento=$ld_sub_total_aumento;
					 $ld_subtotal_disminucion=$ld_sub_total_disminucion;
					 $ld_subtotal_devengado=$ld_sub_total_devengado;
					 $ld_subtotal_cobrado=$ld_sub_total_cobrado;
					 $ld_subtotal_cobrado_anticipado=$ld_sub_total_cobrado_anticipado;
					 $ld_subtotal_monto_actualizado=$ld_sub_total_monto_actualizado;
					 $ld_subtotal_por_cobrar=$ld_sub_total_por_cobrar;
				}
			} // end for($li_s)
          	 uf_print_cabecera_detalle($ls_cuentaimprimir,$ls_denominacion,$io_pdf);
			 uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
			 uf_print_pie_cabecera(number_format($ld_saldo_por_devengar,2,',','.'),
			                       number_format($ld_sub_total_previsto,2,',','.'),
								   number_format($ld_sub_total_aumento,2,',','.'),
			                       number_format($ld_sub_total_disminucion,2,',','.'),
								   number_format($ld_sub_total_monto_actualizado,2,',','.'),
								   number_format($ld_sub_total_devengado,2,',','.'),
								   number_format($ld_sub_total_cobrado,2,',','.'),
								   number_format($ld_sub_total_cobrado_anticipado,2,',','.'),
								   number_format($ld_sub_total_por_cobrar,2,',','.'),$io_pdf);
			
		}// end for($li_z)
		/*for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
		{
		  $li_tmp=($li_s+1);
		  $io_pdf->transaction('start'); // Iniciamos la transacción
		  $thisPageNum=$io_pdf->ezPageCount;
		  $ls_spi_cuenta=$io_report->dts_reporte->data["spi_cuenta"][$li_s];
		  
		  if ($li_s<$li_totrow_det)
		  {
				$ls_spi_cuenta_next=$io_report->dts_reporte->data["spi_cuenta"][$li_tmp];  
		  }
		  elseif($li_s==$li_totrow_det)
		  {
				$ls_spi_cuenta_next='no_next'; 
		  }
		  if(empty($ls_spi_cuenta_next)&&(!empty($ls_spi_cuenta)))
		  {
		     $ls_spi_cuenta_ant=$io_report->dts_reporte->data["spi_cuenta"][$li_s];
		  }
		  if($li_totrow_det==1)
		  {
		     $ls_spi_cuenta_ant=$io_report->dts_reporte->data["spi_cuenta"][$li_s];
		  }
		  print "CUENTA->".$ls_spi_cuenta." NEXT->".$ls_spi_cuenta_next."<br>";
		  $ls_denominacion=$io_report->dts_reporte->data["denominacion"][$li_s];
		  $fecha=$io_report->dts_reporte->data["fecha"][$li_s];
		  $ls_fecha=$io_funciones->uf_convertirfecmostrar($fecha);
		  $ls_procede=$io_report->dts_reporte->data["procede"][$li_s];
		  $ls_procede_doc=$io_report->dts_reporte->data["procede_doc"][$li_s];
		  $ls_comprobante=$io_report->dts_reporte->data["comprobante"][$li_s];
		  $ls_documento=$io_report->dts_reporte->data["documento"][$li_s];
		  $ls_descripcion=$io_report->dts_reporte->data["descripcion"][$li_s];
		  $ld_previsto=$io_report->dts_reporte->data["previsto"][$li_s];
		  $ld_aumento=$io_report->dts_reporte->data["aumento"][$li_s];
		  $ld_disminucion=$io_report->dts_reporte->data["disminucion"][$li_s];
		  $ld_devengado=$io_report->dts_reporte->data["devengado"][$li_s];
		  $ld_cobrado=$io_report->dts_reporte->data["cobrado"][$li_s];
		  $ld_cobrado_anticipado=$io_report->dts_reporte->data["cobrado_anticipado"][$li_s];
		  $ls_tipo_destino=$io_report->dts_reporte->data["tipo_destino"][$li_s];
		  $ls_cod_pro=$io_report->dts_reporte->data["cod_pro"][$li_s];
		  $ls_nompro=$io_report->dts_reporte->data["nompro"][$li_s];
		  $ls_nombene=$io_report->dts_reporte->data["nombene"][$li_s];
		  $ls_operacion=$io_report->dts_reporte->data["operacion"][$li_s];
          $ld_monto_actualizado=($ld_previsto+$ld_aumento-$ld_disminucion)-$ld_devengado;
		  $ld_monto_actualizado_aux=$ld_monto_actualizado;
		  if(($ls_operacion=="DEV")or($ls_operacion=="COB")or($ls_operacion=="DC"))
		  {
		      $ld_monto_actualizado=0;
		  }
		  $ld_por_cobrar=$ld_devengado-$ld_cobrado;
		  
		  $ld_total_previsto=$ld_total_previsto+$ld_previsto;
		  $ld_total_aumento=$ld_total_aumento+$ld_aumento;
		  $ld_total_disminucion=$ld_total_disminucion+$ld_disminucion;
		  $ld_total_devengado=$ld_total_devengado+$ld_devengado;
		  $ld_total_cobrado=$ld_total_cobrado+$ld_cobrado;
		  $ld_total_cobrado_anticipado=$ld_total_cobrado_anticipado+$ld_cobrado_anticipado;
		  $ld_total_monto_actualizado=$ld_total_monto_actualizado+$ld_monto_actualizado;
		  $ld_total_por_cobrar=$ld_total_por_cobrar+$ld_por_cobrar;
		 
		  $ld_sub_total_previsto=$ld_sub_total_previsto+$ld_previsto;
		  $ld_sub_total_aumento=$ld_sub_total_aumento+$ld_aumento;
		  $ld_sub_total_disminucion=$ld_sub_total_disminucion+$ld_disminucion;
		  $ld_sub_total_devengado=$ld_sub_total_devengado+$ld_devengado;
		  $ld_sub_total_cobrado=$ld_sub_total_cobrado+$ld_cobrado;
		  $ld_sub_total_cobrado_anticipado=$ld_sub_total_cobrado_anticipado+$ld_cobrado_anticipado;
		  $ld_sub_total_monto_actualizado=$ld_sub_total_monto_actualizado+$ld_monto_actualizado_aux;
		  $ld_sub_total_por_cobrar=$ld_sub_total_por_cobrar+$ld_por_cobrar;
		  
		  
		 if (!empty($ls_spi_cuenta))
		  {
			  $la_data[$li_s]=array('fecha'=>$ls_fecha,'comprobante'=>$ls_comprobante,'documento'=>$ls_documento,
									'detalle'=>$ls_descripcion,'previsto'=>number_format($ld_previsto,2,',','.'),'aumento'=>number_format($ld_aumento,2,',','.'),
									'disminución'=>number_format($ld_disminucion,2,',','.'),'montoactualizado'=>number_format($ld_monto_actualizado,2,',','.'),
									'devengado'=>number_format($ld_devengado,2,',','.'),'cobrado'=>number_format($ld_cobrado,2,',','.'),
									'cobrado_anticipado'=>number_format($ld_cobrado_anticipado,2,',','.'),'porcobrar'=>number_format($ld_por_cobrar,2,',','.'));
			 
		  }
		  else
		  {
			  $la_data[$li_s]=array('fecha'=>$ls_fecha,'comprobante'=>$ls_comprobante,'documento'=>$ls_documento,
									'detalle'=>$ls_descripcion,'previsto'=>number_format($ld_previsto,2,',','.'),'aumento'=>number_format($ld_aumento,2,',','.'),
									'disminución'=>number_format($ld_disminucion,2,',','.'),'montoactualizado'=>number_format($ld_monto_actualizado,2,',','.'),
									'devengado'=>number_format($ld_devengado,2,',','.'),'cobrado'=>number_format($ld_cobrado,2,',','.'),
									'cobrado_anticipado'=>number_format($ld_cobrado_anticipado,2,',','.'),'porcobrar'=>number_format($ld_por_cobrar,2,',','.'));
		  }
		  if (!empty($ls_spi_cuenta_next))
		  {
			  $la_data[$li_s]=array('fecha'=>$ls_fecha,'comprobante'=>$ls_comprobante,'documento'=>$ls_documento,
									'detalle'=>$ls_descripcion,'previsto'=>number_format($ld_previsto,2,',','.'),'aumento'=>number_format($ld_aumento,2,',','.'),
									'disminución'=>number_format($ld_disminucion,2,',','.'),'montoactualizado'=>number_format($ld_monto_actualizado,2,',','.'),
									'devengado'=>number_format($ld_devengado,2,',','.'),'cobrado'=>number_format($ld_cobrado,2,',','.'),
								    'cobrado_anticipado'=>number_format($ld_cobrado_anticipado,2,',','.'),'porcobrar'=>number_format($ld_por_cobrar,2,',','.'));
          	 uf_print_cabecera_detalle($ls_spi_cuenta_ant,$ls_denominacion,$io_pdf);
			 uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		     $ld_por_devengar=$ld_sub_total_previsto+$ld_sub_total_aumento+$ld_sub_total_disminucion;
			 $ld_saldo_por_devengar=$ld_por_devengar-$ld_sub_total_devengado;
			 $ld_subtotal_previsto=$ld_sub_total_previsto;
			 $ld_subtotal_aumento=$ld_sub_total_aumento;
			 $ld_subtotal_disminucion=$ld_sub_total_disminucion;
			 $ld_subtotal_devengado=$ld_sub_total_devengado;
			 $ld_subtotal_cobrado=$ld_sub_total_cobrado;
			 $ld_subtotal_cobrado_anticipado=$ld_sub_total_cobrado_anticipado;
			 $ld_subtotal_monto_actualizado=$ld_sub_total_monto_actualizado;
			 $ld_subtotal_por_cobrar=$ld_sub_total_por_cobrar;
			 
			 uf_print_pie_cabecera(number_format($ld_saldo_por_devengar,2,',','.'),
			                       number_format($ld_sub_total_previsto,2,',','.'),
								   number_format($ld_sub_total_aumento,2,',','.'),
			                       number_format($ld_sub_total_disminucion,2,',','.'),
								   number_format($ld_sub_total_monto_actualizado,2,',','.'),
								   number_format($ld_sub_total_devengado,2,',','.'),
								   number_format($ld_sub_total_cobrado,2,',','.'),
								   number_format($ld_sub_total_cobrado_anticipado,2,',','.'),
								   number_format($ld_sub_total_por_cobrar,2,',','.'),$io_pdf);
		    
			$ld_sub_total_previsto=0;
			$ld_sub_total_aumento=0;
			$ld_sub_total_disminucion=0;
			$ld_sub_total_devengado=0;
			$ld_sub_total_cobrado=0;
			$ld_sub_total_cobrado_anticipado=0;
			$ld_sub_total_monto_actualizado=0;
			$ld_sub_total_por_cobrar=0;
			 if ($io_pdf->ezPageCount==$thisPageNum)
			 {// Hacemos el commit de los registros que se desean imprimir
				$io_pdf->transaction('commit');
			 }
			 elseif($thisPageNum>1)
			 {// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
				 $io_pdf->transaction('rewind');
				 $io_pdf->ezNewPage(); // Insertar una nueva página
				 uf_print_cabecera_detalle($ls_spi_cuenta_ant,$ls_denominacion,$io_pdf);
				 uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				 $ld_por_devengar=$ld_sub_total_previsto+$ld_sub_total_aumento+$ld_sub_total_disminucion;
				 $ld_saldo_por_devengar=$ld_por_devengar-$ld_sub_total_devengado;
				 uf_print_pie_cabecera(number_format($ld_saldo_por_devengar,2,',','.'),
                                       number_format($ld_subtotal_previsto,2,',','.'),
									   number_format($ld_subtotal_aumento,2,',','.'),
				                       number_format($ld_subtotal_disminucion,2,',','.'),
									   number_format($ld_subtotal_monto_actualizado,2,',','.'),
									   number_format($ld_subtotal_devengado,2,',','.'),
									   number_format($ld_subtotal_cobrado,2,',','.'),
									   number_format($ld_subtotal_cobrado_anticipado,2,',','.'),
									   number_format($ld_subtotal_por_cobrar,2,',','.'),$io_pdf);
				
				$ld_subtotal_previsto=0;
				$ld_subtotal_aumento=0;
				$ld_subtotal_disminucion=0;
				$ld_subtotal_devengado=0;
				$ld_subtotal_cobrado=0;
				$ld_subtotal_cobrado_anticipado=0;
				$ld_subtotal_monto_actualizado=0;
				$ld_subtotal_por_cobrar=0;
			 }
			unset($la_data1);			
		  }//if		
		}*///for
		$io_pdf->ezStopPageNumbers(1,1);
		if (isset($d) && $d)
		{
			$ls_pdfcode = $io_pdf->ezOutput(1);
		  	$ls_pdfcode = str_replace("\n","\n<br>",htmlspecialchars($ls_pdfcode));
		  	echo '<html><body>';
		  	echo trim($ls_pdfcode);
		  	echo '</body></html>';
		}
		else
		{
			$io_pdf->ezStream();
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_function_report);
?> 