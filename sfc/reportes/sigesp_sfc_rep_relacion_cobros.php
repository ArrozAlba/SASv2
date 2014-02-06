<?php
    session_start();   
	ini_set('memory_limit','2048M');
	ini_set('max_execution_time ','0');	
        
        function cambiarFormatoFecha($fecha){
            list($dia,$mes,$anio)=explode("/",$fecha);
        return $anio."/".$mes."/".$dia;
        } 
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
		require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
		require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
		$lo_archivo = tempnam("/home/production/tmp", "relacion_selectiva_cheques.xls");
		$lo_libro = &new writeexcel_workbookbig($lo_archivo);
		$lo_hoja = &$lo_libro->addworksheet();
	//---------------------------------------------------------------------------------------------------------------------------
	
	// para crear la data necesaria del reporte
                
                require_once("../../shared/ezpdf/class.ezpdf.php");
                require_once("../../shared/class_folder/class_funciones.php");
                require_once("../../shared/class_folder/sigesp_include.php");
                require_once("../../shared/class_folder/class_datastore.php");
                require_once("../../shared/class_folder/class_sql.php");
                $in=new sigesp_include();
                $con=$in->uf_conectar();
                $io_sql=new class_sql($con);		
                
		
	//---------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------
	//Parametros para Filtar el Reporte
	/*$ls_codban	    = $_GET["codban"];
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
	$ls_tiporeporte = $_GET["tiporeporte"];*/
        $ls_fecha_inicio = $_GET["desde"]; //"2011/04/09";
        $ls_fecha_fin =    $_GET["hasta"]; //"2011/08/08";
        $ls_razcli       = $_GET["razcli"];
	
               
        
        /*global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_scb_class_reportbsf.php");
		$io_report = new sigesp_scb_class_reportbsf($con);
		$ls_tipbol = 'Bs.F.';
	}*/
        $fi_format = cambiarFormatoFecha($ls_fecha_inicio);
        $ff_format = cambiarFormatoFecha($ls_fecha_fin);
        
        $cadena_cliente = (trim($ls_razcli)=="") ? ("") :("AND cli.razcli ilike '%$ls_razcli%'");
        
        if (trim($ls_fecha_inicio)=="" && trim($ls_fecha_fin)=="") {
            $cadena_fecha = "";
            $ldt_fecha ="";
        }else {
            $cadena_fecha = "AND feccob BETWEEN '$fi_format' AND '$ff_format'";
            $ldt_fecha ="DEL  $ls_fecha_inicio HASTA $ls_fecha_fin";
        }
        
        
        
	$ls_titulo      = utf8_decode("RELACIÓN DE COBROS");
       
	
        $sql = "
            SELECT
                cli.razcli
                ,cobcli.numcob
                ,to_char(cobcli.feccob,'DD/MM/YYYY') as feccob
                ,cobcli.moncob
            FROM
                sfc_cobro_cliente cobcli, sfc_cliente cli
            WHERE 
                        cobcli.estcob <> 'A'
                        $cadena_cliente
                    AND cobcli.codcli = cli.codcli
                        $cadena_fecha
             ORDER BY    cobcli.numcob ";
        $rs = pg_query($sql);
	$li_total =  pg_num_rows($rs);
        
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
	     //$lo_hoja->write(3, 0, "Banco  :",$lo_titulo);
	     //$lo_hoja->write(3, 1, $ls_denban, $lo_datacenter);
	     //$lo_hoja->write(3, 2, "Cuenta :",$lo_titulo);
	     //$lo_hoja->write(3, 3, $ls_ctaban." - ".$ls_dencta, $lo_datacenter);

		
		 $lo_hoja->write(3, 0, "Num. Cobro",$lo_titulo);
		 $lo_hoja->write(3, 1, "Cliente",$lo_titulo);
		 $lo_hoja->write(3, 2, "Fecha del Cobro",$lo_titulo);
		 $lo_hoja->write(3, 3, "Monto",$lo_titulo);
		 //$lo_hoja->write(4, 4, "Estatus",$lo_titulo);
                 $li_row = 5;
                 $montoTotal = 0;
		 while($la_data  = pg_fetch_array($rs))
                    {
		           $ls_numcob	 = $la_data['numcob'];
			   $ls_razcli	 = $la_data['razcli'];
			   $ld_feccob	 = $la_data['feccob'];
			   $ld_monto	 = $la_data['moncob'];

			   
                             
			     $lo_hoja->write($li_row, 0, $ls_numcob, $lo_datacenter);
			     $lo_hoja->write($li_row, 1, utf8_decode($ls_razcli), $lo_datacenter);
			     $lo_hoja->write($li_row, 2, $ld_feccob, $lo_datacenter);
			     $lo_hoja->write($li_row, 3, $ld_monto, $lo_dataright);
			    // $lo_hoja->write($li_row, 4, $ls_estmov, $lo_dataleft);
                             $montoTotal = $montoTotal + $ld_monto;
                             $li_row++;
			  }
		   
		   
		   $lo_hoja->write($li_row+1, 2, " Total:  ",$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'14')));
		   $lo_hoja->write($li_row+1, 3, $montoTotal, $lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'14')));
	
		   

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