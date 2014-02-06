<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//  REPORTE: Formato de salida  de Evluaci�n de Eficienia
	//  ORGANISMO: IPSFA
	//  MODIFICADO POR: MAR�A BEATRIZ UNDA         FECHA DE MODIFICACION : 15/04/2008
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
		//	    Arguments: as_titulo // T�tulo del reporte
		//    Description: funci�n que guarda la seguridad de quien gener� el reporte
		//	   Creado Por: Mar�a Beatriz Unda
		// Fecha Creaci�n: 04/04/08		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_srh;
		
		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("SRH","sigesp_srh_p_necesidad_adiestramiento.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Funci�n que imprime los encabezados por p�gina
		// Fecha Creaci�n: 11/03/2007		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();        
		$io_pdf->setStrokeColor(0,0,0);		
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],80,500,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
				
				
		$io_pdf->addText(650,550,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(650,560,8,date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

function print_cabecera_causas(&$io_pdf)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: print_cabecera_causas
		//		   Access: private 
		//	    Arguments:  io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime la cabecera por causas de adiestramiento
		// Fecha Creaci�n: 04/08/2008		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

				
		$io_pdf->ezSetDy(-20);
		$io_pdf->Rectangle(86,100,614,232);
		$la_data[1]=array('seccion'=>'<b>CAUSAS QUE ORIGINAN LA NECESIDAD DE ADIESTRAMIENTO</b>');
		$la_columnas=array('seccion'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
						 'titleFontSize' => 18,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>600, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xPos'=>392, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('seccion'=>array('justification'=>'left','width'=>600))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		
	}
	
	//------------------------------------------------------------------------------------------------------------------------------------

	
function uf_print_items($as_data,&$io_pdf)
{   
	   
 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: print_cabecera_causas
		//		   Access: private 
		//	    Arguments:  io_pdf // Instancia de objeto pdf
		//                  $as_data // data con los datos de la causas de adiestramiento
		//    Description: funci�n que imprime la cabecera por causas de adiestramiento
		// Fecha Creaci�n: 04/08/2008		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	    		
		$la_columnas=array('name1'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xPos'=>392, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('name1'=>array('justification'=>'left','width'=>600))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($as_data,$la_columnas,'',$la_config);
		
}

//-----------------------------------------------------------------------------------------------------------------------------------

function print_competencias($as_comptec, &$io_pdf)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: print_competencias
		//		   Access: private 
		//	    Arguments:  io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime la cabecera por causas de adiestramiento
		// Fecha Creaci�n: 04/08/2008		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('seccion'=>'(Relacionadas con su actividad actual o futura en la Organizaci�n)');
		$la_columnas=array('seccion'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>600, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xPos'=>392, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('seccion'=>array('justification'=>'left','width'=>600))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'<b>COMPETENCIAS O ACTITUDES A SER FORTALECIDAS PROFESIONALES, T�CNICAS Y/O ADMINISTRATIVAS</b>',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('seccion'=>$as_comptec);
		$la_columnas=array('seccion'=>'');		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>600, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xPos'=>392, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('seccion'=>array('justification'=>'left','width'=>600))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
	}
	
	//------------------------------------------------------------------------------------------------------------------------------------

function print_competencias_genericas($aa_data, &$io_pdf)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: print_competencias
		//		   Access: private 
		//	    Arguments:  io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime la cabecera por causas de adiestramiento
		// Fecha Creaci�n: 04/08/2008		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-14);
		
		$la_data[1]=array('comp'=>'<b>COMPETENCIAS GENERICAS</b>');
		$la_columnas=array('comp'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>600, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xPos'=>392, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('comp'=>array('justification'=>'left','width'=>600))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('comp1'=>'<b>COMPETENCIAS GENERICA</b>',
		                  'comp2'=>'<b>PRIORIDAD</b>');
		
		$la_columnas=array('comp1'=>'',
		                   'comp2'=>'');		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>600, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xPos'=>392, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('comp1'=>array('justification'=>'center','width'=>400),
						 			   'comp2'=>array('justification'=>'center','width'=>200))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
		$la_columnas=array('comp1'=>'',
		                   'comp2'=>'');		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>600, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xPos'=>392, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('comp1'=>array('justification'=>'left','width'=>400),
						 			   'comp2'=>array('justification'=>'center','width'=>200))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
	}
	
	//---------------------------------------------------------------------------------------------------------------------------------
	
function print_seccion_adiestramiento($as_area, $as_obj, $as_estra, &$io_pdf)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: print_seccion_adiestramiento
		//		   Access: private 
		//	    Arguments:  io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime la parte de la secci�n de Adiestramiento
		// Fecha Creaci�n: 04/08/2008		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-14);
		
		$la_data[1]=array('name'=>'<b>PARA USO DE LA SECCI�N DE ADIESTRAMIENTO</b>');
		$la_columnas=array('name'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>600, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xPos'=>392, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('name'=>array('justification'=>'left','width'=>600))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('name1'=>'<b>�reas o Contenidos a ser Atendidos</b>',
		                  'name2'=>'<b>Objetivo del Adiestramiento</b>',
						  'name3'=>'<b>Estrategia de Capacitaci�n</b>');
		
		$la_columnas=array('name1'=>'',
		                   'name2'=>'',
						   'name3'=>'');		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>600, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xPos'=>392, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>200),
						 			   'name2'=>array('justification'=>'center','width'=>200),
									   'name3'=>array('justification'=>'center','width'=>200))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('name1'=>$as_area, 
		                  'name2'=>$as_obj,
						  'name3'=>$as_estra);
		
		$la_columnas=array('name1'=>'',
		                   'name2'=>'',
						   'name3'=>'');		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>600, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xPos'=>392, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('name1'=>array('justification'=>'left','width'=>200),
						 			   'name2'=>array('justification'=>'left','width'=>200),
									   'name3'=>array('justification'=>'left','width'=>200))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
		
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle2($as_titulo, $ad_fecha, $as_nomper, $as_cedula, $as_descargo, $as_desuniadm, $as_nivaca, $as_codsup, $as_nomsup,$as_cargosup,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por concepto
		//	   Creado Por: Mar�a Beatriz Unda
		// Fecha Creaci�n: 04/04/08 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->addText(226,485,14,$as_titulo); // Agregar el titulo
		
		$io_pdf->ezSetDy(-2);
		$la_data[1]=array('nombre'=>'<b>NOMBRES Y APELLIDOS DEL FUNCIONARIO</b>',
		                  'cedula'=>'<b>CEDULA</b>',
						  'cargo'=>'<b>CARGO</b>',
						  'nivel'=>'<b>NIVEL ACADEMICO</b>');
		$la_columnas=array('nombre'=>'',
		                  'cedula'=>'',
						  'cargo'=>'',
						  'nivel'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>710, // Ancho de la tabla
						 'maxWidth'=>710, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('nombre'=>array('justification'=>'center','width'=>220),
						               'cedula'=>array('justification'=>'center','width'=>80),
									   'cargo'=>array('justification'=>'center','width'=>160),
									   'nivel'=>array('justification'=>'center','width'=>150))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
			$la_data[1]=array('nombre'=>$as_nomper,
		                  'cedula'=>$as_cedula,
						  'cargo'=>$as_descargo,
						  'nivel'=>$as_nivaca);
		$la_columnas=array('nombre'=>'',
		                  'cedula'=>'',
						  'cargo'=>'',
						  'nivel'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>710, // Ancho de la tabla
						 'maxWidth'=>710, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('nombre'=>array('justification'=>'center','width'=>220),
						               'cedula'=>array('justification'=>'center','width'=>80),
									   'cargo'=>array('justification'=>'center','width'=>160),
									   'nivel'=>array('justification'=>'center','width'=>150))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
	   $la_data[1]=array('codsup'=>'<b>C�DIGO SUPERVISOR</b>',
		                  'nomsup'=>'<b>NOMBRE SUPERVISOR</b>',
						  'carsup'=>'<b>CARGO SUPERVISOR</b>',
						  'uniadm'=>'<b>UNIDAD ADMINISTRTIVA</b>',
						  'fecha'=>'<b>FECHA DEL DIAGNOSTICO</b>');
						  
		$la_columnas=array('codsup'=>'',
		                  'nomsup'=>'',
						  'carsup'=>'',
						  'uniadm'=>'',
						  'fecha'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>710, // Ancho de la tabla
						 'maxWidth'=>710, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('codsup'=>array('justification'=>'center','width'=>95),
						               'nomsup'=>array('justification'=>'center','width'=>160),
									   'carsup'=>array('justification'=>'center','width'=>125),
									   'uniadm'=>array('justification'=>'center','width'=>145),
									   'fecha'=>array('justification'=>'center','width'=>85))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
	  $la_data[1]=array('codsup'=>$as_codsup,
		                  'nomsup'=>$as_nomsup,
						  'carsup'=>$as_cargosup,
						  'uniadm'=>$as_desuniadm,
						  'fecha'=>$ad_fecha);
						  
		$la_columnas=array('codsup'=>'',
		                  'nomsup'=>'',
						  'carsup'=>'',
						  'uniadm'=>'',
						  'fecha'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>710, // Ancho de la tabla
						 'maxWidth'=>710, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('codsup'=>array('justification'=>'center','width'=>95),
						               'nomsup'=>array('justification'=>'center','width'=>160),
									   'carsup'=>array('justification'=>'center','width'=>125),
									   'uniadm'=>array('justification'=>'center','width'=>145),
									   'fecha'=>array('justification'=>'center','width'=>85))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
	}// end function uf_print_detalle
   //------------------------------------------------------------------------------------------------------------------------
   function uf_print_firmas(&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por concepto
		//	   Creado Por: Mar�a Beatriz Unda
		// Fecha Creaci�n: 04/04/08
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-20);
		$la_data_F[1]=array('firma'=>'<b>DATOS DEL ANALISTA DE ADIESTRAMIENTO NOMBRE Y APELLIDO</b>',
						    'firma2'=>'<b>FIRMA</b>',
							'firma3'=>'<b>FECHA</b>');
		$la_columnas=array('firma'=>'',
						   'firma2'=>'',
						   'firma3'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>600, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xPos'=>392, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('firma'=>array('justification'=>'center','width'=>300),
								       'firma2'=>array('justification'=>'center','width'=>150),
			   						   'firma3'=>array('justification'=>'center','width'=>150))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_F,$la_columnas,'',$la_config);
		unset($la_data_F);
		unset($la_columnas);
		unset($la_config);
		
			$la_data_F[1]=array('firma'=>'',
						    'firma2'=>'',
							'firma3'=>'');
		$la_columnas=array('firma'=>'',
						   'firma2'=>'',
						   'firma3'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>17, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>600, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xPos'=>392, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('firma'=>array('justification'=>'center','width'=>300),
								       'firma2'=>array('justification'=>'center','width'=>150),
			   						   'firma3'=>array('justification'=>'center','width'=>150))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_F,$la_columnas,'',$la_config);
		unset($la_data_F);
		unset($la_columnas);
		unset($la_config);
		
		
	}
	
	//------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------  Instancia de las clases  ---------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha = new class_fecha();	
	require_once("class_folder/sigesp_srh_class_report.php");
	$io_report  = new sigesp_srh_class_report('../../');
	require_once("../class_folder/utilidades/class_funciones_srh.php");
	$io_fun_srh=new class_funciones_srh("../../");	
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];	
	require_once("../../shared/class_folder/sigesp_include.php");
	$io_include=new sigesp_include();
	$io_conexion=$io_include->uf_conectar();
	require_once("../../shared/class_folder/class_sql.php");
	$io_sql=new class_sql($io_conexion);	
	//----------------------------------------------------  Par�metros del encabezado  --
	 
	 
	 $ls_nroreg=$_GET["nroreg"];
	 $ls_codper=$_GET["codper"];
	 $ls_codsup=$_GET["codsup"];
	 $ls_nomsup=$_GET["nomsup"];
	 $ls_cargosup=$_GET["cargosup"];
	 $ld_fecha=$_GET["fecha"];
	 $ls_titulo="<b>DETECCI�N DE NECESIDADES DE ADIESTRAMIENTO</b>";
	 //--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{       
		$lb_valido=$io_report->uf_select_deteccion_necesidad_adiestramiento($ls_nroreg);		
	}
	if($lb_valido==false) // Existe alg�n error � no hay registros
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	 }
	 else
	 {
	        error_reporting(E_ALL);
			set_time_limit(1800);
			ini_set('display_errors','off');
			$io_pdf = new Cezpdf("LETTER","landscape");
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
			$io_pdf->ezSetCmMargins(5,4,3,3);
			$io_pdf->ezStartPageNumbers(406,30,10,'','',1);//Insertar el n�mero de p�gina.
		    $li_total=$io_report->DS->getRowCount("nroreg");
		    $li_aux=0;
			uf_print_encabezado_pagina(&$io_pdf);		  	
            
			for ($li_o=1;$li_o<=$li_total;$li_o++)
			{			 
			  $thisPageNum=$io_pdf->ezPageCount;
			  $lb_valido=$io_report->uf_select_registro_persona_deteccion_adiestramiento($ls_codper);
			  $li_total2=$io_report->ds_detalle2->getRowCount("cedper");
			
			  for($li_d=1;$li_d<=$li_total2;$li_d++)
			  {
			       
					$ls_codper2=$io_report->ds_detalle2->getValue("codper",$li_d);
					$ls_cedula=$io_report->ds_detalle2->getValue("cedper",$li_d); 	
			        $ls_cedula=number_format($ls_cedula,0,",",".");	
					$ls_codasicar=$io_report->ds_detalle2->getValue("codasicar",$li_d);
					$ls_deasicar=$io_report->ds_detalle2->getValue("denasicar",$li_d);
					$ls_codcar=$io_report->ds_detalle2->getValue("codcargo",$li_d);
					$ls_descar=$io_report->ds_detalle2->getValue("descargo",$li_d);	
					
					if ($ls_deasicar=="Sin Asignaci�n de Cargo")
				    {
					  	$ls_codcargo=$ls_codcar;
					  	$ls_descargo = trim ($ls_descar);
				    }
				   if ($ls_descar=="Sin Cargo")
				    {
						$ls_codcargo=$ls_codasicar;
					  	$ls_descargo = trim ($ls_deasicar);
				    }
					
					$ls_nivaca=$io_report->ds_detalle2->getValue("nivacaper",$li_d);
					
					 switch($ls_nivaca)
					{
						
						case "":
							$ls_nivaca="Ninguno";
							break;
						case "0":
							$ls_nivaca="Ninguno";
							break;
						case "1":
							$ls_nivaca="Primaria";
							break;
						case "2":
							$ls_nivaca="Bachiller";
							break;
						case "3":
							$ls_nivaca="Tecnico Superior";
							break;
					   case "4":
							$ls_nivaca="Universitario";
							break;
					   case "5":
							$ls_nivaca="Maestria";
							break;
					  case "6":
							$ls_nivaca="Postgrado";
							break;
					  case "7":
							$ls_nivaca="Doctorado";
							break;
					}
							
					$ls_desuniadm=$io_report->ds_detalle2->getValue("desuniadm",$li_d);
					$ls_nomper=$io_report->ds_detalle2->getValue("nombre",$li_d);
								
					}					
				
			    uf_print_detalle2($ls_titulo, $ld_fecha, $ls_nomper, $ls_cedula, $ls_descargo, $ls_desuniadm, $ls_nivaca, $ls_codsup, $ls_nomsup,$ls_cargosup,$io_pdf);					
			  		  
		  
		   $rs_data="";
		   $lb_valido=$io_report->uf_select_causas_adiestramiento ($ls_nroreg,$rs_data);   
		   print_cabecera_causas(&$io_pdf);
		   
		   $li_t=0;	 
		   while ($row=$io_sql->fetch_row($rs_data))
		   {
		   	   $causa=trim ($row["dencauadi"]);	  				   
			    $ls_data[$li_t]=array('name1'=>$causa);				   	
				$li_t=$li_t+1;
 		   			  
		   }
		    uf_print_items($ls_data,$io_pdf);	
			$io_pdf->ezNewPage(); // Insertar una nueva p�gina
			
			
			
			$ls_comptec=($io_report->DS->getValue("comptec",$li_o))."\r\n";
			print_competencias($ls_comptec, $io_pdf);
			
			
		   $lb_valido2=$io_report->uf_select_competencias_adiestramiento ($ls_nroreg,$rs_data2);   
		   $li_t2=0;	 
		   while ($row=$io_sql->fetch_row($rs_data2))
		   {
		   	   $compe=trim ($row["dencompadi"]);	
			   $prioridad = trim ($row["prioridad"]);				   
			   
			   switch ($prioridad)
			   {
					case "0":
						$prioridad="No Aplica";
						break;
					case "1":
						$prioridad="Urgente";
						break;
					case "2":
						$prioridad="Importante";
						break;
					case "3":
						$prioridad="Puede Esperar";
						break;
				}
			   $ls_data2[$li_t2]=array('comp1'=>$compe,'comp2'=>$prioridad);				   	
			   $li_t2=$li_t2+1; 		   			  
		   }
		   print_competencias_genericas($ls_data2, $io_pdf);
		   $ls_areadi=($io_report->DS->getValue("areadi",$li_o))."\r\n";
		   $ls_objadi=($io_report->DS->getValue("objadi",$li_o))."\r\n";
		   $ls_estadi=($io_report->DS->getValue("estadi",$li_o))."\r\n";
		   print_seccion_adiestramiento ($ls_areadi,$ls_objadi,$ls_estadi,$io_pdf);
		
 		  }				  	
		}	
		  uf_print_firmas($io_pdf);			
		  $io_pdf->ezStopPageNumbers(1,1);		
		  $io_pdf->ezStream(); // Mo
		
		       
    

?>
