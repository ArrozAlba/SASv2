<?php
    session_start();   
	ini_set('memory_limit','512M');
	ini_set('max_execution_time','0');

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_scg;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_scg->uf_load_seguridad_reporte("SCG","sigesp_scg_r_estado_resultado.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_init_niveles()
	{	///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_init_niveles
		//	     Access: public
		//	    Returns: vacio	 
		//	Description: Este método realiza una consulta a los formatos de las cuentas
		//               para conocer los niveles de la escalera de las cuentas contables  
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones,$ia_niveles_scg;
		
		$ls_formato=""; $li_posicion=0; $li_indice=0;
		$dat_emp=$_SESSION["la_empresa"];
		//contable
		$ls_formato = trim($dat_emp["formcont"])."-";
		$li_posicion = 1 ;
		$li_indice   = 1 ;
		$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		do
		{
			$ia_niveles_scg[$li_indice] = $li_posicion;
			$li_indice   = $li_indice+1;
			$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		} while ($li_posicion>=0);
	}// end function uf_init_niveles
	//-----------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
		require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
		require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
		$lo_archivo = tempnam("/home/production/tmp", "estado_resultado.xls");
		$lo_libro = &new writeexcel_workbookbig($lo_archivo);
		$lo_hoja = &$lo_libro->addworksheet();
		
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear la data necesaria del reporte
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha=new class_fecha();
		require_once("../class_funciones_scg.php");
		$io_fun_scg=new class_funciones_scg();
		$ls_tiporeporte="0";
		$ls_bolivares="";
		if (array_key_exists("tiporeporte",$_GET))
		{
			$ls_tiporeporte=$_GET["tiporeporte"];
		}
		switch($ls_tiporeporte)
		{
			case "0":
				require_once("sigesp_scg_reporte.php");
				$io_report  = new sigesp_scg_reporte();
				$ls_bolivares ="Bs.";
				break;
	
			case "1":
				require_once("sigesp_scg_reportebsf.php");
				$io_report  = new sigesp_scg_reportebsf();
				$ls_bolivares ="Bs.F.";
				break;
		}
		$ia_niveles_scg[0]="";			
		uf_init_niveles();
		$li_total=count($ia_niveles_scg)-1;
	//---------------------------------------------------------------------------------------------------------------------------
	//Parámetros para Filtar el Reporte
		$ls_hidbot=$_GET["hidbot"];
		if($ls_hidbot==true)
		{
			$ls_cmbmesdes=$_GET["cmbmesdes"];
			$ls_cmbagnodes=$_GET["cmbagnodes"];
			$fecdes=$ls_cmbagnodes."-".$ls_cmbmesdes."-01"." 00:00:00";
			$ldt_fecdes=$ls_cmbagnodes."-".$ls_cmbmesdes."-01"." 00:00:00";			
			$ls_cmbmeshas=$_GET["cmbmeshas"];
			$ls_cmbagnohas=$_GET["cmbagnohas"];
			$ls_last_day=$io_fecha->uf_last_day($ls_cmbmeshas,$ls_cmbagnohas);
			$fechas=$ls_last_day;
			$ldt_fechas=$io_funciones->uf_convertirdatetobd($ls_last_day);
		}
		elseif($ls_hidbot==false)
		{
			$fecdes=$_GET["txtfecdes"];
			$ldt_fecdes=$io_funciones->uf_convertirdatetobd($fecdes);
			$fechas=$_GET["txtfechas"];
			$ldt_fechas=$io_funciones->uf_convertirdatetobd($fechas);
		}
		$li_nivel=$_GET["cmbnivel"];
	//---------------------------------------------------------------------------------------------------------------------------
	//Parámetros del encabezado
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		$ls_nombre=$_SESSION["la_empresa"]["nombre"];
		$ld_fecdes=$io_funciones->uf_convertirfecmostrar($fecdes);
		$ld_fechas=$io_funciones->uf_convertirfecmostrar($fechas);
		$ls_titulo="ESTADO DE RESULTADOS";
		$ls_titulo1="".$ls_nombre.""; 
		$ls_titulo2="al ".$ld_fechas."";
		$ls_titulo3="(Expresado en ".$ls_bolivares.")";  
	//---------------------------------------------------------------------------------------------------------------------------
	//Busqueda de la data 
	$lb_valido=uf_insert_seguridad("<b>Estado de Resultado en Excel</b>"); // Seguridad de Reporte
	if($lb_valido)
	{
	    $lb_valido_ing=$io_report->uf_scg_reporte_estado_de_resultado_ingreso($ldt_fecdes,$ldt_fechas,$li_nivel);
	    $lb_valido_egr=$io_report->uf_scg_reporte_estado_de_resultado_egreso($ldt_fecdes,$ldt_fechas,$li_nivel);
	}
	//---------------------------------------------------------------------------------------------------------------------------
	// Impresión de la información encontrada en caso de que exista
		if((($lb_valido_ing==false)&&($lb_valido_egr==false))||($lb_valido==false)) // Existe algún error ó no hay registros
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
			$lo_hoja->set_column(0,0,20);
			$lo_hoja->set_column(1,1,50);
			$lo_hoja->set_column(2,4,20);

			$lo_hoja->write(0,2,$ls_titulo,$lo_encabezado);
			$lo_hoja->write(1,2,$ls_titulo1,$lo_encabezado);
			$lo_hoja->write(2,2,$ls_titulo2,$lo_encabezado);
			$lo_hoja->write(3,2,$ls_titulo3,$lo_encabezado);
			$li_row=4;
			$ld_total_ingresos=0;
			$ld_total_egresos=0;
			if($lb_valido_ing)
			{
				$lo_hoja->write($li_row, 0, "INGRESOS",$lo_titulo);
				$li_row=$li_row+1;
				$lo_hoja->write($li_row, 0, "Cuenta",$lo_titulo);
				$lo_hoja->write($li_row, 1, "Denominación",$lo_titulo);
				$lo_hoja->write($li_row, 2, "Saldo",$lo_titulo);
				$lo_hoja->write($li_row, 3, "",$lo_titulo);
				$lo_hoja->write($li_row, 4, "",$lo_titulo);
				$li_tot=$io_report->dts_reporte->getRowCount("sc_cuenta");
				for($li_i=1;$li_i<=$li_tot;$li_i++)
				{
					$ls_sc_cuenta=trim($io_report->dts_reporte->data["sc_cuenta"][$li_i]);
					$li_totfil=0;
					$as_cuenta="";
					for($li=$li_total;$li>1;$li--)
					{
						$li_ant=$ia_niveles_scg[$li-1];
						$li_act=$ia_niveles_scg[$li];
						$li_fila=$li_act-$li_ant;
						$li_len=strlen($ls_sc_cuenta);
						$li_totfil=$li_totfil+$li_fila;
						$li_inicio=$li_len-$li_totfil;
						if($li==$li_total)
						{
							$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila);
						}
						else
						{
							$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila)."-".$as_cuenta;
						}
					}
					$li_fila=$ia_niveles_scg[1]+1;
					$as_cuenta=substr($ls_sc_cuenta,0,$li_fila)."-".$as_cuenta;
					$ls_status=$io_report->dts_reporte->data["status"][$li_i];
					$ls_denominacion=$io_report->dts_reporte->data["denominacion"][$li_i];
					$ld_saldo=$io_report->dts_reporte->data["saldo"][$li_i];
					$ld_total_ingresos=$io_report->dts_reporte->data["total_ingresos"][$li_i];
					$ls_nivel=$io_report->dts_reporte->data["nivel"][$li_i];
					if($ls_nivel>3)
					{
						$ld_saldo=abs($ld_saldo);
						$ld_saldomay=$ld_saldo;
						$ld_saldomen="";  
						$ld_saldo="";
					}
					if($ls_nivel==3)
					{
						$ld_saldo=abs($ld_saldo);					
						$ld_saldomay="";
						$ld_saldomen=$ld_saldo;  
						$ld_saldo="";
					}
					if(($ls_nivel==1)||($ls_nivel==2))
					{
						$ld_saldo=abs($ld_saldo);					
						$ld_saldomay="";
						$ld_saldomen="";  
						$ld_saldo=$ld_saldo;
					}
					$li_row=$li_row+1;
					$lo_hoja->write($li_row, 0, $as_cuenta, $lo_datacenter);
					$lo_hoja->write($li_row, 1, $ls_denominacion, $lo_dataleft);
					$lo_hoja->write($li_row, 2, $ld_saldomay, $lo_dataright);
					$lo_hoja->write($li_row, 3, $ld_saldomen, $lo_dataright);
					$lo_hoja->write($li_row, 4, $ld_saldo, $lo_dataright);
				}//for
				$ld_total_ingresos=abs($ld_total_ingresos);
				$li_row=$li_row+1;
				$lo_hoja->write($li_row, 2, "Total Ingresos ".$ls_bolivares,$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
				$lo_hoja->write($li_row, 4, $ld_total_ingresos,$lo_dataright);
				$li_row=$li_row+1;
			}
			if($lb_valido_egr)
			{
				$lo_hoja->write($li_row, 0, "EGRESOS",$lo_titulo);
				$li_row=$li_row+1;
				$lo_hoja->write($li_row, 0, "Cuenta",$lo_titulo);
				$lo_hoja->write($li_row, 1, "Denominación",$lo_titulo);
				$lo_hoja->write($li_row, 2, "Saldo",$lo_titulo);
				$lo_hoja->write($li_row, 3, "",$lo_titulo);
				$lo_hoja->write($li_row, 4, "",$lo_titulo);
				$li_tot=$io_report->dts_egresos->getRowCount("sc_cuenta");
				for($li_i=1;$li_i<=$li_tot;$li_i++)
				{
					$ls_sc_cuenta=trim($io_report->dts_egresos->data["sc_cuenta"][$li_i]);				
					$li_totfil=0;
					$as_cuenta="";
					for($li=$li_total;$li>1;$li--)
					{
						$li_ant=$ia_niveles_scg[$li-1];
						$li_act=$ia_niveles_scg[$li];
						$li_fila=$li_act-$li_ant;
						$li_len=strlen($ls_sc_cuenta);
						$li_totfil=$li_totfil+$li_fila;
						$li_inicio=$li_len-$li_totfil;
						if($li==$li_total)
						{
							$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila);
						}
						else
						{
							$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila)."-".$as_cuenta;
						}
					}
					$li_fila=$ia_niveles_scg[1]+1;
					$as_cuenta=substr($ls_sc_cuenta,0,$li_fila)."-".$as_cuenta;
					$ls_status=$io_report->dts_egresos->data["status"][$li_i];
					$ls_denominacion=$io_report->dts_egresos->data["denominacion"][$li_i];
					$ld_saldo=$io_report->dts_egresos->data["saldo"][$li_i];
					$ld_total_egresos=$io_report->dts_egresos->data["total_egresos"][$li_i];
					$ls_nivel=$io_report->dts_egresos->data["nivel"][$li_i];
					if($ls_nivel>3)
					{
						$ld_saldo=abs($ld_saldo);
						$ld_saldomay=$ld_saldo;
						$ld_saldomen="";  
						$ld_saldo="";
					}
					if($ls_nivel==3)
					{
						$ld_saldo=abs($ld_saldo);
						$ld_saldomay="";
						$ld_saldomen=$ld_saldo;  
						$ld_saldo="";
					}
					if(($ls_nivel==1)||($ls_nivel==2))
					{
						$ld_saldo=abs($ld_saldo);
						$ld_saldomay="";
						$ld_saldomen="";  
						$ld_saldo=$ld_saldo;
					}
					$li_row=$li_row+1;
					$lo_hoja->write($li_row, 0, $as_cuenta, $lo_datacenter);
					$lo_hoja->write($li_row, 1, $ls_denominacion, $lo_dataleft);
					$lo_hoja->write($li_row, 2, $ld_saldomay, $lo_dataright);
					$lo_hoja->write($li_row, 3, $ld_saldomen, $lo_dataright);
					$lo_hoja->write($li_row, 4, $ld_saldo, $lo_dataright);
				}//for
				$ld_total_egresos=abs($ld_total_egresos);
				$li_row=$li_row+1;
				$lo_hoja->write($li_row, 2, "Total Egresos ".$ls_bolivares,$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
				$lo_hoja->write($li_row, 4, $ld_total_egresos,$lo_dataright);
				$li_row=$li_row+1;
			}
		    $ld_total=$ld_total_ingresos-$ld_total_egresos;
			if($ld_total<0)
			{
				$ls_cadena="DESAHORRO";
			}
			else
			{
				$ls_cadena="AHORRO";
			}
			$lo_hoja->write($li_row, 2, "Total (".$ls_cadena.") ".$ls_bolivares,$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
			$lo_hoja->write($li_row, 4, $ld_total,$lo_dataright);
			$lo_libro->close();
			header("Content-Type: application/x-msexcel; name=\"estado_resultado.xls\"");
			header("Content-Disposition: inline; filename=\"estado_resultado.xls\"");
			$fh=fopen($lo_archivo, "rb");
			fpassthru($fh);
			unlink($lo_archivo);
			print("<script language=JavaScript>");
			print(" close();");
			print("</script>");
		}
?> 