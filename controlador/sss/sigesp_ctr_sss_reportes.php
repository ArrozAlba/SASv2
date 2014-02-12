<?php
session_start(); 
/********************************************************************************* 	
* @Controlador para reportes de seguridad.
* @versión: 1.0      
* @fecha creación: 31/10/2008
* @autor: Ing. Gusmary Balza
* *****************************
* @fecha modificacion  
* @autor  
* @descripcion  
**********************************************************************************/
require_once('../../base/librerias/php/general/sigesp_lib_funciones.php');
$sessionvalida = validarSession();
if (($_POST['objdata']) && ($sessionvalida))
{		
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistemaventana.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistema.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_evento.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_reportes.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_funciones.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_crearreporte.php');
	
	$_SESSION['session_activa'] = time();
	$objdata    = str_replace('\\','',$_POST['objdata']);	
	$objdata    = json_decode($objdata,false);
	$objPerfil  = new Reportes();
	//pasarDatos(&$objPerfil,$objdata,&$evento);
	$objPerfil->codemp = $_SESSION['la_empresa']['codemp'];	
	//$objPerfil->codsis = $objdata->sistema;	
	$objPerfil->nomfisico = $objdata->vista;
	
	$objSistema = new Sistema();
	$objSistema->codemp = $_SESSION['la_empresa']['codemp'];	
	$objEvento = new Evento();
	$objEvento->nomfisico = $objdata->vista;
	
	$objSistemaVentana = new SistemaVentana();	
	$objSistemaVentana->codemp = $_SESSION['la_empresa']['codemp'];	
	$objSistemaVentana->codusu = $_SESSION['la_logusr'];	
	$objSistemaVentana->codsis = $objdata->sistema;
	$objSistemaVentana->nomfisico = $objdata->vista;
	$evento = $objdata->oper;	
	
	switch ($evento)
	{
		case 'permisos':			
			$objSistemaVentana->campo = 'imprimir';
			$accionvalida=$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{			
				$objPerfil->orden = $objdata->orden;				
				$contador = 0;
				if ($objdata->codsis!='')
				{
					$objPerfil->criterio[$contador]['operador'] = "AND";
					$objPerfil->criterio[$contador]['criterio'] = "codsis";
					$objPerfil->criterio[$contador]['condicion'] = "=";
					$objPerfil->criterio[$contador]['valor'] = "'".$objdata->codsis."'";
					
					
					$objPerfil->criterio2[$contador]['operador'] = "AND";
					$objPerfil->criterio2[$contador]['criterio'] = "codsis";
					$objPerfil->criterio2[$contador]['condicion'] = "=";
					$objPerfil->criterio2[$contador]['valor'] = "'".$objdata->codsis."'";
					
					$contador++;				
				}	
				if ($objdata->codusu!='')
				{
					$objPerfil->criterio[$contador]['operador'] = "AND";
					$objPerfil->criterio[$contador]['criterio'] = "codusu";
					$objPerfil->criterio[$contador]['condicion'] = "=";
					$objPerfil->criterio[$contador]['valor'] = "'".$objdata->codusu."'";
										
					$contador++;				
				}	
				if ($objdata->nomgru!='')
				{
					$objPerfil->criterio2[$contador]['operador'] = "AND";
					$objPerfil->criterio2[$contador]['criterio'] = "nomgru";
					$objPerfil->criterio2[$contador]['condicion'] = "=";
					$objPerfil->criterio2[$contador]['valor'] = "'".$objdata->nomgru."'";
										
					$contador++;				
				}	
				
				$datos = $objPerfil->leerDerechos();
				if ($objPerfil->valido)
				{
					if (!$datos->EOF)
					{
						$objReporte = new crearReporte();
						$objReporte->codsis = strtolower($objdata->sistema);
						$objReporte->crearXml('permisos',$datos);
						$objReporte->nomRep = 'permisos';						
						echo $objReporte->mostrarReporte();				
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
			}
			else
			{
				$arreglo['mensaje'] = obtenerMensaje('ACCION_NO_VALIDA'); 
				$arreglo['valido']  = false;
				$respuesta  = array('raiz'=>$arreglo);
				$respuesta  = json_encode($respuesta);
				echo $respuesta;
			}	
		break;	
		
		//reporte de auditoria
		case 'obtenerSistema':			
			$datos = $objSistema->leer();
			if ($objSistema->valido)
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
		break;
		
		case 'obtenerEvento':			
			$datos = $objEvento->leer();
			if ($objEvento->valido)
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
		break;
		
		case 'auditoria':
			$objSistemaVentana->campo = 'imprimir';
			$accionvalida=$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{		
				$objPerfil->fecha = convertirFechaBd($objdata->fecha);
				$contador = 0;
				if ($objdata->busqueda=='todos' && $objdata->tipoeve=='exito')
				{
					if ($objdata->codsis!='')
					{
						$objPerfil->criterio[$contador]['operador'] = "AND";
						$objPerfil->criterio[$contador]['criterio'] = "codsis";
						$objPerfil->criterio[$contador]['condicion'] = "=";
						$objPerfil->criterio[$contador]['valor'] = "'".$objdata->codsis."'";
						$contador++;
					}	
					if ($objdata->evento!='')
					{				
						$objPerfil->criterio[$contador]['operador'] = "AND";
						$objPerfil->criterio[$contador]['criterio'] = "evento";
						$objPerfil->criterio[$contador]['condicion'] = "=";
						$objPerfil->criterio[$contador]['valor'] = "'".$objdata->evento."'";
					}
					$datos = $objPerfil->leerEventos();			
					
				}
				elseif ($objdata->busqueda=='todos' && $objdata->tipoeve=='falla')
				{
					if ($objdata->codsis!='')
					{
						$objPerfil->criterio[$contador]['operador'] = "AND";
						$objPerfil->criterio[$contador]['criterio'] = "codsis";
						$objPerfil->criterio[$contador]['condicion'] = "=";
						$objPerfil->criterio[$contador]['valor'] = "'".$objdata->codsis."'";
						$contador++;
					}	
					if ($objdata->evento!='')
					{
						$objPerfil->criterio[$contador]['operador'] = "AND";
						$objPerfil->criterio[$contador]['criterio'] = "evento";
						$objPerfil->criterio[$contador]['condicion'] = "=";
						$objPerfil->criterio[$contador]['valor'] = "'".$objdata->evento."'";
					}
					$datos = $objPerfil->leerFallas();
				}
				elseif ($objdata->busqueda=='todos' && $objdata->tipoeve=='todostipo')
				{
					if ($objdata->codsis!='')
					{
						$objPerfil->criterio[$contador]['operador'] = "AND";
						$objPerfil->criterio[$contador]['criterio'] = "codsis";
						$objPerfil->criterio[$contador]['condicion'] = "=";
						$objPerfil->criterio[$contador]['valor'] = "'".$objdata->codsis."'";
						
						$objPerfil->criterio2[$contador]['operador'] = "AND";
						$objPerfil->criterio2[$contador]['criterio'] = "codsis";
						$objPerfil->criterio2[$contador]['condicion'] = "=";
						$objPerfil->criterio2[$contador]['valor'] = "'".$objdata->codsis."'";						
						$contador++;
					}	
					if ($objdata->evento!='')
					{
						$objPerfil->criterio[$contador]['operador'] = "AND";
						$objPerfil->criterio[$contador]['criterio'] = "evento";
						$objPerfil->criterio[$contador]['condicion'] = "=";
						$objPerfil->criterio[$contador]['valor'] = "'".$objdata->evento."'";
						
						$objPerfil->criterio2[$contador]['operador'] = "AND";
						$objPerfil->criterio2[$contador]['criterio'] = "evento";
						$objPerfil->criterio2[$contador]['condicion'] = "=";
						$objPerfil->criterio2[$contador]['valor'] = "'".$objdata->evento."'";												
					}
					$datos = $objPerfil->leerRegistros();						
					
				}
				elseif ($objdata->busqueda=='usuario' && $objdata->tipoeve=='todostipo')
				{
					$objPerfil->criterio[$contador]['operador'] = "AND";
					$objPerfil->criterio[$contador]['criterio'] = "codusu";
					$objPerfil->criterio[$contador]['condicion'] = "=";
					$objPerfil->criterio[$contador]['valor'] = "'".$objdata->codusu."'";
					
					$objPerfil->criterio2[$contador]['operador'] = "AND";
					$objPerfil->criterio2[$contador]['criterio'] = "codusu";
					$objPerfil->criterio2[$contador]['condicion'] = "=";
					$objPerfil->criterio2[$contador]['valor'] = "'".$objdata->codusu."'";
					$contador++;
					
					if ($objdata->codsis!='')
					{
						$objPerfil->criterio[$contador]['operador'] = "AND";
						$objPerfil->criterio[$contador]['criterio'] = "codsis";
						$objPerfil->criterio[$contador]['condicion'] = "=";
						$objPerfil->criterio[$contador]['valor'] = "'".$objdata->codsis."'";
						
						$objPerfil->criterio2[$contador]['operador'] = "AND";
						$objPerfil->criterio2[$contador]['criterio'] = "codsis";
						$objPerfil->criterio2[$contador]['condicion'] = "=";
						$objPerfil->criterio2[$contador]['valor'] = "'".$objdata->codsis."'";	
						
						$contador++;
					}	
					if ($objdata->evento!='')
					{
						$objPerfil->criterio[$contador]['operador'] = "AND";
						$objPerfil->criterio[$contador]['criterio'] = "evento";
						$objPerfil->criterio[$contador]['condicion'] = "=";
						$objPerfil->criterio[$contador]['valor'] = "'".$objdata->evento."'";
						
						$objPerfil->criterio2[$contador]['operador'] = "AND";
						$objPerfil->criterio2[$contador]['criterio'] = "evento";
						$objPerfil->criterio2[$contador]['condicion'] = "=";
						$objPerfil->criterio2[$contador]['valor'] = "'".$objdata->evento."'";	
												
					}								
					$datos = $objPerfil->leerRegistros();																	
				}
				elseif ($objdata->busqueda=='usuario' && $objdata->tipoeve=='exito')
				{
					$objPerfil->criterio[$contador]['operador'] = "AND";
					$objPerfil->criterio[$contador]['criterio'] = "codusu";
					$objPerfil->criterio[$contador]['condicion'] = "=";
					$objPerfil->criterio[$contador]['valor'] = "'".$objdata->codusu."'";
					$contador++;
					
					if ($objdata->codsis!='')
					{
						$objPerfil->criterio[$contador]['operador'] = "AND";
						$objPerfil->criterio[$contador]['criterio'] = "codsis";
						$objPerfil->criterio[$contador]['condicion'] = "=";
						$objPerfil->criterio[$contador]['valor'] = "'".$objdata->codsis."'";
						$contador++;
					}
					if ($objdata->evento!='')	
					{
						$objPerfil->criterio[$contador]['operador'] = "AND";
						$objPerfil->criterio[$contador]['criterio'] = "evento";
						$objPerfil->criterio[$contador]['condicion'] = "=";
						$objPerfil->criterio[$contador]['valor'] = "'".$objdata->evento."'";
					}	
										
					$datos = $objPerfil->leerEventos();
				}
				elseif ($objdata->busqueda=='usuario' && $objdata->tipoeve=='falla')
				{
					$objPerfil->criterio[$contador]['operador'] = "AND";
					$objPerfil->criterio[$contador]['criterio'] = "codusu";
					$objPerfil->criterio[$contador]['condicion'] = "=";
					$objPerfil->criterio[$contador]['valor'] = "'".$objdata->codusu."'";
					$contador++;

					if ($objdata->codsis!='')
					{
						$objPerfil->criterio[$contador]['operador'] = "AND";
						$objPerfil->criterio[$contador]['criterio'] = "codsis";
						$objPerfil->criterio[$contador]['condicion'] = "=";
						$objPerfil->criterio[$contador]['valor'] = "'".$objdata->codsis."'";
						$contador++;						
					}
					if ($objdata->evento!='')
					{
						$objPerfil->criterio[$contador]['operador'] = "AND";
						$objPerfil->criterio[$contador]['criterio'] = "evento";
						$objPerfil->criterio[$contador]['condicion'] = "=";
						$objPerfil->criterio[$contador]['valor'] = "'".$objdata->evento."'";
					}
					$datos = $objPerfil->leerFallas();
				}					
				if ($objPerfil->valido)
				{
					if (!$datos->EOF)
					{
						$objReporte = new crearReporte();
						$objReporte->codsis = strtolower($objdata->sistema);
						$objReporte->crearXml('auditoria',$datos);
						$objReporte->nomRep = 'auditoria';						
						echo $objReporte->mostrarReporte();				
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
			}
			else
			{
				$arreglo['mensaje'] = obtenerMensaje('ACCION_NO_VALIDA'); 
				$arreglo['valido']  = false;
				$respuesta  = array('raiz'=>$arreglo);
				$respuesta  = json_encode($respuesta);
				echo $respuesta;
			}
		break;	
		
		
		case 'traspaso':
			$objSistemaVentana->campo = 'imprimir';
			$accionvalida=$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{		
				$fecha = compararFecha($objdata->fecdesde,$objdata->fechasta);
				if ($fecha)
				{
					//$contador=0;				
					$objPerfil->criterio[0]['operador'] = "WHERE";
					$objPerfil->criterio[0]['criterio'] = "bddestino";
					$objPerfil->criterio[0]['condicion'] = "=";
					$objPerfil->criterio[0]['valor'] = "'".$objdata->bddestino."'";
					//$contador++;
					
					$objdata->fecdesde = convertirFechaBd($objdata->fecdesde);
					$objPerfil->criterio[1]['operador'] = "AND";
					$objPerfil->criterio[1]['criterio'] = "fecha";
					$objPerfil->criterio[1]['condicion'] = ">"."=";
					$objPerfil->criterio[1]['valor'] = "'".$objdata->fecdesde."'";
				//	$contador++;
					
					$objdata->fechasta = convertirFechaBd($objdata->fechasta);
					$objPerfil->criterio[2]['operador'] = "AND";
					$objPerfil->criterio[2]['criterio'] = "fecha";
					$objPerfil->criterio[2]['condicion'] = "<"."=";
					$objPerfil->criterio[2]['valor'] = "'".$objdata->fechasta."'";
				//	$contador++;
					
					$datos = $objPerfil->leerTraspasos();
					if ($objPerfil->valido)
					{					
						if (!$datos->EOF)
						{
							$objReporte = new crearReporte();
							$objReporte->codsis = strtolower($objdata->sistema);
							$objReporte->crearXml('traspaso',$datos);
							$objReporte->nomRep = 'traspasos';							
							echo $objReporte->mostrarReporte();				
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
				}
				else
				{
					$arreglo['mensaje'] = obtenerMensaje('DATOS_NO_VALIDO'); 
					$arreglo['valido']  = false;
					$respuesta  = array('raiz'=>$arreglo);
					$respuesta  = json_encode($respuesta);
					echo $respuesta;
				}	
			}	
			else
			{
				$arreglo['mensaje'] = obtenerMensaje('ACCION_NO_VALIDA'); 
				$arreglo['valido']  = false;
				$respuesta  = array('raiz'=>$arreglo);
				$respuesta  = json_encode($respuesta);
				echo $respuesta;
			}				
		break;	
	}		
	unset($objSistemaVentana);
	unset($objSistema);
	unset($objEvento);
	unset($objPerfil);
	unset($objReporte);	
}
?>