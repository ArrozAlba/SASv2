<?Php
class sigesp_cxp_c_cmp_islr
{
    var $io_function;
    var $la_empresa;
	var $ls_codusu;
    var $io_sql;
    var $io_msg;
    var $io_fec;
	var $io_seguridad;
	
	function sigesp_cxp_c_cmp_islr($as_path)
	{
      	require_once($as_path."shared/class_folder/sigesp_include.php");
	    require_once($as_path."shared/class_folder/class_sql.php");
	    require_once($as_path."shared/class_folder/class_funciones.php");
	    require_once($as_path."shared/class_folder/class_mensajes.php");
        require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
	    require_once($as_path."shared/class_folder/class_fecha.php");
	    require_once($as_path."shared/class_folder/class_datastore.php");
		require_once($as_path."shared/class_folder/sigesp_c_generar_consecutivo.php");
		$this->io_keygen= new sigesp_c_generar_consecutivo();
         
		$io_include=new sigesp_include();
	    $io_connect=$io_include->uf_conectar();
        $this->io_seguridad= new sigesp_c_seguridad();
	    $this->io_sql= new class_sql($io_connect);	
	    $this->io_function= new class_funciones();
	    $this->io_msg= new class_mensajes();
	    $this->io_fec= new class_fecha();
		$this->DS=new class_datastore();
		$this->ds_detalle=new class_datastore();
		$this->la_empresa= $_SESSION["la_empresa"];
		$this->ls_codusu= $_SESSION["la_logusr"];
        $this->ls_basdatcmp=$_SESSION["la_empresa"]["basdatcmp"];
		if($this->ls_basdatcmp!="")
		{
			$io_include->uf_obtener_parametros_conexion($as_path,$this->ls_basdatcmp,&$as_hostname,&$as_login,
														&$as_password,&$as_gestor);
			if($as_hostname!="")
			{
				$this->io_keygen->io_conexion=$io_include->uf_conectar_otra_bd($as_hostname, $as_login, $as_password,
																			   $this->ls_basdatcmp,$as_gestor);
				$this->io_keygen->io_sql=new class_sql($this->io_keygen->io_conexion);
				$io_connectaux=$io_include->uf_conectar_otra_bd($as_hostname, $as_login, $as_password,
																$this->ls_basdatcmp,$as_gestor);
				$this->io_sqlaux=new class_sql($io_connectaux);
			}
			else
			{
				$this->io_msg->message("Esta mal configurada la BD integradora");
				print "<script language=JavaScript>";
				print "location.href='sigespwindow_blank.php'";
				print "</script>";		
			}
		}
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}
	//---------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_comprobantes_islr($as_comprobantes,$as_procedencias,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_procesar_comprobantes_islar
		//		   Access: private 
		//	    Arguments: as_comprobantes // Números de comprobantes a registrar
		//	    		   as_procedencias // Procedencias de comprobantes
		//    Description: Función que registra los comprobantes de retencion de ISLR
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 12/08/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$la_procedencias=split('<<<',$as_procedencias);
		$la_comprobantes=split('<<<',$as_comprobantes);
		$la_datos=array_unique($la_comprobantes);
		$li_totrow=count($la_datos);
		sort($la_datos,SORT_STRING);
		$this->io_sqlaux->begin_transaction();
		for ($li_z=0;($li_z<$li_totrow)&&($lb_valido);$li_z++)
		{
			$ls_numsol=$la_datos[$li_z];
			$ls_procede=$la_procedencias[$li_z];  
			if($ls_procede=="SCBBCH")
			{
				$lb_valido=$this->uf_retencionesislr_scb($ls_numsol);  
			}
			else
			{
				$lb_valido=$this->uf_retencionesislr_cxp($ls_numsol);
			}
			if($lb_valido)
			{
				$ls_nrocom=$this->io_keygen->uf_generar_numero_nuevo("CXP","cxp_cmp_islr","numcmpislr","CXPCMP",15,"","","");
				$ls_codpro=$this->DS->data["cod_pro"][1];
				$ls_cedbene=$this->DS->data["ced_bene"][1];
				$ls_consol=$this->DS->data["consol"][1];
				$lb_existe=$this->uf_select_comprobantes($ls_numsol);
				if(!$lb_existe)
				{
					$lb_valido=$this->uf_insert_comprobante($ls_numsol,$ls_nrocom,$ls_codpro,$ls_cedbene,
															$ls_consol,$aa_seguridad);
					$li_total=$this->DS->getRowCount("numdoc");
					for($li_i=1;($li_i<=$li_total)&&($lb_valido);$li_i++)
					{
						$ls_codded=$this->DS->data["codded"][$li_i];
						$ls_tipproben=$this->DS->data["tipproben"][$li_i];
						$ls_codpro=$this->DS->data["cod_pro"][$li_i];
						$ls_ced_bene=$this->DS->data["ced_bene"][$li_i];
						if($ls_tipproben=="P")
						{
							$ls_nombre=$this->DS->data["proveedor"][$li_i];
							$ls_telefono=$this->DS->data["telpro"][$li_i];
							$ls_direccion=$this->DS->data["dirpro"][$li_i];
							$ls_rif=$this->DS->data["rifpro"][$li_i];
						}
						else
						{
							$ls_nombre=$this->DS->data["beneficiario"][$li_i];
							$ls_telefono=$this->DS->data["telbene"][$li_i];
							$ls_direccion=$this->DS->data["dirbene"][$li_i];
							$ls_rif=$this->DS->data["rifben"][$li_i];
						}						 
						$ls_nit=$this->DS->data["nit"][$li_i];
						$ls_consol=$this->DS->data["consol"][$li_i];
						$ls_numdoc=$this->DS->data["numdoc"][$li_i];
						$ls_numref=$this->DS->data["numref"][$li_i];
						$ld_fecemidoc=$this->DS->data["fecemidoc"][$li_i];
						$li_montotdoc=$this->DS->data["montotdoc"][$li_i];  
						$li_monobjret=$this->DS->data["monobjret"][$li_i];
						$li_retenido=$this->DS->data["retenido"][$li_i];  
						$li_porcentaje=$this->DS->data["porcentaje"][$li_i];
						$lb_valido=$this->uf_insert_dt_comprobante($ls_numsol,$ls_nrocom,$ls_numdoc,$ls_numref,$ld_fecemidoc,
																   $li_monobjret,$li_porcentaje,$li_retenido,$ls_codded,$aa_seguridad);
					}
				}
				else
				{
					$this->io_msg->message("El comprobante para el documento ".$ls_numsol." ya ha sido generado");
				}
			}
		}
		if($lb_valido)
		{
			$this->io_sqlaux->commit();
			$this->io_msg->message("El proceso se genero correctamente");
		}
		else
		{
			$this->io_sqlaux->rollback();
			$this->io_msg->message("Se produjo un error al generar los comprobantes");
		}
		
	}
	//---------------------------------------------------------------------------------------------------------------------------
	
	//---------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesislr_cxp($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesislr_cxp
		//         Access: public  
		//	    Arguments: as_numsol     // Numero de solicitud de orden de pago
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información de las retenciones de una solicitud de pago
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 04/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $ls_gestor = $_SESSION["ls_gestor"];
	   $lb_valido=true;
	   switch ($ls_gestor){
		case 'MYSQLT':
		   $ls_cadena=" CONCAT(RTRIM(rpc_beneficiario.apebene),', ',rpc_beneficiario.nombene) ";
		   break;
		case 'ORACLE':
		   $ls_cadena=" RTRIM(rpc_beneficiario.apebene)||', '||rpc_beneficiario.nombene ";
		   break;
		case 'POSTGRES':
		   $ls_cadena=" RTRIM(rpc_beneficiario.apebene)||', '||rpc_beneficiario.nombene ";
		   break;	    
		case 'INFORMIX':
		   $ls_cadena=" rpc_beneficiario.apebene||', '||rpc_beneficiario.nombene ";
		   break;	    
		case 'ANYWHERE':
		   $ls_cadena=" rtrim(rpc_beneficiario.apebene)+', '+rpc_beneficiario.nombene ";
		   break;
	   }
	   $ls_sql="SELECT cxp_rd_deducciones.codded,cxp_rd.numrecdoc AS numdoc, cxp_rd.numref, cxp_rd.fecemidoc, cxp_rd.tipproben, rpc_proveedor.nitpro AS nit, ".
	   		   "	   rpc_proveedor.nompro AS proveedor, rpc_proveedor.telpro, rpc_proveedor.dirpro, rpc_proveedor.rifpro, ".
			   $ls_cadena." AS beneficiario, rpc_beneficiario.dirbene, rpc_beneficiario.rifben, rpc_beneficiario.telbene, ".
			   "	   rpc_proveedor.cod_pro, rpc_beneficiario.ced_bene,cxp_solicitudes.numsol,".
			   "	   cxp_solicitudes.consol, cxp_rd.montotdoc, cxp_rd_deducciones.monret AS retenido, ".
			   "	   (CASE WHEN cxp_rd_deducciones.monobjret is null THEN cxp_solicitudes.monsol ELSE cxp_rd_deducciones.monobjret END) AS monobjret, ".
			   "	   sigesp_deducciones.porded AS porcentaje,sigesp_deducciones.dended AS dended,".
                       "      sigesp_deducciones.monded, cxp_rd.mondeddoc,		                        ".
                       "	   (SELECT cxp_sol_banco.numdoc".
			   "		  FROM cxp_sol_banco".
			   "		 WHERE cxp_sol_banco.estmov<>'A' 
			               AND cxp_sol_banco.estmov<>'O' 
			               AND cxp_solicitudes.codemp=cxp_sol_banco.codemp".
			   "           AND cxp_solicitudes.numsol=cxp_sol_banco.numsol) AS cheque".
			   "  FROM cxp_solicitudes, cxp_dt_solicitudes, cxp_rd, cxp_rd_deducciones, sigesp_deducciones, ".
			   "       rpc_beneficiario, rpc_proveedor ".
			   " WHERE sigesp_deducciones.islr=1 ".
			   "   AND sigesp_deducciones.iva=0 ".
			   "   AND sigesp_deducciones.estretmun=0 ".
			   "   AND cxp_solicitudes.estprosol<>'A' ".
			   "   AND cxp_solicitudes.codemp='".$this->ls_codemp."' ".
			   "   AND cxp_solicitudes.numsol='".$as_numsol."' ".
			   "   AND cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp ".
			   "   AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol ".
			   "   AND cxp_solicitudes.cod_pro=cxp_dt_solicitudes.cod_pro ".
			   "   AND cxp_solicitudes.ced_bene=cxp_dt_solicitudes.ced_bene ".
			   "   AND cxp_dt_solicitudes.codemp=cxp_rd.codemp ".
			   "   AND cxp_dt_solicitudes.cod_pro=cxp_rd.cod_pro ".
			   "   AND cxp_dt_solicitudes.ced_bene=cxp_rd.ced_bene ".
			   "   AND cxp_dt_solicitudes.codtipdoc=cxp_rd.codtipdoc ".
			   "   AND cxp_dt_solicitudes.numrecdoc=cxp_rd.numrecdoc ".
			   "   AND cxp_rd.codemp=cxp_rd_deducciones.codemp ".
			   "   AND cxp_rd.cod_pro=cxp_rd_deducciones.cod_pro ".
			   "   AND cxp_rd.ced_bene=cxp_rd_deducciones.ced_bene ".
			   "   AND cxp_rd.codtipdoc=cxp_rd_deducciones.codtipdoc ".
			   "   AND cxp_rd.numrecdoc=cxp_rd_deducciones.numrecdoc ".
			   "   AND sigesp_deducciones.codemp=cxp_rd_deducciones.codemp ".
			   "   AND sigesp_deducciones.codded=cxp_rd_deducciones.codded ".
			   "   AND rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
			   "   AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene ".
			   "   AND rpc_proveedor.codemp=cxp_solicitudes.codemp ".
			   "   AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro ".
			   " ORDER BY cxp_solicitudes.numsol";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_retencionesislr_cxp ERROR->".
										$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retencionesislr_cxp
	//---------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesislr_scb($as_numdoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesislr_scb
		//         Access: public  
		//	    Arguments: as_numsol     // Numero de solicitud de orden de pago
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información de las retenciones de banco
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 07/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $ls_gestor = $_SESSION["ls_gestor"];
	   $lb_valido=true;
	   switch ($ls_gestor)
	   {
		case 'MYSQLT':
		   $ls_cadena=" CONCAT(RTRIM(rpc_beneficiario.apebene),', ',rpc_beneficiario.nombene) ";
		   break;
		case 'ORACLE':
		   $ls_cadena=" RTRIM(rpc_beneficiario.apebene)||', '||rpc_beneficiario.nombene ";
		   break;
		case 'POSTGRES':
		   $ls_cadena=" RTRIM(rpc_beneficiario.apebene)||', '||rpc_beneficiario.nombene ";
		   break;	    
		case 'INFORMIX':
		   $ls_cadena=" rpc_beneficiario.apebene||', '||rpc_beneficiario.nombene ";
		   break;	    
		case 'ANYWHERE':
		   $ls_cadena=" rtrim(rpc_beneficiario.apebene)+', '+rpc_beneficiario.nombene ";
		   break;
	   }
	   $ls_sql="SELECT sigesp_deducciones.codded,scb_movbco.numdoc, scb_movbco.chevau AS numref, scb_movbco.fecmov AS fecemidoc, scb_movbco.tipo_destino AS tipproben, ".
	   		   "	   rpc_proveedor.nitpro AS nit, rpc_proveedor.nompro AS proveedor, rpc_proveedor.telpro, rpc_proveedor.dirpro, ".
			   "	   rpc_proveedor.rifpro, ".$ls_cadena." AS beneficiario, rpc_beneficiario.dirbene, rpc_beneficiario.rifben, ".
			   "	   rpc_beneficiario.telbene, rpc_proveedor.cod_pro, rpc_beneficiario.ced_bene, scb_movbco.conmov AS consol,".
               "       scb_movbco.monto AS montotdoc, scb_movbco.monret AS retenido, scb_movbco.monobjret AS monobjret,'' AS numsol,        ".
			   "       sigesp_deducciones.porded AS porcentaje,sigesp_deducciones.dended AS dended,scb_movbco.numdoc AS cheque  ".
			   "  FROM scb_movbco, scb_movbco_scg, sigesp_deducciones, rpc_proveedor, rpc_beneficiario ".
			   " WHERE scb_movbco.codemp = '".$this->ls_codemp."' ".
			   "   AND scb_movbco.numdoc = '".$as_numdoc."' ".
			   "   AND scb_movbco.estmov<>'O' ".
			   "   AND scb_movbco.estmov<>'A' ".
			   "   AND sigesp_deducciones.islr = 1 ".
			   "   AND sigesp_deducciones.iva = 0 ".
			   "   AND sigesp_deducciones.estretmun = 0 ".
			   "   AND scb_movbco.codemp = scb_movbco_scg.codemp ".
			   "   AND scb_movbco.codban = scb_movbco_scg.codban ".
			   "   AND scb_movbco.ctaban = scb_movbco_scg.ctaban ".
			   "   AND scb_movbco.numdoc = scb_movbco_scg.numdoc ".
			   "   AND scb_movbco.codope = scb_movbco_scg.codope ".
			   "   AND scb_movbco.estmov = scb_movbco_scg.estmov ".
			   "   AND scb_movbco_scg.codemp = sigesp_deducciones.codemp ".
			   "   AND scb_movbco_scg.codded = sigesp_deducciones.codded ".
			   "   AND scb_movbco.codemp = rpc_proveedor.codemp ".
			   "   AND scb_movbco.cod_pro = rpc_proveedor.cod_pro ".
			   "   AND scb_movbco.codemp = rpc_beneficiario.codemp ".
			   "   AND scb_movbco.ced_bene = rpc_beneficiario.ced_bene ".
			   "  ORDER BY scb_movbco.numdoc ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_retencionesislr_scb ERROR->".
										$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retencionesislr_scb
	//---------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
    function uf_insert_comprobante($as_numsol,$as_nrocom,$as_codpro,$as_cedbene,$as_consol,$aa_seguridad)
	{
	    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	      Function: uf_insert_comprobante
		//	        Access: public
		//	      Argument: $as_numsol // Numero de la solicitud
		//                  $as_nrocom // Numero de comprobante
		//                  $as_codpro // Codigo del proveedor 
		//                  $as_cedbene // Cedula de Beneficiario
		//                  $as_consol // Concepto de la solicitud
		//     Description: Función que guarda la cabecera de un comprobante de retencion  
		//	    Creado Por: Ing. Luis Anibal Lang
		//  Fecha Creación: 13/09/2007								Fecha Última Modificación : 13/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" INSERT INTO cxp_cmp_islr (codemp,numcmpislr,numsol,cod_pro,ced_bene,consol)".
				  " VALUES ('".$this->la_empresa["codemp"]."','".$as_nrocom."','".$as_numsol."','".$as_codpro."',".
				  "         '".$as_cedbene."','".$as_consol."')";
		$li_result=$this->io_sqlaux->execute($ls_sql);
		if($li_result===false)
		{	
			$this->io_msg->message("CLASE->Generar Comprobate MÉTODO->uf_insert_comprobante ERROR->".
									$this->io_function->uf_convertirmsg($this->io_sqlaux->message));
			$lb_valido=false;
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Comprobante de I.S.L.R. de la solicitud de pago".$as_numsol.
							 " Asociado a la empresa ".$this->la_empresa["codemp"];
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
    }//FIN  uf_insert_comprobante
	//---------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
    function uf_insert_dt_comprobante($as_numsol,$as_nrocom,$as_numrecdoc,$as_numref,$ad_fecpag,$ai_monobjret,
									  $ai_porded,$ai_totimpret,$as_codded,$aa_seguridad)
	{
	    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	      Function: uf_insert_dt_comprobante
		//	        Access: public
		//	      Argument: $as_numsol // Numero de la solicitud
		//                  $as_nrocom // Numero de comprobante
		//                  $as_codpro // Codigo del proveedor 
		//                  $as_cedbene // Cedula de Beneficiario
		//     Description: Función que guarda la cabecera de un comprobante de retencion  
		//	    Creado Por: Ing. Luis Anibal Lang
		//  Fecha Creación: 13/09/2007								Fecha Última Modificación : 13/09/2007
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" INSERT INTO cxp_dt_cmp_islr (codemp,numcmpislr,numsol,numrecdoc,numref,fecpag,monobjret,porded,totimpret,codded)".
				  " VALUES ('".$this->la_empresa["codemp"]."','".$as_nrocom."','".$as_numsol."','".$as_numrecdoc."',".
				  "         '".$as_numref."','".$ad_fecpag."',".$ai_monobjret.",".$ai_porded.",".$ai_totimpret.",'".$as_codded."')";
		$li_result=$this->io_sqlaux->execute($ls_sql);
		if($li_result===false)
		{	
			$this->io_msg->message("CLASE->Generar Comprobate MÉTODO->uf_insert_dt_comprobante ERROR->".
									$this->io_function->uf_convertirmsg($this->io_sqlaux->message));
			$lb_valido=false;
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Se asocio el Documento ".$as_numrecdoc. " Asociado al Comprobante de la Solicitud ".$as_numsol.
							 " Asociado a la empresa ".$this->la_empresa["codemp"];
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
    }//FIN  uf_insert_dt_comprobante
	//--------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------
	function uf_select_comprobantes($as_numsol)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_comprobantes
		//		   Access: private
		//		 Argument: $as_numsol // Numero de Solicitud de pago
		//	  Description: Función que verifica si ya ha sido generado el comprobante de I.S.L.R.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT numsol ".
				"  FROM cxp_cmp_islr  ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsol='".$as_numsol."'".
				" ORDER BY numsol DESC";
		$rs_data=$this->io_sqlaux->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_validar_fecha_solicitud ERROR->".
									    $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sqlaux->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_validar_fecha_solicitud
	//---------------------------------------------------------------------------------------------------------------------------
	
}
?>