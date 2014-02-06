<?php

class sigesp_srh_c_odi
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_odi($path)
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
		//         Access: public (sigesp_srh_p_odi)
		//      Argumento: 
		//	      Returns: Retorna el nuevo número de un registro de ODI
		//    Description: Funcion que genera un número de un registro de ODI
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
		$ls_sql = "SELECT MAX(nroreg) AS numero FROM srh_odi ";
		$lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
		if ($lb_hay)
		$ls_nroreg = $la_datos["numero"][0]+1;
	    $ls_nroreg = str_pad ($ls_nroreg,10,"0","left");
		return $ls_nroreg;
   } 
	
  
  
function uf_srh_guardarodi ($ao_objetivo,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarodi																		
		//         access: public (sigesp_srh_odi)
		//      Argumento: $ao_objetivo    // arreglo con los datos de los ODI//     
		//		            $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica un ODI en la tabla srh_odi             
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 12/12/2007							Fecha Última Modificación: 12/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	
	$as_nroreg=$ao_objetivo->nroreg;
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	 $ao_objetivo->fecha=$this->io_funcion->uf_convertirdatetobd($ao_objetivo->fecha);
	 $ao_objetivo->fecini1=$this->io_funcion->uf_convertirdatetobd($ao_objetivo->fecini1);
     $ao_objetivo->fecfin1=$this->io_funcion->uf_convertirdatetobd($ao_objetivo->fecfin1);
     $ao_objetivo->fecini2=$this->io_funcion->uf_convertirdatetobd($ao_objetivo->fecini2);
	 $ao_objetivo->fecfin2=$this->io_funcion->uf_convertirdatetobd($ao_objetivo->fecfin2);
	 
	 
	  $ls_sql = "UPDATE srh_odi SET ".
		  		"fecinirev1 = '$ao_objetivo->fecini1' , ".			
				"fecfinrev1 = '$ao_objetivo->fecfin1' , ".
				"fecinirev2 = '$ao_objetivo->fecini2' , ".
				"fecfinrev2 = '$ao_objetivo->fecfin2' , ".
				"total = '$ao_objetivo->total' , ".
				"objetivo = '$ao_objetivo->obj'  ".
	            "WHERE  fecha = '$ao_objetivo->fecha'  AND nroreg = '$ao_objetivo->nroreg' AND codemp='".$this->ls_codemp."'" ;


			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó los ODI ".$as_nroreg;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	  $ao_objetivo->fecha=$this->io_funcion->uf_convertirdatetobd($ao_objetivo->fecha);
      $ao_objetivo->fecini1=$this->io_funcion->uf_convertirdatetobd($ao_objetivo->fecini1);
      $ao_objetivo->fecfin1=$this->io_funcion->uf_convertirdatetobd($ao_objetivo->fecfin1);
      $ao_objetivo->fecini2=$this->io_funcion->uf_convertirdatetobd($ao_objetivo->fecini2);
	  $ao_objetivo->fecfin2=$this->io_funcion->uf_convertirdatetobd($ao_objetivo->fecfin2);
	
	  $ls_sql = "INSERT INTO srh_odi (nroreg, fecha, objetivo, fecinirev1,fecfinrev1,fecinirev2,fecfinrev2,total, codemp) ".	  
	            "VALUES ('$ao_objetivo->nroreg','$ao_objetivo->fecha', '$ao_objetivo->obj', '$ao_objetivo->fecini1','$ao_objetivo->fecfin1','$ao_objetivo->fecini2','$ao_objetivo->fecfin2','$ao_objetivo->total','".$this->ls_codemp."')";

	
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el registro de ODI ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->uf_srh_odi MÉTODO->guardarodi ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				$lb_guardo=false;
				if ($lb_valido)
				{
				   //Guardamos los ODI
				    $lb_guardo = $this->guardarDetalles_ODI($ao_objetivo, $aa_seguridad);
					if ($lb_guardo)
					{//Guardamos las Personas involucradas en la Revisión de ODI (Evaluador y Trabajador)
						$lb_guardo = $this->guardarPersonas_odi($ao_objetivo, $aa_seguridad);
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
	
	
function guardarPersonas_odi ($ao_objetivo, $aa_seguridad)
  {
   
	$lb_guardo = true;
	$as_nro = $ao_objetivo->nroreg;
	  $lb_guardo1 = $this-> uf_srh_eliminar_persona($as_nro);
	  if ($lb_guardo1===true) {
		
	  }
     $lb_guardo = $this->uf_srh_guardar_evaluador  ($ao_objetivo, $aa_seguridad);
  	 $lb_guardo = $this->uf_srh_guardar_trabajador ($ao_objetivo, $aa_seguridad); 
	
	return $lb_guardo;    
  }
	
	
	
function guardarDetalles_ODI ($ao_objetivo, $aa_seguridad)
  {
    //Borramos los registros anteriores 
	$this-> uf_srh_eliminar_dt_odi($ao_objetivo->nroreg, $aa_seguridad);
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_obj = 0;
	while (($li_obj < count($ao_objetivo->odi)) &&($lb_guardo))
	{
	  $lb_guardo = $this->uf_srh_guardar_dt_odi($ao_objetivo->odi[$li_obj], $aa_seguridad);
	  $li_obj++;
	}
	
	return $lb_guardo;    
  }

	
 function uf_select_odi_revisiones ($as_nroreg)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_odi_revisiones
		//		   Access: private
 		//	    Arguments: as_nroreg // número de l registro de ODIS
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el registro de Odis esta asociada a una revision
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT nroreg ".
				 "  FROM srh_revisiones_odi".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND nroreg = '".$as_codasp."' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->odi  MÉTODO->uf_select_odi_revisiones  ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}



	
function uf_srh_eliminarodi($as_nroreg, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarodi																		
		//        access:  public (sigesp_srh_odi)														
		//      Argumento: $as_nroreg        //  número del registro de ODIS						
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina una solicitud de empleo en la tabla srh_odi                         
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 12/12/2007							Fecha Última Modificación: 12/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $lb_valido=true;
	$lb_existe= $this->uf_select_odi_revisiones($as_nroreg);
	if ($lb_existe)
	{
			
		$lb_valido=false;
		
	}
	else
	{
	
    $this->io_sql->begin_transaction();	
	$this-> uf_srh_eliminar_persona($as_nroreg, $aa_seguridad);
	$this-> uf_srh_eliminar_dt_odi($as_nroreg, $aa_seguridad);
    $ls_sql = "DELETE FROM srh_odi ".
	          "WHERE nroreg = '$as_nroreg'   AND codemp='".$this->ls_codemp."'";


	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->uf_srh_odi MÉTODO->eliminarodi ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó los ODI ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				
					$this->io_sql->commit();
			}
	}

	return array($lb_valido,$lb_existe);
  }
	

	
	
function uf_srh_buscar_odi($as_nroreg, $as_fecha1, $as_fecha2)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_odi																		//
		//         access: public (sigesp_srh_odi)												
		//      Argumento: $as_nroreg   //  número del registro de ODIS
		//				   $as_codper   //  codigo de la persona                                                             
		//                 $as_apeper   //  apellido de la persona                                                            
		//                 $as_nomper   //  nombre de la persona                                                             
		//                 $as_fecha   //   fecha de los ODI//	      Returns: Retorna un XML  																						
		//    Description: Funcion busca una evaluación en la tabla srh_odi y crea un XML para mostrar    
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 12/12/2007							Fecha Última Modificación: 12/12/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
	    $as_fecha1=$this->io_funcion->uf_convertirdatetobd($as_fecha1);
		$as_fecha2=$this->io_funcion->uf_convertirdatetobd($as_fecha2);
		
		
		$ls_nroregdestino="txtnroreg";
		$ls_codperdestino="txtcodper";
		$ls_fechadestino="txtfecha";
		$ls_nomdestino="txtnomper";
		$ls_carperdestino="txtcodcarper";
		$ls_objdestino="txtobj";
		$ls_fecini1destino="txtfecini1";
		$ls_fecfin1destino="txtfecfin1";
		$ls_fecini2destino="txtfecini2";
		$ls_fecfin2destino="txtfecfin2";
		$ls_totaldestino="txttotal";
		$ls_codevadestino="txtcodeva";
		$ls_nomevadestino="txtnomeva";
		$ls_carevadestino="txtcodcareva";
		
		$lb_valido=true;
		
		
				
		$ls_sql= "SELECT *  FROM  srh_odi INNER JOIN srh_persona_odi ON (srh_persona_odi.nroreg = srh_odi.nroreg) 
		         INNER JOIN sno_personal ON (srh_persona_odi.codper = sno_personal.codper ) 
				 JOIN sno_personalnomina  ON  ( srh_persona_odi.codper=sno_personalnomina.codper)
				 LEFT JOIN sno_asignacioncargo ON  (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar and sno_personalnomina.codnom=sno_asignacioncargo.codnom)
				 LEFT JOIN sno_cargo  ON  (sno_personalnomina.codcar=sno_cargo.codcar and sno_personalnomina.codnom=sno_cargo.codnom)
				 JOIN sno_nomina ON (sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0')  
				 WHERE  srh_odi.nroreg  like '$as_nroreg' 
				 AND fecha between  '".$as_fecha1."' AND '".$as_fecha2."' 
				 ORDER BY srh_odi.nroreg" ;
				
					
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->odi MÉTODO->uf_srh_buscar_odi( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
					
					$ls_fecini1=$this->io_funcion->uf_formatovalidofecha($row["fecinirev1"]);
				    $ls_fecini1=$this->io_funcion->uf_convertirfecmostrar($ls_fecini1);
					
					$ls_fecfin1=$this->io_funcion->uf_formatovalidofecha($row["fecfinrev1"]);
				    $ls_fecfin1=$this->io_funcion->uf_convertirfecmostrar($ls_fecfin1);
					
					$ls_fecini2=$this->io_funcion->uf_formatovalidofecha($row["fecinirev2"]);
				    $ls_fecini2=$this->io_funcion->uf_convertirfecmostrar($ls_fecini2);
					
					$ls_fecfin2=$this->io_funcion->uf_formatovalidofecha($row["fecfinrev2"]);
				    $ls_fecfin2=$this->io_funcion->uf_convertirfecmostrar($ls_fecfin2);
					
									
					$ls_carper="";
					$ls_obj= trim (htmlentities  ($row["objetivo"]));
					$li_total=$row["total"];
					
					$ls_cargo1= trim (htmlentities ($row["denasicar"]));
					 $ls_cargo2= trim (htmlentities ($row["descar"]));
					
					if ($row["tipo"]=="E") 
					{ 
					  $ls_apeeva = trim (htmlentities ($row["apeper"]));
					  $ls_nomeva= trim (htmlentities  ($row["nomper"]));
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
					
					  $ls_apeper = trim (htmlentities ($row["apeper"]));
				      $ls_nomper= trim (htmlentities  ($row["nomper"]));
				      $ls_codper=$row["codper"];
					  
					   if ($ls_cargo1!="Sin Asignación de Cargo")
				      {
					   $ls_carper=$ls_cargo1;
				       }
				      if ($ls_cargo2!="Sin Cargo")
				      {
					   $ls_carper=$ls_cargo2;
				       }	
					  
					  $ls_control=$ls_control + 1; 
					}
					
					
					if ($ls_control=="2")  {
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['nroreg']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($row['nroreg']." ^javascript:aceptar(\"$ls_nroreg\", \"$ls_codper\", \"$ls_fecha\", \"$ls_apeper\", \"$ls_nomper\",\"$ls_codeva\", \"$ls_nomeva\", \"$ls_apeeva\", \"$ls_carper\", \"$ls_obj\", \"$ls_nroregdestino\",\"$ls_codperdestino\", \"$ls_fechadestino\",  \"$ls_nomdestino\", \"$ls_codevadestino\", \"$ls_nomevadestino\", 	 \"$ls_carperdestino\", \"$ls_objdestino\", \"$ls_fecini1\", \"$ls_fecfin1\", \"$ls_fecini2\", \"$ls_fecfin2\", \"$ls_fecini1destino\", \"$ls_fecfin1destino\",\"$ls_fecini2destino\", \"$ls_fecfin2destino\", \"$li_total\",\"$ls_totaldestino\", \"$ls_careva\", \"$ls_carevadestino\");^_self"));
					
				
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
      
		
	} // end function buscar_odi
	

//FUNCIONES PARA EL MANEJO DEL DETALLE DE LOS ODI

function uf_srh_guardar_dt_odi($ao_objetivo, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_dt_odi															     	
		//         access: public (sigesp_dt_srh_odi)														
		//      Argumento: $ao_solicitud    // arreglo con los datos de los detalle de los ODI//                
		//			       $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica un ODI en la tabla srh_dt_odi           
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 12/12/2007							Fecha Última Modificación: 12/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 
	 $ls_sql = "INSERT INTO srh_dt_odi (nroreg,cododi,odi, valor, codemp) ".	  
	            " VALUES ('$ao_objetivo->nroreg','$ao_objetivo->cododi','$ao_objetivo->odi','$ao_objetivo->valor','".$this->ls_codemp."')";


		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el detalle de ODI ".$ao_objetivo->nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->uf_srh_dt_odi MÉTODO->guardar_dt_odi ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			
		}
		else
		{
				$lb_valido=true;
				
		}
		
	return $lb_guardo;
  }
	
	
function uf_srh_eliminar_dt_odi($as_nroreg, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_dt_odi																
		//        access:  public (sigesp_srh_dt_odi)														
		//      Argumento: $as_nroreg        //  número de registro
		//	                $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																				    
		//    Description: Funcion que elimina un ODI en la tabla srh_dt_odi                      
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 12/12/2007							Fecha Última Modificación: 12/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_dt_odi ".
	          " WHERE nroreg='$as_nroreg' AND codemp='".$this->ls_codemp."'";
	

	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->uf_srh_dt_odi MÉTODO->eliminar_dt_odi ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el detalle de ODI ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
			
	
	return $lb_borro;
	
  }
  
  
  

function uf_srh_load_odi_campos($as_nroreg,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_odi_campos
		//	    Arguments: as_nroreg // número de registro de ODI
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de un registro de ODI
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
				
		$ls_sql="SELECT * ".
				"  FROM srh_dt_odi ".
				"  WHERE srh_dt_odi.codemp='".$this->ls_codemp."'".
				"  AND srh_dt_odi.nroreg = '".$as_nroreg."' ".
				" ORDER BY nroreg, cododi  ";
  
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->odi MÉTODO->uf_srh_load_odi_campos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_odi= trim (htmlentities($row["odi"]));
				$ls_cododi= trim ($row["cododi"]);
				$li_valor=$row["valor"];
				
				$ao_object[$ai_totrows][1]="<textarea name=txtodi".$ai_totrows."  cols=60 rows=3 id=txtodi".$ai_totrows." class=sin-borde>".$ls_odi."</textarea> <input name=txtcododi".$ai_totrows." type=hidden class=sin-borde id=txtcododi".$ai_totrows."  readonly value=".$ls_cododi.">";
				$ao_object[$ai_totrows][2]="<textarea name=txtvalor".$ai_totrows."    cols=6 rows=3 id=txtvalor".$ai_totrows."  class=sin-borde onKeyUp='javascript: ue_validarnumero(this);' onBlur='javascript: ue_sumar(txttotal,this);'>".$li_valor." </textarea>";
				$ao_object[$ai_totrows][3]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$ao_object[$ai_totrows][4]="<a href=javascript:uf_delete_dt(".$ai_totrows.",txttotal,txtvalor".$ai_totrows.");     align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";				
				
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

//FUNCIONES PARA EL MANEJO DEL LAS PERSONAS INVOLUACRADAS EN LA REVISIÓN DE LOS ODI

function uf_srh_eliminar_persona($as_nroreg)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_persona																																		
		//      Argumento: $as_revision		      // arreglo con detalles de la revision de ODIS						       
		//                 $aa_seguridad          //  arreglo de registro de seguridad                                  
		//	      Returns: Retorna un Booleano																					
		//    Description: 
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 14/12/2007							Fecha Última Modificación: 14/12/2007						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_persona_odi ".
	          "WHERE nroreg = '$as_nroreg'   AND codemp='".$this->ls_codemp."'";
			  
			  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->odi MÉTODO->uf_srh_eliminar_persona ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	



function uf_srh_guardar_trabajador ($ao_objetivo, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_trabajador															     														
		//      Argumento: $ao_objetivo    // arreglo con los datos de los detalle del registro de ODIS							
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)             //
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta una personal en la tabla srh_dt_revisionnestacion   	        
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 14/12/2007							Fecha Última Modificación: 14/12/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 
		 
	  $ls_sql = "INSERT INTO srh_persona_odi (nroreg,codper, tipo, codemp) ".	  
	            "VALUES ('$ao_objetivo->nroreg','$ao_objetivo->codper','P','".$this->ls_codemp."')";
				
				

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó Persona de la Revision de ODI ".$ao_objetivo->nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->odi MÉTODO->uf_srh_guardar_trabajador ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			
		}
		else
		{
				$lb_valido=true;
				
		}
		
	return $lb_guardo;
  }
  
  
 function uf_srh_guardar_evaluador ($ao_objetivo, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_evaluador														     													        //
		//      Argumento: $ao_objetivo    // arreglo con los datos de los detalle del registro de ODI						//
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              //
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta o modifica una persona en la tabla srh_persona_odi           
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 14/12/2007							Fecha Última Modificación: 14/12/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 
		 
	  $ls_sql = "INSERT INTO srh_persona_odi (nroreg,codper, tipo, codemp) ".	  
	            "VALUES ('$ao_objetivo->nroreg','$ao_objetivo->codeva','E','".$this->ls_codemp."')";
				
				

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó Persona de la Revsion de ODI".$ao_objetivo->nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->odi MÉTODO->uf_srh_guardar_evaluador ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			
		}
		else
		{
				$lb_valido=true;
				
		}
		
	return $lb_guardo;
  }	
	
	

}// end   class sigesp_srh_c_odi
?>