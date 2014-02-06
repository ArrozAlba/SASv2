<?php
require_once ('../class_folder/dao/sigesp_sfp_empresasDao.php');
require_once ('../class_folder/dao/sigesp_sfp_empresaDao.php');
require_once ('../librerias/php/general/funciones.php');
require_once("../librerias/php/general/class_funciones_seguridad.php");
require_once ('../librerias/php/general/CrearReporte.php');
require_once('../librerias/php/general/Json.php');

if ($_POST['ObjSon']) 	
{
    $submit = str_replace("\\", "", $_POST['ObjSon']);
    $json = new Services_JSON;
 	$ArJson = $json->decode($submit);
    $ofuente = new empresas();
    
    PasarDatos(&$ofuente,$ArJson,$Evento);
   
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
            //echo "{$ofuente->denfuefin}";
            if ($ofuente->incluir())
            {
                echo "|1";
            }
            else
            {
                echo "|0";
            }
            break;
        case 'buscarcodigo':
            $cad = AgregarUnoZ($ofuente->BuscarCodigo(),4);
            echo "|{$cad}";
            break;
		case 'leerempresa':
			$Datos=$ofuente->LeerTodos();
			$ObjSon = GenerarJson2($Datos);
			echo $ObjSon;
			break;
		case 'formatocuentas':
			$oEmp = new Empresa();
			$oEmp->codemp =$_SESSION["codemp"];
			$rs = $oEmp->LeerDatos();
			$ObjSon = GenerarJson2($rs);
            echo $ObjSon;
			break;	
        case 'catalogo':
            $Datos = $ofuente->LeerTodos();
           // var_dump($Datos);
        //    die();
            $ObjSon = GenerarJson2($Datos);
            echo $ObjSon;
            break;

        case 'actualizar':
            //var_dump($ofuente->cod_fuenfin);
            //echo "{$ofuente->denfuefin}";
            //die();
            if ($ofuente->Modificar())
            {
                echo "|1";
            }
            else
            {
                echo "|0";
            }
            break;
        case 'eliminar':
            if ($ofuente->Eliminar())
            {
                echo "|1";
            }
            else
            {
                echo "|0";
            }
            break;
        case 'buscarcadena':

            $Datos = $ofuente->LeerPorCadena($GLOBALS["criterio"], $GLOBALS["cadena"]);
           // var_dump($Datos);
         //   die();
            $ObjSon = GenerarJson2($Datos);
            echo $ObjSon;
            break;
        case 'Reporte':
            $oReporte = new Reporte();
            $Data = $ofuente->LeerTodos();
            $oReporte->CrearXml('listaproblemas', $Data);
            $oReporte->NomRep = "problemas";
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
				if($valor!='')
				{
					$ObjDao->$Indice = utf8_decode($valor);
				}
				else
				{
					$ObjDao->$Indice = null;
				}					
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