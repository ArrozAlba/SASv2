<?php
session_start(); 
/***************************************************************** 	
* @Controlador para proceso de asignar perfil a usuario o grupo.
* @versión: 1.0      
* @fecha creación: 19/08/2008
* @autor: Ing. Gusmary Balza
*********************************
* @fecha modificacion  
* @autor  
* @descripcion  
********************************************************************/
$sessionvalida = false;
if(array_key_exists('sigesp_codempresa',$_SESSION))
{
	$sessionvalida = true;
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_sistema.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_grupo.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_usuario.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_menu.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_perfil.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_funciones.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_validaciones.php');
}
else
{
	$arreglo["mensaje"] = utf8_encode("Su Sessión ha Expirado. Ingrese nuevamente al sistema."); 
	$arreglo["valido"]  = false;
	$respuesta  = array('raiz'=>$arreglo);
	$respuesta  = json_encode($respuesta);
	echo $respuesta;
}
if (($_POST['objdata']) && ($sessionvalida))	
{	
	$objdata = str_replace("\\","",$_POST['objdata']);	
	$objdata = json_decode($objdata,false);	
	$objSistema = new Sistema();
	$objUsuario = new Usuario();
	$objGrupo   = new Grupo();
	$objMenu    = new Menu();
	$objPerfil  = new Perfil();
	pasarDatos(&$objPerfil,$objdata,&$evento);
	$objPerfil->codempresa = $_SESSION['sigesp_codempresa'];	
	$objMenu->codempresa = $_SESSION['sigesp_codempresa'];	
	$objMenu->codusuario = $_SESSION['sigesp_codusuario'];	
	$objMenu->codsistema = $objdata->sistema;
	$objMenu->nomfisico = $objdata->vista;
	$evento = $objdata->operacion;
	
	switch ($evento)
	{
		case 'obtenerSistema':
			$objSistema->codempresa = $_SESSION['sigesp_codempresa'];
			$datos = $objSistema->leer();
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
		
		case 'obtenerUsuario':
			$objUsuario->codempresa = $_SESSION['sigesp_codempresa'];
			$datos = $objUsuario->leerActivos();
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
		
		case 'obtenerGrupo':
			$objGrupo->codempresa = $_SESSION['sigesp_codempresa'];
			$datos = $objGrupo->leer();
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
		
		case 'obtenerMenu':
			$objMenu->codsistema = $objdata->codsistema;
			$datos = $objMenu->obtenerMenu();
			if (count($datos)>0)
			{
				$varJson = generarJson($datos);
				echo $varJson;				
			}
			else 
			{	
				echo '';
			}
		
		break;
				
		case 'incluir':
			$objMenu->campo = 'incluir';
			$accionvalida   = $objMenu->verificarUsuario();
			if ($accionvalida)
			{
				$objPerfil->verificarPerfilUno();
				if ($objPerfil->existe==false)
				{	
					$objPerfil->incluir();
					$arreglo["mensaje"] = $objPerfil->mensaje;
					$arreglo["valido"]  = $objPerfil->valido;
				}
				else
				{
					$arreglo["mensaje"] = $objPerfil->mensaje;
					$arreglo["existe"]  = $objPerfil->existe;
				}
			}
			else
			{
				$arreglo["mensaje"] = utf8_encode("El Usuario no Tiene permiso para esta Acción. Comuníquese con el Administrador del sistema."); 
				$arreglo["valido"]  = false;
			}	
			$respuesta  = array('raiz'=>$arreglo);
			$respuesta  = json_encode($respuesta);
			echo $respuesta;
		break;
				
		case 'actualizarUno':
			$objMenu->campo = 'actualizar';
			$accionvalida=$objMenu->verificarUsuario();
			if ($accionvalida)
			{
				$objPerfil->modificar();
				$arreglo["mensaje"] = $objPerfil->mensaje;
				$arreglo["valido"]  = $objPerfil->valido;
			}
			else
			{
				$arreglo["mensaje"] = utf8_encode("El Usuario no Tiene permiso para esta Acción. Comuníquese con el Administrador del sistema."); 
				$arreglo["valido"]  = false;
			}	
			$respuesta  = array('raiz'=>$arreglo);
			$respuesta  = json_encode($respuesta);
			echo $respuesta;
		break;		
		
		case 'actualizar':
			$objMenu->campo = 'actualizar';
			$accionvalida=$objMenu->verificarUsuario();
			if ($accionvalida)
			{
				$objPerfil->modificarTodos();
				$arreglo["mensaje"] = $objPerfil->mensaje;
				$arreglo["valido"]  = $objPerfil->valido;
			}	
			else
			{
				$arreglo["mensaje"] = utf8_encode("El Usuario no Tiene permiso para esta Acción. Comuníquese con el Administrador del sistema."); 
				$arreglo["valido"]  = false;
			}	
			$respuesta  = array('raiz'=>$arreglo);
			$respuesta  = json_encode($respuesta);
			echo $respuesta;
		break;	
		
		case 'insertarTodas': //incluir los permisos a todas las funcionalidades
			$objMenu->campo = 'incluir';
			$accionvalida  = $objMenu->verificarUsuario();
			if ($accionvalida)
			{
				$objPerfil->verificarPerfilTodos();
				if ($objPerfil->existe==false)
				{		
					$objPerfil->insertarPermisosGlobales();
					$arreglo["mensaje"] = $objPerfil->mensaje;
					$arreglo["valido"]  = $objPerfil->valido;
				}
				else
				{
					$objPerfil->modificarTodos();
					$arreglo["mensaje"] = $objPerfil->mensaje;
					$arreglo["valido"]  = $objPerfil->valido;
				}
			}
			else
			{
				$arreglo["mensaje"] = utf8_encode("El Usuario no Tiene permiso para esta Acción. Comuníquese con el Administrador del sistema."); 
				$arreglo["valido"]  = false;
			}	
			$respuesta  = array('raiz'=>$arreglo);
			$respuesta  = json_encode($respuesta);
			echo $respuesta;	
		break;
		
		case 'buscarUno':
			$objMenu->campo = 'leer';
			$accionvalida=$objMenu->verificarUsuario();
			if ($accionvalida)
			{
				$datos = $objPerfil->leerUno();
				if (count($datos)>0)
				{
					$varJson = generarJson($datos);
					echo $varJson;				
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
		break;
		
		case 'buscarTodos':
			$objMenu->campo = 'leer';
			$accionvalida=$objMenu->verificarUsuario();
			if ($accionvalida)
			{
				$datos = $objPerfil->leerTodos();
				if (count($datos)>0)
				{
					$varJson = generarJson($datos);
					echo $varJson;				
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
		break;
				
		case 'eliminarTodos':
			$objMenu->campo = 'eliminar';
			$accionvalida=$objMenu->verificarUsuario();
			if ($accionvalida)
			{
				$objPerfil->eliminarTodos();
				$arreglo["mensaje"] = $objPerfil->mensaje;
				$arreglo["valido"]  = $objPerfil->valido;
			}
			else
			{
				$arreglo["mensaje"] = utf8_encode("El Usuario no Tiene permiso para esta Acción. Comuníquese con el Administrador del sistema."); 
				$arreglo["valido"]  = false;
			}	
			$respuesta  = array('raiz'=>$arreglo);
			$respuesta  = json_encode($respuesta);
			echo $respuesta;	
		break;
		
		case 'eliminarUno':
			$objMenu->campo = 'eliminar';
			$accionvalida=$objMenu->verificarUsuario();
			if ($accionvalida)
			{
				$objPerfil->eliminarUno();
				$arreglo["mensaje"] = $objPerfil->mensaje;
				$arreglo["valido"]  = $objPerfil->valido;
			}
			else
			{
				$arreglo["mensaje"] = utf8_encode("El Usuario no Tiene permiso para esta Acción. Comuníquese con el Administrador del sistema."); 
				$arreglo["valido"]  = false;
			}	
			$respuesta  = array('raiz'=>$arreglo);
			$respuesta  = json_encode($respuesta);
			echo $respuesta;		
		break;
	}
	unset($objMenu);
	unset($objSistema);
	unset($objUsuario);
	unset($objGrupo);
	unset($objPerfil);
}	
?>