<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class intUb2Dao extends ADOdb_Active_Record
{
	var $_table='spe_int_ub2';
	
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
		$sql = "select spe_int_ub2.codubgeo2,spe_int_ub2.codubgeo1,sigesp_ub2.denominacion
				from spe_int_ub2 inner join sigesp_ub2 on spe_int_ub2.codubgeo1=sigesp_ub2.codubgeo1 
				and spe_int_ub2.codubgeo2=sigesp_ub2.codubgeo2
				and spe_int_ub2.codemp=sigesp_ub2.codemp inner join spe_relacion_es 
				on spe_int_ub2.codinte=spe_relacion_es.codinte      
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