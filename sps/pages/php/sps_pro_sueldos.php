<?Php
	session_start();
	header("cache-Control:no-cache");
	header("pragma:no-cache");
	
	require_once("../../../shared/class_folder/JSON.php");
        require_once("../../../sps/class_folder/dao/sps_pro_sueldos_dao.php");
        require_once("../../../sps/class_folder/dao/sps_def_configuracion_dao.php");
	
	$ls_salida       = "";	
	$ls_operacion    = $_GET["operacion"];
	$lo_json         = new JSON();
	$lo_dao          = new sps_pro_sueldos_dao();
	$lo_config       = new sps_def_configuracion_dao();
   	
	if ($ls_operacion == "ue_guardar")
	{
		$objeto    = str_replace('\"','"', $_GET["objeto"]);
		$lo_object = $lo_json->decode($objeto);
		$lo_dao->guardarSueldos($lo_object, $_GET["insmod"]);
	}
	elseif ($ls_operacion == "ue_eliminar")
	{
		$lo_dao->eliminarData($_GET["codper"],$_GET["codnom"]);
	}
	elseif ($ls_operacion == "ue_sueldos_nomina")
	{  
		$lb_existen = $lo_dao->getSueldosNomina($_GET["codper"],$_GET["codnom"],$la_datos);
		if ($lb_existen)
		{$ls_salida .= $lo_json->encode($la_datos);}
	}
        elseif ($ls_operacion == "ue_chequear_sueldos")
	{  
		$lb_existen = $lo_dao->getDetallesSueldos($_GET["codper"],$_GET["codnom"],$la_datos);
		if ($lb_existen)
		{$ls_salida .= $lo_json->encode($la_datos);}
	}
	elseif ($ls_operacion == "ue_configuracion")
	{
		$lb_valido = $lo_config->getConfiguracion($la_datos);
		if ($lb_valido)	{$ls_salida = $lo_json->encode($la_datos);}
	}
	
	if( is_object($lo_json) ) { unset($lo_json);  }	
	if( is_object($lo_dao) ) { unset($lo_dao);  }
	if( is_object($lo_config) ) { unset($lo_config);  }
	echo utf8_encode($ls_salida);
?>
