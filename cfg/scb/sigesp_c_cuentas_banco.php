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
		// Funtion	  :  uf_verificar_saldo
		//	Return	   :	ldec_saldo
		//	Descripcion :  Fucnion que se encarga de obtener el saldo disponible para
		//						el banco y cuenta recibido como parametro
		/////////////////////////////////////////////////////////////////////////////
		$ls_codemp=0;
		$ldec_monto_debe=0;$ldec_monto_haber=0;$ldec_saldo=0;
		
		$ls_codemp = $this->dat["codemp"];
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_sql="SELECT  (SELECT SUM(monto - monret) As mondeb
					   FROM scb_movbco
					   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND 
					   (codope='NC' OR codope='DP') AND estmov<>'A' AND estmov<>'O' AND codemp='".$ls_codemp."')  as monhab,
					   ( SELECT SUM(monto - monret) As monhab
					   FROM  scb_movbco 
					   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND 
					   (codope='RE' OR codope='ND' OR codope='CH') AND estmov<>'A' AND estmov<>'O' AND codemp='".$ls_codemp."') as mondeb,
					   ( SELECT SUM(monto - monret) As monhab
					   FROM  scb_movbco 
					   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND 
					   (codope='RE' OR codope='ND' OR codope='CH') AND estmov<>'A' AND estmov<>'O' AND codemp='".$ls_codemp."')-
					   (SELECT SUM(monto - monret) As mondeb
					   FROM scb_movbco
					   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND 
					   (codope='NC' OR codope='DP') AND estmov<>'A' AND estmov<>'O' AND codemp='".$ls_codemp."')  AS saldo
				FROM scb_movbco limit 1";
				break;
			case "POSTGRES":
				$ls_sql="SELECT  (SELECT SUM(monto - monret) As mondeb
					   FROM scb_movbco
					   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND 
					   (codope='NC' OR codope='DP') AND estmov<>'A' AND estmov<>'O' AND codemp='".$ls_codemp."')  as monhab,
					   ( SELECT SUM(monto - monret) As monhab
					   FROM  scb_movbco 
					   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND 
					   (codope='RE' OR codope='ND' OR codope='CH') AND estmov<>'A' AND estmov<>'O' AND codemp='".$ls_codemp."') as mondeb,
					   ( SELECT SUM(monto - monret) As monhab
					   FROM  scb_movbco 
					   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND 
					   (codope='RE' OR codope='ND' OR codope='CH') AND estmov<>'A' AND estmov<>'O' AND codemp='".$ls_codemp."')-
					   (SELECT SUM(monto - monret) As mondeb
					   FROM scb_movbco
					   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND 
					   (codope='NC' OR codope='DP') AND estmov<>'A' AND estmov<>'O' AND codemp='".$ls_codemp."')  AS saldo
				FROM scb_movbco limit 1";
				break;
			case "INFORMIX":
				$ls_sql="SELECT limit 1 (SELECT SUM(monto - monret) As mondeb
					   FROM scb_movbco
					   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND 
					   (codope='NC' OR codope='DP') AND estmov<>'A' AND estmov<>'O' AND codemp='".$ls_codemp."')  as monhab,
					   ( SELECT SUM(monto - monret) As monhab
					   FROM  scb_movbco 
					   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND 
					   (codope='RE' OR codope='ND' OR codope='CH') AND estmov<>'A' AND estmov<>'O' AND codemp='".$ls_codemp."') as mondeb,
					   ( SELECT SUM(monto - monret) As monhab
					   FROM  scb_movbco 
					   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND 
					   (codope='RE' OR codope='ND' OR codope='CH') AND estmov<>'A' AND estmov<>'O' AND codemp='".$ls_codemp."')-
					   (SELECT SUM(monto - monret) As mondeb
					   FROM scb_movbco
					   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND 
					   (codope='NC' OR codope='DP') AND estmov<>'A' AND estmov<>'O' AND codemp='".$ls_codemp."')  AS saldo
				FROM scb_movbco ";
				break;
		}
		$rs_cta=$this->SQL->select($ls_sql);
		if($rs_cta==false)
		{
			$this->is_msg_error="Error al consultar saldo ".$this->fun->uf_convertirmsg($this->SQL->message);
		    $lb_valido = false;
			$ldec_saldo=0;
		}
		else
		{
			if($row=$this->SQL->fetch_row($rs_cta))
			{
				$ldec_saldo      = $row["saldo"];
				$ldec_monto_debe = $row["mondeb"];
				$ldec_monto_haber= $row["monhab"];
				if(is_null($ldec_saldo))
				{
					$ldec_saldo=0;
				}
				if((is_null($ldec_monto_debe))&&($ldec_monto_haber>0))
				{
					$ldec_saldo=$ldec_monto_haber;
				}
				if(is_null($ldec_monto_haber)&&($ldec_monto_debe>0))
				{
					$ldec_saldo=$ldec_monto_debe;
				}
				
				$lb_valido=true;
				
			}
			else
			{
				$lb_valido=false;
				$this->is_msg_error="No hay movimientos para la cuenta";
				$ldec_saldo=0;
			}
		}	
		return  $lb_valido;
	}

}
?>