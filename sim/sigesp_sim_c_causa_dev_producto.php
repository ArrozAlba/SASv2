<?php
require_once("../shared/class_folder/class_sql.php");
class sigesp_sim_c_causa_dev_producto
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	function sigesp_sim_c_causa_dev_producto()
	{
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->io_msg=    new class_mensajes();
		$this->io_funcion=new class_funciones();
	}
	
	function uf_sim_select_causadevolucion($as_codcaudev)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_causadevolucion
		//         Access: public (sigesp_sim_d_causa_dev_producto)
		//      Argumento: $as_codcaudev    // codigo de causa de devolucion de producto
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de una causa de devolucion de producto en la tabla de  sigesp_causadevolucionproducto
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 07/09/2010							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sigesp_causadevolucionproducto  ".
				  " WHERE codcaudev='".$as_codcaudev."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->causa_dev_producto MÉTODO->uf_sim_select_causadevolucion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end function uf_sim_select_causadevolucion

	function  uf_sim_insert_causadevolucion($as_codcaudev,$as_dencau,$as_obscausadev,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_insert_causadevolucion
		//         Access: public (sigesp_sim_d_causa_dev_producto)
		//      Argumento: $as_codcaudev    // codigo de causa de devolucion de producto
		//  			   $as_dencau    // descripcion de causa de devolucion de producto
		//  			   $as_obscausadev    // observacion de causa de devolucion de producto
		//  			   $aa_seguridad    // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta una causa de devolucion de producto en la tabla de  sigesp_causadevolucionproducto
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 07/09/2010							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "INSERT INTO sigesp_causadevolucionproducto (codemp,codcaudev, dencau, obscau,estatus) ".
					" VALUES('".$_SESSION["la_empresa"]["codemp"]."','".$as_codcaudev."','".$as_dencau."','".$as_obscausadev."','t')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->causa_dev_producto MÉTODO->uf_sim_insert_causadevolucion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la causa de devolucion ".$as_codcaudev;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_sim_insert_causadevolucion

	function uf_sim_update_causadevolucion($as_codcaudev,$as_dencau,$as_obscausadev,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_causadevolucion
		//         Access: public (sigesp_sim_d_causa_dev_producto)
		//      Argumento: $as_codcaudev    // codigo de cause de devolucion de producto
		//  			   $as_dencau    // descripcion de causa de devolucion de producto
		//  			   $as_obscausadev    // observacion de causa de devolucion de producto
		//  			   $aa_seguridad    // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica una causa de devolucion de producto en la tabla de  sigesp_causadevolucionproducto
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 07/09/2010							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE sigesp_causadevolucionproducto SET dencau='".$as_dencau."' ,obscau='".$as_obscausadev."' ".
				   " WHERE codcaudev='".$as_codcaudev."' ";
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->causa_dev_producto MÉTODO->uf_sim_update_causadevolucion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó la causa de devolucion".$as_codcaudev;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end function uf_sim_update_causadevolucion

	function uf_sim_delete_causadevolucion($as_codcaudev,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_delete_causadevolucion
		//         Access: public (sigesp_sim_d_causa_dev_producto)
		//      Argumento: $as_codcaudev    // codigo de causa de devolucion de producto
		//				   $aa_seguridad    // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina logicamente la causa de devolucion de producto en la tabla de  sim_causadevolcuion
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 07/09/2010							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		/*$lb_existe= $this->uf_sim_select_articulounidad($as_codunimed);
		if($lb_existe)
		{
			$this->io_msg->message("La Unidad de Medida tiene articulos asociados");		
			$lb_valido=false;
		}
		else
		{*/
			$this->io_sql->begin_transaction();	
			$ls_sql = " UPDATE sigesp_causadevolucionproducto ".
					  "    SET estatus='f' WHERE codcaudev= '".$as_codcaudev."'"; 
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->causa_dev_producto MÉTODO->uf_sim_update_causadevolucion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la causa de devolucion de producto ".$as_codcaudev;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		//}			
		return $lb_valido;
	}  // end function uf_sim_delete_unidadmedida

	function uf_sim_select_articulounidad($as_codunimed)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_delete_unidadmedida
		//         Access: private
		//      Argumento: $as_codunimed    // codigo de unidad de medida
		//	      Returns: Retorna un Booleano
		//    Description: Funcion verifica si existen articulos que esten utilizando una determinada unidad de medida
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/02/2006							Fecha Última Modificación : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sim_articulo  ".
				  " WHERE codunimed='".$as_codunimed."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->unidadmedida MÉTODO->uf_sim_select_articulounidad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end function uf_sim_select_articulounidad

} // end  class sigesp_sim_c_unidadmedida
?>
