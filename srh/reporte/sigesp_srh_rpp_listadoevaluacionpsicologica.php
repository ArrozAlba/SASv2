<?php

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Listado de Evaluaciones por Meta
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
	
		function uf_print_encabezado_pagina($as_titulo,$io_pdf)
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
	
	    $io_pdf->ezSetY(677);
	    
		$la_data=array(array('titulo1'=>'<b>'.$as_titulo.'</b>'));
					
		$la_columnas=array('titulo1'=>'');
					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 12, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
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
	
//---------------------------------------------------------------------------------------------------------------------------------//
  function uf_print_encabezado_detalle($la_dataper,&$io_pdf)
	 {
		
	   $io_pdf->ezSetY(633);
	   $la_datap[1]=array(   'codper'=>'<b>Código del Personal</b>',
		                     'nombre'=>'<b>Nombre del Personal</b>',
							 'codcon'=>'<b>Còdigo del Concurso</b>',
							 'fecha'=>'<b>Fecha de la Evaluación</b>',
							 'puntaje'=>'<b>Puntaje</b>');
							 
		$la_columnas=array(  'codper'=>'',
		                     'nombre'=>'',
							 'codcon'=>'',
							 'fecha'=>'',
							 'puntaje'=>'');
							 
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
						 'cols'=>array('codper'=>array('justification'=>'center','width'=>100),
						               'nombre'=>array('justification'=>'center','width'=>180), // Justificación y ancho de la columna
						               'codcon'=>array('justification'=>'center','width'=>100),
									   'fecha'=>array('justification'=>'center','width'=>120),
									   'puntaje'=>array('justification'=>'center','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datap,$la_columnas,'',$la_config);
	
		
	    $io_pdf->ezSetY(617);
		$la_columnas=array(  'codper'=>'',
		                     'nombre'=>'',
							 'codcon'=>'',
							 'fecha'=>'',
							 'puntaje'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'cols'=>array('codper'=>array('justification'=>'center','width'=>100),
						               'nombre'=>array('justification'=>'center','width'=>180), // Justificación y ancho de la columna
						               'codcon'=>array('justification'=>'center','width'=>100),
									   'fecha'=>array('justification'=>'center','width'=>120),
									   'puntaje'=>array('justification'=>'center','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_dataper,$la_columnas,'',$la_config);	
		
	    unset($la_dataper);
		unset($la_columnas);
		unset($la_config);	
				
		}
//---------------------------------------------------------------------------------------------------------------------------------//

 function uf_print_detalle($la_data,$ai_i,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				   as_titcuentas // titulo de estructura presupuestaria
		//				   ai_i // total de registros
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 10/06/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    
		
		$io_pdf->ezSetY(560);
		$la_data_titulo[1]=array('codite'=>'Còdigo del Item',
						         'denite'=>'Denominación',
						         'puntos'=>'Puntaje');
		$la_columnas=array('codite'=>'',
						   'denite'=>'',
						   'puntos'=>'');
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
						 'cols'=>array('codite'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
									   'denite'=>array('justification'=>'left','width'=>400),
						 			   'puntos'=>array('justification'=>'center','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_titulo,$la_columnas,'',$la_config);
		
		$la_columnas=array('codite'=>'',
						   'denite'=>'',
						   'puntos'=>'');
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
						 'cols'=>array('codite'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
									   'denite'=>array('justification'=>'left','width'=>400),
						 			   'puntos'=>array('justification'=>'center','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
					
	}// end function uf_print_detalle		


//-----------------------------------------------------------------------------------------------------------------------------------

    require_once("../../shared/ezpdf/class.ezpdf.php");  
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/utilidades/class_funciones_srh.php");
	$io_fun_srh=new class_funciones_srh('../../');
	require_once("class_folder/sigesp_srh_class_report.php");
	$io_report=new sigesp_srh_class_report();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>Listado de Evaluaciones Psicológicas</b>";
	
//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
    
	$ls_tiporeporte=$io_fun_srh->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
 
	$ld_concurdes=$io_fun_srh->uf_obtenervalor_get("curdes","");
	$ld_concurhas=$io_fun_srh->uf_obtenervalor_get("curhas","");
	$ls_orden=$io_fun_srh->uf_obtenervalor_get("ls_orden","");
		
//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
        $lb_valido=$io_report->uf_personal_eval_psicologico($ld_concurdes,$ld_concurhas,$ls_orden);
      
		if ($lb_valido==false)
		{
		    print("<script language=JavaScript>");
			print(" alert('No hay nada que reportar');"); 
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
			uf_print_encabezado_pagina($ls_titulo,&$io_pdf);
			$lp_totrow=$io_report->det_per_psi->getRowCount("codper");
			$li_aux=0;
			for($lp_p=1;$lp_p<=$lp_totrow;$lp_p++)
			{
			 $li_aux++;
			 $ls_codper=$io_report->det_per_psi->data["codper"][$lp_p];
			 $ls_nombre1=$io_report->det_per_psi->data["nombre1"][$lp_p]; 
			 $ls_nombre=$ls_nombre1;
			 $ls_concurso=$io_report->det_per_psi->data["codcon"][$lp_p];
			 $ls_fecha=$io_report->det_per_psi->data["fecha"][$lp_p];
			 $ls_fecha_items=$ls_fecha;
			 $ls_fecha=$io_funciones->uf_convertirfecmostrar($ls_fecha);
			 $ls_puntaje=$io_report->det_per_psi->data["punevapsi"][$lp_p];
			 $ls_tipo_eval=$io_report->det_per_psi->data["tipo_eval"][$lp_p];
			 
			 $la_dataper[$lp_p]=array('codper'=>$ls_codper,'nombre'=>$ls_nombre,'codcon'=>$ls_concurso,
			                          'fecha'=>$ls_fecha,'puntaje'=>$ls_puntaje);			 
						  		
			 uf_print_encabezado_detalle($la_dataper,&$io_pdf);
			 unset($la_dataper);
			
			 $lb_valido=$io_report->uf_items_eval_psicologico($ls_codper,$ls_fecha_items,$ls_tipo_eval);
			
			 if ($lb_valido)
		     {
				 $li_totrow=$io_report->det_item_psi->getRowCount("codite"); 			 
				 for($li_i=1;$li_i<=$li_totrow;$li_i++)
			  		{      
					 $ls_codite=$io_report->det_item_psi->data["codite"][$li_i];
					 $ls_denominacion=trim ($io_report->det_item_psi->data["denite"][$li_i]);
					 $ls_puntos=$io_report->det_item_psi->data["puntos"][$li_i];
					
					 $la_data[$li_i]=array('codite'=>$ls_codite,'denite'=>$ls_denominacion,
				                           'puntos'=>$ls_puntos);
			  			  
			       }
			     uf_print_detalle($la_data,$li_totrow,&$io_pdf);
			     unset($la_data);
		     }
			 if($li_aux<$lp_totrow)		
			 {
			 	$io_pdf->ezNewPage(); // Insertar una nueva página
			 }
						
		   }//end del for*/
					
		}	//end del else
		   
		      
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

	
	