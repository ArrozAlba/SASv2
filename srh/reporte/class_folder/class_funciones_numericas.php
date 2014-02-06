<?Php
class class_funciones_numericas
{
  
function class_funciones_numericas()
{
}
	
function ue_convertir_cadenanumero($numero)
{
  //////////////////////////////////////////////////////////////////////////////
 //	Metodo: ue_convertir_cadenanumero	 //
 //	Access:  public
 //	Returns: cadena numerica con formato xxxxx.xx
 //	Description: Funcion que permite transformar una cadena numerica con
 //				  formato xx.xxx,xx a formato xxxxx.xx
 // Fecha: 21/03/2006
 // Autor: Ing. Laura Cabré
 //////////////////////////////////////////////////////////////////////////////
  
  $numero = str_replace(".", "", $numero);
  $numero = str_replace(",", ".", $numero);
  return $numero;
}

function ue_convertir_numerocadena($numero,$tipo="d")
{
  //////////////////////////////////////////////////////////////////////////////
 //	Metodo: ue_convertir_numerocadena	 //
 //	Access:  public
 //	Returns: cadena numerica con formato xx.xxx,xx
 //	Description: Funcion que permite transformar una cadena numerica con
 //				  formato xxxxx.xx a formato xx.xxx,xx
 // Fecha: 21/03/2006
 // Autor: Ing. Laura Cabré
 //////////////////////////////////////////////////////////////////////////////
  
  if ($tipo=="d")
  {
  	$numero = number_format($numero,2, ',', '.');
  }
  elseif ($tipo == "i")
  {
  	$numero = number_format($numero,0, '', '.');    
  }
  return $numero;
}
}
?>
