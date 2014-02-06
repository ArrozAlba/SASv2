<?php

class sigesp_srh_c_resultados_evaluacion_aspirante
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_resultados_evaluacion_aspirante($path)
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

 function getCodPersonal($as_codper, $as_codcon,&$ao_datos="")       
{
		 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: getCodPersonal																                    
		//      Argumento: $as_codper   //  cédula del personal										                        
		//                 $as_codcon  //  código del concurso
		//                 $ao_datos   //  arreglo con datos del personal                                         
		//	      Returns: Retorna un XML  																						
		//    Description: Funcion que busca un codigo de concurso y un personal en la tabla srh_resultados_evaluacion_aspirante          
		//	   Creado Por: Ing. María Beatriz Unda																			    						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_codcon="";
		$as_codper=trim($as_codper);
		$ls_sql = " SELECT codcon FROM srh_resultados_evaluacion_aspirante ".
				  " WHERE codemp='". $this->ls_codemp."'".
				  " AND  codper = '$as_codper' AND codcon = '$as_codcon'";
			
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
				$this->io_msg->message("CLASE->resultados_evaluacion_aspirante  MÉTODO->getCodPersonal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			
		}
		else
		{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$lb_existe=true;
					$ls_codcon=$row['codcon'];
				}
				
				$this->io_sql->free_result($rs_data);
		}
		return array($lb_existe,$ls_codcon);
  }	// end function getCodPersonal		
	
  function uf_srh_getresultados_evaluacion_aspirantes($as_nropas,&$pa_datos="")
  {  
  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getresultados_evaluacion_aspirantes																				
		//         access: public (sigesp_srh_resultados_evaluacion_aspirante)															
		//      Argumento: $as_nropas    // numero de la resultados_evaluacion_aspirante																
		//                 $pa_datos    //  arreglos donde  se cargaran lo datos de la consulta									
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que realiza una busqueda de un registro de resultado de aspirante en la tabla
		//				   srh_resultados_evaluacion_aspirante		        
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 11/12/2007Fecha Última Modificación: 11/12/2007//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  
  
    $ls_sql = "SELECT * FROM srh_resultados_evaluacion_aspirante ".
	          "WHERE nropas = '$as_nropas'";
    $lb_hay = $this->seleccionar($ls_sql, $pa_datos);
    return $lb_hay;
  }
  
  
  
 
  
function uf_srh_guardarresultados_evaluacion_aspirante($ao_resultados,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarresultados_evaluacion_aspirante																				
		//         access: public (sigesp_srh_resultados_evaluacion_aspirante)													 
		//      Argumento: $ao_resultados    // arreglo con los datos de la resultados_evaluacion_aspirante										    	                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)         	    
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica un registro de resultado de aspirante en la tabla
		//	               srh_resultados_evaluacion_aspirante             		
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 11/12/2007													Fecha Última Modificación: 11/12/2007//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$as_codper= trim ($ao_resultados->codper);
	$fecha=$this->io_funcion->uf_convertirdatetobd($ao_resultados->fecha);

  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	  $ao_resultados->fecha=$this->io_funcion->uf_convertirdatetobd($ao_resultados->fecha);
	 
	  $ls_sql = "UPDATE srh_resultados_evaluacion_aspirante SET ".
	           	"fecreg = '$fecha', ".
   				"toteva	   = '$ao_resultados->totaleval', ".
	            "conclusion = '$ao_resultados->conclu' ".
				"WHERE codper= '$as_codper'  AND codcon = '$ao_resultados->codcon'  AND codemp='".$this->ls_codemp."'" ;
				
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el registro de resultado de aspirante".$as_codper;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	  $fecha=$this->io_funcion->uf_convertirdatetobd($ao_resultados->fecha);
	 	
	  $ls_sql = "INSERT INTO srh_resultados_evaluacion_aspirante (codper, fecreg, codcon, toteva, conclusion,codemp) ".	  
	            "VALUES ('$as_codper','$fecha','$ao_resultados->codcon','$ao_resultados->totaleval','$ao_resultados->conclu', '".$this->ls_codemp."')";
						

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el registro de resultado de aspirante".$as_codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->resultados_evaluacion_aspirante MÉTODO->uf_srh_guardarresultados_evaluacion_aspirante ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				$this->io_sql->commit();
		}
		
	return $lb_valido;
  }

function uf_srh_eliminarresultados_evaluacion_aspirante($as_codper, $as_codcon ,$aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarresultados_evaluacion_aspirante																			
		//        access:  public (sigesp_srh_resultados_evaluacion_aspirante)															
		//      Argumento: $as_nropas        // numero de el registro de resultado de aspirante//
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina un registro de resultado de aspirante en la tabla 
		//                 srh_resultados_evaluacion_aspirante
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 11/12/2007													Fecha Última Modificación: 11/12/2007//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_resultados_evaluacion_aspirante ".
	          "WHERE codper = '$as_codper'  AND codcon = '$as_codcon' AND codemp='".$this->ls_codemp."'";
	 
			  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->resultados_evaluacion_aspirante MÉTODO->uf_srh_eliminarresultados_evaluacion_aspirante ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Registro de resultado de aspirante".$as_codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
	return $lb_borro;
  }
	


	
function uf_srh_buscar_resultados_evaluacion_aspirante($as_codper,$as_fecha1,$as_fecha2)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_resultados_evaluacion_aspirante																				
		//         access: public (sigesp_srh_resultados_evaluacion_aspirante)													
		//      Argumento: $as_cedper   //  cedula del personal                                                              	//
		//                 $as_apeper   //  apellido del personal                                                            	//
		//                 $as_nomper   //  nombre del personal                                                                 //
		//                $as_fecha   //  fecha de incorporacion en la resultados_evaluacion_aspirante												
		//	      Returns: Retorna un XML  																						//
		//    Description: Funcion busca un registro de resultado de aspirante en la tabla srh_resultados_evaluacion_aspirante y 
		//					crea un XML para mostrar  			
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 11/12/2007													Fecha Última Modificación: 11/12/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
		$as_fecha1=$this->io_funcion->uf_convertirdatetobd($as_fecha1);
		$as_fecha2=$this->io_funcion->uf_convertirdatetobd($as_fecha2);
		
	    $ls_codperdestino="txtcodper";
		$ls_fechadestino="txtfecha";
		$ls_nomdestino="txtnomper";
		$ls_codcondestino="txtcodcon";
		$ls_descondestino="txtdescon";
		$ls_pun1destino="txtpunreqmin";
		$ls_pun2destino="txtpunevalpsi";
		$ls_pun3destino="txtpunenttec";
		$ls_totaldestino="txttoteva";
		$ls_concludestino="txtconclu";
		$lb_valido=true;
		
		
				
		$ls_sql= " SELECT srh_resultados_evaluacion_aspirante.*, srh_concursante.nomper, srh_concursante.apeper, ".
		         " srh_concurso.descon, srh_requisitos_minimos.punreqmin,srh_evaluacion_psicologica.punevapsi, ".
				 " srh_entrevista_tecnica.punenttec".
				 " FROM srh_resultados_evaluacion_aspirante".
		         " LEFT  JOIN srh_requisitos_minimos ON  ".
				 " (trim(srh_resultados_evaluacion_aspirante.codper) = trim(srh_requisitos_minimos.codper) ".
				 " AND srh_resultados_evaluacion_aspirante.codcon = srh_requisitos_minimos.codcon) ".
				 " LEFT  JOIN srh_evaluacion_psicologica ON ".
				 " (trim(srh_resultados_evaluacion_aspirante.codper) = trim(srh_evaluacion_psicologica.codper) ".
				 " AND srh_resultados_evaluacion_aspirante.codcon = srh_evaluacion_psicologica.codcon) ".
				 " LEFT  JOIN srh_entrevista_tecnica ON ".
				 " (trim(srh_resultados_evaluacion_aspirante.codper) = trim(srh_entrevista_tecnica.codper)".
				 " AND srh_resultados_evaluacion_aspirante.codcon = srh_entrevista_tecnica.codcon) ".
				 " LEFT JOIN srh_concursante ON (trim(srh_concursante.codper) = trim(srh_resultados_evaluacion_aspirante.codper))".
				 " INNER JOIN srh_concurso ON (srh_resultados_evaluacion_aspirante.codcon = srh_concurso.codcon) ".
				 " WHERE srh_resultados_evaluacion_aspirante.codper like '$as_codper' ".
				 " AND srh_resultados_evaluacion_aspirante.fecreg between  '".$as_fecha1."' AND '".$as_fecha2."' ".				
			     " ORDER BY srh_resultados_evaluacion_aspirante.codper";
	
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->resultados_evaluacion_aspirante MÉTODO->uf_srh_buscar_resultados_evaluacion_aspirante( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);		
			 	
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
					$ls_codper=$row["codper"];
					
					  
					$ls_fecha=$this->io_funcion->uf_formatovalidofecha($row["fecreg"]);
				    $ls_fecha=$this->io_funcion->uf_convertirfecmostrar($ls_fecha);
					
					$ls_apeper = trim (htmlentities  ($row["apeper"]));
					
					  
					$ls_nomper = trim (htmlentities   ($row["nomper"]));
					
					$ls_codcon=$row["codcon"];
					$ls_descon= trim (htmlentities  ($row["descon"]));
					
					$li_pun1=trim ($row["punreqmin"]);
					$li_pun2=trim ($row["punevapsi"]);
					$li_pun3=trim ($row["punenttec"]);
					$li_total=($row["punreqmin"]+$row["punevapsi"]+$row["punenttec"]);
					$ls_conclu=htmlentities ($row["conclusion"]);
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$ls_codper);
					$cell = $row_->appendChild($dom->createElement('cell'));   
										
					
					$cell->appendChild($dom->createTextNode($ls_codper." ^javascript:aceptar(\"$ls_codper\",\"$ls_fecha\", \"$ls_apeper\",\"$ls_nomper\",\"$ls_codcon\",\"$ls_descon\",\"$li_pun1\",\"$li_pun2\",\"$li_pun3\",\"$li_total\", \"$ls_codperdestino\", \"$ls_fechadestino\", \"$ls_nomdestino\", \"$ls_codcondestino\",\"$ls_descondestino\", \"$ls_pun1destino\", \"$ls_pun2destino\",\"$ls_pun3destino\",\"$ls_totaldestino\", \"$ls_conclu\",\"$ls_concludestino\");^_self"));
					
					if ($ls_apeper!='0'){
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_nomper.'  '.$ls_apeper));												
					$row_->appendChild($cell);								
					}
					else 
					{
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_nomper));												
					$row_->appendChild($cell); }
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_fecha));												
					$row_->appendChild($cell);
					
			
			}
			
            
			return $dom->saveXML();
		
	
		
		}
      
		
	} // end function buscar_resultados_evaluacion_aspirante
	
function uf_srh_consultarresultados_evaluacion_aspirante ($as_codper, $as_codcon,&$li_pun1,&$li_pun2,&$li_pun3,&$li_total)
{
		
		$lb_valido=true;
		
		$ls_sql= "SELECT * FROM srh_requisitos_minimos INNER JOIN srh_evaluacion_psicologica ON (srh_requisitos_minimos.codper = srh_evaluacion_psicologica.codper AND srh_requisitos_minimos.codcon = srh_evaluacion_psicologica.codcon) INNER JOIN srh_entrevista_tecnica ON (srh_requisitos_minimos.codper = srh_entrevista_tecnica.codper AND srh_requisitos_minimos.codcon = srh_entrevista_tecnica.codcon) ".
				" WHERE srh_requisitos_minimos.codper = '$as_codper' ".
				"   AND srh_requisitos_minimos.codcon  = '$as_codcon' ".
				" ORDER BY srh_requisitos_minimos.codper";	 
	 	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->resultados_evaluacion_aspirante MÉTODO->uf_srh_buscar_resultados_evaluacion_aspirante( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}
		else
		{ 
		   $num=$this->io_sql->num_rows($rs_data);
           
		   if ($num!=0) {
				while ($row=$this->io_sql->fetch_row($rs_data)) 
				{   $li_pun1=trim ($row["punreqmin"]);
					$li_pun2=trim ($row["punevapsi"]);
					$li_pun3=trim ($row["punenttec"]);
					$li_total=($row["punreqmin"]+$row["punevapsi"]+$row["punenttec"]);
				}
		}
		  else 
		  {
		     $this->io_msg->message("No se encontraron Registros con esos datos.");
			
		  }
	
		return $lb_valido;
	
		}
      
		
	} // end function buscar_resultados_evaluacion_aspirante
	

}// end   class sigesp_srh_c_resultados_evaluacion_aspirante
?>