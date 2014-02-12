<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_tiporequerimiento.php");
    require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_tiporequerimiento=new sigesp_srh_c_tiporequerimiento('../../../');
	$io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_tiporequerimiento.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
	
	$ls_salida="";
	
	if (array_key_exists("txtcodtipreq",$_POST))
	{
		$ls_codtipreq=$_POST['txtcodtipreq'];
	}
	else
	{
		$ls_codtipreq="";
    }
	
	if (array_key_exists("txtdentipreq",$_POST))
	{
		$ls_dentipreq= utf8_decode ($_POST['txtdentipreq']);
	}
	else
	{
		$ls_dentipreq="";
    }
	
	


	
	if (isset($_GET['valor']))
	{
		
		$evento=$_GET['valor'];
		
			if($evento=="guardar") 
			{
					
					
					$lb_existe= $io_tiporequerimiento->uf_srh_select_tiporequerimiento($ls_codtipreq);
					if ($lb_existe)
					{
						    
							$lb_update=$io_tiporequerimiento->uf_srh_update_tiporequerimiento($ls_codtipreq,$ls_dentipreq,$la_seguridad) ;
							
							if ($lb_update)
							{
								
								
								echo utf8_decode ("El Tipo de Requerimiento fue Actulizado");
							}
							
					}
					else
					{
						$lb_guardar= $io_tiporequerimiento->uf_srh_insert_tiporequerimiento($ls_codtipreq,$ls_dentipreq,$la_seguridad) ;
						 if ($lb_guardar)
							{
								
								
								echo utf8_decode ("El Tipo de Requerimiento fue Registrado");
							}

					
					}
					
			}
			
			elseif($evento=="eliminar")
			{
					
	
					list($lb_valido,$lb_existe)=$io_tiporequerimiento->uf_srh_delete_tiporequerimiento($ls_codtipreq,$la_seguridad);
			
					if($lb_existe)
					{
						echo utf8_decode ("El Tipo de Requerimiento no pueder ser eliminado porque esta asociado a un requerimiento");
					}	
					else
					{
							if($lb_valido)
							{
						
								echo utf8_decode ("El Tipo de Requerimiento fue Eliminado");
								
							}
							else
							{
							  echo utf8_decode ("Error al eliminar Tipo de Requerimiento");
							}
							
						
					}
					
					
			}
			elseif($evento=="existe")
			{
					
					$lb_existe= $io_tiporequerimiento->uf_srh_select_tiporequerimiento($ls_codtipreq);
					if ($lb_existe)
					{
					
						echo utf8_decode ("El Tipo de Requerimiento ya Existe");
					}
					
					
			}
				
			elseif($evento=="buscar")
			{
					
				   
				    $ls_codtipreq="%".utf8_encode($_REQUEST['txtcodtipreq'])."%";
	                $ls_dentipreq="%".utf8_encode($_REQUEST['txtdentipreq'])."%";
					
					header('Content-type:text/xml');
					print $io_tiporequerimiento->uf_srh_buscar_tiporequerimiento($ls_codtipreq, $ls_dentipreq);
					
					
					
					
			}
			elseif($evento=="createXML")
			{
			       $ls_codtipreq="%%";
	                $ls_dentipreq="%%";

					header('Content-type:text/xml');
					print $io_tiporequerimiento->uf_srh_buscar_tiporequerimiento($ls_codtipreq, $ls_dentipreq);
					
					
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
    $ls_salida = $io_tiporequerimiento->uf_srh_getProximoCodigo();  

}

echo utf8_encode($ls_salida);
	

?>
