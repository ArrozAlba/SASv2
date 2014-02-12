<?php

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Listado de Participantes por concurso
//  ORGANISMO: 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//-----------------------------------------------------------------------------------------------------------------------------------
///Elaborado por: Ing. María Beatriz Unda
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
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/09/2008		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_srh;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("SRH","sigesp_srh_r_listado_concursante.php",$ls_descripcion);
		return $lb_valido;
	}
	
//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	    {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/09/2008		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		
		$io_pdf->addText(540,770,6,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(546,764,6,date("h:i a")); // Agregar la Hora
		
		$io_pdf->ezSetY(715);	
		$la_data=array(array('titulo1'=>'<b>'.($as_titulo).'</b>'));					
		$la_columnas=array('titulo1'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
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

//-------------------------------------------------------------------------------------------------------------------------------
	
	function uf_print_datos_concurso ($as_codigo,$as_descrip,$as_cargo,$as_descar,$as_fecaper,$as_feccie,
				                      $as_cantcar,$as_tipo,$as_estatus,&$io_pdf)
	{
								  
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		//       Function: uf_print_datos_personales
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los datos del concurso
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/09/2008
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(680);
		$la_data=array(array('titulo1'=>'<b>DATOS DEL CONCURSO</b>'));					
		$la_columnas=array('titulo1'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('titulo1'=>array('justification'=>'left','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('name'=>'<b>CÓDIGO</b>',
		                  'name2'=>'<b>DESCRIPCIÓN</b>');	
		$la_columnas=array('name'=>'',
		                   'name2'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'left','width'=>150),
						               'name2'=>array('justification'=>'left','width'=>350))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('name'=>$as_codigo,
		                  'name2'=>$as_descrip);	
		$la_columnas=array('name'=>'',
		                   'name2'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'lef','width'=>150),
						               'name2'=>array('justification'=>'left','width'=>350))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('name'=>'<b>CÓDIGO CARGO</b>',
		                  'name2'=>'<b>DESCRIPCIÓN CARGO</b>', 
						  'name3'=>'<b>CANTIDAD CARGOS</b>');	
		$la_columnas=array('name'=>'',
		                   'name2'=>'',
						   'name3'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'left','width'=>100),
						               'name2'=>array('justification'=>'left','width'=>300),
									   'name3'=>array('justification'=>'left','width'=>100),)); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('name'=>$as_cargo,
		                  'name2'=>$as_descar, 
						  'name3'=>$as_cantcar);	
		$la_columnas=array('name'=>'',
		                   'name2'=>'',
						   'name3'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'left','width'=>100),
						               'name2'=>array('justification'=>'left','width'=>300),
									   'name3'=>array('justification'=>'center','width'=>100),)); // Justificación y ancho de la 
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('name'=>'<b>FECHA APERTURA</b>',
		                  'name2'=>'<b>FECHA CIERRE</b>', 
						  'name3'=>'<b>TIPO CONCURSO</b>',
						  'name4'=>'<b>ESTADO DEL CONCURSO</b>');	
		$la_columnas=array('name'=>'',
		                   'name2'=>'',
						   'name3'=>'',
						   'name4'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'left','width'=>125),
						               'name2'=>array('justification'=>'left','width'=>125),
									   'name3'=>array('justification'=>'left','width'=>100),
									   'name4'=>array('justification'=>'left','width'=>150))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('name'=>$as_fecaper,
		                  'name2'=>$as_feccie, 
						  'name3'=>$as_tipo,
						  'name4'=>$as_estatus);	
		$la_columnas=array('name'=>'',
		                   'name2'=>'',
						   'name3'=>'',
						   'name4'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'left','width'=>125),
						               'name2'=>array('justification'=>'left','width'=>125),
									   'name3'=>array('justification'=>'left','width'=>100),
									   'name4'=>array('justification'=>'left','width'=>150))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
	}// end function uf_print_encabezado_pagina	

//---------------------------------------------------------------------------------------------------------------------------------
function uf_print_datos_concursantes($aa_data,&$io_pdf)
	{
								  
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_datos_estudios
		//		   Access: private 
		//	    Arguments: aa_data // Arreglo con la data de los estudios
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los datos del concursante
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/09/2008
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(560);		
		$la_data[1]=array('name'=>'<b>CÓDIGO</b>',
		                  'name2'=>'<b>APELLIDOS Y NOMBRES</b>',
						  'name3'=>'<b>TIPO PERSONAL</b>',
						  'name4'=>'<b>ESTATUS</b>',
						  'name5'=>'<b>REQUISITO FALTANTE</b>');	
		$la_columnas=array('name'=>'',
		                  'name2'=>'',
						  'name3'=>'',
						  'name4'=>'',
						  'name5'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'center','width'=>55),
						               'name2'=>array('justification'=>'center','width'=>165),
									   'name3'=>array('justification'=>'center','width'=>65),
									   'name4'=>array('justification'=>'center','width'=>65),
									   'name5'=>array('justification'=>'center','width'=>150))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
			
		$la_columnas=array('name'=>'',
		                  'name2'=>'',
						  'name3'=>'',
						  'name4'=>'',
						  'name5'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'left','width'=>55),
						               'name2'=>array('justification'=>'left','width'=>165),
									   'name3'=>array('justification'=>'center','width'=>65),
									   'name4'=>array('justification'=>'center','width'=>65),
									   'name5'=>array('justification'=>'left','width'=>150))); // Justificación y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas,'',$la_config);
        unset($aa_data);
		unset($la_columnas);
		unset($la_config);
 } 
 //---------------------------------------------------------------------------------------------------------------------------------

function uf_print_totales($ai_total,$ai_totact,$ai_totexc,$as_estatus,&$io_pdf)
	{
								  
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totaless
		//		   Access: private 
		//	    Arguments: aa_data // Arreglo con la data de los familiares
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los datos personales del concursante
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/09/2008
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
		if ($as_estatus=="1")
		{
			$la_data[1]=array('name'=>'<b>TOTAL PARTICIPANTES: </b>'.$ai_total);	
			$la_columnas=array('name'=>'');					
			$la_config=array('showHeadings'=>1, // Mostrar encabezados
							 'fontSize' => 9, // Tamaño de Letras
							 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'width'=>200, // Ancho de la tabla
							 'maxWidth'=>200, // Ancho Máximo de la tabla
							 'xPos'=>410, // Orientación de la tabla
							 'cols'=>array('name'=>array('justification'=>'right','width'=>300))); // Justificación y ancho de la columna
			$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
			unset($la_data);
			unset($la_columnas);
			unset($la_config);

			$la_data[1]=array('name'=>'<b>TOTAL PARTICIPANTES ACTIVOS: </b>'.$ai_totact);	
			$la_columnas=array('name'=>'');					
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'fontSize' => 9, // Tamaño de Letras
							 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'width'=>200, // Ancho de la tabla
							 'maxWidth'=>200, // Ancho Máximo de la tabla
							 'xPos'=>410, // Orientación de la tabla
							 'cols'=>array('name'=>array('justification'=>'right','width'=>300))); // Justificación y ancho de la columna
			$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
			unset($la_data);
			unset($la_columnas);
			unset($la_config);
			$la_data[1]=array('name'=>'<b>TOTAL PARTICIPANTES EXCLUIDOS: </b>'.$ai_totexc);	
			$la_columnas=array('name'=>'');					
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'fontSize' => 9, // Tamaño de Letras
							 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'width'=>200, // Ancho de la tabla
							 'maxWidth'=>200, // Ancho Máximo de la tabla
							 'xPos'=>410, // Orientación de la tabla
							 'cols'=>array('name'=>array('justification'=>'right','width'=>300))); // Justificación y ancho de la columna
			$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
			unset($la_data);
			unset($la_columnas);
			unset($la_config);
		}
		elseif ($as_estatus=="2")
		{
			$la_data[1]=array('name'=>'<b>TOTAL PARTICIPANTES ACTIVOS: </b>'.$ai_totact);	
			$la_columnas=array('name'=>'');					
			$la_config=array('showHeadings'=>1, // Mostrar encabezados
							 'fontSize' => 9, // Tamaño de Letras
							 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'width'=>200, // Ancho de la tabla
							 'maxWidth'=>200, // Ancho Máximo de la tabla
							 'xPos'=>410, // Orientación de la tabla
							 'cols'=>array('name'=>array('justification'=>'right','width'=>300))); // Justificación y ancho de la columna
			$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
			unset($la_data);
			unset($la_columnas);
			unset($la_config);
		}
		elseif ($as_estatus=="3")
		{
			$la_data[1]=array('name'=>'<b>TOTAL PARTICIPANTES EXCLUIDOS: </b>'.$ai_totexc);	
			$la_columnas=array('name'=>'');					
			$la_config=array('showHeadings'=>1, // Mostrar encabezados
							 'fontSize' => 9, // Tamaño de Letras
							 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'width'=>200, // Ancho de la tabla
							 'maxWidth'=>200, // Ancho Máximo de la tabla
							 'xPos'=>410, // Orientación de la tabla
							 'cols'=>array('name'=>array('justification'=>'right','width'=>300))); // Justificación y ancho de la columna
			$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
			unset($la_data);
			unset($la_columnas);
			unset($la_config);
		}
			
		
 }
//---------------------------------------------------------------------------------------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/utilidades/class_funciones_srh.php");
	$io_fun_srh=new class_funciones_srh('../../');
	require_once("class_folder/sigesp_srh_class_report_2.php");
	$io_report=new sigesp_srh_class_report_2();
//----------------------------------------------------  Parámetros del encabezado  -------------------------------------------
	$ls_titulo="LISTADO DE CONCURSANTES";
	
//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------	
	$ls_tiporeporte=$io_fun_srh->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
 	$ls_codcondes=$io_fun_srh->uf_obtenervalor_get("curdes","");
	$ls_codconhas=$io_fun_srh->uf_obtenervalor_get("curhas","");
	$ls_orden=$io_fun_srh->uf_obtenervalor_get("orden","");	
	$ls_estconper=$io_fun_srh->uf_obtenervalor_get("estatus","");	
	
//---------------------------------------------------------------------------------------------------------------------------------
    $lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
        $lb_valido=$io_report->uf_select_concursante_concurso($ls_codcondes,$ls_codconhas,$ls_estconper,$ls_orden,$rs_datcon);
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
			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página			
			uf_print_encabezado_pagina($ls_titulo,&$io_pdf);
			$ls_auxcodigo="";
			$li_i=0;
			$li_conact=0;
			$li_conexc=0;
			while ((!$rs_datcon->EOF)&&($lb_valido))
		    {
		   		$ls_codigo=$rs_datcon->fields["codcon"];	
				
				
				if (($ls_codigo!=$ls_auxcodigo)&&($ls_auxcodigo!=""))
				{
					uf_print_datos_concurso($ls_auxcodigo,$ls_descrip,$ls_cargo,$ls_descar,$ls_fecaper,$ls_feccie,
				                            $ls_cantcar,$ls_tipo,$ls_estatus,$io_pdf);
											
					uf_print_datos_concursantes($la_data,$io_pdf);
					unset($la_data);
					$li_total=$li_conact+$li_conexc;
					uf_print_totales($li_total,$li_conact,$li_conexc,$ls_estconper,$io_pdf);
					$li_i=0;
					$li_conact=0;
				    $li_conexc=0;
					$io_pdf->ezNewPage(); // Insertar una nueva página
					$io_pdf->ezSetCmMargins(4,5,3,3); // Configuración de los margenes en centímetros
				}
				
							
				$ls_codper=$rs_datcon->fields["codper"];
				$ls_nombre= strtoupper(trim($rs_datcon->fields["apeper"]." ".$rs_datcon->fields["nomper"]));
				$ls_tipper=$rs_datcon->fields["tipper"];
				if ($ls_tipper=='I')
				{
					$ls_tipper="INTERNO";
				}
				else
				{
					$ls_tipper="EXTERNO";
				}
				
				$ls_estatus=$rs_datcon->fields["estconper"];
				if ($ls_estatus=='1')
				{
					$ls_estatus="ACTIVO";
					$li_conact=$li_conact+1;								
				}
				else
				{
					$ls_estatus="EXCLUIDO";
					$li_conexc=$li_conexc+1;
				}
				$ls_requisito="";
				$lb_valreq=$io_report->uf_select_requisitos_faltantes_concursante($ls_codigo,$ls_codper);
				if($lb_valreq)
				{
					$li_totrow=$io_report->DS2->getRowCount("desreqcon");				   
					for($li_d=1;$li_d<=$li_totrow;$li_d++)
					{
						$ls_requisito=$ls_requisito." - ".$io_report->DS2->data["desreqcon"][$li_d];
					}
				}
				else
				{
					$ls_requisito="NINGUNO";
				}
				
				
				$li_i=$li_i+1;
				$la_data[$li_i]=array('name'=>trim($ls_codper),'name2'=>$ls_nombre,'name3'=>$ls_tipper,'name4'=>$ls_estatus, 
				 					   'name5'=>trim($ls_requisito)); 
				
								
				$lb_valido=$io_report->uf_select_concurso($ls_codigo,$rs_data);
				if (($row=$io_report->io_sql->fetch_row($rs_data))&&($lb_valido)&&($ls_codigo!=$ls_auxcodigo))
				{
					$ls_descrip=$row["descon"];
					$ls_fecaper=$row["fechaaper"];
					$ls_feccie=$row["fechacie"];
					$ls_codcar1=$row["codasicar"];
					$ls_codcar2=$row["codcar"];
					 if ($ls_codcar1=="")
					 {	
						$ls_cargo=$row["codcar"];
						$ls_descar=trim  ($row["descar"]);
					 }
					else
					 {
						$ls_cargo=$row["codasicar"];
						$ls_descar=trim ($row["denasicar"]);
					 }
					$ls_cantcar=$row["cantcar"];
					$ls_estatus=$row["estatus"];
					$ls_fecaper=$io_funciones->uf_formatovalidofecha($ls_fecaper);
					$ls_fecaper=$io_funciones->uf_convertirfecmostrar($ls_fecaper);
					$ls_feccie=$io_funciones->uf_formatovalidofecha($ls_feccie);
					$ls_feccie=$io_funciones->uf_convertirfecmostrar($ls_feccie);
					$ls_tipo=strtoupper($row["tipo"]);
					
				}
				$ls_auxcodigo=$ls_codigo;
				
				$rs_datcon->MoveNext();
			}	
			uf_print_datos_concurso($ls_auxcodigo,$ls_descrip,$ls_cargo,$ls_descar,$ls_fecaper,$ls_feccie,
				                            $ls_cantcar,$ls_tipo,$ls_estatus,$io_pdf);
											
			uf_print_datos_concursantes($la_data,$io_pdf);
			unset($la_data);
			$li_total=$li_conact+$li_conexc;
			uf_print_totales($li_total,$li_conact,$li_conexc,$ls_estconper,$io_pdf);		
				
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