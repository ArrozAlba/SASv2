<?php
session_start();
/***********************************************************************************
* @Clase para Manejar  para la definición de Grupo.
* @fecha de creación: 07/08/2008
* @autor: Ing. Gusmary Balza
*************************************
* @fecha modificacion  03/09/2008
* @autor  Ing. Yesenia Moreno de Lang
* @descripcion  Se agrego la opción de seguridad
* ***************************************
* @fecha modificacion  03/11/2008
* @autor  Ing. Gusmary Balza
* @descripcion  Se agrego la opción de asignar permisos
***********************************************************************************/
require_once('../../base/librerias/php/general/sigesp_lib_funciones.php');
$sessionvalida = validarSession();
if (($_POST['objdata']) && ($sessionvalida))
{	
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_crearreporte.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_validaciones.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_grupo.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_usuariogrupo.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_derechosgrupo.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_permisosinternos_grupo.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistemaventana.php');
	
	$_SESSION['session_activa']=time();
	$objdata = str_replace('\\','',$_POST['objdata']);	
	$objdata = json_decode($objdata,false);		
	
	$objGrupo = new Grupo();
	pasarDatos(&$objGrupo,$objdata,&$evento);
	$objGrupo->codemp = $_SESSION['la_empresa']['codemp'];	
	$objGrupo->codsis = $objdata->sistema;	
	$objGrupo->nomfisico = $objdata->vista;	
	$objSistemaVentana = new SistemaVentana();		
	$objSistemaVentana->codemp = $_SESSION['la_empresa']['codemp'];	
	$objSistemaVentana->codusu = $_SESSION['la_logusr'];	
	$objSistemaVentana->codsis = $objdata->sistema;
	$objSistemaVentana->nomfisico = $objdata->vista;
	$evento = $objdata->oper;
	// Cargamos los usuarios que se agregaron al grupo
	if ($objdata->datosAdmin)
	{
		$total = count($objdata->datosAdmin);
		for ($j=0; $j<$total; $j++)
		{
			$objGrupo->admin[$j] = new UsuarioGrupo();
			pasarDatos(&$objGrupo->admin[$j],$objdata->datosAdmin[$j]);	
		}
	}
	// Cargamos los usuarios que se eliminaron al grupo
	if ($objdata->datosEliminar)
	{
		$total = count($objdata->datosEliminar);
		for ($j=0; $j<$total; $j++)
		{
			$objGrupo->usuarioeliminar[$j] = new UsuarioGrupo();
			pasarDatos(&$objGrupo->usuarioeliminar[$j],$objdata->datosEliminar[$j]);	
		}
	}
	//personal	
	if ($objdata->datosPer)
	{
		$total = count($objdata->datosPer);
		for ($j=0; $j<$total; $j++)
		{
			$objGrupo->personal[$j] = new PermisosInternosGrupo();
			$objGrupo->personal[$j]->codemp = $_SESSION['la_empresa']['codemp'];	
			$objGrupo->personal[$j]->codsis = $objdata->datosPer[$j]->codsis;
			pasarDatos(&$objGrupo->personal[$j],$objdata->datosPer[$j]);	
		}
	}	
	//constantes
	if ($objdata->datosCons)
	{
		$total = count($objdata->datosCons);
		for ($j=0; $j<$total; $j++)
		{
			$objGrupo->constante[$j] = new PermisosInternosGrupo();
			$objGrupo->constante[$j]->codemp = $_SESSION['la_empresa']['codemp'];	
			pasarDatos(&$objGrupo->constante[$j],$objdata->datosCons[$j]);	
		}
	}
	//nomina
	if ($objdata->datosNom)
	{
		$total = count($objdata->datosNom);
		for ($j=0; $j<$total; $j++)
		{
			$objGrupo->nomina[$j] = new PermisosInternosGrupo();
			$objGrupo->nomina[$j]->codemp = $_SESSION['la_empresa']['codemp'];	
			pasarDatos(&$objGrupo->nomina[$j],$objdata->datosNom[$j]);	
		}
	}
	//unidades ejecutoras
	if ($objdata->datosUni)
	{
		$total = count($objdata->datosUni);
		for ($j=0; $j<$total; $j++)
		{
			$objGrupo->unidad[$j] = new PermisosInternosGrupo();
			$objGrupo->unidad[$j]->codemp = $_SESSION['la_empresa']['codemp'];	
			pasarDatos(&$objGrupo->unidad[$j],$objdata->datosUni[$j]);	
		}
	}
	//estructuras presupuestarias
	if ($objdata->datosEstPre)
	{
		$total = count($objdata->datosEstPre);
		for ($j=0; $j<$total; $j++)
		{
			$objGrupo->estpre[$j] = new PermisosInternosGrupo();
			$objGrupo->estpre[$j]->codemp = $_SESSION['la_empresa']['codemp'];	
			pasarDatos(&$objGrupo->estpre[$j],$objdata->datosEstPre[$j]);	
		}
	}
	if ($objdata->datosEliminarPer)
	{
		$total = count($objdata->datosEliminarPer);
		for ($j=0; $j<$total; $j++)
		{
			$objGrupo->grupopersonal[$j] = new PermisosInternosGrupo();
			pasarDatos(&$objGrupo->grupopersonal[$j],$objdata->datosEliminarPer[$j]);	
		}
	}	
	if ($objdata->datosEliminarCons)
	{
		$total = count($objdata->datosEliminarCons);
		for ($j=0; $j<$total; $j++)
		{
			$objGrupo->grupoconstante[$j] = new PermisosInternosGrupo();
			pasarDatos(&$objGrupo->grupoconstante[$j],$objdata->datosEliminarCons[$j]);	
		}
	}	
	if ($objdata->datosEliminarNom)
	{
		$total = count($objdata->datosEliminarNom);
		for ($j=0; $j<$total; $j++)
		{
			$objGrupo->gruponomina[$j] = new PermisosInternosGrupo();
			pasarDatos(&$objGrupo->gruponomina[$j],$objdata->datosEliminarNom[$j]);	
		}
	}	
	if ($objdata->datosEliminarUni)
	{
		$total = count($objdata->datosEliminarUni);
		for ($j=0; $j<$total; $j++)
		{
			$objGrupo->grupounidad[$j] = new PermisosInternosGrupo();
			pasarDatos(&$objGrupo->grupounidad[$j],$objdata->datosEliminarUni[$j]);	
		}
	}	
	if ($objdata->datosEliminarPre)
	{
		$total = count($objdata->datosEliminarPre);
		for ($j=0; $j<$total; $j++)
		{
			$objGrupo->grupoestpre[$j] = new PermisosInternosGrupo();
			pasarDatos(&$objGrupo->grupoestpre[$j],$objdata->datosEliminarPre[$j]);	
		}
	}	
	
	switch ($evento)
	{
		case 'incluir':	 
			$objSistemaVentana->campo = 'incluir';
			$accionvalida=$objSistemaVentana->verificarUsuario();
			$correcto = (validaciones($objGrupo->nomgru,'60','nombre')) /*&& (validaciones($objGrupo->nota,'3000','alfanumerico'))*/;
			if ($accionvalida)
			{
				if ($correcto)
				{
					$objGrupo->verificarCodigo();
					if($objGrupo->valido)
					{
						if ($objGrupo->existe===false)
						{
							$objGrupo->incluir();
							if($objGrupo->valido)
							{
								$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');	
							}
							else
							{
								$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
							}
							$arreglo['valido']  = $objGrupo->valido;
						}
						else
						{
							$arreglo['valido']  = $objGrupo->valido;
							$arreglo['mensaje'] = obtenerMensaje('REGISTRO_EXISTE');	
						}
					}
					else
					{				
						$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');
						$arreglo['valido']  = $objGrupo->existe;
					}
				}
				else
				{
					$arreglo['mensaje'] = obtenerMensaje('DATOS_NO_VALIDO');  
					$arreglo['valido']  = false;
				}
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
		
		case 'actualizar':
			$objSistemaVentana->campo = 'cambiar';
			$accionvalida=$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{	
				$objGrupo->verificarCodigo();
				if($objGrupo->valido)
				{
					if ($objGrupo->existe===true)
					{
						$objGrupo->modificar();
						if($objGrupo->valido)
						{
							$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');	
						}
						else
						{
							$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
						}
						$arreglo['valido']  = $objGrupo->valido;
					}
					else
					{
						$arreglo['mensaje'] = obtenerMensaje('REGISTRO_NO_EXISTE'); 
						$arreglo['valido']  = $objGrupo->valido;
					}					
				}
				else
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');
					$arreglo['valido']  = $objGrupo->valido;
				}
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
			$objSistemaVentana->campo = 'eliminar';
			$accionvalida=$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				$objGrupo->verificarCodigo();
				if($objGrupo->valido)
				{
					if ($objGrupo->existe===true)
					{
						$objGrupo->usuarioeliminar[0] = new UsuarioGrupo();
						$objGrupo->grupodetalle[0] = new PermisosInternosGrupo();
						$objGrupo->eliminar();
						if($objGrupo->valido)
						{
							$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');	
						}
						else
						{
							$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
						}
						$arreglo['valido']  = $objGrupo->valido;					
					}
					else 
					{
						$arreglo['mensaje'] = obtenerMensaje('REGISTRO_NO_EXISTE');
						$arreglo['existe']  = $objGrupo->existe;
					}
				}
				else
				{
					$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');
					$arreglo['valido']  = $objGrupo->existe;
				}
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
		
		case 'catalogo':
			$objSistemaVentana->campo = 'leer';
			$accionvalida=$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				$datos = $objGrupo->leer();
				if($objGrupo->valido)
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
			}
			else
			{
				$arreglo[0]['mensaje'] = obtenerMensaje('ACCION_NO_VALIDA'); 
				$arreglo[0]['valido']  = false;
				$respuesta  = array('raiz'=>$arreglo);			
				$respuesta  = json_encode($respuesta);
				echo $respuesta;
			}
		break;		
			
		case 'catalogousuarios':
			$datos = $objGrupo->obtenerUsuarios();
			if($objGrupo->valido)
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

		case 'catalogodetalle':
			$objGrupoDetalle = new PermisosInternosGrupo();
			$objGrupoDetalle->codemp = $_SESSION['la_empresa']['codemp'];
			$objGrupoDetalle->nomgru = $objdata->nomgru;
			$objGrupoDetalle->tabla = 'sno_tipopersonalsss';
			$objGrupoDetalle->campo = 'codtippersss';
			$objGrupoDetalle->campo2 = 'dentippersss';
			$objGrupoDetalle->sistema = 'SNO';
			$objSonPersonal = generarJson($objGrupoDetalle->obtenerPermisos());
			
			$objGrupoDetalle->tabla = 'sno_constante';
			$objGrupoDetalle->campo = 'codcons';
			$objGrupoDetalle->campo2 = 'nomcon';
			$objGrupoDetalle->nomgru = $objdata->nomgru;
			$objGrupoDetalle->sistema = 'SNO';
			$objSonConstante = generarJson($objGrupoDetalle->obtenerPermisos());
			
			$objGrupoDetalle->tabla = 'sno_nomina';
			$objGrupoDetalle->campo = 'codnom';
			$objGrupoDetalle->campo2 = 'desnom';
			$objGrupoDetalle->nomgru = $objdata->nomgru;
			$objGrupoDetalle->sistema = 'SNO';
			$objSonNomina = generarJson($objGrupoDetalle->obtenerPermisos());
			
			$objGrupoDetalle->tabla = 'spg_unidadadministrativa';
			$objGrupoDetalle->campo = 'coduniadm';
			$objGrupoDetalle->nomgru = $objdata->nomgru;
			$objGrupoDetalle->campo2 = 'denuniadm';
			//$objGrupoDetalle->sistema = $objdata->codsis;
			$objSonUnidad = generarJson($objGrupoDetalle->obtenerPermisos());
						
			$objGrupoDetalle->nomgru = $objdata->nomgru;
			$objSonEstPre = generarJson($objGrupoDetalle->obtenerEstPre());
			echo "{$objSonPersonal}|{$objSonConstante}|{$objSonNomina}|{$objSonUnidad}|{$objSonEstPre}";
		break;
		
		case 'reporteficha':
			$objSistemaVentana->campo = 'imprimir';
			$accionvalida=$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				$objReporte = new crearReporte();
				$data = $objGrupo->obtenerUsuarios();  
				
				$objPermisos = new PermisosInternosGrupo();
				$objPermisos->codemp = $_SESSION['la_empresa']['codemp'];
				$objPermisos->nomgru = $objdata->nomgru;
				$objPermisos->tabla = 'sno_tipopersonalsss';
				$objPermisos->campo = 'codtippersss';
				$objPermisos->campo2 = 'dentippersss';
				$objPermisos->sistema = 'SNO';
				$datosPer = $objPermisos->obtenerPermisos();
				
				$objPermisos->tabla = 'sno_constante';
				$objPermisos->campo = 'codcons';
				$objPermisos->campo2 = 'nomcon';
				$objPermisos->nomgru = $objdata->nomgru;
				$objPermisos->sistema = 'SNO';
				$datosCons = $objPermisos->obtenerPermisos();
				
				$objPermisos->tabla = 'sno_nomina';
				$objPermisos->campo = 'codnom';
				$objPermisos->campo2 = 'desnom';
				$objPermisos->nomgru = $objdata->nomgru;
				$objPermisos->sistema = 'SNO';
				$datosNom = $objPermisos->obtenerPermisos();
				
				$objPermisos->tabla = 'spg_unidadadministrativa';
				$objPermisos->campo = 'coduniadm';
				$objPermisos->nomgru = $objdata->nomgru;
				$objPermisos->campo2 = 'denuniadm';
				$objPermisos->sistema = 'SPG';
				$datosUni = $objPermisos->obtenerPermisos();
				
				$objPermisos->nomgru = $objdata->nomgru;
				$datosPre = $objPermisos->obtenerEstPre();
				
			//	if (count($data)>0)
			//	{
					$objReporte->crearXml('usuarios',$data);
					$objReporte->crearXml('personal',$datosPer);
					$objReporte->crearXml('constantes',$datosCons);
					$objReporte->crearXml('nominas',$datosNom);
					$objReporte->crearXml('unidades',$datosUni);
					$objReporte->crearXml('presupuestos',$datosPre);
					
					$objReporte->nomRep='ficha_grupo';
					echo $objReporte->mostrarReporte();	
			/*	}
				else
				{
					echo '';
				}*/
				unset($objReporte);
			}
			else
			{
				$arreglo[0]['mensaje'] = obtenerMensaje('ACCION_NO_VALIDA'); 
				$arreglo[0]['valido']  = false;
				$respuesta  = array('raiz'=>$arreglo);			
				$respuesta  = json_encode($respuesta);
				echo $respuesta;
			}						
	}
	unset($objSistemaVentana);
	unset($objGrupo);
}	
?>
