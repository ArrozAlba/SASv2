<?php
class sigesp_rpc_c_documento
{
var $ls_sql;
var $is_msg_error;
	
		function sigesp_rpc_c_documento($conn)
		{
          require_once("../shared/class_folder/sigesp_c_seguridad.php");
          require_once("../shared/class_folder/class_mensajes.php");
		  require_once("../shared/class_folder/class_funciones.php");
	      $this->seguridad = new sigesp_c_seguridad();		           
		  $this->io_funcion = new class_funciones();
		  $this->io_sql= new class_sql($conn);		
		  $this->io_msg= new class_mensajes();
		}
 
function uf_insert_documento($as_codemp,$as_coddoc,$as_dendoc,$as_tipdoc,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_insert_documento
// 	        Arguments   
//        $as_codemp:  Código de la empresa.
//        $as_coddoc:  Código del Documento.
//        $as_dendoc:  Denominación del Documento.
//     $aa_seguridad:  Arreglo cargado con la información acerca de la ventana,usuario,etc.
//	          Access:  public
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de insertar un documento en la tabla rpc_documentos. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:22/03/2006.	 
//////////////////////////////////////////////////////////////////////////////
	
	/*$ls_sql = " INSERT INTO rpc_documentos (codemp,coddoc,denDoc) VALUES ('$as_codemp','".$as_coddoc."','".$as_dendoc."')";*/
	$ls_sql = " INSERT INTO rpc_documentos (codemp,coddoc,denDoc,tipdoc) VALUES ('$as_codemp','".$as_coddoc."','".$as_dendoc."','".$as_tipdoc."')";
	$this->io_sql->begin_transaction();
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
		   $lb_valido=false;
		   $this->is_msg->message("CLASE->SIGESP_RPC_C_DOCUMENTO; METODO->uf_insert_documento; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
		 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		 $ls_evento="INSERT";
		 $ls_descripcion ="Insertó en RPC Nuevo Recaudo ".$as_coddoc;
		 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		 $aa_seguridad["ventanas"],$ls_descripcion);
		 /////////////////////////////////         SEGURIDAD               /////////////////////////// 		   
	     $lb_valido=true;
	   }
return $lb_valido;
}


function uf_update_documento($as_codemp,$as_coddoc,$as_dendoc,$as_tipdoc,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_update_documento
//	          Access:  public
// 	        Arguments   
//        $as_codemp:  Código de la empresa.
//        $as_coddoc:  Código del Documento.
//        $as_dendoc:  Denominación del Documento.
//     $aa_seguridad:  Arreglo cargado con la información acerca de la ventana,usuario,etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de actualizar un documento en la tabla rpc_documentos. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:22/03/2006.	 
//////////////////////////////////////////////////////////////////////////////

	/*$ls_sql=" UPDATE rpc_documentos ".
			" SET  dendoc='".$as_dendoc."' ".
			" WHERE codemp='" .$as_codemp. "' AND  coddoc = '" .$as_coddoc. "'";*/
		$ls_sql=" UPDATE rpc_documentos ".
			" SET  dendoc='".$as_dendoc."',tipdoc='".$as_tipdoc."'  ".
			" WHERE codemp='" .$as_codemp. "' AND  coddoc = '" .$as_coddoc. "'";	
			
	$this->io_sql->begin_transaction();
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
		 $lb_valido=false;
		 $this->is_msg->message("CLASE->SIGESP_RPC_C_DOCUMENTO; METODO->uf_update_documento; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
		 $lb_valido=true;
		 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		 $ls_evento="UPDATE";
		 $ls_descripcion ="Actualizó en RPC Recaudo ".$as_coddoc;
		 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		 $aa_seguridad["ventanas"],$ls_descripcion);
		 /////////////////////////////////         SEGURIDAD               /////////////////////////// 		         		   
	}
return $lb_valido;
} 
		
		
function uf_delete_documento($as_codemp,$as_coddoc,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_delete_documento
//	          Access:  public
// 	        Arguments  
//        $as_codemp:  Código de la Empresa.
//        $as_coddoc:  Código del Documento.
//     $aa_seguridad:  Arreglo cargado con la información acerca de la ventana,usuario,etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de eliminar un documento en la tabla rpc_documentos. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:22/03/2006.	 
//////////////////////////////////////////////////////////////////////////////

  $lb_valido=false;
  $lb_relacion=$this->uf_check_relaciones($as_codemp,$as_coddoc);
  if(!$lb_relacion)
	{ 		  
      $ls_sql=" DELETE FROM rpc_documentos WHERE  codemp='".$as_codemp."' AND coddoc='".$as_coddoc."'";	    
      $this->io_sql->begin_transaction();
      $rs_data=$this->io_sql->execute($ls_sql);
      if ($rs_data===false)
	     {
	       $lb_valido=false;
		   $this->is_msg->message("CLASE->SIGESP_RPC_C_DOCUMENTO; METODO->uf_delete_documento; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	     }
      else
	     {
	       $lb_valido=true;
	       /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		   $ls_evento="DELETE";
		   $ls_descripcion ="Eliminó en RPC Recaudo ".$as_coddoc;
		   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		   $aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               ///////////////////////// 		     
         }
	}	  		 
return $lb_valido;
}
	

function uf_select_documento($as_codemp,$as_coddoc) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_select_documento
//	          Access:  public
// 	       Arguments   
//        $as_codemp:  Código de la Empresa.
//        $as_coddoc:  Código del documento.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si existe o no un documento, la funcion devuelve true si el
//                     registro es encontrado caso contrario devuelve false. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:22/03/2006.	 
//////////////////////////////////////////////////////////////////////////////

$lb_valido=false;
$ls_sql=" SELECT * FROM rpc_documentos WHERE codemp='".$as_codemp."'AND coddoc='".$as_coddoc."'";
$rs_data=$this->io_sql->select($ls_sql);
if ($rs_data===false)
   {
     $this->is_msg->message("CLASE->SIGESP_RPC_C_DOCUMENTO; METODO->uf_select_documento; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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


function uf_check_relaciones($as_codemp,$as_coddoc)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo: uf_check_relaciones
// 	        Arguments   
//        $as_codemp:  Código de la Empresa.
//        $as_coddoc:  Código del Documento.
//	          Access:  public
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si existen tablas relacionadas al Código de Documento. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:22/03/2006.	 
//////////////////////////////////////////////////////////////////////////////

	$ls_sql="SELECT * FROM rpc_docxprov WHERE codemp='".$as_codemp."' AND coddoc='".$as_coddoc."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	  {
		$lb_valido=false;
        $this->is_msg->message("CLASE->SIGESP_RPC_C_DOCUMENTO; METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	  }
	else
	  {
		if($row=$this->io_sql->fetch_row($rs_data))
		  {
			$lb_valido=true;
			$this->is_msg_error="El Documento no puede ser eliminado, posee registros asociados a otras tablas !!!";
		  }
		else
		  {
			$lb_valido=false;
			$this->is_msg_error="Registro no encontrado !!!";
	 	  }
	}
	return $lb_valido;	
}

function uf_load_documentos($as_codemp,&$aa_data) 
{
	//////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_load_documentos
	//	          Access:  public
	// 	       Arguments   as_codemp:  Código de la Empresa.
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga de cargar todos los tipos de documentos
	//     Elaborado Por:  Ing. Yesenia Moreno
	// Fecha de Creación:  10/04/2007       				Fecha Última Actualización:
	//////////////////////////////////////////////////////////////////////////////
	require_once("../shared/class_folder/class_datastore.php");
	$io_ds=new class_datastore();
	
	$lb_valido=true;
	$ls_sql="SELECT codemp, coddoc, dendoc ".
			"  FROM rpc_documentos ".
			" WHERE codemp='".$as_codemp."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if ($rs_data===false)
	{
		$this->is_msg->message("CLASE->SIGESP_RPC_C_DOCUMENTO; METODO->uf_load_documentos; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
	}
	else
	{
		$li_numrows=$this->io_sql->num_rows($rs_data);
		if($li_numrows>0)
		{
			$io_ds->data=$this->io_sql->obtener_datos($rs_data);		
		}
	    $this->io_sql->free_result($rs_data);
	}
	$aa_data=$io_ds;
	unset($io_ds);
	return $lb_valido;
}


}//Fin de la Clase...
?>