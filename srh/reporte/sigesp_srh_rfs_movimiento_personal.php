<?php

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Listado de Movimiento de personal
//  ORGANISMO: IPSFA
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//-----------------------------------------------------------------------------------------------------------------------------------
///Elaborado por: Ing. Gusmary Balza
//-----------------------------------------------------------------------------------------------------------------------------------
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
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_srh;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("SRH","sigesp_cxp_r_listadoevaluacionpsicologica.php",$ls_descripcion);
		return $lb_valido;
	}
//-----------------------------------------------------------------------------------------------------------------------------------	
	
		function uf_print_encabezado_pagina($as_titulo,$as_nroreg,$as_fecha_r,$as_para,$as_de,$as_asunto,$io_pdf)
	    {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 11/03/2007
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
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 14,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('titulo1'=>array('justification'=>'center','width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);		
		
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->Rectangle(50,630,500,60);
		$io_pdf->addText(470,670,8," <b>No. </b>".'  '.$as_nroreg); // Agregar el título
		$io_pdf->addText(470,640,8,"<b>FECHA: </b>".' '.$as_fecha_r); // Agregar el título
		$io_pdf->line(460,660,550,660);	//HORIZONTAL
		$io_pdf->line(460,630,460,690);	//VERTICAL	
		// cuadro inferior
		$io_pdf->setStrokeColor(0,0,0);
        $io_pdf->Rectangle(50,60,500,60); 
		$io_pdf->addText(60,112,7,"ELABORADO POR:"); // Agregar el título
		$io_pdf->addText(90,70,7,"T.S.U. MIRNA RUSSO GONZALEZ"); // Agregar el título
		$io_pdf->addText(70,63,7,"JEFE SECCION RECLUTAMIENTO Y SELECCION"); // Agregar el título
		$io_pdf->addText(310,112,7,"APROBADO POR:"); // Agregar el título
		$io_pdf->addText(340,70,7,"JOEL ADRIAN MENA SORETT"); // Agregar el título
		$io_pdf->addText(310,63,7,"GERENTE INTERNO DE RECURSOS HUMANOS"); // Agregar el título
		$io_pdf->line(300,60,300,120);	//VERTICAL		
			
		
		$io_pdf->ezSetY(632);
		$la_data_t[1]=array('titulo1'=>'<b>PARA: </b>'.$as_para);
 		$la_data_t[2]=array('titulo1'=>'<b>DE: </b>'.$as_de);
		$la_data_t[3]=array('titulo1'=>'<b>ASUNTO: </b> '.$as_asunto);				
						
		$la_columnas=array('titulo1'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>305, // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla						 
						 'maxWidth'=>500,
						 'cols'=>array('titulo1'=>array('justification'=>'left','width'=>500))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data_t,$la_columnas,'',$la_config);	
		
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
		
		$io_pdf->restoreState();
	    $io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	
	}// end function uf_print_encabezado_pagina	


  function uf_print_detalle ($as_apeper,$as_nomper,$as_cedper,$as_descar,$as_motivo,$as_observacion,&$io_pdf)
   {
      
	    $io_pdf->ezSetY(589);	    
		$la_data=array(array('titulo1'=>'<b>DATOS DEL EMPLEADO O CIUDADANO:</b>'));				
		$la_columnas=array('titulo1'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('titulo1'=>array('justification'=>'left','width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columna);
		unset($la_config);	
		
		
		$io_pdf->ezSetY(575);
		$la_data[1]=array('columna1'=>'<b>APELLIDOS:</b>'.$as_apeper,
		                 'columna2'=>'<b>NOMBRE:</b> '.$as_nomper,
						 'columna3'=>'<b>C.I:</b> '.$as_cedper);
		
		$la_columna=array('columna1'=>'','columna2'=>'','columna3'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>200),
									   'columna3'=>array('justification'=>'left','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		
	    $io_pdf->ezSetY(560);
		$la_data[1]=array('columna1'=>'<b>CARGO ACTUAL:</b>'.$as_descar,
		                 'columna2'=>'<b>GRADO:</b> ',
						 'columna3'=>'<b>PASO:</b> ',
						 'columna4'=>'<b>SUELDO BASICO:</b> ',
						 'columna5'=>'<b>COMPENSACION</b> ',
						 'columna6'=>'<b>OTROS INGRESOS:</b> ',
						 'columna7'=>'<b>SUELDO TOTAL:</b> ');
		$la_columna=array('columna1'=>'','columna2'=>'','columna3'=>'','columna4'=>'','columna5'=>'','columna6'=>'','columna7'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>100), // Justificación y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>40),
									   'columna3'=>array('justification'=>'left','width'=>40),
									   'columna4'=>array('justification'=>'left','width'=>50),
									   'columna5'=>array('justification'=>'left','width'=>70),
									   'columna6'=>array('justification'=>'left','width'=>100),
									   'columna7'=>array('justification'=>'left','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	  	
		$io_pdf->ezSetY(541);
		$la_data[1]=array('columna1'=>'<b>GERENCIA:</b>',
		                 'columna2'=>'<b>DEPARTAMENTO:</b> ',
						 'columna3'=>'<b>SECCION:</b> ');
		$la_columna=array('columna1'=>'','columna2'=>'','columna3'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>160), // Justificación y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>220),
									   'columna3'=>array('justification'=>'left','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		
	  
	    $io_pdf->ezSetY(527);
		$la_data[1]=array('columna1'=>'<b>CARGO ACTUAL:</b>',
		                 'columna2'=>'<b>GRADO:</b> ',
						 'columna3'=>'<b>PASO:</b> ',
						 'columna4'=>'<b>SUELDO BASICO:</b> ',
						 'columna5'=>'<b>COMPENSACION</b> ',
						 'columna6'=>'<b>OTROS INGRESOS:</b> ',
						 'columna7'=>'<b>SUELDO TOTAL:</b> ');
		$la_columna=array('columna1'=>'','columna2'=>'','columna3'=>'','columna4'=>'','columna5'=>'','columna6'=>'','columna7'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>100), // Justificación y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>40),
									   'columna3'=>array('justification'=>'left','width'=>40),
									   'columna4'=>array('justification'=>'left','width'=>50),
									   'columna5'=>array('justification'=>'left','width'=>70),
									   'columna6'=>array('justification'=>'left','width'=>100),
									   'columna7'=>array('justification'=>'left','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		
		$io_pdf->ezSetY(507);
		$la_data[1]=array('columna1'=>'<b>GERENCIA:</b>',
		                 'columna2'=>'<b>DEPARTAMENTO:</b> ',
						 'columna3'=>'<b>SECCION:</b> ');
		$la_columna=array('columna1'=>'','columna2'=>'','columna3'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>160), // Justificación y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>220),
									   'columna3'=>array('justification'=>'left','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		
		
		$io_pdf->ezSetY(492);
		$la_data[1]=array('columna1'=>'<b>MOTIVO DE LA VACANTE</b>'.$as_motivo,
		                 'columna2'=>'<b></b> ',
						 'columna3'=>'<b>EN REEMPLAZO DE:</b> ');
		$la_columna=array('columna1'=>'','columna2'=>'','columna3'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>250), // Justificación y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>100),
									   'columna3'=>array('justification'=>'left','width'=>150))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		
		
		$io_pdf->ezSetY(477);
		$la_data[1]=array('columna1'=>'<b>RECOMENDACION: PROCEDER AL MOVIMIENTO SEÑALADO A PARTIR DE LA FECHA:</b>',
		                 'columna2'=>'<b>ANEXOS:</b> ');
		$la_columna=array('columna1'=>'','columna2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>350))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		
		$io_pdf->ezSetY(450);
		$la_data[1]=array('columna1'=>'<b>OBSERVACION:</b>'.$as_observacion);
		$la_columna=array('columna1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>500))); // Justificación y ancho de la columna
						 			
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	
	}

//-----------------------------------------------------------------------------------------------------------------------------------

    require_once("../../shared/ezpdf/class.ezpdf.php");  
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/utilidades/class_funciones_srh.php");
	$io_fun_srh=new class_funciones_srh('../../');
	require_once("class_folder/sigesp_srh_class_report.php");
	$io_report=new sigesp_srh_class_report();
//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	
	$ls_titulo="<b>CUENTA MOVIMIENTO DE PERSONAL</b>";
	
	$ls_nroreg = $_GET["nroreg"];
	$ls_codper = $_GET["codper"];

    $lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if ($lb_valido)
	  {
        $lb_valido=$io_report->uf_listado_movimiento($ls_nroreg, $ls_codper);
      
	  if ($lb_valido==false)
		{
		    print("<script language=JavaScript>");
			print(" alert('No hay nada que reportar');"); 
		//	print(" close();");
			print("</script>");
		}
		   
		else  // Imprimimos el reporte
		{	
	
          error_reporting(E_ALL);
	   	  set_time_limit(1800);
		  $io_pdf=new Cezpdf('LETTER','PORTRAIT'); // Instancia de la clase PDF
		  $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		  $io_pdf->ezSetCmMargins(4,5,3,3); // Configuración de los margenes en centímetros
		  $io_pdf->ezStartPageNumbers(720,47,8,'','',1); // Insertar el número de página			
		 
			
			
	      $li_totrow=$io_report->ds_detalle->getRowCount("nummov");			
		  for($lp_i=1;$lp_i<=$li_totrow;$lp_i++)
			{
			 
			 $ls_nroreg=$io_report->ds_detalle->data["nroreg"][$lp_i];
			 $ls_fecha_r=$io_report->ds_detalle->data["fecha"][$lp_i];			 
			 $ls_fecha_r=$io_funciones->uf_convertirfecmostrar($ls_fecha_r);
			// $ls_de=$io_report->ds_detalle->data["de"][$lp_i];			 
			// $ls_para=$io_report->ds_detalle->data["para"][$lp_i];
			// $ls_asunto=$io_report->ds_detalle->data["asunto"][$lp_i];
			 $ls_nomper=$io_report->ds_detalle->data["nomper"][$lp_i];
			 $ls_apeper=$io_report->ds_detalle->data["apeper"][$lp_i];
			 $ls_cedper=$io_report->ds_detalle->data["cedper"][$lp_i];
			 $ls_descar1=$io_report->ds_detalle->data["descar"][$lp_i];
			 $ls_descar2=$io_report->ds_detalle->data["denasicar"][$lp_i];
			 $ls_motivo=$io_report->ds_detalle->data["motivo"][$lp_i];
			 $ls_observacion=$io_report->ds_detalle->data["observacion"][$lp_i];
			 
		 	
			 uf_print_encabezado_pagina($ls_titulo,$ls_nroreg,$ls_fecha_r,$ls_de,$ls_para,$ls_asunto,&$io_pdf);	
		     uf_print_detalle($ls_apeper,$ls_nomper,$ls_cedper,$ls_descar,$ls_motivo,$ls_observacion,&$io_pdf);	

             if($lb_valido) // Si no ocurrio ningún error
		     {
			  $io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			  $io_pdf->ezStream(); // Mostramos el reporte
		     }
            else // Si hubo algún error
		     {
			  print("<script language=JavaScript>");
			  print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			//  print(" close();");
			  print("</script>");	
		    }

          }
	  }
 }
?>	








	