<?php
/**
* @Clase compartida para las funciones de Configuración.
* @fecha de creación: 14/08/2008.
* @autor: Ing. Yesenia Moreno de Lang
**/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/base/librerias/php/general/sigesp_lib_conexion.php');

class Configuracion extends ADODB_Active_Record
{
	var $_table = 'mcdconfiguracionm';
	public $mensaje;
	public $valido;


/***********************************************************************************
* @Función que busca la configuración de la empresa
* @parametros: 
* @retorno: 
* @fecha de creación: 25/08/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function obtenerConfiguracion()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$ls_sql = "SELECT msjenvio, msjsmtp, msjservidor, msjpuerto, msjhtml ".
				  "  FROM {$this->_table} ".
				  " WHERE codempresa = '".$this->codempresa."'";
		$result = $conexionbd->Execute($ls_sql); 
		if ($conexionbd->CompleteTrans())
		{
			while (!$result->EOF)
			{
				$this->msjenvio =$result->fields["msjenvio"];
				$this->msjsmtp =$result->fields["msjsmtp"];
				$this->msjservidor =$result->fields["msjservidor"];
				$this->msjpuerto =$result->fields["msjpuerto"];
				$this->msjhtml =$result->fields["msjhtml"];
				$result->MoveNext();
			}
			$this->valido = true;
			$this->mensaje = "";
		}
		else
		{
			$this->valido = false;
			$this->mensaje = "No se ha encontrado la configuración";
		}
	}
}
?>