<?php
session_start(); 
/*************************************************************************************** 	
* @Controlador para proceso de cambio de password.
* @versión: 1.0      
* @fecha creación: 18/08/2008
* @autor: Ing. Gusmary Balza
* **********************************
* @fecha modificacion: 29/10/2008  
* @descripcion: Adaptar a los nuevos estandares 
* @autor: Ing. Gusmary Balza
****************************************************************************************/
require_once('../../base/librerias/php/general/sigesp_lib_funciones.php');
$sessionvalida = validarSession();
if (($_POST['objdata']) && ($sessionvalida))
{		
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_usuario.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistemaventana.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_funciones.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_validaciones.php');

	$_SESSION['session_activa'] = time();

	$objdata = str_replace("\\","",$_POST['objdata']);	
	$objdata = json_decode($objdata,false);	
	$objUsuario = new Usuario();
	pasarDatos(&$objUsuario,$objdata,&$evento);
	$objUsuario->codemp = $_SESSION['la_empresa']['codemp'];
	$objUsuario->codusu = $_SESSION['la_logusr'];
	$objUsuario->codsis = $objdata->sistema;
	$objUsuario->nomfisico = $objdata->vista;	
	$objSistemaVentana = new SistemaVentana();		
	$objSistemaVentana->codemp = $_SESSION['la_empresa']['codemp'];	
	$objSistemaVentana->codusu = $_SESSION['la_logusr'];	
	$objSistemaVentana->codsis = $objdata->sistema;
	$objSistemaVentana->nomfisico = $objdata->vista;
	$evento = $objdata->oper;
	
	switch ($evento)
	{
		case 'revisarUsuario':  
			$objUsuario->criterio[0]['operador'] = "AND";
			$objUsuario->criterio[0]['criterio'] = "admusu";
			$objUsuario->criterio[0]['condicion'] = "=";
			$objUsuario->criterio[0]['valor'] = "1";
			$datos = $objUsuario->verificarExistenciaDato();
			if ($objUsuario->valido)
			{					
				if (!$datos->EOF)
				{
					$arreglo[0]['existe']  = true;								
				}
				else
				{
					$arreglo[0]['existe']  = false;											
				}				
			}
			else 
			{	
				$arreglo[0]['mensaje'] = obtenerMensaje('OPERACION_FALLIDA'); 
				$arreglo[0]['valido']  = false;
			}				
			$respuesta  = array('raiz'=>$arreglo);
			$respuesta  = json_encode($respuesta);
			echo $respuesta;	
		break;			
		
		case 'actualizar':	
			$objSistemaVentana->campo = 'cambiar';
			$accionvalida = $objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				if ($objdata->administrador==false)
				{				
					$correcto = (validaciones($objdata->password,'50','caracteres')) && (validaciones($objdata->nuevopassword,'50','caracteres'));
					if ($correcto)
					{												
						$objUsuario->criterio[0]['operador'] = "AND";
						$objUsuario->criterio[0]['criterio'] = "pwdusu";
						$objUsuario->criterio[0]['condicion'] = "=";
						$objUsuario->criterio[0]['valor'] = "'".$objdata->password."'";
						//$objUsuario->verificarPassword(); //caso no administrador
						$objUsuario->verificarExistenciaDato();
						if ($objUsuario->valido)
						{
							if ($objUsuario->existe===true)
							{
								$objUsuario->password = $objdata->nuevopassword;
								$objUsuario->actualizarPassword();
								if ($objUsuario->valido)
								{ 
									$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');	
								}
								else
								{
									$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');								
								}
								$arreglo['valido']  = $objUsuario->valido;
							}
							else
							{
								$arreglo['mensaje'] = obtenerMensaje('REGISTRO_NO_EXISTE'); 
								$arreglo['valido']  = $objUsuario->existe;
							}
						}
						else
						{
							$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');
							$arreglo['valido']  = $objUsuario->valido;
						}	
					}
					else
					{
						$arreglo['mensaje'] = obtenerMensaje('DATOS_NO_VALIDO');  
						$arreglo['valido']  = false;
					}				
					$respuesta  = array('raiz'=>$arreglo);
					$respuesta  = json_encode($respuesta);
					echo $respuesta;	
				}	
				else
				{
					$objUsuario->codusu = $objdata->codusu;		
					$correcto = (validaciones($objUsuario->codusu,'20','novacio')) && (validaciones($objdata->nuevopassword,'50','caracteres'));
					if ($correcto)
					{						
						$objUsuario->password = $objdata->nuevopassword;
						$objUsuario->actualizarPassword(); 
						if ($objUsuario->valido)
						{
							$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');	
						}
						else
						{
							$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');								
						}
						$arreglo['valido']  = $objUsuario->valido;
					}
					else
					{
						$arreglo['mensaje'] = obtenerMensaje('DATOS_NO_VALIDO');  
						$arreglo['valido']  = false;
					}
					$respuesta  = array('raiz'=>$arreglo);
					$respuesta  = json_encode($respuesta);
					echo $respuesta;	
				}
			}
			else
			{
				$arreglo['mensaje'] = obtenerMensaje('ACCION_NO_VALIDA'); 
				$arreglo['valido']  = false;
			}	
		break;
	}
	unset($objSistemaVentana);
	unset($objUsuario);
}	
?>	