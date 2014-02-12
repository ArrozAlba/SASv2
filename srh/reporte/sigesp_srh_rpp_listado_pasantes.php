<?php

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Listado de Pasantias
//  ORGANISMO: IPSFA
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//-----------------------------------------------------------------------------------------------------------------------------------
///Elaborado por: Ing. Rivero Jennifer
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
	
		$io_pdf->restoreState();
	    $io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina	
	
//---------------------------------------------------------------------------------------------------------------------------------//
  function uf_print_encabezado_detalle($la_dataper,&$io_pdf)
	 {
		
	   $io_pdf->ezSetY(633);
	   $la_datap[1]=array(   'cedula'=>'<b>Cedula</b>',
		                     'nombre'=>'<b>Nombres y Apellidos</b>',
		                     'carrera'=>'<b>Carrera</b>',	
		                     'pasantia'=>'<b>Nro. Pasantia</b>',						 
							 'fechaini'=>'<b>Fecha de inicio</b>',
							 'fechafin'=>'<b>Fecha de culminación</b>',							 
							 'tutor'=>'<b>Tutor Empresarial</b>',							 
							 'estado'=>'<b>Estado de P.</b>');
							 
		$la_columnas=array(  'cedula'=>'',
		                     'nombre'=>'',
		                     'carrera'=>'',
		                     'pasantia'=>'',							 
							 'fechaini'=>'',
							 'fechafin'=>'',
							 'tutor'=>'',							 
							 'estado'=>'');
							 
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
						 'cols'=>array('cedula'=>array('justification'=>'center','width'=>58), // Justificación y ancho de la columna
						               'nombre'=>array('justification'=>'center','width'=>80),	
						               'carrera'=>array('justification'=>'center','width'=>100),
						               'pasantia'=>array('justification'=>'center','width'=>65),								   
									   'fechaini'=>array('justification'=>'center','width'=>60),
									   'fechafin'=>array('justification'=>'center','width'=>60),
									   'tutor'=>array('justification'=>'center','width'=>100),									   
									   'estado'=>array('justification'=>'center','width'=>50))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datap,$la_columnas,'',$la_config);
	
		
	    $io_pdf->ezSetY(608);
		$la_columnas=array(  'cedula'=>'',
		                     'nombre'=>'',
		                     'carrera'=>'',	
		                     'pasantia'=>'',						
							 'fechaini'=>'',
							 'fechafin'=>'',
							 'tutor'=>'',							 
							 'estado'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'cols'=>array('cedula'=>array('justification'=>'center','width'=>58), // Justificación y ancho de la columna
						               'nombre'=>array('justification'=>'center','width'=>80),
						               'carrera'=>array('justification'=>'center','width'=>100),	
						               'pasantia'=>array('justification'=>'center','width'=>65),								   
									   'fechaini'=>array('justification'=>'center','width'=>60),
									   'fechafin'=>array('justification'=>'center','width'=>60),
									   'tutor'=>array('justification'=>'center','width'=>100),									   
									   'estado'=>array('justification'=>'center','width'=>50))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_dataper,$la_columnas,'',$la_config);	
		
	    unset($la_dataper);
		unset($la_columnas);
		unset($la_config);	
				
		}
//---------------------------------------------------------------------------------------------------------------------------------//
//-----------------------------------------------------------------------------------------------------------------------------------

    require_once("../../shared/ezpdf/class.ezpdf.php");  
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/utilidades/class_funciones_srh.php");
	$io_fun_srh=new class_funciones_srh('../../');
	require_once("class_folder/sigesp_srh_class_report.php");
	$io_report=new sigesp_srh_class_report();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>LISTADO DE PASANTIAS</b>";
	
//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
    
	$ls_tiporeporte=$io_fun_srh->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
 	$ld_fechades1=$io_fun_srh->uf_obtenervalor_get("fecini1","");
	$ld_fechahas1=$io_fun_srh->uf_obtenervalor_get("fecfin1","");
	$ld_fechades2=$io_fun_srh->uf_obtenervalor_get("fecini2","");
	$ld_fechahas2=$io_fun_srh->uf_obtenervalor_get("fecfin2","");
	$ls_orden=$io_fun_srh->uf_obtenervalor_get("orden","");
	$ls_estatus=$io_fun_srh->uf_obtenervalor_get("estatus","");
		
//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
        $lb_valido=$io_report->uf_listado_pasantes($ld_fechades1,$ld_fechahas1,$ld_fechades2,$ld_fechahas2,$ls_estatus,$ls_orden);
      
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
			
			$lp_totrow=$io_report->detalle_pasantia->getRowCount("nropas");
			
			
			for($lp_p=1;$lp_p<=$lp_totrow;$lp_p++)
			{
			 $ls_cedula=$io_report->detalle_pasantia->data["cedpas"][$lp_p];
			 $ls_cedula=number_format($ls_cedula,0,",",".");
			 $ls_nombre=$io_report->detalle_pasantia->data["nompas"][$lp_p];
			 $ls_apellido=$io_report->detalle_pasantia->data["apepas"][$lp_p]; 
			 $ls_pasantia=$io_report->detalle_pasantia->data["nropas"][$lp_p];
			 $ls_fechaini=$io_report->detalle_pasantia->data["fecini"][$lp_p];			
			 $ls_fechaini=$io_funciones->uf_convertirfecmostrar($ls_fechaini);
			 $ls_fechafin=$io_report->detalle_pasantia->data["fecfin"][$lp_p];			
			 $ls_fechafin=$io_funciones->uf_convertirfecmostrar($ls_fechafin);		
			 $ls_estado=$io_report->detalle_pasantia->data["estado"][$lp_p];
			 $ls_tutor=$io_report->detalle_pasantia->data["tutor"][$lp_p];
			 $ls_instituto=$io_report->detalle_pasantia->data["inst_univ"][$lp_p];
			 $ls_carrera=$io_report->detalle_pasantia->data["carrera"][$lp_p];
			 
			 $la_dataper[$lp_p]=array('cedula'=>$ls_cedula,'nombre'=>$ls_nombre." ".$ls_apellido,
			                          'pasantia'=>$ls_pasantia,'fechaini'=>$ls_fechaini,'fechafin'=>$ls_fechafin,
									  'estado'=>$ls_estado,'tutor'=>$ls_tutor,'instituto'=>$ls_instituto,'carrera'=>$ls_carrera);	 		 
						
		   }//end del for*/
		   uf_print_encabezado_detalle($la_dataper,&$io_pdf);
		   unset($la_dataper);
					
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

	
	