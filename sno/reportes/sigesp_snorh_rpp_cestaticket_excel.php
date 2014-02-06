<?php
    session_start();   
	ini_set('memory_limit','512M');
	ini_set('max_execution_time','0');

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_cestaticket.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
	require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
	require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
	$lo_archivo = tempnam("/home/production/tmp", "listado_personal.xls");
	$lo_libro = &new writeexcel_workbookbig($lo_archivo);
	$lo_hoja = &$lo_libro->addworksheet();
	
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear la data necesaria del reporte
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="Listado de Personal";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_codconcdes=$io_fun_nomina->uf_obtenervalor_get("codconcdes","");
	$ls_codconchas=$io_fun_nomina->uf_obtenervalor_get("codconchas","");
	$ls_mes=$io_fun_nomina->uf_obtenervalor_get("mes","");
	$ls_ano=$io_fun_nomina->uf_obtenervalor_get("ano","");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_codperi=$io_fun_nomina->uf_obtenervalor_get("codperi","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_orden="5".trim($ls_orden);
	$ls_tiporeporte=$io_fun_nomina->uf_obtenervalor_get("tiporeporte","");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("codsubnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("codsubnomhas","");
	
	//---------------------------------------------------------------------------------------------------------------------------
	//Busqueda de la data 
	$lb_valido=uf_insert_seguridad("<b>REPORTE DE PERSONAL PARA EL CONTROL DEL PROGRAMA DE ALIMENTACIÓN</b>"); // Seguridad de Reporte
	$lb_valido=true;
	$ls_titulo="REPORTE DE PERSONAL PARA EL CONTROL DEL PROGRAMA DE ALIMENTACIÓN";
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_cestaticket_personal_excel($ls_codnomdes,$ls_codnomhas,$ls_ano,$ls_mes,$ls_codperi,
															 $ls_codconcdes,$ls_codconchas,$ls_conceptocero,$ls_subnomdes,
															 $ls_subnomhas,$ls_orden);
	}
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
		
		$lo_hoja->set_column(0,0,20);
		$lo_hoja->set_column(1,1,45);
		$lo_hoja->set_column(2,2,20);
		$lo_hoja->set_column(3,3,35);
		//---------------------------------------------------------------------------------------------
		$lo_hoja->set_column(0,0,20);
		$lo_hoja->set_column(1,1,60);
		$lo_hoja->write(0,1,$ls_titulo,$lo_encabezado);
		$lo_hoja->write(1,1,$ls_rango,$lo_encabezado);
		
		$ls_coduniadmact="";
		$ls_desuniadmact="";
		$ls_desuniadm="";
		$ls_coduniadm="";
		$li_contador=0;
		$li_totrow=$io_report->DS->getRowCount("cedper");
		$li_row=3;
		$li_total5=0;
		$li_total6=0;
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_coduniadm=$io_report->DS->data["minorguniadm"][$li_i].
			              $io_report->DS->data["ofiuniadm"][$li_i].
						  $io_report->DS->data["uniuniadm"][$li_i].
						  $io_report->DS->data["depuniadm"][$li_i].
						  $io_report->DS->data["prouniadm"][$li_i];
			$ls_desuniadm=$io_report->DS->data["desuniadm"][$li_i];	
			
				
			if ($ls_cedper!=$ls_cedperaux)
			{
				$ls_cedperaux=$ls_cedper;
				$li_row=$li_row+1;
			}
			if($ls_coduniadm!=$ls_coduniadmact)
			{   
				if ($ls_coduniadmact!="")
				{   
					$li_total6=$li_total6+$li_total5;
					$lo_hoja->write($li_row,1,"TOTAL GERENCIA ",$lo_dataright2);
					$lo_hoja->write($li_row,2,$li_total5,$lo_dataright);
					$li_row++;
					$li_total5=0;
				}				
				$ls_coduniadmact=$ls_coduniadm;	
				$lo_hoja->write($li_row, 0, $ls_coduniadm, $lo_titulo);
				$lo_hoja->write($li_row, 1, $ls_desuniadm, $lo_titulo);
				$li_row++;
				$lo_hoja->write($li_row, 0, "Cédula", $lo_titulo);
				$lo_hoja->write($li_row, 1, "Nombre", $lo_titulo);
				$lo_hoja->write($li_row, 2, "Tipo de Personal", $lo_titulo);
				$lo_hoja->write($li_row, 3, "Ubicación", $lo_titulo);
				$li_col=3;
			        $lb_valido=$io_report->uf_pagonomina_concepto_excel(" AND (sigcon='A')",
				                                                 $ls_codperi, $ls_codnomdes, $ls_codnomhas,$ls_codconcdes,$ls_codconchas); 
				if($lb_valido)
				{
				    $columna=0;
				    $columna=$li_col+1;
					$li_totrow2=$io_report->DS_detalle->getRowCount("codconc");
					for($li_j=1;$li_j<=$li_totrow2;$li_j++)
					{
						$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_j];
						$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_j];
						$li_col=$li_col+1;						
						$lo_hoja->set_column($li_col,$li_col,20);
						$lo_hoja->write($li_row, $li_col, $ls_nomcon,$lo_titulo);
						$li_col2=$li_col+1;	
						$lo_hoja->set_column($li_col2,$li_col2,15);
						$lo_hoja->write($li_row, $li_col2, "Monto Diario",$lo_titulo);						
						$li_col3=$li_col+2; 
						$lo_hoja->set_column($li_col3,$li_col3,15);
						$lo_hoja->write($li_row, $li_col3, "Monto Mensual",$lo_titulo);						
					}							
				}
				$io_report->DS_detalle->resetds("codconc");	
				
				$lb_valido=$io_report->uf_pagonomina_concepto_excel(" AND (sigcon='D')",
				                                                    $ls_codperi, $ls_codnomdes, $ls_codnomhas,$ls_codconcdes,$ls_codconchas); 
				if($lb_valido)
				{   
					$li_totrow2=$io_report->DS_detalle->getRowCount("codconc");
					for($li_j=1;(($li_j<=$li_totrow2)&&($lb_valido));$li_j++)
					{
						$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_j];
						$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_j];
						$li_col=$li_col3+1;						
						$lo_hoja->set_column($li_col,$li_col,20);
						$lo_hoja->write($li_row, $li_col, $ls_nomcon,$lo_titulo);
						$li_col2=$li_col3+2;	
						$lo_hoja->set_column($li_col2,$li_col2,15);
						$lo_hoja->write($li_row, $li_col2, "Monto Diario",$lo_titulo);						
						$li_col5=$li_col3+3; 
						$lo_hoja->set_column($li_col5,$li_col5,15);
						$lo_hoja->write($li_row, $li_col5, "Monto Mensual",$lo_titulo);						
					}
					$li_col4=$li_col3+4;
					$lo_hoja->set_column($li_col4,$li_col4,15);
					$lo_hoja->write($li_row, $li_col4, "Monto Total",$lo_titulo);		
				}
				$io_report->DS_detalle->resetds("codconc");							
				$li_row++;
			}
			
			$ls_cedper=$io_report->DS->data["cedper"][$li_i];
			$ls_nomper=$io_report->DS->data["apeper"][$li_i].", ".$io_report->DS->data["nomper"][$li_i];
			$ls_desnom=$io_report->DS->data["tipopersonal"][$li_i];
			$ls_ubicacion=trim($io_report->DS->data["denger"][$li_i]);
						
			
					
			$ls_codper=$io_report->DS->data["codper"][$li_i];
	        $lb_valido=$io_report->uf_pagonomina_conceptopersonal_excel($ls_codper,$ls_codperi,
			                                                            $ls_codnomdes, $ls_codnomhas,
																		" AND (sigcon='A')"); 
			if($lb_valido)
			{   
			    $li_total=0;
				$li_total2=0;
				$li_totrow3=$io_report->DS_detalle->getRowCount("codconc");
				$ls_monto_cesta=$io_report->DS->data["moncestic"][$li_i];
				for($li_k=1;$li_k<=$li_totrow3;$li_k++)
				{
					$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_k];
					$ls_tipsal=rtrim($io_report->DS_detalle->data["tipsal"][$li_k]);
					$li_valsal=abs($io_report->DS_detalle->data["valsal"][$li_k]);
					$li_total=$li_total + $li_valsal;
					$li_ticket=number_format(
					           abs($io_report->DS_detalle->data["valsal"][$li_k])/abs($ls_monto_cesta),0);				   				
					$lo_hoja->write($li_row, $columna, $li_ticket,$lo_datacenter);						
					$li_col2=$columna+1;						
					$lo_hoja->write($li_row, $li_col2, $ls_monto_cesta,$lo_dataright);
					$li_col3=$columna+2; 						
					$lo_hoja->write($li_row, $li_col3, $li_valsal, $lo_dataright);						
				}
				$li_total2=$li_total2+$li_total;							
			}
			$io_report->DS_detalle->resetds("codconc");
			
			$lb_valido1=$io_report->uf_pagonomina_conceptopersonal_excel($ls_codper,$ls_codperi,
			                                                            $ls_codnomdes, $ls_codnomhas,
																		" AND (sigcon='D')"); 
			if($lb_valido1)
			{   
			    $li_total=0;
				$li_total3=0;
				$li_totrow3=$io_report->DS_detalle->getRowCount("codconc");
				$ls_monto_cesta=$io_report->DS->data["mondesdia"][$li_i];
				$columna1=$li_col3+1;				
				for($li_k=1;$li_k<=$li_totrow3;$li_k++)
				{
					$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_k];
					$ls_tipsal=rtrim($io_report->DS_detalle->data["tipsal"][$li_k]);
					$li_valsal=abs($io_report->DS_detalle->data["valsal"][$li_k]);
					$li_total=$li_total + $li_valsal;
					$li_ticket=number_format(
					           abs($io_report->DS_detalle->data["valsal"][$li_k])/abs($ls_monto_cesta),0);										
					$lo_hoja->write($li_row, $columna1, $li_ticket,$lo_datacenter);						
					$li_col5=$li_col3+2;											
					$lo_hoja->write($li_row, $li_col5, $ls_monto_cesta,$lo_dataright);
					$li_col6=$li_col3+3; 													
					$lo_hoja->write($li_row, $li_col6, $li_valsal, $lo_dataright);						
				}
				$li_total3=$li_total3+$li_total;									
			}
			if ($lb_valido1)
			{
				$li_total4=$li_total2-$li_total3;
				if ($ls_cedper!=$ls_cedperaux)
			    {
					$li_total5=$li_total5+$li_total4;	
				}						
				$li_col7=$li_col3+4;						
				$lo_hoja->set_column($li_col7,$li_col7,15);
				$lo_hoja->write($li_row, $li_col7, $li_total4,$lo_dataright);
			}
			else
			{
			    $li_col4=$li_col3+1;
				if ($ls_cedper!=$ls_cedperaux)
			    {
					$li_total5=$li_total5+$li_total2;	
				}
				$lo_hoja->set_column($li_col4,$li_col4,15);
				$lo_hoja->write($li_row, $li_col4, $li_total4,$lo_dataright);
			}
			$io_report->DS_detalle->resetds("codconc");
			
			if ($ls_cedper!=$ls_cedperaux)
			{
				$lo_hoja->write($li_row, 0, $ls_cedper, $lo_dataright2);
				$lo_hoja->write($li_row, 1, $ls_nomper, $lo_dataleft);
				$lo_hoja->write($li_row, 2, $ls_desnom, $lo_datacenter);
				$lo_hoja->write($li_row, 3, $ls_ubicacion, $lo_dataleft);	
			}
				
		}
		$li_row=$li_row+1;
		$lo_hoja->write($li_row,1,"TOTAL GERENCIA ",$lo_dataright2);;
		$lo_hoja->write($li_row,2,$li_total5,$lo_dataright);
		$li_row=$li_row+2;
		$lo_hoja->write($li_row,1,"TOTAL GENERAL PERSONAL",$lo_dataright2);
		$lo_hoja->write($li_row,2,$li_totrow,$lo_dataright2);
		$li_row=$li_row+1;
		$li_total6=$li_total6+$li_total5;
		$lo_hoja->write($li_row,1,"TOTAL GENERAL Bs.",$lo_dataright2);
		$lo_hoja->write($li_row,2,$li_total6,$lo_dataright);
		$lo_libro->close();
		header("Content-Type: application/x-msexcel; name=\"control_programa_alimentacion.xls\"");
		header("Content-Disposition: inline; filename=\"control_programa_alimentacion.xls\"");
		$fh=fopen($lo_archivo, "rb");
		fpassthru($fh);
		unlink($lo_archivo);
		print("<script language=JavaScript>");
		print(" close();");
		print("</script>");
		unset($io_pdf);
	}/// fin de else // Imprimimos el reporte
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 
