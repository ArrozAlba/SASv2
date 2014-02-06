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
	function uf_print_encabezado_pagina($as_titulo,$as_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_desnom // Descripci�n de la n�mina
		//	    		   as_fecha // Fecha
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 26/04/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->line(10,40,775,40);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_nomemp,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private
		//	    Arguments: as_nomemp    // nombre de la empresa
		//	    		   io_pdf       // total de registros que va a tener el reporte
		//    Description: funci�n que imprime la cabecera de cada p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 21/04/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 11, // Tama�o de Letras
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0	, // Sombra entre l�neas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>730, // Ancho de la tabla
						 'maxWidth'=>730); // Ancho M�ximo de la tabla
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
		$la_columna=array('codigo'=>'<b>C�digo</b>',
						  'articulo'=>'<b>Art�culo</b>',
						  'unidad'=>'<b>Unidad</b>',
						  'precio'=>'<b>Precio</b>',
						  'ultcosart'=>'<b>Ultimo Costo</b>',
						  'cosproart'=>'<b>Costo Promedio</b>',
						  'existencia'=>'<b>Existencia</b>',
						  'entradas'=>'<b>Entradas</b>',
						  'salidas'=>'<b>Salidas</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'left','width'=>120), // Justificaci�n y ancho de la columna
						 			   'articulo'=>array('justification'=>'left','width'=>180), // Justificaci�n y ancho de la columna
						 			   'unidad'=>array('justification'=>'left','width'=>70), // Justificaci�n y ancho de la columna
						 			   'precio'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
						 			   'ultcosart'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
						 			   'cosproart'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
						 			   'existencia'=>array('justification'=>'right','width'=>60), // Justificaci�n y ancho de la columna
						 			   'entradas'=>array('justification'=>'right','width'=>50), // Justificaci�n y ancho de la columna
						 			   'salidas'=>array('justification'=>'right','width'=>50))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ai_totent,$ai_totsal,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera
		//		   Access: private
		//	    Arguments: ai_totent // Total Entradas
		//	   			   ai_totsal // Total Salidas
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el fin de la cabecera de cada p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 26/04/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>''));
		//$la_data=array(array('name'=>'_______________________________________________________________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tama�o de Letras
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>730); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('total'=>''));
		$la_columna=array('total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>730, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>500), // Justificaci�n y ancho de la columna
						 			   'entradas'=>array('justification'=>'right','width'=>100), // Justificaci�n y ancho de la columna
						 			   'salidas'=>array('justification'=>'right','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>730, // Ancho M�ximo de la tabla
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

	$ls_titulo="<b> Resumen de Inventario </b>";
	if($ld_desde!="")
	{$ls_fecha="Periodo ".$ld_desde." - ".$ld_hasta;}
	else
	{$ls_fecha="";}

	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
	$ls_coddesde=$io_fun_inventario->uf_obtenervalor_get("coddesde","");
	$ls_codhasta=$io_fun_inventario->uf_obtenervalor_get("codhasta","");
	$li_ordenart=$io_fun_inventario->uf_obtenervalor_get("ordenart","");
	$li_existencia=$io_fun_inventario->uf_obtenervalor_get("existencia",0);

	//--------------------------------------------------------------------------------------------------------------------------------
	//$lb_valido=$io_report->uf_select_articulos($ls_codemp,$ls_codalm,$ls_codart,$ld_desde,$ld_hasta,$li_total,$li_ordenart,$ls_tienda_desde,$ls_tienda_hasta); // Cargar el DS con los datos de la cabecera del reporte
	$lb_valido=$io_report->uf_select_inventario($ls_codemp,$ls_coddesde,$ls_codhasta,$ld_desde,$ld_hasta,$li_ordenart,$ls_tienda_desde,$ls_tienda_hasta); // Obtenemos el detalle del reporte
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
		$li_totrow=1;//$io_report->ds->getRowCount("codart");
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{

			$li_numpag=$io_pdf->ezPageCount; // N�mero de p�gina

			uf_print_cabecera($ls_nomemp,$io_pdf); // Imprimimos la cabecera del registro


				$li_totrow_det=$io_report->ds->getRowCount("codart");
	//print $li_totrow_det."--".$li_existencia;
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_codart=     $io_report->ds->data["codart"][$li_s];
					$ls_denart=     $io_report->ds->data["denart"][$li_s];
					$ls_denunimed=  $io_report->ds->data["denunimed"][$li_s];
					$li_unidad=     $io_report->ds->data["unidad"][$li_s];
					$li_exiart=     $io_report->ds->data["existencia"][$li_s];
					$li_preuniart=  $io_report->ds->data["ultcosart"][$li_s];
					$li_entradas=   $io_report->ds->data["entradas"][$li_s];
					$li_salidas=    $io_report->ds->data["salidas"][$li_s];
					$li_ultcosart=  $io_report->ds->data["ultcosart"][$li_s];
					$li_cosproart=  $io_report->ds->data["cosproart"][$li_s];

					$li_exiartaux=$li_exiart;
					$li_exiart=number_format($li_exiart,2,",",".");
					$li_preuniart=number_format($li_preuniart,2,",",".");
					$li_entradas=number_format($li_entradas,2,",",".");
					$li_salidas=number_format($li_salidas,2,",",".");
					$li_ultcosart=number_format($li_ultcosart,2,",",".");
					$li_cosproart=number_format($li_cosproart,2,",",".");
					if($li_existencia!=1)
					{
						if($li_exiartaux>0)
						{
							$la_data[$li_s]=array('codigo'=>$ls_codart,'articulo'=>$ls_denart,'unidad'=>$ls_denunimed,'precio'=>$li_preuniart,
												  'existencia'=>$li_exiart,'entradas'=>$li_entradas,'salidas'=>$li_salidas,
												  'ultcosart'=>$li_ultcosart,'cosproart'=>$li_cosproart);
						}
					}
					else
					{
						$la_data[$li_s]=array('codigo'=>$ls_codart,'articulo'=>$ls_denart,'unidad'=>$ls_denunimed,'precio'=>$li_preuniart,
											  'existencia'=>$li_exiart,'entradas'=>$li_entradas,'salidas'=>$li_salidas,
											  'ultcosart'=>$li_ultcosart,'cosproart'=>$li_cosproart);
					}
					//print_r($la_data);
				}


				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
				uf_print_pie_cabecera("","",$io_pdf); // Imprimimos pie de la cabecera
				if ($io_pdf->ezPageCount==$li_numpag)
				{// Hacemos el commit de los registros que se desean imprimir
					$io_pdf->transaction('commit');
				}
				else
				{// Hacemos un rollback de los registros, agregamos una nueva p�gina y volvemos a imprimir
					$io_pdf->transaction('rewind');
					if($li_numpag>1)
					{
						$io_pdf->ezNewPage(); // Insertar una nueva p�gina
					}
					/*uf_print_cabecera($ls_nomemp,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
					uf_print_pie_cabecera("","",$io_pdf); // Imprimimos pie de la cabecera*/
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