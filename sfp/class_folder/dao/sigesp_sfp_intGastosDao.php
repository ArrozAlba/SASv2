<?php
require_once("../class_folder/sigesp_conexion_dao.php");
require_once("sigesp_sfp_plan_unico_reDao.php");
require_once('../class_folder/dao/sigesp_sfp_asientosvariacionDao.php');
require_once("../class_folder/dao/sigesp_sfp_planingresoDao.php");
require_once('../class_folder/dao/sigesp_sfp_asientosvariacionDao.php');
class intGastosDao extends ADOdb_Active_Record
{
	var $fuen=array();
	var $_table='spe_int_cuentas';
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
		try
		{
			global $db;
		//	$db->debug=1;
			$db->StartTrans();
			$this->save();
			if($db->CompleteTrans());
			{
				for($j=0;$j<count($this->fuen);$j++)
				{
					$this->fuen[$j]->ano_presupuesto = $this->ano_presupuesto;
					$this->fuen[$j]->codinte = $this->codinte;
					$this->fuen[$j]->sig_cuenta_gas = $this->sig_cuenta;
					$this->fuen[$j]->codemp = $this->codemp;
					if($this->fuen[$j]->Incluir()=="1")
					{
						$oIngreso = new planIngreso();
						$oIngreso->ActualizarDisponibilidad($this->fuen[$j]->sig_cuenta_ing,$this->fuen[$j]->montoasig,$this->ano_presupuesto,$this->codemp);	
					}
				}

			}
			return "1";
		}
		catch (Exception $e) 
		{

    		return "0";
		}
	}

	public function BuscarCuentasGasto($integracion)
	{
		global $db;
		//$db->debug=true;
		$sql = "select sigesp_sfp_plancuentas.denominacion,sigesp_sfp_plancuentas.monto_anreal,sigesp_sfp_plancuentas.monto_anest,{$this->_table}.sig_cuenta as spg_cuenta,{$this->_table}.*,
		({$this->_table}.enero+{$this->_table}.febrero+{$this->_table}.marzo+{$this->_table}.abril+{$this->_table}.mayo+{$this->_table}.junio+{$this->_table}.julio+{$this->_table}.agosto+{$this->_table}
		.septiembre+{$this->_table}.octubre+{$this->_table}.noviembre+{$this->_table}.diciembre) as montoglobal from {$this->_table}  inner join sigesp_sfp_plancuentas 
		on spe_int_cuentas.sig_cuenta=sigesp_sfp_plancuentas.sig_cuenta and spe_int_cuentas.codemp=sigesp_sfp_plancuentas.codemp
 		and spe_int_cuentas.ano_presupuesto=sigesp_sfp_plancuentas.ano_presupuesto where {$this->_table}.codinte ={$integracion} and {$this->_table}.codemp='{$this->codemp}' order by {$this->_table}.sig_cuenta asc";
		//ver($sql);
 		$Rs = $db->Execute($sql); 
		return $Rs;
	}
	public function BuscarCuentasGastoFu($integracion)
	{
		global $db;
		$sql = "select {$this->_table}.codinte,{$this->_table}.sig_cuenta as spg_cuenta,sfp_fuentefinanciamientos.denfuefin,sfp_fuentefinanciamientos.cod_fuenfin,spe_int_cuenta_dtfuentefin.montoA as montot from spe_int_cuenta_dtfuentefin inner join sfp_fuentefinanciamientos on spe_int_cuenta_dtfuentefin.cod_fuenfin=sfp_fuentefinanciamientos.cod_fuenfin inner join {$this->_table} on spe_int_cuenta_dtfuentefin.codinte={$this->_table}.codinte and spe_int_cuenta_dtfuentefin.cuenta={$this->_table}.sig_cuenta and spe_int_cuenta_dtfuentefin.codemp={$this->_table}.codemp and spe_int_cuenta_dtfuentefin.ano_presupuesto={$this->_table}.ano_presupuesto where {$this->_table}.codinte ={$integracion} and {$this->_table}.codemp='{$this->codemp}'";
		$Rs = $db->Execute($sql); 
		return $Rs;
	}
	public function leerDatosReporte()
	{
		global $db;
		$sql=" montoanant,montoanreal,(enero+febrero+marzo) as trimestre1,(abril+mayo+junio) as trimestre2,(julio+agosto+septiembre) as trimestre3,(octubre+noviembre+diciembre) as trimestre4 
				where {$this->_table}.codemp=$this->codemp and {$this->_table}.ano_presupuesto={$this->ano_presupuesto} and {$this->_table}.sig_cuenta='{$this->sig_cuenta}' and {$this->_table}.codemp='{$this->codemp}'";
	}
	public function LeerCuentasAgrupadas()
	{
		global $db;
		//$db->debug=true;
		$sql = "(select '401' as grupo,'Gastos de Personal' as denominacion,coalesce(sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre),000) as Montopre, coalesce((select sum(monto_anest) from sigesp_sfp_plancuentas where sig_cuenta like '401%'),00) as montoanant, coalesce((select sum(monto_anreal) from sigesp_sfp_plancuentas where sig_cuenta like '401%'),000) as montoanreal 
				from spe_int_cuentas inner join sigesp_plan_unico_re on sigesp_plan_unico_re.sig_cuenta=substr(spe_int_cuentas.sig_cuenta,1,9) inner join sigesp_sfp_plancuentas on spe_int_cuentas.sig_cuenta=sigesp_sfp_plancuentas.sig_cuenta where spe_int_cuentas.sig_cuenta like '401%' and {$this->_table}.codemp='{$this->codemp}')
				union
				(select '402' as grupo,'Materiales, Suministros y Mercancia' as denominacion,coalesce(sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre),000) as Montopre,coalesce((select sum(monto_anest) from sigesp_sfp_plancuentas where sig_cuenta like '402%'),000) as montoanant,coalesce((select sum(monto_anreal) from sigesp_sfp_plancuentas where sig_cuenta like '402%'),000) as montoanreal 
				from spe_int_cuentas inner join sigesp_plan_unico_re on sigesp_plan_unico_re.sig_cuenta=substr(spe_int_cuentas.sig_cuenta,1,9) inner join sigesp_sfp_plancuentas on spe_int_cuentas.sig_cuenta=sigesp_sfp_plancuentas.sig_cuenta where spe_int_cuentas.sig_cuenta like '402%' and {$this->_table}.codemp='{$this->codemp}')
				union
				(select '403' as grupo,'Servicios no Personales' as denominacion,coalesce(sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre),000) as Montopre,coalesce((select sum(monto_anest) from sigesp_sfp_plancuentas where sig_cuenta like '403%'),000) as montoanant,coalesce((select sum(monto_anreal) from sigesp_sfp_plancuentas where sig_cuenta like '403%'),000) as montoanreal 
				from spe_int_cuentas inner join sigesp_plan_unico_re on sigesp_plan_unico_re.sig_cuenta=substr(spe_int_cuentas.sig_cuenta,1,9) inner join sigesp_sfp_plancuentas on spe_int_cuentas.sig_cuenta=sigesp_sfp_plancuentas.sig_cuenta where spe_int_cuentas.sig_cuenta like '403%' and {$this->_table}.codemp='{$this->codemp}')
				union
				(select '404' as grupo,'Activos Reales' as denominacion,coalesce(sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre),000) as Montopre,coalesce((select sum(monto_anest) from sigesp_sfp_plancuentas where sig_cuenta like '404%'),000) as montoanant,coalesce((select sum(monto_anreal) from sigesp_sfp_plancuentas where sig_cuenta like '404%'),000) as montoanreal 
				from spe_int_cuentas inner join sigesp_plan_unico_re on sigesp_plan_unico_re.sig_cuenta=substr(spe_int_cuentas.sig_cuenta,1,9) inner join sigesp_sfp_plancuentas on spe_int_cuentas.sig_cuenta=sigesp_sfp_plancuentas.sig_cuenta where spe_int_cuentas.sig_cuenta like '404%' and {$this->_table}.codemp='{$this->codemp}')
				union
				(select '405' as grupo,'Activos Financieros' as denominacion, coalesce(sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre),000) as Montopre, coalesce((select sum(monto_anest) from sigesp_sfp_plancuentas where sig_cuenta like '405%'),000) as montoanant, coalesce((select sum(monto_anreal) from sigesp_sfp_plancuentas where sig_cuenta like '405%'),000) as montoanreal 
				from spe_int_cuentas inner join sigesp_plan_unico_re on sigesp_plan_unico_re.sig_cuenta=substr(spe_int_cuentas.sig_cuenta,1,9) inner join sigesp_sfp_plancuentas on spe_int_cuentas.sig_cuenta=sigesp_sfp_plancuentas.sig_cuenta where spe_int_cuentas.sig_cuenta like '405%' and {$this->_table}.codemp='{$this->codemp}')
				union
				(select '407' as grupo,'Transferencias y Donaciones' as denominacion, coalesce(sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre),000) as Montopre, coalesce((select sum(monto_anest) from sigesp_sfp_plancuentas where sig_cuenta like '407%'),000) as montoanant, coalesce((select sum(monto_anreal) from sigesp_sfp_plancuentas where sig_cuenta like '407%'),000) as montoanreal 
				from spe_int_cuentas inner join sigesp_plan_unico_re on sigesp_plan_unico_re.sig_cuenta=substr(spe_int_cuentas.sig_cuenta,1,9) inner join sigesp_sfp_plancuentas on spe_int_cuentas.sig_cuenta=sigesp_sfp_plancuentas.sig_cuenta where spe_int_cuentas.sig_cuenta like '407%' and {$this->_table}.codemp='{$this->codemp}')
				union
				(select '408' as grupo,'Otros Gastos' as denominacion, coalesce(sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre),000) as Montopre, coalesce((select sum(monto_anest) from sigesp_sfp_plancuentas where sig_cuenta like '408%'),000) as montoanant, coalesce((select sum(monto_anreal) from sigesp_sfp_plancuentas where sig_cuenta like '408%'),000) as montoanreal 
				from spe_int_cuentas inner join sigesp_plan_unico_re on sigesp_plan_unico_re.sig_cuenta=substr(spe_int_cuentas.sig_cuenta,1,9) inner join sigesp_sfp_plancuentas on spe_int_cuentas.sig_cuenta=sigesp_sfp_plancuentas.sig_cuenta where spe_int_cuentas.sig_cuenta like '408%' and {$this->_table}.codemp='{$this->codemp}')
				union
				(select '411' as grupo,'Disminucion de Pasivos' as denominacion, coalesce(sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre),000) as Montopre, coalesce((select sum(monto_anest) from sigesp_sfp_plancuentas where sig_cuenta like '411%'),000) as montoanant, coalesce((select sum(monto_anreal) from sigesp_sfp_plancuentas where sig_cuenta like '411%'),000) as montoanreal 
				from spe_int_cuentas inner join sigesp_plan_unico_re on sigesp_plan_unico_re.sig_cuenta=substr(spe_int_cuentas.sig_cuenta,1,9) inner join sigesp_sfp_plancuentas on spe_int_cuentas.sig_cuenta=sigesp_sfp_plancuentas.sig_cuenta where spe_int_cuentas.sig_cuenta like '411%' and {$this->_table}.codemp='{$this->codemp}')";
		
		
		$Rs = $db->Execute($sql);
	//	die(); 
		return $Rs;
	}
	public function Leertotalgrupo()
	{
		global $db;
		//$db->debug=true;
	
		$sql="select coalesce(sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre),000) 
			 as Montopre from spe_int_cuentas inner join spe_relacion_es on  spe_int_cuentas.codinte=spe_relacion_es.codinte 
			 where spe_int_cuentas.sig_cuenta like '{$this->grupo}%' 
			 and {$this->_table}.codemp='{$this->codemp}' and spe_relacion_es.codestpro1='{$this->estructura}'";
	
		$Rs = $db->Execute($sql); 
		return $Rs->fields["montopre"];	 
	}
	
	
	public function Leertotalplan()
	{
		global $db;
		//$db->debug=true;
	
		$sql="select coalesce(sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre),000) 
			as Montopre 
			from spe_int_cuentas as gastos 
			inner join spe_relacion_es as inte on gastos.codinte=inte.codinte inner join 
			spe_estpro4 as plan on inte.codest1=plan.codest1 and  
			inte.codest2=plan.codest2 and 
			inte.codest3=plan.codest3 and
			inte.codest4=plan.codest4 
			where gastos.sig_cuenta like '{$this->grupo}%' 
			and gastos.codemp='{$this->codemp}'";
			 if($this->codest1!="")
			 {
				$sql.=" and plan.codest1='{$this->codest1}'";	 
			 }
			 if($this->codest2!="")
			 {
				$sql.=" and plan.codest2='{$this->codest2}'";	 
			 }
			 if($this->codest3!="")
			 {
				$sql.=" and plan.codest3='{$this->codest3}'";	 
			 }
			 if($this->codest4!="")
			 {
				$sql.=" and plan.codest4='{$this->codest4}'";	 
			 }
			 if($this->codest5!="")
			 {
				$sql.=" and plan.codest5='{$this->codest5}'";	 
			 }
			
		$Rs = $db->Execute($sql); 
		return $Rs->fields["montopre"];	 
	}
	
	public function Leermetasplan()
	{
		global $db;
		//$db->debug=true;
	
		$sql="select sig_variables.cod_var as codigo,sig_variables.denominacion,coalesce(sum(coalesce(enero_masc,0)+coalesce(febrero_masc,0)+coalesce(marzo_masc,0)+coalesce(abril_masc,0)+coalesce(mayo_masc,0)+coalesce(junio_masc,0)+coalesce(julio_masc,0)+coalesce(agosto_masc,0)+coalesce(septiembre_masc,0)+coalesce(octubre_masc,0)+coalesce(noviembre_masc,0)+coalesce(diciembre_masc,0)+coalesce(enero_fem,0)+coalesce(febrero_fem,0)+coalesce(marzo_fem,0)+coalesce(abril_fem,0)+coalesce(mayo_fem,0)+coalesce(junio_fem,0)+coalesce(julio_fem,0)+coalesce(agosto_fem,0)+coalesce(septiembre_fem,0)+coalesce(octubre_fem,0)+coalesce(noviembre_fem,0)+coalesce(diciembre_fem,0)),000) 
			as Montopre,
			coalesce(sum(coalesce(enero_masc,0)+coalesce(febrero_masc,0)+coalesce(marzo_masc,0)+coalesce(enero_fem,0)+coalesce(febrero_fem,0)+coalesce(marzo_fem,0)),000) as trimestre1,
			coalesce(sum(coalesce(abril_masc,0)+coalesce(mayo_masc,0)+coalesce(junio_masc,0)+coalesce(abril_fem,0)+coalesce(mayo_fem,0)+coalesce(junio_fem,0)),000) as trimestre2,
			coalesce(sum(coalesce(julio_masc,0)+coalesce(agosto_masc,0)+coalesce(septiembre_masc,0)+coalesce(julio_fem,0)+coalesce(agosto_fem,0)+coalesce(septiembre_fem,0)),000) as trimestre3,
			coalesce(sum(coalesce(octubre_masc,0)+coalesce(noviembre_masc,0)+coalesce(diciembre_masc,0)+coalesce(octubre_fem,0)+coalesce(noviembre_fem,0)+coalesce(diciembre_fem,0)),000) as trimestre4 
			from spe_relacion_estvar as gastos
			inner join sig_variables on gastos.cod_var=sig_variables.cod_var 
			inner join spe_relacion_es as inte on gastos.codinte=inte.codinte inner join 
			spe_estpro4 as plan on inte.codest1=plan.codest1 and  
			inte.codest2=plan.codest2 and 
			inte.codest3=plan.codest3 and
			inte.codest4=plan.codest4 ";
			 if($this->codest1!="")
			 {
				$sql.=" and plan.codest1='{$this->codest1}'";	 
			 }
			 if($this->codest2!="")
			 {
				$sql.=" and plan.codest2='{$this->codest2}'";	 
			 }
			 if($this->codest3!="")
			 {
				$sql.=" and plan.codest3='{$this->codest3}'";	 
			 }
			 if($this->codest4!="")
			 {
				$sql.=" and plan.codest4='{$this->codest4}'";	 
			 }
			 if($this->codest5!="")
			 {
				$sql.=" and plan.codest5='{$this->codest5}'";	 
			 }
			 $sql.=" group by sig_variables.cod_var,sig_variables.denominacion";
		
		$Rs = $db->Execute($sql); 
		return $Rs;	 
	}

	
	public function Leerindiplan()
	{
		global $db;
		//$db->debug=true;
		$sql="select sig_indicador.cod_ind as codigo,sig_indicador.denominacion,coalesce(sum(coalesce(enero,0)+coalesce(febrero,0)+coalesce(marzo,0)+coalesce(abril,0)+coalesce(mayo,0)+coalesce(junio,0)+coalesce(julio,0)+coalesce(agosto,0)+coalesce(septiembre,0)+coalesce(octubre,0)+coalesce(noviembre,0)+coalesce(diciembre,0)),000) 
			as Montopre,
			coalesce(sum(coalesce(enero,0)+coalesce(febrero,0)+coalesce(marzo,0)),00) as trimestre1,
			coalesce(sum(coalesce(abril,0)+coalesce(mayo,0)+coalesce(junio,0)),00) as trimestre2,
			coalesce(sum(coalesce(julio,0)+coalesce(agosto,0)+coalesce(septiembre,0)),000) as trimestre3,
			coalesce(sum(coalesce(octubre,0)+coalesce(noviembre,0)+coalesce(diciembre,0)),000) as trimestre4 
			from spe_relacion_estindi as Indi
			inner join sig_indicador on Indi.cod_ind=sig_indicador.cod_ind 
			inner join spe_relacion_es as inte on Indi.codinte=inte.codinte inner join 
			spe_estpro4 as plan on inte.codest1=plan.codest1 and  
			inte.codest2=plan.codest2 and 
			inte.codest3=plan.codest3 and
			inte.codest4=plan.codest4 ";
			 if($this->codest1!="")
			 {
				$sql.=" and plan.codest1='{$this->codest1}'";	 
			 }
			 if($this->codest2!="")
			 {
				$sql.=" and plan.codest2='{$this->codest2}'";	 
			 }
			 if($this->codest3!="")
			 {
				$sql.=" and plan.codest3='{$this->codest3}'";	 
			 }
			 if($this->codest4!="")
			 {
				$sql.=" and plan.codest4='{$this->codest4}'";	 
			 }
			 if($this->codest5!="")
			 {
				$sql.=" and plan.codest5='{$this->codest5}'";	 
			 }
			
			 $sql.=" group by sig_indicador.cod_ind,sig_indicador.denominacion";
		
			 $Rs = $db->Execute($sql); 
		return $Rs;	 
	}
	
	

	public function Leergastosplan()
	{
		global $db;
		//$db->debug=true;
	
		$sql="select '{$this->grupo}' as codigo, coalesce(sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre),000) 
			as Montopre,
			coalesce(sum(enero+febrero+marzo),000) as trimestre1,
			coalesce(sum(abril+mayo+junio),000) as trimestre2,
			coalesce(sum(julio+agosto+septiembre),000) as trimestre3,
			coalesce(sum(octubre+noviembre+diciembre),000) as trimestre4,
			(select denominacion from sigesp_plan_unico_re 
			where sig_cuenta like '{$this->grupo}%' limit 1) as denominacion
			from spe_int_cuentas as gastos 
			inner join spe_relacion_es as inte on gastos.codinte=inte.codinte inner join 
			spe_estpro4 as plan on inte.codest1=plan.codest1 and  
			inte.codest2=plan.codest2 and 
			inte.codest3=plan.codest3 and
			inte.codest4=plan.codest4 
			where gastos.sig_cuenta like '{$this->grupo}%' 
			and gastos.codemp='{$this->codemp}'";
			 if($this->codest1!="")
			 {
				$sql.=" and plan.codest1='{$this->codest1}'";	 
			 }
			 if($this->codest2!="")
			 {
				$sql.=" and plan.codest2='{$this->codest2}'";	 
			 }
			 if($this->codest3!="")
			 {
				$sql.=" and plan.codest3='{$this->codest3}'";	 
			 }
			 if($this->codest4!="")
			 {
				$sql.=" and plan.codest4='{$this->codest4}'";	 
			 }
			 if($this->codest5!="")
			 {
				$sql.=" and plan.codest5='{$this->codest5}'";	 
			 }
			
		$Rs = $db->Execute($sql); 
		return $Rs;	 
	}
	
	
	
	public function leerTransfGastos()
	{
		$plancuentas = new planUnicoRe();
		$cantidad = $plancuentas->Cantdigitoscuentas();
		global $db;
		$sql0="select sigesp_plan_unico_re.sig_cuenta, denominacion,(spe_int_cuentas.enero+spe_int_cuentas.febrero+spe_int_cuentas.marzo+spe_int_cuentas.abril+spe_int_cuentas.mayo+spe_int_cuentas.junio+spe_int_cuentas.julio+spe_int_cuentas.agosto+spe_int_cuentas.septiembre+spe_int_cuentas.octubre+spe_int_cuentas.noviembre+spe_int_cuentas.diciembre) as montoglobal from sigesp_plan_unico_re inner join spe_int_cuentas on sigesp_plan_unico_re.sig_cuenta=substr(spe_int_cuentas.sig_cuenta,1,{$cantidad}) where spe_int_cuentas.sig_cuenta like '4050102%' and {$this->_table}.codemp='{$this->codemp}'";
		$Rs0 = $db->Execute($sql0);
		$sql1="select sigesp_plan_unico_re.sig_cuenta, denominacion,(spe_int_cuentas.enero+spe_int_cuentas.febrero+spe_int_cuentas.marzo+spe_int_cuentas.abril+spe_int_cuentas.mayo+spe_int_cuentas.junio+spe_int_cuentas.julio+spe_int_cuentas.agosto+spe_int_cuentas.septiembre+spe_int_cuentas.octubre+spe_int_cuentas.noviembre+spe_int_cuentas.diciembre) as montoglobal from sigesp_plan_unico_re inner join spe_int_cuentas on sigesp_plan_unico_re.sig_cuenta=substr(spe_int_cuentas.sig_cuenta,1,{$cantidad}) where spe_int_cuentas.sig_cuenta like '4070103%' and {$this->_table}.codemp='{$this->codemp}'";
		$Rs1 = $db->Execute($sql1);
		$sql2="select sigesp_plan_unico_re.sig_cuenta, denominacion,(spe_int_cuentas.enero+spe_int_cuentas.febrero+spe_int_cuentas.marzo+spe_int_cuentas.abril+spe_int_cuentas.mayo+spe_int_cuentas.junio+spe_int_cuentas.julio+spe_int_cuentas.agosto+spe_int_cuentas.septiembre+spe_int_cuentas.octubre+spe_int_cuentas.noviembre+spe_int_cuentas.diciembre) as montoglobal from sigesp_plan_unico_re inner join spe_int_cuentas on sigesp_plan_unico_re.sig_cuenta=substr(spe_int_cuentas.sig_cuenta,1,{$cantidad}) where spe_int_cuentas.sig_cuenta like '4070104%' and {$this->_table}.codemp='{$this->codemp}'";
		//echo $sql2;
		$Rs2 = $db->Execute($sql2);
		$sql3="select sigesp_plan_unico_re.sig_cuenta, denominacion,(spe_int_cuentas.enero+spe_int_cuentas.febrero+spe_int_cuentas.marzo+spe_int_cuentas.abril+spe_int_cuentas.mayo+spe_int_cuentas.junio+spe_int_cuentas.julio+spe_int_cuentas.agosto+spe_int_cuentas.septiembre+spe_int_cuentas.octubre+spe_int_cuentas.noviembre+spe_int_cuentas.diciembre) as montoglobal from sigesp_plan_unico_re inner join spe_int_cuentas on sigesp_plan_unico_re.sig_cuenta=substr(spe_int_cuentas.sig_cuenta,1,{$cantidad}) where spe_int_cuentas.sig_cuenta like '4070303%' and {$this->_table}.codemp='{$this->codemp}'";
		$Rs3 = $db->Execute($sql3);
		$sql4="select sigesp_plan_unico_re.sig_cuenta, denominacion,(spe_int_cuentas.enero+spe_int_cuentas.febrero+spe_int_cuentas.marzo+spe_int_cuentas.abril+spe_int_cuentas.mayo+spe_int_cuentas.junio+spe_int_cuentas.julio+spe_int_cuentas.agosto+spe_int_cuentas.septiembre+spe_int_cuentas.octubre+spe_int_cuentas.noviembre+spe_int_cuentas.diciembre) as montoglobal from sigesp_plan_unico_re inner join spe_int_cuentas on sigesp_plan_unico_re.sig_cuenta=substr(spe_int_cuentas.sig_cuenta,1,{$cantidad}) where spe_int_cuentas.sig_cuenta like '4070304%' and {$this->_table}.codemp='{$this->codemp}'";
		$Rs4 = $db->Execute($sql4);
		$sql5="select sum(spe_int_cuentas.enero+spe_int_cuentas.febrero+spe_int_cuentas.marzo+spe_int_cuentas.abril+spe_int_cuentas.mayo+spe_int_cuentas.junio+spe_int_cuentas.julio+spe_int_cuentas.agosto+spe_int_cuentas.septiembre+spe_int_cuentas.octubre+spe_int_cuentas.noviembre+spe_int_cuentas.diciembre) as suma from spe_int_cuentas where sig_cuenta like '4050102%' or sig_cuenta like '4070103%' or sig_cuenta like '4070104%' or sig_cuenta like '4070303%' or sig_cuenta like '4070304%' and {$this->_table}.codemp='{$this->codemp}'";
		$Rs5 = $db->Execute($sql5);
		$arrRs = array($Rs0,$Rs1,$Rs2,$Rs3,$Rs4,$Rs5);
		return $arrRs;
	}

	public function leerGastosPrivado()
	{
		$plancuentas = new planUnicoRe();
		$cantidad = $plancuentas->Cantdigitoscuentas();
		global $db;
		$sql1="select sigesp_plan_unico_re.sig_cuenta, denominacion,(spe_int_cuentas.enero+spe_int_cuentas.febrero+spe_int_cuentas.marzo+spe_int_cuentas.abril+spe_int_cuentas.mayo+spe_int_cuentas.junio+spe_int_cuentas.julio+spe_int_cuentas.agosto+spe_int_cuentas.septiembre+spe_int_cuentas.octubre+spe_int_cuentas.noviembre+spe_int_cuentas.diciembre) as montoglobal from sigesp_plan_unico_re inner join spe_int_cuentas on sigesp_plan_unico_re.sig_cuenta=substr(spe_int_cuentas.sig_cuenta,1,{$cantidad}) where spe_int_cuentas.sig_cuenta like '4070101%' and {$this->_table}.codemp='{$this->codemp}'";
		$Rs1 = $db->Execute($sql1);
		$sql2="select sigesp_plan_unico_re.sig_cuenta, denominacion,(spe_int_cuentas.enero+spe_int_cuentas.febrero+spe_int_cuentas.marzo+spe_int_cuentas.abril+spe_int_cuentas.mayo+spe_int_cuentas.junio+spe_int_cuentas.julio+spe_int_cuentas.agosto+spe_int_cuentas.septiembre+spe_int_cuentas.octubre+spe_int_cuentas.noviembre+spe_int_cuentas.diciembre) as montoglobal from sigesp_plan_unico_re inner join spe_int_cuentas on sigesp_plan_unico_re.sig_cuenta=substr(spe_int_cuentas.sig_cuenta,1,{$cantidad}) where spe_int_cuentas.sig_cuenta like '4070102%' and {$this->_table}.codemp='{$this->codemp}'";
		$Rs2 = $db->Execute($sql2);
		$sql3="select sigesp_plan_unico_re.sig_cuenta, denominacion,(spe_int_cuentas.enero+spe_int_cuentas.febrero+spe_int_cuentas.marzo+spe_int_cuentas.abril+spe_int_cuentas.mayo+spe_int_cuentas.junio+spe_int_cuentas.julio+spe_int_cuentas.agosto+spe_int_cuentas.septiembre+spe_int_cuentas.octubre+spe_int_cuentas.noviembre+spe_int_cuentas.diciembre) as montoglobal from sigesp_plan_unico_re inner join spe_int_cuentas on sigesp_plan_unico_re.sig_cuenta=substr(spe_int_cuentas.sig_cuenta,1,{$cantidad}) where spe_int_cuentas.sig_cuenta like '4070301%' and {$this->_table}.codemp='{$this->codemp}'";
		$Rs3 = $db->Execute($sql3);
		$sql4="select sigesp_plan_unico_re.sig_cuenta, denominacion,(spe_int_cuentas.enero+spe_int_cuentas.febrero+spe_int_cuentas.marzo+spe_int_cuentas.abril+spe_int_cuentas.mayo+spe_int_cuentas.junio+spe_int_cuentas.julio+spe_int_cuentas.agosto+spe_int_cuentas.septiembre+spe_int_cuentas.octubre+spe_int_cuentas.noviembre+spe_int_cuentas.diciembre) as montoglobal from sigesp_plan_unico_re inner join spe_int_cuentas on sigesp_plan_unico_re.sig_cuenta=substr(spe_int_cuentas.sig_cuenta,1,{$cantidad}) where spe_int_cuentas.sig_cuenta like '4070302%' and {$this->_table}.codemp='{$this->codemp}'";
		$Rs4 = $db->Execute($sql4);
		$sql5="select sum(spe_int_cuentas.enero+spe_int_cuentas.febrero+spe_int_cuentas.marzo+spe_int_cuentas.abril+spe_int_cuentas.mayo+spe_int_cuentas.junio+spe_int_cuentas.julio+spe_int_cuentas.agosto+spe_int_cuentas.septiembre+spe_int_cuentas.octubre+spe_int_cuentas.noviembre+spe_int_cuentas.diciembre) as suma from spe_int_cuentas where sig_cuenta like '4070101%' or sig_cuenta like '4070102%' or sig_cuenta like '4070301%' or sig_cuenta like '4070302%' and {$this->_table}.codemp='{$this->codemp}'";
		$Rs5 = $db->Execute($sql5);
		$arrRs = array($Rs1,$Rs2,$Rs3,$Rs4,$Rs5);
		return $arrRs;
	}
	
	public function LeerCuentasTrimestre()
	{
		global $db;
		//$db->debug=true;
		$cuentasinceros=uf_spg_cuenta_sin_cero(trim($this->sig_cuenta));
		if($cuentasinceros=="")
		{
			$cuentasinceros=trim($this->sig_cuenta);
		}
		$oEmpresa = new Empresa();
		$Rs = $oEmpresa->LeerFormatoCuentas();
		$arrPosiciones = split("-",$Rs->fields["formpre"]);		
		$numPosiciones = count($arrPosiciones);
		$pos1= strlen($arrPosiciones[0]);
		$pos2= $pos1+1;
		$pos3=$pos2+2;
		$pos4=$pos3+2; 
	//	if($numPosiciones=="5")
	//	{
			$sql="select (select denominacion from sigesp_plan_unico_re where sig_cuenta = '{$this->sig_cuenta}') as denominacion, (select substr(sigesp_plan_unico_re.sig_cuenta,1,".strlen($arrPosiciones[0]).") from sigesp_plan_unico_re where sig_cuenta = '{$this->sig_cuenta}') as partida,(select substr(sigesp_plan_unico_re.sig_cuenta,".$pos2.",".strlen($arrPosiciones[1]).") from sigesp_plan_unico_re where sig_cuenta = '{$this->sig_cuenta}') as generica,(select substr(sigesp_plan_unico_re.sig_cuenta,".$pos3.",".strlen($arrPosiciones[2]).") from sigesp_plan_unico_re where sig_cuenta = '{$this->sig_cuenta}') as especifica, (select substr(sigesp_plan_unico_re.sig_cuenta,".$pos4.",".strlen($arrPosiciones[3]).") from sigesp_plan_unico_re where sig_cuenta = '{$this->sig_cuenta}') as subespecifica,
				  sum(sigesp_sfp_plancuentas.monto_anest) as monto_anest,sum(sigesp_sfp_plancuentas.monto_anreal) as monto_anreal, COALESCE(sum(enero),0000) as enero,COALESCE(sum(febrero),000)as febrero,COALESCE(sum(marzo),000) as marzo , COALESCE(sum(abril),000) as abril,COALESCE(sum(mayo),000)as mayo,COALESCE(sum(junio),000) as junio,
				 COALESCE(sum(julio),0000)as julio ,COALESCE(sum(agosto),000) as agosto,COALESCE(sum(septiembre),000) as septiembre, COALESCE(sum(octubre),000) as octubre,COALESCE(sum(noviembre),000) as noviembre,COALESCE(sum(diciembre),000) as diciembre,
				 COALESCE(sum((enero+febrero)),0000) as bimestre1,COALESCE(sum((marzo+abril)),000) as bimestre2,COALESCE(sum((mayo+junio)),000) as bimestre3, COALESCE(sum((julio+agosto)),000) as bimestre4,COALESCE(sum((septiembre+octubre)),000) as bimestre5,COALESCE(sum((noviembre+diciembre)),000) as bimestre6,
				 COALESCE(sum((enero+febrero+marzo)),0000) as trimestre1,COALESCE(sum((abril+mayo+junio)),000) as trimestre2,COALESCE(sum((julio+agosto+septiembre)),000) as trimestre3, COALESCE(sum((octubre+noviembre+diciembre)),000) as trimestre4,
				 COALESCE(sum((enero+febrero+marzo+abril+mayo+junio)),0000) as semestre1, COALESCE(sum((julio+agosto+septiembre+octubre+noviembre+diciembre)),000) as semestre4,
				 COALESCE(sum((spe_int_cuentas.enero+spe_int_cuentas.febrero+spe_int_cuentas.marzo+spe_int_cuentas.abril+spe_int_cuentas.mayo+spe_int_cuentas.junio+spe_int_cuentas.julio+spe_int_cuentas.agosto+spe_int_cuentas.septiembre+spe_int_cuentas.octubre+spe_int_cuentas.noviembre+spe_int_cuentas.diciembre)),000) as montoglobalgas 
				 from spe_int_cuentas inner join sigesp_plan_unico_re on spe_int_cuentas.sig_cuenta = sigesp_plan_unico_re.sig_cuenta inner join sigesp_sfp_plancuentas on spe_int_cuentas.sig_cuenta=sigesp_sfp_plancuentas.sig_cuenta
				  where spe_int_cuentas.codemp = '{$this->codemp}' and spe_int_cuentas.ano_presupuesto={$this->ano_presupuesto} and sigesp_plan_unico_re.sig_cuenta like '{$cuentasinceros}%' ";
		
			//die();
			$Rs=$db->Execute($sql);
			if($Rs->RecordCount()==0)
			{
				return false;
			}
			else
			{
				return $Rs;			
			}
	//	}
	
	}
public function repgastos_aplic()		
{	
		$la_cuenta[5]=array();
		$la_cuenta[0]["cuenta"]='401000000'.$ls_ceros;
		$la_cuenta[1]["cuenta"]='402000000'.$ls_ceros;
		$la_cuenta[2]["cuenta"]='403000000'.$ls_ceros;
		$la_cuenta[3]["cuenta"]='404000000'.$ls_ceros;
		$la_cuenta[4]["cuenta"]='405000000'.$ls_ceros;
		$la_cuenta[5]["cuenta"]='407000000'.$ls_ceros;
		$la_cuenta[6]["cuenta"]='408000000'.$ls_ceros;
		$la_cuenta[7]["cuenta"]='411000000'.$ls_ceros;

		$datastore1=array();
	    $id1=0;
	
	    for($i=0;$i<count($la_cuenta);$i++)
	    {
	     	if($i>0 && $i<=5)
  			{					 	//echo $i;
				$oCuenta1 =  new planUnicoRe();
				$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["cuenta"]);
				$oCuenta1->sig_cuenta = $cuentasinceros;
				$oCuenta1->sig_cuentacomp = $la_cuenta[$i]["cuenta"];
				$rsGrupo = $oCuenta1->LeerGrupo();
				while($rec =$rsGrupo->FetchRow())
				{
					$oCuenta= new intGastosDao();
					$oCuenta->sig_cuenta = trim($rec["sig_cuenta"]);
					$oCuenta->nivelCuenta=$this->nivel;	
					$resp = $oCuenta->Verificar();
					
					if($resp!=false)
					{
						
						$resp = $oCuenta->LeerCuentasTrimestre();
						if($resp!=false)
						{		  						
							$datastore1[$id1]=$resp;	
					  		$id1++;
						}
					}
				}
  			}
	    }
	  //  ver($datastore1);
	    $rsTotal = $this->LeerSaldoTotEgresos();
	    $Datos["cuentas"]=$datastore1;
	    $Datos["totales"]=$rsTotal;
	    return $Datos;
	    
		
}
	public function LeerCuentasxPartidas()
	{
		global $db;
		//$db->debug=true;
	
		
	/*	
		$sql1 = "select sfp_estpro1.codestpro1,sfp_estpro1.denestpro1,coalesce((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from spe_int_cuentas inner join spe_relacion_es on spe_int_cuentas.codinte=spe_relacion_es.codinte 
where spe_int_cuentas.sig_cuenta like '401%'),0000) as cta401,coalesce((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from spe_int_cuentas inner join spe_relacion_es on spe_int_cuentas.codinte=spe_relacion_es.codinte where spe_int_cuentas.sig_cuenta like '402%'),0000) as cta402, 
coalesce((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from spe_int_cuentas inner join spe_relacion_es on spe_int_cuentas.codinte=spe_relacion_es.codinte where spe_int_cuentas.sig_cuenta like '403%'),0000) as cta403,
coalesce((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from spe_int_cuentas inner join spe_relacion_es on spe_int_cuentas.codinte=spe_relacion_es.codinte where spe_int_cuentas.sig_cuenta like '404%'),0000) as cta404,
coalesce((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from spe_int_cuentas inner join spe_relacion_es on spe_int_cuentas.codinte=spe_relacion_es.codinte where spe_int_cuentas.sig_cuenta like '405%'),0000) as cta405,
coalesce((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from spe_int_cuentas inner join spe_relacion_es on spe_int_cuentas.codinte=spe_relacion_es.codinte where spe_int_cuentas.sig_cuenta like '407%'),0000) as cta407,
coalesce((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from spe_int_cuentas inner join spe_relacion_es on spe_int_cuentas.codinte=spe_relacion_es.codinte where spe_int_cuentas.sig_cuenta like '408%'),0000) as cta408
from sfp_estpro1 inner join spe_relacion_es on sfp_estpro1.codestpro1=spe_relacion_es.codestpro1 where sfp_estpro1.estcla='P' and {$this->_table}.codemp='{$this->codemp}'";
		//echo $sql1;
		//die();
		$Rs1=$db->Execute($sql1);

		
		$sql2 = "select sfp_estpro1.codestpro1,sfp_estpro1.denestpro1,coalesce((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from spe_int_cuentas inner join spe_relacion_es on spe_int_cuentas.codinte=spe_relacion_es.codinte 
where spe_int_cuentas.sig_cuenta like '401%'),0000) as cta401,coalesce((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from spe_int_cuentas inner join spe_relacion_es on spe_int_cuentas.codinte=spe_relacion_es.codinte where spe_int_cuentas.sig_cuenta like '402%'),0000) as cta402, 
coalesce((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from spe_int_cuentas inner join spe_relacion_es on spe_int_cuentas.codinte=spe_relacion_es.codinte where spe_int_cuentas.sig_cuenta like '403%'),0000) as cta403,
coalesce((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from spe_int_cuentas inner join spe_relacion_es on spe_int_cuentas.codinte=spe_relacion_es.codinte where spe_int_cuentas.sig_cuenta like '404%'),0000) as cta404,
coalesce((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from spe_int_cuentas inner join spe_relacion_es on spe_int_cuentas.codinte=spe_relacion_es.codinte where spe_int_cuentas.sig_cuenta like '405%'),0000) as cta405,
coalesce((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from spe_int_cuentas inner join spe_relacion_es on spe_int_cuentas.codinte=spe_relacion_es.codinte where spe_int_cuentas.sig_cuenta like '407%'),0000) as cta407,
coalesce((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from spe_int_cuentas inner join spe_relacion_es on spe_int_cuentas.codinte=spe_relacion_es.codinte where spe_int_cuentas.sig_cuenta like '408%'),0000) as cta408,
coalesce((select sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) from spe_int_cuentas inner join spe_relacion_es on spe_int_cuentas.codinte=spe_relacion_es.codinte where spe_int_cuentas.sig_cuenta like '411%'),0000) as cta411
from sfp_estpro1 inner join spe_relacion_es on sfp_estpro1.codestpro1=spe_relacion_es.codestpro1 where sfp_estpro1.estcla='A' and {$this->_table}.codemp='{$this->codemp}'";
		
*/
		$Rs2=$db->Execute($sql2);
		$arrRs = array($Rs1,$Rs2);
		return $arrRs;
	}
	
	
public function LeerDigitosNiveles()
{
	$oEmpresa = new Empresa();
	$Rs = $oEmpresa->LeerFormatoCuentas();
	$arrPosiciones = split("-",$Rs->fields["formpre"]);		
	$numPosiciones = count($arrPosiciones);
	$pos1= strlen($arrPosiciones[0]);
	$pos2= $pos1+1;
	$pos3=$pos2+2;
	$pos4=$pos3+2; 
	$anterior=0;
	for($i=1;$i<=$numPosiciones;$i++)
	{
		$arNiveles[$i]=$anterior+strlen($arrPosiciones[$i-1]);
		$anterior=$arNiveles[$i];
	}
	return $arNiveles;
}



public function Verificar()
{
	$arr = $this->LeerDigitosNiveles();
	$cuentasincero=uf_spg_cuenta_sin_cero($this->sig_cuenta);
	$oCuenta1 =  new planUnicoRe();
	$oCuenta1->cuenta = $cuentasincero;
	if($oCuenta1->TieneHijas())
	{
		$cantidadDigitos=strlen($cuentasincero);
	}
	else
	{
		
		$cantidadDigitos=strlen(trim($this->sig_cuenta));
	}
	//var_dump($cantidadDigitos);
	//die();
	$arrAnteriores=array();
	foreach($arr as $nivel=>$numerodedigitos)
	{
		if($numerodedigitos==$cantidadDigitos)
		{
			
			
			for($i=1;$i<$this->nivelCuenta;$i++)
			{
				$arrAnteriores[$i]=$i;	
			}
		
			$niveldelacuenta= $nivel;
			break;
		}
	}

	if($niveldelacuenta==$this->nivelCuenta or array_search($niveldelacuenta,$arrAnteriores))
	{
		return true;
	}
	else
	{
		return false;
	}
	
}
	
	
public function tieneMovimiento($grupoCuenta)
{
		$valido=true;
		global $db;
		$sql="select * from spe_int_cuentas where sig_cuenta like '$grupoCuenta%' and codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}'";
		$rs=$db->Execute($sql);
		if($rs->RecordCount()>0)
		{
			$Valido=true;
		}
		else
		{
			$Valido=false;
		}
		if($Valido==false)
		{
			$sql="select sum(monto_anreal) as anreal, sum(monto_anest) as anest from sigesp_sfp_plancuentas where sig_cuenta like '{$grupoCuenta}%' and codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}'";
			$rs=$db->Execute($sql);
			if($rs->fields["anreal"]>0 || $rs->fields["anest"]>0)
			{
				$Valido=true;
			}
			else
			{
				$Valido=false;
			}
		}
		return $Valido;
}
	

public function tieneMovimiento2($grupoCuenta)
{
		$Valido=false;
		global $db;
		$sql="select * from spe_int_cuentas where sig_cuenta like '{$grupoCuenta}%' and nat_gasto='{$this->nat_gasto}' and codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}'";
		$rs=$db->Execute($sql);
		if($rs->RecordCount()>0)
		{
			$Valido=true;
		}
		else
		{
			$sql="select sigesp_sfp_asociacion.codcaif,sigesp_plan_unico_caif.desplacaif,sum(sfp_dt_cmp_variacion.monto) 
				from sigesp_sfp_asociacion 
				inner join sfp_dt_cmp_variacion on sigesp_sfp_asociacion.codcaif=sfp_dt_cmp_variacion.sig_cuenta
				inner join sigesp_plan_unico_caif on sigesp_sfp_asociacion.codcaif=sigesp_plan_unico_caif.codplacaif
				where sfp_dt_cmp_variacion.codemp='{$this->codemp}'  and sigesp_sfp_asociacion.sig_cuenta like '{$grupoCuenta}%'
				group by  sigesp_sfp_asociacion.codcaif,sigesp_plan_unico_caif.desplacaif";
			$rs=$db->Execute($sql);
			if($rs->RecordCount()>0)
			{
				$Valido=true;
			}
			else
			{
				$Valido=false;
			}
		}
		return $Valido;
}


	function LeerSaldo()
	{
		global $db;
		$cuentasinceros=uf_spg_cuenta_sin_cero(trim($this->sig_cuenta));
		if($cuentasinceros=="")
		{
			$cuentasinceros=trim($this->sig_cuenta);
		}
			
		if($this->tieneMovimiento($cuentasinceros)==true)
		{
		   $sql="select (select denominacion from sigesp_plan_unico_re where sig_cuenta='{$this->sig_cuenta}') as denominaciongas,COALESCE((select sum(monto_anest) from sigesp_sfp_plancuentas where sig_cuenta like '{$cuentasinceros}%'),0000) as anestimadogas ,COALESCE((select sum(monto_anreal)  from sigesp_sfp_plancuentas where sig_cuenta like '{$cuentasinceros}%'),0000) as anrealgas,$this->sig_cuenta as codcuentagas, 
				 COALESCE(sum(enero),0000) as enero,COALESCE(sum(febrero),000)as febrero,COALESCE(sum(marzo),000) as marzo , COALESCE(sum(abril),000) as abril,COALESCE(sum(mayo),000)as mayo,COALESCE(sum(junio),000) as junio,
				 COALESCE(sum(julio),0000)as julio ,COALESCE(sum(agosto),000) as agosto,COALESCE(sum(septiembre),000) as septiembre, COALESCE(sum(octubre),000) as octubre,COALESCE(sum(noviembre),000) as noviembre,COALESCE(sum(diciembre),000) as diciembre,
				 COALESCE(sum((enero+febrero)),0000) as bimestre1,COALESCE(sum((marzo+abril)),000) as bimestre2,COALESCE(sum((mayo+junio)),000) as bimestre3, COALESCE(sum((julio+agosto)),000) as bimestre4,COALESCE(sum((septiembre+octubre)),000) as bimestre5,COALESCE(sum((noviembre+diciembre)),000) as bimestre6,
				 COALESCE(sum((enero+febrero+marzo)),0000) as trimestre1,COALESCE(sum((abril+mayo+junio)),000) as trimestre2,COALESCE(sum((julio+agosto+septiembre)),000) as trimestre3, COALESCE(sum((octubre+noviembre+diciembre)),000) as trimestre4,
				 COALESCE(sum((enero+febrero+marzo+abril+mayo+junio)),0000) as semestre1, COALESCE(sum((julio+agosto+septiembre+octubre+noviembre+diciembre)),000) as semestre4,
				 COALESCE(sum((spe_int_cuentas.enero+spe_int_cuentas.febrero+spe_int_cuentas.marzo+spe_int_cuentas.abril+spe_int_cuentas.mayo+spe_int_cuentas.junio+spe_int_cuentas.julio+spe_int_cuentas.agosto+spe_int_cuentas.septiembre+spe_int_cuentas.octubre+spe_int_cuentas.noviembre+spe_int_cuentas.diciembre)),000) as montoglobalgas 
				 from  {$this->_table} inner join sigesp_plan_unico_re on {$this->_table}.sig_cuenta=sigesp_plan_unico_re.sig_cuenta where codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}' and spe_int_cuentas.sig_cuenta like '{$cuentasinceros}%'";
			
			$rs =$db->Execute($sql);
			if($rs==true)
			{
				return $rs;	
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	function LeerSaldocaif()
	{
		global $db;
		$plancuentas = new planUnicoRe();
		$cantidad = $plancuentas->Cantdigitoscuentas();
		$this->sig_cuenta = str_pad($this->sig_cuenta,$cantidad,"0");
		$cuentasinceros=uf_spg_cuenta_sin_cero($this->sig_cuenta);
		if($cuentasinceros=="")
		{
			$cuentasinceros=$this->sig_cuenta;
		}
			$sql="select (select denominacion from sigesp_plan_unico_re where sig_cuenta='{$this->sig_cuenta}') as denominaciongas, (select codcaif from sigesp_sfp_asociacion where sig_cuenta='{$this->sig_cuenta}') as codcaifgas,
				  COALESCE(sum((spe_int_cuentas.enero+spe_int_cuentas.febrero+spe_int_cuentas.marzo+spe_int_cuentas.abril+spe_int_cuentas.mayo+spe_int_cuentas.junio+spe_int_cuentas.julio+spe_int_cuentas.agosto+spe_int_cuentas.septiembre+spe_int_cuentas.octubre+spe_int_cuentas.noviembre+spe_int_cuentas.diciembre)),000) as montoglobalgas 
				  from  {$this->_table}  where codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}'
				  and nat_gasto='{$this->nat_gasto}' and spe_int_cuentas.sig_cuenta like '{$cuentasinceros}%'";
			$rs =$db->Execute($sql);
			if($rs==true)
			{
				return $rs;	
			}
			else
			{
				return false;
			}
	}

	public function LeerSaldoAplicFinan()
	{
		
		global $db;
		$plancuentas = new planUnicoRe();
		$cantidad = $plancuentas->Cantdigitoscuentas();
		$this->sig_cuenta = str_pad($this->sig_cuenta,$cantidad,"0");
		$cuentasinceros=uf_spg_cuenta_sin_cero($this->sig_cuenta);
		if($cuentasinceros=="")
		{
			$cuentasinceros=$this->sig_cuenta;
		}
		$ovariacion = new AsientoVariacionDao();
		$ovariacion->sig_cuenta=$cuentasinceros;
		$ovariacion->ano_presupuesto=$this->ano_presupuesto;
		$totalvariacion=$ovariacion->LeerSaldoVariacion('D');
		$sql="select (select denominacion from sigesp_plan_unico_re where sig_cuenta='{$this->sig_cuenta}') as denominaciongas, (select codcaif from sigesp_sfp_asociacion where sig_cuenta='{$this->sig_cuenta}') as codcaifgas,
			  COALESCE(sum((spe_int_cuentas.enero+spe_int_cuentas.febrero+spe_int_cuentas.marzo+spe_int_cuentas.abril+spe_int_cuentas.mayo+spe_int_cuentas.junio+spe_int_cuentas.julio+spe_int_cuentas.agosto+spe_int_cuentas.septiembre
			  +spe_int_cuentas.octubre+spe_int_cuentas.noviembre+spe_int_cuentas.diciembre)),000)+($totalvariacion) as montoglobalgas 
			  from  {$this->_table}  where codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}'
			  and nat_gasto='{$this->nat_gasto}' and spe_int_cuentas.sig_cuenta like '{$cuentasinceros}%'";
			$rs =$db->Execute($sql);
			if($rs==true)
			{
				return $rs;	
			}
			else
			{
				return false;
			}
	}
	
	public function LeerSaldocaifInversion()
	{
		global $db;
		$cuentasinceros=uf_spg_cuenta_sin_cero($this->sig_cuenta);
		if($cuentasinceros=="")
		{
			$cuentasinceros=$this->sig_cuenta;
		}
		
			$ovariacion = new AsientoVariacionDao();
			$ovariacion->sig_cuenta=$cuentasinceros;
			$totalvariacion=$ovariacion->LeerSaldoVariacion();
			$sql="select (select denominacion from sigesp_plan_unico_re where sig_cuenta='{$this->sig_cuenta}') as denominaciongas, (select codcaif from sigesp_plan_unico_re where sig_cuenta='{$this->sig_cuenta}') as codcaifgas,
				  COALESCE(sum((spe_int_cuentas.enero+spe_int_cuentas.febrero+spe_int_cuentas.marzo+spe_int_cuentas.abril+spe_int_cuentas.mayo+spe_int_cuentas.junio+spe_int_cuentas.julio+spe_int_cuentas.agosto+spe_int_cuentas.septiembre+spe_int_cuentas.octubre+spe_int_cuentas.noviembre+spe_int_cuentas.diciembre)),000) as montoglobalgas 
				  from  {$this->_table} inner join sigesp_plan_unico_re on {$this->_table}.sig_cuenta=sigesp_plan_unico_re.sig_cuenta where codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}' and spe_int_cuentas.sig_cuenta like '{$cuentasinceros}%'";
			
			$rs =$db->Execute($sql);
			if($rs==true)
			{
				return $rs;	
			}
			else
			{
				return false;
			}

	}
	
	function Leertotgascorrientes()
	{
		global $db;
		$cuentasinceros=uf_spg_cuenta_sin_cero($this->sig_cuenta);
		if($cuentasinceros=="")
		{
			$cuentasinceros=$this->sig_cuenta;
		}
			
		//if($this->tieneMovimiento($cuentasinceros)==true)
		//{
		$sql="select COALESCE(sum((enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre)),000) as montoglobalgas 
			  from  {$this->_table} where codemp='{$this->codemp}' and ano_presupuesto={$this->ano_presupuesto} and nat_gasto='co' and sig_cuenta not like '411%' and sig_cuenta not like '405%'";
			
			// ver($sql);
			$rs =$db->Execute($sql);
			if($rs==true)
			{
				return $rs->fields["montoglobalgas"];	
			}
			else
			{
				return false;
			}
		//}
		//else
		//{
		//	return false;
		//}
	}
	

	function Leertotgascapital()
	{
		global $db;
		//$db->debug=true;
		$cuentasinceros=uf_spg_cuenta_sin_cero($this->sig_cuenta);
		if($cuentasinceros=="")
		{
			$cuentasinceros=$this->sig_cuenta;
		}
			
		//if($this->tieneMovimiento($cuentasinceros)==true)
		//{
		$sql="select COALESCE(sum((enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre)),000) as montoglobalgas 
			  from  {$this->_table} where codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}' and nat_gasto='ca'";
			
			$rs =$db->Execute($sql);
			if($rs==true)
			{
				return $rs->fields["montoglobalgas"];	
			}
			else
			{
				return false;
			}
		//}
		//else
		//{
		//	return false;
		//}
	}
	
	
public function LeerResFinGas()
{
	global $db;	
	//$db->debug=true;
	$ovariacion = new AsientoVariacionDao();
	$ovariacion->sig_cuenta=$cuentasinceros;
	$ovariacion->ano_presupuesto=$this->ano_presupuesto;
	$totalvariacion=$ovariacion->LeerSaldoGastos();
	
	$sql=" select (select COALESCE(sum((enero+febrero+marzo+abril+
		mayo+junio+julio+agosto+septiembre+octubre
		+noviembre+diciembre)),000) as montoglobalgas 
		 from  {$this->_table} where codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}' 
		 and  ({$this->_table}.sig_cuenta like '411%' or {$this->_table}.sig_cuenta like '405%' or {$this->_table}.sig_cuenta like '412%'))+({$totalvariacion}) as montoresfinanIng";
		$rs =$db->Execute($sql);
	//	ver($sql);
		if($rs==true)
		{
			return $rs->fields["montoresfinaning"];	
		}
		else
		{
			return false;
		}
}	
	
	
	
	function LeerSaldoEgEstRes()
	{
		global $db;	
		//$db->debug=true;
		$sql="select COALESCE((select sum(monto_anest) from sigesp_sfp_plancuentas where sig_cuenta like '401%' or sig_cuenta like '402%' or sig_cuenta like '403%' or sig_cuenta like '407%' or sig_cuenta like '408%'),0000) as anestimadogas ,COALESCE((select sum(monto_anreal) from sigesp_sfp_plancuentas where sig_cuenta like '401%' or sig_cuenta like '402%' or sig_cuenta like '403%' or sig_cuenta like '407%' or sig_cuenta like '408%'),0000) as anrealgas, COALESCE(sum(enero),0000) as enero,COALESCE(sum(febrero),000)as febrero,COALESCE(sum(marzo),000) as marzo , COALESCE(sum(abril),000) as abril,COALESCE(sum(mayo),000)as mayo,COALESCE(sum(junio),000) as junio,
			 COALESCE(sum(julio),0000)as julio ,COALESCE(sum(agosto),000) as agosto,COALESCE(sum(septiembre),000) as septiembre, COALESCE(sum(octubre),000) as octubre,COALESCE(sum(noviembre),000) as noviembre,COALESCE(sum(diciembre),000) as diciembre,
			 COALESCE(sum((enero+febrero)),0000) as bimestre1,COALESCE(sum((marzo+abril)),000) as bimestre2,COALESCE(sum((mayo+junio)),000) as bimestre3, COALESCE(sum((julio+agosto)),000) as bimestre4,COALESCE(sum((septiembre+octubre)),000) as bimestre5,COALESCE(sum((noviembre+diciembre)),000) as bimestre6,
			 COALESCE(sum((enero+febrero+marzo)),0000) as montogastri1,COALESCE(sum((abril+mayo+junio)),000) as montogastri2,COALESCE(sum((julio+agosto+septiembre)),000) as montogastri3, COALESCE(sum((octubre+noviembre+diciembre)),000) as montogastri4,
			 COALESCE(sum((enero+febrero+marzo+abril+mayo+junio)),0000) as semestre1, COALESCE(sum((julio+agosto+septiembre+octubre+noviembre+diciembre)),000) as semestre2,
			 COALESCE(sum((spe_int_cuentas.enero+spe_int_cuentas.febrero+spe_int_cuentas.marzo+spe_int_cuentas.abril+spe_int_cuentas.mayo+spe_int_cuentas.junio+spe_int_cuentas.julio+spe_int_cuentas.agosto+spe_int_cuentas.septiembre+spe_int_cuentas.octubre+spe_int_cuentas.noviembre+spe_int_cuentas.diciembre)),000) as montoglobalgas 
		  	 from  {$this->_table} where codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}' and ({$this->_table}.sig_cuenta like '401%' or {$this->_table}.sig_cuenta like '402%' or {$this->_table}.sig_cuenta like '403%' or {$this->_table}.sig_cuenta like '407%' or {$this->_table}.sig_cuenta like '40806%' or {$this->_table}.sig_cuenta like '40802%' or {$this->_table}.sig_cuenta like '40803%' or {$this->_table}.sig_cuenta like '40808%' or {$this->_table}.sig_cuenta like '40899%' or {$this->_table}.sig_cuenta like '40801%')";
		
		$rs =$db->Execute($sql);
		if($rs==true)
		{
			return $rs;	
		}
		else
		{
			return false;
		}
	}

	function LeerSaldoEgEstRes2()
	{
		global $db;	
		//$db->debug=true;
		$sql="select COALESCE((select sum(monto_anest) from sigesp_sfp_plancuentas where sig_cuenta like '401%' or sig_cuenta like '402%' or sig_cuenta like '403%' or sig_cuenta like '407%' or sig_cuenta like '404%' or sig_cuenta like '405%'),0000) as anestimadogas ,COALESCE((select sum(monto_anreal) from sigesp_sfp_plancuentas where sig_cuenta like '401%' or sig_cuenta like '402%' or sig_cuenta like '403%' or sig_cuenta like '407%' or sig_cuenta like '404%' or sig_cuenta like '405%'),0000) as anrealgas, COALESCE(sum((enero+febrero+marzo)),0000) as trimestre1gas,COALESCE(sum((abril+mayo+junio)),000) as trimestre2gas,COALESCE(sum((julio+agosto+septiembre)),000) as trimestre3gas, COALESCE(sum((octubre+noviembre+diciembre)),000) as trimestre4gas,
			  COALESCE(sum(enero),0000) as enerog,COALESCE(sum(febrero),000)as febrerog,COALESCE(sum(marzo),000) as marzog , COALESCE(sum(abril),000) as abrilg,COALESCE(sum(mayo),000)as mayog,COALESCE(sum(junio),000) as juniog,
			  COALESCE(sum(julio),0000)as juliog ,COALESCE(sum(agosto),000) as agostog,COALESCE(sum(septiembre),000) as septiembreg, COALESCE(sum(octubre),000) as octubreg,COALESCE(sum(noviembre),000) as noviembreg,COALESCE(sum(diciembre),000) as diciembreg,
			  COALESCE(sum((enero+febrero)),0000) as bimestre1g,COALESCE(sum((marzo+abril)),000) as bimestre2g,COALESCE(sum((mayo+junio)),000) as bimestre3g, COALESCE(sum((julio+agosto)),000) as bimestre4g,COALESCE(sum((septiembre+octubre)),000) as bimestre5g,COALESCE(sum((noviembre+diciembre)),000) as bimestre6g,	
			   COALESCE(sum((enero+febrero+marzo+abril+mayo+junio)),0000) as semestre1g, COALESCE(sum((julio+agosto+septiembre+octubre+noviembre+diciembre)),000) as semestre2g,
			  COALESCE(sum((enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre)),000) as montoglobalgas 
			  from  {$this->_table} where codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}' and ({$this->_table}.sig_cuenta like '401%' or {$this->_table}.sig_cuenta like '402%' or {$this->_table}.sig_cuenta like '403%' or {$this->_table}.sig_cuenta like '407%' or {$this->_table}.sig_cuenta like '404%' or {$this->_table}.sig_cuenta like '405%' or {$this->_table}.sig_cuenta like '411%' or {$this->_table}.sig_cuenta like '40806%' or {$this->_table}.sig_cuenta like '40802%' or {$this->_table}.sig_cuenta like '40803%' or {$this->_table}.sig_cuenta like '40808%' or {$this->_table}.sig_cuenta like '40899%')";
		//ver($sql);
		$rs =$db->Execute($sql);
		if($rs==true)
		{
			return $rs;	
		}
		else
		{
			return false;
		}
	}
	
	function LeerSaldoTotEgresos()
	{
		global $db;	
		//$db->debug=true;
		$sql="select COALESCE((select sum(monto_anest) from sigesp_sfp_plancuentas where sig_cuenta like '401%' or sig_cuenta like '402%' or sig_cuenta like '403%' or sig_cuenta like '407%' or sig_cuenta like '404%' or sig_cuenta like '405%' or sig_cuenta like '408%' or sig_cuenta like '411%'),0000) as anestimadogas ,COALESCE((select sum(monto_anreal) from sigesp_sfp_plancuentas where sig_cuenta like '401%' or sig_cuenta like '402%' or sig_cuenta like '403%' or sig_cuenta like '407%' or sig_cuenta like '404%' or sig_cuenta like '405%' or sig_cuenta like '408%' or sig_cuenta like '411%'),0000) as anrealgas, COALESCE(sum((enero+febrero+marzo)),0000) as trimestre1gas,COALESCE(sum((abril+mayo+junio)),000) as trimestre2gas,COALESCE(sum((julio+agosto+septiembre)),000) as trimestre3gas, COALESCE(sum((octubre+noviembre+diciembre)),000) as trimestre4gas,
			  COALESCE(sum(enero),0000) as enerog,COALESCE(sum(febrero),000)as febrerog,COALESCE(sum(marzo),000) as marzog , COALESCE(sum(abril),000) as abrilg,COALESCE(sum(mayo),000)as mayog,COALESCE(sum(junio),000) as juniog,
			  COALESCE(sum(julio),0000)as juliog ,COALESCE(sum(agosto),000) as agostog,COALESCE(sum(septiembre),000) as septiembreg, COALESCE(sum(octubre),000) as octubreg,COALESCE(sum(noviembre),000) as noviembreg,COALESCE(sum(diciembre),000) as diciembreg,
			  COALESCE(sum((enero+febrero)),0000) as bimestre1g,COALESCE(sum((marzo+abril)),000) as bimestre2g,COALESCE(sum((mayo+junio)),000) as bimestre3g, COALESCE(sum((julio+agosto)),000) as bimestre4g,COALESCE(sum((septiembre+octubre)),000) as bimestre5g,COALESCE(sum((noviembre+diciembre)),000) as bimestre6g,	
			   COALESCE(sum((enero+febrero+marzo+abril+mayo+junio)),0000) as semestre1g, COALESCE(sum((julio+agosto+septiembre+octubre+noviembre+diciembre)),000) as semestre2g,
			  COALESCE(sum((enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre)),000) as montoglobalgas 
			  from  {$this->_table} where codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}' and ({$this->_table}.sig_cuenta like '401%' or {$this->_table}.sig_cuenta like '402%' or {$this->_table}.sig_cuenta like '403%' or {$this->_table}.sig_cuenta like '407%' or {$this->_table}.sig_cuenta like '404%' or {$this->_table}.sig_cuenta like '405%' or {$this->_table}.sig_cuenta like '408%' or {$this->_table}.sig_cuenta like '411%' or {$this->_table}.sig_cuenta like '40806%')";
		
		$rs =$db->Execute($sql);
		if($rs==true)
		{
			return $rs;	
		}
		else
		{
			return false;
		}
	}
	
	function LeerGatoConsumo()
	{
		global $db;	
		//$db->debug=true;
		$sql="select COALESCE((select sum(monto_anest) from sigesp_sfp_plancuentas where sig_cuenta like '401%' or sig_cuenta like '402%' or sig_cuenta like '403%' or sig_cuenta like '407%' or sig_cuenta like '408%'),0000) as anestimadogas ,COALESCE((select sum(monto_anreal) from sigesp_sfp_plancuentas where sig_cuenta like '401%' or sig_cuenta like '402%' or sig_cuenta like '403%' or sig_cuenta like '407%' or sig_cuenta like '408%'),0000) as anrealgas, COALESCE(sum((enero+febrero+marzo)),0000) as trimestre1gas,COALESCE(sum((abril+mayo+junio)),000) as trimestre2gas,COALESCE(sum((julio+agosto+septiembre)),000) as trimestre3gas, COALESCE(sum((octubre+noviembre+diciembre)),000) as trimestre4gas,
			  COALESCE(sum((enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre)),000) as montoglobalgas 
			  from  {$this->_table} where codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}' and ({$this->_table}.sig_cuenta like '401%' or {$this->_table}.sig_cuenta like '402%' or {$this->_table}.sig_cuenta like '403%' or {$this->_table}.sig_cuenta like '407%' or {$this->_table}.sig_cuenta like '408%')";
		
		$rs =$db->Execute($sql);
		if($rs==true)
		{
			return $rs;	
		}
		else
		{
			return false;
		}
	}
	
	function LeerGatoCorriente()
	{
		global $db;	
		//$db->debug=true;
		$sql="select COALESCE((select sum(monto_anest) from sigesp_sfp_plancuentas where sig_cuenta like '401%' or sig_cuenta like '402%' or sig_cuenta like '403%' or sig_cuenta like '407%' or sig_cuenta like '408%'),0000) as anestimadogas ,COALESCE((select sum(monto_anreal) from sigesp_sfp_plancuentas where sig_cuenta like '401%' or sig_cuenta like '402%' or sig_cuenta like '403%' or sig_cuenta like '407%' or sig_cuenta like '408%'),0000) as anrealgas, COALESCE(sum((enero+febrero+marzo)),0000) as trimestre1gas,COALESCE(sum((abril+mayo+junio)),000) as trimestre2gas,COALESCE(sum((julio+agosto+septiembre)),000) as trimestre3gas, COALESCE(sum((octubre+noviembre+diciembre)),000) as trimestre4gas,
			  COALESCE(sum((enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre)),000) as montoglobalgas 
			  from  {$this->_table} where codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}' and ({$this->_table}.sig_cuenta like '401%' or {$this->_table}.sig_cuenta like '402%' or {$this->_table}.sig_cuenta like '403%' or {$this->_table}.sig_cuenta like '407%' or {$this->_table}.sig_cuenta like '408%')";
		
		$rs =$db->Execute($sql);
		if($rs==true)
		{
			return $rs;	
		}
		else
		{
			return false;
		}
	}
	
	public function Existe()
	{
		global $db;
		//$db->debug=1;
		$sql="select * from $this->_table where sig_cuenta='{$this->sig_cuenta}' and codinte='{$this->codinte}' and {$this->_table}.codemp='{$this->codemp}'";
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
	
		
	public function Eliminar()
	{
		global $db;
		//$db->debug=1;
		$db->StartTrans();
		$sql="select * from sigesp_sfp_cmp where sig_cuenta='{$this->sig_cuenta}' and ano_presupuesto='{$this->ano_presupuesto}' and codemp='{$this->codemp}' and codinte={$this->codinte}";
		$Rs = $db->Execute($sql);
		if($Rs->fields["comprobante"])
		{
			$sql1="delete from scg_dt_sfp_cmp where comprobante='".$Rs->fields["comprobante"]."'";
			$sql2="delete from sfp_dt_cmp_variacion where comprobante='".$Rs->fields["comprobante"]."'";
			$sql3="delete from sigesp_sfp_cmp where comprobante='".$Rs->fields["comprobante"]."'";
		
		$res = $db->Execute($sql1);
		if($res)
		{
			$res=$db->Execute($sql2);	
		}
		if($res)
		{
			$res=$db->Execute($sql3);
		}
		if($res)
		{
			$oBjDtFinan = new intGastosFuenteDao();
			$oBjDtFinan->codemp=$this->codemp;
			$oBjDtFinan->sig_cuenta_gas=$this->sig_cuenta;
			$oBjDtFinan->codinte=$this->codinte;
			$oBjDtFinan->ano_presupuesto=$this->ano_presupuesto;
			
			$Rs = $oBjDtFinan->leerIngresosGastos();
			$oIngresos= new planIngreso();
			while($reg = $Rs->FetchRow())
			{
				$oIngresos->cuenta=$reg["sig_cuenta_ing"];
				$oIngresos->monto=$reg["montoasig"];
				$oIngresos->reversarDisponibilidad();
			}
					
			if($oBjDtFinan->Eliminar())
			{
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
		}
		}
		else
		{
			return "0";
		}
	}
}

?>