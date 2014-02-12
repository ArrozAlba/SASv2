<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_revision_metas.php");
	
	require_once("../../class_folder/utilidades/class_funciones_srh.php");

	$io_revision_metas= new sigesp_srh_c_revision_metas('../../../');
    $io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_revision_metas.php",$ls_permisos,$la_seguridad,$la_permisos);
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
			print $io_revision_metas->uf_srh_buscar_revision_metas($ls_nroreg,$ls_fecha1,$ls_fecha2);
		}
		
		elseif($evento=="buscar")
		{
		
			$ls_fecha1=$_REQUEST['txtfechades'];
			$ls_fecha2=$_REQUEST['txtfechahas'];
			$ls_nroreg="%".utf8_encode($_REQUEST['txtnroreg'])."%";
							
			header('Content-type:text/xml');
			print $io_revision_metas->uf_srh_buscar_revision_metas($ls_nroreg,$ls_fecha1,$ls_fecha2);
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
  $valido = $io_revision_metas-> uf_srh_guardarrevision_metas ($io_obj,$_POST["insmod"], $la_seguridad);
   if ($valido) {
    if ($_POST["insmod"]=='modificar')
	 {$ls_salida = 'La Revision de Metas de Personal fue Actualizada';	}
	else { $ls_salida = 'La Revision de Metas de Personal fue Registrada';}
  }
  else {$ls_salida = 'Error al guardar la Revision de Metas de Personal';}
 
}
elseif ($ls_operacion == "ue_eliminar")
{  
  $io_revision_metas->uf_srh_eliminarrevision_metas($_GET["nroreg"],$_GET["fecha"], $la_seguridad);
  $ls_salida = 'La Revision de Metas de Personal fue Eliminada';
}
  echo utf8_encode($ls_salida);


?>
