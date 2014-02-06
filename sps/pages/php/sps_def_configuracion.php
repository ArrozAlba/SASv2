<?Php
	session_start();
	header("cache-Control:no-cache");
	header("pragma:no-cache");
	
	require_once("../../../shared/class_folder/JSON.php");
    require_once("../../../sps/class_folder/dao/sps_def_configuracion_dao.php");
	
	$ls_salida    = "";	
	$ls_operacion = $_GET["operacion"];
	
	$lo_json     = new JSON();
	$lo_dao      = new sps_def_configuracion_dao();
        
    if ($ls_operacion == "ue_inicializar")	
	{
        //Hacemos la peticion de los registros
  		$lb_hay = $lo_dao->getData("ORDER BY id",$la_datos);
        if ($lb_hay)
		{
			$li_registros = (count($la_datos,COUNT_RECURSIVE)/count($la_datos))-1;
			for ($i=1; $i<=$li_registros; $i++)
			{
			  $li_i = 1;
			  $la_arr = array();
			  $la_arr[$li_i++] = $ls_id     = $la_datos["id"][$i];
			  $la_arr[$li_i++] = $ls_porant = $la_datos["porant"][$i];
			  $la_arr[$li_i++] = $ls_estsue = $la_datos["estsue"][$i];
			  $la_arr[$li_i++] = $ls_estincbon = $la_datos["estincbon"][$i];
			  $la_arr[$li_i++] = $ls_sc_cta_ps = $la_datos["sc_cuenta_ps"][$i];
			  $la_arr[$li_i++] = $ls_emp_fijo_ps  = $la_datos["sig_cuenta_emp_fijo_ps"][$i];
			  $la_arr[$li_i++] = $ls_emp_fijo_vac = $la_datos["sig_cuenta_emp_fijo_vac"][$i];
			  $la_arr[$li_i++] = $ls_emp_fijo_agu = $la_datos["sig_cuenta_emp_fijo_agu"][$i];
			  $la_arr[$li_i++] = $ls_obr_fijo_ps  = $la_datos["sig_cuenta_obr_fijo_ps"][$i];
			  $la_arr[$li_i++] = $ls_obr_fijo_vac = $la_datos["sig_cuenta_obr_fijo_vac"][$i];
			  $la_arr[$li_i++] = $ls_obr_fijo_agu = $la_datos["sig_cuenta_obr_fijo_agu"][$i];
			  $la_arr[$li_i++] = $ls_emp_cont_ps  = $la_datos["sig_cuenta_emp_cont_ps"][$i];
			  $la_arr[$li_i++] = $ls_emp_cont_vac = $la_datos["sig_cuenta_emp_cont_vac"][$i];
			  $la_arr[$li_i++] = $ls_emp_cont_agu = $la_datos["sig_cuenta_emp_cont_agu"][$i];
			  $la_arr[$li_i++] = $ls_emp_esp_ps   = $la_datos["sig_cuenta_emp_esp_ps"][$i];
			  $la_arr[$li_i++] = $ls_emp_esp_vac  = $la_datos["sig_cuenta_emp_esp_vac"][$i];
			  $la_arr[$li_i++] = $ls_emp_esp_agu  = $la_datos["sig_cuenta_emp_esp_agu"][$i];
			  
			  $la_newarr = array();
			  for ($li_i=1; $li_i<=count($la_arr); $li_i++)
			  {
			   	if ($li_i==1)
				{ 
					$la_newarr = $la_arr[$li_i];
				}
				else
				{	
				   $la_newarr = $la_newarr.$la_arr[$li_i];
				}
				if ($li_i != count($la_arr))
				{$la_newarr = $la_newarr.",";}
			  } 
			  
			}
			$ls_salida = $la_newarr;		
		  }  
	} 	
	elseif ($ls_operacion == "ue_guardar")
	{
		$objeto  = str_replace('\"','"', $_GET["objeto"]);
		$lo_data = $lo_json->decode($objeto);
		$lo_dao->guardarData($lo_data, $_GET["insmod"]);
	}
	if( is_object($lo_json) ) { unset($lo_json);  }	
	if( is_object($lo_dao) ) { unset($lo_dao);  }	
	
	echo utf8_encode($ls_salida);
?>