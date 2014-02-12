<?php
require_once('../librerias/php/adodb/adodb.inc.php');
require_once('../librerias/php/adodb/adodb-active-record.inc.php');

class Conexion
{	
	public function crearconexion()
	{
		$db = NewADOConnection($this->gestor);
		if($db->NConnect($this->host,$this->user,$this->pass,$this->base))
		{		
			ADOdb_Active_Record::SetDatabaseAdapter($db);
			$ADODB_ASSOC_CASE = 0;
			return $db;
		}
		else
		{
			return false;
		}
	}
}

?>