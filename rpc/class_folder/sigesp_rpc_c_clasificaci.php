<?php
class sigesp_rpc_c_clasificaci
{
var $ls_sql;
var $is_msg_error;
	
		function sigesp_rpc_c_clasificaci($conn)
		{
		  require_once("../shared/class_folder/sigesp_c_seguridad.php");
	      $this->seguridad = new sigesp_c_seguridad();		  
          require_once("../shared/class_folder/class_funciones.php");
		  $this->io_funcion = new class_funciones();
		  require_once("../shared/class_folder/class_mensajes.php");
		  $this->io_sql= new class_sql($conn);
		  $this->io_msg= new class_mensajes();		
		}

function uf_insert_clasificacion($as_codemp,$as_codclas,$as_denclas,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_insert_clasificacion
//	          Access:  public
//	        Arguments   
//        $as_codemp:  Código de la Empresa.
//       $as_codclas:  Código de la Clasificación.
//       $as_denclas:  Denominación de la Clasificación.
//     $aa_seguridad:  Arreglo cargado con la información acerca de la ventana,usuario,etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de insertar una nueva clasificacion en la tabla rpc_clasificacion. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:22/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
	  $ls_sql=" INSERT INTO rpc_clasificacion (codemp,codclas,denclas) VALUES ('".$as_codemp."','".$as_codclas."','".$as_denclas."')";
	  $this->io_sql->begin_transaction();
	  $rs_data=$this->io_sql->execute($ls_sql);
	  if ($rs_data===false)		     
		 {
		   $lb_valido=false;
 	       $this->io_msg->message("CLASE->SIGESP_RPC_C_CLASIFICACI; METODO->uf_insert_clasificacion; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }
	  else
		 {
		   $lb_valido=true;
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		   $ls_evento="INSERT";
		   $ls_descripcion ="Insertó en RPC Nuevo Parámetro de Calificación ".$as_codclas;
		   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		   $aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               /////////////////////////// 		     
         }
return $lb_valido;
}

function uf_update_clasificacion($as_codemp,$as_codclas,$as_denclas,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_update_clasificacion
//	          Access:  public
//	        Arguments   
//        $as_codemp:  Código de la Empresa.
//       $as_codclas:  Código de la Clasificación.
//       $as_denclas:  Denominación de la Clasificación.
//     $aa_seguridad:  Arreglo cargado con la información acerca de la ventana,usuario,etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de actualizar los datos de una clasificacion en la tabla rpc_clasificacion. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:10/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  $ls_sql=" UPDATE rpc_clasificacion ".
		  " SET  denclas='".$as_denclas."' ".
		  " WHERE codemp='" .$as_codemp. "' AND  codclas = '" .$as_codclas. "'";
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
 	   $this->io_msg->message("CLASE->SIGESP_RPC_C_CLASIFICACI; METODO->uf_update_clasificacion; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   $lb_valido=true;
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="UPDATE";
	   $ls_descripcion ="Actualizó en RPC Parámetro de Calificación  ".$as_codclas;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		     
     }  		      
return $lb_valido;
} 
		
function uf_delete_clasificacion($as_codemp,$as_codclas,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_delete_clasificacion
//	          Access:  public
// 	        Arguments   
//        $as_codemp:  Código de la Empresa.
//       $as_codclas:  Código de la Clasificación.
//     $aa_seguridad:  Arreglo cargado con la información acerca de la ventana,usuario,etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de eliminar una clasificacion en la tabla rpc_clasificacion.
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:10/03/2006.	 
//////////////////////////////////////////////////////////////////////////////  

  $lb_valido=false;
  $lb_relacion=$this->uf_check_relaciones($as_codemp,$as_codclas);
  if (!$lb_relacion)
	 {
       $ls_sql= " DELETE FROM rpc_clasificacion WHERE codemp='".$as_codemp."' AND codclas='".$as_codclas."'";	    
       $this->io_sql->begin_transaction();
       $rs_data=$this->io_sql->execute($ls_sql);
       if ($rs_data===false)
	      {
	        $lb_valido=false;
 	        $this->io_msg->message("CLASE->SIGESP_RPC_C_CLASIFICACI; METODO->uf_delete_clasificacion; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	      }
       else
	     {
	       $lb_valido=true;
	       /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	       $ls_evento="DELETE";
		   $ls_descripcion ="Eliminó en RPC Parámetro de Calificación  ".$as_codclas;
		   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		   $aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               ///////////////////////////// 		     
	     }
 	 }	  		 
return $lb_valido;
}

function uf_select_clasificacion($as_codemp,$as_codclas) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_select_clasificacion
//	          Access:  public
// 	        Arguments   
//        $as_codemp:  Código de la Empresa.
//       $as_codclas:  Código de la Clasificación.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si existe o no una clasificacion, la funcion devuelve true si el
//                     registro es encontrado caso contrario devuelve false. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:22/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  $lb_valido=false;
  $ls_sql=" SELECT * ".
		  " FROM rpc_clasificacion ".
		  " WHERE codemp='".$as_codemp."'AND codclas='".$as_codclas."'";
  $rs_data=$this->io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
 	   $lb_valido=false;
 	   $this->io_msg->message("CLASE->SIGESP_RPC_C_CLASIFICACI; METODO->uf_select_clasificacion; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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


function uf_check_relaciones($as_codemp,$as_codclas)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_check_relaciones
//	          Access:  public
// 	        Arguments   
//        $as_codemp:  Código de la Empresa.
//       $as_codclas:  Código de la Clasificación.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si existen tablas relacionadas al Código de la Clasificación. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:22/03/2006.	 
//////////////////////////////////////////////////////////////////////////////

	$ls_sql="SELECT * FROM rpc_clasifxprov WHERE codemp='".$as_codemp."' AND codclas='".$as_codclas."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	  {
		$lb_valido=false;
 	    $this->io_msg->message("CLASE->SIGESP_RPC_C_CLASIFICACI; METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	  }
	else
	  {
		if($row=$this->io_sql->fetch_row($rs_data))
		  {
			$lb_valido=true;
			$this->is_msg_error="La Clasificación no puede ser eliminada, posee registros asociados a otras tablas !!!";
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