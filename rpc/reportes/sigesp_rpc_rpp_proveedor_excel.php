<?php
    session_start();   
	ini_set('memory_limit','512M');
	ini_set('max_execution_time ','0');
	
	//---------------------------------------------------------------------------------------------------------------------------
   // para crear el libro excel
		require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
		require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
		$lo_archivo =  tempnam("/tmp", "rpc_proveedor.xls");
 		$lo_libro = &new writeexcel_workbookbig($lo_archivo);
		$lo_hoja = &$lo_libro->addworksheet();
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	$io_in=new sigesp_include();
	$con=$io_in->uf_conectar();
	
	require_once("sigesp_rpc_class_report.php");
	$io_report = new sigesp_rpc_class_report($con);
	
	require_once("../../shared/class_folder/class_sql.php");
	$io_sql = new class_sql($con);
	
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="Listado de Proveedores";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	if (array_key_exists("hidtipo",$_POST))
	   {
		 $ls_tipo=$_POST["hidtipo"];
	   }
	else
	   {
		 $ls_tipo=$_GET["hidtipo"];
	   }
	if (array_key_exists("hidorden",$_POST))
	   {
		 $li_orden=$_POST["hidorden"];
	   }
	else
	   {
		 $li_orden=$_GET["hidorden"];
	   }
	if (array_key_exists("hidcodprov1",$_POST))
	   {
		 $ls_codprov1=$_POST["hidcodprov1"];
	   }
	else
	   {
		 $ls_codprov1=$_GET["hidcodprov1"];
	   }
	if (array_key_exists("hidcodprov2",$_POST))
	   {
		 $ls_codprov2=$_POST["hidcodprov2"];
	   }
	else
	   {
		 $ls_codprov2=$_GET["hidcodprov2"];
	   }
	if (array_key_exists("hidcodesp",$_POST))
	   {
		 $ls_codesp=$_POST["hidcodesp"];
	   }
	else
	   {
		 $ls_codesp=$_GET["hidcodesp"];
	   }
	$lb_valido=true;
	$rs_proveedor=$io_report->uf_load_proveedores($ls_codemp,$li_orden,$ls_tipo,$ls_codprov1,$ls_codprov2,$ls_codesp,$lb_valido);
	if ($lb_valido)
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
		$lo_dataleft= &$lo_libro->addformat(array(num_format => '#0000000000'));
		$lo_dataright= &$lo_libro->addformat(array(num_format => '#,##0.00'));
		$lo_dataright->set_font("Verdana");
		$lo_dataright->set_align('right');
		$lo_dataright->set_size('9');
		$lo_hoja->set_column(0,0,15);
		$lo_hoja->set_column(1,1,50);
		$lo_hoja->set_column(2,2,30);
		$lo_hoja->set_column(3,3,30);
		$lo_hoja->set_column(4,4,50);
		$lo_hoja->set_column(5,5,50);
		$lo_hoja->set_column(6,6,30);
		$lo_hoja->set_column(7,7,30);
		$lo_hoja->set_column(8,8,30);
		$lo_hoja->set_column(9,9,30);
		$lo_hoja->write(0, 3, $ls_titulo,$lo_encabezado);
	    //////************************************************************************************************ 
		$li_total = $io_sql->num_rows($rs_proveedor);
		$li_row=2;
		if ($li_total>0)
		 {
		     $z = 0;
			$lo_hoja->write($li_row, 0, "Código",$lo_titulo);
			$lo_hoja->write($li_row, 1, "Nombre",$lo_titulo);
			$lo_hoja->write($li_row, 2, "RIF",$lo_titulo);
			$lo_hoja->write($li_row, 3, "NIT",$lo_titulo);
			$lo_hoja->write($li_row, 4, "Teléfono",$lo_titulo);
			$lo_hoja->write($li_row, 5, "Especialidad",$lo_titulo);
			$lo_hoja->write($li_row, 6, "Nivel de contratación",$lo_titulo);
			$lo_hoja->write($li_row, 7, "Calificación de Contratación",$lo_titulo);
			$lo_hoja->write($li_row, 8, "Capacidad financiera",$lo_titulo);
			$lo_hoja->write($li_row, 9, "Contable",$lo_titulo);
			while($row=$io_sql->fetch_row($rs_proveedor))
			  {
				$z++;
				$li_row=$li_row+1;
				$ls_codpro   = $row["cod_pro"];
				$ls_nompro   = $row["nompro"]; 
				$ls_rifpro   = $row["rifpro"]; 
				$ls_nitpro   = $row["nitpro"];
				$ls_telpro   = $row["telpro"]; 
				$ls_scgcta   = $row["sc_cuenta"];
				$ls_especialidad = $io_report->uf_load_especialidadproveedor2($ls_codpro,&$lb_valido);  
			    $ls_nivel = $io_report->uf_load_niveldecontratacionporproveedor($ls_codpro,&$lb_valido);  
			    $ls_clas =   $io_report->uf_load_niveldeclasificacionporproveedor($ls_codpro,&$lb_valido);  
			    $ld_moncapfin = number_format($data["monfincon"][$z],2,",",".");//Capacidad Financiera.
				$lo_hoja->write($li_row, 0, $ls_codpro,$lo_dataleft);
				$lo_hoja->write($li_row, 1, $ls_nompro,$lo_dataleft);
				$lo_hoja->write($li_row, 2, $ls_rifpro,$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'right','size'=>'10')));
				$lo_hoja->write($li_row, 3, $ls_nitpro,$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'right','size'=>'10')));
				$lo_hoja->write($li_row, 4, $ls_telpro,$lo_datacenter);
				$lo_hoja->write($li_row, 5, $ls_especialidad,$lo_datacenter);
				$lo_hoja->write($li_row, 6, $ls_nivel,$lo_datacenter);
				$lo_hoja->write($li_row, 7, $ls_clas,$lo_datacenter);
				$lo_hoja->write($li_row, 8, $ls_clas,$lo_datacenter);
				$lo_hoja->write($li_row, 9, $ls_scgcta,$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'right','size'=>'10')));
		      }
		 }  // fin del if
		$lo_libro->close();
		header("Content-Type: application/x-msexcel; name=\"rpc_proveedor.xls\"");
		header("Content-Disposition: inline; filename=\"rpc_proveedor.xls\"");
		$fh=fopen($lo_archivo, "rb");
		fpassthru($fh);
		unlink($lo_archivo);
		print("<script language=JavaScript>");
		print(" close();");
		print("</script>");
				
	}
	else
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	 }
?> 