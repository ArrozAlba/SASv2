<?php
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";		
		print "</script>";		
	}
	ini_set('memory_limit','256M');
	ini_set('max_execution_time','0');

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo,$ai_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 30/07/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_cuadre_netos_deduc.php",$ls_descripcion,$ls_codnom);
		
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	$ls_tiporeporte="0";
	$ls_bolivares="";
	require_once("sigesp_sno_class_report.php");
	$io_report=new sigesp_sno_class_report();	
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	// para crear el libro excel
	require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
	require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
	$lo_archivo = tempnam("/home/production/tmp", "pagonomina.xls");
	$lo_libro = &new writeexcel_workbookbig($lo_archivo);
	$lo_hoja = &$lo_libro->addworksheet();
	
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$ls_titulo="Cuadre de Netos y Deducciones";
	$ls_periodo="Período Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codconcdes=$io_fun_nomina->uf_obtenervalor_get("codconcdes","");
	$ls_codconchas=$io_fun_nomina->uf_obtenervalor_get("codconchas","");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");	
	$ls_codente=$io_fun_nomina->uf_obtenervalor_get("codente","");	
	$ls_monto_total=0;
	$monto_total2=0;	
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_buscar_conceptos_netos_deduc($ls_codconcdes,$ls_codconchas,$ls_codente); 
	}
	if(($lb_valido==false)||($io_report->rs_data_concepto->RecordCount()==0)) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else  // Imprimimos el reporte
	{
		set_time_limit(1800);
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
		$lo_dataright= &$lo_libro->addformat(array("num_format"=> "#,##0.00"));
		$lo_dataright->set_font("Verdana");
		$lo_dataright->set_align('right');
		$lo_dataright->set_size('9');
			
		$lo_hoja->set_column(0,0,12);
		$lo_hoja->set_column(1,1,50);
		$lo_hoja->set_column(2,2,20);
		$lo_hoja->set_column(3,3,16);
		$lo_hoja->set_column(4,4,20);
		$lo_hoja->set_column(5,5,16);
		$lo_hoja->set_column(6,6,20);
		$lo_hoja->set_column(7,7,16);
		$lo_hoja->set_column(8,8,20);
		$lo_hoja->set_column(9,9,16);
		$lo_hoja->set_column(10,10,20);
		$lo_hoja->set_column(11,11,16);
		$lo_hoja->set_column(12,12,20);
		$lo_hoja->set_column(13,13,16);
		$lo_hoja->set_column(14,14,20);
		$lo_hoja->set_column(15,15,16);
		$lo_hoja->set_column(16,16,20);
		$lo_hoja->set_column(17,17,16);
		$lo_hoja->set_column(18,18,20);
		$lo_hoja->set_column(19,19,16);
		$lo_hoja->set_column(20,20,20);
		$lo_hoja->write(0,6,$ls_titulo,$lo_encabezado);
		$lo_hoja->write(1,6,$ls_periodo,$lo_encabezado);
		$lo_hoja->write(2,6,$ls_desnom,$lo_encabezado);
		$li_fila=5;
		$ls_codcat_aux=" ";
		$ls_totalpersona=0;
		$ls_totalmonto=0;
		$monto_total=0;
		$personal_total=0;
		$personal_total2=0;
		$ls_asig=0;
		$ls_deduc=0;
		$li_h=0;
		$li_totcon=$io_report->rs_data_concepto->RecordCount();
		while((!$io_report->rs_data_concepto->EOF)&&($lb_valido))
		{
			$ls_monto_tot1=0;
			$ls_persona_tot1=0; 
			$ls_monto_tot2=0;
			$ls_persona_tot2=0; 
			$li_h=$li_h+1;
			$ls_codconc=$io_report->rs_data_concepto->fields["codconc"];
			$ls_sigcon=$io_report->rs_data_concepto->fields["sigcon"];
			$ls_nomcon=$io_report->rs_data_concepto->fields["nomcon"];	
			if ($li_h==1)
			{			
				if (($ls_sigcon=='A')||($ls_sigcon=='B'))
				{
					$ls_caso='1';
					$lo_hoja->write($li_fila,1,"NETO A PAGAR (ASIGNACIONES)",$lo_encabezado);
					$li_fil=$li_fila+1;
					$li_fila=$li_fila+2;		
					$li_col=0;
					$lo_hoja->write($li_fila,$li_col,"Codigo",$lo_datacenter);
					$li_col++;
					$lo_hoja->write($li_fila,$li_col,"Descripcion",$lo_datacenter);		
					$li_col++;
				}
				else
				{
					$ls_caso='2';
					$lo_hoja->write($li_fila,1,"DEDUCCIONES",$lo_encabezado);
					$li_fil=$li_fila+1;
					$li_fila=$li_fila+2;		
					$li_col=0;
					$lo_hoja->write($li_fila,$li_col,"Codigo",$lo_datacenter);
					$li_col++;
					$lo_hoja->write($li_fila,$li_col,"Descripcion",$lo_datacenter);		
					$li_col++;
				}
			}
			$lb_valido=$io_report->uf_buscar_categorias_rango(); 		
			$li_colum=2;
			while((!$io_report->rs_data->EOF)&&($lb_valido))		
			{ 
				$ls_codcat=$io_report->rs_data->fields["codcat"]; 
				$ls_denominacion=$io_report->rs_data->fields["descat"];	
				if ($li_h==1)
				{
					$lo_hoja->write($li_fil,$li_col,$ls_denominacion,$lo_titulo);				
					$lo_hoja->write($li_fila,$li_col,"Numero",$lo_datacenter);	
					$li_col++;			
					$lo_hoja->write($li_fila,$li_col,"Monto Bs.",$lo_datacenter);	
					$li_col++;
					$li_row=$li_fila+1;
					
				}
				$io_report->uf_cuadre_concepto_neto_deduc($ls_codconc,$ls_codconc,$ls_conceptocero,$ls_subnomdes,$ls_subnomhas,$ls_codcat,$ls_codente);
				$ls_asig=0;
				$ls_deduc=0;
				$li_j=0;
				$li_i=0;
				$ls_totalper1=0;
				$ls_monto1=0;
				while(!$io_report->rs_data_detalle->EOF)
				{				
					$li_i=$li_i+1;	
					$li_j++;			
					$ls_totalper=$io_report->rs_data_detalle->fields["total"];	
					$ls_monto=abs($io_report->rs_data_detalle->fields["monto"]);
					$ls_tipo=$io_report->rs_data_detalle->fields["tipsal"];	
					$ls_totalper1=$ls_totalper1+$ls_totalper;	
					$ls_monto1=$ls_monto1+$ls_monto;		 
					if (rtrim($ls_tipo)=="A")
					{
						$ls_monto_tot1=$ls_monto_tot1+$ls_monto;
						$ls_persona_tot1=$ls_persona_tot1+$ls_totalper; 	
					}	
					if (rtrim($ls_tipo)=="D")
					{
						$ls_monto_tot2=$ls_monto_tot2+abs($ls_monto);
						$ls_persona_tot2=$ls_persona_tot2+$ls_totalper; 
					}	
					$io_report->rs_data_detalle->MoveNext();
					if ((trim($io_report->rs_data_detalle->fields["codconc"])!=trim($ls_codconc)))
					{
						if (rtrim($ls_tipo)=="A")
						{
							$lo_hoja->write($li_row,0,$ls_codconc,$lo_dataleft);									
							$lo_hoja->write($li_row,1,$ls_nomcon,$lo_dataleft);
							$lo_hoja->write($li_row,$li_colum,$ls_totalper1,$lo_datacenter);	
							$li_colum++;			
							$lo_hoja->write($li_row,$li_colum,$ls_monto1,$lo_dataright);	
							$li_colum++;
							$ls_asig=$ls_asig+1;
						}	
						if (rtrim($ls_tipo)=="D")
						{
							$lo_hoja->write($li_row,0,$ls_codconc,$lo_dataleft);									
							$lo_hoja->write($li_row,1,$ls_nomcon,$lo_dataleft);
							$lo_hoja->write($li_row,$li_colum,$ls_totalper1,$lo_datacenter);	
							$li_colum++;			
							$lo_hoja->write($li_row,$li_colum,$ls_monto1,$lo_dataright);	
							$li_colum++;
							$ls_deduc=$ls_deduc+1;
						}
						$ls_totalper1=0;
						$ls_monto1=0;	
					}
				}//fin del for
				$io_report->rs_data->MoveNext();					
			}//fin del for
			if($li_h==1)
			{
				$lo_hoja->write($li_row-2,$li_col,"TOTAL GENERAL",$lo_titulo);									
				$lo_hoja->write($li_row-1,$li_col,"Numero",$lo_datacenter);
				$lo_hoja->write($li_row-1,$li_col+1,"Monto Bs.",$lo_datacenter);								
			}
				if (($ls_sigcon=='A')||($ls_sigcon=='B'))
				{   
					if ($ls_monto_tot1>0)
					{
						$lo_hoja->write($li_row,$li_col,$ls_persona_tot1,$lo_datacenter);
						$lo_hoja->write($li_row,$li_col+1,$ls_monto_tot1,$lo_dataright);
						$monto_total=$monto_total+($ls_monto_tot1);
						$personal_total=$personal_total+($ls_persona_tot1);
						$li_row=$li_row+1;	
					}
				}
				else if (($ls_sigcon=='D')||($ls_sigcon=='P')||($ls_sigcon=='E'))
				{						
					if ($ls_monto_tot2>0)
					{
						$lo_hoja->write($li_row,$li_col,$ls_persona_tot2,$lo_datacenter);
						$lo_hoja->write($li_row,$li_col+1,$ls_monto_tot2,$lo_dataright);
						$monto_total2=$monto_total2+($ls_monto_tot2);
						$personal_total2=$personal_total2+($ls_persona_tot2);
						$li_row=$li_row+1;	
					}
				}
				$io_report->rs_data_concepto->MoveNext();
				$ls_sigcon2=$io_report->rs_data_concepto->fields["sigcon"];	
				if ($ls_sigcon2!=$ls_sigcon)
				{							
					if (($ls_sigcon2=='D')||($ls_sigcon2=='P')||($ls_sigcon2=='E'))
					{
						$li_row=$li_row+1;
						$lo_hoja->write($li_row,$li_col-1,"TOTAL ASIGNACIONES",$lo_titulo);	
						$lo_hoja->write($li_row,$li_col,$personal_total,$lo_datacenter);								
						$lo_hoja->write($li_row,$li_col+1,$monto_total,$lo_dataright);					
						$li_row=$li_row + 2;
						$lo_hoja->write($li_row,1,"DEDUCCIONES",$lo_encabezado);
						
					}
					else if ($li_h==$li_totcon)
					{
						$li_row=$li_row+1;
						$lo_hoja->write($li_row,$li_col-1,"TOTAL DEDUCCIONES",$lo_titulo);	
						$lo_hoja->write($li_row,$li_col,$personal_total2,$lo_datacenter);								
						$lo_hoja->write($li_row,$li_col+1,$monto_total2,$lo_dataright);	
					
					}		
				}		
		}//FIN DEL PRIMER WHILE
		$monto_genral=0;
		$monto_genral=abs($monto_total-$monto_total2);
		$li_row=$li_row+2;	
		$lo_hoja->write($li_row,$li_col,"CUADRE DE NOMINA",$lo_titulo);
		$lo_hoja->write($li_row,$li_col+1,$monto_genral,$lo_dataright);
		//IMPRIMIR MONTO TOTAL
		if(!$lb_valido) // Si no ocurrio ningún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		$lo_libro->close();
		header("Content-Type: application/x-msexcel; name=\"pagonomina.xls\"");
		header("Content-Disposition: inline; filename=\"netos_deduc.xls\"");
		$fh=fopen($lo_archivo, "rb");
		fpassthru($fh);
		unlink($lo_archivo);
		print("<script language=JavaScript>");
		//print(" close();");
		print("</script>");
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 
