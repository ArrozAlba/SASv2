<?php
require_once("../shared/class_folder/class_sql.php");
class sigesp_sim_c_mot_ord_entrega
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sim_c_mot_ord_entrega()
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
	
	function uf_sim_select_mot_ord_entrega($as_codmotordent)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_tipoarticulo
		//         Access: public (sigesp_sim_d_tipoarticulo)
		//      Argumento: $as_codmotordent    // codigo de tipo de articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un tipo de articulo en la tabla de  sim_motivo_ordenentrega
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sim_motivo_ordenentrega  ".
				  " WHERE codmotordent='".$as_codmotordent."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->mot_ord_entrega MÉTODO->uf_sim_select_mot_ord_entrega ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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

	function  uf_sim_insert_mot_ord_entrega($as_codmotordent,$as_denmotordent,$as_obsmotordent,$ai_statusven,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_insert_tipoarticulo
		//         Access: public (sigesp_sim_d_tipoarticulo)
		//      Argumento: $as_codmotordent   // codigo de tipo de articulo
	    //                 $as_denmotordent   // denominacion de tipo de articulo
	    //                 $as_obsmotordent   // observacion de tipo de articulo
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un tipo de articulo en la tabla de sim_motivo_ordenentrega
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO sim_motivo_ordenentrega (codmotordent, denmotordent, obsmotordent,objven,estatus) ".
					" VALUES('".$as_codmotordent."','".$as_denmotordent."','".$as_obsmotordent."','".$ai_statusven."','1')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->mot_ord_entrega MÉTODO->uf_sim_insert_mot_ord_entrega ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Motivo de Traslado en Orden de Entrega ".$as_codmotordent;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_sim_insert_tipoarticulo

	function uf_sim_update_mot_ord_entrega($as_codmotordent,$as_denmotordent,$as_obsmotordent,$ai_statusven,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_tipoarticulo
		//         Access: public (sigesp_sim_d_tipoarticulo)
		//      Argumento: $as_codmotordent   // codigo de tipo de articulo
	    //                 $as_denmotordent   // denominacion de tipo de articulo
	    //                 $as_obsmotordent   // observacion de tipo de articulo
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un tipo de articulo en la tabla de sim_motivo_ordenentrega
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE sim_motivo_ordenentrega SET   denmotordent='". $as_denmotordent ."',obsmotordent='". $as_obsmotordent ."' ,objven='". $ai_statusven ."'". 
				   " WHERE codmotordent='" . $as_codmotordent ."' ";
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->mot_ord_entrega MÉTODO->uf_sim_update_mot_ord_entrega ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el Motivo de Traslado en Orden de Entrega ".$as_codmotordent;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_sim_update_tipoarticulo

	function uf_sim_delete_mot_ord_entrega($as_codmotordent,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_delete_tipoarticulo
		//         Access: public (sigesp_sim_d_tipoarticulo)
		//      Argumento: $as_codmotordent   // codigo de tipo de articulo
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un tipo de articulo en la tabla de sim_motivo_ordenentrega verificando que este no este siendo
		//				   utilizado por ningun articulo.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe= $this->uf_sim_select_mot_ord_entrega_orden($as_codmotordent);
		if($lb_existe)
		{
			$this->io_msg->message("El Motivo de Traslado en Orden de Entrega tiene asociados registro");		
			$lb_valido=false;
		}
		else
		{
			$this->io_sql->begin_transaction();	
			$ls_sql = " update sim_motivo_ordenentrega              ".
 			          " set    estatus= '0'                         ".
					  " where  codmotordent= '".$as_codmotordent. "'"; 
					  
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->mot_ord_entrega MÉTODO->uf_sim_delete_mot_ord_entrega ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Motivo de Traslado en Orden de Entrega".$as_codmotordent;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		return $lb_valido;
	} // end function uf_sim_delete_tipoarticulo
	
	function uf_sim_select_mot_ord_entrega_orden($as_codmotordent)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_tipoarticuloarticulo
		//         Access: private
		//      Argumento: $as_codmotordent   // codigo de tipo de articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existen articulos que estan utilizando el tipo de articulo seleccionado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sim_orden_entrega  ".
				  " WHERE codmotordent='".$as_codmotordent."'" ;

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->mot_ord_entrega MÉTODO->uf_sim_select_tipoarticuloarticulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
