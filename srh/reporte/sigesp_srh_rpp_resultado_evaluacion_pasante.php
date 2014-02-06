<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Listado de Resultado Evaluación por Pasantes
//  ORGANISMO: IPSFA
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 05/03/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_srh;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_relacionsolicitudes.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo// Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 05/03/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(15,40,585,40);
        
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		

		$io_pdf->addText(540,770,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(546,764,6,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');

	    $io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetY(670);
		$la_data=array(array('titulo1'=>'<b>'.$as_titulo.'</b>'));
					
		$la_columnas=array('titulo1'=>'');
					

		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('titulo1'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
	    $io_pdf->ezSetY(650);
		$la_data[1]=array('codper'=>'<b>Código del Personal</b>',
		                     'nombper'=>'<b>Nombre y Apellido</b>',
							 'fechainicio'=>'<b>Fecha Inicio</b>',
							 'fechafinal'=>'<b>Fecha Fin</b>',
							 'numeropas'=>'<b>Número Pasantía</b>',
							 'edo'=>'<b>Estado</b>');
		$la_columnas=array('codper'=>'',
						   'nombper'=>'',
						   'fechainicio'=>'',
						   'fechafinal'=>'',
						   'numeropas'=>'',
						   'edo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codper'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'nombper'=>array('justification'=>'center','width'=>245), // Justificación y ancho de la columna
						 			   'fechainicio'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'fechafinal'=>array('justification'=>'center','width'=>60),
									   'numeropas'=>array('justification'=>'center','width'=>80),
									   'edo'=>array('justification'=>'center','width'=>55))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	
		$io_pdf->restoreState();
	    $io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	
	
     }// end function uf_print_encabezado_pagina
	 //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_cabecera($la_data,&$io_pdf)
 	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cabecera
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el detalle de los datos del pasante.
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 05/03/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(625);
		$la_columnas=array('codigo'=>'',
						   'nombre'=>'',
						   'fechaini'=>'',
						   'fechafin'=>'',
						   'nropas'=>'',
						   'estado'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>245), // Justificación y ancho de la columna
						 			   'fechaini'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'fechafin'=>array('justification'=>'center','width'=>60),
									   'nropas'=>array('justification'=>'center','width'=>80),
									   'estado'=>array('justification'=>'center','width'=>55))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------
   function uf_print_cabecera_evaluacion($la_data,&$io_pdf)
 	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_evaluacion
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime la cabecera de la evaluación del pasante.
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 05/03/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(610);
		$la_data[2]=array('fecha'=>'<b>Fecha de Evaluación</b>',
		                     'obser'=>'<b>Observación</b>',
							 'resultado'=>'<b>Resultado</b>');
		$la_columnas=array('fecha'=>'',
						   'obser'=>'',
						   'resultado'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('fecha'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'obser'=>array('justification'=>'center','width'=>380), // Justificación y ancho de la columna
						 			   'resultado'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_cabecera_evaluacion
//-----------------------------------------------------------------------------------------------------------------------------------
   function uf_print_detalle_evaluacion($la_detalle,&$io_pdf)
 	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_evaluacion
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el detalle de los datos de la evaluación del pasante.
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 05/03/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(595);
		$la_columnas=array('fechaeva'=>'',
						   'observacion'=>'',
						   'resul'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('fechaeva'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'observacion'=>array('justification'=>'left','width'=>380), // Justificación y ancho de la columna
						 			   'resul'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_detalle,$la_columnas,'',$la_config);
		
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_cabecera_evaluacion
	//-----------------------------------------------------------------------------------------------------------------------------------
    require_once("../../shared/ezpdf/class.ezpdf.php");	
	require_once("class_folder/sigesp_srh_class_report.php");
	$io_report=new sigesp_srh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/utilidades/class_funciones_srh.php");
	$io_fun_srh=new class_funciones_srh('../../');
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
       $ls_titulo="<b>RESULTADO EVALUACIÓN POR PASANTE</b>"; 
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_fechainides=$io_fun_srh->uf_obtenervalor_get("fechainides","");
	$ls_fechafinhas=$io_fun_srh->uf_obtenervalor_get("fechafinhas","");
	$ls_estatus=$io_fun_srh->uf_obtenervalor_get("estatus","");
	$ls_orden=$io_fun_srh->uf_obtenervalor_get("orden","");
	//-----------------------------------------------------------------------------------------------------------------------------------
	global $la_data;
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_buscar_datos_pasantes($ls_fechainides,$ls_fechafinhas,$ls_estatus,$ls_orden); // Cargar el DS con los datos del reporte
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
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(4,3,3,3); // Configuración de los margenes en centímetros
			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página
		  	$li_totrow=$io_report->DS->getRowCount("codemp");
			uf_print_encabezado_pagina($ls_titulo,$io_pdf);
			$li_aux=0;
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
			    $li_aux++;
				$ls_nropas=$io_report->DS->data["nropas"][$li_i];
				$ls_cedpas=$io_report->DS->data["cedpas"][$li_i];
				$ls_fechaini=$io_report->DS->data["fecini"][$li_i];
				$ls_fechafin=$io_report->DS->data["fecfin"][$li_i];
				$ls_nombrepas=$io_report->DS->data["nompas"][$li_i];
				$ls_apellidopas=$io_report->DS->data["apepas"][$li_i];
				$ls_estado=$io_report->DS->data["estado"][$li_i];
				$ls_fechaini=$io_funciones->uf_formatovalidofecha($ls_fechaini);
				$ls_fechaini=$io_funciones->uf_convertirfecmostrar($ls_fechaini);
				$ls_fechafin=$io_funciones->uf_formatovalidofecha($ls_fechafin);
				$ls_fechafin=$io_funciones->uf_convertirfecmostrar($ls_fechafin);
				$ls_cadena=$ls_nombrepas."  ".$ls_apellidopas;
				$la_data[$li_i]=array('codigo'=>$ls_cedpas,'nombre'=>$ls_cadena,'fechaini'=>$ls_fechaini,
				                     'fechafin'=>$ls_fechafin,'nropas'=>$ls_nropas,'estado'=>$ls_estado);
			    uf_print_detalle_cabecera($la_data,$io_pdf);
			    unset($la_data);
				$io_report->uf_print_detalle_evaluacion_pasantes($ls_fechainides,$ls_fechafinhas,$ls_estatus,$ls_orden,$ls_cedpas);
				uf_print_cabecera_evaluacion($la_data,&$io_pdf);
				
				$li_totrow1=$io_report->ds_detalle->getRowCount("cedpas");
				
				for($li_p=1;$li_p<=$li_totrow1;$li_p++)
				{
					$ls_fechaeva=$io_report->ds_detalle->data["feceval"][$li_p];
					$ls_observacion=$io_report->ds_detalle->data["observacion"][$li_p];
					$ls_resultado=$io_report->ds_detalle->data["resultado"][$li_p];
					$ls_fechaeva=$io_funciones->uf_formatovalidofecha($ls_fechaeva);
					$ls_fechaeva=$io_funciones->uf_convertirfecmostrar($ls_fechaeva);
					$la_detalle[$li_p]=array('fechaeva'=>$ls_fechaeva,'observacion'=>$ls_observacion,
										  'resul'=>$ls_resultado);
				    uf_print_detalle_evaluacion($la_detalle,&$io_pdf);
				} //fin del for
				unset($la_detalle);
				
				if($li_aux<$li_totrow)
				{
					$io_pdf->ezNewPage(); // Insertar una nueva página
				}
			}// fin del for de empleados
		
		 }  // fin del else	
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
	}
?>


