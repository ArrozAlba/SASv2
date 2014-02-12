<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_necesidad_adiestramiento.php");
	
	require_once("../../class_folder/utilidades/class_funciones_srh.php");

	$io_necesidad_adiestramiento= new sigesp_srh_c_necesidad_adiestramiento('../../../');
    $io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_necesidad_adiestramiento.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
    $ls_salida = "";




if (isset($_GET['valor']))
{
	    $evento=$_GET['valor'];

		if($evento=="createXML")
		{
			
			$ls_fecha1=$_REQUEST['txtfechades'];
			$ls_fecha2=$_REQUEST['txtfechahas'];
	        $ls_nroreg="%%";
			
		    header('Content-type:text/xml');
			print $io_necesidad_adiestramiento->uf_srh_buscar_necesidad_adiestramiento($ls_nroreg,$ls_fecha1,$ls_fecha2);
		}
		
		elseif($evento=="buscar")
		{
			$ls_fecha1=$_REQUEST['txtfechades'];
			$ls_fecha2=$_REQUEST['txtfechahas'];
			$ls_nroreg="%".utf8_encode($_REQUEST['txtnroreg'])."%";
							
			header('Content-type:text/xml');
			print $io_necesidad_adiestramiento->uf_srh_buscar_necesidad_adiestramiento($ls_nroreg,$ls_fecha1,$ls_fecha2);
		}
			
	
}



require_once("../../class_folder/utilidades/JSON.php");	
$io_json = new JSON();	


if (array_key_exists("operacion",$_GET))
{
  $ls_operacion = $_GET["operacion"];
}
else if (array_key_exists("operacion",$_POST))
{
  $ls_operacion = $_POST["operacion"];
}
else 
{
  $ls_operacion = "";
}


if ($ls_operacion == "ue_guardar")
{  
  $objeto = str_replace('\"','"',$_POST["objeto"]);
  $io_obj = $io_json->decode(utf8_decode($objeto));
  $valido = $io_necesidad_adiestramiento-> uf_srh_guardar_necesidad_adiestramiento ($io_obj,$_POST["insmod"], $la_seguridad); 
   if ($valido) {
    if ($_POST["insmod"]=='modificar')
	 {$ls_salida = 'La Necesidad de Adiestramiento fue Actualizada';	}
	else { $ls_salida = 'La Necesidad de Adiestramiento fue Registrada';}
  }
  else {$ls_salida = 'Error al Guardar la Necesidad de Adiestramiento';}
 
}
elseif ($ls_operacion == "ue_eliminar")
{  
  $io_necesidad_adiestramiento->uf_srh_eliminar_necesidad_adiestramiento($_GET["nroreg"], $la_seguridad);
  $ls_salida = 'La Necesidad de Adiestramiento fue Eliminada';
}
elseif ($ls_operacion == "ue_nuevo_codigo")
{  
    $ls_salida =$io_necesidad_adiestramiento->uf_srh_getProximoCodigo();  

}



  echo utf8_encode($ls_salida);


?>
