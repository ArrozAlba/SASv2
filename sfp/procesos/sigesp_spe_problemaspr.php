<?php
require_once ('../class_folder/dao/sigesp_spe_problemas_dao.php');
require_once ('../librerias/php/general/funciones.php');
require_once ('../librerias/php/general/CrearReporte.php');
require_once('../librerias/php/general/Json.php');

if ($_POST['ObjSon']) 	
{
    $submit = str_replace("\\", "", $_POST['ObjSon']);
    $json = new Services_JSON;
 	$ArJson = $json->decode($submit);
    $ofuente = new problemaDao();
    PasarDatos(&$ofuente, $ArJson,$Evento);
    $Evento = $GLOBALS["oper"];
    switch ($Evento)
    {
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
            $cad = AgregarUnoZ($ofuente->BuscarCodigo(), 15);
            echo "|{$cad}";
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