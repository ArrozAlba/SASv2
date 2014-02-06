<?php
 /* ********************************************************************************************************************************
          Compañia : SOFTBRI C.A.
    Nombre Archivo : class_redondeo_bcv.php
   Tipo de Archivo : archivo de clase php
             Autor : Ing. Ma. Alejandra Roa
    Fecha Creación : 10-9-07   
	   Descripción : Clase que contiene un conjunto de funciones para la reexpresion monetaria de bolivar actual a bolivar fuerte.
 ******************************************************************************************************************************** */
	class class_redondeo_bcv
	{
		  public function class_redondeo_bcv( )
		  {  
		    /////////////////////
		    //   Constructure  //
		    /////////////////////						
		  } // end contructor class_redondeo_bcv
	
										
	
		   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		   //     Function : uf_exponencial
		   //      Alcance : Publico
		   //         Tipo : funcion en php
		   //  Descripción : Función que permite multiplicar la base tantas veces como la potencia indique 
		   //    Arguments : $ai_decimal -> numero por el cual se hará la multiplicación
		   //      Retorna : $li_exp
		   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		   function uf_exponencial( $ai_potencia )
		   {
				$li_base = 10;
				$li_exp  = 10;
			    for ($i=1; $i<$ai_potencia; $i++)
				{
					$li_exp = $li_exp * $li_base;
				}
			
			    return  $li_exp;
		   } // end function uf_exponencial
	
			
		  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		   //     Function : uf_redondeo
		   //      Alcance : Publico
		   //         Tipo : funcion en php
		   //  Descripción : Función que permite la conversion y redondeo del bolivar actual a bolivar fuerte
		   //    Arguments : $ad_importe -> expresion numerica que se desea redondear
		   //                $ai_decimal -> expresion numerica que indica la presicion de cifras decimales
		   //      Retorna : $ld_monred  -> monto redondeado. 
		   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		   function uf_redondeo( $ad_importe, $ai_decimal )
		   {
				$pos = strpos($ad_importe, ","); 
				//if ($pos===false)  //No se encontro comas es 0
//				{
//					
//				}
//				else
//				{
					$ld_importe1 = substr($ad_importe, pos, $ai_decimal+1); 
					$ld_importe2 = substr($ad_importe, pos, $ai_decimal);
					$ld_potencia = uf_exponencial( $ai_decimal );
					$ld_factor = ($ld_importe1 - $ld_importe2)*$ld_potencia;
					
					if ($ld_factor >= 0.5)
					{
						$ld_monred = $ld_importe2 + (1/$ld_potencia);
					}
					elseif ($ld_factor <= -0.5)
					{
						$ld_monred = -($ld_importe2) - (1/$ld_potencia);
					}
					else
					{
						$ld_monred = $ld_importe2;
					}
		//		}
			    return  $ld_monred;
		   } // end function uf_redondeo

	    
	} // end class_redondeo_bcv
?>