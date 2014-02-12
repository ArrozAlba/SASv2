<?php
session_start();
header("Cache-Control:no-cache");
header("Pragma:no-cache");

require_once("../../class_folder/dao/sps_def_tasainteres_dao.php");
require_once("../../../sps/class_folder/utilidades/class_array_to_load.php");

$ls_operacion = "scroll";

if (array_key_exists("operacion",$_GET))
{ 
	$ls_operacion = $_GET["operacion"]; 
}

$lo_dao = new sps_def_tasainteres_dao();
$lo_arreglos = new class_array_to_load();

$ls_salida    = "";

if ($ls_operacion=="ue_inicializar")
{
	  require_once("../../../shared/class_folder/JSON.php");
	  $lo_json     = new JSON();
	  //Años
	  $la_anos = $lo_arreglos->getArreglo("anos","","",0);
	  $ls_salida = $lo_json->encode($la_anos);	   	
}
elseif ($ls_operacion=="ue_ver_tasainteres")
{                              
  $lb_hay = $lo_dao->getData("ORDER BY anotasint,mestasint ASC",$_GET["ano"],$la_datos);
  if (!$lb_hay)
  {
    $ls_salida  ='&<table width="400" class="fondo-tabla" cellpadding="1" cellspacing="1">';
    $ls_salida .='<tr class="celdas-letras-rojas">';
    $ls_salida .='<td align="center" colspan="2">No se han Registrado Datos</td>';
    $ls_salida .='</tr>';
    $ls_salida .='</table>';
  }
  else
  {
	$li_registros = (count($la_datos,COUNT_RECURSIVE)/count($la_datos))-1;
	$ls_salida    = "$li_registros&"; 
    $ls_salida   .= '<table id="data_grid" width="400" class="fondo-tabla" cellpadding="1" cellspacing="1">';
	for($i=0; $i<$li_registros; $i++)
	{   
	  $ls_anotas  = $la_datos["anotasint"][$i];
	  $ls_mestas  = $la_datos["mestasint"][$i];
	  $ls_valtas  = $la_datos["valtas"][$i];
	  $ls_numgac  = $la_datos["numgac"][$i];
	  $ls_salida .='<tr class="celdas-blancas">';
	  $ls_salida .='<td align="center" width="100">'.$ls_anotas.'</td>';
	  $ls_salida .='<td align="center" width="100">'.$ls_mestas.'</td>';
	  $ls_salida .='<td align="center" width="100">'.$ls_valtas.'</td>';
	  $ls_salida .='<td align="center" width="100">'.$ls_numgac.'</td>';
	  $ls_salida .='</tr>';
    }
    $ls_salida.= '</table>';
  }
}
else
{
  //Asignamos las variables para el ordenamiento
  $pagesize = 20; // Registros a traerse de la bd
  if (array_key_exists('pagesize',$_GET))
  {$pagesize = $_GET['pagesize'];}
  $offset = 0; // Primer Registro a mostrar
  if (array_key_exists('offset',$_GET))
  {$offset = $_GET['offset'];}
  $sort_col = 'número'; //Columna en la tabla HTML 
  if (array_key_exists('sort_col',$_GET))
  {$sort_col = $_GET['sort_col'];}
  
  // Asiganamos el campo para el ordenamiento
  // Hay que estar pendiente de comparalo en minusculas y con los acentos si lleva
  switch($sort_col)
  {
    case("año"):
      $sort_col="anotasint";
      break;
    case("mes"):
      $sort_col="mestasint";
      break;
	case("valor"):
      $sort_col="valtas";
      break;
	case("gaceta"):
      $sort_col="numgac";
      break;      
	default : $sort_col="mestasint";
  }  

  //Asignamos la direccion del ordenamiento
  $sort_dir = 'ASC';
  if (array_key_exists('sort_dir',$_GET))
  {
  	$sort_dir = $_GET['sort_dir'];
  }  
  //Hacemos la peticion de los registros
  $lb_hay = $lo_dao->getData("ORDER BY $sort_col $sort_dir LIMIT $offset,$pagesize",$_GET["ano"],$la_datos); 
  if ($lb_hay)
  {
    $li_registros = (count($la_datos,COUNT_RECURSIVE)/count($la_datos))-1;  
    //Indicamos en la cabecera que devolveremos un documento xml
    header("Content-Type: text/xml");	
	
	//Escribimos el xml con el formato requerido por rico
	$ls_salida  = '<'.'?xml version="1.0" encoding="UTF-8"?'.'>';
	$ls_salida .= '<ajax-response><response type="object" id="data_grid_updater"><rows update_ui="true">';  
    for($i=0; $i<$li_registros; $i++)
    {
      $li_i = 1;
	  $la_arr = array();
	  $la_arr[$li_i++] = $ls_anotas  = $la_datos["anotasint"][$i];
	  $la_arr[$li_i++] = $ls_mestas  = $la_datos["mestasint"][$i];
	  $la_arr[$li_i++] = $ls_valtas  = $la_datos["valtas"][$i];
	  $la_arr[$li_i++] = $ls_numgac  = $la_datos["numgac"][$i];
	  	  	       
      $la_newarr= "new Array(";
	  for ($li_i=1; $li_i<=count($la_arr); $li_i++)
	  {
	    $la_newarr = $la_newarr . "'" . $la_arr[$li_i] . "'";
	    if ($li_i != count($la_arr))
	    {$la_newarr = $la_newarr . ",";}
	  }
	  $la_newarr = $la_newarr . ")";
	  
      $ls_salida .='<tr class="celdas-blancas">';
	  $ls_salida .='<td align="center" width="100"><a href="javascript:ue_seleccionar('.$la_newarr.');">'.$ls_anotas.'</a></td>';
	  $ls_salida .='<td align="center" width="100"><a href="javascript:ue_seleccionar('.$la_newarr.');">'.$ls_mestas.'</a></td>';
	  $ls_salida .='</tr>';
    }
    $ls_salida .='</rows></response></ajax-response>';		
  }  
}
echo utf8_encode($ls_salida);
?>