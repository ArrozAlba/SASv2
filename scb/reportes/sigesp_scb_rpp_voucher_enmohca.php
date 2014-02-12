<?php
	session_start();
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);//enmohca
	header("X-LIGHTTPD-SID: ".session_id()); 
	$ls_directorio="cheque_configurable";
	$ls_archivo="cheque_configurable/medidas.txt";
	$li_medidas=16;
	//-------------------------------------------------------------------------------------------------
	function uf_inicializar_variables()
	{
		global $valores;
		global $ls_directorio;
		global $ls_archivo;	
		global $li_medidas;	
		if(!file_exists ($ls_directorio))
		{
			$lb_exito=mkdir($ls_directorio,0777);
			if(!$lb_exito)
			{
				print "<script>";
				print "alert('Error al crear directorio cheque_configurable');";
				print "close();";
				print "</script>";
			}
		}
		
		if((!file_exists ($ls_archivo)) || (filesize($ls_archivo)==0))
		{	
			if(file_exists ($ls_directorio))
			{
				$archivo = fopen($ls_archivo, "w");			
				$ls_contenido="150.00-15.00-45.00-30.50-45.00-36.50-32.00-42.50-30.00-49.00-96.00-49.00-150.00-77.00-90.00-77.00";
				fwrite($archivo,$ls_contenido);
				fclose($archivo);
			}
		}
			
		if((file_exists($ls_archivo)) && (filesize($ls_archivo)>0))
		{
			$archivo = fopen($ls_archivo, "r");
			$contenido = fread($archivo, filesize($ls_archivo));			
			fclose($archivo);			
			$valores = explode("-",$contenido);	
			if(count($valores)<>$li_medidas)
			{
				$archivo = fopen($ls_archivo, "w");
				fclose($archivo);			
				print "<script>";
				print "alert('Ocurrio un error, por favor cargue de nuevo el cheque (Las medidas seran inicializadas por fallo de lectura y escritura)');";
				print "close();";
				print "</script>";
			}
		}
		else
		{
			print "<script>";
			print "alert('Ocurrio un error, por favor cargue de nuevo el cheque (Las medidas seran inicializadas por fallo de lectura y escritura)');";
			print "close();";
			print "</script>";
		}
	
	
	}
	function uf_print_encabezado_pagina($ldec_monto,$ls_nomproben,$ls_monto,$ls_fecha,$ls_agnio,&$io_pdf,$ls_fecha_corta)
	{
		/*----------------------------------------------------------------------------------------
		       Function:	uf_print_encabezado_pagina
			   Acess: 		private 
			   Arguments: 	ldec_monto : Monto del cheque
			    		   	ls_nomproben:  Nombre del proveedor o beneficiario
			    		   	ls_monto : Monto en letras
			    		   	ls_fecha : Fecha del cheque
						   	io_pdf   : Instancia de objeto pdf
		       Description: funcion que imprime los encabezados por pagina
			   Creado Por: 	Ing. Laura Cabre
		       Fecha Creaci�: 05/10/2006 
		/----------------------------------------------------------------------------------------*/
		global $valores;
		//print_r
		//Fecha actual
		$io_pdf->add_texto(146,-4,10,"<b>".$ls_fecha_corta."</b>");
		//Imprimo el monto
		$io_pdf->add_texto($valores[0],$valores[1],10,"<b>***".$ldec_monto."***</b>");
		//Informacion de Caducidad
		$io_pdf->add_texto($valores[14],$valores[15],9,"<b>CADUCA A LOS 90 DIAS</b>");		
		//Beneficiario del Cheque
		$io_pdf->add_texto($valores[2],$valores[3],9,"<b>$ls_nomproben</b>");
		

		//Monto en letras del Cheque
		//Cortando el monto en caso de que sea muy largo
		
		$ls_monto_cortado=wordwrap($ls_monto,70,"?");
		$la_arreglo=array();
		$la_arreglo=explode("?",$ls_monto_cortado);
		if(array_key_exists(0,$la_arreglo))
			$io_pdf->add_texto($valores[4],$valores[5],9,"<b>$la_arreglo[0]</b>");
		if(array_key_exists(1,$la_arreglo))
			$io_pdf->add_texto($valores[6],$valores[7],9,"<b>$la_arreglo[1]</b>");	

		//Fecha del Cheque
		$io_pdf->add_texto($valores[8],$valores[9],9,"<b>$ls_fecha</b>");
		//A� del Cheque
		$io_pdf->add_texto($valores[10],$valores[11],9,"<b>$ls_agnio</b>");
		//No Endosable
		$io_pdf->add_texto($valores[12],$valores[13],9,"<b>NO ENDOSABLE</b>");
		
	}// end function uf_print_encabezadopagina

	function uf_print_cabecera($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_chevau,$ls_nomproben,$ls_solicitudes,$ls_conmov,&$io_pdf)
	{
		/*---------------------------------------------------------------------------------------
		       Function: 	uf_print_cabecera
			   Arguments: 	ls_numdoc : Numero de documento
			    		   	ls_nomban : Nombre del banco
						   	ls_cbtan  : Cuenta del banco
						   	ls_chevau : Voucher del cheque
						   	ls_nomproben: Nombre del proveedor o beneficiario
					   		ls_solicitudes: Solicitudes canceladas con el cheque					  
			    		   	io_pdf // total de registros que va a tener el reporte
		       Description: funcion que imprime los datos basicos del cheque
			   Creado Por: 	Ing. Laura Cabre
		 	   Fecha Creaci�: 05/10/2006 
		-----------------------------------------------------------------------------------------*/
		//banco
		$io_pdf->add_texto(5,99.5,10,"<b>$ls_nomban</b>");
		//Numero de cheque(solo mostrara los ultimos 8 digitos del numero)
		$io_pdf->add_texto(76,99.5,10,"<b>".substr($ls_numdoc,-8)."</b>");		
		//Numero Cuenta (solo mostrara los ultimos 20 digitos del numero)
		$io_pdf->add_texto(112,99.5,10,"<b>".substr($ls_ctaban,-23)."</b>");	
		//Orden de Pago (solo mostrara los ultimos 6 digitos del numero)
		$io_pdf->add_texto(175,99.5,10,"<b>".substr($ls_solicitudes,-6)."</b>");
		//Concepto
		$la_data=array();//Columna 2
		$li_pos=154;
		$io_pdf->convertir_valor_mm_px($li_pos);		
		$io_pdf->ezSetY($li_pos);
		$la_data[0]["1"]="<b>$ls_conmov</b>";
		$la_anchos_col = array(195);
		$la_justificaciones = array("left");
		$la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 10,
							   "lineas"=>0,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>1,
							   "margen_vertical"=>0.5);
		$io_pdf->add_tabla(5,$la_data,$la_opciones);
	}// 
	function uf_print_detalle($la_title,$la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de informacion
		//	   			   io_pdf // Objeto PDF
		//    Description: funci� que imprime el detalle
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creaci�: 24/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		global $io_fun_nomina;
		//Imprimo los detalles tanto `de presupuesto como contablwe del movimiento
		$la_datacompleta=array();
		$la_anchos_col = array();
		$la_justificaciones = array();
		$la_opciones = array();
		$li_totalfilas=count($la_data);//(count($la_data, COUNT_RECURSIVE) / count($la_data)) - 1;
		//(la estructura programatica se mostrara de la siguiente manera xxxxxx-xxxxxx-xxx)
		for($li_i=0;$li_i<$li_totalfilas;$li_i++)
		{
			$la_datacompleta[$li_i]["estpro"]="<b>".substr($la_data[($li_i+1)]["estpro"],-17)."</b>";
			$la_datacompleta[$li_i]["spg_cuenta"]="<b>".$la_data[($li_i+1)]["spg_cuenta"]."</b>";
			$la_datacompleta[$li_i]["monto_spg"]="<b>".$la_data[($li_i+1)]["monto_spg"]."</b>";
			$la_datacompleta[$li_i]["scg_cuenta"]="<b>".$la_data[($li_i+1)]["scg_cuenta"]."</b>";
			$la_datacompleta[$li_i]["debe"]="<b>".$la_data[($li_i+1)]["debe"]."</b>";
			$la_datacompleta[$li_i]["haber"]="<b>".$la_data[($li_i+1)]["haber"]."</b>";
		}
		$li_pos=130;
		$io_pdf->convertir_valor_mm_px($li_pos);		
		$io_pdf->ezSetY($li_pos);
		$la_anchos_col = array(51,22,32,30,33,39);
		$la_justificaciones = array("left","right","right","center","right","right");
		$la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 9,
							   "lineas"=>0,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>1,
							   "margen_vertical"=>1);
		$io_pdf->add_tabla(-1,$la_datacompleta,$la_opciones);
	}//
	function uf_print_firmas(&$io_pdf) 
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_firmas
		//		    Acess: private 
		//	    Arguments: io_pdf // Objeto PDF
		//    Description: funcion que imprime los nombres de las personas firmantes del voucher
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creaci�: 22/12/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//Presupuesto
		$io_pdf->add_texto(10,214.5,10,"<b>Lic.Carla Brito</b>");
		//Contabilidad
		$io_pdf->add_texto(79,214.5,10,"<b>Lic. Morela Camacho</b>");
		//Tesoreria
		$io_pdf->add_texto(153,214.5,10,"<b>T.S.U. Yaira Melendez</b>");
		/*//Contabilizado por
		$io_pdf->add_texto(153,214.5,10,"<b>Lic. Morela Camacho</b>");*/
		//Coordinacion de administracion
		$io_pdf->add_texto(32,238.5,10,"<b>Lic. Yanett Coronel</b>");
		//Presidencia
		$io_pdf->add_texto(137,238.5,10,"<b>Ing. Jesus Lugo</b>");
		/*//Tesoreria
		$io_pdf->add_texto(140,156.5,10,"<b>Lic. Nayibe Rodriguez</b>");*/
		
		
	}
	function uf_convertir($ls_numero)
	{
		$ls_numero=str_replace(".","",$ls_numero);
		$ls_numero=str_replace(",",".",$ls_numero);
		return $ls_numero;
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------Declaraciones e Inicializaciones-----------------------//
	uf_inicializar_variables();
	require_once('../../shared/class_folder/class_pdf.php');
	require_once("../../shared/class_folder/class_mensajes.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_datastore.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();
	$in=new sigesp_include();
	$con=$in->uf_conectar();
	$io_sql=new class_sql($con);	
	$io_funciones=new class_funciones();				
	$io_msg      = new class_mensajes();
	$io_pdf = new class_pdf("LETTER","portrait");
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
	$io_pdf->set_margenes(13.5,7,3,5);
	require_once("sigesp_scb_report.php");
	$class_report=new sigesp_scb_report($con);
	$ds_voucher=new class_datastore();	
	$ds_dt_scg=new class_datastore();				
	$ds_dt_spg=new class_datastore();
	include("../../shared/class_folder/cnumero_letra.php");
	$numalet= new cnumero_letra();
	
	$ls_tipimp = "";
	if (array_key_exists("tipimp",$_GET))
	{
		$ls_tipimp = $_GET["tipimp"];
	}	

	if ($ls_tipimp=='lote')
	{
		$ls_codemp		=$_SESSION["la_empresa"]["codemp"];
		$ls_codban      = $_GET["codban"];
		$ls_ctaban      = $_GET["ctaban"];
		$ls_documentos  = $_GET["documentos"];
		$ls_fechas      = $_GET["fechas"];
		$ld_fecdes      = $_GET["fecdesde"];
		$ld_fechas      = $_GET["fechasta"];
		$ls_operaciones = $_GET["operaciones"];
			
		//Descompongo la cadena de documentos en un arreglo tomando como separación el ','
		$arr_documentos = split(",",$ls_documentos);
		$li_totdoc		= count($arr_documentos);
		//Descompongo la cadena de fechas en un arreglo tomando como separación el '-'
		$arr_fecmov = split("-",$ls_fechas);
		$li_totfec  = count($arr_fecmov);
	   //Descompongo la cadena de operaciones en un arreglo tomando como separación el '-'
		$arr_operaciones = split("-",$ls_operaciones);
		$li_totdoc	= count($arr_operaciones);		
		$class_report->uf_buscar_cheques_vouchers($arr_documentos,$arr_fecmov,$arr_operaciones,$ls_codban,$ls_ctaban);
		
		$li_total   = $class_report->ds_voucher1->getRowCount("numdoc");

		if ($li_total>0)
		{	
			error_reporting(E_ALL);
			set_time_limit(1800);
				
			for ($i=1;$i<=$li_total;$i++)
			{
				$ls_numdoc=$class_report->ds_voucher1->getValue("numdoc",$i);
				
				$ls_chevau=$class_report->ds_voucher1->getValue("chevau",$i);
				$ls_codope='CH';
				
				$data=$class_report->uf_cargar_chq_voucher($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
				$class_report->SQL->begin_transaction();
				$lb_valido=$class_report->uf_actualizar_status_impreso($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
				if(!$lb_valido)
				{
					print "Error al actualizar";
					$class_report->is_msg_error;	
					$class_report->SQL->rollback();
				}
				else
				{
					$class_report->SQL->commit();
				}
				$ds_voucher->data=$data;			
				$li_totrow=$ds_voucher->getRowCount("numdoc");
				for($li_i=1;$li_i<=$li_totrow;$li_i++)
				{
					unset($la_data);
					$li_totprenom=0;
					$ldec_mondeb=0;
					$ldec_monhab=0;
					$li_totant=0;
					$ls_numdoc=$ds_voucher->data["numdoc"][$li_i];
					$ls_codban=$ds_voucher->data["codban"][$li_i];
					$ls_nomban=$class_report->uf_select_data($io_sql,"SELECT * FROM scb_banco WHERE codban ='".$ls_codban."' AND codemp='".$ls_codemp."'","nomban");
					$ls_ctaban=$ds_voucher->data["ctaban"][$li_i];
					$ls_chevau=$ds_voucher->data["chevau"][$li_i];
					$ls_fecha_corta=date("d/m/Y");
					$ld_fecmov=$io_funciones->uf_convertirfecmostrar($ds_voucher->data["fecmov"][$li_i]);
					$ls_mes = $io_fecha->uf_load_nombre_mes(substr($ld_fecmov,3,2));
					$ls_fecha=$_SESSION["la_empresa"]["ciuemp"].", ".substr($ld_fecmov,0,2)." de ".$ls_mes;
					$ls_agnio=substr($ld_fecmov,-4,4);
					$ls_nomproben=$ds_voucher->data["nomproben"][$li_i];
					$ls_solicitudes=$class_report->uf_select_solicitudes($ls_numdoc,$ls_codban,$ls_ctaban);
					$ls_conmov=$ds_voucher->getValue("conmov",$li_i);
					$ldec_monret=$ds_voucher->getValue("monret",$li_i);
					$ldec_monto=$ds_voucher->getValue("monto",$li_i);
					$ldec_total=$ldec_monto-$ldec_monret;
			
					$ls_monto=$numalet->uf_convertir_letra($ldec_total,'','');
					uf_print_encabezado_pagina(number_format($ldec_total,2,",","."),$ls_nomproben,$ls_monto,$ls_fecha,$ls_agnio,$io_pdf,$ls_fecha_corta); // Imprimimos el encabezado de la p�ina
					uf_print_cabecera($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_chevau,$ls_nomproben,$ls_solicitudes,$ls_conmov,$io_pdf); // Imprimimos la cabecera del registro
					$ds_dt_scg->data=$class_report->uf_cargar_dt_scg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope); // Obtenemos el detalle del reporte
					$la_items = array('0'=>'scg_cuenta');
					$la_suma  = array('0'=>'monto');
					$ds_dt_scg->group_by($la_items,$la_suma,'scg_cuenta');
					$li_totrow_det=$ds_dt_scg->getRowCount("scg_cuenta");
					
					$ds_dt_spg->data=$class_report->uf_cargar_dt_spg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope);
					$la_items = array('0'=>'estpro','1'=>'spg_cuenta');
					$la_suma  = array('0'=>'monto');
					$ds_dt_spg->group_by($la_items,$la_suma,'spg_cuenta');
					$li_totrow_spg=$ds_dt_spg->getRowCount("spg_cuenta");
					
					$li_totrow_det=$ds_dt_scg->getRowCount("scg_cuenta");
					
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					// Hago un ciclo para unir en una sola matriz los detalles de presupuesto y los contables para proceder luego a pintarlos
					if($li_totrow_det>=$li_totrow_spg)
					{
						for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
						{
							$ls_scg_cuenta=$ds_dt_scg->data["scg_cuenta"][$li_s];
							$ls_debhab = $ds_dt_scg->data["debhab"][$li_s];
							$ldec_monto= $ds_dt_scg->data["monto"][$li_s];
							if($ls_debhab=='D')
							{
								$ldec_mondeb=number_format($ldec_monto,2,",",".");
								$ldec_monhab=" ";
							}
							else
							{
								$ldec_monhab=number_format($ldec_monto,2,",",".");
								$ldec_mondeb=" ";
							}
							if(array_key_exists("spg_cuenta",$ds_dt_spg->data))
							{
								if(array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
								{
									$ls_cuentaspg=$ds_dt_spg->getValue("spg_cuenta",$li_s);	
									$ls_estpro=$ds_dt_spg->getValue("estpro",$li_s);	  
									$ldec_monto_spg=number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
								}
								else
								{
									$ls_cuentaspg=" ";	
									$ls_estpro=" ";	  
									$ldec_monto_spg=" ";
								}
							}
							else
							{
								$ls_cuentaspg=" ";	
								$ls_estpro=" ";	  
								$ldec_monto_spg=" ";
							}
							$la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
						}
						
					}
					if($li_totrow_spg>$li_totrow_det)
					{
						for($li_s=1;$li_s<=$li_totrow_spg;$li_s++)
						{
							if(array_key_exists("scg_cuenta",$ds_dt_scg->data))
							{
								if(array_key_exists($li_s,$ds_dt_scg->data["scg_cuenta"]))
								{
									$ls_scg_cuenta=$ds_dt_scg->data["scg_cuenta"][$li_s];
									$ls_debhab = $ds_dt_scg->data["debhab"][$li_s];
									$ldec_monto= $ds_dt_scg->data["monto"][$li_s];
									if($ls_debhab=='D')
									{
										$ldec_mondeb=number_format($ldec_monto,2,",",".");
										$ldec_monhab=" ";
									}
									else
									{
										$ldec_monhab=number_format($ldec_monto,2,",",".");
										$ldec_mondeb=" ";
									}
								}
								else
								{
									$ls_scg_cuenta="";
									$ls_debhab = "";
									$ldec_monto= "";
									$ldec_mondeb="";
									$ldec_monhab="";					
								}
							}
							else
							{
								$ls_scg_cuenta="";
								$ls_debhab = "";
								$ldec_monto= "";
								$ldec_mondeb="";
								$ldec_monhab="";					
							}
							if(array_key_exists("spg_cuenta",$ds_dt_spg->data))
							{
								if(array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
								{
									$ls_cuentaspg=$ds_dt_spg->getValue("spg_cuenta",$li_s);	
									$ls_estpro=$ds_dt_spg->getValue("estpro",$li_s);	  
									$ldec_monto_spg=number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
								}
								else
								{
									$ls_cuentaspg=" ";	
									$ls_estpro=" ";	  
									$ldec_monto_spg=" ";
								}
							}
							else
							{
								$ls_cuentaspg=" ";	
								$ls_estpro=" ";	  
								$ldec_monto_spg=" ";
							}
							$la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);				
						}			
					}
					////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					if(empty($la_data))
					{
						$ls_cuentaspg='';
						$ls_estpro='';
						$ldec_monto_spg='';
						$ls_scg_cuenta='';
						$ldec_mondeb='';
						$ldec_monhab='';
						$la_data[1]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
						$la_data[2]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
						$la_data[3]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
					}
					
					$x=count($la_data);
					if($x<=7)
					{
						uf_print_detalle(array('title'=>'Detalle Presupuestario Pago','title2'=>'Detalle Contable Pago'),$la_data,$io_pdf); // Imprimimos el detalle 
					}
					else
					{
						$li_aux=1;
						$la_aux=array();
						$lb_print=false;
						for($li_i=1;$li_i<=$x;$li_i++)
						{				
							if($li_aux<=7)
							{
								$la_aux[$li_aux]=$la_data[$li_i];
								$li_aux++;
								$lb_print=false;				
							}
							else
							{
								$li_aux=1;
								$li_i--;
								$lb_print=true;								
							}
							if($lb_print || $li_i==$x)
							{
								uf_print_detalle(array('title'=>'Detalle Presupuestario Pago','title2'=>'Detalle Contable Pago'),$la_aux,$io_pdf); // Imprimimos el detalle 								
								$la_aux=array();
								if($li_i<$x)
									$io_pdf->ezNewPage();
							}
						}
					}
					uf_print_firmas($io_pdf) ;
				}	
			 $io_pdf->ezStream();
			
				if ($i<$li_total)
				{			
					$io_pdf->ezNewPage(); // Insertar una nueva página
					$io_pdf->set_margenes(13.5,7,3,5);
				}
		
			}// Fin del for 1
			$io_pdf->ezStopPageNumbers(1,1);
			$io_pdf->ezStream();
			unset($io_pdf,$class_report,$io_funciones,$ds_dt_spg,$ds_dt_scg,$ds_voucher,$la_data);
		}//Fin del if ($li_total>0)
		else
		{
			 print("<script language=JavaScript>");
			 print(" alert('No hay nada que Reportar');"); 
			 print(" close();");
			 print("</script>");	  
		} 
		
	} // Fin de si es lote
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// SI NO ES POR LOTE ENTONCES
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	else 
	{
	
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_codban=$_GET["codban"];
		$ls_ctaban=$_GET["ctaban"];
		$ls_numdoc=$_GET["numdoc"];
		$ls_chevau=$_GET["chevau"];
		$ls_codope=$_GET["codope"];				
	
		$data=$class_report->uf_cargar_chq_voucher($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
		$class_report->SQL->begin_transaction();
		$lb_valido=$class_report->uf_actualizar_status_impreso($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
		if(!$lb_valido)
		{
			print "Error al actualizar";
			$class_report->is_msg_error;	
			$class_report->SQL->rollback();
		}
		else
		{
			$class_report->SQL->commit();
		}
		$ds_voucher->data=$data;
		error_reporting(E_ALL);
		set_time_limit(1800);
		$li_totrow=$ds_voucher->getRowCount("numdoc");
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
			unset($la_data);
			$li_totprenom=0;
			$ldec_mondeb=0;
			$ldec_monhab=0;
			$li_totant=0;
			$ls_numdoc=$ds_voucher->data["numdoc"][$li_i];
			$ls_codban=$ds_voucher->data["codban"][$li_i];
			$ls_nomban=$class_report->uf_select_data($io_sql,"SELECT * FROM scb_banco WHERE codban ='".$ls_codban."' AND codemp='".$ls_codemp."'","nomban");
			$ls_ctaban=$ds_voucher->data["ctaban"][$li_i];
			$ls_chevau=$ds_voucher->data["chevau"][$li_i];
			$ls_fecha_corta=date("d/m/Y");
			$ld_fecmov=$io_funciones->uf_convertirfecmostrar($ds_voucher->data["fecmov"][$li_i]);
			$ls_mes = $io_fecha->uf_load_nombre_mes(substr($ld_fecmov,3,2));
			$ls_fecha=$_SESSION["la_empresa"]["ciuemp"].", ".substr($ld_fecmov,0,2)." de ".$ls_mes;
			$ls_agnio=substr($ld_fecmov,-4,4);
			$ls_nomproben=$ds_voucher->data["nomproben"][$li_i];
			$ls_solicitudes=$class_report->uf_select_solicitudes($ls_numdoc,$ls_codban,$ls_ctaban);
			$ls_conmov=$ds_voucher->getValue("conmov",$li_i);
			$ldec_monret=$ds_voucher->getValue("monret",$li_i);
			$ldec_monto=$ds_voucher->getValue("monto",$li_i);
			$ldec_total=$ldec_monto-$ldec_monret;
	
			$ls_monto=$numalet->uf_convertir_letra($ldec_total,'','');
			uf_print_encabezado_pagina(number_format($ldec_total,2,",","."),$ls_nomproben,$ls_monto,$ls_fecha,$ls_agnio,$io_pdf,$ls_fecha_corta); // Imprimimos el encabezado de la p�ina
			uf_print_cabecera($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_chevau,$ls_nomproben,$ls_solicitudes,$ls_conmov,$io_pdf); // Imprimimos la cabecera del registro
			$ds_dt_scg->data=$class_report->uf_cargar_dt_scg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope); // Obtenemos el detalle del reporte
			$la_items = array('0'=>'scg_cuenta');
			$la_suma  = array('0'=>'monto');
			$ds_dt_scg->group_by($la_items,$la_suma,'scg_cuenta');
			$li_totrow_det=$ds_dt_scg->getRowCount("scg_cuenta");
			
			$ds_dt_spg->data=$class_report->uf_cargar_dt_spg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope);
			$la_items = array('0'=>'estpro','1'=>'spg_cuenta');
			$la_suma  = array('0'=>'monto');
			$ds_dt_spg->group_by($la_items,$la_suma,'spg_cuenta');
			$li_totrow_spg=$ds_dt_spg->getRowCount("spg_cuenta");
			
			$li_totrow_det=$ds_dt_scg->getRowCount("scg_cuenta");
			
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// Hago un ciclo para unir en una sola matriz los detalles de presupuesto y los contables para proceder luego a pintarlos
			if($li_totrow_det>=$li_totrow_spg)
			{
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_scg_cuenta=$ds_dt_scg->data["scg_cuenta"][$li_s];
					$ls_debhab = $ds_dt_scg->data["debhab"][$li_s];
					$ldec_monto= $ds_dt_scg->data["monto"][$li_s];
					if($ls_debhab=='D')
					{
						$ldec_mondeb=number_format($ldec_monto,2,",",".");
						$ldec_monhab=" ";
					}
					else
					{
						$ldec_monhab=number_format($ldec_monto,2,",",".");
						$ldec_mondeb=" ";
					}
					if(array_key_exists("spg_cuenta",$ds_dt_spg->data))
					{
						if(array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
						{
							$ls_cuentaspg=$ds_dt_spg->getValue("spg_cuenta",$li_s);	
							$ls_estpro=$ds_dt_spg->getValue("estpro",$li_s);	  
							$ldec_monto_spg=number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
						}
						else
						{
							$ls_cuentaspg=" ";	
							$ls_estpro=" ";	  
							$ldec_monto_spg=" ";
						}
					}
					else
					{
						$ls_cuentaspg=" ";	
						$ls_estpro=" ";	  
						$ldec_monto_spg=" ";
					}
					$la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
				}
				
			}
			if($li_totrow_spg>$li_totrow_det)
			{
				for($li_s=1;$li_s<=$li_totrow_spg;$li_s++)
				{
					if(array_key_exists("scg_cuenta",$ds_dt_scg->data))
					{
						if(array_key_exists($li_s,$ds_dt_scg->data["scg_cuenta"]))
						{
							$ls_scg_cuenta=$ds_dt_scg->data["scg_cuenta"][$li_s];
							$ls_debhab = $ds_dt_scg->data["debhab"][$li_s];
							$ldec_monto= $ds_dt_scg->data["monto"][$li_s];
							if($ls_debhab=='D')
							{
								$ldec_mondeb=number_format($ldec_monto,2,",",".");
								$ldec_monhab=" ";
							}
							else
							{
								$ldec_monhab=number_format($ldec_monto,2,",",".");
								$ldec_mondeb=" ";
							}
						}
						else
						{
							$ls_scg_cuenta="";
							$ls_debhab = "";
							$ldec_monto= "";
							$ldec_mondeb="";
							$ldec_monhab="";					
						}
					}
					else
					{
						$ls_scg_cuenta="";
						$ls_debhab = "";
						$ldec_monto= "";
						$ldec_mondeb="";
						$ldec_monhab="";					
					}
					if(array_key_exists("spg_cuenta",$ds_dt_spg->data))
					{
						if(array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
						{
							$ls_cuentaspg=$ds_dt_spg->getValue("spg_cuenta",$li_s);	
							$ls_estpro=$ds_dt_spg->getValue("estpro",$li_s);	  
							$ldec_monto_spg=number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
						}
						else
						{
							$ls_cuentaspg=" ";	
							$ls_estpro=" ";	  
							$ldec_monto_spg=" ";
						}
					}
					else
					{
						$ls_cuentaspg=" ";	
						$ls_estpro=" ";	  
						$ldec_monto_spg=" ";
					}
					$la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);				
				}			
			}
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			if(empty($la_data))
			{
				$ls_cuentaspg='';
				$ls_estpro='';
				$ldec_monto_spg='';
				$ls_scg_cuenta='';
				$ldec_mondeb='';
				$ldec_monhab='';
				$la_data[1]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
				$la_data[2]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
				$la_data[3]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
			}
			
			$x=count($la_data);
			if($x<=7)
			{
				uf_print_detalle(array('title'=>'Detalle Presupuestario Pago','title2'=>'Detalle Contable Pago'),$la_data,$io_pdf); // Imprimimos el detalle 
			}
			else
			{
				$li_aux=1;
				$la_aux=array();
				$lb_print=false;
				for($li_i=1;$li_i<=$x;$li_i++)
				{				
					if($li_aux<=7)
					{
						$la_aux[$li_aux]=$la_data[$li_i];
						$li_aux++;
						$lb_print=false;				
					}
					else
					{
						$li_aux=1;
						$li_i--;
						$lb_print=true;								
					}
					if($lb_print || $li_i==$x)
					{
						uf_print_detalle(array('title'=>'Detalle Presupuestario Pago','title2'=>'Detalle Contable Pago'),$la_aux,$io_pdf); // Imprimimos el detalle 								
						$la_aux=array();
						if($li_i<$x)
							$io_pdf->ezNewPage();
					}
				}
			}
			uf_print_firmas($io_pdf) ;
		}	
	 $io_pdf->ezStream();	
 }
?> 