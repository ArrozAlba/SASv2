<?php
require_once('../class_folder/dao/sigesp_sfp_generalDao.php');
require_once('../class_folder/dao/sigesp_sfp_estAdminDao.php');
require_once('../class_folder/dao/sigesp_spe_muniDao.php');
require_once('../class_folder/dao/sigesp_spe_estprog4Dao.php');
require_once('../class_folder/dao/sigesp_spe_estprog5Dao.php');
require_once("../librerias/php/general/class_funciones_seguridad.php");
require_once('../class_folder/dao/sigesp_sfp_con_estplandao.php');
require_once('../librerias/php/general/funciones.php');
require_once('../librerias/php/general/CrearReporte.php');
require_once('../librerias/php/general/Json.php');
Function DatosNivel($nivel)
{
	global $Vars;
	switch($nivel)
	{
		
		case '1':
		$DatNivel['tabla']= "sigesp_pais";
		break;
		case '2':
		$DatNivel['tabla']= "sigesp_estados";
		$DatNivel['obj']= new estadosDao;
		$DatNivel['cond']= "codpai='{$GLOBALS['cod1']}'";
		break;
		case '3':
		$DatNivel['tabla']= "sigesp_municipio";
		$DatNivel['obj']= new muniDao;
		$DatNivel['cond']= "codpai='{$GLOBALS['cod1']}' and codest='{$GLOBALS['cod2']}'";
		break;
		case '4':
		$DatNivel['tabla']= "sigesp_parroquia";
		$DatNivel['obj']= new estprog4Dao;
		$DatNivel['cond']= "CODEST1='{$GLOBALS['cod1']}' and CODEST2='{$GLOBALS['cod2']}' and CODEST3='{$GLOBALS['cod3']}'";
		break;
		case '5':
		$DatNivel['tabla']= "sigesp_sector";
		$DatNivel['obj']= new estprog5Dao;
		$DatNivel['cond']= "CODEST1='{$GLOBALS['cod1']}' and CODEST2='{$GLOBALS['cod2']}' and CODEST3='{$GLOBALS['cod3']}' and CODEST4='{$GLOBALS['cod4']}'";
		break;
		case '6':
		$DatNivel['tabla']= "sigesp_manzana";
		$DatNivel['obj']= new estprog5Dao;
		$DatNivel['cond']= "CODEST1='{$GLOBALS['cod1']}' and CODEST2='{$GLOBALS['cod2']}' and CODEST3='{$GLOBALS['cod3']}' and CODEST4='{$GLOBALS['cod4']}'";
		break;
		case '7':
		$DatNivel['tabla']= "sigesp_parcela";
		$DatNivel['obj']= new estprog5Dao;
		$DatNivel['cond']= "CODEST1='{$GLOBALS['cod1']}' and CODEST2='{$GLOBALS['cod2']}' and CODEST3='{$GLOBALS['cod3']}' and CODEST4='{$GLOBALS['cod4']}'";
		break;
		
	}
	return $DatNivel;
}


if($_POST['ObjSon']) 	
{
	$submit = str_replace("\\","",$_POST['ObjSon']);
	//$submit = utf8_decode($submit);
	$json = new Services_JSON;
	$ArJson = $json->decode($submit);
	$ArObjetos = array();

	if($ArJson->datos)
	{
		$Evento = $ArJson->oper;
		for($j=0;$j<=count($ArJson->datos)-1;$j++)
		{
			$ArObjetos[$j] = new EstAdmin();
			PasarDatos(&$ArObjetos[$j],$ArJson->datos[$j]);	
			
		}
		
		
	}
	else
	{
		$oEst = new EstAdmin();
		PasarDatos(&$oEst,$ArJson);
		$Evento = $GLOBALS["oper"];
	}
		
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
			
			if($ofuente->incluir())
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
	
		case 'buscarcodigo':
			$cad = $ofuente->BuscarCodigo();
			if($cad!='0001')
			{
				$cad = AgregarUno($cad);	
			}
			echo "|{$cad}";
			break;
			
		case 'getSesion':
			$oNiveles= new ConfNivelDao();
			$oNiveles->tipo="EA";
			$Datos = $oNiveles->LeerTodos();
			$Cantidad = count($Datos);
			$Texto = GenerarJsonDeObjetos($Datos);
			echo $Cantidad."|".$Texto;
			break;	
		case 'catalogo':
			$Datos = $ofuente->LeerTodos();
			//var_dump($Datos);
			//$Registros = "|";
		//aqui se pasan los datos de un arreglo de objetos a un arreglo denfuefin arreglos de php	
			$obj = $Datos[0];
			if($Datos)
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
				$TextJson = json_encode($TextJso);
				echo $TextJson;
			}
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
		
			if($ArObjetos[0]->Eliminar())
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
		case 'catestpro':
			//var_dump($oEst->LeerPadre());
			//die();
			$TextJso = GenerarJson2($oEst->LeerPadre());
			echo $TextJso;	
		break;
			case 'filtrarEst':
			$TextJso = GenerarJson2($oEst->FiltrarEst());
			echo $TextJso;	
		break;
		case 'incluirestpro':
						foreach($ArObjetos as $objeto)
						{
							if($objeto->incluir())
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
				
							echo $TextJso;	
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
	//return $DatosFuncion;
}

//genera un objeto json a partir de un objeto ya existente 
function GenerarJson($Datos)
{
	global $json,$ArJson;
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
						
						$arRegistros[$i][$Propiedad] = utf8_encode($Datos[$i]->$Propiedad);
						$i++;
					}
				
				}
		
					
			}
			//aqui se pasa el arreglo de arreglos a un objeto json
			$TextJso = array("raiz"=>$arRegistros);
			$TextJson = json_encode($TextJso);
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