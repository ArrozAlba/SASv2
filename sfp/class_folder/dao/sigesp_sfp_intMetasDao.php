<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class intMetasDao extends ADOdb_Active_Record
{
	var $_table='spe_relacion_estvar';
	
	public function Modificar()
	{
		global $db;
		$db->StartTrans();
		$this->Replace();
		$db->CompleteTrans();
		return "1";
		
	}
	
	public function Eliminar()
	{
		global $db;
		$db->debug=true;
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
	
	
	public function Incluir()
	{
		try
		{
			//var_dump($this);
			//die();
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
	
	public function BuscarMetas($integracion)
	{
		global $db;
		$sql = "select {$this->_table}.*, sig_variables.denominacion as meta from {$this->_table} inner join sig_variables on {$this->_table}.cod_var=sig_variables.cod_var
				inner join spe_relacion_es on {$this->_table}.codinte=spe_relacion_es.codinte where {$this->_table}.codinte ={$integracion} ";
		//echo $sql;
		//die();
		$Rs = $db->Execute($sql); 
		return $Rs;
	}

}

?>