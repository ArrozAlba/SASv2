<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class unimedidaDao extends ADOdb_Active_Record
{
	var $_table='sig_unidademedidas';
	
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
			$db->CompleteTrans();
			return "1";
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
		$db->CompleteTrans();
		return "1";

	}
	
	public function BuscarCodigo()
	{
		global $db;
		$Rs = $db->Execute("select max(cod_uni)  as cod from {$this->_table} where {$this->_table}.codemp='{$this->codemp}'"); 
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
		$Rs = $db->Execute("select * from sig_unidademedidas where {$this->_table}.codemp='{$this->codemp}'");
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