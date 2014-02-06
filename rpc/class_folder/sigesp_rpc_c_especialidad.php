<?php
class sigesp_rpc_c_especialidad
{
var $ls_sql;
var $is_msg_error;
	
	function sigesp_rpc_c_especialidad($conn)
	{
	  require_once("../shared/class_folder/sigesp_c_seguridad.php");
	  $this->seguridad = new sigesp_c_seguridad();
	  require_once("../shared/class_folder/class_funciones.php");
	  $this->io_funcion = new class_funciones();
	  require_once("../shared/class_folder/class_mensajes.php");
	  $this->io_sql= new class_sql($conn);		
	  $this->io_msg= new class_mensajes();
	}


function uf_insert_especialidad($as_codesp,$as_denesp,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_insert_especialidad
//	          Access:  public
//	        Arguments   
//        $as_codesp:  Código de la Especialidad a insertar.
//        $as_denesp:  Denominación de la Especialidad a insertar.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al nombre de la ventana,nombre del usuario etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de Insertar una nueva especialidad en la tabla rpc_especialidad. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  06/09/2005       Fecha Última Actualización:22/03/2006.	 
//////////////////////////////////////////////////////////////////////////////
	$ls_sql = " INSERT INTO rpc_especialidad (codesp,denesp) VALUES ('".$as_codesp."','".$as_denesp."')";
	$this->io_sql->begin_transaction();
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {               
		 $lb_valido=false;
         $this->is_msg->message("CLASE->SIGESP_RPC_C_ESPECIALIDAD; METODO->uf_insert_especialidad; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
       }
	else
	   {
		 $lb_valido=true;
         /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		 $ls_evento="INSERT";
		 $ls_descripcion ="Insertó Nueva Especialidad en RPC ".$as_codesp;
		 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		 $aa_seguridad["ventanas"],$ls_descripcion);
		 /////////////////////////////////         SEGURIDAD               ///////////////////////////	   
    }
return $lb_valido;
}

function uf_update_especialidad($as_codesp,$as_denesp,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_update_especialidad
// 	          Access:  public
//	        Arguments  
//        $as_codesp:  Código de la Especialidad.
//        $as_denesp:  Denominación de la Especialidad.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al nombre de la ventana,nombre del usuario etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de actualizar los datos de una especialidad en la tabla rpc_especialidad. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  06/09/2005       Fecha Última Actualización:22/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
	$ls_sql=" UPDATE rpc_especialidad SET denesp='".$as_denesp."' WHERE codesp='" .$as_codesp. "'";
	$this->io_sql->begin_transaction();
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
		 $lb_valido=false;
         $this->is_msg->message("CLASE->SIGESP_RPC_C_ESPECIALIDAD; METODO->uf_update_especialidad; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
		 $lb_valido=true;
	     /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		 $ls_evento="UPDATE";
		 $ls_descripcion ="Actualizó en RPC Especialidad  ".$as_codesp;
		 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		 $aa_seguridad["ventanas"],$ls_descripcion);
		 /////////////////////////////////         SEGURIDAD               ///////////////////////////
       }
return $lb_valido;
} 

function uf_delete_especialidad($as_codemp,$as_codesp,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo: uf_delete_especialidad
//	          Access:  public
//	       Arguments:  
//        $as_codesp:  Código de la Especialidad.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al nombre de la ventana,nombre del usuario etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de eliminar una especialidad en la tabla rpc_especialidad. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  06/09/2005       Fecha Última Actualización:22/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  $lb_valido=false;
  $lb_relacion=$this->uf_check_relaciones($as_codemp,$as_codesp);
  if (!$lb_relacion)
	 { 		  
       $ls_sql=" DELETE FROM rpc_especialidad WHERE codesp='".$as_codesp."'";	    
       $this->io_sql->begin_transaction();
	   $rs_data=$this->io_sql->execute($ls_sql);
	   if ($rs_data===false)
		  {
		    $lb_valido=false;
            $this->is_msg->message("CLASE->SIGESP_RPC_C_ESPECIALIDAD; METODO->uf_delete_especialidad; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  }	
       else
	      {
		    $lb_valido=true;
		    /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		    $ls_evento="DELETE";
		    $ls_descripcion ="Eliminó en RPC Especialidad  ".$as_codesp;
		    $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		    $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		    $aa_seguridad["ventanas"],$ls_descripcion);
		    /////////////////////////////////         SEGURIDAD               /////////////////////////// 
          } 		 
    }
return $lb_valido;
}


function uf_select_especialidad($as_codesp) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_existeespecialidad
//	          Access:  public
//	        Arguments   
//        $as_codesp:  Código de la Especialidad.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si existe o no una especialidad, la funcion devuelve true si el
//                     registro es encontrado caso contrario devuelve false. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:22/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
	$lb_valido=false;
	$ls_sql=" SELECT * FROM rpc_especialidad WHERE codesp='".$as_codesp."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
          $this->is_msg->message("CLASE->SIGESP_RPC_C_ESPECIALIDAD; METODO->uf_select_especialidad; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  $lb_valido=false;
	   }
	else
	   {
	 	 $li_numrows=$this->io_sql->num_rows($rs_data);
		 if($li_numrows>0)
		   {
		     $lb_valido=true;
		   }
	     $this->io_sql->free_result($rs_data);
	   }
	return $lb_valido;
}

function uf_check_relaciones($as_codesp)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_check_relaciones
//	          Access:  public
// 	        Arguments   
//        $as_codesp:  Código de la Especialidad.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si existen tablas relacionadas al Código de la Especialidad. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:22/03/2006.	 
//////////////////////////////////////////////////////////////////////////////

	$ls_sql="SELECT * FROM rpc_proveedor WHERE codesp='".$as_codesp."'";
	$rs=$this->io_sql->select($ls_sql);
	if($rs===false)
	  {
		$lb_valido=false;
 	    $this->io_msg->message("CLASE->SIGESP_RPC_C_ESPECIALIDAD; METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	  }
	else
	  {
		if($row=$this->io_sql->fetch_row($rs))
		  {
			$lb_valido=true;
			$this->is_msg_error="La Especialidad no puede ser eliminada, posee registros asociados a otras tablas !!!";
		  }
		else
		  {
			$lb_valido=false;
			$this->is_msg_error="Registro no encontrado !!!";
	 	  }
	}
	return $lb_valido;	
}
}//Fin de la Clase...
?>