<?php

class sigesp_srh_c_requisitos_minimos
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_requisitos_minimos($path)
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
		//    Description: Funcion que busca un codigo de concurso y un personal en la tabla srh_requisitos_minimos           
		//	   Creado Por: Ing. María Beatriz Unda																			    						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_codcon="";
		$as_codper=trim($as_codper);
		$ls_sql = " SELECT codcon FROM srh_requisitos_minimos ".
				  " WHERE codemp='". $this->ls_codemp."'".
				  " AND  codper = '$as_codper' AND codcon = '$as_codcon'";
			
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
				$this->io_msg->message("CLASE->requisitos_minimos  MÉTODO->getCodPersonal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			
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

  function uf_srh_getrequisitos_minimos($as_codper, $as_fecha,&$pa_datos="")
  {  
  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getrequisitos_minimos																			
		//         access: public (sigesp_srh_requisitos_minimos)														         
		//      Argumento: $as_codper    // numero de la evaluación de requisitos mínimos
		//                 $as_fecha    //  fecha de la evaluación de requisitos mínimos												
		//                 $pa_datos    //  arreglos donde  se cargaran lo datos de la consulta									
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que realiza una busqueda de requisitos mínimos en la tabla srh_requisitos_minimos       				
		//    Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 07/12/2007							Fecha Última Modificación: 07/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT * FROM srh_requisitos_minimos ".
	          "WHERE codper = '$as_codper' AND fecha = '$as_fecha' ";
    $lb_hay = $this->seleccionar($ls_sql, $pa_datos);
    return $lb_hay;
  }
  
  
  
 
  
function uf_srh_guardarrequisitos_minimos ($ao_requisitos,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarrequisitos_minimos																		
		//         access: public (sigesp_srh_requisitos_minimos)
	  	//      Argumento: $ao_requisitos    // arreglo con los datos de la evaluación de requisitos mínimos								
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica requisitos mínimos en la tabla srh_requisitos_minimos             
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 07/12/2007							Fecha Última Modificación: 07/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$as_codper=$ao_requisitos->codper;		
	
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	  $as_fecha=$this->io_funcion->uf_convertirdatetobd($ao_requisitos->fecha);
	  $ls_sql = "UPDATE srh_requisitos_minimos SET ".
		  		"punreqmin = '$ao_requisitos->total' , ".
				"tipo_eval = '$ao_requisitos->tipo' ,  ".
	            "codcon = '$ao_requisitos->codcon' ".
	            "WHERE codper= '$ao_requisitos->codper'  AND fecha = '".$as_fecha."' AND codemp='".$this->ls_codemp."'" ;
		

			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó la evaluación de requisitos mínimos ".$as_codper;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	 $ao_requisitos->fecha=$this->io_funcion->uf_convertirdatetobd($ao_requisitos->fecha);
	  $ls_sql = "INSERT INTO srh_requisitos_minimos (codper, codcon, fecha, punreqmin, tipo_eval, codemp) ".	  
	            "VALUES ('$ao_requisitos->codper','$ao_requisitos->codcon','$ao_requisitos->fecha', '$ao_requisitos->total','$ao_requisitos->tipo','".$this->ls_codemp."')";

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la evaluación de requisitos mínimos ".$as_codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->requisitos_minimos MÉTODO->uf_srh_guardarrequisitos_minimos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				$this->io_sql->commit();
		}
		
		
	//Guardamos los items de la Evaluación de Requisitos Mínimos
	$lb_guardo = $this->guardarDetalles_Evaluacion($ao_requisitos, $aa_seguridad);
		
	return $lb_guardo;
  }
	
	
	
function guardarDetalles_Evaluacion ($ao_requisitos, $aa_seguridad)
  {
    //Borramos los registros anteriores 
	$as_fecha=$this->io_funcion->uf_convertirdatetobd($ao_requisitos->fecha);
	$this-> uf_srh_eliminar_dt_requisitos_minimos($ao_requisitos->codper,$as_fecha, $aa_seguridad);
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_req = 0;
	while (($li_req < count($ao_requisitos->req_min)) &&
	       ($lb_guardo))
	{
	  $lb_guardo = $this-> uf_srh_guardar_dt_requisitos_minimos($ao_requisitos->req_min[$li_req], $aa_seguridad);
	  $li_req++;
	}
	
	return $lb_guardo;    
  }

	
	
	
function uf_srh_eliminarrequisitos_minimos($as_codper, $as_fecha, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarrequisitos_minimos																		
		//        access:  public (sigesp_srh_requisitos_minimos)														
		//      Argumento: $as_codper        // codigo del personal 
		//                 $as_fecha        //  fecha de la evaluación de requesitos mínimos										
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina una evaluación de requisitos mínimos en la tabla srh_requisitos_minimos      
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 07/12/2007							Fecha Última Modificación: 07/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
   
	
	 $as_fecha=$this->io_funcion->uf_convertirdatetobd($as_fecha);
     $this-> uf_srh_eliminar_dt_requisitos_minimos($as_codper, $as_fecha, $aa_seguridad);
     $this->io_sql->begin_transaction();	
     $ls_sql = "DELETE FROM srh_requisitos_minimos ".
	          "WHERE codper = '$as_codper' AND fecha = '$as_fecha'   AND codemp='".$this->ls_codemp."'";

  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->requisitos_minimos MÉTODO->uf_srh_eliminarrequisitos_minimos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la evaluación de requisitos mínimos ".$as_codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				
					$this->io_sql->commit();
			}
	
	return $lb_borro;
  }
	


	
	
function uf_srh_buscar_requisitos_minimos($as_codper,$as_fecha1, $as_fecha2)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_requisitos_minimos																		//
		//         access: public (sigesp_srh_requisitos_minimos)												
		//      Argumento: $as_codper   //  codigo de la persona                                                             
		//                 $as_apeper   //  apellido de la persona                                                            
		//                 $as_nomper   //  nombre de la persona                                                             
		//                 $as_fecha   //   fecha de la evaluación de requisitos mínimos
		//	      Returns: Retorna un XML  																						
		//    Description: Funcion busca una evaluación en la tabla srh_requisitos_minimos y crea un XML para mostrar    
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 07/12/2007							Fecha Última Modificación: 07/12/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
  
		$as_fecha1=$this->io_funcion->uf_convertirdatetobd($as_fecha1);
		$as_fecha2=$this->io_funcion->uf_convertirdatetobd($as_fecha2);		
		
	    $ls_codperdestino="txtcodper";
		$ls_fechadestino="txtfecha";
		$ls_nomdestino="txtnomper";
		$ls_codcondestino="txtcodcon";
		$ls_descondestino="txtdescon";
		$ls_codevaldestino="txtcodeval";
		$ls_denevaldestino="txtdeneval";
		$ls_totaldestino="txtres";

		$lb_valido=true;
		
		$ls_sql= " SELECT srh_requisitos_minimos.*,srh_concurso.descon,  ".
		         " srh_concursante.nomper, srh_concursante.apeper, srh_tipoevaluacion.deneval, ".
				 " srh_tipoevaluacion.codeval ".
				 " FROM srh_requisitos_minimos ".
				 " INNER JOIN srh_concurso ON (srh_requisitos_minimos.codcon = srh_concurso.codcon) ".
				 " INNER JOIN srh_tipoevaluacion ON  (srh_tipoevaluacion.codeval = srh_requisitos_minimos.tipo_eval) ".
				 " LEFT JOIN srh_concursante ON (srh_concursante.codper = srh_requisitos_minimos.codper) ".
				 " WHERE  srh_requisitos_minimos.codper like '".$as_codper."' ".					 			
				 " AND srh_requisitos_minimos.fecha between  '".$as_fecha1."' AND '".$as_fecha2."'".
				 " ORDER BY srh_requisitos_minimos.codper";

	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->requisitos_minimos MÉTODO->uf_srh_buscar_requisitos_minimos( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
										  
					$ls_nomper = trim (htmlentities ($row["nomper"]));
					
					$ls_codcon=$row["codcon"];
					$ls_descon= trim (htmlentities   ($row["descon"]));
					$ls_codeval=($row["codeval"]);
        			$ls_deneval=trim (htmlentities  ($row["deneval"]));	
					$li_total=trim ($row["punreqmin"]);
					
					
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id", $ls_codper);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode( $ls_codper." ^javascript:aceptar(\"$ls_codper\", \"$ls_fecha\", \"$ls_apeper\", \"$ls_nomper\", \"$ls_codcon\", \"$ls_descon\", \"$ls_codperdestino\", \"$ls_fechadestino\",  \"$ls_nomdestino\", 	 \"$ls_codcondestino\", \"$ls_descondestino\", \"$ls_codeval\",\"$ls_deneval\", \"$ls_codevaldestino\", \"$ls_denevaldestino\",\"$li_total\", \"$ls_totaldestino\");^_self"));
					
				
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
      
		
	} // end function buscar_requisitos_minimos
	

//FUNCIONES PARA EL MANEJO DEL DETALLE DE LA EVALUACIÓN DE REQUISITOS MÍNIMOS

function uf_srh_guardar_dt_requisitos_minimos($ao_requisitos, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_dt_requisitos_minimos															     	
		//         access: public (sigesp_dt_srh_requisitos_minimos)														
		//      Argumento: $ao_requisitos    // arreglo con los datos de los detalle de la evaluación de requisitos mínimos					
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica requisitos mínimos en la tabla srh_dt_requisitos_minimos           
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 07/12/2007							Fecha Última Modificación: 07/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 
	 if (($ao_requisitos->puntos==0) || ($ao_requisitos->puntos=='0'))
	 {
	   	$ao_requisitos->puntos=0;
	 }
	 
	 
	 $ao_requisitos->fecha=$this->io_funcion->uf_convertirdatetobd($ao_requisitos->fecha);
	  $ls_sql = "INSERT INTO srh_dt_requisitos_minimos (codper,fecha,codite, puntos, codemp) ".	  
	            " VALUES ('$ao_requisitos->codper','$ao_requisitos->fecha','$ao_requisitos->codite','$ao_requisitos->puntos','".$this->ls_codemp."')";
		

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el detalle de evaluación de requisitos mínimos ".$ao_requisitos->codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->requisitos_minimos MÉTODO->uf_srh_guardar_dt_requisitos_minimos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
	
	
function uf_srh_eliminar_dt_requisitos_minimos($as_codper,$as_fecha, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_dt_requisitos_minimos																
		//        access:  public (sigesp_srh_dt_requisitos_minimos)														
		//      Argumento: $as_codper        // numero de la evaluación de requisitos mínimos
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																				    
		//    Description: Funcion que elimina requisitos mínimos en la tabla srh_dt_requisitos_minimos                      
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 07/12/2007							Fecha Última Modificación: 07/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_dt_requisitos_minimos ".
	          " WHERE codper='$as_codper' AND fecha ='$as_fecha'   AND codemp='".$this->ls_codemp."'";


	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->requisitos_minimos MÉTODO->uf_srh_eliminar_dt_requisitos_minimos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el detalle de evaluación de requisitos mínimos ".$as_codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
			
	
	return $lb_borro;
	
  }
  
  
  

function uf_srh_load_requisitos_minimos_campos($as_codper,$as_fecha,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_requisitos_minimos_campos
		//		   Access: public (sigesp_srh_d_escala)
		//	    Arguments: $as_codper  // código de la persona
		//                 $as_fecha   // fecha de la evaluación de requisitos mínimos
		//				   $ai_totrows  // total de filas del detalle
		//				   $ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de una evaluación de requisitos mínimos
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$as_fecha=$this->io_funcion->uf_convertirdatetobd($as_fecha);
				
		$ls_sql="SELECT * ".
				"  FROM srh_dt_requisitos_minimos, srh_items_evaluacion ".
				"  WHERE srh_dt_requisitos_minimos.codemp='".$this->ls_codemp."'".
				"  AND srh_dt_requisitos_minimos.codper = '".$as_codper."' ".
				"  AND srh_dt_requisitos_minimos.fecha = '".$as_fecha."' ".
				"  AND srh_dt_requisitos_minimos.codite = srh_items_evaluacion.codite".
				" ORDER BY codper ";
  
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->requisitos_minimos MÉTODO->uf_srh_load_requisitos_minimos_campos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_codite=$row["codite"];
				$ls_denite=htmlentities ($row["denite"]);
				$li_valor=$row["valormax"];
				$li_puntos=$row["puntos"];
				
				$ao_object[$ai_totrows][1]="<input name=txtcodite".$ai_totrows." type=text id=txtcodite".$ai_totrows." class=sin-borde size=15  readonly value='".$ls_codite."' >";
				$ao_object[$ai_totrows][2]="<input name=txtdenite".$ai_totrows." type=text id=txtdenite".$ai_totrows." class=sin-borde size=70  readonly value='".$ls_denite."'>";
				$ao_object[$ai_totrows][3]="<input name=txtvalor".$ai_totrows." type=text id=txtvalor".$ai_totrows." class=sin-borde  maxlength=3 size=8 readonly value='".$li_valor."'>";
				$ao_object[$ai_totrows][4]="<input name=txtpuntos".$ai_totrows." type=text id=txtpuntos".$ai_totrows." class=sin-borde size=8   onKeyUp='javascript: ue_validarnumero(this);' value='".$li_puntos."' onChange='javascript:valida_puntos(this,txtvalor".$ai_totrows."); ue_suma(txtres);'>";
							
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	function uf_srh_consultar_items ($as_codeval, &$ai_totrows,&$ao_object)
{
		
		$lb_valido=true;
		
		$ls_sql= "SELECT * FROM srh_tipoevaluacion INNER JOIN srh_items_evaluacion ON (srh_tipoevaluacion.codeval = srh_items_evaluacion.codeval)  ".
				" WHERE srh_tipoevaluacion.codeval = '$as_codeval' ".
				" ORDER BY srh_items_evaluacion.codite";
				

	 	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->requsitos_minimos MÉTODO->uf_srh_consultar_items( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
					$ls_denite=htmlentities ($row["denite"]);
					$li_valor=$row["valormax"];
												
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
      
		
	} // end uf_srh_consultar_revisiones_odi 
		
	

}// end   class sigesp_srh_c_requisitos_minimos
?>