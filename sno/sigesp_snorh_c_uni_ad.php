<?php
class sigesp_snorh_c_uni_ad
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_personal;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_uni_ad()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_uni_ad
		//		   Access: public (sigesp_snorh_d_uni_ad)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/05/2006 								Fecha Última Modificación : 
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
	}// end function sigesp_snorh_c_uni_ad
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_uni_ad)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/05/2006 								Fecha Última Modificación : 
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
	function uf_select_unidadadministrativa($as_coduniadm)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_unidadadministrativa
		//		   Access: private
 		//	    Arguments: as_coduniadm  // código de la unidad administrativa 
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la profesión está registrada
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/05/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
	    $ls_minorguniadm=substr($as_coduniadm,0,4);
		$ls_ofiuniadm=substr($as_coduniadm,4,2);
		$ls_uniuniadm=substr($as_coduniadm,6,2);
		$ls_depuniadm=substr($as_coduniadm,8,2);
		$ls_prouniadm=substr($as_coduniadm,10,2);
		$ls_sql= "SELECT codemp ".
				 "  FROM sno_unidadadmin ".
				 " WHERE codemp='".$this->ls_codemp."' ".
				 "   AND minorguniadm='".$ls_minorguniadm."' ".
				 "   AND ofiuniadm='".$ls_ofiuniadm."' ".
				 "   AND uniuniadm='".$ls_uniuniadm."' ".
				 "   AND depuniadm='".$ls_depuniadm."' ".
				 "   AND prouniadm='".$ls_prouniadm."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Unidad Administrativa MÉTODO->uf_select_unidadadministrativa ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_unidadadministrativa
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_unidadadministrativa($as_coduniadm,$as_desuniadm,$as_codpro,$as_estcla,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_unidadadministrativa
		//		   Access: private
		//	    Arguments: as_coduniadm  // código de la unidad administrativa
		//				   as_desuniadm  // descripción de la unidad administrativa
		//				   as_codpro  // Estructura programática 5 niveles
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_unidadadmin
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/05/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	    $ls_minorguniadm=substr($as_coduniadm,0,4);
		$ls_ofiuniadm=substr($as_coduniadm,4,2);
		$ls_uniuniadm=substr($as_coduniadm,6,2);
		$ls_depuniadm=substr($as_coduniadm,8,2);
		$ls_prouniadm=substr($as_coduniadm,10,2);
		$ls_sql= "INSERT INTO sno_unidadadmin (codemp,minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm,desuniadm,codprouniadm,".
				 "codproviauniadm,estcla)VALUES('".$this->ls_codemp."','".$ls_minorguniadm."','".$ls_ofiuniadm."','".$ls_uniuniadm."',".
				 "'".$ls_depuniadm."','".$ls_prouniadm."','".$as_desuniadm."','".$as_codpro."','','".$as_estcla."') ";
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Unidad Administrativa MÉTODO->uf_insert_unidadadministrativa ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Unidad administrativa ".$as_coduniadm;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Unidad Administrativa fue Registrada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Unidad Administrativa MÉTODO->uf_insert_unidadadministrativa ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_unidadadministrativa
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_unidadadministrativa($as_coduniadm,$as_desuniadm,$as_codpro,$as_estcla,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//    	 Function: uf_update_unidadadministrativa
		//		   Access: private
		//	    Arguments: as_coduniadm  // código de la unidad administrativa
		//				   as_desuniadm  // descripción de la unidad administrativa
		//				   as_codpro  // Estructura programática  5 niveles
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_unidadadmin
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/05/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	    $ls_minorguniadm=substr($as_coduniadm,0,4);
		$ls_ofiuniadm=substr($as_coduniadm,4,2);
		$ls_uniuniadm=substr($as_coduniadm,6,2);
		$ls_depuniadm=substr($as_coduniadm,8,2);
		$ls_prouniadm=substr($as_coduniadm,10,2);
		$ls_sql= "UPDATE sno_unidadadmin ".
				 "   SET desuniadm='".$as_desuniadm."', ".
				 "       codprouniadm='".$as_codpro."', ".
				 "       estcla ='".$as_estcla."'".
				 " WHERE codemp='".$this->ls_codemp."' ".
				 "   AND minorguniadm='".$ls_minorguniadm."' ".
				 "   AND ofiuniadm='".$ls_ofiuniadm."' ".
				 "   AND uniuniadm='".$ls_uniuniadm."' ".
				 "   AND depuniadm='".$ls_depuniadm."' ".
				 "   AND prouniadm='".$ls_prouniadm."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Unidad Administrativa MÉTODO->uf_update_unidadadministrativa ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			////////////////////////////////         SEGURIDAD               //////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Unidad Administrativa ".$as_coduniadm;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Unidad Administrativa fue Actualizada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Unidad Administrativa MÉTODO->uf_update_unidadadministrativa ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_unidadadministrativa
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_coduniadm,$as_desuniadm,$as_codpro,$as_estcla,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_uni_ad)
		//	    Arguments: as_coduniadm  // código de la unidad administrativa
		//				   as_desuniadm  // descripción de la unidad administrativa
		//				   as_codpro  // Estructura programática 5 Niveles
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sno_profesión
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/05/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_unidadadministrativa($as_coduniadm)===false)
				{
					$lb_valido=$this->uf_insert_unidadadministrativa($as_coduniadm,$as_desuniadm,$as_codpro,$as_estcla,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Unidad Administrativa ya existe, no la puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_unidadadministrativa($as_coduniadm)))
				{
					$lb_valido=$this->uf_update_unidadadministrativa($as_coduniadm,$as_desuniadm,$as_codpro,$as_estcla,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Unidad Administrativa no existe, no la puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_personalnomina($as_coduniadm)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_personalnomina
		//		   Access: private
		//	    Arguments: as_coduniadm  // código de la unidad administrativa
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el personal tiene asociado esta unidad administrativa
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/05/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_minorguniadm=substr($as_coduniadm,0,4);
		$ls_ofiuniadm=substr($as_coduniadm,4,2);
		$ls_uniuniadm=substr($as_coduniadm,6,2);
		$ls_depuniadm=substr($as_coduniadm,8,2);
		$ls_prouniadm=substr($as_coduniadm,10,2);
		$ls_sql="SELECT codper ".
				"  FROM sno_personalnomina ".
				" WHERE codemp='".$this->ls_codemp."'".
				 "  AND minorguniadm='".$ls_minorguniadm."' ".
				 "  AND ofiuniadm='".$ls_ofiuniadm."' ".
				 "  AND uniuniadm='".$ls_uniuniadm."' ".
				 "  AND depuniadm='".$ls_depuniadm."' ".
				 "  AND prouniadm='".$ls_prouniadm."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Unidad Administrativa MÉTODO->uf_select_personalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_select_personalnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cestaticunidadadm($as_coduniadm)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cestaticunidadadm
		//		   Access: private
		//	    Arguments: as_coduniadm  // código de la unidad administrativa
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el personal tiene asociado esta unidad administrativa
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/05/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_minorguniadm=substr($as_coduniadm,0,4);
		$ls_ofiuniadm=substr($as_coduniadm,4,2);
		$ls_uniuniadm=substr($as_coduniadm,6,2);
		$ls_depuniadm=substr($as_coduniadm,8,2);
		$ls_prouniadm=substr($as_coduniadm,10,2);
		$ls_sql="SELECT codemp ".
				"  FROM sno_cestaticunidadadm ".
				" WHERE codemp='".$this->ls_codemp."'".
				 "  AND minorguniadm='".$ls_minorguniadm."' ".
				 "  AND ofiuniadm='".$ls_ofiuniadm."' ".
				 "  AND uniuniadm='".$ls_uniuniadm."' ".
				 "  AND depuniadm='".$ls_depuniadm."' ".
				 "  AND prouniadm='".$ls_prouniadm."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Unidad Administrativa MÉTODO->uf_select_cestaticunidadadm ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_select_cestaticunidadadm
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_unidadadministrativa($as_coduniadm,$aa_seguridad)
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
        if (($this->uf_select_personalnomina($as_coduniadm)===false)&&
			($this->uf_select_cestaticunidadadm($as_coduniadm)===false))
		{
			$ls_minorguniadm=substr($as_coduniadm,0,4);
			$ls_ofiuniadm=substr($as_coduniadm,4,2);
			$ls_uniuniadm=substr($as_coduniadm,6,2);
			$ls_depuniadm=substr($as_coduniadm,8,2);
			$ls_prouniadm=substr($as_coduniadm,10,2);
			$ls_sql="DELETE ".
			        "  FROM sno_unidadadmin ".
				    " WHERE codemp='".$this->ls_codemp."' ".
				    "   AND minorguniadm='".$ls_minorguniadm."' ".
				    "   AND ofiuniadm='".$ls_ofiuniadm."' ".
				    "   AND uniuniadm='".$ls_uniuniadm."' ".
				    "   AND depuniadm='".$ls_depuniadm."' ".
				    "   AND prouniadm='".$ls_prouniadm."' ";
        	$this->io_sql->begin_transaction();
		   	$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Unidad Administrativa MÉTODO->uf_delete_unidadadministrativa ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la Unidad Administrativa ".$as_coduniadm;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{	
					$this->io_mensajes->message("La Unidad Administrativa fue Eliminada.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
        			$this->io_mensajes->message("CLASE->Unidad Administrativa MÉTODO->uf_delete_unidadadministrativa ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_sql->rollback();
				}
			}
		} 
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("No se puede eliminar la Unidad Administrativa, hay personal relacionado a esta ó Cesta Ticket.");
		}       
		return $lb_valido;
    }// end function uf_delete_unidadadministrativa
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_unidadadministrativa_historico($as_coduniadm,$as_desuniadm,$as_codpro,$as_estcla,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//    	 Function: uf_update_unidadadministrativa_historico
		//		   Access: private
		//	    Arguments: as_coduniadm  // código de la unidad administrativa
		//				   as_desuniadm  // descripción de la unidad administrativa
		//				   as_codpro  // Estructura programática  5 niveles
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_unidadadmin
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;

	    $ls_minorguniadm=substr($as_coduniadm,0,4);
		$ls_ofiuniadm=substr($as_coduniadm,4,2);
		$ls_uniuniadm=substr($as_coduniadm,6,2);
		$ls_depuniadm=substr($as_coduniadm,8,2);
		$ls_prouniadm=substr($as_coduniadm,10,2);
		$ls_sql= "UPDATE sno_hunidadadmin ".
				 "   SET codprouniadm='".$as_codpro."' ".
				 " WHERE codemp='".$this->ls_codemp."' ".
				 "   AND minorguniadm='".$ls_minorguniadm."' ".
				 "   AND ofiuniadm='".$ls_ofiuniadm."' ".
				 "   AND uniuniadm='".$ls_uniuniadm."' ".
				 "   AND depuniadm='".$ls_depuniadm."' ".
				 "   AND prouniadm='".$ls_prouniadm."' ".
				 "   AND codnom='".$_SESSION["la_nomina"]["codnom"]."'".
				 "	 AND anocur='".$_SESSION["la_nomina"]["anocurnom"]."'".
				 "	 AND codperi='".$_SESSION["la_nomina"]["peractnom"]."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Unidad Administrativa MÉTODO->uf_update_unidadadministrativa ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			$ls_sql= "UPDATE sno_thunidadadmin ".
					 "   SET codprouniadm='".$as_codpro."',estcla='".$as_estcla."' ".
					 " WHERE codemp='".$this->ls_codemp."' ".
					 
					 "   AND minorguniadm='".$ls_minorguniadm."' ".
					 "   AND ofiuniadm='".$ls_ofiuniadm."' ".
					 "   AND uniuniadm='".$ls_uniuniadm."' ".
					 "   AND depuniadm='".$ls_depuniadm."' ".
					 "   AND prouniadm='".$ls_prouniadm."' ".
					 "   AND codnom='".$_SESSION["la_nomina"]["codnom"]."'".
					 "	 AND anocur='".$_SESSION["la_nomina"]["anocurnom"]."'".
					 "	 AND codperi='".$_SESSION["la_nomina"]["peractnom"]."'";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Unidad Administrativa MÉTODO->uf_update_unidadadministrativa ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
			else
			{
				////////////////////////////////         SEGURIDAD               //////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la Unidad Administrativa ".$as_coduniadm;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{	
					$this->io_mensajes->message("La Unidad Administrativa fue Actualizada.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Unidad Administrativa MÉTODO->uf_update_unidadadministrativa ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_sql->rollback();
				}
			}
		}
		return $lb_valido;
	}// end function uf_update_unidadadministrativa
	//-------------------------------------------------------------------------------------------------------------------------------------
	function uf_validarcierre_gastos_ingreso(&$as_statusg,&$as_statusi)
	{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validarcierre_gastos_ingreso
		//		   Access: private
		//     Argumentos: 
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que buscas los estatus de cierre del presuepuesto de gastos i de ingreso
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 28/08/2008 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT estciespg, estciespi FROM sigesp_empresa where codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->Cierre Periodo MÉTODO->SELECT->uf_validarcierre_gastos_ingreso ERROR->"
			                            .$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$as_statusg= $row["estciespg"];
				$as_statusi= $row["estciespi"];				
			}
		}
		return 	$lb_valido;
	}//fin de uf_validarcierre_gastos_ingreso
//-------------------------------------------------------------------------------------------------------------------------------------
}
?>