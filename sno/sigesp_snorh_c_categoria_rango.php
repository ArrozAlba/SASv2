<?php
class sigesp_snorh_c_categoria_rango
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_categoria_rango()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_categoria_rango
		//		   Access: public (sigesp_snorh_d_categoria_rango)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 18/06/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_snorh_c_componente
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_categoria_rango)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 18/06/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		/*unset($this->io_personalnomina);
		unset($this->io_asignacioncargo);*/
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_categoria($as_codcat)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_categoria
		//		   Access: private
		//	    Arguments: as_codcat  // código de la categoria
		//	      Returns: lb_valido True si existe ó False si no existe
		//	  Description: Funcion que verifica si la categoria está registrado
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 18/06/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codcat FROM sno_categoria_rango ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcat='".$as_codcat."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$this->io_mensajes->message("CLASE->Componente MÉTODO->uf_select_categoria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_select_categoria
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_categoria($as_codcat,$as_descat,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_categoria
		//		   Access: private
		//	    Arguments: as_codcat  // código de al categoria
		//				   as_descat  // descripción de la Categoria 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_ategoria_rango
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 18/06/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_categoria_rango(codemp,codcat,descat) VALUES ('".$this->ls_codemp."','".$as_codcat."','".$as_descat."')";
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Componente MÉTODO->uf_insert_categoria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Caategoria ".$as_codcat;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Categoría fue registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Categoría MÉTODO->uf_insert_categoria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_categoria
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_categoria($as_codcat,$as_descat,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_categoria
		//		   Access: private
		//	    Arguments: as_codcat  // código de la categoría
		//				   as_descat  // descripción de la Categoría 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_categoria
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 18/06/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_categoria_rango ".
				"   SET descat='".$as_descat."' ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcat='".$as_codcat."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Categoría MÉTODO->uf_update_categoria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			////////////////////////////////         SEGURIDAD               //////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Categoria ".$as_codcat;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Categoría fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Categoria MÉTODO->uf_update_categoria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_categoria	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codcat,$as_descat,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_categoria_rango)
		//	    Arguments: as_codcat  // código de la categoria
		//				   as_descat  // descripción de la Categoria 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sno_categoria_rango
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 18/06/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		switch ($as_existe)
		{
			case "FALSE":
				if(!($this->uf_select_categoria($as_codcat)))
				{
					$lb_valido=$this->uf_insert_categoria($as_codcat,$as_descat,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Categoría  ya existe, no lo puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_categoria($as_codcat)))
				{
					$lb_valido=$this->uf_update_categoria($as_codcat,$as_descat,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Categoría no existe, no lo puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar	
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_rango($as_codcat)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_rango
		//		   Access: private
		//	    Arguments: as_codcat     // codigo de la categoria
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la categoria esta asociado a un rango
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 18/06/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codran, desran ".
				"  FROM sno_rango   ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcat='".$as_codcat."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Categoria MÉTODO->uf_select_rango ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}// end function uf_select_rango
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_categoria($as_codcat,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_categoria
		//		   Access: public (sigesp_snorh_d_categoria_rango)
		//	    Arguments: as_codcat  // código de la categoria
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina de la tabla sno_categoria_rango
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 18/06/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        if($this->uf_select_rango($as_codcat)===false)
		{
			$ls_sql="DELETE FROM sno_categoria_rango ".
				    " WHERE codemp='".$this->ls_codemp."'".
					"   AND codcat='".$as_codcat."'";
			$this->io_sql->begin_transaction();
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Categoria MÉTODO->uf_delete_categoria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la categoría ".$as_codcat;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{	
					$this->io_mensajes->message("La Categoría fue Eliminado.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Categoría omponente MÉTODO->uf_delete_categoria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$this->io_sql->rollback();
				}
			}
		}	
		else
		{
			$this->io_mensajes->message("La Categoría no se puede eliminar esta asociado a un Rango.");
		}	
		return $lb_valido;
    }// end function uf_delete_categoria	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_categoria(&$as_existe,&$as_codcat,&$as_descat)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_categoria
		//		   Access: public (sigesp_snorh_d_componente)
		//	    Arguments: as_existe  // si existe la categoria
		//			       as_codcat  // código de la categoria
		//				   as_descat  // descripción de la Categoria
		//	      Returns: lb_valido True si existe ó False si no existe
		//	  Description: Funcion que obtiene los datos de la categoria
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 18/06/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codcat, descat ".
				"  FROM sno_categoria_rango ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcat='".$as_codcat."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Categoria MÉTODO->uf_load_categoria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_existe="TRUE";			
				$as_codcat=$row["codcat"];
				$as_descat=$row["descat"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// fin function uf_load_categoria
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>