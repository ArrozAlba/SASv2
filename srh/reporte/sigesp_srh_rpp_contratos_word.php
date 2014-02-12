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

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 01/07/2008 		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$lb_valido=true;
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SRH","sigesp_srh_r_contratos.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_leer_archivo($as_archivo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_leer_archivo
		//		   Access: private 
		//	    Arguments: as_archivo //  ruta donde se encuentra el archivo
		//    Description: función que lee un archivo de texto y lo mete en una cadena
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 08/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_texto = file($as_archivo);
		$li_tamano = sizeof($ls_texto);
		$ls_textocompleto="";
		for($li_i=0;$li_i<$li_tamano;$li_i++)
		{
			$ls_textocompleto=$ls_textocompleto.$ls_texto[$li_i];
		}
		return $ls_textocompleto;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("class_folder/sigesp_srh_class_report.php");
	$io_report  = new sigesp_srh_class_report('../../');
	require_once("../class_folder/utilidades/class_funciones_srh.php");
	$io_fun_srh=new class_funciones_srh("../../");
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
	//imprime numero con los cambios
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../../sno/class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codcont=$io_fun_srh->uf_obtenervalor_get("codcont","");
	$ls_nroregdes=$io_fun_srh->uf_obtenervalor_get("nroregdes","");
	$ls_nroreghas=$io_fun_srh->uf_obtenervalor_get("nroreghas","");
	$ls_mesactual=$io_fun_nomina->uf_obtenervalor_get("mesactual","");
	$ls_anocurnom=$io_fun_nomina->uf_obtenervalor_get("anocurnom","");
	$li_mesanterior=(intval($ls_mesactual)-1);
	if($li_mesanterior==0)
	{
		$li_mesanterior=12;
		$ls_anocurnom=(intval($ls_anocurnom)-1);
	}
	$ls_mesanterior=str_pad($li_mesanterior,2,"0",0);
	global $ls_tiporeporte;
	$ls_prefijo="Bs.";
	
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad("CONTRATO EN WORD"); // Seguridad de Reporte
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
			$ls_original=trim($io_report->DS->data["arcrtfcont"][$li_i]);
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
			//uf_print_encabezado_pagina($ls_titcont,$io_pdf); // Imprimimos el encabezado de la página
			$lb_valido=$io_report->uf_select_contratos_personal($ls_nroregdes,$ls_nroreghas, $rs_data); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				 $li_s=0;
				 $li_totrow_det=$io_report->io_sql->num_rows($rs_data);
				 
				 $ls_archivo="../documentos/original/".$ls_original;
				 $ls_copia=substr($ls_original,0,strrpos($ls_original,"."));
				 $ls_salida="../documentos/copia/".$ls_copia."-".$_SESSION["la_logusr"].".rtf";
				 $ls_contenido="";
				 $ls_contenido=uf_leer_archivo($ls_archivo);
				 $la_matriz=explode("sectd",$ls_contenido);
				 $ls_cabecera=$la_matriz[0]."sectd";
				 $li_inicio=strlen($ls_cabecera);
				 $li_final=strrpos($ls_contenido,"}");
				 $li_longitud=$li_final-$li_inicio;
				 $ls_nuevocuerpo=substr($ls_contenido,$li_inicio,$li_longitud);
				 $ls_punt=fopen($ls_salida,"w");
				 fputs($ls_punt,$ls_cabecera);
				 
				while ($row=$io_report->io_sql->fetch_row($rs_data))
				{
				    $li_s=$li_s+1;
					$ls_contenido="";
					$ls_contenido=$ls_concont;

					$ls_horario=trim ($row["horario"]);
					$ls_funcion=trim ($row["funcion"]);
					$ls_obs=trim ($row["observacion"]);		
					$ls_dentipcon= strtoupper (trim ($row["dentipcon"]));		
					$ls_nroreg=$row["nroreg"];
					
					$ls_profesion= strtoupper ($row["despro"]);
					
					$ls_descripcion=trim ($row["descripcion"]);
									
					$ls_cedper= trim ($row["codper"]);
					$ls_apeper= strtoupper (trim ($row["apeper"]));		
					$ls_nomper= strtoupper (trim ($row["nomper"]));	
							
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
						
					$ls_desuniadm= strtoupper ($row["desuniadm"]);	
					
					
					
					
					$li_monto=$io_fun_nomina->uf_formatonumerico($row["monto"]);		
					$io_numero_letra->setNumero($row["monto"]);
					$ls_monto=$io_numero_letra->letra();
					$ls_monto=$ls_monto." (".$ls_prefijo." ".$li_monto.")";
				
					$ls_cuerpo=$ls_nuevocuerpo;
					$ls_cuerpo=str_replace("\$ls_dia",$ls_dia_act,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_mes",$ls_mes_act,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_ano",$ls_ano_act,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_nombres",$ls_nomper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_apellidos",$ls_apeper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_cedula",$ls_cedper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_cargo",$ls_descar,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_profesion",$ls_profesion,$ls_cuerpo);					
					$ls_cuerpo=str_replace("\$ld_fecha_inicio",$ls_fechainicio,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ld_fecha_culminacion",$ls_fechafinal,$ls_cuerpo);				
					$ls_cuerpo=str_replace("\$ls_nacionalidad",$ls_nacper,$ls_cuerpo);					
					$ls_cuerpo=str_replace("\$ls_unidad_administrativa",$ls_desuniadm,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_horario",$ls_horario,$ls_cuerpo);				
					$ls_cuerpo=str_replace("\$ls_descripcion",$ls_descripcion,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_funciones",$ls_funcion,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_observacion",$ls_obs,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_tipo_contrato",$ls_dentipcon,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_nroreg",$ls_nroreg,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$li_monto_contrato",$ls_monto,$ls_cuerpo);				
					fputs($ls_punt,$ls_cuerpo);
					if($li_s<$li_totrow_det)
					{
						$ls_salto="\par \page \par";
						fputs($ls_punt,$ls_salto);
					}
				}
				$io_report->DS->resetds("codper");
				fputs($ls_punt,"}");
				fclose($ls_punt);
				@chmod($ls_salida,0755);
			}
		}
		$io_report->DS->resetds("codcont");
		if($lb_valido) // Si no ocurrio ningún error
		{
			header ("Content-Disposition: attachment; filename=".$ls_copia."-".$_SESSION["la_logusr"].".rtf\n\n");
			header ("Content-Type: application/octet-stream");
			header ("Content-Length: ".filesize($ls_salida));
			readfile($ls_salida);
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
	unset($io_funciones);
	unset($io_fun_nomina);
?> 