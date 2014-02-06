<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Reporte de Evaluación Psicológica
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
		// Fecha Creación: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_srh;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("SRH","sigesp_srh_rpp_registro_evaluacion_psicologica.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//                 as_codigoper // Código del personal
		//                 as_nombreper // nombre del personal
		//                 as_codigocon  // Código del concurso
		//                 as_descricon  // descripción del concurso
		//                 as_fecha   // Fecha del concurso
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 11/02/2008
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
		$io_pdf->ezSetY(730);
		$la_data=array(array('titulo1'=>'<b>'.$as_titulo.'</b>'));

		$la_columnas=array('titulo1'=>'');

		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' =>16,  // Tamaño de Letras de los títulos
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
		
	   
		
	
		$io_pdf->restoreState();
	    $io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	
	
     }// end function uf_print_encabezado_pagina
	 //-----------------------------------------------------------------------------------------------------------------------------------
  function uf_print_cabecera_detalle($la_data,$as_codigoper,$as_nombreper,$as_codigocon,$as_descricon,$as_fecha, &$io_pdf)
 	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte.
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 11/02/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $io_pdf->ezSetY(690);
		$la_data[1]=array('codigoper'=>'<b>Código del Personal</b>',
		                     'nombre'=>'<b>                   Nombre y Apellido</b>',
							 'codigocon'=>'<b>Código del Concurso</b>',
							 'descripcion'=>'<b>Descripción</b>',
							 'fecha'=>'<b>Fecha</b>');
		$la_columnas=array('codigoper'=>'',
						   'nombre'=>'',
						   'codigocon'=>'',
						   'descripcion'=>'',
						   'fecha'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>980, // Ancho de la tabla
						 'maxWidth'=>980, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigoper'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>190), // Justificación y ancho de la columna
						 			   'codigocon'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'descripcion'=>array('justification'=>'center','width'=>165),
									   'fecha'=>array('justification'=>'center','width'=>65))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[2]=array('codigoper'=>'<b>'.$as_codigoper.'</b>',
		                     'nombre'=>'<b>'.$as_nombreper.'</b>',
							 'codigocon'=>'<b>'.$as_codigocon.'</b>',
							 'descripcion'=>'<b>'.$as_descricon.'</b>',
							 'fecha'=>'<b>'.$as_fecha.'</b>');
		$la_columnas=array('codigoper'=>'',
						   'nombre'=>'',
						   'codigocon'=>'',
						   'descripcion'=>'',
						   'fecha'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>980, // Ancho de la tabla
						 'maxWidth'=>980, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigoper'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>190), // Justificación y ancho de la columna
						 			   'codigocon'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'descripcion'=>array('justification'=>'center','width'=>165),
									   'fecha'=>array('justification'=>'center','width'=>65))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	   
	   
	    $io_pdf->ezSetY(640);
		$la_data[1]=array('codigoitem'=>'<b>Codigo del Item</b>',
						   'descripcion'=>'<b>Descripción</b> ',
						   'valormax'=>'<b>Puntaje Requerido</b>',
						   'puntositem'=>'<b>Puntaje Obtenido</b>');
		$la_columnas=array('codigoitem'=>'',
						   'descripcion'=>'',
						   'valormax'=>'',
						   'puntositem'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>980, // Ancho de la tabla
						 'maxWidth'=>980, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigoitem'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'descripcion'=>array('justification'=>'center','width'=>280), // Justificación y ancho de la columna
						 			   'valormax'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'puntositem'=>array('justification'=>'center','width'=>100)));// Justificación y ancho de la columna  
	   $io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	   	unset($la_data);
		unset($la_columnas);
		unset($la_config);
	
	} // fin de uf_print_cabecera_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_print_detalle_item($la_data,$ls_resulteva,&$io_pdf)
 	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_item
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte.
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 11/02/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columnas=array('codigoitem'=>'',
						   'descripcion'=>'',
						   'valormax'=>'',
						   'puntositem'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>980, // Ancho de la tabla
						 'maxWidth'=>980, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigoitem'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'descripcion'=>array('justification'=>'left','width'=>280), // Justificación y ancho de la columna
						 			   'valormax'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'puntositem'=>array('justification'=>'center','width'=>100))); 
	    $io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		
		
		 uf_print_total($ls_resulteva,&$io_pdf);
	}// end function uf_print_detalle_item
	//-----------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_total($ls_resulteva,&$io_pdf)
 	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_total
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte.
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 11/02/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$io_pdf->ezSetY(335);
		$la_datatotal[4]=array('total'=>'<b>Total:        '.$ls_resulteva.'</b>');
		$la_columnas=array('total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>570))); 
	    $io_pdf->ezTable($la_datatotal,$la_columnas,'',$la_config);
		unset($la_datatotal);
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_detalle_item
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
       $ls_titulo="<b>RESULTADO DE LA EVALUACIÓN PSICOLÓGICA</b>"; 
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codigoeva=$io_fun_srh->uf_obtenervalor_get("codigoeva","");
	$ls_descrieva=$io_fun_srh->uf_obtenervalor_get("descrieva","");
	$ls_codigoper=$io_fun_srh->uf_obtenervalor_get("codigoper",""); 
	$ls_nombreper=$io_fun_srh->uf_obtenervalor_get("nombreper","");
	$ls_codigocon=$io_fun_srh->uf_obtenervalor_get("codigocon","");
	$ls_descricon=$io_fun_srh->uf_obtenervalor_get("descricon","");
	$ls_fecha=$io_fun_srh->uf_obtenervalor_get("fecha","");
	$ls_resulteva=$io_fun_srh->uf_obtenervalor_get("resulteva","");
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	global $la_data;
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_buscar_evaluados($ls_codigoeva,$ls_codigoper,$ls_codigocon,$ls_fecha,$rs_data2); // Cargar el DS con los datos del reporte
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
			$io_pdf->ezSetCmMargins(3.6,5,3,3); // Configuración de los margenes en centímetros
			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página	
		  	uf_print_encabezado_pagina($ls_titulo,$io_pdf);
			uf_print_cabecera_detalle($la_data,$ls_codigoper,$ls_nombreper,$ls_codigocon,$ls_descricon,$ls_fecha,&$io_pdf);
			$li_totrow=$io_report->ds_detalle->getRowCount("codite");
			$li_aux=0;
			$li_i=1;
			
			 while ($row=$io_report->io_sql->fetch_row($rs_data2))
		   { 
		  	   $li_aux++;
			  
			    $ls_codigoitem=$row["codite"];
				$ls_descripcion=trim ($row["denite"]);
				$ls_valormax=$row["valormax"];
				$ls_puntositem=$row["puntos"];
				$ls_resultado=$row["punevapsi"];
				$la_data[$li_i]=array('codigoitem'=>$ls_codigoitem,'descripcion'=>$ls_descripcion,'valormax'=>$ls_valormax,
				                     'puntositem'=>$ls_puntositem,'resultado'=>$ls_resultado);
			  
			   $li_i++;
			}
			
			  
			  
		   uf_print_detalle_item($la_data,$ls_resulteva,$io_pdf);
	   	   unset($la_data);
		  
		    if($li_aux<$li_totrow)		
			 {
			 	$io_pdf->ezNewPage(); // Insertar una nueva página
			 }
		
	     }	// fin del else
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



