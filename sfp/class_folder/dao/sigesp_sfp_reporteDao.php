<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class reporteDao extends ADOdb_Active_Record
{
	var $_table='sigesp_sfp_reporte';
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
		$db->StartTrans();
		$this->save();
		$db->CompleteTrans();
		return "1";

	}
	public function Eliminar()
	{
	//	$ObjAux = $this->LeerUno();
		//if(is_array($ObjAux))
		//{
			global $db;
			$db->StartTrans();
			$this->delete();
			$db->CompleteTrans();
			return "1";
		//}
	}
	
public function LeerUno()
{
		global $db;
		$sql="codgi='{$this->codgi}' and codcod='{$this->codcod}' and codcoh='{$this->codcoh}' and codvp='{$this->codvp}' and colvp='{$this->colvp}' and codcai='{$this->codcai}'";
		$Rs = $this->Find($sql);
		return $Rs;
}
	
public function LeerTodos()
{
		global $db;
		$sql="select * from {$this->_table} order by nombre asc";
		//echo $sql;
		//die();
		$Rs = $db->Execute($sql);
		return $Rs;
}

public function ObtenerCuentaH()
{
	global $db;
	//$db->debug=1;
	$sql="select codcoh,denominacion from $this->_table inner join sigesp_plan_unico on $this->_table.codcoh=sigesp_plan_unico.sc_cuenta  where codgi='$this->codgi'";
	$Rs = $db->Execute($sql);
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