<?
	session_start();


//Initialization class variables
	require_once("../../class_folder/dao/sigesp_srh_c_tipopersonal.php");
    require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_tip=new sigesp_srh_c_tipopersonal('../../../');
	$io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_tipopersonal.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=utf8_encode($_SESSION["la_logusr"]);

     $ls_salida="";
	
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
		$ls_dentipper= utf8_decode ($_POST['txtdentipper']);
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
					$lb_existe= $io_tip->uf_srh_select_tipopersonal($ls_codtipper);
					if ($lb_existe)
					{
						
							$lb_update=$io_tip->uf_srh_update_tipopersonal($ls_codtipper,$ls_dentipper,$la_seguridad);
							
							if ($lb_update)
							{
								echo utf8_decode ("El Tipo de Personal fue Actulizado");
							}
							
					}
					else
					{
						$lb_guardar= $io_tip->uf_srh_insert_tipopersonal($ls_codtipper,$ls_dentipper,$la_seguridad);
						 if ($lb_guardar)
							{
								
									echo utf8_decode ("El Tipo de Personal fue Registrado");
							}

					
					}
					
			}
		
			elseif($evento=="eliminar")
			{
					list($lb_valido,$lb_existe)=$io_tip->uf_srh_delete_tipopersonal($ls_codtipper,$la_seguridad);
			
					if($lb_existe)
					{
						echo utf8_decode ("El Tipo de Personal no puede ser eliminado ");
					}	
					else
					{
							if($lb_valido)
							{
								echo utf8_decode ("El Tipo de Personal fue Eliminado");
								
							}
							else
							{
							   	echo utf8_decode ("Error al eliminar Tipo de Personal");
								
							}
					
					}
					
			}
			elseif($evento=="existe")
			{
					$lb_existe= $io_tip->uf_srh_select_tipopersonal($ls_codtipper);
					if ($lb_existe)
					{
						echo utf8_decode ("El Tipo de Personal ya existe");
					}
			}
			
			
			elseif($evento=="buscar")
			{
				    $ls_codtipper="%".utf8_encode($_REQUEST['txtcodtipper'])."%";
	                $ls_dentipper="%".utf8_encode($_REQUEST['txtdentipper'])."%";
					header('Content-type:text/xml');
					print $io_tip->uf_srh_buscar_tipopersonal($ls_codtipper, $ls_dentipper);
					
					
					
					
			}
			elseif($evento=="createXML")
			{
                       
				    $ls_codtipper="%%";
	                $ls_dentipper="%%";			
					 header('Content-type:text/xml');
					print $io_tip->uf_srh_buscar_tipopersonal($ls_codtipper, $ls_dentipper);
					
					
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
    $ls_salida = $io_tip->uf_srh_getProximoCodigo();  

}

echo utf8_encode($ls_salida);


?>
