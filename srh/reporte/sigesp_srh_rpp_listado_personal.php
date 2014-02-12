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
	ini_set('memory_limit','512M');
	ini_set('max_execution_time','0');
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
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("SRH","sigesp_srh_r_listado_personal.php",$ls_descripcion);
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
		
   
		$io_pdf->ezSetY(650);
		$la_datatit[1]=array('codper'=>'<b>Código</b>',
							 'cedper'=>'<b>Cédula</b>',
							 'nomper'=>'<b>Nombre</b>',
							 'apeper'=>'<b>Apellido</b>',
							 'descar'=>'<b>                    Cargo</b>');
		$la_columnas=array('codper'=>'',
						   'cedper'=>'',
						   'nomper'=>'',
						   'apeper'=>'',
						   'descar'=>'',);
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
						 'cols'=>array('codper'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
									   'cedper'=>array('justification'=>'center','width'=>65),
									   'nomper'=>array('justification'=>'center','width'=>150),
						 			   'apeper'=>array('justification'=>'center','width'=>150),
									   'cargo'=>array('justification'=>'center','width'=>140))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->restoreState();
	    $io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina	
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
		$io_pdf->ezSetY(635);
		$la_columnas=array('codper'=>'',
						   'cedper'=>'',
						   'nomper'=>'',
						   'apeper'=>'',
						   'descar'=>'',);
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
						 'cols'=>array('codper'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
									   'cedper'=>array('justification'=>'center','width'=>65),
									   'nomper'=>array('justification'=>'center','width'=>150),
						 			   'apeper'=>array('justification'=>'center','width'=>150),
									   'descar'=>array('justification'=>'center','width'=>140))); // Justificación y ancho de la columna
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
	$ls_titulo="<b>Listado de Personal</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------	
	$ls_orden=$io_fun_srh->uf_obtenervalor_get("orden","");
 	$ls_codperdes =$io_fun_srh->uf_obtenervalor_get("codperdes",""); 
	$ls_codperhas =$io_fun_srh->uf_obtenervalor_get("codperhas",""); 
	//----------------------------------------------------------------------------------------------------------------------------------//
    $lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
        $lb_valido=$io_report->uf_select_personal_listado($ls_codperdes,$ls_codperhas,$ls_orden);
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
		    
			$lp_totrow=$io_report->ds_detalle->getRowCount("codper"); //print $lp_totrow;
			$li_aux=0;
			for($lp_p=1;$lp_p<=$lp_totrow;$lp_p++)
			{
				 $li_aux++;
				 $li_totrow=0;
				 $ls_codper=$io_report->ds_detalle->data["codper"][$lp_p];
				 $ls_cedper=$io_report->ds_detalle->data["cedper"][$lp_p];
				 $ls_nomper=$io_report->ds_detalle->data["nomper"][$lp_p];
				 $ls_apeper=$io_report->ds_detalle->data["apeper"][$lp_p];
				 
				 $ls_cargo1=trim ($io_report->ds_detalle->data["denasicar"][$lp_p]);
				 $ls_cargo2=trim ($io_report->ds_detalle->data["descar"][$lp_p]);
					
				  if ($ls_cargo1=="Sin Asignación de Cargo")
				  {
					 $ls_cargo=$ls_cargo2;
				  }
				  else 
				  {
					  $ls_cargo=$ls_cargo1;
				  }	
				 
				 
				 $la_data[$lp_p]=array('codper'=>$ls_codper,'cedper'=>$ls_cedper,'nomper'=>$ls_nomper,'apeper'=>$ls_apeper,
										  'descar'=>$ls_cargo); 
			
				
			   
		  /* if($li_aux<$lp_totrow)		
			 {
			 	$io_pdf->ezNewPage(); // Insertar una nueva página
			 }*/
		}
		 uf_print_detalle($la_data,$lp_totrow,&$io_pdf);
		unset($la_data);
		
		
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