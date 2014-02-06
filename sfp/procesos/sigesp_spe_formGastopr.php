<?php
require_once('../class_folder/dao/class_sigesp_int.php');
require_once('../class_folder/dao/class_sigesp_int_scg.php');
require_once("../class_folder/dao/class_fecha.php");
require_once("../class_folder/dao/sigesp_sfp_cuentascontDao.php");
require_once('../class_folder/dao/sigesp_sfp_intGastosFuenDao.php');
require_once('../class_folder/dao/sigesp_sfp_intUb1Dao.php');
require_once('../class_folder/dao/sigesp_sfp_intUb2Dao.php');
require_once('../class_folder/dao/sigesp_sfp_intUb3Dao.php');
require_once('../class_folder/dao/sigesp_sfp_intUb4Dao.php');
require_once('../class_folder/dao/sigesp_sfp_intUb5Dao.php');
require_once('../class_folder/dao/sigesp_sfp_intProbDao.php');
require_once('../class_folder/dao/sigesp_sfp_intGastosDao.php');
require_once('../class_folder/dao/sigesp_sfp_intMetasDao.php');
require_once('../class_folder/dao/sigesp_sfp_intIndiDao.php');
require_once('../class_folder/dao/sigesp_sfp_planingresoDao.php');
require_once('../class_folder/dao/sigesp_spe_inteprogDao.php');
require_once('../class_folder/dao/sigesp_sfp_cuentasgastosDao.php');
require_once('../class_folder/dao/sigesp_sfp_conversionDao.php');
require_once("../class_folder/dao/sigesp_sfp_cuentascontDao.php");
require_once('../class_folder/dao/sigesp_sfp_plancuentasDao.php');
require_once('../class_folder/dao/sigesp_spe_inteprogDao.php');
require_once('../class_folder/dao/sigesp_sfp_plan_unico_reDao.php');
require_once("../librerias/php/general/class_funciones_seguridad.php");
require_once('../class_folder/dao/sigesp_sfp_intefuenteDao.php');
require_once('../class_folder/dao/sigesp_spe_metasDao.php');
require_once('../class_folder/dao/sigesp_sfp_asientosvariacionDao.php');
require_once('../class_folder/dao/sigesp_sfp_variacionDao.php');
require_once('../class_folder/dao/sigesp_spe_problemas_dao.php');
require_once('../librerias/php/general/funciones.php');
require_once('../librerias/php/general/CrearReporte.php');
require_once('../librerias/php/general/Json.php');
function ObtenerNivelUb($Nivel)
{
		switch($Nivel)
		{
			case "1":
				$AuxObj= new intUb1Dao();
				break;
			case "2":
				$AuxObj= new intUb2Dao();
				break;
			case "3":
				$AuxObj= new intUb3Dao();
				break;
			case "4":
				$AuxObj= new intUb4Dao();
				break;
			case "5":
				$AuxObj= new intUb5Dao();
				break;
		}
		return $AuxObj;
	
}

if ($_POST['ObjSon']) 		
{
	$submit = str_replace("\\","",$_POST['ObjSon']);
	//$submit = utf8_decode($submit);
	$json = new Services_JSON;
	$ArJson = $json->decode($submit);
	$ArObjetos = array();
	$oInte = new IntegracionPre();
	//$oInte->LeerAsientos();
	PasarDatos(&$oInte,$ArJson);
	$Evento = $ArJson->oper;	
	if($ArJson->DatosMetas)
	{
		for($j=0;$j<count($ArJson->DatosMetas);$j++)
		{
			$oInte->Metas[$j] = new intMetasDao();
			PasarDatos(&$oInte->Metas[$j],$ArJson->DatosMetas[$j]);	
		}
	}
	
	
	if($ArJson->DatosIndi)
	{
		for($j=0;$j<count($ArJson->DatosIndi);$j++)
		{
			$oInte->Indis[$j] = new intIndiDao();
			PasarDatos(&$oInte->Indis[$j],$ArJson->DatosIndi[$j]);	
		}
	}

	if($ArJson->DatosGas || $ArJson->oper=='eliminarCuentas')
	{
		for($j=0;$j<count($ArJson->DatosGas);$j++)
		{
			$oInte->Gastos[$j] =  new intGastosDao();
			PasarDatos(&$oInte->Gastos[$j],$ArJson->DatosGas[$j]);	
			if($ArJson->DatosGas[$j]->fuentes)
			{
				for($r=0;$r<count($ArJson->DatosGas[$j]->fuentes);$r++)
				{
					$oInte->Gastos[$j]->fuen[$r] = new intGastosFuenteDao();
					PasarDatos(&$oInte->Gastos[$j]->fuen[$r],$ArJson->DatosGas[$j]->fuentes[$r]);
				}
			}		
		}

	}
	
	
	if($ArJson->oper=='eliminarMetas')
	{
		for($j=0;$j<count($ArJson->DatosMetas);$j++)
		{
			$oInte->Metas[$j] = new intMetasDao();
			PasarDatos(&$oInte->Metas[$j],$ArJson->DatosMetas[$j]);	
		}
	}
	
	
	if($ArJson->oper=='eliminarIndi')
	{
		for($j=0;$j<count($ArJson->DatosIndi);$j++)
		{
			$oInte->Indis[$j] = new intIndiDao();
			PasarDatos(&$oInte->Indis[$j],$ArJson->DatosIndi[$j]);	
		}
	}
	
	switch ($Evento)
	{
		case 'ObtenerSesion':
    		if(!array_key_exists("la_logusr",$_SESSION))
			{
				echo "|nosesion";
				break;	
			}
			$io_fun_activo=new class_funciones_seguridad();
			$io_fun_activo->uf_load_seguridad("SFP",$ArJson->pantalla,$ls_permisos,$la_seguridad,$la_permisos);
			if($ls_permisos===true)
			{
				$jla_seguridad = $json->encode($la_seguridad);
				$jla_permisos = $json->encode($la_permisos);
				echo "{$jla_seguridad}|{$jla_permisos}|{$ls_permisos}";
			}
			else
			{
				echo "0|0|0";
			}
		break;    	
		case 'leersesion':
			echo generarJsonSesion();
		break;
		case 'incluirInt':
				$oInte->codinte=$oInte->ObtenerCodigo();				
				$Resp = $oInte->IncluirTodos();
				if($Resp=="1")
				{			
						if($ArJson->DatosGas)
						{	
						for($j=0;$j<count($ArJson->DatosGas);$j++)
						{
								$obInt= new class_sigesp_int_scg(); 
								$ls_codemp  = "0001";
								$ls_procede = "SFPG";
								$ls_comprobante = $obInt->ObtenerComprobante();
				//			//	var_dump($ls_comprobante);
				//			//	die();
								$ls_fecha     = date("Y-m-d");
								$ls_descripcion = "Formulacion de presupuesto de ingresos";
								$is_tipo ="N";
								$is_tipocomp ="1";
							//	$sig_cuenta=$ArJson->DatosGas[$j]->sig_cuenta;
								$codinte=$oInte->codinte;
								$ano_presupuesto = $oInte->ano_presupuesto;
				//				//luego de hacer la formulacion (incluir en la tabla).
				//				//- Formar el comprobante contable(llamar al correlativo de comprobantes).
				//				//-cargar cuentas(hacer la consulta en la tabla conversiones, luego traer la cuenta contable1, traer del formulario la cuenta contable2 y el monto).
				//				//-una vez traida la informacion de las cuentas crear  el arreglo de cuentas, montos y operacion.
				//				// y lamar a las funciones correspondientes de acuerdo a esta prueba.
				//				// estas funciones se encargaran de incluir la informacion en las tablas de contabilidad
				//				// hacer una prueba con el proceso completo de incluir .
				//				//luego cuando se actualiza algun monto.
				//				//y tambien cuando se elimina una operacion de formulacion sea de gastos o ingresos.
				//				
								$obInt= new class_sigesp_int_scg(); 
								$ls_valido = $obInt->uf_select_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha);
								
								if($ls_valido)
								{
									echo "lo consigio";
								}
								else
								{
									echo "no lo consiguio";
									$result = $obInt->uf_sigesp_insert_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$is_tipocomp,$ls_descripcion,$is_tipo,$sig_cuenta,$ano_presupuesto,$codinte);
									if($result)
									{
										$CuentaAbonar = $ArJson->DatosGas[$j]->CuentaHaber;//viene de conversiones
										$MontoD = $ArJson->DatosGas[$j]->monto;
										$CuentaADebitar=$ArJson->DatosGas[$j]->CuentaDebe;
																											
										//cargar las cuentas para el movimiento
										$arCuentas[1]["sc_cuenta"]=$CuentaADebitar;
										$arCuentas[1]["operacion"]="D";
										$arCuentas[1]["monto"]=$MontoD;
										$arCuentas[1]["documento"]=00000;
										$arCuentas[1]["procede_doc"]="SFPFGI";
										$arCuentas[2]["sc_cuenta"]=$CuentaAbonar;
										$arCuentas[2]["operacion"]="H";
										$arCuentas[2]["monto"]=$MontoD;
										$arCuentas[2]["documento"]=00000;
										$arCuentas[2]["procede_doc"]="SFPFGI";
										$ldec_monto_anterior=0;
										$ldec_monto_actual=$arCuentas[2]["monto"];
									//para cada cuenta del movimiento
								//	var_dump($arCuentas);
								//	die();
									foreach($arCuentas as $registroCuenta)
									{
										if(!$obInt->uf_valida_procedencia($registroCuenta["procede_doc"],&$ls_desproc ))
										{
											$msg->message("Procedencia ".$ls_procede_doc." es invalida");
											return false;	
										}
											
										$ls_valido = $obInt->uf_select_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha);
									if($ls_valido)
									{
											//$ld_fecha=$fun->uf_convertirdatetobd($id_fecha);
										//	echo "estamos aqui";
										//	die();
											$valido = $obInt->uf_scg_procesar_insert_movimiento($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$is_tipo,$registroCuenta["sc_cuenta"],$registroCuenta["procede_doc"],$registroCuenta["documento"],$registroCuenta["operacion"],$ls_descripcion,$ldec_monto_anterior,$registroCuenta["monto"]);
											echo "ya salio de la funcion";
										//	var_dump($valido);
										//	die();
										if(!$valido)
										{
											echo "Error al registrar movimiento contable.";
											return false;
										}
									}
										
								}	
								//echo "llego aqui bien";
								//die();
								$oVariacion= new variacionDao();
								$oVariacion->cuentacontable=$ArJson->DatosGas[$j]->CuentaDebe;
								$cuentaVarDebe = $oVariacion->LeerCuentaDebe();
								$oVariacion->cuentacontable=$ArJson->DatosGas[$j]->CuentaHaber;
								$cuentaVarHaber = $oVariacion->LeerCuentaHaber();
								if($cuentaVarDebe->fields['cuentadebe']!='')
								{
									$oAsientoVar= new AsientoVariacionDao();
									$oAsientoVar->codemp=$ArJson->codemp;
									$oAsientoVar->procede='SFPJ';
									$oAsientoVar->comprobante=$ls_comprobante;
									$oAsientoVar->sig_cuenta=$cuentaVarDebe->fields['cuentadebe'];
									$oAsientoVar->debhab='D';
									$oAsientoVar->descripcion='Asiento de Variacion patrimonial';
									$oAsientoVar->monto=$GLOBALS["monto"];
									$oAsientoVar->orden=0;	
									if($oAsientoVar->Incluir())
									{
										$lb_valido=true;
									}
									else
									{
										$lb_valido=false;
									}						
								}
								
								if($cuentaVarHaber->fields['cuentahaber']!='')
								{
									$oAsientoVar= new AsientoVariacionDao();
									$oAsientoVar->codemp=$ArJson->codemp;
									$oAsientoVar->procede='SFPJ';
									$oAsientoVar->comprobante=$ls_comprobante;
									$oAsientoVar->sig_cuenta=$cuentaVarHaber->fields['cuentahaber'];;
									$oAsientoVar->debhab='H';
									$oAsientoVar->descripcion='Asiento de Variacion patrimonial';
									$oAsientoVar->monto=$GLOBALS["monto"];
									$oAsientoVar->orden=0;
									if($oAsientoVar->Incluir())
									{
										$lb_valido=true;
									}
									else
									{
										$lb_valido=false;
									}									
								}
							
								}
						
								}
								echo "|1";
						}
					}
					echo "|1";
				}
				else
				{
					echo "|$Resp";
				}
			break;
		case 'actualizarInt':
				$Resp=$oInte->ActualizarTodos();
				if($Resp=="1")
				{			
					
						if($ArJson->DatosGas)
						{	
							
							for($j=0;$j<count($ArJson->DatosGas);$j++)
							{
								if($ArJson->DatosGas[$j]->monto!="0")
								{
								$obInt= new class_sigesp_int_scg(); 
								$ls_codemp  = "0001";
								$ls_procede = "SFPG";
								$ls_comprobante = $obInt->ObtenerComprobante();
				//			//	var_dump($ls_comprobante);
				//			//	die();
								$ls_fecha     = date("Y-m-d");
								$ls_descripcion = "Formulacion de presupuesto de gastos";
								$is_tipo ="N";
								$is_tipocomp ="1";
								$sig_cuenta=$ArJson->DatosGas[0]->sig_cuenta;
								$codinte=$oInte->codinte;
								$ano_presupuesto = $ArJson->DatosGas[0]->ano_presupuesto;
				//				//luego de hacer la formulacion (incluir en la tabla).
				//				//- Formar el comprobante contable(llamar al correlativo de comprobantes).
				//				//-cargar cuentas(hacer la consulta en la tabla conversiones, luego traer la cuenta contable1, traer del formulario la cuenta contable2 y el monto).
				//				//-una vez traida la informacion de las cuentas crear  el arreglo de cuentas, montos y operacion.
				//				// y lamar a las funciones correspondientes de acuerdo a esta prueba.
				//				// estas funciones se encargaran de incluir la informacion en las tablas de contabilidad
				//				// hacer una prueba con el proceso completo de incluir .
				//				//luego cuando se actualiza algun monto.
				//				//y tambien cuando se elimina una operacion de formulacion sea de gastos o ingresos.
				//				
								$obInt= new class_sigesp_int_scg(); 
								$ls_valido = $obInt->uf_select_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha);
								
								if($ls_valido)
								{
									//echo "lo consigio";
								}
								else
								{
									//echo "no lo consiguio";
									$result = $obInt->uf_sigesp_insert_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$is_tipocomp,$ls_descripcion,$is_tipo,$sig_cuenta,$ano_presupuesto,$codinte);
									if($result)
									{
										$CuentaAbonar = $ArJson->DatosGas[$j]->CuentaHaber;//viene de conversiones
										$MontoD = $ArJson->DatosGas[$j]->monto;
										$CuentaADebitar=$ArJson->DatosGas[$j]->CuentaDebe;
																											
										//cargar las cuentas para el movimiento
										$arCuentas[1]["sc_cuenta"]=$CuentaADebitar;
										$arCuentas[1]["operacion"]="D";
										$arCuentas[1]["monto"]=$MontoD;
										$arCuentas[1]["documento"]=00000;
										$arCuentas[1]["procede_doc"]="SFPFGI";
										$arCuentas[2]["sc_cuenta"]=$CuentaAbonar;
										$arCuentas[2]["operacion"]="H";
										$arCuentas[2]["monto"]=$MontoD;
										$arCuentas[2]["documento"]=00000;
										$arCuentas[2]["procede_doc"]="SFPFGI";
										$ldec_monto_anterior=0;
										$ldec_monto_actual=$arCuentas[2]["monto"];
									//para cada cuenta del movimiento
								//	var_dump($arCuentas);
								//	die();
									foreach($arCuentas as $registroCuenta)
									{
										if(!$obInt->uf_valida_procedencia($registroCuenta["procede_doc"],&$ls_desproc ))
										{
											$msg->message("Procedencia ".$ls_procede_doc." es invalida");
											return false;	
										}
											
										$ls_valido = $obInt->uf_select_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha);
									if($ls_valido)
									{
											//$ld_fecha=$fun->uf_convertirdatetobd($id_fecha);
										//	echo "estamos aqui";
										//	die();
											$valido = $obInt->uf_scg_procesar_insert_movimiento($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$is_tipo,$registroCuenta["sc_cuenta"],$registroCuenta["procede_doc"],$registroCuenta["documento"],$registroCuenta["operacion"],$ls_descripcion,$ldec_monto_anterior,$registroCuenta["monto"]);
											//echo "ya salio de la funcion";
										//	var_dump($valido);
										//	die();
										if(!$valido)
										{
											echo "Error al registrar movimiento contable.";
											return false;
										}
									}
										
								}	
								//echo "llego aqui bien";
								//die();
								$oVariacion= new variacionDao();
								$oVariacion->cuentacontable=$ArJson->DatosGas[$j]->CuentaDebe;
								$cuentaVarDebe = $oVariacion->LeerCuentaDebe();
								$oVariacion->cuentacontable=$ArJson->DatosGas[$j]->CuentaHaber;
								$cuentaVarHaber = $oVariacion->LeerCuentaHaber();
								if($cuentaVarDebe->fields['cuentadebe']!='')
								{
									$oAsientoVar= new AsientoVariacionDao();
									$oAsientoVar->codemp=$ArJson->codemp;
									$oAsientoVar->procede='SFPJ';
									$oAsientoVar->comprobante=$ls_comprobante;
									$oAsientoVar->sig_cuenta=$cuentaVarDebe->fields['cuentadebe'];
									$oAsientoVar->debhab='D';
									$oAsientoVar->descripcion='Asiento de Variacion patrimonial';
									$oAsientoVar->monto=$GLOBALS["monto"];
									$oAsientoVar->orden=0;	
									if($oAsientoVar->Incluir())
									{
										$lb_valido=true;
									}
									else
									{
										$lb_valido=false;
									}						
								}
								
								if($cuentaVarHaber->fields['cuentahaber']!='')
								{
									$oAsientoVar= new AsientoVariacionDao();
									$oAsientoVar->codemp=$ArJson->codemp;
									$oAsientoVar->procede='SFPJ';
									$oAsientoVar->comprobante=$ls_comprobante;
									$oAsientoVar->sig_cuenta=$cuentaVarHaber->fields['cuentahaber'];;
									$oAsientoVar->debhab='H';
									$oAsientoVar->descripcion='Asiento de Variacion patrimonial';
									$oAsientoVar->monto=$GLOBALS["monto"];
									$oAsientoVar->orden=0;
									if($oAsientoVar->Incluir())
									{
										$lb_valido=true;
									}
									else
									{
										$lb_valido=false;
									}
										
								}
								}
						
								}
								echo "|1";
							}
							}
						}
						else
						{
							echo "|1";
						}
			}
			else
			{
				echo "|0";
			}
			break;
		case 'incluirvarios':
			foreach($ArObjetos as $ofuente)
			{
				if($ofuente->incluir())
				{
					$est =  1;
				}
				else
				{
					$est = 0;
				}
			}
			if($est==1)
			{
				echo "|1";
			}
			else
			{
				echo "|0";
			}
			break;
		case 'CatCuenGas':
			$oCuentasGas = new CuentasGastos();
			$cad = $oCuentasGas->LeerCuentasGastos();
			$ObjSon = GenerarJson2($cad);	
			echo "{$ObjSon}";
			break;
		case 'leerAsientos':
			
			$oInte->sig_cuenta=$ArJson->sig_cuenta;
			
			$rsAsientos = $oInte->LeerAsientos();
		
			$ObjSonVar = GenerarJson2($rsAsientos["variacion"]);
			$ObjSonCaif = GenerarJson2($rsAsientos["caif"]);
			//$ObjSonCaif2 = GenerarJson2($rsAsientos["caif2"]);	
			echo "{$ObjSonVar}|{$ObjSonCaif}|$ObjSonCaif2";	
		break;		
		case 'buscarcaif':
			$oCuentas = new planUnicoRe();
			$oCuentas->sig_cuenta=$ArJson->sig_cuenta;
			$Datos = $oCuentas->LeerUna();
			$ObjSon = GenerarJson2($Datos);
			echo $ObjSon;
		break;	
		case 'CatCuenGasCad':
			$oCuentasIn = new PlancuentasDao();
			$Datos = $oCuentasIn->LeerPorCadenaGas($GLOBALS["criterio"],$GLOBALS["cadena"]);
			$ObjSon = GenerarJson2($Datos);
			echo $ObjSon;
			break;
		case 'catalogoEstInt':	
				//$oInt = new PlancuentasDao();
				$Datos = $oInte->LeerTodosCat();
				$ObjSon = GenerarJson2($Datos);
				echo $ObjSon;
		break;
		case 'buscarcodigo':
			$cad = $ofuente->BuscarCodigo();
			if($cad!='0001')
			{
				$cad = AgregarUno($cad);	
			}
			echo "|{$cad}";
			break;
		case 'CatPlanContGas':
				$oplaCont= new planContable();
				$Datos= $oplaCont->LeerTodas();
				$Registros = GenerarJson2($Datos);
				echo "{$Registros}|''";
				break;		
		case 'buscaruna':
			$Datos = $oInte->LeerUno();
			if($Datos->RecordCount()>0)
			{
				$oInte->codinte=$Datos->fields['codinte'];
				
				$ObjSon = GenerarJson2($Datos);	
				$oNiveles = new ConfNivelDao();
				$nivelProg = $oNiveles->ObtenerNivelesProg();
				$nivelPlan = $oNiveles->ObtenerNivelesPlan();
				
				$rsUbs = $oInte->obtenerUbicaciones();
				$ObjSonUbs = GenerarJson2($rsUbs[0]);
				$nivelUb =$ArUbicaciones[1];
				$ObjSonUnis =  GenerarJson2($oInte->obtenerUnidades());
				$ObjMetas = $oInte->obtenerMetas();
				$ObjMetas = GenerarJson2($ObjMetas);
				$AObjSonCuentas = $oInte->obtenerCuentas();
			
				if($AObjSonCuentas->RecordCount()==0)
				{
					
					$oPlancuenta=new PlancuentasDao();
					$AObjSonCuentas=$oPlancuenta->LeerCuentasFuentes(); 
				}
				$ObjSonCuentas = GenerarJson2($AObjSonCuentas);
				$ObjSonProg = GenerarJson2($nivelProg);				
				$ObjSonPlan = GenerarJson2($nivelPlan);
				echo "{$ObjSon}|{$ObjSonProg}|{$ObjSonPlan}|$ObjSonProb|$ObjSonUbs|$ObjSonUnis|$ObjSonCuentas|$ObjMetas|$rsUbs[1]";
			}
			else
			{
				echo "|0";
			}
			break;	
		case 'leermetas':
			$ObjMetas = $oInte->obtenerMetas();
			$ObjMetas = GenerarJson2($ObjMetas);
			echo "{$ObjMetas}";
		break;	
		case 'buscardetalles':
			if($oInte->codinte!="")
			{
				$ObjMetas = $oInte->obtenerMetas();
				$ObjMetas = GenerarJsonSinFormato($ObjMetas);
				
				$rsInd=$oInte->obtenerIndicadores();
				$ObjSonInd = GenerarJson2($rsInd);
				$AObjSonCuentas = $oInte->obtenerCuentas();
				if($AObjSonCuentas->RecordCount()==0)
				{
					$oPlancuenta=new PlancuentasDao();
					$AObjSonCuentas=$oPlancuenta->LeerCuentasFuentes();
				}
				$ObjSonCuentas = GenerarJson2($AObjSonCuentas);
				echo "{$ObjMetas}|{$ObjSonCuentas}|{$ObjSonInd}";
			}
			break;	
		case 'actualizarvarios':
			//var_dump($ofuente->cod_fuenfin);
			//echo "{$ofuente->denfuefin}";
			//die();
		foreach($ArObjetos as $ofuente)
			{
				
				if($ofuente->Modificar())
				{
					$est =  1;
				}
				else
				{
					$est = 0;
				}
	
			}
				if($est==1)
				{
					echo "|1";
				}
				else
				{
					echo "|0";
				}
				break;
		case 'actualizar':
			//var_dump($ofuente->cod_fuenfin);
			//echo "{$ofuente->denfuefin}";
			//die();
			if($ofuente->Modificar())
			{
				echo "|1";
			}
			else
			{
				echo "|0";
			}
			break;
	
		case 'eliminar':
			if($ofuente->Eliminar())
			{
				echo "|1";
			}
			else
			{
				echo "|0";
			}
			break;	
		case 'buscarcadena':
			$Datos = $ofuente->LeerPorCadena($GLOBALS["criterio"],$GLOBALS["cadena"]);
			$ObjSon = GenerarJson($Datos);
			echo $ObjSon;
			break;
		case 'Reporte':
			$oReporte = new Reporte();
			$Data = $ofuente->LeerTodos();
			$oReporte->CrearXml('listafuente',$Data);
			$oReporte->NomRep="FuenteFin";
			echo $oReporte->MostrarReporte();
			break;
		case 'datosInt':
		switch($GLOBALS['tipodato'])
		{
			case 'uniAds':
			$rsFuentes=$oInte->obtenerUnidades();
			if($rsFuentes->RecordCount()>0)
			{
				$Json=GenerarJson2($rsFuentes);	
				echo $Json;	
			}
			else
			{
				echo "|0";	
			}
					break;
				case 'Ubgeo':
					$rsFuentes=$oInte->obtenerUbicaciones();
				 	if($rsFuentes->RecordCount()>0)
					{
						$Json=GenerarJson2($rsFuentes);	
						echo $Json;	
					}
					else
					{
						echo "|0";
					}
					break;
		}
		case 'leerecursos':
			$oIngresos = new planIngreso();
			
			if($ArJson->Leer=="todas")
			{
				$Datos = $oIngresos->LeerDistribucion();
			}
			else
			{
				$oIngresos->cuenta=$ArJson->sig_cuenta;	
				$Datos = $oIngresos->LeerDistribucionporCuenta();
			}
			$ObjSon = GenerarJson2($Datos);	
			echo $ObjSon;		
			break;	
		case 'eliminarUnis':
			$Res = $oInte->eliminarUnidad($oInte->Ads[0]);
			echo  "|{$Res}";
			break;
		case 'eliminarUbs':
			$Res = $oInte->eliminarUbicacion($oInte->Ubs[0]);
			echo  "|{$Res}";
			break;
		case 'eliminarProbs':
			$Res = $oInte->eliminarProblemas($oInte->Probs[0]);
			echo  "|{$Res}";
			break;
		case 'eliminarMetas':
			for($j=0;$j<count($oInte->Metas);$j++)
			{
				$Res = $oInte->eliminarMetas($oInte->Metas[$j]);
			}
			echo  "|{$Res}";	
			break;
		case 'eliminarIndi':
			for($j=0;$j<count($oInte->Indis);$j++)
			{
				$Res = $oInte->eliminarIndicadores($oInte->Indis[$j]);
			}
			echo  "|{$Res}";	
		break;	
		case 'eliminarCuentas':
			for($j=0;$j<count($oInte->Gastos);$j++)
			{
				$Res = $oInte->eliminarCuentas($oInte->Gastos[$j]);
			}
			echo  "|{$Res}";
			break;
		case 'LeerTodosRep':
				$rs = $oInte->LeerTodosReporte();
				$oReporte= new Reporte();
				$oReporte->CrearXml("cuentasporestructura",$rs);
				$oReporte->NomRep="cuentasporestructuras";
				echo $oReporte->MostrarReporte();
		break;
	}

}

function PasarDatos($ObjDao,$ObJson)
{
	$ArDao = $ObjDao->getAttributeNames();
	foreach($ObjDao as $IndiceD =>$valorD)
	{
		foreach($ObJson as $Indice =>$valor)
		{
			$Indice = strtolower($Indice);
			if($Indice==$IndiceD && $Indice!="ano_presupuesto" && $Indice!="codemp")
			{
				if($valor!="")
				{
					$ObjDao->$Indice = utf8_decode($valor);					
				}
				else
				{
					$ObjDao->$Indice=NULL;
					
				}
			}
			else
			{
				
				$GLOBALS[$Indice] = $valor;
				
			}
			
			
			
		}
	}
}
function GenerarJson2($Datos)
{
			global $json;
			$i=0;
			while($Datos2=$Datos->FetchRow())
			{
				foreach($Datos2 as $Propiedad=>$valor)
				{
					if(!is_numeric($Propiedad))
					{
						
						if (is_numeric($valor) && $Propiedad!="ano_presupuesto" && $Propiedad!="codcaif" && $Propiedad!="codinte")
						{							
							$valor = number_format($valor,2,",",".");	
						}
						
						$Propiedad = strtolower($Propiedad);
						$arRegistros[$i][$Propiedad]= utf8_encode($valor);
					}		
				}
		
				$i++;		
			}
			//aqui se pasa el arreglo de arreglos a un objeto json
			$TextJso = array("raiz"=>$arRegistros);
			$TextJson = $json->encode($TextJso);
			return $TextJson;
}

function GenerarJsonSinFormato($Datos)
{
			global $json;
			$i=0;
			while($Datos2=$Datos->FetchRow())
			{
				foreach($Datos2 as $Propiedad=>$valor)
				{
					if(!is_numeric($Propiedad))
					{
						
						
						
						$Propiedad = strtolower($Propiedad);
						$arRegistros[$i][$Propiedad]= utf8_encode($valor);
					}		
				}
		
				$i++;		
			}
			//aqui se pasa el arreglo de arreglos a un objeto json
			$TextJso = array("raiz"=>$arRegistros);
			$TextJson = $json->encode($TextJso);
			return $TextJson;
}


function GenerarJsonDeArreglos($Datos)
{
		$j=0;
		global $json;
		$i=0;
		foreach($Datos as $Dato)	
		{
			while($Datos2=$Dato->FetchRow())
			{
			
				foreach($Datos2 as $Propiedad=>$valor)
				{
					if(!is_numeric($Propiedad))
					{
						$Propiedad = strtolower($Propiedad);
						$arRegistros[$i][$Propiedad]= utf8_encode($valor);
					}		
				}
		
				$i++;		
			}
			$auxArray[$j]=$arRegistros;
			$j++;
		 }
			//aqui se pasa el arreglo de arreglos a un objeto json
			$TextJso = array("raiz"=>$arRegistros);
			$TextJson = $json->encode($TextJso);
			return $TextJson;
}


function GenerarJson($Datos)
{
	global $ArJson;
	$obj = $Datos[0];
		if(is_object($obj))
		{
			foreach($obj as $Propiedad=>$valor)
			{
				$i=0;
				foreach($Datos as $obj)
				{
		
					if(array_key_exists($Propiedad,$ArJson))	
					{	
						
						$arRegistros[$i][$Propiedad]= $Datos[$i]->$Propiedad;
						$i++;
					}
				
				}
		
					
			}
			//aqui se pasa el arreglo de arreglos a un objeto json
			$TextJso = array("raiz"=>$arRegistros);
			$TextJson = $json->encode($TextJso);
			return $TextJson;
			
		}
}

function GenerarJsonCuentas($Datos)
{
			global $json;
			$i=0;
			while($Datos2=$Datos->FetchRow())
			{
			
				foreach($Datos2 as $Propiedad=>$valor)
				{
					if(!is_numeric($Propiedad))
					{
						$Propiedad = strtolower($Propiedad);
						$arRegistros[$i][$Propiedad]= utf8_encode($valor);
					}		
				}
		
				$i++;		
			}
			//aqui se pasa el arreglo de arreglos a un objeto json
			$TextJso = array("raiz"=>$arRegistros);
			$TextJson = $json->encode($TextJso);
			return $TextJson;		
}


?>