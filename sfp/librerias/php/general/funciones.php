<?php
// function que le suma un numero a una cadena de cuatro caracteres manteniendo sus ceros a la izquierda de acuerdo sea el caso//
//Parametros; 														//
//$cod = Cadena a la cual se la sumara un numero										//
//Retorna: la cadena con la nueva cifra											//

Function AgregarUno($cod="")
{

		if($cod=="")
		{
			$cad = "0001";
		}
		else
		{		
			if(substr($cod,0,1)<>'0')
			{
			
				$cad = $cod + 1;
			}
			elseif(substr($cod,1,1)<>'0' and substr($cod,1,3)<999)
			{
				$suma = substr($cod,1,3)+1;
				$cad = "0".$suma;

			}elseif(substr($cod,1,1)<>'0' and substr($cod,1,3)==999)
			{
				$cad = $cod + 1;
				
			}
			elseif(substr($cod,2,1)<>'0' and substr($cod,2,2)<99)
			{
				$suma = substr($cod,2,2)+1;
				$cad = "00".$suma;
			}
			elseif(substr($cod,2,1)<>'0' and substr($cod,2,2)==99)
			{
				$suma = substr($cod,2,2)+1;
				$cad = "0".$suma;
			}
			elseif(substr($cod,3,1)<>'0' and substr($cod,3,1)<9)
			{
				$suma = substr($cod,3,2)+1;
				$cad = "000".$suma;
			}
			elseif(substr($cod,3,1)<>'0' and substr($cod,3,1)==9)
			{
				$suma = substr($cod,3,2)+1;
				$cad = "00".$suma;
			}
		}	
		return  $cad;

}



/**
 *	Rellena una cadena con ceros a la izquierda
 * 	$cod 			variable tipo char
 * 	$cantidad		variable tipo integer
 *
 */
function AgregarUnoZ($cod, $cantidad)
{
    $suma = intval($cod) + 1;
    $cad = str_pad(intval($suma), $cantidad, '0', STR_PAD_LEFT);
    return $cad;

}

function ver($variable)
{
	var_dump($variable);
	die();
}

function generarJsonSesion()
{
	global $json;
	$i=0;
	foreach($_SESSION as $Propiedad=>$valor)
	{
		if(!is_numeric($Propiedad))
		{
			$Propiedad = strtolower($Propiedad);
			$arRegistros[$Propiedad]= utf8_encode($valor);
		}
	}
	$json = new Services_JSON;
	$TextJson = $json->encode($arRegistros);
	return $TextJson;
}



function uf_convertirdatetobd($as_fecha)
{
	  ////////////////////////////////////////////////////////////////////////////////////////////////////////	   
	  //    Function:   uf_convertirdatetobd
	  // Descripción:   método que convierte el formato de una fecha tipo caracter a formato (yyyy/mm/dd)
	  //   Arguments:   $cadena: cadena, $catacter->caracter a ser rellenado , longitud de la cadena
	  ////////////////////////////////////////////////////////////////////////////////////////////////////////	   	   
      $ls_fecreg=""; 
 	  $li_pos=strpos($as_fecha,"/");
 	  $li_pos2=strpos($as_fecha,"-");
	  if(($li_pos==2)||($li_pos2==2))
  	  {
		 $ls_fecreg=(substr($as_fecha,6,4)."-".substr($as_fecha,3,2)."-".substr($as_fecha,0,2)); 
 	  }
	  elseif(($li_pos==4)||($li_pos2==4))
 	  {
	 	 $ls_fecreg=str_replace("/","-",$as_fecha);
	  }
      return $ls_fecreg;
  } // end function
  
  function uf_convertirfecmostrar($as_fecha)
  {
	  ////////////////////////////////////////////////////////////////////////////////////////////////////////	   
	  //    Function:   uf_convertirfecmostrar
	  // Descripción:   método que convierte el formato de una fecha tipo caracter a formato (dd/mm/yyyy)
	  //   Arguments:   $cadena: cadena, $catacter->caracter a ser rellenado , longitud de la cadena
	  ////////////////////////////////////////////////////////////////////////////////////////////////////////	   	   
      $ls_fecha="";
	  $li_pos=strpos($as_fecha,"-"); 
	  $li_pos2=strpos($as_fecha,"/"); 
	  if(($li_pos==4)||($li_pos2==4))
	  {
   		$ls_fecha=(substr($as_fecha,8,2)."/".substr($as_fecha,5,2)."/".substr($as_fecha,0,4)); 
 	  }
	  elseif(($li_pos==2)||($li_pos2==2))
	  {
		$ls_fecha=$as_fecha;
	  }
      return $ls_fecha;
   } // end function()
 














/**
 * Convert an object into an associative array
 *
 * This function converts an object into an associative array by iterating
 * over its public properties. Because this function uses the foreach
 * construct, Iterators are respected. It also works on arrays of objects.
 *
 * @return array
 */
function object_to_array($var) 
{
    $result = array();
    $references = array();

    // loop over elements/properties
    foreach ($var as $key => $value) 
    {
        // recursively convert objects
        if (is_object($value) || is_array($value)) 
	{
            // but prevent cycles
            if (!in_array($value, $references)) 
	    {
                $result[$key] = object_to_array($value);
                $references[] = $value;
            }
        } 
	else 
	{
            // simple values are untouched
            $result[$key] = $value;
        }
    }
    return $result;
}

/**
 * Convert a value to JSON
 *
 * This function returns a JSON representation of $param. It uses json_encode
 * to accomplish this, but converts objects and arrays containing objects to
 * associative arrays first. This way, objects that do not expose (all) their
 * properties directly but only through an Iterator interface are also encoded
 * correctly.
 */
function json_encode2($param) {
    if (is_object($param) || is_array($param)) {
        $param = object_to_array($param);
    }
    return json_encode($param);
}

function uf_spg_cuenta_sin_cero($as_cuenta)
{
	$cadena=posceros($as_cuenta);
	if($cadena!=NULL)
	{
		$posicion=$cadena;
		$criterio=substr($as_cuenta,0,$posicion);
	}
	else
	{
		$criterio=$as_cuenta;
	}
	return $criterio;
} 
// end function uf_spg_cuenta_sin_cero






function posceros($cadena)
{
	for($i=strlen(trim($cadena))-1;$i>=0;$i--)
	{
			if($cadena[$i]=="0")
			{
				$pos=$i;	
			}
			else
			{
				return $pos;
			}
	}
}


?>