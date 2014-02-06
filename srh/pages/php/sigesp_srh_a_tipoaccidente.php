<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_tipoaccidente.php");
    require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_tipoaccidente=new sigesp_srh_c_tipoaccidente('../../../');
	$io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_tipoaccidente.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
	
	$ls_salida="";
	
	if (array_key_exists("txtcodacc",$_POST))
	{
		$ls_codacc=$_POST['txtcodacc'];
	}
	else
	{
		$ls_codacc="";
    }
	
	if (array_key_exists("txtdenacc",$_POST))
	{
		$ls_denacc=utf8_decode ($_POST['txtdenacc']);
	}
	else
	{
		$ls_denacc="";
    }
	
	
	
	
	if (isset($_GET['valor']))
	{
		
		$evento=$_GET['valor'];
		
			if($evento=="guardar") 
			{
					
					
					$lb_existe= $io_tipoaccidente->uf_srh_select_tipoaccidente($ls_codacc);
					if ($lb_existe)
					{
						 
							$lb_update=$io_tipoaccidente->uf_srh_update_tipoaccidente($ls_codacc,$ls_denacc,$la_seguridad) ;
							
							if ($lb_update)
							{
								
								
								echo utf8_decode ("El Tipo de Accidente fue Actulizado");
							}
							
					}
					else
					{
						$lb_guardar= $io_tipoaccidente->uf_srh_insert_tipoaccidente($ls_codacc,$ls_denacc,$la_seguridad) ;
						 if ($lb_guardar)
							{
								
								
								echo utf8_decode ("El Tipo de Accidente fue Registrado");
							}

					
					}
					
			}
			
							

		
		
			elseif($evento=="eliminar")
			{

					list($lb_valido,$lb_existe)=$io_tipoaccidente->uf_srh_delete_tipoaccidente($ls_codacc,$la_seguridad);
			
					if($lb_existe)
					{
						echo utf8_decode ("El Tipo de Accidente no puede ser eliminado porque esta asociado a un accidente");
					}	
					else
					{
							if($lb_valido)
							{
						
								echo utf8_decode ("El Tipo de Accidente fue Eliminado");
								
							}
							else
							{
						
								echo utf8_decode ("Error al eliminar Tipo de Accidente");
								
							}
						
					}
					
					
			}
			elseif($evento=="existe")
			{
					
					$lb_existe= $io_tipoaccidente->uf_srh_select_tipoaccidente($ls_codacc);
					if ($lb_existe)
					{
					
						echo utf8_decode ("El Tipo de Accidente ya Existe");
					}
					
					
			}
			elseif($evento=="buscar")
			{
					
				   
				    $ls_codacc="%".utf8_encode($_REQUEST['txtcodacc'])."%";
	                $ls_denacc="%".utf8_encode($_REQUEST['txtdenacc'])."%";
					
					header('Content-type:text/xml');
					print $io_tipoaccidente->uf_srh_buscar_tipoaccidente($ls_codacc, $ls_denacc);
					
					
					
					
			}
			elseif($evento=="createXML")
			{

				$ls_codacc="%%";
	             $ls_denacc="%%";
					
					header('Content-type:text/xml');
					print $io_tipoaccidente->uf_srh_buscar_tipoaccidente($ls_codacc, $ls_denacc);
					
					
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
    $ls_salida = $io_tipoaccidente->uf_srh_getProximoCodigo();  

}

echo utf8_encode($ls_salida);


?>
