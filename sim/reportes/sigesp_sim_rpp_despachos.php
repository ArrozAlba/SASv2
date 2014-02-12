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
		$io_pdf->line(20,40,730,40);
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_numorddes,$as_fecdes,$as_numsol,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private
		//	    Arguments: as_numorddes    //numero de orden de compra
		//	    		   as_fecdes    // fecha del despacho
		//	    		   io_pdf       // total de registros que va a tener el reporte
		//    Description: funci�n que imprime la cabecera de cada p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 21/04/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$as_nomfisalm=substr($as_nomfisalm,0,35);
		//$as_denpro=substr($as_denpro,0,25);
		$la_data=array(array('name'=>'<b>Orden de Despacho</b>  '.$as_numorddes.''),
					   array('name'=>'<b>Numero de Solicitud de Despacho </b>  '.$as_numsol.''),
					   array('name'=>'<b>Fecha               	       </b>  '.$as_fecdes.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar L�neas
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2	, // Sombra entre l�neas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>670, // Ancho de la tabla
						 'maxWidth'=>670); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_cabecera
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
						  'totsol'=>'',
						  'totart'=>'',
						  'vacio'=>'',
						  'totmon'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>670, // Ancho de la tabla
						 'maxWidth'=>670, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>375), // Justificaci�n y ancho de la columna
						 			   'totsol'=>array('justification'=>'right','width'=>62), // Justificaci�n y ancho de la columna
						 			   'totart'=>array('justification'=>'right','width'=>70), // Justificaci�n y ancho de la columna
						 			   'vacio'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
						 			   'totmon'=>array('justification'=>'right','width'=>82))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>660, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center'); // Orientaci�n de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_totales
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
		$la_columna=array('articulo'=>'<b>Articulo</b>',
						  'almacen'=>'<b>Almacen</b>',
						  'unidad'=>'<b>Unidad</b>',
						  'solicitada'=>'<b>Solicitada</b>',
						  'despachada'=>'<b>Despachada</b>',
						  'precio'=>'<b>Precio Unitario</b>',
						  'total'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>670, // Ancho de la tabla
						 'maxWidth'=>670, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('articulo'=>array('justification'=>'left','width'=>190), // Justificaci�n y ancho de la columna
						 			   'almacen'=>array('justification'=>'left','width'=>140), // Justificaci�n y ancho de la columna
						 			   'unidad'=>array('justification'=>'left','width'=>45), // Justificaci�n y ancho de la columna
						 			   'solicitada'=>array('justification'=>'right','width'=>62), // Justificaci�n y ancho de la columna
						 			   'despachada'=>array('justification'=>'right','width'=>70), // Justificaci�n y ancho de la columna
						 			   'precio'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>82))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalleg
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
		$la_data=array(array('name'=>'_____________________________________________________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tama�o de Letras
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>670); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('total'=>''));
		$la_columna=array('total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>670, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>400), // Justificaci�n y ancho de la columna
						 			   'entradas'=>array('justification'=>'left','width'=>100), // Justificaci�n y ancho de la columna
						 			   'salidas'=>array('justification'=>'left','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>670, // Ancho M�ximo de la tabla
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

	$ls_titulo="<b> Reporte de Ordenes de Despacho </b>";
	$ls_fecha="Periodo ".$ld_desde." - ".$ld_hasta;
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
	$ls_numorddes="";
	$li_ordenfec=$io_fun_inventario->uf_obtenervalor_get("ordenfec","");

	$ls_tienda_desde=$io_fun_inventario->uf_obtenervalor_get("agro_desde","");
	$ls_tienda_hasta=$io_fun_inventario->uf_obtenervalor_get("agro_hasta","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_select_despachos($ls_codemp,$ls_numorddes,$ld_desde,$ld_hasta,$li_ordenfec,$ls_tienda_desde,$ls_tienda_hasta); // Cargar el DS con los datos de la cabecera del reporte
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
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(4.5,3,3,3); // Configuraci�n de los margenes en cent�metros
		$io_pdf->addJpegFromFile('../../shared/imagebank/encabezado/'.$_SESSION["ls_logo"],30,500,720,100); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(15,$ls_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,500,12,$ls_titulo); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(15,$ls_fecha);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,690,15,$ls_fecha); // Agregar la fecha
		$io_pdf->addText(685,515,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(691,508,7,date("h:i a")); // Agregar la Hora
		uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la p�gina
		$io_pdf->ezStartPageNumbers(400,50,7,'','',1); // Insertar el n�mero de p�gina
		$li_totrow=$io_report->ds->getRowCount("numorddes");
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
		    $io_pdf->transaction('start'); // Iniciamos la transacci�n
			$li_numpag=$io_pdf->ezPageCount; // N�mero de p�gina
			$li_total=0;
			$li_totcanart=0;
			$li_totcansol=0;
			$ls_numorddes= $io_report->ds->data["numorddes"][$li_i];
			$ls_numsol=    $io_report->ds->data["numsol"][$li_i];
			$ls_fecdes=    $io_report->ds->data["fecdes"][$li_i];
			$ls_fecdes=$io_funciones->uf_convertirfecmostrar($ls_fecdes);
			uf_print_cabecera($ls_numorddes,$ls_fecdes,$ls_numsol,$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_select_dt_despacho($ls_codemp,$ls_numorddes,$ld_desde,$ld_hasta,$li_ordenfec,$ls_tienda_desde,$ls_tienda_hasta); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->ds_detalle->getRowCount("codart");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_codart=     $io_report->ds_detalle->data["codart"][$li_s];
					$ls_denart=     $io_report->ds_detalle->data["denart"][$li_s];
					$ls_nomfisalm=  $io_report->ds_detalle->data["nomfisalm"][$li_s];
					$li_canart=     $io_report->ds_detalle->data["canart"][$li_s];
					$li_cansol=     $io_report->ds_detalle->data["canorisolsep"][$li_s];
					$li_preuniart=  $io_report->ds_detalle->data["preuniart"][$li_s];
					$li_montotart=  $io_report->ds_detalle->data["montotart"][$li_s];
					$ls_unidad=     $io_report->ds_detalle->data["unidad"][$li_s];
					if($ls_unidad=="D"){$ls_unidad="Detal";}
					else{$ls_unidad="Mayor";}
					$li_total=$li_total + $li_montotart;
					$li_totcanart=$li_totcanart + $li_canart;
					$li_totcansol=$li_totcansol + $li_cansol;
					$li_canart=number_format($li_canart,2,",",".");
					$li_cansol=number_format($li_cansol,2,",",".");
					$li_preuniart=number_format($li_preuniart,2,",",".");
					$li_montotart=number_format($li_montotart,2,",",".");
					$la_data[$li_s]=array('articulo'=>$ls_denart,'almacen'=>$ls_nomfisalm,'unidad'=>$ls_unidad,'solicitada'=>$li_cansol,'despachada'=>$li_canart,'precio'=>$li_preuniart,'total'=>$li_montotart);
				}
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
				$li_total=number_format($li_total,2,",",".");
				$li_totcanart=number_format($li_totcanart,2,",",".");
				$li_totcansol=number_format($li_totcansol,2,",",".");
				$la_data1[1]=array('total'=>'<b>Total</b>','totsol'=>$li_totcansol,'totart'=>$li_totcanart,'vacio'=>'--','totmon'=>$li_total);
				uf_print_totales($la_data1,$io_pdf); // Imprimimos el detalle
				if ($io_pdf->ezPageCount==$li_numpag)
				{// Hacemos el commit de los registros que se desean imprimir
					$io_pdf->transaction('commit');
				}
				else
				{// Hacemos un rollback de los registros, agregamos una nueva p�gina y volvemos a imprimir
					$io_pdf->transaction('rewind');
					//if($li_numpag>1)
					//{
						$io_pdf->ezNewPage(); // Insertar una nueva p�gina
					//}
					uf_print_cabecera($ls_numorddes,$ls_fecdes,$ls_numsol,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
					$la_data1[1]=array('total'=>'<b>Total</b>','totsol'=>$li_totcansol,'totart'=>$li_totcanart,'vacio'=>'--','totmon'=>$li_total);
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