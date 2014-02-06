<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class conversionDao extends ADOdb_Active_Record
{
var $_table='sigesp_sfp_asociacion';

public function Modificar()
{
		global $db;
		//$db->debug = true;
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
		//$db->debug=true;
		$sql="select sigesp_sfp_asociacion.sig_cuenta as codcuenta,
			 sigesp_plan_unico_re.denominacion as dencuenta,s1.denominacion 
			 as dendebe,s2.denominacion as denhaber,s1.sc_cuenta as codigodebe,s2.sc_cuenta 
			 as codigohaber,coalesce(sigesp_plan_unico_re.codcaif,'No aplica para esta cuenta') 
			 as codcaif ,v1.cuentadebe  as codvardebe,s3.denominacion as denvardebe,v2.cuentahaber 
			 as codvarhaber ,s4.denominacion as denvarhaber from sigesp_sfp_asociacion inner join sigesp_plan_unico_re on 
			 sigesp_sfp_asociacion.sig_cuenta=sigesp_plan_unico_re.sig_cuenta 
			 inner join sigesp_sfp_plan_unico s1 on s1.sc_cuenta=sigesp_sfp_asociacion.sc_cuenta 
			 inner join sigesp_sfp_plan_unico s2 on s2.sc_cuenta=sigesp_sfp_asociacion.sc_cuenta_haber  
			 left outer  join sigesp_sfp_variaciones  v1 on sigesp_sfp_asociacion.sc_cuenta=v1.cuentacontable 
			 left outer join sigesp_plan_unico_re s3 on s3.sig_cuenta=v1.cuentadebe 
			 left outer  join sigesp_sfp_variaciones  v2 on sigesp_sfp_asociacion.sc_cuenta_haber=v2.cuentacontable 
			 left outer join sigesp_plan_unico_re s4 on s4.sig_cuenta=v2.cuentahaber
			 where sigesp_sfp_asociacion.estatus='C' order by sigesp_plan_unico_re.sig_cuenta asc ";	
		$Rs = $db->Execute($sql);		
		return $Rs;
}

public function LeerTodasCuentas2($cr,$cad)
{
		global $db;
		//$db->debug=true;
		$sql="select sigesp_sfp_asociacion.sig_cuenta as codcuenta,sigesp_plan_unico_re.denominacion
			as dencuenta,sigesp_sfp_asociacion.estatus,s1.denominacion 
			as dendebe,s2.denominacion as denhaber,s1.sc_cuenta as codigodebe,s2.sc_cuenta as codigohaber,
			coalesce(sigesp_sfp_asociacion.codcaif,'No Disponible') as codcaif ,
			coalesce(v1.cuentadebe,sigesp_sfp_asociacion.codcaif)  as codvardebe,
			coalesce(s3.desplacaif,caif1.desplacaif)  as denvardebe,
			coalesce(v2.cuentahaber,sigesp_sfp_asociacion.codcaif) as codvarhaber ,
			coalesce(s4.desplacaif,caif1.desplacaif) as denvarhaber 		
			from sigesp_sfp_asociacion inner join
			sigesp_plan_unico_re on sigesp_sfp_asociacion.sig_cuenta=sigesp_plan_unico_re.sig_cuenta 
			left outer join sigesp_plan_unico_caif as caif1 on caif1.codplacaif=sigesp_sfp_asociacion.codcaif
			inner join sigesp_sfp_plan_unico s1 on s1.sc_cuenta=sigesp_sfp_asociacion.sc_cuenta inner join sigesp_sfp_plan_unico
			s2 on s2.sc_cuenta=sigesp_sfp_asociacion.sc_cuenta_haber  
			left outer  join sigesp_sfp_variaciones  v1 on sigesp_sfp_asociacion.sc_cuenta=v1.cuentacontable left
			 outer join sigesp_plan_unico_caif s3 on s3.codplacaif=v1.cuentadebe 
			left outer  join sigesp_sfp_variaciones  v2 on sigesp_sfp_asociacion.sc_cuenta_haber=v2.cuentacontable
			left outer join sigesp_plan_unico_caif s4 on s4.codplacaif=v2.cuentahaber
			where upper(sigesp_plan_unico_re.{$cr}) like upper('{$cad}%'
			) order by sigesp_plan_unico_re.sig_cuenta asc";
			
			//ver($sql);
		$Rs = $db->Execute($sql);		
		return $Rs;
}

public function LeerTodasCuentas($cr,$cad)
{
		global $db;
		//$db->debug=true;
		$sql="select sigesp_sfp_asociacion.sig_cuenta as codcuenta,sigesp_plan_unico_re.denominacion
			as dencuenta,sigesp_sfp_asociacion.estatus,s1.denominacion 
			as dendebe,s2.denominacion as denhaber,s1.sc_cuenta as codigodebe,s2.sc_cuenta as codigohaber,
			coalesce(sigesp_sfp_asociacion.codcaif,'No Disponible') as codcaif ,
			coalesce(v1.cuentadebe,sigesp_sfp_asociacion.codcaif)  as codvardebe,
			coalesce(s3.desplacaif,caif1.desplacaif)  as denvardebe,
			coalesce(v2.cuentahaber,sigesp_sfp_asociacion.codcaif) as codvarhaber ,
			coalesce(s4.desplacaif,caif1.desplacaif) as denvarhaber 		
			from sigesp_sfp_asociacion inner join
			sigesp_plan_unico_re on sigesp_sfp_asociacion.sig_cuenta=sigesp_plan_unico_re.sig_cuenta 
			left outer join sigesp_plan_unico_caif as caif1 on caif1.codplacaif=sigesp_sfp_asociacion.codcaif
			inner join sigesp_sfp_plan_unico s1 on s1.sc_cuenta=sigesp_sfp_asociacion.sc_cuenta inner join sigesp_sfp_plan_unico
			s2 on s2.sc_cuenta=sigesp_sfp_asociacion.sc_cuenta_haber  
			left outer  join sigesp_sfp_variaciones  v1 on sigesp_sfp_asociacion.sc_cuenta=v1.cuentacontable left
			 outer join sigesp_plan_unico_caif s3 on s3.codplacaif=v1.cuentadebe 
			left outer  join sigesp_sfp_variaciones  v2 on sigesp_sfp_asociacion.sc_cuenta_haber=v2.cuentacontable
			left outer join sigesp_plan_unico_caif s4 on s4.codplacaif=v2.cuentahaber
			where sigesp_sfp_asociacion.estatus='C' and upper(sigesp_plan_unico_re.{$cr}) like upper('{$cad}%'
			) order by sigesp_plan_unico_re.sig_cuenta asc";
			
			//ver($sql);
		$Rs = $db->Execute($sql);		
		return $Rs;
}

public function LeerTodas()
{
		global $db;
		//$db->debug=true;
		$sql="select sigesp_sfp_asociacion.sig_cuenta as codcuenta,sigesp_plan_unico_re.denominacion
			as dencuenta,sigesp_sfp_asociacion.estatus,s1.denominacion 
			as dendebe,s2.denominacion as denhaber,s1.sc_cuenta as codigodebe,s2.sc_cuenta as codigohaber,
			coalesce(sigesp_sfp_asociacion.codcaif,'No Disponible') as codcaif ,
			coalesce(v1.cuentadebe,sigesp_sfp_asociacion.codcaif)  as codvardebe,
			coalesce(s3.desplacaif,caif1.desplacaif)  as denvardebe,
			coalesce(v2.cuentahaber,sigesp_sfp_asociacion.codcaif) as codvarhaber ,
			coalesce(s4.desplacaif,caif1.desplacaif) as denvarhaber 		
			from sigesp_sfp_asociacion inner join
			sigesp_plan_unico_re on sigesp_sfp_asociacion.sig_cuenta=sigesp_plan_unico_re.sig_cuenta 
			left outer join sigesp_plan_unico_caif as caif1 on caif1.codplacaif=sigesp_sfp_asociacion.codcaif
			inner join sigesp_sfp_plan_unico s1 on s1.sc_cuenta=sigesp_sfp_asociacion.sc_cuenta inner join sigesp_sfp_plan_unico
			s2 on s2.sc_cuenta=sigesp_sfp_asociacion.sc_cuenta_haber  
			left outer  join sigesp_sfp_variaciones  v1 on sigesp_sfp_asociacion.sc_cuenta=v1.cuentacontable left
			 outer join sigesp_plan_unico_caif s3 on s3.codplacaif=v1.cuentadebe 
			left outer  join sigesp_sfp_variaciones  v2 on sigesp_sfp_asociacion.sc_cuenta_haber=v2.cuentacontable
			left outer join sigesp_plan_unico_caif s4 on s4.codplacaif=v2.cuentahaber
			where sigesp_sfp_asociacion.estatus='C' order by sigesp_plan_unico_re.sig_cuenta asc";	
		
		$Rs = $db->Execute($sql);		
		return $Rs;
}

public function ObtenerCuentaH()
{
	global $db;
	//$db->debug=1;
	$sql="select codcoh,denominacion from $this->_table inner join sigesp_plan_unico on $this->_table.codcoh=sigesp_plan_unico.sc_cuenta  where codgi='$this->codgi'";
	$Rs = $db->Execute($sql);
	return $Rs;		
}

public function ExisteCambio()
{
	global $db;
	//$db->debug=1;
	$sql="select sig_cuenta,sc_cuenta_haber from sigesp_sfp_asociacion 
		  where (sc_cuenta='{$this->sc_cuenta}')";
	$Rs = $db->Execute($sql);
	$auxLetra = substr(trim($Rs->fields["sig_cuenta"]),0,3);
	if($this->sc_cuenta=='112020000')
	{
			//ver($auxLetra);
	}
	if($auxLetra=='404' || $auxLetra=='405' and (trim($this->sc_cuenta)!="111010200" || trim($this->sc_cuenta)!="111010201" || $this->sc_cuenta!="111010202" || trim($this->sc_cuenta)!="111010203"))
	{
		return true;
	}
	else
	{
		$sql="select sig_cuenta,sc_cuenta_haber from sigesp_sfp_asociacion 
		 	 where (sc_cuenta_haber='{$this->sc_cuenta}')";
		$Rs = $db->Execute($sql);
		$auxLetra = substr(trim($Rs->fields["sig_cuenta"]),0,3);
		if($auxLetra=='311')
		{
			return "-5";
		}
		else
		{
			return false;			
		}
	}
}


public function LeerCuentasIngreso()
{
	global $db;
	$sql="select an.sig_cuenta as codigo,si.denominacion
	from sigesp_sfp_asociacion an
	inner join sigesp_plan_unico_re si 
	on an.sig_cuenta=si.sig_cuenta 
	inner join sigesp_plan_unico s
	on an.sc_cuenta=s.sc_cuenta
	where an.estatus=1";
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