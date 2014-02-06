<?php
class class_array_to_load
{
private $la_si_no;
private $la_yes_no;
private $la_sexo;
private $la_edo_civil;
private $la_dias;
private $la_meses;
private $la_anos;
private $la_frecuencias_salariales;
private $la_modalidad;
private $la_tipopersona;
private $la_nacionalidad;
private $la_estatus;

public function class_array_to_load()//Constructor de la clase.
{
  //INICIALIZACION DEL ARREGLO DE tipo persona
  $la_arr = array("Activo","Eliminado", "Suspendido");
  for ($li_i = 0; $li_i < count($la_arr); $li_i++) 
  {
    $this->la_estatus[$li_i]["cod"] = substr($la_arr[$li_i],0,1);
    $this->la_estatus[$li_i]["den"] = $la_arr[$li_i];
  }    
  //INICIALIZACION DEL ARREGLO DE tipo persona
  $la_arr = array("Natural","Jurídico");
  for ($li_i = 0; $li_i < count($la_arr); $li_i++) 
  {
    $this->la_tipopersona[$li_i]["cod"] = substr($la_arr[$li_i],0,1);
    $this->la_tipopersona[$li_i]["den"] = $la_arr[$li_i];
  }    
  //INICIALIZACION DEL ARREGLO DE NACIONALIDAD
  $la_arr = array("Venezolano","Extranjero");
  for ($li_i = 0; $li_i < count($la_arr); $li_i++)
  {
    $this->la_nacionalidad[$li_i]["cod"] = substr($la_arr[$li_i],0,1);
    $this->la_nacionalidad[$li_i]["den"] = $la_arr[$li_i];
  }    
  //INICIALIZACION DEL ARREGLO DE YESNO
  $la_arr = array("No","Si");
  for ($li_i = 0; $li_i < count($la_arr); $li_i++)
  {
    $this->la_yes_no[$li_i]["cod"] = "".($li_i)."";
    $this->la_yes_no[$li_i]["den"] = $la_arr[$li_i];
  }    
  //INICIALIZACION DEL ARREGLO DE SI Y NO
  $la_arr = array("No","Si");
  for ($li_i = 0; $li_i < count($la_arr); $li_i++)
  {
    $this->la_si_no[$li_i]["cod"] = substr($la_arr[$li_i],0,1);
    $this->la_si_no[$li_i]["den"] = $la_arr[$li_i];
  }
  
  //INICIALIZACION DEL ARREGLO DE SEXO
  $la_arr = array("Femenino","Masculino");
  for ($li_i = 0; $li_i < count($la_arr); $li_i++)
  {
    $this->la_sexo[$li_i]["cod"] = substr($la_arr[$li_i],0,1);
    $this->la_sexo[$li_i]["den"] = $la_arr[$li_i];
  }
 
  //INICIALIZACION DEL ARREGLO DE ESTADO CIVIL
  $la_arr = array("Soltero(a)","Casado(a)","Divorciado(a)","Viudo(a)","Concubino(a)","Separado(a)");
  for ($li_i = 0; $li_i < count($la_arr); $li_i++)
  {
    $this->la_edo_civil[$li_i]["cod"] = "".($li_i+1)."";
    $this->la_edo_civil[$li_i]["den"] = $la_arr[$li_i];    
  }
  
  //INICIALIZACION DEL ARREGLO DE DIAS
  $la_arr = array("01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31");
  for ($li_i=0; $li_i < count($la_arr); $li_i++)
  {
    $this->la_dias[$li_i]["cod"]=$this->la_dias[$li_i]["den"]=$la_arr[$li_i];
  }
  
  //INICIALIZACION DEL ARREGLO DE MESES
  $la_arr = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
  for ($li_i=0; $li_i < count($la_arr); $li_i++)
  {
    if (($li_i+1)<10)
    {$this->la_meses[$li_i]["cod"] = "0".($li_i+1)."";}
    else
    {$this->la_meses[$li_i]["cod"] = "".($li_i+1)."";}    
	$this->la_meses[$li_i]["den"]  = $la_arr[$li_i];
  }
  
  //INICIALIZACION DEL ARREGLO DE AÑOS
  $li_ano = date("Y") - 18;
  for($li_i=0;$li_i<100;$li_i++)
  {
    $this->la_anos[$li_i]["cod"]=$this->la_anos[$li_i]["den"]="".($li_ano - $li_i)."";  
  }
  
  //INICIALIZACION DEL ARREGLO DE FRECUENCIAS SALARIALES
  $la_arr = array("Semanal","Quincenal","Mensual","Bimestral","Trimestral","Eventual","No Devenga Sueldo");
  for ($li_i = 0; $li_i < count($la_arr); $li_i++)
  {
    $this->la_frecuencias_salariales[$li_i]["cod"] = substr($la_arr[$li_i],0,1);
    $this->la_frecuencias_salariales[$li_i]["den"] = $la_arr[$li_i];    
  }  
  
  //INICIALIZACION DEL ARREGLO DE MODALIDAD DE TARIFAS
  $la_arr = array("Monto Tarifa","Unidad Tributaria","Porcentaje Unidad Tributaria");
  for ($li_i = 0; $li_i < count($la_arr); $li_i++)
  {
    $this->la_modalidad[$li_i]["cod"] = "".($li_i)."";
    $this->la_modalidad[$li_i]["den"] = $la_arr[$li_i];    
  }  
}

function getArreglo($nom_arreglo,$dia="",$mes="",$minimaedad=18,$numanos=80)
{
  switch($nom_arreglo)
  {
    case "estc": return $this->la_estatus; break;  	  
    case "tipp": return $this->la_tipopersona; break;  	  
    case "nac": return $this->la_nacionalidad; break;  	
    case "yesno": return $this->la_yes_no; break;  	
    case "sino": return $this->la_si_no; break;
    case "sexo": return $this->la_sexo; break;
    case "edoc": return $this->la_edo_civil; break;
    case "dias": return $this->la_dias; break;
    case "meses":$this->valida_meses($dia);
	             return $this->la_meses; break;
    case "anos": $this->valida_anos($dia,$mes,$minimaedad,$numanos);    
				 return $this->la_anos; break;
	case "fres": return $this->la_frecuencias_salariales; break;
	case "mod":  return $this->la_modalidad; break;
  }
}

function getCodigoEnArreglo($nom_arreglo, $ls_denominacion)
{
  $la_arr = $this->getArreglo($nom_arreglo);
  for ($li_i=0;$li_i<count($la_arr);$li_i++)
  {    
    if ($la_arr[$li_i]["den"] == $ls_denominacion)
    {	 
	 break; 	  
	}
  }
  if ($li_i < count($la_arr))
  {return $la_arr[$li_i]["cod"];}
  else
  {return $ls_denominacion;}
}

function getDenominacionEnArreglo($nom_arreglo,$ls_codigo)
{
  $la_arr = $this->getArreglo($nom_arreglo);
  for ($li_i=0;$li_i<count($la_arr);$li_i++)
  {
    if ($la_arr[$li_i]["cod"] == $ls_codigo)
    {
	 break; 	  
	}
  }  
  if ($li_i < count($la_arr))
  {return $la_arr[$li_i]["den"];}
  else
  {return $ls_codigo;}
}

function valida_meses($dia)
{
  $this->la_meses=array();
  if ($dia > 30)
  {
    $la_arr = array("Enero","Marzo","Mayo","Julio","Agosto","Octubre","Diciembre");
    $la_cod = array("01","03","05","07","08","10","12");
  }
  elseif ($dia > 29)
  {
    $la_arr = array("Enero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    $la_cod = array("01","03","04","05","06","07","08","09","10","11","12");
  }				   
  else
  {
    $la_arr = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    $la_cod = array("01","02","03","04","05","06","07","08","09","10","11","12");
  }
  for ($li_i=0; $li_i < count($la_arr); $li_i++)
  {
    $this->la_meses[$li_i]["cod"] = $la_cod[$li_i];
	$this->la_meses[$li_i]["den"] = $la_arr[$li_i];
  }	                
}

function valida_anos($dia,$mes,$minimaedad,$numanos)
{
  $this->la_anos=array();
  if (($dia == "29") && ($mes == "02"))
  {
	  $li_anosdesde2004 = date("Y") - 2004;
	  $li_anosdesdeultimobisiesto = $li_anosdesde2004 % 4;
	  $li_ano = date("Y") - $li_anosdesdeultimobisiesto - (floor($minimaedad/4)*4);
	  $numanos = floor($numanos/4);
	  for($li_i=0;$li_i<$numanos;$li_i++)
	  {
	    $this->la_anos[$li_i]["cod"]=$this->la_anos[$li_i]["den"]="".$li_ano-($li_i*4)."";  
	  }
  }				   
  else
  {
      $li_ano = date("Y") - $minimaedad;
	  for($li_i=0;$li_i<$numanos;$li_i++)
	  {
	    $this->la_anos[$li_i]["cod"]=$this->la_anos[$li_i]["den"]="".($li_ano-$li_i)."";  
	  }
  }
}
}	
?>