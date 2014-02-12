<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_bono_merito.php");
	
	require_once("../../class_folder/utilidades/class_funciones_srh.php");

	$io_bono_merito= new sigesp_srh_c_bono_merito('../../../');
    $io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_bono_merito.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
    $ls_salida = "";




if (isset($_GET['valor']))
{
	    $evento=$_GET['valor'];

		if($evento=="createXML")
		{
			$ls_codper="%%";
			$ls_apeper="%%";
			$ls_nomper="%%";
			$ls_fecha1=$_REQUEST['txtfechades'];
			$ls_fecha2=$_REQUEST['txtfechahas'];
			
		    header('Content-type:text/xml');
			print $io_bono_merito->uf_srh_buscar_bono_merito($ls_codper,$ls_apeper,$ls_nomper,$ls_fecha1,$ls_fecha2);
		}
		
		elseif($evento=="buscar")
		{
			$ls_codper="%".utf8_encode($_REQUEST['txtcodper'])."%";
			$ls_apeper="%".utf8_encode($_REQUEST['txtapeper'])."%";
			$ls_nomper="%".utf8_encode($_REQUEST['txtnomper'])."%";
			$ls_fecha1=$_REQUEST['txtfechades'];
			$ls_fecha2=$_REQUEST['txtfechahas'];
				
			header('Content-type:text/xml');
			print $io_bono_merito->uf_srh_buscar_bono_merito($ls_codper,$ls_apeper,$ls_nomper,$ls_fecha1,$ls_fecha2);
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
  $ls_operacion ="";
}


if ($ls_operacion == "ue_guardar")
{  
  $objeto = str_replace('\"','"',$_POST["objeto"]);
  $io_bono = $io_json->decode(utf8_decode($objeto));
  list($valido,$lb_valfecha)= $io_bono_merito-> uf_srh_guardarbono_merito ($io_bono,$_POST["insmod"], $la_seguridad);
  if (!$lb_valfecha)
  {
  	$ls_salida = 'No puede registrar el Bono por Merito porque el personal ya tiene una evaluacion en el mes de la fecha seleccionada. Modifique la fecha o haga click en nuevo para registrar otra  Evaluacion.';	
  }
  else  if ($valido) 
  {
    if ($_POST["insmod"]=='modificar')
	{
	 	$ls_salida = 'El Bono por Merito fue Actualizado';	
	}
	else
	{ 
		$ls_salida = 'El Bono por Merito fue Registrado';
	}
  }
  else if (!$valido) 
  {
  	$ls_salida = 'Error al guardar el Bono por Merito';
  }
 
}
elseif ($ls_operacion == "ue_eliminar")
{  
  $io_bono_merito->uf_srh_eliminarbono_merito($_GET["codper"],$_GET["fecha"], $la_seguridad);
  $ls_salida = 'El Bono por Merito fue Eliminado';
}

  echo utf8_encode (trim ($ls_salida));


?>

