<?php
class sigesp_snorh_c_ipasme_dependencias
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_afiliado;
	var $io_sno;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_ipasme_dependencias()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_ipasme_dependencias
		//		   Access: public (sigesp_snorh_d_ipasme_dependencias)
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
		$this->io_seguridad=new sigesp_c_seguridad();
		require_once("sigesp_snorh_c_ipasme_afiliado.php");
		$this->io_afiliado=new sigesp_snorh_c_ipasme_afiliado();
		require_once("sigesp_sno.php");
		$this->io_sno=new sigesp_sno();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_snorh_c_ipasme_dependencias
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_ipasme_dependencia)
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
		unset($this->io_afiliado);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_ipasme_dependencia($as_coddep)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_ipasme_dependencia
		//		   Access: private
 		//	    Arguments: as_coddep  // código de la dependencia
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la dependencia está registrada
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT coddep FROM sno_ipasme_dependencias ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND coddep='".$as_coddep."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Ipasme Dependencia MÉTODO->uf_select_ipasme_dependencia ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_ipasme_dependencia
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_ipasme_dependencia($as_coddep,$as_desdep,$as_entdep,$as_mundep,$as_locdep,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_ipasme_dependencia
		//		   Access: private
		//	    Arguments: as_coddep  // código de la Dependencia
		//				   as_desdep  // descripción de la Dependencia
		//				   as_entdep  // Entidad de la Dependencia
		//				   as_mundep  // Municipio de la Dependencia 
		//				   as_locdep  // Localidad de la Dependencia 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_ipasme_dependencias
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_ipasme_dependencias(codemp,coddep,desdep,entdep,mundep,locdep) VALUES ".
				"('".$this->ls_codemp."','".$as_coddep."','".$as_desdep."','".$as_entdep."','".$as_mundep."','".$as_locdep."')";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Ipasme Dependencia MÉTODO->uf_insert_ipasme_dependencia ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Dependencia ".$as_coddep;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Dependencia fue Registrada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Ipasme Dependencia MÉTODO->uf_insert_ipasme_dependencia ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_ipasme_dependencia
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_ipasme_dependencia($as_coddep,$as_desdep,$as_entdep,$as_mundep,$as_locdep,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//    	 Function: uf_update_ipasme_dependencia
		//		   Access: private
		//	    Arguments: as_coddep  // código de la Dependencia
		//				   as_desdep  // descripción de la Dependencia
		//				   as_entdep  // Entidad de la Dependencia
		//				   as_mundep  // Municipio de la Dependencia 
		//				   as_locdep  // Localidad de la Dependencia 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_ipasme_dependencias
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_ipasme_dependencias ".
				"   SET desdep='".$as_desdep."', ".
				"       entdep='".$as_entdep."', ".
				"       mundep='".$as_mundep."', ".
				"       locdep='".$as_locdep."' ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND coddep='".$as_coddep."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Ipasme Dependencia MÉTODO->uf_update_ipasme_dependencia ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			////////////////////////////////         SEGURIDAD               //////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Dependencia ".$as_coddep;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Dependencia fue Actualizada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Ipasme Dependencia MÉTODO->uf_update_ipasme_dependencia ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_ipasme_dependencia
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_coddep,$as_desdep,$as_entdep,$as_mundep,$as_locdep,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_ipasme_dependencia)
		//	    Arguments: as_coddep  // código de la Dependencia
		//				   as_desdep  // descripción de la Dependencia
		//				   as_entdep  // Entidad de la Dependencia
		//				   as_mundep  // Municipio de la Dependencia 
		//				   as_locdep  // Localidad de la Dependencia 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sno_profesión
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_ipasme_dependencia($as_coddep)===false)
				{
					$lb_valido=$this->uf_insert_ipasme_dependencia($as_coddep,$as_desdep,$as_entdep,$as_mundep,$as_locdep,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Dependencia ya existe, no la puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_ipasme_dependencia($as_coddep)))
				{
					$lb_valido=$this->uf_update_ipasme_dependencia($as_coddep,$as_desdep,$as_entdep,$as_mundep,$as_locdep,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Dependencia no existe, no la puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_ipasme_dependencia($as_coddep,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_ipasme_dependencia
		//		   Access: public (sigesp_snorh_d_ipasme_dependencia)
		//	    Arguments: as_coddep  // código de la profesión
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina de la tabla sno_profesión
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        if ($this->io_afiliado->uf_select_ipasme_afiliado("coddep",$as_coddep)===false)   
		{
			$ls_sql="DELETE FROM sno_ipasme_dependencias ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND coddep='".$as_coddep."'";
        	$this->io_sql->begin_transaction();
		   	$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Ipasme Dependencia MÉTODO->uf_delete_ipasme_dependencia ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la Dependencia ".$as_coddep;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{	
					$this->io_mensajes->message("La Dependencia fue Eliminada.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
        			$this->io_mensajes->message("CLASE->Ipasme Dependencia MÉTODO->uf_delete_ipasme_dependencia ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_sql->rollback();
				}
			}
		} 
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("No se puede eliminar la Dependencia, hay Afiliados relacionados a esta.");
		}       
		return $lb_valido;
    }// end function uf_delete_ipasme_dependencia
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_gendisk_dependencia($as_coddepdes,$as_coddephas,$ad_fecmov,$as_ruta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_gendisk_dependencia
		//         Access: public (desde la clase sigesp_snorh_rpp_ipasme_dependencia)  
		//	    Arguments: as_coddepdes // Código de Dependencia donde se empieza a filtrar
		//	  			   as_coddephas // Código de Dependencia donde se termina de filtrar		  
		//	  			   ad_fecmov // Fecha del Movimiento
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las Dependencias y lo exporta a un disco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codorg=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD ORGANISMO IPAS","XXX","C"));
		$ls_criterio="";
		if(!empty($as_coddepdes))
		{
			$ls_criterio= " AND coddep>='".$as_coddepdes."'";
		}
		if(!empty($as_coddephas))
		{
			$ls_criterio= $ls_criterio." AND coddep<='".$as_coddephas."'";
		}
		$ls_sql="SELECT coddep, desdep, entdep, mundep, locdep ".
				"  FROM sno_ipasme_dependencias ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   ".$ls_criterio." ".
				" ORDER BY coddep ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_gendisk_dependencia ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_ano=substr($ad_fecmov,8,2);
			$ls_mes=substr($ad_fecmov,3,2);
			$ls_dia=substr($ad_fecmov,0,2);
			$ls_nombrearchivo=$as_ruta."/dependencias".$ls_codorg."_".$ls_ano.$ls_mes.$ls_dia.".txt";
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido = false;
				}
				else
				{
					$ls_creararchivo = @fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo = @fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_cadena="";
				$ls_cadena=$ls_cadena.$ls_codorg.":";
				$ls_cadena=$ls_cadena.$row["coddep"].":";
				$ls_cadena=$ls_cadena.$row["desdep"].":";
				$ls_cadena=$ls_cadena.$row["entdep"].":";
				$ls_cadena=$ls_cadena.$row["mundep"].":";
				$ls_cadena=$ls_cadena.$row["locdep"]."";
				$ls_cadena=$ls_cadena."\r\n";
				if ($ls_creararchivo)  //Chequea que el archivo este abierto				
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido = false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;
				}
			}
			$this->io_sql->free_result($rs_data);
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Generó el Archivo al IPASME de Dependencias ";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}		
		return $lb_valido;
	}// end function uf_gendisk_dependencia
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>