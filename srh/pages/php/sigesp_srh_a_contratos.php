<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_contratos.php");
	
	require_once("../../class_folder/utilidades/class_funciones_srh.php");

	$io_contratos= new sigesp_srh_c_contratos('../../../');
    $io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_contratos.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=utf8_encode($_SESSION["la_logusr"]);
    $ls_salida = "";

if (isset($_GET['valor']))
{
	    $evento=$_GET['valor'];

		if($evento=="createXML")
		{
			$ls_nroreg="%%";
			$ls_codper="%%";
			$ls_apeper="%%";
			$ls_nomper="%%";
			$ls_codtipcon="%%";
			
			
		    header('Content-type:text/xml');			
			print $io_contratos->uf_srh_buscar_contratos($ls_nroreg,$ls_codper,$ls_apeper,$ls_nomper,$ls_codtipcon);
		}
		
		elseif($evento=="buscar")
		{
			$ls_nroreg="%".utf8_encode($_REQUEST['txtnroreg'])."%";
			$ls_codper="%".utf8_encode($_REQUEST['txtcodper'])."%";
			$ls_apeper="%".utf8_encode($_REQUEST['txtapeper'])."%";
			$ls_nomper="%".utf8_encode($_REQUEST['txtnomper'])."%";
			$ls_codtipcon="%".utf8_encode($_REQUEST['txtcodtipcon'])."%";
			
				
			header('Content-type:text/xml');			
			print $io_contratos->uf_srh_buscar_contratos($ls_nroreg,$ls_codper,$ls_apeper,$ls_nomper,$ls_codtipcon);
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
  $valido= $io_contratos-> uf_srh_guardarContrato ($io_cont,$_POST["insmod"], $la_seguridad);
  if ($valido) {
    if ($_POST["insmod"]=='modificar')
	 {$ls_salida = 'El Contrato fue Actualizado';	}
	else { $ls_salida = 'El Contrato fue Registrado';}
  }
  else {$ls_salida = 'Error al guardar el Contrato';}
 
}
elseif ($ls_operacion == "ue_eliminar")
{  
  $io_contratos->uf_srh_eliminarContrato($_GET["nroreg"], $la_seguridad);
  $ls_salida = 'El Contrato fue Eliminado';
}

elseif ($ls_operacion == "ue_nuevo_codigo")
{  
    $ls_salida = $io_contratos->uf_srh_getProximoCodigo();  

}

  echo utf8_encode($ls_salida);


?>
