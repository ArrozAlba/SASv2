<?php
require_once('../class_folder/dao/sigesp_sfp_con_estplandao.php');
require_once("../class_folder/sigesp_conexion_dao.php");
require_once("sigesp_sfp_intefuenteDao.php");
require_once("sigesp_sfp_inteAdDao.php");
require_once("sigesp_sfp_intGastosDao.php");
require_once("sigesp_sfp_intProbDao.php");
require_once("sigesp_sfp_intMetaDao.php");
require_once('sigesp_spe_asientosDao.php');
require_once('sigesp_sfp_plan_unico_reDao.php');
class IntegracionPre extends ADODB_Active_Record
{
	var $_table='spe_relacion_es';
	var $Probs=array();
	var $Ads = array();
	var $Ubs = array();
	var $Gastos= array();
	var $Metas =array();
						
	public function LeerUno()
	{
		global $db;
		$this->crearcondicion(&$conSelect,&$conFrom,&$conWhere);	
		$sql = "select i.codinte{$conSelect} from spe_relacion_es as i {$conFrom} where i.codinte<>0  {$conWhere}";
		//echo $sql;
		//die();
		$Rs = $db->Execute($sql);	 
		return $Rs;
	}
	
	public function existeFuenteCuentas()
	{
		global $db;
		$sql = "select fuentecuentas from spe_relacion_es where fuentecuentas='1' and codemp='{$this->codemp}' and ano_presupuesto='2008'";
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
	public function marcarFuenteCuenta()
	{
		global $db;
		$db->StartTrans();
		$sql = "update spe_relacion_es set fuentecuentas='1' 
				where codinte='{$this->codinte}' and codemp='{$this->codemp}' 
				and ano_presupuesto='2008'";
		$db->Execute($sql);	 
		if($db->CompleteTrans())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function Leerasientos()
	{
		
		global $db;
		//$db->debug=true;
		$oAsiento = new Asientos();
		$oAsiento->codemp=$this->codemp;
		$oAsiento->sig_cuenta=$this->sig_cuenta;
		$oAsiento->ano_presupuesto=$this->ano_presupuesto;
		$RsA = $oAsiento->LeerAsiento();
		$oPlanCuentas = new planUnicoRe();
		$oPlanCuentas->sig_cuenta=$this->sig_cuenta; 
		$RsC = $oPlanCuentas->LeerUna();
		$ArAux["variacion"]=$RsA;
		$ArAux["caif"]=$RsC;  
		return $ArAux;
	}
	
	public function obtenerUltimoCodigo()
	{ 
		global $db;
		$sql="select codinte as Ultimo from {$this->_table} where codest1='{$this->codest1}' and codest2='{$this->codest2}' and codest3='{$this->codest3}' and codest4='{$this->codest4}' and codest5='{$this->codest5}' and codestpro1='{$this->codestpro1}' and codestpro2='{$this->codestpro2}' and codestpro3='{$this->codestpro3}' and codestpro4='{$this->codestpro4}' and codestpro5='{$this->codestpro5}'";
;
		$Rs = $db->Execute($sql); 
		return $Rs->fields["Ultimo"];
	}
	
	public function IncluirTodos()
	{
		global $db;
		$db->debug=1;
		$db->StartTrans();
		$this->Save();
		if($db->CompleteTrans())
		{
			$IdPadre = $this->codinte;	
			for($i=0;$i<count($this->Ads);$i++)
			{	
				$this->Ads[$i]->codinte = $IdPadre;
				$this->Ads[$i]->Incluir();
			}
			for($i=0;$i<count($this->Ubs);$i++)
			{	
				$this->Ubs[$i]->codinte = $IdPadre;
				$this->Ubs[$i]->Incluir();
			}	
			for($i=0;$i<count($this->Probs);$i++)
			{	
				$this->Probs[$i]->codinte = $IdPadre;
				$this->Probs[$i]->Incluir();
			}
			for($i=0;$i<count($this->Metas);$i++)
			{	
				$this->Metas[$i]->codinte = $IdPadre;
				$this->Metas[$i]->Incluir();
			}
			if(count($this->Gastos)>0)
			{
				if($this->existeFuenteCuentas()===false)
				{
					$this->marcarFuenteCuenta();
				}
				for($i=0;$i<count($this->Gastos);$i++)
				{
					$this->Gastos[$i]->codinte = $IdPadre;
					$this->Gastos[$i]->Incluir();
				}
			}	
			$db->CompleteTrans();
			return "1";
		}
		else
		{
			return $db->ErrorNo();
		}
			
	}
	
	
	public function ActualizarTodos()
	{
		global $db;
	//	$db->StartTrans();
//		$this->save();
//		if($db->CompleteTrans())
//		{
		//	$IdPadre = $this->obtenerUltimoCodigo();	
			for($i=0;$i<count($this->Ads);$i++)
			{	
				$this->Ads[$i]->codinte = $this->codinte;
		
				$this->Ads[$i]->Incluir();
			}
			for($i=0;$i<count($this->Ubs);$i++)
			{	
				$this->Ubs[$i]->codinte = $this->codinte;
				$this->Ubs[$i]->Incluir();
			}	
			for($i=0;$i<count($this->Probs);$i++)
			{	
				$this->Probs[$i]->codinte = $this->codinte;
				$this->Probs[$i]->Incluir();
			}
			for($i=0;$i<count($this->Metas);$i++)
			{	
				$this->Metas[$i]->codinte = $this->codinte;
				$this->Metas[$i]->Incluir();
			}
			
			if(count($this->Gastos)>0)
			{
				if($this->existeFuenteCuentas()===false)
				{
					$this->marcarFuenteCuenta();
				}	
				for($i=0;$i<count($this->Gastos);$i++)
				{	
					$this->Gastos[$i]->codinte = $this->codinte;	
					$this->Gastos[$i]->Incluir();
				}
			}	
			$db->CompleteTrans();
		//}
			return "1";
	}


	public function IncluirEnc()
	{
		try
		{
			global $db;
			$db->StartTrans();
			$this->save();
			$db->CompleteTrans();
			$IdPadre = $this->obtenerUltimoCodigo();
			return "{$IdPadre}";
		}
		catch (Exception $e) 
		{
			//mandar a un archivo de logs con los eventos fallidos 	
    		return "0";
		}
		
	}

	public function ObtenerCodigo()
	{
		global $db;
		$sql="select coalesce(max(codinte)+1,1) as codigo  from {$this->_table}";
		$Rs = $db->Execute($sql);
		return $Rs->fields["codigo"];
	}

 	public function crearcondicion($conSelect,$conFrom,$conWhere)
	{
		$oNiveles = new ConfNivelDao();
		$nivelPlan = $oNiveles->ObtenerNivelPlan();
		$nivelPro = $oNiveles->ObtenerNivelProg();	
		
		switch($nivelPlan)
		{
			case '1':
				$conSelect .=",p1.codest1,p1.denest1";
				$conFrom.=",spe_estpro1 as p1";
				$conWhere.="and p1.codest1=i.codest1";
				break;	
			case '2':	
				$conSelect .=",p1.codest1,p1.denest1,p2.codest2,p2.denest2";
				$conFrom.=",spe_estpro1 as p1,spe_estpro2 as p2";
				$conWhere.=" and p2.codest1=i.codest1 and p2.codest2=i.codest2
 and p1.codest1=i.codest1 ";
				break;	
			case '3':	
				$conSelect .=",p1.codest1,p1.denest1,p2.codest2,p2.denest2,p3.codest3,p3.denest3";
				$conFrom.=",spe_estpro1 as p1,spe_estpro2 as p2,spe_estpro3 as p3";
				$conWhere.=" and p3.codest1=i.codest1 and p3.codest2=i.codest2 and p3.codest3=i.codest3 and
p2.codest1=i.codest1 and p2.codest2=i.codest2
 and p1.codest1=i.codest1";
				break;	
					
			case '4':	
				$conSelect.=",p1.codest1,p1.denest1,p2.codest2,p2.denest2,p3.codest3,p3.denest3,
p4.codest4,p4.denest4";
				$conFrom.=",spe_estpro1 as p1,spe_estpro2 as p2,spe_estpro3 as p3,spe_estpro4 as p4";
				$conWhere.=" and p4.codest1=i.codest1 and p4.codest2=i.codest2 and p4.codest3=i.codest3 and p4.codest4=i.codest4 and p3.codest1=i.codest1 and p3.codest2=i.codest2 and p3.codest3=i.codest3 and
p2.codest1=i.codest1 and p2.codest2=i.codest2
 and p1.codest1=i.codest1 ";
				break;	
			case '5':	
				$conSelect .=",i.codinte,p1.codest1,p1.denest1,p2.codest2,p2.denest2,p3.codest3,p3.denest3,p4.codest4,p4.denest4,p5.codest5,p5.denest5";
				$conFrom.=",spe_estpro1 as p1,spe_estpro2 as p2,spe_estpro3 as p3,spe_estpro4 as p4,spe_estpro5 as p5";
				$conWhere.=" and p5.codest1=i.codest1 and p5.codest2=i.codest2 and p5.codest3=i.codest3 and p5.codest4=i.codest4 and p5.codest5=i.codest5 and
p4.codest1=i.codest1 and p4.codest2=i.codest2 and p4.codest3=i.codest3 and p4.codest4=i.codest4 and p3.codest1=i.codest1 and p3.codest2=i.codest2 and p3.codest3=i.codest3 and
p2.codest1=i.codest1 and p2.codest2=i.codest2
 and p1.codest1=i.codest1 ";
				break;	

			
		}
		

	switch($nivelPro)
		{
			case '1':
				$conSelect .=",e1.codestpro1,e1.denestpro1";
				$conFrom.=",sfp_estpro1 as e1";
				$conWhere.="and e1.codestpro1=i.codestpro1 and e1.codestpro1='{$this->codestpro1}'";
				break;	
			case '2':	
				$conSelect .=",e2.codestpro2,e2.denestpro2,e1.denestpro1,e1.codestpro1";
				$conFrom.=",sfp_estpro1 as e1,sfp_estpro2 as e2";
				$conWhere.=" and e2.codestpro1=i.codestpro1 and e2.codestpro2=i.codestpro2 and e1.codestpro1=i.codestpro1 and e2.codestpro1='{$this->codestpro1}' and e2.codestpro2='{$this->codestpro2}'";
				break;	
			case '3':	
				$conSelect .=",e2.codestpro2,e2.denestpro2,e1.denestpro1,e1.codestpro1,e3.codestpro3,e3.denestpro3";
				$conFrom.=",sfp_estpro1 as e1,sfp_estpro2 as e2,sfp_estpro3 as e3";
				$conWhere.=" and e3.codestpro1=i.codestpro1 and e3.codestpro2=i.codestpro2 and e3.codestpro3=i.codestpro3 and e2.codestpro1=i.codestpro1 and e2.codestpro2=i.codestpro2 and e1.codestpro1=i.codestpro1 and e3.codestpro1='{$this->codestpro1}' and e3.codestpro2='{$this->codestpro2}' and e3.codestpro3='{$this->codestpro3}'";
				break;	
					
			case '4':	
				$conSelect.=",e1.codestpro1,e1.denestpro1,e2.codestpro2,e2.denestpro2,e3.codestpro3,e3.denestpro3,e4.codestpro4,e4.denestpro4";
				$conFrom.=",sfp_estpro1 as e1,sfp_estpro2 as e2,sfp_estpro3 as e3,sfp_estpro4 as e4";
				$conWhere.=" and e4.codestpro1=i.codestpro1 and e4.codestpro2=i.codestpro2 and e4.codestpro3=i.codestpro3 and e4.codestpro4=i.codestpro4 and e3.codestpro1=i.codestpro1 and e3.codestpro2=i.codestpro2 and e3.codestpro3=i.codestpro3 and e2.codestpro1=i.codestpro1 and e2.codestpro2=i.codestpro2 and e1.codestpro1=i.codestpro1 and e4.codestpro1='{$this->codestpro1}' and e4.codestpro2='{$this->codestpro2}' and e4.codestpro3='{$this->codestpro3}' and e4.codestpro4='{$this->codestpro4}'";
				break;	
			case '5':	
				$conSelect .=",e1.codestpro1,e1.denestpro1,e2.codestpro2,e2.denestpro2,e3.codestpro3,e3.denestpro3,e4.codestpro4,e4.denestpro4,e5.codestpro5,e5.denestpro5";
				$conFrom.=",sfp_estpro1 as e1,sfp_estpro2 as e2,sfp_estpro3 as e3,sfp_estpro4 as e4,sfp_estpro5 as e5";
				$conWhere.=" and e5.codestpro1=i.codestpro1 and e5.codestpro2=i.codestpro2 and e5.codestpro3=i.codestpro3 and e5.codestpro4=i.codestpro4 and e5.codestpro5=i.codestpro5 and
e4.codestpro1=i.codestpro1 and e4.codestpro2=i.codestpro2 and e4.codestpro3=i.codestpro3 and e4.codestpro4=i.codestpro4 and e3.codestpro1=i.codestpro1 and e3.codestpro2=i.codestpro2 and e3.codestpro3=i.codestpro3 and e2.codestpro1=i.codestpro1 and e2.codestpro2=i.codestpro2 and e1.codestpro1=i.codestpro1 and e5.codestpro1='{$this->codestpro1}' and e5.codestpro2='{$this->codestpro2}' and e5.codestpro3='{$this->codestpro3}' and e5.codestpro4='{$this->codestpro4}' and e5.codestpro5='{$this->codestpro5}'";
	break;	

			
		}

		$condicion="{$conSelect} {$conFrom} {$conWhere}";
		return $condicion;
//		
//		$cad='';
//		if($this->codestpro1!='')
//		{
//			$cad .="i.codestpro1=e1.codestpro1 and e1.codestpro1='{$this->codestpro1}'"; 
//		}
//		
//		if($this->codestpro2!='')
//		{
//			$cad .="i.codestpro1=e2.codestpro1 i.codestpro2=e2.codestpro2 and e2.codestpro1='{$this->codestpro1}' and e2.codestpro2='{$this->codestpro2}'"; 
//		}
//		
//		if($this->codestpro3!='')
//		{
//			$cad .="i.codestpro1=e3.codestpro1 and i.codestpro2=e3.codestpro2 i.codestpro3=e3.codestpro3 and e3.codestpro1='{$this->codestpro1}' and e3.codestpro2='{$this->codestpro2}' and e3.codestpro3='{$this->codestpro3}'"; 
//		}
//
//		if($this->codestpro4!='')
//		{
//			$cad .="i.codestpro1=e4.codestpro1 and i.codestpro2=e4.codestpro2 and i.codestpro3=e4.codestpro3 and i.codestpro4=e4.codestpro4 and e4.codestpro1='{$this->codestpro1}' and e4.codestpro2='{$this->codestpro2}' and e4.codestpro3='{$this->codestpro3}' and e4.codestpro4='{$this->codestpro4}'"; 
//		}
//
//		if($this->codestpro5!='')
//		{
//			$cad .="i.codestpro1=e5.codestpro1 and i.codestpro2=e5.codestpro2 and i.codestpro3=e5.codestpro3 and i.codestpro4=e5.codestpro4 and i.codestpro5=e5.codestpro5 and e5.codestpro1='{$this->codestpro1}' and e5.codestpro2='{$this->codestpro2}' and e5.codestpro3='{$this->codestpro3}' and e5.codestpro4='{$this->codestpro4}' and e5.codestpro5='{$this->codestpro5}'"; 
//		}
//
//		return $cad;
				
	}
 	
 	
	public function BuscarInt()
	{
		global $db;
		$sql = "select * from {$this->_table} where codest1='{$this->codest1}' and codest2='{$this->codest2}' and codest3='{$this->codest3}' and codest4='{$this->codest4}' and codest5='{$this->codest5}' and codestpro1='{$this->codestpro1}' and codestpro2='{$this->codestpro2}' and codestpro3='{$this->codestpro3}' and codestpro4='{$this->codestpro4}' and codestpro5='{$this->codestpro5}'";
		
		
		$Rs= $db->Execute($sql);
		return $Rs;

	}
	public function obtenerProblemas()
	{
		$oProb = new intProbDao();
		$rs = $oProb->BuscarProblemas($this->codinte);
		return $rs;

	}
	public function obtenerMetas()
	{
		$oMeta = new intMetasDao();
		$rs = $oMeta->BuscarMetas($this->codinte);
		return $rs;

	}
	public function obtenerUnidades()
	{
		$oUnidades = new intUniAdDao();
		$rs = $oUnidades->BuscarUnidades($this->codinte);
		return $rs;
	}
	
	public function eliminarUnidad($oBj)
	{
		$Re = $oBj->Eliminar();
		return $Re;
	}
	
	public function eliminarUbicacion($oBj)
	{
		$Re = $oBj->Eliminar();
		return $Re;
	}
	public function eliminarProblemas($oBj)
	{
		$Re = $oBj->Eliminar();
		return $Re;
	}
	
	public function eliminarMetas($oBj)
	{
		$Re = $oBj->Eliminar();
		return $Re;
	}
	
	public function eliminarCuentas($oBj)
	{
		$Re = $oBj->Eliminar();
		return $Re;
	}

	public function obtenerUbicaciones()
	{
		$oUbicacion=$this->ObtenerObjetoActual();
		$Ar[0] = $oUbicacion["rs"]->BuscarUbicaciones($this->codinte);
		$Ar[1] = $oUbicacion["nivel"];
		return $Ar;
	}
	
	public function obtenerCuentas()
	{
		$oCuenta=new intGastosDao();
		$rs = $oCuenta->BuscarCuentasGasto($this->codinte);
		return $rs;
	}

	public function ObtenerObjetoActual()
	{
		$oNiveles = new ConfNivelDao();
		$nivel = $oNiveles->ObtenerNivelUb();		
		switch($nivel)
		{
			case '1':
				$oUbicacion = new intUb1Dao();
				break;
			case '2':
				$oUbicacion = new intUb2Dao();
				break;
			case '3':
				$oUbicacion = new intUb3Dao();
				break;
			case '4':
				$oUbicacion = new intUb4Dao();
				break;
			case '5':
				$oUbicacion = new intUb5Dao();
				break;
				
		}
		$ar['rs']=$oUbicacion;
		$ar['nivel']=$nivel;
		return $ar;
	}
	public function IncluirFuente($ofuente)
	{
			$oFuente->Incluir();	
			return $rs;

	}	

}
?>