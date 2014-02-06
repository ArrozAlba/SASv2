<?php
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if (!array_key_exists("la_logusr",$_SESSION))
	{
		 print "<script language=JavaScript>";
		 print "close();";
		 print "</script>";		
	}
	ini_set('memory_limit','512M');
	ini_set('max_execution_time ','0');
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_periodo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 15/07/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Generó el Reporte Libro de Compra para el periodo ".$as_periodo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_librocompra.php",$ls_descripcion);
		return $lb_valido;
	}
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($ls_mes,$ls_ano,$ld_tot_basimp,$ld_tot_sinderiva,$ld_monimp14,$ld_monimp8,$ad_totgenadi,&$io_pdf)										
				                         
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   hidnumero // Número de Orden de compra
		//	    		   ls_fecord // fecha de Orden de compra
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
        	$ls_titulo="RESUMEN DE LIBRO DE COMPRAS Bs.F.";	      				
		}
		else
		{
        	$ls_titulo="RESUMEN DE LIBRO DE COMPRAS Bs.";	      				
		}
		$io_encabezado=$io_pdf->openObject();		
		$io_pdf->saveState();				
		$io_pdf->rectangle(20,480,570,220);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],35,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo		
		$li_tm=$io_pdf->getTextWidth(16,$ls_titulo);
		$tm=296-($li_tm/2);
		$io_pdf->addText($tm,660,16,$ls_titulo); // Agregar el ttulo
        $ls_periodo="<b>MES :</b>".$ls_mes."        "."<b>AÑO :</b>".$ls_ano;	
		$li_tm=$io_pdf->getTextWidth(14,$ls_periodo);
		$tm=296-($li_tm/2);
		$io_pdf->addText($tm,640,12,$ls_periodo); // Agregar el título
		
		$ld_tot_basimp    = number_format($ld_tot_basimp,2,',','.');	
		$ld_tot_sinderiva = number_format($ld_tot_sinderiva,2,',','.');	
		$ld_monimp14      = number_format($ld_monimp14,2,',','.');	
		$ld_monimp8       = number_format($ld_monimp8,2,',','.');	
		$ad_totgenadi     = number_format($ad_totgenadi,2,',','.');	
				
		$la_data[1]=array('titulo'=>'<b>TOTAL BASE IMPONIBLE: </b>','valor'=>$ld_tot_basimp);
		$la_data[2]=array('titulo'=>'<b>TOTAL EXENTOS: </b>','valor'=>$ld_tot_sinderiva);
   		$la_data[3]=array('titulo'=>'<b>TOTAL IMPUESTO ALICUOTA GENERAL: </b>','valor'=>$ld_monimp14);	
   		$la_data[4]=array('titulo'=>'<b>TOTAL IMPUESTO ALICUOTA GENERAL + ADICIONAL: </b>','valor'=>$ad_totgenadi);	
		$la_data[5]=array('titulo'=>'<b>TOTAL IMPUESTO ALICUOTA REDUCIDA: </b>','valor'=>$ld_monimp8);			
		
		$la_columna=array('titulo'=>'','valor'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>  12, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla						 
						 'maxWidth'=>580, // Ancho Máximo de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>350), // Justificación y ancho de la columna
						 			   'valor'=>array('justification'=>'right','width'=>130))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');		
		$io_pdf->ezSetCmMargins(7.5,6.5,3,3); // Configuración de los margenes en centímetros
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	
					
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
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
	$li_mes =$io_fun_cxp->uf_obtenervalor_get("mes",0);
	$ls_agno=$io_fun_cxp->uf_obtenervalor_get("agno",0);
	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_cxp_class_reportbsf.php");
		$io_report=new sigesp_cxp_class_reportbsf();
	}
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo     = "<b>Libro de Compras</b>";
	$ls_mes        = $io_fecha->uf_load_nombre_mes($li_mes);
	$li_lastday    = $io_fecha->uf_last_day($li_mes,$ls_agno);
	$li_lastday    = substr($li_lastday,0,2);
	$as_fechadesde = $ls_agno.'-'.$li_mes.'-01';
	$as_fechahasta = $ls_agno.'-'.$li_mes.'-'.$li_lastday;
	$ls_periodo    = "MES: ".$ls_mes."    "."AÑO:".$ls_agno."";
	//--------------------------------------------------------------------------------------------------------------------------------
	$arremp      = $_SESSION["la_empresa"];
    $ls_codemp   = $arremp["codemp"];
	$lb_valido=$io_report->uf_select_report_libcompra($as_fechadesde,$as_fechahasta,&$rs_resultado);
	error_reporting(E_ALL);
	set_time_limit(1800);
	$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->ezSetCmMargins(6.5,3,3,3); // Configuración de los margenes en centímetros
	$io_pdf->ezStartPageNumbers(970,40,10,'','',1); // Insertar el número de página
	$ldec_totimp8      = 0; //Variable de tipo acumulador que almacenara el monto total de los impuestos a 8%.
	$ldec_totimp9      = 0; //Variable de tipo acumulador que almacenara el monto total de los impuestos a 9%.
	$ldec_totimp25     = 0; //Variable de tipo acumulador que almacenara el monto total de los impuestos a 25%.
	$ldec_totbasimp8   = 0;
	$ldec_totbasimp9   = 0;
	$ldec_totbasimp25  = 0;
	$ldec_totcomsiniva = 0;
	$ldec_totbaseimp   = 0;
	if($lb_valido)
	{
		$li=0;
		while($row=$io_report->io_sql->fetch_row($rs_resultado))	
		{
			$ldec_monret=0;
			$ls_numrecdoc=trim($row["numrecdoc"]);
			$ls_tipproben=$row["tipproben"];
			$ls_codpro=trim($row["cod_pro"]);
			$ls_cedben=trim($row["ced_bene"]);
			$ldec_montoret=$row["monret"];
			$ldec_montodoc=$row["montotdoc"];
			$ldec_mondeddoc=$row["mondeddoc"];
			$ls_codtipdoc =$row["codtipdoc"];
			if($ls_tiporeporte==1)
			{
				$la_cmpret=$io_report->uf_select_rowdata($io_sql,"SELECT DISTINCT max(a.numrecdoc) as numrecdoc,max(a.monobjretaux) as monobjret,SUM(a.monretaux) as monret,max(a.porded) as porded,max(b.codret) as codret,max(b.numcom) as numcom,max(b.iva_retaux) as iva_ret,max(tiptrans) as tiptrans".
																  "   FROM cxp_rd_deducciones a,scb_dt_cmp_ret b                                                                         ".
																  "  WHERE a.codemp=b.codemp AND a.numrecdoc=b.numfac AND a.codemp='".$ls_codemp."' AND a.numrecdoc='".$ls_numrecdoc."' AND ".
																  "        a.cod_pro='".$ls_codpro."' AND a.ced_bene='".$ls_cedben."'                                              ".
																  " GROUP BY a.numrecdoc ");
			}
			else
			{
				$la_cmpret=$io_report->uf_select_rowdata($io_sql,"SELECT DISTINCT max(a.numrecdoc) as numrecdoc,max(a.monobjret) as monobjret,SUM(a.monret) as monret,max(a.porded) as porded,max(b.codret) as codret,max(b.numcom) as numcom,max(b.iva_ret) as iva_ret,max(tiptrans) as tiptrans".
																  "   FROM cxp_rd_deducciones a,scb_dt_cmp_ret b                                                                         ".
																  "  WHERE a.codemp=b.codemp AND a.numrecdoc=b.numfac AND a.codemp='".$ls_codemp."' AND a.numrecdoc='".$ls_numrecdoc."' AND ".
																  "        a.cod_pro='".$ls_codpro."' AND a.ced_bene='".$ls_cedben."'                                              ".
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
			$ldec_totbaseimp=$ldec_totbaseimp+$ldec_baseimp;
			$li_anticipos=$io_report->uf_buscar_asientomanual($ls_numrecdoc,$ls_codtipdoc,$ls_cedben,$ls_codpro);
			$ldec_montodoc= $ldec_montodoc+$ldec_mondeddoc;			
			$ldec_sinderiva= ($ldec_montodoc + $li_anticipos) -($ldec_baseimp+$ldec_monimp);// Total de la Compra sin Derecho a Crédito Iva.
			$li_porcentaje= intval($ldec_porcar);
			if ($ldec_porcar>0)
			{
				switch ($li_porcentaje){
				  case '8':
					$ldec_totimp8    = ($ldec_totimp8+$ldec_monimp);
					$ldec_totbasimp8 = ($ldec_totbasimp8+$ldec_baseimp);
					break;
				  case '9'||'14'||'11':
					$ldec_totimp9    = ($ldec_totimp9+$ldec_monimp);  
					$ldec_totbasimp9 = ($ldec_totbasimp9+$ldec_baseimp);
					break;
				  case '25':
					$ldec_totimp25    = ($ldec_totimp25+$ldec_monimp);  
					$ldec_totbasimp25 = ($ldec_totbasimp28+$ldec_baseimp);
					break;
				  }
			 }	
			 $ldec_totcomsiniva = ($ldec_totcomsiniva+$ldec_sinderiva);	
			 $ldec_basimpga     = ($ldec_totbasimp9+$ldec_totbasimp25);//Total Base Imponible Compras Internas Afectadas en Alicuota General + Adicional(9% y 25%).
			 $ldec_totgenadi    = ($ldec_totimp9+$ldec_totimp25);//Total Compras Internas Afectadas en Alicuota Reducida. Impuestos al 9% y 25%.
			 if(($ldec_totcomsiniva<0)&&($ldec_totcomsiniva>-0.004))
			 {
			 	$ldec_totcomsiniva=abs($ldec_totcomsiniva);
			 }
			$la_notas=$io_report->uf_select_rowdata($io_sql,"SELECT cxp_sol_dc.*,(SELECT porcar FROM cxp_dc_cargos WHERE cxp_sol_dc.codemp=cxp_dc_cargos.codemp AND cxp_sol_dc.numsol=cxp_dc_cargos.numsol AND cxp_sol_dc.numrecdoc=cxp_dc_cargos.numrecdoc AND cxp_sol_dc.codtipdoc=cxp_dc_cargos.codtipdoc AND cxp_sol_dc.cod_pro=cxp_dc_cargos.cod_pro AND cxp_sol_dc.ced_bene=cxp_dc_cargos.ced_bene AND cxp_sol_dc.codope=cxp_dc_cargos.codope AND cxp_sol_dc.numdc=cxp_dc_cargos.numdc) as porcar FROM cxp_sol_dc WHERE cxp_sol_dc.numrecdoc='".$ls_numrecdoc."' AND cxp_sol_dc.codtipdoc='".$ls_codtipdoc."' AND cxp_sol_dc.cod_pro='".$ls_codpro."' AND cxp_sol_dc.ced_bene='".$ls_cedben."' ");
			if(count($la_notas)>0)
			{
				$ls_codope=$la_notas["codope"];
				$ls_numnota=$la_notas["numdc"];
				$ls_monnota=$la_notas["monto"];
				$ls_carnota=$la_notas["moncar"];
				$ldec_porcar=$la_notas["porcar"];
				$ls_basnota=$ls_monnota-$ls_carnota;
				if($ls_codope=='NC')
				{
					$ls_numnc=$ls_numnota;
					$ls_numnd="";
					$ls_monnota=$ls_monnota*(-1);
					$ls_carnota=$ls_carnota*(-1);
					$ls_basnota=$ls_basnota*(-1);
				}
				else
				{
					$ls_numnd=$ls_numnota;
					$ls_numnc="";
				} 
				 $li_porcentaje   = intval($ldec_porcar);
				if ($ldec_porcar>0)
				{
					switch ($li_porcentaje){
					  case '8':
						$ldec_totimp8    = ($ldec_totimp8+$ls_carnota);
						$ldec_totbasimp8 = ($ldec_totbasimp8+$ls_basnota);
						break;
					  case '9'||'14'||'11':
						$ldec_totimp9    = ($ldec_totimp9+$ls_carnota);  
						$ldec_totbasimp9 = ($ldec_totbasimp9+$ls_basnota);
						break;
					  case '25':
						$ldec_totimp25    = ($ldec_totimp25+$ls_carnota);  
						$ldec_totbasimp25 = ($ldec_totbasimp28+$ls_basnota);
						break;
					  }
				 }	
				 $ldec_totcomsiniva = ($ldec_totcomsiniva+$ldec_sinderiva);	
				 $ldec_basimpga     = ($ldec_totbasimp9+$ldec_totbasimp25);//Total Base Imponible Compras Internas Afectadas en Alicuota General + Adicional(9% y 25%).
				 $ldec_totgenadi    = ($ldec_totimp9+$ldec_totimp25);//Total Compras Internas Afectadas en Alicuota Reducida. Impuestos al 9% y 25%.
				$ldec_totbaseimp=$ldec_totbaseimp+$ls_basnota;
				 if(($ldec_totcomsiniva<0)&&($ldec_totcomsiniva>-0.004))
				 {
					$ldec_totcomsiniva=abs($ldec_totcomsiniva);
				 }
			}
			
		}	//fin del while
		//uf_print_encabezado_pagina($ls_mes,$ls_ano,$ld_tot_basimp,$ld_tot_sinderiva,$ld_monimp14,$ld_monimp8,$io_pdf);	
		$ldec_totgenadi    = ($ldec_totimp9+$ldec_totimp25);//Total Compras Internas Afectadas en Alicuota Reducida. Impuestos al 14% y 25%.
		uf_print_encabezado_pagina($ls_mes,$ls_agno,$ldec_totbaseimp,$ldec_totcomsiniva,$ldec_totimp9,$ldec_totimp8,$ldec_totgenadi,&$io_pdf);	
		uf_insert_seguridad($ls_periodo); 
	}		 	  	      	 
	else
	{
		print("<script language=JavaScript>");
		print("alert('No hay Registros para Mostrar');"); 
		print("close();");
		print("</script>");	
	}
    $io_pdf->ezStream(); // Mostramos el reporte		  
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 