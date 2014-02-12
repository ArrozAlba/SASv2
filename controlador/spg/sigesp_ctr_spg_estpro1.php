<?php
session_start();
/************************************************************************** 	
* @Controlador para las funciones de estructuras presupuestarias de nivel 1.
* @versión: 1.0  
* @fecha creación: 26/11/2008
* @autor: Ing. Gusmary Balza
* *************************************************************************
* @fecha modificacion 
* @autor  
* @descripcion  
***************************************************************************/
require_once('../../base/librerias/php/general/sigesp_lib_funciones.php');
$sessionvalida = true;//validarSession();
if (($_POST['objdata']) && ($sessionvalida))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/spg/sigesp_dao_spg_estpro1.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistemaventana.php');
	
	$_SESSION['session_activa']=time();
	$objdata = str_replace("\\","",$_POST['objdata']);	
	$objdata = json_decode($objdata,false);		
	$objEstPre = new EstPro1();		
	pasarDatos(&$objEstPre,$objdata,&$evento);
	$objEstPre->codemp = $_SESSION['la_empresa']['codemp'];	
	$objEstPre->codsis = $objdata->sistema;
	$objEstPre->nomfisico = $objdata->vista;	
	$objSistemaVentana = new SistemaVentana();		
	$objSistemaVentana->codemp = $_SESSION['la_empresa']['codemp'];	
	$objSistemaVentana->codusu = $_SESSION['la_logusr'];	
	$objSistemaVentana->codsis = $objdata->sistema;
	$objSistemaVentana->nomfisico = $objdata->vista;
	$evento = $objdata->oper;
	
	switch ($evento)
	{
		case 'cargarTituloGridCat':		
			$nomestpro1 = $_SESSION['la_empresa']['nomestpro1'];
						
			$arreglo = array ('nivel1'=>$nomestpro1);			
			$respuesta  = array('raiz'=>$arreglo);
			$respuesta  = json_encode($respuesta);
			echo $respuesta;
		break;
		
		case 'catalogo':			
			/*$objSistemaVentana->campo = 'leer'; //espera por presupuesto de gasto
			$accionvalida=$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{*/				
				$objEstPre->codusu = $_SESSION['la_logusr'];

				$objSolicitud->servidor  = $_SESSION['sigesp_servidor'];
				$objSolicitud->usuario   = $_SESSION['sigesp_usuario'];
				$objSolicitud->clave     = $_SESSION['sigesp_clave'];
				$objSolicitud->basedatos = $_SESSION['sigesp_basedatos'];
				$objSolicitud->gestor    = $_SESSION['sigesp_gestor'];
				$objSolicitud->tipoconexionbd='ALTERNA';
				$datos = $objEstPre->leer();
				if ($objEstPre->valido)
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
		/*	}
			else
			{
				$arreglo[0]['mensaje'] = obtenerMensaje('ACCION_NO_VALIDA'); 
				$arreglo[0]['valido']  = false;
				$respuesta  = array('raiz'=>$arreglo);			
				$respuesta  = json_encode($respuesta);
				echo $respuesta;
			}*/
		break;	
	}
	unset($objSistemaVentana);
	unset($objEstPre);
}		
?>