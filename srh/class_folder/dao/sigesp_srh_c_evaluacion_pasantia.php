<?php

class sigesp_srh_c_evaluacion_pasantia
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_evaluacion_pasantia($path)
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
	
	
	
   
  
function uf_srh_guardarEvaluacion_Pasantia($ao_evaluacion,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarEvaluacion_Pasantia																	//
		//         access: public (sigesp_srh_evaluacion_pasantia)		   											            //
		//      Argumento: $ao_evaluacion    // arreglo con los datos de la pasantia										    	
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)         	    //
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta o modifica un registro de pasantia en la tabla srh_evaluacion_pasantia     		//
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 14/11/2007							Fecha Última Modificación: 14/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$as_nropas = $ao_evaluacion->nropas;
	
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	 $ao_evaluacion->feceval=$this->io_funcion->uf_convertirdatetobd($ao_evaluacion->feceval);
	 
	 
	  $ls_sql = "UPDATE srh_evaluacion_pasantia SET ".
	            "resultado      = '$ao_evaluacion->res' ,".
	           	"observacion    = '$ao_evaluacion->obs' ".
				"WHERE nropas= '$ao_evaluacion->nropas' AND feceval= '$ao_evaluacion->feceval' AND codemp='".$this->ls_codemp."'" ;
		      
				
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó la evaluacion de pasantia ".$as_nropas;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	  $ao_evaluacion->feceval=$this->io_funcion->uf_convertirdatetobd($ao_evaluacion->feceval);
	 
	
	  $ls_sql = "INSERT INTO srh_evaluacion_pasantia (nropas, feceval,resultado, observacion, codemp) ".	  
	            "VALUES ('$ao_evaluacion->nropas','$ao_evaluacion->feceval','$ao_evaluacion->res','$ao_evaluacion->obs','".$this->ls_codemp."')";
				

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la evaluacion de pasantia ".$as_nropas;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->evaluacion_pasantia MÉTODO->uf_srh_guardarEvaluacion_Pasantia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				$this->io_sql->commit();
		}
	
	// Se actualiza el estado de la pasantia en la tabla srh_pasantias
	 $ls_sql = "UPDATE srh_pasantias SET ".
	            "estado      = '$ao_evaluacion->estado' ".
	            "WHERE nropas= '$ao_evaluacion->nropas'  AND codemp='".$this->ls_codemp."'" ;
		    
			
		$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->evaluacion_pasantia MÉTODO->uf_srh_guardarEvaluacion_Pasantia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				$this->io_sql->commit();
		}  

//Guardamos los Metas de la Evaluación de Pasantía
	 if($lb_guardo)
		{
			$lb_guardo = $this->guardarDetalles_Evaluacion($ao_evaluacion, $aa_seguridad);
		}
		
	return $lb_guardo;
  }
	
	
	
function guardarDetalles_Evaluacion ($ao_evaluacion, $aa_seguridad)
  {
    //Borramos los registros anteriores 
	$this-> uf_srh_eliminar_meta($ao_evaluacion->nropas,$ao_evaluacion->feceval, $aa_seguridad);
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_eval = 0;
	while (($li_eval < count($ao_evaluacion->metas)) && ($lb_guardo))
	{
	  $lb_guardo = $this->uf_srh_guardar_meta($ao_evaluacion->metas[$li_eval], $aa_seguridad);
	  $li_eval++;
	}
	
	return $lb_guardo;    
  }

	

function uf_srh_eliminarEvaluacion_Pasantia($as_nropas, $as_feceval, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarEvaluacion_Pasantia																	
		//        access:  public (sigesp_srh_evaluacion_pasantia)														
		//      Argumento: $as_nropas        // numero de el registro de pasantia		
		//                 $as_feceval       // fecha de la evaluación de la pasantía
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina un registro de evaluación de pasantia en la tabla srh_evaluacion_pasantia
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 14/11/2007							Fecha Última Modificación: 14/11/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
	$as_feceval=$this->io_funcion->uf_convertirdatetobd($as_feceval);
	$this-> uf_srh_eliminar_meta($as_nropas,$as_feceval ,$aa_seguridad);
	  
    $ls_sql = "DELETE FROM srh_evaluacion_pasantia ".
	          "WHERE nropas = '$as_nropas' AND feceval = '$as_feceval'  AND codemp='".$this->ls_codemp."'";
		  
			  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->evaluacion_pasantia MÉTODO->uf_srh_eliminarEvaluacion_Pasantia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la Evaluacion de Pasantia ".$as_nropas;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
   
   
	return $lb_borro;
  }
	
	
	
function uf_srh_buscar_evaluacion_pasantia($as_nropas,$as_cedpas,$as_apepas,$as_nompas,$as_feceval1,$as_feceval2)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_evaluacion_pasantia																				
		//         access: public (sigesp_srh_evaluacion_pasantia)													             //
		//      Argumento: $as_nropas   //  numero de pasantia											                        //
		//                 $as_cedpas   //  cedula del pasante                                                              	//
		//                 $as_apepas   //  apellido del pasante                                                            	//
		//                 $as_nompas   //  nombre del pasante                                                                  //
		//                $as_feceval   //  fecha de evaluación de la pasantia												//
		//	      Returns: Retorna un XML  																						//
		//    Description: Funcion busca una evaluacion de pasantia en la tabla srh_evaluacion_pasantia 
		//                  y crea un XML para mostrar  																		//
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 14/11/2007							Fecha Última Modificación: 14/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	    $as_feceval1=$this->io_funcion->uf_convertirdatetobd($as_feceval1);
		$as_feceval2=$this->io_funcion->uf_convertirdatetobd($as_feceval2);
		
			
	    $ls_nrodestino="txtnropas";
		$ls_ceddestino="txtcedpas";
		$ls_fecevaldestino="txtfeceval";
		$ls_apedestino="txtapepas";
		$ls_nomdestino="txtnompas";
		$ls_resdestino="txtres";
		$ls_obsdestino="txtobs";
     	$ls_estadodestino="combopas";
		$ls_fecinidestino="txtfecini";
		
		$lb_valido=true;
		
		
				
		$ls_sql= "SELECT * FROM srh_evaluacion_pasantia INNER JOIN srh_pasantias ON (srh_pasantias.nropas = srh_evaluacion_pasantia.nropas) ".
				" WHERE srh_evaluacion_pasantia.nropas like '$as_nropas' ".
				"   AND srh_evaluacion_pasantia.feceval BETWEEN  '".$as_feceval1."' AND '".$as_feceval2."' ".
				"   AND cedpas like '$as_cedpas' ".
				"   AND nompas like '$as_nompas' ".
				"   AND apepas like '$as_apepas' ".
				
			   " ORDER BY srh_evaluacion_pasantia.nropas";
			   
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->evaluacion_pasantia MÉTODO->uf_srh_buscar_evaluacion_pasantia( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);		
			
			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{  
			
					$ls_nropas=$row["nropas"];
					$ls_cedpas=$row["cedpas"];
					$ls_feceval=$this->io_funcion->uf_convertirfecmostrar($row["feceval"]);
					$ls_apepas =trim ( htmlentities ($row["apepas"]));
					$ls_nompas= trim (htmlentities  ($row["nompas"]));
					$ls_res=trim ($row["resultado"]);
					$ls_obs=trim (htmlentities ($row["observacion"]));
					$ls_estado= trim (htmlentities   ($row["estado"]));
					$ls_fecini=$this->io_funcion->uf_formatovalidofecha($row["fecini"]);
				    $ls_fecini=$this->io_funcion->uf_convertirfecmostrar($ls_fecini);
			
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['nropas']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($row['nropas']." ^javascript:aceptar(\"$ls_nropas\",\"$ls_cedpas\", \"$ls_feceval\",\"$ls_apepas\",\"$ls_nompas\",\"$ls_estado\", \"$ls_obs\", \"$ls_res\", \"$ls_nrodestino\", \"$ls_ceddestino\",\"$ls_fecevaldestino\", \"$ls_apedestino\", \"$ls_nomdestino\", \"$ls_estadodestino\",\"$ls_obsdestino\",\"$ls_resdestino\", \"$ls_fecini\", \"$ls_fecinidestino\");^_self"));
					
								
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_feceval));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($row['cedpas']));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_nompas.'  '.$ls_apepas));												
					$row_->appendChild($cell);
					
					}
			 
			
			}
			
            
			return $dom->saveXML();
		
	
		

      
		
	} // end function buscar_evaluacion_pasantia
	

//FUNCIONES PARA MANEJAR LAS METAS DE LA EVALUACION DE PASANTIA

function uf_srh_guardar_meta($ao_evaluacion, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_meta															     					
		//      Argumento: $ao_evaluacion    // arreglo con los datos de los detalle de la evaluación de pasantía				//		        //                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta  una meta en la tabla srh_metas_evaluacion_pasantia		        			   //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 15/11/2007							Fecha Última Modificación: 15/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 $ao_evaluacion->feceval=$this->io_funcion->uf_convertirdatetobd($ao_evaluacion->feceval);
	if (($ao_evaluacion->puntos==0) || ($ao_evaluacion->puntos=='0'))
	 {
	    $ao_evaluacion->puntos=0;
	 }
	 
	  $ls_sql = "INSERT INTO srh_metas_evaluacion_pasantia (nropas,feceval, codmeta, desmeta,resultado,observacion_meta, codemp) ".	  
	            "VALUES ('$ao_evaluacion->nropas','$ao_evaluacion->feceval','$ao_evaluacion->codmeta','$ao_evaluacion->metap','$ao_evaluacion->puntos','$ao_evaluacion->obsmeta','".$this->ls_codemp."')";
			
				

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la meta de la evaluación de pasantía ".$ao_evaluacion->nropas;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->evaluacion_pasantia MÉTODO->uf_srh_guardar_meta ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
	
function uf_srh_eliminar_meta($as_nropas,$as_feceval ,$aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_meta																					//
		//       Argumento: $as_nropas         // numero de la pasntía			                                               //
		//                  $aa_seguridad    //  arreglo de registro de seguridad                                                //
		//	      Returns: Retorna un Booleano																				    //
		//    Description: Funcion que elimina una meta en la tabla srh_metas_evaluacion_pasantia             				   //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 15/11/2007							Fecha Última Modificación: 15/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
	
	  
    $ls_sql = "DELETE FROM srh_metas_evaluacion_pasantia ".
	          " WHERE nropas='$as_nropas' AND feceval='$as_feceval' AND codemp='".$this->ls_codemp."'";
			  
			  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->evaluacion_pasantia MÉTODO->uf_srh_eliminar_meta ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la meta de la evaluación de pasantía ".$as_nropas;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
	return $lb_borro;
	
  }
  
  
function uf_srh_load_evaluacion_pasantia_campos($as_nropas, $as_feceval,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_evaluacion_pasantia_campos
		//	    Arguments: $as_nropas  // código de la evaluacion de pasantia
		//				   $as_fecha   // fecha de la evaluación de pasantía
		//				   $ai_totrows  // total de filas del detalle
		//				   $ao_object  // objetos del detalle
		//	      Returns: $lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de una evaluacion_pasantia
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_feceval=$this->io_funcion->uf_convertirdatetobd($as_feceval);
		$ls_sql="SELECT * ".
				"  FROM srh_metas_evaluacion_pasantia ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND nropas='".$as_nropas."'".
				"   AND feceval='".$as_feceval."'".
				" ORDER BY nropas,codmeta ";
			
				 $rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->evaluacion_pasantia MÉTODO->uf_srh_load_evaluacion_pasantia_campos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_metap= trim (htmlentities ($row["desmeta"]));
				$ls_obsm= trim (htmlentities ($row["observacion_meta"]));
	   			$li_valor=$row["resultado"];
				
				$ao_object[$ai_totrows][1]="<textarea name=txtmetap".$ai_totrows."  cols=45 rows=3 id=txtmetap".$ai_totrows." class=sin-borde onKeyUp='ue_validarcomillas(this);' readonly>".$ls_metap."</textarea>";
				$ao_object[$ai_totrows][2]="<textarea name=txtobsm".$ai_totrows."  cols=45 rows=3 id=txtobsm".$ai_totrows." class=sin-borde onKeyUp='ue_validarcomillas(this);' >".$ls_obsm."</textarea>";
				$ao_object[$ai_totrows][3]="<textarea name=txtvalor".$ai_totrows." cols=7 rows=3 id=txtvalor".$ai_totrows." class=sin-borde  onKeyPress='return validarreal2(event,this);'  onChange='javascript: ue_suma(txtres);' >".$li_valor."</textarea>";
				$ao_object[$ai_totrows][4]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$ao_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt(".$ai_totrows.",txtres,txtvalor".$ai_totrows.");   align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";				
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_evaluacion_pasantia
	//-----------------------------------------------------------------------------------------------------------------------------------	



}// end   class sigesp_srh_c_evaluacion_pasantia
?>