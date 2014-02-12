<?php
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  //       Class : class_funciones
  // Description : Clase universal de funciones para distintos usos de validaciones
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
 class class_funciones
 {
	var $cadena="";
	var $cadenabuscar="";
	var $ocurrencias=0;
	var $fecha="";
	var $scadena="";
 	var $scaracter;
	
	function class_funciones() // contructor
	{
	}
	
	function uf_posocurrencia($cad, $cadbus, $ocurr)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		//    Function:  uf_posocurrencia( $cad, $cadbus, $ocurr )
		// Descripción:  Devuelve la posicion, segun la cantidad de ocurrencia, de una cadena
		//               encontrada dentro de otra.
		//   Arguments:   $cad: cadena, $cadbus:cadena a buscar, $ocurr:
		////////////////////////////////////////////////////////////////////////////////////////////////////////	
	   $pos = 0;  $count = 0;  $pos2 = 0;   $possig = 0;
       for ($i=0; $i<$ocurr; $i++)
	   {
	     if ($i==0)  { $pos=strpos($cad,$cadbus);  } 
	     else 
	     { 
	       $lencad=strlen($cadbus);
	 	   $possig=$lencad + $pos;
	       $pos=strpos($cad,$cadbus,$possig);
	     }
       }	 
       $posret=$pos;
       return $posret;
    } // end function()

    function uf_cerosderecha($cadena, $longitud)
    {
	   ////////////////////////////////////////////////////////////////////////////////////////////////////////	
	   //    Function:  uf_cerosderecha( $cadena, $longuitud )
	   // Descripción: Devuelve una cadena rellena con ceros a la derecha
	   //   Arguments: $cadena: cadena, $longuitud:cantidad de ceros a rellenar*/
	   ////////////////////////////////////////////////////////////////////////////////////////////////////////	 
		 $len=0;
		 $aux=$cadena;
		 $pos=strlen($cadena);
		 $len=$longitud-$pos;
		 for ($i=0;$i<$len;$i++) {   $aux=$aux."0";    }
		 return $aux; 
    } // end function
   
 
   function uf_cerosizquierda($cadena,$longitud)
   { 
	   ////////////////////////////////////////////////////////////////////////////////////////////////////////	   
	   //    Function:   uf_cerosizquierda($cadena,$longitud)
	   // Descripción:   Devuelve una cadena rellena con ceros a la izquierda
	   //   Arguments:   $cadena: cadena, $longuitud:cantidad de ceros a rellenar*/
	   ////////////////////////////////////////////////////////////////////////////////////////////////////////	   	   
       $len=0;
       $aux=$cadena;
	   $pos=strlen($cadena);
       $len=$longitud-$pos;
       for ($i=0;$i<$len;$i++)   {  $aux="0".$aux;   }
       return $aux; 
   } // end function
   
  function uf_rellenar_der ( $cadena , $caracter , $longitud ) 
  {
	  ////////////////////////////////////////////////////////////////////////////////////////////////////////	   
	  //    Function:   uf_rellenar_der
	  // Descripción:   Rellenar por la derecha 
	  //   Arguments:   $cadena: cadena, $catacter->caracter a ser rellenado , longitud de la cadena
	  ////////////////////////////////////////////////////////////////////////////////////////////////////////	   	   
  	  $cadena = str_pad ( $cadena , $longitud , $caracter , STR_PAD_RIGHT);
	  return $cadena;
   } // end function
   
  function uf_rellenar_izq ( $cadena , $caracter , $longitud )
  {
	  ////////////////////////////////////////////////////////////////////////////////////////////////////////	   
	  //    Function:   uf_rellenar_izq
	  // Descripción:   Rellenar por la izquierda
	  //   Arguments:   $cadena: cadena, $catacter->caracter a ser rellenado , longitud de la cadena
	  ////////////////////////////////////////////////////////////////////////////////////////////////////////	   	   
      $cadena = str_pad ($cadena,$longitud,$caracter,STR_PAD_LEFT);
      return $cadena;
  } // end function
   
  function uf_rellenar_lados ( $cadena , $caracter , $longitud ) 
  {
	  ////////////////////////////////////////////////////////////////////////////////////////////////////////	   
	  //    Function:   uf_rellenar_lados
	  // Descripción:   Rellenar por la izquierda y a la derecha
	  //   Arguments:   $cadena: cadena, $catacter->caracter a ser rellenado , longitud de la cadena
	  ////////////////////////////////////////////////////////////////////////////////////////////////////////	   	   
   	  $i = strlen ( $cadena ) + ( $longitud * 2 );
   	  $cadena = str_pad ( $cadena , $i , $caracter , STR_PAD_BOTH );
   	  return $cadena;
  } // end fucntion
  
  function uf_formatovalidofecha($as_fecha)
  {
	  ////////////////////////////////////////////////////////////////////////////////////////////////////////	   
	  //    Function:   uf_convertirdatetobd
	  // Descripción:   método que convierte el formato de una fecha tipo caracter a formato (yyyy/mm/dd)
	  //   Arguments:   $cadena: cadena, $catacter->caracter a ser rellenado , longitud de la cadena
	  ////////////////////////////////////////////////////////////////////////////////////////////////////////	 
	  $ld_fecha=str_replace("/","-",$as_fecha);
	  if((trim($ld_fecha)=="")||($ld_fecha=="1900-01-01")||($ld_fecha=="01-01-1900"))
	  {
	  	$as_fecha="1900-01-01";
	  }
	  else
	  {
      	$as_fecha=date("Y-m-d",strtotime($as_fecha));
	   }
      return $as_fecha;
  } // end function  
  
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
 
   function uf_trim($cadena)
   {
	  ////////////////////////////////////////////////////////////////////////////////////////////////////////	   
	  //    Function:   uf_trim
	  // Descripción:   método que elimina todos los espacios en blanco de una cadena
	  //   Arguments:   $cadena: cadena, $catacter->caracter a ser rellenado , longitud de la cadena
	  ////////////////////////////////////////////////////////////////////////////////////////////////////////	   	   
	  $oldcadena = $cadena;
	  $newcadena = "";
	  $schar     = "";
	  $blanco    = ""; 
	  $i         = 0;
	  $ac_cadena = preg_split('//', $oldcadena, -1, PREG_SPLIT_NO_EMPTY);
	  $tot=count($ac_cadena);
	  for($i=0;$i<$tot;$i++) 
	  {
		if($ac_cadena[$i]!=' ')	{ $newcadena.=$ac_cadena[$i]; }		
	  }
	  return $newcadena;
    }	// end function
 
	function uf_convertirmsg($as_mensaje) 
	{
	    ////////////////////////////////////////////////////////////////////////////////////////////////////////	   
	    //    Function:   uf_convertirmsg($as_mensaje) 
	    // Descripción:   método que convierte una tira de catracteres en mensaje visual
	    //   Arguments:   $as_mensaje
	    ////////////////////////////////////////////////////////////////////////////////////////////////////////	   	   
		$ls_mensaje=substr($as_mensaje,0,36);
		$ls_mensaje=str_replace("'"," ",$ls_mensaje);
		$ls_mensaje=str_replace(";"," ",$ls_mensaje);
		$ls_mensaje=str_replace("("," ",$ls_mensaje);
		$ls_mensaje=str_replace(")"," ",$ls_mensaje);
		$ls_mensaje=str_replace("+"," ",$ls_mensaje);
		$ls_mensaje=str_replace("-"," ",$ls_mensaje);
		return $ls_mensaje;
	} // end function

	function iif_string($ad_condicional,$ad_true,$ad_false)
	{
		if(eval("return $ad_condicional;")) { $ad_return=$ad_true;}
		else { $ad_return=$ad_false;}
		return $ad_return;
	} // end function()
	
	
	function uf_convert_cadena($ls_cadena)
	{
		$ls_mensaje=str_replace('"','¨',$ls_mensaje);
	}
	
	function uf_convertir_cadena($as_mensaje) 
	{
	    ////////////////////////////////////////////////////////////////////////////////////////////////////////	   
	    //    Function:   uf_convertirmsg($as_mensaje) 
	    // Descripción:   método que convierte una tira de catracteres en mensaje visual
	    //   Arguments:   $as_mensaje
	    ////////////////////////////////////////////////////////////////////////////////////////////////////////	   	   
		$ls_mensaje=str_replace('"','¨',$as_mensaje);
		return $ls_mensaje;
	} // end function	
}	// end class
?>