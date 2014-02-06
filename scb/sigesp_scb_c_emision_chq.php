<?php
class sigesp_scb_c_emision_chq
{
	var $io_sql;
	var $fun;
	var $msg;
	var $is_msg_error;	
	var $ds_sol;
	var $dat;
	var $ds_temp;
	var $io_sql_aux;
	
	function sigesp_scb_c_emision_chq()
	{
		require_once("class_funciones_banco.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/sigesp_include.php");
		$sig_inc=new sigesp_include();
		$con=$sig_inc->uf_conectar();
		$this->io_sql=new class_sql($con);
		$this->io_sql_aux = new class_sql($con);
		$this->io_funscb  = new class_funciones_banco();
		$this->fun=new class_funciones();
		$this->msg=new class_mensajes();
		$this->dat=$_SESSION["la_empresa"];	
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];	
		$this->ds_temp=new class_datastore();
		$this->ds_sol=new class_datastore();
    }

	function  uf_cargar_programaciones($as_tipproben,$as_codproben,$as_codban,$as_ctaban,&$object,&$li_totsolpag,&$ls_conmov,$as_numordpagmin,$as_codtipfon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargar_programaciones
		//		   Access: private
		//	    Arguments: $as_tipproben = Tipo de Proveedor (P) o Beneficiario (B).
		//                 $as_codproben = Código del Proveedor/Beneficiario.
		//                 $as_codban    = Código del Banco.
		//                 $as_ctaban    = Cuenta Bancaria.
		//                 $object       = Arreglo cargado con las Solicitudes de Pago listas para Emisión del Cheque.
		//                 $li_totsolpag = Total de Solicitudes de pago previstas para la Emisión del Cheque.
		//                 $ls_conmov    = Concepto de la Solicitud de Pago.
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualiza la solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 17/03/2007. 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $li_i = 0;
		$li_estciespg = $this->io_funscb->uf_obtenervalor("hidestciespg",0);
	    $li_estciespi = $this->io_funscb->uf_obtenervalor("hidestciespi",0);
	    $li_estciescg = $this->io_funscb->uf_obtenervalor("hidestciescg",0);

	    $ls_codemp = $this->dat["codemp"];
	    $ld_fecha  = date("Y-m-d");
	    if ($as_tipproben=='P')
	       {
		     $ls_tabla  = ', rpc_proveedor';
		     $ls_campo  = 'cod_pro';
		     $ls_campos = ',cxp_solicitudes.cod_pro as cod_pro, rpc_proveedor.nompro';
		     $ls_sqlaux = " AND cxp_solicitudes.tipproben='P' AND cxp_solicitudes.cod_pro=rpc_proveedor.cod_pro";
		   }
	    elseif($as_tipproben=='B')
	       {
		     $ls_tabla  = ', rpc_beneficiario';
		     $ls_campo  = 'ced_bene';
		     $ls_campos = ',cxp_solicitudes.ced_bene,rpc_beneficiario.nombene,rpc_beneficiario.apebene';
		     $ls_sqlaux = " AND cxp_solicitudes.tipproben='B' AND cxp_solicitudes.ced_bene=rpc_beneficiario.ced_bene";
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
		$ls_sql = "SELECT cxp_solicitudes.numsol as numsol,
		   		 	      cxp_solicitudes.consol as consol,
						  cxp_solicitudes.monsol as monsol,
						  scb_prog_pago.codban as codban,
						  scb_prog_pago.ctaban as ctaban,
						  cxp_solicitudes.codfuefin $ls_campos,
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
						      AND cxp_rd.ced_bene=cxp_rd_spg.ced_bene) as detspg,(SELECT CAST(estcon as char)||CAST(estpre as char) FROM cxp_documento WHERE cxp_documento.codtipdoc=cxp_dt_solicitudes.codtipdoc) as estcodtipdoc
	 			     FROM cxp_solicitudes,cxp_dt_solicitudes, scb_prog_pago $ls_tabla
				    WHERE cxp_solicitudes.codemp='".$ls_codemp."' 
					  AND trim(cxp_solicitudes.$ls_campo)='".trim($as_codproben)."' 
					  AND cxp_solicitudes.estprosol='S' 
					  AND scb_prog_pago.estmov='P' 
					  AND scb_prog_pago.codban='".$as_codban."' 
					  AND scb_prog_pago.ctaban='".$as_ctaban."'
					  AND scb_prog_pago.fecpropag<='".$ld_fecha."' $ls_sqlaux   
				      AND cxp_solicitudes.numsol=scb_prog_pago.numsol 
					  AND cxp_solicitudes.codemp=scb_prog_pago.codemp 
  				      AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol 
					  AND cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp 
					  AND scb_prog_pago.ctaban IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
							"				    UNION ".
							"				   SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
							"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)		  
					ORDER BY cxp_solicitudes.numsol ASC";
		$rs_data = $this->io_sql->select($ls_sql);//echo "SQL=>".$ls_sql.'<br>';
		if ($rs_data===false)
		{
			 $this->is_msg_error="Error en consulta, ".$this->fun->uf_convertirmsg($this->io_sql->message);
			 echo $this->io_sql->message;
			 $lb_valido=false;
		}
		else
		{
		     while (!$rs_data->EOF)
	         {
				     $li_detspg = $rs_data->fields["detspg"];
					 if (($li_estciespg==1 || $li_estciespi==1) && ($li_detspg==0 && $li_estciescg==0) || 
				        ($li_estciespg==0 && $li_estciespi==0 && $li_estciescg==0))
				     {
						  if ($as_tipproben=='P')
						  {
							   $ls_codprovben = trim($rs_data->fields["cod_pro"]);
							   $ls_nomproben  = $rs_data->fields["nompro"];
						  }
						  else
						  { 
							   $ls_codprovben = trim($rs_data->fields["ced_bene"]);
							   $ls_nomproben  = $rs_data->fields["nombene"].', '.$rs_data->fields["apebene"];
						  }
						  $ls_numsol    = trim($rs_data->fields["numsol"]);
						  $li_estcodtipdoc=$rs_data->fields["estcodtipdoc"];
						  $li_estcon=substr($li_estcodtipdoc,0,1);
						  $li_estpre=substr($li_estcodtipdoc,1,1);
						  if($li_estpre!='3'&&$li_estpre!='4')// Si el documento aplica imputacion presupuestaria verifico si el usuario tiene asignada
						  {								 // las estructura para filtrar solo las estructuras disponibles para el usuario.
						 		$lb_valido_estructura=$this->uf_validar_asignacion_estructura($ls_numsol,$ls_codprovben,$as_tipproben);
						  }
						  else
						  {
						 		$lb_valido_estructura=true;
						  }	
						  if($lb_valido_estructura)
						  {
							  $li_i++;
							  $ls_consol	= $rs_data->fields["consol"];
							  $ldec_monsol  = $rs_data->fields["monsol"];
							  $ls_codban	= $rs_data->fields["codban"];
							  $ls_ctaban    = $rs_data->fields["ctaban"];
							  $ls_codfuefin = $rs_data->fields["codfuefin"];
							  $ldec_montocancelado = $this->uf_select_solcxp_montocancelado($ls_codemp,$ls_numsol,$ls_codban,$ls_ctaban);
							  $ai_montonotas=0;
							  $lb_valido=$this->uf_load_notas_asociadas($ls_codemp,$ls_numsol,&$ai_montonotas);
							  $ldec_montopendiente  = ($ldec_monsol-$ldec_montocancelado)+$ai_montonotas;
							  $object[$li_i][1]  = "<input type=checkbox name=chk".$li_i."               id=chk".$li_i."               value=1                class=sin-borde onClick=javascript:uf_selected('".$li_i."');  >";
							  $object[$li_i][2]  = "<input type=text     name=txtnumsol".$li_i."         id=txtnumsol".$li_i."         value='".$ls_numsol."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
							  $object[$li_i][3]  = "<input type=text     name=txtconsol".$li_i."         id=txtnumsol".$li_i."         value='".$ls_consol."' title='".$ls_consol."' class=sin-borde readonly style=text-align:left size=45 maxlength=254>";
							  $object[$li_i][4]  = "<input type=text     name=txtmonsol".$li_i."         id=txtnumsol".$li_i."         value='".number_format($ldec_monsol,2,",",".")."' class=sin-borde readonly style=text-align:right size=18 maxlength=18>";
							  $object[$li_i][5]  = "<input type=text     name=txtmontopendiente".$li_i." id=txtmontopendiente".$li_i." value='".number_format($ldec_montopendiente,2,",",".")."' class=sin-borde readonly style=text-align:right size=18 maxlength=18>";
							  $object[$li_i][6]  = "<input type=text     name=txtmonto".$li_i."          id=txtmonto".$li_i."          value='".number_format($ldec_montopendiente,2,",",".")."' class=sin-borde onBlur=javascript:uf_actualizar_monto(".$li_i."); onKeyPress=\"return(currencyFormat(this,'.',',',event));return keyRestrict(event,'1234567890,');\"  style=text-align:right size=18 maxlength=18>".
												   "<input type=hidden   name=txtcodfuefin".$li_i."      id=txtcodfuefin".$li_i."      value='".$ls_codfuefin."'>";
						 }	  
					 }
				     $rs_data->MoveNext();
			 }
			 if ($li_i==0)
			    {
				  $li_i=1;
				  $object[$li_i][1]  = "<input name=chk".$li_i." type=checkbox id=chk".$li_i." value=1 class=sin-borde onClick=javascript:uf_selected('".$li_i."');  >";
				  $object[$li_i][2]  = "<input type=text name=txtnumsol".$li_i." value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
				  $object[$li_i][3]  = "<input type=text name=txtconsol".$li_i." value='' class=sin-borde readonly style=text-align:left size=45 maxlength=254>";
				  $object[$li_i][4]  = "<input type=text name=txtmonsol".$li_i." value='".number_format(0,2,",",".")."' class=sin-borde readonly style=text-align:right size=18 maxlength=18>";
				  $object[$li_i][5]  = "<input type=text name=txtmontopendiente".$li_i."  value='".number_format(0,2,",",".")."' class=sin-borde readonly style=text-align:right size=18 maxlength=18>";				
				  $object[$li_i][6]  = "<input type=text name=txtmonto".$li_i."  value='".number_format(0,2,",",".")."' class=sin-borde onBlur=javascript:uf_actualizar_monto(".$li_i."); style=text-align:right size=18 maxlength=18>".
					  				   "<input type=hidden  name=txtcodfuefin".$li_i."  id=txtcodfuefin".$li_i."  value=''>";
				}
			 $this->io_sql->free_result($rs_data);
		   }
		$li_totsolpag=$li_i;
	}//Fin de uf_cargar_programaciones

	
function uf_select_solcxp_montocancelado($ls_codemp,$ls_numsol,$ls_codban,$ls_ctaban)
{
//////////////////////////////////////////////////////////////////////////////
//	Function:	uf_select_solcxp_montocancelado
// Access:			public
//	Returns:			Decimal--- Valor decimal con el monto que ha sido cancelado o abonado para la solicitud
//	Description:	Funcion que suma los montos cancelados o abonados para cada solicitud
//////////////////////////////////////////////////////////////////////////////
	
	/*$ls_sql="SELECT sum(monto) as monto
			 FROM   cxp_sol_banco 
			 WHERE  codemp='".$ls_codemp."' AND numsol='".$ls_numsol."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND estmov<>'A' AND estmov<>'O'";*/

	$ls_sql = "SELECT sum(monto) as monto
			     FROM cxp_sol_banco 
			    WHERE codemp='".$ls_codemp."'
				  AND numsol='".$ls_numsol."'
				  AND estmov<>'A' 
				  AND estmov<>'O'";
	$rs_data = $this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$this->is_msg_error="Error en consulta,".$this->fun->uf_convertirmsg($this->io_sql->message);
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
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
function uf_select_ctaprovbene($as_provbene,$as_codprobene,$as_codban,$as_ctaban)
{
//////////////////////////////////////////////////////////////////////////////
//	Function:	  uf_select_catprovben
// Access:		  public
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
		$this->is_msg_error="Error en consulta,".$this->fun->uf_convertirmsg($this->io_sql->message);
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
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
function uf_actualizar_estatus_ch($ls_codban,$ls_ctaban,$ls_numdoc,$ls_numchequera)
{
  if (!empty($ls_numdoc)&(!empty($ls_numchequera)))
	 { 
	   $ls_sql = "SELECT numche
				    FROM scb_cheques
				   WHERE codban='".$ls_codban."' 
				     AND ctaban='".$ls_ctaban."'
					 AND numche='".$ls_numdoc."'
					 AND numchequera='".$ls_numchequera."'";
	   $rs_data=$this->io_sql->select($ls_sql);
	   if ($rs_data===false)
		  {
		    $this->is_msg_error="Error en actualizar estatus Cheque.".$this->fun->uf_convertirmsg($this->io_sql->message);
		    return false;
		  }
	   else
		  { 
		    if ($row=$this->io_sql->fetch_row($rs_data))
			   {
			     $ls_sql = "UPDATE scb_cheques 
						       SET estche=1
						     WHERE codban='".$ls_codban."' 
							   AND ctaban='".$ls_ctaban."'
							   AND numche='".$ls_numdoc."'
							   AND numchequera='".$ls_numchequera."'";
				
				$rs_data = $this->io_sql->execute($ls_sql);
				if ($rs_data===false)
				   {
					 $this->is_msg_error="Error en actualizar estatus Cheque.".$this->fun->uf_convertirmsg($this->io_sql->message);
					 return false;					
				   }
				else
				   {
				     return true;
				   }
			   }
			else
			   {
			     return true;
			   }
		  }
	}
  else
	 {
	   return true;
	 }
}

function uf_procesar_emision_chq($ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_numsol,$ls_estmov,$ldec_monto,$ls_estdoc)
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
	$rs_data = $this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
		 $lb_valido=false;
		 $this->is_msg_error="Error en insert cxp_sol_banco,".$this->fun->uf_convertirmsg($this->io_sql->message);
		 print $this->io_sql->message;	
	   }
	else
	   {
		 $lb_valido=true;
		 if ($ls_estdoc=='C')
		    {
			  $ls_sql = "UPDATE scb_prog_pago
					        SET estmov = '".$ls_estmov."'
					      WHERE codemp='".$ls_codemp."'
						    AND numsol='".$ls_numsol."'";
			  $rs_data = $this->io_sql->execute($ls_sql);
			  if ($rs_data===false)
			     {
				   $lb_valido=false;
				   $this->is_msg_error="Error en actualizar scb_prog_pago, ".$this->fun->uf_convertirmsg($this->io_sql->message);	
				   print $this->is_msg_error;					
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
  $lb_valido    = false;
  $aa_dt_cxpspg = array();
  $ls_codemp=$this->dat["codemp"];
		
  $ls_sql="SELECT numsol, numdoc, monto as montochq 
	 	     FROM cxp_sol_banco 
		    WHERE codemp='".$ls_codemp."' 
			  AND numsol ='".$as_numsol."' 
			  AND (estmov='N' OR estmov='C')";
  $rs_cheques=$this->io_sql->select($ls_sql);
  if ($rs_cheques===false)
	 {
	   $this->is_msg_error="Error en consulta,".$this->fun->uf_convertirmsg($this->io_sql->message);
	   echo $this->io_sql->message;
	 }
  else
	 {
	   while($row=$this->io_sql->fetch_row($rs_cheques))
			{				
			  $li_row        = $li_row+1;
			  $ls_cheque     = $row["numdoc"];
			  $ls_numsol     = $row["numsol"];
			  $ldec_montochq = $row["montochq"];
			  
			  $ls_sql="SELECT codestpro, spg_cuenta, sum(monto) as monto, estcla
						 FROM scb_movbco_spg
		    			WHERE codemp='".$ls_codemp."' 
						  AND procede_doc='CXPSOP' 
						  AND numdoc='".$ls_cheque."' 
						  AND documento ='".$ls_numsol."' 
					    GROUP BY codestpro, spg_cuenta, estcla";
			  $rs_dt_spgchq = $this->io_sql_aux->select($ls_sql);
			  if ($rs_dt_spgchq===false)
				 {
					$this->is_msg_error="Error en consulta,".$this->fun->uf_convertirmsg($this->io_sql_aux->message);
					echo $this->io_sql_aux->message;	
					$lb_valido=false;		
				 }
			  else
				 {
				   while($row=$this->io_sql_aux->fetch_row($rs_dt_spgchq))
				        {
						  $ls_estcla     = $row["estcla"]; 
						  $ldec_monto	 = $row["monto"];
						  $ls_spgcuenta  = trim($row["spg_cuenta"]);	
						  $ls_codestpro1 = substr($row["codestpro"],0,25);
						  $ls_codestpro2 = substr($row["codestpro"],25,25);
						  $ls_codestpro3 = substr($row["codestpro"],50,25);
						  $ls_codestpro4 = substr($row["codestpro"],75,25);	
						  $ls_codestpro5 = substr($row["codestpro"],100,25);
						  $this->ds_temp->insertRow("estcla",$ls_estcla);
						  $this->ds_temp->insertRow("monto",$ldec_monto);
						  $this->ds_temp->insertRow("spg_cuenta",$ls_spgcuenta);
						  $this->ds_temp->insertRow("codestpro1",$ls_codestpro1);
						  $this->ds_temp->insertRow("codestpro2",$ls_codestpro2);
						  $this->ds_temp->insertRow("codestpro3",$ls_codestpro3);
						  $this->ds_temp->insertRow("codestpro4",$ls_codestpro4);
						  $this->ds_temp->insertRow("codestpro5",$ls_codestpro5);
					    }
				   $this->io_sql_aux->free_result($rs_dt_spgchq);
				 } 
			}
	 }
  if (array_key_exists("codestpro1",$this->ds_temp->data))
	 {		  
	   if ($this->ds_temp->getRowCount("codestpro1")>0)
		  {				
		    $arr_group[0]="codestpro1";
		 	$arr_group[1]="codestpro2";
		 	$arr_group[2]="codestpro3";
		 	$arr_group[3]="codestpro4";
		 	$arr_group[4]="codestpro5";
			$arr_group[5]="spg_cuenta";
			$arr_group[6]="estcla";
			$this->ds_temp->group_by($arr_group,array('0'=>"monto"),$arr_group);
		  }			
	 }
		$li_row=0;
		$ls_conrecdoc=$_SESSION["la_empresa"]["conrecdoc"].'<br>';
		if($ls_conrecdoc!=1)
		{
			$ls_sql="SELECT spg_dt_cmp.codestpro1 as codestpro1,
			                spg_dt_cmp.codestpro2 as codestpro2,
							spg_dt_cmp.codestpro3 as codestpro3,
							spg_dt_cmp.codestpro4 as codestpro4,
							spg_dt_cmp.codestpro5 as codestpro5,
							spg_dt_cmp.spg_cuenta as spg_cuenta,
							sum(spg_dt_cmp.monto) as monto,
							spg_dt_cmp.descripcion as descripcion,
							spg_dt_cmp.estcla as estcla
					   FROM sigesp_cmp, spg_dt_cmp
					  WHERE spg_dt_cmp.codemp='".$ls_codemp."'
					    AND spg_dt_cmp.procede='CXPSOP'
					    AND spg_dt_cmp.comprobante='".$as_numsol."'
					    AND sigesp_cmp.codemp=spg_dt_cmp.codemp
						AND sigesp_cmp.procede=spg_dt_cmp.procede
						AND sigesp_cmp.comprobante=spg_dt_cmp.comprobante
						AND sigesp_cmp.fecha=spg_dt_cmp.fecha
						AND sigesp_cmp.codban=spg_dt_cmp.codban
						AND sigesp_cmp.ctaban=spg_dt_cmp.ctaban						
					  GROUP BY spg_dt_cmp.codestpro1,spg_dt_cmp.codestpro2,spg_dt_cmp.codestpro3,spg_dt_cmp.codestpro4,
					           spg_dt_cmp.codestpro5,spg_dt_cmp.spg_cuenta,spg_dt_cmp.estcla,spg_dt_cmp.descripcion
					 UNION ".
					"SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,sum(spg_dt_cmp.monto) as monto,descripcion,estcla ".
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
					" GROUP BY codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,estcla,descripcion ".
					" UNION  ".
					"SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,sum(spg_dt_cmp.monto) as monto,descripcion,estcla ".
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
					" GROUP BY codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,estcla,descripcion ";
		}
		else
		{   
			$rs_dararec=$this->uf_obtener_recepciones_asociadas($as_numsol);
			$li_i=0;
			$ls_cadena="";
			while ($row=$this->io_sql->fetch_row($rs_dararec))
			      {
				    $li_i++;
					$ls_numrecdoc=$row["numrecdoc"];
				    $ls_codrecdoc=$row["codrecdoc"];
                    if (empty($ls_cadena))
					   {
					     $ls_cadena = "AND (spg_dt_cmp.comprobante='".$ls_codrecdoc."'";
					   }				
				    else
				       {
					     $ls_cadena=$ls_cadena." OR spg_dt_cmp.comprobante='".$ls_codrecdoc."'";
				       }
			      }
			if (!empty($ls_cadena))
			   {
				 $ls_cadena = $ls_cadena." OR spg_dt_cmp.comprobante='".$as_numsol."')";
			   }
			else
			   {
			     $ls_cadena = " AND comprobante='".$as_numsol."'";
			   }
	        $ls_sql = "SELECT spg_dt_cmp.codestpro1 as codestpro1,
			                  spg_dt_cmp.codestpro2 as codestpro2,
							  spg_dt_cmp.codestpro3 as codestpro3,
							  spg_dt_cmp.codestpro4 as codestpro4,
							  spg_dt_cmp.codestpro5 as codestpro5,
					          spg_dt_cmp.spg_cuenta as spg_cuenta,
							  sum(spg_dt_cmp.monto) as monto,
							  spg_dt_cmp.descripcion as descripcion,
							  spg_dt_cmp.estcla as estcla
						 FROM sigesp_cmp, spg_dt_cmp
					    WHERE spg_dt_cmp.codemp='".$ls_codemp."' 
						  AND sigesp_cmp.procede='CXPRCD' $ls_cadena 
						  AND sigesp_cmp.codemp=spg_dt_cmp.codemp
						  AND sigesp_cmp.procede=spg_dt_cmp.procede
						  AND sigesp_cmp.comprobante=spg_dt_cmp.comprobante
						  AND sigesp_cmp.fecha=spg_dt_cmp.fecha
						  AND sigesp_cmp.codban=spg_dt_cmp.codban
						  AND sigesp_cmp.ctaban=spg_dt_cmp.ctaban
					    GROUP BY spg_dt_cmp.codestpro1,spg_dt_cmp.codestpro2,spg_dt_cmp.codestpro3,spg_dt_cmp.codestpro4,spg_dt_cmp.codestpro5,
								 spg_dt_cmp.spg_cuenta,spg_dt_cmp.estcla,spg_dt_cmp.descripcion
					 UNION ".
					"SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,sum(spg_dt_cmp.monto) as monto,descripcion,estcla ".
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
					" GROUP BY codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,estcla,descripcion ".
					" UNION  ".
					"SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,sum(spg_dt_cmp.monto) as monto,descripcion,estcla ".
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
					" GROUP BY codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,estcla,descripcion ";
		}
		$rs_dt_cxpspg=	$this->io_sql->select($ls_sql);
		if($rs_dt_cxpspg===false)
		{
			$this->is_msg_error="Error en consulta,".$this->fun->uf_convertirmsg($this->io_sql->message);			
			return false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_dt_cxpspg))
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
				$ls_descripcion=$row["descripcion"];
				$aa_dt_cxpspg["descripcion"][$li_row]      = $ls_descripcion;
				$ls_estcla = $row["estcla"];
				$aa_dt_cxpspg["estcla"][$li_row] = $ls_estcla;
			}//End While
			//Asigno la matriz de detalles presupuestarios al datastore.		
			$arr_group[0]="codestpro1";
			$arr_group[1]="codestpro2";
			$arr_group[2]="codestpro3";
			$arr_group[3]="codestpro4";
			$arr_group[4]="codestpro5";
			$arr_group[5]="spg_cuenta";
			$arr_group[6]="estcla";
			//Agrupo el datastore por programaticas y cuentas y sumo el monto
			$this->ds_sol->data=$aa_dt_cxpspg;
			
			$this->ds_sol->group_by($arr_group,array('0'=>"monto"),$arr_group);
			$li_row=$this->ds_sol->getRowCount("codestpro1");
			if($li_row>0)
			{
				for($li_j=1;$li_j<=$li_row;$li_j++)
				{
					$ls_estcla     = $this->ds_sol->getValue("estcla",$li_j);
					$ldec_monto    = $this->ds_sol->getValue("monto",$li_j);
					$ls_spg_cuenta = trim($this->ds_sol->getValue("spg_cuenta",$li_j));
					$ls_codestpro1 = $this->ds_sol->getValue("codestpro1",$li_j);
					$ls_codestpro2 = $this->ds_sol->getValue("codestpro2",$li_j);
					$ls_codestpro3 = $this->ds_sol->getValue("codestpro3",$li_j);
					$ls_codestpro4 = $this->ds_sol->getValue("codestpro4",$li_j);
					$ls_codestpro5 = $this->ds_temp->getValue("codestpro5",$li_j);
					$li_row_tots   = $this->ds_temp->getRowCount("codestpro1");
					if($li_row_tots>0)
					{
						for($li_i=1;$li_i<=$li_row_tots;$li_i++)
						{
							$ls_tipcla     = $this->ds_temp->getValue("estcla",$li_i);
							$ls_estpro1    = $this->ds_temp->getValue("codestpro1",$li_i);
							$ls_estpro2    = $this->ds_temp->getValue("codestpro2",$li_i);
							$ls_estpro3    = $this->ds_temp->getValue("codestpro3",$li_i);
							$ls_estpro4    = $this->ds_temp->getValue("codestpro4",$li_i);
							$ls_estpro5    = $this->ds_temp->getValue("codestpro5",$li_i);
							$ls_cuentaspg  = trim($this->ds_temp->getValue("spg_cuenta",$li_i));
							$ldec_montotmp = $this->ds_temp->getValue("monto",$li_i);
							if(($ls_codestpro1==$ls_estpro1)&&($ls_codestpro2==$ls_estpro2)&&($ls_codestpro3==$ls_estpro3)&&($ls_codestpro4==$ls_estpro4)&&($ls_codestpro5==$ls_estpro5)&&($ls_spg_cuenta==$ls_cuentaspg)&&($ls_estcla==$ls_tipcla))
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

//-----------------------------------------------------------------------------------------------------------------------------------
function uf_obtener_recepciones_asociadas($as_numsol)
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
	$ls_sql="SELECT cxp_dt_solicitudes.numrecdoc, cxp_rd.codrecdoc".
			"  FROM cxp_solicitudes,cxp_dt_solicitudes,cxp_rd ".	
			" WHERE cxp_dt_solicitudes.codemp='".$this->ls_codemp."' ".
			"   AND cxp_dt_solicitudes.numsol='".$as_numsol."'".
			"   AND cxp_dt_solicitudes.codemp=cxp_solicitudes.codemp".
			"   AND cxp_dt_solicitudes.numsol=cxp_solicitudes.numsol".
			"   AND cxp_dt_solicitudes.codemp=cxp_rd.codemp".
			"   AND cxp_dt_solicitudes.numrecdoc=cxp_rd.numrecdoc".
			"   AND cxp_dt_solicitudes.codtipdoc=cxp_rd.codtipdoc".
			"   AND cxp_dt_solicitudes.cod_pro=cxp_rd.cod_pro".
			"   AND cxp_dt_solicitudes.ced_bene=cxp_rd.ced_bene";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$this->msg->message("CLASE->Solicitud MÉTODO->uf_load_recepciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		return false;
	}
	return $rs_data;
}// end function uf_load_recepciones
//-----------------------------------------------------------------------------------------------------------------------------------

function uf_load_retenciones_iva_cxp($as_codemp,$as_numsol)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_load_retenciones_iva_cxp
//		   Access: public
//		 Argument: $as_codemp = Código de la Empresa.
//                 $as_numsol = Número de la Solicitud de Pago.
//	  Description: Función que extrae la sumatoria de las retenciones de IVA Cuentas Por Pagar asociadas
//                 a una Solicitud de Pago.
//	   Creado Por: Ing. Néstor Falcón.
//     Modificado por: Ing. Jennifer Rivero
// Fecha Creación: 23/06/2008
// Fecha de Modificación:17/10/2008
////////////////////////////////////////////////////////////////////////////////////////////////////////

  $li_i = 0;
  $la_deducciones = array();
  $ls_sql = "SELECT max(cxp_rd_deducciones.codded) as codded, max(sigesp_deducciones.dended) as dended, 
				    max(cxp_rd_deducciones.sc_cuenta) as sc_cuenta, max(cxp_rd_deducciones.monobjret) as monobjret, 
					COALESCE(sum(cxp_rd_deducciones.monret),0) as montotret
			   FROM cxp_dt_solicitudes, cxp_solicitudes, cxp_rd_deducciones, cxp_rd, sigesp_deducciones
			  WHERE cxp_solicitudes.codemp = '".$as_codemp."'
			    AND cxp_solicitudes.numsol = '".$as_numsol."'
			    AND sigesp_deducciones.iva=1
			    AND sigesp_deducciones.islr=0
			    AND sigesp_deducciones.estretmun=0
			    AND sigesp_deducciones.otras=0
			    AND cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp
			    AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol
			    AND cxp_dt_solicitudes.codemp=cxp_rd.codemp
			    AND cxp_dt_solicitudes.numrecdoc=cxp_rd.numrecdoc
			    AND cxp_dt_solicitudes.codtipdoc=cxp_rd.codtipdoc
			    AND cxp_dt_solicitudes.ced_bene=cxp_rd.ced_bene
			    AND cxp_dt_solicitudes.cod_pro=cxp_rd.cod_pro
			    AND cxp_dt_solicitudes.codemp=cxp_rd_deducciones.codemp
			    AND cxp_dt_solicitudes.numrecdoc=cxp_rd_deducciones.numrecdoc
			    AND cxp_dt_solicitudes.codtipdoc=cxp_rd_deducciones.codtipdoc
			    AND cxp_dt_solicitudes.ced_bene=cxp_rd_deducciones.ced_bene
			    AND cxp_dt_solicitudes.cod_pro=cxp_rd_deducciones.cod_pro
			    AND cxp_rd.codemp=cxp_rd_deducciones.codemp
			    AND cxp_rd.numrecdoc=cxp_rd_deducciones.numrecdoc
			    AND cxp_rd.codtipdoc=cxp_rd_deducciones.codtipdoc 
			    AND cxp_rd.ced_bene=cxp_rd_deducciones.ced_bene
			    AND cxp_rd.cod_pro=cxp_rd_deducciones.cod_pro
			    AND sigesp_deducciones.codemp=cxp_rd_deducciones.codemp
			    AND sigesp_deducciones.codded=cxp_rd_deducciones.codded
			  GROUP BY cxp_solicitudes.numsol";
	$ls_sql = $ls_sql." UNION ".
	          "SELECT max(cxp_rd_deducciones.codded) as codded, max(sigesp_deducciones.dended) as dended, 
				    max(cxp_rd_deducciones.sc_cuenta) as sc_cuenta, max(cxp_rd_deducciones.monobjret) as monobjret, 
					COALESCE(sum(cxp_rd_deducciones.monret),0) as montotret
			   FROM cxp_dt_solicitudes, cxp_solicitudes, cxp_rd_deducciones, cxp_rd, sigesp_deducciones
			  WHERE cxp_solicitudes.codemp = '".$as_codemp."'
			    AND cxp_solicitudes.numsol = '".$as_numsol."'
			    AND sigesp_deducciones.iva=0
			    AND sigesp_deducciones.islr=1
			    AND sigesp_deducciones.estretmun=0
			    AND sigesp_deducciones.otras=0
			    AND cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp
			    AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol
			    AND cxp_dt_solicitudes.codemp=cxp_rd.codemp
			    AND cxp_dt_solicitudes.numrecdoc=cxp_rd.numrecdoc
			    AND cxp_dt_solicitudes.codtipdoc=cxp_rd.codtipdoc
			    AND cxp_dt_solicitudes.ced_bene=cxp_rd.ced_bene
			    AND cxp_dt_solicitudes.cod_pro=cxp_rd.cod_pro
			    AND cxp_dt_solicitudes.codemp=cxp_rd_deducciones.codemp
			    AND cxp_dt_solicitudes.numrecdoc=cxp_rd_deducciones.numrecdoc
			    AND cxp_dt_solicitudes.codtipdoc=cxp_rd_deducciones.codtipdoc
			    AND cxp_dt_solicitudes.ced_bene=cxp_rd_deducciones.ced_bene
			    AND cxp_dt_solicitudes.cod_pro=cxp_rd_deducciones.cod_pro
			    AND cxp_rd.codemp=cxp_rd_deducciones.codemp
			    AND cxp_rd.numrecdoc=cxp_rd_deducciones.numrecdoc
			    AND cxp_rd.codtipdoc=cxp_rd_deducciones.codtipdoc 
			    AND cxp_rd.ced_bene=cxp_rd_deducciones.ced_bene
			    AND cxp_rd.cod_pro=cxp_rd_deducciones.cod_pro
			    AND sigesp_deducciones.codemp=cxp_rd_deducciones.codemp
			    AND sigesp_deducciones.codded=cxp_rd_deducciones.codded
			  GROUP BY cxp_solicitudes.numsol";
  $rs_data = $this->io_sql->select($ls_sql); 
  if ($rs_data===false)
     {
	   $lb_valido = false;
	   $this->msg->message("CLASS->sigesp_scb_report.php;MÉTODO->uf_load_retenciones_iva_cxp;ERROR->".$this->fun->uf_convertirmsg($this->io_sql->message));
	   echo $this->io_sql->message;
	 }		  
  else
     {
	   $li_numrows = $this->io_sql->num_rows($rs_data);
	   if ($li_numrows>0)
	      {
		   	while($row=$this->io_sql->fetch_row($rs_data))
				 {
				   $li_i++;
				   $la_deducciones["codded"][$li_i]    = $row["codded"];
				   $la_deducciones["dended"][$li_i]    = $row["dended"];
				   $la_deducciones["sc_cuenta"][$li_i] = $row["sc_cuenta"];				   
				   $la_deducciones["monobjret"][$li_i] = $row["monobjret"];
				   $la_deducciones["monret"][$li_i]    = $row["montotret"];
				 }
		  }
	 }
  return $la_deducciones;			  
}
//--------------------------------------------------------------------------------------------------------------------------------------
    function uf_buscar_anticipos($cod_prov, $ced_bene, &$monsal)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_buscar_anticipos
	//	          Access:  public
	//	        Arguments  as_codemp //  Código de la Empresa.	
	//	         Returns:  lb_valido.
	//	     Description:  Función que busca si posee pago de anticipos (Contabilizados)
	//     Elaborado Por:  Ing. Jennifer Rivero
	// Fecha de Creación:  06/10/2008       Fecha Última Actualización:
	////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=true;	
		$ls_sql= "  SELECT scb_movbco_anticipo.codemp, scb_movbco_anticipo.codban, scb_movbco_anticipo.ctaban, 
						   scb_movbco_anticipo.numdoc, scb_movbco_anticipo.codope, scb_movbco_anticipo.estmov, 
						   scb_movbco_anticipo.codamo, scb_movbco_anticipo.monamo, scb_movbco_anticipo.monsal, 
						   scb_movbco_anticipo.montotamo, scb_movbco_anticipo.sc_cuenta,
						   scb_movbco.cod_pro, scb_movbco.ced_bene
					  FROM scb_movbco_anticipo
					  JOIN scb_movbco ON (scb_movbco.codemp = scb_movbco_anticipo.codemp
									 AND  scb_movbco.codban = scb_movbco_anticipo.codban
									 AND  scb_movbco.ctaban = scb_movbco_anticipo.ctaban
									 AND  scb_movbco.numdoc = scb_movbco_anticipo.numdoc
									 AND  scb_movbco.codope = scb_movbco_anticipo.codope
									 AND  scb_movbco.estmov = scb_movbco_anticipo.estmov)
					  WHERE scb_movbco_anticipo.codemp='".$this->ls_codemp."'
						AND scb_movbco_anticipo.estmov='C'
						AND scb_movbco.estant='1'
						AND scb_movbco.cod_pro='".$cod_prov."'
						AND scb_movbco.ced_bene='".$ced_bene."'
						AND scb_movbco_anticipo.monsal>0 "; 
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en metodo uf_buscar_anticipos".$this->fun->uf_convertirmsg($this->SQL->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$monsal=$row["monsal"]; 
			}
		}
		return $lb_valido;
    }// fin de uf_buscar_anticipos
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
    function uf_guardar_anticipos($codban, $ctaban, $numdoc, $codope, $estmov, $codamo, $sc_cuenta,
	                              $ls_monamo, $ls_monsal)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_guardar_anticipos
	//	          Access:  public
	//	        Arguments  
	//	         Returns:  lb_valido.
	//	     Description:  Función que amortiza el saldo del anticipo
	//     Elaborado Por:  Ing. Jennifer Rivero
	// Fecha de Creación:  07/10/2008       Fecha Última Actualización:
	////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=true;	
		$ls_sql= "  UPDATE scb_movbco_anticipo ".
                 "     SET monamo=".$ls_monamo.", ".
				 "         monsal=".$ls_monsal." ".				 			
                 "  WHERE  codemp='".$this->ls_codemp."'".
				 "    AND  codban='".$codban."'".
				 "    AND  ctaban='".$ctaban."'".
				 "    AND  numdoc='".$numdoc."'".
				 "    AND  codope='".$codope."'".
				 "    AND  estmov='".$estmov."'".
				 "    AND  codamo='".$codamo."'".
				 "    AND  sc_cuenta='".$sc_cuenta."'"; 
		$rs_data=$this->io_sql->execute($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en metodo uf_guardar_anticipos".$this->fun->uf_convertirmsg($this->SQL->message);
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
    }// uf_guardar_anticipos
//---------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------
    function select_dt_contable($as_codban, $as_ctaban, $as_numdoc, $as_codope, $ae_estmov, $as_scg_cuenta, $as_debhab,
	                            $as_codded,$as_documento,&$ldec_actual)
	{
		$lb_valido=true;
		$ls_codemp=$this->ls_codemp;	
		$ls_sql="SELECT monto 
				   FROM scb_movbco_scg
				  WHERE codemp='".$ls_codemp."'
					AND codban='".$as_codban."'
					AND ctaban='".$als_ctaban."'
					AND numdoc='".$as_numdoc."' 
					AND codope='".$as_codope."'
					AND estmov='".$as_estmov."'
					AND scg_cuenta='".$as_scg_cuenta."' 
					AND debhab='".$as_debhab."'
					AND codded='".$as_codded."'
					AND documento='".$as_documento."'"; 
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
				$ldec_actual=0;
			}
		}	
		return $lb_valido;		
	}/// fin de select_dt_contable
//--------------------------------------------------------------------------------------------------------------------------------------
    function uf_contable_anticipo($as_codban, $as_ctaban, $as_numdoc, $as_codope, $ae_estmov, $as_scg_cuenta, $as_debhab,
	                              $as_codded,$as_documento,$as_desmov, $as_procede,$as_monto,$as_monobjret)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_contable_anticipo
	//	          Access:  public
	//	        Arguments  
	//	         Returns:  lb_valido.
	//	     Description:  Función que inserta los asientos contables
	//     Elaborado Por:  Ing. Jennifer Rivero
	// Fecha de Creación:  07/10/2008       Fecha Última Actualización:
	////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=true;
		$ls_codemp=$this->ls_codemp;
		$ldec_actual=0;
		$as_monto=str_replace(",",".",$as_monto);
		$as_monobjret=str_replace(",",".",$as_monobjret);
		$this->select_dt_contable($as_codban, $as_ctaban, $as_numdoc, $as_codope, $ae_estmov, $as_scg_cuenta, $as_debhab,
	                              $as_codded,$as_documento,&$ldec_actual);
		if ($ldec_actual==0)
		{			
			$ls_sql= "  INSERT INTO scb_movbco_scg(codemp, codban, ctaban, numdoc, codope, estmov, scg_cuenta, debhab, ".
					 "                             codded, documento, desmov, procede_doc, monto, monobjret)           ".
					 "      VALUES ('".$ls_codemp."','".$as_codban."','".$as_ctaban."','".$as_numdoc."','".$as_codope."', ".
					 "              '".$ae_estmov."','".$as_scg_cuenta."','".$as_debhab."','".$as_codded."','".$as_documento."', ".
					 "              '".$as_desmov."','".$as_procede."',".$as_monto.", ".$as_monobjret.");";
			$rs_data=$this->io_sql->execute($ls_sql); 
			if ($rs_data===false)
			{
				$lb_valido=false;
				$this->is_msg_error="Error en metodo uf_contable_anticipo".$this->fun->uf_convertirmsg($this->SQL->message);
			}
			else
			{
				$lb_valido=true;
			}
		}// fin del if		
		return $lb_valido;
	}// fin uf_contable_anticipo()
	
	function uf_validar_asignacion_estructura($as_numsol,$as_codproben,$as_tipproben)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//     Function:  uf_validar_asignacion_estructura
		//   Parametros:  $as_numsol    - Numero de Solicitud
		//				  $as_codproben - Codigo del proveedor/Benficiario
		// 				  $as_tipproben - Tipo de receptor del pago
		//  Observacion:  Funcion que valida que el usuario tenga asignada las estructuras presupuestarias 
		//				  del detalle presupuestario de la solicitud.
		//Desarrollador:  Ing. Nelson Barraez
		//	   Creacion:  22/07/2010   
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		if($as_tipproben=='P')
		{
			$ls_aux=" AND cxp_solicitudes.cod_pro='".$as_codproben."' ";
		}
		else
		{
			$ls_aux=" AND cxp_solicitudes.ced_bene='".$as_codproben."' ";
		}
		
		$ls_sql = "SELECT codestpro
				   FROM cxp_solicitudes,cxp_dt_solicitudes,cxp_rd_spg
				  WHERE cxp_solicitudes.codemp = '".$_SESSION["la_empresa"]["codemp"]."' 
					AND cxp_solicitudes.numsol = '".trim($as_numsol)."' ".$ls_aux. 
				  " AND cxp_solicitudes.codemp = cxp_dt_solicitudes.codemp 
					AND cxp_solicitudes.numsol = cxp_dt_solicitudes.numsol
					AND cxp_dt_solicitudes.numrecdoc=cxp_rd_spg.numrecdoc
					AND cxp_dt_solicitudes.codtipdoc=cxp_rd_spg.codtipdoc
					AND cxp_dt_solicitudes.cod_pro=cxp_rd_spg.cod_pro
					AND cxp_dt_solicitudes.ced_bene=cxp_rd_spg.ced_bene 
					AND codestpro||estcla IN (SELECT codintper
												FROM sss_permisos_internos
											   WHERE codusu='".$_SESSION["la_logusr"]."' AND codemp = '".$_SESSION["la_empresa"]["codemp"]."' 
											  UNION
											  SELECT codintper
												FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos
											   WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codemp = '".$_SESSION["la_empresa"]["codemp"]."' 
												 AND sss_permisos_internos_grupo.codemp = sss_usuarios_en_grupos.codemp AND sss_permisos_internos_grupo.codgru = sss_usuarios_en_grupos.codgru)";
	  $rs_data = $this->io_sql->select($ls_sql);
	  if ($rs_data===false)
	  {
		   $this->msg->message("Error en uf_validar_asignacion_estructura ");	   
	  }
	  else
	  {
		   if ($row=$this->io_sql->fetch_row($rs_data))
		   {
				$lb_valido=true;
		   }
	  }
	  return $lb_valido;
	}
//---------------------------------------------------------------------------------------------------------------------------------------
}// fin de la clase
?>