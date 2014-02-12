<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class estprog3Dao extends ADODB_Active_Record
{
var $_table='spe_estpro3';				
public function FiltrarEst($Cond)
{
	$oNivel=new ConfNivelDao();
	$oNivel->tipo="PL";
	$oNivel->nivel="3";
	$tama1 = $oNivel->LeerNumCar();
	$pos1=(25-$tama1)+1;
	global $db;
	$Rs = $db->Execute("select codest1,codest2,substr(codest3,{$pos1},{$tama1}) as codest3,denest3 from {$this->_table} where {$Cond} and {$this->_table}.codemp='{$this->codemp}'"); 
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
			$db->CompleteTrans();
			return "1";
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