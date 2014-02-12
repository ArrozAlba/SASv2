<?php
    session_start();   
	ini_set('memory_limit','2048M');
	ini_set('max_execution_time ','0');	

	//---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
		require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
		require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
		$lo_archivo = tempnam("/home/production/tmp", "relacion_selectiva_cheques.xls");
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
	//---------------------------------------------------------------------------------------------------------------------------
	//Parámetros para Filtar el Reporte
	$ls_codban	    = $_GET["codban"];
	$ls_ctaban	    = $_GET["ctaban"];
	$ls_denban	    = $_GET["hidnomban"];
	$ls_dencta      = $_GET["dencta"];
	$ls_documentos  = $_GET["documentos"];
	$ls_fechas      = $_GET["fechas"];
	$ls_operaciones = $_GET["operaciones"];
	$ld_fecdes      = $_GET["fecdesde"];
	$ld_fechas      = $_GET["fechasta"];
	$ldt_fecha = $ld_fecdes."-".$ld_fechas;
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
	$ls_titulo      = "RELACIÓN SELECTIVA DE CHEQUES $ls_tipbol";
	//Descompongo la cadena de documentos en un arreglo tomando como separación el ','
	$arr_documentos=split(",",$ls_documentos);
	$li_totdoc=count($arr_documentos);
	//Descompongo la cadena de documentos en un arreglo tomando como separación el '-'
	$arr_fecmov=split("-",$ls_fechas);
	$li_totfec=count($arr_fecmov);
   //Descompongo la cadena de documentos en un arreglo tomando como separación el '-'
	$arr_operaciones=split("-",$ls_operaciones);
	$li_totdoc=count($arr_operaciones);
	$io_report->uf_cargar_documentos_relacion($arr_documentos,$arr_fecmov,$arr_operaciones,$ls_codban,$ls_ctaban);
	$li_total   = $io_report->ds_documentos->getRowCount("numdoc");
    $ld_totdeb  = 0;
    $ld_totcre  = 0;
    $ldec_saldo = 0;
	$li_row     = 0;
	if ($li_total<=0)
	   {
		 print("<script language=JavaScript>");
		 print(" alert('No hay nada que Reportar !!!');"); 
		 print(" close();");
		 print("</script>");
	   }
    else
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
	     $lo_hoja->write(3, 1, $ls_denban, $lo_datacenter);
	     $lo_hoja->write(3, 2, "Cuenta :",$lo_titulo);
	     $lo_hoja->write(3, 3, $ls_ctaban." - ".$ls_dencta, $lo_datacenter);

		 $li_row = 4;
		 $lo_hoja->write(4, 0, "Fecha",$lo_titulo);
		 $lo_hoja->write(4, 1, "Documento",$lo_titulo);
		 $lo_hoja->write(4, 2, "Proveedor/Beneficiario",$lo_titulo);
		 $lo_hoja->write(4, 3, "Monto",$lo_titulo);
		 $lo_hoja->write(4, 4, "Estatus",$lo_titulo);

		 for ($i=1;$i<=$li_total;$i++)
		     {
		       $ls_numdoc	 = $io_report->ds_documentos->getValue("numdoc",$i);
			   $ldec_monto	 = $io_report->ds_documentos->getValue("monto",$i);
			   $ld_fecmov	 = $io_report->ds_documentos->getValue("fecmov",$i);
			   $ld_fecmov	 = $io_funcion->uf_convertirfecmostrar($ld_fecmov);
			   $ls_nomproben = $io_report->ds_documentos->getValue("nomproben",$i);
               $ls_estmov	 = $io_report->ds_documentos->getValue("estmov",$i);
			   
			   switch($ls_estmov){
			     case 'C':
				    $ls_estmov='Contabilizado';//Haber
				    $ld_totcre = ($ld_totcre+$ldec_monto);
				 break;
				 case 'N':
				    $ls_estmov='No Contabilizado';//Haber
				    $ld_totcre = ($ld_totcre+$ldec_monto);
				  break;
				  case 'L':
				   $ls_estmov='No Contabilizable';//Haber
				   $ld_totcre = ($ld_totcre+$ldec_monto);
				  break;
			 	case 'A':
				   $ls_estmov = 'Anulado';//Debe
				   $ld_totdeb = ($ld_totdeb+$ldec_monto);
				break;					
			 	case 'O':
				   $ls_estmov='Original';//Haber
				   $ld_totcre = ($ld_totcre+$ldec_monto);
				break;										
			    }
				 $li_row=$li_row+1;
			     $lo_hoja->write($li_row, 0, $ld_fecmov, $lo_datacenter);
			     $lo_hoja->write($li_row, 1, " ".$ls_numdoc, $lo_datacenter);
			     $lo_hoja->write($li_row, 2, $ls_nomproben, $lo_datacenter);
			     $lo_hoja->write($li_row, 3, $ldec_monto, $lo_dataright);
			     $lo_hoja->write($li_row, 4, $ls_estmov, $lo_dataleft);
			  }
		   $ldec_saldo = ($ld_totcre-$ld_totdeb);
		   $li_row=$li_row+1;
		   $lo_hoja->write($li_row, 2, "Total Créditos",$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
		   $lo_hoja->write($li_row, 3, $ld_totcre, $lo_dataright);
	
		   $li_row=$li_row+1;
		   $lo_hoja->write($li_row, 2, "Total Débitos",$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
		   $lo_hoja->write($li_row, 3, $ld_totdeb, $lo_dataright);
	
 		   $li_row=$li_row+1;			
		   $lo_hoja->write($li_row, 2, "Total Saldo",$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
		   $lo_hoja->write($li_row, 3, $ldec_saldo, $lo_dataright);

		   $lo_libro->close();
		   header("Content-Type: application/x-msexcel; name=\"relacion_selectiva_cheques.xls\"");
		   header("Content-Disposition: inline; filename=\"relacion_selectiva_cheques.xls\"");
		   $fh=fopen($lo_archivo, "rb");
		   fpassthru($fh);
		   unlink($lo_archivo);
		   print("<script language=JavaScript>");
		   print(" close();");
		   print("</script>");
	   } 
?>