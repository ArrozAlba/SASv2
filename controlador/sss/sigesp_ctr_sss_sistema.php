<?php
session_start(); 
/***********************************************************************************
* @Clase para Manejar para la definición de Sistema.
* @fecha de creación: 08/08/2008
* @autor: Ing. Gusmary Balza
* **************************
* @fecha modificacion  13/10/2008
* @autor  Ing. Yesenia Moreno de Lang
* @descripcion  Se agrego la opción de seguridad
***********************************************************************************/
require_once('../../base/librerias/php/general/sigesp_lib_funciones.php');
$sessionvalida = validarSession();
if (($_POST['objdata']) && ($sessionvalida))
{	
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_crearreporte.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_validaciones.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistema.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistemaventana.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_usuariosistema.php');
	
	$_SESSION['session_activa']=time();
	$objdata = str_replace('\\','',$_POST['objdata']);	
	$objdata = json_decode($objdata,false);		
	$objSistema = new Sistema();		
	$objSistema->codemp = $_SESSION['la_empresa']['codemp'];	
	$objSistema->codsis = $objdata->sistema;
	$objSistema->nomfisico = $objdata->vista;	
	pasarDatos(&$objSistema,$objdata,&$evento);
	$objSistema->codemp = $_SESSION['la_empresa']['codemp'];
	$objSistemaVentana = new SistemaVentana();		
	$objSistemaVentana->codemp = $_SESSION['la_empresa']['codemp'];	
	$objSistemaVentana->codusu = $_SESSION['la_logusr']; 
	$objSistemaVentana->codsis = $objdata->sistema;
	$objSistemaVentana->nomfisico  = $objdata->vista;
	$evento = $objdata->oper;
	// Cargamos los usuarios que se agregaron al sistema
	if ($objdata->datosAdmin)
	{
		$total = count($objdata->datosAdmin);
		for ($j=0; $j<$total; $j++)
		{
			$objSistema->admin[$j] = new Usuariosistema();
			pasarDatos(&$objSistema->admin[$j],$objdata->datosAdmin[$j]);	
		}
	}
	// Cargamos los usuarios que se eliminaron al sistema
	if ($objdata->datosEliminar)
	{
		$total = count($objdata->datosEliminar);
		for ($j=0; $j<$total; $j++)
		{
			$objSistema->usuarioeliminar[$j] = new UsuarioSistema();
			pasarDatos(&$objSistema->usuarioeliminar[$j],$objdata->datosEliminar[$j]);	
		}
	}	
	switch ($evento)
	{
		case 'incluir':	
			$objSistemaVentana->campo = 'incluir';
			$accionvalida=$objSistemaVentana->verificarUsuario();
			$correcto=(validaciones($objSistema->codsis,'3','novacio|longexacta') && validaciones($objSistema->nomsis,'60','novacio|nombre'));
			if ($accionvalida)
			{
				if ($correcto)
				{
					$objSistema->verificarCodigo();
					if($objSistema->valido)
					{
						if ($objSistema->existe==false)	
						{				
							$objSistema->incluir();
							if($objSistema->valido)
							{
								$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');	
							}
							else
							{
								$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
							}
							$arreglo['valido']  = $objSistema->valido;
						}
						else
						{					
							$arreglo['mensaje'] = obtenerMensaje('REGISTRO_EXISTE');
							$arreglo['valido']  = $objSistema->existe;
						}	
					}
					else
					{				
						$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');
						$arreglo['valido']  = $objSistema->existe;
					}
				}
				else
				{
					$arreglo['mensaje'] = obtenerMensaje('DATOS_NO_VALIDO');  
					$arreglo['valido']  = false;
				}
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
		
		case 'actualizar':
			$objSistemaVentana->campo = 'cambiar';
			$accionvalida=$objSistemaVentana->verificarUsuario();
			$correcto=validaciones($objSistema->nomsis,'60','novacio|nombre');
			if ($accionvalida)
			{	
				if ($correcto)
				{
					$objSistema->verificarCodigo();
					if($objSistema->valido)
					{
						if ($objSistema->existe==true)	
						{				
							$objSistema->modificar();
							if($objSistema->valido)
							{
								$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');	
							}
							else
							{
								$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
							}
							$arreglo['valido']  = $objSistema->valido;
						}					
										}
					else
					{				
						$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');
						$arreglo['valido']  = $objSistema->existe;
					}
				}
				else
				{
					$arreglo['mensaje'] = obtenerMensaje('REGISTRO_NO_EXISTE');
					$arreglo['valido']  = $objSistema->existe;
				}
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
			
		case 'catalogo':
			$objSistemaVentana->campo = 'leer';
			$accionvalida = $objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				$datos = $objSistema->leer();
				if($objSistema->valido)
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
		
		case 'catalogodetalle':	
			$datos = $objSistema->obtenerUsuarios();
			if($objSistema->valido)
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
			
		case 'eliminar':
			$objSistemaVentana->campo = 'eliminar';
			$accionvalida = $objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				$objSistema->verificarCodigo();
				if($objSistema->valido)
				{
					if ($objSistema->existe===true)
					{
						$objSistema->usuarioeliminar[0] = new UsuarioSistema();
						$objSistema->eliminar();
						if($objSistema->valido)
						{
							$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');	
						}
						else
						{
							$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
						}
						$arreglo['valido']  = $objSistema->valido;					
					}
					else 
					{
						$arreglo['mensaje'] = obtenerMensaje('REGISTRO_NO_EXISTE');
						$arreglo['valido']  = $objSistema->existe;
					}
				}
				else
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');
					$arreglo['valido']  = $objSistema->existe;
				}
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
		
		case 'reporteficha':
			$objSistemaVentana->campo = 'imprimir';
			$accionvalida = $objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				$objReporte = new crearReporte();
				$objSistema->cadena = $objdata->codsis;
				$datosSis = $objSistema->leer();
				$data = $objSistema->obtenerUsuarios();  
				if (count($data)>0)
				{
					$objReporte->crearXml('datos_sistema',$datosSis);
					$objReporte->crearXml('ficha_sistema',$data);
					$objReporte->nomRep='ficha_sistema';
					echo $objReporte->mostrarReporte();	
				}
				else
				{
					echo '';
				}						
				unset($objReporte);
			}
			else
			{
				echo '';
			}						
	}
	unset($objSistemaVentana);
	unset($objSistema);
}	
?>
