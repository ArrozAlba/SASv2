<?php

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Resultados Evaluación de Requisitos Mínimos
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
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("SRH","sigesp_cxp_r_listados.php",$ls_descripcion);
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
		
		$io_pdf->ezSetY(730);
		$la_data=array(array('titulo1'=>'<b>'.$as_titulo.'</b>'));
					
		$la_columnas=array('titulo1'=>'');
					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
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
		
		
		
					
		$io_pdf->restoreState();
	    $io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina	
	
//-------------------------------------------------------------------------------------------------------------------------------//

 function uf_print_encabezado_detalle($la_dataper,&$io_pdf)
	 {
		
		$io_pdf->ezSetY(680);
		$la_datap[1]=array('tipo_eval'=>'<b>Tipo de Evaluación</b>',
		                     'codper'=>'<b>Código Aspirante</b>',
		                     'nombre'=>'<b>Nombre</b>',
							// 'descon'=>'<b>Concurso</b>',
							 'codcon'=>'<b>Código del Concurso</b>',
							 'fecha'=>'<b>Fecha Evaluación</b>',
							 'punreqmin'=>'<b>Resultados</b>');
		$la_columnas=array('tipo_eval'=>'',
		                   'codper'=>'',
						   'nombre'=>'',
						//   'descon'=>'',
						   'codcon'=>'',
						   'fecha'=>'',
						   'punreqmin'=>'');
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
						 'cols'=>array('tipo_eval'=>array('justification'=>'center','width'=>90),
						               'codper'=>array('justification'=>'center','width'=>90),
						               'nombre'=>array('justification'=>'center','width'=>170),
								//	   'descon'=>array('justification'=>'left','width'=>100),
									   'codcon'=>array('justification'=>'left','width'=>100),
									   'fecha'=>array('justification'=>'center','width'=>60),
									   'punreqmin'=>array('justification'=>'center','width'=>60))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datap,$la_columnas,'',$la_config);
	
		
	   $la_columnas=array('tipo_eval'=>'','codper'=>'',
						   'nombre'=>'',
						//    'descon'=>'',
						   'codcon'=>'',
						   'fecha'=>'',
						   'punreqmin'=>'');
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
						'cols'=>array('tipo_eval'=>array('justification'=>'center','width'=>90),
						              'codper'=>array('justification'=>'center','width'=>90),
						              'nombre'=>array('justification'=>'left','width'=>170),
								//	  'descon'=>array('justification'=>'left','width'=>100),
									  'codcon'=>array('justification'=>'left','width'=>100),
									  'fecha'=>array('justification'=>'center','width'=>60),
									  'punreqmin'=>array('justification'=>'right','width'=>60))); // Justificación y ancho de la columna
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
	
		
		
	    $io_pdf->ezSetY(630);
		$la_datatit[1]=array('codite'=>'<b>Código del Requisito</b>',
							 'denite'=>'<b>Denominación</b>',
							 'valormax'=>'<b>Puntaje Req</b>',
							 'puntos'=>'<b>Puntaje Obt</b>');
		$la_columnas=array('codite'=>'',
						   'denite'=>'',
						   'valormax'=>'',
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
						 'cols'=>array('codite'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
									   'denite'=>array('justification'=>'left','width'=>310),
									   'valormax'=>array('justification'=>'center','width'=>70),
						 			   'puntos'=>array('justification'=>'center','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
	
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		$la_columnas=array('codite'=>'',
						   'denite'=>'',
						   'valormax'=>'',
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
						 'cols'=>array('codite'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
									   'denite'=>array('justification'=>'left','width'=>310),
									   'valormax'=>array('justification'=>'right','width'=>70),
						 			   'puntos'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
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
	$ls_titulo="<b>Resultados de Evaluación de Requisitos Mínimos</b>";
	
//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------	

 	$ls_tipo_eval=$io_fun_srh->uf_obtenervalor_get("codeval","");
	$ls_codper =$io_fun_srh->uf_obtenervalor_get("codper",""); 
	$ls_codcon =$io_fun_srh->uf_obtenervalor_get("codcon","");

	$ls_fecha =$io_fun_srh->uf_obtenervalor_get("fecha","");

	
//----------------------------------------------------------------------------------------------------------------------------------//

 $lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
        $lb_valido=$io_report->uf_select_resultadosxaspirante($ls_tipo_eval,$ls_codper,$ls_codcon,$ls_fecha);
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
		    
			$lp_totrow=$io_report->ds_detalle->getRowCount("codper");
			$li_aux=0;
			for($lp_p=1;$lp_p<=$lp_totrow;$lp_p++)
			{
			 $li_aux++;
			 $li_totrow=0;
			 $ls_tipo_eval=$io_report->ds_detalle->data["tipo_eval"][$lp_p];
			 $ls_codper=$io_report->ds_detalle->data["codper"][$lp_p];
			 $ls_nombre=$io_report->ds_detalle->data["nombre"][$lp_p];
		//	 $ls_descon=$io_report->ds_detalle->data["descon"][$lp_p];
			 $ls_codcon=$io_report->ds_detalle->data["codcon"][$lp_p];
			 $ld_fecha=$io_report->ds_detalle->data["fecha"][$lp_p];
			
			 $ld_fecha_f=$io_funciones->uf_convertirfecmostrar($ld_fecha);
			 
			 $ls_punreqmin=$io_report->ds_detalle->data["punreqmin"][$lp_p];
			 $la_dataper[$lp_p]=array('tipo_eval'=>$ls_tipo_eval,'codper'=>$ls_codper,'nombre'=>$ls_nombre,
			                      //    'descon'=>$ls_descon,
								  'codcon'=>$ls_codcon,'fecha'=>$ld_fecha_f,
									  'punreqmin'=>$ls_punreqmin); 
		
		     uf_print_encabezado_detalle($la_dataper,&$io_pdf); //print $lp_totrow;
			 unset($la_dataper);
			 
		     
			 $io_report->uf_select_requisitos($ls_tipo_eval,$ls_codcon,$ls_codper,$ld_fecha);
			 $li_totrow=$io_report->DS->getRowCount("codite"); //print $li_totrow." ".$lp_p."<br>";	 
			 for($li_i=1;$li_i<=$li_totrow;$li_i++)
			  {      
				$ls_codigo=$io_report->DS->data["codite"][$li_i];
				$ls_denite=trim ($io_report->DS->data["denite"][$li_i]);
				$ls_valormax=$io_report->DS->data["valormax"][$li_i];
				$ls_puntos=$io_report->DS->data["puntos"][$li_i];
				$la_data[$li_i]=array('codite'=>$ls_codigo,'denite'=>$ls_denite,
				                      'valormax'=>$ls_valormax,'puntos'=>$ls_puntos);
			  			  
			  }
		   uf_print_detalle($la_data,$li_totrow,&$io_pdf);
		   unset($la_data);
		   
		   if($li_aux<$lp_totrow)		
			 {
			 	$io_pdf->ezNewPage(); // Insertar una nueva página
			 }
		}
		
		
	}	
		
		  
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