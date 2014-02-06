<?php 
class sigesp_sss_c_grupos
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sss_c_grupos()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$this->seguridad= new sigesp_c_seguridad;
		$this->dat_emp=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->io_funcion = new class_funciones();
	}

	function  uf_sss_select_grupos($as_codemp,$as_codgru)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_grupos
		//         Access: public (sigesp_sss_d_grupos)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_nomgru    // nombre del grupo
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de verificar la existencia de un grupo en la tabla sss_grupos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT codgru FROM sss_grupos  ".
				  " WHERE codgru='".$as_codgru."'".
				  " AND codemp = '".$as_codemp."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->grupos MÉTODO->uf_sss_select_grupos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end function  uf_sss_select_grupos

	function  uf_sss_insert_grupo($as_codemp,$as_codgru,$as_nomgru,$as_nota,$aa_seguridad )
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_grupo
		//         Access: public (sigesp_sss_d_grupos)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_nomgru    // nombre del grupo
		//  			   $as_nota      // descripcion de grupo
		//  			   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de insertar un grupo en la tabla de  sss_grupos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "INSERT INTO sss_grupos ( codemp,codgru,nomgru,nota ) ".
					" VALUES('".$as_codemp."','".$as_codgru."','".$as_nomgru."','".$as_nota."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->grupos MÉTODO->uf_sss_insert_grupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Grupo ".$as_nomgru." Asociado a la empresa ".$as_codemp." y al codigo de grupo ".$as_codgru;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if ($lb_variable)
			{
				$lb_valido=true;
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
			}	
		}
		return $lb_valido;
	} // end  function  uf_sss_insert_grupo

	function uf_sss_delete_grupo($as_codemp,$as_codgru,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_delete_grupo
		//         Access: public (sigesp_sss_d_grupos)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_nomgru    // nombre del grupo
		//  			   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de eliminar un grupo en la tabla de  sss_grupos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_existe=$this->uf_sss_select_usuariosgrupos($as_codemp,$as_codgru);
		if($lb_existe)
		{
			$this->io_msg->message("El grupo tiene usuarios asociados");
		}
		else
		{
			$ls_sql = " DELETE FROM sss_grupos".
					   " WHERE codemp= '".$as_codemp. "'".
					   " AND codgru= '".$as_codgru."'"; 
			$this->io_sql->begin_transaction();	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->grupos MÉTODO->uf_sss_delete_grupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el codigo de Grupo ".$as_codgru." Asociado a la empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				if ($lb_variable)
				{
					$lb_valido=true;
					$this->io_sql->commit();
				}
				else
				{
					$this->io_sql->rollback();
				}	
			}
		}
		return $lb_valido;
	}  // end  function uf_sss_delete_grupo

	function uf_sss_update_grupo($as_codemp,$as_codgru,$as_nomgru,$as_nota,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_update_grupo
		//         Access: public (sigesp_sss_d_grupos)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_nomgru    // nombre del grupo
		//  			   $as_nota      // descripcion de grupo
		//  			   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de modificar un grupo en la tabla de  sss_grupos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=false;
		 $ls_sql = "UPDATE sss_grupos SET   nota='". $as_nota ."'".
				   " WHERE codemp='" . $as_codemp ."'".
				   " AND codgru='" . $as_codgru ."'";
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->grupos MÉTODO->uf_sss_update_grupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Grupo ".$as_nomgru." Asociado a la empresa ".$as_codemp." y al codigo de grupo ".$as_codgru;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if ($lb_variable)
			{
				$lb_valido=true;
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
			}	
		}
	  return $lb_valido;
	}  // end  function uf_sss_update_grupo
	
	function  uf_sss_select_usuariosgrupos($as_codemp,$as_nomgru)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_usuariosgrupos
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_nomgru    // nombre del grupo
		//	      Returns: Retorna un Booleano
		//    Description: Función que verifica si existen usuarios asociados a un grupo en la tabla sss_usuarios_en_grupo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM sss_usuarios_en_grupos  ".
					" WHERE nomgru='".$as_nomgru."'".
					" AND codemp = '".$as_codemp."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->grupos MÉTODO->uf_sss_select_usuariosgrupos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} //  end  function  uf_sss_select_usuariosgrupos
	
}//fin de la class sigesp_sss_c_actualizar_ventana
?>
