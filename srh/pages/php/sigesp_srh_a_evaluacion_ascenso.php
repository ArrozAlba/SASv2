<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_evaluacion_ascenso.php");
	
	require_once("../../class_folder/utilidades/class_funciones_srh.php");

	$io_evaluacion_ascenso= new sigesp_srh_c_evaluacion_ascenso('../../../');
    $io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_evaluacion_ascenso.php",$ls_permisos,$la_seguridad,$la_permisos);
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
			print $io_evaluacion_ascenso->uf_srh_buscar_evaluacion_ascenso($ls_nroreg,$ls_fecha1,$ls_fecha2);
		}
		
		elseif($evento=="buscar")
		{
			$ls_nroreg="%".utf8_encode($_REQUEST['txtnroreg'])."%";
			$ls_fecha1=$_REQUEST['txtfechades'];
			$ls_fecha2=$_REQUEST['txtfechahas'];
			
			header('Content-type:text/xml');			
			print $io_evaluacion_ascenso->uf_srh_buscar_evaluacion_ascenso($ls_nroreg,$ls_fecha1,$ls_fecha2);
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
  $io_eval = $io_json->decode(utf8_decode ($objeto));
  $valido= $io_evaluacion_ascenso-> uf_srh_guardarevaluacion_ascenso ($io_eval,$_POST["insmod"], $la_seguridad);
   if ($valido) {
    if ($_POST["insmod"]=='modificar')
	 {$ls_salida = 'La Evaluación de Ascenso fue Actualizada';	}
	else { $ls_salida = 'La Evaluación de Ascenso fue Registrada';}
    }
    else {$ls_salida = 'Error al guardar la Evaluación de Ascenso';}
}
elseif ($ls_operacion == "ue_eliminar")
{  
  $io_evaluacion_ascenso->uf_srh_eliminarevaluacion_ascenso($_GET["nroreg"],$_GET["fecha"], $la_seguridad);
  $ls_salida = 'Los Resultados para el Ascenso fueron Eliminados';
}

  echo utf8_encode($ls_salida);


?>
