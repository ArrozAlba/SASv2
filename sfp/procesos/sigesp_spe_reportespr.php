<?php
require_once('../class_folder/dao/sigesp_sfp_reporteDao.php');
require_once('../class_folder/dao/sigesp_spe_inteprogDao.php');
require_once('../class_folder/dao/sigesp_spe_asientosDao.php');
require_once('../class_folder/dao/sigesp_sfp_conversionDao.php');
require_once('../class_folder/dao/sigesp_sfp_intGastosDao.php');
require_once('../class_folder/dao/sigesp_sfp_plancuentasDao.php');
require_once('../class_folder/dao/sigesp_sfp_empresaDao.php');
require_once('../class_folder/dao/sigesp_sfp_empresasDao.php');
require_once("../librerias/php/general/class_funciones_seguridad.php");
require_once('../class_folder/dao/sigesp_sfp_planingresoDao.php');
require_once('../class_folder/dao/sigesp_sfp_intGastosDao.php');
require_once('../librerias/php/general/funciones.php');
require_once('../librerias/php/general/CrearReporte.php');
require_once('../librerias/php/general/Json.php');
function getObj($tipo)
{
	switch($tipo)
	{
		case 'GI':
		$tipoCuenta['obj']=new planUnicoRe();
		break;
		case 'CO':
		$tipoCuenta['obj']=new planContable();
		break;
		case 'VP':
		$tipoCuenta['obj']=new planUnicoRe();
		break;
		
	}
	return $tipoCuenta;
}

function getEnc()
{
	$oEmpresa = new empresas();						
	$datosenc = $oEmpresa->obtenerEncReporte();
	if(is_object($datosenc))
	{
		$arrEnc["codente"] =$_SESSION["codemp"]; 
		$arrEnc["anopre"]  =$_SESSION["ano_presupuesto"]; 
		$arrEnc["denente"] =$datosenc->fields["nombre"];
		$arrEnc["organo"] =$datosenc->fields["nomorgads"]; 
	}
	if(is_array($arrEnc))
	{
		return $arrEnc;
	}
	else
	{
		return false;	
	}
}


if ($_POST['ObjSon']) 		
{
	$submit = str_replace("\\","",$_POST['ObjSon']);
	$json = new Services_JSON;
	$ArJson = $json->decode($submit);
	if($ArJson->tipo)
	{
		$oCuenta = getObj($ArJson->tipo);
		PasarDatos(&$oCuenta['obj'],$ArJson);		
	}
	$orepor=new reporteDao();
	PasarDatos($orepor,$ArJson);
	$Evento = $GLOBALS["oper"];
	$arEnc = Array("codente"=>$_SESSION['codemp'],"nombre"=>$_SESSION['nombre'],"year"=>$_SESSION['ano_presupuesto'],"organo"=>$_SESSION["organoad"]);
	//ver($ArJson->codreporte);
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
		case 'incluir':
			//echo "{$ofuente->denfuefin}";
		
		//	var_dump($oConversion);
		//	die();
			if($oConversion->Incluir())
			{
				echo "|1";
			}
			else
			{
				echo "|0";
			}
			break;
		case 'actualizar':
			if($oConversion->Modificar())
			{
				echo "|1";
			}
			else
			{
				echo "|0";
			}
			break;	
		case 'eliminar':
			if($oConversion->Eliminar()==1)
			{
				echo "|1";
			}
			else
			{
				echo "|0";
			}
			break;	
		case 'buscarcadena':
			$Datos = $oCuenta['obj']->LeerTodasCuentas($GLOBALS["criterio"],$GLOBALS["criterio2"],$GLOBALS["cadena"]);
			$ObjSon = GenerarJson2($Datos);
			echo $ObjSon;
			break;
		case 'Reporte':
			$oReporte = new Reporte();
			$Data = $ofuente->LeerTodos();
			$oReporte->CrearXml('listafuente',$Data);
			$oReporte->NomRep="FuenteFin";
			echo $oReporte->MostrarReporte();
		case 'catalogoreporte':
			$Data = $orepor->LeerTodos();
			$ObjSon = GenerarJson2($Data);
			echo $ObjSon;
			break;
		case 'MostrarReporte':
			if($ArJson->codreporte!="")
			{
				switch($ArJson->codreporte)
				{
					case "15":
						$oEmpresa=new empresas();						
						$data = $oEmpresa->LeerUno();
						$oReporte= new Reporte();
						$data->fields["mision"]= str_replace("|","\n",$data->fields["mision"]);
						$oReporte->CrearXmlArr("InfGeneral",$data->fields);
						$oReporte->CrearXmlArr("encabezado",getEnc());
						$oReporte->NomRep="identificacion_ente";
						$ruta = $oReporte->MostrarReporte();
						echo $ruta;
						break;
					case "19":
						$oEmpresa=new empresas();						
						$data = $oEmpresa->LeerUno();
						$oReporte= new Reporte();
						$data->fields["politicapre"]= str_replace("|","\n",$data->fields["politicapre"]);
						$oReporte->CrearXmlArr("politicapre",$data->fields);
						$oReporte->CrearXmlArr("encabezado",getEnc());
						$oReporte->NomRep="politicapre";
						$ruta = $oReporte->MostrarReporte();
						echo $ruta;
						break;						
						case "11":
							$oCuentas=new planIngreso();
							//$data = $oCuentas->reporte_estado_de_resultados();
							$oCuentas->nivel = $ArJson->nivel;
							$data = $oCuentas->reporte_estado_de_resultados2();
							$oReporte= new Reporte();
							$Sumadearreglostotal=$data["datos1"];
							array_push($Sumadearreglostotal,$data["datos8"][0]);
							$oReporte->CrearXmlAcumCuenta("estadoresultado0",$data["datos0"]);
							$oReporte->CrearXmlAcumCuenta("estadoresultado1",$data["datos1"]);
							$oReporte->CrearXmlAcumCuenta("estadoresultado2",$data["datos2"]);
							$oReporte->CrearXmlAcumCuenta("estadoresultado3",$data["datos3"]);
							$oReporte->CrearXmlAcumCuenta("estadoresultado4",$data["datos4"]);
							$oReporte->CrearXmlAcumCuenta("estadoresultado5",$data["datos5"]);
							$oReporte->CrearXmlAcumCuenta("estadoresultado6",$data["datos6"]);
							$oReporte->CrearXmlAcumCuenta("estadoresultado7",$data["datos7"]);
							$oReporte->CrearXmlAcumCuenta("estadoresultado8",$data["datos8"]);
							$oReporte->CrearXmlArr("encabezado",getEnc());
					//		die();
							//$oReporte->CrearXmlAcumCuenta("estadoderesultadostotal",$Sumadearreglostotal);
							switch($ArJson->frecuencia)
							{
								case "Trimestral":
									$oReporte->NomRep="estado_de_resultados";
								break;
								case "Mensual":
									$oReporte->NomRep="estado_de_resultadosmensual";
								break;
								case "Bimensual":
									$oReporte->NomRep="estado_de_resultadosbimensual";
								break;
								case "Semestral":
									$oReporte->NomRep="estado_de_resultadosemestral";
								break;
								default:
									"No disponible";
								break;
							}	
							$ruta = $oReporte->MostrarReporte();
							//$ruta=$ruta."dddddd";
							
							//var_dump($data["datos8"][0]->fields);
							//die();
							$ruta.="&montogastoest=".$data["datos8"][0]->fields["anestimadogas"];
							$ruta.="&montogastoreal=".$data["datos8"][0]->fields["anrealgas"];
							$ruta.="&montogastri1=".$data["datos8"][0]->fields["montogastri1"];
							$ruta.="&montogastri2=".$data["datos8"][0]->fields["montogastri2"];
							$ruta.="&montogastri3=".$data["datos8"][0]->fields["montogastri3"];
							$ruta.="&montogastri4=".$data["datos8"][0]->fields["montogastri4"];
							$ruta.="&enero=".$data["datos8"][0]->fields["enero"];
							$ruta.="&febrero=".$data["datos8"][0]->fields["febrero"];
							$ruta.="&marzo=".$data["datos8"][0]->fields["marzo"];
							$ruta.="&abril=".$data["datos8"][0]->fields["abril"];
							$ruta.="&mayo=".$data["datos8"][0]->fields["mayo"];
							$ruta.="&junio=".$data["datos8"][0]->fields["junio"];
							$ruta.="&julio=".$data["datos8"][0]->fields["julio"];
							$ruta.="&agosto=".$data["datos8"][0]->fields["agosto"];
							$ruta.="&septiembre=".$data["datos8"][0]->fields["septiembre"];
							$ruta.="&octubre=".$data["datos8"][0]->fields["octubre"];
							$ruta.="&noviembre=".$data["datos8"][0]->fields["noviembre"];
							$ruta.="&diciembre=".$data["datos8"][0]->fields["diciembre"];
							$ruta.="&bimestre1=".$data["datos8"][0]->fields["bimestre1"];
							$ruta.="&bimestre2=".$data["datos8"][0]->fields["bimestre2"];
							$ruta.="&bimestre3=".$data["datos8"][0]->fields["bimestre3"];
							$ruta.="&bimestre4=".$data["datos8"][0]->fields["bimestre4"];
							$ruta.="&bimestre5=".$data["datos8"][0]->fields["bimestre5"];
							$ruta.="&bimestre6=".$data["datos8"][0]->fields["bimestre6"];
							$ruta.="&semestre1=".$data["datos8"][0]->fields["semestre1"];
							$ruta.="&semestre2=".$data["datos8"][0]->fields["semestre2"];
							echo $ruta;	
						break;	
					case "13":
							$oCuentas=new Asientos();
							$data = $oCuentas->reporte_balance_general();
							$arrayDefecto = array();
							$oReporte= new Reporte();	
						
							$oReporte->CrearXmlAcumCuenta("balancegeneral0",$data["datos0"]);
							
							$oReporte->CrearXmlArr("balancegeneraltotal0",$data["datos0"][1]->fields);
							
							$oReporte->CrearXmlAcumCuenta("balancegeneral1",$data["datos1"]);
							
							$oReporte->CrearXmlArr("balancegeneraltotal1",$data["datos1"][0]->fields);
							
							$oReporte->CrearXmlArr("totalactivos",$data["datos0"][0]->fields);
							
							$oReporte->CrearXmlAcumCuenta("balancegeneral2",$data["datos2"]);
							if(is_array($data["datos2"][0]->fields))
							{
								$oReporte->CrearXmlArr("totalpasivos",$data["datos2"][0]->fields);
							}
							else
							{
								$oReporte->CrearXmlArr("totalpasivos",$arrayDefecto);
							}
							$oReporte->CrearXmlAcumCuenta("balancegeneral3",$data["datos3"]);
							$oReporte->CrearXmlArr("encabezado",getEnc());
							$oReporte->CrearXmlArr("resejercicio",$data["datos4"]);
							$oReporte->NomRep="balance_general";
							$ruta = $oReporte->MostrarReporte();
							$ruta.="&resejetr1=".$data["datos4"]["montri1"];
							$ruta.="&resejetr2=".$data["datos4"]["montri2"];
							$ruta.="&resejetr3=".$data["datos4"]["montri3"];
							$ruta.="&resejetr4=".$data["datos4"]["montri4"];
							$ruta.="&montoresacum=".$data["datos4"]["anesresult"];
							$ruta.="&montoresejer=".$data["datos4"]["anest"];
							$ruta.="&totaltrimestre1=".$data["datos5"]["totaltrimestre1"];
							$ruta.="&totaltrimestre2=".$data["datos5"]["totaltrimestre2"];
							$ruta.="&totaltrimestre3=".$data["datos5"]["totaltrimestre3"];
							$ruta.="&totaltrimestre4=".$data["datos5"]["totaltrimestre4"];
							
							
						//	ver($data["datos5"]);
							
							echo $ruta;
							break;	
					case "14":
							$oCuentas=new planIngreso();
							$oCuentaGas = new intGastosDao();
							$data = $oCuentas->reporte_cuenta_ahorro_inversion();
							$oReporte= new Reporte();
							$ingCor = $oCuentas->Leertotingcorrientes();
							$gasCor = $oCuentaGas->Leertotgascorrientes();
							$ingCap = $oCuentas->Leertotingcapital();
							$gasCap = $oCuentaGas->Leertotgascapital();
							$resFinIng=$oCuentas->LeerResFinIng();
							$resFinGas = $oCuentaGas->LeerResFinGas();
							
							$totgascorrientes = $ingCor - $gasCor; 
							$totgascapital = ($totgascorrientes+$ingCap) - $gasCap;
							$ingCap+=$totgascorrientes;
							
							$oReporte->CrearXmlAcumCuenta("caif0",$data["datos0"]);
							
							$oReporte->CrearXmlAcumCuenta("caif1",$data["datos1"]);
							
							$oReporte->CrearXmlAcumCuenta("caif3",$data["datos3"]);
							
						//	ver($data["datos2"]);
							$oReporte->CrearXmlAcumCuenta("caif2",$data["datos2"]);
							$oReporte->CrearXmlAcumCuenta("caif4",$data["datos4"]);
							
							$oReporte->CrearXmlAcumCuenta("caif5",$data["datos5"]);
							
							$oReporte->CrearXmlAcumCuenta("caif6",$data["datos6"]);
							$oReporte->CrearXmlAcumCuenta("caif7",$data["datos7"]);
							$oReporte->CrearXmlArr("encabezado",getEnc());
							$oReporte->NomRep="cuenta_ahorro_inversion";
							$ruta = $oReporte->MostrarReporte();
							$ruta.="&totgascorrientes=".$totgascorrientes;
							$ruta.="&totgastoscorrientes=".$gasCor;
							$ruta.="&totingcorrientes=".$ingCor;
							$ruta.="&totalgascapital=".$totgascapital;
							$ruta.="&totgastoscapital=".$gasCap;
							$ruta.="&totingcapital=".$ingCap;
							$ruta.="&resfining=".$resFinIng;
							$ruta.="&resfingas=".$resFinGas;
							$ruta.="&anpre=".$_SESSION["ano_presupuesto"];
							//die();
							echo $ruta;		
					break;	
					
					case "12":
							$oCuentas=new planIngreso();
							$oCuentas->nivel = $ArJson->nivel;
							$data = $oCuentas->reporte_presupuesto_de_caja2();
							$oReporte= new Reporte();
						//	var_dump($data["datos1"]);
						//	die();
							$oReporte->CrearXmlAcumCuenta("presupuestocajai",$data["datastorei"]);
							//var_dump($data["datos8"]);
						
							$oReporte->CrearXmlAcumCuenta("presupuestocaja0",$data["datos0"]);
							
							$oReporte->CrearXmlAcumCuenta("presupuestocaja1",$data["datos1"]);
								//die();
							$oReporte->CrearXmlAcumCuenta("presupuestocaja2",$data["datos2"]);
							//die();
							$oReporte->CrearXmlAcumCuenta("presupuestocaja3",$data["datos3"]);
							
							$oReporte->CrearXmlAcumCuenta("presupuestocaja4",$data["datos4"]);
							
							$oReporte->CrearXmlAcumCuenta("presupuestocaja5",$data["datos5"]);
							
							$oReporte->CrearXmlAcumCuenta("presupuestocaja6",$data["datos6"]);
						
							$oReporte->CrearXmlAcumCuenta("presupuestocaja7",$data["datos7"]);
						
							$oReporte->CrearXmlAcumCuenta("presupuestocaja8",$data["datos8"]);
							$Sumadearreglostotal = array_merge($data["datos0"][0]->fields,$data["datos6"][0]->fields);
							
							$oReporte->CrearXmlArr("presupuestocajatotal",$Sumadearreglostotal);
							$oReporte->CrearXmlArr("encabezado",getEnc());
							//var_dump($Sumadearreglostotal);
							//die();

							switch($ArJson->frecuencia)
							{	
								case "Trimestral":
									$oReporte->NomRep="presupuesto_de_caja";
								break;
								case "Mensual":
									$oReporte->NomRep="presupuesto_de_cajamensual";
								break;
								case "Bimensual":
									$oReporte->NomRep="presupuesto_de_cajabimensual";
								break;
								case "Semestral":
									$oReporte->NomRep="presupuesto_de_cajasemestral";
								break;
								default:
									"No disponible";
								break;
							}	
							
							//die();
		
							$ruta = $oReporte->MostrarReporte();
							$ruta.="&montoinianoest=".$data["datastorei"][0]->fields["saldoinicialpasin"];
							$ruta.="&montoinitri1=".$data["datastorei"][0]->fields["saldoinicialtri1"];
							echo $ruta;		
							
						/*
						$oEmpresa=new Empresa();
						$datosCab = $oEmpresa->LeerDatosCabRep();
						$data = $oEmpresa->LeerDatosGenerales();
						$oReporte= new Reporte();
						$oReporte->CrearXml("InfGeneral",$data);
						$oReporte->CrearXml("tituloEmpresa",$datosCab);
						$oReporte->NomRep="identificacion_ente";
						$ruta = $oReporte->MostrarReporte();
						*/
						//echo $ruta;
						break;	
					case "16":
						$oEmpresa = new Empresa();
						$datosCab = $oEmpresa->LeerDatosCabRep();
						$oPlan = new planIngreso();
						$arrCuentas = $oPlan->LeerTransferencia();
						$oReporte->CrearXml("tran1",$arrCuentas[0]);
						$oReporte->CrearXml("tran2",$arrCuentas[1]);
						$oReporte->CrearXml("tran3",$arrCuentas[2]);
						$oReporte->CrearXml("tran4",$arrCuentas[3]);
						$oReporte->CrearXml("tranTotal",$arrCuentas[4]);
						$oReporte->CrearXmlArr("encabezado",getEnc());
						$oReporte->NomRep="relacion_transferencias_recibir_sector_publico";
						$ruta = $oReporte->MostrarReporte();
						echo $ruta;
						break;	
					case "9":
						$oEmpresa = new Empresa();
						$datosCab = $oEmpresa->LeerDatosCabRep();	
						$oPlan = new planIngreso();
						$oPlan->nivel = $ArJson->nivel;
						$data = $oPlan->repingfuentes_finan();
						//ver($data["cuentas"]);
						$oReporte->CrearXmlAcumCuenta("preing",$data["cuentas"]);
						$oReporte->CrearXml("preingtot",$data["datos8"]);
						$oReporte->CrearXmlArr("encabezado",getEnc());
						
						switch($ArJson->frecuencia)
						{	
							case "Trimestral":
								$oReporte->NomRep="presupuesto_ingresos_y_fuentes_financieras";
							break;
							case "Mensual":
								 $oReporte->NomRep="presupuesto_ingresos_y_fuentes_financierasmensual";
							break;
							case "Bimensual":
								$oReporte->NomRep="presupuesto_ingresos_y_fuentes_financierasbimensual";
							break;
							case "Semestral":
									$oReporte->NomRep="presupuesto_ingresos_y_fuentes_financierasemestral";
							break;
								default:
									"No disponible";
							break;
						}	
						
							$ruta = $oReporte->MostrarReporte();
							$ruta.="&montogastoest=".$data["datos8"]->fields["anestimado"];
							$ruta.="&montogastoreal=".$data["datos8"]->fields["anreal"];
							$ruta.="&montogastri1=".$data["datos8"]->fields["trimestre1"];
							$ruta.="&montogastri2=".$data["datos8"]->fields["trimestre2"];
							$ruta.="&montogastri3=".$data["datos8"]->fields["trimestre3"];
							$ruta.="&montogastri4=".$data["datos8"]->fields["trimestre4"];
							$ruta.="&enerop=".$data["datos8"]->fields["enero"];
							$ruta.="&febrerop=".$data["datos8"]->fields["febrero"];
							$ruta.="&marzop=".$data["datos8"]->fields["marzo"];
							$ruta.="&abrilp=".$data["datos8"]->fields["abril"];
							$ruta.="&mayop=".$data["datos8"]->fields["mayo"];
							$ruta.="&juniop=".$data["datos8"]->fields["junio"];
							$ruta.="&juliop=".$data["datos8"]->fields["julio"];
							$ruta.="&agostop=".$data["datos8"]->fields["agosto"];
							$ruta.="&septiembrep=".$data["datos8"]->fields["septiembre"];
							$ruta.="&octubrep=".$data["datos8"]->fields["octubre"];
							$ruta.="&noviembrep=".$data["datos8"]->fields["noviembre"];
							$ruta.="&diciembrep=".$data["datos8"]->fields["diciembre"];
							$ruta.="&bimestre1p=".$data["datos8"]->fields["bimestre1"];
							$ruta.="&bimestre2p=".$data["datos8"]->fields["bimestre2"];
							$ruta.="&bimestre3p=".$data["datos8"]->fields["bimestre3"];
							$ruta.="&bimestre4p=".$data["datos8"]->fields["bimestre4"];
							$ruta.="&bimestre5p=".$data["datos8"]->fields["bimestre5"];
							$ruta.="&bimestre6p=".$data["datos8"]->fields["bimestre6"];
							$ruta.="&semestre1=".$data["datos8"]->fields["semestre1"];
							$ruta.="&semestre1p=".$data["datos8"]->fields["semestre1"];
							$ruta.="&semestre2=".$data["datos8"]->fields["semestre4"];
							$ruta.="&semestre2p=".$data["datos8"]->fields["semestre4"];
						
							echo $ruta;								
					//	var_dump($arrCuentas);
					//	die();
					break;
					case "4":
						$oEmpresa = new Empresa();
						$datosCab = $oEmpresa->LeerDatosCabRep();	
						$oGastos = new intGastosDao();
					  	$data = $oGastos->LeerCuentasAgrupadas();
					  	$oReporte->CrearXml("CrePar",$data);
						$oReporte->CrearXmlArr("encabezado",getEnc());
						$oReporte->NomRep="resumen_creditosxpartida";
						$ruta = $oReporte->MostrarReporte();
						echo $ruta;					  	
					break;					
					case "17":
						$oEmpresa = new Empresa();
						$datosCab = $oEmpresa->LeerDatosCabRep();	
						$oGastos = new intGastosDao();
					  	$arrCuentas = $oGastos->leerTransfGastos();
					  //var_dump($arrCuentas);
					 // die();
					  	$oReporte->CrearXml("trangasto1",$arrCuentas[0]);
						$oReporte->CrearXml("trangasto2",$arrCuentas[1]);
						$oReporte->CrearXml("trangasto3",$arrCuentas[2]);
						$oReporte->CrearXml("trangasto4",$arrCuentas[3]);
						$oReporte->CrearXml("trangasto5",$arrCuentas[4]);
						$oReporte->CrearXml("tranTotalGasto",$arrCuentas[5]);
						$oReporte->CrearXmlArr("encabezado",getEnc());
					    $oReporte->NomRep="relacion_transferencias_otorgar_sector_publico";
						$ruta = $oReporte->MostrarReporte();
						echo $ruta;  	
					break;
					case "18":
						$oGastos = new intGastosDao();
						$oEmpresa = new Empresa();
						$datosCab = $oEmpresa->LeerDatosCabRep();												
					  	$arrCuentas = $oGastos->leerGastosPrivado();
					  //var_dump($arrCuentas);
					 // die();					 	
					  	$oReporte->CrearXml("gasto1",$arrCuentas[0]);
						$oReporte->CrearXml("gasto2",$arrCuentas[1]);
						$oReporte->CrearXml("gasto3",$arrCuentas[2]);
						$oReporte->CrearXml("gasto4",$arrCuentas[3]);
						$oReporte->CrearXml("total",$arrCuentas[4]);
						$oReporte->CrearXmlArr("encabezado",getEnc());
						$oReporte->NomRep="relacion_transferencias_otorgar_sector_privado";
						$ruta = $oReporte->MostrarReporte();
						echo $ruta;  	
					break;

					case "10":
					
						$oEmpresa = new Empresa();
						$datosCab = $oEmpresa->LeerDatosCabRep();
						$oGastos = new intGastosDao();
						$oGastos->nivel = $ArJson->nivel;
						$datos = $oGastos->repgastos_aplic();
						//var_dump($datos);
						//die();
						$oReporte->CrearXmlAcumCuenta("PreGastos",$datos["cuentas"]);
						$oReporte->CrearXml("PreGastosTot",$datos["totales"]);
						$oReporte->CrearXmlArr("encabezado",getEnc());
							switch($ArJson->frecuencia)
							{	
								case "Trimestral":
									$oReporte->NomRep="presupuesto_gastos_y_aplicaciones";
								break;
								case "Mensual":
									$oReporte->NomRep="presupuesto_gastos_y_aplicacionesmensual";
								break;
								case "Bimensual":
									$oReporte->NomRep="presupuesto_gastos_y_aplicacionesbimensual";
								break;
								case "Semestral":
									$oReporte->NomRep="presupuesto_gastos_y_aplicacionesemestral";
								break;
								default:
									"No disponible";
								break;
							}	
			
					//	die();
						$ruta = $oReporte->MostrarReporte();
						echo $ruta;				
					break;	
					
					case "3":
						
					$oInte=new IntegracionPre();
					$Rs  = $oInte->LeerTodosCatNivel1();
					$p=0;
					$s=0;
					$r=0;
					$Arrs=array();
					$Arrs2=array();
					while($record=$Rs->FetchRow())
					{
							$ocuentastot=new intGastosDao();
							$ocuentastot->estructura=$record["codestpro1"];
							for($i=1;$i<=11;$i++)
							{
							
								if($i<10)
								{
									$ocuentastot->grupo="40{$i}";
									$Monto=$ocuentastot->Leertotalgrupo();
									if($ArrsTotal["monto40{$i}"]>0)
									{
										$ArrsTotal["monto40{$i}"]+=$Monto;
									}
									else
									{
										$ArrsTotal["monto40{$i}"]=$Monto;	
									}
								}
								else
								{
									$ocuentastot->grupo="4{$i}";
									$Monto=$ocuentastot->Leertotalgrupo();
									if($ArrsTotal["monto4{$i}"]>0)
									{
										$ArrsTotal["monto4{$i}"]+=$Monto;
									}
									else
									{
										$ArrsTotal["monto4{$i}"]=$Monto;	
									}								
								}
							} 							
							
					
						if($record["estcla"]=='P')
						{
							$Arrs[$p]["codestructura"]=$record["codigo"];
							$Arrs[$p]["denestructura"]=$record["descripcion"];
							$ocuentas=new intGastosDao();
							$ocuentas->estructura=$record["codestpro1"];
							for($i=1;$i<=8;$i++)
							{
								$ocuentas->grupo="40{$i}";
								$Monto=$ocuentas->Leertotalgrupo();
								$Arrs[$p]["monto40{$i}"]=$Monto;
							} 							
							$p++;
						}
						else
						{
							$Arrs2[$s]["codestructura"]=$record["codigo"];
							$Arrs2[$s]["denestructura"]=$record["descripcion"];
							$ocuentas=new intGastosDao();
							$ocuentas->estructura=$record["codestpro1"];
							for($i=1;$i<=11;$i++)
							{
								if($i<10)
								{
									$ocuentas->grupo="40{$i}";
									$Monto=$ocuentas->Leertotalgrupo();
									$Arrs2[$s]["monto40{$i}"]=$Monto;
								}
								else
								{
									$ocuentas->grupo="4{$i}";
									$Monto=$ocuentas->Leertotalgrupo();
									$Arrs2[$s]["monto4{$i}"]=$Monto;
								}
							} 							
							$s++;
						}
					}

						$oReporte= new Reporte();
						$oEmpresa = new Empresa();
						$datosCab = $oEmpresa->LeerDatosCabRep();
						$oReporte->CrearXmlArr("encabezado",getEnc());
						$oReporte->CrearXmlArr2("consolidadoparegr",$Arrs);
						$oReporte->CrearXmlArr2("consolidadoparegr2",$Arrs2);
						$oReporte->CrearXmlArr("consolidadoparegrTotal",$ArrsTotal);
						
						$oReporte->NomRep="consolidado_partidas_egreso";
						$ruta = $oReporte->MostrarReporte();
						echo $ruta;	
					//var_dump($Arrs);
					break;
					
	
					
						//echo $ruta;				
					case "21":
						$oEmpresa = new Empresa();
						$datosCab = $oEmpresa->LeerDatosCabRep();
						
						for($i=0;$i<count($la_cuenta);$i++)
						{
							$la_cuenta="";
							$oCuenta= new PlancuentasDao();
							$oCuenta->sig_cuenta=$la_cuenta[$i];
							if($oCuenta->VerificarExistencia()==true)
							{
								$oCuenta= new intGastosDao();
								$oCuenta->ano_presupuesto=2009;
								$oCuenta->codemp='0001';
								$oCuenta->sig_cuenta=$la_cuenta[$i];
								$rs = $oCuenta->leerDatosReporte();
								
							}	
						}
						$oGastos = new intGastosDao();
						$arrCuentas = $oGastos->LeerCuentasxPartidas();
						//var_dump($arrCuentas[0]);
						//die();
						
						
						$oReporte->CrearXml("ConsProyPartidas",$arrCuentas[0]);
						$oReporte->CrearXml("ConsAccPartidas",$arrCuentas[1]);
						$oReporte->CrearXml("tituloEmpresa",$datosCab);
						$oReporte->NomRep="consolidado_partidas_egreso";
						$ruta = $oReporte->MostrarReporte();
						echo $ruta;					
					break;	
				}
			}	
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
			if($Indice==$IndiceD)
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