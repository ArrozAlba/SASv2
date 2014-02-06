<?php
 /* ********************************************************************************************************************************
          Compañia : SOFTBRI C.A.
    Nombre Archivo : class_function.php
   Tipo de Archivo : archivo de clase php
             Autor : Ing. Wilmer Briceño  
    Fecha Creación : 17-2-07   
	   Descripción : Clae que contiene un conjunto de funciones básicas que permiten reutilizar objetos para el desarrollo de 
	                 aplicaciones web en php.
      Modificación : 17-2-07    Responsable Modificación : Ing. Wilmer Briceño
    ******************************************************************************************************************************** */
class class_function
{
	  public function class_function()
	  {  
	    /////////////////////
	    //   Constructure  //
	    /////////////////////						
	  } // end contructor class_function

  	  /* ********************************************
		 Métodos de funciones de Mensajes
	  ******************************************** */ 

	   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //     Function : message
	   //      Alcance : Publico
	   //         Tipo : Impresión Mensaje
	   //  Descripción : Método que imprime un mensaje, utilizando lenguaje Javascript mediante la función ALERT
	   //    Arguments : $as_message -> información que desee que se envie a la función ALERT de javascript.
	   //      Retorna : Impresión en HTML
	   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	   function message( $as_message )
	   {
		
		  print $as_message;
		
		  return ;
	   } // end function message
	
	   
	   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //     Function : confirm
	   //      Alcance : Publico
	   //         Tipo : Impresión Mensaje de confirmación
	   //  Descripción : Método que imprime un mensaje de confirmación , utilizando lenguaje Javascript mediante la función ALERT   
	   //    Arguments : $as_message -> información que desee que se envie a la función ALERT de javascript.
	   //      Retorna : Retorna un valor boolean
	   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	   function confirm( $as_message )
	   {  
		  $lb_valido = "false";
		  ?> 
			<script language=javascript>
			  if(confirm("<?php print $as_message ?>"))
			  {
				<?php $lb_valido="true"; ?>
			  }
			</script>
		  <?php
		   return $lb_valido;
		}	// end function confirm
	
		/* ********************************************
		   Métodos de Manejo de String
		******************************************** */ 

	   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //     Function : rfill
	   //      Alcance : Publico
	   //         Tipo : Manejo de string
	   //  Descripción : Método que rellena a la derecha un string con un carácter específico tantas veces desee.
	   //    Arguments : $as_string  ->  cadena de caracteres
	   //                $as_char    ->  caracter a rellenar
	   //                $ai_veces   ->  número de ocurrencias
	   //      Retorna :  string rellenado a la derecha con un caracter especial.
	   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   function rfill ( $as_string , $as_char , $ai_veces ) 
       {  		      
		  $ls_string = str_pad ( $as_string , $ai_veces , $as_char , STR_PAD_RIGHT) ;
          return $ls_string;
       } // end function rfill

	   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //     Function : lfill
	   //      Alcance : Publico
	   //         Tipo : Manejo de string
	   //  Descripción : Método que rellena a la izquierda un string con un carácter específico tantas veces desee.
	   //    Arguments : $as_string  ->  cadena de caracteres
	   //                $as_char    ->  caracter a rellenar
	   //                $ai_veces   ->  número de ocurrencias
	   //      Retorna :  string rellenado a la izquierda con un caracter especial.
	   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   function lfill ( $as_string , $as_char , $ai_veces ) 
       {  	
		  $ls_string = str_pad( $as_string , $ai_veces , $as_char , STR_PAD_LEFT) ;
	
          return $ls_string;
       } // end function lfill
		
	   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //     Function : replace
	   //      Alcance : Publico
	   //         Tipo : Manejo de string
	   //  Descripción : Método que reemplaza un token en un string
	   //    Arguments : $as_string ->  cadena de caracteres
	   //                $as_token  -> token para buscar y remover
	   //                $as_char   -> caracter para ser reemplazado
	   //      Retorna : nuevo string con caracteres reemplazados
	   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   /*
		function replace($as_string , $as_token,  $as_char  ) 
		{
		  $i=$as_string.indexOf($as_token);
		  
		  $r = "";
		  if ($i == -1) return $as_string;
		  
		  $r += $as_string.substring(0,$i) + $as_char;
		  if ( $i + $as_token.length < $as_string.length)
			 $r += replace($as_string.substring($i + $as_token.length, $as_string.length), $as_token, $as_char);
		  return $r;
		}
		*/
       /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //     Function : uf_trim
	   //      Alcance : Publico
	   //         Tipo : Manejo de string
	   //  Descripción : Método que elimina los espacios en blanco en un string
	   //    Arguments : $as_string ->  cadena de caracteres
	   //      Retorna : nuevo string sin espacios en blanco
	   ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   /*						
	   function uf_trim($as_string_old)
       {			
		  $ls_new_string = "";
		  $schar         = "";
		  $ac_cadena = preg_split('//', $as_string_old, -1, PREG_SPLIT_NO_EMPTY);
		  $tot = count($ac_cadena);
		  for($i=0;$i<$tot;$i++) 
		  {
			if($ac_cadena[$i]!=' ')	{ $ls_new_string.=$ac_cadena[$i]; }		
		  }
		  return $ls_new_string;
       }
		*/
		/* ********************************************
		   Métodos de Manejo de Fechas
		******************************************** */ 
	
       /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //     Function : uf_ctod
	   //      Alcance : Publico
	   //         Tipo : date (string)
	   //  Descripción : Método que convierte una fecha en formato string (dd/mm/yyyy) a formato string fecha (yyyy/mm/dd)
	   //    Arguments : $as_cadena  ->  cadena de fecha
	   //      Retorna : string de fecha con formato fecha "yyyy-mm-dd"
	   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function uf_ctod($as_cadena)
		{
		  $ldt_formato_date = "0000-00-00";
		  if ($as_cadena != "")
		  {
			$li_pos  = strpos($as_cadena,"/");
			$li_pos2 = strpos($as_cadena,"-");				
			if(($li_pos==2)||($li_pos2==2))
			{
			  $ldt_formato_date =(substr($as_cadena,6,4)."-".substr($as_cadena,3,2)."-".substr($as_cadena,0,2)); 
			}
			elseif(($li_pos==4)||($li_pos2==4))
			{
			  $ldt_formato_date=$as_cadena;
			}
		  }
		  return $ldt_formato_date;
		}

       /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //     Function : uf_dtoc
	   //      Alcance : Publico
	   //         Tipo : date (string)
	   //  Descripción : Método que convierte una fecha en formato string fecha (yyyy/mm/dd) a formato string (dd/mm/yyyy)
	   //    Arguments : $as_cadena  ->  cadena de fecha
	   //      Retorna : string de fecha con formato fecha "dd-mm-yyyy"
	   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
	   function uf_dtoc($as_fecha)
	   {
	   	  $ldt_formato_ddmmyyy="";
		  $li_pos  = strpos($as_fecha,"-"); 
		  $li_pos2 = strpos($as_fecha,"/"); 
		  if(($li_pos==4)||($li_pos2==4))
		  {
	   		$ldt_formato_ddmmyyy=(substr($as_fecha,8,2)."/".substr($as_fecha,5,2)."/".substr($as_fecha,0,4)); 
	 	  }
		  elseif(($li_pos==2)||($li_pos2==2))
		  {
			$ldt_formato_ddmmyyy=$as_fecha;
		  }
	      return $ldt_formato_ddmmyyy;
	   }
	   
	   ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //     Function : uf_ntoc
	   //      Alcance : Publico
	   //         Tipo : numero (string)
	   //  Descripción : Método que convierte un numero en formato de bd (0000.000) a formato string (0.000,000)
	   //    Arguments : $ps_valor  ->  cadena a convertir
	   //                $pi_decimales -> cantidad de decimales del numero
	   //      Retorna : string de numero con nuevo formato
	   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   function uf_ntoc($ps_valor, $pi_decimales=0)
	   {
	   	   $ps_valor = number_format($ps_valor,$pi_decimales,',','.');
	   	   if ($pi_decimales > 2)
		   {
		   	 $lb_continuar = true;
		   	 $j = 0;
		     while (($lb_continuar) && ($j<$pi_decimales-2))
		     {
				if ($ps_valor{strlen($ps_valor)-($j+1)} != "0")
				  $lb_continuar = false;
				else
				  $j++;
			 }
			 $ps_valor = substr($ps_valor,0,strlen($ps_valor)-$j);		 
		   }
		   return $ps_valor;
	   }
	   
	   ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //     Function : uf_cton
	   //      Alcance : Publico
	   //         Tipo : numero (string)
	   //  Descripción : Método que convierte un numero en formato string (0.000,000) a formato de bd (0000.000)
	   //    Arguments : $ps_valor  ->  cadena a convertir
	   //      Retorna : string de numero con nuevo formato
	   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   function uf_cton($ps_valor)
	   {
	   	   $ps_valor = str_replace(".","",$ps_valor);
	   	   $ps_valor = str_replace(",",".",$ps_valor);
		   return $ps_valor;
	   }
	   
	   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //     Function : uf_sort_array
	   //      Alcance : Publico
	   //         Tipo : Array
	   //  Descripción : Método que ordena un arreglo que comienza en 1 a comenzar en 0
	   //    Arguments : $pa_data  ->  arreglo de datos
	   //      Retorna : arreglo ordenado desde 0
	   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
	   function uf_sort_array($pa_data)
	   {
			$index   = array_keys($pa_data);										
			$numindex= count($index);
			$la_aux  = array();
		    for($i=0;$i<$numindex;$i++)
		    {
		    	$row  = count($pa_data[$index[$i]]);
		    	for ($j=0; $j<$row; $j++)
		    	  $la_aux[$index[$i]][$j] = $pa_data[$index[$i]][$j+1];
			}
		   return $la_aux;
	   }

} // end class-function
?>