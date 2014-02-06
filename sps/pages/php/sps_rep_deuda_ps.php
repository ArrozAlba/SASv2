<?Php
	session_start();
	header("cache-Control:no-cache");
	header("pragma:no-cache");
	
	require_once("../../../shared/class_folder/JSON.php");
    require_once("../../../sps/class_folder/dao/sps_pro_antiguedad_dao.php");
	
	$ls_salida       = "";	
	$ls_operacion    = $_GET["operacion"];
	$lo_json         = new JSON();
	$lo_dao          = new sps_pro_antiguedad_dao();
   	
	
	if ($ls_operacion == "ue_inicializar")	
	{
	  // Nóminas
	  $lb_hay = $lo_dao->getNominas("ORDER BY codnom",$la_nomina);
	  if ($lb_hay)
		$ls_salida .= $lo_json->encode($la_nomina);
	}
	   
	
	if( is_object($lo_json) ) { unset($lo_json);  }	
	if( is_object($lo_dao) ) { unset($lo_dao);  }
	echo utf8_encode($ls_salida);
?>