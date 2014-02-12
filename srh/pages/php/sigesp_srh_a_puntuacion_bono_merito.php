<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_puntuacion_bono_merito.php");
    require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_punt=new sigesp_srh_c_puntuacion_bono_merito('../../../');
	$io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_puntuacion_bono_merito.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
	
	$ls_salida="";
	
	if (array_key_exists("txtcodpunt",$_POST))
	{
		$ls_codpunt=$_POST['txtcodpunt'];
	}
	else
	{
		$ls_codpunt="";
    }
	
	if (array_key_exists("txtnombpunt",$_POST))
	{
		$ls_nombpunt=utf8_decode ($_POST['txtnombpunt']);
	}
	else
	{
		$ls_nombpunt="";
    }
	if (array_key_exists("txtdespunt",$_POST))
	{
		$ls_despunt=utf8_decode ($_POST['txtdespunt']);
	}
	else
	{
		$ls_despunt="";
    }
	
	if (array_key_exists("txtvalini",$_POST))
	{
		$li_valini=$_POST['txtvalini'];
	}
	else
	{
		$li_valini="";
    }
	if (array_key_exists("txtvalfin",$_POST))
	{
		$li_valfin=$_POST['txtvalfin'];
	}
	else
	{
		$li_valfin="";
    }
	
	if (array_key_exists("txtcodtipper",$_POST))
	{
		$ls_codtipper=$_POST['txtcodtipper'];
	}
	else
	{
		$ls_codtipper="";
    }
	if (array_key_exists("txtdentipper",$_POST))
	{
		$ls_dentipper=utf8_decode ($_POST['txtdentipper']);
	}
	else
	{
		$ls_dentipper="";
    }
	
	
	
	if (isset($_GET['valor']))
	{
		
		$evento=$_GET['valor'];
		
			if($evento=="guardar")
			{
					$lb_existe= $io_punt->uf_srh_select_puntuacion_bono_merito($ls_codpunt);
					if ($lb_existe)
					{
						
							$lb_update=$io_punt->uf_srh_update_puntuacion_bono_merito($ls_codpunt,$ls_nombpunt,$ls_despunt,$li_valini,$li_valfin,$ls_codtipper,$la_seguridad);
							
							if ($lb_update)
							{
								echo "La Puntuacion fue Actualizada";
							}
							
					}
					else
					{
						$lb_guardar= $io_punt->uf_srh_insert_puntuacion_bono_merito($ls_codpunt,$ls_nombpunt,$ls_despunt,$li_valini,$li_valfin,$ls_codtipper,$la_seguridad);
						 if ($lb_guardar)
							{
								echo "La Puntuacion fue Registrada";
							}

					
					}
					
			}
			elseif($evento=="eliminar")
			{
					list($lb_valido,$lb_existe)=$io_punt->uf_srh_delete_puntuacion_bono_merito($ls_codpunt,$la_seguridad);
			
					if($lb_existe)
					{
						echo 'La Puntuacion no puede ser elimianda porque esta asociada a un Bono por Merito ';
					}	
					else
					{	if($lb_valido)
							{
								echo 'La Puntuacion fue Eliminada';
								
							}
							else
							{
							  	echo 'Error al eliminar la Puntuacion';
								
							}
					
					}
					
					
			}
			elseif($evento=="existe")
			{
					$lb_existe= $io_punt->uf_srh_select_puntuacion_bono_merito($ls_codpunt);
					if ($lb_existe)
					{
						echo "La Puntuacion ya existe";
					}
			}
			
			
			elseif($evento=="buscar")
			{
				    $ls_codpunt="%".utf8_encode($_REQUEST['txtcodpunt'])."%";
	                $ls_nombpunt="%".utf8_encode($_REQUEST['txtnombpunt'])."%";
					$ls_codtipper="%".utf8_encode($_REQUEST['txtcodtipper'])."%";
					
					header('Content-type:text/xml');
					print $io_punt->uf_srh_buscar_puntuacion_bono_merito($ls_codpunt, $ls_nombpunt, $ls_codtipper);
					
					
			}
			elseif($evento=="createXML")
			{
        		    $ls_codpunt="%%";
	                $ls_nombpunt="%%";
					$ls_codtipper="%%";

					header('Content-type:text/xml');
					print  $io_punt->uf_srh_buscar_puntuacion_bono_merito($ls_codpunt, $ls_nombpunt, $ls_codtipper);
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
    $ls_salida = $io_punt->uf_srh_getProximoCodigo();  

}

echo utf8_encode($ls_salida);
	

?>
