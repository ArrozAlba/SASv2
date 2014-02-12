<?php
session_start();
/***********************************************************************************
* @Clase para Manejar el menu del sistema según la permisología del usuario
* @fecha de creación: 07/08/2008
* @autor: Ing. Gusmary Balza
* **************************
* @fecha modificacion 
* @autor   
* @descripcion 
***********************************************************************************/

require_once('../../base/librerias/php/general/sigesp_lib_funciones.php');
$sessionvalida = true;//validarSession();
if (($_POST['objdata']) && ($sessionvalida))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistemaventana.php');
	
	$objdata = str_replace('\\','',$_POST['objdata']);	
	$objdata = json_decode($objdata,false);	
	$objSistemaVentana    = new SistemaVentana();
	$objSistemaVentana->codemp = $_SESSION['la_empresa']['codemp'];
	$objSistemaVentana->codusu = $_SESSION['la_logusr']; 
	$objSistemaVentana->codsis = $objdata->codsis;
	$evento = $objdata->operacion;
	switch ($evento)
	{
		case 'menu':
			$datos = $objSistemaVentana->obtenerMenuUsuario();
			if (count($datos)>0)
			{
				$varJson=generarJson($datos);
				echo $varJson;				
			}
			else 
			{	
				echo '';
			}
		break;

		case 'barraherramienta':
			$objSistemaVentana->nomfisico = $objdata->nomfisico;
			$datos = $objSistemaVentana->obtenerBarraHerramientaUsuario();
			if (count($datos)>0)
			{
				$varJson=generarJson($datos);
				echo $varJson;				
			}
			else 
			{	
				echo '';
			}
		break;
	}
}
?>