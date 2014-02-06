<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Formato de salida  de Solicitud de Pago
//  ORGANISMO: ENMOHCA
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
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_p_solicitudpago.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_numsol,$as_codigo,$as_nombre,$as_rifproben,$ad_fecemisol,$as_consol,$as_obssol,
							   $ai_monsol,$as_monto,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_numsol    // Numero de la Solicitud de Pago
		//	   			   as_codigo    // Codigo del Proveedor / Beneficiario
		//	   			   as_nombre    // Nombre del Proveedor / Beneficiario
		//	   			   as_denfuefin // Denominacion de la fuente de financiamiento
		//	   			   ad_fecemisol // Fecha de Emision de la Solicitud
		//	   			   as_consol    // Concepto de la Solicitud
		//	   			   as_obssol    // Observaciones de la Solicitud
		//	   			   ai_monsol    // Monto de la Solicitud
		//	   			   as_monto     // Monto de la Solicitud en letras
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera 
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 11/02/2008.
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->addText(437,752,10,substr($as_numsol,-6));
		$io_pdf->addText(432,722,11,$ad_fecemisol);
		
		$io_pdf->addText(64,100,11,"Lic. Carla Brito");
		$io_pdf->addText(273,100,11,"Lic. Morela Camacho");
		$io_pdf->addText(478,100,11,"Lic. Morela Camacho");
		$io_pdf->addText(20,43,11,"Lic. Blanca Barrios");
		$io_pdf->addText(493,43,11,"Ing. Jorge Gonzalez");
		
		$io_pdf->ezSetY(715);
		$la_data[1]=array('titulo'=>'<b>'.$as_nombre.'</b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>9, // Tamaño de Letras
						 'titleFontSize'=>9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xPos'=>340, // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness'=>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->ezSetDy(-13);
		$la_data[1]=array('titulo'=>'<b>'.$as_rifproben.'</b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>9, // Tamaño de Letras
						 'titleFontSize'=>9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>548, // Ancho de la tabla
						 'maxWidth'=>548, // Ancho Máximo de la tabla
						 'xPos'=>340, // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness'=>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>580))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->ezSetY(665);
		$la_data[1]=array('titulo'=>$as_consol);
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>9, // Tamaño de Letras
						 'titleFontSize'=>9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>548, // Ancho de la tabla
						 'maxWidth'=>548, // Ancho Máximo de la tabla
						 'xPos'=>320, // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>548))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$io_pdf->ezSetY(585);
		$la_data[1]=array('titulo'=>$as_monto);
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>9, // Tamaño de Letras
						 'titleFontSize'=>9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xPos'=>330, // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>520))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->ezSetY(555);
		$la_data=array(array('name'=>$ai_monsol));						
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>705, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 						 					 
						 'maxWidth'=>480); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);		
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_recepcion($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				   ai_totsubtot // acumulado del subtotal
		//				   ai_tottot // acumulado del total
		//				   ai_totcar // acumulado de los cargos
		//				   ai_totded // acumulado de las deducciones
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 11/02/2008. 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(520);
		$la_columnas=array('numrecdoc'=>'','fecemisol'=>'','subtotdoc'=>'','moncardoc'=>'','mondeddoc'=>'','montotdoc'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>9, // Tamaño de Letras
						 'titleFontSize'=>9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numrecdoc'=>array('justification'=>'center','width'=>130), // Justificación y ancho de la columna
						 			   'fecemisol'=>array('justification'=>'left','width'=>70), // Justificación y ancho de la columna
						 			   'subtotdoc'=>array('justification'=>'right','width'=>92), // Justificación y ancho de la columna
						 			   'moncardoc'=>array('justification'=>'right','width'=>92), // Justificación y ancho de la columna
						 			   'mondeddoc'=>array('justification'=>'right','width'=>92), // Justificación y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'right','width'=>92))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	    unset($la_data);
		unset($la_columnas);
		unset($la_config);		
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_spg($aa_data,$ai_totpre,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: aa_data // arreglo de información
		//	    		   ai_totpre // monto total de presupuesto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle presupuestario
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
	    $la_data    = array(array('estpro'=>"ESTRUCTURA PRESUPUESTARIA",
		                          'spg_cuenta'=>"CUENTA PRESUPUESTARIA",
								  'denominacion'=>"DENOMINACION",
								  'monto'=>"MONTO"));
		$la_columna = array('estpro'=>'','spg_cuenta'=>'','denominacion'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>8, // Tamaño de Letras
						 'titleFontSize'=>8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Lineas
						 'shaded'=>1, // Sombra entre lineas
						 'shadeCol2'=>array(0.8,0.8,0.8), // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Minimo de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('estpro'=>array('justification'=>'center','width'=>150),
                                       'spg_cuenta'=>array('justification'=>'center','width'=>90),
									   'denominacion'=>array('justification'=>'center','width'=>240),
									   'monto'=>array('justification'=>'center','width'=>90))); // Justificacion y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'CUENTAS PRESUPUESTARIAS',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$la_columnas=array('codestpro'=>'','spg_cuenta'=>'','denominacion'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>8, // Tamaño de Letras
						 'titleFontSize'=>8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codestpro'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'spg_cuenta'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>240), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas,'',$la_config);
		unset($aa_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data=array(array('name'=>'<b>                 ____________________</b>'), 		              
   		               array('name'=>'                                  '.$ai_totpre));				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Lineas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'justification'=>'right',
						 'xPos'=>960, // Orientacion de la tabla
						 'width'=>1000, // Ancho de la tabla						 
						 'maxWidth'=>1000); // Ancho Minimo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($aa_data);
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_scg($aa_data,$ad_totdeb,$ad_tothab,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: aa_data // arreglo de información
		//	    		   si_totdeb // total monto debe
		//	    		   si_tothab // total monto haber
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle contable
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 27/05/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_data[1]=array('sc_cuenta'=>'CUENTA CONTABLE',
						  'denominacion'=>'DENOMINACION',
						  'mondeb'=>'DEBE',
						  'monhab'=>'HABER');
		$la_columnas=array('sc_cuenta'=>'','denominacion'=>'','mondeb'=>'','monhab'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>8, // Tamaño de Letras
						 'titleFontSize'=>8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('sc_cuenta'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'center','width'=>290), // Justificación y ancho de la columna
						 			   'mondeb'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'monhab'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'CUENTAS CONTABLES',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_columnas=array('sc_cuenta'=>'','denominacion'=>'','mondeb'=>'','monhab'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>8, // Tamaño de Letras
						 'titleFontSize'=>8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('sc_cuenta'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>290), // Justificación y ancho de la columna
						 			   'mondeb'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'monhab'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas,'',$la_config);
		unset($aa_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data=array(array('name'=>'<b>___________________     __________________</b>'),
   		               array('name'=>'               '.$ad_totdeb.'                  '.$ad_tothab.''));				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Lineas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'justification'=>'left',
						 'xPos'=>910, // Orientacion de la tabla
						 'width'=>1000, // Ancho de la tabla						 
						 'maxWidth'=>1000); // Ancho Minimo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	//Instancio a la clase de conversión de numeros a letras.
	require_once("../../shared/class_folder/class_numero_a_letra.php");
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
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>SOLICITUD DE ORDEN DE PAGO</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_numsol=$io_fun_cxp->uf_obtenervalor_get("numsol","");
	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	require_once("../../shared/ezpdf/class.ezpdf.php");
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_cxp_class_reportbsf.php");
		$io_report=new sigesp_cxp_class_reportbsf();
	}
	else
	{
		require_once("sigesp_cxp_class_report.php");
		$io_report=new sigesp_cxp_class_report();
	}	
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_select_solicitud($ls_numsol); // Cargar el DS con los datos del reporte
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
			$io_pdf->ezSetCmMargins(9,5,3,3); // Configuración de los margenes en centímetros
			$li_totrow=$io_report->DS->getRowCount("numsol");
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_numsol    = $io_report->DS->data["numsol"][$li_i];
				$ls_tipproben = $io_report->DS->data["tipproben"][$li_i];
				$ls_codpro    = $io_report->DS->data["cod_pro"][$li_i];
				$ls_cedbene   = $io_report->DS->data["ced_bene"][$li_i];
				if ($ls_tipproben=='P')
				   {
				     $ls_rifproben = $io_report->DS->data["rifpro"][$li_i];
				   }
				elseif($ls_tipproben=='B')
				   {
				     $ls_rifproben = $ls_cedbene;
				   }
				$ls_nombre    = $io_report->DS->data["nombre"][$li_i];
				$ls_dirproben = $io_report->DS->data["dirproben"][$li_i];
				$ls_consol	  = $io_report->DS->data["consol"][$li_i];
				$ls_obssol	  = $io_report->DS->data["obssol"][$li_i];
				$li_monsol	  = $io_report->DS->data["monsol"][$li_i];
				$numalet->setNumero($li_monsol);
				$ls_monto     = $numalet->letra();
				$li_monsol    = number_format($li_monsol,2,",",".");
				$ld_fecemisol = $io_report->DS->data["fecemisol"][$li_i];
				$ld_fecemisol = $io_funciones->uf_convertirfecmostrar($ld_fecemisol);
				if($ls_codpro!="----------")
				{
					$ls_codigo=$ls_codpro;
				}
				else
				{
					$ls_codigo=$ls_cedbene;
				}						
/*				if($ls_tiporeporte==0)
				{
					$li_monsolaux=$io_report->DS->data["monsolaux"][$li_i];
					$li_monsolaux=number_format($li_monsolaux,2,",",".");
				}
*/				uf_print_cabecera($ls_numsol,$ls_codigo,$ls_nombre,$ls_rifproben,$ld_fecemisol,$ls_consol,$ls_obssol,$li_monsol,$ls_monto,&$io_pdf);
				//////////////////////////  GRID RECEPCIONES DE DOCUMENTOS		//////////////////////////////////////
				$io_report->ds_detalle->reset_ds();
				$lb_valido=$io_report->uf_select_rec_doc_solicitud($ls_numsol); // Cargar el DS con los datos del reporte
				if($lb_valido)
				{
					$li_totrowdet=$io_report->ds_detalle_rec->getRowCount("numrecdoc");
					$la_data="";
					$li_totsubtot=0;
					$li_tottot=0;
					$li_totcar=0;
					$li_totded=0;
					for($li_s=1;$li_s<=$li_totrowdet;$li_s++)
					{
						$ls_numrecdoc=$io_report->ds_detalle_rec->data["numrecdoc"][$li_s];
						$ld_fecemidoc=$io_report->ds_detalle_rec->data["fecemidoc"][$li_s];
						$ls_numdoccomspg=$io_report->ds_detalle_rec->data["numdoccomspg"][$li_s];
						$li_mondeddoc=$io_report->ds_detalle_rec->data["mondeddoc"][$li_s];
						$li_moncardoc=$io_report->ds_detalle_rec->data["moncardoc"][$li_s];
						$li_montotdoc=$io_report->ds_detalle_rec->data["montotdoc"][$li_s];
						$li_subtotdoc=($li_montotdoc-$li_moncardoc+$li_mondeddoc);
						$ld_fecemidoc=$io_funciones->uf_convertirfecmostrar($ld_fecemidoc);
						$li_mondeddoc=number_format($li_mondeddoc,2,",",".");
						$li_moncardoc=number_format($li_moncardoc,2,",",".");
						$li_montotdoc=number_format($li_montotdoc,2,",",".");
						$li_subtotdoc=number_format($li_subtotdoc,2,",",".");
						$la_data[$li_s]=array('numrecdoc'=>$ls_numrecdoc,'fecemisol'=>$ld_fecemidoc,'mondeddoc'=>$li_mondeddoc,
											  'moncardoc'=>$li_moncardoc,'montotdoc'=>$li_montotdoc,'subtotdoc'=>$li_subtotdoc);
					}
					uf_print_detalle_recepcion($la_data,&$io_pdf);
					unset($la_data);
//////////////////////////  GRID RECEPCIONES DE DOCUMENTOS		//////////////////////////////////////
//////////////////////////   GRID DETALLE PRESUPUESTARIO		//////////////////////////////////////
					$lb_valido=$io_report->uf_select_detalle_spg($ls_numsol); // Cargar el DS con los datos del reporte
					if($lb_valido)
					{
						$li_totrowspg=$io_report->ds_detalle_spg->getRowCount("codestpro");
						$la_data="";
						$li_totpre=0;
						for($li_s=1;$li_s<=$li_totrowspg;$li_s++)
						{
							$ls_codestpro	 = $io_report->ds_detalle_spg->data["codestpro"][$li_s];
							$ls_spgcuenta	 = $io_report->ds_detalle_spg->data["spg_cuenta"][$li_s];
							$ls_denominacion = $io_report->ds_detalle_spg->data["denominacion"][$li_s];
							$li_monto		 = $io_report->ds_detalle_spg->data["monto"][$li_s];
							$li_totpre		 = $li_totpre+$li_monto;
							$li_monto		 = number_format($li_monto,2,",",".");
							if($ls_estmodest==1)
							{
								$ls_codestpro1 = substr(substr($ls_codestpro,0,20),-6);	
								$ls_codestpro2 = substr($ls_codestpro,20,6);
								$ls_codestpro3 = substr($ls_codestpro,26,3);
								$la_data[$li_s]= array('codestpro'=>$ls_codestpro1."-".$ls_codestpro2."-".$ls_codestpro3,
								                       'spg_cuenta'=>$ls_spgcuenta,
													   'denominacion'=>$ls_denominacion,
													   'monto'=>$li_monto);
							}
							else
							{
								$ls_codestpro1 = substr($ls_codestpro,18,2);	
								$ls_codestpro2 = substr($ls_codestpro,24,2);
								$ls_codestpro3 = substr($ls_codestpro,27,2);
								$ls_codestpro4 = substr($ls_codestpro,29,2);
								$ls_codestpro5 = substr($ls_codestpro,31,2);
								$la_data[$li_s]=array('codestpro'=>$ls_codestpro1."-".$ls_codestpro2."-".$ls_codestpro3."-".$ls_codestpro4."-".$ls_codestpro5,
								                      'spg_cuenta'=>$ls_spgcuenta,
													  'denominacion'=>$ls_denominacion,
													  'monto'=>$li_monto);
							}
						}	
						$li_totpre=number_format($li_totpre,2,",",".");
						uf_print_detalle_spg($la_data,$li_totpre,&$io_pdf);
						unset($la_data);
					}
//////////////////////////      GRID DETALLE PRESUPUESTARIO		//////////////////////////////////////
//////////////////////////         GRID DETALLE CONTABLE	    //////////////////////////////////////
					$lb_valido=$io_report->uf_select_detalle_scg($ls_numsol); // Cargar el DS con los datos del reporte
					if($lb_valido)
					{
						$li_totrowscg=$io_report->ds_detalle_scg->getRowCount("sc_cuenta");
						$la_data="";
						$li_totdeb=0;
						$li_tothab=0;
						for($li_s=1;$li_s<=$li_totrowscg;$li_s++)
						{
							$ls_sccuenta     = trim($io_report->ds_detalle_scg->data["sc_cuenta"][$li_s]);
							$ls_debhab		 = trim($io_report->ds_detalle_scg->data["debhab"][$li_s]);
							$ls_denominacion = trim($io_report->ds_detalle_scg->data["denominacion"][$li_s]);
							$li_monto		 = $io_report->ds_detalle_scg->data["monto"][$li_s];
							if($ls_debhab=="D")
							{
								$li_montodebe=$li_monto;
								$li_montohab="";
								$li_totdeb=$li_totdeb+$li_montodebe;
								$li_montodebe=number_format($li_montodebe,2,",",".");
							}
							else
							{
								$li_montodebe="";
								$li_montohab=$li_monto;
								$li_tothab=$li_tothab+$li_montohab;
								$li_montohab=number_format($li_montohab,2,",",".");
							}
							$la_data[$li_s]=array('sc_cuenta'=>$ls_sccuenta,
												  'denominacion'=>$ls_denominacion,
												  'mondeb'=>$li_montodebe,
												  'monhab'=>$li_montohab);
						}	
						$li_totdeb=number_format($li_totdeb,2,",",".");
						$li_tothab=number_format($li_tothab,2,",",".");
						uf_print_detalle_scg($la_data,$li_totdeb,$li_tothab,&$io_pdf);
						unset($la_data);
					}
//////////////////////////         GRID DETALLE CONTABLE	    //////////////////////////////////////
				}
			}
		}
		if($lb_valido) // Si no ocurrio ningún error
		{
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

?>
