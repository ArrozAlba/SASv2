<?php
    session_start();   
	ini_set('memory_limit','1204M');
	ini_set('max_execution_time ','0');
   //---------------------------------------------------------------------------------------------------------------------------

   //---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
		require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
		require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
		$lo_archivo =  tempnam("/tmp", "spi_mayor_analitico.xls");
		$lo_libro = &new writeexcel_workbookbig($lo_archivo);
		$lo_hoja = &$lo_libro->addworksheet();
	//---------------------------------------------------------------------------------------------------------------------------
		require_once("sigesp_spi_reporte.php");
		$io_report = new sigesp_spi_reporte();
		require_once("sigesp_spi_funciones_reportes.php");
		$io_function_report = new sigesp_spi_funciones_reportes();
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();			
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
    //--------------------------------------------------  Parámetros para Filtar el Reporte  ---------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		$li_estmodest		= $_SESSION["la_empresa"]["estmodest"];
		
		$ls_codestpro1_min  = $_GET["codestpro1"];
		$ls_codestpro2_min  = $_GET["codestpro2"];
		$ls_codestpro3_min  = $_GET["codestpro3"];
		$ls_codestpro1h_max = $_GET["codestpro1h"];
		$ls_codestpro2h_max = $_GET["codestpro2h"];
		$ls_codestpro3h_max = $_GET["codestpro3h"];
	    $ls_estclades       = $_GET["estclades"];
	    $ls_estclahas       = $_GET["estclahas"];
		$ls_loncodestpro1   = $_SESSION["la_empresa"]["loncodestpro1"];
		$ls_loncodestpro2   = $_SESSION["la_empresa"]["loncodestpro2"];
		$ls_loncodestpro3   = $_SESSION["la_empresa"]["loncodestpro3"];
		$ls_loncodestpro4   = $_SESSION["la_empresa"]["loncodestpro4"];
		$ls_loncodestpro5   = $_SESSION["la_empresa"]["loncodestpro5"];

		$ls_tipoformato=1;
		if($li_estmodest==1)
		{
			$ls_codestpro4_min =  "0000000000000000000000000";
			$ls_codestpro5_min =  "0000000000000000000000000";
			$ls_codestpro4h_max = "0000000000000000000000000";
			$ls_codestpro5h_max = "0000000000000000000000000";
			if(($ls_codestpro1_min=="")&&($ls_codestpro2_min=="")&&($ls_codestpro3_min==""))
			{
			  if($io_function_report->uf_spi_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,
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
			  $io_function_report->uf_spi_reporte_select_min_codestpro1(&$ls_codestpro1_min,&$ls_estclades);
			  $ls_codestpro1=$ls_codestpro1_min;
		     }
			 
			 if($ls_codestpro2_min<>"")
		     {	
			  $ls_codestpro2_min=$io_funciones->uf_cerosizquierda($ls_codestpro2_min,25);
			  $ls_codestpro2= $ls_codestpro2_min;	
			 }
		     else
		     {	
			  $io_function_report->uf_spi_reporte_select_min_codestpro2($ls_codestpro1_min,&$ls_codestpro2_min,$ls_estclades);
			  $ls_codestpro1=$ls_codestpro1_min;
		     }
			 
			 if($ls_codestpro1_min<>"")
		     {	
			  $ls_codestpro1_min=$io_funciones->uf_cerosizquierda($ls_codestpro1_min,25);
			  $ls_codestpro1= $ls_codestpro1_min;	
			 }
		     else
		     {	
			  $io_function_report->uf_spi_reporte_select_min_codestpro3($ls_codestpro1_min,$ls_codestpro2_min,&$ls_codestpro3_min,$ls_estclades);
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
			  if($io_function_report->uf_spi_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
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
			  if($io_function_report->uf_spi_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,
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
			  if($io_function_report->uf_spi_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
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
		   if($io_function_report->uf_spi_reporte_select_min_cuenta($ls_cuentades_min))
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
		   if($io_function_report->uf_spi_reporte_select_max_cuenta($ls_cuentahas_max))
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
		
		$ls_programatica_desde=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
		$ls_programatica_hasta=$ls_codestpro1h.$ls_codestpro2h.$ls_codestpro3h.$ls_codestpro4h.$ls_codestpro5h;
		
	    if($li_estmodest==1)
		{
		    if (($ls_codestpro1<>"")&&($ls_codestpro2=="")&&($ls_codestpro3==""))
			{
			 $ls_programatica_desde1=substr($ls_codestpro1,-$ls_loncodestpro1);
			 $ls_programatica_hasta1=substr($ls_codestpro1h,-$ls_loncodestpro1);
			}
			elseif(($ls_codestpro1<>"")&&($ls_codestpro2<>"")&&($ls_codestpro3==""))
			{
			 $ls_programatica_desde1=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2);
			 $ls_programatica_hasta1=substr($ls_codestpro1h,-$ls_loncodestpro1)."-".substr($ls_codestpro2h,-$ls_loncodestpro2);
			}
			elseif(($ls_codestpro1<>"")&&($ls_codestpro2<>"")&&($ls_codestpro3<>""))
			{
			 $ls_programatica_desde1=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3);
			 $ls_programatica_hasta1=substr($ls_codestpro1h,-$ls_loncodestpro1)."-".substr($ls_codestpro2h,-$ls_loncodestpro2)."-".substr($ls_codestpro3h,-$ls_loncodestpro3);
			}
			else
			{
			 $ls_programatica_desde1="";
			 $ls_programatica_hasta1="";
			}
		}
		else
		{ 
			$ls_programatica_desde1=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3)."-".substr($ls_codestpro4,-$ls_loncodestpro4)."-".substr($ls_codestpro5,-$ls_loncodestpro5)."-".$ls_estclades;
			$ls_programatica_hasta1=substr($ls_codestpro1h,-$ls_loncodestpro1)."-".substr($ls_codestpro2h,-$ls_loncodestpro2)."-".substr($ls_codestpro3h,-$ls_loncodestpro3)."-".substr($ls_codestpro4h,-$ls_loncodestpro4)."-".substr($ls_codestpro5h,-$ls_loncodestpro5)."-".$ls_estclahas;
		}
		/////////////////////////////////         SEGURIDAD               ///////////////////////////////////
		$ls_desc_event="Se genero el Reporte Mayor Analitico desde la fecha ".$fecdes." hasta ".$fechas.", Desde la programatica ".$ls_programatica_desde."  hasta ".$ls_programatica_hasta." , Desde la Cuenta ".$ls_cuentades." hasta la ".$ls_cuentahas;
		$io_function_report->uf_load_seguridad_reporte("SPI","sigesp_spi_r_mayor_analitico.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               ///////////////////////////////////
//----------------------------------------------------  Parámetros del encabezado  ---------------------------------------------
		//$ldt_fecha="Desde   ".$fecdes."   al   ".$fechas."";
		$ls_titulo="MAYOR ANALITICO Desde   ".$fecdes."   al   ".$fechas."";
		$ls_titulo1="DESDE LA PROGRAMATICA  ".$ls_programatica_desde1."  HASTA  ".$ls_programatica_hasta1.""; 
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
	$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
	$ls_estpreing=$_SESSION["la_empresa"]["estpreing"];
	$li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
	$li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
	$li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
	$li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
	$li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
	$li_nomestpro1 = $_SESSION["la_empresa"]["nomestpro1"];
	$li_nomestpro2 = $_SESSION["la_empresa"]["nomestpro2"];
	$li_nomestpro3 = $_SESSION["la_empresa"]["nomestpro3"];
	$li_nomestpro4 = $_SESSION["la_empresa"]["nomestpro4"];
	$li_nomestpro5 = $_SESSION["la_empresa"]["nomestpro5"];

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
	//$lo_dataright= &$lo_libro->addformat(array(num_format => '#,##0.00'));
	$lo_dataright= &$lo_libro->addformat();
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
	$ld_total_previsto = 0;
	$ld_total_aumento  = 0;		  
	$ld_total_disminucion = 0;		 
	$ld_total_devengado = 0;		 		   
	$ld_total_cobrado = 0;		 		   		  
	$ld_total_cobrado_anticipado = 0;
	$ld_total_monto_actualizado=0;
	$ld_total_por_cobrar=0;
	$ld_sub_total_previsto=0;
	$ld_sub_total_aumento=0;
	$ld_sub_total_disminucion=0;
	$ld_sub_total_devengado=0;
	$ld_sub_total_cobrado=0;
	$ld_sub_total_cobrado_anticipado=0;
	$ld_sub_total_monto_actualizado=0;
	$ld_sub_total_por_cobrar=0;
	$li_row=0;
    if ($ls_estpreing==1)
	{
		 $lb_valido=$io_report->select_estructuras($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
											        $ls_codestpro5,$ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,
											        $ls_codestpro4h,$ls_codestpro5h,$ls_estclades,$ls_estclahas);
		 $li_totfila=$io_report->data_est->getRowCount("codestpro1");
		 for ($j=1;$j<=$li_totfila;$j++)
		  {
			  $ls_codestpro1=trim($io_report->data_est->data["codestpro1"][$j]); 
			  $ls_codestpro2=trim($io_report->data_est->data["codestpro2"][$j]);
			  $ls_codestpro3=trim($io_report->data_est->data["codestpro3"][$j]);
			  $ls_codestpro4=trim($io_report->data_est->data["codestpro4"][$j]);
			  $ls_codestpro5=trim($io_report->data_est->data["codestpro5"][$j]);
			  $ls_estcla=trim($io_report->data_est->data["estcla"][$j]);
			  $ls_estclades=trim($io_report->data_est->data["estcla"][$j]);
			
			  $ls_codestpro1h=trim($io_report->data_est->data["codestpro1"][$j]);
			  $ls_codestpro2h=trim($io_report->data_est->data["codestpro2"][$j]);
			  $ls_codestpro3h=trim($io_report->data_est->data["codestpro3"][$j]);
			  $ls_codestpro4h=trim($io_report->data_est->data["codestpro4"][$j]);
			  $ls_codestpro5h=trim($io_report->data_est->data["codestpro5"][$j]);
			  $ls_estclahas=trim($io_report->data_est->data["estcla"][$j]);

	          $lb_valido=$io_report->uf_spi_reporte_mayor_analitico2($ldt_fecdes,$ldt_fechas,$ls_cuentades,
															   $ls_cuentahas,$ls_orden,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
	                                                           $ls_codestpro5,$ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,
	                                                           $ls_codestpro4h,$ls_codestpro5h,$ls_estclades,$ls_estclahas);
			  $io_report->dts_reporte->group_noorder("spi_cuenta");
			  $li_totrow_det=$io_report->dts_reporte->getRowCount("spi_cuenta");
			  for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
				  $li_tmp=($li_s+1); // Iniciamos la transacción
				  $ls_spi_cuenta=$io_report->dts_reporte->data["spi_cuenta"][$li_s];
				  
				  if ($li_s<$li_totrow_det)
				  {
						$ls_spi_cuenta_next=$io_report->dts_reporte->data["spi_cuenta"][$li_tmp];  
				  }
				  elseif($li_s==$li_totrow_det)
				  {
						$ls_spi_cuenta_next='no_next'; 
				  }
				  if(empty($ls_spi_cuenta_next)&&(!empty($ls_spi_cuenta)))
				  {
					 $ls_spi_cuenta_ant=$io_report->dts_reporte->data["spi_cuenta"][$li_s];
				  }
				  if($li_totrow_det==1)
				  {
					 $ls_spi_cuenta_ant=$io_report->dts_reporte->data["spi_cuenta"][$li_s];
				  }
				  $ls_denominacion=$io_report->dts_reporte->data["denominacion"][$li_s];
				  $fecha=$io_report->dts_reporte->data["fecha"][$li_s];
				  $ls_fecha=$io_funciones->uf_convertirfecmostrar($fecha);
				  $ls_procede=$io_report->dts_reporte->data["procede"][$li_s];
				  $ls_procede_doc=$io_report->dts_reporte->data["procede_doc"][$li_s];
				  $ls_comprobante=$io_report->dts_reporte->data["comprobante"][$li_s];
				  $ls_documento=$io_report->dts_reporte->data["documento"][$li_s];
				  $ls_descripcion=$io_report->dts_reporte->data["descripcion"][$li_s];
				  $ld_previsto=$io_report->dts_reporte->data["previsto"][$li_s];  //print "previsto".$ld_previsto."<br>";
				  $ld_aumento=$io_report->dts_reporte->data["aumento"][$li_s];// print "aumento".$ld_aumento."<br>";
				  $ld_disminucion=$io_report->dts_reporte->data["disminucion"][$li_s];  //print "dininucion".$ld_disminucion."<br>";
				  $ld_devengado=$io_report->dts_reporte->data["devengado"][$li_s]; //print "devengado".$ld_devengado."<br>";
				  $ld_cobrado=$io_report->dts_reporte->data["cobrado"][$li_s]; //print "cobrado".$ld_cobrado."<br>";
				  $ld_cobrado_anticipado=$io_report->dts_reporte->data["cobrado_anticipado"][$li_s];  //print "cobrado anticipado".$ld_cobrado_anticipado."<br>";
				  $ls_tipo_destino=$io_report->dts_reporte->data["tipo_destino"][$li_s];
				  $ls_cod_pro=$io_report->dts_reporte->data["cod_pro"][$li_s];
				  $ls_nompro=$io_report->dts_reporte->data["nompro"][$li_s];
				  $ls_nombene=$io_report->dts_reporte->data["nombene"][$li_s];
				  $ls_operacion=$io_report->dts_reporte->data["operacion"][$li_s];
				  $ld_monto_actualizado=($ld_previsto+$ld_aumento-$ld_disminucion)-$ld_devengado;
				  $ld_monto_actualizado_aux=$ld_monto_actualizado;
				  if(($ls_operacion=="DEV")or($ls_operacion=="COB")or($ls_operacion=="DC"))
				  {
					  $ld_monto_actualizado=0;
				  }
				  $ld_por_cobrar=$ld_devengado-$ld_cobrado;
				  
				  $ld_total_previsto=$ld_total_previsto+$ld_previsto;
				  $ld_total_aumento=$ld_total_aumento+$ld_aumento;
				  $ld_total_disminucion=$ld_total_disminucion+$ld_disminucion;
				  $ld_total_devengado=$ld_total_devengado+$ld_devengado;
				  $ld_total_cobrado=$ld_total_cobrado+$ld_cobrado;
				  $ld_total_cobrado_anticipado=$ld_total_cobrado_anticipado+$ld_cobrado_anticipado;
				  $ld_total_monto_actualizado=$ld_total_monto_actualizado+$ld_monto_actualizado;
				  $ld_total_por_cobrar=$ld_total_por_cobrar+$ld_por_cobrar;
				  $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro1($ls_codestpro1,$ls_denestpro1,$ls_estcla);			
					if($lb_valido)
					{
						$ls_denestpro1=$ls_denestpro1; 
					}			
					if($lb_valido)
					{
						  $ls_denestpro2="";
						  $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro2($ls_codestpro1,$ls_codestpro2,
																				  $ls_denestpro2,$ls_estcla);
						  $ls_denestpro2=$ls_denestpro2;
					}
					if($lb_valido)
					{
						  $ls_denestpro3="";
						  $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro3($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																				  $ls_denestpro3,$ls_estcla);
						  $ls_denestpro3=$ls_denestpro3;
					}
					if($li_estmodest==2)
					{
						$ls_codestpro4=substr($ls_programatica,75,25);
						if($lb_valido)
						{
						  $ls_denestpro4="";
						  $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro4($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																				  $ls_codestpro4,$ls_denestpro4,$ls_estcla);
						  $ls_denestpro4=$ls_denestpro4;
						}
						$ls_codestpro5=substr($ls_programatica,100,25);
						if($lb_valido)
						{
						  $ls_denestpro5="";
						  $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro5($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																				  $ls_codestpro4,$ls_codestpro5,$ls_denestpro5,
																				  $ls_estcla);
						  $ls_denestpro5=$ls_denestpro5;
						}			
					}
					$ls_codestpro1    = trim(substr($ls_codestpro1,-$li_loncodestpro1));
					$ls_codestpro2    = trim(substr($ls_codestpro2,-$li_loncodestpro2));
					$ls_codestpro3    = trim(substr($ls_codestpro3,-$li_loncodestpro3));
					$ls_codestpro4    = trim(substr($ls_codestpro4,-$li_loncodestpro4));
					$ls_codestpro5    = trim(substr($ls_codestpro5,-$li_loncodestpro5));
				  
				 /* if ($li_estmodest==1)
		          {
				      $li_row=$li_row+3; 
					  $lo_hoja->write($li_row, 0, $li_nomestpro1,$lo_titulo);
					  $lo_hoja->write($li_row, 1, $ls_codestpro1,$lo_datacenter);
					  $lo_hoja->write($li_row, 2, $ls_denestpro1,$lo_datacenter);
					  $li_row=$li_row+1;
				      $lo_hoja->write($li_row, 0,$li_nomestpro2,$lo_titulo);
					  $lo_hoja->write($li_row, 1, $ls_codestpro2,$lo_datacenter);
					  $lo_hoja->write($li_row, 2, $ls_denestpro2,$lo_datacenter);
					  //$li_row=$li_row+1;
				      $lo_hoja->write($li_row, 0,$li_nomestpro3,$lo_titulo);
					  $lo_hoja->write($li_row, 1, $ls_codestpro3,$lo_datacenter);
					 // $lo_hoja->write($li_row, 2, $ls_denestpro3,$lo_datacenter);
	              }
				  else
				  {
				      $lo_hoja->write($li_row, 0, $li_nomestpro1,$lo_titulo);
					  $lo_hoja->write($li_row, 1, $ls_codestpro1."".$ls_denestpro1,$lo_datacenter);
					  $lo_hoja->write($li_row, 1, $li_nomestpro2,$lo_titulo);
					  $lo_hoja->write($li_row, 1, $ls_codestpro2."".$ls_denestpro2,$lo_datacenter);
					  $lo_hoja->write($li_row, 2, $li_nomestpro3,$lo_titulo);
					  $lo_hoja->write($li_row, 1, $ls_codestpro3."".$ls_denestpro3,$lo_datacenter);
					  $lo_hoja->write($li_row, 4, $li_nomestpro4,$lo_titulo);
					  $lo_hoja->write($li_row, 1, $ls_codestpro4."".$ls_denestpro4,$lo_datacenter);
					  $lo_hoja->write($li_row, 5, $li_nomestpro5,$lo_titulo);
					  $lo_hoja->write($li_row, 1, $ls_codestpro5."".$ls_denestpro5,$lo_datacenter);
				  }*/
				  if (!empty($ls_spi_cuenta))
				  {
							 $li_row=$li_row+4;
							 $ls_cuenta_act=$ls_spi_cuenta;
							 $lo_hoja->write($li_row, 0, "Cuenta",$lo_titulo);
							 $lo_hoja->write($li_row, 1, $ls_cuenta_act,$lo_datacenter);
							 $lo_hoja->write($li_row, 2, $ls_denominacion,$lo_libro->addformat(array('font'=>'Verdana','align'=>'left','size'=>'9')));
							 $li_row=$li_row+1; 
							 $lo_hoja->write($li_row, 0, "Fecha",$lo_titulo);
							 $lo_hoja->write($li_row, 1, "Comprobante",$lo_titulo);
							 $lo_hoja->write($li_row, 2, "Documento",$lo_titulo);
							 $lo_hoja->write($li_row, 3, "Detalle",$lo_titulo);
							 $lo_hoja->write($li_row, 4, "Previsto",$lo_titulo);
							 $lo_hoja->write($li_row, 5, "Aumento",$lo_titulo);
							 $lo_hoja->write($li_row, 6, "Disminucion",$lo_titulo);
							 $lo_hoja->write($li_row, 7, "Monto Actualizado",$lo_titulo);
							 $lo_hoja->write($li_row, 8, "Devengado",$lo_titulo);
							 $lo_hoja->write($li_row, 9, "Cobrado",$lo_titulo);
							 $lo_hoja->write($li_row, 10, "Cobrado Anticipado",$lo_titulo);
							 $lo_hoja->write($li_row, 11, "Por Cobrar",$lo_titulo);
							 
							 $ld_sub_total_previsto=0;
							 $ld_sub_total_aumento=0;
							 $ld_sub_total_disminucion=0;
							 $ld_sub_total_devengado=0;
							 $ld_sub_total_cobrado=0;
							 $ld_sub_total_cobrado_anticipado=0;
							 $ld_sub_total_monto_actualizado=0;
							 $ld_sub_total_por_cobrar=0;
							 //print "filas --->>".$li_row."<br>";
				 }
				  $ld_sub_total_previsto=$ld_sub_total_previsto+$ld_previsto;
				  $ld_sub_total_aumento=$ld_sub_total_aumento+$ld_aumento;
				  $ld_sub_total_disminucion=$ld_sub_total_disminucion+$ld_disminucion;
				  $ld_sub_total_devengado=$ld_sub_total_devengado+$ld_devengado;
				  $ld_sub_total_cobrado=$ld_sub_total_cobrado+$ld_cobrado;
				  $ld_sub_total_cobrado_anticipado=$ld_sub_total_cobrado_anticipado+$ld_cobrado_anticipado;
				  $ld_sub_total_monto_actualizado=$ld_sub_total_monto_actualizado+$ld_monto_actualizado_aux;
				  $ld_sub_total_por_cobrar=$ld_sub_total_por_cobrar+$ld_por_cobrar;
						 
						 
						 
						  $li_row=$li_row+1; //print "entro al detalle"."<br>"; 
						  $lo_hoja->write($li_row, 0, $ls_fecha,$lo_datacenter); 
						  $lo_hoja->write($li_row, 1, $ls_comprobante." ",$lo_datacenter);
						  $lo_hoja->write($li_row, 2, $ls_documento,$lo_dataleft);
						  $lo_hoja->write($li_row, 3, $ls_descripcion." ",$lo_dataleft);
						  $lo_hoja->write($li_row, 4, $ld_previsto,$lo_dataright);
						  $lo_hoja->write($li_row, 5, $ld_aumento,$lo_dataright);
						  $lo_hoja->write($li_row, 6, $ld_disminucion,$lo_dataright);
						  $lo_hoja->write($li_row, 7, $ld_monto_actualizado,$lo_dataright);
						  $lo_hoja->write($li_row, 8, $ld_devengado,$lo_dataright);
						  $lo_hoja->write($li_row, 9, $ld_cobrado,$lo_dataright);
						  $lo_hoja->write($li_row, 10, $ld_cobrado_anticipado,$lo_dataright);
						  $lo_hoja->write($li_row, 11, $ld_por_cobrar,$lo_dataright);
				 if (!empty($ls_spi_cuenta_next))
				 {
					$li_row=$li_row+1;
					$lo_hoja->write($li_row, 2, "SALDO POR DEVENGAR ",$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
					$lo_hoja->write($li_row, 3, $ld_sub_total_monto_actualizado,$lo_dataright);
					$lo_hoja->write($li_row, 4, $ld_sub_total_previsto,$lo_dataright);
					$lo_hoja->write($li_row, 5, $ld_sub_total_aumento,$lo_dataright);
					$lo_hoja->write($li_row, 6, $ld_sub_total_disminucion,$lo_dataright);
					$lo_hoja->write($li_row, 7, $ld_sub_total_monto_actualizado,$lo_dataright);
					$lo_hoja->write($li_row, 8, $ld_sub_total_devengado,$lo_dataright);
					$lo_hoja->write($li_row, 9, $ld_sub_total_cobrado,$lo_dataright);
					$lo_hoja->write($li_row, 10,$ld_sub_total_cobrado_anticipado,$lo_dataright);
					$lo_hoja->write($li_row, 11,$ld_sub_total_cobrado_anticipado,$lo_dataright);
					$ls_cuenta_next="";
					$ls_cuenta_ant="";
				 }  
										 
				 if($li_s==$li_totrow_det)
				 {
							$li_row=$li_row+1;
							$lo_hoja->write($li_row, 2, "TOTAL POR DEVENGAR ",$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
							$lo_hoja->write($li_row, 3, $ld_total_monto_actualizado,$lo_dataright);
							$lo_hoja->write($li_row, 4, $ld_total_previsto,$lo_dataright);
							$lo_hoja->write($li_row, 5, $ld_total_aumento,$lo_dataright);
							$lo_hoja->write($li_row, 6, $ld_total_disminucion,$lo_dataright);
							$lo_hoja->write($li_row, 7, $ld_total_monto_actualizado,$lo_dataright);
							$lo_hoja->write($li_row, 8, $ld_total_devengado,$lo_dataright);
							$lo_hoja->write($li_row, 9, $ld_total_cobrado,$lo_dataright);
							$lo_hoja->write($li_row, 10,$ld_total_cobrado_anticipado,$lo_dataright);
							$lo_hoja->write($li_row, 11,$ld_total_por_cobrar,$lo_dataright);
							$ls_cuenta_next="";
							$ls_cuenta_ant="";	  	  
				 }//if 
				}//for
		 }//for								
	}
	else // Imprimimos el reporte
	 {
		
		$lb_valido=$io_report->uf_spi_reporte_mayor_analitico($ldt_fecdes,$ldt_fechas,$ls_cuentades,$ls_cuentahas,$ls_orden);
		$io_report->dts_reporte->group_noorder("spi_cuenta");
		$li_totrow_det=$io_report->dts_reporte->getRowCount("spi_cuenta");
		for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
		{
		  $li_tmp=($li_s+1); // Iniciamos la transacción
		  $ls_spi_cuenta=$io_report->dts_reporte->data["spi_cuenta"][$li_s];
		  
		  if ($li_s<$li_totrow_det)
		  {
				$ls_spi_cuenta_next=$io_report->dts_reporte->data["spi_cuenta"][$li_tmp];  
		  }
		  elseif($li_s==$li_totrow_det)
		  {
				$ls_spi_cuenta_next='no_next'; 
		  }
		  if(empty($ls_spi_cuenta_next)&&(!empty($ls_spi_cuenta)))
		  {
		     $ls_spi_cuenta_ant=$io_report->dts_reporte->data["spi_cuenta"][$li_s];
		  }
		  if($li_totrow_det==1)
		  {
		     $ls_spi_cuenta_ant=$io_report->dts_reporte->data["spi_cuenta"][$li_s];
		  }
		  
		  
		  $ls_denominacion=$io_report->dts_reporte->data["denominacion"][$li_s];
		  $fecha=$io_report->dts_reporte->data["fecha"][$li_s];
		  $ls_fecha=$io_funciones->uf_convertirfecmostrar($fecha);
		  $ls_procede=$io_report->dts_reporte->data["procede"][$li_s];
		  $ls_procede_doc=$io_report->dts_reporte->data["procede_doc"][$li_s];
		  $ls_comprobante=$io_report->dts_reporte->data["comprobante"][$li_s];
		  $ls_documento=$io_report->dts_reporte->data["documento"][$li_s];
		  $ls_descripcion=$io_report->dts_reporte->data["descripcion"][$li_s];
		  $ld_previsto=$io_report->dts_reporte->data["previsto"][$li_s];
		  $ld_aumento=$io_report->dts_reporte->data["aumento"][$li_s];
		  $ld_disminucion=$io_report->dts_reporte->data["disminucion"][$li_s];
		  $ld_devengado=$io_report->dts_reporte->data["devengado"][$li_s];
		  $ld_cobrado=$io_report->dts_reporte->data["cobrado"][$li_s];
		  $ld_cobrado_anticipado=$io_report->dts_reporte->data["cobrado_anticipado"][$li_s];
		  $ls_tipo_destino=$io_report->dts_reporte->data["tipo_destino"][$li_s];
		  $ls_cod_pro=$io_report->dts_reporte->data["cod_pro"][$li_s];
		  $ls_nompro=$io_report->dts_reporte->data["nompro"][$li_s];
		  $ls_nombene=$io_report->dts_reporte->data["nombene"][$li_s];
		  $ls_operacion=$io_report->dts_reporte->data["operacion"][$li_s];
          $ld_monto_actualizado=($ld_previsto+$ld_aumento-$ld_disminucion)-$ld_devengado;
		  $ld_monto_actualizado_aux=$ld_monto_actualizado;
		  if(($ls_operacion=="DEV")or($ls_operacion=="COB")or($ls_operacion=="DC"))
		  {
		      $ld_monto_actualizado=0;
		  }
		  $ld_por_cobrar=$ld_devengado-$ld_cobrado;
		  
		  $ld_total_previsto=$ld_total_previsto+$ld_previsto;
		  $ld_total_aumento=$ld_total_aumento+$ld_aumento;
		  $ld_total_disminucion=$ld_total_disminucion+$ld_disminucion;
		  $ld_total_devengado=$ld_total_devengado+$ld_devengado;
		  $ld_total_cobrado=$ld_total_cobrado+$ld_cobrado;
		  $ld_total_cobrado_anticipado=$ld_total_cobrado_anticipado+$ld_cobrado_anticipado;
		  $ld_total_monto_actualizado=$ld_total_monto_actualizado+$ld_monto_actualizado;
		  $ld_total_por_cobrar=$ld_total_por_cobrar+$ld_por_cobrar;
		  
				  
		  if (!empty($ls_spi_cuenta))
		  {
					 $li_row=$li_row+3;
	 				 $ls_cuenta_act=$ls_spi_cuenta;
					 $lo_hoja->write($li_row, 0, "Cuenta",$lo_titulo);
					 $lo_hoja->write($li_row, 1, $ls_cuenta_act,$lo_datacenter);
					 $lo_hoja->write($li_row, 2, $ls_denominacion,$lo_libro->addformat(array('font'=>'Verdana','align'=>'left','size'=>'9')));
					 $li_row=$li_row+1; 
				     $lo_hoja->write($li_row, 0, "Fecha",$lo_titulo);
					 $lo_hoja->write($li_row, 1, "Comprobante",$lo_titulo);
					 $lo_hoja->write($li_row, 2, "Documento",$lo_titulo);
					 $lo_hoja->write($li_row, 3, "Detalle",$lo_titulo);
					 $lo_hoja->write($li_row, 4, "Previsto",$lo_titulo);
					 $lo_hoja->write($li_row, 5, "Aumento",$lo_titulo);
					 $lo_hoja->write($li_row, 6, "Disminucion",$lo_titulo);
					 $lo_hoja->write($li_row, 7, "Monto Actualizado",$lo_titulo);
					 $lo_hoja->write($li_row, 8, "Devengado",$lo_titulo);
					 $lo_hoja->write($li_row, 9, "Cobrado",$lo_titulo);
					 $lo_hoja->write($li_row, 10, "Cobrado Anticipado",$lo_titulo);
					 $lo_hoja->write($li_row, 11, "Por Cobrar",$lo_titulo);
					 
					 $ld_sub_total_previsto=0;
		             $ld_sub_total_aumento=0;
		             $ld_sub_total_disminucion=0;
		             $ld_sub_total_devengado=0;
		             $ld_sub_total_cobrado=0;
		             $ld_sub_total_cobrado_anticipado=0;
		             $ld_sub_total_monto_actualizado=0;
		             $ld_sub_total_por_cobrar=0;
					 //print "filas --->>".$li_row."<br>";
		 }
		  $ld_sub_total_previsto=$ld_sub_total_previsto+$ld_previsto;
		  $ld_sub_total_aumento=$ld_sub_total_aumento+$ld_aumento;
		  $ld_sub_total_disminucion=$ld_sub_total_disminucion+$ld_disminucion;
		  $ld_sub_total_devengado=$ld_sub_total_devengado+$ld_devengado;
		  $ld_sub_total_cobrado=$ld_sub_total_cobrado+$ld_cobrado;
		  $ld_sub_total_cobrado_anticipado=$ld_sub_total_cobrado_anticipado+$ld_cobrado_anticipado;
		  $ld_sub_total_monto_actualizado=$ld_sub_total_monto_actualizado+$ld_monto_actualizado_aux;
		  $ld_sub_total_por_cobrar=$ld_sub_total_por_cobrar+$ld_por_cobrar;
				 
				 
				 
				  $li_row=$li_row+1; //print "entro al detalle"."<br>"; 
				  $lo_hoja->write($li_row, 0, $ls_fecha,$lo_datacenter); 
				  $lo_hoja->write($li_row, 1, $ls_comprobante." ",$lo_datacenter);
				  $lo_hoja->write($li_row, 2, $ls_documento,$lo_dataleft);
				  $lo_hoja->write($li_row, 3, $ls_descripcion." ",$lo_dataleft);
				  $lo_hoja->write($li_row, 4, $ld_previsto,$lo_dataright);
				  $lo_hoja->write($li_row, 5, $ld_aumento,$lo_dataright);
				  $lo_hoja->write($li_row, 6, $ld_disminucion,$lo_dataright);
				  $lo_hoja->write($li_row, 7, $ld_monto_actualizado,$lo_dataright);
				  $lo_hoja->write($li_row, 8, $ld_devengado,$lo_dataright);
				  $lo_hoja->write($li_row, 9, $ld_cobrado,$lo_dataright);
				  $lo_hoja->write($li_row, 10, $ld_cobrado_anticipado,$lo_dataright);
				  $lo_hoja->write($li_row, 11, $ld_por_cobrar,$lo_dataright);
		 if (!empty($ls_spi_cuenta_next))
		 {
		  	$li_row=$li_row+1;
			$lo_hoja->write($li_row, 2, "SALDO POR DEVENGAR ",$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
			$lo_hoja->write($li_row, 3, $ld_sub_total_monto_actualizado,$lo_dataright);
			$lo_hoja->write($li_row, 4, $ld_sub_total_previsto,$lo_dataright);
			$lo_hoja->write($li_row, 5, $ld_sub_total_aumento,$lo_dataright);
			$lo_hoja->write($li_row, 6, $ld_sub_total_disminucion,$lo_dataright);
			$lo_hoja->write($li_row, 7, $ld_sub_total_monto_actualizado,$lo_dataright);
			$lo_hoja->write($li_row, 8, $ld_sub_total_devengado,$lo_dataright);
			$lo_hoja->write($li_row, 9, $ld_sub_total_cobrado,$lo_dataright);
			$lo_hoja->write($li_row, 10,$ld_sub_total_cobrado_anticipado,$lo_dataright);
			$lo_hoja->write($li_row, 11,$ld_sub_total_cobrado_anticipado,$lo_dataright);
			$ls_cuenta_next="";
			$ls_cuenta_ant="";
		 }  
				  				 
    	 if($li_s==$li_totrow_det)
		 {
					$li_row=$li_row+1;
					$lo_hoja->write($li_row, 2, "TOTAL POR DEVENGAR ",$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
					$lo_hoja->write($li_row, 3, $ld_total_monto_actualizado,$lo_dataright);
					$lo_hoja->write($li_row, 4, $ld_total_previsto,$lo_dataright);
					$lo_hoja->write($li_row, 5, $ld_total_aumento,$lo_dataright);
					$lo_hoja->write($li_row, 6, $ld_total_disminucion,$lo_dataright);
					$lo_hoja->write($li_row, 7, $ld_total_monto_actualizado,$lo_dataright);
					$lo_hoja->write($li_row, 8, $ld_total_devengado,$lo_dataright);
					$lo_hoja->write($li_row, 9, $ld_total_cobrado,$lo_dataright);
					$lo_hoja->write($li_row, 10,$ld_total_cobrado_anticipado,$lo_dataright);
					$lo_hoja->write($li_row, 11,$ld_total_por_cobrar,$lo_dataright);
					$ls_cuenta_next="";
					$ls_cuenta_ant="";	  	  
		 }//if 
		}//for

	}//else
	$lo_libro->close();
	header("Content-Type: application/x-msexcel; name=\"spi_mayor_analitico.xls\"");
	header("Content-Disposition: inline; filename=\"spi_mayor_analitico.xls\"");
	$fh=fopen($lo_archivo, "rb");
	fpassthru($fh);
	unlink($lo_archivo);
	print("<script language=JavaScript>");
	print(" close();");
	print("</script>");
?> 