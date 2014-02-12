<?php
class sigesp_soc_c_clausulas
{

var $ls_sql;
var $is_msg_error;
	
	function sigesp_soc_c_clausulas($conn)
	{
	  require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	  require_once("../../shared/class_folder/class_funciones.php");
	  require_once("../../shared/class_folder/class_mensajes.php");
	  $this->seguridad  = new sigesp_c_seguridad();		  
	  $this->io_funcion = new class_funciones();
	  $this->io_sql     = new class_sql($conn);
	  $this->io_msg     = new class_mensajes();		
	}

function uf_insert_clausula($as_codemp,$as_codcla,$as_dencla,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_insert_clausula
//	          Access:  public
//	       Arguments: 
//        $as_codemp:  Código de la Empresa.
//        $as_codcla:  Código de la Clausula a Insertar.
//        $as_dencla:  Denominación de la Clusula que se va a Insertar.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de insertar en la tabla soc_clausulas
//                     un código y denominación para una nueva clausula.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
//////////////////////////////////////////////////////////////////////////////  
	  
	  $ls_sql = " INSERT INTO soc_clausulas (codemp, codcla, dencla) ".
				" VALUES ('".$as_codemp."','".$as_codcla."','".$as_dencla."')";
	  $this->io_sql->begin_transaction();
	  $rs_data=$this->io_sql->execute($ls_sql);
	  if ($rs_data===false)		     
		 {
		   $lb_valido=false;
		   $this->io_msg->message("CLASE->SIGESP_SOC_C_CLAUSULAS; METODO->uf_insert_tiposervicio; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }
	  else
		 {
		   $lb_valido=true;
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		   $ls_evento="INSERT";
		   $ls_descripcion ="Insertó en SOC la Clausula Número ".$as_codcla;
		   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		   $aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               /////////////////////////// 		     
         }
return $lb_valido;
}

function uf_update_clausula($as_codemp,$as_codcla,$as_dencla,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo: uf_update_clausula
//	          Access:  public
//	       Arguments: 
//        $as_codemp:  Código de la Empresa.
//        $as_codcla:  Código de la Clausula a actualizar.
//        $as_dencla:  Denominación de la Clausula que se va a actualizar.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de actualizar la denominacion
//                     de una clausula para la clausula que viene como parametro
//                     en la tabla soc_clausulas.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
 
  $ls_sql=" UPDATE soc_clausulas ".
		  " SET  dencla='".$as_dencla."' ".
		  " WHERE codemp='" .$as_codemp. "' AND codcla = '".$as_codcla."'";

  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->SIGESP_SOC_C_CLAUSULAS; METODO->uf_insert_tiposervicio; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   $lb_valido=true;
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="UPDATE";
	   $ls_descripcion ="Actualizó en SOC la clasula Número ".$as_codcla;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		     
     }  		      
return $lb_valido;
} 
		
function uf_delete_clausula($as_codemp,$as_codcla,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_delete_clausula
//	          Access:  public
//	       Arguments: 
//        $as_codemp:  Código de la Empresa.
//        $as_codcla:  Código de la Clausula a eliminar.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	     Description:  Función que se encarga de eliminar la clausula que 
//                     viene como parametro  en la tabla soc_clausulas.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
 
  $lb_valido = false;
  $ls_sql    = "DELETE FROM soc_clausulas WHERE  codemp='".$as_codemp."' AND codcla='".$as_codcla."'";	    
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
     {
       $lb_valido=false;
       $this->io_msg->message("CLASE->SIGESP_SOC_C_CLAUSULAS; METODO->uf_delete_clausula; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
     }
  else
     {
		$lb_valido=true;
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="DELETE";
		$ls_descripcion ="Eliminó en SOC la Clausula Número ".$as_codcla;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               ///////////////////////////// 		     
	}	   	 	 
  return $lb_valido;
}

function uf_select_clausula($as_codemp,$as_codcla) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo: uf_select_clausula
//	          Access:  public
//	       Arguments: 
//        $as_codemp:  Código de la Empresa.
//        $as_codcla:  Código de la Clausula a eliminar.
//	     Description:  Función que se encarga verificar si existe el código
//                     de la clausula que viene como parametro.En caso de encontrarla
//                     devuelve true, caso contrario devuelve false.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 

  $lb_valido = false;
  $ls_sql    = "SELECT * FROM soc_clausulas WHERE codemp='".$as_codemp."'AND codcla='".$as_codcla."'";
  $rs_data   = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->SIGESP_SOC_C_CLAUSULAS; METODO->uf_select_clausula; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   $li_numrows=$this->io_sql->num_rows($rs_data);
	   if ($li_numrows>0)
		  {
		    $lb_valido=true;
		    $this->io_sql->free_result($rs_data);
		  }
	 }
  return $lb_valido;
}
}//Fin de la Clase...
?> 