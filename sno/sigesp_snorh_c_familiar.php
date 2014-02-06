<?php
class sigesp_snorh_c_familiar
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_familiar()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_diaferiado
		//		   Access: public (sigesp_snorh_d_familiar)
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
	}// end function sigesp_snorh_c_familiar
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_familiar)
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
	function uf_select_familiar($as_codper, $as_cedfam)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_familiar
		//		   Access: private
		//	    Arguments: as_codper  // Código de Personal
		//				   as_cedfam  // Cédula del Familiar
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el familiar está registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$lb_existe=true;
		$ls_sql="SELECT cedfam ".
		        "  FROM sno_familiar ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND cedfam='".$as_cedfam."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Familiar MÉTODO->uf_select_familiar ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_familiar
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_familiar($as_codper,$as_cedfam,$as_nomfam,$as_apefam,$as_sexfam,$ad_fecnacfam,$as_nexfam,$ai_estfam,
								$ai_hcfam,$ai_hcmfam,$ai_hijesp,$ai_bonjug,$as_cedula,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_familiar
		//		   Access: private
		//	    Arguments: as_codper  // Código del Personal
		//				   as_cedfam  // Cedula
		//				   as_nomfam  // Nombre
		//				   as_apefam  // Apellido
		//				   as_sexfam  // Sexo
		//				   ad_fecnacfam  // Fecha Nacimiento
		//				   as_nexfam  // Nexo 
		//				   ai_estfam  // Estudio del familiar
		//				   ai_hcfam  // si el familiar tiene hc
		//				   ai_hcmfam //  si el personal tiene hcm
		//                 ai_hijesp // indica si es un hijo especial
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el familiar
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_familiar".
				"(codemp,codper,cedfam,nomfam,apefam,sexfam,fecnacfam,nexfam,estfam,hcfam,hcmfam,hijesp,estbonjug,cedula)VALUES".
				"('".$this->ls_codemp."','".$as_codper."','".$as_cedfam."','".$as_nomfam."','".$as_apefam."',".
				"'".$as_sexfam."','".$ad_fecnacfam."','".$as_nexfam."','".$ai_estfam."','".$ai_hcfam."','".$ai_hcmfam."','".$ai_hijesp."', ".
				"'".$ai_bonjug."','".$as_cedula."')";
	
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Familiar MÉTODO->uf_insert_familiar ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Familiar ".$as_cedfam." asociado al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Familiar fue Registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Familiar MÉTODO->uf_insert_familiar ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_familiar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_familiar($as_codper,$as_cedfam,$as_nomfam,$as_apefam,$as_sexfam,$ad_fecnacfam,$as_nexfam,$ai_estfam,
								$ai_hcfam,$ai_hcmfam,$ai_hijesp,$ai_bonjug,$as_cedula,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_familiar
		//		   Access: private
		//	    Arguments: as_codper  // Código del Personal
		//				   as_cedfam  // Cedula
		//				   as_nomfam  // Nombre
		//				   as_apefam  // Apellido
		//				   as_sexfam  // Sexo
		//				   ad_fecnacfam  // Fecha Nacimiento
		//				   as_nexfam  // Nexo 
		//				   ai_estfam  // Estudio del familiar
		//				   ai_hcfam  // si el familiar tiene hc
		//				   ai_hcmfam //  si el personal tiene hcm
		//                 ai_hijesp // indica si es un hijo especial
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el familiar
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_familiar ".
				"   SET nomfam='".$as_nomfam."', ".
				"		apefam='".$as_apefam."', ".
				"		sexfam='".$as_sexfam."', ".
				"		fecnacfam='".$ad_fecnacfam."', ".
				"		nexfam='".$as_nexfam."', ".
				"		estfam='".$ai_estfam."', ".
				"		hcfam='".$ai_hcfam."', ".
				"		hcmfam='".$ai_hcmfam."', ".
				"		hijesp='".$ai_hijesp."', ".
				"       estbonjug='".$ai_bonjug."',  ".
				"       cedula='".$as_cedula."'  ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND cedfam='".$as_cedfam."'";
				
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Familiar MÉTODO->uf_update_familiar ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Familiar ".$as_cedfam." asociado al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Familiar fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
	        	$this->io_mensajes->message("CLASE->Familiar MÉTODO->uf_update_familiar ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_familiar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codper,$as_cedfam,$as_nomfam,$as_apefam,$as_sexfam,$ad_fecnacfam,$as_nexfam,$ai_estfam,
						$ai_hcfam,$ai_hcmfam,$ai_hijesp,$ai_bonjug,$as_cedula,$aa_seguridad)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_familiar)
		//	    Arguments: as_codper  // Código del Personal
		//				   as_cedfam  // Cedula
		//				   as_nomfam  // Nombre
		//				   as_apefam  // Apellido
		//				   as_sexfam  // Sexo
		//				   ad_fecnacfam  // Fecha Nacimiento
		//				   as_nexfam  // Nexo 
		//				   ai_estfam // Estudio del familiar 
		//				   ai_hcfam  // si el familiar tiene hc
		//				   ai_hcmfam //  si el personal tiene hcm
		//                 ai_hijesp // indica si es un hijo especial
		//                 ai_bonjug // indica si el hijo recibe bono de juguete
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el familiar
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ad_fecnacfam=$this->io_funciones->uf_convertirdatetobd($ad_fecnacfam);
		$lb_valido=false;		
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_familiar($as_codper,$as_cedfam)===false)
				{
					$lb_valido=$this->uf_insert_familiar($as_codper,$as_cedfam,$as_nomfam,$as_apefam,$as_sexfam,$ad_fecnacfam,
														 $as_nexfam,$ai_estfam,$ai_hcfam,$ai_hcmfam,$ai_hijesp,$ai_bonjug,
														 $as_cedula,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Familiar ya existe, no lo puede incluir.");
				}
				break;
							
			case "TRUE":
				if(($this->uf_select_familiar($as_codper,$as_cedfam)))
				{
					$lb_valido=$this->uf_update_familiar($as_codper,$as_cedfam,$as_nomfam,$as_apefam,$as_sexfam,$ad_fecnacfam,
														 $as_nexfam,$ai_estfam,$ai_hcfam,$ai_hcmfam,$ai_hijesp,$ai_bonjug,
														 $as_cedula,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Familiar no existe, no lo puede actualizar.");
				}
				break;
		}		
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_familiar($as_codper,$as_cedfam,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_familiar
		//		   Access: public (sigesp_snorh_d_familiar)
		//	    Arguments: as_codper  // Código del Personal
		//				   as_cedfam  // Cedula
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina el familiar
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_familiar ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND cedfam='".$as_cedfam."'";
				
       	$this->io_sql->begin_transaction();
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Familiar MÉTODO->uf_delete_familiar ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Elimino el Familiar ".$as_cedfam." asociado al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Familiar fue Eliminado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
 		       	$this->io_mensajes->message("CLASE->Familiar MÉTODO->uf_delete ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
    }// end function uf_delete_familiar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_hijosmenores($as_codper,$ai_edad,$ad_hasta,&$li_total)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_hijosmenores
		//		   Access: public (sigesp_snorh_d_familiar)
		//	    Arguments: as_codper  // Código de Personal
		//				   ai_edad  // edad de los hijos 
		//				   ad_hasta  // fecha hasta donde de quiere calcular la edad
		//				   li_total  // cantidad de hijos menores a ai_edad
		//	      Returns: lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Funcion que obtiene la cantidad de hijos del personal menores a la edad 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total=0;
		$ls_sql="SELECT fecnacfam ".
		        "  FROM sno_familiar ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codper='".$as_codper."' ".
				"   AND nexfam='H' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
 		    $this->io_mensajes->message("CLASE->Familiar MÉTODO->uf_load_hijosmenores ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ld_fecnacfam=$row["fecnacfam"];
				$li_edad=intval(((strtotime($ad_hasta)-strtotime($ld_fecnacfam))/86400)/365.25);				
				if(intval($li_edad)<intval($ai_edad))
				{
					$li_total=$li_total+1;
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_hijosmenores
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_nexofamiliar($as_codper,$as_nexo,&$ai_resultado)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_nexofamiliar
		//		   Access: public (sigesp_sno_c_evaluador)
		//	    Arguments: as_codper  // Código de Personal
		//				   as_nexo  //  Nexo por el cual voy a verificar
		//				   ai_resultado  // resultado true ó false 
		//	      Returns: lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Funcion que verifica si el personal tiene registrado un familiar con el nexo  especificado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 04/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_resultado=false;
		$ls_sql="SELECT nexfam ".
		        "  FROM sno_familiar ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codper='".$as_codper."' ".
				"   AND nexfam='".$as_nexo."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
 		    $this->io_mensajes->message("CLASE->Familiar MÉTODO->uf_load_nexofamiliar ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_resultado=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_nexofamiliar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_sexofamiliar($as_codper,$as_nexo,&$ai_resultado)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_sexofamiliar
		//		   Access: public (sigesp_sno_c_evaluador)
		//	    Arguments: as_codper  // Código de Personal
		//				   as_nexo  //  Nexo por el cual voy a verificar
		//				   ai_resultado  // resultado true ó false 
		//	      Returns: lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Funcion que obtiene el sexo de un familiar
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 04/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_resultado=false;
		$ls_sql="SELECT sexfam ".
		        "  FROM sno_familiar ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codper='".$as_codper."' ".
				"   AND nexfam='".$as_nexo."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
 		    $this->io_mensajes->message("CLASE->Familiar MÉTODO->uf_load_nexofamiliar ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_resultado=$row["sexfam"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_sexofamiliar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_hcfamiliar($as_codper,$as_nexo,&$ai_resultado)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_hcfamiliar
		//		   Access: public (sigesp_sno_c_evaluador)
		//	    Arguments: as_codper  // Código de Personal
		//				   as_nexo  //  Nexo por el cual voy a verificar
		//				   ai_resultado  // resultado true ó false 
		//	      Returns: lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Funcion que obtiene si un familiar tiene hc ó no
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 04/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_resultado=false;
		$ls_sql="SELECT hcfam ".
		        "  FROM sno_familiar ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codper='".$as_codper."' ".
				"   AND nexfam='".$as_nexo."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
 		    $this->io_mensajes->message("CLASE->Familiar MÉTODO->uf_load_nexofamiliar ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				switch ($row["hcfam"])
				{
					case "":
						$ai_resultado=false;
						break;
				
					case "0":
						$ai_resultado=false;
						break;
				
					case "1":
						$ai_resultado=true;
						break;
				} 
				
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_hcfamiliar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_hcmfamiliar($as_codper,$as_nexo,&$ai_resultado)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_hcmfamiliar
		//		   Access: public (sigesp_sno_c_evaluador)
		//	    Arguments: as_codper  // Código de Personal
		//				   as_nexo  //  Nexo por el cual voy a verificar
		//				   ai_resultado  // resultado true ó false 
		//	      Returns: lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Funcion que obtiene si un familiar tiene hcm ó no
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 04/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_resultado=false;
		$ls_sql="SELECT hcmfam ".
		        "  FROM sno_familiar ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codper='".$as_codper."' ".
				"   AND nexfam='".$as_nexo."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
 		    $this->io_mensajes->message("CLASE->Familiar MÉTODO->uf_load_nexofamiliar ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				switch ($row["hcmfam"])
				{
					case "":
						$ai_resultado=false;
						break;
				
					case "0":
						$ai_resultado=false;
						break;
				
					case "1":
						$ai_resultado=true;
						break;
				} 
				
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_hcmfamiliar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_totalfamiliar($as_codper,$as_nexo,$ai_edaddesde,$ai_edadhasta,$ad_hasta,&$ai_resultado)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_totalfamiliar
		//		   Access: public (sigesp_sno_c_evaluador)
		//	    Arguments: as_codper  // Código de Personal
		//				   as_nexo  //  Nexo por el cual voy a verificar
		//				   ai_edaddesde  // edad del familiar desde
		//				   ai_edadhasta  // edad del familiar hasta
		//				   ad_hasta  // fecha hasta donde de quiere calcular la edad
		//				   ai_resultado  // resultado true ó false 
		//	      Returns: lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Funcion que obtiene el total de familiares que tienen la edad comprendida en el rango
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_resultado=0;
		$ls_sql="SELECT fecnacfam ".
		        "  FROM sno_familiar ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codper='".$as_codper."' ".
				"   AND nexfam='".$as_nexo."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
 		    $this->io_mensajes->message("CLASE->Familiar MÉTODO->uf_load_totalfamiliar ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ld_fecnacfam=$row["fecnacfam"];
				$li_edad=intval(((strtotime($ad_hasta)-strtotime($ld_fecnacfam))/86400)/365.25);				
				if((intval($li_edad)>=intval($ai_edaddesde))&&(intval($li_edad)<=intval($ai_edadhasta)))
				{
					$ai_resultado=$ai_resultado+1;
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_totalfamiliar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_hijosmenores_estudiantes($as_codper,$ai_edadesde,$ai_edahasta,$ad_hasta,&$li_total)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_hijosmenores_estudiantes
		//		   Access: public 
		//	    Arguments: as_codper  // Código de Personal
		//				   ai_edadesde  // edad desde de los hijos 
		//				   ai_edahasta  // edad hasta de los hijos 
		//				   ad_hasta  // fecha hasta donde de quiere calcular la edad
		//				   li_total  // cantidad de hijos menores a ai_edad
		//	      Returns: lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Funcion que obtiene la cantidad de hijos del personal menores a la edad 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total=0;
		$ls_sql="SELECT fecnacfam ".
		        "  FROM sno_familiar ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codper='".$as_codper."' ".
				"   AND nexfam='H' ".
				"   AND estfam='1' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
 		    $this->io_mensajes->message("CLASE->Familiar MÉTODO->uf_load_hijosmenores_estudiantes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ld_fecnacfam=$row["fecnacfam"];
				$li_edad=intval(((strtotime($ad_hasta)-strtotime($ld_fecnacfam))/86400)/365.25);				
				if((intval($li_edad)>=intval($ai_edadesde))&&(intval($li_edad)<=intval($ai_edahasta)))
				{
					$li_total=$li_total+1;
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_hijosmenores_estudiantes
	
//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_hijos_especiales($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_hijos_especiales
		//		   Access: public 
		//	    Arguments: as_codper  // Código de Personal
		//	      Returns: lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Funci María Beatriz Unda
		// Fecha Creación: 17/11/2008						Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total=0;
		$ls_sql="SELECT hijesp ".
		        "  FROM sno_familiar ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codper='".$as_codper."' ".
				"   AND nexfam='H' ".
				"   AND hijesp='1' ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
 		    $this->io_mensajes->message("CLASE->Familiar MÉTODO->uf_select_hijos_especiales ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$li_total=$this->io_sql->num_rows($rs_data);
		}
		return $li_total;
	}// end function uf_select_hijos_especiales
	//-----------------------------------------------------------------------------------------------------------------------------------	
//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_hijos_bono_juguetes($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_hijos_bono_juguetes
		//		   Access: public 
		//	    Arguments: as_codper  // Código de Personal
		//	      Returns: lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Funci María Beatriz Unda
		// Fecha Creación: 04/03/2009						Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total=0;
		$ls_sql="SELECT estbonjug ".
		        "  FROM sno_familiar ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codper='".$as_codper."' ".
				"   AND nexfam='H' ".
				"   AND estbonjug='1' ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
 		    $this->io_mensajes->message("CLASE->Familiar MÉTODO->uf_select_hijos_bono_juguetes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$li_total=$this->io_sql->num_rows($rs_data);
		}
		return $li_total;
	}// end function uf_select_hijos_bono_juguetes
	//-----------------------------------------------------------------------------------------------------------------------------------		
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>