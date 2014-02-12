<?php
class sigesp_snorh_c_clasifidocente
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $seguridad;
	var $ls_codemp;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_clasifidocente()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//  	 Function: sigesp_snorh_c_clasifidocente
		//		   Access: public (sigesp_snorh_d_clasifidocente)
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
	}// end function sigesp_snorh_c_clasifidocente
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_clasifidocente)
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
	function uf_select_clasifidocente($as_codescdoc, $ai_codcladoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_clasifidocente
		//		   Access: private
		//	    Arguments: as_codescdoc  // código de escala docente
		//				   ai_codcladoc  // código de clasificación docente
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la clasificacion docente está registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codcladoc ".
				"  FROM sno_clasificaciondocente ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codescdoc='".$as_codescdoc."'".
				"   AND codcladoc='".$ai_codcladoc."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Clasificación Docente MÉTODO->uf_select_clasifidocente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_clasifidocente
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_clasifidocente($as_codescdoc,$as_codcladoc,$as_descladoc,$as_tiesercladoc,$ai_suesupcladoc,$ai_suedircladoc,
									  $ai_suedoccladoc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_clasifidocente
		//		   Access: private
		//	    Arguments: as_codescdoc  // Código de escala docente
		//				   ai_codcladoc  // Código de clasificación docente
		//				   as_descladoc // descripción 
		//				   as_tiesercladoc  // tiempo de servicio
		//				   ai_suesupcladoc  // sueldo de supervisor
		//				   ai_suedircladoc  // sueldo de director
		//				   ai_suedoccladoc  // fecha grado
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta la clasificación docente asociado a una escala docente
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_clasificaciondocente".
				"(codemp,codescdoc,codcladoc,descladoc,tiesercladoc,suesupcladoc,suedircladoc,suedoccladoc)VALUES".
				"('".$this->ls_codemp."','".$as_codescdoc."','".$as_codcladoc."','".$as_descladoc."','".$as_tiesercladoc."',".
				"".$ai_suesupcladoc.",".$ai_suedircladoc.",".$ai_suedoccladoc.")";
				
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Clasificación Docente MÉTODO->uf_insert_clasifidocente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la clasificación docente ".$as_codcladoc." asociado a la escala docente ".$as_codescdoc;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Clasificación Docente fue Registrada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Clasificación Docente MÉTODO->uf_insert_clasifidocente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_clasifidocente	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_clasifidocente($as_codescdoc,$as_codcladoc,$as_descladoc,$as_tiesercladoc,$ai_suesupcladoc,$ai_suedircladoc,
									  $ai_suedoccladoc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_clasifidocente
		//		   Access: private
		//	    Arguments: as_codescdoc  // Código de escala docente
		//				   ai_codcladoc  // Código de clasificación docente
		//				   as_descladoc // descripción 
		//				   as_tiesercladoc  // tiempo de servicio
		//				   ai_suesupcladoc  // sueldo de supervisor
		//				   ai_suedircladoc  // sueldo de director
		//				   ai_suedoccladoc  // fecha grado
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza la clasificación docente asociado a una escala docente
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_clasificaciondocente ".
				"   SET descladoc='".$as_descladoc."', ".
				"		tiesercladoc='".$as_tiesercladoc."', ".
				"		suesupcladoc=".$ai_suesupcladoc.", ".
				"		suedircladoc=".$ai_suedircladoc.", ".
				"		suedoccladoc=".$ai_suedoccladoc." ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codescdoc='".$as_codescdoc."'".
				"   AND codcladoc='".$as_codcladoc."'";
				
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Clasificación Docente MÉTODO->uf_update_clasifidocente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Clasificación Docente ".$as_codcladoc." asociado a la Escala  ".$as_codescdoc;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Clasificación Docente fue Actualizada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Clasificación Docente MÉTODO->uf_update_clasifidocente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_clasifidocente		
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codescdoc,$as_codcladoc,$as_descladoc,$as_tiesercladoc,$ai_suesupcladoc,$ai_suedircladoc,
						$ai_suedoccladoc,$aa_seguridad)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_clasifidocente)
		//	    Arguments: as_codescdoc  // Código de escala docente
		//				   ai_codcladoc  // Código de clasificación docente
		//				   as_descladoc // descripción 
		//				   as_tiesercladoc  // tiempo de servicio
		//				   ai_suesupcladoc  // sueldo de supervisor
		//				   ai_suedircladoc  // sueldo de director
		//				   ai_suedoccladoc  // fecha grado
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que almacena la clasificación docente asociado a una escala docente
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_suesupcladoc=str_replace(".","",$ai_suesupcladoc);
		$ai_suesupcladoc=str_replace(",",".",$ai_suesupcladoc);				
		$ai_suedircladoc=str_replace(".","",$ai_suedircladoc);
		$ai_suedircladoc=str_replace(",",".",$ai_suedircladoc);				
		$ai_suedoccladoc=str_replace(".","",$ai_suedoccladoc);
		$ai_suedoccladoc=str_replace(",",".",$ai_suedoccladoc);				
		$lb_valido=false;		
		switch ($as_existe)
		{
			case "FALSE":
				if(!($this->uf_select_clasifidocente($as_codescdoc,$as_codcladoc)))
				{
					$lb_valido=$this->uf_insert_clasifidocente($as_codescdoc,$as_codcladoc,$as_descladoc,$as_tiesercladoc,$ai_suesupcladoc,
															   $ai_suedircladoc,$ai_suedoccladoc,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Clasificación Docente ya existe, no lo puede incluir.");
				}
				break;
							
			case "TRUE":
				if(($this->uf_select_clasifidocente($as_codescdoc,$as_codcladoc)))
				{
					$lb_valido=$this->uf_update_clasifidocente($as_codescdoc,$as_codcladoc,$as_descladoc,$as_tiesercladoc,$ai_suesupcladoc,
															   $ai_suedircladoc,$ai_suedoccladoc,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Clasificación Docente no existe, no la puede actualizar.");
				}
				break;
		}		
		
		return $lb_valido;
	}// end function uf_update_clasifidocente	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_integridad_personalnomina($as_codescdoc,$as_codcladoc)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_integridad_personalnomina
		//		   Access: private
		//	    Arguments: as_codescdoc  // Código de Escala Docente
		//				   as_codcladoc  // Código de Clasificción Docente
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que valida que ningún asignación de cargo tenga asociada este dedicación y tipo de personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 		$lb_existe=true;
       	$ls_sql="SELECT codded ".
				"  FROM sno_personalnomina ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codescdoc='".$as_codescdoc."'".
				"   AND codcladoc='".$as_codcladoc."'";
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
			$this->msg->message("CLASE->Clasificación Docente MÉTODO->uf_integridad_personalnomina ERROR->".$this->fun->uf_convertirmsg($this->SQL->message)); 
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
	function uf_delete_clasifidocente($as_codescdoc,$as_codcladoc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function:     uf_delete_clasifidocente
		//	Arguments:    as_codescdoc  // código de la escala Docente
		//				  as_codcladoc  // código de la clasificación Docente
		//				  aa_seguridad  // arreglo de las variables de seguridad
		//	Returns:	  $lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	Description:  Funcion que elimina de la tabla sno_clasificaciondocente
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        if ($this->uf_integridad_personalnomina($as_codescdoc,$as_codcladoc)===false)
		{
			$ls_sql="DELETE FROM sno_clasificaciondocente ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codescdoc='".$as_codescdoc."'".
					"   AND codcladoc='".$as_codcladoc."'";
					
        	$this->io_sql->begin_transaction();
		   	$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Clasificación Docente METODO->uf_delete_clasifidocente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la clasificación docente ".$as_codcladoc." relacionada a la escala docente ".$as_codescdoc;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{	
					$this->io_mensajes->message("La Clasificación Docente fue Eliminada.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Clasificación Docente MÉTODO->uf_delete_clasifidocente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$this->io_sql->rollback();
				}
			}
		} 
		else
		{
			$this->io_mensajes->message("No se puede eliminar la Clasificación Docente. Hay personal asociado a esta.");
			$lb_valido=false;
		}       
		return $lb_valido;
    }// end function uf_delete_clasifidocente
	//-----------------------------------------------------------------------------------------------------------------------------------

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>