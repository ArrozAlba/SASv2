<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Reporte de Solicitudes de Empleo
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
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("SRH","sigesp_srh_listado_solicitudes_empleo.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 11/02/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(15,40,585,40);     
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],75,500,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo		

		$io_pdf->addText(540,770,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(546,764,6,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');

	    $io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetY(500);
		$la_data=array(array('titulo1'=>'<b>'.$as_titulo.'</b>'));
					
		$la_columnas=array('titulo1'=>'');
					

		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 12, // Tamaño de Letras
						 'titleFontSize' =>14,  // Tamaño de Letras de los títulos
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
		
	    $io_pdf->ezSetY(450);
		$la_data[1]=array('numerosoli'=>'<b>Nº de la Solicitud</b>',
		                     'nombre'=>'<b>          Nombre y Apellido</b>',
							 'cedula'=>'<b>Cédula</b>',
							 'profesion'=>'<b>Profesión</b>',
							 'fecha'=>'<b>Fecha de la Solicitud</b>',
							 'telefono'=>'<b>Teléfono</b>',
							 'email'=>'<b>Email</b>');
		$la_columnas=array('numerosoli'=>'',
						   'nombre'=>'',
						   'cedula'=>'',
						   'profesion'=>'',
						   'fecha'=>'',
						   'telefono'=>'',
						   'email'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numerosoli'=>array('justification'=>'center','width'=>63), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>180), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>63), // Justificación y ancho de la columna
									   'profesion'=>array('justification'=>'center','width'=>125), // Justificación y ancho de la columna
									   'fecha'=>array('justification'=>'center','width'=>65),
									   'telefono'=>array('justification'=>'center','width'=>70),
									   'email'=>array('justification'=>'center','width'=>155))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	
		$io_pdf->restoreState();
	    $io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	
	
     }// end function uf_print_encabezado_pagina
	 //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
 	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte.
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 11/02/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(425);
		$la_columnas=array('numerosoli'=>'',
						   'nombre'=>'',
						   'cedula'=>'',
						   'profesion'=>'',
						   'fecha'=>'',
						   'telefono'=>'',
						   'email'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('numerosoli'=>array('justification'=>'center','width'=>63), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>180), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>63), // Justificación y ancho de la columna
									   'profesion'=>array('justification'=>'center','width'=>125), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>65),
									   'telefono'=>array('justification'=>'center','width'=>70),
									   'email'=>array('justification'=>'left','width'=>155))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
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
       $ls_titulo="LISTADO DE SOLICITUDES DE EMPLEO"; 
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ld_fechades=$io_fun_srh->uf_obtenervalor_get("fechades","");
	$ld_fechahas=$io_fun_srh->uf_obtenervalor_get("fechahas","");
	$ls_nrosoldes=$io_fun_srh->uf_obtenervalor_get("nrosoldes","");
	$ls_nrosolhas=$io_fun_srh->uf_obtenervalor_get("nrosolhas","");
	$ls_orden=$io_fun_srh->uf_obtenervalor_get("orden","");
	$ls_sexo=$io_fun_srh->uf_obtenervalor_get("cmbsexo","");
	//-----------------------------------------------------------------------------------------------------------------------------------
	global $la_data;
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_select_solicitudes_empleo($ld_fechades,$ld_fechahas,$ls_nrosoldes,$ls_nrosolhas,$ls_orden,$ls_sexo); // Cargar el DS con los datos del reporte
		if($lb_valido==false) // Existe algún error ó no hay registros
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}
		else  // Imprimimos el reporte
		{
			 error_reporting(E_ALL);
			 set_time_limit(1800);
			$io_pdf = new Cezpdf("LETTER","landscape");
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
			$io_pdf->ezSetCmMargins(5,4,3,3);
			$io_pdf->ezStartPageNumbers(406,30,10,'','',1);//Insertar el número de página.
		  	$li_totrow=$io_report->DS->getRowCount("codemp");
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{ 
				$ls_numero=$io_report->DS->data["nrosol"][$li_i];
				$ls_cedula=$io_report->DS->data["cedsol"][$li_i];
				$ls_fecha=$io_report->DS->data["fecsol"][$li_i];
				$ls_nombresol=trim ($io_report->DS->data["nomsol"][$li_i]);
				$ls_apellidosol=trim ($io_report->DS->data["apesol"][$li_i]);
				$ls_direccion=$io_report->DS->data["dirsol"][$li_i];
				$ls_telefono=$io_report->DS->data["telmov"][$li_i];
				$ls_email=$io_report->DS->data["email"][$li_i];
				$ls_prof=$io_report->DS->data["despro"][$li_i];
				$ls_perfil=$io_report->DS->data["comsol"][$li_i];
				$ls_fecha=$io_funciones->uf_formatovalidofecha($ls_fecha);
				$ls_fecha=$io_funciones->uf_convertirfecmostrar($ls_fecha);
				$ls_cadena=$ls_nombresol."  ".$ls_apellidosol;
				$la_data[$li_i]=array('numerosoli'=>$ls_numero,'nombre'=>$ls_cadena,'cedula'=>$ls_cedula,'profesion'=>$ls_prof,
				                     'fecha'=>$ls_fecha,'telefono'=>$ls_telefono,
									 'email'=>$ls_email);
			}
			uf_print_encabezado_pagina($ls_titulo,$io_pdf);
			uf_print_detalle($la_data,$io_pdf);
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
	}
?>


