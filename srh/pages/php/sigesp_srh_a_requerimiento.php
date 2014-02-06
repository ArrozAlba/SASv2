<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_requerimiento.php");
    require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_req=new sigesp_srh_c_requerimiento('../../../');
	$io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_requerimiento.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];

	$ls_salida="";
	
	if (array_key_exists("txtcodreq",$_POST))
	{
		$ls_codreq=$_POST['txtcodreq'];
	}
	else
	{
		$ls_codreq="";
    }
	
	if (array_key_exists("txtdenreq",$_POST))
	{
		$ls_denreq=utf8_decode ($_POST["txtdenreq"]);
	}
	else
	{
		$ls_denreq="";
    }
	
	if (array_key_exists("txtcodtipreq",$_POST))
	{
		$ls_codtipreq=$_POST['txtcodtipreq'];
	}
	else
	{
		$ls_codtipreq="";
    }
	
	
	
	
	if (isset($_GET['valor']))
	{
		
		$evento=$_GET['valor'];
		
			if($evento=="guardar")
			{
					
					
					$lb_existe= $io_req->uf_srh_select_requerimiento($ls_codreq);
					if ($lb_existe)
					{
						
							$lb_update=$io_req->uf_srh_update_requerimiento($ls_codreq,$ls_denreq,$ls_codtipreq,$la_seguridad);
							
							if ($lb_update)
							{
								
							
								echo utf8_decode ("El Requerimiento fue Actualizado");
							}
							
					}
					else
					{
						$lb_guardar= $io_req->uf_srh_insert_requerimiento($ls_codreq,$ls_denreq,$ls_codtipreq,$la_seguridad);
						 if ($lb_guardar)
							{
								
							
								echo utf8_decode ("El Requerimiento fue Registrado");
							}

					
					}
					
			}
			
							

		
		
			elseif($evento=="eliminar")
			{
					
					
	
	
					list($lb_valido,$lb_existe)=$io_req->uf_srh_delete_requerimiento($ls_codreq,$la_seguridad);
			
					if($lb_existe)
					{
						echo utf8_decode ("El Requerimiento no puede ser eliminado porque esta asociado a un cargo");
					}	
					else
					{
							if($lb_valido)
							{
								
								echo utf8_decode ("El Requerimiento fue Eliminado");
								
							}
							else
							{
							
								
							}
					
					}
					
					
			}
			elseif($evento=="existe")
			{
					$lb_existe= $io_req->uf_srh_select_requerimiento($ls_codreq);
					if ($lb_existe)
					{
						
						echo utf8_decode ("El Requerimiento ya Existe");
					}
					
					
			}elseif($evento=="buscar")
			{
					$ls_codreq="%".utf8_encode($_REQUEST['txtcodreq'])."%";
	                $ls_denreq="%".utf8_encode($_REQUEST['txtdenreq'])."%";
					$ls_codtipreq="%".utf8_encode($_REQUEST['txtcodtipreq'])."%";
					 
					header('Content-type:text/xml');
					print $io_req->uf_srh_buscar_requerimiento($ls_codreq,$ls_denreq,$ls_codtipreq);
					
			
			}
			elseif($evento=="createXML")
			{
			      $ls_codreq="%%";
	              $ls_denreq="%%";
				  $ls_codtipreq="%%";
				  header('Content-type:text/xml');
  				  print $io_req->uf_srh_buscar_requerimiento($ls_codreq,$ls_denreq,$ls_codtipreq);
					
					
			}
				
	}
		
if (array_key_exists("operacion",$_GET))
{
  $ls_operacion = $_GET["operacion"];
}
else
{
  $ls_operacion = "";
}


if ($ls_operacion == "ue_nuevo")
{  
    $ls_salida = $io_req->uf_srh_getProximoCodigo();  

}

echo utf8_encode($ls_salida);



?>
