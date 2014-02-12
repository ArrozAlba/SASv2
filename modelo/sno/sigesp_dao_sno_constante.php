<?php
/*****************************************************************************
* @Modelo para las funciones de tipo de personal.
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

class Constante extends ADOdb_Active_Record
{
	var $_table = 'sno_constante';
	public $valido = true;
	public $mensaje;
	public $cadena;
	public $criterio;
	public $seguridad = true;
	public $codsis;
	public $nomfisico;
	
/***********************************************************************************
* @Función para insertar una constante
* @parametros: 
* @retorno:
* @fecha de creación: 09/10/2008.
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
		$this->mensaje='Incluyo la Constante de Nómina '.$this->codcons;
		try 
		{
			$this->save();
		}		
		catch (exception $e) 
	   	{
			$this->valido  = false;				
			$this->mensaje='Error al Incluir la Constante '.$this->codcons.' '.$conexionbd->ErrorMsg();
		} 
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('INSERTAR',$this->valido);
	}
	
	
/***********************************************************************************
* @Función que Busca una,varias todas las constantes
* @parametros: 
* @retorno:
* @fecha de creación: 09/10/2008
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
			$consulta = " SELECT DISTINCT codnom,codcons,nomcon, 1 as valido ".
						" FROM {$this->_table} ".
						" WHERE codemp='{$this->codemp}'";
			/*if (($this->criterio=='')&&(($this->cadena!='')))
			{
				$consulta .= " AND codcons ='{$this->cadena}'";
			}
			elseif ($this->criterio!='')
			{
				$consulta .= " AND {$this->criterio} like '%{$this->cadena}%'";
		  	}*/
		  	$cadena=" ";
            $total = count($this->criterio);
            for ($contador = 0; $contador < $total; $contador++)
			{
            	$cadena.= $this->criterio[$contador]['operador']." ".$this->criterio[$contador]['criterio']." ".
 			               $this->criterio[$contador]['condicion']." ".$this->criterio[$contador]['valor']." ";
            }
            $consulta.= $cadena;
		  	$consulta.= " ORDER BY codcons";
		   	$result = $conexionbd->Execute($consulta);	
		 	return $result;	
		}
		catch (exception $e) 
		{ 
			$this->valido  = false;	
			$this->mensaje='Error al consultar la Constante '.$consulta.' '.$conexionbd->ErrorMsg();
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