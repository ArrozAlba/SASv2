<?php
class sigesp_scb_c_anulacion
{
	var $SQL;
	var $fun;
	var $msg;
	var $is_msg_error;	
	var $ds_sol;
	var $dat;
	var $io_seguridad;
	var $is_empresa;
	var $is_sistema;
	var $is_logusr;
	var $is_ventanas;
	
	function sigesp_scb_c_anulacion($aa_security)
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_seguridad= new sigesp_c_seguridad();
		require_once("../shared/class_folder/sigesp_include.php");
		$sig_inc=new sigesp_include();
		$con=$sig_inc->uf_conectar();
		$this->SQL=new class_sql($con);
		$this->fun=new class_funciones();
		$this->msg=new class_mensajes();
		$this->dat=$_SESSION["la_empresa"];
		$this->is_empresa = $aa_security['empresa'];
		$this->is_sistema = $aa_security["sistema"];
		$this->is_logusr  = $aa_security["logusr"];	
		$this->is_ventana = $aa_security["ventanas"];
		
					
	}//Fin del constructor

	function uf_cargar_documentos($ls_codemp,$as_codope,&$object,&$li_total)
	{
		//////////////////////////////////////////////////////////////////////////////
		//
		//	Function:	 uf_cargar_documentos
		// 					
		// Access:		 public
		//
		//	Returns:	 Boolean Retorna si encontro o no errores en la consulta
		//	
		//	Parameters:  - $ls_codemp=Codigo de la empresa
		//				 - $as_codope=Operacion del documento(CH,ND,NC,DP,RE)
		//				 - $object=Matriz de objetos con los valores del resulset que seran enviados a la clase grid_param , estos son retornados como parametro por referencia.
		//			  	 - $li_total=Total de registros encontrados
		//
		//	Description:	Funcion que se encarga de llenar el object con los datos de
		//					los documentos  para el proceso de anulacion o eliminacion de movimientos.
		//               
		//////////////////////////////////////////////////////////////////////////////
	
		$li_row_total=0;$li_dw_row=0;$li_x=0;$li_row=0;
		
		/*if($as_proben== 'P')
		{*/
				$ls_sql="SELECT a.numdoc as numdoc,a.codban as codban,b.nomban as nomban,a.ctaban as ctaban,a.ced_bene as ced_bene,a.cod_pro as cod_pro,a.nomproben as nomproben,a.fecmov as fecmov,a.codope as codope,a.estmov as estmov,a.monto as monto,a.monret as monret
						 FROM scb_movbco a,scb_banco b
						 WHERE a.codope='".$as_codope."'  and a.estmov='C' AND a.codban=b.codban AND  a.codemp='".$ls_codemp."'
						 ORDER BY a.numdoc";			
	
		/*}
		elseif($as_proben=='B')
		{
			  $ls_sql="SELECT   a.numsol as NumSol,a.ced_bene as CodProBen,b.nombene as NomProBen,a.fecemisol as FecEmiSol,c.fecpropag as FecProPag,a.consol as ConSol,a.estprosol as EstProSol,a.monsol as MonSol,a.obssol as ObsSol,c.codban as CodBan,d.nomban as NomBan,c.ctaban as CtaBan,e.dencta as DenCta
					   FROM     CXP_solicitudes a, RPC_beneficiario b , scb_prog_pago c,scb_banco d,scb_ctabanco e
					   WHERE    a.codemp=b.codemp AND a.codemp=c.codemp AND a.codemp= d.codemp AND a.codemp=e.codemp AND a.codemp='".$ls_codemp."' AND a.tipproben='".$as_proben."' AND a.numsol=c.numsol  AND a.ced_bene=b.ced_bene AND a.estprosol='S' 
					   AND      c.codban=d.codban AND c.ctaban=e.ctaban AND d.codban=e.codban AND c.estmov='P'
					   ORDER BY a.numsol asc";			
		}*/
		
				
		$rs_documentos	=$this->SQL->select($ls_sql);
		
		if($rs_documentos===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en metodo uf_cargar_documentos".$this->fun->uf_convertirmsg($this->SQL->message);
			$data="";
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_documentos))
			{
				$li_row=$li_row+1;
				$ls_numdoc=$row["numdoc"];
				$ls_codban=$row["codban"];
				$ls_nomban=$row["nomban"];
				$ls_ctaban=$row["ctaban"];
				$ls_cedbene=$row["ced_bene"];
				$ls_codpro=$row["cod_pro"];
				$ls_nomproben=$row["nomproben"];
				$ld_fecha=$row["fecmov"];
				$ld_fecmov=$this->fun->uf_convertirfecmostrar($ld_fecha);
				$ls_codope=$row["codope"];
				$ls_estmov=$row["estmov"];
				$ldec_monto=$row["monto"];
				$ldec_monret=$row["monret"];
				$object[$li_row][1] = "<input type=checkbox name=chksel".$li_row."   id=chksel".$li_row." value=1 style=width:15px;height:15px>";		
				$object[$li_row][2] = "<input type=text name=txtnumdoc".$li_row."    value='".$ls_numdoc."'    class=sin-borde readonly style=text-align:center size=17 maxlength=15>";
				$object[$li_row][3] = "<input type=hidden name=txtcodban".$li_row."  value='".$ls_codban."'  ><input type=text name=txtnomban".$li_row." value='".$ls_nomban."' class=sin-borde readonly style=text-align:left size=20 maxlength=20 >";
				$object[$li_row][4] = "<input type=text name=txtctaban".$li_row."    value='".$ls_ctaban."'    class=sin-borde readonly style=text-align:center size=27 maxlength=25  >";
				$object[$li_row][5] = "<input type=text name=txtfecmov".$li_row."    value='".$ld_fecmov."'    class=sin-borde readonly style=text-align:center size=10 maxlength=10>";			
				$object[$li_row][6] = "<input type=hidden name=txtcedbene".$li_row."   value='".$ls_cedbene."'><input type=hidden name=txtcodpro".$li_row."    value='".$ls_codpro."'><input type=text name=txtnomproben".$li_row." value='".$ls_nomproben."' class=sin-borde readonly style=text-align:left size=17 maxlength=15>";							
				$object[$li_row][7] = "<input type=text name=txtmonto".$li_row."     value='".number_format($ldec_monto,2,",",".")."'   class=sin-borde readonly style=text-align:right size=18 maxlength=22>"; 								
			}
			if($li_row==0)
			{
				$li_row=1;
				$object[$li_row][1] = "<input type=checkbox name=chksel".$li_row."   id=chksel".$li_row." value=1 >";		
				$object[$li_row][2] = "<input type=text name=txtnumdoc".$li_row."    value=''    class=sin-borde readonly style=text-align:center size=17 maxlength=15>";
				$object[$li_row][3] = "<input type=text name=txtcodban".$li_row."    value=''    class=sin-borde readonly style=text-align:center size=22 maxlength=22>";
				$object[$li_row][4] = "<input type=text name=txtctaban".$li_row."    value=''    class=sin-borde readonly style=text-align:center size=22 maxlength=22>";
				$object[$li_row][5] = "<input type=text name=txtfecmov".$li_row."    value=''    class=sin-borde readonly style=text-align:center size=10 maxlength=10>";			
				$object[$li_row][6] = "<input type=hidden name=txtcedbene".$li_row."   value=''><input type=hidden name=txtcodpro".$li_row."    value=''    ><input type=text name=txtnomproben".$li_row." value=''    class=sin-borde readonly style=text-align:left size=17 maxlength=15>";			
				$object[$li_row][7] = "<input type=text name=txtmonto".$li_row."     value=''    class=sin-borde readonly style=text-align:right size=17 maxlength=15>";	 			
				$this->is_msg_error = "No encontro registros";
			}
		}
		$li_total=$li_row;	
	}//Fin uf_cargar_documentos
	
	function uf_procesar_anulacion($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope)
	{
		$ls_codemp=$this->dat["codemp"];
		$ls_sql="SELECT * 
				 FROM scb_movbco 
				 WHERE codemp='".$ls_codemp."' AND numdoc='".$ls_numdoc."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND codope='".$ls_codope."' ";
	
		$rs_anul=$this->SQL->select($ls_sql);
		
		if($rs_anul===false)
		{
			$this->is_msg_error="Error en consulta, ".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido=false;
		}
		else
		{
			
		}
	}
}
?>