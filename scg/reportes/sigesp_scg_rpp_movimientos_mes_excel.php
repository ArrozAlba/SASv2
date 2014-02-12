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
		$lb_valido=$io_fun_scg->uf_load_seguridad_reporte("SCG","sigesp_scg_r_movimientos_mes.php",$ls_descripcion);
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
		$lo_archivo = tempnam("/home/production/tmp", "movimientos_mes.xls");
		$lo_libro = &new writeexcel_workbookbig($lo_archivo);
		$lo_hoja = &$lo_libro->addworksheet();
		
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear la data necesaria del reporte
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();			
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
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
		$ls_fecha=$_GET["fecha"];
		$ls_fechasta=$io_fecha->uf_last_day(substr($ls_fecha,3,2),substr($ls_fecha,6,4));
		$ls_cuentadesde=$_GET["cuentadesde"];
		$ls_cuentahasta=$_GET["cuentahasta"];
		if(($ls_cuentadesde=="")&&($ls_cuentahasta==""))
		{
		   if($io_report->uf_spg_reporte_select_cuenta_min_max($ls_cuentadesde,$ls_cuentahasta))
		   {
		    // $ls_cuentadesde=$ls_cuentadesde_min;
		    // $ls_cuentahasta=$ls_cuentahasta_max;
		   } 
		}
	//---------------------------------------------------------------------------------------------------------------------------
	//Parámetros del encabezado
		$ldt_fecha="".$io_fecha->uf_load_nombre_mes(substr($ls_fecha,3,2))." - ".substr($ls_fecha,6,4)."";
		$ls_titulo="MOVIMIENTOS DEL MES";       
	//---------------------------------------------------------------------------------------------------------------------------
	//Busqueda de la data
	$lb_valido=uf_insert_seguridad("<b>Movimientos del Mes en Excel</b>"); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_scg_reporte_movimientos_mes($ls_cuentadesde,$ls_cuentahasta,$ls_fecha,$ls_fechasta);
	}
	//---------------------------------------------------------------------------------------------------------------------------
	// Impresión de la información encontrada en caso de que exista
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
		$lo_hoja->set_column(0,0,20);
		$lo_hoja->set_column(1,1,50);
		$lo_hoja->set_column(2,3,20);
		$lo_hoja->write(0, 1, $ls_titulo,$lo_encabezado);
		$lo_hoja->write(1, 1, $ldt_fecha,$lo_encabezado);
		$lo_hoja->write(3, 0, "Cuenta",$lo_titulo);
		$lo_hoja->write(3, 1, "Denominación",$lo_titulo);
		$lo_hoja->write(3, 2, "Debe",$lo_titulo);
		$lo_hoja->write(3, 3, "Haber",$lo_titulo);
		$li_row=3;
		$li_tot=$io_report->dts_reporte->getRowCount("sc_cuenta");
		$ldec_totaldebe=0;
		$ldec_totalhaber=0;
		$ldec_total_saldo=0;
		$ldec_total_ant=0;
        $ld_saldo=0;
		$ldec_mondeb=0;
        $ldec_monhab=0;
		for($i=1;$i<=$li_tot;$i++)
		{
			$ls_cuenta=rtrim($io_report->dts_reporte->getValue("sc_cuenta",$i));
			$li_totfil=0;
			$as_cuenta="";
			for($li=$li_total;$li>1;$li--)
			{
				$li_ant=$ia_niveles_scg[$li-1];
				$li_act=$ia_niveles_scg[$li];
				$li_fila=$li_act-$li_ant;
				$li_len=strlen($ls_cuenta);
				$li_totfil=$li_totfil+$li_fila;
				$li_inicio=$li_len-$li_totfil;
				if($li==$li_total)
				{
					$as_cuenta=substr($ls_cuenta,$li_inicio,$li_fila);
				}
				else
				{
					$as_cuenta=substr($ls_cuenta,$li_inicio,$li_fila)."-".$as_cuenta;
				}
			}
			$li_fila=$ia_niveles_scg[1]+1;
			$as_cuenta=substr($ls_cuenta,0,$li_fila)."-".$as_cuenta;
			$ls_denominacion=rtrim($io_report->dts_reporte->getValue("denominacion",$i));
			$ldec_debe=$io_report->dts_reporte->getValue("debe_mes",$i);
			$ldec_haber=$io_report->dts_reporte->getValue("haber_mes",$i);
			$ldec_saldo_ant=($io_report->dts_reporte->getValue("debe_mes_ant",$i)-$io_report->dts_reporte->getValue("haber_mes_ant",$i));
			$ldec_saldo_act=$ldec_saldo_ant+$ldec_debe-$ldec_haber;
			$ldec_BalDebe=$io_report->dts_reporte->getValue("total_debe",$i);
			$ldec_BalHABER=$io_report->dts_reporte->getValue("total_haber",$i);
			$ldec_totaldebe=$ldec_totaldebe+$ldec_BalDebe;
			$ldec_totalhaber=$ldec_totalhaber+$ldec_BalHABER;
			$ldec_saldo=$ldec_saldo_act;
			$li_row=$li_row+1;
			$lo_hoja->write($li_row, 0, $as_cuenta, $lo_datacenter);
			$lo_hoja->write($li_row, 1, $ls_denominacion, $lo_dataleft);
			$lo_hoja->write($li_row, 2, $ldec_debe, $lo_dataright);
			$lo_hoja->write($li_row, 3, $ldec_haber, $lo_dataright);
		}
		$li_row=$li_row+1;
		$lo_hoja->write($li_row, 1, "Total ".$ls_bolivares,$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
		$lo_hoja->write($li_row, 2, $ldec_totaldebe, $lo_dataright);
		$lo_hoja->write($li_row, 3, $ldec_totalhaber, $lo_dataright);

		$lo_libro->close();
		header("Content-Type: application/x-msexcel; name=\"movimientos_mes.xls\"");
		header("Content-Disposition: inline; filename=\"movimientos_mes.xls\"");
		$fh=fopen($lo_archivo, "rb");
		fpassthru($fh);
		unlink($lo_archivo);
		print("<script language=JavaScript>");
		print(" close();");
		print("</script>");
	}
?> 