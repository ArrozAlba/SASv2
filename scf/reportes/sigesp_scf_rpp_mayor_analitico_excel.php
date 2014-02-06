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
		global $io_fun_scf;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_scf->uf_load_seguridad_reporte("SCF","sigesp_scf_r_mayor_analitico.php",$ls_descripcion);
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
		global $io_funciones,$ia_niveles_scf;
		
		$ls_formato=""; $li_posicion=0; $li_indice=0;
		$dat_emp=$_SESSION["la_empresa"];
		//contable
		$ls_formato = trim($dat_emp["formcont"])."-";
		$li_posicion = 1 ;
		$li_indice   = 1 ;
		$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		do
		{
			$ia_niveles_scf[$li_indice] = $li_posicion;
			$li_indice   = $li_indice+1;
			$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		} while ($li_posicion>=0);
	}// end function uf_init_niveles
	//-----------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
		require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
		require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
		$lo_archivo = tempnam("/tmp", "mayor_analitico.xls");
		$lo_libro = &new writeexcel_workbookbig($lo_archivo);
		$lo_hoja = &$lo_libro->addworksheet();
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear la data necesaria del reporte
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
		require_once("../class_folder/class_funciones_scf.php");
		$io_fun_scf=new class_funciones_scf("../../");
		$ls_tiporeporte="0";
		$ls_bolivares="";
		if (array_key_exists("tiporeporte",$_GET))
		{
			$ls_tiporeporte=$_GET["tiporeporte"];
		}
		switch($ls_tiporeporte)
		{
			case "0":
				require_once("sigesp_scf_class_report.php");
				$io_report  = new sigesp_scf_class_report();
				$ls_bolivares ="Bs.";
				break;
	
			case "1":
				require_once("sigesp_scf_class_reportbsf.php");
				$io_report  = new sigesp_scf_class_reportbsf();
				$ls_bolivares ="Bs.F.";
				break;
		}
		$ia_niveles_scf[0]="";			
		uf_init_niveles();
		$li_total=count($ia_niveles_scf)-1;
	//---------------------------------------------------------------------------------------------------------------------------
	//Parámetros para Filtar el Reporte
	$ld_fecdes=$_GET["fecdes"];
	$ld_fechas=$_GET["fechas"];
	$ls_cuentadesde=$_GET["cuentadesde"];
	$ls_cuentahasta=$_GET["cuentahasta"];
	$ls_orden=$_GET["orden"];
	//---------------------------------------------------------------------------------------------------------------------------
	//Parámetros del encabezado
		$ldt_fecha="Desde   ".$ld_fecdes."   al   ".$ld_fechas."";
		$ls_titulo="Mayor  Analitico";       
	//---------------------------------------------------------------------------------------------------------------------------
	//Busqueda de la data
	$lb_valido=uf_insert_seguridad("<b>Mayor Analítico en Excel</b>"); // Seguridad de Reporte
	if($lb_valido)
	{
		 $lb_valido=$io_report->uf_mayoranalitico($ld_fecdes,$ld_fechas,$ls_cuentadesde,$ls_cuentahasta,$ls_orden);
	}
	//---------------------------------------------------------------------------------------------------------------------------
	// Impresión de la información encontrada en caso de que exista
	if($lb_valido===false) // Existe algún error ó no hay registros
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
		$lo_hoja->set_column(3,3,30);		
		$lo_hoja->set_column(4,4,20);
		$lo_hoja->set_column(5,5,13);
		$lo_hoja->set_column(6,8,30);
		$lo_hoja->write(0, 3, $ls_titulo,$lo_encabezado);
		$lo_hoja->write(1, 3, $ldt_fecha,$lo_encabezado);
		$io_report->DS->group_noorder("sc_cuenta");
		$li_tot=$io_report->DS->getRowCount("sc_cuenta");
		$ld_totaldebe=0;
		$ld_totalhaber=0;
		$ld_totalsaldo=0;
        $ld_saldo=0;
		$ldec_mondeb=0;
        $ldec_monhab=0;
		$li_row=2;
		$ls_cuenta_ant="";
		for($i=1;$i<=$li_tot;$i++)
		{
		    $li_tmp=($i+1);
			$ls_comprobante=$io_report->DS->getValue("comprobante",$i);
			$ls_cuenta=trim($io_report->DS->getValue("sc_cuenta",$i));
		    if ($i<$li_tot)
		    {
				$ls_cuenta_next=trim($io_report->DS->getValue("sc_cuenta",$li_tmp));
		    }
		    elseif($i=$li_tot)
		    {
				$ls_cuenta_next='no_next';
		    }
			$ls_denominacion=$io_report->DS->getValue("denominacion",$i);
			$ls_codpro=$io_report->DS->getValue("cod_pro",$i);
			$ls_cedbene=$io_report->DS->getValue("ced_bene",$i);
			$ls_nompro=$io_report->DS->getValue("nompro",$i);
			$ls_nombene=$io_report->DS->getValue("apebene",$i).", ".$io_report->DS->getValue("nombene",$i);
			$ls_nombre="";
			if($ls_codpro!="----------")
			{
				$ls_nombre=$ls_nompro;
			}			
			if($ls_cedbene!="----------")
			{
				$ls_nombre=$ls_nombene;
			}			

			$ls_documento=$io_report->DS->getValue("documento",$i);
			$ls_procede=$io_report->DS->getValue("procede",$i);
			$ls_concepto=$io_report->DS->getValue("descripcion",$i);
			$ldec_monto=$io_report->DS->getValue("monto",$i);
			$fecmov=$io_report->DS->getValue("fecha",$i);
			$ld_fecmov=$io_funciones->uf_convertirfecmostrar($fecmov);
			$ls_debhab=$io_report->DS->getValue("debhab",$i);
			$ld_saldo_ant=$io_report->DS->getValue("saldo_ant",$i);
			$li_totfil=0;
			$as_cuenta="";
			for($li=$li_total;$li>1;$li--)
			{
				$li_ant=$ia_niveles_scf[$li-1];
				$li_act=$ia_niveles_scf[$li];
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
			$li_fila=$ia_niveles_scf[1]+1;
			$as_cuenta=substr($ls_cuenta,0,$li_fila)."-".$as_cuenta;
			if($ls_cuenta_ant=="")
			{
				$ls_cuenta_ant=$ls_cuenta;
				$li_row=$li_row+1;
				$lo_hoja->write($li_row, 0, "Cuenta",$lo_titulo);
				$lo_hoja->write($li_row, 1, $as_cuenta,$lo_datacenter);
				$lo_hoja->write($li_row, 2, $ls_denominacion,$lo_libro->addformat(array('font'=>'Verdana','align'=>'left','size'=>'9')));
				$li_row=$li_row+1;
				$lo_hoja->write($li_row, 0, "Saldo Anterior ".$ls_bolivares,$lo_titulo);
				$lo_hoja->write($li_row, 1, $ld_saldo_ant,$lo_dataright);
				$li_row=$li_row+1;
				$lo_hoja->write($li_row, 0, "Procede",$lo_titulo);
				$lo_hoja->write($li_row, 1, "Comprobante",$lo_titulo);
				$lo_hoja->write($li_row, 2, "Concepto",$lo_titulo);
				$lo_hoja->write($li_row, 3, "Beneficiario",$lo_titulo);
				$lo_hoja->write($li_row, 4, "Documento",$lo_titulo);
				$lo_hoja->write($li_row, 5, "Fecha",$lo_titulo);
				$lo_hoja->write($li_row, 6, "Debe",$lo_titulo);
				$lo_hoja->write($li_row, 7, "Haber",$lo_titulo);
				$lo_hoja->write($li_row, 8, "Saldo Actual",$lo_titulo);
			}
			if($ls_debhab=='D')
			{
				$ldec_mondeb=$ldec_monto;
				$ldec_monhab=0;		
				$ld_totaldebe=$ld_totaldebe+$ldec_mondeb;
			}
			elseif($ls_debhab=='H')
			{
				$ldec_monhab=$ldec_monto;		
				$ldec_mondeb=0;
				$ld_totalhaber=$ld_totalhaber+$ldec_monhab;
			}
			if(!empty($ls_cuenta))
			{
				$ld_saldo=$ld_saldo_ant+$ldec_mondeb-$ldec_monhab;
			}
			else
			{
				if($ls_debhab=='D')
				{
					$ld_saldo=$ld_saldo+$ldec_monto;
				}
				elseif($ls_debhab=='H')
				{
					$ld_saldo=$ld_saldo-$ldec_monto;
				}
			}
			$ldec_mondeb=abs($ldec_mondeb);
			$ldec_monhab=abs($ldec_monhab);
			$ld_saldo_final=$ld_saldo;
			$li_row=$li_row+1;
			$lo_hoja->write($li_row, 0, $ls_procede,$lo_datacenter);
			$lo_hoja->write($li_row, 1, $ls_comprobante." ",$lo_datacenter);
			$lo_hoja->write($li_row, 2, $ls_concepto,$lo_dataleft);
			$lo_hoja->write($li_row, 3, $ls_nombre,$lo_dataleft);
			$lo_hoja->write($li_row, 4, $ls_documento." ",$lo_datacenter);
			$lo_hoja->write($li_row, 5, $ld_fecmov,$lo_datacenter);
			$lo_hoja->write($li_row, 6, $ldec_mondeb,$lo_dataright);
			$lo_hoja->write($li_row, 7, $ldec_monhab,$lo_dataright);
			$lo_hoja->write($li_row, 8, $ld_saldo_final,$lo_dataright);
			if (!empty($ls_cuenta_next))
			{
				$ldec_mondeb=abs($ldec_mondeb);
				$ldec_monhab=abs($ldec_monhab);
				$ld_saldo_final=$ld_saldo;
				$ld_saldo_anterior=$ld_saldo_ant;
				$li_row=$li_row+1;
				$lo_hoja->write($li_row, 4, "Total ".$ls_bolivares,$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
				$lo_hoja->write($li_row, 5, $ld_totaldebe,$lo_dataright);
				$lo_hoja->write($li_row, 6, $ld_totalhaber,$lo_dataright);
				$lo_hoja->write($li_row, 7, $ld_saldo_final,$lo_dataright);
				$ld_totaldebe=0;
				$ld_totalhaber=0;
				$ls_cuenta_next="";
				$ls_cuenta_ant="";
			}
		}
		$lo_libro->close();
		header("Content-Type: application/x-msexcel; name=\"mayor_analitico.xls\"");
		header("Content-Disposition: inline; filename=\"mayor_analitico.xls\"");
		$fh=fopen($lo_archivo, "rb");
		fpassthru($fh);
		unlink($lo_archivo);
		print("<script language=JavaScript>");
		print(" close();");
		print("</script>");
	}
?> 