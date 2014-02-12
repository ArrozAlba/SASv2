<?php

class sigesp_srh_c_evaluacion_adiestramiento
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_evaluacion_adiestramiento($path)
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
	
	
	
  
  
function uf_srh_guardarevaluacion_adiestramiento ($ao_evaluacion,$as_operacion="insertar", $ao_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarevaluacion_adiestramiento																		
		//         access: public (sigesp_srh_evaluacion_adiestramiento)															
		//      Argumento: $ao_evaluacion    // arreglo con los datos de la evaluacion de adiestramiento								
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $ao_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica una evaluacion de adiestramiento en la tabla 
		//                 srh_evaluacion_adiestramiento 
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 09/01/2008							Fecha Última Modificación: 09/01/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	 $ao_evaluacion->fecha=$this->io_funcion->uf_convertirdatetobd($ao_evaluacion->fecha);
	 
	 
	  $ls_sql = "UPDATE srh_evaluacion_adiestramiento SET ".
	            "obseval = '$ao_evaluacion->obs'  ".
				"WHERE nroreg= '$ao_evaluacion->nroreg'  AND feceval='$ao_evaluacion->fecha' AND codemp='".$this->ls_codemp."'" ;
		
	  
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó la evaluacion de adiestramiento ".$ao_evaluacion->nroreg;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($ao_seguridad["empresa"],
											$ao_seguridad["sistema"],$ls_evento,$ao_seguridad["logusr"],
											$ao_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
 	 $ao_evaluacion->fecha=$this->io_funcion->uf_convertirdatetobd($ao_evaluacion->fecha);
	 
	
		
	  $ls_sql = "INSERT INTO srh_evaluacion_adiestramiento (nroreg, feceval, obseval, codemp) ".	  
			"VALUES ('$ao_evaluacion->nroreg', '$ao_evaluacion->fecha','$ao_evaluacion->obs','".$this->ls_codemp."')";

	
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la evaluacion de adiestramiento ".$ao_evaluacion->nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($ao_seguridad["empresa"],
												$ao_seguridad["sistema"],$ls_evento,$ao_seguridad["logusr"],
												$ao_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->evaluacion_adiestramiento MÉTODO->uf_srh_guardarevaluacion_adiestramiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				//Guardamos los detalles de la evaluacion de adiestramiento
				$lb_guardo = $this->guardarDetalles_evaluacion($ao_evaluacion, $ao_seguridad);
				if($lb_guardo)	
				{
				  $this->io_sql->commit();
				}
				
		}
	
	return $lb_guardo;
  }
	
	
	
function guardarDetalles_evaluacion ($ao_evaluacion, $ao_seguridad)
  {
    //Borramos los registros anteriores 
	$this-> uf_srh_eliminar_dt_evaluacion_adiestramiento($ao_evaluacion->nroreg, $ao_evaluacion->fecha, $ao_seguridad);
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_per= 0;
	while (($li_per < count($ao_evaluacion->personal)) &&
	       ($lb_guardo))
	{
	  $lb_guardo = $this-> uf_srh_guardar_dt_evaluacion_adiestramiento($ao_evaluacion->personal[$li_per], $ao_seguridad);
	  $li_per++;
	}
	
	return $lb_guardo;    
  }

	
	
	
function uf_srh_eliminarevaluacion_adiestramiento($as_nroreg, $as_fecha,  $ao_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarevaluacion_adiestramiento																		
		//        access:  public (sigesp_srh_evaluacion_adiestramiento)														
		//      Argumento: $as_nroreg       // codigo de la evaluacion de adiestramiento 								
		//                 $ao_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina una evaluacion de adiestramiento en la tabla srh_evaluacion_adiestramiento                         
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 09/01/2008							Fecha Última Modificación: 09/01/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
	$as_fecha=$this->io_funcion->uf_convertirdatetobd($as_fecha);
	$this-> uf_srh_eliminar_dt_evaluacion_adiestramiento($as_nroreg, $as_fecha, $ao_seguridad);
	
    $ls_sql = "DELETE FROM srh_evaluacion_adiestramiento ".
	          "WHERE nroreg = '$as_nroreg' AND feceval = '$as_fecha'   AND codemp='".$this->ls_codemp."'";

  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->evaluacion_adiestramiento MÉTODO->uf_srh_eliminarevaluacion_adiestramiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la evaluacion de adiestramiento ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($ao_seguridad["empresa"],
												$ao_seguridad["sistema"],$ls_evento,$ao_seguridad["logusr"],
												$ao_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				
					$this->io_sql->commit();
			}
	
	return $lb_borro;
  }
	


	
	
function uf_srh_buscar_evaluacion_adiestramiento($as_nroreg,$as_fecha1, $as_fecha2)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_evaluacion_adiestramiento																											
		//      Argumento: $as_nroreg   // número de la solicitud de adiestramiento
		//                 $as_fecha   // fecha de la evaluacion                                                           
		//                 $as_fecsol   //fecha de la soliciutd de adiestramiento
		//                 $as_des   //   descripción de adiestramiento
		//	      Returns: Retorna un XML  																						
		//    Description: Funcion busca una evaluación en la tabla srh_evaluacion_adiestramiento y crea un XML para mostrar    
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 09/01/2008							Fecha Última Modificación: 09/01/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		
		
		$as_fecha1=$this->io_funcion->uf_convertirdatetobd($as_fecha1);
		$as_fecha2=$this->io_funcion->uf_convertirdatetobd($as_fecha2);
	
		
		
		
	    $ls_nrodestino="txtnroreg";
		$ls_fechadestino="txtfecha";
		$ls_desdestino="txtdes";
		$ls_codsoldestino="txtcodper";
		$ls_nomdestino="txtnomper";
		$ls_denuniaddestino="txtdenuniad";
		$ls_codprovdestino="txtcodprov";
		$ls_denprovdestino="txtdenprov";
		$ls_fecinidestino="txtfecini";
		$ls_fecfindestino="txtfecfin";
		$ls_durhrasdestino="txtdurhras";
		$ls_costodestino="txtcosto";
		$ls_obsdestino="txtobs";
		$ls_fecsoldestino="txtfecsol";
		$ls_obsevaldestino="txtobseval";
	
		
		$lb_valido=true;
		
		
				
		$ls_sql= "SELECT *  FROM srh_evaluacion_adiestramiento  INNER JOIN srh_solicitud_adiestramiento ON (srh_solicitud_adiestramiento.nroreg = srh_evaluacion_adiestramiento.nroreg) INNER JOIN sno_personal ON (srh_solicitud_adiestramiento.codper = sno_personal.codper)  INNER JOIN rpc_proveedor ON (srh_solicitud_adiestramiento.codprov = rpc_proveedor.cod_pro)
		           INNER JOIN srh_unidadvipladin ON (srh_solicitud_adiestramiento.codunivipladin = srh_unidadvipladin.codunivipladin) ".
				"   AND srh_evaluacion_adiestramiento.feceval  between  '".$as_fecha1."' AND '".$as_fecha2."' ".
				"   AND srh_evaluacion_adiestramiento.nroreg like '$as_nroreg' ".
				" ORDER BY srh_evaluacion_adiestramiento.nroreg";
				
			
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->evaluacion_adiestramiento MÉTODO->uf_srh_buscar_evaluacion_adiestramiento( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
					
					$ls_fecha=$this->io_funcion->uf_formatovalidofecha($row["feceval"]);
				    $ls_fecha=$this->io_funcion->uf_convertirfecmostrar($ls_fecha);
					
					$ls_fecsol=$this->io_funcion->uf_formatovalidofecha($row["fecha"]);
				    $ls_fecsol=$this->io_funcion->uf_convertirfecmostrar($ls_fecsol);
					
					$ls_des = trim (htmlentities  ($row["descripcion"]));
					$ls_codsol=$row["codper"];
					$ls_apesol= trim (htmlentities ($row["apeper"]));
					if ($ls_apeson!=0) {
					  $ls_nomsol= trim (htmlentities ($row['nomper'])).'  '.trim (htmlentities ($row['apeper']));
					}
					else {
					  $ls_nomsol= trim (htmlentities ($row["nomper"]));
					}
								
					$ls_denuniad= trim (htmlentities  ($row["denunivipladin"]));
					$ls_codprov=$row["codprov"];
					$ls_denprov=trim (htmlentities  ($row["nompro"]));
					
					$ls_fecini=$this->io_funcion->uf_formatovalidofecha($row["fecini"]);
				    $ls_fecini=$this->io_funcion->uf_convertirfecmostrar($ls_fecini);
					
					$ls_fecfin=$this->io_funcion->uf_formatovalidofecha($row["fecfin"]);
				    $ls_fecfin=$this->io_funcion->uf_convertirfecmostrar($ls_fecfin);
					
			
					$ls_durhras=$row["durhras"];
					$ls_costo=$row["costo"];
					$ls_obs=htmlentities ($row["observacion"]);
					$ls_obseval=htmlentities  ($row["obseval"]);
					
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codper']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($row['nroreg']." ^javascript:aceptar( \"$ls_nroreg\", \"$ls_fecsol\", \"$ls_des\", \"$ls_codsol\", \"$ls_nomsol\", \"$ls_apesol\",  \"$ls_denuniad\", \"$ls_fecha\", \"$ls_codprov\",\"$ls_denprov\", \"$ls_fecini\", \"$ls_fecfin\", \"$ls_durhras\" ,  \"$ls_costo\" ,  \"$ls_obs\", \"$ls_obseval\", \"$ls_fecha\" , \"$ls_nrodestino\" ,  \"$ls_fecsoldestino\" ,  \"$ls_desdestino\" ,  \"$ls_codsoldestino\" ,  \"$ls_nomdestino\" ,   \"$ls_denuniaddestino\" , \"$ls_fechadestino\" ,\"$ls_codprovdestino\" ,  \"$ls_denprovdestino\" ,  \"$ls_fecinidestino\" ,  \"$ls_fecfindestino\" ,  \"$ls_durhrasdestino\" ,  \"$ls_costodestino\" ,  \"$ls_obsdestino\", \"$ls_obsevaldestino\", \"$ls_fechadestino\");^_self"));
					
				
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_fecsol));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_des));												
					$row_->appendChild($cell);
				
					
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_fecha));													
					$row_->appendChild($cell);
			
					
			
			}
			return $dom->saveXML();
		
			
			
		
		}
      
		
	} // end function buscar_evaluacion_adiestramiento
	

//FUNCIONES PARA EL MANEJO DEL DETALLE DE LA evaluacion DE ADIESTRAMIENTO

function uf_srh_guardar_dt_evaluacion_adiestramiento($ao_evaluacion, $ao_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_dt_evaluacion_adiestramiento															
		//         access: public (sigesp_dt_srh_evaluacion_adiestramiento)														
		//      Argumento: $ao_evaluacion    // arreglo con los datos de los detalle de la evaluacion de adiestramiento					
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $ao_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica una evaluacion de adiestramiento en la tabla 
		//				   srh_dt_evaluacion_adiestramiento           
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 09/01/2008							Fecha Última Modificación: 09/01/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 $ao_evaluacion->fecha=$this->io_funcion->uf_convertirdatetobd($ao_evaluacion->fecha);
	  $ao_evaluacion->asistencia = trim ($ao_evaluacion->asistencia);
	  $ls_sql = "INSERT INTO srh_dt_evaluacion_adiestramiento (nroreg, feceval,codper,asistencia, codemp) ".	  
	            " VALUES ('$ao_evaluacion->nroreg','$ao_evaluacion->fecha','$ao_evaluacion->codper', '$ao_evaluacion->asistencia','".$this->ls_codemp."')";

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el detalle de evaluación de la evaluacion de adiestramiento ".$ao_evaluacion->nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($ao_seguridad["empresa"],
												$ao_seguridad["sistema"],$ls_evento,$ao_seguridad["logusr"],
												$ao_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->evaluacion_adiestramiento MÉTODO->uf_srh_guardar_dt_evaluacion_adiestramiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
	
function uf_srh_eliminar_dt_evaluacion_adiestramiento($as_nroreg,$as_fecha, $ao_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_dt_evaluacion_adiestramiento																
		//        access:  public (sigesp_srh_dt_evaluacion_adiestramiento)														
		//      Argumento: $as_nroreg        // numero de la evaluacion de adiestramiento
		//                 $ao_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																				    
		//    Description: Funcion que elimina una evaluacion de adiestramiento en la tabla srh_dt_evaluacion_adiestramiento
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 09/01/2008							Fecha Última Modificación: 09/01/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_dt_evaluacion_adiestramiento ".
	          " WHERE nroreg='$as_nroreg'  AND  feceval='$as_fecha'  AND codemp='".$this->ls_codemp."'";
		  

	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->evaluacion_adiestramiento MÉTODO->uf_srh_eliminar_dt_evaluacion_adiestramiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el detalle de evaluación de requisitos mínimos ".$as_codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($ao_seguridad["empresa"],
												$ao_seguridad["sistema"],$ls_evento,$ao_seguridad["logusr"],
												$ao_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
			
	
	return $lb_borro;
	
  }
  
  
  

function uf_srh_load_evaluacion_adiestramiento_campos($as_nroreg, $as_fecha,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_evaluacion_adiestramiento_campos
		//		   Access: public (sigesp_srh_d_escala)
		//	    Arguments: as_nroreg  // número de la evaluacion de adiestramiento
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de una soliciutd de adiestramiento
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_fecha=$this->io_funcion->uf_convertirdatetobd($as_fecha);
		$ls_sql="SELECT * ".
				"  FROM srh_dt_evaluacion_adiestramiento, srh_dt_solicitud_adiestramiento, sno_personal ".
				"  WHERE srh_dt_evaluacion_adiestramiento.codemp='".$this->ls_codemp."'".
				"  AND srh_dt_evaluacion_adiestramiento.nroreg = '".$as_nroreg."' ".
				"  AND srh_dt_evaluacion_adiestramiento.feceval = '".$as_fecha."' ".
				"  AND srh_dt_solicitud_adiestramiento.nroreg = '".$as_nroreg."' ".
				"  AND srh_dt_evaluacion_adiestramiento.codper =  srh_dt_solicitud_adiestramiento.codper ".
				"  AND srh_dt_evaluacion_adiestramiento.codper = sno_personal.codper ".
				" ORDER BY srh_dt_evaluacion_adiestramiento.codper ";

				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->evaluacion_adiestramiento MÉTODO->uf_srh_load_evaluacion_adiestramiento_campos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_codper=$row["codper"];
					$ls_carper=htmlentities($row["carper"]);
					$ls_dep=htmlentities  ($row["dep"]);
					$ls_apeper=htmlentities ($row["apeper"]);
					$ls_asistencia=$row["asistencia"];
					
					if ($ls_apeper!=0) {
					$ls_nomper= trim (htmlentities ($row["nomper"])). ' '.trim (htmlentities ($row["apeper"]));
					
					}
					else  {
					 $ls_nomper= trim (htmlentities ($row["nomper"]));
					}
					
					
					$ao_object[$ai_totrows][1]="<input name=txtcodper".$ai_totrows." type=text id=txtcodper".$ai_totrows." class=sin-borde size=15 readonly  maxlength=10 value='".$ls_codper."'>";
				    $ao_object[$ai_totrows][2]="<input name=txtnomper".$ai_totrows." type=text id=txtnomper".$ai_totrows." class=sin-borde size=35 readonly value='".$ls_nomper."'>";
				   $ao_object[$ai_totrows][3]="<input name=txtcarper".$ai_totrows." type=text id=txtcarper".$ai_totrows." class=sin-borde  size=20 readonly value='".$ls_carper."'>";
				   $ao_object[$ai_totrows][4]="<input name=txtdep".$ai_totrows." type=text id=txtdep".$ai_totrows." class=sin-borde  size=30 readonly value='".$ls_dep."'>";
				   
				   if ($ls_asistencia=='1') {
				  $ao_object[$ai_totrows][5]="<input type=checkbox name=asistencia".$ai_totrows." id=asistencia".$ai_totrows."  checked>";  }	          
                 else {
				  $ao_object[$ai_totrows][5]="<input type=checkbox name=asistencia".$ai_totrows." id=asistencia".$ai_totrows." >";  }	          
 				 
			
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	
function uf_srh_consultar_evaluacion_adiestramiento ($as_nroreg,&$ai_totrows,&$ao_object)
{
		
		$lb_valido=true;
		
		
		
		$ls_sql= "SELECT * FROM srh_solicitud_adiestramiento INNER JOIN srh_dt_solicitud_adiestramiento ON (srh_solicitud_adiestramiento.nroreg = srh_dt_solicitud_adiestramiento.nroreg) INNER JOIN sno_personal ON  (srh_dt_solicitud_adiestramiento.codper = sno_personal.codper)".
		        "  WHERE srh_solicitud_adiestramiento.nroreg='".$as_nroreg."'".
				" ORDER BY srh_solicitud_adiestramiento.nroreg";
		

	 	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->evaluacion_adiestramiento MÉTODO->uf_srh_consultar_evaluacion_adiestramiento( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
					$ls_codper=$row["codper"];
					$ls_carper=trim (htmlentities ($row["carper"]));
					$ls_dep=trim (htmlentities ($row["dep"]));
					$ls_apeper=trim (htmlentities($row["apeper"]));
					
					if ($ls_apeper!=0) {
					$ls_nomper= trim (htmlentities ($row["nomper"])). ' '.trim (htmlentities ($row["apeper"]));
					
					}
					else  {
					 $ls_nomper=trim (htmlentities ($row["nomper"]));
					}
					
					
					$ao_object[$ai_totrows][1]="<input name=txtcodper".$ai_totrows." type=text id=txtcodper".$ai_totrows." class=sin-borde size=15 readonly  maxlength=10 value='".$ls_codper."'>";
				    $ao_object[$ai_totrows][2]="<input name=txtnomper".$ai_totrows." type=text id=txtnomper".$ai_totrows." class=sin-borde size=35  readonly value='".$ls_nomper."'>";
				   $ao_object[$ai_totrows][3]="<input name=txtcarper".$ai_totrows." type=text id=txtcarper".$ai_totrows." class=sin-borde  size=20 readonly value='".$ls_carper."'>";
				   $ao_object[$ai_totrows][4]="<input name=txtdep".$ai_totrows." type=text id=txtdep".$ai_totrows." class=sin-borde  size=30  readonly value='".$ls_dep."'>";
				  $ao_object[$ai_totrows][5]="<input type=checkbox name=asistencia".$ai_totrows." id=asistencia".$ai_totrows." value='1' >";
					
				}
		
		}
		else 
		  {
		     $this->io_msg->message("No se encontraron Registros con esos datos.");
	 		  $ai_totrows=1;	
			 $ao_object[$ai_totrows][1]="<input name=txtcodper".$ai_totrows." type=text id=txtcodper".$ai_totrows." class=sin-borde size=15 readonly  maxlength=10>";
			 $ao_object[$ai_totrows][2]="<input name=txtnomper".$ai_totrows." type=text id=txtnomper".$ai_totrows." class=sin-borde readonly size=35>";
		    $ao_object[$ai_totrows][3]="<input name=txtcarper".$ai_totrows." type=text id=txtcarper".$ai_totrows." class=sin-borde readonly size=20>";
		   $ao_object[$ai_totrows][4]="<input name=txtdep".$ai_totrows." type=text id=txtdep".$ai_totrows." class=sin-borde  readonly =30>";
		   $ao_object[$ai_totrows][5]="<input type=checkbox name=asistencia".$ai_totrows." id=asistencia".$ai_totrows."  value='1'>";
			
		  }  
		return $lb_valido;
	
		}
      
		
	} // end function buscar_evaluacion_adiestramiento
		

	

}// end   class sigesp_srh_c_evaluacion_adiestramiento
?>