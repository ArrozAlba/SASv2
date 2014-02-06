<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class estprog2Dao extends ADODB_Active_Record
{
var $_table='sfp_estpro2';				
public function FiltrarEst($Cond)
{
	$oNivel=new ConfNivelDao();
	$oNivel->tipo="PR";
	$oNivel->nivel="2";
	$tama1 = $oNivel->LeerNumCar();
	$pos1=(25-$tama1)+1;
	$oNivel->nivel="1";
	$tama2 = $oNivel->LeerNumCar();
	$pos2=(25-$tama2)+1;
	global $db;
	//$db->debug=true;
	$Rs = $db->Execute("select substr(codestpro1,{$pos2},{$tama2}) as codestpro1,substr(codestpro2,{$pos1},{$tama1}) as codestpro2,denestpro2 from {$this->_table} where {$Cond} and {$this->_table}.codemp='{$this->codemp}'"); 
	return $Rs;
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
		try
		{
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
		catch(exception $e)
		{
			//capturar el error y guardarlo en la bd
			return "0";
		}
	}
	
	public function LeerTodas()
	{
		global $db;
		try
		{
			$sql = "select * from {$this->_table}";
			$rs = $db->Execute($sql);
			return $rs;				
		}
		catch(exception $e)
		{
			return "0";
		}
	}
	

}
?>