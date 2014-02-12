<?php
require_once("../class_folder/sigesp_conexion_dao.php");
require_once('../class_folder/dao/sigesp_sfp_empresaDao.php');
require_once('../class_folder/dao/sigesp_sfp_saldosconDao.php');
require_once('../class_folder/dao/sigesp_sfp_plan_unico_reDao.php');
require_once('../class_folder/dao/sigesp_sfp_asientosvariacionDao.php');
require_once('../class_folder/dao/sigesp_spe_asientosDao.php');
require_once('../class_folder/dao/sigesp_sfp_intGastosDao.php');
class planIngreso extends ADOdb_Active_Record
{
	var $_table='spe_plan_ingr';
	var $arrmovcont = array();
	var $arrmovcaif =  array();
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
		if($db->CompleteTrans())
		{
			return "1";	
		}
		else
		{
			$db->ErrorNo();	
		}
	}
	public function LeerAsientos()
	{
		global $db;
		//$db->debug=true;
		$oAsiento = new Asientos();
		$oAsiento->codemp=$this->codemp;
		$oAsiento->sig_cuenta=$this->sig_cuenta;
		$oAsiento->ano_presupuesto=$this->ano_presupuesto;
		$RsA = $oAsiento->LeerAsientoIngreso();
		//ver($RsA);
		$oAsientoVar = new AsientoVariacionDao();
		$oAsientoVar->sig_cuenta=$this->sig_cuenta; 
		$oAsientoVar->ano_presupuesto=$this->ano_presupuesto; 
		$RsC = $oAsientoVar->LeerAsientoIngreso();
		$ArAux["variacion"]=$RsA;
		$ArAux["caif"]=$RsC;  
		//ver($ArAux);
		return $ArAux;
	}
	
	public function estaDistribuida()
	{
		global $db;
		//$db->debug=1;
		$sql="select * from 
			  spe_int_cuentas_dtrecursos where 
			  sig_cuenta_ing='{$this->sig_cuenta}' and 
			  ano_presupuesto='{$this->ano_presupuesto}' 
			  and codemp='{$this->codemp}'";
		$res = $db->Execute($sql);
		if($res->RecordCount()>0)
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
		$sql="select * from sigesp_sfp_cmp where sig_cuenta='{$this->sig_cuenta}' and ano_presupuesto='{$this->ano_presupuesto}' and codemp='{$this->codemp}'";
		$Rs = $db->Execute($sql);
		//ver($Rs->fields["comprobante"]);
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
			else
			{
				return "0";
			}
	 	}
	}
	
	public function ModificarMontoIngreso()
	{
		global $db;
		//$db->debug=1;
		$db->StartTrans();
		$sql="select * from sigesp_sfp_cmp where sig_cuenta='{$this->sig_cuenta}' and ano_presupuesto='{$this->ano_presupuesto}' and codemp='{$this->codemp}'";
		$Rs = $db->Execute($sql);
	//	ver($Rs->fields["comprobante"]);
		if($Rs->fields["comprobante"])
		{
			// ver($this->arrmovcaif);
			for($i=0;$i<count($this->arrmovcont);$i++)
			{
				$sql1="update scg_dt_sfp_cmp set monto={$this->arrmovcont[$i]->monto} where comprobante='".$Rs->fields["comprobante"]."' and sc_cuenta = '{$this->arrmovcont[$i]->sc_cuenta}'";
				$res = $db->Execute($sql1);
			}
			
			for($i=0;$i<count($this->arrmovcaif);$i++)
			{
				$sql2="update sfp_dt_cmp_variacion set monto={$this->arrmovcont[$i]->monto} where comprobante='".$Rs->fields["comprobante"]."' and sig_cuenta = '{$this->arrmovcont[$i]->sig_cuenta}'";
				$res = $db->Execute($sql2);
			}
			if($res)
			{
				$sql="update {$this->_table} set enero={$this->enero},febrero={$this->febrero},marzo={$this->marzo},
					  abril={$this->abril},mayo={$this->mayo},junio={$this->junio},julio={$this->julio},agosto={$this->agosto},septiembre={$this->septiembre},
					  octubre={$this->octubre},noviembre={$this->noviembre},diciembre={$this->diciembre},
					  enerocob={$this->enerocob},febrerocob={$this->febrerocob},marzocob={$this->marzocob},
					  abrilcob={$this->abrilcob},mayocob={$this->mayocob},juniocob={$this->juniocob},juliocob={$this->juliocob},agostocob={$this->agostocob},septiembrecob={$this->septiembrecob},
					  octubrecob={$this->octubrecob},noviembrecob={$this->noviembrecob},diciembrecob={$this->diciembrecob}
					  where sig_cuenta='{$this->sig_cuenta}' 
					  and ano_presupuesto={$this->ano_presupuesto} and codemp = '{$this->codemp}'";
				$res=$db->Execute($sql);
				if($db->CompleteTrans())
				{
					return true;
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
	}
	
	public function BuscarCodigo()
	{
		global $db;
		$Rs = $db->Execute("select max(cod_fuenfin)  as cod from {$this->_table}"); 
		var_dump($Rs->fields['cod']); 
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
	
	public function LeerTodos()
	{
		global $db;
		$Rs = $this->Find("cod_fuenfin<>''");
		return $Rs;
		
	}
	
public function LeerCuentasIngrsos()
{
		global $db;
		$oEmpresa = new Empresa();
		$Rs = $oEmpresa->LeerDatos();
		if($Rs)
		{
			$Ingreso = $Rs->fields['ingreso'];
			$Rs = $db->Execute("select * from {$this->_table} where spi_cuenta like'{$Ingreso}%'");
		}
		return $Rs;
}
	
public function LeerPorCadena($cr,$cad)
{
	global $db;
	$oEmpresa = new Empresa();
	$Rs = $oEmpresa->LeerDatos();
	if($Rs)
	{
		$Ingreso = $Rs->fields['ingreso'];
		$Rs = $db->Execute("select * from {$this->_table} where spi_cuenta like'{$Ingreso}%' and {$cr} like  '%{$cad}%'");
		
	}
	return $Rs;
}

public function LeerPlan()
{
	global $db;
		$sql="select distinct spe_plan_ingr.ano_presupuesto, spe_plan_ingr.*,(spe_plan_ingr.enero+spe_plan_ingr.febrero+spe_plan_ingr.marzo+spe_plan_ingr.abril
			+spe_plan_ingr.mayo+spe_plan_ingr.junio+spe_plan_ingr.julio+spe_plan_ingr.agosto+spe_plan_ingr.septiembre
			+spe_plan_ingr.octubre+spe_plan_ingr.noviembre+spe_plan_ingr.diciembre) as montoglobal,spe_plan_ingr
			.disponible,sigesp_plan_unico_re.sig_cuenta,sigesp_plan_unico_re.denominacion, (spe_plan_ingr.enero+spe_plan_ingr.febrero+spe_plan_ingr.marzo+spe_plan_ingr.abril
			+spe_plan_ingr.mayo+spe_plan_ingr.junio+spe_plan_ingr.julio+spe_plan_ingr.agosto+spe_plan_ingr.septiembre
			+spe_plan_ingr.octubre+spe_plan_ingr.noviembre+spe_plan_ingr.diciembre)-spe_plan_ingr
			.disponible as distribuido 
			from spe_plan_ingr inner join sigesp_sfp_plancuentas on spe_plan_ingr
			.sig_cuenta=sigesp_sfp_plancuentas.sig_cuenta inner join sigesp_plan_unico_re on spe_plan_ingr
			.sig_cuenta=sigesp_plan_unico_re.sig_cuenta
			left outer join spe_int_cuentas_dtrecursos on spe_plan_ingr.sig_cuenta=spe_int_cuentas_dtrecursos
			.sig_cuenta_ing and spe_plan_ingr.ano_presupuesto=spe_int_cuentas_dtrecursos
			.ano_presupuesto and spe_plan_ingr.codemp=spe_int_cuentas_dtrecursos
			.codemp where spe_plan_ingr.codemp='{$this->codemp}' and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto}  
			group by spe_plan_ingr.enero,spe_plan_ingr.febrero, spe_plan_ingr.marzo, spe_plan_ingr.abril, spe_plan_ingr
			.mayo, spe_plan_ingr.junio, spe_plan_ingr.julio, spe_plan_ingr.agosto, spe_plan_ingr.septiembre, spe_plan_ingr
			.octubre, spe_plan_ingr.noviembre, spe_plan_ingr.diciembre, spe_plan_ingr.ano_presupuesto, spe_plan_ingr
			.sig_cuenta, spe_plan_ingr.codemp,spe_plan_ingr.sig_codemp,spe_plan_ingr.nivel, spe_plan_ingr.referencia
			,spe_plan_ingr.disponible,sigesp_plan_unico_re.sig_cuenta,sigesp_plan_unico_re.denominacion, spe_int_cuentas_dtrecursos.codemp, spe_int_cuentas_dtrecursos.ano_presupuesto,
			spe_int_cuentas_dtrecursos.sig_cuenta_gas, spe_int_cuentas_dtrecursos.sig_cuenta_ing, spe_int_cuentas_dtrecursos.codinte";
	//ver($sql);
	$Rs = $db->Execute($sql);
	return $Rs;
}



public function LeerDistribucion()
{
	$plancuentas = new planUnicoRe();
	$cantidad = $plancuentas->Cantdigitoscuentas();
	global $db;
	$sql="select distinct cuentaporcobrar,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre
			,enerocob,febrerocob,marzocob,abrilcob,mayocob,juniocob,juliocob,agostocob,septiembrecob,octubrecob,noviembrecob,diciembrecob
		  ,spe_plan_ingr.ano_presupuesto,sigesp_sfp_plancuentas.sig_cuenta,sigesp_sfp_plancuentas.denominacion,
			coalesce(sum(spe_int_cuentas_dtrecursos.montoasig),0) as distribuido,
			(spe_plan_ingr.enero+spe_plan_ingr.febrero
			+spe_plan_ingr.marzo+spe_plan_ingr.abril+spe_plan_ingr.mayo+spe_plan_ingr.junio
			+spe_plan_ingr.julio+spe_plan_ingr.agosto+spe_plan_ingr.septiembre
			+spe_plan_ingr.octubre+spe_plan_ingr.noviembre+spe_plan_ingr.diciembre) as montoglobal,
			(spe_plan_ingr.enerocob+spe_plan_ingr.febrerocob
			+spe_plan_ingr.marzocob+spe_plan_ingr.abrilcob+spe_plan_ingr.mayocob+spe_plan_ingr.juniocob
			+spe_plan_ingr.juliocob+spe_plan_ingr.agostocob+spe_plan_ingr.septiembrecob
			+spe_plan_ingr.octubrecob+spe_plan_ingr.noviembrecob+spe_plan_ingr.diciembrecob) as montoglobalcob,			
			(spe_plan_ingr.enero+spe_plan_ingr.febrero
			+spe_plan_ingr.marzo+spe_plan_ingr.abril+spe_plan_ingr.mayo+spe_plan_ingr.junio
			+spe_plan_ingr.julio+spe_plan_ingr.agosto+spe_plan_ingr.septiembre
			+spe_plan_ingr.octubre+spe_plan_ingr.noviembre+spe_plan_ingr.diciembre)-coalesce(sum(spe_int_cuentas_dtrecursos.montoasig),0) as disponible
			from spe_plan_ingr inner join sigesp_sfp_plancuentas on spe_plan_ingr
			.sig_cuenta=sigesp_sfp_plancuentas.sig_cuenta inner join sigesp_plan_unico_re sigesp_plan_unico_re on substr(spe_plan_ingr
			.sig_cuenta,1,{$cantidad})=sigesp_plan_unico_re.sig_cuenta
			left outer join spe_int_cuentas_dtrecursos on spe_plan_ingr.sig_cuenta=spe_int_cuentas_dtrecursos
			.sig_cuenta_ing and spe_plan_ingr.ano_presupuesto=spe_int_cuentas_dtrecursos
			.ano_presupuesto and spe_plan_ingr.codemp=spe_int_cuentas_dtrecursos
			.codemp where spe_plan_ingr.codemp='{$this->codemp}' and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto}  
			group by  cuentaporcobrar,sigesp_sfp_plancuentas.sig_cuenta,sigesp_sfp_plancuentas.denominacion,spe_plan_ingr.ano_presupuesto,
			spe_plan_ingr.enero,spe_plan_ingr.febrero, spe_plan_ingr.marzo, spe_plan_ingr.abril, spe_plan_ingr
			.mayo, spe_plan_ingr.junio, spe_plan_ingr.julio, spe_plan_ingr.agosto, spe_plan_ingr.septiembre, spe_plan_ingr
			.octubre, spe_plan_ingr.noviembre, spe_plan_ingr.diciembre,enerocob,febrerocob,marzocob,abrilcob,mayocob,juniocob,juliocob,agostocob,septiembrecob,octubrecob,noviembrecob,diciembrecob";
	//ver($sql);
	$Rs = $db->Execute($sql);
	return $Rs;	
}



public function LeerDistribucionTran()
{
	global $db;
	$sql="select distinct cuentaporcobrar,sigesp_sfp_plancuentas.referencia,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre
			,enerocob,febrerocob,marzocob,abrilcob,mayocob,juniocob,juliocob,agostocob,septiembrecob,octubrecob,noviembrecob,diciembrecob
		   ,spe_plan_ingr.ano_presupuesto,sigesp_sfp_plancuentas.nivel,sigesp_sfp_plancuentas.sig_cuenta as spi_cuenta,sigesp_sfp_plancuentas.estatus as status,sigesp_sfp_plancuentas.denominacion,
			coalesce(sum(spe_int_cuentas_dtrecursos.montoasig),0) as distribuido,
			(spe_plan_ingr.enero+spe_plan_ingr.febrero
			+spe_plan_ingr.marzo+spe_plan_ingr.abril+spe_plan_ingr.mayo+spe_plan_ingr.junio
			+spe_plan_ingr.julio+spe_plan_ingr.agosto+spe_plan_ingr.septiembre
			+spe_plan_ingr.octubre+spe_plan_ingr.noviembre+spe_plan_ingr.diciembre) as previsto,0 as devengado,0 as cobrado,
			0 as cobrado_anticipado,0 as aumento,0 as disminucion,0 as distribuir,
			(spe_plan_ingr.enerocob+spe_plan_ingr.febrerocob
			+spe_plan_ingr.marzocob+spe_plan_ingr.abrilcob+spe_plan_ingr.mayocob+spe_plan_ingr.juniocob
			+spe_plan_ingr.juliocob+spe_plan_ingr.agostocob+spe_plan_ingr.septiembrecob
			+spe_plan_ingr.octubrecob+spe_plan_ingr.noviembrecob+spe_plan_ingr.diciembrecob) as montoglobalcob,			
			(spe_plan_ingr.enero+spe_plan_ingr.febrero
			+spe_plan_ingr.marzo+spe_plan_ingr.abril+spe_plan_ingr.mayo+spe_plan_ingr.junio
			+spe_plan_ingr.julio+spe_plan_ingr.agosto+spe_plan_ingr.septiembre
			+spe_plan_ingr.octubre+spe_plan_ingr.noviembre+spe_plan_ingr.diciembre)-coalesce(sum(spe_int_cuentas_dtrecursos.montoasig),0) as disponible
			from spe_plan_ingr inner join sigesp_sfp_plancuentas on spe_plan_ingr
			.sig_cuenta=sigesp_sfp_plancuentas.sig_cuenta inner join sigesp_plan_unico_re sigesp_plan_unico_re on substr(spe_plan_ingr
			.sig_cuenta,1,9)=sigesp_plan_unico_re.sig_cuenta
			left outer join spe_int_cuentas_dtrecursos on spe_plan_ingr.sig_cuenta=spe_int_cuentas_dtrecursos
			.sig_cuenta_ing and spe_plan_ingr.ano_presupuesto=spe_int_cuentas_dtrecursos
			.ano_presupuesto and spe_plan_ingr.codemp=spe_int_cuentas_dtrecursos
			.codemp where spe_plan_ingr.codemp='{$this->codemp}' and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto}  
			group by  cuentaporcobrar,sigesp_sfp_plancuentas.sig_cuenta,sigesp_sfp_plancuentas.estatus ,sigesp_sfp_plancuentas.referencia,sigesp_sfp_plancuentas.denominacion,spe_plan_ingr.ano_presupuesto,
			spe_plan_ingr.enero,spe_plan_ingr.febrero, spe_plan_ingr.marzo, spe_plan_ingr.abril, spe_plan_ingr
			.mayo, spe_plan_ingr.junio, spe_plan_ingr.julio, spe_plan_ingr.agosto, spe_plan_ingr.septiembre, spe_plan_ingr
			.octubre, spe_plan_ingr.noviembre, spe_plan_ingr.diciembre,enerocob,febrerocob,marzocob,abrilcob,
			mayocob,juniocob,juliocob,agostocob,septiembrecob,octubrecob,noviembrecob,diciembrecob,sigesp_sfp_plancuentas.nivel";
	
	$Rs = $db->Execute($sql);
	return $Rs;	
}


public function LeerDistribucionporCuenta()
{
	global $db;
	//$db->debug=true;
	$sql="select distinct spe_plan_ingr.ano_presupuesto,sigesp_plan_unico_re.sig_cuenta,sigesp_plan_unico_re.denominacion,
			coalesce(sum(spe_int_cuentas_dtrecursos.montoasig),0) as distribuido,
			(spe_plan_ingr.enero+spe_plan_ingr.febrero
			+spe_plan_ingr.marzo+spe_plan_ingr.abril+spe_plan_ingr.mayo+spe_plan_ingr.junio
			+spe_plan_ingr.julio+spe_plan_ingr.agosto+spe_plan_ingr.septiembre
			+spe_plan_ingr.octubre+spe_plan_ingr.noviembre+spe_plan_ingr.diciembre) as montoglobal,
			(spe_plan_ingr.enero+spe_plan_ingr.febrero
			+spe_plan_ingr.marzo+spe_plan_ingr.abril+spe_plan_ingr.mayo+spe_plan_ingr.junio
			+spe_plan_ingr.julio+spe_plan_ingr.agosto+spe_plan_ingr.septiembre
			+spe_plan_ingr.octubre+spe_plan_ingr.noviembre+spe_plan_ingr.diciembre)-coalesce(sum(spe_int_cuentas_dtrecursos.montoasig),0) as disponible
			from spe_plan_ingr inner join sigesp_sfp_plancuentas on spe_plan_ingr
			.sig_cuenta=sigesp_sfp_plancuentas.sig_cuenta inner join sigesp_plan_unico_re on spe_plan_ingr
			.sig_cuenta=sigesp_plan_unico_re.sig_cuenta
			left outer join spe_int_cuentas_dtrecursos on spe_plan_ingr.sig_cuenta=spe_int_cuentas_dtrecursos
			.sig_cuenta_ing and spe_plan_ingr.ano_presupuesto=spe_int_cuentas_dtrecursos
			.ano_presupuesto and spe_plan_ingr.codemp=spe_int_cuentas_dtrecursos
			.codemp where spe_plan_ingr.codemp='{$this->codemp}' and spe_plan_ingr.ano_presupuesto={$this->ano_presupuesto} and sig_cuenta_gas='{$this->cuenta}'
			group by  sigesp_plan_unico_re.sig_cuenta,sigesp_plan_unico_re.denominacion,spe_plan_ingr.ano_presupuesto,
			spe_plan_ingr.enero,spe_plan_ingr.febrero, spe_plan_ingr.marzo, spe_plan_ingr.abril, spe_plan_ingr
			.mayo, spe_plan_ingr.junio, spe_plan_ingr.julio, spe_plan_ingr.agosto, spe_plan_ingr.septiembre, spe_plan_ingr
			.octubre, spe_plan_ingr.noviembre, spe_plan_ingr.diciembre";
	
	$Rs = $db->Execute($sql);
	return $Rs;	
}



public function LeerPlan2()
{
	global $db;
	$sql="select $this->_table.*,({$this->_table}.enero+{$this->_table}.febrero+{$this->_table}.marzo+{$this->_table}.abril+{$this->_table}.mayo+{$this->_table}.junio+{$this->_table}.julio+{$this->_table}.agosto+{$this->_table}.septiembre+{$this->_table}.octubre+{$this->_table}.noviembre+{$this->_table}.diciembre) as montoglobal,{$this->_table}.disponible,sigesp_plan_unico_re.sig_cuenta,sigesp_plan_unico_re.denominacion, coalesce(sum(spe_int_cuentas_dtrecursos.montoasig),000) as distribuido from {$this->_table} inner join sigesp_sfp_plancuentas on {$this->_table}.sig_cuenta=sigesp_sfp_plancuentas.sig_cuenta inner join sigesp_sfp_asociacion on sigesp_sfp_plancuentas.sig_cuenta=sigesp_sfp_asociacion.sig_cuenta inner join sigesp_plan_unico_re on sigesp_sfp_asociacion.sig_cuenta=sigesp_plan_unico_re.sig_cuenta
			left outer join spe_int_cuentas_dtrecursos on {$this->_table}.sig_cuenta=spe_int_cuentas_dtrecursos.sig_cuenta_ing
			group by {$this->_table}.enero,{$this->_table}.febrero, {$this->_table}.marzo, {$this->_table}.abril, {$this->_table}.mayo, {$this->_table}.junio, {$this->_table}.julio, {$this->_table}.agosto, {$this->_table}.septiembre, {$this->_table}.octubre, {$this->_table}.noviembre, {$this->_table}.diciembre, {$this->_table}.ano_presupuesto, {$this->_table}.sig_cuenta, {$this->_table}.codemp,{$this->_table}.sig_codemp,{$this->_table}.nivel, {$this->_table}.referencia,{$this->_table}.disponible,sigesp_plan_unico_re.sig_cuenta,sigesp_plan_unico_re.denominacion";
   
	$Rs = $db->Execute($sql);
	return $Rs;
}


public function LeerAsiento()
{
	global $db;
	$sql="select scg_dt_sfp_cmp.sc_cuenta, sigesp_sfp_plan_unico.denominacion from sigesp_sfp_cmp inner join scg_dt_sfp_cmp on sigesp_sfp_cmp.comprobante=scg_dt_sfp_cmp.comprobante inner join sigesp_sfp_plan_unico on scg_dt_sfp_cmp.sc_cuenta=sigesp_sfp_plan_unico.sc_cuenta  where sigesp_sfp_cmp.sig_cuenta='{$this->sig_cuenta}' and ano_presupuesto='{$this->ano_presupuesto}'";
	$Rs = $db->Execute($sql);
	return $Rs;
}
public function ActualizarDisponibilidad($cuenta,$monto,$an_pre,$codemp)
{
	global $db;
	//$db->debug=1;
	$db->StartTrans();
	$sql="update {$this->_table} set disponible=disponible-{$monto} where sig_cuenta='{$cuenta}' and ano_presupuesto=$an_pre and codemp='{$codemp}'";
	$db->Execute($sql);
	if($db->CompleteTrans())
	{
		return "1";	
	}
	else
	{
		$db->ErrorNo();	
	}
	return $Rs;
}

public function reversarDisponibilidad()
{
	global $db;
	//$db->debug=1;
	$db->StartTrans();
	$sql="update {$this->_table} set disponible=disponible+{$this->monto} where sig_cuenta='{$this->cuenta}' and ano_presupuesto={$this->ano_presupuesto} and codemp='{$this->codemp}'";
	$db->Execute($sql);
	if($db->CompleteTrans())
	{
		return "1";	
	}
	else
	{
		$db->ErrorNo();	
	}
	return $Rs;
}

public function LeerPlanPorCuenta($cuenta)
{
	global $db;
	$Rs = $db->Execute("select {$this->_table}.*,{$this->_table}.enero+{$this->_table}.febrero+{$this->_table}.marzo+{$this->_table}.abril+{$this->_table}.mayo+{$this->_table}.junio+{$this->_table}.julio+{$this->_table}.agosto+{$this->_table}.septiembre+{$this->_table}.octubre+{$this->_table}.noviembre+{$this->_table}.diciembre as montoGlobal,spi_cuentas.denominacion from {$this->_table} inner join spi_cuentas on {$this->_table}.spi_cuenta=spi_cuentas.spi_cuenta where {$this->_table}.spi_cuenta='$cuenta'");
	return $Rs;
}

public function LeerTransferencia()
{
	$plancuentas = new planUnicoRe();
	$cantidad = $plancuentas->Cantdigitoscuentas();
	global $db;
	$sql1="select sigesp_plan_unico_re.sig_cuenta, denominacion,(spe_plan_ingr.enero+spe_plan_ingr.febrero+spe_plan_ingr.marzo+spe_plan_ingr.abril+spe_plan_ingr.mayo+spe_plan_ingr.junio+spe_plan_ingr.julio+spe_plan_ingr.agosto+spe_plan_ingr.septiembre+spe_plan_ingr.octubre+spe_plan_ingr.noviembre+spe_plan_ingr.diciembre) as montoglobal from sigesp_plan_unico_re inner join spe_plan_ingr on sigesp_plan_unico_re.sig_cuenta=substr(spe_plan_ingr.sig_cuenta,1,{$cantidad}) where spe_plan_ingr.sig_cuenta like '3050103%'";
	$Rs1 = $db->Execute($sql1);
	$sql2="select sigesp_plan_unico_re.sig_cuenta, denominacion,(spe_plan_ingr.enero+spe_plan_ingr.febrero+spe_plan_ingr.marzo+spe_plan_ingr.abril+spe_plan_ingr.mayo+spe_plan_ingr.junio+spe_plan_ingr.julio+spe_plan_ingr.agosto+spe_plan_ingr.septiembre+spe_plan_ingr.octubre+spe_plan_ingr.noviembre+spe_plan_ingr.diciembre) as montoglobal from sigesp_plan_unico_re inner join spe_plan_ingr on sigesp_plan_unico_re.sig_cuenta=substr(spe_plan_ingr.sig_cuenta,1,{$cantidad}) where spe_plan_ingr.sig_cuenta like '3050104%'";
	$Rs2 = $db->Execute($sql2);
	$sql3="select sigesp_plan_unico_re.sig_cuenta, denominacion,(spe_plan_ingr.enero+spe_plan_ingr.febrero+spe_plan_ingr.marzo+spe_plan_ingr.abril+spe_plan_ingr.mayo+spe_plan_ingr.junio+spe_plan_ingr.julio+spe_plan_ingr.agosto+spe_plan_ingr.septiembre+spe_plan_ingr.octubre+spe_plan_ingr.noviembre+spe_plan_ingr.diciembre) as montoglobal from sigesp_plan_unico_re inner join spe_plan_ingr on sigesp_plan_unico_re.sig_cuenta=substr(spe_plan_ingr.sig_cuenta,1,{$cantidad}) where spe_plan_ingr.sig_cuenta like '3050203%'";
	$Rs3 = $db->Execute($sql3);
	$sql4="select sigesp_plan_unico_re.sig_cuenta, denominacion,(spe_plan_ingr.enero+spe_plan_ingr.febrero+spe_plan_ingr.marzo+spe_plan_ingr.abril+spe_plan_ingr.mayo+spe_plan_ingr.junio+spe_plan_ingr.julio+spe_plan_ingr.agosto+spe_plan_ingr.septiembre+spe_plan_ingr.octubre+spe_plan_ingr.noviembre+spe_plan_ingr.diciembre) as montoglobal from sigesp_plan_unico_re inner join spe_plan_ingr on sigesp_plan_unico_re.sig_cuenta=substr(spe_plan_ingr.sig_cuenta,1,{$cantidad}) where spe_plan_ingr.sig_cuenta like '3050204%'";
	$Rs4 = $db->Execute($sql4);
	$sql5="select sum(spe_plan_ingr.enero+spe_plan_ingr.febrero+spe_plan_ingr.marzo+spe_plan_ingr.abril+spe_plan_ingr.mayo+spe_plan_ingr.junio+spe_plan_ingr.julio+spe_plan_ingr.agosto+spe_plan_ingr.septiembre+spe_plan_ingr.octubre+spe_plan_ingr.noviembre+spe_plan_ingr.diciembre) as suma from spe_plan_ingr where sig_cuenta like '3050103%' or sig_cuenta like '3050104%' or sig_cuenta like '3050203%' or sig_cuenta like '3050204%'";
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
		$arrPosiciones = split("-",$Rs->fields["formspi"]);		
		$numPosiciones = count($arrPosiciones);
		$pos1= strlen($arrPosiciones[0]);
		$pos2= $pos1+1;
		$pos3=$pos2+2;
		$pos4=$pos3+2; 
	//	if($numPosiciones=="5")
	//	{
			$sql="select (select denominacion from sigesp_plan_unico_re where sig_cuenta = '{$this->sig_cuenta}') as denominacion, (select substr(sigesp_plan_unico_re.sig_cuenta,1,".strlen($arrPosiciones[0]).") from sigesp_plan_unico_re where sig_cuenta = '{$this->sig_cuenta}') as ramo,(select substr(sigesp_plan_unico_re.sig_cuenta,".$pos2.",".strlen($arrPosiciones[1]).") from sigesp_plan_unico_re where sig_cuenta = '{$this->sig_cuenta}') as subramo,(select substr(sigesp_plan_unico_re.sig_cuenta,".$pos3.",".strlen($arrPosiciones[2]).") from sigesp_plan_unico_re where sig_cuenta = '{$this->sig_cuenta}') as especifica, (select substr(sigesp_plan_unico_re.sig_cuenta,".$pos4.",".strlen($arrPosiciones[3]).") from sigesp_plan_unico_re where sig_cuenta = '{$this->sig_cuenta}') as subespecifica,
				  sum(sigesp_sfp_plancuentas.monto_anest) as monto_anest,sum(sigesp_sfp_plancuentas.monto_anreal) as monto_anreal, COALESCE(sum(enero),0000) as enero,COALESCE(sum(febrero),000)as febrero,COALESCE(sum(marzo),000) as marzo , COALESCE(sum(abril),000) as abril,COALESCE(sum(mayo),000)as mayo,COALESCE(sum(junio),000) as junio,
				 COALESCE(sum(julio),0000)as julio ,COALESCE(sum(agosto),000) as agosto,COALESCE(sum(septiembre),000) as septiembre, COALESCE(sum(octubre),000) as octubre,COALESCE(sum(noviembre),000) as noviembre,COALESCE(sum(diciembre),000) as diciembre,
				 COALESCE(sum((enero+febrero)),0000) as bimestre1,COALESCE(sum((marzo+abril)),000) as bimestre2,COALESCE(sum((mayo+junio)),000) as bimestre3, COALESCE(sum((julio+agosto)),000) as bimestre4,COALESCE(sum((septiembre+octubre)),000) as bimestre5,COALESCE(sum((noviembre+diciembre)),000) as bimestre6,
				 COALESCE(sum((enero+febrero+marzo)),0000) as trimestre1,COALESCE(sum((abril+mayo+junio)),000) as trimestre2,COALESCE(sum((julio+agosto+septiembre)),000) as trimestre3, COALESCE(sum((octubre+noviembre+diciembre)),000) as trimestre4,
				 COALESCE(sum((enero+febrero+marzo+abril+mayo+junio)),0000) as semestre1, COALESCE(sum((julio+agosto+septiembre+octubre+noviembre+diciembre)),000) as semestre4,
				 COALESCE(sum(({$this->_table}.enero+{$this->_table}.febrero+{$this->_table}.marzo+{$this->_table}.abril+{$this->_table}.mayo+{$this->_table}.junio+{$this->_table}.julio+{$this->_table}.agosto+{$this->_table}.septiembre+{$this->_table}.octubre+{$this->_table}.noviembre+{$this->_table}.diciembre)),000) as montoglobalgas 
				 from {$this->_table} inner join sigesp_plan_unico_re on substr({$this->_table}.sig_cuenta,1,9) = sigesp_plan_unico_re.sig_cuenta inner join sigesp_sfp_plancuentas on {$this->_table}.sig_cuenta=sigesp_sfp_plancuentas.sig_cuenta
				  where {$this->_table}.codemp = '{$this->codemp}' and {$this->_table}.ano_presupuesto={$this->ano_presupuesto} and sigesp_plan_unico_re.sig_cuenta like '{$cuentasinceros}%' ";
		
			//die();
			$Rs=$db->Execute($sql);
			if($Rs->RecordCount()==0)
			{
				return false;
			}
			else
			{
			//	if($cuentasinceros=="303")
			//	ver ($Rs);	
				return $Rs;			
			}
	//	}
	
	}
public Function LeerIngresos()
{
	global $db;
	$sql ="select sigesp_plan_unico_re.sig_cuenta, sigesp_plan_unico_re.denominacion,(spe_plan_ingr.enero+spe_plan_ingr.febrero+spe_plan_ingr.marzo+spe_plan_ingr.abril+spe_plan_ingr.mayo+spe_plan_ingr.junio+spe_plan_ingr.julio+spe_plan_ingr.agosto+spe_plan_ingr.septiembre+
		  spe_plan_ingr.octubre+spe_plan_ingr.noviembre+spe_plan_ingr.diciembre) as total, spe_plan_ingr.disponible
		  from sigesp_plan_unico_re inner join spe_plan_ingr on sigesp_plan_unico_re.sig_cuenta=spe_plan_ingr.sig_cuenta";	
	
	$Rs=$db->Execute($sql);
	return $Rs;
}

public function tieneMovimiento($grupoCuenta)
{
	$valido=true;
	global $db;
	//$db->debug=true;
	$sql="select * from spe_plan_ingr where sig_cuenta like '$grupoCuenta%' and codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}'";
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
		$sql="select sum(monto_anreal) as anreal, sum(monto_anest) as anest from sigesp_sfp_plancuentas where sig_cuenta like '$grupoCuenta%' codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}'";
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
	$valido=true;
	global $db;
	$sql="select * from spe_plan_ingr where sig_cuenta like '$grupoCuenta%' and codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}'";
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


public function leerDatosPorDefecto()
{
	global $db;
	$sql="select '' as denominacion,'' as anestimado,'' as anreal,'' as codcuenta,'' as trimestre1, '' as trimestre2, '' as trimestre3, '' as trimestre4, 
		 '' as enero, '' as febrero, '' as marzo, '' as abril,'' as mayo, '' as junio,'' as julio,'' as agosto, '' as septiembre, '' as octubre, '' as noviembre, '' as diciembre,
		  '' as bimestre1, '' as bimestre2, '' as bimestre3, '' as bimestre4, '' as bimestre5, '' as bimestre6, '' as semestre1, '' as semestre2, '' as montoglobal from sigesp_empresa";
	$rs =$db->Execute($sql);
	return $rs;
}

public function  tienecxp($grupoCuenta)
{	
	global $db;
	$sql="select * from spe_plan_ingr where sig_cuenta like '$grupoCuenta%' and codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}' and (cuentaporcobrar<>'' and cuentaporcobrar is not null)";
	$rs=$db->Execute($sql);
	if($rs->RecordCount()>0)
	{
		$Valido=true;
	}
	else
	{
		$Valido=false;
	}
	return $Valido;

}


function LeerSaldo()
{
	global $db;
	//$db->debug=true;
	$cuentasinceros=uf_spg_cuenta_sin_cero(trim($this->sig_cuenta));
	if($cuentasinceros=="")
	{
		$cuentasinceros=trim($this->sig_cuenta);
	}
	if($this->tieneMovimiento($cuentasinceros)==true)
	{
			if(!$this->tienecxp($cuentasinceros))
			{
				$sql="select (select denominacion from sigesp_plan_unico_re where sig_cuenta='{$this->sig_cuenta}') as denominacion,COALESCE((select sum(monto_anest) from sigesp_sfp_plancuentas where sig_cuenta like '{$cuentasinceros}%'),0000) as anestimado ,COALESCE((select sum(monto_anreal)  from sigesp_sfp_plancuentas where sig_cuenta like '{$cuentasinceros}%'),0000) as anreal,$this->sig_cuenta as codcuenta,
					 COALESCE(sum(enero),0000) as enero,COALESCE(sum(febrero),000)as febrero,COALESCE(sum(marzo),000) as marzo , COALESCE(sum(abril),000) as abril,COALESCE(sum(mayo),000)as mayo,COALESCE(sum(junio),000) as junio,
					 COALESCE(sum(julio),0000)as julio ,COALESCE(sum(agosto),000) as agosto,COALESCE(sum(septiembre),000) as septiembre, COALESCE(sum(octubre),000) as octubre,COALESCE(sum(noviembre),000) as noviembre,COALESCE(sum(diciembre),000) as diciembre,
					 COALESCE(sum((enero+febrero)),0000) as bimestre1,COALESCE(sum((marzo+abril)),000) as bimestre2,COALESCE(sum((mayo+junio)),000) as bimestre3, COALESCE(sum((julio+agosto)),000) as bimestre4,COALESCE(sum((septiembre+octubre)),000) as bimestre5,COALESCE(sum((noviembre+diciembre)),000) as bimestre6,
					 COALESCE(sum((enero+febrero+marzo)),0000) as trimestre1,COALESCE(sum((abril+mayo+junio)),000) as trimestre2,COALESCE(sum((julio+agosto+septiembre)),000) as trimestre3, COALESCE(sum((octubre+noviembre+diciembre)),000) as trimestre4,
					 COALESCE(sum((enero+febrero+marzo+abril+mayo+junio)),0000) as semestre1, COALESCE(sum((julio+agosto+septiembre+octubre+noviembre+diciembre)),000) as semestre2,			 
					 COALESCE(sum((spe_plan_ingr.enero+spe_plan_ingr.febrero+spe_plan_ingr.marzo+spe_plan_ingr.abril+spe_plan_ingr.mayo+spe_plan_ingr.junio+spe_plan_ingr.julio+spe_plan_ingr.agosto+spe_plan_ingr.septiembre+spe_plan_ingr.octubre+spe_plan_ingr.noviembre+spe_plan_ingr.diciembre)),000) as montoglobal 
					 from spe_plan_ingr {$this->_table} inner join sigesp_plan_unico_re on {$this->_table}.sig_cuenta=sigesp_plan_unico_re.sig_cuenta where codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}' and spe_plan_ingr.sig_cuenta like '{$cuentasinceros}%' and  spe_plan_ingr.sig_cuenta not like '31101%'"; 
				
				$rs =$db->Execute($sql);
				if($rs!=false)
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
				$sql="select (select denominacion from sigesp_plan_unico_re where sig_cuenta='{$this->sig_cuenta}') as denominacion,COALESCE((select sum(monto_anest) from sigesp_sfp_plancuentas where sig_cuenta like '{$cuentasinceros}%'),0000) as anestimado ,COALESCE((select sum(monto_anreal)  from sigesp_sfp_plancuentas where sig_cuenta like '{$cuentasinceros}%'),0000) as anreal,$this->sig_cuenta as codcuenta,
						 COALESCE(sum(enerocob),0000) as enero,COALESCE(sum(febrerocob),000)as febrero,COALESCE(sum(marzocob),000) as marzo , COALESCE(sum(abrilcob),000) as abril,COALESCE(sum(mayocob),000)as mayo,COALESCE(sum(juniocob),000) as junio,
						 COALESCE(sum(juliocob),0000)as julio ,COALESCE(sum(agostocob),000) as agosto,COALESCE(sum(septiembrecob),000) as septiembre, COALESCE(sum(octubrecob),000) as octubre,COALESCE(sum(noviembrecob),000) as noviembre,COALESCE(sum(diciembrecob),000) as diciembre,
						 COALESCE(sum((enerocob+febrerocob)),0000) as bimestre1,COALESCE(sum((marzocob+abrilcob)),000) as bimestre2,COALESCE(sum((mayocob+juniocob)),000) as bimestre3, COALESCE(sum((juliocob+agostocob)),000) as bimestre4,COALESCE(sum((septiembrecob+octubrecob)),000) as bimestre5,COALESCE(sum((noviembrecob+diciembrecob)),000) as bimestre6,
						 COALESCE(sum((enerocob+febrerocob+marzocob)),0000) as trimestre1,COALESCE(sum((abrilcob+mayocob+juniocob)),000) as trimestre2,COALESCE(sum((juliocob+agostocob+septiembrecob)),000) as trimestre3, COALESCE(sum((octubrecob+noviembrecob+diciembrecob)),000) as trimestre4,
						 COALESCE(sum((enerocob+febrerocob+marzocob+abrilcob+mayocob+juniocob)),0000) as semestre1, COALESCE(sum((juliocob+agostocob+septiembrecob+octubrecob+noviembrecob+diciembrecob)),000) as semestre2,			 
						 COALESCE(sum((spe_plan_ingr.enerocob+spe_plan_ingr.febrerocob+spe_plan_ingr.marzocob+spe_plan_ingr.abrilcob+spe_plan_ingr.mayocob+spe_plan_ingr.juniocob+spe_plan_ingr.juliocob+spe_plan_ingr.agostocob+spe_plan_ingr.septiembrecob+spe_plan_ingr.octubrecob+spe_plan_ingr.noviembrecob+spe_plan_ingr.diciembrecob)),000) as montoglobal 
						 from spe_plan_ingr {$this->_table} inner join sigesp_plan_unico_re on {$this->_table}.sig_cuenta=sigesp_plan_unico_re.sig_cuenta where codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}' and spe_plan_ingr.sig_cuenta like '{$cuentasinceros}%' and  spe_plan_ingr.sig_cuenta not like '31101%'"; 
					$rs =$db->Execute($sql);
					if($rs!=false)
					{
						return $rs;	
					}
					else
					{
						return false;
					}	
			}
	}
	else
	{
		return false;
	}
}



function LeerSaldototIngr()
{
	global $db;
	//$db->debug=true;
	$cuentasinceros=uf_spg_cuenta_sin_cero(trim($this->sig_cuenta));
	if($cuentasinceros=="")
	{
		$cuentasinceros=trim($this->sig_cuenta);
	}
	if($this->tieneMovimiento($cuentasinceros)==true)
	{
				$sql="select (select denominacion from sigesp_plan_unico_re where sig_cuenta='{$this->sig_cuenta}') as denominacion,COALESCE((select sum(monto_anest) from sigesp_sfp_plancuentas where sig_cuenta like '{$cuentasinceros}%'),0000) as anestimado ,COALESCE((select sum(monto_anreal)  from sigesp_sfp_plancuentas where sig_cuenta like '{$cuentasinceros}%'),0000) as anreal,$this->sig_cuenta as codcuenta,
					 COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then enero when cuentaporcobrar<>'' or cuentaporcobrar is not null then enerocob end),0000) as enero,COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then febrero when cuentaporcobrar<>'' or cuentaporcobrar is not null then febrerocob end),000) as febrero,COALESCE(sum(marzo),000)+COALESCE(sum(marzocob),000) as marzo , COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then abril when cuentaporcobrar<>'' or cuentaporcobrar is not null then abrilcob end),000) as abril,COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then mayo when cuentaporcobrar<>'' or cuentaporcobrar is not null then mayocob end),000) as mayo,COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then junio when cuentaporcobrar<>'' or cuentaporcobrar is not null then juniocob end),000) as junio,
					 COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then julio when cuentaporcobrar<>'' or cuentaporcobrar is not null then juliocob end),0000) as julio ,COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then agosto when cuentaporcobrar<>'' or cuentaporcobrar is not null then agostocob end),000) as agosto,COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then septiembre when cuentaporcobrar<>'' or cuentaporcobrar is not null then septiembrecob end),000) as septiembre, COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then octubre when cuentaporcobrar<>'' or cuentaporcobrar is not null then octubrecob end),000) as octubre,COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then noviembre when cuentaporcobrar<>'' or cuentaporcobrar is not null then noviembrecob end),000) as noviembre,COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then noviembre when cuentaporcobrar<>'' or cuentaporcobrar is not null then noviembrecob end),000) as diciembre,
					 COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then enero+febrero when cuentaporcobrar<>'' or cuentaporcobrar is not null then enerocob+febrerocob end),0000) as bimestre1,COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then marzo+abril when cuentaporcobrar<>'' or cuentaporcobrar is not null then marzocob+abrilcob end),000) as bimestre2,COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then mayo+junio when cuentaporcobrar<>'' or cuentaporcobrar is not null then mayocob+juniocob end),000) as bimestre3, COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then julio+agosto when cuentaporcobrar<>'' or cuentaporcobrar is not null then juliocob+agostocob end),000) as bimestre4,COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then septiembre+octubre when cuentaporcobrar<>'' or cuentaporcobrar is not null then septiembrecob+octubrecob end),000) as bimestre5,COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then noviembre+diciembre when cuentaporcobrar<>'' or cuentaporcobrar is not null then noviembrecob+diciembrecob end),000) as bimestre6,
					 COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then enero+febrero+marzo when cuentaporcobrar<>'' or cuentaporcobrar is not null then enerocob+febrerocob+marzocob end),0000) as trimestre1,COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then abril+mayo+junio when cuentaporcobrar<>'' or cuentaporcobrar is not null then abrilcob+mayocob+juniocob end),000) as trimestre2,COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then julio+agosto+septiembre when cuentaporcobrar<>'' or cuentaporcobrar is not null then juliocob+agostocob+septiembrecob end),000) as trimestre3, COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then octubre+noviembre+diciembre when cuentaporcobrar<>'' or cuentaporcobrar is not null then octubrecob+noviembrecob+diciembrecob end),000) as trimestre4,
					 COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then enero+febrero+marzo+abril+mayo+junio when cuentaporcobrar<>'' or cuentaporcobrar is not null then enerocob+febrerocob+marzocob+abrilcob+mayocob+juniocob end),0000) as semestre1, COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then julio+agosto+septiembre+octubre+noviembre+diciembre when cuentaporcobrar<>'' or cuentaporcobrar is not null then juliocob+agostocob+septiembrecob+octubrecob+noviembrecob+diciembrecob end),000) as semestre2,			 
					 COALESCE(sum(case when cuentaporcobrar='' or cuentaporcobrar isnull  then enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre when cuentaporcobrar<>'' or cuentaporcobrar is not null then enerocob+febrerocob+marzocob+abrilcob+mayocob+juniocob+juliocob+agostocob+septiembrecob+octubrecob+noviembrecob+diciembrecob end),000) as montoglobal 
					 from spe_plan_ingr {$this->_table} inner join sigesp_plan_unico_re on {$this->_table}.sig_cuenta=sigesp_plan_unico_re.sig_cuenta where codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}' and spe_plan_ingr.sig_cuenta like '{$cuentasinceros}%' and  spe_plan_ingr.sig_cuenta not like '31101%'"; 
				//ver($sql);
				$rs =$db->Execute($sql);
				if($rs!=false)
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
	//$db->debug=true;
	$cuentasinceros=uf_spg_cuenta_sin_cero($this->sig_cuenta);
	if($cuentasinceros=="")
	{
		$cuentasinceros=$this->sig_cuenta;
	}
	
		//ver($this->sig_cuenta);
		$sql="select (select denominacion from sigesp_plan_unico_re where sig_cuenta='{$this->sig_cuenta}') as denominacion,
			(select codcaif from sigesp_sfp_asociacion where sig_cuenta='{$this->sig_cuenta}') as codcaif,COALESCE((select sum(monto_anest) from sigesp_sfp_plancuentas where sig_cuenta like '{$cuentasinceros}%'),0000) as anestimado ,COALESCE((select sum(monto_anreal)  from sigesp_sfp_plancuentas where sig_cuenta like '{$cuentasinceros}%'),0000) as anreal,$this->sig_cuenta as codcuenta,
			COALESCE(sum((spe_plan_ingr.enero+spe_plan_ingr.febrero+spe_plan_ingr.marzo+spe_plan_ingr.abril+spe_plan_ingr.mayo+spe_plan_ingr.junio+spe_plan_ingr.julio+spe_plan_ingr.agosto+spe_plan_ingr.septiembre+spe_plan_ingr.octubre+spe_plan_ingr.noviembre+spe_plan_ingr.diciembre)),000) as montoglobal 
			from spe_plan_ingr {$this->_table}  where codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}' and spe_plan_ingr.sig_cuenta like '{$cuentasinceros}%'";	  

			
			$rs =$db->Execute($sql);
			if($rs!=false)
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
	//$db->debug=true;
	$plancuentas = new planUnicoRe();
	$cantidad = $plancuentas->Cantdigitoscuentas();
	$this->sig_cuenta = str_pad($this->sig_cuenta,$cantidad,"0");
	$cuentasinceros=uf_spg_cuenta_sin_cero($this->sig_cuenta);
	if($cuentasinceros=="")
	{
		$cuentasinceros=$this->sig_cuenta;
	}
	//if($this->tieneMovimiento($cuentasinceros)==true)
	//{
	//echo "si";
		
	//se lee el saldo de la cuenta en la clase de asientos de variacion patrimonial
		$ovariacion = new AsientoVariacionDao();
		$ovariacion->sig_cuenta=$cuentasinceros;
		$totalvariacion=$ovariacion->LeerSaldoVariacion('H');
		
		$sql="select (select denominacion from sigesp_plan_unico_re where sig_cuenta='{$this->sig_cuenta}') as denominacion,
			(select codcaif from sigesp_sfp_asociacion where sig_cuenta='{$this->sig_cuenta}') as codcaif,COALESCE((select sum(monto_anest) from sigesp_sfp_plancuentas where sig_cuenta like '{$cuentasinceros}%'),0000) as anestimado ,COALESCE((select sum(monto_anreal)  from sigesp_sfp_plancuentas where sig_cuenta like '{$cuentasinceros}%'),0000) as anreal,$this->sig_cuenta as codcuenta,
			COALESCE(sum((spe_plan_ingr.enero+spe_plan_ingr.febrero+spe_plan_ingr.marzo+spe_plan_ingr.abril+spe_plan_ingr.mayo+spe_plan_ingr.junio+spe_plan_ingr.julio+spe_plan_ingr.agosto+spe_plan_ingr.septiembre+spe_plan_ingr.octubre+spe_plan_ingr.noviembre+spe_plan_ingr.diciembre)),000)+{$totalvariacion} as montoglobal 
			from spe_plan_ingr {$this->_table} inner join sigesp_plan_unico_re on {$this->_table}.sig_cuenta=sigesp_plan_unico_re.sig_cuenta where codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}' and spe_plan_ingr.sig_cuenta like '{$cuentasinceros}%'";
			if($cuentasinceros=="311")
			{
			//	ver($sql);
			}	  
			$rs =$db->Execute($sql);
			if($rs!=false)
			{
				return $rs;	
			}
			else
			{
				return false;
			}
}


function LeerSaldoInRes()
{
		global $db;	
		//$db->debug=true;
		$sql="select COALESCE((select sum(monto_anest) from sigesp_sfp_plancuentas where sig_cuenta like '301%' or sig_cuenta like '302%' or sig_cuenta like '303%' or sig_cuenta like '304%' or sig_cuenta like '305%'),0000) as anestimado ,COALESCE((select sum(monto_anreal) from sigesp_sfp_plancuentas where sig_cuenta like '301%' or sig_cuenta like '302%' or sig_cuenta like '303%' or sig_cuenta like '304%' or sig_cuenta like '305%'),0000) as anreal,
			 COALESCE(sum(enero),0000) as enero,COALESCE(sum(febrero),000)as febrero,COALESCE(sum(marzo),000) as marzo , COALESCE(sum(abril),000) as abril,COALESCE(sum(mayo),000)as mayo,COALESCE(sum(junio),000) as junio,
			 COALESCE(sum(julio),0000)as julio ,COALESCE(sum(agosto),000) as agosto,COALESCE(sum(septiembre),000) as septiembre, COALESCE(sum(octubre),000) as octubre,COALESCE(sum(noviembre),000) as noviembre,COALESCE(sum(diciembre),000) as diciembre,
			 COALESCE(sum((enero+febrero)),0000) as bimestre1,COALESCE(sum((marzo+abril)),000) as bimestre2,COALESCE(sum((mayo+junio)),000) as bimestre3, COALESCE(sum((julio+agosto)),000) as bimestre4,COALESCE(sum((septiembre+octubre)),000) as bimestre5,COALESCE(sum((noviembre+diciembre)),000) as bimestre6,
			 COALESCE(sum((enero+febrero+marzo)),0000) as trimestre1,COALESCE(sum((abril+mayo+junio)),000) as trimestre2,COALESCE(sum((julio+agosto+septiembre)),000) as trimestre3, COALESCE(sum((octubre+noviembre+diciembre)),000) as trimestre4,
			 COALESCE(sum((enero+febrero+marzo+abril+mayo+junio)),0000) as semestre1, COALESCE(sum((julio+agosto+septiembre+octubre+noviembre+diciembre)),000) as semestre4,			 
			 COALESCE(sum((spe_plan_ingr.enero+spe_plan_ingr.febrero+spe_plan_ingr.marzo+spe_plan_ingr.abril+spe_plan_ingr.mayo+spe_plan_ingr.junio+spe_plan_ingr.julio+spe_plan_ingr.agosto+spe_plan_ingr.septiembre+spe_plan_ingr.octubre+spe_plan_ingr.noviembre+spe_plan_ingr.diciembre)),000) as montoglobal 
			 from spe_plan_ingr {$this->_table} inner join sigesp_plan_unico_re on {$this->_table}.sig_cuenta=sigesp_plan_unico_re.sig_cuenta where codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}' and (spe_plan_ingr.sig_cuenta like '301%' or spe_plan_ingr.sig_cuenta like '302%' or spe_plan_ingr.sig_cuenta like '303%' or  spe_plan_ingr.sig_cuenta like '304%' or spe_plan_ingr.sig_cuenta like '305%')";
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

function LeerSaldoInRes2()
{
		global $db;	
	//	$db->debug=true;
		$sql="select COALESCE((select sum(monto_anest) from sigesp_sfp_plancuentas where sig_cuenta like '301%' or sig_cuenta like '302%' or sig_cuenta like '303%' or sig_cuenta like '304%' or sig_cuenta like '305%' or sig_cuenta like '312%' or sig_cuenta like '306%' or sig_cuenta like '307%' or sig_cuenta like '311%' or sig_cuenta like '310%' or sig_cuenta like '309%' or sig_cuenta like '312%' or sig_cuenta like '313%' or sig_cuenta like '308%'),0000) as anestimado ,COALESCE((select sum(monto_anreal) from sigesp_sfp_plancuentas where sig_cuenta like '301%' or sig_cuenta like '302%' or sig_cuenta like '303%' or sig_cuenta like '304%' or sig_cuenta like '305%' or sig_cuenta like '312%' or sig_cuenta like '306%' or sig_cuenta like '307%' or sig_cuenta like '311%' or sig_cuenta like '310%' or sig_cuenta like '309%' or sig_cuenta like '312%' or sig_cuenta like '313%' or sig_cuenta like '308%'),0000) as anreal,
			 COALESCE(sum(enero),0000) as enero,COALESCE(sum(febrero),000)as febrero,COALESCE(sum(marzo),000) as marzo , COALESCE(sum(abril),000) as abril,COALESCE(sum(mayo),000)as mayo,COALESCE(sum(junio),000) as junio,
			 COALESCE(sum(julio),0000)as julio ,COALESCE(sum(agosto),000) as agosto,COALESCE(sum(septiembre),000) as septiembre, COALESCE(sum(octubre),000) as octubre,COALESCE(sum(noviembre),000) as noviembre,COALESCE(sum(diciembre),000) as diciembre,
			 COALESCE(sum((enero+febrero)),0000) as bimestre1,COALESCE(sum((marzo+abril)),000) as bimestre2,COALESCE(sum((mayo+junio)),000) as bimestre3, COALESCE(sum((julio+agosto)),000) as bimestre4,COALESCE(sum((septiembre+octubre)),000) as bimestre5,COALESCE(sum((noviembre+diciembre)),000) as bimestre6,
			 COALESCE(sum((enero+febrero+marzo)),0000) as trimestre1,COALESCE(sum((abril+mayo+junio)),000) as trimestre2,COALESCE(sum((julio+agosto+septiembre)),000) as trimestre3, COALESCE(sum((octubre+noviembre+diciembre)),000) as trimestre4,
			 COALESCE(sum((enero+febrero+marzo+abril+mayo+junio)),0000) as semestre1, COALESCE(sum((julio+agosto+septiembre+octubre+noviembre+diciembre)),000) as semestre4,			 
			 COALESCE(sum((spe_plan_ingr.enero+spe_plan_ingr.febrero+spe_plan_ingr.marzo+spe_plan_ingr.abril+spe_plan_ingr.mayo+spe_plan_ingr.junio+spe_plan_ingr.julio+spe_plan_ingr.agosto+spe_plan_ingr.septiembre+spe_plan_ingr.octubre+spe_plan_ingr.noviembre+spe_plan_ingr.diciembre)),000) as montoglobal 
			 from spe_plan_ingr  inner join sigesp_plan_unico_re on {$this->_table}.sig_cuenta=sigesp_plan_unico_re.sig_cuenta where codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}' and ({$this->_table}.sig_cuenta like '301%' or {$this->_table}.sig_cuenta like '302%' or {$this->_table}.sig_cuenta like '303%' or {$this->_table}.sig_cuenta like '304%' or {$this->_table}.sig_cuenta like '305%' or {$this->_table}.sig_cuenta like '312%' or {$this->_table}.sig_cuenta like '306%' or {$this->_table}.sig_cuenta like '307%' or {$this->_table}.sig_cuenta like '311%' or {$this->_table}.sig_cuenta like '310%' or {$this->_table}.sig_cuenta like '309%' or {$this->_table}.sig_cuenta like '312%' or {$this->_table}.sig_cuenta like '313%' or {$this->_table}.sig_cuenta like '308%')";
		
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

function LeerAhorroDes()
{
	$oCuentaGas = new intGastosDao();
	$ingCor = $this->Leertotingcorrientes();
	$gasCor = $oCuentaGas->Leertotgascorrientes();
	$ingCap = $this->Leertotingcapital();
	$gasCap = $oCuentaGas->Leertotgascapital();
	$totgascorrientes = $ingCor - $gasCor; 
	$totgascapital = $ingCap - $gasCap;
	global $db;
	$sql="select '' as codcuenta, 'Ahorro/Desahorro en Cuenta Corriente' as denominacion,{$totgascorrientes} as montoglobal";
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

function Leertotingcorrientes()
{
		global $db;	
		//$db->debug=true;
		$sql="select COALESCE(sum((spe_plan_ingr.enero+spe_plan_ingr.febrero+spe_plan_ingr.marzo+spe_plan_ingr.abril+spe_plan_ingr.mayo+spe_plan_ingr.junio+spe_plan_ingr.julio+spe_plan_ingr.agosto+spe_plan_ingr.septiembre+spe_plan_ingr.octubre+spe_plan_ingr.noviembre+spe_plan_ingr.diciembre)),000) as montoglobal 
			  from spe_plan_ingr {$this->_table} where codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}' and (spe_plan_ingr.sig_cuenta like '30501%' or spe_plan_ingr.sig_cuenta like '30401%' or spe_plan_ingr.sig_cuenta like '30402%' or  spe_plan_ingr.sig_cuenta like '301%' or  spe_plan_ingr.sig_cuenta like '30499%' or  spe_plan_ingr.sig_cuenta like '303%')";
		
		$rs =$db->Execute($sql);
		if($rs==true)
		{
			return $rs->fields["montoglobal"];	
		}
		else
		{
			return false;
		}
}

function Leertotingcapital()
{
		global $db;	
		//$db->debug=true;
		$sql="select COALESCE(sum((spe_plan_ingr.enero+spe_plan_ingr.febrero+spe_plan_ingr.marzo+spe_plan_ingr.abril+spe_plan_ingr.mayo+spe_plan_ingr.junio+spe_plan_ingr.julio+spe_plan_ingr.agosto+spe_plan_ingr.septiembre+spe_plan_ingr.octubre+spe_plan_ingr.noviembre+spe_plan_ingr.diciembre)),000) as montoglobal 
			  from spe_plan_ingr {$this->_table} where codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}' and (spe_plan_ingr.sig_cuenta like '30502%' or spe_plan_ingr.sig_cuenta like '306%' or spe_plan_ingr.sig_cuenta like '30801%' or  spe_plan_ingr.sig_cuenta like '30802%' or  spe_plan_ingr.sig_cuenta like '30803%' or  spe_plan_ingr.sig_cuenta like '3090101%' or spe_plan_ingr.sig_cuenta like '30902%' or  spe_plan_ingr.sig_cuenta like '30903%' or spe_plan_ingr.sig_cuenta like '31001%' or  spe_plan_ingr.sig_cuenta like '31002%' or  spe_plan_ingr.sig_cuenta like '31003%')";
		
		$rs =$db->Execute($sql);
		if($rs==true)
		{
			return $rs->fields["montoglobal"];	
		}
		else
		{
			return false;
		}
}

public function LeerResFinIng()
{
	global $db;	
	//$db->debug=true;
	$ovariacion = new AsientoVariacionDao();
	$ovariacion->sig_cuenta=$cuentasinceros;
	$totalvariacion=$ovariacion->LeerSaldoIngresos();
	
	$sql=" select (select COALESCE(sum((spe_plan_ingr.enero+spe_plan_ingr.febrero+spe_plan_ingr.marzo+spe_plan_ingr.abril+spe_plan_ingr.mayo+spe_plan_ingr.junio+spe_plan_ingr.julio+spe_plan_ingr.agosto+spe_plan_ingr.septiembre
		 +spe_plan_ingr.octubre+spe_plan_ingr.noviembre+spe_plan_ingr.diciembre)),000) as montoglobal 
		 from spe_plan_ingr {$this->_table} where codemp='{$this->codemp}' and ano_presupuesto='{$this->ano_presupuesto}' 
		 and (spe_plan_ingr.sig_cuenta like '307%' or spe_plan_ingr.sig_cuenta like '311%' or spe_plan_ingr.sig_cuenta like '312%' or  
		 spe_plan_ingr.sig_cuenta like '313%'))+({$totalvariacion}) as montoresfinanIng";
		$rs =$db->Execute($sql);
		if($rs==true)
		{
			
			return $rs->fields["montoresfinaning"];	
		}
		else
		{
			return false;
		}
}


public function repingfuentes_finan()		
{	
	//	$la_cuenta[5]=array();
		$la_cuenta[0]["cuenta"]='301000000'.$ls_ceros;
		$la_cuenta[1]["cuenta"]='302000000'.$ls_ceros;
		$la_cuenta[2]["cuenta"]='303000000'.$ls_ceros;
		$la_cuenta[3]["cuenta"]='304000000'.$ls_ceros;
		$la_cuenta[4]["cuenta"]='305000000'.$ls_ceros;
		$la_cuenta[5]["cuenta"]='306000000'.$ls_ceros;
		$la_cuenta[6]["cuenta"]='307000000'.$ls_ceros;
		$la_cuenta[7]["cuenta"]='308000000'.$ls_ceros;
		$la_cuenta[8]["cuenta"]='309000000'.$ls_ceros;
		$la_cuenta[9]["cuenta"]='311000000'.$ls_ceros;
//		$la_cuenta[10]["cuenta"]='311000000'.$ls_ceros;
		$la_cuenta[10]["cuenta"]='312000000'.$ls_ceros;
		$la_cuenta[11]["cuenta"]='313000000'.$ls_ceros;

		$datastore1=array();
	    $id1=0;
	
	    for($i=0;$i<count($la_cuenta);$i++)
	    {
	    		
				$oCuenta1 =  new planUnicoRe();
				$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["cuenta"]);
				$oCuenta1->sig_cuenta = $cuentasinceros;
				$oCuenta1->sig_cuentacomp = $la_cuenta[$i]["cuenta"];
				$rsGrupo = $oCuenta1->LeerGrupo();
				while($rec =$rsGrupo->FetchRow())
				{
					$oCuenta= new planIngreso();
					$oCuenta->sig_cuenta = trim($rec["sig_cuenta"]);
					$oCuenta->nivelCuenta=$this->nivel;	
					$resp = $oCuenta->Verificar();
					
					if($resp!=false)
					{
						//echo $oCuenta->sig_cuenta; 
						$resp = $oCuenta->LeerCuentasTrimestre();
						if($resp!=false)
						{		  						
							$datastore1[$id1]=$resp;	
					  		$id1++;
						}
					}
				}
	    }
	  //  ver($datastore1);
	    $rsTotal = $this->LeerSaldoInRes2();
	    $Datos["cuentas"]=$datastore1;
	    $Datos["datos8"]=$rsTotal;
	    return $Datos;	
}


function reporte_presupuesto_de_caja2()
{////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :  uf_spg_reportes_presupuesto_de_caja
	 //     Argumentos : adt_fecdes ... adt_fechas  // rango de fecha del reporte
	 //                  adts_datastore  // datastore que imprime el reporte
     //	       Returns : Retorna true o false si se realizo la consulta para el reporte
	 //	   Description : Reporte que genera salida del Presupuesto de Caja
	 //     Creado por : Ing. Arnaldo Suárez
	 // Fecha Creación : 18/06/2008                       Fecha última Modificacion :      Hora :
  	 ///////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$la_cuenta[18]=array();
		// ----> 1.  INGRESOS CORRIENTES
		//$la_cuenta[0]["detalles"]=Array('302000000','303000000','304000000','305000000');
		$la_cuenta[0]["cuenta"]='300000000'.$ls_ceros;
		
		$la_cuenta[1]["cuenta"]='305010000'.$ls_ceros;
		
		$la_cuenta[2]["cuenta"]='301090000'.$ls_ceros;
		
		$la_cuenta[3]["cuenta"]='408070000'.$ls_ceros; // ---> Menos Descuentos, Bonificaciones y Devoluciones
        // ---> Ventas Netas
        
		$la_cuenta[4]["cuenta"]='301000000'.$ls_ceros;
		
		$la_cuenta[5]["cuenta"]='303000000'.$ls_ceros;
		
		// ---> 2. GASTOS CORRIENTES
		
		
	
		$la_cuenta[6]["cuenta"]='305020000'.$ls_ceros;
		$la_cuenta[7]["cuenta"]='306000000'.$ls_ceros;

		$la_cuenta[8]["cuenta"]='307000000'.$ls_ceros;
		$la_cuenta[9]["cuenta"]='308000000'.$ls_ceros;
		$la_cuenta[10]["cuenta"]='309000000'.$ls_ceros;
		$la_cuenta[11]["cuenta"]='311000000'.$ls_ceros;
		$la_cuenta[12]["cuenta"]='312000000'.$ls_ceros;
		$la_cuenta[13]["cuenta"]='313000000'.$ls_ceros;
		$la_cuenta[14]["cuenta"]='400000000'.$ls_ceros;
		$la_cuenta[15]["cuenta"]='401000000'.$ls_ceros;
		$la_cuenta[16]["cuenta"]='402000000'.$ls_ceros;
		$la_cuenta[17]["cuenta"]='404000000'.$ls_ceros;
		// ---> Variación de Inventarios (Detallar)
		// ---> b. Otros Gastos Corrientes
		$la_cuenta[18]["cuenta"]='407000000'.$ls_ceros;
		$la_cuenta[19]["cuenta"]='408020000'.$ls_ceros;
		$la_cuenta[20]["cuenta"]='408030000'.$ls_ceros;
		$la_cuenta[21]["cuenta"]='408060000'.$ls_ceros;
		$la_cuenta[22]["cuenta"]='408080000'.$ls_ceros;
		$la_cuenta[23]["cuenta"]='408990000'.$ls_ceros;
		$la_cuenta[24]["cuenta"]='408080000'.$ls_ceros;
		$la_cuenta[25]["cuenta"]='409000000'.$ls_ceros;
		$la_cuenta[26]["cuenta"]='411000000'.$ls_ceros;
		$la_cuenta[27]["cuenta"]='412000000'.$ls_ceros;
		$la_cuenta[28]["cuenta"]='413000000'.$ls_ceros;
	    
		$datastore0=array();
	    $id0=0;
		$datastore1=array();
	    $id1=0;
	    $datastore2=array();
	    $id2=0;
	    $datastore3=array();
	    $id3=0;
	    $datastore4=array();
	    $id4=0;
	    $datastore5=array();
	    $id5=0;
	    $datastore6=array();
	    $id6=0;
	    $datastore7=array();
	    $id7=0;
	    $datastore8=array();
	    $id8=0;
	    
		for($i=0;$i<=27;$i++)
		{
			if($i==0)
			{
				$oCuenta= new planIngreso();
				$oCuenta->sig_cuenta = $la_cuenta[$i]["cuenta"];
		  		$resp = $oCuenta->LeerSaldo();
				if($resp!=false)
				{		  						
					$datastore0[$id0]=$oCuenta->LeerSaldototIngr();	
			  		$id0++;
				}
			}
			
			if($i==1 || $i==2)
			{
				$oCuenta1 =  new planUnicoRe();
				$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["cuenta"]);
				$oCuenta1->sig_cuenta = $cuentasinceros;
				$oCuenta1->sig_cuentacomp = $la_cuenta[$i]["cuenta"];
				$rsGrupo = $oCuenta1->LeerGrupo();
				while($rec =$rsGrupo->FetchRow())
				{
					$oCuenta= new planIngreso();
					$oCuenta->sig_cuenta = $rec["sig_cuenta"];
					$oCuenta->nivelCuenta=$this->nivel;	
					$resp = $oCuenta->Verificar();
					if($resp!=false)
					{
						$resp = $oCuenta->LeerSaldo();
						if($resp!=false)
						{		  						
							$datastore1[$id1]=$oCuenta->LeerSaldo();	
					  		$id1++;
						}
					}
				}
			}
			if($i==3)
			{
				$oCuenta1 =  new planUnicoRe();
				$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["cuenta"]);
				$oCuenta1->sig_cuenta = $cuentasinceros;
				$oCuenta1->sig_cuentacomp = $la_cuenta[$i]["cuenta"];
				$rsGrupo = $oCuenta1->LeerGrupo();
				while($rec =$rsGrupo->FetchRow())
				{
					$oCuenta= new intGastosDao();
					$oCuenta->sig_cuenta = $rec["sig_cuenta"];
					$oCuenta->nivelCuenta=$this->nivel;	
					$resp = $oCuenta->Verificar();
					if($resp!=false)
					{
						$resp = $oCuenta->LeerSaldo();
						if($resp!=false)
						{		  						
							$datastore2[$id2]=$oCuenta->LeerSaldo();	
					  		$id2++;
						}
					}
				}
			}
  			
  				
  			if($i>3 && $i<=5)
  			{
				$oCuenta1 =  new planUnicoRe();
				$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["cuenta"]);
				$oCuenta1->sig_cuenta = $cuentasinceros;
				$oCuenta1->sig_cuentacomp = $la_cuenta[$i]["cuenta"];
				$rsGrupo = $oCuenta1->LeerGrupo();
				while($rec =$rsGrupo->FetchRow())
				{
					$oCuenta= new planIngreso();
					$oCuenta->sig_cuenta = trim($rec["sig_cuenta"]);
					$oCuenta->nivelCuenta=$this->nivel;	
					$resp = $oCuenta->Verificar();
					if($resp!=false)
					{	
						$resp = $oCuenta->LeerSaldo();
						if($resp!=false)
						{		  			
							$datastore3[$id3]=$oCuenta->LeerSaldo();	
					  		$id3++;
						}
					}
				}	
  			}
  			
  			
			if($i>5 && $i<=7)
  			{					 	//echo $i;
				$oCuenta1 =  new planUnicoRe();
				$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["cuenta"]);
				$oCuenta1->sig_cuenta = $cuentasinceros;
				$oCuenta1->sig_cuentacomp = $la_cuenta[$i]["cuenta"];
				$rsGrupo = $oCuenta1->LeerGrupo();
				while($rec =$rsGrupo->FetchRow())
				{
					$oCuenta= new planIngreso();
					$oCuenta->sig_cuenta = $rec["sig_cuenta"];
					$oCuenta->nivelCuenta=$this->nivel;	
					$resp = $oCuenta->Verificar();
					if($resp!=false)
					{
						$resp = $oCuenta->LeerSaldo();
						if($resp!=false)
						{		  						
							$datastore4[$id4]=$oCuenta->LeerSaldo();	
					  		$id4++;
						}
					}
				}
  					
    		}
    		
    		if($i>7 && $i<=13)
  			{					 	//echo $i;
				$oCuenta1 =  new planUnicoRe();
				$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["cuenta"]);
				$oCuenta1->sig_cuenta = $cuentasinceros;
				$oCuenta1->sig_cuentacomp = $la_cuenta[$i]["cuenta"];
				$rsGrupo = $oCuenta1->LeerGrupo();
				while($rec =$rsGrupo->FetchRow())
				{
					$oCuenta= new planIngreso();
					$oCuenta->sig_cuenta = $rec["sig_cuenta"];
					if(trim($oCuenta->sig_cuenta)!="311010000")
					{
						$oCuenta->nivelCuenta=$this->nivel;	
						$resp = $oCuenta->Verificar();
						if($resp!=false)
						{
							$resp = $oCuenta->LeerSaldo();
							if($resp!=false)
							{		  						
								$datastore5[$id5]=$oCuenta->LeerSaldo();	
						  		$id5++;
							}
						}
					}
				}
  					
    		}
    		
  			
  			if($i==14)
  			{
				$oCuenta= new intGastosDao();
				$oCuenta->sig_cuenta = $la_cuenta[$i]["cuenta"];
		  		$datastore6[0]=$oCuenta->LeerSaldoEgEstRes2();
		  		$id6++;
  			}
  			
  			if($i>14 && $i<=16)
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
						$resp = $oCuenta->LeerSaldo();
						if($resp!=false)
						{		  						
							$datastore7[$id7]=$oCuenta->LeerSaldo();	
					  		$id7++;
						}
					}
				}
  			}
  			
  			if($i>=17 && $i<=28)
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
						$resp = $oCuenta->LeerSaldo();
						if($resp!=false)
						{		  						
							$datastore8[$id8]=$oCuenta->LeerSaldo();	
					  		$id8++;
						}
					}
				}
  			}
   		}	 		
		if($id0==0)
		{
			$datastore0[0] = $this->leerDatosPorDefecto();
		}
		if($id1==0)
		{
			$datastore1[0] = $this->leerDatosPorDefecto();
		}	
		if($id2==0)
		{
			$datastore2[0] = $this->leerDatosPorDefecto();
		}
		if($id3==0)
		{
			$datastore3[0] = $this->leerDatosPorDefecto();
		}
		if($id4==0)
		{
			$datastore4[0] = $this->leerDatosPorDefecto();
		}
		if($id5==0)
		{
			$datastore5[0] = $this->leerDatosPorDefecto();
		}
		if($id6==0)
		{
			$datastore6[0] = $this->leerDatosPorDefecto();
		}
		
		if($id7==0)
		{
			$datastore7[0] = $this->leerDatosPorDefecto();
		}
		
		if($id8==0)
		{
			$datastore8[0] = $this->leerDatosPorDefecto();
		}
      		
   		$oSalInicial=new SaldosCont();
   		$oSalInicial->sc_cuenta="11101";
   		$datastorei[0]=$oSalInicial->LeerSaldoInicial();
   		$arrDatos["datastorei"]=$datastorei;
   		$arrDatos["datos0"]=$datastore0;
		$arrDatos["datos1"]=$datastore1;
		$arrDatos["datos2"]=$datastore2;
		$arrDatos["datos3"]=$datastore3;
		$arrDatos["datos4"]=$datastore4;
		$arrDatos["datos5"]=$datastore5;
		$arrDatos["datos6"]=$datastore6;
		$arrDatos["datos7"]=$datastore7;
		$arrDatos["datos8"]=$datastore8;
  		return  $arrDatos;
}//fin uf_spg_reportes_presupuesto_de_caja


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
	$cuentasincero=uf_spg_cuenta_sin_cero(trim($this->sig_cuenta));
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
			
		//var_dump($this->nivelCuenta);
		//die();
	if($niveldelacuenta==$this->nivelCuenta or array_search($niveldelacuenta,$arrAnteriores))
	{
		return true;
	}
	else
	{
		return false;
	}
	
}


function reporte_estado_de_resultados2()
{	 ////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :  uf_spg_reportes_presupuesto_de_caja
	 //     Argumentos : adt_fecdes ... adt_fechas  // rango de fecha del reporte
	 //                  adts_datastore  // datastore que imprime el reporte
     //	       Returns : Retorna true o false si se realizo la consulta para el reporte
	 //	   Description : Reporte que genera salida del Presupuesto de Caja
	 //     Creado por : Ing. Arnaldo Suárez
	 // Fecha Creación : 18/06/2008                       Fecha última Modificacion :      Hora :
  	 ///////////////////////////////////////////////////////////////////////////////////////////////////////

		$la_cuenta[18]=array();
		// ----> 1.  INGRESOS CORRIENTES
		//$la_cuenta[0]["detalles"]=Array('302000000','303000000','304000000','305000000');
		$la_cuenta[0]["cuenta"]='300000000'.$ls_ceros;
		
		$la_cuenta[1]["cuenta"]='305000000'.$ls_ceros;
		
		$la_cuenta[2]["cuenta"]='301090000'.$ls_ceros;
		
		$la_cuenta[3]["cuenta"]='408070000'.$ls_ceros; // ---> Menos Descuentos, Bonificaciones y Devoluciones
        // ---> Ventas Netas
		$la_cuenta[4]["cuenta"]='301030000'.$ls_ceros;
		$la_cuenta[5]["cuenta"]='303000000'.$ls_ceros;
		
		// ---> 2. GASTOS CORRIENTES
		$la_cuenta[6]["cuenta"]='301000000'.$ls_ceros;
		$la_cuenta[7]["cuenta"]='301050000'.$ls_ceros;
		$la_cuenta[8]["cuenta"]='301100000'.$ls_ceros;
		$la_cuenta[9]["cuenta"]='300200000'.$ls_ceros;
			
		$la_cuenta[10]["cuenta"]='401000000'.$ls_ceros;
		$la_cuenta[11]["cuenta"]='402000000'.$ls_ceros;
		$la_cuenta[12]["cuenta"]='403000000'.$ls_ceros;
		$la_cuenta[13]["cuenta"]='408010000'.$ls_ceros;
		// ---> Variación de Inventarios (Detallar)
		// ---> b. Otros Gastos Corrientes
		$la_cuenta[14]["cuenta"]='407000000'.$ls_ceros;
		$la_cuenta[15]["cuenta"]='408020000'.$ls_ceros;
		$la_cuenta[16]["cuenta"]='408050000'.$ls_ceros;
		$la_cuenta[17]["cuenta"]='408060000'.$ls_ceros;
		$la_cuenta[18]["cuenta"]='408080000'.$ls_ceros;
	    
		$datastore0=array();
	    $id0=0;
		$datastore1=array();
	    $id1=0;
	    $datastore2=array();
	    $id2=0;
	    $datastore3=array();
	    $id3=0;
	    $datastore4=array();
	    $id4=0;
	    $datastore5=array();
	    $id5=0;
	    $datastore6=array();
	    $id6=0;
	    $datastore7=array();
	    $id7=0;
	    $datastore8=array();
	    $id8=0;
	    
		for($i=0;$i<=18;$i++)
		{
			if($i==0)
			{
				$oCuenta= new planIngreso();
				$oCuenta->sig_cuenta = $la_cuenta[$i]["cuenta"];
		  		$resp = $oCuenta->LeerSaldo();
				if($resp!=false)
				{		  						
					$datastore0[$id0]=$oCuenta->LeerSaldoInRes();	
			  		$id0++;
				}
				if(is_array($la_cuenta[$i]["detalles"]))
  				{
  					for($j=0;$j<count($la_cuenta[$i]["detalles"]);$j++)
  					{	
  						$oCuenta=new planIngreso();
  						$oCuenta->sig_cuenta=$la_cuenta[$i]["detalles"][$j];	
  						$resp = $oCuenta->LeerSaldo();
						if($resp!=false)
						{		  						
	  						$datastore0[$id0]=$oCuenta->LeerSaldo();
	  						$id0++;
						}
					}
  				}
			}
		
			if($i==1)
			{
				$oCuenta1 =  new planUnicoRe();
				$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["cuenta"]);
				
				$oCuenta1->sig_cuenta = $cuentasinceros;
				$oCuenta1->sig_cuentacomp = $la_cuenta[$i]["cuenta"];
				$rsGrupo = $oCuenta1->LeerGrupo();
				while($rec =$rsGrupo->FetchRow())
				{
					$oCuenta= new planIngreso();
					$oCuenta->sig_cuenta = $rec["sig_cuenta"];
					$oCuenta->nivelCuenta=$this->nivel;	
					$resp = $oCuenta->Verificar();
					if($resp!=false)
					{
						$resp = $oCuenta->LeerSaldo();
						if($resp!=false)
						{		  						
							$datastore1[$id1]=$oCuenta->LeerSaldo();	
					  		$id1++;
						}
					}
				}
			}
			
				
			if($i==2)
			{
				$oCuenta1 =  new planUnicoRe();
				$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["cuenta"]);
				$oCuenta1->sig_cuenta = $cuentasinceros;
				$oCuenta1->sig_cuentacomp = $la_cuenta[$i]["cuenta"];
				$rsGrupo = $oCuenta1->LeerGrupo();
				while($rec =$rsGrupo->FetchRow())
				{
					$oCuenta= new planIngreso();
					$oCuenta->sig_cuenta = $rec["sig_cuenta"];
					$oCuenta->nivelCuenta=$this->nivel;	
					$resp = $oCuenta->Verificar();
					if($resp!=false)
					{
						$resp = $oCuenta->LeerSaldo();
						if($resp!=false)
						{		  						
							$datastore2[$id2]=$oCuenta->LeerSaldo();	
					  		$id2++;
						}
					}
				}
			}
			
			
			if($i==3)
			{
				$oCuenta1 =  new planUnicoRe();
				$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["cuenta"]);
				$oCuenta1->sig_cuenta = $cuentasinceros;
				$oCuenta1->sig_cuentacomp = $la_cuenta[$i]["cuenta"];
				$rsGrupo = $oCuenta1->LeerGrupo();
				while($rec =$rsGrupo->FetchRow())
				{
					$oCuenta= new intGastosDao();
					$oCuenta->sig_cuenta = $rec["sig_cuenta"];
					$oCuenta->nivelCuenta=$this->nivel;	
					$resp = $oCuenta->Verificar();
					if($resp!=false)
					{
						$resp = $oCuenta->LeerSaldo();
						if($resp!=false)
						{		  						
							$datastore3[$id3]=$oCuenta->LeerSaldo();	
					  		$id3++;
						}
					}
				}
			}
  			
  				
  			if($i>3 && $i<=5)
  			{
				$oCuenta1 =  new planUnicoRe();
				$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["cuenta"]);
				$oCuenta1->sig_cuenta = $cuentasinceros;
				$oCuenta1->sig_cuentacomp = $la_cuenta[$i]["cuenta"];
				$rsGrupo = $oCuenta1->LeerGrupo();
				
				while($rec =$rsGrupo->FetchRow())
				{
					$oCuenta= new planIngreso();
					$oCuenta->sig_cuenta = $rec["sig_cuenta"];
					$oCuenta->nivelCuenta=$this->nivel;	
					$resp = $oCuenta->Verificar();
					if($resp!=false)
					{	
						$resp = $oCuenta->LeerSaldo();
						if($resp!=false)
						{		  			
							$datastore4[$id4]=$oCuenta->LeerSaldo();	
					  		$id4++;
						}
					}
				}	
  			}
  			
  			
			if($i>5 && $i<=9)
  			{					 	//echo $i;
				$oCuenta1 =  new planUnicoRe();
				$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["cuenta"]);
				$oCuenta1->sig_cuenta = $cuentasinceros;
				$oCuenta1->sig_cuentacomp = $la_cuenta[$i]["cuenta"];
				$rsGrupo = $oCuenta1->LeerGrupo();
				while($rec =$rsGrupo->FetchRow())
				{
					$oCuenta= new planIngreso();
					$oCuenta->sig_cuenta = $rec["sig_cuenta"];
					$oCuenta->nivelCuenta=$this->nivel;	
					$resp = $oCuenta->Verificar();
					if($resp!=false)
					{
						$resp = $oCuenta->LeerSaldo();
						if($resp!=false)
						{		  						
							$datastore5[$id5]=$oCuenta->LeerSaldo();	
					  		$id5++;
						}
					}
				}
  					
    		}
  			
  			if($i>=10 && $i<=13)
  			{
				$oCuenta1 =  new planUnicoRe();
				$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["cuenta"]);
				$oCuenta1->sig_cuenta = $cuentasinceros;
				$oCuenta1->sig_cuentacomp = $la_cuenta[$i]["cuenta"];
				$rsGrupo = $oCuenta1->LeerGrupo();
				while($rec =$rsGrupo->FetchRow())
				{
					$oCuenta= new intGastosDao();
					$oCuenta->sig_cuenta = $rec["sig_cuenta"];
					$oCuenta->nivelCuenta=$this->nivel;	
					$resp = $oCuenta->Verificar();
					if($resp!=false)
					{
						$resp = $oCuenta->LeerSaldo();
						if($resp!=false)
						{		  						
							$datastore6[$id6]=$oCuenta->LeerSaldo();	
					  		$id6++;
						}
					}
				}
  				 
  			}
  			
  			if($i>=14 && $i<=18)
  			{					 	//echo $i;
				$oCuenta1 =  new planUnicoRe();
				$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["cuenta"]);
				$oCuenta1->sig_cuenta = $cuentasinceros;
				$oCuenta1->sig_cuentacomp = $la_cuenta[$i]["cuenta"];
				$rsGrupo = $oCuenta1->LeerGrupo();
				while($rec =$rsGrupo->FetchRow())
				{
					$oCuenta= new intGastosDao();
					$oCuenta->sig_cuenta = $rec["sig_cuenta"];
					$oCuenta->nivelCuenta=$this->nivel;	
					$resp = $oCuenta->Verificar();
					if($resp!=false)
					{
						$resp = $oCuenta->LeerSaldo();
						if($resp!=false)
						{		  						
							$datastore7[$id7]=$oCuenta->LeerSaldo();	
					  		$id7++;
						}
					}
				}
  			}
   		}	 		

   	
   		$oCuenta=new intGastosDao();
		//$oCuenta->sig_cuenta="400000000";
		$datastore8[0]=$oCuenta->LeerSaldoEgEstRes();
		if($id0==0)
		{
			$datastore0[0] = $this->leerDatosPorDefecto();
		}
		if($id1==0)
		{
			$datastore1[0] = $this->leerDatosPorDefecto();
		}	
		if($id2==0)
		{
			$datastore2[0] = $this->leerDatosPorDefecto();
		}
		if($id3==0)
		{
			$datastore3[0] = $this->leerDatosPorDefecto();
		}
		if($id4==0)
		{
			$datastore4[0] = $this->leerDatosPorDefecto();
		}
		if($id5==0)
		{
			$datastore5[0] = $this->leerDatosPorDefecto();
		}
		if($id6==0)
		{
			$datastore6[0] = $this->leerDatosPorDefecto();
		}
		
		if($id7==0)
		{
			$datastore7[0] = $this->leerDatosPorDefecto();
		}
		
   		$arrDatos["datos0"]=$datastore0;
		$arrDatos["datos1"]=$datastore1;
		$arrDatos["datos2"]=$datastore2;
		$arrDatos["datos3"]=$datastore3;
		$arrDatos["datos4"]=$datastore4;
		$arrDatos["datos5"]=$datastore5;
		$arrDatos["datos6"]=$datastore6;
		$arrDatos["datos7"]=$datastore7;
		$arrDatos["datos8"]=$datastore8;
  		return  $arrDatos;
		

}//fin uf_spg_reportes_estado2



function reporte_cuenta_ahorro_inversion()
{////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :  reporte_cuenta_ahorro_inversion
	 //     Argumentos : adt_fecdes ... adt_fechas  // rango de fecha del reporte
	 //                  adts_datastore  // datastore que imprime el reporte
     //	       Returns : Retorna true o false si se realizo la consulta para el reporte
	 //	   Description : Reporte que genera salida del Presupuesto de Caja
	 //     Creado por : Ing. Arnaldo Suárez
	 // Fecha Creación : 18/06/2008                       Fecha última Modificacion :      Hora :
  	 ///////////////////////////////////////////////////////////////////////////////////////////////////////

		$la_cuenta[118]=array();
		// ----> 1.  INGRESOS CORRIENTES
	//	$la_cuenta[0]["cuenta"]='300000000'.$ls_ceros;
		//$la_cuenta[0]["detalles"]=Array('302000000','303000000','304000000','305000000');
		
		//$la_cuenta[1]["cuenta"]='305000000'.$ls_ceros;
		$la_cuenta[2]["cuenta"]='305010000'.$ls_ceros;
		$la_cuenta[3]["cuenta"]='305010100'.$ls_ceros;
		$la_cuenta[3]["titulo"]='Del sector privado'.$ls_ceros;
		$la_cuenta[3]["detalles"]=Array('305010101','305010102','305010103');
		$la_cuenta[4]["cuenta"]='305010200'.$ls_ceros;
		$la_cuenta[4]["detalles"]=Array('305010201','305010201','305010201');
		
		$la_cuenta[5]["cuenta"]='305010300'.$ls_ceros;
		$la_cuenta[5]["detalles"]='305010300'.$ls_ceros;
		$la_cuenta[6]["cuenta"]='305010301'.$ls_ceros;
		$la_cuenta[7]["cuenta"]='305010302'.$ls_ceros;
		$la_cuenta[8]["cuenta"]='305010303'.$ls_ceros;
		$la_cuenta[9]["cuenta"]='305010304'.$ls_ceros;
		$la_cuenta[10]["cuenta"]='305010305'.$ls_ceros;
		$la_cuenta[11]["cuenta"]='305010306'.$ls_ceros;
		$la_cuenta[12]["cuenta"]='305010307'.$ls_ceros;
		$la_cuenta[13]["cuenta"]='305010308'.$ls_ceros;
		$la_cuenta[14]["cuenta"]='305010309'.$ls_ceros;
		$la_cuenta[15]["cuenta"]='305010400'.$ls_ceros;
		$la_cuenta[16]["cuenta"]='305010401'.$ls_ceros;
		$la_cuenta[17]["cuenta"]='305010402'.$ls_ceros;
		$la_cuenta[18]["cuenta"]='305010403'.$ls_ceros;
		$la_cuenta[19]["cuenta"]='305010404'.$ls_ceros;
		$la_cuenta[20]["cuenta"]='305010405'.$ls_ceros;
		$la_cuenta[21]["cuenta"]='305010406'.$ls_ceros;
		$la_cuenta[22]["cuenta"]='305010407'.$ls_ceros;
		$la_cuenta[23]["cuenta"]='305010408'.$ls_ceros;
		$la_cuenta[24]["cuenta"]='305010409'.$ls_ceros;
		$la_cuenta[25]["cuenta"]='305010500'.$ls_ceros;
		$la_cuenta[25]["cuenta"]='305010500'.$ls_ceros;
		$la_cuenta[25]["detalles"]=Array('305010501','305010502','305010503');
		$la_cuenta[26]["cuenta"]='305010600'.$ls_ceros;
		$la_cuenta[26]["detalles"]=Array('305010601','305010602','305010603','305010604');
		// ---> b. Ingresos por Actividades Propias
		
		/*
		$la_cuenta[27]["cuenta"]='301030000'.$ls_ceros;
		$la_cuenta[28]["detalles"]=Array('301030100','301030200','301030300','301030400','301030500','301030600','301030700','301030800','301030900','301031000','301031100','301031200','301031300','301031400','301031500','301031600','301031700','301031800','301031900','301032000','301032100','301032200','301032300','301032400','301032500','301032600','301032700','301032800','301032900','301033000','301033100','301033200','301033300','301033400','301033500','301033600','301033700','301033800','301033900','301034000','301034100','301034200','301034300','301034400','301034500','301034600','301034700','301034800','301034900','301039900');
		$la_cuenta[29]["cuenta"]='303000000'.$ls_ceros;
		$la_cuenta[30]["detalles"]=Array('303030000','303990000',);
		*/
		/*$la_cuenta[27]["cuenta"]='301090000'.$ls_ceros;
		$la_cuenta[28]["cuenta"]='301090100'.$ls_ceros;
		$la_cuenta[29]["cuenta"]='301090200'.$ls_ceros;
		$la_cuenta[30]["cuenta"]='301099900'.$ls_ceros;
		$la_cuenta[31]["cuenta"]='408070000'.$ls_ceros; // ---> Menos Descuentos, Bonificaciones y Devoluciones
		$la_cuenta[31]["detalles"]=Array('408070100','408070200','408070300');
       */ 
		// ---> Ventas Netas
		$la_cuenta[27]["cuenta"]='301030000'.$ls_ceros;
		$la_cuenta[28]["cuenta"]='301040000'.$ls_ceros;
		$la_cuenta[29]["cuenta"]='301050000'.$ls_ceros;
		
		
		$la_cuenta[30]["cuenta"]='301090000'.$ls_ceros;
		$la_cuenta[30]["detalles"]=Array('301090100','301090200','301099900');
		
		
		$la_cuenta[31]["cuenta"]='408070000'.$ls_ceros; // ---> Menos Descuentos, Bonificaciones y Devoluciones
		$la_cuenta[31]["detalles"]=Array('408070100','408070200','408070300');
		
		$la_cuenta[32]["cuenta"]='303000000'.$ls_ceros;
		$la_cuenta[32]["detalles"]=Array('303030000','303990000','301000000','301100100','301100501','301100400','301100300','301100300','301100503','301100600','301100601','301100801','301101000','301100802','301100900');
		$la_cuenta[33]["detalles"]=Array('304990000');
		$la_cuenta[33]["detalles"]=Array('301110000','301991100');
        // gastos Ingresos Corrientes
	/*	$la_cuenta[34]["cuenta"]='301040000'.$ls_ceros;
		$la_cuenta[35]["cuenta"]='301050000'.$ls_ceros;
		$la_cuenta[36]["cuenta"]='301100000'.$ls_ceros;
		$la_cuenta[36]["detalles"]=Array('301110100','301110200','301110300','301110400','301110401','301110402','301110403','301110404','301110405','301110406','301110500','301110600','301110700','301110800','301110900','301111000','301111000');
		$la_cuenta[37]["cuenta"]='302030000'.$ls_ceros;
		$la_cuenta[38]["cuenta"]='302040000'.$ls_ceros;
		$la_cuenta[39]["cuenta"]='302050000'.$ls_ceros;*/
		// ---> 2. GASTOS CORRIENTES
		// ---> a. Gastos de Consumo
		$la_cuenta[34]["cuenta"]='401000000'.$ls_ceros;
		$la_cuenta[34]["detalles"]=Array('401010000','401040000','401060000','401080000','401070000','401900000');
		$la_cuenta[35]["cuenta"]='402000000'.$ls_ceros;
		$la_cuenta[36]["cuenta"]='403000000'.$ls_ceros;
		// ---> Variación de Inventarios (Detallar)
		$la_cuenta[37]["cuenta"]='403180000'.$ls_ceros;
		$la_cuenta[44]["cuenta"]='408010000'.$ls_ceros;
		$la_cuenta[45]["cuenta"]='408060000'.$ls_ceros;
		$la_cuenta[46]["cuenta"]='408020000'.$ls_ceros;
		$la_cuenta[46]["detalles"]=Array('408020100','408020200','408020300');
		// ---> b. Otros Gastos Corrientes
		$la_cuenta[47]["cuenta"]='403010300'.$ls_ceros;
		$la_cuenta[48]["cuenta"]='403030000'.$ls_ceros;
		$la_cuenta[49]["cuenta"]='408060200'.$ls_ceros;
		$la_cuenta[49]["detalles"]=Array('408060200','408060300','408060500');
		$la_cuenta[49]["titulo"]='Otros Gastos Corrientes'.$ls_ceros;
		$la_cuenta[50]["cuenta"]='408050000'.$ls_ceros;
		$la_cuenta[50]["detalles"]=Array('408050100','408050200','408050300');
		$la_cuenta[51]["cuenta"]='408060000ee'.$ls_ceros;
		$la_cuenta[52]["cuenta"]='408080000'.$ls_ceros;
		$la_cuenta[52]["detalles"]=Array('408080102','408080202','408990000');	
		$la_cuenta[53]["cuenta"]='407010000'.$ls_ceros;
		$la_cuenta[53]["detalles"]=Array('407010100','407010101','407010102','407010199','407010200','407010201','407010202','407010300','407010301','407010302','407010303','407010304','407010305','407010306','407010307','407010308','407010309','407010310','407010311','407010312','407010313','407010400','407010401','407010402','407010403','407010404','407010405','407010406','407010407','407010408','407010409');
		$la_cuenta[54]["cuenta"]='407020000'.$ls_ceros;
		$la_cuenta[54]["detalles"]=Array('407020100','407020101','407020102','407020103','407020104','407020200','407020201','407020202','407020203','407020204');
		
		$la_cuenta[55]["cuenta"]='306000000'.$ls_ceros;
		$la_cuenta[56]["detalles"]=Array('ahorro','306010000','306010000','306010200','306010100','306020000','306030000');
		$la_cuenta[57]["cuenta"]='305020000';
		$la_cuenta[57]["detalles"]=Array('305020000','305020100','305020101','305020102','305020103','305020300','305020301','305020302','305020303','305020304','305020305','305020306','305020307','305020308','305020309','305020400','305020401','305020402','305020403','305020404','305020405','305020406','305020407','305020408','305020409','305020500','305020501','305020502','305020503','305020600','305020601','305020602','305020603','305020604');
		$la_cuenta[58]["cuenta"]='308010000'.$ls_ceros;
		$la_cuenta[59]["cuenta"]='308020000'.$ls_ceros;
		$la_cuenta[59]["detalles"]=Array('308020100','308020200','308020300','308020400','308020500','308020600');
		$la_cuenta[60]["cuenta"]='308030000';	
		$la_cuenta[60]["detalles"]=Array('308030100','308039900');
		$la_cuenta[61]["cuenta"]='309010100';
		$la_cuenta[62]["cuenta"]='309020100';
		$la_cuenta[62]["detalles"]=Array('309020100','309020200','309020300','309020400','309020500','309020600','309020700','309020800','309020900');
		$la_cuenta[63]["cuenta"]='309030000';
		$la_cuenta[63]["detalles"]=Array('309030100','309030200','309030300');
		$la_cuenta[64]["cuenta"]='310010100';	
		$la_cuenta[65]["cuenta"]='310020000';
		$la_cuenta[65]["detalles"]=Array('310020100','310020200','310020300','310020400','310020500','310020600','310020700','310020800','310020900');
		$la_cuenta[66]["cuenta"]='310030000';
		$la_cuenta[66]["detalles"]=Array('310030100','310030200','310030300');
		$la_cuenta[67]["cuenta"]='404090000'.$ls_ceros;
		$la_cuenta[68]["cuenta"]='404150000';
		$la_cuenta[69]["cuenta"]='404160000'.$ls_ceros;
		$la_cuenta[70]["cuenta"]='401010000';	
		$la_cuenta[71]["cuenta"]='401040000';
		$la_cuenta[72]["cuenta"]='401060000';
		$la_cuenta[73]["cuenta"]='401080000';
		$la_cuenta[74]["cuenta"]='401070000';
		$la_cuenta[74]["detalles"]=Array('401900000');
		$la_cuenta[75]["cuenta"]='403990000';
		$la_cuenta[76]["cuenta"]='403180000';
		$la_cuenta[77]["cuenta"]='403010300';
		
		$la_cuenta[78]["cuenta"]='407030100';
		$la_cuenta[78]["detalles"]=Array('407030101','407030102','407030103','407030200','407030201','407030202');
		$la_cuenta[79]["cuenta"]='407030300';
		$la_cuenta[80]["cuenta"]=Array('407030301','407030302','407030303','407030304','407030305','407030306','407030307','407030308','407030309');
		$la_cuenta[81]["cuenta"]='407030400';
		$la_cuenta[82]["cuenta"]=Array('407030401','407030402','407030403','407030404','407030405','407030406','407030407','407030408','407030409');
		$la_cuenta[83]["cuenta"]='407040100';
		$la_cuenta[84]["cuenta"]=Array('407040101','407040102','407040103','407040104','407040200','407040201','407040202','407040203','407040204');
		$la_cuenta[85]["cuenta"]='405010000';
		$la_cuenta[86]["cuenta"]='405010100';
		$la_cuenta[86]["cuenta"]='405010200';
		$la_cuenta[86]["detalles"]=Array('405010201','405010202','405010203','405010204','405010205','405010206','405010207');
		
		$la_cuenta[87]["cuenta"]='405010300';
		$la_cuenta[87]["cuenta"]=Array('405010301','405010399');
		$la_cuenta[88]["cuenta"]='405030000';
		$la_cuenta[90]["cuenta"]=Array('405030100','405030200','405030201','405030202','405030203','405030204','405030205','405030206','405030207','405030208','405030209','405030300','405030301','405030302','405030303');
		$la_cuenta[91]["cuenta"]='405040000';
		$la_cuenta[92]["detalles"]=Array('405040100','405040200','405040201','405040200','405040201','405040202','405040203','405040204','405040205','405040206','405040207','405040208','405040209','405040300','405040301','405040302','405040303');
		
		//ingresos financieros
		
		$la_cuenta[93]["cuenta"]='307000000';
		$la_cuenta[93]["detalles"]=Array('307010000','307010100','307010200','307010300','307020100','307020000','307020100','307020200');
		$la_cuenta[94]["cuenta"]='311000000';
		$la_cuenta[94]["detalles"]=Array('311010000','311010100','311010200','311010300','311020000','311020100','311029900','311030000','311030100','311039900','311040000','311040100','311040200','311049900','311050000','311050100','311059900','311060000','311060300','311060400','311060500','311060600','311070000','311080000','311200000','311990100','311990200');
		$la_cuenta[95]["cuenta"]='312000000';
		$la_cuenta[95]["detalles"]=Array('312010100','312020000','312030100','312030200','312040100','312040200','312050000','312050200','312030300','312030400','312040300','312040400','312060100','312060200','312060300','312100000','312100100','312100200','312070100','312070201','312070202','312070203','312080100','312080101','312080102','312080103','312080104','312080199','312080200','312090100','312099900','312090100','312090200');
		$la_cuenta[96]["cuenta"]='313010000';
		$la_cuenta[96]["detalles"]=Array('313010100','313010200','313020100','313040100','313040200');
		
		//aplicaciones financieras
		
		$la_cuenta[97]["cuenta"]='405020000';
		$la_cuenta[98]["cuenta"]='405020100';
		$la_cuenta[98]["detalles"]=Array('405020101','405020102','405020103','405020200','405020201','405020202','405020203');
		$la_cuenta[99]["cuenta"]='405050000';
		$la_cuenta[100]["cuenta"]='405050000ee';
		$la_cuenta[100]["detalles"]=Array('405050100','405050200','405050300');
		$la_cuenta[101]["cuenta"]='405060000';
		$la_cuenta[101]["detalles"]=Array('405060100','405060200','405060300','405060301','405060302','405069900');
		$la_cuenta[102]["cuenta"]='405070000';
		$la_cuenta[102]["detalles"]=Array('405070100','405079900');	
		
		
		$la_cuenta[103]["cuenta"]='405070000';
		$la_cuenta[103]["detalles"]=Array('405060100','405060200','405060300','405060301','405060302','405069900');
		$la_cuenta[104]["cuenta"]='405070000';
		$la_cuenta[104]["detalles"]=Array('405070100','405079900');		
		$la_cuenta[105]["cuenta"]='405080000';
		$la_cuenta[105]["detalles"]=Array('405080100','405080200','405089900');
		
		
		$la_cuenta[106]["cuenta"]='405090000';
		$la_cuenta[106]["detalles"]=Array('405090100','405099900','405060300','405060301','405060302','405069900');
		$la_cuenta[107]["cuenta"]='405100000';
		$la_cuenta[107]["detalles"]=Array('405100000','405100300','405100400','405100500','405100600');		
		$la_cuenta[108]["cuenta"]='405110000';
		$la_cuenta[109]["cuenta"]='405120000';
		$la_cuenta[110]["cuenta"]='405200000';
		$la_cuenta[111]["cuenta"]='405210000';
		$la_cuenta[112]["cuenta"]='405990000';
		$la_cuenta[113]["cuenta"]='411000000';
		$la_cuenta[113]["detalles"]=Array('411030000','411010100','411020000','411030100','411030200','411040100','411040200','411050000','411050100','411050200');		
		$la_cuenta[114]["cuenta"]='411030300';
		$la_cuenta[114]["detalles"]=Array('411030400','411040300','411040400','411060000','411060100','411060200','411060300','411100000','411100100','411100200');
		$la_cuenta[115]["cuenta"]='411070100';
		$la_cuenta[115]["detalles"]=Array('411070200','411070201','411070202','411070203');
		$la_cuenta[116]["cuenta"]='411080000';
		$la_cuenta[116]["detalles"]=Array('411080100','411080101','411080102','411080103','411080104','411080199','411080200');
		$la_cuenta[117]["cuenta"]='411090000';
		$la_cuenta[117]["detalles"]=Array('411090100','411099900','411980100','411990100');
		$la_cuenta[118]["cuenta"]='412000000';
		$la_cuenta[118]["detalles"]=Array('412010000','412010100','412010200','412020000','412020100','412030000','412030100','412040000','412040100','412040200');
		$datastore0=array();
	    $id0=0;
		$datastore1=array();
	    $id1=0;
	    $datastore2=array();
	    $id2=0;
	    $datastore3=array();
	    $id3=0;
	    $datastore4=array();
	    $id4=0;
	    $datastore5=array();
	    $id5=0;
	    $datastore6=array();
	    $id6=0;
	    $datastore7=array();
	    $id7=0;
	    $datastore8=array();
	    $id8=0;
		for($i=2;$i<=118;$i++)
		{
			if($i>=2 && $i<=30  && $i!=31)
			{
				$oCuenta= new planIngreso();
				$oCuenta->sig_cuenta = trim($la_cuenta[$i]["cuenta"]);
				$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["cuenta"]);
		  		$resp = $oCuenta->tieneMovimiento2($cuentasinceros);
				if($resp!=false)
				{
					$datastore0[$id0]=$oCuenta->LeerSaldocaif();	
			  		$id0++;
				}
				if(is_array($la_cuenta[$i]["detalles"]))
  				{
  					for($j=0;$j<count($la_cuenta[$i]["detalles"]);$j++)
  					{
    					$oCuenta=new planIngreso();
  						$oCuenta->sig_cuenta = trim($la_cuenta[$i]["detalles"][$j]);
						$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["detalles"][$j]);
		  				$resp = $oCuenta->tieneMovimiento2($cuentasinceros);		  				
		  				if($resp!=false)
						{		  						
	  						$datastore0[$id0]=$oCuenta->LeerSaldocaif();
	  						$id0++;
						}
					}
  				}
			}

			if($i>=32 && $i<=33)
			{
					$oCuenta= new planIngreso();
				   	$oCuenta->sig_cuenta = trim($la_cuenta[$i]["cuenta"]);
					$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["cuenta"]);
		  			$resp = $oCuenta->tieneMovimiento2($cuentasinceros);
		  			if($resp!=false)
					{	  						
						$datastore2[$id2]=$oCuenta->LeerSaldocaif();	
				  		$id2++;
					}
				if(is_array($la_cuenta[$i]["detalles"]))
  				{
  					for($j=0;$j<count($la_cuenta[$i]["detalles"]);$j++)
  					{	
  						$oCuenta=new planIngreso();
  						$oCuenta->sig_cuenta=trim($la_cuenta[$i]["detalles"][$j]);	
  						$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["detalles"][$j]);
  						$resp = $oCuenta->tieneMovimiento2($cuentasinceros);
						if($resp!=false)
						{		  						
	  						$datastore2[$id2]=$oCuenta->LeerSaldocaif();
	  						$id2++;
						}
					}
  				}
			}		
			

			if($i>=34 && $i<=54)
			{
				if($i==38)
					$i=44;
				$oCuenta= new intGastosDao();
				//$oCuenta->sig_cuenta = $la_cuenta[$i]["cuenta"];
				$oCuenta->sig_cuenta = trim($la_cuenta[$i]["cuenta"]);
				$oCuenta->nat_gasto = "co";
				$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["cuenta"]);
				
					
				$resp = $oCuenta->tieneMovimiento2($cuentasinceros);
				
				if($resp!=false)
				{		  		
					$datastore1[$id1]=$oCuenta->LeerSaldocaif();
					//if($cuentasinceros=="402")
					//	ver($datastore1[$id1]);
					$id1++;
				}
				if(is_array($la_cuenta[$i]["detalles"]))
  				{
  					for($j=0;$j<count($la_cuenta[$i]["detalles"]);$j++)
  					{	
  						$oCuenta=new intGastosDao();
  						$oCuenta->nat_gasto = "co";
  						$oCuenta->sig_cuenta=trim($la_cuenta[$i]["detalles"][$j]);	
  						$cuentasinceros=uf_spg_cuenta_sin_cero($oCuenta->sig_cuenta);		
  						$resp = $oCuenta->tieneMovimiento2($cuentasinceros);
  						if($resp!=false)
						{		  	
						//	if($oCuenta->sig_cuenta=='402000000')
						//		ver($oCuenta->sig_cuenta);
							
	  						$datastore1[$id1]=$oCuenta->LeerSaldocaif();
	  						$id1++;
						}
					}
  				}
			}

			
			if($i==31) 
			{
				$oCuenta= new intGastosDao();
				$oCuenta->sig_cuenta = trim($la_cuenta[$i]["cuenta"]);
		  		$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["cuenta"]);
				$resp = $oCuenta->tieneMovimiento2($cuentasinceros);
		  		
				if($resp!=false)
				{		  						
					
					$datastore3[$id3]=$oCuenta->LeerSaldocaif();	
			  		$id3++;
				}
				if(is_array($la_cuenta[$i]["detalles"]))
  				{
  					for($j=0;$j<count($la_cuenta[$i]["detalles"]);$j++)
  					{	
  						$oCuenta=new intGastosDao();
  						$oCuenta->sig_cuenta=trim($la_cuenta[$i]["detalles"][$j]);	
  						$cuentasinceros=uf_spg_cuenta_sin_cero($oCuenta->sig_cuenta);		
  						$resp = $oCuenta->tieneMovimiento2($cuentasinceros);				
  						if($resp!=false)
						{		  						
	  						$datastore3[$id3]=$oCuenta->LeerSaldocaif();
	  						$id3++;
						}
					}
  				}
			}				
			
			if($i>=55 && $i<=66)
			{
				$oCuenta= new planIngreso();
				$oCuenta->sig_cuenta = trim($la_cuenta[$i]["cuenta"]);
		  		$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["cuenta"]);
				$resp = $oCuenta->tieneMovimiento2($cuentasinceros);			
				if($resp!=false)
				{		  						
					$datastore4[$id4]=$oCuenta->LeerSaldocaif();	
			  		$id4++;
				}
				if(is_array($la_cuenta[$i]["detalles"]))
  				{
  					for($j=0;$j<count($la_cuenta[$i]["detalles"]);$j++)
  					{	
  						if($la_cuenta[$i]["detalles"][$j]=='ahorro')
  						{
  							$datastore4[$id4]=$oCuenta->LeerAhorroDes();
	  						$id4++;
	  						$j++;
  							
  						}
  						$oCuenta=new planIngreso();
  						$oCuenta->sig_cuenta=trim($la_cuenta[$i]["detalles"][$j]);	
  						$cuentasinceros=uf_spg_cuenta_sin_cero($oCuenta->sig_cuenta);		
  						$resp = $oCuenta->tieneMovimiento2($cuentasinceros);		
						if($resp!=false)
						{		  						
	  						$datastore4[$id4]=$oCuenta->LeerSaldocaif();
	  						$id4++;
						}
					}
  				}
			}		

			
			if($i>=67 && $i<=92)
			{
				if($i==89)
					$i=90;
				$oCuenta= new intGastosDao();
				$oCuenta->sig_cuenta = trim($la_cuenta[$i]["cuenta"]);
				$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["cuenta"]);
				$oCuenta->nat_gasto = "ca";
				$resp = $oCuenta->tieneMovimiento2($cuentasinceros);
				if($resp!=false)
				{		  						
					$datastore5[$id5]=$oCuenta->LeerSaldocaif();	
			  		$id5++;
			  			//var_dump($i."sdaddfasdfsadf");
				//	var_dump($oCuenta->LeerSaldocaif());
				}
				if(is_array($la_cuenta[$i]["detalles"]))
  				{
  					for($j=0;$j<count($la_cuenta[$i]["detalles"]);$j++)
  					{	
  						$oCuenta=new intGastosDao();
  						$oCuenta->nat_gasto = "ca";
  						$oCuenta->sig_cuenta=trim($la_cuenta[$i]["detalles"][$j]);	
  						$cuentasinceros=uf_spg_cuenta_sin_cero($oCuenta->sig_cuenta);		
  						$resp = $oCuenta->tieneMovimiento2($cuentasinceros);		
						if($resp!=false)
						{		  						
	  						$datastore5[$id5]=$oCuenta->LeerSaldocaif();
	  						$id5++;
						}
					}
  				}
			}		
			if($i>=93 && $i<=96)
			{
				$oCuenta= new planIngreso();
				$oCuenta->sig_cuenta = trim($la_cuenta[$i]["cuenta"]);
				$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["cuenta"]);
				$resp = $oCuenta->tieneMovimiento2($cuentasinceros);
				if($resp!=false)
				{		  						
					$datastore6[$id6]=$oCuenta->LeerSaldocaifInversion();	
			  		$id6++;
				}
				if(is_array($la_cuenta[$i]["detalles"]))
  				{
  					for($j=0;$j<count($la_cuenta[$i]["detalles"]);$j++)
  					{	
  						$oCuenta=new planIngreso();
  						$oCuenta->sig_cuenta=trim($la_cuenta[$i]["detalles"][$j]);	
  						$cuentasinceros=uf_spg_cuenta_sin_cero($oCuenta->sig_cuenta);		
  						$resp = $oCuenta->tieneMovimiento2($cuentasinceros);	
						if($resp!=false)
						{		  						
	  						$datastore6[$id6]=$oCuenta->LeerSaldocaifInversion();
	  						$id6++;
						}
					}
  				}
			}		
			
			
			if($i>=97 && $i<=118)
			{
				
				$oCuenta= new intGastosDao();
				$oCuenta->sig_cuenta = trim($la_cuenta[$i]["cuenta"]);
				$oCuenta->nat_gasto = "co";
				$cuentasinceros=uf_spg_cuenta_sin_cero($la_cuenta[$i]["cuenta"]);
				$resp = $oCuenta->tieneMovimiento2($cuentasinceros);			
				if($resp!=false)
				{		  
					
					$datastore7[$id7]=$oCuenta->LeerSaldoAplicFinan();	
			  		$id7++;
				}
				if(is_array($la_cuenta[$i]["detalles"]))
  				{
  					for($j=0;$j<count($la_cuenta[$i]["detalles"]);$j++)
  					{	
  						$oCuenta=new intGastosDao();
  						$oCuenta->sig_cuenta=trim($la_cuenta[$i]["detalles"][$j]);	
  						$cuentasinceros=uf_spg_cuenta_sin_cero($oCuenta->sig_cuenta);		
  						$resp = $oCuenta->tieneMovimiento2($cuentasinceros);
						if($resp!=false)
						{		  						
	  						$datastore7[$id7]=$oCuenta->LeerSaldoAplicFinan();
	  						$id7++;
						}
					}
  				}
			}	
			
   		}	 		
   		
		if($id0==0)
		{
			$datastore0[0] = $this->leerDatosPorDefecto();
		}
		if($id1==0)
		{
			$datastore1[0] = $this->leerDatosPorDefecto();
		}	
		if($id2==0)
		{
			$datastore2[0] = $this->leerDatosPorDefecto();
		}
		if($id3==0)
		{
			$datastore3[0] = $this->leerDatosPorDefecto();
		}
		if($id4==0)
		{
			$datastore4[0] = $this->leerDatosPorDefecto();
		}
		if($id5==0)
		{
			$datastore5[0] = $this->leerDatosPorDefecto();
		}
		if($id6==0)
		{
			$datastore6[0] = $this->leerDatosPorDefecto();
		}
		
		if($id7==0)
		{
			$datastore7[0] = $this->leerDatosPorDefecto();
		}
//	ver($datastore0);
   		$arrDatos["datos0"]=$datastore0;
  		//$arrDatos["datos0"]=$datastore0;
		$arrDatos["datos1"]=$datastore1;
		$arrDatos["datos2"]=$datastore2;
		$arrDatos["datos3"]=$datastore3;
		$arrDatos["datos4"]=$datastore4;
		$arrDatos["datos5"]=$datastore5;
		$arrDatos["datos6"]=$datastore6;
		$arrDatos["datos7"]=$datastore7;
		$arrDatos["datos8"]=$datastore8;
  		return  $arrDatos;
		

}//fin uf_spg_reportes_presupuesto_de_caja





	function uf_spi_reportes_ingresos_corrientes()
    {////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function : uf_spi_reportes_ingresos_corrientes
	 //     Argumentos : adt_fecdes ... adt_fechas  // rango de fecha del reporte
	 //                  adts_datastore  // datastore que imprime el reporte
     //	       Returns : Retorna true o false si se realizo la consulta para el reporte
	 //	   Description : Reporte que genera salida del PResupuesto de Caja
	 //     Creado por : Ing. Arnaldo Suarez
	 // Fecha Creación : 18/06/2008                       Fecha última Modificacion :      Hora :
  	 ///////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=true;
	  $ls_sql=" SELECT max(spi_cuenta) as spi_cuenta,max(denominacion) as denominacion,max(status) as status,        ".
              "        sum(previsto) as previsto, sum(cobrado_anticipado) as cobrado_anticipado,         			".
              "        sum(cobrado) as cobrado, sum(devengado) as devengado, sum(aumento) as aumento,    			".
              "        sum(disminucion) as disminucion, sum(enero) as enero , sum(febrero) as febrero ,  			".
              "        sum(marzo) as marzo, sum(abril) as abril, sum(mayo) as mayo, sum(junio) as junio, 			".
              "        sum(julio) as julio, sum(agosto) as agosto, sum(septiembre) as septiembre,        			".
              "        sum(octubre) as octubre, sum(noviembre) as noviembre,                             			".
			  "        sum(diciembre) as diciembre,                                                      			".
			  "        sum(enero+febrero+marzo) as trimetrei, sum(abril+mayo+junio) as trimetreii,       			".
			  "        sum(julio+agosto+septiembre) as trimetreiii,                                     		 	".
			  "        sum(octubre+noviembre+diciembre) as trimetreiv                                    			".
	  	      " FROM   spi_cuentas ".
			  " WHERE  codemp='".$this->ls_codemp."' AND ".
			  "        spi_cuenta like '305010000%' OR spi_cuenta like '305010100%' OR spi_cuenta like '305010300%' ".
			  "        OR spi_cuenta like '305010301%' OR".
			  "        spi_cuenta like '305010302%' OR spi_cuenta like '305010303%' OR spi_cuenta like '305010304%' OR ".
			  "        spi_cuenta like '305010305%' OR spi_cuenta like '305010306%' OR spi_cuenta like '305010307%' OR ".
			  "        spi_cuenta like '305010308%' OR spi_cuenta like '305010309%' OR spi_cuenta like '305010500%' OR ".
			  "        spi_cuenta like '305010501%' OR spi_cuenta like '305010502%' OR spi_cuenta like '305010503%' OR ".
			  "        spi_cuenta like '301090000%' OR spi_cuenta like '301090100%' OR spi_cuenta like '301090200%' OR ".
			  "        spi_cuenta like '301099900%' ";  	  
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  { // error interno sql
		$this->io_mensajes->message("CLASE->sigesp_spg_reporte_pres_caja_07 ". 
			                        "MÉTODO->uf_spi_reportes_ingresos_corrientes ".
									"ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		$lb_valido = false;
 	  }
	  else
	  {
		$li_numrows=$this->io_sql->num_rows($rs_data);	
		if($li_numrows>=0)
		{
		     while($row=$this->io_sql->fetch_row($rs_data))
			 {
			   $ls_spi_cuenta=$row["spi_cuenta"];
			   $ls_denominacion=$row["denominacion"];
			   $ls_status=$row["status"];
			   $ld_previsto=$row["previsto"];
			   $ld_cobrado_total=$row["cobrado"];
			   $ld_devengado_total=$row["devengado"];
			   $ld_aumento_total=$row["aumento"];
			   $ld_disminucion_total=$row["disminucion"];
			   $ld_enero=$row["enero"];
			   $ld_febrero=$row["febrero"];
			   $ld_marzo=$row["marzo"];
			   $ld_abril=$row["abril"];
			   $ld_mayo=$row["mayo"];
			   $ld_junio=$row["junio"];
			   $ld_julio=$row["julio"];
			   $ld_agosto=$row["agosto"];
			   $ld_septiembre=$row["septiembre"];
			   $ld_octubre=$row["octubre"];
			   $ld_noviembre=$row["noviembre"];
			   $ld_diciembre=$row["diciembre"];
			   $ld_trimetreI=$row["trimetrei"]; 
			   $ld_trimetreII=$row["trimetreii"]; 
			   $ld_trimetreIII=$row["trimetreiii"]; 
			   $ld_trimetreIV=$row["trimetreiv"]; 
			   $ls_spi_cuenta = substr($ls_spi_cuenta,0,9);
			   $ls_detallar=true; 

			   $lb_valido=$this->uf_spi_ejecutado_trimestral($ls_spi_cuenta,$adt_fecdes,$adt_fechas,&$ld_cobrado_anticipado,
			                                                 &$ld_cobrado,&$ld_devengado,&$ld_aumento,&$ld_disminucion,
															 $ls_detallar);
			   if($lb_valido)
		       {
				   $lb_valido=$this->uf_spi_ejecutado_acumulado($ls_spi_cuenta,$adt_fechas,&$ld_cobrado_anticipado_acumulado,
																&$ld_cobrado_acumulado,&$ld_devengado_acumulado,
																&$ld_aumento_acumulado,&$ld_disminucion_acumulado,
																$ls_detallar);
			   }//if
			   if($as_mesdes=='Enero')
		       {
				   $ld_programado_trimestral=$ld_trimetreI;
				   $ld_programado_acumulado=$ld_trimetreI;
			   }//if
			   if($as_mesdes=='Abril')
		       {
				   $ld_programado_trimestral=$ld_trimetreII;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII;
			   }//if
			   if($as_mesdes=='Junio')
		       {
				   $ld_programado_trimestral=$ld_trimetreIII;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII+$ld_trimetreIII;
			   }//if
			   if($as_mesdes=='Octubre')
		       {
				   $ld_programado_trimestral=$ld_trimetreIV;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII+$ld_trimetreIII+$ld_trimetreIV;
			   }//if
			   $ld_previsto_modificado=$ld_previsto+$ld_aumento-$ld_disminucion;
			   if ($ld_programado_trimestral > 0)
			   {
			    	$ld_porcentual =($ld_cobrado/$ld_programado_trimestral)*100;
			   }
			   else
			   {
			    	$ld_porcentual=0;
			   }
			   
			   $this->dts_ingresos_corrientes->insertRow("cuenta",$ls_spi_cuenta);
			   $this->dts_ingresos_corrientes->insertRow("denominacion",$ls_denominacion);
			   $this->dts_ingresos_corrientes->insertRow("asignado",$ld_previsto);
			   $this->dts_ingresos_corrientes->insertRow("modificado",$ld_previsto_modificado);
			   $this->dts_ingresos_corrientes->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_ingresos_corrientes->insertRow("ejecutado",$ld_cobrado);		
		  	   $this->dts_ingresos_corrientes->insertRow("absoluto",abs($ld_cobrado-$ld_programado_trimestral));		
		  	   $this->dts_ingresos_corrientes->insertRow("porcentual", $ld_porcentual);		
			   $this->dts_ingresos_corrientes->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_ingresos_corrientes->insertRow("ejecutado_acumulado",$ld_cobrado_acumulado);
			   $this->dts_ingresos_corrientes->insertRow("status",$ls_status);
			   /// datastore  del reportes
			   $this->dts_reporte_temporal->insertRow("cuenta",$ls_spi_cuenta);
			   $this->dts_reporte_temporal->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte_temporal->insertRow("asignado",$ld_previsto);
			   $this->dts_reporte_temporal->insertRow("modificado",$ld_previsto_modificado);
			   $this->dts_reporte_temporal->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado",$ld_cobrado);		
		  	   $this->dts_reporte_temporal->insertRow("absoluto",abs($ld_cobrado-$ld_programado_trimestral));		
		  	   $this->dts_reporte_temporal->insertRow("porcentual", $ld_porcentual);		
			   $this->dts_reporte_temporal->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado_acumulado",$ld_cobrado_acumulado);
			   $lb_valido=true;
		    }
		    //while
	    }//if	
	    $this->io_sql->free_result($rs_data);
	  }//else
	  
	   $ls_sql=" SELECT spg_cuenta, max(denominacion) as denominacion, max(status) as status 
	   , sum(asignado) as asignado, ".
              "        sum(precomprometido) as precomprometido, sum(comprometido) as comprometido,       ".
              "        sum(causado) as causado, sum(pagado) as pagado, sum(aumento) as aumento,          ".
              "        sum(disminucion) as disminucion, sum(enero) as enero , sum(febrero) as febrero ,  ".
              "        sum(marzo) as marzo, sum(abril) as abril, sum(mayo) as mayo, sum(junio) as junio, ".
              "        sum(julio) as julio, sum(agosto) as agosto, sum(septiembre) as septiembre,        ".
              "        sum(octubre) as octubre, sum(noviembre) as noviembre,                             ".
			  "        sum(diciembre) as diciembre,                                                      ".
			  "        sum(enero+febrero+marzo) as trimetrei, sum(abril+mayo+junio) as trimetreii,       ".
			  "        sum(julio+agosto+septiembre) as trimetreiii,                                      ".
			  "        sum(octubre+noviembre+diciembre) as trimetreiv                                    ".
	  	      " FROM   spg_cuentas ".
			  " WHERE  codemp='".$this->ls_codemp."' AND ".
			  "        spg_cuenta like '408070000%' ".
			  " GROUP BY spg_cuenta".
			  " ORDER BY spg_cuenta ";	  
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  { // error interno sql
		$this->io_mensajes->message("CLASE->sigesp_spg_reporte_pres_caja_07 ". 
			                        "MÉTODO->uf_spi_reportes_ingresos_corrientes ".
									"ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		$lb_valido = false;
 	  }
	  else
	  {
		$li_numrows=$this->io_sql->num_rows($rs_data);	
		if($li_numrows>=0)
		{
		     while($row=$this->io_sql->fetch_row($rs_data))
			 {
			   $ls_spg_cuenta=$row["spg_cuenta"];
			   $ls_spg_cuenta = substr($ls_spg_cuenta,0,9);
			   $ls_denominacion=$row["denominacion"];
			   $ls_status=$row["status"];
			   $ld_asignado=$row["asignado"];
			   $ld_comprometido_total=$row["comprometido"];
			   $ld_causado_total=$row["causado"];
			   $ld_pagado_total=$row["pagado"];
			   $ld_aumento_total=$row["aumento"];
			   $ld_disminucion_total=$row["disminucion"];
			   $ld_enero=$row["enero"];
			   $ld_febrero=$row["febrero"];
			   $ld_marzo=$row["marzo"];
			   $ld_abril=$row["abril"];
			   $ld_mayo=$row["mayo"];
			   $ld_junio=$row["junio"];
			   $ld_julio=$row["julio"];
			   $ld_agosto=$row["agosto"];
			   $ld_septiembre=$row["septiembre"];
			   $ld_octubre=$row["octubre"];
			   $ld_noviembre=$row["noviembre"];
			   $ld_diciembre=$row["diciembre"];
			   $ld_trimetreI=$row["trimetrei"]; 
			   $ld_trimetreII=$row["trimetreii"]; 
			   $ld_trimetreIII=$row["trimetreiii"]; 
			   $ld_trimetreIV=$row["trimetreiv"]; 
			   $ls_detallar=false; 
			   $lb_valido=$this->uf_spg_ejecutado_trimestral_estado_resultado($ls_spg_cuenta,$adt_fecdes,$adt_fechas,
			                                                                  &$ld_comprometer,&$ld_causado,&$ld_pagado,
																			  &$ld_aumento,&$ld_disminucion,$ls_detallar);
			   if($lb_valido)
		       {
				   $lb_valido=$this->uf_spg_ejecutado_acumulado_estado_resultado($ls_spg_cuenta,$adt_fechas,&$ld_comprometer_acumulado,
				                                                                 &$ld_causado_acumulado,&$ld_pagado_acumulado,
																				 &$ld_aumento_acumulado,&$ld_disminucion_acumulado,
																                 $ls_detallar);
			   }//if
			   if($as_mesdes=='Enero')
		       {
				   $ld_programado_trimestral=$ld_trimetreI;
				   $ld_programado_acumulado=$ld_trimetreI;
			   }//if
			   if($as_mesdes=='Abril')
		       {
				   $ld_programado_trimestral=$ld_trimetreII;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII;
			   }//if
			   if($as_mesdes=='Junio')
		       {
				   $ld_programado_trimestral=$ld_trimetreIII;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII+$ld_trimetreIII;
			   }//if
			   if($as_mesdes=='Octubre')
		       {
				   $ld_programado_trimestral=$ld_trimetreIV;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII+$ld_trimetreIII+$ld_trimetreIV;
			   }//if
			   $ld_asignado_modificado=$ld_asignado+$ld_aumento-$ld_disminucion;
			    if ($ld_programado_trimestral > 0)
			   {
			    $ld_porcentual =($ld_causado/$ld_programado_trimestral)*100;
			   }
			   else
			   {
			    $ld_porcentual =0;
			   }
			   $this->dts_ingresos_corrientes->insertRow("cuenta",$ls_spg_cuenta);
			   $this->dts_ingresos_corrientes->insertRow("denominacion",$ls_denominacion);
			   $this->dts_ingresos_corrientes->insertRow("asignado",$ld_asignado);
			   $this->dts_ingresos_corrientes->insertRow("modificado",$ld_asignado_modificado);
			   $this->dts_ingresos_corrientes->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_ingresos_corrientes->insertRow("ejecutado",$ld_causado);		
		  	   $this->dts_ingresos_corrientes->insertRow("absoluto",abs($ld_causado-$ld_programado_trimestral));		
		  	   $this->dts_ingresos_corrientes->insertRow("porcentual", $ld_porcentual);		
			   $this->dts_ingresos_corrientes->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_ingresos_corrientes->insertRow("ejecutado_acumulado",$ld_causado_acumulado);
			   /// datastore  del reportes
			   $this->dts_reporte_temporal->insertRow("cuenta",$ls_spg_cuenta);
			   $this->dts_reporte_temporal->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte_temporal->insertRow("asignado",$ld_asignado);
			   $this->dts_reporte_temporal->insertRow("modificado",$ld_asignado_modificado);
			   $this->dts_reporte_temporal->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado",$ld_causado);		
		  	   $this->dts_reporte_temporal->insertRow("absoluto",abs($ld_causado-$ld_programado_trimestral));		
		  	   $this->dts_reporte_temporal->insertRow("porcentual", $ld_porcentual);		
			   $this->dts_reporte_temporal->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado_acumulado",$ld_causado_acumulado);
			   $lb_valido=true;
		    }//while
	    }//if	
	    $this->io_sql->free_result($rs_data);
	  }//else
	  
	  $ls_sql=" SELECT max(spi_cuenta) as spi_cuenta,max(denominacion) as denominacion,                  ".
              "        sum(previsto) as previsto, sum(cobrado_anticipado) as cobrado_anticipado,         ".
              "        sum(cobrado) as cobrado, sum(devengado) as devengado, sum(aumento) as aumento,    ".
              "        sum(disminucion) as disminucion, sum(enero) as enero , sum(febrero) as febrero ,  ".
              "        sum(marzo) as marzo, sum(abril) as abril, sum(mayo) as mayo, sum(junio) as junio, ".
              "        sum(julio) as julio, sum(agosto) as agosto, sum(septiembre) as septiembre,        ".
              "        sum(octubre) as octubre, sum(noviembre) as noviembre,                             ".
			  "        sum(diciembre) as diciembre,                                                      ".
			  "        sum(enero+febrero+marzo) as trimetrei, sum(abril+mayo+junio) as trimetreii,       ".
			  "        sum(julio+agosto+septiembre) as trimetreiii,                                      ".
			  "        sum(octubre+noviembre+diciembre) as trimetreiv                                    ".
	  	      " FROM   spi_cuentas ".
			  " WHERE  codemp='".$this->ls_codemp."' AND ".
			  "        spi_cuenta like '301030000%' OR spi_cuenta like '301040000%' OR spi_cuenta like '301050000%' ".
			  "        OR spi_cuenta like '301100000%' OR".
			  "        spi_cuenta like '301100401%' OR spi_cuenta like '301100400%' OR spi_cuenta like '301100500%' OR ".
			  "        spi_cuenta like '301100800%' OR spi_cuenta like '301110000%' ";  	  
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  { // error interno sql
		$this->io_mensajes->message("CLASE->sigesp_spg_reporte_pres_caja_07 ". 
			                        "MÉTODO->uf_spi_reportes_ingresos_corrientes ".
									"ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		$lb_valido = false;
 	  }
	  else
	  {
		$li_numrows=$this->io_sql->num_rows($rs_data);	
		if($li_numrows>=0)
		{
		     while($row=$this->io_sql->fetch_row($rs_data))
			 {
			   $ls_spi_cuenta=$row["spi_cuenta"];
			   $ls_spi_cuenta = substr($ls_spi_cuenta,0,9);
			   $ls_denominacion=$row["denominacion"];
			   $ld_previsto=$row["previsto"];
			   $ld_cobrado_total=$row["cobrado"];
			   $ld_devengado_total=$row["devengado"];
			   $ld_aumento_total=$row["aumento"];
			   $ld_disminucion_total=$row["disminucion"];
			   $ld_enero=$row["enero"];
			   $ld_febrero=$row["febrero"];
			   $ld_marzo=$row["marzo"];
			   $ld_abril=$row["abril"];
			   $ld_mayo=$row["mayo"];
			   $ld_junio=$row["junio"];
			   $ld_julio=$row["julio"];
			   $ld_agosto=$row["agosto"];
			   $ld_septiembre=$row["septiembre"];
			   $ld_octubre=$row["octubre"];
			   $ld_noviembre=$row["noviembre"];
			   $ld_diciembre=$row["diciembre"];
			   $ld_trimetreI=$row["trimetrei"]; 
			   $ld_trimetreII=$row["trimetreii"]; 
			   $ld_trimetreIII=$row["trimetreiii"]; 
			   $ld_trimetreIV=$row["trimetreiv"]; 
			   $ls_spi_cuenta = substr($ls_spi_cuenta,0,9);
               $ls_detallar = true;
			   $lb_valido=$this->uf_spi_ejecutado_trimestral($ls_spi_cuenta,$adt_fecdes,$adt_fechas,&$ld_cobrado_anticipado,
			                                                 &$ld_cobrado,&$ld_devengado,&$ld_aumento,&$ld_disminucion,
															 $ls_detallar);
			   if($lb_valido)
		       {
				   $lb_valido=$this->uf_spi_ejecutado_acumulado($ls_spi_cuenta,$adt_fechas,&$ld_cobrado_anticipado_acumulado,
																&$ld_cobrado_acumulado,&$ld_devengado_acumulado,
																&$ld_aumento_acumulado,&$ld_disminucion_acumulado,
																$ls_detallar);
			   }//if
			   if($as_mesdes=='Enero')
		       {
				   $ld_programado_trimestral=$ld_trimetreI;
				   $ld_programado_acumulado=$ld_trimetreI;
			   }//if
			   if($as_mesdes=='Abril')
		       {
				   $ld_programado_trimestral=$ld_trimetreII;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII;
			   }//if
			   if($as_mesdes=='Junio')
		       {
				   $ld_programado_trimestral=$ld_trimetreIII;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII+$ld_trimetreIII;
			   }//if
			   if($as_mesdes=='Octubre')
		       {
				   $ld_programado_trimestral=$ld_trimetreIV;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII+$ld_trimetreIII+$ld_trimetreIV;
			   }//if
			   $ld_previsto_modificado=$ld_previsto+$ld_aumento-$ld_disminucion;
			   if ($ld_programado_trimestral > 0)
			   {
			    $ld_porcentual =($ld_cobrado/$ld_programado_trimestral)*100;
			   }
			   else
			   {
			    $ld_porcentual =0;
			   }
			   $this->dts_ingresos_corrientes->insertRow("cuenta",$ls_spi_cuenta);
			   $this->dts_ingresos_corrientes->insertRow("denominacion",$ls_denominacion);
			   $this->dts_ingresos_corrientes->insertRow("asignado",$ld_previsto);
			   $this->dts_ingresos_corrientes->insertRow("modificado",$ld_previsto_modificado);
			   $this->dts_ingresos_corrientes->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_ingresos_corrientes->insertRow("ejecutado",$ld_cobrado);		
		  	   $this->dts_ingresos_corrientes->insertRow("absoluto",abs($ld_cobrado-$ld_programado_trimestral));		
		  	   $this->dts_ingresos_corrientes->insertRow("porcentual", $ld_porcentual);		
			   $this->dts_ingresos_corrientes->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_ingresos_corrientes->insertRow("ejecutado_acumulado",$ld_cobrado_acumulado);
			   $this->dts_ingresos_corrientes->insertRow("status",$ls_status);
			   /// datastore  del reportes
			   $this->dts_reporte_temporal->insertRow("cuenta",$ls_spi_cuenta);
			   $this->dts_reporte_temporal->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte_temporal->insertRow("asignado",$ld_previsto);
			   $this->dts_reporte_temporal->insertRow("modificado",$ld_previsto_modificado);
			   $this->dts_reporte_temporal->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado",$ld_cobrado);		
		  	   $this->dts_reporte_temporal->insertRow("absoluto",abs($ld_cobrado-$ld_programado_trimestral));		
		  	   $this->dts_reporte_temporal->insertRow("porcentual", $ld_porcentual);		
			   $this->dts_reporte_temporal->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado_acumulado",$ld_cobrado_acumulado);
			   $lb_valido=true;
		    }//while
	    }//if	
	    $this->io_sql->free_result($rs_data);
	  }//else
    	return $lb_valido;
    }//fin uf_spg_reportes_gastos_corrientes

    
    
    function uf_spi_reportes_ingresos_transecprivado($adt_fecdes,$adt_fechas,$adts_datastore,$as_mesdes,$as_meshas)
    {////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function : uf_spi_reportes_ingresos_corrientes
	 //     Argumentos : adt_fecdes ... adt_fechas  // rango de fecha del reporte
	 //                  adts_datastore  // datastore que imprime el reporte
     //	       Returns : Retorna true o false si se realizo la consulta para el reporte
	 //	   Description : Reporte que genera salida del PResupuesto de Caja
	 //     Creado por : Ing. Arnaldo Suarez
	 // Fecha Creación : 18/06/2008                       Fecha última Modificacion :      Hora :
  	 ///////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=true;
	  $ls_sql=" SELECT max(spi_cuenta) as spi_cuenta,max(denominacion) as denominacion,max(status) as status,        ".
              "        sum(previsto) as previsto, sum(cobrado_anticipado) as cobrado_anticipado,         			".
              "        sum(cobrado) as cobrado, sum(devengado) as devengado, sum(aumento) as aumento,    			".
              "        sum(disminucion) as disminucion, sum(enero) as enero , sum(febrero) as febrero ,  			".
              "        sum(marzo) as marzo, sum(abril) as abril, sum(mayo) as mayo, sum(junio) as junio, 			".
              "        sum(julio) as julio, sum(agosto) as agosto, sum(septiembre) as septiembre,        			".
              "        sum(octubre) as octubre, sum(noviembre) as noviembre,                             			".
			  "        sum(diciembre) as diciembre,                                                      			".
			  "        sum(enero+febrero+marzo) as trimetrei, sum(abril+mayo+junio) as trimetreii,       			".
			  "        sum(julio+agosto+septiembre) as trimetreiii,                                     		 	".
			  "        sum(octubre+noviembre+diciembre) as trimetreiv                                    			".
	  	      " FROM   spi_cuentas ".
			  " WHERE  codemp='".$this->ls_codemp."' AND ".
			  "        spi_cuenta like '305010000%' OR spi_cuenta like '305010100%' OR spi_cuenta like '305010300%' ".
			  "        OR spi_cuenta like '305010301%' OR".
			  "        spi_cuenta like '305010302%' OR spi_cuenta like '305010303%' OR spi_cuenta like '305010304%' OR ".
			  "        spi_cuenta like '305010305%' OR spi_cuenta like '305010306%' OR spi_cuenta like '305010307%' OR ".
			  "        spi_cuenta like '305010308%' OR spi_cuenta like '305010309%' OR spi_cuenta like '305010500%' OR ".
			  "        spi_cuenta like '305010501%' OR spi_cuenta like '305010502%' OR spi_cuenta like '305010503%' OR ".
			  "        spi_cuenta like '301090000%' OR spi_cuenta like '301090100%' OR spi_cuenta like '301090200%' OR ".
			  "        spi_cuenta like '301099900%' ";  	  
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  { // error interno sql
		$this->io_mensajes->message("CLASE->sigesp_spg_reporte_pres_caja_07 ". 
			                        "MÉTODO->uf_spi_reportes_ingresos_corrientes ".
									"ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		$lb_valido = false;
 	  }
	  else
	  {
		$li_numrows=$this->io_sql->num_rows($rs_data);	
		if($li_numrows>=0)
		{
		     while($row=$this->io_sql->fetch_row($rs_data))
			 {
			   $ls_spi_cuenta=$row["spi_cuenta"];
			   $ls_denominacion=$row["denominacion"];
			   $ls_status=$row["status"];
			   $ld_previsto=$row["previsto"];
			   $ld_cobrado_total=$row["cobrado"];
			   $ld_devengado_total=$row["devengado"];
			   $ld_aumento_total=$row["aumento"];
			   $ld_disminucion_total=$row["disminucion"];
			   $ld_enero=$row["enero"];
			   $ld_febrero=$row["febrero"];
			   $ld_marzo=$row["marzo"];
			   $ld_abril=$row["abril"];
			   $ld_mayo=$row["mayo"];
			   $ld_junio=$row["junio"];
			   $ld_julio=$row["julio"];
			   $ld_agosto=$row["agosto"];
			   $ld_septiembre=$row["septiembre"];
			   $ld_octubre=$row["octubre"];
			   $ld_noviembre=$row["noviembre"];
			   $ld_diciembre=$row["diciembre"];
			   $ld_trimetreI=$row["trimetrei"]; 
			   $ld_trimetreII=$row["trimetreii"]; 
			   $ld_trimetreIII=$row["trimetreiii"]; 
			   $ld_trimetreIV=$row["trimetreiv"]; 
			   $ls_spi_cuenta = substr($ls_spi_cuenta,0,9);
			   $ls_detallar=true; 
			 }
		}
	  }
    }
    
    
    
}
?>