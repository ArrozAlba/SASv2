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
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->line(20,40,770,40);
		$io_pdf->rectangle(140,500,630,40);
		$io_pdf->line(600,540,600,500);
		$io_pdf->line(600,520,770,520);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],35,500,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(15,$as_titulo);
		$io_pdf->addText(280,515,15,$as_titulo); // Agregar el título
		$io_pdf->addText(620,525,10,"<b>Fecha:</b> ".date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(620,505,10,"<b>Hora:</b> ".date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
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
		$io_pdf->ezSetY(480);
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
	
		$la_datatit = array(array('codigo'=>'<b>Código</b>','nombre'=>'<b>Nombre</b>','rif'=>'<b>RIF</b>','nit'=>'<b>NIT</b>','telefono'=>'<b>Teléfono</b>','scgcta'=>'<b>Contable</b>'));
		$la_columna = array('codigo'=>'','nombre'=>'','rif'=>'','nit'=>'','telefono'=>'','scgcta'=>'');
		$la_config  = array('showHeadings'=>0, // Mostrar encabezados
							'titleFontSize' =>10,  // Tamaño de Letras de los títulos
							'showLines'=>1, // Mostrar Líneas
							'shaded'=>0,
							'shadeCol2'=>array(0.86,0.86,0.86),
							'width'=>520, // Ancho de la tabla
							'maxWidth'=>520, // Ancho Máximo de la tabla
							'xPos'=>405, // Orientación de la tabla
							'cols'=>array('codigo'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
										  'nombre'=>array('justification'=>'left','width'=>250), // Justificación y ancho de la columna
										  'rif'=>array('justification'=>'center','width'=>85), // Justificación y ancho de la columna
										  'nit'=>array('justification'=>'center','width'=>85), // Justificación y ancho de la columna
										  'telefono'=>array('justification'=>'center','width'=>145),
										  'scgcta'=>array('justification'=>'center','width'=>100)));								   
		$io_pdf->ezTable($la_datatit,$la_columna,'',$la_config);	
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	
		$la_columna=array('codigo'=>'','nombre'=>'','rif'=>'','nit'=>'','telefono'=>'','scgcta'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>9, // Tamaño de Letras
						 'titleFontSize'=>9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.8,0.8,0.8),
						 'shadeCol2'=>array(0.9,0.9,0.9),
						 'width'=>735, // Ancho de la tabla
						 'maxWidth'=>735, // Ancho Máximo de la tabla
						 'xPos'=>405, // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'nombre'=>array('justification'=>'left','width'=>250), // Justificación y ancho de la columna
									   'rif'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la columna
									   'nit'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la columna
									   'telefono'=>array('justification'=>'right','width'=>145),
									   'scgcta'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_detalle
	
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	
	require_once("sigesp_rpc_class_report.php");
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	$io_in     = new sigesp_include();
	$con       = $io_in->uf_conectar();	
	$io_report = new sigesp_rpc_class_report($con);
	$io_sql    = new class_sql($con);
	
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
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	if($ls_tipo=="C")
	{$ls_titulo="<b>Listado de Contratistas</b>";}
	else
	{$ls_titulo="<b>Listado de Proveedores</b>";}
	//---------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=true;
	$rs_proveedor=$io_report->uf_load_proveedores($ls_codemp,$li_orden,$ls_tipo,$ls_codprov1,$ls_codprov2,$ls_codesp,$lb_valido);
	if ($lb_valido)
    {
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(5.2,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(740,25,10,'','',1); // Insertar el número de página
		$li_total = $io_sql->num_rows($rs_proveedor);
		if ($li_total>0)
		   {
		     $z = 0;
			 while($row=$io_sql->fetch_row($rs_proveedor))
			      {
				    $z++;
					$ls_codpro   = $row["cod_pro"];
					$ls_nompro   = $row["nompro"];
					$ls_rifpro   = $row["rifpro"];
					$ls_nitpro   = $row["nitpro"];
					$ls_telpro   = $row["telpro"];
					$ls_scgcta   = $row["sc_cuenta"];
					$la_data[$z] = array('codigo'=>$ls_codpro,'nombre'=>$ls_nompro,'rif'=>$ls_rifpro,'nit'=>$ls_nitpro,'telefono'=>$ls_telpro,'scgcta'=>$ls_scgcta);
				  }
		   }
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		if ($lb_valido) // Si no ocurrio ningún error
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