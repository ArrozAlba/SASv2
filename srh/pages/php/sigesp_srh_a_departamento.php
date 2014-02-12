<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_departamento.php");
    require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_dep=new sigesp_srh_c_departamento('../../../');
	$io_fun_srh=new class_funciones_srh('../../../');
	
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_departamento.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];

    $ls_salida="";
	
	if (array_key_exists("txtcoddep",$_POST))
	{
		$ls_coddep=$_POST["txtcoddep"];
	}
	else
	{
		//$ls_coddep="";
    }
	
	if (array_key_exists("txtdendep",$_POST))
	{
		$ls_dendep=utf8_decode ($_POST["txtdendep"]);
	}
	else
	{
		//$ls_dendep="";
    }
	if (array_key_exists("txtcoduniadm",$_POST))
	{
		$ls_uniadm=utf8_decode ($_POST["txtcoduniadm"]);
	}
	else
	{
		$ls_uniadm="";
    }
	if (array_key_exists("txtcodger",$_POST))
	{
		$ls_codger=utf8_decode ($_POST["txtcodger"]);
	}
	else
	{
		$ls_codger="";
    }
	
		
	if (isset($_GET['valor']))
	{
		
		$evento=$_GET['valor'];
		if(array_key_exists("codger",$_GET))
		{
			$ls_codger1=$_GET['codger']; 			
		}
		else
		{
			$ls_codger1=""; 
		}
				
			if($evento=="guardar")
			{				
					$lb_existe= $io_dep->uf_srh_select_departamento($ls_coddep);
					if ($lb_existe)
					{
						
							$lb_update=$io_dep->uf_srh_update_departamento($ls_coddep,$ls_dendep,$ls_uniadm,$ls_codger,$la_seguridad);
							
							if ($lb_update)
							{
								echo "El Departamento fue Actulizado";
							}
							
					}
					else
					{
						$lb_guardar= $io_dep->uf_srh_insert_departamento($ls_coddep,$ls_dendep,$ls_uniadm,$ls_codger,$la_seguridad);
						 if ($lb_guardar)
							{
								
									echo "El Departamento fue Registrado";
							}

					
					}
					
			}
		
			elseif($evento=="eliminar")
			{
					list($lb_valido,$lb_existe)=$io_dep->uf_srh_delete_departamento($ls_coddep,$la_seguridad);
			
					if($lb_existe)
					{
						echo "El Departamento no puede ser eliminado porque esta asociado a una seccion";
					}	
					else
					{
							if($lb_valido)
							{
								echo "El Departamento fue Eliminado";
								
							}
							else
							{
							   echo "Error al eliminar el Departamento";
								
							}
					
					}
					
			}
			elseif($evento=="existe")
			{
					$lb_existe= $io_dep->uf_srh_select_departamento($ls_coddep);
					if ($lb_existe)
					{
						echo "El Departamento ya existe";
					}
			}
			
			
			elseif($evento=="buscar")
			{
					
				   
				    $ls_coddep="%".utf8_encode($_REQUEST['txtcoddep'])."%";
	                $ls_dendep="%".utf8_encode($_REQUEST['txtdendep'])."%";					
					header('Content-type:text/xml');
					print $io_dep->uf_srh_buscar_departamento($ls_coddep, $ls_dendep,$ls_codger1);
					
					
					
					
			}
			elseif($evento=="createXML")
			{
 				  $ls_coddep="%%";
                  $ls_dendep="%%";
				  header('Content-type:text/xml');
				  print $io_dep->uf_srh_buscar_departamento($ls_coddep, $ls_dendep, $ls_codger1);
					
					
			}
				
		
	}	

?>
