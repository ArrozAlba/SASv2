<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_solicitud_adiestramiento.php");
	require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_solicitud= new sigesp_srh_c_solicitud_adiestramiento('../../../');
    $io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_solicitud_adiestramiento.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
    $ls_salida = "";




if (isset($_GET['valor']))
{
	    $evento=$_GET['valor'];

		if($evento=="createXML")
		{
			$ls_nroreg="%%";
			$ls_codprov="%%";
			$ls_des="%%";
			$ls_fecsol1=$_REQUEST['txtfecsoldes'];
			$ls_fecsol2=$_REQUEST['txtfecsolhas'];
		    header('Content-type:text/xml');
			print $io_solicitud->uf_srh_buscar_solicitud_adiestramiento($ls_nroreg,$ls_fecsol1,$ls_fecsol2,$ls_codprov,$ls_des);
		}
		
		elseif($evento=="buscar")
		{
			$ls_nroreg="%".utf8_encode($_REQUEST['txtnroreg'])."%";
			$ls_codprov="%".utf8_encode($_REQUEST['txtcodprov'])."%";
			$ls_des="%".utf8_encode($_REQUEST['txtdes'])."%";
			$ls_fecsol1=$_REQUEST['txtfecsoldes'];
			$ls_fecsol2=$_REQUEST['txtfecsolhas'];
			header('Content-type:text/xml');
			print $io_solicitud->uf_srh_buscar_solicitud_adiestramiento($ls_nroreg,$ls_fecsol1,$ls_fecsol2,$ls_codprov,$ls_des);
		}
	  elseif($evento=="createXML_proveedor")
		{
			$ls_codprov="%%";
			$ls_denprov="%%";
			
		    header('Content-type:text/xml');
			print $io_solicitud->uf_srh_buscar_proveedor($ls_codprov,$ls_denprov);
		}
		elseif($evento=="buscar_proveedor")
		{
			$ls_codprov="%".utf8_encode($_REQUEST['txtcodprov'])."%";
			$ls_denprov="%".utf8_encode($_REQUEST['txtdenprov'])."%";		
			
			header('Content-type:text/xml');
			print $io_solicitud->uf_srh_buscar_proveedor($ls_codprov,$ls_denprov);
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
  $io_sol = $io_json->decode(utf8_decode($objeto));
  $valido = $io_solicitud->uf_srh_guardarsolicitud_adiestramiento($io_sol,$_POST["insmod"], $la_seguridad);
   if ($valido) {
    if ($_POST["insmod"]=='modificar')
	 {$ls_salida = 'La Solicitud de Adiestramiento fue Actualizada';	}
	else { $ls_salida = 'La Solicitud de Adiestramiento fue Registrada';}
  }
  else {$ls_salida = 'Error al guardar la Solicitud de Adiestramiento';}
 
}
elseif ($ls_operacion == "ue_eliminar")
{  
 
 list($valido,$existe)= $io_solicitud->uf_srh_eliminarsolicitud_adiestramiento($_GET["nroreg"], $la_seguridad);
   if ($existe)
  {$ls_salida = 'La Solicitud de Adiestramient no pueden ser eliminada porque esta asociada a una Evaluacion';}
  else 
  {
	  if ($valido)
	  {$ls_salida =  $ls_salida = 'La Solicitud de Adiestramiento fue Eliminada';}
	  else 
	  {$ls_salida = 'Ocurrio un error al eliminar la Solicitud de Adiestramient';}
   }
 
 
}
elseif ($ls_operacion == "ue_nuevo_codigo")
{  
    $ls_salida = $io_solicitud->uf_srh_getProximoCodigo();  

}


echo utf8_encode($ls_salida);


?>
