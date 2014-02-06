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
	
//--------------------------------------------------------------------------------------------------------------------------------//	
	 $li_tm=$io_pdf->getTextWidth(9,'<b>REPUBLICA BOLIVARIANA DE VENEZUELA</b>');
	 $tm=306-($li_tm/2);
	 $io_pdf->addText($tm,700,9,'<b>REPUBLICA BOLIVARIANA DE VENEZUELA</b>'); // Agregar el título
	 $li_tm=$io_pdf->getTextWidth(9,'<b>MINISTERIO DEL PODER POPULAR PARA LA DEFENSA</b>');
	 $tm=306-($li_tm/2);
     $io_pdf->addText($tm,685,9,'<b>MINISTERIO DEL PODER POPULAR PARA LA DEFENSA</b>'); // Agregar el título
	 $li_tm=$io_pdf->getTextWidth(9,'<b>ESTADO MAYOR DE LA DEFENSA</b>');
	 $tm=306-($li_tm/2);
     $io_pdf->addText($tm,670,9,'<b>ESTADO MAYOR DE LA DEFENSA</b>'); // Agregar el título
	 $li_tm=$io_pdf->getTextWidth(9,'<b>DIRECCION GENERAL DE CONTROL DE GESTION DE EMPRESAS Y SERVICIOS</b>');
	 $tm=306-($li_tm/2);
	 $io_pdf->addText($tm,655,9,'<b>DIRECCION GENERAL DE CONTROL DE GESTION DE EMPRESAS Y SERVICIOS</b>'); // Agregar el título
	 $li_tm=$io_pdf->getTextWidth(9,'<b>INSTITUTO DE PREVISION SOCIAL DE LA FUERZA ARMADA</b>');
	 $tm=306-($li_tm/2);
	 $io_pdf->addText($tm,640,9,'<b>INSTITUTO DE PREVISION SOCIAL DE LA FUERZA ARMADA</b>'); // Agregar el título
	
	
//---------------------------------------------------------------------------------------------------------------------------------//
	
	$li_tm=$io_pdf->getTextWidth(10,$as_titulo);
	 $tm=306-($li_tm/2);
	$io_pdf->addText($tm,615,10,$as_titulo); // Agregar el título
	// cuadro inferior
        $io_pdf->Rectangle(15,70,570,70);
		$io_pdf->line(15,83,585,83);		
		$io_pdf->line(15,127,585,127);		
		$io_pdf->line(210,70,210,140);		//vertical
		$io_pdf->line(390,70,390,140);		//vertical
		$io_pdf->addText(80,132,7,"ELABORADO POR"); // Agregar el título
		$io_pdf->addText(90,73,7,"ANALISTA"); // Agregar el título
		$io_pdf->addText(270,132,7,"AUTORIZADO POR"); // Agregar el título
		$io_pdf->addText(275,73,7,"JEFE DEL DPTO"); // Agregar el título
		$io_pdf->addText(450,132,7,"REPRESENTANTE LEGAL"); // Agregar el título
		$io_pdf->addText(470,73,7,"PROVEEDOR"); // Agregar el título
		
		
	    $io_pdf->restoreState();
	    $io_pdf->closeObject();
	    $io_pdf->addObject($io_encabezado,'all');
}// end function uf_print_encabezadopagina
//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_documentos($ls_titulo,$la_data,&$io_pdf)
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
	$la_datos[0]= array('titulo'=>'');
	$la_columna = array('titulo'=>'');
	$la_config  = array('showHeadings'=>0,     // Mostrar encabezados
					    'fontSize' => 9,       // Tamaño de Letras
					    'titleFontSize' => 5, // Tamaño de Letras de los títulos
					    'showLines'=>0,        // Mostrar Líneas
					    'shaded'=>0,           // Sombra entre líneas
					    'xOrientation'=>'left', // Orientación de la tabla
					    'width'=>700, // Ancho de la tabla
					    'maxWidth'=>700,
					    'cols'=>array('titulo'=>array('justification'=>'center','width'=>520))); // Justificación y ancho de la columna
	$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);	
	unset($la_datos);
	unset($la_columna);
	unset($la_config);
	$la_columna = array('denominacion'=>'<b>'.$ls_titulo.'</b>');
	$la_config  = array('showHeadings'=>1,     // Mostrar encabezados
					    'fontSize' => 9,       // Tamaño de Letras
					    'titleFontSize' => 5, // Tamaño de Letras de los títulos
					    'showLines'=>0,        // Mostrar Líneas
					    'shaded'=>0,           // Sombra entre líneas
					    'xOrientation'=>'center', // Orientación de la tabla
					    'width'=>700, // Ancho de la tabla
					    'maxWidth'=>700,
					    'cols'=>array('denominacion'=>array('justification'=>'left','width'=>520))); // Justificación y ancho de la columna Nro de Operacion.
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
}

// end function uf_print_documentos
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
	$ls_tiporeporte="0";
	if (array_key_exists("tiporeporte",$_GET))
	{
		$ls_tiporeporte=$_GET["tiporeporte"];
	}
	switch($ls_tiporeporte)
	{
		case "0":
			require_once("sigesp_rpc_class_report.php");
			$io_report  = new sigesp_rpc_class_report($con);
			$ls_bolivares ="Bs.";
			break;

		case "1":
			require_once("sigesp_rpc_class_reportbsf.php");
			$io_report  = new sigesp_rpc_class_reportbsf($con);
			$ls_bolivares ="Bs.F.";
			break;
	}
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>CONSTANCIA DE RECEPCION DE DOCUMENTOS ANTE EL REGISTRO AUXILIAR DE CONTRATISTAS DEL IPSFA</b>";
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
//--------------------------------------------------------------------------------------------------------------------------------//
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
		 $io_pdf->ezStartPageNumbers(580,50,8,'','',1); // Insertar el número de página
		 for ($z=1;$z<=$li_total;$z++)
			 {
			   $ls_codpro     = $data["cod_pro"][$z];
			   $ls_nompro     = $data["nompro"][$z];
			   $ls_dirpro     = $data["dirpro"][$z];
			   $ls_rifpro     = $data["rifpro"][$z];
			   $ls_telpro     = $data["telpro"][$z];
			   $ls_nomrep     = $data["nomreppro"][$z];
			   $ls_cedrep     = $data["cedrep"][$z];
			   $ld_capital    = $data["capital"][$z];
			   $ls_cuenta     = $data["sc_cuenta"][$z];
			   $ls_fecvenrnc  = $data["fecvenrnc"][$z];
			   $ls_fecvenrnc  = $io_funcion->uf_convertirfecmostrar($ls_fecvenrnc);
			   $ls_nro_reg    = $data["ocei_no_reg"][$z];
//---------------------------------------------------------------------------------------------------------------------------------			   
			 
	$io_pdf->addText(510,590,9,"Fecha: ".date("d/m/Y")); 
			
//---------------------------------------------------------------------------------------------------------------------------------
 
	$la_datos[1]=array('titulo'=>'<b>RIF:  </b>'.$ls_rifpro);
    $la_datos[2]=array('titulo'=>'<b>NOMBRE O RAZON SOCIAL:  </b>'.$ls_nompro);
    $la_datos[3]=array('titulo'=>'<b>CI DEL REPRESENTANTE LEGAL:  </b>'.$ls_cedrep);
    $la_datos[4]=array('titulo'=>'<b>REPRESENTANTE LEGAL:  </b>'.$ls_nomrep);
    $la_datos[5]=array('titulo'=>'<b>VIGENCIA DE LA CERTIFICACION DE INSCRIPCION ANTE RNC:  </b>'.$ls_fecvenrnc);
    $la_datos[6]=array('titulo'=>'<b>Nº DE CERTIFICADO:  </b>'.$ls_nro_reg);
					   
	$la_columna = array('titulo'=>'');
	$la_config  = array('showHeadings'=>0,     // Mostrar encabezados
					    'fontSize' => 9,       // Tamaño de Letras
					    'titleFontSize' => 5, // Tamaño de Letras de los títulos
					    'showLines'=>0,        // Mostrar Líneas
					    'shaded'=>0,           // Sombra entre líneas
					    'xOrientation'=>'center', // Orientación de la tabla
					    'width'=>520, // Ancho de la tabla
					    'maxWidth'=>520,
					    'cols'=>array('titulo'=>array('justification'=>'left','width'=>520))); // Justificación y ancho de la columna Nro de Operacion.
								  
	$io_pdf->ezSetY(590);
	$io_pdf->ezTable($la_datos,$la_columna,'',$la_config);	
			   
			   
//----------------------------------------------------------------------------------------------------------------------------------
			   
		   $rs_documentos = $io_report->uf_select_documentosproveedores($ls_codemp,$ls_codpro,$la_documentos,"0",$lb_existe);
			
			   if($lb_existe)
			   { 
			   		$li_documentos=$io_sql->num_rows($rs_documentos);
					$documentos=$io_sql->obtener_datos($rs_documentos);
					$li_legal=0;
					$li_esp=0;
					$li_financ=0;
					for($li_i=1;$li_i<=$li_documentos;$li_i++)
				    {
						$ls_dendoc= $documentos["dendoc"][$li_i];
						$ls_estdoc= $documentos["estdoc"][$li_i];
						$ls_tipdoc= $documentos["tipdoc"][$li_i];
						switch($ls_estdoc)
						{
							case "0":
								$ls_estatus="No Entregado";
								break;
							case "1":
								$ls_estatus="Entregado";
								switch($ls_tipdoc)
								{
									case "01": // documentos legales
										$li_legal++;
										$la_datalegales[$li_legal]=array('denominacion'=>$ls_dendoc);
										break;
								    case "02"; //documentos según especialidad	
									    $li_esp++;
										$la_dataespecialidades[$li_esp]=array('denominacion'=>$ls_dendoc);
										break;
								    case "03"; //documentos financieros
									    $li_financ++;
										$la_datafinanciera[$li_financ]=array('denominacion'=>$ls_dendoc);
										break;			
								}
								//$la_data[$li_i]=array('denominacion'=>$ls_dendoc);
								//uf_print_documentos($la_data,&$io_pdf);
								break;
							case "2":
								$ls_estatus="En Trámite";
								break;
							case "3":
								$ls_estatus="No Aplica";
								break;
						}
						//$ld_fecrecdoc= $documentos["fecrecdoc"][$li_i];
						//$ld_fecvendoc= $documentos["fecvendoc"][$li_i];
					    //$ld_fecrecdoc  = $io_funcion->uf_convertirfecmostrar($ld_fecrecdoc);
					    //$ld_fecvendoc     = $io_funcion->uf_convertirfecmostrar($ld_fecvendoc);
						
						
						//$la_data[$li_i]=array('denominacion'=>$ls_dendoc);
					}
					$io_pdf->ezSetY(500);
					if($li_legal>0)
					 {
						uf_print_documentos('DOCUMENTACION LEGAL',$la_datalegales,&$io_pdf);
					 }
				    if ($li_esp>0)
			         {
						uf_print_documentos('DOCUMENTACION SEGUN ESPECIALIDAD',$la_dataespecialidades,&$io_pdf);
					 }
			       if ($li_financ>0)
			         {
						uf_print_documentos('DOCUMENTACION FINANCIERA',$la_datafinanciera,&$io_pdf);
					 }
			
			
			 }
			   			  		   	   
		  
			  $io_pdf->addText(40,190,9,'<b>'."OBSERVACIONES:".'</b>');
			  
			 
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
