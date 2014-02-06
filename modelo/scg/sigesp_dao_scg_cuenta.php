<?php
/*****************************************************************************
* @Modelo para las funciones de cuentas scg.
* @fecha de creación: 03/12/2008.
* @autor: Ing.Gusmary Balza
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
******************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/base/librerias/php/general/sigesp_lib_conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/modelo/sss/sigesp_dao_sss_registroeventos.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/modelo/sss/sigesp_dao_sss_registrofallas.php');

class CuentaScg extends ADOdb_Active_Record
{
	var $_table = 'scg_cuentas';
	public $valido = true;
	public $mensaje;
	public $seguridad = true;
	public $codsis;
	public $nomfisico;
	public $criterio;
	
	
/***********************************************************************************
* @Función que Busca uno o todas las cuentas spg
* @parametros: 
* @retorno:
* @fecha de creación: 26/11/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
************************************************************************************/		
	public function leer() 
 	{	
 		global $conexionbd;
 		try
		{	
			$consulta = " SELECT sc_cuenta, denominacion, status,1 as valido ".
						" FROM {$this->_table} ".
						" WHERE codemp='{$this->codemp}' ".
						" AND status='C'";
			$cadena=" ";
            $total = count($this->criterio);
            for ($contador = 0; $contador < $total; $contador++)
			{
            	$cadena.= $this->criterio[$contador]['operador']." ".$this->criterio[$contador]['criterio']." ".
 			               $this->criterio[$contador]['condicion']." ".$this->criterio[$contador]['valor']." ";
            }
            $consulta.= $cadena;
		 	$result = $conexionbd->Execute($consulta);
		 	return $result;
		}
		catch (exception $e) 
		{ 
			$this->valido  = false;	
			$this->mensaje='Error al consultar la Estructura Presupuestaria '.$consulta.' '.$conexionbd->ErrorMsg();
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
		$objEvento->codsis = $this->codsis;
		$objEvento->nomfisico = $this->nomfisico;
		$objEvento->evento = $evento;
		$objEvento->desevetra = $this->mensaje;
		$objEvento->incluir();
		unset($objEvento);
	}
}	
?>