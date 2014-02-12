<?php
class sigesp_scb_c_tipofondo
 {
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_id_process;
	var $ls_codemp;
	var $io_dscuentas;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_scb_c_tipofondo()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_scb_c_tipofondo
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once("../../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
	    require_once("../../shared/class_folder/class_fecha.php");		
		$this->io_fecha= new class_fecha();
		require_once("../../shared/class_folder/class_datastore.php");
		require_once("../../shared/class_folder/sigesp_c_generar_consecutivo.php");
		$this->io_keygen= new sigesp_c_generar_consecutivo();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_cxp_c_solicitudpago
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_cxp_p_recepcion.php)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fecha);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_tipofondo(&$as_codtipfon,$as_dentipfon,$ai_porrepfon,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_tipofondo
		//		   Access: private
		//	    Arguments: as_codtipfon // Codigo 
		//				   as_dentipfon // Denominacion
		//				   ai_porrepfon // Porcentaje de para la Reposicion.
		//				   aa_seguridad // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el tipo de fondo en avance
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_auxcodtipfon=$as_codtipfon;
		$lb_valido= $this->io_keygen->uf_verificar_numero_generado("CFG","scb_tipofondo","codtipfon","CFGSCB",4,"","","",&$as_codtipfon);
		$lb_valido=true;
		if($lb_valido)
		{
			$ls_sql="INSERT INTO scb_tipofondo (codemp, codtipfon, dentipfon, porrepfon)".
					"	  VALUES ('".$this->ls_codemp."','".$as_codtipfon."','".$as_dentipfon."',".$ai_porrepfon.")";
			$this->io_sql->begin_transaction();				
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_sql->rollback();
				if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
				{
					$lb_valido=$this->uf_insert_tipofondo(&$as_codtipfon,$as_dentipfon,$ai_porrepfon,$aa_seguridad);
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->TipoFondo MÉTODO->uf_insert_tipofondo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el tipo de fondo en avance ".$as_codtipfon." Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				if($lb_valido)
				{	
					if($ls_auxcodtipfon!=$as_codtipfon)
					{
						$this->io_mensajes->message("Se Asigno el Numero de Registro: ".$as_codtipfon);
					}
					$lb_valido=true;
					$this->io_sql->commit();
					$this->io_mensajes->message("El tipo de fondo ha sido Registrado."); 
				}			
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("Ocurrio un Error al Registrar el tipo de fondo."); 
					$this->io_sql->rollback();
				}
			}
		}
		return $lb_valido;
	}// end function uf_insert_tipofondo
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,&$as_codtipfon,$as_dentipfon,$ai_porrepfon,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_sep_p_solicitud.php)
		//	    Arguments: as_existe    // Fecha de Solicitud
		//				   as_codtipfon // Codigo 
		//				   as_dentipfon // Denominacion
		//				   ai_porrepfon // Porcentaje de para la Reposicion.
		//				   aa_seguridad // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que valida y guarda el tipo de fondo en avance.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;	
		$ai_porrepfon=str_replace(".","",$ai_porrepfon);
		$ai_porrepfon=str_replace(",",".",$ai_porrepfon);
		if($as_existe=="C")
		{
			$as_existe="TRUE";
		}
		else
		{
			$as_existe="FALSE";
		}
		switch ($as_existe)
		{
			case "FALSE":
				$lb_valido=$this->uf_insert_tipofondo(&$as_codtipfon,$as_dentipfon,$ai_porrepfon,$aa_seguridad);
				break;

			case "TRUE":
				$lb_valido=$this->uf_update_tipofondo($as_codtipfon,$as_dentipfon,$ai_porrepfon,$aa_seguridad);
				break;
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_tipofondo($as_codtipfon,$as_dentipfon,$ai_porrepfon,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_tipofondo
		//		   Access: private
		//	    Arguments: as_codtipfon // Codigo 
		//				   as_dentipfon // Denominacion
		//				   ai_porrepfon // Porcentaje de para la Reposicion.
		//				   aa_seguridad // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta la Solicitud de Pagos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE scb_tipofondo ".
				"   SET dentipfon	= '".$as_dentipfon."', ".
				"		porrepfon = ".$ai_porrepfon." ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"	AND codtipfon = '".$as_codtipfon."' ";
		$this->io_sql->begin_transaction();				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->TipoFondo MÉTODO->uf_update_tipofondo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el tipo de fondo ".$as_codtipfon." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El tipo de fondo fue actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("Ocurrio un Error al Actualizar el tipo de fondo."); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_tipofondo
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitud($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_solicitud
		//		   Access: private
		//	    Arguments: as_numsol  //  Número de Solicitud
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la Solicitud de pago Existe
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 26/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT numsol ".
				"  FROM cxp_solicitudes ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsol='".$as_numsol."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_select_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_tipofondo($as_codtipfon,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_tipofondo
		//		   Access: public
		//	    Arguments: as_codtipfon     // Codigo de tipo de fondo
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que elimina la solicitud de Pagos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM scb_tipofondo ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"	AND codtipfon = '".$as_codtipfon."' ";
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->TipoFondo MÉTODO->uf_delete_tipofondo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Elimino el tipo de fondo ".$as_codtipfon." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El tipo de fondo fue Eliminada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("Ocurrio un Error al Eliminar el tipo de fondo."); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_delete_tipofondo
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>