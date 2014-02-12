<?Php
	session_start();
	header("cache-Control:no-cache");
	header("pragma:no-cache");
	
	require_once("../../../shared/class_folder/JSON.php");
    require_once("../../../sps/class_folder/dao/sps_pro_liquidacion_dao.php");

	$ls_salida       = "";	
	$lb_valido       = false;
	$lo_dao          = new sps_pro_liquidacion_dao();
	$lo_json         = new JSON();
	$ls_operacion    = $_GET["operacion"];
	
	if ($ls_operacion === "ue_detalleliquidacion")
	{   
		$lb_valido   = $lo_dao->getDetalleLiquidacion($_GET["codper"],$_GET["codnom"],$_GET["numliq"],&$pa_datos ); 
		if ($lb_valido)
		{  $ls_salida = $lo_json->encode($pa_datos); }
	}	
	elseif ($ls_operacion == "ue_inicializar")	
	{ 
	  // Causa de Retiro
	  $lb_hay = $lo_dao->getCausaRetiro("ORDER BY codcauret",$la_array);
	  if ($lb_hay)
		$ls_salida .= $lo_json->encode($la_array);
	  // Articulos
	  $ls_salida .= "&";
	  $lb_hay = $lo_dao->getArticulos("ORDER BY id_art",$la_articulo);
	  if ($lb_hay)
		$ls_salida .= $lo_json->encode($la_articulo);
	} 	
	elseif ($ls_operacion == "ue_nuevo")	
	{ 
	  $ls_salida = $lo_dao->getProximoCodigo();
	}
	elseif ($ls_operacion == "ue_deudaanterior")
	{
		$lb_valido = $lo_dao->getDeudaAnterior( $_GET["codper"],$_GET["codnom"],$_GET["fecdes"],&$pa_datos);
		if ($lb_valido) { $ls_salida = $lo_json->encode($pa_datos); }
	}
	elseif ($ls_operacion == "ue_antiguedad")
	{
		$lb_valido = $lo_dao->getAntiguedad( $_GET["codper"],$_GET["codnom"],$_GET["fecdes"],$_GET["fechas"],&$pa_datos);
		if ($lb_valido) { $ls_salida = $lo_json->encode($pa_datos); }
	}
	elseif ($ls_operacion == "ue_incidencias")  
	{
		$lb_valido = $lo_dao->getIncidencias($_GET["ano"],$_GET["tipoper"],$_GET["dedicacion"],$la_datos);
		if ($lb_valido)	{$ls_salida = $lo_json->encode($la_datos);}
	}
	elseif ($ls_operacion == "ue_vacaciones")
	{
		$lb_valido = $lo_dao->getVacaciones( $_GET["codper"],$_GET["codnom"],&$pa_datos );
		if ($lb_valido) { $ls_salida = $lo_json->encode($pa_datos); }
	}
	elseif ($ls_operacion == "ue_bonovacacional")
	{
		$lb_valido = $lo_dao->getBonoVacacional( $_GET["codper"],$_GET["codnom"],&$pa_datos );
		if ($lb_valido) { $ls_salida = $lo_json->encode($pa_datos); }
	}
	elseif ($ls_operacion == "ue_datospersonal")
	{
		$lb_valido = $lo_dao->getDatosPersonal( $_GET["codper"],$_GET["codnom"],&$pa_datos );
		if ($lb_valido) { $ls_salida = $lo_json->encode($pa_datos); }
	}
	elseif ($ls_operacion == "ue_detallearticulo")
	{
		$lb_valido = $lo_dao->getDetalleArticulo( $_GET["id_art"],&$pa_datos );
		if ($lb_valido) { $ls_salida = $lo_json->encode($pa_datos); }
	}
	elseif ($ls_operacion == "ue_guardar")
	{
		$objeto    = str_replace('\"','"', $_GET["objeto"]);
		$lo_object = $lo_json->decode($objeto);
		$lo_dao->guardarLiquidacion($lo_object, $_GET["insmod"]);
	}
	elseif ($ls_operacion == "ue_eliminar")
	{
		$lo_dao->eliminarData($_GET["codper"],$_GET["codnom"],$_GET["numliq"]);
	}
	if(is_object($lo_json)){ unset($lo_json);  }	
	if(is_object($lo_dao)){ unset($lo_dao);  }
	echo utf8_encode($ls_salida);
?>