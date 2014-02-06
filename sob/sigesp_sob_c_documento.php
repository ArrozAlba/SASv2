<?PHP
class sigesp_sob_c_documento
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_personal;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sob_c_documento()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sob_c_documento
		//		   Access: public (sigesp_sob_d_documento)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 12/05/2008 								Fecha Última Modificación : 
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
		require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
		$this->io_keygen= new sigesp_c_generar_consecutivo();
	}// end function sigesp_sob_c_documento
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sob_d_profesion)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 12/05/2008  								Fecha Última Modificación : 
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
	function uf_select_documento($as_coddoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_documento
		//		   Access: private
 		//	    Arguments: as_coddoc  // código del documento
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el documento esta registrado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 12/05/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT coddoc FROM sob_documento ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND coddoc='".$as_coddoc."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Documento MÉTODO->uf_select_documento ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_documento
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_documento(&$as_coddoc,$as_desdoc,$as_condoc,$ai_tamletdoc,$ai_intlindoc,$ai_marinfdoc,$ai_marsupdoc,$as_titdoc,$as_piepagdoc,
								 $ai_tamletpiedoc,$as_arcrtfdoc,$as_tipdoc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_documento
		//		   Access: private
		//	    Arguments: as_coddoc  // Código del documento
		//				   as_desdoc  // Descripción del documento
		//				   as_condoc  // Contenido del documento
		//				   ai_tamletdoc  // Tamaño de la letra
		//				   ai_intlindoc  // Interlineado
		//				   ai_marinfdoc  // Margen Inferior
		//				   ai_marsupdoc  // Margen Superior
		//				   as_titdoc  // Título del documento
		//				   as_piepagdoc  // Pie de Página 
		//				   ai_tamletpiedoc  // Tamaño de la letra Pie de Pagina
		//				   as_arcrtfdoc // Nombre del Archivo rtf plantilla
		//                 as_tipdoc    // Tipo de Documento
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el contenido del documento
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 12/05/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_valido= $this->io_keygen->uf_verificar_numero_generado("SOB","sob_documento","coddoc","SOB",3,"","","",&$as_coddoc);
		$ls_sql="INSERT INTO sob_documento(codemp,coddoc,desdoc,condoc,tamletdoc,intlindoc,marinfdoc,marsupdoc,".
				"						   titdoc,piepagdoc,tamletpiedoc,arcrtfdoc,tipdoc) ".
				" VALUES ('".$this->ls_codemp."','".$as_coddoc."','".$as_desdoc."','".$as_condoc."',".$ai_tamletdoc.",".
				"		  ".$ai_intlindoc.",".$ai_marinfdoc.",".$ai_marsupdoc.",'".$as_titdoc."','".$as_piepagdoc."',".
				"		  ".$ai_tamletpiedoc.",'".$as_arcrtfdoc."','".$as_tipdoc."')";
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_sql->rollback();
			if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
			{
				$lb_valido=$this->uf_insert_documento($as_coddoc,$as_desdoc,$as_condoc,$ai_tamletdoc,$ai_intlindoc,$ai_marinfdoc,$ai_marsupdoc,$as_titdoc,$as_piepagdoc,
													  $ai_tamletpiedoc,$as_arcrtfdoc,$as_tipdoc,$aa_seguridad);
			}
			else
			{
	        	$this->io_mensajes->message("CLASE->Documento MÉTODO->uf_insert_documento ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Documento ".$as_coddoc;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_documento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_documento($as_coddoc,$as_desdoc,$as_condoc,$ai_tamletdoc,$ai_intlindoc,$ai_marinfdoc,$ai_marsupdoc,$as_titdoc,$as_piepagdoc,
	                             $ai_tamletpiedoc,$as_arcrtfdoc,$as_tipdoc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//    	 Function: uf_update_documento
		//		   Access: private
		//	    Arguments: as_coddoc  // código del documento
		//				   as_desdoc  // descripción del documento
		//				   as_condoc  // contenido del documento
		//				   ai_tamletdoc  // Tamaño de la letra
		//				   ai_intlindoc  // Interlineado
		//				   ai_marinfdoc  // Margen Inferior
		//				   ai_marsupdoc  // Margen Superior
		//				   as_titdoc  // Título del documento
		//				   as_piepagdoc  // Pie de Página 
		//				   ai_tamletpiedoc  // Tamaño de la letra Pie de Pagina
		//				   as_arcrtfdoc // Nombre del Archivo rtf plantilla
		//                 as_tipdoc    // Tipo de Documento
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el documento
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 12/05/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sqlarc="";
		if($as_arcrtfdoc!="")
		{
			$ls_sqlarc=", arcrtfdoc='".$as_arcrtfdoc."' ";
		}
		$ls_sql="UPDATE sob_documento ".
				"   SET desdoc='".$as_desdoc."', ".
				"   	condoc='".$as_condoc."', ".
				"   	tipdoc='".$as_tipdoc."', ".
				"   	tamletdoc=".$ai_tamletdoc.", ".
				"   	tamletpiedoc=".$ai_tamletpiedoc.", ".
				"   	intlindoc=".$ai_intlindoc.", ".
				"   	marinfdoc=".$ai_marinfdoc.", ".
				"   	marsupdoc=".$ai_marsupdoc.", ".
				"   	titdoc='".$as_titdoc."', ".
				"   	piepagdoc='".$as_piepagdoc."' ".
				$ls_sqlarc.
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND coddoc='".$as_coddoc."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Documento MÉTODO->uf_update_documento ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			////////////////////////////////         SEGURIDAD               //////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Documento ".$as_coddoc;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Documento fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Documento MÉTODO->uf_update_documento ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_documento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_coddoc,$as_desdoc,$as_condoc,$ai_tamletdoc,$ai_intlindoc,$ai_marinfdoc,$ai_marsupdoc,
					    $as_titdoc,$as_piepagdoc,$ai_tamletpiedoc,$as_arcrtfdoc,$as_tipdoc,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_sob_d_documento)
		//	    Arguments: as_coddoc  // Código del Documento
		//				   as_desdoc  // Descripción del Documento
		//				   as_condoc  // Contenido del Documento
		//				   ai_tamletdoc  // Tamaño de la letra
		//				   ai_intlindoc  // Interlineado
		//				   ai_marinfdoc  // Margen Inferior
		//				   ai_marsupdoc  // Margen Superior
		//				   as_titdoc  // Título del Documento
		//				   as_piepagdoc  // Pie de Página 
		//				   ai_tamletpiedoc  // Tamaño de la letra Pie de Pagina
		//				   as_arcrtfdoc // Nombre del Archivo rtf plantilla
		//                 as_tipdoc    // Tipo de Documento
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda el contenido del documento
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 12/05/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ai_marinfdoc=str_replace(".","",$ai_marinfdoc);
		$ai_marinfdoc=str_replace(",",".",$ai_marinfdoc);
		$ai_marsupdoc=str_replace(".","",$ai_marsupdoc);
		$ai_marsupdoc=str_replace(",",".",$ai_marsupdoc);
		switch ($as_existe)
		{
			case "FALSE":
				$ls_coddocaux=$as_coddoc;
				$lb_valido=$this->uf_insert_documento(&$as_coddoc,$as_desdoc,$as_condoc,$ai_tamletdoc,$ai_intlindoc,
															  $ai_marinfdoc,$ai_marsupdoc,$as_titdoc,$as_piepagdoc,$ai_tamletpiedoc,
															  $as_arcrtfdoc,$as_tipdoc,$aa_seguridad);
				if($lb_valido)
				{
					if($ls_coddocaux!=$as_coddoc)
					{
						$this->io_mensajes->message("Se le asigno un nuevo numero ".$as_coddoc);
					}
					$this->io_mensajes->message("El documento fue Registrado.");
				}
				else
				{
					$this->io_mensajes->message("No se pudo registrar el documento.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_documento($as_coddoc)))
				{
					$lb_valido=$this->uf_update_documento($as_coddoc,$as_desdoc,$as_condoc,$ai_tamletdoc,$ai_intlindoc,
					                                              $ai_marinfdoc,$ai_marsupdoc,$as_titdoc,$as_piepagdoc,$ai_tamletpiedoc,
																  $as_arcrtfdoc,$as_tipdoc,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Documento no existe, no se puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_documento($as_coddoc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_documento
		//		   Access: public (sigesp_sob_d_documento)
		//	    Arguments: as_coddoc  // código del documento
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina el documento
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 12/05/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM sob_documento ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND coddoc='".$as_coddoc."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Documento MÉTODO->uf_delete_documento ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Documento ".$as_coddoc;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Documento fue Eliminado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Documento MÉTODO->uf_delete_documento ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
    }// end function uf_delete_documento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_upload($as_nombre,$as_tipo,$as_tamano,$as_nombretemporal)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_upload
		//		   Access: public (sigesp_sob_d_documento)
		//	    Arguments: as_nombre  // Nombre 
		//				   as_tipo  // Tipo 
		//				   as_tamano  // Tamaño 
		//				   as_nombretemporal  // Nombre Temporal
		//	      Returns: as_nombre sale vacio si da un error y con el mismo valor si se subio correctamente
		//	  Description: Funcion que sube un archivo al servidor
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 12/05/2008 								Fecha Última Modificación : 
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
		        	$this->io_mensajes->message("CLASE->Documento MÉTODO->uf_upload ERROR-> No tiene Permiso para copiar en la carpeta Contacte con el administrador del sistema."); 
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