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
	ini_set('memory_limit','24M');
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 05/02/2007
		// Fecha Modificación: 05/05/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(25,40,750,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->addText(571,580,8,"<b>REPÚBLICA BOLIVARIANA DE VENEZUELA</b>"); // Agregar la Fecha
		$io_pdf->addText(585,570,8,"<b>MINISTERIO DE EDUCACIÓN SUPERIOR</b>"); // Agregar la Fecha
		$io_pdf->addText(630,560,8,"<b>DIRECCIÓN DEL DESPACHO</b>"); // Agregar la Fecha
		$io_pdf->addText(568,550,8,"<b>OFICINA DE ADMINISTRACIÓN Y SERVICIOS</b>"); // Agregar la Fecha
		$io_pdf->addText(576,540,8,"<b>COORDINACIÓN DE BIENES NACIONALES</b>"); // Agregar la Fecha
		
		/*$li_tm=$io_pdf->getTextWidth(10,$as_titulo);
		$tm=306-($li_tm/2);*/
		$io_pdf->addText(200,515,11,$as_titulo); // Agregar el título
	
		$io_pdf->addText(500,740,12,"Pág."); // Agregar texto
		//$io_pdf->addText(500,710,9,date("d/m/Y")); // Agregar la Fecha
		//$io_pdf->addText(500,700,9,date("h:i a")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_detalle(&$io_pdf)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private
		//	    Arguments: io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera del detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 06/05/2008
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		
		$io_pdf->setStrokeColor(0,0,0);		
		$io_pdf->rectangle(17,282,748,40);
		$io_pdf->addText(25,310,7,"Nro DE"); // Agregar texto
		$io_pdf->addText(20,300,7,"INVENTARIO"); // Agregar texto
		$io_pdf->addText(24,290,7,"DEL BIEN"); // Agregar texto
		$io_pdf->line(69,282,69,322);   //linea vertical
		$io_pdf->addText(90,300,7,"DESCRIPCIÓN"); // Agregar texto
	    $io_pdf->line(172,282,172,322);   //linea vertical
		$io_pdf->addText(220,310,7,"INCORPORACIÓN"); // Agregar texto
		$io_pdf->line(172,305,765,305);  // linea horizontal
		$io_pdf->addText(178,290,7,"FECHA"); // Agregar texto
		$io_pdf->line(212,282,212,305);   //linea vertical
		$io_pdf->addText(220,290,7,"CÓDIGO"); // Agregar texto
		$io_pdf->line(262,282,262,305);   //linea vertical
		$io_pdf->addText(270,290,7,"CONCEPTO"); // Agregar texto
	    $io_pdf->line(314,282,314,322);   //linea vertical
		$io_pdf->addText(340,310,7,"DESINCORPORACIÓN"); // Agregar texto
		$io_pdf->line(320,305,765,305);  // linea horizontal
		$io_pdf->addText(320,290,7,"FECHA"); // Agregar texto
		$io_pdf->line(354,282,354,305);   //linea vertical
		$io_pdf->addText(360,290,7,"CÓDIGO"); // Agregar texto
		$io_pdf->line(394,282,394,305);   //linea vertical
		$io_pdf->addText(400,290,7,"CONCEPTO"); // Agregar texto
	    $io_pdf->line(449,282,449,322);   //linea vertical
		$io_pdf->addText(500,310,7,"REASIGNACIÓN"); // Agregar texto
		$io_pdf->addText(460,290,7,"FECHA"); // Agregar texto
		$io_pdf->line(494,282,494,305);   //linea vertical
		$io_pdf->addText(505,290,7,"CÓDIGO"); // Agregar texto
		$io_pdf->line(539,282,539,305);   //linea vertical
		$io_pdf->addText(550,290,7,"CONCEPTO"); // Agregar texto
	    $io_pdf->line(590,282,590,322);   //linea vertical
		$io_pdf->addText(600,310,7,"VALOR DEL"); // Agregar texto
		$io_pdf->addText(610,290,7,"BIEN"); // Agregar texto
        $io_pdf->line(650,282,650,322);   //linea vertical
		$io_pdf->addText(670,310,7,"ÚLTIMA VERIFICACIÓN"); // Agregar texto
		$io_pdf->addText(655,290,7,"FECHA"); // Agregar texto
		$io_pdf->line(680,282,680,305);   //linea vertical
		$io_pdf->addText(685,290,7,"CÓDIGO"); // Agregar texto
		$io_pdf->line(720,282,720,305);   //linea vertical
		$io_pdf->addText(723,290,7,"CONCEPTO"); // Agregar texto
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	}// end function uf_print_cabecera_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_cabecera($ls_codsigecof,$ls_desigecof,$ls_direcemp,$ls_ciuemp,$ls_zonpos,$ls_nomresuso,$ls_denuniadm,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_cabecera
		//		    Acess: private
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: Ing. Gloriely Féitez
		// Fecha Creación: 05/05/2008
		//////////////////////////////////////////////////////////////////////////////////////////
		//$io_pdf->ezSetDy(-10);
		$io_pdf->ezSetY(500);
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		
    	$io_pdf->setStrokeColor(0,0,0);		
		$io_pdf->rectangle(17,360,748,140);
		$io_pdf->addText(45,489,9,"<b>ORGANISMO</b>"); // Agregar texto
		//$io_pdf->addText(50,475,9,$as_codemp); // Agregar texto
		$io_pdf->addText(100,475,9,"MINISTERIO DE EDUCACIÓN SUPERIOR"); // Agregar texto
		$io_pdf->line(95,470,95,487);   //linea vertical
		$io_pdf->line(17,487,765,487);  // linea horizontal
		$io_pdf->line(17,470,765,470);  // linea horizontal
		$io_pdf->addText(45,460,9,"<b>CUENTA PATRIMONIAL</b>"); // Agregar texto
		//$io_pdf->addText(30,440,9,$ls_coduniadm); // Agregar texto
		//$io_pdf->addText(100,440,9,$ls_denuniadm); // Agregar texto
		$io_pdf->line(95,435,95,455);   // linea vertical
		$io_pdf->line(17,455,765,455);  //linea horizontal
		$io_pdf->line(17,435,765,435);  // linea horizontal
		$io_pdf->line(280,435,280,470);   // linea vertical
		$io_pdf->addText(320,460,9,"<b>SUBCUENTA PATRIMONIAL</b>"); // Agregar texto
    	$io_pdf->line(350,435,350,455);   // linea vertical
	    $io_pdf->line(510,435,510,470);   // linea vertical
		$io_pdf->addText(600,460,9,"<b>CATÁLOGO</b>"); // Agregar texto
		$io_pdf->addText(515,440,9,$ls_codsigecof); // Agregar texto
		$io_pdf->addText(585,440,9,$ls_desigecof); // Agregar texto
		$io_pdf->line(580,435,580,455);   // linea vertical
		$io_pdf->addText(45,425,9,"<b>UBICACIÓN GEOGRÁFICA</b>"); // Agregar texto
		$io_pdf->addText(25,410,9,"<b>Región</b>"); // Agregar texto
		//$io_pdf->line(17,420,765,420);  // linea horizontal
		$io_pdf->line(200,360,200,435);   //linea vertical
		$io_pdf->addText(210,410,9,"<b>Entidad Federal</b>"); // Agregar texto
		$io_pdf->line(400,360,400,435);   //linea vertical
		$io_pdf->addText(410,425,9,"<b>Dirección</b>"); // Agregar texto
		$io_pdf->addText(405,410,9,$ls_direcemp); // Agregar texto
		$io_pdf->addText(30,430,9,''); // Agregar texto
		$io_pdf->addText(100,430,9,''); // Agregar texto
		$io_pdf->line(17,395,765,395);  // linea horizontal
		$io_pdf->addText(25,380,9,"<b>Municipio</b>"); // Agregar texto
        $io_pdf->addText(210,380,9,"<b>Ciudad</b>"); // Agregar texto
		$io_pdf->addText(210,365,9,$ls_ciuemp); // Agregar texto
		$io_pdf->addText(410,380,9,"<b>Código Postal</b>"); // Agregar texto
		$io_pdf->addText(410,365,9,$ls_zonpos); // Agregar texto
	    $io_pdf->ezSetY(362);
		$la_data[1]=array('columna1'=>'<b>RESPONSABLE PATRIMONIAL PRIMARIO: MINISTERIO DE EDUCACIÓN SUPERIOR</b>',
		                 'columna2'=>'<b>UNIDAD ADMINISTRADORA: C.U. "PROF JOSÉ LORENZO PEREZ RODRIGUEZ"</b> ');
		$la_columna=array('columna1'=>'','columna2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>748, // Ancho de la tabla
						 'maxWidth'=>748, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>348), // Justificación y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>400))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[2]=array('columna1'=>'<b>RESPONSABLE PATRIMONIAL POR USO</b> '.$ls_nomresuso,
		                 'columna2'=>'<b>DEPENDENCIA USUARIA</b> '.$ls_denuniadm);
		$la_columna=array('columna1'=>'','columna2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>748, // Ancho de la tabla
						 'maxWidth'=>748, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>348), // Justificación y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>400))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
			
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_cabecera_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 11/04/2008
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(284);
		$la_columna=array('nrobien'=>'',
						  'descri'=>'',
						  'fechinc'=>'',
						  'codinc'=>'',
						  'concinco'=>'',
						  'fechdesi'=>'',
						  'coddesi'=>'',
						  'concedesi'=>'',
						  'fechreas'=>'',
						  'codreasig'=>'',
						  'concreasig'=>'',
						  'valor'=>'',
						  'fechult'=>'',
						  'codult'=>'',
						  'concult'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>752, // Ancho de la tabla
						 'maxWidth'=>752, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('nrobien'=>array('justification'=>'left','width'=>52), // Justificación y ancho de la columna
						 			   'descri'=>array('justification'=>'left','width'=>103), 
									   'fechinc'=>array('justification'=>'left','width'=>40),// Justificación y ancho de la columna
						 			   'codinc'=>array('justification'=>'left','width'=>50), // Justificación y ancho de la columna
						 			   'concinco'=>array('justification'=>'left','width'=>52),
									   'fechdesi'=>array('justification'=>'left','width'=>40),
									   'coddesi'=>array('justification'=>'left','width'=>40),
									   'concedesi'=>array('justification'=>'left','width'=>55),
									   'fechreas'=>array('justification'=>'left','width'=>45),
									   'codreasig'=>array('justification'=>'left','width'=>45),
									   'concreasig'=>array('justification'=>'left','width'=>51),
									   'valor'=>array('justification'=>'right','width'=>60),
									   'fechult'=>array('justification'=>'left','width'=>30),
									   'codult'=>array('justification'=>'left','width'=>40),
									   'concult'=>array('justification'=>'left','width'=>45))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	 
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por : Ing. Yesenia Moreno
		//  Modificado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 11/04/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		//$io_pdf->ezSetY(500);
    	$io_pdf->setStrokeColor(0,0,0);		
		$io_pdf->rectangle(17,70,748,80);
		$io_pdf->addText(20,135,10,"<b>Coordinación de Bienes Nacionales</b>"); 
		$io_pdf->addText(20,75,9,"<b>Sello y Firma</b>"); 
		$io_pdf->line(260,70,260,150);   //linea vertical
		$io_pdf->addText(265,135,10,"<b>Registrador de Bienes Nacionales</b>"); 
		$io_pdf->addText(265,75,9,"<b>Nombre y Apellidos                                                   C.I:</b>"); 
		$io_pdf->line(560,70,560,150);   //linea vertical
	    $io_pdf->addText(580,135,10,"<b>Trancrito Por:</b>"); 
	    $io_pdf->addText(580,125,10,"<b>Madeleine Villamizar/Eglis González</b>"); 
		$io_pdf->addText(565,75,9,"<b>Nombre y Apellidos</b>"); 
		$io_pdf->addText(700,85,9,"<b>CI: 13.219.201</b>"); 
		$io_pdf->addText(715,75,9,"<b>12.147.502</b>"); 
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');

	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../class_funciones_activos.php");
		$io_fun_activo=new class_funciones_activos("../../");
		$ls_tipoformato=$io_fun_activo->uf_obtenervalor_get("tipoformato",0);
		global $ls_tipoformato;
		if($ls_tipoformato==1)
		{
			require_once("sigesp_saf_class_reportbsf.php");
			$io_report=new sigesp_saf_class_reportbsf();
			$ls_titulo_report="Bs.F.";
		}
		else
		{
			require_once("sigesp_saf_class_report.php");
			$io_report=new sigesp_saf_class_report();
			$ls_titulo_report="Bs.";
		}	
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -------------------------------------
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_nomemp=$arre["nombre"];
	$ls_direcemp=$arre["direccion"];
	$ls_estemp=$arre["estemp"];
	$ls_ciuemp=$arre["ciuemp"];
	$ls_zonpos=$arre["zonpos"];
	//----------------------------------------------------  Parámetros del encabezado  --------------------------------------------
	$ls_titulo="<b>REGISTRO AUXILIAR DE BIENES MUEBLES POR RESPONSABLE Y POR UBICACIÓN</b>";   
	$ls_codsigecof=$io_fun_activo->uf_obtenervalor_get("codsigecof",""); 
	$ls_desigecof=$io_fun_activo->uf_obtenervalor_get("desigecof","");  
	$ls_orden=$io_fun_activo->uf_obtenervalor_get("orden","");
	$ld_desde=$io_fun_activo->uf_obtenervalor_get("desde",""); 
	$ls_hasta=$io_fun_activo->uf_obtenervalor_get("hasta",""); 
	$ls_codactdes=$io_fun_activo->uf_obtenervalor_get("codactdes",""); 
	$ls_denactdes=$io_fun_activo->uf_obtenervalor_get("denactdes","");   
	$ls_codhasta=$io_fun_activo->uf_obtenervalor_get("codhasta",""); 
	$ls_denhasta=$io_fun_activo->uf_obtenervalor_get("denhasta",""); 
	$ld_codrespri=$io_fun_activo->uf_obtenervalor_get("codrespri","");   
	$ld_nomrespri=$io_fun_activo->uf_obtenervalor_get("nomrespri",""); 
	$ls_codresuso=$io_fun_activo->uf_obtenervalor_get("codresuso",""); 
	$ls_nomresuso=$io_fun_activo->uf_obtenervalor_get("nomresuso","");  
	$ls_coduniadm=$io_fun_activo->uf_obtenervalor_get("coduniadm","");   
	$ls_denuniadm=$io_fun_activo->uf_obtenervalor_get("denuniadm","");     
	//--------------------------------------------------------------------------------------------------------------------------------
   
	$lb_valido=$io_report->uf_saf_buscar_registroaux($ls_codemp,$ls_codsigecof,$ls_orden,$ld_desde,$ls_hasta,$ls_codactdes,
	                                                 $ls_codhasta,$ls_codresuso); // Cargar el DS con los datos de la cabecera del reporte
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		//print(" close();");
		print("</script>");
	}
	 else // Imprimimos el reporte
	 {
		/////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////
		$ls_desc_event="Se Genero la autorización de salida ".$ls_cmpsal." ";
		$io_fun_activo->uf_load_seguridad_reporte("SAF","sigesp_saf_p_autorizacionsalida.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Times-Roman.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(11.6,4,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(750,50,10,'','',1); // Insertar el número de página
		$io_pdf->transaction('start'); // Iniciamos la transacción
		$li_numpag=$io_pdf->ezPageCount; // Número de página
	    uf_cabecera($ls_codsigecof,$ls_desigecof,$ls_direcemp,$ls_ciuemp,$ls_zonpos,$ls_nomresuso,$ls_denuniadm,&$io_pdf);
		uf_print_cabecera_detalle(&$io_pdf);
		uf_print_pie_cabecera($io_pdf);
		if($lb_valido)
		{
		 	$li_totrow_det=$io_report->ds_detalle->getRowCount("codact");
			for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
			{
				$ls_codart=$io_report->ds_detalle->data["codact"][$li_s];
				$ls_denart=$io_report->ds_detalle->data["denact"][$li_s];
				$ls_feccmp=$io_report->ds_detalle->data["feccmp"][$li_s];
				$ls_codcau=$io_report->ds_detalle->data["codcau"][$li_s];
				$ls_dencau=$io_report->ds_detalle->data["dencau"][$li_s];
				$ls_monact=$io_report->ds_detalle->data["monact"][$li_s];
				$ls_tipcau=$io_report->ds_detalle->data["tipcau"][$li_s];
				$ls_feccmp=$io_funciones->uf_convertirfecmostrar($ls_feccmp);
				$ls_icorpo=$ls_feccmp.' '.$ls_codcau.' '.$ls_dencau;
				$ls_monact=$io_fun_activo->uf_formatonumerico($ls_monact);
				if($ls_tipcau=='I')
				{
	        		$la_data[$li_s]=array('nrobien'=>$ls_codart,'descri'=>$ls_denart,'fechinc'=>$ls_feccmp,'codinc'=>$ls_codcau,'concinco'=>$ls_dencau,
				                      'fechdesi'=>'','coddesi'=>'','concedesi'=>'','fechreas'=>'','codreasig'=>'','concreasig'=>'',
									  'valor'=>$ls_monact,'fechult'=>'','codult'=>'','concult'=>'');
		        }
				else
				{
				  if($ls_tipcau=='D')
				   {
				     $la_data[$li_s]=array('nrobien'=>$ls_codart,'descri'=>$ls_denart,'fechinc'=>'','codinc'=>'','concinco'=>'',
				                      'fechdesi'=>$ls_feccmp,'coddesi'=>$ls_codcau,'concedesi'=>$ls_dencau,'fechreas'=>'','codreasig'=>'','concreasig'=>'',
									  'valor'=>$ls_monact,'fechult'=>'','codult'=>'','concult'=>'');
				   }
				   else
				   {
				    $la_data[$li_s]=array('nrobien'=>$ls_codart,'descri'=>$ls_denart,'fechinc'=>'','codinc'=>'','concinco'=>'',
				                      'fechdesi'=>'','coddesi'=>'','concedesi'=>'','fechreas'=>$ls_feccmp,'codreasig'=>$ls_codcau,'concreasig'=>$ls_dencau,
									  'valor'=>$ls_monact,'fechult'=>'','codult'=>'','concult'=>''); 
				   }
				}
		 	}
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		}
		
		$io_pdf->ezStopPageNumbers(1,1);
		if (isset($d) && $d)
		{
			$ls_pdfcode = $io_pdf->ezOutput(1);
		  	$ls_pdfcode = str_replace("\n","\n<br>",htmlspecialchars($ls_pdfcode));
		  	echo '<html><body>';
		  	echo trim($ls_pdfcode);
		  	echo '</body></html>';
		}
		else
		{
			$io_pdf->ezStream();
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_activo);
?> 