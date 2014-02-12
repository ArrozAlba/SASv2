<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//  ESTE FORMATO SE IMPRIME EN Bs Y EN BsF. SEGUN LO SELECCIONADO POR EL USUARIO
	//  MODIFICADO POR: ING.YOZELIN BARRAGAN         FECHA DE MODIFICACION : 03/09/2007
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
		//$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],38,730,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->addJpegFromFile('../../shared/imagebank/LOGO ZONA EDUCATIVA LARA.jpg',25,705,220,50); // Agregar Logo
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
	function uf_cabecera(&$io_pdf)
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
	    $la_data=array(array('name'=>'   Los sucritos ___________________________________________________, mayores de edad, hacemos'),
				       array('name'=>'   constar por la presente acta que con fecha:______________________________, y de conformidad con las'),
					   array('name'=>'   instrucciones recibidas de la Direccion de Administración del Ministerio del Poder Popular para la Educación'),
					   array('name'=>'   se han incorporado al inventario de la Unidad _________________________________ que funciona en'),
					   array('name'=>'   ______________________, Municipio _______________________ del Estado _______________________,'),
					   array('name'=>'   los bienes que a continuación se especifican, los cuales fueron adquiridos por__________________________.'));
		
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
		$io_pdf->ezSetY(550);
	    global $ls_tipoformato;
		if($ls_tipoformato==0)
		{
		  $ls_titulo=" Bs.";
		}
		elseif($ls_tipoformato==1)
		{
		  $ls_titulo=" Bs.F.";
		}
		$la_columna=array('cantidad'=>'<b>Cantidad</b>',
						  'catalogo'=>'<b>Código del Catálogo</b>',
						  'codact'=>'<b>Numero de Inventario</b>',
						  'denact'=>'<b>Descripción</b>',
						  'codcau'=>'<b>Incorporación</b>',
						  'costo'=>'<b>Valor Unitario</b>',
						  'total'=>'<b>Valor Total</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cantidad'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna
						 			   'catalogo'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'codact'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'denact'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la columna
						 			   'codcau'=>array('justification'=>'left','width'=>100), // Justificación y ancho de la columna
						 			   'costo'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>55))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_detalle(&$io_pdf)
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
	    $la_data=array(array('name'=>'   Esta  acta es realizada por quintuplicado a un solo tenor y efecto para los fines de la incorporación '),
				       array('name'=>'   correspondiente, en la ciudad de _______________________________ a los _________________________. '));
					   		
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
	function uf_print_pie_cabecera(&$io_pdf)
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
        $io_pdf->addText(30,205,10,"QUIEN RECIBE");   
        $io_pdf->addText(30,170,10,"__________________________________");   
        $io_pdf->addText(30,155,10,"<b>Nombre:</b>");   
        $io_pdf->addText(30,140,10,"<b>C:I. No.:</b>");   
        $io_pdf->addText(30,125,10,"<b>Cargo:</b>");   		

        $io_pdf->addText(350,220,10,"TESTIGO");   
        $io_pdf->addText(350,170,10,"__________________________________");   
        $io_pdf->addText(350,155,10,"<b>Nombre:</b>");   
        $io_pdf->addText(350,140,10,"<b>C:I. No.:</b>");   
        $io_pdf->addText(350,125,10,"<b>Cargo:</b>");   
		
		$io_pdf->addText(200,140,10,"TESTIGO");   
        $io_pdf->addText(200,100,10,"__________________________________");   
        $io_pdf->addText(200,85,10,"<b>Nombre:</b>");   
        $io_pdf->addText(200,70,10,"<b>C:I. No.:</b>");   
        $io_pdf->addText(200,55,10,"<b>Cargo:</b>");  
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');

	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../class_funciones_activos.php");
		$io_fun_activo=new class_funciones_activos();
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
	$ls_cmpmov=$io_fun_activo->uf_obtenervalor_get("cmpmov","");
	//----------------------------------------------------  Parámetros del encabezado  --------------------------------------------
	$ls_titulo="<b>ACTA DE INCORPORACIÓN</b>";   
	$ls_coduniadm=$io_fun_activo->uf_obtenervalor_get("coduniadm","");
	$ls_codres=$io_fun_activo->uf_obtenervalor_get("codres","");    
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_saf_load_dt_compmovimiento($ls_codemp,$ls_cmpmov,$ls_codres); // Cargar el DS con los datos de la cabecera del reporte
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
		$ls_desc_event="Se Genero el Reporte Acta de Recepcion de Bienes con orden de compra ".$ls_numordcom." ";
		$io_fun_activo->uf_load_seguridad_reporte("SIV","sigesp_siv_r_acta_recepcion_bienes.php",$ls_desc_event);
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
		/*$ls_cmpmov=$io_report->ds_detalle->data["cmpmov"][1];
		$ls_codcau=$io_report->ds_detalle->data["codcau"][1];
		$ls_dencau=$io_report->ds_detalle->data["dencau"][1];*/
	    uf_cabecera($io_pdf);
		if($lb_valido)
		{
			$li_aux=0;
			$li_totrow_det=$io_report->ds_detalle->getRowCount("codact");
			for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
			{
				$ls_auxcoduniadm= $io_report->ds_detalle->data["coduniadm"][$li_s];
				$ls_codart=       $io_report->ds_detalle->data["codact"][$li_s];
				$ls_denart=       $io_report->ds_detalle->data["denact"][$li_s];
				$ls_catalogo=     $io_report->ds_detalle->data["catalogo"][$li_s];
				$li_ideact=       $io_report->ds_detalle->data["ideact"][$li_s];
				$ls_codcau=       $io_report->ds_detalle->data["codcau"][$li_s];
				$ls_dencau=       $io_report->ds_detalle->data["dencau"][$li_s];
				$li_costo=        $io_report->ds_detalle->data["costo"][$li_s];
				$li_cantidad=     $io_report->ds_detalle->data["cantidad"][$li_s];
				$li_total=($li_costo * $li_cantidad);
				$li_cantidad=$io_fun_activo->uf_formatonumerico($li_cantidad);
				$li_costo=$io_fun_activo->uf_formatonumerico($li_costo);
				$li_total=$io_fun_activo->uf_formatonumerico($li_total);
			   if($ls_auxcoduniadm==$ls_coduniadm)
				{
					$li_aux=$li_aux + 1;
					$la_data[$li_aux]=array('cantidad'=>$li_cantidad,'catalogo'=>$ls_catalogo,'codact'=>$ls_codart,'denact'=>$ls_denart,
										  'codcau'=>$ls_codcau." ".$ls_dencau,'costo'=>$li_costo,'total'=>$li_total);
				}
			}
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		}
		unset($la_data);			
		unset($la_datat);			
		uf_print_pie_detalle($io_pdf); 
		uf_print_pie_cabecera($io_pdf);
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