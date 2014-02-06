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
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],55.5,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,550,11,"<b>".$as_titulo."</b>"); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(11,$ad_fecha);
		$tm=390-($li_tm/2);
		$io_pdf->addText($tm,535,11,$ad_fecha); // Agregar la fecha
		$io_pdf->addText(685,550,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(691,543,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($ls_numtom,$ls_nomfisalm,$ld_fectom,$ls_obstom,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private
		//	    Arguments: ls_numtom    // numero toma
		//	    		   ls_nomfisalm // nombre fiscal de almacen
		//	    		   ld_fectom    // fecha de la toma
		//	    		   ls_obstom    // observaciones
		//	    		   io_pdf       // total de registros que va a tener el reporte
		//    Description: funci�n que imprime la cabecera de cada p�gina
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 15/09/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$as_nomfisalm=substr($as_nomfisalm,0,35);
		//$as_denpro=substr($as_denpro,0,25);
		$la_data=array(array('name'=>'<b>Nro. de Toma</b>  '.$ls_numtom.''),
					   array('name'=>'<b>Almac�n         </b>  '.$ls_nomfisalm.''),
					   array('name'=>'<b>Fecha             </b>  '.$ld_fectom.''),
					   array('name'=>'<b>Observaci�n  </b>  ccc'.$ls_obstom.''));
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
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 15/09/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('total'=>'',
						  'vacio'=>'',
						  'totdif'=>'',
						  'totcos'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>670, // Ancho de la tabla
						 'maxWidth'=>670, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>430), // Justificaci�n y ancho de la columna
						 			   'vacio'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
						 			   'totdif'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
						 			   'totcos'=>array('justification'=>'right','width'=>80))); // Justificaci�n y ancho de la columna
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
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 15/09/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_columna=array('codart'=>'<b>C�digo</b>',
						  'denart'=>'<b>Art�culo</b>',
						  'denunimed'=>'<b>Unidad</b>',
						  'cospro'=>'<b>Costo Promedio</b>',
						  'diferencia'=>'<b>Diferencia</b>',
						  'totcospro'=>'<b>Total Costo Promedio</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>670, // Ancho de la tabla
						 'maxWidth'=>670, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('codart'=>array('justification'=>'left','width'=>120), // Justificaci�n y ancho de la columna
						 			   'denart'=>array('justification'=>'left','width'=>210), // Justificaci�n y ancho de la columna
						 			   'denunimed'=>array('justification'=>'left','width'=>100), // Justificaci�n y ancho de la columna
						 			   'cospro'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
						 			   'diferencia'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
						 			   'totcospro'=>array('justification'=>'right','width'=>80))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalleg
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

	$ls_titulo="<b> Valoración de Ajustes de Inventario </b>";
	$ls_fecha="Periodo ".$ld_desde." - ".$ld_hasta;
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
	$ls_numorddes="";
	$li_ordenfec=$io_fun_inventario->uf_obtenervalor_get("ordenfec","");
	$ls_tienda_desde=$io_fun_inventario->uf_obtenervalor_get("agro_desde","");
	$ls_tienda_hasta=$io_fun_inventario->uf_obtenervalor_get("agro_hasta","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_select_ajuste($ls_codemp,$ld_desde,$ld_hasta,$ls_tienda_desde,$ls_tienda_hasta); // Cargar el DS con los datos de la cabecera del reporte
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
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuraci�n de los margenes en cent�metros
		uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la p�gina
		$io_pdf->ezStartPageNumbers(690,50,10,'','',1); // Insertar el n�mero de p�gina
		$li_totrow=$io_report->ds->getRowCount("numtom");
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
		    $io_pdf->transaction('start'); // Iniciamos la transacci�n
			$li_numpag=$io_pdf->ezPageCount; // N�mero de p�gina
			$li_totdif=0;
			$li_totcos=0;
			$ls_numtom= $io_report->ds->data["numtom"][$li_i];
			$ls_nomfisalm=$io_report->ds->data["nomfisalm"][$li_i];
			$ls_codalm=$io_report->ds->data["codalm"][$li_i];
			$ls_obstom=$io_report->ds->data["obstom"][$li_i];
			$ld_fectom=$io_report->ds->data["fectom"][$li_i];
			$ld_fectom=$io_funciones->uf_convertirfecmostrar($ld_fectom);
			uf_print_cabecera($ls_numtom,$ls_nomfisalm,$ld_fectom,$ls_obstom,$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_select_dt_valtoma($ls_codemp,$ls_numtom,$ls_tienda_desde,$ls_tienda_hasta); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->ds_detalle->getRowCount("codart");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_codart=     $io_report->ds_detalle->data["codart"][$li_s];
					$ls_denart=     $io_report->ds_detalle->data["denart"][$li_s];
					$ls_denunimed=  $io_report->ds_detalle->data["denunimed"][$li_s];
					$li_cospro=     $io_report->ds_detalle->data["cospro"][$li_s];
					$li_canexisis=  $io_report->ds_detalle->data["canexisis"][$li_s];
					$li_canexifis=  $io_report->ds_detalle->data["canexifis"][$li_s];

//					print "<br>".$li_s."SISTEMA->".$li_canexisis."FISICA->".$li_canexifis."<br>";

					$li_diferencia=($li_canexisis - $li_canexifis);
					$li_totcospro=($li_diferencia * $li_cospro);

					$li_totdif=($li_totdif + $li_diferencia);
					$li_totcos=($li_totcos + $li_totcospro);

					$li_cospro=     number_format($li_cospro,2,",",".");
					$li_diferencia= number_format($li_diferencia,2,",",".");
					$li_totcospro=  number_format($li_totcospro,2,",",".");
					$la_data[$li_s]=array('codart'=>$ls_codart,'denart'=>$ls_denart,'denunimed'=>$ls_denunimed,
										  'cospro'=>$li_cospro,'diferencia'=>$li_diferencia,'totcospro'=>$li_totcospro);
				}
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
				$li_totdif=number_format($li_totdif,2,",",".");
				$li_totcos=number_format($li_totcos,2,",",".");
				$la_data1[1]=array('total'=>'<b>Total</b>','vacio'=>"--",'totdif'=>$li_totdif,'totcos'=>$li_totcos);
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
					uf_print_cabecera($ls_numtom,$ls_nomfisalm,$ld_fectom,$ls_obstom,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
					$la_data1[1]=array('total'=>'<b>Total</b>','vacio'=>"--",'totdif'=>$li_totdif,'totcos'=>$li_totcos);
					uf_print_totales($la_data1,$io_pdf); // Imprimimos el detalle
				}
			}
			unset($la_data);
			unset($la_data1);
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