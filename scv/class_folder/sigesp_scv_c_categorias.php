<?php
class sigesp_scv_c_categorias
{
	var $ls_sql;
	var $is_msg_error;
		
	function sigesp_scv_c_categorias($conn)
	{
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_mensajes.php");
		$this->seguridad = new sigesp_c_seguridad();		  
		$this->io_funcion = new class_funciones();
		$this->io_sql= new class_sql($conn);
		$this->io_msg= new class_mensajes();		
	}
	
	function uf_scv_select_categoria($as_codemp,$as_codcat) 
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_select_categoria
		//	          Access:  public
		//	       Arguments:  $as_codemp    // código de empresa.
		//        			   $as_codcat    // código de categoria
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de verificar la existencia de una categoria
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  22/09/2006      
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_sql=" SELECT * FROM scv_categorias".
				"  WHERE codemp='".$as_codemp."'".
				"    AND codcat='".$as_codcat."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->sigesp_scv_c_categorias METODO->uf_scv_select_categoria ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // fin de la function uf_scv_select_categoria

	function uf_scv_insert_categoria($as_codemp,$as_codcat,$as_dencat,$aa_seguridad) 
	{
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_insert_categoria
		//	          Access:  public
		//	        Arguments  $as_codemp    // código de empresa.
		//        			   $as_codcat    // código de categoria
		//    			       $as_dencat    // denominación de la categoria
		//     				   $aa_seguridad // arreglo de seguridad
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de insertar una nueva categoria de viaticos en la tabla scv_categorias
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  22/09/2006      
		////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$this->io_sql->begin_transaction();
		$ls_sql=" INSERT INTO scv_categorias (codemp,codcat,dencat)".
				"      VALUES ('".$as_codemp."','".$as_codcat."','".$as_dencat."')";
		$rs_data=$this->io_sql->execute($ls_sql);
		if ($rs_data===false)		     
		{
			$this->io_sql->rollback();
			$this->io_msg->message("CLASE->sigesp_scv_c_categorias METODO->uf_scv_insert_categoria ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Categoría de Viáticos ".$as_codcat." Asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////// 		     
			$lb_valido=true;
			$this->io_sql->commit();
		}
		return $lb_valido;
	} // fin de la function uf_scv_insert_categoria
	
	function uf_scv_update_categoria($as_codemp,$as_codcat,$as_dencat,$aa_seguridad) 
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_update_categoria
		//	          Access:  public
		//	        Arguments  $as_codemp    // código de empresa.
		//        			   $as_codcat    // código de categoria
		//    			       $as_dencat    // denominación de la categoria
		//     				   $aa_seguridad // arreglo de seguridad
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de modificar una categoria de viaticos en la tabla scv_categorias
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  22/09/2006      
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$this->io_sql->begin_transaction();
		$ls_sql=" UPDATE scv_categorias SET dencat='".$as_dencat."'".
				"  WHERE codemp='" .$as_codemp. "'".
				"    AND codcat='".$as_codcat."'";
		$rs_data = $this->io_sql->execute($ls_sql);
		if ($rs_data===false)
		{
			$this->io_sql->rollback();
			$this->io_msg->message("CLASE->sigesp_scv_c_categorias METODO->uf_scv_update_categoria; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Categoría de Viáticos ".$as_codcat." Asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		     
			$this->io_sql->commit();
		}  		      
		return $lb_valido;
	} // fin de la function uf_scv_update_categoria
			
	function uf_scv_delete_categoria($as_codemp,$as_codcat,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_delete_categoria
		//	          Access:  public
		//	       Arguments:  $as_codemp    // código de empresa.
		//        			   $as_codcat    // código de categoria
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de eliminar una categoria de la tabla scv_categorias
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  22/09/2006      
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido= false;
		$lb_relacion=$this->uf_scv_check_relaciones($as_codemp,$as_codcat,"sno_personalnomina");
		if (!$lb_relacion)
		{
			$lb_relacion=$this->uf_scv_check_relaciones($as_codemp,$as_codcat,"scv_tarifas");
			if (!$lb_relacion)
			{
				$this->io_sql->begin_transaction();
				$ls_sql= " DELETE FROM scv_categorias".
						 "  WHERE codemp='".$as_codemp."'".
						 "    AND codcat='".$as_codcat."'";	    
				$rs_data=$this->io_sql->execute($ls_sql);
				if ($rs_data===false)
				{
					$this->io_sql->rollback();
					$this->io_msg->message("CLASE->sigesp_scv_c_categoria METODO->uf_scv_delete_categoria; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				}
				else
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="DELETE";
					$ls_descripcion ="Eliminó la Categoría de Viáticos ".$as_codcat." Asociada a la Empresa ".$as_codemp;
					$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               ///////////////////////////// 		     
					$this->io_sql->commit();
				}
			}	  		 
		}
		return $lb_valido;
	}// fin de la function uf_scv_delete_categoria
	
	
	function uf_scv_check_relaciones($as_codemp,$as_codcat,$as_tabla)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_check_relaciones
		//	          Access:  public
		//	       Arguments:  $as_codemp    // código de empresa.
		//        			   $as_codcat    // código de categoria
		//        			   $as_tabla     // tabla para la busqueda
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de eliminar una categoria de la tabla scv_categorias
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  22/09/2006      
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		if($as_tabla=="sno_personalnomina")
		{
			$ls_sql="SELECT * FROM sno_personalnomina".
					" WHERE codemp='".$as_codemp."'".
					"   AND codclavia='".$as_codcat."'";
		}
		else
		{
			$ls_sql="SELECT * FROM scv_tarifas".
					" WHERE codemp='".$as_codemp."'".
					"   AND codcat='".$as_codcat."'";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->sigesp_scv_c_categorias METODO->uf_scv_check_relaciones ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_msg->message("La Categoría de viaticos tiene registros asociados");
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;	
	} //Fin de la function uf_scv_check_relaciones
	
} //Fin de la class sigesp_scv_c_misiones
?> 