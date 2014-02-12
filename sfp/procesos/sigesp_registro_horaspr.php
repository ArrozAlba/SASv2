<?php
session_start();
require_once('../class_folder/dao/sigesp_registro_horasDao.php');
require_once('../class_folder/dao/sigesp_registro_clientesDao.php');
require_once('../librerias/php/general/funciones.php');
require_once('../librerias/php/general/CrearReporte.php');
require_once('../librerias/php/general/Json.php');
if ($_POST['ObjSon']) 	
{
	$submit = str_replace("\\","",$_POST['ObjSon']);	
	$json = new Services_JSON;
	$ArJson = $json->decode($submit);
	$oHoras = new registroHoras();
	PasarDatos(&$oHoras,$ArJson);	
	$Evento = $GLOBALS["oper"];
	//var_dump(uf_convertirdatetobd($oHoras->fecsys));
	//die();
	switch ($Evento)
	{
		case 'obtenersesion':
		 if(array_key_exists('codconsultor',$_SESSION) && array_key_exists('nomconsultor',$_SESSION))
		 {
			echo $_SESSION['codconsultor']."|".$_SESSION['nomconsultor'];
		 }
		 else
		 {
			echo "0|0";
		 }
		break;
		case 'incluir':
			$oHoras->fecreg= uf_convertirdatetobd($oHoras->fecreg);
			$oHoras->fecsys= uf_convertirdatetobd($oHoras->fecsys);
		//	var_dump($oHoras);
		//	die();
			if($oHoras->incluir())
			{
				echo "|1";
			
			}
			else
			{
				echo "|0";
			}
			break;
		case 'buscarcodigo':
			$cad = AgregarUno($ofuente->BuscarCodigo());
			echo "|{$cad}";
			break;
		case 'modificar':
			$oHoras->fecreg= uf_convertirdatetobd($oHoras->fecreg);
			$oHoras->fecsys= uf_convertirdatetobd($oHoras->fecsys);
		//	var_dump($oHoras);
		//	die();
			if($oHoras->Modificar())
			{
				echo "|1";
			
			}
			else
			{
				echo "|0";
			}
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
			//var_dump($ofuente->cod_fuenfin);
			//echo "{$ofuente->denfuefin}";
			//die();
			if($oPlaIn->Modificar())
			{
				echo "|1";
			}
			else
			{
				echo "|0";
			}
			break;
		case 'eliminar':
			$oHoras->fecreg= uf_convertirdatetobd($oHoras->fecreg);
		
			if($oHoras->Eliminar()=='1')
			{
				echo "|1";
			}
			else
			{
				echo "|0";
			}
			break;	
		case 'buscarcadena':
			$oCuentasIn = new planUnicoRe();
			$Datos = $oCuentasIn->LeerPorCadena($GLOBALS["criterio"],$GLOBALS["cadena"]);
			$ObjSon = GenerarJson2($Datos);
			echo $ObjSon;
			break;
		case 'imprimir':
			$oReporte = new Reporte();
			$oHoras->fecreg= uf_convertirdatetobd($oHoras->fecreg);
			$Data = $oHoras->LeerRegistrosClientes();
			if($Data->RecordCount()>0)
			{
				$oReporte->CrearXml('reportediario',$Data);
				$oReporte->NomRep="informe_diario";
				echo $oReporte->MostrarReporte();				
			}
			else
			{
				echo "|0";	
			}
		break;	
		case 'Catalogos':
		//echo "sdsd";
		//die();
			$oClientes= new clientes();
			if($oHoras->fecreg=='')
			{
				$oHoras->fecreg='1982-12-02';
			}
			$oHoras->fecreg= uf_convertirdatetobd($oHoras->fecreg);
			$Datos = $oClientes->LeerTodos();
			$DatosSer = $oHoras->LeerServicios('0000000029');
			$DatosMod = $oHoras->LeerModulos();
			$DatosReg = $oHoras->LeerRegistros();
			$can = $oHoras->LeerNumHorasDia();
			$DatosCliente = GenerarJson2($Datos);
			$DatosServicio = GenerarJson2($DatosSer);
			$DatosModulo = GenerarJson2($DatosMod);
			$DatosRegistro = GenerarJson2($DatosReg);
			//$Datos = $oPlaIn->LeerPlan();
			//$Registros = GenerarJson2($Datos);	
			echo $DatosCliente."|".$DatosServicio."|".$DatosModulo."|".$DatosRegistro."|".$can;
			break;	
		case 'buscarcadenaClie':
			$oClientes= new clientes();
			$Datos = $oClientes->LeerPorCadena($GLOBALS["criterio"],$GLOBALS["cadena"]);
			$ObjSon = GenerarJson2($Datos);
			echo $ObjSon;
			break;
		case 'buscarcadenaServ':
			$Datos = $oHoras->LeerPorCadenaSer($GLOBALS["criterio"],$GLOBALS["cadena"]);
			$ObjSon = GenerarJson2($Datos);
			echo $ObjSon;
			break;
		case 'buscarcadenaMod':
			$Datos = $oHoras->LeerPorCadenaMod($GLOBALS["criterio"],$GLOBALS["cadena"]);
			$ObjSon = GenerarJson2($Datos);
			echo $ObjSon;
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