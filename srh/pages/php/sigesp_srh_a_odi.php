<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_odi.php");
	
	require_once("../../class_folder/utilidades/class_funciones_srh.php");

	$io_odi= new sigesp_srh_c_odi('../../../');
    $io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_odi.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
    $ls_salida = "";




if (isset($_GET['valor']))
{
	    $evento=$_GET['valor'];

		if($evento=="createXML")
		{
			$ls_nroreg="%%";
			$ls_fecha1=$_REQUEST['txtfechades'];
			$ls_fecha2=$_REQUEST['txtfechahas'];
			
		    header('Content-type:text/xml');
			print $io_odi->uf_srh_buscar_odi($ls_nroreg,$ls_fecha1,$ls_fecha2);
		}
		
		elseif($evento=="buscar")
		{
			$ls_nroreg="%".utf8_encode($_REQUEST['txtnroreg'])."%";
			$ls_fecha1=$_REQUEST['txtfechades'];
			$ls_fecha2=$_REQUEST['txtfechahas'];	
			header('Content-type:text/xml');
			print $io_odi->uf_srh_buscar_odi($ls_nroreg,$ls_fecha1,$ls_fecha2);
		}
			
	
}



require_once("../../class_folder/utilidades/JSON.php");	
$io_json = new JSON();	


if (array_key_exists("operacion",$_GET))
{
  $ls_operacion = $_GET["operacion"];
}
else if(array_key_exists("operacion",$_POST))
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
  $valido = $io_odi-> uf_srh_guardarodi ($io_obj,$_POST["insmod"], $la_seguridad);
   if ($valido) {
    if ($_POST["insmod"]=='modificar')
	 {$ls_salida = 'Los Objetivos de Desempeño Individual fueron Actualizados';	}
	else { $ls_salida = 'Los Objetivos de Desempeño Individual fueron Registrados';}
  }
  else {$ls_salida = 'Error al guardar Los Objetivos de Desempeño Individual';}
 
}
elseif ($ls_operacion == "ue_eliminar")
{  
   list($existe,$valido)= $io_odi->uf_srh_eliminarodi($_GET["nroreg"], $la_seguridad);
   if ($existe)
  {$ls_salida = 'Los Objetivos de Desempeño Individual no pueden ser eliminados porque esta asociada a una Revsion';}
  else 
  {
	  if ($valido)
	  {$ls_salida = 'Los Objetivos de Desempeño Individual fueron Eliminados';}
	  else 
	  {$ls_salida = 'Ocurrio un error al eliminar los Objetivos de Desempeño Individual';}
  }
  
}
elseif ($ls_operacion == "ue_nuevo_codigo")
{  
    $ls_salida = $io_odi->uf_srh_getProximoCodigo();  

}

  echo utf8_encode($ls_salida);


?>
