<?php
/***********************************************************************************
* @Clase compartida para registrar los eventos que generan modificaciones a la base de datos.
* @fecha de creación: 15/07/2008.
* @autor: Ing. Gusmary Balza.
* **************************
* @fecha modificacion 
* @autor   
* @descripcion 
***********************************************************************************/
require_once('sigesp_dao_msg_notificacion.php');

class PerfilEvento extends ADOdb_Active_Record
{
	var $_table='msgperfileventod';	
	
	public $valido;
	public $objNotificacion;
	
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
	function PerfilEvento()
	{	
		$this->objNotificacion = new Notificacion();
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
		$conexionbd->StartTrans();
		$consulta = "INSERT INTO msgperfileventod (codempresa, codevento, codsistema, codusuario, codgrupo, codmenu, codintpermiso, ".
					"  evento, tipo, fecha, hora, equipo, descripcion) VALUES ('{$this->codempresa}',{$this->codevento},'{$this->codsistema}', ".
					" '{$this->codusuario}','{$this->codgrupo}',{$this->codmenu},'{$this->codintpermiso}','{$this->evento}','{$this->tipo}',".
					" '{$this->fecha}','{$this->hora}','{$this->equipo}','{$this->descripcion}' )";
		$result = $conexionbd->Execute($consulta);
		
		if ($conexionbd->CompleteTrans())
		{
			$this->valido = true;				
		}
		else
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
		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"),"unknown"))
		{
			$this->equipo = getenv("HTTP_CLIENT_IP");
		}	
		else if (getenv("HTTP_X_FORWARDED_FOR ") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR "), "unknown"))
		{
			$this->equipo = getenv("HTTP_X_FORWARDED_FOR ");
		}
		else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
		{
			$this->equipo = getenv("REMOTE_ADDR");
		}
		else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
		{
			$this->equipo = $_SERVER['REMOTE_ADDR'];
		}	
		else
		{
		   $this->equipo = "unknown";
		}
	}
	
/***********************************************************************************
* @Función que obtiene los eventos por usuario o grupo para el reporte de auditoria 
* @parametros: 
* @retorno:
* @fecha de creación: 28/08/2008
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	public function leerReporte() 
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$consulta = " SELECT codempresa,codevento,codsistema,codusuario,codgrupo,codmenu,codintpermiso,evento,tipo,fecha,hora, ".
					" equipo,descripcion, ".
					" (SELECT nombre FROM msgsistemam WHERE {$this->_table}.codsistema=msgsistemam.codsistema) as sistema, ".
					" (SELECT nombre FROM msgusuariom WHERE {$this->_table}.codusuario=msgusuariom.codusuario) as nombre, ".
					" (SELECT apellido FROM msgusuariom WHERE {$this->_table}.codusuario=msgusuariom.codusuario) as apellido, ".
					" (SELECT nombre FROM msggrupom WHERE {$this->_table}.codgrupo=msggrupom.codgrupo) as grupo, ".
					" (SELECT evento FROM msgeventom WHERE {$this->_table}.evento=msgeventom.evento) as evento, ".
					" (SELECT nomlogico FROM msgmenum WHERE {$this->_table}.codmenu=msgmenum.codmenu ".
					" AND {$this->_table}.codsistema=msgmenum.codsistema) as pantalla ".
					" FROM {$this->_table} WHERE {$this->_table}.codempresa='{$this->codempresa}' ".
					" AND {$this->_table}.codusuario='{$this->codusuario}' AND {$this->_table}.codgrupo='{$this->codgrupo}' ".
				/*	" AND {$this->_table}.codsistema='{$this->codsistema}' ".*/
					" AND {$this->_table}.fecha='{$this->fecha}'";
		if ($this->evento!='')
		{
			$consulta .= " AND {$this->_table}.evento='{$this->evento}'";
		}
		elseif ($this->codsistema!='')
		{
			$consulta .= " AND {$this->_table}.codsistema='{$this->codsistema}'";
		}
	/*	elseif
		{
			$consulta .= "";
		}	*/
		//print $consulta;			
		$result = $conexionbd->Execute($consulta);
		if ($conexionbd->CompleteTrans())
		{
			return $result;
		}
		else
		{
			echo "Ha ocurrido un error";
		}
	}
}
?>