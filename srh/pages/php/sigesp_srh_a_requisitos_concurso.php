<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_requisitos_concurso.php");
	
	require_once("../../class_folder/utilidades/class_funciones_srh.php");

	$io_requisito= new sigesp_srh_c_requisitos_concurso('../../../');
    $io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_requisitos_concurso.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
    $ls_salida = "";




if (isset($_GET['valor']))
{
	    $evento=$_GET['valor'];

		if($evento=="createXML")
		{
			$ls_codcon="%%";
			$ls_descon="%%";
			
		    header('Content-type:text/xml');
			print  $io_requisito->uf_srh_buscar_requisitos_concurso($ls_codcon,$ls_descon);
		}
		
		elseif($evento=="buscar")
		{
			$ls_codcon="%".utf8_encode($_REQUEST['txtcodcon'])."%";
			$ls_descon="%".utf8_encode($_REQUEST['txtdescon'])."%";
				
			header('Content-type:text/xml');
			print $io_requisito->uf_srh_buscar_requisitos_concurso($ls_codcon,ls_descon);
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
  list($valido,$existe)=$io_requisito->uf_srh_guardar_requisitos_concurso ($io_req,$_POST["insmod"], $la_seguridad);
  if ($existe)
  {
  	$ls_salida = 'Los Requisitos del Concurso ya se encuentran Registrados';
  }
  else 
  {
	   if ($valido)
	   {
			if ($_POST["insmod"]=='modificar')
			{
				$ls_salida = 'Los Requisitos de Concurso se Actualizaron';	
			}
			else
			{
				 $ls_salida = 'Los Requisitos de Concurso se Registraron';
			}
	  }
	  else 
	  {
	  	$ls_salida = 'Error al guardar los Requisitos de Concurso';
	  }
  }
 
}
elseif ($ls_operacion == "ue_eliminar")
{  
  	$tipo='2';
	$valido= $io_requisito->uf_srh_eliminar_requisitos_concurso($_GET["codcon"], $la_seguridad,$tipo);
 
	  if ($valido)
	  {$ls_salida = 'Los Requisitos de Concurso se Eliminaron';}
	  else 
	  {$ls_salida = 'Ocurrio un error al eliminar Los Requisitos de Concurso';}
  
}


  echo utf8_encode($ls_salida);


?>
