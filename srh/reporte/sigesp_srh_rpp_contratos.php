<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Reporte de Contratos de Personal
//  ORGANISMO: Cualquiera
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 30/06/2008		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_srh;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("SRH","sigesp_srh_r_contratos.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(16,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,680,16,$as_titulo); // Agregar el título
		
			$io_pdf->addText(512,750,8,date("d/m/Y")); // Agregar la Fecha
			$io_pdf->addText(518,743,7,date("h:i a")); // Agregar la Hora
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	
	 //---------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------
	include("../../shared/class_folder/class_numero_a_letra.php");
	$io_numero_letra= new class_numero_a_letra();
	//imprime numero con los valore por defecto
	//cambia a minusculas
	$io_numero_letra->setMayusculas(1);
	//cambia a femenino
	$io_numero_letra->setGenero(1);
	//cambia moneda
	$io_numero_letra->setMoneda("Bolivares");
	//cambia prefijo
	$io_numero_letra->setPrefijo("");
	//cambia sufijo
	$io_numero_letra->setSufijo("");
  	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha = new class_fecha();	
	require_once("class_folder/sigesp_srh_class_report.php");
	$io_report  = new sigesp_srh_class_report('../../');
	require_once("../class_folder/utilidades/class_funciones_srh.php");
	$io_fun_srh=new class_funciones_srh("../../");
	require_once("../../sno/class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
		
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
       $ls_titulo="CONTRATO"; 
//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codcont=$io_fun_srh->uf_obtenervalor_get("codcont","");
	$ls_nroregdes=$io_fun_srh->uf_obtenervalor_get("nroregdes","");
	$ls_nroreghas=$io_fun_srh->uf_obtenervalor_get("nroreghas","");//---------------------------------------------------------------------------------------------------------------------------------
	global $la_data;
	$ls_prefijo="Bs.";
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_select_configuracion_contrato ($ls_codcont); // Obtenemos el detalle del reporte
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
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$li_totrow=$io_report->DS->getRowCount("codcont");
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_concont=$io_report->DS->data["concont"][$li_i];
			$li_tamletcont=$io_report->DS->data["tamletcont"][$li_i];
			$li_tamletpiecont=$io_report->DS->data["tamletpiecont"][$li_i];
			if($li_tamletpiecont=="")
			{
				$li_tamletpiecont=$li_tamletcont;
			}
			$li_intlincont=$io_report->DS->data["intlincont"][$li_i];
			$li_marinfcont=$io_report->DS->data["marinfcont"][$li_i];
			$li_marsupcont=$io_report->DS->data["marsupcont"][$li_i];
			$ls_titcont=$io_report->DS->data["titcont"][$li_i];
			$ls_piepagcont=$io_report->DS->data["piepagcont"][$li_i];
			$ls_ente=$_SESSION["la_empresa"]["nombre"];
			$ld_fecha=date("d/m/Y");
			$ls_dia_act=substr($ld_fecha,0,2);
			$ls_mes_act=$io_fecha->uf_load_nombre_mes(substr($ld_fecha,3,2));
			$ls_ano_act=substr($ld_fecha,6,4);
			$io_pdf->ezSetCmMargins($li_marsupcont,$li_marinfcont,3,3); // Configuración de los margenes en centímetros
			uf_print_encabezado_pagina($ls_titcont,$io_pdf); // Imprimimos el encabezado de la página
			$lb_valido=$io_report->uf_select_contratos_personal($ls_nroregdes,$ls_nroreghas, $rs_data); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				 $li_s=0;
				 $li_totrow_det=$io_report->io_sql->num_rows($rs_data);
				while ($row=$io_report->io_sql->fetch_row($rs_data))
				{
				    $li_s=$li_s+1;
					$ls_contenido="";
					$ls_contenido=$ls_concont;

					$ls_horario=trim ($row["horario"]);
					$ls_funcion=trim ($row["funcion"]);
					$ls_obs=trim ($row["observacion"]);		
					$ls_dentipcon=trim ($row["dentipcon"]);		
					$ls_nroreg=$row["nroreg"];
									
					$ls_cedper= trim ($row["codper"]);
					$ls_apeper= trim ($row["apeper"]);		
					$ls_nomper= trim ($row["nomper"]);	
							
					$ls_descar1=$row["descar1"];		
					$ls_descar2=$row["descar2"];		
					
					if ($ls_descar1=='')
					{
						$ls_descar= $ls_descar2;
					}
					else
					{
						$ls_descar= $ls_descar1;
					}

					
					$ls_profesion=$row["despro"];
					
					$ls_descripcion=trim ($row["descripcion"]);
					
					$ld_fecini=$row["fecini"];
					$ls_mes=$io_fecha->uf_load_nombre_mes(substr($ld_fecini,5,2));
					$ls_fechainicio="el ".substr($ld_fecini,8,2)." de ".$ls_mes." de ".substr($ld_fecini,0,4);
					$ld_fecfin=$row["fecfin"];
					$ls_mes=$io_fecha->uf_load_nombre_mes(substr($ld_fecfin,5,2));
					$ls_fechafinal="el ".substr($ld_fecfin,8,2)." de ".$ls_mes." de ".substr($ld_fecfin,0,4);
					
				
					$ls_nacper=$row["nacper"];
					switch($ls_nacper)
					{
						case "V": // Venezolano
							$ls_nacper="Venezolano";
							break;
						case "E": // Extranjero
							$ls_nacper="Extranjero";
							break;
					}
					
					$ls_desuniadm=$row["desuniadm"];	
					
						
					
					
					$li_monto=$io_fun_nomina->uf_formatonumerico($row["monto"]);		
					$io_numero_letra->setNumero($row["monto"]);
					$ls_monto=$io_numero_letra->letra();
					$ls_monto=$ls_monto." (".$ls_prefijo." ".$li_monto.")";
				
					$ls_contenido=str_replace("\$ls_dia",$ls_dia_act,$ls_contenido);
					$ls_contenido=str_replace("\$ls_mes",$ls_mes_act,$ls_contenido);
					$ls_contenido=str_replace("\$ls_ano",$ls_ano_act,$ls_contenido);
					$ls_contenido=str_replace("\$ls_nombres",$ls_nomper,$ls_contenido);
					$ls_contenido=str_replace("\$ls_apellidos",$ls_apeper,$ls_contenido);
					$ls_contenido=str_replace("\$ls_cedula",$ls_cedper,$ls_contenido);
					$ls_contenido=str_replace("\$ls_cargo",$ls_descar,$ls_contenido);
					$ls_contenido=str_replace("\$ls_profesion",$ls_profesion,$ls_contenido);					
					$ls_contenido=str_replace("\$ld_fecha_inicio",$ls_fechainicio,$ls_contenido);
					$ls_contenido=str_replace("\$ld_fecha_culminacion",$ls_fechafinal,$ls_contenido);				
					$ls_contenido=str_replace("\$ls_nacionalidad",$ls_nacper,$ls_contenido);					
					$ls_contenido=str_replace("\$ls_unidad_administrativa",$ls_desuniadm,$ls_contenido);
					$ls_contenido=str_replace("\$ls_horario",$ls_horario,$ls_contenido);				
					$ls_contenido=str_replace("\$ls_descripcion",$ls_descripcion,$ls_contenido);
					$ls_contenido=str_replace("\$ls_funciones",$ls_funcion,$ls_contenido);
					$ls_contenido=str_replace("\$ls_observacion",$ls_obs,$ls_contenido);
					$ls_contenido=str_replace("\$ls_tipo_contrato",$ls_dentipcon,$ls_contenido);
					$ls_contenido=str_replace("\$ls_nroreg",$ls_nroreg,$ls_contenido);
					$ls_contenido=str_replace("\$li_monto_contrato",$ls_monto,$ls_contenido);
					
					$io_pdf->ezText($ls_contenido,$li_tamletcont,array('justification' =>'full','spacing' =>$li_intlincont));
					$li_pos=($li_marinfcont*10)*(72/25.4);
										
					$li_texto=$io_pdf->addTextWrap(50,$li_pos,500,$li_tamletpiecont,$ls_piepagcont,'center');
					$li_pos=$li_pos-$li_tamletpiecont;
					$li_texto=$io_pdf->addTextWrap(50,$li_pos,500,$li_tamletpiecont,$li_texto,'center');
					$li_pos=$li_pos-$li_tamletpiecont;
					$io_pdf->addTextWrap(50,$li_pos,600,$li_tamletpiecont,$li_texto,'center');
					
					if($li_s<$li_totrow_det)
					{					
						$io_pdf->ezNewPage(); // Insertar una nueva página
					}
			
				}
				$io_report->DS->resetds("codper");
			}
		}
		$io_report->DS->resetds("codcont");
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		unset($io_pdf);
	}
	unset($io_report);

?> 
	
	