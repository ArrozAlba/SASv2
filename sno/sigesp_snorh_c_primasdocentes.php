<?php
class sigesp_snorh_c_primasdocentes
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $seguridad;
	var $ls_codemp;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_primasdocentes()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//  	 Function: sigesp_snorh_c_primasdocentes
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
		require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
		$this->io_keygen= new sigesp_c_generar_consecutivo();
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];		
	}// end function sigesp_snorh_c_primasdocentes
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
	function uf_select_primadocente($as_codpridoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_primadocente
		//		   Access: private
		//	    Arguments: as_codpridoc  // Codigo de la prima del docente
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la prima del docente está registrado
		//	   Creado Por: Ing. Carlos Zambrano
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codpridoc ".
				"  FROM sno_primasdocentes ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codpridoc='".$as_codpridoc."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Prima Docente MÉTODO->uf_select_primadocente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_primadocente
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_primadocente(&$as_codpridoc,$as_despridoc,$ai_valpridoc,$as_tippridoc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_primadocente
		//		   Access: private
		//	    Arguments: as_codpridoc     // Código de la prima del docente
		//				   as_despridoc     // descripción 
		//				   ai_valpridoc     // valor de la prima
		//				   as_tippridoc     // tipo de prima del docente
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta la clasificación docente asociado a una escala docente
		//	   Creado Por: Ing. Carlos Zambrano
		// Fecha Creación: 17/03/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_codpridocaux=$as_codpridoc;
		$lb_valido= $this->io_keygen->uf_verificar_numero_generado("SNO","sno_primasdocentes","codpridoc","SNOPD",4,"","","",&$as_codpridoc);
		$lb_valido=true;
		if($lb_valido)
		{
			$ls_sql="INSERT INTO sno_primasdocentes".
				"(codemp,codpridoc,despridoc,valpridoc,tippridoc)VALUES".
				"('".$this->ls_codemp."','".$as_codpridoc."','".$as_despridoc."','".$ai_valpridoc."','".$as_tippridoc."')";
				
			$this->io_sql->begin_transaction();				
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_sql->rollback();
				if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
				{
					$lb_valido=$this->uf_insert_primadocente(&$as_codpridoc,$as_despridoc,$ai_valpridoc,$as_tippridoc,$aa_seguridad);
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_primadocente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
			}
			else
			{
			///////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó la prima docente ".$as_codpridoc." del tipo ".$as_tippridoc;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
				if($lb_valido)
				{	
					if($ls_codpridocaux!=$as_codpridoc)
					{
						$this->io_mensajes->message("Se Asigno el Codigo de Prima: ".$as_codpridoc);
					}
					$lb_valido=true;
					$this->io_sql->commit();
					$this->io_mensajes->message("La Prima Docente fue Registrada."); 
				}			
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("Ocurrio un Error al Registrar la Prima Docente."); 
					$this->io_sql->rollback();
				}
			}
		}
		return $lb_valido;
		
	}// end function uf_insert_primadocente	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_primadocente($as_codpridoc,$as_despridoc,$ai_valpridoc,$as_tippridoc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_primadocente
		//		   Access: private
		//	     Arguments: as_codpridoc     // Código de la prima del docente
		//				    as_despridoc     // descripción 
		//				    ai_valpridoc     // valor de la prima
		//				    as_tippridoc     // tipo de prima del docente
		//				    aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza la clasificación docente asociado a una escala docente
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_primasdocentes ".
				"   SET despridoc='".$as_despridoc."', ".
				"		valpridoc='".$ai_valpridoc."'".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codpridoc='".$as_codpridoc."'".
				"   AND tippridoc='".$as_tippridoc."'";
				
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Primas Docente MÉTODO->uf_update_primadocente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Prima Docente ".$as_codpridoc." del tipo  ".$as_tippridoc;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Prima Docente fue Actualizada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Prima Docente MÉTODO->uf_update_primadocente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_primadocente		
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codpridoc,$as_despridoc,$ai_valpridoc,$as_tippridoc,$aa_seguridad)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_primasdocentes)
		//	    Arguments: as_codpridoc     // Código de la prima docente
		//				   as_despridoc     // descripción de la prima del docente 
		//				   as_tippridoc     // Tipo de prima
		//				   ai_valpridoc     // Valor de la prima del docente
		//				   aa_seguridad     // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que almacena la clasificación docente asociado a una escala docente
		//	   Creado Por: Ing. Carlos Zambrano
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_valpridoc=str_replace(".","",$ai_valpridoc);
		$ai_valpridoc=str_replace(",",".",$ai_valpridoc);				
		$lb_valido=false;		
		switch ($as_existe)
		{
			case "FALSE":
				if(!($this->uf_select_primadocente($as_codpridoc)))
				{
					$lb_valido=$this->uf_insert_primadocente($as_codpridoc,$as_despridoc,$ai_valpridoc,$as_tippridoc,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Prima del Docente ya existe, no la puede incluir.");
				}
				break;
							
			case "TRUE":
				if(($this->uf_select_primadocente($as_codpridoc)))
				{
					$lb_valido=$this->uf_update_primadocente($as_codpridoc,$as_despridoc,$ai_valpridoc,$as_tippridoc,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Prima del Docente no existe, no la puede actualizar.");
				}
				break;
		}		
		
		return $lb_valido;
	}// end function uf_update_primadocente	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_integridad_primapersonal($as_codpridoc)
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
       	$ls_sql="SELECT codpridoc 
				  FROM sno_primadocentepersonal 
				  WHERE codemp='".$this->ls_codemp."'
				  AND codpridoc='".$as_codpridoc."'";
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
			$this->msg->message("CLASE->Prima Docente MÉTODO->uf_integridad_personalnomina ERROR->".$this->fun->uf_convertirmsg($this->SQL->message)); 
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
	function uf_delete_primadocente($as_codpridoc,$as_tippridoc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function:     uf_delete_primadocente
		//	Arguments:    as_codescdoc  // código de la escala Docente
		//				  as_codcladoc  // código de la clasificación Docente
		//				  aa_seguridad  // arreglo de las variables de seguridad
		//	Returns:	  $lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	Description:  Funcion que elimina de la tabla sno_clasificaciondocente
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        if (!$this->uf_integridad_primapersonal($as_codpridoc))
		{
			$ls_sql=" DELETE FROM sno_primasdocentes ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codpridoc='".$as_codpridoc."'".
					"   AND tippridoc='".$as_tippridoc."'";
					
        	$this->io_sql->begin_transaction();
		   	$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Primas Docente METODO->uf_delete_primadocente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la Prima docente ".$as_codpridoc." del tipo ".$as_tippridoc;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{	
					$this->io_mensajes->message("La Prima Docente fue Eliminada.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Prima Docente MÉTODO->uf_delete_primadocente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$this->io_sql->rollback();
				}
			}
		} 
		else
		{
			$this->io_mensajes->message("No se puede eliminar la Prima Docente. Hay personal asociado a esta.");
			$lb_valido=false;
		}       
		return $lb_valido;
    }// end function uf_delete_primadocente
	//-----------------------------------------------------------------------------------------------------------------------------------

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>