<?php
session_start();
header("Cache-Control:no-cache");
header("Pragma:no-cache");

require_once("../../../shared/class_folder/JSON.php");
require_once("../../class_folder/dao/sps_pro_deudaanterior_dao.php");

$ls_operacion = $_GET["operacion"];

$lo_json    = new JSON();
$lo_deudaanterior_dao = new sps_pro_deudaanterior_dao();
$ls_salida  = "";


if ($ls_operacion == "ue_guardar")
{  
	$objeto = str_replace('\"','"',$_GET["objeto"]);
	$lo_deudaanterior = $lo_json->decode($objeto);
	$lo_deudaanterior_dao->guardarData($lo_deudaanterior,$_GET["insmod"]);
	$ls_salida = $lo_deudaanterior_dao->getMensaje();
}
elseif ($ls_operacion == "ue_eliminar")
{  
  $lo_deudaanterior_dao->eliminarData($_GET["codper"],$_GET["codnom"],$_GET["feccordeuant"] );
  $ls_salida = $lo_deudaanterior_dao->getMensaje();
}
echo utf8_encode($ls_salida);
?>