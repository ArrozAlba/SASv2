<?php
session_start();
/***********************************************************************************
* @Clase para Manejar  el proceso de asignar usuarios a un tipo de personal.
* @fecha de creación: 24/10/2008
* @autor: Ing. Gusmary Balza
**********************************************************************
* @fecha modificacion: 
* @autor:  
* @descripcion:  
***********************************************************************************/
require_once('../../base/librerias/php/general/sigesp_lib_funciones.php');
$sessionvalida = validarSession();
if (($_POST['objdata']) && ($sessionvalida))
{	
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_crearreporte.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_validaciones.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sno/sigesp_dao_sno_tipopersonalsss.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sno/sigesp_dao_sno_constante.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sno/sigesp_dao_sno_nomina.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/spg/sigesp_dao_spg_unidadadmin.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/spg/sigesp_dao_spg_estpro5.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_permisosinternos.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_derechosusuario.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistemaventana.php');
	
	$_SESSION['session_activa']=time();
	$objdata = str_replace('\\','',$_POST['objdata']);	
	$objdata = json_decode($objdata,false);		
	if ($objdata->seleccionado=='personal')
	{
		$objPersonal = new TipoPersonalSSS();
	}
	elseif ($objdata->seleccionado=='constante')
	{
		$objPersonal = new Constante();
	}	
	elseif ($objdata->seleccionado=='unidad')
	{
		$objPersonal = new UnidadAdministrativa();
	}	
	elseif ($objdata->seleccionado=='nomina')
	{
		$objPersonal = new Nomina();
	}
	elseif ($objdata->seleccionado=='presupuesto')
	{
		$objPersonal = new EstPro5();
	}	
	$objPersonal->codemp = $_SESSION['la_empresa']['codemp'];		
	//$objPersonal->codsis = $objdata->sistema;
	$objPersonal->nomfisico = $objdata->vista;	
	
	//pasarDatos(&$objPersonal,$objdata,&$evento);
	
	$objPermisos = new PermisosInternos();
	$objPermisos->codemp = $_SESSION['la_empresa']['codemp'];
	$objPermisos->codusu = $objdata->datosAdmin[0]->codusu;
	$objPermisos->codsis = $objdata->codsis;
	$objPermisos->codintper = $objdata->codtippersss;
	$objPermisos->enabled = 1;
	$objPermisos->nomfisico = $objdata->vista;
		
	$objSistemaVentana = new SistemaVentana();		
	$objSistemaVentana->codemp = $_SESSION['la_empresa']['codemp'];	
	$objSistemaVentana->codusu = $_SESSION['la_logusr'];	
	$objSistemaVentana->codsis = $objdata->sistema;
	$objSistemaVentana->nomfisico = $objdata->vista;
	$evento = $objdata->oper;
	// Cargamos los usuarios que se agregaron al personal
	if ($objdata->datosAdmin)
	{
		$total = count($objdata->datosAdmin);
		for ($j=0; $j<$total; $j++)
		{
			$objPermisos->admin[$j] = new PermisosInternos();
			pasarDatos(&$objPermisos->admin[$j],$objdata->datosAdmin[$j]);
			$objPermisos->admin[$j]->codemp = $_SESSION['la_empresa']['codemp'];
			$objPermisos->admin[$j]->codusu = $objdata->datosAdmin[$j]->codusu;
			$objPermisos->admin[$j]->codsis = $objdata->codsis;
			$objPermisos->admin[$j]->codintper = $objdata->codtippersss;
			$objPermisos->admin[$j]->nomfisico = $objdata->vista;			
		}
	}
	// Cargamos los usuarios que se eliminaron al personal
	if ($objdata->datosEliminar)
	{
		$total = count($objdata->datosEliminar);
		for ($j=0; $j<$total; $j++)
		{
			$objPermisos->usuarioeliminar[$j] = new PermisosInternos();
			pasarDatos(&$objPermisos->usuarioeliminar[$j],$objdata->datosEliminar[$j]);
			$objPermisos->usuarioeliminar[$j]->codemp = $_SESSION['la_empresa']['codemp'];
			$objPermisos->usuarioeliminar[$j]->codusu = $objdata->datosEliminar[$j]->codusu;
			$objPermisos->usuarioeliminar[$j]->codsis = $objdata->codsis;
			$objPermisos->usuarioeliminar[$j]->codintper = $objdata->codtippersss;
			$objPermisos->usuarioeliminar[$j]->nomfisico = $objdata->vista;	
		}
	}	
	switch ($evento)
	{
		case 'actualizar':	 
			$objSistemaVentana->campo = 'cambiar';
			$accionvalida=$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{				
				$objPermisos->actualizar();
				if($objPermisos->valido)
				{
					$objPersonal = new DerechosUsuario();
					//var_dump($objPersonal);
					//die();				
					$objPersonal->codemp = $_SESSION['la_empresa']['codemp'];
					$objPersonal->codusu = $objdata->datosAdmin[0]->codusu;
					$objPersonal->codsis = $objdata->codsis;
					$objPersonal->codintper = $objdata->codtippersss;
					$objPersonal->nomfisico = $objdata->vista;	
					$objPersonal->cargarDerechos();
					if ($objPersonal->valido)
					{
						$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');
						
					}
					else
					{
						$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
					}
					$arreglo['valido']  = $objPermisos->valido;	
				}
				else
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');  
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
								
		case 'eliminar':
			$objSistemaVentana->campo = 'eliminar';
			$accionvalida=$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{				
				$objPersonalElim = new PermisosInternos();
				$objPersonalElim->nomfisico = $objdata->vista;
				$objPersonalElim->codemp = $_SESSION['la_empresa']['codemp'];
						
				$objPersonalElim->criterio[0]['operador'] = "AND";
				$objPersonalElim->criterio[0]['criterio'] = "codsis";
				$objPersonalElim->criterio[0]['condicion'] = "=";
				$objPersonalElim->criterio[0]['valor'] = "'".$objdata->codsis."'";
				
				$objPersonalElim->criterio[1]['operador'] = "AND";
				$objPersonalElim->criterio[1]['criterio'] = "codintper";
				$objPersonalElim->criterio[1]['condicion'] = "=";
				$objPersonalElim->criterio[1]['valor'] = "'".$objdata->codtippersss."'";
				
				$objPersonalElim->eliminarTodosPrueba();
				if($objPersonalElim->valido)
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');	
				}
				else
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
				}
				$arreglo['valido']  = $objPersonalElim->valido;			
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
			$accionvalida=$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{				
				$datos = $objPersonal->leer();
				if($objPersonal->valido)
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
			$objPersonal->admin[0] = new PermisosInternos();
			$objPersonal->admin[0]->codemp = $_SESSION['la_empresa']['codemp'];
			$objPersonal->admin[0]->codusu = $objdata->datosAdmin[0]->codusu;			
			$objPersonal->admin[0]->codsis = $objdata->codsis;
			$objPersonal->admin[0]->codintper = $objdata->codtippersss;
			$objPersonal->admin[0]->campo = $objdata->campo;
			$objPersonal->admin[0]->tabla = $objdata->tabla;
			$datos = $objPersonal->admin[0]->obtenerUsuarios();	
			if($objPersonal->admin[0]->valido)
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
	}
	unset($objSistemaVentana);
	unset($objPersonal);
}	
?>
