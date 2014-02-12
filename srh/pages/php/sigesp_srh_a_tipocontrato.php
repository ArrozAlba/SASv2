<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_tipocontrato.php");
    require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_tipocontrato=new sigesp_srh_c_tipocontrato('../../../');
	$io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_tipocontrato.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
	
	$ls_salida="";
	
	if (array_key_exists("txtcodtipcon",$_POST))
	{
		$ls_codtipcon=$_POST['txtcodtipcon'];
	}
	else
	{
		$ls_codtipcon="";
    }
	
	if (array_key_exists("txtdentipcon",$_POST))
	{
		$ls_dentipcon=utf8_decode ($_POST['txtdentipcon']);
	}
	else
	{
		$ls_dentipcon="";
    }
	
	
	
	if (isset($_GET['valor']))
	{
		
		$evento=$_GET['valor'];
		
			if($evento=="guardar") 
			{
					
					
					$lb_existe= $io_tipocontrato->uf_srh_select_tipocontrato($ls_codtipcon);
					if ($lb_existe)
					{
						    
							$lb_update=$io_tipocontrato->uf_srh_update_tipocontrato($ls_codtipcon,$ls_dentipcon,$la_seguridad) ;
							
							if ($lb_update)
							{
								
								
								echo utf8_decode ("El Tipo de Contrato fue Actulizado");
							}
							
					}
					else
					{
						$lb_guardar= $io_tipocontrato->uf_srh_insert_tipocontrato($ls_codtipcon,$ls_dentipcon,$la_seguridad) ;
						 if ($lb_guardar)
							{
								
								
								echo utf8_decode ("El Tipo de Contrato fue Registrado");
							}

					
					}
					
			}
			
							

		
		
			elseif($evento=="eliminar")
			{

					list($lb_valido,$lb_existe)=$io_tipocontrato->uf_srh_delete_tipocontrato($ls_codtipcon,$la_seguridad);
			
					if($lb_existe)
					{
						echo utf8_decode ("El Tipo de Contrato no puede ser eliminado porque esta asociado a un contrato");
					}	
					else
					{
							if($lb_valido)
							{
						
								echo utf8_decode ("El Tipo de Contrato fue Eliminado");
								
							}
							else
							{
							  echo utf8_decode ("Error al eliminar Tipo de Contrato");
							}
							
						
					}
					
					
			}
			elseif($evento=="existe")
			{
					
					$lb_existe= $io_tipocontrato->uf_srh_select_tipocontrato($ls_codtipcon);
					if ($lb_existe)
					{
					
						echo utf8_decode ("El Tipo de Contrato ya Existe");
					}
					
					
			}
			elseif($evento=="buscar")
			{
				
					
				   
				    $ls_codtipcon="%".utf8_encode($_REQUEST['txtcodtipcon'])."%";
	                $ls_dentipcon="%".utf8_encode($_REQUEST['txtdentipcon'])."%";
					
					header('Content-type:text/xml');
					print $io_tipocontrato->uf_srh_buscar_tipocontrato($ls_codtipcon, $ls_dentipcon);
					
					
					
					
			}
			elseif($evento=="createXML")
			{

    				$ls_codtipcon="%%";
	                $ls_dentipcon="%%";

					header('Content-type:text/xml');
				    print $io_tipocontrato->uf_srh_buscar_tipocontrato($ls_codtipcon, $ls_dentipcon);
					
					
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
    $ls_salida = $io_tipocontrato->uf_srh_getProximoCodigo();  

}

echo utf8_encode($ls_salida);


?>
