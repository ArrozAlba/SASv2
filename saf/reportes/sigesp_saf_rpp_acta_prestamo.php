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
		// Fecha Modificación: 11/04/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,695,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,680,11,$as_titulo); // Agregar el título
	
		$io_pdf->addText(500,740,12,"Pág."); // Agregar texto
		//$io_pdf->addText(500,710,9,date("d/m/Y")); // Agregar la Fecha
		//$io_pdf->addText(500,700,9,date("h:i a")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_cabecera($ls_denuniadmcede,$ls_denuniadmrece,$ls_estemp,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_cabecera
		//		    Acess: private
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: Ing. Yozelin Barragán
		// Fecha Creación: 05/02/2007
		//////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(670);
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$ls_fecha=date("d/m/Y");
	    $la_data=array(array('name'=>'Los sucritos ___________________________________________________, mayores de edad, hacemos'),
				       array('name'=>'constar por la presente acta que con fecha: '.$ls_fecha.', y de conformidad con las instrucciones recibidas'),
					   array('name'=>'de la Direccion de Administración del Ministerio del Poder Popular para la Educación y Deportes'),
					   array('name'=>'se ha traspasado en <b>CALIDAD DE PRESTAMO<b> del Inventario de la Unidad Cedente:'),
					   array('name'=>''.$ls_denuniadmcede.' que funciona en: ___________________, al Inventario de la Unidad '),
					   array('name'=>'Receptora: '.$ls_denuniadmrece.' que funciona en:_______________________,del Estado: '),
					   array('name'=>''.$ls_estemp.', los bienes que a continuación se especifican:'));
		
		$la_columnas=array('name'=>'','name'=>'','name'=>'','name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 12, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.5,0.5,0.5),// Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>306); // Justificación y ancho de la 
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
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
		$io_pdf->ezSetY(510);
		$la_columna=array('cantidad'=>'<b>Cantidad</b>',
						  'denact'=>'<b>Descripción</b>',
						  'costo'=>'<b>Valor Unitario</b>',
						  'total'=>'<b>Valor Total</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xPos'=>295,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cantidad'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'denact'=>array('justification'=>'left','width'=>300), // Justificación y ancho de la columna
						 			   'costo'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>60))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_detalle($ls_periodo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el pie del detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 05/02/2007
		// Fecha Modificación: 11/04/2008
		//////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(350);
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$ls_periodo=substr($ls_periodo,0,4);
	    $la_data=array(array('name'=>'Esta  acta es realizada por quintuplicado a un solo tenor y efecto para el traslado correspondiente,'),
				       array('name'=>'en la ciudad de _______________________________________a los _________________ días'),
					   array('name'=>'del mes ______________________ del '.$ls_periodo.''));
					   		
		$la_columnas=array('name'=>'','name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 12, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.5,0.5,0.5),//array($r,$g,$b), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>306); // Orientación de la tabla 
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_pie_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ls_nomced,$ls_apeced,$ls_cedced,$li_denasicar,$ls_nomrec,$ls_aperec,$ls_cedrec,
		                           $li_denasicar_r,$ls_nomtes,$ls_apetes,$ls_cedtes,$li_denasicar_t,$io_pdf)
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
        $io_pdf->addText(30,220,10,"JEFE O DIRECTOR DE LA UNIDAD");   
        $io_pdf->addText(30,205,10,"QUIEN ENTREGA");   
        $io_pdf->addText(30,170,10,"__________________________________");   
        $io_pdf->addText(30,155,10,"<b>Nombre:</b>");  
		$io_pdf->addText(72,155,8,$ls_nomced." ".$ls_apeced);  
        $io_pdf->addText(30,140,10,"<b>C:I. No.:</b>"); 
		$io_pdf->addText(72,140,8,$ls_cedced);   
        $io_pdf->addText(30,125,10,"<b>Cargo:</b>"); 
		$io_pdf->addText(69,125,8,$li_denasicar);   		

        $io_pdf->addText(350,220,10,"JEFE O DIRECTOR DE LA UNIDAD");
		$io_pdf->addText(350,205,10,"QUIEN RECIBE");  
        $io_pdf->addText(350,170,10,"__________________________________");   
        $io_pdf->addText(350,155,10,"<b>Nombre:</b>");  
		$io_pdf->addText(390,155,8,$ls_nomrec." ".$ls_aperec);    
        $io_pdf->addText(350,140,10,"<b>C:I. No.:</b>");
		$io_pdf->addText(390,140,8,$ls_cedrec);    
        $io_pdf->addText(350,125,10,"<b>Cargo:</b>");  
		$io_pdf->addText(387,125,8, $li_denasicar_r);    
		
		$io_pdf->addText(200,140,10,"TESTIGO");   
        $io_pdf->addText(200,100,10,"__________________________________");   
        $io_pdf->addText(200,85,10,"<b>Nombre:</b>"); 
		$io_pdf->addText(240,85,8,$ls_nomtes." ".$ls_apetes);     
        $io_pdf->addText(200,70,10,"<b>C:I. No.:</b>");   
		$io_pdf->addText(240,70,8,$ls_cedtes); 
        $io_pdf->addText(200,55,10,"<b>Cargo:</b>");  
		$io_pdf->addText(236,55,8,$li_denasicar_t); 
		
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
	$ls_estemp=$arre["estemp"];
	$ls_periodo=$arre["periodo"];
	$ls_cmpmov=$io_fun_activo->uf_obtenervalor_get("cmpmov","");
	//----------------------------------------------------  Parámetros del encabezado  --------------------------------------------
	$ls_titulo="<b>ACTA DE PRÉSTAMO</b>";   
	$ls_cmpres=$io_fun_activo->uf_obtenervalor_get("ls_cmpres","");
	$ls_coduniadmcede=$io_fun_activo->uf_obtenervalor_get("ls_coduniadmcede","");  
	$ls_coduniadmrece=$io_fun_activo->uf_obtenervalor_get("ls_coduniadmrece",""); 
	$ld_fecenacta=$io_fun_activo->uf_obtenervalor_get("ld_fecenacta",""); 
	$ls_codper=$io_fun_activo->uf_obtenervalor_get("ls_codper",""); 
	$ls_nomper=$io_fun_activo->uf_obtenervalor_get("ls_nomper",""); 
	$ls_denuniadmcede=$io_fun_activo->uf_obtenervalor_get("ls_denuniadmcede",""); 
	$ls_denuniadmrece=$io_fun_activo->uf_obtenervalor_get("ls_denuniadmrece",""); 
	$ls_codresced=$io_fun_activo->uf_obtenervalor_get("ls_codresced",""); 
	$ls_nomresced=$io_fun_activo->uf_obtenervalor_get("ls_nomresced",""); 
	$ls_codreserec=$io_fun_activo->uf_obtenervalor_get("ls_codreserec","");   
	$ls_nomresrec=$io_fun_activo->uf_obtenervalor_get("ls_nomresrec","");   
	//--------------------------------------------------------------------------------------------------------------------------------
    $ld_fecenacta=$io_funciones->uf_convertirdatetobd($ld_fecenacta);
	$lb_valido=$io_report->uf_saf_buscar_prestamo($ls_codemp,$ls_cmpres,$ls_coduniadmcede,$ls_coduniadmrece,$ld_fecenacta,
	                                     $ls_codresced,$ls_codreserec,$ls_codper); // Cargar el DS con los datos de la cabecera del reporte
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	 else // Imprimimos el reporte
	 {
		/////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////
		$ls_desc_event="Se Genero el Reporte Acta de préstamo ".$ls_cmpres." ";
		$io_fun_activo->uf_load_seguridad_reporte("SAF","sigesp_saf_r_acta_prestamo.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Times-Roman.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,6,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,740,10,'','',1); // Insertar el número de página
		$io_pdf->transaction('start'); // Iniciamos la transacción
		$li_numpag=$io_pdf->ezPageCount; // Número de página
	    uf_cabecera($ls_denuniadmcede,$ls_denuniadmrece,$ls_estemp,&$io_pdf);
		$ls_nomced="";
		$ls_apeced="";
		$ls_cedced="";
		$li_racnom="";
		$ls_nomtes="";
		$ls_apetes="";
		$ls_cedtes="";
		$li_racnom="";
		$ls_nomtes="";
		$ls_apetes="";
		$ls_cedtes="";
		$li_racnom="";
		$ls_nomrec="";
		$ls_aperec="";
		$ls_cedrec="";
		$li_racnom="";
		$li_denasicar="";
		$li_denasicar_r="";
		$li_denasicar_t="";
		if($lb_valido)
		{
		 	$li_totrow_det=$io_report->ds_detalle->getRowCount("codact");
			for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
			{
				$ls_codart=$io_report->ds_detalle->data["codact"][$li_s];
				$li_cantidad=$io_report->ds_detalle->data["cantidad"][$li_s];
				$ls_denart=$io_report->ds_detalle->data["denact"][$li_s];
				$li_costo=$io_report->ds_detalle->data["costo"][$li_s];
				$li_total=$io_report->ds_detalle->data["total"][$li_s];
				$li_costo=$io_fun_activo->uf_formatonumerico($li_costo);
				$li_total=$io_fun_activo->uf_formatonumerico($li_total);
	     		$la_data[$li_s]=array('cantidad'=>$li_cantidad,'denact'=>$ls_codart." ".$ls_denart,
									  'costo'=>$li_costo,'total'=>$li_total);
			}
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		}
		unset($la_data);			
		uf_print_pie_detalle($ls_periodo,$io_pdf); 
		$io_report->uf_saf_buscarcargos_reponcedente($ls_codemp,$ls_cmpres,$ls_codresced);
		$li_totrow=$io_report->ds_detalle->getRowCount("cedper");
		for($li_c=1;$li_c<=$li_totrow;$li_c++)
		{
		    $ls_nomced=$io_report->ds_detalle->data["nomper"][$li_c];
			$ls_apeced=$io_report->ds_detalle->data["apeper"][$li_c];
			$ls_cedced=$io_report->ds_detalle->data["cedper"][$li_c];
			$li_racnom=$io_report->ds_detalle->data["racnom"][$li_c];
			if ($li_racnom==0)
			{
			  $li_denasicar=$io_report->ds_detalle->data["descar"][$li_c];
			}
			else
			{
			  $li_denasicar=$io_report->ds_detalle->data["denasicar"][$li_c];
			}
			 
		}
		$io_report->uf_saf_buscarcargos_reponreceptor($ls_codemp,$ls_cmpres,$ls_codreserec);
		$li_totrow_r=$io_report->ds_detalle->getRowCount("cedper");
		for($li_r=1;$li_r<=$li_totrow_r;$li_r++)
		{
		    $ls_nomrec=$io_report->ds_detalle->data["nomper"][$li_r];
			$ls_aperec=$io_report->ds_detalle->data["apeper"][$li_r];
			$ls_cedrec=$io_report->ds_detalle->data["cedper"][$li_r];
			$li_racnom=$io_report->ds_detalle->data["racnom"][$li_r];
			if ($li_racnom==0)
			{
			  $li_denasicar_r=$io_report->ds_detalle->data["descar"][$li_r];
			}
			else
			{
			  $li_denasicar_r=$io_report->ds_detalle->data["denasicar"][$li_r];
			}
			 
		}
		$io_report->uf_saf_buscarcargos_repontestigo($ls_codemp,$ls_cmpres,$ls_codper);
		$li_totrow_t=$io_report->ds_detalle->getRowCount("cedper");
		for($li_t=1;$li_t<=$li_totrow_t;$li_t++)
		{
		    $ls_nomtes=$io_report->ds_detalle->data["nomper"][$li_t];
			$ls_apetes=$io_report->ds_detalle->data["apeper"][$li_t];
			$ls_cedtes=$io_report->ds_detalle->data["cedper"][$li_t];
			$li_racnom=$io_report->ds_detalle->data["racnom"][$li_t];
			if ($li_racnom==0)
			{
			  $li_denasicar_t=$io_report->ds_detalle->data["descar"][$li_t];
			}
			else
			{
			  $li_denasicar_t=$io_report->ds_detalle->data["denasicar"][$li_t];
			}
			 
		}
		uf_print_pie_cabecera($ls_nomced,$ls_apeced,$ls_cedced,$li_denasicar,$ls_nomrec,$ls_aperec,$ls_cedrec,
		                      $li_denasicar_r,$ls_nomtes,$ls_apetes,$ls_cedtes,$li_denasicar_t,$io_pdf);
		unset($la_data);
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