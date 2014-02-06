<?php

class sigesp_srh_c_solicitud_adiestramiento
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_solicitud_adiestramiento($path)
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
		//         Access: public (sigesp_srh_p_solicitud_adiestramiento)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de una solicitud de adiestramiento
		//    Description: Funcion que genera un código nuevo de una solicutd de adiestramiento
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:16/01/2008							Fecha Última Modificación:16/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(nroreg) AS numero FROM srh_solicitud_adiestramiento ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_nroreg = $la_datos["numero"][0]+1;
    $ls_nroreg = str_pad ($ls_nroreg,10,"0","left");
	return $ls_nroreg;
  } 

	
  
  
function uf_srh_guardarsolicitud_adiestramiento ($ao_solicitud,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarsolicitud_adiestramiento																		
		//         access: public (sigesp_srh_solicitud_adiestramiento)														    			
		//      Argumento: $ao_solicitud    // arreglo con los datos de la solicitud de adiestramiento								
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica una solicitud de adiestramiento en la tabla srh_solicitud_adiestramiento 
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 20/12/2007							Fecha Última Modificación: 20/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	 $ao_solicitud->fecsol=$this->io_funcion->uf_convertirdatetobd($ao_solicitud->fecsol);
	 $ao_solicitud->fecini=$this->io_funcion->uf_convertirdatetobd($ao_solicitud->fecini);
	 $ao_solicitud->fecfin=$this->io_funcion->uf_convertirdatetobd($ao_solicitud->fecfin);
	 
	 $ao_solicitud->costo=str_replace(".","",$ao_solicitud->costo);
	 $ao_solicitud->costo=str_replace(",",".",$ao_solicitud->costo);
	 
	  $ls_sql = "UPDATE srh_solicitud_adiestramiento SET ".
		  		"fecha = '$ao_solicitud->fecsol' , ".
	            "codper = '$ao_solicitud->codsol' , ".
				"codunivipladin = '$ao_solicitud->uniad' , ".
				"codprov = '$ao_solicitud->prov' , ".
				"descripcion = '$ao_solicitud->descrip' , ".
				"observacion = '$ao_solicitud->obs' , ".
				"fecini = '$ao_solicitud->fecini' , ".
				"fecfin = '$ao_solicitud->fecfin' , ".
				"costo = '$ao_solicitud->costo' , ".
				"objetivo = '$ao_solicitud->obj' , ".
				"area = '$ao_solicitud->are' , ".
				"estrategia = '$ao_solicitud->est' , ".
				"durhras = '$ao_solicitud->durhras'  ".
	            "WHERE nroreg= '$ao_solicitud->nroreg'  AND codemp='".$this->ls_codemp."'" ;
		
	  
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó la solicitud de adiestramiento ".$ao_solicitud->nroreg;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
 	 $ao_solicitud->fecsol=$this->io_funcion->uf_convertirdatetobd($ao_solicitud->fecsol);
	 $ao_solicitud->fecini=$this->io_funcion->uf_convertirdatetobd($ao_solicitud->fecini);
	 $ao_solicitud->fecfin=$this->io_funcion->uf_convertirdatetobd($ao_solicitud->fecfin);
	 
	 $ao_solicitud->costo=str_replace(".","",$ao_solicitud->costo);
	 $ao_solicitud->costo=str_replace(",",".",$ao_solicitud->costo);
	
		
	  $ls_sql = "INSERT INTO srh_solicitud_adiestramiento (nroreg, codper, codunivipladin, fecha, codprov, descripcion, observacion, fecini, fecfin, costo, durhras, objetivo, area, estrategia, codemp) ".	  
			"VALUES ('$ao_solicitud->nroreg', '$ao_solicitud->codsol','$ao_solicitud->uniad','$ao_solicitud->fecsol', '$ao_solicitud->prov','$ao_solicitud->descrip','$ao_solicitud->obs','$ao_solicitud->fecini','$ao_solicitud->fecfin','$ao_solicitud->costo','$ao_solicitud->durhras', '$ao_solicitud->obj','$ao_solicitud->are','$ao_solicitud->est','".$this->ls_codemp."')";
			
			
	
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la solicitud de adiestramiento ".$ao_solicitud->nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->solicitud_adiestramiento MÉTODO->uf_srh_guardarsolicitud_adiestramiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				//Guardamos los detalles de la solicitud de adiestramiento
		         $lb_guardo = $this->guardarDetalles_Solicitud($ao_solicitud, $aa_seguridad);
				 if($lb_guardo)	
					{
					$this->io_sql->commit();
					}
				else 
				{$lb_valido=false;}
				
		}
	
	return $lb_guardo;
  }
	
	
	
function guardarDetalles_Solicitud ($ao_solicitud, $aa_seguridad)
  {
    //Borramos los registros anteriores 
	$this-> uf_srh_eliminar_dt_solicitud_adiestramiento($ao_solicitud->nroreg, $aa_seguridad);
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_per= 0;
	while (($li_per < count($ao_solicitud->personal)) &&
	       ($lb_guardo))
	{
	  $lb_guardo = $this-> uf_srh_guardar_dt_solicitud_adiestramiento($ao_solicitud->personal[$li_per], $aa_seguridad);
	  $li_per++;
	}
	
	return $lb_guardo;    
  }


function uf_select_solicitud_evaluacion($as_nroreg)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_solicitud_evaluacion
		//		   Access: private
 		//	    Arguments: as_nroreg // número del registro de solicitud
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el cargo esta asociada a una personal de nomina
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 25/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$as_nroreg= trim ($as_nroreg);
		$ls_sql= "SELECT nroreg ".
				 "  FROM srh_evaluacion_adiestramiento".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND nroreg= '$as_nroreg'";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->solicitud_adiestramiento MÉTODO->uf_select_solicitud_evaluacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	

	
	
function uf_srh_eliminarsolicitud_adiestramiento($as_nroreg,  $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarsolicitud_adiestramiento																		
		//        access:  public (sigesp_srh_solicitud_adiestramiento)														
		//      Argumento: $as_nroreg       // codigo de la solicitud de adiestramiento 								
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina una solicitud de adiestramiento en la tabla srh_solicitud_adiestramiento
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 20/12/2007							Fecha Última Modificación: 20/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
         $lb_valido=false;
	     $lb_existe=true;
		
		if ($this->uf_select_solicitud_evaluacion ($as_nroreg)===false)
		    
		{
		   $lb_existe=false;
			$this->io_sql->begin_transaction();	
			$this-> uf_srh_eliminar_dt_solicitud_adiestramiento($as_nroreg, $aa_seguridad);
			
			$ls_sql = "DELETE FROM srh_solicitud_adiestramiento ".
					  "WHERE nroreg = '$as_nroreg'   AND codemp='".$this->ls_codemp."'";
		
		  
			$lb_borro=$this->io_sql->execute($ls_sql);
			if($lb_borro===false)
			 {
				$this->io_msg->message("CLASE->solicitud_adiestramiento MÉTODO->uf_srh_eliminarsolicitud_adiestramiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			 }
			else
			 {
						$lb_valido=true;
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Eliminó la solicitud de adiestramiento ".$as_nroreg;
						$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////			
						
							$this->io_sql->commit();
					}
	}
	return array($lb_valido,$lb_existe);
  }
	
	
	
	
function uf_srh_buscar_solicitud_adiestramiento($as_nroreg,$as_fecsol1, $as_fecsol2,$as_codprov,$as_des)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_solicitud_adiestramiento																											
		//      Argumento: $as_nroreg    // número de la soliciutd de adiestramiento
		//                 $as_fecsol    // fecha de la solicitud                                                           
		//                 $as_codprov   //  código del proveedor de adiestramiento
		//                 $as_des       //   descripción de adiestramiento
		//	      Returns: Retorna un XML  																						
		//    Description: Funcion busca una soliciutd de adiestramiento en la tabla srh_solicitud_adiestramiento y crea un XML 
		//                 para mostrar los datos en el catalogo
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 20/12/2007							Fecha Última Modificación: 20/12/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		
		$as_fecsol1=$this->io_funcion->uf_convertirdatetobd($as_fecsol1);
		$as_fecsol2=$this->io_funcion->uf_convertirdatetobd($as_fecsol2);
		
		
	    $ls_nrodestino="txtnroreg";
		$ls_fecsoldestino="txtfecsol";
		$ls_desdestino="txtdes";
		$ls_codsoldestino="txtcodper";
		$ls_nomdestino="txtnomper";
	    $ls_uniaddestino="txtcodunivi";
		$ls_denuniaddestino="txtdenunivi";
		$ls_codprovdestino="txtcodprov";
		$ls_denprovdestino="txtdenprov";
		$ls_fecinidestino="txtfecini";
		$ls_fecfindestino="txtfecfin";
		$ls_durhrasdestino="txtdurhras";
		$ls_costodestino="txtcosto";
		$ls_obsdestino="txtobs";
		$ls_aredestino="txtare";
		$ls_estdestino="txtest";
		$ls_objdestino="txtobj";
	
		
		$lb_valido=true;
		
		
				
		$ls_sql= "SELECT *  FROM srh_solicitud_adiestramiento INNER JOIN sno_personal ON (sno_personal.codper = srh_solicitud_adiestramiento.codper) INNER JOIN rpc_proveedor ON (srh_solicitud_adiestramiento.codprov = rpc_proveedor.cod_pro)
		           INNER JOIN srh_unidadvipladin ON (srh_solicitud_adiestramiento.codunivipladin = srh_unidadvipladin.codunivipladin)  ".
				"   AND srh_solicitud_adiestramiento.fecha BETWEEN  '".$as_fecsol1."' AND '".$as_fecsol2."' ".
				"   AND srh_solicitud_adiestramiento.nroreg like '$as_nroreg' ".
				"   AND srh_solicitud_adiestramiento.codprov like '$as_codprov' ".
				"   AND srh_solicitud_adiestramiento.descripcion like '$as_des' ".
				" ORDER BY srh_solicitud_adiestramiento.nroreg";
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->solicitud_adiestramiento MÉTODO->uf_srh_buscar_solicitud_adiestramiento( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
					$ls_fecsol=$this->io_funcion->uf_formatovalidofecha($row["fecha"]);
				    $ls_fecsol=$this->io_funcion->uf_convertirfecmostrar($ls_fecsol);
					$ls_des = trim (htmlentities ($row["descripcion"]));
					$ls_codsol=$row["codper"];
					$ls_apesol= trim (htmlentities  ($row["apeper"]));
					if ($ls_apesol!=0) {
					  $ls_nomsol= trim (htmlentities ($row['nomper'])).'  '.trim (htmlentities ($row['apeper']));
					}
					else {
					  $ls_nomsol= trim (htmlentities ($row["nomper"]));
					}
								
					$ls_uniad=$row["codunivipladin"];
					$ls_denuniad= trim (htmlentities  ($row["denunivipladin"]));
					$ls_codprov=$row["codprov"];
					$ls_denprov=trim (htmlentities  ($row["nompro"]));
					$ls_fecini=$this->io_funcion->uf_formatovalidofecha($row["fecini"]);
				    $ls_fecini=$this->io_funcion->uf_convertirfecmostrar($ls_fecini);
					
					$ls_fecfin=$this->io_funcion->uf_formatovalidofecha($row["fecfin"]);
				    $ls_fecfin=$this->io_funcion->uf_convertirfecmostrar($ls_fecfin);
					$ls_durhras=$row["durhras"];
					$ls_costo=$row["costo"];
					$ls_obs= trim (htmlentities  ($row["observacion"]));
					$ls_est= trim (htmlentities  ($row["estrategia"]));
					$ls_are= trim (htmlentities  ($row["area"]));
					$ls_obj= trim (htmlentities  ($row["objetivo"]));
					
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['nroreg']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($row['nroreg']." ^javascript:aceptar( \"$ls_nroreg\", \"$ls_fecsol\", \"$ls_des\", \"$ls_codsol\", \"$ls_nomsol\", \"$ls_apesol\", \"$ls_uniad\", \"$ls_denuniad\", \"$ls_codprov\", \"$ls_denprov\", \"$ls_fecini\", \"$ls_fecfin\", \"$ls_durhras\" ,  \"$ls_costo\" ,  \"$ls_obs\" , \"$ls_obj\", \"$ls_est\", \"$ls_are\" , \"$ls_nrodestino\" , \"$ls_fecsoldestino\" ,  \"$ls_desdestino\" ,  \"$ls_codsoldestino\" ,  \"$ls_nomdestino\" , \"$ls_uniaddestino\" , \"$ls_denuniaddestino\" , \"$ls_codprovdestino\" ,  \"$ls_denprovdestino\" ,  \"$ls_fecinidestino\" ,  \"$ls_fecfindestino\" ,  \"$ls_durhrasdestino\" , \"$ls_costodestino\" , \"$ls_obsdestino\",\"$ls_objdestino\",\"$ls_estdestino\",\"$ls_aredestino\");^_self"));
					
				
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_fecsol));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_des));												
					$row_->appendChild($cell);
				
					
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_denprov));												
					$row_->appendChild($cell);
			
					
			
			}
			return $dom->saveXML();
		
			
			
		
		}
      
		
	} // end function buscar_solicitud_adiestramiento
	

//FUNCIONES PARA EL MANEJO DEL DETALLE DE LA SOLICITUD DE ADIESTRAMIENTO

function uf_srh_guardar_dt_solicitud_adiestramiento($ao_solicitud, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_dt_solicitud_adiestramiento															     	
		//         access: public (sigesp_dt_srh_solicitud_adiestramiento)														
		//      Argumento: $ao_solicitud    // arreglo con los datos de los detalle de la solicitud de adiestramiento					
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica una solicitud de adiestramiento en la tabla 
		//				   srh_dt_solicitud_adiestramiento           
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 20/12/2007							Fecha Última Modificación: 20/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	  
	  $ls_sql = "INSERT INTO srh_dt_solicitud_adiestramiento (nroreg,codper,carper,dep, codemp) ".	  
	            " VALUES ('$ao_solicitud->nroreg','$ao_solicitud->codper','$ao_solicitud->carper','$ao_solicitud->depto','".$this->ls_codemp."')";

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el detalle de evaluación de la solicitud de adiestramiento ".$ao_solicitud->nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->solicitud_adiestramiento MÉTODO->uf_srh_guardar_dt_solicitud_adiestramiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
  
  
   function uf_select_solicitud_evaluacion_adiestramiento ($as_nroreg)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_solicitud_evaluacion_adiestramiento
		//		   Access: private
 		//	    Arguments: as_nroreg // número de registro de la solicitud
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el registro de solicitud de adiestramiento esta asociada a una evaluación
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT nroreg ".
				 "  FROM srh_evaluacion_adiestramiento".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND nroreg = '".$as_nroreg."' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->soliciutd_adiestramiento  MÉTODO->uf_select_solicitud_evaluacion_adiestramiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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



	
	
function uf_srh_eliminar_dt_solicitud_adiestramiento($as_nroreg, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_dt_solicitud_adiestramiento																
		//        access:  public (sigesp_srh_dt_solicitud_adiestramiento)														
		//      Argumento: $as_nroreg        // numero de la solicitud de adiestramiento
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																				    
		//    Description: Funcion que elimina una solicitud de adiestramiento en la tabla srh_dt_solicitud_adiestramiento                      
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 20/12/2007							Fecha Última Modificación: 20/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $lb_valido=true;
	$lb_existe= $this->uf_select_solicitud_evaluacion_adiestramiento ($as_nroreg);
	if ($lb_existe)
	{
			
		$lb_valido=false;
		
	}
	else
	{
   
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_dt_solicitud_adiestramiento ".
	          " WHERE nroreg='$as_nroreg'  AND codemp='".$this->ls_codemp."'";
		  

	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->solicitud_adiestramiento MÉTODO->uf_srh_eliminar_dt_solicitud_adiestramiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el detalle de evaluación de solicitud de adiestramiento ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
	}	
	
	return array($lb_valido,$lb_existe);
	
  }
  
  
  

function uf_srh_load_solicitud_adiestramiento_campos($as_nroreg,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_solicitud_adiestramiento_campos
		//	    Arguments: as_nroreg  // número de la solicitud de adiestramiento
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de una soliciutd de adiestramiento
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$ls_sql="SELECT * ".
				"  FROM srh_dt_solicitud_adiestramiento, sno_personal ".
				"  WHERE srh_dt_solicitud_adiestramiento.codemp='".$this->ls_codemp."'".
				"  AND srh_dt_solicitud_adiestramiento.nroreg = '".$as_nroreg."' ".
				"  AND srh_dt_solicitud_adiestramiento.codper = sno_personal.codper ".
				" ORDER BY srh_dt_solicitud_adiestramiento.codper ";

				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->solicitud_adiestramiento MÉTODO->uf_srh_load_solicitud_adiestramiento_campos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_codper=$row["codper"];
					$ls_carper= trim (htmlentities ($row["carper"]));
					$ls_dep= trim (htmlentities ($row["dep"]));
					$ls_apeper= trim (htmlentities ($row["apeper"]));
					
					if ($ls_apeper!=0) {
					$ls_nomper= trim (htmlentities  ($row["nomper"])). ' '.trim (htmlentities ($row["apeper"]));
					
					}
					else  {
					 $ls_nomper= trim (htmlentities ($row["nomper"]));
					}
				
				$ao_object[$ai_totrows][1]="<input name=txtcodper".$ai_totrows." type=text id=txtcodper".$ai_totrows."  class=sin-borde size=15 maxlength=10  readonly value='".$ls_codper."'>";
				$ao_object[$ai_totrows][2]="<input name=txtnomper".$ai_totrows." type=text id=txtnomper".$ai_totrows." class=sin-borde readonly size=35 value='".$ls_nomper."'>";
				$ao_object[$ai_totrows][3]="<input name=txtcarper".$ai_totrows." type=text id=txtcarper".$ai_totrows." class=sin-borde  size=20 value='".$ls_carper."'>";
				$ao_object[$ai_totrows][4]="<input name=txtdep".$ai_totrows." type=text id=txtdep".$ai_totrows." class=sin-borde  size=30 value='".$ls_dep."'>";
				$ao_object[$ai_totrows][5]="<a href=javascript:catalogo_personal(".$ai_totrows.");   align=center><img src=../../../../shared/imagebank/tools15/buscar.gif alt=Buscar width=15 height=15 border=0 align=center></a>";
				$ao_object[$ai_totrows][6]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$ao_object[$ai_totrows][7]="<a href=javascript:uf_delete_dt(".$ai_totrows.");     align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";
			
	
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
//FUNCIONES PARA EL MANEJO DEL CATÁLOGO DE PROVEEDOR 



function uf_srh_buscar_proveedor ($as_codprov, $as_denprov)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_solicitud_adiestramiento																											
		//      Argumento: $as_codprov   //  código del proveedor de adiestramiento       
	    //                 $as_denprov   //  denominación del proveedor de adiestramiento
		//	      Returns: Retorna un XML  																						
		//    Description: Funcion busca un proveedor en la tabla rpc_proveedor y crea un XML para mostrar    
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 20/12/2007							Fecha Última Modificación: 20/12/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		
	   
		$ls_codprovdestino="txtcodprov";
		$ls_denprovdestino="txtdenprov";
		
		
		$lb_valido=true;
		
		
				
		$ls_sql= "SELECT *  FROM rpc_proveedor  ".
				"   WHERE cod_pro like '$as_codprov' ".
				"   AND nompro like '$as_denprov' ".
				" ORDER BY cod_pro";
		
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->solicitud_adiestramiento MÉTODO->uf_srh_buscar_proveedor( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	

		
		    $dom1 = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom1->createElement('rows');
		     $dom1->appendChild($team);			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
		
					$ls_codprov=$row["cod_pro"];
					$ls_denprov=$row["nompro"];
					$ls_denprov=trim (htmlentities($ls_denprov));
					
					$row_ = $team->appendChild($dom1->createElement('row'));
					$row_->setAttribute("id",$row['cod_pro']);
					$cell = $row_->appendChild($dom1->createElement('cell'));   
					
					$cell->appendChild($dom1->createTextNode($row['cod_pro']." ^javascript:aceptar( \"$ls_codprov\", \"$ls_denprov\", \"$ls_codprovdestino\" ,  \"$ls_denprovdestino\" );^_self"));
					
				
					$cell = $row_->appendChild($dom1->createElement('cell'));
					$cell->appendChild($dom1->createTextNode($ls_denprov));												
					$row_->appendChild($cell);
			
			}
			return $dom1->saveXML();
		
			
			
		
		}
      
		
	} // end function buscar_proveedor
		
	

}// end   class sigesp_srh_c_solicitud_adiestramiento
?>