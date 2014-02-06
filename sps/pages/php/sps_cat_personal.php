<?php
session_start();
header("Cache-Control:no-cache");
header("Pragma:no-cache");

require_once("../../class_folder/dao/sps_pro_personal_dao.php");

$ls_operacion = "scroll";

if (array_key_exists("operacion",$_GET))
{ 
	$ls_operacion = $_GET["operacion"]; 
}

$lo_dao = new sps_pro_personal_dao();

$ls_salida    = "";

if ($ls_operacion=="ue_inicializar")
{
	  require_once("../../../shared/class_folder/JSON.php");
	  $lo_json = new JSON();
	  $lb_hay = $lo_dao->getNominas("ORDER BY codnom",$la_nominas);
	  if ($lb_hay)
		$ls_salida = $lo_json->encode($la_nominas);		
}
elseif ($ls_operacion=="ue_ver_personal")
{
  $lb_hay = $lo_dao->getPersonal("ORDER BY p.codper ASC",$la_datos,$_GET["cedper"],$_GET["nomper"],$_GET["apeper"],$_GET["codnom"]);
  if (!$lb_hay)
  {
    $ls_salida  ='&<table width="600" class="fondo-tabla" cellpadding="1" cellspacing="1">';
    $ls_salida .='<tr class="celdas-letras-rojas">';
    $ls_salida .='<td align="center" colspan="2">No se han Registrado Datos</td>';
    $ls_salida .='</tr>';
    $ls_salida .='</table>';
  }
  else
  {
	$li_registros = (count($la_datos,COUNT_RECURSIVE)/count($la_datos))-1;
	$ls_salida    = "$li_registros&"; 
    $ls_salida   .= '<table id="data_grid" width="600" class="fondo-tabla" cellpadding="1" cellspacing="1">';
	for($i=0; $i<$li_registros; $i++)
	{   
	  $ls_codper  = $la_datos["codper"][$i];
	  $ls_nomper  = $la_datos["nomper"][$i];
	  $ls_apeper  = $la_datos["apeper"][$i];
	  $ls_codnom  = $la_datos["codnom"][$i];
	  $ls_desnom  = $la_datos["desnom"][$i];
	  $ls_salida .='<tr class="celdas-blancas">';
	  $ls_salida .='<td align="center" width="100">'.$ls_codper.'</td>';
	  $ls_salida .='<td align="center" width="150">'.$ls_nomper.'</td>';
	  $ls_salida .='<td align="center" width="150">'.$ls_apeper.'</td>';
	  $ls_salida .='<td align="center" width="60">'.$ls_codnom.'</td>';
	  $ls_salida .='<td align="center" width="140">'.$ls_desnom.'</td>';
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
    case("personal"):
      $sort_col="codper";
      break;
    case("nombre"):
      $sort_col="nomper";
      break;
	case("apellido"):
      $sort_col="apeper";
      break;
	case("código"):
      $sort_col="codnom";
      break;
	case("nómina"):
      $sort_col="desnom";
      break;              
	default : $sort_col="codper";
  }  

  //Asignamos la direccion del ordenamiento
  $sort_dir = 'ASC';
  if (array_key_exists('sort_dir',$_GET))
  {
  	$sort_dir = $_GET['sort_dir'];
  }  
  //Hacemos la peticion de los registros
  $ls_cedper = $_GET["cedper"];
  $ls_nomper = $_GET["nomper"];
  $ls_apeper = $_GET["apeper"];
  $ls_codnom = $_GET["codnom"];
  $lb_hay = $lo_dao->getPersonal("ORDER BY $sort_col $sort_dir LIMIT $offset,$pagesize",$la_datos,$ls_cedper,$ls_nomper,$ls_apeper,$ls_codnom); 
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
	  $la_arr[$li_i++] = $ls_codper  = $la_datos["codper"][$i];
      $la_arr[$li_i++] = $ls_nomper  = $la_datos["nomper"][$i];
	  $la_arr[$li_i++] = $ls_apeper  = $la_datos["apeper"][$i];
	  $la_arr[$li_i++] = $ls_codnom  = $la_datos["codnom"][$i];
	  $la_arr[$li_i++] = $ls_desnom  = $la_datos["desnom"][$i]; 
	  	       
      $la_newarr= "new Array(";
	  for ($li_i=1; $li_i<=count($la_arr); $li_i++)
	  {
	    $la_newarr = $la_newarr . "'" . $la_arr[$li_i] . "'";
	    if ($li_i != count($la_arr))
	    {$la_newarr = $la_newarr . ",";}
	  }
	  $la_newarr = $la_newarr . ")";
	  
      $ls_salida .='<tr class="celdas-blancas">';
	  $ls_salida .='<td align="center" width="100"><a href="javascript:ue_seleccionar('.$la_newarr.');">'.$ls_codper.'</a></td>';
	  $ls_salida .='</tr>';
    }
    $ls_salida .='</rows></response></ajax-response>';		
  }  
}
echo utf8_encode($ls_salida);
?>