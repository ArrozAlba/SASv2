<?php
require_once('../class_folder/dao/sigesp_sfp_plan_unico_reDao.php');
require_once('../class_folder/dao/sigesp_sfp_con_estplandao.php');
require_once('../class_folder/dao/sigesp_sfp_conversionDao.php');
require_once('../class_folder/dao/sigesp_sfp_plancuentasDao.php');
require_once('../class_folder/dao/sigesp_sfp_cuentascontDao.php');
require_once("../librerias/php/general/class_funciones_seguridad.php");
require_once("../class_folder/dao/sigesp_sfp_empresasDao.php");
require_once("../class_folder/dao/sigesp_sfp_plan_unico_reDao.php");
require_once('../librerias/php/general/funciones.php');
require_once('../librerias/php/general/CrearReporte.php');
require_once('../librerias/php/general/Json.php');

function getObj($tipo)
{
	switch($tipo)
	{
		case 'GI':
		$tipoCuenta['obj']=new planUnicoRe();
		break;
		case 'CO':
		$tipoCuenta['obj']=new planContable();
		break;
		case 'VP':
		$tipoCuenta['obj']=new planUnicoRe();
		break;
	}
	return $tipoCuenta;
}


function uf_total_niveles($as_formato)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_total_niveles
		//		   Access: public 
		//       Argument: as_formato //   formato de l cuenta definida en sigesp_empresa
		//	  Description: Este método retorna el numero de niveles de la cuenta
		//	      Returns: li_count // total de niveles
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_count=0;
		$i=0;
		$arr=str_split($as_formato);
		$arr2=split("-",$as_formato);
		$tot=count($arr);
		for($i=0;$i<$tot;$i++) 
		{
			if($arr[$i]=="-")
			{
				$li_count=$li_count+1;
			}
		}
		$arr=array("cantidad"=>$li_count+1,"niveles"=>$arr2);
	    return $arr;	
}// end function uf_total_niveles



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
	$oConversion1= new PlancuentasDao();
	$oConversion= new conversionDao();
	PasarDatos($oConversion,$ArJson);
	PasarDatos($oConversion1,$ArJson);
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
			if($oConversion1->Incluir())
			{
				echo "|1";
			}
			else
			{
				echo "|0";
			}
			break;
		case 'actualizar':
			if($oConversion1->Modificar())
			{
				echo "|1";
			}
			else
			{
				echo "|0";
			}
			break;
		case 'eliminar':
			if($oConversion1->Eliminar()==1)
			{
				echo "|1";
			}
			else
			{
				echo "|0";
			}
			break;	
		case 'buscarcadena':
			$Data = $oConversion->LeerTodasCuentas($GLOBALS["criterio"],$GLOBALS["cadena"]);
			$ObjSon = GenerarJson2($Data);
			echo $ObjSon;
			break;
		case 'Reporte':
			$oReporte = new Reporte();
			$Data = $ofuente->LeerTodos();
			$oReporte->CrearXml('listafuente',$Data);
			$oReporte->NomRep="FuenteFin";
			echo $oReporte->MostrarReporte();
		case 'catalogo':
			$Data = $oConversion->LeerTodos();
			$ObjSon = GenerarJson2($Data);
			echo $ObjSon;
			break;
		case 'catalogofiltro':
			$Data = $oConversion->LeerTodasCuentas($ArJson->criterio,$ArJson->valor);
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
					$cuentActual = $AuxReg->sig_cuenta;
					$tamCuentActual = strlen(trim($cuentActual));
					$oEmp = new empresas();
					$rsEmp = $oEmp->LeerUno();
					$formatogastos = $rsEmp->fields["formpre"];
					$formatoingresos = $rsEmp->fields["formspi"];
					$Grupocuenta = substr($AuxReg->sig_cuenta,0,1);
					if($Grupocuenta=='3')
					{
						$formato = $formatoingresos;
					}
					else
					{
						$formato = $formatogastos;
					}
					
					$Cantformatogen = strlen(trim(str_replace("-","",$formato))); 
					$totalNiveles = uf_total_niveles($formato);
					$acuNiveles=0;
					$acuNivelAnt=0;
					$oCuenta = new planUnicoRe();
					PlancuentasDao::IniciarTran();
					$referencia="";
					if($totalNiveles["cantidad"]==4)
					{
						$auxtotal = $totalNiveles["cantidad"];
					}
					elseif($totalNiveles["cantidad"]==5 && $tamCuentActual=='9')
					{
						$auxtotal = $totalNiveles["cantidad"]-1;
					}
					elseif($totalNiveles["cantidad"]==5 && $tamCuentActual!='9')
					{
						$auxtotal = $totalNiveles["cantidad"];
					}
					
					for($i=0;$i<$auxtotal;$i++)										
					{
						$acuNiveles = $acuNiveles+strlen($totalNiveles["niveles"][$i]); 
						$acuNivelAnt = $acuNivelAnt+strlen($totalNiveles["niveles"][$i-1]);
						$auxcuenta1 = substr($cuentActual,0,$acuNiveles);
						$auxcuentaref = substr($cuentActual,0,$acuNivelAnt);
						$oCuenta->sig_cuenta = $auxcuenta1;
						$oCuenta->cuenta = $auxcuenta1;
						$rsAux = $oCuenta->LeerUna2();
						if($oCuenta->tieneHijas()===true)
						{
							$AuxReg->estatus = "S";
						}
						else
						{
							$AuxReg->estatus = "C";
						}
						$AuxReg->sig_cuenta = str_pad($auxcuenta1,$Cantformatogen,"0");
						if($auxcuentaref!="")
						{
							$AuxReg->referencia = str_pad($auxcuentaref,$Cantformatogen,"0");	
						}
						else
						{
							$AuxReg->referencia="";
						}
						$oPlancuenta = new PlancuentasDao();
						PasarDatos($oPlancuenta,$AuxReg);
						$oPlancuenta->nivel = $i + 1;
						$oPlancuenta->denominacion = trim($rsAux->fields["denominacion"]);
						if($oPlancuenta->VerificarExistencia()===false)
						{
							$oPlancuenta->Incluir();					
						}
					}
					PlancuentasDao::CompletarTran();
				}
				echo "|1";
			}
			break;
		case 'grabarnuevacuenta':
					$cuentActual = $ArJson->sig_cuenta;
					$oEmp = new empresas();
					$rsEmp = $oEmp->LeerUno();
					$formatogastos = $rsEmp->fields["formpre"];
					$formatoingresos = $rsEmp->fields["formspi"];
					$Grupocuenta = substr($ArJson->sig_cuenta,0,1);
					if($Grupocuenta=='3')
					{
						$formato = $formatoingresos;
					}
					else
					{
						$formato = $formatogastos;
					}
					
					$Cantformatogen = strlen(str_replace("-","",$formato)); 
					$totalNiveles = uf_total_niveles($formato);
					$acuNiveles=0;
					$acuNivelAnt=0;
					$oCuenta = new planUnicoRe();
					$referencia="";
					if($totalNiveles["cantidad"]>4)
					{
						PlancuentasDao::IniciarTran();
						$canNivel5 = strlen($cuentActual["cantidad"]);
						$AuxReg->sig_cuenta = str_pad($auxcuenta1,$Cantformatogen,"0");
						if($auxcuentaref!="")
						{
							$AuxReg->referencia = str_pad($auxcuentaref,$Cantformatogen,"0");	
						}
						else
						{
							$AuxReg->referencia="";
						}
						
						$oPlancuenta = new PlancuentasDao();
						PasarDatos($oPlancuenta,$ArJson);
						$oPlancuenta->nivel = 5;
						$oPlancuenta->estatus = 'C';
						$oPlancuenta->referencia = str_pad(substr($ArJson->sig_cuenta,0,9),$Cantformatogen,"0");
						$oPlancuenta->denominacion = trim($ArJson->denominacion);
						//ver($oPlancuenta);
						if($oPlancuenta->VerificarExistencia()===false)
						{
							$oPlancuenta->Incluir();
							$oPlancuenta->cambiarestatusref();					
						}
					   $res = PlancuentasDao::CompletarTran();
					   echo "|{$res}";
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