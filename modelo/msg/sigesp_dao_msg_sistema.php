<?php
/***************************************************** 	
* @Modelo para la definición de Sistema.
* @versión: 1.0      
* @autor: Ing. Gusmary Balza
* @fecha creación: 08/08/2008
********************************************************
* @fecha modificación:
* @descripción:
* @autor:
*******************************************************/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_conexion.php');
require_once('sigesp_dao_msg_registroeventos.php');

class Sistema extends ADOdb_Active_Record
{
	var $_table='msgsistemam';	
	var $admin = array();
	
	public $mensaje;
	public $evento;
	public $valido;
	public $existe;
	public $cadena;
	public $criterio;
	
	
/*****************************************************
* @Función que  valida si un sistema ya existe
* @parametros: 
* @retorno:
* @fecha de creación: 08/08/2008
* @autor: Ing. Gusmary Balza
******************************************************/		
	function buscarCodigo()
	{
		global $conexionbd;
		$conexionbd->StartTrans();		
		$consulta = " SELECT codsistema FROM {$this->_table} WHERE codempresa='{$this->codempresa}' AND codsistema='{$this->codsistema}' ";
		$result = $conexionbd->Execute($consulta);
		if ($conexionbd->CompleteTrans())
		{
			if (!$result->EOF)
			{
				$this->existe = true;
				$this->mensaje = "El codigo para sistema ya existe";
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
* @Función que incluye un sistema
* @parametros: 
* @retorno:
* @fecha de creación: 08/08/2008
* @autor: Ing. Gusmary Balza
******************************************************/		
	public function incluir()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$this->save();
		if ($conexionbd->CompleteTrans())
		{
			$this->valido = true;
		}
		else
		{
			$this->valido = false;
		}
	}	

/*****************************************************
* @Función que incluye un sistema y su detalle
* @parametros: 
* @retorno:
* @fecha de creación: 08/08/2008
* @autor: Ing. Gusmary Balza
******************************************************/		
	public function incluirTodos()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$this->save();
		if ($conexionbd->CompleteTrans())
		{
			for ($i=0; $i < count($this->admin); $i++)
			{	
				
				$this->admin[$i]->codempresa = $this->codempresa;
				$this->admin[$i]->codsistema = $this->codsistema;				
				$this->admin[$i]->incluir();
			}
			$conexionbd->CompleteTrans();
			$this->valido = true;
		}
		else
		{
			$this->valido = false;
		}
	}	
	
/*****************************************************
* @Función que  actualiza el sistema y su detalle
* @parametros: 
* @retorno:
* @fecha de creación: 08/08/2008
* @autor: Ing. Gusmary Balza
******************************************************/		
	public function modificarTodos()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$this->replace();
		for ($i=0; $i<count($this->admin); $i++)
		{	
			$this->admin[$i]->codempresa = $this->codempresa;
			$this->admin[$i]->codsistema = $this->codsistema;				
			$this->admin[$i]->incluir();
		}
		$conexionbd->CompleteTrans();
		$this->valido = true;
		
	}	
	
/*****************************************************
* @Función que elimina un sistema
* @parametros: 
* @retorno:
* @fecha de creación: 08/08/2008
* @autor: Ing. Gusmary Balza
******************************************************/			
	public function eliminar()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		for ($i=0; $i < count($this->admin); $i++)
		{	
			$this->admin[$i]->codempresa = $this->codempresa;
			$this->admin[$i]->codsistema = $this->codsistema;				
			$this->admin[$i]->eliminar();
		}
		$conexionbd->CompleteTrans();
		//$this->valido = true;		
		$this->delete();	
		if ($conexionbd->CompleteTrans())
		{
			$this->mensaje = "Elimino el Sistema: ".$this->nombre;
			$this->evento = "DELETE";
			//$this->incluirSeguridad();	
			$this->valido = true;		
		}
		else
		{
			$this->mensaje = $conexionbd->ErrorMsg();
			$this->valido = false;
		}
		
	}
		
/*****************************************************
* @Función que busca los usuarios de un sistema
* @parametros: 
* @retorno:
* @fecha de creación: 08/08/2008
* @autor: Ing. Gusmary Balza
******************************************************/			
	public function obtenerUsuarios()
	{
		global $conexionbd;
		$consulta = " SELECT msgsistemam.codempresa, msgsistemam.codsistema, msgsistemam.nombre as nomsistema, ".
					" msgusuariom.codusuario, msgusuariom.nombre, msgusuariom.apellido, msgusuariom.email ".
					"  FROM msgsistemam, msgusuariom, msgusuariosistemad ".
					" WHERE msgsistemam.codempresa = '{$this->codempresa}' ".
					"   AND msgsistemam.codsistema = '{$this->codsistema}' ".
					"   AND msgsistemam.codempresa = msgusuariom.codempresa ".
					"   AND msgsistemam.codempresa = msgusuariosistemad.codempresa ".
					"   AND msgsistemam.codsistema = msgusuariosistemad.codsistema ".
					"   AND msgusuariom.codusuario=msgusuariosistemad.codusuario ";
		$result = $conexionbd->Execute($consulta);
		return $result;
	}
	
/*****************************************************
* @Función que elimina un usuario de un sistema
* @parametros: 
* @retorno:
* @fecha de creación: 08/08/2008
* @autor: Ing. Gusmary Balza
******************************************************/		
	public function eliminarUsuarios()
	{
		global $conexionbd;
		$objUsuario = new Usuario();
		$consulta = " DELETE FROM msgusuariosistemad WHERE codempresa='{$this->codempresa}' ". 
					" AND  codsistema= '{$this->codsistema}' AND codusuario='{$this->codusuario}'";
		$result = $conexionbd->Execute($consulta);
		return $result;		
	}	
	
/*****************************************************
* @Función que verifica si un sistema tiene usuarios.
* @parametros: 
* @retorno:
* @fecha de creación: 08/08/2008
* @autor: Ing. Gusmary Balza
******************************************************/			
	public function buscarUsuarioSistema() //no se esta usando, se validó en el catalogo, revisar!
	{
		global $conexionbd;
		$conexionbd->StartTrans();		
		$result = $conexionbd->Execute(" SELECT codsistema,codusuario FROM msgusuariosistemad ".
										" WHERE codempresa='{$this->codempresa}' AND codsistema='{$this->codsistema}' ");
		if ($conexionbd->CompleteTrans())
		{
			if (!$result->EOF)
			{
				$this->existe = true;
				$this->mensaje = "No se puede eliminar el sistema: Elimine sus usuarios";
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

/***********************************************************
* @Función que verifica si un sistema tiene perfil asignado.
* @parametros: 
* @retorno:
* @fecha de creación: 02/09/2008
* @autor: Ing. Gusmary Balza
************************************************************/			
	public function buscarPerfilSistema()
	{
		global $conexionbd;
		$conexionbd->StartTrans();		
		$result = $conexionbd->Execute(" SELECT DISTINCT codsistema FROM msgperfild ".
									   " WHERE codempresa='{$this->codempresa}' AND codsistema='{$this->codsistema}' ");
		if ($conexionbd->CompleteTrans())
		{
			if (!$result->EOF)
			{
				$this->existe = true;
				$this->mensaje = "No se puede eliminar el sistema: Posee un perfil";
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
	
/************************************************************
* @Función que verifica si un sistema tiene un menú asignado.
* @parametros: 
* @retorno:
* @fecha de creación: 03/09/2008
* @autor: Ing. Gusmary Balza
***********************************************************/			
	public function buscarMenuSistema()
	{
		global $conexionbd;
		$conexionbd->StartTrans();		
		$result = $conexionbd->Execute(" SELECT codsistema FROM msgmenum ".
										" WHERE codempresa='{$this->codempresa}' AND codsistema='{$this->codsistema}' ");
		if ($conexionbd->CompleteTrans())
		{
			if (!$result->EOF)
			{
				$this->existe = true;
				$this->mensaje = "No se puede eliminar el sistema: Posee un Menu";
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
* @Función que busca uno todos los sistemas
* @parametros: 
* @retorno:
* @fecha de creación: 08/08/2008
* @autor: Ing. Gusmary Balza
******************************************************/					
	public function leer() 
 	{		
		global $conexionbd;
		$conexionbd->StartTrans();
		$consulta = "SELECT codempresa,codsistema,nombre FROM {$this->_table} ";
		if ($this->cadena=='')
		{
			$result = $conexionbd->Execute($consulta);
		}
		elseif ($this->criterio=='')
		{
			$consulta .= " WHERE codsistema ='{$this->cadena}' AND codempresa ='{$this->codempresa}'";
		}
		else
		{
			$consulta .= " WHERE {$this->criterio} like '%{$this->cadena}%'";
	  	}
		//print ($consulta);
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
		
	
	public function incluirSeguridad()
	{
		$objRegistro = new RegistroEventos();
		$objRegistro->codempresa  = $this->codempresa;  //asi
		//$objRegistro->codempresa    = '00001'; 
		$objRegistro->codsistema    = 'MSG'; //obtener
		$objRegistro->codusuario    = '';           //obtener
		$objRegistro->evento        = $this->evento;
		$objRegistro->funcionalidad = 'sigesp_vis_msg_sistema';    //obtener
		$objRegistro->desevento     = $this->mensaje;
		$objRegistro->codinterno    = '';	
		$objRegistro->insertarEvento();
	}
	
}
?>

