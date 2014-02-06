<?php
require_once("../class_folder/sigesp_conexion_dao.php");
require_once('sigesp_sfp_con_estplandao.php');
class estprog1Dao extends ADODB_Active_Record
{
	var $_table='spe_estpro1';				
	public function FiltrarEst()
	{
		$oNivel=new ConfNivelDao();
		$oNivel->tipo="PL";
		$oNivel->nivel="1";
		$tama = $oNivel->LeerNumCar();
		$pos=(25-$tama)+1;
		global $db;
		$Rs = $db->Execute("select substr(codest1,{$pos},{$tama}) as codest1,denest1 from {$this->_table} where {$this->_table}.codemp='{$this->codemp}'"); 
		return $Rs;
	}

	public function Modificar()
	{
		global $db;
		$db->StartTrans();
		$this->Replace();
		if($db->CompleteTrans())
			{
				return "1";	
			}
			else
			{
				return "0";
			}
		
	}
	
	public function Incluir()
	{
		global $db;
		try
		{
			$db->debug=1;
			$db->StartTrans();
			$this->save();
			if($db->CompleteTrans())
			{
				return "1";	
			}
			else
			{
				return "0";
			}
		}
		catch(exception $e)
		{
			//capturar el error y guardarlo en la bd
			return "0";
		}
	}
	
	public function Eliminar()
	{
		global $db;
		try
		{
			$db->StartTrans();
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
		catch(exception $e)
		{
			//capturar el error y guardarlo en la bd
			return "0";
		}
	}

	public function Copiarestprog()
	{
		global $db;
		//$db->debug=true;
		$sql="	delete from sfp_estpro5;
				delete from sfp_estpro4;
				delete from sfp_estpro3;
				delete from sfp_estpro2;
				delete from sfp_estpro1;
				insert into sfp_estpro1(codemp,ano_presupuesto,codestpro1,estcla,denestpro1) 
				select '{$this->codemp}','{$this->ano_presupuesto}',codestpro1,estcla,denestpro1 from spg_ep1; 
				insert into sfp_estpro2(codemp,ano_presupuesto,codestpro1,codestpro2,estcla,denestpro2) 
				select '{$this->codemp}','{$this->ano_presupuesto}',codestpro1,codestpro2,estcla,denestpro2 from spg_ep2;
				insert into sfp_estpro3(codemp,ano_presupuesto,codestpro1,codestpro2,codestpro3,estcla,denestpro3)
				select '{$this->codemp}','{$this->ano_presupuesto}',codestpro1,codestpro2,codestpro3,estcla,denestpro3 from spg_ep3;
				insert into sfp_estpro4(codemp,ano_presupuesto,codestpro1,codestpro2,codestpro3,codestpro4,estcla,denestpro4)
				select '{$this->codemp}','{$this->ano_presupuesto}',codestpro1,codestpro2,codestpro3,codestpro4,estcla,denestpro4 from spg_ep4; 
				insert into sfp_estpro5(codemp,ano_presupuesto,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla,denestpro5)
				select '{$this->codemp}','{$this->ano_presupuesto}',codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla,denestpro5 from spg_ep5";
		$Rs = $db->Execute($sql);
		if($Rs)
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