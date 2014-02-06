<?php
require_once("../class_folder/sigesp_conexion_dao.php");
require_once('../class_folder/dao/sigesp_sfp_empresaDao.php');
class CuentasGastos extends ADOdb_Active_Record
{
	var $_table='spg_cuentas';	
	
	public function LeerCuentasGastos()
	{
		global $db;
		$Rs = $db->Execute("select spg_cuenta,denominacion from {$this->_table} where spg_cuenta.status='C' and spg_cuenta not in(select spg_cuenta from sfp_cuentas_fuenfin)");
		return $Rs;
	}

	public function LeerPorCadena($cr,$cad)
	{
		global $db;
		$Rs = $db->Execute("select spg_cuenta,denominacion from {$this->_table} where  {$cr} like  '%{$cad}%' and spg_cuentas.status='C' and spg_cuenta not in(select spi_cuenta from spe_plan_ingresos)");
		return $Rs;
		
	}



		
}

?>