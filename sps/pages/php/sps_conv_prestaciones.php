<?php
session_start();
header("Cache-Control:no-cache");
header("Pragma:no-cache");


require_once("../../class_folder/dao/sps_convertidor_dao.php");

$ls_operacion = $_GET["operacion"];

$lo_convertidor = new sps_convertidor_dao();
$ls_salida  = "";

if ($ls_operacion == "ue_leer_archivo")  
{
  $lo_convertidor->convertirData($_GET["archivo"]);
  $ls_salida = $lo_convertidor->getMensaje();
}

echo utf8_encode($ls_salida);
?>