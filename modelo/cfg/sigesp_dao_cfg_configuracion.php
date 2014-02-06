<?php
/***********************************************************************************
* @Modelo para la definición del servidor de Correo
* @fecha de creación: 09/10/2008.
* @autor: Ing. Yesenia Moreno de Lang
* **************************
* @fecha modificacion  
* @autor  
* @descripcion  
***********************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_conexion.php');

class Configuracion extends ADODB_Active_Record
{
	var $_table = 'sigesp_config';
	public $mensaje;
	public $valido= true;
	public $nomfisico;
	public $codsis;
	public $criterio = Array();
	

/***********************************************************************************
* @Función que busca a que sistemas se les ha hecho la apertura
* @parametros: 
* @retorno: 
* @fecha de creación: 21/10/2008
* @autor: Ing. Yesenia Moreno de Lang
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
			$consulta = " SELECT codemp, codsis, seccion, entry, type, value, 1 as valido ".
						"  FROM {$this->_table}";
			$cadena=" ";
			$total = count($this->criterio);
			for ($contador = 0; $contador < $total; $contador++)
			{
				$cadena.= $this->criterio[$contador]['operador']." ".$this->criterio[$contador]['criterio']." ".
						  $this->criterio[$contador]['condicion']." ".$this->criterio[$contador]['valor']." ";
			}
			$consulta.=$cadena;
			$result = $conexionbd->Execute($consulta); 
			return $result; 
		}
		catch (exception $e) 
		{
			$this->valido = false;
		}
		$conexion->Close();
	}
	

/***********************************************************************************
* @Función para insertar una configuración
* @parametros: 
* @retorno:
* @fecha de creación: 27/10/2008.
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/			
	public function incluir()
	{
		global $conexionbd;
		$this->save();
		if($conexionbd->HasFailedTrans())
		{
			$this->valido  = false;	
			$this->mensaje=$conexionbd->ErrorMsg();
		}
	}	

	
/***********************************************************************************
* @Función que Elimina una Configuración
* @parametros: 
* @retorno:
* @fecha de creación: 27/10/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function eliminar()
	{
		global $conexionbd;
		$this->delete();	
		if($conexionbd->HasFailedTrans())
		{
			$this->valido  = false;	
			$this->mensaje=$conexionbd->ErrorMsg();
		}
	}	
}
?>