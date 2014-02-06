<?php
require_once("../shared/class_folder/class_sql.php");
class sigesp_siv_c_tipoarticulo
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_siv_c_tipoarticulo()
	{
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_msg=new class_mensajes();
		$this->io_funcion = new class_funciones();
		$this->dat_emp=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
	}
	
	function uf_siv_select_tipoarticulo($as_codtipart)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_tipoarticulo
		//         Access: public (sigesp_siv_d_tipoarticulo)
		//      Argumento: $as_codtipart    // codigo de tipo de articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un tipo de articulo en la tabla de  siv_tipoarticulo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM siv_tipoarticulo  ".
				  " WHERE codtipart='".$as_codtipart."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tipoarticulo MÉTODO->uf_siv_select_tipoarticulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	}  //  end function uf_siv_select_tipoarticulo

	function  uf_siv_insert_tipoarticulo($as_codtipart,$as_dentipart,$as_obstipart,$as_clasif, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_insert_tipoarticulo
		//         Access: public (sigesp_siv_d_tipoarticulo)
		//      Argumento: $as_codtipart   // codigo de tipo de articulo
	    //                 $as_dentipart   // denominacion de tipo de articulo
	    //                 $as_obstipart   // observacion de tipo de articulo
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un tipo de articulo en la tabla de siv_tipoarticulo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO siv_tipoarticulo (codtipart, dentipart, obstipart, tipart) ".
					" VALUES('".$as_codtipart."','".$as_dentipart."','".$as_obstipart."','".$as_clasif."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->tipoarticulo MÉTODO->uf_siv_insert_tipoarticulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Tipo de Articulo ".$as_codtipart;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_siv_insert_tipoarticulo

	function uf_siv_update_tipoarticulo($as_codtipart,$as_dentipart,$as_obstipart, $as_clasif, $aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_update_tipoarticulo
		//         Access: public (sigesp_siv_d_tipoarticulo)
		//      Argumento: $as_codtipart   // codigo de tipo de articulo
	    //                 $as_dentipart   // denominacion de tipo de articulo
	    //                 $as_obstipart   // observacion de tipo de articulo
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un tipo de articulo en la tabla de siv_tipoarticulo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = " UPDATE siv_tipoarticulo ".
		           "    SET   dentipart='".$as_dentipart."', ".
				   "          obstipart='".$as_obstipart."', ". 
				   "             tipart='".$as_clasif."' ". 
				   " WHERE codtipart='" .$as_codtipart."' ";
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->tipoarticulo MÉTODO->uf_siv_update_tipoarticulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el Tipo de Articulo ".$as_codtipart;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_siv_update_tipoarticulo

	function uf_siv_delete_tipoarticulo($as_codtipart,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_delete_tipoarticulo
		//         Access: public (sigesp_siv_d_tipoarticulo)
		//      Argumento: $as_codtipart   // codigo de tipo de articulo
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un tipo de articulo en la tabla de siv_tipoarticulo verificando que este no este siendo
		//				   utilizado por ningun articulo.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe= $this->uf_siv_select_tipoarticuloarticulo($as_codtipart);
		if($lb_existe)
		{
			$this->io_msg->message("El tipo de articulo tiene articulos asociados");		
			$lb_valido=false;
		}
		else
		{
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM siv_tipoarticulo".
						 " WHERE codtipart= '".$as_codtipart. "'"; 
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->tipoarticulo MÉTODO->uf_siv_delete_tipoarticulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Tipo de Articulo ".$as_codtipart;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		return $lb_valido;
	} // end function uf_siv_delete_tipoarticulo
	
	function uf_siv_select_tipoarticuloarticulo($as_codtipart)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_tipoarticuloarticulo
		//         Access: private
		//      Argumento: $as_codtipart   // codigo de tipo de articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existen articulos que estan utilizando el tipo de articulo seleccionado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM siv_articulo  ".
				  " WHERE codtipart='".$as_codtipart."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tipoarticulo MÉTODO->uf_siv_select_tipoarticuloarticulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function uf_siv_select_tipoarticuloarticulo

}// end   class sigesp_siv_c_tipoarticulo
?>
