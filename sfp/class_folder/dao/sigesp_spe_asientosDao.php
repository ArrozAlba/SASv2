<?php
require_once("../class_folder/sigesp_conexion_dao.php");
require_once("sigesp_sfp_conversionDao.php");
require_once("sigesp_sfp_planingresoDao.php");
require_once("sigesp_sfp_intGastosDao.php");
require_once('sigesp_sfp_saldosconDao.php');

class Asientos extends ADOdb_Active_Record
{
	var $_table='sigesp_sfp_cmp';
	public function LeerAsiento()
	{
		
		global $db;
		$sql="select scg_dt_sfp_cmp.sc_cuenta,sigesp_sfp_plan_unico.denominacion as dencont, sigesp_plan_unico_re.denominacion as denpat, scg_dt_sfp_cmp.debhab,scg_dt_sfp_cmp.monto,sfp_dt_cmp_variacion.sig_cuenta,sfp_dt_cmp_variacion.debhab as debhabvp  
			  from  sigesp_sfp_cmp inner join  scg_dt_sfp_cmp on sigesp_sfp_cmp.comprobante=scg_dt_sfp_cmp.comprobante
			  inner join sfp_dt_cmp_variacion on sigesp_sfp_cmp.comprobante=sfp_dt_cmp_variacion.comprobante inner join
			  sigesp_plan_unico_re on sfp_dt_cmp_variacion.sig_cuenta=sigesp_plan_unico_re.sig_cuenta
			  left outer join sigesp_sfp_plan_unico on scg_dt_sfp_cmp.sc_cuenta=sigesp_sfp_plan_unico.sc_cuenta 
			  where {$this->_table}.codemp='{$this->codemp}' and {$this->_table}.ano_presupuesto={$this->ano_presupuesto}
			  and {$this->_table}.sig_cuenta='{$this->sig_cuenta}'
			  order by scg_dt_sfp_cmp.debhab desc";
			
	
		//die();
		$Rs = $db->Execute($sql);
		return $Rs;	  	
	}	
	
	public function LeerAsientoIngreso()
	{
		global $db;
		$sql="select scg_dt_sfp_cmp.sc_cuenta as codigo,sigesp_sfp_plan_unico.denominacion,scg_dt_sfp_cmp.debhab as operacion,scg_dt_sfp_cmp.monto
			  from  sigesp_sfp_cmp inner join  scg_dt_sfp_cmp on sigesp_sfp_cmp.comprobante=scg_dt_sfp_cmp.comprobante
			  left outer join sigesp_sfp_plan_unico on scg_dt_sfp_cmp.sc_cuenta=sigesp_sfp_plan_unico.sc_cuenta
			  where sigesp_sfp_cmp.codemp='{$this->codemp}' and sigesp_sfp_cmp.ano_presupuesto={$this->ano_presupuesto}
			  and sigesp_sfp_cmp.sig_cuenta='{$this->sig_cuenta}'
			  order by scg_dt_sfp_cmp.debhab asc
		";
			 // ver($sql);
		//echo $sql;
		//die();
		$Rs = $db->Execute($sql);
		return $Rs;	  	
	}	
	
	
	public function LeerAsiento2()
	{
		global $db;
		$sql="select scg_dt_sfp_cmp.sc_cuenta,sigesp_sfp_cmp.comprobante, 
				COALESCE(sigesp_plan_unico_re.denominacion,sigesp_plan_unico.denominacion) as denominacion,
				 scg_dt_sfp_cmp.debhab,scg_dt_sfp_cmp.monto from  sigesp_sfp_cmp inner join
				 scg_dt_sfp_cmp on sigesp_sfp_cmp.comprobante=scg_dt_sfp_cmp.comprobante
				left outer join sigesp_plan_unico_re on scg_dt_sfp_cmp.sc_cuenta=sigesp_plan_unico_re.sig_cuenta 
				left outer join sigesp_plan_unico on scg_dt_sfp_cmp.sc_cuenta=sigesp_plan_unico.sc_cuenta 
				 where sigesp_sfp_cmp.codemp='{$this->codemp}' and sigesp_sfp_cmp.ano_presupuesto={$this->ano_presupuesto}
				and sigesp_sfp_cmp.sig_cuenta='{$this->sig_cuenta}'
				order by scg_dt_sfp_cmp.debhab asc
				";

	    $RsContable = $db->Execute($sql);
		if($RsContable->fields["comprobante"]!="")
		{
	    		$sql="select scg_dt_sfp_cmp.sc_cuenta,sigesp_sfp_cmp.comprobante, 
				COALESCE(sigesp_plan_unico_re.denominacion,sigesp_plan_unico.denominacion) as denominacion,
				 scg_dt_sfp_cmp.debhab,scg_dt_sfp_cmp.monto from  sigesp_sfp_cmp inner join
				 scg_dt_sfp_cmp on sigesp_sfp_cmp.comprobante=scg_dt_sfp_cmp.comprobante
				left outer join sigesp_plan_unico_re on scg_dt_sfp_cmp.sc_cuenta=sigesp_plan_unico_re.sig_cuenta 
				left outer join sigesp_plan_unico on scg_dt_sfp_cmp.sc_cuenta=sigesp_plan_unico.sc_cuenta 
				 where sigesp_sfp_cmp.codemp='{$this->codemp}' and sigesp_sfp_cmp.ano_presupuesto={$this->ano_presupuesto}
				and sigesp_sfp_cmp.sig_cuenta='{$this->sig_cuenta}'
				order by scg_dt_sfp_cmp.debhab asc
				";
	    		$RsVarPat = $db->Execute($sql);
		}
	    return $Rs;	  	
	}	

	public function LeerTotalPatIn()
	{
		$resulejer =$this->LeerResultadoEjercicio();
		$this->sc_cuenta = "321000000";
		$saldoPtIns = $this->LeerSaldosCont();
		
		
		
	}
	
	
	public function LeerResultadoEjercicio()
	{
		global $db;	
		$oIngresos = new planIngreso();
		$oGastos = new intGastosDao();
		$oSaldos = new SaldosCont();
		$rsIngresos = $oIngresos->LeerSaldoInRes(); 	
		$rsGastos = $oGastos->LeerSaldoEgEstRes();
		$oSaldos->sc_cuenta="325020000";
		$rsSaldos = $oSaldos->LeerSaldoInicial();
		$oSaldos->sc_cuenta="325010000";
		$rsSaldos2 = $oSaldos->LeerSaldoInicial();
		$arResultado["anreal"]= $rsSaldos->fields["saldoinicialpasin"];
		$arResultado["anest"]= $rsSaldos->fields["saldoinicialtri1"];
		$arResultado["anesresult"]= $rsSaldos2->fields["saldoinicialtri1"];
		$arResultado["tri1"] = $rsIngresos->fields["trimestre1"]-$rsGastos->fields["montogastri1"]; 
		$arResultado["tri2"] = ($rsIngresos->fields["trimestre2"])-($rsGastos->fields["montogastri2"]);
		$arResultado["tri3"] =($rsIngresos->fields["trimestre3"])-($rsGastos->fields["montogastri3"]);
		$arResultado["tri4"] = ($rsIngresos->fields["trimestre4"])-($rsGastos->fields["montogastri4"]);
		$arResultado["montri1"] = $rsIngresos->fields["trimestre1"]-$rsGastos->fields["montogastri1"];
		$arResultado["montri2"] = ($rsIngresos->fields["trimestre2"])-($rsGastos->fields["montogastri2"]);
		$arResultado["montri3"] = ($rsIngresos->fields["trimestre3"])-($rsGastos->fields["montogastri3"]);
		$arResultado["montri4"] = ($rsIngresos->fields["trimestre4"])-($rsGastos->fields["montogastri4"]);
		$arResultado["varanual"]=$arResultado["tri4"]-$arResultado["anest"];			
		if(is_array($arResultado))
		{
			return $arResultado;
		}
	}
	
	
	public function LeerCapital()
	{
		$oIngresos = new planIngreso();
		$oGastos = new intGastosDao();
		$oSaldos = new Asientos();
		$rsIngresos = $oIngresos->LeerSaldoInRes(); 		
		$rsGastos = $oGastos->LeerSaldoEgEstRes();
		$oSaldos->sc_cuenta="321000000";
		$rsSaldos = $oSaldos->LeerSaldosCont(); 
		$oSaldos->sc_cuenta="322000000";
		$rsSaldos2 = $oSaldos->LeerSaldosCont();
		$oSaldos->sc_cuenta="323000000";
		$rsSaldos3 = $oSaldos->LeerSaldosCont();
		$oSaldos->sc_cuenta="324000000";
		$rsSaldos4 = $oSaldos->LeerSaldosCont();
		$arResultado["totaltrimestre1"] = $rsSaldos->fields["saldotri1"] + $rsSaldos2->fields["saldotri1"] + $rsSaldos3->fields["saldotri1"]+ $rsSaldos4->fields["saldotri1"];
		$arResultado["totaltrimestre2"] = $rsSaldos->fields["saldotri2"] + $rsSaldos2->fields["saldotri2"] + $rsSaldos3->fields["saldotri2"] + $rsSaldos4->fields["saldotri2"];
		$arResultado["totaltrimestre3"] = $rsSaldos->fields["saldotri3"] + $rsSaldos2->fields["saldotri3"] + $rsSaldos3->fields["saldotri3"]+ $rsSaldos4->fields["saldotri3"];
		$arResultado["totaltrimestre4"] = $rsSaldos->fields["saldotri4"] + $rsSaldos2->fields["saldotri4"] + $rsSaldos3->fields["saldotri4"]+ $rsSaldos4->fields["saldotri4"];
		
					
		if(is_array($arResultado))
		{
			return $arResultado;
		}
	}
	
	public function LeerActivoNeto()
	{
		$oSaldoActivo = new Asientos();
		$oSaldoDep = new Asientos();		
		$oSaldoActivo->sc_cuenta="100000000";
		$rsSaldosActivo = $oSaldoActivo->LeerSaldosCont(); 
		$oSaldoDep->sc_cuenta="220000000";
		$rsSaldosDep = $oSaldoDep->LeerSaldosCont();
		$arrTotal["anreal"] = $rsSaldosActivo->fields["anreal"]-abs($rsSaldosDep->fields["anreal"]);
		$arrTotal["anestimado"] = $rsSaldosActivo->fields["anestimado"]-abs($rsSaldosDep->fields["anestimado"]);
		$arrTotal["saldotri1"] = $rsSaldosActivo->fields["saldotri1"]-abs($rsSaldosDep->fields["saldotri1"]);
		$arrTotal["saldotri2"] = $rsSaldosActivo->fields["saldotri2"]-abs($rsSaldosDep->fields["saldotri2"]);
		$arrTotal["saldotri3"] = $rsSaldosActivo->fields["saldotri3"]-abs($rsSaldosDep->fields["saldotri3"]);
		$arrTotal["saldotri4"] = $rsSaldosActivo->fields["saldotri4"]-abs($rsSaldosDep->fields["saldotri4"]); 
		$arrTotal["variacion"] = $rsSaldosActivo->fields["variacion"]-abs($rsSaldosDep->fields["variacion"]);  
		if(is_array($arrTotal))
		{
			return $arrTotal;
		}
	}
	
	public function tieneMovimiento()
	{
		global $db;
		$cuentasinceros=uf_spg_cuenta_sin_cero($this->sc_cuenta);
		if($cuentasinceros=="")
		{
			$cuentasinceros=$this->sc_cuenta;
		}
		$sql="select * from sigesp_sfp_saldoscon 
			 where sc_cuenta like '{$cuentasinceros}%'
			 and (monto_anreal>0 or monto_anest>0) and 
			 ano_presupuesto={$this->ano_presupuesto}
			 and codemp='{$this->codemp}'";
		$Rs = $db->Execute($sql);
		if($Rs->RecordCount()>0)
		{
			return true;
		}
		else
		{
			 $sql="select * from sigesp_sfp_cmp 
			 inner join  scg_dt_sfp_cmp on sigesp_sfp_cmp.
			 comprobante=scg_dt_sfp_cmp.comprobante
			 where scg_dt_sfp_cmp.sc_cuenta like '{$cuentasinceros}%' and 
			 sigesp_sfp_cmp.ano_presupuesto={$this->ano_presupuesto}
			 and sigesp_sfp_cmp.codemp='{$this->codemp}'";
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
	}
	
	public function LeerAsientoGasto()
	{
		global $db;
		$sql="select scg_dt_sfp_cmp.sc_cuenta as codigo,sigesp_sfp_plan_unico.denominacion,scg_dt_sfp_cmp.debhab as operacion,scg_dt_sfp_cmp.monto
			  from  sigesp_sfp_cmp inner join  scg_dt_sfp_cmp on sigesp_sfp_cmp.comprobante=scg_dt_sfp_cmp.comprobante
			  left outer join sigesp_sfp_plan_unico on scg_dt_sfp_cmp.sc_cuenta=sigesp_sfp_plan_unico.sc_cuenta
			  inner join spe_relacion_es on sigesp_sfp_cmp.codinte = spe_relacion_es.codinte 
			  where sigesp_sfp_cmp.codemp='{$this->codemp}' and sigesp_sfp_cmp.ano_presupuesto={$this->ano_presupuesto}
			  and sigesp_sfp_cmp.sig_cuenta='{$this->sig_cuenta}' and  spe_relacion_es.codinte='{$this->codinte}' order by scg_dt_sfp_cmp.debhab asc";
		//ver($sql);
		$Rs = $db->Execute($sql);
		return $Rs;	  	
	}

		
	
	public function LeerSaldosCont()
	{
		global $db;
		$cuentasinceros=uf_spg_cuenta_sin_cero($this->sc_cuenta);
		if($cuentasinceros=="")
		{
			$cuentasinceros=$this->sc_cuenta;
		}
			
		   $sql="select '{$this->sc_cuenta}' as codcuenta, (select denominacion from sigesp_plan_unico where sc_cuenta='{$this->sc_cuenta}') as denominacion, COALESCE((select sum(monto_anreal) 
			 from sigesp_sfp_saldoscon where sc_cuenta 
			 like '{$cuentasinceros}%' and ano_presupuesto={$this->ano_presupuesto} and codemp='{$this->codemp}'),0000) as anreal,COALESCE((select sum(monto_anest) 
			from sigesp_sfp_saldoscon where sc_cuenta like '{$cuentasinceros}%' and ano_presupuesto={$this->ano_presupuesto} and codemp='{$this->codemp}'),000) as anestimado,
			(COALESCE((select sum(monto_anest) 
			from sigesp_sfp_saldoscon where sc_cuenta like '{$cuentasinceros}%' and ano_presupuesto={$this->ano_presupuesto} and codemp='{$this->codemp}'),000)+(COALESCE((select sum(enero+febrero+marzo) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='D' and sigesp_sfp_cmp.ano_presupuesto={$this->ano_presupuesto} and sigesp_sfp_cmp.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%'),000)+(select COALESCE(sum(enero+febrero+marzo),00) from scg_dt_sfp_cmp 
			inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='D' and sc_cuenta like '{$cuentasinceros}%' 
			and sigesp_sfp_cmp.codemp='{$this->codemp}' and sigesp_sfp_cmp.ano_presupuesto={$this->ano_presupuesto}))
			-
			(COALESCE((select sum(enero+febrero+marzo) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante 
			inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='H' and sc_cuenta like '{$cuentasinceros}%' and sigesp_sfp_cmp.codemp='{$this->codemp}' and sigesp_sfp_cmp.ano_presupuesto=$this->ano_presupuesto),0000)+(COALESCE((select sum(enero+febrero+marzo) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='H' and sigesp_sfp_cmp.ano_presupuesto={$this->ano_presupuesto} and sigesp_sfp_cmp.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%'),000)))) as saldotri1,
			(COALESCE((select sum(monto_anest) 
			from sigesp_sfp_saldoscon where sc_cuenta like '{$cuentasinceros}%' and ano_presupuesto={$this->ano_presupuesto} and codemp='{$this->codemp}'),000)+(COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='D' and sigesp_sfp_cmp.ano_presupuesto={$this->ano_presupuesto} and spe_plan_ingr.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%'),0000)+(select COALESCE(sum(enero+febrero+marzo+abril+mayo+junio),00) from scg_dt_sfp_cmp 
			inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='D' and sc_cuenta like '{$cuentasinceros}%' 
			and sigesp_sfp_cmp.codemp='{$this->codemp}' and sigesp_sfp_cmp.ano_presupuesto={$this->ano_presupuesto}))
			-
			((COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante 
			inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='H' and sc_cuenta like '{$cuentasinceros}%' and sigesp_sfp_cmp.codemp='{$this->codemp}' and sigesp_sfp_cmp.ano_presupuesto={$this->ano_presupuesto}),0000))+(COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='H' and sigesp_sfp_cmp.ano_presupuesto={$this->ano_presupuesto} and sigesp_sfp_cmp.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%'),0000)))) as saldotri2,
			(COALESCE((select sum(monto_anest) 
			from sigesp_sfp_saldoscon where sc_cuenta like '{$cuentasinceros}%' and ano_presupuesto={$this->ano_presupuesto} and codemp='{$this->codemp}'),000)+((COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='D' and sigesp_sfp_cmp.ano_presupuesto=$this->ano_presupuesto and sigesp_sfp_cmp.codemp='$this->codemp' and sc_cuenta like '{$cuentasinceros}%'),0000))+(select COALESCE(sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre),00) from scg_dt_sfp_cmp 
			inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='D' and sc_cuenta like '{$cuentasinceros}%' 
			and sigesp_sfp_cmp.codemp='{$this->codemp}' and sigesp_sfp_cmp.ano_presupuesto={$this->ano_presupuesto}))
			-
			((COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante 
			inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='H' and sc_cuenta like '{$cuentasinceros}%' and sigesp_sfp_cmp.codemp='{$this->codemp}' and sigesp_sfp_cmp.ano_presupuesto={$this->ano_presupuesto}),0000))+(COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='H' and sigesp_sfp_cmp.ano_presupuesto=$this->ano_presupuesto and sigesp_sfp_cmp.codemp='$this->codemp' and sc_cuenta like '{$cuentasinceros}%'),0000)))) as saldotri3,
			
			(COALESCE((select sum(monto_anest) 
			from sigesp_sfp_saldoscon where sc_cuenta like '{$cuentasinceros}%' and ano_presupuesto={$this->ano_presupuesto} and codemp='{$this->codemp}'),000)+((COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='D' and sigesp_sfp_cmp.ano_presupuesto={$this->ano_presupuesto} and sigesp_sfp_cmp.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%'),0000))+(COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) 
			from scg_dt_sfp_cmp inner join sigesp_sfp_cmp
			on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='D' and sc_cuenta like '{$cuentasinceros}%' and sigesp_sfp_cmp.codemp='{$this->codemp}' and
			sigesp_sfp_cmp.ano_presupuesto={$this->ano_presupuesto}),00)))
			-
			((COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante 
			inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='H' and sc_cuenta like '{$cuentasinceros}%' and sigesp_sfp_cmp.codemp='{$this->codemp}' and sigesp_sfp_cmp.ano_presupuesto={$this->ano_presupuesto}),0000))+(COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='H' and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto} and spe_plan_ingr.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%'),0000)))) as saldotri4,
			((COALESCE((select sum(monto_anest) 
			from sigesp_sfp_saldoscon where sc_cuenta like '{$cuentasinceros}%' and ano_presupuesto={$this->ano_presupuesto} and codemp='{$this->codemp}'),000)+((COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='D' and sigesp_sfp_cmp.ano_presupuesto={$this->ano_presupuesto} and sigesp_sfp_cmp.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%'),0000))+(COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) 
			from scg_dt_sfp_cmp inner join sigesp_sfp_cmp
			on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='D' and sc_cuenta like '{$cuentasinceros}%' and sigesp_sfp_cmp.codemp='{$this->codemp}' and
			sigesp_sfp_cmp.ano_presupuesto={$this->ano_presupuesto}),00)))
			-
			((COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante 
			inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='H' and sc_cuenta like '{$cuentasinceros}%' and sigesp_sfp_cmp.codemp='{$this->codemp}' and sigesp_sfp_cmp.ano_presupuesto={$this->ano_presupuesto}),0000))+(COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='H' and sigesp_sfp_cmp.ano_presupuesto={$this->ano_presupuesto} and sigesp_sfp_cmp.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%'),0000)))) -
			(select COALESCE(sum(monto_anest),000) from sigesp_sfp_saldoscon where sc_cuenta like '{$cuentasinceros}%' and ano_presupuesto=$this->ano_presupuesto and codemp='{$this->codemp}')) as variacion";
	
			//ver($sql);
	
		$Rs = $db->Execute($sql);
		return $Rs;	  	
	}


	public function LeerSaldosContcxc()
	{
		global $db;
		$cuentasinceros=uf_spg_cuenta_sin_cero($this->sc_cuenta);
		if($cuentasinceros=="")
		{
			$cuentasinceros=$this->sc_cuenta;
		}
			
		   $sql="select '{$this->sc_cuenta}' as codcuenta, (select denominacion from sigesp_plan_unico where sc_cuenta='{$this->sc_cuenta}') as denominacion, COALESCE((select sum(monto_anreal) 
			 from sigesp_sfp_saldoscon where sc_cuenta 
			 like '{$cuentasinceros}%' and ano_presupuesto={$this->ano_presupuesto} and codemp='{$this->codemp}'),0000) as anreal,COALESCE((select sum(monto_anest) 
			from sigesp_sfp_saldoscon where sc_cuenta like '{$cuentasinceros}%' and ano_presupuesto={$this->ano_presupuesto} and codemp='{$this->codemp}'),000) as anestimado,
			(COALESCE((select sum(monto_anest) 
			from sigesp_sfp_saldoscon where sc_cuenta like '{$cuentasinceros}%' and ano_presupuesto={$this->ano_presupuesto} and codemp='{$this->codemp}'),000)
			+
			(
			COALESCE((select sum(enero+febrero+marzo)  from scg_dt_sfp_cmp 
			inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='D' 
			and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto} 
			and spe_plan_ingr.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%' and (cuentaporcobrar is null or cuentaporcobrar=''))+
			(select sum((enero-enerocob)+(febrero-febrerocob)+(marzo-marzocob))  from scg_dt_sfp_cmp 
			inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='D' 
			and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto} 
			and spe_plan_ingr.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%' and (cuentaporcobrar is not null or cuentaporcobrar<>''))
			,000)
		
			+
		
			(select COALESCE(sum(enero+febrero+marzo),00) from scg_dt_sfp_cmp 
			inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='D' and sc_cuenta like '{$cuentasinceros}%' 
			and spe_int_cuentas.codemp='{$this->codemp}' and spe_int_cuentas.ano_presupuesto={$this->ano_presupuesto}))
			-
			(COALESCE((select sum(enero+febrero+marzo) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante 
			inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='H' and sc_cuenta like '{$cuentasinceros}%' and spe_int_cuentas.codemp='{$this->codemp}' and spe_int_cuentas.ano_presupuesto=$this->ano_presupuesto),0000)+(COALESCE((select sum(enero+febrero+marzo) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='H' and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto} and spe_plan_ingr.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%'),000)))) as saldotri1,
			(COALESCE((select sum(monto_anest) 
			from sigesp_sfp_saldoscon where sc_cuenta like '{$cuentasinceros}%' and ano_presupuesto={$this->ano_presupuesto} and codemp='{$this->codemp}'),000)+
			
			(COALESCE(
			
			(select sum(enero+febrero+marzo+abril+mayo+junio)  from scg_dt_sfp_cmp 
			inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='D' 
			and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto} 
			and spe_plan_ingr.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%' and (cuentaporcobrar is null or cuentaporcobrar=''))+
			(select sum((enero-enerocob)+(febrero-febrerocob)+(marzo-marzocob)+(abril-abrilcob)+(mayo-mayocob)+(junio-juniocob)) from scg_dt_sfp_cmp 
			inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='D' 
			and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto} 
			and spe_plan_ingr.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%' and (cuentaporcobrar is not null or cuentaporcobrar<>''))
	
			,0000)
			
			+
			(select COALESCE(sum(enero+febrero+marzo+abril+mayo+junio),00) from scg_dt_sfp_cmp 
			inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='D' and sc_cuenta like '{$cuentasinceros}%' 
			and spe_int_cuentas.codemp='{$this->codemp}' and spe_int_cuentas.ano_presupuesto={$this->ano_presupuesto}))
			-
			((COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante 
			inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='H' and sc_cuenta like '{$cuentasinceros}%' and spe_int_cuentas.codemp='{$this->codemp}' and spe_int_cuentas.ano_presupuesto={$this->ano_presupuesto}),0000))+(COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='H' and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto} and spe_plan_ingr.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%'),0000)))) as saldotri2,
			(COALESCE((select sum(monto_anest) 
			from sigesp_sfp_saldoscon where sc_cuenta like '{$cuentasinceros}%' and ano_presupuesto={$this->ano_presupuesto} and codemp='{$this->codemp}'),000)
			
			+
			
			((COALESCE(
			
			(select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre)  from scg_dt_sfp_cmp 
			inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='D' 
			and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto} 
			and spe_plan_ingr.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%' and (cuentaporcobrar is null or cuentaporcobrar=''))+
			(select sum((enero-enerocob)+(febrero-febrerocob)+(marzo-marzocob)+(abril-abrilcob)+(mayo-mayocob)+(junio-juniocob)+(julio-juliocob)+(agosto-agostocob)+(septiembre-septiembrecob)) from scg_dt_sfp_cmp 
			inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='D' 
			and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto} 
			and spe_plan_ingr.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%' and (cuentaporcobrar is not null or cuentaporcobrar<>''))
			
			,0000))
			+
			(select COALESCE(sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre),00) from scg_dt_sfp_cmp 
			inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='D' and sc_cuenta like '{$cuentasinceros}%' 
			and spe_int_cuentas.codemp='{$this->codemp}' and spe_int_cuentas.ano_presupuesto={$this->ano_presupuesto}))
			-
			((COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante 
			inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='H' and sc_cuenta like '{$cuentasinceros}%' and spe_int_cuentas.codemp='{$this->codemp}' and spe_int_cuentas.ano_presupuesto={$this->ano_presupuesto}),0000))+(COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='H' and spe_plan_ingr.ano_presupuesto=$this->ano_presupuesto and spe_plan_ingr.codemp='$this->codemp' and sc_cuenta like '{$cuentasinceros}%'),0000)))) as saldotri3,
			
			(COALESCE((select sum(monto_anest) 
			from sigesp_sfp_saldoscon where sc_cuenta like '{$cuentasinceros}%' and ano_presupuesto={$this->ano_presupuesto} and codemp='{$this->codemp}'),000)
			
			+
			((COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre)  from scg_dt_sfp_cmp 
			inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='D' 
			and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto} 
			and spe_plan_ingr.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%' and (cuentaporcobrar is null or cuentaporcobrar=''))+
			(select sum((enero-enerocob)+(febrero-febrerocob)+(marzo-marzocob)+(abril-abrilcob)+(mayo-mayocob)+(junio-juniocob)+(julio-juliocob)+(agosto-agostocob)+(septiembre-septiembrecob)+(octubre-octubrecob)+(noviembre-noviembrecob)+(diciembre-diciembrecob)) from scg_dt_sfp_cmp 
			inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='D' 
			and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto} 
			and spe_plan_ingr.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%' and (cuentaporcobrar is not null or cuentaporcobrar<>''))
			,0000))

			+
			(COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) 
			from scg_dt_sfp_cmp inner join sigesp_sfp_cmp
			on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='D' and sc_cuenta like '{$cuentasinceros}%' and spe_int_cuentas.codemp='{$this->codemp}' and
			spe_int_cuentas.ano_presupuesto={$this->ano_presupuesto}),00)))
			-
			((COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante 
			inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='H' and sc_cuenta like '{$cuentasinceros}%' and spe_int_cuentas.codemp='{$this->codemp}' and spe_int_cuentas.ano_presupuesto={$this->ano_presupuesto}),0000))+(COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='H' and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto} and spe_plan_ingr.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%'),0000)))) as saldotri4,
			((COALESCE((select sum(monto_anest) 
			from sigesp_sfp_saldoscon where sc_cuenta like '{$cuentasinceros}%' and ano_presupuesto={$this->ano_presupuesto} and codemp='{$this->codemp}'),000)
			+
			
			((COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre)  from scg_dt_sfp_cmp 
			inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='D' 
			and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto} 
			and spe_plan_ingr.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%' and (cuentaporcobrar is null or cuentaporcobrar=''))+
			(select sum((enero-enerocob)+(febrero-febrerocob)+(marzo-marzocob)+(abril-abrilcob)+(mayo-mayocob)+(junio-juniocob)+(julio-juliocob)+(agosto-agostocob)+(septiembre-septiembrecob)+(octubre-octubrecob)+(noviembre-noviembrecob)+(diciembre-diciembrecob)) from scg_dt_sfp_cmp 
			inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='D' 
			and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto} 
			and spe_plan_ingr.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%' and (cuentaporcobrar is not null or cuentaporcobrar<>'')),0000))
			
			+
			(COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) 
			from scg_dt_sfp_cmp inner join sigesp_sfp_cmp
			on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='D' and sc_cuenta like '{$cuentasinceros}%' and spe_int_cuentas.codemp='{$this->codemp}' and
			spe_int_cuentas.ano_presupuesto={$this->ano_presupuesto}),00)))
			-
			((COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante 
			inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='H' and sc_cuenta like '{$cuentasinceros}%' and spe_int_cuentas.codemp='{$this->codemp}' and spe_int_cuentas.ano_presupuesto={$this->ano_presupuesto}),0000))+(COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='H' and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto} and spe_plan_ingr.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%'),0000)))) -
			(select COALESCE(sum(monto_anest),000) from sigesp_sfp_saldoscon where sc_cuenta like '{$cuentasinceros}%' and ano_presupuesto=$this->ano_presupuesto and codemp='{$this->codemp}')) as variacion";

						
	if($this->sc_cuenta=='123010600')
	{
		//ver($sql);
	}
		$Rs = $db->Execute($sql);
		return $Rs;	  	
	}
	
	public function LeerSaldosContNeto($rsCuenta,$rsResta)
	{
		global $db;
		$sql="select '' as codcuenta,'{$rsCuenta->fields['denominacion']}- Neto' as Denominacion,
			{$rsCuenta->fields['anreal']}-(".abs($rsResta->fields['anreal']).") as anreal,
			{$rsCuenta->fields['anestimado']}-(".abs($rsResta->fields['anestimado']).") as anestimado,
			{$rsCuenta->fields['saldotri1']}-(".abs($rsResta->fields['saldotri1']).") as saldotri1,
			{$rsCuenta->fields['saldotri2']}-(".abs($rsResta->fields['saldotri2']).") as saldotri2,
			{$rsCuenta->fields['saldotri3']}-(".abs($rsResta->fields['saldotri3']).") as saldotri3,
			{$rsCuenta->fields['saldotri4']}-(".abs($rsResta->fields['saldotri4']).") as saldotri4,
			{$rsCuenta->fields['variacion']}-(".abs($rsResta->fields['variacion']).") as variacion";

		$Rs = $db->Execute($sql);		
		return $Rs;	  
	}
	
	public function LeerSaldosContPasivo2()
	{
		global $db;
		//$db->debug=true;
		$cuentasinceros=uf_spg_cuenta_sin_cero($this->sc_cuenta);
		if($cuentasinceros=="")
		{
			$cuentasinceros=$this->sc_cuenta;
		}
		$sql="select '{$this->sc_cuenta}' as codcuenta, (select denominacion from sigesp_plan_unico where sc_cuenta='{$this->sc_cuenta}') as denominacion, COALESCE((select sum(monto_anreal) 
			 from sigesp_sfp_saldoscon where sc_cuenta 
			 like '{$cuentasinceros}%' and ano_presupuesto={$this->ano_presupuesto} and codemp='{$this->codemp}'),0000) as anreal,COALESCE((select sum(monto_anest) 
			from sigesp_sfp_saldoscon where sc_cuenta like '{$cuentasinceros}%' and ano_presupuesto={$this->ano_presupuesto} and codemp='{$this->codemp}'),000) as anestimado,
			(COALESCE((select sum(monto_anest) 
			from sigesp_sfp_saldoscon where sc_cuenta like '{$cuentasinceros}%' and ano_presupuesto={$this->ano_presupuesto} and codemp='{$this->codemp}'),000)+(COALESCE((select sum(enero+febrero+marzo) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='H' and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto} and spe_plan_ingr.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%'),000)+(select COALESCE(sum(enero+febrero+marzo),00) from scg_dt_sfp_cmp 
			inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='H' and sc_cuenta like '{$cuentasinceros}%' 
			and spe_int_cuentas.codemp='{$this->codemp}' and spe_int_cuentas.ano_presupuesto={$this->ano_presupuesto}))
			-
			(COALESCE((select sum(enero+febrero+marzo) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante 
			inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='D' and sc_cuenta like '{$cuentasinceros}%' and spe_int_cuentas.codemp='{$this->codemp}' and spe_int_cuentas.ano_presupuesto=$this->ano_presupuesto),0000)+(COALESCE((select sum(enero+febrero+marzo) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='D' and spe_plan_ingr.ano_presupuesto=$this->ano_presupuesto and spe_plan_ingr.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%'),000)))) as saldotrimestr1,
			(COALESCE((select sum(monto_anest) 
			from sigesp_sfp_saldoscon where sc_cuenta like '{$cuentasinceros}%' and ano_presupuesto={$this->ano_presupuesto} and codemp='{$this->codemp}'),000)+(COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='H' and spe_plan_ingr.ano_presupuesto=$this->ano_presupuesto and spe_plan_ingr.codemp='0001' and sc_cuenta like '{$cuentasinceros}%'),0000)+(select COALESCE(sum(enero+febrero+marzo),00) from scg_dt_sfp_cmp 
			inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='H' and sc_cuenta like '{$cuentasinceros}%' 
			and spe_int_cuentas.codemp='{$this->codemp}' and spe_int_cuentas.ano_presupuesto={$this->ano_presupuesto}))
			-
			((COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante 
			inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='D' and sc_cuenta like '{$cuentasinceros}%' and spe_int_cuentas.codemp='{$this->codemp}' and spe_int_cuentas.ano_presupuesto={$this->ano_presupuesto}),0000))+(COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='D' and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto} and spe_plan_ingr.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%'),0000)))) as trimestre2,
			(COALESCE((select sum(monto_anest) 
			from sigesp_sfp_saldoscon where sc_cuenta like '{$cuentasinceros}%' and ano_presupuesto={$this->ano_presupuesto} and codemp='{$this->codemp}'),000)+((COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='H' and spe_plan_ingr.ano_presupuesto=$this->ano_presupuesto and spe_plan_ingr.codemp='$this->codemp' and sc_cuenta like '{$cuentasinceros}%'),0000))+(select COALESCE(sum(enero+febrero+marzo),00) from scg_dt_sfp_cmp 
			inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='H' and sc_cuenta like '{$cuentasinceros}%' 
			and spe_int_cuentas.codemp='{$this->codemp}' and spe_int_cuentas.ano_presupuesto={$this->ano_presupuesto}))
			-
			((COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante 
			inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='D' and sc_cuenta like '{$cuentasinceros}%' and spe_int_cuentas.codemp='{$this->codemp}' and spe_int_cuentas.ano_presupuesto={$this->ano_presupuesto}),0000))+(COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='D' and spe_plan_ingr.ano_presupuesto=$this->ano_presupuesto and spe_plan_ingr.codemp='$this->codemp' and sc_cuenta like '{$cuentasinceros}%'),0000)))) as saldotrimestre3,
			
			(COALESCE((select sum(monto_anest) 
			from sigesp_sfp_saldoscon where sc_cuenta like '{$cuentasinceros}%' and ano_presupuesto={$this->ano_presupuesto} and codemp='{$this->codemp}'),000)+((COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='H' and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto} and spe_plan_ingr.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%'),0000))+(COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) 
			from scg_dt_sfp_cmp inner join sigesp_sfp_cmp
			on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='H' and sc_cuenta like '{$cuentasinceros}%' and spe_int_cuentas.codemp='{$this->codemp}' and
			spe_int_cuentas.ano_presupuesto={$this->ano_presupuesto}),00)))
			-
			((COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante 
			inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='D' and sc_cuenta like '{$cuentasinceros}%' and spe_int_cuentas.codemp='{$this->codemp}' and spe_int_cuentas.ano_presupuesto={$this->ano_presupuesto}),0000))+(COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='D' and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto} and spe_plan_ingr.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%'),0000)))) as saldotrimestre4,
			((COALESCE((select sum(monto_anest) 
			from sigesp_sfp_saldoscon where sc_cuenta like '{$cuentasinceros}%' and ano_presupuesto={$this->ano_presupuesto} and codemp='{$this->codemp}'),000)+((COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='H' and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto} and spe_plan_ingr.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%'),0000))+(COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) 
			from scg_dt_sfp_cmp inner join sigesp_sfp_cmp
			on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='H' and sc_cuenta like '{$cuentasinceros}%' and spe_int_cuentas.codemp='{$this->codemp}' and
			spe_int_cuentas.ano_presupuesto={$this->ano_presupuesto}),00)))
			-
			((COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante 
			inner join spe_int_cuentas on
			sigesp_sfp_cmp.sig_cuenta=spe_int_cuentas.sig_cuenta and sigesp_sfp_cmp.codinte=spe_int_cuentas.codinte
			where scg_dt_sfp_cmp.debhab='D' and sc_cuenta like '{$cuentasinceros}%' and spe_int_cuentas.codemp='{$this->codemp}' and spe_int_cuentas.ano_presupuesto={$this->ano_presupuesto}),0000))+(COALESCE((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from scg_dt_sfp_cmp inner join sigesp_sfp_cmp on scg_dt_sfp_cmp.comprobante=sigesp_sfp_cmp.comprobante inner join spe_plan_ingr on
			sigesp_sfp_cmp.sig_cuenta=spe_plan_ingr.sig_cuenta where scg_dt_sfp_cmp.debhab='D' and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto} and spe_plan_ingr.codemp='{$this->codemp}' and sc_cuenta like '{$cuentasinceros}%'),0000)))) -
			(select COALESCE(sum(monto_anest),000) from sigesp_sfp_saldoscon where sc_cuenta like '{$cuentasinceros}%' and ano_presupuesto=$this->ano_presupuesto and codemp='{$this->codemp}')) as variacion";

//			if($this->sc_cuenta=='220000000')
//			{
//				ver($sql);
//			}
			
		$Rs = $db->Execute($sql);
		return $Rs;	  	
	}	
	
	public function reporte_balance_general()
	{
	
		$la_cuentas[1]  ='100000000';//activo
		$la_cuentas[2]  ='110000000';//activo circulante
		$la_cuentas[3]  ='111000000';//activo disponible
		$la_cuentas[4]  ='111010000';
		$la_cuentas[5]  ='111010100';
		$la_cuentas[6]  ='111010200';
		$la_cuentas[7]  ='111010201';
		$la_cuentas[8]  ='111010202';
		$la_cuentas[9]  ='111010203';
		$la_cuentas[10] ='111020000';
		//activos circuilante exigible
		
		$la_cuentas[11] ='112000000';//activo exigible
		$la_cuentas[12] ='112010000';
		$la_cuentas[13] ='112010100';
		$la_cuentas[14] ='112010200';
		$la_cuentas[15] ='112020000';
		$la_cuentas[16] ='112020100';
		$la_cuentas[17] ='112020200';
		$la_cuentas[18] ='112030000';
		
		$la_cuentas[19] ='224010100';// menos provision para cuentas incobrables
		$la_cuentas[20] ='112049900';
		$la_cuentas[21] ='112050000';
		$la_cuentas[22] ='112060000';
		$la_cuentas[23] ='112100000';
		$la_cuentas[24] ='112110000';
		$la_cuentas[25] ='112190000';
		
		
		$la_cuentas[26] ='113000000';// activos realizables
		$la_cuentas[27] ='113010000';
		$la_cuentas[28] ='113020000';
		$la_cuentas[29] ='113030000';
		$la_cuentas[30] ='113040000';
		$la_cuentas[31] ='113050000';
		$la_cuentas[32] ='224010300';
		$la_cuentas[33] ='113060000';
		$la_cuentas[34] ='113060100';
		$la_cuentas[35] ='113060200';
		$la_cuentas[36] ='113060300';
		$la_cuentas[37] ='114000000';// activos diferidos a corto plazo
		$la_cuentas[38] ='114010000';
		$la_cuentas[39] ='114010300';
		$la_cuentas[40] ='114010900';
		$la_cuentas[41] ='114990000';
		$la_cuentas[42] ='119000000';//otros activos circulates
		$la_cuentas[43] ='119090000';
		
		//activos no circulantes
		$la_cuentas[45]  ='120000000';
		$la_cuentas[46]  ='121000000';
		$la_cuentas[47]  ='121010000';
		$la_cuentas[48]  ='121010100';
		$la_cuentas[49]  ='121010200';
		$la_cuentas[50]  ='121020000';
		$la_cuentas[51]  ='121020100';
		$la_cuentas[52]  ='121020200';
		$la_cuentas[54]  ='121030000';
		$la_cuentas[55]  ='121030100';
		$la_cuentas[56]  ='121030200';
		$la_cuentas[57]  ='122000000';
		$la_cuentas[58]  ='122010000';
		$la_cuentas[59]  ='122020000';
		$la_cuentas[60]  ='122030000';
		$la_cuentas[61]  ='122040000';
		$la_cuentas[62]  ='122050000';
		$la_cuentas[63]  ='123000000';
		$la_cuentas[64]  ='123010000';
		
		
		$la_cuentas[65]  ='123010100';
		$la_cuentas[66]  ='225010100';
		$la_cuentas[67]  ='123010200';
		$la_cuentas[68]  ='225010200';
		$la_cuentas[69]  ='123010300';
		$la_cuentas[70]  ='225010300';
		$la_cuentas[71]  ='123010400';
		$la_cuentas[72]  ='225010400';
		$la_cuentas[73]  ='123010500';
		$la_cuentas[74]  ='225010500';
		$la_cuentas[75]  ='123010600';
		$la_cuentas[76]  ='225010600';
		$la_cuentas[77]  ='123010700';
		$la_cuentas[78]  ='225010700';
		$la_cuentas[79]  ='123010800';
		$la_cuentas[80]  ='225010800';
		$la_cuentas[81]  ='123010900';
		$la_cuentas[82]  ='225010900';
		$la_cuentas[83]  ='123011900';
		$la_cuentas[84]  ='225011900';
		
		
		$la_cuentas[85]  ='123020000';
		$la_cuentas[86]  ='123030000';
		$la_cuentas[87]  ='123040000';
		$la_cuentas[88]  ='123050000';
		$la_cuentas[89]  ='123050100';
		$la_cuentas[90]  ='123050200';
		$la_cuentas[91]  ='124000000';
		
		
		$la_cuentas[92]  ='124010000';
		$la_cuentas[93]  ='225020100';
		$la_cuentas[94]  ='124020000';
		$la_cuentas[95]  ='225020200';
		$la_cuentas[96]  ='124030000';
		$la_cuentas[97]  ='225020300';
		$la_cuentas[98]  ='124040000';
		$la_cuentas[99]  ='225020400';
		$la_cuentas[100]  ='124050000';
		$la_cuentas[101]  ='225020500';
		$la_cuentas[102]  ='124190000';
		$la_cuentas[103]  ='225021900';
		
		
		$la_cuentas[104]  ='125000000';
		$la_cuentas[105]  ='125010000';
		$la_cuentas[106]  ='125010600';
		$la_cuentas[107]  ='125010900';
		$la_cuentas[108]  ='125090000';
		$la_cuentas[109]  ='129000000';
		$la_cuentas[110]  ='129010000';
		$la_cuentas[111]  ='129010100';
		$la_cuentas[112]  ='129090000';
		//pasivos
				
		$la_cuentas[113]  ='200000000';
		$la_cuentas[114]  ='210000000';
		$la_cuentas[115]  ='211010000';
		$la_cuentas[116]  ='211020000';
		$la_cuentas[117]  ='211030000';
		$la_cuentas[118]  ='211040000';
		$la_cuentas[119]  ='211050000';
		$la_cuentas[120]  ='214000000';
		$la_cuentas[121]  ='214010000';
		$la_cuentas[122]  ='214090000';
		$la_cuentas[123]  ='219000000';
		$la_cuentas[124]  ='219090000';
		$la_cuentas[125]  ='220000000';
		$la_cuentas[126]  ='221000000';
		$la_cuentas[127]  ='221010000';
		$la_cuentas[128]  ='221020000';
		$la_cuentas[129]  ='224000000';
		$la_cuentas[130]  ='224010000';
		$la_cuentas[131]  ='224010200';
		$la_cuentas[132]  ='224010400';
		$la_cuentas[133]  ='224010900';
		$la_cuentas[134]  ='224020000';
		$la_cuentas[135]  ='229000000';
		$la_cuentas[136]  ='229090000';
		
		// patrimonio
		$la_cuentas[137]  ='300000000';
		$la_cuentas[138]  ='320000000';	
		$la_cuentas[139]  ='321000000';
		$la_cuentas[140]  ='321010000';	
		$la_cuentas[141]  ='322000000';	
		$la_cuentas[142]  ='322010000';	
		$la_cuentas[143]  ='322010100';	
		$la_cuentas[144]  ='322010200';	
		$la_cuentas[145]  ='322010300';
		$la_cuentas[146]  ='322020000';
		$la_cuentas[147]  ='322020100';	
		$la_cuentas[148]  ='322020200';	
		$la_cuentas[149]  ='323000000';	
		$la_cuentas[150]  ='323010000';
		$la_cuentas[151]  ='324000000';	
		$la_cuentas[152]  ='324010000';	
		
		
		$id1=0;
		$datastore1=Array();
		$id2=0;
		$datastore2=Array();
		$id3=0;
		$datastore3=Array();
		$id4=0;
		$datastore4=Array();
				
		for($i=1;$i<=155;$i++)
  		{		
  			if($i>=1 && $i<=43)
  			{
	  			if($i>=11 && $i<=18)
	  			{
					$oCuenta= new Asientos();
					$oCuenta->sc_cuenta = $la_cuentas[$i];
					if($oCuenta->tieneMovimiento()===true)
					{
						$oConversion = new ConversionDao;
						$oConversion->sc_cuenta=trim($la_cuentas[$i]);
						$resp =  $oConversion->ExisteCambio();
						$datastore1[$id1]=$oCuenta->LeerSaldosContcxc();
						$id1++;
					}
	  			}
	  			else
	  			{	
	  				
					$oCuenta= new Asientos();
					$oCuenta->sc_cuenta = $la_cuentas[$i];
					if($oCuenta->tieneMovimiento()===true)
					{
						
						$oConversion = new ConversionDao;
						$oConversion->sc_cuenta=trim($la_cuentas[$i]);
						$resp =  $oConversion->ExisteCambio();
						$datastore1[$id1]=$oCuenta->LeerSaldosCont();
						$id1++;
						
					} 
		  		}
  			}
  			
	  		if($i>=45 && $i<=112)
  			{
				$oCuenta= new Asientos();
				$oCuenta->sc_cuenta = $la_cuentas[$i];
				if($oCuenta->tieneMovimiento()===true)
				{
					$oConversion->sc_cuenta=trim($la_cuentas[$i]);				
					$datastore2[$id2]=$oCuenta->LeerSaldosCont();
					$idActual = $id2;
					$id2++; 
					
					if($i>=65 && $i<=84)
					{
						if(($i%2)==0)
						{
							$datastore2[$id2]=$oCuenta->LeerSaldosContNeto($rsAnterior,$datastore2[$idActual]);
							$id2++;	
						}
					}
					
					
					
					if($i>=92 && $i<=103)
					{
						if(($i%2)!=0)
						{
							$datastore2[$id2]=$oCuenta->LeerSaldosContNeto($rsAnterior,$datastore2[$idActual]);
							$id2++;	
						}
					}
					
					
					
					
				}
				$rsAnterior=$datastore2[$idActual];
			}
	  		
	  		if($i>=113 && $i<=136)
  			{
				$oCuenta= new Asientos();
				$oCuenta->sc_cuenta = $la_cuentas[$i];
				if($oCuenta->tieneMovimiento()===true)
				{
					$datastore3[$id3]=$oCuenta->LeerSaldosContPasivo2();
			  		$id3++; 				
	  			}
  			}
	  		
	  		if($i>=137 && $i<=152)
  			{
				$oCuenta= new Asientos();
				$oCuenta->sc_cuenta = $la_cuentas[$i];
				if($oCuenta->tieneMovimiento()===true)
				{
					$datastore4[$id4]=$oCuenta->LeerSaldosContPasivo2();
		  			$id4++; 			
				}
	  		}
  		}
  		$data["datos0"]=$datastore1;
  		$data["datos1"]=$datastore2;
  		$data["datos2"]=$datastore3;
  		$data["datos3"]=$datastore4;
  		$data["datos4"]=$this->LeerResultadoEjercicio();
  		$data["datos5"]=$this->LeerCapital();
  		$data["datos6"]=$this->LeerActivoNeto();
  		
		return $data;
	}
	
}
?>