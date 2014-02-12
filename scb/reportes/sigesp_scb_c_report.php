<?php
class sigesp_scb_c_report
{

	var $SQL;
	var $dat_emp;
	var $fun;
	var $io_msg;
	var $SQL_aux;
	var $ds_disponibilidad;
	var $ds_documentos;
	function sigesp_scb_c_report($conn)
	{
	  require_once("../../shared/class_folder/class_sql.php");	
	  require_once("../../shared/class_folder/class_fecha.php");  
	  require_once("../../shared/class_folder/class_funciones.php");
	  require_once("../../shared/class_folder/class_mensajes.php");
	  $this->fun = new class_funciones();
	  $this->SQL= new class_sql($conn);
	  $this->SQL_aux= new class_sql($conn);
	  $this->io_msg= new class_mensajes();		
	  $this->dat_emp=$_SESSION["la_empresa"];
	  $this->ds_disponibilidad=new class_datastore();
	  $this->ds_documentos=new class_datastore();
	}

	
	function uf_cargar_disponibilidad($arr_codban,$ld_fecha,$ls_typereport)
	{
		$ld_fecha=$this->fun->uf_convertirdatetobd($ld_fecha);
		$ls_codemp=$this->dat_emp["codemp"];
		$li_total_bancos=count($arr_codban);
		for ($li_x=0;$li_x<$li_total_bancos;$li_x++)
		    {
			  $ls_codban=$arr_codban[$li_x];
			  $ls_sql = "SELECT scb_ctabanco.ctaban as ctaban, 
			                    scb_ctabanco.dencta as dencta,
								scb_banco.nomban as nomban,
								scb_tipocuenta.codtipcta as codtipcta,
			                    scb_tipocuenta.nomtipcta as nomtipcta,
								scb_ctabanco.fecapr as fecapr,
								trim(scb_ctabanco.sc_cuenta) as sc_cuenta
					       FROM scb_ctabanco, scb_tipocuenta, scb_banco
					      WHERE scb_ctabanco.codemp='".$ls_codemp."'
						    AND scb_ctabanco.codban='".$ls_codban."'
							AND scb_ctabanco.codemp=scb_banco.codemp
							AND scb_ctabanco.codtipcta=scb_tipocuenta.codtipcta 
							AND scb_ctabanco.codban=scb_banco.codban AND scb_ctabanco.ctaban IN (SELECT codintper ".
																					"					 FROM sss_permisos_internos ".
																					"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
																					"				    UNION ".
																					"				   SELECT codintper ".
																					"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
																					"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)							
					      ORDER BY scb_ctabanco.codban,scb_ctabanco.ctaban";			
			  $rs_data = $this->SQL->select($ls_sql);
			  if ($rs_data===false)
			     {
				 $this->is_msg_error="Error en select cuentas, ".$this->fun->uf_convertirmsg($this->SQL->message);
			   }
			  else
			     {			
			       while(!$rs_data->EOF)//Recorro los bancos y cuentas
				        {
						$ldec_saldoanterior 	= 0;
						$ldec_debitos     		= 0;
						$ldec_creditos    		= 0;
						$ldec_debitosneg  		= 0;
						$ldec_creditosneg 		= 0;
						$ldec_debitosant  		= 0;
						$ldec_creditosant 	    = 0;
						$ldec_saldoanterior_ant = 0;
						$ldec_debitosant_ant 	= 0;
						$ldec_debitos_ant		= 0;
						$ldec_debitosneg_ant	= 0;
						$ldec_creditosant_ant	= 0;
						$ldec_creditos_ant		= 0;
						$ldec_creditosneg_ant	= 0;
						$ls_ctaban    = $rs_data->fields["ctaban"];
						$ls_dencta    = $rs_data->fields["dencta"];
						$ls_nomban    = $rs_data->fields["nomban"];				
						$ls_codtipcta = $rs_data->fields["codtipcta"];
						$ls_nomtipcta = $rs_data->fields["nomtipcta"];
						$ls_sc_cuenta = $rs_data->fields["sc_cuenta"];
						$ld_fecapr    = $rs_data->fields["fecapr"];
						if ($ls_typereport=="A")
						   {
						     $ls_auxsql=" AND fecmov<='".$ld_fecha."'";
						   }
						else
						   {
						     $ls_auxsql = " AND fecmov ='".$ld_fecha."'";
						   }
					    $ls_sql = "SELECT codope,(monto-monret) as monto,estmov
							         FROM scb_movbco
							        WHERE codemp='".$ls_codemp."'
									  AND codban='".$ls_codban."'
									  AND ctaban= '".$ls_ctaban."' ".$ls_auxsql ;
					    $rs_saldo = $this->SQL_aux->select($ls_sql);
					    if ($rs_saldo===false)
						   {
							 $this->is_msg_error="Error en select cuentas, ".$this->fun->uf_convertirmsg($this->SQL_aux->message);
						     print "Error".$this->SQL_aux->message;
						   }
					    else
					       {
						     while(!$rs_saldo->EOF)//Recorro los movimientos realizados para el banco y la cuenta para totalizar los movimientos dependiendo de la operacion que genero
								  {
								    $ldec_monto = $rs_saldo->fields["monto"];
								    $ls_codope  = $rs_saldo->fields["codope"];
								    $ls_estmov  = $rs_saldo->fields["estmov"];
									//Acumuladores de movimientos que generan un débito.
									if((($ls_codope=="CH")||($ls_codope=="ND")||($ls_codope=="RE"))&&($ls_estmov!="A"))
									{
										$ldec_creditos=$ldec_creditos+$ldec_monto;
									}
									if((($ls_codope=="CH")||($ls_codope=="ND")||($ls_codope=="RE"))&&($ls_estmov=="A"))
									{
										$ldec_creditosneg=$ldec_creditosneg+$ldec_monto;
									}
									////Acumuladores de movimientos que generan un crédito.
									if((($ls_codope=="DP")||($ls_codope=="NC"))&&($ls_estmov!="A"))
									{
										$ldec_debitos=$ldec_debitos+$ldec_monto;							
									}
									if((($ls_codope=="DP")||($ls_codope=="NC"))&&($ls_estmov=="A"))
									{
										$ldec_debitosneg=$ldec_debitosneg+$ldec_monto;				
									}
						            $rs_saldo->MoveNext();
								  }
						     $this->SQL_aux->free_result($rs_saldo);
					       }
					    if ($ls_typereport=="D")
					       {
						     $ls_sql = "SELECT codope ,(monto-monret) as monto,estmov
								          FROM scb_movbco
								         WHERE codemp='".$ls_codemp."'
										   AND codban='".$ls_codban."'
										   AND ctaban= '".$ls_ctaban."' 
								           AND fecmov < '".$ld_fecha."'" ;
							 $rs_saldocuenta = $this->SQL_aux->select($ls_sql);						
							 if ($rs_saldocuenta===false)
						        {
								  $this->is_msg_error="Error en select cuentas, ".$this->fun->uf_convertirmsg($this->SQL_aux->message);
								  print "Error".$this->SQL_aux->message;
						        }
							 else
						        {
							      while(!$rs_saldocuenta->EOF)//Recorro los movimientos realizados para el banco y la cuenta para totalizar los movimientos dependiendo de la operacion que genero
							           {
										 $ldec_monto_ant = $rs_saldocuenta->fields["monto"];
										 $ls_codope		 = $rs_saldocuenta->fields["codope"];
										 $ls_estmov		 = $rs_saldocuenta->fields["estmov"];
										 //Acumuladores de movimientos que generan un débito.
										 if((($ls_codope=="CH")||($ls_codope=="ND")||($ls_codope=="RE"))&&($ls_estmov!="A"))
										 {
											$ldec_creditos_ant=$ldec_creditos_ant+$ldec_monto_ant;									
										 }
										 if((($ls_codope=="CH")||($ls_codope=="ND")||($ls_codope=="RE"))&&($ls_estmov=="A"))
										 {
											$ldec_creditosneg_ant=$ldec_creditosneg_ant+$ldec_monto_ant;
										 }
										 ////Acumuladores de movimientos que generan un crédito.
										 if((($ls_codope=="DP")||($ls_codope=="NC"))&&($ls_estmov!="A"))
										 {
											$ldec_debitos_ant=$ldec_debitos_ant+$ldec_monto_ant;
										 } 
										 if((($ls_codope=="DP")||($ls_codope=="NC"))&&($ls_estmov=="A"))
										 {
											$ldec_debitosneg_ant=$ldec_debitosneg_ant+$ldec_monto_ant;				
										 }
							  		     $rs_saldocuenta->MoveNext();
									   }
								  $this->SQL_aux->free_result($rs_saldocuenta);
								  $ldec_debitosant_ant    = $ldec_debitos_ant-$ldec_debitosneg_ant;
								  $ldec_creditosant_ant   = $ldec_creditos_ant-$ldec_creditosneg_ant;
								  $ldec_saldoanterior_ant = $ldec_debitosant_ant - $ldec_creditosant_ant;
						        }
					       }
						$ldec_debitosant    = $ldec_debitos-$ldec_debitosneg;
						$ldec_creditosant   = $ldec_creditos-$ldec_creditosneg;
						$ldec_saldoanterior = $ldec_debitosant - $ldec_creditosant;
						$this->ds_disponibilidad->insertRow("codban",$ls_codban);
						$this->ds_disponibilidad->insertRow("nomban",$ls_nomban);
						$this->ds_disponibilidad->insertRow("ctaban",$ls_ctaban);
						$this->ds_disponibilidad->insertRow("dencta",$ls_dencta);
						$this->ds_disponibilidad->insertRow("codtipcta",$ls_codtipcta);
						$this->ds_disponibilidad->insertRow("nomtipcta",$ls_nomtipcta);
						$this->ds_disponibilidad->insertRow("debitos",$ldec_debitosant);
						$this->ds_disponibilidad->insertRow("creditos",$ldec_creditosant);
						$this->ds_disponibilidad->insertRow("saldo",$ldec_saldoanterior);
						$this->ds_disponibilidad->insertRow("saldo_anterior",$ldec_saldoanterior_ant);
						$this->ds_disponibilidad->insertRow("sc_cuenta",$ls_sc_cuenta);
						$ls_fecha=substr($ld_fecapr,0,4);
						$this->ds_disponibilidad->insertRow("fecapr",$ls_fecha);
				        $rs_data->MoveNext();
				      }
			     }
			
			  //---------------------Colocaciones---------------------//
			  $ls_sql = "SELECT scb_colocacion.numcol as numcol,scb_colocacion.ctaban as ctaban, scb_colocacion.dencol as dencta,scb_banco.nomban as nomban,
			                    scb_tipocolocacion.codtipcol as codtipcol,scb_tipocolocacion.nomtipcol as nomtipcol,scb_colocacion.feccol as feccol,scb_colocacion.sc_cuenta as sc_cuenta
						   FROM scb_colocacion, scb_tipocolocacion, scb_banco 
					      WHERE scb_colocacion.codemp='".$ls_codemp."'
						    AND scb_colocacion.codemp=scb_banco.codemp 
						    AND scb_colocacion.codtipcol=scb_tipocolocacion.codtipcol 
						    AND scb_colocacion.codban=scb_banco.codban 
						    AND scb_colocacion.codban='".$ls_codban."'
					      ORDER BY scb_colocacion.codban,scb_colocacion.ctaban";
			  $rs_data = $this->SQL->select($ls_sql);
			  if ($rs_data===false)
			     {
				   $this->is_msg_error="Error en select cuentas, ".$this->fun->uf_convertirmsg($this->SQL->message);
			     }
			  else
			     {			
			       while(!$rs_data->EOF)
				        {
						  $ldec_saldoanterior     = 0;
						  $ldec_debitos           = 0;
						  $ldec_creditos          = 0;
						  $ldec_debitosneg        = 0;
						  $ldec_creditosneg       = 0;
						  $ldec_debitosant        = 0;
						  $ldec_creditosant       = 0;
						  $ldec_saldoanterior_ant = 0;
						  $ldec_debitosant_ant 	= 0;
						  $ldec_debitos_ant		= 0;
						  $ldec_debitosneg_ant	= 0;
						  $ldec_creditosant_ant	= 0;
						  $ldec_creditos_ant	= 0;
						  $ldec_creditosneg_ant = 0;
						  $ls_ctaban	= $rs_data->fields["ctaban"];
						  $ls_dencta	= $rs_data->fields["dencta"];
						  $ls_nomban	= $rs_data->fields["nomban"];	
						  $ls_numcol    = $rs_data->fields["numcol"];			
						  $ls_codtipcta = $rs_data->fields["codtipcol"];
						  $ls_nomtipcta = $rs_data->fields["nomtipcol"];
						  $ls_sc_cuenta = $rs_data->fields["sc_cuenta"];
						  $ld_fecapr	= $rs_data->fields["feccol"];
						  if ($ls_typereport=="A")
						     {
							   $ls_auxsql = " AND fecmovcol<='".$ld_fecha."'";
						     }
						  else
						     { 
							   $ls_auxsql=" AND fecmovcol ='".$ld_fecha."'";
						     }
					      $ls_sql = "SELECT codope ,monmovcol,estcol
							           FROM scb_movcol
							          WHERE codemp='".$ls_codemp."'
									    AND codban='".$ls_codban."'
									    AND ctaban= '".$ls_ctaban."'
									    AND numcol='".$ls_numcol."' ".$ls_auxsql;
					      $rs_saldo = $this->SQL_aux->select($ls_sql);
						  if ($rs_saldo===false)
						     {
							 $this->is_msg_error="Error en select cuentas, ".$this->fun->uf_convertirmsg($this->SQL_aux->message);
						     print "Error".$this->SQL_aux->message;
						   }
						  else
					         {
						       while(!$rs_saldo->EOF)
								    {
									$ldec_monto = $rs_saldo->fields["monmovcol"];
									$ls_codope  = $rs_saldo->fields["codope"];
									$ls_estmov  = $rs_saldo->fields["estcol"];
									//Acumuladores de movimientos que generan un débito.
									if((($ls_codope=="CH")||($ls_codope=="ND")||($ls_codope=="RE"))&&($ls_estmov!="A"))
									{
										$ldec_creditos=$ldec_creditos+$ldec_monto;
									}
									if((($ls_codope=="CH")||($ls_codope=="ND")||($ls_codope=="RE"))&&($ls_estmov=="A"))
									{
										$ldec_creditosneg=$ldec_creditosneg+$ldec_monto;
									}
									////Acumuladores de movimientos que generan un crédito.
									if((($ls_codope=="DP")||($ls_codope=="NC"))&&($ls_estmov!="A"))
									{
										$ldec_debitos=$ldec_debitos+$ldec_monto;							
									}
									if((($ls_codope=="DP")||($ls_codope=="NC"))&&($ls_estmov=="A"))
									{
										$ldec_debitosneg=$ldec_debitosneg+$ldec_monto;	
									}
								    $rs_saldo->MoveNext();
								  }
						       $this->SQL_aux->free_result($rs_saldo);
					         }
					      if ($ls_typereport=="D")
						     {
						       $ls_sql = "SELECT numcol, codope, monmovcol, estcol
								            FROM scb_movcol
								           WHERE codemp='".$ls_codemp."'
										     AND codban='".$ls_codban."'
										     AND ctaban= '".$ls_ctaban."'
										     AND numcol='".$ls_numcol."'
										     AND fecmovcol < '".$ld_fecha."'";
		
						       $rs_saldocuenta = $this->SQL_aux->select($ls_sql);
						       if ($rs_saldocuenta===false)
								  {
								  $this->is_msg_error="Error en select cuentas, ".$this->fun->uf_convertirmsg($this->SQL_aux->message);
								  print "Error".$this->SQL_aux->message;
								}
							   else
							      {
								  while(!$rs_saldocuenta->EOF)
							           {
										 $ldec_monto_ant = $rs_saldocuenta->fields["monmovcol"];
										 $ls_codope		 = $rs_saldocuenta->fields["codope"];
										 $ls_estmov		 = $rs_saldocuenta->fields["estcol"];
										 //Acumuladores de movimientos que generan un débito.
										 if((($ls_codope=="CH")||($ls_codope=="ND")||($ls_codope=="RE"))&&($ls_estmov!="A"))
										 { 
											$ldec_creditos_ant=$ldec_creditos_ant+$ldec_monto_ant;
										 }
										 if((($ls_codope=="CH")||($ls_codope=="ND")||($ls_codope=="RE"))&&($ls_estmov=="A"))
										 {
											$ldec_creditosneg_ant=$ldec_creditosneg_ant+$ldec_monto_ant;
										 }
										 ////Acumuladores de movimientos que generan un crédito.
										 if((($ls_codope=="DP")||($ls_codope=="NC"))&&($ls_estmov!="A"))
										 {
											$ldec_debitos_ant=$ldec_debitos_ant+$ldec_monto_ant;
										 }
										 if((($ls_codope=="DP")||($ls_codope=="NC"))&&($ls_estmov=="A"))
										 {
											$ldec_debitosneg_ant=$ldec_debitosneg_ant+$ldec_monto_ant;				
										 }
									     $rs_saldocuenta->MoveNext();
									   }
								  $this->SQL_aux->free_result($rs_saldocuenta);
								  $ldec_debitosant_ant    = $ldec_debitos_ant-$ldec_debitosneg_ant;
								  $ldec_creditosant_ant   = $ldec_creditos_ant-$ldec_creditosneg_ant;
								  $ldec_saldoanterior_ant = $ldec_debitosant_ant - $ldec_creditosant_ant;
						        }
					         }
						  $ldec_debitosant    = $ldec_debitos-$ldec_debitosneg;
						  $ldec_creditosant   = $ldec_creditos-$ldec_creditosneg;
						  $ldec_saldoanterior = $ldec_debitosant-$ldec_creditosant;
						  $this->ds_disponibilidad->insertRow("codban",$ls_codban);
						  $this->ds_disponibilidad->insertRow("nomban",$ls_nomban);
						  $this->ds_disponibilidad->insertRow("ctaban",$ls_numcol);
						  $this->ds_disponibilidad->insertRow("dencta",$ls_dencta);
						  $this->ds_disponibilidad->insertRow("codtipcta",$ls_codtipcta);
						  $this->ds_disponibilidad->insertRow("nomtipcta",$ls_nomtipcta);
						  $this->ds_disponibilidad->insertRow("debitos",$ldec_debitosant);
						  $this->ds_disponibilidad->insertRow("creditos",$ldec_creditosant);
						  $this->ds_disponibilidad->insertRow("saldo",$ldec_saldoanterior);
						  $this->ds_disponibilidad->insertRow("saldo_anterior",$ldec_saldoanterior_ant);
						  $this->ds_disponibilidad->insertRow("sc_cuenta",$ls_sc_cuenta);
						  $ls_fecha=substr($ld_fecapr,0,4);
						  $this->ds_disponibilidad->insertRow("fecapr",$ls_fecha);
						  $rs_data->MoveNext();
				        }
			     }
		    }
		$la_items=array("ctaban");
		$la_sum=array("debitos","creditos","saldo_anterior");
		$this->ds_disponibilidad->group_by($la_items,$la_sum,$la_items);
		$this->ds_disponibilidad->group("nomban");
	}
	
	function uf_cargar_documentos_descuadrados($as_codope,$ad_fecdesde,$ad_fechasta,$as_codban,$as_ctaban,$as_orden)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////
		//
		//	Function: uf_cargar_documentos_descuadrados
		//
		//	Arguments:
		//			  -$as_codope=Codigo de la operacion a buscar ( Adicionalmente se maneja T para el caso de mostrar todos lo tipos de operación)
		//			  -$ad_fecdesde=Fehca inicio rango de busqueda	
		//			  -$ad_fechasta=Fehca final  rango de busqueda	
		//			  -$as_codban=Codigo del banco
		//			  -$as_ctaban=Cuenta bancaria
		//			  -$as_codconcep=conepto del movimiento
		//			  -$as_orden=Columan de ordenamiento del reporte
		//
		//  Description: Metodo que se encarga de retornar los documentos que se encuentran descuadrados
		//				 filtrados segun los parametros de busqueda enviados
		///////////////////////////////////////////////////////////////////////////////////////////////
			
		$ls_codemp=$this->dat_emp["codemp"];
		$ls_aux="";	
		if(!empty($ad_fecdesde))
		{
			$ld_fecdesde=$this->fun->uf_convertirdatetobd($ad_fecdesde);
			$ls_aux=" AND a.fecmov>='".$ld_fecdesde."' ";
		}
		if(!empty($ad_fechasta))
		{
			$ld_fechasta=$this->fun->uf_convertirdatetobd($ad_fechasta);
			$ls_aux=$ls_aux." AND a.fecmov<='".$ld_fechasta."' ";
		}	
		if(!empty($as_codban))
		{
			$ls_aux=$ls_aux." AND a.codban='".$as_codban."' ";
		}	
		if(!empty($as_ctaban))
		{
			$ls_aux=$ls_aux." AND a.ctaban='".$as_ctaban."' ";
		}	
		if((!empty($as_codope))&&($as_codope!='T'))
		{
			$ls_aux=$ls_aux." AND a.codope='".$as_codope."' ";
		}
		/*if($as_codconcep!='---')
		{
			if(!empty($as_codconcep))
			{
				$ls_aux=$ls_aux." AND a.codconmov='".$as_codconcep."' ";
			}
		}*/
		if($as_orden=='D')//Documento
		{
			$ls_aux=$ls_aux." ORDER BY c.nomban,a.ctaban, a.numdoc";
		}
		if($as_orden=='C')//Cuenta
		{
			$ls_aux=$ls_aux." ORDER BY c.nomban,a.ctaban";
		}
		if($as_orden=='F')//Fecha
		{
			$ls_aux=$ls_aux." ORDER BY c.nomban,a.ctaban,a.fecmov";
		}
		if($as_orden=='B')//Banco
		{
			$ls_aux=$ls_aux." ORDER BY c.nomban,a.ctaban,a.codban";
		}
		if($as_orden=='O')//Operacion
		{
			$ls_aux=$ls_aux." ORDER BY c.nomban,a.ctaban,a.codope";
		}
		
		$ls_sql="SELECT a.codban as codban,c.nomban as nomban, a.ctaban as ctaban, a.codope as codope,
				(a.monto - a.monret) as monto,a.estmovint as estmovint,a.fecmov as fecmov,a.nomproben 
				 as nomproben,a.numdoc as numdoc,a.estmov as estmov,a.conmov as conmov
				 FROM    scb_movbco a,scb_ctabanco b,scb_banco c
				 WHERE   a.codban=b.codban AND a.ctaban=b.ctaban AND a.codban=c.codban
				   AND     a.codemp=b.codemp AND  a.codemp=c.codemp AND a.estmov='N' AND a.codemp='".$ls_codemp."' ".
			    " AND a.ctaban IN (SELECT codintper ".
				"					 FROM sss_permisos_internos ".
				"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"				    UNION ".
				"				   SELECT codintper ".
				"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
				"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)  ".$ls_aux;
				
		$rs_documentos=$this->SQL->select($ls_sql);
		
		if($rs_documentos===false)
		{
			print "Error en uf_cargar_documentos_descuadrados";
			$lb_valido=false;
		}
		else
		{
			if($row=$this->SQL->fetch_row($rs_documentos))
			{
				$data=$this->SQL->obtener_datos($rs_documentos);
				$li_total=(count($data, COUNT_RECURSIVE) / count($data)) - 1;
				$la_descuadrados=array();
				$li_temp=1;
				
				for($li_i=1;$li_i<=$li_total;$li_i++)
				{
					$ls_numdoc=$data["numdoc"][$li_i];
					$ls_codban=$data["codban"][$li_i];	
					$ls_ctaban=$data["ctaban"][$li_i];
					$ls_codope=$data["codope"][$li_i];
				
					$ls_sql="SELECT mondeb,monhab,(mondeb-monhab) AS saldo FROM
							(SELECT SUM(monto) AS mondeb 
							FROM scb_movbco_scg
							WHERE codemp='$ls_codemp' AND numdoc='$ls_numdoc' AND codban='$ls_codban' AND 
							ctaban='$ls_ctaban' AND debhab='D' AND codope='$ls_codope' AND estmov='N') D,
							(SELECT SUM(monto) AS monhab
							FROM scb_movbco_scg
							WHERE codemp='$ls_codemp' AND numdoc='$ls_numdoc' AND codban='$ls_codban' AND 
							ctaban='$ls_ctaban' AND debhab='H' AND codope='$ls_codope' AND estmov='N') H";
							
					$rs_desc=$this->SQL->select($ls_sql);
					if($rs_desc===false)
					{
						print "Error en uf_cargar_documentos_descuadrados";
						$lb_valido=false;
					}
					else
					{
						if($row=$this->SQL->fetch_row($rs_desc))
						{
							 $ldec_saldo = $ld_debe = $ld_haber = 0;
							 $ld_debe  = floatval($row["mondeb"]);      
							 $ld_debe =  number_format($ld_debe,2,".","");
							 $ld_haber = floatval($row["monhab"]);      
							 $ld_haber = number_format($ld_haber,2,".","");
							 $ldec_saldo = floatval($ld_debe-$ld_haber);	
							 $ldec_saldo = number_format($ldec_saldo,2,".","");						
							 $aux_saldo= abs($ldec_saldo);
							if($aux_saldo!=0)
							{   
								$la_descuadrados["codban"][$li_temp]=$data["codban"][$li_i];
								$la_descuadrados["nomban"][$li_temp]=$data["nomban"][$li_i];
								$la_descuadrados["ctaban"][$li_temp]=$data["ctaban"][$li_i];
								$la_descuadrados["codope"][$li_temp]=$data["codope"][$li_i];
								$la_descuadrados["monto"][$li_temp]=$data["monto"][$li_i];
								$la_descuadrados["estmovint"][$li_temp]=$data["estmovint"][$li_i];
								$la_descuadrados["fecmov"][$li_temp]=$data["fecmov"][$li_i];
								$la_descuadrados["nomproben"][$li_temp]=$data["nomproben"][$li_i];
								$la_descuadrados["numdoc"][$li_temp]=$data["numdoc"][$li_i];
								$la_descuadrados["estmov"][$li_temp]=$data["estmov"][$li_i];
								$la_descuadrados["conmov"][$li_temp]=$data["conmov"][$li_i];
								$la_descuadrados["mondeb"][$li_temp]=$row["mondeb"];
								$la_descuadrados["monhab"][$li_temp]=$row["monhab"];
								$la_descuadrados["saldo"][$li_temp]=$ldec_saldo;								
								$li_temp++;
							}
							elseif($ldec_saldo=="")
							{
								$ls_sql="SELECT numdoc
										FROM scb_movbco_spg
										WHERE codemp='$ls_codemp' AND numdoc='$ls_numdoc' AND codban='$ls_codban' AND 
										ctaban='$ls_ctaban' AND codope='$ls_codope' AND estmov='N'";
								$rs_descpresup=$this->SQL->select($ls_sql);
								if($rs_descpresup===false)
								{
									print "Error en uf_cargar_documentos_descuadrados";
									$lb_valido=false;
								}
								else
								{
									 if(!$row=$this->SQL->fetch_row($rs_descpresup))
									 {
									   	$la_descuadrados["codban"][$li_temp]=$data["codban"][$li_i];
										$la_descuadrados["nomban"][$li_temp]=$data["nomban"][$li_i];
										$la_descuadrados["ctaban"][$li_temp]=$data["ctaban"][$li_i];
										$la_descuadrados["codope"][$li_temp]=$data["codope"][$li_i];
										$la_descuadrados["monto"][$li_temp]=$data["monto"][$li_i];
										$la_descuadrados["estmovint"][$li_temp]=$data["estmovint"][$li_i];
										$la_descuadrados["fecmov"][$li_temp]=$data["fecmov"][$li_i];
										$la_descuadrados["nomproben"][$li_temp]=$data["nomproben"][$li_i];
										$la_descuadrados["numdoc"][$li_temp]=$data["numdoc"][$li_i];
										$la_descuadrados["estmov"][$li_temp]=$data["estmov"][$li_i];
										$la_descuadrados["conmov"][$li_temp]=$data["conmov"][$li_i];
										$la_descuadrados["mondeb"][$li_temp]="0.000";
										$la_descuadrados["monhab"][$li_temp]="0.000";
										$la_descuadrados["saldo"][$li_temp]="0.000";								
										$li_temp++;
									 }  		
								}	
							}
						}							
					}
				}
				if(count($la_descuadrados>0))
					$this->ds_documentos->data=$la_descuadrados;
				$lb_valido=true;
			}	
		}
	
	}
	
}	
?>