<?php

class sigesp_srh_c_registro_metas
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_registro_metas($path)
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
		//         Access: public (sigesp_srh_p_revision_metas)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de una registro de metas de personal
		//    Description: Funcion que genera un código nuevo de una registro de metas de personal
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:16/01/2008							Fecha Última Modificación:16/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(nroreg) AS numero FROM srh_registro_metas ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_nroreg = $la_datos["numero"][0]+1;
    $ls_nroreg = str_pad ($ls_nroreg,10,"0","left");
	 
    return $ls_nroreg;
  } 
	
	
  
  
function uf_srh_guardarregistro_metas($ao_registro,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarregistro_metas																	
		//         access: public (sigesp_srh_registro_metas)		   											            
		//      Argumento: $ao_registro    // arreglo con los datos del registro de metas 										
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)         	    
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                              
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica un registro de metas en la tabla srh_registro_metas     		
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 03/01/2008							Fecha Última Modificación: 03/01/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$as_nroreg = $ao_registro->nroreg;
	
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	 $ao_registro->fecreg=$this->io_funcion->uf_convertirdatetobd($ao_registro->fecreg);
	 $ao_registro->fecini=$this->io_funcion->uf_convertirdatetobd($ao_registro->fecini);
	 $ao_registro->fecfin=$this->io_funcion->uf_convertirdatetobd($ao_registro->fecfin);
	 
	 
	  $ls_sql = "UPDATE srh_registro_metas SET ".
	            "fecini      = '$ao_registro->fecini' ,".
			    "fecfin      = '$ao_registro->fecfin' ,".
	           	"observacion    = '$ao_registro->obs' ".
				"WHERE nroreg= '$ao_registro->nroreg' AND fecreg= '$ao_registro->fecreg' AND codper= '$ao_registro->codper'  AND codemp='".$this->ls_codemp."'" ;
		      
				
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el registro de metas de personal ".$as_nroreg;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	  $ao_registro->fecreg=$this->io_funcion->uf_convertirdatetobd($ao_registro->fecreg);
	  $ao_registro->fecini=$this->io_funcion->uf_convertirdatetobd($ao_registro->fecini);
  	  $ao_registro->fecfin=$this->io_funcion->uf_convertirdatetobd($ao_registro->fecfin);
	 
	
	    $ls_sql = "INSERT INTO srh_registro_metas (nroreg, codper, fecreg,fecini, fecfin, observacion, codemp) ".	  
	            "VALUES ('$ao_registro->nroreg','$ao_registro->codper','$ao_registro->fecreg','$ao_registro->fecini','$ao_registro->fecfin','$ao_registro->obs','".$this->ls_codemp."')";
				

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el registro de metas de personal".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->registro_metas MÉTODO->uf_srh_guardarregistro_metas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				//Guardamos los Metas del Personal			 	
				$lb_guardo = $this->guardar_Metas($ao_registro, $aa_seguridad);				
				if ($lb_guardo)
				{$this->io_sql->commit();}
				
		}
		
	 


	return $lb_valido;
  }
	
	
	
function guardar_Metas ($ao_registro, $aa_seguridad)
  {
    //Borramos los registros anteriores 
	$this-> uf_srh_eliminar_meta($ao_registro->nroreg, $aa_seguridad);
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_reg = 0;
	while (($li_reg < count($ao_registro->meta)) && ($lb_guardo))
	{
	  $lb_guardo = $this-> uf_srh_guardar_meta($ao_registro->meta[$li_reg], $aa_seguridad);
	  $li_reg++;
	}
	
	return $lb_guardo;    
  }


function uf_select_metas_revisiones ($as_nroreg)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_metas_revisiones
		//		   Access: private
 		//	    Arguments: as_nroreg // código del registro de revision de las metas
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el registro de metas esta asociado a un evaluacion
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT nroreg ".
				 "  FROM srh_revision_metas".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND nroreg = '".$as_codasp."' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->registro_metas  MÉTODO->uf_select_metas_revisiones ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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


	

function uf_srh_eliminarregistro_metas($as_nroreg,  $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarregistro_metas																	
		//        access:  public (sigesp_srh_registro_metas)														
		//      Argumento: $as_nroreg        // numero del registro de metas de personal		
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina un registro de metas de personal en la tabla srh_registro_metas
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 03/01/2008							Fecha Última Modificación: 03/01/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $lb_valido=true;
	$lb_existe= $this->uf_select_metas_revisiones($as_nroreg);
	if ($lb_existe)
	{
			
		$lb_valido=false;
		
	}
	else
	{
	
    $this->io_sql->begin_transaction();	

	$this-> uf_srh_eliminar_meta($as_nroreg,$aa_seguridad);
	  
    $ls_sql = "DELETE FROM srh_registro_metas ".
	          "WHERE nroreg = '$as_nroreg' AND codemp='".$this->ls_codemp."'";
		 
			  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->registro_metas MÉTODO->uf_srh_eliminarregistro_metas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó lel registro de metas de personal ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
   }
   
	return array($lb_valido,$lb_existe);
  }
	

	
function uf_srh_buscar_registro_metas($as_nroreg,$as_codper,$as_apeper,$as_nomper,$as_fecreg1,$as_fecreg2)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_registro_metas																				
		//         access: public (sigesp_srh_registro_metas)													             //
		//      Argumento: $as_nroreg   //  numero del registro de metas
		//                 $as_codper   //  código del personal                                                              	//
		//                 $as_apeper   //  apellido del personal                                                            	//
		//                 $as_nomper   //  nombre del personal                                                                  //
		//                $as_fecreg   //  fecha de registro de las metas												
		//	      Returns: Retorna un XML  																						//
		//    Description: Funcion busca un registro de metas de personal en la tabla srh_registro_metas 
		//                  y crea un XML para mostrar  los datos en el catalogo
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 03/01/2008							Fecha Última Modificación: 03/01/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
	    $as_fecreg1=$this->io_funcion->uf_convertirdatetobd($as_fecreg1);
		$as_fecreg2=$this->io_funcion->uf_convertirdatetobd($as_fecreg2);
			
	    $ls_nrodestino="txtnroreg";
		$ls_ceddestino="txtcodper";
		$ls_fecregdestino="txtfecreg";
		$ls_apedestino="txtapeper";
		$ls_nomdestino="txtnomper";
		$ls_fecinidestino="txtfecini";
		$ls_obsdestino="txtobs";
     	$ls_fecfindestino="txtfecfin";
		$ls_codcarperdestino="txtcodcarper";
		
		$lb_valido=true;
		
		
				
		$ls_sql= "SELECT * FROM srh_registro_metas INNER JOIN sno_personal ON (sno_personal.codper = srh_registro_metas.codper) ".
				 " JOIN sno_personalnomina  ON  (srh_registro_metas.codper=sno_personalnomina.codper)   ".
				 " LEFT JOIN sno_asignacioncargo ON  (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar and sno_personalnomina.codnom=sno_asignacioncargo.codnom)  ".
				" LEFT JOIN sno_cargo  ON  (sno_personalnomina.codcar=sno_cargo.codcar and sno_personalnomina.codnom=sno_cargo.codnom)  ".
				" JOIN sno_nomina ON ( sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0')  ".
				" WHERE srh_registro_metas.nroreg LIKE '$as_nroreg' ".
				"   AND srh_registro_metas.fecreg  BETWEEN  '".$as_fecreg1."' AND '".$as_fecreg2."' ".
				"   AND srh_registro_metas.codper LIKE '$as_codper' ".
				"   AND nomper LIKE '$as_nomper' ".
				"   AND apeper LIKE '$as_apeper' ".
			   " ORDER BY srh_registro_metas.nroreg";
			   
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->registro_metas MÉTODO->uf_srh_buscar_registro_metas( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);		
			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{  
					$ls_nroreg=$row["nroreg"];
					$ls_codper=$row["codper"];
					$ls_fecreg=$this->io_funcion->uf_formatovalidofecha($row["fecreg"]);
				    $ls_fecreg=$this->io_funcion->uf_convertirfecmostrar($ls_fecreg);
					$ls_apeper = trim (htmlentities ($row["apeper"]));
					$ls_nomper= trim (htmlentities  ($row["nomper"]));
					
					$ls_cargo1= trim (htmlentities ($row["denasicar"]));
				    $ls_cargo2= trim (htmlentities ($row["descar"]));
					
					 if ($ls_cargo1!="Sin Asignación de Cargo")
				      {
					   $ls_codcarper=$ls_cargo1;
				       }
				      if ($ls_cargo2!="Sin Cargo")
				      {
					   $ls_codcarper=$ls_cargo2;
				       }	
					
					$ls_fecini=$this->io_funcion->uf_formatovalidofecha($row["fecini"]);
				    $ls_fecini=$this->io_funcion->uf_convertirfecmostrar($ls_fecini);
					
					$ls_fecfin=$this->io_funcion->uf_formatovalidofecha($row["fecfin"]);
				    $ls_fecfin=$this->io_funcion->uf_convertirfecmostrar($ls_fecfin);
				
					$ls_obs= trim (htmlentities  ($row["observacion"]));
								
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['nroreg']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($row['nroreg']." ^javascript:aceptar(\"$ls_nroreg\", \"$ls_codper\",\"$ls_fecreg\", \"$ls_apeper\", \"$ls_nomper\", \"$ls_fecini\", \"$ls_obs\", \"$ls_fecfin\", \"$ls_nrodestino\" ,  \"$ls_ceddestino\" ,  \"$ls_fecregdestino\" ,  \"$ls_apedestino\" ,  \"$ls_nomdestino\" , \"$ls_fecinidestino\", \"$ls_obsdestino\", \"$ls_fecfindestino\", \"$ls_codcarper\", \"$ls_codcarperdestino\");^_self"));
					
								
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_fecreg));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($row['codper']));												
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
					
				 
					
					}
			 
			}
			
            
			return $dom->saveXML();
	
		
	} // end function buscar_registro_metas
	

//FUNCIONES PARA MANEJAR LAS METAS DEL PERSONAL

function uf_srh_guardar_meta($ao_registro, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_meta															     					
		//      Argumento: $ao_registro     // arreglo con los datos de los detalle de las metas de personal						        //                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta  una meta en la tabla srh_metas_registro_metas		        			  
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 03/01/2008							Fecha Última Modificación: 03/01/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();

	   $ls_sql = "INSERT INTO srh_dt_registro_metas (nroreg, codmeta, meta,estado_meta, codemp) ".	  
	            "VALUES ('$ao_registro->nroreg','$ao_registro->codmeta','$ao_registro->meta','$ao_registro->estado','".$this->ls_codemp."')";
	
				

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la meta del personal ".$ao_registro->nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->registro_metas MÉTODO->uf_srh_guardar_meta ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
	
function uf_srh_eliminar_meta($as_nroreg,$aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_meta																					//
		//       Argumento: $as_nroreg         // numero del registro de metas del personal
		//                  $aa_seguridad    //  arreglo de registro de seguridad                                                //
		//	      Returns: Retorna un Booleano																				    //
		//    Description: Funcion que elimina una meta en la tabla srh_metas_registro_metas             				   //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 03/01/2008							Fecha Última Modificación: 03/01/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
	
	  
    $ls_sql = "DELETE FROM srh_dt_registro_metas ".
	          " WHERE nroreg='$as_nroreg' AND codemp='".$this->ls_codemp."'";
			  
			  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->registro_metas MÉTODO->uf_srh_eliminar_meta ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la meta del personal ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
	return $lb_borro;
	
  }
  
  
function uf_srh_load_registro_metas_campos($as_nroreg,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_registro_metas_campos
		//	    Arguments: $as_nroreg  // número del registro de metas de perosnal
		//				   $ai_totrows  // total de filas del detalle
		//				   $ao_object  // objetos del detalle
		//	      Returns: $lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de una registro_metas
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	
		$ls_sql="SELECT * ".
				"  FROM srh_dt_registro_metas ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND nroreg='".$as_nroreg."'".
				" ORDER BY nroreg ";
	
				 $rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->registro_metas MÉTODO->uf_srh_load_registro_metas_campos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_meta=$row["meta"];
				$ls_codmet=trim (htmlentities  ($row["codmeta"]));
	   			$ls_estmet=trim (htmlentities  ($row["estado_meta"]));
				
				$ao_object[$ai_totrows][1]="<textarea name=txtcodmet".$ai_totrows."  cols=5 rows=3 id=txtcodmet".$ai_totrows." class=sin-borde >".$ls_codmet."</textarea>";
				$ao_object[$ai_totrows][2]="<textarea name=txtmeta".$ai_totrows."  cols=60 rows=3 id=txtmeta".$ai_totrows." class=sin-borde >".$ls_meta."</textarea>";
				$ao_object[$ai_totrows][3]="<textarea name=txtestmet".$ai_totrows." cols=10 rows=3  id=txtestmet".$ai_totrows."  class=sin-borde >".$ls_estmet."</textarea>";
				$ao_object[$ai_totrows][4]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$ao_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt(".$ai_totrows.");    align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";	
			
				
				}
				
				$this->io_sql->free_result($rs_data);
				
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_registro_metas
	//-----------------------------------------------------------------------------------------------------------------------------------	



}// end   class sigesp_srh_c_registro_metas
?>