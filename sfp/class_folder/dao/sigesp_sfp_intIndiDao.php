<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class intIndiDao extends ADOdb_Active_Record
{
	var $_table='spe_relacion_estindi';
	
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
			//$db->debug=true;
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
	
	public function BuscarIndicadores($integracion)
	{
		global $db;
		$sql = "select {$this->_table}.*, sig_indicador.denominacion from {$this->_table} inner join sig_indicador on {$this->_table}.cod_ind=sig_indicador.cod_ind
				inner join spe_relacion_es on {$this->_table}.codinte=spe_relacion_es.codinte where {$this->_table}.codinte ={$integracion} ";
		
		//ver($sql);
		$Rs = $db->Execute($sql); 
		return $Rs;
	}

}

?>