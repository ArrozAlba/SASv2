<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_registro_metas.php");
	require_once("../../class_folder/utilidades/class_funciones_srh.php");
	
	$io_registro= new sigesp_srh_c_registro_metas('../../../');
    $io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_registro_metas.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
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
			$ls_fecreg1=$_REQUEST['txtfechades'];
			$ls_fecreg2=$_REQUEST['txtfechahas'];
			
			header('Content-type:text/xml');
			print ($io_registro->uf_srh_buscar_registro_metas($ls_nroreg,$ls_codper,$ls_apeper,$ls_nomper,$ls_fecreg1,$ls_fecreg2));
			
		}
		
		elseif($evento=="buscar")
		{
			$ls_nroreg="%".utf8_encode($_REQUEST['txtnroreg'])."%";
			$ls_codper="%".utf8_encode($_REQUEST['txtcodper'])."%";
			$ls_apeper="%".utf8_encode($_REQUEST['txtapeper'])."%";
			$ls_nomper="%".utf8_encode($_REQUEST['txtnomper'])."%";
			$ls_fecreg1=$_REQUEST['txtfechades'];
			$ls_fecreg2=$_REQUEST['txtfechahas'];
			header('Content-type:text/xml');
			print ($io_registro->uf_srh_buscar_registro_metas($ls_nroreg,$ls_codper,$ls_apeper,$ls_nomper,$ls_fecreg1,$ls_fecreg2));
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
{ $ls_operacion = "";}

if ($ls_operacion == "ue_guardar")
{  
  $objeto = str_replace('\"','"',$_POST["objeto"]);
  $io_reg = $io_json->decode(utf8_decode($objeto));
  $valido = $io_registro-> uf_srh_guardarregistro_metas($io_reg,$_POST["insmod"], $la_seguridad);
   if ($valido) {
    if ($_POST["insmod"]=='modificar')
	 {$ls_salida = 'El Registro de Metas de Personal fue Actualizado';	}
	else { $ls_salida = 'El Registro de Metas de Personal fue Registrado';}
  }
  else {$ls_salida = 'Error al guardar el Registro de Metas de Personal';}
 
}
elseif ($ls_operacion == "ue_eliminar")
{  
  
   list($existe,$valido)= $io_registro->uf_srh_eliminarregistro_metas($_GET["nroreg"], $la_seguridad);
   if ($existe)
  {$ls_salida = 'El Registro de Metas de Personal no pueden ser eliminado porque esta asociada a una Revsion';}
  else 
  {
	  if ($valido)
	  {$ls_salida = 'El Registro de Metas de Personal fue Eliminado';}
	  else 
	  {$ls_salida = 'Ocurrio un error al eliminar El Registro de Metas de Personal';}
  }
}
elseif ($ls_operacion == "ue_nuevo_codigo")
{  
    $ls_salida = $io_registro->uf_srh_getProximoCodigo();  

}


  echo utf8_encode($ls_salida);


?>
