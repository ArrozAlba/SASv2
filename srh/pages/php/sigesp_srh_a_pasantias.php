<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_pasantias.php");
	require_once("../../class_folder/dao/sigesp_srh_c_estado.php");
    require_once("../../class_folder/dao/sigesp_srh_c_municipio.php");
    require_once("../../class_folder/dao/sigesp_srh_c_parroquia.php");
    require_once("../../class_folder/utilidades/class_funciones_srh.php");
	
	$io_estado = new sigesp_srh_c_estado('../../../');
	$io_municipio = new sigesp_srh_c_municipio ('../../../');
	$io_parroquia = new sigesp_srh_c_parroquia('../../../');
	$io_pasantia= new sigesp_srh_c_pasantias('../../../');
    $io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_pasantias.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
    $ls_salida = "";
	
if (isset($_GET['valor']))
{
	    $evento=$_GET['valor'];

		if($evento=="createXML")
		{
			$ls_nropas="%%";
			$ls_cedpas="%%";
			$ls_apepas="%%";
			$ls_nompas="%%";
			$ls_fecini1=$_REQUEST['txtfecinides'];
			$ls_fecini2=$_REQUEST['txtfecinihas'];
			
			header('Content-type:text/xml');			
			print ($io_pasantia->uf_srh_buscar_pasantias($ls_nropas,$ls_cedpas,$ls_apepas,$ls_nompas,$ls_fecini1,$ls_fecini2));
			
		}
		
		elseif($evento=="buscar")
		{
			$ls_nropas="%".utf8_encode($_REQUEST['txtnropas'])."%";
			$ls_cedpas="%".utf8_encode($_REQUEST['txtcedpas'])."%";
			$ls_apepas="%".utf8_encode($_REQUEST['txtapepas'])."%";
			$ls_nompas="%".utf8_encode($_REQUEST['txtnompas'])."%";
			$ls_fecini1=$_REQUEST['txtfecinides'];
			$ls_fecini2=$_REQUEST['txtfecinihas'];
			 	
			header('Content-type:text/xml');			
			print ($io_pasantia->uf_srh_buscar_pasantias($ls_nropas,$ls_cedpas,$ls_apepas,$ls_nompas,$ls_fecini1,$ls_fecini2));
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
  $ls_operacion ="";
}



if ($ls_operacion == "ue_inicializar")
			{  	
			
			//Estados
  				$lb_hay = $io_estado ->getEstados($_GET["codpai"],"ORDER BY desest ASC",$la_estados);
				 if ($lb_hay)
					{$ls_salida = $io_json->encode($la_estados);}	

			}		

 elseif ($ls_operacion == "ue_cambioestado")
{
  $lb_hay = $io_municipio->getMunicipios($_GET["codpai"],$_GET["codest"],"ORDER BY denmun ASC",$la_municipios);
  if ($lb_hay)
  {$ls_salida  = $io_json->encode($la_municipios);}
}
elseif ($ls_operacion == "ue_cambiomunicipio")
{
  $lb_hay = $io_parroquia->getparroquias($_GET["codpai"],$_GET["codest"],$_GET["codmun"],"ORDER BY denpar ASC",$la_parroquias);
  if ($lb_hay)
  {$ls_salida  = $io_json->encode($la_parroquias);}
}
  
 elseif ($ls_operacion == "ue_guardar")
{  
  $objeto = str_replace('\"','"',$_POST["objeto"]);
  $io_pas = $io_json->decode(utf8_decode ($objeto));
  $valido = $io_pasantia-> uf_srh_guardarPasantia($io_pas,$_POST["insmod"], $la_seguridad);
   if ($valido) {
    if ($_POST["insmod"]=='modificar')
	 {$ls_salida = 'El Registro de Pasantia fue Actualizado';	}
	else { $ls_salida = 'El Registro de Pasantia fue Registrado';}
  }
  else {$ls_salida = 'Error al guardar el Registro de Pasantia';}
 
}
elseif ($ls_operacion == "ue_eliminar")
{  
  
  list($valido,$existe)= $io_pasantia->uf_srh_eliminarPasantia($_GET["nropas"], $la_seguridad);
  if ($existe)
  {$ls_salida = 'El Registro de Pasantia no puede ser eliminada porque esta asociada a una Evaluación';}
  else 
  {
	  if ($valido)
	  {$ls_salida = $ls_salida = 'El Registro de Pasantia fue Eliminado';}
	  else 
	  {$ls_salida = 'Ocurrio un error al eliminar el Registro de Pasantía';}
  }
  
}
elseif ($ls_operacion == "ue_nuevo_codigo")
{  
    $ls_salida = $io_pasantia->uf_srh_getProximoCodigo();  

}

  echo utf8_encode($ls_salida);


?>
