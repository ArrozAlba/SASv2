<?php
session_start();
/***********************************************************************************
* @Clase para Manejar el Escritorio del sistema según la permisología del usuario
* @fecha de creación: 07/08/2008
* @autor: 
* **************************
* @fecha modificacion  07/10/2008
* @autor   Ing. Yesenia Moreno de Lang
* @descripcion Se realizó el escritorio dinámico según la permisología del usuario
* **************************
* @fecha modificacion  07/10/2008
* @autor   Ing. Yesenia Moreno de Lang
* @descripcion Se realizó la modificación para que la cabecera obtenga el usuario y nombre del sistema
***********************************************************************************/
require_once('../../base/librerias/php/general/sigesp_lib_funciones.php');
$sessionvalida = true;//validarSession();
if (($_POST['objdata']) && ($sessionvalida))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_derechosusuario.php');
	
	$objdata = str_replace('\\','',$_POST['objdata']);	
	$objdata = json_decode($objdata,false);	
	$objDerechosUsuario    = new DerechosUsuario();
	$objDerechosUsuario->codemp=$_SESSION['la_empresa']['codemp'];
	$objDerechosUsuario->codusu=$_SESSION['la_codusu'];
	$evento = $objdata->operacion;
	switch ($evento)
	{
		case 'escritorio':
			$datos = $objDerechosUsuario->obtenerEscritorioUsuario();
			if (!$datos->EOF)
			{
				$varJson=generarJson($datos);
				echo $varJson;				
			}
			else 
			{	
				$arreglo[0]['mensaje'] = obtenerMensaje('SESION_EXPIRADA'); 
				$arreglo[0]['valido']  = false;
				$respuesta  = array('raiz'=>$arreglo);
				$respuesta  = json_encode($respuesta);
				echo $respuesta;
			}
			$datos->close();
		break;
		
		case 'cabecera':
			$objDerechosUsuario->codsis=$objdata->codsis;;
			$datos = $objDerechosUsuario->obtenerSistemaUsuario();
			if (!$datos->EOF)
			{
				$varJson=generarJson($datos);
				echo $varJson;				
			}
			else 
			{	
				$arreglo[0]['mensaje'] = obtenerMensaje('SESION_EXPIRADA'); 
				$arreglo[0]['valido']  = false;
				$respuesta  = array('raiz'=>$arreglo);
				$respuesta  = json_encode($respuesta);
				echo $respuesta;
			}
			$datos->close();
		break;
	}
	unset($objDerechosUsuario);
}
?>