<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class estadosDao extends ADODB_Active_Record
{
var $_table='sigesp_estados';				
public function FiltrarEst($Cond)
{
	global $db;
	$Rs = $db->Execute("select * from {$this->_table} where {$Cond}"); 
	return $Rs;
}
}
?>