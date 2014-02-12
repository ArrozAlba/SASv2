<?php
session_start();
require_once('../class_folder/dao/sigesp_sfp_empresaDao.php');
require_once('../class_folder/dao/sigesp_sfp_empresasDao.php');
require_once('../librerias/php/general/funciones.php');
require_once("../librerias/php/general/class_funciones_seguridad.php");
require_once('../librerias/php/general/CrearReporte.php');
require_once('../librerias/php/general/Json.php');
if ($_POST['ObjSon']) 		
{
	$submit = str_replace("\\","",$_POST['ObjSon']);
	$json = new Services_JSON;
	$ArJson = $json->decode($submit);
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
		case 'leerempresa':
			$oEmpresas = new empresas();
			$Datos=$oEmpresas->LeerTodos();
			$ObjSon = GenerarJson2($Datos);
			echo $ObjSon;
			break;
		case 'grabarsesion':
			if($ArJson->empresa!="" && $ArJson->ano_presupuesto!="")
			{
				$oEmpresas = new empresas();
				$oEmpresas->codemp=	$ArJson->empresa;
				$rs = $oEmpresas->obtenerEncReporte();
				$_SESSION["codemp"]=$ArJson->empresa;
				$_SESSION["ano_presupuesto"]=$ArJson->ano_presupuesto;
				$_SESSION["nombre"]=$rs->fields["nombre"];
				$_SESSION["organoad"]=$rs->fields["nomorgads"];
				echo "1";
			}
			else
			{
				echo "0";
			}
			break;
		case 'catalogoreporte':
			$Data = $orepor->LeerTodos();
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