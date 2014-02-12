<?php
class sigesp_sno_c_cargo
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_personalnomina;
	var $ls_codemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_cargo()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_cargo
		//		   Access: public (sigesp_sno_d_cargo)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/02/2006 								Fecha Última Modificación : 
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
		$this->io_personalnomina= new sigesp_sno_c_personalnomina();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
	}// end function sigesp_sno_c_cargo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sno_d_cargo)
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
        unset($this->ls_codemp);
        unset($this->ls_codnom);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cargo($as_codcar)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cargo
		//		   Access: private
		//	    Arguments: as_codcar  // Código de Cargo
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el cargo está registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codcar ".
				"  FROM sno_cargo ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codcar='".$as_codcar."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Cargo MÉTODO->uf_select_cargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_cargo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cargo($as_codcar,$as_descar,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cargo
		//		   Access: private
		//	    Arguments: as_codcar  // código de cargo
		//				   as_descar  // descripción
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el cargo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_cargo(codemp,codnom,codcar,descar)VALUES".
				"('".$this->ls_codemp."','".$this->ls_codnom."','".$as_codcar."','".$as_descar."')";
		$this->io_sql->begin_transaction();				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cargo MÉTODO->uf_insert_cargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el  Cargo ".$as_codcar." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Cargo fue registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Cargo MÉTODO->uf_insert_cargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_cargo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_cargo($as_codcar,$as_descar,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_cargo
		//		   Access: private
		//	    Arguments: as_codcar  // código de cargo
		//				   as_descar  // descripción
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el cargo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_cargo ".
				"   SET descar='".$as_descar."' ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codcar='".$as_codcar."' ";

		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cargo MÉTODO->uf_update_cargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el  Cargo ".$as_codcar." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Cargo fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Cargo MÉTODO->uf_update_cargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_cargo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codcar,$as_descar,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: private
		//	    Arguments: as_codcar  // código de cargo
		//				   as_descar  // descripción
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda el cargo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		switch ($as_existe)
		{
			case "FALSE":
				if(!($this->uf_select_cargo($as_codcar)))
				{
					$lb_valido=$this->uf_insert_cargo($as_codcar,$as_descar,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Cargo ya existe, no lo puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_cargo($as_codcar)))
				{
					$lb_valido=$this->uf_update_cargo($as_codcar,$as_descar,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Cargo no existe, no lo puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_cargo($as_codcar,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_cargo
		//	    Arguments: as_codcar  // código de cargo
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina el cargo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        if ($this->io_personalnomina->uf_select_personalnomina("codcar",$as_codcar,"1")===false)
		{
			$ls_sql="DELETE ".
					"  FROM sno_cargo ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$this->ls_codnom."' ".
					"   AND codcar='".$as_codcar."' ";
			$this->io_sql->begin_transaction();
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_sql->rollback();
				$this->io_mensajes->message("CLASE->Cargo MÉTODO->uf_delete_cargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el  Cargo ".$as_codcar." asociado a la nómina ".$this->ls_codnom;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				if($lb_valido)
				{	
					$this->io_mensajes->message("El Cargo fue Eliminado.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Cargo MÉTODO->uf_delete_cargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$this->io_sql->rollback();
				}
			}
		} 
		else
		{
			$this->io_mensajes->message("No se puede eliminar el registro. Hay Personal asociado a este cargo.");
		}       
		return $lb_valido;
    }// end function uf_delete_cargo
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>