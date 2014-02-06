<?php
session_start(); 
/************************************************ 	
* @Controlador para la definición de Usuario.
* @versión: 1.0 
* @fecha creación: 21/07/2008.
* @autor: Ing. Gusmary Balza.
***************************
* @fecha modificacion. 03/09/2008
* @autor: Ing. Gusmary Balza
* @descripcion: Se agrego la opción de seguridad 
************************************************/
$sessionvalida = false;
if(array_key_exists('sigesp_codempresa',$_SESSION))
{
	$sessionvalida = true;
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_usuario.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_menu.php');

	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_funciones.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_crearreporte.php');
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
	$objUsuario = new Usuario();
	pasarDatos(&$objUsuario,$objdata,&$evento);
	$objUsuario->codempresa = $_SESSION['sigesp_codempresa'];	
	$objMenu = new Menu();		
	$objMenu->codempresa = $_SESSION['sigesp_codempresa'];	
	$objMenu->codusuario = $_SESSION['sigesp_codusuario'];	
	$objMenu->codsistema = $objdata->sistema;
	$objMenu->nomfisico  = $objdata->vista;
	$evento = $objdata->oper;
	switch ($evento)
	{
		case 'incluir':	 
			$objMenu->campo = 'incluir';
			$accionvalida   = $objMenu->verificarUsuario();
			$correcto       = (validaciones($objUsuario->codusuario,'20','novacio|caracteres')) && (validaciones($objUsuario->cedula,'10','novacio|numero')) && (validaciones($objUsuario->nombre,'50','nombre')) && (validaciones($objUsuario->apellido,'50','nombre')) && (validaciones($objUsuario->telefono,'50','vaciotelefono')) && (validaciones($objUsuario->email,'100','vacioemail')) && (validaciones($objUsuario->nota,'2000','vaciocaracteres'));
			if ($accionvalida)
			{
				if ($correcto)
				{
					$objUsuario->buscarCodigo();
					if ($objUsuario->existe==false)	
					{								
						$objUsuario->insertarUsuario();
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
		break;
			
		case 'actualizar':
			$objMenu->campo = 'actualizar';
			$accionvalida = $objMenu->verificarUsuario();
			$correcto = (validaciones($objUsuario->cedula,'10','numero')) && (validaciones($objUsuario->nombre,'50','nombre')) && (validaciones($objUsuario->apellido,'50','nombre')) && (validaciones($objUsuario->telefono,'50','vaciotelefono')) && (validaciones($objUsuario->email,'100','vacioemail')) && (validaciones($objUsuario->nota,'2000','vaciocaracteres'));
			if ($accionvalida)
			{	
				if ($correcto)
				{
					$objUsuario->buscarCodigo();
					if ($objUsuario->existe==true)	
					{				
						$objUsuario->modificar();
						$arreglo["mensaje"] = $objUsuario->mensaje;
						$arreglo["valido"]  = $objUsuario->valido;
					}
					else
					{
						$arreglo["mensaje"] = $objUsuario->mensaje;
						$arreglo["valido"]  = $objUsuario->existe;
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
		break;
				
		case 'catalogo':
			$objMenu->campo = 'leer';
			$accionvalida=$objMenu->verificarUsuario();
			if ($accionvalida)
			{
				$datos = $objUsuario->leer();
				if (count($datos)>0)
				{
					$varJson=generarJson($datos);
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
		
		case 'catalogoActivos':
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
			
		case 'eliminar':
			$objMenu->campo = 'eliminar';
			$accionvalida=$objMenu->verificarUsuario();
			if ($accionvalida)
			{
				$objUsuario->buscarUsuarioSistema();
				if ($objUsuario->existe==false)
				{
					$objUsuario->buscarUsuarioGrupo();
					if ($objUsuario->existe==false)
					{				
						$objUsuario->buscarPerfilUsuario();
						if ($objUsuario->existe==false)
						{
							$objUsuario->eliminar();
							$arreglo["mensaje"] = $objUsuario->mensaje;
							$arreglo["valido"]  = $objUsuario->valido;							
						}
						else
						{
							$arreglo["mensaje"] = $objUsuario->mensaje;
							$arreglo["valido"]  = $objUsuario->existe;
						}	
					}
					else
					{
						$arreglo["mensaje"] = $objUsuario->mensaje;
						$arreglo["valido"]  = $objUsuario->existe;
					}	
				}	
				else
				{
					$arreglo["mensaje"] = $objUsuario->mensaje;
					$arreglo["valido"]  = $objUsuario->existe;
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
				
		case 'buscarcadena':			
			$datos  = $objUsuario->leer();
			$objSon = generarJson($datos);
			echo $objSon;
		break;
		
		case 'reporteficha':
			$objMenu->campo = 'imprimir';
			$accionvalida=$objMenu->verificarUsuario();
			if ($accionvalida)
			{
				$objReporte = new crearReporte();
				$objUsuario->codempresa = $_SESSION['sigesp_codempresa'];	
				$objUsuario->cadena = $objdata['codusuario']; 
				$data = $objUsuario->leer();
				if (count($data)>0)
				{
					$objReporte->crearXml('ficha_usuario',$data);
					$objReporte->nomRep="ficha_usuario";
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
	unset($objMenu);
	unset($objUsuario);
}

?>

