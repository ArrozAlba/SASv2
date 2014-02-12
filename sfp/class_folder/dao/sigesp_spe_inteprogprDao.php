<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class InteEst extends ADODB_Active_Record
{
	var $_table='spe_relacion_es';				
	public function Filtrar($Cond)
	{
		global $db;
		$Rs = $db->Execute("select * from {$this->_table} where {$Cond}"); 
		return $Rs;
	}
	
	public function IncluirInt()
	{
		global $db;
		$db->StartTrans();
		$this->save();
		$db->CompleteTrans();
		return "1";
	}
}
?>