<?php

class sigesp_srh_c_tipoevaluacion
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_tipoevaluacion($path)
	{   require_once($path."shared/class_folder/class_sql.php");
		require_once($path."shared/class_folder/class_datastore.php");
		require_once($path."shared/class_folder/class_mensajes.php");
		require_once($path."shared/class_folder/sigesp_include.php");
		require_once($path."shared/class_folder/sigesp_c_seguridad.php");
		require_once($path."shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$this->io_funcion = new class_funciones();
		$this->la_empresa=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		
		
		
	}
	
	
function uf_srh_getProximoCodigo()
  {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getProximoCodigo
		//         Access: public (sigesp_srh_d_tipoevaluacion)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de un tipo de evaluación
		//    Description: Funcion que genera un código un tipo de evaluación
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(codeval) AS codigo FROM srh_tipoevaluacion ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_codeval = $la_datos["codigo"][0]+1;
    $ls_codeval = str_pad ($ls_codeval,15,"0","left");
	return $ls_codeval;
  }
	
	function uf_srh_select_tipoevaluacion($as_codeval)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_tipoevaluacion
		//      Argumento: $as_codeval    // codigo de tipo de evaluación
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un tipo de evaluación en la tabla de  srh_tipoevaluacion
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 22/11/2007					Fecha Última Modificación: 22/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_tipoevaluacion  ".
				  " WHERE codeval='".trim($as_codeval)."'".
				  " AND codemp='".$this->ls_codemp."'" ;
	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tipoevaluacion MÉTODO->uf_srh_select_tipoevaluacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				
				
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	}  //  end function uf_srh_select_tipoevaluacion

	function  uf_srh_insert_tipoevaluacion($as_codeval,$as_deneval,$as_codesc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_tipoevaluacion
		//      Argumento: $as_codeval   // codigo de tipo de evaluación
	    //                 $as_deneval// denominacion de tipo de evaluación
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un tipo de evaluación en la tabla de srh_tipoevaluacion
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 22/11/2007					Fecha Última Modificación:22/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO srh_tipoevaluacion (codeval, deneval,codesc, codemp) ".
					" VALUES('".$as_codeval."','".$as_deneval."','".$as_codesc."','".$this->ls_codemp."')" ;
		
		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->tipoevaluacion MÉTODO->uf_srh_insert_tipoevaluacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el tipo de evaluación".$as_codeval;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_tipoevaluacion

	function uf_srh_update_tipoevaluacion($as_codeval,$as_deneval,$as_codesc,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_tipoevaluacion
		//      Argumento: $as_codeval   // codigo de tipo de evaluación
	    //                 $as_deneval   // denominacion de tipo de evaluación
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un tipo de evaluación en la tabla de srh_tipoevaluacion
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 22/11/2007					Fecha Última Modificación: 22/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE  srh_tipoevaluacion SET  codesc='". $as_codesc."', deneval='". $as_deneval."'". 
				   " WHERE codeval='" . $as_codeval ."'".
				   " AND codemp='".$this->ls_codemp."'";
		
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->tipoevaluacion MÉTODO->uf_srh_update_tipoevaluacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el tipo de evaluación".$as_codeval;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_tipoevaluacion
	
	
 function uf_select_aspectos_evaluacion ($as_codeval)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_aspectos_evaluacion
		//		   Access: private
 		//	    Arguments: as_codeval // código del tipo de evaluación
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el tipo de evaluacion esta asociada a un aspecto de evaluacion
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codeval ".
				 "  FROM srh_aspectos_evaluacion".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND codeval = '".$as_codeval."' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->tipoevaluacion  MÉTODO->uf_select_aspectos_evaluacion  ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	}
	
	
   function uf_select_tipo_evaluacion_psicologica ($as_codeval)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_tipo_evaluacion_psicologica
		//		   Access: private
 		//	    Arguments: as_codeval // código del tipo de evaluación
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el tipo de evaluacion esta asociada a una evaluación psicológica
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT tipo_eval ".
				 "  FROM srh_evaluacion_psicologica".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND tipo_eval = '".$as_codeval."' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->tipoevaluacion  MÉTODO->uf_select_tipo_evaluacion_psicologica  ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	}
	
	
	function uf_select_tipo_evaluacion_requisitos ($as_codeval)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_tipo_evaluacion_requisitos
		//		   Access: private
 		//	    Arguments: as_codeval // código del tipo de evaluación
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el tipo de evaluacion esta asociada a una evaluación de requisitos mínimos
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT tipo_eval ".
				 "  FROM srh_requisitos_minimos".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND tipo_eval = '".$as_codeval."' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->tipoevaluacion  MÉTODO->uf_select_tipo_evaluacion_requisitos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	}
	
	function uf_select_tipo_evaluacion_entrevista ($as_codeval)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_tipo_evaluacion_entrevista
		//		   Access: private
 		//	    Arguments: as_codeval // código del tipo de evaluación
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el tipo de evaluacion esta asociada a un entrevista técnica
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT tipo_eval ".
				 "  FROM srh_entrevista_tecnica".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND tipo_eval = '".$as_codeval."' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->tipoevaluacion  MÉTODO->uf_select_tipo_evaluacion_entrevista  ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	}
	
	
	function uf_select_tipo_evaluacion_ascenso ($as_codeval)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:uf_select_seccion_departamento
		//		   Access: private
 		//	    Arguments: as_codeval // código del tipo de evaluación
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el tipo de evaluacion esta asociada a una evaluación de ascenso
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT tipo_eval ".
				 "  FROM srh_evaluacion_ascenso".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND tipo_eval = '".$as_codeval."' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->tipoevaluacion  MÉTODO->uf_select_tipo_evaluacion_ascenso  ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	}
	
	
	function uf_select_tipo_evaluacion_eficiencia($as_codeval)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_tipo_evaluacion_eficiencia
		//		   Access: private
 		//	    Arguments: as_codeval // código del tipo de evaluación
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el tipo de evaluacion esta asociada a una evaluación de eficiencia
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT tipo_eval ".
				 "  FROM srh_evaluacion_eficiencia".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND tipo_eval = '".$as_codeval."' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->tipoevaluacion  MÉTODO->uf_select_tipo_evaluacion_eficiencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	}
	
	function uf_select_tipo_evaluacion_desempeno ($as_codeval)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_tipo_evaluacion_desempeno
		//		   Access: private
 		//	    Arguments: as_codeval // código del tipo de evaluación
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el tipo de evaluacion esta asociada a una evaluación de desempeño
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT tipo_eval ".
				 "  FROM srh_evaluacion_desempeno".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND tipo_eval = '".$as_codeval."' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->tipoevaluacion  MÉTODO->uf_select_tipo_evaluacion_desempeno  ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	}
	
	
	function uf_select_tipo_evaluacion_metas ($as_codeval)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_tipo_evaluacion_metas
		//		   Access: private
 		//	    Arguments: as_codeval // código del tipo de evaluación
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el tipo de evaluacion esta asociada a una revision de metas
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT tipo_eval ".
				 "  FROM srh_revision_metas".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND tipo_eval = '".$as_codeval."' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->tipoevaluacion  MÉTODO->uf_select_tipo_evaluacion_metas  ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	}

	function uf_srh_delete_tipoevaluacion($as_codeval,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_tipoevaluacion
		//         areaess: public 
		//      Argumento: $as_codeval   // codigo de tipo de evaluación
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un tipo de evaluación en la tabla de srh_tipoevaluacion
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 22/11/2007						Fecha Última Modificación: 22/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=false;
	     $lb_existe=true;
		
		if (($this->uf_select_aspectos_evaluacion ($as_codeval)===false)&&
			($this->uf_select_tipo_evaluacion_psicologica($as_codeval)===false)&&
			($this->uf_select_tipo_evaluacion_requisitos($as_codeval)===false)&&
			($this->uf_select_tipo_evaluacion_entrevista ($as_codeval)===false) &&
			($this->uf_select_tipo_evaluacion_eficiencia ($as_codeval)===false)&&
			($this->uf_select_tipo_evaluacion_desempeno($as_codeval)===false)&&
			($this->uf_select_tipo_evaluacion_metas ($as_codeval)===false))
		 {
		    $lb_existe=false;
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM srh_tipoevaluacion".
						 " WHERE codeval= '".$as_codeval. "'".
						 "AND codemp='".$this->ls_codemp."'"; 
			
			
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->tipoevaluacion MÉTODO->uf_srh_delete_tipoevaluacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el tipo de evaluación".$as_codeval;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		return array($lb_valido,$lb_existe);
	} // end function uf_srh_delete_tipoevaluacion


	
	
function uf_srh_buscar_tipoevaluacion($as_codeval,$as_deneval)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_tipoevaluacion
		//         Access: private
		//      Argumento: $as_codeval  // codigo de el tipo de evaluación
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca un tipo de evaluación  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 22/11/2007						Fecha Última Modificación: 22/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_coddestino="txtcodeval";
		$ls_dendestino="txtdeneval";
		$ls_codescdestino="txtcodesc";
		$ls_denescdestino="txtdenesc";
	
		
		$lb_valido=true;
		$ls_sql="SELECT * FROM srh_tipoevaluacion, srh_escalageneral".
				" WHERE codeval like '".$as_codeval."' ".
				"   AND deneval like '".$as_deneval."' ".
				"   AND srh_tipoevaluacion.codesc = srh_escalageneral.codesc ".
			   " ORDER BY codeval";
	
	 
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tipoevaluacion MÉTODO->uf_srh_buscar_tipoevaluacion( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
			$dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			 	
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
					
					$ls_codeval=$row["codeval"];
					$ls_deneval= htmlentities ($row["deneval"]);
					$ls_codesc=$row["codesc"];
					$ls_denesc= htmlentities ($row["denesc"]);
					
					
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codeval']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codeval']." ^javascript:aceptar(\"$ls_codeval\",\"$ls_deneval\",\"$ls_codesc\",\"$ls_denesc\",\"$ls_coddestino\",\"$ls_dendestino\",\"$ls_codescdestino\",\"$ls_denescdestino\");^_self"));
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_deneval));												
					$row_->appendChild($cell);
					
					
			
			}
			return $dom->saveXML();
		
		
		}
      
		
	} // end function uf_srh_buscar_tipoevaluacion
	

}// end   class sigesp_srh_c_tipoevaluacion
?>