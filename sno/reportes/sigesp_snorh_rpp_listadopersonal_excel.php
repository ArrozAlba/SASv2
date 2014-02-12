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
		// Fecha Creación: 21/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_listadopersonal.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
	require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
	require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
	$lo_archivo = tempnam("/home/production/tmp", "listado_personal.xls");
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
	$ls_titulo="Listado de Personal";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_activo=$io_fun_nomina->uf_obtenervalor_get("activo","");
	$ls_egresado=$io_fun_nomina->uf_obtenervalor_get("egresado","");
	$ls_causaegreso=$io_fun_nomina->uf_obtenervalor_get("causaegreso","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_activono=$io_fun_nomina->uf_obtenervalor_get("activono","");
	$ls_vacacionesno=$io_fun_nomina->uf_obtenervalor_get("vacacionesno","");
	$ls_suspendidono=$io_fun_nomina->uf_obtenervalor_get("suspendidono","");
	$ls_egresadono=$io_fun_nomina->uf_obtenervalor_get("egresadono","");	
	$ls_masculino=$io_fun_nomina->uf_obtenervalor_get("masculino","");
	$ls_femenino=$io_fun_nomina->uf_obtenervalor_get("femenino","");
	$ls_codubifis=$io_fun_nomina->uf_obtenervalor_get("codubifis","");
	$ls_codpai=$io_fun_nomina->uf_obtenervalor_get("codpai","");
	$ls_codest=$io_fun_nomina->uf_obtenervalor_get("codest","");
	$ls_codmun=$io_fun_nomina->uf_obtenervalor_get("codmun","");
	$ls_codpar=$io_fun_nomina->uf_obtenervalor_get("codpar","");
	//---------------------------------------------------------------------------------------------------------------------------
	//Busqueda de la data 
	$lb_valido=uf_insert_seguridad("<b>Listado de Personal en Excel</b>"); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_listadopersonal_personal($ls_codnomdes,$ls_codnomhas,$ls_codperdes,$ls_codperhas,$ls_activo,
														   $ls_egresado,$ls_causaegreso,$ls_activono,$ls_vacacionesno,$ls_suspendidono,
														   $ls_egresadono,$ls_masculino,$ls_femenino,$ls_codubifis,$ls_codpai,$ls_codest,
														   $ls_codmun,$ls_codpar,$ls_orden); // Obtenemos el detalle del reporte
	}
	if($lb_valido==false) // Existe algún error ó no hay registros
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
		$lo_encabezado->set_size('11');
		$lo_titulo= &$lo_libro->addformat();
		$lo_titulo->set_text_wrap();
		$lo_titulo->set_bold();
		$lo_titulo->set_font("Verdana");
		$lo_titulo->set_align('center');
		$lo_titulo->set_size('9');		
		$lo_datacenter= &$lo_libro->addformat();
		$lo_datacenter->set_font("Verdana");
		$lo_datacenter->set_align('center');
		$lo_datacenter->set_size('9');
		$lo_dataleft= &$lo_libro->addformat();
		$lo_dataleft->set_text_wrap();
		$lo_dataleft->set_font("Verdana");
		$lo_dataleft->set_align('left');
		$lo_dataleft->set_size('9');
		$lo_dataright= &$lo_libro->addformat(array(num_format => '#,##0.00'));
		$lo_dataright->set_font("Verdana");
		$lo_dataright->set_align('right');
		$lo_dataright->set_size('9');
		$lo_hoja->set_column(0,0,20);
		$lo_hoja->set_column(1,1,60);
		$lo_hoja->set_column(2,2,15);
		$lo_hoja->set_column(3,3,20);
		$lo_hoja->set_column(4,4,50);
		$lo_hoja->set_column(5,5,20);
		$lo_hoja->set_column(6,6,15);
		$lo_hoja->set_column(7,7,20);
		$lo_hoja->set_column(8,9,50);
		$lo_hoja->set_column(10,10,15);
		$lo_hoja->set_column(11,12,50);
		$lo_hoja->set_column(13,14,20);
		$lo_hoja->set_column(15,18,50);
		$lo_hoja->write(0,1,$ls_titulo,$lo_encabezado);
		$lo_hoja->write(1,1,$ls_rango,$lo_encabezado);			
		$lo_hoja->write(3, 0, "Cédula de Identidad",$lo_titulo);
		$lo_hoja->write(3, 1, "Apellidos y Nombres",$lo_titulo);
		$lo_hoja->write(3, 2, "Género",$lo_titulo);
		$lo_hoja->write(3, 3, "Fecha de Nacimiento",$lo_titulo);
		$lo_hoja->write(3, 4, "Profesión",$lo_titulo);
		$lo_hoja->write(3, 5, "Fecha de Ingreso",$lo_titulo);
		$lo_hoja->write(3, 6, "Estatus",$lo_titulo);
		$lo_hoja->write(3, 7, "Nivel Académico",$lo_titulo);
		$lo_hoja->write(3, 8, "Nómina",$lo_titulo);
		$lo_hoja->write(3, 9, "Cargo",$lo_titulo);
		$lo_hoja->write(3, 10, "Sueldo",$lo_titulo);
		$lo_hoja->write(3, 11, "Dedicación",$lo_titulo);
		$lo_hoja->write(3, 12, "Tipo de Personal",$lo_titulo);
		$lo_hoja->write(3, 13, "Fecha de Ingreso a la Nómina",$lo_titulo);
		$lo_hoja->write(3, 14, "Estatus en Nómina",$lo_titulo);
		$lo_hoja->write(3, 15, "Ubicacion Fisica",$lo_titulo);
		$lo_hoja->write(3, 16, "Estado",$lo_titulo);
		$lo_hoja->write(3, 17, "Municipio",$lo_titulo);
		$lo_hoja->write(3, 18, "Parroquia",$lo_titulo);


		$li_row=3;
		$li_totrow=$io_report->DS->getRowCount("codper");
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_codper=$io_report->DS->data["codper"][$li_i];
			$ls_nomper=$io_report->DS->data["apeper"][$li_i].", ".$io_report->DS->data["nomper"][$li_i];
			$ld_fecingper=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecingper"][$li_i]);
			$ld_fecnacper=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecnacper"][$li_i]);
			$ls_nivacaper=$io_report->DS->data["nivacaper"][$li_i];
			$ls_estper=$io_report->DS->data["estper"][$li_i];
			$ls_estnom=$io_report->DS->data["estnom"][$li_i];
			$ls_despro=$io_report->DS->data["despro"][$li_i];
			$ls_nomina=$io_report->DS->data["desnom"][$li_i];
			$ld_fechano=$io_report->DS->data["fecingnom"][$li_i];
			$ls_sexper=$io_report->DS->data["sexper"][$li_i];
			$ls_racnom=$io_report->DS->data["racnom"][$li_i];
			$li_sueper=number_format($io_report->DS->data["sueper"][$li_i],2,",",".");
			$ls_desded=$io_report->DS->data["desded"][$li_i];
			$ls_destipper=$io_report->DS->data["destipper"][$li_i];
			$ls_desubifis=$io_report->DS->data["desubifis"][$li_i];
			$ls_desest=$io_report->DS->data["desest"][$li_i];
			$ls_desmun=$io_report->DS->data["desmun"][$li_i];
			$ls_despar=$io_report->DS->data["despar"][$li_i];
			switch ($ls_racnom)
			{
				case "0":
					$ls_cargo=$io_report->DS->data["descar"][$li_i];
					break;
				case "1":
					$ls_cargo=$io_report->DS->data["denasicar"][$li_i];
					break;
			}

			switch ($ls_sexper)
			{
				case "M":
					$ls_sexper="Masculino";
					break;
				case "F":
					$ls_sexper="Femenino";
					break;
			}
			if($ld_fechano!="---")
			{
				$ld_fechano=$io_funciones->uf_convertirfecmostrar($ld_fechano);
			}
			switch ($ls_estper)
			{
				case "0":
					$ls_estper="Pre-Ingreso";
					break;
				case "1":
					$ls_estper="Activo";
					break;
				case "2":
					$ls_estper="N/A";
					break;
				case "3":
					$ls_estper="Egresado";
					break;
			}
			switch ($ls_estnom)
			{
				case "0":
					$ls_estnom="N/A";
					break;
				case "1":
					$ls_estnom="Activo";
					break;
				case "2":
					$ls_estnom="Vacaciones";
					break;
				case "3":
					$ls_estnom="Egresado";
					break;
				case "4":
					$ls_estnom="Suspendido";
					break;
			}
			switch ($ls_nivacaper)
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

				$lo_hoja->write($li_row, 0, $ls_codper, $lo_datacenter);
				$lo_hoja->write($li_row, 1, $ls_nomper, $lo_dataleft);
				$lo_hoja->write($li_row, 2, $ls_sexper, $lo_datacenter);
				$lo_hoja->write($li_row, 3, $ld_fecnacper, $lo_datacenter);
				$lo_hoja->write($li_row, 4, $ls_despro, $lo_dataleft);
				$lo_hoja->write($li_row, 5, $ld_fecingper, $lo_datacenter);
				$lo_hoja->write($li_row, 6, $ls_estper, $lo_datacenter);
				$lo_hoja->write($li_row, 7, $ls_nivacaper, $lo_datacenter);
				$lo_hoja->write($li_row, 8, $ls_nomina, $lo_dataleft);
				$lo_hoja->write($li_row, 9, $ls_cargo, $lo_dataleft);
				$lo_hoja->write($li_row, 10, $li_sueper, $lo_dataright);
				$lo_hoja->write($li_row, 11, $ls_desded, $lo_dataleft);
				$lo_hoja->write($li_row, 12, $ls_destipper, $lo_dataleft);
				$lo_hoja->write($li_row, 13, $ld_fechano, $lo_datacenter);
				$lo_hoja->write($li_row, 14, $ls_estnom, $lo_datacenter);
				$lo_hoja->write($li_row, 15, $ls_desubifis, $lo_dataleft);
				$lo_hoja->write($li_row, 16, $ls_desest, $lo_dataleft);
				$lo_hoja->write($li_row, 17, $ls_desmun, $lo_dataleft);
				$lo_hoja->write($li_row, 18, $ls_despar, $lo_dataleft);
		}
		$io_report->DS->resetds("codper");
		$lo_libro->close();
		header("Content-Type: application/x-msexcel; name=\"listado_personal.xls\"");
		header("Content-Disposition: inline; filename=\"listado_personal.xls\"");
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