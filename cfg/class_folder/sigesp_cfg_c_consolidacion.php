<?php
class sigesp_cfg_c_consolidacion
 {
  	var $ls_sql="";
	var $io_msg_error;
	var $la_seguridad;
	
	
function sigesp_cfg_c_consolidacion()//Constructor de la Clase.
{
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	
	$this->seguridad = new sigesp_c_seguridad();		  
	$this->io_fun    = new class_funciones();
	$io_conect       = new sigesp_include();
	$conn            = $io_conect->uf_conectar();
	$this->la_emp    = $_SESSION["la_empresa"];
	$this->io_sql    = new class_sql($conn); //Instanciando  la clase sql
	$this->io_msg    = new class_mensajes();
}

function uf_select_consolidacion($as_nombd)
{	
	$ls_cadena = "SELECT codemp FROM sigesp_consolidacion WHERE nombasdat='".$as_nombd."' ";
	$rs_data   = $this->io_sql->select($ls_cadena);
	if ($rs_data===false)
	   {
		$this->io_msg->message("CLASE->sigesp_cfg_c_consolidacion; METODO->uf_select_consolidacion;ERROR->".$this->io_fun->uf_convertirmsg($this->io_sql->message));   
		 $lb_valido=false;
	   }
	else
	   {
		 if ($row=$this->io_sql->fetch_row($rs_data))
		    {
			  $lb_valido=true;
		      $this->io_sql->free_result($rs_data);
			}
	 	 else
		    {
		 	  $lb_valido=false;
	  	    }
	   }
	return $lb_valido;
}

function uf_load_datos_consolidacion()
{	
	$ls_sql  = "SELECT codemp, nombasdat, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, estcla
	              FROM sigesp_consolidacion";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		 $this->io_msg->message("CLASE->sigesp_cfg_c_consolidacion; METODO->uf_load_datos_consolidacion;ERROR->".$this->io_fun->uf_convertirmsg($this->io_sql->message));   
		 $lb_valido=false;
	   }
	return $rs_data;
}

function uf_procesar_consolidacion($as_empresa,$as_nombd,$as_codestpro,$as_estcla,$aa_seguridad)
{  	   
   $ls_codestpro = trim($as_codestpro);
   $ls_codestpro1 = str_pad(substr($ls_codestpro,0,$_SESSION["la_empresa"]["loncodestpro1"]),25,0,0);
   $ls_codestpro2 = str_pad(substr($ls_codestpro,($_SESSION["la_empresa"]["loncodestpro1"]+3),$_SESSION["la_empresa"]["loncodestpro2"]),25,0,0);
   if ($_SESSION["la_empresa"]["estmodest"]==2)
	  {
		$ls_codestpro3 = str_pad(substr($ls_codestpro,$_SESSION["la_empresa"]["loncodestpro1"]+$_SESSION["la_empresa"]["loncodestpro2"]+6,$_SESSION["la_empresa"]["loncodestpro3"]),25,0,0);
		
		$ls_codestpro4 = str_pad(substr($ls_codestpro,-($_SESSION["la_empresa"]["loncodestpro4"]+$_SESSION["la_empresa"]["loncodestpro5"]+3),$_SESSION["la_empresa"]["loncodestpro4"]),25,0,0);
		$ls_codestpro5 = str_pad(substr($ls_codestpro,-$_SESSION["la_empresa"]["loncodestpro5"]),25,0,0);
	  }
   else
	  {
		$ls_codestpro3 = str_pad(substr($ls_codestpro,-$_SESSION["la_empresa"]["loncodestpro3"]),25,0,0);
		$ls_codestpro4 = $ls_codestpro5 = str_pad("",25,0,0);
	  }
   if ($as_estcla=='PROYECTO')
	  {
		$ls_estcla = "P";
	  }
   elseif($as_estcla=='ACCIÓN')
	  {
		$ls_estcla = "A";
	  }
   $ls_sql = "INSERT INTO sigesp_consolidacion(codemp,nombasdat,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla)
			   VALUES ('".$as_empresa."', '".$as_nombd."' ,'".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."',
					   '".$ls_codestpro4."','".$ls_codestpro5."','".$ls_estcla."') ";
   
   $rs_data = $this->io_sql->execute($ls_sql);
   if ($rs_data===false)
	  {
		$lb_valido=false;
		$this->io_msg->message("CLASE->sigesp_cfg_c_consolidacion; METODO->uf_procesar_consolidacion;ERROR->".$this->io_fun->uf_convertirmsg($this->io_sql->message));   
		echo $this->io_sql->message;
		$this->io_sql->rollback();
	  }
   else
	  {				
		$ls_descripcion = "Inserto la información de consolidacion: base de datos ".$as_nombd."en la Estructura $ls_codestpro1-$ls_codestpro2-$ls_codestpro3-$ls_codestpro4-$ls_codestpro5 de Tipo $as_estcla";
		$this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],"INSERT",
														$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
		$this->io_sql->commit();
		$lb_valido=true;
	  }
   return $lb_valido;	
 }
 
function uf_delete_consolidacion($as_empresa,$aa_seguridad)
{
	$lb_valido= false; 
	
	$ls_sql="DELETE FROM sigesp_consolidacion";
	$rs_data = $this->io_sql->execute($ls_sql);
	$this->io_sql->begin_transaction();
	if ($rs_data===false)
	   {
		 $lb_valido=false;
	     $this->io_msg_error->message("CLASE->sigesp_cfg_c_consolidacion; METODO->uf_delete_consolidacion;ERROR->".$this->io_fun->uf_convertirmsg($this->io_sql->message));   
	   }
	else
	   {
	   	 $ls_evento="DELETE";
	     $ls_descripcion="Eliminó en CFG la informacion de consolidacion";
		 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		 $aa_seguridad["ventanas"],$ls_descripcion);
	     /////////////////////////////////         SEGURIDAD               ///////////////////////////// 
		 $lb_valido = true;
	   }
	   if($lb_valido)
	   {
			$this->io_sql->commit();
	   }
	   else
	   {
			$this->io_sql->rollback();
	   }
    return $lb_valido;	
}

function uf_delete_datos_consolidacion($as_empresa,$aa_seguridad)
{
	$lb_valido= false; 
	
	$ls_sql  = "DELETE FROM sigesp_consolidacion";
	$rs_data = $this->io_sql->execute($ls_sql);
	$this->io_sql->begin_transaction();
	if ($rs_data===false)
	   {
		 $lb_valido=false;
		 $this->io_sql->rollback();
	     $this->io_msg_error->message("CLASE->sigesp_cfg_c_consolidacion; METODO->uf_delete_consolidacion;ERROR->".$this->io_fun->uf_convertirmsg($this->io_sql->message));   
	   }
	else
	   {
	   	 $ls_evento="DELETE";
	     $ls_descripcion="Eliminó en CFG la informacion de consolidacion para realizar Insert de la Informacion";
		 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		 $aa_seguridad["ventanas"],$ls_descripcion);
	     /////////////////////////////////         SEGURIDAD               ///////////////////////////// 
		 $lb_valido = true;
	   }
    return $lb_valido;	
}
}//Fin de la Clase.
?>