<?php
require_once("../shared/class_folder/class_sql.php");
class sigesp_siv_c_almacen
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_siv_c_almacen()
	{
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$in=new sigesp_include();
		$con=$in->uf_conectar();
		$this->io_sql=      new class_sql($con);
		$this->seguridad=   new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();

	}
	
	function uf_siv_select_almacen($as_codemp,$as_codalm)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_almacen
		//         Access: public (sigesp_siv_d_almacen)
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_codalm //codigo de almacen
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que verifica si existe un determinado almacen en la tabla de  siv_almacen.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT codalm FROM siv_almacen  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND codalm='".$as_codalm."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->almacen MÉTODO->uf_siv_select_almacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
		}
		return $lb_valido;
	}  // end function uf_siv_select_almacen($as_codemp,$as_codalm)

	function  uf_siv_insert_almacen($as_codemp,$as_codalm,$as_nomfisalm,$as_desalm,$as_telalm,$as_ubialm,$as_nomresalm,$as_telresalm,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_insert_almacen
		//         Access: public (sigesp_siv_d_almacen)
		//      Argumento: $as_codemp //codigo de empresa ,$as_codalm //codigo de almacen, $as_nomfisalm //nombre fiscal del almacen
		//				   $as_desalm //descripcion del almacen, $as_telalm //telefono del almacen, $as_ubialm //ubicacion del almacen
		//				   $as_nomresalm //nombre del responsable del almacen, $as_telresalm //telefono del responsable del almacen
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que inserta un nuevo almacen en la tabla de  siv_almacen
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		$ls_sql= "INSERT INTO siv_almacen (codemp,codalm,nomfisalm,desalm,telalm,ubialm,nomresalm,telresalm) ".
				 "     VALUES('".$as_codemp."','".$as_codalm."','".$as_nomfisalm."','".$as_desalm."','".$as_telalm."', ".
				 "            '".$as_ubialm."','".$as_nomresalm."','".$as_telresalm."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->almacen MÉTODO->uf_siv_insert_almacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Almacén ".$as_codalm." Asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} //end function  uf_siv_insert_almacen

	function uf_siv_update_almacen($as_codemp,$as_codalm,$as_nomfisalm,$as_desalm,$as_telalm,$as_ubialm,$as_nomresalm,$as_telresalm,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_update_almacen
		//         Access: public (sigesp_siv_d_almacen)
		//      Argumento: $as_codemp //codigo de empresa ,$as_codalm //codigo de almacen, $as_nomfisalm //nombre fiscal del almacen
		//				   $as_desalm //descripcion del almacen, $as_telalm //telefono del almacen, $as_ubialm //ubicacion del almacen
		//				   $as_nomresalm //nombre del responsable del almacen, $as_telresalm //telefono del responsable del almacen
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que actualiza un  almacen existente en la tabla de  siv_almacen
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 	$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql= "UPDATE siv_almacen".
				 "   SET nomfisalm='". $as_nomfisalm ."',".
				 "       desalm='". $as_desalm ."',".
				 "       telalm='". $as_telalm ."', ". 
				 "       ubialm='". $as_ubialm ."', ". 
				 "       nomresalm='". $as_nomresalm ."',".
				 "       telresalm='". $as_telresalm ."'".
				 " WHERE codalm='" . $as_codalm ."'".
				 "   AND codemp='" . $as_codemp ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->almacen MÉTODO->uf_siv_insert_almacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Almacén ".$as_codalm." Asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	    return $lb_valido;
	} // end  function uf_siv_update_almacen

	function uf_siv_delete_almacen($as_codemp,$as_codalm,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_delete_almacen
		//         Access: public (sigesp_siv_d_almacen)
		//      Argumento: $as_codemp //codigo de empresa ,$as_codalm //codigo de almacen, $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un almacén determinado que no contenga articulos de la tabla de  siv_almacen.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe= $this->uf_siv_select_articuloalmacen($as_codemp,$as_codalm);
		if($lb_existe)
		{
			$this->io_msg->message("El almacen tiene articulos asociados");		
			$lb_valido=false;
		}
		else
		{
			$this->io_sql->begin_transaction();	
			$ls_sql= "DELETE FROM siv_almacen".
					 " WHERE codemp= '".$as_codemp. "'".
					 "   AND codalm= '".$as_codalm. "'"; 
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->almacen MÉTODO->uf_siv_delete_almacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Almacén ".$as_codalm." Asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}			
		return $lb_valido;
	} //end function uf_siv_delete_almacen

	function uf_siv_select_articuloalmacen($as_codemp,$as_codalm)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_articuloalmacen
		//         Access: private
		//      Argumento: $as_codemp //codigo de empresa ,$as_codalm //codigo de almacen
		//	      Returns: Retorna un Booleano
		//    Description: Funcion verifica si existen articulos en un determinado almacen en la tabla de  siv_articuloalmacen.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT codalm FROM siv_articuloalmacen  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND codalm='".$as_codalm."'" ;
		$li_row=$this->io_sql->select($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->almacen MÉTODO->uf_siv_select_articuloalmacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($li_row))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
			}
		}
		$this->io_sql->free_result($li_row);
		return $lb_valido;
	} // end function uf_siv_select_articuloalmacen
	
} //end class sigesp_siv_c_almacen
?>
