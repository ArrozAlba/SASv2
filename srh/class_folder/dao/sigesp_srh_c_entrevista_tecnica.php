<?php

class sigesp_srh_c_entrevista_tecnica
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_entrevista_tecnica($path)
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
		//    Description: Funcion que busca un codigo de concurso y un personal en la tabla srh_entrevista_tecnica                
		//	   Creado Por: Ing. María Beatriz Unda																			    						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_codcon="";
		$as_codper=trim($as_codper);
		$ls_sql = " SELECT codcon FROM srh_entrevista_tecnica ".
				  " WHERE codemp='". $this->ls_codemp."'".
				  " AND  codper = '$as_codper' AND codcon = '$as_codcon'";
			
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
				$this->io_msg->message("CLASE->entrevista_tecnica  MÉTODO->getCodPersonal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			
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
  	
  function uf_srh_getentrevista_tecnica($as_codper, $as_fecha,&$pa_datos="")
  {  
  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getentrevista_tecnica																			
		//         access: public (sigesp_srh_entrevista_tecnica)														         
		//      Argumento: $as_codper    // numero de la entrevista tecnica												
		//                 $pa_datos    //  arreglos donde  se cargaran lo datos de la consulta									
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que realiza una busqueda de una entrevista técnica en la tabla srh_entrevista_tecnica       				
		//    Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 10/12/2007							Fecha Última Modificación: 10/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT * FROM srh_entrevista_tecnica ".
	          "WHERE codper = '$as_codper' AND fecha = '$as_fecha' ";
    $lb_hay = $this->seleccionar($ls_sql, $pa_datos);
    return $lb_hay;
  }
  
  
  
 
  
function uf_srh_guardarentrevista_tecnica ($ao_entrevista,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarentrevista_tecnica																		
		//      Argumento: $ao_entrevista    // arreglo con los datos de la entrevista tecnica								
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica una entrevista técnica en la tabla srh_entrevista_tecnica             
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 10/12/2007							Fecha Última Modificación: 10/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$as_codper=$ao_entrevista->codper;
	
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	 $ao_entrevista->fecha=$this->io_funcion->uf_convertirdatetobd($ao_entrevista->fecha);
	 
	 
	  $ls_sql = "UPDATE srh_entrevista_tecnica SET ".
	            "punenttec = '$ao_entrevista->total', ".
				"tipo_eval = '$ao_entrevista->tipo' ,  ".
	            "codcon = '$ao_entrevista->codcon' ".
	           "WHERE codper= '$ao_entrevista->codper' AND fecha = '$ao_entrevista->fecha'   AND codemp='".$this->ls_codemp."'" ;
	  
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó la entrevista tecnica de la persona ".$as_codper;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	  $ao_entrevista->fecha=$this->io_funcion->uf_convertirdatetobd($ao_entrevista->fecha);
	  $ao_entrevista->total= trim ($ao_entrevista->total);
	
	  $ls_sql = "INSERT INTO srh_entrevista_tecnica (codper, codcon, fecha, punenttec, tipo_eval, codemp) ".	  
	            "VALUES ('$ao_entrevista->codper','$ao_entrevista->codcon', '$ao_entrevista->fecha','$ao_entrevista->total','$ao_entrevista->tipo' ,'".$this->ls_codemp."')";
	
	
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la entrevista tecnica de la persona ".$as_codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->entrevista_tecnica MÉTODO->uf_srh_guardarentrevista_tecnica ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				$this->io_sql->commit();
		}
		
		
	//Guardamos los items de la entrevista tecnica
	$lb_guardo = $this->guardarDetalles_Entrevista($ao_entrevista, $aa_seguridad);
		
	return $lb_guardo;
  }
	
	
	
function guardarDetalles_Entrevista ($ao_entrevista, $aa_seguridad)
  {
    //Borramos los registros anteriores 
	$this-> uf_srh_eliminar_dt_entrevista_tecnica($ao_entrevista->codper, $ao_entrevista->fecha , $aa_seguridad);
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_ent = 0;
	while (($li_ent < count($ao_entrevista->entre)) &&
	       ($lb_guardo))
	{
	  $lb_guardo = $this-> uf_srh_guardar_dt_entrevista_tecnica($ao_entrevista->entre[$li_ent], $aa_seguridad);
	  $li_ent++;
	}
	
	return $lb_guardo;    
  }

	
	
	
function uf_srh_eliminarentrevista_tecnica($as_codper, $as_fecha, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarentrevista_tecnica																		
		//        access:  public (sigesp_srh_entrevista_tecnica)														
		//      Argumento: $as_codper        // codigo del personal 
		//                 $as_fecha        //  fecha de la evaluación de requesitos mínimos										
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina una entrevista técnica en la tabla srh_entrevista_tecnica                         
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 10/12/2007							Fecha Última Modificación: 10/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
	
	 $as_fecha=$this->io_funcion->uf_convertirdatetobd($as_fecha);
     $this-> uf_srh_eliminar_dt_entrevista_tecnica($as_codper, $as_fecha, $aa_seguridad);
    $ls_sql = "DELETE FROM srh_entrevista_tecnica ".
	          "WHERE codper = '$as_codper' AND fecha = '$as_fecha'   AND codemp='".$this->ls_codemp."'";

  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->entrevista_tecnica MÉTODO->uf_srh_eliminarentrevista_tecnica ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la entrevista tecnica de la persona".$as_codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				
					$this->io_sql->commit();
			}
	
	return $lb_borro;
  }
	
	
	
function uf_srh_buscar_entrevista_tecnica($as_codper,$as_fecha1,$as_fecha2)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_entrevista_tecnica																		//
		//         access: public (sigesp_srh_entrevista_tecnica)												
		//      Argumento: $as_codper   //  codigo de la persona                                                             
		//                 $as_apeper   //  apellido de la persona                                                            
		//                 $as_nomper   //  nombre de la persona                                                             
		//                 $as_fecha   //   fecha de la entrevista tecnica
		//	      Returns: Retorna un XML  																						
		//    Description: Funcion busca una entrevista tecnica en la tabla srh_entrevista_tecnica y crea un XML para mostrar    
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 10/12/2007							Fecha Última Modificación: 10/12/2007							//
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
		
		
		$ls_sql= " SELECT srh_entrevista_tecnica.*,srh_concurso.descon, srh_concursante.nomper, srh_concursante.apeper, ".
		         " srh_tipoevaluacion.deneval, ".
				 " srh_tipoevaluacion.codeval ".
				 " FROM srh_entrevista_tecnica ".
				 " INNER JOIN srh_concurso ON (srh_entrevista_tecnica.codcon = srh_concurso.codcon) ".
				 " INNER JOIN srh_tipoevaluacion ON  (srh_tipoevaluacion.codeval = srh_entrevista_tecnica.tipo_eval) ".
				 " LEFT JOIN srh_concursante ON (srh_concursante.codper = srh_entrevista_tecnica.codper) ".			 
				 " WHERE  srh_entrevista_tecnica.codper like '".$as_codper."' ".					 			
				 " AND srh_entrevista_tecnica.fecha between  '".$as_fecha1."' AND '".$as_fecha2."'".
				 " ORDER BY srh_entrevista_tecnica.codper";

	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->entrevista_tecnica MÉTODO->uf_srh_buscar_entrevista_tecnica( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
					$ls_descon=trim (htmlentities ($row["descon"]));
					$ls_codeval=($row["codeval"]);
					$ls_deneval=trim (htmlentities ($row["deneval"]));	
				    $li_total=trim ($row["punenttec"]);
					
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$ls_codper);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode( $ls_codper." ^javascript:aceptar(\"$ls_codper\", \"$ls_fecha\", \"$ls_apeper\", \"$ls_nomper\", \"$ls_codcon\", \"$ls_descon\", \"$ls_codperdestino\", \"$ls_fechadestino\",  \"$ls_nomdestino\", 	 \"$ls_codcondestino\", \"$ls_descondestino\", \"$ls_codeval\",\"$ls_deneval\",\"$ls_codevaldestino\", \"$ls_denevaldestino\", \"$li_total\", \"$ls_totaldestino\");^_self"));
					
				
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
      
		
	} // end function buscar_entrevista_tecnica
	

//FUNCIONES PARA EL MANEJO DEL DETALLE DE LA ENTREVISTA TÉCNICA

function uf_srh_guardar_dt_entrevista_tecnica($ao_entrevista, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_dt_entrevista_tecnica															     	
		//         access: public (sigesp_dt_srh_entrevista_tecnica)														
		//      Argumento: $ao_entrevista    // arreglo con los datos de los detalle de la entrevista tecnica					
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta los detalles de una entrevista técnica en la tabla srh_dt_entrevista_tecnica  
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 10/12/2007							Fecha Última Modificación: 10/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 
	 $ao_entrevista->fecha=$this->io_funcion->uf_convertirdatetobd($ao_entrevista->fecha);
	
	 if (($ao_entrevista->puntos==0) || ($ao_entrevista->puntos=='0'))
	 {
	   	$ao_entrevista->puntos=0;
	 }
	 
	  $ls_sql = "INSERT INTO srh_dt_entrevista_tecnica (codper,fecha,codite, puntos, codemp) ".	  
	            " VALUES ('$ao_entrevista->codper','$ao_entrevista->fecha','$ao_entrevista->codite','$ao_entrevista->puntos','".$this->ls_codemp."')";
		

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el detalle de entrevista tecnica ".$ao_entrevista->codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->entrevista_tecnica MÉTODO->uf_srh_guardar_dt_entrevista_tecnica ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
	
function uf_srh_eliminar_dt_entrevista_tecnica($as_codper,$as_fecha, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_dt_entrevista_tecnica																
		//        access:  public (sigesp_srh_dt_entrevista_tecnica)														
		//      Argumento: $as_codper        // numero de la entrevista tecnica
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																				    
		//    Description: Funcion que elimina una entrevista técnica en la tabla srh_dt_entrevista_tecnica                      
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 10/12/2007							Fecha Última Modificación: 10/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_dt_entrevista_tecnica ".
	          " WHERE codper='$as_codper' AND fecha ='$as_fecha'   AND codemp='".$this->ls_codemp."'";
			  

	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->entrevista_tecnica MÉTODO->eliminar_dt_entrevista_tecnica ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el detalle de entrevista tecnica ".$as_codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
			
	
	return $lb_borro;
	
  }
  
  
  

function uf_srh_load_entrevista_tecnica_campos($as_codper,$as_fecha,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_entrevista_tecnica_campos
		//	    Arguments: as_codper   // código de la persona
		//				   as_fecha    // fecha del registro
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de una entrevista técnica
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$as_fecha=$this->io_funcion->uf_convertirdatetobd($as_fecha);
				
		$ls_sql="SELECT * ".
				"  FROM srh_dt_entrevista_tecnica, srh_items_evaluacion ".
				"  WHERE srh_dt_entrevista_tecnica.codemp='".$this->ls_codemp."'".
				"  AND srh_dt_entrevista_tecnica.codper = '".$as_codper."' ".
				"  AND srh_dt_entrevista_tecnica.fecha = '".$as_fecha."' ".
				"  AND srh_dt_entrevista_tecnica.codite = srh_items_evaluacion.codite".
				" ORDER BY codper ";

				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->entrevista_tecnica MÉTODO->uf_srh_load_entrevista_tecnica_campos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_codite=$row["codite"];
				$ls_denite= trim (htmlentities  ($row["denite"]));
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
			$this->io_msg->message("CLASE->entrevista_tecnica MÉTODO->uf_srh_consultar_items( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
					$ls_denite=trim (htmlentities ($row["denite"]));
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
			
	

}// end   class sigesp_srh_c_entrevista_tecnica
?>
