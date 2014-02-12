<?php
class sigesp_scb_c_movcol
{
	var $is_msg_error;
	var $SQL;
	var $siginc;
	var $int_scg;
	var $int_spg;
	var $msg;
	var $fun;
	var $io_fecha;
	var $dat;
    var $io_seguridad;
	var $is_empresa;
	var $is_sistema;
	var $is_logusr ;	
	var $is_ventana;
	var $ldec_actual;
function sigesp_scb_c_movcol($aa_security)
{
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_fecha.php");
	require_once("../shared/class_folder/class_funciones.php");
	$this->siginc=new sigesp_include();
	$this->io_fecha=new class_fecha();
	$con=$this->siginc->uf_conectar();
	$this->SQL=new class_sql($con);
	$this->is_msg_error="";
	$this->msg=new class_mensajes();
	$this->fun=new class_funciones();
	$this->io_seguridad= new sigesp_c_seguridad();	
	$this->dat=$_SESSION["la_empresa"];
	$this->is_empresa   = $aa_security["empresa"];
	$this->is_sistema   = $aa_security["sistema"];
	$this->is_logusr    = $aa_security["logusr"];	
	$this->is_ventana   = $aa_security["ventanas"];
}

function uf_generar_num_cmp($as_codemp,$as_procede)
{
	 $ls_sql="SELECT comprobante FROM sigesp_cmp WHERE codemp='".$as_codemp."' AND procede='".$as_procede."' ORDER BY comprobante DESC";		
	  $rs_funciondb=$this->SQL->select($ls_sql);
	  if ($row=$this->SQL->fetch_row($rs_funciondb))
	  { 
		  $codigo=$row["comprobante"];
		  settype($codigo,'int');                             // Asigna el tipo a la variable.
		  $codigo = $codigo + 1;                              // Le sumo uno al entero.
		  settype($codigo,'string');                          // Lo convierto a varchar nuevamente.
		  $ls_codigo=$this->fun->uf_cerosizquierda($codigo,15);
	  }
	  else
	  {
		  $codigo="1";
		  $ls_codigo=$this->fun->uf_cerosizquierda($codigo,15);
	  }
	return $ls_codigo;
}

function uf_generar_voucher($as_codemp)
{
	 $ls_sql="  SELECT chevau 
	 			FROM  scb_movbco  
				WHERE  codemp ='".$as_codemp."'
		        ORDER BY chevau DESC";		
	  $rs_funciondb=$this->SQL->select($ls_sql);
	  if ($row=$this->SQL->fetch_row($rs_funciondb))
	  { 
		  $codigo=$row["chevau"];
		  settype($codigo,'int');                             // Asigna el tipo a la variable.
		  $codigo = $codigo + 1;                              // Le sumo uno al entero.
		  settype($codigo,'string');                          // Lo convierto a varchar nuevamente.
		  $ls_codigo=$this->fun->uf_cerosizquierda($codigo,25);
	  }
	  else
	  {
		  $codigo="1";
		  $ls_codigo=$this->fun->uf_cerosizquierda($codigo,25);
	  }
	return $ls_codigo;
}

function uf_select_movimiento($ls_numdoc,$ls_numcol,$ls_codope,$ls_codban,$ls_ctaban)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que verifica que el movimiento de colocacion  no exista
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];
	
	$ls_sql="SELECT numcol,codope,estcol 
			 FROM   scb_movcol
			 WHERE  codemp='".$ls_codemp."' AND codban ='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND numcol='".$ls_numcol."' AND codope ='".$ls_codope ."' AND numdoc='".$ls_numdoc."'";
	
	$rs_mov=$this->SQL->select($ls_sql);
	if(($rs_mov===false))
	{
		$this->is_msg_error="Error en select movimiento,".$this->uf_convertirmsg($this->SQL->message);
		print $this->is_msg_error;
		return false;
	}
	else
	{
		if($row=$this->SQL->fetch_row($rs_mov))
		{
			return true;
		}
		else
		{
			return false;
		}	
	}			
}

function uf_guardar_automatico($ls_codban,$ls_ctaban,$ls_numdoc,$ls_numcol,$ls_codope,$ld_fecmovcol,$ls_conmov,$ldec_monmovcol,$ldec_tasmovcol,$ls_estcol,$li_cobrapaga,$li_esttransf,$aa_seguridad)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que procesa los datos de la cabecera del movimiento bancario
	//	validando que no exista y que el periodo este abierto.
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	
	$lb_valido=false;
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];
	$ls_codusu=$_SESSION["la_logusr"];
	//print $ad_fecha;
	if($this->io_fecha->uf_valida_fecha_periodo($ld_fecmovcol,$ls_codemp))
	{
	   if(!$this->uf_select_movimiento($ls_numdoc,$ls_numcol,$ls_codope,$ls_codban,$ls_ctaban))
	   {	
	  	   $this->SQL->begin_transaction();
		   $lb_valido = $this->uf_insert_colocacion($ls_codemp,$ls_codusu,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_numcol,$ls_codope,$ld_fecmovcol,$ls_conmov,$ldec_monmovcol,$ldec_tasmovcol,$ls_estcol,$li_cobrapaga,$li_esttransf,$aa_seguridad);
													
		   if(!$lb_valido)
		   {
			 	$this->msg->message("Error al procesar el comprobante Presupuestario".$this->is_msg_error); 						
		   } 
		   else
		   {
		   		$this->msg->message("Movimiento Registrado");
		   }
		   $ib_valido = $lb_valido;
		   if($lb_valido)
		   {
				$ib_new = false;
		   }	
	   }
	   else
	   {
		    $lb_valido=true;
	   } 	
	}
	else
	{
		$this->msg->message($this->io_fecha->is_msg_error);
	}	
	return $lb_valido;
}

function uf_insert_colocacion($ls_codemp,$ls_codusu,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_numcol,$ls_codope,$ld_fecmovcol,$ls_conmov,$ldec_monmovcol,$ldec_tasmovcol,$ls_estcol,$li_cobrapaga,$li_esttransf,$aa_seguridad)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que inserta la cabecera del movimiento  de colocacion
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$ld_fecha=$this->fun->uf_convertirdatetobd($ld_fecmovcol);
	
	$ls_sql="INSERT INTO scb_movcol(codemp,codusu,codban,ctaban,numdoc,numcol,codope,fecmovcol,conmov,monmovcol,tasmovcol,estcol,estcob,esttranf)
			 VALUES('".$ls_codemp."','".$ls_codusu."','".$ls_codban."','".$ls_ctaban."','".$ls_numdoc."','".$ls_numcol."','".$ls_codope."','".$ld_fecha."','".$ls_conmov."',".$ldec_monmovcol.",".$ldec_tasmovcol.",'".$ls_estcol."',".$li_cobrapaga.",".$li_esttransf.")";

	$li_result=$this->SQL->execute($ls_sql);
	
	if(($li_result===false))
	{
		$this->is_msg_error=" Fallo insercion, ".$this->fun->uf_convertirmsg($this->SQL->message);
		print $this->SQL->message;
		return false;
	}
	else
	{
		////////////////////////////////////Seguridad////////////////////////////////////////////
		$ls_evento="INSERT";
		$ls_descripcion="Inserto el movimiento de colocacion ".$ls_numdoc." asociado a colocacion ".$ls_numcol." y denominacion ".$ls_conmov." y la operacion ".$ls_codope ;
		$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		return true;
	}	
}

function uf_select_dt_contable($arr_movbco,$ls_cuenta,$ls_operacioncon,$ls_codded,$ls_descripcion,$ldec_monto)
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
	$ls_numdoc     = $arr_movbco["mov_colocacion"];
	$ls_numcol     = $arr_movbco["numcol"];
	$ls_codope     = $arr_movbco["codope"];	
	
	$ls_sql="SELECT monto 
			 FROM scb_movcol_scg
			 WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND numdoc='".$ls_numdoc."' AND codope='".$ls_codope."' AND sc_cuenta='".$ls_cuenta."' AND debhab='".$ls_operacioncon."' AND numcol='".$ls_numcol."'";
	
	$rs_dt_scg=$this->SQL->select($ls_sql);
	if(($rs_dt_scg===false))
	{
		$this->is_msg_error="Error en select detalle ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido=false;
	}
	else
	{
		if($row=$this->SQL->fetch_row($rs_dt_scg))
		{
			$lb_valido=true;
			$this->ldec_actual=$row["monto"];
		}
		else
		{
			$lb_valido=false;
		}
	}	
	return $lb_valido;
}

function  uf_procesar_dt_contable($arr_movbco,$ls_cuenta,$ls_operacioncon,$ls_codded,$ls_descripcion,$ldec_monto,$lb_mov_mandatory,$aa_seguridad)
{					
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que inserta el detalle contable del movimiento 
	//
	///////////////////////////////////////////////////////////////////////////////////////////////	
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];
	
    $ls_codban     = $arr_movbco["codban"];
 	$ls_ctaban     = $arr_movbco["ctaban"];
	$ls_numdoc     = $arr_movbco["mov_colocacion"];
	$ls_numcol     = $arr_movbco["numcol"];
	$ls_codope     = $arr_movbco["codope"];
	$ls_estcol     = $arr_movbco["estcol"];	

	$lb_valido=$this->uf_select_dt_contable($arr_movbco,$ls_cuenta,$ls_operacioncon,$ls_codded,$ls_descripcion,$ldec_monto);
	
	if(!$lb_valido)
	{
			$ls_sql="INSERT INTO scb_movcol_scg(codemp,codban,ctaban,numdoc,numcol,codope,estcol,sc_cuenta,debhab,codded,desmov,monto)
					 VALUES ('".$ls_codemp."','".$ls_codban."','".$ls_ctaban."','".$ls_numdoc."','".$ls_numcol."','".$ls_codope."','".$ls_estcol."','".$ls_cuenta."','".$ls_operacioncon."','".$ls_codded."','".$ls_descripcion."',".$ldec_monto.")";
			
			$li_result=$this->SQL->execute($ls_sql);	
			
			if(($li_result===false))
			{
				$this->is_msg_error="Error al procesar detalle contable, ".$this->fun->uf_convertirmsg($this->SQL->message);
				print $this->SQL->message;
				$lb_valido=false;			
			}
			else
			{
					$lb_valido=true;
					////////////////////////////////////Seguridad////////////////////////////////////////////
					$ls_evento="INSERT";
					$ls_descripcion="Inserto el detalle contable del movimiento  ".$ls_numdoc." asociado a colocacion ".$ls_numcol."  y la operacion ".$ls_codope.",  operacion contable ".$ls_operacioncon." y  cuenta contable ".$ls_cuenta ;
					$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			}
	}
	else
	{
		$ldec_monto=$ldec_monto+$this->ldec_actual;
		$ls_sql="UPDATE scb_movcol_scg 
				 SET monto=".$ldec_monto." 
				 WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' 
				 AND ctaban='".$ls_ctaban."' AND numdoc='".$ls_numdoc."' AND 
				 codope='".$ls_codope."' AND sc_cuenta='".$ls_cuenta."' AND 
				 debhab='".$ls_operacioncon."' AND codded='".$ls_codded."' AND numcol='".$ls_numcol."'";
		if(($lb_valido)&&(!$lb_mov_mandatory))
		{
			$li_result=$this->SQL->execute($ls_sql);	

			if(($li_result===false))
			{
				$this->is_msg_error="Error al procesar detalle contable, ".$this->fun->uf_convertirmsg($this->SQL->message);
				$lb_valido=false;			
			}
			else
			{
				$lb_valido=true;
				////////////////////////////////////Seguridad////////////////////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion="Actualizo detalle contable del movimiento de colocacion ".$ls_numdoc." asociado a colocacion ".$ls_numcol." y denominacion ".$ls_descripcion." y la operacion ".$ls_codope.", y operacion contable ".$ls_operacioncon." y la cuenta contable ".$ls_cuenta ;
				$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
				///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			}
		}		
	}
	return $lb_valido;
}

function uf_update_monto_mov($arr_movbco,$ls_cuenta,$ls_procede,$ls_descripcion,$ls_documento,$ls_operacioncon,$ldec_monto,$ldec_objret,$ls_codded,$aa_seguridad)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que actualiza el monto de un movimiento cuando se selecciona la misma cuenta
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
		
		$dat=$_SESSION["la_empresa"];
		$ls_codemp=$dat["codemp"];
		$ls_codban     = $arr_movbco["codban"];
 		$ls_ctaban     = $arr_movbco["ctaban"];
		$ls_numdoc     = $arr_movbco["mov_document"];
		$ls_codope     = $arr_movbco["codope"];
		$ls_numcol	   = $arr_movbco["numcol"];		
		
		$ls_sql="UPDATE scb_movcol_scg 
				    SET monto=".$ldec_monto." 
				  WHERE codemp='".$ls_codemp."' 
				    AND codban='".$ls_codban."' 
					AND ctaban='".$ls_ctaban."' 
					AND numdoc='".$ls_numdoc."'
					AND numcol='".$ls_numcol."'
					AND codope='".$ls_codope."'
					AND scg_cuenta='".$ls_cuenta."'
					AND debhab='".$ls_operacioncon."'
					AND codded='".$ls_codded."'";

		$li_result=$this->SQL->execute($ls_sql);	

		if(($li_result===false))
		{
			$this->is_msg_error="Error al procesar detalle contable, ".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido=false;			
		}
		else
		{
			$lb_valido=true;
			////////////////////////////////////Seguridad////////////////////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion="Actualizo detalle contable del movimiento de colocacion ".$ls_numdoc." asociado a colocacion ".$ls_numcol." y denominacion ".$ls_conmov." y la operacion ".$ls_codope.", y operacion contable ".$ls_operacioncon." y la cuenta contable ".$ls_cuenta ;
			$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
	return $lb_valido;
}

function uf_update_montodelete($arr_movbco,$ls_cuenta,$ls_descripcion,$ls_documento,$ls_operacioncon,$ldec_monto,$ldec_objret,$ls_codded)
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
		$ls_numcol = $arr_movbco["numcol"];
		
		$ls_sql="UPDATE scb_movcol_scg 
				    SET monto=monto + ".$ldec_monto." 
				  WHERE codemp='".$ls_codemp."' 
				    AND codban='".$ls_codban."' 
					AND ctaban='".$ls_ctaban."' 
					AND numdoc='".$ls_numdoc."' 
					AND numcol='".$ls_numcol."' 
					AND codope='".$ls_codope."' 
					AND scg_cuenta='".$ls_cuenta."' 
					AND debhab='".$ls_operacioncon."' 
					AND codded='".$ls_codded."'";
		
		$li_result=$this->SQL->execute($ls_sql);	

		if(($li_result===false))
		{
			$this->is_msg_error="Error al procesar detalle contable, ".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido=false;			
		}
		else
		{
		  $this->uf_update_montos_auxiliares_movcol_scg($ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_numcol,$ls_codope,$ls_cuenta,$ls_operacioncon,$ls_codded);
		  $lb_valido=true;
		  ////////////////////////////////////Seguridad////////////////////////////////////////////
		  $ls_evento="UPDATE";
		  $ls_descripcion="Actualizo detalle contable del movimiento de colocacion ".$ls_numdoc." asociado a colocacion ".$ls_numcol." y denominacion ".$ls_conmov." y la operacion ".$ls_codope.", y operacion contable ".$ls_operacioncon." y la cuenta contable ".$ls_cuenta ;
		  $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
		  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
	return $lb_valido;
}

function uf_procesar_dt_gasto($ls_codban,$ls_ctaban,$ls_numdoc,$as_codope,$ls_numcol,$ls_programa,$ls_spgcuenta,$ls_desmov,$ldec_monto,$ls_operacion,$ls_estcol,$as_estcla,$aa_seguridad)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que inserta el detalle presupuestario del movimiento 
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];	
										
	$ls_sql="INSERT INTO scb_movcol_spg(codemp, codban, ctaban, numcol, numdoc, codope,estcol, codestpro, spg_cuenta, operacion, desmov, monto, estcla)
			 VALUES ('".$ls_codemp."','".$ls_codban."','".$ls_ctaban."','".$ls_numcol."','".$ls_numdoc."','".$as_codope."','".$ls_estcol."','".$ls_programa."','".$ls_spgcuenta."','".$ls_operacion."','".$ls_desmov."','".$ldec_monto."','".$as_estcla."')";
	
	$li_result=$this->SQL->execute($ls_sql);
	
	if($li_result===false)
	{
		$this->is_msg_error="Error al insertar detalle de gasto, ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido=false;
	}
	else
	{
		$lb_valido=true;
		$this->is_msg_error="Registro Insertado";
		////////////////////////////////////Seguridad////////////////////////////////////////////
		$ls_evento="INSERT";
		$ls_descripcion="Inserto detalle presupuestario del movimiento de colocacion ".$ls_numdoc." asociado a colocacion ".$ls_numcol." y denominacion ".$ls_desmov." y la operacion ".$ls_operacion.", y operacion presupuestaria ".$ls_operacion." y la cuenta prespuestaria ".$ls_spgcuenta." y programatica ".$ls_programa ;
		$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}		
	return $lb_valido;
}

function uf_cargar_dt($as_numdoc,$as_numcol,$as_codban,$as_ctaban,$as_codope,$objectScg,$li_row_scg,$ldec_mondeb,$ldec_monhab,$objectSpg,$li_temp_spg,$ldec_monto_spg,$objectSpi,$li_temp_spi,$ldec_monto_spi,$objectRet,$li_temp_ret,$ldec_monto_ret)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que carga todos los detalles del movimiento de banco en los object 
	//	requeridos por la clase grid_param.
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	
	$li_row_scg  = 0;
	$li_temp_spg = 0;
	$li_temp_spi = 0;
	$li_temp_ret = 0;
	
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];
	
	$ls_sql="SELECT   codban,ctaban,codope,sc_cuenta,codded,debhab,numdoc,desmov,monto
			 FROM     scb_movcol_scg
			 WHERE    codemp='".$ls_codemp ."' AND numdoc ='".$as_numdoc."' AND numcol='".$as_numcol."' AND codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND codope='".$as_codope."'	
			 ORDER BY numdoc asc";
			 
	$rs_scg=$this->SQL->select($ls_sql);		
	
	if(($rs_scg===false))
	{
		$this->is_msg_error="Error en inserción, ".$this->fun->uf_convertirmsg($this->SQL->message);
		print  $this->SQL->message;
		$lb_valido=false;
	}
	else
	{
		while($row=$this->SQL->fetch_row($rs_scg))
		{
				$li_row_scg     = $li_row_scg+1;
				$ls_cuenta      = trim($row["sc_cuenta"]);
				$ls_documento   = trim($row["numdoc"]);
				$ls_descripcion = $row["desmov"];
				$ls_debhab      = trim($row["debhab"]);
				$ldec_monto     = $row["monto"];
				if ($ls_debhab=="D")
				   {
					 $ldec_mondeb=$ldec_mondeb+$ldec_monto;
				   }
				else
				   {
					 $ldec_monhab=$ldec_monhab+$ldec_monto;
				   }
				$ls_codded=trim($row["codded"]);
				$objectScg[$li_row_scg][1] = "<input type=text name=txtcontable".$li_row_scg." id=txtcontable".$li_row_scg."  value='".$ls_cuenta."' class=sin-borde readonly style=text-align:center size=15 maxlength=25>";		
				$objectScg[$li_row_scg][2] = "<input type=text name=txtdocscg".$li_row_scg."    value='".$ls_documento."'   class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
				$objectScg[$li_row_scg][3] = "<input type=text name=txtdesdoc".$li_row_scg."    value='".$ls_descripcion."' class=sin-borde readonly style=text-align:left size=35 maxlength=254>";
				$objectScg[$li_row_scg][4] = "<input type=text name=txtdebhab".$li_row_scg."    value='".$ls_debhab."'      class=sin-borde readonly style=text-align:center size=8 maxlength=1>"; 
				$objectScg[$li_row_scg][5] = "<input type=text name=txtmontocont".$li_row_scg." value='".number_format($ldec_monto,2,",",".")."' class=sin-borde readonly style=text-align:right size=22 maxlength=22>";
				if($ls_codded=='00000')
				{
					$objectScg[$li_row_scg][6] = "<a href=javascript:uf_delete_Scg('".$li_row_scg."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle contable' width=15 height=15 border=0></a>";
				}
				else
				{
					$objectScg[$li_row_scg][6] = "";
				}
		}
		
		if($li_row_scg==0)		
		{
			$li_row_scg=1;
			$objectScg[$li_row_scg][1] = "<input type=text name=txtcontable".$li_row_scg." id=txtcontable".$li_row_scg."  value='' class=sin-borde readonly style=text-align:center size=15 maxlength=25>";		
			$objectScg[$li_row_scg][2] = "<input type=text name=txtdocscg".$li_row_scg."    value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
			$objectScg[$li_row_scg][3] = "<input type=text name=txtdesdoc".$li_row_scg."    value='' class=sin-borde readonly style=text-align:left   size=35 maxlength=254>";
			$objectScg[$li_row_scg][4] = "<input type=text name=txtdebhab".$li_row_scg."    value='' class=sin-borde readonly style=text-align:center size=8 maxlength=1>"; 
			$objectScg[$li_row_scg][5] = "<input type=text name=txtmontocont".$li_row_scg." value='' class=sin-borde readonly style=text-align:right  size=22 maxlength=22>";
			$objectScg[$li_row_scg][6] = "<a href=javascript:uf_delete_Scg('".$li_row_scg."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle contable' width=15 height=15 border=0></a>";
		
		}
		$this->SQL->free_result($rs_scg);
	}		 


	$ls_sql="SELECT   codban,ctaban,operacion,codestpro,spg_cuenta,numdoc,desmov,monto,estcla
			 FROM     scb_movcol_spg
			 WHERE    codemp='".$ls_codemp."' AND numdoc ='".$as_numdoc."' AND numcol='".$as_numcol."' AND codban='".$as_codban."' and ctaban='".$as_ctaban."' and codope='".$as_codope."'
			 ORDER BY numdoc asc";

	$rs_spg=$this->SQL->select($ls_sql);		
	if(($rs_spg===false))
	{
		$this->is_msg_error="Error en inserción, ".$this->fun->uf_convertirmsg($this->SQL->message);
		print  $this->SQL->message;
		$lb_valido=false;
	}
	else
	{

		while($row=$this->SQL->fetch_row($rs_spg))
		{
			$li_temp_spg=$li_temp_spg+1;
			$ls_cuenta        = trim($row["spg_cuenta"]);
			$ls_estcla        = $row["estcla"];
			if ($ls_estcla=='P')
			   {
				 $ls_denestcla = 'Proyecto';
			   }
			elseif($ls_estcla=='A')
			   {
				 $ls_denestcla = 'Acción';
			   }
			if (($_SESSION["la_empresa"]["estmodest"]=='2') && $ls_estcla=='P')
			   {
				 $ls_denestcla = $_SESSION["la_empresa"]["denestpro1"];
			   }
			$ls_programatica  = trim($row["codestpro"]);
			$ls_documento     = $row["numdoc"];
			$ls_descripcion   = $row["desmov"];
			$ls_operacion_spg = $row["operacion"];
			$ldec_monto       = $row["monto"];
			$objectSpg[$li_temp_spg][1]  = "<input type=text name=txtcuenta".$li_temp_spg."       id=txtcuenta".$li_temp_spg."       value='".$ls_cuenta."'        class=sin-borde readonly style=text-align:center size=10 maxlength=25 ><input type=hidden name=hidestcla".$li_temp_spg." id=hidestcla".$li_temp_spg." value='".$ls_estcla."'>";
			$objectSpg[$li_temp_spg][2]  = "<input type=text name=txtprogramatico".$li_temp_spg." id=txtprogramatico".$li_temp_spg." value='".$ls_programatica."'  title='".$ls_programatica.'-'.$ls_denestcla."' class=sin-borde readonly style=text-align:center size=34 maxlength=129>"; 
			$objectSpg[$li_temp_spg][3]  = "<input type=text name=txtdocumento".$li_temp_spg."    id=txtdocumento".$li_temp_spg."    value='".$ls_documento."'     class=sin-borde readonly style=text-align:center size=16 maxlength=15>";
			$objectSpg[$li_temp_spg][4]  = "<input type=text name=txtdescripcion".$li_temp_spg."  id=txtdescripcion".$li_temp_spg."  value='".$ls_descripcion."'   class=sin-borde readonly style=text-align:left>";
			$objectSpg[$li_temp_spg][5]  = "<input type=text name=txtoperacion".$li_temp_spg."    id=txtoperacion".$li_temp_spg."    value='".$ls_operacion_spg."' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
			$objectSpg[$li_temp_spg][6]  = "<input type=text name=txtmonto".$li_temp_spg."        id=txtmonto".$li_temp_spg."        value='".number_format($ldec_monto,2,",",".")."'      class=sin-borde readonly style=text-align:right>";		
			$objectSpg[$li_temp_spg][7]  = "<a href=javascript:uf_delete_Spg('".$li_temp_spg."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle Presupuestario de Gasto' width=15 height=15 border=0></a>";	
		}
		if($li_temp_spg==0)
		{
			$li_temp_spg=1;
			$objectSpg[$li_temp_spg][1]  = "<input type=text name=txtcuenta".$li_temp_spg."       id=txtcuenta".$li_temp_spg."       value='' class=sin-borde readonly style=text-align:center size=10 maxlength=25><input type=hidden name=hidestcla".$li_temp_spg." id=hidestcla".$li_temp_spg." value=''>";
			$objectSpg[$li_temp_spg][2]  = "<input type=text name=txtprogramatico".$li_temp_spg." id=txtprogramatico".$li_temp_spg." value='' class=sin-borde readonly style=text-align:center size=34 maxlength=129>"; 
			$objectSpg[$li_temp_spg][3]  = "<input type=text name=txtdocumento".$li_temp_spg."    id=txtdocumento".$li_temp_spg."    value='' class=sin-borde readonly style=text-align:center size=16 maxlength=15>";
			$objectSpg[$li_temp_spg][4]  = "<input type=text name=txtdescripcion".$li_temp_spg."  id=txtdescripcion".$li_temp_spg."  value='' class=sin-borde readonly style=text-align:left>";
			$objectSpg[$li_temp_spg][5]  = "<input type=text name=txtoperacion".$li_temp_spg."    id=txtoperacion".$li_temp_spg."    value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
			$objectSpg[$li_temp_spg][6]  = "<input type=text name=txtmonto".$li_temp_spg."        id=txtmonto".$li_temp_spg."        value='' class=sin-borde readonly style=text-align:right>";		
			$objectSpg[$li_temp_spg][7]  = "<a href=javascript:uf_delete_Spg('".$li_temp_spg."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle Presupuestario de Gasto' width=15 height=15 border=0></a>";	
		}
		$this->SQL->free_result($rs_spg);
	}
	
	$ls_sql="SELECT   codban,ctaban,operacion,spi_cuenta,numdoc,desmov,monto
			 FROM     scb_movcol_spi
			 WHERE    codemp='".$ls_codemp."' AND numdoc ='".$as_numdoc."' AND numcol='".$as_numcol."' AND codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND codope='".$as_codope."'
			 ORDER BY numdoc asc";
	$rs_spi=$this->SQL->select($ls_sql);		
	if(($rs_spi===false))
	{
		$this->is_msg_error="Error en select, ".$this->fun->uf_convertirmsg($this->SQL->message);
		print  $this->SQL->message;
		$lb_valido=false;
	}
	else
	{
		while($row=$this->SQL->fetch_row($rs_spi))
		{
			$li_temp_spi=$li_temp_spi+1;
			$ls_cuenta=$row["spi_cuenta"];
			$ls_descripcion=$row["desmov"];
			$ls_documento=$row["numdoc"];
			$ls_operacion_spi=$row["operacion"];
			$ldec_monto=$row["monto"];
			$objectSpi[$li_temp_spi][1]  = "<input type=text name=txtcuentaspi".$li_temp_spi." value='".$ls_cuenta."'        class=sin-borde readonly style=text-align:center size=6 maxlength=25>";
			$objectSpi[$li_temp_spi][2]  = "<input type=text name=txtdescspi".$li_temp_spi."   value='".$ls_descripcion."'   class=sin-borde readonly style=text-align:center size=15 maxlength=15>"; 
			$objectSpi[$li_temp_spi][3]  = "<input type=text name=txtdocspi".$li_temp_spi."    value='".$ls_documento."'     class=sin-borde readonly style=text-align:center>";
			$objectSpi[$li_temp_spi][4]  = "<input type=text name=txtopespi".$li_temp_spi."    value='".$ls_operacion_spi."' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
			$objectSpi[$li_temp_spi][5]  = "<input type=text name=txtmontospi".$li_temp_spi."  value='".number_format($ldec_monto,2,",",".")."' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
			$objectSpi[$li_temp_spi][6]  = "<a href=javascript:uf_delete_Spi('".$li_temp_spi."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle Presupuestario de Ingreso' width=15 height=15 border=0></a>";	
		}
		if($li_temp_spi==0)
		{
			$li_temp_spi=1;
			$objectSpi[$li_temp_spi][1]  = "<input type=text name=txtcuentaspi".$li_temp_spi." value='' class=sin-borde readonly style=text-align:center size=6 maxlength=25>";
			$objectSpi[$li_temp_spi][2]  = "<input type=text name=txtdescspi".$li_temp_spi."   value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>"; 
			$objectSpi[$li_temp_spi][3]  = "<input type=text name=txtdocspi".$li_temp_spi."    value='' class=sin-borde readonly style=text-align:center>";
			$objectSpi[$li_temp_spi][4]  = "<input type=text name=txtopespi".$li_temp_spi."    value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
			$objectSpi[$li_temp_spi][5]  = "<input type=text name=txtmontospi".$li_temp_spi."  value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
			$objectSpi[$li_temp_spi][6]  = "<a href=javascript:uf_delete_Spi('".$li_temp_spi."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle Presupuestario de Ingreso' width=15 height=15 border=0></a>";	
		}
		$this->SQL->free_result($rs_spi);
	}
	
	$ls_sql=" SELECT   a.codban as codban,a.ctaban as ctaban,a.codope as codope,a.sc_cuenta as sc_cuenta,a.codded as codded,b.dended as dended,a.debhab as debhab,a.numdoc as numdoc,a.desmov as desmov,a.monto as monto
			  FROM     scb_movcol_scg a,sigesp_deducciones b
			  WHERE    a.codemp='".$ls_codemp."' AND a.numdoc ='".$as_numdoc."' AND numcol='".$as_numcol."' AND a.codban='".$as_codban."' and a.ctaban='".$as_ctaban."' and a.codope='".$as_codope."' and a.codded <> '00000'		
			  AND      a.codded=b.codded AND a.codemp=b.codemp
			  ORDER BY numdoc asc";
	
	$rs_ret=$this->SQL->select($ls_sql);		
	if(($rs_ret===false))
	{
		$this->is_msg_error="Error en inserción, ".$this->fun->uf_convertirmsg($this->SQL->message);
		print $this->SQL->message;
		$lb_valido=false;
	}
	else
	{
		while($row=$this->SQL->fetch_row($rs_ret))
		{				
			$li_temp_ret=$li_temp_ret+1;
			$ls_deduccion=$row["codded"];
			$ls_cuenta=$row["sc_cuenta"];
			$ls_descripcion=$row["desmov"];
			$ls_documento=$row["numdoc"];
			$ldec_monto=$row["monto"];
			$objectRet[$li_temp_ret][1]  = "<input type=text name=txtdeduccion".$li_temp_ret."   value='".$ls_deduccion."'   class=sin-borde readonly style=text-align:center  size=5 maxlength=25>";
			$objectRet[$li_temp_ret][2]  = "<input type=text name=txtcuentaret".$li_temp_ret."   value='".$ls_cuenta."'      class=sin-borde readonly style=text-align:center size=15 maxlength=15>"; 
			$objectRet[$li_temp_ret][3]  = "<input type=text name=txtdescret".$li_temp_ret."     value='".$ls_descripcion."' class=sin-borde readonly style=text-align:left size=32 maxlength=45>";
			$objectRet[$li_temp_ret][4]  = "<input type=text name=txtdocret".$li_temp_ret."      value='".$ls_documento."'   class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
			$objectRet[$li_temp_ret][5]  = "<input type=text name=txtmontoret".$li_temp_ret."    value='".number_format($ldec_monto,2,",",".")."' class=sin-borde readonly style=text-align:right >";
			$objectRet[$li_temp_ret][6]  = "<a href=javascript:uf_delete_Ret('".$li_temp_ret."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar Retención' width=15 height=15 border=0></a>";	
			$ldec_monto_ret=$ldec_monto_ret + $ldec_monto;
		}	
		if($li_temp_ret==0)
		{
			$li_temp_ret=1;
			$objectRet[$li_temp_ret][1]  = "<input type=text name=txtdeduccion".$li_temp_ret."   value='' class=sin-borde readonly style=text-align:center  size=5 maxlength=25>";
			$objectRet[$li_temp_ret][2]  = "<input type=text name=txtcuentaret".$li_temp_ret."   value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>"; 
			$objectRet[$li_temp_ret][3]  = "<input type=text name=txtdescret".$li_temp_ret."     value='' class=sin-borde readonly style=text-align:left size=32 maxlength=45>";
			$objectRet[$li_temp_ret][4]  = "<input type=text name=txtdocret".$li_temp_ret."      value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
			$objectRet[$li_temp_ret][5]  = "<input type=text name=txtmontoret".$li_temp_ret."    value='' class=sin-borde readonly style=text-align:right >";
			$objectRet[$li_temp_ret][6]  = "<a href=javascript:uf_delete_Ret('".$li_temp_ret."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar Retención' width=15 height=15 border=0></a>";	
		}
		$this->SQL->free_result($rs_ret);
	}
}

function uf_delete_dt_scg($ls_mov_document,$ls_codban,$ls_ctaban,$ls_codope,$ls_numcol,$ls_scgcuenta,$ls_debhab,$ls_codded)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que elimina el detalle contable del movimiento 
	//
	///////////////////////////////////////////////////////////////////////////////////////////////

	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];
	
	$ls_sql=" DELETE FROM scb_movcol_scg 
			  WHERE  codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
			  AND    numdoc='".$ls_mov_document."' AND codope='".$ls_codope."' AND debhab='".$ls_debhab."' AND codded='".$ls_codded."' AND numcol='".$ls_numcol."'";
	
	$li_result=$this->SQL->execute($ls_sql);				  
	
	if(($li_result===false))	
	{
		$this->is_msg_error="Error al eliminar registro, ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido=false;
	}
	else
	{
			$lb_valido=true;
			$this->is_msg_error="Registro eliminado";	
			////////////////////////////////////Seguridad////////////////////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion="Elimino  detalle contable del movimiento de colocacion ".$ls_mov_document." asociado a colocacion ".$ls_numcol." y la operacion ".$ls_codope.", y operacion contable ".$ls_debhab;
			$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////							
	}

return $lb_valido;
}
	
function uf_select_cuenta_scg($ls_codemp,$ls_programatica,$ls_cuenta_spg,$as_estcla)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que retorna cuenta contable asociada 
	//  a la cuenta presupuestaria enviada como parametro.
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$ls_cuenta_scg = "";
	$ls_codestpro1 = substr($ls_programatica,0,25);
	$ls_codestpro2 = substr($ls_programatica,25,25);
	$ls_codestpro3 = substr($ls_programatica,50,25);
	$ls_codestpro4 = substr($ls_programatica,75,25);
	$ls_codestpro5 = substr($ls_programatica,100,25);
	$ls_sql="SELECT trim(sc_cuenta) as sc_cuenta
			   FROM spg_cuentas 
			  WHERE codemp='".$ls_codemp."'
				AND codestpro1='".$ls_codestpro1."' 
				AND codestpro2='".$ls_codestpro2."' 
				AND codestpro3='".$ls_codestpro3."' 
				AND codestpro4='".$ls_codestpro4."' 
				AND codestpro5='".$ls_codestpro5."' 
				AND spg_cuenta='".trim($ls_cuenta_spg)."'
				AND estcla='".$as_estcla."'";

	$rs_cuenta=$this->SQL->select($ls_sql);				  
	if($rs_cuenta===false)	
	{
		$this->is_msg_error="Error al busacr cuenta, ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido=false;
		$ls_cuenta_scg="";
	}
	else
	{
		if($row=$this->SQL->fetch_row($rs_cuenta))
		{
			$ls_cuenta_scg=$row["sc_cuenta"];
		}
	}
return $ls_cuenta_scg;
}

function uf_delete_dt_spg($ls_mov_document,$ls_codban,$ls_ctaban,$ls_codope,$ls_numcol,$ls_cuenta_spg,$ls_operacion,$ls_programatica,$as_estcla)
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
	
	$ls_sql=" DELETE FROM scb_movcol_spg 
			   WHERE codemp='".$ls_codemp."' 
				 AND codban='".$ls_codban."'
				 AND ctaban='".$ls_ctaban."' 
				 AND numdoc='".$ls_mov_document."'
				 AND codope='".$ls_codope."'
				 AND operacion='".$ls_operacion."'
				 AND codestpro='".$ls_programatica."'
				 AND numcol='".$ls_numcol."'
				 AND spg_cuenta='".$ls_cuenta_spg."'
				 AND estcla='".$as_estcla."'";
	
	$li_result=$this->SQL->execute($ls_sql);				  
	if(($li_result===false))	
	{
		$this->is_msg_error="Error al eliminar registro, ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido=false;
	}
	else
	{
			$lb_valido=true;
			$lb_valido=$this->uf_delete_dt_scg($ls_mov_document,$ls_codban,$ls_ctaban,$ls_codope,$ls_numcol,$ls_cuenta_scg,'D','00000');
			if($lb_valido)
			{
				$this->is_msg_error="Registro eliminado";
				////////////////////////////////////Seguridad////////////////////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion="Elimino detalle presupuestario del movimiento de colocacion ".$ls_mov_document." asociado a colocacion ".$ls_numcol." y la operacion ".$ls_codope.", y operacion presupuestaria ".$ls_operacion." y la cuenta prespuestaria ".$ls_spgcuenta." y programatica ".$ls_programa ;
				$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
				///////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
			}
			else
			{
				$lb_valido=false;
			}
	}

	return $lb_valido;
}
	
function uf_delete_all_movimiento($ls_numdoc,$ls_numcol,$ls_codban,$ls_ctaban,$ls_codope)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que elimina el movimiento Bancario junto con los detalles contables,presupuestarios
	//  asociados a el mismo.
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$dat=$_SESSION["la_empresa"];
	$ls_codemp=$dat["codemp"];		
	
	$lb_valido=	$this->uf_delete_all_dtmov($ls_codemp,$ls_numdoc,$ls_numcol,$ls_codban,$ls_ctaban,$ls_codope);//Funcion que elimina los detalles del movimiento

	if($lb_valido)
	{
		$ls_sql="DELETE FROM scb_movcol
				 WHERE 	codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND codope='".$ls_codope."' AND numdoc='".$ls_numdoc."' AND numcol='".$ls_numcol."'";
		
		$li_result=$this->SQL->execute($ls_sql);
		
		if(($li_result===false))
		{
			$lb_valido=false;
			$this->is_msg_error="Error al eliminar detalle de movimiento".$this->fun->un_convertirmsg($this->SQL->message);
		}
		else
		{
				$lb_valido=true;
				////////////////////////////////////Seguridad////////////////////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion="Elimino  movimiento de colocacion ".$ls_numdoc." asociado a colocacion ".$ls_numcol." y la operacion ".$ls_codope;
				$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
				///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				$this->is_msg_error="Registro eliminado satisfactoriamente";
		}		
	}
	else
	{
		$lb_valido=false;
	}
	return $lb_valido;
}

function uf_delete_all_dtmov($ls_codemp,$ls_numdoc,$ls_numcol,$ls_codban,$ls_ctaban,$ls_codope)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que elimina todos los detalles asociados al movimiento Bancario 
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$ls_sql="DELETE FROM scb_movcol_scg 
			 WHERE	codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND codope='".$ls_codope."' AND numdoc='".$ls_numdoc."' AND numcol='".$ls_numcol."'";
	
	$li_result=$this->SQL->execute($ls_sql);
	
	if(($li_result===false))
	{
		$lb_valido=false;
		$this->is_msg_error="Error al eliminar detalle de movimiento".$this->fun->un_convertirmsg($this->SQL->message);
	}
	else
	{
		$lb_valido=true;
		////////////////////////////////////Seguridad////////////////////////////////////////////
		$ls_evento="DELETE";
		$ls_descripcion="Elimino detalle contable del movimiento de colocacion ".$ls_numdoc." asociado a colocacion ".$ls_numcol." del banco ".$ls_codban." y la cuenta ".$ls_ctaban." y la operacion ".$ls_codope ;
		$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
	}
	
	if($lb_valido)
	{
		$ls_sql="DELETE FROM scb_movcol_spg 
				 WHERE	codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND codope='".$ls_codope."' AND numdoc='".$ls_numdoc."' AND numcol='".$ls_numcol."'";
		
		$li_result=$this->SQL->execute($ls_sql);
		
		if(($li_result===false))
		{
			$lb_valido=false;
			$this->is_msg_error="Error al eliminar detalle de movimiento".$this->fun->un_convertirmsg($this->SQL->message);
		}
		else
		{
			$lb_valido=true;
			////////////////////////////////////Seguridad////////////////////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion="Elimino detalle presupuestario del movimiento de colocacion ".$ls_numdoc." asociado a colocacion ".$ls_numcol." del banco ".$ls_codban." y la cuenta ".$ls_ctaban." y la operacion ".$ls_codope ;
			$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		}
	}		
	if($lb_valido)
	{
		$ls_sql="DELETE FROM scb_movcol_spi
				 WHERE	codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND codope='".$ls_codope."' AND numdoc='".$ls_numdoc."' AND numcol='".$ls_numcol."'";
		
		$li_result=$this->SQL->execute($ls_sql);
		
		if(($li_result===false))
		{
			$lb_valido=false;
			$this->is_msg_error="Error al eliminar detalle de movimiento".$this->fun->un_convertirmsg($this->SQL->message);
		}
		else
		{
			$lb_valido=true;
			////////////////////////////////////Seguridad////////////////////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion="Elimino detalle de ingreso del movimiento de colocacion ".$ls_numdoc." asociado a colocacion ".$ls_numcol." del banco ".$ls_codban." y la cuenta ".$ls_ctaban." y la operacion ".$ls_codope ;
			$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		}
	}			
	
	return $lb_valido;
}	

function uf_update_montos_auxiliares_movcol_scg($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_numcol,$as_codope,$as_cuenta,$as_debhab,$as_codded)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	      Function: uf_update_montos_auxiliares_movcol_scg
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

  $ls_sql="SELECT monto 
			 FROM scb_movcol_scg
		    WHERE codemp='".$as_codemp."' 
			  AND codban='".$as_codban."' 
			  AND ctaban='".$as_ctaban."' 
			  AND numdoc='".$as_numdoc."' 
			  AND numcol='".$as_numcol."' 
			  AND codope='".$as_codope."' 
			  AND scg_cuenta='".$as_cuenta."' 
			  AND debhab='".$as_debhab."' 
			  AND codded='".$as_codded."'";
					
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido = false;
	 }
  return $lb_valido;
}
}
?>