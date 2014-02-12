<?php
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
	ini_set('memory_limit','256M');
	ini_set('max_execution_time','0');

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo,$ai_tipo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_listadobanco.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_hlistadobanco.php",$ls_descripcion,$ls_codnom);
		}
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_desnom,$as_periodo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->line(60,100,180,100);
		$io_pdf->line(240,100,360,100);
		$io_pdf->line(420,100,540,100);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,720,11,$as_periodo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(10,$as_desnom);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,710,10,$as_desnom); // Agregar el título
		$io_pdf->addText(512,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(518,743,7,date("h:i a")); // Agregar la Hora		
		$io_pdf->addText(60,90,8,"Elaborado Por:"); 
		$io_pdf->addText(95,80,8,"Lic. Victor E. Gonzalez Ch.");
		$io_pdf->addText(240,90,8,"Revisado Por:"); // Agregar el título
		$io_pdf->addText(255,80,8,"Lic. María Ignacia García"); // Agregar el título
		$io_pdf->addText(420,90,8,"Aprobado Por:"); // Agregar el título
		$io_pdf->addText(445,80,8,"Ing. Manuel Troconis"); // Agregar el título
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_nomban,&$io_cabecera,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_nomban // Nombre del Banco
		//	    		   io_cabecera // Objeto cabecera
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera por banco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(51,690,500,$io_pdf->getFontHeight(14));
        $io_pdf->setColor(0,0,0);
		$io_pdf->addText(55,695,11,'<b>'.$as_nomban.'</b>'); // Agregar el título
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
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
		//    Description: función que imprime el detalle por banco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_dato[1]=array('nro'=>'<b>Nro</b>',
						  'cedula'=>'<b>Cédula</b>',
						  'nombre'=>'<b>Apellidos y Nombres</b>',
						  'cuenta'=>'<b>Cuenta Bancaria</b>',
						  'monto'=>'<b>Monto</b>');
		$la_columna=array('nro'=>'',
						  'cedula'=>'',
						  'nombre'=>'',
						  'cuenta'=>'',
						  'monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('nro'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'cuenta'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_dato,$la_columna,'',$la_config);	
		$la_columna=array('nro'=>'<b>Nro</b>',
						  'cedula'=>'<b>Cédula</b>',
						  'nombre'=>'<b>Apellidos y Nombres</b>',
						  'cuenta'=>'<b>Cuenta Bancaria</b>',
						  'monto'=>'<b>Monto</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('nro'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la columna
						 			   'cuenta'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_detalle($as_descripcion,$ai_total,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_detalle
		//		   Access: private 
		//	    Arguments: as_descripcion // Descripción del total
		//	   			   ai_total // Total 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por banco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$la_data=array(array('titulo'=>'<b>Sub Total '.$ls_bolivares.': </b>'.$as_descripcion.'','total'=>$ai_total));
		$la_columna=array('titulo'=>'','total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('titulo'=>array('justification'=>'right','width'=>400), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($ai_total,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		   Access: private 
		//	    Arguments: ai_total // Total a pagar
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por todos los registros
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$la_data=array(array('titulo'=>'<b>Total General '.$ls_bolivares.': </b>','neto'=>$ai_total));
		$la_columna=array('titulo'=>'','neto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((224/255),(224/255),(224/255)), // Color de la sombra
						 'shadeCol2'=>array((224/255),(224/255),(224/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>400), // Justificación y ancho de la columna
						 			   'neto'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	$ls_tiporeporte="0";
	$ls_bolivares ="Bs.";
	if($_SESSION["la_nomina"]["tiponomina"]=="NORMAL")
	{
		require_once("sigesp_sno_class_report.php");
		$io_report=new sigesp_sno_class_report();
		$li_tipo=1;
	}
	if($_SESSION["la_nomina"]["tiponomina"]=="HISTORICA")
	{
		require_once("sigesp_sno_class_report_historico.php");
		$io_report=new sigesp_sno_class_report_historico();
		$li_tipo=2;
	}	
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$ls_titulo="<b>Depósitos al Banco</b>";
	$ls_periodo="<b>Período Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codban=$io_fun_nomina->uf_obtenervalor_get("codban","");
	$ls_codcue=$io_fun_nomina->uf_obtenervalor_get("codcue","");
	$ld_fecpro=$io_fun_nomina->uf_obtenervalor_get("fecpro","");
	$ls_suspendido=$io_fun_nomina->uf_obtenervalor_get("susp","");
	$ls_quincena=$io_fun_nomina->uf_obtenervalor_get("quincena","-");
	$ls_sc_cuenta=$io_fun_nomina->uf_obtenervalor_get("sc_cuenta","");
	$ls_ctaban=$io_fun_nomina->uf_obtenervalor_get("ctaban","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_tipocuenta=$io_fun_nomina->uf_obtenervalor_get("tipcueban","");
	if(($ls_quincena=="1")&&($ls_divcon=="0"))
	{
		$ls_periodo="<b>Período Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper." -  Adelanto de Quincena</b>";	
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_listadobanco_banco($ls_codban,$ls_suspendido,$ls_sc_cuenta,$ls_ctaban,$ls_subnomdes,$ls_subnomhas,$ls_codperdes,$ls_codperhas); // Cargar el DS con los datos de la cabecera del reporte
	}
	if(($lb_valido==false) || ($io_report->rs_data->RecordCount()==0)) // Existe algún error ó no hay registros
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
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.6,5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_desnom,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->rs_data->RecordCount();
		$li_total=0;
		$li_nro=0;
		while(!$io_report->rs_data->EOF)
		{
			$ls_codban=$io_report->rs_data->fields["codban"];
			$ls_nomban=$io_report->rs_data->fields["nomban"];
			$io_cabecera=$io_pdf->openObject(); // Creamos el objeto cabecera
			uf_print_cabecera($ls_nomban,$io_cabecera,$io_pdf); // Imprimimos la cabecera del registro
			//-------------------------------------------Buscamos las cuentas de ahorro---------------------------------------------
			$ls_tipcueban="A"; // Buscamos las cuentas de ahorro
			if (($ls_tipocuenta == '') ||  ($ls_tipocuenta == $ls_tipcueban))
			{
				$lb_valido=$io_report->uf_listadobanco_personal($ls_codban,$ls_suspendido,$ls_tipcueban,$ls_quincena,$ls_subnomdes,$ls_subnomhas,
																$ls_codperdes,$ls_codperhas,$ls_orden); // Obtenemos el detalle del reporte
				if($lb_valido)
				{
					$li_subtot=0;
					$li_totrow_det=$io_report->rs_data_detalle->RecordCount();
					$li_s=1;
					while(!$io_report->rs_data_detalle->EOF)
					{
						$li_nro=$li_nro+1;
						$ls_cedper=$io_report->rs_data_detalle->fields["cedper"];
						$ls_nomper=$io_report->rs_data_detalle->fields["apeper"].", ".$io_report->rs_data_detalle->fields["nomper"];
						$li_subtot=$li_subtot+$io_report->rs_data_detalle->fields["monnetres"];
						$li_monnetres=$io_fun_nomina->uf_formatonumerico($io_report->rs_data_detalle->fields["monnetres"]);
						$ls_codcueban=$io_report->rs_data_detalle->fields["codcueban"];	
						$la_data[$li_s]=array('nro'=>$li_nro,'cedula'=>$ls_cedper,'nombre'=>$ls_nomper,'monto'=>$li_monnetres, 'cuenta'=>$ls_codcueban);
						$li_s++;
						$io_report->rs_data_detalle->MoveNext();
					}
					if(!empty($la_data))
					{
						uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
						$li_total=$li_total+$li_subtot;
						$ls_descripcion="Cuentas Ahorro";
						$li_subtot=$io_fun_nomina->uf_formatonumerico($li_subtot);
						uf_print_pie_detalle($ls_descripcion,$li_subtot,$io_pdf); // Imprimimos pie de la cabecera
					}
					unset($la_data);
				}
			}
			//-------------------------------------------Buscamos las cuentas corrientes---------------------------------------------
			$ls_tipcueban="C"; // Buscamos las cuentas corriente
			if (($ls_tipocuenta == '') ||  ($ls_tipocuenta == $ls_tipcueban))
			{
				$lb_valido=$io_report->uf_listadobanco_personal($ls_codban,$ls_suspendido,$ls_tipcueban,$ls_quincena,$ls_subnomdes,$ls_subnomhas,
																$ls_codperdes,$ls_codperhas,$ls_orden); // Obtenemos el detalle del reporte
				if($lb_valido)
				{
					$li_subtot=0;
					$li_totrow_det=$io_report->rs_data_detalle->RecordCount();
					$li_s=1;
					while(!$io_report->rs_data_detalle->EOF)	
					{
						$li_nro=$li_nro+1;
						$ls_cedper=$io_report->rs_data_detalle->fields["cedper"];
						$ls_nomper=$io_report->rs_data_detalle->fields["apeper"].", ".$io_report->rs_data_detalle->fields["nomper"];
						$li_subtot=$li_subtot+$io_report->rs_data_detalle->fields["monnetres"];
						$li_monnetres=$io_fun_nomina->uf_formatonumerico($io_report->rs_data_detalle->fields["monnetres"]);
						$ls_codcueban=$io_report->rs_data_detalle->fields["codcueban"];
						$la_data[$li_s]=array('nro'=>$li_nro,'cedula'=>$ls_cedper,'nombre'=>$ls_nomper,'monto'=>$li_monnetres, 'cuenta'=>$ls_codcueban);
						$li_s++;
						$io_report->rs_data_detalle->MoveNext();
					}
					if(!empty($la_data))
					{
						uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
						$li_total=$li_total+$li_subtot;
						$ls_descripcion="Cuentas Corriente";
						$li_subtot=$io_fun_nomina->uf_formatonumerico($li_subtot);
						uf_print_pie_detalle($ls_descripcion,$li_subtot,$io_pdf); // Imprimimos pie de la cabecera
					}
					unset($la_data);
				}
			}
			//-------------------------------------------Buscamos las cuentas Activos Líquidos------------------------------------------
			$ls_tipcueban="L"; // Buscamos las cuentas Activos Líquidos
			if (($ls_tipocuenta == '') ||  ($ls_tipocuenta == $ls_tipcueban))
			{
				$lb_valido=$io_report->uf_listadobanco_personal($ls_codban,$ls_suspendido,$ls_tipcueban,$ls_quincena,$ls_subnomdes,$ls_subnomhas,
																$ls_codperdes,$ls_codperhas,$ls_orden); // Obtenemos el detalle del reporte
				if($lb_valido)
				{
					$li_subtot=0;
					$li_totrow_det=$io_report->rs_data_detalle->RecordCount();
					$li_s=1;
					while(!$io_report->rs_data_detalle->EOF)
					{
						$li_nro=$li_nro+1;
						$ls_cedper=$io_report->rs_data_detalle->fields["cedper"];
						$ls_nomper=$io_report->rs_data_detalle->fields["apeper"].", ".$io_report->rs_data_detalle->fields["nomper"];
						$li_subtot=$li_subtot+$io_report->rs_data_detalle->fields["monnetres"];
						$li_monnetres=$io_fun_nomina->uf_formatonumerico($io_report->rs_data_detalle->fields["monnetres"]);
						$ls_codcueban=$io_report->rs_data_detalle->fields["codcueban"];
						$la_data[$li_s]=array('nro'=>$li_nro,'cedula'=>$ls_cedper,'nombre'=>$ls_nomper,'monto'=>$li_monnetres, 'cuenta'=>$ls_codcueban);
						$li_s++;
						$io_report->rs_data_detalle->MoveNext();
					}
					if(!empty($la_data))
					{
						uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
						$li_total=$li_total+$li_subtot;
						$ls_descripcion="Cuentas Activos Líquidos";
						$li_subtot=$io_fun_nomina->uf_formatonumerico($li_subtot);
						uf_print_pie_detalle($ls_descripcion,$li_subtot,$io_pdf); // Imprimimos pie de la cabecera
					}
					unset($la_data);
				}
			}
			//-------------------------------------------Buscamos los pagos por taquilla------------------------------------------
			$lb_valido=$io_report->uf_listadobancotaquilla_personal($ls_codban,$ls_suspendido,$ls_quincena,$ls_subnomdes,$ls_subnomhas,$ls_codperdes,$ls_codperhas,$ls_orden); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_subtot=0;
				$li_totrow_det=$io_report->rs_data_detalle->RecordCount();
				$li_s=1;
				while(!$io_report->rs_data_detalle->EOF)
				{
					$li_nro=$li_nro+1;
					$ls_cedper=$io_report->rs_data_detalle->fields["cedper"];
					$ls_nomper=$io_report->rs_data_detalle->fields["apeper"].", ".$io_report->rs_data_detalle->fields["nomper"];
					$li_subtot=$li_subtot+$io_report->rs_data_detalle->fields["monnetres"];
					$li_monnetres=$io_fun_nomina->uf_formatonumerico($io_report->rs_data_detalle->fields["monnetres"]);
					$ls_codcueban=$io_report->rs_data_detalle->fields["codcueban"];
					$la_data[$li_s]=array('nro'=>$li_nro,'cedula'=>$ls_cedper,'nombre'=>$ls_nomper,'monto'=>$li_monnetres, 'cuenta'=>$ls_codcueban);
					$li_s++;
					$io_report->rs_data_detalle->MoveNext();
				}
				if(!empty($la_data))
				{
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					$li_total=$li_total+$li_subtot;
					$ls_descripcion="Pago por Taquilla";
					$li_subtot=$io_fun_nomina->uf_formatonumerico($li_subtot);
					uf_print_pie_detalle($ls_descripcion,$li_subtot,$io_pdf); // Imprimimos pie de la cabecera
				}
				unset($la_data);
			}
			$io_pdf->stopObject($io_cabecera); // Detener el objeto cabecera
			unset($io_cabecera);
			$li_total=$io_fun_nomina->uf_formatonumerico($li_total);
			uf_print_piecabecera($li_total,$io_pdf);
			$io_report->rs_data->MoveNext();
		}
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 