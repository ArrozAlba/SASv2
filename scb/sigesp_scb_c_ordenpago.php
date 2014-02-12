<?php
class sigesp_scb_c_ordenpago
{
	var $is_msg_error;
	var $io_sql;
	var $siginc;
	var $int_scg;
	var $int_spg;
	var $msg;
	var $fun;
	var $io_fecha;
	var $dat;
	var $la_security;
	var $io_seguridad;
	function sigesp_scb_c_ordenpago($aa_security)
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_fecha.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();		
		$this->siginc=new sigesp_include();
		$this->io_fecha=new class_fecha();
		$con=$this->siginc->uf_conectar();
		$this->io_sql=new class_sql($con);
		$this->is_msg_error="";
		$this->msg=new class_mensajes();
		$this->fun=new class_funciones();
		$this->dat=$_SESSION["la_empresa"];
		$this->la_security=$aa_security; 		
	}	
	
	function uf_generar_num_cmp($as_codemp,$ls_config_ordpag,$readonly_doc)
	{
		 $ls_sql="SELECT numdoc 
		 		  FROM scb_movbco 
				  WHERE codemp='".$as_codemp."' AND codope='OP' 
				  ORDER BY numdoc DESC";		
		  $rs_funciondb=$this->io_sql->select($ls_sql);
		  $li_len=strlen($ls_config_ordpag);

		  if ($row=$this->io_sql->fetch_row($rs_funciondb))
		  { 
			  $codigo=$row["numdoc"];
			  settype($codigo,'int');                             // Asigna el tipo a la variable.
			  $codigo = $codigo + 1;                              // Le sumo uno al entero.
			  settype($codigo,'string');                          // Lo convierto a varchar nuevamente.
			  $ls_codigo=$this->fun->uf_cerosizquierda($codigo,$li_len);
  			  $readonly_doc="readonly";
		  }
		  else
		  {
			  $codigo="1";
			  $ls_codigo=$this->fun->uf_cerosizquierda($codigo,$li_len);
			  $readonly_doc="";
		  }
		return $ls_codigo;
	}
	
	function uf_verificar_documento($as_codemp,$as_numdoc,$ad_fecha)
	{
		 $ls_sql="SELECT MAX(numdoc) as numdoc,MAX(fecmov) as fecmov
		 		  FROM scb_movbco 
				  WHERE codemp='".$as_codemp."' AND codope='OP' 
				  ORDER BY numdoc DESC";		
		  $rs_funciondb=$this->io_sql->select($ls_sql);
		  $li_len=strlen($ls_config_ordpag);

		  if ($row=$this->io_sql->fetch_row($rs_funciondb))
		  { 
			  $codigo=$row["numdoc"];
			  $fecmov=$row["fecmov"];
			  if($this->io_fecha->uf_comparar_fecha($fecmov,$ad_fecha))
			  {
			  	 $this->msg->message("Fecha invalida, hay un documento anterior con fecha superior ".$fecmov."  ".$ad_fecha);
				 return false;
			  }
			  if($codigo >= $as_numdoc)
			  {
			  	 $this->msg->message("Numero de documento Invalido, puede ser que \n sea menor a uno existente o que ya este registrado.".$codigo."  ".$as_numdoc);
				 return false;
			  }			  
		  }		 
		return true;
	}
	
	function uf_select_banco_cuenta($as_codemp,$ls_codban,$ls_nomban,$ls_ctaban,$ls_dencta,$ls_sc_cuenta)
	{
		 $ls_sql="  SELECT a.codban,a.nomban,b.ctaban,b.dencta,b.sc_cuenta
					FROM   scb_banco a,scb_ctabanco b
					WHERE  a.codemp ='".$as_codemp."' AND a.codemp=b.codemp AND a.codban=b.codban
					ORDER BY a.codban,b.ctaban DESC";		
		  $rs_funciondb=$this->io_sql->select($ls_sql);
		  if ($row=$this->io_sql->fetch_row($rs_funciondb))
		  { 
			  $ls_codban=$row["codban"];
 			  $ls_nomban=$row["nomban"];
  			  $ls_ctaban=$row["ctaban"];
  			  $ls_dencta=$row["dencta"];		 
			  $ls_sc_cuenta=$row["sc_cuenta"];
		  }
	}	
	
	function uf_select_movimiento($ls_numdoc,$ls_codope,$ls_codban,$ls_ctaban,$ls_estmov)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////
		//
		// -Funcion que verifica que el movimiento bancario no exista
		//
		///////////////////////////////////////////////////////////////////////////////////////////////
		
		$dat=$_SESSION["la_empresa"];
		$ls_codemp=$dat["codemp"];
		
		$ls_sql="SELECT numdoc,codope,estmov 
				 FROM   scb_movbco
				 WHERE  codemp='".$ls_codemp."' AND numdoc='".$ls_numdoc."' AND codope ='".$ls_codope."' ";
		
		$rs_mov=$this->io_sql->select($ls_sql);
		if(($rs_mov===false))
		{
			$this->is_msg_error="Error en select movimiento,".$this->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_mov))
			{
				return true;
			}
			else
			{
				return false;
			}	
		}			
	}
	
	function uf_guardar_automatico($ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ldt_fecha,$ls_conmov,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto,$ldec_monobjret,$ldec_monret,$ls_chevau,$ls_estmov,$li_estmovint,$li_cobrapaga,$ls_estbpd,$ls_procede,$ls_estreglib,$ls_estdoc,$ls_tipproben,$ls_tipdocres,$ls_numdocres,$ls_fecdocres,$ls_tipreg,$ls_fte_financiamiento,$ls_origen,$ls_tippag,$ls_mediopago,$ls_modalidad,$ls_coduniadm,$ls_codbansig,$ls_estpro,$ls_codbanbene,$ls_nombanbene,$ls_ctabanbene,$ls_codbanaut,$ls_nombanaut,$ls_ctabanaut,$ls_rifaut,$ls_nomaut,$ls_nrocontrol,$ai_estserext)
	{								
		////////////////////////////////////////////////////////////////////////////////////////////////
		//
		// -Funcion que procesa los datos de la cabecera del movimiento bancario
		//	validando que no exista y que el periodo este abierto.
		//
		///////////////////////////////////////////////////////////////////////////////////////////////
		
		$lb_valido=true;
		$dat=$_SESSION["la_empresa"];
		$ls_codemp=$dat["codemp"];
		$ls_codusu=$_SESSION["la_logusr"];
		if($this->io_fecha->uf_valida_fecha_periodo($ldt_fecha,$ls_codemp))
		{
			   if(!$this->uf_select_movimiento($ls_numdoc,$ls_codope,$ls_codban,$ls_ctaban,$ls_estmov))
			   {	
				   $this->io_sql->begin_transaction();
				   $lb_valido = $this->uf_insert_movimiento($ls_codemp,$ls_codusu,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ldt_fecha,$ls_conmov,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto,$ldec_monobjret,$ldec_monret,$ls_chevau,$ls_estmov,$li_estmovint,$li_cobrapaga,$ls_estbpd,$ls_procede,$ls_estreglib,$ls_tipproben,$ls_tipdocres,$ls_numdocres,$ls_fecdocres,$ls_tipreg,"--",$ls_origen,$ls_tippag,$ls_mediopago,$ls_modalidad,$ls_coduniadm,$ls_codbansig,$ls_estpro,$ls_nrocontrol,$ai_estserext);
				   if($lb_valido)
				   {
						$lb_valido = $this->uf_insert_fuentefinancimiento($ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_estmov,$ls_fte_financiamiento);
				   }
				   $ib_valido = $lb_valido;
				   if($lb_valido)
				   {
						$this->uf_insert_dt_ordenpago($ls_codemp,$ls_numdoc,$ls_codope,$ls_codban,$ls_ctaban,$ls_codpro,$ls_cedbene,$ls_tipproben,$ls_codbanbene,$ls_nombanbene,$ls_ctabanbene,$ls_codbanaut,$ls_nombanaut,$ls_ctabanaut,$ls_rifaut,$ls_nomaut);
						$ib_new = false;
				   }	
			   }
			   elseif($ls_estdoc=='C')
			   {
					
					$lb_valido=true;
					$lb_valido=$this->uf_update_movimiento($ls_codemp,$ls_codusu,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ldt_fecha,$ls_conmov,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto,$ldec_monobjret,$ldec_monret,$ls_chevau,$ls_estmov,$li_estmovint,$li_cobrapaga,$ls_estbpd,$ls_procede,$ls_estreglib,$ls_coduniadm,$ls_estpro,$ls_tipproben,$ai_estserext);
			   }
			   else
			   {
					$lb_valido=false;   
					$this->is_msg_error="El numero de documento ya existe";
			   }			   
		}
		else
		{
			$this->msg->message($this->io_fecha->is_msg_error);
		}	
		return $lb_valido;
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function:  uf_insert_dt_ordenpago
	// 	   Access:  public
	//  Arguments:  
	//	  Returns:  Boolean
	//Description:  Este método verifica si el cheque existe en la chequera y cambia el status si se utilizo en le moimiento actual
	////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_insert_dt_ordenpago($ls_codemp,$ls_numdoc,$ls_codope,$ls_codban,$ls_ctaban,$ls_codpro,$ls_cedbene,$ls_tipproben,$ls_codbanbene,$ls_nombanbene,$ls_ctabanbene,$ls_codbanaut,$ls_nombanaut,$ls_ctabanaut,$ls_rifaut,$ls_nomaut)
	{
	
		$ls_sql="INSERT INTO scb_dt_op(       codemp   ,     codban   ,       ctaban     ,       numdoc   ,
											   codope   ,    cod_pro   ,       ced_bene   ,   tipo_destino ,  estmov    , 
											   nombenaut, rifbenaut, codbanaut, ctabanaut, nombanaut, codbanbene, nombanbene, ctabanbene)
				 VALUES                ('".$ls_codemp."','".$ls_codban."','".$ls_ctaban."','".$ls_numdoc."',
										'".$ls_codope."','".$ls_codpro."','".$ls_cedbene."','".$ls_tipproben."','N',
										'".$ls_nomaut."','".$ls_rifaut."','".$ls_codbanaut."','".$ls_ctabanaut."','".$ls_nombanaut."','".$ls_codbanbene."','".$ls_nombanbene."','".$ls_ctabanbene."')";
	
		$li_result=$this->io_sql->execute($ls_sql);	
		if(($li_result===false))
		{
			$this->is_msg_error="Fallo insercion de movimiento detalle de orden de pago, ".$this->fun->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			$this->is_msg_error="El movimiento fue registrado";
			///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion="Inserto el movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
			$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			return true;		
		}	
	}
							 
function uf_insert_movimiento($ls_codemp,$ls_codusu,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ldt_fecha,$ls_conmov,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto,$ldec_monobjret,$ldec_monret,$ls_chevau,$ls_estmov,$li_estmovint,$li_cobrapaga,$ls_estbpd,$ls_procede,$ls_estreglib,$ls_tipproben,$ls_tipdocres,$ls_numdocres,$ls_fecdocres,$ls_tipreg,$ls_fte_financiamiento,$ls_origen,$ls_tippag,$ls_mediopago,$ls_modalidad,$ls_coduniadm,$ls_codbansig,$ls_estpro,$ls_nrocontrol,$ai_estserext)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que inserta la cabecera del movimiento  bancario
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$ldt_fecha=$this->fun->uf_convertirdatetobd($ldt_fecha);
	$ls_fecdocres=$this->fun->uf_convertirdatetobd($ls_fecdocres);
	if (empty($ls_fecdocres))
	   {
	     $ls_fecdocres = '1900-01-01';
	   }
	if (empty($ls_fecdocres))
	   {
	     $ldt_fecha = '1900-01-01';
	   }
	$ls_sql="INSERT INTO scb_movbco(codemp,codusu,codban,ctaban,numdoc,codope,fecmov,conmov,codconmov,cod_pro,ced_bene,nomproben,
	                                 monto,monobjret,monret,chevau,estmov,estmovint,estcobing,esttra,estbpd,estcon,feccon,estreglib,
									 tipo_destino,fecha,procede,coduniadmsig,codbansig,fecordpagsig,tipdocressig,numdocressig,
									 estmodordpag,codfuefin,forpagsig,medpagsig,codestprosig,nrocontrolop,numordpagmin,codtipfon,estserext)
			 VALUES                ('".$ls_codemp."','".$ls_codusu."','".$ls_codban."','".$ls_ctaban."','".$ls_numdoc."',
			  						'".$ls_codope."','".$ldt_fecha."','".$ls_conmov."','".$ls_codconmov."','".$ls_codpro."',
									'".$ls_cedbene."','".$ls_nomproben."',".$ldec_monto.",".$ldec_monobjret.",".$ldec_monret.",
									'".$ls_chevau."','".$ls_estmov."',".$li_estmovint.",".$li_cobrapaga.",0,'".$ls_estbpd."',
									0,'1900-01-01','".$ls_estreglib."','".$ls_tipproben."','1900-01-01','SCBBCH',
									'".$ls_coduniadm."','".$ls_codbansig."','".$ls_fecdocres."','".$ls_tipdocres."','".$ls_numdocres."',
									'".$ls_modalidad."','".$ls_fte_financiamiento."','".$ls_tippag."','".$ls_mediopago."','".$ls_estpro."','".$ls_nrocontrol."','-','----','".$ai_estserext."')";

	$li_result=$this->io_sql->execute($ls_sql);

	if(($li_result===false))
	{
		$this->is_msg_error="Fallo insercion de movimiento, ".$this->fun->uf_convertirmsg($this->io_sql->message);
		print $this->io_sql->message;
		return false;
	}
	else
	{
		$this->is_msg_error="El movimiento Bancario fue registrado";
		///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
		$ls_evento="INSERT";
		$ls_descripcion="Inserto el movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban." por un monto de ".$ldec_monto;
        $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		return true;		
	}
	
}

function uf_update_movimiento($ls_codemp,$ls_codusu,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ldt_fecha,$ls_conmov,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto,$ldec_monobjret,$ldec_monret,$ls_chevau,$ls_estmov,$li_estmovint,$li_cobrapaga,$ls_estbpd,$ls_procede,$ls_estreglib,$ls_coduniadm,$ls_codestpro,$ls_tipproben,$ai_estserext)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que inserta la cabecera del movimiento  bancario
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$ldt_fecha=$this->fun->uf_convertirdatetobd($ldt_fecha);
	$ls_sql="UPDATE scb_movbco SET conmov='".$ls_conmov."',codconmov='".$ls_codconmov."',cod_pro='".$ls_codpro."',ced_bene='".$ls_cedbene."',nomproben='".$ls_nomproben."',monto='".$ldec_monto."',monobjret='".$ldec_monobjret."',monret='".$ldec_monret."',coduniadmsig='".$ls_coduniadm."',codestprosig='".$ls_codestpro."',tipo_destino='$ls_tipproben',estserext='".$ai_estserext."'
			 WHERE codemp='".$ls_codemp."' 
			   AND codban='".$ls_codban."' 
			   AND ctaban='".$ls_ctaban."' 
			   AND numdoc='".$ls_numdoc."' 
			   AND codope='".$ls_codope."'";

	$li_result=$this->io_sql->execute($ls_sql);
	if($li_result===false)
	{
		$this->is_msg_error=" Fallo Actualizacion de movimiento, ".$this->fun->uf_convertirmsg($this->io_sql->message);
		return false;
	}
	else
	{
		$this->is_msg_error="El movimiento Bancario fue Actualizado";
		///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
		$ls_evento="UPDATE";
		$ls_descripcion="Actualizo el movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban." por un monto de ".$ldec_monto;
        $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);		////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		return true;
	}
	
}

function uf_select_dt_contable($arr_movbco,$ls_cuenta,$ls_procede,$ls_descripcion,$ls_documento,$ls_operacioncon,$ldec_monto,$ldec_actual,$ls_codded)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que verifica si existe el movimiento contable
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];
    $ls_codban     = $arr_movbco["codban"];
 	$ls_ctaban     = $arr_movbco["ctaban"];
	$ls_numdoc     = $arr_movbco["mov_document"];
	$ls_codope     = $arr_movbco["codope"];
	$ls_estmov	   = $arr_movbco["estmov"];
	$ldec_monobjret= $arr_movbco["objret"];	 
	
	$ls_sql="SELECT monto 
			   FROM scb_movbco_scg
			  WHERE codemp='".$ls_codemp."' 
			    AND codban='".$ls_codban."'
				AND ctaban='".$ls_ctaban."'
				AND numdoc='".$ls_numdoc."' 
			    AND codope='".$ls_codope."'
				AND estmov='".$ls_estmov."'
				AND scg_cuenta='".$ls_cuenta."' 
			    AND debhab='".$ls_operacioncon."' 
				AND codded='".$ls_codded."'
				AND documento='".$ls_documento."'";
	
	$rs_dt_scg=$this->io_sql->select($ls_sql);

	if(($rs_dt_scg===false))
	{
		$this->is_msg_error="Error en select detalle contable ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido=false;
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_dt_scg))
		{
			$lb_valido=true;
			$ldec_actual=$row["monto"];
		}
		else
		{
			$lb_valido=false;
		}
	}	
	return $lb_valido;
}

function  uf_procesar_dt_contable($arr_movbco,$ls_cuenta,$ls_procede,$ls_descripcion,$ls_documento,$ls_operacioncon,$ldec_monto,$ldec_objret,$lb_mov_mandatory,$ls_codded)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que inserta el detalle contable del movimiento 
	//
	///////////////////////////////////////////////////////////////////////////////////////////////	
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];
    $ls_codban = $arr_movbco["codban"];
 	$ls_ctaban = $arr_movbco["ctaban"];
	$ls_numdoc = $arr_movbco["mov_document"];
	$ls_codope = $arr_movbco["codope"];
	$ls_estmov = $arr_movbco["estmov"];

	$lb_valido=$this->uf_select_dt_contable($arr_movbco,$ls_cuenta,$ls_procede,$ls_descripcion,$ls_documento,$ls_operacioncon,$ldec_monto,&$ldec_actual,$ls_codded);
	
	if(!$lb_valido)
	{
			$ls_sql="INSERT INTO scb_movbco_scg(codemp,codban,ctaban,numdoc,codope,estmov,scg_cuenta,debhab,documento,codded,desmov,procede_doc,monto,monobjret)
					 VALUES ('".$ls_codemp."','".$ls_codban."','".$ls_ctaban."','".$ls_numdoc."','".$ls_codope."','".$ls_estmov."','".$ls_cuenta."','".$ls_operacioncon."','".$ls_documento."','".$ls_codded."','".$ls_descripcion."','".$ls_procede."',".$ldec_monto.",".$ldec_objret.")";

			$li_result=$this->io_sql->execute($ls_sql);	
			
			if(($li_result===false))
			{
				$this->is_msg_error="Error al procesar detalle contable, ".$this->fun->uf_convertirmsg($this->io_sql->message);
				$lb_valido=false;			
			}
			else
			{
				$lb_valido=true;
				///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
				$ls_evento="INSERT";
				$ls_descripcion="Inserto el detalle contable a la cuenta ".$ls_cuenta." por un monto de ".$ldec_monto." para el movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
                $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);				////////////////////////////////////////////////////////////////////////////////////////////////////////////
			}
	
	}
	else
	{
		$ldec_monto=$ldec_monto+$ldec_actual;
		$ls_sql="UPDATE scb_movbco_scg SET monto=".$ldec_monto." 
				 WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
				 AND numdoc='".$ls_numdoc."' AND codope='".$ls_codope."' AND estmov='".$ls_estmov."' 
				 AND scg_cuenta='".$ls_cuenta."' AND debhab='".$ls_operacioncon."' AND codded='".$ls_codded."' AND documento='".$ls_documento."'";

		if(($lb_valido)&&(!$lb_mov_mandatory))
		{
			$li_result=$this->io_sql->execute($ls_sql);	

			if(($li_result===false))
			{
				$this->is_msg_error="Error al procesar detalle contable, ".$this->fun->uf_convertirmsg($this->io_sql->message);
				$lb_valido=false;			
			}
			else
			{
				$lb_valido=true;
				///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion="Actualizo el detalle contable a la cuenta ".$ls_cuenta." por un monto de ".$ldec_monto." para el movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
                $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);				////////////////////////////////////////////////////////////////////////////////////////////////////////////				
			}
		}		
	}
	return $lb_valido;
}

function uf_update_monto_mov($arr_movbco,$ls_cuenta,$ls_procede,$ls_descripcion,$ls_documento,$ls_operacioncon,$ldec_monto,$ldec_objret,$ls_codded)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que actualiza el monto de un movimiento cuando se selecciona la misma cuenta
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
		
		$dat=$_SESSION["la_empresa"];
		$ls_codemp=$dat["codemp"];
		$ls_codban = $arr_movbco["codban"];
 		$ls_ctaban = $arr_movbco["ctaban"];
		$ls_numdoc = $arr_movbco["mov_document"];
		$ls_codope = $arr_movbco["codope"];
		$ls_estmov = $arr_movbco["estmov"];
		
		$ls_sql="UPDATE scb_movbco_scg SET monto=".$ldec_monto." 
				 WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
				 AND numdoc='".$ls_numdoc."' AND codope='".$ls_codope."' AND estmov='".$ls_estmov."' 
				 AND scg_cuenta='".$ls_cuenta."' AND debhab='".$ls_operacioncon."' AND codded='".$ls_codded."' AND documento='".$ls_documento."'";

		$li_result=$this->io_sql->execute($ls_sql);	

		if($li_result===false)
		{
			$this->is_msg_error="Error al procesar detalle contable, ".$this->fun->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;			
		}
		else
		{
			$lb_valido=true;			
			///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion="Actualizo el detalle contable a la cuenta ".$ls_cuenta." por un monto de ".$ldec_monto." para el movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
            $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);			////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}


	return $lb_valido;
}

function uf_update_montodelete($arr_movbco,$ls_cuenta,$ls_procede,$ls_descripcion,$ls_documento,$ls_operacioncon,$ldec_monto,$ldec_objret,$ls_codded)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que actualiza el monto del movimiento padre cuando se elimina una retencion
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
		
		$dat	   = $_SESSION["la_empresa"];
		$ls_codemp = $dat["codemp"];
		$ls_codban = $arr_movbco["codban"];
 		$ls_ctaban = $arr_movbco["ctaban"];
		$ls_numdoc = $arr_movbco["mov_document"];
		$ls_codope = $arr_movbco["codope"];
		$ls_estmov = $arr_movbco["estmov"];
		
		$ls_sql="UPDATE scb_movbco_scg SET monto=monto + ".$ldec_monto." 
				 WHERE codemp='".$ls_codemp."' 
				   AND codban='".$ls_codban."' 
				   AND ctaban='".$ls_ctaban."' 
				   AND numdoc='".$ls_numdoc."' 
				   AND codope='".$ls_codope."' 
				   AND estmov='".$ls_estmov."' 
				   AND scg_cuenta='".$ls_cuenta."' 
				   AND debhab='".$ls_operacioncon."' 
				   AND codded='".$ls_codded."' 
				   AND documento='".$ls_documento."'";
		
		$li_result=$this->io_sql->execute($ls_sql);	
		if(($li_result===false))
		{
			$this->is_msg_error="Error al procesar detalle contable, ".$this->fun->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;			
		}
		else
		{
			$this->uf_update_montos_auxiliares_movbco_scg($ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_estmov,$ls_cuenta,$ls_operacioncon,$ls_codded,$ls_documento);
			$lb_valido=true;
			///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion="Actualizo el detalle contable a la cuenta ".$ls_cuenta." por un monto de ".$ldec_monto." para el movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
            $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);			////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}

	return $lb_valido;
}

function uf_select_dt_gasto($ls_codban,$ls_ctaban,$ls_numdoc,$as_codope,$ls_estmov,$ls_programa,$ls_spgcuenta,$ls_documento,$as_estcla)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que verifica si existe el movimiento contable
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];
   	
	$ls_sql="SELECT monto 
			   FROM scb_movbco_spgop
			  WHERE codemp='".$ls_codemp."' 
			    AND codban='".$ls_codban."' 
				AND ctaban='".$ls_ctaban."' 
			    AND numdoc='".$ls_numdoc."' 
				AND codope='".$as_codope."' 
				AND estmov='".$ls_estmov."' 
			    AND spg_cuenta='".$ls_spgcuenta."' 
				AND codestpro='".$ls_programa."' 
				AND documento='".$ls_documento."'
				AND estcla='".$as_estcla."'";
	$rs_dt_scg=$this->io_sql->select($ls_sql);

	if(($rs_dt_scg===false))
	{
		$this->is_msg_error="Error en select detalle Presupuestario de gasto ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido=false;
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_dt_scg))
		{
			$lb_valido=true;
			$ldec_actual=$row["monto"];
		}
		else
		{
			$lb_valido=false;
		}
	}	
	return $lb_valido;
}


function uf_procesar_dt_gasto($ls_codban,$ls_ctaban,$ls_numdoc,$as_codope,$ls_estmov,$ls_programa,$ls_spgcuenta,$ls_documento,$ls_desmov,$ls_procededoc,$ldec_monto,$ls_operacion,$ls_coduniadm,$ldec_baseimp,$ls_codcar,$as_estcla)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que inserta el detalle presupuestario del movimiento 
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];	

	$lb_existe=$this->uf_select_dt_gasto($ls_codban,$ls_ctaban,$ls_numdoc,$as_codope,$ls_estmov,$ls_programa,$ls_spgcuenta,$ls_documento,$as_estcla);
	if(!$lb_existe)
	{
		$ls_sql="INSERT INTO scb_movbco_spgop(codemp,codban,ctaban,numdoc,codope,estmov,codestpro,spg_cuenta,documento,desmov,procede_doc,monto,operacion,coduniadm,baseimp,codcar,estcla)
				 VALUES ('".$ls_codemp."','".$ls_codban."','".$ls_ctaban."','".$ls_numdoc."','".$as_codope."','".$ls_estmov."','".$ls_programa."','".$ls_spgcuenta."','".$ls_documento."','".$ls_desmov."','".$ls_procededoc."',".$ldec_monto.",'".$ls_operacion."','".$ls_coduniadm."','".$ldec_baseimp."','".$ls_codcar."','".$as_estcla."')";
		$ls_evento="INSERT";
		$this->is_msg_error="Registro Insertado";
		$ls_descripcion="Inserto el detalle presupuestario a la cuenta ".$ls_spgcuenta." asociado a la programatica ".$ls_programa."  por un monto de ".$ldec_monto." para el movimiento bancario de operacion ".$as_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
	}
	else
	{
		$ls_sql="UPDATE scb_movbco_spgop 
				    SET monto=monto+".$ldec_monto.",baseimp='".$ldec_baseimp."'
				  WHERE codemp='".$ls_codemp."' 
				    AND codban='".$ls_codban."' 
				    AND ctaban='".$ls_ctaban."' 
				    AND numdoc='".$ls_numdoc."'
				    AND codope='".$as_codope."' 
				    AND estmov='".$ls_estmov."' 
				    AND codestpro='".$ls_programa."' 
				    AND spg_cuenta='".$ls_spgcuenta."' 
				    AND documento='".$ls_documento."' 
				    AND coduniadm='".$ls_coduniadm."'
					AND estcla='".$as_estcla."'";
		$ls_evento="UPDATE";
		$this->is_msg_error="Registro Actualizado";
		$ls_descripcion="Actualizo el detalle presupuestario a la cuenta ".$ls_spgcuenta." asociado a la programatica ".$ls_programa."  por un monto de ".$ldec_monto." para el movimiento bancario de operacion ".$as_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
	}
	$li_result=$this->io_sql->execute($ls_sql);
	
	if(($li_result===false))
	{
		$this->is_msg_error="Error al guardar detalle de gasto, ".$this->fun->uf_convertirmsg($this->io_sql->message);	
		$lb_valido=false;
		echo $this->io_sql->message;
	}
	else
	{
	  if (!$lb_existe)
		 {
		   $la_seguridad="";
		 }
	  else
		 {
		   $this->uf_update_montos_auxiliares_scb_movbco_spgop($ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$as_codope,$ls_estmov,$ls_programa,$ls_spgcuenta,$ls_documento,$ls_coduniadm,$as_estcla);
		 }
	  $lb_valido=true;
	  ///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
      $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);		////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	return $lb_valido;
}

function uf_cargar_dt($as_numdoc,$as_codban,$as_ctaban,$as_codope,$ls_estmov,$objectScg,$li_row_scg,$ldec_mondeb,$ldec_monhab,$objectSpg,$li_temp_spg,$ldec_monto_spg,$objectSpi,$li_temp_spi,$ldec_monto_spi)
{
		////////////////////////////////////////////////////////////////////////////////////////////////
		//
		// -Funcion que carga todos los detalles del movimiento de banco en los object 
		//	requeridos por la clase grid_param.
		//
		///////////////////////////////////////////////////////////////////////////////////////////////
		
		$li_row_scg=0;
		$li_temp_spg=0;
		$li_temp_spi=0;
		$li_temp_ret=0;
		$dat=$_SESSION["la_empresa"];
		$ls_codemp=$dat["codemp"];
		$ls_sql="SELECT codban,ctaban,codope,estmov,scg_cuenta,codded,debhab,documento,desmov,procede_doc,monto,monobjret
				   FROM scb_movbco_scg
				  WHERE codemp='".$ls_codemp ."' 
				    AND numdoc ='".$as_numdoc."' 
					AND codban='".$as_codban."' 
					AND ctaban='".$as_ctaban."' 
					AND codope='".$as_codope."' 
					AND estmov='".$ls_estmov."'	
				 ORDER BY debhab asc,numdoc asc";
				 
		$rs_scg=$this->io_sql->select($ls_sql);		
		if(($rs_scg===false))
		{
			$this->is_msg_error="Error en inserción, ".$this->fun->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_scg))
			{
					$li_row_scg     = $li_row_scg+1;
					$ls_cuenta      = trim($row["scg_cuenta"]);
					$ls_documento   = $row["documento"];
					$ls_descripcion = $row["desmov"];
					$ls_procede     = trim($row["procede_doc"]);
					$ls_debhab      = trim($row["debhab"]);
					$ldec_monto     = $row["monto"];
					if($ls_debhab=="D")
					{
						$ldec_mondeb=$ldec_mondeb+$ldec_monto;
					}
					else
					{
						$ldec_monhab=$ldec_monhab+$ldec_monto;
					}
					$ls_codded=$row["codded"];
					$objectScg[$li_row_scg][1] = "<input type=text name=txtcontable".$li_row_scg." id=txtcontable".$li_row_scg."  value='".$ls_cuenta."' class=sin-borde readonly style=text-align:center size=15 maxlength=25>";		
					$objectScg[$li_row_scg][2] = "<input type=text name=txtdocscg".$li_row_scg."    value='".$ls_documento."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
					$objectScg[$li_row_scg][3] = "<input type=text name=txtdesdoc".$li_row_scg."    value='".$ls_descripcion."'  title='".$ls_descripcion."'  class=sin-borde readonly style=text-align:left size=30 maxlength=254>";
					$objectScg[$li_row_scg][4] = "<input type=text name=txtprocdoc".$li_row_scg."   value='".$ls_procede."' class=sin-borde readonly style=text-align:center size=8 maxlength=6>";
					$objectScg[$li_row_scg][5] = "<input type=text name=txtdebhab".$li_row_scg."    value='".$ls_debhab."' class=sin-borde readonly style=text-align:center size=8 maxlength=1>"; 
					$objectScg[$li_row_scg][6] = "<input type=text name=txtmontocont".$li_row_scg." value='".number_format($ldec_monto,2,",",".")."' class=sin-borde readonly style=text-align:right size=16 maxlength=22>";
					$objectScg[$li_row_scg][7] = "<input type=text name=txtcodded".$li_row_scg." value='".$ls_codded."' class=sin-borde readonly style=text-align:right size=5 maxlength=5>";
					$objectScg[$li_row_scg][8] = "<a href=javascript:uf_delete_Scg('".$li_row_scg."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle contable' width=15 height=15 border=0></a>";
					
			}
			
			if($li_row_scg==0)		
			{
				$li_row_scg=0;
				$objectScg[$li_row_scg][1] = "<input type=text name=txtcontable".$li_row_scg." id=txtcontable".$li_row_scg."  value='' class=sin-borde readonly style=text-align:center size=15 maxlength=25>";		
				$objectScg[$li_row_scg][2] = "<input type=text name=txtdocscg".$li_row_scg."    value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
				$objectScg[$li_row_scg][3] = "<input type=text name=txtdesdoc".$li_row_scg."    value='' class=sin-borde readonly style=text-align:left size=30 maxlength=254>";
				$objectScg[$li_row_scg][4] = "<input type=text name=txtprocdoc".$li_row_scg."   value='' class=sin-borde readonly style=text-align:center size=8 maxlength=6>";
				$objectScg[$li_row_scg][5] = "<input type=text name=txtdebhab".$li_row_scg."    value='' class=sin-borde readonly style=text-align:center size=8 maxlength=1>"; 
				$objectScg[$li_row_scg][6] = "<input type=text name=txtmontocont".$li_row_scg." value='' class=sin-borde readonly style=text-align:right size=16 maxlength=22>";
				$objectScg[$li_row_scg][7] = "<input type=text name=txtcodded".$li_row_scg." value='' class=sin-borde readonly style=text-align:right size=5 maxlength=5>";
				$objectScg[$li_row_scg][8] = "<a href=javascript:uf_delete_Scg('".$li_row_scg."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle contable' width=15 height=15 border=0></a>";
			
			}
			$this->io_sql->free_result($rs_scg);
		}		 


		$ls_sql="SELECT codban,ctaban,estmov,operacion,codestpro,spg_cuenta,documento,desmov,procede_doc,monto,baseimp,coduniadm,codcar,estcla
				   FROM scb_movbco_spgop
        		  WHERE codemp='".$ls_codemp."' 
				    AND numdoc ='".$as_numdoc."' 
					AND codban='".$as_codban."' 
				    AND ctaban='".$as_ctaban."' 
					AND codope='".$as_codope."' 
					AND estmov='".$ls_estmov."'
		  	     ORDER BY numdoc ASC";

		$rs_spg=$this->io_sql->select($ls_sql);		
		if(($rs_spg===false))
		{
			$this->is_msg_error="Error en inserción, ".$this->fun->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{

			while($row=$this->io_sql->fetch_row($rs_spg))
			{
				$li_temp_spg      = $li_temp_spg+1;
				$ls_cuenta        = $row["spg_cuenta"];
				$ls_programatica  = $row["codestpro"];
				$ls_documento     = $row["documento"];
				$ls_descripcion   = $row["desmov"];
				$ls_procede       = $row["procede_doc"];
				$ls_operacion_spg = $row["operacion"];
				$ldec_monto       = $row["monto"];
				$ldec_baseimp     = $row["baseimp"];
	         	$ls_codcar        = $row["codcar"];
				$ls_estcla        = $row["estcla"];
				$ls_estmodest     = $_SESSION["la_empresa"]["estmodest"];
				
				$li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
				$li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
				$li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
				$li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
				$li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
				
				$ls_codestpro1 = trim(substr(substr($row["codestpro"],0,25),-$li_loncodestpro1));
				$ls_codestpro2 = trim(substr(substr($row["codestpro"],25,25),-$li_loncodestpro2));
				$ls_codestpro3 = trim(substr(substr($row["codestpro"],50,25),-$li_loncodestpro3));

				if($ls_estmodest==2)
				{
					$ls_denestcla="";
					$ls_codestpro4   = trim(substr(substr($row["codestpro"],75,25),-$li_loncodestpro4));
					$ls_codestpro5   = trim(substr(substr($row["codestpro"],100,25),-$li_loncodestpro5));
					$ls_programatica = $ls_codestpro1."-".$ls_codestpro2."-".$ls_codestpro3."-".$ls_codestpro4."-".$ls_codestpro5;
				}
				else
				{
					if ($ls_estcla=='P')
					   {
					     $ls_denestcla = 'Proyecto';
					   }
					elseif($ls_estcla=='A')
					   {
					     $ls_denestcla = 'Acción Centralizada';
					   }
					$ls_programatica=$ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
				}
				$ldec_monto_spg=$ldec_monto_spg+$ldec_monto;
				$objectSpg[$li_temp_spg][1]  = "<input type=text name=txtcuenta".$li_temp_spg."       id=txtcuenta".$li_temp_spg."       value='".$ls_cuenta."'       class=sin-borde readonly style=text-align:center size=9 maxlength=25><input type=hidden name=hidestcla".$li_temp_spg." id=hidestcla".$li_temp_spg." value='".$ls_estcla."'>";
				$objectSpg[$li_temp_spg][2]  = "<input type=text name=txtprogramatico".$li_temp_spg." id=txtprogramatico".$li_temp_spg." value='".$ls_programatica."' title='".$ls_programatica.'-'.$ls_denestcla."' class=sin-borde readonly style=text-align:center size=30 maxlength=125 >"; 
				$objectSpg[$li_temp_spg][3]  = "<input type=text name=txtdocumento".$li_temp_spg."    id=txtdocumento".$li_temp_spg."    value='".$ls_documento."'    class=sin-borde readonly style=text-align:center size=13 maxlength=15>";
				$objectSpg[$li_temp_spg][4]  = "<input type=text name=txtdescripcion".$li_temp_spg."  id=txtdescripcion".$li_temp_spg."  value='".$ls_descripcion."' title='".$ls_descripcion."'  class=sin-borde readonly style=text-align:left>";
				$objectSpg[$li_temp_spg][5]  = "<input type=text name=txtprocede".$li_temp_spg."      id=txtprocede".$li_temp_spg."      value='".$ls_procede."'      class=sin-borde readonly style=text-align:center size=5 maxlength=6>";
				$objectSpg[$li_temp_spg][6]  = "<input type=text name=txtoperacion".$li_temp_spg."    id=txtoperacion".$li_temp_spg."    value='".$ls_operacion_spg."'    class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
				$objectSpg[$li_temp_spg][7]  = "<input type=text name=txtmonto".$li_temp_spg."        id=txtmonto".$li_temp_spg."        value='".number_format($ldec_monto,2,",",".")."'      class=sin-borde readonly style=text-align:right size=15 maxlength=19><input type=hidden name=txtmonobjret".$li_temp_spg." id=txtmonobjret".$li_temp_spg." value='".$ldec_baseimp."' ><input type=hidden name=txtcodcar".$li_temp_spg."        id=txtcodcar".$li_temp_spg."        value='".$ls_codcar."' >";		
				$objectSpg[$li_temp_spg][8]  = "<a href=javascript:uf_delete_Spg('".$li_temp_spg."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle Presupuestario de Gasto' width=15 height=15 border=0></a>";	
			}
			if($li_temp_spg==0)
			{
				$li_temp_spg=0;
				$objectSpg[$li_temp_spg][1]  = "<input type=text name=txtcuenta".$li_temp_spg."       id=txtcuenta".$li_temp_spg."       value='' class=sin-borde readonly style=text-align:center size=9 maxlength=25>";
				$objectSpg[$li_temp_spg][2]  = "<input type=text name=txtprogramatico".$li_temp_spg." id=txtprogramatico".$li_temp_spg." value='' class=sin-borde readonly style=text-align:center size=30 maxlength=125>"; 
				$objectSpg[$li_temp_spg][3]  = "<input type=text name=txtdocumento".$li_temp_spg."    id=txtdocumento".$li_temp_spg."    value='' class=sin-borde readonly style=text-align:center size=13 maxlength=15>";
				$objectSpg[$li_temp_spg][4]  = "<input type=text name=txtdescripcion".$li_temp_spg."  id=txtdescripcion".$li_temp_spg."  value='' class=sin-borde readonly style=text-align:left>";
				$objectSpg[$li_temp_spg][5]  = "<input type=text name=txtprocede".$li_temp_spg."      id=txtprocede".$li_temp_spg."      value='' class=sin-borde readonly style=text-align:center size=5 maxlength=6>";
				$objectSpg[$li_temp_spg][6]  = "<input type=text name=txtoperacion".$li_temp_spg."    id=txtoperacion".$li_temp_spg."    value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
				$objectSpg[$li_temp_spg][7]  = "<input type=text name=txtmonto".$li_temp_spg."        id=txtmonto".$li_temp_spg."        value='' class=sin-borde readonly style=text-align:right size=15 maxlength=19>";		
				$objectSpg[$li_temp_spg][8]  = "<a href=javascript:uf_delete_Spg('".$li_temp_spg."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle Presupuestario de Gasto' width=15 height=15 border=0></a>";	
			}
			$this->io_sql->free_result($rs_spg);
		}
		
		$ls_sql="SELECT   codban,ctaban,estmov,operacion,spi_cuenta,documento,desmov,procede_doc,monto
				 FROM     scb_movbco_spi
        		 WHERE    codemp='".$ls_codemp."' AND numdoc ='".$as_numdoc."' and codban='".$as_codban."' and ctaban='".$as_ctaban."' and codope='".$as_codope."' AND estmov='".$ls_estmov."'
				 ORDER BY numdoc asc";
		$rs_spi=$this->io_sql->select($ls_sql);		
		if(($rs_spi===false))
		{
			$this->is_msg_error="Error en select, ".$this->fun->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_spi))
			{
				$li_temp_spi      = $li_temp_spi+1;
				$ls_cuenta        = $row["spi_cuenta"];
				$ls_descripcion   = $row["desmov"];
				$ls_procede       = $row["procede_doc"];
				$ls_documento     = $row["documento"];
				$ls_operacion_spi = $row["operacion"];
				$ldec_monto=$row["monto"];
				$objectSpi[$li_temp_spi][1]  = "<input type=text name=txtcuentaspi".$li_temp_spi." value='".$ls_cuenta."' class=sin-borde readonly style=text-align:center size=6 maxlength=25>";
				$objectSpi[$li_temp_spi][2]  = "<input type=text name=txtdescspi".$li_temp_spi."   value='".$ls_descripcion."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>"; 
				$objectSpi[$li_temp_spi][3]  = "<input type=text name=txtprocspi".$li_temp_spi."   value='".$ls_procede."' class=sin-borde readonly style=text-align:center size=32 maxlength=45>";
				$objectSpi[$li_temp_spi][4]  = "<input type=text name=txtdocspi".$li_temp_spi."    value='".$ls_documento."' class=sin-borde readonly style=text-align:center>";
				$objectSpi[$li_temp_spi][5]  = "<input type=text name=txtopespi".$li_temp_spi."    value='".$ls_operacion_spi."' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
				$objectSpi[$li_temp_spi][6]  = "<input type=text name=txtmontospi".$li_temp_spi."  value='".number_format($ldec_monto,2,",",".")."' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
				$objectSpi[$li_temp_spi][7]  = "<a href=javascript:uf_delete_Spi('".$li_temp_spi."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle Presupuestario de Ingreso' width=15 height=15 border=0></a>";	
			}
			if($li_temp_spi==0)
			{
				$li_temp_spi=0;
				$objectSpi[$li_temp_spi][1]  = "<input type=text name=txtcuentaspi".$li_temp_spi." value='' class=sin-borde readonly style=text-align:center size=6 maxlength=25>";
				$objectSpi[$li_temp_spi][2]  = "<input type=text name=txtdescspi".$li_temp_spi."   value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>"; 
				$objectSpi[$li_temp_spi][3]  = "<input type=text name=txtprocspi".$li_temp_spi."   value='' class=sin-borde readonly style=text-align:center size=32 maxlength=45>";
				$objectSpi[$li_temp_spi][4]  = "<input type=text name=txtdocspi".$li_temp_spi."    value='' class=sin-borde readonly style=text-align:center>";
				$objectSpi[$li_temp_spi][5]  = "<input type=text name=txtopespi".$li_temp_spi."    value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
				$objectSpi[$li_temp_spi][6]  = "<input type=text name=txtmontospi".$li_temp_spi."  value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
				$objectSpi[$li_temp_spi][7]  = "<img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle Presupuestario de Ingreso' width=15 height=15 border=0>";	
			}
			$this->io_sql->free_result($rs_spi);
		}	
		
}
	function uf_delete_dt_scg($ls_mov_document,$ls_codban,$ls_ctaban,$ls_codope,$ls_estmov,$ls_documento,$ls_scgcuenta,$ls_debhab,$ls_codded,$ldec_monto,$ls_proc_delete)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////
		//
		// - Funcion que elimina el detalle contable del movimiento  de banco
		//
		///////////////////////////////////////////////////////////////////////////////////////////////

		$dat=$_SESSION["la_empresa"];
		$ls_codemp=$dat["codemp"];
		
		if($ls_proc_delete=="SCG")
		{
			$ls_sql=" DELETE FROM scb_movbco_scg 
					  WHERE  codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
					  AND    numdoc='".$ls_mov_document."' AND codope='".$ls_codope."' AND estmov='".$ls_estmov."' 
					  AND debhab='".$ls_debhab."' AND codded='".$ls_codded."' AND documento='".$ls_documento."' 
					  AND scg_cuenta='".$ls_scgcuenta."'";
		}
		else
		{
			$ldec_diferencia=$this->uf_calcular_diferencia($ls_codemp,$ls_codban,$ls_ctaban,$ls_mov_document,$ls_codope,$ldec_monto,$ls_scgcuenta,$ls_documento);
			if($ldec_diferencia!=0)
			{
				$ls_sql=" UPDATE scb_movbco_scg 
						  SET monto=(monto-$ldec_monto)
						  WHERE  codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
						  AND    numdoc='".$ls_mov_document."' AND codope='".$ls_codope."' AND estmov='".$ls_estmov."' 
						  AND debhab='".$ls_debhab."' AND codded='".$ls_codded."' AND documento='".$ls_documento."'
						  AND scg_cuenta='".$ls_scgcuenta."'";
			
			   $this->uf_update_montos_auxiliares_movbco_scg($ls_codemp,$ls_codban,$ls_ctaban,$ls_mov_document,$ls_codope,$ls_estmov,$ls_scgcuenta,$ls_debhab,$ls_codded,$ls_documento);

			}
			else
			{
				$ls_sql=" DELETE FROM scb_movbco_scg 
						  WHERE  codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
						  AND    numdoc='".$ls_mov_document."' AND codope='".$ls_codope."' AND estmov='".$ls_estmov."' 
						  AND debhab='".$ls_debhab."' AND codded='".$ls_codded."' AND documento='".$ls_documento."' 
						  AND scg_cuenta='".$ls_scgcuenta."'";
			}
		}

		$li_result=$this->io_sql->execute($ls_sql);			
		if(($li_result===false))	
		{
			$this->is_msg_error="Error al eliminar registro, ".$this->fun->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion="Elimino el detalle contable a la cuenta ".$ls_scgcuenta." para el movimiento bancario de operacion ".$ls_codope." numero ".$ls_mov_document." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
            $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$this->is_msg_error="El detalle contable fue eliminado";				
		}
	
		return $lb_valido;
	}
	
	
	function uf_calcular_diferencia($ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ldec_monto,$ls_scgcuenta,$ls_documento)
	{
		
		$ls_sql="SELECT * 
				 FROM scb_movbco_scg 
	 	 		 WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND numdoc='".$ls_numdoc."' AND codope='".$ls_codope."' 
				 AND scg_cuenta='".$ls_scgcuenta."' AND documento='".$ls_documento."'";
		$rs_data=$this->io_sql->select($ls_sql);
		
		if(($rs_data===false))	
		{
			$this->is_msg_error="Error al busacr cuenta, ".$this->fun->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
			$ldec_monto_scg=0;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ldec_monto_scg=$row["monto"];
			}
			else
			{
				$ldec_monto_scg=0;
			}
		}
		$ldec_diferencia=$ldec_monto-$ldec_monto_scg;
	return $ldec_diferencia;	 
				 
	}
	
	function uf_select_cuenta_scg($ls_codemp,$ls_programatica,$ls_cuenta_spg,$as_estcla)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////
		//
		// -Funcion que retorna cuenta contable asociada 
		//  a la cuenta presupuestaria enviada como parametro.
		//
		///////////////////////////////////////////////////////////////////////////////////////////////
		$ls_codest1=substr($ls_programatica,0,20);
		$ls_codest2=substr($ls_programatica,20,6);
		$ls_codest3=substr($ls_programatica,26,3);
		$ls_codest4=substr($ls_programatica,29,2);
		$ls_codest5=substr($ls_programatica,31,2);
		$ls_sql="SELECT sc_cuenta 
		           FROM spg_cuentas 
				  WHERE codemp='".$ls_codemp."'
				    AND codestpro1='".$ls_codest1."' 
					AND codestpro2='".$ls_codest2."' 
				    AND codestpro3='".$ls_codest3."' 
					AND codestpro4='".$ls_codest4."' 
					AND codestpro5='".$ls_codest5."' 
				    AND spg_cuenta='".$ls_cuenta_spg."'
					AND estcla='".$as_estcla."'" ;

		$rs_cuenta=$this->io_sql->select($ls_sql);				  
		
		if(($rs_cuenta===false))	
		{
			$this->is_msg_error="Error al busacr cuenta, ".$this->fun->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
			$ls_cuenta_scg="";
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_cuenta))
			{
				$ls_cuenta_scg=$row["sc_cuenta"];
			}
			else
			{
				$ls_cuenta_scg="";
			}
		}
	return $ls_cuenta_scg;
	}


	function uf_delete_dt_spg($ls_mov_document,$ls_codban,$ls_ctaban,$ls_codope,$ls_estmov,$ls_numdoc,$ls_cuenta_spg,$ls_operacion,$ls_programatica,$ldec_monto,$as_estcla)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////
		//
		// -Funcion que elimina el detalle presupuestario del movimiento 
		//  junto con el contable asociado a la cuenta de presupuesto.
		//
		///////////////////////////////////////////////////////////////////////////////////////////////
		$dat=$_SESSION["la_empresa"];
		$ls_codemp=$dat["codemp"];
		
		$ls_cuenta_scg=$this->uf_select_cuenta_scg($ls_codemp,$ls_programatica,$ls_cuenta_spg,$as_estcla);
		
		$ls_sql=" DELETE FROM scb_movbco_spgop 
				   WHERE codemp='".$ls_codemp."' 
				     AND codban='".$ls_codban."' 
					 AND ctaban='".$ls_ctaban."' 
				     AND numdoc='".$ls_mov_document."' 
					 AND codope='".$ls_codope."' 
					 AND estmov='".$ls_estmov."' 
					 AND operacion='".$ls_operacion."' 
				     AND codestpro='".$ls_programatica."' 
					 AND documento='".$ls_numdoc."' 
					 AND spg_cuenta='".$ls_cuenta_spg."' 
					 AND estcla='".$as_estcla."'";
		
		$li_result=$this->io_sql->execute($ls_sql);				  
		if(($li_result===false))	
		{
			$this->is_msg_error="Error al eliminar registro, ".$this->fun->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion="Elimino el detalle presupuestario a la cuenta ".$ls_cuenta_spg." de programatica ".$ls_programatica." para el movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
            $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);				////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$lb_valido=$this->uf_delete_dt_scg($ls_mov_document,$ls_codban,$ls_ctaban,$ls_codope,$ls_estmov,$ls_numdoc,$ls_cuenta_scg,'D','00000',$ldec_monto,'SPG');
			$this->is_msg_error="El detalle presupuestario fue eliminado";				
		}
	
		return $lb_valido;
	}
	
	function uf_delete_all_movimiento($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope,$ls_estmov)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////
		//
		// -Funcion que elimina el movimiento Bancario junto con los detalles contables,presupuestarios
		//  asociados a el mismo.
		//
		///////////////////////////////////////////////////////////////////////////////////////////////
		$dat=$_SESSION["la_empresa"];
		$ls_codemp=$dat["codemp"];		
		
		$lb_valido=	$this->uf_delete_all_dtmov($ls_codemp,$ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope,$ls_estmov);//Funcion que elimina los detalles del movimiento

		if($lb_valido)
		{
			$ls_sql="DELETE FROM scb_movbco 
					 WHERE 	codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
					 AND codope='".$ls_codope."' AND estmov='".$ls_estmov."' AND numdoc='".$ls_numdoc."'";
			
			$li_result=$this->io_sql->execute($ls_sql);
			
			if(($li_result===false))
			{
				$lb_valido=false;
				$this->is_msg_error="Error al eliminar detalle de movimiento".$this->fun->uf_convertirmsg($this->io_sql->message);
			}
			else
			{
				$lb_valido=true;
				///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion="Elimino el movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
                $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);				////////////////////////////////////////////////////////////////////////////////////////////////////////////
				$this->is_msg_error="El movimiento Bancario fue eliminado";
			}		
		}
		else
		{
			$lb_valido=false;
		}
		return $lb_valido;
	}

	function uf_delete_all_dtmov($ls_codemp,$ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope,$ls_estmov)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////
		//
		// -Funcion que elimina todos los detalles asociados al movimiento Bancario 
		//
		///////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql="DELETE FROM scb_movbco_scg 
				 WHERE	codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
				 AND codope='".$ls_codope."' AND estmov='".$ls_estmov."' AND numdoc='".$ls_numdoc."'";
		
		$li_result=$this->io_sql->execute($ls_sql);
		
		if(($li_result===false))
		{
			$lb_valido=false;
			$this->is_msg_error="Error al eliminar detalle de movimiento".$this->fun->un_convertirmsg($this->io_sql->message);
		}
		else
		{
			$lb_valido=true;
			///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion="Elimino los detalles contables del movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
            $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);			////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		
		if($lb_valido)
		{
			$ls_sql="DELETE FROM scb_movbco_spgop 
					 WHERE	codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
					 AND codope='".$ls_codope."' AND estmov='".$ls_estmov."' AND numdoc='".$ls_numdoc."'";
			
			$li_result=$this->io_sql->execute($ls_sql);
			
			if(($li_result===false))
			{
				$lb_valido=false;
				$this->is_msg_error="Error al eliminar detalle de movimiento".$this->fun->un_convertirmsg($this->io_sql->message);
			}
			else
			{
				$lb_valido=true;
				///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion="Elimino los detalles presupuestarios del movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
                $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);				////////////////////////////////////////////////////////////////////////////////////////////////////////////
			}
		}		
		if($lb_valido)
		{
			$ls_sql="DELETE FROM scb_movbco_spi
					 WHERE	codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
					 AND codope='".$ls_codope."' AND estmov='".$ls_estmov."' AND numdoc='".$ls_numdoc."'";
			
			$li_result=$this->io_sql->execute($ls_sql);
			
			if(($li_result===false))
			{
				$lb_valido=false;
				$this->is_msg_error="Error al eliminar detalle de movimiento".$this->fun->un_convertirmsg($this->io_sql->message);
			}
			else
			{
				$lb_valido=true;
				///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion="Elimino los detalles de ingresos del movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
                $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);				////////////////////////////////////////////////////////////////////////////////////////////////////////////
			}
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM scb_dt_op
					 WHERE	codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
					 AND    codope='".$ls_codope."' AND numdoc='".$ls_numdoc."'";
			
			$li_result=$this->io_sql->execute($ls_sql);
			
			if(($li_result===false))
			{
				$lb_valido=false;
				$this->is_msg_error="Error al eliminar detalle de movimiento".$this->fun->un_convertirmsg($this->io_sql->message);
			}
			else
			{
				$lb_valido=true;
				///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion="Elimino los detalles de ingresos del movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
                $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);				////////////////////////////////////////////////////////////////////////////////////////////////////////////
			}
		}				

		if($lb_valido)
		{
			$ls_sql="DELETE FROM scb_movbco_fuefinanciamiento
					 WHERE	codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
					 AND    codope='".$ls_codope."' AND numdoc='".$ls_numdoc."'";
			
			$li_result=$this->io_sql->execute($ls_sql);
			
			if(($li_result===false))
			{
				$lb_valido=false;
				$this->is_msg_error="Error al eliminar detalle de fuente de financiamiento ".$this->fun->un_convertirmsg($this->io_sql->message);
			}
			else
			{
				$lb_valido=true;
				///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion="Elimino los detalles de fuente de financiamiento ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban;
                $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);				////////////////////////////////////////////////////////////////////////////////////////////////////////////
			}
		}				



		
		return $lb_valido;
	}


	function uf_numero_voucher($as_codemp,$ls_codban,$ls_ctaban,$ls_numdoc)
	{
		 $ls_sql="  SELECT chevau 
					FROM   scb_movbco  
					WHERE  codemp ='".$as_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND numdoc='".$ls_numdoc."' AND codope='CH'";		
		  $rs_data=$this->io_sql->select($ls_sql);
		  if($rs_data===false)
		  {
				$ls_codigo="";  
		  }
		  else
		  {
			  if ($row=$this->io_sql->fetch_row($rs_data))
			  { 
				  $ls_codigo=$row["chevau"];		  
			  }
			  else
			  {
				  $ls_codigo="";  
			  }
		  }
		return $ls_codigo;
	}

function uf_update_montos_auxiliares_movbco_scg($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$as_cuenta,$as_debhab,$as_codded,$as_documento)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	      Function: uf_update_montos_auxiliares_movbco_scg
//		    Access: private
//	     Arguments: 
//       $as_codemp
//       $as_codban
//       $as_ctaban
//       $as_numdoc
//       $as_codope
//       $as_estmov
//       $as_cuenta
// $as_operacioncon
//       $as_codded
//    $as_documento
//	       Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	   Description: Función que busca y actualiza monto con su correspondiente en Bs.F.
//	    Creado Por: Ing. Nestor Falcón.
//  Fecha Creación: 15/08/2007 								Fecha Última Modificación : 15/08/2007
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;

  $ls_sql  = "SELECT monto
                FROM scb_movbco_scg
			   WHERE codemp='".$as_codemp."'
			     AND codban='".$as_codban."'
				 AND ctaban='".$as_ctaban."'
				 AND numdoc='".$as_numdoc."'
				 AND codope='".$as_codope."'
				 AND estmov='".$as_estmov."'
				 AND scg_cuenta='".$as_cuenta."'
				 AND debhab='".$as_debhab."'
				 AND codded='".$as_codded."'
				 AND documento='".$as_documento."'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido = false;
	 }
  else
     {
	   if ($row=$this->io_sql->fetch_row($rs_data))
	      {
		    $ld_monto = $row["monto"] ;  
		  }
	 }
  return $lb_valido;
}

function uf_update_montos_auxiliares_scb_movbco_spgop($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$as_programa,$as_spgcuenta,$as_documento,$as_coduniadm,$as_estcla)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	      Function: uf_update_montos_auxiliares_scb_movbco_spgop
//		    Access: private
//	     Arguments: 
//       $as_codemp
//       $as_codban
//       $as_ctaban
//       $as_numdoc
//       $as_codope
//       $as_estmov
//     $as_programa
//    $as_spgcuenta
//    $as_documento
//    $as_coduniadm
//	       Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	   Description: Función que busca y actualiza monto con su correspondiente en Bs.F.
//	    Creado Por: Ing. Nestor Falcón.
//  Fecha Creación: 15/08/2007 								Fecha Última Modificación : 15/08/2007
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;

  $ls_sql="SELECT monto, baseimp
		    FROM scb_movbco_spgop
		   WHERE codemp='".$as_codemp."' 
		     AND codban='".$as_codban."' 
		     AND ctaban='".$as_ctaban."' 
		     AND numdoc='".$as_numdoc."'
		     AND codope='".$as_codope."' 
		     AND estmov='".$as_estmov."' 
		     AND codestpro='".$as_programa."' 
		     AND spg_cuenta='".$as_spgcuenta."' 
		     AND documento='".$as_documento."' 
		     AND coduniadm='".$as_coduniadm."'
			 AND estcla='".$as_estcla."'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido = false;
	 }
  else
     {
	   if ($row=$this->io_sql->fetch_row($rs_data))
	      {
		    $ld_monto  = $row["monto"] ;
		  }
	 }
  return $lb_valido;
}


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_fuentefinancimiento($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$as_codfuefin)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_fuentefinancimiento
		//		   Access: public  
		//	    Arguments: as_codemp  // Código de empresa
		//				   as_codban  // Código de Banco
		//				   as_ctaban  // Cuenta del Banco
		//				   as_numdoc  // Número de Documento
		//				   as_codope  // Código de Operación
		//				   as_estmov  // Estatus del Movimiento
		//				   as_codfuefin  // código de La fuente de Financiamiento
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta la fuente de financiamiento por movimiento de banco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 09/10/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if (empty($as_codfuefin))
		   {
		     $as_codfuefin = '--';
		   }
		$ls_sql="INSERT INTO scb_movbco_fuefinanciamiento(codemp, codban, ctaban, numdoc, codope, estmov, codfuefin) VALUES ".
				"('".$as_codemp."','".$as_codban."','".$as_ctaban."','".$as_numdoc."','".$as_codope."','".$as_estmov."','".$as_codfuefin."')";
		$li_numrow=$this->io_sql->execute($ls_sql);
		if($li_numrow===false)
		{
 			$lb_valido=false;
			$this->msg->message("CLASE->Orden de Pago MÉTODO->uf_insert_fuentefinancimiento ERROR->".$this->fun->uf_convertirmsg($this->io_sql->message));
			echo ($this->io_sql->message);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_fuentefinancimiento($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_fuentefinancimiento
		//		   Access: public  
		//	    Arguments: as_codemp  // Código de empresa
		//				   as_codban  // Código de Banco
		//				   as_ctaban  // Cuenta del Banco
		//				   as_numdoc  // Número de Documento
		//				   as_codope  // Código de Operación
		//				   as_estmov  // Estatus del Movimiento
		//				   as_codfuefin  // código de La fuente de Financiamiento
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta la fuente de financiamiento por movimiento de banco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 09/10/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM scb_movbco_fuefinanciamiento ".
				" WHERE	codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."' ".
				"   AND numdoc='".$as_numdoc."' ";
		$li_numrow=$this->io_sql->execute($ls_sql);
		if($li_numrow===false)
		{
 			$lb_valido=false;
			$this->msg->message("CLASE->Orden de Pago MÉTODO->uf_delete_fuentefinancimiento ERROR->".$this->fun->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>