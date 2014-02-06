<?php
/***********************************************************************************
* @Clase para Manejar los eventos del sistema
* @fecha de creación: 27/08/2008
* @autor: Ing. Yesenia Moreno de Lang
* **************************
* @fecha modificacion 
* @autor   
* @descripcion 
***********************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_conexion.php');

class Evento extends ADOdb_Active_Record
{
	var $_table='msgeventom';	
	
	public $mensaje;
	public $evento;
	public $valido;
	public $existe;
	public $cadena;
	public $criterio;
		
		
/***********************************************************************************
* @Función que incluye los eventos 
* @parametros: 
* @retorno: 
* @fecha de creación: 27/08/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
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

/***********************************************************************************
* @Función que obtiene los eventos. 
* @parametros: 
* @retorno:
* @fecha de creación: 28/08/2008
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	public function leer() 
 	{		
		global $conexionbd;
		$conexionbd->StartTrans();
		$consulta = "SELECT codempresa,evento,descripcion FROM {$this->_table} ";
		if ($this->cadena=='')
		{
			$result = $conexionbd->Execute($consulta);
		}
		elseif ($this->criterio=='')
		{
			$consulta .= " WHERE codempresa ='{$this->codempresa}' AND evento ='{$this->cadena}'";
		}
		else
		{
			$consulta .= " WHERE {$this->criterio} like '%{$this->cadena}%'";
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
}
?>

