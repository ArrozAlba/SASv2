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
		$lo_archivo = tempnam("/home/production/tmp", "relacion_cobros_inspago.xls");
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
        $ls_fac       = $_GET["factura"];
        $ls_cob       = $_GET["cobro"];
	
               
        
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
         $cadena_fac = (trim($ls_fac)=="") ? ("") :("AND dt_cobcli.numfac ilike '%$ls_fac%'");
         $cadena_cob = (trim($ls_cob)=="") ? ("") :("AND cobcli.numcob ilike '%$ls_cob%'");
        
        if (trim($ls_fecha_inicio)=="" && trim($ls_fecha_fin)=="") {
            $cadena_fecha = "";
            $ldt_fecha ="";
        }else {
            $cadena_fecha = "AND feccob BETWEEN '$fi_format' AND '$ff_format'";
            $ldt_fecha ="DEL  $ls_fecha_inicio HASTA $ls_fecha_fin";
        }
        
        
        
	$ls_titulo      = utf8_decode("RELACIÓN DE COBROS E INSTRUMENTOS DE PAGO");
       
        $sql = " 
            SELECT
                cli.razcli
                ,cobcli.numcob
                ,fac.numfac
                ,to_char(fac.fecemi,'DD/MM/YYYY') as fecemi
                ,fac.monto as monfac
                ,to_char(cobcli.feccob,'DD/MM/YYYY') as feccob
                ,cobcli.moncob
                ,CASE dt_cobcli.tipcancel 
                     when 'T' THEN 'TOTAL'
                     when 'P' THEN 'PARCIAL'
                END as tipcancel
                ,CASE fac.estfaccon 
                     when 'C' THEN 'CANCELADA'
                     when 'P' THEN 'POR COBRAR'
                     when 'N' THEN 'POR COBRAR'
                END as estatusfac
                ,pag.numinst
                ,to_char(pag.fecins,'DD/MM/YYYY') as fecins
                ,pag.obsins
                ,ban.nomban
                ,inspago.denforpag
                ,pag.ctaban
                ,pag.monto as montopago

            FROM
                sfc_cobro_cliente cobcli, sfc_cliente cli, sfc_dt_cobrocliente dt_cobcli, sfc_factura fac
                ,sfc_instpagocob pag, scb_banco ban, sfc_formapago inspago
            WHERE 
                        cobcli.estcob <> 'A'
                    AND fac.estfaccon <> 'A'
                    $cadena_cob
                    $cadena_cliente    
                    $cadena_fecha
                    $cadena_fac
                    AND cobcli.codcli = cli.codcli
                    AND dt_cobcli.numcob = cobcli.numcob
                    AND fac.numfac = dt_cobcli.numfac
                    AND cobcli.numcob = pag.numcob
                    AND ban.codban = pag.codban
                    AND inspago.codforpag = pag.codforpag
                    AND cli.codcli = pag.codcli
           ORDER BY    cobcli.numcob
        ";
	

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

		 
		 $lo_hoja->write(3, 0, "Num. de Factura",$lo_titulo);
                 $lo_hoja->write(3, 1, utf8_decode("Fecha de Facturación"),$lo_titulo);
                 $lo_hoja->write(3, 2, "Cliente",$lo_titulo);
		 $lo_hoja->write(3, 3, "Monto de la Factura",$lo_titulo);
                 $lo_hoja->write(3, 4, utf8_decode("Condición de pago"),$lo_titulo);
                 $lo_hoja->write(3, 5, utf8_decode("Estatus de la Factura"),$lo_titulo);
                 $lo_hoja->write(3, 6, "Numero de Instrumento de Pago",$lo_titulo);
                 $lo_hoja->write(3, 7, "Fecha de Pago",$lo_titulo);
                 $lo_hoja->write(3, 8, "Monto del Pago",$lo_titulo);
                 $lo_hoja->write(3, 9, utf8_decode("Observación del Pago"),$lo_titulo);
                 $lo_hoja->write(3, 10, "Banco",$lo_titulo);
                 $lo_hoja->write(3, 11, "Cuenta",$lo_titulo);
                 $lo_hoja->write(3, 12, "Forma de Pago",$lo_titulo);
		 
                //$lo_hoja->write(4, 4, "Estatus",$lo_titulo);
                 $li_row = 4;
                 $montoTotal = 0;
		 while($la_data  = pg_fetch_array($rs))
                    {
		           
			  
			     $lo_hoja->write($li_row, 0, $la_data['numfac'], $lo_datacenter);
			     $lo_hoja->write($li_row, 1, $la_data['fecemi'], $lo_datacenter);
			     $lo_hoja->write($li_row, 2, utf8_decode($la_data['razcli']), $lo_datacenter);
			     $lo_hoja->write($li_row, 3, $la_data['monfac'], $lo_dataright);
			     $lo_hoja->write($li_row, 4, $la_data['tipcancel'], $lo_dataleft);
                             $lo_hoja->write($li_row, 5, $la_data['estatusfac'], $lo_dataleft);
                             $lo_hoja->write($li_row, 6, " ".$la_data['numinst']." ", $lo_dataleft);
                             $lo_hoja->write($li_row, 7, $la_data['fecins'], $lo_dataleft);
                             $lo_hoja->write($li_row, 8, $la_data['montopago'], $lo_dataright);
                             $lo_hoja->write($li_row, 9, utf8_decode($la_data['obsins']), $lo_dataleft);
                             $lo_hoja->write($li_row, 10, utf8_decode($la_data['nomban']), $lo_dataleft);
                             $lo_hoja->write($li_row, 11, " ".$la_data['ctaban']." ", $lo_dataleft);
                             $lo_hoja->write($li_row, 12, utf8_decode($la_data['denforpag']), $lo_dataleft);
                             
                             
                             $li_row++;
		  }
		   
		   
		   //$lo_hoja->write($li_row+1, 2, " Total:  ",$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'14')));
		   //$lo_hoja->write($li_row+1, 3, $montoTotal, $lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'14')));
	
		   

		   $lo_libro->close();
		   header("Content-Type: application/x-msexcel; name=\"relacion_cobros_inspago.xls\"");
		   header("Content-Disposition: inline; filename=\"relacion_cobros_inspago.xls\"");
		   $fh=fopen($lo_archivo, "rb");
		   fpassthru($fh);
		   unlink($lo_archivo);
		  /* print("<script language=JavaScript>");
		   print(" close();");
		   print("</script>");*/
	   }
?>