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
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$ad_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_desnom // Descripci�n de la n�mina
		//	    		   ad_fecha // Fecha
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 26/04/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(20,40,578,40);
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_numtra,$ad_fecemi,$as_nomfisori,$as_nomfisdes,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private
		//	    Arguments: as_numtra    //numero de transferencia
		//	    		   ad_fecemi    // fecha de la transferencia
		//	    		   as_nomfisori // nombre fiscal del almacen de origen
		//	    		   as_nomfisdes // nombre fiscal del almacen de destino
		//	    		   io_pdf       // total de registros que va a tener el reporte
		//    Description: funci�n que imprime la cabecera de cada p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 21/04/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_nomfisori=substr($as_nomfisori,0,35);
		$as_nomfisdes=substr($as_nomfisdes,0,35);
		$la_data=array(array('name'=>'<b>Transferencia</b>  '.$as_numtra.'                        <b>Fecha</b>  '.$ad_fecemi.''),
					   array('name'=>'<b>Origen</b>              '.$as_nomfisori.''),
					   array('name'=>'<b>Destino</b>            '.$as_nomfisdes.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar L�neas
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2	, // Sombra entre l�neas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 21/04/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_columna=array('articulo'=>'<b>Art�culo</b>',
						  'unidad'=>'<b>Unidad</b>',
						  'cantidad'=>'<b>Cantidad</b>',
						  'costo'=>'<b>Costo Unitario</b>',
						  'total'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('articulo'=>array('justification'=>'left','width'=>234), // Justificaci�n y ancho de la columna
						 			   'unidad'=>array('justification'=>'left','width'=>45), // Justificaci�n y ancho de la columna
						 			   'cantidad'=>array('justification'=>'right','width'=>62), // Justificaci�n y ancho de la columna
						 			   'costo'=>array('justification'=>'right','width'=>84), // Justificaci�n y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>75))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalleg
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 06/07/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('total'=>'',
						  'totcan'=>'',
						  'vacio'=>'',
						  'totmon'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>279), // Justificaci�n y ancho de la columna
						 			   'totcan'=>array('justification'=>'right','width'=>62), // Justificaci�n y ancho de la columna
						 			   'vacio'=>array('justification'=>'right','width'=>84), // Justificaci�n y ancho de la columna
						 			   'totmon'=>array('justification'=>'right','width'=>75))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center'); // Orientaci�n de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($as_dentipart,$ai_total,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera
		//		   Access: private
		//	    Arguments: as_dentipart // denominacion del tipo de articulo
		//	   			   ai_total // Total de articulos
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el fin de la cabecera de cada p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 26/04/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'________________________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tama�o de Letras
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>500); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('total'=>''));
		$la_columna=array('total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>396), // Justificaci�n y ancho de la columna
						 			   'entradas'=>array('justification'=>'left','width'=>100), // Justificaci�n y ancho de la columna
						 			   'salidas'=>array('justification'=>'left','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center'); // Orientaci�n de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_sim_class_report.php");
	$io_report=new sigesp_sim_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	require_once("../class_funciones_inventario.php");
	$io_fun_inventario=new class_funciones_inventario();
	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
	$ld_desde=$io_fun_inventario->uf_obtenervalor_get("desde","");
	$ld_hasta=$io_fun_inventario->uf_obtenervalor_get("hasta","");

	$ls_tienda_desde=$io_fun_inventario->uf_obtenervalor_get("agro_desde","");
	$ls_tienda_hasta=$io_fun_inventario->uf_obtenervalor_get("agro_hasta","");

	$ls_titulo="Reporte Transferencias entre Almacenes";
	$ls_fecha="Periodo ".$ld_desde." - ".$ld_hasta;
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
	$ls_numorddes="";
	$li_ordenfec=$io_fun_inventario->uf_obtenervalor_get("ordenfec","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_select_transferencia($ls_codemp,"",$ld_desde,$ld_hasta,$li_ordenfec,$ls_tienda_desde,$ls_tienda_hasta); // Cargar el DS con los datos de la cabecera del reporte
	if($lb_valido==false) // Existe alg�n error � no hay registros
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
		$io_pdf=new Cezpdf('LETTER','portait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(4.5,3,3,3); // Configuraci�n de los margenes en cent�metros
		$io_pdf->addJpegFromFile('../../shared/imagebank/encabezado/'.$_SESSION["ls_logo"],30,680,560,100); ; // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$ls_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,680,12,$ls_titulo); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(11,$ls_fecha);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,670,10,$ls_fecha); // Agregar el t�tulo
		$io_pdf->addText(525,695,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(531,688,7,date("h:i a")); // Agregar la Hora
		uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la p�gina
		$io_pdf->ezStartPageNumbers(300,50,7,'','',1); // Insertar el n�mero de p�gina
		$li_totrow=$io_report->ds->getRowCount("numtra");
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
		    $io_pdf->transaction('start'); // Iniciamos la transacci�n
			$li_numpag=$io_pdf->ezPageCount; // N�mero de p�gina
			$ls_numtra=     $io_report->ds->data["numtra"][$li_i];
			$ld_fecemi=     $io_report->ds->data["fecemi"][$li_i];
			$ls_nomfisori=  $io_report->ds->data["nomfisalmori"][$li_i];
			$ls_nomfisdes=  $io_report->ds->data["nomfisalmdes"][$li_i];
			$ld_fecemi=$io_funciones->uf_convertirfecmostrar($ld_fecemi);
			$li_totcan=0;
			$li_totmon=0;
			uf_print_cabecera($ls_numtra,$ld_fecemi,$ls_nomfisori,$ls_nomfisdes,$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_select_dt_transferencia($ls_codemp,$ls_numtra,$ls_tienda_desde,$ls_tienda_hasta);// Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->ds_detalle->getRowCount("codart");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_codart=     $io_report->ds_detalle->data["codart"][$li_s];
					$ls_denart=     $io_report->ds_detalle->data["denart"][$li_s];
					$li_cantidad=   $io_report->ds_detalle->data["cantidad"][$li_s];
					$li_cosuni=     $io_report->ds_detalle->data["cosuni"][$li_s];
					$li_costot=     $io_report->ds_detalle->data["costot"][$li_s];
					$ls_unidad=     $io_report->ds_detalle->data["unidad"][$li_s];
					$li_unidad=     $io_report->ds_detalle->data["unidades"][$li_s];
					if($ls_unidad=="D")
					{
						$ls_unidad="Detal";
					}
					else
					{
						$ls_unidad="Mayor";
						$li_cantidad=($li_cantidad / $li_unidad);

					}
					$li_totcan=$li_totcan + $li_cantidad;
					$li_totmon=$li_totmon + $li_costot;
					$li_cantidad=number_format($li_cantidad,2,",",".");
					$li_cosuni=number_format($li_cosuni,2,",",".");
					$li_costot=number_format($li_costot,2,",",".");
					$la_data[$li_s]=array('articulo'=>$ls_denart,'unidad'=>$ls_unidad,'cantidad'=>$li_cantidad,'costo'=>$li_cosuni,'total'=>$li_costot);
				}
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
				$li_totcan=number_format($li_totcan,2,",",".");
				$li_totmon=number_format($li_totmon,2,",",".");
				$la_data1[1]=array('total'=>'<b>Total</b>','totcan'=>$li_totcan,'vacio'=>'--','totmon'=>$li_totmon);
				uf_print_totales($la_data1,$io_pdf); // Imprimimos el detalle
				if ($io_pdf->ezPageCount==$li_numpag)
				{// Hacemos el commit de los registros que se desean imprimir
					$io_pdf->transaction('commit');
				}
				else
				{// Hacemos un rollback de los registros, agregamos una nueva p�gina y volvemos a imprimir
					$io_pdf->transaction('rewind');

						$io_pdf->ezNewPage(); // Insertar una nueva p�gina

					uf_print_cabecera($ls_numtra,$ld_fecemi,$ls_nomfisori,$ls_nomfisdes,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
					uf_print_totales($la_data1,$io_pdf); // Imprimimos el detalle
				}
			}
			unset($la_data);
		}
		if($lb_valido)
		{
			$io_pdf->ezStopPageNumbers(1,1);
			$io_pdf->ezStream();
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?>