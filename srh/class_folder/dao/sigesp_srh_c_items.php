<?php

class sigesp_srh_c_items
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_items($path)
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
	
function uf_srh_getProximoCodigo($as_codeval, $as_codasp, $as_coditeaux)
  {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getProximoCodigo
		//         Access: public (sigesp_srh_p_accidentes)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de un registro de un accidente de personal
		//    Description: Funcion que genera un código de registro de un accidente de personal
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:17/01/2008							Fecha Última Modificación:17/01/2008 Prueba
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(codite) AS numero FROM srh_items_evaluacion WHERE codasp = '$as_codasp' AND codeval = '$as_codeval' ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
	 if ($la_datos["numero"][0]+1 == 1)
	 {
          $ls_codite = $as_coditeaux.'0'.($la_datos["numero"][0]+1);
	 }
	 else
	 {
	    $ls_codite = '0'.($la_datos["numero"][0]+1);
	 }
	
	return $ls_codite;
  }	
		
	
	function uf_srh_select_items($as_codite, $as_codasp, $as_codeval)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_items
		//         areaess: public 
		//      Argumento: $as_codite    // codigo de item de evaluación
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un item de evaluación en la tabla de  srh_items_evaluacion
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 26/11/2007							Fecha Última Modificación: 26/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_items_evaluacion  ".
				  " WHERE codite='".trim($as_codite)."' AND codasp='".trim($as_codasp)."' AND codeval='".trim($as_codeval)."'  ".
				  " AND codemp='".$this->ls_codemp."'" ;
	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->item MÉTODO->uf_srh_select_items ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_items
	
	
	
 function uf_srh_guardar_items  ($ao_items,$as_insmod, $aa_seguridad)
  {
    $li_det = 0;
	$lb_guardo=false;
	while ($li_det < count($ao_items->detalle))
	{
	  $lb_valido = $this-> uf_srh_select_items($ao_items->detalle[$li_det]->codite,$ao_items->codasp,$ao_items->codeval);
	  
	  if (($lb_valido) && ($as_insmod=='modificar'))
	  
	  {
	     $lb_guardo = $this->uf_srh_update_items($ao_items->detalle[$li_det],$aa_seguridad);
	  }
	  
	  else if ((!$lb_valido))
	  {
	     $lb_guardo = $this->uf_srh_insert_items($ao_items->detalle[$li_det],$aa_seguridad);
	  }
	  $li_det++;
	}
	
	return array($lb_valido,$lb_guardo);    
  }


	

function uf_srh_update_items($ao_items,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_items
		//         areaess: public 
		//      Argumento: $as_items   // arreglo con los datos de item de evaluación
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica una item de evaluación en la tabla de srh_items_evaluacion
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 26/11/2007							Fecha Última Modificación: 26/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		  $ls_sql = "UPDATE  srh_items_evaluacion SET  denite='$ao_items->denite', valormax='$ao_items->valor' ".
				   " WHERE codite='$ao_items->codite' AND codasp='$ao_items->codasp' AND codeval='$ao_items->codeval' ".
				   " AND codemp='".$this->ls_codemp."'";
				   
				   
		
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->item  MÉTODO->uf_srh_update_items ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el item de evaluacion ".$ao_items->codite;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_items
	
	
	

function  uf_srh_insert_items($ao_items,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_items
		//      Argumento: $ao_items // arreglo con los datos de los items
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta una item de evaluación en la tabla de srh_items_evaluacion
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 26/11/2007							Fecha Última Modificación: 26/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO srh_items_evaluacion (codite, denite, codeval, codasp, valormax, codemp) ".
					" VALUES('$ao_items->codite','$ao_items->denite', '$ao_items->codeval', '$ao_items->codasp', '$ao_items->valor', '".$this->ls_codemp."')" ;
	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->item MÉTODO->uf_srh_insert_items ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el item de evalucion ".$ao_items->codite;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_items


	
  function uf_select_items_entrevista_tecnica ($as_codite,$as_codeval)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_items_entrevista_tecnica
		//		   Access: private
 		//	    Arguments: as_codite  // código del item de evaluación
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el item de evaluacion esta asociada a una evaluacion de entrevista tecnica
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 25/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT srh_dt_entrevista_tecnica.codite, srh_entrevista_tecnica.tipo_eval  ".
				 "  FROM srh_dt_entrevista_tecnica, srh_entrevista_tecnica".
				 "  WHERE srh_dt_entrevista_tecnica.codemp='".$this->ls_codemp."' ".
				 "  AND srh_entrevista_tecnica.codemp='".$this->ls_codemp."' ".
				 "    AND codite='".$as_codite."' AND tipo_eval='".$as_codeval."' ";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->item MÉTODO->uf_select_items_entrevista_tecnica  ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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


 function uf_select_items_requisitos_minimos ($as_codite,$as_codeval)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_items_requisitos_minimos
		//		   Access: private
 		//	    Arguments: as_codite  // código del item de evaluación
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el item de evaluacion esta asociada a una evaluacion de requisitos minimos
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 25/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT srh_dt_requisitos_minimos.codite, srh_requisitos_minimos.tipo_eval ".
				 "  FROM srh_dt_requisitos_minimos, srh_requisitos_minimos".
				 "  WHERE srh_dt_requisitos_minimos.codemp='".$this->ls_codemp."' ".
				 "  AND srh_requisitos_minimos.codemp='".$this->ls_codemp."' ".
				 "    AND codite='".$as_codite."' AND tipo_eval='".$as_codeval."' ";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->item ->uf_select_items_requisitos_minimos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	
	
	function uf_select_items_evaluacion_psicologica ($as_codite,$as_codeval)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_items_evaluacion_psicologica
		//		   Access: private
 		//	    Arguments: as_codite  // código del item de evaluación
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el item de evaluacion esta asociada a una evaluacion psicológica
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 25/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT srh_dt_evaluacion_psicologica.codite, srh_evaluacion_psicologica.tipo_eval ".
				 "  FROM srh_dt_evaluacion_psicologica, srh_evaluacion_psicologica".
				 "  WHERE srh_dt_evaluacion_psicologica.codemp='".$this->ls_codemp."' ".
				 "  AND srh_evaluacion_psicologica.codemp='".$this->ls_codemp."' ".
				 "    AND codite='".$as_codite."' AND tipo_eval='".$as_codeval."' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->item ->uf_select_items_evaluacion_psicologica  ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	
	
	
function uf_select_items_evaluacion_eficiencia ($as_codite,$as_codeval)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_items_evaluacion_eficiencia
		//		   Access: private
 		//	    Arguments: as_codite  // código del item de evaluación
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el item de evaluacion esta asociada a una evaluacion eficiencia
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 25/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT srh_dt_evaluacion_eficiencia.codite, srh_evaluacion_eficiencia.tipo_eval ".
				 "  FROM srh_dt_evaluacion_eficiencia, srh_evaluacion_eficiencia".
				 "  WHERE srh_dt_evaluacion_eficiencia.codemp='".$this->ls_codemp."' ".
				  "  AND srh_evaluacion_eficiencia.codemp='".$this->ls_codemp."' ".
				 "    AND codite='".$as_codite."' AND tipo_eval = '".$as_codeval."'  ";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->item ->uf_select_items_evaluacion_eficiencia  ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	
	
	function uf_select_items_evaluacion_ascenso ($as_codite,$as_codeval)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_items_evaluacion_ascenso
		//		   Access: private
 		//	    Arguments: as_codite  // código del item de evaluación
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el item de evaluacion esta asociada a una evaluacion de ascenso
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 25/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT srh_dt_evaluacion_ascenso.codite, srh_evaluacion_ascenso.tipoeval ".
				 "  FROM srh_dt_evaluacion_ascenso, srh_evaluacion_ascenso".
				 "  WHERE srh_dt_evaluacion_ascenso.codemp='".$this->ls_codemp."' ".
				 "  AND srh_evaluacion_ascenso.codemp='".$this->ls_codemp."' ".
				 "    AND codite='".$as_codite."' AND tipoeval='".$as_codeval."' ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->item ->uf_select_items_evaluacion_ascenso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	
function uf_select_items_evaluacion_desempeno ($as_codite,$as_codeval)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_items_evaluacion_ascenso
		//		   Access: private
 		//	    Arguments: as_codite  // código del item de evaluación
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el item de evaluacion esta asociada a una evaluacion de desempeno
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 25/04/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT srh_competencias_evaluacion_desempeno.codite, srh_evaluacion_desempeno.tipo_eval ".
				 "  FROM srh_competencias_evaluacion_desempeno, srh_evaluacion_desempeno".
				 "  WHERE srh_competencias_evaluacion_desempeno.codemp='".$this->ls_codemp."' ".
				 "  AND srh_evaluacion_desempeno.codemp='".$this->ls_codemp."' ".
				 "    AND codite='".$as_codite."' AND tipo_eval='".$as_codeval."' ";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->item ->uf_select_items_evaluacion_desempeno  ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	
	

	function uf_srh_delete_items($as_codite,$as_codasp,$as_codeval,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_items
		//         areaess: public 
		//      Argumento: $as_codite   // codigo de item de evaluación
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina una item de evaluación en la tabla de srh_items_evaluacion  
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 26/11/2007							Fecha Última Modificación: 26/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=false;
	     $lb_existe=true;
		
		if (($this->uf_select_items_entrevista_tecnica ($as_codite, $as_codeval)===false)&&
		     ($this->uf_select_items_requisitos_minimos($as_codite,$as_codeval)===false)&&
			 ($this->uf_select_items_evaluacion_psicologica ($as_codite,$as_codeval)===false) &&
			 ($this->uf_select_items_evaluacion_eficiencia ($as_codite,$as_codeval)===false)&&
			 ($this->uf_select_items_evaluacion_ascenso($as_codite,$as_codeval)===false)&&
			 ($this->uf_select_items_evaluacion_desempeno($as_codite,$as_codeval)===false))
		 {
		    $lb_existe=false;
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM srh_items_evaluacion".
						 " WHERE codite= '".$as_codite. "' AND codasp= '".$as_codasp. "' AND codeval= '".$as_codeval. "'".
						 "AND codemp='".$this->ls_codemp."'"; 

			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->item MÉTODO->uf_srh_delete_items ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el item de evaluacion ".$as_codite;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		
		else
		{
		  $lb_existe=true;
		  $lb_valido=false;
		}
		return array($lb_valido,$lb_existe);
	} // end function uf_srh_delete_items
	


	
	
function uf_srh_buscar_items($as_codeval,$as_codasp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_items
		//         Access: private
		//      Argumento: $as_codeval  // codigo de  evaluacion
		//                 $as_codasp  // codigo del aspecto
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca un item de evaluación  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 26/11/2007							Fecha Última Modificación: 26/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		
		$ls_codevaldestino="txtcodeval";
		$ls_denevaldestino="txtdeneval";
		$ls_codaspdestino="txtcodasp";
		$ls_denaspdestino="txtdenasp";
		
	
		
		$lb_valido=true;
		$ls_sql="SELECT DISTINCT (srh_items_evaluacion.codasp),srh_aspectos_evaluacion.*, srh_tipoevaluacion.* FROM srh_items_evaluacion, srh_aspectos_evaluacion, srh_tipoevaluacion ".
		  		" WHERE srh_items_evaluacion.codasp=srh_aspectos_evaluacion.codasp  ".
				" AND srh_items_evaluacion.codeval=srh_tipoevaluacion.codeval  ".
				"  AND srh_items_evaluacion.codeval like '".$as_codeval."' ".
				"  AND srh_items_evaluacion.codasp like '".$as_codasp."' ".
			   " ORDER BY srh_items_evaluacion.codasp";
		
	
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->item  MÉTODO->uf_srh_buscar_items( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
					$ls_codasp=$row["codasp"];
					$ls_denasp=htmlentities  ($row["denasp"]);
					
					
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codeval']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codeval']." ^javascript:aceptar(\"$ls_codeval\",\"$ls_deneval\",\"$ls_codasp\",\"$ls_denasp\",\"$ls_codevaldestino\",\"$ls_denevaldestino\", \"$ls_codaspdestino\", \"$ls_denaspdestino\");^_self"));
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_deneval));												
					$row_->appendChild($cell);
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($row['codasp']));												
					$row_->appendChild($cell);
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_denasp));												
					$row_->appendChild($cell);
					
					
					
			
			}
			return $dom->saveXML();
		
		
		}
      
		
	} // end function uf_srh_buscar_items
	

function uf_srh_load_items_campos($as_codeval,$as_codasp,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_items_campos
		//	    Arguments: as_codeval  // código de laevaluación
		//			       as_codasp  //  código del aspecto 	
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de un requerimiento de cargo
		// Fecha Creación: 08/05/2008							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * ". 
				"  FROM srh_items_evaluacion ".
				" WHERE srh_items_evaluacion.codemp='".$this->ls_codemp."'".
				"   AND codeval='".$as_codeval."'".
				"   AND codasp='".$as_codasp."'".
				" ORDER BY codasp ";
			
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->aspectos MÉTODO->uf_srh_load_items_campos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
		 $num=$this->io_sql->num_rows($rs_data);
           
		  if ($num!=0) {
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_codite=$row["codite"];
				$ls_denite= htmlentities($row["denite"]);
				$li_valor= $row["valormax"];
				
				$ao_object[$ai_totrows][1]="<input name=txtcodite".$ai_totrows." type=text id=txtcodite".$ai_totrows." class=sin-borde size=15 maxlength=15  onKeyUp='javascript: ue_validarnumero(this);'  value='".$ls_codite."' onBlur='javascript: generar_codigo(".$ai_totrows.");' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtdenite".$ai_totrows." type=text id=txtdenite".$ai_totrows." onKeyUp='ue_validarcomillas(this);' class=sin-borde size=70  value='".$ls_denite."'>";
				$ao_object[$ai_totrows][3]="<input name=txtvalor".$ai_totrows." class=sin-borde  type=text id=txtvalor".$ai_totrows." onKeyPress='return validarreal2(event,this);' size=8 maxlength=5 value='".$li_valor."'>";		
				$ao_object[$ai_totrows][4]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools/grabar.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$ao_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt(".$ai_totrows.");    align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";				
					
							
			}
			$this->io_sql->free_result($rs_data);
			}
		else 
		 {
		    $this->io_msg->message("No hay aspectos asociados a esa evaluación.");
	 		$ai_totrows=0;	
			
		
		  }
		  return $lb_valido;
		}
		
	}
	
	

}// end   class sigesp_srh_c_items
?>