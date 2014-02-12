<?PHP
class sigesp_sob_c_formatoreportes
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_personal;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sob_c_formatoreportes()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sob_c_formatoreporte
		//		   Access: public (sigesp_sob_c_formatoreporte)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 								Fecha Última Modificación : 29/02/2008
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
		//require_once("sigesp_snorh_c_personal.php");
		//$this->io_personal=new sigesp_snorh_c_personal();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_sob_c_formatoreportes
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
	function uf_select_formatoreporte($as_codcont)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_formatoreporte
		//		   Access: private
 		//	    Arguments: as_codfor  // código de la constancia de trabajo
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el formato del reporte esta registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 								Fecha Última Modificación :28/03/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codfor FROM sob_formatoreporte ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codfor='".$as_codfor."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Formato Reporte MÉTODO->uf_select_formatoreporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_formatoreporte
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_formatoreporte($as_codfor,$as_desfor,$as_confor,$ai_tamletfor,$ai_intlinfor,$ai_marinffor,$ai_marsupfor,
										 $as_titfor,$as_piepagfor,$ai_tamletpiefor,$as_arcrtffor,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_formatoreporte
		//		   Access: private
		//	    Arguments: as_codfor  // código del formato de reporte
		//				   as_desfor  // descripción del formato de reporte
		//				   as_confor  // contenido del formato de reporte
		//				   ai_tamletfor  // Tamaño de la letra
		//				   ai_intlinfor  // Interlineado
		//				   ai_marinffor  // Margen Inferior
		//				   ai_marsupfor  // Margen Superior
		//				   as_titfor  // Título del reporte
		//				   as_piepagfor  // Pie de Página 
		//				   ai_tamletpiefor  // Tamaño de la letra Pie de Pagina
		//				   as_arcrtffor // Nombre del Archivo rtf plantilla
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_constanciatrabajo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sob_formatoreporte(codemp,codfor,desfor,confor,tamletfor,intlinfor,marinffor,marsupfor,".
				"titfor,piepagfor,tamletpiefor,arcrtffor) ".
				" VALUES ('".$this->ls_codemp."','".$as_codfor."','".$as_desfor."','".$as_confor."',".$ai_tamletfor.",".
				"		  ".$ai_intlinfor.",".$ai_marinffor.",".$ai_marsupfor.",'".$as_titfor."','".$as_piepagfor."',".
				"		  ".$ai_tamletpiefor.",'".$as_arcrtffor."')";
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Formato Reporte MÉTODO->uf_insert_formatoreporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Formato de Reporte ".$as_codcont;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Formato de Reporte fue Registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Formato Reporte MÉTODO->uf_insert_formatoreporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_formatoreporte
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_formatoreporte($as_codfor,$as_desfor,$as_confor,$ai_tamletfor,$ai_intlinfor,$ai_marinffor,$ai_marsupfor,
	                                     $as_titfor,$as_piepagfor,$ai_tamletpiefor,$as_arcrtffor,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//    	 Function: uf_update_formatoreporte
		//		   Access: private
		//	    Arguments: as_codfor  // código del formato de reporte
		//				   as_desfor  // descripción del formato de reporte
		//				   as_confor  // contenido del formato de reporte
		//				   ai_tamletfor  // Tamaño de la letra
		//				   ai_intlinfor  // Interlineado
		//				   ai_marinffor  // Margen Inferior
		//				   ai_marsupfor  // Margen Superior
		//				   as_titfor  // Título del reporte
		//				   as_piepagfor  // Pie de Página 
		//				   ai_tamletpiefor  // Tamaño de la letra Pie de Pagina
		//				   as_arcrtffor // Nombre del Archivo rtf plantilla
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sob_formatoreporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sqlarc="";
		if($as_arcrtffor!="")
		{
			$ls_sqlarc=", arcrtffor='".$as_arcrtffor."' ";
		}
		$ls_sql="UPDATE sob_formatoreporte ".
				"   SET desfor='".$as_desfor."', ".
				"   	confor='".$as_confor."', ".
				"   	tamletfor=".$ai_tamletfor.", ".
				"   	tamletpiefor=".$ai_tamletpiefor.", ".
				"   	intlinfor=".$ai_intlinfor.", ".
				"   	marinffor=".$ai_marinffor.", ".
				"   	marsupfor=".$ai_marsupfor.", ".
				"   	titfor='".$as_titfor."', ".
				"   	piepagfor='".$as_piepagfor."' ".
				$ls_sqlarc.
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codfor='".$as_codfor."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Formato Reporte MÉTODO->uf_update_formatoreporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			////////////////////////////////         SEGURIDAD               //////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Formato de Reporte ".$as_codfor;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El formato de Reporte fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Formato Reporte MÉTODO->uf_update_formatoreporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_constanciatrabajo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codfor,$as_desfor,$as_confor,$ai_tamletfor,$ai_intlinfor,$ai_marinffor,$ai_marsupfor,
					    $as_titfor,$as_piepagfor,$ai_tamletpiefor,$as_arcrtffor,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_sob_d_formatoreportes)
		//	    Arguments: as_codfor  // código del formato de reporte
		//				   as_desfor  // descripción del formato de reporte
		//				   as_confor  // contenido del formato
		//				   ai_tamletfor  // Tamaño de la letra
		//				   ai_intlinfor  // Interlineado
		//				   ai_marinffor  // Margen Inferior
		//				   ai_marsupfor  // Margen Superior
		//				   as_titfor  // Título del formato
		//				   as_piepagfor  // Pie de Página 
		//				   ai_tamletpiefor  // Tamaño de la letra Pie de Pagina
		//				   as_arcrtffor // Nombre del Archivo rtf plantilla
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sob_formatoreporte
		//	   Creado Por: Ing. Yesenia Moreno                      
		// Fecha Creación: 06/07/2006 								Fecha Última Modificación : 28/03/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ai_marinffor=str_replace(".","",$ai_marinffor);
		$ai_marinffor=str_replace(",",".",$ai_marinffor);
		$ai_marsupfor=str_replace(".","",$ai_marsupfor);
		$ai_marsupfor=str_replace(",",".",$ai_marsupfor);
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_constanciatrabajo($as_codfor)===false)
				{
					$lb_valido=$this->uf_insert_formatoreporte($as_codfor,$as_desfor,$as_confor,$ai_tamletfor,$ai_intlinfor,
					                                              $ai_marinffor,$ai_marsupfor,$as_titfor,$as_piepagfor,$ai_tamletpiefor,
																  $as_arcrtffor,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El formato de Reporte ya existe, no la puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_constanciatrabajo($as_codfor)))
				{
					$lb_valido=$this->uf_update_formatoreporte($as_codfor,$as_desfor,$as_confor,$ai_tamletfor,$ai_intlinfor,
					                                              $ai_marinffor,$ai_marsupfor,$as_titfor,$as_piepagfor,$ai_tamletpiefor,
																  $as_arcrtffor,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El formato de Reporte no existe, no la puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_formatoreporte($as_codfor,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_formatoreporte
		//		   Access: public (sigesp_sob_d_formatoreportes)
		//	    Arguments: as_codfor  // código del formato de reporte
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina de la tabla sob_formatoreporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM sob_formatoreporte ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codfor='".$as_codfor."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Formato Reporte MÉTODO->uf_delete_formatoreporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Formato de Reporte ".$as_codfor;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Formato de Reporte fue Eliminado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Formato Reporte MÉTODO->uf_delete_formatoreporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
		        	$this->io_mensajes->message("CLASE->Formato Reporte MÉTODO->uf_upload ERROR-> No tiene Permiso para copiar en la carpeta Contacte con el administrador del sistema."); 
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