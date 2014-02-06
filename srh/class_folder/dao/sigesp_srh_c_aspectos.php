<?php

class sigesp_srh_c_aspectos
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_aspectos($path)
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
	
function uf_srh_getProximoCodigo($as_codeval, $as_codaspaux)
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
  
    $ls_sql = "SELECT MAX(codasp) AS numero FROM srh_aspectos_evaluacion WHERE codeval = '$as_codeval' ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
	 if ($la_datos["numero"][0]+1 == 1)
	 {
          $ls_codasp = $as_codaspaux.'0'.($la_datos["numero"][0]+1);
	}
	else
	{
	    $ls_codasp = '0'.($la_datos["numero"][0]+1);
	}
	return $ls_codasp;
  }
	   
	function uf_srh_select_aspectos($as_codasp, $as_codeval)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_aspectos
		//         areaess: public 
		//      Argumento: $as_codasp    // codigo de aspecto de evaluación
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un aspecto de evaluación en la tabla de  srh_aspectos_evaluacion
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 22/11/2007							Fecha Última Modificación: 22/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_aspectos_evaluacion  ".
				  " WHERE codasp='".trim($as_codasp)."' AND codeval='".trim($as_codeval)."'".
				  " AND codemp='".$this->ls_codemp."'" ;
	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->aspecto_evaluación MÉTODO->uf_srh_select_aspectos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_aspectos



 function uf_srh_guardar_aspectos  ($ao_aspecto,$as_insmod, $aa_seguridad)
  {
    $li_det = 0;
	$lb_guardo=false;
	while ($li_det < count($ao_aspecto->detalle))
	{
	  $lb_valido = $this-> uf_srh_select_aspectos($ao_aspecto->detalle[$li_det]->codasp,$ao_aspecto->codeval);
	  
	  if (($lb_valido) && ($as_insmod=='modificar'))
	  
	  {
	     $lb_guardo = $this->uf_srh_update_aspectos($ao_aspecto->detalle[$li_det],$aa_seguridad);
	  }
	  
	  else if ((!$lb_valido))
	  {
	     $lb_guardo = $this->uf_srh_insert_aspectos($ao_aspecto->detalle[$li_det],$aa_seguridad);
	  }
	  $li_det++;
	}
	
	return array($lb_valido,$lb_guardo);    
  }



function  uf_srh_insert_aspectos($ao_aspecto,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_aspectos
		//         areaess: public (sigesp_srh_d_aspectos_evaluacion)
		//      Argumento: $as_codasp   // codigo de aspecto de evaluación
	    //                 $as_denasp   // denominacion de aspecto de evaluación
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta una aspecto de evaluación en la tabla de srh_aspectos_evaluacion
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 22/11/2007							Fecha Última Modificación: 22/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO srh_aspectos_evaluacion (codemp, codasp, denasp, codeval) ".
					" VALUES('".$this->ls_codemp."','$ao_aspecto->codasp','$ao_aspecto->denasp','$ao_aspecto->codeval')";		
		 
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->aspecto_evaluación MÉTODO->uf_srh_insert_aspectos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el aspecto de evalucion ".$ao_aspecto->codasp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_aspectos



function uf_srh_update_aspectos($ao_aspecto,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_aspectos
		//         areaess: public 
		//      Argumento: $ao_aspectos   // arreglo con los datos de item de evaluación
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica una item de evaluación en la tabla de srh_items_evaluacion
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 26/11/2007							Fecha Última Modificación: 26/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		$ls_sql = "UPDATE  srh_aspectos_evaluacion SET   denasp='$ao_aspecto->denasp'   ".
				   " WHERE codasp='$ao_aspecto->codasp' AND codeval='$ao_aspecto->codeval' ".
				   " AND codemp='".$this->ls_codemp."'";
				   
				   
		
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->aspecto  MÉTODO->uf_srh_update_aspectos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el aspecto de evaluacion ".$ao_aspecto->codasp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_aspectos

	
 function uf_select_aspectos_items ($as_codeval, $as_codasp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_aspectos_items
		//		   Access: private
 		//	    Arguments: as_codeval // código de la evaluación
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el aspecto de evaluacion esta asociada a un item de evaluacion
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/04/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codeval, codasp ".
				 "  FROM srh_items_evaluacion".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND codeval = '".$as_codeval."' AND codasp = '".$as_codasp."'  ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->aspecto  MÉTODO->uf_select_aspectos_items  ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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

	

function uf_srh_delete_aspectos($as_codeval, $as_codasp,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_aspectos
		//         areaess: public 
		//      Argumento: $as_codeval   // codigo de la evaluación
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina una aspecto de evaluación en la tabla de srh_aspectos_evaluacion  
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 22/11/2007							Fecha Última Modificación: 22/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe= $this->uf_select_aspectos_items ($as_codeval, $as_codasp);
		if ($lb_existe)
		{
				
			$lb_valido=false;
			
		}
		else
		{
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM srh_aspectos_evaluacion".
						 " WHERE codeval= '".$as_codeval. "' AND codasp= '".$as_codasp. "'".
						 "AND codemp='".$this->ls_codemp."'"; 
			
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->aspecto_evaluación MÉTODO->uf_srh_delete_aspectos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó los aspectos de la evaluacion evaluación ".$as_codeval;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		return array($lb_valido,$lb_existe);
	} // end function uf_srh_delete_aspectos
	
	
	
 function uf_srh_buscar_aspectos($as_codeval, $as_deneval)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_aspectos
		//         Access: private
		//      Argumento: $as_deneval // denominación de la evaluacion
		//			       $as_codeva  // código de la evaluacion
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca un aspecto de evaluación  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 22/11/2007							Fecha Última Modificación: 22/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		
		$ls_codevaldestino="txtcodeval";
		$ls_denevaldestino="txtdeneval";
	
		
		$lb_valido=true;
		$ls_sql=" SELECT DISTINCT (srh_aspectos_evaluacion.codeval),srh_tipoevaluacion.* FROM srh_aspectos_evaluacion, srh_tipoevaluacion".
				" WHERE srh_aspectos_evaluacion.codeval = srh_tipoevaluacion.codeval ".
				" AND srh_aspectos_evaluacion.codeval like '".$as_codeval."' ".
				"  AND srh_tipoevaluacion.deneval like '".$as_deneval."' ".
			   " ORDER BY srh_aspectos_evaluacion.codeval";
		
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->aspecto_evaluación MÉTODO->uf_srh_buscar_aspectos( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
					$ls_deneval= htmlentities($row["deneval"]);
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codeval']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codeval']." ^javascript:aceptar(\"$ls_codeval\",\"$ls_deneval\",\"$ls_codevaldestino\",\"$ls_denevaldestino\");^_self"));
				
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_deneval));												
					$row_->appendChild($cell);
					
					
			
			}
			return $dom->saveXML();
		
		
		}
      
		
	} // end function uf_srh_buscar_aspectos
	
	
	
function uf_srh_load_aspectos_campos($as_codeval,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_aspectos_campos
		//	    Arguments: as_codeval  // código de laevaluación
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de un requerimiento de cargo
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * ". 
				"  FROM srh_aspectos_evaluacion ".
				" WHERE srh_aspectos_evaluacion.codemp='".$this->ls_codemp."'".
				"   AND codeval='".$as_codeval."'".
				" ORDER BY codasp ";
			
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->aspectos MÉTODO->uf_srh_load_aspectos_campos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
		 $num=$this->io_sql->num_rows($rs_data);
           
		  if ($num!=0) {
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_codasp=$row["codasp"];
				$ls_denasp= htmlentities($row["denasp"]);
				
				
				$ao_object[$ai_totrows][1]="<input name=txtcodasp".$ai_totrows." type=text id=txtcodasp".$ai_totrows." class=sin-borde size=15 onBlur='javascript: generar_codigo(".$ai_totrows.");' maxlength=15 value='".$ls_codasp."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtdenasp".$ai_totrows." type=text id=txtdenasp".$ai_totrows." class=sin-borde size=70  onKeyUp='ue_validarcomillas(this);' value='".$ls_denasp."'  >";			
				$ao_object[$ai_totrows][3]="<a href=javascript:uf_agregar_dt(".$ai_totrows."); align=center><img src=../../../../shared/imagebank/tools/grabar.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$ao_object[$ai_totrows][4]="<a href=javascript:uf_delete_dt(".$ai_totrows."); align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";	
							
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
	
	
function uf_srh_buscar_aspectos_items ($as_codeval, $as_codasp, $as_denasp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_aspectos_items
		//         Access: private
		//      Argumento: $as_codeva  // código de la evaluacion
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca un aspecto de evaluación  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 08/05/200/						Fecha Última Modificación: 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		
		$ls_coddestino="txtcodasp";
		$ls_dendestino="txtdenasp";
	
		
		$lb_valido=true;
		$ls_sql=" SELECT * FROM srh_aspectos_evaluacion, srh_tipoevaluacion ".
				" WHERE srh_aspectos_evaluacion.codeval = srh_tipoevaluacion.codeval ".
				" AND srh_aspectos_evaluacion.codeval = '".$as_codeval."' ".
				" AND codasp like '".$as_codasp."' ".
				"  AND denasp like '".$as_denasp."' ".
			   " ORDER BY codasp";
			
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->aspecto_evaluación MÉTODO->uf_srh_buscar_aspectos_items ( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
			$dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			 	
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			      			  
				    $ls_codasp=$row["codasp"];
					$ls_denasp=htmlentities($row["denasp"]);
					
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codasp']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codasp']." ^javascript:aceptar(\"$ls_codasp\",\"$ls_denasp\",\"$ls_coddestino\",\"$ls_dendestino\");^_self"));
				
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(htmlentities($row["denasp"])));												
					$row_->appendChild($cell);


					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(htmlentities($row["deneval"])));												
					$row_->appendChild($cell);
					
					
			
			}
			return $dom->saveXML();
		
		
		}
		
	}
	
	

}// end   class sigesp_srh_c_aspectos
?>