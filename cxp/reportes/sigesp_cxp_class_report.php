<?php
class sigesp_cxp_class_report
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_cxp_class_report($as_path="../../")
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sep_class_report
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno /Ing. Luis Lang
		// Fecha Creación: 11/03/2007 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once($as_path."shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$this->io_conexion=$io_include->uf_conectar();
		require_once($as_path."shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($this->io_conexion);	
		$this->DS=new class_datastore();
		$this->ds_detalle=new class_datastore();
		require_once($as_path."shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once($as_path."shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once($as_path."shared/class_folder/class_fecha.php");
		$this->io_fecha=new class_fecha();		
		require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad=new sigesp_c_seguridad();		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->rs_data ="";
	}// end function sigesp_sep_class_report
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitud($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitud
		//         Access: public (sigesp_sep_p_solicitud)  
		//	    Arguments: as_numsol     // Numero de solicitud 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la una solicitud de pago
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 14/05/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRES":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
			case "INFORMIX":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT cxp_solicitudes.numsol, cxp_solicitudes.cod_pro, cxp_solicitudes.ced_bene, cxp_solicitudes.codfuefin,".
				"       cxp_solicitudes.tipproben, cxp_solicitudes.fecemisol, cxp_solicitudes.consol, cxp_solicitudes.estprosol,".
				"       cxp_solicitudes.monsol, cxp_solicitudes.obssol, cxp_solicitudes.estaprosol,".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre, ".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.rifpro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT rpc_beneficiario.rifben ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE '-' END ) AS rifpro, ".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.dirpro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT rpc_beneficiario.dirbene ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE '-' END ) AS dirproben, ".
				"       (SELECT denfuefin".
				"		   FROM sigesp_fuentefinanciamiento".
				"         WHERE sigesp_fuentefinanciamiento.codemp=cxp_solicitudes.codemp".
				"           AND sigesp_fuentefinanciamiento.codfuefin=cxp_solicitudes.codfuefin) AS denfuefin,".
				"       (SELECT MAX(nomban)".
				"		   FROM scb_banco,cxp_sol_banco".
				"         WHERE cxp_sol_banco.codemp=cxp_solicitudes.codemp".
				"           AND cxp_sol_banco.numsol=cxp_solicitudes.numsol".
				"           AND cxp_sol_banco.codban=scb_banco.codban GROUP BY cxp_sol_banco.numsol) AS nomban,".
				"       (SELECT MAX(ctaban)".
				"		   FROM cxp_sol_banco".
				"         WHERE cxp_sol_banco.codemp=cxp_solicitudes.codemp".
				"           AND cxp_sol_banco.numsol=cxp_solicitudes.numsol GROUP BY cxp_sol_banco.numsol) AS ctaban,".
				"       (SELECT MAX(nomtipcta)".
				"		   FROM cxp_sol_banco,scb_ctabanco,scb_tipocuenta".
				"         WHERE cxp_sol_banco.codemp=cxp_solicitudes.codemp".
				"           AND cxp_sol_banco.numsol=cxp_solicitudes.numsol".
				"           AND cxp_sol_banco.ctaban=scb_ctabanco.ctaban".
				"           AND cxp_sol_banco.codban=scb_ctabanco.codban".
				"           AND scb_ctabanco.codtipcta=scb_tipocuenta.codtipcta GROUP BY cxp_sol_banco.numsol) AS tipcta".
				"  FROM cxp_solicitudes ".	
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsol='".$as_numsol."' "; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_select_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitud_tipodocumento($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitud_tipodocumento
		//         Access: public
		//	    Arguments: as_numsol     // Numero de solicitud 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la una solicitud de pago y los tipos de documento que presentan
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 14/05/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRES":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
			case "INFORMIX":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT DISTINCT(cxp_solicitudes.numsol), cxp_solicitudes.cod_pro, cxp_solicitudes.ced_bene, cxp_solicitudes.codfuefin,".
				"       cxp_solicitudes.tipproben, cxp_solicitudes.fecemisol, cxp_solicitudes.consol, cxp_solicitudes.estprosol,".
				"       cxp_solicitudes.monsol, cxp_solicitudes.obssol, cxp_solicitudes.estaprosol,cxp_rd.codtipdoc,".
				"       cxp_documento.dentipdoc,".
				"       (CASE cxp_solicitudes.tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                      				  	 FROM rpc_proveedor ".
				"                                       				WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         				  AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       				WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                       				 FROM rpc_beneficiario ".
				"                                       				WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                       				  AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       				ELSE 'NINGUNO' END ) AS nombre, ".
				"       (CASE cxp_solicitudes.tipproben WHEN 'P' THEN (SELECT rpc_proveedor.rifpro ".
				"                                        				 FROM rpc_proveedor ".
				"                                       				WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         				  AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       				WHEN 'B' THEN (SELECT rpc_beneficiario.rifben ".
				"                                       				 FROM rpc_beneficiario ".
				"                                       				WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         				  AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       				ELSE '-' END ) AS rifpro, ".
				"       (SELECT denfuefin".
				"		   FROM sigesp_fuentefinanciamiento".
				"         WHERE sigesp_fuentefinanciamiento.codemp=cxp_solicitudes.codemp".
				"           AND sigesp_fuentefinanciamiento.codfuefin=cxp_solicitudes.codfuefin) AS denfuefin,".
				"       (SELECT dencla".
				"		   FROM cxp_clasificador_rd".
				"         WHERE cxp_clasificador_rd.codcla=cxp_rd.codcla) AS dencla".
				"  FROM cxp_solicitudes,cxp_dt_solicitudes,cxp_rd,cxp_documento ".	
				" WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"   AND cxp_solicitudes.numsol='".$as_numsol."' ".
				"   AND cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp".
				"   AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol".
				"   AND cxp_dt_solicitudes.codemp=cxp_rd.codemp".
				"   AND cxp_dt_solicitudes.numrecdoc=cxp_rd.numrecdoc".
				"   AND cxp_dt_solicitudes.ced_bene=cxp_rd.ced_bene".
				"   AND cxp_dt_solicitudes.cod_pro=cxp_rd.cod_pro".
				"   AND cxp_dt_solicitudes.codtipdoc=cxp_rd.codtipdoc".
				"   AND cxp_dt_solicitudes.codtipdoc=cxp_documento.codtipdoc".
				"   AND cxp_rd.codtipdoc=cxp_documento.codtipdoc";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_rec_doc_solicitud($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_rec_doc_solicitud
		//         Access: public (sigesp_sep_p_solicitud)  
		//	    Arguments: as_numsol     // Numero de solicitud 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de recepciones de documentos asociadas a  una solicitud de pago
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 17/05/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;				
		$this->ds_detalle_rec = new class_datastore();
		$ls_sql="SELECT cxp_dt_solicitudes.numsol,MAX(cxp_dt_solicitudes.codtipdoc) AS codtipdoc ,MAX(cxp_dt_solicitudes.cod_pro) AS cod_pro,".
				"       MAX(cxp_dt_solicitudes.ced_bene) AS ced_bene,cxp_rd.numrecdoc, cxp_documento.dentipdoc,".
				"       cxp_rd.montotdoc,cxp_rd.mondeddoc, cxp_rd.moncardoc,cxp_rd.fecemidoc,cxp_rd.procede,".
				"       (SELECT MAX(procede_doc)".
				"          FROM cxp_rd_scg".
				"         WHERE cxp_rd_scg.codemp=cxp_rd.codemp".
				"           AND cxp_rd_scg.numrecdoc=cxp_rd.numrecdoc".
				"           AND cxp_rd_scg.codtipdoc=cxp_rd.codtipdoc".
				"           AND cxp_rd_scg.ced_bene=cxp_rd.ced_bene".
				"           AND cxp_rd_scg.cod_pro=cxp_rd.cod_pro ".
				"           AND cxp_rd_scg.debhab='D') AS procede_docscg,".
				"       (SELECT MAX(procede_doc)".
				"          FROM cxp_rd_spg".
				"         WHERE cxp_rd_spg.codemp=cxp_rd.codemp".
				"           AND cxp_rd_spg.numrecdoc=cxp_rd.numrecdoc".
				"           AND cxp_rd_spg.codtipdoc=cxp_rd.codtipdoc".
				"           AND cxp_rd_spg.ced_bene=cxp_rd.ced_bene".
				"           AND cxp_rd_spg.cod_pro=cxp_rd.cod_pro) AS procede_docspg,".
				"       (SELECT MAX(numdoccom)".
				"          FROM cxp_rd_scg".
				"         WHERE cxp_rd_scg.codemp=cxp_rd.codemp".
				"           AND cxp_rd_scg.numrecdoc=cxp_rd.numrecdoc".
				"           AND cxp_rd_scg.codtipdoc=cxp_rd.codtipdoc".
				"           AND cxp_rd_scg.ced_bene=cxp_rd.ced_bene".
				"           AND cxp_rd_scg.cod_pro=cxp_rd.cod_pro ".
				"           AND cxp_rd_scg.debhab='D') AS numdoccomscg,".
				"       (SELECT MAX(numdoccom)".
				"          FROM cxp_rd_spg".
				"         WHERE cxp_rd_spg.codemp=cxp_rd.codemp".
				"           AND cxp_rd_spg.numrecdoc=cxp_rd.numrecdoc".
				"           AND cxp_rd_spg.codtipdoc=cxp_rd.codtipdoc".
				"           AND cxp_rd_spg.ced_bene=cxp_rd.ced_bene".
				"           AND cxp_rd_spg.cod_pro=cxp_rd.cod_pro) AS numdoccomspg".
				"  FROM cxp_dt_solicitudes, cxp_solicitudes, cxp_rd, cxp_documento".
				" WHERE cxp_dt_solicitudes.codemp='".$this->ls_codemp."'".
				"   AND cxp_dt_solicitudes.numsol='".$as_numsol."'".
				"   AND cxp_dt_solicitudes.codemp=cxp_solicitudes.codemp".
				"   AND cxp_dt_solicitudes.codemp=cxp_rd.codemp".
				"   AND cxp_rd.codemp=cxp_solicitudes.codemp".
				"   AND cxp_dt_solicitudes.numsol=cxp_solicitudes.numsol".
				"   AND cxp_dt_solicitudes.numrecdoc=cxp_rd.numrecdoc".
				"   AND cxp_documento.codtipdoc=cxp_rd.codtipdoc".
				"   AND cxp_dt_solicitudes.cod_pro=cxp_rd.cod_pro".
				"   AND cxp_dt_solicitudes.ced_bene=cxp_rd.ced_bene".
				" GROUP BY cxp_rd.codemp, cxp_dt_solicitudes.numsol,cxp_rd.numrecdoc,cxp_rd.codtipdoc,cxp_rd.cod_pro,cxp_rd.ced_bene, cxp_documento.dentipdoc,".
				"       cxp_rd.montotdoc,cxp_rd.mondeddoc, cxp_rd.moncardoc,cxp_rd.fecemidoc,cxp_rd.procede".
				" ORDER BY cxp_rd.numrecdoc ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_rec_doc_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle_rec->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{   
				$lb_valido=false;			     
			}	
		}
		return $lb_valido;		
	}// end function uf_select_rec_doc_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------
	//------>Carlos Zambrano
		//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_compromiso_afectado($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_compromiso_afectado
		//         Access: public (sigesp_sep_p_solicitud)  
		//	    Arguments: as_numsol     // Numero de solicitud 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información presupuestaria asociada a una solicitud de pago
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;				
		//$this->ds_comp_afec = new class_datastore();
		$ls_sql="SELECT MAX(cxp_rd_spg.numdoccom) as numdoccom".
				"  FROM cxp_solicitudes,cxp_dt_solicitudes,cxp_rd_spg,cxp_documento".
				" WHERE cxp_dt_solicitudes.numsol='".$as_numsol."'".
				"   AND cxp_documento.estcon='1'".
				"   AND cxp_documento.estpre='1'".
				"   AND cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp".
				"   AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol".
				"   AND cxp_rd_spg.codemp=cxp_dt_solicitudes.codemp".
				"   AND cxp_rd_spg.numrecdoc=cxp_dt_solicitudes.numrecdoc".
				"   AND cxp_rd_spg.codtipdoc=cxp_dt_solicitudes.codtipdoc".
				"   AND cxp_rd_spg.ced_bene=cxp_dt_solicitudes.ced_bene".
				"   AND cxp_rd_spg.cod_pro=cxp_dt_solicitudes.cod_pro".
				"   AND cxp_rd_spg.codtipdoc=cxp_documento.codtipdoc";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_compromiso_afectado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_comafec=$row["numdoccom"];		
			}
			else
			{   
				$lb_valido=false;			     
			}	
		}
		return $ls_comafec;		
	}// end function uf_select_detalle_spg
	//-----------------------------------------------------------------------------------------------------------------------------------

	
	
	
	
	//------>Carlos Zambrano
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_detalle_spg($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_detalle_spg
		//         Access: public (sigesp_sep_p_solicitud)  
		//	    Arguments: as_numsol     // Numero de solicitud 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información presupuestaria asociada a una solicitud de pago
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;				
		$this->ds_detalle_spg = new class_datastore();
		$ls_sql="SELECT cxp_dt_solicitudes.numsol,cxp_rd_spg.codestpro,cxp_rd_spg.spg_cuenta, sum(cxp_rd_spg.monto) AS monto ,max(spg_cuentas.denominacion) as denominacion".
				"  FROM cxp_rd_spg,cxp_dt_solicitudes,spg_cuentas".
				" WHERE cxp_dt_solicitudes.codemp='".$this->ls_codemp."'".
				"   AND cxp_dt_solicitudes.numsol='".$as_numsol."'".
				"   AND cxp_rd_spg.codemp=cxp_dt_solicitudes.codemp".
				"   AND cxp_rd_spg.numrecdoc=cxp_dt_solicitudes.numrecdoc".
				"   AND cxp_rd_spg.cod_pro=cxp_dt_solicitudes.cod_pro".
				"   AND cxp_rd_spg.ced_bene=cxp_dt_solicitudes.ced_bene".
				"   AND cxp_rd_spg.codtipdoc=cxp_dt_solicitudes.codtipdoc".
				"   AND spg_cuentas.codemp=cxp_rd_spg.codemp".
				"   AND spg_cuentas.spg_cuenta=cxp_rd_spg.spg_cuenta".
				"   AND SUBSTR(cxp_rd_spg.codestpro,1,25)=spg_cuentas.codestpro1 ".
				"   AND SUBSTR(cxp_rd_spg.codestpro,26,25)=spg_cuentas.codestpro2 ".
				"   AND SUBSTR(cxp_rd_spg.codestpro,51,25)=spg_cuentas.codestpro3 ".
				"   AND SUBSTR(cxp_rd_spg.codestpro,76,25)=spg_cuentas.codestpro4 ".
				"   AND SUBSTR(cxp_rd_spg.codestpro,101,25)=spg_cuentas.codestpro5 ".
				" GROUP BY cxp_dt_solicitudes.numsol,cxp_rd_spg.codestpro,cxp_rd_spg.spg_cuenta";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_detalle_spg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle_spg->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{   
				$lb_valido=false;			     
			}	
		}
		return $lb_valido;		
	}// end function uf_select_detalle_spg
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_detalle_scg($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_detalle_scg
		//         Access: public (sigesp_sep_p_solicitud)  
		//	    Arguments: as_numsol     // Numero de solicitud 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información presupuestaria asociada a una solicitud de pago
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;				
		$this->ds_detalle_scg = new class_datastore();
		$ls_sql="SELECT cxp_rd_scg.sc_cuenta,cxp_rd_scg.monto,cxp_rd_scg.debhab,scg_cuentas.denominacion".
				" FROM cxp_rd_scg,cxp_dt_solicitudes,scg_cuentas".
				" WHERE cxp_dt_solicitudes.codemp='".$this->ls_codemp."'".
				"   AND cxp_dt_solicitudes.numsol='".$as_numsol."'".
				"   AND cxp_rd_scg.numrecdoc=cxp_dt_solicitudes.numrecdoc".
				"   AND cxp_rd_scg.codemp=cxp_dt_solicitudes.codemp".
				"   AND cxp_rd_scg.cod_pro=cxp_dt_solicitudes.cod_pro".
				"   AND cxp_rd_scg.ced_bene=cxp_dt_solicitudes.ced_bene".
				"   AND cxp_rd_scg.codtipdoc=cxp_dt_solicitudes.codtipdoc".
				"   AND scg_cuentas.codemp=cxp_rd_scg.codemp".
				"   AND trim(scg_cuentas.sc_cuenta)=trim(cxp_rd_scg.sc_cuenta)".
				" ORDER BY cxp_rd_scg.debhab";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_detalle_scg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle_scg->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{   
				$lb_valido=false;			     
			}	
		}
		return $lb_valido;		
	}// end function uf_select_detalle_scg
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_detalleconrecdoc_scg($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_detalle_scg
		//         Access: public (sigesp_sep_p_solicitud)  
		//	    Arguments: as_numsol     // Numero de solicitud 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información presupuestaria asociada a una solicitud de pago
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;				
		$this->ds_detalle_scg = new class_datastore();
		$ls_sql="SELECT cxp_solicitudes_scg.sc_cuenta,cxp_solicitudes_scg.monto,cxp_solicitudes_scg.debhab,scg_cuentas.denominacion".
				" FROM cxp_solicitudes_scg,cxp_dt_solicitudes,scg_cuentas".
				" WHERE cxp_dt_solicitudes.codemp='".$this->ls_codemp."'".
				"   AND cxp_dt_solicitudes.numsol='".$as_numsol."'".
				"   AND cxp_solicitudes_scg.numrecdoc=cxp_dt_solicitudes.numrecdoc".
				"   AND cxp_solicitudes_scg.codemp=cxp_dt_solicitudes.codemp".
				"   AND cxp_solicitudes_scg.cod_pro=cxp_dt_solicitudes.cod_pro".
				"   AND cxp_solicitudes_scg.ced_bene=cxp_dt_solicitudes.ced_bene".
				"   AND cxp_solicitudes_scg.codtipdoc=cxp_dt_solicitudes.codtipdoc".
				"   AND scg_cuentas.codemp=cxp_solicitudes_scg.codemp".
				"   AND trim(scg_cuentas.sc_cuenta)=trim(cxp_solicitudes_scg.sc_cuenta)".
				" ORDER BY cxp_solicitudes_scg.debhab";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_detalleconrecdoc_scg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle_scg->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{   
				$lb_valido=false;			     
			}	
		}
		return $lb_valido;		
	}// end function uf_select_detalleconrecdoc_scg
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_recepcion($as_numrecdoc,$as_codpro,$as_cedben,$as_codtipdoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_recepcion
		//         Access: public  
		//	    Arguments: as_numrecdoc  // Numero de solicitud
		//                 as_codpro     // Codigo del Proveedor
		//                 as_cedben     // Cedula del Beneficiario
		//                 as_codtipdoc  // Codigo de Tipo de Documento
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la una solicitud de pago
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 21/05/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRES":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
			case "INFORMIX":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT cxp_rd.*,cxp_documento.dentipdoc, ".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_rd.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_rd.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_rd.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_rd.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre, ".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.rifpro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_rd.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_rd.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT rpc_beneficiario.ced_bene ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_rd.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_rd.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS rif ".
				"  FROM cxp_rd,cxp_documento ".	
				" WHERE cxp_rd.codemp='".$this->ls_codemp."' ".
				"   AND cxp_rd.numrecdoc='".$as_numrecdoc."' ".
				"   AND cxp_rd.codtipdoc='".$as_codtipdoc."' ".
				"   AND cxp_rd.cod_pro='".$as_codpro."' ".
				"   AND cxp_rd.ced_bene='".$as_cedben."' ".
				"   AND cxp_rd.codtipdoc=cxp_documento.codtipdoc";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_recepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_select_recepcion
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_detalle_recepcionspg($as_numrecdoc,$as_codpro,$as_cedben,$as_codtipdoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_detalle_recepcionspg
		//         Access: public 
		//	    Arguments: as_numrecdoc  // Numero de solicitud
		//                 as_codpro     // Codigo del Proveedor
		//                 as_cedben     // Cedula del Beneficiario
		//                 as_codtipdoc  // Codigo de Tipo de Documento
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información presupuestaria asociada a una recepcion de documentos
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 22/05/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;				
		$this->ds_detalle_spg = new class_datastore();
		$ls_sql="SELECT codestpro,numrecdoc,spg_cuenta,monto,numdoccom".
				" FROM cxp_rd_spg".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numrecdoc='".$as_numrecdoc."' ".
				"   AND codtipdoc='".$as_codtipdoc."' ".
				"   AND cod_pro='".$as_codpro."' ".
				"   AND ced_bene='".$as_cedben."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_detalle_recepcionspg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle_spg->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{   
				$lb_valido=false;			     
			}	
		}
		return $lb_valido;		
	}// end function uf_select_detalle_recepcionspg
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_detalle_recepcionscg($as_numrecdoc,$as_codpro,$as_cedben,$as_codtipdoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_detalle_recepcionscg
		//         Access: public   
		//	    Arguments: as_numrecdoc  // Numero de solicitud
		//                 as_codpro     // Codigo del Proveedor
		//                 as_cedben     // Cedula del Beneficiario
		//                 as_codtipdoc  // Codigo de Tipo de Documento
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información presupuestaria asociada a una Recepcion de documentos
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 22/05/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;				
		$this->ds_detalle_scg = new class_datastore();
		$ls_sql="SELECT sc_cuenta,numrecdoc,debhab,monto,numdoccom".
				" FROM cxp_rd_scg".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numrecdoc='".$as_numrecdoc."' ".
				"   AND codtipdoc='".$as_codtipdoc."' ".
				"   AND cod_pro='".$as_codpro."' ".
				"   AND ced_bene='".$as_cedben."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_detalle_recepcionscg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle_scg->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{   
				$lb_valido=false;			     
			}	
		}
		return $lb_valido;		
	}// end function uf_select_detalle_recepcionscg
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_recepciones($as_tipproben,$as_codprobendes,$as_codprobenhas,$ad_fecregdes,$ad_fecreghas,$as_codtipdoc,
								   $ai_registrada,$ai_anulada,$ai_procesada,$as_orden)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_recepcion
		//         Access: public  
		//	    Arguments: as_tipproben     // Tipo de Proveedor/Beneficiario
		//                 as_codprobendes  // Codigo de Proveedor/Beneficiario Desde
		//                 as_codprobenhas  // Codigo de Proveedor/Beneficiario Hasta
		//                 ad_fecregdes     // Fecha de Registro Desde
		//                 ad_fecreghas     // Fecha de Registro Hasta
		//                 as_codtipdoc     // Codigo de Tipo de Documento
		//                 as_registrada    // Estatus de la Recepcion Registrada
		//                 ai_anulada       // Estatus de la Recepcion Anulada
		//                 ai_procesada     // Estatus de la Recepcion Procesada
		//                 ai_orden         // Orden de los Datos en el Reporte Numero/Fecha
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las recepciones de documentos en los intervalos indicados
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_tipproben))
		{
			$ls_criterio= $ls_criterio."   AND cxp_rd.tipproben='".$as_tipproben."'";
		}
		if(!empty($as_codprobendes))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND cxp_rd.cod_pro>='".$as_codprobendes."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND cxp_rd.ced_bene>='".$as_codprobendes."'";
			}
		}
		if(!empty($as_codprobenhas))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND cxp_rd.cod_pro<='".$as_codprobenhas."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND cxp_rd.ced_bene<='".$as_codprobenhas."'";
			}
		}
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND cxp_rd.fecregdoc>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND cxp_rd.fecregdoc<='".$ad_fecreghas."'";
		}

		if(!empty($as_codtipdoc) &&($as_codtipdoc!="-"))
		{
			$as_codtipdoc=substr($as_codtipdoc,0,5);
			$ls_criterio= $ls_criterio."   AND cxp_rd.codtipdoc='".$as_codtipdoc."'";
		}
		
		if(($ai_registrada==1)||($ai_procesada==1)||($ai_anulada==1))
		{
			$lb_anterior=false;
			if($ai_registrada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_rd.estprodoc='R'";
					$lb_anterior=true;
				}
			}
			if($ai_procesada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_rd.estprodoc='C'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR cxp_rd.estprodoc='C'";
				}
			}
			if($ai_anulada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_rd.estprodoc='A'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR cxp_rd.estprodoc='A'";
				}
			}
			if($lb_anterior)
			{
				$ls_criterio=$ls_criterio.")";
			}
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="cxp_rd.numrecdoc ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="cxp_rd.fecregdoc ";
				break;

		}
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRES":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
			case "INFORMIX":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT cxp_rd.numrecdoc,cxp_rd.fecemidoc,cxp_rd.fecregdoc,MAX(cxp_rd.montotdoc) AS montotdoc,".
				"       MAX(cxp_rd.mondeddoc) AS mondeddoc,MAX(cxp_rd.moncardoc) AS moncardoc,".
				"		MAX(cxp_documento.dentipdoc) AS dentipdoc,".
				"		(SELECT MAX(numdoccom) FROM cxp_rd_spg".
				" 		  WHERE cxp_rd.codemp=cxp_rd_spg.codemp AND cxp_rd.numrecdoc=cxp_rd_spg.numrecdoc".
				"           AND cxp_rd.codtipdoc=cxp_rd_spg.codtipdoc AND cxp_rd.cod_pro=cxp_rd_spg.cod_pro".
				"           AND cxp_rd.ced_bene=cxp_rd_spg.ced_bene) AS numdoccom,".
				"       (SELECT MAX(procede_doc) FROM cxp_rd_spg".
				" 		  WHERE cxp_rd.codemp=cxp_rd_spg.codemp AND cxp_rd.numrecdoc=cxp_rd_spg.numrecdoc".
				"           AND cxp_rd.codtipdoc=cxp_rd_spg.codtipdoc AND cxp_rd.cod_pro=cxp_rd_spg.cod_pro".
				"           AND cxp_rd.ced_bene=cxp_rd_spg.ced_bene) AS procede_doc,".
				"		(SELECT MAX(numdoccom) FROM cxp_rd_scg".
				" 		  WHERE cxp_rd.codemp=cxp_rd_scg.codemp AND cxp_rd.numrecdoc=cxp_rd_scg.numrecdoc".
				"           AND cxp_rd.codtipdoc=cxp_rd_scg.codtipdoc AND cxp_rd.cod_pro=cxp_rd_scg.cod_pro".
				"           AND cxp_rd.ced_bene=cxp_rd_scg.ced_bene) AS numdoccont,".
				"       (SELECT MAX(procede_doc) FROM cxp_rd_scg".
				" 		  WHERE cxp_rd.codemp=cxp_rd_scg.codemp AND cxp_rd.numrecdoc=cxp_rd_scg.numrecdoc".
				"           AND cxp_rd.codtipdoc=cxp_rd_scg.codtipdoc AND cxp_rd.cod_pro=cxp_rd_scg.cod_pro".
				"           AND cxp_rd.ced_bene=cxp_rd_scg.ced_bene) AS procede_cont,".
				"       (CASE MAX(tipproben) WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_rd.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_rd.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_rd.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_rd.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre ".
				"  FROM cxp_rd,cxp_documento ".	
				" WHERE cxp_rd.codemp='".$this->ls_codemp."' ".
				"   ".$ls_criterio." ".
				"   AND cxp_rd.codtipdoc=cxp_documento.codtipdoc".
				" GROUP BY cxp_rd.codemp,cxp_rd.cod_pro,cxp_rd.ced_bene,cxp_rd.numrecdoc,cxp_rd.codtipdoc,cxp_rd.fecemidoc,cxp_rd.fecregdoc".
				" ORDER BY ".$ls_orden."";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_recepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS=new class_datastore();
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_select_recepciones
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudes($as_tipproben,$as_codprobendes,$as_codprobenhas,$ad_fecregdes,$ad_fecreghas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitudes
		//         Access: public  
		//	    Arguments: as_tipproben     // Tipo de Proveedor/Beneficiario
		//                 as_codprobendes  // Codigo de Proveedor/Beneficiario Desde
		//                 as_codprobenhas  // Codigo de Proveedor/Beneficiario Hasta
		//                 ad_fecregdes     // Fecha de Registro Desde
		//                 ad_fecreghas     // Fecha de Registro Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las recepciones de documentos en los intervalos indicados
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_tipproben))
		{
			$ls_criterio= $ls_criterio."   AND cxp_solicitudes.tipproben='".$as_tipproben."'";
		}
		if(!empty($as_codprobendes))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.cod_pro>='".$as_codprobendes."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.ced_bene>='".$as_codprobendes."'";
			}
		}
		if(!empty($as_codprobenhas))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.cod_pro<='".$as_codprobenhas."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.ced_bene<='".$as_codprobenhas."'";
			}
		}
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.fecemisol>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.fecemisol<='".$ad_fecreghas."'";
		}

		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRES":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
			case "INFORMIX":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT MAX(cxp_solicitudes.tipproben) AS tipproben,cxp_solicitudes.cod_pro,cxp_solicitudes.ced_bene, ".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre ".
				"  FROM cxp_solicitudes,cxp_historico_solicitud ".	
				" WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"   AND cxp_historico_solicitud.codemp=cxp_solicitudes.codemp".
				"   AND cxp_historico_solicitud.numsol=cxp_solicitudes.numsol".
				"   AND cxp_historico_solicitud.estprodoc='C'".
				"   AND cxp_solicitudes.estprosol<>'A'".
				"   ".$ls_criterio." ".
				" GROUP BY cxp_solicitudes.codemp,cxp_solicitudes.ced_bene,cxp_solicitudes.cod_pro,cxp_solicitudes.tipproben".
				" ORDER BY cxp_solicitudes.ced_bene,cxp_solicitudes.cod_pro";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_solicitudes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_select_solicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_proveedores($as_tipproben,$as_codproben)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_proveedores
		//         Access: public  
		//	    Arguments: as_tipproben     // Tipo de Proveedor/Beneficiario
		//                 as_codprobendes  // Codigo de Proveedor/Beneficiario Desde
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información de los proveedores.
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 09/10/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_nombre="";
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRES":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		if($as_tipproben=="P")
		{
			$ls_sql="SELECT rpc_proveedor.nompro AS nombre".
					"  FROM rpc_proveedor ".
					" WHERE rpc_proveedor.codemp='".$this->ls_codemp."' ".
					"   AND rpc_proveedor.cod_pro='".$as_codproben."' ";
		}
		else
		{
			$ls_sql="SELECT ".$ls_cadena." AS nombre ".
					"  FROM rpc_beneficiario ".
					" WHERE rpc_beneficiario.codemp='".$this->ls_codemp."' ".
					"   AND rpc_beneficiario.ced_bene='".$as_codproben."'";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_proveedores ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_nombre=$row["nombre"];
				//$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $ls_nombre;
	}// end function uf_select_proveedores
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudes_previas($as_tipproben,$as_codpro,$as_cedbene,$ad_fecregdes,$ad_fecreghas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitudes_previas
		//         Access: public  
		//	    Arguments: as_tipproben  // Tipo de Proveedor/Beneficiario
		//                 as_codpro     // Codigo de Proveedor
		//                 as_cedbene    // Codigo de Beneficiario
		//                 ad_fecregdes  // Fecha de Registro Desde
		//                 ad_fecreghas  // Fecha de Registro Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las recepciones de documentos en los intervalos indicados
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
		$this->ds_solprevias= new class_datastore();
		$ls_cadena="";
		if($ad_fecregdes!="")
		{
			$ls_cadena="AND cxp_historico_solicitud.fecha < '".$ad_fecregdes."'";
		}
		$as_tipproben="%".$as_tipproben."%";
		$ls_sql=" SELECT  cxp_solicitudes.numsol, cxp_solicitudes.monsol, cxp_historico_solicitud.estprodoc AS estatus, cxp_historico_solicitud.fecha".
				"   FROM  cxp_solicitudes, cxp_historico_solicitud ".
				"  WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"    AND cxp_historico_solicitud.codemp=cxp_solicitudes.codemp".
				"    AND  cxp_solicitudes.numsol=cxp_historico_solicitud.numsol".
				"    AND cxp_solicitudes.tipproben LIKE'".$as_tipproben."'".
				"    AND cxp_solicitudes.ced_bene='".$as_cedbene."'".
				"    AND cxp_solicitudes.cod_pro='".$as_codpro."'".
				"    AND (cxp_historico_solicitud.estprodoc='C' OR cxp_historico_solicitud.estprodoc='A')".
				$ls_cadena.
				"  ORDER  BY cxp_solicitudes.ced_bene, cxp_solicitudes.cod_pro";
			//	print $ls_sql."<br><br>";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_solicitudes_previas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_solprevias->data=$this->io_sql->obtener_datos($rs_data);		
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_solicitudes_previas
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_pagosprevios($as_tipproben,$as_codpro,$as_cedbene,$ad_fecregdes,$ad_fecreghas,&$ad_pagosprevios,&$ad_retencionesprevios)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_pagosprevios
		//         Access: public  
		//	    Arguments: as_tipproben  // Tipo de Proveedor/Beneficiario
		//                 as_codpro     // Codigo de Proveedor
		//                 as_cedbene    // Codigo de Beneficiario
		//                 ad_fecregdes  // Fecha de Registro Desde
		//                 ad_fecreghas  // Fecha de Registro Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las recepciones de documentos en los intervalos indicados
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
		$ls_cadena="";
		if($ad_fecregdes!="")
		{
			$ls_cadena="AND cxp_historico_solicitud.fecha < '".$ad_fecregdes."'";
		}
		$ls_sql=" SELECT SUM(CASE WHEN cxp_solicitudes.monsol is null THEN 0 ELSE cxp_solicitudes.monsol END) AS pagos".
				"   FROM cxp_solicitudes, cxp_historico_solicitud, cxp_sol_banco".
				"  WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"    AND cxp_historico_solicitud.codemp=cxp_solicitudes.codemp".
				"    AND cxp_solicitudes.numsol=cxp_historico_solicitud.numsol".
				"    AND cxp_historico_solicitud.estprodoc='P'".
				"    AND cxp_solicitudes.tipproben='".$as_tipproben."'".
				"    AND cxp_solicitudes.ced_bene='".$as_cedbene."'".
				"    AND cxp_solicitudes.cod_pro='".$as_codpro."'".
				$ls_cadena.
				"    AND cxp_historico_solicitud.codemp=cxp_sol_banco.codemp".
				"    AND cxp_historico_solicitud.numsol=cxp_sol_banco.numsol";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_pagosprevios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ad_pagosprevios=$row["pagos"];
			}
			$this->io_sql->free_result($rs_data);
		}		
			$ls_sql=" SELECT SUM(cxp_rd_deducciones.monret) as retenciones".
					"   FROM  cxp_solicitudes, cxp_historico_solicitud, cxp_sol_banco,cxp_rd_deducciones, sigesp_deducciones,cxp_dt_solicitudes".
					"  WHERE cxp_dt_solicitudes.codemp='".$this->ls_codemp."'".
					"    AND cxp_historico_solicitud.estprodoc='P'".
					"    AND cxp_solicitudes.tipproben='".$as_tipproben."'".
					"    AND cxp_solicitudes.ced_bene='".$as_cedbene."'".
					"    AND cxp_solicitudes.cod_pro='".$as_codpro."'".
					$ls_cadena.
					"    AND cxp_historico_solicitud.codemp=cxp_solicitudes.codemp".
					"    AND cxp_solicitudes.numsol=cxp_historico_solicitud.numsol".
					"    AND cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp".
					"    AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol".
					"    AND cxp_rd_deducciones.codemp =cxp_dt_solicitudes.codemp". 
					"    AND cxp_rd_deducciones.numrecdoc =cxp_dt_solicitudes.numrecdoc". 
					"    AND cxp_rd_deducciones.codtipdoc =cxp_dt_solicitudes.codtipdoc". 
					"    AND cxp_rd_deducciones.cod_pro =cxp_dt_solicitudes.cod_pro". 
					"    AND cxp_rd_deducciones.ced_bene =cxp_dt_solicitudes.ced_bene". 
					"    AND cxp_rd_deducciones.codemp=sigesp_deducciones.codemp".
					"	 AND cxp_rd_deducciones.codded=sigesp_deducciones.codded".
					"    AND cxp_historico_solicitud.codemp=cxp_sol_banco.codemp".
					"    AND cxp_historico_solicitud.numsol=cxp_sol_banco.numsol".
					"    AND (sigesp_deducciones.iva=1 OR sigesp_deducciones.islr=1)";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_pagosprevios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ad_retencionesprevios=$row["retenciones"];
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_pagosprevios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_informacionpagosprevios($as_tipproben,$as_codpro,$as_cedbene,$ad_fecregdes,$ad_fecreghas,&$ad_totpagosprevios)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_informacionpagosprevios
		//         Access: public  
		//	    Arguments: as_tipproben  // Tipo de Proveedor/Beneficiario
		//                 as_codpro     // Codigo de Proveedor
		//                 as_cedbene    // Codigo de Beneficiario
		//                 ad_fecregdes  // Fecha de Registro Desde
		//                 ad_fecreghas  // Fecha de Registro Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las recepciones de documentos en los intervalos indicados
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
		$ls_sql=" SELECT COALESCE(SUM(cxp_solicitudes.monsol),0) AS pagos                                                                                    ".
				"   FROM cxp_solicitudes, cxp_historico_solicitud, cxp_sol_banco".
				"  WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"    AND cxp_historico_solicitud.codemp=cxp_solicitudes.codemp".
				"    AND cxp_solicitudes.numsol=cxp_historico_solicitud.numsol".
				"    AND cxp_historico_solicitud.estprodoc='P'".
				"    AND cxp_solicitudes.tipproben='".$as_tipproben."'".
				"    AND cxp_solicitudes.ced_bene='".$as_cedbene."'".
				"    AND cxp_solicitudes.cod_pro='".$as_codpro."'".
				"    AND cxp_historico_solicitud.codemp=cxp_sol_banco.codemp".
				"    AND cxp_historico_solicitud.numsol=cxp_sol_banco.numsol".
				"    AND cxp_historico_solicitud.fecha <'".$ad_fecreghas."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_informacionpagosprevios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ad_totpagosprevios=$row["pagos"];
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_informacionpagosprevios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_detalles_pagosprevios($as_numsol,$ad_fecregdes,$ad_fecreghas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_detalles_pagosprevios
		//         Access: public  
		//	    Arguments: as_tipproben  // Tipo de Proveedor/Beneficiario
		//                 as_codpro     // Codigo de Proveedor
		//                 as_cedbene    // Codigo de Beneficiario
		//                 ad_fecregdes  // Fecha de Registro Desde
		//                 ad_fecreghas  // Fecha de Registro Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las recepciones de documentos en los intervalos indicados
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND cxp_historico_solicitud.fecha>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND cxp_historico_solicitud.fecha<='".$ad_fecreghas."'";
		}
		$this->ds_detpagosprevios = new class_datastore();
		$ls_sql=" SELECT cxp_historico_solicitud.fecha, cxp_sol_banco.codban, cxp_sol_banco.ctaban,".
				"        cxp_sol_banco.numdoc, cxp_sol_banco.monto,scb_banco.nomban".
				"   FROM cxp_solicitudes, cxp_historico_solicitud, cxp_sol_banco,scb_banco ".
				"  WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"    AND cxp_historico_solicitud.codemp=cxp_solicitudes.codemp".
				"    AND cxp_sol_banco.codemp=scb_banco.codemp".
				"    AND cxp_sol_banco.codban=scb_banco.codban".
				"	 AND cxp_solicitudes.numsol=cxp_historico_solicitud.numsol".
				"	 AND cxp_historico_solicitud.estprodoc='P'".
				"    AND cxp_historico_solicitud.codemp=cxp_sol_banco.codemp".
				"    AND cxp_historico_solicitud.numsol=cxp_sol_banco.numsol".
				"	 AND cxp_solicitudes.numsol='".$as_numsol."'".
				" ".$ls_criterio." ";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_detalles_pagosprevios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detpagosprevios->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_detalles_pagosprevios
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudesactuales($as_tipproben,$as_cedbene,$as_codpro,$ad_fecregdes,$ad_fecreghas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitudesactuales
		//         Access: public  
		//	    Arguments: as_tipproben  // Tipo de Proveedor/Beneficiario
		//                 as_codpro     // Codigo de Proveedor
		//                 as_cedbene    // Codigo de Beneficiario
		//                 ad_fecregdes  // Fecha de Registro Desde
		//                 ad_fecreghas  // Fecha de Registro Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las recepciones de documentos en los intervalos indicados
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$ls_criterio="";
		$this->ds_solactuales = new class_datastore();
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND cxp_historico_solicitud.fecha>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND cxp_historico_solicitud.fecha<='".$ad_fecreghas."'";
		}
		$ls_sql=" SELECT cxp_solicitudes.numsol, cxp_solicitudes.monsol,cxp_solicitudes.consol, cxp_historico_solicitud.estprodoc, cxp_historico_solicitud.fecha ".
				"   FROM cxp_solicitudes, cxp_historico_solicitud".
				"  WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"    AND cxp_historico_solicitud.codemp=cxp_solicitudes.codemp".
				"    AND cxp_solicitudes.numsol=cxp_historico_solicitud.numsol".
				"	 AND cxp_solicitudes.tipproben='".$as_tipproben."'".
				"    AND cxp_solicitudes.ced_bene='".$as_cedbene."'".
				"    AND cxp_solicitudes.cod_pro='".$as_codpro."'".
				"    AND (cxp_historico_solicitud.estprodoc='C' OR cxp_historico_solicitud.estprodoc='A') ".
				" ".$ls_criterio." ".
				" ORDER BY cxp_solicitudes.ced_bene, cxp_solicitudes.cod_pro ";	
			//	print $ls_sql."<br><br>";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_solicitudesactuales ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_solactuales->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_solicitudesactuales
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_pagosolicitudes($as_numsol,$ad_fecregdes,$ad_fecreghas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_pagosolicitudes
		//         Access: public  
		//	    Arguments: as_numsol    // Numero de Solicitud de Pago
		//                 ad_fecregdes  // Fecha de Registro Desde
		//                 ad_fecreghas  // Fecha de Registro Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información de las recepciones de documentos en los intervalos indicados
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$ls_criterio="";
		$this->ds_detpagsolact = new class_datastore();
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND cxp_historico_solicitud.fecha>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND cxp_historico_solicitud.fecha<='".$ad_fecreghas."'";
		}
		$ls_sql=" SELECT cxp_historico_solicitud.fecha, cxp_sol_banco.codban, cxp_sol_banco.ctaban, cxp_sol_banco.numdoc,".
				"	 	 cxp_sol_banco.monto,scb_banco.nomban,cxp_sol_banco.estmov,cxp_sol_banco.codope ".
				"   FROM cxp_solicitudes, cxp_historico_solicitud, cxp_sol_banco,scb_banco ".
				"  WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"    AND cxp_historico_solicitud.codemp=cxp_solicitudes.codemp".
				"    AND cxp_sol_banco.codemp=scb_banco.codemp".
				"    AND cxp_sol_banco.codban=scb_banco.codban".
				"	 AND cxp_solicitudes.numsol=cxp_historico_solicitud.numsol".
				"	 AND cxp_historico_solicitud.estprodoc='P'".
				"    AND cxp_historico_solicitud.codemp=cxp_sol_banco.codemp".
				"    AND cxp_historico_solicitud.numsol=cxp_sol_banco.numsol".
				"	 AND cxp_solicitudes.numsol='".$as_numsol."'".
				" ".$ls_criterio." ";
		//		print $ls_sql."<br><br>";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_pagosolicitudes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detpagsolact->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_pagosolicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_otros_creditos($as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_otros_creditos
		//         Access: public (sigesp_sep_p_solicitud)  
		//	    Arguments: as_orden     // Parametro para ordenar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Funcion que busca el listado de otros creditos
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 10/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;				
		$ls_sql="SELECT codcar AS codigo, dencar AS denominacion, codestpro, spg_cuenta, porcar, formula".
				"  FROM sigesp_cargos".
				" WHERE codemp='".$this->ls_codemp."'".
				" ORDER BY ".$as_orden."";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_otros_creditos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{   
				$lb_valido=false;			     
			}	
		}
		return $lb_valido;		
	}// end function uf_select_otros_creditos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_deducciones($as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_deducciones
		//         Access: public (sigesp_sep_p_solicitud)  
		//	    Arguments: as_orden     // Parametro para ordenar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Funcion que busca el listado de otros creditos
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 10/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;				
		$ls_sql="SELECT codded AS codigo, dended AS denominacion, sc_cuenta, porded, monded, formula".
				"  FROM sigesp_deducciones".
				" WHERE codemp='".$this->ls_codemp."'".
				" ORDER BY ".$as_orden."";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_deducciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{   
				$lb_valido=false;			     
			}	
		}
		return $lb_valido;		
	}// end function uf_select_deducciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_documentos($as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_documentos
		//         Access: public (sigesp_sep_p_solicitud)  
		//	    Arguments: as_orden     // Parametro para ordenar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Funcion que busca el listado de otros creditos
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 10/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;				
		$ls_sql="SELECT codtipdoc AS codigo, dentipdoc AS denominacion, estcon, estpre".
				"  FROM cxp_documento".
				" ORDER BY ".$as_orden."";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_documentos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{   
				$lb_valido=false;			     
			}	
		}
		return $lb_valido;		
	}// end function uf_select_documentos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudesf1($as_tipproben,$as_codprobendes,$as_codprobenhas,$ad_fecemides,$ad_fecemihas,$ai_emitida,
									 $ai_contabilizada,$ai_anulada,$ai_propago,$ai_pagada)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_recepcion
		//         Access: public  
		//	    Arguments: as_tipproben     // Tipo de Proveedor/Beneficiario
		//                 as_codprobendes  // Codigo de Proveedor/Beneficiario Desde
		//                 as_codprobenhas  // Codigo de Proveedor/Beneficiario Hasta
		//                 ad_fecemides     // Fecha de Emision Desde
		//                 ad_fecemihas     // Fecha de Emision Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las recepciones de documentos en los intervalos indicados
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_tipproben))
		{
			$ls_criterio= $ls_criterio."   AND cxp_solicitudes.tipproben='".$as_tipproben."'";
		}
		if(!empty($as_codprobendes))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.cod_pro>='".$as_codprobendes."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.ced_bene>='".$as_codprobendes."'";
			}
		}
		if(!empty($as_codprobenhas))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.cod_pro<='".$as_codprobenhas."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.ced_bene<='".$as_codprobenhas."'";
			}
		}
		if(!empty($ad_fecemides))
		{
			$ad_fecemides=$this->io_funciones->uf_convertirdatetobd($ad_fecemides);
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.fecemisol>='".$ad_fecemides."'";
		}
		if(!empty($ad_fecemihas))
		{
			$ad_fecemihas=$this->io_funciones->uf_convertirdatetobd($ad_fecemihas);
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.fecemisol<='".$ad_fecemihas."'";
		}
		if(($ai_emitida==1)||($ai_contabilizada==1)||($ai_anulada==1)||($ai_propago==1)||($ai_pagada==1))
		{
			$lb_anterior=false;
			if($ai_emitida==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_solicitudes.estprosol='E'";
					$lb_anterior=true;
				}
			}
			if($ai_contabilizada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_solicitudes.estprosol='C'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR cxp_solicitudes.estprosol='C'";
				}
			}
			if($ai_anulada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_solicitudes.estprosol='A'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR cxp_solicitudes.estprosol='A'";
				}
			}
			if($ai_propago==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_solicitudes.estprosol='S'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR cxp_solicitudes.estprosol='S'";
				}
			}
			if($ai_pagada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_solicitudes.estprosol='P'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR cxp_solicitudes.estprosol='P'";
				}
			}
			if($lb_anterior)
			{
				$ls_criterio=$ls_criterio.")";
			}
		}

		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRES":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
			case "INFORMIX":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT DISTINCT cxp_solicitudes.tipproben,cxp_solicitudes.numsol,cxp_solicitudes.cod_pro,cxp_solicitudes.ced_bene, ".
				"        cxp_solicitudes.fecemisol,cxp_solicitudes.estprosol,cxp_solicitudes.monsol,cxp_solicitudes.consol,".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre ".
				"  FROM cxp_solicitudes ".	
				" WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"   ".$ls_criterio." ".
				" ORDER BY cxp_solicitudes.numsol";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_solicitudes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_select_solicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudf2($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitudf2
		//         Access: public  
		//	    Arguments: as_numsol     // Numero de solicitud de orden de pago
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información de una solicitud de pago en especifico
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 17/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRES":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
			case "INFORMIX":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT DISTINCT cxp_solicitudes.tipproben,cxp_solicitudes.numsol,cxp_solicitudes.cod_pro,cxp_solicitudes.ced_bene, ".
				"        cxp_solicitudes.fecemisol,cxp_solicitudes.estprosol,cxp_solicitudes.monsol,cxp_solicitudes.consol,".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre ".
				"  FROM cxp_solicitudes ".	
				" WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"   AND cxp_solicitudes.numsol='".$as_numsol."'".
				" ORDER BY cxp_solicitudes.numsol";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_solicitudf2 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_solicitudf2
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesislr_cxp($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesislr_cxp
		//         Access: public  
		//	    Arguments: as_numsol     // Numero de solicitud de orden de pago
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información de las retenciones de una solicitud de pago
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 04/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
	   /*$ls_sql="SELECT cxp_rd.numrecdoc AS numdoc, cxp_rd.numref, cxp_rd.fecemidoc, cxp_rd.tipproben, rpc_proveedor.nitpro AS nit, ".
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
			   "           AND cxp_solicitudes.numsol=cxp_sol_banco.numsol) AS cheque, ".
			   "       cxp_dt_cmp_islr.numcmpislr".
			   "  FROM cxp_solicitudes, cxp_dt_solicitudes, cxp_rd, cxp_rd_deducciones, sigesp_deducciones, ".
			   "       rpc_beneficiario, rpc_proveedor ".
			   "  left join  cxp_dt_cmp_islr on (cxp_dt_cmp_islr.codemp=cxp_solicitudes.codemp ".
			   "                             and cxp_dt_cmp_islr.numsol=cxp_solicitudes.numsol)".
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
			   " ORDER BY cxp_solicitudes.numsol"; */
			   
	    $ls_sql="SELECT cxp_rd.numrecdoc AS numdoc, cxp_rd.numref, cxp_rd.fecemidoc, cxp_rd.tipproben, ".
		        "       rpc_proveedor.nitpro AS nit, ".
	   		    "	   rpc_proveedor.nompro AS proveedor, rpc_proveedor.telpro, rpc_proveedor.dirpro, rpc_proveedor.rifpro, ".
			   $ls_cadena." AS beneficiario, rpc_beneficiario.dirbene, rpc_beneficiario.rifben, rpc_beneficiario.telbene, ".
			   "	   rpc_proveedor.cod_pro, rpc_beneficiario.ced_bene,cxp_solicitudes.numsol,".
			   "	   cxp_solicitudes.consol, cxp_rd.montotdoc, cxp_rd_deducciones.monret AS retenido, ".
			   "	   (CASE WHEN cxp_rd_deducciones.monobjret is null THEN cxp_solicitudes.monsol ELSE cxp_rd_deducciones.monobjret END) AS monobjret, ".
			   "	   sigesp_deducciones.porded AS porcentaje,sigesp_deducciones.dended AS dended,".
               "      sigesp_deducciones.monded, cxp_rd.mondeddoc,cxp_rd.moncardoc,		                        ".
               "	   (SELECT MAX(cxp_sol_banco.numdoc)".
			   "		  FROM cxp_sol_banco".
			   "		 WHERE cxp_sol_banco.estmov<>'A' ".
			   "           AND cxp_sol_banco.estmov<>'O' ".
			   "           AND cxp_solicitudes.codemp=cxp_sol_banco.codemp".
			   "           AND cxp_solicitudes.numsol=cxp_sol_banco.numsol) AS cheque, ".
			   "       cxp_dt_cmp_islr.numcmpislr, ".
	           "    (SELECT scb_movbco.fecmov from scb_movbco,cxp_sol_banco where cxp_sol_banco.numsol=cxp_solicitudes.numsol ".
               " and cxp_sol_banco.estmov<>'A' AND cxp_sol_banco.estmov<>'O' and cxp_sol_banco.numdoc=scb_movbco.numdoc ".
			   " and cxp_sol_banco.codban=scb_movbco.codban and cxp_sol_banco.ctaban=scb_movbco.ctaban and cxp_sol_banco.codope=scb_movbco.codope ".
               " and cxp_sol_banco.estmov=scb_movbco.estmov) as fecche ".
			   "	FROM cxp_solicitudes
			  	    join cxp_dt_solicitudes on  ( cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp 
									              AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol 
									              AND cxp_solicitudes.cod_pro=cxp_dt_solicitudes.cod_pro 
									              AND cxp_solicitudes.ced_bene=cxp_dt_solicitudes.ced_bene)
					join cxp_rd on ( cxp_dt_solicitudes.codemp=cxp_rd.codemp 
											        AND cxp_dt_solicitudes.cod_pro=cxp_rd.cod_pro 
													AND cxp_dt_solicitudes.ced_bene=cxp_rd.ced_bene 
													AND cxp_dt_solicitudes.codtipdoc=cxp_rd.codtipdoc 
													AND cxp_dt_solicitudes.numrecdoc=cxp_rd.numrecdoc )
					join cxp_rd_deducciones on (cxp_rd.codemp=cxp_rd_deducciones.codemp 
												AND cxp_rd.cod_pro=cxp_rd_deducciones.cod_pro 
												AND cxp_rd.ced_bene=cxp_rd_deducciones.ced_bene 
												AND cxp_rd.codtipdoc=cxp_rd_deducciones.codtipdoc 
												AND cxp_rd.numrecdoc=cxp_rd_deducciones.numrecdoc)
					join sigesp_deducciones on (sigesp_deducciones.codemp=cxp_rd_deducciones.codemp 
												AND sigesp_deducciones.codded=cxp_rd_deducciones.codded)
					join rpc_beneficiario on (rpc_beneficiario.codemp=cxp_solicitudes.codemp 
											  AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene)
					join rpc_proveedor on (rpc_proveedor.codemp=cxp_solicitudes.codemp 
					                        AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro)
					left join cxp_dt_cmp_islr on (cxp_dt_cmp_islr.codemp=cxp_solicitudes.codemp 
					                              and cxp_dt_cmp_islr.numsol=cxp_solicitudes.numsol) ".
			   " WHERE sigesp_deducciones.islr=1 ".
			   "   AND sigesp_deducciones.iva=0 ".
			   "   AND sigesp_deducciones.estretmun=0 ".
			   "   AND cxp_solicitudes.estprosol<>'A' ".
			   "   AND cxp_solicitudes.codemp='".$this->ls_codemp."' ".
			   "   AND cxp_solicitudes.numsol='".$as_numsol."' ".			  
			   " ORDER BY cxp_solicitudes.numsol"; 
			   
			   
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_retencionesislr_cxp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesislr_int($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesislr_int
		//         Access: public  
		//	    Arguments: as_numsol     // Numero de solicitud de orden de pago
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información de las retenciones de una solicitud de pago
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 04/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
	   $ls_sql="SELECT cxp_dt_cmp_islr.numrecdoc AS numdoc, cxp_dt_cmp_islr.numref, cxp_dt_cmp_islr.fecpag AS fecemidoc, '' AS tipproben, rpc_proveedor.nitpro AS nit, ".
	   		   "	   rpc_proveedor.nompro AS proveedor, rpc_proveedor.telpro, rpc_proveedor.dirpro, rpc_proveedor.rifpro, ".
			   $ls_cadena." AS beneficiario, rpc_beneficiario.dirbene, rpc_beneficiario.rifben, rpc_beneficiario.telbene, ".
			   "	   rpc_proveedor.cod_pro, rpc_beneficiario.ced_bene,cxp_cmp_islr.numsol,".
			   "	   cxp_cmp_islr.consol, cxp_dt_cmp_islr.totimpret AS retenido,cxp_dt_cmp_islr.monobjret, ".
			   "	   cxp_dt_cmp_islr.porded AS porcentaje,'' AS dended,".
               "       '' AS monded, '' AS montotdoc, '' AS mondeddoc, cxp_cmp_islr.numcmpislr".
			   "  FROM cxp_cmp_islr, cxp_dt_cmp_islr, rpc_beneficiario, rpc_proveedor ".
			   " WHERE cxp_cmp_islr.codemp='".$this->ls_codemp."' ".
			   "   AND cxp_cmp_islr.numsol='".$as_numsol."' ".
			   "   AND cxp_cmp_islr.codemp=cxp_dt_cmp_islr.codemp ".
			   "   AND cxp_cmp_islr.numsol=cxp_dt_cmp_islr.numsol ".
			   "   AND cxp_cmp_islr.numcmpislr=cxp_dt_cmp_islr.numcmpislr ".
			   "   AND rpc_beneficiario.codemp=cxp_cmp_islr.codemp ".
			   "   AND rpc_beneficiario.ced_bene=cxp_cmp_islr.ced_bene ".
			   "   AND rpc_proveedor.codemp=cxp_cmp_islr.codemp ".
			   "   AND rpc_proveedor.cod_pro=cxp_cmp_islr.cod_pro ";//print "ENTRE=>  ".$ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_retencionesislr_cxp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesislr_scb($as_numdoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesislr_scb
		//         Access: public  
		//	    Arguments: as_numsol     // Numero de solicitud de orden de pago
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información de las retenciones de banco
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 07/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
	   $ls_sql="SELECT scb_movbco.numdoc, scb_movbco.chevau AS numref, scb_movbco.fecmov AS fecemidoc, scb_movbco.tipo_destino AS tipproben, ".
	   		   "	   rpc_proveedor.nitpro AS nit, rpc_proveedor.nompro AS proveedor, rpc_proveedor.telpro, rpc_proveedor.dirpro, ".
			   "	   rpc_proveedor.rifpro, ".$ls_cadena." AS beneficiario, rpc_beneficiario.dirbene, rpc_beneficiario.rifben, ".
			   "	   rpc_beneficiario.telbene, rpc_proveedor.cod_pro, rpc_beneficiario.ced_bene, scb_movbco.conmov AS consol,".
               "       scb_movbco.monto AS montotdoc, scb_movbco.monret AS retenido, scb_movbco.monobjret AS monobjret,'' AS numsol,        ".
			   "      sigesp_deducciones.porded AS porcentaje,sigesp_deducciones.dended AS dended,scb_movbco.numdoc AS cheque,  ".
			   "      '' as numcmpislr, scb_movbco.fecmov as fecche".
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
			  // print $ls_sql."<br>";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_retencionesislr_scb ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesgeneral($ad_fecdes,$ad_fechas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesgeneral
		//         Access: public  
		//	    Arguments: ad_fecdes     // Rango de fecha desde
		//	    		   ad_fechas     // Rango de fecha hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que se encarga de extraer todas aquellas solicitudes de pago
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 04/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_gestor = $_SESSION["ls_gestor"];
		$ad_fecdes=$this->io_funciones->uf_convertirdatetobd($ad_fecdes);
		$ad_fechas=$this->io_funciones->uf_convertirdatetobd($ad_fechas);
		$lb_valido=true;
		switch ($ls_gestor){
		case 'MYSQLT':
		   $ls_cadena=" CONCAT(RTRIM(rpc_beneficiario.apebene),', ',rpc_beneficiario.nombene) ";
		   break;
		case 'ORACLE':
		   $ls_cadena=" RTRIM(rpc_beneficiario.apebene)||', '||rpc_beneficiario.nombene ";
		   break;
		case 'POSTGRES':
		   $ls_cadena=" RTRIM(MAX(rpc_beneficiario.apebene))||', '||MAX(rpc_beneficiario.nombene) ";
		   break;	    
		case 'INFORMIX':
		   $ls_cadena=" MAX(rpc_beneficiario.apebene)||', '||MAX(rpc_beneficiario.nombene)";
		   break;	    
		case 'ANYWHERE':
		   $ls_cadena=" rtrim(rpc_beneficiario.apebene)+', '+rpc_beneficiario.nombene ";
		   break;
		}
		$ls_sql="SELECT cxp_solicitudes.numsol, MAX(rpc_proveedor.nitpro) AS nitpro, MAX(cxp_solicitudes.tipproben) AS tipproben, MAX(cxp_solicitudes.fecemisol) AS fecemisol, ".
			   "       MAX(rtrim(cxp_solicitudes.consol)) AS concepto, MAX(cxp_solicitudes.monsol) AS monsol, MAX(cxp_solicitudes.estprosol) AS estprosol, ".
			   "	   MAX(CAST(SUBSTR((CASE WHEN cxp_solicitudes.obssol is null THEN ' ' ELSE cxp_solicitudes.obssol END),1,250) AS CHAR(250))) AS observaciones, ".
			   "	   MAX(CASE WHEN cxp_rd_deducciones.monobjret is null THEN cxp_solicitudes.monsol ELSE cxp_rd_deducciones.monobjret END) AS mon_obj_ret, SUM(cxp_rd_deducciones.monret) AS monret, ".
			   "       (CASE MAX(cxp_solicitudes.tipproben) WHEN 'P' THEN MAX(rpc_proveedor.nompro) ".
			   "								       WHEN 'B' THEN ".$ls_cadena.
			   "				 	                   ELSE 'Nombre no N/D' END) AS nombre, ".
			   "  	   (CASE MAX(cxp_solicitudes.tipproben) WHEN 'P' THEN MAX(rpc_proveedor.rifpro) ".
			   "								 	   WHEN 'B' THEN MAX(rpc_beneficiario.ced_bene) ".
			   "     						  	       ELSE 'RIF. ó CI. N/D'END) AS cedula_rif ".
			   "   FROM cxp_solicitudes, cxp_dt_solicitudes, cxp_rd, cxp_rd_deducciones, sigesp_deducciones, rpc_beneficiario, rpc_proveedor ".
			   "  WHERE cxp_solicitudes.codemp = '".$this->ls_codemp."' ".
			   "    AND (cxp_solicitudes.estprosol = 'E' OR cxp_solicitudes.estprosol='C' OR cxp_solicitudes.estprosol='S' OR cxp_solicitudes.estprosol='P') ".
			   "    AND cxp_solicitudes.fecemisol >= '".$ad_fecdes."' ".
			   "    AND cxp_solicitudes.fecemisol <= '".$ad_fechas."' ".
			   "    AND cxp_solicitudes.codemp = cxp_dt_solicitudes.codemp ". 
			   "    AND cxp_solicitudes.numsol = cxp_dt_solicitudes.numsol ".
			   "    AND cxp_solicitudes.cod_pro = cxp_dt_solicitudes.cod_pro ".
			   "    AND cxp_solicitudes.ced_bene = cxp_dt_solicitudes.ced_bene ".
			   "    AND cxp_dt_solicitudes.codemp = cxp_rd.codemp ".
			   "    AND cxp_dt_solicitudes.numrecdoc = cxp_rd.numrecdoc ".
			   "    AND cxp_dt_solicitudes.codtipdoc = cxp_rd.codtipdoc ".
			   "    AND cxp_dt_solicitudes.cod_pro = cxp_rd.cod_pro ".
			   "    AND cxp_dt_solicitudes.ced_bene = cxp_rd.ced_bene ".
			   "    AND cxp_rd.codemp = cxp_rd_deducciones.codemp ".
			   "    AND cxp_rd.numrecdoc = cxp_rd_deducciones.numrecdoc ".
			   "    AND cxp_rd.codtipdoc = cxp_rd_deducciones.codtipdoc ".
			   "    AND	cxp_rd.cod_pro = cxp_rd_deducciones.cod_pro ".
			   "    AND cxp_rd.ced_bene = cxp_rd_deducciones.ced_bene ".
			   "    AND cxp_rd_deducciones.codemp = sigesp_deducciones.codemp ".
			   "    AND cxp_rd_deducciones.codded = sigesp_deducciones.codded ".
			   "    AND rpc_beneficiario.codemp = cxp_solicitudes.codemp ".
			   "    AND rpc_beneficiario.ced_bene = cxp_solicitudes.ced_bene ".
			   "    AND rpc_proveedor.codemp = cxp_solicitudes.codemp ".
			   "    AND rpc_proveedor.cod_pro = cxp_solicitudes.cod_pro ".
			   "  GROUP BY cxp_solicitudes.numsol ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_retencionesgeneral ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_retencionesgeneral
	//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_select_retenciones($as_codded,$as_coddedhas,$as_tipded="T")
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_retenciones
		//         Access: public  
		//	    Arguments: as_codded     // Código de Deduccion
		//	    		   as_coddedhas     // Código de Deduccion final del intervalo
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que se encarga de traer las deducciones en el intervalo indicado
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/06/2008									Fecha Última Modificación :  20/06/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->DS=new class_datastore();
		switch($as_tipded)
		{
			case "T":
				$ls_criterio="";
			break;
			case "S":
				$ls_criterio=" AND islr=1";
			break;
			case "I":
				$ls_criterio="AND iva=1";
			break;
			case "M":
				$ls_criterio="AND estretmun=1";
			break;
			case "A":
				$ls_criterio="AND retaposol=1";
			break;
			case "O":
				$ls_criterio="AND otras=1";
			break;
		}
		$ls_sql="SELECT codded, dended,islr,iva,estretmun,retaposol".
				"  FROM sigesp_deducciones".
				" WHERE codded>='".$as_codded."'".
				"   AND codded<='".$as_coddedhas."'".
				$ls_criterio;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_retenciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesespecifico($as_codded,$as_coddedhas,$as_tipproben,$as_codprobenhas,$as_codprobendes,$ad_fecdes,
									  $ad_fechas,$as_tipper="")
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesespecifico
		//         Access: public  
		//	    Arguments: as_codded     // Código de Deduccion
		//	    		   as_coddedhas     // Código de Deduccion final del intervalo
		//	    		   as_tipproben     // Tipo de Proveedor ó beneficiario
		//	    		   as_codprobenhas     // código de Poveedor / Beneficiario Desde
		//	    		   as_codprobendes     // código de Poveedor / Beneficiario Hasta
		//	    		   ad_fecdes     // Rango de fecha desde
		//	    		   ad_fechas     // Rango de fecha hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que se encarga de extraer todas aquellas deducciones de las solicitudes de pago
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 10/07/2007									Fecha Última Modificación :  20/06/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_gestor = $_SESSION["ls_gestor"];
		$lb_valido=true;
		$ls_criterio="";
		$this->ds_detalle=new class_datastore();
		$ad_fecdes=$this->io_funciones->uf_convertirdatetobd($ad_fecdes);
		$ad_fechas=$this->io_funciones->uf_convertirdatetobd($ad_fechas);
		if($as_codded!="")
		{
			$ls_criterio=$ls_criterio."	AND cxp_rd_deducciones.codded = '".$as_codded."'";
		}
		switch($as_tipproben)
		{
			case "P":
				if($as_codprobendes!="")
				{
					$ls_criterio=$ls_criterio."	AND cxp_solicitudes.cod_pro >= '".$as_codprobendes."'";
				}
				if($as_codprobenhas!="")
				{
					$ls_criterio=$ls_criterio."	AND cxp_solicitudes.cod_pro <= '".$as_codprobenhas."'";
				}
				if($as_tipper!="")
				{
					$ls_criterio=$ls_criterio." AND rpc_proveedor.tipperpro='".$as_tipper."'"; 
				}
				break;	
			case "B":
				if($as_codprobendes!="")
				{
					$ls_criterio=$ls_criterio."	AND cxp_solicitudes.ced_bene >= '".$as_codprobendes."'";
				}
				if($as_codprobenhas!="")
				{
					$ls_criterio=$ls_criterio."	AND cxp_solicitudes.ced_bene <= '".$as_codprobenhas."'";
				}
				break;	
		}
		switch ($ls_gestor)
		{
			case 'MYSQLT':
			   $ls_cadena=" CONCAT(RTRIM(rpc_beneficiario.apebene),', ',rpc_beneficiario.nombene) ";
			   break;
			case 'ORACLE':
			   $ls_cadena=" RTRIM(rpc_beneficiario.apebene)||', '||rpc_beneficiario.nombene ";
			   break;
			case 'POSTGRES':
			   $ls_cadena=" RTRIM(MAX(rpc_beneficiario.apebene))||', '||MAX(rpc_beneficiario.nombene) ";
			   break;	    
			case 'ANYWHERE':
			   $ls_cadena=" rtrim(rpc_beneficiario.apebene)+', '+rpc_beneficiario.nombene ";
			   break;
		}

		$ls_sql="SELECT cxp_solicitudes.numsol, MAX(rpc_proveedor.nitpro) AS nitpro, MAX(cxp_solicitudes.tipproben) AS tipproben, MAX(cxp_solicitudes.fecemisol) AS fecemisol, ".
				"       MAX(rtrim(cxp_solicitudes.consol)) AS concepto, MAX(cxp_solicitudes.monsol) AS monsol, MAX(cxp_solicitudes.estprosol) AS estprosol, ".
				"	   MAX(CAST(SUBSTRING(COALESCE(cxp_solicitudes.obssol,' '),1,250) AS CHAR(250))) AS observaciones, ".
				"	   SUM(cxp_rd_deducciones.monobjret) AS mon_obj_ret, SUM(cxp_rd_deducciones.monret) AS monret, MAX(cxp_rd.numrecdoc) AS numrecdoc, MAX(cxp_rd.numref) AS numref,".
				"       MAX(cxp_rd_deducciones.codded) as codded,MAX(cxp_rd_deducciones.porded) as porded,".
				"       (CASE MAX(cxp_solicitudes.tipproben) WHEN 'P' THEN MAX(rpc_proveedor.nompro) ".
				"								       WHEN 'B' THEN ".$ls_cadena.
				"				 	                   ELSE 'Nombre N/D' END) AS nombre, ".
				"  	   (CASE MAX(cxp_solicitudes.tipproben) WHEN 'P' THEN MAX(rpc_proveedor.rifpro) ".
				"								 	   WHEN 'B' THEN MAX(rpc_beneficiario.ced_bene) ".
				"     						  	       ELSE 'RIF. ó CI. N/D'END) AS cedula_rif, ".
				"      (SELECT MAX(numcom) FROM scb_dt_cmp_ret".
				"        WHERE codret='0000000001'".
				"          AND scb_dt_cmp_ret.numsop=cxp_solicitudes.numsol".
				"        GROUP BY scb_dt_cmp_ret.numsop ) as numcomiva,".
				"      (SELECT MAX(numcom) FROM scb_dt_cmp_ret".
				"        WHERE codret='0000000003'".
				"          AND scb_dt_cmp_ret.numsop=cxp_solicitudes.numsol".
				"        GROUP BY scb_dt_cmp_ret.numsop ) as numcommun,".
				"      (SELECT MAX(numcom) FROM scb_dt_cmp_ret".
				"        WHERE codret='0000000004'".
				"          AND scb_dt_cmp_ret.numsop=cxp_solicitudes.numsol".
				"        GROUP BY scb_dt_cmp_ret.numsop ) as numcomapo".
				"  FROM cxp_solicitudes,cxp_dt_solicitudes,cxp_rd_deducciones,rpc_proveedor,rpc_beneficiario,cxp_rd ".
			   "  WHERE cxp_solicitudes.codemp = '".$this->ls_codemp."' ".
			   "    AND (cxp_solicitudes.estprosol = 'E' OR cxp_solicitudes.estprosol='C' OR cxp_solicitudes.estprosol='S' OR cxp_solicitudes.estprosol='P') ".
			   "    AND cxp_solicitudes.fecemisol >= '".$ad_fecdes."' ".
			   "    AND cxp_solicitudes.fecemisol <= '".$ad_fechas."' ".
			   $ls_criterio.
				"   AND cxp_solicitudes.codemp = cxp_dt_solicitudes.codemp".
				"   AND cxp_solicitudes.numsol = cxp_dt_solicitudes.numsol ".
				"   AND cxp_solicitudes.cod_pro = cxp_dt_solicitudes.cod_pro ".
				"   AND cxp_solicitudes.ced_bene = cxp_dt_solicitudes.ced_bene ".
				"   AND cxp_dt_solicitudes.codemp = cxp_rd_deducciones.codemp ".
				"   AND cxp_dt_solicitudes.numrecdoc = cxp_rd_deducciones.numrecdoc ".
				"   AND cxp_dt_solicitudes.codtipdoc = cxp_rd_deducciones.codtipdoc ".
				"   AND cxp_dt_solicitudes.cod_pro = cxp_rd_deducciones.cod_pro ".
				"   AND cxp_dt_solicitudes.ced_bene = cxp_rd_deducciones.ced_bene ".
				"   AND rpc_beneficiario.codemp = cxp_solicitudes.codemp ".
				"   AND rpc_beneficiario.ced_bene = cxp_solicitudes.ced_bene ".
				"   AND rpc_proveedor.codemp = cxp_solicitudes.codemp ".
				"   AND rpc_proveedor.cod_pro = cxp_solicitudes.cod_pro ".
				"   AND cxp_dt_solicitudes.codemp = cxp_rd.codemp ".
				"   AND cxp_dt_solicitudes.numrecdoc = cxp_rd.numrecdoc ".
				"   AND cxp_dt_solicitudes.codtipdoc = cxp_rd.codtipdoc ".
				"   AND cxp_dt_solicitudes.cod_pro = cxp_rd.cod_pro ".
				"   AND cxp_dt_solicitudes.ced_bene = cxp_rd.ced_bene ".
				" GROUP BY cxp_solicitudes.numsol".
				" ORDER BY cxp_solicitudes.numsol"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_retencionesespecifico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);	
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retencionesespecifico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesiva_proveedor($as_numcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesiva_proveedor
		//         Access: public  
		//	    Arguments: as_numcom     // Numero de comprobante de iva
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información de los comprobantes de iva
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 14/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	    $ls_sql="SELECT numcom, codret, fecrep, perfiscal, codsujret, nomsujret, rif, dirsujret, estcmpret, ".
			 	"        (SELECT telpro ".
				" 		    FROM rpc_proveedor ".
				"		   WHERE rpc_proveedor.codemp=scb_cmp_ret.codemp ".
				"			 AND rpc_proveedor.cod_pro=scb_cmp_ret.codsujret) AS telpro ".
				"  FROM scb_cmp_ret ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND numcom = '".$as_numcom."'".
				"   AND codret ='0000000001' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_retencionesiva_proveedor ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_retencionesiva_proveedor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesiva_detalle($as_numcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesiva_detalle
		//         Access: public  
		//	    Arguments: as_numcom     // Numero de comprobante de iva
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información de los comprobantes de iva
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 14/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT max(codret) as codret, max(numcom) as numcom, max(numope) as numope, max(fecfac) as fecfac, ".
				"		 max(numfac) as numfac, max(numcon) as numcon, max(numnd) as numnd, max(numnc) as numnc, ".
				"		 max(tiptrans) as tiptrans, SUM(totcmp_sin_iva) as totcmp_sin_iva , max(totcmp_con_iva) as totcmp_con_iva, ".
				"		 SUM(basimp) as basimp, porimp, SUM(totimp) as totimp, SUM(iva_ret) as iva_ret, max(desope) as desope, ".
				"		 max(numsop) as numsop, max(codban) as codban, max(ctaban) as ctaban, max(numdoc) as numdoc, max(codope) as codope ".
				"  FROM scb_dt_cmp_ret ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numcom='".$as_numcom."' ".
				"	AND codret='0000000001' ".
				" GROUP BY codemp, numfac, porimp, numnd,numnc".
				" ORDER BY numope "; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_retencionesiva_detalle ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retencionesiva_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesaporte_proveedor($as_numcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesaporte_proveedor
		//         Access: public  
		//	    Arguments: as_numcom     // Numero de comprobante de iva
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información de los comprobantes de aporte social
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 21/10/08									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	    $ls_sql="SELECT numcom, codret, fecrep, perfiscal, codsujret, nomsujret, rif, dirsujret, estcmpret, ".
			 	"        (SELECT telpro ".
				" 		    FROM rpc_proveedor ".
				"		   WHERE rpc_proveedor.codemp=scb_cmp_ret.codemp ".
				"			 AND rpc_proveedor.cod_pro=scb_cmp_ret.codsujret) AS telpro ".
				"  FROM scb_cmp_ret ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND numcom = '".$as_numcom."'".
				"   AND codret ='0000000004' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_retencionesaporte_proveedor ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_retencionesaporte_proveedor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesaporte_detalle($as_numcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesaporte_detalle
		//         Access: public  
		//	    Arguments: as_numcom     // Numero de comprobante de iva
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información de los comprobantes de aporte social
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 21/10/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT max(codret) as codret, max(numcom) as numcom, max(numope) as numope, max(fecfac) as fecfac, ".
				"		 max(numfac) as numfac, max(numcon) as numcon, max(numnd) as numnd, max(numnc) as numnc, ".
				"		 max(tiptrans) as tiptrans, SUM(totcmp_sin_iva) as totcmp_sin_iva , max(totcmp_con_iva) as totcmp_con_iva, ".
				"		 SUM(basimp) as basimp, porimp, SUM(totimp) as totimp, SUM(iva_ret) as iva_ret, max(desope) as desope, ".
				"		 max(numsop) as numsop, max(codban) as codban, max(ctaban) as ctaban, max(numdoc) as numdoc, max(codope) as codope ".
				"  FROM scb_dt_cmp_ret ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numcom='".$as_numcom."' ".
				"	AND codret='0000000004' ".
				" GROUP BY codemp, numfac, porimp,numsop ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_retencionesaporte_detalle ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retencionesaporte_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_declaracioninformativa($as_quincena,$as_mes,$as_anio,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_declaracioninformativa
		//         Access: public  
		//	    Arguments: as_quincena // Quincena del cual se van a generar los txt
		//	    		   as_mes      // Mes del cual se van a generar los txt
		//	    		   as_anio     // Año del cual se van a generar los txt
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que genera los txt de la declaración informativa
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 15/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fechadesde=$as_anio."-".$as_mes."-01";
		$ld_fechahasta=$as_anio."-".$as_mes."-".substr($this->io_fecha->uf_last_day($as_mes,$as_anio),0,2);
	    $ls_periodofiscal=substr($ld_fechadesde,0,4).substr($ld_fechadesde,5,2);
		$ls_criterio="";
		$ls_archivo="declaracioninformativa/Retencion_IVA_".date("Y_m_d_H_i").".txt";
		$lo_archivo=fopen("$ls_archivo","a+");	
		switch($as_quincena)
		{
			case "1":
				$ld_fechahasta=$as_anio."-".$as_mes."-15";
				$ls_criterio=$ls_criterio." AND fecrep >='".$ld_fechadesde."'".
										  " AND fecrep <='".$ld_fechahasta."'";
				break;               
			
			case "2":
				$ld_fechadesde=$as_anio."-".$as_mes."-16";
				$ls_criterio=$ls_criterio." AND fecrep >='".$ld_fechadesde."'".
										  " AND fecrep <='".$ld_fechahasta."'";
				break;               
		}
		$ls_sql="SELECT * ".
				"  FROM scb_cmp_ret ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codret ='0000000001' ".
				"   AND estcmpret=1 ".
				"   AND perfiscal ='".$ls_periodofiscal."' ".
				$ls_criterio;	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_declaracioninformativa ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_agenteretencion=$_SESSION["la_empresa"]["nombre"];
			$ls_rifagenteret=str_replace('-','',$_SESSION["la_empresa"]["rifemp"]);
			$ls_direccionagenteret=$_SESSION["la_empresa"]["direccion"];
			$li_j=0;	
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$li_j++;
				$ls_numcom=$row["numcom"];
				$ls_perfiscal=$row["perfiscal"];
				$ls_codsujret=$row["codsujret"];
				$ls_nomsujret=$row["nomsujret"];
				$ls_rif=str_replace('-','',$row["rif"]);
				$ls_dirsujret=$row["dirsujret"];
				$lb_valido=$this->uf_retencionesiva_detalle($ls_numcom);						
				if($lb_valido)
				{  
					if(strlen($ls_numcom)==15)
					{
						$ls_numcom1=substr($ls_numcom,0,6);
						$ls_numcom2=substr($ls_numcom,6,8);
						$ls_numcom =$ls_numcom1.$ls_numcom2;
					}
					$li_total=$this->ds_detalle->getRowCount("numfac");			   
					for($li_i=1;$li_i<=$li_total;$li_i++)
					{
						$ls_numope=$this->ds_detalle->data["numope"][$li_i];					
						$ls_numfac=trim($this->ds_detalle->data["numfac"][$li_i]);	
						$ls_numref=trim($this->ds_detalle->data["numcon"][$li_i]);
						$ld_fecfac=substr($this->ds_detalle->data["fecfac"][$li_i],0,10);	
						$li_siniva=number_format($this->ds_detalle->data["totcmp_sin_iva"][$li_i],2,".","");
						$li_coniva=number_format($this->ds_detalle->data["totcmp_con_iva"][$li_i],2,".","");	
						$li_baseimp=number_format($this->ds_detalle->data["basimp"][$li_i],2,".","");	
						$li_porimp=number_format($this->ds_detalle->data["porimp"][$li_i],2,".","");	
						$li_totimp=number_format($this->ds_detalle->data["totimp"][$li_i],2,".","");
						$li_ivaret=number_format($this->ds_detalle->data["iva_ret"][$li_i],2,".","");
						$ls_numdoc=$this->ds_detalle->data["numdoc"][$li_i];	
						$ls_tiptrans=$this->ds_detalle->data["tiptrans"][$li_i];	
						$ls_numnotdeb=$this->ds_detalle->data["numnd"][$li_i];	
						$ls_numnotcre=$this->ds_detalle->data["numnc"][$li_i];									
						$li_monto=$li_baseimp + $li_totimp;  
						$li_totdersiniva= number_format(abs($li_coniva - $li_monto),2,".","");
						$ls_numfacafec="0";
						$ls_tipope="C";
						$ls_tipdoc="01";
						$ls_numexp="0";
						$ls_cadena=$ls_rifagenteret."\t".$ls_perfiscal."\t".$ld_fecfac."\t".$ls_tipope."\t".$ls_tipdoc."\t".
								   $ls_rif."\t".$ls_numfac."\t".$ls_numref."\t".$li_coniva."\t".$li_baseimp."\t".$li_ivaret."\t".
								   $ls_numfacafec."\t".$ls_numcom."\t".$li_totdersiniva."\t".$li_porimp."\t".$ls_numexp."\r\n";
						if ($lo_archivo)			
						{
							@fwrite($lo_archivo,$ls_cadena);
						}
					}
				}																		 																						  
			}
			if($li_j==0)
			{
				$this->io_mensajes->message("No existen retenciones para el periodo indicado");
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Genero el txt de Declaración Informativa Para el Año ".$as_anio." Mes ".$as_mes." Archivo ".$ls_archivo." Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////					
			}
		}		
		return $lb_valido;
	}// end function uf_declaracioninformativa
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesmunicipales_proveedor($as_numcom,$as_mes,$as_anio)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesmunicipales_proveedor
		//         Access: public  
		//	    Arguments: as_numcom // Numero de comprobante municipal
		//	    		   as_mes    // mes del comprobante
		//	    		   as_anio   // año del comprobante
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información de los comprobantes municipales
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 15/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fechadesde=$as_anio."-".$as_mes."-01";
		$ld_fechahasta=$as_anio."-".$as_mes."-".substr($this->io_fecha->uf_last_day($as_mes,$as_anio),0,2);
		$ls_sql="SELECT numcom, codret, fecrep, perfiscal, codsujret, nomsujret, rif,nit, dirsujret, estcmpret, numlic ".
				"  FROM scb_cmp_ret ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codret='0000000003' ".
				"   AND fecrep>='".$ld_fechadesde."' ".
				"   AND fecrep<='".$ld_fechahasta."' ".
				"   AND numcom='".$as_numcom."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_retencionesmunicipales_proveedor ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_retencionesmunicipales_proveedor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesmunicipales_detalles($as_numcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesmunicipales_detalles
		//         Access: public  
		//	    Arguments: as_numcom     // Numero de comprobante de iva
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información de los comprobantes municipales
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 17/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT max(scb_dt_cmp_ret.codret) as codret, 
		                max(scb_dt_cmp_ret.numsop) as numsop, 
						max(scb_dt_cmp_ret.fecfac) as fecfac,
						max(scb_dt_cmp_ret.numfac) as numfac,
						max(scb_dt_cmp_ret.numcon) as numcon,
					    scb_dt_cmp_ret.basimp,
						scb_dt_cmp_ret.porimp,
						scb_dt_cmp_ret.totimp,
						scb_dt_cmp_ret.iva_ret,
						max(cxp_solicitudes.fecemisol) as fecemisol
				   FROM scb_dt_cmp_ret, cxp_solicitudes, cxp_dt_solicitudes
				  WHERE scb_dt_cmp_ret.codemp='".$this->ls_codemp."'
				    AND scb_dt_cmp_ret.numcom='".$as_numcom."'
				    AND scb_dt_cmp_ret.codret='0000000003'
					AND scb_dt_cmp_ret.codemp=cxp_dt_solicitudes.codemp
					AND scb_dt_cmp_ret.numfac=cxp_dt_solicitudes.numrecdoc
					AND scb_dt_cmp_ret.numsop=cxp_dt_solicitudes.numsol
					AND cxp_dt_solicitudes.codemp=cxp_solicitudes.codemp
					AND cxp_dt_solicitudes.numsol=cxp_solicitudes.numsol
				  GROUP BY scb_dt_cmp_ret.codemp, scb_dt_cmp_ret.numfac, scb_dt_cmp_ret.porimp,scb_dt_cmp_ret.basimp,scb_dt_cmp_ret.totimp,scb_dt_cmp_ret.iva_ret";
		$rs_data=$this->io_sql->select($ls_sql);//echo $ls_sql.'<br>';
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_retencionesmunicipales_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retencionesmunicipales_detalles
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionesmunicipales_monfact($as_numcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionesmunicipales_monfact
		//         Access: public  
		//	    Arguments: as_numcom     // Numero de comprobante de iva
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información de los comprobantes municipales
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 17/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_montotdoc=0;
		$ls_sql="SELECT max(cxp_rd.montotdoc) as montotdoc
				   FROM scb_dt_cmp_ret, cxp_rd, cxp_dt_solicitudes
				  WHERE scb_dt_cmp_ret.codemp='".$this->ls_codemp."'
				    AND scb_dt_cmp_ret.numcom='".$as_numcom."'
				    AND scb_dt_cmp_ret.codret='0000000003'
					AND scb_dt_cmp_ret.codemp=cxp_dt_solicitudes.codemp
					AND scb_dt_cmp_ret.numfac=cxp_dt_solicitudes.numrecdoc
					AND scb_dt_cmp_ret.numsop=cxp_dt_solicitudes.numsol
					AND cxp_dt_solicitudes.codemp=cxp_rd.codemp
					AND cxp_dt_solicitudes.numrecdoc=cxp_rd.numrecdoc
					AND cxp_dt_solicitudes.codtipdoc=cxp_rd.codtipdoc
					AND cxp_dt_solicitudes.cod_pro=cxp_rd.cod_pro
					AND cxp_dt_solicitudes.ced_bene=cxp_rd.ced_bene
				  GROUP BY scb_dt_cmp_ret.codemp, scb_dt_cmp_ret.numfac, scb_dt_cmp_ret.porimp,scb_dt_cmp_ret.basimp,scb_dt_cmp_ret.totimp,scb_dt_cmp_ret.iva_ret";
		$rs_data=$this->io_sql->select($ls_sql);//echo $ls_sql.'<br>';
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_retencionesmunicipales_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_montotdoc=$row["montotdoc"];
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $li_montotdoc;
	}// end function uf_retencionesmunicipales_detalles
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_arc_cabecera($as_coddes,$as_codhas,$as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_arc_cabecera
		//         Access: public 
		//      Argumento: as_coddes // codigo del proveedor ó beneficario desde
		//				   as_codhas // codigo del proveedor ó beneficario hasta
		//				   as_tipo // Si buscamos proveedores, beneficiarios ó ambos
		//	      Returns: Retorna un Datastored
		//    Description: Funcion que obtiene los datos de los proveedores ó beneficarios que tiene deducciones de ARC
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 15/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_criterio="";
		$lb_valido=true;
		switch($as_tipo)
		{
			case "P": // si es un proveedor
				$ls_codprodes=$as_coddes;
				$ls_codprohas=$as_codhas;
				$ls_cedbendes="";
				$ls_cedbenhas="";
				$ls_criterio=$ls_criterio." AND cxp_rd.tipproben='".$as_tipo."'";
				break;
				
			case "B": // si es un beneficiario
				$ls_codprodes="";
				$ls_codprohas="";
				$ls_cedbendes=$as_coddes;
				$ls_cedbenhas=$as_codhas;
				$ls_criterio=$ls_criterio." AND cxp_rd.tipproben='".$as_tipo."'";
				break;
				
			case "": // si son todos
				$ls_codprodes="";
				$ls_codprohas="";
				$ls_cedbendes="";
				$ls_cedbenhas="";
				break;
		}
		if($ls_codprodes!="")
		{
			$ls_criterio=$ls_criterio." AND cxp_rd.cod_pro>='".$ls_codprodes."'";
		}
		if($ls_codprohas!="")
		{
			$ls_criterio=$ls_criterio." AND cxp_rd.cod_pro<='".$ls_codprohas."'";
		}
		if($ls_cedbendes!="")
		{
			$ls_criterio=$ls_criterio." AND cxp_rd.ced_bene>='".$ls_cedbendes."'";
		}
		if($ls_cedbenhas!="")
		{
			$ls_criterio=$ls_criterio." AND cxp_rd.ced_bene<='".$ls_cedbenhas."'";
		}
		$ls_sql="SELECT MAX(cxp_rd.tipproben) AS tipproben, MAX(rpc_proveedor.nompro) AS nompro, ".
				"		MAX(rpc_proveedor.nacpro) AS nacpro, MAX(rpc_proveedor.rifpro) AS rifpro, ".
				"		MAX(rpc_proveedor.nitpro) AS nitpro, MAX(rpc_proveedor.dirpro) AS dirpro, ".
				"       MAX(rpc_proveedor.telpro) AS telpro, MAX(rpc_beneficiario.nombene) AS nombene, ".
				"		MAX(rpc_beneficiario.apebene) AS apebene, MAX(rpc_beneficiario.nacben) AS nacben, ".
				"		MAX(rpc_beneficiario.ced_bene) AS ced_bene, MAX(rpc_beneficiario.numpasben) AS numpasben, ".
				"       MAX(rpc_beneficiario.dirbene) AS dirbene, MAX(rpc_beneficiario.telbene) AS telbene, ".
				"       MAX(cxp_rd.cod_pro) AS cod_pro, MAX(cxp_rd.ced_bene) AS ced_bene ".
				"  FROM cxp_rd, cxp_rd_deducciones, sigesp_deducciones, rpc_proveedor, rpc_beneficiario ".
				" WHERE sigesp_deducciones.islr = 1 ".
				$ls_criterio.
				"   AND cxp_rd.codemp = cxp_rd_deducciones.codemp ".
				"   AND cxp_rd.numrecdoc = cxp_rd_deducciones.numrecdoc ".
				"   AND cxp_rd.codtipdoc = cxp_rd_deducciones.codtipdoc ".
				"   AND cxp_rd.ced_bene = cxp_rd_deducciones.ced_bene ".
				"   AND cxp_rd.cod_pro = cxp_rd_deducciones.cod_pro ".
				"   AND cxp_rd_deducciones.codemp = sigesp_deducciones.codemp ".
				"   AND cxp_rd_deducciones.codded = sigesp_deducciones.codded ".
				"   AND cxp_rd.codemp = rpc_proveedor.codemp ".
				"   AND cxp_rd.cod_pro = rpc_proveedor.cod_pro ".
				"   AND cxp_rd.codemp = rpc_beneficiario.codemp ".
				"   AND cxp_rd.ced_bene = rpc_beneficiario.ced_bene ".
				" GROUP BY cxp_rd.cod_pro, cxp_rd.ced_bene ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_arc_cabecera ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_arc_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_arc_detalle($as_codigo,$as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_arc_detalle
		//         Access: public 
		//      Argumento: $as_codigo // codigo del proveedor ó beneficario desde
		//				   $as_tipo // Si buscamos proveedores, beneficiarios ó ambos
		//	      Returns: Retorna un Datastored
		//    Description: Funcion que obtiene el detalle del arc dado el proveedor ó beneficario
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 15/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_criterio="";
		$lb_valido=true;
		switch($as_tipo)
		{
			case "P": // si es un proveedor
				$ls_criterio=$ls_criterio." AND cxp_rd.cod_pro='".$as_codigo."'";
				$ls_criterio=$ls_criterio." AND cxp_rd.tipproben='".$as_tipo."'";
				break;
				
			case "B": // si es un beneficiario
				$ls_criterio=$ls_criterio." AND cxp_rd.ced_bene='".$as_codigo."'";
				$ls_criterio=$ls_criterio." AND cxp_rd.tipproben='".$as_tipo."'";
				break;
		}
		$ls_sql="SELECT cxp_rd.fecemidoc, cxp_rd.montotdoc, cxp_rd_deducciones.monobjret, ".
				"		cxp_rd_deducciones.porded, cxp_rd_deducciones.monret, cxp_rd_deducciones.cod_pro ".
				"  FROM cxp_rd, cxp_rd_deducciones, sigesp_deducciones ".
				" WHERE sigesp_deducciones.islr = 1 ".
				$ls_criterio.
				"   AND cxp_rd.codemp = cxp_rd_deducciones.codemp ".
				"   AND cxp_rd.numrecdoc = cxp_rd_deducciones.numrecdoc ".
				"   AND cxp_rd.codtipdoc = cxp_rd_deducciones.codtipdoc ".
				"   AND cxp_rd.ced_bene = cxp_rd_deducciones.ced_bene ".
				"   AND cxp_rd.cod_pro = cxp_rd_deducciones.cod_pro ".
				"   AND cxp_rd_deducciones.codemp = sigesp_deducciones.codemp ".
				"   AND cxp_rd_deducciones.codded = sigesp_deducciones.codded ".
				" ORDER BY cxp_rd.fecemidoc";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_arc_detalle ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{   
				$lb_valido=false;			     
			}	
			$this->io_sql->free_result($rs_data);  	
		}
		return $lb_valido;		
	}// end function uf_arc_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_probenrelacionfacturas($as_tipproben,$as_codprobendes,$as_codprobenhas,$ad_fecregdes,$ad_fecreghas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_probenrelacionfacturas
		//         Access: public  
		//	    Arguments: as_tipproben     // Tipo de proveedor
		//				   as_codprobendes  // Codigo proveedor/beneficiario Desde
		//				   as_codprobenhas  // Codigo proveedor/beneficiario Hasta
		//				   ad_fecregdes     // Fecha de registro Desde
		//				   ad_fecreghas     // Fecha de registro Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca los proveedores/beneficiarios que tienen facturas asociadas
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 02/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRES":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
			case "INFORMIX":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		switch($as_tipproben)
		{
			case "P":
				$ls_criterio=" AND cxp_rd.cod_pro>='".$as_codprobendes."'".
							 " AND cxp_rd.cod_pro<='".$as_codprobenhas."'".
							 " AND cxp_rd.ced_bene='----------'";
			break;
			case "B":
				$ls_criterio=" AND cxp_rd.ced_bene>='".$as_codprobendes."'".
							 " AND cxp_rd.ced_bene<='".$as_codprobenhas."'".
							 " AND cxp_rd.cod_pro='----------'";
			break;
		}
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND cxp_rd.fecregdoc>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND cxp_rd.fecregdoc<='".$ad_fecreghas."'";
		}
		$ls_sql="SELECT  DISTINCT(CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.cod_pro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_rd.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_rd.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT rpc_beneficiario.ced_bene ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_rd.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_rd.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS codigo, ".
				" (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_rd.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_rd.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_rd.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_rd.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre,cxp_rd.tipproben ".
				"  FROM cxp_rd,cxp_dt_solicitudes ".	
				" WHERE cxp_rd.codemp='".$this->ls_codemp."' ".
				"   AND cxp_rd.codemp=cxp_dt_solicitudes.codemp".
				"   AND cxp_rd.numrecdoc=cxp_dt_solicitudes.numrecdoc".
				"   AND cxp_rd.cod_pro=cxp_dt_solicitudes.cod_pro".
				"   AND cxp_rd.ced_bene=cxp_dt_solicitudes.ced_bene".
				"   AND cxp_rd.codtipdoc=cxp_dt_solicitudes.codtipdoc".
				" ".$ls_criterio." ";
				" GROUP BY codigo,cxp_rd.codemp,cxp_rd.cod_pro,cxp_rd.ced_bene,cxp_rd.tipproben".
				" ORDER BY codigo";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_probenrelacionfacturas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_select_probenrelacionfacturas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_facturasproben($as_tipproben,$as_codigo,$ad_fecregdes,$ad_fecreghas,$ai_ordendoc,$ai_ordenfec)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_probenrelacionfacturas
		//         Access: public  
		//	    Arguments: as_tipproben  // Tipo de proveedor
		//				   as_codigo     // Codigo proveedor/beneficiario 
		//				   ad_fecregdes  // Fecha de registro Desde
		//				   ad_fecreghas  // Fecha de registro Hasta
		//				   ai_ordendoc   // Indica si se desea ordenar por documento
		//				   ai_ordenfec   // Indica si se desea ordenar por fecha de Registro
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca los las facturas asociadas a un proveedor/beneficiario
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 03/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_order="";
		$this->ds_detrecdoc = new class_datastore();
		if($as_tipproben=="P")
		{
			$ls_criterio=" AND cxp_rd.cod_pro='".$as_codigo."'".
						 " AND cxp_rd.ced_bene='----------'";
		}
		else
		{
			$ls_criterio=" AND cxp_rd.ced_bene='".$as_codigo."'".
						 " AND cxp_rd.cod_pro='----------'";
		}
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND cxp_rd.fecregdoc>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND cxp_rd.fecregdoc<='".$ad_fecreghas."'";
		}
		if($ai_ordendoc==1)
		{
			$ls_order=" ORDER BY cxp_rd.numrecdoc";
		}
		if($ai_ordenfec==1)
		{
			if($ls_order=="")
			{
				$ls_order=" ORDER BY cxp_rd.fecregdoc";
			}
			else
			{
				$ls_order=$ls_order.", cxp_rd.fecregdoc";
			}
		}
		$ls_sql="SELECT cxp_rd.numrecdoc, cxp_rd.fecregdoc, cxp_rd.fecemidoc, cxp_rd.dencondoc,".
				" 		cxp_rd.montotdoc,cxp_rd.moncardoc,cxp_rd.mondeddoc,cxp_dt_solicitudes.numsol".
				"  FROM cxp_rd,cxp_dt_solicitudes ".	
				" WHERE cxp_rd.codemp='".$this->ls_codemp."' ".
				"   AND cxp_rd.codemp=cxp_dt_solicitudes.codemp".
				"   AND cxp_rd.numrecdoc=cxp_dt_solicitudes.numrecdoc".
				"   AND cxp_rd.cod_pro=cxp_dt_solicitudes.cod_pro".
				"   AND cxp_rd.ced_bene=cxp_dt_solicitudes.ced_bene".
				"   AND cxp_rd.codtipdoc=cxp_dt_solicitudes.codtipdoc".
				" ".$ls_criterio." ".
				" ".$ls_order." ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_probenrelacionfacturas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detrecdoc->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_facturasproben
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_relacionsolicitudes($as_numsoldes,$as_numsolhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_probenrelacionfacturas
		//         Access: public  
		//	    Arguments: as_numsoldes  // Numero de solicitud de orden de pago desde
		//				   as_numsolhas  // Numero de solicitud de orden de pago hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca los las solicitudes de pago
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 03/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$this->ds_detsolicitudes = new class_datastore();
		if(!empty($as_numsoldes))
		{
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.numsol>='".$as_numsoldes."'";
		}
		if(!empty($as_numsolhas))
		{
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.numsol<='".$as_numsolhas."'";
		}
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRES":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
			case "INFORMIX":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT cxp_solicitudes.numsol,cxp_solicitudes.cod_pro,cxp_solicitudes.ced_bene, ".
				"       cxp_solicitudes.fecemisol,cxp_solicitudes.estprosol,cxp_solicitudes.monsol,".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre ".
				"  FROM cxp_solicitudes ".	
				" WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				" ".$ls_criterio." ".
				" ORDER BY cxp_solicitudes.numsol";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_relacionsolicitudes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_select_relacionsolicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudesprobensaldos($as_tipproben,$as_codprobendes,$as_codprobenhas,$ad_fecemides,$ad_fecemihas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitudesprobensaldos
		//         Access: public  
		//	    Arguments: as_tipproben     // Tipo de Proveedor/Beneficiario
		//                 as_codprobendes  // Codigo de Proveedor/Beneficiario Desde
		//                 as_codprobenhas  // Codigo de Proveedor/Beneficiario Hasta
		//                 ad_fecemides     // Fecha de Emision Desde
		//                 ad_fecemihas     // Fecha de Emision Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca los proveedores/beneficiarios con solicitudes de pago asociadas
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 14/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_tipproben))
		{
			$ls_criterio= $ls_criterio."   AND cxp_solicitudes.tipproben='".$as_tipproben."'";
		}
		if(!empty($as_codprobendes))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.cod_pro>='".$as_codprobendes."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.ced_bene>='".$as_codprobendes."'";
			}
		}
		if(!empty($as_codprobenhas))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.cod_pro<='".$as_codprobenhas."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.ced_bene<='".$as_codprobenhas."'";
			}
		}
		if(!empty($ad_fecemides))
		{
			$ad_fecemides=$this->io_funciones->uf_convertirdatetobd($ad_fecemides);
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.fecemisol>='".$ad_fecemides."'";
		}
		if(!empty($ad_fecemihas))
		{
			$ad_fecemihas=$this->io_funciones->uf_convertirdatetobd($ad_fecemihas);
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.fecemisol<='".$ad_fecemihas."'";
		}

		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRES":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
			case "INFORMIX":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT cxp_solicitudes.cod_pro,cxp_solicitudes.ced_bene,cxp_solicitudes.tipproben, ".
				"   MAX((CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END )) AS nombre, ".
				"   MAX((CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.cod_pro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT rpc_beneficiario.ced_bene ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END )) AS codigo ".
				"  FROM cxp_solicitudes ".	
				" WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"   AND (cxp_solicitudes.estprosol='E'".
				"    OR cxp_solicitudes.estprosol='C'".
				"    OR cxp_solicitudes.estprosol='S')".
				"   ".$ls_criterio." ".
				" GROUP BY cxp_solicitudes.ced_bene,cxp_solicitudes.cod_pro,cxp_solicitudes.tipproben";
				" ORDER BY cxp_solicitudes.ced_bene,cxp_solicitudes.cod_pro";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_solicitudesprobensaldos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_select_solicitudesprobensaldos
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_informacionsaldos($as_tipproben,$as_codproben,$ad_fecemides,$ad_fecemihas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitudesprobensaldos
		//         Access: public  
		//	    Arguments: as_tipproben // Tipo de Proveedor/Beneficiario
		//                 as_codproben // Codigo de Proveedor/Beneficiario Desde
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información de las solicitudes de pago asociadas a proveedores/beneficiarios
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 14/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->ds_detsolicitudes = new class_datastore();
		$lb_valido=true;
		$ls_criterio="";
		if($as_tipproben=="P")
		{
			$ls_criterio=$ls_criterio." AND cxp_solicitudes.cod_pro='".$as_codproben."'".
									  " AND cxp_solicitudes.ced_bene='----------'".
									  " AND cxp_solicitudes.tipproben='P'";
		}
		else
		{
			$ls_criterio=$ls_criterio." AND cxp_solicitudes.ced_bene='".$as_codproben."'".
									  " AND cxp_solicitudes.cod_pro='----------'".
									  " AND cxp_solicitudes.tipproben='B'";
		}
		if(!empty($ad_fecemides))
		{
			$ad_fecemides=$this->io_funciones->uf_convertirdatetobd($ad_fecemides);
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.fecemisol>='".$ad_fecemides."'";
		}
		if(!empty($ad_fecemihas))
		{
			$ad_fecemihas=$this->io_funciones->uf_convertirdatetobd($ad_fecemihas);
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.fecemisol<='".$ad_fecemihas."'";
		}
		
		$ls_sql="SELECT cxp_solicitudes.fecemisol, cxp_solicitudes.consol,cxp_solicitudes.numsol,".
				"       cxp_solicitudes.monsol".
				"  FROM cxp_solicitudes".	
				" WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"   ".$ls_criterio." ".
				"   AND (cxp_solicitudes.estprosol='E'".
				"    OR cxp_solicitudes.estprosol='C'".
				"    OR cxp_solicitudes.estprosol='S')".
				" ORDER BY cxp_solicitudes.fecemisol";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_informacionsaldos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detsolicitudes->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_informacionsaldos
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_informacionndnc($as_tipproben,$as_codproben,$ad_fecemides,$ad_fecemihas,$as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_informacionndnc
		//         Access: public  
		//	    Arguments: as_tipproben // Tipo de Proveedor/Beneficiario
		//                 as_codproben // Codigo de Proveedor/Beneficiario Desde
		//                 ad_fecemides // Fecha de Emision Desde
		//                 ad_fecemihas // Fecha de Emision
		//                 as_numsol    // Numero de la Solicitud
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información de las notas de Debito/Credito de una Solicitud de Pago
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 26/08/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->ds_detndnc = new class_datastore();
		$lb_valido=true;
		$ls_criterio="";
		if($as_tipproben=="P")
		{
			$ls_criterio=$ls_criterio." AND cxp_sol_dc.cod_pro='".$as_codproben."'".
									  " AND cxp_sol_dc.ced_bene='----------'";
		}
		else
		{
			$ls_criterio=$ls_criterio." AND cxp_sol_dc.ced_bene='".$as_codproben."'".
									  " AND cxp_sol_dc.cod_pro='----------'";
		}
		if(!empty($ad_fecemides))
		{
			$ad_fecemides=$this->io_funciones->uf_convertirdatetobd($ad_fecemides);
			$ls_criterio=$ls_criterio. "  AND cxp_sol_dc.fecope>='".$ad_fecemides."'";
		}
		if(!empty($ad_fecemihas))
		{
			$ad_fecemihas=$this->io_funciones->uf_convertirdatetobd($ad_fecemihas);
			$ls_criterio=$ls_criterio. "  AND cxp_sol_dc.fecope<='".$ad_fecemihas."'";
		}
		if(!empty($as_numsol))
		{
			$ls_criterio=$ls_criterio. "  AND cxp_sol_dc.numsol='".$as_numsol."'";
		}
		
		$ls_sql="SELECT cxp_sol_dc.numsol,cxp_sol_dc.monto, cxp_sol_dc.codope,cxp_sol_dc.numdc,".
				"		cxp_sol_dc.fecope,cxp_sol_dc.desope".
				"  FROM cxp_sol_dc".	
				" WHERE cxp_sol_dc.codemp='".$this->ls_codemp."' ".
				"   ".$ls_criterio." ".
				"   AND cxp_sol_dc.estnotadc='C'";
		//		print $ls_sql."<br>";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_informacionndnc ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detndnc->data=$this->io_sql->obtener_datos($rs_data);		
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_informacionndnc
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_informacionpagos($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_informacionpagos
		//         Access: public  
		//	    Arguments: as_tipproben // Tipo de Proveedor/Beneficiario
		//                 as_codproben // Codigo de Proveedor/Beneficiario Desde
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información de las solicitudes de pago asociadas a proveedores/beneficiarios
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 14/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_monto=0;
		$ls_sql="SELECT SUM(monto) AS monto".
				"  FROM cxp_sol_banco".	
				" WHERE cxp_sol_banco.codemp='".$this->ls_codemp."' ".
				"   AND cxp_sol_banco.numsol='".$as_numsol."'".
				" GROUP BY cxp_sol_banco.numsol";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_informacionpagos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_monto=$row["monto"];
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $ls_monto;
	}// end function uf_select_informacionpagos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_relacionndnc($as_tipndnc,$as_numsoldes,$as_numsolhas,$as_ndncdes,$ad_ndnchas,
								    $ad_fecregdes,$ad_fecreghas,$ai_emitida,$ai_contabilizada,$ai_anulada)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_relacionndnc
		//         Access: public  
		//	    Arguments: $as_tipndnc   // Tipo de nota Debito/Credito
		//                 $as_numsoldes // Numero de Solicitud Desde
		//                 $as_numsolhas // Numero de Solicitud Hasta
		//                 $as_ndncdes   // Numero de Nota Debito/Credito Desde
		//                 $ad_ndnchas   // Numero de Nota Debito/Credito Hasta
		//                 $ad_fecregdes // Fecha de Registro Desde
		//                 $ad_fecreghas // Fecha de Registro Hasta
		//                 $ai_emitida   // Estatus de Nota Emitida
		//                 $ai_contabilizada // Estatus de Nota Contabilizada
		//                 $ai_anulada   // Estatus de Nota Anulada
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información de las notas de Debito / Credito
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 17/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_criterio="";
		$lb_valido=true;
		if(!empty($as_numsoldes))
		{
			$ls_criterio= $ls_criterio."   AND cxp_sol_dc.numsol>='".$as_numsoldes."'";
		}
		if(!empty($as_numsolhas))
		{
			$ls_criterio= $ls_criterio."   AND cxp_sol_dc.numsol<='".$as_numsolhas."'";
		}
		if(!empty($as_ndncdes))
		{
			$ls_criterio= $ls_criterio."   AND cxp_sol_dc.numdc>='".$as_ndncdes."'";
		}
		if(!empty($ad_ndnchas))
		{
			$ls_criterio= $ls_criterio."   AND cxp_sol_dc.numdc<='".$ad_ndnchas."'";
		}
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND cxp_sol_dc.fecope>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND cxp_sol_dc.fecope<='".$ad_fecreghas."'";
		}
		switch($as_tipndnc)
		{
			case "D":
				$ls_criterio=$ls_criterio."AND cxp_sol_dc.codope='D'";
			break;
			case "C":
				$ls_criterio=$ls_criterio."AND cxp_sol_dc.codope='C'";
			break;
		}
		if(($ai_emitida==1)||($ai_contabilizada==1)||($ai_anulada==1))
		{
			$lb_anterior=false;
			if($ai_emitida==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_sol_dc.estnotadc='E'";
					$lb_anterior=true;
				}
			}
			if($ai_contabilizada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_sol_dc.estnotadc='C'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR cxp_sol_dc.estnotadc='C'";
				}
			}
			if($ai_anulada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_sol_dc.estnotadc='A'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR cxp_sol_dc.estnotadc='A'";
				}
			}
			if($lb_anterior)
			{
				$ls_criterio=$ls_criterio.")";
			}
		}
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRES":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
			case "INFORMIX":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT cxp_sol_dc.numsol, cxp_sol_dc.numdc, cxp_sol_dc.codtipdoc, cxp_sol_dc.numrecdoc, cxp_sol_dc.fecope,".
				"       cxp_sol_dc.codope, cxp_sol_dc.desope, cxp_sol_dc.monto, cxp_sol_dc.estapr, cxp_sol_dc.estnotadc,".
				"		cxp_documento.dentipdoc,".
				"		(CASE cxp_sol_dc.ced_bene WHEN '----------' THEN (SELECT rpc_proveedor.nompro ".
				"                                        				    FROM rpc_proveedor ".
				"                                       				   WHERE rpc_proveedor.codemp=cxp_sol_dc.codemp ".
				"                                         					 AND rpc_proveedor.cod_pro=cxp_sol_dc.cod_pro) ".
				"                       		  ELSE (SELECT ".$ls_cadena." ".
				"                                         FROM rpc_beneficiario ".
				"                                        WHERE rpc_beneficiario.codemp=cxp_sol_dc.codemp ".
				"                                          AND rpc_beneficiario.ced_bene=cxp_sol_dc.ced_bene) END) AS nombre ".
				"  FROM cxp_sol_dc,cxp_documento".	
				" WHERE cxp_sol_dc.codemp='".$this->ls_codemp."' ".
				" ".$ls_criterio." ".
				"   AND cxp_sol_dc.codtipdoc=cxp_documento.codtipdoc";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_relacionndnc ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_select_relacionndnc
	//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_select_report_libcompra($ld_fecdesde,$ld_fechasta,$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_dt_spg_nota
		//		   Access: public 
		//	  Description: 
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 11/03/2007 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql="SELECT RD.numrecdoc,RD.tipproben,RD.cod_pro,RD.ced_bene,MAX(RD.montotdoc) AS montotdoc,".
				"       MAX(RD.mondeddoc) AS mondeddoc,RD.codtipdoc,MAX(RD.fecemidoc) AS fecemidoc,MAX(RD.numref) AS numref,".
//				"       SUM(COALESCE(RDDED.monret,0)) as monret,MAX(DED.iva) AS iva".
				"       MAX((SELECT cxp_rd_deducciones.monret".
				"		   FROM cxp_rd_deducciones".
				"         WHERE RD.numrecdoc=cxp_rd_deducciones.numrecdoc".
				"           AND RD.codtipdoc=cxp_rd_deducciones.codtipdoc".
				"           AND RD.cod_pro=cxp_rd_deducciones.cod_pro".
				"           AND RD.ced_bene=cxp_rd_deducciones.ced_bene".
				"           AND DED.codded=cxp_rd_deducciones.codded".
				"           AND DED.iva=1)) AS monret,MAX(DED.iva) AS iva".
				"  FROM sigesp_deducciones DED,cxp_rd RD".
				"  LEFT OUTER JOIN cxp_rd_deducciones RDDED".
				"    ON RD.numrecdoc=RDDED.numrecdoc".
				"   AND RD.codtipdoc=RDDED.codtipdoc".
				"   AND RD.cod_pro=RDDED.cod_pro".
				"   AND RD.ced_bene=RDDED.ced_bene".
				" WHERE RD.codemp='".$_SESSION["la_empresa"]["codemp"]."'".
				"   AND estlibcom=1".
				"   AND fecregdoc between '".$ld_fecdesde."' AND '".$ld_fechasta."'".
				//"   AND DED.codded=RDDED.codded".
				"   AND RD.estprodoc='C'".
				" GROUP BY RD.numrecdoc,RD.tipproben,RD.cod_pro,RD.ced_bene,RD.codtipdoc".
				" ORDER BY MAX(fecemidoc)";//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_reportLibCompra ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($this->io_sql->num_rows($rs_data)>0)
			{
			//	mysql_data_seek( $rs_data,0);//Devuelvo el puntero al comienzo
				$lb_valido=true;	
			}
			else
			{
				$lb_valido=false;
			}
			//$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}
	
	
	function uf_select_data($io_sql,$ls_cadena,$ls_campo)
	{//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_data
		//		   Access: public 
		//	  Description: Devuelve el valor del campo enviado como parametro
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 11/03/2007 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$data=$io_sql->select($ls_cadena);	
		if($row=$io_sql->fetch_row($data)){	$ls_result=$row[$ls_campo];}	
		else{$ls_result="";	}
		$io_sql->free_result($data);
		return $ls_result;
	}
	
	function uf_select_rowdata($io_sql,$ls_cadena)
	{//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_rowdata
		//		   Access: public 
		//	  Description: Devuelve la fila resultante del select realizado
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 11/03/2007 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$data=$io_sql->select($ls_cadena);	
		if($row=$io_sql->fetch_row($data)){	$la_result=$row;}	
		else{$la_result=array();	}
		$io_sql->free_result($data);
		return $la_result;
	}
	
	function uf_select_rsdata($ls_cadena,$rs_data)
	{//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_rsdata
		//		   Access: public 
		//	  Description: Devuelve el resultset obtenido de la consulta
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 11/03/2007 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$rs_data=$this->io_sql->select($ls_cadena);	
		if($rs_data===false)
		{
			$this->io_mensajes->message(" ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($this->io_sql->num_rows($rs_data)>0)
			{
				mysql_data_seek($rs_data,0);//Devuelvo el puntero al comienzo
				$lb_valido=true;	
			}
			else
			{
				$lb_valido=false;
			}
			//$this->io_sql->free_result($rs_data);
		}	
		return $lb_valido;	
	}

	function uf_buscar_asientomanual($ls_numrecdoc,$ls_codtipdoc,$ls_cedbene,$ls_codpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_asientomnual
		//		   Access: public 
		//	  Description: 
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 11/03/2007 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		 $ls_sql="SELECT monto 
		 		  FROM cxp_rd_scg 
				  WHERE codemp='".$ls_codemp."'    AND numrecdoc='".$ls_numrecdoc."' AND codtipdoc='".$ls_codtipdoc."'
				  AND 	ced_bene='".$ls_cedbene."' AND cod_pro='".$ls_codpro."'      AND estasicon='M' AND debhab='H'";
		 $ldec_monto = $this->uf_select_data($this->io_sql,$ls_sql,"monto");
		 if($ldec_monto=="")
		 {
		 	$ldec_monto=0;
		 }
		 return $ldec_monto;
	}
	
	function uf_select_dt_spg_nota($ls_numnota,$ls_numord,$ls_numrecdoc,$ls_codtipdoc,$ls_tipproben,$ls_codproben,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_dt_spg_nota
		//		   Access: public 
		//	  Description: 
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 11/03/2007 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($ls_tipproben=='P')
		{
			$ls_aux=" AND a.cod_pro='".$ls_codproben."' ";
		}
		else
		{
			$ls_aux=" AND a.ced_bene='".$ls_codproben."' ";
		}
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_aux_codestpro=" a.codestpro=CONCAT(b.codestpro1,b.codestpro2,b.codestpro3,b.codestpro4,b.codestpro5) ";
				break;
			case "POSTGRES":
				$ls_aux_codestpro=" a.codestpro=(b.codestpro1||b.codestpro2||b.codestpro3||b.codestpro4||b.codestpro5) ";
				break;
			case "INFORMIX":
				$ls_aux_codestpro=" a.codestpro=(b.codestpro1||b.codestpro2||b.codestpro3||b.codestpro4||b.codestpro5) ";
				break;
		}
		$ls_sql="SELECT a.spg_cuenta,a.monto,a.codestpro,b.denominacion".
				"  FROM cxp_dc_spg a,spg_cuentas b".
				" WHERE a.codemp='".$_SESSION["la_empresa"]["codemp"]."'".
				"   AND a.numdc='".$ls_numnota."'".
				"   AND a.numsol='".$ls_numord."'".
				"   AND a.numrecdoc='".$ls_numrecdoc."'".
				"   AND a.codtipdoc='".$ls_codtipdoc."'".
				"   AND ".$ls_aux_codestpro."".
				"   AND a.spg_cuenta=b.spg_cuenta".
				"   AND a.codemp=b.codemp ".$ls_aux."".
				" ORDER BY a.codestpro,a.spg_cuenta";	
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_dt_spg_nota ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($this->io_sql->num_rows($rs_data)>0)
			{
//				mysql_data_seek($rs_data,0);//Devuelvo el puntero al comienzo
				$lb_valido=true;	
			}
			else
			{
				$lb_valido=false;
			}
			//$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;			
						
	}
	
	function uf_select_dt_scg_nota($ls_numnota,$ls_numord,$ls_numrecdoc,$ls_codtipdoc,$ls_tipproben,$ls_codproben,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_dt_scg_nota
		//		   Access: public 
		//	  Description: 
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 11/03/2007 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($ls_tipproben=='P')
		{
			$ls_aux=" AND a.cod_pro='".$ls_codproben."' ";
		}
		else
		{
			$ls_aux=" AND a.ced_bene='".$ls_codproben."' ";
		}

		$ls_sql="SELECT a.*,b.denominacion 
				 FROM cxp_dc_scg a,scg_cuentas b 
				 WHERE a.codemp='".$_SESSION["la_empresa"]["codemp"]."' AND a.numdc='".$ls_numnota."' AND a.numsol='".$ls_numord."' 
				 AND a.numrecdoc='".$ls_numrecdoc."' AND a.codtipdoc='".$ls_codtipdoc."' 
				 AND a.sc_cuenta=b.sc_cuenta AND a.codemp=b.codemp ".$ls_aux."
				 ORDER BY a.debhab DESC,a.sc_cuenta";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_dt_spg_nota ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($this->io_sql->num_rows($rs_data)>0)
			{
//				mysql_data_seek( $rs_data,0);//Devuelvo el puntero al comienzo
				$lb_valido=true;	
			}
			else
			{
				$lb_valido=false;
			}
			//$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;			
						
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudesactualescxp($as_tipproben,$as_cedbene,$as_codpro,$ad_fecregdes,$ad_fecreghas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitudesactualescxp
		//         Access: public  
		//	    Arguments: as_tipproben  // Tipo de Proveedor/Beneficiario
		//                 as_cedbene    // Codigo de Beneficiario
		//                 as_codpro     // Codigo de Proveedor
		//                 ad_fecregdes  // Fecha de Registro Desde
		//                 ad_fecreghas  // Fecha de Registro Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las solicitudes de pagos  en el intervalo indicado
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$ls_criterio="";
		$this->ds_solactuales = new class_datastore();
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND cxp_historico_solicitud.fecha>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND cxp_historico_solicitud.fecha<='".$ad_fecreghas."'";
		}
		$ls_sql=" SELECT cxp_solicitudes.numsol,cxp_solicitudes.consol,cxp_solicitudes.monsol, cxp_historico_solicitud.estprodoc,".
				"		 cxp_historico_solicitud.fecha ".
				"   FROM cxp_solicitudes, cxp_historico_solicitud".
				"  WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"	 AND cxp_solicitudes.tipproben='".$as_tipproben."'".
				"    AND cxp_solicitudes.ced_bene='".$as_cedbene."'".
				"    AND cxp_solicitudes.cod_pro='".$as_codpro."'".
				"    AND cxp_historico_solicitud.estprodoc='C'".
				"    AND cxp_solicitudes.estprosol<>'A'".
				" ".$ls_criterio." ".
				"    AND cxp_historico_solicitud.codemp=cxp_solicitudes.codemp".
				"    AND cxp_solicitudes.numsol=cxp_historico_solicitud.numsol".
				" ORDER BY cxp_solicitudes.ced_bene, cxp_solicitudes.cod_pro ";	
			//	print $ls_sql."<br><br>";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_solicitudesactualescxp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_solactuales->data=$this->io_sql->obtener_datos($rs_data);		
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_solicitudesactualescxp
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_informacionpagoscxp($as_tipproben,$as_cedbene,$as_codpro,$ad_fecregdes,$ad_fecreghas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_informacionpagoscxp
		//         Access: public  
		//	    Arguments: as_tipproben  // Tipo de Proveedor/Beneficiario
		//                 as_cedbene    // Codigo de Beneficiario
		//                 as_codpro     // Codigo de Proveedor
		//                 ad_fecregdes  // Fecha de Registro Desde
		//                 ad_fecreghas  // Fecha de Registro Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información de las solicitudes de pago asociadas a proveedores/beneficiarios
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 14/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_monto=0;
		$lb_valido= true;
		$ls_criterio="";
		$this->ds_pagactuales = new class_datastore();
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND cxp_historico_solicitud.fecha>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND cxp_historico_solicitud.fecha<='".$ad_fecreghas."'";
		}
		$ls_sql=" SELECT cxp_solicitudes.numsol,cxp_sol_banco.monto,cxp_sol_banco.codope,scb_movbco.fecmov,scb_movbco.conmov ".
				"   FROM cxp_solicitudes, cxp_historico_solicitud,cxp_sol_banco,scb_movbco".
				"  WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"	 AND cxp_solicitudes.tipproben='".$as_tipproben."'".
				"    AND cxp_solicitudes.ced_bene='".$as_cedbene."'".
				"    AND cxp_solicitudes.cod_pro='".$as_codpro."'".
				"    AND cxp_historico_solicitud.estprodoc='P'".
				"    AND cxp_solicitudes.estprosol<>'A'".
				" ".$ls_criterio." ".
				"    AND cxp_historico_solicitud.codemp=cxp_solicitudes.codemp".
				"    AND cxp_solicitudes.numsol=cxp_historico_solicitud.numsol".
				"    AND cxp_solicitudes.codemp=cxp_sol_banco.codemp".
				"    AND cxp_solicitudes.numsol=cxp_sol_banco.numsol".
				"    AND cxp_sol_banco.codemp=scb_movbco.codemp".
				"    AND cxp_sol_banco.codban=scb_movbco.codban".
				"    AND cxp_sol_banco.ctaban=scb_movbco.ctaban".
				"    AND cxp_sol_banco.numdoc=scb_movbco.numdoc".
				"    AND cxp_sol_banco.codope=scb_movbco.codope".
				" ORDER BY cxp_solicitudes.ced_bene, cxp_solicitudes.cod_pro ";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_informacionpagoscxp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_pagactuales->data=$this->io_sql->obtener_datos($rs_data);		
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_informacionpagoscxp
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	function uf_datos_comprobante($ls_codemp,$ls_numrecdoc,$ls_codpro,$ls_cedben,$ls_codproben)
	{//Utilizado en libro de compra
	
		$la_data=$this->uf_select_rowdata($this->io_sql,"SELECT a.numrecdoc as numrecdoc,a.monobjret as monobjret,a.monret as monret,a.porded as porded,
																              b.codret as codret,b.numcom as numcom,b.iva_ret as iva_ret,tiptrans
												  FROM cxp_rd_deducciones a,scb_dt_cmp_ret b,scb_cmp_ret cmp
												  WHERE a.codemp='".$ls_codemp."' AND a.numrecdoc='".$ls_numrecdoc."' AND a.cod_pro='".$ls_codpro."' AND a.ced_bene='".$ls_cedben."' AND cmp.codsujret='".$ls_codproben."'
												  AND a.codemp=b.codemp AND a.codemp=cmp.codemp AND a.numrecdoc=b.numfac AND b.codret=cmp.codret AND b.numcom=cmp.numcom
												  GROUP BY a.numrecdoc ");
		return $la_data;
	}
	
	function uf_select_cargos($ls_codemp,$ls_numrecdoc,$ls_codtipdoc,$ls_codpro,$ls_cedben)
	{//Utilizado en libro de compra.
		$la_data=$this->uf_select_rowdata($this->io_sql,"SELECT monobjret as basimp,porcar,monret as impiva".
															 "  FROM cxp_rd_cargos ".
															 " WHERE codemp='".$ls_codemp."'".
															 "   AND numrecdoc='".$ls_numrecdoc."'".
															 "   AND codtipdoc='".$ls_codtipdoc."'".
															 "   AND cod_pro='".$ls_codpro."'".
															 "   AND ced_bene='".$ls_cedben."'");
		return	$la_data;	
	}
	
	function uf_select_dtnotas($ls_codemp,$ls_numrecdoc,$ls_codtipdoc,$ls_codpro,$ls_cedben,&$rs_data)
	{
		$ls_cadena="SELECT * 
					FROM cxp_sol_dc 
					WHERE codemp='".$ls_codemp."' AND numrecdoc='".$ls_numrecdoc."' AND codtipdoc='".$ls_codtipdoc."' 
					AND cod_pro='".$ls_codpro."' AND ced_bene='".$ls_cedben."'";
		$lb_valido=$this->uf_select_rsdata($ls_cadena,&$rs_data);
		return $lb_valido;	
	}
	
	function uf_select_notaformatosalida($ls_codemp,$ls_numnota,$ls_tiponota,$ls_numord,$ls_numrecdoc,$ls_codtipdoc,$ls_aux)
	{
		$la_nota=$this->uf_select_rowdata($this->io_sql,"SELECT * FROM cxp_sol_dc WHERE codemp='".$ls_codemp."' 
												   AND numdc='".$ls_numnota."' AND codope='".$ls_tiponota."' AND numsol='".$ls_numord."' 
												   AND numrecdoc='".$ls_numrecdoc."' AND codtipdoc='".$ls_codtipdoc."' ".$ls_aux);
		return $la_nota;
	
	}
	
	function uf_select_notacargos($ls_codemp,$ls_numrecdoc,$ls_codtipdoc,$ls_codpro,$ls_cedben,$ls_codope,$ls_numnota,$ls_numsol,$ldec_porcar)
	{
		$ldec_monto=0;
		$ldec_porcar=0;
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_aux_codestpro=" CONCAT(spg_cuenta,codestpro)";
				break;
			case "POSTGRES":
				$ls_aux_codestpro=" (b.codestpro||b.spg_cuenta) ";
				break;
			case "INFORMIX":
				$ls_aux_codestpro=" (b.codestpro||b.spg_cuenta) ";
				break;
		}
		$ls_sql="SELECT monto,porcar
				 FROM cxp_dc_spg a,cxp_rd_cargos c
				 WHERE a.numdc='".$ls_numnota."' AND a.codope='".$ls_codope."' AND a.numsol='".$ls_numsol."' AND a.numrecdoc='".$ls_numrecdoc."' AND a.codtipdoc='".$ls_codtipdoc."' AND a.cod_pro='".$ls_codpro."'
				 AND a.ced_bene='".$ls_cedben."' AND a.numrecdoc=c.numrecdoc AND a.codtipdoc=c.codtipdoc AND a.cod_pro=c.cod_pro AND a.ced_bene=c.ced_bene AND CONCAT(a.spg_cuenta,a.codestpro) IN (SELECT DISTINCT CONCAT(spg_cuenta,codestpro) FROM sigesp_cargos)";

		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_informacionpagoscxp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ldec_monto=$row["monto"];
				$ldec_porcar=$row["porcar"];
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $ldec_monto;
	}

	function uf_nrocomprobante($as_fac,$as_nrocon,$as_fecha)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_nrocomprobante
		//         Access: public  
		//	    Arguments: as_fac     // nurmero de la factura
		//      Arguments: as_nrocon     // numero de control
		//      Arguments: as_fecha     // fecha de la factura
		//	      Returns: lb_valido True si encontro un numero de comprobante
		//    Description: Función que busca el numero de  comprobantes ISRL
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 09/01/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nrocom=0;
		$as_fecha=$this->io_funciones->uf_convertirdatetobd($as_fecha);
		$ls_sql="select numcom from scb_dt_cmp_ret
                 where codemp= '".$this->ls_codemp."'
				 and numfac like '%".$as_fac."%' 
				 and numcon like '%".$as_nrocon."%' 
				 and fecfac='".$as_fecha."'";		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_nrocomprobante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_nrocom=$row["numcom"];
				
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $ls_nrocom;
	}// end function uf_retencionesmunicipales_detalles
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_iva_retendio_ISLR($as_fac,$as_nrocon,$as_fecha)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_iva_retendio_ISLR
		//         Access: public  
		//	    Arguments: as_fac     // nurmero de la factura
		//      Arguments: as_nrocon     // numero de control
		//      Arguments: as_fecha     // fecha de la factura
		//	      Returns: lb_valido True si encontro un numero de comprobante
		//    Description: Función que busca el iva retenido
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 09/01/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_iva=0;
		$as_fecha=$this->io_funciones->uf_convertirdatetobd($as_fecha);
		$ls_sql="select iva_ret as iva from scb_dt_cmp_ret where codemp= '".$this->ls_codemp."' and numfac like '%".$as_fac."%' 
				 and numcon like '%".$as_nrocon."%' 
				 and fecfac='".$as_fecha."'";		
		$rs_data=$this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_iva_retendio_ISLR ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_iva=$row["iva"];
				
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $ls_iva;
	}// end function uf_retencionesmunicipales_detalles
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_fecha_ISLR($as_fac,$as_nrocon,$as_fecha)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_iva_retendio_ISLR
		//         Access: public  
		//	    Arguments: as_fac     // nurmero de la factura
		//      Arguments: as_nrocon     // numero de control
		//      Arguments: as_fecha     // fecha de la factura
		//	      Returns: lb_valido True si encontro un numero de comprobante
		//    Description: Función que busca el iva retenido
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 09/01/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_fecha="";
		$as_fecha=$this->io_funciones->uf_convertirdatetobd($as_fecha);
		$ls_sql="select a.fecrep 
                   from scb_cmp_ret  a
                   join scb_dt_cmp_ret b on (a.numcom=b.numcom)
                   where a.codemp= '".$this->ls_codemp."' 
                   and b.numfac like '%".$as_fac."%'
                   and b.numcon like '%".$as_nrocon."%'
                   and b.fecfac='".$as_fecha."'";		
		$rs_data=$this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_fecha_ISLR ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_fecha=$row["fecrep"];
				
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $ls_fecha;
	}// end function uf_retencionesmunicipales_detalles
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_det_deducciones($as_numrecdoc,$as_codpro,$as_cedben,$as_codtipdoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_det_deducciones
		//         Access: public  
		//	    Arguments: as_numrecdoc  // Numero de Recepcion de Documentos
		//                 as_cedbene    // Codigo de Beneficiario
		//                 as_codpro     // Codigo de Proveedor
		//                 as_codtipdoc  // Codigo de Tipo de Documento
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca las retenciones de IVA.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/06/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_monret=0;
		$ls_sql=" SELECT SUM(cxp_rd_deducciones.monret) as monret".
				"   FROM  cxp_rd_deducciones, sigesp_deducciones".
				"   WHERE cxp_rd_deducciones.codemp='".$this->ls_codemp."'".
				"     AND cxp_rd_deducciones.numrecdoc ='".$as_numrecdoc."'". 
				"     AND cxp_rd_deducciones.codtipdoc ='".$as_codtipdoc."'". 
				"	  AND cxp_rd_deducciones.cod_pro='".$as_codpro."'".
				"     AND cxp_rd_deducciones.ced_bene='".$as_cedben."'".      
				"     AND cxp_rd_deducciones.codemp=sigesp_deducciones.codemp".
				"	  AND cxp_rd_deducciones.codded=sigesp_deducciones.codded".
				"     AND (sigesp_deducciones.iva=1 OR sigesp_deducciones.islr=1)";
		$rs=$this->io_sql->select($ls_sql);
		if ($rs===false)
		{		 
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_det_deducciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs))
			{
				$li_monret=$row["monret"];
			}
		}
    	return $li_monret;		
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_det_deducciones_solpag($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_det_deducciones_solpag
		//         Access: public  
		//	    Arguments: as_numrecdoc  // Numero de Recepcion de Documentos
		//                 as_cedbene    // Codigo de Beneficiario
		//                 as_codpro     // Codigo de Proveedor
		//                 as_codtipdoc  // Codigo de Tipo de Documento
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca las retenciones de IVA.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/06/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_monret=0;
		$ls_sql=" SELECT SUM(cxp_rd_deducciones.monret) as monret".
				"   FROM  cxp_rd_deducciones, sigesp_deducciones,cxp_dt_solicitudes".
				"   WHERE cxp_dt_solicitudes.codemp='".$this->ls_codemp."'".
				"     AND cxp_dt_solicitudes.numsol ='".$as_numsol."'". 
				"     AND cxp_rd_deducciones.codemp =cxp_dt_solicitudes.codemp". 
				"     AND cxp_rd_deducciones.numrecdoc =cxp_dt_solicitudes.numrecdoc". 
				"     AND cxp_rd_deducciones.codtipdoc =cxp_dt_solicitudes.codtipdoc". 
				"     AND cxp_rd_deducciones.cod_pro =cxp_dt_solicitudes.cod_pro". 
				"     AND cxp_rd_deducciones.ced_bene =cxp_dt_solicitudes.ced_bene". 
				"     AND cxp_rd_deducciones.codemp=sigesp_deducciones.codemp".
				"	  AND cxp_rd_deducciones.codded=sigesp_deducciones.codded".
				"     AND (sigesp_deducciones.iva=1 OR sigesp_deducciones.islr=1)";
		$rs=$this->io_sql->select($ls_sql);
		if ($rs===false)
		{		 
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_det_deducciones_solpag ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs))
			{
				$li_monret=$row["monret"];
			}
		}
    	return $li_monret;		
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_select_datos_cheque_retencion($as_numsol,&$as_nummov,&$ad_fecmov,&$as_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_datos_cheque_retencion
		//         Access: public  
		//	    Arguments: $as_numsol  // Numero de solicitud
		//                 $as_nummov  // Numero del movimiento (cheque)
		//                 $ad_fecmov  // Fecha  del movimiento (cheque)
		//                 $as_monto   // Monto del movimiento (cheque)
		//    Description: Función que busca los datos del cheque de la retencion del impuesto de 1 x 100
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 31/10/2008									Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_nummov="";
		$ad_fecmov="";
		$as_monto=0;
		$ls_sql=" SELECT cxp_sol_banco.numdoc,scb_movbco.fecmov,scb_movbco.monto  ".
				"   FROM  cxp_sol_banco,scb_movbco ".
				"   WHERE cxp_sol_banco.codemp='".$this->ls_codemp."'".
				"     AND cxp_sol_banco.numsol ='".$as_numsol."'". 
				"     AND scb_movbco.codemp =cxp_sol_banco.codemp".
				"     AND scb_movbco.numdoc =cxp_sol_banco.numdoc".
				"     AND scb_movbco.estmov <> 'A'".
				"     AND scb_movbco.estmov <> 'O'";
		$rs=$this->io_sql->select($ls_sql);
		if ($rs===false)
		{		 
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_det_deducciones_solpag ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs))
			{
				$as_nummov=$row["numdoc"];
				$ad_fecmov=$row["fecmov"];
				$as_monto=$row["monto"];
			}
		}
    	return $lb_valido;		
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_select_contribuyentes_libro_timbrefiscal($as_mes,$as_anio,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_contribuyentes_libro_timbrefiscal
		//         Access: public  
		//	    Arguments:  as_mes    // mes del comprobante
		//	    		   as_anio   // año del comprobante
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información de los comprobantes municipales
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 25/11/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fechadesde=$as_anio."-".$as_mes."-01";
		$ld_fechahasta=$as_anio."-".$as_mes."-".substr($this->io_fecha->uf_last_day($as_mes,$as_anio),0,2);
		$ls_sql="SELECT  scb_cmp_ret.nomsujret, scb_cmp_ret.rif, scb_cmp_ret.numcom, ".
			    " scb_dt_cmp_ret.basimp, scb_dt_cmp_ret.iva_ret, scb_dt_cmp_ret.fecfac".
				"  FROM scb_cmp_ret,scb_dt_cmp_ret ".
				" WHERE scb_cmp_ret.codemp='".$this->ls_codemp."' ".
				"   AND scb_cmp_ret.codret='0000000003' ".
				"   AND scb_cmp_ret.fecrep>='".$ld_fechadesde."' ".
				"   AND scb_cmp_ret.fecrep<='".$ld_fechahasta."' ".
				"   AND scb_cmp_ret.codemp = scb_dt_cmp_ret.codemp ".
				"   AND scb_cmp_ret.codret = scb_dt_cmp_ret.codret ".
				"   AND scb_cmp_ret.numcom = scb_dt_cmp_ret.numcom";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_contribuyentes_libro_timbrefiscal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($rs_data->RecordCount()==0)
			{
				$lb_valido=false;
			}
			
		}		
		return $lb_valido;
	}// end function uf_select_contribuyentes_libro_timbrefiscal
	
//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_select_beneficiarios_libro_islr($as_mes,$as_anio,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_beneficiarios_libro_islr
		//         Access: public  
		//	    Arguments:  as_mes    // mes del comprobante
		//	    		   as_anio   // año del comprobante
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información de los comprobantes de impuesto sobre la renta
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 26/11/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fecdes=$as_anio."-".$as_mes."-01";
		$ld_fechas=$as_anio."-".$as_mes."-".substr($this->io_fecha->uf_last_day($as_mes,$as_anio),0,2);
		if($ld_fecdes!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.fecemisol >= '".$ld_fecdes."'";
			$ls_criterio2=$ls_criterio2."		AND scb_movbco.fecmov >= '".$ld_fecdes."'";
		}
		if($ld_fechas!="")
		{
			$ls_criterio=$ls_criterio."		AND cxp_solicitudes.fecemisol <= '".$ld_fechas."'";
			$ls_criterio2=$ls_criterio2."		AND scb_movbco.fecmov <= '".$ld_fechas."'";
		}
		
		$ls_sql="SELECT DISTINCT cxp_solicitudes.numsol AS numero, cxp_solicitudes.consol AS concepto, cxp_rd.procede AS procede ".
				"  FROM cxp_solicitudes, cxp_dt_solicitudes, cxp_rd, cxp_rd_deducciones, sigesp_deducciones ".
			    " WHERE cxp_solicitudes.codemp = '".$this->ls_codemp."' ".
				"   AND sigesp_deducciones.islr=1 ".
				$ls_criterio.
				"   AND cxp_solicitudes.estprosol<>'A'".
			    "   AND cxp_solicitudes.codemp = cxp_dt_solicitudes.codemp ".
			    "   AND cxp_solicitudes.numsol = cxp_dt_solicitudes.numsol ".
				"   AND cxp_solicitudes.cod_pro = cxp_dt_solicitudes.cod_pro ".
				"   AND cxp_solicitudes.ced_bene = cxp_dt_solicitudes.ced_bene ".
				"	AND cxp_dt_solicitudes.codemp = cxp_rd.codemp ".
				"	AND cxp_dt_solicitudes.numrecdoc = cxp_rd.numrecdoc ".
				"	AND cxp_dt_solicitudes.codtipdoc = cxp_rd.codtipdoc ".
				"	AND cxp_dt_solicitudes.cod_pro = cxp_rd.cod_pro ".
				"   AND cxp_dt_solicitudes.ced_bene = cxp_rd.ced_bene ".
				"	AND cxp_rd.codemp = cxp_rd_deducciones.codemp ".
				"	AND cxp_rd.numrecdoc = cxp_rd_deducciones.numrecdoc ".
				"	AND cxp_rd.codtipdoc = cxp_rd_deducciones.codtipdoc ".
				"   AND cxp_rd.cod_pro = cxp_rd_deducciones.cod_pro ".
				"	AND cxp_rd.ced_bene = cxp_rd_deducciones.ced_bene ".
				"	AND cxp_rd_deducciones.codemp = sigesp_deducciones.codemp ".
				"	AND cxp_rd_deducciones.codded = sigesp_deducciones.codded ".
				" UNION ".
				"SELECT scb_movbco.numdoc AS numero, MAX(scb_movbco.conmov) AS concepto, MAX(scb_movbco.procede) AS procede ".
			    "  FROM scb_movbco, sigesp_deducciones, scb_movbco_scg ".
				" WHERE scb_movbco.codemp = '".$this->ls_codemp."' ".
				"   AND scb_movbco.codope = 'CH' ".
				"   AND scb_movbco.estmov <> 'A' ".
				"   AND scb_movbco.estmov <> 'O' ".
				"   AND scb_movbco.monret <> 0 ".
				"   AND sigesp_deducciones.islr = 1".
				$ls_criterio2.
				"    AND scb_movbco.codemp = scb_movbco_scg.codemp ".
				"    AND scb_movbco.codban = scb_movbco_scg.codban ".
				"    AND scb_movbco.ctaban = scb_movbco_scg.ctaban ".
				"    AND scb_movbco.numdoc = scb_movbco_scg.numdoc ".
				"    AND scb_movbco.codope = scb_movbco_scg.codope ".
				"    AND scb_movbco.estmov = scb_movbco_scg.estmov ".
				"    AND scb_movbco_scg.codemp = sigesp_deducciones.codemp ".
				"    AND scb_movbco_scg.codded = sigesp_deducciones.codded ".
				"  GROUP BY scb_movbco.numdoc ".
				" UNION ".
				"SELECT cxp_cmp_islr.numsol AS numero, cxp_cmp_islr.consol AS concepto, 'INT' AS procede".
				"  FROM cxp_cmp_islr".
				" WHERE cxp_cmp_islr.codemp = '".$this->ls_codemp."' ".
			    "  ORDER BY numero ";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_beneficiarios_libro_islr ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($rs_data->RecordCount()==0)
			{
				$lb_valido=false;
			}
			
		}		
		return $lb_valido;
	}// end function uf_select_beneficiarios_libro_islr
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_deducciones_recepcion($as_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedben)
	{
     //////////////////////////////////////////////////////////////////////////////
     //	Funcion      uf_select_deducciones_recepcion
     //	Access       public
     //	Arguments    $as_codemp,$as_numero
     //	Returns	     $rs (Resulset)
     //	Description  Devuelve un resulset para cargar las receptciones asociados a una
     //              Solicitud, se utiliza en el catalogo de principal del solicitud
     //              de Ejecucion Presupuestaria  
     //////////////////////////////////////////////////////////////////////////////
     $this->ds_ded_rd = new class_datastore();
	 
	 $ls_sql=" SELECT cxp_rd_deducciones.monret,
					  sigesp_deducciones.dended
		  	     FROM cxp_rd , cxp_rd_deducciones, sigesp_deducciones
                WHERE cxp_rd.codemp='".$as_codemp."'  
			      AND cxp_rd.numrecdoc ='".$as_numrecdoc."' 
				  AND cxp_rd.codtipdoc ='".$as_codtipdoc."' 
				  AND cxp_rd.cod_pro   ='".$as_codpro."' 
	 			  AND cxp_rd.ced_bene  ='".$as_cedben."' 
                  AND cxp_rd.codemp=cxp_rd_deducciones.codemp
				  AND cxp_rd.numrecdoc=cxp_rd_deducciones.numrecdoc
				  AND cxp_rd.codtipdoc=cxp_rd_deducciones.codtipdoc
				  AND cxp_rd.cod_pro=cxp_rd_deducciones.cod_pro
				  AND cxp_rd.ced_bene=cxp_rd_deducciones.ced_bene
				  AND sigesp_deducciones.codemp=cxp_rd_deducciones.codemp
				  AND sigesp_deducciones.codded=cxp_rd_deducciones.codded";//print $ls_sql;
			  				
	 $this->ds_ded_rd->resetds("numrecdoc");			
	 $rs=$this->io_sql->select($ls_sql);
	 if ($rs===false)
		 {		 
			 $this->io_msg->message("Error en Sentencia->uf_select_deducciones_recepcion");
			 $lb_valido=false;
		 }
         else
         {
		     if ($row=$this->io_sql->fetch_row($rs))
		     {
				  $lb_valido=true;				
				  $datos=$this->io_sql->obtener_datos($rs);
				  $this->ds_ded_rd->data=$datos;			  				 
		    	  $this->io_sql->free_result($rs);  	
			 }
             else
             {   
                 $lb_valido=false;			     
             }	
         }
    return $lb_valido;		
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudesubicar($as_tipproben,$as_codprobendes,$as_codprobenhas,$as_numsoldes,$as_numsolhas,&$lb_valido)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitudesubicar
		//         Access: public  
		//	    Arguments: as_tipproben     // Tipo de Proveedor/Beneficiario
		//                 as_codprobendes  // Codigo de Proveedor/Beneficiario Desde
		//                 as_codprobenhas  // Codigo de Proveedor/Beneficiario Hasta
		//                 as_numsoldes     // Numero de Solicitud Desde
		//                 as_numsolhas     // Numero de Solicitud Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las solicitudes de pago en los intervalos indicados
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_tipproben))
		{
			$ls_criterio= $ls_criterio."   AND cxp_solicitudes.tipproben='".$as_tipproben."'";
		}
		if(!empty($as_codprobendes))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.cod_pro>='".$as_codprobendes."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.ced_bene>='".$as_codprobendes."'";
			}
		}
		if(!empty($as_codprobenhas))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.cod_pro<='".$as_codprobenhas."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND cxp_solicitudes.ced_bene<='".$as_codprobenhas."'";
			}
		}
		if(!empty($as_numsoldes))
		{
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.numsol>='".$as_numsoldes."'";
		}
		if(!empty($as_numsolhas))
		{
			$ls_criterio=$ls_criterio. "  AND cxp_solicitudes.numsol<='".$as_numsolhas."'";
		}

		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRES":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
			case "INFORMIX":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT DISTINCT cxp_solicitudes.tipproben,cxp_solicitudes.numsol,cxp_solicitudes.cod_pro,cxp_solicitudes.ced_bene, ".
				"        cxp_solicitudes.fecemisol,cxp_solicitudes.estprosol,cxp_solicitudes.monsol,".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre ".
				"  FROM cxp_solicitudes ".	
				" WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"   ".$ls_criterio." ".
				" ORDER BY cxp_solicitudes.numsol";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_solicitudesubicar ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $rs_data;
	}// end function uf_select_solicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_ubicacionsol($as_numsol,&$lb_valido)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_load_ubicacionsol
		//         Access: public  
		//	    Arguments: as_tipproben     // Tipo de Proveedor/Beneficiario
		//                 as_codprobendes  // Codigo de Proveedor/Beneficiario Desde
		//                 as_codprobenhas  // Codigo de Proveedor/Beneficiario Hasta
		//                 as_numsoldes     // Numero de Solicitud Desde
		//                 as_numsolhas     // Numero de Solicitud Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las solicitudes de pago en los intervalos indicados
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT numsol,numdoc,codban,ctaban,estmov,".
				"       (SELECT nomban FROM scb_banco".
				"		  WHERE cxp_sol_banco.codemp=scb_banco.codemp".
				"           AND cxp_sol_banco.codban=scb_banco.codban) AS banco".
				"  FROM cxp_sol_banco".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND numsol='".$as_numsol."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_load_ubicacionsol ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $rs_data;
	}// end function uf_select_solicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_ubicacion_recepciones($as_tipproben,$as_codprobendes,$as_codprobenhas,$ad_fecregdes,$ad_fecreghas,$as_codtipdoc,
								   			 $ai_registrada,$ai_anulada,$ai_procesada,$as_orden,$as_numrecdoc)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_ubicacion_recepciones
		//         Access: public  
		//	    Arguments: as_tipproben     // Tipo de Proveedor/Beneficiario
		//                 as_codprobendes  // Codigo de Proveedor/Beneficiario Desde
		//                 as_codprobenhas  // Codigo de Proveedor/Beneficiario Hasta
		//                 ad_fecregdes     // Fecha de Registro Desde
		//                 ad_fecreghas     // Fecha de Registro Hasta
		//                 as_codtipdoc     // Codigo de Tipo de Documento
		//                 as_registrada    // Estatus de la Recepcion Registrada
		//                 ai_anulada       // Estatus de la Recepcion Anulada
		//                 ai_procesada     // Estatus de la Recepcion Procesada
		//                 as_numrecdoc     // Nro de Recepción de documentos
		//                 ai_orden         // Orden de los Datos en el Reporte Numero/Fecha
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las ubicaciones de recepciones de documentos en los intervalos indicados
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 31/03/2009									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_numrecdoc))
		{
			$ls_criterio= $ls_criterio."   AND cxp_rd.numrecdoc='".$as_numrecdoc."'";
		}
		if(!empty($as_tipproben))
		{
			$ls_criterio= $ls_criterio."   AND cxp_rd.tipproben='".$as_tipproben."'";
		}
		if(!empty($as_codprobendes))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND cxp_rd.cod_pro>='".$as_codprobendes."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND cxp_rd.ced_bene>='".$as_codprobendes."'";
			}
		}
		if(!empty($as_codprobenhas))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND cxp_rd.cod_pro<='".$as_codprobenhas."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND cxp_rd.ced_bene<='".$as_codprobenhas."'";
			}
		}
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND cxp_rd.fecregdoc>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND cxp_rd.fecregdoc<='".$ad_fecreghas."'";
		}

		if(!empty($as_codtipdoc) &&($as_codtipdoc!="-"))
		{
			$as_codtipdoc=substr($as_codtipdoc,0,5);
			$ls_criterio= $ls_criterio."   AND cxp_rd.codtipdoc='".$as_codtipdoc."'";
		}
		
		if(($ai_registrada==1)||($ai_procesada==1)||($ai_anulada==1))
		{
			$lb_anterior=false;
			if($ai_registrada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_rd.estprodoc='R'";
					$lb_anterior=true;
				}
			}
			if($ai_procesada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_rd.estprodoc='C'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR cxp_rd.estprodoc='C'";
				}
			}
			if($ai_anulada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (cxp_rd.estprodoc='A'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR cxp_rd.estprodoc='A'";
				}
			}
			if($lb_anterior)
			{
				$ls_criterio=$ls_criterio.")";
			}
		}

		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="cxp_rd.numrecdoc ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="cxp_rd.fecregdoc ";
				break;

		}
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRES":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
			case "INFORMIX":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT cxp_rd.numrecdoc,  MAX(cxp_documento.dentipdoc) AS dentipdoc, MAX(cxp_rd.estprodoc) AS estprodoc, ".
				"		cxp_solicitudes.numsol, MAX(cxp_solicitudes.estprosol) AS estprosol, ".
				"       (CASE MAX(cxp_rd.tipproben) WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_rd.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_rd.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_rd.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_rd.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre ".
				"  FROM cxp_rd ".
				"  INNER JOIN cxp_documento ".	
				"     ON cxp_rd.codtipdoc=cxp_documento.codtipdoc ".
				"  LEFT JOIN (cxp_dt_solicitudes ".
				"             INNER JOIN cxp_solicitudes ".
				"				 ON cxp_dt_solicitudes.codemp=cxp_solicitudes.codemp ".
				"				AND cxp_dt_solicitudes.numsol=cxp_solicitudes.numsol) ".
				"	  ON cxp_rd.codemp=cxp_dt_solicitudes.codemp ".
				"	 AND cxp_rd.numrecdoc=cxp_dt_solicitudes.numrecdoc ".
				"	 AND cxp_rd.codtipdoc=cxp_dt_solicitudes.codtipdoc ".
				"	 AND cxp_rd.cod_pro=cxp_dt_solicitudes.cod_pro ".
				"	 AND cxp_rd.ced_bene=cxp_dt_solicitudes.ced_bene ".
				" WHERE cxp_rd.codemp='".$this->ls_codemp."' ".
				"   ".$ls_criterio." ".
				" GROUP BY cxp_rd.codemp,cxp_rd.cod_pro,cxp_rd.ced_bene,cxp_rd.numrecdoc, cxp_solicitudes.numsol,cxp_rd.fecregdoc".
				" ORDER BY ".$ls_orden."";
		$this->rs_data=$this->io_sql->select($ls_sql);
		if($this->rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_ubicacion_recepciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			print $this->io_sql->message;
			//$lb_valido=false;
		}
		else
		{
			if($this->rs_data->RecordCount()==0)
			{
				$lb_valido=false;
			}
		}		
		return $lb_valido;
	}// end function uf_select_ubicacion_recepciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------------	
	function uf_sep_select_usuario($as_codusu,&$as_nomusu)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sep_select_usuario
		//		   Access: private 
		//	    Arguments: as_codemp // codigo de la empresa
		//	   			   as_codusu // codigo del articulo
		//                 as_nomusu // codigo unidad de medida (referencia)
		//    Description: Function que devuelve el codigo de la unidad de medida que tiene asociada el articulo
		//	   Creado Por: Ing. Yozelin Barragan.
		// Fecha Creación: 10/04/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		 $lb_valido=false;
		 $ls_sql ="SELECT nomusu,apeusu ".
				  "  FROM sss_usuarios ".
				  " WHERE codemp='".$this->ls_codemp."'".
				  "   AND codusu='".$as_codusu."' ";
		 $rs=$this->io_sql->select($ls_sql);
		 if ($rs===false)
		 {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_sep_select_usuario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		 }		
		 else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$as_nomusu=$row["nomusu"]." ".$row["apeusu"];     
				$lb_valido=true;
			 }	
		 } 
		 return $lb_valido;    
	}//fin 	uf_sep_select_unidad_medida
    //---------------------------------------------------------------------------------------------------------------------------------	

    //---------------------------------------------------------------------------------------------------------------------------------	
	function uf_load_beneficiario_alterno($as_numsol)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_beneficiario_alterno	
		//	    Arguments: $as_codban = Código del Banco.
		//                 $as_ctaban = Cuenta Bancaria.
		//                 $as_numdoc = Número del Documento.
		//                 $as_codope = Código de la Operación.
		//                 $as_estmov = Estatus del Movimiento Bancario.
		//    Description: Metodo que se encarga de retornar el nombre del Beneficiario alterno para aquellas sep
		//                 que manejen la ayuda económica.
		//     Creado por: Ing. Néstor Falcón.
		// Fecha Creación: 26/08/2008
		///////////////////////////////////////////////////////////////////////////////////////////////
		$ls_nombenalt = "";
		$ls_sql="SELECT DISTINCT LTRIM(sep_solicitud.nombenalt) as nombenalt".
				"  FROM sep_solicitud, sep_tiposolicitud, cxp_solicitudes, cxp_dt_solicitudes, cxp_rd, cxp_rd_spg".
				" WHERE cxp_solicitudes.codemp = '".$this->ls_codemp."'".
				"   AND cxp_solicitudes.numsol = '".$as_numsol."'".
				"   AND cxp_rd_spg.procede_doc = 'SEPSPC'".
				"   AND sep_tiposolicitud.estope = 'O'".
				"   AND sep_tiposolicitud.modsep = 'O'".
				"   AND sep_tiposolicitud.estayueco = 'A'".
				"   AND sep_solicitud.codtipsol=sep_tiposolicitud.codtipsol ".
				"   AND cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp".
				"   AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol".
				"   AND cxp_dt_solicitudes.codemp=cxp_rd.codemp".
				"   AND cxp_dt_solicitudes.numrecdoc=cxp_rd.numrecdoc".
				"   AND cxp_dt_solicitudes.codtipdoc=cxp_rd.codtipdoc".
				"   AND cxp_dt_solicitudes.ced_bene=cxp_rd.ced_bene".
				"   AND cxp_dt_solicitudes.cod_pro=cxp_rd.cod_pro".
				"   AND cxp_rd_spg.codemp=cxp_rd.codemp".
				"   AND cxp_rd_spg.numrecdoc=cxp_rd.numrecdoc".
				"   AND cxp_rd_spg.codtipdoc=cxp_rd.codtipdoc".
				"   AND cxp_rd_spg.ced_bene=cxp_rd.ced_bene".
				"   AND cxp_rd_spg.cod_pro=cxp_rd.cod_pro".
				"   AND cxp_rd_spg.codemp=sep_solicitud.codemp".
				"   AND cxp_rd_spg.ced_bene=sep_solicitud.ced_bene".
				"   AND cxp_rd_spg.cod_pro=sep_solicitud.cod_pro".
				"   AND cxp_rd_spg.numdoccom=sep_solicitud.numsol".
				"   AND cxp_rd.codemp=sep_solicitud.codemp".
				"   AND cxp_rd.ced_bene=sep_solicitud.ced_bene".
				"   AND cxp_rd.cod_pro=sep_solicitud.cod_pro";
		$rs_data = $this->io_sql->select($ls_sql);//echo $ls_sql.'<br>';
		if ($rs_data===false)
		{
			$lb_valido = false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_sep_select_usuario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			echo $this->io_sql->message;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_nombenalt = $row["nombenalt"];
			}
		}
		return $ls_nombenalt;
	}//End function uf_load_beneficiario_alterno.
    //---------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_declaracionxml($as_mesdes,$as_meshas,$as_year,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_declaracionxml
		//         Access: public  
		//	    Arguments: as_quincena // Quincena del cual se van a generar los txt
		//	    		   as_mes      // Mes del cual se van a generar los txt
		//	    		   as_anio     // Año del cual se van a generar los txt
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que genera los txt de la declaración informativa
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 15/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $ls_rifemp=$_SESSION["la_empresa"]["rifemp"];
		$ls_mesdesaux=intval($as_mesdes);
		$ls_meshasaux=intval($as_meshas);
		for($li_i=$ls_mesdesaux;$li_i<=$ls_meshasaux;$li_i++)
		{
			$ls_periodo=str_pad($li_i,2,"0",0);  
			$ld_fechadesde=$as_year."-".$ls_periodo."-01";
			$ld_fechahasta=$as_year."-".$ls_periodo."-".substr($this->io_fecha->uf_last_day($ls_periodo,$as_year),0,2);
			$ls_ruta="declaracion";
			@mkdir($ls_ruta,0755);
			$ls_archivo="declaracion/Declaracion_Salarios_y_otras_R_".$ls_periodo."-".date("Y_m_d_H_i").".xml";
			$ls_archivo2="declaracion/ERROR_Declaracion_Salarios_y_otras_R_".$ls_periodo."-".date("Y_m_d_H_i").".txt";
			$lo_archivo=fopen("$ls_archivo","a+");	
			$lo_archivo2=fopen("$ls_archivo2","a+");	
			$rs_datac=$this->uf_declaracion_xml_cabecera($ld_fechadesde,$ld_fechahasta,$ls_periodo,$as_year);
			$ls_contenido='<?xml version="1.0" encoding="utf-8"?>'; 
			$ls_contenido.='<RelacionRetencionesISLR RifAgente="'.$ls_rifemp.'" Periodo="'.$as_year.$ls_periodo.'">'; 
			$ls_cadena="";			
			while(!$rs_datac->EOF)
			{
				$ls_rifpro=trim($rs_datac->fields["rifpro"]);
				$ls_rifben=trim($rs_datac->fields["rifben"]);
				if($ls_rifpro!="")
				{
					$ls_rif=$ls_rifpro;
				}
				else
				{
					$ls_rif=$ls_rifben;
				}
				$ls_numrecdoc=trim($rs_datac->fields["numrecdoc"]);
				$ls_numrecdoc=substr($ls_numrecdoc,-10);
				$ls_numref=trim($rs_datac->fields["numref"]);
				if($ls_numref=="")
				{
					$ls_numref="NA";
				}
				$li_baseimp=number_format($rs_datac->fields["baseimp"],2,'.','');
				$ls_codconret=trim($rs_datac->fields["codconret"]);
				$ls_codper=trim($rs_datac->fields["codper"]);
				$li_porded=number_format($rs_datac->fields["porded"],2,'.','');
				$ls_procedencia=trim($rs_datac->fields["procedencia"]);
				$correcto=true;
				if ($ls_procedencia=='CXP')
				{
					if ((trim($ls_rif)==""))
					{
						$ls_cadena=$ls_cadena."La factura ".$ls_numrecdoc." no se pudo agregar ya que el proveedor/beneficiario asociado no posee rif. \r\n";
						$correcto=false;
					}
					if ((trim($ls_codconret)==""))
					{
						$ls_cadena=$ls_cadena."La factura ".$ls_numrecdoc." no se pudo agregar ya que la deducción no posee Concepto de Retención asociado. \r\n";
						$correcto=false;
					}
				}
				if ($ls_procedencia=='SNO')
				{
					if ((trim($ls_rif)==""))
					{
						$ls_cadena=$ls_cadena."El personal  ".$ls_codper." no se pudo agregar ya que no posee rif. \r\n";
						$correcto=false;
					}
					if ((trim($ls_codconret)==""))
					{
						$ls_cadena=$ls_cadena."El personal  ".$ls_codper." no se pudo agregar ya que la deducción no posee Concepto de Retención asociado. \r\n";
						$correcto=false;
					}
					if (($li_porded==0))
					{
						$ls_cadena=$ls_cadena."El personal  ".$ls_codper." no se pudo agregar ya que el porcentaje de deducción es cero. \r\n";
						$correcto=false;
					}
				}
				if($correcto)
				{
					$ls_contenido.='<DetalleRetencion>';
					$ls_contenido.='<RifRetenido>'.$ls_rif.'</RifRetenido>';
					$ls_contenido.='<NumeroFactura>'.$ls_numrecdoc.'</NumeroFactura>';
					$ls_contenido.='<NumeroControl>'.$ls_numref.'</NumeroControl>';
					$ls_contenido.='<CodigoConcepto>'.$ls_codconret.'</CodigoConcepto>';
					$ls_contenido.='<MontoOperacion>'.$li_baseimp.'</MontoOperacion>';
					$ls_contenido.='<PorcentajeRetencion>'.$li_porded.'</PorcentajeRetencion>';
					$ls_contenido.='</DetalleRetencion>';
				}
				$rs_datac->MoveNext();
			}
			$ls_contenido.='</RelacionRetencionesISLR>';
			@fwrite($lo_archivo,$ls_contenido);
			@fwrite($lo_archivo2,$ls_cadena);
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Genero el xml de Declaración de sueldos y otras remuneraciones para el periodo ".
								 $as_mesdes." a ".$as_meshas." del año ".$as_year." en el Archivo ".$ls_archivo.
								 " Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////					
			}
		}
		return $lb_valido;
	}// end function uf_declaracioninformativa
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_declaracion_xml_cabecera($as_fecemidocdes,$as_fecemidochas,$as_periodo,$as_year)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_declaracion_xml_cabecera
		//         Access: public 
		//      Argumento: as_fecemidocdes // Parametro de busqueda Fecha Desde
		//				   as_fecemidochas // Parametro de busqueda Fecha Hasta
		//	      Returns: Retorna un Datastored
		//    Description: Funcion que obtiene los datos para la declaracion de salarios y otras remuneraciones
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 05/06/2009									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_criterio="";
		$rs_data="";
		if($as_fecemidocdes!="")
		{
			$ls_criterio=$ls_criterio." AND cxp_rd.fecemidoc>='".$as_fecemidocdes."'";
		}
		if($as_fecemidochas!="")
		{
			$ls_criterio=$ls_criterio." AND cxp_rd.fecemidoc<='".$as_fecemidochas."'";
		}
		$ls_sql="SELECT '' AS codper, rpc_proveedor.rifpro, rpc_beneficiario.rifben,cxp_rd.numrecdoc, cxp_rd.numref,".
				" 		(cxp_rd.montotdoc-cxp_rd.mondeddoc+cxp_rd.moncardoc) as baseimp,".
				"       sigesp_deducciones.codconret ,sigesp_deducciones.porded,sigesp_deducciones.codded, 'CXP' AS procedencia".
				"  FROM cxp_rd, cxp_rd_deducciones, sigesp_deducciones, rpc_proveedor, rpc_beneficiario ".
				" WHERE cxp_rd.codemp = '".$this->ls_codemp."' ".
				"   AND sigesp_deducciones.islr = 1 ".
				"   AND cxp_rd.estprodoc='C'".
				$ls_criterio.
				"   AND cxp_rd.codemp = cxp_rd_deducciones.codemp ".
				"   AND cxp_rd.numrecdoc = cxp_rd_deducciones.numrecdoc ".
				"   AND cxp_rd.codtipdoc = cxp_rd_deducciones.codtipdoc ".
				"   AND cxp_rd.ced_bene = cxp_rd_deducciones.ced_bene ".
				"   AND cxp_rd.cod_pro = cxp_rd_deducciones.cod_pro ".
				"   AND cxp_rd_deducciones.codemp = sigesp_deducciones.codemp ".
				"   AND cxp_rd_deducciones.codded = sigesp_deducciones.codded ".
				"   AND cxp_rd.codemp = rpc_proveedor.codemp ".
				"   AND cxp_rd.cod_pro = rpc_proveedor.cod_pro ".
				"   AND cxp_rd.codemp = rpc_beneficiario.codemp ".
				"   AND cxp_rd.ced_bene = rpc_beneficiario.ced_bene ".
				" UNION ".
				"SELECT sno_personal.codper, MAX(sno_personal.rifper) AS rifpro,'' AS rifben,'0' AS numrecdoc,'' AS numref, SUM(sno_hsalida.valsal), ".
				"	   MAX(sno_personalisr.codconret) AS codconret, MAX(sno_personalisr.porisr) AS porded, sno_personalisr.codisr AS codded, 'SNO' AS procedencia ".
				"  FROM sno_hsalida, sno_personalisr, sno_personal, sno_hperiodo,sno_hconcepto ".
				" WHERE sno_hsalida.codemp = '".$this->ls_codemp."' ".
				"   AND SUBSTR(sno_hperiodo.fecdesper,1,4) = '".$as_year."' ".
				"   AND SUBSTR(sno_hperiodo.fecdesper,6,2) = '".$as_periodo."' ".
				"   AND sno_personalisr.codisr = '".$as_periodo."'  ".
				"   AND sno_hconcepto.aplarccon = 1  ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp  ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur  ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi  ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom  ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc  ".
				"   AND sno_hsalida.codemp = sno_hperiodo.codemp  ".
				"   AND sno_hsalida.anocur = sno_hperiodo.anocur  ".
				"   AND sno_hsalida.codperi = sno_hperiodo.codperi  ".
				"   AND sno_hsalida.codnom = sno_hperiodo.codnom  ".
				"   AND sno_hsalida.codemp = sno_personalisr.codemp  ".
				"   AND sno_hsalida.codper = sno_personalisr.codper  ".
				"   AND sno_personal.codemp = sno_personalisr.codemp  ".
				"   AND sno_personal.codper = sno_personalisr.codper  ".
				" GROUP BY sno_personal.codper, sno_personalisr.codisr ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_declaracion_xml_cabecera ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		return $rs_data;		
	}// end function uf_arc_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------
		function uf_select_sol_cargos($as_numero)
	{
     //////////////////////////////////////////////////////////////////////////////
     //	Funcion      uf_select_sol_cargos
     //	Access       public
     //	Arguments    $as_codemp,$as_numero
     //	Returns	     $rs (Resulset)
     //	Description  Devuelve un resulset para cargar los cargos asociados a una
     //              Solicitud.
     //////////////////////////////////////////////////////////////////////////////
     $this->ds_car_dt = new class_datastore();
	 
	 $ls_sql=" SELECT cxp_dt_solicitudes.numsol as numsol, cxp_rd_cargos.codcar as codcar, cxp_rd_cargos.monobjret, 
                      cxp_rd_cargos.monret,TRIM(spg_ep3.denestpro3) as denestpro3
			   FROM   cxp_dt_solicitudes ,  cxp_rd_cargos , spg_ep3
               WHERE  cxp_dt_solicitudes.numsol   = '".$as_numero."' 
				 AND  cxp_dt_solicitudes.codemp   = cxp_rd_cargos.codemp          
				 AND  cxp_dt_solicitudes.numrecdoc= cxp_rd_cargos.numrecdoc    
				 AND  cxp_dt_solicitudes.cod_pro  = cxp_rd_cargos.cod_pro        
				 AND  cxp_dt_solicitudes.ced_bene = cxp_rd_cargos.ced_bene      
                 AND  cxp_rd_cargos.codemp=spg_ep3.codemp
				 AND  cxp_rd_cargos.codestpro1=spg_ep3.codestpro1
				 AND  cxp_rd_cargos.codestpro2=spg_ep3.codestpro2
				 AND  cxp_rd_cargos.codestpro3=spg_ep3.codestpro3";  
	 $this->ds_car_dt->resetds("numsol");			
	 $rs=$this->io_sql->select($ls_sql);
	 if ($rs===false)
		 {		 
			 $this->io_mensajes->message("CLASE->SIGESP_CXP_CLASS_REPORT; METODO->uf_select_sol_cargos; ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			 $lb_valido=false;
		 }
         else
         {
		     if ($row=$this->io_sql->fetch_row($rs))
		     {
				  $lb_valido=true;				
				  $datos=$this->io_sql->obtener_datos($rs);
				  $this->ds_car_dt->data=$datos;			  				 
		    	  $this->io_sql->free_result($rs);  	
			 }
             else
             {   
                 $lb_valido=false;			     
             }	
         }
    return $lb_valido;		
	}
	
	function uf_select_sol_deducciones($as_numero)
	{
     //////////////////////////////////////////////////////////////////////////////
     //	Funcion      uf_select_sol_deducciones
     //	Access       public
     //	Arguments    $as_codemp,$as_numero
     //	Returns	     $rs (Resulset)
     //	Description  Devuelve un resulset para cargar la deducciones asociados a una
     //              Solicitud.
     //////////////////////////////////////////////////////////////////////////////
     $this->ds_ded_dt = new class_datastore();
	 
	 $ls_sql=" SELECT cxp_dt_solicitudes.numsol as numsol,cxp_rd_deducciones.codded as codded,
					  SUM(cxp_rd_deducciones.monobjret) as monobjretded, SUM(cxp_rd_deducciones.monret) as objretded
               FROM   cxp_dt_solicitudes , cxp_rd_deducciones 
               WHERE  cxp_dt_solicitudes.numsol   = '".$as_numero."' 
			     AND  cxp_dt_solicitudes.codemp   = cxp_rd_deducciones.codemp          
			     AND  cxp_dt_solicitudes.numrecdoc= cxp_rd_deducciones.numrecdoc    
			     AND  cxp_dt_solicitudes.cod_pro  = cxp_rd_deducciones.cod_pro        
				 AND  cxp_dt_solicitudes.ced_bene = cxp_rd_deducciones.ced_bene      
               GROUP  by cxp_dt_solicitudes.numsol,cxp_rd_deducciones.codded";    
	 //print $ls_sql."<br>";	
	 $this->ds_ded_dt->resetds("numsol");			
	 $rs=$this->io_sql->select($ls_sql);
	 if ($rs===false)
		 {		 
			 $this->io_mensajes->message("Error en Sentencia->uf_select_sol_deducciones");
			 $lb_valido=false;
		 }
         else
         {
		     if ($row=$this->io_sql->fetch_row($rs))
		     {
				  $lb_valido=true;				
				  $datos=$this->io_sql->obtener_datos($rs);
				  $this->ds_ded_dt->data=$datos;			  				 
		    	  $this->io_sql->free_result($rs);  	
			 }
             else
             {   
                 $lb_valido=false;			     
             }	
         }
    return $lb_valido;		
	}
	
    function uf_select_dencar($as_codcar)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Funcion      uf_select_dencar
	 //	Access       public
	 //	Arguments    $as_codemp,$as_codcar
	 //	Returns      $rs (Resulset)	
	 //	Description  Variable string con la denominacion del cargo
	 //////////////////////////////////////////////////////////////////////////////
	    $ls_dencar="";
		 
		$ls_sql=" SELECT dencar ".
				" FROM   sigesp_cargos  ".
		        " WHERE  codcar='".$as_codcar."'";     
				 		 
		$rs=$this->io_sql->select($ls_sql);
		if ($rs===false)
		 {
			$lb_valido=false;
			$ls_dencar="";
			$this->io_msg->message('Error en Consulta SQL !!!'); 
			$this->io_msg->message($this->io_funciones->uf_convertirmsg($this->io_sql->message));
		 }		
		else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$ls_dencar=$row["dencar"];              				
			 }	
		 } 
	return $ls_dencar;
	}
	
	function uf_select_dended($as_codded)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Funcion      uf_select_dended
	 //	Access       public
	 //	Arguments    $as_codemp,$as_codcar
	 //	Returns      $rs (Resulset)	
	 //	Description  Variable string con la denominacion de la deducción
	 //////////////////////////////////////////////////////////////////////////////
	    $ls_dended="";
		 
		$ls_sql=" SELECT dended ".
				" FROM   sigesp_deducciones  ".
		        " WHERE  codded='".$as_codded."'";     
				 		 
		$rs=$this->io_sql->select($ls_sql);
		if ($rs===false)
		 {
			$lb_valido=false;
			$ls_dended="";
			$this->io_msg->message('Error en Consulta SQL !!!'); 
			$this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }		
		else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$ls_dended=$row["dended"];              				
			 }	
		 } 
	return $ls_dended;
	}
	
	function uf_load_comprobantes_retencion_muni_1xmil($as_codemp,$as_numsol)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	          Método:  uf_load_comprobantes_retencion
	//	          Access:  public
	//          Arguments  
	//        $as_codemp:  Código de la Empresa.  
	//        $as_numsol:  Número de la Solicitud de Pago al cual le buscaremos las retenciones asociadas al mismo.
	//           Returns:  $lb_valido=Variable booleana que devuelve true si la sentencia sql fue ejecutada
	//                     sin problemas y el resulset obtuvo registros de la consulta.           
	//       Description:  Función que se encarga de extraer todas aquellas solicitudes de pago que esten en estatus contabilizada, 
	//                     hasta la fecha que viene como parametro.                   
	//     Elaborado Por:  Ing. Néstor Falcón.
	// Fecha de Creación:  17/04/2006        Fecha Última Actualización:17/04/2006.	 
	//////////////////////////////////////////////////////////////////////////////
	
	   $ls_gestor = "POSTGRE";
	   switch ($ls_gestor){
		case 'MYSQL':
		   $ls_cadena=" CONCAT(RTRIM(XBF.apebene),', ',XBF.nombene) ";
		   break;
		case 'ORACLE':
		   $ls_cadena=" RTRIM(XBF.apebene)||', '||XBF.nombene ";
		   break;
		case 'POSTGRE':
		   $ls_cadena=" RTRIM(XBF.apebene)||', '||XBF.nombene ";
		   break;	    
		case 'ANYWHERE':
		   $ls_cadena=" rtrim(XBF.apebene)+', '+XBF.nombene ";
		   break;
	   }
	
	   $this->ds_retenciones = new class_datastore();
	   $ls_sql=" SELECT                                                                                                     ".
			   "    XSP.*, 																									".
			   "    XDS.codtipdoc, XDS.numrecdoc AS numdoc,XMC.dended, XMC.formula,XDC.codded,XRD.numref,XRD.numrecdoc,XRD.fecemidoc, ".
			   "    XRD.dencondoc,XRD.montotdoc,COALESCE(XDC.monobjret, XSP.monsol) AS monobjret, XMC.porded AS porcentaje,               ".
			   "    XDC.monret AS retenido,PRV.nompro AS proveedor, PRV.rifpro, PRV.telpro  ,                               ".
			   "    PRV.dirpro,PRV.nitpro AS nit,$ls_cadena AS beneficiario,                                                ".
			   "    XBF.ced_bene AS cedula,XBF.dirbene,XBF.rifben,XBF.telbene                      ".
			   " FROM                                                                                                       ".
			   "   cxp_solicitudes XSP,cxp_dt_solicitudes XDS,cxp_rd XRD, cxp_rd_deducciones XDC,sigesp_deducciones XMC,    ".
			   "   rpc_beneficiario XBF,rpc_proveedor PRV                                                                   ".
			   " WHERE                                                                                                      ".
			   "  (XSP.numsol=XDS.numsol        AND  XSP.cod_pro=XDS.cod_pro      AND  XSP.ced_bene=XDS.ced_bene)    AND    ".
			   "   XDC.codded=XMC.codded        AND  (XDS.cod_pro=XRD.cod_pro     AND  XDS.ced_bene=XRD.ced_bene     AND    ".
			   "   XDS.codtipdoc=XRD.codtipdoc  AND  XDS.numrecdoc=XRD.numrecdoc) AND  (XRD.cod_pro=XDC.cod_pro      AND    ".
			   "   XRD.ced_bene=XDC.ced_bene    AND  XRD.codtipdoc=XDC.codtipdoc  AND  XRD.numrecdoc=XDC.numrecdoc)  AND    ".
			   "  (XBF.ced_bene=XSP.ced_bene)   AND	 (PRV.cod_pro=XSP.cod_pro)    AND  XSP.codemp=XDS.codemp         AND    ".
			   "   XRD.codemp=XDC.codemp        AND  XMC.codemp=XDC.codemp        AND  XMC.islr=0                    AND    ".
			   "   XMC.iva=0                    AND  XMC.estretmun=1              AND  XSP.codemp='".$as_codemp."'   AND  
				   XSP.numsol='".$as_numsol."'                                                                              ".
			   " ORDER BY XSP.numsol";
	   $rs_data=$this->io_sql->select($ls_sql);
	   if ($rs_data===false)
		  {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->SIGESP_CXP_RC_CMPRETENCION; METODO->uf_load_comprobantes_retencion_cxp; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  }
	   else
		  {
			$li_numrows=$this->io_sql->num_rows($rs_data);
			if ($li_numrows>0)
			   {
				 $datos=$this->io_sql->obtener_datos($rs_data);
				 $this->ds_retenciones->data=$datos;
				 $lb_valido=true;
				 $this->io_sql->free_result($rs_data);
			   }
			else
			   {
				 $lb_valido=false; 
			   } 
		 }		
	return $lb_valido;
	}
	
	function uf_seek_cheque($as_codemp,$as_numsol)
    {
	 //////////////////////////////////////////////////////////////////////////////
	 //	Funcion      uf_seek_cheque
	 //	Access       public
	 //	Arguments    $as_codemp,$as_numsol
	 //	Returns      $ls_numche	
	 //	Description  Variable que devuelve el numero del cheque asociado a la orden
	 //              de Pago
	 //////////////////////////////////////////////////////////////////////////////
	     $ls_numche = "";
		 $ls_sql=" SELECT  numdoc  ".
				 " FROM    cxp_sol_banco ".
				 " WHERE   codemp   = '".$as_codemp."' 
					 AND   numsol   = '".$as_numsol."' ";    
		$rs=$this->io_sql->select($ls_sql);
		if ($rs===false)
		 {
			$lb_valido=false;
			$ls_numche= "";
			$this->io_mensajes->message('Error en Consulta SQL !!!'); 
			$this->io_mensajes->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }		
		else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				 $ls_numche = $row["numdoc"];                      
			 }	
		 } 
	return $ls_numche;
	}
	
	function uf_select_rpp_ret_iva($as_codemp,$as_numcom,$as_feccomdes,$as_feccomhas,$as_perfiscal,$as_codsujret,
	                               $as_nomsujret,$as_rif,$as_numfac,$as_desope,$as_numsop,$as_numdoc,$as_fecfac,
								   $as_codban,$as_cuenta)
	{
     //////////////////////////////////////////////////////////////////////////////    
     //	Funcion       uf_select_ret_iva_det
     //	Access        public
     //	Arguments     $as_codemp,$as_numcom
     //	Returns 	  $lb_valido;
     //	Description   Funcion que busca los detalles de una Ret. de Iva para el imprimirla    
     //////////////////////////////////////////////////////////////////////////////        
	 $this->ds_rpp_ret = new class_datastore();
	 $ls_sql_perfiscal = "";
	 $ls_sql_banco     = "";
	 
	 $ld_periodo = $_SESSION["la_empresa"]["periodo"];	
	 $ld_aoo     = substr($ld_periodo,0,4);

	 if(empty($as_feccomdes))
	 {
	    $as_feccomdes="01-01-".$ld_aoo;
	 }
	 
	 if(empty($as_feccomhas))
	 {
   	    $as_feccomhas="31-12-".$ld_aoo;
	 }
	 
	 if(empty($as_fecfac))
	 {
	    $as_fecfac="01-01-".$ld_aoo;
	 }
	 
	 $ls_feccomdes = $this->io_funciones->uf_convertirdatetobd($as_feccomdes);
	 $ls_feccomhas = $this->io_funciones->uf_convertirdatetobd($as_feccomhas);
     $ls_fecfac    = $this->io_funciones->uf_convertirdatetobd($as_fecfac);
	 if(!empty($as_perfiscal))
	 {
		$ls_sql_perfiscal="  AND scb_cmp_ret.perfiscal='".$as_perfiscal."'                 ";									
     }
 	 if(  (!empty($as_codban)) && (!empty($as_cuenta))  && (!empty($as_numdoc))  )
	 {
		$ls_sql_banco="  AND scb_dt_cmp_ret.numsop IN (SELECT  numsol 
						 	                           FROM  cxp_sol_banco
												      WHERE  codope='CH' 
													    AND  estmov<>'A' 
													    AND  codban like '%".$as_codban."%' 
													    AND  ctaban like '%".$as_cuenta."%' 
													    AND  numdoc like '%".$as_numdoc."%'
													    AND codope='CH')                    ";									
     }

	 $ls_sql=  "SELECT scb_cmp_ret.numcom, scb_cmp_ret.fecrep, scb_cmp_ret.perfiscal,       ".
			   "	   scb_cmp_ret.codsujret, scb_cmp_ret.nomsujret,                        ".
			   "	   scb_cmp_ret.rif, scb_dt_cmp_ret.fecfac, scb_dt_cmp_ret.numfac,       ".
			   "	   scb_dt_cmp_ret.desope, scb_dt_cmp_ret.numsop, scb_dt_cmp_ret.numdoc  ".
			   "FROM   scb_cmp_ret, scb_dt_cmp_ret                                          ".
			   "WHERE  scb_cmp_ret.codemp= scb_dt_cmp_ret.codemp                            ".
			   "AND    scb_cmp_ret.codret= scb_dt_cmp_ret.codret                            ".
			   "AND    scb_cmp_ret.numcom= scb_dt_cmp_ret.numcom                            ".
			   "AND    scb_cmp_ret.codemp='".$as_codemp."'                                  ".
			   "AND    scb_cmp_ret.codret='0000000001'                                      ".
			   "AND    scb_cmp_ret.numcom like '%".$as_numcom."%'                           ".
			   "AND    scb_cmp_ret.fecrep>='".$ls_feccomdes."'                              ".
			   "AND    scb_cmp_ret.fecrep<='".$ls_feccomhas."'                              ".
			   "AND    scb_cmp_ret.codsujret like '%".$as_codsujret."%'                     ".
			   "AND    scb_cmp_ret.nomsujret like '%".$as_nomsujret."%'                     ".
			   "AND    scb_cmp_ret.rif like '%".$as_rif."%'                                 ".
			   "AND    scb_dt_cmp_ret.numfac like '%".$as_numfac."%'                        ".
			   "AND    scb_dt_cmp_ret.desope like '%".$as_desope."%'                        ".
			   "AND    scb_dt_cmp_ret.numsop like '%".$as_numsop."%'                 	    ".						
			   "AND    scb_dt_cmp_ret.fecfac >='".$ls_fecfac."'                             ".
			   $ls_sql_perfiscal.$ls_sql_banco.
			   "ORDER BY scb_cmp_ret.numcom";									
	//print $ls_sql."<br><br><br>";		
	$rs=$this->io_sql->select($ls_sql);
	if ($rs===false)
	{
			$this->io_msg->message("CLASE->sigesp_cxp_class_report; METODO->uf_select_rpp_ret_iva; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			print ($this->io_sql->message);
	}
	else
	{
			if ($row=$this->io_sql->fetch_row($rs))
			{
				  $lb_valido=true;				
				  $datos=$this->io_sql->obtener_datos($rs);
				  $this->ds_rpp_ret->data=$datos;
				  $this->io_sql->free_result($rs);
			}
			else
			{   
				  $lb_valido=false;			      
			}		   
	}
    return $lb_valido;		   	
    }


    function uf_buscar_cheque($as_codemp,$as_numsol,$as_codban,$as_cuenta)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Funcion      uf_buscar_cheque
	 //	Access       public
	 //	Arguments    as_numsol
	 //	Description  Metodo que devuelve el numero de cheque asociado a una Orden de Pago
	 //////////////////////////////////////////////////////////////////////////////
		$ls_numdoc="";
		$ls_sql   =" SELECT  numdoc 
					   FROM  cxp_sol_banco 
					  WHERE  codemp='".$as_codemp."' 
					    AND  numsol='".$as_numsol."' 
					    AND  estmov<>'A' 
					    AND  codope='CH'    
						AND  codban like  '%".$as_codban."%' 
						AND  ctaban like  '%".$as_cuenta."%'   ";       
		$rs_data = $this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			   $lb_valido=false;
			   $this->io_msg->message("CLASE->SIGESP_CXP_CLASS_REPORT; METODO->uf_buscar_cheque; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));

		}		
		else
		{
				if ($row=$this->io_sql->fetch_row($rs_data))
				{ 		   
				  $ls_numdoc=$row["numdoc"];              
				}	
		}  
		return $ls_numdoc;
	}
}
?>
