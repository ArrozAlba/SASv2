<?php
require_once('../class_folder/dao/class_sigesp_int.php');
require_once('../class_folder/dao/class_sigesp_int_scg.php');
require_once("../librerias/php/general/class_funciones_seguridad.php");
require_once("../class_folder/dao/class_fecha.php");
//require_once("../class_folder/dao/sigesp_sfp_cuentascontDao.php");
//require_once("../class_folder/dao/class_mensajes.php");
//require_once("../class_folder/dao/class_funciones.php");
//require_once("../class_folder/dao/class_sql.php");
//require_once("../class_folder/dao/class_datastore.php");
require_once('../class_folder/dao/sigesp_sfp_plan_unico_reDao.php');
require_once('../class_folder/dao/sigesp_sfp_plancuentasDao.php');
require_once('../class_folder/dao/sigesp_sfp_planingresoDao.php');
require_once('../class_folder/dao/sigesp_sfp_conversionDao.php');
require_once('../class_folder/dao/sigesp_sfp_variacionDao.php');
require_once('../class_folder/dao/sigesp_sfp_dtasientoDao.php');
require_once('../class_folder/dao/sigesp_sfp_asientosvariacionDao.php');
require_once('../librerias/php/general/funciones.php');
require_once('../librerias/php/general/CrearReporte.php');
require_once('../librerias/php/general/Json.php');
if ($_POST['ObjSon']) 	
{
	$submit = str_replace("\\","",$_POST['ObjSon']);
	$json = new Services_JSON;
	$ArJson = $json->decode($submit);
//	ver($ArJson);
	$oPlaIn = new planIngreso();
	PasarDatos(&$oPlaIn,$ArJson);	
	if($ArJson->DatosIng)
	{
		for($j=0;$j<count($ArJson->DatosIng);$j++)
		{
			$aIngreso[$j] =  new planIngreso();
			PasarDatos($aIngreso[$j],$ArJson->DatosIng[$j]);
		}
	}
	
	if($ArJson->movimientocon)
	{
		for($i=0;$i<count($ArJson->movimientocon);$i++)
		{
			$oPlaIn->arrmovcont[$i]= new detalleasientoDao();
			PasarDatos($oPlaIn->arrmovcont[$i],$ArJson->movimientocon[$i]);
		}
	}
	
	if($ArJson->movimientocaif)
	{
		for($i=0;$i<count($ArJson->movimientocaif);$i++)
		{
			$oPlaIn->arrmovcaif[$i] = new AsientoVariacionDao();
			PasarDatos($oPlaIn->arrmovcaif[$i],$ArJson->movimientocaif[$i]);
		}
	}
	

	$Evento = $GLOBALS["oper"];
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
		case 'grabarPlan':
		for($j=0;$j<count($ArJson->DatosIng);$j++)
		{
			if($aIngreso[$j]->Incluir()=="1")
			{	
				$obInt= new class_sigesp_int_scg(); 
				$ls_codemp  = $_SESSION["codemp"];
				$ls_procede = "SFPG";
				$ls_comprobante =$obInt->ObtenerComprobante();
			//	var_dump($ls_comprobante);
			//	die();
				$ls_fecha     = date("Y-m-d");
				$ls_descripcion = "Formulacion de presupuesto de ingresos";
				$is_tipo ="N";
				$is_tipocomp ="1";
				$sig_cuenta=$aIngreso[$j]->sig_cuenta;
				$ano_presupuesto = $aIngreso[$j]->ano_presupuesto;
				
				//luego de hacer la formulacion (incluir en la tabla).
				//- Formar el comprobante contable(llamar al correlativo de comprobantes).
				//-cargar cuentas(hacer la consulta en la tabla conversiones, luego traer la cuenta contable1, traer del formulario la cuenta contable2 y el monto).
				//-una vez traida la informacion de las cuentas crear  el arreglo de cuentas, montos y operacion.
				// y lamar a las funciones correspondientes de acuerdo a esta prueba.
				// estas funciones se encargaran de incluir la informacion en las tablas de contabilidad
				// hacer una prueba con el proceso completo de incluir .
				//luego cuando se actualiza algun monto.
				//y tambien cuando se elimina una operacion de formulacion sea de gastos o ingresos.
				
				$obInt= new class_sigesp_int_scg(); 
				$ls_valido = $obInt->uf_select_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha);
				
				if($ls_valido)
				{
					echo "lo consigio";
				}
				else
				{
					echo "no lo consiguio";
					$result = $obInt->uf_sigesp_insert_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$is_tipocomp,$ls_descripcion,$is_tipo,$sig_cuenta,$ano_presupuesto);
					if($result)
					{
						$CuentaAbonar = $ArJson->DatosIng[$j]->CuentaHaber;//viene de conversiones
						$MontoD = $ArJson->DatosIng[$j]->monto;
						$CuentaADebitar= $ArJson->DatosIng[$j]->CuentaDebe;
						
						if(count($ArJson->movimientos)>0)
						{
							for($i=0;$i<count($ArJson->movimientos);$i++)
							{
								if($ArJson->movimientos[$i]->operacion=='Debe')
								{
									$ArJson->movimientos[$i]->operacion='D';
								}
								else
								{
									$ArJson->movimientos[$i]->operacion='H';
								}
								$arCuentas[$i]["sc_cuenta"] = $ArJson->movimientos[$i]->sc_cuenta;
								$arCuentas[$i]["operacion"] = $ArJson->movimientos[$i]->operacion;
								$arCuentas[$i]["monto"] = $ArJson->movimientos[$i]->monto;
								$arCuentas[$i]["documento"] = 00000;
								$arCuentas[$i]["documento"] = "SFPFGI";
								$ldec_monto_anterior=0;
								$ldec_monto_actual=$ArJson->movimientos[$i]->monto;
							}
						}				

						//ver($arCuentas);
						
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
				//se generan los asientos de variacion patrimonial
				
					if(count($ArJson->movimientos)>0)
					{
						
							for($i=0;$i<count($ArJson->movimientos);$i++)
							{
								
								$oVariacion= new variacionDao();
								$oVariacion->cuentacontable= $ArJson->movimientos[$i]->sc_cuenta;
								if($ArJson->movimientos[$i]->operacion=='D')
								{
									
									$cuentaVarDebe = $oVariacion->LeerCuentaDebe();
									//ver($cuentaVarDebe);
									if($cuentaVarDebe->fields['cuentadebe']!='')
									{
										$oAsientoVar= new AsientoVariacionDao();
										$oAsientoVar->procede='SFPJ';
										$oAsientoVar->comprobante=$ls_comprobante;
										$oAsientoVar->sig_cuenta=$cuentaVarDebe->fields['cuentadebe'];
										$oAsientoVar->debhab='D';
										$oAsientoVar->descripcion='Asiento de Variacion patrimonial';
										$oAsientoVar->monto=$ArJson->movimientos[$i]->monto;
										$oAsientoVar->orden=0;	
										if($oAsientoVar->Incluir())
										{
										//	ver("esto es valido");
											$lb_valido=true;
										}
										else
										{
											$lb_valido=false;
										}						
									 }						
								}
								else
								{	
									$cuentaVarHaber = $oVariacion->LeerCuentaHaber();
									if($cuentaVarHaber->fields['cuentahaber']!='')
									{
										$oAsientoVar= new AsientoVariacionDao();
										$oAsientoVar->procede='SFPJ';
										$oAsientoVar->comprobante=$ls_comprobante;
										$oAsientoVar->sig_cuenta=$cuentaVarHaber->fields['cuentahaber'];;
										$oAsientoVar->debhab='H';
										$oAsientoVar->descripcion='Asiento de Variacion patrimonial';
										$oAsientoVar->monto=$ArJson->movimientos[$i]->monto;
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
					}				
				}
				
				}
				echo "|1";
			}
			else
			{
				echo "|0";
			}
		}
			break;
		case 'buscarcodigo':
			$cad = AgregarUno($ofuente->BuscarCodigo());
			echo "|{$cad}";
			break;
		case 'leerAsientos':
			$rsAsientos = $oPlaIn->LeerAsientos();
			//ver($rsAsientos);
			$ObjSonVar = GenerarJson2($rsAsientos["variacion"]);
			$ObjSonCaif = GenerarJson2($rsAsientos["caif"]);	
			echo "{$ObjSonVar}|{$ObjSonCaif}";	
		break;	
		case 'CatCuenIn':
			$oCuentasIn = new PlancuentasDao();
			$cad = $oCuentasIn->LeerCuentasIngresos();
			$ObjSon = GenerarJson2($cad);	
			echo "{$ObjSon}";
			break;	
		case 'catalogo':
			$Datos = $ofuente->LeerTodos();
			//var_dump($Datos);
			//$Registros = "|";
		//aqui se pasan los datos de un arreglo de objetos a un arreglo denfuefin arreglos de php	
			$obj = $Datos[0];
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
			echo $TextJson;
			break;
		case 'ActualizarPlan':
			if($oPlaIn->Incluir()=="1")
			{
				$obInt= new class_sigesp_int_scg(); 
				$ls_codemp  =$_SESSION["codemp"];
				$ls_procede = "SFPG";
				$ls_comprobante = $obInt->ObtenerComprobante();
			//	var_dump($ls_comprobante);
			//	die();
				$ls_fecha     = date("Y-m-d");
				$ls_descripcion = "Formulacion de presupuesto de ingresos";
				$is_tipo ="N";
				$is_tipocomp ="1";
				$sig_cuenta=$oPlaIn->sig_cuenta;
				$ano_presupuesto = $oPlaIn->ano_presupuesto;
				
				//luego de hacer la formulacion (incluir en la tabla).
				//- Formar el comprobante contable(llamar al correlativo de comprobantes).
				//-cargar cuentas(hacer la consulta en la tabla conversiones, luego traer la cuenta contable1, traer del formulario la cuenta contable2 y el monto).
				//-una vez traida la informacion de las cuentas crear  el arreglo de cuentas, montos y operacion.
				// y lamar a las funciones correspondientes de acuerdo a esta prueba.
				// estas funciones se encargaran de incluir la informacion en las tablas de contabilidad
				// hacer una prueba con el proceso completo de incluir .
				//luego cuando se actualiza algun monto.
				//y tambien cuando se elimina una operacion de formulacion sea de gastos o ingresos.
				
				$obInt= new class_sigesp_int_scg(); 
				$ls_valido = $obInt->uf_select_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha);
				
				if($ls_valido)
				{
					echo "lo consigio";
				}
				else
				{
					echo "no lo consiguio";
					$result = $obInt->uf_sigesp_insert_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$is_tipocomp,$ls_descripcion,$is_tipo,$sig_cuenta,$ano_presupuesto);
			
					if($result)
					{
						$CuentaAbonar = $ArJson->CuentaHaber;//viene de conversiones
						$MontoD = $GLOBALS["monto"];
						$CuentaADebitar=$ArJson->CuentaDebe;																				
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
				$oVariacion->cuentacontable=$ArJson->CuentaDebe;
				$cuentaVarDebe = $oVariacion->LeerCuentaDebe();
				$oVariacion->cuentacontable=$ArJson->CuentaHaber;
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
			else
			{
				echo "|0";
			}
			break;
		case 'buscarcaif':
			$oCuentas = new planUnicoRe();
			$oCuentas->sig_cuenta=$ArJson->sig_cuenta;
			$Datos = $oCuentas->LeerUna();
			$ObjSon = GenerarJson2($Datos);
			echo $ObjSon;
			break;
		case 'eliminarPlan':
			if($oPlaIn->estaDistribuida()===false)
			{
				if($oPlaIn->Eliminar())
				{
					echo "|1";
				}
				else
				{
					echo "|0";
				}
			}
			else
			{
				echo "|-1";
			}
			break;
		case 'modificarPlan':
			//ver($oPlaIn);
			//$oPlaIn->monto = $ArJson->monto;
			if($oPlaIn->ModificarMontoIngreso())
			{
				echo "|1";
			}
			else
			{
				echo "|0";
			}
			break;	
		case 'buscarcadena':
			$oCuentasIn = new PlancuentasDao();
			$Datos = $oCuentasIn->LeerPorCadena($GLOBALS["criterio"],$GLOBALS["cadena"]);
			$ObjSon = GenerarJson2($Datos);
			echo $ObjSon;
			break;
		case 'Reporte':
			$oReporte = new Reporte();
			$Data = $ofuente->LeerTodos();
			$oReporte->CrearXml('listafuente',$Data);
			$oReporte->NomRep="FuenteFin";
			echo $oReporte->MostrarReporte();				
		break;	
		case 'CatPlanIn':
			$Datos = $oPlaIn->LeerDistribucion();
			//var_dump($Datos->fields["montoglobal"]);
			//die();
			$Registros = GenerarJson2($Datos);	
			echo "{$Registros}";
			break;
		case 'CatPlanContIng':
			$oplaCont= new planContable();
			$DataAsiento = $oPlaIn->LeerAsiento();
			$DataAsiento=GenerarJson2($DataAsiento);
			$Datos= $oplaCont->LeerBancos();
			$Registros = GenerarJson2($Datos);
			echo "{$Registros}|{$DataAsiento}";
			break;
	}
}



function PasarDatos($ObjDao,$ObJson)
{

	if(is_object($ObjDao))
	{	
			$ArDao = $ObjDao->getAttributeNames();
			foreach($ObjDao as $IndiceD =>$valorD)
			{
				foreach($ObJson as $Indice =>$valor)
				{
					$Indice = strtolower($Indice);
					if($Indice==$IndiceD && $Indice!="ano_presupuesto" && $Indice!="codemp")
					{
						$ObjDao->$Indice = utf8_decode($valor);					
					}
					else
					{
						
						$GLOBALS[$Indice] = $valor;
						
					}
					
					
					
				}
			}
	}
	else
	{
		foreach($ObJson as $Indice =>$valor)
		{
					
			$GLOBALS[$Indice] = $valor;
						
		}
							
	}	
}

function GenerarJson($Datos)
{

	global $ArJson,$json;
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
						if (is_numeric($valor) && $Propiedad!="ano_presupuesto" && $Propiedad!="codcaif")
						{							
							$valor = number_format($valor,2,",",".");	
						}
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


function GenerarJsonDeObjetos($Datos)
{
			global $json;
			$i=0;
			foreach($Datos as $Datos2)
			{
			
				foreach($Datos2 as $Propiedad=>$valor)
				{
					if(!is_numeric($Propiedad))
					{
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