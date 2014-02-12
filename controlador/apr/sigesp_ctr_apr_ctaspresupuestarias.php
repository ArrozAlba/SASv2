<?php
session_start();
/***********************************************************************************
* @Clase para manejar el actualizar las cuentas presupuestarias.
* @fecha creación: 09/12/2008
* @autor: Ing. Gusmary Balza.
* * **************************
* @fecha modificacion 
* @autor  
* @descripcion 
***********************************************************************************/

require_once('../../base/librerias/php/general/sigesp_lib_funciones.php');
$sessionvalida = validarSession();
if (($_POST['objdata']) && ($sessionvalida))
{		
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/apr/sigesp_dao_apr_presupuestarias.php');	
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistemaventana.php');
		
	$_SESSION['session_activa']=time();
	$objdata = str_replace('\\','',$_POST['objdata']);	
	$objdata = json_decode($objdata,false);		
	
	$objActCuentas = new ActCuentasPresupuestarias();	
	$objActCuentas->codemp = $_SESSION['la_empresa']['codemp'];	
	$objActCuentas->codsis = $objdata->sistema;
	$objActCuentas->nomfisico = $objdata->vista;
	
	if ($objdata->datosCuentas)
	{
		$total = count($objdata->datosCuentas);
		for ($j=0; $j<$total; $j++)
		{
			$objActCuentas->cuenta[$j] = new ActCuentasPresupuestarias();	
			$objActCuentas->cuenta[$j]->spgcuentaant = $objdata->datosCuentas[$j]->ctaanterior;
			$objActCuentas->cuenta[$j]->spgcuentaact = $objdata->datosCuentas[$j]->ctaactual;		
			//pasarDatos(&$objActCuentas->cuenta[$j],$objdata->datosCuentas[$j]);	
		}
	}	
	
	$objSistemaVentana = new SistemaVentana();		
	$objSistemaVentana->codemp = $_SESSION['la_empresa']['codemp'];	
	$objSistemaVentana->codusu = $_SESSION['la_logusr'];	
	$objSistemaVentana->codsis = $objdata->sistema;
	$objSistemaVentana->nomfisico = $objdata->vista;
	$evento = $objdata->operacion;
	
	switch ($evento)
	{
		case 'catalogocuentas':	
			$objCuenta = new ActCuentasPresupuestarias();
			$datos = $objCuenta->cargarCuentas();
			if ($objCuenta->valido)
			{
				if (!$datos->EOF)
				{
					//$varJson=generarJson($datos);
					//echo $varJson;
					$respuesta  = array('raiz'=>$datos);
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
		
	
		case 'procesar':
			$objSistemaVentana->campo = 'ejecutar';
			$accionvalida=$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				$fecha = date('d-m-Y');
				$nombrearchivo = '../../vista/apr/resultados/';
				$nombrearchivo.=$_SESSION['sigesp_basedatos_apr'].'_actualizar_cuentas_result_'.$fecha.'.txt';
				$archivo = @fopen($nombrearchivo,'a+');
				$objActCuentas->archivo = $archivo;			
								
				$objActCuentas->incluirCuentas();
				if($objActCuentas->valido)
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');	
				}
				else
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
				}
				$arreglo['valido']  = $objActCuentas->valido;		
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
	unset($objCuenta);	
	unset($objActCuentas);
}
?>