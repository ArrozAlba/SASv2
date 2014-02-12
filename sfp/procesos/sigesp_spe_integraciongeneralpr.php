<?php
require_once('../class_folder/dao/sigesp_sfp_plan_unico_caifDao.php');
require_once('../class_folder/dao/sigesp_sfp_plan_unico_reDao.php');
require_once('../class_folder/dao/sigesp_sfp_con_estplandao.php');
require_once('../class_folder/dao/sigesp_sfp_conversionDao.php');
require_once('../class_folder/dao/sigesp_sfp_plancuentasDao.php');
require_once('../class_folder/dao/sigesp_sfp_cuentascontDao.php');
require_once("../librerias/php/general/class_funciones_seguridad.php");
require_once('../librerias/php/general/funciones.php');
require_once('../librerias/php/general/CrearReporte.php');
require_once('../librerias/php/general/Json.php');
function getObj($tipo)
{
	global $ArJson;
	switch($tipo)
	{
		case 'sig_cuenta':
			$tipoCuenta['obj']=new planUnicoRe();
		break;
		case 'sc_cuenta':
			$tipoCuenta['obj']=new planContable();
		break;
		case 'codplacaif':
			$tipoCuenta['obj']=new planUnicoCaif();
			if($ArJson->criterio=='denominacion')
			{
				$ArJson->criterio='desplacaif';
			}			
		break;	
		case 'sig_cuentaint':
			$ArJson->oper='leercuentasint';
			$tipoCuenta['obj']=new conversionDao();
			if($ArJson->criterio=='sig_cuentaint')
			{
				$ArJson->criterio='sig_cuenta';
			}			
		break;	
	}
	return $tipoCuenta;
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
//	var_dump($ArJson->tipo);
//	die();
	$oConversion=new conversionDao();
	PasarDatos($oConversion,$ArJson);
	$Evento = $ArJson->oper;
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
	//	ver($oConversion);
	//		var_dump($oConversion);
	//		die();
			$Resp=$oConversion->Incluir();
			if($Resp=='1')
			{
				echo "|1";
			
			}
			else
			{
				echo "|{$Resp}";
				
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
			$Datos = $oCuenta['obj']->LeerPorCadena($ArJson->criterio,$ArJson->cadena);
			$ObjSon = GenerarJson2($Datos);
			echo $ObjSon;
			break;
		case 'buscartodos':
			$Datos = $oCuenta['obj']->LeerTodas();
			$ObjSon = GenerarJson2($Datos);
			echo $ObjSon;
			break;
		case 'reporte':
			$oReporte = new Reporte();
			$Data = $oConversion->LeerTodas();
			$oReporte->CrearXml('cuentasgeneral',$Data);
			$oReporte->NomRep="plancuentasgeneral";
			echo $oReporte->MostrarReporte();
			break;
		case 'catalogo':
			$Data = $oConversion->LeerTodos();
			$ObjSon = GenerarJson2($Data);
			echo $ObjSon;
			break;
		case 'catalogoplacuentas':
			$oPlancuenta = new PlancuentasDao();
			$Data = $oPlancuenta->LeerTodos();
			$ObjSon = GenerarJson2($Data);
			echo $ObjSon;
		break;
		case 'pasaregistro':
			if(count($ArJson->registros)>0)
			{
				foreach($ArJson->registros as $AuxReg)
				{
					$oPlancuenta = new PlancuentasDao();
					PasarDatos($oPlancuenta,$AuxReg);
					$oPlancuenta->Incluir();
				}
				echo "|1";
			}
		break;
		case 'leercuentasint':
			$Data = $oConversion->LeerTodasCuentas2($ArJson->criterio,$ArJson->cadena);
			$ObjSon = GenerarJson2($Data);
			echo $ObjSon;
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
						$Propiedad=strtolower($Propiedad);
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