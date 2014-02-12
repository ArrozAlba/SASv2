<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_gerencia.php");
    require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_ger=new sigesp_srh_c_gerencia('../../../');
	$io_fun_srh=new class_funciones_srh('../../../');
	
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_gerencia.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];

    $ls_salida="";
	
	if (array_key_exists("txtcodger",$_POST))
	{
		$ls_codger=$_POST["txtcodger"];
	}
	else
	{
		$ls_codger="";
    }
	
	if (array_key_exists("txtdenger",$_POST))
	{
		$ls_denger=utf8_decode ($_POST["txtdenger"]);
	}
	else
	{
		$ls_denger="";
    }
	
	
		
	if (isset($_GET['valor']))
	{
		
		$evento=$_GET['valor'];
		
				
			if($evento=="guardar")
			{				
					$lb_existe= $io_ger->uf_srh_select_gerencia($ls_codger);
					if ($lb_existe)
					{
						
							$lb_update=$io_ger->uf_srh_update_gerencia($ls_codger,$ls_denger,$la_seguridad);
							
							if ($lb_update)
							{
								echo "La Gerencia fue Actulizada";
							}
							
					}
					else
					{
						$lb_guardar= $io_ger->uf_srh_insert_gerencia($ls_codger,$ls_denger,$la_seguridad);
						 if ($lb_guardar)
							{
								
									echo "La Gerencia fue Registrada";
							}

					
					}
					
			}
		
			elseif($evento=="eliminar")
			{
					list($lb_valido,$lb_existe)=$io_ger->uf_srh_delete_gerencia($ls_codger,$la_seguridad);
			
					if($lb_existe)
					{
						echo "La Gerencia no puede ser eliminado porque esta asociada a un Departamento";
					}	
					else
					{
							if($lb_valido)
							{
								echo "La Gerencia fue Eliminada";
								
							}
							else
							{
							   echo "Error al eliminar la Gerencia";
								
							}
					
					}
					
			}
			elseif($evento=="existe")
			{
					$lb_existe= $io_ger->uf_srh_select_gerencia($ls_codger);
					if ($lb_existe)
					{
						echo "La Gerencia ya existe";
					}
			}
			
			
			elseif($evento=="buscar")
			{
					
				   
				    $ls_codger="%".utf8_encode($_REQUEST['txtcodger'])."%";
	                $ls_denger="%".utf8_encode($_REQUEST['txtdenger'])."%";
					
					header('Content-type:text/xml');
					print $io_ger->uf_srh_buscar_gerencia($ls_codger, $ls_denger);
					
					
					
					
			}
			elseif($evento=="createXML")
			{
 				  $ls_codger="%%";
                  $ls_denger="%%";
				  header('Content-type:text/xml');
				  print $io_ger->uf_srh_buscar_gerencia($ls_codger, $ls_denger);
					
					
			}
				
		
	}	

?>
