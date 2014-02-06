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
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_relacionsaldos.php",$ls_descripcion);
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
		$io_pdf->line(15,40,585,40);
        $io_pdf->Rectangle(15,700,570,60);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$io_pdf->addText(540,770,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(546,764,6,date("h:i a")); // Agregar la Hora
		// cuadro inferior
        $io_pdf->Rectangle(15,60,570,70);
		$io_pdf->line(15,73,585,73);		
		$io_pdf->line(15,117,585,117);		
		$io_pdf->line(130,60,130,130);		
		$io_pdf->line(240,60,240,130);		
		$io_pdf->line(380,60,380,130);		
		$io_pdf->addText(40,122,7,"ELABORADO POR"); // Agregar el título
		$io_pdf->addText(42,63,7,"FIRMA / SELLO"); // Agregar el título
		$io_pdf->addText(157,122,7,"VERIFICADO POR"); // Agregar el título
		$io_pdf->addText(145,63,7,"FIRMA / SELLO / FECHA"); // Agregar el título
		$io_pdf->addText(275,122,7,"AUTORIZADO POR"); // Agregar el título
		$io_pdf->addText(257,63,7,"ADMINISTRACIÓN Y FINANZAS"); // Agregar el título
		$io_pdf->addText(440,122,7,"CONTRALORIA INTERNA"); // Agregar el título
		$io_pdf->addText(445,63,7,"FIRMA / SELLO / FECHA"); // Agregar el título
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codigo,$as_nombre,$as_tipproben,&$io_pdf)
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
		$io_pdf->ezSetDy(-2);
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
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
			
	function uf_print_detalle_solicitudes($la_data,&$io_pdf)
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
		$io_pdf->ezSetDy(-2);
		$la_datatit[1]=array('numsol'=>'<b>Solicitud</b>','fecemisol'=>'<b>Fecha Emision</b>','consol'=>'<b>Concepto</b>',
							 'monsol'=>'<b>Monto</b>','monto'=>'<b>Saldo</b>');
		$la_columnas=array('numsol'=>'<b>Factura</b>',
						   'fecemisol'=>'<b>Fecha</b>',
						   'consol'=>'<b>Monto</b>',
						   'monsol'=>'<b>Cargos</b>',
						   'monto'=>'<b>Cargos</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numsol'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'fecemisol'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
						 			   'consol'=>array('justification'=>'center','width'=>255), // Justificación y ancho de la columna
						 			   'monsol'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);

		$la_columnas=array('numsol'=>'<b>Factura</b>',
						   'fecemisol'=>'<b>Fecha</b>',
						   'consol'=>'<b>Monto</b>',
						   'monsol'=>'<b>Cargos</b>',
						   'monto'=>'<b>Cargos</b>');
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
						 'cols'=>array('numsol'=>array('justification'=>'left','width'=>90), // Justificación y ancho de la columna
						 			   'fecemisol'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
						 			   'consol'=>array('justification'=>'left','width'=>255), // Justificación y ancho de la columna
						 			   'monsol'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_totales($ai_totmonsol,$ai_totsaldo,&$io_pdf)
	 {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detallebeneficiarios
		//		   Access: private 
		//	    Arguments: $ai_totmonsol  // total solicitudes de pago por proveedor/beneficiario
		//	   			   $ai_totsaldo   // total saldo de cuentas por pagar por proveedor/beneficiario
		//	   			   $io_pdf // Objeto PDF
		//    Description: Función que imprime el total por proveedor/beneficiario
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 14/07/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$ai_totmonsol= number_format($ai_totmonsol,2,',','.');
		$ai_totsaldo= number_format($ai_totsaldo,2,',','.');
		$la_data  =array(array('totales'=>'<b>TOTAL</b>','totmonsol'=>$ai_totmonsol,'totsaldo'=>$ai_totsaldo));
	    $la_columna=array('totales'=>'',
						  'totmonsol'=>'',
						  'totsaldo'=>'');
		$la_config=array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 8,      // Tamaño de Letras
						 'titleFontSize' =>8,   // Tamaño de Letras de los títulos
						 'showLines'=>0,        // Mostrar Líneas
						 'shaded'=>2,           // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>1,
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xPos'=>300, // Orientación de la tabla
						 'cols'=>array('totales'=>array('justification'=>'right','width'=>410),
						               'totmonsol'=>array('justification'=>'right','width'=>80),
									   'totsaldo'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
	    $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	 }
	//-----------------------------------------------------------------------------------------------------------------------------------

	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_cxp_class_report.php");
	$io_report=new sigesp_cxp_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>RELACION DE SALDOS POR SOLICITUD</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_tipproben=$io_fun_cxp->uf_obtenervalor_get("tipproben","");
	$ls_codprobendes=$io_fun_cxp->uf_obtenervalor_get("codprobendes","");
	$ls_codprobenhas=$io_fun_cxp->uf_obtenervalor_get("codprobenhas","");
	$ld_fecemides=$io_fun_cxp->uf_obtenervalor_get("fecemides","");
	$ld_fecemihas=$io_fun_cxp->uf_obtenervalor_get("fecemihas","");
	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_cxp_class_reportbsf.php");
		$io_report=new sigesp_cxp_class_reportbsf();
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	$ls_estretiva=$_SESSION["la_empresa"]["estretiva"];
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_select_solicitudesprobensaldos($ls_tipproben,$ls_codprobendes,$ls_codprobenhas,
																$ld_fecemides,$ld_fecemihas); // Cargar el DS con los datos del reporte
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
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(3.6,4.8,3,3); // Configuración de los margenes en centímetros
			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página
			$li_totrow=$io_report->DS->getRowCount("codigo");
			$li_totaldoc=0;
			$li_totalcar=0;
			$li_totalded=0;
			uf_print_encabezado_pagina($ls_titulo,&$io_pdf);
			for($li_i=1;($li_i<=$li_totrow)&&($lb_valido);$li_i++)
			{
				$li_numpag=$io_pdf->ezPageCount; // Número de página
				$ls_codigo=$io_report->DS->data["codigo"][$li_i];
				$ls_nombre=$io_report->DS->data["nombre"][$li_i];
				$ls_tipproben=$io_report->DS->data["tipproben"][$li_i];
				uf_print_cabecera($ls_codigo,$ls_nombre,$ls_tipproben,&$io_pdf);
				$lb_valido=$io_report->uf_select_informacionsaldos($ls_tipproben,$ls_codigo,$ld_fecemides,$ld_fecemihas);
				$li_totmonsol=0;
				$li_totsaldo=0;
				if($lb_valido)
				{
					$li_totrowfac=$io_report->ds_detsolicitudes->getRowCount("numsol");
					$li_totalfacpro=0;
					for($li_j=1;$li_j<=$li_totrowfac;$li_j++)
					{
						$ls_numsol=$io_report->ds_detsolicitudes->data["numsol"][$li_j];
						$ld_fecemisol=$io_report->ds_detsolicitudes->data["fecemisol"][$li_j];
						$ls_consol=$io_report->ds_detsolicitudes->data["consol"][$li_j];
						$li_monsol=$io_report->ds_detsolicitudes->data["monsol"][$li_j];
						$li_monto=$io_report->uf_select_informacionpagos($ls_numsol);
						if($ls_estretiva=="B")
						{
							$li_monretiva=$io_report->uf_select_det_deducciones_solpag($ls_numsol);
							$li_monsol=$li_monsol+$li_monretiva;
						}
						$li_mondeuda=$li_monsol-$li_monto;
						$ld_fecemisol=$io_funciones->uf_convertirfecmostrar($ld_fecemisol);
						$li_totmonsol=$li_totmonsol + $li_monsol;
						$li_totsaldo=$li_totsaldo + $li_mondeuda;
						$li_monsol=number_format($li_monsol,2,',','.');
						$li_mondeuda=number_format($li_mondeuda,2,',','.');
						$la_data[$li_j]=array('numsol'=>$ls_numsol,'fecemisol'=>$ld_fecemisol,'consol'=>$ls_consol,
											  'monsol'=>$li_monsol,'monto'=>$li_mondeuda);
					}
					$li_totalfacpro=number_format($li_totalfacpro,2,',','.');
					uf_print_detalle_solicitudes($la_data,&$io_pdf);
					uf_print_totales($li_totmonsol,$li_totsaldo,$io_pdf);
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
