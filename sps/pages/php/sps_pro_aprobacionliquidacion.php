<?php
session_start();
header("Cache-Control:no-cache");
header("Pragma:no-cache");

require_once("../../../shared/class_folder/JSON.php");
require_once("../../class_folder/dao/sps_pro_liquidacion_dao.php");

$ls_operacion = $_GET["operacion"];

$lo_json    = new JSON();
$lo_liquidacion_dao = new sps_pro_liquidacion_dao();
$ls_salida  = "";

if ($ls_operacion == "ue_guardar")
{  
	$objeto = str_replace('\"','"',$_GET["objeto"]);
	$lo_liquidacion = $lo_json->decode($objeto);
	$lo_liquidacion_dao->updateLiquidacion($lo_liquidacion,$_GET["insmod"]);
	$ls_salida = $lo_liquidacion_dao->getMensaje();
}
echo utf8_encode($ls_salida);
?>