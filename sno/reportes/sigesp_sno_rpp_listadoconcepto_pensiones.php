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
	ini_set('memory_limit','3072M');
	ini_set('max_execution_time','0');

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo,$ai_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_listadoconcepto.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_hlistadoconcepto.php",$ls_descripcion,$ls_codnom);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once ("sigesp_sno_class_tcpdf.php");
	$ls_tiporeporte="0";
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
	$ls_bolivares ="Bs.";
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$ls_titulo="Listado de Conceptos";
	$ls_periodo="Periodo Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codconcdes=$io_fun_nomina->uf_obtenervalor_get("codconcdes","");
	$ls_codconchas=$io_fun_nomina->uf_obtenervalor_get("codconchas","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_coduniadm=$io_fun_nomina->uf_obtenervalor_get("coduniadm","");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_denuniadm=$io_fun_nomina->uf_obtenervalor_get("denuniadm","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");
	$ls_codente=$io_fun_nomina->uf_obtenervalor_get("codente","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_listadoconcepto_conceptos($ls_codconcdes,$ls_codconchas,$ls_codperdes,$ls_codperhas,$ls_coduniadm,$ls_conceptocero,
															$ls_subnomdes,$ls_subnomhas,$ls_codente); // Cargar el DS con los datos de la cabecera del reporte
	}
	if(($lb_valido==false) || ($io_report->rs_data->RecordCount() == 0) )// Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else  // Imprimimos el reporte
	{
		error_reporting(E_ALL);
		$io_tcpdf= new sigesp_sno_class_tcpdf ("P", PDF_UNIT, "letter", true);
		$io_tcpdf->AliasNbPages();		
		$io_tcpdf->SetHeaderData('',20, date("d/m/Y"), date("h:i a"));
		$io_tcpdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$io_tcpdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$io_tcpdf->SetMargins(3, PDF_MARGIN_TOP,3);
		$io_tcpdf->SetHeaderMargin(3);
		$io_tcpdf->SetFooterMargin(2);
		$io_tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$io_tcpdf->AliasNbPages();
		$io_tcpdf->AddPage();
		$io_tcpdf->SetFont("helvetica","B",8);
	 	$io_tcpdf->Cell(0,8,$ls_titulo,0,0,'C');
		$io_tcpdf->Ln(3);
		$io_tcpdf->Cell(0,8,$ls_periodo,0,0,'C');
		$li_totrow=$io_report->rs_data->RecordCount();
		$li_i=1;
		while((!$io_report->rs_data->EOF)&&($lb_valido))
		{
			$ls_codconc=$io_report->rs_data->fields["codconc"];
			$ls_nomcon=$io_report->rs_data->fields["nomcon"];
			$li_tottra=$io_report->rs_data->fields["total"];
			$li_montot=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data->fields["monto"]));
			$io_tcpdf->uf_print_cabecera_listadoconceptos($ls_codconc,$ls_nomcon); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_listadoconcepto_personalconcepto($ls_codconc,$ls_codperdes,$ls_codperhas,$ls_conceptocero,$ls_coduniadm,
																	   $ls_subnomdes,$ls_subnomhas,$ls_orden); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->rs_data_detalle->RecordCount();
				$li_s=0;
				while(!$io_report->rs_data_detalle->EOF)
				{
					$ls_cedper=$io_report->rs_data_detalle->fields["cedper"];
					$ls_apenomper=$io_report->rs_data_detalle->fields["apeper"].", ". $io_report->rs_data_detalle->fields["nomper"];
					$ls_descar=$io_report->rs_data_detalle->fields["descar"];
					$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data_detalle->fields["valsal"]));
					
					$la_data[$li_s]=array(($li_s+1),$ls_cedper,$ls_apenomper,$ls_descar,$li_valsal);					
					$li_s++;
					$io_report->rs_data_detalle->MoveNext();
				}
				$la_data[$li_s]=array('','','','TOTAL TRABAJADORES '.$li_tottra,$li_montot);					
				$io_tcpdf->uf_print_detalle_listadoconceptos($la_data,$li_tottra,$li_montot); // Imprimimos el detalle 
				if($li_i<$li_totrow)
				{
					$io_tcpdf->AddPage();  // Insertar una nueva página
				}
				$li_i++;
				unset($io_cabecera);
				unset($la_data);
			}
			$io_report->rs_data->MoveNext();	
		}
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_tcpdf->Output("sigesp_sno_rpp_listadoconcepto_pensiones.pdf", "I");	
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