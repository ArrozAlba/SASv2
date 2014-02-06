<?php
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  //       Class : class_fecha
  // Description : Clase que maneja la información correspondiente al manejo de periodo y valiudaciones de 
  //               fechas contable.
  ////////////////////////////////////////////////////////////////////////////////////////////////////////

class class_fecha
{
    var $is_msg_error;
	var $la_emp;
	var $SQL;
	var $io_fun;

	function class_fecha()
	{
		$this->la_emp=$_SESSION["la_empresa"];
	}

	function uf_valida_fecha_periodo($as_fecha,$as_codemp)
	{
	    $li_ano    = 0 ; $li_mes=0;$li_ano_periodo=0;$li_mes_periodo=0;
	    $ls_fecha  = ""; $ls_periodo_final=""; 
	    $lb_valido = true;
   	    $as_fecha=$this->uf_convert_date_to_db($as_fecha);
	    $li_ano = intval(substr($as_fecha,0,4));
	    $li_mes = intval(substr($as_fecha,5,2));
	    $li_ano_periodo = intval(substr($_SESSION["la_empresa"]["periodo"],0,4));
	    $li_mes_periodo = intval(substr($_SESSION["la_empresa"]["periodo"],5,2));
	    $ld_periodo_final = "31/12/".$li_ano_periodo;
		if ($li_ano == $li_ano_periodo)
		{
			if($li_mes >= $li_mes_periodo)
			{
			   if($this->uf_valida_fecha_mes( $as_codemp, $as_fecha ))
			   {
			 	  $lb_valido = true;
			   }
			   else	 
			   {
				  $lb_valido = false;
			 	  $this->is_msg_error = "Mes no esta Abierto";
				  return false;
			   }
			} 			
			else {  $lb_valido = false;	}
		}
		else { $lb_valido = false;	}

		if(!$lb_valido)
		{
			$ls_fec=$this->la_emp["periodo"];
			$ls_fec=substr($ls_fec,8,2)."/".substr($ls_fec,5,2)."/".substr($ls_fec,0,4);
			$this->is_msg_error =  "La fecha es invalida, debe estar comprendido entre [".$ls_fec."-".($ld_periodo_final)."]";
		}
		return $lb_valido;	
	} // end function()
	
    function uf_valida_fecha_mes($as_codemp,$as_fecha)
 	{ ////////////////////////////////////////////////////////////////////////////////////////////	
	  //	-Function:  uf_valida_fecha
	  //	-Access:  public
	  //	-Arguments:
	  //   as_codemp     // codigo de la empresa. 
	  //   as_fecha     // valida la fecha en cuanto al mes de apertura de la misma
	  //	-Returns:		lb_valido  // Retorna 
	  //	-Description:  Este método valida que la fecha sea valida en cuanto al ejercicio
	  //               contable del mes. 
	  ////////////////////////////////////////////////////////////////////////////////////////////
		 $li_mes=0;$li_M01=0;$li_M02=0;$li_M03=0;$li_M04=0;$li_M05=0;$li_M06=0;$li_M07=0;$li_M08=0;$li_M09=0;$li_M10=0;$li_M11=0;$li_M12=0;
		 $lb_abierto_mes=false;
		 $lb_valido=false;
		 $ls_cadena="";
    	 $as_fecha=$this->uf_convert_date_to_db($as_fecha);
		 $li_mes = intval(substr($as_fecha,5,2));
		 $sig_inc=new sigesp_include();
		 $con=$sig_inc->uf_conectar();
		 $this->SQL=new class_sql($con);
		 $ls_cadena="SELECT m01,m02,m03,m04,m05,m06,m07,m08,m09,m10,m11,m12 FROM sigesp_empresa WHERE codemp = '".$as_codemp."'";
		 $result=$this->SQL->select($ls_cadena);
		 if($row=$this->SQL->fetch_row($result))
		 {
			$li_M01=$row["m01"];
			$li_M02=$row["m02"];
			$li_M03=$row["m03"];
			$li_M04=$row["m04"];
			$li_M05=$row["m05"];
			$li_M06=$row["m06"];
			$li_M07=$row["m07"];
			$li_M08=$row["m08"];
			$li_M09=$row["m09"];
			$li_M10=$row["m10"];
			$li_M11=$row["m11"];
			$li_M12=$row["m12"];
			$lb_valido=true;
		}
		else {$lb_valido=false;}
		if ($lb_valido)
		{
			switch ($li_mes)
			{
				case 1:
					if($li_M01==1) 	{ $lb_abierto_mes = true; }
					else { $lb_abierto_mes = false;	}
					break;
				case 2:
					if($li_M02==1) 	{ $lb_abierto_mes = true; }
					else { $lb_abierto_mes = false;	}
					break;
				case 3:
					if($li_M03==1) 	{ $lb_abierto_mes = true; }
					else { $lb_abierto_mes = false;	}
					break;
				case 4:
					if($li_M04==1) 	{ $lb_abierto_mes = true; }
					else { $lb_abierto_mes = false;	}
					break;
				case 5:
					if($li_M05==1) 	{ $lb_abierto_mes = true; }
					else { $lb_abierto_mes = false;	}
					break;
				case 6:
					if($li_M06==1) 	{ $lb_abierto_mes = true; }
					else { $lb_abierto_mes = false;	}
					break;
				case 7:
					if($li_M07==1) 	{ $lb_abierto_mes = true; }
					else { $lb_abierto_mes = false;	}
					break;
				case 8:
					if($li_M08==1) 	{ $lb_abierto_mes = true; }
					else { $lb_abierto_mes = false;	}
					break;
				case 9:
					if($li_M09==1) 	{ $lb_abierto_mes = true; }
					else { $lb_abierto_mes = false;	}
					break;
				case 10:
					if($li_M10==1) 	{ $lb_abierto_mes = true; }
					else { $lb_abierto_mes = false;	}
					break;
				case 11:
					if($li_M11==1) 	{ $lb_abierto_mes = true; }
					else { $lb_abierto_mes = false;	}
					break;
				case 12:
					if($li_M12==1) 	{ $lb_abierto_mes = true; }
					else { $lb_abierto_mes = false;	}
					break;
				default:
			}
		}	
		if (!$lb_abierto_mes)
		{
			$this->is_msg_error = "El Mes ".$li_mes." no esta abierto.";
			$lb_valido = false;
		}
       return $lb_valido;
    } // end fuction

	function uf_last_day($ls_mes,$ls_ano)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function: 	  uf_last_day
		//	Description:  Metodo que me retorna la fecha final del mes y ano enviado como parametro.
		//	Arguments:	
		//				  - $ls_mes: Mes de la fecha a obtener el ultimo dia.		
		//				  - $ls_ano: Año de la fecha a obtener el ultimo dia.
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_last_day=28; 
		while (checkdate($ls_mes,$ls_last_day + 1,$ls_ano))
		{ 
		   $ls_last_day++; 
		} 
		$ld_fecha=$ls_last_day."/".$ls_mes."/".$ls_ano;
		return $ld_fecha; 
	} // end function

  function uf_convert_date_to_db($as_cadena)
  {
    $ls_fecreg=""; 
	$li_pos=strpos($as_cadena,"/");
	$li_pos2=strpos($as_cadena,"-");
	
	if(($li_pos==2)||($li_pos2==2))
	{
		$ls_fecreg=(substr($as_cadena,6,4)."-".substr($as_cadena,3,2)."-".substr($as_cadena,0,2)); 
	}
	elseif(($li_pos==4)||($li_pos2==4))
	{
		$ls_fecreg=$as_cadena;
	}
    return $ls_fecreg;
  }
	
  function uf_comparar_fecha($ad_desde,$ad_hasta)
  {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_comparar_fecha
		//	Access:    public
		//	Arguments: ad_desde // fecha de inicio
		//  		   ad_hasta // fecha de cierre
		//  Return:    $lb_fechavalida:  true  -> las fechas son correctas
		//				 false -> las fechas son incorrectas
		//	Description:  Funcion que valida que al tener dos fechas (un periodo de tiempo)
		//                la fecha que inicia el periodo no sea mayor a la fecha que cierra el 
		//                periodo; es decir que lasfechas no esten solapadas.
		//              
		//////////////////////////////////////////////////////////////////////////////		
		$lb_fechavalida=false;
		$ld_desdeaux=$this->uf_convert_date_to_db($ad_desde);
		$ld_hastaaux=$this->uf_convert_date_to_db($ad_hasta);
		
		if(($ld_desdeaux=="")||($ld_hastaaux==""))
		{
			$lb_fechavalida=false;
		}
		else
		{
			$ld_anod= substr($ld_desdeaux,0,4);
			$ld_mesd= substr($ld_desdeaux,5,2);
			$ld_diad= substr($ld_desdeaux,8,2);
			$ld_anoh= substr($ld_hastaaux,0,4);
			$ld_mesh= substr($ld_hastaaux,5,2);
			$ld_diah= substr($ld_hastaaux,8,2);
			
			if($ld_anod<$ld_anoh)
			{$lb_fechavalida=true;}
			elseif($ld_anod==$ld_anoh)
			{
				if($ld_mesd<$ld_mesh)
				{$lb_fechavalida=true;}
				elseif($ld_mesd==$ld_mesh)
				{
					if($ld_diad<=$ld_diah)
					{$lb_fechavalida=true;}
				}
			}
		}
		return $lb_fechavalida;
	}
	function uf_load_numero_mes($ls_mes)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function: 	  uf_load_numero_mes
		//	Description:  Funcion que se encarga de obtener el numero de un mes a partir de su nombre.
		//	Arguments:	
		//				  - $ls_mes: Mes de la fecha a obtener el ultimo dia.		
		//				  - $ls_ano: Año de la fecha a obtener el ultimo dia.
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $li_nummes=0;
		 switch ($ls_mes){
			case 'Enero':
			  $li_nummes='01';
			  break;
			case 'Febrero':
			  $li_nummes='02';
			  break;
			case 'Marzo':
			  $li_nummes='03';
			  break;
			case 'Abril':
			  $li_nummes='04';
			  break;
			case 'Mayo':
			  $li_nummes='05';
			  break;
			case 'Junio':
			  $li_nummes='06';
			  break;
			case 'Julio':
			  $li_nummes='07';
			  break;
			case 'Agosto':
			  $li_nummes='08';
			  break;
			case 'Septiembre':
			  $li_nummes='09';
			  break;
			case 'Octubre':
			  $li_nummes='10';
			  break;
			case 'Noviembre':
			  $li_nummes='11';
			  break;
			case 'Diciembre':
			  $li_nummes='12';
			  break;
		 }
	  return $li_nummes;
	 }
	
	function uf_load_nombre_mes($ai_mes)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function: 	  uf_load_nombre_mes
		//	Description:  Funcion que se encarga de obtener el numero de un mes a partir de su nombre.
		//	Arguments:	
		//				  - $ls_mes: Mes de la fecha a obtener el ultimo dia.		
		//				  - $ls_ano: Año de la fecha a obtener el ultimo dia.
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		 switch ($ai_mes){
			case '01':
			  $ls_nommes='Enero';
			  break;
			case '02':
			  $ls_nommes='Febrero';
			  break;
			case '03':
			  $ls_nommes='Marzo';
			  break;
			case '04':
			  $ls_nommes='Abril';
			  break;
			case '05':
			  $ls_nommes='Mayo';
			  break;
			case '06':
			  $ls_nommes='Junio';
			  break;
			case '07':
			  $ls_nommes='Julio';
			  break;
			case '08':
			  $ls_nommes='Agosto';
			  break;
			case '09':
			  $ls_nommes='Septiembre';
			  break;
			case '10':
			  $ls_nommes='Octubre';
			  break;
			case '11':
			  $ls_nommes='Noviembre';
			  break;
			case '12':
			  $ls_nommes='Diciembre';
			  break;
		 }
	     return $ls_nommes;
	 } // end function	 
   
  function uf_restar_fechas($ad_desde,$ad_hasta)
  {
		//////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_comparar_fecha
		//	     Access: public
		//	  Arguments: ad_desde // fecha de inicio
		//  	  	     ad_hasta // fecha de cierre
		//       Return: cantidad de dias obtenidos en la operacion
		//	Description: Funcion que indica la cantidad de dias que existen entre dos fechas 
		//   Creado Por: Ing. Luis Anibal Lang
		//////////////////////////////////////////////////////////////////////////////		
		$dias_diferencia=0;
		$ld_desdeaux=$this->uf_convert_date_to_db($ad_desde);
		$ld_hastaaux=$this->uf_convert_date_to_db($ad_hasta);
		
		if(($ld_desdeaux=="")||($ld_hastaaux==""))
		{
			return false;
		}
		else
		{
			$ld_anod= substr($ld_desdeaux,0,4);
			$ld_mesd= substr($ld_desdeaux,5,2);
			$ld_diad= substr($ld_desdeaux,8,2);
			$ld_anoh= substr($ld_hastaaux,0,4);
			$ld_mesh= substr($ld_hastaaux,5,2);
			$ld_diah= substr($ld_hastaaux,8,2);
						
			//calculo timestam de las dos fechas 
			$timestampd = mktime(0,0,0,$ld_mesd,$ld_diad,$ld_anod); 
			$timestamph = mktime(4,12,0,$ld_mesh,$ld_diah,$ld_anoh); 
			
			//resto a una fecha la otra 
			$segundos_diferencia = $timestampd - $timestamph; 
		
			//convierto segundos en días 
			$dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
			
			//obtengo el valor absoulto de los días (quito el posible signo negativo) 
			$dias_diferencia = abs($dias_diferencia); 
			
			//quito los decimales a los días de diferencia 
			$dias_diferencia = floor($dias_diferencia);		
		}
		return $dias_diferencia;
	}
	
	
	function suma_fechas($fecha,$ndias) 
	{
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//	     Function: suma_fechas
			//	    Arguments: $fecha  // fecha inicial
			//				   $ndias  // número de días a sumar a la fecha inicial
			//	      Returns: Retorna la variable $nuevafecha con el nuevo valor de la fecha al sumar el número de días pasado como 
			//                 parámetro
			//	  Description: Funcion que suma un valor de días enteros a una fecha (en formato dd/mm/aaaa)
			//	   Creado Por: Maria Beatriz Unda	
			// Fecha Creación: 25/08/2008							
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
	
		  if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
				
	
				  list($dia,$mes,$año)=split("/", $fecha);
				
	
		  if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
				
	
				  list($dia,$mes,$año)=split("-",$fecha);
			$nueva = mktime(0,0,0, $mes,$dia,$año) + $ndias * 24 * 60 * 60;
			$nuevafecha=date("d/m/Y",$nueva);
				
	
		  return ($nuevafecha);  
				
	
	}
}
?>