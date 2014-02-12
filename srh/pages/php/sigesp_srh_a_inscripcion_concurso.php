<?

session_start();

require_once("../../class_folder/utilidades/class_funciones_srh.php");
$io_fun_srh=new class_funciones_srh('../../../');
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_inscripcion_concurso.php",$ls_permisos,$la_seguridad,$la_permisos);
$ls_logusr=utf8_encode($_SESSION["la_logusr"]);

require_once("../../class_folder/dao/sigesp_srh_c_inscripcion_concurso.php");
$io_inscripcion=new sigesp_srh_c_inscripcion_concurso('../../../');
require_once("../../class_folder/dao/sigesp_srh_c_pais.php");
$io_pais=new sigesp_srh_c_pais('../../../');
require_once("../../class_folder/dao/sigesp_srh_c_estado.php");
$io_estado=new sigesp_srh_c_estado('../../../');
require_once("../../class_folder/dao/sigesp_srh_c_municipio.php");
$io_municipio=new sigesp_srh_c_municipio('../../../');
require_once("../../class_folder/dao/sigesp_srh_c_parroquia.php");
$io_parroquia=new sigesp_srh_c_parroquia('../../../');

$ls_salida="";


if (isset($_GET['valor']))
{
	    $evento=$_GET['valor'];

		if($evento=="buscar")
		{
			$ls_codcon="%".utf8_encode($_REQUEST['txtcodcon'])."%";
			$ls_cedper="%".utf8_encode($_REQUEST['txtcedper'])."%";
			$ls_apeper="%".utf8_encode($_REQUEST['txtapeper'])."%";
			$ls_nomper="%".utf8_encode($_REQUEST['txtnomper'])."%";
						
			header('Content-type:text/xml');
			print $io_inscripcion->uf_srh_buscar_concursante($ls_codcon,$ls_cedper,$ls_apeper,$ls_nomper);
			
			
		}
		elseif($evento=="createXML_estudios")
		{
			$ls_codper=$_GET['codper'];
			$ls_codcon=$_GET['codcon'];
			header('Content-type:text/xml');
			print $io_inscripcion->uf_srh_buscar_estudios_concursante($ls_codper,$ls_codcon);		
			
			
		}
		elseif($evento=="createXML_trabajos")
		{
			$ls_codper=$_GET['codper'];
			$ls_codcon=$_GET['codcon'];
			header('Content-type:text/xml');
			print $io_inscripcion->uf_srh_buscar_trabajos_concursantes($ls_codper,$ls_codcon);		
			
			
		}
		elseif($evento=="createXML_familiares")
		{
			$ls_codper=$_GET['codper'];
			$ls_codcon=$_GET['codcon'];
			header('Content-type:text/xml');
			print $io_inscripcion->uf_srh_buscar_familiares_concursante($ls_codper,$ls_codcon);		
			
			
		}
		elseif($evento=="createXML_cursos")
		{
			$ls_codper=$_GET['codper'];
			$ls_codcon=$_GET['codcon'];
			header('Content-type:text/xml');
			print $io_inscripcion->uf_srh_buscar_cursos_concursante($ls_codper,$ls_codcon);		
			
			
		}
			elseif($evento=="createXML_Persona_Concurso")
		{
			$ls_codper="%%";
			$ls_apeper="%%";
			$ls_nomper="%%";
			$ls_hidcodcon=$_REQUEST['hidcodcon'];			 
			
		    header('Content-type:text/xml');			
			print $io_inscripcion->uf_srh_buscar_personal_concurso($ls_codper,$ls_apeper,$ls_nomper,$ls_hidcodcon);
		}
		
		elseif($evento=="buscar_Persona_Concurso")
		{
			$ls_codper="%".utf8_encode($_REQUEST['txtcodper'])."%";
			$ls_apeper="%".utf8_encode($_REQUEST['txtapeper'])."%";
			$ls_nomper="%".utf8_encode($_REQUEST['txtnomper'])."%";
			$ls_hidcodcon=$_REQUEST['hidcodcon'];
					
			header('Content-type:text/xml');			
			print $io_inscripcion->uf_srh_buscar_personal_concurso($ls_codper,$ls_apeper,$ls_nomper,$ls_hidcodcon);
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


if($ls_operacion == "ue_chequear_cedula")
{
 	 list($lb_existe,$ls_codcon) = $io_inscripcion->getCedPersonal($_GET["codper"],$_GET["codcon"],$la_datos);
	  if ($lb_existe)
	  {
	 
	   $ls_salida  ='El código del personal ya Existe en el concurso  '.$ls_codcon;
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

//OPERACIONES PARA EL MANEJO DEL REGISTRO CONCURSANTE

if ($ls_operacion == "ue_guardar")
{  
  $objeto = str_replace('\"','"',$_POST["objeto"]);
  $lo_personal = $io_json->decode(utf8_decode ($objeto));
  $valido= $io_inscripcion->uf_srh_guardarConcursante($lo_personal,$_POST["insmod"],$la_seguridad); 
  if ($valido)
  {
     if ($_POST["insmod"]=='modificar')
	 {
	 	$ls_salida = 'El Registro de Concursante fue Actualizado';	
	 }
 	 else 
	 { 
	 	$ls_salida = 'El Registro de Concursante fue Registrado';
	}
  }
  else 
  {
  	$ls_salida = 'Error al Guardar el Registro de Concursante';
  } 
}
elseif ($ls_operacion == "ue_eliminar")
{  
   
   list($valido,$existe)=$io_inscripcion->uf_srh_eliminarConcursante($_GET["codcon"],$_GET["codper"], $la_seguridad);
  if ($existe)
  {$ls_salida = 'El Registro de Concursante no puede ser eliminado porque esta asociado a una evaluacion de aspirantes';}
  else 
  {
	  if ($valido)
	  {$ls_salida = 'El Registro de Concursante fue Eliminado';}
	  else 
	  {$ls_salida = 'Ocurrio un error al eliminar el Registro de Concursante';}
  }
  
}
elseif ($ls_operacion == "ue_nuevo_codigo")
{  
    $ls_salida = $io_inscripcion->uf_srh_getProximoCodigo();  

}

//OPERACIONES PARA EL MANEJO DE LOS ESTUDIOS

if ($ls_operacion == "ue_nuevo_estudio")
{  
      $ls_salida = $io_inscripcion->uf_srh_getProximoCodigo_estudio($_GET["codper"],$_GET["codcon"]);  
}

if ($ls_operacion == "ue_guardar_estudios")
{  
    $objeto2 = str_replace('\"','"',$_POST["objeto2"]);	
	$lo_estudio = $io_json->decode(utf8_decode ($objeto2));	
	$valido_est=$io_inscripcion->uf_srh_select_concursante($lo_estudio->codcon,$lo_estudio->codper);	
	if (!$valido_est)
	{
		$ls_salida = 'Los Datos Basicos deben estar registrados para poder realizar esta operacion';
	}
	else
	{
		 $valido_est= $io_inscripcion->uf_srh_guardar_estudios($lo_estudio,$_POST["insmod"],$la_seguridad);
		if ($valido_est)
		{
			 if ($_POST["insmod"]=='modificar')
			  {$ls_salida = 'El Estudio fue Actualizado';	}
			 else { $ls_salida = 'El Estudio fue Registrado';}
		 }
		 else {$ls_salida = 'Error al guardar el estudio';} 
			
	}

  }   

if ($ls_operacion == "ue_eliminar_estudio")
{  
    $valido= $io_inscripcion->uf_srh_eliminar_estudio($_GET["codest"],$_GET["codper"],$_GET["codcon"],$la_seguridad);  
	if ($valido)
	{
	  $ls_salida = 'El Estudio fue Eliminado';
	 }
	 else {$ls_salida = 'Error al Eliminar Estudio';} 

}

//OPERACIONES PARA EL MANEJO DE LOS CURSOS

if ($ls_operacion == "ue_nuevo_curso")
{  
      $ls_salida = $io_inscripcion->uf_srh_getProximoCodigo_curso($_GET["codper"],$_GET["codcon"]);  
}

if ($ls_operacion == "ue_guardar_cursos")
{  
    $objeto2 = str_replace('\"','"',$_POST["objeto2"]);	
	$lo_curso = $io_json->decode(utf8_decode ($objeto2));	
    $valido_est=$io_inscripcion->uf_srh_select_concursante($lo_curso->codcon,$lo_curso->codper);	
	if (!$valido_est)
	{
		$ls_salida = 'Los Datos Basicos deben estar registrados para poder realizar esta operacion';
	}
	else
	{
		$valido_est= $io_inscripcion->uf_srh_guardar_cursos($lo_curso,$_POST["insmod"],$la_seguridad);
		if ($valido_est)
		{
			 if ($_POST["insmod"]=='modificar')
			  {$ls_salida = 'El Curso fue Actualizado';	}
			 else { $ls_salida = 'El Curso fue Registrado';}
		 }
		 else {$ls_salida = 'Error al Guardar el Curso';} 
	}
  }   

if ($ls_operacion == "ue_eliminar_cursos")
{  
    $valido= $io_inscripcion->uf_srh_eliminar_cursos($_GET["codcur"],$_GET["codper"],$_GET["codcon"],$la_seguridad);  
	if ($valido)
	{
	  $ls_salida = 'El Curso fue Eliminado';
	 }
	 else {$ls_salida = 'Error al Eliminar Curso';} 

}



//OPERACIONES PARA EL MANEJO DE LOS TRABAJOS ANTERIORES 

if ($ls_operacion == "ue_nuevo_trabajo")
{  
   $ls_salida = $io_inscripcion->uf_srh_getProximoCodigo_trabajo($_GET["codper"],$_GET["codcon"]);  
}

if ($ls_operacion == "ue_guardar_trabajos")
{  
    $objeto3 = str_replace('\"','"',$_POST["objeto2"]);	
	$lo_trabajo = $io_json->decode(utf8_decode ($objeto3));	
	$valido_trab=$io_inscripcion->uf_srh_select_concursante($lo_trabajo->codcon,$lo_trabajo->codper);	
	if (!$valido_trab)
	{
		$ls_salida = 'Los Datos Basicos deben estar registrados para poder realizar esta operacion';
	}
	else
	{
		$valido_trab= $ls_salida =$io_inscripcion->uf_srh_guardar_trabajo($lo_trabajo,$_POST["insmod"],$la_seguridad); 
		if ($valido_trab)
		{
			 if ($_POST["insmod"]=='modificar')
			  {$ls_salida = 'La Experiencia Laboral fue Actualizada';	}
			 else { $ls_salida = 'La Experiencia Laboral fue Registrada';}
		 }
		 else {$ls_salida = 'Error al Guardar la Experiencia Laboral';}
	}
	
}
if ($ls_operacion == "ue_eliminar_trabajo")
{  
    $valido= $io_inscripcion->uf_srh_eliminar_trabajo($_GET["codtrab"],$_GET["codper"],$_GET["codcon"],$la_seguridad);  
	if ($valido)
	{
	  $ls_salida = 'La Experiencia Laboral fue Eliminada';
	 }
	 else {$ls_salida = 'Error al Eliminar Experiencia Laboral';} 

}


// OPERACIONES PARA EL MANEJO DE LOS FAMILIARES 

if ($ls_operacion == "ue_nuevo_familiar")
{  
   $ls_salida = $io_inscripcion->uf_srh_getProximoCodigo_familiar($_GET["codper"],$_GET["codcon"]);  
}

if ($ls_operacion == "ue_guardar_familiar")
{  
    $objeto4 = str_replace('\"','"',$_POST["objeto2"]);	
	$familiar = $io_json->decode(utf8_decode ($objeto4));
	$valido_fam=$io_inscripcion->uf_srh_select_concursante($familiar->codcon,$familiar->codper);	
	if (!$valido_fam)
	{
		$ls_salida = 'Los Datos Basicos deben estar registrados para poder realizar esta operacion';
	}
	else
	{
		 $valido_fam= $io_inscripcion->uf_srh_guardar_familiar($familiar,$_POST["insmod"],$la_seguridad);  
		if ($valido_fam)
		{
			 if ($_POST["insmod"]=='modificar')
			  {$ls_salida = 'El Familiar fue Actualizado';	}
			 else { $ls_salida = 'El Familiar fue Registrado';}
		 }
		 else {$ls_salida = 'Error al Guardar el Familiar';} 
	}

}


if ($ls_operacion == "ue_eliminar_familiar")
{  
   
    $valido= $io_inscripcion->uf_srh_eliminar_familiar($_GET["codfam"],$_GET["codper"],$_GET["codcon"],$la_seguridad); 
	if ($valido)
	{
	  $ls_salida = 'El Familiar fue Eliminado';
	 }
	 else {$ls_salida = 'Error al Eliminar Familiar';}
	

}


// OPERACIONES PARA EL MANEJO DE LOS REQUISITOS

if ($ls_operacion == "ue_guardar_requisitos")
{  
  $objeto = str_replace('\"','"',$_POST["objeto"]);
  $io_req = $io_json->decode(utf8_decode ($objeto));  
  $valido=$io_inscripcion->uf_srh_select_concursante($io_req ->codcon,$io_req->codper);	
	if (!$valido)
	{
		$ls_salida = 'Los Datos Basicos deben estar registrados para poder realizar esta operacion';
	}
	else
	{
		  list($valido,$existe)=$io_inscripcion->uf_srh_guardar_requisitos_concursante($io_req, $la_seguridad);
		  if (($valido)&&(!$existe))
		  {
				$ls_salida = 'Los Requisitos del Concursante se Registraron';
				
		  }
		  else if (($valido)&&($existe))
		  {
				$ls_salida = 'Los Requisitos del Concursante se Actualizaron';
		  }
		  else if (!$valido)
		  {
				$ls_salida = 'Error al guardar los Requisitos del Concursante';
		  }
	}
  
  
}

 echo utf8_encode($ls_salida);
 

?>