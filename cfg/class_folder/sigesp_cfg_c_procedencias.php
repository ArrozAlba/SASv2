<?php
class sigesp_cfg_c_procedencias
 {
    var $ls_sql="";
	var $io_msg_error;
	
	function sigesp_cfg_c_procedencias()//Constructor de la Clase.
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
        require_once("../shared/class_folder/class_funciones.php");
		$this->seguridad  = new sigesp_c_seguridad();		  
        $this->io_funcion = new class_funciones();
		$io_conect        = new sigesp_include();
		$conn             = $io_conect->uf_conectar();
		$this->la_emp     = $_SESSION["la_empresa"];
		$this->io_sql     = new class_sql($conn); //Instanciando  la clase sql
		$this->io_msg     = new class_mensajes();
	}

function uf_guardar_procedencia($ar_datos,$aa_seguridad)
{  	   
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Function:  uf_guardar_procedencia
//	Access:  public
//	Arguments: $ar_datos,$aa_seguridad
//   ar_datos=  Arreglo Cargado con la información proveniente de la Interfaz de Procedencias
//	Returns:	$lb_valido= Variable que devuelve true si la operación 
//                          fue exitosa de lo contrario devuelve false 
//	Description:Este método se encarga de realizar la inserción del registro si este existe con los 
//              datos,de lo contrario realiza una actualización con los datos cargados en el arreglo 
//              $ar_datos                  
/////////////////////////////////////////////////////////////////////////////////////////////////////////

  $ls_codigo       =$ar_datos["codigo"];
  $ls_codigosistema=$ar_datos["codsis"];
  $ls_operacionsis =$ar_datos["operacionsis"];
  $ls_descripcion  =$ar_datos["descripcion"];
  if ($this->uf_select_procedencia($ls_codigo))
	 {
		$ls_sql=" UPDATE sigesp_procedencias ".
				" SET codsis='".$ls_codigosistema."',opeproc='".$ls_operacionsis."', ".
				" desproc='".$ls_descripcion."' ". 
				" WHERE procede='".$ls_codigo."'";
		$this->io_sql->begin_transaction();             
		$rs_data=$this->io_sql->execute($ls_sql);
		if ($rs_data==false)
		   {
			 $this->io_sql->rollback();
	         $this->io_msg->message("CLASE->SIGESP_CFG_C_PROCEDENCIAS; METODO->uf_delete_procedencia;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
		 	 $lb_valido=false;
		   }
		else
		   {   
  		     /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			 $ls_evento="UPDATE";
			 $ls_descripcion ="Actualizó en CFG Nueva Procedencia ".$ls_codigo;
			 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			 $aa_seguridad["ventanas"],$ls_descripcion);
			 /////////////////////////////////         SEGURIDAD               ///////////////////////////
			 $this->io_sql->commit();
			 $lb_valido=true;
			 $this->io_msg->message("Registro Actualizado !!!");
		   }	  	
		return $lb_valido;
	 }
  else
	 {
	  $ls_sql=" INSERT INTO sigesp_procedencias".
			  " (procede,codsis,opeproc,desproc) ".
			  " VALUES "." 
			   ('".$ls_codigo."','".$ls_codigosistema."','".$ls_operacionsis."','".$ls_descripcion."')";
		$this->io_sql->begin_transaction();
		$rs_data=$this->io_sql->execute($ls_sql);
		if ($rs_data==false)
		   {
			 $this->io_sql->rollback();
	         $this->io_msg->message("CLASE->SIGESP_CFG_C_PROCEDENCIAS; METODO->uf_delete_procedencia;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
		     $lb_valido=false;
		   }
		else
		   {   
			 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			 $ls_evento="INSERT";
			 $ls_descripcion ="Insertó en CFG Nueva Procedencia ".$ls_codigo;
			 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			 $aa_seguridad["ventanas"],$ls_descripcion);
			 /////////////////////////////////         SEGURIDAD               ///////////////////////////
			 $this->io_sql->commit();
			 $lb_valido=true;
			 $this->io_msg->message("Registro Incluido !!!");
		   }	  	
		return $lb_valido;	
	  }
 $this->io_sql->close();
 }
	

function uf_select_procedencia($as_codigo)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	   Function:  uf_select_procedencia
//	     Access:  public
//    Arguments:
//   $as_codigo=  Valor a buscar dentro de la tabla de procedencias.
//	    Returns:  $lb_valido= Variable que devuelve true si encontro el registro 
//                de lo contrario devuelve false. 
//	Description:  Este método que se ancarga de buscar el Código de Procedencia enviado por parametro.
/////////////////////////////////////////////////////////////////////////////////////////////////////////

	$ls_sql  = "SELECT * FROM sigesp_procedencias WHERE procede='".$as_codigo."'";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido=false;
	     $this->io_msg->message("CLASE->SIGESP_CFG_C_PROCEDENCIAS; METODO->uf_select_procedencia;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
	   }
	else
	   {
	     $li_numrows=$this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
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

function uf_delete_procedencia($as_codigo,$aa_seguridad)
{   
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Function:  uf_delete_procedencia
//	Access:  public
//	Arguments:
// $as_codigo=  Valor a buscar dentro de la tabla de procedencias.
//	  Returns:	$lb_valido= Variable que devuelve true si encontro el registro 
//                          de lo contrario devuelve false. 
//	Description: Este método que se ancarga de buscar el Código de Procedencia enviado por parametro.
/////////////////////////////////////////////////////////////////////////////////////////////////////////

	$lb_valido = false;
	$ls_sql    = " DELETE FROM sigesp_procedencias WHERE procede='".$as_codigo."'";
    $this->io_sql->begin_transaction();
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   { 
	          $this->io_msg_error="CLASE->SIGESP_CFG_C_PROCEDENCIAS; METODO->uf_delete_procedencia;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);   
	   }
    else
	   {
	     /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	     $ls_evento="DELETE";
	     $ls_descripcion ="Eliminó en CFG la Procedencia ".$as_codigo;
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