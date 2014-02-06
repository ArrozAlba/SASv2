<?php
    session_start();
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "</script>";
	}
	ini_set('memory_limit','1024M');
	ini_set('max_execution_time','0');

	
//------------------------------------------------------------------------------------------------------------------------------
		require_once ("sigesp_spg_class_tcpdf.php");
		require_once("sigesp_spg_reporte.php");
		$io_report = new sigesp_spg_reporte();
		require_once("sigesp_spg_funciones_reportes.php");
		$io_function_report = new sigesp_spg_funciones_reportes();
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
		require_once("sigesp_spg_reporte.php");
		$io_report = new sigesp_spg_reporte();
//--------------------------------------------------  Parámetros para Filtar el Reporte  ---------------------------------------

		$ldt_periodo		= $_SESSION["la_empresa"]["periodo"];
		$li_ano				= substr($ldt_periodo,0,4);
		$li_estmodest		= $_SESSION["la_empresa"]["estmodest"];
		$ls_codestpro1_min  = $_GET["codestpro1"];
		$ls_codestpro2_min  = $_GET["codestpro2"];
		$ls_codestpro3_min  = $_GET["codestpro3"];
		$ls_codestpro1h_max = $_GET["codestpro1h"];
		$ls_codestpro2h_max = $_GET["codestpro2h"];
		$ls_codestpro3h_max = $_GET["codestpro3h"];
	    $ls_estclades       = $_GET["estclades"];
	    $ls_estclahas       = $_GET["estclahas"];
		$ls_mostrar         = $_GET["mostrar"];

		$ls_tipoformato=1;
		if($li_estmodest==1)
		{
			$ls_codestpro4_min =  "0000000000000000000000000";
			$ls_codestpro5_min =  "0000000000000000000000000";
			$ls_codestpro4h_max = "0000000000000000000000000";
			$ls_codestpro5h_max = "0000000000000000000000000";
			if(($ls_codestpro1_min=="")&&($ls_codestpro2_min=="")&&($ls_codestpro3_min==""))
			{
			  if($io_function_report->uf_spg_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,
			                                                                 $ls_codestpro3_min,$ls_codestpro4_min,
			                                                                 $ls_codestpro5_min,$ls_estclades))
			  {
					$ls_codestpro1  = trim($ls_codestpro1_min);
					$ls_codestpro2  = trim($ls_codestpro2_min);
					$ls_codestpro3  = trim($ls_codestpro3_min);
					$ls_codestpro4  = trim($ls_codestpro4_min);
					$ls_codestpro5  = trim($ls_codestpro5_min);
			  }
			}
			else
			{
			 if($ls_codestpro1_min<>"")
		     {
			  $ls_codestpro1_min=$io_funciones->uf_cerosizquierda($ls_codestpro1_min,25);
			  $ls_codestpro1= $ls_codestpro1_min;
			 }
		     else
		     {
			  $io_function_report->uf_spg_reporte_select_min_codestpro1(&$ls_codestpro1_min,&$ls_estclades);
			  $ls_codestpro1=$ls_codestpro1_min;
		     }

			 if($ls_codestpro2_min<>"")
		     {
			  $ls_codestpro2_min=$io_funciones->uf_cerosizquierda($ls_codestpro2_min,25);
			  $ls_codestpro2= $ls_codestpro2_min;
			 }
		     else
		     {
			  $io_function_report->uf_spg_reporte_select_min_codestpro2($ls_codestpro1_min,&$ls_codestpro2_min,$ls_estclades);
			  $ls_codestpro1=$ls_codestpro1_min;
		     }

			 if($ls_codestpro1_min<>"")
		     {
			  $ls_codestpro1_min=$io_funciones->uf_cerosizquierda($ls_codestpro1_min,25);
			  $ls_codestpro1= $ls_codestpro1_min;
			 }
		     else
		     {
			  $io_function_report->uf_spg_reporte_select_min_codestpro3($ls_codestpro1_min,$ls_codestpro2_min,&$ls_codestpro3_min,$ls_estclades);
			  $ls_codestpro1=$ls_codestpro1_min;
		     }

					$ls_codestpro1  = trim($ls_codestpro1_min);
					$ls_codestpro2  = trim($ls_codestpro2_min);
					$ls_codestpro3  = trim($ls_codestpro3_min);
					$ls_codestpro4  = trim($ls_codestpro4_min);
					$ls_codestpro5  = trim($ls_codestpro5_min);
			}
			if(($ls_codestpro1h_max=="")&&($ls_codestpro2h_max=="")&&($ls_codestpro3h_max==""))
			{
			  if($io_function_report->uf_spg_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
																			 $ls_codestpro3h_max,$ls_codestpro4h_max,
																			 $ls_codestpro5h_max,$ls_estclahas))
			  {
					$ls_codestpro1h  = trim($ls_codestpro1h_max);
					$ls_codestpro2h  = trim($ls_codestpro2h_max);
					$ls_codestpro3h  = trim($ls_codestpro3h_max);
					$ls_codestpro4h  = trim($ls_codestpro4h_max);
					$ls_codestpro5h  = trim($ls_codestpro5h_max);
			  }
			}
			else
			{
					$ls_codestpro1h  = trim($ls_codestpro1h_max);
					$ls_codestpro2h  = trim($ls_codestpro2h_max);
					$ls_codestpro3h  = trim($ls_codestpro3h_max);
					$ls_codestpro4h  = trim($ls_codestpro4h_max);
					$ls_codestpro5h  = trim($ls_codestpro5h_max);
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
			    $ls_codestpro1_min  = $io_funciones->uf_cerosizquierda($ls_codestpro1_min,25);
			}
			if(($ls_codestpro2_min=='**') ||($ls_codestpro2_min==''))
			{
				$ls_codestpro2_min='';
			}
			else
			{
				$ls_codestpro2_min  = $io_funciones->uf_cerosizquierda($ls_codestpro2_min,25);

			}
			if(($ls_codestpro3_min=='**')||($ls_codestpro3_min==''))
			{
				$ls_codestpro3_min='';
			}
			else
			{

				$ls_codestpro3_min  = $io_funciones->uf_cerosizquierda($ls_codestpro3_min,25);
			}
			if(($ls_codestpro4_min=='**') ||($ls_codestpro4_min==''))
			{
				$ls_codestpro4_min='';
			}
			else
			{
				$ls_codestpro4_min  = $io_funciones->uf_cerosizquierda($ls_codestpro4_min,25);
			}
			if(($ls_codestpro5_min=='**') ||($ls_codestpro5_min==''))
			{
				$ls_codestpro5_min='';
			}
			else
			{
					$ls_codestpro5_min  = $io_funciones->uf_cerosizquierda($ls_codestpro5_min,25);
			}


			if(($ls_codestpro1h_max=='**')||($ls_codestpro1h_max==''))
			{
				$ls_codestpro1h_max='';
			}
			else
			{
				$ls_codestpro1h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro1h_max,25);
			}
			if(($ls_codestpro2h_max=='**') ||($ls_codestpro2h_max==''))
			{
				$ls_codestpro2h_max='';
			}else
			{
				$ls_codestpro2h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro2h_max,25);
			}
			if(($ls_codestpro3h_max=='**') ||($ls_codestpro3h_max==''))
			{
				$ls_codestpro3h_max='';
			}else
			{
				$ls_codestpro3h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro3h_max,25);
			}
			if(($ls_codestpro4h_max=='**')  ||($ls_codestpro4h_max==''))
			{
				$ls_codestpro4h_max='';
			}else
			{
				$ls_codestpro4h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro4h_max,25);
			}
			if(($ls_codestpro5h_max=='**')  || ($ls_codestpro5h_max==''))
			{
				$ls_codestpro5h_max='';
			}else
			{
				$ls_codestpro5h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro5h_max,25);
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
        $fecdes=$_GET["txtfecdes"];
	    $ldt_fecdes=$io_funciones->uf_convertirdatetobd($fecdes);
        $fechas=$_GET["txtfechas"];
	    $ldt_fechas=$io_funciones->uf_convertirdatetobd($fechas);
		$ls_orden=$_GET["rborden"];
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
		$ls_programatica_desde=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
		$ls_programatica_hasta=$ls_codestpro1h.$ls_codestpro2h.$ls_codestpro3h.$ls_codestpro4h.$ls_codestpro5h;
		/////////////////////////////////         SEGURIDAD               ///////////////////////////////////
		$ls_desc_event="Solicitud de Reporte Mayor Analitico desde la fecha ".$fecdes." hasta ".$fechas." ,Desde la programatica ".$ls_programatica_desde."  hasta ".$ls_programatica_hasta." , Desde la Cuenta ".$ls_cuentades." hasta la ".$ls_cuentahas;
		$io_function_report->uf_load_seguridad_reporte("SPG","sigesp_spg_r_mayor_analitico.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               ///////////////////////////////////
//----------------------------------------------------  Parámetros del encabezado  ---------------------------------------------
		$ls_titulo="                                                                                                            MAYOR ANALITICO  DESDE  ".$fecdes."  AL  ".$fechas." ";
//--------------------------------------------------------------------------------------------------------------------------------
  // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )
 	 $ls_codestpro1  = $io_funciones->uf_cerosizquierda($ls_codestpro1_min,25);
	 $ls_codestpro2  = $io_funciones->uf_cerosizquierda($ls_codestpro2_min,25);
	 $ls_codestpro3  = $io_funciones->uf_cerosizquierda($ls_codestpro3_min,25);
	 $ls_codestpro4  = $io_funciones->uf_cerosizquierda($ls_codestpro4_min,25);
	 $ls_codestpro5  = $io_funciones->uf_cerosizquierda($ls_codestpro5_min,25);

	 $ls_codestpro1h  = $io_funciones->uf_cerosizquierda($ls_codestpro1h_max,25);
	 $ls_codestpro2h  = $io_funciones->uf_cerosizquierda($ls_codestpro2h_max,25);
	 $ls_codestpro3h  = $io_funciones->uf_cerosizquierda($ls_codestpro3h_max,25);
	 $ls_codestpro4h  = $io_funciones->uf_cerosizquierda($ls_codestpro4h_max,25);
	 $ls_codestpro5h  = $io_funciones->uf_cerosizquierda($ls_codestpro5h_max,25);


     $lb_valido=$io_report->uf_spg_reporte_select_mayor_analitico($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
	                                                              $ls_codestpro5,$ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,
																  $ls_codestpro4h,$ls_codestpro5h,$ldt_fecdes,$ldt_fechas,
																  $ls_cuentades,$ls_cuentahas,$ls_orden,&$rs_data,$ls_codfuefindes,
																  $ls_codfuefinhas,$ls_estclades,$ls_estclahas);

	 if(($lb_valido==false)||($rs_data->RecordCount()==0) ) // Existe algún error ó no hay registros
	 {
		print("<script language=JavaScript>");
	    print(" alert('No hay nada que Reportar');");
		print(" close();");
		print("</script>");
	 }
	 else // Imprimimos el reporte
	 {
		error_reporting(E_ALL);
		set_time_limit(3600);
		$io_tcpdf= new sigesp_spg_class_tcpdf ("L", PDF_UNIT, "legal", true);
		$io_tcpdf->AliasNbPages();
		$io_tcpdf->SetFont("helvetica","BI",8);
		$io_tcpdf->SetHeaderData($_SESSION["ls_logo"], 18, $ls_titulo, date("d/m/Y").'  '.date("h:i a"));
		$io_tcpdf->SetTitle($ls_titulo);
		$io_tcpdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$io_tcpdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$io_tcpdf->SetMargins(2, PDF_MARGIN_TOP,2);
		$io_tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$io_tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$io_tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$io_tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$io_tcpdf->AliasNbPages();
		$io_tcpdf->AddPage();

		$io_tcpdf->SetFont("helvetica","B",8);
	 	$io_tcpdf->Cell(0,10,$ls_titulo,0,0,'C');

		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
		$ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
		$ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
		$ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
		$ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];

		while(!$rs_data->EOF)
		{
			$ld_total_asignado=0;
			$ld_total_aumento=0;
			$ld_total_disminucion=0;
			$ld_total_monto_actualizado=0;
			$ld_total_compromiso=0;
			$ld_total_precompromiso=0;
			$ld_total_compromiso=0;
			$ld_total_causado=0;
			$ld_total_pagado=0;
			$ld_total_por_paga=0;
            $ld_total_saldo_comprometer=0;
			$li_tmp=0;
			$ld_monto_actualizado=0;
			$ld_totspg_asignado=0;
			$ld_totspg_aumento=0;
			$ld_totspg_disminucion=0;
			$ld_totspg_monto_actualizado=0;
			$ld_totspg_compromiso=0;
			$ld_totspg_precompromiso=0;
			$ld_totspg_causado=0;
			$ld_totspg_pagado=0;
			$ld_totspg_por_pagar=0;
			$ls_programatica=$rs_data->fields["programatica"];
			$ls_estcla=substr($ls_programatica,-1);
		    $ls_codestpro1=substr($ls_programatica,0,25);
			$ls_denestpro1="";
		    $lb_valido=$io_report->uf_spg_reporte_select_denestpro1($ls_codestpro1,$ls_denestpro1,$ls_estcla);

		    if($lb_valido)
		    {
			  $ls_denestpro1=$ls_denestpro1;
		    }
		    $ls_codestpro2=substr($ls_programatica,25,25);
			if($lb_valido)
		    {
			  $ls_denestpro2="";
			  $lb_valido=$io_report->uf_spg_reporte_select_denestpro2($ls_codestpro1,$ls_codestpro2,$ls_denestpro2,$ls_estcla);
			  $ls_denestpro2=$ls_denestpro2;
		    }
		    $ls_codestpro3=substr($ls_programatica,50,25);
			if($lb_valido)
		    {
			  $ls_denestpro3="";
			  $lb_valido=$io_report->uf_spg_reporte_select_denestpro3($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_denestpro3,$ls_estcla);
			  $ls_denestpro3=$ls_denestpro3;
		    }
			if($li_estmodest==2)
			{
				$ls_codestpro4=substr($ls_programatica,75,25);
				if($lb_valido)
				{
				  $ls_denestpro4="";
				  $lb_valido=$io_report->uf_spg_reporte_select_denestpro4($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_denestpro4,$ls_estcla);
				  $ls_denestpro4=$ls_denestpro4;
				}
				$ls_codestpro5=substr($ls_programatica,100,25);
				if($lb_valido)
				{
				  $ls_denestpro5="";
				  $lb_valido=$io_report->uf_spg_reporte_select_denestpro5($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_denestpro5,$ls_estcla);
				  $ls_denestpro5=$ls_denestpro5;
				}
			    $ls_denestpro=utf8_encode($ls_denestpro1." , ".$ls_denestpro2." , ".$ls_denestpro3." , ".$ls_denestpro4." , ".$ls_denestpro5);
			    $ls_programatica=substr($ls_codestpro1,-$ls_loncodestpro1).substr($ls_codestpro2,-$ls_loncodestpro2).substr($ls_codestpro3,-$ls_loncodestpro3).substr($ls_codestpro4,-$ls_loncodestpro4).substr($ls_codestpro5,-$ls_loncodestpro5);

			}
			else
			{
                $ls_programatica=substr($ls_codestpro1,-$ls_loncodestpro1).substr($ls_codestpro2,-$ls_loncodestpro2).substr($ls_codestpro3,-$ls_loncodestpro3);
				$ls_denestpro = array();
				$ls_denestpro[0]=$ls_denestpro1;
				$ls_denestpro[1]=$ls_denestpro2;
				$ls_denestpro[2]=$ls_denestpro3;
			}
			$lb_valido=$io_report->uf_spg_reporte_mayor_analitico2($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
			                                                      $ls_codestpro5,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																  $ls_codestpro4,$ls_codestpro5,$ldt_fecdes,$ldt_fechas,
																  $ls_cuentades,$ls_cuentahas,$ls_orden,$ls_estcla,$rs_data2);

			if($lb_valido)
			{
			    $li_totrow_det=$rs_data2->RecordCount();
				if($li_totrow_det==0)
				{
				print("<script language=JavaScript>");
				print(" alert('No hay nada que Reportar');");
				print(" close();");
				print("</script>");
				}
				if($li_totrow_det>1)
				{
		           $io_tcpdf->uf_print_cabecera($ls_programatica,$ls_denestpro); // Imprimimos la cabecera del registro
				}
				$entro=false;
				$ls_antasig=0;
				$ls_antaum=0;
				$ls_antdis=0;
				$ls_antpre=0;
				$ls_antcom=0;
				$ls_antcau=0;
				$ls_antpagado=0;
				$ls_antpagar=0;
				$ls_antmon_act=0;
				while(!$rs_data2->EOF)
				{
				  $ls_programatica=$rs_data2->fields["codestpro1"].$rs_data2->fields["codestpro2"].$rs_data2->fields["codestpro3"].$rs_data2->fields["codestpro4"].$rs_data2->fields["codestpro5"];
				  $ls_codestpro1=$rs_data2->fields["codestpro1"];
				  $ls_codestpro2=$rs_data2->fields["codestpro2"];
				  $ls_codestpro3=$rs_data2->fields["codestpro3"];
				  $ls_codestpro4=$rs_data2->fields["codestpro4"];
			      $ls_codestpro5=$rs_data2->fields["codestpro5"];
				  $ls_estcla=$rs_data2->fields["estcla"];

				  $ls_nombre_prog=utf8_encode($rs_data2->fields["nombre_prog"]);

				  $ls_spg_cuenta=$rs_data2->fields["spg_cuenta"];

				  if (($li_tmp)<$li_totrow_det)
				  {
						$rs_data2->MoveNext();
						$ls_spg_cuenta_next=$rs_data2->fields["spg_cuenta"];
						$rs_data2->Move($li_tmp);
				  }
				  elseif(($li_tmp)==$li_totrow_det)
				  {
				        $ls_spg_cuenta_next=$rs_data2->fields["spg_cuenta"];

				  }

				  //PARA BUSCAR LOS SALDOS ANTERIORES
				  if  (!$entro)
				  {
				       $entro=true;
					   $lb_valido=$io_report->uf_spg_calcular_saldo_anterior ($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
					   														  $ls_codestpro4, $ls_codestpro5,$ldt_fecdes,
																	          $ls_spg_cuenta,$ls_estcla,$rs_data3);
						 $li_num=$rs_data3->RecordCount();

						 if (($li_num>=1) && ($lb_valido))
						 {
							$ld_asignado2=0;
							$ld_aumento2=0;
							$ld_disminucion2=0;
							$ld_precompromiso2=0;
							$ld_compromiso2=0;
							$ld_causado2=0;
							$ld_pagado2=0;
							$ld_por_paga2=0;
							$ld_monto_actualizado=0;
							while (!$rs_data3->EOF)
							{

								$ld_asignado2=$ld_asignado2+$rs_data3->fields["asignar"];
								$ld_aumento2=$ld_aumento2+$rs_data3->fields["aumento"];
								$ld_disminucion2=$ld_disminucion2+$rs_data3->fields["disminucion"];
								$ld_precompromiso2=$ld_precompromiso2+$rs_data3->fields["precompromiso"];
								$ld_compromiso2=$ld_compromiso2+$rs_data3->fields["compromiso"];
								$ld_causado2=$ld_causado2+$rs_data3->fields["causado"];
								$ld_pagado2=$ld_pagado2+$rs_data3->fields["pago"];
								$ld_por_paga2=$ld_por_paga2+($ld_causado2-$ld_pagado2);

									$ls_antasig=$ld_asignado2;
									$ls_antaum=$ld_aumento2;
									$ls_antdis=$ld_disminucion2;
									$ls_antpre=$ld_precompromiso2;
									$ls_antcom=$ld_compromiso2;
									$ls_antcau=$ld_causado2;
									$ls_antpagado=$ld_pagado2;
									$ls_antpagar=$ld_por_paga2;

								$rs_data3->MoveNext();
							 }
								$ld_monto_actualizado=$ld_monto_actualizado+$ld_asignado2+$ld_aumento2-$ld_disminucion2;

								$ls_antmon_act=$ld_monto_actualizado;

								$ld_total_asignado=$ld_total_asignado+$ld_asignado2;
								$ld_total_aumento=$ld_total_aumento+$ld_aumento2;
								$ld_total_disminucion=$ld_total_disminucion+$ld_disminucion2;
								$ld_total_precompromiso=$ld_total_precompromiso+$ld_precompromiso2;
								$ld_total_compromiso=$ld_total_compromiso+$ld_compromiso2;
								$ld_total_causado=$ld_total_causado+$ld_causado2;
								$ld_total_pagado=$ld_total_pagado+$ld_pagado2;
								$ld_total_por_paga=$ld_total_por_paga+$ld_por_paga2;
								$ld_por_comprometer=$ld_monto_actualizado-$ld_precompromiso2-$ld_compromiso2;

								$ld_totspg_asignado          = $ld_totspg_asignado + $ld_asignado2;
							    $ld_totspg_aumento           = $ld_totspg_aumento +  $ld_aumento2;
							    $ld_totspg_disminucion       = $ld_totspg_disminucion + $ld_disminucion2;
							    $ld_totspg_monto_actualizado = $ld_monto_actualizado;
							    $ld_totspg_compromiso        = $ld_totspg_compromiso + $ld_compromiso2;
							    $ld_totspg_precompromiso     = $ld_totspg_precompromiso + $ld_precompromiso2;
							    $ld_totspg_causado           = $ld_totspg_causado + $ld_causado2;
							    $ld_totspg_pagado            = $ld_totspg_pagado + $ld_pagado2;
							    $ld_totspg_por_pagar         = $ld_totspg_por_pagar + $ld_por_paga2;

								$la_auxdata[0]=array('','','','SALDOS ANTERIORES','',number_format($ld_asignado2,2,",","."),
								                     number_format($ld_aumento2,2,",","."),
													 number_format($ld_disminucion2,2,",","."),
													 number_format($ld_monto_actualizado,2,",","."),
													 number_format($ld_precompromiso2,2,",","."),
													 number_format($ld_compromiso2,2,",","."),
							 					     number_format($ld_causado2,2,",","."),
													 number_format($ld_pagado2,2,",","."),number_format($ld_por_paga2,2,",","."));


						 }
						 else if ($lb_valido)
						 {
							$la_auxdata[0]=array('','','','SALDOS ANTERIORES','','0.00','0.00',
												'0.00','0.00','0.00','0.00','0.00','0.00','0.00');
							$ld_asignado2=0;
							$ld_aumento2=0;
							$ld_disminucion2=0;
							$ld_precompromiso2=0;
							$ld_compromiso2=0;
							$ld_causado2=0;
							$ld_pagado2=0;
							$ld_por_paga2=0;
							$ld_monto_actualizado=0;


						 }
				 }


				  $ls_denominacion=utf8_encode($rs_data2->fields["denominacion"]);
				  $fecha=$rs_data2->fields["fecha"];
				  $ls_fecha=$io_funciones->uf_convertirfecmostrar($fecha);
				  $ls_procede=$rs_data2->fields["procede"];
				  $ls_procede_doc=$rs_data2->fields["procede_doc"];
				  $ls_comprobante=$rs_data2->fields["comprobante"];
				  $ls_documento=$rs_data2->fields["documento"];
				  $ls_descripcion=utf8_encode($rs_data2->fields["nombre_prog"]);
				  $ld_asignado=$rs_data2->fields["asignar"];
				  $ld_aumento=$rs_data2->fields["aumento"];
				  $ld_disminucion=$rs_data2->fields["disminucion"];
				  $ls_tipodest=$rs_data2->fields["tipo_destino"];
				  $ls_nombene=utf8_encode($rs_data2->fields["nombene"]);
				  $ls_apebene=utf8_encode($rs_data2->fields["apebene"]);
				  $ls_nompro=utf8_encode($rs_data2->fields["nompro"]);
				  if ($ls_tipodest=='B')
				  {
				  	$ls_nomproben=$ls_apebene." ".$ls_nombene;
				  }
				  else
				  {
				  	$ls_nomproben=$ls_nompro;
				  }
				  $ld_monto_actualizado=$ld_monto_actualizado+$ld_asignado+$ld_aumento-$ld_disminucion;

				  $ld_precompromiso=$rs_data2->fields["precompromiso"];
				  $ld_compromiso=$rs_data2->fields["compromiso"];
				  $ld_causado=$rs_data2->fields["causado"];
				  $ld_pagado=$rs_data2->fields["pago"];

				  $ld_por_paga=$ld_causado-$ld_pagado;
				  $ld_total_asignado=$ld_total_asignado+$ld_asignado;
				  $ld_total_aumento=$ld_total_aumento+$ld_aumento;
				  $ld_total_disminucion=$ld_total_disminucion+$ld_disminucion;
				  $ld_total_precompromiso=$ld_total_precompromiso+$ld_precompromiso;
				  $ld_total_compromiso=$ld_total_compromiso+$ld_compromiso;
				  $ld_total_causado=$ld_total_causado+$ld_causado;
				  $ld_total_pagado=$ld_total_pagado+$ld_pagado;
				  $ld_total_por_paga=$ld_total_por_paga+$ld_por_paga;
				  $ld_por_comprometer=$ld_monto_actualizado-$ld_precompromiso-$ld_compromiso;

				  $ld_totspg_asignado          = $ld_totspg_asignado + $ld_asignado;
				  $ld_totspg_aumento           = $ld_totspg_aumento +  $ld_aumento;
				  $ld_totspg_disminucion       = $ld_totspg_disminucion + $ld_disminucion;
				  $ld_totspg_monto_actualizado = $ld_monto_actualizado;
				  $ld_totspg_compromiso        = $ld_totspg_compromiso + $ld_compromiso;
				  $ld_totspg_precompromiso     = $ld_totspg_precompromiso + $ld_precompromiso;
				  $ld_totspg_causado           = $ld_totspg_causado + $ld_causado;
				  $ld_totspg_pagado            = $ld_totspg_pagado + $ld_pagado;
				  $ld_totspg_por_pagar         = $ld_totspg_por_pagar + $ld_por_paga;


			      if(($ls_spg_cuenta!=$ls_spg_cuenta_next)&& ($li_tmp!=($li_totrow_det-1)))
			      {

					  $ld_total_monto_actualizado=$ld_total_monto_actualizado+$ld_monto_actualizado;// Agregado por Ing. Nelson Barraez 20-12-2006


					  $la_data[$li_tmp]=array($ls_fecha,$ls_comprobante,$ls_documento,
										      $ls_descripcion,$ls_nomproben,number_format($ld_asignado,2,",","."),
											  number_format($ld_aumento,2,",","."), number_format($ld_disminucion,2,",","."),
											  number_format($ld_monto_actualizado,2,",","."),
											  number_format($ld_precompromiso,2,",","."),
											  number_format($ld_compromiso,2,",","."),
											  number_format($ld_causado,2,",","."),number_format($ld_pagado,2,",","."),
											  number_format($ld_por_paga,2,",","."));

					 $ld_monto_actualizado=0;
					 $entro=false;

					 $io_tcpdf->uf_print_cabecera_detalle($ls_spg_cuenta,$ls_denominacion);
					 $io_tcpdf->uf_print_titulos ($ls_mostrar);
					 $io_tcpdf->uf_print_detalle($la_auxdata,$y,0,$ls_mostrar); // Imprimimos los saldos anteriores
					 $io_tcpdf->uf_print_detalle($la_data,$y,1,$ls_mostrar); // Imprimimos el detalle


					 //---------- SUB TOTAL POR PERIODO DE LA CUENTA-----------------------------------
					 $ls_sub_asignado=abs($ld_totspg_asignado-$ls_antasig);
					 $ls_subaumento=abs($ld_totspg_aumento-$ls_antaum);
					 $ls_subdisminucion=abs($ld_totspg_disminucion-$ls_antdis);
					 $ls_submonto_actual=abs($ld_totspg_monto_actualizado-$ls_antmon_act);
					 $ls_subprecompromiso=abs($ld_totspg_precompromiso-$ls_antpre);
					 $ls_subcompromiso=abs($ld_totspg_compromiso-$ls_antcom);
					 $ls_subcausado=abs($ld_totspg_causado-$ls_antcau);
					 $ls_subpagar=abs($ld_totspg_por_pagar-$ls_antpagar);
					 $ls_subpagado=abs($ld_totspg_pagado-$ls_antpagado);

					 $la_data_subtot2[1]=array('SUB TOTAL CUENTA '.$ls_spg_cuenta,number_format($ls_sub_asignado,2,",","."),
												number_format($ls_subaumento ,2,",","."),
												number_format($ls_subdisminucion,2,",","."),
												number_format($ls_submonto_actual,2,",","."),
												number_format($ls_subprecompromiso,2,",","."),
												number_format($ls_subcompromiso,2,",","."),
												number_format($ls_subcausado,2,",","."),
												number_format($ls_subpagado,2,",","."),
												number_format($ls_subpagar,2,",","."));

					 $io_tcpdf->uf_print_total($la_data_subtot2,'0',$y,$ls_mostrar);
					 $ls_sub_asignado          =0;
					 $ls_subaumento            =0;
					 $ls_subdisminucion        =0;
				     $ls_submonto_actual       =0;
					 $ls_subcompromiso         =0;
					 $ls_subprecompromiso      =0;
					 $ls_subcausado            =0;
					 $ls_subpagar              =0;
					 $ls_subpagado             =0;
					 //-------------------------------------------------------------------------------------------------------

					 $la_data_subtot[1]=array("TOTAL CUENTA ".$ls_spg_cuenta,number_format($ld_totspg_asignado,2,",","."),
											  number_format($ld_totspg_aumento ,2,",","."),
											  number_format($ld_totspg_disminucion,2,",","."),
											  number_format($ld_totspg_monto_actualizado,2,",","."),
											  number_format($ld_totspg_precompromiso,2,",","."),
											  number_format($ld_totspg_compromiso,2,",","."),
										      number_format($ld_totspg_causado,2,",","."),
											  number_format($ld_totspg_pagado,2,",","."),
											  number_format($ld_totspg_por_pagar,2,",","."));


					 $io_tcpdf->uf_print_total($la_data_subtot,'1',0,$ls_mostrar);

					 $ld_totspg_asignado=0;
			         $ld_totspg_aumento=0;
			         $ld_totspg_disminucion=0;
			         $ld_totspg_monto_actualizado=0;
			         $ld_totspg_compromiso=0;
			         $ld_totspg_precompromiso=0;
			         $ld_totspg_causado=0;
			         $ld_totspg_pagado=0;
			         $ld_totspg_por_pagar=0;
					 unset($la_data_subtot);
					 unset($la_data_subtot2);
					 unset($la_data);
					 unset($la_auxdata);
				 }//if
				 else
				 {

					  $la_data[$li_tmp]=array($ls_fecha,$ls_comprobante,$ls_documento,
										      $ls_descripcion,$ls_nomproben,number_format($ld_asignado,2,",","."),
											  number_format($ld_aumento,2,",","."), number_format($ld_disminucion,2,",","."),
											  number_format($ld_monto_actualizado,2,",","."),
											  number_format($ld_precompromiso,2,",","."),number_format($ld_compromiso,2,",","."),
										   	  number_format($ld_causado,2,",","."),number_format($ld_pagado,2,",","."),
											  number_format($ld_por_paga,2,",","."));

			    }//else
				if($li_tmp==($li_totrow_det-1))
				{

					  $ld_total_monto_actualizado=$ld_total_monto_actualizado+$ld_monto_actualizado;  //Agregado por Ing. Nelson Barraez
					  $ld_total_saldo_comprometer=$ld_total_monto_actualizado-$ld_total_precompromiso-$ld_total_compromiso;//Agregado por Ing. Nelson Barraez 20-12-2006


					  $la_data[$li_tmp]=array($ls_fecha,$ls_comprobante,$ls_documento,
										      $ls_descripcion,$ls_nomproben,number_format($ld_asignado,2,",","."),
											  number_format($ld_aumento,2,",","."), number_format($ld_disminucion,2,",","."),
											  number_format($ld_monto_actualizado,2,",","."),
											  number_format($ld_precompromiso,2,",","."),number_format($ld_compromiso,2,",","."),
											  number_format($ld_causado,2,",","."),number_format($ld_pagado,2,",","."),
											  number_format($ld_por_paga,2,",","."));



					 //---------- SUB TOTAL POR PERIODO DE LA CUENTA-----------------------------------
					 $ls_sub_asignado=abs($ld_totspg_asignado-$ls_antasig);
					 $ls_subaumento=abs($ld_totspg_aumento-$ls_antaum);
					 $ls_subdisminucion=abs($ld_totspg_disminucion-$ls_antdis);
					 $ls_submonto_actual=abs($ld_totspg_monto_actualizado-$ls_antmon_act);
					 $ls_subprecompromiso=abs($ld_totspg_precompromiso-$ls_antpre);
					 $ls_subcompromiso=abs($ld_totspg_compromiso-$ls_antcom);
					 $ls_subcausado=abs($ld_totspg_causado-$ls_antcau);
					 $ls_subpagar=abs($ld_totspg_por_pagar-$ls_antpagar);
					 $ls_subpagado=abs($ld_totspg_pagado-$ls_antpagado);


					$la_data_subtot2[1]=array('SUB TOTAL CUENTA '.$ls_spg_cuenta,
											  number_format($ls_sub_asignado,2,",","."),
											  number_format($ls_subaumento ,2,",","."),
											  number_format($ls_subdisminucion,2,",","."),
											  number_format($ls_submonto_actual,2,",","."),
											  number_format($ls_subprecompromiso,2,",","."),
											  number_format($ls_subcompromiso,2,",","."),
											  number_format($ls_subcausado,2,",","."),
											  number_format($ls_subpagado,2,",","."),
											  number_format($ls_subpagar,2,",","."));


					 $ls_sub_asignado          =0;
					 $ls_subaumento            =0;
					 $ls_subdisminucion        =0;
				     $ls_submonto_actual       =0;
					 $ls_subcompromiso         =0;
					 $ls_subprecompromiso      =0;
					 $ls_subcausado            =0;
					 $ls_subpagar              =0;
					 $ls_subpagado             =0;
					 //------------------------------------------------------------------------------------------


					 $io_tcpdf->uf_print_cabecera_detalle($ls_spg_cuenta,$ls_denominacion);
					 $io_tcpdf->uf_print_titulos ($ls_mostrar);
					 $io_tcpdf->uf_print_detalle($la_auxdata,$y,0,$ls_mostrar); // Imprimimos los saldos anteriores
					 $io_tcpdf->uf_print_detalle($la_data,$y,1,$ls_mostrar); // Impr,imimos el detalle
					 $io_tcpdf->uf_print_total($la_data_subtot2,'0',$y,$ls_mostrar);
					 $la_data_subtot[1]=array("TOTAL CUENTA ".$ls_spg_cuenta,number_format($ld_totspg_asignado,2,",","."),
					  						  number_format($ld_totspg_aumento ,2,",","."),
											  number_format($ld_totspg_disminucion,2,",","."),
											  number_format($ld_totspg_monto_actualizado,2,",","."),
											  number_format($ld_totspg_precompromiso,2,",","."),
											  number_format($ld_totspg_compromiso,2,",","."),
										      number_format($ld_totspg_causado,2,",","."),
											  number_format($ld_totspg_pagado,2,",","."),
											  number_format($ld_totspg_por_pagar,2,",","."));

					$io_tcpdf->uf_print_total($la_data_subtot,'1',0,$ls_mostrar);

					 $ld_totspg_asignado=0;
			         $ld_totspg_aumento=0;
			         $ld_totspg_disminucion=0;
			         $ld_totspg_monto_actualizado=0;
			         $ld_totspg_compromiso=0;
			         $ld_totspg_precompromiso=0;
			         $ld_totspg_causado=0;
			         $ld_totspg_pagado=0;
			         $ld_totspg_por_pagar=0;
					 unset($la_data_subtot);
					 unset($la_data_subtot2);

					 $ld_total_asignado=number_format($ld_total_asignado,2,",",".");
					 $ld_total_aumento=number_format($ld_total_aumento,2,",",".");
					 $ld_total_disminucion=number_format($ld_total_disminucion,2,",",".");
					 $ld_total_monto_actualizado=number_format($ld_total_monto_actualizado,2,",",".");
					 $ld_total_precompromiso=number_format($ld_total_precompromiso,2,",",".");
					 $ld_total_compromiso=number_format($ld_total_compromiso,2,",",".");
					 $ld_total_saldo_comprometer=number_format($ld_total_saldo_comprometer,2,",",".");
					 $ld_total_causado=number_format($ld_total_causado,2,",",".");
					 $ld_total_pagado=number_format($ld_total_pagado,2,",",".");
					 $ld_total_por_paga=number_format($ld_total_por_paga,2,",",".");


					 $la_data_tot[$li_tmp]=array('SALDO POR COMPROMETER Bs.  '.$ld_total_saldo_comprometer.'   ',
					                             $ld_total_asignado,
					 							 $ld_total_aumento,$ld_total_disminucion,$ld_total_monto_actualizado,
												 $ld_total_precompromiso,$ld_total_compromiso,
												 $ld_total_causado,$ld_total_pagado,$ld_total_por_paga);

					 $io_tcpdf->uf_print_total($la_data_tot,'3',0,$ls_mostrar);
					 unset($la_data);
					 unset($la_data_tot);
					 unset($la_auxdata);

				}//if
			   $rs_data2->MoveNext();
			   $li_tmp=$li_tmp+1;

			}//while

            }//if
		 unset($la_data);
		 $rs_data->MoveNext();
		}//while
		$io_tcpdf->Output("sigesp_spg_rpp_mayor_anlitico.pdf", "I");
		unset($io_tcpdf);
	} //else
	unset($io_report);
	unset($io_funciones);
	unset($io_function_report);
?>