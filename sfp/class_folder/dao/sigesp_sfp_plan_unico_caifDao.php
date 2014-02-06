<?php
require_once("../class_folder/sigesp_conexion_dao.php");
require_once('../class_folder/dao/sigesp_sfp_empresaDao.php');
class planUnicoCaif extends ADOdb_Active_Record
{
	var $_table='sigesp_plan_unico_caif';
	public function Modificar()
	{
		global $db;
		$db->StartTrans();
		$this->Replace();
		if($db->CompleteTrans())
		{
			return "1";	
		}
		else
		{
			return false;			
		}		
	}
	public function Incluir()
	{
		global $db;
		$db->StartTrans();
		$this->save();
		if($db->CompleteTrans())
		{
			return "1";	
		}
		else
		{
			return false;			
		}		
	}
	public function Eliminar()
	{
		global $db;
		$db->StartTrans();
		$this->delete();
		if($db->CompleteTrans())
		{
			return "1";	
		}
		else
		{
			return false;			
		}		
	}
	public function LeerGrupo()
	{
		global $db;
		$sql = "select sig_cuenta from {$this->_table} where sig_cuenta like '{$this->sig_cuenta}%' or sig_cuenta ='{$this->sig_cuentacomp}' order by sig_cuenta";	
		
		$rs = $db->Execute($sql);
		return $rs;
	}
	
	public function LeerPorCadena($cr,$cad)
	{
		global $db;
		//$db->debug=1;
		$Rs = $db->Execute("select codplacaif as codigo, desplacaif as denominacion from $this->_table where  UPPER({$cr}) like  UPPER('{$cad}%') order by codplacaif");	
		return $Rs;
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
		$Rs = $db->Execute("select * from $this->_table where sig_cuenta='{$this->sig_cuenta}'");
		return $Rs;
		
	}
	public function LeerTodas()
	{
		global $db;
		$Rs = $db->Execute("select codplacaif as codigo, desplacaif as denominacion from $this->_table");	
		return $Rs;
	}
	
	
}

?>