<?php

class sigesp_srh_c_revisiones_odi
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_revisiones_odi($path)
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
	
	
	
function  uf_srh_validar_revsion ($as_nroreg,$as_fecrev1,$as_fecrev2,$as_revsion)
{

	    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_validar_revsion																	
		//         access: public (sigesp_srh_bono_merito)														    	
		//    	Argumento: $as_nroreg    // número de registro de ODI		
		//                 $as_fecha    //  fecha de la revision
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que valida que en un mismo periodo de evaluacion no se permita dos evaluaciones del mismo personal             
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 15/08/2008							Fecha Última Modificación: 						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$lb_valido=true;
		
		$ls_sql= "SELECT fecrev ".
				" FROM srh_revisiones_odi ".
				" WHERE srh_revisiones_odi.nroreg = '$as_nroreg' ";
			
			 
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revisiones_odi MÉTODO->uf_srh_validar_revsion( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
			$num= $this->io_sql->num_rows($rs_data);
			if ($num!=0)
			{
				
						
				while (($row=$this->io_sql->fetch_row($rs_data)) && ($lb_valido))
				{
					
						$ls_fecha_rev=$this->io_funcion->uf_formatovalidofecha($row["fecrev"]);
						if (($as_fecrev1 <= $ls_fecha_rev) && ($ls_fecha_rev<=$as_fecrev2) && ($as_revsion!='SEGUNDA REVISION')&&($num==1))
						 {
						     $lb_valido=false;
						 }
						 if (($as_fecrev1 <= $ls_fecha_rev) && ($ls_fecha_rev<=$as_fecrev2) && ($as_revsion!='PRIMERA REVISION')&&($num==2))
						 {
						     $lb_valido=false;
						 }
						 
						
				}
			}			
		  
		 }
	return $lb_valido;

}

	
	
 function uf_srh_guardarrevisiones_odi ($ao_revision,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarodi																		
		//         access: public (sigesp_srh_revisiones_odi)
		//      Argumento: $ao_revision    // arreglo con los datos de la revision de ODI//     
		//		            $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica un ODI en la tabla srh_revisiones_odi             
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 14/12/2007							Fecha Última Modificación: 14/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$as_nroreg=$ao_revision->nroreg;
	$lb_valido=true;
	$lb_valfecha=true;
  	if ($as_operacion == "modificar")
	{
		 $this->io_sql->begin_transaction();
		  $ao_revision->fecha=$this->io_funcion->uf_convertirdatetobd($ao_revision->fecha);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Modificó la revision de ODI ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				
				
		 $lb_guardo = $this->guardarDetalles_revisiones_odi($ao_revision, $aa_seguridad);		
			    
	}
	else
	{ 
	
		$this->io_sql->begin_transaction();
		
		$lb_valfecha=$this->uf_srh_validar_revsion($ao_revision->nroreg,$ao_revision->fecrev1,$ao_revision->fecrev2,trim($ao_revision->revision));
		if ($lb_valfecha)
		{
		 
			  $ao_revision->fecha=$this->io_funcion->uf_convertirdatetobd($ao_revision->fecha);
		
			  
			
			  $ls_sql = "INSERT INTO srh_revisiones_odi (nroreg, fecrev, codemp) ".	  
						"VALUES ('$ao_revision->nroreg','$ao_revision->fecha',  '".$this->ls_codemp."')";
		
				
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="INSERT";
						$ls_descripcion ="Insertó la revision de ODI ".$as_nroreg;
						$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			
			
			
			$lb_guardo = $this->io_sql->execute($ls_sql);
			
			
		
			 if($lb_guardo===false)
				{
					$this->io_msg->message("CLASE->revisiones_odi MÉTODO->uf_srh_guardarrevisiones_odi ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;
					$this->io_sql->rollback();
				}
				else
				{
						
						$lb_guardo=false;
						if ($lb_valido)
						{
						   //Guardamos el detalle de la revision de ODI
						  $lb_guardo = $this->guardarDetalles_revisiones_odi($ao_revision, $aa_seguridad);
							
							
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
			}	
	 }
	return array ($lb_valido,$lb_valfecha);
  }
	
	
	
function guardarDetalles_revisiones_odi ($ao_revision, $aa_seguridad)
  {
    //Borramos los registros anteriores 
	$this-> uf_srh_eliminar_dt_revisiones_odi($ao_revision->nroreg,$ao_revision->fecha, $aa_seguridad);
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_obj = 0;
	while (($li_obj < count($ao_revision->odi)) &&
	       ($lb_guardo))
	{
	  $lb_guardo = $this-> uf_srh_guardar_dt_revisiones_odi($ao_revision->odi[$li_obj], $aa_seguridad);
	  $li_obj++;
	}
	
	return $lb_guardo;    
  }


	

	
function uf_srh_eliminarrevisiones_odi($as_nroreg, $as_fecha, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uuf_srh_eliminarrevisiones_odi																	
		//        access:  public (sigesp_srh_revisiones_odi)														
		//      Argumento: $as_nroreg        // número de registro de los ODI
		//                 $as_fecha        //  fecha de registro de ODIS										
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina una revisión de ODI en la tabla srh_revisiones_odi                         
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 14/12/2007							Fecha Última Modificación: 14/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $as_fecha=$this->io_funcion->uf_convertirdatetobd($as_fecha);
	$this-> uf_srh_eliminar_dt_revisiones_odi($as_nroreg, $as_fecha, $aa_seguridad);

    $ls_sql = "DELETE FROM srh_revisiones_odi ".
	          "WHERE nroreg = '$as_nroreg' AND fecrev = '$as_fecha' AND codemp='".$this->ls_codemp."'";


	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->revisiones_odi MÉTODO->uf_srh_eliminarrevisiones_odi ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la revision de ODI ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				
					$this->io_sql->commit();
			}
	
	return $lb_valido;
  }
	

	
	
function uf_srh_buscar_revisiones_odi($as_nroreg,$as_fecha1,$as_fecha2)
	{
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_revisiones_odi																		//
		//         access: public (sigesp_srh_revisiones_odi)												
		//      Argumento: $as_codper   //  codigo de la persona                                                             
		//                 $as_apeper   //  apellido de la persona                                                            
		//                 $as_nomper   //  nombre de la persona                                                             
		//                 $as_fecha   //   fecha de la revision de ODI//	    																						
		//    Description: Funcion busca una revisión de ODI en la tabla srh_revisiones_odi y crea un XML para mostrar    
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 14/12/2007							Fecha Última Modificación: 14/12/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		
		$as_fecha1=$this->io_funcion->uf_convertirdatetobd($as_fecha1);
		$as_fecha2=$this->io_funcion->uf_convertirdatetobd($as_fecha2);
		
		
		$ls_fechadestino="txtfecha";
		$ls_nrodestino="txtnroreg";
		
		$ls_fecinidestino="txtfecini1";
		$ls_fecfindestino="txtfecfin1";

			
	    $ls_codevadestino="txtcodeva";
		$ls_nomevadestino="txtnomeva";
		$ls_carevadestino="txtcareva";
		$ls_revdestino="txtrev";
	    $ls_codperdestino="txtcodper";
		$ls_nomperdestino="txtnomper";
		$ls_carperdestino="txtcodcarper";


		$lb_valido=true;
		

				
		$ls_sql= "SELECT  * FROM srh_revisiones_odi INNER JOIN srh_odi ON (srh_revisiones_odi.nroreg = srh_odi.nroreg) INNER JOIN  srh_persona_odi ON  (srh_persona_odi.nroreg = srh_revisiones_odi.nroreg) INNER JOIN sno_personal ON (sno_personal.codper = srh_persona_odi.codper)   ".
				" JOIN sno_personalnomina  ON  (srh_persona_odi.codper=sno_personalnomina.codper)   ".
				" LEFT JOIN sno_asignacioncargo ON  (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar and sno_personalnomina.codnom=sno_asignacioncargo.codnom)  ".
				" LEFT JOIN sno_cargo  ON  (sno_personalnomina.codcar=sno_cargo.codcar and sno_personalnomina.codnom=sno_cargo.codnom)  ".
				" JOIN sno_nomina ON ( sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0')  ".
				" WHERE srh_revisiones_odi.nroreg like '$as_nroreg' ".	
				" AND srh_revisiones_odi.fecrev BETWEEN '".$as_fecha1."' AND '".$as_fecha2."' ".			
				" ORDER BY srh_revisiones_odi.nroreg, srh_revisiones_odi.fecrev";
			
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revisiones_odi MÉTODO->uf_srh_buscar_revisiones_odi( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		     $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);		
			 $ls_control=0;	
			 
			  $ls_apeper = "";
		      $ls_nomper="";
		      $ls_codper="";
			  $ls_carper="";
			 
			 
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			        $ls_nroreg=$row["nroreg"];
					$ls_fecha=$this->io_funcion->uf_formatovalidofecha($row["fecrev"]);
				    $ls_fecha=$this->io_funcion->uf_convertirfecmostrar($ls_fecha);
					
					$ls_fecinirev1=$this->io_funcion->uf_formatovalidofecha($row["fecinirev1"]);
					$ls_fecinirev1=$this->io_funcion->uf_convertirfecmostrar($ls_fecinirev1);	
					
					$ls_fecfinrev1=$this->io_funcion->uf_formatovalidofecha($row["fecfinrev1"]);
					$ls_fecfinrev1=$this->io_funcion->uf_convertirfecmostrar($ls_fecfinrev1);
					
					
					if (($this->io_fecha->uf_comparar_fecha($ls_fecinirev1,$ls_fecha)) && ($this->io_fecha->uf_comparar_fecha($ls_fecha,$ls_fecfinrev1)))
					{
						$ls_fecini=$this->io_funcion->uf_formatovalidofecha($row["fecinirev1"]);
						$ls_fecini=$this->io_funcion->uf_convertirfecmostrar($ls_fecini);						
						$ls_fecfin=$this->io_funcion->uf_formatovalidofecha($row["fecfinrev1"]);
						$ls_fecfin=$this->io_funcion->uf_convertirfecmostrar($ls_fecfin);						
						$ls_rev= "PRIMERA REVISION";
					}
					else
					{
						$ls_fecini=$this->io_funcion->uf_formatovalidofecha($row["fecinirev2"]);
						$ls_fecini=$this->io_funcion->uf_convertirfecmostrar($ls_fecini);						
						$ls_fecfin=$this->io_funcion->uf_formatovalidofecha($row["fecfinrev2"]);
						$ls_fecfin=$this->io_funcion->uf_convertirfecmostrar($ls_fecfin);
						$ls_rev= "SEGUNDA REVISION";
					}
			
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
					
			if ($ls_control=="2") 
			 {
			
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['nroreg']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($row['nroreg']." ^javascript:aceptar(\"$ls_nroreg\", \"$ls_fecha\", \"$ls_apeper\", \"$ls_nomper\", \"$ls_codper\",\"$ls_apeeva\", \"$ls_nomeva\", \"$ls_codeva\", \"$ls_careva\", \"$ls_fecini\",\"$ls_fecfin\", \"$ls_rev\", \"$ls_nrodestino\",\"$ls_codperdestino\", \"$ls_fechadestino\",  \"$ls_nomperdestino\", \"$ls_fecinidestino\",  \"$ls_fecfindestino\", \"$ls_codevadestino\",\"$ls_nomevadestino\", \"$ls_carevadestino\", \"$ls_revdestino\",\"$ls_carper\",\"$ls_carperdestino\");^_self"));
					
				
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
      
		
	} // end function buscar_revisiones_odi
	

//FUNCIONES PARA EL MANEJO DEL DETALLE DE LAS REVISIONES DE ODI

function uf_srh_guardar_dt_revisiones_odi($ao_revision, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_dt_revisiones_odi															     	
		//         access: public (sigesp_dt_srh_revisiones_odi)													              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica un ODI en la tabla srh_dt_revisiones_odi           
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 14/12/2007							Fecha Última Modificación: 14/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 
	 $ao_revision->fecha=$this->io_funcion->uf_convertirdatetobd($ao_revision->fecha);
	 
	  $ls_sql = "INSERT INTO srh_dt_revisiones_odi (nroreg, fecrev, cododi, odi, observacion, codemp) ".	  
	            " VALUES ('$ao_revision->nroreg','$ao_revision->fecha','$ao_revision->cododi', '$ao_revision->odi','$ao_revision->obs','".$this->ls_codemp."')";


		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el detalle de la revisión del ODI ".$ao_revision->nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->revisiones_odi MÉTODO->uf_srh_guardar_dt_revisiones_odi ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
	
function uf_srh_eliminar_dt_revisiones_odi($as_nroreg,$as_fecha, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_dt_revisiones_odi																
		//        access:  public (sigesp_srh_dt_revisiones_odi)														
		//      Argumento: $as_codper        //
		//	                $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																				    
		//    Description: Funcion que elimina un ODI en la tabla srh_dt_revisiones_odi                      
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 14/12/2007							Fecha Última Modificación: 14/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_dt_revisiones_odi ".
	          " WHERE nroreg='$as_nroreg' AND fecrev='$as_fecha' AND codemp='".$this->ls_codemp."'";


	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->revisiones_odi MÉTODO->uf_srh_eliminar_dt_revisiones_odi ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el detalle de la revisión del ODI ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
			
	
	return $lb_borro;
	
  }
  
  
function uf_srh_load_revisiones_odi_campos($as_nroreg,$as_fecha,$as_rev,&$ai_totrows,&$ao_object)
	{		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_revisiones_odi_campos
		//	    Arguments: as_nroreg   // número de revisión
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de una revisión de ODI
		//	   Creado Por: Maria Beatriz Unda	
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$as_fecha=$this->io_funcion->uf_convertirdatetobd($as_fecha);
		$ls_sql="SELECT * ".
				"  FROM srh_dt_revisiones_odi,  srh_odi, srh_dt_odi ".
				"  WHERE srh_dt_revisiones_odi.codemp='".$this->ls_codemp."'".
				"  AND srh_dt_revisiones_odi.nroreg = '$as_nroreg' ".
				"  AND srh_dt_revisiones_odi.fecrev = '$as_fecha' ".
				"  AND srh_odi.nroreg = '$as_nroreg' ".
				"  AND srh_dt_odi.nroreg = '$as_nroreg' ".
				"  AND srh_dt_revisiones_odi.cododi = srh_dt_odi.cododi ".
				" ORDER BY srh_dt_odi.nroreg, srh_dt_revisiones_odi.cododi ";
        
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->revisiones_odi MÉTODO->uf_srh_load_revisiones_odi_campos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			$la_obs[0]="";
			$la_obs[1]="";
			$la_obs[2]="";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_odi=htmlentities($row["odi"]);
				$ls_codod=trim($row["cododi"]);
				$li_valor=$row["valor"];
				$ls_obsrev=htmlentities  ($row["observacion"]);
				
				 switch($ls_obsrev)
				 {
					case "1":
						$la_obs[0]="selected";
						break;
					case "2":
						$la_obs[1]="selected";
						break;
					case "3":
						$la_obs[2]="selected";
						break;
				}
							
				$ao_object[$ai_totrows][1]="<textarea name=txtodi".$ai_totrows."  cols=47 rows=3 id=txtodi".$ai_totrows." class=sin-borde readonly>".$ls_odi."</textarea> <input name=txtcododi".$ai_totrows." type=hidden class=sin-borde id=txtcododi".$ai_totrows."  readonly value=".$ls_cododi.">";
				$ao_object[$ai_totrows][2]="<textarea name=txtvalor".$ai_totrows."    cols=6 rows=3 id=txtvalor".$ai_totrows."  class=sin-borde readonly>".$li_valor." </textarea>";
				
				 if ($as_rev=="PRIMERA REVISION")
						 {
				  	       $ao_object[$ai_totrows][3]="<select name=cmbobs".$ai_totrows." id=cmbobs".$ai_totrows.">
												  <option value='' selected>--Seleccione--</option>
												  <option value='1' ".$la_obs[0].">En Proceso</option>
												  <option value='2' ".$la_obs[1].">Alcanzado</option>
												</select>";
							$la_obs[0]="";
							$la_obs[1]="";
							$la_obs[2]="";
						}
						else
						{
						   	$ao_object[$ai_totrows][3]="<select name=cmbobs".$ai_totrows." id=cmbobs".$ai_totrows.">
												  <option value='' selected>--Seleccione--</option>							 
												  <option value='2' ".$la_obs[1].">Alcanzado</option>
												  <option value='3' ".$la_obs[2].">No Alcanzado</option>
												</select>";
							$la_obs[0]="";
							$la_obs[1]="";
							$la_obs[2]="";
						}
				
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	
function uf_srh_consultar_revisiones_odi ($as_nroreg, $as_fecha, &$ai_totrows,&$ao_object,&$as_fecini,&$as_fecfin,&$as_rev,&$as_codper,&$as_nomper, &$as_carper, &$as_codeva, &$as_nomeva, &$as_careva)
{
		
		$lb_valido=true;
		
		$as_fecha1=$this->io_funcion->uf_convertirdatetobd($as_fecha);
		
		$ls_sql= "SELECT srh_odi.fecinirev1,srh_odi.fecinirev2,srh_odi.fecinirev1,srh_persona_odi.codper,srh_persona_odi.tipo, ".
				 " sno_personal.nomper, sno_personal.apeper, sno_asignacioncargo.denasicar, sno_cargo.descar ".
				 " FROM srh_odi INNER JOIN srh_persona_odi ON (srh_persona_odi.nroreg= srh_odi.nroreg) INNER JOIN sno_personal ON (srh_persona_odi.codper = sno_personal.codper) ".
		        " JOIN sno_personalnomina  ON  (srh_persona_odi.codper=sno_personalnomina.codper)   ".
				" LEFT JOIN sno_asignacioncargo ON  (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar and sno_personalnomina.codnom=sno_asignacioncargo.codnom)  ".
				" LEFT JOIN sno_cargo  ON  (sno_personalnomina.codcar=sno_cargo.codcar and sno_personalnomina.codnom=sno_cargo.codnom)  ".
				" JOIN sno_nomina ON ( sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0')  ".
				" WHERE srh_odi.nroreg = '$as_nroreg' ".
				"  AND '".$as_fecha1."' BETWEEN srh_odi.fecinirev1 AND srh_odi.fecfinrev2 ";			

	 	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revisiones_odi MÉTODO->uf_srh_consultar_revisiones_odi( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}
		else
		{ 
		   $num=$this->io_sql->num_rows($rs_data);
           
		  if ($num!=0) {
		       $ai_totrows=0;
				$ls_control=0;
				while ($row=$this->io_sql->fetch_row($rs_data) ) 
				{   					
					$ls_cargo1="";
					$ls_cargo2="";
					
					$ls_fecinirev1=$this->io_funcion->uf_formatovalidofecha($row["fecinirev1"]);
					$ls_fecinirev1=$this->io_funcion->uf_convertirfecmostrar($ls_fecinirev1);	
					
					
					if (($this->io_fecha->uf_comparar_fecha($ls_fecinirev1,$as_fecha)) && ($this->io_fecha->uf_comparar_fecha($as_fecha,$ls_fecfinrev1)))
					{
						$as_fecini=$this->io_funcion->uf_formatovalidofecha($row["fecinirev1"]);
						$as_fecini=$this->io_funcion->uf_convertirfecmostrar($as_fecini);						
						$as_fecfin=$this->io_funcion->uf_formatovalidofecha($row["fecfinrev1"]);
						$as_fecfin=$this->io_funcion->uf_convertirfecmostrar($as_fecfin);		
						$as_rev= "PRIMERA REVISION";
					}
					else
					{
						$as_fecini=$this->io_funcion->uf_formatovalidofecha($row["fecinirev2"]);
						$as_fecini=$this->io_funcion->uf_convertirfecmostrar($as_fecini);						
						$as_fecfin=$this->io_funcion->uf_formatovalidofecha($row["fecfinrev2"]);
						$as_fecfin=$this->io_funcion->uf_convertirfecmostrar($as_fecfin);		
						$as_rev= "SEGUNDA REVISION";
					}
					$ls_cargo1= trim ($row["denasicar"]);
					$ls_cargo2= trim ($row["descar"]);
					
					if ($row["tipo"]=="E") 
					{ 
					  $as_apeeva = htmlentities  ($row["apeper"]);
					  if ($as_apeeva!='0')
					  {
				        $as_nomeva=htmlentities ($row["nomper"]). " ".$as_apeeva;
					   }
					   
					   else
					   {
					    $as_nomeva=htmlentities  ($row["nomper"]);
					   }
				        $as_codeva=$row["codper"];
					    if (trim($ls_cargo1)!="Sin Asignación de Cargo")
						  {
						   $as_careva=htmlentities($ls_cargo1);
						   
				       }
				     	else if (trim($ls_cargo2)!="Sin Cargo")
				      	{
					  	  $as_careva=htmlentities($ls_cargo2);
						 
				      	 }	
						
					 }
					else 
					{ 
					 
					  $as_codper=$row["codper"];
					  $as_apeper =htmlentities   ($row["apeper"]);
					   if ($as_apeper!='0')
					  {
				        $as_nomper=htmlentities   ($row["nomper"]). " ".$as_apeper;
					   }
					   else
					   {
					    $as_nomper=htmlentities  ($row["nomper"]);
					   }
					   
					     if ($ls_cargo1!="Sin Asignación de Cargo")
				      {
					   $as_carper=htmlentities($ls_cargo1);
				       }
				      if ($ls_cargo2!="Sin Cargo")
				      {
					   $as_carper=htmlentities($ls_cargo2);
				       }	
					}   
			}   
			$ls_sql2="SELECT * ".
				"  FROM srh_dt_odi ".
				"  WHERE srh_dt_odi.codemp='".$this->ls_codemp."'".
				"  AND srh_dt_odi.nroreg = '".$as_nroreg."' ".
				" ORDER BY  cododi  ";
  
		
		$rs_data2=$this->io_sql->select($ls_sql2);
		if($rs_data2===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->revisiones_odi MÉTODO->uf_srh_consultar_revisiones_odi ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{		   
			while($row=$this->io_sql->fetch_row($rs_data2))
			{
				$ai_totrows++;	   			   
				$ls_odi=htmlentities  ($row["odi"]);
				$li_valor=$row["valor"];
				$ls_cododi=trim($row["cododi"]);
				$ao_object[$ai_totrows][1]="<textarea name=txtodi".$ai_totrows."  cols=47 rows=3 id=txtodi".$ai_totrows." class=sin-borde readonly>".$ls_odi."</textarea> <input name=txtcododi".$ai_totrows." type=hidden class=sin-borde id=txtcododi".$ai_totrows."  readonly value=".$ls_cododi.">";
					     $ao_object[$ai_totrows][2]="<textarea name=txtvalor".$ai_totrows."    cols=6 rows=3 id=txtvalor".$ai_totrows."  class=sin-borde readonly>".$li_valor." </textarea>";
						 
						 if ($as_rev== "PRIMERA REVISION")
						 {
				  	       $ao_object[$ai_totrows][3]="<select name=cmbobs".$ai_totrows." id=cmbobs".$ai_totrows.">
												  <option value='' selected>--Seleccione--</option>
												  <option value='1' >En Proceso</option>
												  <option value='2' >Alcanzado</option>											 
												</select>";
						}
						else
						{
						   	$ao_object[$ai_totrows][3]="<select name=cmbobs".$ai_totrows." id=cmbobs".$ai_totrows.">
												  <option value='' selected>--Seleccione--</option>							 
												  <option value='2' >Alcanzado</option>
												  <option value='3' >No Alcanzado</option>
												</select>";
						}
					   
					}
			}
				
		}
		else 
		  {
		     $this->io_msg->message("No se encontraron Registros con esos datos.");
	 		$ai_totrows=1;	
			$ao_object[$ai_totrows][1]="<textarea name=txtodi".$ai_totrows."  cols=47 rows=3 id=txtodi".$ai_totrows." class=sin-borde readonly></textarea>";
			$ao_object[$ai_totrows][2]="<textarea name=txtvalor".$ai_totrows."    cols=6 rows=3 id=txtvalor".$ai_totrows."  class=sin-borde readonly></textarea>";
			$ao_object[$ai_totrows][3]="<select name=cmbobs".$ai_totrows." id=cmbobs".$ai_totrows.">
										<option value='' selected>--Seleccione--</option>
										<option value='1' >En Proceso</option>
										<option value='2' >Alcanzado</option>
										<option value='3' >No Alcanzado</option>
										</select>";
			
		  }  
		
		
		return $lb_valido;
	
		}
      
		
	} // end uf_srh_consultar_revisiones_odi 



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
	   
	  
	   if (($total_dias < 120)  && ($lb_valido!=false))  
	   {
	           $lb_valido= true;
	   }
	   else 
	   { 
	   			$lb_valido=false;
		}
	   
	  
	 return $lb_valido;
  }
}


}// end   class sigesp_srh_c_revisiones_odi
?>