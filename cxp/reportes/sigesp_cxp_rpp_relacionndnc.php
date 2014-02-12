<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Relacion de Notas de Debito / Credito
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
		// Fecha Creación: 17/07/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_relacionndnc.php",$ls_descripcion);
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
		// Fecha Creación: 17/07/2007
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
			
	function uf_print_detalle($la_data,$ai_i,$ai_totmonndnc,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data      // arreglo de información
		//				   ai_i         // total de registros
		//				   ai_totmonndnc // total de Notas (Montos)
		//	    		   io_pdf       // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 17/07/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);
		$la_datatit[1]=array('numdc'=>'<b>Numero ND/NC</b>','nombre'=>'<b>Proveedor/Beneficiario</b>','fecope'=>'<b>Fecha Registro</b>',
							 'descodope'=>'<b>Tipo</b>','status'=>'<b>Estatus</b>','monto'=>'<b>Monto</b>');
		$la_columnas=array('numdc'=>'',
						   'nombre'=>'',
						   'fecope'=>'',
						   'descodope'=>'',
						   'status'=>'',
						   'monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('numdc'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>225), // Justificación y ancho de la columna
						 			   'fecope'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
						 			   'descodope'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						 			   'status'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'center','width'=>75))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);

		$la_columnas=array('numdc'=>'',
						   'nombre'=>'',
						   'fecope'=>'',
						   'descodope'=>'',
						   'status'=>'',
						   'monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('numdc'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>225), // Justificación y ancho de la columna
						 			   'fecope'=>array('justification'=>'left','width'=>65), // Justificación y ancho de la columna
						 			   'descodope'=>array('justification'=>'left','width'=>55), // Justificación y ancho de la columna
						 			   'status'=>array('justification'=>'left','width'=>70), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>75))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$ai_i=$ai_i-1;
		$la_datatot[1]=array('titulo'=>'<b>Total de Registros: </b>'.$ai_i,'total'=>$ai_totmonndnc);
		$la_columnas=array('titulo'=>'<b>Factura</b>',
						   'total'=>'<b>Total</b>');
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
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>495), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>75))); // Justificación y ancho de la columna
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
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>RELACION DE NOTAS DE DEBITO / CREDITO</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_tipndnc= $io_fun_cxp->uf_obtenervalor_get("tipndnc","");
	$ls_numsoldes= $io_fun_cxp->uf_obtenervalor_get("numsoldes","");
	$ls_numsolhas= $io_fun_cxp->uf_obtenervalor_get("numsolhas","");
	$ls_ndncdes= $io_fun_cxp->uf_obtenervalor_get("ndncdes","");
	$ld_ndnchas= $io_fun_cxp->uf_obtenervalor_get("ndnchas","");
	$ld_fecregdes= $io_fun_cxp->uf_obtenervalor_get("fecregdes","");
	$ld_fecreghas= $io_fun_cxp->uf_obtenervalor_get("fecreghas","");
	$ls_emitida= $io_fun_cxp->uf_obtenervalor_get("emitida","");
	$ls_contabilizada= $io_fun_cxp->uf_obtenervalor_get("contabilizada","");
	$ls_anulada= $io_fun_cxp->uf_obtenervalor_get("anulada","");
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
		$lb_valido=$io_report->uf_select_relacionndnc($ls_tipndnc,$ls_numsoldes,$ls_numsolhas,$ls_ndncdes,$ld_ndnchas,
													   $ld_fecregdes,$ld_fecreghas,$ls_emitida,$ls_contabilizada,$ls_anulada); // Cargar el DS con los datos del reporte
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
			$li_totrow=$io_report->DS->getRowCount("numsol");
			$li_totmonndnc=0;
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_numdc= $io_report->DS->data["numdc"][$li_i];
				$ls_numsol= $io_report->DS->data["numsol"][$li_i];
				$ls_nombre= $io_report->DS->data["nombre"][$li_i];
				$ls_codtipdoc= $io_report->DS->data["codtipdoc"][$li_i];
				$ls_numrecdoc= $io_report->DS->data["numrecdoc"][$li_i];
				$ld_fecope= $io_report->DS->data["fecope"][$li_i];
				$ls_codope= trim($io_report->DS->data["codope"][$li_i]);    
				$ls_desope= $io_report->DS->data["desope"][$li_i];              
				$li_monto= $io_report->DS->data["monto"][$li_i];				  
				$ls_estapr= $io_report->DS->data["estapr"][$li_i];	
				$ls_estnotadc= $io_report->DS->data["estnotadc"][$li_i];
				$ls_denest="";
				switch ($ls_estnotadc)
				{
					case 'E':
						if($ls_estapr==1)
							$ls_denest='Emitida (Aprobada)';
						else
							$ls_denest='Emitida';
						break;
					case 'C':
						$ls_denest='Contabilizada';
						break;
					case 'A':
						$ls_denest='Anulada';
						break;
				}
				$ls_descodope="";
				switch ($ls_codope)
				{
					case"ND":
						$ls_descodope="Debito";
						break;
					case"NC":
						$ls_descodope="Credito";
						break;
				}
				$li_totmonndnc=$li_totmonndnc+$li_monto;
				$li_monto=number_format($li_monto,2,",",".");
				$ld_fecope=$io_funciones->uf_convertirfecmostrar($ld_fecope);
				$la_data[$li_i]=array('numdc'=>$ls_numdc,'nombre'=>$ls_nombre,'fecope'=>$ld_fecope,'descodope'=>$ls_descodope,
									  'status'=>$ls_denest,'monto'=>$li_monto);
			}
			$li_totmonndnc=number_format($li_totmonndnc,2,",",".");
			uf_print_encabezado_pagina($ls_titulo,&$io_pdf);
			uf_print_detalle($la_data,$li_i,$li_totmonndnc,&$io_pdf);
		}
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		
	}

?>
