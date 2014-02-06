<?php
session_start(); 
/*********************************************** 	
* @Controlador para la definición de Sistema.
* @versión: 1.0      
* @fecha creación: 08/08/2008
* @autor: Ing. Gusmary Balza
* *****************************
* @fecha modificacion: 03/09/2008 
* @autor: Ing. Yesenia Moreno 
* @descripcion: Se agrego la opción de seguridad  
***********************************************/
$sessionvalida = false;
if(array_key_exists('sigesp_codempresa',$_SESSION))
{
	$sessionvalida = true;
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_sistema.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_menu.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_usuariosistema.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_usuario.php');
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
	$objSistema = new Sistema();		
	pasarDatos(&$objSistema,$objdata,&$evento);
	$objSistema->codempresa = $_SESSION['sigesp_codempresa'];	
	$objMenu = new Menu();		
	$objMenu->codempresa = $_SESSION['sigesp_codempresa'];	
	$objMenu->codusuario = $_SESSION['sigesp_codusuario'];	
	$objMenu->codsistema = $objdata->sistema;
	$objMenu->nomfisico  = $objdata->vista;
	$evento = $objdata->oper;
	if ($objdata->datosAdmin)
	{
		for ($j=0; $j<count($objdata->datosAdmin); $j++)
		{
			$objSistema->admin[$j] = new Usuariosistema();
			pasarDatos(&$objSistema->admin[$j],$objdata->datosAdmin[$j]);	
		}
	}
	
	switch ($evento)
	{
		case 'incluirSistema':	
			$objMenu->campo = 'incluir';
			$accionvalida=$objMenu->verificarUsuario();
			$correcto=(validaciones($objSistema->codsistema,'3','novacio|longexacta') && validaciones($objSistema->nombre,'60','novacio|nombre'));
			if ($accionvalida)
			{
				if ($correcto)
				{
					$objSistema->buscarCodigo();
					if ($objSistema->existe==false)	
					{				
						$objSistema->incluirTodos();
						$arreglo["mensaje"] = $objSistema->mensaje;
						$arreglo["valido"]  = $objSistema->valido;
					}
					else
					{					
						$arreglo["mensaje"] = $objSistema->mensaje;
						$arreglo["existe"]  = $objSistema->existe;
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
			$accionvalida=$objMenu->verificarUsuario();
			$correcto=validaciones($objSistema->nombre,'60','novacio|nombre');
			if ($accionvalida)
			{	
				if ($correcto)
				{
					$objSistema->buscarCodigo();
					if ($objSistema->existe==true)	
					{				
						$objSistema->modificarTodos();
						$arreglo["mensaje"] = $objSistema->mensaje;
						$arreglo["valido"]  = $objSistema->valido;
					}
					else
					{
						$arreglo["mensaje"] = $objSistema->mensaje;
						$arreglo["valido"]  = $objSistema->existe;
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
			}
			else
			{
				echo '';
			}
		break;
		
		case 'catalogodetalle':
			$objSistema->codempresa = $_SESSION['sigesp_codempresa'];	
			$objSonUsu = generarJson($objSistema->obtenerUsuarios());
			echo $objSonUsu;
		break;			
			
		case 'eliminar':
			$objMenu->campo = 'eliminar';
			$accionvalida=$objMenu->verificarUsuario();
			if ($accionvalida)
			{
				$objSistema->buscarPerfilSistema();
				if ($objSistema->existe==false)
				{
					$objSistema->eliminar();
					$arreglo["mensaje"] = $objSistema->mensaje;
					$arreglo["valido"]  = $objSistema->valido;
				}
				else
				{
					$arreglo["mensaje"] = $objSistema->mensaje;
					$arreglo["existe"]  = $objSistema->existe;
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
		
		case 'eliminardetalle':
			$objSistema->codempresa = $_SESSION['sigesp_codempresa'];
			$objSistema->codusuario = $objdata->codusuario;
			$objSonUsu = generarJson($objSistema->eliminarUsuarios());
			echo $objSonUsu;
		break;			
					
		case 'buscarcadena':	
			$datos = $objSistema->leer();
			$objSon = generarJson($datos);
			echo $objSon;
		break;
		
		case 'reporteficha':
			$objMenu->campo = 'imprimir';
			$accionvalida=$objMenu->verificarUsuario();
			if ($accionvalida)
			{
				$objReporte = new crearReporte();
				$data = $objSistema->obtenerUsuarios();  
				if (count($data)>0)
				{
					$objReporte->crearXml('ficha_sistema',$data);
					$objReporte->nomRep="ficha_sistema";
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
	unset($objSistema);
}	
?>
