<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Reporte de Recepciones de Documentos
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
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_recepciones.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_tipproben,$as_codprobendes,$as_codprobenhas,$as_nomprobendes,$as_nomprobenhas,&$io_pdf)
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
        $io_pdf->Rectangle(10,530,762,60);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,535,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,570,11,$as_titulo); // Agregar el título
		$io_pdf->addText(730,598,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(736,591,6,date("h:i a")); // Agregar la Hora
		if(($as_codprobendes!="")&&($as_codprobendes!=""))
		{
			switch($as_tipproben)
			{
				case"P":
					if($as_codprobendes==$as_codprobenhas)
					{
						$ls_criterio="Proveedor: ".$as_codprobendes." - <b>".$as_nomprobendes."</b>";
						$li_tm=$io_pdf->getTextWidth(8,$ls_criterio);
						$tm=396-($li_tm/2);
						$io_pdf->addText($tm,540,8,$ls_criterio); // Agregar el título
					
					}
					else
					{
						$ls_criterio="Proveedores: ";
						$li_tm=$io_pdf->getTextWidth(8,$ls_criterio);
						$tm=396-($li_tm/2);
						$io_pdf->addText($tm,555,8,$ls_criterio); // Agregar el título
						$ls_criterio="Desde: ".$as_codprobendes." - <b>".$as_nomprobendes."</b>";
						$li_tm=$io_pdf->getTextWidth(8,$ls_criterio);
						$tm=396-($li_tm/2);
						$io_pdf->addText($tm,545,8,$ls_criterio); // Agregar el título
						$ls_criterio="Hasta: ".$as_codprobenhas." - <b>".$as_nomprobenhas."</b>";
						$li_tm=$io_pdf->getTextWidth(8,$ls_criterio);
						$tm=396-($li_tm/2);
						$io_pdf->addText($tm,535,8,$ls_criterio); // Agregar el título
					}
				break;
				case"B":
					if($as_codprobendes==$as_codprobenhas)
					{
						$ls_criterio="Beneficiario: ".$as_codprobendes." - <b>".$as_nomprobendes."</b>";
						$li_tm=$io_pdf->getTextWidth(8,$ls_criterio);
						$tm=396-($li_tm/2);
						$io_pdf->addText($tm,540,8,$ls_criterio); // Agregar el título
					
					}
					else
					{
						$ls_criterio="Beneficiarios: ";
						$li_tm=$io_pdf->getTextWidth(8,$ls_criterio);
						$tm=396-($li_tm/2);
						$io_pdf->addText($tm,555,8,$ls_criterio); // Agregar el título
						$ls_criterio="Desde: ".$as_codprobendes." - <b>".$as_nomprobendes."</b>";
						$li_tm=$io_pdf->getTextWidth(8,$ls_criterio);
						$tm=396-($li_tm/2);
						$io_pdf->addText($tm,545,8,$ls_criterio); // Agregar el título
						$ls_criterio="Hasta: ".$as_codprobenhas." - <b>".$as_nomprobenhas."</b>";
						$li_tm=$io_pdf->getTextWidth(8,$ls_criterio);
						$tm=396-($li_tm/2);
						$io_pdf->addText($tm,535,8,$ls_criterio); // Agregar el título
					}
				break;
			}
		}
		// cuadro inferior
        $io_pdf->Rectangle(10,60,762,70);
		$io_pdf->line(10,73,772,73);		
		$io_pdf->line(10,117,772,117);		
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
	function uf_print_detalle_recepcion($la_data,$li_totaldoc,$li_totalcar,$li_totalded,$li_totbasimp,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				   li_totaldoc // acumulado del total
		//				   li_totalcar // acumulado de los cargos
		//				   li_totalded // acumulado de las deducciones
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle de las recepciones de documentos
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetY(520);
		$la_datatit[1]=array('numrecdoc'=>'<b>Documento</b>',
							 'nombre'=>'<b>Proveedor / Beneficiario</b>',
							 'fecemidoc'=>'<b>Fecha Emision</b>',
							 'fecregdoc'=>'<b>Fecha Registro</b>',
							 'procede_doc'=>'<b>Procedencia</b>',
							 'numdoccom'=>'<b>Compromiso</b>',
							 'basimp'=>'<b>Base Imponible</b>',
							 'mondeddoc'=>'<b>Deducciones</b>',
							 'moncardoc'=>'<b>Cargos</b>',
							 'montotdoc'=>'<b>Monto Total Factura</b>');
		$la_columnas=array('numrecdoc'=>'<b>Documento</b>',
						   'nombre'=>'<b>Proveedor / Beneficiario</b>',
						   'fecemidoc'=>'<b>Fecha Emision</b>',
						   'fecregdoc'=>'<b>Fecha Registro</b>',
						   'procede_doc'=>'<b>Procedencia</b>',
						   'numdoccom'=>'<b>Compromiso</b>',
						   'basimp'=>'<b>Base Imponible</b>',
						   'mondeddoc'=>'<b>Deducciones</b>',
						   'moncardoc'=>'<b>Cargos</b>',
						   'montotdoc'=>'<b>Monto Total Factura</b>');
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
						 'cols'=>array('numrecdoc'=>array('justification'=>'center','width'=>75), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'fecemidoc'=>array('justification'=>'center','width'=>56), // Justificación y ancho de la columna
						 			   'fecregdoc'=>array('justification'=>'center','width'=>56), // Justificación y ancho de la columna
									   'procede_doc'=>array('justification'=>'center','width'=>64),// Justificación y ancho de la columna
									   'numdoccom'=>array('justification'=>'center','width'=>88), // Justificación y ancho de la columna
						 			   'basimp'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'mondeddoc'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'moncardoc'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'center','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');

		$la_columnas=array('numrecdoc'=>'<b>Documento</b>',
						   'nombre'=>'<b>Proveedor / Beneficiario</b>',
						   'fecemidoc'=>'<b>Fecha Emision</b>',
						   'fecregdoc'=>'<b>Fecha Registro</b>',
						   'procede_doc'=>'<b>Procedencia</b>',
						   'numdoccom'=>'<b>Compromiso</b>',
						   'basimp'=>'<b>Base Imponible</b>',
						   'mondeddoc'=>'<b>Deducciones</b>',
						   'moncardoc'=>'<b>Cargos</b>',
						   'montotdoc'=>'<b>Monto Total Factura</b>');
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
						 'cols'=>array('numrecdoc'=>array('justification'=>'left','width'=>75), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la columna
						 			   'fecemidoc'=>array('justification'=>'center','width'=>56), // Justificación y ancho de la columna
						 			   'fecregdoc'=>array('justification'=>'center','width'=>56), // Justificación y ancho de la columna
									   'procede_doc'=>array('justification'=>'center','width'=>64),// Justificación y ancho de la columna
									   'numdoccom'=>array('justification'=>'left','width'=>88), // Justificación y ancho de la columna
						 			   'basimp'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'mondeddoc'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'moncardoc'=>array('justification'=>'right','width'=>65), // Justificación y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$la_datatot[1]=array('numrecdoc'=>'<b>Totales Bs.</b>','basimp'=>$li_totbasimp,'mondeddoc'=>$li_totalded,'moncardoc'=>$li_totalcar,'montotdoc'=>$li_totaldoc);
		$la_columnas=array('numrecdoc'=>'<b>Factura</b>',
						   'basimp'=>'<b>Monto</b>',
						   'mondeddoc'=>'<b>Cargos</b>',
						   'moncardoc'=>'<b>Total</b>',
						   'montotdoc'=>'<b>Monto</b>');
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
						 'cols'=>array('numrecdoc'=>array('justification'=>'right','width'=>489), // Justificación y ancho de la columna
						 			   'basimp'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'mondeddoc'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'moncardoc'=>array('justification'=>'right','width'=>65), // Justificación y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
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
	//Instancio a la clase de conversión de numeros a letras.
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>RECEPCIONES DE DOCUMENTOS</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_tipproben=$io_fun_cxp->uf_obtenervalor_get("tipproben","");
	$ls_codprobendes=trim($io_fun_cxp->uf_obtenervalor_get("codprobendes",""));
	$ls_codprobenhas=trim($io_fun_cxp->uf_obtenervalor_get("codprobenhas",""));
	$ld_fecregdes=$io_fun_cxp->uf_obtenervalor_get("fecregdes","");
	$ld_fecreghas=$io_fun_cxp->uf_obtenervalor_get("fecreghas","");
	$ls_codtipdoc=$io_fun_cxp->uf_obtenervalor_get("codtipdoc","");
	$ls_registrada=$io_fun_cxp->uf_obtenervalor_get("registrada","");
	$ls_anulada=$io_fun_cxp->uf_obtenervalor_get("anulada","");
	$ls_procesada=$io_fun_cxp->uf_obtenervalor_get("procesada","");
	$ls_orden=$io_fun_cxp->uf_obtenervalor_get("orden","");
	$ls_nomprobendes="";
	$ls_nomprobenhas="";
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_select_recepciones($ls_tipproben,$ls_codprobendes,$ls_codprobenhas,$ld_fecregdes,$ld_fecreghas,
													 $ls_codtipdoc,$ls_registrada,$ls_anulada,$ls_procesada,$ls_orden); // Cargar el DS con los datos del reporte
		if($lb_valido==false) // Existe algún error ó no hay registros
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			//print(" close();");
			print("</script>");
		}
		else  // Imprimimos el reporte
		{
			error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(4.1,5,3,3); // Configuración de los margenes en centímetros
			$io_pdf->ezStartPageNumbers(770,47,8,'','',1); // Insertar el número de página
			$li_totrow=$io_report->DS->getRowCount("numrecdoc");
			if($ls_codprobendes!="")
				$ls_nomprobendes=$io_report->uf_select_proveedores($ls_tipproben,$ls_codprobendes);
			if($ls_codprobenhas!="")
				$ls_nomprobenhas=$io_report->uf_select_proveedores($ls_tipproben,$ls_codprobenhas);
			$li_totaldoc=0;
			$li_totalcar=0;
			$li_totalded=0;
			$li_totbasimp=0;
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_numrecdoc= $io_report->DS->data["numrecdoc"][$li_i];
				$ls_nombre= $io_report->DS->data["nombre"][$li_i]; 
				$ls_procede= $io_report->DS->data["procede_doc"][$li_i];
				if($ls_procede=="")
				{
					$ls_procede=$io_report->DS->data["procede_cont"][$li_i];
				}
				$ls_numdoccom= $io_report->DS->data["numdoccom"][$li_i];
				if($ls_numdoccom=="")
				{
					$ls_numdoccom=$io_report->DS->data["numdoccont"][$li_i];
				}
				$ld_fecemidoc= $io_report->DS->data["fecemidoc"][$li_i];
				$ld_fecregdoc= $io_report->DS->data["fecregdoc"][$li_i];
				$li_montotdoc= $io_report->DS->data["montotdoc"][$li_i];
				$li_mondeddoc= $io_report->DS->data["mondeddoc"][$li_i];
				$li_moncardoc= $io_report->DS->data["moncardoc"][$li_i];
				$ld_fecemidoc= $io_funciones->uf_convertirfecmostrar($ld_fecemidoc);
				$ld_fecregdoc= $io_funciones->uf_convertirfecmostrar($ld_fecregdoc);
				$li_basimp=$li_montotdoc+$li_mondeddoc-$li_moncardoc;
				$li_totaldoc= $li_totaldoc + $li_montotdoc;
				$li_totalcar= $li_totalcar + $li_moncardoc;
				$li_totalded= $li_totalded + $li_mondeddoc;
				$li_totbasimp= $li_totbasimp + $li_basimp;
				$li_montotdoc= number_format($li_montotdoc,2,',','.');
				$li_mondeddoc= number_format($li_mondeddoc,2,',','.');
				$li_moncardoc= number_format($li_moncardoc,2,',','.');
				$li_basimp= number_format($li_basimp,2,',','.');
				$la_data[$li_i]=array('numrecdoc'=>$ls_numrecdoc,'nombre'=>$ls_nombre,'fecemidoc'=>$ld_fecemidoc,'fecregdoc'=>$ld_fecregdoc,
									  'procede_doc'=>$ls_procede,'numdoccom'=>$ls_numdoccom,'basimp'=>$li_basimp,
									  'mondeddoc'=>$li_mondeddoc,'moncardoc'=>$li_moncardoc,'montotdoc'=>$li_montotdoc);
			}
			$li_totbasimp= number_format($li_totbasimp,2,',','.');
			$li_totaldoc= number_format($li_totaldoc,2,',','.');
			$li_totalcar= number_format($li_totalcar,2,',','.');
			$li_totalded= number_format($li_totalded,2,',','.');
			uf_print_encabezado_pagina($ls_titulo,$ls_tipproben,$ls_codprobendes,$ls_codprobenhas,$ls_nomprobendes,$ls_nomprobenhas,&$io_pdf);
			uf_print_detalle_recepcion($la_data,$li_totaldoc,$li_totalcar,$li_totalded,$li_totbasimp,&$io_pdf);
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
		}
	}

?>
