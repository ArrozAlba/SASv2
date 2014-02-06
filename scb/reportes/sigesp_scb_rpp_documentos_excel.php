<?php
    session_start();   
	ini_set('memory_limit','1024M');
	ini_set('max_execution_time ','0');
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
		require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
		require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
		$lo_archivo = tempnam("/home/production/tmp", "listado_documentos.xls");
		$lo_libro = &new writeexcel_workbookbig($lo_archivo);
		$lo_hoja = &$lo_libro->addworksheet();
		
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear la data necesaria del reporte
		require_once("sigesp_scb_class_report.php");
		require_once("../../shared/class_folder/class_fecha.php");
		require_once("../../shared/class_folder/class_funciones.php");
		require_once("../../shared/class_folder/sigesp_include.php");
        require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_datastore.php");    
		
		$io_conect  = new sigesp_include();
		$con        = $io_conect->uf_conectar();
		$io_report  = new sigesp_scb_class_report($con);
		$io_funcion = new class_funciones();			
		$io_fecha   = new class_fecha();
	    $io_sql     = new class_sql($con);
	//---------------------------------------------------------------------------------------------------------------------------
	//Parámetros para Filtar el Reporte
	$ls_codemp    = $_SESSION["la_empresa"]["codemp"];
	$ld_fecdesde  = $_GET["fecdes"];
	$ld_fechasta  = $_GET["fechas"];
	$ls_codope    = $_GET["codope"];
	$ls_codban    = $_GET["codban"];
	$ls_nomban    = $_GET["nomban"];
	$ls_ctaban    = $_GET["ctaban"];
	$ls_codconcep = $_GET["codconcep"];
	$ls_orden     = $_GET["orden"];
	$ls_estatus   = $_GET["hidestmov"];
	$ls_tipbol      = 'Bs.';
	$ls_tiporeporte = 0;
	$ls_tiporeporte = $_GET["tiporeporte"];
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_scb_class_reportbsf.php");
		$io_report = new sigesp_scb_class_reportbsf($con);
		$ls_tipbol = 'Bs.F.';
	}
	
	//---------------------------------------------------------------------------------------------------------------------------
	//Parámetros del encabezado
		$ldt_fecha="Desde  ".$ld_fecdesde."  al ".$ld_fechasta."";
		$ls_titulo="LISTADO DE DOCUMENTOS $ls_tipbol";       
	//---------------------------------------------------------------------------------------------------------------------------
	//Busqueda de la data 
	$lb_valido = true;
	$rs_data   = $io_report->uf_cargar_documentos($ls_codope,$ld_fecdesde,$ld_fechasta,$ls_codban,$ls_ctaban,$ls_codconcep,$ls_estmov,$ls_orden,&$lb_valido);
	//---------------------------------------------------------------------------------------------------------------------------
  	// Impresión de la información encontrada en caso de que exista
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar !!!');"); 
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
		$lo_hoja->set_column(0,0,30);
		$lo_hoja->set_column(1,1,45);
		$lo_hoja->set_column(2,5,30);

		$lo_hoja->write(0, 2, $ls_titulo,$lo_encabezado);
		$lo_hoja->write(1, 2, $ldt_fecha,$lo_encabezado);
		$lo_hoja->write(3, 0, "Banco  :",$lo_titulo);
		$lo_hoja->write(3, 1, $ls_nomban, $lo_datacenter);
		$lo_hoja->write(3, 2, "Cuenta :",$lo_titulo);
		$lo_hoja->write(3, 3, $ls_ctaban, $lo_datacenter);
		
		$li_row = 4;
		$lo_hoja->write(4, 0, "Nro",$lo_titulo);
		$lo_hoja->write(4, 1, "Documento",$lo_titulo);
		$lo_hoja->write(4, 2, "Fecha",$lo_titulo);
		$lo_hoja->write(4, 3, "Proveedor/Beneficiario",$lo_titulo);
		$lo_hoja->write(4, 4, "Concepto",$lo_titulo);
		$lo_hoja->write(4, 5, "Monto",$lo_titulo);
		$lo_hoja->write(4, 6, "Estatus",$lo_titulo);
		
		$ldec_totaldebitos  = 0;
     	$ldec_totalcreditos = 0;
	    $ldec_saldo         = 0;
		$ld_totanu          = 0;
		
		$li_cont = 0;
		while($row=$io_sql->fetch_row($rs_data))
		     {
			   $li_cont++;
			   $ls_numdoc    = " ".$row["numdoc"];
			   $ldec_monto   = $row["monto"]; 
			   $ld_fecmov    = $row["fecmov"];
			   $ld_fecmov    = $io_funcion->uf_convertirfecmostrar($ld_fecmov);
			   $ls_nombre    = $row["nomproben"];
			   $ls_codope    = $row["codope"]; 
			   $ls_conmov    = $row["conmov"];
			   $ls_estbpd    = $row["estbpd"];
			   $ls_estmov    = $row["estmov"];
			   $ls_numcarord = $row["numcarord"];
			   if ($ls_estbpd=='T')
				  {
				    $ls_numdoc = $ls_numcarord;
				  } 
			   if ($ls_estatus=='A')
				  {
				    $ld_totanu = ($ld_totanu+$ldec_monto);
				  }
				switch($ls_codope)
				{
					case "ND":
                      if ($ls_estmov=='A')
					     {
						   $ld_totdeb = ($ld_totdeb+$ldec_monto);
						 }
					  else
					     {
						   $ld_totcre = ($ld_totcre+$ldec_monto);						   
						 }
					break;
					case "NC":
                      if ($ls_estmov=='A')
					     {
						   $ld_totcre = ($ld_totcre+$ldec_monto);						   
						 }
					  else
					     {
						   $ld_totdeb = ($ld_totdeb+$ldec_monto);
						 }
					break;
					case "DP":
                      if ($ls_estmov=='A')
					     {
						   $ld_totcre = ($ld_totcre+$ldec_monto);						   
						 }
					  else
					     {
						   $ld_totdeb = ($ld_totdeb+$ldec_monto);
						 }
					break;
					case "CH":
                      if ($ls_estmov=='A')
					     {
						   $ld_totdeb = ($ld_totdeb+$ldec_monto);
						 }
					  else
					     {
						   $ld_totcre = ($ld_totcre+$ldec_monto);						   
						 }
					break;
					case "RE":
                      if ($ls_estmov=='A')
					     {
						   $ld_totdeb = ($ld_totdeb+$ldec_monto);
						 }
					  else
					     {
						   $ld_totcre = ($ld_totcre+$ldec_monto);						   
						 }
					break;
				}
			   $li_row=$li_row+1;
			   $lo_hoja->write($li_row, 0, $li_cont, $lo_datacenter);
			   $lo_hoja->write($li_row, 1, $ls_numdoc, $lo_datacenter);
			   $lo_hoja->write($li_row, 2, $ld_fecmov, $lo_datacenter);
			   $lo_hoja->write($li_row, 3, $ls_nombre, $lo_dataleft);
			   $lo_hoja->write($li_row, 4, $ls_conmov, $lo_dataleft);
			   $lo_hoja->write($li_row, 5, $ldec_monto, $lo_dataright);
			   $lo_hoja->write($li_row, 6, $ls_estmov, $lo_datacenter);
			 }

			$li_row++;
			$lo_hoja->write($li_row, 0, "C: DOCUMENTOS CONTABILIZADOS",$lo_titulo);
		    $lo_hoja->write($li_row, 1, "N: DOCUMENTOS POR CONTABILIZAR",$lo_titulo);
		    $lo_hoja->write($li_row, 2, "A: DOCUMENTOS ANULADOS",$lo_titulo);
		    
			$li_row++;
			$lo_hoja->write($li_row, 0, "O: DOCUMENTOS ORIGINALES",$lo_titulo);
		    $lo_hoja->write($li_row, 1, "L: DOCUMENTOS SIN AFECTACIÓN CONTABLE",$lo_titulo);
			
		  if ($ls_estatus=='A')
		     {
			   $li_row=$li_row+1;
			   $lo_hoja->write($li_row, 2, "Total Anulados",$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
			   $lo_hoja->write($li_row, 3, $ld_totanu, $lo_dataright);
		     }
		  else
		     {
			   $ld_saldo = $ld_totcre-$ld_totdeb;//Calculo del saldo total para todas las cuentas
			   $li_row=$li_row+1;
			   $lo_hoja->write($li_row, 2, "Total Créditos",$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
			   $lo_hoja->write($li_row, 3, $ld_totcre, $lo_dataright);
	
			   $li_row=$li_row+1;
			   $lo_hoja->write($li_row, 2, "Total Débitos",$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
			   $lo_hoja->write($li_row, 3, $ldec_totdeb, $lo_dataright);
	
 			   $li_row=$li_row+1;			
			   $lo_hoja->write($li_row, 2, "Total Saldo",$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
			   $lo_hoja->write($li_row, 3, $ld_saldo, $lo_dataright);
  	         }

			$lo_libro->close();
			header("Content-Type: application/x-msexcel; name=\"listado_documentos.xls\"");
			header("Content-Disposition: inline; filename=\"listado_documentos.xls\"");
			$fh=fopen($lo_archivo, "rb");
			fpassthru($fh);
			unlink($lo_archivo);
			print("<script language=JavaScript>");
			print(" close();");
			print("</script>");
    }
?> 