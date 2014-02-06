<?php

class sigesp_srh_c_llamada_atencion
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_llamada_atencion($path)
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
			//         Access: public (sigesp_srh_p_llamada_atencion)
			//      Argumento: 
			//	      Returns: Retorna el nuevo número de una llamada de atención 
			//    Description: Funcion que genera un número de una llamada de atención
			//	   Creado Por: Maria Beatriz Unda
			// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  
		$ls_sql = "SELECT MAX(nrollam) AS numero FROM srh_llamada_atencion ";
		$lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
		if ($lb_hay)
		$ls_nrollam = $la_datos["numero"][0]+1;
		$ls_nrollam = str_pad ($ls_nrollam,10,"0","left");
		return $ls_nrollam;
	  }

	
  	
  function uf_srh_getllamada_atencion($as_nrollam,&$pa_datos="")
  {  
  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getllamada_atencion																			//
		//         access: public (sigesp_srh_llamada_atencion)														            //
		//      Argumento: $as_nrollam    // numero de la llamada de atencion													//
		//                 $pa_datos    //  arreglos donde  se cargaran lo datos de la consulta									//
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que realiza una busqueda de una llamada de atencion en la tabla srh_llamada_atencion         //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 08/11/2007							Fecha Última Modificación: 08/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT * FROM srh_llamada_atencion ".
	          "WHERE nrollam = '$as_nrollam'";
    $lb_hay = $this->seleccionar($ls_sql, $pa_datos);
    return $lb_hay;
  }
  
  
  
 
  
function uf_srh_guardarllamada_atencion ($ao_llamada,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarllamada_atencion																		//
		//         access: public (sigesp_srh_llamada_atencion)														            //
		//      Argumento: $ao_llamada    // arreglo con los datos de la llamada de atencion								    //
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              //
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta o modifica una llamada de atencion en la tabla srh_llamada_atencion              //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 08/11/2007							Fecha Última Modificación: 08/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$as_nrollam=$ao_llamada->nrollam;
	
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	 $ao_llamada->fecllam=$this->io_funcion->uf_convertirdatetobd($ao_llamada->fecllam);
	 
	 
	  $ls_sql = "UPDATE srh_llamada_atencion SET ".
	            "codtrab = '$ao_llamada->codtrab', ".
	            "fecllam = '$ao_llamada->fecllam', ".
	            "causa = '$ao_llamada->causa', ".
				"tipo = '$ao_llamada->tipo', ".
	            "unidad = '$ao_llamada->coduniad', ".
	            "descripcion = '$ao_llamada->descripcion' ".
	            "WHERE nrollam= '$ao_llamada->nrollam' AND codemp='".$this->ls_codemp."'" ;
		
				
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó la Amontestacion / llamada de atencion ".$as_nrollam;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	  $ao_llamada->fecllam=$this->io_funcion->uf_convertirdatetobd($ao_llamada->fecllam);

	
	  $ls_sql = "INSERT INTO srh_llamada_atencion (nrollam, codtrab, fecllam, unidad, causa, tipo, descripcion, codemp) ".	  
	            "VALUES ('$ao_llamada->nrollam','$ao_llamada->codtrab','$ao_llamada->fecllam','$ao_llamada->coduniad','$ao_llamada->causa','$ao_llamada->tipo','$ao_llamada->descripcion', '".$this->ls_codemp."')";
	

	
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la Amontestacion / llamada de atencion ".$as_nrollam;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->llamada_atencion MÉTODO->uf_srh_guardarllamada_atencion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				$lb_guardo=false;
				
				if ($lb_valido)
				{
				  //Guardamos las causas de la llamada de atenioción
				 $lb_guardo = $this->guardarDetalles_Llamada($ao_llamada, $aa_seguridad);
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
	
	
	
function guardarDetalles_Llamada ($ao_llamada, $aa_seguridad)
  {
    //Borramos los registros anteriores 
	$this-> uf_srh_eliminar_dt_llamada_atencion($ao_llamada->nrollam, $aa_seguridad);
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_causa = 0;
	while (($li_causa < count($ao_llamada->causas)) &&
	       ($lb_guardo))
	{
	  $lb_guardo = $this-> uf_srh_guardar_dt_llamada_atencion($ao_llamada->causas[$li_causa], $aa_seguridad);
	  $li_causa++;
	}
	
	return $lb_guardo;    
  }

	
	
	
function uf_srh_eliminarllamada_atencion($as_nrollam, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarllamada_atencion																		//
		//        access:  public (sigesp_srh_llamada_atencion)														            //
		//      Argumento: $as_nrollam        // numero de la llamada de atencion										        //
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que elimina una llamada de atención en la tabla srh_llamada_atencion                         //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 08/11/2007							Fecha Última Modificación: 08/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();
	$this-> uf_srh_eliminar_dt_llamada_atencion($as_nrollam, $aa_seguridad);
    $ls_sql = "DELETE FROM srh_llamada_atencion ".
	          "WHERE nrollam = '$as_nrollam'   AND codemp='".$this->ls_codemp."'";
			  
			  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->llamada_atencion MÉTODO->uf_srh_eliminarllamada_atencion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la llamada de atencion ".$as_nrollam;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				
					$this->io_sql->commit();
			}
	
	return $lb_borro;
  }
	
	
	
function uf_srh_buscar_llamada_atencion($as_nrollam,$as_codtrab,$as_apetrab,$as_nomtrab)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_llamada_atencion																		//
		//         access: public (sigesp_srh_llamada_atencion)												                    //
		//      Argumento: $as_nrollam   //  numero de la llamada de atencion							                        //
		//                 $as_codtrab   //  codigo del trabajador                                                              //
		//                 $as_apetrab   //  apellido del trabajador                                                            //
		//                 $as_nomtrab   //  nombre del trabajador                                                              //
		//                 $as_coduniad   //  codigo de la unidad administrativa                                                //
		//	      Returns: Retorna un XML  																						//
		//    Description: Funcion busca una llamada de atención en la tabla srh_llamada_atencion y crea un XML para mostrar    //
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 08/11/2007							Fecha Última Modificación: 08/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
	
	    $ls_nrodestino="txtnrollam";
		$ls_coddestino="txtcodper";
		$ls_fecllamdestino="txtfecllam";
		$ls_nomdestino="txtnomper";
		$ls_cardestino="txtcodcarper";
		$ls_coduniaddestino="txtuniad";
    	$ls_desdestino="txtdes";
		$ls_causadestino="cmbcausa";
		$ls_tipodestino="cmbtipo";
		
		
		
		$lb_valido=true;
		
		
				
		$ls_sql= " SELECT *  FROM srh_llamada_atencion INNER JOIN sno_personal ON (sno_personal.codper = srh_llamada_atencion.codtrab) ".
		         " JOIN sno_personalnomina  ON  (srh_llamada_atencion.codtrab=sno_personalnomina.codper)   ".
				 " LEFT JOIN sno_asignacioncargo ON  (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar and sno_personalnomina.codnom=sno_asignacioncargo.codnom) ".
				 " LEFT JOIN sno_cargo  ON  (sno_personalnomina.codcar=sno_cargo.codcar and sno_personalnomina.codnom=sno_cargo.codnom)  ".
				 " JOIN sno_nomina ON ( sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0')  ".
				 " WHERE nrollam like '$as_nrollam' ".
				 "   AND codtrab like '$as_codtrab' ".
				 "   AND nomper like '$as_nomtrab' ".
				 "   AND apeper like '$as_apetrab' ".				
			     " ORDER BY nrollam";
				 
	 
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->llamada_atencion MÉTODO->uf_srh_buscar_llamada_atencion( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
					$ls_nrollam=$row["nrollam"];
					$ls_codtrab=$row["codtrab"];
					$ls_fecllam=$this->io_funcion->uf_formatovalidofecha($row["fecllam"]);
				    $ls_fecllam=$this->io_funcion->uf_convertirfecmostrar($ls_fecllam);
					$ls_apetrab =  trim ( htmlentities  ($row["apeper"]));
					$ls_nomtrab = trim ( htmlentities   ($row["nomper"]));
					$ls_tipo= trim (htmlentities ($row["tipo"]));
					$ls_causa= trim (htmlentities ($row["causa"]));
					$ls_cargo1= trim (htmlentities ($row["denasicar"]));
				    $ls_cargo2= trim (htmlentities ($row["descar"]));
										
					 if ($ls_cargo1!="Sin Asignación de Cargo")
				     {
					   $ls_cargo=$ls_cargo1;
				     }
				     if ($ls_cargo2!="Sin Cargo")
				     {
					   $ls_cargo=$ls_cargo2;
				      }	
					
					$ls_coduniad=  trim (htmlentities ($row["unidad"]));					
					$ls_des= trim (htmlentities  ($row["descripcion"]));
				
			
					
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['nrollam']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($row['nrollam']." ^javascript:aceptar(\"$ls_nrollam\", \"$ls_codtrab\", \"$ls_fecllam\", \"$ls_apetrab\", \"$ls_nomtrab\", \"$ls_des\",\"$ls_cargo\", \"$ls_coduniad\",  \"$ls_nrodestino\", \"$ls_coddestino\", \"$ls_fecllamdestino\",  \"$ls_nomdestino\", \"$ls_desdestino\",\"$ls_cardestino\", \"$ls_coduniaddestino\", \"$ls_causa\", \"$ls_causadestino\", \"$ls_tipo\",\"$ls_tipodestino\" );^_self"));
					
			
			
			
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_fecllam));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($row['codtrab']));												
					$row_->appendChild($cell);
					
					
					if ($ls_apetrab!='0'){
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_nomtrab.'  '.$ls_apetrab));												
					$row_->appendChild($cell);								
					}
					else 
					{
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_nomtrab));												
					$row_->appendChild($cell); }
					
					if ($ls_causa == '1')
					{
					  if ($ls_tipo == '1')
					  {					
						$cell = $row_->appendChild($dom->createElement('cell'));
						$cell->appendChild($dom->createTextNode('Amonestacion - Verbal'));												
						$row_->appendChild($cell);
					  }
					  elseif ($ls_tipo == '2')
					  {					
						$cell = $row_->appendChild($dom->createElement('cell'));
						$cell->appendChild($dom->createTextNode('Amonestacion - Escrita'));												
						$row_->appendChild($cell);
					  }
					}
					elseif ($ls_causa == '2')
					{
					  if ($ls_tipo == '1')
					  {					
						$cell = $row_->appendChild($dom->createElement('cell'));
						$cell->appendChild($dom->createTextNode('Llamada de Atencion - Verbal'));												
						$row_->appendChild($cell);
					  }
					  elseif ($ls_tipo == '2')
					  {					
						$cell = $row_->appendChild($dom->createElement('cell'));
						$cell->appendChild($dom->createTextNode('Llamada de Atencion - Escrita'));												
						$row_->appendChild($cell);
					  }
					}
			
			}
			return $dom->saveXML();
		
			
			
		
		}
      
		
	} // end function buscar_llamada_atencion
	

//FUNCIONES PARA EL MANEJO DEL DETALLE DE LAS CAUSAS DE LLAMADA DE ATENCION

function uf_srh_guardar_dt_llamada_atencion($ao_llamada, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_dt_llamada_atencion															     	//
		//         access: public (sigesp_dt_srh_llamada_atencion)														        //
		//      Argumento: $ao_llamada    // arreglo con los datos de los detalle de la llamada de atencion					//
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              //
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta o modifica una llamada de atencion en la tabla srh_dt_llamada_atencion           //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 08/11/2007							Fecha Última Modificación: 08/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	  $ls_sql = "INSERT INTO srh_dt_llamada_atencion (nrollam,codcaullam_aten, codemp) ".	  
	            " VALUES ('$ao_llamada->nrollam','$ao_llamada->codcaullam_aten','".$this->ls_codemp."')";
	  
	
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el detalle de llamada de atencion ".$ao_llamada->nrollam;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->llamada_atencion MÉTODO->uf_srh_guardar_dt_llamada_atencion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
	
function uf_srh_eliminar_dt_llamada_atencion($as_nrollam, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_dt_llamada_atencion																	//
		//        access:  public (sigesp_srh_dt_llamada_atencion)														        //
		//      Argumento: $as_nrollam        // numero de la llamada de atencion                                               //
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                //
		//	      Returns: Retorna un Booleano																				    //
		//    Description: Funcion que elimina una llamada de atencion en la tabla srh_dt_llamada_atencion                      //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 08/11/2007							Fecha Última Modificación: 08/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_dt_llamada_atencion ".
	          " WHERE nrollam='$as_nrollam'  AND codemp='".$this->ls_codemp."'";
			  
	
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->llamada_atencion MÉTODO->uf_srh_eliminar_dt_llamada_atencion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el detalle de llamada de atencion ".$as_nrollam;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
			
	
	return $lb_borro;
	
  }
  
  
  
 function getDetalle_Llamada ($as_nrollam, &$pa_datos="")
  {
     $lb_valido=true;
    $ls_sql= "SELECT *  FROM srh_dt_llamada_atencion INNER JOIN srh_causa_llamada_atencion ON (srh_causa_llamada_atencion.codcaullam_aten = srh_dt_llamada_atencion.codcaullam_aten)  ".
				" WHERE nrollam = '$as_nrollam' ".
				" ORDER BY nrollam";
	$lb_valido=$this->io_sql->seleccionar($ls_sql, $pa_datos);
   
    return $lb_valido;
  }	
	
	
	
function uf_srh_load_llamada_atencion_campos($as_nrollam,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_llamada_atencion_campos
		//	    Arguments: $as_nrollam  // código de la escala
		//				   $ai_totrows  // total de filas del detalle
		//				   $ao_object  // objetos del detalle
		//	      Returns: $lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de una llamada de atención
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * ".
				"  FROM srh_dt_llamada_atencion, srh_causa_llamada_atencion ".
				"  WHERE srh_dt_llamada_atencion.codemp='".$this->ls_codemp."'".
				"  AND srh_dt_llamada_atencion.codcaullam_aten = srh_causa_llamada_atencion.codcaullam_aten".
				"   AND nrollam='".$as_nrollam."'".
				" ORDER BY nrollam ";

				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->llamada_atencion MÉTODO->uf_srh_uf_srh_load_llamada_atencion_campos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_codcau=$row["codcaullam_aten"];
				$ls_dencau=trim (htmlentities ($row["dencaullam_aten"]));
				
				$ao_object[$ai_totrows][1]="<input name=txtcodcaullam_aten".$ai_totrows." type=text id=txtcodcaullam_aten".$ai_totrows." class=sin-borde size=15  readonly  value='".$ls_codcau."' >";
				$ao_object[$ai_totrows][2]="<input name=txtdencaullam_aten".$ai_totrows." type=text id=txtdencaullam_aten".$ai_totrows." class=sin-borde size=70  readonly  value='".$ls_dencau."'>";
				$ao_object[$ai_totrows][3]="<a href=javascript:catalogo_causas(".$ai_totrows.")    align=center><img src=../../../../shared/imagebank/tools15/buscar.gif alt=Buscar width=15 height=15 border=0 align=center></a>";		
				$ao_object[$ai_totrows][4]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$ao_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt(".$ai_totrows.");    align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";		
				
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	
	

}// end   class sigesp_srh_c_llamada_atencion
?>
