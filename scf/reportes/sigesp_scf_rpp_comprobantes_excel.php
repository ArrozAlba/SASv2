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
		$lb_valido=$io_fun_scf->uf_load_seguridad_reporte("SCF","sigesp_scf_r_comprobantes.php",$ls_descripcion);
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
		$lo_archivo = tempnam("/tmp", "comprobante.xls");
		$lo_libro = &new writeexcel_workbookbig($lo_archivo);
		$lo_hoja = &$lo_libro->addworksheet();
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear la data necesaria del reporte
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();	
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
	 $ls_comprobantedesde=$_GET["comprobantedesde"];
	 $ls_comprobantehasta=$_GET["comprobantehasta"];
	 $ls_procededesde=$_GET["procededesde"];
	 $ls_procedehasta=$_GET["procedehasta"];
	 $ld_fecdes=$_GET["fecdes"];
	 $ld_fechas=$_GET["fechas"];
	 $ls_orden=$_GET["orden"];
	//---------------------------------------------------------------------------------------------------------------------------
	//Parámetros del encabezado
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		
		$ld_fecdes=substr($ld_fecdes,0,10);
		$ldt_fecdes_cab=$io_funciones->uf_convertirfecmostrar($ld_fecdes);
		$ld_fechas=substr($ld_fechas,0,10);
		$ldt_fechas_cab=$io_funciones->uf_convertirfecmostrar($ld_fechas);
		$ldt_fecha_cab="Desde  ".$ldt_fecdes_cab."  al  ".$ldt_fechas_cab."";
		$ls_titulo="COMPROBANTES  CONTABLES ";
	//---------------------------------------------------------------------------------------------------------------------------
	//Busqueda de la data
	$lb_valido=uf_insert_seguridad("<b>Comprobante en Excel</b>"); // Seguridad de Reporte
	if($lb_valido)
	{
		 $lb_valido=$io_report->uf_comprobante_cabecera($ld_fecdes,$ld_fechas,$ls_comprobantedesde,$ls_comprobantehasta,
		 												$ls_procededesde,$ls_procedehasta,$ls_orden);
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
		$lo_hoja->set_column(1,2,50);
		$lo_hoja->set_column(3,3,25);
		$lo_hoja->set_column(4,5,20);
		$lo_hoja->write(0, 2, $ls_titulo,$lo_encabezado);
		$lo_hoja->write(1, 2, $ldt_fecha_cab,$lo_encabezado);
		$li_row=2;
		$li_tot=$io_report->DS->getRowCount("comprobante");
		$ld_totald=0;
		$ld_totalh=0;
		for($li_i=1;$li_i<=$li_tot;$li_i++)
		{
			$ld_totaldebe=0;
			$ld_totalhaber=0;
			$ls_comprobante=$io_report->DS->data["comprobante"][$li_i];
			$ldt_fecha=$io_report->DS->data["fecha"][$li_i];
			$ls_procede=$io_report->DS->data["procede"][$li_i];
			$ls_ced_bene=$io_report->DS->data["ced_bene"][$li_i];
			$ls_cod_pro=$io_report->DS->data["cod_pro"][$li_i];
			$ls_tipo_destino=$io_report->DS->data["tipo_destino"][$li_i];
			$ls_codban=$io_report->DS->data["codban"][$li_i];
			$ls_ctaban=$io_report->DS->data["ctaban"][$li_i];
			$ls_destino="Beneficiario";
			if($ls_tipo_destino=="P")
			{
				$ls_destino="Proveedor";
			}
			switch($ls_tipo_destino)
			{
				case "P":
					$ls_nomproben=$io_report->DS->data["nompro"][$li_i];
					break;
				case "B":
					$ls_nomproben=$io_report->DS->data["apebene"][$li_i].", ".$io_report->DS->data["nombene"][$li_i];
					break;
				default:
					$ls_nomproben="";
					break;
			}			
			$ldt_fec=$io_funciones->uf_convertirfecmostrar($ldt_fecha);
			$li_row=$li_row+1;
			$lo_hoja->write($li_row, 0, "Comprobante",$lo_titulo);
			$lo_hoja->write($li_row, 1, $ls_procede."---".$ls_comprobante,$lo_dataleft);
			$lo_hoja->write($li_row, 2, $ldt_fec,$lo_datacenter);
			$li_row=$li_row+1;
			$lo_hoja->write($li_row, 0, $ls_destino,$lo_titulo);
			$lo_hoja->write($li_row, 1, $ls_nomproben,$lo_dataleft);
			$lb_valido=$io_report->uf_comprobante_detalle($ls_procede,$ls_comprobante,$ldt_fecha,$ls_codban,$ls_ctaban);
			if($lb_valido)
			{
				$li_row=$li_row+1;
				$lo_hoja->write($li_row, 0, "Cuenta", $lo_titulo);
				$lo_hoja->write($li_row, 1, "Denominación", $lo_titulo);
				$lo_hoja->write($li_row, 2, "Descripción", $lo_titulo);
				$lo_hoja->write($li_row, 3, "Documento", $lo_titulo);
				$lo_hoja->write($li_row, 4, "Debe", $lo_titulo);
				$lo_hoja->write($li_row, 5, "Haber", $lo_titulo);
				$li_totrow_det=$io_report->DS_detalle->getRowCount("comprobante");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_comprobante=$io_report->DS_detalle->data["comprobante"][$li_s];
					$ls_sc_cuenta=trim($io_report->DS_detalle->data["sc_cuenta"][$li_s]);
					$li_totfil=0;
					$as_cuenta="";
					for($li=$li_total;$li>1;$li--)
					{
						$li_ant=$ia_niveles_scf[$li-1];
						$li_act=$ia_niveles_scf[$li];
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
					$li_fila=$ia_niveles_scf[1]+1;
					$as_cuenta=substr($ls_sc_cuenta,0,$li_fila)."-".$as_cuenta;
					$ls_procede_doc=$io_report->DS_detalle->data["procede_doc"][$li_s];
					$ls_debhab=$io_report->DS_detalle->data["debhab"][$li_s];
					$ld_monto=$io_report->DS_detalle->data["monto"][$li_s];
					$ls_denominacion=$io_report->DS_detalle->data["denominacion"][$li_s];
					$ls_CMP_descripcion=$io_report->DS_detalle->data["cmp_descripcion"][$li_s];
					if($ls_debhab=='D')
					{
					   $ld_debe=$ld_monto;
					   $ld_totaldebe=$ld_totaldebe+$ld_monto;
					   $ld_haber="";
					}
					if($ls_debhab=='H')
					{
					   $ld_haber=$ld_monto;
					   $ld_totalhaber=$ld_totalhaber+$ld_monto;
					   $ld_debe="";
					}
					$ls_documentoproc=$ls_procede_doc."-".$ls_comprobante;
					$li_row=$li_row+1;
					$lo_hoja->write($li_row, 0, $as_cuenta, $lo_datacenter);
					$lo_hoja->write($li_row, 1, $ls_denominacion, $lo_dataleft);
					$lo_hoja->write($li_row, 2, $ls_CMP_descripcion, $lo_dataleft);
					$lo_hoja->write($li_row, 3, $ls_documentoproc, $lo_datacenter);
					$lo_hoja->write($li_row, 4, $ld_debe, $lo_dataright);
					$lo_hoja->write($li_row, 5, $ld_haber, $lo_dataright);
				}
				$li_row=$li_row+1;
				$ld_totald=$ld_totald+$ld_totaldebe;
				$ld_totalh=$ld_totalh+$ld_totalhaber;
				$lo_hoja->write($li_row, 3, "Total Comprobante ".$ls_bolivares,$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
				$lo_hoja->write($li_row, 4, $ld_totaldebe, $lo_dataright);
				$lo_hoja->write($li_row, 5, $ld_totalhaber, $lo_dataright);
				$li_row=$li_row+1;
			}
		}
		$li_row=$li_row+1;
		$lo_hoja->write($li_row, 3, "Total ".$ls_bolivares,$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'10')));
		$lo_hoja->write($li_row, 4, $ld_totald, $lo_dataright);
		$lo_hoja->write($li_row, 5, $ld_totalh, $lo_dataright);
		$lo_libro->close();
		header("Content-Type: application/x-msexcel; name=\"comprobante.xls\"");
		header("Content-Disposition: inline; filename=\"comprobante.xls\"");
		$fh=fopen($lo_archivo, "rb");
		fpassthru($fh);
		unlink($lo_archivo);
		print("<script language=JavaScript>");
		print(" close();");
		print("</script>");
	}
?> 