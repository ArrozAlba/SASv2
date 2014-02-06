<?php
session_start();
/***********************************************************************************
* @Clase para manejar el traspaso de saldos y movimientos en tránsito
* @fecha creación: 04/12/2008
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
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/scb/sigesp_dao_scb_banco.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/scb/sigesp_dao_scb_cuentabanco.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/apr/sigesp_dao_apr_banco.php');	
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistemaventana.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_validaciones.php');
	
	$_SESSION['session_activa']=time();
	$objdata = str_replace('\\','',$_POST['objdata']);	
	$objdata = json_decode($objdata,false);		
	
	$objTrasSaldos = new TraspasoSaldos();		
	$objTrasSaldos->codemp = $_SESSION['la_empresa']['codemp'];	
	$objTrasSaldos->codsis = $objdata->sistema;
	$objTrasSaldos->nomfisico = $objdata->vista;
	//$objTrasSaldos->sistema = strtoupper($objdata->codsis);	
	pasarDatos(&$objTrasSaldos,$objdata,&$evento);
	
	$objSistemaVentana = new SistemaVentana();		
	$objSistemaVentana->codemp = $_SESSION['la_empresa']['codemp'];	
	$objSistemaVentana->codusu = $_SESSION['la_logusr'];	
	$objSistemaVentana->codsis = $objdata->sistema;
	$objSistemaVentana->nomfisico = $objdata->vista;
	$evento = $objdata->operacion;
	
	switch ($evento)
	{
		case 'obtenerBancos':	
			$objBanco = new Banco();
			$objBanco->codemp = $_SESSION['la_empresa']['codemp'];	
			
			$objBanco->servidor  = $_SESSION['sigesp_servidor_apr'];
			$objBanco->usuario   = $_SESSION['sigesp_usuario_apr'];
			$objBanco->clave     = $_SESSION['sigesp_clave_apr'];
			$objBanco->basedatos = $_SESSION['sigesp_basedatos_apr'];
			$objBanco->gestor    = $_SESSION['sigesp_gestor_apr'];
			$objBanco->tipoconexionbd='ALTERNA';
			
			$datos = $objBanco->leer();
			if ($objBanco->valido)
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
		
		case 'obtenerCuenta':
			$objCuenta = new CuentaBanco();
			$objCuenta->codemp = $_SESSION['la_empresa']['codemp'];
			
			$objCuenta->servidor  = $_SESSION['sigesp_servidor_apr'];
			$objCuenta->usuario   = $_SESSION['sigesp_usuario_apr'];
			$objCuenta->clave     = $_SESSION['sigesp_clave_apr'];
			$objCuenta->basedatos = $_SESSION['sigesp_basedatos_apr'];
			$objCuenta->gestor    = $_SESSION['sigesp_gestor_apr'];
			$objCuenta->tipoconexionbd='ALTERNA';
			
			$i=0;
			$objCuenta->criterio[$i]['operador'] = "AND";
			$objCuenta->criterio[$i]['criterio'] = "codban";
			$objCuenta->criterio[$i]['condicion'] = "=";
			$objCuenta->criterio[$i]['valor'] =	"'".$objdata->codban."'";
			$datos = $objCuenta->leer();
			if ($objCuenta->valido)
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

		case 'irProcesar':
			$objSistemaVentana->campo = 'ejecutar';
			$accionvalida=true;//$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				$fecha=date('d-m-Y');
				$nombrearchivo='../../vista/apr/resultados/';
				$nombrearchivo.=$_SESSION['sigesp_basedatos_apr'].'_'.$objTrasSaldos->codsis.'_'.$fecha.'.txt';
				$archivo=@fopen($nombrearchivo,'a+');
				$objTrasSaldos->archivo = $archivo;
								
			//	$objTrasSaldos->iniciarTransaccion();					
				$objTrasSaldos->movtransito = $objdata->movtransito;				
				$objTrasSaldos->fecfin      = convertirFechaBd($objdata->fecfin);	
				$objTrasSaldos->fecini      = convertirFechaBd($objdata->fecini);			
				$objTrasSaldos->procesarSaldos();
			//	$objTrasSaldos->completarTransaccion();				
				if($objTrasSaldos->valido)
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');	
				}
				else
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
				}
				$arreglo['valido']  = $objTrasSaldos->valido;				
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
	unset($objBanco);
	unset($objCuenta);
	unset($objTrasSaldos);
	
}
?>	