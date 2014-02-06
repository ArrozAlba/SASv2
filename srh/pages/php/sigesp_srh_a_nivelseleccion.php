<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_nivelseleccion.php");
    require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_nivelseleccion=new sigesp_srh_c_nivelseleccion('../../../');
	$io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_nivelseleccion.php",$ls_permisos,$la_seguridad,$la_permisos);
	
	/// inicilizar variables///////////////////////////////////////////////////////////////////////
	$ls_logusr=$_SESSION["la_logusr"];	
	$ls_salida="";
	if (array_key_exists("txtcodniv",$_POST))
	{
		$ls_codniv=$_POST['txtcodniv'];
	}
	else
	{
		$ls_codniv="";
	}
	
	if (array_key_exists("txtdenniv",$_POST))
	{
		$ls_denniv=utf8_decode ($_POST['txtdenniv']);
	}
	else
	{
		$ls_denniv="";
	}
	//////////////////////////////////////////////////////////////////////////////////////////
	
	
	if (isset($_GET['valor']))
	{
		
		$evento=$_GET['valor'];
		
			if($evento=="guardar") 
			{
					
					
					$lb_existe= $io_nivelseleccion->uf_srh_select_nivelseleccion($ls_codniv);
					if ($lb_existe)
					{
						    
							$lb_update=$io_nivelseleccion->uf_srh_update_nivelseleccion($ls_codniv,$ls_denniv,$la_seguridad) ;
							
							if ($lb_update)
							{
								
								
								echo utf8_decode ("El Nivel de Selecci&oacute;n fue Actulizado");
							}
							
					}
					else
					{
						$lb_guardar= $io_nivelseleccion->uf_srh_insert_nivelseleccion($ls_codniv,$ls_denniv,$la_seguridad) ;
						 if ($lb_guardar)
							{
								
								
								echo utf8_decode ("El Nivel de Selecci&oacute;n fue Registrado");
							}

					
					}
					
			}
			
							

		
		
			elseif($evento=="eliminar")
			{
					
					
	
	
					list($lb_valido,$lb_existe)=$io_nivelseleccion->uf_srh_delete_nivelseleccion($ls_codniv,$la_seguridad);
			
					if($lb_existe)
					{
						echo utf8_decode ("El Nivel de Selecci&oacute;n no puede ser eliminado porque esta asociado a una solicitud de empleo");
					}	
					else
					{
							if($lb_valido)
							{
						
								echo utf8_decode ("El Nivel de Selecci&oacute;n fue Eliminado");
								
							}
							else
							{
							   echo utf8_decode ("Error al eliminar Nivel de Selecci&oacute;n");
							}
							
						
					}
					
					
			}
			elseif($evento=="existe")
			{
					
					$lb_existe= $io_nivelseleccion->uf_srh_select_nivelseleccion($ls_codniv);
					if ($lb_existe)
					{
					
						echo utf8_decode ("El Nivel de Selecci&oacute;n ya Existe");
					}
					
					
			}
			
					elseif($evento=="buscar")
			{
					
				   
				    $ls_codniv="%".utf8_encode($_REQUEST['txtcodniv'])."%";
	                $ls_denniv="%".utf8_encode($_REQUEST['txtdenniv'])."%";
					
					header('Content-type:text/xml');					
					print $io_nivelseleccion->uf_srh_buscar_nivelseleccion($ls_codniv, $ls_denniv);
					
					
					
					
			}
			elseif($evento=="createXML")
			{

				    $ls_codniv="%%";
	                $ls_denniv="%%";
					
   				    header('Content-type:text/xml');					
					print $io_nivelseleccion->uf_srh_buscar_nivelseleccion($ls_codniv, $ls_denniv);
					
					
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
    $ls_salida = $io_nivelseleccion->uf_srh_getProximoCodigo();  

}

echo utf8_encode($ls_salida);
	

?>