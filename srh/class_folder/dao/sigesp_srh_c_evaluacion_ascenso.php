<?php

class sigesp_srh_c_evaluacion_ascenso
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_evaluacion_ascenso($path)
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
	
	
	
 
  function uf_srh_getevaluacion_ascenso($as_nroreg, $as_fecha,&$pa_datos="")
  {  
  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getevaluacion_ascenso																			
		//         access: public (sigesp_srh_evaluacion_ascenso)														         
		//      Argumento: $as_nroreg    // numero de la evaluacion de ascenso
		//                 $as_fecha    //  fecha de la evaluación de ascenso												
		//                 $pa_datos    //  arreglos donde  se cargaran lo datos de la consulta									
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que realiza una busqueda de una llamada de atencion en la tabla srh_evaluacion_ascenso       				
		//    Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 19/12/2007							Fecha Última Modificación: 19/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT * FROM srh_evaluacion_ascenso ".
	          "WHERE nroreg = '$as_nroreg' AND fecha = '$as_fecha' ";
    $lb_hay = $this->seleccionar($ls_sql, $pa_datos);
    return $lb_hay;
  }
  
  
  
 
  
function uf_srh_guardarevaluacion_ascenso ($ao_evaluacion,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarevaluacion_ascenso																		
		//         access: public (sigesp_srh_evaluacion_ascenso)	
		//      Argumento: $ao_evaluacion    // arreglo con los datos de la evaluacion de ascenso								
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica una llamada de atencion en la tabla srh_evaluacion_ascenso             
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 19/12/2007							Fecha Última Modificación: 19/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$as_nroreg=$ao_evaluacion->nroreg;
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	 $ao_evaluacion->fecha=$this->io_funcion->uf_convertirdatetobd($ao_evaluacion->fecha);
	 
	 
	  $ls_sql = "UPDATE srh_evaluacion_ascenso SET ".
		  		"reseval = '$ao_evaluacion->total' , ".
				"obseval = '$ao_evaluacion->obs'  ,".
				"tipoeval = '$ao_evaluacion->tipo_eval'  ".
				"WHERE nroreg= '$ao_evaluacion->nroreg'  AND fecha = '$ao_evaluacion->fecha' AND codemp='".$this->ls_codemp."'" ;
		
	 
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó la evaluacion de ascenso ".$as_nroreg;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	  $ao_evaluacion->fecha=$this->io_funcion->uf_convertirdatetobd($ao_evaluacion->fecha);

	
	  $ls_sql = "INSERT INTO srh_evaluacion_ascenso (nroreg, fecha,reseval, obseval, tipoeval, codemp) ".	  
	            "VALUES ('$ao_evaluacion->nroreg','$ao_evaluacion->fecha', '$ao_evaluacion->total', '$ao_evaluacion->obs', '$ao_evaluacion->tipo_eval',  '".$this->ls_codemp."')";
	

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la evaluacion de ascenso ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->evaluacion_ascenso MÉTODO->uf_srh_guardarevaluacion_ascenso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				$this->io_sql->commit();
		}
		
	  if ($lb_guardo) {
		//Guardamos los items de la evaluacion de ascenso
		$lb_guardo = $this->guardarDetalles_Evaluacion($ao_evaluacion, $aa_seguridad);
	  }
	return $lb_guardo;
  }
	
	
	
function guardarDetalles_Evaluacion ($ao_evaluacion, $aa_seguridad)
  {
    //Borramos los registros anteriores 
	$this-> uf_srh_eliminar_dt_evaluacion_ascenso($ao_evaluacion->nroreg, $ao_evaluacion->fecha , $aa_seguridad);
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_res = 0;
	while (($li_res < count($ao_evaluacion->res_eval)) &&
	       ($lb_guardo))
	{
	  $lb_guardo = $this-> uf_srh_guardar_dt_evaluacion_ascenso($ao_evaluacion->res_eval[$li_res], $aa_seguridad);
	  $li_res++;
	}
	
	return $lb_guardo;    
  }

	
	
	
function uf_srh_eliminarevaluacion_ascenso($as_nroreg, $as_fecha, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarevaluacion_ascenso																		
		//        access:  public (sigesp_srh_evaluacion_ascenso)														
		//      Argumento: $as_nroreg        // codigo del personal 
		//                 $as_fecha        //  fecha de la evaluación de ascenso										
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina una evaluación de ascenso en la tabla srh_evaluacion_ascenso   	
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 19/12/2007							Fecha Última Modificación: 19/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
	$as_fecha=$this->io_funcion->uf_convertirdatetobd($as_fecha);
    $this-> uf_srh_eliminar_dt_evaluacion_ascenso($as_nroreg, $as_fecha, $aa_seguridad);
    $ls_sql = "DELETE FROM srh_evaluacion_ascenso ".
	          "WHERE nroreg = '$as_nroreg' AND fecha = '$as_fecha'   AND codemp='".$this->ls_codemp."'";

  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->evaluacion_ascenso MÉTODO->uf_srh_eliminarevaluacion_ascenso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la evaluacion de ascenso ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				
					$this->io_sql->commit();
			}
	
	return $lb_borro;
  }

	
	
	
function uf_srh_buscar_evaluacion_ascenso($as_nroreg,$as_fecha1, $as_fecha2)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_evaluacion_ascenso																		//
		//         access: public (sigesp_srh_evaluacion_ascenso)												
		//      Argumento: $as_codper   //  codigo de la persona                                                             
		//                 $as_apeper   //  apellido de la persona                                                            
		//                 $as_nomper   //  nombre de la persona                                                             
		//					$as_nroreg   // número de registri de ascenso
		//	      Returns: Retorna un XML  																						
		//    Description: Funcion busca una evaluación en la tabla srh_evaluacion_ascenso y crea un XML para mostrar    
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 19/12/2007							Fecha Última Modificación: 19/12/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	    
		$as_fecha1=$this->io_funcion->uf_convertirdatetobd($as_fecha1);
		$as_fecha2=$this->io_funcion->uf_convertirdatetobd($as_fecha2);
		
	
	    $ls_codperdestino="txtcodper";
		$ls_fechadestino="txtfecha";
		$ls_nomdestino="txtnomper";
		$ls_caractdestino="txtcaract";
		$ls_carascdestino="txtdescar";
		$ls_resdestino="txtres";
		$ls_obsdestino="txtobs";
		$ls_nroregdestino="txtnroreg";
		$ls_fecregdestino="txtfecreg";
		$ls_codevaldestino="txtcodeval";
		$ls_denevaldestino="txtdeneval";
		$lb_valido=true;
				
        $ls_sql="SELECT distinct (sno_personalnomina.codper), srh_evaluacion_ascenso.*, srh_registro_ascenso.fecreg, 
       					srh_tipoevaluacion.codeval, srh_tipoevaluacion.deneval,
       					sno_personal.codper, sno_personal.apeper, sno_personal.nomper,
						sno_asignacioncargo.denasicar, sno_cargo.descar,
						(Select sno_cargo.descar from sno_cargo where sno_cargo.codcar=srh_concurso.codcar)  as cargoascenso 
				 FROM srh_evaluacion_ascenso 
				 JOIN srh_registro_ascenso ON (srh_registro_ascenso.nroreg = srh_evaluacion_ascenso.nroreg) 
				 JOIN srh_persona_registro_ascenso ON (srh_registro_ascenso.nroreg = srh_persona_registro_ascenso.nroreg AND srh_persona_registro_ascenso.tipo='P') 
				 JOIN sno_personal ON (srh_persona_registro_ascenso.codper = sno_personal.codper) 
				 JOIN srh_tipoevaluacion ON  (srh_tipoevaluacion.codeval = srh_evaluacion_ascenso.tipoeval)
				 JOIN sno_personalnomina  on  (srh_persona_registro_ascenso.codper=sno_personalnomina.codper)   
				 LEFT JOIN sno_asignacioncargo  on  (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar)  
                 LEFT JOIN sno_cargo on  (sno_personalnomina.codcar=sno_cargo.codcar)  
				 JOIN sno_nomina ON ( sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0')  
				 JOIN srh_concurso on (srh_concurso.codcon=srh_registro_ascenso.codcon) 
				 WHERE srh_evaluacion_ascenso.nroreg like '$as_nroreg' ".
				" AND srh_evaluacion_ascenso.fecha BETWEEN '".$as_fecha1."' AND '".$as_fecha2."' ".
				" ORDER BY srh_evaluacion_ascenso.nroreg";
				 
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->evaluacion_ascenso MÉTODO->uf_srh_buscar_evaluacion_ascenso( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
					$ls_fecha=$this->io_funcion->uf_formatovalidofecha($row["fecha"]);
				    $ls_fecha=$this->io_funcion->uf_convertirfecmostrar($ls_fecha);
					$ls_apeper = trim (htmlentities ($row["apeper"]));
					$ls_nomper= trim (htmlentities  ($row["nomper"]));
					$ls_cargo1=trim (htmlentities  ($row["denasicar"]));
					$ls_cargo2=trim (htmlentities  ($row["descar"]));
					 if ($ls_cargo1!="Sin Asignación de Cargo")
				      {
					   $ls_caract=$ls_cargo1;
				      }
				     if ($ls_cargo2!="Sin Cargo")
				     {
					  $ls_caract=$ls_cargo2;
				     }					
					$ls_carasc=trim (htmlentities  ($row["cargoascenso"]));					
					$li_res=trim ($row["reseval"]);
					$ls_obs=trim (htmlentities  ($row["obseval"]));
					$ls_nroreg=$row["nroreg"];
					$ls_fecreg=$this->io_funcion->uf_formatovalidofecha($row["fecreg"]);
				    $ls_fecreg=$this->io_funcion->uf_convertirfecmostrar($ls_fecreg);
					$ls_codeval=$row["codeval"]; 
					$ls_deneval=trim (htmlentities  ($row["deneval"]));
					
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['nroreg']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($row['nroreg']." ^javascript:aceptar(\"$ls_codper\", \"$ls_fecha\", \"$ls_apeper\", \"$ls_nomper\", \"$ls_caract\", \"$ls_carasc\", \"$li_res\",\"$ls_obs\" ,\"$ls_codperdestino\", \"$ls_fechadestino\",  \"$ls_nomdestino\", 	 \"$ls_caractdestino\", \"$ls_carascdestino\", \"$ls_resdestino\", \"$ls_obsdestino\",\"$ls_nroreg\",\"$ls_fecreg\",\"$ls_nroregdestino\",\"$ls_fecregdestino\",  \"$ls_codeval\", \"$ls_deneval\", \"$ls_denevaldestino\", \"$ls_codevaldestino\");^_self"));
					
				
			    	$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($row["codper"]));												
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
					$row_->appendChild($cell); }
					
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_fecha));												
					$row_->appendChild($cell);
			
					
			
			}
			return $dom->saveXML();
		
		
		}
      
		
	} // end function buscar_evaluacion_ascenso
	

//FUNCIONES PARA EL MANEJO DEL DETALLE DE LOS RESULTADOS DE LA EVALUACIÓN DE ASCENSO

function uf_srh_guardar_dt_evaluacion_ascenso($ao_evaluacion, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_dt_evaluacion_ascenso															     	
		//         access: public (sigesp_dt_srh_evaluacion_ascenso)														
		//      Argumento: $ao_evaluacion    // arreglo con los datos de los detalle de la evaluacion de ascenso	            
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta los detalles de una evaluación de ascenso en la tabla srh_dt_evaluacion_ascenso
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 19/12/2007							Fecha Última Modificación: 19/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 
	 $ao_evaluacion->fecha=$this->io_funcion->uf_convertirdatetobd($ao_evaluacion->fecha);
	 
	 if (($ao_evaluacion->puntos==0) || ($ao_evaluacion->puntos=='0'))
	 {
	    $ao_evaluacion->puntos=0;
	 }
	 
	  $ls_sql = "INSERT INTO srh_dt_evaluacion_ascenso (nroreg,fecha,codite, puntos, codemp) ".	  
	            " VALUES ('$ao_evaluacion->nroreg','$ao_evaluacion->fecha','$ao_evaluacion->codite','$ao_evaluacion->puntos','".$this->ls_codemp."')";
	
		
		
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el detalle de evaluacion de ascenso ".$ao_evaluacion->nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->uf_srh_dt_evaluacion_ascenso MÉTODO->uf_srh_guardar_dt_evaluacion_ascenso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
	
function uf_srh_eliminar_dt_evaluacion_ascenso($as_nroreg,$as_fecha, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_dt_evaluacion_ascenso																
		//        access:  public (sigesp_srh_dt_evaluacion_ascenso)														
		//      Argumento: $as_nroreg        // numero de la evaluacion de ascenso
		//                 $as_fecha		// fecha de la evaluación de ascenso
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																				    
		//    Description: Funcion que elimina una llamada de atencion en la tabla srh_dt_evaluacion_ascenso                      
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 19/12/2007							Fecha Última Modificación: 19/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_dt_evaluacion_ascenso ".
	          " WHERE nroreg='$as_nroreg' AND fecha ='$as_fecha'   AND codemp='".$this->ls_codemp."'";
			  

	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->uf_srh_dt_evaluacion_ascenso MÉTODO->eliminar_dt_evaluacion_ascenso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el detalle de evaluacion de ascenso ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
			
	
	return $lb_borro;
	
  }
  
  
  

function uf_srh_load_evaluacion_ascenso_campos($as_nroreg,$as_fecha,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_evaluacion_ascenso_campos
		//	    Arguments: $as_nroreg  // código del personal
		//                 $as_fecha   // fecha de la evaluaión de ascenso 
		//				   $ai_totrows  // total de filas del detalle
		//				   $ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de una evaluación de ascenso
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$as_fecha=$this->io_funcion->uf_convertirdatetobd($as_fecha);
				
		$ls_sql="SELECT * ".
				"  FROM srh_dt_evaluacion_ascenso, srh_items_evaluacion ".
				"  WHERE srh_dt_evaluacion_ascenso.codemp='".$this->ls_codemp."'".
				"  AND srh_dt_evaluacion_ascenso.nroreg = '".$as_nroreg."' ".
				"  AND srh_dt_evaluacion_ascenso.fecha = '".$as_fecha."' ".
				"  AND srh_dt_evaluacion_ascenso.codite = srh_items_evaluacion.codite".
				" ORDER BY nroreg ";
  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->evaluacion_ascenso MÉTODO->uf_srh_load_evaluacion_ascenso_campos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_codite=$row["codite"];
				$ls_denite=trim (htmlentities  ($row["denite"]));
				$li_valor= trim ($row["valormax"]);
				$li_puntos=trim ($row["puntos"]);
				
				$ao_object[$ai_totrows][1]="<input name=txtcodite".$ai_totrows." type=text id=txtcodite".$ai_totrows." class=sin-borde size=15  readonly value='".$ls_codite."' >";
				$ao_object[$ai_totrows][2]="<input name=txtdenite".$ai_totrows." type=text id=txtdenite".$ai_totrows." class=sin-borde size=60  readonly value='".$ls_denite."'>";
				$ao_object[$ai_totrows][3]="<input name=txtvalor".$ai_totrows." type=text id=txtvalor".$ai_totrows." class=sin-borde  maxlength=3 size=6 readonly value='".$li_valor."'>";
				$ao_object[$ai_totrows][4]="<input name=txtpuntos".$ai_totrows." type=text id=txtpuntos".$ai_totrows." class=sin-borde size=6   onKeyUp='javascript: ue_validarnumero(this); ue_suma(txtres);' value='".$li_puntos."' onChange='javascript:valida_puntos(this,txtvalor".$ai_totrows.");'>";
				
				
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}
	//---------------------------------------------------------------------------------------------------------------------------------
function uf_srh_consultar_items ($as_codeval, &$ai_totrows,&$ao_object)
{
		
		$lb_valido=true;
		
		$ls_sql= "SELECT * FROM srh_tipoevaluacion INNER JOIN srh_items_evaluacion ON (srh_tipoevaluacion.codeval = srh_items_evaluacion.codeval)  ".
				" WHERE srh_tipoevaluacion.codeval = '$as_codeval' ".
				" ORDER BY srh_items_evaluacion.codite";
				

	 	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->evaluacion_psicologica MÉTODO->uf_srh_consultar_items( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
					$ls_codite=$row["codite"];
					$ls_denite=trim (htmlentities  ($row["denite"]));
					$li_valor=trim($row["valormax"]);
												
					$ao_object[$ai_totrows][1]="<input name=txtcodite".$ai_totrows." type=text id=txtcodite".$ai_totrows." class=sin-borde size=15 value='".$ls_codite."'  readonly  >";
					$ao_object[$ai_totrows][2]="<input name=txtdenite".$ai_totrows." type=text id=txtdenite".$ai_totrows." class=sin-borde size=70 value='".$ls_denite."' readonly>";
					$ao_object[$ai_totrows][3]="<input name=txtvalor".$ai_totrows." type=text id=txtvalor".$ai_totrows." class=sin-borde maxlength=3 size=8 value='".$li_valor."' readonly>";
					$ao_object[$ai_totrows][4]="<input name=txtpuntos".$ai_totrows." type=text id=txtpuntos".$ai_totrows." class=sin-borde size=8   onKeyUp='javascript: ue_validarnumero(this);' onChange='javascript:valida_puntos(this,txtvalor".$ai_totrows."); ue_suma(txtres);'>";
					  
					}
		
		}
		else 
		  {
		     $this->io_msg->message("No se encontraron Items de Evaluación.");
	 		$ai_totrows=1;	
			$ao_object[$ai_totrows][1]="<input name=txtcodite".$ai_totrows." type=text id=txtcodite".$ai_totrows." class=sin-borde size=15  readonly  >";
			$ao_object[$ai_totrows][2]="<input name=txtdenite".$ai_totrows." type=text id=txtdenite".$ai_totrows." class=sin-borde size=70  readonly>";
			$ao_object[$ai_totrows][3]="<input name=txtvalor".$ai_totrows." type=text id=txtvalor".$ai_totrows." class=sin-borde maxlength=3 size=8
readonly>";
			$ao_object[$ai_totrows][4]="<input name=txtpuntos".$ai_totrows." type=text id=txtpuntos".$ai_totrows." class=sin-borde size=8   onKeyUp='javascript: ue_validarnumero(this);' onChange='javascript:valida_puntos(this,txtvalor".$ai_totrows."); ue_suma(txtres);'>";
			
			
		  }  
		return $lb_valido;
	
		}
      
		
	} // end uf_srh_consultar_items 
		

}// end   class sigesp_srh_c_evaluacion_ascenso
?>