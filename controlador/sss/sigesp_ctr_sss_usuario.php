<?php
session_start(); 
/***************************************************************************** 	
* @Controlador para la definición de Usuario.
* @versión: 1.0 
* @fecha creación: 21/07/2008.
* @autor: Ing. Gusmary Balza.
**************************************************************************
* @fecha modificacion. 03/09/2008
* @autor: Ing. Gusmary Balza
* @descripcion: Se agrego la opción de seguridad 
******************************************************************************/
require_once('../../base/librerias/php/general/sigesp_lib_funciones.php');
$sessionvalida = validarSession();
if (($_POST['objdata']) && ($sessionvalida))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_usuario.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_usuariosistema.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_usuariogrupo.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_derechosusuario.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_permisosinternos.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistemaventana.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_crearreporte.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_validaciones.php');
	
	$_SESSION['session_activa'] = time();
	$objdata = str_replace('\\','',$_POST['objdata']);	
	$objdata = json_decode($objdata,false);
	$objUsuario = new Usuario();
	pasarDatos(&$objUsuario,$objdata,&$evento);
	$objUsuario->codemp = $_SESSION['la_empresa']['codemp'];
	$objUsuario->codsis = $objdata->sistema;
	$objUsuario->nomfisico = $objdata->vista;		
	$objSistemaVentana = new SistemaVentana();		
	$objSistemaVentana->codemp    = $_SESSION['la_empresa']['codemp'];	
	$objSistemaVentana->codusu    = $_SESSION['la_logusr']; 	
	$objSistemaVentana->codsis    = $objdata->sistema;
	$objSistemaVentana->nomfisico = $objdata->vista;
	$evento = $objdata->oper;
	
	//personal
	if ($objdata->datosAdmin)
	{
		$total = count($objdata->datosAdmin);
		for ($j=0; $j<$total; $j++)
		{
			$objUsuario->admin[$j] = new PermisosInternos();
			$objUsuario->admin[$j]->codemp = $_SESSION['la_empresa']['codemp'];	
			$objUsuario->admin[$j]->codsis = $objdata->datosAdmin[$j]->codsis;
			pasarDatos(&$objUsuario->admin[$j],$objdata->datosAdmin[$j]);	
		}
	}	
	//constantes
	if ($objdata->datosCons)
	{
		$total = count($objdata->datosCons);
		for ($j=0; $j<$total; $j++)
		{
			$objUsuario->constante[$j] = new PermisosInternos();
			$objUsuario->constante[$j]->codemp = $_SESSION['la_empresa']['codemp'];	
			pasarDatos(&$objUsuario->constante[$j],$objdata->datosCons[$j]);	
		}
	}
	//nomina
	if ($objdata->datosNom)
	{
		$total = count($objdata->datosNom);
		for ($j=0; $j<$total; $j++)
		{
			$objUsuario->nomina[$j] = new PermisosInternos();
			$objUsuario->nomina[$j]->codemp = $_SESSION['la_empresa']['codemp'];	
			pasarDatos(&$objUsuario->nomina[$j],$objdata->datosNom[$j]);	
		}
	}
	//unidades ejecutoras
	if ($objdata->datosUni)
	{
		$total = count($objdata->datosUni);
		for ($j=0; $j<$total; $j++)
		{
			$objUsuario->unidad[$j] = new PermisosInternos();
			$objUsuario->unidad[$j]->codemp = $_SESSION['la_empresa']['codemp'];	
			pasarDatos(&$objUsuario->unidad[$j],$objdata->datosUni[$j]);	
		}
	}
	//estructuras presupuestarias
	if ($objdata->datosEstPre)
	{
		$total = count($objdata->datosEstPre);
		for ($j=0; $j<$total; $j++)
		{
			$objUsuario->estpre[$j] = new PermisosInternos();
			$objUsuario->estpre[$j]->codemp = $_SESSION['la_empresa']['codemp'];	
			pasarDatos(&$objUsuario->estpre[$j],$objdata->datosEstPre[$j]);	
		}
	}
	if ($objdata->datosEliminar)
	{
		$total = count($objdata->datosEliminar);
		for ($j=0; $j<$total; $j++)
		{
			$objUsuario->usuariopersonal[$j] = new PermisosInternos();
			pasarDatos(&$objUsuario->usuariopersonal[$j],$objdata->datosEliminar[$j]);	
		}
	}	
	if ($objdata->datosEliminarCons)
	{
		$total = count($objdata->datosEliminarCons);
		for ($j=0; $j<$total; $j++)
		{
			$objUsuario->usuarioconstante[$j] = new PermisosInternos();
			pasarDatos(&$objUsuario->usuarioconstante[$j],$objdata->datosEliminarCons[$j]);	
		}
	}	
	if ($objdata->datosEliminarNom)
	{
		$total = count($objdata->datosEliminarNom);
		for ($j=0; $j<$total; $j++)
		{
			$objUsuario->usuarionomina[$j] = new PermisosInternos();
			pasarDatos(&$objUsuario->usuarionomina[$j],$objdata->datosEliminarNom[$j]);	
		}
	}	
	if ($objdata->datosEliminarUni)
	{
		$total = count($objdata->datosEliminarUni);
		for ($j=0; $j<$total; $j++)
		{
			$objUsuario->usuariounidad[$j] = new PermisosInternos();
			pasarDatos(&$objUsuario->usuariounidad[$j],$objdata->datosEliminarUni[$j]);	
		}
	}	
	if ($objdata->datosEliminarPre)
	{
		$total = count($objdata->datosEliminarPre);
		for ($j=0; $j<$total; $j++)
		{
			$objUsuario->usuarioestpre[$j] = new PermisosInternos();
			pasarDatos(&$objUsuario->usuarioestpre[$j],$objdata->datosEliminarPre[$j]);	
		}
	}	
	
	switch ($evento)
	{
		case 'incluir':	 
			$objSistemaVentana->campo = 'incluir';
			$objUsuario->fecnacusu = convertirFechaBd($objUsuario->fecnacusu);
			$accionvalida   = $objSistemaVentana->verificarUsuario();
			$correcto       = (validaciones($objUsuario->codusu,'30','novacio|caracteres')) && (validaciones($objUsuario->cedusu,'8','novacio|numero')) && (validaciones($objUsuario->nomusu,'100','nombre')) && (validaciones($objUsuario->apeusu,'50','nombre')) && (validaciones($objUsuario->telusu,'20','telefono')) && (validaciones($objUsuario->email,'100','vacioemail')) && (validaciones($objUsuario->nota,'2000','vaciocaracteres'));
			if ($accionvalida)
			{
				if ($correcto)
				{
					$objUsuario->buscarCodigo();
					if ($objUsuario->valido)
					{
						if ($objUsuario->existe===false)	
						{								
							$objUsuario->incluir(); 
							if ($objUsuario->valido)
							{
								$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');
							}
							else
							{
								$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
							}
							$arreglo['valido']  = $objUsuario->valido;
						}
						else
						{					
							$arreglo['mensaje'] = obtenerMensaje('REGISTRO_EXISTE');
							$arreglo['existe']  = $objUsuario->existe;
						}
					}
					else
					{
						$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');
						$arreglo['valido']  = $objUsuario->existe;
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
			$objUsuario->fecnacusu = convertirFechaBd($objUsuario->fecnacusu);
			$accionvalida = $objSistemaVentana->verificarUsuario();
			$correcto = (validaciones($objUsuario->cedusu,'8','numero')) && (validaciones($objUsuario->nomusu,'100','nombre')) && (validaciones($objUsuario->apeusu,'50','nombre')) && (validaciones($objUsuario->telusu,'20','telefono')) && (validaciones($objUsuario->email,'100','vacioemail')) && (validaciones($objUsuario->nota,'2000','vaciocaracteres'));
			if ($accionvalida)
			{	
				if ($correcto)
				{
					$objUsuario->buscarCodigo();
					if ($objUsuario->valido)
					{
						if ($objUsuario->existe===true)	
						{						
							$objUsuario->modificar();
							if ($objUsuario->valido)
							{						
								$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');
							}
							else
							{
								$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
							}
							$arreglo['valido']  = $objUsuario->valido;			
						}							
						else
						{
							$arreglo['mensaje'] = obtenerMensaje('REGISTRO_NO_EXISTE');
							$arreglo['valido']  = $objUsuario->existe;
						}						
					}
					else
					{
						$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');
						$arreglo['valido']  = $objUsuario->existe;
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
				
		case 'catalogo':
			$objSistemaVentana->campo = 'leer';
			$accionvalida=$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				if ($objdata->campo!='')
				{
					$objUsuario->criterio[0]['operador']  = "AND";
					$objUsuario->criterio[0]['criterio']  = "UPPER({$objdata->campo})";
					$objUsuario->criterio[0]['condicion'] = "like";
					$objUsuario->criterio[0]['valor']     = "UPPER('".$objdata->cadena."%"."')";
				}
				$datos = $objUsuario->leer();
				if ($objUsuario->valido)
				{
					if (!$datos->EOF)
					{
						$varJson = generarJson($datos);
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
		
		case 'catalogoActivos':
			$datos = $objUsuario->leerActivos();
			if ($objUsuario->valido)
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
			$objUsuarioP = new PermisosInternos();
			$objUsuarioP->codemp = $_SESSION['la_empresa']['codemp'];
			$objUsuarioP->codusu = $objdata->codusu;
			$objUsuarioP->tabla = 'sno_tipopersonalsss';
			$objUsuarioP->campo = 'codtippersss';
			$objUsuarioP->campo2 = 'dentippersss';
			$objUsuarioP->sistema = 'SNO';
			$objSonPersonal = generarJson($objUsuarioP->obtenerPersonal());
			
			$objUsuarioP->tabla = 'sno_constante';
			$objUsuarioP->campo = 'codcons';
			$objUsuarioP->campo2 = 'nomcon';
			$objUsuarioP->codusu = $objdata->codusu;
			$objUsuarioP->sistema = 'SNO';
			$objSonConstante = generarJson($objUsuarioP->obtenerPersonal());
			
			$objUsuarioP->tabla = 'sno_nomina';
			$objUsuarioP->campo = 'codnom';
			$objUsuarioP->campo2 = 'desnom';
			$objUsuarioP->codusu = $objdata->codusu;
			$objUsuarioP->sistema = 'SNO';
			$objSonNomina = generarJson($objUsuarioP->obtenerPersonal());
			
			$objUsuarioP->tabla = 'spg_unidadadministrativa';
			$objUsuarioP->campo = 'coduniadm';
			$objUsuarioP->campo2 = 'denuniadm';
			$objUsuarioP->codusu = $objdata->codusu;
			$objUsuarioP->sistema = 'SPG';
			$objSonUnidad = generarJson($objUsuarioP->obtenerPersonal());
			
			/*$objUsuario->tabla = 'spg_ep5';
			$objUsuario->campo = 'codest';
			$objUsuario->campo2 = 'denestpro5';
			$objUsuario->sistema = 'SPG';*/
			$objUsuarioP->codusu = $objdata->codusu;
			$objSonEstPre = generarJson($objUsuarioP->obtenerEstPre());
			echo "{$objSonPersonal}|{$objSonConstante}|{$objSonNomina}|{$objSonUnidad}|{$objSonEstPre}";
		break;
		
		case 'eliminar':
			$objSistemaVentana->campo = 'eliminar';
			$accionvalida=$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				$objUsuarioSistema = new UsuarioSistema();
				$objUsuarioSistema->codemp = $_SESSION['la_empresa']['codemp'];
				$objUsuarioSistema->codusu = $objdata->codusu;
				$objUsuarioSistema->buscarUsuarioSistema();
				if ($objUsuarioSistema->existe===false)
				{					
					$objUsuarioGrupo = new UsuarioGrupo();
					$objUsuarioGrupo->codemp = $_SESSION['la_empresa']['codemp'];
					$objUsuarioGrupo->codusu = $objdata->codusu;
					$objUsuarioGrupo->buscarUsuarioGrupo();
					if ($objUsuarioGrupo->existe===false)
					{				
						$objUsuario->usuariodetalle[0] = new PermisosInternos();
						$objUsuario->usuariodetalle[0]->codemp = $_SESSION['la_empresa']['codemp'];
						$objUsuario->usuariodetalle[0]->nomfisico = $objdata->vista;
													
						/*$objUsuario->usuariodetalle[0]->criterio[0]['operador'] = "WHERE";
						$objUsuario->usuariodetalle[0]->criterio[0]['criterio'] = "codemp";
						$objUsuario->usuariodetalle[0]->criterio[0]['condicion'] = "=";
						$objUsuario->usuariodetalle[0]->criterio[0]['valor'] = "'".$_SESSION['la_empresa']['codemp']."'";
																			
						$objUsuario->usuariodetalle[0]->criterio[1]['operador'] = "AND";
						$objUsuario->usuariodetalle[0]->criterio[1]['criterio'] = "codusu";
						$objUsuario->usuariodetalle[0]->criterio[1]['condicion'] = "=";
						$objUsuario->usuariodetalle[0]->criterio[1]['valor'] = "'".$objdata->codusu."'";*/
																				
						//$objUsuario->usuariodetalle[0]->eliminarTodosPrueba();
					
						//if ($objUsuario->usuariodetalle[0]->valido)
						//{
							$objUsuario->eliminar();
							if ($objUsuario->valido)
							{
							$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');	
							}
							else
							{
								$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
							}
							$arreglo['valido']  = $objUsuario->valido;	
						/*}
						else
						{
							$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
						}*/						
					}
					else
					{
						$arreglo['mensaje'] = obtenerMensaje('RELACION_OTRAS_TABLAS','Grupos');
						$arreglo['existe']  = $objUsuario->existe;
					}	
				}	
				else
				{
					$arreglo['mensaje'] = obtenerMensaje('RELACION_OTRAS_TABLAS','Sistema');
					$arreglo['existe']  = $objUsuario->existe;
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
			
		case 'reporteficha':
			$objSistemaVentana->campo = 'imprimir';
			$accionvalida=$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				$objReporte = new crearReporte();
				$objUsuario->cadena = $objdata->codusu;
				$datos = $objUsuario->leer(); 
				if (count($datos)>0)
				{
					$objReporte->crearXml('datos_usuario',$datos);
					
					$objUsuarioP = new PermisosInternos();
					$objUsuarioP->codemp = $_SESSION['la_empresa']['codemp'];
					$objUsuarioP->codusu = $objdata->codusu;
					$objUsuarioP->tabla = 'sno_tipopersonalsss';
					$objUsuarioP->campo = 'codtippersss';
					$objUsuarioP->campo2 = 'dentippersss';
					$objUsuarioP->sistema = 'SNO';
					$datosPer = $objUsuarioP->obtenerPersonal();
									
					$objUsuarioP->tabla = 'sno_constante';
					$objUsuarioP->campo = 'codcons';
					$objUsuarioP->campo2 = 'nomcon';
					$objUsuarioP->codusu = $objdata->codusu;
					$objUsuarioP->sistema = 'SNO';
					$datosCons = $objUsuarioP->obtenerPersonal();
					
					$objUsuarioP->tabla = 'sno_nomina';
					$objUsuarioP->campo = 'codnom';
					$objUsuarioP->campo2 = 'desnom';
					$objUsuarioP->codusu = $objdata->codusu;
					$objUsuarioP->sistema = 'SNO';
					$datosNom = $objUsuarioP->obtenerPersonal();
					
					$objUsuarioP->tabla = 'spg_unidadadministrativa';
					$objUsuarioP->campo = 'coduniadm';
					$objUsuarioP->campo2 = 'denuniadm';
					$objUsuarioP->codusu = $objdata->codusu;
					$objUsuarioP->sistema = 'SPG';
					$datosUni = $objUsuarioP->obtenerPersonal();
					
					$objUsuarioP->codusu = $objdata->codusu;
					$datosPre = $objUsuarioP->obtenerEstPre();
					
					$objReporte->crearXml('personal_usuario',$datosPer);
					$objReporte->crearXml('constantes_usuario',$datosCons);
					$objReporte->crearXml('nominas_usuario',$datosNom);
					$objReporte->crearXml('unidades_usuario',$datosUni);
					$objReporte->crearXml('presupuestos_usuario',$datosPre);
					
					$objReporte->nomRep='ficha_usuario';				
					echo $objReporte->mostrarReporte();	
				}
				else
				{
					echo '';
				}
				unset($objReporte);
			}
			else
			{
				$arreglo['mensaje'] = obtenerMensaje('ACCION_NO_VALIDA'); 
				$arreglo['valido']  = false;
				$respuesta  = array('raiz'=>$arreglo);			
				$respuesta  = json_encode($respuesta);
				echo $respuesta;
			}							
	}
	unset($objSistemaVentana);
	unset($objUsuario);
	//unset($objPerfil);
	unset($objUsuarioGrupo);
	unset($objUsuarioSistema);
	unset($objUsuarioP);
}

?>

