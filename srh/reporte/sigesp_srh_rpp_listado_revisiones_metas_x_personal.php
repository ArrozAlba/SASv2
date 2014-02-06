<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Reporte de Revisiones de Metas por Personal
//  ORGANISMO: IPSFA
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
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_srh;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_relacionsolicitudes.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 11/02/2008
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
		$io_pdf->ezSetY(670);
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
						 'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
				      	 'cols'=>array('titulo1'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
	

	
		$io_pdf->restoreState();
	    $io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
     }// end function uf_print_encabezado_pagina
   //-----------------------------------------------------------------------------------------------------------------------------------
	
  //-----------------------------------------------------------------------------------------------------------------------------------
 
	
  //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_detalle($la_dataper,$li_totrow,$io_pdf)
 	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_detalle
		//		   Access: private 
		//	    Arguments: la_dataper // arreglo de información
		//				 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime la cabecera del personal.
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 11/02/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$io_pdf->ezSetY(645);
		$la_datap[1]=array('codigo'=>'<b>Código del Personal</b>',
		                     'nombre'=>'<b>                             Nombre y Apellido</b>',
							 'fechaini'=>'<b>Fecha de inicio</b>',
							 'fechafin'=>'<b>Fecha de fin</b>');
		$la_columnas=array('codigo'=>'',
						   'nombre'=>'',
						   'fechaini'=>'',
						   'fechafin'=>'');
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
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>265), // Justificación y ancho de la columna
						 			   'fechaini'=>array('justification'=>'center','width'=>145), // Justificación y ancho de la columna
						 			   'fechafin'=>array('justification'=>'center','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datap,$la_columnas,'',$la_config);
		unset($la_datap);
		unset($la_columnas);
		unset($la_config);
		$la_columnas=array('codigo'=>'',
						   'nombre'=>'',
						   'fechaini'=>'',
						   'fechafin'=>'');
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
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>265), // Justificación y ancho de la columna
						 			   'fechaini'=>array('justification'=>'center','width'=>145), // Justificación y ancho de la columna
						 			   'fechafin'=>array('justification'=>'center','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_dataper,$la_columnas,'',$la_config);
		unset($la_dataper);
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_cabecera_detalle
  //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_detalle,$li_totrow1,&$io_pdf)
 	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_detalle
		//		   Access: private 
		//	    Arguments: la_datalle // arreglo de información
		//				 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte.
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 11/02/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(603);
		$la_columnas=array('codigo'=>'Código',
						   'meta'=>'                                              Meta',
						   'valor'=>'Valor',
						   'fechaeje'=>'Fecha de Ejecución',
						   'obse'=>'                    Observación');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>40), // Justificación y ancho de la columna
						 			   'meta'=>array('justification'=>'left','width'=>260), // Justificación y ancho de la columna
						 			   'valor'=>array('justification'=>'center','width'=>40), // Justificación y ancho de la columna
						 			   'fechaeje'=>array('justification'=>'center','width'=>65),  // Justificación y ancho de la columna
									   'obse'=>array('justification'=>'left','width'=>165))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_detalle,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    require_once("../../shared/ezpdf/class.ezpdf.php");	
	require_once("class_folder/sigesp_srh_class_report.php");
	$io_report=new sigesp_srh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/utilidades/class_funciones_srh.php");
	$io_fun_srh=new class_funciones_srh('../../');
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
       $ls_titulo="<b>LISTADO DE REVISIONES DE METAS POR PERSONAL</b>"; 
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ld_fechades=$io_fun_srh->uf_obtenervalor_get("fechades","");
	$ld_fechahas=$io_fun_srh->uf_obtenervalor_get("fechahas","");
	$ls_codperdes=$io_fun_srh->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_srh->uf_obtenervalor_get("codperhas","");	
	//-----------------------------------------------------------------------------------------------------------------------------------
	global $la_data;
	global $la_detalle;
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_select_revisiones_metas_x_personal($ld_fechades,$ld_fechahas,$ls_codperdes,$ls_codperhas); // Cargar el DS con los datos del reporte
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
		    $io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(4,3,3,3); // Configuración de los margenes en centímetros
			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página
		  	uf_print_encabezado_pagina($ls_titulo,$io_pdf);
			$li_totrow=$io_report->DS->getRowCount("nroreg");
			$li_aux=0;
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$li_aux++;
				$ls_nroreg=$io_report->DS->data["nroreg"][$li_i];
				$ls_codigo=$io_report->DS->data["codper"][$li_i];
				$ls_fechaini=$io_report->DS->data["fecini"][$li_i];
				$ls_fechafin=$io_report->DS->data["fecfin"][$li_i];
				$ls_nombreper=$io_report->DS->data["nomper"][$li_i];
				$ls_apellidoper=$io_report->DS->data["apeper"][$li_i];
				$ls_fechaini=$io_funciones->uf_formatovalidofecha($ls_fechaini);
				$ls_fechaini=$io_funciones->uf_convertirfecmostrar($ls_fechaini);
				$ls_fechafin=$io_funciones->uf_formatovalidofecha($ls_fechafin);
				$ls_fechafin=$io_funciones->uf_convertirfecmostrar($ls_fechafin);
				$ls_cadena=$ls_nombreper."  ".$ls_apellidoper;
				$la_dataper[$li_i]=array('codigo'=>$ls_codigo,'nombre'=>$ls_cadena,
									  'fechaini'=>$ls_fechaini,'fechafin'=>$ls_fechafin);
				uf_print_cabecera_detalle($la_dataper,$li_totrow,$io_pdf);
                unset($la_dataper);
				$io_report->uf_print_detalle_metas_x_personal($ls_nroreg,$ld_fechades,$ld_fechahas,$ls_codperdes,$ls_codperhas);// Cargar el DS con los datos del reporte
				$li_totrow1=$io_report->ds_detalle->getRowCount("codmeta");
				for($li_p=1;$li_p<=$li_totrow1;$li_p++)
				{
					$ls_codigo=$io_report->ds_detalle->data["codmeta"][$li_p];
					$ls_meta=$io_report->ds_detalle->data["meta"][$li_p];
					$ls_valor=$io_report->ds_detalle->data["valor"][$li_p];
					$ls_fechaeje=$io_report->ds_detalle->data["feceje"][$li_p];
					$ls_obse=$io_report->ds_detalle->data["obsmet"][$li_p];
					$ls_fechaeje=$io_funciones->uf_formatovalidofecha($ls_fechaeje);
					$ls_fechaeje=$io_funciones->uf_convertirfecmostrar($ls_fechaeje);
					$la_detalle[$li_p]=array('codigo'=>$ls_codigo,'meta'=>$ls_meta,
										  'valor'=>$ls_valor,'fechaeje'=>$ls_fechaeje,'obse'=>$ls_obse);
				} //fin del for
				uf_print_detalle($la_detalle,$li_totrow1,$io_pdf);
				if($li_aux<$li_totrow)
				{
					$io_pdf->ezNewPage(); // Insertar una nueva página
				}
			}// fin del for
		 }  // fin del else
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



