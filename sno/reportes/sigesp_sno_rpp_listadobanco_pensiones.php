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
	ini_set('memory_limit','2048M');
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

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once ("sigesp_sno_class_tcpdf.php");
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
	$ls_divcon=$_SESSION["la_nomina"]["divcon"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$ls_titulo="Depositos al Banco";
	$ls_periodo="Periodo Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."";
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
		$ls_periodo="<b>Periodo Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper." -  Adelanto de Quincena</b>";	
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
		$io_tcpdf= new sigesp_sno_class_tcpdf ("P", PDF_UNIT, "letter", true);
		$io_tcpdf->AliasNbPages();		
		$io_tcpdf->SetHeaderData('',20, date("d/m/Y"), date("h:i a"));
		$io_tcpdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$io_tcpdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$io_tcpdf->SetMargins(3, PDF_MARGIN_TOP,3);
		$io_tcpdf->SetHeaderMargin(3);
		$io_tcpdf->SetFooterMargin(3);
		$io_tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$io_tcpdf->AliasNbPages();
		$io_tcpdf->AddPage();
		$io_tcpdf->SetFont("helvetica","B",8);
	 	$io_tcpdf->Cell(0,8,$ls_titulo,0,0,'C');
		$io_tcpdf->Ln(3);
		$io_tcpdf->Cell(0,8,$ls_periodo,0,0,'C');
		$li_totrow=$io_report->rs_data->RecordCount();
		$li_i=1;
		$li_total=0;
		$li_totalpersonas=0;
		while(!$io_report->rs_data->EOF)
		{
			$ls_codban=$io_report->rs_data->fields["codban"];
			$ls_nomban=$io_report->rs_data->fields["nomban"];
			$io_tcpdf->uf_print_cabecera_listadobanco($ls_codban,$ls_nomban); // Imprimimos la cabecera del registro
			//-------------------------------------------Buscamos las cuentas de ahorro---------------------------------------------
			$ls_tipcueban="A"; // Buscamos las cuentas de ahorro
			if (($ls_tipocuenta == '') ||  ($ls_tipocuenta == $ls_tipcueban))
			{
				$lb_valido=$io_report->uf_listadobanco_personal($ls_codban,$ls_suspendido,$ls_tipcueban,$ls_quincena,$ls_subnomdes,
																			$ls_subnomhas,$ls_codperdes,$ls_codperhas,$ls_orden); // Obtenemos el detalle del reporte
				if($lb_valido)
				{
					$li_subtot=0;
					$li_totrow_det=$io_report->rs_data_detalle->RecordCount();
					$li_s=0;
					while(!$io_report->rs_data_detalle->EOF)
					{
						$ls_cedper=$io_report->rs_data_detalle->fields["cedper"];
						$ls_nomper=$io_report->rs_data_detalle->fields["apeper"].", ".$io_report->rs_data_detalle->fields["nomper"];
						$li_subtot=$li_subtot+$io_report->rs_data_detalle->fields["monnetres"];
						$li_monnetres=$io_fun_nomina->uf_formatonumerico($io_report->rs_data_detalle->fields["monnetres"]);
						$ls_codcueban=$io_report->rs_data_detalle->fields["codcueban"];
						//$la_data[$li_s]=array(($li_s+1),$ls_cedper,$ls_nomper,$ls_codcueban,$li_monnetres);
						$la_data[$li_s]=array(($li_s+1),$ls_cedper,$ls_nomper,$li_monnetres);
						$li_s++;
						$io_report->rs_data_detalle->MoveNext();
					}
					if(!empty($la_data))
					{
						$li_total=$li_total+$li_subtot;
						$li_subtot=$io_fun_nomina->uf_formatonumerico($li_subtot);
						$la_data[$li_s]=array('','','','CUENTAS DE AHORRO ',$li_subtot);					
						$io_tcpdf->uf_print_detalle_listadobanco($la_data); // Imprimimos el detalle 
						$li_totalpersonas=$li_totalpersonas+($li_s-1);
					}
					unset($la_data);
				}
			}
			//-------------------------------------------Buscamos las cuentas de ahorro---------------------------------------------
			$ls_tipcueban="C"; // Buscamos las cuentas de corriente
			if (($ls_tipocuenta == '') ||  ($ls_tipocuenta == $ls_tipcueban))
			{
				$lb_valido=$io_report->uf_listadobanco_personal($ls_codban,$ls_suspendido,$ls_tipcueban,$ls_quincena,$ls_subnomdes,
																			$ls_subnomhas,$ls_codperdes,$ls_codperhas,$ls_orden); // Obtenemos el detalle del reporte
				if($lb_valido)
				{
					$li_subtot=0;
					$li_totrow_det=$io_report->rs_data_detalle->RecordCount();
					$li_s=0;
					while(!$io_report->rs_data_detalle->EOF)
					{
						$ls_cedper=$io_report->rs_data_detalle->fields["cedper"];
						$ls_nomper=$io_report->rs_data_detalle->fields["apeper"].", ".$io_report->rs_data_detalle->fields["nomper"];
						$li_subtot=$li_subtot+$io_report->rs_data_detalle->fields["monnetres"];
						$li_monnetres=$io_fun_nomina->uf_formatonumerico($io_report->rs_data_detalle->fields["monnetres"]);
						$ls_codcueban=$io_report->rs_data_detalle->fields["codcueban"];
						//$la_data[$li_s]=array(($li_s+1),$ls_cedper,$ls_nomper,$ls_codcueban,$li_monnetres);
						$la_data[$li_s]=array(($li_s+1),$ls_cedper,$ls_nomper,$li_monnetres);
						$li_s++;
						$io_report->rs_data_detalle->MoveNext();
					}
					if(!empty($la_data))
					{
						$li_total=$li_total+$li_subtot;
						$li_subtot=$io_fun_nomina->uf_formatonumerico($li_subtot);
						$la_data[$li_s]=array('','','','CUENTAS CORRIENTES ',$li_subtot);	
						$io_tcpdf->AddPage(); 				
						$io_tcpdf->uf_print_detalle_listadobanco($la_data); // Imprimimos el detalle 
						$li_totalpersonas=$li_totalpersonas+($li_s-1);
					}
					unset($la_data);
				}
			}
			//-------------------------------------------Buscamos las cuentas de Fondo de activos Liquidos---------------------------------------------
			$ls_tipcueban="L"; // Buscamos las cuentas de Fondos de Activos Liquidos
			if (($ls_tipocuenta == '') ||  ($ls_tipocuenta == $ls_tipcueban))
			{
				$lb_valido=$io_report->uf_listadobanco_personal($ls_codban,$ls_suspendido,$ls_tipcueban,$ls_quincena,$ls_subnomdes,
																			$ls_subnomhas,$ls_codperdes,$ls_codperhas,$ls_orden); // Obtenemos el detalle del reporte
				if($lb_valido)
				{
					$li_subtot=0;
					$li_totrow_det=$io_report->rs_data_detalle->RecordCount();
					$li_s=0;
					while(!$io_report->rs_data_detalle->EOF)
					{
						$ls_cedper=$io_report->rs_data_detalle->fields["cedper"];
						$ls_nomper=$io_report->rs_data_detalle->fields["apeper"].", ".$io_report->rs_data_detalle->fields["nomper"];
						$li_subtot=$li_subtot+$io_report->rs_data_detalle->fields["monnetres"];
						$li_monnetres=$io_fun_nomina->uf_formatonumerico($io_report->rs_data_detalle->fields["monnetres"]);
						$ls_codcueban=$io_report->rs_data_detalle->fields["codcueban"];
						//$la_data[$li_s]=array(($li_s+1),$ls_cedper,$ls_nomper,$ls_codcueban,$li_monnetres);
						$la_data[$li_s]=array(($li_s+1),$ls_cedper,$ls_nomper,$li_monnetres);
						$li_s++;
						$io_report->rs_data_detalle->MoveNext();
					}
					if(!empty($la_data))
					{
						$li_total=$li_total+$li_subtot;
						$li_subtot=$io_fun_nomina->uf_formatonumerico($li_subtot);
						$la_data[$li_s]=array('','','','ACTIVOS LIQUIDOS ',$li_subtot);	
						$io_tcpdf->AddPage(); 				
						$io_tcpdf->uf_print_detalle_listadobanco($la_data); // Imprimimos el detalle 
						$li_totalpersonas=$li_totalpersonas+($li_s-1);
					}
					unset($la_data);
				}
			}
			$li_total=$io_fun_nomina->uf_formatonumerico($li_total);
			$li_totalpersonas=number_format($li_totalpersonas,0,"",".");
			$io_tcpdf->uf_print_piecabecera_listadobanco($li_totalpersonas,$li_total); // Imprimimos el detalle 
			$io_report->rs_data->MoveNext();
		}
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_tcpdf->Output("sigesp_sno_rpp_listadobanco_pensiones.pdf", "I");	
		}
		else  // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		unset($io_tcpdf);	
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 