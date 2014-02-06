<?php
session_start();
/***********************************************************************************
* @Clase para el inicio del módulo de apertura
* @fecha creación: 20/10/2008
* @autor: Ing. Yesenia Moreno de Lang
* * **************************
* @fecha modificacion 
* @autor  
* @descripcion 
***********************************************************************************/

require_once('../../base/librerias/php/general/sigesp_lib_funciones.php');
$sessionvalida = true;//validarSession();
if (($_POST['objdata']) && ($sessionvalida))
{	
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_conexion.php');
	
	$_SESSION['session_activa']=time();
	$objdata = str_replace('\\','',$_POST['objdata']);
	$objdata = json_decode($objdata,false);	
	$ruta = '../../base/xml/';
	$archivoconfig = 'sigesp_xml_configuracion_apr.xml';
	switch ($objdata->operacion)
	{
		case 'obtenerbd':
			$documentoxml = abrirArchivoXml($ruta,$archivoconfig);
			if ($documentoxml != null)
			{
				$datos = array();
				obtenerConexionbd($documentoxml,$datos);
				$datos  = array('raiz'=>$datos);
				$respuesta = json_encode($datos);
				echo $respuesta;
			}
		break;

		case 'verificarsession':	
			$valido=true;
			$mensaje=obtenerMensaje('OPERACION_EXITOSA');	
			$documentoxml = abrirArchivoXml($ruta,$archivoconfig);	
			if (!is_null($documentoxml))
			{
				$basededatos = obtenerBdApertura($documentoxml,$objdata->basedatos);
				$conexion = conectarBD($_SESSION['sigesp_servidor_apr'], $_SESSION['sigesp_usuario_apr'],
									   $_SESSION['sigesp_clave_apr'], $_SESSION['sigesp_basedatos_apr'], 
									   $_SESSION['sigesp_gestor_apr']);
									   
				$_SESSION["ls_data_des"] = $_SESSION['sigesp_basedatos_apr'];				   
				if($conexion===false)
				{
					$valido=false;
					$mensaje=obtenerMensaje('OPERACION_FALLIDA');
				}
			}	
			$datos['valido'] = $valido;
			$datos['mensaje'] = $mensaje;
			$datos  = array('raiz'=>$datos);
			$respuesta= json_encode($datos);
			echo $respuesta;
		break;	
	}
}	
?>
