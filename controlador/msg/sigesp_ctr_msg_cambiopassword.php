<?php
session_start(); 
/************************************************** 	
* @Controlador para proceso de cambio de password.
* @versión: 1.0      
* @fecha creación: 18/08/2008
* @autor: Ing. Gusmary Balza
* *****************************
* @fecha modificacion  
* @autor  
* @descripcion  
**************************************************/
$sessionvalida = false;
if(array_key_exists('sigesp_codempresa',$_SESSION))
{
	$sessionvalida = true;
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_usuario.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_menu.php');
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
if (($_POST['objdata'])  && ($sessionvalida))	
{	
	$objdata = str_replace("\\","",$_POST['objdata']);	
	$objdata = json_decode($objdata,false);	
	$objUsuario = new Usuario();
	pasarDatos(&$objUsuario,$objdata,&$evento);
	$objUsuario->codempresa = $_SESSION['sigesp_codempresa'];	
	$objMenu = new Menu();		
	$objMenu->codempresa = $_SESSION['sigesp_codempresa'];	
	$objMenu->codusuario = $_SESSION['sigesp_codusuario'];	
	$objMenu->codsistema = $objdata->sistema;
	$objMenu->nomfisico = $objdata->vista;
	$evento = $objdata->oper;
	
	switch ($evento)
	{
		case 'revisarUsuario':  //chequear si el usuario es administrador
			$objUsuario->codusuario = $_SESSION['sigesp_codusuario'];
			$objUsuario->verificarAdministrador(); 
			$arreglo["mensaje"] = $objUsuario->mensaje;
			$arreglo["existe"]  = $objUsuario->existe;
			$jsonActualizar  = array('raiz'=>$arreglo);
			$jsonActualizar = json_encode($jsonActualizar);
			echo $jsonActualizar;
		break;		
		
		
		case 'actualizar':	//caso no administrador
			$objUsuario->codusuario = $_SESSION['sigesp_codusuario'];
			if ($objUsuario->administrador==false)
			{
				$objMenu->campo = 'actualizar';
				$accionvalida = $objMenu->verificarUsuario();
				$correcto = (validaciones($objUsuario->password,'50','caracteres')) && (validaciones($objUsuario->nuevopassword,'50','caracteres'));
				if ($accionvalida)
				{
					if ($correcto)
					{
						$objUsuario->verificarPassword(); //caso no administrador
						if ($objUsuario->existe)
						{
							$objUsuario->password = $objdata->nuevopassword;
							$objUsuario->actualizarPassword(); 
							$arreglo["mensaje"] = $objUsuario->mensaje;
							$arreglo["valido"]  = $objUsuario->valido;
						}
						else
						{
							$arreglo["mensaje"] = $objUsuario->mensaje;
							$arreglo["existe"]  = $objUsuario->existe;
						}
					}
					else
					{
						$arreglo["mensaje"] = utf8_encode("Datos Inválidos"); 
						$arreglo["valido"]  = false;
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
			}	
			else
			{
				$objMenu->campo = 'actualizar';
				$accionvalida = $objMenu->verificarUsuario();
				$correcto = (validaciones($objUsuario->codusuario,'20','novacio')) && (validaciones($objUsuario->nuevopassword,'50','caracteres'));
				if ($accionvalida)
				{
					if ($correcto)
					{
						$objUsuario->codusuario = $objdata->codusuario;
						$objUsuario->password = $objdata->nuevopassword;
						$objUsuario->actualizarPassword(); //caso administrador
						$arreglo["mensaje"] = $objUsuario->mensaje;
						$arreglo["valido"]  = $objUsuario->valido;
					}
					else
					{
						$arreglo["mensaje"] = utf8_encode("Datos Inválidos"); 
						$arreglo["valido"]  = false;
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
			}
		break;
	}
	unset($objMenu);
	unset($objUsuario);
}	
?>	