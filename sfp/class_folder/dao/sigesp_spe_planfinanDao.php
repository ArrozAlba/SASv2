<?php
require_once("../class_folder/sigesp_conexion_dao.php");
require_once('../class_folder/dao/sigesp_spe_asientosDao.php');
class PlanIngreso extends ADOdb_Active_Record
{
	var $_table='spe_plan_ingr';
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
		
	public function LeerPlan()
	{
		global $db;
		$Rs = $db->Execute("select * from {$this->_table}"); 
		return $Rs; 	
	}
	
}

?>