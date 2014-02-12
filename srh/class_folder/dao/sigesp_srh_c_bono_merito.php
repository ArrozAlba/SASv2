<?php

class sigesp_srh_c_bono_merito
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_bono_merito($path)
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
	
function  uf_srh_validar_fecha_bono_merito($as_codper,$as_fecha)
{

	    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_srh_validar_fecha_bono_merito																	
		//         access: public (sigesp_srh_bono_merito)														    	
		//    	Argumento: $as_codper    // código del personal			
		//                 $as_fecha    //  fecha de la evaluacion de bono por merito
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que valida que en un mismo mes no existan dos evaluaciones de bono por merito             
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 15/08/2008							Fecha Última Modificación: 						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$lb_valido=true;
		
		$ls_sql= "SELECT fecha ".
				" FROM srh_bono_merito ".
				" WHERE srh_bono_merito.codper = '$as_codper' ".
				" ORDER BY srh_bono_merito.fecha";
			
			 
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->bono_merito MÉTODO->uf_srh_validar_fecha_bono_merito( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
			
			if ($this->io_sql->num_rows($rs_data)!=0)
			{
				$ld_ano= substr($as_fecha,0,4);
				$ld_mes= substr($as_fecha,5,2);
						
				while (($row=$this->io_sql->fetch_row($rs_data)) && ($lb_valido))
				{
					
						$ls_fecha_bono=$this->io_funcion->uf_formatovalidofecha($row["fecha"]);
						
						$ld_ano_bono= substr($ls_fecha_bono,0,4);
						$ld_mes_bono= substr($ls_fecha_bono,5,2);
						
						if (($ld_ano_bono==$ld_ano) && ($ld_mes_bono==$ld_mes))
						{
							$lb_valido=false;
						}
						
				}
			}
			
			
			
		  
		 }
	return $lb_valido;

}

  
function uf_srh_guardarbono_merito ($ao_bono,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarbono_merito																		
		//         access: public (sigesp_srh_bono_merito)														    	
		//    	Argumento: $ao_bono    // arreglo con los datos de la puntuación de bono por mérito								
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica un bono por mérito en la tabla srh_bono_merito             
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 24/12/2007							Fecha Última Modificación: 24/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	     $lb_valfecha=true;
		 $lb_valido=true;
		$as_codper=$ao_bono->codper;
		
		if ($as_operacion == "modificar")
		{
			 $this->io_sql->begin_transaction();
			 
			 $ao_bono->fecha=$this->io_funcion->uf_convertirdatetobd($ao_bono->fecha);
			 
			 
			  $ls_sql = "UPDATE srh_bono_merito SET ".
						"total = '$ao_bono->total',  ".
						"codtipper = '$ao_bono->codtipper',  ".
						"codpun = '$ao_bono->codpun'  ".
						"WHERE codper= '$ao_bono->codper'  AND fecha = '$ao_bono->fecha' AND codemp='".$this->ls_codemp."'" ;
				
			  
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="UPDATE";
					$ls_descripcion ="Modificó la puntuación de bono por mérito ".$as_codper;
					$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				$lb_guardo = $this->io_sql->execute($ls_sql);
	
				 if($lb_guardo===false)
					{
						$this->io_msg->message("CLASE->bono_merito MÉTODO->uf_srh_guardarbono_merito ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
						$lb_valido=false;
						$this->io_sql->rollback();
					}
					else
					{
							$lb_valido=true;
							$lb_guardo=false;
							if ($lb_valido)
							{  
							   //Guardamos los items de la puntuación de bono por mérito
								$lb_guardo = $this->uf_srh_guardarDetalles_Bono($ao_bono, $aa_seguridad);					
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
		else
		{ 
			$ao_bono->fecha=$this->io_funcion->uf_convertirdatetobd($ao_bono->fecha);
			$lb_valfecha=$this->uf_srh_validar_fecha_bono_merito($ao_bono->codper,$ao_bono->fecha);
			if ($lb_valfecha)
			{
				$this->io_sql->begin_transaction();
				
				  $ls_sql = "INSERT INTO srh_bono_merito (codper, fecha, total, codtipper, codpun, codemp) ".	  
							"VALUES ('$ao_bono->codper','$ao_bono->fecha', '$ao_bono->total','$ao_bono->codtipper','$ao_bono->codpun','".$this->ls_codemp."')";
				
			
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
							$ls_evento="INSERT";
							$ls_descripcion ="Insertó la puntuación de bono por mérito de la persona".$as_codper;
							$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
															$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
															$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////	
					
					$lb_guardo = $this->io_sql->execute($ls_sql);
	
					 if($lb_guardo===false)
						{
							$this->io_msg->message("CLASE->bono_merito MÉTODO->uf_srh_guardarbono_merito ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
							$lb_valido=false;
							$this->io_sql->rollback();
						}
						else
						{
								$lb_valido=true;
								$lb_guardo=false;
								if ($lb_valido)
								{  
								   //Guardamos los items de la puntuación de bono por mérito
									$lb_guardo = $this->uf_srh_guardarDetalles_Bono($ao_bono, $aa_seguridad);					
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
		  }//fin del else
		
						
	return array ($lb_valido,$lb_valfecha);
  }
	
	
	
function uf_srh_guardarDetalles_Bono ($ao_bono, $aa_seguridad)
  {
   		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarDetalles_Bono													
		//    	Argumento: $ao_bono         // arreglo con los datos de la puntuación de bono por mérito								
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que guarda los detalles de un bono por mérito en la tabla srh_bono_merito             
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 24/12/2007							Fecha Última Modificación: 24/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  
    //Borramos los registros anteriores 
	$this-> uf_srh_eliminar_dt_bono_merito($ao_bono->codper, $ao_bono->fecha , $aa_seguridad);
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_bono = 0;
	while (($li_bono < count($ao_bono->pun_bono)) &&
	       ($lb_guardo))
	{
	  $lb_guardo = $this->uf_srh_guardar_dt_bono_merito($ao_bono->pun_bono[$li_bono], $aa_seguridad);
	  $li_bono++;
	}
	
	return $lb_guardo;    
  }

	
	
	
function uf_srh_eliminarbono_merito($as_codper, $as_fecha, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarbono_merito																		
		//        access:  public (sigesp_srh_bono_merito)														
		//      Argumento: $as_codper        // codigo del personal 
		//                 $as_fecha        //  fecha del bono por mérito										
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina un bono por mérito en la tabla srh_bono_merito                         
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 24/12/2007							Fecha Última Modificación: 24/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
	$as_fecha=$this->io_funcion->uf_convertirdatetobd($as_fecha);
	$this-> uf_srh_eliminar_dt_bono_merito($as_codper, $as_fecha, $aa_seguridad);
    $ls_sql = "DELETE FROM srh_bono_merito ".
	          "WHERE codper = '$as_codper' AND fecha = '$as_fecha'   AND codemp='".$this->ls_codemp."'";

  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->bono_merito MÉTODO->uf_srh_eliminarbono_merito ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la puntuación de bono por mérito ".$as_codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				
					$this->io_sql->commit();
			}
	
	return $lb_borro;
  }
	


	
	
	
function uf_srh_buscar_bono_merito($as_codper,$as_apeper,$as_nomper,$as_fecha1,$as_fecha2)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_bono_merito																		//
		//         access: public (sigesp_srh_bono_merito)												
		//      Argumento: $as_codper   //  codigo de la persona                                                             
		//                 $as_apeper   //  apellido de la persona                                                            
		//                 $as_nomper   //  nombre de la persona                                                             
		//                 $as_fecha   //   fecha de la puntuación de bono por mérito
		//	      Returns: Retorna un XML  																						
		//    Description: Funcion busca un bono por mérito en la tabla srh_bono_merito y crea un XML para mostrar    
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 24/12/2007							Fecha Última Modificación: 24/12/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
     	$as_fecha1=$this->io_funcion->uf_convertirdatetobd($as_fecha1);
		$as_fecha2=$this->io_funcion->uf_convertirdatetobd($as_fecha2);
		
	
	
	    $ls_codperdestino="txtcodper";
		$ls_fechadestino="txtfecha";
		$ls_nomdestino="txtnomper";
		$ls_totaldestino="txttotal";
		$ls_tipperdestino="txttipper";
		$ls_codtipperdestino="txtcodtipper";
		$ls_dentipperdestino="txtdentipper";
		$ls_codpundestino="txtcodesc";
		$ls_denpundestino="txtdenesc";
		
		$lb_valido=true;
		
		
				
		$ls_sql= "SELECT srh_bono_merito.*, sno_personal.nomper, sno_personal.apeper, sno_tipopersonalsss.dentippersss, ".
		        " srh_tipopersonal.dentipper, srh_puntosunitri.denpun ".
				" FROM srh_bono_merito, sno_personal,sno_tipopersonalsss, srh_tipopersonal, srh_puntosunitri".
				" WHERE sno_personal.codper = srh_bono_merito.codper ".
				" AND sno_personal.codtippersss = sno_tipopersonalsss.codtippersss ".
				" AND srh_tipopersonal.codtipper = srh_bono_merito.codtipper ".
				" AND srh_puntosunitri.codpun = srh_bono_merito.codpun ".
				" AND srh_bono_merito.codper LIKE '$as_codper' ".
				"   AND fecha  BETWEEN  '".$as_fecha1."' AND '".$as_fecha2."' ".
				"   AND nomper LIKE '$as_nomper' ".
				"   AND apeper LIKE '$as_apeper' ".
				" ORDER BY srh_bono_merito.codper";
			
			 
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->bono_merito MÉTODO->uf_srh_buscar_bono_merito( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
					$ls_fecha=$this->io_funcion->uf_formatovalidofecha($row["fecha"]);
				    $ls_fecha=$this->io_funcion->uf_convertirfecmostrar($ls_fecha);
					$ls_apeper = trim (htmlentities ($row["apeper"]));
					$ls_nomper= trim (htmlentities  ($row["nomper"]));
					$ls_dentippersss= htmlentities  ($row["dentippersss"]);
					$ls_codtipper=trim ($row["codtipper"]);
					$ls_dentipper=trim (htmlentities  ($row["dentipper"]));
					$ls_codpun=trim ($row["codpun"]);
					$ls_denpun=trim (htmlentities  ($row["denpun"]));
					
					$ls_total=trim ($row["total"]);
										
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codper']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($row['codper']." ^javascript:aceptar(\"$ls_codper\", \"$ls_fecha\", \"$ls_apeper\", \"$ls_nomper\",  \"$ls_total\",\"$ls_codperdestino\", \"$ls_fechadestino\",  \"$ls_nomdestino\", 	 \"$ls_totaldestino\",\"$ls_dentippersss\",\"$ls_tipperdestino\",\"$ls_codtipper\",\"$ls_codtipperdestino\",\"$ls_dentipper\",\"$ls_dentipperdestino\",\"$ls_codpun\",\"$ls_codpundestino\",\"$ls_denpun\",\"$ls_denpundestino\" );^_self"));
					
				
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
			
					
			
			}
			return $dom->saveXML();
		
			
			
		
		}
      
		
	} // end function buscar_bono_merito
	

//FUNCIONES PARA EL MANEJO DEL DETALLE DE LAS CAUSAS de puntuación de bono por mérito

function uf_srh_guardar_dt_bono_merito($ao_bono, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_dt_bono_merito															     	
		//         access: public (sigesp_dt_srh_bono_merito)														
		//      Argumento: $ao_bono    // arreglo con los datos de los detalle de la puntuación de bono por mérito					
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta los detalles del bono por mérito en la tabla srh_dt_bono_merito           
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 24/12/2007							Fecha Última Modificación: 24/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 
	 $ao_bono->fecha=$this->io_funcion->uf_convertirdatetobd($ao_bono->fecha);
	 
	  if (($ao_bono->puntos==0) || ($ao_bono->puntos=='0'))
	 {
	    $ao_bono->puntos=0;
	 }
	 
	  $ls_sql = "INSERT INTO srh_dt_bono_merito (codper,fecha,codpunt, puntos,observacion, codemp) ".	  
	            " VALUES ('$ao_bono->codper','$ao_bono->fecha','$ao_bono->codpunt','$ao_bono->puntos','$ao_bono->obs','".$this->ls_codemp."')";
		

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el detalle de puntuación de bono por mérito ".$ao_bono->codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->bono_merito MÉTODO->uf_srh_guardar_dt_bono_merito ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
	
function uf_srh_eliminar_dt_bono_merito($as_codper,$as_fecha, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_dt_bono_merito																														
		//      Argumento: $as_codper        // código del personal
		//                 $as_fecha         // fecha del bono por mérito
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																				    
		//    Description: Funcion que elimina un bono por mérito en la tabla srh_dt_bono_merito                      
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 24/12/2007							Fecha Última Modificación: 24/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_dt_bono_merito ".
	          " WHERE codper='$as_codper' AND fecha ='$as_fecha'   AND codemp='".$this->ls_codemp."'";
			  

	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->bono_merito MÉTODO->uf_srh_eliminar_dt_bono_merito ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el detalle de puntuación de bono por mérito ".$as_codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
			
	
	return $lb_borro;
	
  }
  
  
  

function uf_srh_load_bono_merito_campos($as_codper,$as_fecha,&$ai_totrows,&$ao_object)
	{
		
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_bono_merito_campos
		//	    Arguments: as_codper  // código del personal
		//                 as_fecha   // fecha del bono por mérito
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de bono mérito
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$as_fecha=$this->io_funcion->uf_convertirdatetobd($as_fecha);
				
		$ls_sql="SELECT * ".
				"  FROM srh_dt_bono_merito, srh_puntuacion_bono_merito ".
				"  WHERE srh_dt_bono_merito.codemp='".$this->ls_codemp."'".
				"  AND srh_dt_bono_merito.codper = '".$as_codper."' ".
				"  AND srh_dt_bono_merito.fecha = '".$as_fecha."' ".
				"  AND srh_dt_bono_merito.codpunt = srh_puntuacion_bono_merito.codpunt".
				" ORDER BY codper ";
  
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->bono_merito MÉTODO->uf_srh_load_bono_merito_campos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_codpunt=$row["codpunt"];
				$ls_nombpunt= trim (htmlentities ($row["nompunt"]));
				$ls_valini=($row['valini']. ' / ' .$row['valfin']) ;
				$li_puntos= trim ($row["puntos"]);
				$ls_obs= trim (htmlentities  ($row["observacion"]));
			
				
				$ao_object[$ai_totrows][1]="<input name=txtcodpunt".$ai_totrows." type=text id=txtcodpunt".$ai_totrows." class=sin-borde size=15  readonly  value='".$ls_codpunt."'>";
				$ao_object[$ai_totrows][2]="<input name=txtnombpunt".$ai_totrows." type=text id=txtnombpunt".$ai_totrows." class=sin-borde size=70  readonly value='".$ls_nombpunt."'>";
				$ao_object[$ai_totrows][3]="<input name=txtvalini".$ai_totrows." type=text id=txtvalini".$ai_totrows." class=sin-borde size=7  readonly value='".$ls_valini."'>";
				$ao_object[$ai_totrows][4]="<input name=txtpuntos".$ai_totrows." type=text id=txtpuntos".$ai_totrows." class=sin-borde size=5 maxlength=4 onKeyUp='javascript: ue_validarnumero2(this);' onBlur= 'javascript: validar_escala (".$ai_totrows.");' onChange='javascript: ue_suma (txttotal);' value='".$li_puntos."'>";
				$ao_object[$ai_totrows][5]="<input name=txtobs".$ai_totrows." type=text id=txtobs".$ai_totrows." class=sin-borde size=40  onKeyUp='ue_validarcomillas(this);' value='".$ls_obs."'>";
			   
				
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load
	//-----------------------------------------------------------------------------------------------------------------------------------	
function uf_srh_consultar_items ($as_codtipper, &$ai_totrows,&$ao_object)
{
		
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_consultar_items
		//	    Arguments: $as_codtipper // código del tipo de personal
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de la puntuación bono mérito
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$lb_valido=true;
		
		$ls_sql= "SELECT * FROM srh_puntuacion_bono_merito   ".
				" WHERE srh_puntuacion_bono_merito.codtipper = '$as_codtipper' ".
				" ORDER BY codpunt";
				

	 	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->bonomerito MÉTODO->uf_srh_consultar_items( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
					$ls_codpunt=$row["codpunt"];
					$ls_nombpunt=trim (htmlentities  ($row["nompunt"]));
					$ls_valini=($row['valini']. ' / ' .$row['valfin']) ;
					
					$ao_object[$ai_totrows][1]="<input name=txtcodpunt".$ai_totrows." type=text id=txtcodpunt".$ai_totrows." class=sin-borde size=15  readonly  value='".$ls_codpunt."'>";
					$ao_object[$ai_totrows][2]="<input name=txtnombpunt".$ai_totrows." type=text id=txtnombpunt".$ai_totrows." class=sin-borde size=70  readonly value='".$ls_nombpunt."'>";
					$ao_object[$ai_totrows][3]="<input name=txtvalini".$ai_totrows." type=text id=txtvalini".$ai_totrows." class=sin-borde size=7  readonly value='".$ls_valini."'>";
					$ao_object[$ai_totrows][4]="<input name=txtpuntos".$ai_totrows." type=text id=txtpuntos".$ai_totrows." class=sin-borde size=5 maxlength=4 onKeyUp='javascript: ue_validarnumero2(this);' onBlur= 'javascript: validar_escala (".$ai_totrows.");' onChange='javascript: ue_suma (txttotal);' >";
					$ao_object[$ai_totrows][5]="<input name=txtobs".$ai_totrows." type=text id=txtobs".$ai_totrows." class=sin-borde size=40 onKeyUp='ue_validarcomillas(this);'  >";
						  
					}
		
		}
		else 
		  {
		     $this->io_msg->message("No se encontraron Items de Evaluación.");
	 		$ai_totrows=1;	
			$ao_object[$ai_totrows][1]="<input name=txtcodpunt".$ai_totrows." type=text id=txtcodpunt".$ai_totrows." class=sin-borde size=15  readonly  >";
				$ao_object[$ai_totrows][2]="<input name=txtnombpunt".$ai_totrows." type=text id=txtnombpunt".$ai_totrows." class=sin-borde size=70  readonly >";
				$ao_object[$ai_totrows][3]="<input name=txtvalini".$ai_totrows." type=text id=txtvalini".$ai_totrows." class=sin-borde size=7  readonly >";
				$ao_object[$ai_totrows][4]="<input name=txtpuntos".$ai_totrows." type=text id=txtpuntos".$ai_totrows." class=sin-borde size=5 maxlength=4 onKeyUp='javascript: ue_validarnumero2(this);' onBlur= 'javascript: validar_escala (".$ai_totrows.");' onChange='javascript: ue_suma (txttotal);' >";
				$ao_object[$ai_totrows][5]="<input name=txtobs".$ai_totrows." type=text id=txtobs".$ai_totrows." class=sin-borde size=40  onKeyUp='ue_validarcomillas(this);' >";
			
			
		  }  
		return $lb_valido;
	
		}
      
		
	} // end uf_srh_consultar_items 	
	
	

}// end   class sigesp_srh_c_bono_merito
?>
