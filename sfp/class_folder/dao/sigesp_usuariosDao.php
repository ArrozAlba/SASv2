<?php
require_once("../class_folder/sigesp_conexiona_dao.php");
class Usuario extends ADOdb_Active_Record
{
	var $_table='sss_usuarios';
	public function Validar($Rs)
	{
		global $db;
		$db->debug=1;
		$Rs = $db->Execute("select sch_consultor.cedcon,sch_consultor.nomcon from {$this->_table} inner join sch_consultor on {$this->_table}.codusu=sch_consultor.codusu where {$this->_table}.codusu='{$this->codusu}' and pwdusu='{$this->pwdusu}'");
		if($Rs->RecordCount()>0)
		{
			return '1';
		}	
		else
		{
			return '0';
		}
	}
		
}

?>