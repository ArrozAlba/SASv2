<?php

class sigesp_srh_c_pasantias
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_pasantias($path)
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
		//         Access: public (sigesp_srh_p_pasantia)
		//      Argumento: 
		//	      Returns: Retorna el nuevo número de un registro de pasantía
		//    Description: Funcion que genera un registro de pasantía
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(nropas) AS numero FROM srh_pasantias ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_nropas = $la_datos["numero"][0]+1;
    $ls_nropas = str_pad ($ls_nropas,10,"0","left");
	return $ls_nropas;
  }
	
  	
  function uf_srh_getPasantias($as_nropas,&$pa_datos="")
  {  
  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getPasantias																					//
		//         access: public (sigesp_srh_pasantias)															            //
		//      Argumento: $as_nropas    // numero de la pasantia																//
		//                 $pa_datos    //  arreglos donde  se cargaran lo datos de la consulta									//
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que realiza una busqueda de un registro de pasantia en la tabla srh_pasantias		        //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 12/11/2007							Fecha Última Modificación: 12/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  
  
    $ls_sql = "SELECT * FROM srh_pasantias ".
	          "WHERE nropas = '$as_nropas'";
    $lb_hay = $this->seleccionar($ls_sql, $pa_datos);
    return $lb_hay;
  }
  
  
  
 
  
function uf_srh_guardarPasantia($ao_pasantia,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarPasantia																				//
		//         access: public (sigesp_srh_pasantias)															            //
		//      Argumento: $ao_pasantia    // arreglo con los datos de la pasantia										    	//
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)         	    //
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta o modifica un registro de pasantia en la tabla srh_pasantias             		//
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 12/11/2007							Fecha Última Modificación: 12/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$as_nropas=$ao_pasantia->nropas;
	
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	 $ao_pasantia->fecini=$this->io_funcion->uf_convertirdatetobd($ao_pasantia->fecini);
	 $ao_pasantia->fecfin=$this->io_funcion->uf_convertirdatetobd($ao_pasantia->fecfin);
	 $ao_pasantia->fecnac=$this->io_funcion->uf_convertirdatetobd($ao_pasantia->fecnac);
	 
	  $ls_sql = "UPDATE srh_pasantias SET ".
	            "cedpas = '$ao_pasantia->cedpas', ".
	            "fecini = '$ao_pasantia->fecini', ".
   				"fecfin = '$ao_pasantia->fecfin', ".
	            "apepas = '$ao_pasantia->apepas', ".
	            "nompas = '$ao_pasantia->nompas', ".
	            "sexpas = '$ao_pasantia->sexpas', ".
	            "fecnac = '$ao_pasantia->fecnac', ".
	            "telhab = '$ao_pasantia->telhab', ".
	            "email  = '$ao_pasantia->email', ".
	            "codpar = '$ao_pasantia->codpar', ".
				"codmun = '$ao_pasantia->codmun', ".
				"codest = '$ao_pasantia->codest', ".
	            "dirpas = '$ao_pasantia->dirpas', ".
	            "telmov = '$ao_pasantia->telmov', ".
	            "edociv = '$ao_pasantia->edociv', ".
				"inst_univ = '$ao_pasantia->inst_univ', ".
				"carrera   = '$ao_pasantia->carrera', ".
				"tutor     = '$ao_pasantia->tutor', ".
				"estado    = '$ao_pasantia->estado' ".
				"WHERE nropas= '$ao_pasantia->nropas' AND codemp='".$this->ls_codemp."'" ;

				
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el registro de pasantia ".$as_nropas;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	  $ao_pasantia->fecini=$this->io_funcion->uf_convertirdatetobd($ao_pasantia->fecini);
	  $ao_pasantia->fecfin=$this->io_funcion->uf_convertirdatetobd($ao_pasantia->fecfin);
	  $ao_pasantia->fecnac=$this->io_funcion->uf_convertirdatetobd($ao_pasantia->fecnac);
	
	  $ls_sql = "INSERT INTO srh_pasantias (nropas, cedpas, fecini,fecfin, apepas, nompas, sexpas, fecnac, telhab, email, codpar, codmun, codest, dirpas, telmov, edociv, inst_univ, carrera, tutor,estado,codemp) ".	  
	            "VALUES ('$ao_pasantia->nropas','$ao_pasantia->cedpas','$ao_pasantia->fecini','$ao_pasantia->fecfin','$ao_pasantia->apepas','$ao_pasantia->nompas','$ao_pasantia->sexpas','$ao_pasantia->fecnac','$ao_pasantia->telhab','$ao_pasantia->email','$ao_pasantia->codpar','$ao_pasantia->codmun','$ao_pasantia->codest','$ao_pasantia->dirpas','$ao_pasantia->telmov','$ao_pasantia->edociv','$ao_pasantia->inst_univ','$ao_pasantia->carrera','$ao_pasantia->tutor','$ao_pasantia->estado','".$this->ls_codemp."')";
				

				
	
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el registro de pasantia ".$as_nropas;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->pasantias MÉTODO->uf_srh_guardarPasantia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				$this->io_sql->commit();
		}
		
	return $lb_valido;
  }

function uf_srh_eliminarPasantia($as_nropas, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarPasantia																				//
		//        access:  public (sigesp_srh_pasantias)															            //
		//      Argumento: $as_nropas        // numero de el registro de pasantia										        //
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que elimina un registro de pasantia en la tabla srh_pasantias                         		//
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 12/11/2007							Fecha Última Modificación: 12/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 	$lb_valido=false;
	$lb_existe=true;
    $this->io_sql->begin_transaction();	
	if ($this->buscar_evaluacion_pasantia($as_nropas)===false)
	
  {    $ls_sql = "DELETE FROM srh_pasantias ".
	          "WHERE nropas = '$as_nropas'   AND codemp='".$this->ls_codemp."'";
			  
	  $lb_existe=false;
	  $lb_borro=$this->io_sql->execute($ls_sql);
	  if($lb_borro===false)
	   {
		  $this->io_msg->message("CLASE->pasantias MÉTODO->uf_srh_eliminarPasantia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  $lb_valido=false;
		  $this->io_sql->rollback();
	   }
	   else
	   {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Registro de Pasantia ".$as_nropas;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
	}
   return array($lb_valido,$lb_existe);
   
  }
	
	
	
	
function uf_srh_buscar_pasantias($as_nropas,$as_cedpas,$as_apepas,$as_nompas,$as_fecini1,$as_fecini2)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_pasantias																				//
		//         access: public (sigesp_srh_pasantias)													                    //
		//      Argumento: $as_nropas   //  numero de pasantia											                        //
		//                 $as_cedpas   //  cedula del pasante                                                              	//
		//                 $as_apepas   //  apellido del pasante                                                            	//
		//                 $as_nompas   //  nombre del pasante                                                                  //
		//                $as_fecini   //  fecha de incorporacion en la pasantia												//
		//	      Returns: Retorna un XML  																						//
		//    Description: Funcion busca un registro de pasantia en la tabla srh_pasantias y crea un XML para mostrar  			//
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 12/11/2007							Fecha Última Modificación: 12/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	    $as_fecini1=$this->io_funcion->uf_convertirdatetobd($as_fecini1);
		$as_fecini2=$this->io_funcion->uf_convertirdatetobd($as_fecini2);
		
		
	
	    $ls_nrodestino="txtnropas";
		$ls_ceddestino="txtcedpas";
		$ls_fecinidestino="txtfecini";
		$ls_fecfindestino="txtfecfin";
		$ls_apedestino="txtapepas";
		$ls_fecnacdestino="txtfecnac";
		$ls_nomdestino="txtnompas";
		$ls_sexdestino="combosexo";
		$ls_telhdestino="txttelhab";
		$ls_emadestino="txtemail";
		$ls_codpardestino="combopar";
		$ls_codmundestino="combomun";
		$ls_codestdestino="comboest";
		$ls_dirdestino="txtdirpas";
		$ls_telmdestino="txttelmov";
		$ls_estdestino="comboedociv";
		$ls_tutordestino="txtcodper";
		$ls_carredestino="txtcarre";
		$ls_univdestino="txtuniv";
	    $ls_nomtutordestino="txtnomper";
		
		$lb_valido=true;
		
		
				
		$ls_sql= "SELECT srh_pasantias.nropas, srh_pasantias.nompas, srh_pasantias.apepas, srh_pasantias.cedpas,srh_pasantias.fecini, srh_pasantias.fecfin, srh_pasantias.sexpas, srh_pasantias.fecnac, srh_pasantias.telhab, srh_pasantias.telmov, srh_pasantias.email, srh_pasantias.dirpas, srh_pasantias.codpar, srh_pasantias.codest, srh_pasantias.codmun, srh_pasantias.edociv, srh_pasantias.tutor, srh_pasantias.inst_univ, srh_pasantias.carrera, sigesp_parroquia.codpar, sigesp_parroquia.denpar, sigesp_municipio.codmun, sigesp_municipio.denmun, sigesp_estados.codest, sigesp_estados.desest, sno_personal.codper, sno_personal.apeper, sno_personal.nomper   FROM srh_pasantias INNER JOIN sigesp_parroquia ON (sigesp_parroquia.codpar = srh_pasantias.codpar AND sigesp_parroquia.codmun= srh_pasantias.codmun AND sigesp_parroquia.codest= srh_pasantias.codest) INNER JOIN sigesp_municipio ON (srh_pasantias.codmun = sigesp_municipio.codmun AND sigesp_parroquia.codmun = sigesp_municipio.codmun) INNER JOIN sigesp_estados ON (sigesp_municipio.codest = sigesp_estados.codest AND  srh_pasantias.codest = sigesp_estados.codest AND  sigesp_parroquia.codest = sigesp_estados.codest)INNER JOIN sno_personal ON (sno_personal.codper = srh_pasantias.tutor)".
				" WHERE nropas like '$as_nropas' ".
				"   AND fecini between  '".$as_fecini1."' AND '".$as_fecini2."' ".
				"   AND cedpas like '$as_cedpas' ".
				"   AND nompas like '$as_nompas' ".
				"   AND apepas like '$as_apepas' ".				
			   " ORDER BY nropas";
	 
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->pasantia MÉTODO->uf_srh_buscar_pasantias( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
					$ls_fecini=$this->io_funcion->uf_formatovalidofecha($row["fecini"]);
				    $ls_fecini=$this->io_funcion->uf_convertirfecmostrar($ls_fecini);
					$ls_fecfin=$this->io_funcion->uf_formatovalidofecha($row["fecfin"]);
				    $ls_fecfin=$this->io_funcion->uf_convertirfecmostrar($ls_fecfin);
					$ls_apepas = trim (htmlentities   ($row["apepas"]));
					$ls_nompas= trim  (htmlentities  ($row["nompas"]));
					$ls_sexpas=$row["sexpas"];
					$ls_fecnac=$this->io_funcion->uf_formatovalidofecha($row["fecnac"]);
				    $ls_fecnac=$this->io_funcion->uf_convertirfecmostrar($ls_fecnac);
					$ls_telhab=$row["telhab"];
					$ls_email=$row["email"];
					$ls_codpar=$row["codpar"];
					$ls_denmun=$row["codmun"];
					$ls_denest=$row["codest"];
					$ls_dirpas=trim (htmlentities ($row["dirpas"]));
					$ls_telmov=$row["telmov"];
					$ls_edociv=$row["edociv"];
					$ls_carre=trim (htmlentities ($row["carrera"]));
					$ls_univ=trim(htmlentities ($row["inst_univ"]));
					$ls_tutor=trim (htmlentities  ($row["tutor"]));
			        $ls_nomtutor=trim (htmlentities ($row["nomper"]));
					$ls_apetutor=trim (htmlentities   ($row["apeper"]));
			
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['nropas']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($row['nropas']." ^javascript:aceptar(\"$ls_nropas\",\"$ls_cedpas\", \"$ls_fecini\",\"$ls_apepas\",\"$ls_nompas\",\"$ls_sexpas\",\"$ls_fecnac\",\"$ls_telhab\",\"$ls_email\",\"$ls_codpar\",\"$ls_dirpas\",\"$ls_telmov\", \"$ls_edociv\",\"$ls_tutor\" ,\"$ls_univ\", \"$ls_carre\", \"$ls_nrodestino\", \"$ls_ceddestino\",\"$ls_fecinidestino\", \"$ls_apedestino\", \"$ls_fecnacdestino\",\"$ls_nomdestino\", \"$ls_sexdestino\", \"$ls_telhdestino\",\"$ls_emadestino\",\"$ls_codpardestino\", \"$ls_dirdestino\",\"$ls_telmdestino\",\"$ls_fecinidestino\",\"$ls_estdestino\",\"$ls_codmundestino\",\"$ls_denmun\",\"$ls_codestdestino\",\"$ls_denest\",\"$ls_tutordestino\",\"$ls_carredestino\",\"$ls_univdestino\",\"$ls_fecfin\", \"$ls_fecfindestino\",\"$ls_nomtutor\",\"$ls_nomtutordestino\",\"$ls_apetutor\");^_self"));
					
								
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_fecini));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($row['cedpas']));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_nompas.'  '.$ls_apepas));												
					$row_->appendChild($cell);
					
					
			
			}
			
            
			return $dom->saveXML();
		
	
		
		}
      
		
	} // end function buscar_pasantias
	///--------------------------------------------------------------------------------------------------------------
	  function buscar_evaluacion_pasantia($ls_rnopas)
	  {
	    $ls_valido=true;
	      $ls_sql=" SELECT nropas FROM srh_evaluacion_pasantia ".
		          " WHERE nropas = '".$ls_rnopas."'  AND codemp='".$this->ls_codemp."'";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->Evaluaciòn  MÉTODO->buscar_evaluacion_pasantia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			$ls_valido=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $ls_valido;
	  }
	  
	//---------------------------------------------------------------------------------------------------------------


}// end   class sigesp_srh_c_pasantias
?>