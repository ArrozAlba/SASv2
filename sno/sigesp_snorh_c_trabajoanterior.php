<?php
class sigesp_snorh_c_trabajoanterior
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_trabajoanterior()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_trabajoanterior
		//		   Access: public (sigesp_snorh_d_profesion)
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
		require_once("sigesp_snorh_c_personal.php");
		$this->io_personal= new sigesp_snorh_c_personal();
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_trabajoanterior)
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
	function uf_select_trabajoanterior($as_codper, $ai_codtraant)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_trabajoanterior
		//		   Access: public (sigesp_snorh_d_trabajoanterior)
		//	    Arguments: as_codper // Código de Personal
		//			       ai_codtraant  // Código de trabajo anterior
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el trabajo anterior está registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codtraant ".
				"  FROM sno_trabajoanterior ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND codtraant='".$ai_codtraant."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Trabajo Anterior MÉTODO->uf_select_trabajoanterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_trabajoanterior
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_correlativo($as_codper, &$ai_codtraant)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_correlativo
		//		   Access: private (uf_guardar) 
		//	    Arguments: as_codper  // código del personal
		//				   ai_codtraant  // código de trabajo anterior
		//	      Returns: lb_valido True si lo obtuvo correctamente ó False si hubo error
		//	  Description: Funcion que busca el correlativo del último trabajo anterior y genera el nuevo correlativo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_codtraant=1;
		$ls_sql="SELECT codtraant as codigo ".
				"  FROM sno_trabajoanterior ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				" ORDER BY codtraant DESC ";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Trabajo Anterior MÉTODO->uf_load_correlativo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_codtraant=intval($row["codigo"]+1);
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_load_correlativo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_trabajoanterior($as_codper,$ai_codtraant,$as_emptraant,$as_ultcartraant,$ai_ultsuetraant,
				 					   $ad_fecingtraant,$ad_fecrettraant,$as_emppubtraant,$as_codded,$ai_anolab,$ai_meslab,
									   $ai_dialab,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_trabajoanterior
		//		   Access: private
		//	    Arguments: as_codper // Código de Personal
		//			       ai_codtraant  // Código de trabajo anterior
		//			       as_emptraant  // empresa
		//			       as_ultcartraant  // último cargo
		//			       ai_ultsuetraant  // último sueldo
		//			       ad_fecingtraant  // Fecha de ingreso del trabajo
		//			       ad_fecrettraant  // Fecha de Retiro del trabajo
		//			       as_emppubtraant  // Si la empresa fué pública
		//			       as_codded  // Código de Dedicación
		//			       ai_anolab  // Años Laborados
		//			       ai_meslab  // Meses Laborados
		//			       ai_dialab  // Días Laborados
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el trabajo anterior
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_trabajoanterior".
				"(codemp,codper,codtraant,emptraant,ultcartraant,ultsuetraant,fecingtraant,fecrettraant,emppubtraant,".
				"codded,anolab,meslab,dialab) VALUES ('".$this->ls_codemp."','".$as_codper."',".$ai_codtraant.",'".$as_emptraant."',".
				"'".$as_ultcartraant."',".$ai_ultsuetraant.",'".$ad_fecingtraant."','".$ad_fecrettraant."','".$as_emppubtraant."',".
				"'".$as_codded."',".$ai_anolab.",".$ai_meslab.",".$ai_dialab.")";
				
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Trabajo Anterior MÉTODO->uf_insert_trabajoanterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Trabajo anterior ".$ai_codtraant." asociada al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Trabajo Anterior fue Registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Trabajo Anterior MÉTODO->uf_insert_trabajoanterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_trabajoanterior
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_trabajoanterior($as_codper,$ai_codtraant,$as_emptraant,$as_ultcartraant,$ai_ultsuetraant,
				   					   $ad_fecingtraant,$ad_fecrettraant,$as_emppubtraant,$as_codded,$ai_anolab,$ai_meslab,
									   $ai_dialab,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_trabajoanterior
		//		   Access: private
		//	    Arguments: as_codper // Código de Personal
		//			       ai_codtraant  // Código de trabajo anterior
		//			       as_emptraant  // empresa
		//			       as_ultcartraant  // último cargo
		//			       ai_ultsuetraant  // último sueldo
		//			       ad_fecingtraant  // Fecha de ingreso del trabajo
		//			       ad_fecrettraant  // Fecha de Retiro del trabajo
		//			       as_emppubtraant  // Si la empresa fué pública
		//			       as_codded  // Código de Dedicación
		//			       ai_anolab  // Años Laborados
		//			       ai_meslab  // Meses Laborados
		//			       ai_dialab  // Días Laborados
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el estudio realizado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_trabajoanterior ".
				"   SET emptraant='".$as_emptraant."', ".
				"       ultcartraant='".$as_ultcartraant."', ".
				"  		ultsuetraant=".$ai_ultsuetraant.", ".
				"  		fecingtraant='".$ad_fecingtraant."', ".
				"  		fecrettraant='".$ad_fecrettraant."', ".
				"  		emppubtraant='".$as_emppubtraant."', ".
				"  		codded='".$as_codded."', ".
				"  		anolab=".$ai_anolab.", ".
				"  		meslab=".$ai_meslab.", ".
				"  		dialab=".$ai_dialab." ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND codtraant=".$ai_codtraant."";
		
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{

			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Trabajo Anterior MÉTODO->uf_update_trabajoanterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Trabajo anterior ".$ai_codtraant." asociada al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Trabajo Anterior fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Trabajo Anterior MÉTODO->uf_update_trabajoanterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_trabajoanterior
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codper,$ai_codtraant,$as_emptraant,$as_ultcartraant,$ai_ultsuetraant, 
						$ad_fecingtraant,$ad_fecrettraant,$as_emppubtraant,$as_codded,$ai_anolab,$ai_meslab,$ai_dialab,
						$aa_seguridad)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_trabajoanterior)
		//	    Arguments: as_codper // Código de Personal
		//			       ai_codtraant  // Código de trabajo anterior
		//			       as_emptraant  // empresa
		//			       as_ultcartraant  // último cargo
		//			       ai_ultsuetraant  // último sueldo
		//			       ad_fecingtraant  // Fecha de ingreso del trabajo
		//			       ad_fecrettraant  // Fecha de Retiro del trabajo
		//			       as_emppubtraant  // Si la empresa fué pública
		//			       as_codded  // Código de Dedicación
		//			       ai_anolab  // Años Laborados
		//			       ai_meslab  // Meses Laborados
		//			       ai_dialab  // Días Laborados
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que actualiza el estudio realizado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_ultsuetraant=str_replace(".","",$ai_ultsuetraant);
		$ai_ultsuetraant=str_replace(",",".",$ai_ultsuetraant);				
		$ad_fecingtraant=$this->io_funciones->uf_convertirdatetobd($ad_fecingtraant);
		$ad_fecrettraant=$this->io_funciones->uf_convertirdatetobd($ad_fecrettraant);
		$lb_valido=false;		
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_trabajoanterior($as_codper,$ai_codtraant)===false)
				{
					$lb_valido=$this->uf_load_correlativo($as_codper,$ai_codtraant);
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_trabajoanterior($as_codper,$ai_codtraant,$as_emptraant,$as_ultcartraant,$ai_ultsuetraant,
															 	$ad_fecingtraant,$ad_fecrettraant,$as_emppubtraant,$as_codded,$ai_anolab,$ai_meslab,
																$ai_dialab,$aa_seguridad);
					}
				}
				else
				{
					$this->io_mensajes->message("El Trabajo Anterior ya existe, no lo puede incluir.");
				}
				break;
							
			case "TRUE":
				if(($this->uf_select_trabajoanterior($as_codper,$ai_codtraant)))
				{
					$lb_valido=$this->uf_update_trabajoanterior($as_codper,$ai_codtraant,$as_emptraant,$as_ultcartraant,$ai_ultsuetraant,
															 	$ad_fecingtraant,$ad_fecrettraant,$as_emppubtraant,$as_codded,$ai_anolab,
																$ai_meslab,$ai_dialab,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Trabajo Anterior no existe, no lo puede actualizar.");
				}
				break;
		}
		
		if($lb_valido)
		{
		    $lb_anofijo=0;
			$lb_anofijo=$this->io_personal->uf_select_anotrabajoantfijo($as_codper);
			$lb_anocont=0;
			$lb_anocont=$this->io_personal->uf_select_anotrabajoantcontratado($as_codper);	
			
			$ls_sql="	UPDATE sno_personal                   ".
			        "      SET anoservprefijo=".$lb_anofijo.", ".
					"          anoservprecont=".$lb_anocont." ".					
					"    WHERE codper='".$as_codper."'        ";	
									
			$this->io_sql->begin_transaction();
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
	
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Trabajo Anterior MÉTODO->uf_update_años ERROR->".
				                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}	
			if($lb_valido)
			{					
				$this->io_sql->commit();
			}	
		}			
		
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_trabajoanterior($as_codper,$ai_codtraant,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_trabajoanterior
		//		   Access: public (sigesp_snorh_d_trabajoanterior)
		//	    Arguments: as_codper // Código de Personal
		//			       ai_codtraant  // Código de trabajo anterior
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina el trabajo anterior
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_trabajoanterior ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND codtraant=".$ai_codtraant."";
				
       	$this->io_sql->begin_transaction();
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Trabajo Anterior MÉTODO->uf_delete_trabajoanterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Trabajo anterior ".$ai_codtraant." asociada al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Trabajo Anterior fue Eliminado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Trabajo Anterior MÉTODO->uf_delete_trabajoanterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
			if($lb_valido)
			{
				$lb_anofijo=0;
				$lb_anofijo=$this->io_personal->uf_select_anotrabajoantfijo($as_codper);
				$lb_anocont=0;
				$lb_anocont=$this->io_personal->uf_select_anotrabajoantcontratado($as_codper);	
				
				$ls_sql="	UPDATE sno_personal                   ".
						"      SET anoservprefijo=".$lb_anofijo.", ".
						"          anoservprecont=".$lb_anocont." ".					
						"    WHERE codper='".$as_codper."'        ";	
										
				$this->io_sql->begin_transaction();
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
		
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Trabajo Anterior MÉTODO->uf_update_años ERROR->".
												$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_sql->rollback();
				}	
				if($lb_valido)
				{					
					$this->io_sql->commit();
				}	
		}			
		}
		return $lb_valido;
    }
	//-----------------------------------------------------------------------------------------------------------------------------------

	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>