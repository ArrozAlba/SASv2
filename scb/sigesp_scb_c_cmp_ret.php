<?Php
class sigesp_scb_c_cmp_ret
{
	var $io_sql;
	var $msg;
	var $io_function;
	var $ls_retid;
	var $ds_pagos;
	var $ds_ordenes;
	var $ds_ivaret;
	var $ds_libcompra;
	var $lb_ivacxp;
	var $sig_inc;	
	var $li_operacion;
	var $li_pago_actual;
	var $ls_gestorbd;
	var $dat_emp;
	var $ds_dt_cmpret;
	var $is_msg_error;
	function sigesp_scb_c_cmp_ret($as_retid)
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_fecha.php");
		require_once("../shared/class_folder/class_mensajes.php");
		$this->sig_inc=new sigesp_include();
		$con=$this->sig_inc->uf_conectar();
		$this->io_sql=new class_sql($con);
		$this->ds_pagos=new class_datastore();	
		$this->ds_ordenes=new class_datastore();		
		$this->ds_ivaret=new class_datastore();		
		$this->ds_libcompra=new class_datastore();		
		$this->ds_dt_cmpret=new class_datastore();
		$this->io_function=new class_funciones();
		$this->msg=new class_mensajes();
		$this->fec=new class_fecha();
		$this->ls_retid=$as_retid;
		$this->li_pago_actual=0;
		$this->ls_gestorbd=$_SESSION["ls_gestor"];
		$this->dat_emp=$_SESSION["la_empresa"];
	}

	function uf_comprobante_ret($ls_mes,$ls_agno,$ls_probendesde,$ls_probenhasta,$ls_tipo)
	{
		$ld_fecdesde= $this->io_function->uf_convertirdatetobd("01/".$ls_mes."/".$ls_agno);
		$ld_hasta   = $this->fec->uf_last_day($ls_mes,$ls_agno);
		$ld_fechasta= $this->io_function->uf_convertirdatetobd($ld_hasta);
		$ls_codemp  = $this->dat_emp["codemp"];
		$lb_valido  = true;
		$data=array();
		$ds_provben=new class_datastore();
		if($ls_tipo=='P')
		{
			$ls_sql = "SELECT cod_pro as codproben 
					   FROM rpc_proveedor
					   WHERE codemp='".$ls_codemp."' AND cod_pro between '".$ls_probendesde."' AND '".$ls_probenhasta."'
					   ORDER BY cod_pro ";
		}
		else
		{
			$ls_sql = "SELECT ced_bene as codproben  
					   FROM rpc_beneficiario
					   WHERE codemp='".$ls_codemp."' AND ced_bene between '".$ls_probendesde."' AND '".$ls_probenhasta."'
					   ORDER BY ced_bene ";
		}
		
		$rs_result=$this->io_sql->select($ls_sql);
		$ds_provben->resetds("codproben");
		if($rs_result===false)
		{
			$this->is_msg_error="Error al cargar proveedores o beneficiarios,".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
			$lb_valido=false;	
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_result))
			{
				$ds_provben->data=$this->io_sql->obtener_datos($rs_result);
			}
			$this->io_sql->free_result($rs_result);
		}

		$li_total=$ds_provben->getRowCount("codproben");
		
		if($lb_valido)
		{
			$this->io_sql->begin_transaction();
			for($li_i=1;$li_i<=$li_total;$li_i++)
			{
				$ls_codproben=$ds_provben->getValue("codproben",$li_i);
				$lb_valido=$this->uf_crear_comprobante($ld_fecdesde,$ld_fechasta, $ls_tipo, $ls_codproben, $ls_mensaje);
				if(!$lb_valido)			
				{					
					$this->msg->message($ls_mensaje);
					$this->io_sql->rollback();
				}
				else
				{
					$this->io_sql->commit();
					$this->msg->message("Registro Guardado".$ls_mensaje);
				}
			}			
		}	
		return $lb_valido;	
	}//fin uf_comprobante_ret

	function uf_crear_comprobante($ld_fecdesde,$ld_fechasta, $ls_tipo, $ls_codproben, &$ls_mensaje)
	{
		$ls_nrocomp; $ldec_ivaretenido; $ldec_porc;$ls_periodofiscal; $ls_Concepto="";$ldec_mor;$ls_numsol;
		$lb_valido = true;
		$lb_firsttime = true;
		$lb_continue=true;
		$ldec_total_iva=0;
		$ls_periodofiscal = $this->io_function->uf_cerosizquierda(substr($ld_fecdesde,0,4),4).$this->io_function->uf_cerosizquierda(substr($ld_fecdesde,5,2),2) ;
		$this->lb_ivacxp =true;
		
		if ((!$this->lb_ivacxp)||($this->ls_retid=='0000000003'))
		{
			if(!$this->uf_rep_cur_pagos($ld_fecdesde, $ld_fechasta,$ls_tipo, $ls_codproben))// Busco pagos para Proveedor/Beneficiario
			{
			   $ls_mensaje = $ls_mensaje."No hay pagos en el periodo para el Proveedor/Beneficiario ";
			   return false;
			}
		}
		else
		{
			if(!$this->uf_rep_cur_ordenes_pago($ld_fecdesde, $ld_fechasta,$ls_tipo,$ls_codproben))	// Busco ordenes de pago para Proveedor/Beneficiario
			{
			   $ls_mensaje = $ls_mensaje."No hay Ordenes de Pago contabilizadas en el periodo para el Proveedor/Beneficiario";
			   return false;
			}
		}
		
		if(!$this->uf_inf_source($ls_tipo,$ls_codproben,&$arr_datos_proben))// obtengo los datos del proveedor/beneficiario
		{
			$ls_mensaje = $ls_mensaje."Error al obtener los dats del Proveedor/Beneficiario";
			return false;
		}
		
		if(!$this->uf_ccr_get_nro($ls_periodofiscal,&$ls_nrocomp))// obtengo el numero del nuevo comprobante
		{
			$ls_mensaje = $ls_mensaje."Error al generar nuevo numero de comprobante";
			return false;
		}
		$this->li_operacion = 1;
		
		if(!($this->lb_ivacxp)||($this->ls_retid=='0000000003'))
		{
			$li_total=$this->ds_pagos->getRowCount("numsol");
		}
		else
		{
		   $li_total=$this->ds_ordenes->getRowCount("numsol");
		}
		
		for($li_x=1;$li_x<=$li_total;$li_x++)
		{
			$this->li_pago_actual=$li_x;
			if($lb_valido)
			{				
				if(!($this->lb_ivacxp)||($this->ls_retid=='0000000003'))
				{
					$ls_numdoc=$this->ds_pagos->getValue("numdoc",$li_x);					
					if(!$this->uf_rep_cur_iva_retenido($ls_numdoc, &$ldec_ivaretenido,&$ldec_porc,&$ldec_mor))
					{ 
						return false;
					}
					$ldec_total_iva=$ldec_total_iva	+$ldec_ivaretenido;				
				}
				else
				{
					$ls_numsol=$this->ds_ordenes->getValue("numsol",$li_x);					
					if(!$this->uf_rep_cur_iva_retenido($ls_numsol, &$ldec_ivaretenido, &$ldec_porc,&$ldec_mor))
					{
					   return false;
					}
					$ldec_total_iva=$ldec_total_iva	+$ldec_ivaretenido;
				}	
						
				switch ($this->ls_retid){
				 case '0000000001':
					 break;
				 case '0000000002':
					 break;
				 case '0000000003':
					if ($ldec_ivaretenido == 0)
					{
						$lb_continue=false;
					}
					break;
				}
				
				if($lb_continue)
				{
					$ls_numsol="";
					if (!($this->lb_ivacxp)||($this->ls_retid=='0000000003'))
					{
						$ls_numsol = $this->ds_pagos->getValue("numsol",$li_x);
					}
					else
					{
						$ls_numsol = $this->ds_ordenes->getValue("numsol",$li_x);
					}
					
					if(!is_null($ls_numsol)||(!empty($ls_numsol)))
					{
						if($this->uf_rep_cur_libro_compra( $ls_numsol,$ldec_porc, &$ls_Concepto ))
						{
							$lb_valido = $this->uf_ccr_guarda_inf_clc($lb_firsttime, $ls_periodofiscal, $ls_nrocomp, $arr_datos_proben, $ldec_ivaretenido, $ls_numsol, $ls_Concepto, $ldec_porc, $ldec_mor);
						}
						else
						{
							$this->msg->message("No hay Documentos validos para generar Retenciones de Impuestos Municipales o IVA Favor revisar las Recepciones de Documentos contenidas en esta Orden de Pago") ;
						}
					}
					else
					{
						$lb_valido = $this->uf_ccr_guarda_inf_slc($lb_firsttime, $ls_periodofiscal,$ls_nrocomp, $arr_datos_proben, $ldec_ivaretenido,$ls_numsol,$ls_Concepto,$ldec_porc,$ldec_mor) ;
					}						
				}					
			}	
			//Reset Datos			
			$this->ds_pagos->resetds("numsol");		
			$this->ds_ordenes->resetds("numsol");			
			}//End For			
			
				
			$ls_sql ="SELECT numope, numfac, iva_ret
					  FROM	scb_dt_cmp_ret
					  WHERE numcom = '".$ls_nrocomp."'  AND codret ='".$this->ls_retid."'
					  GROUP by numope, numfac, iva_ret 
					  ORDER by numope ";
			
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				print $this->io_sql->message;
				return false;
			}
			else
			{
			$ldec_ivaret = 0.00;
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ldec_monto=$row["iva_ret"];
					$ls_numfac=$row["numfac"];
					$ldec_ivaret = $ldec_ivaret + $ldec_monto;
				}
			}
			if($ldec_ivaret>$ldec_total_iva)
			{	
					$ldec_iva = $ldec_ivaret - $ldec_total_iva;
					$ls_sql = "UPDATE scb_dt_cmp_ret
							   SET iva_ret = ".($ldec_monto - $ldec_iva)." 
							   WHERE numfac = '".$ls_numfac."'";
			
					$li_result=$this->io_sql->execute($ls_sql);
					if($li_result ===false)
					{	
						print $this->io_sql->message;
						return false;
					}
			}
			elseif($ldec_ivaret<$ldec_total_iva)
			{
					$ldec_iva =  $ldec_total_iva - $ldec_ivaret;
					$ls_sql = "UPDATE scb_dt_cmp_ret
							  SET iva_ret = ".($ldec_total_iva + $ldec_iva)."
							  WHERE  numfac = '".$ls_numfact."'";
			
					$li_result=$this->io_sql->execute($ls_sql);
					if($li_result ===false)
					{	
						print $this->io_sql->message;
						return false;
					}
			}
			if($lb_valido)
			{
				$ls_mensaje = "Comprobante Nro: ".$ls_nrocomp;
			}		
		
		return $lb_valido;
	}//fin uf_crear_comprobante

	function uf_rep_cur_pagos($ld_fecdesde, $ld_fechasta, $ls_tipo, $ls_codproben)
	{
		
		switch ($this->ls_retid){
			case '0000000001':
				$ls_filt = "";
				break;
			case '0000000002':
				$ls_filt = "";
				break;
			case '0000000003':
				$ls_filt = " AND monret>0 ";
				break;
		}
		$ls_codemp=$this->dat_emp["codemp"];
		
		if($ls_tipo=='B')
		{
			$ls_sql=" SELECT BRH.numdoc as numdoc, BRH.fecmov as fecmov, XSC.numsol as numsol, XSC.monto as monto,BRH.codban as codban,BRH.ctaban as ctaban,BRH.codope as codope
					  FROM scb_movbco BRH LEFT OUTER JOIN  cxp_sol_banco XSC 
					  ON (BRH.codban=XSC.codban AND BRH.ctaban=XSC.ctaban AND BRH.numdoc=XSC.numdoc AND BRH.codope=XSC.codope AND BRH.estmov=XSC.estmov AND BRH.codemp=XSC.codemp)   
				      WHERE (BRH.codemp='".$ls_codemp."' AND BRH.codope='CH') AND (BRH.estmov='C') AND BRH.ced_bene='".$ls_codproben."'  AND (BRH.fecmov between '".$ld_fecdesde."' AND '".$ld_fechasta."') AND  
				     		  BRH.numdoc not IN ( SELECT numdoc FROM scb_dt_cmp_ret WHERE codret='".$this->ls_retid."' AND codemp='".$ls_codemp."' ) ".$ls_filt;
		
		}
		elseif($ls_tipo=='P')
		{
			$ls_sql=" SELECT BRH.numdoc as numdoc, BRH.fecmov as fecmov, XSC.numsol as numsol, XSC.monto as monto,BRH.codban as codban,BRH.ctaban as ctaban,BRH.codope as codope
				      FROM scb_movbco BRH LEFT OUTER JOIN cxp_sol_banco XSC 
				      ON (BRH.codban=XSC.codban AND BRH.ctaban=XSC.ctaban AND BRH.numdoc=XSC.numdoc AND BRH.codope=XSC.codope AND BRH.estmov=XSC.estmov AND BRH.codemp=XSC.codemp)   
				      WHERE ( BRH.codemp='".$ls_codemp."' AND BRH.codope='CH' OR BRH.codope='ND') AND (BRH.estmov='C') AND   
				           BRH.cod_pro='".$ls_codproben."' AND (BRH.fecmov between '".$ld_fecdesde."' AND '".$ld_fechasta."') AND 
				           BRH.numdoc not IN (SELECT numdoc FROM scb_dt_cmp_ret WHERE codret='".$this->ls_retid."' AND codemp='".$ls_codemp."') ".$ls_filt;
		}
		else
		{
			return false;
		}
				
		$rs_result=$this->io_sql->select($ls_sql);
		if($rs_result===false)
		{
			$this->is_msg_error="Error en uf_rep_cur_pagos,".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->io_sql->message;
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_result))
			{
				$this->ds_pagos->data=$this->io_sql->obtener_datos($rs_result);
				$this->io_sql->free_result($rs_result);
				return true;
			}
			else
			{
				$this->ds_pagos->data=array();
				$this->io_sql->free_result($rs_result);
				return false;
			}
		}
	}//fin uf_rep_cur_pagos

	function uf_rep_cur_ordenes_pago($ld_fecdesde, $ld_fechasta, $ls_tipo, $ls_codproben)
	{		
		switch ($this->ls_retid){
			case '0000000001':
				$ls_filt = "";
				break;
			case '0000000002':
				$ls_filt = "";
				break;
			case '0000000003':
				$ls_filt = "";
				break;
		}		
		$ls_codemp=$this->dat_emp["codemp"];
		if($ls_tipo=='B')
		{
			$ls_sql=" SELECT numsol, fecemisol as fecemi, monsol  as monto
					  FROM cxp_solicitudes 
					  WHERE  (estprosol ='C' OR estprosol='P')  AND ced_bene='".$ls_codproben."' AND (fecemisol between '".$ld_fecdesde."' AND '".$ld_fechasta."') 
					  AND codemp='".$ls_codemp."' AND numsol not in (SELECT numdoc FROM scb_dt_cmp_ret WHERE codemp='".$ls_codemp." AND codret='".$this->ls_retid."' ) ".$ls_filt;
		}
		elseif($ls_tipo=='P')
		{
			$ls_sql=" SELECT numsol, fecemisol as fecemi,monsol as monto  
					  FROM cxp_solicitudes 
					  WHERE (estprosol ='C' OR estprosol='P') AND cod_pro='".$ls_codproben."' AND (fecemisol between '".$ld_fecdesde."' AND '".$ld_fechasta."') 
					  AND codemp='".$ls_codemp."' AND numsol not in (SELECT numdoc FROM scb_dt_cmp_ret WHERE codemp='".$ls_codemp." AND codret='".$this->ls_retid."' ) ".$ls_filt;
		}
		else
		{
			return false;
		}
			
		$rs_result=$this->io_sql->select($ls_sql);
		//print $ls_sql;
		if($rs_result===false)
		{			
			$this->is_msg_error="Error en uf_rep_cur_ordenes_pago,".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_result))
			{
				$this->ds_ordenes->data=$this->io_sql->obtener_datos($rs_result);
				$this->io_sql->free_result($rs_result);
				return true;
			}
			else
			{				
				$this->io_sql->free_result($rs_result);
				$this->ds_ordenes->data=array();
				return false;
			}
			
		}
	}//fin uf_rep_cur_ordenes_pago
	
	function uf_rep_cur_iva_retenido($ls_numdoc,&$ldec_ivaretenido,$ldec_porc,$ldec_mor)
	{		
	
		$ls_retid=$this->ls_retid;
		switch ($ls_retid){
			case '0000000001':
				$ls_tipret = " XMC.IVA=1 ";
				break;
			case '0000000002':
				$ls_tipret = " XMC.ISLR=1 ";
				break;
			case '0000000003':
				$ls_tipret = " XMC.EstRetMun=1 ";
				break;				
		}
		$ls_codemp=$this->dat_emp["codemp"];				
		if(!($this->lb_ivacxp)||($this->ls_retid=='0000000003'))
		{
			$ls_sql=" SELECT BRC.codded as codded, XMC.porded as porded, sum(BRC.monto) as retenido, sum(brc.monobjret) as moret 
			  		  FROM scb_movbco_scg BRC,sigesp_deducciones XMC 
					  WHERE (BRC.codded = XMC.codded) AND ( ".$ls_tipret." AND BRC.numdoc = '".$ls_numdoc."') AND BRC.codemp='".$ls_codemp."' AND XMC.codemp='".$ls_codemp."'
					  GROUP by BRC.codded, XMC.porded ";		   
		}
		else
		{
		   $ls_sql=" SELECT XRC.codded as codded, XMC.porded as porded, sum(XRC.monret) as retenido, sum(XRC.monobjret) as moret
			  		 FROM cxp_dt_solicitudes XDS,sigesp_deducciones XMC, cxp_rd_deducciones XRC 
			 		 WHERE  (XDS.numsol = '".$ls_numdoc."') AND (XDS.numrecdoc = XRC.numrecdoc) AND (XRC.codded = XMC.codded) AND ( ".$ls_tipret.") AND XDS.codemp='".$ls_codemp."' AND XMC.codemp='".$ls_codemp."' AND XRC.codemp='".$ls_codemp."'
					 GROUP by XRC.codded, XMC.porded ";
		}
		
		
		$rs_result=$this->io_sql->select($ls_sql);
		//print $ls_sql;
		if($rs_result===false)
		{
			$this->is_msg_error="Error en uf_rep_cur_iva_retenido,".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_result))
			{
				$this->ds_ivaret->data=$this->io_sql->obtener_datos($rs_result);
				
				$ldec_retenido=$this->ds_ivaret->getValue("retenido",1);				
				if(is_null($ldec_retenido))	
				{
					$ldec_ivaretenido=0;
				}
				else
				{
					$ldec_ivaretenido=$ldec_retenido;
				}
				$ldec_porc=$this->ds_ivaret->getValue("porded",1);
				$ldec_monobjret = $this->ds_ivaret->getValue("moret",1);
				if(empty($ldec_monobjret))
				{
					$ldec_mor=0;
				}
				else
				{
					$ldec_mor=$ldec_monobjret;
				}
				if($this->ds_ivaret->getRowCount("porded")>1)
				{
					$this->msg("Se esta aplicando mas de una retencion al documento ".$ls_numdoc);
				}
				$this->io_sql->free_result($rs_result);
				$this->ds_ivaret->resetds("moret");
				return true;
			}
			return true;
			//unset($this->ds_ivaret);
		}	
	}//fin uf_rep_cur_iva_retenido
	
	function uf_inf_source($ls_tipo,$ls_codproben,$arr_proben)
	{	
			$arr_proben=array();
			$ls_codemp=$this->dat_emp["codemp"];
			if($ls_tipo=='B')
			{
				   if($_SESSION["ls_gestor"]=="MYSQL")
				   { 
					 $ls_cadena_gestor = " concat(rtrim(apebene),', ',rtrim(nombene))";
				   }
				   elseif($_SESSION["ls_gestor"]=="ORACLE")
				   { 
					 $ls_cadena_gestor = " rtrim(apebene)||', '||rtrim(nombene)";
				   }
				   else
				   {
					  $ls_cadena_gestor = "rtrim(apebene) +', '+ rtrim(nombene)";
				   }
				$ls_sql=" SELECT ced_bene as codigo,rifben as rif,".$ls_cadena_gestor." as nombre, dirbene as direccion
						  FROM   rpc_beneficiario 
						  WHERE  codemp='".$ls_codemp."' AND ced_bene = '".$ls_codproben."'";
			}
			elseif($ls_tipo=='P')
			{
				$ls_sql=" SELECT  cod_pro as codigo, rifpro as rif, nompro as nombre, dirpro as direccion, numlic as numlic, nitpro as nit
						  FROM    rpc_proveedor
						  WHERE   codemp='".$ls_codemp."' AND cod_pro = '".$ls_codproben."'";
			}
			else
			{
				return false;
			}
		
		
			$rs_result=$this->io_sql->select($ls_sql);
			//print $ls_sql;
			if($rs_result===false)
			{
				$this->is_msg_error="Error en uf_inf_source ,".$this->io_function->uf_convertirmsg($this->io_sql->messsage);
				print $this->is_msg_error;
				return false;				
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_result))
				{
					$arr_proben["codigo"]=$row["codigo"];
					$arr_proben["rif"]=$row["rif"];
					$arr_proben["nombre"]=$row["nombre"];
					$arr_proben["direccion"]=$row["direccion"];
					if($ls_tipo=='P')
					{	
						$arr_proben["numlic"]=$row["numlic"];
						$arr_proben["nit"]=$row["nit"];
					}
					else
					{
						$arr_proben["numlic"]=" ";
						$arr_proben["nit"]=" ";
					}
					$this->io_sql->free_result($rs_result);
					return true;
				}
				return true;
				
			}
	}//Fin uf_inf_source
	
	
	function uf_ccr_get_nro($ls_periodofiscal,$ls_nrocomp)
	{
		$ls_codemp=$this->dat_emp["codemp"];	
		$ls_sql="SELECT numcom 
				 FROM   scb_cmp_ret 
				 WHERE  codemp='".$ls_codemp."' AND codret = '".$this->ls_retid."' AND perfiscal = '".$ls_periodofiscal."'
				 ORDER  by numcom desc ";
		
		$rs_result=$this->io_sql->select($ls_sql);
		//print $ls_sql;
		if($rs_result===false)
		{
			$this->is_msg_error=$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
			return false;			
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_result))
			{ 
			   $codigo=$row["numcom"];
			   settype($codigo,'int');                             // Asigna el tipo a la variable.
			   $codigo = $codigo + 1;                              // Le sumo uno al entero.
			   settype($codigo,'string');                          // Lo convierto a varchar nuevamente.
			   $ls_nrocomp=$this->io_function->uf_cerosizquierda($codigo,8);
			  $this->io_sql->free_result($rs_result);
			   return true;
		    }
		    else
		    {
			   $codigo="1";
			   $ls_nrocomp=$this->io_function->uf_cerosizquierda($codigo,8);
			   $this->io_sql->free_result($rs_result);
   			   return true;
		    }				
			
		}
		
	
	}//fin uf_ccr_get_nro
	
	function uf_rep_cur_libro_compra( $ls_numsol,$ldec_porc, $ls_Concepto )
	{
		$ls_concepto ="";
		$li_total=0;		
		$ls_codemp=$this->dat_emp["codemp"];
		if ($this->ls_retid=='0000000003')
		{
			$ls_im=" (XRD.imp_mun = 1) AND ";
		}
		else
		{
			$ls_im="";
		}
		
		if(empty($ls_im))
		{
			 $ls_sql=" SELECT xds.numrecdoc as numrecdoc,XRD.codtipdoc as codtipdoc,  XRD.cod_pro as cod_pro,  XRD.ced_bene as ced_bene, ". 
				     "        XRD.fecemidoc AS fecha, XRD.numrecdoc AS factura, XRD.mondeddoc as mondeddoc, XRD.numref AS nro_control, ".
				     "   	   XRD.montotdoc as total_p_isv,0 as csdc, COALESCE(XDC.monobjret,0) as base_imponible, ". 
				     "   	   COALESCE(XDC.monret,0) as total_impuesto,0 as porc_impuesto,". 
				     "   	   XSP.consol as consol, COALESCE(XDC.codcar,'-----') as codcar ".
				  	 " FROM cxp_rd XRD,cxp_rd_cargos XDC,cxp_dt_solicitudes XDS,cxp_solicitudes XSP ".
				  	 " WHERE (XRD.codemp=XDC.codemp AND XRD.codemp=XDS.codemp AND XRD.codemp=XSP.codemp AND XRD.codemp='".$ls_codemp."') AND (XRD.numrecdoc=XDC.numrecdoc AND XRD.codtipdoc=XDC.codtipdoc AND XRD.cod_pro=XDC.cod_pro AND XRD.ced_bene=XDC.ced_bene) AND ".
				     "		  (XRD.numrecdoc=XDS.numrecdoc AND XRD.codtipdoc=XDS.codtipdoc) AND ".
				     "		  (XDS.numsol=XSP.numsol) AND (XRD.estlibcom=1 or XRD.estlibcom=2) AND ".$ls_im.
				     "		  (XSP.estprosol='C' OR XSP.estprosol='S' OR XSP.estprosol='P') AND ".
				     "		  (XDC.codcar IN (SELECT codcar ".
				     "      				  FROM sigesp_cargos ".
				     "                        WHERE  estlibcom=1) AND XSP.numsol='".$ls_numsol."')". 
				  	 " ORDER BY XRD.fecemidoc, XRD.numrecdoc ";
		}
		else
		{ 
				
				if(($this->ls_gestorbd=="MYSQL")||($this->ls_gestorbd=="ORACLE"))
				{
					$ls_sql = " SELECT TMP.*,COALESCE(XDC.codcar,'-----') as codcar ".
							  " FROM ( SELECT xds.numrecdoc as numrecdoc,XRD.codtipdoc as codtipdoc,XRD.cod_pro as cod_pro,XRD.ced_bene as ced_bene,XRD.fecemidoc AS fecha, XRD.numrecdoc AS factura, ".
							  "               XRD.mondeddoc,XRD.numref AS nro_control,XRD.montotdoc as total_p_isv,0 as csdc,0 as total_impuesto,0 as porc_impuesto,XSP.consol  as consol".
							  "        FROM cxp_rd XRD,cxp_dt_solicitudes XDS,cxp_solicitudes XSP ".
							  "        WHERE (XRD.codemp=XDS.codemp AND XRD.codemp=XSP.codemp AND XRD.codemp='".$ls_codemp."') AND ".
							  "				 (XRD.numrecdoc=XDS.numrecdoc AND XRD.codtipdoc=XDS.codtipdoc ) AND ".
							  "  	         (XDS.numsol=XSP.numsol) AND (XRD.estlibcom=1 or XRD.estlibcom=2) AND  ".
							  "  	         (XSP.estprosol='C' OR XSP.estprosol='S' OR XSP.estprosol='P') AND XSP.numsol='".$ls_numsol."'  ) TMP ".
							  " LEFT OUTER JOIN cxp_rd_cargos XDC ".
							  " ON TMP.numrecdoc=XDC.numrecdoc AND TMP.codtipdoc=XDC.codtipdoc AND TMP.cod_pro=XDC.cod_pro AND TMP.ced_bene=XDC.ced_bene  ".
							  "  WHERE XDC.codcar IN (SELECT codcar  ".
							  "                       FROM   sigesp_cargos  ".
							  "                       WHERE  estlibcom=1) ".
							  "  ORDER BY  TMP.fecha, TMP.numrecdoc ";					
							  
				}
				 else
				{		
					$ls_sql=" SELECT xds.numrecdoc as numrecdoc,XRD.codtipdoc as codtipdoc,XRD.cod_pro as cod_pro, XRD.ced_bene as ced_bene,  ".
						    "     XRD.fecemidoc AS fecha, XRD.numrecdoc AS factura, XRD.mondeddoc as mondeddoc, XRD.numref AS nro_control,  ".
						    "     XRD.monto as total_p_isv, cast(0 as money) as csdc, isnull(XDC.monobjret,cast(0 as money)) as base_imponible,  ".
						    "     isnull(XDC.monret,cast(0 as money)) as total_impuesto, cast(0 as money) as porc_impuesto,  ".
						    "     XSP.consol as consol,isnull(XDC.codcar,'-----') as codcar  ".
						    "  FROM cxp_rd XRD, cxp_rd_cargos XDC, cxp_dt_solicitudes XDS, cxp_solicitudes XSP ".
						 	"  WHERE	(XRD.codemp=XDC.codemp AND XRD.codemp=XDS.codemp AND XRD.codemp=XSP.codemp AND XRD.codemp='".$ls_codemp."') AND ".
							"		(XRD.numrecdoc*=XDC.numrecdoc AND XRD.codtipdoc*=XDC.codtipdoc AND XRD.cod_pro*=XDC.cod_pro AND XRD.ced_bene*=XDC.ced_bene) AND ".
						    "		(XRD.numrecdoc=XDS.numdoc AND XRD.codtipdoc=XDS.codtipdoc ) AND ".
						    "		(XDS.numsol=XSP.numsol) AND ".
						    "		(XRD.estlibcom=1 or XRD.estlibcom=2) AND ".$ls_im.
						    "		(XSP.estprosol='C' OR XSP.estprosol='S' OR XSP.estprosol='P') AND ".
						    "		(XDC.codcar IN (SELECT codcar FROM sigesp_cargos where  and estlibcom=1))  ".
						    "		 AND XSP.numsol='".$ls_numsol."'".
						    "  ORDER  by XRD.fecemidoc, XRD.numrecdoc ";
				}
		}
		
		$rs_result = $this->io_sql->select($ls_sql);
		//print $ls_sql;
		if($rs_result===false)
		{
			$this->is_msg_error="Error en uf_rep_cur_libro_compra, ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->io_sql->message;
			print $ls_sql;
			return false;
		}
		else
		{
			
			//if(!oApp.CFG_READ([SOC], [IMPUESTO SOC], [DESAGREGAR], [1], [I], @nDesagregar)
			   //return .F.
			//endif//
			
			$lb_desagregar =true;
			if($row=$this->io_sql->fetch_row($rs_result))
			{
				$this->ds_libcompra->data=$this->io_sql->obtener_datos($rs_result);
				$this->io_sql->free_result($rs_result);
				$li_total=$this->ds_libcompra->getRowCount("numrecdoc");	
									
				for($li_i=1;$li_i<=$li_total;$li_i++)	
				{			
					//print "Total".$li_total;
					$ls_Concepto = $this->ds_libcompra->getValue("consol",$li_i);
					$ldec_totimp=$this->ds_libcompra->getValue("total_impuesto",$li_i);
					
					if( ($ldec_totimp==0)AND($lb_desagregar))
					{
						if(($this->ls_retid=="0000000001")OR($this->ls_retid=="0000000002"))
						{ 					  
							$ldec_total=$this->ds_libcompra->getValue("total_p_isv",$li_i);								
							$this->ds_libcompra->updateRow("csdc",$ldec_total,$li_i);
							
							$this->uf_buscar_base_soc();
							
							$ldec_total=$this->ds_libcompra->getValue("total_p_isv",$li_i);
							
							$ldec_baseimponible=$this->ds_libcompra->getValue("base_imponible",$li_i);
							
							$ldec_valor=$ldec_total-$ldec_baseimponible;	
							
							$this->ds_libcompra->updateRow("total_impuesto",$ldec_valor,$li_i);
							if(($ldec_baseimponible==0)OR(is_null($ldec_baseimponible)))
							{	//*=MESSAGEBOX("La base imponible es cero, Solicitud: "+tcNumsol+" RD: "+curLibComp.factura)
								$ldec_porcimp = 0;
							}
							else
							{
								$ldec_totalimpuesto=$this->ds_libcompra->getValue("total_impuesto",$li_i);
								$ldec_porcimp = ( $ldec_totalimpuesto / $ldec_baseimponible) * 100;
								$ldec_porcimp = round($ldec_porcimp, 4);								
							}
							$this->ds_libcompra->updateRow("porc_impuesto",$ldec_porcimp,$li_i);

						}
						elseif($this->ls_retid=='0000000003')
						{
							$this->uf_buscar_base_soc();
							
							$ldec_baseimponible=$this->ds_libcompra->getValue("base_imponible",$li_i);
							
							if( $ldec_baseimponible == 0)
							{
								$ldec_porcimp = 0.00;
							}
							else	
							{
								$ldec_totalimpuesto=$this->ds_libcompra->getValue("total_impuesto",$li_i);
								$ldec_porcimp = ($ldec_totalimpuesto / $ldec_baseimponible) * 100;
							}
							$ldec_porcimp = round($ldec_porcimp, 4);
							
							$this->ds_libcompra->updateRow("porc_impuesto",$ldec_porcimp,$li_i);
						}
					}
					else
					{
						$ldec_baseimponible=$this->ds_libcompra->getValue("base_imponible",$li_i);						
						$ldec_totalimpuesto=$this->ds_libcompra->getValue("total_impuesto",$li_i);						
						if($ldec_baseimponible != 0)
						{
							$ldec_porcimp = ($ldec_totalimpuesto / $ldec_baseimponible) * 100;
							$ldec_porcimp = round($ldec_porcimp, 4);											
						}
						else
						{
							//&&=MESSAGEBOX("La base imponible es cero Solicitud: "+tcNumsol+" RD: "+curLibComp.factura)
							$ldec_porcimp = 0;
						}
						$this->ds_libcompra->updateRow("porc_impuesto",$ldec_porcimp,$li_i);
					}
				
					switch ($this->ls_retid){
						case '0000000001':
							break;
						case '0000000002':
							break;
						case '0000000003':						
							$this->ds_libcompra->updateRow("porc_impuesto",$ldec_porc,$li_i);
							break;
					}			
			}
		}		
	}
	return ($li_total>0);
	}
	
	
	function uf_buscar_base_soc()
	{
		//-copio el cursor original
		$ds_temporal=new class_datastore();
		$con=$this->sig_inc->uf_conectar();	
		$io_sql_aux=new class_sql($con);
		$ls_codemp=$this->dat_emp["codemp"];
		$ds_temporal->data=$this->ds_libcompra->data;//Asigno a Temporal con los datos del cursos libro de compra
		$this->ds_libcompra->resetds("numrecdoc");//blanqueo los datos del datastore libro de compra
		
		$li_total=$ds_temporal->getRowCount("numrecdoc");

		for($li_x=1;$li_x<=$li_total;$li_x++)
		{
			
			$ls_factura=$ds_temporal->getValue("factura",$li_x);
			$ls_codtipdoc=$ds_temporal->getValue("codtipdoc",$li_x);
			$ls_codpro=$ds_temporal->getValue("cod_pro",$li_x);
			$ls_cedbene=$ds_temporal->getValue("ced_bene",$li_x);
			
			$ls_sql="SELECT  distinct procede_doc, numdoccom 
				     FROM    cxp_rd_spg
				     WHERE   codemp='".$ls_codemp."' AND numrecdoc='".$ls_factura."' AND codtipdoc='".$ls_codtipdoc."' AND cod_pro='".$ls_codpro."' AND ced_bene='".$ls_cedbene."'";
			
			$rs_result= $this->io_sql->select($ls_sql);
			//print $ls_sql;
			if($rs_result===false)
			{
				$this->is_msg_error="Error en uf_buscar_base_soc,".$this->io_function->uf_convertirmsg($this->io_sql->message);
				print $this->is_msg_error;
				return false;
			}
			else
			{	
				while($row=$this->io_sql->fetch_row($rs_result))
				{
				 	$ls_compromiso=$row["numdoccom"];
					$ls_procede_doc=$row["procede_doc"];
					switch($ls_procede_doc){
						case 'SOCCOC':
							$ls_cad="SELECT base_imponible FROM soc_comp  WHERE codemp='".$ls_codemp."' AND num_ord_com='".$ls_compromiso."'";
							break;
						case 'SOCCOS':
							$ls_cad="SELECT base_imponible FROM soc_oserv WHERE codemp='".$ls_codemp."' AND num_ord_serv='".$ls_compromiso."'";
							break;							
						default:
							$ls_cad=" SELECT 0 as base_imponible ";
					}
			
//					*-Busco la base imponible
					$rs_base=$io_sql_aux->select($ls_cad);
					//print $ls_cad;
					if($rs_base===false)
					{
						$this->is_msg_error="Error al buscar la base imponible metodo uf_buscar_base_soc,".$this->io_function->uf_convertirmsg($this->io_sql->message);
						print $this->io_sql->message;
						return false;
					}
					else
					{
						if($row=$io_sql_aux->fetch_row($rs_base))
						{
							$ldec_base = $row["base_imponible"];
						}	
					}					
				
				  $ld_fecha=$this->io_function->uf_convertirfecmostrar($ds_temporal->getValue("fecha",$li_x));
				  $this->ds_libcompra->insertRow("fecha",$ld_fecha);
				  $ls_factura=$ds_temporal->getValue("factura",$li_x);
				  $this->ds_libcompra->insertRow("factura",$ls_factura);
				  $ls_nrocontrol=$ds_temporal->getValue("nro_control",$li_x);
				  $this->ds_libcompra->insertRow("nro_control",$ls_nrocontrol);
				  $ldec_total=$ds_temporal->getValue("total_p_isv",$li_x);
				  $this->ds_libcompra->insertRow("total_p_isv",$ldec_total);
				  $ldec_csdc=$ds_temporal->getValue("csdc",$li_x);
				  $this->ds_libcompra->insertRow("csdc",$ldec_csdc);
				  $this->ds_libcompra->insertRow("base_imponible",$ldec_base);
				  $ldec_totalimpuesto=$ds_temporal->getValue("total_impuesto",$li_x);
				  $this->ds_libcompra->insertRow("total_impuesto",$ldec_totalimpuesto);
  				  $ldec_porcimpuesto=$ds_temporal->getValue("porc_impuesto",$li_x);
				  $this->ds_libcompra->insertRow("porc_impuesto",$ldec_porcimpuesto);
				  $ls_concepto=$ds_temporal->getValue("consol",$li_x);
				  $this->ds_libcompra->insertRow("consol",$ls_concepto);
				  $ls_codcar=$ds_temporal->getValue("codcar",$li_x);
				  $this->ds_libcompra->insertRow("codcar",$ls_codcar);
  				  $ls_numrecdoc=$ds_temporal->getValue("numrecdoc",$li_x);
				  $this->ds_libcompra->insertRow("numrecdoc",$ls_numrecdoc);			
				  
				}//End while

			}	//End if
			
		}//End For

	}
	
	
	function uf_ccr_guarda_inf_clc($lb_firsttime, $ls_periodofiscal, $ls_nrocomp, $arr_datos_probene, $ldec_ivaretenido, $ls_numsol, $ls_concepto, $ldec_porc, $ldec_mor)
	{
		
		$ls_codemp=$this->dat_emp["codemp"];
		$lb_valido=true;		
		if($lb_firsttime)
		{
			$lb_valido = $this->uf_ccr_graba_cabecera($ls_nrocomp, $ls_periodofiscal, $arr_datos_probene);
			$lb_firsttime=false;
		}
		
		if($lb_valido)
		{	
			$ls_num_ND = ' ';
			$ls_num_NC = ' ';
			$ls_tipotrans = '01-reg';
			$ls_num_fact_afec = ' ';
			$li_total=$this->ds_libcompra->getRowCount("numrecdoc");
			
			for($li_i=1;$li_i<=$li_total;$li_i++)
			{
				
				$ls_operacion = $this->io_function->uf_cerosizquierda($this->li_operacion, 10);
				$this->li_operacion = ($this->li_operacion + 1);
				
				$ldec_total=$this->ds_libcompra->getValue("total_p_isv",$li_i);
				
				if( $ldec_total!= 0)
				{
					if(!($this->lb_ivacxp)OR($this->ls_retid=='0000000003'))	
					{
						
						$ldec_monto=$this->ds_pagos->getValue("monto",$this->li_pago_actual);
						
						$li_factor = $ldec_monto / $ldec_total;
						if($li_factor > 1)
						{
						   $li_factor = 1;
						}
						$ls_numdoc=$this->ds_pagos->getValue("numdoc",$this->li_pago_actual);
						$ls_codban=$this->ds_pagos->getValue("codban",$this->li_pago_actual);
						$ls_ctaban=$this->ds_pagos->getValue("ctaban",$this->li_pago_actual);
						$ls_codope=$this->ds_pagos->getValue("codope",$this->li_pago_actual);
					}
					else 
					{
						$ldec_monto=$this->ds_ordenes->getValue("monto",$this->li_pago_actual);
						$li_factor = $ldec_monto / $ldec_total;
						if($li_factor> 1)
						{
						   $li_factor = 1;
						}
						$ls_numdoc = $ls_numsol;
						$ls_codban="";
						$ls_ctaban="";
						$ls_codope="";
					}
				}	
				else
				{
					$li_factor = 1;
					$this->msg("La base imponible es cero RD: ".$this->ds_libcompra->getValue("factura",$li_i));
				}
				
				$ldec_basei=0;
				$ldec_impuesto=0;
				$ls_codcar=$this->ds_libcompra->getValue("codcar",$li_i);
				$ls_numdoc=$this->ds_libcompra->getValue("numrecdoc",$li_i);
				//----> mando a buscar el monto del impuesto y de ob. ret.
				$this->uf_rep_montos($ls_numsol,$ls_codcar,$ls_numdoc,&$ldec_basei,&$ldec_impuesto );
				//print "RD= $ls_numdoc"." basei=".$ldec_basei." impuesto= ".$ldec_impuesto;
				$ldec_monded=$this->ds_libcompra->getValue("mondeddoc",$li_i);
				$ldec_baseimponible=$this->ds_libcompra->getValue("base_imponible",$li_i);
				$ldec_totalimpuesto=$this->ds_libcompra->getValue("total_impuesto",$li_i);
				$ls_codpro=$this->ds_libcompra->getValue("cod_pro",$li_i);
				$ls_cedbene=$this->ds_libcompra->getValue("ced_bene",$li_i);
				$ldec_totcsdc = ($ldec_total + $ldec_monded) - ($ldec_basei + $ldec_impuesto);
				$ldec_totPISV = $li_factor * ( $ldec_total + $ldec_monded);
				// Agregado el 03-08-2005
				if(($ldec_totPISV != ($ldec_baseimponible+$ldec_totcsdc+$ldec_totalimpuesto))AND($ldec_totalimpuesto != 0))
				{
				   $ldec_csdc=$this->ds_libcompra->getValue("csdc",$li_i);
				   $ldec_totPISV = ($ldec_baseimponible+$ldec_csdc+$ldec_totalimpuesto);
				}
				$ldec_baseimponible = $li_factor * $ldec_baseimponible;
				$ldec_totimp = $li_factor * $ldec_totalimpuesto;
				$ldec_baseimponible = $ldec_basei;
				$ldec_totimp = $ldec_impuesto;
				$ldec_ivaretenido = $ldec_totimp * ($ldec_porc/100);
				
				if(($ldec_ivaretenido == 0)OR($this->lb_ivacxp))
				{
				   $ls_sql="SELECT NPR.monret as retenido 
							FROM   cxp_rd_deducciones NPR, sigesp_deducciones NPS 
							WHERE  NPR.numrecdoc ='".$ls_numdoc."' AND NPR.codded = NPS.codded AND NPR.codemp='".$ls_codemp."' AND NPS.codemp='".$ls_codemp."' AND
							NPS.iva = 1 AND (NPR.cod_pro='".$ls_codpro."' AND NPR.ced_bene='".$ls_cedbene."')";
		
				   $rs_result=$this->io_sql->select($ls_sql);
				   //print $ls_sql;
				   if($rs_result===false)
				   {
					  $this->is_msg_error="Error en uf_ccr_guarda_inf_clc, ".$this->io_function->uf_convertirmsg($this->io_sql->message);
					  print $this->is_msg_error;
					  return false;
				   }
				   else
				   {
				   		if($row=$this->io_sql->fetch_row($rs_result))
						{
							$ldec_ivaretenido = $row["Retenido"];
				   		}						
				   		$this->io_sql->free_result($rs_result);
				   }
				
				
				if($this->ls_retid=='0000000003')
				{
				   if($ldec_baseimponible != 0)
				   {
				   	 $ldec_porcimpuesto=$this->ds_libcompra->getValue("porc_impuesto",$li_i);
				     $ldec_ivaretenido = round((($ldec_baseimponible+$ldec_totcsdc)*$ldec_porcimpuesto),2);
				   }
				   else
				   { 
			          $ldec_porcimpuesto=$this->ds_libcompra->getValue("porc_impuesto",$li_i);
					  $ldec_ivaretenido = round(($ldec_totPISV * $ldec_porcimpuesto),2);
				   }
				}
			
				if($ldec_ivaretenido > 0)
				{
					   $ld_fecha=$this->ds_libcompra->getValue("fecha",$li_i);
					   $ld_fecha=$this->io_function->uf_convertirdatetobd($ld_fecha);
					   $ls_factura=$this->ds_libcompra->getValue("factura",$li_i);
					   $ldec_porcimpuesto=$this->ds_libcompra->getValue("porc_impuesto",$li_i);
					   $ls_nrocontrol=$this->ds_libcompra->getValue("nro_control",$li_i);
					     
					   $ls_sql="INSERT into scb_dt_cmp_ret(codemp, codret, numcom, numope, fecfac, numfac, numcon, numnd, numnc, tiptrans, totcmp_sin_iva, totcmp_con_iva, basimp, porimp, totimp, iva_ret, desope, numsop, codban, ctaban, numdoc, codope)														   
								VALUES('".$ls_codemp."','".$this->ls_retid."','".$ls_nrocomp."','".$ls_operacion."','".$ld_fecha."','".$ls_factura."','".$ls_nrocontrol."','".$ls_num_ND."','".$ls_num_NC."','".$ls_tipotrans."',".$ldec_totPISV.",".$ldec_totcsdc.",".$ldec_baseimponible.",".$ldec_porcimpuesto.",".$ldec_totimp.",".$ldec_ivaretenido.",'".$ls_concepto."','".$ls_numsol."','".$ls_codban."','".$ls_ctaban."','".$ls_numdoc."','".$ls_codope."')";
						
					  $li_result = $this->io_sql->execute($ls_sql);
		  			  //print $ls_sql;
					  if($li_result===false)
					  {
						 $this->is_msg_error="Error al insertar detalle en uf_ccr_guarda_inf_clc, ".$this->io_function->uf_convertirmsg($this->io_sql->message);
						 print $this->io_sql->message;
						 return false;
					  }
					  else
					  {
						$lb_valido=true;
					  }
				}			
			}//End For

		}
	}
	
	return $lb_valido;	
}
	function uf_ccr_graba_cabecera($ls_nrocomp, $ls_periodofiscal, $arr_datos_probene)
	{
			
		$ld_fecha=date("Y/m/d");
		$ls_codusu=$_SESSION["la_logusr"];
		$ls_rif= $arr_datos_probene["rif"];
		$ls_codigo=$arr_datos_probene["rif"];
		$ls_nombre=$arr_datos_probene["nombre"];
		$ls_direccion=$arr_datos_probene["direccion"];
		$ls_nit=$arr_datos_probene["nit"];
		$ls_numlic=$arr_datos_probene["numlic"];
		$ls_codemp=$this->dat_emp["codemp"];
		if(strlen($ls_rif)==8)
		{
			$ls_rif = $this->io_function->uf_cerosizquierda($ls_rif,10);
		}
		
		$ls_sql="INSERT into scb_cmp_ret(codemp,codret,numcom,fecrep,perfiscal,codsujret, nomsujret, dirsujret, rif, nit,numlic,estcmpret,codusu) 
				 VALUES ('".$ls_codemp."','".$this->ls_retid."','".$ls_nrocomp."','".$ld_fecha."','".$ls_periodofiscal."','".$ls_codigo."','".$ls_nombre."','".$ls_direccion."','".$ls_rif."','".$ls_nit."','".$ls_numlic."', 1,'".$ls_codusu."')";
		
		$li_result= $this->io_sql->execute($ls_sql);
		//print $ls_sql;
		if($li_result===false)
		{
			$this->is_msg_error="Error en uf_ccr_graba_cabecera,".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->io_sql->message;
			return false;
		}
		else
		{
			return true;
		}
	
	
	}
	
	function uf_rep_montos($ls_numsol,$ls_coddedcar,$ls_numdoc,&$ldec_basei,&$ldec_impuesto)
	{
		
		//> este proceso sirve para buscar al base imponible y el impuesto
		//*local nBaseI,nImpuesto
		$ls_concepto ='';
		$ls_codemp=$this->dat_emp["codemp"];
			
		$ls_sql=" SELECT xdc.monobjret as base_imponible,xdc.monret as impuesto,xdc.codded 
				  FROM   cxp_solicitudes xsp, cxp_dt_solicitudes xds , cxp_rd_deducciones xdc, cxp_rd xrd 
				  WHERE  (xsp.codemp=xds.codemp AND xsp.codemp=xdc.codemp AND xsp.codemp=xrd.codemp AND xsp.codemp='".$ls_codemp."') 
				  AND xsp.numsol=xds.numsol and xsp.numsol = '".$ls_numsol."' AND 
					  	 xdc.codded ='".$ls_coddedcar."' AND 
						 xds.numrecdoc ='".$ls_numdoc."' AND 
					  	 xds.numrecdoc = xdc.numrecdoc AND 
					 	 xds.codtipdoc = xdc.codtipdoc AND 						
					(xrd.numrecdoc=xds.numrecdoc AND xrd.codtipdoc=xds.codtipdoc ) AND    
					(xrd.estlibcom=1 or xrd.estlibcom=2) AND    
					(xsp.estprosol='C' OR xsp.estprosol='S' OR xsp.estprosol='P')";  
		
		
		$rs_result=$this->io_sql->select($ls_sql);
		if($rs_result===false)
		{	
			$this->is_msg_error="Error en uf_rep_montos, ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_result))
			{
				$ldec_basei    = $row["base_imponible"];
				$ldec_impuesto = $row["impuesto"];
			}
			else
			{
				$ldec_basei    = 0.00;
				$ldec_impuesto = 0.00;
			}
		}	
	
	}

	function uf_ccr_guarda_inf_slc($lb_firsttime, $ls_periodofiscal,$ls_nrocomp, $arr_datos_probene, $ldec_ivaretenido,$ls_numsol,$ls_concepto,$ldec_porc,$ldec_mor) 
	{
			
		$lb_valido = true;
		$ls_codemp=$this->dat_emp["codemp"];
				
		if(is_null($ls_numsol))
		{
			$ls_numsol = "Nulo";
		}
		if(is_null($ls_concepto))
		{
			$ls_concepto = "Nulo";
		}

		if($lb_firsttime)
		{
			$lb_valido = $this->uf_ccr_graba_cabecera($ls_nrocomp, $ls_periodofiscal, $arr_datos_probene);
			$lb_firsttime=false;
		}

		if($lb_valido)
		{
			$ls_operacion = "P".$this->io_function->uf_cerosizquierda($this->li_pago_actual,9);
			$ls_numND = ' ';
			$ls_numNC = ' ';
			$ls_tipotrans = '01-reg';
			$ls_numfactafec = ' ';
			
			$ld_fecha = "1900/01/01";
			$ls_factura = 'N/D';
			$ls_nrocontrol = 'N/D';
			$ldec_total_p_isv = 0;
			$ldec_csdc = 0;
			$ldec_baseimponible = $ldec_mor;
			$ldec_porcimpuesto = 0;
			$ldec_totalimpuesto = 0;
			
			$ld_fecmov=$this->ds_pagos->getValue("fecmov",$this->li_pago_actual);
			$ld_fecmov=$this->io_function->uf_convertirdatetobd($ld_fecmov);			
			$ls_codban=$this->ds_pagos->getValue("codban",$this->li_pago_actual);
			$ls_ctaban=$this->ds_pagos->getValue("ctaban",$this->li_pago_actual);
			$ls_codope=$this->ds_pagos->getValue("codope",$this->li_pago_actual);
			$ls_numdoc=$this->ds_pagos->getValue("numdoc",$this->li_pago_actual);
			
			$ls_sql = "INSERT into scb_dt_cmp_ret(codemp,codret,numcom,numope,fecfac,numfac,numcon,numnd,numnc,tiptrans,totcmp_sin_iva,totcmp_con_iva,basimp,porimp,totimp,iva_ret,numsop,codban,ctaban,numdoc,codope,desope)
					   VALUES('".$ls_codemp."','".$this->ls_retid."','".$ls_nrocomp."','".$ls_operacion."','".$ld_fecmov."','".$ls_factura."','".$ls_nrocontrol."','".$ls_numND."','".$ls_numNC."','".$ls_tipotrans."',".$ldec_total_p_isv.",".$ldec_csdc.",".$ldec_baseimponible.",".$ldec_porc.",".$ldec_totalimpuesto.",".$ldec_ivaretenido.",'".$ls_numsol."','".$ls_codban."','".$ls_ctaban."','".$ls_numdoc."','".$ls_codope."','".$ls_concepto."')";
		
			$rs_result=$this->io_sql->execute($ls_sql);
			if($rs_result===false)
			{	
				$this->is_msg_error="Error en uf_ccr_guarda_inf_slc, ".$this->io_function->uf_convertirmsg($this->io_sql->message);
				print $this->io_sql->message;
				return false;
			}
			else
			{
				return true;
			}
		}
	
	}
	
	
	function uf_select_cmp_ret($as_numcom,$as_codret,&$object,&$li_total_rows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//
		//  Function:    uf_cargar_dt_cmp_ret
		//
		//	Description: - Metodo que se encarga de seleccionar los detalles de un comprobante de retencion y los retorna en 
		//				   un arreglo de Objects que sera enviado a la clase grid_param.	
		//
		//  Arguments:   - $as_numcom:    - Numero del comprobante de retenciónn a buscar.
		//				 - $as_codret:    - Codigo de la Retención a buscar.	
		//				 - $object:       - Arreglo de object que se retorna como referencia, lleno con los detalles del 
		//									comprobante en caso de poseer.
		//				 - $li_total_rows - Total de filas retornadas en la consulta.
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ds_temp=new class_datastore();
		$li_total_rows=0;
		$ls_codemp=$this->dat_emp["codemp"];
		
		
		$ls_origen=$this->uf_select_origen($as_codret,$as_numcom);
		$ls_sql="SELECT codemp, codret, numcom, numope, fecfac, numfac, numcon, numnd, 
				numnc, tiptrans, totcmp_sin_iva, totcmp_con_iva, basimp, porimp, totimp, 
				iva_ret, desope, numsop, codban, ctaban, numdoc, codope 
				 FROM scb_dt_cmp_ret 
				 WHERE codemp='".$ls_codemp."' AND numcom='".$as_numcom."' AND codret='".$as_codret."'";	
	//	print $ls_sql;
		$rs_result=$this->io_sql->select($ls_sql);
		
		if($rs_result===false)
		{
			$this->is_msg_error="Error en uf_cargar_dt_cmp_ret, ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_result))
			{
				$ds_temp->data=$this->io_sql->obtener_datos($rs_result);
			      //print_r($ds_temp->data);
				$li_total_rows=$ds_temp->getRowCount("numcom");
				for($li_i=1;$li_i<=$li_total_rows;$li_i++)
				{
					$ls_numope  = $ds_temp->getValue("numope",$li_i);	
					$ls_numfac  = $ds_temp->getValue("numfac",$li_i);	
					$ld_fecfac  = $ds_temp->getValue("fecfac",$li_i);	
					$ld_fecfac  = $this->io_function->uf_convertirfecmostrar($ld_fecfac);
					$ldec_totalsiniva= $ds_temp->getValue("totcmp_sin_iva",$li_i);	
					$ldec_totalconiva= $ds_temp->getValue("totcmp_con_iva",$li_i);	
					$ldec_baseimp    = $ds_temp->getValue("basimp",$li_i);	
					$ldec_porimp     = $ds_temp->getValue("porimp",$li_i);	
					$ldec_totimp     = $ds_temp->getValue("totimp",$li_i);
					$ldec_ivaret     = $ds_temp->getValue("iva_ret",$li_i);	
					$ls_numdoc  = $ds_temp->getValue("numdoc",$li_i);	
					//print "origen   '".$ls_origen."'";
					if($ls_origen=='M')
						$object[$li_i][1] = "<a href=\"javascript:aceptar('$as_numcom','$as_codret','$ls_numope','$ls_numfac');\">".$ls_numope."</a>";
					elseif($ls_origen=='A')
						$object[$li_i][1] = $ls_numope;
					$object[$li_i][2] = $ls_numfac;
					$object[$li_i][3] = $ld_fecfac;
					$object[$li_i][4] = number_format($ldec_totalsiniva,2,",","."); 
					$object[$li_i][5] = number_format($ldec_totalconiva,2,",",".");			
					$object[$li_i][6] = number_format($ldec_baseimp,2,",",".");			
					$object[$li_i][7] = number_format($ldec_porimp,2,",",".");			
					$object[$li_i][8] = number_format($ldec_totimp,2,",",".");							
					$object[$li_i][9] = number_format($ldec_ivaret,2,",",".");			
					$object[$li_i][10]= $ls_numdoc;			
					$object[$li_i][11]= "<div align=center><a href=javascript:uf_delete_dt('$ls_numope','$ls_numfac');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar Retención' width=15 height=15 border=0></a></div>";
	
				}
			}
		}	
	
	}
	
	
	function uf_cargar_dt_cmp_ret($as_numcom,$as_codret,$as_numope,$as_numfac)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//
		//  Function:    uf_cargar_dt_cmp_ret
		//
		//	Description: - Metodo que se encarga de seleccionar los detalles de un comprobante de retencion y los retorna en 
		//				   un arreglo de Objects que sera enviado a la clase grid_param.	
		//
		//  Arguments:   - $as_numcom:    - Numero del comprobante de retenciónn a buscar.
		//				 - $as_codret:    - Codigo de la Retención a buscar.	
		//				 - $as_numope:    - Numero de la operacion.
		//				 - $as_numfac:    - Numero de factura.	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		
		$li_total_rows=0;
		
		$ls_codemp=$this->dat_emp["codemp"];
		
		$ls_sql="SELECT codemp, codret, numcom, numope, fecfac, numfac, numcon, numnd, numnc, tiptrans, totcmp_sin_iva, totcmp_con_iva, basimp, porimp, totimp, iva_ret, desope, numsop, codban, ctaban, numdoc, codope 
				 FROM scb_dt_cmp_ret 
				 WHERE codemp='".$ls_codemp."' AND numcom='".$as_numcom."' AND codret='".$as_codret."' AND numope='".$as_numope."' AND numfac='".$as_numfac."'";	
		
		$rs_result=$this->io_sql->select($ls_sql);
		
		if($rs_result===false)
		{
			$this->is_msg_error="Error en uf_cargar_dt_cmp_ret, ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_result))
			{
				$this->ds_dt_cmpret->data=$this->io_sql->obtener_datos($rs_result);
				$li_total_rows=$this->ds_dt_cmpret->getRowCount("numcom");
			}
		}	
	}
	
	
	function uf_update_dt_cmp_ret($ls_codret,$ls_numcom,$ls_numope,$ld_fecfac,$ls_numfac,$ls_numcon,$ldec_totsiniva,$ldec_totconiva,$ldec_baseimp,$ldec_porimp,$ldec_totimp,$ldec_ivaret,$ls_numsol )
	{
		$ls_codemp=$this->dat_emp["codemp"];
		$ld_fecfac=$this->io_function->uf_convertirdatetobd($ld_fecfac);
		$ls_sql="UPDATE scb_dt_cmp_ret 
				 SET  fecfac='".$ld_fecfac."', numfac='".$ls_numfac."', numcon='".$ls_numcon."', 
				 totcmp_sin_iva=".$ldec_totsiniva.", totcmp_con_iva=".$ldec_totconiva.", 
				 basimp=".$ldec_baseimp.", porimp=".$ldec_porimp.", totimp=".$ldec_totimp.", 
				 iva_ret=".$ldec_ivaret." ,numsop=$ls_numsol
				 WHERE codemp='".$ls_codemp."' AND codret='".$ls_codret."' AND numcom='".$ls_numcom."' AND numope='".$ls_numope."'";
		
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$this->is_msg_error="Error en uf_update_dt_cmp_ret, ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->io_sql->message;
			return false;
		}
		else
		{
		  return true;
		}
	}
	
	function uf_delete_cmp_ret($ls_codret,$ls_numcom)
	{
		$ls_codemp=$this->dat_emp["codemp"];
		
		$ls_sql="DELETE FROM scb_dt_cmp_ret 
				 WHERE codemp='".$ls_codemp."' AND codret='".$ls_codret."' AND numcom='".$ls_numcom."'";
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$this->is_msg_error="Error en uf_delete_cmp_ret al eliminar detalle , ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->io_sql->message;
			return false;
		}
		else
		{
			$ls_sql="DELETE FROM scb_cmp_ret 
					 WHERE codemp='".$ls_codemp."' AND codret='".$ls_codret."' AND numcom='".$ls_numcom."'";
			$li_result=$this->io_sql->execute($ls_sql);
			if($li_result===false)
			{
				$this->is_msg_error="Error en uf_delete_cmp_ret al eliminar cabezera, ".$this->io_function->uf_convertirmsg($this->io_sql->message);
				print $this->io_sql->message;
				return false;
			}
			else
			{
			  return true;
			}
		}
	}
	
	
	function uf_delete_dt_cmp_ret($ls_codret,$ls_numcom,$ls_numope,$ls_numfac)
	{
		$ls_codemp=$this->dat_emp["codemp"];
		$ls_sql="DELETE FROM scb_dt_cmp_ret 
				 WHERE codemp='".$ls_codemp."' AND codret='".$ls_codret."' AND numcom='".$ls_numcom."' AND numope='".$ls_numope."'";
		
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$this->is_msg_error="Error en uf_delete_dt_cmp_ret, ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->io_sql->message;
			return false;
		}
		else
		{
		  return true;
		}
	}
	
	function uf_update_cmp_ret($ls_codret,$ls_numcom,$ls_codsujret,$ls_nomsujret,$ls_rifsujret,$ls_dirsujret)
	{
		$ls_codemp=$this->dat_emp["codemp"];
		
		$ls_sql="UPDATE scb_cmp_ret 
				 SET codsujret='".$ls_codsujret."',nomsujret='".$ls_nomsujret."',rif='".$ls_rifsujret."',dirsujret='".$ls_dirsujret."'
				 WHERE codemp='".$ls_codemp."' AND codret='".$ls_codret."' AND numcom='".$ls_numcom."'";
		
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$this->is_msg_error="Error en uf_update_dt_cmp_ret, ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->io_sql->message;
			return false;
		}
		else
		{
		  return true;
		}	
	}
	
	function uf_select_origen($ls_codret,$ls_numcom)
	{
	  /*----------------------------------------------------------------
	  		Funcion:   uf_select_origen
	  		Descripcion: metodo que retorna el origen de la cracion del
	  		comprobante de retencion municipal, es decir, si fue creada
	  		automatica o manualmente.
	  		Autora: Ing. Laura Cabré
	  		Fecha: 04/12/2006
	----------------------------------------------------------------------*/
	    $ls_codemp=$this->dat_emp["codemp"];
	    $ls_sql="SELECT origen 
				FROM scb_cmp_ret
				WHERE codemp='$ls_codemp' AND codret='$ls_codret' AND numcom='$ls_numcom'";
		//print $ls_sql;
		 $rs_result=$this->io_sql->select($ls_sql);
		
		if($rs_result===false)
		{
			$this->is_msg_error="Error en uf_select_origen, ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_result))
			{
				return $row["origen"];
			}
		}	
	}	
	
	function uf_determinar_ultimo($ls_periodofiscal,$ls_nrocomp,$ls_codret)
	{
	  /*----------------------------------------------------------------------------------------------
	  Funcion: uf_determinar_ultimo
	  Descripción: Funcion que permite determinar si el comprobante de retencion es el ultimo, retorna 1 
	  				si es el ultimo, 0 si no lo es, false si ocurrio un error o no existen registros. 
	  Autor: Ing. Laura Cabré
	  Fecha: 12/12/2006		 
	  ----------------------------------------------------------------------------------------------*/
	
		$ls_agno=substr($ls_periodofiscal,0,4);
		$ls_mes=substr($ls_periodofiscal,4,2);
		$ls_codemp=$this->dat_emp["codemp"];	
		$ls_sql="SELECT substr(numcom,7,8) as numcom
				 FROM   scb_cmp_ret 
				 WHERE  codemp='".$ls_codemp."' AND 
				 codret = '".$ls_codret."' AND perfiscal = '".$ls_periodofiscal."'
				 ORDER  by numcom desc ";
				 //print $ls_sql;		
		$rs_result=$this->io_sql->select($ls_sql);
		if($rs_result===false)
		{
			$this->is_msg_error=$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
			return false;			
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_result))
			{ 
			   $ls_codigo=$ls_periodofiscal.$row["numcom"];			  
			   if($ls_codigo==$ls_nrocomp)
			   		return 1;
			   else
			   		return 0;			   
		    }
		    else
		    {
			  return false;
		    }				
		}	
	}
	
	function uf_anular_comprobante($ls_codret,$ls_numcom)
	{
		$ls_codemp=$this->dat_emp["codemp"];
		
		$ls_sql="UPDATE scb_cmp_ret
				SET estcmpret=2   
				WHERE codemp='".$ls_codemp."' AND codret='".$ls_codret."' AND numcom='".$ls_numcom."'";
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$this->is_msg_error="Error en uf_anular_comprobante , ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->io_sql->message;
			return false;
		}
		else
		{
		  return true;
		}	
	}
}
?>