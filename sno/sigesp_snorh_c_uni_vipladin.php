<?php
class sigesp_snorh_c_uni_vipladin
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_personal;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_uni_vipladin()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_uni_vipladin
		//		   Access: public (sigesp_snorh_d_uni_vipladin)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 01/08/2008 								Fecha Última Modificación : 
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
		$this->io_seguridad=new sigesp_c_seguridad();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_snorh_c_uni_vipladin
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_uni_vipladin)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 01/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_unidadvipladin($as_codunivipladin)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_unidadvipladin
		//		   Access: private
 		//	    Arguments: as_coduniadm  // código de la unidad administrativa 
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la unidad vipladin está registrada
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 01/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codemp ".
				 "  FROM srh_unidadvipladin".
				 " WHERE codemp='".$this->ls_codemp."' ".
				 "   AND codunivipladin='".$as_codunivipladin."' ";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Unidad VIPLADIN MÉTODO->uf_select_unidadvipladin ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_unidadvipladin
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_unidad_vipladin($as_codunivipladin,$as_desunivipladin,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_unidad_vipladin
		//		   Access: private
		//	    Arguments: as_codunivipladin  // código de la unidad vipladin
		//				   as_desunivipladin  // descripción de la unidad vipladin				  
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla srh_unidadvipladin
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 01/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	    $ls_sql= "INSERT INTO srh_unidadvipladin (codemp, codunivipladin, denunivipladin) ".
		         " VALUES('".$this->ls_codemp."','".$as_codunivipladin."','".$as_desunivipladin."') ";
				 
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Unidad VIPLADIN MÉTODO->uf_insert_unidad_vipladin ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Unidad VIPLADIN ".$as_codunivipladin;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Unidad VIPLADIN fue Registrada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Unidad VIPLADIN MÉTODO->uf_insert_unidad_vipladin ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_unidad_vipladin
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_unidad_vipladin($as_codunivipladin,$as_desunivipladin,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//    	 Function:  uf_update_unidad_vipladin
		//		   Access: private
		//	    Arguments: as_codunivipladin  // código de la unidad vipladin
		//				   as_desunivipladin  // descripción de la unidad vipladin		//				  
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla srh_unidadvipladin
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 01/04/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	    $ls_sql= "UPDATE srh_unidadvipladin ".
				 "   SET denunivipladin='".$as_desunivipladin."'".
				 " WHERE codemp='".$this->ls_codemp."' ".
				 "   AND codunivipladin='".$as_codunivipladin."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Unidad VIPLADIN MÉTODO->uf_update_unidad_vipladin ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			////////////////////////////////         SEGURIDAD               //////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Unidad Administrativa ".$as_codunivipladin;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Unidad VIPLADIN fue Actualizada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Unidad VIPLADIN MÉTODO->uf_update_unidad_vipladin ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_unidadadministrativa
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codunivipladin,$as_desunivipladin,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_uni_vipladin)
		//	    Arguments: as_codunivipladin  // código de la unidad vipladin
		//				   as_desunivipladin  // descripción de la unidad vipladin				   
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla srh_unidadvipladin
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 01/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		switch ($as_existe)
		{ 
			case "FALSE":
				if($this->uf_select_unidadvipladin($as_codunivipladin)===false)
				{
					$lb_valido=$this->uf_insert_unidad_vipladin($as_codunivipladin,$as_desunivipladin,$aa_seguridad);					
				}
				else
				{
					$this->io_mensajes->message("La Unidad VIPLADIN ya existe, no la puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_unidadvipladin($as_codunivipladin)))
				{
					$lb_valido=$this->uf_update_unidad_vipladin($as_codunivipladin,$as_desunivipladin,$aa_seguridad);					
				}
				else
				{
					$this->io_mensajes->message("La Unidad VIPLADIN no existe, no la puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_unidadvipladin_personas($as_codunivipladin)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_unidadvipladin
		//		   Access: private
 		//	    Arguments: as_coduniadm  // código de la unidad administrativa 
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la unidad vipladin esta asociada a una persona
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 09/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codunivipladin ".
				 "  FROM sno_personal".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND codunivipladin='".$as_codunivipladin."' ";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Unidad VIPLADIN MÉTODO->uf_select_unidadvipladin_personas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_unidadvipladin_cargos
	//---------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_unidad_vipladin($as_codunivipladin,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_unidadadministrativa
		//		   Access: public (sigesp_snorh_d_uni_ad)
		//	    Arguments: as_coduniadm  // código de la unidad administrativa
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina de la tabla sno_unidadadmin
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/05/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		 if ($this->uf_select_unidadvipladin_personas($as_codunivipladin)===false)		     
		 {
			$ls_sql="DELETE ".
			        "  FROM srh_unidadvipladin ".
				    " WHERE codemp='".$this->ls_codemp."' ".
				    "   AND codunivipladin='".$as_codunivipladin."' ";
        	$this->io_sql->begin_transaction();
		   	$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Unidad VIPLADIN MÉTODO->uf_delete_unidad_vipladin ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la Unidad VIPLADIN ".$as_codunivipladin;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{	
					$this->io_mensajes->message("La Unidad VIPLADIN fue Eliminada.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
        			$this->io_mensajes->message("CLASE->Unidad VIPLADIN MÉTODO->uf_delete_unidad_vipladin ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_sql->rollback();
				}
			}
		  }	
		  else
		  {
		  $lb_valido=false;
		  $this->io_mensajes->message("No se puede eliminar la Unidad VIPLADIN, hay Personas relacionados con esta Unidad.");
		  }	 
		      
		return $lb_valido;
    }// end uf_delete_unidad_vipladin
	//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
}
?>