<?php
session_start();
header("Cache-Control:no-cache");
header("Pragma:no-cache");

require_once("../../class_folder/dao/sps_def_configuracion_dao.php");

$ls_operacion = "scroll";

if (array_key_exists("operacion",$_GET))
{ 
	$ls_operacion = $_GET["operacion"]; 
}

$lo_dao = new sps_def_configuracion_dao();

$ls_salida    = "";

if ($ls_operacion=="ue_ver_spg_cuentas")
{
  $lb_hay = $lo_dao->get_spg_cuenta($_GET["spg_cuenta"],$_GET["denominacion"],"ORDER BY spg_cuenta ASC",$la_datos);
  
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
	 
	for($i=1; $i<=$li_registros; $i++)
	{
	  $ls_spg_cuenta  = $la_datos["spg_cuenta"][$i];
	  $ls_denominacion= $la_datos["denominacion"][$i];
      $ls_salida .='<tr class="celdas-blancas">';
	  $ls_salida .='<td align="center" width="150">'.$ls_spg_cuenta.'</td>';
	  $ls_salida .='<td align="center" width="350">'.$ls_denominacion.'</td>';
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
  // Hay que estar pendiente de comparalo en minusculas y con los acentos si lleva
  switch($sort_col)
  {
    case("código"):
      $sort_col="spg_cuenta";
      break;
    case("denominación"):
      $sort_col="denominacion";
      break;
	default : $sort_col="spg_cuenta";
  }  

  //Asignamos la direccion del ordenamiento
  $sort_dir = 'ASC';
  if (array_key_exists('sort_dir',$_GET))
  {
  	$sort_dir = $_GET['sort_dir'];
  }  
  //Hacemos la peticion de los registros
  $ls_spg_cuenta = $_GET["spg_cuenta"];
  $ls_denominacion = $_GET["denominacion"];
  
  $lb_hay = $lo_dao->get_spg_cuenta($ls_spg_cuenta, $ls_denominacion, "ORDER BY $sort_col $sort_dir LIMIT $offset,$pagesize",$la_datos); //$_GET["sc_cuenta"],$_GET["denominacion"],
  if ($lb_hay)
  {
    $li_registros = (count($la_datos,COUNT_RECURSIVE)/count($la_datos))-1;  
    //Indicamos en la cabecera que devolveremos un documento xml
    header("Content-Type: text/xml");	
	
	//Escribimos el xml con el formato requerido por rico
	$ls_salida  = '<'.'?xml version="1.0" encoding="UTF-8"?'.'>';
	$ls_salida .= '<ajax-response><response type="object" id="data_grid_updater"><rows update_ui="true">';  
    for ($i=1; $i<=$li_registros; $i++)
    {
      $li_i = 1;
	  $la_arr = array();
	  $la_arr[$li_i++] = $ls_spg_cuenta   = $la_datos["spg_cuenta"][$i];
      $la_arr[$li_i++] = $ls_denominacion = $la_datos["denominacion"][$i];
           
      $la_newarr= "new Array(";
	  for ($li_i=1; $li_i<=count($la_arr); $li_i++)
	  {
	    $la_newarr = $la_newarr . "'" . $la_arr[$li_i] . "'";
	    if ($li_i != count($la_arr))
	    {$la_newarr = $la_newarr . ",";}
	  }
	  $la_newarr = $la_newarr . ")";
	  
      $ls_salida .='<tr class="celdas-blancas">';
	  $ls_salida .='<td align="center" width="200"><a href="javascript:ue_seleccionar('.$la_newarr.');">'.$ls_spg_cuenta.'</a></td>';
	  $ls_salida .='</tr>';
    }
    $ls_salida .='</rows></response></ajax-response>';		
  }  
}
echo utf8_encode($ls_salida);
?>