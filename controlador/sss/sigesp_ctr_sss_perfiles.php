<?php
session_start(); 
/************************************************************************************* 	
* @Controlador para proceso de asignar perfil a usuario o grupo.
* @versión: 1.0      
* @fecha creación: 19/08/2008
* @autor: Ing. Gusmary Balza
**********************************************
* @fecha modificacion: 20/10/2008
* @autor: Gusmary Balza
* @descripcion: Adaptar a estandares
*************************************************************************************/
require_once('../../base/librerias/php/general/sigesp_lib_funciones.php');
$sessionvalida = validarSession();
if (($_POST['objdata']) && ($sessionvalida))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistema.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_grupo.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_usuario.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistemaventana.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_derechosusuario.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_derechosgrupo.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_permisosinternos.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_permisosinternos_grupo.php');
	
	$_SESSION['session_activa'] = time();
	$objdata = str_replace('\\','',$_POST['objdata']);	
	$objdata = json_decode($objdata,false);	
	$objSistema = new Sistema();
	$objSistema->codemp = $_SESSION['la_empresa']['codemp'];	
	$objUsuario = new Usuario();
	$objUsuario->codemp = $_SESSION['la_empresa']['codemp'];
	$objGrupo   = new Grupo();
	$objGrupo->codemp = $_SESSION['la_empresa']['codemp'];
		
	if ($objdata->seleccionado=='usuario')
	{
		$objPerfil   = new DerechosUsuario();
		$objPermisos = new PermisosInternos();	
		$objPermisos->codusu = $objdata->codusu;
	}
	else
	{
		$objPerfil   = new DerechosGrupo();
		$objPermisos = new PermisosInternosGrupo();
		$objPermisos->nomgru = $objdata->nomgru;
		
	}	
	$objPermisos->codemp = $_SESSION['la_empresa']['codemp'];
	$objPermisos->codsis = $objdata->codsis;
	$objPermisos->codintper = $objdata->codintper;
	$objPermisos->nomfisico = $objdata->vista;
	$objPerfil->codemp = $_SESSION['la_empresa']['codemp'];	
	$objPerfil->codsis = $objdata->sistema;
	$objPerfil->nomfisico = $objdata->vista;	
	pasarDatos(&$objPerfil,$objdata,&$evento);
	$objSistemaVentana = new SistemaVentana();		
	$objSistemaVentana->codemp = $_SESSION['la_empresa']['codemp'];	
	$objSistemaVentana->codusu = $_SESSION['la_logusr'];	
	$objSistemaVentana->codsis = $objdata->sistema;
	$objSistemaVentana->nomfisico = $objdata->vista;
	$evento = $objdata->operacion;
	
	switch ($evento)
	{
		case 'obtenerSistema':			
			$datos = $objSistema->leer();
			if ($objSistema->valido)
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
		
		case 'obtenerUsuario':			
			$datos = $objUsuario->leerActivos();
			if ($objUsuario->valido)
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
		
		case 'obtenerGrupo':			
			$datos = $objGrupo->leer();
			if ($objGrupo->valido)
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
		
		case 'obtenerMenu':
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

		case 'obtenerPermisos':
			$objSistemaVentana->codsis = $objdata->codsis;
			$objSistemaVentana->codmenu = $objdata->codmenu;
			$datos = $objSistemaVentana->obtenerMenu();
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
		
		case 'incluir':
			$objSistemaVentana->campo = 'cambiar';
			$accionvalida = $objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				$objPermisos->enabled =1;				
				$objPermisos->incluirPermisosInternos();
				if ($objPermisos->valido)
				{	
					//$objPerfil->descargar = 0;					
					$objPerfil->incluir();
					if ($objPerfil->valido)
					{
						$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');	
					}
					else
					{
						$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
					}
					$arreglo['valido']  = $objPerfil->valido;
				}
				else
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');
					$arreglo['existe']  = $objPerfil->existe;
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
				
		case 'actualizarUno':
			$objSistemaVentana->campo = 'cambiar';
			$accionvalida = $objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				$i=0;
				$objPerfil->criterio[$i]['operador'] = "WHERE";
				$objPerfil->criterio[$i]['criterio'] = "codemp";
				$objPerfil->criterio[$i]['condicion'] = "=";
				$objPerfil->criterio[$i]['valor'] = "'".$_SESSION['la_empresa']['codemp']."'";
				$i++;
				
				$objPerfil->criterio[$i]['operador'] = "AND";
				$objPerfil->criterio[$i]['criterio'] = "codsis";
				$objPerfil->criterio[$i]['condicion'] = "=";
				$objPerfil->criterio[$i]['valor'] = "'".$objdata->codsis."'";
				$i++;
				if ($objdata->codusu!='')
				{
					$objPerfil->criterio[$i]['operador'] = "AND";
					$objPerfil->criterio[$i]['criterio'] = "codusu";
					$objPerfil->criterio[$i]['condicion'] = "=";
					$objPerfil->criterio[$i]['valor'] = "'".$objdata->codusu."'";
					$i++;
				}	
				else
				{
					$objPerfil->criterio[$i]['operador'] = "AND";
					$objPerfil->criterio[$i]['criterio'] = "nomgru";
					$objPerfil->criterio[$i]['condicion'] = "=";
					$objPerfil->criterio[$i]['valor'] = "'".$objdata->nomgru."'";
					$i++;
				}				
				if ($objdata->codintper!='')
				{
					$objPerfil->criterio[$i]['operador'] = "AND";
					$objPerfil->criterio[$i]['criterio'] = "codintper";
					$objPerfil->criterio[$i]['condicion'] = "=";
					$objPerfil->criterio[$i]['valor'] = "'".$objdata->codintper."'";
					$i++;
				}	

				$objPerfil->criterio[$i]['operador'] = "AND";
				$objPerfil->criterio[$i]['criterio'] = "codmenu";
				$objPerfil->criterio[$i]['condicion'] = "=";
				$objPerfil->criterio[$i]['valor'] = "'".$objdata->codmenu."'";
				
				$objPerfil->modificar();
				if ($objPerfil->valido)
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');	
				}
				else
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
				}
				$arreglo['valido'] = $objPerfil->valido;
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
		
		case 'insertarTodas': 
			$objSistemaVentana->campo = 'cambiar';
			$accionvalida  = $objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				$objPermisos->enabled =1;
				$objPermisos->incluirPermisosInternos();
				if ($objPermisos->valido)
				{						
					$objPerfil->insertarPermisosGlobales();
					if ($objPerfil->valido)
					{
						$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');	
					}
					else
					{
						$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
					}
					$arreglo['valido'] = $objPerfil->valido;
				}
				else
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');
					$arreglo['existe']  = $objPerfil->existe;
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
		
		case 'buscarUno':
		//	$objSistemaVentana->campo = 'leer';
		//	$accionvalida=$objSistemaVentana->verificarUsuario();
		//	if ($accionvalida)
		//	{
				$datos = $objPerfil->leerUno();
				if ($objPerfil->valido)
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
	/*		}
			else 
			{
				$arreglo[0]['mensaje'] = obtenerMensaje('ACCION_NO_VALIDA'); 
				$arreglo[0]['valido']  = false;
				$respuesta  = array('raiz'=>$arreglo);			
				$respuesta  = json_encode($respuesta);
				echo $respuesta;					
			}	*/		
		break;		
		
		case 'eliminarTodos':
			$objSistemaVentana->campo = 'eliminar';
			$accionvalida=$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				$objPerfil->criterio[0]['operador'] = "WHERE";
				$objPerfil->criterio[0]['criterio'] = "codemp";
				$objPerfil->criterio[0]['condicion'] = "=";
				$objPerfil->criterio[0]['valor'] = "'".$_SESSION['la_empresa']['codemp']."'";
				
				$objPerfil->criterio[1]['operador'] = "AND";
				$objPerfil->criterio[1]['criterio'] = "codsis";
				$objPerfil->criterio[1]['condicion'] = "=";
				$objPerfil->criterio[1]['valor'] = "'".$objdata->codsis."'";
				
				if ($objdata->codusu!='')
				{
					$objPerfil->criterio[2]['operador'] = "AND";
					$objPerfil->criterio[2]['criterio'] = "codusu";
					$objPerfil->criterio[2]['condicion'] = "=";
					$objPerfil->criterio[2]['valor'] = "'".$objdata->codusu."'";
				}	
				else
				{
					$objPerfil->criterio[2]['operador'] = "AND";
					$objPerfil->criterio[2]['criterio'] = "nomgru";
					$objPerfil->criterio[2]['condicion'] = "=";
					$objPerfil->criterio[2]['valor'] = "'".$objdata->nomgru."'";
				}				
				$objPerfil->criterio[3]['operador'] = "AND";
				$objPerfil->criterio[3]['criterio'] = "codintper";
				$objPerfil->criterio[3]['condicion'] = "=";
				$objPerfil->criterio[3]['valor'] = "'".$objdata->codintper."'";
								
				$objPerfil->eliminarTodosPrueba();
				//$objPerfil->eliminarTodos();
				if ($objPerfil->valido)
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');	
				}
				else
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
				}
				$arreglo['valido']  = $objPerfil->valido;		
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
		
		case 'eliminarUno':
			$objSistemaVentana->campo = 'eliminar';
			$accionvalida=$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				/*$objPerfil->criterio[0]['operador'] = "WHERE";
				$objPerfil->criterio[0]['criterio'] = "codemp";
				$objPerfil->criterio[0]['condicion'] = "=";
				$objPerfil->criterio[0]['valor'] = "'".$_SESSION['la_empresa']['codemp']."'";
			*/
				$contador = 0;	
				$objPerfil->criterio[$contador]['operador'] = "AND";
				$objPerfil->criterio[$contador]['criterio'] = "codsis";
				$objPerfil->criterio[$contador]['condicion'] = "=";
				$objPerfil->criterio[$contador]['valor'] = "'".$objdata->codsis."'";
				$contador++;
				
				if ($objdata->codusu!='')
				{
					$objPerfil->criterio[$contador]['operador'] = "AND";
					$objPerfil->criterio[$contador]['criterio'] = "codusu";
					$objPerfil->criterio[$contador]['condicion'] = "=";
					$objPerfil->criterio[$contador]['valor'] = "'".$objdata->codusu."'";
				}	
				else
				{
					$objPerfil->criterio[$contador]['operador'] = "AND";
					$objPerfil->criterio[$contador]['criterio'] = "nomgru";
					$objPerfil->criterio[$contador]['condicion'] = "=";
					$objPerfil->criterio[$contador]['valor'] = "'".$objdata->nomgru."'";
				}				
				$contador++;
				
				$objPerfil->criterio[$contador]['operador'] = "AND";
				$objPerfil->criterio[$contador]['criterio'] = "codmenu";
				$objPerfil->criterio[$contador]['condicion'] = "=";
				$objPerfil->criterio[$contador]['valor'] = "'".$objdata->codmenu."'";
				$contador++;
				
				$objPerfil->criterio[$contador]['operador'] = "AND";
				$objPerfil->criterio[$contador]['criterio'] = "codintper";
				$objPerfil->criterio[$contador]['condicion'] = "=";
				$objPerfil->criterio[$contador]['valor'] = "'".$objdata->codintper."'";
				$contador++;
				
				//$objPerfil->eliminarUno();
				$objPerfil->eliminarTodosPrueba();
				if ($objPerfil->valido)
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');	
				}
				else
				{					
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');		
				}
				$arreglo['valido']  = $objPerfil->valido;
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
	}
	unset($objSistemaVentana);
	unset($objSistema);
	unset($objUsuario);
	unset($objGrupo);
	unset($objPerfil);
}	
?>