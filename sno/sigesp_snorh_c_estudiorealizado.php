<?php
class sigesp_snorh_c_estudiorealizado
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_estudiorealizado()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:  sigesp_snorh_c_estudiorealizado
		//		   Access: public (sigesp_snorh_d_estudiorealizado)
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
	}// end function sigesp_snorh_c_estudiorealizado
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_estudiorealizado)
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
	function uf_select_estudiorealizado($as_codper, $ai_codestrea)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_estudiorealizado
		//		   Access: private
		//	    Arguments: as_codper  // código del personal
		//				   ai_codestrea  // código estudio realizado
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el estudiorealizado está registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codestrea FROM sno_estudiorealizado ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND codestrea='".$ai_codestrea."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Estudio Realizado MÉTODO->uf_select_estudiorealizado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_estudiorealizado
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_correlativo($as_codper, &$ai_codestrea)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_correlativo
		//		   Access: private (uf_guardar) 
		//	    Arguments: as_codper  // código del personal
		//				   ai_codestrea  // código estudio realizado
		//	      Returns: lb_valido True si lo obtuvo correctamente ó False si hubo error
		//	  Description: Funcion que busca el correlativo del último estudio realizado  y genera el nuevo correlativo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_codestrea=1;
		$ls_sql="SELECT codestrea ".
				"  FROM sno_estudiorealizado ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				" ORDER BY codestrea DESC ";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Estudio Realizado MÉTODO->uf_load_correlativo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_codestrea=intval($row["codestrea"]+1);
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_load_correlativo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_estudiorealizado($as_codper,$ai_codestrea,$as_tipestrea,$as_insestrea,$as_titestrea,$ai_calestrea,
										$ad_fecgraestrea,$as_escval,$ad_feciniact,$ad_fecfinact,$as_desestrea,$as_aprestrea,
										$as_anoaprestrea,$as_horestrea,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_estudiorealizado
		//		   Access: private
		//	    Arguments: as_codper  // Código del Personal
		//				   ai_codestrea  // Código
		//				   as_tipestrea  // tipo
		//				   as_insestrea  // intituto
		//				   as_titestrea  // titulo obtenido
		//				   ai_calestrea  // calificación
		//				   ad_fecgraestrea  // fecha grado
		//				   as_escval  // escala de valoración del estudio
		//				   ad_feciniact  // fecha de inicio del estudio
		//				   ad_fecfinact  // fecha de finalización del estudio
		//				   as_desestrea  // Descripción de Estudio Realizado
		//				   as_aprestrea  // Aprobación del Estudio Realizado
		//				   as_anoaprestrea  // Años de Aprobación de Estudio Realizado
		//				   as_horestrea  // Horas del Estudio Realizado
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el estudio realizado asociado a un personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_estudiorealizado".
				"(codemp,codper,codestrea,tipestrea,insestrea,titestrea,calestrea,fecgraestrea,escval,feciniact,fecfinact,".
				" desestrea,aprestrea,anoaprestrea,horestrea)".
				"VALUES('".$this->ls_codemp."','".$as_codper."',".$ai_codestrea.",'".$as_tipestrea."','".$as_insestrea."',".
				"'".$as_titestrea."',".$ai_calestrea.",'".$ad_fecgraestrea."','".$as_escval."','".$ad_feciniact."','".$ad_fecfinact."',".
				"'".$as_desestrea."','".$as_aprestrea."','".$as_anoaprestrea."','".$as_horestrea."')";
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Estudio Realizado MÉTODO->uf_insert_estudiorealizado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Estudio Realizado ".$ai_codestrea." asociado al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Estudio Realizado fue registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
  	      		$this->io_mensajes->message("CLASE->Estudio Realizado MÉTODO->uf_insert_estudiorealizado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_select_estudiorealizado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estudiorealizado($as_codper,$ai_codestrea,$as_tipestrea,$as_insestrea,$as_titestrea,$ai_calestrea,
										$ad_fecgraestrea,$as_escval,$ad_feciniact,$ad_fecfinact,$as_desestrea,$as_aprestrea,
										$as_anoaprestrea,$as_horestrea,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estudiorealizado
		//		   Access: private
		//	    Arguments: as_codper  // Código del Personal
		//				   ai_codestrea  // Código
		//				   as_tipestrea  // tipo
		//				   as_insestrea  // intituto
		//				   as_titestrea  // titulo obtenido
		//				   ai_calestrea  // calificación
		//				   ad_fecgraestrea  // fecha grado
		//				   as_escval  // escala de valoración del estudio
		//				   ad_feciniact  // fecha de inicio del estudio
		//				   ad_fecfinact  // fecha de finalización del estudio
		//				   as_desestrea  // Descripción de Estudio Realizado
		//				   as_aprestrea  // Aprobación del Estudio Realizado
		//				   as_anoaprestrea  // Años de Aprobación de Estudio Realizado
		//				   as_horestrea  // Horas del Estudio Realizado
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el estudio realizado asociado a un personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_estudiorealizado ".
				"   SET tipestrea='".$as_tipestrea."', ".
				"		insestrea='".$as_insestrea."', ".
				"		desestrea='".$as_desestrea."', ".
				"		titestrea='".$as_titestrea."', ".
				"		calestrea=".$ai_calestrea.", ".
				"		fecgraestrea='".$ad_fecgraestrea."', ".
				"		escval='".$as_escval."', ".
				"		feciniact='".$ad_feciniact."', ".
				"		fecfinact='".$ad_fecfinact."', ".
				"		aprestrea='".$as_aprestrea."' , ".
				"		anoaprestrea='".$as_anoaprestrea."', ".
				"		horestrea='".$as_horestrea."' ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND codestrea=".$ai_codestrea."";
				
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Estudio Realizado MÉTODO->uf_update_estudiorealizado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Estudio Realizado ".$ai_codestrea." asociado al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Estudio Realizado fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
     	   		$this->io_mensajes->message("CLASE->Estudio Realizado MÉTODO->uf_update_estudiorealizado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_estudiorealizado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codper,$ai_codestrea,$as_tipestrea,$as_insestrea,$as_titestrea,$ai_calestrea,
						$ad_fecgraestrea,$as_escval,$ad_feciniact,$ad_fecfinact,$as_desestrea,$as_aprestrea,$as_anoaprestrea,
						$as_horestrea,$aa_seguridad)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_estudiorealizado)
		//	    Arguments: as_codper  // Código del Personal
		//				   ai_codestrea  // Código
		//				   as_tipestrea  // tipo
		//				   as_insestrea  // intituto
		//				   as_titestrea  // titulo obtenido
		//				   ai_calestrea  // calificación
		//				   ad_fecgraestrea  // fecha grado
		//				   as_escval  // escala de valoración del estudio
		//				   ad_feciniact  // fecha de inicio del estudio
		//				   ad_fecfinact  // fecha de finalización del estudio
		//				   as_desestrea  // Descripción de Estudio Realizado
		//				   as_aprestrea  // Aprobación del Estudio Realizado
		//				   as_anoaprestrea  // Años de Aprobación de Estudio Realizado
		//				   as_horestrea  // Horas del Estudio Realizado
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que almacena el estudio realizado asociado a un personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ad_fecgraestrea=$this->io_funciones->uf_convertirdatetobd($ad_fecgraestrea);
		$ad_feciniact=$this->io_funciones->uf_convertirdatetobd($ad_feciniact);
		$ad_fecfinact=$this->io_funciones->uf_convertirdatetobd($ad_fecfinact);
		$ai_calestrea=str_replace(".","",$ai_calestrea);
		$ai_calestrea=str_replace(",",".",$ai_calestrea);		
		$lb_valido=false;		
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_estudiorealizado($as_codper,$ai_codestrea)===false)
				{
					$lb_valido=$this->uf_load_correlativo($as_codper,$ai_codestrea);
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_estudiorealizado($as_codper,$ai_codestrea,$as_tipestrea,$as_insestrea,
																	 $as_titestrea,$ai_calestrea,$ad_fecgraestrea,
																	 $as_escval,$ad_feciniact,$ad_fecfinact,$as_desestrea,
																	 $as_aprestrea,$as_anoaprestrea,$as_horestrea,$aa_seguridad);
					}
				}
				else
				{
					$this->io_mensajes->message("El Estudio Realizado ya existe, no lo puede incluir.");
				}
				break;
							
			case "TRUE":
				if(($this->uf_select_estudiorealizado($as_codper,$ai_codestrea)))
				{
					$lb_valido=$this->uf_update_estudiorealizado($as_codper,$ai_codestrea,$as_tipestrea,$as_insestrea,
																 $as_titestrea,$ai_calestrea,$ad_fecgraestrea,
																 $as_escval,$ad_feciniact,$ad_fecfinact,$as_desestrea,
																 $as_aprestrea,$as_anoaprestrea,$as_horestrea,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Estudio Realizado no existe, no lo puede actualizar.");
				}
				break;
		}		
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_estudiorealizado($as_codper,$ai_codestrea,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_estudiorealizado
		//		   Access: public (sigesp_snorh_d_estudiorealizado)
		//	    Arguments: as_codper  // Código de Personal
		//				   ai_codestrea  // Código de Estudio Ralizado
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina el estudio realizado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM sno_estudiorealizado ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND codestrea='".$ai_codestrea."'";
				
       	$this->io_sql->begin_transaction();
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Estudio Realizado MÉTODO->uf_delete_estudiorealizado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Estudio realizado ".$ai_codestrea." asociado al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Estudio realizado fue Eliminado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Estudio Realizado MÉTODO->uf_delete_estudiorealizado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
    }// end function uf_delete_estudiorealizado                                                                                                                                                                                                            
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>