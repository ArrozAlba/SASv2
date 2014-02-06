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
	// Fecha Creación: 10/05/2006 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$io_encabezado=$io_pdf->openObject();
	$io_pdf->saveState();
	$io_pdf->line(10,30,1000,30);
	$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],10,550,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
	
	$li_tm=$io_pdf->getTextWidth(16,$as_titulo);
	$tm=505-($li_tm/2);
	$io_pdf->addText($tm,550,16,$as_titulo); // Agregar el título
	
	$io_pdf->addText(900,550,10,date("d/m/Y")); // Agregar la Fecha
	$io_pdf->addText(900,540,10,date("h:i a")); // Agregar la hora
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');
}// end function uf_print_encabezadopagina
//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_cabecera($as_programatica,$as_denestpro,&$io_pdf)
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
	$io_pdf->ezSetDy(-10);
	$ls_estpro1=substr($as_programatica,5,10);
	$ls_estpro2=substr($as_programatica,44,6);
	$ls_estpro3=substr($as_programatica,73,2);
	$ls_programatica=$ls_estpro1."   -   ".$ls_estpro2."   -   ".$ls_estpro3;
	$la_data=array(array('name'=>'<b>Programatica</b>  '.$ls_programatica.''),
				   array('name'=>'<b></b> '.$as_denestpro.''));
	$la_columna=array('name'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
					 'showLines'=>0, // Mostrar Líneas
					 'shaded'=>2, // Sombra entre líneas
					 'fontSize' => 8, // Tamaño de Letras
					 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
					 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
					 'xOrientation'=>'center', // Orientación de la tabla
					 'xPos'=>510, // Orientación de la tabla
					 'width'=>990, // Ancho de la tabla
					 'maxWidth'=>990); // Ancho Máximo de la tabla
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
}// end function uf_print_cabecera
//--------------------------------------------------------------------------------------------------------------------------------

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
	//	   Creado Por: Ing.Yozelin Barragán
	// Fecha Creación: 21/04/2006 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$io_pdf->ezSetDy(-10);
	$la_data=array(array('name'=>'<b>Cuenta</b>  '.$as_spg_cuenta.'  ---  '.$as_denominacion.''));
	$la_columna=array('name'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
					 'showLines'=>0, // Mostrar Líneas
					 'shaded'=>2, // Sombra entre líneas
					 'fontSize' => 8, // Tamaño de Letras
					 'shadeCol'=>array(0.9,0.9,0.9),
					 'shadeCol'=>array(0.9,0.9,0.9),
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
	// Fecha Creación: 10/05/2006 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$la_config=array('showHeadings'=>1, // Mostrar encabezados
					 'fontSize' => 7, // Tamaño de Letras
					 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
					 'showLines'=>2, // Mostrar Líneas
					 'shaded'=>0, // Sombra entre líneas
					 'colGap'=>1, // separacion entre tablas
					 'width'=>990, // Ancho de la tabla
					 'maxWidth'=>990, // Ancho Máximo de la tabla
					 'xOrientation'=>'center', // Orientación de la tabla
					 'xPos'=>502, // Orientación de la tabla
					 'cols'=>array('fecha'=>array('justification'=>'center','width'=>38), // Justificación y ancho de la columna
								   'comprobante'=>array('justification'=>'left','width'=>68), // Justificación y ancho de la columna
								   'documento'=>array('justification'=>'left','width'=>68), // Justificación y ancho de la columna
								   'detalle'=>array('justification'=>'left','width'=>88), // Justificación y ancho de la columna
                                   'proben'=>array('justification'=>'center','width'=>78), // Justificación y ancho de la columna
								   'asignado'=>array('justification'=>'right','width'=>71), // Justificación y ancho de la columna
								   'aumento'=>array('justification'=>'right','width'=>71), // Justificación y ancho de la columna
								   'disminución'=>array('justification'=>'right','width'=>71), // Justificación y ancho de la 
								   'montoactualizado'=>array('justification'=>'right','width'=>71), // Justificación y ancho de la 
								   'precomprometido'=>array('justification'=>'right','width'=>71), // Justificación y ancho de la 
								   'comprometido'=>array('justification'=>'right','width'=>71), // Justificación y ancho de la 
								   'causado'=>array('justification'=>'right','width'=>71), // Justificación y ancho de la columna
								   'pagado'=>array('justification'=>'right','width'=>71), // Justificación y ancho de la columna
								   'porpagar'=>array('justification'=>'right','width'=>71))); // Justificación y ancho de la columna
	
	$la_columnas=array('fecha'=>'<b>Fecha</b>',
					   'comprobante'=>'<b>Comprobante</b>',
					   'documento'=>'<b>Documento</b>',
					   'detalle'=>'<b>Detalle</b>',
					   'proben'=>'<b>Prov/Benef.</b>',
					   'asignado'=>'<b>Asignado</b>',
					   'aumento'=>'<b>Aumento</b>',
					   'disminución'=>'<b>Disminución</b>',
					   'montoactualizado'=>'<b>Monto Actualizado</b>',
					   'precomprometido'=>'<b>Pre Comprometido</b>',
		 			   'comprometido'=>'<b>Comprometido</b>',
					   'causado'=>'<b>Causado</b>',
					   'pagado'=>'<b>Pagado</b>',
					   'porpagar'=>'<b>Por Pagar</b>');
	$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
}// end function uf_print_detalle
//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($la_data_tot,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private 
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 10/05/2006 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>990,//Ancho de la tabla
						 'xPos'=>502, // Orientación de la tabla
						 'maxWidth'=>990); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>180), // Justificación y ancho de la columna
									   'saldoporcomprometer'=>array('justification'=>'right','width'=>90), // Justificación y ancho 
									   'asignado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
						 			   'aumento'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
									   'disminución'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
									   'montoactualizado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de 
									   'precomprometido'=>array('justification'=>'right','width'=>80), // Justificación y ancho de 
									   'comprometido'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
									   'causado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
									   'pagado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'porpagar'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la 
		
		$la_columnas=array('total'=>'',
						   'saldoporcomprometer'=>'',
						   'asignado'=>'',
						   'aumento'=>'',
						   'disminución'=>'',
						   'montoactualizado'=>'',
						   'precomprometido'=>'',
						   'comprometido'=>'',
						   'causado'=>'',
						   'pagado'=>'',
						   'porpagar'=>'');
		$io_pdf->ezTable($la_data_tot,$la_columnas,'',$la_config);
		
	}// end function uf_print_pie_cabecera
//--------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("sigesp_spg_reporte_1.php");
		$io_report = new sigesp_spg_reporte_1();
		require_once("sigesp_spg_funciones_reportes.php");
		$io_function_report = new sigesp_spg_funciones_reportes();
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();			
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
//--------------------------------------------------  Parámetros para Filtar el Reporte  ---------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		$ls_codestpro1_min  = $_GET["codestpro1"];
		$ls_codestpro2_min  = $_GET["codestpro2"];
		$ls_codestpro3_min  = $_GET["codestpro3"];
		$ls_codestpro1h_max = $_GET["codestpro1h"];
		$ls_codestpro2h_max = $_GET["codestpro2h"];
		$ls_codestpro3h_max = $_GET["codestpro3h"];
		if($li_estmodest==1)
		{
			$ls_codestpro4_min = "00";
			$ls_codestpro5_min = "00";
			$ls_codestpro4h_max = "00";
			$ls_codestpro5h_max = "00";
			if(($ls_codestpro1_min=="")&&($ls_codestpro2_min=="")&&($ls_codestpro3_min==""))
			{
			  if($io_function_report->uf_spg_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,                                                                             $ls_codestpro3_min,$ls_codestpro4_min,
			                                                                 $ls_codestpro5_min))
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
					$ls_codestpro1_min=$io_funciones->uf_cerosizquierda($ls_codestpro1_min,25);
					$ls_codestpro2_min=$io_funciones->uf_cerosizquierda($ls_codestpro2_min,25);
					$ls_codestpro3_min=$io_funciones->uf_cerosizquierda($ls_codestpro3_min,25);
					$ls_codestpro4_min=$io_funciones->uf_cerosizquierda($ls_codestpro4_min,25);
					$ls_codestpro5_min=$io_funciones->uf_cerosizquierda($ls_codestpro5_min,25);
					
					$ls_codestpro1  = $ls_codestpro1_min;
					$ls_codestpro2  = $ls_codestpro2_min;
					$ls_codestpro3  = $ls_codestpro3_min;
					$ls_codestpro4  = $ls_codestpro4_min;
					$ls_codestpro5  = $ls_codestpro5_min;
			}
			if(($ls_codestpro1h_max=="")&&($ls_codestpro2h_max=="")&&($ls_codestpro3h_max==""))
			{
			  if($io_function_report->uf_spg_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
																			 $ls_codestpro3h_max,$ls_codestpro4h_max,
																			 $ls_codestpro4h_max))
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
					$ls_codestpro1h_max = $io_funciones->uf_cerosizquierda($ls_codestpro1h_max,25);
					$ls_codestpro2h_max = $io_funciones->uf_cerosizquierda($ls_codestpro2h_max,25);
					$ls_codestpro3h_max = $io_funciones->uf_cerosizquierda($ls_codestpro3h_max,25);
					$ls_codestpro4h_max = $io_funciones->uf_cerosizquierda($ls_codestpro4h_max,25);
					$ls_codestpro5h_max = $io_funciones->uf_cerosizquierda($ls_codestpro5h_max,25);
					
					$ls_codestpro1h  = $ls_codestpro1h_max;
					$ls_codestpro2h  = $ls_codestpro2h_max;
					$ls_codestpro3h  = $ls_codestpro3h_max;
					$ls_codestpro4h  = $ls_codestpro4h_max;
					$ls_codestpro5h  = $ls_codestpro5h_max;
			}
		}
		elseif($li_estmodest==2)
		{
			$ls_codestpro4_min = $_GET["codestpro4"];
			$ls_codestpro5_min = $_GET["codestpro5"];
			$ls_codestpro4h_max = $_GET["codestpro4h"];
			$ls_codestpro5h_max = $_GET["codestpro5h"];
			if(($ls_codestpro1_min=="")&&($ls_codestpro2_min=="")&&($ls_codestpro3_min=="")&&($ls_codestpro4_min=="")&&
			   ($ls_codestpro5_min==""))
			{
			  if($io_function_report->uf_spg_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,                                                                             $ls_codestpro3_min,$ls_codestpro4_min,
			                                                                 $ls_codestpro5_min))
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
			if(($ls_codestpro1h_max=="")&&($ls_codestpro2h_max=="")&&($ls_codestpro3h_max=="")&&($ls_codestpro4h_max=="")&&
			   ($ls_codestpro5h_max==""))
			{
			  if($io_function_report->uf_spg_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
																			 $ls_codestpro3h_max,$ls_codestpro4h_max,
																			 $ls_codestpro4h_max))
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
		
		$ls_programatica_desde=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
		$ls_programatica_hasta=$ls_codestpro1h.$ls_codestpro2h.$ls_codestpro3h.$ls_codestpro4h.$ls_codestpro5h;
		/////////////////////////////////         SEGURIDAD               ///////////////////////////////////
		$ls_desc_event="Solicitud de Reporte Mayor Analitico desde la fecha ".$fecdes." hasta ".$fechas." ,Desde la programatica ".$ls_programatica_desde."  hasta ".$ls_programatica_hasta." , Desde la Cuenta ".$ls_cuentades." hasta la ".$ls_cuentahas;
		$io_function_report->uf_load_seguridad_reporte("SPG","sigesp_spg_r_mayor_analitico.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               ///////////////////////////////////
		//----------------------------------------------------  Parámetros del encabezado  ---------------------------------------------
		$ls_titulo=" <b>MAYOR ANALITICO  DESDE  ".$fecdes."  AL  ".$fechas." </b>"; 
		//--------------------------------------------------------------------------------------------------------------------------------
   		// Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
     $lb_valido=$io_report->uf_spg_reporte_select_mayor_analitico($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
	                                                              $ls_codestpro5,$ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,
																  $ls_codestpro4h,$ls_codestpro5h,$ldt_fecdes,$ldt_fechas,
																  $ls_cuentades,$ls_cuentahas,$ls_orden);
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
	 if($lb_valido==false) // Existe algún error ó no hay registros
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		//print(" close();");
		print("</script>");
	 }
	 else // Imprimimos el reporte
	 {
	    error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
        $io_pdf->ezStartPageNumbers(980,40,10,'','',1); // Insertar el número de página
		$li_tot=$io_report->dts_cab->getRowCount("programatica");
		for($z=1;$z<=$li_tot;$z++)
		{
			$ld_total_asignado=0;
			$ld_total_aumento=0;
			$ld_total_disminucion=0;
			$ld_total_monto_actualizado=0;
			$ld_total_compromiso=0;
			$ld_total_precompromiso=0;
			$ld_total_compromiso=0;
			$ld_total_causado=0;
			$ld_total_pagado=0;
			$ld_total_por_paga=0;
            $ld_total_saldo_comprometer=0;
			$thisPageNum=$io_pdf->ezPageCount;
			$ls_programatica=$io_report->dts_cab->data["programatica"][$z];
		    $ls_codestpro1=substr($ls_programatica,0,25);
		    $ls_denestpro1="";
		    $lb_valido=$io_report->uf_spg_reporte_select_denestpro1($ls_codestpro1,$ls_denestpro1);
		
		    if($lb_valido)
		    {
			  $ls_denestpro1=$ls_denestpro1;
		    }
		    $ls_codestpro2=substr($ls_programatica,25,25);
		    if($lb_valido)
		    {
			  $ls_denestpro2="";
			  $lb_valido=$io_report->uf_spg_reporte_select_denestpro2($ls_codestpro1,$ls_codestpro2,$ls_denestpro2);
			  $ls_denestpro2=$ls_denestpro2;
		    }
		    $ls_codestpro3=substr($ls_programatica,50,25);
		    if($lb_valido)
		    {
			  $ls_denestpro3="";
			  $lb_valido=$io_report->uf_spg_reporte_select_denestpro3($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_denestpro3);
			  $ls_denestpro3=$ls_denestpro3;
		    }
			if($li_estmodest==2)
			{
				$ls_codestpro4=substr($ls_programatica,75,25);
				if($lb_valido)
				{
				  $ls_denestpro4="";
				  $lb_valido=$io_report->uf_spg_reporte_select_denestpro4($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_denestpro4);
				  $ls_denestpro4=$ls_denestpro4;
				}
				$ls_codestpro5=substr($ls_programatica,100,25);
				if($lb_valido)
				{
				  $ls_denestpro5="";
				  $lb_valido=$io_report->uf_spg_reporte_select_denestpro5($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_denestpro5);
				  $ls_denestpro5=$ls_denestpro5;
				}
			    $ls_denestpro=$ls_denestpro1." , ".$ls_denestpro2." , ".$ls_denestpro3." , ".$ls_denestpro4." , ".$ls_denestpro5;
			    $ls_programatica=substr($ls_codestpro1,-2).substr($ls_codestpro2,-2).substr($ls_codestpro3,-2).substr($ls_codestpro4,-2).substr($ls_codestpro5,-2);
			}
			else
			{
			    $ls_denestpro=$ls_denestpro1." , ".$ls_denestpro2." , ".$ls_denestpro3;
			}
			$lb_valido=$io_report->uf_spg_reporte_mayor_analitico( $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
			                                                       $ls_codestpro5,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																   $ls_codestpro4,$ls_codestpro5,$ldt_fecdes,$ldt_fechas,
																   $ls_cuentades,$ls_cuentahas,$ls_orden);
			if($lb_valido)
			{
			  $li_totrow_det=$io_report->dts_reporte->getRowCount("spg_cuenta");
			  
			   
			  /*if($li_totrow_det==0)
			  {
				 print("<script language=JavaScript>");
				 print(" alert('No hay nada que Reportar 22222 ');"); 
				 //print(" close();");
				 print("</script>");
			  }
			  else
			  {*/
				if($li_totrow_det>1)
				{
		           uf_print_cabecera($ls_programatica,$ls_denestpro,$io_pdf); // Imprimimos la cabecera del registro
				}
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
				  $li_tmp=($li_s+1);
				  $ls_programatica=$io_report->dts_reporte->data["programatica"][$li_s];
				  $ls_nombre_prog=$io_report->dts_reporte->data["nombre_prog"][$li_s];
				  $ls_spg_cuenta=$io_report->dts_reporte->data["spg_cuenta"][$li_s];
				  if ($li_tmp<$li_totrow_det)
				  {
						$ls_spg_cuenta_next=$io_report->dts_reporte->data["spg_cuenta"][$li_tmp];  
				  }
				  elseif($li_tmp==$li_totrow_det)
				  {
				        $ls_spg_cuenta_next=$io_report->dts_reporte->data["spg_cuenta"][$li_s];  
				  }
				  $ls_denominacion=$io_report->dts_reporte->data["denominacion"][$li_s];
  				  $ls_provbef     =$io_report->dts_reporte->data["provbenef"][$li_s];				 
				  $fecha          =$io_report->dts_reporte->data["fecha"][$li_s];
				  $ls_fecha       =$io_funciones->uf_convertirfecmostrar($fecha);
				  $ls_procede     =$io_report->dts_reporte->data["procede"][$li_s];
				  $ls_procede_doc =$io_report->dts_reporte->data["procede_doc"][$li_s];
				  $ls_comprobante =$io_report->dts_reporte->data["comprobante"][$li_s];
				  $ls_documento   =$io_report->dts_reporte->data["documento"][$li_s];
				  $ls_descripcion =$io_report->dts_reporte->data["descripcion"][$li_s];
				  $ld_asignado    =$io_report->dts_reporte->data["asignado"][$li_s];
				  $ld_aumento     =$io_report->dts_reporte->data["aumento"][$li_s];
				  $ld_disminucion =$io_report->dts_reporte->data["disminucion"][$li_s];
				  //$ld_monto_actualizado=$ld_asignado+$ld_aumento-$ld_disminucion;
				  $ld_monto_actualizado=$io_report->dts_reporte->data["monto_actualizado"][$li_s];
				  $ld_precompromiso    =$io_report->dts_reporte->data["precompromiso"][$li_s];
				  $ld_compromiso       =$io_report->dts_reporte->data["compromiso"][$li_s];
				  $ld_causado          =$io_report->dts_reporte->data["causado"][$li_s];
				  $ld_pagado           =$io_report->dts_reporte->data["pagado"][$li_s];
				  //$ld_por_comprometer=$io_report->dts_reporte->data["por_comprometer"][$li_s];
				  //$ls_tipo_destino=$io_report->dts_reporte->data["tipo_destino"][$li_s];
				  //$ls_cod_pro=$io_report->dts_reporte->data["cod_pro"][$li_s];
				  //$ls_nompro=$io_report->dts_reporte->data["nompro"][$li_s];
				  //$ls_ben_nombre=$io_report->dts_reporte->data["ben_nombre"][$li_s];
				 
				  $ld_por_paga         = $ld_causado-$ld_pagado;
				  $ld_total_asignado   = $ld_total_asignado+$ld_asignado;
				  $ld_total_aumento    = $ld_total_aumento+$ld_aumento;
				  $ld_total_disminucion=$ld_total_disminucion+$ld_disminucion;
				  $ld_total_precompromiso=$ld_total_precompromiso+$ld_precompromiso;
				  $ld_total_compromiso = $ld_total_compromiso+$ld_compromiso;
				  $ld_total_causado    = $ld_total_causado+$ld_causado;
				  $ld_total_pagado     = $ld_total_pagado+$ld_pagado;
				  $ld_total_por_paga   = $ld_total_por_paga+$ld_por_paga;
				 
				  //$ld_total_saldo_comprometer=$ld_total_saldo_comprometer+$ld_por_comprometer;
				  $ld_por_comprometer=$ld_monto_actualizado-$ld_precompromiso-$ld_compromiso;
				  
				  //$ld_total_saldo_comprometer=$ld_total_saldo_comprometer+$ld_por_comprometer;	 Cambiado por Ing. Nelson Barraez 20-12-2006			  
				  
				  if(($ls_spg_cuenta!=$ls_spg_cuenta_next)&&($li_s!=$li_totrow_det))
			      {
					  $ld_total_monto_actualizado=$ld_total_monto_actualizado+$ld_monto_actualizado;// Agregado por Ing. Nelson Barraez 20-12-2006			  
					  $ld_asignado        =number_format($ld_asignado,2,",",".");
					  $ld_aumento         =number_format($ld_aumento,2,",",".");
					  $ld_disminucion     =number_format($ld_disminucion,2,",",".");
					  $ld_monto_actualizado=number_format($ld_monto_actualizado,2,",",".");
					  $ld_precompromiso   =number_format($ld_precompromiso,2,",",".");
					  $ld_compromiso      =number_format($ld_compromiso,2,",",".");
					  $ld_por_comprometer =number_format($ld_por_comprometer,2,",",".");
					  $ld_causado         =number_format($ld_causado,2,",",".");
					  $ld_pagado          =number_format($ld_pagado,2,",",".");
					  $ld_por_paga        =number_format($ld_por_paga,2,",",".");
						   
					  $la_data[$li_s]=array('fecha'=>$ls_fecha,'comprobante'=>$ls_comprobante,'documento'=>$ls_documento,
										    'detalle'=>$ls_descripcion,'proben'=>$ls_provbef,'asignado'=>$ld_asignado,'aumento'=>$ld_aumento,
										    'disminución'=>$ld_disminucion,'montoactualizado'=>$ld_monto_actualizado,
										    'precomprometido'=>$ld_precompromiso,'comprometido'=>$ld_compromiso,
										    'causado'=>$ld_causado,'pagado'=>$ld_pagado,'porpagar'=>$ld_por_paga);
	
					 uf_print_cabecera_detalle($ls_spg_cuenta,$ls_denominacion,$io_pdf);
					 uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					 unset($la_data);
				 }//if	 
				 else
				 {
					  $ld_asignado		=number_format($ld_asignado,2,",",".");
					  $ld_aumento		=number_format($ld_aumento,2,",",".");
					  $ld_disminucion	=number_format($ld_disminucion,2,",",".");
					  $ld_monto_actualizado=number_format($ld_monto_actualizado,2,",",".");
					  $ld_precompromiso	=number_format($ld_precompromiso,2,",",".");
					  $ld_compromiso	=number_format($ld_compromiso,2,",",".");
					  $ld_por_comprometer=number_format($ld_por_comprometer,2,",",".");
					  $ld_causado		=number_format($ld_causado,2,",",".");
					  $ld_pagado		=number_format($ld_pagado,2,",",".");
					  $ld_por_paga		=number_format($ld_por_paga,2,",",".");
						   
					  $la_data[$li_s]=array('fecha'=>$ls_fecha,'comprobante'=>$ls_comprobante,'documento'=>$ls_documento,
										    'detalle'=>$ls_descripcion,'proben'=>$ls_provbef,'asignado'=>$ld_asignado,'aumento'=>$ld_aumento,
										    'disminución'=>$ld_disminucion,'montoactualizado'=>$ld_monto_actualizado,
										    'precomprometido'=>$ld_precompromiso,'comprometido'=>$ld_compromiso,
										    'causado'=>$ld_causado,'pagado'=>$ld_pagado,'porpagar'=>$ld_por_paga);
					 $ld_asignado		=str_replace('.','',$ld_asignado);
					 $ld_asignado		=str_replace(',','.',$ld_asignado);		
					 $ld_aumento		=str_replace('.','',$ld_aumento);
					 $ld_aumento		=str_replace(',','.',$ld_aumento);		
					 $ld_disminucion	=str_replace('.','',$ld_disminucion);
					 $ld_disminucion	=str_replace(',','.',$ld_disminucion);		
					 $ld_monto_actualizado=str_replace('.','',$ld_monto_actualizado);
					 $ld_monto_actualizado=str_replace(',','.',$ld_monto_actualizado);	
					 $ld_precompromiso	=str_replace('.','',$ld_precompromiso);
					 $ld_precompromiso	=str_replace(',','.',$ld_precompromiso);		
					 $ld_compromiso		=str_replace('.','',$ld_compromiso);
					 $ld_compromiso		=str_replace(',','.',$ld_compromiso);		
					 $ld_por_comprometer=str_replace('.','',$ld_por_comprometer);
					 $ld_por_comprometer=str_replace(',','.',$ld_por_comprometer);		
					 $ld_causado		=str_replace('.','',$ld_causado);
					 $ld_causado		=str_replace(',','.',$ld_causado);
					 $ld_pagado			=str_replace('.','',$ld_pagado);
					 $ld_pagado			=str_replace(',','.',$ld_pagado);
					 $ld_por_paga		=str_replace('.','',$ld_por_paga);
					 $ld_por_paga		=str_replace(',','.',$ld_por_paga);
			    }//else
				if($li_s==$li_totrow_det)
				{
					  $ld_total_monto_actualizado=$ld_total_monto_actualizado+$ld_monto_actualizado;//Agregado por Ing. Nelson Barraez
					  $ld_asignado		=number_format($ld_asignado,2,",",".");
					  $ld_aumento		=number_format($ld_aumento,2,",",".");
					  $ld_disminucion	=number_format($ld_disminucion,2,",",".");
					  $ld_monto_actualizado=number_format($ld_monto_actualizado,2,",",".");
					  $ld_precompromiso	=number_format($ld_precompromiso,2,",",".");
					  $ld_compromiso	=number_format($ld_compromiso,2,",",".");
					  $ld_por_comprometer=number_format($ld_por_comprometer,2,",",".");
					  $ld_causado		=number_format($ld_causado,2,",",".");
					  $ld_pagado		=number_format($ld_pagado,2,",",".");
					  $ld_por_paga		=number_format($ld_por_paga,2,",",".");
					  $la_data[$li_s]=array('fecha'=>$ls_fecha,'comprobante'=>$ls_comprobante,'documento'=>$ls_documento,
										    'detalle'=>$ls_descripcion,'proben'=>$ls_provbef,'asignado'=>$ld_asignado,'aumento'=>$ld_aumento,
										    'disminución'=>$ld_disminucion,'montoactualizado'=>$ld_monto_actualizado,
										    'precomprometido'=>$ld_precompromiso,'comprometido'=>$ld_compromiso,
										    'causado'=>$ld_causado,'pagado'=>$ld_pagado,'porpagar'=>$ld_por_paga);
					 uf_print_cabecera_detalle($ls_spg_cuenta,$ls_denominacion,$io_pdf);
					 uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		 			 $ld_total_saldo_comprometer=$ld_total_monto_actualizado-$ld_total_precompromiso-$ld_total_compromiso;//Agregado por Ing. Nelson Barraez 20-12-2006
					 $ld_total_asignado		=number_format($ld_total_asignado,2,",",".");
				     $ld_total_aumento		=number_format($ld_total_aumento,2,",",".");
				     $ld_total_disminucion	=number_format($ld_total_disminucion,2,",",".");
				     $ld_total_monto_actualizado=number_format($ld_total_monto_actualizado,2,",",".");
				     $ld_total_precompromiso=number_format($ld_total_precompromiso,2,",",".");
				     $ld_total_compromiso	=number_format($ld_total_compromiso,2,",",".");
				     $ld_total_saldo_comprometer=number_format($ld_total_saldo_comprometer,2,",",".");
				     $ld_total_causado		=number_format($ld_total_causado,2,",",".");
				     $ld_total_pagado		=number_format($ld_total_pagado,2,",",".");
				     $ld_total_por_paga		=number_format($ld_total_por_paga,2,",",".");


				     $la_data_tot[$li_s]=array('total'=>'<b>SALDO POR COMPROMETER</b>',
					                           'saldoporcomprometer'=>$ld_total_saldo_comprometer,
											   'asignado'=>$ld_total_asignado,'aumento'=>$ld_total_aumento,
											   'disminución'=>$ld_total_disminucion,'montoactualizado'=>$ld_total_monto_actualizado,
											   'precomprometido'=>$ld_total_precompromiso,'comprometido'=>$ld_total_compromiso,
											   'causado'=>$ld_total_causado,'pagado'=>$ld_total_pagado,
											   'porpagar'=>$ld_total_por_paga);
				     uf_print_pie_cabecera($la_data_tot,$io_pdf);		
					 unset($la_data);	
					 unset($la_data_tot);			
				}//if
			}//for
		  // }//else
          }//if
		 unset($la_data);			
		}//for
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
	}//else
	unset($io_report);
	unset($io_funciones);	
	unset($io_function_report);		
?> 