<?php
require_once('../class_folder/dao/sigesp_sfp_con_estplandao.php');
require_once('../class_folder/dao/sigesp_sfp_generalDao.php');
require_once('../class_folder/dao/sigesp_spe_ub1Dao.php');
require_once('../class_folder/dao/sigesp_spe_estprog1Dao.php');
require_once('../class_folder/dao/sigesp_sfp_estAdminDao.php');
require_once('../librerias/php/general/funciones.php');
require_once("../librerias/php/general/class_funciones_seguridad.php");
require_once('../librerias/php/general/CrearReporte.php');
require_once('../librerias/php/general/Json.php');
if ($_POST['ObjSon']) 		
{
	$submit = str_replace("\\","",$_POST['ObjSon']);
	//$submit = utf8_decode($submit);
	$json = new Services_JSON;
	$ArJson = $json->decode($submit);
	$ArObjetos = array();
	
	if($ArJson->datos)
	{
		for($j=0;$j<=count($ArJson->datos)-1;$j++)
		{
			$ArObjetos[$j] = new ConfNivelDao();
			PasarDatos($ArObjetos[$j],$ArJson->datos[$j]);	
			$ArObjetos[$j]->pasaDatosSesion();
			//var_dump($ArObjetos[$j]);
			//die();
		}
		$Evento = $ArJson->oper;
		
	}
	else
	{	
		$ofuente = new ConfNivelDao();
		PasarDatos(&$ofuente,$ArJson);
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
				$ofuente->codnivel=$ofuente->BuscarCodigo();
				$ofuente->codnivel++;
				if($ofuente->tipo=="EA")
				{
					$ofuente->numcar=5;
				}
				elseif($ofuente->tipo=="UG")
				{
					$ofuente->numcar=4;
				}
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
							
							$arRegistros[$i][$Propiedad]= utf8_encode($Datos[$i]->$Propiedad);
							$i++;
						}
					
					}
			
						
				}
				//aqui se pasa el arreglo de arreglos a un objeto json
				$TextJso = array("raiz"=>$arRegistros);
				$TextJson = $json->encode($TextJso);
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
			if($ofuente->Eliminar()==1)
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
		case 'catalogocombopais':
			$oPais =  new generalDao('sigesp_pais');
			$rsPais = $oPais->LeerTodos2();
			$ObjSon = GenerarJson2($rsPais);
			echo $ObjSon;
			break;
		case 'catalogocomboestado':
			$oEstado =  new generalDao('sigesp_estados');
			$oEstado->codpai = $ArJson->codpai;
			$rs = $oEstado->LeerEstados();
			$ObjSon = GenerarJson2($rs);
			echo $ObjSon;	
		break;
		case 'catalogocombomuni':
			$oMuni =  new generalDao('sigesp_municipio');
			$oMuni->codpai = $ArJson->codpai;
			$oMuni->codest = $ArJson->codest;
			$rs = $oMuni->LeerMunicipios();
			$ObjSon = GenerarJson2($rs);
			echo $ObjSon;	
		break;
		case 'copiardatosub':
			$oUb = new ub1Dao();
			switch ($ArJson->desde)
			{
				case 'PAIS':
					$res = $oUb->Copiardatospais();	
					if($res===true)
					{
						echo "1";
					}
					else
					{
						echo "0";
					}
				break;
				case 'ESTADO':
					$oUb->codpai=$ArJson->codpai;
					$res = $oUb->Copiardatosestado();	
					if($res===true)
					{
						echo "1";
					}
					else
					{
						echo "0";
					}
				break;
				case 'MUNICIPIO':
					$oUb->codpai=$ArJson->codpai;
					$oUb->codest=$ArJson->codest;
					$res = $oUb->Copiardatosmuni();	
					if($res===true)
					{
						echo "1";
					}
					else
					{
						echo "0";
					}
				break;
				case 'PARROQUIA':
					$oUb->codpai=$ArJson->codpai;
					$oUb->codest=$ArJson->codest;
					$oUb->codmun=$ArJson->codmun;
					$res = $oUb->Copiardatospar();	
					if($res===true)
					{
						echo "1";
					}
					else
					{
						echo "0";
					}
				break;
				
				
			}
		break;	
		case 'copiarunej':
			$ounadmin = new EstAdmin();
			$res=$ounadmin->Copiarunej();
			if($res===true)
			{
				echo "1";
			}
			else
			{
				echo "0";
			}
		break;
		case 'copiaruniadmin':
			$ounadmin = new EstAdmin();
			$res=$ounadmin->Copiarunadmin();
			if($res===true)
			{
				echo "1";
			}
			else
			{
				echo "0";
			}
		break;
		case 'copiarestpre':
			$oEstpro = new estprog1Dao();
			$res=$oEstpro->Copiarestprog();
			if($res===true)
			{
				echo "1";
			}
			else
			{
				echo "0";
			}
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

?>