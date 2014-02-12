<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_resultados_evaluacion_aspirante.php");
	require_once("../../class_folder/utilidades/class_funciones_srh.php");
	
	$io_resultado= new sigesp_srh_c_resultados_evaluacion_aspirante('../../../');
    $io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_resultados_evaluacion_aspirante.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
    $ls_salida = "";




if (isset($_GET['valor']))
{
	    $evento=$_GET['valor'];

		if($evento=="createXML")
		{
			$ls_codper="%%";			
			$ls_fecha1=$_REQUEST['txtfechades'];
			$ls_fecha2=$_REQUEST['txtfechahas'];
			
		    header('Content-type:text/xml');			
			print $io_resultado->uf_srh_buscar_resultados_evaluacion_aspirante($ls_codper,$ls_fecha1,$ls_fecha2);
		}
		
		elseif($evento=="buscar")
		{
			$ls_codper="%".utf8_encode($_REQUEST['txtcodper'])."%";			
			$ls_fecha1=$_REQUEST['txtfechades'];
			$ls_fecha2=$_REQUEST['txtfechahas'];	
			header('Content-type:text/xml');			
			print $io_resultado->uf_srh_buscar_resultados_evaluacion_aspirante($ls_codper,$ls_fecha1,$ls_fecha2);
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


if($ls_operacion == "ue_chequear_codigo")
{
 	 list($lb_existe,$ls_codcon) = $io_resultado->getCodPersonal($_GET["codper"],$_GET["codcon"],$la_datos);
	  if ($lb_existe)
	  {
	 
	    $ls_salida  ='El código del personal '.$_GET["codper"].' ya fue evaluado en el concurso  '.$ls_codcon;
	  }

}
elseif ($ls_operacion == "ue_guardar")
{  
  $objeto = str_replace('\"','"',$_POST["objeto"]);
  $io_res = $io_json->decode(utf8_decode ($objeto));
  $valido = $io_resultado-> uf_srh_guardarresultados_evaluacion_aspirante($io_res,$_POST["insmod"], $la_seguridad);
   if ($valido) {
    if ($_POST["insmod"]=='modificar')
	 {$ls_salida = 'El Registro de Resultados de Evaluación de Aspirantes fue Actualizado';	}
	else { $ls_salida = 'El Registro de Resultados de Evaluación de Aspirantes fue Registrado';}
  }
  else {$ls_salida = 'Error al guardar el Registro de Resultados de Evaluación de Aspirantes ';}
 
}
elseif ($ls_operacion == "ue_eliminar")
{  
  $io_resultado->uf_srh_eliminarresultados_evaluacion_aspirante($_GET["codper"],$_GET["codcon"], $la_seguridad);
  $ls_salida = 'El Registro de Resultados de Evaluación de Aspirantes fue Eliminado';
}


  echo utf8_encode($ls_salida);


?>
