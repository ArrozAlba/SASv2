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
		$io_pdf->line(20,40,578,40);
		$io_pdf->setStrokeColor(0,0,0);
		//$io_pdf->rectangle(200,710,350,40);
		//$io_pdf->line(400,750,400,710);
		//$io_pdf->line(400,730,550 ,730);
		$io_pdf->addJpegFromFile('../../shared/imagebank/encabezado/'.$_SESSION["ls_logo"],30,680,560,100); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,680,12,$as_titulo); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(11,$as_fecha);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,490,10,$as_fecha); // Agregar el t�tulo
		$io_pdf->addText(525,695,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(527,688,7,date("h:i a")); // Agregar la Hora

		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_nomemp,$as_nomfisalm,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private
		//	    Arguments: as_nomemp    // nombre de la empresa
		//	    		   as_nomfisalm // nombre fiscal de la empresa
		//	    		   io_pdf       // total de registros que va a tener el reporte
		//    Description: funci�n que imprime la cabecera de cada p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 21/04/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>Empresa</b>  '.$as_nomemp.''),
					   array ('name'=>'<b>Almacen</b>  '.$as_nomfisalm.''));
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
		$la_columna=array('codigo'=>'<b>Codigo</b>',
						  'articulo'=>'<b>Denominacion</b>',
						  'proveedor'=>'<b>Proveedor</b>',
						  'detal'=>'<b>Existencia (Detal)</b>',
						  'existencia'=>'<b>Existencia (Mayor)</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la columna
						 			   'articulo'=>array('justification'=>'left','width'=>230), // Justificaci�n y ancho de la columna
						 			   'proveedor'=>array('justification'=>'left','width'=>50), // Justificaci�n y ancho de la columna
						 			   'detal'=>array('justification'=>'right','width'=>60), // Justificaci�n y ancho de la columna
						 			   'existencia'=>array('justification'=>'right','width'=>60))); // Justificaci�n y ancho de la columna
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
						  'sueldointegral'=>'',
						  'bonovacacional'=>'',
						  'bonofin'=>'',
						  'aporte'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>220), // Justificaci�n y ancho de la columna
						 			   'sueldointegral'=>array('justification'=>'right','width'=>70), // Justificaci�n y ancho de la columna
						 			   'bonovacacional'=>array('justification'=>'right','width'=>70), // Justificaci�n y ancho de la columna
						 			   'bonofin'=>array('justification'=>'right','width'=>70), // Justificaci�n y ancho de la columna
						 			   'aporte'=>array('justification'=>'right','width'=>70))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ai_totprenom,$ai_totant,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera
		//		   Access: private
		//	    Arguments: ai_totprenom // Total Pren�mina
		//	   			   ai_totant // Total Anterior
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el fin de la cabecera de cada p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 26/04/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>''));
		//$la_data=array(array('name'=>'_________________________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tama�o de Letras
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>510); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('total'=>''));
		$la_columna=array('total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>510, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>300), // Justificaci�n y ancho de la columna
						 			   'prenomina'=>array('justification'=>'right','width'=>100), // Justificaci�n y ancho de la columna
						 			   'anterior'=>array('justification'=>'right','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>510, // Ancho M�ximo de la tabla
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
	$ls_fecrec=$io_fun_inventario->uf_obtenervalor_get("fecrec","");

	$ls_titulo="<b> Niveles de Existencia de Articulos </b>";
	$ls_fecha="";
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
	$ls_codart="";
	$ls_codarti=$io_fun_inventario->uf_obtenervalor_get("codart","");
	$ls_codalm=$io_fun_inventario->uf_obtenervalor_get("codalm","");
	$li_ordenalm=$io_fun_inventario->uf_obtenervalor_get("ordenalm",0);
	$li_ordenart=$io_fun_inventario->uf_obtenervalor_get("ordenart",0);

	$ls_tienda_desde=$io_fun_inventario->uf_obtenervalor_get("agro_desde","");
	$ls_tienda_hasta=$io_fun_inventario->uf_obtenervalor_get("agro_hasta","");
	$ls_codpro=$io_fun_inventario->uf_obtenervalor_get("ls_codpro","");

	//$li_ordenalm=0;
	//$li_ordenart=1;
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_select_almacen($ls_codemp,$ls_codalm,$ls_codarti,$li_ordenalm,$ls_tienda_desde,$ls_tienda_hasta,$ls_codtiend); // Cargar el DS con los datos de la cabecera del reporte
	if($lb_valido==false) // Existe alg�n error � no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');");
		//print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(4.5,3,3,3); // Configuraci�n de los margenes en cent�metros


		uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la p�gina
		$io_pdf->ezStartPageNumbers(300,50,7,'','',1); // Insertar el n�mero de p�gina
		$li_totrow=$io_report->ds->getRowCount("codalm");
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
		    $io_pdf->transaction('start'); // Iniciamos la transacci�n
			$li_numpag=$io_pdf->ezPageCount; // N�mero de p�gina
			$li_totprenom=0;
			$li_totant=0;
			$ls_codalm=$io_report->ds->data["codalm"][$li_i];
			$ls_nomfisalm=$io_report->ds->data["nomfisalm"][$li_i];
			uf_print_cabecera($ls_nomemp,$ls_nomfisalm,$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_select_articuloxalmacen($ls_codemp,$ls_codalm,$ls_codarti,$li_ordenalm,$li_ordenart,$ls_tienda_desde,$ls_tienda_hasta,$ls_codpro,&$rs_data); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_total=0;
				$li_totrow_det=0;
				$li_s=0;
				while($row=$io_report->io_sql->fetch_row($rs_data))
				{
					$li_totrow_det++;
					$li_s++;
					$ls_codart= $row["codart"];
					$ls_denart= $row["denart"]."  ".$row["denuni"];
					$ls_cod_pro= $row["cod_pro"];
					$li_detal= $row["existencia"];
					$li_total=$li_total + $li_detal;
					$li_unidad=     $row["unidades"];
					$li_existencia= ($li_detal/$li_unidad);
					//print $li_existencia;
					$li_existencia=number_format($li_existencia,2,",",".");
					$li_detal=number_format($li_detal,2,",",".");
					$la_data[$li_s]=array('codigo'=>$ls_codart,'articulo'=>$ls_denart,'proveedor'=>$ls_cod_pro,'detal'=>$li_detal,'existencia'=>$li_existencia);
					//print $ls_denart= $io_report->ds_detalle->data["denart"][$li_s].$li_detal= $io_report->ds_detalle->data["existencia"][$li_s]."--";
				}
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
				uf_print_pie_cabecera("","",$io_pdf); // Imprimimos pie de la cabecera

				/*$la_data[1]=array('total'=>'<b>Total</b>','sueldointegral'=>$li_totalsueintper,'bonovacacional'=>$li_totalbonvacper,
								  'bonofin'=>$li_totalbonfinper,'aporte'=>$li_totalapoper);
				uf_print_totales($la_data,$io_pdf); // Imprimimos el detalle */

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
					uf_print_cabecera($ls_nomemp,$ls_nomfisalm,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
					uf_print_pie_cabecera("","",$io_pdf); // Imprimimos pie de la cabecera
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
