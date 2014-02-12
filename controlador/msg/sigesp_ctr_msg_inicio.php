<?php
session_start();
$_SESSION["sigesp_sitioweb"]="sigesp_v2";
/***************************************** 	
* @Controlador para el Inicio de Sesión.
* @versión: 1.0   
* @fecha creación: 07/07/2008
* @autor: Ing. Gusmary Balza
***************************
* @fecha modificacion  
* @autor  
* @descripcion  
*****************************************/

require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/base/librerias/php/general/sigesp_lib_funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/base/librerias/php/general/sigesp_lib_validaciones.php');

if ($_POST['objdata'])	
{	
	$objdata = str_replace("\\","",$_POST['objdata']);
	$objdata = json_decode($objdata,false);	
	
	switch ($objdata->operacion)
	{
		case 'obtenerbd':
			$ruta = $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/base/xml/';
			$archivoconfig = "sigesp_xml_configuracion.xml";
			$documentoxml = abrirArchivoXml($ruta,$archivoconfig);
			if ($documentoxml != null)
			{
				$datos = array();
				obtenerConexionbd($documentoxml,$datos);
				$datos  = array('raiz'=>$datos);
				$textJson = json_encode($datos);
				echo $textJson;
			}
		break;
			
		case 'obtenerempresa':
			$ruta = $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/base/xml/';			
			$archivoconfig = "sigesp_xml_configuracion.xml";
			$documentoxml = abrirArchivoXml($ruta,$archivoconfig);	
			if (!is_null($documentoxml))
			{
				$basededatos = obtenerEmpresa($documentoxml,$objdata->basedatos);
				require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/modelo/mcd/sigesp_dao_mcd_empresa.php');
				$objEmpresa = new Empresa();
				if ($basededatos !='')
				{
					$datos = array();
					$datos = $objEmpresa->filtrarEmpresas();
					if (!empty($datos))
					{
						$arJson = generarJson($datos);
						echo $arJson;
					}
					else
					{					
						require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/modelo/mcd/sigesp_dao_mcd_cargardatos.php');
						$objCarga = new CargarDatos();						
						$objCarga->crearDatos();
						//unset($objCarga);
						$datos = array();
						$datos = $objEmpresa->filtrarEmpresas();
						if (!empty($datos))
						{
							$arJson = generarJson($datos);
							echo $arJson;
						}
						else
						{
							$arreglo["valido"]  = $objEmpresa->valido;
							$arreglo["mensaje"] = $objEmpresa->mensaje;
							$textJso  = array('raiz'=>$arreglo);
							$textJson = json_encode($textJso);
							echo $textJson;
						}
					}
				}
				else
				{
					$arreglo["valido"]  = $objEmpresa->valido;
					$arreglo["mensaje"] = $objEmpresa->mensaje;
					$textJso  = array('raiz'=>$arreglo);
					$textJson = json_encode($textJso);
					echo $textJson;
				}
				unset($objEmpresa);
			}
			else
			{
				$arreglo["valido"]  = true;
				$arreglo["mensaje"] = "Error al abrir el archivo de configuración";
				$textJso  = array('raiz'=>$arreglo);
				$textJson = json_encode($textJso);
				echo $textJson;
			}		
		break;
			
		case 'iniciarsesion':					
			require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/modelo/msg/sigesp_dao_msg_iniciosesion.php');
			$objInicio = new InicioSesion();
			$objInicio->codusuario = $objdata->codusuario;
			$objInicio->password   = $objdata->pasusuario;			
			$objInicio->verificarUsuario();
			$arreglo["valido"]  = $objInicio->valido;
			$arreglo["mensaje"] = $objInicio->mensaje;
			$textJso  = array('raiz'=>$arreglo);
			$textJson = json_encode($textJso);
			echo $textJson;
			unset($objInicio);		
		break;	
	}
	
}	
?>
