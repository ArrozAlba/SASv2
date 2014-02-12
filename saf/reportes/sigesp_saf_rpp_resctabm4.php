<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//  ESTE FORMATO SE IMPRIME EN Bs Y EN BsF. SEGUN LO SELECCIONADO POR EL USUARIO
	//  MODIFICADO POR: ING.YOZELIN BARRAGAN         FECHA DE MODIFICACION : 27/08/2007
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "</script>";		
	}//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_cmpmov,$ad_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_cmpmov // numero de comprobante de movimiento
		//	    		   ad_fecha // Fecha 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Luis Anibal Lang
		//     Modificado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->saveState();
		$io_pdf->line(50,40,950,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],22,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=504-($li_tm/2);
		$io_pdf->addText($tm,550,11,"<b>".$as_titulo."</b>"); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$ad_fecha);
		$tm=504-($li_tm/2);
		$io_pdf->addText(750,535,11,""); // Agregar la fecha
		$io_pdf->addText($tm,535,11,$ad_fecha); // Agregar la fecha
		$io_pdf->addText(750,555,11,""); // Agregar la fecha
		$io_pdf->addText(800,555,11,""); // Agregar la fecha
		$io_pdf->addText(928,570,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(934,563,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------


	function uf_print_totales($ai_moninc,$ai_mondesinc,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: ai_moninc    // Total de Incorporaciones en el mes más el total de existencia del mes anterior
		//                 ai_mondesinc // Total de Desincorporaciones en el mes
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los totales de incorporaciones y desincorporaciones
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/12/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-300);	
		$la_data=array(array('total'=>'<b>TOTALES: </b>                                                                          '.$ai_moninc.'          '.$ai_mondesinc.''));
		$la_columna=array('total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 11, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>480, // Ancho Máximo de la tabla
						 'xOrientation'=>'right'); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_estado,$as_municipio,$as_denunisrv,$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: 
		//                 as_estado     //nombre del estado 
		//                 as_municipio  // nombre del municipio
		//                 as_diremp     // direccion de la empresa
		//                 as_codemp     // codigo de empresa
		//	    		   as_nomemp     // nombre de empresa
		//                 as_denunisrv  // nombre de la Unidad de Servicio
		//                 as_denunidep  // nombre de la Unidad de Trabajo o Dependencia
		//                 as_periodo    //  periodo en el que se hace el reporte
		//	    		   io_pdf        // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 04/12/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tipoformato;
		if($ls_tipoformato==0)
		{
		  $ls_titulo="Costo Bs.:";
		}
		elseif($ls_tipoformato==1)
		{
		  $ls_titulo="Costo Bs.F.:";
		}
		$la_data=array(array('name'=>'<b>ESTADO:</b>  '.$as_estado.''.'<b> MUNICIPIO:</b> '.$as_municipio.''),
					   array('name'=>'<b>UNIDAD DE TRABAJO:</b>  '.$as_denunisrv.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar Líneas
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>954, // Ancho de la tabla
						 'maxWidth'=>954); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($li_existencia,$li_totincmes,$li_totdesinc_no_060,$li_totdesinc_060,$li_exisfinal,$li_tot_inc,$li_tot_desinc,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: li_existencia        // Monto de la Existencia de Bienes en Bs.
		//                 li_totincmes         // Total de incorporaciones en el mes, expresados en Bs.
		//                 li_totdesinc_no_060  // Total de Desincorporaciones con causa distinta a la 060, expresados en Bs.
		//                 li_totdesinc_060     // Total de Desincorporaciones con causa igual a la 060, expresados en Bs.  
		//	               io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 10/12/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
    $io_pdf->addText(50,420,12,"Existencia del Mes Anterior...........................................................................................");
	$io_pdf->addText(600,420,12,$li_existencia);
		
	$io_pdf->addText(50,390,12,"Incorporaciones en el mes de la cuenta........................................................................");
	$io_pdf->addText(600,390,12,$li_totincmes);
	
	$io_pdf->addText(50,360,12,"Desincorporaciones en el mes de la cuenta por todos los conceptos, con");
	$io_pdf->addText(50,345,12,"excepcion del 060 - Faltantes de Bienes por Investigar................................................");
	$io_pdf->addText(800,345,12,$li_totdesinc_no_060);
	
	$io_pdf->addText(50,320,12,"Desincorporaciones en el mes de la cuenta por el concepto"); 
	$io_pdf->addText(50,305,12,"060 - Faltantes de Bienes por Investigar.......................................................................");
	$io_pdf->addText(800,305,12,$li_totdesinc_060);
	
	$io_pdf->addText(50,275,12,"Existencia Final.............................................................................................................");
	$io_pdf->addText(800,275,12, $li_exisfinal);
	$io_pdf->addText(50,260,12,"                                                                                                                                                         ================================================");
	$io_pdf->addText(50,240,12,"                                                                                              TOTALES");
	$io_pdf->addText(800,240,14, $li_tot_desinc);
	$io_pdf->addText(600,240,14, $li_tot_desinc);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------

	function uf_print_firmas(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_firmas
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime las firmas
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 06/12/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$io_pdf->addText(40,150,9,"JEFE DE LA UNIDAD DE TRABAJO:");	
	$io_pdf->addText(215,150,9,"Nombre y Apellido:______________________________________");
	$io_pdf->addText(485,150,9,"C.I.: ______________________________");
	$io_pdf->addText(685,150,9,"Firma:______________________________");
	
	$io_pdf->addText(40,100,9,"BIENES:");	
	$io_pdf->addText(215,100,9,"Nombre y Apellido:______________________________________");
	$io_pdf->addText(485,100,9,"C.I.: ______________________________");
	$io_pdf->addText(685,100,9,"Firma:______________________________");
                	
	}// end function uf_print_firmas	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_funciones_activos.php");
	$io_fun_activos=new class_funciones_activos();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ld_desde=$io_fun_activos->uf_obtenervalor_get("desde","");
	$ld_hasta=$io_fun_activos->uf_obtenervalor_get("hasta","");
	$ld_fecha="";
	$ls_titulo="<b>RESUMEN DE LA CUENTA DE BIENES MUEBLES</b>";
	if(($ld_desde!="")&&($ld_hasta!=""))
	{
	    $periodo = substr($ld_desde,3,2).'-'.substr($ld_desde,6,4);
		if (substr($ld_desde,3,2) == '01')
		{
		 $ld_mes = 12;
		 $ld_anno = intval(substr($ld_desde,6,4)) - 1;
		}
		else
		{
		 $ld_mes  = intval(substr($ld_desde,3,2))-1;
		 $ld_anno = intval(substr($ld_desde,6,4));
		}
		$ld_fecha="Desde:".$ld_desde."  Hasta:".$ld_hasta."";
	}
	else
	{
	 $periodo = "";
	 $ld_fecha="";
	}
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$arre=$_SESSION["la_empresa"];
	$ls_estemp=$arre["estemp"];
	$ls_codemp=$arre["codemp"];
	$ls_coddesde=$io_fun_activos->uf_obtenervalor_get("coddesde","");
	$ls_codhasta=$io_fun_activos->uf_obtenervalor_get("codhasta","");
	$ls_coduniadm=$io_fun_activos->uf_obtenervalor_get("coduniadm","");
	$ls_denuniadm=$io_fun_activos->uf_obtenervalor_get("denuniadm","");
	$ls_tipoformato=$io_fun_activos->uf_obtenervalor_get("tipoformato",0);
	$ls_orden=$io_fun_activos->uf_obtenervalor_get("orden",0);
	global $ls_tipoformato;
	if($ls_tipoformato==1)
	{
		require_once("sigesp_saf_class_reportbsf.php");
		$io_report=new sigesp_saf_class_reportbsf();
	}
	else
	{
		require_once("sigesp_saf_class_report.php");
		$io_report=new sigesp_saf_class_report();
	}	
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido_exi=$io_report->uf_saf_load_existencia($ls_codemp,$ls_coduniadm,$ld_mes,$ld_anno,$ls_coddesde,$ls_codhasta,$ls_orden);
	$lb_valido_inc=$io_report->uf_saf_load_dt_resctabiemue_inc($ls_codemp,$ls_coduniadm,$ld_desde,$ld_hasta,$ls_coddesde,$ls_codhasta,$ls_orden); 
	$lb_valido_desinc=$io_report->uf_saf_load_dt_resctabiemue_desinc($ls_codemp,$ls_coduniadm,$ld_desde,$ld_hasta,$ls_coddesde,$ls_codhasta,$ls_orden);
	$lb_valido_desinc_060=$io_report->uf_saf_load_dt_resctabiemue_desinc_060($ls_codemp,$ls_coduniadm,$ld_desde,$ld_hasta,$ls_coddesde,$ls_codhasta,$ls_orden);
	
	if(($lb_valido_exi==false)&&($lb_valido_inc==false)&&($lb_valido_desinc==false)&&($lb_valido_desinc_060==false)) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		/////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////
		$ls_desc_event="Generó un reporte de Activo. Desde el activo   ".$ls_coddesde." hasta   ".$ls_codhasta;
		//$io_fun_activos->uf_load_seguridad_reporte("SAF","sigesp_saf_r_relmovbm2.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		$io_pdf->ezStartPageNumbers(940,50,10,'','',1); // Insertar el número de página
		uf_print_encabezado_pagina($ls_titulo,"",$ld_fecha,$io_pdf); // Imprimimos el encabezado de la página
		$li_totrow=$io_report->ds->getRowCount("codact");
		$i=0;
		$li_tot_exi_mes_ant=0.00;
		$li_tot_inc_mes=0.00;
		$li_tot_desinc_mes=0.00;
		$li_tot_desinc_060_mes=0.00;
	        $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount;
			
			$lb_valido_exi=$io_report->uf_saf_load_existencia($ls_codemp,$ls_coduniadm,$ld_mes,$ld_anno,$ls_coddesde,$ls_codhasta,$ls_orden); // Obtenemos el detalle del reporte
			if($lb_valido_exi)
			{
				$li_inc_mes_ant=0.00;
				$li_desinc_mes_ant=0.00;
				$ls_estatus="";
				$li_totrow_det=$io_report->ds_detalle->getRowCount("codact");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
				    $ls_estatus=$io_report->ds_detalle->data["estatus"][$li_s];
					if ($ls_estatus == 'I')
					{
					 $li_inc_mes_ant = $li_inc_mes_ant + $li_exi_mes_ant=$io_report->ds_detalle->data["tot_exi_mes"][$li_s];
					}
					else
					{
					 $li_desinc_mes_ant = $li_desinc_mes_ant + $li_exi_mes_ant=$io_report->ds_detalle->data["tot_exi_mes"][$li_s];
					}
				}
				$li_tot_exi_mes_ant =  $li_inc_mes_ant - $li_desinc_mes_ant; 
			}
			
			$lb_valido_inc=$io_report->uf_saf_load_dt_resctabiemue_inc($ls_codemp,$ls_coduniadm,$ld_desde,$ld_hasta,$ls_coddesde,$ls_codhasta,$ls_orden); // Obtenemos el detalle del reporte
			if($lb_valido_inc)
			{
				$li_inc_mes=0.00;
				$li_totrow_det=$io_report->ds_detalle->getRowCount("codact");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
				    $li_inc_mes=$io_report->ds_detalle->data["tot_inc_mes"][$li_s];
					$li_tot_inc_mes =  $li_tot_inc_mes +  $li_inc_mes; 
				}
			}
				$lb_valido_desinc=$io_report->uf_saf_load_dt_resctabiemue_desinc($ls_codemp,$ls_coduniadm,$ld_desde,$ld_hasta,$ls_coddesde,$ls_codhasta,$ls_orden);
			 if($lb_valido_desinc)
			{
				$li_desinc_mes=0.00;
				$li_totrow_det=$io_report->ds_detalle->getRowCount("codact");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
				    $li_desinc_mes=$io_report->ds_detalle->data["tot_desinc_no_060"][$li_s];
					$li_tot_desinc_mes =  $li_tot_desinc_mes +  $li_desinc_mes; 
				}
			}
			$lb_valido_desinc_060=$io_report->uf_saf_load_dt_resctabiemue_desinc_060($ls_codemp,$ls_coduniadm,$ld_desde,$ld_hasta,$ls_coddesde,$ls_codhasta,$ls_orden);
			if($lb_valido_desinc_060)
			{
				$li_desinc_060_mes=0.00;
				$li_totrow_det=$io_report->ds_detalle->getRowCount("codact");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
				    $li_desinc_060_mes=$io_report->ds_detalle->data["tot_desinc_060"][$li_s];
					$li_tot_desinc_060_mes =  $li_tot_desinc_060_mes +  $li_desinc_060_mes; 
				}
				
			}
			    $li_exisfinal = 0.00;
				$li_tot_inc = 0.00;
				$li_tot_desinc = 0.00; 
				$li_exisfinal = $li_tot_exi_mes_ant + $li_tot_inc_mes - $li_tot_desinc_mes - $li_tot_desinc_060_mes;
				$li_tot_inc = $li_tot_exi_mes_ant +  $li_tot_inc_mes;
				$li_tot_inc = $io_fun_activos->uf_formatonumerico($li_tot_inc);
				$li_tot_desinc = $li_exisfinal + $li_tot_desinc_mes + $li_tot_desinc_060_mes;
				$li_tot_desinc = $io_fun_activos->uf_formatonumerico($li_tot_desinc);
				$li_tot_exi_mes_ant=$io_fun_activos->uf_formatonumerico($li_tot_exi_mes_ant);
				$li_tot_inc_mes=$io_fun_activos->uf_formatonumerico($li_tot_inc_mes);
				$li_tot_desinc_mes=$io_fun_activos->uf_formatonumerico($li_tot_desinc_mes);
			    $li_tot_desinc_060_mes=$io_fun_activos->uf_formatonumerico($li_tot_desinc_060_mes);
	            $li_exisfinal=$io_fun_activos->uf_formatonumerico($li_exisfinal);
				uf_print_cabecera($ls_estemp,'',$ls_denuniadm,$io_pdf); // Imprimimos la cabecera del registro
				uf_print_detalle($li_tot_exi_mes_ant,$li_tot_inc_mes,$li_tot_desinc_060_mes,$li_tot_desinc_mes, $li_exisfinal,$li_tot_inc,$li_tot_desinc,$io_pdf); // Imprimimos el detalle 
		        uf_print_firmas(&$io_pdf);	
		if(($lb_valido_exi)||($lb_valido_inc)||($lb_valido_desinc)||($lb_valido_desinc_060))
		{
			$io_pdf->ezStopPageNumbers(1,1);
			$io_pdf->ezStream();
		}
		else
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}		
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 