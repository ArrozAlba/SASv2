<?php
//require_once("../class_folder/dao/class_funciones.php");
require_once('../class_folder/dao/class_sigesp_int.php');
require_once('../class_folder/dao/class_sigesp_int_scg.php');
require_once("../class_folder/dao/class_fecha.php");
require_once("../librerias/php/general/class_funciones_seguridad.php");
require_once("../class_folder/dao/sigesp_sfp_cuentascontDao.php");
//require_once("../class_folder/dao/class_mensajes.php");
//require_once("../class_folder/dao/class_sql.php");
//require_once("../class_folder/dao/class_datastore.php");
require_once('../class_folder/dao/sigesp_sfp_intGastosFuenDao.php');
require_once('../class_folder/dao/sigesp_sfp_intUb1Dao.php');
require_once('../class_folder/dao/sigesp_sfp_intUb2Dao.php');
require_once('../class_folder/dao/sigesp_sfp_intUb3Dao.php');
require_once('../class_folder/dao/sigesp_sfp_intUb4Dao.php');
require_once('../class_folder/dao/sigesp_sfp_intUb5Dao.php');
require_once('../class_folder/dao/sigesp_sfp_intProbDao.php');
require_once('../class_folder/dao/sigesp_sfp_intGastosDao.php');
require_once('../class_folder/dao/sigesp_sfp_intMetasDao.php');
require_once('../class_folder/dao/sigesp_sfp_planingresoDao.php');
require_once('../class_folder/dao/sigesp_spe_inteprogDao.php');
require_once('../class_folder/dao/sigesp_sfp_cuentasgastosDao.php');
require_once('../class_folder/dao/sigesp_sfp_conversionDao.php');
require_once("../class_folder/dao/sigesp_sfp_cuentascontDao.php");
require_once('../class_folder/dao/sigesp_sfp_plancuentasDao.php');
require_once('../class_folder/dao/sigesp_spe_inteprogDao.php');
require_once('../class_folder/dao/sigesp_sfp_plan_unico_reDao.php');
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
	if($ArJson->ArrEst)
	{
		$oInte = array();
		for($j=0;$j<count($ArJson->ArrEst);$j++)
		{
			$oInte[$j] = new IntegracionPre();
			PasarDatos(&$oInte[$j],$ArJson->ArrEst[$j]);
		}
	}
	
	$Evento = $ArJson->oper;	
	if($ArJson->oper=='actualizarInt')
	{
		$oInte = new IntegracionPre();
		PasarDatos(&$oInte,$ArJson);
		if($ArJson->DatosAd)
		{
			for($j=0;$j<count($ArJson->DatosAd);$j++)
			{
				$oInte->Ads[$j] = new intUniAdDao();
				PasarDatos(&$oInte->Ads[$j],$ArJson->DatosAd[$j]);	
			}
		}
	
		
		if($ArJson->DatosUb)
		{
			for($j=0;$j<count($ArJson->DatosUb);$j++)
			{
				$oInte->Ubs[$j] = ObtenerNivelUb($ArJson->NivelUb);
				PasarDatos(&$oInte->Ubs[$j],$ArJson->DatosUb[$j]);	
			}
		}
   }
	
   	if($ArJson->oper=='buscaruna' || $ArJson->oper=='LeerTodos' || $ArJson->oper=='EliminarInt')
	{
		$oInte = new IntegracionPre();
		PasarDatos(&$oInte,$ArJson);	
	}
	
	if($ArJson->oper=='eliminarUnis')
	{
	
		$oInte = new IntegracionPre();
		PasarDatos(&$oInte,$ArJson);
		if($ArJson->DatosAd)
		{
			for($j=0;$j<count($ArJson->DatosAd);$j++)
			{
				$oInte->Ads[$j] = new intUniAdDao();
				PasarDatos(&$oInte->Ads[$j],$ArJson->DatosAd[$j]);	
			}
		}
	
   }
   if($ArJson->oper=='eliminarUbs')
	{
		$oInte = new IntegracionPre();
		if($ArJson->DatosUb)
		{
			for($j=0;$j<count($ArJson->DatosUb);$j++)
			{
				$oInte->Ubs[$j] = ObtenerNivelUb($ArJson->NivelUb);
				PasarDatos(&$oInte->Ubs[$j],$ArJson->DatosUb[$j]);	
			}
		}
	}
	if($ArJson->DatosGas)
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
		//var_dump($ArJson->DatosGas[2]->fuentes);
		//die();
	}
	


	
	
	//if($ArJson->DatosFuente)
//	{
//		
//		for($j=0;$j<=count($ArJson->datos)-1;$j++)
//		{
//			$ArObjetos[$j] = new ConfNivelDao();
//			PasarDatos($ArObjetos[$j],$ArJson->datos[$j]);	
//		}
//		$Evento = $ArJson->oper;
//		
//	}
//

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
		case 'incluirInt':
			$codigo=IntegracionPre::ObtenerCodigo();
			IntegracionPre::IniciarTran();
			for($i=0;$i<count($oInte);$i++)
			{
				$oInte[$i]->codinte=$codigo;			
				$Resp = $oInte[$i]->IncluirTodos();
				$codigo++;
			}
			$resp= IntegracionPre::CompletarTran();	
			if($resp=="1")
			{
				$cad = $oInte[0]->LeerUnoPlan();
				$ObjSon = GenerarJson2($cad);	
				echo "{$ObjSon}";				
			}
			else
			{
				echo "0";
			}
			break;
		case 'actualizarInt':
			$Resp=$oInte->ActualizarTodos();
			if($Resp=="1")
			{			
					echo "|1";
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
			$rsAsientos = $oInte->LeerAsientos();
			$ObjSonVar = GenerarJson2($rsAsientos["variacion"]);
			$ObjSonCaif = GenerarJson2($rsAsientos["caif"]);	
			echo "{$ObjSonVar}|{$ObjSonCaif}";	
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
		/*case 'CatMetas':
			$ometa = new metaDao();
			$Datos = $ometa->LeerPorCadena($GLOBALS["criterio"],$GLOBALS["cadena"]);
			//var_dump($Datos);
			//die();
			$ObjSon = GenerarJson2($Datos);
			echo $ObjSon;
		break;	
		case 'CatProb':
			$oprob = new problemaDao();
			$Datos = $oprob->LeerPorCadena($GLOBALS["criterio"],$GLOBALS["cadena"]);
			$ObjSon = GenerarJson2($Datos);
			echo $ObjSon;
		break;	*/
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
				$ObjSonProg = GenerarJson2($nivelProg);				
				$ObjSonPlan = GenerarJson2($nivelPlan);
				echo "{$ObjSon}|{$ObjSonProg}|{$ObjSonPlan}|$ObjSonUbs|$ObjSonUnis|$rsUbs[1]";
			}
			else
			{
				echo "|0";
			}
			break;	
		case 'buscardetalles':	
			//echo "{$ObjSon}|{$ObjSonProg}|{$ObjSonPlan}";
			break;	
		case "LeerTodos":
		//echo "sdff";
		//die();
				$datos  = $oInte->LeerTodos();
				$reporte1= new Reporte();
				$reporte1->CrearXml("planpresupuesto",$datos);
				$reporte1->NomRep="planpresupuesto";
				echo $reporte1->MostrarReporte();
				//$reporte->
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
		case 'EliminarInt':
			IntegracionPre::IniciarTran();
			$oInte->Eliminar();
			if(IntegracionPre::CompletarTran())
			{
				echo "1";
			}
			else
			{
				echo "0";
			}
		break;						
		case 'leerecursos':
			$oIngresos = new planIngreso();
			$Datos = $oIngresos->LeerPlan();
			$ObjSon = GenerarJson2($Datos);	
			echo $ObjSon;		
			break;	
		case 'eliminarUnis':
			for($j=0;$j<count($ArJson->DatosAd);$j++)
			{
				$Res = $oInte->eliminarUnidad($oInte->Ads[$j]);
			}
			echo  "|{$Res}";
			break;
		case 'eliminarUbs':
			for($j=0;$j<count($ArJson->DatosUb);$j++)
			{
				$Res = $oInte->eliminarUbicacion($oInte->Ubs[$j]);
			}
			echo  "|{$Res}";
			break;
		case 'eliminarProbs':
			$Res = $oInte->eliminarProblemas($oInte->Probs[0]);
			echo  "|{$Res}";
			break;
		case 'eliminarMetas':
			$Res = $oInte->eliminarMetas($oInte->Metas[0]);
			echo  "|{$Res}";
			break;
		case 'eliminarCuentas':
			$Res = $oInte->eliminarCuentas($oInte->Gastos[0]);
			echo  "|{$Res}";
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