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
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$lb_valido=true;
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_constanciatrabajo.php",$ls_descripcion);
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
		//	   Creado Por: Ing. Yesenia Moreno
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
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
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
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codcont=$io_fun_nomina->uf_obtenervalor_get("codcont","");
	$ls_codnom=$io_fun_nomina->uf_obtenervalor_get("codnom","");
	$li_rac=$io_fun_nomina->uf_obtenervalor_get("rac","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_fecha=$io_fun_nomina->uf_obtenervalor_get("fecha","");
	$ls_tiporeporte=$io_fun_nomina->uf_obtenervalor_get("tiporeporte",0);
	$ls_mesactual=$io_fun_nomina->uf_obtenervalor_get("mesactual","");
	$ls_anocurnom=$io_fun_nomina->uf_obtenervalor_get("anocurnom","");
	
	$ls_parametros=$io_fun_nomina->uf_obtenervalor_get("parametro","");
	$arr_codper=split("-",$ls_parametros); 
	$li_totcodper=count($arr_codper);
	
	
	$li_mesanterior=(intval($ls_mesactual)-1);
	if($li_mesanterior==0)
	{
		$li_mesanterior=12;
		$ls_anocurnom=(intval($ls_anocurnom)-1);
	}
	$ls_mesanterior=str_pad($li_mesanterior,2,"0",0);
	global $ls_tiporeporte;
	
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad("CONSTANCIA En Word"); // Seguridad de Reporte
	if($lb_valido)
	{
		if ($li_totcodper==1)
		{
			$lb_valido=$io_report->uf_constanciatrabajo_constancia($ls_codcont,$ls_codnom,$ls_codperdes,$ls_codperhas); // Obtenemos el detalle del reporte
			if ($lb_valido)
			{
				$li_totrow=$io_report->DS->getRowCount("codcont"); 
			}
		}
		else
		{
			$lb_valido=$io_report->uf_constanciatrabajo_constancia_lote($ls_codcont,$ls_codnom,
			                                                            $ls_codperdes,$ls_codperhas,$arr_codper,$li_totcodper);
			if ($lb_valido)
			{
				$li_totrow=count($io_report->DS->data);
			}
		}
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
	   if ($li_totcodper==1)
	   {
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_ente=$_SESSION["la_empresa"]["nombre"];
			$ld_fecha=date("d/m/Y");
			$ls_dia_act=substr($ld_fecha,0,2);
			$ls_mes_act=$io_fecha->uf_load_nombre_mes(substr($ld_fecha,3,2));
			$ls_ano_act=substr($ld_fecha,6,4);			
			$ls_original=$io_report->DS->data["arcrtfcont"][$li_i];			
			$lb_valido=$io_report->uf_constanciatrabajo_personal($ls_codnom,$li_rac,$ls_codperdes,$ls_codperhas); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->DS_detalle->getRowCount("codper");
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
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_codper=$io_report->DS_detalle->data["codper"][$li_s];
					$ls_cedper=$io_report->DS_detalle->data["cedper"][$li_s];
					$ls_apeper=$io_report->DS_detalle->data["apeper"][$li_s];		
					$ls_nomper=$io_report->DS_detalle->data["nomper"][$li_s];		
					$ls_descar=$io_report->DS_detalle->data["descar"][$li_s];		
					$ld_fecingper=$io_report->DS_detalle->data["fecingper"][$li_s];
					$ls_mes=$io_fecha->uf_load_nombre_mes(substr($ld_fecingper,5,2));
					$ls_fechaingreso="el ".substr($ld_fecingper,8,2)." de ".$ls_mes." de ".substr($ld_fecingper,0,4);
					$ld_fecegrper=$io_report->DS_detalle->data["fecegrper"][$li_s];
					$ls_mes=$io_fecha->uf_load_nombre_mes(substr($ld_fecegrper,5,2));
					$ls_fechaegreso="el ".substr($ld_fecegrper,8,2)." de ".$ls_mes." de ".substr($ld_fecegrper,0,4);
					$ls_dirper=$io_report->DS_detalle->data["dirper"][$li_s];		
					$ld_fecnacper=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["fecnacper"][$li_s]);		
					$ls_edocivper=$io_report->DS_detalle->data["edocivper"][$li_s];	
					switch($ls_edocivper)
					{
						case "S": // Soltero
							$ls_edocivper="Soltero";
							break;
						case "C": // Casado
							$ls_edocivper="Casado";
							break;
						case "D": // Divociado
							$ls_edocivper="Divociado";
							break;
						case "V": // Viudo
							$ls_edocivper="Viudo";
							break;
					}
					$ls_nacper=$io_report->DS_detalle->data["nacper"][$li_s];
					switch($ls_nacper)
					{
						case "V": // Venezolano
							$ls_nacper="Venezolano";
							break;
						case "E": // Extranjero
							$ls_nacper="Extranjero";
							break;
					}
					$ls_tipnom=$io_report->DS_detalle->data["tipnom"][$li_s];	
					switch($ls_tipnom)
					{
						case "1": // Empleado Fijo
							$ls_tipnom="Empleado Fijo";
							break;
						case "2": // Empleado Contratado
							$ls_tipnom="Empleado Contratado";
							break;
						case "3": // Obrero Fijo
							$ls_tipnom="Obrero Fijo";
							break;
						case "4": // Obrero Contratado
							$ls_tipnom="Obrero Contratado";
							break;
						case "5": // Docente Fijo
							$ls_tipnom="Docente Fijo";
							break;
						case "6": // Docente Contratado
							$ls_tipnom="Docente Contratado";
							break;
						case "7": // Jubilado
							$ls_tipnom="Jubilado";
							break;
						case "8": // Comision de Servicios
							$ls_tipnom="Comision de Servicios";
							break;
						case "9": // Libre Nombramiento
							$ls_tipnom="Libre Nombramiento";
							break;
						case "10": // Militar
							$ls_tipnom="Militar";
							break;
						case "11": // Honorarios Profesionales
							$ls_tipnom="Honorarios Profesionales";
							break;
						case "12": // Pensionado
							$ls_tipnom="Pensionado";
							break;
						case "13": // Suplente
							$ls_tipnom="Suplente";
							break;
						case "14": // Contratado
							$ls_tipnom="Contratado";
							break;
						case "15": // Incapacitados
							$ls_tipnom="Incapacitados";
							break;
					}
					if($ls_tiporeporte==1)
					{
						$ls_prefijo="Bs.F.";
					}
					else
					{
						$ls_prefijo="Bs.";
					}
					$ls_telhabper=$io_report->DS_detalle->data["telhabper"][$li_s];	
					$ls_telmovper=$io_report->DS_detalle->data["telmovper"][$li_s];	
					$ls_desuniadm=$io_report->DS_detalle->data["desuniadm"][$li_s];	
					$li_horper=$io_fun_nomina->uf_formatonumerico($io_report->DS_detalle->data["horper"][$li_s]);	
					$li_sueper=$io_fun_nomina->uf_formatonumerico($io_report->DS_detalle->data["sueper"][$li_s]);		
					$io_numero_letra->setNumero($io_report->DS_detalle->data["sueper"][$li_s]);
					$ls_sueper=$io_numero_letra->letra();
					$ls_sueper=$ls_sueper." (".$ls_prefijo." ".$li_sueper.")";
					$li_sueintper=$io_fun_nomina->uf_formatonumerico($io_report->DS_detalle->data["sueintper"][$li_s]);	
					$io_numero_letra->setNumero($io_report->DS_detalle->data["sueintper"][$li_s]);
					$ls_sueintper=$io_numero_letra->letra();
					$ls_sueintper=$ls_sueintper." (".$ls_prefijo." ".$li_sueintper.")";
					$li_sueproper=$io_fun_nomina->uf_formatonumerico($io_report->DS_detalle->data["sueproper"][$li_s]);	
					$io_numero_letra->setNumero($io_report->DS_detalle->data["sueproper"][$li_s]);
					$ls_sueproper=$io_numero_letra->letra();
					$ls_sueproper=$ls_sueproper." (".$ls_prefijo." ".$li_sueproper.")";
					$ls_desded=$io_report->DS_detalle->data["desded"][$li_s];	
					$ls_destipper=$io_report->DS_detalle->data["destipper"][$li_s];	
					$ls_fecjub=$io_report->DS_detalle->data["fecjubper"][$li_s];
					$ls_mes2=$io_fecha->uf_load_nombre_mes(substr($ls_fecjub,5,2));
					$ls_fecjub="el ".substr($ls_fecjub,8,2)." de ".$ls_mes2." de ".substr($ls_fecjub,0,4);	
					$li_sueintper_mensual=0;
					$li_sueproper_mensual=0;
					$lb_valido=$io_report->uf_constanciatrabajo_integralpromedio_mensual($ls_codnom,$ls_codper,$ls_mesanterior,$ls_anocurnom,$li_sueintper_mensual,
																						 $li_sueproper_mensual); // Obtenemos el detalle del reporte
					$io_numero_letra->setNumero($li_sueintper_mensual);
					$ls_sueintper_mensual=$io_numero_letra->letra();
					$li_sueintper_mensual=$io_fun_nomina->uf_formatonumerico($li_sueintper_mensual);
					$ls_sueintper_mensual=$ls_sueintper_mensual." (".$ls_prefijo." ".$li_sueintper_mensual.")";
					$io_numero_letra->setNumero($li_sueproper_mensual);
					$ls_sueproper_mensual=$io_numero_letra->letra();
					$li_sueproper_mensual=$io_fun_nomina->uf_formatonumerico($li_sueproper_mensual);
					$ls_sueproper_mensual=$ls_sueproper_mensual." (".$ls_prefijo." ".$li_sueproper_mensual.")";
					
					$li_salnorper=$io_fun_nomina->uf_formatonumerico($io_report->DS_detalle->data["salnorper"][$li_s]);	
					$io_numero_letra->setNumero($io_report->DS_detalle->data["salnorper"][$li_s]);
					$ls_salnorper=$io_numero_letra->letra();
					$ls_salnorper=$ls_salnorper." (".$ls_prefijo." ".$li_salnorper.")";
					$ls_gerencia=$io_report->DS_detalle->data["denger"][$li_s];
					$ls_cuerpo=$ls_nuevocuerpo;
					$ls_cuerpo=str_replace("\$ls_ente",$ls_ente,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_dia",$ls_dia_act,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_mes",$ls_mes_act,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_ano",$ls_ano_act,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_nombres",$ls_nomper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_apellidos",$ls_apeper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_cedula",$ls_cedper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_cargo",$ls_descar,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$li_sueldo",$ls_sueper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ld_fecha_ingreso",$ls_fechaingreso,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ld_fecha_egreso",$ls_fechaegreso,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_direccion",$ls_dirper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ld_fecha_nacimiento",$ld_fecnacper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_edo_civil",$ls_edocivper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_nacionalidad",$ls_nacper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_telefono_hab",$ls_telhabper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_telefono_mov",$ls_telmovper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_unidad_administrativa",$ls_desuniadm,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$li_horas_lab",$li_horper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$li_inte_sueldo",$ls_sueintper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$li_salario_normal",$ls_salnorper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$li_prom_sueldo",$ls_sueproper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_dedicacion",$ls_desded,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_tipo_personal",$ls_destipper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_tipo_nomina",$ls_tipnom,$ls_cuerpo);		
					$ls_cuerpo=str_replace("\$li_mensual_inte_sueldo",$ls_sueintper_mensual,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$li_mensual_prom_sueldo",$ls_sueproper_mensual,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_fecjub",$ls_fecjub,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_gerencia",$ls_gerencia,$ls_cuerpo);
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
		}
		else
		{ 
		  for($li_i=0;(($li_i<$li_totrow)&&($lb_valido));$li_i++)
		  { 
			$ls_ente=$_SESSION["la_empresa"]["nombre"];
			$ld_fecha=date("d/m/Y");
			$ls_dia_act=substr($ld_fecha,0,2);
			$ls_mes_act=$io_fecha->uf_load_nombre_mes(substr($ld_fecha,3,2));
			$ls_ano_act=substr($ld_fecha,6,4);			
			$ls_original=$io_report->DS->data[$li_i]["arcrtfcont"];			
			$lb_valido=$io_report->uf_constanciatrabajo_personal_lote($ls_codnom,$li_rac,$arr_codper,$li_totcodper); // Obtenemos el detalle del reporte			
			if($lb_valido)
			{
				$li_totrow_det=$io_report->DS_detalle->getRowCount("codper");
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
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_codper=$io_report->DS_detalle->data["codper"][$li_s];
					$ls_cedper=$io_report->DS_detalle->data["cedper"][$li_s];
					$ls_apeper=$io_report->DS_detalle->data["apeper"][$li_s];		
					$ls_nomper=$io_report->DS_detalle->data["nomper"][$li_s];		
					$ls_descar=$io_report->DS_detalle->data["descar"][$li_s];		
					$ld_fecingper=$io_report->DS_detalle->data["fecingper"][$li_s];
					$ls_mes=$io_fecha->uf_load_nombre_mes(substr($ld_fecingper,5,2));
					$ls_fechaingreso="el ".substr($ld_fecingper,8,2)." de ".$ls_mes." de ".substr($ld_fecingper,0,4);
					$ld_fecegrper=$io_report->DS_detalle->data["fecegrper"][$li_s];
					$ls_mes=$io_fecha->uf_load_nombre_mes(substr($ld_fecegrper,5,2));
					$ls_fechaegreso="el ".substr($ld_fecegrper,8,2)." de ".$ls_mes." de ".substr($ld_fecegrper,0,4);
					$ls_dirper=$io_report->DS_detalle->data["dirper"][$li_s];		
					$ld_fecnacper=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["fecnacper"][$li_s]);		
					$ls_edocivper=$io_report->DS_detalle->data["edocivper"][$li_s];	
					switch($ls_edocivper)
					{
						case "S": // Soltero
							$ls_edocivper="Soltero";
							break;
						case "C": // Casado
							$ls_edocivper="Casado";
							break;
						case "D": // Divociado
							$ls_edocivper="Divociado";
							break;
						case "V": // Viudo
							$ls_edocivper="Viudo";
							break;
					}
					$ls_nacper=$io_report->DS_detalle->data["nacper"][$li_s];
					switch($ls_nacper)
					{
						case "V": // Venezolano
							$ls_nacper="Venezolano";
							break;
						case "E": // Extranjero
							$ls_nacper="Extranjero";
							break;
					}
					$ls_tipnom=$io_report->DS_detalle->data["tipnom"][$li_s];	
					switch($ls_tipnom)
					{
						case "1": // Empleado Fijo
							$ls_tipnom="Empleado Fijo";
							break;
						case "2": // Empleado Contratado
							$ls_tipnom="Empleado Contratado";
							break;
						case "3": // Obrero Fijo
							$ls_tipnom="Obrero Fijo";
							break;
						case "4": // Obrero Contratado
							$ls_tipnom="Obrero Contratado";
							break;
						case "5": // Docente Fijo
							$ls_tipnom="Docente Fijo";
							break;
						case "6": // Docente Contratado
							$ls_tipnom="Docente Contratado";
							break;
						case "7": // Jubilado
							$ls_tipnom="Jubilado";
							break;
						case "8": // Comision de Servicios
							$ls_tipnom="Comision de Servicios";
							break;
						case "9": // Libre Nombramiento
							$ls_tipnom="Libre Nombramiento";
							break;
					}
					if($ls_tiporeporte==1)
					{
						$ls_prefijo="Bs.F.";
					}
					else
					{
						$ls_prefijo="Bs.";
					}
					$ls_telhabper=$io_report->DS_detalle->data["telhabper"][$li_s];	
					$ls_telmovper=$io_report->DS_detalle->data["telmovper"][$li_s];	
					$ls_desuniadm=$io_report->DS_detalle->data["desuniadm"][$li_s];	
					$li_horper=$io_fun_nomina->uf_formatonumerico($io_report->DS_detalle->data["horper"][$li_s]);	
					$li_sueper=$io_fun_nomina->uf_formatonumerico($io_report->DS_detalle->data["sueper"][$li_s]);		
					$io_numero_letra->setNumero($io_report->DS_detalle->data["sueper"][$li_s]);
					$ls_sueper=$io_numero_letra->letra();
					//$ls_sueper=$ls_sueper." (".$ls_prefijo." ".$li_sueper.")";
					$li_sueintper=$io_fun_nomina->uf_formatonumerico($io_report->DS_detalle->data["sueintper"][$li_s]);	
					$io_numero_letra->setNumero($io_report->DS_detalle->data["sueintper"][$li_s]);
					$ls_sueintper=$io_numero_letra->letra();
					//$ls_sueintper=$ls_sueintper." (".$ls_prefijo." ".$li_sueintper.")";
					$li_sueproper=$io_fun_nomina->uf_formatonumerico($io_report->DS_detalle->data["sueproper"][$li_s]);	
					$io_numero_letra->setNumero($io_report->DS_detalle->data["sueproper"][$li_s]);
					$ls_sueproper=$io_numero_letra->letra();
					//$ls_sueproper=$ls_sueproper." (".$ls_prefijo." ".$li_sueproper.")";
					$ls_desded=$io_report->DS_detalle->data["desded"][$li_s];	
					$ls_destipper=$io_report->DS_detalle->data["destipper"][$li_s];
					$ls_fecjub=$io_report->DS_detalle->data["fecjubper"][$li_s];
					$ls_mes2=$io_fecha->uf_load_nombre_mes(substr($ls_fecjub,5,2));
					$ls_fecjub="el ".substr($ls_fecjub,8,2)." de ".$ls_mes2." de ".substr($ls_fecjub,0,4);	
					$ls_gerencia=$io_report->DS_detalle->data["denger"][$li_s];
					$li_sueintper_mensual=0;
					$li_sueproper_mensual=0;
					$lb_valido=$io_report->uf_constanciatrabajo_integralpromedio_mensual($ls_codnom,$ls_codper,$ls_mesanterior,$ls_anocurnom,$li_sueintper_mensual,
																						 $li_sueproper_mensual); // Obtenemos el detalle del reporte
					$io_numero_letra->setNumero($li_sueintper_mensual);
					$ls_sueintper_mensual=$io_numero_letra->letra();
					$li_sueintper_mensual=$io_fun_nomina->uf_formatonumerico($li_sueintper_mensual);
					//$ls_sueintper_mensual=$ls_sueintper_mensual." (".$ls_prefijo." ".$li_sueintper_mensual.")";
					$io_numero_letra->setNumero($li_sueproper_mensual);
					$ls_sueproper_mensual=$io_numero_letra->letra();
					$li_sueproper_mensual=$io_fun_nomina->uf_formatonumerico($li_sueproper_mensual);
					//$ls_sueproper_mensual=$ls_sueproper_mensual." (".$ls_prefijo." ".$li_sueproper_mensual.")";
					
					$li_salnorper=$io_fun_nomina->uf_formatonumerico($io_report->DS_detalle->data["salnorper"][$li_s]);	
					$io_numero_letra->setNumero($io_report->DS_detalle->data["salnorper"][$li_s]);
					$ls_salnorper=$io_numero_letra->letra();
					$ls_salnorper=$ls_salnorper." (".$ls_prefijo." ".$li_salnorper.")";

					$ls_cuerpo=$ls_nuevocuerpo;
					$ls_cuerpo=str_replace("\$ls_ente",$ls_ente,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_dia",$ls_dia_act,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_mes",$ls_mes_act,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_ano",$ls_ano_act,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_nombres",$ls_nomper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_apellidos",$ls_apeper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_cedula",$ls_cedper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_cargo",$ls_descar,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$li_sueldo",$ls_sueper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ld_fecha_ingreso",$ls_fechaingreso,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ld_fecha_egreso",$ls_fechaegreso,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_direccion",$ls_dirper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ld_fecha_nacimiento",$ld_fecnacper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_edo_civil",$ls_edocivper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_nacionalidad",$ls_nacper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_telefono_hab",$ls_telhabper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_telefono_mov",$ls_telmovper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_unidad_administrativa",$ls_desuniadm,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$li_horas_lab",$li_horper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$li_inte_sueldo",$ls_sueintper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$li_prom_sueldo",$ls_sueproper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_dedicacion",$ls_desded,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_tipo_personal",$ls_destipper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_tipo_nomina",$ls_tipnom,$ls_cuerpo);		
					$ls_cuerpo=str_replace("\$li_mensual_inte_sueldo",$ls_sueintper_mensual,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$li_mensual_prom_sueldo",$ls_sueproper_mensual,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_fecjub",$ls_fecjub,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$li_salario_normal",$ls_salnorper,$ls_cuerpo);
					$ls_cuerpo=str_replace("\$ls_gerencia",$ls_gerencia,$ls_cuerpo);
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