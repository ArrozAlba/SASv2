<?php
session_start(); 
/********************************************** 	
* @Controlador para reporte de permisos.
* @versión: 1.0      
* @fecha creación: 27/08/2008
* @autor: Ing. Gusmary Balza
* *****************************
* @fecha modificacion  
* @autor  
* @descripcion  
***********************************************/
$sessionvalida = false;
if(array_key_exists('sigesp_codempresa',$_SESSION))
{
	$sessionvalida = true;
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_menu.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_perfil.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_funciones.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_crearreporte.php');
}
else
{
	$arreglo["mensaje"] = utf8_encode("Su Sessión ha Expirado. Ingrese nuevamente al sistema."); 
	$arreglo["valido"]  = false;
	$respuesta  = array('raiz'=>$arreglo);
	$respuesta  = json_encode($respuesta);
	echo $respuesta;
}

if (($_POST['objdata'])  && ($sessionvalida))	
{	
	$objdata    = str_replace("\\","",$_POST['objdata']);	
	$objdata    = json_decode($objdata,false);
	$objPerfil  = new Perfil();
	pasarDatos(&$objPerfil,$objdata,&$evento);
	$objPerfil->codempresa = $_SESSION['sigesp_codempresa'];	
	$objMenu = new Menu();		
	$objMenu->codempresa = $_SESSION['sigesp_codempresa'];	
	$objMenu->codusuario = $_SESSION['sigesp_codusuario'];	
	$objMenu->codsistema = $objdata->sistema;
	$objMenu->nomfisico = $objdata->vista;
	$evento = $objdata->operacion;	
	
	if ($evento=='permisos')
	{
		$objMenu->campo = 'imprimir';
		$accionvalida=$objMenu->verificarUsuario();
		if ($accionvalida)
		{
			$objReporte = new crearReporte();
			$objPerfil->orden = $objdata['orden'];
			$data = $objPerfil->leerReporte();
			if (count($data)>0)
			{
				$objReporte->crearXml('permisos',$data);
				$objReporte->nomRep = "permisos";
				echo $objReporte->mostrarReporte();	
			}
			else
			{
				echo '';
			}
		}
		else
		{
			echo '';
		}	
	}
	unset($objMenu);
	unset($objPerfil);
	unset($objReporte);
}
?>