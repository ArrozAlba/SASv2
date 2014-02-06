<?php
    session_start();   
	ini_set('memory_limit','256M');
	ini_set('max_execution_time ','0');

	//---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
		require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
		require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
		$lo_archivo = tempnam("/home/production/tmp", "libro_banco.xls");
		$lo_libro = &new writeexcel_workbookbig($lo_archivo);
		$lo_hoja = &$lo_libro->addworksheet();
		
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear la data necesaria del reporte
		require_once("sigesp_scb_report.php");
		require_once("../../shared/class_folder/class_fecha.php");
		require_once("../../shared/class_folder/class_funciones.php");
		require_once("../../shared/class_folder/sigesp_include.php");
        require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_datastore.php");    
		
		$io_conect    = new sigesp_include();
		$con          = $io_conect->uf_conectar();
		$io_report    = new sigesp_scb_report($con);
		$io_funciones = new class_funciones();			
		$io_fecha     = new class_fecha();
		$ds_edocta    = new class_datastore();  
	    $io_sql       = new class_sql($con);
	//---------------------------------------------------------------------------------------------------------------------------
	//Parámetros para Filtar el Reporte
		$ls_codemp   = $_SESSION["la_empresa"]["codemp"];
	    $ls_codban   = $_GET["codban"];
	    $ls_ctaban   = $_GET["ctaban"];
	    $ld_fecdesde = $_GET["fecdes"];		
	    $ld_fechasta = $_GET["fechas"];	
	    $ls_orden    = $_GET["orden"];
		$ls_tipbol      = 'Bs.';
		$ls_tiporeporte = 0;
		$ls_tiporeporte = $_GET["tiporeporte"];
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			require_once("sigesp_scb_reportbsf.php");
			$io_report = new sigesp_scb_reportbsf($con);
			$ls_tipbol = 'Bs.F.';
		}
	//---------------------------------------------------------------------------------------------------------------------------
	//Parámetros del encabezado
		$ldt_fecha="Desde  ".$ld_fecdesde."  al ".$ld_fechasta."";
		$ls_titulo="LIBRO BANCO DETALLADO $ls_tipbol";       
	//---------------------------------------------------------------------------------------------------------------------------
	//Busqueda de la data 
	$lb_valido        = true;
	$data             = $io_report->uf_generar_estado_cuenta($ls_codemp,$ls_codban,$ls_ctaban,$ls_orden,$ld_fecdesde,$ld_fechasta,&$ldec_saldoant,&$ldec_total_debe,&$ldec_total_haber,true,'D','---');
    $li_numrows       = $io_sql->num_rows($data);
	$ldec_saldoactual = ($ldec_saldoant+$ldec_total_debe-$ldec_total_haber);
	$ldec_saldo       = $ldec_saldoant;
	$ldec_saldoant    = number_format($ldec_saldoant,2,",",".");	
	$ls_nomban        = $io_report->uf_select_data($io_sql,"SELECT * FROM scb_banco WHERE codban ='".$ls_codban."' AND codemp='".$ls_codemp."'","nomban");
	$ls_nomtipcta     = $io_report->uf_select_data($io_sql,"SELECT * FROM scb_tipocuenta t, scb_ctabanco c WHERE c.codemp='".$ls_codemp."' AND c.codtipcta=t.codtipcta AND c.ctaban='".$ls_ctaban."'","nomtipcta");	
	$ds_edocta->data  = $data;
	$li_totrow        = $ds_edocta->getRowCount("numdoc");

	//---------------------------------------------------------------------------------------------------------------------------
  	// Impresión de la información encontrada en caso de que exista
	if(empty($ds_edocta->data)) // Existe algún error ó no hay registros
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
		$lo_hoja->set_column(0,0,20);
		$lo_hoja->set_column(1,1,15);
		$lo_hoja->set_column(2,5,20);
		$lo_hoja->set_column(3,5,30);
		$lo_hoja->set_column(6,6,40);
		$lo_hoja->set_column(7,9,30);

		$lo_hoja->write(0, 2, $ls_titulo,$lo_encabezado);
		$lo_hoja->write(1, 2, $ldt_fecha,$lo_encabezado);
		
		$lo_hoja->write(3, 0, "Banco  :",$lo_titulo);
		$lo_hoja->write(3, 1, $ls_nomban, $lo_datacenter);
		$lo_hoja->write(3, 2, "Tipo de Cuenta :",$lo_titulo);
		$lo_hoja->write(3, 3, $ls_nomtipcta, $lo_dataleft);
		$lo_hoja->write(3, 4, "Cuenta :",$lo_titulo);
		$lo_hoja->write(3, 5, $ls_ctaban, $lo_dataleft);
		$lo_hoja->write(3, 6, "Saldo Anterior :",$lo_titulo);
		$lo_hoja->write(3, 7, $ldec_saldoant, $lo_dataright);
		
		$li_row = 4;
		$lo_hoja->write(4, 0, "Fecha",$lo_titulo);
		$lo_hoja->write(4, 1, "Operación",$lo_titulo);
		$lo_hoja->write(4, 2, "Documento",$lo_titulo);
		$lo_hoja->write(4, 3, "Estatus",$lo_titulo);
		$lo_hoja->write(4, 4, "Proveedor/Beneficiario",$lo_titulo);
		$lo_hoja->write(4, 5, "Region",$lo_titulo);
		$lo_hoja->write(4, 6, "Estructura Presupuestaria",$lo_titulo);
		$lo_hoja->write(4, 7, "Cuenta",$lo_titulo);
		$lo_hoja->write(4, 8, "Denominacion",$lo_titulo);
		$lo_hoja->write(4, 9, "Monto",$lo_titulo);
		
		$ls_nomban    = $io_report->uf_select_data($io_sql,"SELECT * FROM scb_banco WHERE codban ='".$ls_codban."' AND codemp='".$ls_codemp."'","nomban");
		$ls_nomtipcta = $io_report->uf_select_data($io_sql,"SELECT * FROM scb_tipocuenta t, scb_ctabanco c WHERE c.codemp='".$ls_codemp."' AND c.ctaban='".$ls_ctaban."' AND c.codtipcta=t.codtipcta","nomtipcta");	
         $li_estmodest = $_SESSION["la_empresa"]["estmodest"];
        for ($li_i=1;$li_i<=$li_totrow;$li_i++)
		    {
			    $ld_fecmov	    = $io_funciones->uf_convertirfecmostrar($ds_edocta->getValue("fecmov",$li_i));
			    $ls_operacion   = $ds_edocta->getValue("operacion",$li_i);
		   	    $ls_numdoc	    = $ds_edocta->getValue("numdoc",$li_i);
			    $ls_nomproben   = $ds_edocta->getValue("beneficiario",$li_i);
			    $ls_region      = $ds_edocta->getValue("region",$li_i);
				if ($ls_region=='---seleccione---')
				   {
				     $ls_region = "";
				   } 
				$ls_codestpro   = $ds_edocta->getValue("codestpro",$li_i);
			    if ($li_estmodest=='1')
				   {
				     $ls_codestpro1 = substr($ls_codestpro,0,20);
					 $ls_codestpro2 = substr($ls_codestpro,20,6);
					 $ls_codestpro3 = substr($ls_codestpro,26,3);
  				     $ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
				   }
				else
				   {
				     $ls_codestpro1 = substr($ls_codestpro,18,2);
					 $ls_codestpro2 = substr($ls_codestpro,24,2);
					 $ls_codestpro3 = substr($ls_codestpro,27,2);
					 $ls_codestpro4 = substr($ls_codestpro,29,2);
					 $ls_codestpro5 = substr($ls_codestpro,31,2);
					 $ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3.'-'.$ls_codestpro4.'-'.$ls_codestpro5;
				   }
				$ls_spgcuenta   = $ds_edocta->getValue("spg_cuenta",$li_i);
                $ls_denctaspg   = $ds_edocta->getValue("denctaspg",$li_i);
				$ls_estmov      = $ds_edocta->getValue("estmov",$li_i);
			    $ld_monspg      = $ds_edocta->getValue("monto",$li_i);

			  $li_row=$li_row+1;

			  $lo_hoja->write($li_row, 0, $ld_fecmov, $lo_datacenter);
			  $lo_hoja->write($li_row, 1, $ls_operacion, $lo_datacenter);
			  $lo_hoja->write($li_row, 2, $ls_numdoc, $lo_datacenter);
			  $lo_hoja->write($li_row, 3, $ls_estmov, $lo_datacenter);
			  $lo_hoja->write($li_row, 4, $ls_nomproben, $lo_dataleft);
			  $lo_hoja->write($li_row, 5, $ls_region, $lo_dataleft);
			  $lo_hoja->write($li_row, 6, $ls_codestpro, $lo_dataleft);
			  $lo_hoja->write($li_row, 7, $ls_spgcuenta, $lo_dataleft);				   
			  $lo_hoja->write($li_row, 8, $ls_denctaspg, $lo_dataleft);				   
			  $lo_hoja->write($li_row, 9, $ld_monspg, $lo_dataright);				   
		    }
		$li_row=$li_row+1;
		$lo_hoja->write($li_row, 4, "Totales",$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
		$lo_hoja->write($li_row, 5, $ldec_total_debe, $lo_dataright);
		$lo_hoja->write($li_row, 6, $ldec_total_haber, $lo_dataright);
		$lo_hoja->write($li_row, 7, $ldec_saldo, $lo_dataright);
		 
		$lo_libro->close();
		header("Content-Type: application/x-msexcel; name=\"libro_banco.xls\"");
		header("Content-Disposition: inline; filename=\"libro_banco.xls\"");
		$fh=fopen($lo_archivo, "rb");
		fpassthru($fh);
		unlink($lo_archivo);
		print("<script language=JavaScript>");
		print(" close();");
		print("</script>");
    }
?> 