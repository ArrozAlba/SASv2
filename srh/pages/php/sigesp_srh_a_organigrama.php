<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_organigrama.php");
    require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_org=new sigesp_srh_c_organigrama('../../../');
	$io_fun_srh=new class_funciones_srh('../../../');
	
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_organigrama.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];

    $ls_salida="";
	
	if (array_key_exists("txtcodorg",$_POST))
	{
		$ls_codorg=$_POST["txtcodorg"];
	}
	else
	{
		$ls_codorg="";
    }
	
	if (array_key_exists("txtdesorg",$_POST))
	{
		$ls_desorg=utf8_decode ($_POST["txtdesorg"]);
	}
	else
	{
		$ls_desorg="";
    }
	if (array_key_exists("cmbnivorg",$_POST))
	{
		$ls_nivorg=utf8_decode ($_POST["cmbnivorg"]);
	}
	else
	{
		$ls_nivorg="";
    }
	if (array_key_exists("txtpadorg",$_POST))
	{
		$ls_padorg=utf8_decode ($_POST["txtpadorg"]);
	}
	else
	{
		$ls_padorg="";
    }
	
		
	if (isset($_GET['valor']))
	{
		
		$evento=$_GET['valor'];
			
			if($evento=="guardar")
			{				
					$lb_existe= $io_org->uf_srh_select_organigrama($ls_codorg);
					if ($lb_existe)
					{
						
							$lb_update=$io_org->uf_srh_update_organigrama($ls_codorg, $ls_desorg, $ls_nivorg, $ls_padorg, 
							                                              $la_seguridad);
							
							if ($lb_update)
							{
								echo "El Organigrama fue Actulizado";
							}
							
					}
					else
					{
						$lb_guardar= $io_org->uf_srh_insert_organigrama($ls_codorg, $ls_desorg, $ls_nivorg, $ls_padorg, 
							                                              $la_seguridad);
						 if ($lb_guardar)
							{
								
									echo "El Organigrama fue Registrado";
							}

					
					}
					
			}
		
			elseif($evento=="eliminar")
			{
					list($lb_valido,$lb_existe)=$io_org->uf_srh_delete_organigrama($ls_codorg,$la_seguridad);
			
					if($lb_existe)
					{
						echo "El Organigrama no puede ser eliminado porque esta asociado a un personal o es padre de un nivel inferior";
					}	
					else
					{
							if($lb_valido)
							{
								echo "El Organigrama fue Eliminado";
								
							}
							else
							{
							   echo "Error al Eliminar el Organigrama";
								
							}
					
					}
					
			}
			elseif($evento=="existe")
			{
					$lb_existe= $io_org->uf_srh_select_organigrama($ls_codorg);
					if ($lb_existe)
					{
						echo "El Organigrama ya existe";
					}
			}
			
			
			elseif($evento=="buscar")
			{
					
				   
				    $ls_codorg="%".utf8_encode($_REQUEST['txtcodorg'])."%";
	                $ls_desorg="%".utf8_encode($_REQUEST['txtdesorg'])."%";
					$ls_nivorg=utf8_encode($_REQUEST['cmbnivorg']);
					
					header('Content-type:text/xml');
					print $io_org->uf_srh_buscar_organigrama($ls_codorg,$ls_desorg,$ls_nivorg);
					
					
					
					
			}
			elseif($evento=="createXML")
			{
 				  $ls_codorg="%%";
                  $ls_desorg="%%";
				  if(array_key_exists("nivel",$_GET))
				  {
						$ls_nivorg=$_GET['nivel'];
						$ls_tipo=trim($_GET['tipo']);
						if (($ls_nivorg!="")&&($ls_tipo==1))
						{ 
							$ls_nivorg= intval($ls_nivorg) - 1;
						}
						else
						{
							$ls_nivorg= intval($ls_nivorg);
						}
				  }
				  else
				  {
						$ls_nivorg=""; 
				  }
				  header('Content-type:text/xml');
				  print $io_org->uf_srh_buscar_organigrama($ls_codorg,$ls_desorg,$ls_nivorg);
					
					
			}
				
		
	}	

?>
