<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class intProbDao extends ADOdb_Active_Record
{
	var $_table='spe_int_prob';
	
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
			$db->debug=true;
			$db->StartTrans();
			$this->save();
			if($db->CompleteTrans())
			{
				return "1";
			}
			else
			{
				return "0";
			}
		}
		catch (Exception $e) 
		{

    		return "0";
		}


	}
	
	public function Eliminar()
	{
		global $db;
		$db->StartTrans();
		$this->delete();
		if($db->CompleteTrans())
		{
			return "1";		
		}
		else
		{
			return "0";
		}
	}
	
	public function BuscarProblemas($integracion)
	{
		global $db;
		$sql = "select {$this->_table}.codprob,spe_problemas.denominacion,spe_problemas.descripcion,spe_problemas.causa,spe_problemas.efecto,{$this->_table}.codinte from {$this->_table} inner join spe_problemas on {$this->_table}.codprob=spe_problemas.codprob where {$this->_table}.codinte ={$integracion}";
		$Rs = $db->Execute($sql); 
		return $Rs;
	}

}

?>