<?
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");
require_once("../shared/class_folder/class_funciones.php");
//----------------------------------------------------------------------------------------------------------------------------------
class sigesp_saf_c_grupo
{
	var $io_sql;
	var $is_msg_error;
	var $datemp;
	var $con;
	var $siginc;	
	function sigesp_saf_c_grupo()
	{
		$this->io_msg=new class_mensajes();
		$this->io_funcion = new class_funciones();
		$this->siginc=new sigesp_include();
		$this->con=$this->siginc->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];		
		$this->seguridad= new sigesp_c_seguridad();
	}
//----------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_select_grupo($as_codgru)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_grupo
		//		   Access: private
		//		 Argument: as_codgru // Codigo de Grupo
		//	  Description: Funcion que verifica la existencia de un grupo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/05/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = " SELECT codgru ".
		          " FROM saf_grupo  ".
				  " WHERE codgru='".$as_codgru."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->grupo MÉTODO->uf_saf_select_grupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}//fin function uf_saf_select_grupo()
//----------------------------------------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------------------------------------
	function  uf_saf_insert_grupo($as_codgru,$as_dengru,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_grupo
		//		   Access: private
		//		 Argument: as_codgru // Codigo de Grupo
		//                 as_dengru // Denominacion de Grupo
		//				   aa_seguridad // Arreglo de variables de seguridad
		//	  Description: Funcion que registra un grupo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/05/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
        $this->io_sql->begin_transaction();
		$ls_sql = " INSERT INTO saf_grupo (codgru, dengru)". 
				  " VALUES('".$as_codgru."','".$as_dengru."')" ;
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->grupo MÉTODO->uf_saf_insert_grupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Grupo ".$as_codgru;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
		return $lb_valido;
	}//fin de la uf_saf_insert_grupo
//----------------------------------------------------------------------------------------------------------------------------------
	
//----------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_update_grupo($as_codgru,$as_dengru,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_grupo
		//		   Access: private
		//		 Argument: as_codgru // Codigo de Grupo
		//                 as_dengru // Denominacion de Grupo
		//				   aa_seguridad // Arreglo de variables de seguridad
		//	  Description: Funcion que actualiza un grupo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/05/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	    $lb_valido=false;
	    $ls_sql = " UPDATE saf_grupo ".
		          " SET    dengru='".$as_dengru."'". 
	 		      " WHERE  codgru='".$as_codgru."' ";
        $this->io_sql->begin_transaction();
		$li_exec = $this->io_sql->execute($ls_sql);
		if($li_exec===false)
		{
			$this->io_msg->message("CLASE->grupo MÉTODO->uf_saf_update_grupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Grupo ".$as_dengru;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	    return $lb_valido;
	}// fin function uf_saf_update_grupo
//----------------------------------------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_guardar_grupo($as_codgru,$as_dengru,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_guardar_grupo
		//		   Access: private
		//		 Argument: as_codgru // Codigo de Grupo
		//                 as_dengru // Denominacion de Grupo
		//				   aa_seguridad // Arreglo de variables de seguridad
		//	  Description: Funcion que realiza las operaciondes de guardar un grupo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/05/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=$this->uf_saf_select_grupo($as_codgru);
		if ($lb_valido)
		{
			$lb_valido=$this->uf_saf_update_grupo($as_codgru,$as_dengru,$aa_seguridad);
			if($lb_valido)
			{
				$this->io_msg->message("El registro fue actualizado con exito");
			}	
			else
			{
				$this->io_msg->message("El registro no pudo ser actualizado");
			}
		}
		else
		{
			$lb_valido=$this->uf_saf_insert_grupo($as_codgru,$as_dengru,$aa_seguridad);
			if ($lb_valido)
			{
				$this->io_msg->message("El registro fue guardado con exito.");
			}
			else
			{
				$this->io_msg->message("No se pudo incluir el registro");
			}
	  }
	  return $lb_valido;	
    }// fin function uf_saf_guardar_grupo
//----------------------------------------------------------------------------------------------------------------------------------
	
//----------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_delete_grupo($as_codgru,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_delete_grupo
		//		   Access: private
		//		 Argument: as_codgru // Codigo de Grupo
		//				   aa_seguridad // Arreglo de variables de seguridad
		//	  Description: Funcion que elimina un grupo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/05/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_where="codemp='".$this->ls_codemp."' AND codgru='".$as_codgru."' ";
		$lb_valido=$this->uf_saf_check_relaciones("saf_activo","codgru",$ls_where);
		if(!$lb_valido)
		{
			$lb_existe=$this->uf_saf_select_grupo($as_codgru);
			$lb_existe_relacion=$this->uf_saf_select_subgrupo($as_codgru,"");
			$this->io_sql->begin_transaction();
			if(($lb_existe)&&(!$lb_existe_relacion))
			{
				$ls_sql= " DELETE ".
						 " FROM saf_grupo". 
						 " WHERE  codgru='".$as_codgru."' ";
				$rs_data=$this->io_sql->execute($ls_sql);
				if($rs_data===false)
				{
					$this->io_msg->message("CLASE->grupo MÉTODO->uf_saf_delete_grupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;
					$this->io_sql->rollback();
				}
				else
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="DELETE";
					$ls_descripcion ="Eliminó el Grupo ".$as_codgru;
					$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////			
					$this->io_sql->commit();
				}
			}
			else
			{
				$lb_valido=false;
				$this->io_msg->message("Codigo no existe..");
			}
		}	
		else
		{
			$this->io_msg->message("Posee relaciones no se puede eliminar..");
			$lb_valido=false;
		}
		return $lb_valido;
	}//fin function delete_saf_subgrupo
//----------------------------------------------------------------------------------------------------------------------------------
	
//----------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_select_subgrupo($as_codgru,$as_codsubgru)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_subgrupo
		//		   Access: private
		//		 Argument: as_codgru // Codigo de Grupo
		//				   as_codsubgru // Codigo de Sub Grupo
		//	  Description: Funcion que verifica la existencia de un sub-grupo asociado a un grupo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/05/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codsubgru ".
				"  FROM saf_subgrupo ". 
				" WHERE codgru='".$as_codgru."'".
				"   AND codsubgru='".$as_codsubgru."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)			
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->grupo MÉTODO->uf_saf_select_subgrupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}	
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
 }//fin de function uf_saf_select_subgrupo
//----------------------------------------------------------------------------------------------------------------------------------
	
//----------------------------------------------------------------------------------------------------------------------------------
	function  uf_saf_insert_subgrupo($as_codgru,$as_codsubgru,$as_densubgru,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_subgrupo
		//		   Access: private
		//		 Argument: as_codgru // Codigo de Grupo
		//				   as_codsubgru // Codigo de Sub-Grupo
		//                 as_densubgru // Denominacion de Sub-Grupo
		//				   aa_seguridad // Arreglo de variables de seguridad
		//	  Description: Funcion que Inserta un sub grupo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/05/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $this->io_sql->begin_transaction();
		$ls_sql = " INSERT INTO saf_subgrupo (codgru,codsubgru, densubgru)". 
				  " VALUES( '".$as_codgru."','".$as_codsubgru."','".$as_densubgru."')" ;
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->grupo MÉTODO->uf_saf_insert_subgrupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el SubGrupo ".$as_codsubgru." Asociado al Grupo ".$as_codgru;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
	}
		return $lb_valido;
	}//fin de la uf_saf_insert_subgrupo
//----------------------------------------------------------------------------------------------------------------------------------
	
//----------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_update_subgrupo($as_codgru,$as_codsubgru,$as_denominacion,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_subgrupo
		//		   Access: private
		//		 Argument: as_codgru // Codigo de Grupo
		//				   as_codsubgru // Codigo de Sub-Grupo
		//                 as_densubgru // Denominacion de Sub-Grupo
		//				   aa_seguridad // Arreglo de variables de seguridad
		//	  Description: Funcion que Actualiza un sub grupo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/05/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql ="UPDATE saf_subgrupo ".
		         "   SET densubgru='". $as_denominacion ."' ". 
			     " WHERE codgru='".$as_codgru."'".
				 "   AND  codsubgru='".$as_codsubgru."' ";
		$this->io_sql->begin_transaction();
		$rs_data = $this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->grupo MÉTODO->uf_saf_update_subgrupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el SubGrupo ".$as_codsubgru." Asociado al Grupo ".$as_codgru;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	    return $lb_valido;
	}// fin de la function uf_saf_update_subgrupo
//----------------------------------------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_guardar_subgrupo($as_codgru,$as_codsubgru,$as_densubgru,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_guardar_subgrupo
		//		   Access: private
		//		 Argument: as_codgru // Codigo de Grupo
		//				   as_codsubgru // Codigo de Sub-Grupo
		//                 as_densubgru // Denominacion de Sub-Grupo
		//				   aa_seguridad // Arreglo de variables de seguridad
		//	  Description: Funcion que realiza las operaciones de guardar un sub grupo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/05/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=$this->uf_saf_select_subgrupo($as_codgru,$as_codsubgru);
		if($lb_valido)
		{
			$lb_valido=$this->uf_saf_update_subgrupo($as_codgru,$as_codsubgru,$as_densubgru,$aa_seguridad);
			if($lb_valido)
			{
				$this->io_msg->message("El registro fue actualizado con exito");
			}	
			else
			{
				$this->io_msg->message("El registro no pudo ser actualizado");
			}
		}
		else
		{
			$lb_valido=$this->uf_saf_insert_subgrupo($as_codgru,$as_codsubgru,$as_densubgru,$aa_seguridad);
			if ($lb_valido)
			{
				$this->io_msg->message("El registro fue guardado con exito.");
			}
			else
			{
				$this->io_msg->message("El registro no pudo ser guardado");
			}
		}
	    return $lb_valido;	
    }// fin de la function uf_saf_guardar_subgrupo
//----------------------------------------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_delete_subgrupo($as_codgru,$as_codsubgru,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_guardar_subgrupo
		//		   Access: private
		//		 Argument: as_codgru // Codigo de Grupo
		//				   as_codsubgru // Codigo de Sub-Grupo
		//				   aa_seguridad // Arreglo de variables de seguridad
		//	  Description: Funcion que Elimina un sub grupo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/05/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_where="codemp='".$this->ls_codemp."' AND  codgru='".$as_codgru."' AND codsubgru='".$as_codsubgru."' ";
		$lb_valido=$this->uf_saf_check_relaciones("saf_activo","codsubgru",$ls_where);
		if(!$lb_valido)
		{
			$lb_existe=$this->uf_saf_select_subgrupo($as_codgru,$as_codsubgru);
			$lb_existe_relacion=$this->uf_saf_select_seccion($as_codgru,$as_codsubgru,"");
			$this->io_sql->begin_transaction();
			if(($lb_existe)&&(!$lb_existe_relacion))
			{
				$ls_sql=" DELETE FROM saf_subgrupo ". 
						" WHERE  codgru='".$as_codgru."' ".
						" AND codsubgru='".$as_codsubgru."' ";
				$li_return=$this->io_sql->execute($ls_sql);
				if($li_return===false)
				{
					$this->io_msg->message("CLASE->grupo MÉTODO->uf_saf_delete_subgrupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;
					$this->io_sql->rollback();
				}
				else
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="DELETE";
					$ls_descripcion ="Eliminó el SubGrupo ".$as_codsubgru." Asociado al Grupo ".$as_codgru;
					$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////			
					$this->io_sql->commit();
				}
			}
			else
			{
				$lb_valido=false;
				$this->io_msg->message("Codigo no existe o posee relaciones");
			}
		  }
		  return $lb_valido;
	}// fin de function uf_saf_delete_subgrupo
//----------------------------------------------------------------------------------------------------------------------------------
	
//----------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_select_seccion($as_codgru,$as_codsubgru,$as_codsec)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_guardar_subgrupo
		//		   Access: private
		//		 Argument: as_codgru // Codigo de Grupo
		//				   as_codsubgru // Codigo de Sub-Grupo
		//				   as_codsec // Codigo de Seccion
		//	  Description: Funcion que verifica la existencia de una seccion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/05/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codsec".
				"  FROM saf_seccion". 
				" WHERE codgru = '".$as_codgru."'".
				"   AND codsubgru ='".$as_codsubgru."'".
				"   AND codsec='".$as_codsec."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->grupo MÉTODO->uf_saf_select_seccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
		    $this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}//fin de function uf_saf_select_seccion
//----------------------------------------------------------------------------------------------------------------------------------
	
//----------------------------------------------------------------------------------------------------------------------------------
	function  uf_saf_insert_seccion($as_codgru,$as_codsubgru,$as_codsec,$as_densec,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_seccion
		//		   Access: private
		//		 Argument: as_codgru // Codigo de Grupo
		//				   as_codsubgru // Codigo de Sub-Grupo
		//                 as_codsec // Codigo de Seccion
		//                 as_densec // Denominacion de Seccion
		//				   aa_seguridad // Arreglo de variables de seguridad
		//	  Description: Funcion que inserta una seccion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/05/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO saf_seccion (codgru, codsubgru, codsec, densec)". 
				  "VALUES( '".$as_codgru."','".$as_codsubgru."','".$as_codsec."','".$as_densec."')" ;
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->grupo MÉTODO->uf_saf_insert_seccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Sección ".$as_codsec." Asociado al SubGrupo ".$as_codsubgru." y al Grupo ".$as_codgru;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
		return $lb_valido;
	}//fin de la uf_saf_insert_seccion
//----------------------------------------------------------------------------------------------------------------------------------
	
//----------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_update_seccion($as_codgru,$as_codsubgru,$as_codsec,$as_densec,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_seccion
		//		   Access: private
		//		 Argument: as_codgru // Codigo de Grupo
		//				   as_codsubgru // Codigo de Sub-Grupo
		//                 as_codsec // Codigo de Seccion
		//                 as_densec // Denominacion de Seccion
		//				   aa_seguridad // Arreglo de variables de seguridad
		//	  Description: Funcion que Actualiza  una seccion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/05/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=false;
	    $ls_sql ="UPDATE saf_seccion ".
		         "   SET densec='".$as_densec."'". 
	 		     " WHERE codgru='".$as_codgru."'".
			     "   AND codsubgru='".$as_codsubgru."' ".
			     "   AND codsec='".$as_codsec."'";
        $this->io_sql->begin_transaction();
		$rs_data = $this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->grupo MÉTODO->uf_saf_update_seccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Sección ".$as_codsec." Asociado al SubGrupo ".$as_codsubgru." y al Grupo ".$as_codgru;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	    return $lb_valido;
	}// fin function uf_saf_update_seccion
//----------------------------------------------------------------------------------------------------------------------------------
	
//----------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_guardar_seccion($as_codgru,$as_codsubgru,$as_codsec,$as_densec,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_guardar_seccion
		//		   Access: private
		//		 Argument: as_codgru // Codigo de Grupo
		//				   as_codsubgru // Codigo de Sub-Grupo
		//                 as_codsec // Codigo de Seccion
		//                 as_densec // Denominacion de Seccion
		//				   aa_seguridad // Arreglo de variables de seguridad
		//	  Description: Funcion que realiza las operaciones de guardar  una seccion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/05/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=$this->uf_saf_select_seccion($as_codgru,$as_codsubgru,$as_codsec);
		if ($lb_valido)
		{
			$lb_valido=$this->uf_saf_update_seccion($as_codgru,$as_codsubgru,$as_codsec,$as_densec,$aa_seguridad);

			if($lb_valido)
			{
				$this->io_msg->message("El registro fue actualizado con exito");
			}	
			else
			{
				$this->io_msg->message("El registro no pudo ser actualizado");
			}
		}
		else
		{
			$lb_valido=$this->uf_saf_insert_seccion($as_codgru,$as_codsubgru,$as_codsec,$as_densec,$aa_seguridad);

			if ($lb_valido)
			{
				$this->io_msg->message("El registro fue guardado.");
			}
			else
			{
				$this->io_msg->message("No se pudo guardar el registro");
			}
		}
	    return $lb_valido;	
    }// fin de la function uf_saf_guardar_seccion
//----------------------------------------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_delete_seccion($as_codgru,$as_codsubgru,$as_codsec,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_delete_seccion
		//		   Access: private
		//		 Argument: as_codgru // Codigo de Grupo
		//				   as_codsubgru // Codigo de Sub-Grupo
		//                 as_codsec // Codigo de Seccion
		//				   aa_seguridad // Arreglo de variables de seguridad
		//	  Description: Funcion que elimina  una seccion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/05/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_where="codemp='".$this->ls_codemp."' AND codgru = '".$as_codgru."' AND codsubgru ='".$as_codsubgru."' AND codsec='".$as_codsec."'  ";
		$lb_existe_relacion=$this->uf_saf_check_relaciones("saf_activo","codsec",$ls_where);
		if(!$lb_existe_relacion)
		{
		    $lb_existe=$this->uf_saf_select_seccion($as_codgru,$as_codsubgru,$as_codsec);
			$this->io_sql->begin_transaction();
			if($lb_existe)
			{
				$ls_sql="DELETE  ".
						"  FROM  saf_seccion ". 
						" WHERE codgru='".$as_codgru."'".
						"   AND codsubgru='".$as_codsubgru."'".
						"   AND codsec='".$as_codsec."' ";
				$rs_data=$this->io_sql->execute($ls_sql);
				if($rs_data===false)
				{
					$this->io_msg->message("CLASE->grupo MÉTODO->uf_saf_delete_seccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;
					$this->io_sql->rollback();
				}
				else
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="DELETE";
					$ls_descripcion ="Elininó la Sección ".$as_codsec." Asociado al SubGrupo ".$as_codsubgru." y al Grupo ".$as_codgru;
					$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////			
					$this->io_sql->commit();
				}
			}
			else
			{
				$lb_valido=false;
				$this->io_msg->message("Codigo no existe..");
			}
		 }
		 else
		 {
			$this->io_msg->message("Existe relacion con este registro..");
		 }	
		return $lb_valido;
	}//fin de function uf_saf_delete_seccion
//----------------------------------------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------------------------------------
	function  uf_saf_insert_item($as_codgru,$as_codsubgru,$as_codsec,$as_codite,$as_denite,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_item
		//		   Access: private
		//		 Argument: as_codgru // Codigo de Grupo
		//				   as_codsubgru // Codigo de Sub-Grupo
		//                 as_codsec // Codigo de Seccion
		//                 as_codite // Codigo de Item
		//                 as_denite // Denominacion de Item
		//				   aa_seguridad // Arreglo de variables de seguridad
		//	  Description: Funcion que inserta una seccion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 17/11/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO saf_item (codgru, codsubgru, codsec, codite, denite)". 
				  "VALUES( '".$as_codgru."','".$as_codsubgru."','".$as_codsec."','".$as_codite."','".$as_denite."')" ;
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->grupo MÉTODO->uf_saf_insert_item ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion="Insertó el Item ".$as_codite." Asociado a la Seccion ".$as_codsubgru.
							" Asociado al SubGrupo ".$as_codsubgru." y al Grupo ".$as_codgru;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
		return $lb_valido;
	}//fin de la uf_saf_insert_item
//----------------------------------------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_update_item($as_codgru,$as_codsubgru,$as_codsec,$as_codite,$as_denite,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_item
		//		   Access: private
		//		 Argument: as_codgru // Codigo de Grupo
		//				   as_codsubgru // Codigo de Sub-Grupo
		//                 as_codsec // Codigo de Seccion
		//                 as_codite // Codigo de Item
		//                 as_denite // Denominacion de Item
		//				   aa_seguridad // Arreglo de variables de seguridad
		//	  Description: Funcion que Actualiza  una seccion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 17/11/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=false;
	    $ls_sql ="UPDATE saf_item ".
		         "   SET denite='".$as_denite."'". 
	 		     " WHERE codgru='".$as_codgru."'".
			     "   AND codsubgru='".$as_codsubgru."' ".
			     "   AND codsec='".$as_codsec."' ".
			     "   AND codite='".$as_codite."'";
        $this->io_sql->begin_transaction();
		$rs_data = $this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->grupo MÉTODO->uf_saf_update_item ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion="Actualizó el Item ".$as_codite." Asociado a la Seccion ".$as_codsubgru.
							" Asociado al SubGrupo ".$as_codsubgru." y al Grupo ".$as_codgru;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	    return $lb_valido;
	}// fin function uf_saf_update_item
//----------------------------------------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_select_item($as_codgru,$as_codsubgru,$as_codsec,$as_codite)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_item
		//		   Access: private
		//		 Argument: as_codgru // Codigo de Grupo
		//				   as_codsubgru // Codigo de Sub-Grupo
		//				   as_codsec // Codigo de Seccion
		//				   as_codite // Codigo de Item
		//	  Description: Funcion que verifica la existencia de un  Item
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 17/11/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codite".
				"  FROM saf_item". 
				" WHERE codgru = '".$as_codgru."'".
				"   AND codsubgru ='".$as_codsubgru."'".
				"   AND codsec='".$as_codsec."'".
				"   AND codite='".$as_codite."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->grupo MÉTODO->uf_saf_select_item ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
		    $this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}//fin de function uf_saf_select_item
//----------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_guardar_item($as_codgru,$as_codsubgru,$as_codsec,$as_codite,$as_denite,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_guardar_seccion
		//		   Access: private
		//		 Argument: as_codgru // Codigo de Grupo
		//				   as_codsubgru // Codigo de Sub-Grupo
		//                 as_codsec // Codigo de Seccion
		//                 as_codite // Codigo de Item
		//                 as_denite // Denominacion de Item
		//				   aa_seguridad // Arreglo de variables de seguridad
		//	  Description: Funcion que realiza las operaciones de guardar  un item
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/05/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=$this->uf_saf_select_item($as_codgru,$as_codsubgru,$as_codsec,$as_codite);
		if ($lb_valido)
		{
			$lb_valido=$this->uf_saf_update_item($as_codgru,$as_codsubgru,$as_codsec,$as_codite,$as_denite,$aa_seguridad);

			if($lb_valido)
			{
				$this->io_msg->message("El registro fue actualizado con exito");
			}	
			else
			{
				$this->io_msg->message("El registro no pudo ser actualizado");
			}
		}
		else
		{
			$lb_valido=$this->uf_saf_insert_item($as_codgru,$as_codsubgru,$as_codsec,$as_codite,$as_denite,$aa_seguridad);

			if ($lb_valido)
			{
				$this->io_msg->message("El registro fue guardado.");
			}
			else
			{
				$this->io_msg->message("No se pudo guardar el registro");
			}
		}
	    return $lb_valido;	
    }// fin de la function uf_saf_guardar_seccion
//----------------------------------------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_delete_item($as_codgru,$as_codsubgru,$as_codsec,$as_codite,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_delete_item
		//		   Access: private
		//		 Argument: as_codgru // Codigo de Grupo
		//				   as_codsubgru // Codigo de Sub-Grupo
		//                 as_codsec // Codigo de Seccion
		//                 as_codite // Codigo de Item
		//				   aa_seguridad // Arreglo de variables de seguridad
		//	  Description: Funcion que elimina  un Item
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 17/11/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_where="codemp='".$this->ls_codemp."' AND codgru = '".$as_codgru."' AND codsubgru ='".$as_codsubgru."' AND codsec='".$as_codsec."'AND codite='".$as_codite."'  ";
		$lb_existe_relacion=$this->uf_saf_check_relaciones("saf_activo","codite",$ls_where);
		if(!$lb_existe_relacion)
		{
		    $lb_existe=$this->uf_saf_select_item($as_codgru,$as_codsubgru,$as_codsec,$as_codite);
			$this->io_sql->begin_transaction();
			if($lb_existe)
			{
				$ls_sql="DELETE  ".
						"  FROM  saf_item ". 
						" WHERE codgru='".$as_codgru."'".
						"   AND codsubgru='".$as_codsubgru."'".
						"   AND codsec='".$as_codsec."' ".
						"   AND codite='".$as_codite."' ";
				$rs_data=$this->io_sql->execute($ls_sql);
				if($rs_data===false)
				{
					$this->io_msg->message("CLASE->grupo MÉTODO->uf_saf_delete_item ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;
					$this->io_sql->rollback();
				}
				else
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="DELETE";
					$ls_descripcion="Eliminó el Item ".$as_codite." Asociado a la Seccion ".$as_codsubgru.
									" Asociado al SubGrupo ".$as_codsubgru." y al Grupo ".$as_codgru;
					$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////			
					$this->io_sql->commit();
				}
			}
			else
			{
				$lb_valido=false;
				$this->io_msg->message("Codigo no existe..");
			}
		 }
		 else
		 {
			$this->io_msg->message("Existe relacion con este registro..");
		 }	
		return $lb_valido;
	}//fin de function uf_saf_delete_item
//----------------------------------------------------------------------------------------------------------------------------------
	
//----------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_check_relaciones($as_tabla,$as_campo,$as_where)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_saf_check_relaciones
		//	     Access: public
		//	  Arguments: as_tabla   // nombre de la tabla donde se va a buscar
		//               as_campo   // nombre del campo a buscar
		//               as_where   // condicion
		//	    Returns: $lb_valido-----> true: operacion exitosa false: operacion no exitosa
		//	Description: Esta funcion chequea si existe un elemento en una tabla que esta relacionada
		////////////////////////////////////////////////////////////////////////////////////////////////////		
		$lb_valido=false;
		$ls_sql="SELECT ".$as_campo." FROM ".$as_tabla." WHERE ".$as_where;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->grupo MÉTODO->uf_saf_check_relaciones ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
			   $lb_valido=true;
			}	
			$this->io_sql->free_result($rs_data);
		}	
	   return $lb_valido;
    }// fin de function uf_saf_check_relaciones
//----------------------------------------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_generar_codigo($as_codgru,$as_codsubgru,$as_tabla,$as_columna)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_saf_generar_codigo
		//	Access:    public
		//	Arguments:
		//  as_codgru    // codigo de grupo
		//  as_codsubgru // codigo de subgrupo
		//  as_codsec    // codigo de seccion
		//  as_tabla    // nombre de la tabla a generar el codigo
		//  as_columna    // nombre de la columna donde estan los codigos
		//	Returns:		$lb_valido-----> true: operacion exitosa false: operacion no exitosa
		//	Description:  Esta funcion busca cual es el ultimo codigo que se ha generado en determinada
		//					tabla  y le suma uno mas para generar un codigo nuevo
		//              
		//////////////////////////////////////////////////////////////////////////////////////////////////////		
		$ls_where="";
		if(($as_codgru!="")&&($as_codsubgru==""))
		{
			$ls_where= "WHERE codgru = '".$as_codgru."'";
		}
		
		if(($as_codgru=="")&&($as_codsubgru!=""))
		{
			$ls_where= "WHERE codgru = '".$as_codsubgru."'";
		}
		
		if(($as_codgru!="") && ($as_codsubgru!=""))
		{
			$ls_where= "WHERE codgru = '".$as_codgru."' AND codsubgru = '".$as_codsubgru."'";
		}
		$ls_sql="SELECT ".$as_columna." FROM ".$as_tabla." ".$ls_where." ORDER BY ".$as_columna." DESC";
		$rs_funciondb=$this->io_sql->select($ls_sql);
		if ($row=$this->io_sql->fetch_row($rs_funciondb))
		{ 
		   $ls_codigo=$row[$as_columna];
		   settype($ls_codigo,'int');                                          // Asigna el tipo a la variable.
		   $ls_codigo = $ls_codigo + 1;                                           // Le sumo uno al entero.
		   settype($ls_codigo,'string');                                       // Lo convierto a varchar nuevamente.
		}   
		else
		{
		   $ls_codigo="1";
		}
		return $ls_codigo;
	}
//----------------------------------------------------------------------------------------------------------------------------------
}
?>
