<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class planContable extends ADOdb_Active_Record
{
	var $_table='sigesp_plan_unico';
	public function LeerPorCadena($cr,$cad)
	{
		global $db;
		$sql="select sc_cuenta as codigo,denominacion,sigesp_plan_unico_caif.codplacaif as codvardebe,
			 sigesp_plan_unico_caif.desplacaif as desvardebe,caif2.codplacaif as codvarhaber,
			 caif2.desplacaif as desvarhaber 
			 from {$this->_table} left outer join sigesp_sfp_variaciones on 
			 {$this->_table}.sc_cuenta = sigesp_sfp_variaciones.cuentacontable 
			 left outer join sigesp_plan_unico_caif on sigesp_sfp_variaciones.cuentadebe=sigesp_plan_unico_caif.codplacaif
			 left outer join sigesp_plan_unico_caif as caif2 on sigesp_sfp_variaciones.cuentahaber=caif2.codplacaif  
			 where  {$this->_table}.{$cr} like  '{$cad}%' order by sc_cuenta asc";
		//ver($sql);
		$Rs = $db->Execute($sql);
		return $Rs;
	}
	
	
	public function LeerTodas()
	{
		global $db;
		$Rs = $db->Execute("select sc_cuenta as codigo,denominacion from {$this->_table}");
		return $Rs;	
	}
	public function LeerBancos()
	{
		global $db;
		$Rs = $db->Execute("select sc_cuenta as codigo,denominacion from {$this->_table} where sc_cuenta like '1110102%'");
		return $Rs;
		
	}		
	
		
}

?>