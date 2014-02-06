<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class intUb1Dao extends ADOdb_Active_Record
{
	var $_table='spe_int_ub1';

	public function Incluir()
	{
		try
		{
			global $db;
			$this->save();
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
		if($db->CompleteTrans())
		{
			return "1";		
		}
		else
		{
			return "0";
		}
	}
	
	public function BuscarUbicaciones($integracion)
	{
		global $db;
		//$db->debug=true;	
		$sql = "select spe_int_ub1.codubgeo1,sigesp_ub1.denominacion
				from spe_int_ub1 inner join sigesp_ub1 on spe_int_ub1.codubgeo1=sigesp_ub1.codubgeo1  
				and spe_int_ub1.codemp=sigesp_ub1.codemp inner join spe_relacion_es 
				on spe_int_ub1.codinte=spe_relacion_es.codinte      
				where spe_relacion_es.codinte={$integracion} and spe_relacion_es.codemp='{$this->codemp}'";
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