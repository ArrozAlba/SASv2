<?php
require_once("../class_folder/sigesp_conexion_dao.php");
require_once("sigesp_sfp_plan_unico_reDao.php");
class AsientoVariacionDao extends ADOdb_Active_Record
{
	var $_table='sfp_dt_cmp_variacion';
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
		//$db->debug=1;
		$db->StartTrans();
		$this->save();
		$db->CompleteTrans();
		return "1";

	}
	public function Eliminar()
	{
	//	$ObjAux = $this->LeerUno();
		//if(is_array($ObjAux))
		//{
			global $db;
			$db->StartTrans();
			$this->delete();
			$db->CompleteTrans();
			return "1";
		//}
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
	$sql="select * from $this->_table inner join sigesp_sfp_asociacion on $this->_table.codconversion=sigesp_sfp_asociacion.codconversion";
		//echo $sql;
		//die();
	$Rs = $db->Execute($sql);
	return $Rs;
}

public function LeerPorCadena($cr,$cad)
{
	global $db;
	$sql="select sigesp_plan_unico_re.sig_cuenta as codigo,sigesp_plan_unico_re.denominacion from $this->_table inner join sigesp_sfp_asociacion on $this->_table.codconversion=sigesp_sfp_asociacion.codconversion inner join sigesp_plan_unico_re on sigesp_sfp_asociacion.codgi=sigesp_plan_unico_re.sig_cuenta where sigesp_plan_unico_re.sig_cuenta like '3%' and sigesp_plan_unico_re.{$cr} like '%{$cad}%' and sigesp_plan_unico_re.sig_cuenta not in(SELECT spi_cuenta FROM spe_plan_ingresos)";
		//echo $sql;
		//die();
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





public function LeerSaldoVariacionGastos()
{
	global $db;
	$sql="select COALESCE(sum(enero+febrero+marzo+
			abril+mayo+junio+julio+agosto+septiembre+
			octubre+noviembre+diciembre),000) as saldovariacion
			from sigesp_sfp_cmp inner join spe_int_cuentas 
			on sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and 
			sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte inner join 
			sfp_dt_cmp_variacion on  sigesp_sfp_cmp.comprobante=sfp_dt_cmp_variacion.comprobante where 
			sfp_dt_cmp_variacion.sig_cuenta like '{$this->sig_cuenta}%'
			and sigesp_sfp_cmp.codemp='{$this->codemp}'";
			
//$sql="select COALESCE(sum(monto),000) as saldovariacion from  {$this->_table} where sig_cuenta like '$this->sig_cuenta%'";
	
	$Rs = $db->Execute($sql);
	return  $Rs->fields["saldovariacion"];
}

public function LeerSaldoVariacion($debhab)
{
	global $db;
	$sql="	select sum(monto) as saldovariacion  
			from sfp_dt_cmp_variacion where debhab='{$debhab}'
			and codemp = '{$this->codemp}'";
			//ver($sql);
//$sql="select COALESCE(sum(monto),000) as saldovariacion from  {$this->_table} where sig_cuenta like '$this->sig_cuenta%'";
	
	$Rs = $db->Execute($sql);
	return  $Rs->fields["saldovariacion"];
}




public function LeerSaldoIngresos()
{
	
	
	$sql="	select COALESCE(sum(enero+febrero+marzo+
			abril+mayo+junio+julio+agosto+septiembre+
			octubre+noviembre+diciembre),000) as saldovariacion
			from sigesp_sfp_cmp inner join spe_int_cuentas 
			on sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and 
			sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte inner join 
			sfp_dt_cmp_variacion on  sigesp_sfp_cmp.comprobante=sfp_dt_cmp_variacion.comprobante where 
			(sfp_dt_cmp_variacion.sig_cuenta like '307%' or sfp_dt_cmp_variacion.sig_cuenta like '311%' 
			or sfp_dt_cmp_variacion.sig_cuenta like '312%' or sfp_dt_cmp_variacion.sig_cuenta like '313%') 
			and sigesp_sfp_cmp.codemp='{$this->codemp}'";
	global $db;
	
	$Rs = $db->Execute($sql);
	return  $Rs->fields["saldovariacion"];


}

public function LeerSaldoGastos()
{
		global $db;
		$sql="select COALESCE(sum(enero+febrero+marzo+
			abril+mayo+junio+julio+agosto+septiembre+
			octubre+noviembre+diciembre),000) as saldovariacion
			from sigesp_sfp_cmp inner join spe_plan_ingr 
			on sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta 
			inner join 
			sfp_dt_cmp_variacion on  sigesp_sfp_cmp.comprobante=sfp_dt_cmp_variacion.comprobante where 
			(sfp_dt_cmp_variacion.sig_cuenta like '405%' or sfp_dt_cmp_variacion.sig_cuenta like '411%' 
			or sfp_dt_cmp_variacion.sig_cuenta like '412%')
			and spe_plan_ingr.codemp='{$this->codemp}' and spe_plan_ingr.ano_presupuesto='{$this->ano_presupuesto}'";
		$Rs = $db->Execute($sql);
		return  $Rs->fields["saldovariacion"];
}


public function LeerAsientoIngreso()
{
		global $db;
		$plancuentas = new planUnicoRe();
		$cantidad = $plancuentas->Cantdigitoscuentas();
		$sql="(select sfp_dt_cmp_variacion.sig_cuenta as codigo,sigesp_plan_unico_caif.desplacaif 
			  as denominacion,sfp_dt_cmp_variacion.monto
			  from  sigesp_sfp_cmp inner join sfp_dt_cmp_variacion on 
			  sigesp_sfp_cmp.comprobante=sfp_dt_cmp_variacion.comprobante 
			  inner join sigesp_plan_unico_caif on  sfp_dt_cmp_variacion.sig_cuenta=sigesp_plan_unico_caif.codplacaif
			  where sigesp_sfp_cmp.codemp='{$this->codemp}' and sigesp_sfp_cmp.ano_presupuesto={$this->ano_presupuesto}
			  and sigesp_sfp_cmp.sig_cuenta='{$this->sig_cuenta}')
			  union	
			  (select sigesp_plan_unico_caif.codplacaif as codigo,sigesp_plan_unico_caif.desplacaif as denominacion,
			  enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre as monto
			  from  spe_plan_ingr inner join sigesp_sfp_asociacion  on substr(spe_plan_ingr.sig_cuenta,1,{$cantidad})=sigesp_sfp_asociacion.sig_cuenta
			 inner join sigesp_plan_unico_caif on sigesp_sfp_asociacion.codcaif=sigesp_plan_unico_caif.codplacaif 
			  where spe_plan_ingr.codemp='{$this->codemp}' and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto}
			  and spe_plan_ingr.sig_cuenta='{$this->sig_cuenta}')";
	
		$Rs = $db->Execute($sql);
		return  $Rs;
}

public function LeerAsientoGastos()
{
		global $db;
		$plancuentas = new planUnicoRe();
		$cantidad = $plancuentas->Cantdigitoscuentas();
		$sql="(select coalesce(sfp_dt_cmp_variacion.sig_cuenta) as codigo,
				coalesce(denominacion,sigesp_plan_unico_caif.desplacaif) 
				as denominacion,sfp_dt_cmp_variacion.monto
				from  sigesp_sfp_cmp inner join sfp_dt_cmp_variacion on 
				sigesp_sfp_cmp.comprobante=sfp_dt_cmp_variacion.comprobante 
				left outer join sigesp_plan_unico_caif on  sfp_dt_cmp_variacion.sig_cuenta=sigesp_plan_unico_caif.codplacaif
				inner join spe_relacion_es on sigesp_sfp_cmp.codinte=spe_relacion_es.codinte	
				left outer join sigesp_plan_unico_re on  sfp_dt_cmp_variacion.sig_cuenta=sigesp_plan_unico_re.sig_cuenta
				where sigesp_sfp_cmp.codemp='{$this->codemp}' and sigesp_sfp_cmp.ano_presupuesto={$this->ano_presupuesto}
				and sigesp_sfp_cmp.sig_cuenta='{$this->sig_cuenta}' and  spe_relacion_es.codinte='{$this->codinte}')
				union	
				(select sigesp_plan_unico_caif.codplacaif as codigo,
				sigesp_plan_unico_caif.desplacaif as denominacion,
				enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre as monto
				from  spe_int_cuentas inner join sigesp_sfp_asociacion  on substr(spe_int_cuentas.sig_cuenta,1,{$cantidad})=sigesp_sfp_asociacion.sig_cuenta
				inner join sigesp_plan_unico_caif on sigesp_sfp_asociacion.codcaif=sigesp_plan_unico_caif.codplacaif
				where spe_int_cuentas.codemp='{$this->codemp}' and spe_int_cuentas.ano_presupuesto={$this->ano_presupuesto}
				and spe_int_cuentas.sig_cuenta='{$this->sig_cuenta}' and spe_int_cuentas.codinte='{$this->codinte}')";
		//ver($sql);
		$Rs = $db->Execute($sql);
		return  $Rs;
}
}

?>