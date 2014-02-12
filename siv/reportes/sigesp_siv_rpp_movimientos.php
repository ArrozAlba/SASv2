<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//  ESTE FORMATO SE IMPRIME EN Bs Y EN BsF. SEGUN LO SELECCIONADO POR EL USUARIO
	//  MODIFICADO POR: ING.YOZELIN BARRAGAN         FECHA DE MODIFICACION : 01/09/2007
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
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_fecha // periodo de fecha
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->line(20,40,730,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],55.5,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,550,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_fecha);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,535,11,$as_fecha); // Agregar la fecha
		$io_pdf->addText(685,550,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(691,543,7,date("h:i a")); // Agregar la Hora
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
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$as_nomfisalm=substr($as_nomfisalm,0,35);
		//$as_denpro=substr($as_denpro,0,25);
		$la_data=array(array('name'=>'<b>Empresa</b>  '.$as_nomemp.''),
					   array ('name'=>'<b>Artículo</b>  '.$as_codart.' '.$as_denart));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar Líneas
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>670, // Ancho de la tabla
						 'maxWidth'=>670); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		global $ls_tipoformato;
		if($ls_tipoformato==0)
		{
		  $ls_titulo="Costo Bs.";
		}
		elseif($ls_tipoformato==1)
		{
		  $ls_titulo="Costo Bs.F.";
		}
		$la_columna=array('fecha'=>'<b>Fecha</b>',
						  'operacion'=>'<b>Operación</b>',
						  'documento'=>'<b>Documento</b>',
						  'almacen'=>'<b>Almacén</b>',
						  'cantidad'=>'<b>Cantidad</b>',
						  'costo'=>'<b>'.$ls_titulo.'</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>670, // Ancho de la tabla
						 'maxWidth'=>670, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('fecha'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
						 			   'operacion'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'documento'=>array('justification'=>'left','width'=>110), // Justificación y ancho de la columna
						 			   'almacen'=>array('justification'=>'left','width'=>160), // Justificación y ancho de la columna
						 			   'cantidad'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'costo'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('total'=>'',
						  'totent'=>'',
						  'totsal'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>670, // Ancho de la tabla
						 'maxWidth'=>670, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>430), // Justificación y ancho de la columna
						 			   'totent'=>array('justification'=>'right','width'=>120), // Justificación y ancho de la columna
						 			   'totsal'=>array('justification'=>'right','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
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
		//    Description: función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//print "Entradas".$ai_totent."Salidas".$ai_totsal."<br>";
		$la_data=array(array('name'=>'____________________________________________________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>660); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$la_data=array(array('total'=>'<b>Totales:        Entradas  </b>'.$ai_totent.' '.'<b>Salidas  </b>'.$ai_totsal.''));
		$la_columna=array('total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>500), // Justificación y ancho de la columna
						 			   'entradas'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'salidas'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_funciones_inventario.php");
	$io_fun_inventario=new class_funciones_inventario();
	$ls_tipoformato=$io_fun_inventario->uf_obtenervalor_get("tipoformato",0);
	global $ls_tipoformato;
	if($ls_tipoformato==1)
	{
		require_once("sigesp_siv_class_reportbsf.php");
		$io_report=new sigesp_siv_class_reportbsf();
		$ls_titulo_report="Bs.F.";
	}
	else
	{
		require_once("sigesp_siv_class_report.php");
		$io_report=new sigesp_siv_class_report();
		$ls_titulo_report="Bs.";
	}	
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ld_desde=$io_fun_inventario->uf_obtenervalor_get("desde","");
	$ld_hasta=$io_fun_inventario->uf_obtenervalor_get("hasta","");

	$ls_titulo="Movimientos de Artículos";
	if($ld_desde!="")
	{$ls_fecha="Periodo ".$ld_desde." - ".$ld_hasta;}
	else
	{$ls_fecha="";}
	
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
	$ls_codart=$io_fun_inventario->uf_obtenervalor_get("codart","");
	$ls_codalm=$io_fun_inventario->uf_obtenervalor_get("codalm","");
	$li_ordenart=$io_fun_inventario->uf_obtenervalor_get("ordenart","");
	$li_ordenfec=$io_fun_inventario->uf_obtenervalor_get("ordenfec","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_select_articulos($ls_codemp,$ls_codalm,$ls_codart,$ld_desde,$ld_hasta,$li_total,$li_ordenart); // Cargar el DS con los datos de la cabecera del reporte
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		/////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_desc_event="Generó el reporte de Movimientos de Artículos, del Articulo ".$ls_codart." en el almacen  ".$ls_codalm." Periodo de fechas ".$ld_desde." - ".$ld_hasta;
		$io_fun_inventario->uf_load_seguridad_reporte("SIV","sigesp_siv_r_movimientos.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////////////////////////////////////////////////////////////
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(700,50,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->ds->getRowCount("codart");
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
		    $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$li_totent=0;
			$li_totsal=0;
			$ls_codart=  $io_report->ds->data["codart"][$li_i];
			$ls_denart=  $io_report->ds->data["denart"][$li_i];
			uf_print_cabecera($ls_nomemp,$ls_codart,$ls_denart,$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_select_movimientosxarticulos($ls_codemp,$ls_codalm,$ls_codart,$ld_desde,$ld_hasta,
																   $li_total,$li_ordenart,$li_ordenfec); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->ds_detalle->getRowCount("nummov");
				$li_totent=0;
				$li_totent=0;
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ld_fecmov=     $io_report->ds_detalle->data["fecmov"][$li_s];
					$ls_opeinv=     $io_report->ds_detalle->data["opeinv"][$li_s]; 
					$ls_codprodoc=  $io_report->ds_detalle->data["codprodoc"][$li_s];  
					$ls_numdoc=     $io_report->ds_detalle->data["numdoc"][$li_s];
					$ls_nomfisalm=  $io_report->ds_detalle->data["nomfisalm"][$li_s]; 
					$li_canart=     $io_report->ds_detalle->data["canart"][$li_s]; 
					$li_cosart=     $io_report->ds_detalle->data["cosart"][$li_s];
					$ld_fecmov=     $io_funciones->uf_convertirfecmostrar($ld_fecmov);
					if(($ls_opeinv=="ENT")&&($ls_codprodoc=="FAC"))
					{
						$ls_opeinv="Entrada de Inventario por Factura";
						//$li_totent=$li_totent + 1;
						$li_totent=$li_totent+$li_canart;
					}
					if(($ls_opeinv=="ENT")&&($ls_codprodoc=="AJE"))
					{
						$ls_opeinv="Entrada de Inventario por Ajuste";
						//$li_totent=$li_totent + 1;
						$li_totent=$li_totent+$li_canart;
					}
					if(($ls_opeinv=="ENT")&&($ls_codprodoc=="ORD"))
					{
						$ls_opeinv="Entrada de Inventario por Orden de Compra";
						//$li_totent=$li_totent + 1;
						$li_totent=$li_totent+$li_canart; 
					}
					if(($ls_opeinv=="SAL")&&($ls_codprodoc=="SEP"))
					{
						$ls_opeinv="Salida de Inventario por Despacho";
						//$li_totsal=$li_totsal + 1;
						$li_totsal=$li_totsal+$li_canart;
					}
					if(($ls_opeinv=="ENT")&&($ls_codprodoc=="REV"))
					{
						$ls_opeinv="Reverso de Inventario";
						//$li_totsal=$li_totsal + 1;
						$li_totsal=$li_totsal+$li_canart;
					}
					if(($ls_opeinv=="SAL")&&($ls_codprodoc=="REV"))
					{
						$ls_opeinv="Reverso de Inventario";
						//$li_totsal=$li_totsal + 1;
						$li_totsal=$li_totsal+$li_canart;
					}

					$li_cosart=number_format($li_cosart,2,",",".");
					$li_canart=number_format($li_canart,2,",",".");
					$la_data[$li_s]=array('fecha'=>$ld_fecmov,'operacion'=>$ls_opeinv,'documento'=>$ls_numdoc,'almacen'=>$ls_nomfisalm,'cantidad'=>$li_canart,'costo'=>$li_cosart);
				    $ls_opeinv="";
				}
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				$la_data1[1]=array('total'=>'<b>Total</b>','totent'=>'<b>Entradas </b>'.$li_totent,'totsal'=>'<b>Salidas </b>'.$li_totsal);
				uf_print_totales($la_data1,$io_pdf); // Imprimimos el detalle 
				//uf_print_pie_cabecera($li_totent,$li_totsal,$io_pdf); // Imprimimos pie de la cabecera
				if ($io_pdf->ezPageCount==$li_numpag)
				{// Hacemos el commit de los registros que se desean imprimir
					$io_pdf->transaction('commit');
				}
				else
				{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
					$io_pdf->transaction('rewind');
					if(($li_numpag>1)||($li_i!=1))
					{
						$io_pdf->ezNewPage(); // Insertar una nueva página
					}
					uf_print_cabecera($ls_nomemp,$ls_codart,$ls_denart,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					$la_data1[1]=array('total'=>'<b>Total</b>','totent'=>'<b>Entradas </b>'.$li_totent,'totsal'=>'<b>Salidas </b>'.$li_totsal);
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