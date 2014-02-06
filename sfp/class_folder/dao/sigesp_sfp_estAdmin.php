<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class EstAdmin extends ADOdb_Active_Record
{

	
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
		try
		{
			$db->StartTrans();
			$this->delete();
			$db->CompleteTrans();
			return "1";
		}
		catch(exception $e)
		{
			//capturar el error y guardarlo en la bd
			return "0";
		}
	}
	
	public function BuscarCodigo()
	{
		global $db;
		$Rs = $db->Execute("select max(cod_fuenfin)  as cod from {$this->_table}"); 
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
		$Rs = $db->Execute("select * from {$this->_table}");
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