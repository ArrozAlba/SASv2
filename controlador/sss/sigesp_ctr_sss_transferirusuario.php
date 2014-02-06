<?php
session_start(); 
/*************************************************************************************** 	
* @Controlador para proceso de transferir usuario y permisología.
* @versión: 1.0      
* @fecha creación: 17/11/2008
* @autor: Ing. Gusmary Balza
* ************************************
* @fecha modificacion: 
* @descripcion: 
* @autor:
****************************************************************************************/
require_once('../../base/librerias/php/general/sigesp_lib_funciones.php');
$sessionvalida = validarSession();
if (($_POST['objdata']) && ($sessionvalida))
{		
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_usuario.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistemaventana.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/cfg/sigesp_dao_cfg_empresa.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_permisosinternos.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_derechosusuario.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/cfg/sigesp_dao_cfg_proc_cons.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/cfg/sigesp_dao_cfg_generarconsecutivo.php');
	
	$_SESSION['session_activa'] = time();	
	$objdata = str_replace('\\','',$_POST['objdata']);
	$objdata = json_decode($objdata,false);	
	
	$ruta = '../../base/xml/';
	$archivoconfig = 'sigesp_xml_configuracion.xml';
	
	$objUsuario = new Usuario();
	$objUsuario->codemp = $_SESSION['la_empresa']['codemp'];
	$objUsuario->nomfisico = $objdata->vista;
	
	$objSistemaVentana = new SistemaVentana();		
	$objSistemaVentana->codemp = $_SESSION['la_empresa']['codemp'];	
	$objSistemaVentana->codusu = $_SESSION['la_logusr'];	
	$objSistemaVentana->codsis = $objdata->sistema;
	$objSistemaVentana->nomfisico = $objdata->vista;
	
	$evento = $objdata->operacion;
	switch ($evento)
	{
		case 'obtenerBdOrigen':
			$varJsonSesion = generarJsonSesion();
			echo $varJsonSesion;	
		break;	
		
		case 'obtenerBdDestino':
			$documentoxml = abrirArchivoXml($ruta,$archivoconfig);
			if ($documentoxml != null)
			{
				$datos = array();
				obtenerConexionbd($documentoxml,$datos);
				$datos  = array('raiz'=>$datos);
				$respuesta = json_encode($datos);
				echo $respuesta;									
			}
		break;
		
		case 'conectar':
			$documentoxml = abrirArchivoXml($ruta,$archivoconfig);	
			if (!is_null($documentoxml))
			{
				$basededatos = crearConexionDestino($documentoxml,$objdata->basedatos);
				if ($basededatos!='')
				{
					$respuesta  = array('raiz'=>$basededatos);
					$respuesta  = json_encode($respuesta);
					echo $respuesta;
				}
				else
				{
					$arreglo['mensaje'] = obtenerMensaje('DATA_NO_EXISTE'); 
					$arreglo['valido']  = false;
					$respuesta  = array('raiz'=>$arreglo);
					$respuesta  = json_encode($respuesta);
					echo $respuesta;
				}
			}
		break;	
		
		case 'obtenerUsuarios':			
				
				$contador = 0;			
				if ($objdata->desde!='' && $objdata->hasta!='')
				{
					$objUsuario->criterio[$contador]['operador'] = "AND";
					$objUsuario->criterio[$contador]['criterio'] = "codusu";
					$objUsuario->criterio[$contador]['condicion'] = ">"."=";
					$objUsuario->criterio[$contador]['valor'] = "'".$objdata->desde."'";
					$contador++;
					
					$objUsuario->criterio[$contador]['operador'] = "AND";
					$objUsuario->criterio[$contador]['criterio'] = "codusu";
					$objUsuario->criterio[$contador]['condicion'] = "<"."=";
					$objUsuario->criterio[$contador]['valor'] = "'".$objdata->hasta."'";
					$contador++;
				}
				if ($objdata->codusu!='')
				{
					$objUsuario->criterio[$contador]['operador'] = "AND";
					$objUsuario->criterio[$contador]['criterio'] = "codusu";
					$objUsuario->criterio[$contador]['condicion'] = "like";
					$objUsuario->criterio[$contador]['valor'] = "'"."%".$objdata->codusu."'";
					$contador++;				
				}	
				if ($objdata->cedusu!='')
				{
					$objUsuario->criterio[$contador]['operador'] = "AND";
					$objUsuario->criterio[$contador]['criterio'] = "cedusu";
					$objUsuario->criterio[$contador]['condicion'] = "like";
					$objUsuario->criterio[$contador]['valor'] = "'"."%".$objdata->cedusu."'";
					$contador++;				
				}	
				if ($objdata->nomusu!='')
				{
					$objUsuario->criterio[$contador]['operador'] = "AND";
					$objUsuario->criterio[$contador]['criterio'] = "nomusu";
					$objUsuario->criterio[$contador]['condicion'] = "like";
					$objUsuario->criterio[$contador]['valor'] = "'"."%".$objdata->nomusu."'";
					$contador++;				
				}
				if ($objdata->apeusu!='')
				{
					$objUsuario->criterio[$contador]['operador'] = "AND";
					$objUsuario->criterio[$contador]['criterio'] = "apeusu";
					$objUsuario->criterio[$contador]['condicion'] = "like";
					$objUsuario->criterio[$contador]['valor'] = "'"."%".$objdata->apeusu."'";
					$contador++;				
				}				
				$datos = $objUsuario->leer();
				if($objUsuario->valido)
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
		
		
		case 'conectarBdDestino':
			$objEmpresa = new Empresa();
			$objEmpresa->servidor 		= $_SESSION['sigesp_servidor_destino'];
			$objEmpresa->usuario 		= $_SESSION['sigesp_usuario_destino'];
			$objEmpresa->clave 			= $_SESSION['sigesp_clave_destino'];
			$objEmpresa->basedatos 		= $_SESSION['sigesp_basedatos_destino'];
			$objEmpresa->gestor 		= $_SESSION['sigesp_gestor_destino'];
			$objEmpresa->tipoconexionbd = 'ALTERNA';
			$datos = $objEmpresa->filtrarEmpresas();
			if($objEmpresa->valido)
			{
				if (!$datos->EOF)
				{
					$varJson=generarJson($datos);
					echo $varJson;				
				}
				else
				{
					$arreglo[0]['valido']  = true;
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
			unset($objEmpresa);			
		break;	
		
		case 'procesar':
			$objSistemaVentana->campo = 'ejecutar';
			$accionvalida=$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				Usuario::iniciarTransaccion();
				$total = count($objdata->datosUsu);
				//echo "total".$total;
				for ($j=0; $j < $total; $j++)
				{					
						
				$objUsuario->servidor		= $_SESSION['sigesp_servidor_destino'];
				$objUsuario->usuario 		= $_SESSION['sigesp_usuario_destino'];
				$objUsuario->clave 			= $_SESSION['sigesp_clave_destino'];
				$objUsuario->basedatos 		= $_SESSION['sigesp_basedatos_destino'];
				$objUsuario->gestor 		= $_SESSION['sigesp_gestor_destino'];
				$objUsuario->tipoconexionbd = 'ALTERNA';
				//objUsuario->codusu = $objdata->datosUsu[0]->codusu;
				$objUsuario->codusu = $objdata->datosUsu[$j]->codusu;
				//echo $objUsuario->codusu;
				//die();
				$objUsuario->buscarCodigo();
				if ($objUsuario->valido)
				{
					//echo "existe ".$objUsuario->existe;
					if ($objUsuario->existe==false)
					{
						$objUsuarioOrig = new Usuario();
						$objUsuarioOrig->codemp = $_SESSION['la_empresa']['codemp'];
						$objUsuarioOrig->nomfisico = $objdata->vista;
						$objUsuarioOrig->codusu = $objdata->datosUsu[$j]->codusu;
						//$objUsuarioOrig->codusu = $objdata->datosUsu[0]->codusu;
						
						$objUsuarioOrig->servidor		= $_SESSION['sigesp_servidor'];
						$objUsuarioOrig->usuario 		= $_SESSION['sigesp_usuario'];
						$objUsuarioOrig->clave 			= $_SESSION['sigesp_clave'];
						$objUsuarioOrig->basedatos 		= $_SESSION['sigesp_basedatos'];
						$objUsuarioOrig->gestor 		= $_SESSION['sigesp_gestor'];
						$objUsuarioOrig->tipoconexionbd = 'ALTERNA';						
						
						$objUsuarioOrig->criterio[0]['operador'] = "AND";
						$objUsuarioOrig->criterio[0]['criterio'] = "codusu";
						$objUsuarioOrig->criterio[0]['condicion'] = "=";
						//$objUsuarioOrig->criterio[0]['valor'] = "'".$objdata->datosUsu[0]->codusu."'";
						$objUsuarioOrig->criterio[0]['valor'] = "'".$objdata->datosUsu[$j]->codusu."'";
						
						$resp = $objUsuarioOrig->leer();
						if ($objUsuarioOrig->valido)
						{
							//$objUsuario->codusu = $objdata->datosUsu[0]->codusu;
							$objUsuario->codusu = $objdata->datosUsu[$j]->codusu;
							$objUsuario->cedusu = $resp->fields['cedusu'];
							$objUsuario->nomusu = $resp->fields['nomusu'];
							$objUsuario->apeusu = $resp->fields['apeusu'];
							$objUsuario->fecnacusu = $resp->fields['fecnacusu'];
							$objUsuario->pwdusu  = $resp->fields['pwdusu'];
							$objUsuario->telusu  = $resp->fields['telusu'];
							$objUsuario->email   = $resp->fields['email'];
							$objUsuario->nota    = $resp->fields['nota'];
							$objUsuario->estatus = $resp->fields['estatus'];
							$objUsuario->admusu  = $resp->fields['admusu'];
							$objUsuario->ultingusu = $resp->fields['ultingusu'];
							$objUsuario->fecblousu = $resp->fields['fecblousu'];
							$objUsuario->fotousu = $resp->fields['fotousu'];
							
							$objUsuario->servidor 		= $_SESSION['sigesp_servidor_destino'];
							$objUsuario->usuario 		= $_SESSION['sigesp_usuario_destino'];
							$objUsuario->clave 			= $_SESSION['sigesp_clave_destino'];
							$objUsuario->basedatos 		= $_SESSION['sigesp_basedatos_destino'];
							$objUsuario->gestor 		= $_SESSION['sigesp_gestor_destino'];
							$objUsuario->tipoconexionbd = 'ALTERNA';
							
							$objUsuario->incluir();
						
							if ($objUsuario->valido)
							{
								$objPermisosOrig = new PermisosInternos();
								$objPermisosOrig->servidor = $_SESSION['sigesp_servidor'];
								$objPermisosOrig->usuario  = $_SESSION['sigesp_usuario'];
								$objPermisosOrig->clave  = $_SESSION['sigesp_clave'];
								$objPermisosOrig->basedatos = $_SESSION['sigesp_basedatos'];
								$objPermisosOrig->gestor = $_SESSION['sigesp_gestor'];
								$objPermisosOrig->tipoconexionbd = 'ALTERNA';	
								$objPermisosOrig->seguridad = false;
								
								$objPermisosOrig->codemp = $_SESSION['la_empresa']['codemp'];	
								//$objPermisosOrig->codusu = $objdata->datosUsu[0]->codusu;
								$objPermisosOrig->codusu = $objdata->datosUsu[$j]->codusu;
								$respPer = $objPermisosOrig->leerTodos();
								//var_dump($respPer->RecordCount());
								//die();
								if ($objPermisosOrig->valido)
								{							
									$objPermisos = new PermisosInternos();
									$objPermisos->servidor = $_SESSION['sigesp_servidor_destino'];
									$objPermisos->usuario  = $_SESSION['sigesp_usuario_destino'];
									$objPermisos->clave  = $_SESSION['sigesp_clave_destino'];
									$objPermisos->basedatos = $_SESSION['sigesp_basedatos_destino'];
									$objPermisos->gestor = $_SESSION['sigesp_gestor_destino'];
									$objPermisos->tipoconexionbd = 'ALTERNA';	
									$objPermisos->seguridad = false;
									//var_dump($respPer);
									while (!$respPer->EOF)
									{
										$objPermisos->codemp = $_SESSION['la_empresa']['codemp'];	
										//$objPermisos->codusu = $objdata->datosUsu[0]->codusu;
										$objPermisos->codusu = $objdata->datosUsu[$j]->codusu;
										$objPermisos->codsis = $respPer->fields['codsis'];
										$objPermisos->codintper = $respPer->fields['codintper'];
										
										$objPermisos->incluirPermisosInternos();
										if ($objPermisos->valido)
										{
											$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');
											$arreglo['valido']  = $objPermisos->valido;
											
										}
										else
										{
											$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
											$arreglo['valido']  = false;
										}
										$respPer->MoveNext();
										
									}
									if ($respPer->EOF)
										{
											$objDerechosOrig = new DerechosUsuario();
											$objDerechosOrig->servidor = $_SESSION['sigesp_servidor'];
											$objDerechosOrig->usuario  = $_SESSION['sigesp_usuario'];
											$objDerechosOrig->clave  = $_SESSION['sigesp_clave'];
											$objDerechosOrig->basedatos = $_SESSION['sigesp_basedatos'];
											$objDerechosOrig->gestor = $_SESSION['sigesp_gestor'];
											$objDerechosOrig->tipoconexionbd = 'ALTERNA';	
											$objDerechosOrig->seguridad = false;	
											
											$objDerechosOrig->codemp = $_SESSION['la_empresa']['codemp'];	
											//$objDerechosOrig->codusu = $objdata->datosUsu[0]->codusu;
											$objDerechosOrig->codusu = $objdata->datosUsu[$j]->codusu;
											$respDe = $objDerechosOrig->leerTodos();
											//echo "sdas".$respDe;
											if ($objDerechosOrig->valido)
											{												
												$objDerechos = new DerechosUsuario();
												$objDerechos->servidor = $_SESSION['sigesp_servidor_destino'];
												$objDerechos->usuario  = $_SESSION['sigesp_usuario_destino'];
												$objDerechos->clave  = $_SESSION['sigesp_clave_destino'];
												$objDerechos->basedatos = $_SESSION['sigesp_basedatos_destino'];
												$objDerechos->gestor = $_SESSION['sigesp_gestor_destino'];
												$objDerechos->tipoconexionbd = 'ALTERNA';	
												$objDerechos->seguridad = false;
												while(!$respDe->EOF)
												{
												//	echo "sistema ".$respDe->fields['codsis'];
													$objDerechos->codemp    = $_SESSION['la_empresa']['codemp'];	
												//	$objDerechos->codusu    = $objdata->datosUsu[0]->codusu;
													$objDerechos->codusu    = $objdata->datosUsu[$j]->codusu;
													$objDerechos->codsis    = $respDe->fields['codsis'];
													$objDerechos->codmenu   = $respDe->fields['codmenu'];
													$objDerechos->codintper = $respDe->fields['codintper'];
													$objDerechos->visible   = $respDe->fields['visible'];
													$objDerechos->enabled   = $respDe->fields['enabled'];
													$objDerechos->leer      = $respDe->fields['leer'];
													$objDerechos->incluir   = $respDe->fields['incluir'];
													$objDerechos->cambiar   = $respDe->fields['cambiar'];
													$objDerechos->eliminar  = $respDe->fields['eliminar'];
													$objDerechos->imprimir  = $respDe->fields['imprimir'];
													$objDerechos->administrativo = $respDe->fields['administrativo'];
													$objDerechos->anular    = $respDe->fields['anular'];
													$objDerechos->ejecutar  = $respDe->fields['ejecutar'];
													$objDerechos->ayuda     = $respDe->fields['ayuda'];
													$objDerechos->cancelar  = $respDe->fields['cancelar'];
													$objDerechos->enviarcorreo  = $respDe->fields['enviarcorreo'];
													$objDerechos->descargar = $respDe->fields['descargar'];
													
													$objDerechos->verificarPerfilUno();
													if ($objDerechos->valido)
													{
														if ($objDerechos->existe==false)
														{
															$objDerechos->incluirTraspaso();															
														}	
														else
														{
															$objDerechos->modificarTraspaso();
														}
														if ($objDerechos->valido)
														{
															$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');
															$arreglo['valido']  = $objDerechos->valido;
															
														}
														else
														{
															$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
															$arreglo['valido']  = false;
														}
													}
													else
													{
														$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
														$arreglo['valido']  = false;
													}											
													$respDe->MoveNext();										
												}
												 $descripcion = 'Usuario Nuevo Transferido: '.$objdata->datosUsu[$j]->codusu.' con los permisos asociados ';
											}
											else
											{
												$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
												$arreglo['valido']  = false;
											}
										} //end del while
										
								}
								else
								{
									$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
									$arreglo['valido']  = false;
								}
														
							}
							else
							{
								$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
								$arreglo['valido']  = false;
							}
						}
						else
						{
							$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
							$arreglo['valido']  = false;
						}
							
							
					}
					else //si el usuario existe
					{
						$objPermisosOrig = new PermisosInternos();
						$objPermisosOrig->servidor = $_SESSION['sigesp_servidor'];
						$objPermisosOrig->usuario  = $_SESSION['sigesp_usuario'];
						$objPermisosOrig->clave  = $_SESSION['sigesp_clave'];
						$objPermisosOrig->basedatos = $_SESSION['sigesp_basedatos'];
						$objPermisosOrig->gestor = $_SESSION['sigesp_gestor'];
						$objPermisosOrig->tipoconexionbd = 'ALTERNA';	
						$objPermisosOrig->seguridad = false;
						
						$objPermisosOrig->codemp = $_SESSION['la_empresa']['codemp'];	
						//$objPermisosOrig->codusu = $objdata->datosUsu[0]->codusu;
						$objPermisosOrig->codusu = $objdata->datosUsu[$j]->codusu;
						$respPer = $objPermisosOrig->leerTodos();
						if ($objPermisosOrig->valido)
						{	
						
							$objPermisos = new PermisosInternos();
							$objPermisos->servidor = $_SESSION['sigesp_servidor_destino'];
							$objPermisos->usuario  = $_SESSION['sigesp_usuario_destino'];
							$objPermisos->clave  = $_SESSION['sigesp_clave_destino'];
							$objPermisos->basedatos = $_SESSION['sigesp_basedatos_destino'];
							$objPermisos->gestor = $_SESSION['sigesp_gestor_destino'];
							$objPermisos->tipoconexionbd = 'ALTERNA';	
							$objPermisos->seguridad = false;
							while (!$respPer->EOF)
							{
								$objPermisos->codemp = $_SESSION['la_empresa']['codemp'];	
								//$objPermisos->codusu = $objdata->datosUsu[0]->codusu;
								$objPermisos->codusu = $objdata->datosUsu[$j]->codusu;
								$objPermisos->codsis = $respPer->fields['codsis'];
								$objPermisos->codintper = $respPer->fields['codintper'];
								
								$objPermisos->incluirPermisosInternos();
								if ($objPermisos->valido)
								{
									$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');
									$arreglo['valido']  = $objPermisos->valido;
									
								}
								else
								{
									$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
									$arreglo['valido']  = false;
								}
								$respPer->MoveNext();
								
							}	
							$objDerechosOrig = new DerechosUsuario();
							$objDerechosOrig->servidor = $_SESSION['sigesp_servidor'];
							$objDerechosOrig->usuario  = $_SESSION['sigesp_usuario'];
							$objDerechosOrig->clave  = $_SESSION['sigesp_clave'];
							$objDerechosOrig->basedatos = $_SESSION['sigesp_basedatos'];
							$objDerechosOrig->gestor = $_SESSION['sigesp_gestor'];
							$objDerechosOrig->tipoconexionbd = 'ALTERNA';	
							$objDerechosOrig->seguridad = false;	
							
							$objDerechosOrig->codemp = $_SESSION['la_empresa']['codemp'];	
							//$objDerechosOrig->codusu = $objdata->datosUsu[0]->codusu;
							$objDerechosOrig->codusu = $objdata->datosUsu[$j]->codusu;
							$respDe = $objDerechosOrig->leerTodos();
							if ($objDerechosOrig->valido)
							{												
								$objDerechos = new DerechosUsuario();
								$objDerechos->servidor = $_SESSION['sigesp_servidor_destino'];
								$objDerechos->usuario  = $_SESSION['sigesp_usuario_destino'];
								$objDerechos->clave  = $_SESSION['sigesp_clave_destino'];
								$objDerechos->basedatos = $_SESSION['sigesp_basedatos_destino'];
								$objDerechos->gestor = $_SESSION['sigesp_gestor_destino'];
								$objDerechos->tipoconexionbd = 'ALTERNA';	
								$objDerechos->seguridad = false;
								while(!$respDe->EOF)
								{
									$objDerechos->codemp    = $_SESSION['la_empresa']['codemp'];	
									//$objDerechos->codusu    = $objdata->datosUsu[0]->codusu;
									$objDerechos->codusu    = $objdata->datosUsu[$j]->codusu;
									$objDerechos->codsis    = $respDe->fields['codsis'];
									$objDerechos->codmenu   = $respDe->fields['codmenu'];
									$objDerechos->codintper = $respDe->fields['codintper'];
									$objDerechos->visible   = $respDe->fields['visible'];
									$objDerechos->enabled   = $respDe->fields['enabled'];
									$objDerechos->leer      = $respDe->fields['leer'];
									$objDerechos->incluir   = $respDe->fields['incluir'];
									$objDerechos->cambiar   = $respDe->fields['cambiar'];
									$objDerechos->eliminar  = $respDe->fields['eliminar'];
									$objDerechos->imprimir  = $respDe->fields['imprimir'];
									$objDerechos->administrativo = $respDe->fields['administrativo'];
									$objDerechos->anular    = $respDe->fields['anular'];
									$objDerechos->ejecutar  = $respDe->fields['ejecutar'];
									$objDerechos->ayuda     = $respDe->fields['ayuda'];
									$objDerechos->cancelar  = $respDe->fields['cancelar'];
									$objDerechos->enviarcorreo  = $respDe->fields['enviarcorreo'];
									$objDerechos->descargar = $respDe->fields['descargar'];
									
									$objDerechos->verificarPerfilUno();
									if ($objDerechos->valido)
									{
										if ($objDerechos->existe==false)
										{
											$objDerechos->incluirTraspaso();															
										}	
										else
										{
											$objDerechos->criterio[0]['operador']  = "WHERE";
											$objDerechos->criterio[0]['criterio']  = "codusu";
											$objDerechos->criterio[0]['condicion'] = "=";
											$objDerechos->criterio[0]['valor']   = "'".$objdata->datosUsu[0]->codusu."'";
											$objDerechos->modificarTraspaso();
										}
										if ($objDerechos->valido)
										{
											$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');
											$arreglo['valido']  = $objDerechos->valido;
											
										}
										else
										{
											$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
											$arreglo['valido']  = false;
										}
									}
									else
									{
										$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
										$arreglo['valido']  = false;
									}										
									$respDe->MoveNext();										
								}
								$descripcion = 'El Usuario '.$objdata->datosUsu[$j]->codusu.' no fue transferido, ya se encuentra en la base de datos destino, se actualizaron sus permisos ';
							}
							else
							{
								$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
								$arreglo['valido']  = false;
							}
						}
					}					
					$objResultado = new ProcCons();
					$objResultado->servidor = $_SESSION['sigesp_servidor'];
					$objResultado->usuario  = $_SESSION['sigesp_usuario'];
					$objResultado->clave  = $_SESSION['sigesp_clave'];
					$objResultado->basedatos = $_SESSION['sigesp_basedatos'];
					$objResultado->gestor = $_SESSION['sigesp_gestor'];
					$objResultado->tipoconexionbd = 'ALTERNA';	
					
					$objConsecutivo = new GenerarConsecutivo();					
					$objConsecutivo->codemp = $_SESSION['la_empresa']['codemp'];		
					$objConsecutivo->codsis = $objdata->sistema;
					$objConsecutivo->tabla      = 'sigesp_dt_proc_cons';
					$objConsecutivo->campo      = 'codres';
					$objConsecutivo->procede    = '';
					$objConsecutivo->longcampo  = '10';
					$objConsecutivo->campoini   = '';
					$objConsecutivo->filtro     = '';
					$objConsecutivo->valor      = '';
					$codigo = $objConsecutivo->generarNumeroNuevo();
										
					$objResultado->codres = $codigo;
					$objResultado->codproc = 'SSSTUS';
					$objResultado->codsis = 'SSS';
					$objResultado->fecha  = date('Y/m/d');
					$objResultado->fecha  = convertirFechaBd($objResultado->fecha);
					$objResultado->bdorigen  = $_SESSION['sigesp_basedatos'];
					$objResultado->bddestino = $_SESSION['sigesp_basedatos_destino'];
					$objResultado->descripcion = $descripcion;
					$objResultado->incluir();
					if ($objResultado->valido)
					{
						$arreglo['mensaje'] = obtenerMensaje('OPERACION_EXITOSA');
						$arreglo['valido']  = $objResultado->valido;						
					}
					else
					{
						$arreglo['mensaje'] = obtenerMensaje('OPERACION_FALLIDA');	
						$arreglo['valido']  = false;
					}					
				}
			
			/*	else
				{
					$arreglo[0]['mensaje'] = obtenerMensaje('OPERACION_FALLIDA'); 
					$arreglo[0]['valido']  = false;
									
				}*/
				
				} //end del for
				Usuario::completarTransaccion();
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
	unset($objPermisosOrig);
	unset($objPermisos);
	unset($objDerechosOrig);
	unset($objDerechos);	
	unset($objSistemaVentana);
	unset($objUsuario);	
}
?>