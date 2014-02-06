<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//  REPORTE: Formato de salida  de Solicitud de Ejecucion Presupuestaria
	//  ORGANISMO: Ninguno en particular
	//  ESTE FORMATO SE IMPRIME EN Bs Y EN BsF. SEGUN LO SELECCIONADO POR EL USUARIO
	//  MODIFICADO POR: ING.YOZELIN BARRAGAN         FECHA DE MODIFICACION : 14/08/2007
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
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creaci�n: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_srh;
		
		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("SRH","sigesp_srh_r_lote_revision.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo='Titulo',$as_numsol='0001',$ad_fecregsol='20/09/2007',&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_numsol // numero de la solicitud
		//	    		   ad_fecregsol // fecha de registro de la solicitud
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Funci�n que imprime los encabezados por p�gina
		// Fecha Creaci�n: 11/03/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();        
		$io_pdf->setStrokeColor(0,0,0);	
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],100,500,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(14,$as_titulo);
	
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_nombre,$as_cedula,$as_cargo,$as_departamento,$as_gerencia,$as_extension,$as_objetivo,
	                           $adt_revini1,$adt_revfin1,$adt_revini2,$adt_revfin2,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_numsol    // numero de la solicitud de ejecucion presupuestaria
		//	   			   as_dentipsol // Denominacion del tipo de solicitud
		//	   			   as_denuniadm // Denominacion de la Unidad Ejecutora solicitante
		//	   			   as_denfuefin // Denominacion de la fuente de financiamiento
		//	   			   as_codigo    // Codigo del Proveedor / Beneficiario
		//	   			   as_nombre    // Nombre del Proveedor / Beneficiario
		//	   			   as_consol    // Concepto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime la cabecera por concepto
		// Fecha Creaci�n: 17/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(102);
		$la_data[1]=array('titulo'=>'<b> ESTABLECIMIENTO Y SEGUIMIENTO DE LOS OBJETIVOS DE DESEMPE�O INDIVIDUAL </b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 16, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�nea
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				        //'outerLineThickness'=>0.5,
						// 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>540))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
		
		$io_pdf->ezSetDy(-15);
		$la_data[1]=array('nombre'=>$as_nombre,'cedula'=>$as_cedula,'cargo'=>$as_cargo,'departamento'=>$as_departamento,'gerencia'=>$as_gerencia, 
		                  'extension'=>$as_extension);
		$la_columnas=array('nombre'=>'APELLIDOS Y NOMBRES',
		                  'cedula'=>'CEDULA DE IDENTIDAD',
		                  'cargo'=>'CARGO',
		                  'departamento'=>'DEPARTAMENTO',
		                  'gerencia'=>'GERENCIA',
		                  'extension'=>'EXTENSION');
		                  
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('nombre'=>array('justification'=>'center','width'=>110), // Justificaci�n y ancho de la columna
						               'cedula'=>array('justification'=>'center','width'=>110), // Justificaci�n y ancho de la columna
						               'cargo'=>array('justification'=>'center','width'=>110), // Justificaci�n y ancho de la columna
						               'departamento'=>array('justification'=>'center','width'=>110), // Justificaci�n y ancho de la columna
						               'gerencia'=>array('justification'=>'center','width'=>110), // Justificaci�n y ancho de la columna
						               'extension'=>array('justification'=>'center','width'=>110))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		
		
		
		
		//$io_pdf->addLink("http://www.ros.co.nz/pdf/",50,100,500,120);
		//$io_pdf->rectangle(63,340,660,42);
		$io_pdf->ezSetDy(-12);
		$la_data[1]=array('nombre'=>'    '.trim($as_objetivo));
		$la_columnas=array('nombre'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>1, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>660))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'OBJETIVO FUNCIONAL DE LA UNIDAD',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
		
		$io_pdf->ezSetDy(-15);
		$la_data[1]=array('odi'=>'OBJETIVOS DE DESEMPE�O INDIVIDUAL','peso'=>'PESO',
		                 'rev1'=>"PRIMERA REVISION ".$adt_revini1." AL ".$adt_revini1." ",'rev2'=>"SEGUNDA REVISION ".$adt_revini2." AL ".$adt_revini2."");
		$la_columnas=array('odi'=>'',
		                  'peso'=>'',
		                  'rev1'=>'',
		                  'rev2'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('odi'=>array('justification'=>'center','width'=>300), // Justificaci�n y ancho de la columna
						               'peso'=>array('justification'=>'center','width'=>120), // Justificaci�n y ancho de la columna
						               'rev1'=>array('justification'=>'center','width'=>120), // Justificaci�n y ancho de la columna
						               'rev2'=>array('justification'=>'center','width'=>120))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
	  
		

	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------
	function print_cabecera_seccion_B(&$io_pdf)
	{
		$io_pdf->ezSetDy(-1);
		$la_data[1]=array('seccion'=>'<b>SECCI�N B</b>');
		$la_columnas=array('seccion'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>16, // Tamaño de Letras
						 'titleFontSize' => 18,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('seccion'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		//------------------------------------------------------------------------
		$la_data_2[1]=array('seccion_2'=>'<b>ESTABLECIMEINTO Y EVALUACI�N DE OBJETIVOS DE DESEMPE�O INDIVIDUAL</b>');
		$la_columnas=array('seccion_2'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('seccion_2'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_2,$la_columnas,'',$la_config);
		//-----------------------------------------------------------------------
		//------------------------------------------------------------------------
		$la_data_3[1]=array('seccion_3'=>'En esta secci�n se establece los Objetivos de Desempe�o individual que el funcionario que debe cumplir en el per�odo a evaluar');
		$la_columnas=array('seccion_3'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('seccion_3'=>array('justification'=>'center','width'=>600))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_3,$la_columnas,'',$la_config);
		//-----------------------------------------------------------------------		
	}
	
	//------------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_seccion_B($as_data,$total1,$total2,&$io_pdf)
	{   
	    $io_pdf->ezSetDy(-5);
	    $la_data_titulo[1]=array('name1'=>'OBJETIVO DE DESEMPE�O INDIVIDUAL',
		                         'name2'=>'PESO',
								 'name3'=>'1',
								 'name4'=>'2',
								 'name5'=>'3',
								 'name6'=>'4',
								 'name7'=>'5',
								 'name8'=>'PESO x RANGO');
		$la_columnas=array('name1'=>'',
		                   'name2'=>'',
						   'name3'=>'',
					       'name4'=>'',
						   'name5'=>'',
						   'name6'=>'',
						   'name7'=>'',
						   'name8'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>300),
						               'name2'=>array('justification'=>'center','width'=>70),
									   'name3'=>array('justification'=>'center','width'=>50),
									   'name4'=>array('justification'=>'center','width'=>50),
									   'name5'=>array('justification'=>'center','width'=>50),
									   'name6'=>array('justification'=>'center','width'=>50),
									   'name7'=>array('justification'=>'center','width'=>50),
									   'name8'=>array('justification'=>'center','width'=>90))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_titulo,$la_columnas,'',$la_config);
		
		//--------------------detalles----------------------------------------------------------------------
		$la_columnas=array('name1'=>'',
		                   'name2'=>'',
						   'name3'=>'',
					       'name4'=>'',
						   'name5'=>'',
						   'name5'=>'',
						   'name6'=>'',
						   'name7'=>'',
						   'name8'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>300),
						               'name2'=>array('justification'=>'center','width'=>70),
									   'name3'=>array('justification'=>'center','width'=>50),
									   'name4'=>array('justification'=>'center','width'=>50),
									   'name5'=>array('justification'=>'center','width'=>50),
									   'name6'=>array('justification'=>'center','width'=>50),
									   'name7'=>array('justification'=>'center','width'=>50),
									   'name8'=>array('justification'=>'center','width'=>90))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($as_data,$la_columnas,'',$la_config);
		//--------------------------------------------------------------------------------------------------
		//-----------------------totales---------------------------------------------------------------------
		$la_data_totales[1]=array('total1'=>'','total2'=>$total1,'total3'=>'TOTAL','total4'=>$total2);
		$la_columnas=array('total1'=>'','total2'=>'','total3'=>'','total4'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('total1'=>array('justification'=>'center','width'=>300),
						               'total2'=>array('justification'=>'center','width'=>70),
									   'total3'=>array('justification'=>'right','width'=>250),
									   'total4'=>array('justification'=>'center','width'=>90))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_totales,$la_columnas,'',$la_config);
		//-----------------------------------------------------------------------------------------------------------
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_seccion_C($as_data,$total1,$total2,&$io_pdf)
	{   
	    $io_pdf->ezSetDy(-5);
	    $la_data_titulo[1]=array('name1'=>'COMPETENCIAS',
		                         'name2'=>'PESO',
								 'name3'=>'1',
								 'name4'=>'2',
								 'name5'=>'3',
								 'name6'=>'4',
								 'name7'=>'5',
								 'name8'=>'PESO x RANGO');
		$la_columnas=array('name1'=>'',
		                   'name2'=>'',
						   'name3'=>'',
					       'name4'=>'',
						   'name5'=>'',
						   'name6'=>'',
						   'name7'=>'',
						   'name8'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>300),
						               'name2'=>array('justification'=>'center','width'=>70),
									   'name3'=>array('justification'=>'center','width'=>50),
									   'name4'=>array('justification'=>'center','width'=>50),
									   'name5'=>array('justification'=>'center','width'=>50),
									   'name6'=>array('justification'=>'center','width'=>50),
									   'name7'=>array('justification'=>'center','width'=>50),
									   'name8'=>array('justification'=>'center','width'=>90))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_titulo,$la_columnas,'',$la_config);
		
		//--------------------detalles----------------------------------------------------------------------
		$la_columnas=array('name1'=>'',
		                   'name2'=>'',
						   'name3'=>'',
					       'name4'=>'',
						   'name5'=>'',
						   'name5'=>'',
						   'name6'=>'',
						   'name7'=>'',
						   'name8'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>300),
						               'name2'=>array('justification'=>'center','width'=>70),
									   'name3'=>array('justification'=>'center','width'=>50),
									   'name4'=>array('justification'=>'center','width'=>50),
									   'name5'=>array('justification'=>'center','width'=>50),
									   'name6'=>array('justification'=>'center','width'=>50),
									   'name7'=>array('justification'=>'center','width'=>50),
									   'name8'=>array('justification'=>'center','width'=>90))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($as_data,$la_columnas,'',$la_config);
		//--------------------------------------------------------------------------------------------------
		//-----------------------totales---------------------------------------------------------------------
		$la_data_totales[1]=array('total1'=>'','total2'=>$total1,'total3'=>'TOTAL','total4'=>$total2);
		$la_columnas=array('total1'=>'','total2'=>'','total3'=>'','total4'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('total1'=>array('justification'=>'center','width'=>300),
						               'total2'=>array('justification'=>'center','width'=>70),
									   'total3'=>array('justification'=>'right','width'=>250),
									   'total4'=>array('justification'=>'center','width'=>90))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_totales,$la_columnas,'',$la_config);
		//-----------------------------------------------------------------------------------------------------------
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
		function print_cabecera_seccion_C(&$io_pdf)
	      {
		$io_pdf->ezSetDy(-1);
		$la_data[1]=array('seccion'=>'<b>SECCI�N C</b>');
		$la_columnas=array('seccion'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>16, // Tamaño de Letras
						 'titleFontSize' => 18,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('seccion'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		//------------------------------------------------------------------------
		$la_data_2[1]=array('seccion_2'=>'<b>EVALUACI�N DE LAS COMPETENCIAS</b>');
		$la_data_2[2]=array('seccion_2'=>'<b>NIVEL ADMINISTRATIVO</b>');
		$la_columnas=array('seccion_2'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('seccion_2'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_2,$la_columnas,'',$la_config);
		//-----------------------------------------------------------------------
		//------------------------------------------------------------------------
		$la_data_3[1]=array('seccion_3'=>'En esta secci�n se ponderan las competencias en relaci�n con el cargo y se evalua de acuerdo al gardoa en que est�n presentes en el evaluado');
		$la_columnas=array('seccion_3'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>650, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('seccion_3'=>array('justification'=>'center','width'=>650))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_3,$la_columnas,'',$la_config);
		//-----------------------------------------------------------------------		
	}
	//------------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($adt_fecini,$adt_fecfin,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-1);
		$la_data[1]=array('periodo'=>'PRERIODO A EVALUAR:              DESDE     '.$adt_fecini.'               HASTA    '.$adt_fecfin);
		$la_columnas=array('periodo'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('periodo'=>array('justification'=>'left','width'=>660))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$tm=250-(200/2);
		$io_pdf->addText(315,425,14,"<b>EVALUACION DE DESEMPE�O</b>"); // Agregar el t�tulo
		$io_pdf->ezSetDy(-50);

	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
		function print_cabecera_seccion_D(&$io_pdf)
	      {
		$io_pdf->ezSetDy(-1);
		$la_data[1]=array('seccion'=>'<b>SECCI�N D</b>');
		$la_columnas=array('seccion'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>16, // Tamaño de Letras
						 'titleFontSize' => 18,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('seccion'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		//------------------------------------------------------------------------
		$la_data_3[2]=array('seccion_2'=>'En esta secci�n se obtendra el rango de actuaci�n del evaluado:');
		$la_columnas=array('seccion_2'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>650, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('seccion_2'=>array('justification'=>'left','width'=>650))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_3,$la_columnas,'',$la_config);
		//-----------------------------------------------------------------------		
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle2($adt_fecini,$adt_fecfin,$as_nombre,$as_cedper,$as_deasicar,$as_codasicar,$as_desuniadm,$ls_titulo,$as_codnom,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);
		$la_data[1]=array('nombre'=>'APELLIDOS  Y NOMBRE :'.$as_nombre);
		$la_columnas=array('nombre'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>660))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'DATOS DEL '.$ls_titulo,$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->ezSetDy(-1);
		$la_data[1]=array('cedula'=>' CEDULA DE IDENTIDAD :'.$as_cedper,'codnom'=>' C�DIGO N�MINA: '.$as_codnom);
		$la_columnas=array('cedula'=>'','codnom'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('cedula'=>array('justification'=>'left','width'=>330),
						 			   'codnom'=>array('justification'=>'left','width'=>330))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->ezSetDy(-1);
		$la_data[1]=array('titulo'=>' TITULO DEL CARGO :'.$as_deasicar,'nomina'=>' CODIGO DEL CARGO :'.$as_codasicar);
		$la_columnas=array('titulo'=>'','nomina'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>330), // Justificaci�n y ancho de la columna
						               'nomina'=>array('justification'=>'left','width'=>330))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->ezSetDy(-1);
		$la_data[1]=array('unidad'=>' UBICACION ADMINISTRATIVA:  '.$as_desuniadm);
		$la_columnas=array('unidad'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('unidad'=>array('justification'=>'left','width'=>660))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_detalle
   //------------------------------------------------------------------------------------------------------------------------
   	function print_detalle_seccion_D($tot_B,$tot_C,$total_final,$actuacion,$ls_data_s,&$io_pdf)
	{
		$io_pdf->ezSetDy(-1);
		$la_data[1]=array('seccion'=>'<b>CLASIFICACI�N FINAL</b>');
		$la_columnas=array('seccion'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('seccion'=>array('justification'=>'left','width'=>650))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		//------------------------------------------------------------------------
		$io_pdf->ezSetDy(-5);
		$la_data_2[1]=array('tot_B1'=>'<b>Total Secci�n B: </b>','tot_B2'=>$tot_B,'tot_3'=>'');
		$la_data_2[2]=array('tot_B1'=>'<b>Total Secci�n C: </b>','tot_B2'=>$tot_C,'tot_3'=>'');
		$la_columnas=array('tot_B1'=>'','tot_B2'=>'','tot_3'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>11, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('tot_B1'=>array('justification'=>'right','width'=>100),
						 			   'tot_B2'=>array('justification'=>'right','width'=>70),
						 			   'tot_3'=>array('justification'=>'left','width'=>430))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_2,$la_columnas,'',$la_config);
		//-----------------------------------------------------------------------
		//------------------------------------------------------------------------
		$io_pdf->ezSetDy(-5);
		$la_data_3[1]=array('tot_B1'=>'<b>Puntaje Final (B+C): </b>','tot_B2'=>$total_final,'tot_3'=>'','actuacion'=>'<b>Rango de Actuaci�n:</b> '.$actuacion);
		
		$la_columnas=array('tot_B1'=>'','tot_B2'=>'','tot_3'=>'','actuacion'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>11, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('tot_B1'=>array('justification'=>'left','width'=>130),
						 			   'tot_B2'=>array('justification'=>'right','width'=>40),
						 			   'tot_3'=>array('justification'=>'left','width'=>30),
						 			   'actuacion'=>array('justification'=>'left','width'=>400))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_3,$la_columnas,'',$la_config);
		//-----------------------------------------------------------------------
		$io_pdf->ezSetDy(-5);
		$la_data_4[1]=array('seccion'=>'<b>SECCI�N E</b>');
		$la_columnas=array('seccion'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>16, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('seccion'=>array('justification'=>'center','width'=>650))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_4,$la_columnas,'',$la_config);
		//-------------------------------------------------------------------------
		$la_data_5[1]=array('seccion_5'=>'En esta secci�n, se expresa comentarios con respecto a los resultados de la evaluaci�n del funcionario, as� como las acciones a seguir para mejorar las debilidades presentadas');
		$la_columnas=array('seccion_5'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>650, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('seccion_5'=>array('justification'=>'left','width'=>650))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_5,$la_columnas,'',$la_config);
		//------------------------------------------------------------------------------------------
		$io_pdf->ezSetDy(-5);
		$la_data_6[1]=array('seccion_6'=>'<b>COMENTARIOS DEL SUPERVISOR</b>');
		$la_columnas=array('seccion_6'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>650, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('seccion_6'=>array('justification'=>'center','width'=>650))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_6,$la_columnas,'',$la_config);
		//----------------------------------------------------------------------------
		
		
		$la_columnas=array('obs_sup'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>650, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('obs_sup'=>array('justification'=>'left','width'=>650))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($ls_data_s,$la_columnas,'',$la_config);				
	}
   //------------------------------------------------------------------------------------------------------------------------
   function uf_print_firmas(&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_data_F[1]=array('firma'=>'FIRMAS:');
		$la_columnas=array('firma'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('firma'=>array('justification'=>'left','width'=>660))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_F,$la_columnas,'',$la_config);
		//------------------------------------------------------------------------------------------------
		$la_data[1]=array('firma1'=>'SUPERVISOR INMEDIATO','firma2'=>'JEFE INMEDIATO DEL SUPERVISOR');
		$la_data[2]=array('firma1'=>'','firma2'=>'');
		$la_data[3]=array('firma1'=>'','firma2'=>'');
		$la_data[4]=array('firma1'=>'','firma2'=>'');
		
		$la_columnas=array('firma1'=>'','firma2'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('firma1'=>array('justification'=>'left','width'=>330),
						               'firma2'=>array('justification'=>'left','width'=>330))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		//----------------------------------------------------------------------------------------------------------
		$io_pdf->ezSetDy(-5);
		$la_data_E[1]=array('evaluado'=>'A SER LLENADO POR EL EVALUADO:');
		$la_columnas=array('evaluado'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('evaluado'=>array('justification'=>'left','width'=>660))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_E,$la_columnas,'',$la_config);
		//-------------------------------------------------------------------------
		$io_pdf->ezSetDy(-2);
		$la_data_Preg[1]=array('pregunta'=>'�Est� de acuerdo?','respuesta1'=>'Si:________','respuesta2'=>'No:________');
		$la_columnas=array('pregunta'=>'','respuesta1'=>'','respuesta2'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('pregunta'=>array('justification'=>'left','width'=>330),
						               'respuesta1'=>array('justification'=>'left','width'=>115),
						               'respuesta2'=>array('justification'=>'left','width'=>115))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_Preg,$la_columnas,'',$la_config);
		//--------------------------------------------------------------
		$io_pdf->ezSetDy(-15);
		$la_data_Comentario[1]=array('comentario'=>'<b>C O M E N T A R I O S</b>');
		$la_data_Comentario[2]=array('comentario'=>'');
		$la_data_Comentario[3]=array('comentario'=>'');
		$la_data_Comentario[4]=array('comentario'=>'');
		$la_data_Comentario[5]=array('comentario'=>'');
		$la_data_Comentario[6]=array('comentario'=>'');
		$la_data_Comentario[7]=array('comentario'=>'');
		$la_data_Comentario[8]=array('comentario'=>'');

		$la_columnas=array('comentario'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('comentario'=>array('justification'=>'center','width'=>660))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_Comentario,$la_columnas,'',$la_config);
		//--------------------------------------------------------------
		$io_pdf->ezSetDy(-15);
		$la_data_FE[1]=array('firma1'=>'NOMBRE Y APELLIDO Y FIRMA DEL EVALUADO','firma2'=>'FECHA');
		$la_data_FE[2]=array('firma1'=>'','firma2'=>'');
		$la_data_FE[3]=array('firma1'=>'','firma2'=>'');
		$la_data_FE[4]=array('firma1'=>'','firma2'=>'');
		
		$la_columnas=array('firma1'=>'','firma2'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('firma1'=>array('justification'=>'left','width'=>330),
						               'firma2'=>array('justification'=>'left','width'=>330))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_FE,$la_columnas,'',$la_config);
	}
	
	//------------------------------------------------------------------------------------------------------------------------
	function print_cabecera_seccion_F($ls_data_jefe,$rango_act,&$io_pdf)
	      {
		$io_pdf->ezSetDy(-1);
		$la_data[1]=array('seccion'=>'<b>SECCI�N F</b>');
		$la_columnas=array('seccion'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>16, // Tamaño de Letras
						 'titleFontSize' => 18,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('seccion'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		//------------------------------------------------------------------------
		$la_data_1[1]=array('seccion_2'=>'COMENTARIOS DE JEFE INMEDIATO DEL SUPERVISOR');
		$la_columnas=array('seccion_2'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>650, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('seccion_2'=>array('justification'=>'left','width'=>650))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_1,$la_columnas,'',$la_config);
		//-------------------------------------------------------------------------------
		$la_columnas=array('obs_jefe'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>650, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('obs_jefe'=>array('justification'=>'left','width'=>650))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($ls_data_jefe,$la_columnas,'',$la_config);
		//---------------------------------------------------------------------------------
		$io_pdf->ezSetDy(-35);
		$la_data_firma2[1]=array('firmas'=>'','firmas2'=>'','firmas3'=>$rango_act);
		$la_data_firma2[2]=array('firmas'=>'_______________________________________','firmas2'=>'','firmas3'=>'________________________________________');
		$la_data_firma2[3]=array('firmas'=>'FIRMA JEFE INMEDIATO DEL SUPERVISOR','firmas2'=>'','firmas3'=>'RANGO DE ACTUACI�N');
		$la_columnas=array('firmas'=>'','firmas2'=>'','firmas3'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>650, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('firmas'=>array('justification'=>'center','width'=>320),
						               'firmas2'=>array('justification'=>'left','width'=>50),
						               'firmas3'=>array('justification'=>'center','width'=>300))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_firma2,$la_columnas,'',$la_config);
		
	 }
	 //-----------------------------------------------------------------------------------------------------------------------
	 function print_cabecera_seccion_G(&$io_pdf)
	      {
		$io_pdf->ezSetDy(-1);
		$la_data[1]=array('seccion'=>'<b>SECCI�N G</b>');
		$la_columnas=array('seccion'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>16, // Tamaño de Letras
						 'titleFontSize' => 18,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('seccion'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		//------------------------------------------------------------------------
		$io_pdf->ezSetDy(-5);
		$la_data_1[1]=array('seccion_2'=>'COMENTARIOS DE LA SECCI�N DE ADIESTRAMIENTO Y CLASIFICACI�N DE SERVICIOA DEL DEPARTAMENTO TECNICO DE GERENCIA DE RECURSOS HUMANOS');
		$la_columnas=array('seccion_2'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>650, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('seccion_2'=>array('justification'=>'left','width'=>650))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_1,$la_columnas,'',$la_config);
		//--------------------------------------------------------------------------
		$io_pdf->ezSetDy(-15);
		$la_data_Comentario[1]=array('comentario'=>'<b>OPINION DE RECURSOS HUMANOS, SECCI�N ADIESTRAMIENTO Y CLASIFICACI�N DE SERVICIOS</b>');
		$la_data_Comentario[2]=array('comentario'=>'');
		$la_data_Comentario[3]=array('comentario'=>'');
		$la_data_Comentario[4]=array('comentario'=>'');
		$la_data_Comentario[5]=array('comentario'=>'');
		$la_data_Comentario[6]=array('comentario'=>'');
		$la_data_Comentario[7]=array('comentario'=>'');
		$la_data_Comentario[8]=array('comentario'=>'');

		$la_columnas=array('comentario'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('comentario'=>array('justification'=>'center','width'=>660))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_Comentario,$la_columnas,'',$la_config);
		//--------------------------------------------------------------------------
		$io_pdf->ezSetDy(-35);
		$la_data_firma2[1]=array('firmas'=>'','firmas2'=>'','firmas3'=>'');
		$la_data_firma2[2]=array('firmas'=>'__________________________________________','firmas2'=>'________________________________','firmas3'=>'________________________________');
		$la_data_firma2[3]=array('firmas'=>'FIRMA JEFE SECCI�N DE ADIESTRAMIENTO Y CLASIFICACI�N DE SERVICIOS','firmas2'=>'JEFE DEPARTAMENTO T�CNICO','firmas3'=>'FIRMA GERENTE DE RECURSOS HUMANOS');
		
		$la_columnas=array('firmas'=>'','firmas2'=>'','firmas3'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>650, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('firmas'=>array('justification'=>'center','width'=>270),
						               'firmas2'=>array('justification'=>'center','width'=>200),
						               'firmas3'=>array('justification'=>'center','width'=>200))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_firma2,$la_columnas,'',$la_config);
		
	 }
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
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
	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
	 $ls_titulo='<b>REVISION DE ODIS </b>';
	 //--------------variable que se toman de sigesp_srh_r_listado_evaluacioneficiencia.php------------------------------------------
	 $ls_codper=$_GET["codper"]; 
	 $ls_nroeval=$_GET["nroeval"];
	 $ldt_fecini=$_GET["fecini"];
	 $ldt_fecfin=$_GET["fecfin"];
	 
	 
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{       
		$lb_valido=$io_report->uf_select_odi_personas($ls_nroeval);		
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
		    $li_total=$io_report->DS->getRowCount("nroeval");
		    $li_aux=0;
			uf_print_encabezado_pagina('','','',&$io_pdf);
		    uf_print_detalle($ldt_fecini,$ldt_fecfin,$io_pdf);	   	
            
			for ($li_o=1;$li_o<=$li_total;$li_o++)
			{			 
			  $thisPageNum=$io_pdf->ezPageCount;			
			  $ls_tipo=$io_report->DS->getValue("tipo",$li_o);			
			  $ls_codper=$io_report->DS->getValue("codper",$li_o); 				  	 				
			  $lb_valido=$io_report->uf_select_registro_odi($ls_nroeval,$ls_codper,$ls_tipo);
			  $li_total2=$io_report->ds_detalle2->getRowCount("cedper");
			 
			  for($li_d=1;$li_d<=$li_total2;$li_d++)
			  {
			        $ls_codnom=$io_report->ds_detalle2->getValue("codnom",$li_d);
					$ls_codper2=$io_report->ds_detalle2->getValue("codper",$li_d);
					$ls_cedula=$io_report->ds_detalle2->getValue("cedper",$li_d); 	
			        $ls_cedula=number_format($ls_cedula,0,",",".");	
					$ls_codasicar=$io_report->ds_detalle2->getValue("codasicar",$li_d);
					$ls_deasicar=$io_report->ds_detalle2->getValue("denasicar",$li_d);			
					$ls_desuniadm=$io_report->ds_detalle2->getValue("desuniadm",$li_d);
					$ls_nomper=$io_report->ds_detalle2->getValue("nombre",$li_d);
					switch ($ls_tipo){
						case 'S':
							$ls_titulo='SUPERVISOR';
						break;
						case 'E':
							$ls_titulo='EVALUADOR';
						break;
						case 'P':
							$ls_titulo='EVALUADO';
						break;				
					}					
				
			    uf_print_detalle2($ldt_fecini, $ldt_fecfin, $ls_nomper, $ls_cedula, $ls_deasicar, $ls_codasicar, $ls_desuniadm,$ls_titulo,$ls_codnom,$io_pdf);					
			   }				  	
			 }			  
		   $io_pdf->ezNewPage(); // Insertar una nueva p�gina
		   $lb_valido1=$io_report->uf_select_dt_evaluacion_odi($ls_nroeval);
		   print_cabecera_seccion_B(&$io_pdf);
		   
		   $li_total3=$io_report->ds_detalle3->getRowCount("nroeval");
		   $total_peso=0;
		   $total_peso_rango=0;
			 for($li_t=1;$li_t<=$li_total3;$li_t++)
			  {		
				$rango=$io_report->ds_detalle3->getValue("rango",$li_t);
				$peso_rango=$io_report->ds_detalle3->getValue("peso_rango",$li_t);	
				$peso=$peso_rango/$rango;
				$total_peso=$total_peso+$peso;
		        $total_peso_rango=$total_peso_rango+$peso_rango;
				$odi=$io_report->ds_detalle3->getValue("odi",$li_t);
				switch ($rango)
				{
				  case 1:
				   $rango1='X';
				   $rango2='';
				   $rango3='';
				   $rango4='';
				   $rango5='';
				  break;
				  case 2:
				   $rango1='';
				   $rango2='X';
				   $rango3='';
				   $rango4='';
				   $rango5='';
				  break;
				  case 3:
				   $rango1='';
				   $rango2='';
				   $rango3='X';
				   $rango4='';
				   $rango5='';
				  break;	
				  case 4:
				   $rango1='';
				   $rango2='';
				   $rango3='';
				   $rango4='X';
				   $rango5='';
				  break;			
				   case 5:
				   $rango1='';
				   $rango2='';
				   $rango3='';
				   $rango4='';
				   $rango5='X';
				  break;
				}
			    $ls_data[$li_t]=array('name1'=>$odi,'name2'=>$peso,'name3'=>$rango1,'name4'=>$rango2,
				                      'name5'=>$rango3,'name6'=>$rango4,'name7'=>$rango5,'name8'=>$peso_rango);						
			  }	
			uf_print_detalle_seccion_B($ls_data,$total_peso,$total_peso_rango,&$io_pdf);
			
			$io_pdf->ezNewPage(); // Insertar una nueva p�gina
			print_cabecera_seccion_C(&$io_pdf);			
			$lb_valido=$io_report->uf_select_competencias_odi($ls_nroeval);
			$li_total4=$io_report->ds_detalle4->getRowCount("nroeval");
			$total_peso_c=0;
		    $total_peso_rango_c=0;
			for($li_c=1;$li_c<=$li_total4;$li_c++)
			{
			    $rango_c=$io_report->ds_detalle4->getValue("rango",$li_c);
				$peso_c=$io_report->ds_detalle4->getValue("peso",$li_c);	
				$peso_rango_c=$peso_c*$rango_c;
				$competencia=$io_report->ds_detalle4->getValue("denite",$li_c);
				$total_peso_c=$total_peso_c+$peso_c;
		        $total_peso_rango_c=$total_peso_rango_c+$peso_rango_c;
				switch ($rango_c)
				{
				  case 1:
				   $rango1_c='X';
				   $rango2_c='';
				   $rango3_c='';
				   $rango4_c='';
				   $rango5_c='';
				  break;
				  case 2:
				   $rango1_c='';
				   $rango2_c='X';
				   $rango3_c='';
				   $rango4_c='';
				   $rango5_c='';
				  break;
				  case 3:
				   $rango1_c='';
				   $rango2_c='';
				   $rango3_c='X';
				   $rango4_c='';
				   $rango5_c='';
				  break;	
				  case 4:
				   $rango1_c='';
				   $rango2_c='';
				   $rango3_c='';
				   $rango4_c='X';
				   $rango5_c='';
				  break;			
				   case 5:
				   $rango1_c='';
				   $rango2_c='';
				   $rango3_c='';
				   $rango4_c='';
				   $rango5_c='X';
				  break;
				}
				$ls_data_c[$li_c]=array('name1'=>$competencia,'name2'=>$peso_c,'name3'=>$rango1_c,'name4'=>$rango2_c,
				                        'name5'=>$rango3_c,'name6'=>$rango4_c,'name7'=>$rango5_c,'name8'=>$peso_rango_c);	
			}
		    uf_print_detalle_seccion_C($ls_data_c,$total_peso_c,$total_peso_rango_c,&$io_pdf);
			
			$io_pdf->ezNewPage(); // Insertar una nueva p�gina
			print_cabecera_seccion_D(&$io_pdf);
			$lb_valido=$io_report->uf_select_dt_evaluacion_desempe�o($ls_nroeval);
			$li_total5=$io_report->ds_detalle5->getRowCount("nroeval");
			
			for($li_e=1;$li_e<=$li_total5;$li_e++)
			{
				$total_odi=$io_report->ds_detalle5->getValue("totalodi",$li_e);
				$total_compe=$io_report->ds_detalle5->getValue("totalcompe",$li_e);
				$rango_act=$io_report->ds_detalle5->getValue("actuacion",$li_e);
				$total_final=$total_odi+$total_compe;
				$obs_sup=$io_report->ds_detalle5->getValue("obs_sup",$li_e);
				$obs_jefe_i=$io_report->ds_detalle5->getValue("obs_jefe",$li_e);				   
			}
			//---------------------------------------------------------------
			  
				$ELEMENTOS=explode("\\n",$obs_sup);
				
				
                   for($i=0;$i<count($ELEMENTOS);$i++) 
                   {
                   	 $valor=$ELEMENTOS[$i];
                     $ls_data_s[$i]=array('obs_sup'=>$valor);
                   }
               //--------------------------------------------------------------- 
			print_detalle_seccion_D($total_odi,$total_compe,$total_final,$rango_act,$ls_data_s,&$io_pdf);
			$io_pdf->ezNewPage(); // Insertar una nueva p�gina
			uf_print_firmas(&$io_pdf);
			
			$io_pdf->ezNewPage(); // Insertar una nueva p�gina
			//---------------------------------------------------------------
			  
				$ELEMENTOS2=explode("\\n",$obs_jefe_i);
				
				
                   for($j=0;$j<count($ELEMENTOS2);$j++) 
                   {
                   	 $valor2=$ELEMENTOS2[$j];
                     $ls_data_jefe[$j]=array('obs_jefe'=>$valor2);
                   }
               //--------------------------------------------------------------- 
			print_cabecera_seccion_F($ls_data_jefe,$rango_act,&$io_pdf);
			$io_pdf->ezNewPage(); // Insertar una nueva p�gina
			print_cabecera_seccion_G(&$io_pdf);
			
			if(($lb_valido==false)||($lb_valido1==false)) // Existe alg�n error � no hay registros
			 {
				print("<script language=JavaScript>");
				print(" alert('Ocurrio un error al generar el reporte');"); 
				print(" close();");
				print("</script>");
			 }
			else
			{
				for($li_i=1;$li_i<=$li_total;$li_i++)
				{	
				  $io_pdf->ezStopPageNumbers(1,1);		
				  $io_pdf->ezStream(); // Mo
				}  
			}
		       
     }

?>
