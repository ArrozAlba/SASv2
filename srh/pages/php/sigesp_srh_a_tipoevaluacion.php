<?
	session_start();


//Initialization class variables
	require_once("../../class_folder/dao/sigesp_srh_c_tipoevaluacion.php");
    require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_tipeval=new sigesp_srh_c_tipoevaluacion('../../../');
	$io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_tipoevaluacion.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=utf8_encode($_SESSION["la_logusr"]);

    
	$ls_salida="";
	
	if (array_key_exists("txtcodeval",$_POST))
	{
		$ls_codeval=$_POST['txtcodeval'];
	}
	else
	{
		$ls_codeval="";
    }
	
	if (array_key_exists("txtdeneval",$_POST))
	{
		$ls_deneval= utf8_decode ($_POST['txtdeneval']);
	}
	else
	{
		$ls_deneval="";
    }
	if (array_key_exists("txtcodesc",$_POST))
	{
		$ls_codesc=$_POST['txtcodesc'];
	}
	else
	{
		$ls_codesc="";
    }
	
	
	if (isset($_GET['valor']))
	{
		
		$evento=$_GET['valor'];
		
			if($evento=="guardar")
			{				
					$lb_existe= $io_tipeval->uf_srh_select_tipoevaluacion($ls_codeval);
					if ($lb_existe)
					{
						
							$lb_update=$io_tipeval->uf_srh_update_tipoevaluacion($ls_codeval,$ls_deneval,$ls_codesc,$la_seguridad);
							
							if ($lb_update)
							{
								echo utf8_decode ("El Tipo de Evaluaci&oacute;n fue Actulizado");
							}
							
					}
					else
					{
						$lb_guardar= $io_tipeval->uf_srh_insert_tipoevaluacion($ls_codeval,$ls_deneval,$ls_codesc,$la_seguridad);
						 if ($lb_guardar)
							{
								
									echo utf8_decode ("El Tipo de Evaluaci&oacute;n fue Registrado");
							}

					
					}
					
			}
		
			elseif($evento=="eliminar")
			{
					list($lb_valido,$lb_existe)=$io_tipeval->uf_srh_delete_tipoevaluacion($ls_codeval,$la_seguridad);
			
					if($lb_existe)
					{
					   echo utf8_decode ("El Tipo de Evaluaci&oacute;nno puede ser eliminado porque esta asociado a un Aspecto de Evaluaci&oacute;n");	
					}	
					else
					{
							if($lb_valido)
							{
								echo utf8_decode ("El Tipo de Evaluaci&oacute;n fue Eliminado");
								
							}
							else
							{
							   echo utf8_decode ("Error al eliminar Tipo de Evaluaci&oacute;n");
								
							}
					
					}
					
			}
			elseif($evento=="existe")
			{
					$lb_existe= $io_tipeval->uf_srh_select_tipoevaluacion($ls_codeval);
					if ($lb_existe)
					{
						echo utf8_decode  ("El Tipo de Evaluaci&oacute;n ya existe");
					}
			}
			
			
			elseif($evento=="buscar")
			{
					
				   
				    $ls_codeval="%".utf8_encode($_REQUEST['txtcodeval'])."%";
	                $ls_deneval="%".utf8_encode($_REQUEST['txtdeneval'])."%";
					
					header('Content-type:text/xml');
					Print $io_tipeval->uf_srh_buscar_tipoevaluacion($ls_codeval, $ls_deneval);
	
					
			}
			elseif($evento=="createXML")
			{
                       
				    $ls_codeval="%%";
	                $ls_deneval="%%";
				
					 header('Content-type:text/xml');
					 print $io_tipeval->uf_srh_buscar_tipoevaluacion($ls_codeval, $ls_deneval);
					
					
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
    $ls_salida = $io_tipeval->uf_srh_getProximoCodigo();  

}

echo utf8_encode($ls_salida);

?>
