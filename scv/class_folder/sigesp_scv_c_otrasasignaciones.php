<?php
class sigesp_scv_c_otrasasignaciones
{
	var $ls_sql;
	var $is_msg_error;
		
	function sigesp_scv_c_otrasasignaciones($conn)
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
	
	function uf_scv_select_otrasasignaciones($as_codemp,$as_codotrasi) 
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_select_otrasasignaciones
		//	          Access:  public
		//	       Arguments:  $as_codemp    // código de empresa.
		//        			   $as_codotrasi // código de otras asignaciones
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de verificar la existencia de un registro en la tabla de otras asignaciones 
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  14/11/2006      
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_sql=" SELECT codotrasi".
				"   FROM scv_otrasasignaciones".
				"  WHERE codemp='".$as_codemp."'".
				"    AND codotrasi='".$as_codotrasi."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->sigesp_scv_c_otrasasignaciones METODO->uf_scv_select_otrasasignaciones ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // fin de la function uf_scv_select_otrasasignaciones

	function uf_scv_insert_otrasasignaciones($as_codemp,$as_codotrasi,$as_denotrasi,$ai_tarotrasi,$aa_seguridad) 
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_insert_otrasasignaciones
		//	          Access:  public
		//	        Arguments  $as_codemp    // código de empresa.
		//        			   $as_codotrasi // código de otras asignaciones
		//        			   $as_denotrasi // denominacion de otras asignaciones
		//        			   $ai_tarotrasi // tarifa de otras asignaciones
		//     				   $aa_seguridad // arreglo de seguridad
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de insertar una asignacion de viaticos (en la categoria "otras")
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  14/11/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_codotrasiaux = $as_codotrasi;
		$as_codotrasi=$this->io_funciondb->uf_generar_codigo(true,$as_codemp,'scv_otrasasignaciones','codotrasi');
		$this->io_sql->begin_transaction();
		$ls_sql=" INSERT INTO scv_otrasasignaciones (codemp, codotrasi, denotrasi, tarotrasi)".
				"      VALUES ('".$as_codemp."','".$as_codotrasi."','".$as_denotrasi."','".$ai_tarotrasi."')";
		$rs_data=$this->io_sql->execute($ls_sql);
		if ($rs_data===false)		     
		{
			$this->io_sql->rollback();
			if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
			{
				$lb_valido=$this->uf_scv_insert_otrasasignaciones($as_codemp,$as_codotrasi,$as_denotrasi,$ai_tarotrasi,$aa_seguridad) ;
			}
			else
			{
				$this->io_msg->message("CLASE->sigesp_scv_c_otrasasignaciones METODO->uf_scv_insert_otrasasignaciones ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			}
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Asignación (otras) de Viáticos ".$as_codotrasi." Asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////// 		     
			$lb_valido=true;
				
			if($lb_valido)
			{
				
				if($ls_codotrasiaux!=$as_codotrasi)
				{
					$this->io_msg->message("Se Asigno el Código: ".$as_codotrasi);
				}
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	} // fin de la function uf_scv_insert_otrasasignaciones
	
	function uf_scv_update_otrasasignaciones($as_codemp,$as_codotrasi,$as_denotrasi,$ai_tarotrasi,$aa_seguridad) 
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_update_otrasasignaciones
		//	          Access:  public
		//	        Arguments  $as_codemp    // código de empresa.
		//        			   $as_codotrasi // código de otras asignaciones
		//        			   $as_denotrasi // denominacion de otras asignaciones
		//        			   $ai_tarotrasi // tarifa de otras asignaciones
		//     				   $aa_seguridad // arreglo de seguridad
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de modificar una categoria de viaticos en la tabla scv_categorias
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  22/09/2006      
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$this->io_sql->begin_transaction();
		$ls_sql=" UPDATE scv_otrasasignaciones".
				"    SET denotrasi='".$as_denotrasi."'".
				"  WHERE codemp='" .$as_codemp. "'".
				"    AND codotrasi='".$as_codotrasi."'";
		$rs_data = $this->io_sql->execute($ls_sql);
		if ($rs_data===false)
		{
			$this->io_sql->rollback();
			$this->io_msg->message("CLASE->sigesp_scv_c_otrasasignaciones METODO->uf_scv_update_otrasasignaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Asignación (otras) de Viáticos ".$as_codotrasi." Asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		     
			$this->io_sql->commit();
		}  		      
		return $lb_valido;
	} // fin de la function uf_scv_update_otrasasignaciones
			
	function uf_scv_delete_otrasasignaciones($as_codemp,$as_codotrasi,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_delete_otrasasignaciones
		//	          Access:  public
		//	       Arguments:  $as_codemp    // código de empresa.
		//        			   $as_codotrasi // código de otras asignaciones
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de eliminar una asignacion de viaticos
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  22/09/2006      
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido= false;
		$lb_relacion= $this->uf_scv_check_relaciones($as_codemp,$as_codotrasi);
		if (!$lb_relacion)
		{
			$this->io_sql->begin_transaction();
			$ls_sql= " DELETE FROM scv_otrasasignaciones".
					 "  WHERE codemp='".$as_codemp."'".
					 "    AND codotrasi='".$as_codotrasi."'";	    
			$rs_data=$this->io_sql->execute($ls_sql);
			if ($rs_data===false)
			{
				$this->io_sql->rollback();
				$this->io_msg->message("CLASE->sigesp_scv_c_otrasasignaciones METODO->uf_scv_delete_otrasasignaciones ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la Asignación (otras) de Viáticos ".$as_codotrasi." Asociada a la Empresa ".$as_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               ///////////////////////////// 		     
				$this->io_sql->commit();
			}
		}	  		 
		return $lb_valido;
	}// fin de la function uf_scv_delete_otrasasignaciones
	
	
	function uf_scv_check_relaciones($as_codemp,$as_codtar)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	      Metodo:  uf_check_relaciones
		//	      Access:  public
		// 	   Arguments:  $as_codemp // codigo de empresa.
		//     			   $as_codtar // codigo de tarifa
		//	      Returns: Retorna un Booleano
		//	  Description: Función que se encarga de verificar si existen tablas relacionadas al Código de la Dependencia. 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/08/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codasi".
				"  FROM scv_dt_asignaciones".
				" WHERE codemp='".$as_codemp."'".
				"   AND proasi='TOA'".
				"   AND codasi='".$as_codtar."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->sigesp_scv_c_otrasasignaciones METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->is_msg_error="La Asignacion no puede ser eliminada, posee registros asociados a otras tablas";
			}
		}
		return $lb_valido;	
	}
	
} //Fin de la class sigesp_scv_c_misiones
?> 