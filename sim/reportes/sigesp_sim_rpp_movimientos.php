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
		//	    		   as_fecha // periodo de fecha
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 26/04/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->line(20,40,730,40);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_nomemp,$as_codart,$as_denart,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private
		//	    Arguments: as_nomemp    // nombre de la empresa
		//	    		   as_codart    // codigo del articulo
		//	    		   as_denart    // denominacion del articulo
		//	    		   io_pdf       // total de registros que va a tener el reporte
		//    Description: funci�n que imprime la cabecera de cada p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 21/04/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$as_nomfisalm=substr($as_nomfisalm,0,35);
		//$as_denpro=substr($as_denpro,0,25);
		$la_data=array(array('name'=>'<b>Empresa</b>  '.$as_nomemp.''),
					   array ('name'=>'<b>Articulo</b>  '.$as_codart.' '.$as_denart));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar L�neas
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2	, // Sombra entre l�neas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>760, // Ancho de la tabla
						 'maxWidth'=>760); // Ancho M�ximo de la tabla
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
		$la_columna=array('fecha'=>'<b>Fecha</b>',
						  'operacion'=>'<b>Operacion</b>',
						  'documento'=>'<b>Documento</b>',
						  'almacen'=>'<b>Almacon</b>',
						  'cantidad'=>'<b>Cantidad</b>',
						  'costo'=>'<b>Costo</b>',
						  'existe'=>'<b>Existencia</b>',
						  'total'=>'<b>Total Costo</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>670, // Ancho de la tabla
						 'maxWidth'=>670, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('fecha'=>array('justification'=>'left','width'=>80), // Justificaci�n y ancho de la columna
						 			   'operacion'=>array('justification'=>'left','width'=>100), // Justificaci�n y ancho de la columna
						 			   'documento'=>array('justification'=>'left','width'=>100), // Justificaci�n y ancho de la columna
						 			   'almacen'=>array('justification'=>'left','width'=>160), // Justificaci�n y ancho de la columna
						 			   'cantidad'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
						 			   'costo'=>array('justification'=>'right','width'=>80),

									   'existe'=>array('justification'=>'right','width'=>80),
									   'total'=>array('justification'=>'right','width'=>80))); // Justificaci�n y ancho de la columna
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

		$io_pdf->ezSetDy(-15);
		$la_columna = array('name1'=>'','name2'=>'','name3'=>'','name4'=>'');

		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>670, // Ancho de la tabla
						 'maxWidth'=>670, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('name1'=>array('justification'=>'left','width'=>160), // Justificaci�n y ancho de la columna
						 			   'name2'=>array('justification'=>'right','width'=>50),
									   'name3'=>array('justification'=>'left','width'=>160), // Justificaci�n y ancho de la columna
						 			   'name4'=>array('justification'=>'right','width'=>50))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);

	}// end function uf_print_totales
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
				//print "Entradas".$ai_totent."Salidas".$ai_totsal."<br>";
		$la_data=array(array('name'=>'____________________________________________________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tama�o de Letras
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>660); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('total'=>'<b>Totales:        Entradas  </b>'.$ai_totent.' '.'<b>Salidas  </b>'.$ai_totsal.''));
		$la_columna=array('total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>660, // Ancho M�ximo de la tabla
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
						 'width'=>660, // Ancho M�ximo de la tabla
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
	require_once("../../shared/class_folder/class_datastore.php");
	$ds_existencia=new class_datastore();
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_mensajes.php");
	require_once("../../shared/class_folder/class_sql.php");
	$io_datastore= new class_datastore();
	$io_include=new sigesp_include();
	$io_connect=$io_include->uf_conectar();
	$io_msg=new class_mensajes();
	$io_sql=new class_sql($io_connect);


	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------

	if(array_key_exists("orden",$_GET))
	{
		$li_orden=$_GET["orden"];
	}
	else
	{
		$li_orden="";
	}


	$ld_desde=$io_fun_inventario->uf_obtenervalor_get("desde","");
	$ld_hasta=$io_fun_inventario->uf_obtenervalor_get("hasta","");
	$ls_tienda_desde=$io_fun_inventario->uf_obtenervalor_get("agro_desde","");
	$ls_tienda_hasta=$io_fun_inventario->uf_obtenervalor_get("agro_hasta","");

	$ls_titulo="Movimientos de Art�culos";
	if($ld_desde!="")
	{$ls_fecha="Periodo Desde: ".$ld_desde."  Hasta: ".$ld_hasta;}
	else
	{$ls_fecha="";}

	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
	$ls_codart=$io_fun_inventario->uf_obtenervalor_get("codart","");
	$ls_codalm=$io_fun_inventario->uf_obtenervalor_get("codalm","");
	$li_ordenart=$io_fun_inventario->uf_obtenervalor_get("ordenart","");
	$li_ordenfec=$io_fun_inventario->uf_obtenervalor_get("ordenfec","");
	$li_existencia=0;
	$li_totcantent=0;
	$li_totmovsal=0;
	$li_totcansal=0;
	$li_totmovrev=0;
	$li_totcanrev=0;//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_select_articulos($ls_codemp,$ls_codalm,$ls_codart,$ld_desde,$ld_hasta,$li_total,$li_ordenart,$ls_tienda_desde,$ls_tienda_hasta); // Cargar el DS con los datos de la cabecera del reporte
	if($lb_valido==false) // Existe algun error o no hay registros
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
		$li_tm=$io_pdf->getTextWidth(11,$ls_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,520,12,$ls_titulo); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(15,$ls_fecha);
		$tm=396-($li_tm/2);
		//$io_pdf->addText($tm,690,15,$ls_fecha); // Agregar la fecha
		$io_pdf->addText(685,515,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(691,508,7,date("h:i a")); // Agregar la Hora
		$io_pdf->addText(300,508,9,$ls_fecha);

		uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la p�gina
		$io_pdf->ezStartPageNumbers(400,50,7,'','',1); // Insertar el n�mero de p�gina
		$li_totrow=$io_report->ds->getRowCount("codart");
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
		    $io_pdf->transaction('start'); // Iniciamos la transacci�n
			$li_numpag=$io_pdf->ezPageCount; // N�mero de p�gina
			$li_totent=0;
			$li_totsal=0;
			$ls_codart=  $io_report->ds->data["codart"][$li_i];
			$ls_denart=  $io_report->ds->data["denart"][$li_i];
			uf_print_cabecera($ls_nomemp,$ls_codart,$ls_denart,$io_pdf); //Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_select_movimientosxarticulos($ls_codemp,$ls_codalm,$ls_codart,$ld_desde,$ld_hasta,
																   $li_total,$li_ordenart,$li_ordenfec,$ls_tienda_desde,$ls_tienda_hasta); // Obtenemos el detalle del reporte
			//print $lb_valido;
			if($lb_valido)
			{

				//$li_existencia=0;
				$li_totmovent=0;

				$li_totrow_det=$io_report->ds_detalle->getRowCount("nummov");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ld_fecmov=     $io_report->ds_detalle->data["fecmov"][$li_s];
					$ls_opeinv=     $io_report->ds_detalle->data["opeinv"][$li_s];
					$ls_numdoc=     $io_report->ds_detalle->data["numdoc"][$li_s];
					$ls_nomfisalm=  $io_report->ds_detalle->data["nomfisalm"][$li_s];
					$li_canart=     $io_report->ds_detalle->data["canart"][$li_s];
					$li_cosart=     $io_report->ds_detalle->data["cosart"][$li_s];
					$ld_fecmov=     $io_funciones->uf_convertirfecmostrar($ld_fecmov);
					$ls_coddoc=     $io_report->ds_detalle->data["codprodoc"][$li_s];

					if($ls_opeinv=="ENT")
					{
						$ls_opeinv="Entrada de Inventario";
						$li_totmovent=$li_totmovent + 1;
						$li_totcantent=$li_totcantent + $li_canart;
						$li_existencia=$li_existencia + $li_canart;
					}
					elseif(($ls_opeinv=="SAL") and ($ls_coddoc=="FAC"))
					{
						$ls_opeinv="Salida de Inventario por Facturacion";
						$li_totmovsal=$li_totmovsal + 1;

						$li_totcansal=$li_totcansal + $li_canart;
						$li_existencia=$li_existencia - $li_canart;

					}
					elseif(($ls_opeinv=="SAL") and ($ls_coddoc=="REV"))
					{
						$ls_opeinv="Reverso de Inventario";
						$li_totmovrev=$li_totmovrev + 1;
						$li_totcanrev=$li_totcanrev + $li_canart;
						$li_existencia=$li_existencia - $li_canart;

					}
					elseif(($ls_opeinv=="SAL") and ($ls_coddoc=="SAL"))
					{
						$ls_opeinv="Salida de Inventario por Despacho";
						$li_totmovsal=$li_totmovsal + 1;
						$li_totcansal=$li_totcansal + $li_canart;
						$li_existencia=$li_existencia - $li_canart;
					}
					elseif(($ls_opeinv=="SAL") and ($ls_coddoc=="ALM"))
					{
						$ls_opeinv="Salida de Inventario por Transferencia";
						$li_totmovsal=$li_totmovsal + 1;
						$li_totcansal=$li_totcansal + $li_canart;
						$li_existencia=$li_existencia - $li_canart;
					}
					$li_totalcosto=$li_cosart*$li_canart;
					$li_cosart=number_format($li_cosart,2,",",".");
					$li_canart=number_format($li_canart,2,",",".");

					$li_existenciapan=number_format($li_existencia,2,",",".");
					$li_totalcosto=number_format($li_totalcosto,2,",",".");


					$la_data[$li_s]=array('fecha'=>$ld_fecmov,'operacion'=>$ls_opeinv,'documento'=>$ls_numdoc,'almacen'=>$ls_nomfisalm,'cantidad'=>$li_canart,'costo'=>$li_cosart,'existe'=>$li_existenciapan,'total'=>$li_totalcosto);

				//$li_existencia=0;
				}

				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle


				$li_totmovent=number_format($li_totmovent,2,",",".");
				$li_totcantent=number_format($li_totcantent,2,",",".");
				$li_totmovsal=number_format($li_totmovsal,2,",",".");
				$li_totcansal=number_format($li_totcansal,2,",",".");
				$li_totmovrev=number_format($li_totmovrev,2,",",".");
				$li_totcanrev=number_format($li_totcanrev,2,",",".");

				$la_data3[0] = array('name1'=>'<b>RESUMEN MOVIMIENTOS</b>','name2'=>'','name3'=>'','name4'=>'');
				$la_data3[1] = array('name1'=>'Total Movimientos de Entrada ','name2'=>$li_totmovent,'name3'=>'Total Entradas (Cantidad)','name4'=>$li_totcantent);
				$la_data3[2] = array('name1'=>'Total Movientos de Salida ','name2'=>$li_totmovsal,'name3'=>'Total Salidas (Cantidad)','name4'=>$li_totcansal);
				$la_data3[3] = array('name1'=>'Total Movimientos de Reverso ','name2'=>$li_totmovrev,'name3'=>'Total Reversos (Cantidad)','name4'=>$li_totcanrev);
				$la_data3[4] = array('name1'=>' <b>- TOTAL EXISTENCIA (Cantidad) -</b> ','name2'=>$li_existenciapan,'name3'=>'','name4'=>'');
				uf_print_totales($la_data3,$io_pdf); // Imprimimos el detalle
				//uf_print_pie_cabecera($li_totent,$li_totsal,$io_pdf); // Imprimimos pie de la cabecera
				if ($io_pdf->ezPageCount==$li_numpag)
				{// Hacemos el commit de los registros que se desean imprimir
					$io_pdf->transaction('commit');
				}
				else
				{// Hacemos un rollback de los registros, agregamos una nueva p�gina y volvemos a imprimir
					$io_pdf->transaction('rewind');
					if(($li_numpag>1)||($li_i!=1))
					{
						$io_pdf->ezNewPage(); // Insertar una nueva p�gina
					}
					uf_print_cabecera($ls_nomemp,$ls_codart,$ls_denart,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
					$la_data3[0] = array('name1'=>'<b>RESUMEN MOVIMIENTOS</b>','name2'=>'','name3'=>'','name4'=>'');
					$la_data3[1] = array('name1'=>'Total Movimientos de Entrada ','name2'=>$li_totmovent,'name3'=>'Total Entradas (Cantidad)','name4'=>$li_totcantent);
					$la_data3[2] = array('name1'=>'Total Movientos de Salida ','name2'=>$li_totmovsal,'name3'=>'Total Salidas (Cantidad)','name4'=>$li_totcansal);
					$la_data3[3] = array('name1'=>'Total Movimientos de Reverso ','name2'=>$li_totmovrev,'name3'=>'Total Reversos (Cantidad)','name4'=>$li_totcanrev);
					$la_data3[4] = array('name1'=>'<b> - TOTAL EXISTENCIA (Cantidad) -</b> ','name2'=>$li_existenciapan,'name3'=>'','name4'=>'');
					uf_print_totales($la_data3,$io_pdf); // Imprimimos el detalle
				}
				$li_totmovent=0;
				$li_totcantent=0;
				$li_totmovsal=0;
				$li_totcansal=0;
				$li_totmovrev=0;
				$li_totcanrev=0;
				$li_existencia=0;
				$li_existenciapan=0;
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