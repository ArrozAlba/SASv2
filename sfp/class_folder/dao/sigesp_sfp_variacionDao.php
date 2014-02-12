<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class variacionDao extends ADOdb_Active_Record
{
	var $_table='sigesp_sfp_variaciones';
	public function Modificar()
	{
		global $db;
		$db->debug=true;
		$db->StartTrans();
		$this->Replace();
		if($db->CompleteTrans())
		{
			return "1";	
		}
		else
		{
			return $db->ErrorNo();
		}
		
	}
	public function Incluir()
	{	
	
		global $db;
		$db->debug=true;
		$db->StartTrans();
		$this->save();
		if($db->CompleteTrans())
		{
			return "1";	
		}
		else
		{
			return $db->ErrorNo();
		}
	}
	public function Eliminar()
	{
		global $db;
		$db->StartTrans();
		$this->delete();
		if($db->CompleteTrans())
		{
			return "1";	
		}
		else
		{
			return $db->ErrorNo();
		}

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
	//	$db->debug=true;
	//	$sql="select codgi,codvp,dengi,denvp,sfp_conversiones.colvp,tblCont1.sc_cuenta as codcod,tblCont1.denominacion as dencod,tblCont2.sc_cuenta as codcoh,tblCont2.denominacion as dencoh from sfp_conversiones,sigesp_plan_unico as tblCont1,sigesp_plan_unico as tblCont2 where tblCont1.sc_cuenta=sfp_conversiones.codcod and tblCont2.sc_cuenta=sfp_conversiones.codcoh";
		
//		$sql="select * from sfp_conversiones,sigesp_plan_unico as tblCont1 where tblCont1.sc_cuenta=sfp_conversiones.codcod";
		$sql="select $this->_table.*,sigesp_plan_unico.denominacion as dencontable,sigesp_plan_unico_caif.desplacaif as dencuentadebe,sigesp_plan_unico_caif2.desplacaif as dencuentahaber 
			from $this->_table inner join sigesp_plan_unico on $this->_table.cuentacontable=sigesp_plan_unico.sc_cuenta inner join sigesp_plan_unico_caif on $this->_table.cuentadebe=sigesp_plan_unico_caif.codplacaif 
			inner join sigesp_plan_unico_caif as  sigesp_plan_unico_caif2 on $this->_table.cuentahaber=sigesp_plan_unico_caif2.codplacaif";
		//echo $sql;
		//die();
		$Rs = $db->Execute($sql);
		return $Rs;
	}
	
public function ObtenerCuentaDA()
{
	global $db;
	$sql="select codcod,codcoh,codvp from $this->_table where codgi='$this->codgi'";
	$Rs = $db->Execute($sql);
	return $Rs;		
}

public function LeerCuentaDebe()
{
	//$db->debug=1;
	global $db;
	//$db->debug=1;
	$sql="select codplacaif as cuentadebe,desplacaif from 
		sigesp_sfp_variaciones inner join  sigesp_plan_unico_caif
		on sigesp_sfp_variaciones.cuentadebe = sigesp_plan_unico_caif.codplacaif 
		where  cuentacontable='".trim($this->cuentacontable)."'";
	$Rs = $db->Execute($sql);
	return $Rs;	
}

public function LeerCuentaHaber()
{
	global $db;
	//$db->debug=1;
	$sql="select codplacaif as cuentahaber,desplacaif from 
		sigesp_sfp_variaciones inner join  sigesp_plan_unico_caif
		on sigesp_sfp_variaciones.cuentahaber = sigesp_plan_unico_caif.codplacaif 
		where  cuentacontable='".trim($this->cuentacontable)."'";
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