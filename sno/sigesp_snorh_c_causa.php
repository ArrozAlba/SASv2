<?php
class sigesp_snorh_c_causa
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_personal;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_causa()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_causa
		//		   Access: public (sigesp_snorh_d_causa)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 25/07/2008								Fecha Última Modificación : 
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
		require_once("sigesp_snorh_c_personal.php");
		$this->io_personal=new sigesp_snorh_c_personal();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_snorh_c_causa
	//-----------------------------------------------------------------------------------------------------------------------------------
		//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_profesion)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 025/07/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_personal);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_causa($as_codcausa)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_causa
		//		   Access: private
 		//	    Arguments: as_codcausa  // código de la causa de retiro 
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la causa está registrada
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 25/07/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codcausa FROM sno_causales ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcausa='".$as_codcausa."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Causa MÉTODO->uf_select_causa ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_causa
	//-----------------------------------------------------------------------------------------------------------------------------------	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_causa($as_codcausa,$as_dencausa,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_causa
		//		   Access: private
		//	    Arguments: as_codcausa  // código de la causa
		//				   as_dencausa // descripción de la causa 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_causales
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 25/07/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" INSERT INTO sno_causales(codemp,codcausa,dencausa) ".
		        " VALUES('".$this->ls_codemp."','".$as_codcausa."','".$as_dencausa."')";
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Causa MÉTODO->uf_insert_causales ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Causa ".$as_codcausa;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Causa de Retiro fue Registrada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Causa MÉTODO->uf_insert_causa ERROR->".
				                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_causa
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_causa($as_codcausa,$as_dencausa,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//    	 Function: uf_update_causa
		//		   Access: private
		//	    Arguments: as_codcausa  // código de la causa
		//				   as_dencausa  // descripción de la causa 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_causales
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 25/07/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_causales ".
				"   SET dencausa='".$as_dencausa."' ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcausa='".$as_codcausa."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Causa MÉTODO->uf_update_causales ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			////////////////////////////////         SEGURIDAD               //////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Causa ".$as_codcausa;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Causa de Retiro fue Actualizada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Causa MÉTODO->uf_update_causa ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_causa
	//-----------------------------------------------------------------------------------------------------------------------------------
		//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codcausa,$as_dencausa,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_causa)
		//	    Arguments: as_codcausa  // código de la causa
		//				   as_dencausa  // descripción de la causa 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sno_causa
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 25/07/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_causa($as_codcausa)===false)
				{
					$lb_valido=$this->uf_insert_causa($as_codcausa,$as_dencausa,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Causa de Retiro ya existe, no la puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_causa($as_codcausa)))
				{
					$lb_valido=$this->uf_update_causa($as_codcausa,$as_dencausa,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Causa de Retiro no existe, no la puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_causa($as_codcausa,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_causa
		//		   Access: public (sigesp_snorh_d_causa)
		//	    Arguments: as_codpro  // código de la causa
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina de la tabla sno_causa
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 25/07/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        if ($this->io_personal->uf_select_personal("codcausa",$as_codpro)===false)   
		{
			$ls_sql="DELETE FROM sno_causales ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codcausa='".$as_codcausa."'";
        	$this->io_sql->begin_transaction();
		   	$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Causa MÉTODO->uf_delete_causa ERROR->".
				                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la Causa ".$as_codcausa;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{	
					$this->io_mensajes->message("La Causa de Retiro fue Eliminada.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
        			$this->io_mensajes->message("CLASE->Causa MÉTODO->uf_delete_causa ERROR->".
					                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_sql->rollback();
				}
			}
		} 
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("No se puede eliminar la Causa de Retiro, hay personal relacionado a esta.");
		}       
		return $lb_valido;
    }// end function uf_delete_causa
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>