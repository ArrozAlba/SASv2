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
		$io_pdf->line(15,40,750,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],65,505,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->addText(670,590,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(676,584,6,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
		 $io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();		
	
	    $io_pdf->ezSetY(477);
	    
		$la_data=array(array('titulo1'=>'<b>'.$as_titulo.'</b>'));
					
		$la_columnas=array('titulo1'=>'');
					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 14,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('titulo1'=>array('justification'=>'center','width'=>670))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);		
		
		 $io_pdf->ezSetY(390);
	    
		$la_data=array(array('titulo2'=>"Datos de Personas a Asistir al Adiestramiento"));
					
		$la_columnas=array('titulo2'=>'');
					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 14,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('titulo2'=>array('justification'=>'center','width'=>670))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);		
	
		$io_pdf->restoreState();
	    $io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina	
	
//---------------------------------------------------------------------------------------------------------------------------------//
  function uf_print_encabezado_detalle($la_data,&$io_pdf)
	 {
		
	   $io_pdf->ezSetY(450);
	   $la_data_t[1]=array(  'adiest'=>'<b>Código del Adiest.</b>', 
	   						 'decripcion'=>'<b>Descripción</b>',
		                     'fechareg'=>'<b>Fecha de Reg. </b>',
							 'solicitante'=>'<b>Solcitante </b>',
		                     'porv'=>'<b>Proveedor </b>',
		                     'fechaini'=>'<b>Fecha de Inicio</b>',
		                     'fechafin'=>'<b>Fecha de Culminación </b>',							 
							 'duracion'=>'<b>Duración</b>',
							 'costo'=>'<b>Costo</b>');
							 
		$la_columnas=array(  'adiest'=>'', 
	   						 'decripcion'=>'',
		                     'fechareg'=>'',
		                     'porv'=>'',
							 'solicitante'=>'',
		                     'fechaini'=>'',
		                     'fechafin'=>'',							 
							 'duracion'=>'',
							 'costo'=>'');
							 
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('adiest'=>array('justification'=>'center','width'=>65),
						               'decripcion'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
						               'fechareg'=>array('justification'=>'center','width'=>70),
									   'porv'=>array('justification'=>'center','width'=>110),
									   'solicitante'=>array('justification'=>'center','width'=>110),
									   'fechaini'=>array('justification'=>'center','width'=>70),
									   'fechafin'=>array('justification'=>'center','width'=>70),									   
									   'duracion'=>array('justification'=>'center','width'=>50),
									   'costo'=>array('justification'=>'center','width'=>50))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_t,$la_columnas,'',$la_config);
	
		
	    $io_pdf->ezSetY(425);
		$la_columnas=array(  'adiest'=>'', 
	   						 'decripcion'=>'',
		                     'fechareg'=>'',
		                     'porv'=>'',
							 'solicitante'=>'',
		                     'fechaini'=>'',
		                     'fechafin'=>'',							 
							 'duracion'=>'',
							 'costo'=>'');
							 
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('adiest'=>array('justification'=>'center','width'=>65),
						               'decripcion'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
						               'fechareg'=>array('justification'=>'center','width'=>70),
									   'porv'=>array('justification'=>'center','width'=>110),
									   'solicitante'=>array('justification'=>'center','width'=>110),
									   'fechaini'=>array('justification'=>'center','width'=>70),
									   'fechafin'=>array('justification'=>'center','width'=>70),									   
									   'duracion'=>array('justification'=>'center','width'=>50),
									   'costo'=>array('justification'=>'center','width'=>50)));// Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);	
		
	    unset($la_data);
		unset($la_columnas);
		unset($la_config);	
				
		}
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
	function uf_print_detalle($la_data,&$io_pdf)
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
	    
		$io_pdf->ezSetY(370);
		$la_data_titulo[1]=array('ced'=>'Cedula',
						         'nombre_per'=>'Nombres y Apellidos',
								 'cargo'=>'Cargo','depa'=>'Departamento');
		$la_columnas=array('ced'=>'',
						   'nombre_per'=>'',
						   'cargo'=>'',
						   'depa'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('ced'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
									   'nombre_per'=>array('justification'=>'left','width'=>255),
									   'cargo'=>array('justification'=>'center','width'=>180),
									   'depa'=>array('justification'=>'center','width'=>210))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_titulo,$la_columnas,'',$la_config);
		
		$la_columnas=array('ced'=>'',
						   'nombre_per'=>'',
						   'cargo'=>'',
						   'depa'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('ced'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
									   'nombre_per'=>array('justification'=>'left','width'=>255),
									   'cargo'=>array('justification'=>'center','width'=>180),
									   'depa'=>array('justification'=>'center','width'=>210))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);					
	}// end function uf_print_detalle		
//-----------------------------------------------------------------------------------------------------------------------------------
      function uf_print_total($lp_p,&$io_pdf)  
		{
		$io_pdf->ezSetY(250);	    
		$la_datat=array(array('titulo3'=>" TOTAL DE PERSONAS POR ASISTIR AL ADIESTRAMIENTO:".'    '.$lp_p));
					
		$la_columnas=array('titulo3'=>'');
					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 14,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('titulo3'=>array('justification'=>'right','width'=>705))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datat,$la_columnas,'',$la_config);
        unset($la_datat);
		unset($la_columnas);
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
	$ls_titulo="<b>LISTADO DE SOLICITUD DE ADIESTRAMIENTO</b>";
	
//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
    
	 $ls_tiporeporte=$io_fun_srh->uf_obtenervalor_get("tiporeporte",0);
	 global $ls_tiporeporte;
 	 $ls_fechades=$_GET["fechades"]; 
	 $ls_fechahas=$_GET["fechahas"];
	 $ls_codperdes=$_GET["codperdes"];
	 $ls_codperhas=$_GET["codperhas"];
	 $ls_orden=$_GET["ls_orden"];
		
//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
        $lb_valido=$io_report->uf_listado_adiestramiento($ls_fechades,$ls_fechahas,$ls_codperdes,$ls_codperhas,$ls_orden);
      
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
			$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(4,5,3,3); // Configuración de los margenes en centímetros
			$io_pdf->ezStartPageNumbers(720,47,8,'','',1); // Insertar el número de página			
			uf_print_encabezado_pagina($ls_titulo,&$io_pdf);
			
			$li_totrow=$io_report->det_list_adi->getRowCount("nroreg");			
			$li_aux=0;
			for($lp_i=1;$lp_i<=$li_totrow;$lp_i++)
			{
			 $li_aux++;
			 $ls_adiest=$io_report->det_list_adi->data["nroreg"][$lp_i];
			 $ls_descrip=$io_report->det_list_adi->data["descripcion"][$lp_i];
			 $ls_fecha_r=$io_report->det_list_adi->data["fecha"][$lp_i];			 
			 $ls_fecha_r=$io_funciones->uf_convertirfecmostrar($ls_fecha_r);
			 $ls_fecha_i=$io_report->det_list_adi->data["fecini"][$lp_i];			 
			 $ls_fecha_i=$io_funciones->uf_convertirfecmostrar($ls_fecha_i);
			 $ls_fecha_f=$io_report->det_list_adi->data["fecfin"][$lp_i];			 
			 $ls_fecha_f=$io_funciones->uf_convertirfecmostrar($ls_fecha_f);			 
			 $ls_prov=$io_report->det_list_adi->data["nompro"][$lp_i];			 
			 $ls_duracion=$io_report->det_list_adi->data["durhras"][$lp_i];
			 $ls_costo=$io_report->det_list_adi->data["costo"][$lp_i];
			 $ls_costo=number_format($ls_costo,0,",",".");
			 $ls_nombre=$io_report->det_list_adi->data["nomper"][$lp_i];
			 $ls_apellido=$io_report->det_list_adi->data["apeper"][$lp_i];
			 
			 $la_data[$lp_i]=array('adiest'=>$ls_adiest,'decripcion'=>$ls_descrip,'fechareg'=>$ls_fecha_r,
			                       'fechaini'=>$ls_fecha_i,'fechafin'=>$ls_fecha_f,'porv'=>$ls_prov,'duracion'=>$ls_duracion,
								   'costo'=>$ls_costo,'solicitante'=>$ls_nombre." ".$ls_apellido);   
			uf_print_encabezado_detalle($la_data,&$io_pdf);
		    unset($la_data);
			
		    $lb_valido=$io_report->uf_listado_personas_adiestramiento($ls_adiest);
			
		    $li_totrow_per=$io_report->det_pers_adi->getRowCount("codper"); 		    
		    for($lp_p=1;$lp_p<=$li_totrow_per;$lp_p++)
			{			
			  $ls_cedula=$io_report->det_pers_adi->data["cedper"][$lp_p];
			  $ls_nombre_per=$io_report->det_pers_adi->data["nomper"][$lp_p];
			  $ls_apellido_per=$io_report->det_pers_adi->data["apeper"][$lp_p];			 
			  $ls_cargo=$io_report->det_pers_adi->data["carper"][$lp_p];	
			  $ls_departamento=$io_report->det_pers_adi->data["dep"][$lp_p]; 
			 
			 $la_data_per[$lp_p]=array('ced'=>$ls_cedula,'nombre_per'=>$ls_nombre_per."".$ls_apellido_per,'cargo'=>$ls_cargo,
			                           'depa'=> $ls_departamento);			
			}
			uf_print_detalle($la_data_per,&$io_pdf);
			unset($la_data_per);
			
			
			uf_print_total( $li_totrow_per,&$io_pdf); 
			
			
			if($li_aux<$li_totrow)		
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

	
	