<?php
require_once("../class_folder/sigesp_conexiona_dao.php");
class clientes extends ADOdb_Active_Record
{
	var $_table='sif_clientes';
	
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
	
	public function LeerTodos()
	{
		global $db;
		$Rs = $db->Execute("select codcli,nomcli from sif_clientes");
		return $Rs;	
	}
	
	public function LeerPorCadena($cr,$cad)
	{
		global $db;
		$Rs = $db->Execute("select codcli,nomcli from sif_clientes where {$cr} like  '%{$cad}%'");
		return $Rs;	
	}
	
}

?>