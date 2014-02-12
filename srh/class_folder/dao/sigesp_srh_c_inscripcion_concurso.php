<?PHP


class sigesp_srh_c_inscripcion_concurso 
{
	var $io_sql;
	var $io_msg;
	var $io_funcion;
	var $io_seguridad;
	var $io_fun_nomina;
	var $io_personalnomina;
	var $ls_codemp;
	var $ls_mensaje="No hay Datos";
	//--------------------------------------------------------------------------------------------------------------------------------
	public function sigesp_srh_c_inscripcion_concurso($path)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_personal
		//		   Access: public (sigesp_snorh_d_personal)
		//	  Description: Constructor de la Clase
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		require_once($path."shared/class_folder/sigesp_include.php");
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

	}// end function sigesp_srh_c_inscripcion_concurso
	

/////////////////////////////// FUNCIONES PARA EL MANEJO DEL REGISTRO DE CONCURSANTE  ///////////////////////////////	
	
//--------------------------------------------------------------------------------------------------------------------------------

function uf_srh_select_concursante($as_codcon, $as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_concursante
		//      Argumento: $as_codcon    // codigo del concurso
		//                 $as_codper    // código de la persona
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un requisito de concurso en la tabla de srh_requisitosconcursante
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 19/09/2008							Fecha Última Modificación: 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_concursante  ".
				  " WHERE codcon='".trim($as_codcon)."'".
				  " AND codper='".trim($as_codper)."' ".
				  " AND codemp='".$this->ls_codemp."'" ;
	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_select_concursante ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				
				
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	}  //  end function uf_srh_select_concursante
  //--------------------------------------------------------------------------------------------------------------------------------

 function getCedPersonal($as_codper, $as_codcon,&$ao_datos="")       
{
		 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: getCodPersonal																                    
		//      Argumento: $as_codper   //  cédula del personal										                        
		//                 $as_codcon  //  código del concurso
		//                 $ao_datos   //  arreglo con datos del personal                                         
		//	      Returns: Retorna un XML  																						
		//    Description: Funcion busca un personal en la tabla sno_personal  dado la cédula del personal                    
		//	   Creado Por: Ing. María Beatriz Unda																			    						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_codcon="";
		$ls_sql = " SELECT codcon FROM srh_concursante ".
				  " WHERE codemp='". $this->ls_codemp."'".
				  " AND  codper = '$as_codper' AND codcon = '$as_codcon'";
			
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
				$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->getCedPersonal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			
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
  }	// end function getPersonal
  
//--------------------------------------------------------------------------------------------------------------------------------

function uf_srh_guardarConcursante ($ao_concurso,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarConcursante																										
		//      Argumento: $ao_concurso    // arreglo con los datos del personal										        //
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              //
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta o modifica un participante a un concurso                    			   
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 16/09/2008							Fecha Última Modificación: 16/09/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  		if ($ao_concurso->tipper!='I')
		{$ao_concurso->tipper='E'; }
		
		if ($ao_concurso->estciv=='null')
		{$ao_concurso->estciv='S'; }
				
		if ($ao_concurso->nacper=='null')
		{$ao_concurso->nacper='V'; }
		
		if ($ao_concurso->codpai=='null')
		{$ao_concurso->codpai='058'; }
		
		if ($ao_concurso->codest=='null')
		{$ao_concurso->codest='001'; }		
		
		if ($ao_concurso->sexper=='null')
		{$ao_concurso->sexper='M'; }
		
		if ($ao_concurso->codpro=="")
		{$ao_concurso->codpro='001'; }
		
	    $ao_concurso->fecnac=$this->io_funcion->uf_convertirdatetobd($ao_concurso->fecnac);
	    $ao_concurso->fecreg=$this->io_funcion->uf_convertirdatetobd($ao_concurso->fecreg);
	 
	   if ($ao_concurso->fecnac =="" )
	   {
			$ao_concurso->fecnac= '1900-01-01';
	   }
	
	   $as_codper=$ao_concurso->codper;
	   $as_codcon=$ao_concurso->codcon;
	
  	  if ($as_operacion == "modificar")
	  {
	 	 $this->io_sql->begin_transaction();
		 
	 
		  $ls_sql = "UPDATE srh_concursante SET ".
			"codper= '$ao_concurso->codper', ".
			"nomper= '$ao_concurso->nomper', ".
			"apeper= '$ao_concurso->apeper', ".
			"dirper= '$ao_concurso->dirper', ".
			"fecnacper= '$ao_concurso->fecnac', ".
			"edocivper= '$ao_concurso->estciv', ".
			"nacper= '$ao_concurso->nacper', ".
			"codpai= '$ao_concurso->codpai', ".
			"codest= '$ao_concurso->codest', ".
			"telhabper= '$ao_concurso->telhab', ".
			"telmovper= '$ao_concurso->telmov', ".
			"codpro= '$ao_concurso->codpro', ".
			"coreleper= '$ao_concurso->corele', ".
			"nivacaper= '$ao_concurso->nivaca', ".
			"tipper= '$ao_concurso->tipper', ".
			"sexper= '$ao_concurso->sexper' ".	
			"WHERE codper='$ao_concurso->codper' AND codcon='$ao_concurso->codcon' AND codemp='".$this->ls_codemp."'" ;
	}
	else
	{ 
	  $this->io_sql->begin_transaction();
	
	  $ls_sql = "INSERT INTO srh_concursante(codcon, fecreg, codper, nomper, apeper, dirper, fecnacper, ".
	            " edocivper, codpai, codest,  nacper, telhabper, telmovper, sexper, coreleper, codpro, nivacaper, tipper,estconper, codemp) ".	  
	            "VALUES ('$ao_concurso->codcon', '$ao_concurso->fecreg', '$ao_concurso->codper', '$ao_concurso->nomper', ".
				" '$ao_concurso->apeper', '$ao_concurso->dirper', '$ao_concurso->fecnac', '$ao_concurso->estciv', ".
				" '$ao_concurso->codpai', '$ao_concurso->codest', '$ao_concurso->nacper',   ".
				" '$ao_concurso->telhab', '$ao_concurso->telmov', '$ao_concurso->sexper','$ao_concurso->corele','$ao_concurso->codpro','$ao_concurso->nivaca','$ao_concurso->tipper','1', '".$this->ls_codemp."')";
				
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_guardarConcursante ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				if ($as_operacion == "modificar")
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="UPDATE";
					$ls_descripcion ="Modifico la inscripcion a concurso de la persona ".$as_codper." en el concurso ".$as_codcon;
					$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="INSERT";
						$ls_descripcion ="Inserto la inscripcion a concurso de la persona ".$as_codper." en el concurso ".$as_codcon;
						$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
				  ////////////////////////////////         SEGURIDAD               /////////////////////////////		
				
				}
				$this->io_sql->commit();
		}
		
	
	return $lb_guardo;
  } //end  function uf_srh_guardarConcursante

//--------------------------------------------------------------------------------------------------------------------------------

 function uf_select_concursante_requisitos_minimos ($as_codcon,$as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_concursante_requisitos_minimos
		//		   Access: private
 		//	    Arguments: as_codcon // código del concurso
		//                 as_codper // código del personal
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el registro de concursante puede ser eliminado
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 23/09/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT srh_requisitos_minimos.*  ".
				 "  FROM srh_requisitos_minimos ".
				 "  WHERE srh_requisitos_minimos.codemp='".$this->ls_codemp."' ".
				 "    AND trim(codcon)='".$as_codcon."' AND trim(codper)='".trim($as_codper)."' ";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_select_concursante_requisitos_minimos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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

//--------------------------------------------------------------------------------------------------------------------------------

 function uf_select_concursante_evaluacion_psicologica ($as_codcon,$as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_concursante_evaluacion_psicologica
		//		   Access: private
 		//	    Arguments: as_codcon // código del concurso
		//                 as_codper // código del personal
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el registro de concursante puede ser eliminado
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 23/09/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT *  ".
				 "  FROM srh_evaluacion_psicologica ".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND trim(codcon)='".$as_codcon."' AND trim(codper)='".trim($as_codper)."' ";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_select_concursante_evaluacion_psicologica ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
//--------------------------------------------------------------------------------------------------------------------------------

 function uf_select_concursante_entrevista_tecnica ($as_codcon,$as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_concursante_entrevista_tecnica
		//		   Access: private
 		//	    Arguments: as_codcon // código del concurso
		//                 as_codper // código del personal
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el registro de concursante puede ser eliminado
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 23/09/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT *  ".
				 "  FROM srh_entrevista_tecnica ".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND trim(codcon)='".$as_codcon."' AND trim(codper)='".trim($as_codper)."' ";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_select_concursante_entrevista_tecnica ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
//--------------------------------------------------------------------------------------------------------------------------------

 function uf_select_dt_ganadores_concurso ($as_codcon,$as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_dt_ganadores_concurso
		//		   Access: private
 		//	    Arguments: as_codcon // código del concurso
		//                 as_codper // código del personal
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el registro de concursante puede ser eliminado
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 23/09/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT *  ".
				 "  FROM srh_dt_ganadores_concurso ".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND trim(codcon)='".$as_codcon."' AND trim(codper)='".trim($as_codper)."' ";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_select_concursante_dt_ganadores_concurso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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

//--------------------------------------------------------------------------------------------------------------------------------

 function uf_select_resultados_evaluacion_aspirante ($as_codcon,$as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_resultados_evaluacion_aspirante
		//		   Access: private
 		//	    Arguments: as_codcon // código del concurso
		//                 as_codper // código del personal
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el registro de concursante puede ser eliminado
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 23/09/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT *  ".
				 "  FROM srh_resultados_evaluacion_aspirante ".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND trim(codcon)='".$as_codcon."' AND trim(codper)='".trim($as_codper)."' ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_select_concursante_resultados_evaluacion_aspirante ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	
//--------------------------------------------------------------------------------------------------------------------------------

function uf_srh_eliminarConcursante($as_codcon, $as_codper, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarConcursante																												
		//      Argumento: $as_codcon        // código del concurso	
		//                 $as_coder        // código del personal									        
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina una inscripcion a un concurso                         
		//	   Creado Por: Maria Beatriz Unda																				 
		// Fecha Creación: 17/09/2008							Fecha Última Modificación: 					
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   
     $lb_valido=false;
	 $lb_existe=true;
	
	if (($this->uf_select_resultados_evaluacion_aspirante ($as_codcon,$as_codper)===false)&&
		 ($this->uf_select_dt_ganadores_concurso ($as_codcon,$as_codper)===false)&&
		 ($this->uf_select_concursante_entrevista_tecnica($as_codcon, $as_codper)===false) &&
		 ($this->uf_select_concursante_evaluacion_psicologica ($as_codcon,$as_codper)===false)&&
		 ($this->uf_select_concursante_requisitos_minimos ($as_codcon,$as_codper)===false))
	 {
        $lb_existe=false;
		$this->io_sql->begin_transaction();	
		
		$lb_valido=$this->uf_srh_eliminar_detalles_cocursante($as_codper,$as_codcon);
		if ($lb_valido)
		{
		
			$ls_sql = "DELETE FROM srh_concursante ".
					  "WHERE codcon = '$as_codcon' AND codper='$as_codper'   AND codemp='".$this->ls_codemp."'";
					  
					  
			$lb_borro=$this->io_sql->execute($ls_sql);
			if($lb_borro===false)
			 {
				$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_eliminarConcursante ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			 }
			else
			 {
						$lb_valido=true;
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Eliminó la inscripcion a concurso de la persona ".$as_codper." en el concurso ".$as_codcon;
						$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////			
						
						
							$this->io_sql->commit();
						
						
			}
		}
	}
	else
	{
	  $lb_existe=true;
	  $lb_valido=false;
	}
		
	return array($lb_valido,$lb_existe);
	
  }
	
//--------------------------------------------------------------------------------------------------------------------------------

function uf_srh_eliminar_detalles_cocursante($as_codper,$as_codcon)
{

 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_detalles_cocursante																											
		//      Argumento: $as_codper       // código del personal									        
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                              
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina los detalles (estudios, cursos, familiares y trabajos) asociados a un concursante
		//	   Creado Por: Maria Beatriz Unda																				   
		// Fecha Creación: 16/09/2008							Fecha Última Modificación: 						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_sql = "DELETE FROM srh_estudiosconcursante ".
					  "WHERE codper = '$as_codper' AND codcon='$as_codcon'   AND codemp='".$this->ls_codemp."'";
				  
		$lb_borro=$this->io_sql->execute($ls_sql);
		if($lb_borro===false)
		 {
			$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_eliminar_detalles_cocursante1 ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;			
		 }
		else
		 {
		 
		 	$ls_sql = "DELETE FROM srh_cursosconcursante ".
					  "WHERE codper = '$as_codper' AND codcon='$as_codcon'   AND codemp='".$this->ls_codemp."'";
					  
			$lb_borro=$this->io_sql->execute($ls_sql);
			if($lb_borro===false)
			 {
				$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_eliminar_detalles_cocursante2 ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;			
		 	}
			else
			{
				$ls_sql = "DELETE FROM srh_trabajosconcursante ".
					  "WHERE codper = '$as_codper' AND codcon='$as_codcon'   AND codemp='".$this->ls_codemp."'";
					  
				$lb_borro=$this->io_sql->execute($ls_sql);
				if($lb_borro===false)
				 {
					$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_eliminar_detalles_cocursante3 ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;			
				}
				else
				{
					$ls_sql = "DELETE FROM srh_familiaresconcursante ".
					  "WHERE codper = '$as_codper' AND codcon='$as_codcon'   AND codemp='".$this->ls_codemp."'";
					  
					$lb_borro=$this->io_sql->execute($ls_sql);
					if($lb_borro===false)
					 {
						$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_eliminar_detalles_cocursante4 ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
						$lb_valido=false;			
					}
					else
					{
						$ls_sql = "DELETE FROM srh_requisitosconcursante ".
						 		   "WHERE codper = '$as_codper' AND codcon='$as_codcon' AND codcon='$as_codcon'  AND codemp='".$this->ls_codemp."'";
						  
						$lb_borro=$this->io_sql->execute($ls_sql);
						if($lb_borro===false)
						 {
							$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_eliminar_detalles_cocursante5 ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
							$lb_valido=false;			
						}
					}
				
				}
				
			}
		 
		 }

return $lb_borro;
}

//--------------------------------------------------------------------------------------------------------------------------------

function uf_srh_buscar_concursante($as_codcon,$as_codper,$as_apeper,$as_nomper)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_concursante																		
		//         access: public (sigesp_sno_personal)												                    
		//      Argumento: $as_codcon   //  código del concurso								                        
		//                 $as_codper   //  cedula del personal                                                              
		//                 $as_apeper   //  apellido del personal                                                           
		//                 $as_nomper   //  nombre del personal                                                             
		//	      Returns: Retorna un XML  																						
		//    Description: Funcion busca una personal que es participante a un concurso  
		//                  los datos en el catalogo                                                                           
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 21/01/2007							Fecha Última Modificación: 21/01/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
		$ls_codcondestino="txtcodcon";
		$ls_descondestino="txtdescon";
		$ls_codcardestino="txtcodcar";
		$ls_descardestino="txtdescar";
		$ls_cantcardestino="txtcantcar";
		$ls_tipodestino="txtcodtipconcur";
		$ls_fechaaperdestino="txtfechaaper";
		$ls_fechaciedestino="txtfechacie";
		$ls_codperdestino="txtcodper";
		$ls_fecregdestino="txtfecreg";
		$ls_apeperdestino="txtapeper";
		$ls_nomperdestino="txtnomper";
		$ls_sexperdestino="cmbsexper";
		$ls_fecnacperdestino="txtfecnacper";
		$ls_telhabperdestino="txttelhabper";	
		$ls_codpaidestino="cmbcodpainac";
		$ls_codestdestino="hidcodestnac";
		$ls_dirperdestino="txtdirper";				
		$ls_telmovperdestino="txttelmovper";
		$ls_edocivperdestino="cmbedocivper";
		$ls_nacperdestino="cmbnacper";
		$ls_nivacadestino="cmbnivacaper";
		$ls_codprodestino="txtcodpro";
		$ls_desprodestino="txtdespro";
		$ls_coreledestino="txtcoreleper";
	
		$lb_valido=true;
					
		$ls_sql= " SELECT srh_concursante.*, sigesp_pais.despai, sigesp_estados.desest, ".
				 " sno_profesion.despro, srh_concurso.*,  ".
				 " sno_cargo.descar, sno_cargo.codcar, sno_asignacioncargo.denasicar, ".
		         " sno_asignacioncargo.codasicar ".
		 		 " FROM sigesp_pais, sigesp_estados,sno_profesion, srh_concursante,srh_concurso ".
				 " LEFT JOIN sno_cargo ON (srh_concurso.codcar = sno_cargo.codcar AND srh_concurso.codnom = sno_cargo.codnom) ".
				 " LEFT JOIN sno_asignacioncargo ON (srh_concurso.codcar = sno_asignacioncargo.codasicar ".
				 " AND srh_concurso.codnom = sno_asignacioncargo.codnom) ".
				 " WHERE trim(codper) like trim('$as_codper') ".
				 " AND srh_concursante.codcon like '$as_codcon' ".
			 	 " AND nomper like '$as_nomper' ".
				 " AND apeper like '$as_apeper' ".
				 " AND sigesp_pais.codpai = srh_concursante.codpai ".
				 " AND srh_concurso.codcon = srh_concursante.codcon ".
				 " AND sigesp_estados.codpai = srh_concursante.codpai ".
				 " AND sigesp_estados.codest = srh_concursante.codest ".				
				 " AND sno_profesion.codpro = srh_concursante.codpro ".
				 " ORDER BY srh_concursante.codcon,srh_concursante.codper";

	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_buscar_concursante( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
					$ls_codcon=$row["codcon"];
					$ls_descon=$row["descon"];
					
					$ls_codcar1=$row["codasicar"];
					$ls_codcar2=$row["codcar"];
					
					 if ($ls_codcar1=="")
					 {	
					 	$ls_codcar=$row["codcar"];
						$ls_descar=trim ( htmlentities ($row["descar"]));
					 }
					 else
					 {
					   	$ls_descar=trim (htmlentities ($row["denasicar"]));
					    $ls_codcar=$row["codasicar"];
						
					 }
					
					
					$ls_cantcar=$row["cantcar"];
					$ls_tipo=trim ($row["tipo"]);
					
					$ls_fechaaper=$this->io_funcion->uf_formatovalidofecha($row["fechaaper"]);
				    $ls_fechaaper=$this->io_funcion->uf_convertirfecmostrar($ls_fechaaper);
					
					$ls_fechacie=$this->io_funcion->uf_formatovalidofecha($row["fechacie"]);
				    $ls_fechacie=$this->io_funcion->uf_convertirfecmostrar($ls_fechacie);
					
						
					$ls_fecreg=$this->io_funcion->uf_formatovalidofecha($row["fecreg"]);
				    $ls_fecreg=$this->io_funcion->uf_convertirfecmostrar($ls_fecreg);
					
					$ls_codper=$row["codper"];
					
					$ls_fecnacper=$this->io_funcion->uf_formatovalidofecha($row["fecnacper"]);
				    $ls_fecnacper=$this->io_funcion->uf_convertirfecmostrar($ls_fecnacper);
					
					$ls_apeper=trim (htmlentities ($row["apeper"]));
					$ls_nomper=trim (htmlentities ($row["nomper"]));
					$ls_sexper=$row["sexper"];
					$ls_telhabper=$row["telhabper"];	
					$ls_codpai=$row["codpai"];
					$ls_codest=$row["codest"];
					$ls_dirper=$row["dirper"];				
					$ls_telmovper=$row["telmovper"];
					$ls_edocivper=$row["edocivper"];
					$ls_nacper=$row["nacper"];
					$ls_codpro=$row["codpro"];
					$ls_despro=trim (htmlentities ($row["despro"]));
					$ls_nivaca=$row["nivacaper"];
					$ls_corele=$row["coreleper"];
					
					
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codcon']);
					$cell = $row_->appendChild($dom->createElement('cell'));
					   					
					$cell->appendChild($dom->createTextNode($row['codcon']." ^javascript:aceptar(\"$ls_codcon\",\"$ls_descon\",\"$ls_codcar\",\"$ls_cantcar\",\"$ls_tipo\",\"$ls_fechaaper\",\"$ls_fechacie\",\"$ls_codper\",\"$ls_fecreg\",\"$ls_apeper\",\"$ls_nomper\",\"$ls_sexper\",\"$ls_fecnacper\",\"$ls_telhabper\",\"$ls_codpai\",\"$ls_codest\",\"$ls_dirper\",\"$ls_telmovper\",\"$ls_edocivper\",\"$ls_nacper\",\"	$ls_codcondestino\", \"$ls_descondestino\", \"$ls_codcardestino\", \"$ls_cantcardestino\", \"$ls_tipodestino\", \"$ls_fechaaperdestino\", \"$ls_fechaciedestino\", \"$ls_codperdestino\", \"$ls_fecregdestino\", \"$ls_apeperdestino\", \"$ls_nomperdestino\", \"$ls_sexperdestino\", \"$ls_fecnacperdestino\", \"$ls_telhabperdestino\", \"$ls_codpaidestino\", \"$ls_codestdestino\", \"$ls_dirperdestino\" ,\"$ls_telmovperdestino\", \"$ls_edocivperdestino\",\"$ls_nacperdestino\",\"$ls_descar\",\"$ls_descardestino\",\"$ls_codpro\", \"$ls_despro\", \"$ls_nivaca\", \"$ls_corele\",\"$ls_codprodestino\", \"$ls_desprodestino\", \"$ls_nivacadestino\", \"$ls_coreledestino\");^_self"));
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($row['codper']));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($row["apeper"]))));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($row["nomper"]))));												
					$row_->appendChild($cell);
			}
			return $dom->saveXML();
		}
   
		
	} // end function buscar_personal
	
//-----------------------------------------------------------------------------------------------------------------------------


/////////////////////////////// FUNCIONES PARA EL MANEJO DE LOS ESTUDIOS DEL PERSONAL  ///////////////////////////////

	  
//----------------------------------------------------------------------------------------------------------------------------
function uf_srh_getProximoCodigo_estudio($as_codper,$as_codcon)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_srh_getProximoCodigo_estudio
	//         Access: public (sigesp_srh_d_personal)
	//      Argumento: 
	//	      Returns: Retorna el nuevo código de estudio
	//    Description: Funcion que genera un código nuevo de estudios
	//	   Creado Por: Ing. María Beatriz Unda
	// Fecha Creación:17/03/2008							Fecha Última Modificación:
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$ls_sql = "SELECT MAX(codestper) AS codigo FROM srh_estudiosconcursante ".
	          " WHERE codper = '".trim($as_codper)."' AND codcon='".$as_codcon."' ";
	
	$lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
		if (!$lb_hay)
		  $ls_codest = $la_datos["codigo"][0];///si no tiene esetudios.......
 
		if ($lb_hay)
		  $ls_codest = $la_datos["codigo"][0]+1; 
	return $ls_codest;
 } 
	 
//----------------------------------------------------------------------------------------------------------------------------
	function uf_srh_guardar_estudios($ao_estudio,$as_operacion="insertar", $aa_seguridad)
	{ 
	    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_estudio
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de estudio
		//    Description: Funcion que guarda los estudios del personal
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación:19/03/2008							Fecha Última Modificación:
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
		$lb_valido=true;
		if ($as_operacion == "modificar")
		{
		 $this->io_sql->begin_transaction();
		 
		  $ls_sql="UPDATE srh_estudiosconcursante ".
					"   SET nivestper='$ao_estudio->nivel', ".
					"		insestper='$ao_estudio->insestper', ".
					"		carestper='$ao_estudio->carrera', ".
					"		anofinestper=$ao_estudio->anofin, ".
					"		anoaprestper=$ao_estudio->anoapr, ".
					"		titestper='$ao_estudio->titulo' ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='$ao_estudio->codper' ".
					"   AND codcon='$ao_estudio->codcon' ".
					"   AND codestper='$ao_estudio->codestper'";
		}
		else
		{ 
		
		$this->io_sql->begin_transaction();
		
		
		 $ls_sql="INSERT INTO srh_estudiosconcursante".
					"(codemp,codper,codcon,codestper,nivestper,insestper,carestper,anofinestper,anoaprestper,titestper)".
					"VALUES('".$this->ls_codemp."','$ao_estudio->codper','$ao_estudio->codcon','$ao_estudio->codestper',".
					" '$ao_estudio->nivel', '$ao_estudio->insestper','$ao_estudio->carrera',".
					" $ao_estudio->anofin,$ao_estudio->anoapr,'$ao_estudio->titulo')"; 	
		
		}
		$lb_guardo = $this->io_sql->execute($ls_sql);
	
		if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_guardar_estudios ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			if ($as_operacion == "modificar")
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó el Estudio Realizado ".$ao_estudio->codestper." asociado al concursante ".$ao_estudio->codper;
				$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Estudio Realizado ".$ao_estudio->codestper." asociado al concursante ".$ao_estudio->codper;
				$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}
			
			
			$this->io_sql->commit();
		}
	
		return $lb_guardo;
		
}// end function uf_srh_guardar
  	
//-------------------------------------------------------------------------------------------------------------------------------
	
	function uf_srh_buscar_estudios_concursante($as_codper,$as_codcon)
	{		
	    				/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_estudios
		//		   Access: private
		//	    Arguments: as_codper  // código del personal
		//	  Description: Funcion que buscas los estudios realizados por un Personal dado el código del perosnal y
		//                 crea un XML para mostrarlo en un catalogo
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$ls_codestperdestino="txtcodestper";
	    $ls_tipestareadestino="cmbtipestper";
	    $ls_insestperdestino="txtinsestper";
   		$ls_carestperdestino="txtcar";
	    $ls_anofindestino="txtanofin";
	    $ls_anoaprdestino="txtanoapr";
   		$ls_titestperdestino="chktit";
			
		$lb_valido=true;
		
		$ls_sql= "select * from srh_estudiosconcursante where codper='".trim($as_codper)."'  AND codcon='".$as_codcon."' ".
		         "ORDER BY codestper"; 
		
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_buscar_estudios( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}		
		 else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);	
			 		
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			    $ls_codestper=$row["codestper"];
				$ls_tipestarea=$row["nivestper"];
				$ls_insestper=htmlentities ($row["insestper"]);
				$ls_carestper=htmlentities ($row["carestper"]);
				$ls_titestper=$row["titestper"];
				$ls_anofin=$row["anofinestper"];
				$ls_anoapr=$row["anoaprestper"];
					
				$row_ = $team->appendChild($dom->createElement('row'));
				$row_->setAttribute("id",$row['codestper']);
				$cell = $row_->appendChild($dom->createElement('cell'));   
						
			
				$cell->appendChild($dom->createTextNode($row['codestper']." ^javascript:aceptar
				( \"$ls_codestper\",  \"$ls_tipestarea\",  \"$ls_insestper\",  \"$ls_carestper\",  \"$ls_titestper\",  \"$ls_anofin\", \"$ls_anoapr\",  \"$ls_codestperdestino\",  \"$ls_tipestareadestino\",  \"$ls_insestperdestino\",  \"$ls_carestperdestino\",  \"$ls_titestperdestino\",  \"$ls_anofindestino\", \"$ls_anoaprdestino\");^_self"));
				switch ($ls_tipestarea)
				{
					case '0':
				  	$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode('Primaria'));												
					$row_->appendChild($cell);
				 	break;
					case '1':
				  	$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode('Ciclo Basico'));												
					$row_->appendChild($cell);
				 	break;
					case '2':
				  	$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode('Ciclo Diversificado'));												
					$row_->appendChild($cell);
				 	break;
					case '3':
				  	$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode('Pre grado'));												
					$row_->appendChild($cell);
				 	break;
					case '4':
				  	$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode('Espeializacion'));												
					$row_->appendChild($cell);
				 	break;
					case '5':
				  	$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode('Maestria'));												
					$row_->appendChild($cell);
				 	break;
					case '6':
				  	$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode('Post Grado'));												
					$row_->appendChild($cell);
				 	break;
					case '7':
				  	$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode('Doctorado'));												
					$row_->appendChild($cell);
				 	break;  
				}
				
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_carestper));												
				$row_->appendChild($cell);
			
			}
			return $dom->saveXML();
		
		}	   
	} 
	
//-------------------------------------------------------------------------------------------------------------------------------
	function uf_srh_eliminar_estudio ($as_codest, $as_codper, $as_codcon ,$aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_elimnar_estudio																													
		//      Argumento: $as_codest        //  código del estudio
		//                 $as_codper        // código del personal
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina un estudio realizado en la tabla srh_estudiosconcursante                        
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 06/05/2008							Fecha Última Modificación: 						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
	
    $ls_sql = "DELETE FROM srh_estudiosconcursante ".
	          "WHERE codestper = '$as_codest' AND codper = '$as_codper' AND codcon='$as_codcon'   AND codemp='".$this->ls_codemp."'";

  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_elimnar_estudio ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
		$lb_valido=true;
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="DELETE";
		$ls_descripcion ="Eliminó el estudio realizado del concursante".$as_codper;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////			
		
			$this->io_sql->commit();
	}
	
	return $lb_valido;
  }
//-------------------------------------------------------------------------------------------------------------------------------

/////////////////////////////// FUNCIONES PARA EL MANEJO DE LOS CURSOS DEL CONCURSANTE  ///////////////////////////////


function uf_srh_getProximoCodigo_curso($as_codper, $as_codcon)
  {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getProximoCodigo_curso
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de un curso de un concursante
		//    Description: Funcion que genera un código de un curso de un concursante
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:16/09/2008							Fecha Última Modificación:16/09/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(codcurper) AS codigo FROM srh_cursosconcursante ".
	           "WHERE codper = '".trim($as_codper)."' AND codcon= '".$as_codcon."' ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_codcur = $la_datos["codigo"][0]+1;
	return $ls_codcur;
  }

//-------------------------------------------------------------------------------------------------------------------------------

function uf_srh_guardar_cursos ($ao_cursos,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_cursos																	
		//         access: public (sigesp_srh_cursosconcursante)
	  	//      Argumento: $ao_cursos    // arreglo con los datos de los cursos 								
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica un curso de un concursante          
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 07/12/2007							Fecha Última Modificación: 07/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	$lb_valido=true;
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	  $ls_sql = "UPDATE srh_cursosconcursante SET ".
		  		"descurper = '$ao_cursos->descur' , ".
	            "horcurper = '$ao_cursos->horcur' ".
	            "WHERE codcurper= '$ao_cursos->codcur' AND codper='$ao_cursos->codper' ".
				"AND codcon='$ao_cursos->codcon' AND codemp='".$this->ls_codemp."'" ;
		  
	}
	else
	{ $this->io_sql->begin_transaction();
	
	
	  $ls_sql = "INSERT INTO srh_cursosconcursante (codper, codcon, codcurper, descurper, horcurper, codemp) ".	  
	            "VALUES ('$ao_cursos->codper','$ao_cursos->codcon','$ao_cursos->codcur','$ao_cursos->descur', '$ao_cursos->horcur','".$this->ls_codemp."')";
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

    if($lb_guardo===false)
	{
		$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_guardar_cursos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	}
	else
	{
		$this->io_sql->commit();
		
		if ($as_operacion == "modificar")
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el curso ".$ao_cursos->codcur." realizado por la persona ".$ao_cursos->codper;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el curso ".$ao_cursos->codcur." realizado por la persona ".$ao_cursos->codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		
		}
	}

	return $lb_guardo;
 }
	
//-------------------------------------------------------------------------------------------------------------------------------
		
function uf_srh_eliminar_cursos ($as_codcur, $as_codper,$as_codcon, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_cursos																													
		//      Argumento: $as_codcur       //  código del curso
		//                 $as_codper        // código del personal
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina un curso realizado en la tabla srh_estudiosconcursante                        
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 16/09/2008							Fecha Última Modificación: 						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
	
    $ls_sql = "DELETE FROM srh_cursosconcursante ".
	          "WHERE codcurper = '$as_codcur' AND codper = '$as_codper' AND codcon='$as_codcon'   AND codemp='".$this->ls_codemp."'";
			  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_eliminar_cursos	 ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
		$lb_valido=true;
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="DELETE";
		$ls_descripcion ="Elimino el curso ".$as_codcur." realizado por la persona ".$as_codper;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////			
		
			$this->io_sql->commit();
	}
	
	return $lb_valido;
  }
  
//-------------------------------------------------------------------------------------------------------------------------------
  

function uf_srh_buscar_cursos_concursante($as_codper, $as_codcon)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_cursos_concursantel
		//         Access: private
		//      Argumento: $as_codper // codigo del concursante
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca la información de los cursos realizados por un concursante
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 16/09/2008							Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_codcurdestino="txtcodcur";
		$ls_descurdestino="txtdescur";
		$ls_horcurdestino="cmbhorcur";
	
		$lb_valido=true;
		$ls_sql="SELECT * FROM srh_cursosconcursante".          
				" WHERE codper = '".trim($as_codper)."' ".
				" AND codcon='".$as_codcon."' ".
			   " ORDER BY codcurper";
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_buscar_cursos_concursante (ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);		
				
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
					$ls_codcur=$row["codcurper"];
					$ls_descur= htmlentities ($row["descurper"]);
					$ls_horcur=trim ($row["horcurper"]);
					
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codcurper']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codcurper']." ^javascript:aceptar(\"$ls_codcur\",\"$ls_descur\",
					\"$ls_codcurdestino\",\"$ls_descurdestino\",\"$ls_horcur\",\"$ls_horcurdestino\");^_self"));
				
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_descur));												
					$row_->appendChild($cell);
								
			}
			return $dom->saveXML();
		
		}
      
		
} // end function uf_srh_buscar_cursos_concursante
//-------------------------------------------------------------------------------------------------------------------------------


///////////////////////////// FUNCIONES PARA EL MANEJO DE LA EXPERIENCIA LABORAL DEL CONCURSANTE  /////////////////////////////


function uf_srh_getProximoCodigo_trabajo($as_codper, $as_codcon)
  {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getProximoCodigo_trabajo
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de un trabajo del concursante
		//    Description: Funcion que genera un código de un trabajo del concursante
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:16/09/2008							Fecha Última Modificación:16/09/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(codtraper) AS codigo FROM srh_trabajosconcursante ".
	          "WHERE codper = '".trim($as_codper)."' AND codcon='".$as_codcon."' ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_codtrab = $la_datos["codigo"][0]+1;
    return $ls_codtrab;
  }

//-------------------------------------------------------------------------------------------------------------------------------

function uf_srh_guardar_trabajo ($ao_trabajos,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_trabajo																
		//         access: public (sigesp_srh_cursosconcursante)
	  	//      Argumento: $ao_trabajos    // arreglo con los datos de los trabajos								
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica una trabajo de un concursante            
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 16/09/2008						Fecha Última Modificación: 						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	$lb_valido=true;
	
	$ao_trabajos->fecingtraant=$this->io_funcion->uf_convertirdatetobd($ao_trabajos->fecingtraant);
	$ao_trabajos->fecrettraant=$this->io_funcion->uf_convertirdatetobd($ao_trabajos->fecrettraant);
	 
   if ($ao_trabajos->fecrettraant=="" )
   {
		$ao_trabajos->fecrettraant= '1900-01-01';
   }
   
   if ($ao_trabajos->fecingtraant=="" )
   {
		$ao_trabajos->fecingtraant= '1900-01-01';
   }
	
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	  $ls_sql = "UPDATE srh_trabajosconcursante SET ".
		  		"emptraper = '$ao_trabajos->emptraant' , ".
	            "cartraant = '$ao_trabajos->ultcartraant', ".
				"fecingtraper = '$ao_trabajos->fecingtraant', ".
				"fecegrtraper = '$ao_trabajos->fecrettraant' ".
	            "WHERE codtraper= $ao_trabajos->codtraant AND codper='$ao_trabajos->codper' ".
				"AND codcon='$ao_trabajos->codcon' AND codemp='".$this->ls_codemp."'" ;
		    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	
	  $ls_sql = "INSERT INTO srh_trabajosconcursante (codper, codcon, codtraper, emptraper, cartraant, fecingtraper, fecegrtraper, codemp) ".	  
	            "VALUES ('$ao_trabajos->codper','$ao_trabajos->codcon',$ao_trabajos->codtraant,'$ao_trabajos->emptraant','$ao_trabajos->ultcartraant', '$ao_trabajos->fecingtraant', '$ao_trabajos->fecrettraant','".$this->ls_codemp."')";
				
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

    if($lb_guardo===false)
	{
		$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_guardar_trabajo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	}
	else
	{
		$this->io_sql->commit();
		if ($as_operacion == "modificar")
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el trabajo ".$ao_trabajos->codtraant." realizado por la persona ".$ao_trabajos->codper;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el trabajo ".$ao_trabajos->codtraant." realizado por la persona ".$ao_trabajos->codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
	}

	return $lb_guardo;
 }
	
//-------------------------------------------------------------------------------------------------------------------------------
		
function uf_srh_eliminar_trabajo ($as_codtra, $as_codper, $as_codcon,$aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_trabajo																													
		//      Argumento: $as_codtra      //  código del trabajo
		//                 $as_codper        // código del personal
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina un trabajo de un concursante                        
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 16/09/2008							Fecha Última Modificación: 						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
	
    $ls_sql = "DELETE FROM srh_trabajosconcursante ".
	          "WHERE codtraper = '$as_codtra' AND codper = '$as_codper' AND codcon='$as_codcon'   AND codemp='".$this->ls_codemp."'";
			  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_eliminar_trabajo	 ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
		$lb_valido=true;
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="DELETE";
		$ls_descripcion ="Elimino el trabajo ".$as_codtra." realizado por la persona ".$as_codper;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////			
		
			$this->io_sql->commit();
	}
	
	return $lb_valido;
  }
  
//-------------------------------------------------------------------------------------------------------------------------------
  

function uf_srh_buscar_trabajos_concursantes($as_codper,$as_codcon)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_trabajos_concursantes
		//         Access: private
		//      Argumento: $as_codper // codigo del concursante
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca la información de los trabajos realizados por un concursante
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 16/09/2008							Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		 $ls_codperdestino="txtcedper";
		 $ls_codtradestino="txtcodtraant";
		 $ls_codempdestino="txtemptraant";
		 $ls_codcardestino="txtultcartraant";
		 $ls_fecingdestino="txtfecingtraant";
		 $ls_fecegrdestino="txtfecrettraant";
	
		$lb_valido=true;
		$ls_sql="SELECT * FROM srh_trabajosconcursante ".          
				" WHERE codper = '".trim($as_codper)."' ".
				" AND codcon='".$as_codcon."' ".
			   " ORDER BY codtraper";
	
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_buscar_trabajos_concursantes (ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);		
				
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
				
				$ls_fecing=$this->io_funcion->uf_formatovalidofecha($row["fecingtraper"]);
				$ls_fecing=$this->io_funcion->uf_convertirfecmostrar($ls_fecing);
				
				$ls_fecegr=$this->io_funcion->uf_formatovalidofecha($row["fecegrtraper"]);
				$ls_fecegr=$this->io_funcion->uf_convertirfecmostrar($ls_fecegr);
				
				$ls_codper=$row["codper"];
				$ls_codtra=$row["codtraper"];
				$ls_codemp=$row["emptraper"];
				$ls_codcar=$row["cartraant"];
				
				$row_ = $team->appendChild($dom->createElement('row'));
				$row_->setAttribute("id",$row['codtraper']);
				$cell = $row_->appendChild($dom->createElement('cell'));   
				$cell->appendChild($dom->createTextNode($row['codtraper']." ^javascript:aceptar(\"$ls_codtra\", \"$ls_codemp\",\"$ls_codcar\",\"$ls_fecing\", \"$ls_fecegr\",\"$ls_codtradestino\", \"$ls_codempdestino\",\"$ls_codcardestino\",\"$ls_fecingdestino\",\"$ls_fecegrdestino\");^_self"));
			
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_codemp));												
				$row_->appendChild($cell);
				
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_codcar));												
				$row_->appendChild($cell);
								
			}
			return $dom->saveXML();
		
		}
      
		
} // end function uf_srh_buscar_trabajos_concursantes
//-------------------------------------------------------------------------------------------------------------------------------


///////////////////////////// FUNCIONES PARA EL MANEJO DE LOS FAMILIARES CONCURSANTE  /////////////////////////////


function uf_srh_getProximoCodigo_familiar($as_codper, $as_codcon)
  {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getProximoCodigo_familiar
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de un familiar del concursante
		//    Description: Funcion que genera un código de un familiar del concursante
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:16/09/2008							Fecha Última Modificación:16/09/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(codfamper) AS codigo FROM srh_familiaresconcursante ".
	          " WHERE codper = '".trim($as_codper)."' AND codcon='".$as_codcon."' ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_codtrab = $la_datos["codigo"][0]+1;
	return $ls_codtrab;
  }

//-------------------------------------------------------------------------------------------------------------------------------

function uf_srh_guardar_familiar ($ao_familia,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_trabajo																
		//         access: public (sigesp_srh_cursosconcursante)
	  	//      Argumento: $ao_familia    // arreglo con los datos de los trabajos								
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica una trabajo de un concursante            
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 16/09/2008						Fecha Última Modificación: 						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	$lb_valido=true;
	$ao_familia->fecnacfam=$this->io_funcion->uf_convertirdatetobd($ao_familia->fecnacfam);
	  
   if ($ao_familia->fecnacfam=="" )
   {
		$ao_familia->fecnacfam= '1900-01-01';
   }
   
   if ($ao_familia->cedfam=="" )
   {
		$ao_familia->cedfam= $ao_familia->codfam;
   }
	
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	  $ls_sql = "UPDATE srh_familiaresconcursante SET ".
		  		"nomfamper = '$ao_familia->nomfam' , ".
	            "apefamper = '$ao_familia->apefam', ".
				"sexfamper = '$ao_familia->sexfam', ".
				"nexfamper = '$ao_familia->nexfam', ".
				"fecnacfamper = '$ao_familia->fecnacfam' ".
	            "WHERE codfamper= $ao_familia->codfam AND codper='$ao_familia->codper' ".
				" AND codcon='$ao_familia->codcon' AND codemp='".$this->ls_codemp."'" ;
	}
	else
	{ 
		$this->io_sql->begin_transaction();
	
	
	  $ls_sql = "INSERT INTO srh_familiaresconcursante (codper, codcon, codfamper, nomfamper, apefamper, cedfamper, sexfamper, nexfamper, fecnacfamper,codemp) ".	  
	            "VALUES ('$ao_familia->codper','$ao_familia->codcon',$ao_familia->codfam,'$ao_familia->nomfam','$ao_familia->apefam','$ao_familia->cedfam', '$ao_familia->sexfam', '$ao_familia->nexfam', '$ao_familia->fecnacfam','".$this->ls_codemp."')";
			
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

    if($lb_guardo===false)
	{
		$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_guardar_familiar ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	}
	else
	{
		$this->io_sql->commit();
		if ($as_operacion == "modificar")
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el familiar ".$ao_familia->codfam." del concursante".$ao_familia->codper;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el familiar ".$ao_familia->codfam." del concursante ".$ao_familia->codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	}

	return $lb_guardo;
 }
	
//-------------------------------------------------------------------------------------------------------------------------------
		
function uf_srh_eliminar_familiar ($as_codfam, $as_codper, $as_codcon, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_familiar																											
		//      Argumento: $as_codfam      //  código del familiar
		//                 $as_codper        // código del personal
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina un familiar de un concursante                        
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 16/09/2008							Fecha Última Modificación: 						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
	
    $ls_sql = "DELETE FROM srh_familiaresconcursante ".
	          "WHERE codfamper = '$as_codfam' AND codper = '$as_codper' AND codcon='$as_codcon'   AND codemp='".$this->ls_codemp."'";
			  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_eliminar_familiar	 ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
		$lb_valido=true;
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="DELETE";
		$ls_descripcion ="Elimino el familiar ".$as_codfam." de la persona ".$as_codper;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////			
		
			$this->io_sql->commit();
	}
	
	return $lb_valido;
  }
  
//-------------------------------------------------------------------------------------------------------------------------------
  

function uf_srh_buscar_familiares_concursante($as_codper, $as_codcon)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_familiares_concursante
		//         Access: private
		//      Argumento: $as_codper // codigo del concursante
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca la información de los familiares un concursante
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 16/09/2008							Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		 $ls_codperdestino="txtcedper";
		 $ls_codfamdestino="txtcodfam";
		 $ls_nomfamdestino="txtnomfam";
		 $ls_apefamdestino="txtapefam";
		 $ls_sexfamdestino="cmbsexfam";
		 $ls_fecnacfamdestino="txtfecnacperfam";
		 $ls_nexfamdestino="cmbnexfam";
		 $ls_cedfamdestino="txtcedfam";
	
		$lb_valido=true;
		$ls_sql="SELECT * FROM srh_familiaresconcursante ".          
				" WHERE codper = '".trim($as_codper)."' ".
				" AND codcon='".$as_codcon."' ".
			   " ORDER BY codfamper";
	
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_buscar_familiares_concursante (ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);		
				
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
				$ls_fecnacfam=$this->io_funcion->uf_formatovalidofecha($row["fecnacfamper"]);
				$ls_fecnacfam=$this->io_funcion->uf_convertirfecmostrar($ls_fecnacfam);
				
				$ls_codper=$row["codper"];
				$ls_codfam=$row["codfamper"];
				$ls_nomfam=$row["nomfamper"];
				$ls_apefam=$row["apefamper"];
				$ls_sexfam=$row["sexfamper"];
				$ls_nexfam=$row["nexfamper"];
				$ls_cedfam=trim($row["cedfamper"]);
				
				$row_ = $team->appendChild($dom->createElement('row'));
				$row_->setAttribute("id",$row['codfamper']);
				$cell = $row_->appendChild($dom->createElement('cell'));   
				$cell->appendChild($dom->createTextNode($row['codfamper']." ^javascript:aceptar(\"$ls_codfam\", \"$ls_nomfam\",\"$ls_apefam\",\"$ls_sexfam\", \"$ls_fecnacfam\",\"$ls_nexfam\",\"$ls_codfamdestino\", \"$ls_nomfamdestino\",\"$ls_apefamdestino\",\"$ls_sexfamdestino\",\"$ls_fecnacfamdestino\",\"$ls_nexfamdestino\",\"$ls_cedfam\" ,\"$ls_cedfamdestino\");^_self"));
			
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_nomfam.' '.$ls_apefam));												
				$row_->appendChild($cell);
				
				switch ($ls_nexfam) 
				{
				  case 'C' :
				    $cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode('Conyuge'));												
					$row_->appendChild($cell);
					break;
				  case 'H' :
				    $cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode('Hijo'));												
					$row_->appendChild($cell);
					break;
				  case 'P' :
				    $cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode('Progenitor'));												
					$row_->appendChild($cell);
					break;
				 case 'E' :
				    $cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode('Hermano'));												
					$row_->appendChild($cell);
					break;				
				
				}
								
			}
			return $dom->saveXML();
		
		}
      
		
} // end function uf_srh_buscar_familiares_concursante
//-------------------------------------------------------------------------------------------------------------------------------



///////////////////////////// FUNCIONES PARA EL MANEJO DE LOS REQUISITOS DEL CONCURSANTE  /////////////////////////////


function uf_srh_cargar_requistos_concurso($as_codcon,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_cargar_requistos_concurso
		//	    Arguments: as_codcon  // código del concurso
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de una escala
		// Fecha Creación: 19/09/2008							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT *  ".
				"  FROM srh_requisitos_concurso ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcon='".$as_codcon."'".
				" ORDER BY codcon,codreqcon ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_cargar_requistos_concurso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$li_codreqcon=$row["codreqcon"];
				$ls_desreqcon=trim (htmlentities($row["desreqcon"]));
				$li_canreqcon=trim ($row["canreqcon"]);
				
				$ao_object[$ai_totrows][1]="<input name=txtcodreqcon".$ai_totrows." type=text id=txtcodreqcon".$ai_totrows." class=sin-borde size=4 maxlength=3  value='".$li_codreqcon."'  style='text-align:center' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtdesreqcon".$ai_totrows." type=text id=txtdesreqcon".$ai_totrows." class=sin-borde size=80 maxlength=254 readonly value='".$ls_desreqcon."'  >";
				$ao_object[$ai_totrows][3]="<input name=txtcanreqcon".$ai_totrows." type=text id=txtcanreqcon".$ai_totrows." class=sin-borde size=4 maxlength=3 readonly style='text-align:center' value='".$li_canreqcon."' >";		
				$ao_object[$ai_totrows][4]="<select name=cmbentreq".$ai_totrows." id=cmbentreq".$ai_totrows."><option value='1' >Si</option><option value='0' >No</option></select>";
				$ao_object[$ai_totrows][5]="<input name=txtcanentreq".$ai_totrows." type=text id=txtcanentreq".$ai_totrows." class=sin-borde size=5 maxlength=3 style='text-align:center' onKeyUp='javascript:ue_validarnumero(this);' onBlur='javascript:ue_valida_catidad(".$ai_totrows.");'>";		
						
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_srh_cargar_requistos_concurso
	//--------------------------------------------------------------------------------------------------------------------------------

function uf_srh_select_requisitos_concursante($as_codcon, $as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_requisitos_concursante
		//      Argumento: $as_codcon    // codigo del concurso
		//                 $as_codper    // código de la persona
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un requisito de concurso en la tabla de srh_requisitosconcursante
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 19/09/2008							Fecha Última Modificación: 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_requisitosconcursante  ".
				  " WHERE codcon='".trim($as_codcon)."'".
				  " AND codper='".trim($as_codper)."' ".
				  " AND codemp='".$this->ls_codemp."'" ;
	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_select_requisitos_concursante ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				
				
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	}  //  end function uf_srh_select_requisitos_concursante


//--------------------------------------------------------------------------------------------------------------------------------

function uf_srh_buscar_reqindcon($as_codcon,&$as_reqindcon)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_estconper																		
		//         access: public (sigesp_srh_requisitos_concurso)
	  	//      Argumento: $as_codcon    // código del concurso						
		//                 $as_reqindcon   //   estatus de los requisitos del concurs
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que busca reqindcon  en la tabla srh_requisitos_concurso
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 17/09/2008							Fecha Última Modificación: 							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_reqindcon="";
		$lb_valido=true;
		$ls_sql = "SELECT MAX(codreqcon), reqindcon FROM srh_requisitos_concurso  ".
				  " WHERE codcon='".trim($as_codcon)."'".				  
				  " AND codemp='".$this->ls_codemp."' ".
				  " GROUP BY codreqcon,reqindcon"  ;
	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_buscar_reqindcon ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				
				$as_reqindcon=$row["reqindcon"];
				
				$this->io_sql->free_result($rs_data);
			}
			
		}
		return $lb_valido;
	}  //  end function uf_srh_select_requisitos_concursante
	
//--------------------------------------------------------------------------------------------------------------------------------

function uf_srh_actualizar_estatus_concursante($as_codcon,$as_codper,$as_valor,$aa_seguridad)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_actualizar_estatus_concursante																	
		//         access: public (sigesp_srh_requisitos_concurso)
	  	//      Argumento: $as_codcon    // código del concurso						
		//                 $as_codper   //  código del personal
		//                 $as_valor   // valor para actualizar el campo
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que actualiza el estatus de la persona en la tabla srh_concursante
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 17/09/2008							Fecha Última Modificación: 							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$lb_valido=true;
	    $ls_sql = "UPDATE srh_concursante SET ".
		"estconper= '".$as_valor."' ".
		"WHERE codper='$as_codper' AND codcon='$as_codcon' AND codemp='".$this->ls_codemp."'" ;
	 
		$lb_guardo = $this->io_sql->execute($ls_sql);

		 if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_actualizar_estatus_concursante ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			
		}
		
		return $lb_guardo;
	}  //  end function uf_srh_actualizar_estatus_concursante

//--------------------------------------------------------------------------------------------------------------------------------

	
function uf_srh_guardar_requisitos_concursante($ao_requisitos, $aa_seguridad)
 { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_requisitos_concurso																		
		//         access: public (sigesp_srh_requisitos_concurso)
	  	//      Argumento: $ao_requisitos    // arreglo con los datos de los requisitos de un concurso							
		//                $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica escala de evaluación en la tabla srh_requisitosconcursante             
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 17/09/2008							Fecha Última Modificación: 17/09/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	 $lb_correcto=true;
	 $lb_guardo =true;
	 $lb_existe=false;
	 $lb_existe = $this->uf_srh_select_requisitos_concursante($ao_requisitos->codcon,$ao_requisitos->codper);
	$this->io_sql->begin_transaction();
	//Borramos los registros anteriores 
	$this->uf_srh_eliminar_requisitos_concursante($ao_requisitos->codcon,$ao_requisitos->codper,$aa_seguridad);
	  
	//Ahora guardamos

	$li_det = 0;
	while (($li_det < count($ao_requisitos->detalle))&&($lb_guardo))
	{
	  $lb_guardo = $this->uf_srh_guardar_dt_requisitos_concursante($ao_requisitos->detalle[$li_det],$lb_guardo,$lb_val);
	  if (!$lb_val)
	  {
	  	$lb_correcto=false;
	  }
	  $li_det++;
	}
	
	if ($lb_guardo ==true)
	{
		$lb_valido=$this->uf_srh_buscar_reqindcon($ao_requisitos->codcon,$ls_reqindcon);
		if (($ls_reqindcon=='1') && (!$lb_correcto))
	    {
			$lb_valido=$this->uf_srh_actualizar_estatus_concursante($ao_requisitos->codcon,$ao_requisitos->codper,'0',$aa_seguridad);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizo el estatus de la persona ".$ao_requisitos->codper." en el concurso ".$ao_requisitos->codcon;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			
	   }
	   else
	   {
	   		$lb_valido=$this->uf_srh_actualizar_estatus_concursante($ao_requisitos->codcon,$ao_requisitos->codper,'1',$aa_seguridad);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizo el estatus de la persona ".$ao_requisitos->codper." en el concurso ".$ao_requisitos->codcon;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
	   
	   }
	  
	   if ($lb_valido)
	   {
			$this->io_sql->commit();
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó los requisitos del concursante  ".$ao_requisitos->codper."  para el concurso".$ao_requisitos->codcon;
					$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
	   	
	   }
	   else
	   {
	   		$this->io_sql->rollback();
	   }
				
	}
	else
	{
		$this->io_sql->rollback();
	}
	
	
	return array($lb_guardo,$lb_existe);    
  }

//--------------------------------------------------------------------------------------------------------------------------------

function uf_srh_eliminar_requisitos_concursante($as_codcon,$as_codper,$aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_requisitos_concursante																														
		//      Argumento: $as_codcon        // código del concurso
		//		           $as_codper       //  código del personal
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																				    
		//    Description: Funcion que elimina los requisitos de una persona asociados a un concurso                     
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 19/09/2008							Fecha Última Modificación:							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_requisitosconcursante ".
	          " WHERE codcon='$as_codcon' AND codper = '$as_codper' AND codcon='$as_codcon' AND codemp='".$this->ls_codemp."'";  

	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_eliminar_requisitos_concursante ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
		$lb_valido=true;
		
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="DELETE";
		$ls_descripcion ="Eliminó los requisitos del concursante  ".$as_codper."  para el concurso".$as_codcon;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////			
		
		$this->io_sql->commit();
	}
			
	
	return $lb_borro;
	
  }
  
  //---------------------------------------------------------------------------------------------------------------------------------

function uf_srh_guardar_dt_requisitos_concursante($ao_requisitos,&$lb_guardo,&$lb_correcto)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_dt_requisitos_concursante															     														
		//      Argumento: $ao_requisitos    // arreglo con los datos de los requisitos del concurso				
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta un requisito de una persona asociada a un concurso en la tabla 
		//                 srh_requisitosconcursante
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 19/09/2008							Fecha Última Modificación: 17/09/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  		$lb_correcto=true;
		if (($ao_requisitos->canentreq==""))
		{
			$ao_requisitos->canentreq=0;
		}
		
		if (($ao_requisitos->entreqcon==0) || ($ao_requisitos->entreqcon=='0'))
		{
			$lb_correcto=false;
		}
		
	  $ls_sql = "INSERT INTO srh_requisitosconcursante (codcon,codper,codreqcon,canentreqcon,entreqcon, codemp) ".	  
	            " VALUES ('$ao_requisitos->codcon','$ao_requisitos->codper','$ao_requisitos->codreqcon',$ao_requisitos->canentreq,$ao_requisitos->entreqcon,'".$this->ls_codemp."')";
		
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_guardar_dt_requisitos_concursante ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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

//--------------------------------------------------------------------------------------------------------------------------------

function uf_srh_consultar_requistos_concursante($as_codcon,$as_codper,&$ai_totrows,&$ao_object,&$lb_existe)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_consultar_requistos_concursante
		//	    Arguments: as_codcon  // código del concurso        
		//                 as_codper  // código del personal
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de una escala
		// Fecha Creación: 19/09/2008							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT  srh_requisitosconcursante.*, ".
		        " srh_requisitos_concurso.desreqcon,srh_requisitos_concurso.canreqcon ".
				"  FROM srh_requisitosconcursante, srh_requisitos_concurso ".
				" WHERE srh_requisitosconcursante.codemp='".$this->ls_codemp."'".
				"   AND srh_requisitosconcursante.codcon=srh_requisitos_concurso.codcon".
				"   AND srh_requisitosconcursante.codreqcon=srh_requisitos_concurso.codreqcon".
				"   AND srh_requisitosconcursante.codcon='".$as_codcon."'".
				"   AND srh_requisitosconcursante.codper='".trim($as_codper)."' ".
				"  GROUP BY srh_requisitosconcursante.codcon, codper,srh_requisitosconcursante.codreqcon, ".
				"  desreqcon, canreqcon, canentreqcon,entreqcon, srh_requisitosconcursante.codemp ".
				" ORDER BY srh_requisitosconcursante.codcon,srh_requisitosconcursante.codreqcon ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO->uf_srh_consultar_requistos_concursante ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{		
			$num=$this->io_sql->num_rows($rs_data);
         	if ($num!=0) 
			{   
				$lb_existe=true;
				$ai_totrows=0;
				while($row=$this->io_sql->fetch_row($rs_data))
				{
						$ai_totrows++;
						$li_codreqcon=$row["codreqcon"];
						$ls_desreqcon=trim (htmlentities($row["desreqcon"]));
						$li_canreqcon=trim ($row["canreqcon"]);
						$li_canentreqcon=trim ($row["canentreqcon"]);
						$ls_entreq=trim ($row["entreqcon"]);
						
						if ($ls_entreq==1)
						{
							$la_entreq[1]="selected";
							$la_entreq[0]="";
						}
						else if ($ls_entreq==0)
						{
							$la_entreq[0]="selected";
							$la_entreq[1]="";
						}
						
						$ao_object[$ai_totrows][1]="<input name=txtcodreqcon".$ai_totrows." type=text id=txtcodreqcon".$ai_totrows." class=sin-borde size=4 maxlength=3  value='".$li_codreqcon."'  style='text-align:center' readonly>";
						$ao_object[$ai_totrows][2]="<input name=txtdesreqcon".$ai_totrows." type=text id=txtdesreqcon".$ai_totrows." class=sin-borde size=80 maxlength=254 readonly value='".$ls_desreqcon."'  >";
						$ao_object[$ai_totrows][3]="<input name=txtcanreqcon".$ai_totrows." type=text id=txtcanreqcon".$ai_totrows." class=sin-borde size=4 maxlength=3 readonly style='text-align:center' value='".$li_canreqcon."' >";		
						$ao_object[$ai_totrows][4]="<select name=cmbentreq".$ai_totrows." id=cmbentreq".$ai_totrows.">
												   <option value='1' ".$la_entreq[1]." >Si</option>
												   <option value='0' ".$la_entreq[0].">No</option></select>";
						$ao_object[$ai_totrows][5]="<input name=txtcanentreq".$ai_totrows." type=text id=txtcanentreq".$ai_totrows." class=sin-borde size=5 maxlength=3 style='text-align:center' onKeyUp='javascript:ue_validarnumero(this);' onBlur='javascript:ue_valida_catidad(".$ai_totrows.");' value='".$li_canentreqcon."'>";	
					}
			}
			else
			{
				 $this->io_msg->message("No se encontraron Requisitos de Concurso asociados al concursante ".$as_codper);
				  $lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_srh_cargar_requistos_concurso
	//--------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
function uf_srh_buscar_personal_concurso($as_codper,$as_apeper,$as_nomper,$as_hidcodcon)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_asignar_concurso																											
		//      Argumento: as_codper   //  código del personal		
		//                 $as_apeper   //  apellido del personal                                                           
		//                 $as_nomper   //  nombre del personal
		//	      Returns: Retorna un XML  																						
		//    Description: Funcion busca un personal en la tabla srh_persona_concurso y crea un XML para mostrar    
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 18/03/2008							Fecha Última Modificación: 18/03/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		
	    $ls_codperdestino="txtcodper";
		$ls_nomperdestino="txtnomper";
		
		$lb_valido=true;	
		
				
		$ls_sql= "SELECT *  FROM srh_concursante ".
				"   WHERE srh_concursante.codcon =  '$as_hidcodcon' ".
				" AND estconper='1' ".
				" AND codper like '$as_codper' ".
			 	 " AND nomper like '$as_nomper' ".
				 " AND apeper like '$as_apeper' ".
				" ORDER BY srh_concursante.codper ";
				
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->inscripcion_concurso MÉTODO-> uf_srh_buscar_personal_concurso( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
				  
				$ls_apeper =htmlentities (trim ($row["apeper"]));
				
				  
				$ls_nomper =htmlentities (trim ($row["nomper"]));
				
				
				$ls_nomper_completo=$ls_nomper." ".$ls_apeper;
				
				if ($row["tipper"]=='E') {
					 $ls_tipo='Externo';
				}
				elseif ($row["tipper"]=='I') {
					$ls_tipo='Interno';
				}
				
				
				$row_ = $team->appendChild($dom->createElement('row'));
				$row_->setAttribute("id",$ls_codper);
				$cell = $row_->appendChild($dom->createElement('cell'));   
				
				$cell->appendChild($dom->createTextNode($ls_codper." ^javascript:aceptar( \"$ls_codper\", \"$ls_nomper_completo\", \"$ls_codperdestino\",  \"$ls_nomperdestino\");^_self"));
				
			
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_apeper));												
				$row_->appendChild($cell);
			
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_nomper));												
				$row_->appendChild($cell);
				
				
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_tipo));												
				$row_->appendChild($cell);
			
			}
			return $dom->saveXML();
		}
      
		
	} // end function buscar_asignar_concurso

}
?>