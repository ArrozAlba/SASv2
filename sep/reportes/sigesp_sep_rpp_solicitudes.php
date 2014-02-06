<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Listado de Solicitudes de Ejecucion Presupuestaria
//  ORGANISMO: Ninguno en particular 
//  ESTE FORMATO SE IMPRIME EN Bs Y EN BsF. SEGUN LO SELECCIONADO POR EL USUARIO
//  MODIFICADO POR: ING.YOZELIN BARRAGAN         FECHA DE MODIFICACION : 14/08/2007
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
		global $io_fun_sep;
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_sep->uf_load_seguridad_reporte("SEP","sigesp_sep_r_solicitudes.php",$ls_descripcion);
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
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$io_pdf->addText(500,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(506,743,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 13/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);
		global $ls_tipoformato;
		if($ls_tipoformato==0)
		{
		  $ls_titulo_monto="Monto Bs.";
		}
		elseif($ls_tipoformato==1)
		{
		  $ls_titulo_monto="Monto Bs.F.";
		}
		$la_columnas=array('numsol'=>'<b>Solicitud</b>',
						   'denuniadm'=>'<b>Unidad Administrativa</b>',
						   'nombre'=>'<b>Proveedor / Beneficiario</b>',
						   'fecregsol'=>'<b>Fecha de Registro</b>',
						   'estsol'=>'<b>Estatus</b>',
						   'monto'=>'<b>'.$ls_titulo_monto.'</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numsol'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'denuniadm'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna
						 			   'fecregsol'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'estsol'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($ai_total,$ai_totrows,$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		   Access: private 
		//	    Arguments: ai_total // Total por personal
		//	   			   ai_totrows // Total por patrón
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno /Ing. Luis Lang
		// Fecha Creación: 13/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>Total Solicitudes</b>','totrows'=>$ai_totrows,'total'=>$ai_total));
		$la_columna=array('name'=>'','totrows'=>'','total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('name'=>array('justification'=>'right','width'=>390), // Justificación y ancho de la columna
						 			   'totrows'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_sep.php");
	$io_fun_sep=new class_funciones_sep();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>SOLICITUDES DE EJECUCIÓN PRESUPUESTARIA</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_numsoldes=$io_fun_sep->uf_obtenervalor_get("numsoldes","");
	$ls_numsolhas=$io_fun_sep->uf_obtenervalor_get("numsolhas","");
	$ls_tipproben=$io_fun_sep->uf_obtenervalor_get("tipproben","");
	$ls_codprobendes=$io_fun_sep->uf_obtenervalor_get("codprobendes","");
	$ls_codprobenhas=$io_fun_sep->uf_obtenervalor_get("codprobenhas","");
	$ld_fegregdes=$io_fun_sep->uf_obtenervalor_get("fegregdes","");
	$ld_fegreghas=$io_fun_sep->uf_obtenervalor_get("fegreghas","");
	$ls_codunides=$io_fun_sep->uf_obtenervalor_get("codunides","");
	$ls_codunihas=$io_fun_sep->uf_obtenervalor_get("codunihas","");
	$ls_tipsol=$io_fun_sep->uf_obtenervalor_get("tipsol","");
	$li_registrada=$io_fun_sep->uf_obtenervalor_get("registrada",0);
	$li_emitida=$io_fun_sep->uf_obtenervalor_get("emitida",0);
	$li_contabilizada=$io_fun_sep->uf_obtenervalor_get("contabilizada",0);
	$li_procesada=$io_fun_sep->uf_obtenervalor_get("procesada",0);
	$li_anulada=$io_fun_sep->uf_obtenervalor_get("anulada",0);
	$li_despachada=$io_fun_sep->uf_obtenervalor_get("despachada",0);
	$ls_orden=$io_fun_sep->uf_obtenervalor_get("orden","numsol");
	$ls_tipoformato=$io_fun_sep->uf_obtenervalor_get("tipoformato",0);
	$ls_codusudes=$io_fun_sep->uf_obtenervalor_get("codusudes","");
	$ls_codusuhas=$io_fun_sep->uf_obtenervalor_get("codusuhas","");
	$li_aprobada=$io_fun_sep->uf_obtenervalor_get("aprobada",0);
	$li_pagada=$io_fun_sep->uf_obtenervalor_get("pagada",0);
	//--------------------------------------------------------------------------------------------------------------------------------
	global $ls_tipoformato;
	if($ls_tipoformato==1)
	{
		require_once("sigesp_sep_class_reportbsf.php");
		$io_report=new sigesp_sep_class_reportbsf();
	}
	else
	{
		require_once("sigesp_sep_class_report.php");
		$io_report=new sigesp_sep_class_report();
	}	
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_select_solicitudes($ls_numsoldes,$ls_numsolhas,$ls_tipproben,$ls_codprobendes,$ls_codprobenhas,
													 $ld_fegregdes,$ld_fegreghas,$ls_codunides,$ls_codunihas,$ls_tipsol,$li_registrada,
													 $li_emitida,$li_contabilizada,$li_procesada,$li_anulada,
													 $li_despachada,$ls_orden,$ls_codusudes,$ls_codusuhas,$li_aprobada, $li_pagada); // Cargar el DS con los datos del reporte
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
			$io_pdf->ezSetCmMargins(3.6,2.5,3,3); // Configuración de los margenes en centímetros
			uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
			$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
			$li_totrow=$io_report->DS->getRowCount("numsol");
			$li_total=0;
			$li_s=0;
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$li_s=$li_s+1;
				$ls_numsol=$io_report->DS->data["numsol"][$li_i];
				$ls_denuniadm=$io_report->DS->data["denuniadm"][$li_i];
				$ls_nombre=$io_report->DS->data["nombre"][$li_i];
				$ls_estsol=$io_report->DS->data["estsol"][$li_i];
				$ls_estapro=$io_report->DS->data["estapro"][$li_i];
				$li_monto=$io_report->DS->data["monto"][$li_i];
				$ld_fecregsol=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecregsol"][$li_i]);
				$li_total=$li_total+$li_monto;
				switch ($ls_estsol)
				{
					case "R":
						$ls_estsol="Registro";					
						break;
					case "E":
						if ($ls_estapro==0)
						{
						  $ls_estsol="Emitida";					
						}
						else
						{
						  $ls_estsol="Aprobada";
						}					
						break;
					case "C":
						$ls_estsol="Contabilizada";					
						break;
					case "A":
						$ls_estsol="Anulada";					
						break;
					case "P":
						$ls_estsol="Procesada";					
						break;
					case "D":
						$ls_estsol="Despachada";
						break;
				}
				$li_monto=number_format($li_monto,2,",",".");
				$la_data[$li_i]=array('numsol'=>$ls_numsol,'denuniadm'=>$ls_denuniadm,'nombre'=>$ls_nombre,'fecregsol'=>$ld_fecregsol,'estsol'=>$ls_estsol,'monto'=>$li_monto);
			}
			uf_print_detalle($la_data,&$io_pdf);
			$li_total=number_format($li_total,2,",",".");
			uf_print_piecabecera($li_total,$li_s,$io_pdf);
			unset($la_data);
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
