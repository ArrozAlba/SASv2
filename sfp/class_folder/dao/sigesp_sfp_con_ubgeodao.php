<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class ConfNivelDao extends ADOdb_Active_Record
{
	var $_table='sfp_conf_ubgeo';
	
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
		$Rs = $db->Execute("select max(codnivel) as cod from {$this->_table}"); 
	//	var_dump($Rs->fields['cod']); 
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
		try
		{
			$Rs = $this->Find("codnivel<>''");
	
		}
		catch(Exceptions $e)
		{
			$Rs = $e->getMessage();
		}
		
		return $Rs;
		
	}
	
	public function LeerPorCadena($cr,$cad)
	{
		global $db;
		$Rs = $this->Find("{$cr} like  '%{$cad}%' ");
		return $Rs;
		
	}

}

?>