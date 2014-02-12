<?php
class sigesp_rpc_c_tipoorg
{
var $ls_sql;
var $is_msg_error;
	
	function sigesp_rpc_c_tipoorg($conn)
	{
	  require_once("../shared/class_folder/sigesp_c_seguridad.php");
      require_once("../shared/class_folder/class_funciones.php");
      require_once("../shared/class_folder/class_mensajes.php");
	  $this->seguridad = new sigesp_c_seguridad();	
	  $this->io_funcion = new class_funciones();   	 
	  $this->io_sql= new class_sql($conn);		
	  $this->io_msg= new class_mensajes(); 
	}

function uf_insert_tipo_empresa($as_codtipoorg,$as_dentipoorg,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_insert_tipo_empresa
//	          Access:  public
//	        Arguments 
//    $as_codtipoorg:  Código del Tipo de la Empresa.
//    $as_dentipoorg:  Denominación del Tipo de la Empresa.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al nombre de la ventana,nombre del usuario etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de insertar un nuevo Tipo de Empresa en la tabla rpc_tipo_organizacion. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:22/03/2006.	 
//////////////////////////////////////////////////////////////////////////////  

  $ls_sql = " INSERT INTO rpc_tipo_organizacion (codtipoorg,dentipoorg) VALUES ('".$as_codtipoorg."','".$as_dentipoorg."')";
  $this->io_sql->begin_transaction(); 
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido=false;
 	   $this->io_msg->message("CLASE->SIGESP_RPC_C_TIPOORG; METODO->uf_insert_tipo_empresa; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   $lb_valido=true;
       /////////////////////////////////         SEGURIDAD               /////////////////////////////		
       $ls_evento="INSERT";
	   $ls_descripcion ="Insertó Nueva Tipo de Organización en RPC ".$as_codtipoorg;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////// 	 
     }		
return $lb_valido;
}

function uf_update_tipo_empresa($as_codtipoorg,$as_dentipoorg,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_update_tipo_empresa
//            Access:  public
//	        Arguments  
//    $as_codtipoorg:  Código del Tipo de la Empresa.
//    $as_dentipoorg:  Denominación del Tipo de la Empresa.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al nombre de la ventana,nombre del usuario etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de actualizar los datos de un Tipo de Empresa en la tabla rpc_tipo_organizacion. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:22/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  $ls_sql=" UPDATE rpc_tipo_organizacion ".
		  " SET  dentipoorg='".$as_dentipoorg."' ".
		  " WHERE codtipoorg='" .$as_codtipoorg. "'";
  $this->io_sql->begin_transaction(); 
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
 	   $this->io_msg->message("CLASE->SIGESP_RPC_C_TIPOORG; METODO->uf_update_tipo_empresa; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   $lb_valido=true;
       /////////////////////////////////         SEGURIDAD               /////////////////////////////		
  	   $ls_evento="UPDATE";
	   $ls_descripcion ="Actualizó Tipo de Organización en RPC ".$as_codtipoorg;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               ///////////////////////////   
     }		
return $lb_valido;
} 

function uf_delete_tipo_empresa($as_codemp,$as_codtipoorg,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_delete_tipo_empresa
//	          Access:  public
//	        Arguments
//        $as_codemp:  Código de la Empresa.  
//    $as_codtipoorg:  Código del Tipo de la Empresa.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al nombre de la ventana,nombre del usuario etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de eliminar un Tipo de Empresa en la tabla rpc_tipo_organizacion.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:22/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  $lb_valido=false;
  $lb_relacion=$this->uf_check_relaciones($as_codemp,$as_codtipoorg);
  if (!$lb_relacion)
	 {
       $ls_sql= " DELETE FROM rpc_tipo_organizacion WHERE codtipoorg='".$as_codtipoorg."'";	    
	   $this->io_sql->begin_transaction();      
	   $rs_data=$this->io_sql->execute($ls_sql);
	   if ($rs_data===false)
		  {
		    $lb_valido=false;
		    $this->io_msg->message("CLASE->SIGESP_RPC_C_TIPOORG; METODO->uf_delete_tipo_empresa; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  }
	   else
		  {
		    $lb_valido=true;
		    /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		    $ls_evento="DELETE";
		    $ls_descripcion ="Eliminó Tipo de Organización en RPC ".$as_codtipoorg;
		    $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		    $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		    $aa_seguridad["ventanas"],$ls_descripcion);
		    /////////////////////////////////         SEGURIDAD               ///////////////////////////
		  }
	 }	   		 		
return $lb_valido;
}

function uf_select_tipo_empresa($as_codtipoorg) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_select_tipo_empresa
//	          Access:  public
//	        Arguments   
//    $as_codtipoorg:  Código del Tipo de la Empresa.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si existe o no un Tipo de Empresa, la funcion devuelve true si el
//                     registro es encontrado caso contrario devuelve false. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:10/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
	$lb_valido=false;
	$ls_sql=" SELECT * FROM rpc_tipo_organizacion WHERE codtipoorg='".$as_codtipoorg."'";
	$rs_data=$this->io_sql->select($ls_sql);
    if ($rs_data===false)
	   {
	     $lb_valido=false;
 	   $this->io_msg->message("CLASE->SIGESP_RPC_C_TIPOORG; METODO->uf_select_tipo_empresa; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
		 $li_numrows=$this->io_sql->num_rows($rs_data);
		 if($li_numrows>0)
		 {
		   $lb_valido=true;
		 }
	   }
	return $lb_valido;
}

function uf_check_relaciones($as_codemp,$as_codtipoorg)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_check_relaciones
//	          Access:  public
// 	        Arguments 
//        $as_codemp:  Código de la Empresa.  
//    $as_codtipoorg:  Código del Tipo Empresa.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si existen tablas relacionadas al Código del Tipo Empresa. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:22/03/2006.	 
//////////////////////////////////////////////////////////////////////////////

	$ls_sql="SELECT * FROM rpc_proveedor WHERE codemp='".$as_codemp."' AND codtipoorg='".$as_codtipoorg."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	  {
		$lb_valido=false;
 	    $this->io_msg->message("CLASE->SIGESP_RPC_C_TIPOORG; METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	  }
	else
	  {
		if($row=$this->io_sql->fetch_row($rs_data))
		  {
			$lb_valido=true;
			$this->is_msg_error="El Tipo de Empresa no puede ser eliminada, posee registros asociados a otras tablas !!!";
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