<?Php
	session_start();
	header("cache-Control:no-cache");
	header("pragma:no-cache");
	
	require_once("../../../shared/class_folder/JSON.php");
    require_once("../../../sps/class_folder/dao/sps_pro_antig_nomina_dao.php");

	$ls_salida       = "";	
	$ls_operacion    = $_POST["operacion"];
	$lo_json         = new JSON();
	$lo_dao          = new sps_pro_antig_nomina_dao();
	
	if ($ls_operacion == "ue_fecha_ingreso")
	{   
		$lb_valido = $lo_dao->getFechaIngreso($_POST["codper"],$_POST["codnom"],$la_datos);
		if ($lb_valido)	{$ls_salida .= $lo_json->encode($la_datos);}
	}
	elseif ($ls_operacion == "ue_anticipos")  
	{
		$lb_valido = $lo_dao->getAnticipos($_POST["codper"],$_POST["codnom"],$_POST["fecdes"],$_POST["fechas"],$la_datos);
		if ($lb_valido)	{$ls_salida = $lo_json->encode($la_datos);}
	}
	elseif ($ls_operacion== "ue_antig_nomina")	
	{
		$lb_valido = $lo_dao->getAntigNomina($_POST["codper"],$_POST["codnom"],$_POST["fecdes"],$_POST["fechas"],$la_datos);
		if ($lb_valido)	
		{$ls_salida.= $lo_json->encode($la_datos);}
	}
	elseif ($ls_operacion == "ue_detalleantiguedad")
	{
		$lb_valido = $lo_dao->getAntiguedad($_POST["codper"],$_POST["codnom"],$la_datos);
		if ($lb_valido)	
		{$ls_salida = $lo_json->encode($la_datos);}
	}
	elseif ($ls_operacion == "ue_calcular_interes")
	{
		$objeto    = str_replace('\"','"', $_POST["objeto"]);
		$lo_object = $lo_json->decode($objeto);
		$lb_valido = $lo_dao->calcularInteres($lo_object, $la_datos);
		if ($lb_valido)	
		{$ls_salida.= $lo_json->encode($la_datos);}
	}
	elseif ($ls_operacion == "ue_guardar")
	{
		$objeto    = str_replace('\"','"', $_POST["objeto"]);
		$lo_object = $lo_json->decode($objeto);
		$lo_dao->guardarAntiguedad($lo_object, $_POST["insmod"]);
	}
	elseif ($ls_operacion == "ue_eliminar")
	{
		$lo_dao->eliminarData($_POST["codper"],$_POST["codnom"]);
	}

	if( is_object($lo_json) ) { unset($lo_json);  }	
	if( is_object($lo_dao) ) { unset($lo_dao);  }
	echo utf8_encode($ls_salida);
?>