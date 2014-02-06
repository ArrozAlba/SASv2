<?php
/**
* @Clase compartida para las funciones de la Empresa.
* @fecha de creación: 30/07/2008.
* @autor: Ing.Gusmary Balza
**/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/base/librerias/php/general/sigesp_lib_conexion.php');


class Empresa extends ADODB_Active_Record
{
	var $_table = 'mcdempresam';
	public $mensaje;
	public $valido;
				
			
				
/**
* @Función para buscar las empresas.
* @fecha de creación: 30/07/2008.
* @autor: Ing.Gusmary Balza
**/					
	public function filtrarEmpresas()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$result = $conexionbd->Execute("SELECT codempresa,nombre FROM {$this->_table} "); 
		if ($conexionbd->CompleteTrans())
		{
			if ($result->EOF)
			{
				$result ='';
			}
			$this->valido = true;
			$this->mensaje = "";
		}
		else
		{
			$this->valido = false;
			$this->mensaje = "No se ha encontrado la empresa";
		}
		return $result;
	}


/**
* @Función para insertar una empresa por defecto.
* @fecha de creación: 04/08/2008.
* @autor: Ing.Gusmary Balza
**/
	function insertarEmpresa()
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
			$this->valido  = false;				
			$this->mensaje = "Ha ocurrido un error: No se pudo insertar la empresa";	
		} 
	}
	
}
?>