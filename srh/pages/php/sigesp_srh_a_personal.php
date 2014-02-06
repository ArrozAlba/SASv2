<?

session_start();

require_once("../../class_folder/utilidades/class_funciones_srh.php");
$io_fun_srh=new class_funciones_srh('../../../');
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_personal.php",$ls_permisos,$la_seguridad,$la_permisos);
$ls_logusr=utf8_encode($_SESSION["la_logusr"]);

require_once("../../class_folder/dao/sigesp_srh_c_personal.php");
$io_personal=new sigesp_srh_c_personal('../../../');
require_once("../../class_folder/dao/sigesp_srh_c_pais.php");
$io_pais=new sigesp_srh_c_pais('../../../');
require_once("../../class_folder/dao/sigesp_srh_c_estado.php");
$io_estado=new sigesp_srh_c_estado('../../../');
require_once("../../class_folder/dao/sigesp_srh_c_municipio.php");
$io_municipio=new sigesp_srh_c_municipio('../../../');
require_once("../../class_folder/dao/sigesp_srh_c_parroquia.php");
$io_parroquia=new sigesp_srh_c_parroquia('../../../');
require_once("../../class_folder/dao/sigesp_srh_c_tipodeduccion.php");
$io_deduccion=new sigesp_srh_c_tipodeduccion('../../../');



$ls_salida="";


if (isset($_GET['valor']))
{
	    $evento=$_GET['valor'];

		if($evento=="createXML")
		{
			$ls_codper="%%";
			$ls_cedper="%%";
			$ls_apeper="%%";
			$ls_nomper="%%";
			$ls_tipo=$_REQUEST['hidtipo'];
			 
			header('Content-type:text/xml');
			print $io_personal->uf_srh_buscar_personal($ls_codper,$ls_cedper,$ls_apeper,$ls_nomper,$ls_tipo);		
			
			
		}
		
		elseif($evento=="buscar")
		{
			$ls_codper="%".utf8_encode($_REQUEST['txtcodper'])."%";
			$ls_cedper="%".utf8_encode($_REQUEST['txtcedper'])."%";
			$ls_apeper="%".utf8_encode($_REQUEST['txtapeper'])."%";
			$ls_nomper="%".utf8_encode($_REQUEST['txtnomper'])."%";
			$ls_tipo=$_REQUEST['hidtipo'];
					
			header('Content-type:text/xml');
			print $io_personal->uf_srh_buscar_personal($ls_codper,$ls_cedper,$ls_apeper,$ls_nomper,$ls_tipo);
			
			
		}
		elseif($evento=="createXML_estudios")
		{
			$ls_codper=$_GET['codper'];
			header('Content-type:text/xml');
			print $io_personal->uf_srh_buscar_estudios($ls_codper);		
			
			
		}
		elseif($evento=="createXML_trabajos")
		{
			$ls_codper=$_GET['codper'];
			header('Content-type:text/xml');
			print $io_personal->uf_srh_buscar_trabajos($ls_codper);		
			
			
		}
		elseif($evento=="createXML_familiares")
		{
			$ls_codper=$_GET['codper'];
			header('Content-type:text/xml');
			print $io_personal->uf_srh_buscar_familiares($ls_codper);		
			
			
		}
		elseif($evento=="createXML_permisos")
		{
			$ls_codper=$_GET['codper'];
			header('Content-type:text/xml');
			print $io_personal->uf_srh_buscar_permisos($ls_codper);		
			
			
		}
		elseif($evento=="createXML_deducciones")
		{
			$ls_codper=$_GET['codper'];
			header('Content-type:text/xml');
			print $io_personal->uf_srh_buscar_deducciones($ls_codper);		
			
			
		}
		elseif($evento=="createXML_deducciones_fam")
		{
			$ls_codper=$_GET['codper'];
			header('Content-type:text/xml');
			print $io_personal->uf_srh_buscar_deducciones_familiar($ls_codper);		
			
			
		}
		elseif($evento=="createXML_det_deduccion")
		{
			$ls_codded=$_GET['codded'];			
			$ls_tipo=$_GET['tipo'];			
			$ls_nexfam=$_GET['nexfam'];
			$ls_sexper=$_GET['sexper'];
			header('Content-type:text/xml');
			print $io_deduccion->uf_srh_buscar_detalles_deducciones($ls_codded,$ls_tipo,$ls_nexfam, $ls_sexper);		
			
			
		}
		elseif($evento=="createXML_movimientos")
		{
			
			$ls_codper="%%";
			$ls_apeper="%%";
			$ls_nomper="%%";
			$ls_nummov="%%";
			
			header('Content-type:text/xml');
			print $io_personal->uf_srh_buscar_movimientos($ls_codper,$ls_nomper,$ls_apeper,$ls_nummov);		
			
			
		}
		elseif($evento=="buscar_movimiento")
		{
			
			$ls_codper="%".utf8_encode($_REQUEST['txtcodper'])."%";
			$ls_apeper="%".utf8_encode($_REQUEST['txtapeper'])."%";
			$ls_nomper="%".utf8_encode($_REQUEST['txtnomper'])."%";
			$ls_nummov="%".utf8_encode($_REQUEST['txtnummov'])."%";
			
			
			header('Content-type:text/xml');
			print $io_personal->uf_srh_buscar_movimientos($ls_codper,$ls_nomper,$ls_apeper,$ls_nummov);		
			
			
		}
		elseif($evento=="createXML_beneficiarios")
		{
			$ls_codper=$_GET['codper'];
			header('Content-type:text/xml');
			print $io_personal->uf_srh_buscar_beneficiarios($ls_codper);		
			
			
		}
		elseif($evento=="createXML_premio")
		{
			$ls_codper=$_GET['codper'];
			header('Content-type:text/xml');
			print $io_personal->uf_srh_buscar_premio($ls_codper);		
			
			
		}
		
			
	
}


require_once("../../../shared/class_folder/JSON.php");
$io_json=new JSON();

if (array_key_exists("operacion",$_GET))
{
  $ls_operacion = $_GET["operacion"];  
}
else if (array_key_exists("operacion",$_POST))
{
  $ls_operacion = $_POST["operacion"];  
}
else 
{
  $ls_operacion = ""; 
}



if($ls_operacion == "ue_chequear_codpersonal")
{
 	 $lb_existe = $io_personal->getCodPersonal($_GET["codper"],$la_datos);
	  if ($lb_existe)
	  {
	   $ls_salida  ='Existe el Código de Personal';
	  }
}
elseif($ls_operacion == "ue_chequear_cedpersonal")
{
 	 list($lb_existe,$ls_codper) = $io_personal->getCedPersonal($_GET["cedper"],$la_datos);
	  if ($lb_existe)
	  {
	 
	   $ls_salida  ='La Cédula ya Existe en Código de Personal  '.$ls_codper;
	  }

}
elseif ($ls_operacion == "ue_inicializar")
{

  ///////// PAISES /////////////

  $lb_hay = $io_pais->getPais("ORDER BY despai ASC",$la_paises);
   if ($lb_hay)
   {$ls_salida = $io_json->encode($la_paises);} 

    
}
elseif ($ls_operacion == "ue_inicializarestado")
{
	  $lb_hay = $io_estado->getEstados($_GET["codpai"],"ORDER BY desest ASC",$la_estados);
	  if ($lb_hay)
	  {$ls_salida  = $io_json->encode($la_estados);}
}
elseif ($ls_operacion == "ue_inicializarmunicipio")
{
	  $lb_hay = $io_municipio->getMunicipios($_GET["codpai"],$_GET["codest"],"ORDER BY denmun ASC",$la_municipios);
	  if ($lb_hay)
	  {$ls_salida  = $io_json->encode($la_municipios);}
}

elseif ($ls_operacion == "ue_inicializarparroquia")
{
	  $lb_hay = $io_parroquia->getparroquias($_GET["codpai"],$_GET["codest"],$_GET["codmun"],"ORDER BY denpar ASC",$la_parroquias);
	  if ($lb_hay)
	  {$ls_salida  = $io_json->encode($la_parroquias);}
}

//OPERACIONES PARA EL MANEJO DEL REGISTRO PERSONAL

if ($ls_operacion == "ue_guardar")
{  
  $objeto = str_replace('\"','"',$_POST["objeto"]);
  $lo_personal = $io_json->decode(utf8_decode ($objeto));
  $valido= $io_personal->uf_srh_guardarPersonal ($lo_personal,$_POST["insmod"],$la_seguridad); 
  if ($valido)
  {
     if ($_POST["insmod"]=='modificar')
	  {$ls_salida = 'El Personal fue Actualizado';	}
 	 else { $ls_salida = 'El Personal fue Registrado';}
  }
  else {$ls_salida = 'Error al guardar el personal';} 
  }

//OPERACIONES PARA EL MANEJO DE LOS ESTUDIOS DEL PERSONAL

if ($ls_operacion == "ue_nuevo_estudio")
{  
      $ls_salida = $io_personal->uf_srh_getProximoCodigo_estudio($_GET["codper"]);  
}

if ($ls_operacion == "ue_guardar_estudios")
{  
    $objeto2 = str_replace('\"','"',$_POST["objeto2"]);	
	$lo_estudio = $io_json->decode(utf8_decode ($objeto2));	
    $valido_est= $io_personal->uf_srh_guardar_estudios($lo_estudio,$_POST["insmod"],$la_seguridad);
	if ($valido_est)
	{
		 if ($_POST["insmod"]=='modificar')
		  {$ls_salida = 'El Estudio fue Actualizado';	}
		 else { $ls_salida = 'El Estudio fue Registrado';}
	 }
	 else {$ls_salida = 'Error al guardar el estudio';} 
  }   

if ($ls_operacion == "ue_eliminar_estudio")
{  
    $valido= $io_personal->uf_srh_eliminar_estudio($_GET["codest"],$_GET["codper"],$la_seguridad);  
	if ($valido)
	{
	  $ls_salida = 'El Estudio fue Eliminado';
	 }
	 else {$ls_salida = 'Error al Eliminar Estudio';} 

}


//OPERACIONES PARA EL MANEJO DE LOS TRABAJOS ANTERIORES DEL PERSONAL

if ($ls_operacion == "ue_nuevo_trabajo")
{  
   $ls_salida = $io_personal->uf_srh_getProximoCodigo_trabajo($_GET["codper"]);  
}

if ($ls_operacion == "ue_guardar_trabajos")
{  
    $objeto3 = str_replace('\"','"',$_POST["objeto3"]);	
	$lo_trabajo = $io_json->decode(utf8_decode ($objeto3));	
	$valido_trab= $ls_salida =$io_personal->uf_srh_guardar_trabajo($lo_trabajo,$_POST["insmod"],$la_seguridad); 
	if ($valido_trab)
	{
		 if ($_POST["insmod"]=='modificar')
		  {$ls_salida = 'La Experiencia Laboral fue Actualizada';	}
		 else { $ls_salida = 'La Experiencia Laboral fue Registrada';}
	 }
	 else {$ls_salida = 'Error al Guardar la Experiencia Laboral';}
	
}
if ($ls_operacion == "ue_eliminar_trabajo")
{  
    $valido= $io_personal->uf_srh_eliminar_trabajo($_GET["codtrabant"],$_GET["codper"],$la_seguridad);  
	if ($valido)
	{
	  $ls_salida = 'La Experiencia Laboral fue Eliminada';
	 }
	 else {$ls_salida = 'Error al Eliminar Experiencia Laboral';} 

}

if ($ls_operacion == "ue_buscar_servicio_previo")
{  
      $ls_salida = $io_personal->uf_select_anotrabajoantfijo($_GET["codper"]);
	  $ls_salida .= "&";
	  $ls_salida .= $io_personal->uf_select_anotrabajoantcontratado($_GET["codper"]);

}


// OPERACIONES PARA EL MANEJO DE LOS FAMILIARES DEL PERSONAL

if ($ls_operacion == "ue_guardar_familiares")
{  
    $objeto4 = str_replace('\"','"',$_POST["objeto4"]);	
	$familiar = $io_json->decode(utf8_decode ($objeto4));	
    $valido_fam= $io_personal->uf_srh_guardar_familiar($familiar,$_POST["insmod"],$la_seguridad);  
	if ($valido_fam)
	{
		 if ($_POST["insmod"]=='modificar')
		  {$ls_salida = 'El Familiar fue Actualizado';	}
		 else { $ls_salida = 'El Familiar fue Registrado';}
	 }
	 else {$ls_salida = 'Error al Guardar el Familiar';} 
   
}


if ($ls_operacion == "ue_eliminar_familiar")
{  
    
	list($valido,$existe)= $io_personal->uf_srh_eliminar_familiar($_GET["cedfam"],$_GET["codper"],$la_seguridad); 
	  if ($existe)
	  {$ls_salida = 'El familiar no puede ser eliminado';}
	  else 
	  {
		  if ($valido)
		  {$ls_salida = 'El Familiar fue Eliminado';}
		  else 
		  {$ls_salida = 'Error al Eliminar Familiar';}
	
  }

}


//OPERACIONES PARA EL MANEJO DE LOS BENEFICIARIOS DEL PERSONAL

if ($ls_operacion == "ue_nuevo_beneficiario")
{  
  
	$ls_salida = $io_personal->uf_srh_getProximoCodigo_Beneficiario($_GET["codper"]);  
}

if ($ls_operacion == "ue_guardar_beneficiario")
{  
    $objeto4 = str_replace('\"','"',$_POST["objeto4"]);	
	$lo_ben = $io_json->decode(utf8_decode ($objeto4));	
    $valido_ben= $io_personal->uf_srh_guardar_beneficiario($lo_ben,$_POST["insmod"],$la_seguridad);
	if ($valido_ben)
	{
		 if ($_POST["insmod"]=='modificar')
		  {$ls_salida = 'El Beneficiario fue Actualizado';	}
		 else { $ls_salida = 'El Beneficiario fue Registrado';}
	 }
	 else {$ls_salida = 'Error al Guardar Beneficiario';} 
  }  
  
 if ($ls_operacion == "ue_eliminar_beneficiario")
{  
    $valido= $io_personal->uf_srh_eliminar_beneficiario($_GET["codben"],$_GET["codper"],$_GET["tipben"],$la_seguridad);  
	if ($valido)
	{
	  $ls_salida = 'El Beneficiario fue eliminado';
	 }
	 else {$ls_salida = 'Error al Eliminar Beneficiario';} 
}


//OPERACIONES PARA EL MANEJO DE LOS PERMISOS DEL PERSONAL

if ($ls_operacion == "ue_nuevo_permiso")
{  
   $ls_salida = $io_personal->uf_srh_getProximoCodigo_permiso($_GET["codper"]);  
}

if ($ls_operacion == "ue_guardar_permiso")
{  
    $objeto5 = str_replace('\"','"',$_POST["objeto5"]);	
	$permiso = $io_json->decode(utf8_decode ($objeto5));	
    $valido_per= $io_personal->uf_srh_guardar_permiso($permiso,$_POST["insmod"],$la_seguridad);  
	if ($valido_per)
	{
		 if ($_POST["insmod"]=='modificar')
		  {$ls_salida = 'El Permiso fue Actualizado';	}
		 else { $ls_salida = 'El Permiso fue Registrado';}
	 }
	 else {$ls_salida = 'Error al Guardar el Permiso';} 
}

if ($ls_operacion == "ue_eliminar_permiso")
{  
    $valido= $io_personal->uf_srh_eliminar_permiso($_GET["numper"],$_GET["codper"],$la_seguridad);  
	if ($valido)
	{
	  $ls_salida = 'El Permiso fue Eliminado';
	 }
	 else {$ls_salida = 'Error al Eliminar Permiso';} 
}

//OPERACIONES PARA EL MANEJO DE LOS MOVIMIENTOS DEL PERSONAL

if ($ls_operacion == "ue_nuevo_movimiento")
{  
  
	$ls_salida = $io_personal->uf_srh_getProximoCodigo_Movimiento();  
}

if ($ls_operacion == "ue_guardar_movimiento")
{  
    $objeto7 = str_replace('\"','"',$_POST["objeto7"]);	
	$lo_movimiento = $io_json->decode(utf8_decode ($objeto7));	
    $valido_mov= $io_personal->uf_srh_guardar_movimiemto($lo_movimiento,$_POST["insmod"],$la_seguridad);
	if ($valido_mov)
	{
		 if ($_POST["insmod"]=='modificar')
		  {$ls_salida = 'El Movimiento de Personal fue Actualizado';	}
		 else { $ls_salida = 'El Movimiento de Personal fue Registrado';}
	 }
	 else {$ls_salida = 'Error al Guardar el Movimiento de Personal';} 
  }  
  
 if ($ls_operacion == "ue_eliminar_movimiento")
{  
    $valido= $io_personal->uf_srh_eliminar_movimiento($_GET["nummov"],$_GET["codper"],$la_seguridad);  
	if ($valido)
	{
	  $ls_salida = 'El movimiento fue eliminado';
	 }
	 else {$ls_salida = 'Error al Eliminar Movimiento';} 
}

if ($ls_operacion == "valida_mov_nom")
{  
    $valido= $io_personal->uf_srh_validar_movimiento_nomina($_GET["codper"]);  
	if ($valido)
	{
	  $ls_salida = 'NO';
	}
	else 
	{
	  $ls_salida = 'SI';
	} 
}


//OPERACIONES PARA EL MANEJO DE LAS DEDUCCIONES DEL PERSONAL

if ($ls_operacion == "ue_guardar_deduccion")
{  
    $objeto = str_replace('\"','"',$_POST["objeto6"]);	
	$lo_deduccion = $io_json->decode(utf8_decode ($objeto));	
	
	 list($valido,$existe)=$io_personal->uf_srh_guardar_deducion($lo_deduccion,$_POST["insmod"],$la_seguridad);
  if ($existe)
  {
  	$ls_salida = 'La Deduccion de Personal ya se encuentra asignada al personal.';
  }
  else 
  {
	   if ($valido)
	   {
			if ($_POST["insmod"]=='modificar')
			{
				$ls_salida = 'La Deduccion fue Actualizada';	
			}
			else
			{
				 $ls_salida = 'La Deduccion fue Registrada';
			}
	  }
	  else 
	  {
	  	$ls_salida = 'Error al Guardar la Deduccion';
	  } 
  }	
}  

if ($ls_operacion == "ue_eliminar_deduccion")
{  
    	
	 list($valido,$existe)= $io_personal->uf_srh_eliminar_deduccion($_GET["codtipded"],$_GET["codper"],$la_seguridad);  
	  if ($existe)
	  {$ls_salida = 'La deduccion no pueden ser eliminada porque esta asociada a un familiar';}
	  else 
	  {
		  if ($valido)
		  {$ls_salida = 'La Deduccion fue Eliminada';}
		  else 
		  {$ls_salida = 'Error al Eliminar Deduccion';}
	  }

}
if ($ls_operacion == "ue_buscar_deduccion")
{  
    
    $existe= $io_personal->uf_srh_select_deduccion($_GET["codper"]);
	if (!$existe)
	{
		  $ls_salida = 'no encontro';
	 }
	 
  } 
  
  //OPERACIONES PARA EL MANEJO DE LAS PREMIACIONES


if ($ls_operacion == "ue_nuevo_premio")
{  
  
	$ls_salida = $io_personal->uf_srh_getProximoCodigo_Premio($_GET["codper"]);  
}
if ($ls_operacion == "ue_guardar_premio")
{  
    $objeto = str_replace('\"','"',$_POST["objeto"]);	
	$lo_premio = $io_json->decode(utf8_decode ($objeto));	
    $valido_ded= $io_personal->uf_srh_guardar_premio($lo_premio,$_POST["insmod"],$la_seguridad);
	if ($valido_ded)
	{
		 if ($_POST["insmod"]=='modificar')
		  {$ls_salida = 'La Premiacion fue Actualizada';	}
		 else { $ls_salida = 'La Premiacion fue Registrada';}
	 }
	 else {$ls_salida = 'Error al Guardar la Premiacion';} 
  }  

if ($ls_operacion == "ue_eliminar_premio")
{  
    	
	 $valido= $io_personal->uf_srh_eliminar_premio ($_GET["numprem"],$_GET["codper"],$la_seguridad);  
	  
	  if ($valido)
	  {$ls_salida = 'La Premiacion fue Eliminada';}
	  else 
	  {$ls_salida = 'Error al Eliminar Premiacion';}
	

}

 
 
//OPERACIONES PARA EL MANEJO DE LAS DEDUCCIONES DE LOS FAMILIARES DEL PERSONAL
if ($ls_operacion == "ue_guardar_deduccion_fam")
{  
    $objeto6 = str_replace('\"','"',$_POST["objeto"]);	
	$lo_deduccion = $io_json->decode(utf8_decode ($objeto6));	
	
	
	 list($valido,$existe)= $io_personal->uf_srh_guardar_deducion_familiar($lo_deduccion,$_POST["insmod"],$la_seguridad);
	  if ($existe)
	  {
		$ls_salida = 'La Deduccion ya se encuentra asignada al familiar.';
	  }
	  else 
	  {
		   if ($valido)
		   {
				if ($_POST["insmod"]=='modificar')
				{
					$ls_salida = 'La Deduccion del Familiar fue Actualizada';	
				}
				else
				{
					 $ls_salida = 'La Deduccion del Familiar fue Registrada';
				}
		  }
		  else 
		  {
			$ls_salida = 'Error al Guardar la Deduccion del Familiar';
		  } 	
	}	
   
  }  
   
if ($ls_operacion == "ue_eliminar_deduccion_fam")
{  
    $valido= $io_personal->uf_srh_eliminar_deduccion_familiar($_GET["codtipded"],$_GET["codper"], $_GET["cedfam"],$la_seguridad);  
	if ($valido)
	{
	  $ls_salida = 'La Deduccion del Familiar fue Eliminada';
	 }
	 else {$ls_salida = 'Error al Eliminar Deduccion de Familiar';} 

}
elseif ($ls_operacion == "calcular_monto_deduccion")
{  
    $ls_salida =$io_personal->uf_srh_calcular_monto_deduccion ($_GET["codper"], $_GET["codtipded"], $_GET["coddettipded"]);

}

elseif ($ls_operacion == "calcular_monto_deduccion_fam")
{  
    $ls_salida =$io_personal->uf_srh_calcular_monto_deduccion_fam($_GET["codper"], $_GET["codtipded"], $_GET["cedfam"], $_GET["coddettipded"]);

}
elseif ($ls_operacion == "ue_buscar_cargo_actual")
{  
    $ls_salida =$io_personal->uf_srh_ue_buscar_cargo_actual ($_GET["codper"]);

}
elseif ($ls_operacion == "ue_buscar_sueldo_actual")
{  
    $ls_salida =$io_personal->uf_srh_ue_buscar_sueldo_actual ($_GET["codper"]);

}
elseif ($ls_operacion == "ue_buscar_uniadm_actual")
{  
    $ls_salida =$io_personal->uf_srh_buscar_uniadm ($_GET["codper"]);

}

 echo utf8_encode($ls_salida);
 

?>