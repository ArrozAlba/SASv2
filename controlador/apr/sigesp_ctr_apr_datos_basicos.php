<?php
session_start();
/***********************************************************************************
* @Clase para manejar el traspaso de los datos básicos
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
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/apr/sigesp_dao_apr_datos_basicos.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/cfg/sigesp_dao_cfg_configuracion.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sno/sigesp_dao_sno_nomina.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistemaventana.php');
	
	$_SESSION['session_activa']=time();
	$objdata = str_replace('\\','',$_POST['objdata']);	
	$objdata = json_decode($objdata,false);		
	$objDatosBasicos = new DatosBasicos();		
	$objDatosBasicos->codemp = $_SESSION['la_empresa']['codemp'];	
	$objDatosBasicos->codsis = $objdata->sistema;
	$objDatosBasicos->nomfisico = $objdata->vista;
	$objDatosBasicos->sistema = strtoupper($objdata->codsis);	
	$objSistemaVentana = new SistemaVentana();		
	$objSistemaVentana->codemp = $_SESSION['la_empresa']['codemp'];	
	$objSistemaVentana->codusu = $_SESSION['la_logusr'];	
	$objSistemaVentana->codsis = $objdata->sistema;
	$objSistemaVentana->nomfisico = $objdata->vista;
	$evento = $objdata->operacion;
	switch ($evento)
	{
		case 'verificarapertura':
			$objConfiguracion = new Configuracion();
			$i=0;
			$objConfiguracion->criterio[$i]['operador']= "WHERE";
			$objConfiguracion->criterio[$i]['criterio']= " codemp ";
			$objConfiguracion->criterio[$i]['condicion']= " = ";
			$objConfiguracion->criterio[$i]['valor']= "'".$_SESSION['la_empresa']['codemp']."'";
			$i++;
			$objConfiguracion->criterio[$i]['operador']= "AND";
			$objConfiguracion->criterio[$i]['criterio']= " seccion ";
			$objConfiguracion->criterio[$i]['condicion']= " = ";
			$objConfiguracion->criterio[$i]['valor']= "'APERTURA'";
			$i++;
			$objConfiguracion->criterio[$i]['operador']= "AND";
			$objConfiguracion->criterio[$i]['criterio']= " entry ";
			$objConfiguracion->criterio[$i]['condicion']= " = ";
			$objConfiguracion->criterio[$i]['valor']= "'APERTURA'";
			$datos = $objConfiguracion->leer();
			if($objConfiguracion->valido)
			{
				if (!$datos->EOF)
				{
					$varJson=generarJson($datos);
					echo $varJson;				
				}
				else
				{
					$arreglo[0]['codsis']  = '';
					$arreglo[0]['valido']  = true;
					$respuesta  = array('raiz'=>$arreglo);
					$respuesta  = json_encode($respuesta);
					echo $respuesta;
				}
				$datos->Close();
			}
			else 
			{	
				$arreglo[0]['mensaje'] = obtenerMensaje('OPERACION_FALLIDA'); 
				$arreglo[0]['valido']  = false;
				$respuesta  = array('raiz'=>$arreglo);
				$respuesta  = json_encode($respuesta);
				echo $respuesta;
			}
			unset($objConfiguracion);
		break;
		
		case 'obtenerDatosNomina':
			$objNomina = new Nomina();
			$objNomina->codemp=$_SESSION['la_empresa']['codemp'];
			$objNomina->servidor=$_SESSION['sigesp_servidor'];
			$objNomina->usuario=$_SESSION['sigesp_usuario'];
			$objNomina->clave=$_SESSION['sigesp_clave'];
			$objNomina->basedatos=$_SESSION['sigesp_basedatos'];
			$objNomina->gestor=$_SESSION['sigesp_gestor'];
			$objNomina->tipoconexionbd='ALTERNA';
			$datos = $objNomina->leer();
			if($objNomina->valido)
			{
				if (!$datos->EOF)
				{
					$varJson=generarJson($datos);
					echo $varJson;				
				}
				else
				{
					$arreglo[0]['codsis']  = '';
					$arreglo[0]['valido']  = true;
					$respuesta  = array('raiz'=>$arreglo);
					$respuesta  = json_encode($respuesta);
					echo $respuesta;
				}
				$datos->Close();
			}
			else 
			{	
				$arreglo[0]['mensaje'] = obtenerMensaje('OPERACION_FALLIDA'); 
				$arreglo[0]['valido']  = false;
				$respuesta  = array('raiz'=>$arreglo);
				$respuesta  = json_encode($respuesta);
				echo $respuesta;
			}
			unset($objNomina);
		break;
		
		case 'procesar':
			$objSistemaVentana->campo = 'ejecutar';
			$accionvalida=true;//$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				$fecha=date('d-m-Y');
				$nombrearchivo='../../vista/apr/resultados/';
				$nombrearchivo.=$_SESSION['sigesp_basedatos_apr'].'_'.$objDatosBasicos->sistema.'_'.$fecha.'.txt';
				$archivo=@fopen($nombrearchivo,'a+');
				$objDatosBasicos->archivo = $archivo;
				
				$ruta = '../../base/xml/';
				$archivo = 'sigesp_xml_apr_'.strtolower($objDatosBasicos->sistema).'.xml';
				$i=0;
				$documentoxml = abrirArchivoXml($ruta,$archivo);
				if ($documentoxml != null)
				{
					$tablas = $documentoxml->getElementsByTagName('tabla');
					if($tablas)
					{ 
						foreach ($tablas as $tabla)
						{	
							$campo = $tabla->getElementsByTagName('tipo');	
							$valor= rtrim($campo->item(0)->nodeValue);
							if (($valor == 'UPDATE') || ($valor == 'INSERT'))
							{
								$objDatosBasicos->tablas[$i]['tipo'] = $valor;
								$objDatosBasicos->tablas[$i]['valornuevo'] = '';
								
								$campo = $tabla->getElementsByTagName('nombre');	
								$valor= rtrim($campo->item(0)->nodeValue);
								$objDatosBasicos->tablas[$i]['tabla'] = $valor;
								
								$campo = $tabla->getElementsByTagName('criterio');	
								$valor= rtrim($campo->item(0)->nodeValue);
								$valor = str_replace('DISTINTO', '<>', $valor);
								$valor = str_replace("-", "'", $valor);
								$valor = str_replace(":", "-", $valor);								
								$objDatosBasicos->tablas[$i]['criterio'] = $valor;
								$i++;
							}
						}
					}					
				}
				if ($objDatosBasicos->sistema == 'HIS')
				{
					$objDatosBasicos->sistema = 'SNR';
				}
				$objDatosBasicos->procesarDatosBasicos();
				
				if($objDatosBasicos->valido)
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');	
				}
				else
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
				}
				$arreglo['valido']  = $objDatosBasicos->valido;
				
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

		case 'procesarsno':
			$objSistemaVentana->campo = 'ejecutar';
			$accionvalida=true;//$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				$fecha=date('d-m-Y');
				$nombrearchivo='../../vista/apr/resultados/';
				$nombrearchivo.=$_SESSION['sigesp_basedatos_apr'].'_'.$objDatosBasicos->sistema.'_'.$fecha.'.txt';
				$archivo=@fopen($nombrearchivo,'a+');
				$objDatosBasicos->archivo = $archivo;
				
				$ruta = '../../base/xml/';
				$archivo = 'sigesp_xml_apr_'.strtolower($objDatosBasicos->sistema).'.xml';
				$i=0;
				$conthistorico=0;
				$documentoxml = abrirArchivoXml($ruta,$archivo);
				if ($documentoxml != null)
				{
					$tablas = $documentoxml->getElementsByTagName('tabla');
					if($tablas)
					{ 
						foreach ($tablas as $tabla)
						{	
							$campo = $tabla->getElementsByTagName('tipo');	
							$valor= rtrim($campo->item(0)->nodeValue);
							if (($valor == 'UPDATE') || ($valor == 'INSERT') || ($valor == 'HISTORICO'))
							{
								if ($valor == 'HISTORICO')
								{
									$objDatosBasicos->historicos[$conthistorico]['tipo'] = 'INSERT';
									$campo = $tabla->getElementsByTagName('nombre');	
									$valor= rtrim($campo->item(0)->nodeValue);
									$objDatosBasicos->historicos[$conthistorico]['tabla'] = $valor;
									$conthistorico++;
								}
								else
								{
									$objDatosBasicos->tablas[$i]['tipo'] = $valor;
									$objDatosBasicos->tablas[$i]['valornuevo'] = '';
									$campo = $tabla->getElementsByTagName('nombre');	
									$valor= rtrim($campo->item(0)->nodeValue);
									$objDatosBasicos->tablas[$i]['tabla'] = $valor;
									
									$campo = $tabla->getElementsByTagName('criterio');	
									$valor= rtrim($campo->item(0)->nodeValue);
									$valor = str_replace('DISTINTO', '<>', $valor);
									$valor = str_replace("-", "'", $valor);
									$valor = str_replace(":", "-", $valor);								
									$objDatosBasicos->tablas[$i]['criterio'] = $valor;
									$i++;
								}
							}
						}
					}					
				}
				$total=	count($objdata->datosNomina);
				
				for ($contador=0; $contador < $total; $contador++)
				{
					$objDatosBasicos->nominas[$contador] = new Nomina();
					$objDatosBasicos->nominas[$contador]->codnom = $objdata->datosNomina[$contador]->codnom;
					$objDatosBasicos->nominas[$contador]->codnuenom = $objdata->datosNomina[$contador]->codnuenom;
					
					foreach ($tablas as $tabla)
					{	
						$campo = $tabla->getElementsByTagName('tipo');	
						$valor= rtrim($campo->item(0)->nodeValue);
						if (($valor == 'NOMINA'))
						{
							$valor = 'INSERT';
							$objDatosBasicos->tablas[$i]['tipo'] = 'INSERT';
							$campo = $tabla->getElementsByTagName('nombre');	
							$valor= rtrim($campo->item(0)->nodeValue);
							$objDatosBasicos->tablas[$i]['tabla'] = $valor;
							$objDatosBasicos->tablas[$i]['valornuevo'] = str_pad($objdata->datosNomina[$contador]->codnuenom,4,'0',0);
							$campo = $tabla->getElementsByTagName('criterio');	
							$valor= rtrim($campo->item(0)->nodeValue);
							$valor = str_replace('DISTINTO', '<>', $valor);
							$valor = str_replace("-", "'", $valor);
							$valor = str_replace(":", "-", $valor);								
							if ($valor == '')
							{
								$objDatosBasicos->tablas[$i]['criterio'] = " WHERE codnom = '".str_pad($objdata->datosNomina[$contador]->codnom,4,'0',0)."' ";
							}
							else
							{
								$objDatosBasicos->tablas[$i]['criterio'] = $valor." AND codnom = '".str_pad($objdata->datosNomina[$contador]->codnom,4,'0',0)."' ";
							}
							$i++;
						}
					}
				}
				$objDatosBasicos->fecinisem=$objdata->fecinisem;
				$objDatosBasicos->fecinimen=$objdata->fecinimen;
				$objDatosBasicos->procesarDatosBasicos();
				
				if($objDatosBasicos->valido)
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');	
				}
				else
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
				}
				$arreglo['valido']  = $objDatosBasicos->valido;
				
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
		
		case 'eliminar':
			$objSistemaVentana->campo = 'ejecutar';
			$accionvalida=true;//$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				$fecha=date('d-m-Y');
				$nombrearchivo='../../vista/apr/resultados/';
				$nombrearchivo.=$_SESSION['sigesp_basedatos_apr'].'_'.$objDatosBasicos->sistema.'_'.$fecha.'.txt';
				$archivo=@fopen($nombrearchivo,'a+');
				$objDatosBasicos->archivo = $archivo;
				
				$ruta = '../../base/xml/';
				$archivo = 'sigesp_xml_apr_'.strtolower($objDatosBasicos->sistema).'.xml';
				$documentoxml = abrirArchivoXml($ruta,$archivo);
				if ($documentoxml != null)
				{
					$tablas = $documentoxml->getElementsByTagName('tabla');
					if($tablas)
					{ 
						foreach ($tablas as $tabla)
						{	
							$campo = $tabla->getElementsByTagName('tipo');	
							$valor= rtrim($campo->item(0)->nodeValue);
							if (($valor == 'DELETE') || ($valor == 'INSERT') || ($valor == 'NOMINA') || ($valor == 'HISTORICO'))
							{								
								$campo = $tabla->getElementsByTagName('id');	
								$i = rtrim($campo->item(0)->nodeValue);
								
								$campo = $tabla->getElementsByTagName('nombre');	
								$valor= rtrim($campo->item(0)->nodeValue);
								$objDatosBasicos->tablas[$i]['tabla'] = $valor;
								
								$campo = $tabla->getElementsByTagName('criterio');	
								$valor= rtrim($campo->item(0)->nodeValue);
								$valor = str_replace('DISTINTO', '<>', $valor);
								$valor = str_replace("-", "'", $valor);
								$valor = str_replace(":", "-", $valor);								
								$objDatosBasicos->tablas[$i]['criterio'] = $valor;
								
							}
						}
					}
				}																					
				$objDatosBasicos->eliminarDatosBasicos();
				if($objDatosBasicos->valido)
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');	
				}
				else
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
				}
				$arreglo['valido']  = $objDatosBasicos->valido;
				
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
	unset($objDatosBasicos);
	unset($objSistemaVentana);
}	
?>
