<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_documentos.php");
	
	require_once("../../class_folder/utilidades/class_funciones_srh.php");

	$io_documentos= new sigesp_srh_c_documentos('../../../');
    $io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_documentos.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
    $ls_salida = "";

if (isset($_GET['valor']))
{
	    $evento=$_GET['valor'];

		if($evento=="createXML")
		{
			$ls_nrodoc="%%";
			$ls_dendoc="%%";
			$ls_codtipdoc="%%";
			
			
		    header('Content-type:text/xml');			
			print $io_documentos->uf_srh_buscar_documentos($ls_nrodoc,$ls_dendoc,$ls_codtipdoc);
		}
		
		elseif($evento=="buscar")
		{
			$ls_nrodoc="%".utf8_encode($_REQUEST['txtnrodoc'])."%";
			$ls_dendoc="%".utf8_encode($_REQUEST['txtdendoc'])."%";
			$ls_codtipdoc="%".utf8_encode($_REQUEST['txtcodtipdoc'])."%";
			
				
			header('Content-type:text/xml');
			
			print $io_documentos->uf_srh_buscar_documentos($ls_nrodoc,$ls_dendoc,$ls_codtipdoc);
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
 $ls_operacion="";
 }


if ($ls_operacion == "ue_guardar")
{  
  $objeto = str_replace('\"','"',$_POST["objeto"]);
  $io_enf = $io_json->decode(utf8_decode ($objeto));
  $valido= $io_documentos-> uf_srh_guardarDocumentos ($io_enf,$_POST["insmod"], $la_seguridad);
   if ($valido) {
    if ($_POST["insmod"]=='modificar')
	 {$ls_salida = 'El Documento Legal fue fue Actualizado';	}
	else { $ls_salida = 'El Documento Legal fue fue Registrado';}
  }
  else {$ls_salida = 'Error al guardar el Documento Legal';}
 
}
elseif ($ls_operacion == "ue_eliminar")
{  
  $io_documentos->uf_srh_eliminarDocumentos($_GET["nrodoc"], $la_seguridad);
  $ls_salida = 'El Documento Legal fue Eliminado';
}

elseif ($ls_operacion == "ue_nuevo_codigo")
{  
    $ls_salida = $io_documentos->uf_srh_getProximoCodigo();  

}

  echo utf8_encode($ls_salida);


?>
