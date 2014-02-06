<?php
/********************************************************************************* 	
* @Modelo para la definición de Empresa.
* @versión: 1.0  
* @fecha creación: 30/07/2008.
* @autor: Ing. Gusmary Balza
* **************************************************************************
* @fecha modificacion  08/09/2008
* @autor  Ing. Yesenia Moreno de Lang
* @descripcion  Se Modifico para que trabajar con el modelo anterior
**********************************************************************************/
require_once('../../base/librerias/php/general/sigesp_lib_conexion.php');

class Empresa extends ADODB_Active_Record
{
	var $_table = 'sigesp_empresa';
	public $valido = true;
	public $seguridad = true;
	public $mensaje;
	public $codsis;
	public $nomfisico;
	public $servidor;
	public $usuario;
	public $clave;
	public $basedatos;
	public $gestor;
	public $tipoconexionbd = 'DEFECTO';

	
/***********************************************************************************
* @Función para seleccionar con que conexion a Base de Datos se va a trabajar
* @parametros: 
* @retorno:
* @fecha de creación: 06/11/2008.
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/			
	public function seleccionarConexion (&$conexionbd)
	{
		global $conexionbd;
		
		if ($this->tipoconexionbd != 'DEFECTO')
		{
			$conexionbd = conectarBD($this->servidor, $this->usuario, $this->clave, $this->basedatos, $this->gestor);
		}
	}
	
	
/***********************************************************************************
* @Función para buscar las empresas.
* @parametros: 
* @retorno:
* @fecha de creación: 30/07/2008.
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function filtrarEmpresas()
	{
		global $conexionbd;
		
		$this->seleccionarConexion(&$conexionbd);
		
		$this->valido = true;
		$cadena = "SELECT * ".
				  "  FROM {$this->_table} ";
		$result = $conexionbd->Execute($cadena); 
		if ($result->EOF)
		{
			$this->valido = false;
			$this->mensaje = 'No se ha encontrado la empresa';
		}
		return $result;
	}


/***********************************************************************************
*  @Función para insertar una empresa por defecto.
* @parametros: 
* @retorno:
* @fecha de creación: 04/08/2008.
* @autor: Ing.Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function insertarEmpresa()
	{
		global $conexionbd;
		$this->mensaje = 'Incluyo la Empresa '.$this->nombre;
		$conexionbd->StartTrans();
		try 
		{ 
			$this->save();
		}	
		catch (exception $e) 
	   	{
			$this->valido  = false;				
			$this->mensaje = 'Error al Incluir la Empresa '.$this->nombre.' '.$conexionbd->ErrorMsg();
		} 
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('INSERTAR',$this->valido);
	}
	
}
?>