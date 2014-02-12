<?
	session_start();


//Initialization class variables
	require_once("../../class_folder/dao/sigesp_srh_c_causa_adiestramiento.php");
    require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_cauadi=new sigesp_srh_c_causa_adiestramiento('../../../');
	$io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_causa_adiestramiento.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];

    $ls_salida="";
	
	if (array_key_exists("txtcodcauadi",$_POST))
	{
		$ls_codcauadi=$_POST['txtcodcauadi'];
	}
	else
	{
		$ls_codcauadi="";
    }
	
	if (array_key_exists("txtdencauadi",$_POST))
	{
		$ls_dencauadi=utf8_decode ($_POST['txtdencauadi']);
	}
	else
	{
		$ls_dencauadi="";
    }
	
	

    
	
	if (isset($_GET['valor']))
	{
		
		$evento=$_GET['valor'];
		
			if($evento=="guardar")
			{				
					$lb_existe= $io_cauadi->uf_srh_select_causa_adiestramiento($ls_codcauadi);
					if ($lb_existe)
					{
						
							$lb_update=$io_cauadi->uf_srh_update_causa_adiestramiento($ls_codcauadi,$ls_dencauadi,$la_seguridad);
							
							if ($lb_update)
							{
								echo "La Causa de Adiestramiento fue Actulizada";
							}
							
					}
					else
					{
						$lb_guardar= $io_cauadi->uf_srh_insert_causa_adiestramiento($ls_codcauadi,$ls_dencauadi,$la_seguridad);
						 if ($lb_guardar)
							{
								
									echo "La Causa de Adiestramiento fue Registrada";
							}

					
					}
					
			}
		
			elseif($evento=="eliminar")
			{
					list($lb_valido,$lb_existe)=$io_cauadi->uf_srh_delete_causa_adiestramiento($ls_codcauadi,$la_seguridad);
			
					if($lb_existe)
					{
						echo "La Causa de Adiestramiento no puede ser elimnada porque esta asociada a una necesidad de adiestramiento";
					}	
					else
					{
							if($lb_valido)
							{
								echo "La Causa de Adiestramiento fue Eliminada";
								
							}
							else
							{
							
								echo "Error al eliminar la Causa de Adiestramiento";
							}
					
					}
					
			}
			elseif($evento=="existe")
			{
					$lb_existe= $io_cauadi->uf_srh_select_causa_adiestramiento($ls_codcauadi);
					if ($lb_existe)
					{
						echo "La Causa de Adiestramiento ya existe";
					}
			}
			
			
			elseif($evento=="buscar")
			{
					
				   
				    $ls_codcauadi="%".utf8_encode($_REQUEST['txtcodcauadi'])."%";
	                $ls_dencauadi="%".utf8_encode($_REQUEST['txtdencauadi'])."%";
					
					header('Content-type:text/xml');
					print $io_cauadi->uf_srh_buscar_causa_adiestramiento($ls_codcauadi, $ls_dencauadi);
					
					
					
					
			}
			elseif($evento=="createXML")
			{
                       
				    $ls_codcauadi="%%";
	                $ls_dencauadi="%%";
				
					header('Content-type:text/xml');
					print $io_cauadi->uf_srh_buscar_causa_adiestramiento($ls_codcauadi, $ls_dencauadi);
					
					
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
    $ls_salida = $io_cauadi->uf_srh_getProximoCodigo();  

}

echo utf8_encode($ls_salida);
	

?>
