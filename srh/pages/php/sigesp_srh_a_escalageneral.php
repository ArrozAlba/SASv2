<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_escalageneral.php");
	
	require_once("../../class_folder/utilidades/class_funciones_srh.php");

	$io_escalageneral= new sigesp_srh_c_escalageneral('../../../');
    $io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_escalageneral.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
    $ls_salida = "";




if (isset($_GET['valor']))
{
	    $evento=$_GET['valor'];

		if($evento=="createXML")
		{
			$ls_codesc="%%";
			$ls_denesc="%%";
			
		    header('Content-type:text/xml');
			print  $io_escalageneral->uf_srh_buscar_escalageneral($ls_codesc,$ls_denesc);
		}
		
		elseif($evento=="buscar")
		{
			$ls_codesc="%".utf8_encode($_REQUEST['txtcodesc'])."%";
			$ls_denesc="%".utf8_encode($_REQUEST['txtdenesc'])."%";
				
			header('Content-type:text/xml');
			print $io_escalageneral->uf_srh_buscar_escalageneral($ls_codesc,ls_denesc);
		}
			
	
}



require_once("../../class_folder/utilidades/JSON.php");	
$io_json = new JSON();	


if (array_key_exists("operacion",$_GET))
{
  $ls_operacion = $_GET["operacion"];
}
else
{
 
	if (array_key_exists("operacion",$_POST))
	{
	  $ls_operacion = $_POST["operacion"];
	}
	else
	{
	  $ls_operacion = "";
	}
}

if ($ls_operacion == "ue_guardar")
{  
  $objeto = str_replace('\"','"',$_POST["objeto"]);
  $io_esc = $io_json->decode(utf8_decode ($objeto));
  $valido= $io_escalageneral-> uf_srh_guardar_escalageneral ($io_esc,$_POST["insmod"], $la_seguridad);
   if ($valido) {
    if ($_POST["insmod"]=='modificar')
	 {$ls_salida = 'La Escala General fue Actualizada';	}
	else { $ls_salida = 'La Escala General fue Registrada';}
  }
  else {$ls_salida = 'Error al guardar la Escala General';}
 
}
elseif ($ls_operacion == "ue_eliminar")
{  
  list($valido,$existe)= $io_escalageneral->uf_srh_eliminar_escalageneral($_GET["codesc"], $la_seguridad);
  if ($existe)
  {$ls_salida = 'La Escala de Evaluacion no puede ser eliminada porque esta asociada a un Tipo de Evaluacion';}
  else 
  {
	  if ($valido)
	  {$ls_salida = 'La Escala de Evaluacion fue Eliminada';}
	  else 
	  {$ls_salida = 'Ocurrio un error al eliminar la escala de Evaluacion';}
  }
}
elseif ($ls_operacion == "ue_nuevo_codigo")
{  
    $ls_salida = $io_escalageneral->uf_srh_getProximoCodigo();  

}

  echo utf8_encode($ls_salida);


?>
