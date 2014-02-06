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
	ini_set('memory_limit','2048M');
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
		// Fecha Creación: 08/11/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_listadobeneficiario.php",$ls_descripcion,$ls_codnom);
		
		
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_recibo($as_nomaut,$ad_fecha,$as_nomban,$ai_monto,$as_nomper,$as_cedper,$as_numexp,$as_concepto,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/11/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$la_data[1]=array('nota'=>'AUTORIZADO : '. $as_nomaut);		
		$la_data[2]=array('nota'=>'');	
                $la_data[3]=array('nota'=>'                                                                                                                                                                ENVIO DE CHEQUE');
		$la_data[4]=array('nota'=>'');
		$la_data[5]=array('nota'=>'');	
		$la_data[6]=array('nota'=>'CUMPLIENDO INSTRUCCIONES DEL CIUDADANO GRAL. DIV. PRESIDENTE DE LA JUNTA ADMINISTRADORA DEL I.P.S.F.A. LE ANEXAMOS  EL (LOS) SIGUIENTES(S) ');
		$la_data[7]=array('nota'=>'CHEQUE(S) : ');
		$la_data[8]=array('nota'=>'NUMERO : ');
		$la_data[9]=array('nota'=>'FECHA : '.$ad_fecha);
		$la_data[10]=array('nota'=>'BANCO : '.$as_nomban);
		$la_data[11]=array('nota'=>'MONTO : '.$ai_monto);
		$la_data[12]=array('nota'=>'');
		$la_data[13]=array('nota'=>'');	
		$la_data[14]=array('nota'=>'          A LA ORDEN DE LOS BENEFICIARIO(S), REMITIDOS POR CONCEPTO DE: '.$as_concepto.' RETENIDOS A: '.$as_nomper.', C.I. NRO. '.$as_cedper);	
		$la_data[15]=array('nota'=>'          SEGUN EXPEDIENTE NRO. : '.$as_numexp);
		$la_data[16]=array('nota'=>'NOTA:');		
		$la_data[17]=array('nota'=>'          Se agradece remitir a esta Institucion lo mas pronto posible, el numero de cuenta del beneficiario y titular de la misma, indicando No de Cedula de identidad del mismo, aperturada en la entidad bancaria BANFOANDES, para proceder  a realizar el deposito en la misma, y asi dar cumplimiento a la orden emitida en la  Circular No 18 de la Direccion Ejecutiva de la Magitrastura (DEM) de fecha 21 de Noviembre del 2005, donde se ordena aperturar todaslas cuentas existentes en el Tribunal en el Banco de Fomento Regional de los Andes (BANFOANDES). Igualmente nombres, apellidos y cedula de identidad del militar causante.');
		$la_data[18]=array('nota'=>'          Los mismos deben ser remitidos a traves del fax No 0212-609-22-87 o 609-20-10');
		$la_data[21]=array('nota'=>'          REMISION QUE HAGO A USTED PARA SU CONOCIMIENTO Y DEMAS FINES');
		$la_data[22]=array('nota'=>'');
		$la_data[23]=array('nota'=>'                                                                                     DIOS Y FEDERACION');
		$la_data[24]=array('nota'=>'');
		$la_data[25]=array('nota'=>'');
		$la_data[26]=array('nota'=>'                                                                            JHONNY A. BENCOMO FERNANDEZ');
		$la_data[27]=array('nota'=>'                                                                                                      CAP');
		$la_data[28]=array('nota'=>'                                                                                               TESORERO');
		$la_data[29]=array('nota'=>'');
		$la_data[30]=array('nota'=>'');
		$la_data[31]=array('nota'=>'          RECIBI CONFORME:_____________________________');
		$la_data[32]=array('nota'=>'');
		$la_data[33]=array('nota'=>'               Se agradece a los Juzgados correspondientes, devolver el original debidamente firmado por el beneficiario al Presidente del Instituto de Prevision Social de las Fuerzas Armadas (I.P.S.F.A)');
		
		$la_data[34]=array('nota'=>'');
		$la_data[35]=array('nota'=>'                                   I.P.S.F.A    Av Los Proceres. Edif. Sede Caracas');
		$la_data[36]=array('nota'=>'                                         email: pensionestesoreria@hotmail.com');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla						 
						 'cols'=>array('nota'=>array('justification'=>'left','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,'','',$la_config);
		unset($la_data);
		unset($la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------  Instancia de las clases  -----------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	$ls_tiporeporte="0";
	$ls_bolivares ="Bs.";
	$li_tipo=1;
	require_once("sigesp_sno_class_report.php");
	$io_report=new sigesp_sno_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_quincena=$io_fun_nomina->uf_obtenervalor_get("quincena","-");
	$ls_codban=$io_fun_nomina->uf_obtenervalor_get("codban","");
	$ls_nomban=$io_fun_nomina->uf_obtenervalor_get("nomban","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$ls_titulo="Recibo de Cheques de Beneficiarios";
	$ls_periodo="";
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_listadobeneficiario_personal($ls_codperdes,$ls_codperhas,$ls_quincena,$ls_codban,$ls_subnomdes,$ls_subnomhas,$ls_orden,&$rs_data); // Cargar el DS con los datos de la cabecera del reporte
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
		error_reporting(E_ALL);
		set_time_limit(3600);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.25,2.5,3,3); // Configuración de los margenes en centímetros
				//$io_pdf->ezStartPageNumbers(750,50,7,'','',1); // Insertar el número de página
		$li_numrowtot=$rs_data->RecordCount();
		$li_montototalbene=0;	
		$li_totalbene=0;
		$li_s=0;		
 		while((!$rs_data->EOF)&&($lb_valido))
		{
			$ls_codper=$rs_data->fields["codper"];
			$ls_cedper=$rs_data->fields["cedper"];
			$ls_nomper=$rs_data->fields["apeper"].", ".$rs_data->fields["nomper"];
			$li_neto=$rs_data->fields["monnetres"];
			$li_monnet=$io_fun_nomina->uf_formatonumerico($rs_data->fields["monnetres"]);
			$lb_valido=$io_report->uf_listadobeneficiario_beneficiario_cheque($ls_codper,$ls_codban,&$rs_data_dt); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				while((!$rs_data_dt->EOF)&&($rs_data_dt->RecordCount()>0))
				{
					
					$ls_cedben=$rs_data_dt->fields["cedben"];
					$ls_apenomben=$rs_data_dt->fields["apeben"].", ". $rs_data_dt->fields["nomben"];					
					$ls_tipben=$rs_data_dt->fields["tipben"];
					$ls_cta=$rs_data_dt->fields["ctaban"];
					
					 if(($rs_data_dt->fields["nomcheben"]!="")&&($rs_data_dt->fields["cedaut"]!=""))
					 {
						
						$ls_nomaut=$rs_data_dt->fields["nomcheben"];
						$ls_cedaut=$rs_data_dt->fields["cedaut"];
						
						$ls_tipben=$rs_data_dt->fields["tipben"];
						$ls_concepto="";
						switch ($ls_tipben)
						{
							case '0': 
								$ls_concepto="PENSION SOBREVIVIENTE";
							break;
							case '1': 
								$ls_concepto="PENSION JUDICIAL";
							break;
							default: 
								$ls_concepto="PENSION ALIMENTICIA";
							break;
						}
											
						$li_porpagben=$rs_data_dt->fields["porpagben"];
						$li_monpagben=$rs_data_dt->fields["monpagben"];
						$li_monto=0;
						if($li_porpagben>0)
						{
							$li_monto=($li_neto*$li_porpagben)/100;
						}
						if($li_monpagben>0)
						{
							$li_monto=$li_monpagben;
						
						}
						$li_montototalbene=$li_montototalbene+$li_monto;
						$ls_numexp=$rs_data_dt->fields["numexpben"];
						$li_monto=$io_fun_nomina->uf_formatonumerico($li_monto);
						$ld_fecha=date("d/m/Y");						
						uf_print_recibo($ls_nomaut,$ld_fecha,$ls_nomban,$li_monto,$ls_nomper,$ls_cedper,$ls_numexp,$ls_concepto,$io_pdf);
						if ($li_s < $li_numrowtot)
						{
							$io_pdf->ezNewPage(); // Insertar una nueva página
						
						}
						$li_s=$li_s+1;
					}
					$rs_data_dt->MoveNext();
					

				}
				unset($row);
				unset($rs_data_dt->fields);
				$io_report->io_sql->free_result($rs_data_dt);
				
			}
			
			$rs_data->MoveNext();
		}
		
		if(($lb_valido)&&($li_s>0)) // Si no ocurrio ningún error
		{
					
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algún error
		{
			if($li_s==0)
			{
				print("<script language=JavaScript>");
				print(" alert('No hay nada que Reportar');"); 
				print(" close();");
				print("</script>");
			}
			else
			{
				print("<script language=JavaScript>");
				print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
				print(" close();");
				print("</script>");		
			}
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 
