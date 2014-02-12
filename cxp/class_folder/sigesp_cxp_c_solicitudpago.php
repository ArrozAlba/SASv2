<?php
class sigesp_cxp_c_solicitudpago
 {
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_id_process;
	var $ls_codemp;
	var $io_dscuentas;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_cxp_c_solicitudpago($as_path)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_cxp_c_recepcion
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 02/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once($as_path."shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once($as_path."shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once($as_path."shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once($as_path."shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
	    require_once($as_path."shared/class_folder/class_fecha.php");		
		$this->io_fecha= new class_fecha();
		require_once($as_path."shared/class_folder/class_datastore.php");
		require_once($as_path."shared/class_folder/sigesp_c_generar_consecutivo.php");
		$this->io_keygen= new sigesp_c_generar_consecutivo();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		require_once("class_funciones_cxp.php");
		$this->io_cxp= new class_funciones_cxp();
        $this->ls_conrecdoc=$_SESSION["la_empresa"]["conrecdoc"];
	}// end function sigesp_cxp_c_solicitudpago
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_cxp_p_recepcion.php)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 02/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fecha);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_fecha_solicitud($ad_fecemisol)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_fecha_solicitud
		//		   Access: private
		//		 Argument: $ad_fecemisol // fecha de emision de solicitud de pago
		//	  Description: Función que busca la fecha de la última sep y la compara con la fecha actual
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 26/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT numsol,fecemisol ".
				"  FROM cxp_solicitudes  ".
				" WHERE codemp='".$this->ls_codemp."' ".
				" ORDER BY numsol DESC";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_validar_fecha_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ld_fecha=$this->io_funciones->uf_formatovalidofecha($row["fecemisol"]);
				//$ld_fecha=$row["fecemisol"];
				$lb_valido=$this->io_fecha->uf_comparar_fecha($ld_fecha,$ad_fecemisol); 
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_validar_fecha_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_solicitud(&$as_numsol,$as_codpro,$as_cedbene,$as_codfuefin,$as_tipproben,$ad_fecemisol,$as_consol,
								 $ai_monsol,$as_obssol,$as_estsol,$ai_totrowrecepciones,$aa_seguridad,$as_numordpagmin,$as_codtipfon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_solicitud
		//		   Access: private
		//	    Arguments: ad_fecregsol  // Fecha de Solicitud
		//				   as_numsol     // Número de Solicitud 
		//				   as_codpro     // Codigo de Proveedor
		//				   as_cedbene    // Cedula de Beneficiario
		//				   as_codfuefin  // Codigo de Fuente de Financiamiento
		//				   as_tipproben  // Tipo Proveedor/Beneficiario 
		//				   ad_fecemisol  // Fecha de Emision de la Solicitud
		//				   as_consol     // Concepto de la Solicitud
		//				   as_codtipsol  // Código Tipo de solicitud
		//				   as_consol     // Concepto de la Solicitud
		//				   ai_monsol     // Monto de la Solicitud
		//				   as_obssol     // Observacion de la Solicitud
		//				   as_estsol     // Estatus de la Solicitud
		//				   ai_totrowrecepciones  // Total de Filas de R.D.
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta la Solicitud de Pagos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 23/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	    $lb_valido=$this->io_id_process->uf_check_id("cxp_solicitudes","numsol",&$as_numsol);   
		$as_numordpagmin = trim($as_numordpagmin);
		$as_codtipfon    = trim($as_codtipfon);
		if (empty($as_numordpagmin))
		   {
		     $as_numordpagmin = '-';
		   }
		if (empty($as_codtipfon))
		   {
		     $as_codtipfon = '----';
		   }
		$ls_numsolaux=$as_numsol;
		$lb_valido= $this->io_keygen->uf_verificar_numero_generado("CXP","cxp_solicitudes","numsol","CXPSOP",15,"","","",&$as_numsol);
		$lb_valido=true;
		if($lb_valido)
		{
			$ls_sql="INSERT INTO cxp_solicitudes (codemp, numsol, cod_pro, ced_bene, codfuefin, tipproben, fecemisol, consol,".
					"                             estprosol, monsol, obssol, estaprosol,procede,numordpagmin,codtipfon)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$as_codpro."','".trim($as_cedbene)."',".
					" 			  '".$as_codfuefin."','".$as_tipproben."','".$ad_fecemisol."','".$as_consol."','".$as_estsol."',".
					"			  ".$ai_monsol.",'".$as_obssol."',0,'CXPSOP','-','----')";
			$this->io_sql->begin_transaction();				
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_sql->rollback();
				if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
				{
					$lb_valido=$this->uf_insert_solicitud(&$as_numsol,$as_codpro,$as_cedbene,$as_codfuefin,$as_tipproben,
														  $ad_fecemisol,$as_consol,$ai_monsol,$as_obssol,$as_estsol,
														  $ai_totrowrecepciones,$aa_seguridad,$as_numordpagmin,$as_codtipfon);
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la solicitud ".$as_numsol." Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_recepciones($as_numsol, $as_cedbene, $as_codpro, $ai_totrowrecepciones, $ad_fecemisol, $aa_seguridad);
				}			
				if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_historico_solicitud($as_numsol, $ad_fecemisol, $aa_seguridad);
				}			
				if($lb_valido)
				{	
					if($ls_numsolaux!=$as_numsol)
					{
						$this->io_mensajes->message("Se Asigno el Numero de Solicitud: ".$as_numsol);
					}
					$lb_valido=true;
					$this->io_sql->commit();
					$this->io_mensajes->message("La Solicitud ha sido Registrada."); 
				}			
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("Ocurrio un Error al Registrar la Solicitud."); 
					$this->io_sql->rollback();
				}
			}
		}
		return $lb_valido;
	}// end function uf_insert_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_recepciones($as_numsol, $as_cedbene, $as_codpro, $ai_totrowrecepciones, $ad_fecemisol, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_recepciones
		//		   Access: private
		//	    Arguments: as_numsol            // Número de Solicitud 
		//				   as_cedbene           // Cedula de Beneficiario
		//				   as_codpro            // Código Proveedor
		//				   ai_totrowrecepciones // Total de Filas de R.D.
		//				   ad_fecemisol         // Fecha de emision de la solicitud de pago
		//				   aa_seguridad         // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta las Recepciones de Documento de una  Solicitud de Pago
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_i=1;($li_i<$ai_totrowrecepciones)&&($lb_valido);$li_i++)
		{
			$ls_numrecdoc=$_POST["txtnumrecdoc".$li_i];
			$ls_codtipdoc=$_POST["txtcodtipdoc".$li_i];
			$li_monto=$_POST["txtmontotdoc".$li_i];
			$li_monto=str_replace(".","",$li_monto);
			$li_monto=str_replace(",",".",$li_monto);
			$lb_existe=$this->uf_select_recepcion($ls_numrecdoc,$as_codpro,$as_cedbene,$ls_codtipdoc);
			$lb_valido=$this->uf_comparar_fechas($ls_numrecdoc,$as_codpro,$as_cedbene,$ls_codtipdoc,$ad_fecemisol);
			$lb_cierre=$this->uf_verificar_cierre($ls_numrecdoc,$ls_codtipdoc,$as_cedbene,$as_codpro);
			if($lb_cierre)
			{
				return false;
			}
			if((!$lb_existe)&&($lb_valido))
			{
				$ls_sql="INSERT INTO cxp_dt_solicitudes (codemp, numsol, numrecdoc, codtipdoc, ced_bene, cod_pro, monto)".
						"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$ls_numrecdoc."','".$ls_codtipdoc."',".
						" 			  '".trim($as_cedbene)."','".$as_codpro."',".$li_monto.")";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_recepciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó la Recepcion ".$ls_numrecdoc." a la Solicitud de Pago ".$as_numsol.
									 " Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////	
					if($lb_valido)
					{
						$lb_valido=$this->uf_procesar_asientos($as_numsol,$ls_numrecdoc,$ls_codtipdoc,$as_cedbene,
															   $as_codpro,$aa_seguridad);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_update_estatus_procedencia($ls_numrecdoc,$ls_codtipdoc,$as_cedbene,$as_codpro,"E",$aa_seguridad);	
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_historico_recepciones($ls_numrecdoc,$ls_codtipdoc,$as_cedbene,$as_codpro,
																		  $ad_fecemisol,"E",$aa_seguridad);	
					}
				}
			}
			else
			{
				if($lb_existe)
				{
					$this->io_mensajes->message("La Recepcion de documentos ".$ls_numrecdoc." ya esta tomada en otra Solicitud de Pago"); 
				}
				else
				{
					$this->io_mensajes->message("La Recepcion de documentos ".$ls_numrecdoc." tiene una fecha posterior a la Solicitud de Pago"); 
				}
				return false;
			}
		}
		return $lb_valido;
	}// end function uf_insert_recepciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_asientos($as_numsol,$as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_asientos
		//		   Access: public
		//		 Argument: as_numsol    // Número de Solicitud de Pago
		//		 		   as_numrecdoc    // Número de Recepción de Documentos
		//		 		   as_codpro    // Código del Proveedor 
		//		 		   as_cedbene   // Cédula del Beneficiario
		//		 		   as_codtipdoc // Código del Tipo de Documento
		//	  Description: Función que verifica si una recepción existe ó no en otra solicitud de pago
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 05/03/2008								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////		
		$lb_valido=true;
		if($this->ls_conrecdoc)
		{
			$lb_valido=$this->uf_obtener_procedencia($as_numrecdoc,$as_codpro,$as_cedbene,$as_codtipdoc,&$as_procedencia);
			if($as_procedencia!="SCVSOV")
			{
				$lb_valido=$this->uf_load_cuentaproveedor($as_cedbene,$as_codpro,&$as_cuentapro,&$as_cuentarecdoc);
			}
			else
			{
				$lb_valido=$this->uf_load_cuentaviaticos(&$as_cuentapro,&$as_cuentarecdoc);
			}
			if($lb_valido)
			{
				if(($as_cuentapro!="")&&($as_cuentarecdoc!=""))
				{
					$lb_valido=$this->uf_crear_asientos($as_numsol,$as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_cuentapro,
														$as_cuentarecdoc,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("Existen errores el las cuentas del Proveedor/Beneficiario asociado.");
					$lb_valido=false;
				}
			}
		}
		return $lb_valido;
	}// end function uf_procesar_asientos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_procedencia($as_numrecdoc,$as_codpro,$as_cedbene,$as_codtipdoc,&$as_procedencia)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_procedencia
		//		   Access: public
		//		 Argument: as_numrecdoc // Número de Recepción de Documentos
		//		 		   as_codpro    // Código del Proveedor 
		//		 		   as_cedbene   // Cédula del Beneficiario
		//		 		   as_codtipdoc // Código del Tipo de Documento
		//	  Description: Función que verifica si una recepción existe ó no en otra solicitud de pago
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 03/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////		
		$lb_existe=false;
		$ls_sql="SELECT procede ".
				"  FROM cxp_rd ".
				" WHERE cxp_rd.codemp='".$this->ls_codemp."' ".
				"	AND cxp_rd.numrecdoc='".$as_numrecdoc."' ".
				"	AND cxp_rd.codtipdoc='".$as_codtipdoc."' ".
				"   AND cxp_rd.cod_pro='".$as_codpro."' ".
				"   AND cxp_rd.ced_bene='".trim($as_cedbene)."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_obtener_procedencia ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_procedencia=$row["procede"];
				$lb_existe=true;
			}
		}
		return $lb_existe;
	}// end function uf_obtener_procedencia
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_crear_asientos($as_numsol,$as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_cuentapro,$as_cuentarecdoc,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_crear_asientos
		//		   Access: public
		//		 Argument:
		//	  Description: 
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 03/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codemp,numrecdoc,codtipdoc,ced_bene,cod_pro,procede_doc,numdoccom,debhab,sc_cuenta,estasicon,monto".
				"  FROM cxp_rd_scg".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND numrecdoc='".$as_numrecdoc."'".
				"   AND codtipdoc='".$as_codtipdoc."'".
				"   AND cod_pro='".$as_codpro."'".
				"   AND ced_bene='".trim($as_cedbene)."'".
				"   AND sc_cuenta='".$as_cuentarecdoc."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_crear_asientos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_procede_doc=$row["procede_doc"];
				$ls_numdoccom=$row["numrecdoc"];
				$ls_debhab=$row["debhab"];
				$ls_estasicon=$row["estasicon"];
				$li_monto=$row["monto"];
				$lb_valido=$this->uf_insert_asiento($as_numsol,$as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_cuentapro,$as_cuentarecdoc,
													$ls_procede_doc,$ls_numdoccom,$ls_debhab,$ls_estasicon,$li_monto,$aa_seguridad);
			}
		}
		return $lb_valido;
	}// end function uf_crear_asientos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_asiento($as_numsol,$as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_cuentapro,$as_cuentarecdoc,$as_procede_doc,
							   $as_numdoccom,$as_debhab,$as_estasicon,$ai_monto,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_asiento
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de Recepcion de Documentos
		//				   as_codtipdoc  // Codigo de tipo de documento
		//				   as_cedbene    // Cedula de Beneficiario
		//				   as_codpro     // Código Proveedor
		//                 ad_fecemisol  // Fecha de emision de la solicitud
		//                 as_estatus    // Estatus del registro de R.D.
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta las Recepciones de Documento de una  Solicitud de Pago
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 25/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="INSERT INTO cxp_solicitudes_scg (codemp,numsol,numrecdoc,codtipdoc,ced_bene,cod_pro,procede_doc,numdoccom,debhab,sc_cuenta,estasicon,monto)".
				"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$as_numrecdoc."','".$as_codtipdoc."','".trim($as_cedbene)."','".$as_codpro."',".
				" 			  '".$as_procede_doc."','".$as_numdoccom."','D','".$as_cuentarecdoc."','".$as_estasicon."','".$ai_monto."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_asiento ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$ls_sql="INSERT INTO cxp_solicitudes_scg (codemp,numsol,numrecdoc,codtipdoc,ced_bene,cod_pro,procede_doc,numdoccom,debhab,sc_cuenta,estasicon,monto)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$as_numrecdoc."','".$as_codtipdoc."','".trim($as_cedbene)."','".$as_codpro."',".
					" 			  '".$as_procede_doc."','".$as_numdoccom."','H','".$as_cuentapro."','".$as_estasicon."','".$ai_monto."')";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_asiento ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el asiento contable originado de la contabilización de la R.D. ".$as_numrecdoc.
								 " ligada a la solicitud de pago ".$as_numsol." Con las cuentas D ".$as_cuentarecdoc." H ".$as_cuentapro." Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}
		}
		return $lb_valido;
	}// end function uf_insert_historico_recepciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cuentaproveedor($as_cedbene,$as_codpro,&$as_cuentapro,&$as_cuentarecdoc)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cuentaproveedor
		//		   Access: public
		//		 Argument: as_codpro    // Código del Proveedor 
		//		 		   as_cedbene   // Cédula del Beneficiario
		//		 		   as_cuentapro // Cuenta del Proveedor/Beneficiario
		//	  Description: 
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 03/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$as_cuentapro="";
		$as_cuentarecdoc="";
		if($as_codpro!="----------")
		{
			$ls_sql="SELECT trim(sc_cuenta) as sc_cuenta,sc_cuentarecdoc".
					"  FROM rpc_proveedor ".
					" WHERE rpc_proveedor.codemp ='".$this->ls_codemp."'".
					"   AND rpc_proveedor.cod_pro = '".$as_codpro."'";
		}
		else
		{
			$ls_sql="SELECT trim(sc_cuenta) as sc_cuenta,sc_cuentarecdoc".
					"  FROM rpc_beneficiario ".
					" WHERE rpc_beneficiario.codemp ='".$this->ls_codemp."'".
					"   AND rpc_beneficiario.ced_bene = '".trim($as_cedbene)."'";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_cuentaproveedor ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_cuentapro=$row["sc_cuenta"];
				$as_cuentarecdoc=$row["sc_cuentarecdoc"];
				$lb_valido=true;
			}
		}
		return $lb_valido;
	}// end function uf_load_cuentaproveedor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cuentaviaticos(&$as_cuentapro,&$as_cuentarecdoc)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cuentaviaticos
		//		   Access: public
		//		 Argument: as_codpro    // Código del Proveedor 
		//		 		   as_cedbene   // Cédula del Beneficiario
		//		 		   as_cuentapro // Cuenta del Proveedor/Beneficiario
		//	  Description: 
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 03/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$as_cuentapro="";
		$as_cuentarecdoc="";
		$lb_valido=$this->uf_scv_load_config("SCV","CONFIG","BENEFICIARIO",&$as_cuentapro);
		if($lb_valido)
		{
			$lb_valido=$this->uf_scv_load_config("SCV","CONFIG","BENEFICIARIORD",&$as_cuentarecdoc);
		}
		return $lb_valido;
	}// end function uf_load_cuentaviaticos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scv_load_config($as_codsis,$as_seccion,$as_entry,&$as_scgcuenta) 
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_load_config
		//	          Access:  public
		//	       Arguments:  $as_codemp    // código de la Empresa.
		//        			   $as_codmis    //  código de la Misión.
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de verificar si existe o no la configuracion de viaticos
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  13/11/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$ls_sql=" SELECT value".
				"   FROM sigesp_config".
				"  WHERE codemp='".$this->ls_codemp."'".
				"    AND codsis='".$as_codsis."'".
				"    AND seccion='".$as_seccion."'".
				"    AND entry='".$as_entry."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_scv_c_config METODO->uf_scv_load_config ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_scgcuenta=$row["value"];
				$lb_valido=true;
			}
		}
		return $lb_valido;
	} // fin de la function uf_scv_load_config
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_comparar_fechas($as_numrecdoc,$as_codpro,$as_cedbene,$as_codtipdoc,$ad_fecemisol)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_comparar_fechas
		//		   Access: public
		//		 Argument: as_numrecdoc // Número de Recepción de Documentos
		//		 		   as_codpro    // Código del Proveedor 
		//		 		   as_cedbene   // Cédula del Beneficiario
		//		 		   as_codtipdoc // Código del Tipo de Documento
		//		 		   ad_fecemisol // Fecha de emision de la solicitud de pago
		//	  Description: Función que verifica si la fecha de la solicitud de pago con la de la recepcion de documento
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/04/2008								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////		
		$lb_valido=false;
		$ls_sql="SELECT fecregdoc ".
				"  FROM cxp_rd ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"	AND numrecdoc='".$as_numrecdoc."' ".
				"	AND codtipdoc='".$as_codtipdoc."' ".
				"   AND cod_pro='".$as_codpro."' ".
				"   AND ced_bene='".trim($as_cedbene)."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_comparar_fechas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$ld_fegregdoc=$row["fecregdoc"];
				$lb_valido=$this->io_fecha->uf_comparar_fecha($ld_fegregdoc,$ad_fecemisol);
			}
		}
		if(!$lb_valido)
		{
			$this->io_mensajes->message("La fecha de la Recepcion de Documentos ".$as_numrecdoc." es mayor que la fecha de la solicitud");
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_recepcion($as_numrecdoc,$as_codpro,$as_cedbene,$as_codtipdoc)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_recepcion
		//		   Access: public
		//		 Argument: as_numrecdoc // Número de Recepción de Documentos
		//		 		   as_codpro    // Código del Proveedor 
		//		 		   as_cedbene   // Cédula del Beneficiario
		//		 		   as_codtipdoc // Código del Tipo de Documento
		//	  Description: Función que verifica si una recepción existe ó no en otra solicitud de pago
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 03/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////		
		$lb_existe=false;
		$ls_sql="SELECT numrecdoc ".
				"  FROM cxp_solicitudes,cxp_dt_solicitudes ".
				" WHERE cxp_dt_solicitudes.codemp='".$this->ls_codemp."' ".
				"	AND cxp_dt_solicitudes.numrecdoc='".$as_numrecdoc."' ".
				"	AND cxp_dt_solicitudes.codtipdoc='".$as_codtipdoc."' ".
				"   AND cxp_dt_solicitudes.cod_pro='".$as_codpro."' ".
				"   AND cxp_dt_solicitudes.ced_bene='".trim($as_cedbene)."'".
				"   AND cxp_solicitudes.estprosol<>'A'".
				"	AND cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp".
				"	AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_select_recepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
			}
		}
		return $lb_existe;
	}// end function uf_select_recepcion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_procedencia($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$ls_estatus,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_procedencia
		//		   Access: private
		//	    Arguments: as_numrecdoc // Número de Recepcion de Documentos
		//                 as_codtipdoc // Codigo de Tipo de Documento
		//				   as_cedbene   // Cedula de Beneficiario
		//				   as_codpro    // Código Proveedor
		//				   ls_estatus   // Estatus en que se desea colocar la R.D.
		//                 aa_seguridad // Arreglo que contiene informacion de seguridad
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que actualiza el estatus de la Recepcion de Documentos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 25/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($this->ls_conrecdoc!=1)
		{
			$ls_sql="UPDATE cxp_rd ".
					"   SET estprodoc = '".$ls_estatus."' ".
					" WHERE codemp = '".$this->ls_codemp."'".
					"	AND numrecdoc = '".$as_numrecdoc."' ".
					"	AND codtipdoc = '".$as_codtipdoc."' ".
					"	AND ced_bene = '".trim($as_cedbene)."' ".
					"	AND cod_pro = '".$as_codpro."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_update_estatus_procedencia ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó en estatus de la recepcion <b>".$as_numrecdoc.
								 "</b> Asociado a la Empresa <b>".$this->ls_codemp."<b>";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}
		return $lb_valido;
	}// end function uf_update_estatus_procedencia
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_historico_recepciones($as_numrecdoc, $as_codtipdoc, $as_cedbene, $as_codpro, $ad_fecemisol,
											 $as_estatus,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_historico_recepciones
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de Recepcion de Documentos
		//				   as_codtipdoc  // Codigo de tipo de documento
		//				   as_cedbene    // Cedula de Beneficiario
		//				   as_codpro     // Código Proveedor
		//                 ad_fecemisol  // Fecha de emision de la solicitud
		//                 as_estatus    // Estatus del registro de R.D.
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta las Recepciones de Documento de una  Solicitud de Pago
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 25/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe=$this->uf_select_historicord($as_numrecdoc, $as_codtipdoc, $as_cedbene, $as_codpro, $ad_fecemisol,$as_estatus);
		if(!$lb_existe)
		{
			$ls_sql="INSERT INTO cxp_historico_rd (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, fecha, estprodoc)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."',".
					" 			  '".trim($as_cedbene)."','".$as_codpro."','".$ad_fecemisol."','".$as_estatus."')";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_historico_recepciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó un Movimiento en el Historico de la Recepcion ".$as_numrecdoc.
								 " Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}
		}
		return $lb_valido;
	}// end function uf_insert_historico_recepciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_historico_solicitud($as_numsol, $ad_fecemisol, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_historico_solicitud
		//		   Access: private
		//	    Arguments: as_numsol    // Número de Solicitud 
		//                 ad_fecemisol //  Fecha de emision de la solicitud
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta un movimiento en el historico de la solicitud de orden de pago
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 26/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO cxp_historico_solicitud (codemp, numsol, fecha, estprodoc)".
				"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$ad_fecemisol."','R')";        
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_historico_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó un Movimiento en el Historico de la Solicitud de Pago ".$as_numsol.
							 " Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_insert_historico_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,&$as_numsol,$as_codpro,$as_cedbene,$as_codfuefin,$as_tipproben,$ad_fecemisol,$as_consol,
						$ai_monsol,$as_obssol,$as_estsol,$ai_totrowrecepciones,$aa_seguridad,$as_permisosadministrador,
						$as_numordpagmin,$as_codtipfon)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_sep_p_solicitud.php)
		//	    Arguments: as_existe    // Fecha de Solicitud
		//				   as_numsol    // Número de Solicitud 
		//				   as_codpro    // Codigo de Proveedor
		//				   as_cedbene   // Codigo de Beneficiario
		//				   as_codfuefin // Código de Fuente de financiamiento
		//				   as_tipproben // Tipo de Proveedor / Beneficiario
		//				   as_consol    // Concepto de la Solicitud
		//				   ad_fecemisol // Fecha de Emision de la Solicitud
		//				   ai_monsol    // Total de la solicitud
		//				   as_obssol    // Observacion de la Solicitud
		//				   as_estsol    // Estatus de la Solicitud
		//				   ai_totrowrecepciones  // Total de Recepciones de Documento asociadas
		//				   aa_seguridad // arreglo de las variables de seguridad
		//				   as_permisosadministrador  // Indica si el usuario tiene permiso de administrador
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que valida y guarda la solicitud de pago
		//	   Creado Por: Ing. Yesenia Moreno /Ing. Luis Lang
		// Fecha Creación: 26/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;	
		$lb_encontrado=$this->uf_select_solicitud($as_numsol);
		$ai_monsol=str_replace(".","",$ai_monsol);
		$ai_monsol=str_replace(",",".",$ai_monsol);
		$ad_fecemisol=$this->io_funciones->uf_convertirdatetobd($ad_fecemisol);
		switch ($as_existe)
		{
			case "FALSE":
				/*if($as_permisosadministrador!=1)
				{
					$lb_valido=$this->uf_validar_fecha_solicitud($ad_fecemisol);
					if(!$lb_valido)
					{
						$this->io_mensajes->message("La Fecha esta la Solicitud es menor a la fecha de la Solicitud anterior.");
						return false;
					}
				}*/
				$lb_valido=$this->io_fecha->uf_valida_fecha_periodo($ad_fecemisol,$this->ls_codemp);
				if (!$lb_valido)
				{
					$this->io_mensajes->message($this->io_fecha->is_msg_error);           
					return false;
				}                    
				$lb_valido=$this->uf_insert_solicitud(&$as_numsol,$as_codpro,$as_cedbene,$as_codfuefin,$as_tipproben,
													  $ad_fecemisol,$as_consol,$ai_monsol,$as_obssol,$as_estsol,
													  $ai_totrowrecepciones,$aa_seguridad,$as_numordpagmin,$as_codtipfon);
				break;

			case "TRUE":
				if($lb_encontrado)
				{
					$lb_valido=$this->uf_update_solicitud($as_numsol,$as_codpro,$as_cedbene,$as_codfuefin,$as_tipproben,
														  $ad_fecemisol,$as_consol,$ai_monsol,$as_obssol,$as_estsol,
														  $ai_totrowrecepciones,$aa_seguridad,$as_numordpagmin,$as_codtipfon);
				}
				else
				{
					$this->io_mensajes->message("La Solicitud no existe, no la puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_solicitud($as_numsol,$as_codpro,$as_cedbene,$as_codfuefin,$as_tipproben,$ad_fecemisol,$as_consol,
								 $ai_monsol,$as_obssol,$as_estsol,$ai_totrowrecepciones,$aa_seguridad,$as_numordpagmin,$as_codtipfon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_solicitud
		//		   Access: private
		//	    Arguments: ad_fecregsol  // Fecha de Solicitud
		//				   as_numsol     // Número de Solicitud 
		//				   as_codpro     // Codigo de Proveedor
		//				   as_cedbene    // Cedula de Beneficiario
		//				   as_codfuefin  // Codigo de Fuente de Financiamiento
		//				   as_tipproben  // Tipo Proveedor/Beneficiario 
		//				   ad_fecemisol  // Fecha de Emision de la Solicitud
		//				   as_consol     // Concepto de la Solicitud
		//				   as_codtipsol  // Código Tipo de solicitud
		//				   as_consol     // Concepto de la Solicitud
		//				   ai_monsol     // Monto de la Solicitud
		//				   as_obssol     // Observacion de la Solicitud
		//				   as_estsol     // Estatus de la Solicitud
		//				   ai_totrowrecepciones  // Total de Filas de R.D.
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta la Solicitud de Pagos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 23/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE cxp_solicitudes ".
				"   SET cod_pro	= '".$as_codpro."', ".
				"		ced_bene = '".trim($as_cedbene)."', ".
				"		consol = '".$as_consol."', ".
				"		codfuefin = '".$as_codfuefin."', ".
				"		tipproben = '".$as_tipproben."', ".
				"		monsol = ".$ai_monsol.", ".
				"		numordpagmin = '".$as_numordpagmin."', ".
				"		codtipfon = '".$as_codtipfon."', ".
				"		obssol = '".$as_obssol."' ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"	AND numsol = '".$as_numsol."' ";
		$this->io_sql->begin_transaction();				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_update_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la solicitud ".$as_numsol." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{
				$rs_data=$this->uf_load_recepciones($as_numsol);
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ls_numrecdoc=$row["numrecdoc"];
					$ls_codtipdoc=$row["codtipdoc"];
					$lb_valido=$this->uf_update_estatus_procedencia($ls_numrecdoc,$ls_codtipdoc,$as_cedbene,$as_codpro,"R",$aa_seguridad);	
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_historico_recepciones($ls_numrecdoc,$ls_codtipdoc,$as_cedbene,$as_codpro,
																		  $ad_fecemisol,"R",$aa_seguridad);	
					}
				}
				if($rs_data===false)
				{
					$lb_valido=false;
				}
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_delete_detalles($as_numsol,$aa_seguridad);
			}	
			if($lb_valido)
			{	
					$lb_valido=$this->uf_insert_recepciones($as_numsol, $as_cedbene, $as_codpro, $ai_totrowrecepciones,
															$ad_fecemisol, $aa_seguridad);
			}			
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Solicitud fue actualizada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("Ocurrio un Error al Actualizar la Solicitud."); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitud($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_solicitud
		//		   Access: private
		//	    Arguments: as_numsol  //  Número de Solicitud
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la Solicitud de pago Existe
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 26/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT numsol ".
				"  FROM cxp_solicitudes ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsol='".$as_numsol."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_select_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
	}// end function uf_select_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_recepciones($as_numsol)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_recepciones
		//		   Access: public
		//		 Argument: as_numsol // Número de solicitud
		//	  Description: Función que busca las recepciones de documentos asociadas a una solicitud
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 29/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT cxp_dt_solicitudes.numrecdoc, cxp_dt_solicitudes.monto, cxp_dt_solicitudes.codtipdoc, cxp_documento.dentipdoc ".
				"  FROM cxp_dt_solicitudes,cxp_documento ".	
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsol= '".$as_numsol."' ".
				"   AND cxp_dt_solicitudes.codtipdoc=cxp_documento.codtipdoc";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_recepciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_recepciones
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_solicitud($as_numsol,$as_codpro,$as_cedbene,$ad_fecemisol,$ai_totrow,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_solicitud
		//		   Access: public
		//	    Arguments: as_numsol     // Número de Solicitud 
		//				   as_codpro     // Codigo de Proveedor
		//				   as_cedbene    // Codigo de Beneficiario
		//				   ad_fecemisol  // Fecha de Emision de la Solicitud
		//				   ai_totrow     // total de recepciones asociadas
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que elimina la solicitud de Pagos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 30/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fecemisol=$this->io_funciones->uf_convertirdatetobd($ad_fecemisol);
		/*$lb_valido=$this->uf_verificar_solicitudeliminar($as_numsol);
		if($lb_valido)
		{*/
			$this->io_sql->begin_transaction();	
			$lb_valido=$this->io_fecha->uf_valida_fecha_periodo($ad_fecemisol,$this->ls_codemp);
			if (!$lb_valido)
			{
				$this->io_mensajes->message($this->io_fecha->is_msg_error);           
				return false;
			}
			$rs_data=$this->uf_load_recepciones($as_numsol);
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_numrecdoc=$row["numrecdoc"];
				$ls_codtipdoc=$row["codtipdoc"];
				$lb_valido=$this->uf_update_estatus_procedencia($ls_numrecdoc,$ls_codtipdoc,$as_cedbene,$as_codpro,"R",$aa_seguridad);	
				if($lb_valido)
				{
					$lb_valido=$this->uf_insert_historico_recepciones($ls_numrecdoc,$ls_codtipdoc,$as_cedbene,$as_codpro,
																	  $ad_fecemisol,"R",$aa_seguridad);	
				}
			}
			$lb_valido=$this->uf_delete_detalles($as_numsol,$aa_seguridad);
			if($lb_valido)
			{
				$ls_sql="DELETE FROM cxp_solicitudes ".
						" WHERE codemp = '".$this->ls_codemp."' ".
						"	AND numsol = '".$as_numsol."' ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_sql->rollback();
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="DELETE";
					$ls_descripcion ="Elimino la solicitud de pago ".$as_numsol." Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////	
					if($lb_valido)
					{	
						$this->io_mensajes->message("La Solicitud fue Eliminada.");
						$this->io_sql->commit();
					}
					else
					{
						$lb_valido=false;
						$this->io_mensajes->message("Ocurrio un Error al Eliminar la Solicitud."); 
						$this->io_sql->rollback();
					}
				}
			}
			else
			{
				$this->io_mensajes->message("Ocurrio un Error al Eliminar la Solicitud."); 
				$this->io_sql->rollback();
			}
		/*}
		else
		{
			$this->io_mensajes->message("No se pueden eliminar solicitudes intermedias, si la desea dejar sin efecto debe anular la solicitud"); 
			$lb_valido=false;
		}*/
		return $lb_valido;
	}// end function uf_delete_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_detalles($as_numsol,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_detalles
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que elimina los detalles de una solicitud de pago
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 30/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM cxp_dt_solicitudes ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND numsol = '".$as_numsol."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			print $this->io_sql->message;
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM cxp_historico_solicitud ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND numsol = '".$as_numsol."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				print $this->io_sql->message;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			if($lb_valido)
			{
				$ls_sql="DELETE FROM cxp_solicitudes_scg ".
						" WHERE codemp = '".$this->ls_codemp."' ".
						"   AND numsol = '".$as_numsol."' ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				if($lb_valido)
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="DELETE";
					$ls_descripcion ="Eliminó todos los detalles de la solicitud de pago ".$as_numsol." Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				}
			}
		}
		return $lb_valido;
	}// end function uf_delete_detalles
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_historicord($as_numrecdoc, $as_codtipdoc, $as_cedbene, $as_codpro, $ad_fecemisol,$as_estatus)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_historicord
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de Recepcion de Documentos
		//				   as_codtipdoc  // Codigo de tipo de documento
		//				   as_cedbene    // Cedula de Beneficiario
		//				   as_codpro     // Código Proveedor
		//                 ad_fecemisol  // Fecha de emision de la solicitud
		//                 as_estatus    // Estatus del registro de R.D.
		//	  Description: Función que verifica si existe un registro en el historico de la recepcion de documentos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 01/05/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT numrecdoc ".
				"  FROM cxp_historico_rd  ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numrecdoc='".$as_numrecdoc."'".
				"   AND codtipdoc='".$as_codtipdoc."'".
				"   AND ced_bene='".trim($as_cedbene)."'".
				"   AND cod_pro='".$as_codpro."'".
				"   AND fecha='".$ad_fecemisol."'".
				"   AND estprodoc='".$as_estatus."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_select_historicord ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
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
	}// end function uf_select_historicord
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_solicitudeliminar($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_solicitudeliminar
		//		   Access: private
		//	    Arguments: as_numsol  //  Número de Solicitud
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el numero de solicitud de pago es la ultima que esta registrado
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 26/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
	   switch ($_SESSION["ls_gestor"])
	   {
	   		case "INFORMIX":
				$ls_sql="SELECT LIMIT 1 numsol ".
						"  FROM cxp_solicitudes ".
						" WHERE codemp='".$this->ls_codemp."' ".
						" ORDER BY numsol DESC ";
			break;
			
			default: // MYSQLT POSTGRES
				$ls_sql="SELECT numsol ".
						"  FROM cxp_solicitudes ".
						" WHERE codemp='".$this->ls_codemp."' ".
						" ORDER BY fecemisol DESC, numsol DESC LIMIT 1";
			break;
	   }
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_verificar_solicitudeliminar ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_solicitud=$row["numsol"];
				if($ls_solicitud==$as_numsol)
				{
					$lb_valido=true;
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_verificar_solicitudeliminar
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_verificar_cierre($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_cierre
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de Recepcion de Documentos
		//				   as_codtipdoc  // Codigo de tipo de documento
		//				   as_cedbene    // Cedula de Beneficiario
		//				   as_codpro     // Código Proveedor
		//                 ad_fecemisol  // Fecha de emision de la solicitud
		//                 as_estatus    // Estatus del registro de R.D.
		//	  Description: Función que verifica si existe un registro en el historico de la recepcion de documentos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 01/05/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT cxp_rd.numrecdoc,".
				"		(SELECT count(cxp_rd_spg.numrecdoc) ".
				"		   FROM cxp_rd_spg ".
				"		  WHERE cxp_rd.codemp=cxp_rd_spg.codemp ".
				"			AND cxp_rd.numrecdoc=cxp_rd_spg.numrecdoc ".
				"			AND cxp_rd.codtipdoc=cxp_rd_spg.codtipdoc ".
				"			AND cxp_rd.cod_pro=cxp_rd_spg.cod_pro".
				"			AND cxp_rd.ced_bene=cxp_rd_spg.ced_bene) as rowspg,".
				"		(SELECT count(cxp_rd_scg.numrecdoc) ".
				"		   FROM cxp_rd_scg ".
				"		  WHERE cxp_rd.codemp=cxp_rd_scg.codemp ".
				"			AND cxp_rd.numrecdoc=cxp_rd_scg.numrecdoc ".
				"			AND cxp_rd.codtipdoc=cxp_rd_scg.codtipdoc ".
				"			AND cxp_rd.cod_pro=cxp_rd_scg.cod_pro".
				"			AND cxp_rd.ced_bene=cxp_rd_scg.ced_bene) as rowscg ".
				"  FROM cxp_rd".
				" WHERE cxp_rd.codemp= '".$this->ls_codemp."'".
				"   AND cxp_rd.numrecdoc= '".$as_numrecdoc."' ".
				"   AND cxp_rd.codtipdoc= '".$as_codtipdoc."' ".
				"   AND cxp_rd.cod_pro= '".$as_codpro."' ".
				"   AND cxp_rd.ced_bene='".$as_cedbene."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_verificar_cierre ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_rowspg=$row["rowspg"];
				$ls_rowscg=$row["rowscg"];
				if($ls_rowspg>=1)
				{
					$lb_val=$this->io_cxp->uf_verificar_cierre_spg("../",$ls_estciespg);
					if($ls_estciespg=="1")
					{
						$this->io_mensajes->message("Esta procesado el cierre presupuestario");
						return true;
					}
					
				}
				if($ls_rowscg>=1)
				{
					$lb_val=$this->io_cxp->uf_verificar_cierre_scg("../",$ls_estciescg);
					if($ls_estciescg=="1")
					{
						$this->io_mensajes->message("Esta procesado el cierre contable");
						return true;
					}
					
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_verificar_cierre
	//-----------------------------------------------------------------------------------------------------------------------------------	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_archivoformato($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_archivoformato
		//		   Access: private
		//	    Arguments: $as_sistema  // Sistema al que pertenece la variable
		//				   $as_seccion  // Sección a la que pertenece la variable
		//				   $as_variable  // Variable nombre de la variable a buscar
		//				   $as_valor  // valor por defecto que debe tener la variable
		//				   $as_tipo  // tipo de la variable
		//	  Description: Método que busca el fisico del reporte 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/01/2009								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_valor="";
		$ls_sql="SELECT value ".
				"  FROM sigesp_config ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codsis='".$as_sistema."' ".
				"   AND seccion='".$as_seccion."' ".
				"   AND entry='".$as_variable."' ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud->uf_load_archivoformato ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_valor=$row["value"];
			}
			else
			{
				$ls_valor=$as_valor;
			}
			$this->io_sql->free_result($rs_data);		
		}
		return rtrim($ls_valor);
	}// end function uf_validar_fecha_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>