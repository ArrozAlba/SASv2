<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class ub1Dao extends ADODB_Active_Record
{
var $_table='sigesp_ub1';				
public function FiltrarEst($Cond='')
{
	global $db;
	//$db->debug=true;
	$Rs = $db->Execute("select * from {$this->_table}"); 
	return $Rs;
}

public function Copiardatospais()
{
	global $db;
	//$db->debug=true;
	$sql="  delete from sigesp_ub4;
			delete from sigesp_ub3;
			delete from sigesp_ub2;
			delete from sigesp_ub1;
			insert into sigesp_ub1 (codubgeo1,denominacion) select * from sigesp_pais;
			insert into sigesp_ub2 (codubgeo1,codubgeo2,denominacion) select * from sigesp_estados;
			insert into sigesp_ub3 (codubgeo1,codubgeo2,codubgeo3,denominacion) select * from sigesp_municipio;
			insert into sigesp_ub4 (codubgeo1,codubgeo2,codubgeo3,codubgeo4,denominacion) select * from sigesp_parroquia";	
	$rs= $db->execute($sql);
	if($rs)
	{
		return true;
	}
	else
	{
		return false;
	}
}


public function Copiardatosestado()
{
	global $db;
	//$db->debug=true;
	$sql="  delete from sigesp_ub4;
			delete from sigesp_ub3;
			delete from sigesp_ub2;
			delete from sigesp_ub1;
			insert into sigesp_ub1 (codubgeo1,denominacion) select codest,desest from sigesp_estados where codpai='{$this->codpai}';
			insert into sigesp_ub2 (codubgeo1,codubgeo2,denominacion) select codest,codmun,denmun from sigesp_municipio where codpai='{$this->codpai}';
			insert into sigesp_ub3 (codubgeo1,codubgeo2,codubgeo3,denominacion) select codest,codmun,codpar,denpar from sigesp_parroquia where codpai='{$this->codpai}'";	
	$rs= $db->execute($sql);
	if($rs)
	{
		return true;
	}
	else
	{
		return false;
	}
}
public function Copiardatosmuni()
{
	global $db;
	//$db->debug=true;
	$sql="  delete from sigesp_ub4;
			delete from sigesp_ub3;
			delete from sigesp_ub2;
			delete from sigesp_ub1;
			insert into sigesp_ub1 (codubgeo1,denominacion) select codmun,denmun from sigesp_municipio where codpai='{$this->codpai}' and codest='{$this->codest}';
			insert into sigesp_ub2 (codubgeo1,codubgeo2,denominacion) select codmun,codpar,denpar from sigesp_parroquia where codpai='{$this->codpai}' and codest='{$this->codest}'";	
	$rs= $db->execute($sql);
	if($rs)
	{
		return true;
	}
	else
	{
		return false;
	}
}

public function Copiardatospar()
{
	global $db;
	//$db->debug=true;
	$sql="  delete from sigesp_ub4;
			delete from sigesp_ub3;
			delete from sigesp_ub2;
			delete from sigesp_ub1;
			insert into sigesp_ub1 (codubgeo1,denominacion) select codpar,denpar from sigesp_parroquia where codpai='{$this->codpai}' and codest='{$this->codest}' and codmun='{$this->codmun}'";	
	$rs= $db->execute($sql);
	if($rs)
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