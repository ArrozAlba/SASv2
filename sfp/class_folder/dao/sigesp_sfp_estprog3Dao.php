<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class estprog3Dao extends ADODB_Active_Record
{
	var $_table='sfp_estpro3';				
	public function FiltrarEst($Cond)
	{
		$oNivel=new ConfNivelDao();
		$oNivel->tipo="PR";
		$oNivel->nivel="3";
		$tama1 = $oNivel->LeerNumCar();
		$pos1=(25-$tama1)+1;
                $oNivel->nivel="2";
                $tama2 = $oNivel->LeerNumCar();
                $pos2=(25-$tama2)+1;
                $oNivel->nivel="1";
                $tama3 = $oNivel->LeerNumCar();
                $pos3=(25-$tama3)+1;
		global $db;
		$Rs = $db->Execute("select substr(codestpro1,{$pos3},{$tama3}) as codestpro1,substr(codestpro2,{$pos2},{$tama2}) as codestpro2,substr(codestpro3,{$pos1},{$tama1}) as codestpro3,denestpro3 from {$this->_table} where {$Cond} and {$this->_table}.codemp='{$this->codemp}'"); 
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