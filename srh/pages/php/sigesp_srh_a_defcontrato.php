<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_defcontrato.php");
	
	require_once("../../class_folder/utilidades/class_funciones_srh.php");

	$io_defcontrato= new sigesp_srh_c_defcontrato('../../../');
    $io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_defcontrato.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=utf8_encode($_SESSION["la_logusr"]);
    $ls_salida = "";

if (isset($_GET['valor']))
{
	    $evento=$_GET['valor'];

		if($evento=="createXML")
		{
			$ls_codcont="%%";
			$ls_descont="%%";			
			
		    header('Content-type:text/xml');			
			print $io_defcontrato->uf_srh_buscar_defcontrato($ls_codcont,$ls_descont);
		}
		
		elseif($evento=="buscar")
		{
			$ls_codcont="%".utf8_encode($_REQUEST['txtcodcont'])."%";
			$ls_descont="%".utf8_encode($_REQUEST['txtdescont'])."%";		
				
			header('Content-type:text/xml');			
			print $io_defcontrato->uf_srh_buscar_defcontrato($ls_codcont,$ls_descont);
		}
			
	
}


require_once("../../class_folder/utilidades/JSON.php");	
$io_json = new JSON();	


if (array_key_exists("operacion",$_GET))
{
  $ls_operacion = $_GET["operacion"];
}
elseif (array_key_exists("operacion",$_POST))
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
  $io_cont = $io_json->decode(utf8_decode ($objeto));
  $valido= $io_defcontrato-> uf_srh_guardar_defcontrato ($io_cont,$_POST["insmod"], $la_seguridad);
  if ($valido) {
    if ($_POST["insmod"]=='modificar')
	 {$ls_salida = 'La Configuración de Contrato fue Actualizada';	}
	else { $ls_salida = 'La Configuración de Contrato fue Registrada';}
  }
  else {$ls_salida = 'Error al guardar la Configuración de Contrato';}
 
}
elseif ($ls_operacion == "ue_eliminar")
{  
  $io_defcontrato->uf_srh_eliminar_defcontrato($_GET["codcont"], $la_seguridad);
  $ls_salida = 'La Configuración de Contrato fue Eliminada';
}

elseif ($ls_operacion == "ue_nuevo_codigo")
{  
    $ls_salida = $io_defcontrato->uf_srh_getProximoCodigo();  

}

  echo utf8_encode($ls_salida);


?>
