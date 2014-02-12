<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_causa_llamada_atencion.php");
    require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_cau=new sigesp_srh_c_causa_llamada_atencion('../../../');
	$io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_causa_llamada_atencion.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
	
	$ls_salida="";
	
	if (array_key_exists("txtcodcaullam_aten",$_POST))
	{
		$ls_codcaullam_aten=$_POST['txtcodcaullam_aten'];
	}
	else
	{
		$ls_codcaullam_aten="";
    }
	
	if (array_key_exists("txtdencaullam_aten",$_POST))
	{
		$ls_dencaullam_aten=utf8_decode($_POST['txtdencaullam_aten']);
	}
	else
	{
		$ls_dencaullam_aten="";
    }
	
	
	
	
	if (isset($_GET['valor']))
	{
		
		$evento=$_GET['valor'];
		
			if($evento=="guardar")
			{				
					$lb_existe= $io_cau->uf_srh_select_causa_llamada_atencion($ls_codcaullam_aten);
					if ($lb_existe)
					{
						
							$lb_update=$io_cau->uf_srh_update_causa_llamada_atencion($ls_codcaullam_aten,$ls_dencaullam_aten,$la_seguridad);
							
							if ($lb_update)
							{
								echo "La Causa de Llamada de Atencion fue Actulizada";
							}
							
					}
					else
					{
						$lb_guardar= $io_cau->uf_srh_insert_causa_llamada_atencion($ls_codcaullam_aten,$ls_dencaullam_aten,$la_seguridad);
						 if ($lb_guardar)
							{
								
									echo "La Causa de Llamada de Atencion fue Registrada";
							}

					
					}
					
			}
		
			elseif($evento=="eliminar")
			{
					list($lb_valido,$lb_existe)=$io_cau->uf_srh_delete_causa_llamada_atencion($ls_codcaullam_aten,$la_seguridad);
			
					if($lb_existe)
					{
						echo "La Causa de Llamada de Atencion no puede ser elimianda porque esta asociada a una llamada de atencion";
					}	
					else
					{
							if($lb_valido)
							{
								echo "La Causa de Llamada de Atencion fue Eliminada";
								
							}
							else
							{
							  echo "Error al eliminar Causa de Llamada de Atencion";
								
							}
					
					}
					
			}
			elseif($evento=="existe")
			{
					$lb_existe= $io_cau->uf_srh_select_causa_llamada_atencion($ls_codcaullam_aten);
					if ($lb_existe)
					{
						echo "La Causa de Llamada de Atencion ya existe";
					}
			}
			
			
				elseif($evento=="buscar")
			{
				
					
				   
				    $ls_codcaullam_aten="%".utf8_encode($_REQUEST['txtcodcaullam_aten'])."%";
	                $ls_dencaullam_aten="%".utf8_encode($_REQUEST['txtdencaullam_aten'])."%";					
					header('Content-type:text/xml');
					print $io_cau->uf_srh_buscar_causa_llamada_atencion($ls_codcaullam_aten, $ls_dencaullam_aten);
					
					
					
					
			}
			elseif($evento=="createXML")
			{

    				$ls_codcaullam_aten="%%";
	                $ls_dencaullam_aten="%%";
					header('Content-type:text/xml');
					print $io_cau->uf_srh_buscar_causa_llamada_atencion($ls_codcaullam_aten, $ls_dencaullam_aten);
					
					
			}
				
		
	}	
	
if (array_key_exists("operacion",$_GET))
{
  $ls_operacion = $_GET["operacion"];
}
else
{
  $ls_operacion ="";
}


if ($ls_operacion == "ue_nuevo")
{  
    $ls_salida = $io_cau->uf_srh_getProximoCodigo();  

}

echo utf8_encode($ls_salida);
	

?>
