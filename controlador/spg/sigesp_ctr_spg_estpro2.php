<?php
session_start();
/************************************************************************** 	
* @Controlador para las funciones de estructuras presupuestarias de nivel 2.
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
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/spg/sigesp_dao_spg_estpro2.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistemaventana.php');
	
	$_SESSION['session_activa']=time();
	$objdata = str_replace("\\","",$_POST['objdata']);	
	$objdata = json_decode($objdata,false);		
	$objEstPre = new EstPro2();		
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
			$nomestpro2 = $_SESSION['la_empresa']['nomestpro2'];			
			$arreglo = array ('nivel1'=>$nomestpro1,'nivel2'=>$nomestpro2);			
			$respuesta  = array('raiz'=>$arreglo);
			$respuesta  = json_encode($respuesta);
			echo $respuesta;
		break;
		
		case 'catalogo':			
			/*$objSistemaVentana->campo = 'leer';
			$accionvalida=$objSistemaVentana->verificarUsuario(); //espera por presupuesto de gasto
			if ($accionvalida)
			{*/
				$objEstPre->codusu = $_SESSION['la_logusr'];	
					
				$objEstPre->criterio[0]['operador'] = "AND";
				$objEstPre->criterio[0]['criterio'] = "codestpro1";
				$objEstPre->criterio[0]['condicion'] = "=";
				$objEstPre->criterio[0]['valor'] =	"'".$objdata->codestpro1."'";
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
			/*}
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