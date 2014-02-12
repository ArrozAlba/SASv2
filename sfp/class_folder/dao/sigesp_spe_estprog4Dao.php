<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class estprog4Dao extends ADODB_Active_Record
{
var $_table='spe_estpro4';				
public function FiltrarEst($Cond)
{
	$oNivel=new ConfNivelDao();
	$oNivel->tipo="PL";
	$oNivel->nivel="4";
	$tama1 = $oNivel->LeerNumCar();
	$pos1=(25-$tama1)+1;
	global $db;
	$Rs = $db->Execute("select codest1,codest2,codest3,substr(codest4,{$pos1},{$tama1}) as codest4,denest4 from {$this->_table} where {$Cond} and {$this->_table}.codemp='{$this->codemp}'"); 
	return $Rs;
}

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
			$db->CompleteTrans();
			return "1";
		}
		catch(exception $e)
		{
			//capturar el error y guardarlo en la bd
			return "0";
		}
	}


}
?>