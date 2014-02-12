<?php
class sigesp_c_cuentas_banco
{
	var $dat;
	var $SQL;
	var $fun;
	
	function sigesp_c_cuentas_banco()
	{
		$sig_inc=new sigesp_include();
		$con=$sig_inc->uf_conectar();
		$this->SQL=new class_sql($con);
		$this->fun=new class_funciones();	
		$this->dat = $_SESSION["la_empresa"];
	}



	function uf_verificar_saldo($as_codban,$as_ctaban,$ldec_saldo)
	{
		/////////////////////////////////////////////////////////////////////////////
		// Function	    : uf_verificar_saldo
		//	Return	    : ldec_saldo
		//	Descripcion : Funcion que se encarga de obtener el saldo disponible para
		//				  el banco y cuenta recibido como parametro
		/////////////////////////////////////////////////////////////////////////////
		$ldec_monto_debe=$ldec_monto_haber=$ldec_saldo=$ld_totmonhab=$ld_totmondeb=0;
		$ls_codemp = $this->dat["codemp"];

		$ls_sql = "SELECT SUM(monto - monret) As monhab, 0 As mondeb 
				     FROM scb_movbco 
					WHERE codemp='".$ls_codemp."' 
					  AND codban='".$as_codban."' 
					  AND trim(ctaban)='".trim($as_ctaban)."' 
					  AND (codope='RE' OR codope='ND' OR codope='CH') 
					  AND estmov<>'A' 
					  AND estmov<>'O'
			        GROUP BY codemp, codban, ctaban
					UNION
				   SELECT 0 As monhab, SUM(monto - monret) As mondeb 
					 FROM scb_movbco 
					WHERE codemp='".$ls_codemp."' 
					  AND codban='".$as_codban."' 
					  AND trim(ctaban)='".trim($as_ctaban)."' 
					  AND (codope='NC' OR codope='DP') 
					  AND estmov<>'A' 
					  AND estmov<>'O'
					GROUP BY codemp, codban, ctaban";
		$rs_data = $this->SQL->select($ls_sql);
		if ($rs_data===false)
		   {
			 $this->is_msg_error="Error al consultar saldo ".$this->fun->uf_convertirmsg($this->SQL->message);
		     $lb_valido = false;
			 $ldec_saldo=0;
		   }
		else
		   {
		     $li_numrows = $this->SQL->num_rows($rs_data);
			 if ($li_numrows>0)
			    {
			      while(!$rs_data->EOF)
			           {
				         $ldec_monto_debe  = $rs_data->fields["mondeb"];
				         $ldec_monto_haber = $rs_data->fields["monhab"];
						 $ld_totmondeb += $ldec_monto_debe;
						 $ld_totmonhab += $ldec_monto_haber;
						 $lb_valido=true;
						 $rs_data->MoveNext();
			           }
			      $ldec_saldo = $ld_totmondeb-$ld_totmonhab;
				}
			 else
			    {
				  $lb_valido=false;
				  $this->is_msg_error="No hay movimientos para la cuenta ".$as_ctaban;
				}
		   }	
		return  $lb_valido;
	}
}
?>