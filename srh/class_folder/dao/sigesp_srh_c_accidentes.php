<?php

class sigesp_srh_c_accidentes
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_accidentes($path)
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
		//         Access: public (sigesp_srh_p_accidentes)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de un registro de un accidente de personal
		//    Description: Funcion que genera un código de registro de un accidente de personal
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:17/01/2008							Fecha Última Modificación:17/01/2008 Prueba
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(nroreg) AS numero FROM  srh_accidentes ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_nroreg = $la_datos["numero"][0]+1;
    $ls_nroreg= str_pad ($ls_nroreg,10,"0","left");
	return $ls_nroreg;
  }
	
  	
 	
	
  function uf_srh_getAccidente($as_nroreg,&$pa_datos="")
  {  
  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getAccidente																			  	    //
		//         access: public (sigesp_srh_p_accidentes)														         	    //
		//      Argumento: $as_nroreg    // numero del registro del accidente													//
		//                 $pa_datos    //  arreglos donde  se cargaran lo datos de la consulta									//
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que realiza una busqueda del registro de un accidente en la tabla srh_accidentes		  		//
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 20/11/2007							Fecha Última Modificación: 20/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  
  
    $ls_sql = "SELECT * FROM srh_accidentes ".
	          "WHERE nroreg = '$as_nroreg'";
    $lb_hay = $this->seleccionar($ls_sql, $pa_datos);
    return $lb_hay;
  }
  
  
  
 
  
function uf_srh_guardarAccidente ($ao_accidente,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardaramonestacion																		    //
		//         access: public (sigesp_srh_accidentes)														                //
		//      Argumento: $ao_accidente    // arreglo con los datos del accidente											    //
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              //
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta o modifica un registro de accidente en la tabla srh_accidentes                	//
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 20/11/2007							Fecha Última Modificación: 20/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	$as_nroreg = $ao_accidente->nroreg;
	
	
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	 $ao_accidente->fecelab=$this->io_funcion->uf_convertirdatetobd($ao_accidente->fecelab);
	 $ao_accidente->fecacc=$this->io_funcion->uf_convertirdatetobd($ao_accidente->fecacc);
	 
	 
	  $ls_sql = "UPDATE srh_accidentes SET ".
	            "fecelab = '$ao_accidente->fecelab', ".
	            "codper = '$ao_accidente->codper', ".
	            "codacc = '$ao_accidente->codacc', ".
	            "fecacc = '$ao_accidente->fecacc', ".
				"descripcion = '$ao_accidente->des', ".
				"testigos = '$ao_accidente->testigos', ".
				"reposo = '$ao_accidente->reposo' ".
	            "WHERE nroreg= '$ao_accidente->nroreg' AND codemp='".$this->ls_codemp."'" ;
		
				
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el registro del Accidente".$as_nroreg;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	 $ao_accidente->fecelab=$this->io_funcion->uf_convertirdatetobd($ao_accidente->fecelab);
	 $ao_accidente->fecacc=$this->io_funcion->uf_convertirdatetobd($ao_accidente->fecacc);

	
	  $ls_sql = "INSERT INTO srh_accidentes (nroreg, fecelab, codper, codacc, fecacc, descripcion, testigos, reposo, codemp) ".	  
	            "VALUES ('$ao_accidente->nroreg','$ao_accidente->fecelab','$ao_accidente->codper','$ao_accidente->codacc','$ao_accidente->fecacc', '$ao_accidente->des','$ao_accidente->testigos','$ao_accidente->reposo', '".$this->ls_codemp."')";
				
				
	
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el registro del Accidente ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->accidentes MÉTODO->guardarAccidente ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
	
	
	
function uf_srh_eliminarAccidente($as_nroreg, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminaramonestacion																			//
		//        access:  public (sigesp_srh_accidentes)															            //
		//      Argumento: $as_nroreg        // numero del registro del accidente										        //
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que elimina un accidente en la tabla srh_accidentes		                         			//
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 20/11/2007							Fecha Última Modificación: 20/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_accidentes ".
	          "WHERE nroreg = '$as_nroreg'   AND codemp='".$this->ls_codemp."'";
			  
			  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->accidentes MÉTODO->eliminarAccidente ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el registro del Accidente ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
	return $lb_borro;
  }
	
	
	
function uf_srh_buscar_accidentes($as_nroreg,$as_codper,$as_apeper,$as_nomper)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_accidentes																				//
		//         access: public (sigesp_srh_accidentes)													                    //
		//      Argumento: $as_nroreg   //  número del registro del accidete							                        //
		//                 $as_codper   //  cédula del trabajador                                                               //
		//                 $as_apeper   //  apellido del trabajador                                                             //
		//                 $as_nomper   //  nombre del trabajador                                                               //
		//	      Returns: Retorna un XML  																						//
		//    Description: Funcion busca un accidente en la tabla srh_accidentes y crea un XML para mostrar   				    //
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 20/11/2007							Fecha Última Modificación: 20/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
	
	    $ls_nrodestino="txtnroreg";
		$ls_coddestino="txtcodper";
		$ls_fecelabdestino="txtfecelab";
		$ls_nomdestino="txtnomper";
		$ls_codaccdestino="txtcodacc";
		$ls_denaccdestino="txtdenacc";
		$ls_fecaccdestino="txtfecacc";
		$ls_repdestino="txtrep";
		$ls_desdestino="txtdes";
		$ls_testigosdestino="txttestigos";
		
		
		$lb_valido=true;
		
		
				
				
		$ls_sql= "SELECT *  FROM srh_accidentes INNER JOIN srh_tipoaccidentes ON (srh_tipoaccidentes.codacc = srh_accidentes.codacc) INNER JOIN sno_personal ON (sno_personal.codper = srh_accidentes.codper)".
				" WHERE srh_accidentes.nroreg like '$as_nroreg' ".
			    "   AND sno_personal.codper like '$as_codper' ".
				"   AND sno_personal.nomper like '$as_nomper' ".
				"   AND sno_personal.apeper like '$as_apeper' ".
				
			   " ORDER BY nroreg";
		
	 
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->accidentes MÉTODO->uf_srh_buscar_accidentes( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
					$ls_apeper=trim (htmlentities ($row["apeper"]));
					$ls_nomper=trim (htmlentities ($row["nomper"]));
					$ls_codacc=trim (htmlentities ($row["codacc"]));
					$ls_denacc=trim (htmlentities ($row["denacc"]));
					$ls_fecacc=$this->io_funcion->uf_formatovalidofecha($row["fecacc"]);
				    $ls_fecacc=$this->io_funcion->uf_convertirfecmostrar($ls_fecacc);
				   
				    $ls_des= trim (htmlentities ($row["descripcion"]));
					$ls_testigos=trim(htmlentities ($row["testigos"]));
   				    $ls_rep=trim ($row["reposo"]);
								
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['nroreg']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
				
					$cell->appendChild($dom->createTextNode($row['nroreg']." ^javascript:aceptar(\"$ls_nroreg\", \"$ls_codper\", \"$ls_fecelab\", \"$ls_apeper\", \"$ls_nomper\", \"$ls_codacc\", \"$ls_denacc\",\"$ls_fecacc\", \"$ls_des\",\"$ls_testigos\",\"$ls_rep\",  \"$ls_nrodestino\", \"$ls_coddestino\", \"$ls_fecelabdestino\", \"$ls_nomdestino\", \"	$ls_codaccdestino\", \"$ls_denaccdestino\",\"$ls_fecaccdestino\", \"$ls_desdestino\", \"$ls_testigosdestino\", \"$ls_repdestino\");^_self"));
					
								
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_fecelab));												
					$row_->appendChild($cell);
					
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_codper));												
					$row_->appendChild($cell);
					
					if ($ls_apeper!='0') {
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(($ls_nomper).'  '.($ls_apeper)));												
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
      
		
	} // end function buscar_accidentes
	


}// end   class sigesp_srh_c_accidentes
?>