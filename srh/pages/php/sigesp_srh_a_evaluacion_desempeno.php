<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_evaluacion_desempeno.php");
	
	require_once("../../class_folder/utilidades/class_funciones_srh.php");

	$io_evaluacion_desempeno= new sigesp_srh_c_evaluacion_desempeno('../../../');
    $io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_evaluacion_desempeno.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
    $ls_salida = "";




if (isset($_GET['valor']))
{
	    $evento=$_GET['valor'];

		if($evento=="createXML")
		{
		
			$ls_fecha1=$_REQUEST['txtfechades'];
			$ls_fecha2=$_REQUEST['txtfechahas'];
	        $ls_nroeval="%%";
			
		    header('Content-type:text/xml');
			print $io_evaluacion_desempeno->uf_srh_buscar_evaluacion_desempeno($ls_nroeval, $ls_fecha1,$ls_fecha2);
		}
		
		elseif($evento=="buscar")
		{
			
			$ls_fecha1=$_REQUEST['txtfechades'];
			$ls_fecha2=$_REQUEST['txtfechahas'];
			$ls_nroeval="%".utf8_encode($_REQUEST['txtnroeval'])."%";
							
			header('Content-type:text/xml');
			print $io_evaluacion_desempeno->uf_srh_buscar_evaluacion_desempeno($ls_nroeval, $ls_fecha1,$ls_fecha2);
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
  $valido = $io_evaluacion_desempeno-> uf_srh_guardarevaluacion_desempeno ($io_obj,$_POST["insmod"], $la_seguridad); 
   if ($valido) {
    if ($_POST["insmod"]=='modificar')
	 {$ls_salida = 'La Evaluación de Desempeño fue Actualizada';	}
	else { $ls_salida = 'La Evaluación de Desempeño fue Registrada';}
  }
  else {$ls_salida = 'Error al guardar la Evaluación de Desempeño';}
 
}
elseif ($ls_operacion == "ue_eliminar")
{  
  $io_evaluacion_desempeno->uf_srh_eliminarevaluacion_desempeno($_GET["nroeval"], $la_seguridad);
  $ls_salida = 'La Evaluación de Desempeño fue Eliminada';
}
elseif ($ls_operacion == "ue_nuevo_codigo")
{  
    $ls_salida =$io_evaluacion_desempeno->uf_srh_getProximoCodigo();  

}
elseif ($ls_operacion == "consultar_rango_actuacion")
{  
    $ls_salida =$io_evaluacion_desempeno->uf_srh_consultar_rango_actuacion ($_GET["codeval"], $_GET["total"]);

}


  echo utf8_encode($ls_salida);


?>
