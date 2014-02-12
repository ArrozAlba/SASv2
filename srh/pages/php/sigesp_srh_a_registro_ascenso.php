<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_registro_ascenso.php");
	
	require_once("../../class_folder/utilidades/class_funciones_srh.php");

	$io_registro_ascenso= new sigesp_srh_c_registro_ascenso('../../../');
    $io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_registro_ascenso.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
    $ls_salida = "";




if (isset($_GET['valor']))
{
	    $evento=$_GET['valor'];

		if($evento=="createXML")
		{
			$ls_codper="%%";
			$ls_nroreg="%%";
			$ls_codcon="%%";
			
		    header('Content-type:text/xml');			
			print $io_registro_ascenso->uf_srh_buscar_registro_ascenso($ls_nroreg,$ls_codper,$ls_codcon);
		}
		
		elseif($evento=="buscar")
		{
			$ls_codper="";
			$ls_codcon="%".utf8_encode($_REQUEST['txtcodcon'])."%";
			$ls_nroreg="%".utf8_encode($_REQUEST['txtnroreg'])."%";
				
			header('Content-type:text/xml');			
			print $io_registro_ascenso->uf_srh_buscar_registro_ascenso($ls_nroreg,$ls_codper,$ls_codcon);
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
	  $ls_operacion ="";
}


if ($ls_operacion == "ue_guardar")
{  
  $objeto = str_replace('\"','"',$_POST["objeto"]);
  $io_req = $io_json->decode(utf8_decode ($objeto));
  $valido = $io_registro_ascenso-> uf_srh_guardarregistro_ascenso ($io_req,$_POST["insmod"], $la_seguridad);
   if ($valido) {
    if ($_POST["insmod"]=='modificar')
	 {$ls_salida = 'El Registro de Postulados para el ascenso  fue Actualizado';	}
	else { $ls_salida = 'El Registro de Postulados para el ascenso fue Registrado';}
  }
  else {$ls_salida = 'Error al guardar el Registro de Postulados para el ascenso';}
 
}
elseif ($ls_operacion == "ue_eliminar")
{  
  $io_registro_ascenso->uf_srh_eliminarregistro_ascenso($_GET["nroreg"], $la_seguridad);
  $ls_salida = 'El Registro de Postulados para el ascenso fue Eliminado';
}
elseif ($ls_operacion == "ue_nuevo_codigo")
{  
    $ls_salida = $io_registro_ascenso->uf_srh_getProximoCodigo();  

}


  echo utf8_encode($ls_salida);


?>
