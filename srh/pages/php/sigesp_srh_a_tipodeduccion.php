<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_tipodeduccion.php");
    require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_tipodeduccion=new sigesp_srh_c_tipodeduccion('../../../');
	$io_fun_srh=new class_funciones_srh('../../../');
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_tipodeduccion.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=$_SESSION["la_logusr"];
	
	$ls_salida="";
	
	if (array_key_exists("txtcodtipded",$_POST))
	{
		$ls_codtipded=$_POST['txtcodtipded'];
	}
	else
	{
		$ls_codtipded="";
    }
	
	if (array_key_exists("txtdentipded",$_POST))
	{
		$ls_dentipded=utf8_decode ($_POST['txtdentipded']);
	}
	else
	{
		$ls_dentipded="";
    }
	
	
	
	
	if (isset($_GET['valor']))
	{
		
		$evento=$_GET['valor'];
		
			if($evento=="guardar") 
			{
					
					
					$lb_existe= $io_tipodeduccion->uf_srh_select_tipodeduccion($ls_codtipded);
					if ($lb_existe)
					{
						    $lb_update=$io_tipodeduccion->uf_srh_update_tipodeduccion($ls_codtipded,$ls_dentipded,$la_seguridad) ;
							
							if ($lb_update)
							{
								
								
								echo utf8_decode ("El Tipo de Deducci&oacute;n fue Actulizado");
							}
							
					}
					else
					{
						$lb_guardar= $io_tipodeduccion->uf_srh_insert_tipodeduccion($ls_codtipded,$ls_dentipded,$la_seguridad) ;
						 if ($lb_guardar)
							{
								
								
								echo utf8_decode ("El Tipo de Deducci&oacute;n fue Registrado");
							}

					
					}
					
			}
			
							

		
		
			elseif($evento=="eliminar")
			{

					list($lb_valido,$lb_existe)=$io_tipodeduccion->uf_srh_delete_tipodeduccion($ls_codtipded,$la_seguridad);
			
					if($lb_existe)
					{
						echo utf8_decode ("El Tipo de Deducci&oacute;n no puede ser eliminada");
					}	
					else
					{
							if($lb_valido)
							{
						
								echo utf8_decode ("El Tipo de Deducci&oacute;n fue Eliminado");
								
							}
							else
							{
						
								echo utf8_decode ("Error al elmininar El Tipo de Deducci&oacute;n");
								
							}
						
					}
					
					
			}
			elseif($evento=="existe")
			{
					
					$lb_existe= $io_tipodeduccion->uf_srh_select_tipodeduccion($ls_codtipded);
					if ($lb_existe)
					{
					
						echo utf8_decode ("El Tipo de Deducci&oacute;n ya Existe");
					}
					
					
			}
			elseif($evento=="buscar")
			{
					   
				    $ls_codtipded="%".utf8_encode($_REQUEST['txtcodtipded'])."%";
	                $ls_dentipded="%".utf8_encode($_REQUEST['txtdentipded'])."%";
					
					header('Content-type:text/xml');
					print $io_tipodeduccion->uf_srh_buscar_tipodeduccion($ls_codtipded, $ls_dentipded);
					
					
					
					
			}
			elseif($evento=="createXML")
			{

    				$ls_codtipded="%%";
	                $ls_dentipded="%%";

					header('Content-type:text/xml');					
					print $io_tipodeduccion->uf_srh_buscar_tipodeduccion($ls_codtipded, $ls_dentipded);
					
					
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
    $ls_salida = $io_tipodeduccion->uf_srh_getProximoCodigo();  

}

echo utf8_encode($ls_salida);


?>
