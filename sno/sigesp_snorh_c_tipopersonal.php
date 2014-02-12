<?php
class sigesp_snorh_c_tipopersonal
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_tipopersonal()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_tipopersonal
		//		   Access: public (sigesp_snorh_d_tipopersonal)
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
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_snorh_c_tipopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_tipopersonal)
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
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_tipopersonal($as_codded,$as_codtipper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_tipopersonal
		//		   Access: private
		//	    Arguments: as_codded  // código de la dedicación
		//				   as_codtipper  // código de tipopersonal
		//	      Returns: lb_valido True si existe ó False si no existe
		//	  Description: Funcion que verifica si el tipo de personal está registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codtipper FROM sno_tipopersonal ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codded='".$as_codded."'".
				"   AND codtipper='".$as_codtipper."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Tipo Personal MÉTODO->uf_select_tipopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_tipopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_tipopersonal($as_codded,$as_codtipper,$as_destipper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_tipopersonal
		//		   Access: private
		//	    Arguments: as_codded  // código de la dedicación
		//				   as_codtipper  // código de tipopersonal
		//				   as_destipper  // descripción del tipo de personal
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_tipopersonal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_tipopersonal(codemp,codded,codtipper,destipper)VALUES".
				"('".$this->ls_codemp."','".$as_codded."','".$as_codtipper."','".$as_destipper."')";
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tipo Personal MÉTODO->uf_insert_tipopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el tipo de personal ".$as_codtipper." asociado a la Dedicación ".$as_codded;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Tipo de Personal fue Registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Tipo Personal MÉTODO->uf_insert_tipopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_tipopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_tipopersonal($as_codded,$as_codtipper,$as_destipper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_tipopersonal
		//		   Access: private
		//	    Arguments: as_codded  // código de la dedicación
		//				   as_codtipper  // código de tipopersonal
		//				   as_destipper  // descripción del tipo de personal
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_tipopersonal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_tipopersonal ".
				"   SET destipper='".$as_destipper."' ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codded='".$as_codded."'".
				"   AND codtipper='".$as_codtipper."'";
				
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tipo Personal MÉTODO->uf_update_tipopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			////////////////////////////////         SEGURIDAD               //////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el tipo de personal ".$as_codtipper." asociado a la Dedicación ".$as_codded;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Tipo de Personal fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Tipo Personal MÉTODO->uf_update_tipopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_tipopersonal	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codded,$as_codtipper,$as_destipper,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_tipopersonal)
		//	    Arguments: as_codded  // código de la dedicación
		//				   as_codtipper  // código de tipopersonal
		//				   as_destipper  // descripción del tipo de personal
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sno_tipopersonal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		switch ($as_existe)
		{
			case "FALSE":
				if(!($this->uf_select_tipopersonal($as_codded,$as_codtipper)))
				{
					$lb_valido=$this->uf_insert_tipopersonal($as_codded,$as_codtipper,$as_destipper,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Tipo de Personal ya existe, no lo puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_tipopersonal($as_codded,$as_codtipper)))
				{
					$lb_valido=$this->uf_update_tipopersonal($as_codded,$as_codtipper,$as_destipper,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Tipo de Personal no existe, no lo puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar		
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_integridad_asignacioncargo($as_codded,$as_codtipper)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_integridad_asignacioncargo
		//		   Access: private
		//	    Arguments: as_codded  // código de dedicación
		//				   as_codtipper  // código de tipo de personal
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que valida que ningún asignación de cargo tenga asociada este dedicación y tipo de personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 		$lb_existe=true;
       	$ls_sql="SELECT codded ".
				"  FROM sno_asignacioncargo ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codded='".$as_codded."'".
				"   AND codtipper='".$as_codtipper."'";
				
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
			$this->msg->message("CLASE->Tipo Personal MÉTODO->uf_integridad_asignacioncargo ERROR->".$this->fun->uf_convertirmsg($this->SQL->message)); 
       	}
       	else
       	{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);	
       	}
		return $lb_existe ;    
	}// end function uf_integridad_asignacioncargo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_integridad_personalnomina($as_codded,$as_codtipper)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_integridad_personalnomina
		//		   Access: private
		//	    Arguments: as_codded  // código de dedicación
		//				   as_codtipper  // código de tipo de personal
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que valida que ningún personal nómina tenga asociada este dedicación y tipo de personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 		$lb_existe=true;
       	$ls_sql="SELECT codded ".
				"  FROM sno_personalnomina ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codded='".$as_codded."'".
				"   AND codtipper='".$as_codtipper."'";
				
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
			$this->msg->message("CLASE->Tipo Personal MÉTODO->uf_integridad_personalnomina ERROR->".$this->fun->uf_convertirmsg($this->SQL->message)); 
       	}
       	else
       	{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);	
       	}
		return $lb_existe ;    
	}// end function uf_integridad_personalnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_fideiconfigurable($as_codded,$as_codtipper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_fideiconfigurable
		//		   Access: private
		//	    Arguments: as_codded     // codigo de dedicacion
		//                 as_codtipper  // codigo tipo personal
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el tipo de personal existe en fideiconfigurable
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codded ".
				"  FROM sno_fideiconfigurable ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codded='".$as_codded."'".
				"   AND codtipper='".$as_codtipper."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Tipo Personal MÉTODO->uf_select_fideiconfigurable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	function uf_delete_tipopersonal($as_codded,$as_codtipper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_tipopersonal
		//		   Access: public (sigesp_snorh_d_tipopersonal)
		//	    Arguments: as_codded  // código de la dedicacion
		//				   as_codtipper  // código del tipo de personal
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina de la tabla sno_tipopersonal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        if (($this->uf_integridad_personalnomina($as_codded,$as_codtipper)===false)&&
			($this->uf_integridad_asignacioncargo($as_codded,$as_codtipper)===false)&&
			($this->uf_select_fideiconfigurable($as_codded,$as_codtipper)===false))
		{
			$ls_sql="DELETE FROM sno_tipopersonal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codded='".$as_codded."'".
					"   AND codtipper='".$as_codtipper."'";
					
        	$this->io_sql->begin_transaction();
		   	$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Tipo Personal MÉTODO->uf_delete_tipopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el tipo de personal ".$as_codtipper." asociado a la Dedicación ".$as_codded;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{	
					$this->io_mensajes->message("El Tipo de Personal fue Eliminado.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Tipo Personal MÉTODO->uf_delete_tipopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$this->io_sql->rollback();
				}
			}
		} 
		else
		{
			$this->io_mensajes->message("No se puede eliminar el Tipo de Personal. Hay personal, Asignación de cargo ó Fideicomiso asociado a este.");
			$lb_valido=false;
		}       
		return $lb_valido;
    }// end function uf_delete_tipopersonal		
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>