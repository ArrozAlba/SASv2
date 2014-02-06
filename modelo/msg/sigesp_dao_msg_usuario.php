<?php
/****************************************************** 
* @Modelo para la definición de Usuario.
* @versión: 1.0      
* @autor: Ing. Gusmary Balza
* @fecha creación: 06/08//2008
********************************************************
* @fecha modificación:
* @descripción:
* @autor:
*******************************************************/

require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_conexion.php');
require_once('sigesp_dao_msg_registroeventos.php');

class Usuario extends ADOdb_Active_Record
{
	var $_table='msgusuariom';
	
	public $mensaje;
	public $evento;
	public $valido;
	public $existe;
	public $cadena;
	public $criterio;
	public $nuevopassword;
		
/*****************************************************
* @Función que  valida si un usuario ya existe
* @parametros: 
* @retorno:
* @fecha de creación: 06/08/2008
* @autor: Ing. Gusmary Balza
******************************************************/		
	function buscarCodigo()
	{
		global $conexionbd;
		$conexionbd->StartTrans();		
		$result = $conexionbd->Execute(" SELECT codusuario FROM {$this->_table} ".
									   " WHERE codempresa='{$this->codempresa}' AND codusuario='{$this->codusuario}' ");
		if ($conexionbd->CompleteTrans())
		{
			if (!$result->EOF)
			{
				$this->existe = true;
				$this->mensaje = "El login para usuario ya existe";
				$result->MoveNext();
			}
			else
			{			
				$this->existe = false;		
			}
		}
		else
		{
			echo "Ha ocurrido un error";
		}	
	
	}
	
/*****************************************************
* @Función que incluye un usuario
* @parametros: 
* @retorno:
* @fecha de creación: 06/08/2008
* @autor: Ing. Gusmary Balza
******************************************************/		
	function insertarUsuario()
	{
		global $conexionbd;
		$conexionbd->StartTrans();		
		$this->save();
		if ($conexionbd->CompleteTrans())
		{
			$this->valido = true;
			$this->mensaje = "Inserto nuevo usuario: ".$this->codusuario;
			$this->evento = "INSERT";
			//	$this->incluirSeguridad();	
		}
		else
	   	{
			$this->valido  = false;				
			$this->mensaje = "Ha ocurrido un error: No se pudo insertar la empresa";	
		}  
	}
	
/*****************************************************
* @Función que  actualiza un usuario
* @parametros: 
* @retorno:
* @fecha de creación: 06/08/2008
* @autor: Ing. Gusmary Balza
******************************************************/		
	public function modificar() //actualizar consulta cuando se incluya la foto
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		//$this->Replace();
		$consulta = " UPDATE {$this->_table} SET cedula='{$this->cedula}',nombre='{$this->nombre}', ".
					" apellido='{$this->apellido}',telefono='{$this->telefono}',email='{$this->email}', ".
					" estatus='{$this->estatus}',administrador='{$this->administrador}',nota='{$this->nota}' ".
					" WHERE codempresa='{$this->codempresa}' AND codusuario='{$this->codusuario}'";
		$result = $conexionbd->Execute($consulta);
		if ($conexionbd->CompleteTrans())
		{
			$this->mensaje = "Modifico el usuario: ".$this->codusuario;
			$this->evento = "UPDATE";
			$this->valido = true;
		//	$this->incluirSeguridad();
		}
		else
		{
			$this->mensaje = $conexionbd->ErrorMsg();
			$this->valido = false;
		}
	}
		
/*****************************************************
* @Función que elimina un usuario
* @parametros: 
* @retorno:
* @fecha de creación: 06/08/2008
* @autor: Ing. Gusmary Balza
******************************************************/		
	public function eliminar()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$this->delete();
		if ($conexionbd->CompleteTrans())
		{
			$this->mensaje = "Elimino el usuario: ".$this->codusuario;
			$this->evento = "DELETE";
		//	$this->incluirSeguridad();	
			$this->valido = true;		
		}
		else
		{
			$this->mensaje = $conexionbd->ErrorMsg();
			$this->valido = false;
		}
	}	
	
/*****************************************************
* @Función que busca un usuario
* @parametros: 
* @retorno:
* @fecha de creación: 06/08/2008
* @autor: Ing. Gusmary Balza
******************************************************/		
	public function leer() 
 	{		
		global $conexionbd;
		$conexionbd->StartTrans();
		$consulta = "SELECT codempresa,codusuario,cedula,nombre,apellido,telefono,email,estatus,administrador,fecultingreso,nota ".
					" FROM {$this->_table} WHERE codempresa='{$this->codempresa}' AND codusuario<>'--------------------'";
		if ($this->cadena=='')
		{
			$result = $conexionbd->Execute($consulta);
		}
		elseif ($this->criterio=='')
		{
			$consulta .= " AND codempresa='{$this->codempresa}' AND codusuario ='{$this->cadena}'";
		}
		else
		{
			$consulta .= " AND {$this->criterio} like '%{$this->cadena}%'";
	  	}
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
	
/*****************************************************
* @Función que busca los usuarios que están activos
* @parametros: 
* @retorno:
* @fecha de creación: 06/08/2008
* @autor: Ing. Gusmary Balza
******************************************************/		
	public function leerActivos() //para controlador de usuariogrupo
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$consulta = "SELECT codempresa,codusuario,cedula,nombre,apellido,telefono,email,estatus,administrador,fecultingreso,nota ". 					" FROM {$this->_table} WHERE codempresa='{$this->codempresa}' AND estatus='1' ";
		$result = $conexionbd->Execute($consulta);
		return $result;
	}
	
/*****************************************************
* @Función que verifica si el usuario es administrador
* @parametros: 
* @retorno:
* @fecha de creación: 06/08/2008
* @autor: Ing. Gusmary Balza
******************************************************/		
	public function verificarAdministrador() //para controlador cambio de password
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$consulta = " SELECT administrador FROM {$this->_table} WHERE codempresa='{$this->codempresa}' ".
					" AND codusuario='{$this->codusuario}' AND administrador='1'";
		$result = $conexionbd->Execute($consulta);
		if ($conexionbd->CompleteTrans())
		{
			if (!$result->EOF)
			{					
				$this->existe = true;
				$this->mensaje = "";
				$result->MoveNext();
			}
			else
			{					
				$this->existe = false;		
			}
		}
	/*	else
		{
			echo "Ha ocurrido un error";
		}	*/
	}
	
/*****************************************************
* @Función que verifica la contraseña de un usuario
* @parametros: 
* @retorno:
* @fecha de creación: 06/08/2008
* @autor: Ing. Gusmary Balza
******************************************************/		
	public function verificarPassword() //para controlador cambio de password
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$consulta = " SELECT password FROM {$this->_table} WHERE codempresa='{$this->codempresa}' ".
					" AND codusuario='{$this->codusuario}' AND password='{$this->password}'";
		$result = $conexionbd->Execute($consulta);
		if ($conexionbd->CompleteTrans())
		{
			if (!$result->EOF)
			{
				$this->existe = true;
				$this->mensaje = "";
				$result->MoveNext();
			}
			else
			{			
				$this->mensaje = "El password actual no es el correcto ";
				$this->existe = false;		
			}
		}
	}

/*****************************************************
* @Función que actualiza la contraseña de un usuario
* @parametros: 
* @retorno:
* @fecha de creación: 06/08/2008
* @autor: Ing. Gusmary Balza
******************************************************/		
	function actualizarPassword()   //para controlador cambio de password
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$consulta = " UPDATE {$this->_table} SET password='{$this->nuevopassword}' ".
					" WHERE codempresa='{$this->codempresa}' AND codusuario='{$this->codusuario}'";
		$result = $conexionbd->Execute($consulta);
		if ($conexionbd->CompleteTrans())
		{
			$this->mensaje = "Modifico el password al usuario: ".$this->codusuario;
			$this->evento = "UPDATE";
			$this->valido = true;
		//	$this->incluirSeguridad();
		}
		else
		{
			$this->mensaje = $conexionbd->ErrorMsg();
			$this->valido = false;
		}
	
	}

/*********************************************************
* @Función que verifica si un usuario está en un sistema
* @parametros: 
* @retorno:
* @fecha de creación: 06/08/2008
* @autor: Ing. Gusmary Balza
*********************************************************/		
	public function buscarUsuarioSistema()
	{
		global $conexionbd;
		$conexionbd->StartTrans();		
		$result = $conexionbd->Execute(" SELECT codsistema,codusuario FROM msgusuariosistemad ".
										" WHERE codempresa='{$this->codempresa}' AND codusuario='{$this->codusuario}' ");
		if ($conexionbd->CompleteTrans())
		{
			if (!$result->EOF)
			{
				$this->existe = true;
				$this->mensaje = "No se puede eliminar el usuario: Existe en un sistema";
				$result->MoveNext();
			}
			else
			{			
				$this->existe = false;		
			}
		}
		else
		{
			echo "Ha ocurrido un error";
		}	
	}
	
		
/*************************************************************
* @Función que Busca si un usuario esta asignado a un grupo
* @parametros: 
* @retorno:
* @fecha de creación: 03/09/2008
* @autor: Ing. Gusmary Balza

*************************************************************/			
	function buscarUsuarioGrupo()
	{
		global $conexionbd;
		$conexionbd->StartTrans();		
		$result = $conexionbd->Execute(" SELECT codusuario FROM msgusuariogrupod ".
									   " WHERE codempresa = '{$this->codempresa}' ".
									   " AND codusuario='{$this->codusuario}' "); 
		if ($conexionbd->CompleteTrans())
		{
			if (!$result->EOF)
			{
				$this->existe = true;
				$this->mensaje = "No se puede eliminar el usuario: Existe en un grupo";
				$result->MoveNext();
			}
			else
			{			
				$this->existe = false;		
			}
		}
		else
		{
			echo "Ha ocurrido un error";
		}	
	}
	
	
/*****************************************************
* @Función que verifica si un usuario tiene un perfil 
* @parametros: 
* @retorno:
* @fecha de creación: 06/08/2008
* @autor: Ing. Gusmary Balza
******************************************************/		
	public function buscarPerfilUsuario()
	{
		global $conexionbd;
		$conexionbd->StartTrans();		
		$result = $conexionbd->Execute(" SELECT DISTINCT codusuario FROM msgperfild ".
									   " WHERE codempresa='{$this->codempresa}' AND codusuario='{$this->codusuario}' ");
		if ($conexionbd->CompleteTrans())
		{
			if (!$result->EOF)
			{
				$this->existe = true;
				$this->mensaje = "No se puede eliminar el usuario: Posee un perfil";
				$result->MoveNext();
			}
			else
			{			
				$this->existe = false;		
			}
		}
		else
		{
			echo "Ha ocurrido un error";
		}	
	}
	
	
	function incluirSeguridad()
	{
		$objRegistro = new RegistroEventos();
		$objRegistro->codempresa  = $this->codempresa;  //asi
		//$objRegistro->codempresa    = '00001'; 
		$objRegistro->codsistema    = 'MSG'; //obtener
		$objRegistro->codusuario    = $this->codusuario;           //obtener
		$objRegistro->evento        = $this->evento;
		$objRegistro->funcionalidad = 'sigesp_vis_msg_usuario';    //obtener
		$objRegistro->desevento     = $this->mensaje;
		$objRegistro->codinterno    = '';	
		$objRegistro->insertarEvento();
	}

}	
?>	