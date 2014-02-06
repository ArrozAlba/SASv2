<?php
session_start();
header("Cache-Control:no-cache");
header("Pragma:no-cache");

require_once("../../../shared/class_folder/JSON.php");
require_once("../../class_folder/dao/sps_pro_anticipo_dao.php");

$ls_operacion = $_GET["operacion"];

$lo_json    = new JSON();
$lo_anticipo_dao = new sps_pro_anticipo_dao();
$ls_salida  = "";

if ($ls_operacion == "ue_guardar")
{  
	$objeto = str_replace('\"','"',$_GET["objeto"]);
	$lo_anticipo = $lo_json->decode($objeto);
	$lo_anticipo_dao->updateAnticipo($lo_anticipo,$_GET["insmod"]);
	$ls_salida = $lo_anticipo_dao->getMensaje();
}
echo utf8_encode($ls_salida);
?>