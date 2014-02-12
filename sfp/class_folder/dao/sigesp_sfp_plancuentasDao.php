<?php
require_once("../class_folder/sigesp_conexion_dao.php");
require_once("sigesp_sfp_empresasDao.php");
require_once("sigesp_sfp_plan_unico_reDao.php");
class PlancuentasDao extends ADOdb_Active_Record
{
	var $_table='sigesp_sfp_plancuentas';
	public function Modificar()
	{
		global $db;
		$sql = "UPDATE sigesp_sfp_plancuentas SET monto_anest=$this->monto_anest,monto_anreal=$this->monto_anreal
			   WHERE codemp='{$this->codemp}' and ano_presupuesto={$this->ano_presupuesto} 
			   and sig_cuenta='{$this->sig_cuenta}' 
			   and estatus='{$this->estatus}'";
		$db->Execute($sql);
	}
	public function Incluir()
	{
		global $db;
		//$db->debug = true;
		if($this->save())
		{
			return "1";	
		}
		else
		{
			return "0";
		}
	}
	
	public function cambiarestatusref()
	{
		global $db;
		$sql ="update {$this->_table} 
			   set estatus='S' where sig_cuenta ='{$this->referencia}'
			   and codemp='{$this->codemp}' 
			   and ano_presupuesto={$this->ano_presupuesto}";
		if($db->Execute($sql))
		{
			return "1";	
		}
		else
		{
			return "0";
		}
	}
	
	public function IniciarTran()
	{
		global $db;
		//$db->debug=true;
		$db->StartTrans();
	}
	 
	public function CompletarTran()
	{
		global $db;
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
			$db->debug = true;
			$db->StartTrans();
			$this->delete();
			$db->CompleteTrans();
			return "1";
	}
	public function LeerUno()
	{
		global $db;
		$sql="codgi='{$this->codgi}' and codcod='{$this->codcod}' and codcoh='{$this->codcoh}' and codvp='{$this->codvp}' and colvp='{$this->colvp}' and codcai='{$this->codcai}'";
		$Rs = $this->Find($sql);
		return $Rs;
	}

	public function revisarFormato()
	{
		global $db;
		$oEmp = new empresas();
		$rsEmp = $oEmp->LeerUno();
		$formatogastos = str_replace("-","",$rsEmp->fields["formpre"]);
		$formatoingresos = str_replace("-","",$rsEmp->fields["formspi"]);
		$candigitosgastos = strlen(trim($formatogastos));
		$candigitosingreso = strlen(trim($formatoingresos)); 
		$Grupocuenta = substr($this->sig_cuenta,0,1);
		$cancuentactual = strlen(trim($this->sig_cuenta));
		if($Grupocuenta=='3')
		{
			$resto = $cancuentactual+($candigitosingreso-$cancuentactual);
			$this->sig_cuenta = str_pad(trim($this->sig_cuenta),$resto,"0");
		}elseif($Grupocuenta=='4')
		{
			$resto = $cancuentactual+($candigitosgastos-$cancuentactual);
			$this->sig_cuenta = str_pad(trim($this->sig_cuenta),$resto,"0");
		}
	}
	
	
	
public function VerificarExistencia()
{
	global $db;
	$sql="select * from $this->_table 
		  where sig_cuenta='$this->sig_cuenta' 
		  and ano_presupuesto = $this->ano_presupuesto 
		  and codemp='$this->codemp'";
	$Rs = $db->Execute($sql);
	if($Rs->RecordCount()>0)	
	{	
		return true;
	}
	else
	{
		return false;
	}	
}


//public function LeerTodos()
//{
//	global $db;
//	$sql=" select $this->_table.monto_anreal,{$this->_table}.monto_anest,sigesp_sfp_asociacion.sig_cuenta as codcuenta,sigesp_plan_unico_re.denominacion
//			as dencuenta,sigesp_sfp_asociacion.estatus,s1.denominacion 
//			as dendebe,s2.denominacion as denhaber,s1.sc_cuenta as codigodebe,s2.sc_cuenta as codigohaber,
//			coalesce(sigesp_sfp_asociacion.codcaif,'No Disponible') as codcaif ,
//			coalesce(v1.cuentadebe,sigesp_sfp_asociacion.codcaif)  as codvardebe,
//			coalesce(s3.desplacaif,caif1.desplacaif)  as denvardebe,
//			coalesce(v2.cuentahaber,sigesp_sfp_asociacion.codcaif) as codvarhaber ,
//			coalesce(s4.desplacaif,caif1.desplacaif) as denvarhaber 		
//			from sigesp_sfp_asociacion inner join
//			sigesp_plan_unico_re on sigesp_sfp_asociacion.sig_cuenta=sigesp_plan_unico_re.sig_cuenta 
//			left outer join sigesp_plan_unico_caif as caif1 on caif1.codplacaif=sigesp_sfp_asociacion.codcaif
//			inner join sigesp_sfp_plan_unico s1 on s1.sc_cuenta=sigesp_sfp_asociacion.sc_cuenta inner join sigesp_sfp_plan_unico
//			s2 on s2.sc_cuenta=sigesp_sfp_asociacion.sc_cuenta_haber  
//			inner join {$this->_table} on {$this->_table}.sig_cuenta=sigesp_sfp_asociacion.sig_cuenta
//			left outer  join sigesp_sfp_variaciones  v1 on sigesp_sfp_asociacion.sc_cuenta=v1.cuentacontable left
//			 outer join sigesp_plan_unico_caif s3 on s3.codplacaif=v1.cuentadebe 
//			left outer  join sigesp_sfp_variaciones  v2 on sigesp_sfp_asociacion.sc_cuenta_haber=v2.cuentacontable
//			left outer join sigesp_plan_unico_caif s4 on s4.codplacaif=v2.cuentahaber
//			where sigesp_sfp_asociacion.estatus='C'
//			and {$this->_table}.codemp='{$this->codemp}' and {$this->_table}.ano_presupuesto={$this->ano_presupuesto}
//			order by sigesp_plan_unico_re.sig_cuenta asc";
//			
//		ver($sql);	
//		$Rs = $db->Execute($sql);
//		return $Rs;
//}


public function LeerTodos()
{
	global $db;
	$plancuentas = new planUnicoRe();
	$cantidad = $plancuentas->Cantdigitoscuentas();
	//$db->debug=true;
	$sql=" select sigesp_sfp_plancuentas.monto_anreal,sigesp_sfp_plancuentas.estatus,sigesp_sfp_plancuentas.monto_anest,sigesp_sfp_plancuentas
			.sig_cuenta as codcuenta,sigesp_sfp_plancuentas.denominacion
			as dencuenta,sigesp_sfp_plancuentas.estatus,s1.denominacion 
			as dendebe,s2.denominacion as denhaber,s1.sc_cuenta as codigodebe,s2.sc_cuenta as codigohaber,
			coalesce(sigesp_sfp_asociacion.codcaif,'No Disponible') as codcaif ,
			coalesce(v1.cuentadebe,sigesp_sfp_asociacion.codcaif)  as codvardebe,
			coalesce(s3.desplacaif,caif1.desplacaif)  as denvardebe,
			coalesce(v2.cuentahaber,sigesp_sfp_asociacion.codcaif) as codvarhaber ,
			coalesce(s4.desplacaif,caif1.desplacaif) as denvarhaber 	
			from sigesp_sfp_plancuentas inner join sigesp_sfp_asociacion 
			on substr(sigesp_sfp_plancuentas.referencia,1,{$cantidad})=sigesp_sfp_asociacion.sig_cuenta inner join
			sigesp_plan_unico_re on sigesp_sfp_asociacion.sig_cuenta=sigesp_plan_unico_re.sig_cuenta 
			left outer join sigesp_plan_unico_caif as caif1 on caif1.codplacaif=sigesp_sfp_asociacion.codcaif
			inner join sigesp_sfp_plan_unico s1 on s1.sc_cuenta=sigesp_sfp_asociacion.sc_cuenta inner join sigesp_sfp_plan_unico
			s2 on s2.sc_cuenta=sigesp_sfp_asociacion.sc_cuenta_haber 
			left outer  join sigesp_sfp_variaciones  v1 on sigesp_sfp_asociacion.sc_cuenta=v1.cuentacontable left
			 outer join sigesp_plan_unico_caif s3 on s3.codplacaif=v1.cuentadebe 
			left outer  join sigesp_sfp_variaciones  v2 on sigesp_sfp_asociacion.sc_cuenta_haber=v2.cuentacontable
			left outer join sigesp_plan_unico_caif s4 on s4.codplacaif=v2.cuentahaber
			where  sigesp_sfp_plancuentas.codemp='{$this->codemp}' and sigesp_sfp_plancuentas.ano_presupuesto={$this->ano_presupuesto}
			order by sigesp_plan_unico_re.sig_cuenta asc";
			
		//ver($sql);	
		$Rs = $db->Execute($sql);
		return $Rs;
}


public function LeerPorCadena($cr,$cad)
{
	global $db;
	$plancuentas = new planUnicoRe();
	$cantidad = $plancuentas->Cantdigitoscuentas();
	$sql="select $this->_table.monto_anreal,$this->_table.monto_anest,$this->_table.sig_cuenta as codigo,$this->_table.denominacion
			as denominacion,$this->_table.estatus,s1.denominacion 
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
			inner join $this->_table on substr($this->_table.sig_cuenta,1,{$cantidad})=sigesp_sfp_asociacion.sig_cuenta
			left outer  join sigesp_sfp_variaciones  v1 on sigesp_sfp_asociacion.sc_cuenta=v1.cuentacontable left
			outer join sigesp_plan_unico_caif s3 on s3.codplacaif=v1.cuentadebe 
			left outer  join sigesp_sfp_variaciones  v2 on sigesp_sfp_asociacion.sc_cuenta_haber=v2.cuentacontable
			left outer join sigesp_plan_unico_caif s4 on s4.codplacaif=v2.cuentahaber
			where sigesp_sfp_asociacion.estatus='C'
			and sigesp_plan_unico_re.sig_cuenta like '3%' and sigesp_plan_unico_re.{$cr} like '{$cad}%'
			and {$this->_table}.codemp='{$this->codemp}' and {$this->_table}.ano_presupuesto={$this->ano_presupuesto}
			order by sigesp_plan_unico_re.sig_cuenta asc";
	//ver($sql);
	$Rs = $db->Execute($sql);
	return $Rs;
}

public function LeerPorCadenaGas($cr,$cad)
{
	$plancuentas = new planUnicoRe();
	$cantidad = $plancuentas->Cantdigitoscuentas();
	 global $db;
	  $sql="select $this->_table.monto_anreal,$this->_table.monto_anest,{$this->_table}.sig_cuenta as codigo,{$this->_table}.denominacion
			as denominacion,sigesp_sfp_asociacion.estatus,s1.denominacion 
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
			inner join $this->_table on substr(sigesp_sfp_plancuentas.sig_cuenta,1,{$cantidad})=sigesp_sfp_asociacion.sig_cuenta
			left outer  join sigesp_sfp_variaciones  v1 on sigesp_sfp_asociacion.sc_cuenta=v1.cuentacontable left
			outer join sigesp_plan_unico_caif s3 on s3.codplacaif=v1.cuentadebe 
			left outer  join sigesp_sfp_variaciones  v2 on sigesp_sfp_asociacion.sc_cuenta_haber=v2.cuentacontable
			left outer join sigesp_plan_unico_caif s4 on s4.codplacaif=v2.cuentahaber
			where sigesp_sfp_asociacion.estatus='C'
			and sigesp_plan_unico_re.sig_cuenta like '4%' and sigesp_plan_unico_re.{$cr} like '{$cad}%'
			and {$this->_table}.codemp='{$this->codemp}' and {$this->_table}.ano_presupuesto={$this->ano_presupuesto}
			order by sigesp_plan_unico_re.sig_cuenta asc";
	//	ver($sql);
	$Rs = $db->Execute($sql);
	return $Rs;
}

public function LeerCuentasFuentes()
{	
	global $db;	
	$sql="select sigesp_sfp_asociacion.sig_cuenta as spg_cuenta,sigesp_plan_unico_re.denominacion as denominacion,s1.denominacion as dendebe,s2.denominacion as denhaber,s1.sc_cuenta as codigodebe,s2.sc_cuenta as codigohaber ,v1.cuentadebe  as codvardebe,s3.denominacion as denvardebe,v2.cuentahaber as codvarhaber ,s4.denominacion as denvarhaber 
		 from sigesp_sfp_asociacion inner join sigesp_plan_unico_re on 
		 sigesp_sfp_asociacion.sig_cuenta=sigesp_plan_unico_re.sig_cuenta inner join sigesp_sfp_plan_unico s1 on s1.sc_cuenta=sigesp_sfp_asociacion.sc_cuenta inner join sigesp_sfp_plan_unico s2 on s2.sc_cuenta=sigesp_sfp_asociacion.sc_cuenta_haber left outer  join sigesp_sfp_variaciones  v1 on sigesp_sfp_asociacion.sc_cuenta=v1.cuentacontable left outer join sigesp_plan_unico_re s3 on s3.sig_cuenta=v1.cuentadebe 
		 left outer  join sigesp_sfp_variaciones  v2 on sigesp_sfp_asociacion.sc_cuenta_haber=v2.cuentacontable left outer join sigesp_plan_unico_re s4 on s4.sig_cuenta=v2.cuentahaber inner join $this->_table on $this->_table.sig_cuenta=sigesp_sfp_asociacion.sig_cuenta where sigesp_sfp_asociacion.estatus='C'
 	   	 and sigesp_plan_unico_re.sig_cuenta like '4%' and sigesp_plan_unico_re.sig_cuenta not in(SELECT sig_cuenta FROM spe_plan_ingr) and 
 	   	 sigesp_plan_unico_re.sig_cuenta in(select spe_int_cuentas.sig_cuenta from spe_relacion_es inner join spe_int_cuentas on spe_relacion_es.codinte=spe_int_cuentas.codinte where spe_relacion_es.fuentecuentas='1') and {$this->_table}.codemp='{$this->codemp}' and {$this->_table}.ano_presupuesto='{$this->ano_presupuesto}'";
	//$db->debug=1;
	$Rs = $db->Execute($sql);
	return $Rs;
}


public function LeerCuentasGastos()
{
	global $db;
	$sql="select * from $this->_table inner join sigesp_sfp_asociacion on $this->_table.codconversion=sigesp_sfp_asociacion.codconversion";
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
}

?>