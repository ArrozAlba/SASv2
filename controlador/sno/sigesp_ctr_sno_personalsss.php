<?php
session_start();
/****************************************************************************** 	
* @Controlador para las funciones de personal.
* @versión: 1.0  
* @fecha creación: 07/10/2008
* @autor: Ing. Gusmary Balza
******************************************************************************
* @fecha modificacion  15/10/2008
* @autor  Ing. Yesenia Moreno de Lang
* @descripcion  Se agrego la opción de seguridad
********************************************************************************/
require_once('../../base/librerias/php/general/sigesp_lib_funciones.php');
$sessionvalida = validarSession();
if (($_POST['objdata']) && ($sessionvalida))
{	
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sno/sigesp_dao_sno_tipopersonalsss.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistemaventana.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_crearreporte.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_validaciones.php');

	$_SESSION['session_activa']=time();
	$objdata = str_replace("\\","",$_POST['objdata']);	
	$objdata = json_decode($objdata,false);		
	$objPersonal = new TipoPersonalSSS();		
	pasarDatos(&$objPersonal,$objdata,&$evento);
	$objPersonal->codemp = $_SESSION['la_empresa']['codemp'];	
	$objPersonal->codsis = $objdata->sistema;
	$objPersonal->nomfisico = $objdata->vista;	
	$objSistemaVentana = new SistemaVentana();		
	$objSistemaVentana->codemp = $_SESSION['la_empresa']['codemp'];	
	$objSistemaVentana->codusu = $_SESSION['la_logusr'];	
	$objSistemaVentana->codsis = $objdata->sistema;
	$objSistemaVentana->nomfisico = $objdata->vista;
	$evento = $objdata->oper;
	
	switch ($evento)
	{
		case 'catalogo':
			$objSistemaVentana->campo = 'leer';
			$accionvalida = $objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				$datos = $objPersonal->leer();
				if ($objPersonal->valido)
				{
					if (!$datos->EOF)
					{
						$varJson = generarJson($datos);
						echo $varJson;
					}		
				}
				else
				{
					$arreglo[0]['mensaje'] = obtenerMensaje('OPERACION_FALLIDA'); 
					$arreglo[0]['valido']  = false;
					$respuesta  = array('raiz'=>$arreglo);
					$respuesta  = json_encode($respuesta);
					echo $respuesta;					
				}
							
			}
			else
			{
				$arreglo[0]['mensaje'] = obtenerMensaje('ACCION_NO_VALIDA'); 
				$arreglo[0]['valido']  = false;
				$respuesta  = array('raiz'=>$arreglo);			
				$respuesta  = json_encode($respuesta);
				echo $respuesta;
			}
		break;	
	}
	unset($objSistemaVentana);
	unset($objPersonal);
}		
?>