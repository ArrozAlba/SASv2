<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class ub6Dao extends ADODB_Active_Record
{
var $_table='sigesp_ub6';				
public function FiltrarEst($Cond)
{
	global $db;
	$Rs = $db->Execute("select * from {$this->_table} where {$Cond}"); 
	return $Rs;
}
}
?>