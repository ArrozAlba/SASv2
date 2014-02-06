<?php
class sigesp_soc_c_tiposer
{
var $ls_sql;
var $is_msg_error;
	
	function sigesp_soc_c_tiposer($conn)
	{
	  require_once("../../shared/class_folder/class_mensajes.php");
	  require_once("../../shared/class_folder/sigesp_c_seguridad.php");	  
	  require_once("../../shared/class_folder/class_funciones.php");
	  $this->seguridad  = new sigesp_c_seguridad();		 
	  $this->io_funcion = new class_funciones();
	  $this->io_sql     = new class_sql($conn);
	  $this->io_msg     = new class_mensajes();		
	}

function uf_insert_tiposervicio($as_codtip,$as_dentip,$aa_seguridad,$as_codmil) 
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	        Function:  uf_insert_tiposervicio
	//	          Access:  public
	//	       Arguments:
	//         as_codtip:  Código del Tipo de SEP.
	//         as_dentip:  Denominación del Tipo de SEP.
	//      aa_seguridad:  Arreglo cargado con la información de usuario, ventanas, sistema etc. 
	//         as_codmil   codigo del catalogo milco
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga de insertar un nuevo tipo de servicio en la tabla soc_tiposervicio. 
	//     Elaborado Por:  Ing. Néstor Falcón.
	// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
	///////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	$ls_sql=" INSERT INTO soc_tiposervicio (codtipser, dentipser, codmil) VALUES ('".$as_codtip."','".$as_dentip."','".$as_codmil."')";
	$this->io_sql->begin_transaction();
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
         $lb_valido=false;
		 $this->io_msg->message("CLASE->SIGESP_SOC_C_TIPOSER; METODO->uf_insert_tiposervicio; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	 {
	   $lb_valido=true;
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="INSERT";
	   $ls_descripcion ="Insertó en SOC el Tipo de Servicio ".$as_dentip." con código ".$as_codtip;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////
	 }
     return $lb_valido;
}

function uf_update_tiposervicio($as_codtip,$as_dentip,$aa_seguridad,$as_codmil) 
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	        Function: uf_update_tiposervicio
	//	          Access:  public
	//	       Arguments:
	//         as_codtip:  Código del Tipo de SEP.
	//         as_dentip:  Denominación del Tipo de SEP.
	//      aa_seguridad:  Arreglo cargado con la información de usuario, ventanas, sistema etc.
	//         as_codmil   codigo del catalogo milco
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga de actualizar un tipo de servicio en la tabla soc_tiposervicio. 
	//     Elaborado Por:  Ing. Néstor Falcón.
	// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////  
	  $ls_sql=" UPDATE soc_tiposervicio SET dentipser='".$as_dentip."' WHERE codtipser='" .$as_codtip. "'";
	  $this->io_sql->begin_transaction();
	  $rs_data=$this->io_sql->execute($ls_sql);
	  if ($rs_data===false)
		 {
		   $lb_valido=false;
		   $this->io_msg->message("CLASE->SIGESP_SOC_C_TIPOSER; METODO->uf_update_tiposervicio; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }
	  else
		 {
		   $lb_valido=true;
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		   $ls_evento="UPDATE";
		   $ls_descripcion ="Actualizó en SOC Tipo de Servicio ".$as_codtip;
		   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		   $aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////
		 }
      return $lb_valido;
} 

function uf_delete_tiposervicio($as_codemp,$as_codtip,$as_dentip,$aa_seguridad)
{          		 
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Método:  uf_delete_tiposervicio
	//	          Access:  public
	//	        Arguments
	//        $as_codtip:  Código del Tipo de Servicio.
	//        $as_dentip:  Denominación del Tipo de Servicio.  
	//	   $aa_seguridad:  Arreglo cargado con la información de usuario, ventanas, sistema etc.
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga de eliminar un tipo de servicio en la tabla soc_tiposervicio. 
	//     Elaborado Por:  Ing. Néstor Falcón.
	// Fecha de Creación:  20/02/2006       Fecha Última Actualización:27/03/2006.	 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
   $lb_valido=false;
   $ls_sql = " DELETE FROM soc_tiposervicio WHERE codtipser='".$as_codtip."'";	    
   $this->io_sql->begin_transaction();
   $rs_data=$this->io_sql->execute($ls_sql);
   if ($rs_data===false)
      {
	    $lb_valido=false;
	    $this->io_msg->message("CLASE->SIGESP_SOC_C_TIPOSER; METODO->uf_delete_tiposervicio; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	  }
   else
      {
	    $lb_valido=true;
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="DELETE";
		$ls_descripcion ="Eliminó en SOC Tipo de Servicio ".$as_codtip." con denominación ".$as_dentip;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
	  }	    		 
   return $lb_valido;
}

function uf_select_tiposervicio($as_codtip) 
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	        Function:  uf_select_tiposervicio
//	          Access:  public
//	       Arguments:
//         as_codtip:  Código del tipo de servicio.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si existe o no un tipo de servicio, la funcion devuelve true si el
//                     registro es encontrado caso contrario devuelve false.
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  $lb_valido = false;
  $ls_sql    = "SELECT * FROM soc_tiposervicio WHERE codtipser='".$as_codtip."'";
  $rs_data   = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->SIGESP_SOC_C_TIPOSER; METODO->uf_select_tiposervicio; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   $li_numrows=$this->io_sql->num_rows($rs_data);
	   if ($li_numrows>0)
	      {
		    $lb_valido=true;
	      }
	 }
  return $lb_valido;
}
}// Fin de la Clase sigesp_sep_c_tipo.
?> 