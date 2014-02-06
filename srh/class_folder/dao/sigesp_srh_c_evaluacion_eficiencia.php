<?php

class sigesp_srh_c_evaluacion_eficiencia
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_evaluacion_eficiencia($path)
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
		//         Access: public (sigesp_srh_p_evaluacion_eficiencia)
		//      Argumento: 
		//	      Returns: Retorna el nuevo número de una evaluación de eficiencia
		//    Description: Funcion que genera un número de una evaluación de eficiencia
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(nroeval) AS numero FROM srh_evaluacion_eficiencia ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_nroeval = $la_datos["numero"][0]+1;
    $ls_nroeval= str_pad ($ls_nroeval,10,"0","left");
	return $ls_nroeval;
  }
	
		
 
  
function uf_srh_guardarevaluacion_eficiencia ($ao_evaluacion,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarevaluacion_eficiencia																		
		//         access: public (sigesp_srh_p_evaluacion_eficiencia)
		//      Argumento: $ao_evaluacion    // arreglo con los datos de la evaluación de eficiencia    
		//		            $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica una evaluación de desempeno en la tabla srh_evaluacion_eficiencia
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 30/12/2007							Fecha Última Modificación: 30/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$as_nroeval= $ao_evaluacion->nroeval;
	
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	 $ao_evaluacion->fecha=$this->io_funcion->uf_convertirdatetobd($ao_evaluacion->fecha);
   	 $ao_evaluacion->fecini=$this->io_funcion->uf_convertirdatetobd($ao_evaluacion->fecini);
	 $ao_evaluacion->fecfin=$this->io_funcion->uf_convertirdatetobd($ao_evaluacion->fecfin);
      
	 
	  $ls_sql = "UPDATE srh_evaluacion_eficiencia SET ".
		  		"fecha = '$ao_evaluacion->fecha' , ".
				"fecini = '$ao_evaluacion->fecini' ,  ".
				"fecfin = '$ao_evaluacion->fecfin' ,  ".
				"comen_sup = '$ao_evaluacion->comen' ,  ".
				"total  = '$ao_evaluacion->total' ,  ".
				"actuacion =   '$ao_evaluacion->ranact' ,  ".
				"tipo_eval =   '$ao_evaluacion->tipo' ,  ".
				"acciones =   '$ao_evaluacion->accion' ,  ".
				"observacion = '$ao_evaluacion->obs'   ".
				"WHERE nroeval = '$ao_evaluacion->nroeval' AND codemp='".$this->ls_codemp."'" ;

			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó la evaluación de eficiencia ".$as_nroeval;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	  $ao_evaluacion->fecha=$this->io_funcion->uf_convertirdatetobd($ao_evaluacion->fecha);
	  $ao_evaluacion->fecini=$this->io_funcion->uf_convertirdatetobd($ao_evaluacion->fecini);
	  $ao_evaluacion->fecfin=$this->io_funcion->uf_convertirdatetobd($ao_evaluacion->fecfin);
      
	
	  $ls_sql = "INSERT INTO srh_evaluacion_eficiencia (nroeval, fecha,fecini, fecfin, comen_sup, observacion, actuacion, total, tipo_eval, acciones, codemp) ".	  
	            "VALUES ('$ao_evaluacion->nroeval','$ao_evaluacion->fecha', '$ao_evaluacion->fecini','$ao_evaluacion->fecfin',  '$ao_evaluacion->comen', '$ao_evaluacion->obs', '$ao_evaluacion->ranact', '$ao_evaluacion->total', '$ao_evaluacion->tipo',  '$ao_evaluacion->accion', '".$this->ls_codemp."')"; 

		
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la evaluación de eficiencia ".$ao_evaluacion->nroeval;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->evaluacion_eficiencia MÉTODO->uf_srh_guardarevaluacion_eficiencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				$lb_guardo=false;
				
				if ($lb_valido)
				{
				//Guardamos el detalle de la evaluación de eficiencia
					$lb_guardo = $this->uf_guardarDetalle_evaluacion_eficiencia($ao_evaluacion, $aa_seguridad);
					if ($lb_guardo)
					{
				//Guardamos las Personas involucradas en la Evaluación de Eficiencia (Evaluado, Evaluador y Trabajador)
					$lb_guardo = $this->guardarPersonas_evaluacion_eficiencia($ao_evaluacion, $aa_seguridad);
					}
				}
				
				if ($lb_guardo)
				{
				  $this->io_sql->commit();
				
				}	
				else
				{
				 $this->io_sql->rollback();
				 $lb_valido=false;
				 
				}
			}		
	
	return $lb_valido;
  }
	
	
	
function uf_guardarDetalle_evaluacion_eficiencia ($ao_evaluacion, $aa_seguridad)

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardarDetalle_evaluacion_eficiencia																		
		//         access: public (sigesp_srh_p_evaluacion_eficiencia)
		//      Argumento: $ao_evaluacion    // arreglo con los datos de la evaluación de eficiencia         
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica una evaluación de desempeno en la tabla srh_evaluacion_eficiencia
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 30/12/2007							Fecha Última Modificación: 30/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  {
    //Borramos los registros anteriores 
	$this-> uf_srh_eliminar_Detalle_evaluacion_eficiencia($ao_evaluacion->nroeval, $aa_seguridad);
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_obj = 0;
	while (($li_obj < count($ao_evaluacion->eval_efi)) &&
	       ($lb_guardo))
	{
	  $lb_guardo = $this->uf_srh_guardar_Detalle_evaluacion_eficiencia($ao_evaluacion->eval_efi[$li_obj], $aa_seguridad);
	  $li_obj++;
	}
	
	return $lb_guardo;    
  }



	
function guardarPersonas_evaluacion_eficiencia ($ao_evaluacion, $aa_seguridad)
  {
   
	$lb_guardo = true;
	$as_nro = $ao_evaluacion->nroeval;
	  $lb_guardo1 = $this-> uf_srh_eliminar_persona($as_nro);
	  if ($lb_guardo1===true) {
		
	  }
     $lb_guardo = $this-> uf_srh_guardar_evaluador  ($ao_evaluacion, $aa_seguridad);
  	 $lb_guardo = $this-> uf_srh_guardar_trabajador ($ao_evaluacion, $aa_seguridad);  
	
	return $lb_guardo;    
  }
	
	
function uf_srh_eliminarevaluacion_eficiencia($as_nroeval, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarevaluacion_eficiencia																		
		//        access:  public (sigesp_srh_p_evaluacion_eficiencia)														
		//      Argumento: $as_nroeval        // código de la evaluación de eficiencia 
		//                 $aa_seguridad     //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina una solicitud de empleo en la tabla srh_evaluacion_eficiencia         
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 30/12/2007							Fecha Última Modificación: 30/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $this-> uf_srh_eliminar_Detalle_evaluacion_eficiencia($as_nroeval, $aa_seguridad);
	$this-> uf_srh_eliminar_persona($as_nroeval, $aa_seguridad);
    $this->io_sql->begin_transaction();	
	$this-> uf_srh_eliminar_Detalle_evaluacion_eficiencia($as_nroeval, $aa_seguridad);

    $ls_sql = "DELETE FROM srh_evaluacion_eficiencia ".
	          "WHERE nroeval = '$as_nroeval' AND codemp='".$this->ls_codemp."'";

	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->evaluacion_eficiencia MÉTODO->uf_srh_eliminarevaluacion_eficiencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la evaluación de eficiencia ".$as_nroeval;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				
					$this->io_sql->commit();
			}
	}
	

	
	
	
	
function uf_srh_buscar_evaluacion_eficiencia($as_nroeval,$as_fecha1,$as_fecha2,$as_tipo,$as_tipo_caja)
{
	    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_evaluacion_eficiencia															
		//         access: public (sigesp_srh_evaluacion_eficiencia)												
		//      Argumento: $as_nroeval   //  código de la evaluación de eficiencia
		// 				   $as_codper   //   codigo de la persona                                                             
		//                 $as_apeper   //   apellido de la persona                                                           
		//                 $as_nomper   //   nombre de la persona                                                             
		//                 $as_fecha   //    fecha de la evaluación de eficiencia	     
		//         Returns: Retorna un XML  																				
		//    Description: Funcion busca una evaluación en la tabla srh_evaluacion_eficiencia y crea un XML para mostrar    
		//                  los datos en el catalogo                                                                          
		//	   Creado Por: Maria Beatriz Unda																				  
		// Fecha Creación: 30/12/2007							Fecha Última Modificación: 30/12/2007					
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		$lb_valido=true;
		$as_fecha1=$this->io_funcion->uf_convertirdatetobd($as_fecha1);
		$as_fecha2=$this->io_funcion->uf_convertirdatetobd($as_fecha2);
		
	
		switch ($as_tipo)
		{
			case  "M":	//BOTON BUSCAR DEL PROCESO MAESTRO
				$ls_fechadestino="txtfecha";
				$ls_nroevaldestino="txtnroeval";
				$ls_fecinidestino="txtfecini1";
				$ls_fecfindestino="txtfecfin1";
				$ls_codperdestino="txtcodper";
				$ls_nomperdestino="txtnomper";
				$ls_codcarperdestino="txtcodcarper";
				$ls_resodidestino="txtresodi";
				$ls_rescomdestino="txtrescom";
				$ls_totaldestino="txttotal";
				$ls_acciondestino="txtaccion";
				$ls_ranactdestino="txtranact";
				$ls_obsdestino="txtobs";
				$ls_comsupdestino="txtcomsup";
				$ls_codevadestino="txtcodeva";
				$ls_nomevadestino="txtnomeva";
				$ls_codcarevadestino="txtcodcareva";
				$ls_codevaldestino="txtcodeval";
				$ls_denevaldestino="txtdeneval";
				
				$ls_sql= "SELECT * FROM srh_evaluacion_eficiencia INNER JOIN srh_persona_evaluacion_eficiencia ON (srh_persona_evaluacion_eficiencia.nroeval = srh_evaluacion_eficiencia.nroeval) INNER JOIN sno_personal ON (sno_personal.codper = srh_persona_evaluacion_eficiencia.codper)  INNER JOIN srh_tipoevaluacion ON  (srh_tipoevaluacion.codeval = srh_evaluacion_eficiencia.tipo_eval)   ".
				       " JOIN sno_personalnomina  ON  (srh_persona_evaluacion_eficiencia.codper=sno_personalnomina.codper)   ".
					   " LEFT JOIN sno_asignacioncargo ON  (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar and sno_personalnomina.codnom=sno_asignacioncargo.codnom)  ".
						" LEFT JOIN sno_cargo  ON  (sno_personalnomina.codcar=sno_cargo.codcar and sno_personalnomina.codnom=sno_cargo.codnom)  ".
						" JOIN sno_nomina ON ( sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0')  ".
						" WHERE srh_evaluacion_eficiencia.fecha between  '".$as_fecha1."' AND '".$as_fecha2."' ".
						"   AND srh_evaluacion_eficiencia.nroeval like '$as_nroeval' ".
						" ORDER BY srh_persona_evaluacion_eficiencia.nroeval";

			break;
			case "R":// REPORTE
		        if($as_tipo_caja=="1"){$ls_nroevaldestino="txtnroeval1";}
		        elseif($as_tipo_caja=="2"){$ls_nroevaldestino="txtnroeval2";}
				$ls_sql= "SELECT * FROM srh_evaluacion_eficiencia INNER JOIN srh_persona_evaluacion_eficiencia ON (srh_persona_evaluacion_eficiencia.nroeval = srh_evaluacion_eficiencia.nroeval) INNER JOIN sno_personal ON (sno_personal.codper = srh_persona_evaluacion_eficiencia.codper)  INNER JOIN srh_tipoevaluacion ON  (srh_tipoevaluacion.codeval = srh_evaluacion_eficiencia.tipo_eval)   ".
				" JOIN sno_personalnomina  ON  (srh_persona_evaluacion_eficiencia.codper=sno_personalnomina.codper)   ".
				" LEFT JOIN sno_asignacioncargo ON  (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar and sno_personalnomina.codnom=sno_asignacioncargo.codnom)  ".
				" LEFT JOIN sno_cargo  ON  (sno_personalnomina.codcar=sno_cargo.codcar and sno_personalnomina.codnom=sno_cargo.codnom)  ".
				" JOIN sno_nomina ON ( sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0')  ".
				" WHERE srh_evaluacion_eficiencia.fecha between  '".$as_fecha1."' AND '".$as_fecha2."' ".
				"   AND srh_evaluacion_eficiencia.nroeval like '$as_nroeval' ".
				" ORDER BY srh_persona_evaluacion_eficiencia.nroeval";
			break;	
		}
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->evaluacion_eficiencia MÉTODO->uf_srh_buscar_evaluacion_eficiencia( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
			        $ls_nroeval=$row["nroeval"];
					$ls_fecha=$this->io_funcion->uf_formatovalidofecha($row["fecha"]);
				    $ls_fecha=$this->io_funcion->uf_convertirfecmostrar($ls_fecha);
					$ls_fecini=$this->io_funcion->uf_formatovalidofecha($row["fecini"]);
				    $ls_fecini=$this->io_funcion->uf_convertirfecmostrar($ls_fecini);
					$ls_fecfin=$this->io_funcion->uf_formatovalidofecha($row["fecfin"]);
				    $ls_fecfin=$this->io_funcion->uf_convertirfecmostrar($ls_fecfin);			
					$ls_obs= trim (htmlentities ($row["observacion"]));
					$ls_comsup=trim (htmlentities  ($row["comen_sup"]));
					$ls_ranact=trim (htmlentities ($row["actuacion"]));
					$li_total=trim ($row["total"]);
					$ls_accion= trim (htmlentities  ($row["acciones"]));
					$ls_codeval=($row["codeval"]);
					$ls_deneval=trim(htmlentities ($row["deneval"]));
					$ls_cargo1= trim (htmlentities ($row["denasicar"]));
				    $ls_cargo2= trim (htmlentities ($row["descar"]));
					
					if ($row["tipo"]=="E") 
					{ 
					  $ls_apeeva = trim (htmlentities ($row["apeper"]));
					  $ls_nomeva= trim (htmlentities  ($row["nomper"]));
				      $ls_codeva=$row["codper"];
					 
					   if ($ls_cargo1!="Sin Asignación de Cargo")
				      {
					   $ls_codcareva=$ls_cargo1;
				       }
				      if ($ls_cargo2!="Sin Cargo")
				      {
					   $ls_codcareva=$ls_cargo2;
				       }	
					 
					  $ls_control=$ls_control + 1;
					 }
					else 
					{
					
					  $ls_apeper = trim (htmlentities ($row["apeper"]));
				      $ls_nomper= trim (htmlentities  ($row["nomper"]));
				      $ls_codper=$row["codper"];
					  
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
					
			if ($ls_control=="2") 
			{
				$row_ = $team->appendChild($dom->createElement('row'));
				$row_->setAttribute("id",$row['nroeval']);
				$cell = $row_->appendChild($dom->createElement('cell'));   
				switch ($as_tipo)
				{
					case  "M":
							
						$cell->appendChild($dom->createTextNode($row['nroeval']." ^javascript:aceptar( \"$ls_nroeval\",  \"$ls_fecha\",  \"$ls_apeper\",  \"$ls_nomper\",  \"$ls_codper\",  \"$ls_codcarper\", \"$ls_apeeva\",  \"$ls_nomeva\",  \"$ls_codeva\",  \"$ls_codcareva\",  \"$ls_fecini\",  \"$ls_fecfin\",    \"$ls_obs\",  \"$ls_comsup\" , \"$li_total\", \"$ls_ranact\", \"$ls_nroevaldestino\",  \"$ls_fechadestino\",   \"$ls_nomperdestino\",  \"$ls_codperdestino\",  \"$ls_codcarperdestino\",   \"$ls_nomevadestino\",  \"$ls_codevadestino\",  \"$ls_codcarevadestino\", \"$ls_fecinidestino\", \"$ls_fecfindestino\",   \"$ls_obsdestino\",  \"$ls_comsupdestino\" , \"$ls_totaldestino\", \"$ls_ranactdestino\",\"$ls_codeval\",\"$ls_deneval\",\"$ls_codevaldestino\",\"$ls_denevaldestino\",\"$ls_accion\", \"$ls_acciondestino\" );^_self"));
					break;
					case "R":
						
				$cell->appendChild($dom->createTextNode($row['nroeval']." ^javascript:aceptar_listado( \"$ls_nroeval\", \"$ls_nroevaldestino\" );^_self"));
				    break;
				}
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_codper));												
				$row_->appendChild($cell);
				
				if ($ls_apeper!='0')
				{
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
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_fecha));												
				$row_->appendChild($cell);
				$ls_control=0;
			}
		   }
		   return $dom->saveXML();
		
		}
      
		
} // end function buscar_evaluacion_eficiencia

//FUNCIONES PARA EL MANEJO DEL DETALLE DE LA EVALUACIÓN DE EFICIENCIA

function uf_srh_guardar_Detalle_evaluacion_eficiencia($ao_evaluacion, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_Detalle_evaluacion_eficiencia																												
		//      Argumento: $ao_evaluacion   // arreglo con los datos de los detalle de la evaluación de eficiencia                
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta una competencia de evaluación en la tabla srh_Detalle_evaluacion_eficiencia
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 30/12/2007							Fecha Última Modificación: 30/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	 if (($ao_evaluacion->puntos==0) || ($ao_evaluacion->puntos=='0'))
	 {
	    $ao_evaluacion->puntos=0;
	 }
	 
  
	 $this->io_sql->begin_transaction();
	 
	 
	  $ls_sql = "INSERT INTO srh_dt_evaluacion_eficiencia (nroeval, codite, puntos, codemp) ".	  
	            " VALUES ('$ao_evaluacion->nroeval','$ao_evaluacion->codite','$ao_evaluacion->puntos',  '".$this->ls_codemp."')";
	
	/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la competencia de la evaluación de desempeno ".$ao_evaluacion->nroeval;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->evaluacion_eficiencia MÉTODO->uf_srh_guardar_Detalle_evaluacion_eficiencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
	
function uf_srh_eliminar_Detalle_evaluacion_eficiencia($as_nroeval, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_Detalle_evaluacion_eficiencia																													
		//      Argumento: $as_nroeval       // código de la evaluación 
		//	               $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																				    
		//    Description: Funcion que elimina competencias de evaluación en la tabla srh_Detalle_evaluacion_eficiencia
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 30/12/2007							Fecha Última Modificación: 30/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_dt_evaluacion_eficiencia ".
	          " WHERE nroeval='$as_nroeval' AND codemp='".$this->ls_codemp."'";
    


	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->evaluacion_eficiencia MÉTODO->uf_srh_eliminar_Detalle_evaluacion_eficiencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la competencia de la evaluacion de desempeno ".$as_nroeval;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
			
	
	return $lb_borro;
	
  }
  
  
 
function uf_srh_load_evaluacion_eficiencia($as_nroeval, &$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_evaluacion_eficiencia
		//	    Arguments: as_nroeval  // código de la evaluación de eficiencia
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene las competencias de una evaluación de eficiencia
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
				
		$ls_sql="SELECT * ".
				"  FROM srh_items_evaluacion, srh_aspectos_evaluacion, srh_dt_evaluacion_eficiencia ".
				"  WHERE srh_dt_evaluacion_eficiencia.codemp='".$this->ls_codemp."'".
				"  AND srh_dt_evaluacion_eficiencia.nroeval = '$as_nroeval' ".
				"  AND srh_dt_evaluacion_eficiencia.codite = srh_items_evaluacion.codite ".
				"  AND srh_items_evaluacion.codasp = srh_aspectos_evaluacion.codasp ".
				" ORDER BY srh_dt_evaluacion_eficiencia.codite ";
        
	

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->evaluacion_eficiencia MÉTODO->uf_srh_load_evaluacion_eficiencia_competencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			$ai_tot=0;
		    $aux_aspecto1 = 0;
			$aux_aspecto = 0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
			
			    if ($aux_aspecto != $row["codasp"])
				   {   
				     
					   $ls_aspecto =htmlentities  ($row["denasp"]);
					   $ls_codite=$row["codite"];
					   $ls_denite= htmlentities  ($row["denite"]);
					   $li_valor=$row["valormax"];	
					   $li_puntos=$row["puntos"];
					 
						
					  if ( $aux_aspecto1 != $row["codasp"])
					 {  
						 $aux_aspecto1 = $row["codasp"];
					      $ai_totrows=$ai_totrows+1;
					      $ao_object[$ai_totrows][1]="<input name=txtcodite".$ai_totrows." type=text id=txtcodite".$ai_totrows." class=sin-borde size=15 value=''  readonly  >";
					      $ao_object[$ai_totrows][2]="<input name=txtdenite".$ai_totrows." type=text id=txtdenite".$ai_totrows." class=sin-borde2 size=60 style='text-align:center' value='".$ls_aspecto."' readonly  >";					  
					      $ao_object[$ai_totrows][3]="<input name=rdselec".$ai_tot." type=radio class='sin-borde' style='display:none' value=0 >";
						  $ai_tot=$ai_tot+1;
					
					}
					
					$ai_totrows=$ai_totrows+1;
					
					$ao_object[$ai_totrows][1]="<input name=txtcodite".$ai_totrows." type=text id=txtcodite".$ai_totrows." class=sin-borde size=15 value='".$ls_codite."'  readonly  >";
					$ao_object[$ai_totrows][2]="<textarea name=txtdenite".$ai_totrows."    cols=80 rows=3 id=txtdenite".$ai_totrows."  class=sin-borde readonly>$ls_denite</textarea><input name=txtpuntos".$ai_totrows." type=hidden value='".$li_valor."' id=txtpuntos".$ai_totrows." size=5 class=sin-borde readonly >";
					if ($li_puntos!=0)
					{				
					  $ao_object[$ai_totrows][3]="<input name=rdselec".$ai_tot." type=radio class='sin-borde' onClick='javascript: suma(txttotal);' value=".$ai_totrows." checked>";
					}
					else
					{
						 $ao_object[$ai_totrows][3]="<input name=rdselec".$ai_tot." type=radio class='sin-borde'  value=".$ai_totrows." onClick='javascript: suma(txttotal);'>";
					}
				
					
					  
				}  
			}
		    $aux_aspecto = $row["codasp"];
							
				
			}
			$this->io_sql->free_result($rs_data);
		
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	
	

	
//FUNCIONES PARA EL MANEJO DEL LAS PERSONAS INVOLUACRADAS EN LA EVALUACIÓN DE EFICIENCIA

function uf_srh_eliminar_persona($as_nroeval)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_persona																																	
		//      Argumento: $as_nroeval	      // código de la evaluación de eficiencia						
		//                 $aa_seguridad     //  arreglo de registro de seguridad                                  
		//	      Returns: Retorna un Booleano																					
		//    Description: Elimina a las personas involucradas en una evaluación de eficiencia
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 30/12/2007							Fecha Última Modificación: 30/12/2007						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_persona_evaluacion_eficiencia ".
	          "WHERE nroeval = '$as_nroeval'   AND codemp='".$this->ls_codemp."'";
			  
			  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->evaluacion_eficiencia MÉTODO->uf_srh_eliminar_persona ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	



function uf_srh_guardar_trabajador ($ao_evaluacion, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_trabajador																     
		//         access: public 														
		//      Argumento: $ao_evaluacion    // arreglo con los datos de los detalle de la evaluación de eficiencia							
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta un trabajador en una evaluacion de desempeño en la tabla 
		//                 srh_persona_evaluacion_eficiencia	        
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 30/12/2007							Fecha Última Modificación: 30/12/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 
		 
	  $ls_sql = "INSERT INTO srh_persona_evaluacion_eficiencia (nroeval,codper, tipo, codemp) ".	  
	            "VALUES ('$ao_evaluacion->nroeval','$ao_evaluacion->codper','P','".$this->ls_codemp."')";
				
				

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó Persona de la evaluación de eficiencia ".$ao_evaluacion->nroeval;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->evaluacion_eficiencia MÉTODO->uf_srh_guardar_trabajador ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
  
  
 function uf_srh_guardar_evaluador ($ao_evaluacion, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_evaluador															     	
		//         access: public 													      
		//      Argumento: $ao_evaluacion    // arreglo con los datos de los detalle de la evaluacion de desempeño					
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                              
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta un evaluador en la tabla srh_persona_evaluacion_eficiencia
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 30/12/2007							Fecha Última Modificación: 30/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 
		 
	  $ls_sql = "INSERT INTO srh_persona_evaluacion_eficiencia (nroeval,codper, tipo, codemp) ".	  
	            "VALUES ('$ao_evaluacion->nroeval','$ao_evaluacion->codeva','E','".$this->ls_codemp."')";
				
				

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó Persona de la Revsion de ODI".$ao_evaluacion->nroeval;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->evaluacion_eficiencia MÉTODO->guardar_perosonas_evaluacion_eficiencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
  
//FUNCION PARA CONSULTAR EL RANGO DE ACTUACIÓN 


function uf_srh_consultar_rango_actuacion ($as_codeval, $ai_total)
{
		
		$lb_valido=true;
		
		
		$ls_sql= "SELECT * FROM srh_tipoevaluacion INNER JOIN srh_dt_escalageneral ON (srh_tipoevaluacion.codesc = srh_dt_escalageneral.codesc) ".
				" WHERE srh_tipoevaluacion.codeval = '$as_codeval' ".
				"   AND srh_dt_escalageneral.valinidetesc <=  '$ai_total' ".
				"   AND srh_dt_escalageneral.valfindetesc >=  '$ai_total' ".
				" ORDER BY srh_tipoevaluacion.codeval"; 
				
		

	 	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->evaluacion_eficiencia MÉTODO->uf_srh_consultar_rango_actuacion( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}
		else
		{
		 $num=$this->io_sql->num_rows($rs_data);
		 if ($num!=0) {
		  while ($row=$this->io_sql->fetch_row($rs_data)) 
		  {   					
		    $as_ranact = htmlentities ($row["dendetesc"]);
		 }    
		}	
		 else
			{   		
		   $as_ranact='No pertenece a ningún rango de actuación';
		 }
	}
	 
  return $as_ranact;
 }
 
 
function uf_srh_consultar_items ($as_codeval, &$ai_totrows,&$ao_object)
{
		
		$lb_valido=true;
		
		$ls_sql= "SELECT srh_tipoevaluacion.codeval, srh_aspectos_evaluacion.codeval, srh_aspectos_evaluacion.codasp, srh_aspectos_evaluacion.denasp, srh_items_evaluacion.codeval, srh_items_evaluacion.codasp, srh_items_evaluacion.codite, srh_items_evaluacion.denite, srh_items_evaluacion.valormax  FROM srh_tipoevaluacion  INNER JOIN srh_aspectos_evaluacion ON (srh_tipoevaluacion.codeval = srh_aspectos_evaluacion.codeval) INNER JOIN srh_items_evaluacion ON (srh_aspectos_evaluacion.codasp =  srh_items_evaluacion.codasp AND srh_tipoevaluacion.codeval = srh_items_evaluacion.codeval)  ".
				" WHERE srh_tipoevaluacion.codeval = '$as_codeval' ".
				" ORDER BY srh_aspectos_evaluacion.codasp";
				
	 	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->evaluacion_eficiencia MÉTODO->uf_srh_consultar_items( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}
		else
		{ 
		   $num=$this->io_sql->num_rows($rs_data);
           
		  if ($num!=0) {
		       $ai_totrows=0;
			   $ai_tot=0;
			  $aux_aspecto1 = 0;
			   $aux_aspecto = 0;
				while ($row=$this->io_sql->fetch_row($rs_data)) 
				{   
				if ($aux_aspecto != $row["codasp"])
				   {   
				     
					   $ls_aspecto = $row["denasp"];
					   $ls_codite=$row["codite"];
					   $ls_denite= trim (htmlentities ($row["denite"]));
					   $li_valor=$row["valormax"];	
						
					  if ( $aux_aspecto1 != $row["codasp"])
					 {  
						$aux_aspecto1 = $row["codasp"];
					      $ai_totrows=$ai_totrows+1;
					      $ao_object[$ai_totrows][1]="<input name=txtcodite".$ai_totrows." type=text id=txtcodite".$ai_totrows." class=sin-borde size=15 value=''  readonly  >";
					      $ao_object[$ai_totrows][2]="<input name=txtdenite".$ai_totrows." type=text id=txtdenite".$ai_totrows." class=sin-borde2 size=60 style='text-align:center' value='".$ls_aspecto."' readonly  >";					  
					      $ao_object[$ai_totrows][3]="<input name=rdselec".$ai_tot." type=radio class='sin-borde' style='display:none' value=0 >";
						  $ai_tot=$ai_tot+1;
					
					}
					
					$ai_totrows=$ai_totrows+1;
					
					$ao_object[$ai_totrows][1]="<input name=txtcodite".$ai_totrows." type=text id=txtcodite".$ai_totrows." class=sin-borde size=15 value='".$ls_codite."'  readonly  >";
					$ao_object[$ai_totrows][2]="<textarea name=txtdenite".$ai_totrows."    cols=80 rows=3 id=txtdenite".$ai_totrows."  class=sin-borde readonly>$ls_denite</textarea><input name=txtpuntos".$ai_totrows." type=hidden value='".$li_valor."' id=txtpuntos".$ai_totrows." size=5 class=sin-borde readonly >";
					$ao_object[$ai_totrows][3]="<input name=rdselec".$ai_tot." type=radio class='sin-borde' value=".$ai_totrows."  onClick='javascript: suma (txttotal)'>";				
					  
					}  
				}
		    $aux_aspecto = $row["codasp"];
		}
		else 
		  {
		     $this->io_msg->message("No se encontraron Items de Evaluación.");
	 		$ai_totrows=1;
			$ai_tot=1;	
			$ao_object[$ai_totrows][1]="<input name=txtcodite".$ai_totrows." type=text id=txtcodite".$ai_totrows." class=sin-borde size=15  readonly  >";
			$ao_object[$ai_totrows][2]="<textarea name=txtdenite".$ai_totrows."    cols=80 rows=3 id=txtdenite".$ai_totrows."  class=sin-borde readonly> </textarea>";
			 $ao_object[$ai_totrows][3]="<input name=rdselec".$ai_tot." type=radio class='sin-borde' style='display:none' >";
			
			
		  }  
		return $lb_valido;
	
		}
      
		
	} // end uf_srh_consultar_items
		


}// end   class sigesp_srh_c_evaluacion_eficiencia
?>