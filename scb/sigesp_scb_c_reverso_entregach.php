<?php
class sigesp_scb_c_reverso_entregach
{
	var $dat;
 	var $SQL;
	var $is_msg_error;
	var $fun;
	var $la_security;
	function sigesp_scb_c_reverso_entregach($aa_security)
	{
		$this->dat=$_SESSION["la_empresa"];
		require_once("../shared/class_folder/sigesp_include.php");
		$this->sig_inc=new sigesp_include();
		$con=$this->sig_inc->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->SQL=new class_sql($con);
		require_once("../shared/class_folder/class_funciones.php");
		$this->fun=new class_funciones();
		$this->la_security=$aa_security;
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();	
		
	}

	function uf_cargar_cheques($ls_cedula,$ls_nombre,$as_codproben,$as_tipproben,$object,$li_row)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	   uf_cargar_cheques
		// Access:			public
		//	Returns:			Boolean Retorna si proceso correctamente
		//	Description:	Funcion que se encarga de cargar el datastore con los cheques
		//					que ya han sido entregados a los porveedores o beneficiarios
		//////////////////////////////////////////////////////////////////////////////
		
		$li_row    = 0;
		$ls_sqlaux = "";
		$ls_codemp = $this->dat["codemp"];
		if($as_tipproben=='P')
		{
			if (!empty($as_codproben))
			   {
			     $ls_sqlaux = " AND a.cod_pro= '".$as_codproben."' ";
			   }
			$ls_sql =  "SELECT a.numdoc as numdoc,a.conmov as conmov,a.fecmov as fecmov,a.monto as monto,a.chevau as chevau,a.codban as codban,b.nomban as nomban,a.ctaban as ctaban,c.dencta as dencta,a.cod_pro as codproben,d.nompro as nomproben 
						  FROM scb_movbco a,scb_banco b,scb_ctabanco c,rpc_proveedor d 
						 WHERE a.estmov='C' 
						   AND (a.estbpd='P' OR a.estbpd='D') 
						   AND a.estimpche=1 
						   AND a.codope='CH' 
						   AND a.emicheproc=1 $ls_sqlaux					   
						   AND a.tipo_destino='P'
						   AND a.codemp = '".$ls_codemp."' 
					       AND a.emicheced like '%$ls_cedula%' 
					       AND a.emichenom like '%$ls_nombre%'
						   AND a.codban = b.codban 
						   AND a.ctaban=c.ctaban 
						   AND a.cod_pro=d.cod_pro 
						   AND a.codemp = b.codemp 
						   AND c.codemp=a.codemp 
						   AND d.codemp = a.codemp";
		}
		elseif($as_tipproben=='B')
		{
			if (!empty($as_codproben))
			   {
			     $ls_sqlaux = " AND trim(a.ced_bene) = '".trim($as_codproben)."' ";
			   }
			$ls_sql =	"SELECT a.numdoc as numdoc,a.conmov as conmov,a.fecmov as fecmov,a.monto as monto,a.chevau as chevau,a.codban as codban,b.nomban as nomban,a.ctaban as ctaban,c.dencta as dencta,a.ced_bene as codproben ,d.nombene as nomproben
						   FROM scb_movbco a,scb_banco b,scb_ctabanco c,rpc_beneficiario d 
						  WHERE a.estmov='C' 
						    AND (a.estbpd='B' OR a.estbpd='D') 
							AND a.estimpche=1 
							AND a.codope='CH' 
							AND a.emicheproc=1 $ls_sqlaux 							
							AND a.tipo_destino='B'
						    AND a.codemp = '".$ls_codemp."' 
						    AND a.emicheced like '%$ls_cedula%' 
							AND a.emichenom like '%$ls_nombre%'
							AND a.codban = b.codban 
							AND a.ctaban=c.ctaban 
							AND a.ced_bene=d.ced_bene 
							AND a.codemp = b.codemp 
							AND c.codemp=a.codemp 
							AND d.codemp = a.codemp";
		}
		else
		{
			$ls_sql =	"SELECT a.numdoc as numdoc,a.conmov as conmov,a.fecmov as fecmov,a.monto as monto,a.chevau as chevau,a.codban as codban,b.nomban as nomban,a.ctaban as ctaban,c.dencta as dencta,a.ced_bene as codproben ,d.nombene as nomproben
						 FROM  scb_movbco a,scb_banco b,scb_ctabanco c,rpc_beneficiario d 
						 WHERE a.estmov='C' AND (a.estbpd='D') AND a.estimpche=1 AND a.codope='CH'   
						 AND a.codban = b.codban AND a.ctaban=c.ctaban AND a.ced_bene=d.ced_bene AND a.ced_bene= '".$as_codproben."' AND tipo_destino='-'  AND emicheproc=1
						 AND a.codemp = '".$ls_codemp."' AND a.codemp = b.codemp AND c.codemp=a.codemp AND d.codemp = a.codemp 
						 AND emicheced like '%$ls_cedula%' AND emichenom like '%$ls_nombre%'";
		
		}
		$ls_sql=$ls_sql." AND a.ctaban IN (SELECT codintper ".
						"					 FROM sss_permisos_internos ".
						"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
						"				    UNION ".
						"				   SELECT codintper ".
						"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
						"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
		$rs_result=$this->SQL->select($ls_sql);
		if(($rs_result===false))
		{
			print $this->SQL->message;	
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			while($row=$this->SQL->fetch_row($rs_result))
			{
				$li_row++;
				$ls_numdoc=$row["numdoc"];
				$ls_desdoc=$row["conmov"];
				$ldec_monto=$row["monto"];
				$ls_codban=$row["codban"];
				$ls_ctaban=$row["ctaban"];
				$ls_voucher=$row["chevau"];
															
				$object[$li_row][1] = "<input type=checkbox name=chksel".$li_row."   id=chksel".$li_row." value=1 style=width:15px;height:15px>";		
				$object[$li_row][2] = "<input type=text     name=txtnumdoc".$li_row."    value='".$ls_numdoc."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
				$object[$li_row][3] = "<input type=text     name=txtdesdoc".$li_row."    value='".$ls_desdoc."' title='".$ls_desdoc."' class=sin-borde readonly style=text-align:left size=30 maxlength=254>";
				$object[$li_row][4] = "<input type=text     name=txtmonto".$li_row."     value='".number_format($ldec_monto,2,",",".")."' class=sin-borde readonly style=text-align:right size=18 maxlength=22>";
				$object[$li_row][5] = "<input type=text     name=txtcodban".$li_row."    value='".$ls_codban."' class=sin-borde readonly style=text-align:center size=3 maxlength=3>"; 
				$object[$li_row][6] = "<input type=text     name=txtcuenta".$li_row."    value='".$ls_ctaban."' class=sin-borde readonly style=text-align:center size=27 maxlength=25>";
				$object[$li_row][7] = "<input type=text     name=txtvoucher".$li_row."   value='".$ls_voucher."' class=sin-borde readonly style=text-align:center size=27 maxlength=25>";
			}
			if($li_row==0)
			{
				$li_total=5;
				for($li_row=1;$li_row<=$li_total;$li_row++)
				{
					$object[$li_row][1] = "<input type=checkbox name=chksel".$li_row."   id=chksel".$li_row." value=1 style=width:15px;height:15px onClick='return false;'>";		
					$object[$li_row][2] = "<input type=text     name=txtnumdoc".$li_row."       value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
					$object[$li_row][3] = "<input type=text     name=txtdesdoc".$li_row."    value='' class=sin-borde readonly style=text-align:left size=30 maxlength=254>";
					$object[$li_row][4] = "<input type=text     name=txtmonto".$li_row."     value='' class=sin-borde readonly style=text-align:center size=18 maxlength=22>";
					$object[$li_row][5] = "<input type=text     name=txtcodban".$li_row."    value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>"; 
					$object[$li_row][6] = "<input type=text     name=txtcuenta".$li_row."    value='' class=sin-borde readonly style=text-align:right size=22 maxlength=22>";
					$object[$li_row][7] = "<input type=text     name=txtvoucher".$li_row."   value='' class=sin-borde readonly style=text-align:right size=25 maxlength=22>";
				}
				$li_row=$li_total;
			}
		}		
		return $lb_valido;
	}

	function uf_procesar_reversoch($arr_entregach,$as_codproben,$as_tipproben,$ai_procesado)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	uf_procesar_entregach
		//    Access:			public
		//	 Returns:			Boolean Retorna si proceso correctamente
		//Description:	Funcion que se encarga de procesar la entrega del cheque al 
		//						proveedor o al beneficiario actualizando los campos de 
		//                cedula,nombre y fecha de la persona que recibio los cheques
		//////////////////////////////////////////////////////////////////////////////
		
		$ls_codemp = $this->dat["codemp"];
		$li_total  = count($arr_entregach["numdoc"]);
		$ls_sqlaux = "";
		$this->SQL->begin_transaction();
		for ($li_i=1;$li_i<=$li_total;$li_i++)
		    {
			  $ls_codban  = $arr_entregach["codban"][$li_i];
			  $ls_ctaban  = $arr_entregach["ctaban"][$li_i];
			  $ls_numdoc  = $arr_entregach["numdoc"][$li_i];
			  $ls_descripcion = " Se realizó el reverso de la entrega del cheque No $ls_numdoc ";
			  if (!empty($as_codproben))
				 {
			       if ($as_tipproben=='P')
			          {
					    $ls_sqlaux = " AND cod_pro='".$as_codproben."' ";
					    $ls_descripcion = $ls_descripcion." del proveedor $as_codproben "; 
					  }				     
				   elseif($as_tipproben=='B')
					  {
					    $ls_sqlaux = " AND trim(ced_bene) = '".trim($as_codproben)."' "; 
						$ls_descripcion = $ls_descripcion." del beneficiario $as_codproben";
					  }
				 }

			  $ls_sql = "UPDATE scb_movbco 
						    SET emicheproc='".$ai_procesado."',emicheced='',emichenom='',emichefec='1900-01-01' 
					      WHERE codemp = '".$ls_codemp."'
						    AND codban = '".$ls_codban."'
							AND ctaban = '".$ls_ctaban."'
							AND numdoc = '".$ls_numdoc."'
						    AND codope = 'CH' $ls_sqlaux";

			$li_result=$this->SQL->execute($ls_sql);
			if ($li_result===false)
			   {
				 $lb_valido=false;
				 $this->is_msg_error="Error en actualizar entrega de cheque, ".$this->fun->uf_convertirmsg($this->SQL->message);
		  	   }
			else
			   {
			     $lb_valido=true;
			     ///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
			     $ls_evento="UPDATE";						
			     $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
			     ////////////////////////////////////////////////////////////////////////////////////////////////////////////								
			}
		}		
		return $lb_valido;
	}
}
?>