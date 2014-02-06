<?php
/***********************************************************************************
* @librería que contiene la conexión a la Base de Datos
* @fecha de creación: 16/05/2008 
* @autor: Ing. Yesenia Moreno de Lang
* **************************
* @fecha modificacion 
* @autor   
* @descripcion 
***********************************************************************************/

include('../../base/librerias/php/adodb/adodb.inc.php');
include('../../base/librerias/php/adodb/adodb-exceptions.inc.php');
include('../../base/librerias/php/adodb/adodb-active-record.inc.php');

$conexionbd = &ADONewConnection($_SESSION['sigesp_gestor_apr']);
$conexionbd->Connect($_SESSION['sigesp_servidor_apr'],$_SESSION['sigesp_usuario_apr'],$_SESSION['sigesp_clave_apr'],$_SESSION['sigesp_basedatos_apr']);
if ($conexionbd != false)
{
	$conexionbd->SetFetchMode(ADODB_FETCH_ASSOC);
	ADOdb_Active_Record::SetDatabaseAdapter($conexionbd);
	$ADODB_ASSOC_CASE = 0;
	$ADODB_FORCE_IGNORE = 0;
	
}

/***********************************************************************************
* @Función que se conecta a una base de datos según los parámetros 
* @parametros: 
* @retorno:
* @fecha de creación: 20/10/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function conectarBD($servidor, $usuario, $clave, $basedatos, $gestor) 
	{ 
		$conexion=false;
		try
		{
			$conexion=&ADONewConnection($gestor);
				 
			$conexion->Connect($servidor, $usuario, $clave, $basedatos);
			
			if($conexion===false)
			{
				return false;
			}
			else
			{
				$conexion->SetFetchMode(ADODB_FETCH_ASSOC);
			}
			return $conexion;
		}
		catch (exception $e) 
		{
			return false;
		}
	}
?>
