<?php
class class_validacion
{
	function class_validacion()
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: class_validacion
		//		   Access: public
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/06/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		
	}

   //--------------------------------------------------------------
   function uf_valida_texto($as_valor,$ai_inicio,$ai_longitud,$as_valordefecto)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_valida_texto
		//		   Access: public
		//	    Arguments: as_valor  // Variable que deseamos validar
		//				   ai_inicio  // inicio donde se va a cortar el campo
		//				   ai_longitud  // longitud del campo
		//				   as_valordefecto  // Valor por defecto de la variable si viene en blanco
		//	      Returns: valor contenido de la variable
		//	  Description: Función que valida un texto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/06/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$ls_nuevovalor=$as_valor;
		$ls_nuevovalor=trim($ls_nuevovalor);
		
		if(($ls_nuevovalor=="")||($ls_nuevovalor==NULL))
		{
			$ls_nuevovalor=$as_valordefecto;
		}
		else
		{
			$ls_nuevovalor=str_replace("'","",$ls_nuevovalor);
			$ls_nuevovalor=str_replace('"',"",$ls_nuevovalor);
			$ls_nuevovalor=str_replace('\\',"",$ls_nuevovalor);
			$ls_nuevovalor=substr($ls_nuevovalor,$ai_inicio,$ai_longitud);
		}
   		return $ls_nuevovalor; 
   }// end function uf_valida_texto
   //--------------------------------------------------------------
	function uf_valida_texto_banco($as_valor,$ai_inicio,$ai_longitud,$as_valordefecto)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_valida_texto
		//		   Access: public
		//	    Arguments: as_valor  // Variable que deseamos validar
		//				   ai_inicio  // inicio donde se va a cortar el campo
		//				   ai_longitud  // longitud del campo
		//				   as_valordefecto  // Valor por defecto de la variable si viene en blanco
		//	      Returns: valor contenido de la variable
		//	  Description: Función que valida un texto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/06/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$ls_nuevovalor=$as_valor;
		$ls_nuevovalor=trim($ls_nuevovalor);
		
		if(($ls_nuevovalor=="")||($ls_nuevovalor==NULL))
		{
			$ls_nuevovalor=$as_valordefecto;
		}
		else
		{
			$ls_nuevovalor=str_replace("'","0",$ls_nuevovalor);
			$ls_nuevovalor=str_replace('"'," ",$ls_nuevovalor);
			$ls_nuevovalor=str_replace('\\',"/",$ls_nuevovalor);
			$ls_nuevovalor=substr($ls_nuevovalor,$ai_inicio,$ai_longitud);
		}
   		return $ls_nuevovalor; 
   }// end function uf_valida_texto
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_valida_fecha($ad_fecha,$ad_fechadefecto)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_valida_fecha
		//		   Access: public
		//	    Arguments: ad_fecha  // Variable que deseamos validar
		//				   ad_fechdefecto  // Fecha por defecto de la variable si viene en blanco
		//	      Returns: valor contenido de la variable
		//	  Description: Función que valida una fecha
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/06/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$ld_nuevafecha=$ad_fecha;
		$ld_nuevafecha=trim($ld_nuevafecha);
		
		if(($ld_nuevafecha=="")||($ld_nuevafecha==NULL))
		{
			$ld_nuevafecha=$ad_fechadefecto;
		}
		else
		{
			$li_pos=strpos($ld_nuevafecha,"/");
			$li_pos2=strpos($ld_nuevafecha,"-");
			if(($li_pos==2)||($li_pos2==2))
			{
				$ld_nuevafecha=(substr($ld_nuevafecha,6,4)."-".substr($ld_nuevafecha,3,2)."-".substr($ld_nuevafecha,0,2)); 
			}
			elseif(($li_pos==4)||($li_pos2==4))
			{
				$ld_nuevafecha=$ld_nuevafecha;
			}			
		}
   		return $ld_nuevafecha; 
   }// end function uf_valida_fecha
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_valida_monto($ai_monto,$ai_montodefecto)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_valida_monto
		//		   Access: public
		//	    Arguments: ai_monto  // Variable que deseamos validar
		//				   ai_montodefecto  // Fecha por defecto de la variable si viene en blanco
		//	      Returns: valor contenido de la variable
		//	  Description: Función que valida un monto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/06/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$li_nuevomonto=$ai_monto;
		$li_nuevomonto=trim($li_nuevomonto);
		
		if(($li_nuevomonto=="")||($li_nuevomonto==NULL))
		{
			$li_nuevomonto=$ai_montodefecto;
		}
   		return $li_nuevomonto; 
   }// end function uf_valida_monto
   //--------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_suma_fechas($ad_fecha,$ai_ndias)
    {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_suma_fechas
		//		   Access: public
		//	    Arguments: ad_fecha // Fecha a la que se desa sumar
		//                 ai_ndias // Cantidad de dias a sumar          
		//	      Returns: nuevafecha-> variable date
		//	  Description: suma una cantidad de dias pasado por parametros  a una fecha pasada por parametros 
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		if($ai_ndias>0)
		{
			$dia=substr($ad_fecha,8,2);      
			$mes=substr($ad_fecha,5,2);      
			$anio=substr($ad_fecha,0,4);      
			$ultimo_dia=date("d",mktime(0, 0, 0,$mes+1,0,$anio));
			print date("m",mktime(0, 0, 0,$mes+1,0,$anio))."<br>";
			print $mes."-".$ultimo_dia;
			
			$dias_adelanto=$ai_ndias;
			$siguiente=$dia+$dias_adelanto;
			if($ultimo_dia<$siguiente)
			{        
				$dia_final=$siguiente-$ultimo_dia;
				$mes++;         
				if($mes=='13')
				{            
					$anio++;
					$mes='01';        
				}      
				$fecha_final=$anio.'/'.str_pad($mes,2,"0",0).'/'.str_pad($dia_final,2,"0",0); 
			}
			else   
			{         
				$fecha_final=$anio.'/'.str_pad($mes,2,"0",0).'/'.str_pad($siguiente,2,"0",0); 
			} 
		}
		else
		{
			$fecha_final=$ad_fecha;
		}
		return $fecha_final;
    }// end function uf_suma_fechas	
	//-----------------------------------------------------------------------------------------------------------------------------------
	  function esBisiesto( $agno ) 
	  {
         return (( (($agno % 4) == 0) && (($agno % 100) != 0) ) 
                || (($agno % 400) == 0) );
      }
	  
	  function diasMes($ai_dia,$ai_mes,$ai_agno ) {
         $li_diasMes = 0;
      // Comprobar que el mes es correcto
         switch( $ai_mes ) {
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:		// meses de 31 dias
               $li_diasMes = 31;
               break;
            case 4:
            case 6:
            case 9:
            case 11:		// meses de 30 dias
               $li_diasMes = 30;
               break;
            case 2:		// febrero
               if ($this->esBisiesto( $ai_agno ))
                  $li_diasMes = 29;
               else
                  $li_diasMes = 28;
               break;         		
            default:			// mes incorrecto
               print "Incorrecto";
         }
      	
      // Comprobar que el dia es correcto
         if (($ai_dia <= 0) || ($ai_dia > $li_diasMes)) {
            print "Dia incorrecto";
         }
      	return $li_diasMes;
      }
	function RelativeDate($as_fecha,$li_sum)
	{//Suma periodos cortos a la fecha enviada como parametro ,no mayores de 365...
		$li_dia=0;$li_mes=0;$li_agno=0;$li_diasMes=0;$i=0;
		$li_restantes=0;		
		$li_dia  = substr($as_fecha,0,2);
		$li_mes  = substr($as_fecha,3,2);
		$li_agno = substr($as_fecha,6,4);			
		$lb_bisiesto=$this->esBisiesto($li_agno);		
		$li_diasMes=$this->diasMes($li_dia,$li_mes,$li_agno);	
		$li_restantes=$li_dia;		
		$fun=new class_funciones();
		for($i=1;$i<=$li_sum;$i++)
		{
			if($li_restantes==$li_diasMes)
			{				
				if($li_mes==12)
				{
					$li_mes=1;
					$li_agno=$li_agno+1;
					$li_restantes=0;
					$li_restantes=$li_restantes+1;
					$li_diasMes=$this->diasMes($li_restantes,$li_mes,$li_agno);		
				}
				else
				{
					$li_mes=$li_mes+1;
					$li_restantes=0;	
					$li_restantes=$li_restantes+1;
					$li_diasMes=$this->diasMes($li_restantes,$li_mes,$li_agno);
				}
			}
			else
			{
				$li_restantes=$li_restantes+1;
			}			
		}			
		$ls_fecha= $fun->uf_cerosizquierda($li_restantes,2)."-".$fun->uf_cerosizquierda($li_mes,2)."-".$li_agno;				
		return $ls_fecha;		
	}//Fin de RelativeDate

}
?>