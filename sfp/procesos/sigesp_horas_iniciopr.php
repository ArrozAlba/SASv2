<?php
require_once('../class_folder/dao/sigesp_registro_horasDao.php');
require_once('../class_folder/dao/sigesp_usuariosDao.php');
require_once('../class_folder/dao/sigesp_registro_clientesDao.php');
require_once('../librerias/php/general/funciones.php');
require_once('../librerias/php/general/CrearReporte.php');
require_once('../librerias/php/general/Json.php');
session_start();
if ($_POST['ObjSon']) 	
{
	$submit = str_replace("\\","",$_POST['ObjSon']);	
	$json = new Services_JSON;
	$ArJson = $json->decode($submit);
	$oUsu = new Usuario();
	PasarDato(&$oUsu,$ArJson);	
	$Evento = $GLOBALS["oper"];
	switch ($Evento)
	{
	
		case 'iniciarsesion':
			if($oUsu->Validar(&$Rs)=='1')
			{
				session_register('codconsultor');
				session_register('nomconsultor');
				$reg = $Rs->FetchRow();
				$_SESSION['codconsultor']=$reg[0];
				$_SESSION['nomconsultor']=$reg[1];
				echo "|1";
			}
			else
			{
				echo "|0";
			}
			break;	
	
	}
}
function PasarDato($ObjDao,$ObJson)
{

	if(is_object($ObjDao))
	{	
			$ArDao = $ObjDao->getAttributeNames();
			foreach($ObjDao as $IndiceD =>$valorD)
			{
				foreach($ObJson as $Indice =>$valor)
				{
					$Indice = strtolower($Indice);
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
						
						$arRegistros[$i]["propiedad".$Propiedad]= $Datos[$i]->$Propiedad;
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

				//	if(!is_numeric($Propiedad))
				//	{
						
						$arRegistros[$i]["propiedad".$Propiedad]= utf8_encode($valor);
				//	}		
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