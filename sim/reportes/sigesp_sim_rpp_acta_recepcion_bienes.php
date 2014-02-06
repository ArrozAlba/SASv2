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
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_periodo_comp // Descripci�n del periodo del comprobante
		//	    		   as_fecha_comp // Descripci�n del per�odo de la fecha del comprobante
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: Ing. Yozelin Barrag�n
		// Fecha Creaci�n: 05/02/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],38,730,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		//$io_pdf->addJpegFromFile('../../shared/imagebank/logo2.jpg',250,730,80,30); // Agregar Logo
		//$io_pdf->addJpegFromFile('../../shared/imagebank/logo3.jpg',38,730,80,30); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,710,11,$as_titulo); // Agregar el t�tulo

		$io_pdf->line(210,710,400,710);
		$io_pdf->addText(500,740,12,"P�g."); // Agregar texto
		//$io_pdf->addText(500,710,9,date("d/m/Y")); // Agregar la Fecha
		//$io_pdf->addText(500,700,9,date("h:i a")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_cabezera($as_nomresalm,$as_nompro,$as_nomreppro,$as_nombre,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_cabezera
		//		    Acess: private
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: Ing. Yozelin Barrag�n
		// Fecha Creaci�n: 05/02/2007
		//////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(700);
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
	    $la_data=array(array('name'=>'Entre el <b>'.trim($as_nombre).'</b> representado en este acto por el ciudadano ______________'),
				       array('name'=>'por una parte, y por la otra <b>" LA EMPRESA " '.$as_nompro.'</b>'),
					   array('name'=>'representada en este acto por el ciudadano  <b>'.$as_nomreppro.'</b> ,'),
					   array('name'=>'se ha procedido a efectuar la recepcion de los siguientes bienes  </b>'));

		$la_columnas=array('name'=>'','name'=>'','name'=>'','name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'fontSize' => 12, // Tama�o de Letras
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol'=>array(0.5,0.5,0.5),// Color de la sombra
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho M�ximo de la tabla
						 'xPos'=>306); // Justificaci�n y ancho de la
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_cabezera_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_cabezera_detalle(&$io_pdf)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_cabezera_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: Ing. Yozelin Barrag�n
		// Fecha Creaci�n: 05/02/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetY(616);
		$la_data=array(array('codart'=>'<b>Codigo</b>','denart'=>'<b>Denominaci�n</b>','canart'=>'<b>Cantidad</b>',
		                     'preuniart'=>'<b>Precio Unitario</b>'));
		$la_columnas=array('codart'=>'','denart'=>'','canart'=>'','preuniart'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar L�neas
						 'fontSize' => 12, // Tama�o de Letras
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol'=>array(0.5,0.5,0.5),//array($r,$g,$b), // Color de la sombra
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho M�ximo de la tabla
						 'xPos'=>306, // Orientaci�n de la tabla
						 'cols'=>array('codart'=>array('justification'=>'center','width'=>130), // Justificaci�n y ancho de la
						 			   'denart'=>array('justification'=>'center','width'=>260), // Justificaci�n y
						 			   'canart'=>array('justification'=>'center','width'=>60), // Justificaci�n y
									   'preuniart'=>array('justification'=>'center','width'=>100))); // Justificaci�n y ancho de la
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_cabezera_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: Ing. Yozelin Barrag�n
		// Fecha Creaci�n: 05/02/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(600);
		$io_pdf->ezSetCmMargins(6.8,14,3,3); // Configuraci�n de los margenes en cent�metros
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 12, // Tama�o de Letras
						 'showLines'=>2, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho M�ximo de la tabla
						 'xPos'=>306, // Orientaci�n de la tabla
						 'cols'=>array('codart'=>array('justification'=>'center','width'=>130), // Justificaci�n y ancho de la
						 			   'denart'=>array('justification'=>'left','width'=>260), // Justificaci�n y
						 			   'canart'=>array('justification'=>'right','width'=>60), // Justificaci�n y
									   'preuniart'=>array('justification'=>'right','width'=>100))); // Justificaci�n y ancho de la
		$la_columnas=array('codart'=>'<b>Codigo</b>',
						   'denart'=>'<b>Denominaci�n</b>',
						   'canart'=>'<b>Cantidad</b>',
						   'preuniart'=>'<b>Precio Unitario</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuraci�n de los margenes en cent�metros
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_detalle($as_estpro,$as_numordcom,$as_nombre,$as_montotart,$as_ciuemp,$adt_fecrec,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: Ing. Yozelin Barrag�n
		// Fecha Creaci�n: 05/02/2007
		//////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/cnumero_letra.php");
		$io_letra = new cnumero_letra();
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
		if($as_estpro==0)
		{
		  $ls_tipo_orden=" Orden de Compra ";
		}
		else
		{
		  $ls_tipo_orden=" Factura ";
		}
		$ls_prefijo="";
		$ls_sufijo="";
		$ls_monto_letras=$io_letra->uf_convertir_letra($as_montotart,$ls_prefijo,$ls_sufijo);
		$li_dia=substr($adt_fecrec,0,2);
		$li_mes=substr($adt_fecrec,3,2);
		$li_agno=substr($adt_fecrec,6,4);

		$ls_mes=$io_fecha->uf_load_nombre_mes($li_mes);
		$io_pdf->ezSetY(350);
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
	    $la_data=array(array('name'=>'los cuales fueron recibidos  '.$adt_fecrec.' segun <b>'.$ls_tipo_orden.' No. '.$as_numordcom.'</b> Acta levantada '),
				       array('name'=>'al efecto, en la forma siguiente:  El represenante del  <b>"'.trim($as_nombre).'"</b> '),
					   array('name'=>'luego de haber constatado que   <b>"LA EMPRESA"</b>  ejecuto el suministro se�alado en la referida  '.$ls_tipo_orden.', declarada  <b>ACEPTADA</b>  la compra .'),
					   array('name'=>'El monto correspondiente a este suministro es de Bolivares: ( Bs: '.number_format($as_montotart,2,",",".").' )'),
					   array('name'=>'<b>'.$ls_monto_letras.'</b>'),
					   array('name'=>'En prueba de conformidad se firma, en la ciudad de '.trim($as_ciuemp).', a los '.$li_dia.' dias del mes de '.$ls_mes.' de '.$li_agno.'. '));

		$la_columnas=array('name'=>'','name'=>'','name'=>'','name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'fontSize' => 12, // Tama�o de Letras
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol'=>array(0.5,0.5,0.5),//array($r,$g,$b), // Color de la sombra
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho M�ximo de la tabla
						 'xPos'=>306, // Orientaci�n de la tabla
						 /*'cols'=>array('codart'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la
						 			   'denart'=>array('justification'=>'center','width'=>230), // Justificaci�n y
						 			   'canart'=>array('justification'=>'center','width'=>100), // Justificaci�n y
									   'preuniart'=>array('justification'=>'center','width'=>100))*/); // Justificaci�n y ancho de la
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_pie_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($as_nombre,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_total // Total General
		//    Description : funci�n que imprime el fin de la cabecera de cada p�gina
		//	   Creado Por : Ing. Yesenia Moreno
		//  Modificado Por: Ing. Yozelin Barrag�n
		// Fecha Creaci�n: 05/02/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
        $io_pdf->addText(30,180,12,"POR LA EMPRESA");
        $io_pdf->addText(30,165,12,"ENTREGA CONFORME");
        $io_pdf->addText(30,145,12,"Firma:");
        $io_pdf->addText(30,130,12,"Nombre:");
        $io_pdf->addText(30,115,12,"Cargo:");
        $io_pdf->addText(30,100,12,"C:I. No.:");
		$io_pdf->line(70,145,180,145);	//Horizontal
		$io_pdf->line(75,130,180,130);	//Horizontal
		$io_pdf->line(70,115,180,115);	//Horizontal
		$io_pdf->line(75,100,180,100);	//Horizontal

        $io_pdf->addText(350,180,12,"POR EL ".trim($as_nombre));
        $io_pdf->addText(350,165,12,"ENTREGA CONFORME");
        $io_pdf->addText(350,145,12,"Firma:");
        $io_pdf->addText(350,130,12,"Nombre:");
        $io_pdf->addText(350,115,12,"Cargo:");
        $io_pdf->addText(350,100,12,"C:I. No.:");
		$io_pdf->line(390,145,510,145);	//Horizontal
		$io_pdf->line(395,130,510,130);	//Horizontal
		$io_pdf->line(390,115,510,115);	//Horizontal
		$io_pdf->line(395,100,510,100);	//Horizontal
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');

	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("sigesp_sim_class_report.php");
		$io_report = new sigesp_sim_class_report();
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../class_funciones_inventario.php");
		$io_fun_inventario=new class_funciones_inventario();
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -------------------------------------
	    $ls_numordcom=$_GET["txtnumordcom"];

	    $ls_tienda_desde=$io_fun_inventario->uf_obtenervalor_get("agro_desde","");
	    $ls_tienda_hasta=$io_fun_inventario->uf_obtenervalor_get("agro_hasta","");

		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_nombre=$_SESSION["la_empresa"]["nombre"];
		$ls_ciuemp=$_SESSION["la_empresa"]["ciuemp"];
	//----------------------------------------------------  Par�metros del encabezado  --------------------------------------------
		$ls_titulo="<b>ACTA DE RECEPCION DE BIENES</b>";
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )
     $lb_valido=$io_report->uf_sim_acta_recepcion_bienes($ls_codemp,$ls_numordcom,$ls_tienda_desde,$ls_tienda_hasta);
	 if($lb_valido==false) // Existe alg�n error � no hay registros
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
		$io_fun_inventario->uf_load_seguridad_reporte("SIM","sigesp_sim_r_acta_recepcion_bienes.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////

		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Times-Roman.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuraci�n de los margenes en cent�metros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la p�gina
		$io_pdf->ezStartPageNumbers(550,740,10,'','',1); // Insertar el n�mero de p�gina
		$li_totrow_det=$io_report->dts_reporte->getRowCount("numordcom");
		$ld_total=0;
		for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
		{
			  $li_contador=$li_s;
			  $ls_numordcom=$io_report->dts_reporte->data["numordcom"][$li_s];
			  $ls_cod_pro=$io_report->dts_reporte->data["cod_pro"][$li_s];
			  $ls_codalm=$io_report->dts_reporte->data["codalm"][$li_s];
			  $ls_nomresalm=$io_report->dts_reporte->data["nomresalm"][$li_s];
			  $ls_codart=$io_report->dts_reporte->data["codart"][$li_s];
			  $ls_canart=$io_report->dts_reporte->data["canart"][$li_s];
			  $ls_preuniart=number_format($io_report->dts_reporte->data["preuniart"][$li_s],2,",",".");
			  $ls_nompro=$io_report->dts_reporte->data["nompro"][$li_s];
			  $ls_cedrep=$io_report->dts_reporte->data["cedrep"][$li_s];
			  $ls_nomreppro=$io_report->dts_reporte->data["nomreppro"][$li_s];
			  $ls_denart=$io_report->dts_reporte->data["denart"][$li_s];
			  $ls_estpro=$io_report->dts_reporte->data["estpro"][$li_s];
			  $ldt_fecrec=$io_funciones->uf_convertirfecmostrar($io_report->dts_reporte->data["fecrec"][$li_s]);
			  $ls_montotart=$io_report->dts_reporte->data["montotart"][$li_s];

			 $la_data[$li_s]=array('codart'=>$ls_codart,'denart'=>$ls_denart,'canart'=>$ls_canart,'preuniart'=>$ls_preuniart);
		}//for
		uf_cabezera($ls_nomresalm,$ls_nompro,$ls_nomreppro,$ls_nombre,$io_pdf);
		uf_cabezera_detalle($io_pdf);
		uf_print_pie_detalle($ls_estpro,$ls_numordcom,$ls_nombre,$ls_montotart,$ls_ciuemp,$ldt_fecrec,$io_pdf);
		uf_print_pie_cabecera($ls_nombre,$io_pdf);
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
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
	unset($io_fun_inventario);
	unset($io_fecha);
?>