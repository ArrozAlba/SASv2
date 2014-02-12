<?php
   session_start();   
	ini_set('memory_limit','512M');
	ini_set('max_execution_time','0');
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo, $as_desnom,$as_periodo)
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
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_asignacion_comp_ran.php",$ls_descripcion,$ls_codnom);		
		return $lb_valido;
	}
	//---------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
	require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
	require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
	$lo_archivo = tempnam("/home/production/tmp", "listado_asignacion_componente_categoria.xls");
	$lo_libro = &new writeexcel_workbookbig($lo_archivo);
	$lo_hoja = &$lo_libro->addworksheet();
	
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear la data necesaria del reporte
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_sno_class_report.php");
	$io_report=new sigesp_sno_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$ls_titulo="Asignaciones por Componente";
	$ls_periodo="Período Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper;
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codconcdes=$io_fun_nomina->uf_obtenervalor_get("codcomdes","");
	$ls_codconchas=$io_fun_nomina->uf_obtenervalor_get("codcomhas","");	
	$ls_codrandes=$io_fun_nomina->uf_obtenervalor_get("codrandes","");
	$ls_codranhas=$io_fun_nomina->uf_obtenervalor_get("codranhas","");	
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");		//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_componentes_rangos($ls_codconcdes,$ls_codconchas,$ls_codrandes,$ls_codranhas,'CATEGORIA'); // Cargar el DS con los datos de la cabecera del reporte
	}
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else  // Imprimimos el reporte
	{
		//-------formato para el reporte----------------------------------------------------------
		$li_row=0;
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
		$lo_dataleft->set_bold();
		
		$lo_dataleft2= &$lo_libro->addformat();
		$lo_dataleft2->set_text_wrap();
		$lo_dataleft2->set_font("Verdana");
		$lo_dataleft2->set_size('8');
		$lo_dataleft2->set_bold();
		$lo_dataleft2->set_align('center');
		
		$lo_dataright= &$lo_libro->addformat(array(num_format => '#,##0.00'));
		$lo_dataright->set_font("Verdana");
		$lo_dataright->set_align('right');
		$lo_dataright->set_size('9');
		
		$lo_dataright2= &$lo_libro->addformat(array(num_format => '#,##'));
		$lo_dataright2->set_font("Verdana");
		$lo_dataright2->set_align('right');
		$lo_dataright2->set_size('9');
		
		$lo_hoja->set_column(0,0,25);
		$lo_hoja->set_column(1,1,18);
		$lo_hoja->set_column(2,2,18);
		$lo_hoja->set_column(3,3,18);
		$lo_hoja->set_column(4,4,18);
		$lo_hoja->set_column(5,5,20);
		$lo_hoja->set_column(6,6,20);
		$lo_hoja->set_column(7,7,18);
		$lo_hoja->set_column(8,8,18);
		$lo_hoja->set_column(9,9,18);
		$lo_hoja->set_column(10,10,18);
		//---------------------------------------------------------------------------------------------
		$lo_hoja->write($li_row,3,$ls_titulo,$lo_encabezado);
		$li_row=$li_row+1;
		$lo_hoja->write($li_row,3,$ls_periodo,$lo_encabezado);
		$li_row=$li_row+1;
		$lo_hoja->write($li_row,3,$ls_desnom,$lo_encabezado);
		$li_row=$li_row+2;
		//PARA LOS REGISTROS ANTES DEL 13/07/1995
		$lo_hoja->write($li_row,3,'Antes del 13 de Julio de 1995',$lo_encabezado);
		$li_row=$li_row+1;			
		$li_totrow=$io_report->rs_data->RecordCount();
		$subtotalper=0;
		$subtotalmon=0;
		$ls_codcomaux=""; 
		$ls_codcataux="";
		$total=0;
		$totalpersonas=0;
		while(!$io_report->rs_data->EOF)
		{
			$ls_codcom=$io_report->rs_data->fields["codcom"]; 
			$ls_dencom=$io_report->rs_data->fields["descom"];
			$ls_codcat=$io_report->rs_data->fields["codcat"]; 
			$ls_dencat=strtoupper($io_report->rs_data->fields["descat"]);
			$lb_valido2=$io_report->uf_asignacion_componente_rango($ls_codcom,$ls_codran,$ls_codcat,'1');								
			$li_asignacion=$io_report->rs_data_detalle->RecordCount();			
			if ($ls_codcomaux!=$ls_codcom)
			{
				if ($ls_codcomaux!="")
				{
					$li_row=$li_row+3;		
					$lo_hoja->write($li_row,0,"SUB TOTAL COMPONENTE ".$ls_dencomaux,$lo_dataleft);					
					$lo_hoja->write($li_row+1,0,"Nro Personas",$lo_dataleft2);	
					$lo_hoja->write($li_row+2,0,$subtotalper,$lo_dataleft2);	
					$li_col=$li_col+1;
					$lo_hoja->write($li_row+1,1,"Monto Bs.",$lo_dataleft2);					
					$lo_hoja->write($li_row+2,1,$subtotalmon,$lo_dataleft2);
					$li_col=$li_col+1;
					$total=$total+$subtotalmon;
					$totalpersonas=$totalpersonas+$subtotalper;
					$subtotalper=0;
					$subtotalmon=0;
				}
				$ls_dencom=strtoupper($ls_dencom);
			    $li_row=$li_row+4;
				$lo_hoja->write($li_row,0,'COMPONENTE '.$ls_dencom,$lo_dataleft);
				$li_row=$li_row+1;		
				$li_col=1;	
				$ls_codcomaux=$ls_codcom;	
				$ls_dencomaux=$ls_dencom;		
			}	
			if(!$io_report->rs_data_detalle->EOF)
			{
				$monto=number_format($io_report->rs_data_detalle->fields["monto"],2,".","");
				$personas=number_format($io_report->rs_data_detalle->fields["personas"],2,".","");	
			}//fin del for
			if (($ls_codcataux!=$ls_codcat)&&($ls_codcat!=""))
			{			
				if ($personas>0)
				{
					$ls_codcataux=$ls_codcat;
					$ls_codcataux=$ls_codcat;
					$lo_hoja->write($li_row,$li_col,"CATEGORIA: ".$ls_dencat,$lo_dataleft);					
					$lo_hoja->write($li_row+1,$li_col,"Nro Personas",$lo_dataleft2);	
					$lo_hoja->write($li_row+2,$li_col,$personas,$lo_dataleft2);	
					$li_col=$li_col+1;
					$lo_hoja->write($li_row+1,$li_col,"Monto Bs.",$lo_dataleft2);					
					$lo_hoja->write($li_row+2,$li_col,$monto,$lo_dataleft2);
					$li_col=$li_col+1;
					$subtotalper=$subtotalper+$personas;
					$subtotalmon=$subtotalmon+$monto;
				}		
				$personas=0;
				$monto=0;	
			}
			$io_report->rs_data->MoveNext();					
		}//fin del for 	
		$li_row=$li_row+3;		
		$lo_hoja->write($li_row,0,"SUB TOTAL COMPONENTE ".$ls_dencomaux,$lo_dataleft);					
		$lo_hoja->write($li_row+1,0,"Nro Personas",$lo_dataleft2);	
		$lo_hoja->write($li_row+2,0,$subtotalper,$lo_dataleft2);	
		$li_col=$li_col+1;
		$lo_hoja->write($li_row+1,1,"Monto Bs.",$lo_dataleft2);					
		$lo_hoja->write($li_row+2,1,$subtotalmon,$lo_dataleft2);
		$li_col=$li_col+1;
		$total=$total+$subtotalmon;
		$totalpersonas=$totalpersonas+$subtotalper;
		$li_row=$li_row+5;
		$lo_hoja->write($li_row,0,"TOTAL GENERAL ANTES DEL 13 DE JULIO 1995",$lo_dataleft2);					
		$lo_hoja->write($li_row+1,0,"NRO PERSONAS",$lo_dataleft2);					
		$lo_hoja->write($li_row+2,0,$totalpersonas,$lo_dataleft2);					
		$lo_hoja->write($li_row+1,1,"MONTO Bs.",$lo_dataleft2);	
		$lo_hoja->write($li_row+2,1,$total,$lo_dataleft2);	

		//PARA LOS REGISTROS DESPUES DEL 13/07/1995
		$li_row=$li_row+5;
		$lo_hoja->write($li_row,3,'Después del 13 de Julio de 1995',$lo_encabezado);
		$li_row=$li_row+1;			
		$li_totrow=$io_report->rs_data->RecordCount();
		$subtotalper=0;
		$subtotalmon=0;
		$ls_codcomaux=""; 
		$ls_codcataux="";
		$total=0;
		$totalpersonas=0;
		$io_report->rs_data->MoveFirst();
		while(!$io_report->rs_data->EOF)
		{
			$ls_codcom=$io_report->rs_data->fields["codcom"]; 
			$ls_dencom=$io_report->rs_data->fields["descom"];
			$ls_codcat=$io_report->rs_data->fields["codcat"]; 
			$ls_dencat=strtoupper($io_report->rs_data->fields["descat"]);
			$lb_valido2=$io_report->uf_asignacion_componente_rango($ls_codcom,$ls_codran,$ls_codcat,'2');								
			$li_asignacion=$io_report->rs_data_detalle->RecordCount();			
			if ($ls_codcomaux!=$ls_codcom)
			{
				if ($ls_codcomaux!="")
				{
					$li_row=$li_row+3;		
					$lo_hoja->write($li_row,0,"SUB TOTAL COMPONENTE ".$ls_dencomaux,$lo_dataleft);					
					$lo_hoja->write($li_row+1,0,"Nro Personas",$lo_dataleft2);	
					$lo_hoja->write($li_row+2,0,$subtotalper,$lo_dataleft2);	
					$li_col=$li_col+1;
					$lo_hoja->write($li_row+1,1,"Monto Bs.",$lo_dataleft2);					
					$lo_hoja->write($li_row+2,1,$subtotalmon,$lo_dataleft2);
					$li_col=$li_col+1;
					$total=$total+$subtotalmon;
					$totalpersonas=$totalpersonas+$subtotalper;
					$subtotalper=0;
					$subtotalmon=0;
				}
				$ls_dencom=strtoupper($ls_dencom);
			    $li_row=$li_row+4;
				$lo_hoja->write($li_row,0,'COMPONENTE '.$ls_dencom,$lo_dataleft);
				$li_row=$li_row+1;		
				$li_col=1;	
				$ls_codcomaux=$ls_codcom;	
				$ls_dencomaux=$ls_dencom;		
			}	
			if(!$io_report->rs_data_detalle->EOF)
			{
				$monto=number_format($io_report->rs_data_detalle->fields["monto"],2,".","");
				$personas=number_format($io_report->rs_data_detalle->fields["personas"],2,".","");	
			}//fin del for
			if (($ls_codcataux!=$ls_codcat)&&($ls_codcat!=""))
			{			
				if ($personas>0)
				{
					$ls_codcataux=$ls_codcat;
					$ls_codcataux=$ls_codcat;
					$lo_hoja->write($li_row,$li_col,"CATEGORIA: ".$ls_dencat,$lo_dataleft);					
					$lo_hoja->write($li_row+1,$li_col,"Nro Personas",$lo_dataleft2);	
					$lo_hoja->write($li_row+2,$li_col,$personas,$lo_dataleft2);	
					$li_col=$li_col+1;
					$lo_hoja->write($li_row+1,$li_col,"Monto Bs.",$lo_dataleft2);					
					$lo_hoja->write($li_row+2,$li_col,$monto,$lo_dataleft2);
					$li_col=$li_col+1;
					$subtotalper=$subtotalper+$personas;
					$subtotalmon=$subtotalmon+$monto;
				}		
				$personas=0;
				$monto=0;	
			}
			$io_report->rs_data->MoveNext();					
		}//fin del for 	
		$li_row=$li_row+3;		
		$lo_hoja->write($li_row,0,"SUB TOTAL COMPONENTE ".$ls_dencomaux,$lo_dataleft);					
		$lo_hoja->write($li_row+1,0,"Nro Personas",$lo_dataleft2);	
		$lo_hoja->write($li_row+2,0,$subtotalper,$lo_dataleft2);	
		$li_col=$li_col+1;
		$lo_hoja->write($li_row+1,1,"Monto Bs.",$lo_dataleft2);					
		$lo_hoja->write($li_row+2,1,$subtotalmon,$lo_dataleft2);
		$li_col=$li_col+1;
		$total=$total+$subtotalmon;
		$totalpersonas=$totalpersonas+$subtotalper;
		$li_row=$li_row+5;
		$lo_hoja->write($li_row,0,"TOTAL GENERAL DESPUES DEL 13 DE JULIO 1995",$lo_dataleft2);					
		$lo_hoja->write($li_row+1,0,"NRO PERSONAS",$lo_dataleft2);					
		$lo_hoja->write($li_row+2,0,$totalpersonas,$lo_dataleft2);					
		$lo_hoja->write($li_row+1,1,"MONTO Bs.",$lo_dataleft2);	
		$lo_hoja->write($li_row+2,1,$total,$lo_dataleft2);	

		//PARA EL TOTAL DE  REGISTROS 
		$li_row=$li_row+5;
		$lo_hoja->write($li_row,3,'Total de Asignaciones',$lo_encabezado);
		$li_row=$li_row+1;			
		$li_totrow=$io_report->rs_data->RecordCount();
		$subtotalper=0;
		$subtotalmon=0;
		$ls_codcomaux=""; 
		$ls_codcataux="";
		$total=0;
		$totalpersonas=0;
		$io_report->rs_data->MoveFirst();
		while(!$io_report->rs_data->EOF)
		{
			$ls_codcom=$io_report->rs_data->fields["codcom"]; 
			$ls_dencom=$io_report->rs_data->fields["descom"];
			$ls_codcat=$io_report->rs_data->fields["codcat"]; 
			$ls_dencat=strtoupper($io_report->rs_data->fields["descat"]);
			$lb_valido2=$io_report->uf_asignacion_componente_rango($ls_codcom,$ls_codran,$ls_codcat,'3');								
			$li_asignacion=$io_report->rs_data_detalle->RecordCount();			
			if ($ls_codcomaux!=$ls_codcom)
			{
				if ($ls_codcomaux!="")
				{
					$li_row=$li_row+3;		
					$lo_hoja->write($li_row,0,"SUB TOTAL COMPONENTE ".$ls_dencomaux,$lo_dataleft);					
					$lo_hoja->write($li_row+1,0,"Nro Personas",$lo_dataleft2);	
					$lo_hoja->write($li_row+2,0,$subtotalper,$lo_dataleft2);	
					$li_col=$li_col+1;
					$lo_hoja->write($li_row+1,1,"Monto Bs.",$lo_dataleft2);					
					$lo_hoja->write($li_row+2,1,$subtotalmon,$lo_dataleft2);
					$li_col=$li_col+1;
					$total=$total+$subtotalmon;
					$totalpersonas=$totalpersonas+$subtotalper;
					$subtotalper=0;
					$subtotalmon=0;
				}
				$ls_dencom=strtoupper($ls_dencom);
			    $li_row=$li_row+4;
				$lo_hoja->write($li_row,0,'COMPONENTE '.$ls_dencom,$lo_dataleft);
				$li_row=$li_row+1;		
				$li_col=1;	
				$ls_codcomaux=$ls_codcom;	
				$ls_dencomaux=$ls_dencom;		
			}	
			if(!$io_report->rs_data_detalle->EOF)
			{
				$monto=number_format($io_report->rs_data_detalle->fields["monto"],2,".","");
				$personas=number_format($io_report->rs_data_detalle->fields["personas"],2,".","");	
			}//fin del for
			if (($ls_codcataux!=$ls_codcat)&&($ls_codcat!=""))
			{			
				if ($personas>0)
				{
					$ls_codcataux=$ls_codcat;
					$ls_codcataux=$ls_codcat;
					$lo_hoja->write($li_row,$li_col,"CATEGORIA: ".$ls_dencat,$lo_dataleft);					
					$lo_hoja->write($li_row+1,$li_col,"Nro Personas",$lo_dataleft2);	
					$lo_hoja->write($li_row+2,$li_col,$personas,$lo_dataleft2);	
					$li_col=$li_col+1;
					$lo_hoja->write($li_row+1,$li_col,"Monto Bs.",$lo_dataleft2);					
					$lo_hoja->write($li_row+2,$li_col,$monto,$lo_dataleft2);
					$li_col=$li_col+1;
					$subtotalper=$subtotalper+$personas;
					$subtotalmon=$subtotalmon+$monto;
				}		
				$personas=0;
				$monto=0;	
			}
			$io_report->rs_data->MoveNext();					
		}//fin del for 	
		$li_row=$li_row+3;		
		$lo_hoja->write($li_row,0,"SUB TOTAL COMPONENTE ".$ls_dencomaux,$lo_dataleft);					
		$lo_hoja->write($li_row+1,0,"Nro Personas",$lo_dataleft2);	
		$lo_hoja->write($li_row+2,0,$subtotalper,$lo_dataleft2);	
		$li_col=$li_col+1;
		$lo_hoja->write($li_row+1,1,"Monto Bs.",$lo_dataleft2);					
		$lo_hoja->write($li_row+2,1,$subtotalmon,$lo_dataleft2);
		$li_col=$li_col+1;
		$total=$total+$subtotalmon;
		$totalpersonas=$totalpersonas+$subtotalper;
		$li_row=$li_row+5;
		$lo_hoja->write($li_row,0,"TOTAL GENERAL ASIGNACIONES",$lo_dataleft2);					
		$lo_hoja->write($li_row+1,0,"NRO PERSONAS",$lo_dataleft2);					
		$lo_hoja->write($li_row+2,0,$totalpersonas,$lo_dataleft2);					
		$lo_hoja->write($li_row+1,1,"MONTO Bs.",$lo_dataleft2);	
		$lo_hoja->write($li_row+2,1,$total,$lo_dataleft2);	
		$lo_libro->close();
		header("Content-Type: application/x-msexcel; name=\"ASIGNACION_COMPONENTE_RANGO.xls\"");
		header("Content-Disposition: inline; filename=\"ASIGNACION_COMPONENTE_RANGO.xls\"");
		$fh=fopen($lo_archivo, "rb");
		fpassthru($fh);
		unlink($lo_archivo);
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 
