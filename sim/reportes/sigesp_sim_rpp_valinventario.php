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
		$io_pdf->addJpegFromFile('../../shared/imagebank/encabezado/'.$_SESSION["ls_logo"],30,500,720,100); // Agregar Logo

		//$io_pdf->addJpegFromFile('../../shared/imagebank/encabezado/'.$_SESSION["ls_logo"],30,500,720,100); // Agregar Logo
	//$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],9,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(14,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,529,14,$as_titulo); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(12,$as_fecha);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,515,12,$as_fecha); // Agregar el t�tulo
		$io_pdf->addText(730,535,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(735,528,7,date("h:i a")); // Agregar la Hora
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
						  'existencia'=>'<b>Existencia</b>',
						  'cospro'=>'<b>Costo Promedio</b>',
						  'totcospro'=>'<b>Total a Costo Promedio</b>',
						  'ultcosart'=>'<b>�ltimo Costo</b>',
						  'totultcos'=>'<b>Total a �ltimo Costo</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'left','width'=>110), // Justificaci�n y ancho de la columna
						 			   'articulo'=>array('justification'=>'left','width'=>178), // Justificaci�n y ancho de la columna
						 			   'unidad'=>array('justification'=>'left','width'=>70), // Justificaci�n y ancho de la columna
						 			   'existencia'=>array('justification'=>'right','width'=>75), // Justificaci�n y ancho de la columna
						 			   'cospro'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
						 			   'totcospro'=>array('justification'=>'right','width'=>85), // Justificaci�n y ancho de la columna
						 			   'ultcosart'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
						 			   'totultcos'=>array('justification'=>'right','width'=>85))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
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
						  'existencia'=>'',
						  'cospro'=>'',
						  'totcospro'=>'',
						  'ultcosart'=>'',
						  'totultcos'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>670, // Ancho de la tabla
						 'maxWidth'=>670, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>358), // Justificaci�n y ancho de la columna
						 			   'existencia'=>array('justification'=>'right','width'=>75), // Justificaci�n y ancho de la columna
						 			   'cospro'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
						 			   'totcospro'=>array('justification'=>'right','width'=>85), // Justificaci�n y ancho de la columna
						 			   'ultcosart'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
						 			   'totultcos'=>array('justification'=>'right','width'=>85))); // Justificaci�n y ancho de la columna
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

	$ls_titulo="<b> Valoración de Inventario </b>";
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
	$ls_tienda_desde=$io_fun_inventario->uf_obtenervalor_get("agro_desde","");
	$ls_tienda_hasta=$io_fun_inventario->uf_obtenervalor_get("agro_hasta","");

	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_select_articulosmovimientos($ls_codemp,$ls_coddesde,$ls_codhasta,$ld_desde,$ld_hasta,$li_ordenart,$ls_tienda_desde,$ls_tienda_hasta); // Cargar el DS con los datos de la cabecera del reporte
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
		uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la p�gina
		$io_pdf->ezStartPageNumbers(760,50,10,'','',1); // Insertar el n�mero de p�gina
		$li_totrow=$io_report->ds->getRowCount("codart");
		uf_print_cabecera($ls_nomemp,$io_pdf); // Imprimimos la cabecera del registro
		$li_totalexi=0;
		$li_totalpro=0;
		$li_totalult=0;
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{

		    //$io_pdf->transaction('start'); // Iniciamos la transacci�n
			$li_numpag=$io_pdf->ezPageCount; // N�mero de p�gina
			$li_totent=0;
			$li_totsal=0;
			$ls_codart=     $io_report->ds->data["codart"][$li_i];
			$li_cospro=0;
			$li_ultcos=0;
            //echo '<br>'.$li_i.'<br><br>';
			$lb_valido=$io_report->uf_select_promedio($ls_codemp,$ls_codart,$ld_desde,$ld_hasta,$li_cospro,$ls_tienda_desde,$ls_tienda_hasta); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->ds->getRowCount("codart");
			//	$ls_codart= $io_report->ds_detalle->data["codart"][1];
				$ls_denart= $io_report->ds_detalle->data["denart"][1];
				$ls_denunimed= $io_report->ds_detalle->data["denunimed"][1];
				$li_exiart= $io_report->ds_detalle->data["exiart"][1];
				$li_ultcos= $io_report->ds_detalle->data["ultimo"][1];
				$li_totcospro= ($li_exiart * $li_cospro);
				$li_totultcos= ($li_exiart * $li_ultcos);
				$li_totalexi=$li_totalexi + $li_exiart;
				$li_totalpro=$li_totalpro + $li_totcospro;
				$li_totalult=$li_totalult + $li_totultcos;

				$li_cospro=number_format($li_cospro,2,",",".");
				$li_exiart=number_format($li_exiart,2,",",".");
				$li_ultcos=number_format($li_ultcos,2,",",".");
				$li_totcospro=number_format($li_totcospro,2,",",".");
				$li_totultcos=number_format($li_totultcos,2,",",".");

				$la_data[$li_i]=array('codigo'=>$ls_codart,'articulo'=>$ls_denart,'unidad'=>$ls_denunimed,
									  'existencia'=>$li_exiart,'cospro'=>$li_cospro,'totcospro'=>$li_totcospro,
									  'ultcosart'=>$li_ultcos,'totultcos'=>$li_totultcos);
			}else{
				print("<script language=JavaScript>");
				print(" alert('No hay nada que Reportar');");
				print(" close();");
				print("</script>");
			}
		}
		$li_totalexi=number_format($li_totalexi,2,",",".");
		$li_totalpro=number_format($li_totalpro,2,",",".");
		$li_totalult=number_format($li_totalult,2,",",".");
		$la_datat[1]=array('total'=>"Total",'existencia'=>$li_totalexi,'cospro'=>"--",'totcospro'=>$li_totalpro,
						   'ultcosart'=>"--",'totultcos'=>$li_totalult);
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
		uf_print_totales($la_datat,$io_pdf); // Imprimimos el detalle
		unset($la_data);
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