<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_tipoenfermedad.php");
    require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_enf=new sigesp_srh_c_tipoenfermedad('../../../');
	$io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_tipoenfermedad.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
	
	$ls_salida="";
	
	if (array_key_exists("txtcodenf",$_POST))
	{
		$ls_codenf=$_POST['txtcodenf'];
	}
	else
	{
		$ls_codenf="";
    }
	
	if (array_key_exists("txtdenenf",$_POST))
	{
		$ls_denenf=utf8_decode ($_POST['txtdenenf']);
	}
	else
	{
		$ls_denenf="";
    }
	if (array_key_exists("comboriecon",$_POST))
	{
		$ls_riecon=utf8_decode ($_POST['comboriecon']);
	}
	else
	{
		$ls_riecon="";
    }
	if (array_key_exists("comborielet",$_POST))
	{
		$ls_rielet=utf8_decode ($_POST['comborielet']);
	}
	else
	{
		$ls_rielet="";
    }
	if (array_key_exists("txtobsenf",$_POST))
	{
		$ls_obsenf=utf8_decode ($_POST['txtobsenf']);
	}
	else
	{
		$ls_obsenf="";
    }
	
	
	if (isset($_GET['valor']))
	{
		$evento=$_GET['valor'];
			if($evento=="guardar")
			{		
					$lb_existe= $io_enf->uf_srh_select_tipoenfermedad($ls_codenf);
					if ($lb_existe)
					{	$lb_update=$io_enf->uf_srh_update_tipoenfermedad($ls_codenf,$ls_denenf,$ls_riecon,$ls_rielet,$ls_obsenf,$la_seguridad);
							if ($lb_update)
							{	echo utf8_decode ("El Tipo de Enfermedad fue Actualizado");
							
							}
							
					}
					else
					{
						$lb_guardar= $io_enf->uf_srh_insert_tipoenfermedad($ls_codenf,$ls_denenf,$ls_riecon,$ls_rielet,$ls_obsenf,$la_seguridad);
						 if ($lb_guardar)
							{
								echo utf8_decode ("El Tipo de Enfermedad fue Registrado");
							}

					
					}
					
			}
			
		elseif($evento=="eliminar")
			{	list($lb_valido,$lb_existe)=$io_enf->uf_srh_delete_tipoenfermedad($ls_codenf,$la_seguridad);
				if($lb_existe)
					{
						 echo utf8_decode ("El Tipo de Enfermedad no puede ser eliminado porque esta asociado a una Endfermedad");	
					}	
					else
					{
							if($lb_valido)
							{
								echo utf8_decode ("El Tipo de Enfermedad Fue Eliminado");	
							}
							else
							{
							   echo utf8_decode ("Error al eliminar Tipo de Enfermedad");	
							}
					
					}
					
			}
			elseif($evento=="existe")
			{
				
					$lb_existe= $io_enf->uf_srh_select_tipoenfermedad($ls_codenf);
					if ($lb_existe)
					{
						echo utf8_decode ("El Tipo de Enfermedad ya Existe");
					}					
			}
			elseif($evento=="buscar")
			{
					
				   
				    $ls_codenf="%".utf8_encode($_REQUEST['txtcodenf'])."%";
	                $ls_denenf="%".utf8_encode($_REQUEST['txtdenenf'])."%";
					
					header('Content-type:text/xml');
					print $io_enf->uf_srh_buscar_tipoenfermedad($ls_codenf, $ls_denenf);
					
					
					
					
			}
			elseif($evento=="createXML")
			{
                       
				    $ls_codenf="%%";
	                $ls_denenf="%%";
				
					header('Content-type:text/xml');
					print $io_enf->uf_srh_buscar_tipoenfermedad($ls_codenf, $ls_denenf);
					
					
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
    $ls_salida = $io_enf->uf_srh_getProximoCodigo();  

}

echo utf8_encode($ls_salida);

	

?>
