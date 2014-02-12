<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_revisiones_odi.php");
	
	require_once("../../class_folder/utilidades/class_funciones_srh.php");

	$io_revisiones_odi= new sigesp_srh_c_revisiones_odi('../../../');
    $io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_revisiones_odi.php",$ls_permisos,$la_seguridad,$la_permisos);
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
			print $io_revisiones_odi->uf_srh_buscar_revisiones_odi($ls_nroreg,$ls_fecha1,$ls_fecha2);
		}
		
		elseif($evento=="buscar")
		{
			
			$ls_fecha1=$_REQUEST['txtfechades'];
			$ls_fecha2=$_REQUEST['txtfechahas'];
			$ls_nroreg="%".utf8_encode($_REQUEST['txtnroreg'])."%";
							
			header('Content-type:text/xml');
			print $io_revisiones_odi->uf_srh_buscar_revisiones_odi($ls_nroreg,$ls_fecha1,$ls_fecha2);
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
  list($valido,$lb_valfecha)= $io_revisiones_odi-> uf_srh_guardarrevisiones_odi ($io_obj,$_POST["insmod"], $la_seguridad);
  $codper=$_POST["codper"];
  if (!$lb_valfecha)
  {
  	$ls_salida = 'No puede registrar la revisión de ODI. El personal '.$codper.' tiene una revision registrada en el periodo seleccionado. Revise el Catalogo';	
  }
  else if ($valido) {
    if ($_POST["insmod"]=='modificar')
	 {$ls_salida = 'La Revision de Objetivos de Desempeño Individual fue Actualizada';	}
	else { $ls_salida = 'La Revision de Objetivos de Desempeño Individual fue Registrada';}
  }
  else {$ls_salida = 'Error al guardar la Revision de Objetivos de Desempeño Individual';}
 
}
elseif ($ls_operacion == "ue_eliminar")
{  
  $io_revisiones_odi->uf_srh_eliminarrevisiones_odi($_GET["nroreg"],$_GET["fecha"], $la_seguridad);
  $ls_salida = 'La Revision de Objetivos de Desempeño Individual fue Eliminada';
}
elseif ($ls_operacion == "ue_nuevo_codigo")
{  
    $ls_salida = $io_revisiones_odi->uf_srh_getProximoCodigo();  

}


  echo utf8_encode($ls_salida);


?>
