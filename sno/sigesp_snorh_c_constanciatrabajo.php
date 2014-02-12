<?php
class sigesp_snorh_c_constanciatrabajo
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_personal;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_constanciatrabajo()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_constanciatrabajo
		//		   Access: public (sigesp_snorh_d_constanciatrabajo)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 								Fecha Última Modificación : 
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
	}// end function sigesp_snorh_c_constanciatrabajo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_profesion)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006  								Fecha Última Modificación : 
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
	function uf_select_constanciatrabajo($as_codcont)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_constanciatrabajo
		//		   Access: private
 		//	    Arguments: as_codcont  // código de la constancia de trabajo
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la constancia está registrada
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codcont FROM sno_constanciatrabajo ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcont='".$as_codcont."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Constancia Trabajo MÉTODO->uf_select_constanciatrabajo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_constanciatrabajo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_constanciatrabajo($as_codcont,$as_descont,$as_concont,$ai_tamletcont,$ai_intlincont,$ai_marinfcont,$ai_marsupcont,
										 $as_titcont,$as_piepagcont,$ai_tamletpiecont,$as_arcrtfcont,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_constanciatrabajo
		//		   Access: private
		//	    Arguments: as_codcont  // código de la constancia de trabajo
		//				   as_descont  // descripción de la constancia
		//				   as_concont  // contenido de la constancia
		//				   ai_tamletcont  // Tamaño de la letra
		//				   ai_intlincont  // Interlineado
		//				   ai_marinfcont  // Margen Inferior
		//				   ai_marsupcont  // Margen Superior
		//				   as_titcont  // Título de la Constancia
		//				   as_piepagcont  // Pie de Página 
		//				   ai_tamletpiecont  // Tamaño de la letra Pie de Pagina
		//				   as_arcrtfcont // Nombre del Archivo rtf plantilla
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_constanciatrabajo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_constanciatrabajo(codemp,codcont,descont,concont,tamletcont,intlincont,marinfcont,marsupcont,".
				"titcont,piepagcont,tamletpiecont,arcrtfcont) ".
				" VALUES ('".$this->ls_codemp."','".$as_codcont."','".$as_descont."','".$as_concont."',".$ai_tamletcont.",".
				"		  ".$ai_intlincont.",".$ai_marinfcont.",".$ai_marsupcont.",'".$as_titcont."','".$as_piepagcont."',".
				"		  ".$ai_tamletpiecont.",'".$as_arcrtfcont."')";
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Constancia Trabajo MÉTODO->uf_insert_constanciatrabajo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Constancia de Trabajo ".$as_codcont;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Constancia de Trabajo fue Registrada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Constancia Trabajo MÉTODO->uf_insert_constanciatrabajo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_constanciatrabajo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_constanciatrabajo($as_codcont,$as_descont,$as_concont,$ai_tamletcont,$ai_intlincont,$ai_marinfcont,$ai_marsupcont,
	                                     $as_titcont,$as_piepagcont,$ai_tamletpiecont,$as_arcrtfcont,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//    	 Function: uf_update_constanciatrabajo
		//		   Access: private
		//	    Arguments: as_codcont  // código de la constancia de trabajo
		//				   as_descont  // descripción de la constancia
		//				   as_concont  // contenido de la constancia
		//				   ai_tamletcont  // Tamaño de la letra
		//				   ai_intlincont  // Interlineado
		//				   ai_marinfcont  // Margen Inferior
		//				   ai_marsupcont  // Margen Superior
		//				   as_titcont  // Título de la Constancia
		//				   as_piepagcont  // Pie de Página 
		//				   ai_tamletpiecont  // Tamaño de la letra Pie de Pagina
		//				   as_arcrtfcont // Nombre del Archivo rtf plantilla
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_profesión
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sqlarc="";
		if($as_arcrtfcont!="")
		{
			$ls_sqlarc=", arcrtfcont='".$as_arcrtfcont."' ";
		}
		$ls_sql="UPDATE sno_constanciatrabajo ".
				"   SET descont='".$as_descont."', ".
				"   	concont='".$as_concont."', ".
				"   	tamletcont=".$ai_tamletcont.", ".
				"   	tamletpiecont=".$ai_tamletpiecont.", ".
				"   	intlincont=".$ai_intlincont.", ".
				"   	marinfcont=".$ai_marinfcont.", ".
				"   	marsupcont=".$ai_marsupcont.", ".
				"   	titcont='".$as_titcont."', ".
				"   	piepagcont='".$as_piepagcont."' ".
				$ls_sqlarc.
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcont='".$as_codcont."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Constancia Trabajo MÉTODO->uf_update_constanciatrabajo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			////////////////////////////////         SEGURIDAD               //////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Constancia de Trabajo ".$as_codcont;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Constancia de Trabajo fue Actualizada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Constancia Trabajo MÉTODO->uf_update_constanciatrabajo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_constanciatrabajo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codcont,$as_descont,$as_concont,$ai_tamletcont,$ai_intlincont,$ai_marinfcont,$ai_marsupcont,
					    $as_titcont,$as_piepagcont,$ai_tamletpiecont,$as_arcrtfcont,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_constanciatrabajo)
		//	    Arguments: as_codcont  // código de la constancia de trabajo
		//				   as_descont  // descripción de la constancia
		//				   as_concont  // contenido de la constancia
		//				   ai_tamletcont  // Tamaño de la letra
		//				   ai_intlincont  // Interlineado
		//				   ai_marinfcont  // Margen Inferior
		//				   ai_marsupcont  // Margen Superior
		//				   as_titcont  // Título de la Constancia
		//				   as_piepagcont  // Pie de Página 
		//				   ai_tamletpiecont  // Tamaño de la letra Pie de Pagina
		//				   as_arcrtfcont // Nombre del Archivo rtf plantilla
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sno_constanciatrabajo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ai_marinfcont=str_replace(".","",$ai_marinfcont);
		$ai_marinfcont=str_replace(",",".",$ai_marinfcont);
		$ai_marsupcont=str_replace(".","",$ai_marsupcont);
		$ai_marsupcont=str_replace(",",".",$ai_marsupcont);
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_constanciatrabajo($as_codcont)===false)
				{
					$lb_valido=$this->uf_insert_constanciatrabajo($as_codcont,$as_descont,$as_concont,$ai_tamletcont,$ai_intlincont,
					                                              $ai_marinfcont,$ai_marsupcont,$as_titcont,$as_piepagcont,$ai_tamletpiecont,
																  $as_arcrtfcont,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Constancia de Trabajo ya existe, no la puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_constanciatrabajo($as_codcont)))
				{
					$lb_valido=$this->uf_update_constanciatrabajo($as_codcont,$as_descont,$as_concont,$ai_tamletcont,$ai_intlincont,
					                                              $ai_marinfcont,$ai_marsupcont,$as_titcont,$as_piepagcont,$ai_tamletpiecont,
																  $as_arcrtfcont,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Constancia de Trabajo no existe, no la puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_constanciatrabajo($as_codcont,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_constanciatrabajo
		//		   Access: public (sigesp_snorh_d_constanciatrabajo)
		//	    Arguments: as_codcont  // código de la constancia de trabajo
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina de la tabla sno_constanciatrabajo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM sno_constanciatrabajo ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcont='".$as_codcont."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Constancia Trabajo MÉTODO->uf_delete_constanciatrabajo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó la Constancia de Trabajo ".$as_codcont;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Constancia de Trabajo fue Eliminada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Constancia Trabajo MÉTODO->uf_delete_constanciatrabajo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
    }// end function uf_delete_constanciatrabajo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_upload($as_nombre,$as_tipo,$as_tamano,$as_nombretemporal)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_upload
		//		   Access: public (sigesp_snorh_d_constanciatrabajo)
		//	    Arguments: as_nombre  // Nombre 
		//				   as_tipo  // Tipo 
		//				   as_tamano  // Tamaño 
		//				   as_nombretemporal  // Nombre Temporal
		//	      Returns: as_nombre sale vacio si da un error y con el mismo valor si se subio correctamente
		//	  Description: Funcion que sube un archivo al servidor
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/06/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($as_nombre!="")
		{
			if (!((strpos($as_tipo, "word")||strpos($as_tipo, "rtf")) && ($as_tamano < 1000000))) 
			{ 
				$as_nombre="";
				$this->io_mensajes->message("El archivo no es válido, es muy grande o no es de Extención RTF.");
			}
			else
			{ 
				if (!((move_uploaded_file($as_nombretemporal, "documentos/original/".$as_nombre))))
				{
					$as_nombre="";
		        	$this->io_mensajes->message("CLASE->Constancia Trabajo MÉTODO->uf_upload ERROR-> No tiene Permiso para copiar en la carpeta Contacte con el administrador del sistema."); 
				}
				else
				{
					@chmod("documentos/original/".$as_nombre,0755);
				}
			}
		}
		return $as_nombre;	
    }
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>