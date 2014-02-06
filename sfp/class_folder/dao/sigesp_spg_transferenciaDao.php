<?php
require_once('sigesp_conexiondin_dao.php');
require_once('sigesp_sfp_estprog1Dao.php');
require_once('sigesp_sfp_estprog2Dao.php');
require_once('sigesp_sfp_estprog3Dao.php');
require_once('sigesp_sfp_estprog4Dao.php');
require_once('sigesp_sfp_estprog5Dao.php');
require_once('sigesp_spg_estprog1Dao.php');
require_once('sigesp_sfp_planingresoDao.php');
require_once('sigesp_spg_spicuentasDao.php');
class Transferencia 
{
	public function CrearConexion($ArJson)
	{
		try
		{
			$oConexion = new Conexion();
			$oConexion->gestor=$ArJson->gestor;
			$oConexion->host=$ArJson->hostname;
			$oConexion->user=$ArJson->login;
			$oConexion->pass=$ArJson->password;
			$oConexion->base=$ArJson->database;
			$con2 = $oConexion->crearconexion();
			return $con2;				
		}
		catch (Exception $e) 
		{
    		return "0";
		}
	}
	public function TransferirDatos($ArJson)
	{
		$this->PasacuentasIngreso($ArJson);
	/*	$arrEps = array('spg_ep1','spg_ep2','spg_ep3','spg_ep4','spg_ep5');
		for($i=0;$i<$arrEps;$i++)
		{
			$this->Pasarep($ArJson,$arrEps[$i]);
		}
	*/
	}	
	public function Pasarep($ArJson,$tabla)
	{
		switch($tabla)
		{
			case 'spg_ep1':
				$EpOrigen = new estprog1Dao();
			break;
			case 'spg_ep2':
				$EpOrigen = new estprog2Dao();
			break;
			case 'spg_ep3':
				$EpOrigen = new estprog3Dao();
			break;
			case 'spg_ep4':
				$EpOrigen = new estprog4Dao();
			break;
			case 'spg_ep5':
				$EpOrigen = new estprog5Dao();
			break;
		}
		$rs  = 	$EpOrigen->LeerTodas();
		$db1 = $this->CrearConexion($ArJson);
		spgestprogDao::IniciarTran($db1);
		while(!$rs->EOF)
		{
			$EpDestino = new spgestprogDao($tabla);
			$this->pasardatos($rs->fields,$EpDestino);
			$EpDestino->Incluir($db1);
			$rs->MoveNext();
		}
		spgestprogDao::CompletarTran($db1);
	}
	
	
	public function PasacuentasIngreso($ArJson)
	{
		$oOrigen =  new planIngreso();
		$rs  = 	$oOrigen->LeerDistribucionTran();
		$db1 = $this->CrearConexion($ArJson);
		spgestprogDao::IniciarTran($db1);
		while(!$rs->EOF)
		{
			$EpDestino = new spicuentasDao();
			$this->pasardatos($rs->fields,$EpDestino);
			//$EpDestino->sc_cuenta = leerCuentaContable();
			$EpDestino->sc_cuenta = '';
			$EpDestino->nivel = '3';
			if($EpDestino->referencia==NULL)
			{
				$EpDestino->referencia='';
			}
			$EpDestino->Incluir($db1);
			$rs->MoveNext();
		}
		spgestprogDao::CompletarTran($db1);
	}
	
	
	public function PasacuentasGasto($ArJson)
	{
		$oOrigen =  new planIngreso();
		$rs  = 	$oOrigen->LeerDistribucionTran();
		$db1 = $this->CrearConexion($ArJson);
		spgestprogDao::IniciarTran($db1);
		while(!$rs->EOF)
		{
			$EpDestino = new spicuentasDao();
			$this->pasardatos($rs->fields,$EpDestino);
			//$EpDestino->sc_cuenta = leerCuentaContable();
			$EpDestino->sc_cuenta = '';
			$EpDestino->nivel = '3';
			if($EpDestino->referencia==NULL)
			{
				$EpDestino->referencia='';
			}
			$EpDestino->Incluir($db1);
			$rs->MoveNext();
		}
		spgestprogDao::CompletarTran($db1);
	}
	
	public function  pasardatos($origen,&$destino)
	{
		foreach($origen as $camporigen=>$valororigen)
		{
			foreach($destino as $campodestino=>$valordestino)
			{
				if($camporigen==$campodestino && !is_numeric($camporigen) && !is_numeric($campodestino))
				{
					if($origen[$camporigen]!='')
					{
						$destino->$campodestino = $origen[$camporigen];		
					}
					elseif(substr($camporigen,0,9)=='denestpro')
					{
						$destino->$campodestino = 'NINGUNO';	
					}
				}
				else
				{
					continue;
				}
			}
		}
	}
}
?>