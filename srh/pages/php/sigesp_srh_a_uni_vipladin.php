<?
	session_start();


//Initialization class variables
	require_once("../../class_folder/dao/sigesp_srh_c_uni_vipladin.php");
   require_once("../../class_folder/utilidades/class_funciones_srh.php");
	$io_uni_vi=new sigesp_srh_c_uni_vipladin('../../../');
	$io_fun_srh=new class_funciones_srh('../../../');
	
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_uni_vipladin.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_logusr=utf8_encode($_SESSION["la_logusr"]);

    global $ls_salida, $ls_coduni, $ls_denuni;

    $ls_salida="";
	
	if (isset($_POST['txtcodunivi']))
	{$ls_coduni=$_POST['txtcodunivi'];}
	
	if (isset($_POST['txtdenunivi']))
	{$ls_denuni= utf8_decode ($_POST['txtdenunivi']);}

    
	
	if (isset($_GET['valor']))
	{
		
		$evento=$_GET['valor'];
		
			if($evento=="guardar")
			{				
					$lb_existe= $io_uni_vi->uf_srh_select_uni_vipladin($ls_coduni);
					if ($lb_existe)
					{
						
							$lb_update=$io_uni_vi->uf_srh_update_uni_vipladin($ls_coduni,$ls_denuni,$la_seguridad);
							
							if ($lb_update)
							{
								echo utf8_decode ("La Unidad VIPLADIN fue Actulizada");
							}
							
					}
					else
					{
						$lb_guardar= $io_uni_vi->uf_srh_insert_uni_vipladin($ls_coduni,$ls_denuni,$la_seguridad);
						 if ($lb_guardar)
							{
								
									echo utf8_decode ("La Unidad VIPLADIN fue Registrada");
							}

					
					}
					
			}
		
			elseif($evento=="eliminar")
			{
					list($lb_valido,$lb_existe)=$io_uni_vi->uf_srh_delete_uni_vipladin($ls_coduni,$la_seguridad);
			
					if($lb_existe)
					{
						echo utf8_decode ("La Unidad VIPLADIN no puede ser eliminada");
					}	
					else
					{
							if($lb_valido)
							{
								echo utf8_decode ("La Unidad VIPLADIN fue Eliminada");
								
							}
							else
							{
							  echo utf8_decode ("Error al eliminar la Unidad VIPLADIN");
								
							}
					
					}
					
			}
			elseif($evento=="existe")
			{
					$lb_existe= $io_uni_vi->uf_srh_select_uni_vipladin($ls_coduni);
					if ($lb_existe)
					{
						echo utf8_decode ("La Unidad VIPLADIN ya existe");
					}
			}
			
			
			elseif($evento=="buscar")
			{
					
				   
				    $ls_coduni="%".utf8_encode($_REQUEST['txtcodunivi'])."%";
	                $ls_denuni="%".utf8_encode($_REQUEST['txtdenunivi'])."%";
					
					header('Content-type:text/xml');
					print $io_uni_vi->uf_srh_buscar_uni_vipladin($ls_coduni, $ls_denuni);
					
					
					
					
			}
			elseif($evento=="createXML")
			{
 				  $ls_coduni="%%";
                  $ls_denuni="%%";
				  header('Content-type:text/xml');
				 print $io_uni_vi->uf_srh_buscar_uni_vipladin($ls_coduni, $ls_denuni);
					
					
			}
				
		
	}	
	



echo utf8_encode($ls_salida);	

?>
