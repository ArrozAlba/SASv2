<?php

class sigesp_srh_c_necesidad_adiestramiento
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_necesidad_adiestramiento($path)
	{  
		require_once($path."shared/class_folder/class_fecha.php");
	    require_once($path."shared/class_folder/class_sql.php");
		require_once($path."shared/class_folder/class_datastore.php");
		require_once($path."shared/class_folder/class_mensajes.php");
		require_once($path."shared/class_folder/sigesp_include.php");
		require_once($path."shared/class_folder/sigesp_c_seguridad.php");
		require_once($path."shared/class_folder/class_funciones.php");
	    $this->io_fecha=new class_fecha();
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
		//         Access: public (sigesp_srh_p_necesidad_adiestramiento)
		//      Argumento: 
		//	      Returns: Retorna el nuevo número de una necesidad de adiestramiento
		//    Description: Funcion que genera un número de una necesidad de adiestramiento
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(nroreg) AS numero FROM srh_necesidad_adiestramiento ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_nroreg = $la_datos["numero"][0]+1;
    $ls_nroreg= str_pad ($ls_nroreg,10,"0","left");
     return $ls_nroreg;
  }
		
   
function uf_srh_guardar_necesidad_adiestramiento ($ao_necesidad,$ps_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_necesidad_adiestramiento																		
		//         access: public (sigesp_srh_p_necesidad_adiestramiento)
		//      Argumento: $ao_necesidad    // arreglo con los datos de la necesidad de adiestramiento    
		//		            $ps_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica una evaluación de desempeno en la tabla srh_necesidad_adiestramiento
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 12/05/2008							Fecha Última Modificación: 12/05/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$as_nroreg= $ao_necesidad->nroreg;
  	if ($ps_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	 $ao_necesidad->fecha=$this->io_funcion->uf_convertirdatetobd($ao_necesidad->fecha);
   	     	 
	  $ls_sql = "UPDATE srh_necesidad_adiestramiento SET ".
		  		"fecha    = '$ao_necesidad->fecha' , ".
				"codunivi = '$ao_necesidad->coduni' ,  ".
				"comptec  = '$ao_necesidad->comptec' ,  ".
				"objadi   = '$ao_necesidad->obj' ,  ".
				"areadi   = '$ao_necesidad->area' ,  ".
				"estadi   = '$ao_necesidad->estra' ,  ".
				"obsadi   = '$ao_necesidad->obs'  ".
				"WHERE nroreg = '$ao_necesidad->nroreg' AND codemp='".$this->ls_codemp."'" ;


			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó la necesidad de adiestramiento ".$as_nroreg;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	  $ao_necesidad->fecha=$this->io_funcion->uf_convertirdatetobd($ao_necesidad->fecha);
	 	
	  $ls_sql = "INSERT INTO srh_necesidad_adiestramiento (nroreg, fecha,codunivi, comptec, objadi, estadi, obsadi, areadi, codemp) ".	  
	            "VALUES ('$ao_necesidad->nroreg','$ao_necesidad->fecha', '$ao_necesidad->coduni','$ao_necesidad->comptec', '$ao_necesidad->obj', '$ao_necesidad->estra', '$ao_necesidad->obs', '$ao_necesidad->area','".$this->ls_codemp."')";

		
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la necesidad de adiestramiento ".$ao_necesidad->nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->necesidad_adiestramiento MÉTODO->uf_srh_guardar_necesidad_adiestramiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				$lb_guardo=false;
				if ($lb_valido)
				{
				   //Guardamos el detalle de la necesidad de adiestramiento
					$lb_guardo1 = $this->uf_guardarCompetencias_necesidad_adiestramiento($ao_necesidad, $aa_seguridad);
					$lb_guardo2 = $this->uf_guardarCausas_necesidad_adiestramiento($ao_necesidad, $aa_seguridad);
					//Guardamos las Personas involucradas en la  necesidad de adiestramiento (Evaluado, Evaluador y Trabajador)
					$lb_guardo3 = $this->guardarPersonas_necesidad_adiestramiento($ao_necesidad, $aa_seguridad);
					
					
				}
				
				if (($lb_guardo1) && ($lb_guardo2) && ($lb_guardo3))
				{
				  $this->io_sql->commit();
				  $lb_valido=true;
				}	
				else
				{
				 $this->io_sql->rollback();
				 $lb_valido=false;
				 }
		}
		
	
	return $lb_valido;
  }
	
	
	
function uf_guardarCompetencias_necesidad_adiestramiento ($ao_necesidad, $aa_seguridad)

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardarCompetencias_necesidad_adiestramiento																		
		//         access: public (sigesp_srh_p_necesidad_adiestramiento)
		//      Argumento: $ao_necesidad    // arreglo con los datos de la necesidad de adiestramiento         
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica una evaluación de desempeno en la tabla srh_necesidad_adiestramiento
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 12/05/2008							Fecha Última Modificación: 12/05/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  {
    //Borramos los registros anteriores 
	$this-> uf_srh_eliminar_competencias_adiestramiento($ao_necesidad->nroreg, $aa_seguridad);
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_obj = 0;
	while (($li_obj < count($ao_necesidad->competencias)) &&
	       ($lb_guardo))
	{
	  $lb_guardo = $this-> uf_srh_guardar_competencias_necesidad_adiestramiento($ao_necesidad->competencias[$li_obj], $aa_seguridad);
	  $li_obj++;
	}
	
	return $lb_guardo;    
  }


function uf_guardarCausas_necesidad_adiestramiento ($ao_necesidad, $aa_seguridad)
  {
  
   		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardarCausas_necesidad_adiestramiento																		
		//         access: public (sigesp_srh_p_necesidad_adiestramiento)
		//      Argumento: $ao_necesidad    // arreglo con los datos de la necesidad de adiestramiento         
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica una evaluación de desempeno en la tabla srh_necesidad_adiestramiento
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 12/05/2008							Fecha Última Modificación: 12/05/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    //Borramos los registros anteriores 
	$this-> uf_srh_eliminar_causas_adiestramiento($ao_necesidad->nroreg, $aa_seguridad);
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_obj = 0;
	while (($li_obj < count($ao_necesidad->causas)) &&
	       ($lb_guardo))
	{
	  $lb_guardo = $this-> uf_srh_guardar_causas_adiestramiento($ao_necesidad->causas[$li_obj], $aa_seguridad);
	  $li_obj++;
	}
	
	return $lb_guardo;    
  }

	
function guardarPersonas_necesidad_adiestramiento ($ao_necesidad, $aa_seguridad)
  {
   
	$lb_guardo = true;
	$as_nro = $ao_necesidad->nroreg;
	  $lb_guardo1 = $this-> uf_srh_eliminar_persona($as_nro);
	  if ($lb_guardo1===true) {
		
	  }
     
  	 $lb_guardo = $this-> uf_srh_guardar_trabajador ($ao_necesidad, $aa_seguridad); 
	 $lb_guardo = $this-> uf_srh_guardar_supervisor ($ao_necesidad, $aa_seguridad); 
	
	return $lb_guardo;    
  }
	
	
function uf_srh_eliminar_necesidad_adiestramiento($as_nroreg, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_necesidad_adiestramiento																		
		//        access:  public (sigesp_srh_p_necesidad_adiestramiento)														
		//      Argumento: $as_nroreg        // código de la necesidad de adiestramiento 
		//                 $aa_seguridad     //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina una solicitud de empleo en la tabla srh_necesidad_adiestramiento         
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 12/05/2008							Fecha Última Modificación: 12/05/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
	$this-> uf_srh_eliminar_competencias_adiestramiento($as_nroreg, $aa_seguridad);
	$this-> uf_srh_eliminar_causas_adiestramiento($as_nroreg, $aa_seguridad);
	$this-> uf_srh_eliminar_persona($as_nroreg, $aa_seguridad);

    $ls_sql = "DELETE FROM srh_necesidad_adiestramiento ".
	          "WHERE nroreg = '$as_nroreg' AND codemp='".$this->ls_codemp."'";

	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->necesidad_adiestramiento MÉTODO->uf_srh_eliminar_necesidad_adiestramiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la necesidad de adiestramiento ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				
					$this->io_sql->commit();
			}
	
	
	return $lb_borro;
  }
	

	
function uf_srh_buscar_necesidad_adiestramiento($as_nroreg,$as_fecha1,$as_fecha2)
	{
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_necesidad_adiestramiento																		
		//         access: public (sigesp_srh_necesidad_adiestramiento)												
		//      Argumento: $as_nroreg   //  código de la necesidad de adiestramiento
		// 				   $as_codper   //   codigo de la persona                                                             
		//                 $as_apeper   //   apellido de la persona                                                            
		//                 $as_nomper   //   nombre de la persona                                                             
		//                 $as_fecha   //    fecha de la necesidad de adiestramiento	     
		//         Returns: Retorna un XML  																						
		//    Description: Funcion busca una evaluación en la tabla srh_necesidad_adiestramiento y crea un XML para mostrar    
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 12/05/2008							Fecha Última Modificación: 12/05/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
		$as_fecha1=$this->io_funcion->uf_convertirdatetobd($as_fecha1);
		$as_fecha2=$this->io_funcion->uf_convertirdatetobd($as_fecha2);
		
		
		
		$ls_nroregdestino="txtnroreg";
		$ls_fechadestino="txtfecha";
		$ls_codunidestino="txtcodunivi";
		$ls_denunidestino="txtdenunivi";
		$ls_codperdestino="txtcodper";
		$ls_nomperdestino="txtnomper";
		$ls_codcarperdestino="txtcodcarper";
		$ls_nivacaperdestino="txtnivacaper";
		$ls_codsupdestino="txtcodsup";
		$ls_nomsupdestino="txtnomsup";
		$ls_codcarsupdestino="txtcodcarsup";
		$ls_comptecdestino="txtcompe";
		$ls_areadestino="txtarea";
		$ls_objdestino="txtobj";
		$ls_estradestino="txtestcap";
		$ls_obsdestino="txtobs";

		$lb_valido=true;
		

				
		$ls_sql= "SELECT * FROM srh_necesidad_adiestramiento INNER JOIN srh_persona_necesidad_adiestramiento ON (srh_persona_necesidad_adiestramiento.nroreg = srh_necesidad_adiestramiento.nroreg) INNER JOIN sno_personal ON (sno_personal.codper = srh_persona_necesidad_adiestramiento.codper) INNER JOIN srh_unidadvipladin ON  (srh_unidadvipladin.codunivipladin = srh_necesidad_adiestramiento.codunivi) ".
		 " JOIN sno_personalnomina  ON  (srh_persona_necesidad_adiestramiento.codper=sno_personalnomina.codper)   ".
		 " LEFT JOIN sno_asignacioncargo ON  (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar and sno_personalnomina.codnom=sno_asignacioncargo.codnom)  ".
		" LEFT JOIN sno_cargo  ON  (sno_personalnomina.codcar=sno_cargo.codcar and sno_personalnomina.codnom=sno_cargo.codnom)  ".
		" JOIN sno_nomina ON ( sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0')  ".
		" WHERE  srh_necesidad_adiestramiento.nroreg like '$as_nroreg' ".
		"   AND srh_necesidad_adiestramiento.fecha BETWEEN  '".$as_fecha1."' AND '".$as_fecha2."' ".
		" ORDER BY srh_persona_necesidad_adiestramiento.nroreg";


	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->necesidad_adiestramiento MÉTODO->uf_srh_buscar_necesidad_adiestramiento( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		     $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);		
			 $ls_control=0;	
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			        $ls_nroreg=$row["nroreg"];
					$ls_fecha=$this->io_funcion->uf_formatovalidofecha($row["fecha"]);
				    $ls_fecha=$this->io_funcion->uf_convertirfecmostrar($ls_fecha);
					
					
					
					$ls_coduni=$row["codunivipladin"];
					$ls_denuni= trim (htmlentities ($row["denunivipladin"]));
					$ls_comptec=trim (htmlentities ($row["comptec"]));
					$ls_area= trim (htmlentities   ($row["areadi"]));
					$ls_obj=trim (htmlentities   ($row["objadi"]));
					$ls_estra=trim (htmlentities  ($row["estadi"]));
					$ls_obs=trim(htmlentities ($row["obsadi"]));
					$ls_cargo1= trim (htmlentities ($row["denasicar"]));
				    $ls_cargo2= trim (htmlentities ($row["descar"]));
					
					
					
					if ($row["tipo"]=="P")
					{
					  $ls_apeper = trim (htmlentities  ($row["apeper"]));
				      $ls_nomper=  trim (htmlentities  ($row["nomper"]));
					  $ls_nomper= $ls_nomper.' '.$ls_apeper;
				      $ls_codper=$row["codper"];
					  
					   if ($ls_cargo1!="Sin Asignación de Cargo")
				      {
					   $ls_codcarper=$ls_cargo1;
				       }
				      if ($ls_cargo2!="Sin Cargo")
				      {
					   $ls_codcarper=$ls_cargo2;
				       }	
					  
					  $ls_nivacaper=trim ($row["nivacaper"]);
					  
					   switch($ls_nivacaper)
						{
						case "":
							$ls_nivacaper="Ninguno";
							break;
						case "0":
						    $ls_nivacaper="Ninguno";
						    break;
						case "1":
							$ls_nivacaper="Primaria";
							break;
						case "2":
							$ls_nivacaper="Bachiller";
							break;
						case "3":
							$ls_nivacaper="Tecnico Superior";
							break;
					   case "4":
							$ls_nivacaper="Universitario";
							break;
					   case "5":
							$ls_nivacaper="Maestria";
							break;
					  case "6":
							$ls_nivacaper="Postgrado";
							break;
					  case "7":
							$ls_nivacaper="Doctorado";
							break;
						}
					  
					  $ls_control=$ls_control + 1; 
					}
					else if ($row["tipo"]=="S")
					{
					   $ls_apesup = trim (htmlentities ($row["apeper"]));
				       $ls_nomsup= trim (htmlentities  ($row["nomper"]));
					   $ls_nomsup= $ls_nomsup.' '.$ls_apesup;
				       $ls_codsup=$row["codper"];
					  
					   if ($ls_cargo1!="Sin Asignación de Cargo")
				       {
					  	 $ls_codcarsup=$ls_cargo1;
				       }
				       if ($ls_cargo2!="Sin Cargo")
				       {
					   	$ls_codcarsup=$ls_cargo2;
				       }	
					  
					 
					  $ls_control=$ls_control + 1;
					 }
					
			if ($ls_control=="2")  {
			
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['nroreg']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($row['nroreg']." ^javascript:aceptar(\"$ls_nroreg\",\"$ls_fecha\",\"$ls_coduni\",\"$ls_denuni\",\"$ls_codper\",\"$ls_nomper\",\"$ls_codcarper\",\"$ls_nivacaper\",\"$ls_codsup\",\"$ls_nomsup\",\"$ls_codcarsup\",\"$ls_comptec\",\"$ls_area\",\"$ls_obj\",\"$ls_estra\",\"$ls_obs\",\"$ls_nroregdestino\",\"$ls_fechadestino\",\"$ls_codunidestino\",\"$ls_denunidestino\",\"$ls_codperdestino\",\"$ls_nomperdestino\",\"$ls_codcarperdestino\",\"$ls_nivacaperdestino\",\"$ls_codsupdestino\",\"$ls_nomsupdestino\",\"$ls_codcarsupdestino\",\"$ls_comptecdestino\",\"$ls_areadestino\",\"$ls_objdestino\",\"$ls_estradestino\",\"$ls_obsdestino\");^_self"));
				
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_fecha));												
					$row_->appendChild($cell);
			
					$ls_control=0;
					}
			
			}
			return $dom->saveXML();
		
		}
      
		
	} // end function buscar_necesidad_adiestramiento
	

//FUNCIONES PARA EL MANEJO DEL DETALLE DE LA NECESIDAD DE ADIESTRAMIENTO

function uf_srh_guardar_competencias_necesidad_adiestramiento($ao_necesidad, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_competencias_necesidad_adiestramiento																												
		//      Argumento: $ao_necesidad   // arreglo con los datos de los detalle de la necesidad de adiestramiento      
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta una competencia de evaluación en la tabla srh_dt_competencias_adiestramiento
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 12/05/2008							Fecha Última Modificación: 12/05/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 
	 
	  $ls_sql = "INSERT INTO srh_dt_competencias_adiestramiento (nroreg, codcompadi, prioridad, codemp) ".	  
	            " VALUES ('$ao_necesidad->nroreg','$ao_necesidad->codcom','$ao_necesidad->prio', '".$this->ls_codemp."')";

 

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la competencia ".$ao_necesidad->codcom." asociada al registro ".$ao_necesidad->nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->necesidad_adiestramiento MÉTODO->uf_srh_guardar_competencias_necesidad_adiestramiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
	
function uf_srh_eliminar_competencias_adiestramiento($as_nroreg, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_competencias_adiestramiento																													
		//      Argumento: $as_nroreg       // código de la evaluación 
		//	               $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																				    
		//    Description: Funcion que elimina competencias de evaluación en la tabla srh_dt_competencias_adiestramiento
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 12/05/2008							Fecha Última Modificación: 12/05/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_dt_competencias_adiestramiento ".
	          " WHERE nroreg='$as_nroreg' AND codemp='".$this->ls_codemp."'";
    


	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->necesidad_adiestramiento MÉTODO->uf_srh_eliminar_competencias_adiestramiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó las competencias de la necesidad de adiestramiento del registro".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
			
	
	return $lb_borro;
	
  }
  
  
  
  
 function uf_srh_guardar_causas_adiestramiento($ao_necesidad, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_causas_adiestramiento															     													
		//      Argumento: $ao_necesidad    // arreglo con los datos de los detalle de la necesidad de adiestramiento     
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta un odi en la tabla srh_dt_causas_adiestramiento
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 12/05/2008							Fecha Última Modificación: 12/05/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 
	 
	  $ls_sql = "INSERT INTO srh_dt_causas_adiestramiento (nroreg, codcauadi, codemp) ".	  
	            " VALUES ('$ao_necesidad->nroreg','$ao_necesidad->codcau','".$this->ls_codemp."')";

    

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la causa de necesidad de adiestramineto ".$ao_necesidad->codcau." asociada al registro".$ao_necesidad->nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->necesidad_adiestramiento MÉTODO->uf_srh_guardar_causas_adiestramiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
	
function uf_srh_eliminar_causas_adiestramiento($as_nroreg, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_srh_eliminar_causas_adiestramiento																										
		//      Argumento: $as_nroreg        // código de la necesidad de adiestramiento
		//	               $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																				    
		//    Description: Funcion que elimina un ODI en la tabla srh_dt_causas_adiestramiento                      
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 12/05/2008							Fecha Última Modificación: 12/05/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_dt_causas_adiestramiento ".
	          " WHERE nroreg='$as_nroreg' AND codemp='".$this->ls_codemp."'";
    


	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->necesidad_adiestramiento MÉTODO->uf_srh_eliminar_causas_adiestramiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó las causas de necesidad de adiestramiento aspciadas al registro ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
			
	
	return $lb_borro;
	
  }
  
  
//FUNCIONES PARA EL MANEJO DEL LAS PERSONAS INVOLUACRADAS EN LA NECESIDAD DE ADIESTRAMIENTO

function uf_srh_eliminar_persona($as_nroreg)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_persona																																		
		//      Argumento: $as_nroreg	      // código de la necesidad de adiestramiento						
		//                 $aa_seguridad     //  arreglo de registro de seguridad                                  
		//	      Returns: Retorna un Booleano																					
		//    Description: Elimina a las personas involucradas en una necesidad de adiestramiento
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 12/05/2008							Fecha Última Modificación: 12/05/2008						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_persona_necesidad_adiestramiento ".
	          "WHERE nroreg = '$as_nroreg'   AND codemp='".$this->ls_codemp."'";
			  
			  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->necesidad_adiestramiento MÉTODO->uf_srh_eliminar_persona ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
					
				$this->io_sql->commit();
			}
	return $lb_borro;
  }
	



function uf_srh_guardar_trabajador ($ao_necesidad, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_trabajador																     
		//         access: public 														
		//      Argumento: $ao_necesidad    // arreglo con los datos de los detalle de la necesidad de adiestramiento							
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta un trabajador en una evaluacion de desempeño en la tabla 
		//                 srh_persona_necesidad_adiestramiento	        
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 12/05/2008							Fecha Última Modificación: 12/05/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 
		 
	  $ls_sql = "INSERT INTO srh_persona_necesidad_adiestramiento (nroreg,codper, tipo, codemp) ".	  
	            "VALUES ('$ao_necesidad->nroreg','$ao_necesidad->codper','P','".$this->ls_codemp."')";
				
				

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó Persona de la necesidad de adiestramiento ".$ao_necesidad->nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->necesidad_adiestramiento MÉTODO->uf_srh_guardar_trabajador ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
  
 
 
 function uf_srh_guardar_supervisor ($ao_necesidad, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_supervisor															     	
		//         access: public 														  								  
		//      Argumento: $ao_necesidad    // arreglo con los datos de los detalle de la evaluacion de desempeño					
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta un supervisor en la tabla srh_persona_necesidad_adiestramiento
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 12/05/2008							Fecha Última Modificación: 12/05/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 
		 
	  $ls_sql = "INSERT INTO srh_persona_necesidad_adiestramiento (nroreg,codper, tipo, codemp) ".	  
	            "VALUES ('$ao_necesidad->nroreg','$ao_necesidad->codsup','S','".$this->ls_codemp."')";
				
				

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó Persona de la Revsion de ODI".$ao_necesidad->nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->necesidad_adiestramiento MÉTODO->uf_srh_guardar_supervisor ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	

// FUNCIONES PARA CONSULTAR

public function uf_srh_consultar_causas_adiestramiento(&$ai_totrows,&$ao_object)
{
		
		  		   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_consultar_causas_adiestramiento														     	
		//         access: public 														  								  
		//      Argumento: $ai_totrows   // total de filas del grid					
		//                 $ao_object   //  objeto de datos del grid
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que consulta las causas de adiestramiento
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 12/05/2008							Fecha Última Modificación: 12/05/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
		
		
		$lb_valido=true;
		
		$ls_sql= "SELECT * FROM srh_causas_adiestramiento  ".
				" ORDER BY codcauadi";
				

	 	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->necesidad_adiestramiento MÉTODO->uf_srh_consultar_causas_adiestramiento( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}
		else
		{ 
		   $num=$this->io_sql->num_rows($rs_data);
           
		  if ($num!=0) {
		       $ai_totrows=0;
			 		   
				while ($row=$this->io_sql->fetch_row($rs_data)) 
				{   			
					$ai_totrows++;
					$ls_codcau=$row["codcauadi"];
					$ls_dencau=htmlentities  ($row["dencauadi"]);
				
					$ao_object[$ai_totrows][1]="<input name=txtcodcauadi".$ai_totrows." type=text id=txtcodcauadi".$ai_totrows." class=sin-borde size=15 value='".$ls_codcau."'  readonly>";
					$ao_object[$ai_totrows][2]="<input name=txtdencauadi".$ai_totrows." type=text id=txtdencauadi".$ai_totrows." class=sin-borde size=50 value='".$ls_dencau."' readonly>";	
					$ao_object[$ai_totrows][3]="<select name=cmbselcau".$ai_totrows." id=cmbselcau".$ai_totrows.">
									<option value='S'>Si</option>
									<option value='N'selected >No</option></select> ";	
					
					
					  
					}
		
		}
		else 
		  {
		     $this->io_msg->message("No se encontraron Causas de Adiestramiento.");
	 		$ai_totrows=1;	
			$ao_object[$ai_totrows][1]="<input name=txtcodcauadi".$ai_totrows." type=text id=txtcodcauadi".$ai_totrows." class=sin-borde size=15 readonly>";
			$ao_object[$ai_totrows][2]="<input name=txtdencauadi".$ai_totrows." type=text id=txtdencauadi".$ai_totrows." class=sin-borde size=50 readonly>";	
			$ao_object[$ai_totrows][3]="<select name=cmbselcau".$ai_totrows." id=cmbselcau".$ai_totrows.">
									<option value='S'>Si</option>
									<option value='N'selected >No</option></select> ";	
			
			
		  }  
		return $lb_valido;
	
		}
      
		
	} // end uf_srh_consultar_causas_adiestramiento
	

public function uf_srh_consultar_competencias_adiestramiento(&$ai_totrows,&$ao_object)
{
		
		  		   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_consultar_competencias_adiestramiento														     	
		//         access: public 														  								  
		//      Argumento: $ai_totrows   // total de filas del grid					
		//                 $ao_object   //  objeto de datos del grid
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que consulta las competencias de adiestramiento
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 12/05/2008							Fecha Última Modificación: 12/05/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
		
		
		$lb_valido=true;
		
		$ls_sql= "SELECT * FROM srh_competencias_adiestramiento  ".
				" ORDER BY codcompadi";
				
	 	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->necesidad_adiestramiento MÉTODO->uf_srh_consultar_competencias_adiestramiento( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}
		else
		{ 
		   $num=$this->io_sql->num_rows($rs_data);
           
		  if ($num!=0) {
		       $ai_totrows=0;
			 		   
				while ($row=$this->io_sql->fetch_row($rs_data)) 
				{   			
					$ai_totrows++;
					$ls_codcomp=$row["codcompadi"];
					$ls_dencomp=htmlentities ($row["dencompadi"]);
				
					$ao_object[$ai_totrows][1]="<input name=txtcodcomp".$ai_totrows." type=text id=txtcodcomp".$ai_totrows." class=sin-borde size=15 value='".$ls_codcomp."' readonly  >";
					$ao_object[$ai_totrows][2]="<input name=txtdencomp".$ai_totrows." type=text id=txtdencomp".$ai_totrows." class=sin-borde size=50 value='".$ls_dencomp."' readonly>";
					$ao_object[$ai_totrows][3]="<select name=cmbprio".$ai_totrows." id=cmbprio".$ai_totrows.">
					  <option value='0' selected>No aplica</option>
					  <option value='1'>Urgente</option>
					  <option value='2'>Importante</option>
					  <option value='3'>Puede Esperar</option>
					</select>";	
					}
		
		}
		else 
		  {
		     $this->io_msg->message("No se encontraron Competencias de Adiestramiento.");
	 		$ai_totrows=1;	
			$ao_object[$ai_totrows][1]="<input name=txtcodcomp".$ai_totrows." type=text id=txtcodcomp".$ai_totrows." class=sin-borde size=15  readonly  >";
			$ao_object[$ai_totrows][2]="<input name=txtdencomp".$ai_totrows." type=text id=txtdencomp".$ai_totrows." class=sin-borde size=50 readonly>";
			$ao_object[$ai_totrows][3]="<select name=cmbprio".$ai_totrows." id=cmbprio".$ai_totrows.">
              <option value='0' selected>No aplica</option>
              <option value='1'>Urgente</option>
              <option value='2'>Importante</option>
              <option value='3'>Puede Esperar</option>
            </select>";		
			
			
		  }  
		return $lb_valido;
	
		}
      
		
	} // end uf_srh_consultar_competencias_adiestramiento
	
		



  
 function uf_srh_load_causas_adiestramiento($as_nroreg, &$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_srh_load_causas_adiestramiento
		//	    Arguments: $as_nroreg   // código de la necesidad de adiestramiento
		//				   $ai_totrows  // total de filas del detalle
		//				   $ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de un ODI de una necesidad de adiestramiento
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
			
		$ls_sql="SELECT * ".
				"  FROM  srh_dt_causas_adiestramiento,srh_causas_adiestramiento ".
				"  WHERE srh_dt_causas_adiestramiento.codemp='".$this->ls_codemp."'".
				"  AND  srh_causas_adiestramiento.codemp='".$this->ls_codemp."'".
				"  AND srh_dt_causas_adiestramiento.nroreg = '$as_nroreg' ".
				"  AND srh_dt_causas_adiestramiento.codcauadi = srh_causas_adiestramiento.codcauadi ".
				" ORDER BY srh_dt_causas_adiestramiento.codcauadi ";
       

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->necesidad_adiestramiento MÉTODO-> uf_srh_load_causas_adiestramiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_codcau= $row["codcauadi"];
				$ls_dencau= htmlentities ($row["dencauadi"]);
															
				$ao_object[$ai_totrows][1]="<input name=txtcodcauadi".$ai_totrows." type=text id=txtcodcauadi".$ai_totrows." class=sin-borde size=15 value='".$ls_codcau."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtdencauadi".$ai_totrows." type=text id=txtdencauadi".$ai_totrows." class=sin-borde size=50  value='".$ls_dencau."' readonly>";	
				$ao_object[$ai_totrows][3]="<select name=cmbselcau".$ai_totrows." id=cmbselcau".$ai_totrows.">
									<option value='S' selected>Si</option>
									<option value='N' >No</option></select> ";				
				
			}
			
			//PARA BUSCAR LAS OTRAS CAUSAS DE ADIESTRAMIENTO QUE NO FUERON SELECCIONADOS
			
			$ls_sql2= "SELECT * FROM srh_causas_adiestramiento ".
                      " WHERE not exists (SELECT * FROM srh_dt_causas_adiestramiento ".
                      " WHERE srh_dt_causas_adiestramiento.codemp='".$this->ls_codemp."'".
					  " AND srh_causas_adiestramiento.codemp='".$this->ls_codemp."'".
					  " AND srh_causas_adiestramiento.codcauadi = srh_dt_causas_adiestramiento.codcauadi ".
					  " ORDER BY srh_causas_adiestramiento.codcauadi) ";
				
				$rs_data=$this->io_sql->select($ls_sql2);
				if($rs_data===false)
				{
					$lb_valido=false;
					$this->io_msg->message("CLASE->necesidad_adiestramiento MÉTODO-> uf_srh_load_causas_adiestramiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				}
				else
				{
					$num=$this->io_sql->num_rows($rs_data);
           
		 		 if ($num!=0) 
				 {
					while($row=$this->io_sql->fetch_row($rs_data))
					{
						$ai_totrows++;
						$ls_codcau= $row["codcauadi"];
						$ls_dencau= htmlentities  ($row["dencauadi"]);
																	
						$ao_object[$ai_totrows][1]="<input name=txtcodcauadi".$ai_totrows." type=text id=txtcodcauadi".$ai_totrows." class=sin-borde size=15 value='".$ls_codcau."' readonly>";
						$ao_object[$ai_totrows][2]="<input name=txtdencauadi".$ai_totrows." type=text id=txtdencauadi".$ai_totrows." class=sin-borde size=50 value='".$ls_dencau."' readonly>";	
						$ao_object[$ai_totrows][3]="<select name=cmbselcau".$ai_totrows." id=cmbselcau".$ai_totrows.">
											<option value='S' >Si</option>
											<option value='N'  selected>No</option></select> ";				
						
					}
				}
				
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;

} // end  uf_srh_load_causas_adiestramiento
  
  
  

function uf_srh_load_competencias_adiestramiento ($as_nroreg, &$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_competencias_adiestramiento
		//	    Arguments: as_nroreg  // código de la necesidad de adiestramiento
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene las competencias de una necesidad de adiestramiento
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
				
		$ls_sql="SELECT * ".
				"  FROM srh_competencias_adiestramiento, srh_dt_competencias_adiestramiento ".
				"  WHERE srh_dt_competencias_adiestramiento.codemp='".$this->ls_codemp."'".
				"  AND srh_competencias_adiestramiento.codemp='".$this->ls_codemp."'".
				"  AND srh_dt_competencias_adiestramiento.nroreg = '$as_nroreg' ".
				"  AND srh_dt_competencias_adiestramiento.codcompadi = srh_competencias_adiestramiento.codcompadi ".
				" ORDER BY srh_dt_competencias_adiestramiento.codcompadi ";	
       
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->necesidad_adiestramiento MÉTODO->uf_srh_load_competencias_adiestramiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_codcom=$row["codcompadi"];
				$ls_dencom=htmlentities  ($row["dencompadi"]);
				$ls_prio=$row["prioridad"];
				$la_prio[0]="";
				$la_prio[1]="";
				$la_prio[2]="";
				$la_prio[3]="";
				
				switch($ls_prio)
				{
					case "0":
						$la_prio[0]="selected";
						break;
					case "1":
						$la_prio[1]="selected";
						break;
					case "2":
						$la_prio[2]="selected";
						break;
					case "3":
						$la_prio[3]="selected";
						break;
				}
				
				
				$ao_object[$ai_totrows][1]="<input name=txtcodcomp".$ai_totrows." type=text id=txtcodcomp".$ai_totrows." class=sin-borde size=15 value='".$ls_codcom."' readonly  >";
				$ao_object[$ai_totrows][2]="<input name=txtdencomp".$ai_totrows." type=text id=txtdencomp".$ai_totrows." class=sin-borde size=50 value='".$ls_dencom."' readonly>";
				$ao_object[$ai_totrows][3]="<select name=cmbprio".$ai_totrows." id=cmbprio".$ai_totrows.">
              <option value='0' ".$la_prio[0].">No aplica</option>
              <option value='1' ".$la_prio[1].">Urgente</option>
              <option value='2' ".$la_prio[2].">Importante</option>
              <option value='3' ".$la_prio[3].">Puede Esperar</option>
            </select>";	
	}		
			//PARA BUSCAR LAS OTRAS COMPETENCIAS DE ADIESTRAMIENTO QUE NO FUERON SELECCIONADOS
			
			$ls_sql2= "SELECT * FROM srh_competencias_adiestramiento ".
                      " WHERE not exists (SELECT * FROM srh_dt_competencias_adiestramiento ".
                      " WHERE srh_dt_competencias_adiestramiento.codemp='".$this->ls_codemp."'".
					  " AND srh_competencias_adiestramiento.codemp='".$this->ls_codemp."'".
					  " AND srh_competencias_adiestramiento.codcompadi = srh_dt_competencias_adiestramiento.codcompadi ".
					  " ORDER BY srh_competencias_adiestramiento.codcompadi) ";
				
				$rs_data=$this->io_sql->select($ls_sql2);
				if($rs_data===false)
				{
					$lb_valido=false;
					$this->io_msg->message("CLASE->necesidad_adiestramiento MÉTODO-> uf_srh_load_competencias_adiestramiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				}
				else
				{
					$num=$this->io_sql->num_rows($rs_data);
           
		 		 if ($num!=0) 
				 {
					while($row=$this->io_sql->fetch_row($rs_data))
					{
						$ai_totrows++;
						$ls_codcom=$row["codcompadi"];
						$ls_dencom=htmlentities  ($row["dencompadi"]);
																	
						$ao_object[$ai_totrows][1]="<input name=txtcodcomp".$ai_totrows." type=text id=txtcodcomp".$ai_totrows." class=sin-borde size=15 value='".$ls_codcom."' readonly  >";
						$ao_object[$ai_totrows][2]="<input name=txtdencomp".$ai_totrows." type=text id=txtdencomp".$ai_totrows." class=sin-borde size=50 value='".$ls_dencom."' readonly>";
						$ao_object[$ai_totrows][3]="<select name=cmbprio".$ai_totrows." id=cmbprio".$ai_totrows.">
													  <option value='0' selected>No aplica</option>
													  <option value='1' >Urgente</option>
													  <option value='2' >Importante</option>
													  <option value='3' >Puede Esperar</option>
													</select>";				
						
					}
				}
				
			}
		
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}
	




}// end   class sigesp_srh_c_necesidad_adiestramiento
?>
