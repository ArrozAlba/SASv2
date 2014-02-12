<?
	session_start();
	
	require_once("../../class_folder/dao/sigesp_srh_c_concurso.php");
    require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_con=new sigesp_srh_c_concurso('../../../');
	$io_fun_srh=new class_funciones_srh('../../../');
	
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_concurso.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=utf8_encode($_SESSION["la_logusr"]);
	
	
	
	$ls_salida="";
	
	if (array_key_exists("txtcodcon",$_POST))
	{
		$ls_codcon=$_POST['txtcodcon'];
	}
	else
	{
		$ls_codcon="";
    }
	
	if (array_key_exists("txtdescon",$_POST))
	{
		$ls_descon=utf8_decode ($_POST['txtdescon']);
	}
	else
	{
		$ls_descon="";
    }
	if (array_key_exists("txtfechaaper",$_POST))
	{
		$ls_fechaaper=$_POST['txtfechaaper'];
	}
	else
	{
		$ls_fechaaper="";
    }
	if (array_key_exists("txtfechacie",$_POST))
	{
		$ls_fechacie=$_POST['txtfechacie'];
	}
	else
	{
		$ls_fechacie="";
    }
	if (array_key_exists("txtcodcar",$_POST))
	{
		$ls_codcar=$_POST['txtcodcar'];
	}
	else
	{
		$ls_codcar="";
    }
	if (array_key_exists("txtcantcar",$_POST))
	{
		$li_cantcar=$_POST['txtcantcar'];
	}
	else
	{
		$li_cantcar="";
    }
	if (array_key_exists("comboestatus",$_POST))
	{
		$ls_estatus=utf8_decode ($_POST['comboestatus']);
	}
	else
	{
		$ls_estatus="";
    }
	if (array_key_exists("combotipo",$_POST))
	{
		$ls_tipo=utf8_decode ($_POST['combotipo']);
	}
	else
	{
		$ls_tipo="";
    }
	if (array_key_exists("txtcodnom",$_POST))
	{
		$ls_codnom=utf8_decode ($_POST['txtcodnom']);
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
					$lb_existe= $io_con->uf_srh_select_concurso($ls_codcon);
					if ($lb_existe)
					{
						
							$lb_update=$io_con->uf_srh_update_concurso($ls_codcon,$ls_descon,$ls_fechaaper,$ls_fechacie,$ls_codcar,$li_cantcar,$ls_estatus,$ls_tipo, $ls_codnom,$la_seguridad);
							
							if ($lb_update)
							{
								echo "El Concurso fue Actualizado";
							}
							
					}
					else
					{
									   
						$lb_guardar= $io_con->uf_srh_insert_concurso($ls_codcon,$ls_descon,$ls_fechaaper,$ls_fechacie,$ls_codcar,$li_cantcar,$ls_estatus,$ls_tipo, $ls_codnom,$la_seguridad);
						 if ($lb_guardar)
							{
								echo "El Concurso fue Registrado";
							}

					
					}
					
			}
			elseif($evento=="eliminar")
			{
					list($lb_valido,$lb_existe)=$io_con->uf_srh_delete_concurso($ls_codcon,$la_seguridad);
			
					if($lb_existe)
					{
						echo "El concurso no puede ser eliminado";
					}	
					else
					{	if($lb_valido)
							{
								echo "El Concurso fue Eliminado";
								
							}
							else
							{
							   echo "Error al eliminar Concurso";
								
							}
					
					}
					
					
			}
			elseif($evento=="existe")
			{
					$lb_existe= $io_con->uf_srh_select_concurso($ls_codcon);
					if ($lb_existe)
					{
						echo "El Concurso ya existe";
					}
			}
			
			
			elseif($evento=="buscar")
			{
				    $ls_tipo=$_REQUEST['txttipo'];
					$ls_tipo_caja=$_REQUEST['hidtipo'];
					
				    $ls_codcon="%".utf8_encode($_REQUEST['txtcodcon'])."%";
	                $ls_descon="%".utf8_encode($_REQUEST['txtdescon'])."%";
					$ls_fechaaper1="%".utf8_encode($_REQUEST['txtfechaaper'])."%";
	                $ls_fechacie1="%".utf8_encode($_REQUEST['txtfechacie'])."%";
					$ls_estatus="%".utf8_encode($_REQUEST['comboestatus'])."%";
					$ls_fechaaper2='1/01/2108';
					$ls_fechacie2='1/01/2108';
				
					
					header('Content-type:text/xml');
					print $io_con->uf_srh_buscar_concurso($ls_codcon,$ls_descon,$ls_fechaaper1,$ls_fechaaper2,$ls_fechacie1,$ls_fechacie2, $ls_estatus,$ls_tipo,$ls_tipo_caja);
					
					
					
			}
			elseif($evento=="createXML")
			{
				   $ls_codcon="%%";
	                $ls_descon="%%";
					$ls_fechaaper1='01/01/1900';
					$ls_fechaaper2='01/01/2108';
					$ls_fechacie1='01/01/1900';
					$ls_fechacie2='01/01/2108';
					$ls_estatus="%%";
										
					$ls_tipo=$_REQUEST['txttipo'];
					$ls_tipo_caja=$_REQUEST['hidtipo'];				
					
					header('Content-type:text/xml');
					print $io_con->uf_srh_buscar_concurso($ls_codcon,$ls_descon,$ls_fechaaper1,$ls_fechaaper2,$ls_fechacie1,$ls_fechacie2, $ls_estatus,$ls_tipo,$ls_tipo_caja);
					
					
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
    $ls_salida = $io_con->uf_srh_getProximoCodigo();  

}

echo utf8_encode($ls_salida);
	
	

?>
