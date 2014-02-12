<?php
class sigesp_cxp_c_anulacionsolicitudpago
 {
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_cxp_c_anulacionsolicitudpago($as_path)
	{	
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_cxp_c_anulacionsolicitudpago
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 24/12/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		require_once("class_funciones_cxp.php");
		$this->io_cxp= new class_funciones_cxp();
	}// end function sigesp_cxp_c_anulacionsolicitudpago
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sep_p_solicitud.php)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 24/12/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
	function uf_load_solicitudes($as_numsol,$ad_fecemides,$ad_fecemihas,$as_tipproben,$as_proben,$as_tipooperacion)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_solicitudes
		//		   Access: public
		//		 Argument: as_numsol        // Numero de la solicitud de ejecucion presupuestaria
		//                 ad_fecemides     // Fecha (Emision) de inicio de la Busqueda
		//                 ad_fecemihas     // Fecha (Emision) de fin de la Busqueda
		//                 as_tipproben     // tipo proveedor/ beneficiario
		//                 as_proben        // Codigo de proveedor/ beneficiario
		//                 as_tipooperacion // Indica el estatus de la solicitud de pago a Buscar
		//	  Description: Función que busca las solicitudes de ordenes de pago a anular
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 24/12/2007								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="CONCAT(nombene,' ',apebene)";
				break;
			case "POSTGRES":
				$ls_cadena="nombene||' '||apebene";
				break;
			case "INFORMIX":
				$ls_cadena="nombene||' '||apebene";
				break;
		}
		$ls_sql="SELECT cxp_solicitudes.numsol,cxp_solicitudes.estprosol,cxp_solicitudes.monsol,".
				"       cxp_solicitudes.estaprosol,cxp_solicitudes.fecemisol,".
				"       (CASE WHEN cxp_solicitudes.tipproben='B' THEN (SELECT ".$ls_cadena." ".
				"                                                        FROM rpc_beneficiario".
				"                                                       WHERE cxp_solicitudes.codemp=rpc_beneficiario.codemp".
				"                                                         AND cxp_solicitudes.ced_bene=rpc_beneficiario.ced_bene)".
				"             WHEN cxp_solicitudes.tipproben='P' THEN (SELECT nompro".
				"                                                        FROM rpc_proveedor".
				"                                                       WHERE cxp_solicitudes.codemp=rpc_proveedor.codemp".
				"                                                         AND cxp_solicitudes.cod_pro=rpc_proveedor.cod_pro)".
				"                                                ELSE 'NINGUNO'".
				"         END) AS nombre".
				"  FROM cxp_solicitudes".
				" WHERE cxp_solicitudes.codemp = '".$this->ls_codemp."'".
				"   AND cxp_solicitudes.numsol LIKE '".$as_numsol."' ".
				"   AND cxp_solicitudes.fecemisol >= '".$ad_fecemides."' ".
				"   AND cxp_solicitudes.fecemisol <= '".$ad_fecemihas."' ".
				"   AND cxp_solicitudes.estprosol='".$as_tipooperacion."'";
		if($as_tipproben=="B")
		{
			$ls_sql= $ls_sql." AND cxp_solicitudes.ced_bene LIKE '".$as_proben."'";
		}
		else
		{
			$ls_sql= $ls_sql." AND cxp_solicitudes.cod_pro LIKE '".$as_proben."'";
		}
		$ls_sql= $ls_sql." ORDER BY cxp_solicitudes.numsol ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Anulacion MÉTODO->uf_load_solicitudes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_solicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_estatus_solicitud($as_numsol,$as_estsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_estatus_solicitud
		//		   Access: private
		//	    Arguments: as_numsol  //  Número de Solicitud
		//				   as_estsol  //  Estatus de la Solicitud
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que valida el estatus de la solicitud 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/12/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT numsol ".
				"  FROM cxp_solicitudes ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsol='".$as_numsol."' ".
				"   AND estprosol='".$as_estsol."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Anulacion MÉTODO->uf_validar_estatus_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_validar_estatus_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_solicitud($as_numsol,$as_estsol,$ad_fechaanula,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_solicitud
		//		   Access: private
		//	    Arguments: as_numsol    //  Número de Solicitud
		//                 as_estsol    //  Estatus en que se desea colocar la solicitud
		//                 ad_fecaprosol //  Fecha de aprobacion de la solicitud
		//                 aa_seguridad //  Arreglo que contiene informacion de seguridad
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que actualiza el estatus de la solicitud 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/12/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=$this->io_fecha->uf_valida_fecha_periodo($ad_fechaanula,$this->ls_codemp);
		if (!$lb_valido)
		{
			$this->io_mensajes->message($this->io_fecha->is_msg_error);           
			return false;
		}
		$ls_usuario=$_SESSION["la_logusr"];
		if($as_estsol=="E")
		{
			$ad_fechaanula="1900-01-01";
		}
		$ad_fechaanula=$this->io_funciones->uf_convertirdatetobd($ad_fechaanula);
		$ls_sql="UPDATE cxp_solicitudes ".
				"   SET estprosol = '".$as_estsol."', ".
				"       fechaanula = '".$ad_fechaanula."'".
				" WHERE codemp = '".$this->ls_codemp."'".
				"	AND numsol = '".$as_numsol."' ";
		$this->io_sql->begin_transaction();				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Anulacion MÉTODO->uf_update_estatus_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			if($as_estsol=="N")
			{
				$ls_descripcion ="Anuló la Solicitud de Pago <b>".$as_numsol."</b> Asociado a la Empresa <b>".$this->ls_codemp."<b>";
			}
			else
			{
				$ls_descripcion ="Reversó la Anulación de la Solicitud de Pago <b>".$as_numsol."</b> Asociado a la Empresa <b>".$this->ls_codemp."<b>";
			}
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			if($lb_valido)
			{
				$lb_valido=$this->uf_update_estatus_recepcion($as_numsol,$aa_seguridad);
			}		
			if($lb_valido)
			{
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_estatus_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_recepcion($as_numsol,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_recepcion
		//		   Access: private
		//	    Arguments: as_numsol    //  Número de Solicitud
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que actualiza el estatus de la solicitud 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/12/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp,numrecdoc,codtipdoc,cod_pro,ced_bene".
				"  FROM cxp_dt_solicitudes".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND numsol='".$as_numsol."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Anulacion MÉTODO->uf_update_estatus_recepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codemp=$row["codemp"];
				$ls_numrecdoc=$row["numrecdoc"];
				$ls_codtipdoc=$row["codtipdoc"];
				$ls_cod_pro=$row["cod_pro"];
				$ls_ced_bene=$row["ced_bene"];
				$ls_sql="UPDATE cxp_rd ".
						"   SET estprodoc = 'R' ".
						" WHERE codemp = '".$this->ls_codemp."'".
						"	AND numrecdoc = '".$ls_numrecdoc."' ".
						"   AND codtipdoc ='".$ls_codtipdoc."'".
						"   AND cod_pro='".$ls_cod_pro."'".
						"   AND ced_bene='".$ls_ced_bene."'";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					return false;
					$this->io_mensajes->message("CLASE->Anulacion MÉTODO->uf_update_estatus_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
					$lb_valido=true;
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_update_estatus_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_solicitudes($as_numsol)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_solicitudes
		//		   Access: public
		//		 Argument: as_numsol        // Numero de la solicitud de orden de pago
		//	  Description: Función que verifica que una solicitud este en estatus de Emitida
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/12/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT numsol".
				"  FROM cxp_solicitudes".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   AND numsol = '".$as_numsol."'".
				"   AND estprosol = 'N' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Anulacion MÉTODO->uf_validar_solicitudes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
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
	}// end function uf_validar_solicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_recepciones($as_numsol,&$lb_imprimir)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_estatus_solicitud
		//		   Access: private
		//	    Arguments: as_numsol  //  Número de Solicitud
		//				   as_estsol  //  Estatus de la Solicitud
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que valida el estatus de aprobacion de la solicitud 
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 02/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_imprimir=true;
		$lb_valido=true;
		$ls_sql="SELECT numrecdoc,codtipdoc,cod_pro,ced_bene ".
				"  FROM cxp_dt_solicitudes ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsol='".$as_numsol."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Aprobacion MÉTODO->uf_validar_estatus_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_numrecdoc=$row["numrecdoc"];
				$ls_codtipdoc=$row["codtipdoc"];
				$ls_codpro=$row["cod_pro"];
				$ls_cedbene=$row["ced_bene"];
				$lb_cierre=$this->uf_verificar_cierre($ls_numrecdoc,$ls_codtipdoc,$ls_cedbene,$ls_codpro);
				if($lb_cierre)
				{
					$lb_imprimir=false;
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_validar_estatus_solicitud
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
			$this->io_mensajes->message("CLASE->Aprobacion MÉTODO->uf_verificar_cierre ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
					$lb_val=$this->io_cxp->uf_verificar_cierre_spg("../../",$ls_estciespg);
					if($ls_estciespg=="1")
					{
						$this->io_mensajes->message("Esta procesado el cierre presupuestario");
						return true;
					}
					
				}
				if($ls_rowscg>=1)
				{
					$lb_val=$this->io_cxp->uf_verificar_cierre_scg("../../",$ls_estciescg);
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
	function uf_verificar_montos_recepciones($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_estatus_solicitud
		//		   Access: private
		//	    Arguments: as_numsol  //  Número de Solicitud
		//				   as_estsol  //  Estatus de la Solicitud
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que valida el estatus de aprobacion de la solicitud 
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 02/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT monsol,".
				"       (SELECT SUM(montotdoc) FROM cxp_dt_solicitudes,cxp_rd".
				"         WHERE cxp_dt_solicitudes.codemp=cxp_solicitudes.codemp".
				"           AND cxp_dt_solicitudes.numsol=cxp_solicitudes.numsol".
				"           AND cxp_dt_solicitudes.codemp=cxp_rd.codemp".
				"           AND cxp_dt_solicitudes.numrecdoc=cxp_rd.numrecdoc".
				"           AND cxp_dt_solicitudes.codtipdoc=cxp_rd.codtipdoc".
				"           AND cxp_dt_solicitudes.cod_pro=cxp_rd.cod_pro".
				"           AND cxp_dt_solicitudes.ced_bene=cxp_rd.ced_bene) AS totalrecepciones".
				"  FROM cxp_solicitudes ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsol='".$as_numsol."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Aprobacion MÉTODO->uf_validar_estatus_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_monsol=$row["monsol"];
				$li_totalrecepciones=$row["totalrecepciones"];
				if($li_monsol!=$li_totalrecepciones)
				{
					$lb_valido=false;
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_validar_estatus_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
}
?>