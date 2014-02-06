<?php
    session_start();   
	ini_set('memory_limit','256M');
	ini_set('max_execution_time ','0');
//---------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
		require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
		require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
		$lo_archivo =  tempnam("/tmp", "spg_ejecutado_por_partida.xls");
		$lo_libro = &new writeexcel_workbookbig($lo_archivo);
		$lo_hoja = &$lo_libro->addworksheet();
//---------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
		require_once("sigesp_spg_reportes_class.php");
		$io_report = new sigesp_spg_reportes_class();
		require_once("sigesp_spg_funciones_reportes.php");
		$io_function_report = new sigesp_spg_funciones_reportes();
        require_once("../../shared/class_folder/class_funciones.php");
		$io_function=new class_funciones() ;
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
//--------------------------------------------------  Parámetros para Filtar el Reporte  --------------------------------------
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		$ldt_fecdes = $_GET["txtfecdes"];
		$ldt_fechas = $_GET["txtfechas"];	
	    $ls_cuentades_min=$_GET["txtcuentades"];
	    $ls_cuentahas_max=$_GET["txtcuentahas"];
		if($ls_cuentades_min=="")
		{
		   if($io_function_report->uf_spg_reporte_select_min_cuenta($ls_cuentades_min))
		   {
		     $ls_cuentades=$ls_cuentades_min;
		   } 
		   else
		   {
				print("<script language=JavaScript>");
				print(" alert('No hay cuentas presupuestraias');"); 
				print(" close();");
				print("</script>");
		   }
		}
		else
		{
		    $ls_cuentades=$ls_cuentades_min;
		}
		if($ls_cuentahas_max=="")
		{
		   if($io_function_report->uf_spg_reporte_select_max_cuenta($ls_cuentahas_max))
		   {
		     $ls_cuentahas=$ls_cuentahas_max;
		   } 
		   else
		   {
				print("<script language=JavaScript>");
				print(" alert('No hay cuentas presupuestraias');"); 
				print(" close();");
				print("</script>");
		   }
		}
		else
		{
		    $ls_cuentahas=$ls_cuentahas_max;
		}

	    $ls_fechades=$io_function->uf_convertirfecmostrar($ldt_fecdes);
	    $ls_fechahas=$io_function->uf_convertirfecmostrar($ldt_fechas);
		
	 /////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_desc_event="Solicitud de Reporte Ejecutado por Partida desde la  Fecha ".$ls_fechades."  hasta ".$ls_fechahas." Desde la Cuenta ".$ls_cuentades."  hasta ".$ls_cuentahas;
	 $io_function_report->uf_load_seguridad_reporte("SPG","sigesp_spg_r_ejecutado_por_partida.php",$ls_desc_event);
	////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//----------------------------------------------------  Parámetros del encabezado  ----------------------------------------------
		$ls_titulo="EJECUTADO POR PARTIDA"; 
		$ls_fecha="DESDE  ".$ls_fechades."   HASTA LA FECHA  ".$ls_fechahas." ";      
//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
     $lb_valido=$io_report->uf_spg_reportes_ejecutado_por_partida($ldt_fecdes,$ldt_fechas,$ls_cuentades,$ls_cuentahas);
 
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
		$lo_hoja->set_column(0,0,15);
		$lo_hoja->set_column(1,1,20);
		$lo_hoja->set_column(2,2,30);
		$lo_hoja->set_column(3,3,20);
		$lo_hoja->set_column(4,4,13);
		$lo_hoja->set_column(5,7,30);
		$lo_hoja->write(0, 3, $ls_titulo,$lo_encabezado);
		$lo_hoja->write(1, 3, $ls_fecha,$lo_encabezado);
		$io_report->dts_reporte_final->group_noorder("spg_cuenta");
		$li_tot=$io_report->dts_reporte_final->getRowCount("spg_cuenta");
		$ld_total_asignado=0;		        $ld_total_precompromiso=0;
		$ld_total_compromiso=0;		        $ld_total_causado=0;
		$ld_total_pagado=0;		
		$ld_total_asignado_general=0;		$ld_total_precompromiso_general=0;
		$ld_total_compromiso_general=0;		$ld_total_causado_general=0;
		$ld_total_pagado_general=0;		    $ld_asignado_apertura=0;
		$ls_spg_cuenta_ant="";
		$ls_cuenta_ant="";
		$li_row=2;
		for($z=1;$z<=$li_tot;$z++)
		{
			$li_tmp=($z+1);
			$ls_spg_cuenta=$io_report->dts_reporte_final->data["spg_cuenta"][$z];
			$ls_denominacion=$io_report->dts_reporte_final->data["denominacion"][$z];  
			$ls_programatica=$io_report->dts_reporte_final->data["programatica"][$z];
			$ls_descripcion=$io_report->dts_reporte_final->data["descripcion"][$z];
			$ld_asignado=$io_report->dts_reporte_final->data["asignado"][$z];  	  
			$ld_aumento=$io_report->dts_reporte_final->data["aumento"][$z];
			$ld_disminucion=$io_report->dts_reporte_final->data["disminucion"][$z];
		    $ld_precompromiso=$io_report->dts_reporte_final->data["precompromiso"][$z];
		    $ld_compromiso=$io_report->dts_reporte_final->data["compromiso"][$z];
		    $ld_causado=$io_report->dts_reporte_final->data["causado"][$z];
		    $ld_pagado=$io_report->dts_reporte_final->data["pagado"][$z];
			
			$ls_codestpro1=substr($ls_programatica,0,20);
		    $ls_codestpro2=substr($ls_programatica,20,6);
		    $ls_codestpro3=substr($ls_programatica,26,3);
			$ls_programatica=$ls_codestpro1."-".$ls_codestpro2."-".$ls_codestpro3;
			if($li_estmodest==2)
			{
				$ls_programatica="";
				$ls_codestpro4=substr($ls_programatica,29,2);
				$ls_codestpro5=substr($ls_programatica,31,2);
			    $ls_programatica=substr($ls_codestpro1,-2)."-".substr($ls_codestpro2,-2)."-".substr($ls_codestpro3,-2)."-".substr($ls_codestpro4,-2)."-".substr($ls_codestpro5,-2);
			}
			if ($z<$li_tot)
		    {
				$ls_spg_cuenta_next=$io_report->dts_reporte_final->data["spg_cuenta"][$li_tmp]; 
		    }
		    elseif($z=$li_tot)
		    {
				$ls_spg_cuenta_next='no_next';
		    }
			if(empty($ls_spg_cuenta_next)&&(!empty($ls_spg_cuenta)))
			{
			   $ls_spg_cuenta_ant=$io_report->dts_reporte_final->data["spg_cuenta"][$z];
			}
			if($li_tot==1)
			{
			   $ls_spg_cuenta_ant=$io_report->dts_reporte_final->data["spg_cuenta"][$z];
			}
			if($ld_asignado<>0)
			{
			  $ld_asignado_apertura=$ld_asignado;
			}
			
			$ld_suma=$ld_asignado_apertura+$ld_aumento+$ld_disminucion;
			if($ld_suma>0)
			{
			   $ld_porc_comprometido=($ld_compromiso*100)/$ld_suma;
		    }
            else
			{
			   $ld_porc_comprometido=0;
			}
			if($ld_compromiso>0)
			{
			   $ld_porc_causado=($ld_causado*100)/$ld_compromiso;
		    }
            else
			{
			   $ld_porc_causado=0;
			}
			if($ld_causado>0)
			{
			   $ld_porc_pagado=($ld_pagado*100)/$ld_causado;
		    }
            else
			{
			   $ld_porc_pagado=0;
			}
			
		    $ld_total_asignado=$ld_asignado;
		    $ld_total_precompromiso=$ld_total_precompromiso+$ld_precompromiso;
		    $ld_total_compromiso=$ld_total_compromiso+$ld_compromiso;
		    $ld_total_causado=$ld_total_causado+$ld_causado;
		    $ld_total_pagado=$ld_total_pagado+$ld_pagado;
			
		   
		    $ld_total_precompromiso_general=$ld_total_precompromiso_general+$ld_precompromiso;
		    $ld_total_compromiso_general=$ld_total_compromiso_general+$ld_compromiso;
		    $ld_total_causado_general=$ld_total_causado_general+$ld_causado;
		    $ld_total_pagado_general=$ld_total_pagado_general+$ld_pagado;
			
		    if($ls_cuenta_ant=="")
		    {
				$li_row=$li_row+1;
				$ls_cuenta_ant=$ls_spg_cuenta_ant;
				$lo_hoja->write($li_row, 0, "Cuenta",$lo_titulo);
				$lo_hoja->write($li_row, 1, $ls_cuenta_ant,$lo_datacenter);
				$li_row=$li_row+1;
				$lo_hoja->write($li_row, 0, "Denominacion",$lo_titulo);
				$lo_hoja->write($li_row, 1, $ls_denominacion,$lo_libro->addformat(array('font'=>'Verdana','align'=>'left','size'=>'9')));
				$li_row=$li_row+1;
				$lo_hoja->write($li_row, 0, "Programatica",$lo_titulo);
				$lo_hoja->write($li_row, 1, "Descripcion",$lo_titulo);
				$lo_hoja->write($li_row, 2, "Asignado",$lo_titulo);
				$lo_hoja->write($li_row, 3, "Pre Comprometido",$lo_titulo);
				$lo_hoja->write($li_row, 4, "Comprometido",$lo_titulo);
				$lo_hoja->write($li_row, 5, "%Comprometido",$lo_titulo);
				$lo_hoja->write($li_row, 6, "Causado",$lo_titulo);
				$lo_hoja->write($li_row, 7, "%Causado",$lo_titulo);
				$lo_hoja->write($li_row, 8, "Pagado",$lo_titulo);
				$lo_hoja->write($li_row, 9, "%Pagado",$lo_titulo);
		    }

		    $li_row=$li_row+1;
		    $lo_hoja->write($li_row, 0, $ls_programatica,$lo_dataleft);
		    $lo_hoja->write($li_row, 1, $ls_descripcion." ",$lo_dataleft);
		    $lo_hoja->write($li_row, 2, $ld_asignado,$lo_dataright);
		    $lo_hoja->write($li_row, 3, $ld_precompromiso,$lo_dataright);
		    $lo_hoja->write($li_row, 4, $ld_compromiso,$lo_dataright);
		    $lo_hoja->write($li_row, 5, $ld_porc_comprometido,$lo_dataright);
		    $lo_hoja->write($li_row, 6, $ld_causado,$lo_dataright);
		    $lo_hoja->write($li_row, 7, $ld_porc_causado,$lo_dataright);
		    $lo_hoja->write($li_row, 8, $ld_pagado,$lo_dataright);
		    $lo_hoja->write($li_row, 9, $ld_porc_pagado,$lo_dataright);
			
		    if (!empty($ls_spg_cuenta_next))
		    {
			  $li_row=$li_row+1;
			  $lo_hoja->write($li_row, 1, "Total",$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
		      $lo_hoja->write($li_row, 2, $ld_total_asignado_general,$lo_dataright);
		      $lo_hoja->write($li_row, 3, $ld_total_precompromiso_general,$lo_dataright);
		      $lo_hoja->write($li_row, 4, $ld_total_compromiso_general,$lo_dataright);
		      $lo_hoja->write($li_row, 6, $ld_total_causado_general,$lo_dataright);
		      $lo_hoja->write($li_row, 8, $ld_total_pagado_general,$lo_dataright);
			  $ls_cuenta_next="";
			  $ls_cuenta_ant="";
		    }//if
	    }//for
		$lo_libro->close();
		header("Content-Type: application/x-msexcel; name=\"spg_ejecutado_por_partida.xls\"");
		header("Content-Disposition: inline; filename=\"spg_ejecutado_por_partida.xls\"");
		$fh=fopen($lo_archivo, "rb");
		fpassthru($fh);
		unlink($lo_archivo);
		print("<script language=JavaScript>");
		print(" close();");
		print("</script>");
	}//else
?> 