<?php
require_once('../class_folder/dao/sigesp_spe_metasDao.php');
require_once('../class_folder/dao/sigesp_sfp_unimedidaDao.php');
require_once("../librerias/php/general/class_funciones_seguridad.php");
require_once('../librerias/php/general/funciones.php');
require_once('../librerias/php/general/CrearReporte.php');
require_once('../librerias/php/general/Json.php');

if ($_POST['ObjSon']) 	
{
 
	$submit = str_replace("\\","",$_POST['ObjSon']);
	//$submit = utf8_decode($submit);
	$json = new Services_JSON;
	$ArJson = $json->decode($submit);

	$ometa = new metaDao();
	PasarDatos(&$ometa,$ArJson,&$Evento);
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
		case 'incluir':
			if($ometa->incluir())
			{
				echo "|1";
			
			}
			else
			{
				echo "|0";
			}
			break;
		case 'buscarcodigo':
			$cad = $ometa->BuscarCodigo();
			if ($cad!='0001')
			{
				$cad = AgregarUno($ometa->BuscarCodigo());
			}
			echo "|{$cad}";
			break;
		case 'leerUnidades':
			$ounidad = new unimedidaDao();
			$Datos = $ounidad->LeerTodos();
			//var_dump($Datos);			
			$ObjSon = GenerarJson2($Datos);
			echo $ObjSon;			
		break;
		
		case 'catalogo':
			$Datos = $ometa->LeerTodos();
			$ObjSon = GenerarJson2($Datos);
			echo $ObjSon;	
			
			break;
			
		case 'actualizar':
			//var_dump($ometa->cod_fuenfin);
			//echo "{$ometa->denfuefin}";
			//die();
			if($ometa->Modificar())
			{
				echo "|1";
			}
			else
			{
				echo "|0";
			}
			break;
		case 'eliminar':
			if($ometa->Eliminar())
			{
				echo "|1";
			}
			else
			{
				echo "|0";
			}
			break;	
		case 'buscarcadena':			
			$Datos = $ometa->LeerPorCadena($GLOBALS["criterio"],$GLOBALS["cadena"]);
			//var_dump($Datos);
			//die();
			$ObjSon = GenerarJson2($Datos);
			echo $ObjSon;
			break;
		case 'Reporte':
			$oReporte = new Reporte();
			$Data = $ometa->LeerTodos();
			$oReporte->CrearXml('listafuente',$Data);
			$oReporte->NomRep="FuenteFin";
			echo $oReporte->MostrarReporte();

	}
}



function PasarDatos($ObjDao,$ObJson,$evento)
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
					echo $Propiedad;
					
		
					if(array_key_exists($Propiedad,$ArJson))	
					{	
						//echo $Propiedad;
						//die();
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