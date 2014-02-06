<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_grupomovimiento.php");
    require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_grupomovimiento=new sigesp_srh_c_grupomovimiento('../../../');
	$io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_grupomovimiento.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
	
	$ls_salida="";
	
	if (array_key_exists("txtcodgrumov",$_POST))
	{
		$ls_codgrumov=$_POST['txtcodgrumov'];
	}
	else
	{
		$ls_codgrumov="";
    }
	
	if (array_key_exists("txtdengrumov",$_POST))
	{
		$ls_dengrumov=utf8_decode ($_POST['txtdengrumov']);
	}
	else
	{
		$ls_dengrumov="";
    }
	
	
	
	if (isset($_GET['valor']))
	{
		
		$evento=$_GET['valor'];
		
			if($evento=="guardar") 
			{
					
					
					$lb_existe= $io_grupomovimiento->uf_srh_select_grupomovimiento($ls_codgrumov);
					if ($lb_existe)
					{
						
							$lb_update=$io_grupomovimiento->uf_srh_update_grupomovimiento($ls_codgrumov,$ls_dengrumov,$la_seguridad) ;
							
							if ($lb_update)
							{
								
								
								echo "El Grupo de Movimiento de Personal fue Actulizado";
							}
							
					}
					else
					{
						$lb_guardar= $io_grupomovimiento->uf_srh_insert_grupomovimiento($ls_codgrumov,$ls_dengrumov,$la_seguridad) ;
						 if ($lb_guardar)
							{
								
								
								echo "El Grupo de Movimiento de Personal fue Registrado";
							}

					
					}
					
			}
			
		  elseif($evento=="eliminar")
			{
					
					
	
	
					list($lb_valido,$lb_existe)=$io_grupomovimiento->uf_srh_delete_grupomovimiento($ls_codgrumov,$la_seguridad);
			
					if($lb_existe)
					{
						echo 'El Grupo de Movimiento de Personal no pueder ser eliminado porque esta asociado e un movimiento de personal';
					}	
					else
					{
							if($lb_valido)
							{
						
								echo "El Grupo de Movimiento de Personal fue Eliminado";
								
							}
							else 
							
						   {
								echo "Error al eliminar Grupo de Movimiento de Personal";
								
							}
					}
					
					
			}
			elseif($evento=="existe")
			{
					
					$lb_existe= $io_grupomovimiento->uf_srh_select_grupomovimiento($ls_codgrumov);
					if ($lb_existe)
					{
					
						echo "El Grupo de Movimiento de Personal ya Existe";
					}
					
					
			}
			elseif($evento=="buscar")
			{
					
					
				    $ls_codgrumov="%".utf8_encode($_REQUEST['txtcodgrumov'])."%";
	                $ls_dengrumov="%".utf8_encode($_REQUEST['txtdengrumov'])."%";
					
					header('Content-type:text/xml');
					print $io_grupomovimiento->uf_srh_buscar_grupomovimiento($ls_codgrumov, $ls_dengrumov);
					
					
					
					
			}
			elseif($evento=="createXML")
			{
				  $ls_codgrumov="%%";
	              $ls_dengrumov="%%";
				   
				   header('Content-type:text/xml');
				   print $io_grupomovimiento->uf_srh_buscar_grupomovimiento($ls_codgrumov, $ls_dengrumov);
					
					
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
    $ls_salida = $io_grupomovimiento->uf_srh_getProximoCodigo();  

}

echo utf8_encode($ls_salida);
	
	

?>
