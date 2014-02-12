<?php
class sigesp_scb_c_carta_orden
{
	var $io_sql;
	var $io_function;
	var $io_msg;
	var $is_msg_error;	
	var $ds_sol;
	var $dat;
	var $ds_temp;
	var $io_sql_aux;
	function sigesp_scb_c_carta_orden()
	{
		require_once("class_funciones_banco.php");
		require_once("sigesp_c_cuentas_banco.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/sigesp_include.php");        
		$io_siginc   	    = new sigesp_include();
		$io_connect		    = $io_siginc->uf_conectar();
		$this->io_sql	    = new class_sql($io_connect);
		$this->io_sql_aux   = new class_sql($io_connect);
		$this->io_funscb 	= new class_funciones_banco();
		$this->io_function  = new class_funciones();
		$this->io_msg	    = new class_mensajes();
		$this->dat		    = $_SESSION["la_empresa"];	
		$this->ds_temp	    = new class_datastore();
		$this->ds_sol 	    = new class_datastore();
	    $this->io_ctaban    = new sigesp_c_cuentas_banco();
	    $this->la_seguridad = "";
	}

function uf_generar_num_documento($as_codemp,$as_codope)
{
		 $ls_sql="SELECT numdoc FROM scb_movbco ".
		         " WHERE codemp='".$as_codemp."' AND codope='".$as_codope."' AND substr(numdoc,1,3)='CO0'".
				 " ORDER BY numdoc DESC";
		 $rs_funciondb=$this->io_sql->select($ls_sql);
		  if ($row=$this->io_sql->fetch_row($rs_funciondb))
		  { 
			  $codigo=substr($row["numdoc"],2,13);
			  settype($codigo,'int'); 
			  $codigo = $codigo + 1;                              // Le sumo uno al entero.
			  settype($codigo,'string');                          // Lo convierto a varchar nuevamente.
			  $ls_codigo=$this->io_function->uf_cerosizquierda($codigo,13);
			  $ls_codigo="CO".$ls_codigo;
		  }
		  else
		  {
			  $codigo="1";
			  $ls_codigo="CO".$this->io_function->uf_cerosizquierda($codigo,13);
		  }
		return $ls_codigo;
	}
	
function  uf_cargar_programaciones($as_proben,$as_codigo,$as_codban,$as_ctaban,$object,$li_rows,$ai_tipvia,$as_numordpagmin,$as_codtipfon)
{
	    $li_i = 0;
		$li_estciespg = $this->io_funscb->uf_obtenervalor("hidestciespg",0);
	    $li_estciespi = $this->io_funscb->uf_obtenervalor("hidestciespi",0);
	    $li_estciescg = $this->io_funscb->uf_obtenervalor("hidestciescg",0);
		
		$ls_codemp    = $this->dat["codemp"];
		$ls_cadaux    = $ls_straux = $ls_sqlaux = "";
		if ($ai_tipvia=='1')
		   {
		     $ls_sqlaux = " AND scb_prog_pago.esttipvia = '1'";
		   }
		else
		   {
		     $ls_sqlaux = " AND scb_prog_pago.esttipvia <> '1'";
		   }
		if ($as_proben=='B')
		   {
		     $ls_tabla  = ', rpc_beneficiario';
		     $ls_straux = ' rpc_beneficiario.nombene, rpc_beneficiario.apebene,';
		     $ls_sqlaux = $ls_sqlaux.' AND cxp_solicitudes.codemp=rpc_beneficiario.codemp AND cxp_solicitudes.ced_bene=rpc_beneficiario.ced_bene';
		   }
		else
		   {
		     $ls_tabla  = ', rpc_proveedor';
		     $ls_straux = ' rpc_proveedor.nompro, ';
		     $ls_sqlaux = $ls_sqlaux.' AND cxp_solicitudes.codemp=rpc_proveedor.codemp AND cxp_solicitudes.cod_pro=rpc_proveedor.cod_pro';
		   }
	    if (!empty($as_numordpagmin) && !empty($as_codtipfon) && $as_numordpagmin!='-' && $as_codtipfon!='----')
		   {
		     $ls_sqlaux = $ls_sqlaux." AND trim(cxp_solicitudes.numordpagmin) = '".$as_numordpagmin."' 
			                		   AND cxp_solicitudes.codtipfon = '".$as_codtipfon."'";
		   }
		else
		   {
		     $ls_sqlaux = $ls_sqlaux." AND trim(cxp_solicitudes.numordpagmin) = '-' 
			                		   AND cxp_solicitudes.codtipfon = '----'";
		   }
		$ls_sql = "SELECT cxp_solicitudes.numsol, cxp_solicitudes.cod_pro, cxp_solicitudes.ced_bene, $ls_straux
					      cxp_solicitudes.codfuefin, cxp_solicitudes.consol, cxp_solicitudes.monsol,
					      scb_prog_pago.codban, scb_banco.nomban, scb_prog_pago.ctaban, scb_ctabanco.dencta,
					      scb_ctabanco.codtipcta, scb_ctabanco.sc_cuenta, scb_tipocuenta.nomtipcta, '' AS numrecdoc,
						  (SELECT count(cxp_rd_spg.spg_cuenta) 
						     FROM cxp_rd_spg, cxp_rd, cxp_dt_solicitudes
						    WHERE cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp
						      AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol
						      AND cxp_dt_solicitudes.codemp=cxp_rd_spg.codemp
						      AND cxp_dt_solicitudes.numrecdoc=cxp_rd_spg.numrecdoc
						      AND cxp_dt_solicitudes.codtipdoc=cxp_rd_spg.codtipdoc
						      AND cxp_dt_solicitudes.cod_pro=cxp_rd_spg.cod_pro
						      AND cxp_dt_solicitudes.ced_bene=cxp_rd_spg.ced_bene
						      AND cxp_rd.codemp=cxp_rd_spg.codemp
						      AND cxp_rd.numrecdoc=cxp_rd_spg.numrecdoc
						      AND cxp_rd.codtipdoc=cxp_rd_spg.codtipdoc
						      AND cxp_rd.cod_pro=cxp_rd_spg.cod_pro
						      AND cxp_rd.ced_bene=cxp_rd_spg.ced_bene) as detspg
				     FROM cxp_solicitudes, scb_prog_pago, scb_banco, scb_ctabanco, scb_tipocuenta $ls_tabla					 
				    WHERE cxp_solicitudes.codemp='".$ls_codemp."'
				      AND cxp_solicitudes.estprosol='S'
				      AND scb_prog_pago.estmov='P'
				      AND cxp_solicitudes.tipproben='".$as_proben."'
					  AND cxp_solicitudes.codemp=scb_prog_pago.codemp
				      AND cxp_solicitudes.numsol=scb_prog_pago.numsol
				   	  AND scb_banco.codemp=scb_prog_pago.codemp
				   	  AND scb_banco.codban=scb_prog_pago.codban
				   	  AND scb_banco.codemp=scb_ctabanco.codemp
				   	  AND scb_banco.codban=scb_ctabanco.codban
				      AND scb_prog_pago.codemp=scb_ctabanco.codemp
				      AND scb_prog_pago.ctaban=scb_ctabanco.ctaban
				      AND scb_ctabanco.codtipcta=scb_tipocuenta.codtipcta  $ls_sqlaux 
					  AND scb_ctabanco.ctaban IN (SELECT codintper ".
						"					 FROM sss_permisos_internos ".
						"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
						"				    UNION ".
						"				   SELECT codintper ".
						"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
						"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
			 $this->is_msg_error="Error en consulta, ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			 print $this->io_sql->message;
			 $lb_valido=false;
		   }
		else
		   {
			 while(!$rs_data->EOF)
			      {
				     $li_detspg = $rs_data->fields["detspg"];
					 if (($li_estciespg==1 || $li_estciespi==1) && ($li_detspg==0 && $li_estciescg==0) || 
				        ($li_estciespg==0 && $li_estciespi==0 && $li_estciescg==0))
				        {
				          if ($as_proben=='P')
				             {
					           $ls_codproben = $rs_data->fields["cod_pro"];
					           $ls_nomproben = $rs_data->fields["nompro"];
				             }
				          else
				             { 
					           $ls_codproben = $rs_data->fields["ced_bene"];
					           $ls_nomproben = $rs_data->fields["nombene"];
				               $ls_apebene   = $rs_data->fields["apebene"];
							   if (!empty($ls_apebene))
								  {
								    $ls_nomproben = $ls_nomproben.', '.$ls_apebene;
								  }
				             }
						  $ls_numsol	 = trim($rs_data->fields["numsol"]);
					 	  $ls_consol	 = $rs_data->fields["consol"];
						  $ldec_monsol   = $rs_data->fields["monsol"];
						  $ls_codban	 = trim($rs_data->fields["codban"]);
						  $ls_nomban	 = $rs_data->fields["nomban"];
						  $ls_ctaban	 = trim($rs_data->fields["ctaban"]);
						  $ls_denctaban  = $rs_data->fields["dencta"];
						  $ls_codtipcta  = trim($rs_data->fields["codtipcta"]);
						  $ls_nomtipcta  = $rs_data->fields["nomtipcta"];
						  $ls_sccuenta   = trim($rs_data->fields["sc_cuenta"]);
						  $ls_codfuefin  = "--";
						  $ld_disponible = 0; 
						  $this->io_ctaban->uf_verificar_saldo($ls_codban,$ls_ctaban,&$ld_disponible);
						  //Busco el monto que ya se abono a la solicitud programada
						  $li_montonotas=0;
						  $lb_valido=$this->uf_load_notas_asociadas($ls_codemp,$ls_numsol,&$li_montonotas);
						  $lb_valido=$this->uf_load_fuentefinancimiento($ls_codemp,$ls_numsol,&$ls_codfuefin);
						  $ldec_montocancelado = $this->uf_select_solcxp_montocancelado($ls_codemp,$ls_numsol,$ls_codban,$ls_ctaban);
						  //Calculo el monto pendiente
						  $ldec_montopendiente = ($ldec_monsol-$ldec_montocancelado)+$li_montonotas;
						  if ($ldec_montopendiente>0) 
						     {
							   $li_i++;
							   $object[$li_i][1] = "<input type=checkbox name=chk".$li_i."    	         id=chk".$li_i."      		   value=1                                        			  class=sin-borde  onClick=javascript:uf_selected('".$li_i."');><input type=hidden   name=txtcodban".$li_i."  id=txtcodban".$li_i." value='".$ls_codban."' readonly>";
							   $object[$li_i][2] = "<input type=text     name=txtnumsol".$li_i."  	     id=txtnumsol".$li_i." 		   value='".$ls_numsol."'                         			  class=sin-borde  readonly style=text-align:center size=15 maxlength=15>";
							   $object[$li_i][3] = "<input type=text     name=txtconsol".$li_i."  	     id=txtconsol".$li_i."         value='".$ls_consol."' title='".$ls_consol."'  			  class=sin-borde  readonly style=text-align:left size=30 maxlength=254>";
							   $object[$li_i][4] = "<input type=hidden   name=txtcodproben".$li_i."  	 id=txtcodproben".$li_i."      value='".$ls_codproben."'><input type=text name=txtnomproben".$li_i." id=txtnomproben".$li_i."  value='".$ls_nomproben."' title='".$ls_nomproben."' class=sin-borde readonly style=text-align:left size=30 maxlength=254>";
							   $object[$li_i][5] = "<input type=text     name=txtmonsol".$li_i."  	   	 id=txtmonsol".$li_i."  	   value='".number_format($ldec_monsol,2,",",".")."'         class=sin-borde readonly style=text-align:right size=16 maxlength=20>";
							   $object[$li_i][6] = "<input type=text 	 name=txtmontopendiente".$li_i." id=txtmontopendiente".$li_i." value='".number_format($ldec_montopendiente,2,",",".")."' class=sin-borde readonly style=text-align:right size=16 maxlength=20>";
							   $object[$li_i][7] = "<input type=text 	 name=txtmonto".$li_i."   		 id=txtmonto".$li_i."          value='".number_format($ldec_montopendiente,2,",",".")."' class=sin-borde onBlur=javascript:uf_actualizar_monto(".$li_i."); style=text-align:right size=16 maxlength=20>";				
							   $object[$li_i][8] = "<input type=text     name=txtnomban".$li_i."  	     id=txtnomban".$li_i."         value='".$ls_nomban."' title='".$ls_nomban."'  			class=sin-borde  readonly style=text-align:left size=30 maxlength=30>";
							   $object[$li_i][9] = "<input type=text     name=txtctaban".$li_i."  	     id=txtctaban".$li_i."         value='".$ls_ctaban."' title='".$ls_ctaban.' - '.$ls_denctaban."'  			class=sin-borde  readonly style=text-align:center size=25 maxlength=25><input type=hidden  name=txtdenctaban".$li_i."  id=txtdenctaban".$li_i."  value='".$ls_denctaban."'>".
													"<input type=hidden  name=txtcodtipcta".$li_i."      id=txtcodtipcta".$li_i."      value='".$ls_codtipcta."'>".
													"<input type=hidden  name=txtnomtipcta".$li_i."      id=txtnomtipcta".$li_i."      value='".$ls_nomtipcta."'>".
													"<input type=hidden  name=txtscgcuenta".$li_i."      id=txtscgcuenta".$li_i."      value='".$ls_sccuenta."'>".
													"<input type=hidden  name=txtdisponible".$li_i."     id=txtdisponible".$li_i."     value='".number_format($ld_disponible,2,',','.')."'>".
													"<input type=hidden  name=txtcodfuefin".$li_i."      id=txtcodfuefin".$li_i."      value='".$ls_codfuefin."'>";				
				             }
			            }
			        $rs_data->MoveNext();
				  }
			 if ($li_i==0)
			    {
				  $li_i++;
				  $object[$li_i][1] = "<input type=checkbox name=chk".$li_i."  				id=chk".$li_i." 			  value=1 class=sin-borde onClick=javascript:uf_selected('".$li_i."');><input type=hidden   name=txtcodban".$li_i."  id=txtcodban".$li_i." value='' readonly>";
				  $object[$li_i][2] = "<input type=text     name=txtnumsol".$li_i."         id=txtnumsol".$li_i."         value=''  class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
				  $object[$li_i][3] = "<input type=text     name=txtconsol".$li_i."         id=txtconsol".$li_i."         value=''  class=sin-borde readonly style=text-align:left size=30 maxlength=254>";
				  $object[$li_i][4] = "<input type=hidden   name=txtcodproben".$li_i."      id=txtcodproben".$li_i."      value=''  class=sin-borde readonly style=text-align:left size=30 maxlength=254><input type=text name=txtnomproben".$li_i." id=txtnomproben".$li_i." value='' size=30 maxlength=254 class=sin-borde>";
				  $object[$li_i][5] = "<input type=text     name=txtmonsol".$li_i."         id=txtmonsol".$li_i."         value='".number_format(0,2,",",".")."' class=sin-borde readonly style=text-align:right size=16 maxlength=20>";
				  $object[$li_i][6] = "<input type=text     name=txtmontopendiente".$li_i." id=txtmontopendiente".$li_i." value='".number_format(0,2,",",".")."' class=sin-borde readonly style=text-align:right size=16 maxlength=20>";				
				  $object[$li_i][7] = "<input type=text     name=txtmonto".$li_i."          id=txtmonto".$li_i."          value='".number_format(0,2,",",".")."' class=sin-borde onBlur=javascript:uf_actualizar_monto(".$li_i."); style=text-align:right size=16 maxlength=20>";							
				  $object[$li_i][8] = "<input type=text     name=txtnomban".$li_i."  	    id=txtnomban".$li_i."         value=''  class=sin-borde  readonly style=text-align:left size=30 maxlength=30>";
				  $object[$li_i][9] = "<input type=text     name=txtctaban".$li_i."  	    id=txtctaban".$li_i."         value=''  class=sin-borde  readonly style=text-align:center size=25 maxlength=25>".
								      "<input type=hidden   name=txtdenctaban".$li_i."      id=txtdenctaban".$li_i."      value=''>".
									  "<input type=hidden   name=txtdenctaban".$li_i."      id=txtdenctaban".$li_i."  	  value=''>".
									  "<input type=hidden   name=txtcodtipcta".$li_i."      id=txtcodtipcta".$li_i."  	  value=''>".
									  "<input type=hidden   name=txtnomtipcta".$li_i."      id=txtnomtipcta".$li_i."      value=''>".
									  "<input type=hidden   name=txtscgcuenta".$li_i."      id=txtscgcuenta".$li_i."      value=''>".
									  "<input type=hidden   name=txtdisponible".$li_i."     id=txtdisponible".$li_i."     value='0,00'>".
									  "<input type=hidden   name=txtcodfuefin".$li_i."      id=txtcodfuefin".$li_i."      value=''>";			
			    }
			 $this->io_sql->free_result($rs_data);
		   }
		$li_rows=$li_i;	
	}//Fin de uf_cargar_programaciones
	
	
function uf_load_notas_asociadas($as_codemp,$as_numsol,&$ai_montonotas)
{
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_load_notas_asociadas
		//	          Access:  public
		//	        Arguments  as_codemp //  Código de la Empresa.
		//                     as_numsol //  Número de Identificación de la Solicitud de Pago.
		//                     ai_montonotas //  monto de las Notas de Débito y Crédito.
		//	         Returns:  lb_valido.
		//	     Description:  Función que se encarga de buscar las notas de debito y crédito asociadas a la solicitud de pago. 
		//     Elaborado Por:  Ing. Yesenia Moreno
		// Fecha de Creación:  26/09/2007       Fecha Última Actualización:
		////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=true;
		$ai_montonotas=0;
		$ls_sql= "SELECT SUM(CASE cxp_sol_dc.codope WHEN 'NC' THEN (-1*cxp_sol_dc.monto) ".
			   "                                 			ELSE (cxp_sol_dc.monto) END) as total ".
			   "  FROM cxp_dt_solicitudes, cxp_sol_dc ".
			   " WHERE cxp_dt_solicitudes.codemp='".$as_codemp."' ".
			   "   AND cxp_dt_solicitudes.numsol='".$as_numsol."' ".
			   "   AND cxp_sol_dc.estnotadc= 'C' ".
			   "   AND cxp_dt_solicitudes.codemp = cxp_sol_dc.codemp ".
			   "   AND cxp_dt_solicitudes.numsol = cxp_sol_dc.numsol ".
			   "   AND cxp_dt_solicitudes.numrecdoc = cxp_sol_dc.numrecdoc ".
			   "   AND cxp_dt_solicitudes.codtipdoc = cxp_sol_dc.codtipdoc ".
			   "   AND cxp_dt_solicitudes.ced_bene = cxp_sol_dc.ced_bene ".
			   "   AND cxp_dt_solicitudes.cod_pro = cxp_sol_dc.cod_pro";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en metodo uf_load_notas_asociadas".$this->fun->uf_convertirmsg($this->SQL->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_montonotas=$row["total"];
			}
		}
		return $lb_valido;
	}	
	
function uf_load_fuentefinancimiento($as_codemp,$as_numsol,&$as_codfuefin)
{
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_load_fuentefinancimiento
		//	          Access:  public
		//	        Arguments  as_codemp //  Código de la Empresa.
		//                     as_numsol //  Número de Identificación de la Solicitud de Pago.
		//                     as_codfuefin //  fuente de financiemiento
		//	         Returns:  lb_valido.
		//	     Description:  Función que se encarga de buscar las notas de debito y crédito asociadas a la solicitud de pago. 
		//     Elaborado Por:  Ing. Yesenia Moreno
		// Fecha de Creación:  26/09/2007       Fecha Última Actualización:
		////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=true;
		$as_codfuefin="--";
		$ls_sql= "SELECT codfuefin ".
			   "  FROM cxp_solicitudes ".
			   " WHERE codemp='".$as_codemp."' ".
			   "   AND numsol='".$as_numsol."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en metodo uf_load_fuentefinancimiento".$this->fun->uf_convertirmsg($this->SQL->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codfuefin=$row["codfuefin"];
			}
		}
		return $lb_valido;
	}	
	
function uf_select_solcxp_montocancelado($ls_codemp,$ls_numsol,$ls_codban,$ls_ctaban)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	uf_select_solcxp_montocancelado
	// 	Access:		public    -Accesado por el metodo uf_cargar_programaciones
	//  Returns:	Decimal--- Valor decimal con el monto que ha sido cancelado o abonado para la solicitud
	//	Description:	Funcion que suma los montos cancelados o abonados para cada solicitud
	//////////////////////////////////////////////////////////////////////////////
		
        $ls_sql = "SELECT sum(monto) as monto
				     FROM cxp_sol_banco 
				    WHERE codemp='".$ls_codemp."' 
					  AND numsol='".$ls_numsol."' 
					  AND estmov<>'A' 
					  AND estmov<>'O'";		
		$rs_data=	$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en consulta,".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ldec_montocancelado=$row["monto"];
			}
			else
			{
				$ldec_montocancelado=0;
			}
			$this->io_sql->free_result($rs_data);			
		}
		return $ldec_montocancelado;
	
	}//Fin de uf_select_solcxp_montocancelado
	
function uf_select_ctaprovbene($as_provbene,$as_codprobene,$as_codban,$as_ctaban)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	  uf_select_catprovben
	//  Access:		  public
	//	Returns:	  String--- Retorno la cuenta contable del proveedor o beneficiario y como parametro de referenica el banco y la cuenta de banco del mismo
	//	Description:  Funcion que busca el banco, la cuenta de banbco y la cuenta contable del proveedor o beneficiario.
	//////////////////////////////////////////////////////////////////////////////
		
		$ls_codemp=$this->dat["codemp"];
		if($as_provbene=='P')
		{
			
			$ls_sql="SELECT codban,ctaban,sc_cuenta
					 FROM   rpc_proveedor 
					 WHERE  codemp='".$ls_codemp."' AND cod_pro='".$as_codprobene."'";
		}
		else
		{
			$ls_sql="SELECT codban,ctaban,sc_cuenta
					 FROM   rpc_beneficiario 
					 WHERE  codemp='".$ls_codemp."' AND ced_bene='".$as_codprobene."'";
		}	
		$rs_data=	$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en consulta,".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codban=$row["codban"];
				$as_ctaban=$row["ctaban"];
				$ls_cuenta_scg=$row["sc_cuenta"];
			}
			else
			{
				$ls_cuenta_scg="";
			}
			$this->io_sql->free_result($rs_data);
		}
		return $ls_cuenta_scg;
	
	}//Fin de uf_select_ctaprovbene
function uf_select_ctacxpclasificador($as_numsol,$as_provbene,$as_codprobene)
{
//////////////////////////////////////////////////////////////////////////////
//	Function:	  uf_select_ctacxpclasificador
// Access:		  public
//	Returns:	  String--- Retorno la cuenta contable del catalogo de clasificación de CXP
//	Description:  Funcion que busca la cuenta contable de la recepción o recepciones
//////////////////////////////////////////////////////////////////////////////
	$ls_codemp=$this->dat["codemp"];
	if($as_provbene=='P')
	{
		
		$ls_sql=	"SELECT sc_cuenta ".
					"	FROM   cxp_rd_scg, cxp_dt_solicitudes ". 
					"	WHERE  cxp_rd_scg.codemp='".$ls_codemp."' ".
					"	AND cxp_rd_scg.cod_pro='".$as_codprobene."' ".
					"	AND cxp_rd_scg.debhab='H' ".
					"	AND cxp_dt_solicitudes.numsol='".$as_numsol."' ".
					"	AND cxp_rd_scg.codemp=cxp_dt_solicitudes.codemp ".
					"	AND cxp_rd_scg.cod_pro=cxp_dt_solicitudes.cod_pro ".
					"	AND cxp_rd_scg.numrecdoc=cxp_dt_solicitudes.numrecdoc ";
	}
	else
	{
		$ls_sql=	"SELECT sc_cuenta ".
					"	FROM   cxp_rd_scg, cxp_dt_solicitudes ". 
					"	WHERE  cxp_rd_scg.codemp='".$ls_codemp."' ".
					"	AND cxp_rd_scg.ced_bene='".$as_codprobene."' ".
					"	AND cxp_rd_scg.debhab='H' ".
					"	AND cxp_dt_solicitudes.numsol='".$as_numsol."' ".
					"	AND cxp_rd_scg.codemp=cxp_dt_solicitudes.codemp ".
					"	AND cxp_rd_scg.cod_pro=cxp_dt_solicitudes.cod_pro ".
					"	AND cxp_rd_scg.numrecdoc=cxp_dt_solicitudes.numrecdoc ";
	}	
	$rs_data=	$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$this->is_msg_error="Error en consulta,".$this->fun->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$ls_cuenta_scg=$row["sc_cuenta"];
		}
		else
		{
			$ls_cuenta_scg="";
		}
		$this->io_sql->free_result($rs_data);
	}
	return $ls_cuenta_scg;

}//Fin de uf_select_ctacxpclasificador

function uf_select_mov_x_provedorbeneficiario($ls_numcarord,$ls_codban,$ls_ctaban,$ls_codope,$ls_codproben,$ls_tipproben)
{
		if($ls_tipproben=='P')
		{
			$ls_sql="SELECT numdoc 
					 FROM scb_movbco
					 WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND codope='".$ls_codope."' AND numcarord='".$ls_numcarord."' AND cod_pro='".$ls_codproben."'";
		}
		else
		{
			$ls_sql="SELECT numdoc 
					 FROM scb_movbco
					 WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND codope='".$ls_codope."' AND numcarord='".$ls_numcarord."' AND cod_pro='".$ls_codproben."'";
		}

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_mg_error="Error en metodo uf_select_documento,".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data)){  return true; }
			else{ return false;}			
		}
	}
	
function uf_procesar_movbanco($ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ldt_fecha,$ls_conmov,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto,
							  $ldec_monobjret,$ldec_monret,$ls_chevau,$ls_estmov,$li_estmovint,$li_cobrapaga,$ls_estbpd,$ls_procede,$ls_estreglib,$ls_tipproben,
							  $ls_numcarord,$ls_codfuefin,$ls_numordpagmin,$ls_codtipfon)
{
		$ls_codemp=$this->dat["codemp"];
		$ls_codusu=$_SESSION["la_logusr"];

		$this->uf_insert_movimiento($ls_codemp,$ls_codusu,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ldt_fecha,$ls_conmov,$ls_codconmov,$ls_codpro,$ls_cedbene,
									$ls_nomproben,$ldec_monto,$ldec_monobjret,$ldec_monret,$ls_chevau,$ls_estmov,$li_estmovint,$li_cobrapaga,$ls_estbpd,$ls_procede,
									$ls_estreglib,$ls_tipproben,$ls_numcarord,$ls_numordpagmin,$ls_codtipfon);

	}
	
function uf_select_monto_actual($ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ldt_fecha,$ldec_monto,$ldec_monobjret,$ldec_monret)
{
		$lb_valido=true;
		$ldt_fecha=$this->io_function->uf_convertirdatetobd($ldt_fecha);
		$ls_sql="SELECT monto,monret,monobjret 
				 FROM scb_movbco 
				 WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND numdoc='".$ls_numdoc."' AND codope='".$ls_codope."' AND fecmov='".$ldt_fecha."'";
		
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->is_msg_error="Error en select movimiento,".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
			$ldec_monto=0;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ldec_monto=$row["monto"];
				$ldec_monobjret=$row["monobjret"];
				$ldec_monret=$row["monret"];
			}
			else
			{
				$ldec_monto=0;
				$ldec_monobjret=0;
				$ldec_monret=0;
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}
	
function uf_update_monto_movimiento($ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ldt_fecha,$ldec_monto,$ldec_monobjret,$ldec_monret)
{
		////////////////////////////////////////////////////////////////////////////////////////////////
		//
		// -Funcion que inserta la cabecera del movimiento  bancario
		//
		///////////////////////////////////////////////////////////////////////////////////////////////
		$ldt_fecha=$this->io_function->uf_convertirdatetobd($ldt_fecha);
			
		$ls_sql="UPDATE scb_movbco SET monto=".$ldec_monto.",monobjret=".$ldec_monobjret.",monret=".$ldec_monret."
				 WHERE codemp='".$ls_codemp."' 
				   AND codban='".$ls_codban."' 
				   AND ctaban='".$ls_ctaban."'
				   AND numdoc='".$ls_numdoc."'
				   AND codope='".$ls_codope."' 
				   AND fecmov='".$ldt_fecha."'";
	
		$li_result=$this->io_sql->execute($ls_sql);

		if($li_result===false)
		{
			$this->is_msg_error=" Fallo Actualizacion de movimiento, ".$this->fun->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
			return false;
		}
		else
		{
			$this->is_msg_error="Registro Actualizado";
			return true;
		}
	}

	
function uf_insert_movimiento($ls_codemp,$ls_codusu,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ldt_fecha,$ls_conmov,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto,$ldec_monobjret,$ldec_monret,$ls_chevau,$ls_estmov,$li_estmovint,$li_cobrapaga,$ls_estbpd,$ls_procede,$ls_estreglib,$ls_tipproben,$ls_numcarord,$ls_numordpagmin,$ls_codtipfon)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que inserta la cabecera del movimiento  bancario
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$ldt_fecha=$this->io_function->uf_convertirdatetobd($ldt_fecha);
	
	$ls_sql="INSERT INTO scb_movbco(codemp,codusu,codban,ctaban,numdoc,codope,fecmov,conmov,codconmov,cod_pro,ced_bene,nomproben,monto,monobjret,monret,chevau,estmov,estmovint,estcobing,esttra,estbpd,procede,estcon,feccon,estreglib,tipo_destino,numcarord,codfuefin,numordpagmin,codtipfon)
			 VALUES                ('".$ls_codemp."','".$ls_codusu."','".$ls_codban."','".$ls_ctaban."','".$ls_numdoc."','".$ls_codope."','".$ldt_fecha."','".$ls_conmov."','".$ls_codconmov."','".$ls_codpro."','".$ls_cedbene."','".$ls_nomproben."',".$ldec_monto.",".$ldec_monobjret.",".$ldec_monret.",'".$ls_chevau."','".$ls_estmov."',".$li_estmovint.",".$li_cobrapaga.", 0    ,'".$ls_estbpd."','".$ls_procede."',   0  ,'1900-01-01','".$ls_estreglib."','".$ls_tipproben."','".$ls_numcarord."','--','".$ls_numordpagmin."','".$ls_codtipfon."')";

	$li_result=$this->io_sql->execute($ls_sql);
	if(($li_result===false))
	{
		$this->is_msg_error="Fallo insercion de movimiento, ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		print $this->io_sql->message;
		return false;		
	}
	else
	{
		$this->is_msg_error="Registro insertado";
		return true;		
	}	
}
	
function uf_procesar_carta_orden($ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_numsol,$ls_estmov,$ldec_monto,$ls_estdoc)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_procesar_emision_scq
	// Access:			public
	//	Returns:		Boolean Retorna si proceso correctamente
	//	Description:	Funcion que se encarga de guardar los detalles d ela emision de cheque
	//////////////////////////////////////////////////////////////////////////////
	
		$ls_codemp=$this->dat["codemp"];
	
		$ls_sql="INSERT INTO cxp_sol_banco(codemp,codban,ctaban,numdoc,codope,numsol,estmov,monto)
		 		 VALUES('".$ls_codemp."','".$ls_codban."','".$ls_ctaban."','".$ls_numdoc."','".$ls_codope."','".$ls_numsol."','".$ls_estmov."',".$ldec_monto.")";
		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en insert cxp_sol_banco,".$this->io_function->uf_convertirmsg($this->io_sql->message);		
			print $this->io_sql->message;
		}
		else
		{
			$lb_valido=true;
			if($ls_estdoc=='C')
			{
				$ls_sql="UPDATE scb_prog_pago
						 SET    estmov = '".$ls_estmov."'
	 					 WHERE  codemp='".$ls_codemp."' AND numsol='".$ls_numsol."'";
				$li_row=$this->io_sql->execute($ls_sql);
				if(($li_row===false))
				{
					$lb_valido=false;
					$this->is_msg_error="Error en actualizar scb_prog_pago, ".$this->io_function->uf_convertirmsg($this->io_sql->message);										
				}
				else
				{
					$lb_valido=true;							
				}				
			}
		}
		return $lb_valido;
	}//Fin de  uf_procesar_emision_chq
	
function uf_buscar_dt_cxpspg($as_numsol)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_buscar_dt_cxpspg
	// 	Access:			public
	//	Returns:		Boolean Retorna si proceso correctamente
	//	Description:	Funcion que se buscar el detalle presupuestario de una solicitud de pago 
	//////////////////////////////////////////////////////////////////////////////
		$li_row=0;
		$lb_valido=false;
		$aa_dt_cxpspg=array();
		$ls_codemp=$this->dat["codemp"];
		$ls_sql="SELECT numsol, numdoc, monto as montochq 
				 FROM cxp_sol_banco 
				 WHERE codemp='".$ls_codemp."' AND numsol ='".$as_numsol."' AND 
				 (estmov='N' OR estmov='C')";
		$rs_data=	$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en consulta,".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_row=$li_row+1;
				$ls_cheque=$row["numdoc"];
				$ls_numsol=$row["numsol"];
				$ldec_montochq=$row["montochq"];
				$ls_sql="SELECT codestpro, spg_cuenta, sum(monto) as monto, estcla
						 FROM scb_movbco_spg
		    			 WHERE codemp='".$ls_codemp."' AND procede_doc='CXPSOP' AND numdoc='".$ls_cheque."' AND documento ='".$ls_numsol."' 
						 GROUP BY codestpro, spg_cuenta, estcla ";	
				$rs_dt_spgchq=	$this->io_sql_aux->select($ls_sql);	

				if($rs_dt_spgchq===false)
				{
					$this->is_msg_error="Error en consulta,".$this->fun->uf_convertirmsg($this->io_sql_aux->message);
					print $this->is_msg_error;	
					$lb_valido=false;		
				}
				else
				{
					while($row=$this->io_sql_aux->fetch_row($rs_dt_spgchq))
					{
						$ls_estcla = $row["estcla"];
						$ls_codestpro1=substr($row["codestpro"],0,25);
						$ls_codestpro2=substr($row["codestpro"],25,25);
						$ls_codestpro3=substr($row["codestpro"],50,25);
						$ls_codestpro4=substr($row["codestpro"],75,25);	
						$ls_codestpro5=substr($row["codestpro"],100,25);
						$ls_spgcuenta=$row["spg_cuenta"];						
						$ldec_monto=$row["monto"];
						$this->ds_temp->insertRow("estcla",$ls_estcla);
						$this->ds_temp->insertRow("codestpro1",$ls_codestpro1);
						$this->ds_temp->insertRow("codestpro2",$ls_codestpro2);
						$this->ds_temp->insertRow("codestpro3",$ls_codestpro3);
						$this->ds_temp->insertRow("codestpro4",$ls_codestpro4);
						$this->ds_temp->insertRow("codestpro5",$ls_codestpro5);
						$this->ds_temp->insertRow("spg_cuenta",$ls_spgcuenta);
						$this->ds_temp->insertRow("monto",$ldec_monto);
					}
				$this->io_sql_aux->free_result($rs_dt_spgchq);
				} 
			}
		}
			if(array_key_exists("codestpro1",$this->ds_temp->data))
			{
				if($this->ds_temp->getRowCount("codestpro1")>0)
				{
					$arr_group[0]="codestpro1";
					$arr_group[1]="codestpro2";
					$arr_group[2]="codestpro3";
					$arr_group[3]="codestpro4";
					$arr_group[4]="codestpro5";
					$arr_group[5]="spg_cuenta";
					$arr_group[6]="estcla";
					//Agrupo el datastore por programaticas y cuentas y sumo el monto
					$this->ds_temp->group_by($arr_group,array('0'=>"monto"),$arr_group);
				}			
			}
		$li_row=0;
		$ls_sql="SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,sum(monto) as monto ,descripcion,estcla ".
				"	FROM spg_dt_cmp ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND procede='CXPSOP' ".
				"   AND comprobante='".$as_numsol."' ".
				" GROUP BY codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,descripcion,estcla ".
				" UNION ".
				"SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,sum(spg_dt_cmp.monto) as monto ,descripcion,estcla ".
				"	FROM spg_dt_cmp, cxp_dt_solicitudes, cxp_sol_dc ".
				" WHERE spg_dt_cmp.codemp='".$ls_codemp."' ".
				"   AND spg_dt_cmp.procede='CXPNOC' ".
				"   AND spg_dt_cmp.comprobante=LPAD(cxp_sol_dc.numdc,15,'0') ".
				"   AND cxp_dt_solicitudes.numsol='".$as_numsol."' ".
				"   AND cxp_dt_solicitudes.codemp = cxp_sol_dc.codemp ".
				"   AND cxp_dt_solicitudes.numsol = cxp_sol_dc.numsol ".
				"   AND cxp_dt_solicitudes.numrecdoc = cxp_sol_dc.numrecdoc ".
				"   AND cxp_dt_solicitudes.codtipdoc = cxp_sol_dc.codtipdoc ".
				"   AND cxp_dt_solicitudes.ced_bene = cxp_sol_dc.ced_bene ".
				"   AND cxp_dt_solicitudes.cod_pro = cxp_sol_dc.cod_pro ".
				" GROUP BY codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,descripcion,estcla ".
				" UNION  ".
				"SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,sum(spg_dt_cmp.monto) as monto ,descripcion,estcla ".
				"	FROM spg_dt_cmp, cxp_dt_solicitudes, cxp_sol_dc ".
				" WHERE spg_dt_cmp.codemp='".$ls_codemp."' ".
				"   AND spg_dt_cmp.procede='CXPNOD' ".
				"   AND spg_dt_cmp.comprobante=LPAD(cxp_sol_dc.numdc,15,'0') ".
				"   AND cxp_dt_solicitudes.numsol='".$as_numsol."' ".
				"   AND cxp_dt_solicitudes.codemp = cxp_sol_dc.codemp ".
				"   AND cxp_dt_solicitudes.numsol = cxp_sol_dc.numsol ".
				"   AND cxp_dt_solicitudes.numrecdoc = cxp_sol_dc.numrecdoc ".
				"   AND cxp_dt_solicitudes.codtipdoc = cxp_sol_dc.codtipdoc ".
				"   AND cxp_dt_solicitudes.ced_bene = cxp_sol_dc.ced_bene ".
				"   AND cxp_dt_solicitudes.cod_pro = cxp_sol_dc.cod_pro ".
				" GROUP BY codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,descripcion,estcla ";
		$rs_data_dtcxpspg=	$this->io_sql->select($ls_sql);
		if($rs_data_dtcxpspg===false)
		{
			$this->is_msg_error="Error en consulta,".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data_dtcxpspg))
			{
				$li_row=$li_row+1;
				$ls_codestpro1=$row["codestpro1"];
				$aa_dt_cxpspg["codestpro1"][$li_row] = $ls_codestpro1;
				$ls_codestpro2=$row["codestpro2"];
				$aa_dt_cxpspg["codestpro2"][$li_row] = $ls_codestpro2;
				$ls_codestpro3=$row["codestpro3"];
				$aa_dt_cxpspg["codestpro3"][$li_row] = $ls_codestpro3;
				$ls_codestpro4=$row["codestpro4"];
				$aa_dt_cxpspg["codestpro4"][$li_row] = $ls_codestpro4;
				$ls_codestpro5=$row["codestpro5"];
				$aa_dt_cxpspg["codestpro5"][$li_row] = $ls_codestpro5;
				$ls_spg_cuenta=$row["spg_cuenta"];
				$aa_dt_cxpspg["spg_cuenta"][$li_row] = $ls_spg_cuenta;			
				$ldec_monto=$row["monto"];
				$aa_dt_cxpspg["monto"][$li_row]      = $ldec_monto;			
				$ls_estcla=$row["estcla"];
				$aa_dt_cxpspg["estcla"][$li_row]      = $ls_estcla;			
			}//End While
			$this->io_sql->free_result($rs_data_dtcxpspg);
			//Asigno la matriz de detalles presupuestarios al datastore.
			$arr_group[0]="codestpro1";
			$arr_group[1]="codestpro2";
			$arr_group[2]="codestpro3";
			$arr_group[3]="codestpro4";
			$arr_group[4]="codestpro5";
			$arr_group[5]="spg_cuenta";
			$arr_group[6]="estcla";
			$this->ds_sol->data=$aa_dt_cxpspg;
			$this->ds_sol->group_by($arr_group,array('0'=>"monto"),$arr_group);
			$li_row=$this->ds_sol->getRowCount("codestpro1");
			if($li_row>0)
			{
				for($li_j=1;$li_j<=$li_row;$li_j++)
				{
					$ls_estcla     = $this->ds_sol->getValue("estcla",$li_j);
					$ls_codestpro1 = $this->ds_sol->getValue("codestpro1",$li_j);
					$ls_codestpro2 = $this->ds_sol->getValue("codestpro2",$li_j);
					$ls_codestpro3 = $this->ds_sol->getValue("codestpro3",$li_j);
					$ls_codestpro4 = $this->ds_sol->getValue("codestpro4",$li_j);
					$ls_codestpro5 = $this->ds_sol->getValue("codestpro5",$li_j);
					$ls_spg_cuenta = $this->ds_sol->getValue("spg_cuenta",$li_j);
					$ldec_monto    = $this->ds_sol->getValue("monto",$li_j);
					$li_row_tots   = $this->ds_temp->getRowCount("codestpro1");
					if($li_row_tots>0)
					{
						for($li_i=1;$li_i<=$li_row_tots;$li_i++)
						{
							$ls_stacla     = $this->ds_temp->getValue("estcla",$li_i);
							$ls_estpro1    = $this->ds_temp->getValue("codestpro1",$li_i);
							$ls_estpro2    = $this->ds_temp->getValue("codestpro2",$li_i);
							$ls_estpro3    = $this->ds_temp->getValue("codestpro3",$li_i);
							$ls_estpro4    = $this->ds_temp->getValue("codestpro4",$li_i);
							$ls_estpro5    = $this->ds_temp->getValue("codestpro5",$li_i);
							$ls_cuentaspg  = $this->ds_temp->getValue("spg_cuenta",$li_i);
							$ldec_montotmp = $this->ds_temp->getValue("monto",$li_i);
							
							if(($ls_codestpro1==$ls_estpro1)&&($ls_codestpro2==$ls_estpro2)&&($ls_codestpro3==$ls_estpro3)&&($ls_codestpro4==$ls_estpro4)&&($ls_codestpro5==$ls_estpro5)&&($ls_spg_cuenta==$ls_cuentaspg)&&($ls_estcla==$ls_stacla))
							{
								$ldec_new_monto=doubleval($ldec_monto)-doubleval($ldec_montotmp);
								$this->ds_sol->updateRow("monto",$ldec_new_monto,$li_j);
							}//End if
						}//End For
					}//End if	
				}
			}			
		}//End if
	}//Fin uf_buscar_dt_cxpspg.
	
function uf_procesar_dtmov($ls_codemp, $ls_codban, $ls_cuenta_banco, $ls_numcarord, $ls_codope,$ls_estmov, $ls_cod_pro, $ls_cedbene, $ls_numsol, $ldec_monto,$ls_ctabanbene)
{
		$lb_valido=true;
		$ls_sql="INSERT INTO scb_dt_movbco(codemp, codban, ctaban, numdoc, codope, estmov, cod_pro, ced_bene, numsolpag, monsolpag,ctabanbene)
				 VALUES('$ls_codemp','$ls_codban','$ls_cuenta_banco','$ls_numcarord','$ls_codope','$ls_estmov','$ls_cod_pro','$ls_cedbene','$ls_numsol','$ldec_monto','$ls_ctabanbene')";

		$li_exec=$this->io_sql->execute($ls_sql);				 
		if($li_exec===false)
		{
			$this->is_msg_error="Error en consulta,".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
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
		$ls_sql="SELECT codfuefin ".
				"  FROM scb_movbco_fuefinanciamiento ".
				" WHERE	codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codfuefin='".$as_codfuefin."' ";
		$rs_data=$this->io_sql->select($ls_sql);	
		if($rs_data===false)
		{
			$this->is_msg_error="Error en consulta,".$this->fun->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;	
			$lb_valido=false;		
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			else
			{
				$ls_sql="INSERT INTO scb_movbco_fuefinanciamiento(codemp, codban, ctaban, numdoc, codope, estmov, codfuefin) VALUES ".
						"('".$as_codemp."','".$as_codban."','".$as_ctaban."','".$as_numdoc."','".$as_codope."','".$as_estmov."','".$as_codfuefin."')";
				$li_numrow=$this->io_sql->execute($ls_sql);
				if($li_numrow===false)
				{
					$lb_valido=false;
					print $this->io_sql->message;
					$this->msg->message("CLASE->Movimiento de Banco MÉTODO->uf_insert_fuentefinancimiento ERROR->".$this->fun->uf_convertirmsg($this->io_sql->message));
				}
			}
		}
		return $lb_valido;
	}
//-----------------------------------------------------------------------------------------------------------------------------------
}
?>