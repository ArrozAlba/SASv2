<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class metaDao extends ADOdb_Active_Record
{
	var $_table='sig_variables';
	
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
	
	public function BuscarCodigo()
	{
		global $db;
		//$db->debug=true;
		$Rs = $db->Execute("select max(cod_var)  as cod from {$this->_table} where {$this->_table}.codemp='{$this->codemp}'"); 
		//var_dump($Rs->fields['cod']);
		//die(); 
		if($Rs->fields['cod']=='')
		{
			return "0001"; 
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
		$sql = "select cod_var,sig_variables.denominacion as meta,sig_variables.cod_uni,sig_unidademedidas.denominacion as unidad,sig_unidademedidas.genero from sig_variables inner join sig_unidademedidas on sig_variables.cod_uni=sig_unidademedidas.cod_uni and sig_variables.codemp=sig_unidademedidas.codemp where {$this->_table}.codemp='{$this->codemp}'";
		//echo $sql;
		//die();
		$Rs = $db->Execute($sql);
		return $Rs;
		
	}
	
	public function LeerPorCadena($cr,$cad)
	{
		global $db;
		//$Rs = $this->Find("{$cr} like  '%{$cad}%' ");
		$Rs = $db->Execute("select cod_var,denominacion as meta from sig_variables where {$cr} like '%{$cad}%' and {$this->_table}.codemp='{$this->codemp}'");
		return $Rs;
		
	}
	
}
?>