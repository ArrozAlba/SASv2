<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class estprog2Dao extends ADODB_Active_Record
{
var $_table='spe_estpro2';				
public function FiltrarEst($Cond)
{
	$oNivel=new ConfNivelDao();
	$oNivel->tipo="PL";
	$oNivel->nivel="2";
	$tama1 = $oNivel->LeerNumCar();
	$pos1=(25-$tama1)+1;
	global $db;
	//$db->debug=true;
	$Rs = $db->Execute("select codest1,substr(codest2,{$pos1},{$tama1}) as codest2,denest2 from {$this->_table} where {$Cond} and {$this->_table}.codemp='{$this->codemp}'"); 
	return $Rs;
}
public function Modificar()
	{
		global $db;
		$db->StartTrans();
		$this->Replace();
			if($db->CompleteTrans())
			{
				return "1";	
			}
			else
			{
				return "0";
			}
		
	}
	
	public function Incluir()
	{
		global $db;
		try
		{
			$db->debug=1;
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
		catch(exception $e)
		{
			//capturar el error y guardarlo en la bd
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


}
?>