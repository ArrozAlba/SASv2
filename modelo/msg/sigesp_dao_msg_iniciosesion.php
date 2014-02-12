<?php
/*************************************************************
* @Clase compartida para las funciones del Inicio de Sesión
* @fecha de creación: 17/07/2008.
* @autor: Ing.Gusmary Balza
*************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***************************************************************/

require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/base/librerias/php/general/sigesp_lib_conexion.php');

class InicioSesion extends ADOdb_Active_Record
{
	var $_table = 'msgusuariom';
	public $valido;
	public $mensaje;
	

/******************************************************************
* @Función que verifica que los datos del usuario sean correctos.
* @parametros: 
* @retorno: 
* @fecha de creación: 17/07/2008.
* @autor: Gusmary Balza.
******************************************************************/
	function verificarUsuario()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$result = $conexionbd->Execute(" SELECT codempresa,codusuario,cedula,nombre FROM {$this->_table} ".
		 			 		 		   " WHERE codusuario='".$this->codusuario."' AND password='".$this->password."' ");
		if ($conexionbd->CompleteTrans())
		{
			if (!$result->EOF)
			{	
				$bloqueado = $this->verificarBloqueo();
				if ($bloqueado)
				{
					$this->valido = false;
					$this->mensaje = "Usuario bloqueado: Contacte al administrador del sistema";
				}
				else
				{									
					$this->actualizarAcceso();
					$_SESSION['sigesp_codempresa'] = $result->fields['codempresa'];
					$_SESSION['sigesp_codusuario'] = $result->fields['codusuario'];
					$_SESSION['sigesp_cedula'] 	   = $result->fields['cedula'];
					$_SESSION['sigesp_nombre']     = $result->fields['nombre'];
					unset($_SESSION['sigesp_intentos']);
					$result->MoveNext();
					$this->valido = true;
				}	
			}			
			else
			{	
				$intentos = $_SESSION['sigesp_intentos']++;
				if ($intentos > 3)
				{
					$this->bloquearUsuario();
					$this->valido = false;
					$this->mensaje = "Usuario sera bloqueado";
				}
				else
				{	
					$this->valido = false;
					$this->mensaje = "Usuario o password incorrectos";
				}
					
			}
		}
		else
		{
			$this->mensaje = "Ha ocurrido un error: Contacte al administrador del Sistema";	
			$this->valido = false;
		}
	}
	
	
/************************************************************
* @Función que verifica si un usuario está bloqueado.
* @parametros:
* @retorno: 
* @fecha de creación: 01/08/2008.
* @autor: Gusmary Balza.
**************************************************************/	
	function verificarBloqueo()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$result = $conexionbd->Execute(" SELECT codusuario,cedula,nombre FROM {$this->_table} ".
		 			 		 		   " WHERE codusuario='".$this->codusuario."' AND estatus='3' ");
		if ($conexionbd->CompleteTrans())
		{
			if (!$result->EOF)
			{
				$bloqueado = true;
				$result->MoveNext();
			}
			else
			{
				$bloqueado = false;
			}
		}
	}
	
	
/************************************************
* @Función que bloquea un usuario.
* @parametros: 
* @retorno: 
* @fecha de creación: 01/08/2008.
* @autor: Gusmary Balza.
**************************************************/	
	function bloquearUsuario()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$result = $conexionbd->Execute(" UPDATE {$this->_table} SET estatus='3' WHERE codusuario='".$this->codusuario."' ");
		if ($conexionbd->CompleteTrans())
		{
			return $result;
		}
	}


/********************************************************
* @Función que actualiza el último acceso de un usuario.
* @parametros: 
* @retorno: 
* @fecha de creación: 17/07/2008.
* @autor: Gusmary Balza.
*********************************************************/
	function actualizarAcceso()
	{
		$fecha = date("Y/m/d");
		global $conexionbd;
		$conexionbd->StartTrans();
		$result = $conexionbd->Execute(" UPDATE {$this->_table} SET fecultingreso='".$fecha."' ".
									   " WHERE codusuario='".$this->codusuario."' ");
		if ($conexionbd->CompleteTrans())
		{
			$valido = true;
			return $result;
		}
		else
		{
			$valido = false;
			echo "Transaccion fallida: Ha ocurrido un error";
		}
	}
	
	
/******************************************************
* @Función que busca si existen usuarios registrados.
* @parametros: 
* @retorno: 
* @fecha de creación: 17/07/2008.
* @autor: Gusmary Balza.
*******************************************************/	
	function seleccionarUsuario()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$conexionbd->Execute("SELECT codusuario,nombre,password FROM {$this->_table}");
		if ($conexionbd->CompleteTrans())
		{
			if (!$conexionbd->EOF)
			{	
				$valido=true;	
				$conexionbd->MoveNext();	
			}
			else
			{
				$valido = false;
			}
		}
		else
		{
			echo "Transaccion fallida: Ha ocurrido un error";
			$valido = false;
		}
	}

}
?>