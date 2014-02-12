<?php
class sigesp_siv_c_articuloxalmacen
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_siv_c_articuloxalmacen()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones_db.php");
		require_once("../shared/class_folder/class_funciones.php");
		
		$in=              new sigesp_include();
		$this->con=       $in->uf_conectar();
		$this->io_sql=    new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->fun=       new class_funciones_db($this->con);
		$this->io_msg=new class_mensajes();
		$this->io_funcion = new class_funciones();
	}

	function uf_siv_select_articuloxalmacen($as_codemp,$as_codart,$as_codalm)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_articuloxalmacen
		//         Access: public 
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_codart // codigo de articulo
		//                 $as_codalm //codigo de almacen
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que verifica si existe un articulo en un determinado almacen en la tabla de  siv_articuloalmacen
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM siv_articuloalmacen  ".
				  " WHERE codemp='".$as_codemp."' ".
				  " AND codart='".$as_codart."' ".
				  " AND codalm='".$as_codalm."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articuloxalmacen MÉTODO->uf_siv_select_articuloxalmacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end  function uf_siv_select_articuloxalmacen


	function uf_siv_insert_articuloxalmacen($as_codemp,$as_codart,$as_codalm,$as_existencia,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_insert_articuloxalmacen
		//         Access: public 
		//      Argumento: $as_codemp      //codigo de empresa 
		//                 $as_codart      // codigo de articulo
		//                 $as_codalm      //codigo de almacen
		//                 $as_existencia  // codigo del usuario
		//                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que registra cierta cantidad de un articulo en determinado almacen en la tabla siv_articuloalmacen
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO siv_articuloalmacen (codemp, codart, codalm, existencia)".
				" VALUES ('".$as_codemp."','".$as_codart."','".$as_codalm."','".$as_existencia."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->articuloxalmacen MÉTODO->uf_siv_insert_articuloxalmacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
/*				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Articulo ".$as_codart." en el Almacén ".$as_codalm." de la Empresa ".$as_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);*/
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end function uf_siv_insert_articuloxalmacen

	function uf_siv_update_articuloxalmacen($as_codemp,$as_codart,$as_codalm,$as_existencia)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_update_articuloxalmacen
		//         Access: public 
		//      Argumento: $as_codemp      //codigo de empresa 
		//                 $as_codart      // codigo de articulo
		//                 $as_codalm      //codigo de almacen
		//                 $as_existencia  // codigo del usuario
		//                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que actualiza cierta cantidad de articulos en un almacen determinado en la tabla siv_articuloalmacen
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE siv_articuloalmacen SET   existencia='".$as_existencia."' ".
					" WHERE codemp='".$as_codemp."'".
					" AND codart='".$ad_codart."'".
					" AND codalm='".$ad_codalm."'";
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->articuloxalmacen MÉTODO->uf_siv_update_articuloxalmacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();

		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
/*			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el Movimiento ".$as_nummov." de Fecha ".$ad_fecmov;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);*/
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}

 
	  return $lb_valido;

	} 

	function uf_siv_chequear_articuloxalmacen($as_codemp,$as_codart,$as_codalm,$ai_cantidad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_chequear_articuloxalmacen
		//         Access: public 
		//      Argumento: $as_codemp      //codigo de empresa 
		//                 $as_codart      // codigo de articulo
		//                 $as_codalm      //codigo de almacen
		//                 $ai_cantidad   // cantidad de articulos
		//                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que verifica que exista la cantidad suficiente de articulos en un almacen deteriminado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM siv_articuloalmacen  ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND codart='".$as_codart."'".
				  " AND codalm='".$as_codalm."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articuloxalmacen MÉTODO->uf_siv_chequear_articuloxalmacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_existencia=$row["existencia"]; 
				if ($li_existencia >= $ai_cantidad)
				{
					$lb_valido=true;
				} 
				else
				{
					$this->io_msg->message("No existen suficientes articulos en el almacen seleccionado");
					$lb_valido=false;
				}
			}
			else
			{
				$this->io_msg->message("No existen suficientes articulos en el almacen seleccionado");
			}
		}
		return $lb_valido;
	}
	
	function uf_siv_disminuir_articuloxalmacen($as_codemp,$as_codart,$as_codalm,$ai_cantidad,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_disminuir_articuloxalmacen
		//         Access: public 
		//      Argumento: $as_codemp      //codigo de empresa 
		//                 $as_codart      // codigo de articulo
		//                 $as_codalm      //codigo de almacen
		//                 $ai_cantidad    // cantidad de articulos
		//                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que disminuye la cantidad de un articulo en un almacen determinado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 31/08/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $lb_valido=$this->uf_siv_chequear_articuloxalmacen($as_codemp,$as_codart,$as_codalm,$ai_cantidad);
	 if($lb_valido)
	 {
		 $ls_sql =  "UPDATE siv_articuloalmacen".
		 			"   SET existencia= (existencia - '".$ai_cantidad."') ".
					" WHERE codemp='".$as_codemp."'".
					"   AND codart='".$as_codart."'".
					"   AND codalm='".$as_codalm."'";
			$li_row = $this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->articuloxalmacen MÉTODO->uf_siv_disminuir_articuloxalmacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
	
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Disminuyó ".$ai_cantidad." Articulos ".$as_codart." del Almacén ".$as_codalm." de la Empresa ".$as_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}
	  return $lb_valido;
	}

	function uf_siv_sumar_articuloxalmacen($as_codemp,$as_codart,$as_codalm,$ai_cantidad,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_sumar_articuloxalmacen
		//         Access: public 
		//      Argumento: $as_codemp      //codigo de empresa 
		//                 $as_codart      // codigo de articulo
		//                 $as_codalm      //codigo de almacen
		//                 $ai_cantidad    // cantidad de articulos
		//                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que aumenta la cantidad de un articulo en un almacen determinado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql= "UPDATE siv_articuloalmacen ".
		 		  "   SET existencia= (existencia + '".$ai_cantidad."') ".
				  " WHERE codemp='".$as_codemp."' ".
				  "   AND codart='".$as_codart."' ".
				  "   AND codalm='".$as_codalm."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->articuloxalmacen MÉTODO->uf_siv_sumar_articuloxalmacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();

		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Aumentó ".$ai_cantidad." Articulos ".$as_codart." del Almacén ".$as_codalm." de la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	    return $lb_valido;
	}

	function uf_siv_aumentar_articuloxalmacen($as_codemp,$as_codart,$as_codalm,$ai_cantidad,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_aumentar_articuloxalmacen
		//         Access: public 
		//      Argumento: $as_codemp      //codigo de empresa 
		//                 $as_codart      // codigo de articulo
		//                 $as_codalm      //codigo de almacen
		//                 $ai_cantidad    // cantidad de articulos
		//                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que deacuerdo a los resultados de una busqueda (select), inserta o actualiza cierta cantidad de
		//				    articulos en un almacen determinado en la tabla de  siv_articuloalmacen
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(!($this->uf_siv_select_articuloxalmacen($as_codemp,$as_codart,$as_codalm)))
		{
			$lb_valido=$this->uf_siv_insert_articuloxalmacen($as_codemp,$as_codart,$as_codalm,$ai_cantidad,$aa_seguridad);
		}
		else
		{
			$lb_valido=$this->uf_siv_sumar_articuloxalmacen($as_codemp,$as_codart,$as_codalm,$ai_cantidad,$aa_seguridad);
		}
		return $lb_valido;
	}

	function uf_siv_select_articulo($as_codemp,$as_codart)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_articulo
		//         Access: public 
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_codart // codigo de articulo
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que busca un articulo en la tabla de  siv_articulo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM siv_articulo  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND codart='".$as_codart."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articuloxalmacen MÉTODO->uf_siv_select_articulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}

	function uf_siv_disminuir_articulo($as_codemp,$as_codart,$ai_cantidad,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_disminuir_articulo
		//         Access: public 
		//      Argumento: $as_codemp     //codigo de empresa 
		//                 $as_codart     // codigo de articulo
		//                 $as_codalm     // codigo de almacen
		//                 $as_cantidad   // cantidad de articulos
		//                 $aa_seguridad  // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que disminuye la cantidad de un articulo en un almacen determinado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe=false;
		$lb_existe=uf_siv_select_articulo($as_codemp,$as_codart);
		if($lb_existe)
		{
			 $ls_sql= "UPDATE siv_articulo".
			 		  "   SET exiart= (exiart - '". $ai_cantidad ."') ".
					  " WHERE codemp='" . $as_codemp ."'".
					  "   AND codart='" . $as_codart ."'";
				$li_row = $this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$this->io_msg->message("CLASE->articuloxalmacen MÉTODO->uf_siv_disminuir_articulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;
				}
				else
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		/*			$ls_evento="UPDATE";
					$ls_descripcion ="Modificó el Movimiento ".$as_nummov." de Fecha ".$ad_fecmov;
					$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);*/
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
		}
	    return $lb_valido;
	}

	function uf_siv_update_total_articulo($as_codemp,$as_codart,$ai_cantidad,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_update_total_articulo
		//         Access: public 
		//      Argumento: $as_codemp     //codigo de empresa 
		//                 $as_codart     // codigo de articulo
		//                 $as_codalm     // codigo de almacen
		//                 $as_cantidad   // cantidad de articulos
		//                 $aa_seguridad  // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que actualiza la cantidad de un articulo en un almacen determinado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe=$this->uf_siv_select_articulo($as_codemp,$as_codart);
		if($lb_existe)
		{
			 $ls_sql= "UPDATE siv_articulo ".
			 		  "   SET exiart='".$ai_cantidad."' ".
					  " WHERE codemp='".$as_codemp."' ".
					  "   AND codart='".$as_codart."'";
				$li_row = $this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$this->io_msg->message("CLASE->articuloxalmacen MÉTODO->uf_siv_update_total_articulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;
				}
				else
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
/*					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizó la cantidad total del articulo ".$as_codart." de la Empresa ".$as_codemp." en ".$ai_cantidad;
					$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);*/
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
		} 
		return $lb_valido;
	}

	function uf_siv_actualizar_cantidad_articulos($as_codemp,$as_codart,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_actualizar_cantidad_articulos
		//         Access: public 
		//      Argumento: $as_codemp     //codigo de empresa 
		//                 $as_codart     // codigo de articulo
		//                 $aa_seguridad  // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que calcula la cantidad total de un articulo entre todos los almacenes para luego actualizar dicha cantidad
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_exec=-1;
		$li_totart=0;
		$ls_sql = "SELECT * FROM siv_articuloalmacen  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND codart='".$as_codart."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		while($row=$this->io_sql->fetch_row($rs_data))
		{
			$li_cantalm=$row["existencia"];
			$li_totart=$li_totart + $li_cantalm;
		}
		$lb_valido=$this->uf_siv_update_total_articulo($as_codemp,$as_codart,$li_totart,$aa_seguridad);		
		return $lb_valido;
	}

	function uf_siv_delete_articuloxalmacen($as_codemp,$as_codart,$as_codalm)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_delete_articuloxalmacen
		//         Access: public 
		//      Argumento: $as_codemp     //codigo de empresa 
		//                 $as_codart     // codigo de articulo
		//                 $as_codalm     // codigo de almacen
		//                 $aa_seguridad  // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que elimina un registro de cantidad de articulos en un almacen
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "DELETE FROM siv_articuloxalmacen".
				 " WHERE codemp= '".$as_codemp. "'".
				 "   AND codart= '".$as_codart. "'".
				 "   AND codalm= '".$as_codalm. "'";
	
			$this->io_sql->begin_transaction();	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->articuloxalmacen MÉTODO->uf_siv_delete_articuloxalmacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
/*				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Movimiento ".$as_nummov." de Fecha ".$ad_fecmov;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);*/
				//////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		return $lb_valido;
	} 
} 
?>
