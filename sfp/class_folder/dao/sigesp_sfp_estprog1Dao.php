<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class estprog1Dao extends ADODB_Active_Record
{
	var $_table='sfp_estpro1';				
	public function FiltrarEst()
	{
		$oNivel=new ConfNivelDao();
		$oNivel->tipo="PR";
		$oNivel->nivel="1";
		$tama = $oNivel->LeerNumCar();
		$pos=(25-$tama)+1;
		global $db;
		$Rs = $db->Execute("select estcla,fecha_ini,fecha_fin,costototal,responsable,substr(codestpro1,{$pos},{$tama}) as codestpro1,denestpro1 from {$this->_table} where {$this->_table}.codemp='{$this->codemp}'"); 
		return $Rs;
	}

	public function Incluir()
	{
		try
		{
			global $db;
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