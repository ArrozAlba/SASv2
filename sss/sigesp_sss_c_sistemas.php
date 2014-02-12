<?php 
class sigesp_sss_c_sistemas
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sss_c_sistemas()
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

	function  uf_sss_select_sistema($as_codsis)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_grupos
		//         Access: public (sigesp_sss_d_sistemas)
		//      Argumento: $as_codsis    // codigo de sistema
		//    Description: Función que se encarga de verificar la existencia de un sistema en la tabla sss_sistemas
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM sss_sistemas  ".
					" WHERE codsis='".$as_codsis."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->sistemas MÉTODO->uf_sss_select_sistema ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end  function  uf_sss_select_sistema

	function  uf_sss_insert_sistema($as_codsis, $as_nomsis,$aa_seguridad )
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_sistema
		//         Access: public (sigesp_sss_d_sistemas)
		//      Argumento: $as_codsis    // codigo de sistema
		//      		   $as_nomsis    // nombre de sistema
		//      		   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de insertar un sistema en la tabla sss_sistemas
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "INSERT INTO sss_sistemas ( codsis,nomsis ) ".
					" VALUES('".$as_codsis."','".$as_nomsis."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->sistemas MÉTODO->uf_sss_insert_sistema ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Sistema ".$as_codsis;
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
	} // end  function  uf_sss_insert_sistema

	function uf_sss_delete_sistema($as_codsis,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_delete_sistema
		//         Access: public (sigesp_sss_d_sistemas)
		//      Argumento: $as_codsis    // codigo de sistema
		//      		   $as_nomsis    // nombre de sistema
		//      		   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de eliminar un sistema en la tabla sss_sistemas verificando si este tiene ventanas 
		//				   asociadas
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_existe=$this->uf_sss_select_sistemaventana($as_codsis);
		if($lb_existe)
		{
			$this->io_msg->message("El Sistema tiene ventanas asociadas");
		}
		else
		{
			$ls_sql = " DELETE FROM sss_sistemas".
					  " WHERE codsis= '".$as_codsis. "' "; 
			$this->io_sql->begin_transaction();	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->sistemas MÉTODO->uf_sss_delete_sistema ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Sistema ".$as_codsis;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				if($lb_variable)
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
	} //end  function uf_sss_delete_sistema
	
	function uf_sss_update_sistema($as_codsis,$as_nomsis,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_update_sistema
		//         Access: public (sigesp_sss_d_sistemas)
		//      Argumento: $as_codsis    // codigo de sistema
		//      		   $as_nomsis    // nombre de sistema
		//      		   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de modificar un sistema en la tabla sss_sistemas 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=false;
		 $ls_sql = "UPDATE sss_sistemas SET nomsis='". $as_nomsis ."'".
					" WHERE codsis='" .$as_codsis ."' ";
        $this->io_sql->begin_transaction();
		$li_row= $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->sistemas MÉTODO->uf_sss_update_sistema ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Sistema ".$as_codsis;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_variable)
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
	}	// end  function uf_sss_update_sistema

	function  uf_sss_select_sistemaventana($as_codsis)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_sistemaventana
		//         Access: public (sigesp_sss_d_sistemas)
		//      Argumento: $as_codsis    // codigo de sistema
		//	      Returns: Retorna un Booleano
		//    Description: Función que verifica si existe una ventana asociada a un sistema en la tabla sss_sistemas_ventanas
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM sss_sistemas_ventanas  ".
					" WHERE codsis='".$as_codsis."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->sistemas MÉTODO->uf_sss_select_sistemaventana ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end  function  uf_sss_select_sistemaventana

}//end  class sigesp_sss_c_sistemas

?>
