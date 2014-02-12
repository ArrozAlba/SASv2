<?php
session_start();
/******************************************************************************************
* @Clase para Manejar  el proceso de configurar el envío de correo.
* @fecha de creación: 10/11/2008
* @autor: Ing. Gusmary Balza
*************************************
* @fecha modificacion  
* @autor  
* @descripcion 
*******************************************************************************************/

require_once('../../base/librerias/php/general/sigesp_lib_funciones.php');
$sessionvalida = validarSession();
if (($_POST['objdata']) && ($sessionvalida))
{	
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_usuario.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistema.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_enviocorreo.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistemaventana.php');
		
	$_SESSION['session_activa']=time();
	$objdata = str_replace('\\','',$_POST['objdata']);	
	$objdata = json_decode($objdata,false);		
	
	$objCorreo = new EnvioCorreo();
	$objCorreo->codemp = $_SESSION['la_empresa']['codemp'];	
	pasarDatos(&$objCorreo,$objdata,&$evento);
	$objCorreo->nomfisico = $objdata->vista;	
	$objSistemaVentana = new SistemaVentana();		
	$objSistemaVentana->codemp = $_SESSION['la_empresa']['codemp'];	
	$objSistemaVentana->codusu = $_SESSION['la_logusr'];	
	$objSistemaVentana->codsis = $objdata->sistema;
	$objSistemaVentana->nomfisico = $objdata->vista;
	$evento = $objdata->oper;
	
	if ($objdata->datosAdmin)
	{
		$total = count($objdata->datosAdmin);
		for ($j=0; $j<$total; $j++)
		{
			$objCorreo->admin[$j] = new EnvioCorreo();
			pasarDatos(&$objCorreo->admin[$j],$objdata->datosAdmin[$j]);	
		}
	}	
	if ($objdata->datosEliminar)
	{
		$total = count($objdata->datosEliminar);
		for ($j=0; $j<$total; $j++)
		{
			$objCorreo->usuarioeliminar[$j] = new EnvioCorreo();
			pasarDatos(&$objCorreo->usuarioeliminar[$j],$objdata->datosEliminar[$j]);	
		}
	}
	
	switch ($evento)
	{		
		case 'obtenerMenu':
			$objSistemaVentana->criterio[0]['operador'] = "AND ";
			$objSistemaVentana->criterio[0]['criterio'] = "hijo";
			$objSistemaVentana->criterio[0]['condicion'] = "=";
			$objSistemaVentana->criterio[0]['valor'] = "0";
			$objSistemaVentana->codsis = $objdata->codsis;
			$datos = $objSistemaVentana->obtenerOpcionesMenu();
			if ($objSistemaVentana->valido)
			{
				if (!$datos->EOF)
				{
					$varJson=generarJson($datos);
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
		break;	
		
		case 'filtrardatos':			
			if ($objdata->cadena!='')
			{
				$objSistemaVentana->criterio[0]['operador'] = "AND ";
				$objSistemaVentana->criterio[0]['criterio'] = "$objdata->criterio";
				$objSistemaVentana->criterio[0]['condicion'] = "like";
				$objSistemaVentana->criterio[0]['valor'] = "'"."$objdata->cadena"."%"."'";	
			}	
			$objSistemaVentana->codsis = $objdata->codsis;						
			$datos = $objSistemaVentana->obtenerOpcionesMenu();
			if ($objSistemaVentana->valido)
			{
				if (!$datos->EOF)
				{
					$varJson=generarJson($datos);
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
		break;	
		
		case 'catalogodetalle':
			$datos = $objCorreo->obtenerUsuarios();
			if ($objCorreo->valido)
			{
				if (!$datos->EOF)
				{
					$varJson=generarJson($datos);
					echo $varJson;				
				}
				else
				{
					$arreglo[0]['mensaje'] = obtenerMensaje('DATA_NO_EXISTE'); 
					$arreglo[0]['valido']  = false;
					$respuesta  = array('raiz'=>$arreglo);
					$respuesta  = json_encode($respuesta);
					echo $respuesta;					
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
		break;			
		
		case 'incluir':
			$objSistemaVentana->campo = 'cambiar';
			$accionvalida=$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				$objCorreo->modificar();
				if ($objCorreo->valido)
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');	
				}
				else
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
				}
				$arreglo['valido']  = $objCorreo->valido;				
			}
			else
			{
				$arreglo['mensaje'] = obtenerMensaje('ACCION_NO_VALIDA');  
				$arreglo['valido']  = false;
			}	
			$respuesta  = array('raiz'=>$arreglo);
			$respuesta  = json_encode($respuesta);
			echo $respuesta;
		break;	
		
		case 'eliminar':
			$objSistemaVentana->campo = 'eliminar';
			$accionvalida=$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				$objCorreo->eliminar();
				if ($objCorreo->valido)
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');	
				}
				else
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
				}
				$arreglo['valido']  = $objCorreo->valido;				
			}
			else
			{
				$arreglo['mensaje'] = obtenerMensaje('ACCION_NO_VALIDA');  
				$arreglo['valido']  = false;
			}	
			$respuesta  = array('raiz'=>$arreglo);
			$respuesta  = json_encode($respuesta);
			echo $respuesta;
	}
	unset($objSistemaVentana);	
	unset($objCorreo);
	
}
?>	