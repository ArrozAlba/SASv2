<?Php
	session_start();
	header("cache-Control:no-cache");
	header("pragma:no-cache");
	
	require_once("../../../shared/class_folder/JSON.php");
    require_once("../../../sps/class_folder/utilidades/class_array_to_load.php");		
	require_once("../../../sps/class_folder/dao/sps_def_tasainteres_dao.php");
	
	$ls_salida    = "";	
	$ls_operacion = $_GET["operacion"];
	
	$lo_json     = new JSON();
	$lo_dao      = new sps_def_tasainteres_dao();
    $lo_arreglos = new class_array_to_load();
    
    if ($ls_operacion == "ue_inicializar")	
	{
      //Años
	  $la_anos = $lo_arreglos->getArreglo("anos","","",0);
	  $ls_salida .= $lo_json->encode($la_anos);	    	  			
	  //Meses
	  $ls_salida .= "&";
	  $la_meses = $lo_arreglos->getArreglo("meses");
	  $ls_salida .= $lo_json->encode($la_meses);
	} 	
	elseif($ls_operacion == "ue_nuevo")
	{  
		$ls_salida = "";
	}
	elseif ($ls_operacion == "ue_guardar")
	{
		$objeto  = str_replace('\"','"', $_GET["objeto"]);
		$lo_data = $lo_json->decode($objeto);
		$lo_dao->guardarData($lo_data, $_GET["insmod"]);
	}
	elseif ($ls_operacion == "ue_eliminar")
	{
		$lo_dao->eliminarData($_GET["mes"],$_GET["ano"]);
	}
    if( is_object($lo_json) ) { unset($lo_json);  }	
	if( is_object($lo_dao) ) { unset($lo_dao);  }	
	
	echo utf8_encode($ls_salida);
?>
