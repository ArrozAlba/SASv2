<?php
require_once("../class_folder/sigesp_conexion_dao.php");
require_once('../class_folder/dao/sigesp_sfp_empresaDao.php');
class planGasto extends ADOdb_Active_Record
{
	var $_table='sfp_cuentas_fuenfin';
	
	public function Modificar()
	{
		global $db;
		$db->StartTrans();
		$this->Replace();
		$db->CompleteTrans();
		return "1";	
	}
	
	public function Incluir()
	{
		global $db;
		$db->StartTrans();
		$this->save();
		$db->CompleteTrans();
		return "1";
	}
	
	public function Eliminar()
	{
		global $db;
		$db->StartTrans();
		$this->delete();
		$db->CompleteTrans();
		return "1";
	}
	
	public function BuscarCodigo()
	{
		global $db;
		$Rs = $db->Execute("select max(cod_fuenfin)  as cod from {$this->_table}"); 
		var_dump($Rs->fields['cod']); 
		if($Rs->fields['cod']=='')
		{
			return "0001"; 
		}
		else
		{	
			$dato = $Rs->fields['cod'];
			return $dato;
		}
	}
	
	public function LeerTodos()
	{
		global $db;
		$Rs = $this->Find("cod_fuenfin<>''");
		return $Rs;
		
	}
	
	public function LeerCuentasIngrsos()
	{
		global $db;
		$oEmpresa = new Empresa();
		$Rs = $oEmpresa->LeerDatos();
		if($Rs)
		{
			$Ingreso = $Rs->fields['ingreso'];
			$Rs = $db->Execute("select * from {$this->_table} where spi_cuenta like'{$Ingreso}%'");
		}
		return $Rs;
	}
	
	public function LeerPorCadena($cr,$cad)
	{
		global $db;
		$oEmpresa = new Empresa();
		$Rs = $oEmpresa->LeerDatos();
		if($Rs)
		{
			$Ingreso = $Rs->fields['ingreso'];
			$Rs = $db->Execute("select * from {$this->_table} where spi_cuenta like'{$Ingreso}%' and {$cr} like  '%{$cad}%'");
		
		}
		return $Rs;
		
	}



public function LeerPlan()
{
	global $db;
	$Rs = $db->Execute("select {$this->_table}.*,{$this->_table}.enero+{$this->_table}.febrero+{$this->_table}.marzo+{$this->_table}.abril+{$this->_table}.mayo+{$this->_table}.junio+{$this->_table}.julio+{$this->_table}.agosto+{$this->_table}.septiembre+{$this->_table}.octubre+{$this->_table}.noviembre+{$this->_table}.diciembre as montoGlobal,spi_cuentas.denominacion from {$this->_table} inner join spi_cuentas on {$this->_table}.spi_cuenta=spi_cuentas.spi_cuenta");
	return $Rs;
}

public function LeerPlanPorCuenta($cuenta)
{
	global $db;
	$Rs = $db->Execute("select {$this->_table}.*,{$this->_table}.enero+{$this->_table}.febrero+{$this->_table}.marzo+{$this->_table}.abril+{$this->_table}.mayo+{$this->_table}.junio+{$this->_table}.julio+{$this->_table}.agosto+{$this->_table}.septiembre+{$this->_table}.octubre+{$this->_table}.noviembre+{$this->_table}.diciembre as montoGlobal,spi_cuentas.denominacion from {$this->_table} inner join spi_cuentas on {$this->_table}.spi_cuenta=spi_cuentas.spi_cuenta where {$this->_table}.spi_cuenta='$cuenta'");
	return $Rs;
}
	
}

?>