<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class EstAdmin extends ADOdb_Active_Record
{

	var $_table='sfp_estructura_ad';
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
	
	public function BuscarCodigo()
	{
		global $db;
		$Rs = $db->Execute("select max(cod_fuenfin)  as cod from {$this->_table} where codemp='{$this->codemp}'"); 
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
	
	
	public function FiltrarEst()
	{
		global $db;
		$Rs = $db->Execute("select t2.* from {$this->_table} as t1 inner join {$this->_table} as t2  on t1.CODUAC=t2.CODUAC_P and t1.NIVEL=t2.NIVEL_P where t2.CODUAC_P='{$this->coduac_p}' and t2.NIVEL_P={$this->nivel_p} and t1.codemp='$this->codemp'"); 
		return $Rs;
	}
	
	public function LeerPadre()
	{
		global $db;
		$sql="select * from {$this->_table} where coduac_p='P' and {$this->_table}.codemp='{$this->codemp}'";
		//echo $sql;
		$Rs = $db->Execute($sql);
		return $Rs;
	}
	
	public function LeerPorCadena($cr,$cad)
	{
		global $db;
		$Rs = $this->Find("{$cr} like  '%{$cad}%' ");
		return $Rs;
		
	}
	
	public function Copiarunej()
	{
		global $db;
		$sql="delete from sfp_estructura_ad;
			  insert into sfp_estructura_ad(codemp,coduac,denuac,coduac_p,nivel,ano_presupuesto)select codemp,coduniadm,
			  denuniadm,'P',1,{$this->ano_presupuesto} from spg_unidadadministrativa";
		$Rs = $db->Execute($sql);
		if($Rs)
		{
			return true;
		}	
		else
		{
			return false;
		}
	}
	
	public function Copiarunadmin()
	{
		global $db;
		$sql="delete from sfp_estructura_ad;
			  insert into sfp_estructura_ad(codemp,coduac,denuac,coduac_p,nivel,ano_presupuesto)
			  select codemp,coduac,denuac,'P',1,{$this->ano_presupuesto} from spg_ministerio_ua;
			  insert into sfp_estructura_ad(codemp,coduac,denuac,nivel,coduac_p,nivel_p,ano_presupuesto)
			  select codemp,coduniadm,denuniadm,2,coduniadmsig,1,{$this->ano_presupuesto} 
			  from spg_unidadadministrativa where coduniadmsig<>''";
		$Rs = $db->Execute($sql);
		if($Rs)
		{
			return true;
		}	
		else
		{
			return false;
		}
	}
}

?>