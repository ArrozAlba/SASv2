<?php
require_once("../shared/class_folder/class_sql.php");
class sigesp_sim_c_sim_destinoproducto
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sim_c_sim_destinoproducto()
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
	
	function uf_sim_select_destinoproducto($as_coddespro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_tipoarticulo
		//         Access: public (sigesp_sim_d_tipoarticulo)
		//      Argumento: $as_coddespro    // codigo de tipo de articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un tipo de articulo en la tabla de  sim_destinoproducto
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sim_destinoproducto  ".
				  " WHERE coddespro='".$as_coddespro."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->destinoproducto MÉTODO->uf_sim_select_destinoproducto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_sim_select_tipoarticulo

	function  uf_sim_insert_destinoproducto($as_coddespro,$as_dendespro,$as_obsdespro,$ai_statusven,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_insert_tipoarticulo
		//         Access: public (sigesp_sim_d_tipoarticulo)
		//      Argumento: $as_coddespro   // codigo de tipo de articulo
	    //                 $as_dendespro   // denominacion de tipo de articulo
	    //                 $as_obsdespro   // observacion de tipo de articulo
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un tipo de articulo en la tabla de sim_destinoproducto
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO sim_destinoproducto (coddespro, dendespro, obsdespro,estatus) ".
					" VALUES('".$as_coddespro."','".$as_dendespro."','".$as_obsdespro."','1')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->destinoproducto MÉTODO->uf_sim_insert_destinoproducto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Motivo de Traslado en Orden de Entrega ".$as_coddespro;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_sim_insert_tipoarticulo

	function uf_sim_update_destinoproducto($as_coddespro,$as_dendespro,$as_obsdespro,$ai_statusven,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_tipoarticulo
		//         Access: public (sigesp_sim_d_tipoarticulo)
		//      Argumento: $as_coddespro   // codigo de tipo de articulo
	    //                 $as_dendespro   // denominacion de tipo de articulo
	    //                 $as_obsdespro   // observacion de tipo de articulo
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un tipo de articulo en la tabla de sim_destinoproducto
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE sim_destinoproducto SET dendespro='". $as_dendespro ."',obsdespro='". $as_obsdespro ."' ". 
				   " WHERE coddespro='" . $as_coddespro ."' ";
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->destinoproducto MÉTODO->uf_sim_update_destinoproducto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el Destino del Producto Devuelto ".$as_coddespro;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_sim_update_tipoarticulo

	function uf_sim_delete_destinoproducto($as_coddespro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_delete_tipoarticulo
		//         Access: public (sigesp_sim_d_tipoarticulo)
		//      Argumento: $as_coddespro   // codigo de tipo de articulo
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un tipo de articulo en la tabla de sim_destinoproducto verificando que este no este siendo
		//				   utilizado por ningun articulo.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe= $this->uf_sim_select_destino_prod($as_coddespro);
		if($lb_existe)
		{
			$this->io_msg->message("El Destino del Producto Devuelto tiene asociados registro");		
			$lb_valido=false;
		}
		else
		{
			$this->io_sql->begin_transaction();	
			$ls_sql = " update sim_destinoproducto              ".
 			          " set    estatus= '0'                         ".
					  " where  coddespro= '".$as_coddespro. "'"; 
					  
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->destinoproducto MÉTODO->uf_sim_delete_destinoproducto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Destino del Producto Devuelto ".$as_coddespro;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		return $lb_valido;
	} // end function uf_sim_delete_tipoarticulo
	
	function uf_sim_select_destino_prod($as_coddespro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_tipoarticuloarticulo
		//         Access: private
		//      Argumento: $as_coddespro   // codigo de tipo de articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existen articulos que estan utilizando el tipo de articulo seleccionado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sim_orden_entrega  ".
				  " WHERE coddespro='".$as_coddespro."'" ;

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->destinoproducto MÉTODO->uf_sim_select_tipoarticuloarticulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end function uf_sim_select_tipoarticuloarticulo

}// end   class sigesp_sim_c_tipoarticulo
?>
