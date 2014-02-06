<?php

class sigesp_srh_c_revision_metas
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_revision_metas($path)
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
	
	
  
function uf_srh_guardarrevision_metas ($ao_revision,$as_operacion="insertar", $ao_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarodi																		
		//         access: public (sigesp_srh_revision_metas)
		//      Argumento: $ao_revision    // arreglo con los datos de la revision de Meta Personal     
		//		           $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $ao_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica un ODI en la tabla srh_revision_metas             
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 03/01/2008							Fecha Última Modificación: 03/01/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$as_nroreg=$ao_revision->nroreg;
	
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	 $ao_revision->fecha=$this->io_funcion->uf_convertirdatetobd($ao_revision->fecha);
   	 
	  $ls_sql = "UPDATE srh_revision_metas SET ".
		  		"tipo_eval = '$ao_revision->tipo_eval' , ".
				"total = '$ao_revision->total'   ".				
				"WHERE nroreg = '$ao_revision->nroreg' AND fecha = '$ao_revision->fecha' AND codemp='".$this->ls_codemp."'" ;


			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó la revision de Meta Personal ".$as_nroreg;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($ao_seguridad["empresa"],
											$ao_seguridad["sistema"],$ls_evento,$ao_seguridad["logusr"],
											$ao_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	  $ao_revision->fecha=$this->io_funcion->uf_convertirdatetobd($ao_revision->fecha);
	  
      
	
	  $ls_sql = "INSERT INTO srh_revision_metas (nroreg, fecha, tipo_eval, total, codemp) ".	  
	            "VALUES ('$ao_revision->nroreg','$ao_revision->fecha', '$ao_revision->tipo_eval','$ao_revision->total', '".$this->ls_codemp."')";

		
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la revision de Meta Personal ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($ao_seguridad["empresa"],
												$ao_seguridad["sistema"],$ls_evento,$ao_seguridad["logusr"],
												$ao_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->revision_metas MÉTODO->uf_srh_guardarrevision_metas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				//Guardamos el detalle de la revision de Meta Personal
				$lb_guardo1 = $this->guardarDetalles_revision_metas($ao_revision, $ao_seguridad);
				//Guardamos las Personas involucradas en la Revisión de Meta Personal (Evaluador y Trabajador)
				$lb_guardo2 = $this->guardarPersonas_revision_metas($ao_revision, $ao_seguridad);
					
				 if (($lb_guardo1) && ($lb_guardo2)) 
				 {
		
				   $this->io_sql->commit();
				}
		}
	
		
	return $lb_guardo;
  }
	
	
	
function guardarDetalles_revision_metas ($ao_revision, $ao_seguridad)
  {
    //Borramos los registros anteriores 
	$this-> uf_srh_eliminar_dt_revision_metas($ao_revision->nroreg,$ao_revision->fecha, $ao_seguridad);
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_rev = 0;
	while (($li_rev < count($ao_revision->meta)) &&
	       ($lb_guardo))
	{
	  $lb_guardo = $this-> uf_srh_guardar_dt_revision_metas($ao_revision->meta[$li_rev], $ao_seguridad);
	  $li_rev++;
	}
	
	return $lb_guardo;    
  }


	
function guardarPersonas_revision_metas ($ao_revision, $ao_seguridad)
  {
   
	$lb_guardo = true;
	$lb_guardo1 = $this-> uf_srh_eliminar_persona($ao_revision->nroreg,$ao_revision->fecha);
	if ($lb_guardo1===true) {
		
	  }
     $lb_guardo = $this-> uf_srh_guardar_evaluador  ($ao_revision, $ao_seguridad);
  	 $lb_guardo = $this-> uf_srh_guardar_trabajador ($ao_revision, $ao_seguridad); 
	
	return $lb_guardo;    
  }
	
	
function uf_srh_eliminarrevision_metas($as_nroreg, $as_fecha, $ao_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarrevision_metas																		
		//        access:  public (sigesp_srh_revision_metas)														
		//      Argumento: $as_codper        // codigo del personal 
		//                 $as_fecha        //  fecha de registro de Meta Personal										
		//                 $ao_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina una revisión de meta de personal en la tabla srh_revision_metas
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 03/01/2008							Fecha Última Modificación: 03/01/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  	$as_fecha=$this->io_funcion->uf_convertirdatetobd($as_fecha);
	$this-> uf_srh_eliminar_dt_revision_metas($as_nroreg,$as_fecha, $ao_seguridad);
	$this-> uf_srh_eliminar_persona($as_nroreg,$as_fecha);

    $this->io_sql->begin_transaction();	

    $ls_sql = "DELETE FROM srh_revision_metas ".
	          "WHERE nroreg = '$as_nroreg' AND fecha= '$as_fecha' AND codemp='".$this->ls_codemp."'";


	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->revision_metas MÉTODO->uf_srh_eliminarrevision_metas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la revision de Meta Personal ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($ao_seguridad["empresa"],
												$ao_seguridad["sistema"],$ls_evento,$ao_seguridad["logusr"],
												$ao_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				
					$this->io_sql->commit();
			}
	
	return $lb_borro;
  }
	

	
function uf_srh_buscar_revision_metas($as_nroreg,$as_fecha1,$as_fecha2)
	{
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_revision_metas																		//
		//         access: public (sigesp_srh_revision_metas)												
		//      Argumento: $as_codper   //  codigo de la persona                                                             
		//                 $as_apeper   //  apellido de la persona                                                            
		//                 $as_nomper   //  nombre de la persona                                                             
		//                 $as_fecha   //   fecha de la revision de Meta Personal	    																						
		//    Description: Funcion busca una revisión de Meta Personal en la tabla srh_revision_metas y crea un XML para mostrar    	//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 03/01/2008							Fecha Última Modificación: 03/01/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		
		$as_fecha1=$this->io_funcion->uf_convertirdatetobd($as_fecha1);
		$as_fecha2=$this->io_funcion->uf_convertirdatetobd($as_fecha2);
		
		
		$ls_fechadestino="txtfecha";
		$ls_fecregdestino="txtfecreg";
		$ls_nrodestino="txtnroreg";
		
		$ls_fecinidestino="txtfecini";
		$ls_fecfindestino="txtfecfin";

		$ls_codperdestino="txtcodper";
		$ls_nomperdestino="txtnomper";
		$ls_codcarperdestino="txtcodcarper";
		
	    $ls_codevadestino="txtcodeva";
		$ls_nomevadestino="txtnomeva";
		$ls_carevadestino="txtcodcareva";
		
	    $ls_totaldestino="txttotal";
		$ls_obsdestino="txtobs";
		
		$ls_codevaldestino="txtcodeval";
		$ls_denevaldestino="txtdeneval";
		


		$lb_valido=true;
		

				
		$ls_sql= "SELECT  * FROM srh_revision_metas INNER JOIN srh_persona_revision_metas ON (srh_persona_revision_metas.nroreg = srh_revision_metas.nroreg AND srh_persona_revision_metas.fecha = srh_revision_metas.fecha ) INNER JOIN sno_personal ON (sno_personal.codper = srh_persona_revision_metas.codper) INNER JOIN srh_registro_metas ON  (srh_registro_metas.nroreg = srh_revision_metas.nroreg) INNER JOIN srh_tipoevaluacion ON (srh_tipoevaluacion.codeval = srh_revision_metas.tipo_eval) ".
				 " JOIN sno_personalnomina  ON  (srh_persona_revision_metas.codper=sno_personalnomina.codper)   ".
				 " LEFT JOIN sno_asignacioncargo ON  (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar and sno_personalnomina.codnom=sno_asignacioncargo.codnom)  ".
				" LEFT JOIN sno_cargo  ON  (sno_personalnomina.codcar=sno_cargo.codcar and sno_personalnomina.codnom=sno_cargo.codnom)  ".
				" JOIN sno_nomina ON ( sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0')  ".
				" WHERE  srh_revision_metas.nroreg like '$as_nroreg' ".
				" AND srh_revision_metas.fecha BETWEEN '".$as_fecha1."'  AND '".$as_fecha2."' ".
				" ORDER BY srh_revision_metas.nroreg";


	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revision_metas MÉTODO->uf_srh_buscar_revision_metas( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
					$ls_fecini=$this->io_funcion->uf_formatovalidofecha($row["fecini"]);
				    $ls_fecini=$this->io_funcion->uf_convertirfecmostrar($ls_fecini);
					$ls_fecfin=$this->io_funcion->uf_formatovalidofecha($row["fecfin"]);
				    $ls_fecfin=$this->io_funcion->uf_convertirfecmostrar($ls_fecfin);
					$ls_fecreg=$this->io_funcion->uf_formatovalidofecha($row["fecreg"]);
				    $ls_fecreg=$this->io_funcion->uf_convertirfecmostrar($ls_fecreg);
					$ls_obs=$row["observacion"];
					$ls_deneval= trim (htmlentities  ($row["deneval"]));
					$ls_codeval= trim (htmlentities  ($row["codeval"]));
					$li_total= trim ($row["total"]);
					
					$ls_cargo1= trim (htmlentities ($row["denasicar"]));
				    $ls_cargo2= trim (htmlentities ($row["descar"]));
		
					
					if ($row["tipo"]=="E") 
					{
					  $ls_apeeva = trim (htmlentities  ($row["apeper"]));
				      $ls_nomeva= trim (htmlentities ($row["nomper"]));
				      $ls_codeva=$row["codper"];
					   if ($ls_cargo1!="Sin Asignación de Cargo")
				      {
					   $ls_careva=$ls_cargo1;
				       }
				      if ($ls_cargo2!="Sin Cargo")
				      {
					   $ls_careva=$ls_cargo2;
				       }	
					  $ls_control=$ls_control + 1;
					 }
					else 
					{
					  $ls_codper=$row["codper"];
					  $ls_apeper =trim (htmlentities ($row["apeper"]));
   					  $ls_nomper= trim (htmlentities  ($row["nomper"]));
					   if ($ls_cargo1!="Sin Asignación de Cargo")
				      {
					   $ls_codcarper=$ls_cargo1;
				       }
				      if ($ls_cargo2!="Sin Cargo")
				      {
					   $ls_codcarper=$ls_cargo2;
				       }	
					  $ls_control=$ls_control + 1;
					  
					}
					
			if ($ls_control=="2")  {
			
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['nroreg']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($row['nroreg']." ^javascript:aceptar(\"$ls_nroreg\", \"$ls_fecha\", \"$ls_apeper\", \"$ls_nomper\", \"$ls_codper\",\"$ls_apeeva\", \"$ls_nomeva\", \"$ls_codeva\", \"$ls_careva\", \"$ls_fecini\", \"$ls_fecfin\", \"$ls_obs\", \"$li_total\", \"$ls_codeval\", \"$ls_deneval\", \"$ls_nrodestino\", \"$ls_codperdestino\", \"$ls_fechadestino\",  \"$ls_nomperdestino\", \"$ls_fecinidestino\",  \"$ls_fecfindestino\", \"$ls_codevadestino\", \"$ls_nomevadestino\", \"$ls_carevadestino\", \"$ls_obsdestino\", \"$ls_totaldestino\", \"$ls_codevaldestino\", \"$ls_denevaldestino\", \"$ls_fecreg\", \"$ls_fecregdestino\",\"$ls_codcarper\",\"$ls_codcarperdestino\");^_self"));
					
				
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
					$row_->appendChild($cell); }
					
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_fecha));												
					$row_->appendChild($cell);
			
					$ls_control=0;
					}
			
			}
			return $dom->saveXML();
		
		}
      
		
	} // end function buscar_revision_metas
	

//FUNCIONES PARA EL MANEJO DEL DETALLE DE LAS METAS DE PERSONAL

function uf_srh_guardar_dt_revision_metas($ao_revision, $ao_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_dt_revision_metas															     	
		//         access: public (sigesp_dt_srh_revision_metas)														
		//      Argumento: $ao_revision    // arreglo con los datos de los detalle de la revision de Meta Personal     
		//	               $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $ao_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica un ODI en la tabla srh_dt_revision_metas           
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 03/01/2008							Fecha Última Modificación: 03/01/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 
	 $ao_revision->fecha=$this->io_funcion->uf_convertirdatetobd($ao_revision->fecha);
	 $ao_revision->feceje=$this->io_funcion->uf_convertirdatetobd(trim ($ao_revision->feceje));
	 
	  $ls_sql = "INSERT INTO srh_dt_revision_metas (nroreg, fecha, codmeta,  feceje, valor, obsmet, codemp) ".	  
	            " VALUES ('$ao_revision->nroreg','$ao_revision->fecha','$ao_revision->codmeta',  '$ao_revision->feceje','$ao_revision->valor', '$ao_revision->obsmet', '".$this->ls_codemp."')";


		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el detalle de la revisión de las metas ".$ao_revision->nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($ao_seguridad["empresa"],
												$ao_seguridad["sistema"],$ls_evento,$ao_seguridad["logusr"],
												$ao_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->revision_metas MÉTODO->uf_srh_guardar_dt_revision_metas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
	
function uf_srh_eliminar_dt_revision_metas($as_nroreg, $as_fecha, $ao_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_dt_revision_metas																
		//        access:  public (sigesp_srh_dt_revision_metas)														
		//      Argumento: $as_nroreg       // número de registro de las metas de personal
		//                 $as_fecha        // fecha de la revisión de las metas de personal
		//	               $ao_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																				    
		//    Description: Funcion que elimina un detalle de un registro de metas de personal en la tabla srh_dt_revision_metas 		        //	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 03/01/2008							Fecha Última Modificación: 03/01/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_dt_revision_metas ".
	          " WHERE nroreg='$as_nroreg' AND fecha='$as_fecha' AND codemp='".$this->ls_codemp."'";


	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->revision_metas MÉTODO->uf_srh_eliminar_dt_revision_metas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el detalle de la revisión de la meta de personal ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($ao_seguridad["empresa"],
												$ao_seguridad["sistema"],$ls_evento,$ao_seguridad["logusr"],
												$ao_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
			
	
	return $lb_borro;
	
  }
  
  
  

function uf_srh_load_revision_metas_campos($as_nroreg, $as_fecha,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_revision_metas_campos
		//	    Arguments: as_nroreg   // número de revisión
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de una revisión de Meta Personal
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$as_fecha=$this->io_funcion->uf_convertirdatetobd($as_fecha);		
		$ls_sql="SELECT * ".
				"  FROM srh_revision_metas, srh_dt_revision_metas, srh_dt_registro_metas ".
				"  WHERE srh_dt_revision_metas.codemp='".$this->ls_codemp."'".
				"  AND srh_revision_metas.nroreg = '$as_nroreg' ".
				"  AND srh_revision_metas.fecha = '$as_fecha' ".
				"  AND srh_dt_registro_metas.nroreg = '$as_nroreg' ".
				"  AND srh_dt_revision_metas.nroreg = '$as_nroreg' ".
				"  AND srh_dt_revision_metas.fecha = '$as_fecha' ".
				"  AND srh_dt_revision_metas.codmeta = srh_dt_registro_metas.codmeta ".
				" ORDER BY srh_dt_revision_metas.codmeta ";

	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->revision_metas MÉTODO->uf_srh_load_revision_metas_campos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_codmet=$row["codmeta"];
				$ls_meta= trim (htmlentities ($row["meta"]));
				$ls_feceje=$this->io_funcion->uf_formatovalidofecha($row["feceje"]);
				$ls_feceje=$this->io_funcion->uf_convertirfecmostrar($ls_feceje);
				$li_valor=$row["valor"];
				$ls_obs= trim (htmlentities ($row["obsmet"]));
				
				$ao_object[$ai_totrows][1]="<textarea name=txtcodmet".$ai_totrows."  cols=5 rows=3 id=txtcodmet".$ai_totrows." class=sin-borde readonly>".$ls_codmet."</textarea>";
				$ao_object[$ai_totrows][2]="<textarea name=txtmeta".$ai_totrows."    cols=50 rows=3 id=txtmeta".$ai_totrows."  class=sin-borde readonly >".$ls_meta." </textarea>";
				$ao_object[$ai_totrows][3]="<textarea name=txtfeceje".$ai_totrows."  cols=8 rows=3 id=txfeceje".$ai_totrows."  class=sin-borde >".$ls_feceje." </textarea>";
				$ao_object[$ai_totrows][4]="<textarea name=txtevalmet".$ai_totrows." cols=5 rows=3 id=txtevalmet".$ai_totrows."  class=sin-borde onKeyUp='javascript: ue_validarnumero(this);'  onChange='javascript: ue_sumar(txttotal);'> ".$li_valor."</textarea>";
				$ao_object[$ai_totrows][5]="<textarea name=txtobsmet".$ai_totrows."  cols=25 rows=3 id=txtobsmet".$ai_totrows." class=sin-borde>".$ls_obs."</textarea>";
				
				
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	
function uf_srh_consultar_revision_metas ($as_nroreg,&$ai_totrows,&$ao_object)
{
		
		$lb_valido=true;
		
		
		
		$ls_sql= "SELECT * FROM srh_registro_metas INNER JOIN srh_dt_registro_metas ON (srh_registro_metas.nroreg = srh_dt_registro_metas.nroreg) ".
		        "  WHERE srh_registro_metas.nroreg='".$as_nroreg."'".
				" ORDER BY srh_registro_metas.nroreg";
		

	 	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revision_metas MÉTODO->uf_srh_consultar_revision_metas( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
					$ls_codmet=$row["codmeta"];
					$ls_meta= trim (htmlentities ($row["meta"]));
					
				
					$ao_object[$ai_totrows][1]="<textarea name=txtcodmet".$ai_totrows."  cols=5 rows=3 id=txtcodmet".$ai_totrows." class=sin-borde readonly>".$ls_codmet."</textarea>";
					$ao_object[$ai_totrows][2]="<textarea name=txtmeta".$ai_totrows."    cols=50 rows=3 id=txtmeta".$ai_totrows."  class=sin-borde readonly >".$ls_meta." </textarea>";
					$ao_object[$ai_totrows][3]="<textarea name=txtfeceje".$ai_totrows."  cols=8 rows=3 id=txfeceje".$ai_totrows."  class=sin-borde >dd/mm/aaaa </textarea>";
					$ao_object[$ai_totrows][4]="<textarea name=txtevalmet".$ai_totrows." cols=5 rows=3 id=txtevalmet".$ai_totrows."  class=sin-borde onKeyUp='javascript: ue_validarnumero(this);'  onChange='javascript: ue_sumar(txttotal);'> 0</textarea>";
					$ao_object[$ai_totrows][5]="<textarea name=txtobsmet".$ai_totrows."  cols=25 rows=3 id=txtobsmet".$ai_totrows." class=sin-borde></textarea>";
					
				}
		
		}
		else 
		  {
		     $this->io_msg->message("No se encontraron Registros con esos datos.");
	 		  $ai_totrows=1;	
			  $ao_object[$ai_totrows][1]="<textarea name=txtcodmet".$ai_totrows."  cols=5 rows=3 id=txtcodmet".$ai_totrows." class=sin-borde readonly></textarea>";
			  $ao_object[$ai_totrows][2]="<textarea name=txtmeta".$ai_totrows."    cols=50 rows=3 id=txtmeta".$ai_totrows."  class=sin-borde readonly > </textarea>";
				$ao_object[$ai_totrows][3]="<textarea name=txtfeceje".$ai_totrows."  cols=8 rows=3 id=txfeceje".$ai_totrows."  class=sin-borde > </textarea>";
				$ao_object[$ai_totrows][4]="<textarea name=txtevalmet".$ai_totrows." cols=5 rows=3 id=txtevalmet".$ai_totrows."  class=sin-borde onKeyUp='javascript: ue_validarnumero(this);'  onChange='javascript: ue_sumar(txttotal);'> </textarea>";
				$ao_object[$ai_totrows][5]="<textarea name=txtobsmet".$ai_totrows."  cols=25 rows=3 id=txtobsmet".$ai_totrows." class=sin-borde></textarea>";
			
		  }  
		return $lb_valido;
	
		}
      
		
	} // end function buscar_revision_metas
		
	
//FUNCIONES PARA EL MANEJO DEL LAS PERSONAS INVOLUACRADAS EN LA REVISIÓN DE LAS METAS DE PERSONAL

function uf_srh_eliminar_persona($as_nroreg, $as_fecha)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_persona																																		
		//      Argumento: $as_revision		      // arreglo con detalles de la revision de Meta Personal						
		//                 $ao_seguridad          //  arreglo de registro de seguridad                                  
		//	      Returns: Retorna un Booleano																					
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 03/01/2008							Fecha Última Modificación: 03/01/2008						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_persona_revision_metas ".
	          "WHERE nroreg = '$as_nroreg' AND fecha= '$as_fecha'   AND codemp='".$this->ls_codemp."'";
	  
			
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->revision_metas MÉTODO->uf_srh_eliminar_persona ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	



function uf_srh_guardar_trabajador ($ao_revision, $ao_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_dt_revisionnestacion																     
		//         access: public (sigesp_dt_srh_revisionnestacion)															
		//      Argumento: $ao_revision    // arreglo con los datos de los detalle de la revisión de meta de personal						
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              //
		//                 $ao_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta una persona en la tabla srh_persona_revision_metas  	     
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 03/01/2008							Fecha Última Modificación: 03/01/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	

	 $this->io_sql->begin_transaction();
	 
		 
	  $ls_sql = "INSERT INTO srh_persona_revision_metas (nroreg, fecha,codper, tipo, codemp) ".	  
	            "VALUES ('$ao_revision->nroreg', '$ao_revision->fecha','$ao_revision->codper','P','".$this->ls_codemp."')";
		
		

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó Persona de la Revision de Meta Personal ".$ao_revision->nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($ao_seguridad["empresa"],
												$ao_seguridad["sistema"],$ls_evento,$ao_seguridad["logusr"],
												$ao_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->revision_metas MÉTODO->uf_srh_guardar_trabajador ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
  
  
 function uf_srh_guardar_evaluador ($ao_revision, $ao_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_dt_revisionnestacion															     														
		//      Argumento: $ao_revision    // arreglo con los datos de los detalle de la revision de meta de personal
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              //
		//                 $ao_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta una personal en la tabla srh_persona_revision_metas          
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 03/01/2008							Fecha Última Modificación: 03/01/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
		 
	  $ls_sql = "INSERT INTO srh_persona_revision_metas (nroreg, fecha, codper, tipo, codemp) ".	  
	            "VALUES ('$ao_revision->nroreg', '$ao_revision->fecha','$ao_revision->codeva','E','".$this->ls_codemp."')";
				
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó Persona de la Revsion de Meta Personal".$ao_revision->nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($ao_seguridad["empresa"],
												$ao_seguridad["sistema"],$ls_evento,$ao_seguridad["logusr"],
												$ao_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		

	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->revision_metas MÉTODO->uf_srh_guardar_evaluador ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	


}// end   class sigesp_srh_c_revision_metas
?>