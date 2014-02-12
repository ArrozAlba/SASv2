<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_funciones.php");

class sigesp_saf_c_catalogo
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_saf_c_catalogo()
	{
		$this->io_msg=new class_mensajes();
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->msg=new class_mensajes();
	}
	
	function uf_saf_select_catalogo($as_catalogo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_catalogo
		//         Access: public 
		//      Argumento: $as_catalogo    // codigo de catalogo sigecof
		//	      Returns: Retorna un Booleano
		//    Description: Esta funcion busca una codificacion del catalogo SIGECOF en la tabla de  saf_catalogo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM saf_catalogo  ".
				  " WHERE catalogo='".$as_catalogo."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->catalogo MÉTODO->uf_saf_select_catalogo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
		}
			
		$this->io_sql->free_result($rs_data);
		return $lb_valido;

	}//fin de la function uf_saf_select_catalogo 

	function  uf_saf_insert_catalogo($as_catalogo,$as_denominacion,$as_cuenta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_catalogo
		//         Access: public 
		//      Argumento: $as_catalogo     // codigo de catalogo sigecof
		//                 $as_denominacion // denominacion del catalogo
		//                 $as_cuenta       // cuenta presupuestaria asociada
		//                 $aa_seguridad    // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Esta funcion inserta un nuevo registro al catalogo SIGECOF
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO saf_catalogo (catalogo, dencat, spg_cuenta) ".
				  " VALUES ( '".$as_catalogo."','".$as_denominacion."','".$as_cuenta."' )" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->catalogo MÉTODO->uf_saf_insert_catalogo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();

		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó en el Catalogo SIGECOF ".$as_catalogo;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		
		return $lb_valido;

	}//fin de la uf_saf_insert_catalogo

	function uf_saf_update_catalogo($as_catalogo,$as_denominacion,$as_cuenta,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_catalogo
		//         Access: public 
		//      Argumento: $as_catalogo     // codigo de catalogo sigecof
		//                 $as_denominacion // denominacion del catalogo
		//                 $as_cuenta       // cuenta presupuestaria asociada
		//                 $aa_seguridad    // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Esta funcion actualiza un registro al catalogo SIGECOF
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=false;
		 $ls_sql = "UPDATE saf_catalogo SET   dencat='". $as_denominacion ."', spg_cuenta='". $as_cuenta ."' ".
				   " WHERE catalogo='".$as_catalogo."' ";
			$this->io_sql->begin_transaction();
			$li_row = $this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->catalogo MÉTODO->uf_saf_update_catalogo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó ".$as_catalogo." en el Catalogo SIGECOF ";
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
			}
		  return $lb_valido;
	}// fin uf_saf_update_catalogo

	function uf_saf_delete_catalogo($as_catalogo,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_delete_catalogo
		//         Access: public 
		//      Argumento: $as_catalogo     // codigo de catalogo sigecof
		//                 $aa_seguridad    // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Esta funcion elimina un registro al catalogo SIGECOF
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_existe=$this->uf_saf_select_activos($as_catalogo);
		if($lb_existe)
		{
			$this->io_msg->message("El registro tiene bienes asociados");
		}
		else
		{
			$lb_existe=$this->uf_saf_select_articulos($as_catalogo);
			if($lb_existe)
			{
				$this->io_msg->message("El registro tiene articulos asociados");
			}
			else
			{
				$ls_sql = " DELETE FROM saf_catalogo".
						  " WHERE catalogo= '".$as_catalogo. "'"; 
				$this->io_sql->begin_transaction();	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$this->io_msg->message("CLASE->catalogo MÉTODO->uf_saf_delete_catalogo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;
					$this->io_sql->rollback();
				}
				else
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="DELETE";
					$ls_descripcion ="Eliminó el Catalogo SIGECOF ".$as_catalogo;
					$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////			
					$this->io_sql->commit();
				}
			}
		}
		return $lb_valido;
	} //fin  uf_saf_delete_catalogo

	function uf_saf_select_activos($as_catalogo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_activos
		//         Access: public 
		//      Argumento: $as_catalogo    // codigo de catalogo sigecof
		//	      Returns: Retorna un Booleano
		//    Description: Esta funcion verifica si hay activos asociados al renglon del catalogo SIGECOF
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM saf_activo  ".
				  " WHERE codemp='".$this->ls_codemp."'".
				  " AND catalogo='".$as_catalogo."'" ;
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->catalogo MÉTODO->uf_saf_select_activos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				
			}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	}//fin uf_saf_select_activos
	
	function uf_saf_select_articulos($as_catalogo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_articulos
		//         Access: public 
		//      Argumento: $as_catalogo    // codigo de catalogo sigecof
		//	      Returns: Retorna un Booleano
		//    Description: Esta funcion busca una codificacion del catalogo SIGECOF en la tabla de  saf_catalogo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM siv_articulo  ".
				  " WHERE codemp='".$this->ls_codemp."'".
				  " AND codcatsig='".$as_catalogo."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->catalogo MÉTODO->uf_saf_select_articulos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				
			}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	}//fin uf_saf_select_articulos


}//fin sigesp_saf_c_catalogo
?>
