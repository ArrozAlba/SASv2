<?php
    session_start();   
	ini_set('memory_limit','512M');
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
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 30/08/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Generó el Reporte ".$as_titulo." en Excel. Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_pagonomina.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_hpagonomina.php",$ls_descripcion,$ls_codnom);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function calcular_anos_servicioas($fecha_ingreso,$fecha_egreso)
	  {  
		  $c = date("Y",$fecha_ingreso);	   
		  $b = date("m",$fecha_ingreso);	  
		  $a = date("d",$fecha_ingreso); 	  
		  $anos = date("Y",$fecha_egreso)-$c; 
	   
			  if(date("m",$fecha_egreso)-$b > 0){
		  
			  }elseif(date("m",$fecha_egreso)-$b == 0){
		 
			  if(date("d",$fecha_egreso)-$a <= 0)
			  {		  
			     $anos = $anos-1;	  
			  }
		  
			  }else{		  
			         $anos = $anos-1;		  
			       }  
		  return $anos;	 
      } //FIN DE calcular_anos_servicioas
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
	require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
	require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
	$lo_archivo = tempnam("/home/production/tmp", "pagonomina.xls");
	$lo_libro = &new writeexcel_workbookbig($lo_archivo);
	$lo_hoja = &$lo_libro->addworksheet();
	
	//---------------------------------------------------------------------------------------------------------------------------
		//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	
	$ls_tiporeporte="0";
	if($_SESSION["la_nomina"]["tiponomina"]=="NORMAL")
	{
		require_once("sigesp_sno_class_report.php");
		$io_report=new sigesp_sno_class_report();
		$li_tipo=1;
	}
	if($_SESSION["la_nomina"]["tiponomina"]=="HISTORICA")
	{
		require_once("sigesp_sno_class_report_historico.php");
		$io_report=new sigesp_sno_class_report_historico();
		$li_tipo=2;
	}	
	$ls_bolivares ="Bs.";
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$ls_titulo="Reporte General de Pago";
	$ls_periodo="Período Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_tituloconcepto=$io_fun_nomina->uf_obtenervalor_get("tituloconcepto","");
	$ls_conceptoreporte=$io_fun_nomina->uf_obtenervalor_get("conceptoreporte","");
	$ls_conceptop2=$io_fun_nomina->uf_obtenervalor_get("conceptop2","");
	$ls_codubifis=$io_fun_nomina->uf_obtenervalor_get("codubifis","");
	$ls_codpai=$io_fun_nomina->uf_obtenervalor_get("codpai","");
	$ls_codest=$io_fun_nomina->uf_obtenervalor_get("codest","");
	$ls_codmun=$io_fun_nomina->uf_obtenervalor_get("codmun","");
	$ls_codpar=$io_fun_nomina->uf_obtenervalor_get("codpar","");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_pagonomina_personal_pensionado($ls_codperdes,$ls_codperhas,$ls_conceptocero,$ls_conceptoreporte,$ls_conceptop2,
													  $ls_codubifis,$ls_codpai,$ls_codest,$ls_codmun,$ls_codpar,$ls_subnomdes,$ls_subnomhas,$ls_orden); // Cargar el DS con los datos de la cabecera del reporte
	}
	if(($lb_valido==false)||($io_report->rs_data->RecordCount()==0)) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		$io_fun_nomina->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
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
		$lo_dataright= &$lo_libro->addformat(array('num_format'=> '#,##0.00'));
		$lo_dataright->set_font("Verdana");
		$lo_dataright->set_align('right');
		$lo_dataright->set_size('9');
		$lo_hoja->set_column(0,0,15);
		$lo_hoja->set_column(1,1,50);
		$lo_hoja->set_column(2,2,15);
		$lo_hoja->set_column(3,3,40);
		$lo_hoja->set_column(4,4,30);
		$lo_hoja->set_column(5,5,40);
		$lo_hoja->set_column(6,6,40);
		$lo_hoja->set_column(7,7,25);
		$lo_hoja->set_column(8,8,40);
		$lo_hoja->set_column(9,9,20);
		$lo_hoja->set_column(10,10,20);
		$lo_hoja->set_column(11,11,20);
		$lo_hoja->set_column(12,12,15);
		$lo_hoja->set_column(13,13,15);
		$lo_hoja->set_column(14,14,15);
		$lo_hoja->write(0,3,$ls_titulo,$lo_encabezado);
		$lo_hoja->write(1,3,$ls_periodo,$lo_encabezado);
		$lo_hoja->write(2,3,$ls_desnom,$lo_encabezado);
		$lo_hoja->write(5,3,"DATOS DEL PENSIONADO",$lo_encabezado);
		$lo_hoja->write(6, 0, "CEDULA",$lo_titulo);
		$lo_hoja->write(6, 1, "NOMBRE",$lo_titulo);
		$lo_hoja->write(6, 2, "EDAD",$lo_titulo);
		$lo_hoja->write(6, 3, "AÑOS DE SERVICIO",$lo_titulo);
		$lo_hoja->write(6, 4, "SITUACION",$lo_titulo);
		$lo_hoja->write(6, 5, "FECHA DE LA SITUACION",$lo_titulo);
		$lo_hoja->write(6, 6, "CAUSAL",$lo_titulo);
		$lo_hoja->write(6, 7, "FUERZA",$lo_titulo);
		$lo_hoja->write(6, 8, "GRADO",$lo_titulo);		
		$li_col=8;
		$li_fila=6;
		$li_totasi=0;
		$li_totded=0;
		$li_totapo=0;
		$li_totgeneral=0;
		while(!$io_report->rs_data->EOF)
		{
	      	$li_totalasignacion=0;
			$li_totaldeduccion=0;
			$li_totalaporte=0;
			$li_total_neto=0;
			$ls_codper=$io_report->rs_data->fields["codper"];
			$ls_cedper=$io_report->rs_data->fields["cedper"];
			$ls_apenomper=$io_report->rs_data->fields["apeper"].", ".$io_report->rs_data->fields["nomper"];
			$ls_descar=$io_report->rs_data->fields["descar"];
			$ls_desuniadm=$io_report->rs_data->fields["desuniadm"];
			$ld_fecingper=$io_funciones->uf_convertirfecmostrar($io_report->rs_data->fields["fecingper"]);
			$ls_codcueban=$io_report->rs_data->fields["codcueban"];
			$ls_descom=$io_report->rs_data->fields["dencom"];
			$ls_desran=$io_report->rs_data->fields["denran"];
			$ls_situacion=$io_report->rs_data->fields["situacion"];			
			$ls_causales=$io_report->rs_data->fields["dencausa"];
			$ls_fecha_I=$io_report->rs_data->fields["fecingper"];
			$ls_fechasitu=$io_funciones->uf_convertirfecmostrar($io_report->rs_data->fields["fecsitu"]);
			$ls_fecha_E=$io_report->rs_data->fields["fecingper"];
			$ls_fecingnom=$io_report->rs_data->fields["fecingnom"];	
			$ls_ano=calcular_anos_servicioas(strtotime($ls_fecha_I),strtotime($ls_fecingnom));
			$fecha_actual=date("Y/m/d"); 
			$ls_fecnacper=$io_report->rs_data->fields["fecnacper"];
			if ($ls_fecnacper!="")
			{
				$ls_edadper=calcular_anos_servicioas(strtotime($ls_fecnacper),strtotime($fecha_actual));
			}
			else
			{
				$ls_edadper=0;
			}
			if ($ls_ano<0)
			{
				$ls_ano=0;
			}
			switch($ls_situacion)
			{
				  case "1":
					$ls_situacion="Ninguno";
				  break;
				  case "2":
					$ls_situacion="Fallecido";
				  break;
				  case "3":
					$ls_situacion="Pensionado";
				  break;
				  case "4":
					$ls_situacion="Jubilado";
				  break;
				  case "5":
					$ls_situacion="Retirado";
				  break;				  		  
			}
			
			$li_fila++;			
			$lo_hoja->write($li_fila, 0,$ls_cedper,$lo_dataleft);
			$lo_hoja->write($li_fila, 1, $ls_apenomper,$lo_dataleft);
			$lo_hoja->write($li_fila, 2, $ls_edadper,$lo_datacenter);
			$lo_hoja->write($li_fila, 3, $ls_ano,$lo_datacenter);
			$lo_hoja->write($li_fila, 4, $ls_situacion,$lo_datacenter);
			$lo_hoja->write($li_fila, 5, $ls_fechasitu,$lo_datacenter);
			$lo_hoja->write($li_fila, 6, $ls_causales,$lo_datacenter);
			$lo_hoja->write($li_fila, 7, "",$lo_dataleft);
			$lo_hoja->write($li_fila, 8, "",$lo_dataleft);	
			$lb_valido1=$io_report->uf_recibo_nomina_oficiales($ls_codper);
			$li_pension=$io_report->rs_data_detalle->RecordCount();
			if (($li_pension>0)&&($lb_valido1))
			{
				while(!$io_report->rs_data_detalle->EOF)
				{
					$ls_prima=$io_report->rs_data_detalle->fields["pridesper"]; //prima por descendencia
					$ls_pocentaje=$io_report->rs_data_detalle->fields["porpenper"]; // porcentaje
					$ls_prima_NA=$io_report->rs_data_detalle->fields["prinoascper"]; //prima por no ascenso
					$ls_prima_Esp=$io_report->rs_data_detalle->fields["priespper"]; //prima especial
					$ls_sueldo_base=$io_report->rs_data_detalle->fields["suebasper"]; //sueldo base
					$ls_monto=number_format($io_report->rs_data_detalle->fields["monpenper"],2,",","."); //monto en bs
					$io_report->rs_data_detalle->MoveNext();
				}			
				$li_fila++;
				$lo_hoja->write($li_fila, 2,"% PENSIÓN",$lo_titulo);
				$lo_hoja->write($li_fila, 3,"PRIMA POR DESCENDENCIA",$lo_titulo);				
				$lo_hoja->write($li_fila, 4,"PRIMA POR NO ASCENSO",$lo_titulo);
				$lo_hoja->write($li_fila, 5, "PRIMA ESPECIAL",$lo_titulo);
				$lo_hoja->write($li_fila, 6, "PENSIÓN BASE",$lo_titulo);
				$lo_hoja->write($li_fila, 7,"MOTNO BS.",$lo_titulo);
				
				$li_fila++;
				$lo_hoja->write($li_fila, 2,$ls_pocentaje,$lo_datacenter);
				$lo_hoja->write($li_fila, 3,$ls_prima,$lo_datacenter);
				$lo_hoja->write($li_fila, 4,$ls_prima_NA,$lo_datacenter);
				$lo_hoja->write($li_fila, 5,$ls_prima_Esp,$lo_datacenter);
				$lo_hoja->write($li_fila, 6,$ls_sueldo_base,$lo_datacenter);
				$lo_hoja->write($li_fila, 7,$ls_monto,$lo_datacenter);
				
			}
			$li_pension=0;	
			$lb_valido2=$io_report->uf_buscar_beneficiarios('', '',$ls_codper,$ls_codper);
			$li_bene=$io_report->rs_data_detalle2->RecordCount();
			if (($li_bene>0)&&($lb_valido2))
			{
				while(!$io_report->rs_data_detalle2->EOF)
				{
					$ls_ced_ben=$io_report->rs_data_detalle2->fields["cedben"]; 
					$ls_nombre_ben=$io_report->rs_data_detalle2->fields["apeben"].", ".$io_report->rs_data_detalle2->fields["nomben"]; 
					$ls_porcentaje_ben=$io_report->rs_data_detalle2->fields["porpagben"];
					$ls_banco_ben=$io_report->rs_data_detalle2->fields["banco"];				
					$ls_cta_ben=$io_report->rs_data_detalle2->fields["ctaban"];
					$ls_nex_ben=$io_report->rs_data_detalle2->fields["nexben"];
					$ls_fecnacben=$io_report->rs_data_detalle2->fields["fecnacben"];  
					$fecha_actual=date("Y/m/d"); 
					$ls_ano=calcular_anos_servicioas(strtotime($ls_fecnacben),strtotime($fecha_actual));
					switch($ls_nex_ben)
					{
						case "-":
						     $ls_nex_ben="Niguno";
						break;
						case "C":
						     $ls_nex_ben="Conyugue";
						break;
						case "H":
						     $ls_nex_ben="Hijo";
						break;
						case "P":
						     $ls_nex_ben="Progenitor";
						break;
						case "C":
						     $ls_nex_ben="Hermano";
						break;
					}
					$io_report->rs_data_detalle2->MoveNext();
				}			
				$li_fila++;
				$lo_hoja->write($li_fila, 0,"CÉDULA",$lo_titulo);
				$lo_hoja->write($li_fila, 1,"NOMBRE",$lo_titulo);				
				$lo_hoja->write($li_fila, 2,"EDAD",$lo_titulo);
				$lo_hoja->write($li_fila, 3, "PARENTESCO CON EL PENSIONADO",$lo_titulo);
				$lo_hoja->write($li_fila, 4, "% DE PENSIÓN",$lo_titulo);
				$lo_hoja->write($li_fila, 5,"BANCO",$lo_titulo);
				$lo_hoja->write($li_fila, 6,"CTA. BANCARIA",$lo_titulo);
				$li_fila++;
				$lo_hoja->write($li_fila, 0,$ls_ced_ben,$lo_dataleft);
				$lo_hoja->write($li_fila, 1,$ls_nombre_ben,$lo_dataleft);
				$lo_hoja->write($li_fila, 2,$ls_ano,$lo_datacenter);
				$lo_hoja->write($li_fila, 3,$ls_nex_ben,$lo_datacenter);
				$lo_hoja->write($li_fila, 4,$ls_porcentaje_ben,$lo_datacenter);
				$lo_hoja->write($li_fila, 5,$ls_banco_ben,$lo_dataleft);
				$lo_hoja->write($li_fila, 6,' '.$ls_cta_ben.' ',$lo_datacenter);
			}	
			$li_bene=0;
			$lb_valido=$io_report->uf_pagonomina_conceptopersonal($ls_codper,$ls_conceptocero,$ls_tituloconcepto,$ls_conceptoreporte,$ls_conceptop2); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_fila++;
				$lo_hoja->write($li_fila, 2,"CÓDIGO",$lo_titulo);
				$lo_hoja->write($li_fila, 3,"DENOMINACIÓN",$lo_titulo);				
				$lo_hoja->write($li_fila, 4,"CUOTA / PLAZO",$lo_titulo);
				$lo_hoja->write($li_fila, 5, "ASIGNACIÓN",$lo_titulo);
				$lo_hoja->write($li_fila, 6, "DEDUCCIÓN",$lo_titulo);
				$lo_hoja->write($li_fila, 7,"NETO",$lo_titulo);
				
				while(!$io_report->rs_data_detalle->EOF)
				{
					$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
					$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
					$ls_tipsal=rtrim($io_report->rs_data_detalle->fields["tipsal"]);
					$li_valsal=abs($io_report->rs_data_detalle->fields["valsal"]);
					$ls_repconsunicon=$io_report->rs_data_detalle->fields["repconsunicon"];
					$ls_consunicon=$io_report->rs_data_detalle->fields["consunicon"];
					$ls_cuota="";
					if (($ls_repconsunicon=='1')&&($ls_consunicon!=""))
					{
						$io_report->uf_buscar_cuotas($ls_consunicon,$ls_codper,$ls_cuota);
					}
					switch($ls_tipsal)
					{
						case "A":
							$li_totalasignacion=$li_totalasignacion + $li_valsal;
							$li_asignacion=$li_valsal;
							$li_deduccion=""; 
							$li_aporte=""; 
							break;
							
						case "V1":
							$li_totalasignacion=$li_totalasignacion + $li_valsal;
							$li_asignacion=$li_valsal;
							$li_deduccion=""; 
							$li_aporte=""; 
							break;
							
						case "W1":
							$li_totalasignacion=$li_totalasignacion + $li_valsal;
							$li_asignacion=$li_valsal;
							$li_deduccion=""; 
							$li_aporte=""; 
							break;
							
						case "D":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=$li_valsal;
							$li_aporte=""; 
							break;
							
						case "V2":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=$li_valsal;
							$li_aporte=""; 
							break;
							
						case "W2":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=$li_valsal;
							$li_aporte=""; 
							break;

						case "P1":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=$li_valsal;
							$li_aporte=""; 
							break;

						case "V3":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=$li_valsal;
							$li_aporte=""; 
							break;

						case "W3":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=$li_valsal;
							$li_aporte=""; 
							break;

						case "P2":
							$li_totalaporte=$li_totalaporte + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=""; 
							$li_aporte=$li_valsal;
							break;

						case "V4":
							$li_totalaporte=$li_totalaporte + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=""; 
							$li_aporte=$li_valsal;
							break;

						case "W4":
							$li_totalaporte=$li_totalaporte + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=""; 
							$li_aporte=$li_valsal;
							break;

						case "R":
							$li_asignacion=$li_valsal;
							$li_deduccion=""; 
							$li_aporte="";
							break;
					}
					
					$li_fila++;
					$lo_hoja->write($li_fila, 2,$ls_codconc,$lo_dataleft);
					$lo_hoja->write($li_fila, 3,$ls_nomcon,$lo_dataleft);
					$lo_hoja->write($li_fila, 4,$ls_cuota,$lo_dataleft);
					$lo_hoja->write($li_fila, 5,$li_asignacion,$lo_dataright);
					$lo_hoja->write($li_fila, 6,$li_deduccion,$lo_dataright);
					$lo_hoja->write($li_fila, 7,'',$lo_dataright);
					$io_report->rs_data_detalle->MoveNext();
				}
				$li_total_neto=$li_totalasignacion-$li_totaldeduccion;
				$li_totasi=$li_totasi+$li_totalasignacion;
				$li_totded=$li_totded+$li_totaldeduccion;
				$li_totapo=$li_totapo+$li_totalaporte;
				$li_totgeneral=$li_totgeneral+$li_total_neto;
				$li_fila++;
				$lo_hoja->write($li_fila, 4,"TOTALES BS.",$lo_titulo);
				$lo_hoja->write($li_fila, 5,$li_totalasignacion,$lo_dataright);
				$lo_hoja->write($li_fila, 6,$li_totaldeduccion,$lo_dataright);
				$lo_hoja->write($li_fila, 7,$li_total_neto,$lo_dataright);	
				$li_fila++;			
				$lo_hoja->write($li_fila,3,"DATOS DEL PENSIONADO",$lo_encabezado);
				$li_fila++;	
				$lo_hoja->write($li_fila, 0, "CEDULA",$lo_titulo);
				$lo_hoja->write($li_fila, 1, "NOMBRE",$lo_titulo);
				$lo_hoja->write($li_fila, 2, "EDAD",$lo_titulo);
				$lo_hoja->write($li_fila, 3, "AÑOS DE SERVICIO",$lo_titulo);
				$lo_hoja->write($li_fila, 4, "SITUACION",$lo_titulo);
				$lo_hoja->write($li_fila, 5, "FECHA DE LA SITUACION",$lo_titulo);
				$lo_hoja->write($li_fila, 6, "CAUSAL",$lo_titulo);
				$lo_hoja->write($li_fila, 7, "FUERZA",$lo_titulo);
				$lo_hoja->write($li_fila, 8, "GRADO",$lo_titulo);
				$li_fila++;					
			}			
			$io_report->rs_data->MoveNext();
		}
		$li_fila++;
		$lo_hoja->write($li_fila, 4,"TOTAL NOMINA.",$lo_titulo);
		$lo_hoja->write($li_fila, 5,$li_totasi,$lo_dataright);
		$lo_hoja->write($li_fila, 6,$li_totded,$lo_dataright);
		$lo_hoja->write($li_fila, 7,$li_totgeneral,$lo_dataright);		
		
		if(!$lb_valido) // Si no ocurrio ningún error
		{
			
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		
	}
	$lo_libro->close();
	header("Content-Type: application/x-msexcel; name=\"pagonomina.xls\"");
	header("Content-Disposition: inline; filename=\"pagonomina.xls\"");
	$fh=fopen($lo_archivo, "rb");
	fpassthru($fh);
	unlink($lo_archivo);
	print("<script language=JavaScript>");
	print(" close();");
	print("</script>");
	unset($io_pdf);
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 