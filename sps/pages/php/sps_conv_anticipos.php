<?php
session_start();
header("Cache-Control:no-cache");
header("Pragma:no-cache");


require_once("../../class_folder/dao/sps_anticipos_dao.php");

$ls_operacion = $_GET["operacion"];

$lo_anticipos = new sps_anticipos_dao();
$ls_salida  = "";

if ($ls_operacion == "ue_leer_archivo")  
{
  $lo_anticipos->convertirData($_GET["archivo"]);
  $ls_salida = $lo_anticipos->getMensaje();
}

echo utf8_encode($ls_salida);
?>