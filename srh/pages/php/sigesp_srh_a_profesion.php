<?
	session_start();


//Initialization class variables
	require_once("../../class_folder/dao/sigesp_srh_c_profesion.php");
    require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_pro=new sigesp_srh_c_profesion('../../../');
	$io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_profesion.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=utf8_encode($_SESSION["la_logusr"]);
	
	///inicializaciòn de varibales//////////////////////////////////////////////////////////////////////////////////
	 $ls_salida="";
	if (array_key_exists("txtcodpro",$_POST))
	{
		$ls_codpro=$_POST['txtcodpro'];
	}
	else
	{
	  $ls_codpro="";
	}
	
	 if (array_key_exists("txtdespro",$_POST))
	{
		$ls_despro=utf8_decode ($_POST['txtdespro']);
	}
	else
	{
	  $ls_despro="";
	}
   //------------------------------------------------------------------------------------------------------------------ 
	
	if (isset($_GET['valor']))
	{
		
		$evento=$_GET['valor'];
		
			if($evento=="guardar")
			{				
					$lb_existe= $io_pro->uf_srh_select_profesion($ls_codpro);
					if ($lb_existe)
					{
						
							$lb_update=$io_pro->uf_srh_update_profesion($ls_codpro,$ls_despro,$la_seguridad);
							
							if ($lb_update)
							{
								echo utf8_decode ("La Profesi&oacute;n fue Actulizada");
							}
							
					}
					else
					{
						$lb_guardar= $io_pro->uf_srh_insert_profesion($ls_codpro,$ls_despro,$la_seguridad);
						 if ($lb_guardar)
							{
								
									echo utf8_decode ("La Profesi&oacute;n fue Registrada");
							}

					
					}
					
			}
		
			elseif($evento=="eliminar")
			{
					list($lb_valido,$lb_existe)=$io_pro->uf_srh_delete_profesion($ls_codpro,$la_seguridad);
			
					if($lb_existe)
					{
						echo utf8_decode ("La Profesi&oacute;n no puede ser eliminada porque esta asociada a una persona");
					}	
					else
					{
							if($lb_valido)
							{
								echo utf8_decode ("La Profesi&oacute;n fue Eliminada");
								
							}
							else
							{
							    echo utf8_decode ("Error al eliminar Profesi&oacute;n");
								
							}
					
					}
					
			}
			elseif($evento=="existe")
			{
					$lb_existe= $io_pro->uf_srh_select_profesion($ls_codpro);
					if ($lb_existe)
					{
						echo utf8_decode ("La Profesi&oacute;n ya existe");
					}
			}
			
			
			elseif($evento=="buscar")
			{
					
				   
				    $ls_codpro="%".utf8_encode($_REQUEST['txtcodpro'])."%";
	                $ls_despro="%".utf8_encode($_REQUEST['txtdespro'])."%";					
					header('Content-type:text/xml');					
					print $io_pro->uf_srh_buscar_profesion($ls_codpro, $ls_despro);
					
					
					
					
			}
			elseif($evento=="createXML")
			{
                       
				    $ls_codpro="%%";
	                $ls_despro="%%";
				
					 header('Content-type:text/xml');					
					print $io_pro->uf_srh_buscar_profesion($ls_codpro, $ls_despro);
					
					
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
    $ls_salida = $io_pro->uf_srh_getProximoCodigo();  

}
echo utf8_encode($ls_salida);
	

?>
