<?php
/******************************************************** 	
* @Modelo para la definición de usuarios por sistema.
* @versión: 1.0      
* @autor: Ing. Gusmary Balza
* @fecha creación: 11/08/2008
******************************************************/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_conexion.php');
require_once('sigesp_dao_msg_registroeventos.php');

class Usuariosistema extends ADOdb_Active_Record
{
	var $_table='msgusuariosistemad';
	
	public $mensaje;
	public $evento;
	public $valido;
	public $existe;
	
/*****************************************************
* @Función que incluye un usuario para un sistema
* @parametros: 
* @retorno:
* @fecha de creación: 11/08/2008
* @autor: Ing. Gusmary Balza
******************************************************/		
	public function incluir()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$this->save();
		if ($conexionbd->CompleteTrans())
		{
			$this->mensaje = "Inserto nuevo usuario: ".$this->codusuario." en el sistema ".$this->codsistema;
			$this->evento  = "INSERT";
			//$this->incluirSeguridad();	
			$this->valido = true;				
		}
		else
		{
			$this->mensaje = $conexionbd->ErrorMsg();
			$this->valido  = false;
		}
	}
		
/*****************************************************
* @Función que actualiza un usuario en un sistema
* @parametros: 
* @retorno:
* @fecha de creación: 11/08/2008
* @autor: Ing. Gusmary Balza
******************************************************/		
	public function modificar()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$this->Replace();
		if ($conexionbd->CompleteTrans())
		{
			$this->mensaje = "Modifico el usuario: ".$this->codusuario." para el sistema ".$this->codsistema;
			$this->evento  = "UPDATE";
			$this->valido = true;
		//	$this->incluirSeguridad();
		}
		else
		{
			$this->mensaje = $conexionbd->ErrorMsg();
			$this->valido  = false;
		}
	}	

/*****************************************************
* @Función que elimina un usuario de un sistema
* @parametros: 
* @retorno:
* @fecha de creación: 11/08/2008
* @autor: Ing. Gusmary Balza
******************************************************/		
	public function eliminar()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$this->delete();
		if ($conexionbd->CompleteTrans())
		{
			$this->mensaje = "Elimino el usuario: ".$this->codusuario." del sistema ".$this->codsistema;
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
	
	
	public function incluirSeguridad()
	{
		$objRegistro = new RegistroEventos();
		$objRegistro->codempresa  = $this->codempresa;  //asi
		//$objRegistro->codempresa    = '00001'; 
		$objRegistro->codsistema    = 'MSG'; //obtener
		$objRegistro->codusuario    = $this->codusuario;           
		$objRegistro->evento        = $this->evento;
		$objRegistro->funcionalidad = 'sigesp_vis_msg_sistema';    //obtener
		$objRegistro->desevento     = $this->mensaje;
		$objRegistro->codinterno    = '';	
		$objRegistro->insertarEvento();
	}	

}
?>
