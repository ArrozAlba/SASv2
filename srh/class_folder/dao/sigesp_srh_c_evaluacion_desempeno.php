<?php

class sigesp_srh_c_evaluacion_desempeno
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_evaluacion_desempeno($path)
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
		//         Access: public (sigesp_srh_p_evaluacion_desempeno)
		//      Argumento: 
		//	      Returns: Retorna el nuevo número de una evaluación de desempeño
		//    Description: Funcion que genera un número de una evaluación de desempeño
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(nroeval) AS numero FROM srh_evaluacion_desempeno ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_nroeval = $la_datos["numero"][0]+1;
    $ls_nroeval= str_pad ($ls_nroeval,10,"0","left");
     return $ls_nroeval;
  }
		
   
function uf_srh_guardarevaluacion_desempeno ($ao_evaluacion,$ps_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarevaluacion_desempeno																		
		//         access: public (sigesp_srh_p_evaluacion_desempeno)
		//      Argumento: $ao_evaluacion    // arreglo con los datos de la evaluación de desempeño    
		//		            $ps_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica una evaluación de desempeno en la tabla srh_evaluacion_desempeno
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 27/12/2007							Fecha Última Modificación: 27/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$as_nroeval= $ao_evaluacion->nroeval;
  	if ($ps_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	 $ao_evaluacion->fecha=$this->io_funcion->uf_convertirdatetobd($ao_evaluacion->fecha);
   	 $ao_evaluacion->fecini=$this->io_funcion->uf_convertirdatetobd($ao_evaluacion->fecini);
	 $ao_evaluacion->fecfin=$this->io_funcion->uf_convertirdatetobd($ao_evaluacion->fecfin);
      
	 
	  $ls_sql = "UPDATE srh_evaluacion_desempeno SET ".
		  		"fecha = '$ao_evaluacion->fecha' , ".
				"fecinie = '$ao_evaluacion->fecini' ,  ".
				"fecfine = '$ao_evaluacion->fecfin' ,  ".
				"totalodi = '$ao_evaluacion->totalodi' ,  ".
				"totalcompe = '$ao_evaluacion->totalcom' ,   ".
				"actuacion = '$ao_evaluacion->ranact' , ".
				"obs_sup = '$ao_evaluacion->comentario' ,  ".
				"tipo_eval = '$ao_evaluacion->tipo' ,  ".
				"obs_jefe = '$ao_evaluacion->opinion'   ".
				"WHERE nroeval = '$ao_evaluacion->nroeval' AND codemp='".$this->ls_codemp."'" ;


			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó la evaluación de desempeño ".$as_nroeval;
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
      
	
	  $ls_sql = "INSERT INTO srh_evaluacion_desempeno (nroeval, fecha,fecinie, fecfine,  totalodi, totalcompe, actuacion, obs_sup, obs_jefe, tipo_eval, codemp) ".	  
	            "VALUES ('$ao_evaluacion->nroeval','$ao_evaluacion->fecha', '$ao_evaluacion->fecini','$ao_evaluacion->fecfin',  '$ao_evaluacion->totalodi', '$ao_evaluacion->totalcom', '$ao_evaluacion->ranact', '$ao_evaluacion->comentario', '$ao_evaluacion->opinion','$ao_evaluacion->tipo', '".$this->ls_codemp."')";


		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la evaluación de desempeño ".$ao_evaluacion->nroeval;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->evaluacion_desempeno MÉTODO->uf_srh_guardarevaluacion_desempeno ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				$lb_guardo=false;
				if ($lb_valido)
				{
				   //Guardamos el detalle de la evaluación de desempeño
					$lb_guardo1 = $this->uf_guardarCompetencias_evaluacion_desempeno($ao_evaluacion, $aa_seguridad);
					$lb_guardo2 = $this->uf_guardarODIS_evaluacion_desempeno($ao_evaluacion, $aa_seguridad);
					//Guardamos las Personas involucradas en la  evaluacion de desempeno (Evaluado, Evaluador y Trabajador)
					$lb_guardo3 = $this->guardarPersonas_evaluacion_desempeno($ao_evaluacion, $aa_seguridad);
					
					
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
	
	
	
function uf_guardarCompetencias_evaluacion_desempeno ($ao_evaluacion, $aa_seguridad)

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardarCompetencias_evaluacion_desempeno																		
		//         access: public (sigesp_srh_p_evaluacion_desempeno)
		//      Argumento: $ao_evaluacion    // arreglo con los datos de la evaluación de desempeño         
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica una evaluación de desempeno en la tabla srh_evaluacion_desempeno
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 27/12/2007							Fecha Última Modificación: 27/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  {
    //Borramos los registros anteriores 
	$this-> uf_srh_eliminar_competencias_evaluacion_desempeno($ao_evaluacion->nroeval, $aa_seguridad);
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_obj = 0;
	while (($li_obj < count($ao_evaluacion->competencia)) &&
	       ($lb_guardo))
	{
	  $lb_guardo = $this->uf_srh_guardar_competencias_evaluacion_desempeno($ao_evaluacion->competencia[$li_obj], $aa_seguridad);
	  $li_obj++;
	}
	
	return $lb_guardo;    
  }


function uf_guardarODIS_evaluacion_desempeno ($ao_evaluacion, $aa_seguridad)
  {
  
   		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardarODIS_evaluacion_desempeno																		
		//         access: public (sigesp_srh_p_evaluacion_desempeno)
		//      Argumento: $ao_evaluacion    // arreglo con los datos de la evaluación de desempeño         
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica una evaluación de desempeno en la tabla srh_evaluacion_desempeno
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 27/12/2007							Fecha Última Modificación: 27/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    //Borramos los registros anteriores 
	$this-> uf_srh_eliminar_evaluacion_odi($ao_evaluacion->nroeval, $aa_seguridad);
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_obj = 0;
	while (($li_obj < count($ao_evaluacion->odi)) &&
	       ($lb_guardo))
	{
	  $lb_guardo = $this-> uf_srh_guardar_evaluacion_odi($ao_evaluacion->odi[$li_obj], $aa_seguridad);
	  $li_obj++;
	}
	
	return $lb_guardo;    
  }

	
function guardarPersonas_evaluacion_desempeno ($ao_evaluacion, $aa_seguridad)
  {
   
	$lb_guardo = true;
	$as_nro = $ao_evaluacion->nroeval;
	  $lb_guardo1 = $this-> uf_srh_eliminar_persona($as_nro);
	  if ($lb_guardo1===true) {
		
	  }
     $lb_guardo = $this-> uf_srh_guardar_evaluador  ($ao_evaluacion, $aa_seguridad);
  	 $lb_guardo = $this-> uf_srh_guardar_trabajador ($ao_evaluacion, $aa_seguridad); 
	 $lb_guardo = $this-> uf_srh_guardar_supervisor ($ao_evaluacion, $aa_seguridad); 
	
	return $lb_guardo;    
  }
	
	
function uf_srh_eliminarevaluacion_desempeno($as_nroeval, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarevaluacion_desempeno																		
		//        access:  public (sigesp_srh_p_evaluacion_desempeno)														
		//      Argumento: $as_nroeval        // código de la evaluación de desempeño 
		//                 $aa_seguridad     //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina una solicitud de empleo en la tabla srh_evaluacion_desempeno         
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 27/12/2007							Fecha Última Modificación: 27/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
	$this-> uf_srh_eliminar_competencias_evaluacion_desempeno($as_nroeval, $aa_seguridad);
	$this-> uf_srh_eliminar_evaluacion_odi($as_nroeval, $aa_seguridad);
	$this-> uf_srh_eliminar_persona($as_nroeval, $aa_seguridad);

    $ls_sql = "DELETE FROM srh_evaluacion_desempeno ".
	          "WHERE nroeval = '$as_nroeval' AND codemp='".$this->ls_codemp."'";

	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->evaluacion_desempeno MÉTODO->uf_srh_eliminarevaluacion_desempeno ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la evaluación de desempeño ".$as_nroeval;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				
					$this->io_sql->commit();
			}
	
	
	return $lb_borro;
  }
	

	
function uf_srh_buscar_evaluacion_desempeno($as_nroeval,$as_fecha1,$as_fecha2)
	{
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_evaluacion_desempeno																		
		//         access: public (sigesp_srh_evaluacion_desempeno)												
		//      Argumento: $as_nroeval   //  código de la evaluación de desempeño
		// 				   $as_codper   //   codigo de la persona                                                             
		//                 $as_apeper   //   apellido de la persona                                                            
		//                 $as_nomper   //   nombre de la persona                                                             
		//                 $as_fecha   //    fecha de la evaluación de desempeño	     
		//         Returns: Retorna un XML  																						
		//    Description: Funcion busca una evaluación en la tabla srh_evaluacion_desempeno y crea un XML para mostrar    
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 27/12/2007							Fecha Última Modificación: 27/12/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
		$as_fecha1=$this->io_funcion->uf_convertirdatetobd($as_fecha1);
		$as_fecha2=$this->io_funcion->uf_convertirdatetobd($as_fecha2);
		
		
	
		$ls_fechadestino="txtfecha";
		$ls_nroevaldestino="txtnroeval";

		$ls_codperdestino="txtcodper";
		$ls_nomperdestino="txtnomper";
		$ls_codcarperdestino="txtcodcarper";
		$ls_dencarperdestino="txtdencarper";
	
		
		
		$ls_resodidestino="txtresodi";
		$ls_rescomdestino="txtrescom";
		$ls_totaldestino="txttotal";
		$ls_ranactdestino="txtranact";
				
	    $ls_obsdestino="txtobs";
		$ls_opidestino="txtopi";
	
	    $ls_codevadestino="txtcodeva";
		$ls_nomevadestino="txtnomeva";
		$ls_codcarevadestino="txtcodcareva";
		$ls_dencarevadestino="txtdencareva";
		$ls_fecinidestino="txtfecini";
		$ls_fecfindestino="txtfecfin";
		
		$ls_codsupdestino="txtcodsup";
		$ls_nomsupdestino="txtnomsup";
		$ls_codcarsupdestino="txtcodcarsup";
		$ls_dencarsupdestino="txtdencarsup";
		$ls_codevaldestino="txtcodeval";
		$ls_denevaldestino="txtdeneval";


		$lb_valido=true;
		

				
		$ls_sql= "SELECT * FROM srh_evaluacion_desempeno INNER JOIN srh_persona_evaluacion_desempeno ON (srh_persona_evaluacion_desempeno.nroeval = srh_evaluacion_desempeno.nroeval) INNER JOIN sno_personal ON (sno_personal.codper = srh_persona_evaluacion_desempeno.codper) INNER JOIN srh_tipoevaluacion ON  (srh_tipoevaluacion.codeval = srh_evaluacion_desempeno.tipo_eval) ".
				" JOIN sno_personalnomina  ON  (srh_persona_evaluacion_desempeno.codper=sno_personalnomina.codper)   ".
				" LEFT JOIN sno_asignacioncargo ON  (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar and sno_personalnomina.codnom=sno_asignacioncargo.codnom)  ".
				" LEFT JOIN sno_cargo  ON  (sno_personalnomina.codcar=sno_cargo.codcar and sno_personalnomina.codnom=sno_cargo.codnom)  ".
				" JOIN sno_nomina ON ( sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0')  ".
				" WHERE srh_evaluacion_desempeno.fecha BETWEEN  '".$as_fecha1."' AND '".$as_fecha2."' ".
				"   AND srh_evaluacion_desempeno.nroeval LIKE '$as_nroeval' ".
				" ORDER BY srh_persona_evaluacion_desempeno.nroeval";




	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->evaluacion_desempeno MÉTODO->uf_srh_buscar_evaluacion_desempeno( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		     $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);		
			 $ls_control=0;
			 $ls_apeeva ="";
			 $ls_nomeva= "";
			 $ls_codeva="";
			 $ls_codcareva="";
			 $ls_dencareva="";
			 $ls_apesup= "";
		     $ls_nomsup= "";
		     $ls_codsup="";
	   	     $ls_codcarsup="";
			 $ls_dencarsup2="";
			 $ls_apeper = "";
			 $ls_nomper= "";
			 $ls_codper="";
			 $ls_codcarper="";
			 $ls_dencarper="";
			 
			 
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			        $ls_nroeval=$row["nroeval"];
					$ls_fecha=$this->io_funcion->uf_formatovalidofecha($row["fecha"]);
				    $ls_fecha=$this->io_funcion->uf_convertirfecmostrar($ls_fecha);
					
					$ls_fecini=$this->io_funcion->uf_formatovalidofecha($row["fecinie"]);
				    $ls_fecini=$this->io_funcion->uf_convertirfecmostrar($ls_fecini);
					
					$ls_fecfin=$this->io_funcion->uf_formatovalidofecha($row["fecfine"]);
				    $ls_fecfin=$this->io_funcion->uf_convertirfecmostrar($ls_fecfin);
				
					$ls_opi=trim (htmlentities  ($row["obs_jefe"]));
					$ls_obs=trim(htmlentities ($row["obs_sup"]));
					$ls_ranact=trim (htmlentities ($row["actuacion"]));
					$li_resodi= trim ($row["totalodi"]);
					$li_rescom=trim ($row["totalcompe"]);
					$li_total=($row["totalodi"] + $row["totalcompe"]);
					$ls_codeval=($row["codeval"]);
					$ls_deneval=trim (htmlentities ($row["deneval"]));	
					
					
					$ls_cargo1= trim (htmlentities ($row["denasicar"]));
					 $ls_cargo2= trim (htmlentities ($row["descar"]));
					
					if ( $row["tipo"]=="S")
					{
					  $ls_apesup = trim (htmlentities ($row["apeper"]));
				      $ls_nomsup= trim (htmlentities ($row["nomper"]));
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
					else if ($row["tipo"]=="P")
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
					 	  
					

										
			if ($ls_control>=3)  {
		
				
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['nroeval']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($row['nroeval']." ^javascript:aceptar(\"$ls_nroeval\", \"$ls_fecha\", \"$ls_apeper\", \"$ls_nomper\", \"$ls_codper\", \"$ls_codcarper\", \"$ls_dencarper\", \"$ls_apeeva\", \"$ls_nomeva\", \"$ls_codeva\", \"$ls_codcareva\", \"$ls_dencareva\", \"$ls_apesup\", \"$ls_nomsup\", \"$ls_codsup\", \"$ls_codcarsup\", \"$ls_dencarsup2\" ,  \"$li_resodi\", \"$li_rescom\", \"$li_total\", \"$ls_ranact\", \"$ls_obs\", \"$ls_opi\" , \"$ls_nroevaldestino\", \"$ls_fechadestino\",  \"$ls_nomperdestino\", \"$ls_codperdestino\", \"$ls_codcarperdestino\", \"$ls_dencarperdestino\", \"$ls_nomevadestino\", \"$ls_codevadestino\", \"$ls_codcarevadestino\", \"$ls_dencarevadestino\", \"$ls_nomsupdestino\", \"$ls_codsupdestino\", \"$ls_codcarsupdestino\", \"$ls_dencarsupdestino\" ,  \"$ls_resodidestino\", \"$ls_rescomdestino\", \"$ls_totaldestino\", \"$ls_ranactdestino\", \"$ls_obsdestino\", \"$ls_opidestino\",\"$ls_codeval\",\"$ls_deneval\",\"$ls_codevaldestino\",\"$ls_denevaldestino\", \"$ls_fecini\",\"$ls_fecfin\",\"$ls_fecinidestino\",\"$ls_fecfindestino\");^_self"));
					
				
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
      
		
	} // end function buscar_evaluacion_desempeno
	

//FUNCIONES PARA EL MANEJO DEL DETALLE DE LA EVALUACIÓN DE DESEMPEÑO

function uf_srh_guardar_competencias_evaluacion_desempeno($ao_evaluacion, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_competencias_evaluacion_desempeno																												
		//      Argumento: $ao_evaluacion   // arreglo con los datos de los detalle de la evaluación de desempeño                
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta una competencia de evaluación en la tabla srh_competencias_evaluacion_desempeno
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 27/12/2007							Fecha Última Modificación: 27/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
    if (($ao_evaluacion->rango==0) || ($ao_evaluacion->rango=='0'))
	 {
	    $ao_evaluacion->rango=0;
	 }
	 if (($ao_evaluacion->peso==0) || ($ao_evaluacion->peso=='0'))
	 {
	    $ao_evaluacion->peso=0;
	 }
	 
	 
	  $ls_sql = "INSERT INTO srh_competencias_evaluacion_desempeno (nroeval, codite, peso, rango, codemp) ".	  
	            " VALUES ('$ao_evaluacion->nroeval','$ao_evaluacion->codcom','$ao_evaluacion->peso', '$ao_evaluacion->rango','".$this->ls_codemp."')";

 

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
			$this->io_msg->message("CLASE->evaluacion_desempeno MÉTODO->uf_srh_guardar_competencias_evaluacion_desempeno ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		
		}
		else
		{
				$lb_valido=true;
				
		}
		
	return $lb_guardo;
  }
	
	
function uf_srh_eliminar_competencias_evaluacion_desempeno($as_nroeval, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_competencias_evaluacion_desempeno																													
		//      Argumento: $as_nroeval       // código de la evaluación 
		//	               $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																				    
		//    Description: Funcion que elimina competencias de evaluación en la tabla srh_competencias_evaluacion_desempeno
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 27/12/2007							Fecha Última Modificación: 27/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_competencias_evaluacion_desempeno ".
	          " WHERE nroeval='$as_nroeval' AND codemp='".$this->ls_codemp."'";
    


	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->evaluacion_desempeno MÉTODO->uf_srh_eliminar_competencias_evaluacion_desempeno ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
  
  
  
  
 function uf_srh_guardar_evaluacion_odi($ao_evaluacion, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_evaluacion_odi															     													
		//      Argumento: $ao_evaluacion    // arreglo con los datos de los detalle de la evaluación de desempeño     
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta un odi en la tabla srh_evaluacion_odi
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 27/12/2007							Fecha Última Modificación: 27/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 if (($ao_evaluacion->rango==0) || ($ao_evaluacion->rango=='0'))
	 {
	    $ao_evaluacion->rango=0;
	 }
	 if (($ao_evaluacion->pesran==0) || ($ao_evaluacion->pesran=='0'))
	 {
	    $ao_evaluacion->pesran=0;
	 }
	 
	  $ls_sql = "INSERT INTO srh_evaluacion_odi (nroeval, cododi, rango, peso_rango, codemp) ".	  
	            " VALUES ('$ao_evaluacion->nroeval','$ao_evaluacion->cododi','$ao_evaluacion->rango', '$ao_evaluacion->pesran','".$this->ls_codemp."')";

    

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el odi de la evalución ".$ao_evaluacion->nroeval;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->evaluacion_desempeno MÉTODO->uf_srh_guardar_evaluacion_odi ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			
		}
		else
		{
				$lb_valido=true;
				
		}
		
	return $lb_guardo;
  }
	
	
function uf_srh_eliminar_evaluacion_odi($as_nroeval, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_srh_eliminar_evaluacion_odi																										
		//      Argumento: $as_nroeval        // código de la evaluación de desempeño
		//	               $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																				    
		//    Description: Funcion que elimina un ODI en la tabla srh_evaluacion_odi                      
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 27/12/2007							Fecha Última Modificación: 27/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_evaluacion_odi ".
	          " WHERE nroeval='$as_nroeval' AND codemp='".$this->ls_codemp."'";
    


	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->evaluacion_desempeno MÉTODO->uf_srh_eliminar_evaluacion_odi ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el ODI ".$as_nroeval;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
			
	
	return $lb_borro;
	
  }
  
  
  
 function uf_load_evaluacion_desempeno_odi($as_nroeval, $as_codper, $as_fecini,$as_fecfin, &$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_evaluacion_desempeno_odi
		//	    Arguments: $as_nroeval   // código de la evlauación de desempeño
		//                 $as_codper   // código del personal
		//				   $ai_totrows  // total de filas del detalle
		//				   $ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de un ODI de una evaluación de desempeño
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$as_fecini=$this->io_funcion->uf_convertirdatetobd($as_fecini);
		$as_fecfin=$this->io_funcion->uf_convertirdatetobd($as_fecfin);
		
		
		$ls_sql="SELECT * ".
				"  FROM  srh_odi,srh_dt_odi,srh_persona_odi, srh_evaluacion_odi ".
				"  WHERE srh_evaluacion_odi.codemp='".$this->ls_codemp."'".
				"  AND srh_evaluacion_odi.nroeval = '$as_nroeval' ".
				"  AND srh_evaluacion_odi.cododi = srh_dt_odi.cododi ".
				"  AND srh_odi.fecha  BETWEEN  '".$as_fecini."' AND '".$as_fecfin."'".
				"  AND srh_persona_odi.codper = '$as_codper' ".
				"  AND srh_persona_odi.nroreg = srh_dt_odi.nroreg ".
				"  AND srh_odi.nroreg = srh_dt_odi.nroreg ".
				" ORDER BY srh_evaluacion_odi.cododi ";
        


		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->evaluacion_desempeno MÉTODO->uf_load_evaluacion_desempeno_odi ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_odi= trim(htmlentities ($row["odi"]));
				$ls_cododi= trim($row["cododi"]);
				$li_valor=$row["valor"];
				$li_rango=$row["rango"];
				$li_pesran= ($row["valor"] * $row["rango"]);
												
				$ao_object[$ai_totrows][1]="<textarea name=txtodi".$ai_totrows."  cols=60 rows=3 id=txtodi".$ai_totrows." class=sin-borde readonly>".$ls_odi."</textarea><input name=txtcododi".$ai_totrows." type=hidden class=sin-borde id=txtcododi".$ai_totrows."  readonly value=".$ls_cododi.">";
				$ao_object[$ai_totrows][2]="<textarea name=txtvalor".$ai_totrows."    cols=6 rows=3 id=txtvalor".$ai_totrows."  class=sin-borde readonly>".$li_valor." </textarea>";
				$ao_object[$ai_totrows][3]="<textarea name=txtrango".$ai_totrows."    cols=6 rows=3 id=txtrango".$ai_totrows."  class=sin-borde onKeyUp='javascript: ue_validarnumero(this);' onBlur='javascript: ue_chequear_numero(this); ue_multiplicar(this,txtvalor".$ai_totrows." , txtpesran".$ai_totrows."); sumar_odi(txtresodi,txtrescom,txttotal);'>".$li_rango." </textarea>";
				$ao_object[$ai_totrows][4]="<textarea name=txtpesran".$ai_totrows."    cols=6 rows=3 id=txtpesran".$ai_totrows."  class=sin-borde readonly  >".$li_pesran." </textarea>";				
				
				

				
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}
  
  
  

function uf_load_evaluacion_desempeno_competencia($as_nroeval, &$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_evaluacion_desempeno_competencia
		//	    Arguments: as_nroeval  // código de la evaluación de desempeño
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene las competencias de una evaluación de desempeño
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
				
		$ls_sql="SELECT * ".
				"  FROM srh_items_evaluacion, srh_competencias_evaluacion_desempeno ".
				"  WHERE srh_competencias_evaluacion_desempeno.codemp='".$this->ls_codemp."'".
				"  AND srh_competencias_evaluacion_desempeno.nroeval = '$as_nroeval' ".
				"  AND srh_competencias_evaluacion_desempeno.codite = srh_items_evaluacion.codite ".
				" ORDER BY srh_competencias_evaluacion_desempeno.codite ";
        
	

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->evaluacion_desempeno MÉTODO->uf_load_evaluacion_desempeno_competencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_codite=$row["codite"];
				$ls_denite= trim (htmlentities  ($row["denite"]));
				$li_peso=$row["peso"];
				$li_rangoc=$row["rango"];
				$li_pesranc= ($row["peso"] * $row["rango"]);
												
				$ao_object[$ai_totrows][1]="<textarea name=txtcodite".$ai_totrows."  cols=15 rows=3 id=txtcodite".$ai_totrows." class=sin-borde readonly>".$ls_codite."</textarea>";
				$ao_object[$ai_totrows][2]="<textarea name=txtdenite".$ai_totrows."    cols=35 rows=3 id=txtdenite".$ai_totrows."  class=sin-borde readonly>".$ls_denite." </textarea>";
				$ao_object[$ai_totrows][3]="<textarea name=txtpeso".$ai_totrows."    cols=6 rows=3 id=txtpeso".$ai_totrows."  class=sin-borde onKeyUp='javascript: ue_validarnumero(this);'  onBlur='ue_multiplicar(this,txtrangoc".$ai_totrows.",txtpesranc".$ai_totrows."); sumar_competencias(txtrescom,txtresodi,txttotal);'>".$li_peso." </textarea>";
				$ao_object[$ai_totrows][4]="<textarea name=txtrangoc".$ai_totrows."    cols=6 rows=3 id=txtrangoc".$ai_totrows."  class=sin-borde onKeyUp='javascript: ue_validarnumero(this);' onBlur='javascript: ue_chequear_numero(this); ue_multiplicar(this,txtpeso".$ai_totrows.",txtpesranc".$ai_totrows."); sumar_competencias(txtrescom,txtresodi,txttotal);'>".$li_rangoc." </textarea>";
				$ao_object[$ai_totrows][5]="<textarea name=txtpesranc".$ai_totrows."    cols=6 rows=3 id=txtpesranc".$ai_totrows."   class=sin-borde readonly>".$li_pesranc." </textarea>";

				
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	
function uf_srh_consultar_evaluacion_desempeno ($as_codper, $as_fecini,$as_fecfin,&$ai_totrows,&$ao_object,&$as_nomper)
{
		
		$lb_valido=true;
		
		$as_fecini=$this->io_funcion->uf_convertirdatetobd($as_fecini);
		$as_fecfin=$this->io_funcion->uf_convertirdatetobd($as_fecfin);
		
		$ls_sql= "SELECT * FROM srh_odi INNER JOIN srh_dt_odi ON (srh_odi.nroreg = srh_dt_odi.nroreg ) INNER JOIN srh_persona_odi ON (srh_odi.nroreg = srh_persona_odi.nroreg )INNER JOIN sno_personal ON (srh_persona_odi.codper = sno_personal.codper )".
				" WHERE srh_persona_odi.codper = '$as_codper' ".
				//" AND  srh_odi.fecha BETWEEN '".$as_fecini."' AND '".$as_fecfin."'".
				" AND srh_persona_odi.tipo = 'P' ".
				" ORDER BY srh_persona_odi.codper";

	 	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->evaluacion_desempeno MÉTODO->uf_srh_consultar_evaluacion_desempeno( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
					$ls_odi= trim (htmlentities($row["odi"]));
					$li_valor=$row["valor"];
					$ls_cododi= trim ($row["cododi"]);
					$as_apeper=trim (htmlentities  ($row["apeper"]));
					$as_nomper=trim (htmlentities ($row["nomper"]));
					if ($as_apeper!='0') {
					 $as_nomper= $as_nomper ." ". $as_apeper;
					}
					else{
 					 $as_nomper= $as_nomper ;
					}
					
					
					
					$ao_object[$ai_totrows][1]="<textarea name=txtodi".$ai_totrows."  cols=47 rows=3 id=txtodi".$ai_totrows." class=sin-borde readonly>".$ls_odi."</textarea> <input name=txtcododi".$ai_totrows." type=hidden class=sin-borde id=txtcododi".$ai_totrows."  readonly value=".$ls_cododi.">";
					$ao_object[$ai_totrows][2]="<textarea name=txtvalor".$ai_totrows."    cols=6 rows=3 id=txtvalor".$ai_totrows."  class=sin-borde readonly>".$li_valor." </textarea>";
					$ao_object[$ai_totrows][3]="<textarea name=txtrango".$ai_totrows."  cols=6 rows=3 id=txtrango".$ai_totrows." class=sin-borde onKeyUp='javascript: ue_validarnumero(this);' onBlur='javascript: ue_chequear_numero(this); ue_multiplicar(this,txtvalor".$ai_totrows." , txtpesran".$ai_totrows."); sumar_odi(txtresodi,txtrescom,txttotal);'></textarea>";
					$ao_object[$ai_totrows][4]="<textarea name=txtpesran".$ai_totrows."    cols=6 rows=3 id=txtpesran".$ai_totrows."  class=sin-borde readonly  > </textarea>";		
					
				}
		
		}
		else 
		  {
		     $this->io_msg->message("No se encontraron Registros con esos datos.");
			 			
			$ai_totrows=1;
			$ao_object[$ai_totrows][1]="<textarea name=txtodi".$ai_totrows."  cols=47 rows=3 id=txtodi".$ai_totrows." class=sin-borde readonly></textarea>";
			$ao_object[$ai_totrows][2]="<textarea name=txtvalor".$ai_totrows."    cols=6 rows=3 id=txtvalor".$ai_totrows."  class=sin-borde readonly></textarea>";
			$ao_object[$ai_totrows][3]="<textarea name=txtrango".$ai_totrows."  cols=6 rows=3 id=txtrango".$ai_totrows." class=sin-borde onKeyUp='javascript: ue_validarnumero(this);' onBlur='javascript: ue_chequear_numero(this); ue_multiplicar(this,txtvalor".$ai_totrows." , txtpesran".$ai_totrows."); sumar_odi(txtresodi,txtrescom,txttotal);'> </textarea>";
			$ao_object[$ai_totrows][4]="<textarea name=txtpesran".$ai_totrows."    cols=6 rows=3 id=txtpesran".$ai_totrows."  class=sin-borde readonly > </textarea>";	
			
		  }  
		return $lb_valido;
	
		}
      
		
	} // end function buscar_resultados_evaluacion_aspirante
		
	
//FUNCIONES PARA EL MANEJO DEL LAS PERSONAS INVOLUACRADAS EN LA EVALUACIÓN DE DESEMPEÑO

function uf_srh_eliminar_persona($as_nroeval)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_persona																																		
		//      Argumento: $as_nroeval	      // código de la evaluación de desempeño						
		//                 $aa_seguridad     //  arreglo de registro de seguridad                                  
		//	      Returns: Retorna un Booleano																					
		//    Description: Elimina a las personas involucradas en una evaluación de desempeño
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 27/12/2007							Fecha Última Modificación: 27/12/2007						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_persona_evaluacion_desempeno ".
	          "WHERE nroeval = '$as_nroeval'   AND codemp='".$this->ls_codemp."'";
			  
			  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->evaluacion_desempeno MÉTODO->uf_srh_eliminar_persona ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
		//      Argumento: $ao_evaluacion    // arreglo con los datos de los detalle de la evaluación de desempeño							
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta un trabajador en una evaluacion de desempeño en la tabla 
		//                 srh_persona_evaluacion_desempeno	        
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 27/12/2007							Fecha Última Modificación: 27/12/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 
		 
	  $ls_sql = "INSERT INTO srh_persona_evaluacion_desempeno (nroeval,codper, tipo, codemp) ".	  
	            "VALUES ('$ao_evaluacion->nroeval','$ao_evaluacion->codper','P','".$this->ls_codemp."')";
				
				

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó Persona de la evaluación de desempeño ".$ao_evaluacion->nroeval;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->evaluacion_desempeno MÉTODO->uf_srh_guardar_trabajador ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			
		}
		else
		{
				$lb_valido=true;
				
		}
		
	return $lb_guardo;
  }
  
  
 function uf_srh_guardar_evaluador ($ao_evaluacion, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_evaluador															     	
		//         access: public 													      
		//      Argumento: $ao_evaluacion    // arreglo con los datos de los detalle de la evaluacion de desempeño					
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta un evaluador en la tabla srh_persona_evaluacion_desempeno
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 27/12/2007							Fecha Última Modificación: 27/12/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 
		 
	  $ls_sql = "INSERT INTO srh_persona_evaluacion_desempeno (nroeval,codper, tipo, codemp) ".	  
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
			$this->io_msg->message("CLASE->evaluacion_desempeno MÉTODO->guardar_perosonas_evaluacion_desempeno ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		
		}
		else
		{
				$lb_valido=true;
			
		}
		
	return $lb_guardo;
  }
  
 
 
 function uf_srh_guardar_supervisor ($ao_evaluacion, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_supervisor															     	
		//         access: public 														  								  
		//      Argumento: $ao_evaluacion    // arreglo con los datos de los detalle de la evaluacion de desempeño					
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta un supervisor en la tabla srh_persona_evaluacion_desempeno
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 27/12/2007							Fecha Última Modificación: 27/12/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 
		 
	  $ls_sql = "INSERT INTO srh_persona_evaluacion_desempeno (nroeval,codper, tipo, codemp) ".	  
	            "VALUES ('$ao_evaluacion->nroeval','$ao_evaluacion->codsup','S','".$this->ls_codemp."')";
				
				

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
			$this->io_msg->message("CLASE->evaluacion_desempeno MÉTODO->uf_srh_guardar_supervisor ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			
		}
		else
		{
				$lb_valido=true;
				
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
			$this->io_msg->message("CLASE->evaluacion_desempeno MÉTODO->uf_srh_consultar_rango_actuacion( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}
		else
		{
		 $num=$this->io_sql->num_rows($rs_data);
		 if ($num!=0) {
		  while ($row=$this->io_sql->fetch_row($rs_data)) 
		  {   					
		    $as_ranact = htmlentities   ($row["dendetesc"]);
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
					$ls_denite=trim (htmlentities ($row["denite"]));
				
										
					$ao_object[$ai_totrows][1]="<textarea name=txtcodite".$ai_totrows."  cols=15 rows=3 id=txtcodite".$ai_totrows." class=sin-borde readonly>".$ls_codite."</textarea>";
					$ao_object[$ai_totrows][2]="<textarea name=txtdenite".$ai_totrows."    cols=35 rows=3 id=txtdenite".$ai_totrows."  class=sin-borde readonly>".$ls_denite." </textarea>";
					$ao_object[$ai_totrows][3]="<textarea name=txtpeso".$ai_totrows."    cols=6 rows=3 id=txtpeso".$ai_totrows."  class=sin-borde onKeyUp='javascript: ue_validarnumero(this);'  onBlur='ue_multiplicar(this,txtrangoc".$ai_totrows.",txtpesranc".$ai_totrows."); sumar_competencias(txtrescom,txtresodi,txttotal);'> </textarea>";
					$ao_object[$ai_totrows][4]="<textarea name=txtrangoc".$ai_totrows."    cols=6 rows=3 id=txtrangoc".$ai_totrows."  class=sin-borde onKeyUp='javascript: ue_validarnumero(this);' onBlur='javascript: ue_chequear_numero(this); ue_multiplicar(this,txtpeso".$ai_totrows.",txtpesranc".$ai_totrows."); sumar_competencias(txtrescom,txtresodi,txttotal);'> </textarea>";
					$ao_object[$ai_totrows][5]="<textarea name=txtpesranc".$ai_totrows."    cols=6 rows=3 id=txtpesranc".$ai_totrows."   class=sin-borde readonly> </textarea>";
					  
					}
		
		}
		else 
		  {
		     $this->io_msg->message("No se encontraron Items de Evaluación.");
	 		$ai_totrows=1;	
			$ao_object[$ai_totrows][1]="<textarea name=txtcodite".$ai_totrows."  cols=15 rows=3 id=txtcodite".$ai_totrows." class=sin-borde readonly></textarea>";
					$ao_object[$ai_totrows][2]="<textarea name=txtdenite".$ai_totrows."    cols=35 rows=3 id=txtdenite".$ai_totrows."  class=sin-borde readonly></textarea>";
					$ao_object[$ai_totrows][3]="<textarea name=txtpeso".$ai_totrows."    cols=6 rows=3 id=txtpeso".$ai_totrows."  class=sin-borde onKeyUp='javascript: ue_validarnumero(this);'  onBlur='ue_multiplicar(this,txtrangoc".$ai_totrows.",txtpesranc".$ai_totrows."); sumar_competencias(txtrescom,txtresodi,txttotal);'></textarea>";
					$ao_object[$ai_totrows][4]="<textarea name=txtrangoc".$ai_totrows."    cols=6 rows=3 id=txtrangoc".$ai_totrows."  class=sin-borde onKeyUp='javascript: ue_validarnumero(this);' onBlur='javascript: ue_chequear_numero(this); ue_multiplicar(this,txtpeso".$ai_totrows.",txtpesranc".$ai_totrows."); sumar_competencias(txtrescom,txtresodi,txttotal);'> </textarea>";
					$ao_object[$ai_totrows][5]="<textarea name=txtpesranc".$ai_totrows."    cols=6 rows=3 id=txtpesranc".$ai_totrows."   class=sin-borde readonly> </textarea>";
			
			
		  }  
		return $lb_valido;
	
		}
      
		
	} // end uf_srh_consultar_items
	
	

function suma_fechas($fecha,$ndias)
            

{

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: suma_fechas
		//	    Arguments: $fecha  // fecha inicial
		//				   $ndias  // número de días a sumar a la fecha inicial
		//	      Returns: Retorna la variable $nuevafecha con el nuevo valor de la fecha al sumar el número de días pasado como 
		//                 parámetro
		//	  Description: Funcion que suma un valor de días enteros a una fecha (en formato dd/mm/aaaa)
		//	   Creado Por: Maria Beatriz Unda	
		// Fecha Creación: 11/03/2008							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            

      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
            

              list($dia,$mes,$año)=split("/", $fecha);
            

      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
            

              list($dia,$mes,$año)=split("-",$fecha);
        $nueva = mktime(0,0,0, $mes,$dia,$año) + $ndias * 24 * 60 * 60;
        $nuevafecha=date("d/m/Y",$nueva);
            

      return ($nuevafecha);  
            

}



function uf_srh_chequear_permisos ($as_nroreg, $as_fecini,$as_fecfin) {


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_chequear_permisos
		//	    Arguments: $as_nroreg   // número de registro de ODIS
		//				   $as_fecini  //  fecha inicial del período de revisión
		//				   $as_fecfin  //  fecha final del período de revisión
		//	      Returns: Retorna la variable $lb_valido siendo TRUE si el total de días de permisos, vacaciones y reposos no 
		//                 excede los 120 días (2 meses) y FALSE en caso contrario
		//	  Description: Chequea que las cantidad de permisos, reposos y vacaciones de una persona a quién se le realizará una 
		//                 revisión de ODI no excede los 120 días dentro del período de revisión correspondiente.
		//      CreadoPor: María Beatriz Unda
		// Fecha Creación: 11/03/2008							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  
  $lb_valido=true; 

  $as_fecini=$this->io_funcion->uf_convertirdatetobd($as_fecini);
  $as_fecfin=$this->io_funcion->uf_convertirdatetobd($as_fecfin);
  
  $ls_sql= "SELECT  srh_persona_odi.codper, sno_permiso.feciniper, sno_permiso.fecfinper, sno_permiso.numdiaper, sno_permiso.codper, srh_enfermedades.codper, srh_enfermedades.fecini, srh_enfermedades.diarepenf, srh_accidentes.codper, srh_accidentes.fecacc, srh_accidentes.reposo, sno_vacacpersonal.codper, sno_vacacpersonal.dianorvac, sno_vacacpersonal.fecdisvac, sno_vacacpersonal.fecreivac FROM srh_persona_odi LEFT JOIN sno_permiso ON (sno_permiso.codper =  srh_persona_odi.codper) LEFT JOIN srh_enfermedades ON (srh_enfermedades.codper = srh_persona_odi.codper) LEFT JOIN  srh_accidentes ON (srh_accidentes.codper = srh_persona_odi.codper) LEFT JOIN  sno_vacacpersonal ON (sno_vacacpersonal.codper = srh_persona_odi.codper) ".
				" WHERE srh_persona_odi.nroreg = '$as_nroreg' ";	
				
						
 $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revisiones_odi MÉTODO->uf_srh_chequear_permisos( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}
		else
		{ 
		   $total_dias=0;
		   
		   //convertimos la fechas de inicio y fin que entran por parámetro a un formato válido para poder compararlas
		   $as_fecini=$this->io_funcion->uf_formatovalidofecha($as_fecini);
		   $as_fecini=$this->io_funcion->uf_convertirfecmostrar($as_fecini);		   
		   $as_fecifin=$this->io_funcion->uf_formatovalidofecha($as_fecfin);
		   $as_fecfin=$this->io_funcion->uf_convertirfecmostrar($as_fecfin);
		   
		   while ($row=$this->io_sql->fetch_row($rs_data)) {
		  
//Para calcualar el total de días por Permisos		   
		    if ($row["feciniper"]!="null" && ($row["feciniper"]!="")) {
			
			   $feciniper=$this->io_funcion->uf_formatovalidofecha(trim ($row["feciniper"]));
			   $feciniper=$this->io_funcion->uf_convertirfecmostrar(trim ($feciniper));
			   $fecfinper=$this->io_funcion->uf_formatovalidofecha(trim ($row["fecfinper"]));
			   $fecfinper=$this->io_funcion->uf_convertirfecmostrar(trim ($fecfinper));
			   			
			   if (($this->io_fecha->uf_comparar_fecha($feciniper,$as_fecini))  && ($this->io_fecha->uf_comparar_fecha($as_fecfin,$fecfinper))){
			      
					$lb_valido=false; 
				  }
				 elseif (($this->io_fecha->uf_comparar_fecha($feciniper,$as_fecini)) && ($this->io_fecha->uf_comparar_fecha($fecfinper,$as_fecfin))) {
				       $dias= $this->io_fecha->uf_restar_fechas($as_fecini,$fecfinper);
					   $total_dias=$total_dias + $dias;
				    }
				 
				 elseif (($this->io_fecha->uf_comparar_fecha($as_fecini,$feciniper)) && ($this->io_fecha->uf_comparar_fecha($fecfinper,$as_fecfin)))
				    {  $total_dias=$total_dias + $row["numdiaper"];
					
					}
				 
				 elseif (($this->io_fecha->uf_comparar_fecha($as_fecini,$feciniper)) && ($this->io_fecha->uf_comparar_fecha($fecfinper,$as_fecfin)))  {
				       $dias= $this->io_fecha->uf_restar_fechas($feciniper,$as_fecfin);
					   $total_dias=$total_dias + $dias;
				 }
				 
 			 
			}
		 
//Para calcualar el total de días por enfermedad		 
		if (($row["fecini"]!="") && ($row["fecini"]!="null") && ($lb_valido!=false)) {
		     $fecfinenf="";
			 $fecinienf="";
			 $fecinienf=$this->io_funcion->uf_formatovalidofecha(trim ($row["fecini"]));
			 $fecinienf=$this->io_funcion->uf_convertirfecmostrar(trim ($fecinienf));
			 $fecfinenf =$this->suma_fechas($fecinienf,$row["diarepenf"]);
			 
			  if (($this->io_fecha->uf_comparar_fecha($fecinienf,$as_fecini))  && ($this->io_fecha->uf_comparar_fecha($as_fecfin,$fecinienf))){
			  $lb_valido=false;
				  }
		     elseif  (($this->io_fecha->uf_comparar_fecha($fecinienf,$as_fecini))  && ($this->io_fecha->uf_comparar_fecha($fecfinenf ,$as_fecfin)))
			 {
			    $dias= $this->io_fecha->uf_restar_fechas($as_fecini,$fecfinenf);
				$total_dias=$total_dias + $dias;
				
			 }
				 
			 elseif (($this->io_fecha->uf_comparar_fecha( $as_fecini,$fecinienf))  && ($this->io_fecha->uf_comparar_fecha($fecfinenf ,$as_fecfin)))
				    {$total_dias=$total_dias + $row["diarepenf"]; 
					}
				 
			 elseif (($this->io_fecha->uf_comparar_fecha($as_fecini,$fecinienf))  && ($this->io_fecha->uf_comparar_fecha($as_fecfin ,$fecfinenf ))){
			      $dias= $this->io_fecha->uf_restar_fechas($fecinienf,$as_fecfin);
				  $total_dias=$total_dias + $dias;			
			 }
		 }
		 
//Para calcualar el total de días por Accidentes
     	
		if (($row["fecacc"]!="null")&& ($row["fecacc"]!="") && ($lb_valido!=false)) {
		      $fecfinacc="";
			  $feciniacc="";
			  $feciniacc=$this->io_funcion->uf_formatovalidofecha(trim ($row["fecacc"]));
			  $feciniacc=$this->io_funcion->uf_convertirfecmostrar(trim ($feciniacc));
			  $fecfinacc=$this->suma_fechas($feciniacc,$row["reposo"]);			 
					 
		    if  (($this->io_fecha->uf_comparar_fecha($feciniacc,$as_fecini))  && ($this->io_fecha->uf_comparar_fecha($as_fecfin,$fecfinacc))){
			   $lb_valido=false;
				  }
		     elseif (($this->io_fecha->uf_comparar_fecha($feciniacc,$as_fecini))  && ($this->io_fecha->uf_comparar_fecha($fecfinacc,$as_fecfin))) {
			    $dias= $this->io_fecha->uf_restar_fechas($as_fecini,$fecfinacc);
				$total_dias=$total_dias + $dias;
			 }
				 
			 elseif (($this->io_fecha->uf_comparar_fecha($as_fecini,$feciniacc))  && ($this->io_fecha->uf_comparar_fecha($fecfinacc,$as_fecfin)))
			   {$total_dias=$total_dias + $row["reposo"];}
				 
			 elseif (($this->io_fecha->uf_comparar_fecha($as_fecini,$feciniacc))  && ($this->io_fecha->uf_comparar_fecha($as_fecfin, $fecfinacc)))
			 {
			    $dias= $this->io_fecha->uf_restar_fechas($feciniacc,$as_fecfin);
				$total_dias=$total_dias + $dias;	 
			 }
		 
		 }
		 
//Para calcualar el total de días por Vacaciones
		 
		 if (($row["fecdisvac"]!="null") && ($row["fecdisvac"]!="") && ($lb_valido!=false)) {
		 
			   $fecinivac=$this->io_funcion->uf_formatovalidofecha(trim ($row["fecdisvac"]));
			   $fecinivac=$this->io_funcion->uf_convertirfecmostrar(trim ($fecinivac));
			   $fecfinvac=$this->io_funcion->uf_formatovalidofecha(trim ($row["fecreivac"]));
			   $fecfinvac=$this->io_funcion->uf_convertirfecmostrar(trim ($fecfinvac));		  
		 
		    if (($this->io_fecha->uf_comparar_fecha($fecinivac,$as_fecini))  && ($this->io_fecha->uf_comparar_fecha($as_fecfin,$fecfinvac))) {
			  $lb_valido=false; }
			elseif (($this->io_fecha->uf_comparar_fecha($fecinivac,$as_fecini))  && ($this->io_fecha->uf_comparar_fecha($fecfinvac,$as_fecfin))){
			       $dias= $this->io_fecha->uf_restar_fechas($as_fecini,$fecfinvac);
			       $total_dias=$total_dias + $dias;
			  }
				 
			 elseif (($this->io_fecha->uf_comparar_fecha($as_fecini,$fecinivac))  && ($this->io_fecha->uf_comparar_fecha($fecfinvac,$as_fecfin)))
				    {$total_dias=$total_dias + $row["dianorvac"];}
				 
			 elseif (($this->io_fecha->uf_comparar_fecha($as_fecini,$fecinivac))  && ($this->io_fecha->uf_comparar_fecha($as_fecfin,$fecfinvac)))
			 {
			       $dias= $this->io_fecha->uf_restar_fechas($fecinivac,$as_fecfin);
				   $total_dias=$total_dias + $dias;
			 }
		
       }
	   }
	   
	  
	   if (($total_dias < 120)  && ($lb_valido!=false))  {
	           $lb_valido= true;
	   }
	   else { $lb_valido=false;}
	   
	  
	 return $lb_valido;
  }
}



}// end   class sigesp_srh_c_evaluacion_desempeno
?>
