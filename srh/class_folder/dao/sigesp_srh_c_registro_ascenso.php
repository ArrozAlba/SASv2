<?php

class sigesp_srh_c_registro_ascenso
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_registro_ascenso($path)
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
		//         Access: public (sigesp_srh_p_registro_ascenso)
		//      Argumento: 
		//	      Returns: Retorna el nuevo número de un registro de ascenso
		//    Description: Funcion que genera un registro de ascenso
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
		$ls_sql = "SELECT MAX(nroreg) AS numero FROM srh_registro_ascenso ";
		$lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
		if ($lb_hay)
		$ls_nroreg = $la_datos["numero"][0]+1;
		$ls_nroreg = str_pad ($ls_nroreg,10,"0","left");	 		 
		return $ls_nroreg;
  } 
  	
	
  function uf_srh_getregistro_ascenso($ps_nroreg,&$pa_datos="")
  {  
  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getregistro_ascenso																		
		//      Argumento: $ps_nroreg    // numero de la postulación 															//
		//                 $pa_datos    //  arreglos donde  se cargaran lo datos de la consulta									//
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que realiza una busqueda de una postulaciòn de ascenso en la tabla srh_registro_ascenso  	
		//	   Creado Por: Maria Beatriz Unda																				    //
		//   Fecha Creación: 21/12/2007							Fecha Última Modificación: 21/12/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  
  
    $ls_sql = "SELECT * FROM srh_registro_ascenso ".
	          "WHERE nroreg = '$ps_nroreg" ;
    $lb_hay = $this->seleccionar($ls_sql, $pa_datos);
    return $lb_hay;
  }
  
  
  
 
  
function uf_srh_guardarregistro_ascenso ($po_postulacion,$ps_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarregistro_ascenso																		
		//         access: public (sigesp_srh_registro_ascenso)														
		//      Argumento: $po_postulacion    // arreglo con los datos de la postulación										  	
		//                 $ps_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              //
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta o modifica una amonestación en la tabla srh_registro_ascenso     		
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 21/12/2007							Fecha Última Modificación: 21/12/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$as_nroreg=$po_postulacion->nroreg;
	
  	if ($ps_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	 $po_postulacion->fecha=$this->io_funcion->uf_convertirdatetobd($po_postulacion->fecha);

	 $ls_sql = "UPDATE srh_registro_ascenso SET ".
	            "fecreg = '$po_postulacion->fecha', ".
	            "codcon = '$po_postulacion->codcon', ".
	            "observacion = '$po_postulacion->obs', ".
	            "opinion = '$po_postulacion->opinion' ".
	            "WHERE nroreg= '$po_postulacion->nroreg' AND codemp='".$this->ls_codemp."'" ;
		
				
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó la postulación para ascenso ".$as_nroreg;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	  $po_postulacion->fecha=$this->io_funcion->uf_convertirdatetobd($po_postulacion->fecha);
	
	  $ls_sql = "INSERT INTO srh_registro_ascenso (nroreg, fecreg, codcon, observacion, opinion, codemp) ".	  
	            "VALUES ('$po_postulacion->nroreg','$po_postulacion->fecha','$po_postulacion->codcon','$po_postulacion->obs','$po_postulacion->opinion', '".$this->ls_codemp."')";
				

				
	
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la postulación para ascenso ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->ascenso MÉTODO->guardarregistro_ascenso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				$this->io_sql->commit();
		}
		
		
	//Guardamos las Personas involucradas en la postulación (Supervisor y Trabajador)
	$lb_guardo = $this->guardarPersonas_registro_ascenso ($po_postulacion, $aa_seguridad);
		
	return $lb_guardo;
  }
	
	
	
function guardarPersonas_registro_ascenso ($po_postulacion, $aa_seguridad)
  {
   
	$lb_guardo = true;
	$as_nro = $po_postulacion->nroreg;
	  $lb_guardo1 = $this-> uf_srh_eliminar_persona($as_nro);
	  if ($lb_guardo1===true) {
		
	  }
     $lb_guardo = $this-> uf_srh_guardar_supervisor ($po_postulacion, $aa_seguridad);
  	 $lb_guardo = $this-> uf_srh_guardar_trabajador ($po_postulacion, $aa_seguridad); 
	
	return $lb_guardo;    
  }

	
	
	
function uf_srh_eliminarregistro_ascenso($ps_nroreg, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarregistro_ascenso																			//
		//        access:  public (sigesp_srh_registro_ascenso)														
		//      Argumento: $ps_nroreg        // numero de la postulación												        //
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que elimina una postulación para ascenso en la tabla srh_registro_ascenso		
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 21/12/2007							Fecha Última Modificación: 21/12/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_registro_ascenso ".
	          "WHERE nroreg = '$ps_nroreg'   AND codemp='".$this->ls_codemp."'";
			  
			  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->ascenso MÉTODO->eliminarregistro_ascenso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la postulación para ascenso ".$ps_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}

	
	//SE BORRAN LOS DETALLES DE la postulación
	$lb_guardo = $this-> uf_srh_eliminar_persona($ps_nroreg);
	  if ($lb_guardo===true) {
		
	  }
	  	return $lb_borro;
  }
	

	
function uf_srh_buscar_registro_ascenso($as_nroreg,$as_codper,$as_codcon)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_registro_ascenso																		
		//         access: public (sigesp_srh_registro_ascenso)													
		//      Argumento: $as_nroreg   //  numero de la postulación									                      
		//                 $as_codper   //  codula del trabajador                                                            
		//                 $as_apeper   //  apellido del trabajador                                                          
		//                 $as_nomper   //  nombre del trabajador                                                            
		//                 $as_codcon   //  codigo del concurso                                                
		//	      Returns: Retorna un XML  																						
		//    Description: Funcion busca una postulación de ascenso en la tabla srh_registro_ascenso y crea un XML para mostrar	
		//                  los datos en el catalogo                                                                            
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 21/12/2007							Fecha Última Modificación: 21/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		
	    $ls_nroregdestino="txtnroreg";
		$ls_fechadestino="txtfecreg";
		$ls_codcondestino="txtcodcon";
		$ls_descondestino="txtdescon";
		$ls_reqmindestino="txtreqmin";
		$ls_descardestino="txtdescar";
		$ls_nomperdestino="txtnomper";
		$ls_codperdestino="txtcodper";
		$ls_caractdestino="txtcaract";
		$ls_fecingdestino="txtfecing";
		$ls_obsdestino="txtobs";
		$ls_codsupdestino="txtcodsup";
		$ls_nomsupdestino="txtnomsup";
		$ls_codcarsupdestino="txtcodcarsup";
		$ls_opidestino="txtopi";
	
		$lb_valido=true;			   
       
		$ls_sql=" SELECT distinct(e.codper),a.*, d.descon, d.codcon, b.tipo, c.apeper, c.nomper, c.codper, c.fecingper,".
       			" f.denasicar, g.descar, ".
        		" (Select sno_cargo.descar from sno_cargo where sno_cargo.codcar=d.codcar and sno_cargo.codnom=d.codnom)  as cargoascenso1, ".
				" (Select sno_asignacioncargo.denasicar from sno_asignacioncargo where sno_asignacioncargo.codasicar=d.codcar and sno_asignacioncargo.codnom=d.codnom)  as cargoascenso2 ".
				" FROM srh_registro_ascenso a ".
				" JOIN srh_persona_registro_ascenso b ON (b.nroreg = a.nroreg)  ".
				" JOIN sno_personal c ON (c.codper = b.codper)  ".
				" JOIN srh_concurso d ON (d.codcon = a.codcon)  ".
				" JOIN sno_personalnomina e on  (c.codper=e.codper)   ".
				" LEFT JOIN sno_asignacioncargo f on  (e.codasicar=f.codasicar and e.codnom=f.codnom)  ".
				" LEFT JOIN sno_cargo g on  (e.codcar=g.codcar and e.codnom=g.codnom)  ".
			    " JOIN sno_nomina ON (sno_nomina.codnom = e.codnom AND sno_nomina.espnom='0')  ".
				" WHERE a.nroreg like '$as_nroreg' ".    
				" AND a.codcon like '$as_codcon'   ".  
				" ORDER BY a.nroreg";			
			   
	    $rs_data=$this->io_sql->select($ls_sql);
       
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->registro_ascenso MÉTODO->uf_srh_buscar_registro_ascenso( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		      $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);	
			 $ls_control=0;		
			  $ls_apesup ="";
			  $ls_nomsup="";
			  $ls_codsup="";
			  $ls_carsup="";
			  $ls_codper="";
			  $ls_apeper="";
   			  $ls_nomper="";
			  $ls_reqmin="";

			  $ls_codcarsup="";
			  $ls_fecing="";
			  $ls_caract="";			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{     
					$ls_nroreg=$row["nroreg"];
					$ls_fecha=$this->io_funcion->uf_formatovalidofecha($row["fecreg"]);
				    $ls_fecha=$this->io_funcion->uf_convertirfecmostrar($ls_fecha);
					$ls_descon=trim (htmlentities ($row["descon"]));
					$ls_codcon=$row["codcon"];
					
					$ls_descar1 = trim (htmlentities ($row["cargoascenso1"]));
					$ls_descar2 = trim (htmlentities ($row["cargoascenso2"]));
					
					
					if ($ls_descar2=="")
					{
						$ls_descar = $ls_descar1;
					}
					if ($ls_descar1=="")
					{
						$ls_descar = $ls_descar2;
					}	
					
					
					
										
					$ls_obs= trim (htmlentities  ($row["observacion"]));
					$ls_opi= trim (htmlentities  ($row["opinion"]));
					$ls_tipo=$row["tipo"];					
					if ($ls_tipo=="S") 
					{ 
					  $ls_apesup = trim (htmlentities ($row["apeper"]));
				      $ls_nomsup= trim (htmlentities ($row["nomper"]));
				      $ls_codsup=$row["codper"];
					  
					 $ls_cargo1= trim (htmlentities ($row["denasicar"]));
					 $ls_cargo2= trim (htmlentities ($row["descar"]));
					
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
					else 
					{
					  $ls_codper=$row["codper"];
					  $ls_apeper =trim (htmlentities ($row["apeper"]));
   					  $ls_nomper=trim (htmlentities ($row["nomper"]));
					  
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
					  $ls_fecing=$row["fecingper"];
					  $ls_fecing=$this->io_funcion->uf_convertirfecmostrar($ls_fecing);
					  $ls_reqmin="";		 
					  $ls_control=$ls_control + 1;
					  
					}					
			if ($ls_control=="2")  {
			
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['nroreg']);
					$cell = $row_->appendChild($dom->createElement('cell'));    
					
					$cell->appendChild($dom->createTextNode($row['nroreg']."^javascript:aceptar(\"$ls_nroreg\", \"$ls_fecha\",\"$ls_codcon\", \"$ls_descon\", \"$ls_caract\",\"$ls_reqmin\", \"$ls_codper\", \"$ls_nomper\", \"$ls_apeper\", \"$ls_caract\", \"$ls_fecing\", \"$ls_obs\", \"$ls_codsup\", \"$ls_nomsup\", \"$ls_apesup\", \"$ls_codcarsup\", \"$ls_opi\", \"$ls_nroregdestino\", \"$ls_fechadestino\", \"$ls_codcondestino\", \"$ls_descondestino\", \"$ls_caractdestino\", \"$ls_reqmindestino\", \"$ls_codperdestino\", \"$ls_nomperdestino\", \"$ls_caractdestino\", \"$ls_fecingdestino\", \"$ls_obsdestino\", \"$ls_codsupdestino\", \"$ls_nomsupdestino\", \"$ls_codcarsupdestino\", \"$ls_opidestino\", \"$ls_descar\", \"$ls_descardestino\");^_self"));
					
								
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($row["codcon"]));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_descon));												
					$row_->appendChild($cell);
					
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_codper));												
					$row_->appendChild($cell);
					
					if ($ls_apeper!='0') 
					{
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
					$ls_control=0;
					}
					
				
			
			}
			return $dom->saveXML();
		
			
			
		
		}
      
		
	} // end function buscar_registro_ascenso
	

//FUNCIONES PARA EL MANEJO DEL LAS APERSONAS INVOLUACRADAS EN LA POSTULACIÓN PARA ASCENSO

function uf_srh_eliminar_persona($as_nroreg)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_persona																			
		//        access:  public (sigesp_srh_registro_ascenso)															
		//      Argumento: $as_nroreg	    // número de  la postulación								       
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina una persona involucrada en postulación de ascenso en la tabla srh_registro_ascenso	
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 21/12/2007							Fecha Última Modificación: 21/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_persona_registro_ascenso ".
	          "WHERE nroreg = '$as_nroreg'   AND codemp='".$this->ls_codemp."'";
			  
			  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->ascenso MÉTODO->eliminar_persona ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	



function uf_srh_guardar_trabajador ($po_postulacion, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_dt_registro_ascenso																     	
		//         access: public (sigesp_dt_srh_registro_ascenso)															
		//      Argumento: $po_postulacion    // arreglo con los datos de los detalle de la amonestaciopn							
		//                 $ps_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              //
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta un trabajador en la tabla srh_dt_registro_ascenso   	        
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 21/12/2007							Fecha Última Modificación: 21/12/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 
		 
	  $ls_sql = "INSERT INTO srh_persona_registro_ascenso (nroreg,codper, tipo, codemp) ".	  
	            "VALUES ('$po_postulacion->nroreg','$po_postulacion->codper', 'P','".$this->ls_codemp."')";
				
				

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó Persona de la postulación ".$po_postulacion->nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->ascenso MÉTODO->uf_srh_guardar_trabajador ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
  
  
 function uf_srh_guardar_supervisor ($po_postulacion, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_dt_registro_ascenso															     	//
		//         access: public (sigesp_dt_srh_registro_ascenso)														        //
		//      Argumento: $po_postulacion    // arreglo con los datos de los detalle de la postulación							//
		//                 $ps_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              //
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta un supervisor una postulación de ascenso en la tabla srh_dt_registro_ascenso    
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 21/12/2007							Fecha Última Modificación: 21/12/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 
		 
	  $ls_sql = "INSERT INTO srh_persona_registro_ascenso (nroreg,codper, tipo, codemp) ".	  
	            "VALUES ('$po_postulacion->nroreg','$po_postulacion->codsup','S','".$this->ls_codemp."')";
				
				

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó Persona de la postulación ".$po_postulacion->nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->ascenso MÉTODO->uf_srh_guardar_dt_registro_ascenso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
	
  
  
  

}// end   class sigesp_srh_c_registro_ascenso
?>