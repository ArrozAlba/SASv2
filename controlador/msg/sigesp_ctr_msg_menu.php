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

require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_menu.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_funciones.php');
if ($_POST['objdata'])	
{
	$objdata = str_replace("\\","",$_POST['objdata']);	
	$objdata = json_decode($objdata,false);	
	$objMenu    = new Menu();
	$objMenu->codempresa=$_SESSION['sigesp_codempresa'];
	$objMenu->codusuario=$_SESSION['sigesp_codusuario'];
	$objMenu->codsistema=$objdata->codsistema;
	$evento = $objdata->operacion;
	switch ($evento)
	{
		case 'menu':
			$datos = $objMenu->obtenerMenuUsuario();
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
			$objMenu->nomfisico=$objdata->nomfisico;
			$datos = $objMenu->obtenerBarraHerramientaUsuario();
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