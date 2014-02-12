<?Php
	session_start();
	header("cache-Control:no-cache");
	header("pragma:no-cache");
	
	require_once("../../../shared/class_folder/JSON.php");
  	require_once("../../../sps/class_folder/dao/sps_def_articulos_dao.php");
	
	$ls_salida       = "";	
	$ls_operacion    = $_GET["operacion"];
	$lo_json         = new JSON();
	$lo_dao          = new sps_def_articulos_dao();
	
	if ($ls_operacion == "ue_chequear_articulo")  
	{  
		$lb_existen = $lo_dao->getDetallesArticulos($_GET["id_art"],$_GET["numart"],$_GET["fecvig"],$la_datos);
		if ($lb_existen)
		{$ls_salida .= $lo_json->encode($la_datos);}
	}
	elseif ($ls_operacion == "ue_nuevo")
	{  
		$ls_salida = $lo_dao->getProximoCodigo();
	}
	elseif ($ls_operacion == "ue_guardar")
	{
		$objeto    = str_replace('\"','"', $_GET["objeto"]);
		$lo_object = $lo_json->decode($objeto);
		$lo_dao->guardarArticulos($lo_object, $_GET["insmod"]);
	}
	elseif ($ls_operacion == "ue_eliminar")
	{  
	  $lo_dao->eliminarData($_GET["id_art"],$_GET["numart"],$_GET["fecvig"]);
	}
    //if( is_object($lo_json) ) { unset($lo_json);  }	
	//if( is_object($lo_dao) ) { unset($lo_dao);  }
	echo utf8_encode($ls_salida);
?>