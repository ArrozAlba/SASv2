<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Reporte de Relacion de Facturas
//  ORGANISMO: Ninguno en particular
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";		
		print "</script>";		
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_relacionfacturas.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 11/03/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(15,40,775,40);
        $io_pdf->Rectangle(15,530,753,60);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,535,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,570,11,$as_titulo); // Agregar el título
		$io_pdf->addText(740,598,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(746,591,6,date("h:i a")); // Agregar la Hora
		// cuadro inferior
        $io_pdf->Rectangle(15,60,753,70);
		$io_pdf->line(15,73,768,73);		
		$io_pdf->line(15,117,768,117);		
		$io_pdf->line(203,60,203,130);		
		$io_pdf->line(391,60,391,130);		
		$io_pdf->line(579,60,579,130);		
		$io_pdf->addText(80,122,7,"ELABORADO POR"); // Agregar el título
		$io_pdf->addText(82,63,7,"FIRMA / SELLO"); // Agregar el título
		$io_pdf->addText(262,122,7,"VERIFICADO POR"); // Agregar el título
		$io_pdf->addText(252,63,7,"FIRMA / SELLO / FECHA"); // Agregar el título
		$io_pdf->addText(460,122,7,"AUTORIZADO POR"); // Agregar el título
		$io_pdf->addText(440,63,7,"ADMINISTRACIÓN Y FINANZAS"); // Agregar el título
		$io_pdf->addText(635,122,7,"CONTRALORIA INTERNA"); // Agregar el título
		$io_pdf->addText(635,63,7,"FIRMA / SELLO / FECHA"); // Agregar el título
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codigo,$as_nombre,$as_tipproben,$io_encabezado,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codigo    // Codigo de Proveedor / Beneficiario
		//	   			   as_nombre    // Nombre de Proveedor / Beneficiario
		//	   			   as_tipproben // Tipo de Proveedor / Beneficiario
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 03/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSetY(520);
		if($as_tipproben=="B")
		{
			$la_data[1]=array('titulo'=>'<b> Beneficiario:          </b>'.$as_codigo.' - '.$as_nombre);
		}
		else
		{
			$la_data[1]=array('titulo'=>'<b> Proveedor:          </b>'.$as_codigo.' - '.$as_nombre);
		}
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>750))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);

		$la_datatit[1]=array('numrecdoc'=>'<b>Documento</b>','dencondoc'=>'<b>Concepto</b>','fecemidoc'=>'<b>Fecha Emision</b>',
							 'fecregdoc'=>'<b>Fecha Registro</b>','montotfac'=>'<b>	Total Factura</b>',
							 'mondeddoc'=>'<b>Deducciones</b>','montotdoc'=>'<b>Neto a Pagar</b>',
							 'numsol'=>'<b>Solicitud de Pago</b>');
		$la_columnas=array('numrecdoc'=>'','dencondoc'=>'','fecemidoc'=>'','fecregdoc'=>'','montotfac'=>'','mondeddoc'=>'',
						   'montotdoc'=>'','numsol'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numrecdoc'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'dencondoc'=>array('justification'=>'center','width'=>204), // Justificación y ancho de la columna
						 			   'fecemidoc'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
						 			   'fecregdoc'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
						 			   'montotfac'=>array('justification'=>'right','width'=>82), // Justificación y ancho de la columna
						 			   'mondeddoc'=>array('justification'=>'right','width'=>82), // Justificación y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'right','width'=>82), // Justificación y ancho de la columna
						 			   'numsol'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);

		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_recepcion($la_data,$ai_j,$ai_totalfacpro,$ai_totaldedpro,$ai_totaldocpro,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				   ai_j // numero de registros
		//				   ai_totalfacpro // acumulado de los montos
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle de las recepciones de documentos
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 04/07/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	$io_pdf->ezSetY(400);

		$la_columnas=array('numrecdoc'=>'','dencondoc'=>'','fecemidoc'=>'','fecregdoc'=>'','montotfac'=>'','mondeddoc'=>'',
						   'montotdoc'=>'','numsol'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numrecdoc'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
						 			   'dencondoc'=>array('justification'=>'left','width'=>204), // Justificación y ancho de la columna
						 			   'fecemidoc'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
						 			   'fecregdoc'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
						 			   'montotfac'=>array('justification'=>'right','width'=>82), // Justificación y ancho de la columna
						 			   'mondeddoc'=>array('justification'=>'right','width'=>82), // Justificación y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'right','width'=>82), // Justificación y ancho de la columna
						 			   'numsol'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$la_datatot[1]=array('numrecdoc'=>'<b>Totales</b>','mondoc'=>$ai_totalfacpro,'montotfac'=>$ai_totaldedpro,'mondeddoc'=>$ai_totaldocpro,'montotdoc'=>'');
		$la_columnas=array('numrecdoc'=>'','mondoc'=>'',
						   'montotfac'=>'','mondeddoc'=>'','montotdoc'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numrecdoc'=>array('justification'=>'right','width'=>414), // Justificación y ancho de la columna
						 			   'mondoc'=>array('justification'=>'right','width'=>82), // Justificación y ancho de la columna
						 			   'montotfac'=>array('justification'=>'right','width'=>82), // Justificación y ancho de la columna
						 			   'mondeddoc'=>array('justification'=>'right','width'=>82), // Justificación y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatot,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_cxp_class_report.php");
	$io_report=new sigesp_cxp_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	//Instancio a la clase de conversión de numeros a letras.
	include("../../shared/class_folder/class_numero_a_letra.php");
	$numalet= new class_numero_a_letra();
	//imprime numero con los valore por defecto
	//cambia a minusculas
	$numalet->setMayusculas(1);
	//cambia a femenino
	$numalet->setGenero(1);
	//cambia moneda
	$numalet->setMoneda("Bolivares");
	//cambia prefijo
	$numalet->setPrefijo("***");
	//cambia sufijo
	$numalet->setSufijo("***");
		
	if($ls_estmodest==1)
	{
		$ls_titcuentas="Estructura Presupuestaria";
	}
	else
	{
		$ls_titcuentas="Estructura Programatica";
	}
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>RELACION DE FACTURAS</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_tipproben=$io_fun_cxp->uf_obtenervalor_get("tipproben","");
	$ls_codprobendes=$io_fun_cxp->uf_obtenervalor_get("codprobendes","");
	$ls_codprobenhas=$io_fun_cxp->uf_obtenervalor_get("codprobenhas","");
	$ld_fecregdes=$io_fun_cxp->uf_obtenervalor_get("fecregdes","");
	$ld_fecreghas=$io_fun_cxp->uf_obtenervalor_get("fecreghas","");
	$li_ordendoc=$io_fun_cxp->uf_obtenervalor_get("ordendoc","");
	$li_ordenfec=$io_fun_cxp->uf_obtenervalor_get("ordenfec",0);
	$li_ordencod=$io_fun_cxp->uf_obtenervalor_get("ordencod",0);
	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_cxp_class_reportbsf.php");
		$io_report=new sigesp_cxp_class_reportbsf();
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_select_probenrelacionfacturas($ls_tipproben,$ls_codprobendes,$ls_codprobenhas,
																$ld_fecregdes,$ld_fecreghas); // Cargar el DS con los datos del reporte
		if($lb_valido==false) // Existe algún error ó no hay registros
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}
		else  // Imprimimos el reporte
		{
			error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(4.65,4.7,3,3); // Configuración de los margenes en centímetros
			$io_pdf->ezStartPageNumbers(770,47,8,'','',1); // Insertar el número de página
			$li_totrow=$io_report->DS->getRowCount("codigo");
			$li_totaldoc=0;
			$li_totalcar=0;
			$li_totalded=0;
			uf_print_encabezado_pagina($ls_titulo,&$io_pdf);
			for($li_i=1;($li_i<=$li_totrow)&&($lb_valido);$li_i++)
			{
				$io_encabezado=$io_pdf->openObject();
				$li_numpag=$io_pdf->ezPageCount; // Número de página
				$ls_codigo=$io_report->DS->data["codigo"][$li_i];
				$ls_nombre=$io_report->DS->data["nombre"][$li_i];
				$ls_tipproben=$io_report->DS->data["tipproben"][$li_i];
				uf_print_cabecera($ls_codigo,$ls_nombre,$ls_tipproben,$io_encabezado,&$io_pdf);
				$lb_valido=$io_report->uf_select_facturasproben($ls_tipproben,$ls_codigo,$ld_fecregdes,$ld_fecreghas,$li_ordendoc,
																$li_ordenfec);
				if($lb_valido)
				{
					$li_totrowfac=$io_report->ds_detrecdoc->getRowCount("numrecdoc");
					$li_totalfacpro=0;
					$li_totaldedpro=0;
					$li_totaldocpro=0;
					for($li_j=1;$li_j<=$li_totrowfac;$li_j++)
					{
						$ls_numrecdoc=$io_report->ds_detrecdoc->data["numrecdoc"][$li_j];
						$ld_fecregdoc=$io_report->ds_detrecdoc->data["fecregdoc"][$li_j];
						$ld_fecemidoc=$io_report->ds_detrecdoc->data["fecemidoc"][$li_j];
						$ls_dencondoc=$io_report->ds_detrecdoc->data["dencondoc"][$li_j];
						$li_montotdoc=$io_report->ds_detrecdoc->data["montotdoc"][$li_j];
						$li_moncardoc=$io_report->ds_detrecdoc->data["moncardoc"][$li_j];
						$li_mondeddoc=$io_report->ds_detrecdoc->data["mondeddoc"][$li_j];
						$li_montotfac=$li_montotdoc+$li_mondeddoc;
						$ls_numsol=$io_report->ds_detrecdoc->data["numsol"][$li_j];
						$ld_fecregdoc=$io_funciones->uf_convertirfecmostrar($ld_fecregdoc);
						$ld_fecemidoc=$io_funciones->uf_convertirfecmostrar($ld_fecemidoc);
						$li_totalfacpro=$li_totalfacpro + $li_montotfac;
						$li_totaldedpro=$li_totaldedpro + $li_mondeddoc;
						$li_totaldocpro=$li_totaldocpro + $li_montotdoc;
						$li_montotdoc=number_format($li_montotdoc,2,',','.');
						$li_montotfac=number_format($li_montotfac,2,',','.');
						$li_mondeddoc=number_format($li_mondeddoc,2,',','.');
						$la_data[$li_j]=array('numrecdoc'=>$ls_numrecdoc,'dencondoc'=>$ls_dencondoc,'fecemidoc'=>$ld_fecemidoc,
											  'fecregdoc'=>$ld_fecregdoc,'montotfac'=>$li_montotfac,'mondeddoc'=>$li_mondeddoc,
											  'montotdoc'=>$li_montotdoc,'numsol'=>$ls_numsol);
					}
					$li_totalfacpro=number_format($li_totalfacpro,2,',','.');
					$li_totaldedpro=number_format($li_totaldedpro,2,',','.');
					$li_totaldocpro=number_format($li_totaldocpro,2,',','.');
					uf_print_detalle_recepcion($la_data,$li_totrowfac,$li_totalfacpro,$li_totaldedpro,$li_totaldocpro,&$io_pdf);
				}
				if($li_i<$li_totrow)
				{
					$io_pdf->StopObject($io_encabezado);
					$io_pdf->ezNewPage(); // Insertar una nueva página
				}
/*				if ($io_pdf->ezPageCount==$li_numpag)
				{// Hacemos el commit de los registros que se desean imprimir
					$io_pdf->transaction('commit');
				}
				else
				{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
					$io_pdf->transaction('rewind');
					$io_pdf->ezNewPage(); // Insertar una nueva página
					uf_print_cabecera($ls_codigo,$ls_nombre,$ls_tipproben,&$io_pdf);
					uf_print_detalle_recepcion($la_data,$li_totrowfac,$li_totalfacpro,&$io_pdf);
				}*/
				unset($la_data);			
			}
			if($lb_valido) // Si no ocurrio ningún error
			{
				$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
				$io_pdf->ezStream(); // Mostramos el reporte
			}
			else // Si hubo algún error
			{
				print("<script language=JavaScript>");
				print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
				print(" close();");
				print("</script>");		
			}
			unset($io_pdf);
		}
	}
	unset($io_report);
	unset($io_funciones);
?>
