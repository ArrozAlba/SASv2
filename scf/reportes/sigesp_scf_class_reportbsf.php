<?php
class sigesp_scf_class_reportbsf
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_scf_class_reportbsf()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_scf_class_reportbsf
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 13/08/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$this->io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($this->io_conexion);	
		$this->DS=new class_datastore();
		$this->DS_detalle=new class_datastore();
		$this->DS_detalle2=new class_datastore();
		require_once("../../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_scf_class_reportbsf
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_mayoranalitico($ad_fecdesde,$ad_fechasta,$as_cuentadesde,$as_cuentahasta,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_mayoranalitico
		//         Access: public (desde la clase sigesp_sno_rpp_mayor_analitico  
		//	    Arguments: ad_fecdesde // Fecha donde se empieza a filtrar
		//	  			   ad_fechasta // Fecha donde se termina de filtrar		  
		//	  			   as_cuentadesde // Cuenta Contable donde se termina de filtrar		  
		//	  			   as_cuentahasta // cuenta Contable donde se termina de filtrar		  
		//	  			   as_orden // Orde a mostrar en el reporte		  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los detalles contables
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 13/08/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$ad_fecdesde=$this->io_funciones->uf_convertirdatetobd($ad_fecdesde);
		$ad_fechasta=$this->io_funciones->uf_convertirdatetobd($ad_fechasta);
		if(!empty($as_cuentadesde))
		{
			$ls_criterio= "AND scg_dt_cmp.sc_cuenta>='".$as_cuentadesde."'";
		}
		if(!empty($as_cuentahasta))
		{
			$ls_criterio= $ls_criterio."   AND scg_dt_cmp.sc_cuenta<='".$as_cuentahasta."'";
		}
		switch($as_orden)
		{
			case "0": // Ordena por comprobante
				$ls_orden="ORDER BY scg_dt_cmp.comprobante ";
				break;

			case "1": // Ordena por Fecha
				$ls_orden="ORDER BY scg_dt_cmp.fecha ";
				break;

			case "2": // Ordena por cuenta contable
				$ls_orden="ORDER BY scg_dt_cmp.sc_cuenta ";
				break;
		}
	   $ls_sql = " SELECT scg_dt_cmp.sc_cuenta, scg_dt_cmp.procede, scg_dt_cmp.comprobante,scg_dt_cmp.procede_doc, ".
				 "        scg_dt_cmp.documento,scg_dt_cmp.fecha, scg_dt_cmp.debhab,scg_dt_cmp.descripcion, (scg_dt_cmp.montoaux) AS monto, ".
				 "        scg_dt_cmp.orden,scg_cuentas.denominacion, sigesp_cmp.descripcion as des_comp, ".
				 "		  sigesp_cmp.cod_pro, sigesp_cmp.ced_bene, ".
				 "        (SELECT nompro FROM rpc_proveedor ".
				 "			WHERE rpc_proveedor.codemp = sigesp_cmp.codemp ".
				 "			  AND rpc_proveedor.cod_pro = sigesp_cmp.cod_pro ) AS nompro, ".
				 "        (SELECT nombene FROM rpc_beneficiario ".
				 "			WHERE rpc_beneficiario.codemp = sigesp_cmp.codemp ".
				 "			  AND rpc_beneficiario.ced_bene = sigesp_cmp.ced_bene ) AS nombene, ".
				 "        (SELECT apebene FROM rpc_beneficiario ".
				 "			WHERE rpc_beneficiario.codemp = sigesp_cmp.codemp ".
				 "			  AND rpc_beneficiario.ced_bene = sigesp_cmp.ced_bene ) AS apebene, ".				 
				 "        (SELECT COALESCE(SUM(debe_mesaux-haber_mesaux),0) ".
				 "           FROM scg_saldos ".
				 "          WHERE scg_saldos.fecsal<'".$ad_fecdesde."' ".
				 "            AND scg_dt_cmp.codemp=scg_saldos.codemp ".
				 "            AND scg_dt_cmp.sc_cuenta=scg_saldos.sc_cuenta) As saldo_ant ".
				 "   FROM scg_dt_cmp, sigesp_cmp, scg_cuentas ".
				 "  WHERE scg_dt_cmp.codemp = '".$this->ls_codemp."' ".
				 "    AND scg_dt_cmp.fecha>= '".$ad_fecdesde."' ".
				 "	  AND scg_dt_cmp.fecha<= '".$ad_fechasta."' ".
				 $ls_criterio.
				 "    AND scg_dt_cmp.codemp=scg_cuentas.codemp ".
				 "    AND scg_dt_cmp.sc_cuenta=scg_cuentas.sc_cuenta ".
				 "    AND sigesp_cmp.codemp=scg_dt_cmp.codemp ".
				 "    AND sigesp_cmp.procede=scg_dt_cmp.procede ".
				 "    AND sigesp_cmp.comprobante=scg_dt_cmp.comprobante ".
				 "    AND sigesp_cmp.fecha=scg_dt_cmp.fecha ".
				 "    AND sigesp_cmp.codban=scg_dt_cmp.codban ".
				 "    AND sigesp_cmp.ctaban=scg_dt_cmp.ctaban ".
			     "  ".$ls_orden; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_mayoranalitico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_mayoranalitico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_balancecomprobacion($ad_fecdesde,$ad_fechasta,$as_cuentadesde,$as_cuentahasta,$as_nivel)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_balancecomprobacion
		//         Access: public (desde la clase sigesp_sno_rpp_balance_comprobacion
		//	    Arguments: ad_fecdesde // Fecha donde se empieza a filtrar
		//	  			   ad_fechasta // Fecha donde se termina de filtrar		  
		//	  			   as_cuentadesde // Cuenta Contable donde se termina de filtrar		  
		//	  			   as_cuentahasta // cuenta Contable donde se termina de filtrar		  
		//	  			   as_nivel // Nivel de la cuenta  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los detalles contables
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 13/08/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$ad_fecdesde=$this->io_funciones->uf_convertirdatetobd($ad_fecdesde);
		$ad_fechasta=$this->io_funciones->uf_convertirdatetobd($ad_fechasta);
		if(!empty($as_cuentadesde))
		{
			$ls_criterio= "AND scg_dt_cmp.sc_cuenta>='".$as_cuentadesde."'";
		}
		if(!empty($as_cuentahasta))
		{
			$ls_criterio= $ls_criterio."   AND scg_dt_cmp.sc_cuenta<='".$as_cuentahasta."'";
		}
		$ls_sql= "SELECT scg_cuentas.sc_cuenta, MAX(scg_cuentas.denominacion) AS denominacion, ".
				   "       COALESCE(SUM(scg_saldos.debe_mesaux),0) as debe_mes, COALESCE(SUM(scg_saldos.haber_mesaux),0) as haber_mes,  ".
				   "       COALESCE(0,0) as debe_mes_ant , COALESCE(0,0) as haber_mes_ant,  ".
				   "       COALESCE(0,0) as total_debe , COALESCE(0,0) as total_haber  ".
				   "  FROM scg_cuentas ".
				   "  LEFT OUTER JOIN scg_saldos  ".
				   "    ON scg_saldos.fecsal >= '".$ad_fecdesde."' ".
				   "   AND scg_saldos.fecsal <= '".$ad_fechasta."' ".
				   "   AND scg_cuentas.codemp = scg_saldos.codemp ".
				   "   AND scg_cuentas.sc_cuenta = scg_saldos.sc_cuenta ".				   
				   " WHERE scg_cuentas.codemp='".$this->ls_codemp."' ".
				   $ls_criterio.
				   "   AND scg_cuentas.nivel<=".$ai_nivel."".
				   " GROUP BY scg_cuentas.sc_cuenta  ".
				   "UNION ".
				   "SELECT scg_cuentas.sc_cuenta, MAX(scg_cuentas.denominacion) AS denominacion, ".
				   "       COALESCE(0,0) as debe_mes, COALESCE(0,0) as haber_mes, ".
				   "       COALESCE(SUM(scg_saldos.debe_mesaux),0) as debe_mes_ant ,COALESCE(SUM(scg_saldos.haber_mesaux),0) as haber_mes_ant, ".
				   "       COALESCE(0,0) as total_debe , COALESCE(0,0) as total_haber  ".
				   "  FROM scg_cuentas, scg_saldos ".
				   " WHERE scg_cuentas.codemp='".$this->ls_codemp."' ".
				   $ls_criterio.
				   "   AND scg_saldos.fecsal<'".$ad_fecdesde."'". 
				   "   AND scg_cuentas.nivel<=".$ai_nivel."".
				   "   AND scg_cuentas.codemp = scg_saldos.codemp ".
				   "   AND scg_cuentas.sc_cuenta = scg_saldos.sc_cuenta ".
				   " GROUP BY scg_cuentas.sc_cuenta  ".
				   "UNION ".
				   "SELECT scg_cuentas.sc_cuenta, MAX(scg_cuentas.denominacion) AS denominacion, ".
				   "       COALESCE(0,0) as debe_mes, COALESCE(0,0) as haber_mes,  ".
				   "       COALESCE(0,0) as debe_mes_ant , COALESCE(0,0) as haber_mes_ant,  ".
				   "       COALESCE(SUM(scg_saldos.debe_mesaux),0) as total_debe , COALESCE(SUM(scg_saldos.haber_mesaux),0) as total_haber  ".
				   "  FROM scg_cuentas, scg_saldos  ".
				   " WHERE scg_cuentas.codemp='".$this->ls_codemp."' ".
				   $ls_criterio.
				   "   AND scg_saldos.fecsal >= '".$ad_fecdesde."' ".
				   "   AND scg_saldos.fecsal <= '".$ad_fechasta."' ".
				   "   AND scg_cuentas.nivel=1".
				   "   AND scg_cuentas.codemp = scg_saldos.codemp ".
				   "   AND scg_cuentas.sc_cuenta = scg_saldos.sc_cuenta ".
				   " GROUP BY scg_cuentas.sc_cuenta  ".
                   " ORDER BY sc_cuenta ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_balancecomprobacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
				$this->DS->group_by(array('0'=>'sc_cuenta'),array('0'=>'debe_mes','1'=>'haber_mes','2'=>'debe_mes_ant',
																  '3'=>'haber_mes_ant','4'=>'total_debe','5'=>'total_haber'),'debe_mes');
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_balancecomprobacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_comprobante_cabecera($ad_fecdesde,$ad_fechasta,$as_comprobantedesde,$as_comprobantehasta,
									 $as_procededesde,$as_procedehasta,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_comprobante_cabecera
		//         Access: public (desde la clase sigesp_sno_rpp_balance_comprobacion
		//	    Arguments: ad_fecdesde // Fecha donde se empieza a filtrar
		//	  			   ad_fechasta // Fecha donde se termina de filtrar		  
		//	  			   as_comprobantedesde // Comprobante donde se empieza de filtrar		  
		//	  			   as_comprobantehasta // Comprobante donde se termina de filtrar		  
		//	  			   as_procededesde // Procede donde se empieza de filtrar		  
		//	  			   as_procedehasta // Procede donde se termina de filtrar		  
		//	  			   as_orden // Orden del reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los comprobantes contables
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 13/08/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($ad_fecdesde))
		{
			$ad_fecdesde=$this->io_funciones->uf_convertirdatetobd($ad_fecdesde);
			$ls_criterio= " AND sigesp_cmp.fecha>='".$ad_fecdesde."'";
		}
		if(!empty($ad_fechasta))
		{
			$ad_fechasta=$this->io_funciones->uf_convertirdatetobd($ad_fechasta);
			$ls_criterio= $ls_criterio."   AND sigesp_cmp.fecha<='".$ad_fechasta."'";
		}
		if(!empty($as_comprobantedesde))
		{
			$ls_criterio= $ls_criterio."   AND sigesp_cmp.comprobante>='".$as_comprobantedesde."'";
		}
		if(!empty($as_comprobantehasta))
		{
			$ls_criterio= $ls_criterio."   AND sigesp_cmp.comprobante<='".$as_comprobantehasta."'";
		}
		if(!empty($as_procededesde))
		{
			$ls_criterio= "AND sigesp_cmp.procede>='".$as_procededesde."'";
		}
		if(!empty($as_procedehasta))
		{
			$ls_criterio= $ls_criterio."   AND sigesp_cmp.procede<='".$as_procedehasta."'";
		}
		switch($as_orden)
		{
			case "0": // Ordena por comprobante
				$ls_orden="ORDER BY sigesp_cmp.procede, sigesp_cmp.comprobante, sigesp_cmp.fecha ";
				break;

			case "1": // Ordena por Fecha
				$ls_orden="ORDER BY sigesp_cmp.comprobante, sigesp_cmp.fecha, sigesp_cmp.procede ";
				break;

			case "2": // Ordena por cuenta contable
				$ls_orden="ORDER BY sigesp_cmp.fecha, sigesp_cmp.procede, sigesp_cmp.comprobante ";
				break;
		}
		$ls_sql="SELECT sigesp_cmp.codemp, sigesp_cmp.procede, sigesp_cmp.comprobante, sigesp_cmp.descripcion, sigesp_cmp.fecha, ".
				"		sigesp_cmp.cod_pro, sigesp_cmp.ced_bene, sigesp_cmp.tipo_destino, sigesp_cmp.codban, sigesp_cmp.ctaban, ".
				"       (SELECT nompro FROM rpc_proveedor ".
				"		  WHERE rpc_proveedor.codemp = sigesp_cmp.codemp ".
				"			AND rpc_proveedor.cod_pro = sigesp_cmp.cod_pro ) AS nompro, ".
				"       (SELECT nombene FROM rpc_beneficiario ".
				"		  WHERE rpc_beneficiario.codemp = sigesp_cmp.codemp ".
				"			AND rpc_beneficiario.ced_bene = sigesp_cmp.ced_bene ) AS nombene, ".
				"       (SELECT apebene FROM rpc_beneficiario ".
				"		  WHERE rpc_beneficiario.codemp = sigesp_cmp.codemp ".
				"			AND rpc_beneficiario.ced_bene = sigesp_cmp.ced_bene ) AS apebene ".				 
				"  FROM sigesp_cmp ".
				" WHERE sigesp_cmp.codemp='".$this->ls_codemp."' ".
				"	AND sigesp_cmp.tipo_comp=1 ".
				$ls_criterio.
				"	AND sigesp_cmp.codemp IN  (SELECT scg_dt_cmp.codemp FROM scg_dt_cmp ".
				"					 			WHERE scg_dt_cmp.codemp = sigesp_cmp.codemp ".
				"					   			  AND scg_dt_cmp.procede = sigesp_cmp.procede ".
				"					   			  AND scg_dt_cmp.comprobante = sigesp_cmp.comprobante ".
				"					 			  AND scg_dt_cmp.fecha = sigesp_cmp.fecha ".
				"					   			  AND scg_dt_cmp.codban = sigesp_cmp.codban ".
				"					   			  AND scg_dt_cmp.ctaban = sigesp_cmp.ctaban) ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_comprobante_cabecera ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_comprobante_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_comprobante_detalle($as_procede,$as_comprobante,$ad_fecha,$as_codban,$as_ctaban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_comprobante_detalle
		//         Access: public (desde la clase sigesp_sno_rpp_balance_comprobacion
		//	    Arguments: as_procede // Procede del Comprobante
		//	  			   as_comprobante // Número del Comprobante
		//	  			   ad_fecha // Fecha del Comprobante
		//	  			   as_codban // Código del Banco del comprobante  
		//	  			   as_ctaban // Cuenta del Banco del Comprobante	  
		//	  			   as_orden // Orden del reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los comprobantes contables
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 13/08/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT scg_dt_cmp.procede, scg_dt_cmp.comprobante, scg_dt_cmp.sc_cuenta, scg_dt_cmp.procede_doc, ".
			    "       scg_dt_cmp.debhab, (scg_dt_cmp.montoaux) AS monto, scg_cuentas.denominacion, CAST(scg_dt_cmp.descripcion as char(250)) as cmp_descripcion ".
			    "  FROM scg_dt_cmp, scg_cuentas ".
			    " WHERE scg_dt_cmp.codemp='".$this->ls_codemp."' ".
			    "   AND scg_dt_cmp.procede='".$as_procede."' ".
			    "   AND scg_dt_cmp.comprobante='".$as_comprobante."' ".
			    "   AND scg_dt_cmp.fecha='".$ad_fecha."' ".
			    "   AND scg_dt_cmp.codban='".$as_codban."' ".
			    "   AND scg_dt_cmp.ctaban='".$as_ctaban."' ".
			    "   AND scg_dt_cmp.codemp = scg_cuentas.codemp ".
			    "   AND scg_dt_cmp.sc_cuenta = scg_cuentas.sc_cuenta ".
			    " ORDER BY scg_dt_cmp.sc_cuenta, scg_dt_cmp.debhab ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_comprobante_detalle ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_comprobante_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>