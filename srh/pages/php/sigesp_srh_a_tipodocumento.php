<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_tipodocumento.php");
    require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_tipodocumento=new sigesp_srh_c_tipodocumento('../../../');
	$io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_tipodocumento.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
	
	$ls_salida="";
	
	if (array_key_exists("txtcodtipdoc",$_POST))
	{
		$ls_codtipdoc=$_POST['txtcodtipdoc'];
	}
	else
	{
		$ls_codtipdoc="";
    }
	
	if (array_key_exists("txtdentipdoc",$_POST))
	{
		$ls_dentipdoc= utf8_decode ($_POST['txtdentipdoc']);
	}
	else
	{
		$ls_dentipdoc="";
    }
	
	
	
	
	
	if (isset($_GET['valor']))
	{
		
		$evento=$_GET['valor'];
		
			if($evento=="guardar") 
			{
					
					
					$lb_existe= $io_tipodocumento->uf_srh_select_tipodocumento($ls_codtipdoc);
					if ($lb_existe)
					{
						    
							$lb_update=$io_tipodocumento->uf_srh_update_tipodocumento($ls_codtipdoc,$ls_dentipdoc,$la_seguridad) ;
							
							if ($lb_update)
							{
								
								
								echo utf8_decode ("El Tipo de Documento fue Actulizado");
							}
							
					}
					else
					{
						$lb_guardar= $io_tipodocumento->uf_srh_insert_tipodocumento($ls_codtipdoc,$ls_dentipdoc,$la_seguridad) ;
						 if ($lb_guardar)
							{
								
								
								echo utf8_decode ("El Tipo de Documento fue Registrado");
							}

					
					}
					
			}

			elseif($evento=="eliminar")
			{
					list($lb_valido,$lb_existe)=$io_tipodocumento->uf_srh_delete_tipodocumento($ls_codtipdoc,$la_seguridad);
			
					if($lb_existe)
					{
						echo utf8_decode ("El Tipo de Documento no puede ser eliminado porque esta asociado a un Documento");
					}	
					else
					{
							if($lb_valido)
							{
						
								echo utf8_decode ("El Tipo de Documento fue Eliminado");
								
							}
							else
							
							{
						
								echo utf8_decode ("Error al eliminar Tipo de Documento");
								
							}
					}
					
					
			}
			elseif($evento=="existe")
			{
					$lb_existe= $io_tipodocumento->uf_srh_select_tipodocumento($ls_codtipdoc);
					if ($lb_existe)
					{
						echo utf8_decode ("El Tipo de Documento ya existe");
					}
			}
			
			
			elseif($evento=="buscar")
			{
					
				   
				    $ls_codtipdoc="%".utf8_encode($_REQUEST['txtcodtipdoc'])."%";
	                $ls_dentipdoc="%".utf8_encode($_REQUEST['txtdentipdoc'])."%";
					
					header('Content-type:text/xml');
					print $io_tipodocumento->uf_srh_buscar_tipodocumento($ls_codtipdoc, $ls_dentipdoc);
					
					
					
					
			}
			elseif($evento=="createXML")
			{

					$ls_codtipdoc="%%";
	                $ls_dentipdoc="%%";
					
					header('Content-type:text/xml');
					print $io_tipodocumento->uf_srh_buscar_tipodocumento($ls_codtipdoc, $ls_dentipdoc);
					
					
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
    $ls_salida = $io_tipodocumento->uf_srh_getProximoCodigo();  

}

echo utf8_encode($ls_salida);

?>
