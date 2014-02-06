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
	$li_tm=$io_pdf->getTextWidth(14,$as_titulo);
	$io_pdf->addText(230,680,14,$as_titulo); // Agregar el título
	//$io_pdf->addText(470,735,10,"Fecha: ".date("d/m/Y")); // Agregar la Fecha
	//$io_pdf->addText(476,715,10,"Hora: ".date("h:i a")); // Agregar la hora
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');
}// end function uf_print_encabezadopagina
//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_registronacional($ls_fecreg,$ls_numreg,$ls_fecvenrnc,&$io_pdf)
{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Function: uf_print_registronacional
//		   Access: private 
//	    Arguments: as_codper // total de registros que va a tener el reporte
//	    		   as_nomper // total de registros que va a tener el reporte
//	    		   io_pdf    // total de registros que va a tener el reporte
//    Description: función que imprime la cabecera de cada página
//	   Creado Por: Ing. Yesenia Moreno
// Fecha Creación: 21/04/2006 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$la_datos[0]= array('titulo'=>'<b>REGISTRO NACIONAL DE CONTRATISTA</b>');
	$la_columna = array('titulo'=>'');
	$la_config  = array('showHeadings'=>0,     // Mostrar encabezados
					    'fontSize' => 10,       // Tamaño de Letras
					    'titleFontSize' => 5, // Tamaño de Letras de los títulos
					    'showLines'=>2,        // Mostrar Líneas
					    'shaded'=>0,           // Sombra entre líneas
					    'xOrientation'=>'center', // Orientación de la tabla
					    'width'=>520, // Ancho de la tabla
					    'maxWidth'=>520,
						'xPos'=>300,
					    'cols'=>array('titulo'=>array('justification'=>'center','width'=>520))); // Justificación y ancho de la columna
	$io_pdf->ezSetY(473);
	$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);	
	unset($la_datos);
	unset($la_columna);
	unset($la_config);
	//$ls_inscripcion=str_pad('<b>INSCRIPCION:</b>'.$ls_fecreg
	$la_datos[1]=array('inscripcion'=>'<b>INSCRIPCION:  </b>'.$ls_fecreg,
	                   'registro'=>'<b>N° DE REGISTRO:  </b>'.$ls_numreg,
					   'vencimiento'=>'<b>VENCIMIENTO:  </b>'.$ls_fecvenrnc);
	$la_columna = array('inscripcion'=>'','registro'=>'','vencimiento'=>'');
	$la_config  = array('showHeadings'=>0,     // Mostrar encabezados
					    'fontSize' => 10,       // Tamaño de Letras
					    'titleFontSize' => 5, // Tamaño de Letras de los títulos
					    'showLines'=>1,        // Mostrar Líneas
					    'shaded'=>0,           // Sombra entre líneas
					    'xOrientation'=>'center', // Orientación de la tabla
					    'width'=>520, // Ancho de la tabla
					    'maxWidth'=>520,
						'xPos'=>300,
					    'cols'=>array('inscripcion'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la columna Nro de Operacion.
								      'registro'=>array('justification'=>'left','width'=>220), // Justificación y ancho de la columna
								      'vencimiento'=>array('justification'=>'left','width'=>150))); // Justificación y ancho de la columna
	$io_pdf->ezSetY(457.5);
	$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);	
}// end function uf_print_registronacional
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
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$la_datos[0]= array('titulo'=>'<b>DOCUMENTOS CONSIGNADOS</b>');
	$la_columna = array('titulo'=>'');
	$la_config  = array('showHeadings'=>0,     // Mostrar encabezados
					    'fontSize' => 10,       // Tamaño de Letras
					    'titleFontSize' => 5, // Tamaño de Letras de los títulos
					    'showLines'=>2,        // Mostrar Líneas
					    'shaded'=>0,           // Sombra entre líneas
					    'xOrientation'=>'center', // Orientación de la tabla
					    'width'=>520, // Ancho de la tabla
					    'maxWidth'=>520,
						'xPos'=>300,
					    'cols'=>array('titulo'=>array('justification'=>'center','width'=>520))); // Justificación y ancho de la columna
	$io_pdf->ezSetY(437);
	$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);	
	unset($la_datos);
	unset($la_columna);
	unset($la_config);
	$la_columna = array('denominacion'=>'                                    Denominación','recepcion'=>'Fecha Recepción','vencimiento'=>'Fecha Vencimiento','estatus'=>'Estatus');
	$la_config  = array('showHeadings'=>1,     // Mostrar encabezados
					    'fontSize' => 10,       // Tamaño de Letras
					    'titleFontSize' => 5, // Tamaño de Letras de los títulos
					    'showLines'=>2,        // Mostrar Líneas
					    'shaded'=>0,           // Sombra entre líneas
					    'xOrientation'=>'center', // Orientación de la tabla
					    'width'=>520, // Ancho de la tabla
					    'maxWidth'=>520,
						'xPos'=>300,
					    'cols'=>array('denominacion'=>array('justification'=>'left','width'=>270), // Justificación y ancho de la columna Nro de Operacion.
								      'recepcion'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
								      'vencimiento'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
								      'estatus'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
	$io_pdf->ezSetY(419);
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
}// end function uf_print_documentos
//--------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/sigesp_include.php");
    require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_in      = new sigesp_include();
	$con        = $io_in->uf_conectar();	
	$io_sql     = new class_sql($con);
	$io_funcion = new class_funciones();
	if (array_key_exists("tiporeporte",$_GET))
	{
		$ls_tiporeporte=$_GET["tiporeporte"];
	}
	switch($ls_tiporeporte)
	{
		case "0":
			require_once("sigesp_rpc_class_report.php");
			$io_report  = new sigesp_rpc_class_report($con);
			break;

		case "1":
			require_once("sigesp_rpc_class_reportbsf.php");
			$io_report  = new sigesp_rpc_class_reportbsf($con);
			break;
	}
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
		$ls_titulo = "<c:uline><b>FICHA DE PROVEEDOR</c:uline></b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	if (array_key_exists("hidorden",$_POST))
	   {
		 $li_orden=$_POST["hidorden"];
	   }
	else
	   {
		 $li_orden=$_GET["hidorden"];
	   }
	if (array_key_exists("hidcodproben1",$_POST))
	   {
		 $ls_codproben1 = $_POST["hidcodproben1"];
	   }
	else
	   {
		 $ls_codproben1=$_GET["hidcodproben1"];
	   }
	if (array_key_exists("hidcodproben2",$_POST))
	   {
		 $ls_codproben2 = $_POST["hidcodproben2"];
	   }
	else
	   {
		 $ls_codproben2 = $_GET["hidcodproben2"];
	   }
	if (array_key_exists("total",$_POST))
	   {
		 $li_total = $_POST["total"];
	   }
	else
	   {
		 $li_total = $_GET["total"];
	   }
	$la_documentos[0]="";
	if($li_total>0)
	{
		for($li_i=1;$li_i<=$li_total;$li_i++)
		{
			$la_documentos[$li_i]=$_GET["coddoc".$li_i];
		}
	}
    $lb_valido  = true;
	$la_empresa = $_SESSION["la_empresa"];
	$ls_codemp  = $la_empresa["codemp"];
    $rs_data    = $io_report->uf_select_proveedores($ls_codemp,$li_orden,$ls_codproben1,$ls_codproben2,$lb_valido);
	if ($lb_valido)
	   {
		 error_reporting(E_ALL);
		 set_time_limit(1800);
		 $li_total=$io_sql->num_rows($rs_data);
		 $data=$io_sql->obtener_datos($rs_data);
		 $io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		 $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		 $io_pdf->ezSetCmMargins(3.8,3,3,3); // Configuración de los margenes en centímetros
		 uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		 $io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		 for ($z=1;$z<=$li_total;$z++)
			 {
			   $ls_codpro     = $data["cod_pro"][$z];
			   $ls_nompro     = $data["nompro"][$z];
			   $ls_dirpro     = $data["dirpro"][$z];
			   $ls_rifpro     = $data["rifpro"][$z];
			   $ls_telpro     = $data["telpro"][$z];
			   $ls_faxpro     = $data["faxpro"][$z];
			   $ls_nomrep     = $data["nomreppro"][$z];
			   $ls_carrep     = $data["carrep"][$z];
			   $ld_capital    = $data["capital"][$z];
			   $ls_cuenta     = $data["sc_cuenta"][$z];
  			   $ls_fecreg     = $data["fecreg"][$z];
			   $ls_fecreg     = $io_funcion->uf_convertirfecmostrar($ls_fecreg);
			   $ls_codesp     = $data["codesp"][$z];
			   $ls_nitpro     = $data["nitpro"][$z];
			   $ls_codpai     = $data["codpai"][$z];
			   $ls_estpro     = $data["estado"][$z];
			   $ls_numreg     = $data["ocei_no_reg"][$z];
			   $ls_fecvenrnc  = $data["fecvenrnc"][$z];
			   $ls_fecreg     = $data["ocei_fec_reg"][$z];
			   $ls_nompai     = $data["pais"][$z];
			   $ls_denesp     = $io_report->uf_load_especialidadproveedor($ls_codpro,&$lb_valido) ;
			   $ls_fecvenrnc  = $io_funcion->uf_convertirfecmostrar($ls_fecvenrnc);
			   $ls_fecreg     = $io_funcion->uf_convertirfecmostrar($ls_fecreg);
			   $io_pdf->rectangle(35,618,520,35);
			   $io_pdf->addText(43,640,11,'<b>CODIGO :</b>');
			   $io_pdf->addText(125,640,11,$ls_codpro); 
			   $io_pdf->addText(43,625,10,'<b>PROVEEDOR :</b>');
			   $io_pdf->addText(125,625,10,$ls_nompro); 
		       $io_pdf->line(120,618,120,652);
			   $io_pdf->rectangle(35,475,520,140);
			   $io_pdf->addText(43,600,10,'<b>DIRECCION :</b>');
			   $ls_dirpro = $io_pdf->addTextWrap(130,600,400,10,$ls_dirpro);
			   $io_pdf->addText(130,590,10,$ls_dirpro);
			   $io_pdf->addText(43,570,10,'<b>ESTADO :</b>');
			   $io_pdf->addText(130,570,10,$ls_estpro);
			   $io_pdf->addText(43,550,10,'<b>TELEFONO :</b>');
			   $io_pdf->addText(130,550,10,$ls_telpro);
			   $io_pdf->addText(43,530,10,'<b>ESPECIALIDAD :</b>');
			   $io_pdf->addText(130,530,10,$ls_denesp);
			   $io_pdf->addText(43,510,10,'<b>CONTACTO :</b>');
			   $io_pdf->addText(130,510,10,$ls_nomrep); 
			   $io_pdf->addText(43,490,10,'<b>RIF :</b>');
			   $io_pdf->addText(130,490,10,$ls_rifpro);
			   $io_pdf->addText(350,570,10,'<b>PAIS :</b>');
			   $io_pdf->addText(400,570,10,$ls_nompai);
			   $io_pdf->addText(350,550,10,'<b>FAX :</b>');
			   $io_pdf->addText(400,550,10,$ls_faxpro);
			   $io_pdf->addText(350,510,10,'<b>CARGO :</b>');
			   $io_pdf->addText(400,510,10,$ls_carrep);
			   $io_pdf->addText(350,490,10,'<b>NIT :</b>');
			   $io_pdf->addText(400,490,10,$ls_nitpro);
			   uf_print_registronacional($ls_fecreg,$ls_numreg,$ls_fecvenrnc,&$io_pdf);
			   $rs_documentos = $io_report->uf_select_documentosproveedores($ls_codemp,$ls_codpro,$la_documentos,"0",$lb_existe);
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
						$la_data[$li_i]=array('denominacion'=>$ls_dendoc,'recepcion'=>$ld_fecrecdoc,'vencimiento'=>$ld_fecvendoc,'estatus'=>$ls_estatus);
					}
					uf_print_documentos($la_data,&$io_pdf);
			   }
			   if ($z<$li_total)
			      {$io_pdf->ezNewPage();}
 		    }
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
	}//1
	else
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	 }
?> 
