<?php
/***********************************************************************************************
* @Clase compartida para registrar los eventos que generan modificaciones a la base de datos.
* @Fecha de creación: 15/07/2008.
* @Autor: Gusmary Balza.
**********************************************************************************************/

require_once('sigesp_dao_msg_notificacion.php');

class RegistroEventos extends ADOdb_Active_Record
{
	var $_table='msgregistroeventom';	
	
	public $ejecucion = 1;
	public $result;
	public $valido;
	public $error;
	public $objNotificacion;
	
	function RegistroEventos()
	{	
		$this->objNotificacion = new Notificacion();
	}	
	
	
/***********************************************************************************
* @Función que busca los datos del evento pasado como parámetro.
* @parametros: $event: nombre del evento, $descrip: descripción del evento.
* @retorno: 1 o 0 según el éxito de la operación.
* @fecha de creación: 15/07/2008
* @autor: Ing.Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function seleccionarEvento($event,&$descrip)
	{
		global $db;
		$db->StartTrans();		
		$result = $db->Execute("SELECT evento, desevento FROM msgeventom WHERE msgeventom.evento='{$event}'");
		if ($db->CompleteTrans())
		{				
			//if ($fila=$db->FetchRow()) 
			if (!$result->EOF) 
			{
				$descrip = $result["desevento"];	//revisar
				$valido = true;
				$result->MoveNext();
			}
			else
			{
				$descrip = "";
				$valido = false;
			}		
		}
		else
		{
			echo "Ha ocurrido un error  ";
			$valido = false;
			$error  = $db->ErrorMsg();	
		}
		//return $valido;
	}


/********************************************************************************
* @Función que inserta los datos del evento.
* @parametros: $empresa: código de la empresa,
*			   $sistema: código del sistema,
*			   $event: nombre del evento, 
*			   $usuario: código del usuario.
*			   $funcionalidad: pantalla desde donde se inserta el evento.
* @retorno: 1 o 0 según el éxito de la función.
*********************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*********************************************************************************/	
	function insertarEvento()
	{
			
	//	if ($usuario!="PSEGIS")
		$this->fecevento = date("Y/m/d h:i");
//		$this->hora    = date("h:i");
		$this->equevento = $this->obtenerIp();
		global $db;
		$db->StartTrans();	
		$result    = $db->Execute("SELECT numevento FROM msgregistroeventom");
		$numeroevento  = 1;		
		while (!$result->EOF)
		{
			$numeroevento = $numeroevento+1;
			$result->MoveNext();
		}
		$this->numevento = $numeroevento;
		$this->save();	 
		if ($db->CompleteTrans())
		{
			$valido = true;
			//echo "Transaccion exitosa";	
		}
		else
		{
			$valido = false;
			echo "Transacion fallida: Ha ocurrido un error";
			$this->desevento = $db->ErrorMsg();	
		}
	//	return '1';
	}		
	
	
/********************************************************************************
* @Función que busca los derechos de un usuario para una ventana de un sistema.
* @parametros: $empresa: código de la empresa,
               $usuario: código del usuario.
			   $sistema: código del sistema,
			   $ventana: pantalla desde donde se inserta el evento.
* @retorno: 1 o 0 según el éxito de la función.
*********************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
**********************************************************************************/	
	function seleccionarPermisos($empresa,$usuario,$sistema,$ventana)  //probar
	{		
		$habilitado = 1;		
		global $db;
		$db->StartTrans();				
		$db->Execute(" SELECT * FROM msgderechosusuariosm WHERE codempresa='".$empresa."' AND codusuario='".$usuario."' ".
					 " AND codsistema='".$sistema."' AND nomventana='".$ventana."' AND enabled='".$habilitado."'");
		if ($db->CompleteTrans())
		{
		//	if ($fila = $db->FetchRow())
			if (!$db->EOF)
			{
				$valido = true;
				$db->MoveNext();
			}
			else
			{
				echo "No tiene Permiso";
				$valido = false;
				$error = $db->ErrorMsg();	
			}
		}
		else
		{
			echo "Ha ocurrido un error";
			$valido = false;
			$error = $db->ErrorMsg();	
		}
		//return $valido;
		//$db->Close();
		//$this->SQL->free_result($ejecucion);
	}	
				
	
	function cargarPermisos($empresa,$usuario,$sistema,$ventana,&$aa_permisos)
	{	
		
		
	}
	
	function cargarPermisosInternos($empresa,$usuario,$sistema,$ventana,$codintper,&$aa_permisos)
	{
	}
	
	
/**
* @Función que permite obtener la dirección IP.
* @parámetros:
* @retorno: $ip dirección IP
* 
**/	
	function obtenerIp()
	{
		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"),"unknown"))
		{
			$ip = getenv("HTTP_CLIENT_IP");
		}	
		else if (getenv("HTTP_X_FORWARDED_FOR ") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR "), "unknown"))
		{
			$ip = getenv("HTTP_X_FORWARDED_FOR ");
		}
		else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
		{
			$ip = getenv("REMOTE_ADDR");
		}
		else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}	
		else
		{
		   $ip = "unknown";
		}
		return($ip);
	}
	
}
?>