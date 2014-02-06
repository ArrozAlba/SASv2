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
						 'fontSize' => 12, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
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
	
		$io_pdf->restoreState();
	    $io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina	
	
//---------------------------------------------------------------------------------------------------------------------------------//
  function uf_print_encabezado_detalle($la_data,&$io_pdf)
	 {
		
	   $io_pdf->ezSetY(450);
	   $la_data_t[1]=array(  'ascenso'=>'<b>Còdig del Ascenso</b>', 
	   						 'cedula'=>'<b>Cédula</b>',
		                     'nombre'=>'<b>Nombre </b>',
		                     'apellido'=>'<b>Apellido </b>',
		                     'caract'=>'<b>Cargo Actual </b>',
		                     'carasc'=>'<b>Cargo para Ascenso </b>',							 
							 'fecha'=>'<b>Fecha de la Post. al Ascenso</b>',
							 'puntaje'=>'<b>Puntaje</b>');
							 
		$la_columnas=array(  'ascenso'=>'', 
	   						 'cedula'=>'',
		                     'nombre'=>'',
		                     'apellido'=>'',
		                     'caract'=>'',
		                     'carasc'=>'',							 
							 'fecha'=>'',
							 'puntaje'=>'');
							 
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
						 'cols'=>array('ascenso'=>array('justification'=>'center','width'=>65),
						               'cedula'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
						               'nombre'=>array('justification'=>'center','width'=>80),
									   'apellido'=>array('justification'=>'center','width'=>100),
									   'caract'=>array('justification'=>'center','width'=>100),
									   'carasc'=>array('justification'=>'center','width'=>100),									   
									   'fecha'=>array('justification'=>'center','width'=>100),
									   'puntaje'=>array('justification'=>'center','width'=>50))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_t,$la_columnas,'',$la_config);
	
		
	    $io_pdf->ezSetY(425);
		$la_columnas=array(  'ascenso'=>'', 
	   						 'cedula'=>'',
		                     'nombre'=>'',
		                     'apellido'=>'',
		                     'caract'=>'',
		                     'carasc'=>'',							 
							 'fecha'=>'',
							 'puntaje'=>'');
							 
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
						 'cols'=>array('ascenso'=>array('justification'=>'center','width'=>65),
						               'cedula'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
						               'nombre'=>array('justification'=>'left','width'=>80),
									   'apellido'=>array('justification'=>'left','width'=>100),
									   'caract'=>array('justification'=>'center','width'=>100),
									   'carasc'=>array('justification'=>'center','width'=>100),	
									   'fecha'=>array('justification'=>'center','width'=>100),
									   'puntaje'=>array('justification'=>'center','width'=>50)));// Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);	
		
	    unset($la_data);
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
	$ls_titulo="<b>LISTADO DE ASCENSOS</b>";
	
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
        $lb_valido=$io_report->uf_listado_ascenso($ls_codperdes,$ls_codperhas,$ls_fechades,$ls_fechahas, $ls_orden);
      
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
			
			$li_totrow=$io_report->det_ascenso->getRowCount("nroreg");			
			
			for($lp_i=1;$lp_i<=$li_totrow;$lp_i++)
			{
			 $ls_codper=$io_report->det_ascenso->data["codper"][$lp_i];
			 $ls_cedula=$io_report->det_ascenso->data["cedper"][$lp_i];
			 $ls_cedula=number_format($ls_cedula,0,",",".");
			 $ls_nombre=$io_report->det_ascenso->data["nomper"][$lp_i]; 
			 $ls_apellido=$io_report->det_ascenso->data["apeper"][$lp_i];
			 $ls_ascenso=$io_report->det_ascenso->data["nroreg"][$lp_i];
			 $ls_fecha_asc=$io_report->det_ascenso->data["fecreg"][$lp_i];			 
			 $ls_fecha_asc=$io_funciones->uf_convertirfecmostrar($ls_fecha_asc);
			 $ls_puntaje=$io_report->det_ascenso->data["reseval"][$lp_i];
			
			$ls_caract1=$io_report->det_ascenso->data["caract1"][$lp_i];
			$ls_caract2=$io_report->det_ascenso->data["caract2"][$lp_i];
			
			if ($ls_caract2=="")
			{
				$ls_caract=$ls_caract1;
			
			}
			else
			{
				 $ls_caract=$ls_caract2;
			}
			
			
			
			 $ls_carasc1=$io_report->det_ascenso->data["descar"][$lp_i];
			 $ls_carasc2=$io_report->det_ascenso->data["denasicar"][$lp_i];
			 
			 if ($ls_carasc2=="")
			{
				$ls_carasc=$ls_carasc1;
			
			}
			else
			{
				 $ls_carasc=$ls_carasc2;
			}
			
			 
			 $la_data[$lp_i]=array('codper'=>$ls_codper,'cedula'=>$ls_cedula,'nombre'=>$ls_nombre,'apellido'=>$ls_apellido,
			                       'ascenso'=>$ls_ascenso,'fecha'=>$ls_fecha_asc,'puntaje'=>$ls_puntaje,
			                       'caract'=>$ls_caract,'carasc'=>$ls_carasc);   
						
		   }//end del for*/
		   uf_print_encabezado_detalle($la_data,&$io_pdf);
		   unset($la_data); 
					
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

	
	