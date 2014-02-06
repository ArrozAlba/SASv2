<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class intFuentefinDao extends ADOdb_Active_Record
{
	var $_table='spe_est_fuefin';
	
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
	
	public function BuscarFuentes($integracion)
	{
		global $db;
		$sql = "select {$this->_table}.cod_fuenfin,sfp_fuentefinanciamientos.denfuefin,montot from {$this->_table} inner join	sfp_fuentefinanciamientos on {$this->_table}.cod_fuenfin=sfp_fuentefinanciamientos.cod_fuenfin where {$this->_table}.codinte ={$integracion}";
		$Rs = $db->Execute($sql); 
		return $Rs;
	}
	
	public function LeerTodos()
	{
		global $db;
		$Rs = $this->Find("cod_fuenfin<>''");
		return $Rs;
		
	}
	
	public function LeerPorCadena($cr,$cad)
	{
		global $db;
		$Rs = $this->Find("{$cr} like  '%{$cad}%' ");
		return $Rs;
		
	}
	
}

?>