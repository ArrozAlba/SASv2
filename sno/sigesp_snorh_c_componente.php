<?php
class sigesp_snorh_c_componente
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_componente()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_componente
		//		   Access: public (sigesp_snorh_d_componente)
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
	}// end function sigesp_snorh_c_componente
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_componente)
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
		unset($this->io_personalnomina);
		unset($this->io_asignacioncargo);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_componente($as_codcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_componente
		//		   Access: private
		//	    Arguments: as_codcom  // código del componente
		//	      Returns: lb_valido True si existe ó False si no existe
		//	  Description: Funcion que verifica si el Componente está registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codcom FROM sno_componente ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcom='".$as_codcom."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$this->io_mensajes->message("CLASE->Componente MÉTODO->uf_select_componente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_componente
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_componente($as_codcom,$as_descom,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_componente
		//		   Access: private
		//	    Arguments: as_codcom  // código del Componente
		//				   as_descom  // descripción del Componente 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_componente
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_componente(codemp,codcom,descom) VALUES ('".$this->ls_codemp."','".$as_codcom."','".$as_descom."')";
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Componente MÉTODO->uf_insert_componente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Componente ".$as_codcom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Componente fue registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Componente MÉTODO->uf_insert_componente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_componente
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_componente($as_codcom,$as_descom,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_componente
		//		   Access: private
		//	    Arguments: as_codcom  // código del Componente
		//				   as_descom  // descripción del Componente 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_componente
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_componente ".
				"   SET descom='".$as_descom."' ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcom='".$as_codcom."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Componente MÉTODO->uf_update_componente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			////////////////////////////////         SEGURIDAD               //////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Componente ".$as_codcom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Componente fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Componente MÉTODO->uf_update_componente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_componente	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codcom,$as_descom,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_componente)
		//	    Arguments: as_codcom  // código del Componente
		//				   as_descom  // descripción del Componente 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sno_componente
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		switch ($as_existe)
		{
			case "FALSE":
				if(!($this->uf_select_componente($as_codcom)))
				{
					$lb_valido=$this->uf_insert_componente($as_codcom,$as_descom,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Componente ya existe, no lo puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_componente($as_codcom)))
				{
					$lb_valido=$this->uf_update_componente($as_codcom,$as_descom,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Componente no existe, no lo puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_rango($as_codcom,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_rango
		//		   Access: private
		//	    Arguments: as_codcom  // código de la componente
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si existe la componente en la tabla tipo personal False si no existe
		//	  Description: Funcion que elimina todos los tipos de personal relacionados a este Componente
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 		$lb_valido=true;
			$ls_sql="DELETE FROM sno_rango ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codcom='".$as_codcom."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Componente MÉTODO->uf_delete_rango ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó todos los Rangos relacionados al Componente ".$as_codcom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;    
	}// end function uf_delete_rango	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_personal($as_codcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_personal
		//		   Access: private
		//	    Arguments: as_codcom     // codigo de componente
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el Componente existe en personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codcom ".
				"  FROM sno_personal ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcom='".$as_codcom."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Componente MÉTODO->uf_select_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_componente($as_codcom,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_componente
		//		   Access: public (sigesp_snorh_d_componente)
		//	    Arguments: as_codcom  // código del Componente
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina de la tabla sno_componente
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        if($this->uf_select_personal($as_codcom)===false)
		{
			$lb_valido=$this->uf_delete_rango($as_codcom,$aa_seguridad);
			if($lb_valido)
			{
				$ls_sql="DELETE FROM sno_componente ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codcom='".$as_codcom."'";
				$this->io_sql->begin_transaction();
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Componente MÉTODO->uf_delete_componente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$this->io_sql->rollback();
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="DELETE";
					$ls_descripcion ="Eliminó el Componente ".$as_codcom;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////	
					if($lb_valido)
					{	
						$this->io_mensajes->message("El Componente fue Eliminado.");
						$this->io_sql->commit();
					}
					else
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Componente MÉTODO->uf_delete_componente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
						$this->io_sql->rollback();
					}
				}
			}
		} 
		else
		{
			$this->io_mensajes->message("No se puede eliminar el Componente. Hay personal asociado a este.");
			$lb_valido=false;
		}       
		return $lb_valido;
    }// end function uf_delete_componente	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_componente(&$as_existe,&$as_codcom,&$as_descom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_componente
		//		   Access: public (sigesp_snorh_d_componente)
		//	    Arguments: as_existe  // si existe la Componente
		//			       as_codcom  // código de la Componente
		//				   as_descom  // descripción de la Componente
		//	      Returns: lb_valido True si existe ó False si no existe
		//	  Description: Funcion que obtiene los datos de la Componente
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codcom, descom ".
				"  FROM sno_componente ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcom='".$as_codcom."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Componente MÉTODO->uf_load_componente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_existe="TRUE";			
				$as_codcom=$row["codcom"];
				$as_descom=$row["descom"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>