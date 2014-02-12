<?php
class sigesp_cxp_c_clasificaci
{
var $ls_sql;
var $is_msg_error;
	
	function sigesp_cxp_c_clasificaci($conn)
	{
	  require_once("../../shared/class_folder/sigesp_c_seguridad.php");	      
	  require_once("../../shared/class_folder/class_funciones.php");		  
	  require_once("../../shared/class_folder/class_mensajes.php");
	  $this->io_funcion = new class_funciones();
	  $this->seguridad  = new sigesp_c_seguridad();		  
	  $this->io_sql     = new class_sql($conn);
	  $this->io_msg     = new class_mensajes();		
	}
 
function uf_insert_clasificador($as_codclas,$as_denclas,$as_cuenta,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_insert_clasificador
//	          Access:  public
//	       Arguments: 
//       $as_codclas:  Código del Clasificador de la recepcion de documento.
//       $as_denclas:  Denominación del Clasificador.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de insertar en la tabla soc_clausulas
//                     un código y denominación para una nueva clausula.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:23/07/2008.	 
//////////////////////////////////////////////////////////////////////////////  
  $ls_sql  = " INSERT INTO cxp_clasificador_rd (codcla,dencla,sc_cuenta) VALUES ('".$as_codclas."','".$as_denclas."','".$as_cuenta."')";
  $rs_data = $this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->SIGESP_CXP_C_CLASIFICACI; METODO->uf_insert_clasificador; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 } 
  else
	 {
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="INSERT";
	   $ls_descripcion ="Insertó en CXP el Clasificador de RD ".$as_denclas ." con código ".$as_codclas;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               ////////////////////////////
       $lb_valido=true;
	 }
return $lb_valido;
}

function uf_update_clasificador($as_codclas,$as_denclas,$as_cuenta,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_update_clasificador
//	          Access:  public
//	        Arguments  
//       $as_codclas:  Código del Clasificador de la recepcion de documento.
//       $as_denclas:  Denominación del Clasificador.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de actualizar en la tabla cxp_clasificador_rd
//                     un código y denominación para un nuevo clasificador.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:23/07/2008.	 
//////////////////////////////////////////////////////////////////////////////  
  $ls_sql=" UPDATE cxp_clasificador_rd SET dencla='".$as_denclas."', sc_cuenta='".$as_cuenta."' WHERE codcla='" .$as_codclas. "'";
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
       $lb_valido=false;
	   $this->io_msg->message("CLASE->SIGESP_CXP_C_CLASIFICACI; METODO->uf_update_clasificador; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="UPDATE";
	   $ls_descripcion ="Actualizó en CXP el Clasificador de RD con código ".$as_codclas;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               ////////////////////////////
	   $lb_valido=true;
	 }
return $lb_valido;
} 

function uf_delete_clasificador($as_codemp,$as_codclas,$as_denclas,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_delete_clasificador
//	          Access:  public
//	        Arguments  
//       $as_codclas:  Código del Clasificador de la recepcion de documento.
//       $as_denclas:  Denominación del Clasificador.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de eliminar en la tabla cxp_clasificador_rd
//                     un Clasificador de la recepcion de documentos.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:28/03/2006.	 
//////////////////////////////////////////////////////////////////////////////  
  $lb_valido = false;
  $ls_sql    = "DELETE FROM cxp_clasificador_rd WHERE codcla='".$as_codclas."'";	    
  $this->io_sql->begin_transaction();
  $rs_data   = $this->io_sql->execute($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->SIGESP_CXP_C_CLASIFICACI; METODO->uf_delete_clasificador; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
     {
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="DELETE";
	   $ls_descripcion ="Eliminó en CXP el Clasificador de RD ".$as_codclas." con denominacíon ".$as_denclas;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               ////////////////////////////
	   $lb_valido=true;
	 } 		 
  return $lb_valido;	 
}

function uf_select_clasificador($as_codclas) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_select_clasificador
//	          Access:  public
//	        Arguments  
//       $as_codclas:  Código del Clasificador.
//	     Description:  Función que se encarga verificar si existe el código
//                     del clasificador que viene como parametro.En caso de encontrarlo devuelve true, caso contrario devuelve false.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  $ls_sql  = " SELECT * FROM cxp_clasificador_rd WHERE codcla='".$as_codclas."'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
  	   $this->io_msg->message("CLASE->SIGESP_CXP_C_CLASIFICACI; METODO->uf_select_clasificador; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
		 $li_numrows=$this->io_sql->num_rows($rs_data);
		 if($li_numrows>0)
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
}//Fin de la Clase.
?> 