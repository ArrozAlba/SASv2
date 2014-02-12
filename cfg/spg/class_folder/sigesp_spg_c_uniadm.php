<?php
class sigesp_spg_c_uniadm
{
var $ls_sql;
var $is_msg_error;
	
function sigesp_spg_c_uniadm($conn)
{
  require_once("../../shared/class_folder/sigesp_c_seguridad.php");	      
  require_once("../../shared/class_folder/class_funciones.php");		  
  require_once("../../shared/class_folder/class_mensajes.php");
  $this->io_funcion = new class_funciones();
  $this->seguridad = new sigesp_c_seguridad();		  
  $this->io_sql= new class_sql($conn);
  $this->io_msg= new class_mensajes();		
}
 
function uf_insert_unidad_administradora($as_codemp,$as_coduac,$as_denuac,$as_resuac,$as_tipuac,$aa_seguridad) 
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_insert_unidad_administradora
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
// Fecha de Creación:  31/07/2006       Fecha Última Actualización:31/07/2006.	 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
  $ls_sql = " INSERT INTO spg_ministerio_ua (codemp, coduac, denuac, resuac, tipuac) ".
            "      VALUES ('".$as_codemp."','".$as_coduac."','".$as_denuac."','".$as_resuac."','".$as_tipuac."')";
  $rs_data = $this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->SIGESP_SPG_C_UNIADM; METODO->uf_insert_unidad_administradora; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 } 
  else
	 {
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="INSERT";
	   $ls_descripcion ="Insertó en SPG la Unidad Administradora ".$as_denuac ." con código ".$as_coduac;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               ////////////////////////////
       $lb_valido=true;
	 }
return $lb_valido;
}


function uf_update_unidad_administradora($as_codemp,$as_coduac,$as_denuac,$as_resuac,$as_tipuac,$aa_seguridad) 
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_update_unidad_administradora
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
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:28/03/2006.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  $ls_sql=" UPDATE spg_ministerio_ua SET denuac='".$as_denuac."',resuac='".$as_resuac."',tipuac='".$as_tipuac."' ".
          "  WHERE codemp='".$as_codemp."' AND coduac='".$as_coduac."'";
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
       $lb_valido=false;
	   $this->io_msg->message("CLASE->SIGESP_SPG_C_UNIADM; METODO->uf_update_unidad_administradora; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento      = "UPDATE";
	   $ls_descripcion = "Actualizó en SPG la Unidad Administradora con código ".$as_coduac;
	   $ls_variable    = $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               ////////////////////////////
	   $lb_valido=true;
	 }
return $lb_valido;
} 

function uf_delete_unidad_administradora($as_codemp,$as_coduac,$as_denuac,$aa_seguridad)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_delete_unidad_administradora
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
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
  $lb_valido   = false;
  $lb_relacion = $this->uf_check_relaciones($as_codemp,$as_coduac);
  if (!$lb_relacion)
	 {
       $ls_sql = " DELETE FROM spg_ministerio_ua WHERE codemp='".$as_codemp."' AND coduac='".$as_coduac."'";	    
	   $this->io_sql->begin_transaction();
	   $rs_data = $this->io_sql->execute($ls_sql);
	   if ($rs_data===false)
		  {
		    $lb_valido=false;
		    $this->io_msg->message("CLASE->SIGESP_SPG_C_UNIADM; METODO->uf_delete_unidad_administradora; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  }
	   else
		  {
		    /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		    $ls_evento="DELETE";
		    $ls_descripcion ="Eliminó en SPG la Unidad Administradora ".$as_coduac." con denominacíon ".$as_denuac;
		    $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		    $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		    $aa_seguridad["ventanas"],$ls_descripcion);
		    /////////////////////////////////         SEGURIDAD               ////////////////////////////
		    $lb_valido=true;
		 } 		 
     }
return $lb_valido;	 
}

function uf_select_unidad_administradora($as_codemp,$as_coduac) 
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_select_clasificador
//	          Access:  public
//	        Arguments  
//       $as_codclas:  Código del Clasificador.
//	     Description:  Función que se encarga verificar si existe el código
//                     del clasificador que viene como parametro.En caso de encontrarlo devuelve true, caso contrario devuelve false.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  31/07/2006       Fecha Última Actualización:31/07/2006.	 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
  $ls_sql  = "SELECT * FROM spg_ministerio_ua WHERE codemp='".$as_codemp."' AND coduac='".$as_coduac."'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido = false;
  	   $this->io_msg->message("CLASE->SIGESP_CXP_C_UNIADM; METODO->uf_select_unidad_administradora; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
		 $li_numrows=$this->io_sql->num_rows($rs_data);
		 if($li_numrows>0)
		   {
			 $lb_valido = true;
 		     $this->io_sql->free_result($rs_data);
		   }
		 else
		   {
			 $lb_valido=false;
		   }
	 }
return $lb_valido;
}

function uf_check_relaciones($as_codemp,$as_coduac)
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

	$ls_sql  = "SELECT * FROM spg_unidadadministrativa WHERE codemp='".$as_codemp."' AND coduniadmsig='".$as_coduac."'";
	$rs_data = $this->io_sql->select($ls_sql);
	if($rs_data===false)
	  {
		$lb_valido=false;
	    $this->io_msg->message("CLASE->SIGESP_SOC_C_CLAUSULAS; METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	  }
	else
	  {
		if($row=$this->io_sql->fetch_row($rs_data))
		  {
			$lb_valido          = true;
			$this->is_msg_error = "La Unidad Administradora no puede ser eliminada, posee registros asociados a otras tablas !!!";
		  }
		else
		  {
			$lb_valido=false;
			$this->is_msg_error="Registro no encontrado !!!";
	 	  }
	}
	return $lb_valido;	
}
}//Fin de la Clase.
?> 