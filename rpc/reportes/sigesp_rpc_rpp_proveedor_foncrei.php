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
	ini_set('max_execution_time ','0');

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
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(20,40,578,40);
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],35,715,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$io_pdf->addText(230,725,11,$as_titulo); // Agregar el título
		$io_pdf->addText(470,735,10,"Fecha: ".date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(476,715,10,"Hora: ".date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera(&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/07/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSety(700);
		$la_data[1]=array('codigo'=>'<b>CÓDIGO</b>',
						  'nombre'=>'<b>NOMBRE</b>',
						  'rif'=>'<b>RIF</b>',
						  'telefono'=>'<b>TELÉFONO</b>',
						  'ocei'=>'<b>No OCEI</b>');
		$la_columna=array('codigo'=>'',
						  'nombre'=>'',
						  'rif'=>'',
						  'telefono'=>'',
						  'ocei'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>62), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>188), // Justificación y ancho de la columna
						 			   'rif'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'telefono'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
						 			   'ocei'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('direccion'=>'<b>DIRECCION</b>');
		$la_columna=array('direccion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('direccion'=>array('justification'=>'left','width'=>550))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->line(30,670,570,670);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,$ls_dirpro,$ls_denesp,$ls_obspro,&$io_pdf)
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_detalle
	//		   Access: private 
	//	    Arguments: la_data // arreglo de información
	//	   			   io_pdf // Objeto PDF
	//    Description: función que imprime el detalle
	//	   Creado Por: Ing. Yesenia Moreno
	// Fecha Creación: 21/04/2006 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('codigo'=>'<b>Código</b>','nombre'=>'<b>Nombre</b>','rif'=>'<b>Rif</b>','telefono'=>'<b>Teléfono</b>','ocei'=>'<b>No OCEI</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.8,0.8,0.8),
						 'shadeCol2'=>array(0.9,0.9,0.9),
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>62), // Justificación y ancho de la columna
									   'nombre'=>array('justification'=>'left','width'=>188), // Justificación y ancho de la columna
									   'rif'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'telefono'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna
									   'ocei'=>array('justification'=>'left','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_datos[1]=array('direccion'=>$ls_dirpro);
		$la_datos[2]=array('direccion'=>'');
		$la_datos[3]=array('direccion'=>'<b>ESPECIALIDAD DE LA EMPRESA:</b>   '.$ls_obspro);
		$la_columna=array('direccion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('direccion'=>array('justification'=>'left','width'=>550))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_documentos($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_documentos
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf    // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_datos[1]=array('direccion'=>'<b>DOCUMENTOS CONSIGNADOS</b>');
		$la_columna=array('direccion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('direccion'=>array('justification'=>'center','width'=>550))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);
		unset($la_datos);
		unset($la_columna);
		unset($la_config);
		$la_columna = array('denominacion'=>'                                <b>Denominación</b>','recepcion'=>'<b>Fecha Recepción</b>','vencimiento'=>'<b>Fecha Vencimiento</b>','estatus'=>'<b>Estatus</b>');
		$la_config  = array('showHeadings'=>1,     // Mostrar encabezados
							'fontSize' => 8,       // Tamaño de Letras
							'titleFontSize' => 5, // Tamaño de Letras de los títulos
							'showLines'=>0,        // Mostrar Líneas
							'shaded'=>0,           // Sombra entre líneas
							'xOrientation'=>'center', // Orientación de la tabla
							'width'=>550, // Ancho de la tabla
							'maxWidth'=>550,
							'cols'=>array('denominacion'=>array('justification'=>'left','width'=>300), // Justificación y ancho de la columna Nro de Operacion.
										  'recepcion'=>array('justification'=>'center','width'=>80),
										  'vencimiento'=>array('justification'=>'center','width'=>90),
										  'estatus'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_documentos
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_fin(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_fin
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf    // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_datos[1]=array('direccion'=>'_________________________________________________________________________________________________________________________');
		$la_columna=array('direccion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('direccion'=>array('justification'=>'left','width'=>550))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);
	}// end function uf_print_fin
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	$io_in=new sigesp_include();
	$con=$io_in->uf_conectar();
	
	require_once("sigesp_rpc_class_report.php");
	$io_report = new sigesp_rpc_class_report($con);
	
	require_once("../../shared/class_folder/class_sql.php");
	$io_sql = new class_sql($con);

	require_once("../../shared/class_folder/class_funciones.php");
	$io_funcion = new class_funciones();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>Listado de Proveedores</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	if (array_key_exists("hidtipo",$_POST))
	   {
		 $ls_tipo=$_POST["hidtipo"];
	   }
	else
	   {
		 $ls_tipo=$_GET["hidtipo"];
	   }
	if (array_key_exists("hidorden",$_POST))
	   {
		 $li_orden=$_POST["hidorden"];
	   }
	else
	   {
		 $li_orden=$_GET["hidorden"];
	   }
	if (array_key_exists("hidcodprov1",$_POST))
	   {
		 $ls_codprov1=$_POST["hidcodprov1"];
	   }
	else
	   {
		 $ls_codprov1=$_GET["hidcodprov1"];
	   }
	if (array_key_exists("hidcodprov2",$_POST))
	   {
		 $ls_codprov2=$_POST["hidcodprov2"];
	   }
	else
	   {
		 $ls_codprov2=$_GET["hidcodprov2"];
	   }
	if (array_key_exists("hidcodesp",$_POST))
	   {
		 $ls_codesp=$_POST["hidcodesp"];
	   }
	else
	   {
		 $ls_codesp=$_GET["hidcodesp"];
	   }
	$lb_valido=true;
	$rs_proveedor=$io_report->uf_load_proveedores($ls_codemp,$li_orden,$ls_tipo,$ls_codprov1,$ls_codprov2,$ls_codesp,$lb_valido);
	if ($lb_valido)
    {
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(4.2,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_total=$io_sql->num_rows($rs_proveedor);
		//print "REGISTROS ENCONTRADOS--->".$li_total;
		uf_print_cabecera($io_pdf);
		$data=$io_sql->obtener_datos($rs_proveedor);
		$la_documentos[0]="";
		
		for ($z=1;$z<=$li_total;$z++)
		{//1
	        $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$ls_codpro=$data["cod_pro"][$z];
			$ls_nompro=$data["nompro"][$z];
			$ls_rifpro=$data["rifpro"][$z];
			$ls_ocei=$data["ocei_no_reg"][$z];
			$ls_telpro=$data["telpro"][$z];
			$ls_dirpro=$data["dirpro"][$z];
			$ls_obspro=$data["obspro"][$z];
			$ls_denesp=$io_report->uf_load_especialidadproveedor($ls_codpro,&$lb_valido);
			$la_data[1]=array('codigo'=>$ls_codpro,'nombre'=>$ls_nompro,'rif'=>$ls_rifpro,'telefono'=>$ls_telpro,'ocei'=>$ls_ocei);
			uf_print_detalle($la_data,$ls_dirpro,$ls_denesp,$ls_obspro,$io_pdf); // Imprimimos el detalle			
			$rs_documentos = $io_report->uf_select_documentosproveedores($ls_codemp,$ls_codpro,$la_documentos,"1",$lb_existe);
			if($lb_existe)
			{
				$li_documentos=$io_sql->num_rows($rs_documentos);
				$documentos=$io_sql->obtener_datos($rs_documentos);
				for($li_i=1;$li_i<=$li_documentos;$li_i++)
				{
					$ls_dendoc= $documentos["dendoc"][$li_i];
					$ls_estdoc= $documentos["estdoc"][$li_i];
					switch($ls_estdoc)
					{
						case "0":
							$ls_estatus="No Entregado";
							break;
						case "1":
							$ls_estatus="Entregado";
							break;
						case "2":
							$ls_estatus="En Trámite";
							break;
						case "3":
							$ls_estatus="No Aplica";
							break;
					}
					$ld_fecrecdoc= $documentos["fecrecdoc"][$li_i];
					$ld_fecvendoc= $documentos["fecvendoc"][$li_i];
					$ld_fecrecdoc  = $io_funcion->uf_convertirfecmostrar($ld_fecrecdoc);
					$ld_fecvendoc     = $io_funcion->uf_convertirfecmostrar($ld_fecvendoc);
					$la_data_doc[$li_i]=array('denominacion'=>$ls_dendoc,'recepcion'=>$ld_fecrecdoc,'vencimiento'=>$ld_fecvendoc,'estatus'=>$ls_estatus);
				}
				uf_print_documentos($la_data_doc,&$io_pdf);
			}
			uf_print_fin(&$io_pdf);
			
			if ($io_pdf->ezPageCount==$li_numpag)
			{// Hacemos el commit de los registros que se desean imprimir
				$io_pdf->transaction('commit');
			}
			else
			{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
				$io_pdf->transaction('rewind');
				$io_pdf->ezNewPage(); // Insertar una nueva página
				uf_print_detalle($la_data,$ls_dirpro,$ls_denesp,$ls_obspro,$io_pdf); // Imprimimos el detalle 
				if($lb_existe)
				{
					uf_print_documentos($la_data_doc,&$io_pdf);
				}
				uf_print_fin(&$io_pdf);
			}
			unset($la_data);
			unset($la_data_doc);
		}//1
		//uf_print_cabecera_detalle($io_pdf);
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		unset($io_pdf);			
	}
	else
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	 }
?> 