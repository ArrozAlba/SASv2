<?php
/***********************************************************************************
* @Clase compartida para registrar los eventos que generan modificaciones a la base 
* de datos.
* @fecha de creación: 15/07/2008.
* @autor: Ing. Gusmary Balza
* **************************
* @fecha modificacion  
* @autor   
* @descripcion  
***********************************************************************************/
require_once('sigesp_dao_sss_notificacion.php');
require_once('sigesp_dao_sss_sistemaventana.php');

class RegistroEventos extends ADOdb_Active_Record
{
	var $_table='sss_registro_eventos';	
	
	public $valido;
	public $objNotificacion;
	public $objSistemaVentana;
	public $nomfisico;
	
/***********************************************************************************
* @Constructor de la clase
* @parametros: 
* @retorno: 
* @fecha de creación: 27/08/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function RegistroEventos()
	{	
		$this->objNotificacion = new Notificacion();
		$this->objSistemaVentana = new SistemaVentana();
	}	
	
	
/***********************************************************************************
* @Función que incluye los eventos según lo realizado por el usuario
* @parametros: 
* @retorno:
* @fecha de creación: 27/08/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function incluir()
	{
		global $conexionbd;
		try 
		{ 
			$this->objSistemaVentana->codsis=$this->codsis;
			$this->objSistemaVentana->nomfisico=$this->nomfisico;
			$this->codmenu = $this->objSistemaVentana->obtenerCodigoMenu();
			$this->obtenerEquipo();
			$this->desevetra = str_replace("'","",$this->desevetra);
			$this->desevetra = str_replace('"','',$this->desevetra);		
			$consulta = "INSERT INTO {$this->_table} (codemp, numeve, codusu, codsis, codmenu, evento, codintper, ".
	  					"                             fecevetra, equevetra, desevetra) ".
						" SELECT '{$this->codemp}',(".$conexionbd->IfNull('(MAX(numeve)  + 1)',1)."),'".$_SESSION['la_logusr']."','{$this->codsis}','{$this->codmenu}', ".
						" 		 '{$this->evento}','---------------------------------', '".date('Y/m/d h:i')."','{$this->equevetra}', ".
						" 		 '{$this->desevetra}' ".
						"   FROM {$this->_table} ";
			$result = $conexionbd->Execute($consulta);
		}
		catch (exception $e) 
		{
			$this->valido  = false;	
		}
	}		
	
	
/***********************************************************************************
* @Función que obtiene el valor de la ip del equipo donde se realizó la transaccción 
* @parametros: 
* @retorno:
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function obtenerEquipo()
	{
		if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown'))
		{
			$this->equevetra = getenv('HTTP_CLIENT_IP');
		}	
		else if (getenv('HTTP_X_FORWARDED_FOR ') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR '), 'unknown'))
		{
			$this->equevetra = getenv('HTTP_X_FORWARDED_FOR ');
		}
		else if (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown'))
		{
			$this->equevetra = getenv('REMOTE_ADDR');
		}
		else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown'))
		{
			$this->equevetra = $_SERVER['REMOTE_ADDR'];
		}	
		else
		{
		   $this->equevetra = 'unknown';
		}
	}
}
?>