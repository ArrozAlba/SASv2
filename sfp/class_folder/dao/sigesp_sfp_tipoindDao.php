<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class tipoIndicadorDao extends ADOdb_Active_Record
{
	var $_table='sig_tipoindicadores';
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
		try
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
				return "0";
			}
		}
		catch (Exception $e) 
		{
			//mandar a un archivo de logs con los eventos fallidos fallidos	
    		return "0";
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
			return "0";
		}
	}
	
	public function BuscarCodigo()
	{
		global $db;
		$Rs = $db->Execute("select max(cod_tipoind)  as cod from {$this->_table} where {$this->_table}.codemp='{$this->codemp}'");
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
		$Rs = $db->Execute("select * from {$this->_table} where {$this->_table}.codemp='{$this->codemp}'");
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