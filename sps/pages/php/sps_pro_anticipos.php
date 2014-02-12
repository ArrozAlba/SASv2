<?php
session_start();
header("Cache-Control:no-cache");
header("Pragma:no-cache");

require_once("../../../shared/class_folder/JSON.php");
require_once("../../class_folder/dao/sps_pro_anticipo_dao.php");

$ls_salida  = "";
$lo_json    = new JSON();
$lo_anticipo_dao = new sps_pro_anticipo_dao();
$ls_operacion = $_GET["operacion"];

if ($ls_operacion == "ue_guardar")
{  
	$objeto = str_replace('\"','"',$_GET["objeto"]);
	$lo_anticipo = $lo_json->decode($objeto);
	$lo_anticipo_dao->guardarData($lo_anticipo,$_GET["insmod"]);
}
elseif ($ls_operacion == "ue_eliminar")
{  
  $lo_anticipo_dao->eliminarData($_GET["codper"],$_GET["codnom"],$_GET["fecantper"] );
}
elseif ($ls_operacion === "ue_antiguedad")
{
    $lb_valido = $lo_anticipo_dao->buscarAntiguedad( $_GET["codper"],$_GET["codnom"],&$pa_datos);
	if ($lb_valido) { $ls_salida = $lo_json->encode($pa_datos); }
}
echo utf8_encode($ls_salida);
?>