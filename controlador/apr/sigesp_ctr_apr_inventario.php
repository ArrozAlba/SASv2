<?php
session_start();
/***********************************************************************************
* @Clase para manejar el proceso de movimiento incial de existencias de inventario.
* @fecha creación: 15/12/2008
* @autor: Ing. Gusmary Balza.
* * **************************
* @fecha modificacion 
* @autor  
* @descripcion 
***********************************************************************************/
require_once('../../base/librerias/php/general/sigesp_lib_funciones.php');
$sessionvalida = true;//validarSession();
if (($_POST['objdata']) && ($sessionvalida))
{		
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/apr/sigesp_dao_apr_inventario.php');	
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistemaventana.php');
		
	$_SESSION['session_activa']=time();
	$objdata = str_replace('\\','',$_POST['objdata']);	
	$objdata = json_decode($objdata,false);		
	
	$objInventario = new MovimientoInventario();	
	$objInventario->codemp = $_SESSION['la_empresa']['codemp'];	
	$objInventario->codsis = $objdata->sistema;
	$objInventario->nomfisico = $objdata->vista;
		
	$objSistemaVentana = new SistemaVentana();		
	$objSistemaVentana->codemp = $_SESSION['la_empresa']['codemp'];	
	$objSistemaVentana->codusu = $_SESSION['la_logusr'];	
	$objSistemaVentana->codsis = $objdata->sistema;
	$objSistemaVentana->nomfisico = $objdata->vista;
	$evento = $objdata->operacion;
	
	switch ($evento)
	{			
		case 'procesarInventario':
			$objSistemaVentana->campo = 'ejecutar';
			$accionvalida=true;//$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				$fecha = date('d-m-Y');
				$nombrearchivo = '../../vista/apr/resultados/';
				$nombrearchivo.=$_SESSION['sigesp_basedatos_apr'].'_movimiento_inicial_siv_result_'.$fecha.'.txt';
				$archivo = @fopen($nombrearchivo,'a+');
				$objInventario->archivo = $archivo;		
					
				$objInventario->servidor  = $_SESSION['sigesp_servidor_apr'];
				$objInventario->usuario   = $_SESSION['sigesp_usuario_apr'];
				$objInventario->clave 	  = $_SESSION['sigesp_clave_apr'];
				$objInventario->basedatos = $_SESSION['sigesp_basedatos_apr'];
				$objInventario->gestor 	  = $_SESSION['sigesp_gestor_apr'];
				$objInventario->tipoconexionbd = 'ALTERNA';								
						
				$objInventario->codusu = $_SESSION['la_logusr'];
				$objInventario->insertarMovimientoInicial();				
				if($objInventario->valido)
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');	
				}
				else
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
				}
				$arreglo['valido']  = $objInventario->valido;		
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
	unset($objInventario);
}
?>