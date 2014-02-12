<?php
require_once('../class_folder/dao/sigesp_sfp_generalDao.php');
require_once('../class_folder/dao/sigesp_spe_estprog1Dao.php');
require_once('../class_folder/dao/sigesp_spe_estprog2Dao.php');
require_once('../class_folder/dao/sigesp_spe_estprog3Dao.php');
require_once("../librerias/php/general/class_funciones_seguridad.php");
require_once('../class_folder/dao/sigesp_spe_estprog4Dao.php');
require_once('../class_folder/dao/sigesp_spe_estprog5Dao.php');
require_once('../class_folder/dao/sigesp_sfp_con_estplandao.php');
require_once('../librerias/php/general/funciones.php');
require_once('../librerias/php/general/CrearReporte.php');
require_once('../librerias/php/general/Json.php');
Function DatosNivel($nivel)
{
	switch($nivel)
	{
		case '1':
		$DatNivel['tabla']= "spe_estpro1";
		$DatNivel['codigos']=array('codest2','codest3','codest4','codest5');
		$DatNivel['obj']= new estprog1Dao;
		$DatNivel['obj2']= new estprog2Dao;
		$DatNivel['obj3']= new estprog3Dao;
		$DatNivel['obj4']= new estprog4Dao;
		$DatNivel['obj5']= new estprog5Dao;
		$DatNivel['cond']= "";
		break;
		case '2':
		$DatNivel['tabla']= "spe_estpro2";
		$DatNivel['obj']= new estprog2Dao;
		$DatNivel['codigos']=array('codest3','codest4','codest5');
		$DatNivel['obj3']= new estprog3Dao;
		$DatNivel['obj4']= new estprog4Dao;
		$DatNivel['obj5']= new estprog5Dao;
		$DatNivel['cond']= "codest1='{$GLOBALS['cod1']}'";
		break;
		case '3':
		$DatNivel['tabla']= "spe_estpro3";
		$DatNivel['obj']= new estprog3Dao;
		$DatNivel['codigos']=array('codest4','codest5');
		$DatNivel['obj4']= new estprog4Dao;
		$DatNivel['obj5']= new estprog5Dao;
		$DatNivel['cond']= "codest1='{$GLOBALS['cod1']}' and codest2='{$GLOBALS['cod2']}'";
		break;
		case '4':
		$DatNivel['tabla']= "spe_estpro4";
		$DatNivel['codigos']=array('codest5');
		$DatNivel['obj']= new estprog4Dao;
		$DatNivel['obj5']= new estprog5Dao;
		$DatNivel['cond']= "codest1='{$GLOBALS['cod1']}' and codest2='{$GLOBALS['cod2']}' and codest3='{$GLOBALS['cod3']}'";
		break;
		case '5':
		$DatNivel['tabla']= "spe_estpro5";
		$DatNivel['obj']= new estprog5Dao;
		$DatNivel['cond']= "codest1='{$GLOBALS['cod1']}' and codest2='{$GLOBALS['cod2']}' and codest3='{$GLOBALS['cod3']}' and codest4='{$GLOBALS['cod4']}'";
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
	$Tope=0;
//	var_dump($ArJson);
//	die();
	if($ArJson->datos)
	{
		$Evento = $ArJson->oper;
		$Nivel= DatosNivel($ArJson->numest);
		//var_dump($Nivel);
		//die();
		for($j=0;$j<=count($ArJson->datos)-1;$j++)
		{
			$ArObjetos[$j] = new generalDao($Nivel['tabla']);
			PasarDatos(&$ArObjetos[$j],$ArJson->datos[$j]);	
			
		}
	}
	else
	{
			
		PasarDatos(&$ofuente,$ArJson);
		$Evento = $GLOBALS["oper"];
		$Nivel= DatosNivel($numest);
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
		case 'incluirUltimo':
			//echo "{$ofuente->denfuefin}";
			//Obtener Ultimo nivel de las presupuestaria'.
			//evaluar si se esta en el ultimonievel
			//instanciar las demas por lo menos si se esta en el primero instanciar del del dos al 5
			//cada ves que se instacien irlos cargando con la data que se tiene y las que faltan llenarlo con 0 e incluirlos.
			foreach($ArObjetos as $ofuente)
			{
				if($ofuente->incluir()=="1")
				{
					$Tope=$ArJson->numest+1;	
					for($i=$Tope;$i<=5;$i++)
					{
						$ObjNuevo = DatosNivel($i);
						$p=$i+1;
						//var_dump($ObjNuevo["obj"]);
						//die();
						PasarDatosProg(&$ObjNuevo["obj"],$ofuente);	
					//	var_dump($ObjNuevo["obj"]);
					//	die();
						$ObjNuevo["obj"]->Incluir();
						
					}				
					echo "|1";
				}
				else
				{
					echo "|0";
				}
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
			$oNiveles->tipo="PL";
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
		
		//	echo "aqui se elimina";
		//	die();
			if($ArObjetos[0]->Eliminar())
			{
				echo "|1";
			}
			else
			{
				echo "|0";
			}
			break;	
		case "eliminarUltimo":
				$Tope=$ArJson->numest+1;	
				for($i=5;$i>=$Tope;$i--)
				{
					$ObjNuevo = DatosNivel($i);
					$p=$i+1;
					PasarDatosProg(&$ObjNuevo["obj"],$ArObjetos[0]);	
				//	var_dump($ObjNuevo["obj"]);
				//	die();
					$re = $ObjNuevo["obj"]->Eliminar();	
					//var_dump($re);
					//die();		
				}
				if($re)
				{
					if($re)
					{
						//var_dump($ArObjetos[0]);
						//die();
						if($ArObjetos[0]->Eliminar()=='1')
						{
							echo "|1";	
						}
						else
						{
							echo "|0";
						}
					}			
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
			$oNivel = new estprog1Dao();
			$TextJso = GenerarJson2($oNivel->FiltrarEst());
			echo $TextJso;	
		break;
			case 'filtrarEst':
			$TextJso = GenerarJson2($Nivel['obj']->FiltrarEst($Nivel['cond']));
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
					case 'incluirUltimo':
						foreach($ArObjetos as $objeto)
						{
							if($objeto->incluir())
							{						
							$Tope=$ArJson->numest+1;	
							for($i=$Tope;$i<=5;$i++)
							{
								$Obj="obj".$i;
								PasarDatosProg(&$Nivel[$Obj],$Nivel["obj"]);
								$Nivel[$Obj]->Incluir();
							}
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
}




function PasarDatosProg($ObjDao,$ObJson)
{
	global $Nivel;
    if(is_object($ObjDao))
	{	
			foreach($ObjDao as $IndiceD =>$valorD)
			{
				foreach($ObJson as $Indice =>$valor)
				{
					$Indice = strtolower($Indice);
					if($Indice==$IndiceD && $Indice!="_table" && $Indice!="_tableat" && $Indice!="_saved")
					{
						$ObjDao->$Indice = utf8_decode($valor);					
					}
					elseif(in_array($IndiceD,$Nivel['codigos']))
					{
						$ObjDao->$IndiceD="0000000000000000000000000";
							
					}elseif(substr($IndiceD,0,3)=="den")
					{
						$ObjDao->$IndiceD="no aplica";
					}
					
	
				}
			}
	}
//	else
//	{
//		foreach($ObJson as $Indice =>$valor)
//		{
					
	//		$GLOBALS[$Indice] = $valor;
						
//		}
							
//	}	
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