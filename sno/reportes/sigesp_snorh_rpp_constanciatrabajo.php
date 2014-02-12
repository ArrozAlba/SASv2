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
	function uf_print_encabezado_pagina($as_titulo,$as_fecha,&$io_pdf)
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
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,680,13,$as_titulo); // Agregar el título
		if($as_fecha=="1")
		{
			$io_pdf->addText(512,750,8,date("d/m/Y")); // Agregar la Fecha
			$io_pdf->addText(518,743,7,date("h:i a")); // Agregar la Hora
		}
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
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
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<i>CONSTANCIA</i>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codcont=$io_fun_nomina->uf_obtenervalor_get("codcont","");
	$ls_codnom=$io_fun_nomina->uf_obtenervalor_get("codnom","");
	$li_rac=$io_fun_nomina->uf_obtenervalor_get("rac","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_fecha=$io_fun_nomina->uf_obtenervalor_get("fecha","");
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
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte	
	if ($li_totcodper==1)
	{  
		if($lb_valido)
		{
			$lb_valido=$io_report->uf_constanciatrabajo_constancia($ls_codcont,$ls_codnom,$ls_codperdes,$ls_codperhas); // Obtenemos el detalle del reporte
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
				uf_print_encabezado_pagina($ls_titcont,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la página
				$lb_valido=$io_report->uf_constanciatrabajo_personal($ls_codnom,$li_rac,$ls_codperdes,$ls_codperhas); // Obtenemos el detalle del reporte
				if($lb_valido)
				{
					$li_totrow_det=$io_report->DS_detalle->getRowCount("codper"); 
					for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
					{
						$ls_contenido="";
						$ls_contenido=$ls_concont;
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
						$ls_sueper=$ls_sueper." (".$ls_prefijo." ".$li_sueper.")";
						
						$li_sueintper=$io_fun_nomina->uf_formatonumerico($io_report->DS_detalle->data["sueintper"][$li_s]);	
						$io_numero_letra->setNumero($io_report->DS_detalle->data["sueintper"][$li_s]);
						$ls_sueintper=$io_numero_letra->letra();
						$ls_sueintper=$ls_sueintper." (".$ls_prefijo." ".$li_sueintper.")";
						
						$li_salnorper=$io_fun_nomina->uf_formatonumerico($io_report->DS_detalle->data["salnorper"][$li_s]);	
						$io_numero_letra->setNumero($io_report->DS_detalle->data["salnorper"][$li_s]);
						$ls_salnorper=$io_numero_letra->letra();
						$ls_salnorper=$ls_salnorper." (".$ls_prefijo." ".$li_salnorper.")";
						
						
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
						
						$ls_gerencia=$io_report->DS_detalle->data["denger"][$li_s];
						
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
						$ls_contenido=str_replace("\$ls_ente",$ls_ente,$ls_contenido);
						$ls_contenido=str_replace("\$ls_dia",$ls_dia_act,$ls_contenido);
						$ls_contenido=str_replace("\$ls_mes",$ls_mes_act,$ls_contenido);
						$ls_contenido=str_replace("\$ls_ano",$ls_ano_act,$ls_contenido);
						$ls_contenido=str_replace("\$ls_nombres",$ls_nomper,$ls_contenido);
						$ls_contenido=str_replace("\$ls_apellidos",$ls_apeper,$ls_contenido);
						$ls_contenido=str_replace("\$ls_cedula",$ls_cedper,$ls_contenido);
						$ls_contenido=str_replace("\$ls_cargo",$ls_descar,$ls_contenido);
						$ls_contenido=str_replace("\$li_sueldo",$ls_sueper,$ls_contenido);
						$ls_contenido=str_replace("\$ld_fecha_ingreso",$ls_fechaingreso,$ls_contenido);
						$ls_contenido=str_replace("\$ld_fecha_egreso",$ls_fechaegreso,$ls_contenido);
						$ls_contenido=str_replace("\$ls_direccion",$ls_dirper,$ls_contenido);
						$ls_contenido=str_replace("\$ld_fecha_nacimiento",$ld_fecnacper,$ls_contenido);
						$ls_contenido=str_replace("\$ls_edo_civil",$ls_edocivper,$ls_contenido);
						$ls_contenido=str_replace("\$ls_nacionalidad",$ls_nacper,$ls_contenido);
						$ls_contenido=str_replace("\$ls_telefono_hab",$ls_telhabper,$ls_contenido);
						$ls_contenido=str_replace("\$ls_telefono_mov",$ls_telmovper,$ls_contenido);
						$ls_contenido=str_replace("\$ls_unidad_administrativa",$ls_desuniadm,$ls_contenido);
						$ls_contenido=str_replace("\$li_horas_lab",$li_horper,$ls_contenido);
						$ls_contenido=str_replace("\$li_inte_sueldo",$ls_sueintper,$ls_contenido);
						$ls_contenido=str_replace("\$li_salario_normal",$ls_salnorper,$ls_contenido);						
						$ls_contenido=str_replace("\$li_prom_sueldo",$ls_sueproper,$ls_contenido);
						$ls_contenido=str_replace("\$ls_dedicacion",$ls_desded,$ls_contenido);
						$ls_contenido=str_replace("\$ls_tipo_personal",$ls_destipper,$ls_contenido);
						$ls_contenido=str_replace("\$ls_tipo_nomina",$ls_tipnom,$ls_contenido);
						$ls_contenido=str_replace("\$li_mensual_inte_sueldo",$ls_sueintper_mensual,$ls_contenido);
						$ls_contenido=str_replace("\$li_mensual_prom_sueldo",$ls_sueproper_mensual,$ls_contenido);
						$ls_contenido=str_replace("\$ls_fecjub",$ls_fecjub,$ls_contenido);
						$ls_contenido=str_replace("\$ls_gerencia",$ls_gerencia,$ls_contenido);
						
						$io_pdf->ezText($ls_contenido,$li_tamletcont,array('justification' =>'full','spacing' =>$li_intlincont));
						$li_pos=($li_marinfcont*10)*(72/25.4);
											
						$li_texto=$io_pdf->addTextWrap(50,$li_pos,500,$li_tamletpiecont,$ls_piepagcont,'center');
						$li_pos=$li_pos-$li_tamletpiecont;
						$li_texto=$io_pdf->addTextWrap(50,$li_pos,500,$li_tamletpiecont,$li_texto,'center');
						$li_pos=$li_pos-$li_tamletpiecont;
						$io_pdf->addTextWrap(50,$li_pos,500,$li_tamletpiecont,$li_texto,'center');
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
				//print(" close();");
				print("</script>");		
			}
			unset($io_pdf);
		}
		unset($io_report);
		unset($io_funciones);
		unset($io_fun_nomina);
	}
	else
	{
	    $li_total=count($arr_codper);
		$codperdes1="";			
		$codperhas1="";	
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra				  
		for ($i=1;$i<$li_total;$i++) 
		{   
		    $codperdes1=$arr_codper[$i]; 		
			$codperhas1=$arr_codper[$i];
			$lb_valido=$io_report->uf_constanciatrabajo_constancia($ls_codcont,$ls_codnom,$codperdes1,$codperhas1);
			if($lb_valido==false) // Existe algún error ó no hay registros
			{
				print("<script language=JavaScript>");
				print(" alert('No hay nada que Reportar');"); 
				print(" close();");
				print("</script>");
			}
			else
			{
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
					uf_print_encabezado_pagina($ls_titcont,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la página
					$lb_valido=$io_report->uf_constanciatrabajo_personal($ls_codnom,$li_rac,$codperdes1,$codperhas1); // Obtenemos el detalle del reporte
					if($lb_valido)
					{
						$li_totrow_det=$io_report->DS_detalle->getRowCount("codper");
						for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
						{
							$ls_contenido="";
							$ls_contenido=$ls_concont;
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
						
							$li_salnorper=$io_fun_nomina->uf_formatonumerico($io_report->DS_detalle->data["salnorper"][$li_s]);	
							$io_numero_letra->setNumero($io_report->DS_detalle->data["salnorper"][$li_s]);
							$ls_salnorper=$io_numero_letra->letra();
							
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
							$ls_contenido=str_replace("\$ls_ente",$ls_ente,$ls_contenido);
							$ls_contenido=str_replace("\$ls_dia",$ls_dia_act,$ls_contenido);
							$ls_contenido=str_replace("\$ls_mes",$ls_mes_act,$ls_contenido);
							$ls_contenido=str_replace("\$ls_ano",$ls_ano_act,$ls_contenido);
							$ls_contenido=str_replace("\$ls_nombres",$ls_nomper,$ls_contenido);
							$ls_contenido=str_replace("\$ls_apellidos",$ls_apeper,$ls_contenido);
							$ls_contenido=str_replace("\$ls_cedula",$ls_cedper,$ls_contenido);
							$ls_contenido=str_replace("\$ls_cargo",$ls_descar,$ls_contenido);
							$ls_contenido=str_replace("\$li_sueldo",$ls_sueper,$ls_contenido);
							$ls_contenido=str_replace("\$ld_fecha_ingreso",$ls_fechaingreso,$ls_contenido);
							$ls_contenido=str_replace("\$ld_fecha_egreso",$ls_fechaegreso,$ls_contenido);
							$ls_contenido=str_replace("\$ls_direccion",$ls_dirper,$ls_contenido);
							$ls_contenido=str_replace("\$ld_fecha_nacimiento",$ld_fecnacper,$ls_contenido);
							$ls_contenido=str_replace("\$ls_edo_civil",$ls_edocivper,$ls_contenido);
							$ls_contenido=str_replace("\$ls_nacionalidad",$ls_nacper,$ls_contenido);
							$ls_contenido=str_replace("\$ls_telefono_hab",$ls_telhabper,$ls_contenido);
							$ls_contenido=str_replace("\$ls_telefono_mov",$ls_telmovper,$ls_contenido);
							$ls_contenido=str_replace("\$ls_unidad_administrativa",$ls_desuniadm,$ls_contenido);
							$ls_contenido=str_replace("\$li_horas_lab",$li_horper,$ls_contenido);
							$ls_contenido=str_replace("\$li_inte_sueldo",$ls_sueintper,$ls_contenido);							
							$ls_contenido=str_replace("\$li_salario_normal",$ls_salnorper,$ls_contenido);							
							$ls_contenido=str_replace("\$li_prom_sueldo",$ls_sueproper,$ls_contenido);
							$ls_contenido=str_replace("\$ls_dedicacion",$ls_desded,$ls_contenido);
							$ls_contenido=str_replace("\$ls_tipo_personal",$ls_destipper,$ls_contenido);
							$ls_contenido=str_replace("\$ls_tipo_nomina",$ls_tipnom,$ls_contenido);
							$ls_contenido=str_replace("\$li_mensual_inte_sueldo",$ls_sueintper_mensual,$ls_contenido);
							$ls_contenido=str_replace("\$li_mensual_prom_sueldo",$ls_sueproper_mensual,$ls_contenido);
							$ls_contenido=str_replace("\$ls_fecjub",$ls_fecjub,$ls_contenido);
							$ls_contenido=str_replace("\$ls_gerencia",$ls_gerencia,$ls_contenido);
							
							$io_pdf->ezText($ls_contenido,$li_tamletcont,array('justification' =>'full','spacing' =>$li_intlincont));
							$li_pos=($li_marinfcont*10)*(72/25.4);
												
							$li_texto=$io_pdf->addTextWrap(50,$li_pos,500,$li_tamletpiecont,$ls_piepagcont,'center');
							$li_pos=$li_pos-$li_tamletpiecont;
							$li_texto=$io_pdf->addTextWrap(50,$li_pos,500,$li_tamletpiecont,$li_texto,'center');
							$li_pos=$li_pos-$li_tamletpiecont;
							$io_pdf->addTextWrap(50,$li_pos,500,$li_tamletpiecont,$li_texto,'center');
							if($li_s<$li_totrow_det)
							{
								$io_pdf->ezNewPage(); // Insertar una nueva página
							}
						}
						$io_report->DS->resetds("codper");
					}
				}
			}// fin del else
			if($i<$li_total-1)
			{
					$io_pdf->ezNewPage(); // Insertar una nueva página
			}									
		}// fin del for($i=1;$i<=$li_total;$i++)
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
	}//fin del else
?> 