<?php
    session_start();   
	ini_set('memory_limit','256M');
	ini_set('max_execution_time ','0');
   //--------------------------------------------------------------------------------------------------------------------------------

   //---------------------------------------------------------------------------------------------------------------------------
   // para crear el libro excel
		require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
		require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
		$lo_archivo =  tempnam("/tmp", "spg_acumulado_x_cuentas.xls");
		$lo_libro = &new writeexcel_workbookbig($lo_archivo);
		$lo_hoja = &$lo_libro->addworksheet();
	//---------------------------------------------------------------------------------------------------------------------------
	
   //------------------------------------------------------------------------------------------------------------------------------
		require_once("sigesp_spg_funciones_reportes.php");
		require_once("sigesp_spg_reporte.php");
		require_once("../../shared/class_folder/class_funciones.php");
		require_once("../../shared/class_folder/class_fecha.php");
		$io_function_report = new sigesp_spg_funciones_reportes();
		$io_report          = new sigesp_spg_reporte();
		$io_funciones       = new class_funciones();			
		$io_fecha           = new class_fecha();
		$ls_tipoformato     = $_GET["tipoformato"];
	    $ls_estclades       = $_GET["estclades"];
	    $ls_estclahas       = $_GET["estclahas"];
//-----------------------------------------------------------------------------------------------------------------------------
		 global $ls_tipoformato;
		 global $la_data_tot_bsf;
		 global $la_data_tot;
		 if($ls_tipoformato==1)
		 {
			require_once("sigesp_spg_reporte_bsf.php");
			$io_report = new sigesp_spg_reporte_bsf();
		 }
		 else
		 {
			require_once("sigesp_spg_reporte.php");
		    $io_report = new sigesp_spg_reporte();
		 }	
		 	
		 require_once("../../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
		 $io_rcbsf= new sigesp_c_reconvertir_monedabsf();
		 $li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		 $li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		 $li_redconmon=$_SESSION["la_empresa"]["redconmon"];
   //--------------------------------------------------  Parámetros para Filtar el Reporte  --------------------------------------
		$ldt_periodo        = $_SESSION["la_empresa"]["periodo"];
		$li_ano             = substr($ldt_periodo,0,4);
		$li_estmodest       = $_SESSION["la_empresa"]["estmodest"];
		$ls_codestpro1_min  = $_GET["codestpro1"];
		$ls_codestpro2_min  = $_GET["codestpro2"];
		$ls_codestpro3_min  = $_GET["codestpro3"];
		$ls_codestpro1h_max = $_GET["codestpro1h"];
		$ls_codestpro2h_max = $_GET["codestpro2h"];
		$ls_codestpro3h_max = $_GET["codestpro3h"];
		
		
		if($li_estmodest==1)
		{
			$ls_codestpro4_min  = "0000000000000000000000000";
			$ls_codestpro5_min  = "0000000000000000000000000";
			$ls_codestpro4h_max = "0000000000000000000000000";
			$ls_codestpro5h_max = "0000000000000000000000000";
			if(($ls_codestpro1_min=="")&&($ls_codestpro2_min=="")&&($ls_codestpro3_min==""))
			{
			  if($io_function_report->uf_spg_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,
			                                                                 $ls_codestpro3_min,$ls_codestpro4_min,
			                                                                 $ls_codestpro5_min,$ls_estclades))
			  {
					$ls_codestpro1  = $ls_codestpro1_min;
					$ls_codestpro2  = $ls_codestpro2_min;
					$ls_codestpro3  = $ls_codestpro3_min;
					$ls_codestpro4  = $ls_codestpro4_min;
					$ls_codestpro5  = $ls_codestpro5_min;
			  }
			}
			else
			{
					$ls_codestpro1  = $ls_codestpro1_min;
					$ls_codestpro2  = $ls_codestpro2_min;
					$ls_codestpro3  = $ls_codestpro3_min;
					$ls_codestpro4  = $ls_codestpro4_min;
					$ls_codestpro5  = $ls_codestpro5_min;
			}
			if(($ls_codestpro1h_max=="")&&($ls_codestpro2h_max=="")&&($ls_codestpro3h_max==""))
			{
			  if($io_function_report->uf_spg_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
																			 $ls_codestpro3h_max,$ls_codestpro4h_max,
																			 $ls_codestpro5h_max,$ls_estclahas))
			  {
					$ls_codestpro1h  = $ls_codestpro1h_max;
					$ls_codestpro2h  = $ls_codestpro2h_max;
					$ls_codestpro3h  = $ls_codestpro3h_max;
					$ls_codestpro4h  = $ls_codestpro4h_max;
					$ls_codestpro5h  = $ls_codestpro5h_max;
			  }
			}
			else
			{
					$ls_codestpro1h  = $ls_codestpro1h_max;
					$ls_codestpro2h  = $ls_codestpro2h_max;
					$ls_codestpro3h  = $ls_codestpro3h_max;
					$ls_codestpro4h  = $ls_codestpro4h_max;
					$ls_codestpro5h  = $ls_codestpro5h_max;
			}
		}
		elseif($li_estmodest==2)
		{
			$ls_codestpro4_min = $_GET["codestpro4"];
			$ls_codestpro5_min = $_GET["codestpro5"];
			$ls_codestpro4h_max = $_GET["codestpro4h"];
			$ls_codestpro5h_max = $_GET["codestpro5h"];
			
			if(($ls_codestpro1_min=='**') ||($ls_codestpro1_min==''))
			{
				$ls_codestpro1_min='';
			}
			else
			{
			    $ls_codestpro1_min  = $io_funciones->uf_cerosizquierda($ls_codestpro1_min,20);
			}
			if(($ls_codestpro2_min=='**') ||($ls_codestpro2_min==''))
			{
				$ls_codestpro2_min='';
			}
			else
			{
				$ls_codestpro2_min  = $io_funciones->uf_cerosizquierda($ls_codestpro2_min,6);
			
			}
			if(($ls_codestpro3_min=='**')||($ls_codestpro3_min==''))
			{
				$ls_codestpro3_min='';
			}
			else
			{
			
				$ls_codestpro3_min  = $io_funciones->uf_cerosizquierda($ls_codestpro3_min,3);
			}
			if(($ls_codestpro4_min=='**') ||($ls_codestpro4_min==''))
			{
				$ls_codestpro4_min='';
			}
			else
			{
				$ls_codestpro4_min  = $io_funciones->uf_cerosizquierda($ls_codestpro4_min,2);
	
			
			}
			if(($ls_codestpro5_min=='**') ||($ls_codestpro5_min==''))
			{
				$ls_codestpro5_min='';
			}else
			{
					$ls_codestpro5_min  = $io_funciones->uf_cerosizquierda($ls_codestpro5_min,2);
			}
			
			
			if(($ls_codestpro1h_max=='**')||($ls_codestpro1h_max==''))
			{
				$ls_codestpro1h_max='';
			}
			else
			{
				$ls_codestpro1h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro1h_max,20);
			}
			if(($ls_codestpro2h_max=='**') ||($ls_codestpro2h_max==''))
			{
				$ls_codestpro2h_max='';
			}else
			{
				$ls_codestpro2h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro2h_max,6);
			}
			if(($ls_codestpro3h_max=='**') ||($ls_codestpro3h_max==''))
			{
				$ls_codestpro3h_max='';
			}else
			{
				$ls_codestpro3h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro3h_max,3);
			}
			if(($ls_codestpro4h_max=='**')  ||($ls_codestpro4h_max==''))
			{
				$ls_codestpro4h_max='';
			}else
			{
				$ls_codestpro4h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro4h_max,2);
			}
			if(($ls_codestpro5h_max=='**')  || ($ls_codestpro5h_max==''))
			{
				$ls_codestpro5h_max='';
			}else
			{
				$ls_codestpro5h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro5h_max,2);
			}
			
			if(($ls_codestpro1_min=="")||($ls_codestpro2_min=="")||($ls_codestpro3_min=="")||($ls_codestpro4_min=="")||($ls_codestpro5_min==""))
			{
			  if($io_function_report->uf_spg_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,
			                                                                 $ls_codestpro3_min,$ls_codestpro4_min,
																			 $ls_codestpro5_min,$ls_estclades))
			  {
					$ls_codestpro1  = $ls_codestpro1_min;
					$ls_codestpro2  = $ls_codestpro2_min;
					$ls_codestpro3  = $ls_codestpro3_min;
					$ls_codestpro4  = $ls_codestpro4_min;
					$ls_codestpro5  = $ls_codestpro5_min;
			  }
			}
			else
			{
					$ls_codestpro1  = $ls_codestpro1_min;
					$ls_codestpro2  = $ls_codestpro2_min;
					$ls_codestpro3  = $ls_codestpro3_min;
					$ls_codestpro4  = $ls_codestpro4_min;
					$ls_codestpro5  = $ls_codestpro5_min;
			}
			if(($ls_codestpro1h_max=="")||($ls_codestpro2h_max=="")||($ls_codestpro3h_max=="")||($ls_codestpro4h_max=="")||($ls_codestpro5h_max==""))
			{
			  if($io_function_report->uf_spg_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
			                                                                 $ls_codestpro3h_max,$ls_codestpro4h_max,
																			 $ls_codestpro5h_max,$ls_estclahas))
			  {
				$ls_codestpro1h  = $ls_codestpro1h_max;
				$ls_codestpro2h  = $ls_codestpro2h_max;
				$ls_codestpro3h  = $ls_codestpro3h_max;
				$ls_codestpro4h  = $ls_codestpro4h_max;
				$ls_codestpro5h  = $ls_codestpro5h_max;
			  }
			}
			else
			{
				$ls_codestpro1h  = $ls_codestpro1h_max;
				$ls_codestpro2h  = $ls_codestpro2h_max;
				$ls_codestpro3h  = $ls_codestpro3h_max;
				$ls_codestpro4h  = $ls_codestpro4h_max;
				$ls_codestpro5h  = $ls_codestpro5h_max;
			}
		}	
		
		$ls_cmbmesdes   = $_GET["cmbmesdes"];
		$ldt_fecini     = $li_ano."-".$ls_cmbmesdes."-01";
		$ldt_fecini_rep = "01/".$ls_cmbmesdes."/".$li_ano;
		$ls_cmbmeshas   = $_GET["cmbmeshas"];
		$ls_mes         = $ls_cmbmeshas;
		$ls_ano         = $li_ano;
		$fecfin         = $io_fecha->uf_last_day($ls_mes,$ls_ano);
		$ldt_fecfin     = $io_funciones->uf_convertirdatetobd($fecfin);
		$cmbnivel       = $_GET["cmbnivel"];
		if($cmbnivel=="s1")
		{
          $ls_cmbnivel="1";
		}
		else
		{
          $ls_cmbnivel=$cmbnivel;
		}
        $ls_subniv=$_GET["checksubniv"];
		if($ls_subniv==1)
		{
		  $lb_subniv=true;
		}
		else
		{
		  $lb_subniv=false;
		}
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
				print(" alert('No hay cuentas presupuestarias');"); 
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
				print(" alert('No hay cuentas presupuestarias');"); 
				print(" close();");
				print("</script>");
		   }
		}
		else
		{
		    $ls_cuentahas=$ls_cuentahas_max;
		}
	    $ls_codfuefindes=$_GET["txtcodfuefindes"];
	    $ls_codfuefinhas=$_GET["txtcodfuefinhas"];
		if (($ls_codfuefindes=='')&&($ls_codfuefindes==''))
		{
		   if($io_function_report->uf_spg_select_fuentefinanciamiento(&$ls_minfuefin,&$ls_maxfuefin))
		   {
		     $ls_codfuefindes=$ls_minfuefin;
		     $ls_codfuefinhas=$ls_maxfuefin;
		   } 
		}
    //------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
	  $ls_codestpro1  = str_pad($ls_codestpro1_min,25,0,0);
	  $ls_codestpro2  = str_pad($ls_codestpro2_min,25,0,0);
	  $ls_codestpro3  = str_pad($ls_codestpro3_min,25,0,0);
	  $ls_codestpro4  = str_pad($ls_codestpro4_min,25,0,0);
	  $ls_codestpro5  = str_pad($ls_codestpro5_min,25,0,0);
	  $ls_codestpro1h = str_pad($ls_codestpro1h_max,25,0,0);
	  $ls_codestpro2h = str_pad($ls_codestpro2h_max,25,0,0);
	  $ls_codestpro3h = str_pad($ls_codestpro3h_max,25,0,0);
	  $ls_codestpro4h = str_pad($ls_codestpro4h_max,25,0,0);
	  $ls_codestpro5h = str_pad($ls_codestpro5h_max,25,0,0);
      $lb_valido=$io_report->uf_spg_reporte_ejecucion_financiera_mensual($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
	                                                        $ls_codestpro5,$ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,
	                                                        $ls_codestpro4h,$ls_codestpro5h,$ldt_fecini,$ldt_fecfin,$ls_cmbnivel,$ls_cuentades,$ls_cuentahas,
															$ls_codfuefindes,$ls_codfuefinhas,$ls_estclades,$ls_estclahas,$rs_data);
	//----------------------------------------------------  Parámetros del encabezado  ---------------------------------------------
		$ls_programatica_desde=$ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3.'-'.$ls_codestpro4.'-'.$ls_codestpro5;
		$ls_programatica_hasta=$ls_codestpro1h.'-'.$ls_codestpro2h.'-'.$ls_codestpro3h.'-'.$ls_codestpro4h.'-'.$ls_codestpro5h;
		$ls_titulo="EJECUCION PRESUPUESTARIA MENSUAL DEL INGRESO DESDE FECHA  ".$ldt_fecini_rep."  HASTA ".$fecfin." ";  
		$ls_titulo1="DESDE LA PROGRAMATICA  ".$ls_programatica_desde."  HASTA  ".$ls_programatica_hasta." ";  
    //------------------------------------------------------------------------------------------------------------------------------
	
	if ($lb_valido==false) // Existe algún error ó no hay registros
	   {
		 print("<script language=JavaScript>");
		 print(" alert('No hay nada que Reportar');"); 
		 print(" close();");
		 print("</script>");
	   }
	else // Imprimimos el reporte
	   {
		/////////////////////////////////         SEGURIDAD               ///////////////////////////////////
		$ls_desc_event="Se genero el Reporte Ejecución Presupuestaria Mensual de Gasto desde la fecha ".$ldt_fecini_rep." hasta ".$fecfin." ,Desde la programatica ".$ls_programatica_desde."  hasta ".$ls_programatica_hasta;
		$io_function_report->uf_load_seguridad_reporte("SPG","sigesp_spg_r_ejecucion_financiera_mensual.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               ///////////////////////////////////
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
		$lo_hoja->write(1, 3, $ls_titulo1,$lo_encabezado);
	    $ls_spg_cuenta_ant="";
		$ld_total_asignado=0;
		$ld_total_aumento=0;
		$ld_total_disminucion=0;
		$ld_total_monto_actualizado=0;
		$ld_total_compromiso=0;
		$ld_total_precompromiso=0;
		$ld_total_compromiso=0;
		$ld_total_saldo_comprometer=0;
		$ld_total_causado=0;
		$ld_total_pagado=0;
		$ld_total_por_paga=0;
		$li_row=2;
		$li_tot=$rs_data->RecordCount();
		$z=0;
		while(!$rs_data->EOF)
		{
		      $ls_spg_cuenta=trim($rs_data->fields["spg_cuenta"]);
		      $ls_denominacion=trim($rs_data->fields["denominacion"]);
			  $ls_nivel=$rs_data->fields["nivel"];
			  $ld_asignado=$rs_data->fields["asignado"];
			  $ld_aumento=$rs_data->fields["aumento"];
			  $ld_disminucion=$rs_data->fields["disminucion"];
			  $ld_precompromiso=$rs_data->fields["precompromiso"];
			  $ld_compromiso=$rs_data->fields["compromiso"];
			  $ld_causado=$rs_data->fields["causado"];
			  $ld_pagado=$rs_data->fields["pagado"];
			  
			  $ld_monto_actualizado=$rs_data->fields["monto_actualizado"];
			  $ld_saldo_comprometer=$rs_data->fields["saldo_comprometer"];
			  $ld_por_paga=$rs_data->fields["por_pagar"];
			  $ls_status=$rs_data->fields["status"];
			  
			  
			  $lb_valido2=$io_report->uf_spg_reporte_detalle_ejecucion_financiera_mensual($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
			                                                                   $ls_codestpro4,$ls_codestpro5,
																			   $ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,
	                                                                           $ls_codestpro4h,$ls_codestpro5h,
																			   $ls_estclades,$ls_estclahas,$ls_spg_cuenta,$ldt_fecini,
															                   $ldt_fecfin,$rs_data2);
															
					
			  while((!$rs_data2->EOF)&&($lb_valido2))
			  {
				  $ld_aumento=$ld_aumento+$rs_data2->fields["aumento"]; 
				  $ld_disminucion=$ld_disminucion+$rs_data2->fields["disminucion"];
				  $ld_precompromiso=$ld_precompromiso+$rs_data2->fields["precompromiso"];
				  $ld_compromiso=$ld_compromiso+$rs_data2->fields["compromiso"];
				  $ld_causado=$ld_causado+$rs_data2->fields["causado"];
				  $ld_pagado=$ld_pagado+$rs_data2->fields["pagado"];				
				  $ld_monto_actualizado=$ld_asignado+$ld_aumento-$ld_disminucion;				 
				  $ld_saldo_comprometer=$ld_asignado+$ld_aumento-$ld_disminucion-$ld_precompromiso-$ld_compromiso;
				  $ld_por_paga=$ld_causado-$ld_pagado;
			  	  $rs_data2->MoveNext();	
			  }
			  
			  if($ls_nivel==1)
			  {
			  	  $ld_total_asignado=$ld_total_asignado+$ld_asignado; 
				  $ld_total_aumento=$ld_total_aumento+$ld_aumento;
				  $ld_total_disminucion=$ld_total_disminucion+$ld_disminucion;
				  $ld_total_monto_actualizado=$ld_total_monto_actualizado+$ld_monto_actualizado;
				  $ld_total_precompromiso=$ld_total_precompromiso+$ld_precompromiso;
				  $ld_total_compromiso=$ld_total_compromiso+$ld_compromiso;
				  $ld_total_saldo_comprometer=$ld_total_saldo_comprometer+$ld_saldo_comprometer;
				  $ld_total_causado=$ld_total_causado+$ld_causado;
				  $ld_total_pagado=$ld_total_pagado+$ld_pagado;
				  $ld_total_por_paga=$ld_total_por_paga+$ld_por_paga;
			  }
			  if($ls_spg_cuenta_ant=="")
			  {
				$li_row=$li_row+1;
	 			$ls_spg_cuenta_ant=$ls_spg_cuenta;
				$lo_hoja->write($li_row, 0, "Cuenta",$lo_titulo);
				$lo_hoja->write($li_row, 1, "Denominacion",$lo_titulo);
				$lo_hoja->write($li_row, 2, "Asignado",$lo_titulo);
				$lo_hoja->write($li_row, 3, "Aumento",$lo_titulo);
				$lo_hoja->write($li_row, 4, "Disminucion",$lo_titulo);
				$lo_hoja->write($li_row, 5, "Monto Actualizado",$lo_titulo);
				$lo_hoja->write($li_row, 6, "Pre Comprometido",$lo_titulo);
				$lo_hoja->write($li_row, 7, "Comprometido",$lo_titulo);
				$lo_hoja->write($li_row, 8, "Saldo Por Comprometer",$lo_titulo);
				$lo_hoja->write($li_row, 9, "Causado",$lo_titulo);
				$lo_hoja->write($li_row, 10, "Pagado",$lo_titulo);
				$lo_hoja->write($li_row, 11, "Por Pagar",$lo_titulo);
			 }
			 $li_row=$li_row+1;
			 $lo_hoja->write($li_row, 0, $ls_spg_cuenta,$lo_datacenter);
			 $lo_hoja->write($li_row, 1, $ls_denominacion." ",$lo_dataleft);
			 $lo_hoja->write($li_row, 2, $ld_asignado,$lo_dataright);
			 $lo_hoja->write($li_row, 3, $ld_aumento,$lo_dataright);
			 $lo_hoja->write($li_row, 4, $ld_disminucion,$lo_dataright);
			 $lo_hoja->write($li_row, 5, $ld_monto_actualizado,$lo_dataright);
			 $lo_hoja->write($li_row, 6, $ld_precompromiso,$lo_dataright);
			 $lo_hoja->write($li_row, 7, $ld_compromiso,$lo_dataright);
			 $lo_hoja->write($li_row, 8, $ld_saldo_comprometer,$lo_dataright);
			 $lo_hoja->write($li_row, 9, $ld_causado,$lo_dataright);
			 $lo_hoja->write($li_row, 10, $ld_pagado,$lo_dataright);
			 $lo_hoja->write($li_row, 11, $ld_por_paga,$lo_dataright);

			 if($z==($li_tot-1))
			 {
				
					$li_row=$li_row+1;
					$lo_hoja->write($li_row, 1, "Total Bs.",$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
					$lo_hoja->write($li_row, 2, $ld_total_asignado,$lo_dataright);
					$lo_hoja->write($li_row, 3, $ld_total_aumento,$lo_dataright);
					$lo_hoja->write($li_row, 4, $ld_total_disminucion,$lo_dataright);
					$lo_hoja->write($li_row, 5, $ld_total_monto_actualizado,$lo_dataright);
					$lo_hoja->write($li_row, 6, $ld_total_precompromiso,$lo_dataright);
					$lo_hoja->write($li_row, 7, $ld_total_compromiso,$lo_dataright);
					$lo_hoja->write($li_row, 8, $ld_total_saldo_comprometer,$lo_dataright);
					$lo_hoja->write($li_row, 9, $ld_total_causado,$lo_dataright);
					$lo_hoja->write($li_row, 10, $ld_total_pagado,$lo_dataright);
					$lo_hoja->write($li_row, 11, $ld_total_por_paga,$lo_dataright);
					$ls_spg_cuenta_ant="";
				
			 }//if
			 
			$rs_data->MoveNext();	
			$z=$z+1;
		}//fin del while 
		$lo_libro->close();
		header("Content-Type: application/x-msexcel; name=\"spg_ejecucion_financiera_mensual.xls\"");
		header("Content-Disposition: inline; filename=\"spg_acumulado_x_cuentas.xls\"");
		$fh=fopen($lo_archivo, "rb");
		fpassthru($fh);
		unlink($lo_archivo);
		print("<script language=JavaScript>");
		print(" close();");
		print("</script>");
	}
?> 