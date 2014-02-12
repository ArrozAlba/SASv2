<?php
session_start();
/***********************************************************************************
* @Clase para Manejar la descarga de archivos dada una ruta
* @fecha de creación: 27/10/2008
* @autor: Ing. Yesenia Moreno de Lang
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
	$objSistemaVentana->codsis = $objdata->sistema;
	$objSistemaVentana->nomfisico = $objdata->vista;
	$evento = $objdata->operacion;
	switch ($evento)
	{
		case 'descargar':
			$contador=-1;
			$objSistemaVentana->campo = 'descargar';
			$accionvalida=true;//$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				$lista = array();
				$manejador = opendir($objdata->ruta);
				$contador=0;
				while (false!==$archivo = readdir($manejador))
				{
					 if(($archivo != '.') && ($archivo != '..') && ($archivo != '.svn'))
					 {
					 	$arreglo[$contador]['valido']=true;
					 	$arreglo[$contador]['archivo']="".$archivo."";
					 	$arreglo[$contador]['tope']=$contador*20;
					 	$arreglo[$contador]['ruta']="../../".$objdata->ruta."";
					 	$contador++;
					 }
				}
				if($contador<0)
				{
				 	$arreglo[0]['valido'] = false;
					$arreglo[0]['mensaje'] = obtenerMensaje('ARCHIVO_NO_EXISTE');
				}
			}
			else
			{
				$arreglo[0]['mensaje'] = obtenerMensaje('ACCION_NO_VALIDA');  
				$arreglo[0]['valido']  = false;
			}	
			$respuesta  = array('raiz'=>$arreglo);
			$respuesta  = json_encode($respuesta);
			echo $respuesta;
		break;


	}
}
?>