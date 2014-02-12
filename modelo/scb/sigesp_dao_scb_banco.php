<?php
/****************************************************************************
* @Modelo para las funciones de banco.
* @fecha de creación: 01/10/2008.
* @autor: Ing.Gusmary Balza
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*****************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/base/librerias/php/general/sigesp_lib_conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/modelo/sss/sigesp_dao_sss_registroeventos.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/modelo/sss/sigesp_dao_sss_registrofallas.php');

class Banco extends ADOdb_Active_Record
{
	var $_table = 'scb_banco';
	public $valido = true;
	public $seguridad = true;
	public $mensaje;
	public $codsis;
	public $nomfisico;
	public $criterio;
	
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
	public function seleccionarConexion(&$conexionbd)
	{
		global $conexionbd;

		if ($this->tipoconexionbd != 'DEFECTO')
		{
			$conexionbd = conectarBD($this->servidor, $this->usuario, $this->clave, $this->basedatos, $this->gestor);
		}
	}
		
	
/***********************************************************************************
* @Función para insertar un banco.
* @parametros: 
* @retorno:
* @fecha de creación: 01/10/2008.
* @autor: Ing.Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/			
	function incluir()
	{
		global $conexionbd;
		$this->mensaje = 'Incluyo el Banco '.$this->codban;
		$conexionbd->StartTrans();
		try 
		{ 
			$this->save();
		}	
		catch (exception $e) 
	   	{
			$this->valido  = false;				
			$this->mensaje='Error al Incluir el Banco '.$this->codban.' '.$conexionbd->ErrorMsg();
		} 
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('INSERTAR',$this->valido);
	}
	
	
	
/***********************************************************************************
* @Función que Busca bancos
* @parametros: 
* @retorno:
* @fecha de creación: 04/12/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
************************************************************************************/		
	public function leer() 
 	{	
 		global $conexionbd;
 		$this->seleccionarConexion(&$conexionbd); 	
 		try
		{	
			$consulta = " SELECT *,1 as valido ".
						" FROM {$this->_table} ".
						" WHERE codemp='{$this->codemp}' AND codban<>'---'";
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
			$this->mensaje='Error al consultar el Banco '.$consulta.' '.$conexionbd->ErrorMsg();
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