<?php
//ini_set("buffering ","0"); // aumentamos la memoria a 1,5GB
//ob_start();
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 // Clase:      - sigesp_sfc_c_contabilizar
 // Autor:       - Ing. Nelson Barraez
 // Descripcion: - Clase que realiza la contabilizacion de las facturas, devoluciones, depositos, asi como todos aquellos movimientos que generen algun asiento contable y    	      
 // 		            presupuestario
 // Fecha:     - 10/12/2008                                                                            Ultima modificacion:  15/01/2009
 // ACTUALIZADO POR: ING. ZULHEYMAR RODRGUEZ     Fecha:  17-02-2009
 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_contabilizar
{
	 var $io_funcion;
	 var $io_msgc;
	 var $io_sql;
	 var $datoemp;
	 var $io_msg;
	 var $io_int_int; 
	 var $ls_cuentacaja;
	 var $ls_cuentaxcob;
	 var $ls_cuentacarta;
	 var $ls_cuentabanco;
	 var $ls_cuentaiva;
	 var $ls_cuentaadelanto;
	 var $ls_cuenta_retiva;  
	 var $ls_cuenta_retislr;  
	 var $ls_cuenta_retmun;
	 var $ls_spicuenta;
	 var $ls_cuentascg; 
	 var $ls_cuentadev;
	 var $ls_nomtie;
	 var $is_conpag; 
	 
function sigesp_sfc_c_contabilizar($ls_feccie)
{
	require_once("../shared/class_folder/class_sql.php");  
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sigesp_int.php");
	require_once("../shared/class_folder/class_sigesp_int_int.php");
	require_once("../shared/class_folder/class_sigesp_int_spg.php");
	require_once("../shared/class_folder/class_sigesp_int_scg.php");
	require_once("../shared/class_folder/class_sigesp_int_spi.php");
	require_once("../shared/class_folder/class_fecha.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$this->io_int_int=new class_sigesp_int_int();
	$this->io_funcion=new class_funciones();
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();
	$this->io_sql= new class_sql($io_connect);
	$this->io_seguridad=   new sigesp_c_seguridad();
	$this->io_msg=new class_mensajes();
	$this->ls_feccie=$ls_feccie;
	$this->io_ds_banco=new class_datastore(); // Datastored de Movimientos de Banco
	$this->io_ds_costos=new class_datastore();// Datastore de Costos Totales
}
function uf_procesar_contabilizacion_facturas($aa_seguridad)
{   //Casos 1 - 2 - 3 .1 - 3.2 - 4 .1 - 4.2 - 4.3 - 5 
	$this->io_int_int->is_comprobante;
	$ls_ini_comprobante=substr($this->io_int_int->is_comprobante,0,3);
	$ls_fin_comprobante=substr($this->io_int_int->is_comprobante,7,8);
	$ls_documento='FAC-'.$ls_ini_comprobante.$ls_fin_comprobante;
	$lb_valido=$this->uf_contabilizar_caso_contado($ls_documento);
	if($lb_valido)	
		//print 'paso4';	      
		$lb_valido=$this->uf_contabilizar_caso_credito($ls_documento);
	if($lb_valido)
		//print 'paso5';
		$lb_valido=$this->uf_contabilizar_caso_parcial($ls_documento);
	if($lb_valido)
		//print 'paso6';
		$lb_valido=$this->uf_contabilizar_caso_carta_orden($ls_documento);
	if ($lb_valido)
		//print 'paso7';
		$lb_valido=$this->uf_contabilizar_caso_costo_venta($ls_documento);
	return $lb_valido;
}
function uf_procesar_contabilizacion_devoluciones($aa_seguridad) 
{
	$this->io_int_int->is_comprobante;
	$ls_ini_comprobante=substr($this->io_int_int->is_comprobante,0,3);
	$ls_fin_comprobante=substr($this->io_int_int->is_comprobante,7,8);
	$ls_documento='DEV-'.$ls_ini_comprobante.$ls_fin_comprobante;
	$lb_valido=$this->uf_contabilizar_caso_devolucion_contado($ls_documento);
	if ($lb_valido)
	{
	$lb_valido=$this->uf_contabilizar_caso_devolucion_credito($ls_documento);
	}
	if ($lb_valido)
	{
	$lb_valido=$this->uf_contabilizar_caso_devolucion_notas($ls_documento);//Notas canceladas por caja el mismo día 
	}
	return $lb_valido;
}

function uf_existe_deposito($ls_numdoc,$ls_codope,$ls_codban,$ls_ctaban)
{
	$lb_valido=false;
	$ls_sql=" SELECT  numdoc  ".
					"	   FROM  scb_movbco ".
					"  WHERE codemp='".$_SESSION["la_empresa"]["codemp"]."'  AND numdoc='".$ls_numdoc."' ".
					"         AND codope='".$ls_codope."'  AND  codban='".$ls_codban."'  AND ctaban='".$ls_ctaban."' ";
	//print $ls_sql;
	$rs_data=$this->io_sql->select($ls_sql);			
	if($rs_data==false)
	{
		$this->io_msgc="Error en uf_existe_deposito ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$lb_valido=true;
		}		
		$this->io_sql->free_result($rs_data);
	}				  						  
	return $lb_valido;
}
	
	function uf_procesar_contabilizacion_despositos_efectivo($ldec_total_caja,$ldec_total_adelantos,$ldec_total_banco,$ls_cuentaban,$aa_seguridad)
	{    //Casos 9.1 - 9.2 - 9.3
				$lb_valido=false;
				$ldec_total_banco=str_replace(".","",$ldec_total_banco);
				//print $ldec_total_banco;
				$ldec_total_banco=str_replace(",",".",$ldec_total_banco);
				//print $ldec_total_banco;
				$ldec_total_caja=str_replace(".","",$ldec_total_caja);
				$ldec_total_caja=str_replace(",",".",$ldec_total_caja);
				$ldec_total_adelantos=str_replace(".","",$ldec_total_adelantos);
				$ldec_total_adelantos=str_replace(",",".",$ldec_total_adelantos);
				$ld_fecha=$this->io_funcion->uf_convertirdatetobd($this->io_int_int->id_fecha);
				$this->io_sql->begin_transaction();
				$lb_existe = $this->uf_existe_deposito($this->io_int_int->is_comprobante,'DP',$this->io_int_int->as_codban,$this->io_int_int->as_ctaban);
				if($lb_existe===false)
				{
						
						$ls_sql="INSERT INTO scb_movbco(codemp, codban, ctaban, numdoc, codope, estmov, cod_pro, ced_bene, tipo_destino,  ".
										"														   codconmov, fecmov, conmov, nomproben, monto, estbpd, estcon, estcobing, esttra, chevau, ".
										"														   estimpche, monobjret, monret, procede, comprobante, fecha, id_mco, emicheproc, emicheced, ".
										"														   emichenom, emichefec, estmovint, codusu, codopeidb, aliidb, feccon, estreglib, numcarord, numpolcon, ".
										"														   coduniadmsig, codbansig, fecordpagsig, tipdocressig, numdocressig, estmodordpag, codfuefin, forpagsig,  ".
										"														   medpagsig,codestprosig, nrocontrolop) ".
										"VALUES(	'".$_SESSION["la_empresa"]["codemp"]."', '".$this->io_int_int->as_codban."', '".$this->io_int_int->as_ctaban."', '".$this->io_int_int->is_comprobante."', 'DP', 'C', '".$this->io_int_int->is_cod_prov."', '".$this->io_int_int->is_ced_ben."', '".$this->io_int_int->is_tipo."', ".
										"					'---', '".$ld_fecha."', '".$this->io_int_int->is_descripcion."', 'Ninguno', ".$ldec_total_banco.", 'M', '0', '0', '0', '', ".
										"														  '0', 0, 0, '".$this->io_int_int->is_procedencia."', '".$this->io_int_int->is_comprobante."', '".$ld_fecha."', '', '0', '0', ".
										"														  '0', '1900-01-01', '0', '".$_SESSION["la_logusr"]."', '', 0, '1900-01-01', '', '', 0,  '', '', '1900-01-01', '', '', '', '--', '', '', '', '')";
						//print $ls_sql.'<br>';
																								  
						$li_rows=$this->io_sql->execute($ls_sql);
						if($li_rows==false)
						{
							$lb_valido=false;
							$this->io_msgc="Error en uf_procesar_contabilizacion_depositos_efectivo ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
							print $this->io_sql->message;
						}
						else
						{
								
								if($li_rows==1)
								{
										$lb_valido=true;
										///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
										$ls_evento="INSERT";
										$ls_descripcion="Inserto el movimiento bancario de operacion Deposito numero ".$this->io_int_int->is_comprobante." para el banco ".$this->io_int_int->as_codban." cuenta ".$this->io_int_int->as_ctaban." por un monto de ".$ldec_diferencia;
										$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
										////////////////////////////////////////////////////////////////////////////////////////////////////////////
								}
						}																					  
						if($lb_valido)
						{
						//print 'paso';
									$lb_valido=$this->uf_insertar_dt_scg($this->ls_cuentacaja,'H',$ldec_total_caja,$aa_seguridad)	;//Asiento cuenta contable Caja .				 	
									if($lb_valido)  
									{
											$lb_valido=$this->uf_insertar_dt_scg($ls_cuentaban,'D',$ldec_total_banco,$aa_seguridad)	;//Asiento cuenta contable Banco.	
											if($lb_valido)  
											{
													$lb_valido=$this->uf_insertar_dt_scg($this->ls_cuentaadelanto,'H',$ldec_total_adelantos,$aa_seguridad)	;//Asiento cuenta contable Adelantos.										
											}
									}
						}
						if($lb_valido)
						{
								$this->io_sql->commit();
						}	
						else
						{
								$this->io_sql->rollback();
						}
						//////////////////////////////////////////////////////////////////////////////Integracion contable del deposito////////////////////////////////////////////////////////////////////////////////////////////////////////////
						if($lb_valido)    //Valido que haya insertado correctamente el deposito..
						{
								$this->io_int_int->uf_init_create_datastore();  
								if($ldec_total_caja>0){//Asiento cuenta contable Caja .				 
									$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentacaja,'H',$ldec_total_caja,$this->io_int_int->is_comprobante,$this->io_int_int->is_procedencia,"Caja de la tienda ".$_SESSION["ls_nomtienda"]);	
								}
								if($ldec_total_banco>0){//Asiento cuenta contable Banco.
									$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$ls_cuentaban,'D',$ldec_total_banco,$this->io_int_int->is_comprobante,$this->io_int_int->is_procedencia,"Banco de la tienda ".$_SESSION["ls_nomtienda"]);	
								}
								if($ldec_total_adelantos>0){//Asiento cuenta contable Adelantos.
									$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaadelanto,'H',$ldec_total_adelantos,$this->io_int_int->is_comprobante,$this->io_int_int->is_procedencia,"Adelantos de la tienda ".$_SESSION["ls_nomtienda"]);	
								}	
								$this->io_int_int->uf_int_init_transaction_begin();
								$lb_valido=$this->io_int_int->uf_init_end_transaccion_integracion($aa_seguridad);		
						}			
						$this->io_int_int->uf_sql_transaction($lb_valido);	
						////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
				}
				else
				{
							$this->io_msg->message("El documento que intenta registrar ya existe en el sistema");
				}			
				return $lb_valido;
	}
	
	function uf_insertar_dt_scg($ls_cuenta,$ls_operacion,$ldec_monto,$aa_seguridad)
	{
			//$lb_valido=false;
			$ls_sql="INSERT INTO scb_movbco_scg(codemp, codban, ctaban, numdoc, codope, estmov, scg_cuenta, debhab, codded, ".
							"																			documento,desmov, procede_doc, monto, monobjret)".
								"		  VALUES('".$_SESSION["la_empresa"]["codemp"]."', '".$this->io_int_int->as_codban."', '".$this->io_int_int->as_ctaban."', '".$this->io_int_int->is_comprobante."', 'DP', 'C', '".$ls_cuenta."', '".$ls_operacion."', '00000', ".
							"							 '".$this->io_int_int->is_comprobante."', '".$this->io_int_int->is_descripcion."', '".$this->io_int_int->is_procedencia."', '".$ldec_monto."', '0')";
							
			//print $ls_sql.'<br>';				
			$li_rows=$this->io_sql->execute($ls_sql);
			if($li_rows==false)
			{
				$lb_valido=false;
				$this->io_msgc="Error en uf_insertar_dt_scg ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
				print $this->io_sql->message;
			}
			else
			{
					if($li_rows==1)
					{
							$lb_valido=true;
							/////////////////////////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
							$ls_evento="INSERT";
							$ls_descripcion="Inserto el detalle contable del movimiento bancario de operacion Deposito numero ".$this->io_int_int->is_comprobante." para el banco ".$this->io_int_int->as_codban." cuenta ".$this->io_int_int->as_ctaban." por un monto de ".$ldec_monto." a la cuenta contable ".$ls_cuenta." operacion ".$ls_operacion;
							$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
							////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					}
			}																		 
			return $lb_valido;
	}
	
	function uf_procesar_contabilizacion_dep_trans_credito()
	{  //Casos 10.1 - 10.2 - 10.3
			
			$lb_valido=$this->uf_contabilizar_caso_facturas_credito();
			if($lb_valido)			
			$lb_valido=$this->uf_contabilizar_caso_cartaorden();
			return $lb_valido;
	}
	
	function uf_contabilizar_caso_cartaorden()
	{  //Casos 10.1 - 10.2 - 10.3
			
			//-->OJOO estos metodos ahi que modificarlos para que totalice sobre los cobros de carta orden actualmente lo hace sobre la tabla cobro de factura.
			
			//print 'lb_valido'.$lb_valido;
			$lb_valido=true;
			$this->io_int_int->is_comprobante;
			print ("<table width='415' border='0' align='center' cellpadding='0' cellspacing='0' class='formato-blanco'>");
           	print ("<tr>");
            print ("<td height='17' colspan='2' align='right' class='titulo-ventana'>Caso Cobranzas Cartas Ordenes</td>"); 
		/*	ob_flush();
			flush();  */
			$ls_ini_comprobante=substr($this->io_int_int->is_comprobante,0,3);
			$ls_fin_comprobante=substr($this->io_int_int->is_comprobante,7,8);
			//print 'comprobante-->'.$ls_comprobante.'<br>**************************************************';
			$ls_documento='CAR-'.$ls_ini_comprobante.$ls_fin_comprobante;
			//print 'documento-->'.$ls_documento.'<br>**************************************************';
			$this->uf_buscar_total_cancelacion_depositos(&$ldec_total_dep_carta,$ls_cuenta,$ls_banco,'CARTA');			
			//-------->>>> OJO  sumar  al total_banco el monto de las cartas y hacer el otro asiento del haber con el monto de las cartas.			
			
			if($ldec_total_dep_carta>0 && $lb_valido)//Asiento cuenta contable Banco.
			{
		//print 'ldec_total_dep_carta'.$ls_sc_cuenta;
				$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$ls_cuenta,'D',$ldec_total_dep_carta,$ls_documento,$this->io_int_int->is_procedencia,"Banco ".$ls_banco." de la tienda ".$_SESSION["ls_nomtienda"]);
				//print 'valido'.$lb_valido;
				//print "<br> DEBE_sin  =".$ldec_total_sin_ret."<br>";
			}
			print ("<tr class='celdas-grises'>");
			print("<td height='22' align='left'>Total Banco(D)</td>");
			print("<td align='right'>");
			print number_format($ldec_total_dep_carta,2, ',', '.');
			print("</td>");
			print("</tr>");
			/*ob_flush();
			flush();*/
			if($ldec_total_dep_carta>0 && $lb_valido)//Asiento cuenta carta orden por cobrar.
			{
				//print 'ldec_total_dep_carta2';
				$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentacarta,'H',$ldec_total_dep_carta,$ls_documento,$this->io_int_int->is_procedencia,"Adelantos de la tienda ".$_SESSION["ls_nomtienda"]);	
				//print "<br> HABER_carta  =".$ldec_total_cartas."<br>";			
			}	
			print ("<tr class='celdas-grises'>");
			print("<td height='22' align='left'>Total Adelantos(H)</td>");
			print("<td align='right'>");
			print number_format($ldec_total_dep_carta,2, ',', '.');
			print("</td>");
			print("</tr>");
			$ls_debe=$this->io_int_int->idec_monto_debe;
			$ls_haber=$this->io_int_int->idec_monto_haber;
			print ("<tr class='celdas-amarillas'>");
			print ("<td height='17' align='left' class='texto-azul'>Total Debe</td>");
			print ("<td height='17' align='right' class='texto-azul'>");								
			print number_format($ls_debe,2, ',', '.');
			print ("</td>");
			print("</tr>");		
			print ("<tr class='celdas-amarillas'>");
			print ("<td height='17' align='left' class='texto-azul'>Total Haber</td>");
			print ("<td height='17' align='right' class='texto-azul'>");								
			print number_format($ls_haber,2, ',', '.');
			print ("</td>");
			print("</tr>");			
			print ("</table>");	
			/*ob_flush();
			flush();*/
			if (doubleval(trim($this->io_int_int->idec_monto_debe))!=doubleval(trim($this->io_int_int->idec_monto_haber)))
				{
				$this->io_msg->message("Comprobante descuadrado, total Debe->".$ls_debe." total haber->".$ls_haber." Verifique Cobranzas de Facturas a Credito");
				}	
			//print 'paso11'.$lb_valido;
			return $lb_valido;
	}
	/************************AGREGADO PARA CASOS FACTURAS A CREDITO CANCELADAS****************************************/
	function uf_contabilizar_caso_facturas_credito()//Caso para facturas por cobrar canceladas (Efectivo, cheque, deposito, transferencias y adelantos
	{
				$this->io_int_int->is_comprobante;
				$ls_ini_comprobante=substr($this->io_int_int->is_comprobante,0,3);
				$ls_fin_comprobante=substr($this->io_int_int->is_comprobante,7,8);
				$ls_documento='COB-'.$ls_ini_comprobante.$ls_fin_comprobante;
				$lb_valido=true;				
				$ldec_mondeb=0;
				$ldec_monhab=0;
				print ("<table width='415' border='0' align='center' cellpadding='0' cellspacing='0' class='formato-blanco'>");
           		print ("<tr>");
                print ("<td height='17' colspan='2' align='right' class='titulo-ventana'>Caso Cobranzas Facturas a Credito</td>");   
				/*ob_flush();
				flush();*/
				$this->uf_buscar_total_monto_factura_por_cobrar($ldec_totalcobros,$ldec_total_retenido);//Busqueda del monto total de la  cobranza y de las retenciones hechas
				//print "<br> TOTAL cobros=".$ldec_totalcobros;
				$ldec_monto_totalcobros=$ldec_totalcobros-$ldec_total_retenido;
				$this->uf_buscar_total_monto_caja_factura_por_cobrar($ldec_total_caja);//Busqueda del monto total de la  facturacion y del total IVA de la facturacion
				$this->uf_buscar_total_monto_banco_factura_por_cobrar($ldec_total_banco);//Monto de depositos y Notas de credito(Transferencias) en el banco ---->>>OJOOOO   modificaar para que agarre la cuenta contable  del banco correspondiente al deposito o transferencia realizada, debe generar un asiento por cada cuenta contable
				$this->uf_buscar_total_monto_cruce_adelantos_factura_por_cobrar($ldec_cruce_adelantos);//Monto de la aplicion de NC de sobrantesen facturas			
				$this->uf_buscar_total_monto_adelantos_factura_por_cobrar($ldec_total_adelantos);//monto obtenido por NC de sobrantes en facturas.											
				$this->uf_buscar_total_retenido_iva(&$ldec_total_iva);
				$this->uf_buscar_total_retenido_islr(&$ldec_total_islr);
				$this->uf_buscar_total_retenido_retmun(&$ldec_total_retmun);										
				$ldec_diferencia=round($ldec_totalcobros-($ldec_total_caja+$ldec_total_banco),2);
				//print "<br> DIF=".$ldec_diferencia;
				$ldec_porcobrar=round((($ldec_totalcobros+$ldec_total_adelantos)-round(($ldec_total_banco+$ldec_total_caja+$ldec_cruce_adelantos),2)),2);
				print ("<tr class='celdas-grises'>");
                print("<td height='22' align='left'>Total Cobrado(H)</td>");
                print("<td align='right'>");
				print number_format($ldec_totalcobros,2, ',', '.');
				print("</td>");
                print("</tr>");
				print ("<tr class='celdas-grises'>");
                print("<td height='22' align='left'>Total Caja(D)</td>");
                print("<td align='right'>");
				print number_format($ldec_total_caja,2, ',', '.');
				print("</td>");
                print("</tr>");		
				/*ob_flush();
				flush();*/
				//print "<br> XCOB=".$ldec_porcobrar;
				$ldec_cobrado        = $ldec_total_caja+$ldec_total_banco+$ldec_total_adelantos+$ldec_cruce_adelantos;
				$ldec_devengado  = $ldec_totalcobros+$ldec_total_adelantos;						
				//*****************************Ingresos*****************************		
				//Asiento presupuestario de ingreso correspondiente al devengado y cobrado.
				if($ldec_devengado>0 && $lb_valido)
				{
					$lb_valido=$this->io_int_int->uf_spi_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_spicuenta,'DEV',$ldec_devengado,$ls_documento,$this->io_int_int->is_procedencia,"DEVENGADO DE LA TIENDA ".$_SESSION["ls_nomtienda"]." POR CONCEPTO DE COBRANZAS REALIZADAS");																														
				}
				if($ldec_cobrado>0 && $lb_valido)
				{
					$lb_valido=$this->io_int_int->uf_spi_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_spicuenta,'COB',$ldec_cobrado,$ls_documento,$this->io_int_int->is_procedencia,"COBRADO DE LA TIENDA ".$_SESSION["ls_nomtienda"]." POR CONCEPTO DE COBRANZAS REALIZADAS");																														
				}	
			   
				//Asientos Contables
				if($ldec_totalcobros>0 && $lb_valido){
					$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaxcob,'H ',$ldec_totalcobros,$ls_documento,$this->io_int_int->is_procedencia,"Cuentas por cobrar Clientes de la tienda ".$_SESSION["ls_nomtienda"]." por conceptos de cobranzas realizadas");
				}
				if($ldec_total_caja>0 && $lb_valido){//Asiento cuenta contable Caja .		
					$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentacaja,'D',$ldec_total_caja,$ls_documento,$this->io_int_int->is_procedencia,"Caja de la tienda ".$_SESSION["ls_nomtienda"]." por conceptos de cobranzas realizadas");	
				}
				if($ldec_total_banco>0 && $lb_valido){//Asiento cuenta contable Banco. ---->>>OJOOOO   modificaar para que agarre la cuenta contable  del banco correspondiente al deposito o transferencia realizada , debe generar un asiento por cada cuenta contable
				
				$lb_valido_banco=$this->uf_cargar_movimientos_banco_cobranza();
				if($lb_valido_banco && $lb_valido)
				{
				$li_totrow=$this->io_ds_banco->getRowCount('sc_cuenta');
				if($li_totrow>0)
				{					
					for($li_i=1;($li_i<=$li_totrow)&&($lb_valido);$li_i++)
					{
						$ls_cuenta=$this->io_ds_banco->data["sc_cuenta"][$li_i];
						$ld_montobanco=$this->io_ds_banco->data["total"][$li_i];
						$ls_banco=$this->io_ds_banco->data["dencta"][$li_i];						
						print ("<tr class='celdas-grises'>");
						print("<td height='22' align='left'>Total Banco(D)");
						print ($ls_banco);
						print ("</td>");
						print("<td align='right'>");
						print number_format($ld_montobanco,2, ',', '.');
						print("</td>");
						print("</tr>");
						/*ob_flush();
						flush();	*/
					if ($ld_montobanco>0 && $lb_valido)
					{
						$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$ls_cuenta,'D',$ld_montobanco,$ls_documento,$this->io_int_int->is_procedencia,"Banco ".$ls_banco." de la tienda ".$_SESSION["ls_nomtienda"]." por conceptos de cobranzas realizadas");
					}
					}	
				}
				}
				}
				print ("<tr class='celdas-grises'>");
                print("<td height='22' align='left'>Total Cruce Adelantos(D)</td>");
                print("<td align='right'>");
				print number_format($ldec_cruce_adelantos,2, ',', '.');
				print("</td>");
                print("</tr>");
				print ("<tr class='celdas-grises'>");
                print("<td height='22' align='left'>Total Adelantos(H)</td>");
                print("<td align='right'>");
				print number_format($ldec_total_adelantos,2, ',', '.');
				print("</td>");
                print("</tr>");	
				print ("<tr class='celdas-grises'>");
                print("<td height='22' align='left'>Total Retencin IVA(D)</td>");
                print("<td align='right'>");
				print number_format($ldec_total_iva,2, ',', '.');
				print("</td>");
                print("</tr>");	
				print ("<tr class='celdas-grises'>");	
				print("<td height='22' align='left'>Total Retencin ISLR(D)</td>");
                print("<td align='right'>");
				print number_format($ldec_total_islr,2, ',', '.');
				print("</td>");
                print("</tr>");	
				print ("<tr class='celdas-grises'>");	
				print("<td height='22' align='left'>Total Retencin Municipal(D)</td>");
                print("<td align='right'>");
				print number_format($ldec_total_retmun,2, ',', '.');
				print("</td>");
                print("</tr>");	
				/*ob_flush();
				flush();	*/
				if($ldec_cruce_adelantos>0 && $lb_valido){//Asiento cuenta contable cruce Adelantos.
				//print '<br> $this->ls_cuentaadelanto->'.$this->ls_cuentaadelanto.'<br>';
					$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaadelanto,'D',$ldec_cruce_adelantos,$ls_documento,$this->io_int_int->is_procedencia,"Adelantos Recibidos de la tienda ".$_SESSION["ls_nomtienda"]." por conceptos de cobranzas realizadas");	
				}
				
				if($ldec_total_adelantos>0 && $lb_valido){//Asiento cuenta contable Adelantos.
				//print '<br> $this->adelanto->'.$this->ls_cuentaadelanto.'<br>';
					$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaadelanto,'H',$ldec_total_adelantos,$ls_documento,$this->io_int_int->is_procedencia,"Adelantos de la tienda ".$_SESSION["ls_nomtienda"]);	
				}
				if($ldec_total_iva>0 && $lb_valido)	//Asiento cuenta contable Retenciones IVA.
			{
			//print '<br> $this->iva->'.$this->ls_cuentaadelanto.'<br>';
				$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuenta_retiva,'D',$ldec_total_iva,$ls_documento,$this->io_int_int->is_procedencia,"Retenciones por cobrar  IVA".$_SESSION["ls_nomtienda"]);	
				//print "<br> DEBE_iva  =".$ldec_total_iva."<br>";			
			}
			if($ldec_total_islr>0 && $lb_valido)	//Asiento cuenta contable Retenciones ISLR.
			{
			//print '<br> $this->islr->'.$this->ls_cuentaadelanto.'<br>';
				$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuenta_retislr,'D',$ldec_total_islr,$ls_documento,$this->io_int_int->is_procedencia,"Retenciones por cobrar  I.S.L.R.".$_SESSION["ls_nomtienda"]);	
				//print "<br> DEBE_islr  =".$ldec_total_islr."<br>";			
			}
			if($ldec_total_retmun>0 && $lb_valido) 	//Asiento cuenta contable Retenciones Municipales.
			{
			//print '<br> $this->iva->'.$this->ls_cuentaadelanto.'<br>';
				$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuenta_retunoxmil,'D',$ldec_total_retmun,$ls_documento,$this->io_int_int->is_procedencia,"Retenciones Municipales ".$_SESSION["ls_nomtienda"]);	
				//print "<br> DEBE_retmun  =".$ldec_total_retmun."<br>";			
			}	
			//print $lb_valido;	
				print ("<tr class='celdas-grises'>");
				print ("<td height='17' align='left' class='texto-azul'>Cobrado</td>");
				print ("<td height='17' align='right' class='texto-azul'>");
				print number_format($ldec_cobrado,2, ',', '.');
				print ("</td>");
			    print("</tr>");
				print ("<tr class='celdas-grises'>");
				print ("<td height='17' align='left' class='texto-azul'>Devengado</td>");
				print ("<td height='17' align='right' class='texto-azul'>");
				print number_format($ldec_devengado,2, ',', '.');
				print ("</td>");
			    print("</tr>");	
				print ("<tr class='celdas-amarillas'>");
				print("</tr>");	
				$ls_debe=$this->io_int_int->idec_monto_debe;
				$ls_haber=$this->io_int_int->idec_monto_haber;
				print ("<tr class='celdas-amarillas'>");
				print ("<td height='17' align='left' class='texto-azul'>Total Debe</td>");
				print ("<td height='17' align='right' class='texto-azul'>");								
				print number_format($ls_debe,2, ',', '.');
				print ("</td>");
			    print("</tr>");	
				print ("<tr class='celdas-amarillas'>");
				print ("<td height='17' align='left' class='texto-azul'>Total Haber</td>");
				print ("<td height='17' align='right' class='texto-azul'>");								
				print number_format($ls_haber,2, ',', '.');
				print ("</td>");
			    print("</tr>");			
				print ("</table>");	
				/*ob_flush();
				flush();*/
				if (doubleval(trim($this->io_int_int->idec_monto_debe))!=doubleval(trim($this->io_int_int->idec_monto_haber)))
				{
				$this->io_msg->message("Comprobante descuadrado, total Debe->".$ls_debe." total haber->".$ls_haber." Verifique Cobranzas de Facturas a Credito");
				}	
		return $lb_valido;
	}    	
	
	function uf_buscar_total_monto_factura_por_cobrar(&$ldec_total,&$ldec_total_ret)//Busco el total obtenido por pagos de factura
	{
			$ldec_total=0;	
			$ls_sql="SELECT COALESCE(SUM(c.moncob),0) as total FROM sfc_cobro_cliente c WHERE c.estcob<>'A'
 AND c.feccob='".$this->ls_feccie."'";
							
			//print 'total COBROS*******>'.$ls_sql;
								
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data==false)
			{
				$lb_valido=false;
				$this->io_msgc="Error en uf_buscar_total_monto_facturacion ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$ldec_total=$row["total"];		
					//$ldec_total_retenido=$row["retenido"];
				}		
				$this->io_sql->free_result($rs_data);
			}	
			$ls_sql="SELECT COALESCE(SUM(f.monret),0) as retenido FROM sfc_facturaretencion f,sfc_cobro_cliente c WHERE f.codemp=c.codemp AND
f.codcli=c.codcli AND f.numcob=c.numcob AND f.codtiend=c.codtiend AND c.estcob<>'A' AND c.feccob='".$this->ls_feccie."'";
							
			//print 'total COBROS*******>'.$ls_sql;
								
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data==false)
			{
				$lb_valido=false;
				$this->io_msgc="Error en uf_buscar_total_monto_facturacion ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$ldec_total_ret=$row["retenido"];	
					//print $ldec_total_ret;	
					//$ldec_total_retenido=$row["retenido"];
				}		
				$this->io_sql->free_result($rs_data);
			}	
			
				print ("<tr class='celdas-amarillas'>");
				print("</tr>");	
				$ls_debe=$this->io_int_int->idec_monto_debe;
				$ls_haber=$this->io_int_int->idec_monto_haber;
				print ("<tr class='celdas-amarillas'>");
				print ("<td height='17' align='left' class='texto-azul'>Total Debe</td>");
				print ("<td height='17' align='right' class='texto-azul'>");								
				print number_format($ls_debe,2, ',', '.');
				print ("</td>");
			    print("</tr>");	
				print ("<tr class='celdas-amarillas'>");
				print ("<td height='17' align='left' class='texto-azul'>Total Haber</td>");
				print ("<td height='17' align='right' class='texto-azul'>");								
				print number_format($ls_haber,2, ',', '.');
				print ("</td>");
			    print("</tr>");				
			return $lb_valido;		
	}	
	function uf_cargar_movimientos_banco_cobranza()//Busco el total obtenido de los depositos o transferencias utilizados como instrumento de pago
	{	
 
 $ls_sql="SELECT COALESCE(SUM(i.monto),0) as total,cta.sc_cuenta,cta.dencta FROM sfc_cobro_cliente c,sfc_instpagocob i,sfc_formapago f,scb_ctabanco cta 
WHERE c.estcob<>'A' AND (f.metforpag='D' OR f.metforpag='B') AND i.numinst NOT like 
'NC%' AND i.codforpag=f.codforpag AND c.numcob=i.numcob AND c.feccob='".$this->ls_feccie."' AND cta.codemp=c.codemp AND cta.codemp=i.codemp AND cta.codban=i.codban AND 
cta.ctaban=i.ctaban 
GROUP BY cta.sc_cuenta,cta.dencta;";
							//print '<br>total_monto_banco cobranza->'.$ls_sql.'<br>';
			$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_cargar_movimientos_banco ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->io_ds_banco->data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;	
	}
	function uf_buscar_total_monto_caja_factura_por_cobrar(&$ldec_total)//Busco el total obtenido por pagos de factura
	{
			$ldec_total=0;	
			$ls_sql="SELECT COALESCE(SUM(i.monto),0) as total FROM sfc_instpagocob i,sfc_cobro_cliente c,sfc_formapago f ".
			" WHERE c.estcob<>'A' AND (f.metforpag='C' OR f.metforpag='H') AND ".
			" i.numinst NOT like 'NC%' AND i.codemp=c.codemp AND i.codcli=c.codcli AND i.numcob=c.numcob AND ".
			" i.codtiend=c.codtiend AND i.codforpag=f.codforpag AND c.feccob='".$this->ls_feccie."'";
							
			//print 'COBROS CONTADO*******>'.$ls_sql;
								
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data==false)
			{
				$lb_valido=false;
				$this->io_msgc="Error en uf_buscar_total_monto_facturacion ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$ldec_total=$row["total"];		
					//$ldec_total_retenido=$row["retenido"];
				}		
				$this->io_sql->free_result($rs_data);
			}	
			return $lb_valido;		
	}	
	function uf_buscar_total_monto_banco_factura_por_cobrar(&$ldec_total)//Busco el total obtenido de los depositos o transferencias utilizados como instrumento de pago
	{
			$ldec_total=0;	
			$ls_sql="SELECT COALESCE(SUM(i.monto),0) as total FROM sfc_cobro_cliente c,sfc_instpagocob i,sfc_formapago f ".
			" WHERE c.estcob<>'A' AND (f.metforpag='D' OR f.metforpag='B') AND i.numinst NOT like 'NC%' AND ".
			" c.codemp=i.codemp AND c.codcli=i.codcli AND c.numcob=i.numcob AND i.codtiend=c.codtiend ".
			" AND i.codforpag=f.codforpag AND c.feccob='".$this->ls_feccie."';"; 
							//print '<br>total_monto_banco->'.$ls_sql.'<br>';
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data==false)
			{
				$lb_valido=false;
				$this->io_msgc="Error en uf_buscar_total_monto_banco ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$ldec_total=$row["total"];		
				}		
				$this->io_sql->free_result($rs_data);
			}	
			return $lb_valido;		
	}
	
	function uf_buscar_total_monto_cruce_adelantos_factura_por_cobrar(&$ldec_total)//Busco el total obtenido de la utilizacion de una Nota de Credito como instrumento de pago en una factura
	{
			$ldec_total=0;	
			$ls_sql="SELECT COALESCE(SUM(i.monto),0) as total FROM sfc_cobro_cliente c,sfc_instpagocob i, sfc_formapago f".
			" WHERE c.estcob<>'A' AND (f.metforpag='D' OR f.metforpag='B') AND i.numinst like 'NC%' AND ".
			" c.codemp=i.codemp AND c.codcli=i.codcli AND c.numcob=i.numcob ".
			" AND c.codtiend=i.codtiend AND i.codforpag=f.codforpag AND c.feccob='".$this->ls_feccie."'"; 
							
			//print '<br>cruce_adelanto->'.$ls_sql.'<br>'; 
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data==false)
			{
				$lb_valido=false;
				$this->io_msgc="Error en uf_buscar_total_monto_cruce_adelantos ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$ldec_total=$row["total"];		
				}		
				$this->io_sql->free_result($rs_data);
			}	
			return $lb_valido;		
	}
	
	function uf_buscar_total_monto_adelantos_factura_por_cobrar(&$ldec_total)//Busco el total de los adelantos recibidos por sobrante en factura
	{
			$ldec_total=0;	
			$ls_sql="SELECT COALESCE(SUM(n.monto),0) as total FROM sfc_nota n,sfc_cobro_cliente c WHERE ".
			" n.nro_documento like 'COB%' AND n.tipnot='CXP' AND c.estcob<>'A' AND ".
			" c.numcob=n.nro_documento AND c.feccob='".$this->ls_feccie."'" ; 
							
//print '<br>total_monto_adelantos->'.$ls_sql.'<br>'; 
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data==false)
			{
				$lb_valido=false;
				$this->io_msgc="Error en uf_buscar_total_monto_adelantos ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$ldec_total=$row["total"];		
				}		
				$this->io_sql->free_result($rs_data);
			}	
			return $lb_valido;		
	}
	
	
	function uf_buscar_total_retenido_iva(&$ldec_monto)
	{
			//CAMBIAR LA TABLA sfc_cobro A sfc_cobro_cliente.
			$ldec_monto=0;
			$ls_sql="SELECT COALESCE(SUM(f.monret),0) as montoret FROM sfc_facturaretencion f,sfc_cobro_cliente c,sigesp_deducciones d WHERE d.iva='1' AND c.estcob<>'A' AND c.feccob='".$this->ls_feccie."'
AND f.numcob=c.numcob AND f.codcli=c.codcli AND f.codemp=c.codemp AND f.codtiend=c.codtiend AND f.codded=d.codded AND f.codemp=d.codemp;";
			//print 'retencion iva->'.$ls_sql;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data==false)
			{
				$lb_valido=false;
				$this->io_msgc="Error en uf_buscar_total_retenido_iva ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ldec_monto    = $row["montoret"];		
					$ldec_total_ret = $row["totalret"];		
				}		
				$this->io_sql->free_result($rs_data);
			}				  						  
	}
	
	function uf_buscar_total_retenido_islr(&$ldec_monto)
	{
			//CAMBIAR LA TABLA sfc_cobro A sfc_cobro_cliente
			$ldec_monto=0;
			$ls_sql="SELECT COALESCE(SUM(f.monret),0) as montoret FROM sfc_facturaretencion f,sfc_cobro_cliente c,sigesp_deducciones d WHERE d.islr='1' AND c.estcob<>'A'AND c.feccob='".$this->ls_feccie."' AND f.numcob=c.numcob AND f.codcli=c.codcli AND f.codemp=c.codemp AND f.codtiend=c.codtiend AND f.codded=d.codded AND f.codemp=d.codemp;";
							
			//print '<br>ISLR->'.$ls_sql.'<br>';
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data==false)
			{
				$lb_valido=false;
				$this->io_msgc="Error en uf_buscar_total_retenido_islr ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ldec_monto    = $row["montoret"];		
					$ldec_total_ret = $row["totalret"];		
				}		
				$this->io_sql->free_result($rs_data);
			}				  						  
	}
	
	function uf_buscar_total_retenido_retmun(&$ldec_monto)
	{
			//CAMBIAR LA TABLA sfc_cobro A sfc_cobro_cliente
			$ldec_monto=0;
			$ls_sql=" SELECT COALESCE(SUM(f.monret),0) as montoret FROM sfc_facturaretencion f,sfc_cobro_cliente c,sigesp_deducciones d 
 WHERE d.estretmun='1' AND c.estcob<>'A' AND c.feccob='".$this->ls_feccie."' AND f.numcob=c.numcob AND f.codcli=c.codcli AND f.codemp=c.codemp AND 
f.codtiend=c.codtiend AND f.codded=d.codded AND f.codemp=d.codemp;";
							
			//print 'RET MUNI->'.$ls_sql.'<br>';
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data==false)
			{
				$lb_valido=false;
				$this->io_msgc="Error en uf_buscar_total_retenido_retmun ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ldec_monto    = $row["montoret"];		
					$ldec_total_ret = $row["totalret"];		
				}		
				$this->io_sql->free_result($rs_data);
			}				  						  
	}
	
	function uf_buscar_total_cancelacion_depositos(&$ldec_monto,&$ls_sc_cuenta,&$ls_ctaban,$ls_tipo)
	{
			//>>>>>>>OOOJJJOOO<<<<<<<<//
			//CAMBIAR LA TABLA sfc_cobro A sfc_cobro_cliente
			$ldec_monto=0;
			if($ls_tipo=='CREDITO')
			{
					$ls_sql="SELECT COALESCE(SUM(sfc_instpagocob.monto),0) as monto ".
									"	  FROM sfc_instpagocob,sfc_cobro_cliente,sfc_formapago ".
"  WHERE   sfc_formapago.metforpag='B' AND sfc_instpagocob.codforpag='05' AND sfc_cobro_cliente.numcob=sfc_instpagocob.numcob  ".
									"		 AND sfc_instpagocob.codforpag=sfc_formapago.codforpag";
			}
			elseif($ls_tipo=='CARTA')//Cambiar aqui a la tabla sfc_cobrocartaorden y sfc_instpagocobcartaorden
			{
					$ls_sql="SELECT COALESCE(SUM(sfc_instpagocobcartaorden.monto),0) as monto,sfc_instpagocobcartaorden  ".
									"	  FROM sfc_instpagocobcartaorden,sfc_cobrocartaorden,sfc_formapago  ".
"   WHERE  sfc_formapago.metforpag='D' AND sfc_instpagocobcartaorden.codforpag='08' AND sfc_cobrocartaorden.numcob=sfc_instpagocobcartaorden.numcob   ".
									"		 AND sfc_instpagocobcartaorden.codforpag=sfc_formapago.codforpag  ";
									
					$ls_sql="SELECT COALESCE(SUM(sfc_instpagocobcartaorden.monto),0) as monto,scb_ctabanco.sc_cuenta,scb_ctabanco.dencta
 FROM sfc_instpagocobcartaorden,sfc_cobrocartaorden,sfc_formapago,scb_ctabanco  WHERE sfc_formapago.metforpag='D' 
AND sfc_instpagocobcartaorden.codforpag='08' AND sfc_cobrocartaorden.numcob=sfc_instpagocobcartaorden.numcob AND sfc_instpagocobcartaorden.codforpag=sfc_formapago.codforpag
AND scb_ctabanco.codemp=sfc_cobrocartaorden.codemp AND scb_ctabanco.codemp=sfc_instpagocobcartaorden.codemp AND scb_ctabanco.codban=sfc_instpagocobcartaorden.codban AND
scb_ctabanco.ctaban=sfc_instpagocobcartaorden.ctaban AND sfc_cobrocartaorden.feccob='".$this->ls_feccie."' AND  sfc_cobrocartaorden.estcob<>'A' GROUP BY scb_ctabanco.sc_cuenta,scb_ctabanco.dencta;";
					//print 'Carta Orden--->'.$ls_sql.'<br>';			
			}
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data==false)
			{
				$lb_valido=false;
				//print  $this->io_sql->message;
				$this->io_msgc="Error en uf_buscar_total_cancelacion_depositos ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ldec_monto=$row["monto"];		
					$ls_sc_cuenta=$row["sc_cuenta"];
					$ls_ctaban=$row["dencta"];
				}		
				$this->io_sql->free_result($rs_data);
			}				  						  
	}
	
	function uf_buscar_total_cancelacion_transferencia(&$ldec_monto,$ls_tipo)
	{
			//>>>>>>>OOOJJJOOO<<<<<<<<//
			//CAMBIAR LA TABLA sfc_cobro A sfc_cobro_cliente
			$dec_monto=0;
			if($ls_tipo=='CREDITO')
			{
					$ls_sql="SELECT COALESCE(SUM(sfc_instpagocob.monto),0) as monto  ".
									"	  FROM sfc_instpagocob,sfc_cobro_cliente,sfc_formapago  ".
									"  WHERE sfc_formapago.metforpag='D' AND sfc_cobro_cliente.numcob=sfc_instpagocob.numcob  ".
									"		 AND sfc_instpagocob.codforpag=sfc_formapago.codforpag  ";			
			}
			elseif($ls_tipo=='CARTA')//Cambiar aqui por la tabla sfc_cobrocaartaorden y sfc_instpagocobcartaorden
			{
					$ls_sql="SELECT COALESCE(SUM(sfc_instpagocobcartaorden.monto),0) as monto  ".
									"	  FROM sfc_instpagocobcartaorden,sfc_cobrocartaorden,sfc_formapago ".
				"  WHERE  sfc_formapago.metforpag='D' AND sfc_cobrocartaorden.numcob=sfc_instpagocobcartaorden.numcob  ".
									"		 AND sfc_instpagocobcartaorden.codforpag=sfc_formapago.codforpag  ";			
			}
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data==false)
			{
				$lb_valido=false;
				$this->io_msgc="Error en uf_buscar_total_cancelacion_transferencia ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ldec_monto=$row["monto"];		
				}		
				$this->io_sql->free_result($rs_data);
			}				  						  
	}	
function uf_contabilizar_caso_devolucion_contado($ls_documento)
{
	$lb_valido=true;
	$this->is_conpag=1;
	$ldec_mondeb=0;
	$ldec_monhab=0;	
	$ldec_total=0;	
	$ldec_total_retenido=0;
	$ldec_total_adelanto=0;
	$ls_sql="SELECT COALESCE(SUM(dt.candev*precio),0) AS total_devuelto,COALESCE(SUM((dt.candev*precio)*dt.porimp/100)) ".
	" AS total_iva FROM sfc_devolucion d,sfc_detdevolucion dt,sfc_factura f WHERE d.codemp=dt.codemp AND d.coddev=dt.coddev".
	" AND d.codtiend=dt.codtiend AND d.codemp=f.codemp AND d.codtiend=f.codtiend AND d.numfac=f.numfac AND dt.codemp=f.codemp".
	" AND dt.codtiend=f.codtiend AND substr(d.fecdev,0,11)='".$this->ls_feccie."' AND f.conpag ='".$this->is_conpag."' ".
	" AND d.estdev<>'A';";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$lb_valido=false;
		$this->io_msgc="Error en uf_contabilizar_caso_devolucion_contado ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$ldec_total=$row["total_devuelto"];		
			$ldec_total_retenido=$row["total_iva"];
			$ldec_total_adelanto=$ldec_total+$ldec_total_retenido;
		}		
		$this->io_sql->free_result($rs_data);
		print ("<table width='415' border='0' align='center' cellpadding='0' cellspacing='0' class='formato-blanco'>");
		print ("<tr>");
		print ("<td height='17' colspan='2' align='right' class='titulo-ventana'>");
		print utf8_decode("Caso Devolución");
		print ("</td>"); 
		print ("<tr class='celdas-grises'>");
		print("<td height='22' align='left'>");
		print utf8_decode("Total Devolución Contado(D)");
		print ("</td>");
		print("<td align='right'>");
		print number_format($ldec_total,2, ',', '.');
		print("</td>");
		print("</tr>");	
		print ("<tr class='celdas-grises'>");
		print("<td height='22' align='left'>");
		print utf8_decode("Total Debito Fiscal Contado(D)");
		print ("</td>");
		print("<td align='right'>");
		print number_format($ldec_total_retenido,2, ',', '.');
		print("</td>");
		print("</tr>");	
		print ("<tr class='celdas-grises'>");
		print("<td height='22' align='left'>");
		print utf8_decode("Total Adelantos(H)");
		print ("</td>");
		print("<td align='right'>");
		print number_format($ldec_total_adelanto,2, ',', '.');
		print("</td>");
		print("</tr>");	
		/*ob_flush();
		flush();			*/
		if($ldec_total_adelanto>0 && $lb_valido)
		{ 
			//print '$ldec_total_contado->'.$ldec_total_contado;
			$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaadelanto,'H'
			,$ldec_total_adelanto,$ls_documento,$this->io_int_int->is_procedencia,"Adelantos Recibidos por Ventas de la 
			tienda ".$_SESSION["ls_nomtienda"]);		
		}	
		if ($ldec_total>0 && $lb_valido)
		{
			$lb_valido=$this->uf_contabilizar_caso_costo_venta_devolucion($ls_documento);
			$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentadev,'D',
			$ldec_total,$ls_documento,$this->io_int_int->is_procedencia,"Devolucion por ventas de la tienda ".$_SESSION[
			"ls_nomtienda"]);	
		}
		if ($ldec_total_retenido>0 && $lb_valido)
		{
			$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaiva,'D',
			$ldec_total_retenido,$ls_documento,$this->io_int_int->is_procedencia,"Debito fiscal de la tienda por devolucion ".
			$_SESSION["ls_nomtienda"]);
		}	
	}
	$ls_debe=$this->io_int_int->idec_monto_debe;
	$ls_haber=$this->io_int_int->idec_monto_haber;
	print ("<tr class='celdas-amarillas'>");
	print ("<td height='17' align='left' class='texto-azul'>Total Debe</td>");
	print ("<td height='17' align='right' class='texto-azul'>");								
	print number_format($ls_debe,2, ',', '.');
	print ("</td>");
	print("</tr>");	
	print ("<tr class='celdas-amarillas'>");
	print ("<td height='17' align='left' class='texto-azul'>Total Haber</td>");
	print ("<td height='17' align='right' class='texto-azul'>");								
	print number_format($ls_haber,2, ',', '.');
	print ("</td>");
	print("</tr>");	
	/*ob_flush();
	flush();*/
	if (doubleval(trim($this->io_int_int->idec_monto_debe))!=doubleval(trim($this->io_int_int->idec_monto_haber)))
	{
	$this->io_msg->message("Comprobante descuadrado, total Debe->".$ls_debe." total haber->".$ls_haber." Verifique Facturas de Contado");
	}
	return $lb_valido;	
}	

function uf_contabilizar_caso_devolucion_credito($ls_documento)
{
	$lb_valido=true;
	$this->is_conpag=2;
	$this->is_conpag_1=3;
	$ldec_mondeb=0;
	$ldec_monhab=0;	
	$ldec_total=0;	
	$ldec_total_retenido=0;
	$ldec_total_cxc=0;
	$ls_sql="SELECT COALESCE(SUM(dt.candev*precio),0) AS total_devuelto,COALESCE(SUM((dt.candev*precio)*dt.porimp/100)) ".
	" AS total_iva FROM sfc_devolucion d,sfc_detdevolucion dt,sfc_factura f WHERE d.codemp=dt.codemp AND d.coddev=dt.coddev".
	" AND d.codtiend=dt.codtiend AND d.codemp=f.codemp AND d.codtiend=f.codtiend AND d.numfac=f.numfac AND dt.codemp=f.codemp".
	" AND dt.codtiend=f.codtiend AND substr(d.fecdev,0,11)='".$this->ls_feccie."' ".
	" AND (f.conpag ='".$this->is_conpag."' OR f.conpag ='".$this->is_conpag_1."') ".
	" AND d.estdev<>'A';";
	//print $ls_sql;
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$lb_valido=false;
		$this->io_msgc="Error en uf_contabilizar_caso_devolucion_credito ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$ldec_total=$row["total_devuelto"];		
			$ldec_total_retenido=$row["total_iva"];
			$ldec_total_cxc=$ldec_total+$ldec_total_retenido;
		}		
		$this->io_sql->free_result($rs_data);
		print ("<table width='415' border='0' align='center' cellpadding='0' cellspacing='0' class='formato-blanco'>");
		print ("<tr>");
		print ("<td height='17' colspan='2' align='right' class='titulo-ventana'>");
		print utf8_decode("Caso Devolución");
		print ("</td>"); 
		print ("<tr class='celdas-grises'>");
		print("<td height='22' align='left'>");
		print utf8_decode("Total Devolución Crédito(D)");
		print ("</td>");
		print("<td align='right'>");
		print number_format($ldec_total,2, ',', '.');
		print("</td>");
		print("</tr>");	
		print ("<tr class='celdas-grises'>");
		print("<td height='22' align='left'>");
		print utf8_decode("Total Debito Fiscal Crédito(D)");
		print ("</td>");
		print("<td align='right'>");
		print number_format($ldec_total_retenido,2, ',', '.');
		print("</td>");
		print("</tr>");	
		print ("<tr class='celdas-grises'>");
		print("<td height='22' align='left'>");
		print utf8_decode("Total ctas. por cobrar(H)");
		print ("</td>");
		print("<td align='right'>");
		print number_format($ldec_total_cxc,2, ',', '.');
		print("</td>");
		print("</tr>");	
		/*ob_flush();
		flush();			*/
		if($ldec_total_cxc>0 && $lb_valido)
		{ 
		//	print '$ldec_total_contado->'.$ldec_total_contado;
			$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaxcob,'H'
			,$ldec_total_cxc,$ls_documento,$this->io_int_int->is_procedencia,"Cuemtas por Cobrar por Ventas de la 
			tienda ".$_SESSION["ls_nomtienda"]);		
		}	
		if ($ldec_total>0 && $lb_valido)
		{
		//print '$ldec_total_contado2->'.$ldec_total_contado;
			$lb_valido=$this->uf_contabilizar_caso_costo_venta_devolucion($ls_documento);
			$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentadev,'D',
			$ldec_total,$ls_documento,$this->io_int_int->is_procedencia,"Devolucion por ventas de la tienda ".$_SESSION[
			"ls_nomtienda"]);	
		}
		if ($ldec_total_retenido>0 && $lb_valido)
		{
		//print '$ldec_total_contado3->'.$ldec_total_contado;
			$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaiva,'D',
			$ldec_total_retenido,$ls_documento,$this->io_int_int->is_procedencia,"Debito fiscal de la tienda por devolucion ".
			$_SESSION["ls_nomtienda"]);
		}	
	}
	$ls_debe=$this->io_int_int->idec_monto_debe;
	$ls_haber=$this->io_int_int->idec_monto_haber;
	print ("<tr class='celdas-amarillas'>");
	print ("<td height='17' align='left' class='texto-azul'>Total Debe</td>");
	print ("<td height='17' align='right' class='texto-azul'>");								
	print number_format($ls_debe,2, ',', '.');
	print ("</td>");
	print("</tr>");	
	print ("<tr class='celdas-amarillas'>");
	print ("<td height='17' align='left' class='texto-azul'>Total Haber</td>");
	print ("<td height='17' align='right' class='texto-azul'>");								
	print number_format($ls_haber,2, ',', '.');
	print ("</td>");
	print("</tr>");	
/*	ob_flush();
	flush();*/
	if (doubleval(trim($this->io_int_int->idec_monto_debe))!=doubleval(trim($this->io_int_int->idec_monto_haber)))
	{
	$this->io_msg->message("Comprobante descuadrado, total Debe->".$ls_debe." total haber->".$ls_haber." Verifique Facturas de Contado");
	}
	return $lb_valido;	
}	

function uf_contabilizar_caso_devolucion_notas($ls_documento)
{
	$lb_valido=true;	
	$ldec_mondeb=0;
	$ldec_monhab=0;	
	$ldec_total_efectivo=0;	
	$ls_sql="	SELECT COALESCE(sum(monto ),0) as total  ".
							"		FROM sfc_nota	".
							"	WHERE estnota ='C' AND tipnot='CXP'  ".
							"			AND numnot NOT IN (SELECT numinst   ".
							"														    FROM sfc_instpago   ".
							"	WHERE numinst=sfc_nota.numnot)  AND sfc_nota.fecnot='".$this->ls_feccie."' AND ".
							" substr(sfc_nota.nro_documento,1,3)='DEV'"; 
	//print $ls_sql;
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$lb_valido=false;
		$this->io_msgc="Error en uf_contabilizar_caso_devolucion_notas ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$ldec_total_efectivo=$row["total"];		
		}				
		$this->io_sql->free_result($rs_data);
		print ("<table width='415' border='0' align='center' cellpadding='0' cellspacing='0' class='formato-blanco'>");
		print ("<tr>");
		print ("<td height='17' colspan='2' align='right' class='titulo-ventana'>");
		print utf8_decode("Caso Devolución Efectivo");
		print ("</td>"); 
		print ("<tr class='celdas-grises'>");
		print("<td height='22' align='left'>");
		print utf8_decode("Total Devolución Adelanto(D)");
		print ("</td>");
		print("<td align='right'>");
		print number_format($ldec_total_efectivo,2, ',', '.');
		print("</td>");
		print("</tr>");	
		print ("<tr class='celdas-grises'>");
		print("<td height='22' align='left'>");
		print utf8_decode("Total Caja(H)");
		print ("</td>");
		print("<td align='right'>");
		print number_format($ldec_total_efectivo,2, ',', '.');
		print("</td>");		
		print("</tr>");	
		/*ob_flush();
		flush();			*/
		 if($ldec_total_efectivo>0&& $lb_valido)
		{ 
		//print 'devolucion'.$this->ls_cuentacaja.'-'.$this->ls_cuentaadelanto;
			$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaadelanto,'D',$ldec_total_efectivo,$ls_documento,$this->io_int_int->is_procedencia,"Adelantos Recibidos por Ventas de la tienda ".$_SESSION["ls_nomtienda"]);	
		if ($lb_valido)
		{
				$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentacaja,'H',$ldec_total_efectivo,$ls_documento,$this->io_int_int->is_procedencia,"Caja de la tienda  ".$_SESSION["ls_nomtienda"]);
		}	
		}
	}
	$ls_debe=$this->io_int_int->idec_monto_debe;
	$ls_haber=$this->io_int_int->idec_monto_haber;
	print ("<tr class='celdas-amarillas'>");
	print ("<td height='17' align='left' class='texto-azul'>Total Debe</td>");
	print ("<td height='17' align='right' class='texto-azul'>");								
	print number_format($ls_debe,2, ',', '.');
	print ("</td>");
	print("</tr>");	
	print ("<tr class='celdas-amarillas'>");
	print ("<td height='17' align='left' class='texto-azul'>Total Haber</td>");
	print ("<td height='17' align='right' class='texto-azul'>");								
	print number_format($ls_haber,2, ',', '.');
	print ("</td>");
	print("</tr>");	
	/*ob_flush();
	flush();*/
	if (doubleval(trim($this->io_int_int->idec_monto_debe))!=doubleval(trim($this->io_int_int->idec_monto_haber)))
	{
	$this->io_msg->message("Comprobante descuadrado, total Debe->".$ls_debe." total haber->".$ls_haber." Verifique Facturas de Contado");
	}
	return $lb_valido;	
}	


function uf_contabilizar_caso_contado($ls_documento)//Caso para facturas con el estatus de condicion de pago 1(Contado)
{
	$lb_valido=true;
	$this->is_conpag=1;
	$ldec_mondeb=0;
	$ldec_monhab=0;
	print ("<table width='415' border='0' align='center' cellpadding='0' cellspacing='0' class='formato-blanco'>");
	print ("<tr>");
	print ("<td height='17' colspan='2' align='right' class='titulo-ventana'>Caso Contado</td>");             
	/*ob_flush();
	flush();*/
	$this->uf_buscar_total_monto_facturacion($ldec_totalcontado,$ldec_total_retenido);//Busqueda del monto total de la  facturacion y del total IVA de la facturacion			
	$ldec_monto_contado=round(($ldec_totalcontado-$ldec_total_retenido),2);//Monto total de facturas menos el IVA				
	$this->uf_buscar_total_monto_caja($ldec_total_caja);//Monto de Efectivo y Cheques entregados en caja
	$this->uf_buscar_total_monto_banco($ldec_total_banco);//Monto de depositos y Notas de credito(Transferencias) en el banco 
	$this->uf_buscar_total_monto_cruce_adelantos($ldec_cruce_adelantos);//Monto de la aplicion de NC de sobrantesen facturas
	$this->uf_buscar_total_monto_adelantos($ldec_total_adelantos);//monto obtenido por NC de sobrantes en facturas.
	$this->uf_buscar_total_monto_carta($ldec_total_carta);//Monto de las carta ordenes  asociadas a las facturas
	$ldec_diferencia     =round($ldec_totalcontado-($ldec_total_caja+$ldec_total_banco),2);				
	$ldec_porcobrar      =round((($ldec_totalcontado+$ldec_total_adelantos)-round(($ldec_total_banco+$ldec_total_caja+
	$ldec_total_carta+$ldec_cruce_adelantos),2)),2);	
	$ldec_cobrado        = $ldec_total_caja+$ldec_total_banco+$ldec_total_adelantos+$ldec_cruce_adelantos;
	$ldec_devengado      = $ldec_totalcontado+$ldec_total_adelantos;
	//Por Cobrar
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total por Cobrar(D)</td>");
	print("<td align='right'>");
	print number_format($ldec_porcobrar,2, ',', '.');
	print("</td>");
	print("</tr>");
	/*ob_flush();
	flush();		*/
	//Caja
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total Caja(D)</td>");
	print("<td align='right'>");
	print number_format($ldec_total_caja,2, ',', '.');
	print("</td>");
	print("</tr>");	
	/*ob_flush();
	flush();								*/
	//*****************************Ingresos*****************************		
	//Asiento presupuestario de ingreso correspondiente al devengado y cobrado.				
	if($ldec_devengado>0 && $lb_valido)
	{
		$lb_valido=$this->io_int_int->uf_spi_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_spicuenta,'DEV',$ldec_devengado,$ls_documento,$this->io_int_int->is_procedencia,"DEVENGADO DE LA TIENDA ".$_SESSION["ls_nomtienda"]." POR CONCEPTO DE VENTAS DIARIAS");																														
	}
	if($ldec_cobrado>0 && $lb_valido)
	{
		$lb_valido=$this->io_int_int->uf_spi_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_spicuenta,'COB',$ldec_cobrado,$ls_documento,$this->io_int_int->is_procedencia,"COBRADO DE LA TIENDA ".$_SESSION["ls_nomtienda"]." POR CONCEPTO DE VENTAS DIARIAS");																														
	}								
	//***************************Contabilidad**************************
	if($ldec_porcobrar>0 && $lb_valido){
		$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaxcob,'D',$ldec_porcobrar,$ls_documento,$this->io_int_int->is_procedencia,"Cuentas por cobrar Clientes de la tienda ".$_SESSION["ls_nomtienda"]);
	}
	if($ldec_total_caja>0 && $lb_valido){//Asiento cuenta contable Caja .		
		$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentacaja,'D',$ldec_total_caja,$ls_documento,$this->io_int_int->is_procedencia,"Caja de la tienda ".$_SESSION["ls_nomtienda"]);	
	}
	if($ldec_total_banco>0 && $lb_valido)
	{//Asiento cuenta contable Banco.	
		$lb_valido_banco=$this->uf_cargar_movimientos_banco();
		if($lb_valido_banco && $lb_valido)
		{
			$li_totrow=$this->io_ds_banco->getRowCount('sc_cuenta');
			if($li_totrow>0)
			{					
				for($li_i=1;($li_i<=$li_totrow)&&($lb_valido);$li_i++)
				{
					$ls_cuenta=$this->io_ds_banco->data["sc_cuenta"][$li_i];
					$ld_montobanco=$this->io_ds_banco->data["total"][$li_i];
					$ls_banco=$this->io_ds_banco->data["dencta"][$li_i];						
					//print '<br> $this->ls_cuentabanco->'.$ls_cuenta.'<br>';
					//Caja
					print ("<tr class='celdas-grises'>");
					print("<td height='22' align='left'>Total Banco(D)");
					print($ls_banco);
					print ("</td>");
					print("<td align='right'>");
					print number_format($ld_montobanco,2, ',', '.');
					print("</td>");
					print("</tr>");
					/*ob_flush();
					flush();		*/
					if ($ld_montobanco>0 && $lb_valido)
					{
						$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$ls_cuenta,'D',$ld_montobanco,$ls_documento,$this->io_int_int->is_procedencia,"Banco ".$ls_banco." de la tienda ".$_SESSION["ls_nomtienda"]);
					}
				}	
			}
		}
	}
	//Cruce Adelantos
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total Cruce Adelantos(D)</td>");
	print("<td align='right'>");
	print number_format($ldec_cruce_adelantos,2, ',', '.');
	print("</td>");
	print("</tr>");	
	/*ob_flush();
	flush();*/
	if($ldec_cruce_adelantos>0 && $lb_valido)
	{//Asiento cuenta contable cruce Adelantos.
	//print '<br> $this->ls_cuentaadelanto->'.$this->ls_cuentaadelanto.'<br>';
		$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaadelanto,'D',$ldec_cruce_adelantos,$ls_documento,$this->io_int_int->is_procedencia,"Adelantos Recibidos por ventas de la tienda ".$_SESSION["ls_nomtienda"]);	
	}
	//Cruce Adelantos				
	if ($ldec_monto_contado>0 && $lb_valido)
	{
	//Asiento cuenta contable correspondiente a la presupuestaria de ingreso.
		$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentascg,'H',$ldec_monto_contado,$ls_documento,$this->io_int_int->is_procedencia,"Ingresos por la venta de insumos de ".$_SESSION["ls_nomtienda"]);
	}
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total Adelantos(H)</td>");
	print("<td align='right'>");
	print number_format($ldec_total_adelantos,2, ',', '.');
	print("</td>");
	print("</tr>");
	/*ob_flush();
	flush();	*/
	if($ldec_total_adelantos>0 && $lb_valido)
	{//Asiento cuenta contable Adelantos.
		$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaadelanto,'H',$ldec_total_adelantos,$ls_documento,$this->io_int_int->is_procedencia,"Adelantos de la tienda ".$_SESSION["ls_nomtienda"]);	
	}
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total IVA(H)</td>");
	print("<td align='right'>");
	print number_format($ldec_total_retenido,2, ',', '.');
	print("</td>");
	print("</tr>");
	/*ob_flush();
	flush();	*/
	if($ldec_total_retenido>0 && $lb_valido)
	{//Asiento contable correspondiente al Debito fiscal.
		$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaiva,'H',$ldec_total_retenido,$ls_documento,$this->io_int_int->is_procedencia,"Debito fiscal de la tienda ".$_SESSION["ls_nomtienda"]);
	}
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total Ingresos(D)</td>");
	print("<td align='right'>");
	print number_format($ldec_monto_contado,2, ',', '.');
	print("</td>");
	print("</tr>");
	print ("<tr class='celdas-grises'>");	
	print ("<td height='17' align='left' >Cobrado</td>");
	print ("<td height='17' align='right'>");
	print number_format($ldec_cobrado,2, ',', '.');
	print ("</td>");
	print("</tr>");
	print ("<tr class='celdas-grises'>");
	print ("<td height='17' align='left' >Devengado</td>");
	print ("<td height='17' align='right' >");
	print number_format($ldec_devengado,2, ',', '.');
	print ("</td>");
	print("</tr>");	
	print ("<tr class='celdas-amarillas'>");
	print("</tr>");	
	$ls_debe=$this->io_int_int->idec_monto_debe;
	$ls_haber=$this->io_int_int->idec_monto_haber;
	print ("<tr class='celdas-amarillas'>");
	print ("<td height='17' align='left' class='texto-azul'>Total Debe</td>");
	print ("<td height='17' align='right' class='texto-azul'>");								
	print number_format($ls_debe,2, ',', '.');
	print ("</td>");
	print("</tr>");	
	print ("<tr class='celdas-amarillas'>");
	print ("<td height='17' align='left' class='texto-azul'>Total Haber</td>");
	print ("<td height='17' align='right' class='texto-azul'>");								
	print number_format($ls_haber,2, ',', '.');
	print ("</td>");
	print("</tr>");	
	/*ob_flush();
	flush();*/
	if (doubleval(trim($this->io_int_int->idec_monto_debe))!=doubleval(trim($this->io_int_int->idec_monto_haber)))
	{
	$this->io_msg->message("Comprobante descuadrado, total Debe->".$ls_debe." total haber->".$ls_haber." Verifique Facturas de Contado");
	}
	return $lb_valido;
}    
function uf_contabilizar_caso_costo_venta($ls_documento)//Caso para facturas con el estatus de condicion de pago 1(Contado)
{
	$lb_valido=true;
	$ldec_mondeb=0;
	$ldec_monhab=0;
	$lb_valido_costos=$this->uf_cargar_costos_totales();
	if($lb_valido_costos && $lb_valido)
	{
		$li_totrow=$this->io_ds_costos->getRowCount('codart');
		//print $li_totrow;
		if($li_totrow>0)
		{		
		$ld_costototal=0;
			for($li_i=1;($li_i<=$li_totrow)&&($lb_valido);$li_i++)
			{
				$ls_codpro=$this->io_ds_costos->data["codart"][$li_i];
				$ld_costototal=$ld_costototal+$this->io_ds_costos->data["costtotal"][$li_i];			
			}	
		}
	}
	if ($ld_costototal>0 && $lb_valido)
	{
	$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuenta_costoventa,'D',$ld_costototal,$ls_documento,$this->io_int_int->is_procedencia,"COSTO DE VENTA DE LA TIENDA ".$_SESSION["ls_nomtienda"]);
	}
	if ($ld_costototal>0 && $lb_valido)
	{//Asiento cuenta contable cruce Adelantos.
	//print '<br> $this->ls_cuentaadelanto->'.$this->ls_cuentaadelanto.'<br>';
		$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuenta_inventario,'H',$ld_costototal,$ls_documento,$this->io_int_int->is_procedencia,"INVENTARIO DE LA TIENDA ".$_SESSION["ls_nomtienda"]);	
	}	
	print ("<tr>");
	print ("<td height='17' colspan='2' align='right' class='titulo-ventana'>Costo de Venta</td>");   
	print ("<tr  class='celdas-grises'>");
	print("<td height='22' align='left'>Total Costo de Ventas(D)</td>");
	print("<td align='right'>");
	print number_format($ld_costototal,2, ',', '.');
	print("</td>");
	print("</tr>"); 
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total Inventario(H)</td>");
	print("<td align='right'>");	
	print number_format($ld_costototal,2, ',', '.');
	print("</td>");
	print("</tr>");				
	print ("<tr class='celdas-amarillas'>");
	print("</tr>");	
	$ls_debe=$this->io_int_int->idec_monto_debe;
	$ls_haber=$this->io_int_int->idec_monto_haber;
	print ("<tr class='celdas-amarillas'>");
	print ("<td height='17' align='left' class='texto-azul'>Total Debe</td>");
	print ("<td height='17' align='right' class='texto-azul'>");								
	print number_format($ls_debe,2, ',', '.');
	print ("</td>");
	print("</tr>");	
	print ("<tr class='celdas-amarillas'>");
	print ("<td height='17' align='left' class='texto-azul'>Total Haber</td>");
	print ("<td height='17' align='right' class='texto-azul'>");								
	print number_format($ls_haber,2, ',', '.');
	print ("</td>");
	print("</tr>");	
	print ("</table>");
	/*ob_flush();
	flush();*/
	if (doubleval(trim($this->io_int_int->idec_monto_debe))!=doubleval(trim($this->io_int_int->idec_monto_haber)))
	{
	$this->io_msg->message("Comprobante descuadrado, total Debe->".$ls_debe." total haber->".$ls_haber." Verifique Costos de Venta");
	}				
	return $lb_valido;
}  	
	function uf_contabilizar_caso_costo_venta_devolucion($ls_documento)//Caso para facturas con el estatus de condicion de pago 1(Contado)
	{
				$lb_valido=true;
				$ldec_mondeb=0;
				$ldec_monhab=0;
				$lb_valido_costo=$this->uf_cargar_costos_totales_devolucion();
				if($lb_valido_costo && $lb_valido)
				{
				$li_totrow=$this->io_ds_costos->getRowCount('codart');
				//print $li_totrow;
				if($li_totrow>0)
				{		
				$ld_costototal=0;
					for($li_i=1;($li_i<=$li_totrow)&&($lb_valido);$li_i++)
					{
						$ls_codpro=$this->io_ds_costos->data["codart"][$li_i];
						$ld_costototal=$ld_costototal+$this->io_ds_costos->data["costtotal"][$li_i];		
					
					}	
				}
				}
				if ($ld_costototal>0 && $lb_valido)
				{
				$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuenta_costoventa,'H',$ld_costototal,$ls_documento,$this->io_int_int->is_procedencia,"COSTO DE VENTA DE LA TIENDA ".$_SESSION["ls_nomtienda"]);
				}
				if ($ld_costototal>0 && $lb_valido)
				{//Asiento cuenta contable cruce Adelantos.
				//print '<br> $this->ls_cuentaadelanto->'.$this->ls_cuentaadelanto.'<br>';
					$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuenta_inventario,'D',$ld_costototal,$ls_documento,$this->io_int_int->is_procedencia,"INVENTARIO DE LA TIENDA ".$_SESSION["ls_nomtienda"]);	
				}	
				print ("<tr class='celdas-grises'>");
                print("<td height='22' align='left'>Costo de Ventas(H)</td>");
                print("<td align='right'>");
				print number_format($ld_costototal,2, ',', '.');
				print("</td>");
                print("</tr>");
				print ("<tr class='celdas-grises'>");
                print("<td height='22' align='left'>Total Inventario(D)</td>");
                print("<td align='right'>");
				print number_format($ld_costototal,2, ',', '.');
				print("</td>");
                print("</tr>");			
				return $lb_valido;
	}   
	
function uf_contabilizar_caso_credito($ls_documento)//Caso para facturas con el estatus de condicion de pago 2(Credito)
{
		
	$lb_valido=true;
	$this->is_conpag=2;
	$ldec_mondeb=0;
	$ldec_monhab=0;
	$this->uf_buscar_total_monto_facturacion($ldec_totalcontado,$ldec_total_retenido);//Busqueda del monto total de la  facturacion y del total IVA de la facturacion
	$ldec_monto_contado=round(($ldec_totalcontado-$ldec_total_retenido),2);//Monto total de facturas menos el IVA
	$this->uf_buscar_total_monto_caja($ldec_total_caja);//Monto de Efectivo y Cheques entregados en caja
	print ("<tr class='celdas-grises'>");
	print ("<td height='17' colspan='2' align='right' class='titulo-ventana'>Caso Credito</td>"); 
	/*ob_flush();
	flush();  */
	$this->uf_buscar_total_monto_banco($ldec_total_banco);//Monto de depositos y Notas de credito(Transferencias) en el banco 
	$this->uf_buscar_total_monto_cruce_adelantos($ldec_cruce_adelantos);//Monto de la aplicion de NC de sobrantes en facturas
	$this->uf_buscar_total_monto_adelantos($ldec_total_adelantos);//monto obtenido por NC de sobrantes en facturas.
	$this->uf_buscar_total_monto_carta($ldec_total_carta);//Monto de las carta ordenes  asociadas a las facturas
	$ldec_porcobrar=round((($ldec_totalcontado+$ldec_total_adelantos)-round(($ldec_total_banco+$ldec_total_caja+$ldec_total_carta+$ldec_cruce_adelantos),2)),2);					
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total por Cobrar(D)</td>");
	print("<td align='right'>");
	print number_format($ldec_porcobrar,2, ',', '.');
	print("</td>");
	print("</tr>"); 	
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total Caja(D)</td>");
	print("<td align='right'>");
	print number_format($ldec_total_caja,2, ',', '.');
	print("</td>");
	print("</tr>");
	/*flush();
	ob_flush();*/
	//print "<br> POR COBRAR=".$ldec_porcobrar;
	$ldec_cobrado  =$ldec_total_caja+$ldec_total_banco+$ldec_total_adelantos+$ldec_cruce_adelantos;
	$ldec_devengado     =$ldec_totalcontado+$ldec_total_adelantos;				
	//print "<br> COBRADO=".$ldec_cobrado;
	//print "<br> DEVENGADO=".$ldec_devengado;					
	//*****************************Ingresos************************************						
	//Asiento presupuestario de ingreso correspondiente al devengado y cobrado.				
	if($ldec_devengado>0 && $lb_valido)
	{
		$lb_valido=$this->io_int_int->uf_spi_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_spicuenta,'DEV',$ldec_devengado,$ls_documento,$this->io_int_int->is_procedencia,"DEVENGADO DE LA TIENDA ".$_SESSION["ls_nomtienda"]." POR CONCEPTO DE VENTAS DIARIAS");																														
	}				
	if($ldec_cobrado>0 && $lb_valido)
	{
		$lb_valido=$this->io_int_int->uf_spi_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_spicuenta,'COB',$ldec_cobrado,$ls_documento,$this->io_int_int->is_procedencia,"COBRADO DE LA TIENDA ".$_SESSION["ls_nomtienda"]." POR CONCEPTO DE VENTAS DIARIAS");																														
	}
	//*****************************Contabilidad*******************************//
	//Asiento cuenta contable correspondiente a la presupuestaria de ingreso.
   if ($ldec_monto_contado>0 && $lb_valido)
   {
	$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentascg,'H',$ldec_monto_contado,$ls_documento,$this->io_int_int->is_procedencia,"Ingresos por la venta de insumos de ".$_SESSION["ls_nomtienda"]);
	}			
	if($ldec_total_caja>0 && $lb_valido){//Asiento cuenta contable Caja .
		$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentacaja,'D',$ldec_total_caja,$ls_documento,$this->io_int_int->is_procedencia,"Caja de la tienda ".$_SESSION["ls_nomtienda"]);	
	}
	if($ldec_cruce_adelantos>0&& $lb_valido){//Asiento cuenta contable cruce Adelantos.
		$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaadelanto,'D',$ldec_cruce_adelantos,$ls_documento,$this->io_int_int->is_procedencia,"Adelantos de la tienda ".$_SESSION["ls_nomtienda"]);	
	}
	if($ldec_total_banco>0 && $lb_valido)
	{//Asiento cuenta contable Banco. 	
		$lb_valido_banco=$this->uf_cargar_movimientos_banco();
		if($lb_valido_banco && $lb_valido)
		{
			$li_totrow=$this->io_ds_banco->getRowCount('sc_cuenta');
			if($li_totrow>0)
			{					
				for($li_i=1;($li_i<=$li_totrow)&&($lb_valido);$li_i++)
				{
					$ls_cuenta=$this->io_ds_banco->data["sc_cuenta"][$li_i];
					$ld_montobanco=$this->io_ds_banco->data["total"][$li_i];
					$ls_banco=$this->io_ds_banco->data["dencta"][$li_i];	
					print ("<tr class='celdas-grises'>");
					print("<td height='22' align='left'>Total Banco(D)");
					print ($ls_banco);
					print ("</td>");
					print("<td align='right'>");
					print number_format($ld_montobanco,2, ',', '.');
					print("</td>");
					print("</tr>"); 
					/*ob_flush();
					flush();*/
					if ($ld_montobanco>0 && $lb_valido)
					{
						$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$ls_cuenta,'D',$ld_montobanco,$ls_documento,$this->io_int_int->is_procedencia,"Banco ".$ls_banco." de la tienda ".$_SESSION["ls_nomtienda"]);
					}
				}	
			}
		}
	}
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total Cruce Adelantos(D)</td>");
	print("<td align='right'>");
	print number_format($ldec_cruce_adelantos,2, ',', '.');
	print("</td>");
	print("</tr>");
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total Adelantos(H)</td>");
	print("<td align='right'>");
	print number_format($ldec_total_adelantos,2, ',', '.');
	print("</td>");
	print("</tr>");
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total Iva(H)</td>");
	print("<td align='right'>");
	print number_format($ldec_total_retenido,2, ',', '.');
	print("</td>");
	print("</tr>");
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total Carta Orden(D)</td>");
	print("<td align='right'>");
	print number_format($ldec_total_carta,2, ',', '.');
	print("</td>");
	print("</tr>");
	/*ob_flush();
	flush();*/
	if($ldec_total_adelantos>0&& $lb_valido)
	{//Asiento cuenta contable Adelantos.
		$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaadelanto,'H',$ldec_total_adelantos,$ls_documento,$this->io_int_int->is_procedencia,"Adelantos de la tienda ".$_SESSION["ls_nomtienda"]);	
	}				
	if($ldec_total_carta>0&& $lb_valido)
	{//Asiento cuenta contable carta orden
		$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentacarta,'D',$ldec_total_carta,$ls_documento,$this->io_int_int->is_procedencia,"Carta Orden por cobrar de la tienda ".$_SESSION["ls_nomtienda"]);	
	}				
	if($ldec_total_retenido>0&& $lb_valido)
	{//Asiento contable correspondiente al Debito fiscal.
		$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaiva,'H',$ldec_total_retenido,$ls_documento,$this->io_int_int->is_procedencia,"Debito fiscal de la tienda ".$_SESSION["ls_nomtienda"]);
	}	 				
	if ($ldec_porcobrar>0 && $lb_valido)
	{
	//Asiento contable de la cuenta por cobrar	
	$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaxcob,'D',$ldec_porcobrar,$ls_documento,$this->io_int_int->is_procedencia,"Cuentas por cobrar Clientes de la tienda ".$_SESSION["ls_nomtienda"]);
	}
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Ingresos(H)</td>");
	print("<td align='right'>");
	print number_format($ldec_monto_contado,2, ',', '.');
	print("</td>");
	print("</tr>"); 
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left''>Cobrado</td>");
	print("<td align='right'>");
	print number_format($ldec_cobrado,2, ',', '.');
	print("</td>");
	print("</tr>"); 	
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Devengado</td>");
	print("<td align='right'>");
	print number_format($ldec_devengado,2, ',', '.');
	print("</td>");
	print("</tr>"); 
	print ("<tr class='celdas-amarillas'>");
	print("</tr>");	
	$ls_debe=$this->io_int_int->idec_monto_debe;
	$ls_haber=$this->io_int_int->idec_monto_haber;
	print ("<tr class='celdas-amarillas'>");
	print ("<td height='17' align='left' class='texto-azul'>Total Debe</td>");
	print ("<td height='17' align='right' class='texto-azul'>");								
	print number_format($ls_debe,2, ',', '.');
	print ("</td>");
	print("</tr>");	
	print ("<tr class='celdas-amarillas'>");
	print ("<td height='17' align='left' class='texto-azul'>Total Haber</td>");
	print ("<td height='17' align='right' class='texto-azul'>");								
	print number_format($ls_haber,2, ',', '.');
	print ("</td>");
	print("</tr>");
	/*ob_flush();
	flush();*/
	if (doubleval(trim($this->io_int_int->idec_monto_debe))!=doubleval(trim($this->io_int_int->idec_monto_haber)))
	{
		$this->io_msg->message("Comprobante descuadrado, total Debe->".$ls_debe." total haber->".$ls_haber." Verifique Facturas a Credito");
	}		
	return $lb_valido;
}    

function uf_contabilizar_caso_parcial($ls_documento)//Caso para facturas con el estatus de condicion de pago 3(Parcial)
{
	$lb_valido=true;
	$ldec_mondeb=0;
	$ldec_monhab=0;
	$this->is_conpag=3;
	$this->uf_buscar_total_monto_facturacion($ldec_totalcontado,$ldec_total_retenido);//Busqueda del monto total de la  facturacion y del total IVA de la facturacion
	$ldec_monto_contado=round(($ldec_totalcontado-$ldec_total_retenido),2);//Monto total de facturas menos el IVA
	print ("<tr>");
	print ("<td height='17' colspan='2' align='right' class='titulo-ventana'>Caso Parcial</td>");   
	$this->uf_buscar_total_monto_caja($ldec_total_caja);//Monto de Efectivo y Cheques entregados en caja
	$this->uf_buscar_total_monto_banco($ldec_total_banco);//Monto de depositos y Notas de credito(Transferencias) en el banco 
	$this->uf_buscar_total_monto_cruce_adelantos($ldec_cruce_adelantos);//Monto de la aplicion de NC de sobrantesen facturas
	$this->uf_buscar_total_monto_adelantos($ldec_total_adelantos);//monto obtenido por NC de sobrantes en facturas.
	$this->uf_buscar_total_monto_carta($ldec_total_carta);//Monto de las carta ordenes  asociadas a las facturas
	$ldec_diferencia=round($ldec_totalcontado-($ldec_total_caja+$ldec_total_banco),2);
	$ldec_porcobrar=round((($ldec_totalcontado+$ldec_total_adelantos)-round(($ldec_total_banco+$ldec_total_caja+$ldec_total_carta+$ldec_cruce_adelantos),2)),2);
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total por Cobrar(D)</td>");
	print("<td align='right'>");
	print number_format($ldec_porcobrar,2, ',', '.');
	print("</td>");
	print("</tr>");
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total Caja(D)</td>");
	print("<td align='right'>");
	print number_format($ldec_total_caja,2, ',', '.');
	print("</td>");
	print("</tr>");
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total Caja(D)</td>");
	print("<td align='right'>");
	print number_format($ldec_total_caja,2, ',', '.');
	print("</td>");
	print("</tr>");
	/*ob_flush();
	flush();*/
	$ldec_cobrado  =$ldec_total_caja+$ldec_total_banco+$ldec_total_adelantos+$ldec_cruce_adelantos;
	$ldec_devengado     =$ldec_totalcontado+$ldec_total_adelantos;				
	//*****************************Ingresos*****************************		
	//Asiento presupuestario de ingreso correspondiente al devengado y cobrado.
	if($ldec_devengado>0 && $lb_valido)
	{
		$lb_valido=$this->io_int_int->uf_spi_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_spicuenta,'DEV',$ldec_devengado,$ls_documento,$this->io_int_int->is_procedencia,"DEVENGADO DE LA TIENDA ".$_SESSION["ls_nomtienda"]." POR CONCEPTO DE VENTAS DIARIAS");																														
	}
	if($ldec_cobrado>0 && $lb_valido)
	{
		$lb_valido=$this->io_int_int->uf_spi_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_spicuenta,'COB',$ldec_cobrado,$ls_documento,$this->io_int_int->is_procedencia,"COBRADO DE LA TIENDA ".$_SESSION["ls_nomtienda"]." POR CONCEPTO DE VENTAS DIARIAS");																														
	}							
	//Asiento cuenta contable correspondiente a la presupuestaria de ingreso.
	if ($ldec_monto_contado>0 && $lb_valido)
	{
	$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentascg,'H',$ldec_monto_contado,$ls_documento,$this->io_int_int->is_procedencia,"Ingresos por la venta de insumos de ".$_SESSION["ls_nomtienda"]);
	}
	if($ldec_total_caja>0 && $lb_valido)
	{//Asiento cuenta contable Caja .
		$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentacaja,'D',$ldec_total_caja,$ls_documento,$this->io_int_int->is_procedencia,"Caja de la tienda ".$_SESSION["ls_nomtienda"]);	
	}
	if($ldec_total_banco>0 && $lb_valido)
	{//Asiento cuenta contable Banco.	
		$lb_valido_banco=$this->uf_cargar_movimientos_banco();
		if($lb_valido_banco && $lb_valido)
		{
			$li_totrow=$this->io_ds_banco->getRowCount('sc_cuenta');
			if($li_totrow>0)
			{					
				for($li_i=1;($li_i<=$li_totrow)&&($lb_valido);$li_i++)
				{
					$ls_cuenta=$this->io_ds_banco->data["sc_cuenta"][$li_i];
					$ld_montobanco=$this->io_ds_banco->data["total"][$li_i];
					$ls_banco=$this->io_ds_banco->data["dencta"][$li_i];						
					print ("<tr class='celdas-grises'>");
					print("<td height='22' align='left'>Total Banco(D)");
					print ($ls_banco);
					print("</td>");
					print("<td align='right'>");
					print number_format($ld_montobanco,2, ',', '.');
					print("</td>");
					print("</tr>");
				/*	ob_flush();
					flush();*/
					if ($ld_montobanco>0 && $lb_valido)
					{
						$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$ls_cuenta,'D',$ld_montobanco,$ls_documento,$this->io_int_int->is_procedencia,"Banco ".$ls_banco." de la tienda ".$_SESSION["ls_nomtienda"]);
					}
				}	
			}
		}
	}
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total Cruce Adelantos(D)</td>");
	print("<td align='right'>");
	print number_format($ldec_cruce_adelantos,2, ',', '.');
	print("</td>");
	print("</tr>");
	/*ob_flush();
	flush();*/
	if($ldec_cruce_adelantos>0&& $lb_valido)
	{//Asiento cuenta contable cruce Adelantos.
		$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaadelanto,'D',$ldec_cruce_adelantos,$ls_documento,$this->io_int_int->is_procedencia,"Adelantos de la tienda ".$_SESSION["ls_nomtienda"]);	
	}
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total Adelantos(H)</td>");
	print("<td align='right'>");
	print number_format($ldec_total_adelantos,2, ',', '.');
	print("</td>");
	print("</tr>");
	/*ob_flush();
	flush();*/
	if($ldec_total_adelantos>0&& $lb_valido)
	{//Asiento cuenta contable Adelantos.
		$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaadelanto,'H',$ldec_total_adelantos,$ls_documento,$this->io_int_int->is_procedencia,"Adelantos de la tienda ".$_SESSION["ls_nomtienda"]);	
	}
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total IVA(H)</td>");
	print("<td align='right'>");
	print number_format($ldec_total_retenido,2, ',', '.');
	print("</td>");
	print("</tr>");
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total Carta Orden(D)</td>");
	print("<td align='right'>");
	print number_format($ldec_total_carta,2, ',', '.');
	print("</td>");
	print("</tr>");
	/*ob_flush();
	flush();*/
	if($ldec_total_carta>0&& $lb_valido)
	{//Asiento cuenta contable carta orden
		$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentacarta,'D',$ldec_total_carta,$ls_documento,$this->io_int_int->is_procedencia,"Carta Orden por cobrar de la tienda ".$_SESSION["ls_nomtienda"]);	
	}				
	if($ldec_total_retenido>0&& $lb_valido)
	{//Asiento contable correspondiente al Debito fiscal.
		$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaiva,'H',$ldec_total_retenido,$ls_documento,$this->io_int_int->is_procedencia,"Debito fiscal de la tienda ".$_SESSION["ls_nomtienda"]);
	}				
	if ($ldec_porcobrar>0 && $lb_valido)
	{	
	//Asiento contable de la cuenta por cobrar	
		$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaxcob,'D',$ldec_porcobrar,$ls_documento,$this->io_int_int->is_procedencia,"Cuentas por cobrar Clientes de la tienda ".$_SESSION["ls_nomtienda"]);
	}
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total Ingresos(H)</td>");
	print("<td align='right'>");
	print number_format($ldec_monto_contado,2, ',', '.');
	print("</td>");
	print("</tr>"); 	
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Cobrado</td>");
	print("<td align='right'>");
	print number_format($ldec_cobrado,2, ',', '.');
	print("</td>");
	print("</tr>"); 	
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Devengado</td>");
	print("<td align='right'>");
	print number_format($ldec_devengado,2, ',', '.');
	print("</td>");
	print("</tr>"); 
	print ("<tr class='celdas-amarillas'>");
	print("</tr>");	
	$ls_debe=$this->io_int_int->idec_monto_debe;
	$ls_haber=$this->io_int_int->idec_monto_haber;
	print ("<tr class='celdas-amarillas'>");
	print ("<td height='17' align='left' class='texto-azul'>Total Debe</td>");
	print ("<td height='17' align='right' class='texto-azul'>");								
	print number_format($ls_debe,2, ',', '.');
	print ("</td>");
	print("</tr>");	
	print ("<tr class='celdas-amarillas'>");
	print ("<td height='17' align='left' class='texto-azul'>Total Haber</td>");
	print ("<td height='17' align='right' class='texto-azul'>");								
	print number_format($ls_haber,2, ',', '.');
	print ("</td>");
	print("</tr>");
/*	ob_flush();
	flush();*/
	if (doubleval(trim($this->io_int_int->idec_monto_debe))!=doubleval(trim($this->io_int_int->idec_monto_haber)))
	{
		$this->io_msg->message("Comprobante descuadrado, total Debe->".$ls_debe." total haber->".$ls_haber." Verifique Facturas con pagos parciales");
	}				
	return $lb_valido;
}    

function uf_contabilizar_caso_carta_orden($ls_documento)//Caso para facturas con el estatus de condicion de pago 4(Carta Orden)
{
	$lb_valido=true;
	$this->is_conpag=4;
	$this->uf_buscar_total_monto_facturacion($ldec_totalcontado,$ldec_total_retenido);//Busqueda del monto total de la  facturacion y del total IVA de la facturacion
	$ldec_monto_contado=round(($ldec_totalcontado-$ldec_total_retenido),2);//Monto total de facturas menos el IVA
	$this->uf_buscar_total_monto_caja($ldec_total_caja);//Monto de Efectivo y Cheques entregados en caja
	print ("<tr>");
	print ("<td height='17' colspan='2' align='right' class='titulo-ventana'>Caso Carta Orden</td>");
	/*ob_flush();
	flush();   */
	$this->uf_buscar_total_monto_banco($ldec_total_banco);//Monto de depositos y Notas de credito(Transferencias) en el banco   
	$this->uf_buscar_total_monto_cruce_adelantos($ldec_cruce_adelantos);//Monto de la aplicion de NC de sobrantesen facturas
	$this->uf_buscar_total_monto_adelantos($ldec_total_adelantos);//monto obtenido por NC de sobrantes en facturas.
	$this->uf_buscar_total_monto_carta($ldec_total_carta);//Monto de las carta ordenes  asociadas a las facturas
	$ldec_diferencia=round($ldec_totalcontado-($ldec_total_caja+$ldec_total_banco),2);				
	//print "<br>MONTOCONTADO=".$ldec_monto_contado;				
	$ldec_porcobrar=round((($ldec_totalcontado+$ldec_total_adelantos)-round(($ldec_total_banco+$ldec_total_caja+$ldec_total_carta+$ldec_cruce_adelantos),2)),2);
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total por Cobrar(D)</td>");
	print("<td align='right'>");
	print number_format($ldec_porcobrar,2, ',', '.');
	print("</td>");
	print("</tr>");
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total Caja(D)</td>");
	print("<td align='right'>");
	print number_format($ldec_total_caja,2, ',', '.');
	print("</td>");
	print("</tr>"); 
	/*ob_flush();
	flush(); */
	$ldec_diferencia=round($ldec_totalcontado-$ldec_porcobrar,2);
	$ldec_cobrado  =$ldec_total_caja+$ldec_total_banco+$ldec_total_adelantos+$ldec_cruce_adelantos;
	$ldec_devengado     =$ldec_totalcontado+$ldec_total_adelantos;
	//*****************************Ingresos*****************************					
	//Asiento presupuestario de ingreso correspondiente al devengado y cobrado.
	if($ldec_devengado>0 && $lb_valido)
	{
		$lb_valido=$this->io_int_int->uf_spi_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_spicuenta,'DEV',$ldec_devengado,$ls_documento,$this->io_int_int->is_procedencia,"DEVENGADO DE LA TIENDA ".$_SESSION["ls_nomtienda"]." POR CONCEPTO DE VENTAS DIARIAS");																														
	}	
	if($ldec_cobrado>0 && $lb_valido)
	{
		$lb_valido=$this->io_int_int->uf_spi_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_spicuenta,'COB',$ldec_cobrado,$ls_documento,$this->io_int_int->is_procedencia,"COBRADO DE LA TIENDA ".$_SESSION["ls_nomtienda"]." POR CONCEPTO DE VENTAS DIARIAS");																														
	}
							
	//***********************************Contabilidad**********************************//
	//Asiento contable de la cuenta por cobrar	
	if ($ldec_porcobrar>0 && $lb_valido)
	{
	$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaxcob,'D',$ldec_porcobrar,$ls_documento,$this->io_int_int->is_procedencia,"Cuentas por cobrar Clientes de la tienda ".$_SESSION["ls_nomtienda"])	;
	}
	if($ldec_total_caja>0 && $lb_valido)
	{//Asiento cuenta contable Caja .
		$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentacaja,'D',$ldec_total_caja,$ls_documento,$this->io_int_int->is_procedencia,"Caja de la tienda ".$_SESSION["ls_nomtienda"]);	
	}					
	if($ldec_total_banco>0 && $lb_valido)
	{//Asiento cuenta contable Banco.				
		$lb_valido_banco=$this->uf_cargar_movimientos_banco();
		if($lb_valido_banco && $lb_valido)
		{
			$li_totrow=$this->io_ds_banco->getRowCount('sc_cuenta');
			if($li_totrow>0)
			{					
				for($li_i=1;($li_i<=$li_totrow)&&($lb_valido);$li_i++)
				{
					$ls_cuenta=$this->io_ds_banco->data["sc_cuenta"][$li_i];
					$ld_montobanco=$this->io_ds_banco->data["total"][$li_i];
					$ls_banco=$this->io_ds_banco->data["dencta"][$li_i];						
					print ("<tr class='celdas-grises'>");
					print("<td height='22' align='left'>Total Banco(D)");
					print ($ls_banco);
					print("</td>");
					print("<td align='right'>");
					print number_format($ldec_total_caja,2, ',', '.');
					print("</td>");
					print("</tr>");
				/*	ob_flush();
					flush();  */
					if ($ld_montobanco>0 && $lb_valido)
					{
						$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$ls_cuenta,'D',$ld_montobanco,$ls_documento,$this->io_int_int->is_procedencia,"Banco ".$ls_banco." de la tienda ".$_SESSION["ls_nomtienda"]);
					}
				}	
			}
		}
	}
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total Cruce Adelanto(D)</td>");
	print("<td align='right'>");
	print number_format($ldec_cruce_adelantos,2, ',', '.');
	print("</td>");
	print("</tr>"); 
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total Adelanto(H)</td>");
	print("<td align='right'>");
	print number_format($ldec_total_adelantos,2, ',', '.');
	print("</td>");
	print("</tr>");   
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total IVA(H)</td>");
	print("<td align='right'>");
	print number_format($ldec_total_retenido,2, ',', '.');
	print("</td>");
	print("</tr>");
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total Carta Orden(D)</td>");
	print("<td align='right'>");
	print number_format($ldec_total_carta,2, ',', '.');
	print("</td>");
	print("</tr>"); 
	/*ob_flush();
	flush();   */
	if($ldec_cruce_adelantos>0&& $lb_valido)
	{//Asiento cuenta contable cruce Adelantos.
		$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaadelanto,'D',$ldec_cruce_adelantos,$ls_documento,$this->io_int_int->is_procedencia,"Adelantos de la tienda ".$_SESSION["ls_nomtienda"]);	
	}
	if($ldec_total_carta>0&& $lb_valido)
	{//Asiento cuenta contable carta orden
		$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentacarta,'D',$ldec_total_carta,$ls_documento,$this->io_int_int->is_procedencia,"Carta orden por cobrar de la tienda ".$_SESSION["ls_nomtienda"]);	
	}
	if ($ldec_monto_contado>0 && $lb_valido)
	{				
	//Asiento cuenta contable correspondiente a la presupuestaria de ingreso. 				
	$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentascg,'H',$ldec_monto_contado,$ls_documento,$this->io_int_int->is_procedencia,"Ingresos por la venta de insumos de ".$_SESSION["ls_nomtienda"]);
	}
	if($ldec_total_adelantos>0&& $lb_valido)
	{//Asiento cuenta contable Adelantos.
		$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaadelanto,'H',$ldec_total_adelantos,$ls_documento,$this->io_int_int->is_procedencia,"Adelantos de la tienda ".$_SESSION["ls_nomtienda"]);	
	}				
	if($ldec_total_retenido>0&& $lb_valido)
	{//Asiento contable correspondiente al Debito fiscal.
		$lb_valido=$this->io_int_int->uf_scg_insert_datastore($_SESSION["la_empresa"]["codemp"],$this->ls_cuentaiva,'H',$ldec_total_retenido,$ls_documento,$this->io_int_int->is_procedencia,"Debito fiscal de la tienda ".$_SESSION["ls_nomtienda"]);
	}
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Total Ingresos(H)</td>");
	print("<td align='right'>");
	print number_format($ldec_monto_contado,2, ',', '.');
	print("</td>");
	print("</tr>"); 	
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Cobrado</td>");
	print("<td align='right'>");
	print number_format($ldec_cobrado,2, ',', '.');
	print("</td>");
	print("</tr>"); 	
	print ("<tr class='celdas-grises'>");
	print("<td height='22' align='left'>Devengado</td>");
	print("<td align='right'>");
	print number_format($ldec_devengado,2, ',', '.');
	print("</td>");
	print("</tr>"); 	
	print ("<tr class='celdas-amarillas'>");
	print("</tr>");	
	$ls_debe=$this->io_int_int->idec_monto_debe;
	$ls_haber=$this->io_int_int->idec_monto_haber;
	print ("<tr class='celdas-amarillas'>");
	print ("<td height='17' align='left' class='texto-azul'>Total Debe</td>");
	print ("<td height='17' align='right' class='texto-azul'>");								
	print number_format($ls_debe,2, ',', '.');
	print ("</td>");
	print("</tr>");	
	print ("<tr class='celdas-amarillas'>");
	print ("<td height='17' align='left' class='texto-azul'>Total Haber</td>");
	print ("<td height='17' align='right' class='texto-azul'>");								
	print number_format($ls_haber,2, ',', '.');
	print ("</td>");
	print("</tr>");	
	/*ob_flush();
	flush();*/
	if (doubleval(trim($this->io_int_int->idec_monto_debe))!=doubleval(trim($this->io_int_int->idec_monto_haber)))
	{
	$this->io_msg->message("Comprobante descuadrado, total Debe->".$ls_debe." total haber->".$ls_haber." Verifique Facturas con Cartas Ordenes");
	}			
	return $lb_valido;				
}    
function uf_buscar_total_monto_facturacion(&$ldec_total,&$ldec_total_retenido)//Busco el total obtenido por pagos de factura
{
$ldec_total=0;	
$ls_sql="SELECT COALESCE(SUM(monto),0) as total,COALESCE(SUM(montoret),0) as retenido ".
		"FROM  sfc_factura WHERE conpag='".$this->is_conpag."' AND estfaccon<>'A' ". 
		"AND substr(fecemi,0,11) ilike '".$this->ls_feccie."'";					
//print 'FACTURACION*******>'.$ls_sql;						
$rs_data=$this->io_sql->select($ls_sql);
if($rs_data==false)
{
	$lb_valido=false;
	$this->io_msgc="Error en uf_buscar_total_monto_facturacion ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
}
else
{
	if($row=$this->io_sql->fetch_row($rs_data))
	{
		$ldec_total=$row["total"];		
		$ldec_total_retenido=$row["retenido"];
	}		
	$this->io_sql->free_result($rs_data);
}	
return $lb_valido;		
}	

function uf_buscar_total_monto_caja(&$ldec_total)//Busco el total obtenido por pagos en Efectivo o Cheque
{
	$ldec_total=0;	
	$ls_sql="SELECT COALESCE(SUM(sfc_instpago.monto),0) as total ".
			"FROM  sfc_factura,sfc_instpago,sfc_formapago WHERE sfc_factura.conpag='".$this->is_conpag."' ".
			"AND sfc_factura.estfaccon<>'A' AND ((sfc_formapago.codforpag='01' AND sfc_formapago.metforpag='C') ".
			" OR (sfc_formapago.codforpag='02' AND sfc_formapago.metforpag='H')) ".
			"AND sfc_instpago.numinst NOT like 'NC%' AND sfc_instpago.codforpag=sfc_formapago.codforpag ".
			"AND sfc_factura.numfac=sfc_instpago.numfac AND  substr(sfc_factura.fecemi,0,11) ilike '".$this->ls_feccie."'";
	//print '<br>total_monto_caja->'.$ls_sql.'<br>'; 
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$lb_valido=false;
		$this->io_msgc="Error en uf_buscar_total_monto_caja ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$ldec_total=$row["total"];		
		}		
		$this->io_sql->free_result($rs_data);
	}	
	return $lb_valido;		
}	

	function uf_buscar_total_monto_contado_efectivo(&$ldec_total)//Busco el total obtenido por pagos en efectivo 
	{
			$ldec_total=0;	
			$ls_sql="SELECT COALESCE(SUM(sfc_instpago.monto),0) as total ".
					" FROM  sfc_factura,sfc_instpago,sfc_formapago ".
					" WHERE sfc_factura.conpag='".$this->is_conpag."' AND sfc_factura.estfaccon<>'A' ".
					" AND (sfc_formapago.metforpag='C' OR sfc_formapago.metforpag='H') ".
					" AND sfc_instpago.numinst NOT like 'NC%' AND sfc_instpago.codforpag=sfc_formapago.codforpag  ".
					" AND sfc_factura.numfac=sfc_instpago.numfac ".
					" AND  substr(sfc_factura.fecemi,0,11) ilike '".$this->ls_feccie."'"; 
							
			//print 'uf_buscar_total_monto_contado_efectivo->'.$ls_sql.'<br><br>';
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data==false)
			{
				$lb_valido=false;
				$this->io_msgc="Error en uf_buscar_total_monto_contado_efectivo ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$ldec_total=$row["total"];		
				}		
				$this->io_sql->free_result($rs_data);
			}	
			return $lb_valido;		
	}	

function uf_buscar_total_monto_adelantos_efectivo(&$ldec_total)//Busco el total obtenido de adelantos en efectivo recibidos por sobrante en factura
{
	$ldec_total=0;	
	$ls_sql="SELECT COALESCE(SUM(sfc_nota.monto),0) as total ".
			" FROM  sfc_nota,sfc_factura,sfc_instpago ".
			" WHERE  sfc_nota.nro_documento like 'FAC%' AND sfc_nota.tipnot='CXP' AND ".
			" sfc_factura.conpag='".$this->is_conpag."' AND sfc_factura.estfaccon<>'A'  ".
			" AND sfc_instpago.codforpag='01'  AND sfc_nota.estnota<>'C'".
			" AND sfc_nota.nro_documento=sfc_factura.numfac AND sfc_factura.numfac= sfc_instpago.numfac ".
			" AND  substr(sfc_factura.fecemi,0,11) ilike '".$this->ls_feccie."' AND sfc_instpago.monto>sfc_nota.monto"; 
			//print 'uf_buscar_total_monto_adelantos_efectivo-->'.$ls_sql.'<br><br>';
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$lb_valido=false;
		$this->io_msgc="Error en uf_buscar_total_monto_adelantos_efectivo ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$ldec_total=$row["total"];		
		}		
		$this->io_sql->free_result($rs_data);
	}	
	return $lb_valido;		
}

function uf_buscar_total_monto_banco(&$ldec_total)//Busco el total obtenido de los depositos o transferencias utilizados como instrumento de pago
{
	$ldec_total=0;	
	$ls_sql="SELECT COALESCE(SUM(sfc_instpago.monto),0) as total ".
			"FROM  sfc_factura,sfc_instpago,sfc_formapago WHERE sfc_factura.conpag='".$this->is_conpag."' ".
			"AND sfc_factura.estfaccon<>'A' AND ((sfc_formapago.codforpag='07' AND sfc_formapago.metforpag='D') ".
			" OR (sfc_formapago.codforpag='05' AND sfc_formapago.metforpag='B')) ".
			"AND sfc_instpago.numinst NOT like 'NC%' AND sfc_instpago.codforpag=sfc_formapago.codforpag ".
			"AND sfc_factura.numfac=sfc_instpago.numfac AND substr(sfc_factura.fecemi,0,11) ilike '".$this->ls_feccie."'"; 
	//print '<br>total_monto_banco->'.$ls_sql.'<br>';
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$lb_valido=false;
		$this->io_msgc="Error en uf_buscar_total_monto_banco ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$ldec_total=$row["total"];		
		}		
		$this->io_sql->free_result($rs_data);
	}	
	return $lb_valido;		
}

function uf_cargar_movimientos_banco()//Busco el total obtenido de los depositos o transferencias utilizados como instrumento de pago
{					
	$ls_sql="SELECT COALESCE(SUM(sfc_instpago.monto),0) as total,scb_ctabanco.sc_cuenta,scb_banco.nomban as dencta ".
		"FROM  sfc_factura,sfc_instpago,sfc_formapago,scb_ctabanco,scb_banco WHERE sfc_factura.conpag='".$this->is_conpag."' ".
			"AND sfc_factura.estfaccon<>'A' AND ((sfc_formapago.codforpag='07' AND sfc_formapago.metforpag='D') OR ". 
			" (sfc_formapago.codforpag='05' AND sfc_formapago.metforpag='B')) ".
			"AND sfc_instpago.numinst NOT like 'NC%' AND sfc_instpago.codforpag=sfc_formapago.codforpag ".
			"AND sfc_factura.numfac=sfc_instpago.numfac AND substr(sfc_factura.fecemi,0,11) ilike '".$this->ls_feccie."'".
			"AND scb_ctabanco.codemp=sfc_factura.codemp AND scb_ctabanco.codemp=sfc_instpago.codemp AND ".
			"scb_ctabanco.codban=sfc_instpago.codban AND scb_ctabanco.ctaban=sfc_instpago.ctaban ".
			" AND scb_banco.codemp=sfc_factura.codemp AND scb_banco.codemp=sfc_instpago.codemp AND ".
			" scb_banco.codemp=scb_ctabanco.codemp AND scb_banco.codban=scb_ctabanco.codban ".
			"GROUP BY scb_ctabanco.sc_cuenta,scb_banco.nomban;";  
	//print '<br>total_monto_banco->'.$ls_sql.'<br>';
	$rs_data=$this->io_sql->select($ls_sql);
	if ($rs_data===false)
	{
		$lb_valido=false;
		$this->io_msgc="Error en uf_cargar_movimientos_banco ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$this->io_ds_banco->data=$this->io_sql->obtener_datos($rs_data);
			$lb_valido=true;
		}
		$this->io_sql->free_result($rs_data);
	}
	return $lb_valido;	
}
function uf_cargar_costos_totales_devolucion()//Busco el costo total obtenido por concepto de devolucion
{					
	
	if ($this->is_conpag=='1')
	{
		$ls_sql="SELECT p.codart,a.denart,ROUND(CAST(COALESCE((SUM(d.candev)*p.cosproart),0)AS NUMERIC),2) as costtotal FROM sfc_detfactura d,sfc_producto p,sfc_factura f,
sim_articulo a WHERE d.codemp=p.codemp AND d.codemp=a.codemp AND d.codart=p.codart AND a.codart=p.codart AND d.codtiend=p.codtiend AND p.codemp=a.codemp 
 AND a.codart=d.codart  AND d.numfac IN (SELECT numfac FROM sfc_devolucion where estdev<>'A' AND substr(sfc_devolucion.fecdev,0,11) ilike '".$this->ls_feccie."') AND f.conpag='".$this->is_conpag."' AND 
 f.codemp=d.codemp AND f.codtiend=d.codtiend AND f.numfac=d.numfac AND f.codemp=p.codemp AND f.codtiend=p.codtiend 
  GROUP BY p.cosproart,P.CODART,a.denart"; 
 }
 else
 {
 $ls_sql="SELECT p.codart,a.denart,ROUND(CAST(COALESCE((SUM(d.candev)*p.cosproart),0)AS NUMERIC),2) as costtotal FROM sfc_detfactura d,sfc_producto p,sfc_factura f,
sim_articulo a WHERE d.codemp=p.codemp AND d.codemp=a.codemp AND d.codart=p.codart AND a.codart=p.codart AND d.codtiend=p.codtiend AND p.codemp=a.codemp 
 AND a.codart=d.codart  AND d.numfac IN (SELECT numfac FROM sfc_devolucion where estdev<>'A' AND substr(sfc_devolucion.fecdev,0,11) ilike '".$this->ls_feccie."') AND (f.conpag ='".$this->is_conpag."' OR f.conpag ='".$this->is_conpag_1."') AND 
 f.codemp=d.codemp AND f.codtiend=d.codtiend AND f.numfac=d.numfac AND f.codemp=p.codemp AND f.codtiend=p.codtiend 
  GROUP BY p.cosproart,P.CODART,a.denart"; 
 
 }
 
		//print '<br>total_costo_venta devolucion->'.$ls_sql.'<br>';
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_cargar_movimientos_banco ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->io_ds_costos->data=$this->io_sql->obtener_datos($rs_data);
				//print 'paso';
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;	
	}

function uf_cargar_costos_totales()//Busco el total obtenido de los depositos o transferencias utilizados como instrumento de pago
{
	$ls_sql="SELECT d.codart,a.denart,ROUND(CAST(COALESCE((SUM(d.canpro)*d.costo),0)AS NUMERIC),2) as costtotal ".
			"FROM sfc_detfactura d,sim_articulo a WHERE d.codemp=a.codemp AND ".
			"a.codart=d.codart  AND d.numfac IN (SELECT numfac FROM sfc_factura where estfaccon<>'A' AND ".
			"substr(sfc_factura.fecemi,0,11) ilike '".$this->ls_feccie."') GROUP BY d.codart,a.denart,d.costo"; 
	//print '<br>total_costo_venta devolucion->'.$ls_sql.'<br>';
	$rs_data=$this->io_sql->select($ls_sql);
	if ($rs_data===false)
	{
		$lb_valido=false;
		$this->io_msgc="Error en uf_cargar_movimientos_banco ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$this->io_ds_costos->data=$this->io_sql->obtener_datos($rs_data);
//				print 'paso';
			$lb_valido=true;
		}
		$this->io_sql->free_result($rs_data);
	}
	return $lb_valido;	
}

function uf_buscar_total_monto_cruce_adelantos(&$ldec_total)//Busco el total obtenido de la utilizacion de una Nota de Credito como instrumento de pago en una factura
{
	$ldec_total=0;	
	$ls_sql="SELECT COALESCE(SUM(sfc_instpago.monto),0) as total  ".
			"FROM  sfc_factura,sfc_instpago,sfc_formapago WHERE sfc_factura.conpag='".$this->is_conpag."' ".
			"AND (sfc_formapago.metforpag='D' OR sfc_formapago.metforpag='B') ".
			"AND sfc_instpago.numinst like 'NC%' AND sfc_factura.estfaccon<>'A' AND ".
			"sfc_instpago.codforpag=sfc_formapago.codforpag ".
			"AND sfc_factura.numfac=sfc_instpago.numfac AND substr(sfc_factura.fecemi,0,11) ilike '".$this->ls_feccie."'"; 
	//print '<br>'.$ls_sql.'<br>'; 
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$lb_valido=false;
		$this->io_msgc="Error en uf_buscar_total_monto_cruce_adelantos ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$ldec_total=$row["total"];		
		}		
		$this->io_sql->free_result($rs_data);
	}	
	return $lb_valido;		
}
	
		
function uf_buscar_total_monto_adelantos(&$ldec_total)//Busco el total de los adelantos recibidos por sobrante en factura
{
	$ldec_total=0;	
	$ls_sql="SELECT COALESCE(SUM(sfc_nota.monto),0) as total  ".
			"FROM  sfc_nota,sfc_factura WHERE  sfc_nota.nro_documento like 'FAC%' AND sfc_nota.tipnot='CXP' ".
			"AND sfc_factura.conpag='".$this->is_conpag."' AND sfc_factura.estfaccon<>'A'  ".
			"AND sfc_nota.nro_documento=sfc_factura.numfac AND substr(sfc_factura.fecemi,0,11) ilike '".$this->ls_feccie."'" ; 
	//print '<br>total_monto_adelantos->'.$ls_sql.'<br>'; 
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$lb_valido=false;
		$this->io_msgc="Error en uf_buscar_total_monto_adelantos ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$ldec_total=$row["total"];		
		}		
		$this->io_sql->free_result($rs_data);
	}	
	return $lb_valido;		
}	
function uf_buscar_total_monto_carta(&$ldec_total)//Busco el total obtenido por la implementacion de una carta orden
{
	$ldec_total=0;	
	$ls_sql="SELECT COALESCE(SUM(sfc_nota.monto),0) as total ".
			"FROM  sfc_nota,sfc_factura WHERE  sfc_nota.numnot NOT like 'FAC%' AND sfc_nota.tipnot='CXC' ".
			"AND sfc_factura.conpag='".$this->is_conpag."' AND sfc_factura.estfaccon<>'A' ".
			"AND sfc_factura.numfac=sfc_nota.nro_documento AND substr(sfc_factura.fecemi,0,11) ilike '".$this->ls_feccie."'" ; 
	//print '<br>total_monto_carta->'.$ls_sql;
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$lb_valido=false;
		$this->io_msgc="Error en uf_buscar_total_monto_carta ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$ldec_total=$row["total"];		
		}		
		$this->io_sql->free_result($rs_data);
	}	
	return $lb_valido;		
}
}/*FIN DE LA CLASE */
?>
