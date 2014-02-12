<?php
require_once("../class_folder/sigesp_conexion_dao.php");
require_once('../class_folder/dao/sigesp_sfp_empresaDao.php');
class planUnicoRe extends ADOdb_Active_Record
{
	var $_table='sigesp_plan_unico_re';
	
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
	
	public function LeerGrupo()
	{
		global $db;
		$sql = "select sig_cuenta from {$this->_table} where sig_cuenta like '{$this->sig_cuenta}%' or sig_cuenta ='{$this->sig_cuentacomp}' order by sig_cuenta";
		$rs = $db->Execute($sql);
		return $rs;
	}
	
	public function TieneHijas()
	{
		global $db;
		//$db->debug=true;
		$sql = "select sig_cuenta from {$this->_table} 
				where sig_cuenta like '{$this->cuenta}%'";	
		$rs = $db->Execute($sql);
		if($rs->RecordCount()>1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	public function LeerUna()
	{
		global $db;
		$Rs = $db->Execute("select sig_cuenta,denominacion,coalesce(codcaif,'no aplica') as codcaif from $this->_table where sig_cuenta='{$this->sig_cuenta}'");
		return $Rs;
		
	}
	
	
	public function LeerUna2()
	{
		global $db;
		//$db->debug=true;
		$Rs = $db->Execute("select sig_cuenta,denominacion,status from $this->_table where sig_cuenta='".str_pad($this->sig_cuenta,$this->Cantdigitoscuentas(),"0")."'");
		return $Rs;
	}
	
	public function Cantdigitoscuentas()
	{
		global $db;
		$Rs = $db->Execute("select sig_cuenta from $this->_table limit 1");
		return strlen(trim($Rs->fields["sig_cuenta"]));
	}
	
	
	public function LeerCuentasIngrsos()
	{
		global $db;
		$oEmpresa = new Empresa();
		$Rs = $oEmpresa->LeerDatos();
		if($Rs)
		{
			$Ingreso = $Rs->fields['ingreso'];
			$Rs = $db->Execute("select sig_cuenta,denominacion from {$this->_table} where sig_cuenta.status='C' and sig_cuenta not in(select sig_cuenta from spe_plan_ingresos)");
		}
		return $Rs;
	}
	
	
	public function LeerPorCadena($cr,$cad)
	{
		global $db;
	//	$db->debug=1;
		$Rs = $db->Execute("select sig_cuenta as codigo,denominacion from $this->_table where  {$cr} like  '{$cad}%' order by sig_cuenta");	
		return $Rs;
	}

	public function LeerTodas()
	{
		global $db;
		$Rs = $db->Execute("select spi_cuenta as codigo,denominacion from $this->_table");	
		return $Rs;
	}
	
	
}

?>