<?php 
class sigesp_cxp_c_doc
{
var $ls_sql;
var $is_msg_error;
	
	function sigesp_cxp_c_doc($conn)
	{
	  require_once("../../shared/class_folder/sigesp_c_seguridad.php");	       
	  require_once("../../shared/class_folder/class_funciones.php");		  
	  require_once("../../shared/class_folder/class_mensajes.php");
	  $this->seguridad  = new sigesp_c_seguridad();		 
	  $this->io_funcion = new class_funciones();
	  $this->io_sql     = new class_sql($conn);		
	  $this->io_msg     = new class_mensajes();
	}

function uf_insert_documento($as_coddoc,$as_dendoc,$as_presu,$as_conta,$as_anticipo,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	Metodo: uf_insert_documento
//	Access:  public
//	Arguments: $as_coddoc,$as_dendoc,$as_presu,$as_conta,$aa_seguridad
//	Description: Función que se encargar de insertar un nuevo registro en la
//               tabla cxp_documento.
//////////////////////////////////////////////////////////////////////////////
	  if ($as_presu=="C") 
	     {
		   $li_presu=1;
	     }
	  if ($as_presu=="P")
	     {
		   $li_presu=2;
	     }
	  if ($as_presu=="N") 
	     {
		   $li_presu=3;
	     }
	  if ($as_presu=="S")
	  {
		$li_presu=4;
	  }
	//-----------------------------------------------------------------------------------------------------------------------------
	  if ($as_conta=="C")
	     {
		   $li_conta=1;
	     }
	  if ($as_conta=="S")
	     {
		   $li_conta=2;
	     }
	
	$ls_sql=" INSERT INTO cxp_documento (codtipdoc,dentipdoc,estcon,estpre,tipodocanti)".
			" VALUES ('".$as_coddoc."','".$as_dendoc."','".$li_conta."','".$li_presu."','".$as_anticipo."')";
	$this->io_sql->begin_transaction();
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido=false;
		 $this->io_msg->message("CLASE->SIGESP_CXP_C_DOC; METODO->uf_insert_documento; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
		 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		 $ls_evento="INSERT";
		 $ls_descripcion ="Insertó en CXP el Documento ".$as_dendoc." con código ".$as_coddoc;
		 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		 $aa_seguridad["ventanas"],$ls_descripcion);
		 /////////////////////////////////         SEGURIDAD               /////////////////////////// 
	     $lb_valido=true;
	   }
return $lb_valido;
}

function uf_update_documento($as_coddoc,$as_dendoc,$as_presu,$as_conta,$as_anticipo,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	Metodo: uf_update_documento
//	Access:  public
//	Arguments: $as_coddoc,$as_dendoc,$as_presu,$as_conta,$aa_seguridad
//	Description: Función que se encargar de actualizar registros en la
//               tabla cxp_documento.
//////////////////////////////////////////////////////////////////////////////
	  if ($as_presu=="C") 
	  {
		$li_presu=1;
	  }
	  if ($as_presu=="P")
	  {
		$li_presu=2;
	  }          
	  if ($as_presu=="N") 
	  {
		$li_presu=3;
	  }
	  if ($as_presu=="S")
	  {
		$li_presu=4;
	  }
	  if ($as_conta=="C")
	     {
		   $li_conta=1;
	     }          
	  if ($as_conta=="S")
	     {
		   $li_conta=2;
	     }
	  $ls_sql=" UPDATE cxp_documento ".
			  " SET dentipdoc='".$as_dendoc."',estcon='".$li_conta."', estpre='".$li_presu."', tipodocanti='".$as_anticipo."' ".
			  " WHERE codtipdoc='".$as_coddoc."'";
	  $this->io_sql->begin_transaction();
	  $rs_data=$this->io_sql->execute($ls_sql);
	  if ($rs_data===false)
		 {
	       $lb_valido=false;
		   $this->io_msg->message("CLASE->SIGESP_CXP_C_DOC; METODO->uf_update_documento; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }
	  else
		 {
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		   $ls_evento="UPDATE";
		   $ls_descripcion ="Actualizó en CXP el Documento con código ".$as_coddoc;
		   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		   $aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               /////////////////////////// 
           $lb_valido=true;
		 }
return $lb_valido;
} 

function uf_delete_documento($as_codemp,$as_coddoc,$as_dendoc,$aa_seguridad)
{        
//////////////////////////////////////////////////////////////////////////////
//	Metodo: uf_delete_documento
//	Access:  public
//	Arguments: $as_coddoc,$aa_seguridad
//	Description: Función que se encargar de eliminar registros en la
//               tabla cxp_documento.
//////////////////////////////////////////////////////////////////////////////
  $lb_valido=false;
  $ls_sql=" DELETE FROM cxp_documento WHERE codtipdoc='".$as_coddoc."'";	    
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->SIGESP_CXP_C_DOC; METODO->uf_delete_documento; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
     {
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="DELETE";
	   $ls_descripcion ="Eliminó en CXP el Documento ".$as_dendoc." con código ".$as_coddoc;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////// 
	   $lb_valido=true;
	 }	 
  return $lb_valido;	   		 
}

function uf_load_documento($as_coddoc) 
{
//////////////////////////////////////////////////////////////////////////////
//	Metodo: uf_select_documento
//	Access:  public
//	Arguments: $as_coddoc
//	Description: Función que se encargar de buscar un registro en la
//               tabla cxp_documento.
//////////////////////////////////////////////////////////////////////////////
	$ls_sql  = " SELECT * FROM cxp_documento WHERE codtipdoc='".$as_coddoc."'";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido=false;
		 $this->io_msg->message("CLASE->SIGESP_CXP_C_DOC; METODO->uf_select_documento; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
}//Fin de la Clase...
?> 