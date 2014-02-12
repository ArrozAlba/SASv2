<?php 
class sigesp_spg_c_fuentfinan
{

var $ls_sql;
	
		function sigesp_spg_c_fuentfinan($conn)
		{
		  require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	      $this->seguridad = new sigesp_c_seguridad();		  
          require_once("../../shared/class_folder/class_funciones.php");
		  $this->io_funcion = new class_funciones();
		  require_once("../../shared/class_folder/class_mensajes.php");
		  $this->io_sql= new class_sql($conn);
		  $this->io_msg= new class_mensajes();		
		}
 

function uf_insert_fuente_financiamiento($as_codemp,$as_codfuefin,$as_denfuefin,$as_expfuefin,$aa_seguridad,$ls_status) 
{
//////////////////////////////////////////////////////////////////////////////
//	Metodo: uf_insert_fuente_financiamiento
//	Access:  public
//	Arguments: 
//        $as_codemp:  Código de la Empresa.
//        $as_codcla:  Código de la Clausula a Insertar.
//        $as_dencla:  Denominación de la Clusula que se va a Insertar.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	     Description:  Función que se encarga de insertar en la tabla soc_clausulas
//                     un código y denominación para una nueva clausula.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:20/02/2006.	 
//////////////////////////////////////////////////////////////////////////////  
	  
	  $ls_sql = " INSERT INTO sigesp_fuentefinanciamiento ".
	            " (codemp, codfuefin, denfuefin, expfuefin) ".
				" VALUES ('".$as_codemp."','".$as_codfuefin."','".$as_denfuefin."','".$as_expfuefin."')";
				
	  $this->io_sql->begin_transaction();
	  $rs_clausula=$this->io_sql->execute($ls_sql);
	  if ($rs_clausula===false)		     
		 {
		   $this->io_sql->rollback();
		   $this->io_msg->message("CLASE->SIGESP_SPG_C_FUENTFINAN; METODO->uf_insert_fuente_financiamiento; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }
	  else
		 {
		   $this->io_sql->commit();
		   $this->io_msg->message('Registro Incluido !!!');
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		   $ls_evento="INSERT";
		   $ls_descripcion ="Insertó en SPG la Fuente de Financiamiento ".$as_codfuefin;
		   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		   $aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               /////////////////////////// 		     
         }
//$this->io_sql->close();
}



function uf_update_fuente_financiamiento($as_codemp,$as_codfuefin,$as_denfuefin,$as_expfuefin,$aa_seguridad,$ls_status) 
{
//////////////////////////////////////////////////////////////////////////////
//	Metodo: uf_update_fuente_financiamiento
//	Access:  public
//	Arguments: 
//        $as_codemp:  Código de la Empresa.
//        $as_codcla:  Código de la Clausula a actualizar.
//        $as_dencla:  Denominación de la Clausula que se va a actualizar.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	     Description:  Función que se encarga de actualizar la denominacion
//                     de una clausula para la clausula que viene como parametro
//                     en la tabla soc_clausulas.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:20/02/2006.	 
////////////////////////////////////////////////////////////////////////////// 
 
	  if($ls_status=='C')
	  {
			$ls_sql=" UPDATE sigesp_fuentefinanciamiento ".
				  " SET  denfuefin='".$as_denfuefin."',expfuefin='".$as_expfuefin."' ".
				  " WHERE codemp='" .$as_codemp. "' AND codfuefin = '".$as_codfuefin."'";
		
			
		  $this->io_sql->begin_transaction();
		  $rs_clausula=$this->io_sql->execute($ls_sql);
		  if ($rs_clausula===false)
			 {
			   $this->io_sql->rollback();
			   $this->io_msg->message("CLASE->SIGESP_SPG_C_FUENTFINAN; METODO->uf_update_fuente_financiamiento; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			   $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
			 }
		  else
			 {
			   $this->io_sql->commit();
			   $this->io_msg->message('Registro Actualizado !!!');
			   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			   $ls_evento="UPDATE";
			   $ls_descripcion ="Actualizó en SPG la Fuente de Financiamiento Número ".$as_codfuefin;
			   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			   $aa_seguridad["ventanas"],$ls_descripcion);
			   /////////////////////////////////         SEGURIDAD               /////////////////////////////		     
			 }  		      
	
	}
	else
	{
		   $this->io_msg->message("Registro ya existe introduzca un nuevo codigo");
		   return false;
	}
 } 
		
		
		
function uf_delete_fuente_financiamiento($as_codemp,$as_codfuefin,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	Metodo: uf_delete_fuente_financiamiento
//	Access:  public
//	Arguments: 
//        $as_codemp:  Código de la Empresa.
//        $as_codcla:  Código de la Fuente de Financiamiento a eliminar.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	     Description:  Función que se encarga de eliminar la fuente de
//                     financiamiento que viene como parámetro en la tabla 
//                     soc_clausulas.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:20/02/2006.	 
////////////////////////////////////////////////////////////////////////////// 
 
  $lb_valido = $this->uf_select_fuente_financiamiento($as_codemp,$as_codfuefin); 
  $lb_tiene  = $this->uf_check_relaciones($as_codemp,$as_codfuefin);
  if (($lb_valido) && (!$lb_tiene))
	 {
       $ls_sql = " DELETE FROM sigesp_fuentefinanciamiento WHERE codemp='".$as_codemp."' AND codfuefin='".$as_codfuefin."'";	    
	   $this->io_sql->begin_transaction();
       $rs_data = $this->io_sql->execute($ls_sql);
       if ($rs_data===false)
	      {
			$lb_valido = false;
			$this->io_sql->rollback();
	        $this->io_msg->message('Error en Eliminación !!!');
	        $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
	      }
       else
	      {
			$lb_valido = true;
			$this->io_sql->commit();
	        $this->io_msg->message('Registro Eliminado !!!'); 
		    /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		    $ls_evento="DELETE";
		    $ls_descripcion ="Eliminó en SPG la Fuente de Financiamiento Número ".$as_codfuefin;
		    $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		    $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		    $aa_seguridad["ventanas"],$ls_descripcion);
		    /////////////////////////////////         SEGURIDAD               ///////////////////////////// 		     
	      } 		 
       //$this->io_sql->close();
	 }
return $lb_valido;
}


function uf_select_fuente_financiamiento($as_codemp,$as_codfuefin) 
{
/////////////////////////////////////////////////////////////////////////////////
//	          Metodo: uf_select_fuente_financiamiento
//	          Access:  public
//	       Arguments: 
//        $as_codemp:  Código de la Empresa.
//     $as_codfuefin:  Código de la Fuente de Financiamiento a Buscar en la tabla
//                     sigesp_fuentefinanciamiento.
//	     Description:  ///.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:20/02/2006.	 
////////////////////////////////////////////////////////////////////////////////// 

  $ls_sql=" SELECT * FROM sigesp_fuentefinanciamiento WHERE codemp='".$as_codemp."'AND codfuefin='".$as_codfuefin."'";
  $rs_clausula=$this->io_sql->select($ls_sql);
  if ($rs_clausula===false)
	 {
	   $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
       $lb_valido=false;
	 }
  else
	 {
	   $li_numrows=$this->io_sql->num_rows($rs_clausula);
	   if ($li_numrows>0)
		  {
		    $lb_valido=true;
		  }
	   else
		  {
		    $lb_valido=false;
		  }
	 }
$this->io_sql->free_result($rs_clausula);
return $lb_valido;
}

function uf_check_relaciones($as_codemp,$as_codfuefin)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_check_relaciones
//	          Access:  public
//	       Arguments: 
//        $as_codemp:  Código de la Empresa.
//     $as_codfuefin:  Código de la Fuente de Financiamiento a Buscar en las tablas.
//	     Description:  Metodo que se encarga de verificar las relaciones de una fuente de financiamiento,
//                     si no se consiguen relaciones, el registro puede ser elinado, caso contrario se envia un mensaje.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  27/09/2006       Fecha Última Actualización:27/09/2006.	 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
$lb_tiene = true;
$ls_sql   = "SELECT codfuefin FROM sep_solicitud WHERE codemp='".$as_codemp."' AND codfuefin='".$as_codfuefin."'";
$rs_data  = $this->io_sql->select($ls_sql);
if ($rs_data===false)
   {
	 $lb_valido=false;
	 $this->io_msg->message("CLASE->SIGESP_SPG_C_FUENTFINAN; METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
   }
else
   {
	 $li_numrows=$this->io_sql->num_rows($rs_data);
	 if ($li_numrows>0)
		{
		  $lb_tiene  = true;
		  $lb_valido = true;
		  $this->io_msg->message("La Fuente de Financiamiento no puede ser eliminada, posee registros asociados a otras tablas !!!");
		}
	 else
		{
		  $ls_sql = "SELECT codfuefin FROM soc_ordencompra WHERE codemp='".$as_codemp."' AND codfuefin ='".$as_codfuefin."'"; 
		  $rs_data  = $this->io_sql->select($ls_sql);
		  if ($rs_data===false)
			 {
			   $lb_valido=false;
			   $this->io_msg->message("CLASE->SIGESP_SPG_C_FUENTFINAN; METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			 }
		  else
			 {
			   $li_numrows=$this->io_sql->num_rows($rs_data);
			   if ($li_numrows>0)
				  {  
					$lb_tiene  = true;
					$lb_valido = true;
		            $this->io_msg->message("La Fuente de Financiamiento no puede ser eliminada, posee registros asociados a otras tablas !!!");
				  }
			   else
				  {
					$ls_sql  = "SELECT codfuefin FROM cxp_solicitudes WHERE codemp='".$as_codemp."' AND codfuefin ='".$as_codfuefin."'"; 
					$rs_data = $this->io_sql->select($ls_sql);
					if ($rs_data===false)
					   {
						 $lb_valido=false;
						 $this->io_msg->message("CLASE->SIGESP_SPG_C_FUENTFINAN; METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
					   }
					else
					   {
						 $li_numrows=$this->io_sql->num_rows($rs_data);
						 if ($li_numrows>0)
							{  
							  $lb_tiene  = true;
							  $lb_valido = true;
		                      $this->io_msg->message("La Fuente de Financiamiento no puede ser eliminada, posee registros asociados a otras tablas !!!");
							}
						 else
							{
							  $lb_tiene = false;
							}
					   }
				  }
			 }
		}	 
   }
return $lb_tiene;
}
}//Fin de la Clase...
?> 