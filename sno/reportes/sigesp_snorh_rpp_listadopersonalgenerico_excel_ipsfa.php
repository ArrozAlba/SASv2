<?php
    session_start();   
	ini_set('memory_limit','512M');
	ini_set('max_execution_time','0');

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/08/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_listadopersonalgenerico.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($lo_titulo,&$lo_hoja)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: lo_hoja // hoja en excel
		//    Description: función que los títulos del reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_total=$_SESSION["li_total"];
		for($li_i=0;($li_i<=$li_total);$li_i++)
		{
			$lo_hoja->set_column($li_i,$li_i,$_SESSION["la_titulos"][$li_i]["ancho"]);
		}
		for($li_i=0;($li_i<=$li_total);$li_i++)
		{
			$lo_hoja->write(3, $li_i, $_SESSION["la_titulos"][$li_i]["titulo"],$lo_titulo);
		}
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	

	//---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
	require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
	require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
	$lo_archivo = tempnam("/home/production/tmp", "listado_personal_generico.xls");
	$lo_libro = &new writeexcel_workbookbig($lo_archivo);
	$lo_hoja = &$lo_libro->addworksheet();
	
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear la data necesaria del reporte
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo=$_SESSION["ls_titulo"];
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_ano=$io_fun_nomina->uf_obtenervalor_get("ano","");
	$ls_mes=$io_fun_nomina->uf_obtenervalor_get("mes","");
	$ls_titmes=strtoupper($io_report->io_fecha->uf_load_nombre_mes($ls_mes));
	$ls_codperi=$io_fun_nomina->uf_obtenervalor_get("codperi","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_titulo="LISTADO DE PERSONAL  ".$ls_titmes." - ".$ls_ano;
	//---------------------------------------------------------------------------------------------------------------------------
	//Busqueda de la data 
	$lb_valido=uf_insert_seguridad("<b>Listado de Personal en Excel IPSFA</b>"); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_listadogenerico_ipsfa($ls_codnomdes,$ls_codnomhas,$ls_ano,$ls_mes,$ls_codperi,$ls_codperdes,$ls_codperhas,$ls_orden,$rs_data); // Obtenemos el detalle del reporte
	}
	if(($lb_valido==false)||($rs_data->RecordCount()==0)) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		$lo_encabezado= &$lo_libro->addformat();
		$lo_encabezado->set_bold();
		$lo_encabezado->set_font("Verdana");
		$lo_encabezado->set_align('center');
		$lo_encabezado->set_size('10');
		$lo_titulo= &$lo_libro->addformat();
		$lo_titulo->set_text_wrap();
		$lo_titulo->set_bold();
		$lo_titulo->set_font("Verdana");
		$lo_titulo->set_align('center');
		$lo_titulo->set_size('8');		
		$lo_datacenter= &$lo_libro->addformat();
		$lo_datacenter->set_font("Verdana");
		$lo_datacenter->set_align('center');
		$lo_datacenter->set_size('8');
		$lo_dataleft= &$lo_libro->addformat();
		$lo_dataleft->set_text_wrap();
		$lo_dataleft->set_font("Verdana");
		$lo_dataleft->set_align('left');
		$lo_dataleft->set_size('8');
		$lo_dataright= &$lo_libro->addformat(array(num_format => '#,##0.00'));
		$lo_dataright->set_font("Verdana");
		$lo_dataright->set_align('right');
		$lo_dataright->set_size('8');
		$lo_dataright2= &$lo_libro->addformat();
		$lo_dataright2->set_font("Verdana");
		$lo_dataright2->set_align('right');
		$lo_dataright2->set_size('8');
		
		$lo_hoja->set_column(0,0,6);
		$lo_hoja->set_column(1,1,50);
		$lo_hoja->set_column(2,2,12);
		$lo_hoja->set_column(3,3,30);
		$lo_hoja->set_column(4,4,35);
		$lo_hoja->set_column(5,5,15);
		$lo_hoja->set_column(6,6,10);
		$lo_hoja->set_column(7,7,15);
		$lo_hoja->set_column(8,8,18);
		$lo_hoja->set_column(9,9,25);
		$lo_hoja->set_column(10,10,15);
		$lo_hoja->set_column(11,11,15);
		$lo_hoja->set_column(12,12,15);
		$lo_hoja->set_column(13,13,15);
		$lo_hoja->set_column(14,14,15);
		$lo_hoja->set_column(15,15,15);
		$lo_hoja->set_column(16,16,15);
		$lo_hoja->set_column(17,17,15);
		$lo_hoja->set_column(18,18,15);
		$lo_hoja->set_column(19,19,18);
		$lo_hoja->write(0, 9,$ls_titulo,$lo_encabezado);
		
		
		$lo_hoja->write(2, 9,"TIPOS",$lo_titulo);
		$lo_hoja->write(2, 10,"DE",$lo_titulo);
		$lo_hoja->write(2, 11,"PRIMAS",$lo_titulo);
		
		$lo_hoja->write(2, 13,"TIPOS",$lo_titulo);
		$lo_hoja->write(2, 14,"DE",$lo_titulo);
		$lo_hoja->write(2, 15,"BONOS",$lo_titulo);
		
		$lo_hoja->write(2, 17,"APORTES",$lo_titulo);
		$lo_hoja->write(2, 18,"PATRONALES",$lo_titulo);
		
		$lo_hoja->write(3, 0, "Nº",$lo_titulo);
		$lo_hoja->write(3, 1, "APELLIDOS Y NOMNBRES",$lo_titulo);
		$lo_hoja->write(3, 2, "CEDULA",$lo_titulo);
		$lo_hoja->write(3, 3, "GRADO INSTRUCCION",$lo_titulo);
		$lo_hoja->write(3, 4, "CARGO",$lo_titulo);
		$lo_hoja->write(3, 5, "FECHA INGRESO",$lo_titulo);
		$lo_hoja->write(3, 6, "AÑOS DE SERVICIO",$lo_titulo);
		$lo_hoja->write(3, 7, "SUELDO BASICO MENSUAL",$lo_titulo);
		$lo_hoja->write(3, 8, "COMPENSACIÓN",$lo_titulo);
		$lo_hoja->write(3, 9, "PRIMA PROFESIONALIZACIÓN",$lo_titulo);
		$lo_hoja->write(3, 10, "PRIMA ANTIGUEDAD",$lo_titulo);
		$lo_hoja->write(3, 11, "PRIMA HIJOS",$lo_titulo);
		$lo_hoja->write(3, 12, "TOTAL SUELDO MENSUAL",$lo_titulo);
		$lo_hoja->write(3, 13, "BONO VACACIONAL",$lo_titulo);
		$lo_hoja->write(3, 14, "BONO AGUINALDO",$lo_titulo);
		$lo_hoja->write(3, 15, "BONO PRODUCCION",$lo_titulo);
		$lo_hoja->write(3, 16, "CESTA TICKETS",$lo_titulo);
		$lo_hoja->write(3, 17, "SEGURO SOCIAL",$lo_titulo);
		$lo_hoja->write(3, 18, "PARO FORZOSO",$lo_titulo);
		$lo_hoja->write(3, 19, "POLITICA HABITACIONAL",$lo_titulo);
		$li_row=3;
		$li_i=0;		
		$li_totrow=$rs_data->RecordCount;
		while ((!$rs_data->EOF)&&($lb_valido))
		{
			$li_i=$li_i+1;
			$li_totsuemes=0;
			$ls_codper=$rs_data->fields["codper"];
			$ls_nomper=$rs_data->fields["apeper"].", ".$rs_data->fields["nomper"];			
			$ls_cedper=$rs_data->fields["cedper"];
			$ls_nivacaper=$rs_data->fields["nivacaper"];
			$ls_fecingper=$io_funciones->uf_convertirfecmostrar($rs_data->fields["fecingper"]);
			$ls_codcar=$rs_data->fields["codcar"];
			$ls_codasicar=$rs_data->fields["codasicar"];
			$ls_denasicar=$rs_data->fields["denasicar"];
			$ls_descar=$rs_data->fields["descar"];
			$ls_sueper=$rs_data->fields["sueper"];
			$li_anoing=intval(substr($rs_data->fields["fecingper"],0,4));
			$li_mesing=intval(substr($rs_data->fields["fecingper"],5,2));
			$li_anoact=$ls_ano;
			if (intval($li_mesing)<=intval($ls_mes))
			{
				$li_ano=intval($li_anoact - $li_anoing);
			}
			else
			{
				$li_ano=intval($li_anoact - $li_anoing)-1;
			}
			$ls_codnom=$rs_data->fields["codnom"];
			$ls_cargo="";
			if($ls_denasicar=="Sin Asignación de Cargo")
			{
				$ls_cargo=$ls_descar;
			}
			else
			{
				$ls_cargo=$ls_denasicar;
			}
			
			switch($ls_nivacaper)
			{
				case "0":
					$ls_nivacaper="Ninguno";
					break;
				case "1":
					$ls_nivacaper="Primaria";
					break;
				case "2":
					$ls_nivacaper="Bachiller";
					break;
				case "3":
					$ls_nivacaper="Técnico Superior";
					break;
				case "4":
					$ls_nivacaper="Universitario";
					break;
				case "5":
					$ls_nivacaper="Maestria";
					break;
				case "6":
					$ls_nivacaper="PostGrado";
					break;
				case "7":
					$ls_nivacaper="Doctorado";
					break;
			}
				
			$li_row=$li_row+1;
			
			$lb_valido=$io_report->uf_buscar_conceptos_personal($ls_codper,$ls_codnom,$ls_ano,$ls_codperi,$ld_comp,$ld_pripro,$ld_priant,$ld_prihij,$ld_bonvac,$ld_bonagu,$ld_sso,$ld_parfor,$ld_lph);
			
			$li_totsuemes=$ls_sueper+$ld_comp+$ld_pripro+$ld_priant+$ld_prihij;
			
			switch($ls_codnom)
			{
				case "0001":
					$ls_codnomces='0010';
					$lb_valido=$io_report->uf_buscar_cestaticket_personal($ls_codper,$ls_codnomces,$ls_ano,$ls_codperi, $ld_cesta);
				break;
				
				case "0002":
					$ls_codnomces='0012';
					$lb_valido=$io_report->uf_buscar_cestaticket_personal($ls_codper,$ls_codnomces,$ls_ano,$ls_codperi, $ld_cesta);
				break;
				
				case "0003":
					$ls_codnomces='0011';
					$lb_valido=$io_report->uf_buscar_cestaticket_personal($ls_codper,$ls_codnomces,$ls_ano,$ls_codperi, $ld_cesta);
					if ($lb_valido)
					{
						$lb_valido=$io_report->uf_buscar_salario($ls_codper,$ls_codnom,$ls_ano,$ls_codperi, $ls_sueper);
					}
				break;
				
				case "0004":
					$ls_codnomces='0013';
					$lb_valido=$io_report->uf_buscar_cestaticket_personal($ls_codper,$ls_codnomces,$ls_ano,$ls_codperi, $ld_cesta);
					if ($lb_valido)
					{
						$lb_valido=$io_report->uf_buscar_salario($ls_codper,$ls_codnom,$ls_ano,$ls_codperi, $ls_sueper);
					}
				break;
				
				case "0005":
					$ls_codnomces='0014';
					$lb_valido=$io_report->uf_buscar_cestaticket_personal($ls_codper,$ls_codnomces,$ls_ano,$ls_codperi, $ld_cesta);
				break;
				
				case "0006":
					$ls_codnomces='0015';
					$lb_valido=$io_report->uf_buscar_cestaticket_personal($ls_codper,$ls_codnomces,$ls_ano,$ls_codperi, $ld_cesta);
				break;
				
				default:
					$ld_cesta=0;
				break;
				
			}
						
			$lo_hoja->write($li_row, 0,$li_i,$lo_dataright2);
			$lo_hoja->write($li_row, 1, $ls_nomper,$lo_dataleft);
			$lo_hoja->write($li_row, 2, $ls_cedper,$lo_dataright2);
			$lo_hoja->write($li_row, 3, $ls_nivacaper,$lo_datacenter);
			$lo_hoja->write($li_row, 4, $ls_cargo,$lo_dataleft);
			$lo_hoja->write($li_row, 5, $ls_fecingper,$lo_datacenter);
			$lo_hoja->write($li_row, 6, $li_ano,$lo_datacenter);
			$lo_hoja->write($li_row, 7, $ls_sueper,$lo_dataright);
			$lo_hoja->write($li_row, 8, $ld_comp,$lo_dataright);
			$lo_hoja->write($li_row, 9, $ld_pripro,$lo_dataright);
			$lo_hoja->write($li_row, 10, $ld_priant,$lo_dataright);
			$lo_hoja->write($li_row, 11, $ld_prihij,$lo_dataright);
			$lo_hoja->write($li_row, 12, $li_totsuemes,$lo_dataright);			
			$lo_hoja->write($li_row, 13, $ld_bonvac,$lo_dataright);			
			$lo_hoja->write($li_row, 14, $ld_bonagu,$lo_dataright);
			$lo_hoja->write($li_row, 15, "NO APLICA",$lo_datacenter);
			$lo_hoja->write($li_row, 16, $ld_cesta,$lo_dataright);
			$lo_hoja->write($li_row, 17, $ld_sso,$lo_dataright);
			$lo_hoja->write($li_row, 18, $ld_parfor,$lo_dataright);
			$lo_hoja->write($li_row, 19, $ld_lph,$lo_dataright);
			
			$rs_data->MoveNext();
		}
		
		$lo_libro->close();
		header("Content-Type: application/x-msexcel; name=\"listado_personal_generico.xls\"");
		header("Content-Disposition: inline; filename=\"listado_personal_generico.xls\"");
		$fh=fopen($lo_archivo, "rb");
		fpassthru($fh);
		unlink($lo_archivo);
		print("<script language=JavaScript>");
		print(" close();");
		print("</script>");
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 
