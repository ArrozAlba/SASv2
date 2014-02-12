<?php
session_start();
/***********************************************************************************
* @Clase para manejar el procesar las cuentas contables y de presupuesto..
* @fecha creación: 15/12/2008
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
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/apr/sigesp_dao_apr_procesar_cuentas.php');	
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistemaventana.php');
		
	$_SESSION['session_activa']=time();
	$objdata = str_replace('\\','',$_POST['objdata']);	
	$objdata = json_decode($objdata,false);		
	
	$objActCuentas = new ProcesoCuentas();	
	$objActCuentas->codemp = $_SESSION['la_empresa']['codemp'];	
	$objActCuentas->codsis = $objdata->sistema;
	$objActCuentas->nomfisico = $objdata->vista;
		
	$objSistemaVentana = new SistemaVentana();		
	$objSistemaVentana->codemp = $_SESSION['la_empresa']['codemp'];	
	$objSistemaVentana->codusu = $_SESSION['la_logusr'];	
	$objSistemaVentana->codsis = $objdata->sistema;
	$objSistemaVentana->nomfisico = $objdata->vista;
	$evento = $objdata->operacion;
	
	switch ($evento)
	{			
		case 'procesarCuentas':
			$objSistemaVentana->campo = 'ejecutar';
			$accionvalida=$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				$fecha = date('d-m-Y');
				$nombrearchivo = '../../vista/apr/resultados/';
				$nombrearchivo.=$_SESSION['sigesp_basedatos_apr'].'_procesar_cuentas_result_'.$fecha.'.txt';
				$archivo = @fopen($nombrearchivo,'a+');
				$objActCuentas->archivo = $archivo;			
								
				if ($objdata->scg)
				{
					$objActCuentas->procesarCuentasScg();	
				}					
				if ($objActCuentas->valido)
				{
					if ($objdata->spg)
					{
						$objActCuentas->procesarCuentasSpg();
					}
				}		
				if ($objActCuentas->valido)
				{
					if ($objdata->estpre)
					{
						$objActCuentas->procesarEstructuras();
					}						
				}				
				
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
	unset($objActCuentas);
}
?>