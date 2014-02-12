<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class SaldosCont extends ADOdb_Active_Record
{
	var $_table='sigesp_sfp_saldoscon';
	
	public function LeerPorCadena($cr,$cad)
	{
		global $db;
		$Rs = $db->Execute("select * from {$this->_table} where  {$cr} like  '{$cad}%'");
		return $Rs;
		
	}
	
	public function LeerTodas()
	{
		global $db;
		$Rs = $db->Execute("select sigesp_plan_unico.sc_cuenta,sigesp_plan_unico.denominacion,coalesce(sigesp_sfp_saldoscon.monto_anreal,000) as monto_anreal,
coalesce(sigesp_sfp_saldoscon.monto_anest,000) as monto_anest 
from sigesp_plan_unico left outer join sigesp_sfp_saldoscon on sigesp_plan_unico.sc_cuenta=sigesp_sfp_saldoscon.sc_cuenta 
where sigesp_plan_unico.sc_cuenta like '1%' or sigesp_plan_unico.sc_cuenta like '2%' or sigesp_plan_unico.sc_cuenta like '3%' 
or sigesp_plan_unico.sc_cuenta like '4%' order by sigesp_plan_unico.sc_cuenta");
		return $Rs;		
	}
	
	public function incluir()
	{
		global $db;
		$db->StartTrans();
		$this->save();
		$db->CompleteTrans();
		return "1";
		
	}
	
	public function actualizar()
	{
		global $db;
		$db->StartTrans();
		$this->replace();
		$db->CompleteTrans();
		return "1";
		
	}
	
	public function buscarCuenta()	
	{
		global $db;
		$Rs = $db->Execute("select * from sigesp_sfp_saldoscon where sc_cuenta=$this->sc_cuenta");
		if ($Rs->RecordCount()>0)
		{
			return true;			
		}
		else
		{
			return false;
			
		}	
	}
	public function LeerSaldoInicial()	
	{
		global $db;
		//$db->debug=true;
		$Rs = $db->Execute("select sum(monto_anreal) as saldoinicialpasin, sum(monto_anest) as saldoinicialtri1  from sigesp_sfp_saldoscon where sc_cuenta like '{$this->sc_cuenta}%' and ano_presupuesto={$this->ano_presupuesto} and codemp='{$this->codemp}'");
		if ($Rs->RecordCount()>0)
		{
			return $Rs;			
		}
		else
		{
			return false;	
		}	
	}
	
}
?>