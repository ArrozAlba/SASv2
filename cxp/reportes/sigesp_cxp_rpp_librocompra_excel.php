<?php
    session_start();   
	ini_set('memory_limit','512M');
	ini_set('max_execution_time','0');


	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_periodo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Ing. Nelson Barraez
		// Fecha Creación: 15/07/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Generó el Reporte Libro de Compra para el periodo ".$as_periodo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_librocompra.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
	require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
	require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
	$lo_archivo = tempnam("/home/production/tmp", "libro_compra.xls");
	$lo_libro = &new writeexcel_workbookbig($lo_archivo);
	$lo_hoja = &$lo_libro->addworksheet();
	
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear la data necesaria del reporte
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_cxp_class_report.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/class_fecha.php");
    $io_fecha = new class_fecha();
	$io_in    = new sigesp_include();
	$con      = $io_in->uf_conectar();
    $io_sql   = new class_sql($con);
	$io_report= new sigesp_cxp_class_report("../../");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();		
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();		
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------	
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_mes=$io_fun_cxp->uf_obtenervalor_get("mes","");
	$ls_agno=$io_fun_cxp->uf_obtenervalor_get("agno","");	
	$ls_titulo     = "LIBRO DE COMPRA";
	$li_lastday    = $io_fecha->uf_last_day($ls_mes,$ls_agno);
	$li_lastday    = substr($li_lastday,0,2);
	$as_fechadesde = $ls_agno.'-'.$ls_mes.'-01';
	$as_fechahasta = $ls_agno.'-'.$ls_mes.'-'.$li_lastday;
	$ls_mesletras        = $io_fecha->uf_load_nombre_mes($ls_mes);
	$ls_periodo    = "MES: ".$ls_mesletras."    AÑO: ".$ls_agno."";
	
	//---------------------------------------------------------------------------------------------------------------------------
	//Busqueda de la data 
	$ld_monto    = 0;
	$ld_impuesto = 0;
	$ld_sumcom   = 0;
	$ld_baseimp  = 0;
	$arremp      = $_SESSION["la_empresa"];
    $ls_codemp   = $arremp["codemp"];
	$lb_valido=true;	
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		//-------formato para el reporte----------------------------------------------------------
		$lo_encabezado= &$lo_libro->addformat();
		$lo_encabezado->set_bold();
		$lo_encabezado->set_font("Verdana");
		$lo_encabezado->set_align('center');
		$lo_encabezado->set_size('11');
		$lo_titulo= &$lo_libro->addformat();
		$lo_titulo->set_text_wrap();
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
		
		$lo_dataright2= &$lo_libro->addformat(array(num_format => '#,##'));
		$lo_dataright2->set_font("Verdana");
		$lo_dataright2->set_align('right');
		$lo_dataright2->set_size('9');	
		$lo_hoja->set_column(0,0,50);
		$lo_hoja->set_column(1,2,15);	
		$lo_hoja->set_column(3,3,40);
		$lo_hoja->set_column(4,4,25);
		$lo_hoja->set_column(5,5,15);
		$lo_hoja->set_column(6,6,20);	
		$lo_hoja->set_column(7,7,20);	
		$lo_hoja->set_column(8,8,15);	
		$lo_hoja->set_column(9,9,15);
		$lo_hoja->set_column(10,10,15);	
		$lo_hoja->set_column(12,12,15);	
		$lo_hoja->set_column(13,13,15);	
		$lo_hoja->set_column(14,14,15);	
		$lo_hoja->set_column(15,15,15);
		$lo_hoja->set_column(16,16,15);	
		$lo_hoja->set_column(17,17,10);	
		$lo_hoja->set_column(18,18,15);	
		$lo_hoja->set_column(19,19,15);		
		$lo_hoja->write(0,3,$ls_titulo,$lo_encabezado);
		$lo_hoja->write(1,3,$ls_periodo,$lo_encabezado);			
		$lo_hoja->write(4,0, "Nro. de Cheque",$lo_titulo);	
		$lo_hoja->write(4,1, "Nro. Oper",$lo_titulo);
		$lo_hoja->write(4,2, "Fecha Factura",$lo_titulo);	
		$lo_hoja->write(4,3, "RIF",$lo_titulo);	
		$lo_hoja->write(4,4, "Número o Razón Social",$lo_titulo);
		$lo_hoja->write(4,5, "Número de Compra",$lo_titulo);
		$lo_hoja->write(4,6, "Tipo Prov.",$lo_titulo);	
		$lo_hoja->write(4,7, "Nro Planilla Importacin (C-80 o c-81)",$lo_titulo);
		$lo_hoja->write(4,8, "Nro Expediente Importacin",$lo_titulo);
		$lo_hoja->write(4,9, "Nro de Factura",$lo_titulo);
		$lo_hoja->write(4,10, "Nro de Control",$lo_titulo);
		$lo_hoja->write(4,11, "Nro Nota Debito",$lo_titulo);
		$lo_hoja->write(4,12, "Nro Nota Credito",$lo_titulo);
		$lo_hoja->write(4,13, "Tipo de Transacc.",$lo_titulo);
		$lo_hoja->write(4,14, "Nro de Factura Afectada",$lo_titulo);
		$lo_hoja->write(4,15, "Total de Compra Incluyendo IVA",$lo_titulo);
		$lo_hoja->write(4,16, "Compra sin Derecho a Credito IVA",$lo_titulo);
		$lo_hoja->write(3,17, "Compras",$lo_titulo);
		$lo_hoja->write(3,18, "Internas o",$lo_titulo);
		$lo_hoja->write(3,19, "Importaciones",$lo_titulo);
		$lo_hoja->write(4,17, "Base Imponible",$lo_titulo);
		$lo_hoja->write(4,18, " % ",$lo_titulo);
		$lo_hoja->write(4,19, "Impuesto Iva",$lo_titulo);
		$lo_hoja->write(4,20, "IVA Retenido (Vendedor)",$lo_titulo);
		//------------------------------------------------------------------------------------------------------
		$lb_valido=$io_report->uf_select_report_libcompra($as_fechadesde,$as_fechahasta,&$rs_resultado);	
		$ldec_totimp8      = 0; //Variable de tipo acumulador que almacenara el monto total de los impuestos a 8%.
		$ldec_totimp9      = 0; //Variable de tipo acumulador que almacenara el monto total de los impuestos a 14%.
		$ldec_totimp25     = 0; //Variable de tipo acumulador que almacenara el monto total de los impuestos a 25%.
		$ldec_totimpret8   = 0; //Variable de tipo acumulador que almacenara el monto total retenido de los impuestos a 8%.
		$ldec_totimpret9   = 0; //Variable de tipo acumulador que almacenara el monto total retenido de los impuestos a 14%.
		$ldec_totimpret25  = 0; //Variable de tipo acumulador que almacenara el monto total retenido de los impuestos a 25%.
		$ldec_totbasimp8   = 0;
		$ldec_totbasimp9   = 0;
		$ldec_totbasimp25  = 0;
		$ldec_totcomsiniva = 0;
		if($lb_valido)
		{	
		    $li=0;
			$li_row=4;
		    while($row=$io_report->io_sql->fetch_row($rs_resultado))	
			{
				$li++;
				$ldec_monret=0;
				$ls_numrecdoc=$row["numrecdoc"];
				$ls_tipproben=$row["tipproben"];
				$ls_codpro=$row["cod_pro"];
				$ls_cedben=$row["ced_bene"];
				$ldec_montoret=$row["monret"];
				$ldec_montodoc=$row["montotdoc"];
				$ldec_mondeddoc=$row["mondeddoc"];
				$ls_codtipdoc =$row["codtipdoc"];
				if($ls_tipproben=='P')
				{
					$la_provben=$io_report->uf_select_rowdata($io_sql,"SELECT * FROM rpc_proveedor WHERE cod_pro='".$ls_codpro."'");
					$ls_rif=$la_provben["rifpro"];
					$ls_nombre=$la_provben["nompro"];
				}	
				else
				{
					$la_provben=$io_report->uf_select_rowdata($io_sql,"SELECT * FROM rpc_beneficiario
					                                                    WHERE ced_bene='".$ls_cedben."'");
					$ls_rif=$la_provben["rifben"];
					$ls_nombre=$la_provben["nombene"]." ".$la_provben["apebene"];
				}
				$la_notas=$io_report->uf_select_rowdata($io_sql,"SELECT * FROM cxp_sol_dc 
				                                                  WHERE numrecdoc='".$ls_numrecdoc."' 
																    AND codtipdoc='".$ls_codtipdoc."' 
																	AND cod_pro='".$ls_codpro."' 
																	AND ced_bene='".$ls_cedben."'");
				if(count($la_notas)>0)
				{
					$ls_codope=$la_notas["codope"];
					$ls_numnota=$la_notas["numdc"];
					if($ls_codope=='NC')
					{
						$ls_numnc=$ls_numnota;
						$ls_numnd="";
					}
					else
					{
						$ls_numnd=$ls_numnota;
						$ls_numnc="";
					}
				}
				else
				{
						$ls_numnc="";
						$ls_numnd="";
				}
				$ls_cheque=$io_report->uf_select_data($io_sql,"SELECT distinct cxp_sol_banco.numdoc AS numdoc".
															  "  FROM cxp_dt_solicitudes, cxp_sol_banco".
															  " WHERE cxp_dt_solicitudes.codemp='".$ls_codemp."'".
															  "   AND cxp_dt_solicitudes.numrecdoc='".$ls_numrecdoc."'".
															  "   AND cxp_dt_solicitudes.cod_pro='".$ls_codpro."'".
															  "   AND cxp_dt_solicitudes.ced_bene='".$ls_cedben."'".
															  "   AND cxp_dt_solicitudes.codemp=cxp_sol_banco.codemp".
															  "   AND cxp_dt_solicitudes.numsol=cxp_sol_banco.numsol","numdoc");
				if($ls_tiporeporte==1)
				{
					$la_cmpret=$io_report->uf_select_rowdata($io_sql,"SELECT DISTINCT max(a.numrecdoc) as numrecdoc,
					                                                                  max(a.monobjretaux) as 
																					  monobjret,SUM(a.monretaux) as 
																					  monret,max(a.porded) as porded,
																					  max(b.codret) as codret,max(b.numcom) as 
																					  numcom,max(b.iva_retaux) as
																					  iva_ret,max(tiptrans) as tiptrans".
																    "   FROM cxp_rd_deducciones a,scb_dt_cmp_ret b     ".
																    "  WHERE a.codemp=b.codemp AND a.numrecdoc=b.numfac AND 
																	         a.codemp='".$ls_codemp."' AND 
																			 a.numrecdoc='".$ls_numrecdoc."' AND ".
																    "        a.cod_pro='".$ls_codpro."' AND 
																	         a.ced_bene='".$ls_cedben."'         ".
																    " GROUP BY a.numrecdoc ");
				}
				else
				{
					$la_cmpret=$io_report->uf_select_rowdata($io_sql,"SELECT DISTINCT max(a.numrecdoc) as numrecdoc,
					                                                                  max(a.monobjret) as monobjret,
																					  SUM(a.monret) as monret,max(a.porded) as 
																					  porded,max(b.codret) as codret,
																					  max(b.numcom) as numcom,max(b.iva_ret) as
																					  iva_ret,max(tiptrans) as tiptrans".
																    "   FROM cxp_rd_deducciones a,scb_dt_cmp_ret b            ".
																    "  WHERE a.codemp=b.codemp AND a.numrecdoc=b.numfac AND 
																	         a.codemp='".$ls_codemp."' AND 
																			 a.numrecdoc='".$ls_numrecdoc."' AND ".
																    "        a.cod_pro='".$ls_codpro."' AND 
																	         a.ced_bene='".$ls_cedben."'".
																	" GROUP BY a.numrecdoc ");
				}
				if(count($la_cmpret)>0)
				{
					$ls_codret = $la_cmpret["codret"];
					if ($ls_codret=='0000000001')
					{
						 $ldec_monret    = $la_cmpret["monret"];  
						 $ls_cmpret      = $la_cmpret["numcom"];
						 $ldec_monobjret = $la_cmpret["monobjret"];
						 $ldec_porded    = $la_cmpret["porded"];
						 $ldec_ivaret    = $la_cmpret["iva_ret"];
						 $ls_tiptrans    = $la_cmpret["tiptrans"];
					}
					else
					{
						 $ldec_monret    = 0; 
						 $ls_cmpret      = '';
						 $ldec_monobjret = 0;
						 $ldec_porded    = 0;
						 $ldec_ivaret    = 0;
						 $ls_tiptrans    = "";
					}													  
				}
				else
				{
					$ldec_monret    = $ldec_monret+$ldec_montoret;  
					$ls_cmpret      = '';
					$ldec_monobjret = 0;
					$ldec_porded    = 0;
					$ls_tiptrans    = "";
					$ldec_ivaret    = 0;
				}	
				if($ls_tiporeporte==1)
				{
					$la_cargos=$io_report->uf_select_rowdata($io_sql,"SELECT monobjretaux as basimp,porcar,monretaux as impiva".
																	 "  FROM cxp_rd_cargos ".
																	 " WHERE codemp='".$ls_codemp."'".
																	 "   AND numrecdoc='".$ls_numrecdoc."'".
																	 "   AND cod_pro='".$ls_codpro."'".
																	 "   AND ced_bene='".$ls_cedben."'");
				}
				else
				{
					$la_cargos=$io_report->uf_select_rowdata($io_sql,"SELECT monobjret as basimp,porcar,monret as impiva".
																	 "  FROM cxp_rd_cargos ".
																	 " WHERE codemp='".$ls_codemp."'".
																	 "   AND numrecdoc='".$ls_numrecdoc."'".
																	 "   AND cod_pro='".$ls_codpro."'".
																	 "   AND ced_bene='".$ls_cedben."'");
				}
				if(count($la_cargos)>0)
				{
					$ldec_porcar=$la_cargos["porcar"];
					$ldec_baseimp=$la_cargos["basimp"];
					$ldec_monimp=$la_cargos["impiva"];
				}
				else
				{
					$ldec_porcar="";
					$ldec_baseimp=0;
					$ldec_monimp=0;				
				}					
				$ldec_montodoc     = $ldec_montodoc+$ldec_mondeddoc;			 
				$ldec_sinderiva    = $ldec_montodoc-$ldec_baseimp-$ldec_monimp;// Total de la Compra sin Derecho a Crédito Iva.		
				 $fecha= $io_report->io_funciones->uf_convertirfecmostrar($row["fecemidoc"]);			  
				 $li_row=$li_row+1; 
				 $lo_hoja->write($li_row, 0, $ls_cheque, $lo_datacenter);
				 $lo_hoja->write($li_row, 1, $li, $lo_datacenter);
				 $lo_hoja->write($li_row, 2, $fecha, $lo_datacenter);
				 $lo_hoja->write($li_row, 3, $ls_rif, $lo_datacenter);
				 $lo_hoja->write($li_row, 4, $ls_nombre, $lo_dataleft);
				 $lo_hoja->write($li_row, 5, $ls_cmpret, $lo_datacenter);
				 $lo_hoja->write($li_row, 6, $ls_tipproben, $lo_datacenter);
				 $lo_hoja->write($li_row, 7, '', $lo_datacenter);
				 $lo_hoja->write($li_row, 8, '', $lo_datacenter);
				 $lo_hoja->write($li_row, 9, trim($ls_numrecdoc), $lo_datacenter);
				 $lo_hoja->write($li_row, 10, $row["numref"], $lo_datacenter);
				 $lo_hoja->write($li_row, 11,$ls_numnd, $lo_datacenter);
				 $lo_hoja->write($li_row, 12,$ls_numnc, $lo_datacenter);
				 $lo_hoja->write($li_row, 13,$ls_tiptrans, $lo_datacenter);
				 $lo_hoja->write($li_row, 14,trim($row["numrecdoc"]), $lo_datacenter);
				 $lo_hoja->write($li_row, 15,$ldec_montodoc, $lo_dataright);
				 $lo_hoja->write($li_row, 16,$ldec_sinderiva, $lo_dataright);
				 $lo_hoja->write($li_row, 17,$ldec_baseimp, $lo_dataright);
				 $lo_hoja->write($li_row, 18,$ldec_porcar, $lo_datacenter);
				 $lo_hoja->write($li_row, 19,$ldec_monimp, $lo_dataright);
				 $lo_hoja->write($li_row, 20,$ldec_montoret, $lo_dataright);
				
				 								 
				 $li_porcentaje   = intval($ldec_porcar);
				 if ($ldec_porcar>0)
				 {
					switch ($li_porcentaje){
					  case '8':
						$ldec_totimp8    = ($ldec_totimp8+$ldec_monimp);
						$ldec_totbasimp8 = ($ldec_totbasimp8+$ldec_baseimp);
						$ldec_totimpret8    = ($ldec_totimpret8+$ldec_montoret);
						break;
					  case '9'||'14'||'11':
						$ldec_totimp9    = ($ldec_totimp9+$ldec_monimp);  
						$ldec_totbasimp9 = ($ldec_totbasimp9+$ldec_baseimp);
						$ldec_totimpret9    = ($ldec_totimpret9+$ldec_montoret);
						break;
					  case '25':
						$ldec_totimp25    = ($ldec_totimp25+$ldec_monimp);  
						$ldec_totbasimp25 = ($ldec_totbasimp28+$ldec_baseimp);
						$ldec_totimpret25    = ($ldec_totimpret25+$ldec_montoret);
						break;
					  }
				 }	
				 $ldec_totcomsiniva = ($ldec_totcomsiniva+$ldec_sinderiva);	
				 $ldec_basimpga     = ($ldec_totbasimp9+$ldec_totbasimp25);//Total Base Imponible Compras Internas Afectadas 
				                                                           //en Alicuota General + Adicional(9% y 25%).
				 $ldec_totgenadi    = ($ldec_totimp9+$ldec_totimp25);//Total Compras Internas Afectadas en Alicuota Reducida.
				                                                     //Impuestos al 9% y 25%.
	
			}
			$li_row1=$li_row+2;
			$lo_hoja->write($li_row1, 0,'', $lo_titulo);
			$lo_hoja->write($li_row1, 1,'', $lo_titulo);
			$lo_hoja->write($li_row1, 2,"Base Imponible", $lo_titulo);
			$lo_hoja->write($li_row1, 3,'', $lo_titulo);
			$lo_hoja->write($li_row1, 4,"Credito Fiscal", $lo_titulo);
			$lo_hoja->write($li_row1, 5,"Iva Retenido a Terceros", $lo_titulo);
			$lo_hoja->write($li_row1, 6,"Anticipo IVA", $lo_titulo);
			
			$li_row2=$li_row1+1;			
			$lo_hoja->write($li_row2, 0,"Total: Compras Exentas y/o sin derecho a credito fiscal",$lo_dataleft);
			$lo_hoja->write($li_row2, 1,"30", $lo_datacenter);
			$lo_hoja->write($li_row2, 2, $ldec_totcomsiniva, $lo_dataright);
			$lo_hoja->write($li_row2, 3,'', $lo_datacenter);
			$lo_hoja->write($li_row2, 4,'', $lo_datacenter);
			$lo_hoja->write($li_row2, 5,'', $lo_datacenter);
			$lo_hoja->write($li_row2, 6,'', $lo_datacenter);
			
			$li_row3=$li_row2+1;			
			$lo_hoja->write($li_row3, 0,"E de las: Compras Importacion Afectadas Alicuota General", $lo_dataleft);
			$lo_hoja->write($li_row3, 1,"31", $lo_datacenter);
			$lo_hoja->write($li_row3, 2, '', $lo_dataright);
			$lo_hoja->write($li_row3, 3,"32", $lo_datacenter);
			$lo_hoja->write($li_row3, 4,'', $lo_datacenter);
			$lo_hoja->write($li_row3, 5,'', $lo_datacenter);
			$lo_hoja->write($li_row3, 6,'', $lo_datacenter);
			
			$li_row4=$li_row3+1;			
			$lo_hoja->write($li_row4, 0,"E de las: Compras Importacion Afectadas en Alicuota General + Adicional",$lo_dataleft);
			$lo_hoja->write($li_row4, 1,"312", $lo_datacenter);
			$lo_hoja->write($li_row4, 2, '', $lo_dataright);
			$lo_hoja->write($li_row4, 3,"322", $lo_datacenter);
			$lo_hoja->write($li_row4, 4,'', $lo_datacenter);
			$lo_hoja->write($li_row4, 5,'', $lo_datacenter);
			$lo_hoja->write($li_row4, 6,'', $lo_datacenter);			
			
			$li_row5=$li_row4+1;			
			$lo_hoja->write($li_row5, 0,"E de las: Compras Importacion Afectadas en Alicuota Reducida",$lo_dataleft);
			$lo_hoja->write($li_row5, 1,"313", $lo_datacenter);
			$lo_hoja->write($li_row5, 2, '', $lo_dataright);
			$lo_hoja->write($li_row5, 3,"323", $lo_datacenter);
			$lo_hoja->write($li_row5, 4,'', $lo_datacenter);
			$lo_hoja->write($li_row5, 5,'', $lo_datacenter);
			$lo_hoja->write($li_row5, 6,'', $lo_datacenter);
			
			$li_row6=$li_row5+1;			
			$lo_hoja->write($li_row6, 0,"E de las: Compras Internas Afectadas solo en Alicuota General",$lo_dataleft);
			$lo_hoja->write($li_row6, 1,"33", $lo_datacenter);
			$lo_hoja->write($li_row6, 2, $ldec_totbasimp9, $lo_dataright);
			$lo_hoja->write($li_row6, 3,"34", $lo_datacenter);
			$lo_hoja->write($li_row6, 4,$ldec_totimp9, $lo_dataright);
			$lo_hoja->write($li_row6, 5,'', $lo_datacenter);
			$lo_hoja->write($li_row6, 6,'', $lo_datacenter);
			
			$li_row7=$li_row6+1;			
			$lo_hoja->write($li_row7, 0,"E de las: Compras Internas Afectadas en Alicuota General + Adicional",$lo_dataleft);
			$lo_hoja->write($li_row7, 1,"332", $lo_datacenter);
			$lo_hoja->write($li_row7, 2, $ldec_basimpga, $lo_dataright);
			$lo_hoja->write($li_row7, 3,"342", $lo_datacenter);
			$lo_hoja->write($li_row7, 4,$ldec_totgenadi, $lo_dataright);
			$lo_hoja->write($li_row7, 5,'', $lo_datacenter);
			$lo_hoja->write($li_row7, 6,'', $lo_datacenter);
			
			$li_row8=$li_row7+1;			
			$lo_hoja->write($li_row8, 0,"E de las: Compras Internas Afectadas en Alicuota Reducida",$lo_dataleft);
			$lo_hoja->write($li_row8, 1,"333", $lo_datacenter);
			$lo_hoja->write($li_row8, 2, $ldec_totbasimp8, $lo_dataright);
			$lo_hoja->write($li_row8, 3,"343", $lo_datacenter);
			$lo_hoja->write($li_row8, 4, $ldec_totbasimp8, $lo_dataright);
			$lo_hoja->write($li_row8, 5,'', $lo_datacenter);
			$lo_hoja->write($li_row8, 6,'', $lo_datacenter);
			
			$li_row9=$li_row8+1;			
			$lo_hoja->write($li_row9, 0,"",$lo_dataleft);
			$lo_hoja->write($li_row9, 1,"35", $lo_datacenter);
			$lo_hoja->write($li_row9, 2, '', $lo_dataright);
			$lo_hoja->write($li_row9, 3,"36", $lo_datacenter);
			$lo_hoja->write($li_row9, 4, '', $lo_dataright);
			$lo_hoja->write($li_row9, 5,'', $lo_datacenter);
			$lo_hoja->write($li_row9, 6,'', $lo_datacenter);
			
			$li_row10=$li_row9+2;			
			$lo_hoja->write($li_row10, 0,"Compras Exentas:",$lo_titulo);
			$lo_hoja->write($li_row10, 1,$ldec_totcomsiniva, $lo_dataright);
			
			$li_row11=$li_row10+1;			
			$lo_hoja->write($li_row11, 0,"Total del 8% de IVA Retenido:",$lo_titulo);
			$lo_hoja->write($li_row11, 1,$ldec_totimpret8, $lo_dataright);
			
		    $li_row12=$li_row11+1;			
			$lo_hoja->write($li_row12, 0,"Total del 11% de IVA Retenido:",$lo_titulo);
			$lo_hoja->write($li_row12, 1,$ldec_totimpret9, $lo_dataright);

            $li_row13=$li_row12+1;			
			$lo_hoja->write($li_row13, 0,"Total del 25% de IVA Retenido:",$lo_titulo);
			$lo_hoja->write($li_row13, 1,$ldec_totimpret25, $lo_dataright);			
			
			$lo_libro->close();
			header("Content-Type: application/x-msexcel; name=\"libro_compra.xls\"");
			header("Content-Disposition: inline; filename=\"libro_compra.xls\"");
			$fh=fopen($lo_archivo, "rb");
			fpassthru($fh);
			unlink($lo_archivo);		
			print("<script language=JavaScript>");
			print(" close();");
			print("</script>");
			unset($io_pdf);
		}
	}/// fin de else // Imprimimos el reporte	
?> 