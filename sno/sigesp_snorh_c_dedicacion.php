<?php
class sigesp_snorh_c_dedicacion
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_personalnomina;
	var $io_asignacioncargo;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_dedicacion()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_dedicacion
		//		   Access: public (sigesp_snorh_d_dedicacion)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
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
		require_once("sigesp_sno_c_personalnomina.php");
		$this->io_personalnomina=new sigesp_sno_c_personalnomina();
		require_once("sigesp_sno_c_asignacioncargo.php");
		$this->io_asignacioncargo=new sigesp_sno_c_asignacioncargo();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_snorh_c_dedicacion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_dedicacion)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_personalnomina);
		unset($this->io_asignacioncargo);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_dedicacion($as_codded)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_dedicacion
		//		   Access: private
		//	    Arguments: as_codded  // código de la dedicacion
		//	      Returns: lb_valido True si existe ó False si no existe
		//	  Description: Funcion que verifica si la dedicación está registrada
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codded FROM sno_dedicacion ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codded='".$as_codded."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$this->io_mensajes->message("CLASE->Dedicación MÉTODO->uf_select_dedicacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_dedicacion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_dedicacion($as_codded,$as_desded,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_dedicacion
		//		   Access: private
		//	    Arguments: as_codded  // código de la dedicación
		//				   as_desded  // descripción de la dedicación 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_dedicacion
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_dedicacion(codemp,codded,desded) VALUES ('".$this->ls_codemp."','".$as_codded."','".$as_desded."')";
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Dedicación MÉTODO->uf_insert_dedicacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Dedicación ".$as_codded;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Dedicación fue registrada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Dedicación MÉTODO->uf_insert_dedicacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_dedicacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_dedicacion($as_codded,$as_desded,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_dedicacion
		//		   Access: private
		//	    Arguments: as_codded  // código de la dedicación
		//				   as_desded  // descripción de la dedicación 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_dedicacion
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_dedicacion ".
				"   SET desded='".$as_desded."' ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codded='".$as_codded."'";
				
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Dedicación MÉTODO->uf_update_dedicacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			////////////////////////////////         SEGURIDAD               //////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la dedicación ".$as_codded;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Dedicación fue Actualizada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Dedicación MÉTODO->uf_update_dedicacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_dedicacion	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codded,$as_desded,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_dedicacion)
		//	    Arguments: as_codded  // código de la dedicación
		//				   as_desded  // descripción de la dedicación 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sno_dedicacion
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		switch ($as_existe)
		{
			case "FALSE":
				if(!($this->uf_select_dedicacion($as_codded)))
				{
					$lb_valido=$this->uf_insert_dedicacion($as_codded,$as_desded,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Dedicación ya existe, no la puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_dedicacion($as_codded)))
				{
					$lb_valido=$this->uf_update_dedicacion($as_codded,$as_desded,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Dedicacióno no existe, no la puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_tipopersonal($as_codded,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_tipopersonal
		//		   Access: private
		//	    Arguments: as_codded  // código de la dedicacion
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si existe la dedicacion en la tabla tipo personal False si no existe
		//	  Description: Funcion que elimina todos los tipos de personal relacionados a esta dedicación
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 		$lb_valido=true;
			$ls_sql="DELETE FROM sno_tipopersonal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codded='".$as_codded."'";
		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Dedicación MÉTODO->uf_delete_tipopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó todos los tipos de personal relacionados a la dedicación ".$as_codded;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;    
	}// end function uf_delete_tipopersonal	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_fideiconfigurable($as_codded)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_fideiconfigurable
		//		   Access: private
		//	    Arguments: as_codded     // codigo de dedicacion
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la dedicación existe en fideiconfigurable
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codded ".
				"  FROM sno_fideiconfigurable ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codded='".$as_codded."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Dedicación MÉTODO->uf_select_fideiconfigurable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_fideiconfigurable
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_dedicacion($as_codded,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_dedicacion
		//		   Access: public (sigesp_snorh_d_dedicacion)
		//	    Arguments: as_codded  // código de la dedicación
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina de la tabla sno_dedicacion
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        if (($this->io_personalnomina->uf_select_personalnomina("codded",$as_codded,"0")===false)&&
		    ($this->io_asignacioncargo->uf_select_asignacioncargo("codded",$as_codded,"0")===false)&&
			($this->uf_select_fideiconfigurable($as_codded)===false))
		{
			$lb_valido=$this->uf_delete_tipopersonal($as_codded,$aa_seguridad);
			if($lb_valido)
			{
				$ls_sql="DELETE FROM sno_dedicacion ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codded='".$as_codded."'";
						
				$this->io_sql->begin_transaction();
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Dedicación MÉTODO->uf_delete_dedicacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$this->io_sql->rollback();
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="DELETE";
					$ls_descripcion ="Eliminó la dedicación ".$as_codded;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////	
					if($lb_valido)
					{	
						$this->io_mensajes->message("La Dedicación fue Eliminada.");
						$this->io_sql->commit();
					}
					else
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Dedicación MÉTODO->uf_delete_dedicacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
						$this->io_sql->rollback();
					}
				}
			}
		} 
		else
		{
			$this->io_mensajes->message("No se puede eliminar la Dedicación. Hay personal, Asignación de cargo ó Fideicomiso asociado a esta.");
			$lb_valido=false;
		}       
		return $lb_valido;
    }// end function uf_delete_dedicacion	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_dedicacion(&$as_existe,&$as_codded,&$as_desded)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_dedicacion
		//		   Access: public (sigesp_snorh_d_dedicacion)
		//	    Arguments: as_existe  // si existe la dedicación
		//			       as_codded  // código de la dedicación
		//				   as_desded  // descripción de la dedicación
		//	      Returns: lb_valido True si existe ó False si no existe
		//	  Description: Funcion que obtiene los datos de la dedicación
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codded, desded ".
				"  FROM sno_dedicacion ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codded='".$as_codded."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Dedicación MÉTODO->uf_load_dedicacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_existe="TRUE";			
				$as_codded=$row["codded"];
				$as_desded=$row["desded"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>