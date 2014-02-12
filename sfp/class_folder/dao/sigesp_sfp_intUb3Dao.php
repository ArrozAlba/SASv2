<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class intUb3Dao extends ADOdb_Active_Record
{
	var $_table='spe_int_ub3';
	
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
		$sql = "select spe_int_ub3.codubgeo3,spe_int_ub3.codubgeo2,spe_int_ub3.codubgeo1,sigesp_ub3.denominacion
				from spe_int_ub3 inner join sigesp_ub3 on spe_int_ub3.codubgeo1=sigesp_ub3.codubgeo1 
				and spe_int_ub3.codubgeo2=sigesp_ub3.codubgeo2
				and spe_int_ub3.codubgeo3=sigesp_ub3.codubgeo3  
				and spe_int_ub3.codemp=sigesp_ub3.codemp inner join spe_relacion_es 
				on spe_int_ub3.codinte=spe_relacion_es.codinte      
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