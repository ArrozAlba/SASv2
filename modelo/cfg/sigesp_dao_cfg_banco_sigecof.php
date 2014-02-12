<?php
/****************************************************************************
* @Modelo para las funciones de banco sigecof.
* @fecha de creación: 30/09/2008.
* @autor: Ing.Gusmary Balza
***************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*******************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/base/librerias/php/general/sigesp_lib_conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/modelo/sss/sigesp_dao_sss_registroeventos.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/modelo/sss/sigesp_dao_sss_registrofallas.php');

class BancoSigecof extends ADOdb_Active_Record
{
	var $_table = 'sigesp_banco_sigecof';
	public $valido = true;
	public $seguridad = true;
	public $mensaje;
	public $codsis;
	public $nomfisico;
	
	
/***********************************************************************************
* @Función para insertar un banco sigecof.
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
		$this->mensaje = 'Incluyo el Banco Sigecof '.$this->codbansig;
		$conexionbd->StartTrans();
		try 
		{ 
			$this->save();
		}	
		catch (exception $e) 
	   	{
			$this->valido  = false;				
			$this->mensaje='Error al Incluir el Banco Sigecof '.$this->codbansig.' '.$conexionbd->ErrorMsg();
		} 
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('INSERTAR',$this->valido);
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
		$objEvento->codsis = $this->codsis;
		$objEvento->nomfisico = $this->nomfisico;
		$objEvento->evento = $evento;
		$objEvento->desevetra = $this->mensaje;
		$objEvento->incluir();
		unset($objEvento);
	}	
	
}	
?>