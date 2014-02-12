<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_ganadores_concurso.php");
	
	require_once("../../class_folder/utilidades/class_funciones_srh.php");

	$io_ganadores_concurso= new sigesp_srh_c_ganadores_concurso('../../../');
    $io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_ganadores_concurso.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
    $ls_salida = "";

if (isset($_GET['valor']))
{
	    $evento=$_GET['valor'];

		if($evento=="createXML")
		{
			
			$ls_fecha2=$_REQUEST['txtfechahas'];
			$ls_fecha1=$_REQUEST['txtfechades'];
			$ls_codcon="%%";
			
		    header('Content-type:text/xml');			
			print $io_ganadores_concurso->uf_srh_buscar_ganadores_concurso($ls_codcon,$ls_fecha1,$ls_fecha2);
		}
		
		elseif($evento=="buscar")
		{
			$ls_fecha2=$_REQUEST['txtfechahas'];
			$ls_fecha1=$_REQUEST['txtfechades'];
			$ls_codcon="%".utf8_encode($_REQUEST['txtcodcon'])."%";
							
			header('Content-type:text/xml');			
			print $io_ganadores_concurso->uf_srh_buscar_ganadores_concurso($ls_codcon,$ls_fecha1,$ls_fecha2);
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
  $io_obj = $io_json->decode(utf8_decode ($objeto));
  list($valido,$existe)=$io_ganadores_concurso-> uf_srh_guardarganadores_concurso ($io_obj,$_POST["insmod"], $la_seguridad);
  if ($existe)
  {$ls_salida = 'Los Ganadores del Concurso ya se encuentran Registrados';}
  else 
  {
	  if ($valido)
	  {
	      if ($_POST["insmod"]=='modificar')
	 	  {
		  	  $ls_salida = 'Los Ganadores por Concurso fueron Actualizados';	
		  }
	     else 
		 {  
		     $ls_salida = 'Los Ganadores por Concurso fueron Registrados';
		}
     }
     else 
	 {
	   $ls_salida = 'Error al guardar Ganadores por Concurso';
	  }
	  
    } 
}
elseif ($ls_operacion == "ue_eliminar")
{  
  $io_ganadores_concurso->uf_srh_eliminarganadores_concurso($_GET["codcon"], $la_seguridad);
  $ls_salida = 'Los Ganadores de Concurso fueron Eliminados';
}


  echo utf8_encode($ls_salida);


?>
