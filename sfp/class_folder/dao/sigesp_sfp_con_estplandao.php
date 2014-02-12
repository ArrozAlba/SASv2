<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class ConfNivelDao extends ADOdb_Active_Record
{
	var $_table='sfp_conf_niveles';
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
		global $db;
		$db->debug=1;
		$db->StartTrans();
		$this->save();
		$db->CompleteTrans();
		return "1";

	}
	public function Eliminar()
	{
		global $db;
		if($this->verificarRegistros($this->obtenerTabla($this->tipo,$this->nivel))==0)
		{
			$db->StartTrans();
			$this->delete();
			$db->CompleteTrans();
			return "1";		
		}
		else
		{
			return "0";
		}
	

	}
	
	public function obtenerTabla($tipo,$nivel)
	{
		switch ($tipo)
		{
			case "UG":
			 return "sigesp_ub{$nivel}";
			 break;
			case "PL":
			 return "spe_estpro{$nivel}";
			 break;
			case "PR":
			 return "sfp_estpro{$nivel}";
			 break;
			case "EA":
			 return "sfp_estructura_org";
			 break;
			
		}
	}
	public function LeerNombreUltnivel()
	{
		global $db;
		$sql="select nombre_pest from sfp_conf_niveles 
			  where nivel in (select max(nivel) 
			  from sfp_conf_niveles where tipo='{$this->tipo}' and codemp='{$this->codemp}') 
			  and tipo='{$this->tipo}'";
		$Rs = $db->Execute($sql);
		return $Rs->fields['nombre_pest'];
	}

	public function LeerNumCar()
	{
		global $db;
		//$db->debug=1;
		$Rs = $db->Execute("select numcar  from {$this->_table} where tipo='{$this->tipo}' and nivel='{$this->nivel}' and codemp='{$this->codemp}'"); 
		return $Rs->fields['numcar'];
	}
	
	
	public function BuscarCodigo()
	{
		global $db;
		$Rs = $db->Execute("select max(codnivel) as cod from {$this->_table} where codemp='{$this->codemp}'"); 
	//	var_dump($Rs->fields['cod']); 
		if($Rs->fields['cod']=='')
		{
			return "1"; 
		}
		else
		{	
			$dato = $Rs->fields['cod'];
			return $dato;
		}
	}
	
	public function LeerTodos()
	{
		global $db;
		//$db->debug=true;
		try
		{
			$Rs = $this->Find("tipo='{$this->tipo}' and codemp='{$this->codemp}' order by nivel asc");	
	
		}
		catch(Exceptions $e)
		{
			$Rs = $e->getMessage();
		}
		
		return $Rs;
		
	}
	
	public function LeerPorCadena($cr,$cad)
	{
		global $db;
		$Rs = $this->Find("{$cr} like  '%{$cad}%' and codemp='{$this->codemp}'");
		return $Rs;
		
	}
	
	public function verificarRegistros($tabla)
	{
		global $db;
		$db->debug=true;
		$Rs = $db->Execute("select count(*) as numero from {$tabla} where codemp='{$this->codemp}'"); 
		return $Rs->fields['numero'];
	}
	public function ObtenerNivelUb()
	{
		global $db;
		$Rs = $db->Execute("select max(nivel) as nivel from {$this->_table} where tipo='UG' and codemp='{$this->codemp}'"); 
		return $Rs->fields['nivel'];
		
	}
	
	public function ObtenerNivelPlan()
	{
		global $db;
		$Rs = $db->Execute("select max(nivel) as numero from {$this->_table} where tipo='PL' and codemp='{$this->codemp}'");
		return $Rs->fields['numero'];
		
	}
	public function ObtenerNivelProg()
	{
		global $db;
		$Rs = $db->Execute("select max(nivel) as numero from {$this->_table} where tipo='PR' and codemp='{$this->codemp}'"); 
		return $Rs->fields['numero'];	
	}
	public function ObtenerNivelesPlan()
	{
		global $db;
		$Rs = $db->Execute("select *  from {$this->_table} where tipo='PL' and codemp='{$this->codemp}' order by nivel asc");
		return $Rs;
	}
	public function ObtenerNivelesProg()
	{
		global $db;
		$Rs = $db->Execute("select *  from {$this->_table} where tipo='PR' and codemp='{$this->codemp}' order by nivel asc"); 
		return $Rs;
	}

	

}

?>