<?php
class sigesp_scv_c_misiones
{
	var $ls_sql;
	var $is_msg_error;
		
	function sigesp_scv_c_misiones($conn)
	{
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_funciones_db.php"); 
		$this->io_funciondb= new class_funciones_db($conn);
		$this->seguridad = new sigesp_c_seguridad();		  
		$this->io_funcion = new class_funciones();
		$this->io_sql= new class_sql($conn);
		$this->io_msg= new class_mensajes();		
	}
	
	function uf_insert_mision($as_codemp,$as_codmis,$as_denmis,$aa_seguridad) 
	{
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_insert_mision
		//	          Access:  public
		//	        Arguments  $as_codemp    // Código de la Empresa.
		//        			   $as_codmis    //  Código de la Misión.
		//    			       $as_denmis    // Denominación de la Misión.
		//     				   $aa_seguridad // Arreglo de seguridad
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de insertar una nueva misión en la tabla scv_misiones. 
		//     Elaborado Por:  Ing. Néstor Falcón.
		// Fecha de Creación:  22/06/2006      
		//    Modificado Por:  Ing. Luis Anibal Lang
		//   Fecha de Modif.:  18/09/2006      
		////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_codmisaux = $as_codmis;
		$as_codmis=$this->io_funciondb->uf_generar_codigo(true,$as_codemp,'scv_misiones','codmis');		 
		$this->io_sql->begin_transaction();
		$ls_sql=" INSERT INTO scv_misiones (codemp,codmis,denmis)".
				" VALUES ('".$as_codemp."','".$as_codmis."','".$as_denmis."')";
		$rs_data=$this->io_sql->execute($ls_sql);
		if ($rs_data===false)		     
		{
			$this->io_sql->rollback();
			if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
			{
				$lb_valido=$this->uf_insert_mision($as_codemp,$as_codmis,$as_denmis,$aa_seguridad);
			}
			else
			{
				$this->io_msg->message("CLASE->SIGESP_SCV_C_MISIONES; METODO->uf_insert_mision; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			}
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Misión ".$as_codmis." Asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////// 		     
			$lb_valido=true;
			if($ls_codmisaux!=$as_codmis)
			{
				$this->io_msg->message("Se Asigno el Código de Mision: ".$as_codmis);
			}
			$this->io_sql->commit();
		}
		return $lb_valido;
	} // fin de la function uf_insert_mision
	
	function uf_update_mision($as_codemp,$as_codmis,$as_denmis,$aa_seguridad) 
	{
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_update_mision
		//	          Access:  public
		//	       Arguments:  $as_codemp    // Código de la Empresa.
		//        			   $as_codmis    //  Código de la Misión.
		//    			       $as_denmis    // Denominación de la Misión.
		//     				   $aa_seguridad // Arreglo de seguridad
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de actualizar los datos de una Misión en la tabla scv_misiones. 
		//     Elaborado Por:  Ing. Néstor Falcón.
		// Fecha de Creación:  22/06/2006        
		//    Modificado Por:  Ing. Luis Anibal Lang
		//   Fecha de Modif.:  18/09/2006      
		////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$this->io_sql->begin_transaction();
		$ls_sql=" UPDATE scv_misiones SET denmis='".$as_denmis."'".
				" WHERE codemp='" .$as_codemp. "'".
				" AND codmis='".$as_codmis."'";
		$rs_data = $this->io_sql->execute($ls_sql);
		if ($rs_data===false)
		{
			$this->io_sql->rollback();
			$this->io_msg->message("CLASE->SIGESP_SCV_C_MISIONES; METODO->uf_update_mision; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Misión  ".$as_codmis." Asociado a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		     
			$this->io_sql->commit();
		}  		      
		return $lb_valido;
	} // fin de la function uf_update_mision
			
	function uf_delete_mision($as_codemp,$as_codmis,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_delete_mision
		//	          Access:  public
		//	       Arguments:  $as_codemp    // Código de la Empresa.
		//        			   $as_codmis    //  Código de la Misión.
		//     				   $aa_seguridad // Arreglo de seguridad
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de eliminar una misión en la tabla scv_misiones.
		//     Elaborado Por:  Ing. Néstor Falcón.
		// Fecha de Creación:  22/06/2006        
		//    Modificado Por:  Ing. Luis Anibal Lang
		//   Fecha de Modif.:  18/09/2006      
		//////////////////////////////////////////////////////////////////////////////  
		$lb_valido= false;
		$lb_relacion= $this->uf_check_relaciones($as_codemp,$as_codmis);
		if (!$lb_relacion)
		{
			$this->io_sql->begin_transaction();
			$ls_sql= " DELETE FROM scv_misiones".
					 " WHERE codemp='".$as_codemp."'".
					 " AND codmis='".$as_codmis."'";	    
			$rs_data=$this->io_sql->execute($ls_sql);
			if ($rs_data===false)
			{
				$this->io_sql->rollback();
				$this->io_msg->message("CLASE->SIGESP_SCV_C_MISIONES; METODO->uf_delete_mision; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la Misión ".$as_codmis." Asociada a la Empresa ".$as_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
				$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
				$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               ///////////////////////////// 		     
				$this->io_sql->commit();
			}
		}	  		 
		return $lb_valido;
	}// fin de la function uf_delete_mision
	
	function uf_load_mision($as_codemp,$as_codmis) 
	{
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_load_mision
		//	          Access:  public
		//	       Arguments:  $as_codemp    // Código de la Empresa.
		//        			   $as_codmis    //  Código de la Misión.
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de verificar si existe o no una misión, la funcion devuelve true si el
		//                     registro es encontrado caso contrario devuelve false. 
		//     Elaborado Por:  Ing. Néstor Falcón.
		// Fecha de Creación:  22/06/2006       
		//    Modificado Por:  Ing. Luis Anibal Lang
		//   Fecha de Modif.:  18/09/2006      
		////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_sql=" SELECT * FROM scv_misiones".
				" WHERE codemp='".$as_codemp."'".
				" AND codmis='".$as_codmis."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->SIGESP_SCV_C_MISIONES; METODO->uf_load_mision; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // fin de la function uf_load_mision
	
	function uf_check_relaciones($as_codemp,$as_codmis)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_check_relaciones
		//	          Access:  public
		//	       Arguments:  $as_codemp    // Código de la Empresa.
		//        			   $as_codmis    //  Código de la Misión.
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de verificar si existen tablas relacionadas al Código de la Misión. 
		//     Elaborado Por:  Ing. Néstor Falcón.
		// Fecha de Creación:  22/06/2006       
		//    Modificado Por:  Ing. Luis Anibal Lang
		//   Fecha de Modif.:  18/09/2006      
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codmis".
				"  FROM scv_solicitudviatico".
				" WHERE codemp='".$as_codemp."'".
				"   AND codmis='".$as_codmis."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->SIGESP_SCV_C_MISIONES; METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->is_msg_error="La Misión no puede ser eliminada, posee registros asociados a otras tablas";
			}
		}
		return $lb_valido;	
	} //Fin de la function uf_check_relaciones
	
} //Fin de la class sigesp_scv_c_misiones
?> 