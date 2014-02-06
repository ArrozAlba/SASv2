<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class spe_Estpro2Dao extends ADOdb_Active_Record
{
	var $_table="spe_estpro2";
		
	public function FiltrarDatos($cadena)
	{
		global $db;
		$Rs = $db->Execute("select * from {$this->_table} where CODEST1='{cadena}'");
		return $Rs;
		
	}
	

	
	
}

?>