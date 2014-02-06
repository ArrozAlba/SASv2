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

class Correo extends ADODB_Active_Record
{
	var $_table = 'sigesp_correo';
	public $mensaje;
	public $valido= true;
	

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
		try 
		{ 
			$consulta = "SELECT msjenvio, msjsmtp, msjservidor, msjpuerto, msjhtml, ".
					    "  FROM {$this->_table} ".
					    " WHERE codemp = '".$this->codemp."'";
			$result = $conexionbd->Execute($consulta); 
			while (!$result->EOF)
			{
				$this->msjenvio =$result->fields["msjenvio"];
				$this->msjsmtp =$result->fields["msjsmtp"];
				$this->msjservidor =$result->fields["msjservidor"];
				$this->msjpuerto =$result->fields["msjpuerto"];
				$this->msjhtml =$result->fields["msjhtml"];
				$result->MoveNext();
			}
			$result->Close();
		}
		catch (exception $e) 
		{
			$this->valido = false;
			$this->mensaje='Error al obtener la Configuración del Correo '.$conexionbd->ErrorMsg();
		}
	}
}
?>