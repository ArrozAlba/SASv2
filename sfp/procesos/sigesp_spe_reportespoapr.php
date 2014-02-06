<?php
require_once('../class_folder/dao/sigesp_sfp_reporteDao.php');
require_once('../class_folder/dao/sigesp_spe_inteprogDao.php');
require_once('../class_folder/dao/sigesp_spe_asientosDao.php');
require_once('../class_folder/dao/sigesp_sfp_conversionDao.php');
require_once('../class_folder/dao/sigesp_sfp_intGastosDao.php');
require_once('../class_folder/dao/sigesp_sfp_plancuentasDao.php');
require_once('../class_folder/dao/sigesp_sfp_empresaDao.php');
require_once('../class_folder/dao/sigesp_sfp_empresasDao.php');
require_once('../class_folder/dao/sigesp_sfp_planingresoDao.php');
require_once("../librerias/php/general/class_funciones_seguridad.php");
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
		case 'MostrarReporte':
		switch($ArJson->codreporte)
		{
					case "0001":		
					$oInte=new IntegracionPre();
					$Rs  = $oInte->LeerPlanIntegrados();
					$p=0;
					$s=0;
					$r=0;
					$Arrs=array();
					$Arrs2=array();
					while($record=$Rs->FetchRow())
					{
							$ocuentastot=new intGastosDao();
							$ocuentastot->codest1=$record["codest1"];
							$ocuentastot->codest2=$record["codest2"];
							$ocuentastot->codest3=$record["codest3"];
							$ocuentastot->codest4=$record["codest4"];
							$ocuentastot->codest5=$record["codest5"];
							$ArrsTotal[$p]["codigo"]=$record["codigo"];
							$ArrsTotal[$p]["denominacion"]=$record["denominacion"];
							for($i=1;$i<=11;$i++)
							{
								if($i<10)
								{
									$ocuentastot->grupo="40{$i}";
									$Monto=$ocuentastot->Leertotalplan();
									$ArrsTotal[$p]["monto40{$i}"]=$Monto;
								}
								else
								{
									$ocuentastot->grupo="4{$i}";
									$Monto=$ocuentastot->Leertotalplan();
									$ArrsTotal[$p]["monto40{$i}"]=$Monto;
								}
							} 											
/*			
							
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
*/
					$p++;
					}
					
						$oReporte= new Reporte();
						$oReporte->CrearXmlArr("encabezado",getEnc());
						$oReporte->CrearXmlArr2("consolidadopoacuentas",$ArrsTotal);
						$oReporte->NomRep="cosolidadoegresospoa";
						$ruta = $oReporte->MostrarReporte();
						echo $ruta;	
					//var_dump($Arrs);
					break;
					case '0002':
						$ocuentastot=new intGastosDao();
						$ocuentastot->codest1=$ArJson->codest1;
						$ocuentastot->codest2=$ArJson->codest2;
						$ocuentastot->codest3=$ArJson->codest3;
						$ocuentastot->codest4=$ArJson->codest4;
						$ocuentastot->codest5=$ArJson->codest5;
						$p=0;
						for($i=1;$i<=11;$i++)
						{
								if($i<10)
								{
									$ocuentastot->grupo="40{$i}";
									$Rs=$ocuentastot->Leergastosplan();
									$ArrsTotal[$p]=$Rs;
								}
								else
								{
									$ocuentastot->grupo="4{$i}";
									$Rs=$ocuentastot->Leergastosplan();
									$ArrsTotal[$p]=$Rs;
								}
								$p++;
						} 
						$ArrsTotalMetas = $ocuentastot->Leermetasplan();
						$ArrstotalIndi = $ocuentastot->Leerindiplan();	
						
						$oReporte= new Reporte();
						$oReporte->CrearXmlArr("encabezado",getEnc());
						$oReporte->CrearXmlAcumCuenta("progfisicofinanpoa",$ArrsTotal);
						$oReporte->CrearXml("progfisicofinanpoametas",$ArrsTotalMetas);
						$oReporte->CrearXml("progfisicofinanpoaiondi",$ArrstotalIndi);
						$oReporte->NomRep="resfisicofinanpoa";
						$ruta = $oReporte->MostrarReporte();
						$ruta.= "&codigoplan={$ArJson->codigoplan}";
						$ruta.= "&denplan={$ArJson->denplan}"; 
						echo $ruta;
					break;	
				}
				break;
				case 'leerplanes':
				
					$oInte=new IntegracionPre();
					$Rs  = $oInte->LeerPlanIntegrados();
					echo GenerarJson2($Rs);	
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