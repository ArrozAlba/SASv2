<?php
/***********************************************************************************
* @Clase para Manejar  para la definición de Evento
* @fecha de creación: 30/09/2008.
* @autor: Ing. Gusmary Balza
* **************************
* @fecha modificacion  
* @autor  
* @descripcion  
***********************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/base/librerias/php/general/sigesp_lib_conexion.php');

class Evento extends ADOdb_Active_Record
{
	var $_table = 'sss_eventos';
	public $valido=true;
	public $mensaje;
	public $nomfisico;
	public $criterio;
	public $cadena;
	
/***********************************************************************************
* @Función para insertar un evento.
* @parametros: 
* @retorno:
* @fecha de creación: 30/09/2008.
* @autor: Ing.Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/			
	function incluir()
	{
		global $conexionbd;
		
		$conexionbd->StartTrans();
		try 
		{ 
			$this->save();	
		}
		catch (exception $e) 
		{
			$this->valido  = false;	
			$this->mensaje='Error al Incluir el Evento '.$this->evento.' '.$conexionbd->ErrorMsg();
		}
		$conexionbd->CompleteTrans();
	}
	
	
/***********************************************************************************
* @Función que Busca uno o todos los eventos
* @parametros: 
* @retorno:
* @fecha de creación: 31/10/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function leer() 
 	{		
		global $conexionbd;
		try 
		{ 
			$consulta = "SELECT evento,deseve, 1 as valido FROM {$this->_table}";
			if (($this->criterio=='')&&(($this->cadena!='')))
			{
				$consulta .= " WHERE evento ='{$this->cadena}'";
			}
			elseif ($this->criterio!='')
			{
				$consulta .= " AND {$this->criterio} like '{$this->cadena}%'";
		  	}
			$result = $conexionbd->Execute($consulta);
			return $result;
		}
		catch (exception $e) 
		{ 
			$this->valido  = false;	
			$this->mensaje='Error al consultar el Evento '.$consulta.' '.$conexionbd->ErrorMsg();
			$this->incluirSeguridad('CONSULTAR',$this->valido);
	   	} 
 	}

 	
/***********************************************************************************
* @Función que Incluye el registro de la transacción exitosa
* @parametros: $evento
* @retorno:
* @fecha de creación: 10/10/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function incluirSeguridad($evento,$tipotransaccion)
	{
		if($tipotransaccion) // Transacción Exitosa
		{
			$objEvento = new RegistroEventos();
		}
		else // Transacción fallida
		{
			$objEvento = new RegistroFallas();
		}
		// Registro del Evento
		$objEvento->codemp = $this->codemp;
		$objEvento->codsis = 'SSS';
		$objEvento->nomfisico = $this->nomfisico;
		$objEvento->evento = $evento;
		$objEvento->desevetra = $this->mensaje;
		$objEvento->incluir();
		unset($objEvento);
	}
 	
}	
?>