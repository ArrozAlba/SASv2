<?php

class sigesp_srh_c_enfermedades
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_enfermedades($path)
	{   
	    require_once($path."shared/class_folder/class_sql.php");
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
		//         Access: public (sigesp_srh_p_enfermedades)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de un registro de una enfermedad del personal
		//    Description: Funcion que genera un código de registro de una enfermedad del personal
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:17/01/2008							Fecha Última Modificación:17/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(nroreg) AS numero FROM srh_enfermedades ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_nroreg = $la_datos["numero"][0]+1;
    $ls_nroreg = str_pad ($ls_nroreg,10,"0","left");
	 return $ls_nroreg;
  }

	
  
  function uf_srh_getEnfermedad($as_nroreg,&$pa_datos="")
  {  
  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getEnfermedad																			  	    //
		//         access: public (sigesp_srh_enfermedades)														         	    //
		//      Argumento: $as_nroreg    // numero del registro de la enfermedad												//
		//                 $pa_datos    //  arreglos donde  se cargaran lo datos de la consulta									//
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que realiza una busqueda del registro de una enfermedad en la tabla srh_enfermedades	  		//
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 20/11/2007							Fecha Última Modificación: 20/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  
  
    $ls_sql = "SELECT * FROM srh_enfermedades ".
	          "WHERE nroreg = '$as_nroreg'";
    $lb_hay = $this->seleccionar($ls_sql, $pa_datos);
    return $lb_hay;
  }
  
  
  
 
  
function uf_srh_guardarEnfermedad ($ao_enfermedad,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarEnfermedad																		    //
		//         access: public (sigesp_srh_enfermedades)														                //
		//      Argumento: $ao_enfermedad    // arreglo con los datos de la enfermedad										    //
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              //
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta o modifica un registro de enfermedad en la tabla srh_enfermedades                //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 20/11/2007							Fecha Última Modificación: 20/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$as_nroreg=$ao_enfermedad->nroreg;
	
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	 $ao_enfermedad->fecelab=$this->io_funcion->uf_convertirdatetobd($ao_enfermedad->fecelab);
	 $ao_enfermedad->fecini=$this->io_funcion->uf_convertirdatetobd($ao_enfermedad->fecini);
	 
	 
	  $ls_sql = "UPDATE srh_enfermedades SET ".
	            "fecelab = '$ao_enfermedad->fecelab', ".
	            "codper = '$ao_enfermedad->codper', ".
	            "codenf = '$ao_enfermedad->codenf', ".
	            "fecini = '$ao_enfermedad->fecini', ".
				"observacion = '$ao_enfermedad->obs', ".
				"diarepenf = '$ao_enfermedad->diarepenf' ".
	            "WHERE nroreg= '$ao_enfermedad->nroreg' AND codemp='".$this->ls_codemp."'" ;
		
				
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el registro de Enfermedad".$as_nroreg;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	 $ao_enfermedad->fecelab=$this->io_funcion->uf_convertirdatetobd($ao_enfermedad->fecelab);
	 $ao_enfermedad->fecini=$this->io_funcion->uf_convertirdatetobd($ao_enfermedad->fecini);

	
	  $ls_sql = "INSERT INTO srh_enfermedades (nroreg, fecelab, codper, codenf, fecini, observacion, diarepenf, codemp) ".	  
	            "VALUES ('$ao_enfermedad->nroreg','$ao_enfermedad->fecelab','$ao_enfermedad->codper','$ao_enfermedad->codenf','$ao_enfermedad->fecini', '$ao_enfermedad->obs','$ao_enfermedad->diarepenf', '".$this->ls_codemp."')";
				

	
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el registro de Enfermedad ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->enfermedad MÉTODO->guardarEnfermedad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				$this->io_sql->commit();
		}
		
		
	return $lb_guardo;
  }
	
	
	
	
function uf_srh_eliminarEnfermedad($as_nroreg, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarEnfermedad																		//
		//      Argumento: $as_nroreg        // numero del registro de la enfermedad									        //
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que elimina una solicitud de empleo en la tabla srh_enfermedades		                         //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 20/11/2007							Fecha Última Modificación: 20/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_enfermedades ".
	          "WHERE nroreg = '$as_nroreg'   AND codemp='".$this->ls_codemp."'";
			  
			  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->enfermedad MÉTODO->uf_srh_eliminarEnfermedad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el registro de Enfermedad ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
	return $lb_borro;
  }
	

	
function uf_srh_buscar_enfermedades($as_nroreg,$as_codper,$as_apeper,$as_nomper)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_enfermedades																			//
		//         access: public (sigesp_srh_enfermedades)													                    //
		//      Argumento: $as_nroreg   //  numero del registro de le enfermedad						                        //
		//                 $as_codper   //  codula del trabajador                                                               //
		//                 $as_apeper   //  apellido del trabajador                                                             //
		//                 $as_nomper   //  nombre del trabajador                                                               //
		//                 $as_coduniad   //  codigo de la unidad administrativa                                                //
		//	      Returns: Retorna un XML  																							
		//    Description: Funcion busca una enfermedad en la tabla srh_enfermedades y crea un XML para mostrar        	
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 20/11/2007							Fecha Última Modificación: 20/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
	
	    $ls_nrodestino="txtnroreg";
		$ls_coddestino="txtcodper";
		$ls_fecelabdestino="txtfecelab";
		$ls_nomdestino="txtnomper";
		$ls_codenfdestino="txtcodenf";
		$ls_desdestino="txtdenenf";
		$ls_fecinidestino="txtfecini";
		$ls_repdestino="txtrep";
		$ls_obsdestino="txtobs";
		
		
		$lb_valido=true;
		
		
				
				
		$ls_sql= "SELECT *  FROM srh_enfermedades INNER JOIN srh_tipoenfermedad ON (srh_tipoenfermedad.codenf = srh_enfermedades.codenf) INNER JOIN sno_personal ON (sno_personal.codper = srh_enfermedades.codper)".
				" WHERE srh_enfermedades.nroreg like '$as_nroreg' ".
			    "   AND sno_personal.codper like '$as_codper' ".
				"   AND sno_personal.nomper like '$as_nomper' ".
				"   AND sno_personal.apeper like '$as_apeper' ".
				
			   " ORDER BY nroreg";
		
	 
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->enfermedad MÉTODO->uf_srh_buscar_enfermedades( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);	
					
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			         
					 			     
					$ls_nroreg=$row["nroreg"];
					$ls_fecelab=$this->io_funcion->uf_formatovalidofecha($row["fecelab"]);
				    $ls_fecelab=$this->io_funcion->uf_convertirfecmostrar($ls_fecelab);
					$ls_codper=$row["codper"];
					$ls_apeper= trim (htmlentities ($row["apeper"]));
					$ls_nomper= trim (htmlentities ($row["nomper"]));
					$ls_codenf=$row["codenf"];
					$ls_denenf= trim (htmlentities ($row["denenf"]));
				    $ls_fecini=$this->io_funcion->uf_formatovalidofecha($row["fecini"]);
				    $ls_fecini=$this->io_funcion->uf_convertirfecmostrar($ls_fecini);
				    $ls_obs= trim (htmlentities ($row["observacion"]));
   				    $ls_rep= trim ($row["diarepenf"]);
								
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['nroreg']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
				
					$cell->appendChild($dom->createTextNode($row['nroreg']." ^javascript:aceptar(\"$ls_nroreg\", \"$ls_codper\", \"$ls_fecelab\", \"$ls_apeper\", \"$ls_nomper\", \"$ls_codenf\", \"$ls_denenf\",\"$ls_fecini\", \"$ls_obs\",\"$ls_rep\",  \"$ls_nrodestino\", \"$ls_coddestino\", \"$ls_fecelabdestino\", \"$ls_nomdestino\", \"	$ls_codenfdestino\", \"$ls_desdestino\",\"$ls_fecinidestino\", \"$ls_obsdestino\", \"$ls_repdestino\");^_self"));
					
								
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_fecelab));												
					$row_->appendChild($cell);
					
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_codper));												
					$row_->appendChild($cell);
					
					if ($ls_apeper!='0'){
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_nomper.'  '.$ls_apeper));												
					$row_->appendChild($cell);								
					}
					else 
					{
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_nomper));												
					$row_->appendChild($cell); 
					}
				
			}
			return $dom->saveXML();
		
			
			
		
		}
      
		
	} // end function buscar_enfermedades
	


}// end   class sigesp_srh_c_enfermedades
?>