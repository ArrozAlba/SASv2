<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class ub4Dao extends ADODB_Active_Record
{
var $_table='sigesp_ub4';				
public function FiltrarEst($Cond)
{
	global $db;
	$Rs = $db->Execute("select * from {$this->_table} where {$Cond}"); 
	return $Rs;
}
}
?>