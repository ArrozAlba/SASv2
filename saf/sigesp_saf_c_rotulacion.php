<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");

class sigesp_saf_c_rotulacion
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_saf_c_rotulacion()
	{
		$this->io_msg=new class_mensajes();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();
	}
	
	function uf_saf_select_rotulacion($as_codigo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_rotulacion
		//         Access: public  
		//      Argumento: $as_codigo //codigo de rotulacion
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca una rotulacion en la tabla saf_rotulacion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 01/01/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM saf_rotulacion  ".
				  " WHERE codrot='".$as_codigo."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->rotulacion MÉTODO->uf_saf_select_rotulacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
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
	}//fin uf_saf_select_rotulacion


	function  uf_saf_insert_rotulacion($as_codigo,$as_denominacion,$as_empleo,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_rotulacion
		//         Access: public  
		//      Argumento: $as_codigo       //codigo de rotulacion
		//                 $as_denominacion //denominacion de la rotulacion
		//                 $as_empleo       //empleo de la rotulacion
		//                 $aa_seguridad    //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un tipo rotulacion en la tabla saf_rotulacion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 01/01/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO saf_rotulacion (codrot, denrot, emprot) ".
				  " VALUES('".$as_codigo."','".$as_denominacion."','".$as_empleo."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->rotulacion MÉTODO->uf_saf_insert_rotulacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();

		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Metodo ".$as_codigo;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	}//fin uf_saf_insert_rotulacion

	function uf_saf_update_rotulacion($as_codigo,$as_denominacion,$as_empleo,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_rotulacion
		//         Access: public  
		//      Argumento: $as_codigo       //codigo de rotulacion
		//                 $as_denominacion //denominacion de la rotulacion
		//                 $as_empleo       //empleo de la rotulacion
		//                 $aa_seguridad    //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un tipo rotulacion en la tabla saf_rotulacion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 01/01/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "UPDATE saf_rotulacion SET   denrot='". $as_denominacion ."', emprot='". $as_empleo ."'". 
			      " WHERE codrot='" . $as_codigo ."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->rotulacion MÉTODO->uf_saf_update_rotulacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Metodo ".$as_codigo;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	}// fin uf_saf_update_rotulacion

	function uf_saf_delete_rotulacion($as_codigo,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_delete_rotulacion
		//         Access: public  
		//      Argumento: $as_codigo    //codigo de rotulacion
		//                 $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un tipo rotulacion en la tabla saf_rotulacion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 01/01/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_existe=$this->uf_saf_select_activos($as_codigo);
		if($lb_existe)
		{
			$this->io_msg->message("El registro tiene bienes asociados");
		}
		else
		{
			$ls_sql = "DELETE FROM saf_rotulacion".
					  " WHERE codrot= '".$as_codigo. "'"; 
			$this->io_sql->begin_transaction();	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->rotulacion MÉTODO->uf_saf_delete_rotulacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
	
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Metodo ".$as_codigo;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		return $lb_valido;
	} //fin de uf_saf_delete_rotulacion
	
	function uf_saf_select_activos($as_codrot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_activos
		//         Access: public 
		//      Argumento: $as_codrot // codigo de rotulacion
		//	      Returns: Retorna un Booleano
		//    Description: Esta funcion verifica si hay activos asociados al renglon de la rotulacion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM saf_activo  ".
				  " WHERE codemp='".$this->ls_codemp."'".
				  " AND codrot='".$as_codrot."'" ;
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->rotulacion MÉTODO->uf_saf_select_activos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	

}//fin de la class sigesp_saf_c_metodos
?>
