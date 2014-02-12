<?php
session_start();
header("Cache-Control:no-cache");
header("Pragma:no-cache");

require_once("../../../shared/class_folder/JSON.php");
require_once("../../class_folder/dao/sps_def_cartaanticipo_dao.php");

$ls_operacion = $_GET["operacion"];

$lo_json = new JSON();
$lo_dao = new sps_def_cartaanticipo_dao();
$ls_salida  = "";

if ($ls_operacion == "ue_nuevo")
{
  $ls_salida = $lo_dao->getProximoCodigo();  
}
elseif ($ls_operacion == "ue_guardar")
{  
  $objeto = str_replace('\"','"',$_GET["objeto"]);
  $lo_cartaanticipo = $lo_json->decode($objeto);
  $lo_dao->guardarData($lo_cartaanticipo,$_GET["insmod"]);
  $ls_salida = $lo_dao->getMensaje();
}
elseif ($ls_operacion == "ue_eliminar")
{  
  $lo_dao->eliminarData($_GET["codigo"]);
  $ls_salida = $lo_dao->getMensaje();
}
echo utf8_encode($ls_salida);
?>