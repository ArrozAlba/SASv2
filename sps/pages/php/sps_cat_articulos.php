<?php
session_start();
header("Cache-Control:no-cache");
header("Pragma:no-cache");

require_once("../../class_folder/dao/sps_def_articulos_dao.php");
require_once("../../../shared/class_folder/class_funciones.php");

$ls_operacion = "scroll";

if (array_key_exists("operacion",$_GET))
{ 
	$ls_operacion = $_GET["operacion"]; 
}

$lo_dao = new sps_def_articulos_dao();
$io_function = new class_funciones();
$ls_salida    = "";

if ($ls_operacion=="ue_inicializar")
{
  $lb_hay = $lo_dao->getArticulos("ORDER BY id_art ASC",$la_datos);
  if (!$lb_hay)
  {
    $ls_salida  ='0&<table width="600" class="tabla-fondo" cellpadding="0" cellspacing="1">';
    $ls_salida .='<tr class="tabla-detalle-rojo">';
    $ls_salida .='<td align="center" colspan="2">No se ha registrado información</td>';
    $ls_salida .='</tr>';
    $ls_salida .='</table>';
  }
  else
  {
	$li_registros = (count($la_datos,COUNT_RECURSIVE)/count($la_datos))-1;
	$ls_salida    = "$li_registros&"; 
    $ls_salida   .= '<table id="data_grid" width="600" class="tabla-fondo" cellpadding="0" cellspacing="1">';
	 
	for($i=0; $i<$li_registros; $i++)
	{
	  $ls_fecvig  = $io_function->uf_convertirfecmostrar($la_datos["fecvig"][$i]);	
      $ls_salida .='<tr class="tabla-detalle" onMouseOver="seleccionarFila(this)" onMouseOut="deseleccionarFila(this)">';
      $ls_salida .='<td align="center" width="100">'.$la_datos["id_art"][$i].'</td>';
	  $ls_salida .='<td align="center" width="150">'.$la_datos["numart"][$i].'</td>';
	  $ls_salida .='<td align="center" width="150">'.$ls_fecvig.'</td>';
	  $ls_salida .='<td align="center">'.$la_datos["conart"][$i].'</td>';
	  $ls_salida .='</tr>';
    }
    $ls_salida.= '</table>';
  }
}
else
{
  //Asignamos las variables para el ordenamiento
  $pagesize = 30; // Registros a traerse de la bd
  if (array_key_exists('pagesize',$_GET))
  {$pagesize = $_GET['pagesize'];}
  $offset = 0; // Primer Registro a mostrar
  if (array_key_exists('offset',$_GET))
  {$offset = $_GET['offset'];}
  $sort_col = 'código'; //Columna en la tabla HTML 
  if (array_key_exists('sort_col',$_GET))
  {$sort_col = $_GET['sort_col'];}
  
  // Asiganamos el campo para el ordenamiento
  // IMPORTANTE: Hay que comparalo en minusculas, con los acentos si lleva 
  // y los espacios en blancos cambiarlos por "_"
  switch($sort_col)
  {
    case("código"):
      $sort_col="id_art";
      break;
    case("artículo"):
      $sort_col="numart";
      break;
    case("fecha_vig."):
      $sort_col="fecvig";
      break;  
    case("concepto"):
      $sort_col="conart";
      break;  
    default : $sort_col="id_art";
  }
  
  //Asignamos la direccion del ordenamiento
  $sort_dir = 'ASC';
  if (array_key_exists('sort_dir',$_GET))
  {$sort_dir = $_GET['sort_dir'];}
  
  //Hacemos la peticion de los registros
  $lb_hay = $lo_dao->getArticulos("ORDER BY $sort_col $sort_dir LIMIT $pagesize OFFSET $offset",$la_datos);
  if ($lb_hay)
  {
    $li_registros = (count($la_datos,COUNT_RECURSIVE)/count($la_datos))-1;
  
    //Indicamos en la cabecera que devolveremos un documento xml
    header("Content-Type: text/xml");
	
	//Escribimos el xml con el formato requerido por rico
	$ls_salida  = '<'.'?xml version="1.0" encoding="UTF-8"?'.'>';
	$ls_salida .= '<ajax-response><response type="object" id="data_grid_updater"><rows update_ui="true">';  
    for ($i=0; $i<$li_registros; $i++)
    {
      $li_i = 1;
	  $la_arr = array();  //id_art,numart,fecvig,conart
	  $la_arr[$li_i++] = $ls_id_art  = $la_datos["id_art"][$i];
      $la_arr[$li_i++] = $ls_numart  = $la_datos["numart"][$i];
	  $la_arr[$li_i++] = $ls_fecvig  = $la_datos["fecvig"][$i];
	  $la_arr[$li_i++] = $ls_conart  = $la_datos["conart"][$i];
	  	  	  	       
      $la_newarr= "new Array(";
	  for ($li_i=1; $li_i<=count($la_arr); $li_i++)
	  {
	    $la_newarr = $la_newarr . "'" . $la_arr[$li_i] . "'";
	    if ($li_i != count($la_arr))
	    {$la_newarr = $la_newarr . ",";}
	  }
	  $la_newarr = $la_newarr . ")";
	  $ls_fecvig  = $io_function->uf_convertirfecmostrar($la_datos["fecvig"][$i]);		
      $ls_salida .='<tr class="tabla-detalle">';
	  $ls_salida .='<td align="center" width="100"><a href="javascript:ue_seleccionar('.$la_newarr.');">'.$la_datos["id_art"][$i].'</a></td>';
	  $ls_salida .='<td align="center" width="150">'.$la_datos["numart"][$i].'</td>';
	  $ls_salida .='<td align="center" width="150">'.$ls_fecvig.'</td>';
	  $ls_salida .='<td align="center">'.$la_datos["conart"][$i].'</td>';
	  $ls_salida .='</tr>';
    }
    $ls_salida .='</rows></response></ajax-response>';		
  }  
}
echo utf8_encode($ls_salida);
?>