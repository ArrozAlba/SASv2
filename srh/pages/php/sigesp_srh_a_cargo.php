<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_cargo.php");
    require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_cargo=new sigesp_srh_c_cargo('../../../');
	$io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_cargo.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];

	 $ls_salida="";
	
	if (array_key_exists("txtcodcar",$_POST))
	{
		$ls_codcar=$_POST['txtcodcar'];
	}
	else
	{
		$ls_codcar="";
    }
	
	if (array_key_exists("txtdescar",$_POST))
	{
		$ls_descar=utf8_decode ($_POST['txtdescar']);
	}
	else
	{
		$ls_descar="";
    }
	
	if (array_key_exists("txtcodnom",$_POST))
	{
		$ls_codnom=$_POST['txtcodnom'];
	}
	else
	{
		$ls_codnom="";
    }
	
	
	
	
	
	if (isset($_GET['valor']))
	{
		
		$evento=$_GET['valor'];
		
			if($evento=="guardar")
			{
					
					
					$lb_existe= $io_cargo->uf_srh_select_cargo($ls_codcar);
					if ($lb_existe)
					{
						
							$lb_update=$io_cargo->uf_srh_update_cargo($ls_codcar,$ls_descar,$ls_codnom,$la_seguridad);
							
							if ($lb_update)
							{
								
							
								echo "El Cargo fue Actualizado";
							}
							
					}
					else
					{
						$lb_guardar= $io_cargo->uf_srh_insert_cargo($ls_codcar,$ls_descar,$ls_codnom,$la_seguridad);
						 if ($lb_guardar)
							{
								
							
								echo "El Cargo fue Registrado";
							}

					
					}
					
			}
			
							

		
		
			elseif($evento=="eliminar")
			{
					
					
	
	
					list($lb_valido,$lb_existe)=$io_cargo->uf_srh_delete_cargo($ls_codcar, $ls_codnom,$la_seguridad);
			
					if($lb_existe)
					{
						echo 'El Cargo no puede ser eliminado';					}	
					else
					{
							if($lb_valido)
							{
								
								echo "El Cargo fue Eliminado";
								
							}
							else
							{
							   echo "Error al eliminar Cargo";
								
							}
					
					}
					
					
			}
			elseif($evento=="existe")
			{
					$lb_existe= $io_cargo->uf_srh_select_cargo($ls_codcar);
					if ($lb_existe)
					{
						
						echo "El Cargo ya Existe";
					}
					
					
			}elseif($evento=="buscar")
			{
					$ls_codcar="%".utf8_encode($_REQUEST['txtcodcar'])."%";
	                $ls_descar="%".utf8_encode($_REQUEST['txtdescar'])."%";
					$ls_codnom="%".utf8_encode($_REQUEST['txtcodnom'])."%";
					 
					header('Content-type:text/xml');
					print $io_cargo->uf_srh_buscar_cargo($ls_codcar,$ls_descar,$ls_codnom);
					
			
			}
			elseif($evento=="createXML")
			{
			      $ls_codcar="%%";
	              $ls_descar="%%";
				  $ls_codnom="%%";

					header('Content-type:text/xml');
					print $io_cargo->uf_srh_buscar_cargo($ls_codcar,$ls_descar,$ls_codnom);
			}

			elseif($evento=="createXML_nomina")
			{
			$ls_codnom="%%";
			$ls_desnom="%%";
			
		    header('Content-type:text/xml');
			print $io_cargo->uf_srh_buscar_nomina($ls_codnom,$ls_desnom);
			}
			
			elseif($evento=="buscar_nomina")
			{
			$ls_codnom="%".utf8_encode($_REQUEST['txtcodnom'])."%";
			$ls_desnom="%".utf8_encode($_REQUEST['txtdesnom'])."%";		
			
			header('Content-type:text/xml');
			print $io_cargo->uf_srh_buscar_nomina($ls_codnom,$ls_desnom);
			}
			elseif($evento=="buscar_rac")
			{
					$ls_codcar="%".utf8_encode($_REQUEST['txtcodcar'])."%";
	                $ls_descar="%".utf8_encode($_REQUEST['txtdescar'])."%";
					
					 
					header('Content-type:text/xml');
					print $io_cargo->uf_srh_buscar_cargo_rac($ls_codcar,$ls_descar);
					
			
			}
			elseif($evento=="createXML_rac")
			{
			      $ls_codcar="%%";
	              $ls_descar="%%";
				 

					header('Content-type:text/xml');
					print $io_cargo->uf_srh_buscar_cargo_rac($ls_codcar,$ls_descar);
			}
				
	}
		




?>
