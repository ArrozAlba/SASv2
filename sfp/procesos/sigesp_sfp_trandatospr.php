<?php
require_once('../class_folder/dao/sigesp_sfp_indicadorDao.php');
require_once('../class_folder/dao/sigesp_sfp_tipoindDao.php');
require_once('../class_folder/dao/sigesp_spe_metasDao.php');
require_once('../librerias/php/general/funciones.php');
require_once('../librerias/php/general/CrearReporte.php');
require_once('../librerias/php/general/Json.php');

if ($_POST['ObjSon']) 	
{
	$submit = str_replace("\\","",$_POST['ObjSon']);
	//$submit = utf8_decode($submit);
	$json = new Services_JSON;
	$ArJson = $json->decode($submit);
	$ounidad = new IndicadorDao();
	PasarDatos(&$ounidad,$ArJson,&$Evento);
	$Evento = $GLOBALS["oper"];
	switch ($Evento)
	{
		case 'incluir':
			//echo "{$ounidad->denfuefin}";
			if($ounidad->incluir())
			{
				echo "|1";
			
			}
			else
			{
				echo "|0";
			}
			break;
		case 'buscarcodigo':
			$cad = $ounidad->BuscarCodigo();
			$cad = AgregarUno($ounidad->BuscarCodigo());
			echo "|{$cad}";
			break;
		case 'catalogo':
			$Datos = $ounidad->LeerTodos();					
			$ObjSon = GenerarJson2($Datos);
			echo $ObjSon;	
			break;
			
		case 'actualizar':
			if($ounidad->Modificar())
			{
				echo "|1";
			}
			else
			{
				echo "|0";
			}
			break;
		case 'eliminar':
			if($ounidad->Eliminar())
			{
				echo "|1";
			}
			else
			{
				echo "|0";
			}
			break;	
		case 'buscarcadena':	
			$Datos = $ounidad->LeerPorCadena($GLOBALS["criterio"],$GLOBALS["cadena"]);
			$ObjSon = GenerarJson($Datos);
			echo $ObjSon;
			break;
		case 'leertipos':	
			$oTipo = new tipoIndicadorDao();
			$Datos = $oTipo->LeerTodos();
			$ObjSon = GenerarJson2($Datos);
			echo $ObjSon;
			break;	
		case 'comprobarformula':
			$total=0;
			foreach($ArJson->mesesformula as $obj)
			{
				foreach($obj as $mes=>$formula)
				{
					$formula = '$resultado='.str_replace("|@@@|","+",$formula).";";
					//ver($formula);
					$resp=eval($formula);
					if($resp===false)
					{
						echo "0";
						break;
					}
					else
					{
						$result[$mes]=$resultado;
						$total+=$resultado;
						//echo $resultado;
						//echo $total;
					}					
				}
			}
			
			$TextJso = array("raiz"=>$result,"total"=>$total);
			$TextJson = $json->encode($TextJso);
			echo $TextJson;
			break;		
		case 'leervariables':
			$oMeta = new metaDao();
			$Datos = $oMeta->LeerTodos();
			$ObjSon = GenerarJson2($Datos);
			echo $ObjSon;
			break;	
		case 'Reporte':
			$oReporte = new Reporte();
			$Data = $ounidad->LeerTodos();
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