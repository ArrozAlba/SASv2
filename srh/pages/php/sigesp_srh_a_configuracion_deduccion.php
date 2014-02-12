<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_tipodeduccion.php");
    require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_deduccion=new sigesp_srh_c_tipodeduccion('../../../');
	$io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_configuracion_deduccion.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_salida = "";




if (isset($_GET['valor']))
{
	    $evento=$_GET['valor'];

		if($evento=="buscar")
			{       
			        $ls_codtipded="%".utf8_encode($_REQUEST['txtcodtipded'])."%";
	                $ls_dentipded="%".utf8_encode($_REQUEST['txtdentipded'])."%";
					
					header('Content-type:text/xml');
					print $io_deduccion->uf_srh_buscar_configuracion_deduccion($ls_codtipded, $ls_dentipded);
					
			}
			elseif($evento=="createXML")
			{

    				$ls_codtipded="%%";
	                $ls_dentipded="%%";

					header('Content-type:text/xml');
					print $io_deduccion->uf_srh_buscar_configuracion_deduccion($ls_codtipded, $ls_dentipded);
					
					
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
  $io_req = $io_json->decode(utf8_decode ($objeto));
  $valido = $io_deduccion-> uf_srh_guardar_configuracion_deduccion ($io_req,$la_seguridad);
  if ($valido) {
    if ($_POST["insmod"]=='modificar')
	 {$ls_salida = 'La Configuracion del Tipo de Deduccion fue Actualizada';	}
	else { $ls_salida = 'La Configuracion del Tipo de Deduccion fue Registrada';}
  }
  else {$ls_salida = 'Error al guardar La Configuracion del Tipo de Deduccion';}
 
}
elseif ($ls_operacion == "ue_eliminar")
{  
  $io_deduccion->uf_srh_eliminar_dt_configuracion_deduccion($_GET["codtipded"], $la_seguridad);
  $ls_salida = 'La Configuracion del Tipo de Deduccion  fue Eliminada';
}


  echo utf8_encode($ls_salida);


?>

