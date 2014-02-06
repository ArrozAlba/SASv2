<?php
session_start();
header("Cache-Control:no-cache");
header("Pragma:no-cache");

require_once("../../class_folder/dao/sps_pro_liquidacion_dao.php");

$ls_operacion = "scroll";

if (array_key_exists("operacion",$_GET))
{ 
	$ls_operacion = $_GET["operacion"]; 
}

$lo_dao = new sps_pro_liquidacion_dao();

$ls_salida    = "";

if ($ls_operacion=="ue_ver_liquidacion")
{
  $lb_hay = $lo_dao->get_aprob_liquidacion($_GET["numliq"],$_GET["nomper"],$_GET["apeper"],"ORDER BY numliq ASC",$la_datos);
  
  if (!$lb_hay)
  {
    $ls_salida  ='&<table width="500" class="fondo-tabla" cellpadding="1" cellspacing="1">';
    $ls_salida .='<tr class="celdas-letras-rojas">';
    $ls_salida .='<td align="center" colspan="2">No se han Registrado Datos</td>';
    $ls_salida .='</tr>';
    $ls_salida .='</table>';
  }
  else
  {
	$li_registros = (count($la_datos,COUNT_RECURSIVE)/count($la_datos))-1;
	$ls_salida    = "$li_registros&"; 
    $ls_salida   .= '<table id="data_grid" width="500" class="fondo-tabla" cellpadding="1" cellspacing="1">';
	for($i=0; $i<$li_registros; $i++)
	{    
	  $ls_numliq  = $la_datos["numliq"][$i];
	  $ls_nomper  = $la_datos["nomper"][$i];
	  $ls_apeper  = $la_datos["apeper"][$i];
	  $ls_codper  = $la_datos["codper"][$i];
	  $ls_codnom  = $la_datos["codnom"][$i];
	  $ls_desnom  = $la_datos["desnom"][$i];
	  $ls_fecliq  = $la_datos["fecliq"][$i];
	  $ls_fecing  = $la_datos["fecing"][$i];
	  $ls_fecegr  = $la_datos["fecegr"][$i];
	  $ls_totpagliq = $la_datos["totpagliq"][$i];
	  $ls_estliq  = $la_datos["estliq"][$i];
	  $ls_obsliq  = $la_datos["obsliq"][$i];
      $ls_salida .='<tr class="celdas-blancas">';
	  $ls_salida .='<td align="center" width="120">'.$ls_numliq.'</td>';
	  $ls_salida .='<td align="center" width="190">'.$ls_nomper.'</td>';
	  $ls_salida .='<td align="center" width="190">'.$ls_apeper.'</td>';
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
    case("número"):
      $sort_col="numliq";
      break;
    case("nombre"):
      $sort_col="nomper";
      break;
	case("apellido"):
      $sort_col="apeper";
      break;  
	default : $sort_col="numliq";
  }  

  //Asignamos la direccion del ordenamiento
  $sort_dir = 'ASC';
  if (array_key_exists('sort_dir',$_GET))
  {
  	$sort_dir = $_GET['sort_dir'];
  }  
  //Hacemos la peticion de los registros
  $ls_numliq = $_GET["numliq"];
  $ls_nomper = $_GET["nomper"];
  $ls_apeper = $_GET["apeper"];
  $lb_hay = $lo_dao->get_aprob_liquidacion($ls_numliq, $ls_nomper, $ls_apeper, "ORDER BY $sort_col $sort_dir LIMIT $offset,$pagesize",$la_datos); 
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
	  $la_arr[$li_i++] = $ls_numliq    = $la_datos["numliq"][$i];
      $la_arr[$li_i++] = $ls_nomper    = $la_datos["nomper"][$i];
	  $la_arr[$li_i++] = $ls_apeper    = $la_datos["apeper"][$i];
      $la_arr[$li_i++] = $ls_codper    = $la_datos["codper"][$i];
	  $la_arr[$li_i++] = $ls_codnom    = $la_datos["codnom"][$i];
	  $la_arr[$li_i++] = $ls_desnom    = $la_datos["desnom"][$i];
	  $la_arr[$li_i++] = $ls_fecliq    = $la_datos["fecliq"][$i];
	  $la_arr[$li_i++] = $ls_fecing    = $la_datos["fecing"][$i];
	  $la_arr[$li_i++] = $ls_fecegr    = $la_datos["fecegr"][$i];
	  $la_arr[$li_i++] = $ls_totpagliq = $la_datos["totpagliq"][$i];
	  $la_arr[$li_i++] = $ls_estliq    = $la_datos["estliq"][$i];
	  $la_arr[$li_i++] = $ls_obsliq    = $la_datos["obsliq"][$i]; 
	  
      $la_newarr= "new Array(";
	  for ($li_i=1; $li_i<=count($la_arr); $li_i++)
	  {
	    $la_newarr = $la_newarr . "'" . $la_arr[$li_i] . "'";
	    if ($li_i != count($la_arr))
	    {$la_newarr = $la_newarr . ",";}
	  }
	  $la_newarr = $la_newarr . ")";
	  
      $ls_salida .='<tr class="celdas-blancas">';
	  $ls_salida .='<td align="center" width="200"><a href="javascript:ue_seleccionar('.$la_newarr.');">'.$ls_numliq.'</a></td>';
	  $ls_salida .='</tr>';
    }
    $ls_salida .='</rows></response></ajax-response>';		
  }  
}
echo utf8_encode($ls_salida);
?>