<?
	session_start();


//Initialization class variables
	require_once("../../class_folder/dao/sigesp_srh_c_competencia_adiestramiento.php");
    require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_compadi=new sigesp_srh_c_competencia_adiestramiento('../../../');
	$io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_competencia_adiestramiento.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=utf8_encode($_SESSION["la_logusr"]);

    $ls_salida="";
	
	if (array_key_exists("txtcodcompadi",$_POST))
	{
		$ls_codcompadi=$_POST['txtcodcompadi'];
	}
	else
	{
		$ls_codcompadi="";
    }
	
	if (array_key_exists("txtdencompadi",$_POST))
	{
		$ls_dencompadi=utf8_decode ($_POST['txtdencompadi']);
	}
	else
	{
		$ls_dencompadi="";
    }
	
	

    
	
	if (isset($_GET['valor']))
	{
		
		$evento=$_GET['valor'];
		
			if($evento=="guardar")
			{				
					$lb_existe= $io_compadi->uf_srh_select_competencia_adiestramiento($ls_codcompadi);
					if ($lb_existe)
					{
						
							$lb_update=$io_compadi->uf_srh_update_competencia_adiestramiento($ls_codcompadi,$ls_dencompadi,$la_seguridad);
							
							if ($lb_update)
							{
								echo "La Competencia de Adiestramiento fue Actulizada";
							}
							
					}
					else
					{
						$lb_guardar= $io_compadi->uf_srh_insert_competencia_adiestramiento($ls_codcompadi,$ls_dencompadi,$la_seguridad);
						 if ($lb_guardar)
							{
								
									echo "La Competencia de Adiestramiento fue Registrada";
							}

					
					}
					
			}
		
			elseif($evento=="eliminar")
			{
					list($lb_valido,$lb_existe)=$io_compadi->uf_srh_delete_competencia_adiestramiento($ls_codcompadi,$la_seguridad);
			
					if($lb_existe)
					{
						echo "La Competencia de Adiestramiento no puede ser elimnada porque esta asociada a una necesidad de adiestramiento";
					}	
					else
					{
							if($lb_valido)
							{
								echo "La Competencia de Adiestramiento fue Eliminada";
								
							}
							else
							{
							
								echo "Error al eliminar la Competencia de Adiestramiento";
							}
					
					}
					
			}
			elseif($evento=="existe")
			{
					$lb_existe= $io_compadi->uf_srh_select_competencia_adiestramiento($ls_codcompadi);
					if ($lb_existe)
					{
						echo "La Competencia de Adiestramiento ya existe";
					}
			}
			
			
			elseif($evento=="buscar")
			{
					
				   
				    $ls_codcompadi="%".utf8_encode($_REQUEST['txtcodcompadi'])."%";
	                $ls_dencompadi="%".utf8_encode($_REQUEST['txtdencompadi'])."%";
					
					header('Content-type:text/xml');
					print $io_compadi->uf_srh_buscar_competencia_adiestramiento($ls_codcompadi, $ls_dencompadi);
					
					
					
					
			}
			elseif($evento=="createXML")
			{
                       
				    $ls_codcompadi="%%";
	                $ls_dencompadi="%%";
				
					header('Content-type:text/xml');
					print $io_compadi->uf_srh_buscar_competencia_adiestramiento($ls_codcompadi, $ls_dencompadi);
					
					
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
    $ls_salida = $io_compadi->uf_srh_getProximoCodigo();  

}

echo utf8_encode($ls_salida);
	

?>
