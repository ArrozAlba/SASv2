<?php
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

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_listadocomponente.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,755,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,540,11,$as_titulo); // Agregar el título
		$io_pdf->addText(712,560,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(718,553,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera1($as_codcom, $as_descom, &$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/07/2006 
		////////////
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(28);
		$la_data[1]=array('codigo'=>'<b>Componente: </b>'.$as_codcom." ".$as_descom);
		$la_columna=array('codigo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'rigth','width'=>600))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);		
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera( &$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/07/2006 
		////////////
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSety(520);
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(55,505,670,$io_pdf->getFontHeight(11));			
		$la_data_c[1]=array('rango'=>'<b>Rango</b>',
		                  'cedula'=>'<b>Cédula</b>',
						  'apellido'=>'<b>Apellido</b>',
						  'nombre'=>'<b>Nombre</b>',
						  'cargo'=>'<b>Cargo</b>','expediente'=>'<b>Resolución</b>');
		$la_columna=array('rango'=>'<b>Rango</b>',
		                  'cedula'=>'<b>Cédula</b>',
						  'apellido'=>'<b>Apellido</b>',
						  'nombre'=>'<b>Nombre</b>',
						  'cargo'=>'<b>Cargo</b>','expediente'=>'<b>Expediente</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos' => 396,
						 'cols'=>array('rango'=>array('justification'=>'center','width'=>150),// Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>90),
									   'apellido'=>array('justification'=>'center','width'=>90),
									   'cargo'=>array('justification'=>'center','width'=>180),
									   'expediente'=>array('justification'=>'center','width'=>100)));  // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_c,$la_columna,'',$la_config);	
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		///$io_pdf->ezSetDy(-8);		
		$la_columna=array('rango'=>'<b>Rango</b>',
		                  'cedula'=>'<b>Cédula</b>',
						  'apellido'=>'<b>Apellido</b>',
						  'nombre'=>'<b>Nombre</b>',
						  'cargo'=>'<b>Cargo</b>','expediente'=>'<b>Expediente</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('rango'=>array('justification'=>'left','width'=>150),// Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>90),
									   'apellido'=>array('justification'=>'left','width'=>90),
									   'cargo'=>array('justification'=>'left','width'=>180),
									   'expediente'=>array('justification'=>'left','width'=>100)));  // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>Listado de Personal por Componente</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_activo=$io_fun_nomina->uf_obtenervalor_get("activo","");
	$ls_egresado=$io_fun_nomina->uf_obtenervalor_get("egresado","");
	$ls_causaegreso=$io_fun_nomina->uf_obtenervalor_get("causaegreso","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_activono=$io_fun_nomina->uf_obtenervalor_get("activono","");
	$ls_vacacionesno=$io_fun_nomina->uf_obtenervalor_get("vacacionesno","");
	$ls_suspendidono=$io_fun_nomina->uf_obtenervalor_get("suspendidono","");
	$ls_egresadono=$io_fun_nomina->uf_obtenervalor_get("egresadono","");
	$ls_masculino=$io_fun_nomina->uf_obtenervalor_get("masculino","");
	$ls_femenino=$io_fun_nomina->uf_obtenervalor_get("femenino","");
	$ls_codcompdes=$io_fun_nomina->uf_obtenervalor_get("codcomdes","");
	$ls_codcomphas=$io_fun_nomina->uf_obtenervalor_get("codcomhas","");


	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	
	if($lb_valido)
	{
	   $lb_valido=$io_report->uf_buscar_componentes($ls_codcompdes,$ls_codcomphas);
	}
	
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.7,2.5,3,3); // Configuración de los margenes en centímetros		
		$io_pdf->ezStartPageNumbers(750,50,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->ds_componente->getRowCount("codcom");
		if ($li_totrow>0)
		{
		  uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		}
		else
		{
		  $lb_valido=false;
		}
		$la_dataP=array(); 		
		$ls_aux=0;	
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
		    $ls_aux++;
		    $ls_codcomp=$io_report->ds_componente->data["codcom"][$li_i];//// print $ls_codcomp;
			$ls_descomp=$io_report->ds_componente->data["descom"][$li_i];	
			uf_print_cabecera1($ls_codcomp, $ls_descomp, &$io_pdf);	
			uf_print_cabecera(&$io_pdf);	
			$lb_valido=$io_report->uf_listadocomponente_personal($ls_codnomdes,$ls_codnomhas,$ls_codperdes,$ls_codperhas,$ls_activo,
													  $ls_egresado,$ls_causaegreso,$ls_activono,$ls_vacacionesno,
													  $ls_suspendidono,$ls_egresadono,$ls_masculino,$ls_femenino,
													  $ls_codcomp,$ls_orden);
			$li_comp=$io_report->DS->getRowCount("codcom"); 
			for($li_j=1;(($li_j<=$li_comp)&&($lb_valido));$li_j++)
			{
			  $ls_rango=$io_report->DS->data["desrango"][$li_j];
			  $ls_cedula=$io_report->DS->data["cedper"][$li_j];
			  $ls_cedula=number_format($ls_cedula,0,",",".");	
			  $ls_nombre=$io_report->DS->data["nomper"][$li_j];
			  $ls_apellido=$io_report->DS->data["apeper"][$li_j];
			  $ls_rac=$io_report->DS->data["rac"][$li_j];
			  $ls_asicar=$io_report->DS->data["denasicar"][$li_j];
			  $ls_cargo=$io_report->DS->data["descar"][$li_j];
			  $ls_expediente=$io_report->DS->data["numexpper"][$li_j];
			  
			  if ($ls_rac==1)
			  {
			    $cargo_final=$ls_asicar;
			  }
			  else
			   {
			   	 $cargo_final=$ls_cargo;
			   }
			   $la_dataP[$li_j]=array('rango'=>$ls_rango,'cedula'=>$ls_cedula,'nombre'=> $ls_nombre,
			                         'apellido'=>$ls_apellido,'cargo'=>$cargo_final,'expediente'=>$ls_expediente);			
			}
			if ($la_dataP!="")
			  {
			    uf_print_detalle($la_dataP,&$io_pdf);
			    unset($la_dataP);				
			  }	
			 if ($ls_aux<$li_totrow)
			 {
			  $io_pdf->ezNewPage();		 
			 }
		}		
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algún error
		{
			print("<script language=JavaScript>");
		    print(" alert('No hay nada que Reportar');"); 
		    print(" close();");
		    print("</script>");	
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 