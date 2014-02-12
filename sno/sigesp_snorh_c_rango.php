<?php
class sigesp_snorh_c_rango
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_rango()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_rango
		//		   Access: public (sigesp_snorh_d_rango)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/07/2007 								Fecha Última Modificación : 
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
	}// end function sigesp_snorh_c_rango
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_ango)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/07/2007 								Fecha Última Modificación : 
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
	function uf_select_rango($as_codcom,$as_codran)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_rango
		//		   Access: private
		//	    Arguments: as_codcom  // código del componente
		//				   as_codran  // código del ango
		//	      Returns: lb_valido True si existe ó False si no existe
		//	  Description: Funcion que verifica si el rango está registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codran FROM sno_rango ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcom='".$as_codcom."'".
				"   AND codran='".$as_codran."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Rango MÉTODO->uf_select_rango ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_rango
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_rango($as_codcom,$as_codran,$as_desran,$as_codcat,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_rango
		//		   Access: private
		//	    Arguments: as_codcom  // código del componente
		//				   as_codran  // código del rango
		//				   as_desran  // descripción del rango
		//                 as_codcat // codigo de la categoria
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_rango
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_rango(codemp,codcom,codran,desran, codcat) VALUES ".
				"('".$this->ls_codemp."','".$as_codcom."','".$as_codran."','".$as_desran."','".$as_codcat."')";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Rango MÉTODO->uf_insert_rango ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Rango ".$as_codran." asociado al Componente ".$as_codcom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Rango fue Registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Rango MÉTODO->uf_insert_rango ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_rango
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_rango($as_codcom,$as_codran,$as_desran,$as_codcat,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_rango
		//		   Access: private
		//	    Arguments: as_codcom  // código del Componente
		//				   as_codran  // código del Rango
		//				   as_desran  // descripción del Rango
		//                 as_codcat  // codigo de la categoria
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_rango
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_rango ".
				"   SET desran='".$as_desran."', ".
				"       codcat='".$as_codcat."'  ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcom='".$as_codcom."'".
				"   AND codran='".$as_codran."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Rango MÉTODO->uf_update_rango ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			////////////////////////////////         SEGURIDAD               //////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Rango ".$as_codran." asociado al Componente ".$as_codcom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Rango fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Rango MÉTODO->uf_update_rango ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_rango	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codcom,$as_codran,$as_desran,$as_codcat,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_rango)
		//	    Arguments: as_codcom  // código del Componente
		//				   as_codran  // código del Rango
		//				   as_desran  // descripción del Rango
		//                 as_codcat // codigo de la categoria
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sno_rango
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		switch ($as_existe)
		{
			case "FALSE":
				if(!($this->uf_select_rango($as_codcom,$as_codran)))
				{
					$lb_valido=$this->uf_insert_rango($as_codcom,$as_codran,$as_desran,$as_codcat,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Rango ya existe, no lo puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_rango($as_codcom,$as_codran)))
				{
					$lb_valido=$this->uf_update_rango($as_codcom,$as_codran,$as_desran,$as_codcat,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Rango no existe, no lo puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar		
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_integridad_personal($as_codcom,$as_codran)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_integridad_personal
		//		   Access: private
		//	    Arguments: as_codcom  // código de Componente
		//				   as_codran  // código de Rango
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que valida que ningún personal tenga asociado este Componente y Rango
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 		$lb_existe=true;
       	$ls_sql="SELECT codcom ".
				"  FROM sno_personal ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcom='".$as_codcom."'".
				"   AND codran='".$as_codran."'";
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
			$this->msg->message("CLASE->Rango MÉTODO->uf_integridad_personal ERROR->".$this->fun->uf_convertirmsg($this->SQL->message)); 
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
	}// end function uf_integridad_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_rango($as_codcom,$as_codran,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_rango
		//		   Access: public (sigesp_snorh_d_rango)
		//	    Arguments: as_codcom  // código de la dedicacion
		//				   as_codran  // código del tipo de personal
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina de la tabla sno_rango
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        if($this->uf_integridad_personal($as_codcom,$as_codran)===false)
		{
			$ls_sql="DELETE FROM sno_rango ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codcom='".$as_codcom."'".
					"   AND codran='".$as_codran."'";
        	$this->io_sql->begin_transaction();
		   	$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Rango MÉTODO->uf_delete_rango ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Rango ".$as_codran." asociado al Componente ".$as_codcom;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{	
					$this->io_mensajes->message("El Rango fue Eliminado.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Rango MÉTODO->uf_delete_rango ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$this->io_sql->rollback();
				}
			}
		} 
		else
		{
			$this->io_mensajes->message("No se puede eliminar el Rango. Hay personal asociado a este.");
			$lb_valido=false;
		}       
		return $lb_valido;
    }// end function uf_delete_rango		
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>