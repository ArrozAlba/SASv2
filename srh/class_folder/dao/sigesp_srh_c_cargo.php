<?php

class sigesp_srh_c_cargo
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_cargo($path)
	{   require_once($path."shared/class_folder/class_sql.php");
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
	
	
	
	
	
	function uf_srh_select_cargo($as_codcar)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_cargo
		//         reqess: public (sigesp_sno_cargo)
		//      Argumento: $as_codcar    // codigo de cargo de cargo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un cargo en la tabla de  sno_cargo
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 19/02/2008							Fecha Última Modificación: 19/02/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sno_cargo  ".
				  " WHERE codcar='".$as_codcar."'".
				  " AND codemp='".$this->ls_codemp."'" ;
   
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->cargo MÉTODO->uf_srh_select_cargo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_cargo

	function  uf_srh_insert_cargo($as_codcar,$as_descar,$as_codnom,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_cargo
		//         reqess: public (sigesp_sno_cargo)
		//      Argumento: $as_codtipart   // codigo de cargo 
	    //                 $as_dentipart   // denominacion de cargo 
	    //                 $as_obstipart   // observacion de cargo 
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un cargo de cargo en la tabla de sno_cargo
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 19/02/2008							Fecha Última Modificación: 19/02/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO sno_cargo (codcar, descar,codnom,codemp) ".
					" VALUES('".$as_codcar."','".$as_descar."','".$as_codnom."','".$this->ls_codemp."')" ;
		
	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->cargo MÉTODO->uf_srh_insert_cargo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el cargo ".$as_codcar;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_cargo

	function uf_srh_update_cargo($as_codcar,$as_descar,$as_codnom,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_cargo
		//         reqess: public (sigesp_sno_cargo)
		//      Argumento: $as_codtipart   // codigo de cargo 
	    //                 $as_dentipart   // denominacion de cargo 
	    //                 $as_obstipart   // observacion de cargo 
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un cargo de cargo en la tabla de sno_cargo
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 19/02/2008							Fecha Última Modificación: 19/02/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE sno_cargo SET   descar='". $as_descar ."', codnom='".$as_codnom."'". 
				   " WHERE codcar='" . $as_codcar ."'".
				   " AND codemp='".$this->ls_codemp."'";
		
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->cargo MÉTODO->uf_srh_update_cargo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el cargo ".$as_codcar;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_cargo
	
	
		
function uf_select_cargo_nomina ($as_codcar)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cargo_nomina
		//		   Access: private
 		//	    Arguments: as_codcar // código del cargo
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el cargo esta asociada a una asignacion de cargo
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 25/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$as_codcar= trim ($as_codcar);
		$ls_sql= "SELECT codasicar ".
				 "  FROM sno_asignacioncargo".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND codasicar= '$as_codcar'";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->cargo ->uf_select_cargo_nomina ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	
	function uf_select_cargo_personal($as_codcar)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cargo_personal
		//		   Access: private
 		//	    Arguments: as_codcar // código del cargo
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el cargo esta asociada a una personal de nomina
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 25/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$as_codcar= trim ($as_codcar);
		$ls_sql= "SELECT codcar ".
				 "  FROM sno_personalnomina".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND codcar= '$as_codcar'";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->cargo ->uf_select_cargo_personal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	
	

	function uf_srh_delete_cargo($as_codcar, $as_codnom,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_cargo
		//         reqess: public (sigesp_sno_cargo)
		//      Argumento: $as_codtipart   // codigo de cargo de cargo
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un cargo  en la tabla de sno_cargo 
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 19/02/2008							Fecha Última Modificación: 19/02/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	     $as_codcar= trim ($as_codcar);
		  $lb_valido=false;
	     $lb_existe=true;
		
		if (($this->uf_select_cargo_personal ($as_codcar)===false)&&
		     ($this->uf_select_cargo_nomina($as_codcar)===false))
		{
		   $lb_existe=false;
		   $this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM sno_cargo".
						 " WHERE codcar= '$as_codcar' AND codnom ='$as_codnom'";
						 
				
			$li_row=$this->io_sql->execute($ls_sql);
			
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->cargo MÉTODO->uf_srh_delete_cargo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				$this->io_sql->commit();
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el cargo ".$as_codcar;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				
			}
		}
				
		return array($lb_valido,$lb_existe);
	} // end function uf_srh_delete_cargo
	

	
	
	function uf_srh_buscar_cargo($as_codcar,$as_descar,$as_codnom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_cargo
		//         reqess: private
		//      Argumento: $as_ccodcar    // codigo de cargo
		//                 $as_descar     // descccripcion de cargo
		//                 $as_codnom  // codigo del nómina
		//	      Returns: Retorna un Booleano
		//    Description: 
		//	   Creado Por: María Beatriz Unda
 		// Fecha Creación: 19/02/2008							Fecha Última Modificación: 19/02/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$ls_coddestino="txtcodcar";
		$ls_dendestino="txtdescar";
		$ls_codnomdestino="txtcodnom";
		$ls_desnomdestino="txtdesnom";
	
		
		$lb_valido=true;
		$ls_sql="SELECT codcar , descar,sno_cargo.codnom,desnom FROM sno_cargo, sno_nomina ".
				 "WHERE sno_nomina.codnom= sno_cargo.codnom ".
                 "AND  codcar like '".$as_codcar."' ".
                 "AND descar like '".$as_descar."' ".	
				 "AND sno_cargo.codcar <> '0000000000'".	
                 "AND sno_cargo.codnom like '".$as_codnom."' ";
                 "ORDER BY sno_cargo.codnom, codcar ";
				 
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->cargo MÉTODO->uf_srh_buscar_cargo( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			 
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
					$ls_codcar=$row["codcar"];
					$ls_descar=htmlentities($row['descar']); 
					$ls_codnom=$row["codnom"];
					$ls_desnom=htmlentities($row['desnom']);
					
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codcar']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codcar']." ^javascript:aceptar(\"$ls_codcar\",\"$ls_descar\",\"$ls_codnom\",\"$ls_desnom\",\"$ls_coddestino\",\"$ls_dendestino\",\"$ls_codnomdestino\",\"$ls_desnomdestino\");^_self"));
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_descar));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_desnom));												
					$row_->appendChild($cell);
			
			}
			return $dom->saveXML();

		}
        
	} // end function uf_srh_buscar_cargo
	
	
//FUNCIONES PARA EL MANEJO DEL CATÁLOGO DE NOMINA



function uf_srh_buscar_nomina ($as_codnom, $as_desnom)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_nomina																											
		//      Argumento: $as_codnom   //  código del nomina     
	    //                 $as_desnom   //  denominación de la nomina 
		//	      Returns: Retorna un XML  																						
		//    Description: Funcion busca un nomina en la tabla sno_nomina y crea un XML para mostrar    
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 19/02/2008							Fecha Última Modificación: 19/02/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		
	   
		$ls_codnomdestino="txtcodnom";
		$ls_desnomdestino="txtdesnom";
		
		
		$lb_valido=true;
		
		
				
		$ls_sql= "SELECT *  FROM sno_nomina  ".
				"   WHERE codnom like '$as_codnom' ".
				"   AND desnom like '$as_desnom' ".
				" ORDER BY codnom";
		
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->cargo MÉTODO->uf_srh_buscar_nomina( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	

		
		    $dom1 = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom1->createElement('rows');
		     $dom1->appendChild($team);			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
		
					$ls_codnom=$row["codnom"];
					$ls_desnom=htmlentities($row['desnom']);
					
					
					$row_ = $team->appendChild($dom1->createElement('row'));
					$row_->setAttribute("id",$row['codnom']);
					$cell = $row_->appendChild($dom1->createElement('cell'));   
					
					$cell->appendChild($dom1->createTextNode($row['codnom']." ^javascript:aceptar( \"$ls_codnom\", \"$ls_desnom\", \"$ls_codnomdestino\" ,  \"$ls_desnomdestino\" );^_self"));
					
				


					$cell = $row_->appendChild($dom1->createElement('cell'));
					$cell->appendChild($dom1->createTextNode($ls_desnom));												
					$row_->appendChild($cell);
			
			}
			return $dom1->saveXML();
		
			
			
		
		}
      
		
	} // end function buscar_nomina
		
//FUNCIONES PARA MANEJAR LOS DETALLES DE CARGO



function uf_srh_load_requerimiento_cargo_campos($as_codcar,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_requerimiento_cargo_campos
		//	    Arguments: as_codcar  // código del cargo
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de un requerimiento de cargo
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * ". 
				"  FROM srh_dt_cargo, srh_requerimientos, srh_tiporequerimientos ".
				" WHERE srh_dt_cargo.codemp='".$this->ls_codemp."'".
				"   AND codcar='".$as_codcar."'".
				"   AND srh_dt_cargo.codreq = srh_requerimientos.codreq".
				"   AND srh_dt_cargo.codtipreq=srh_tiporequerimientos.codtipreq".
				" ORDER BY srh_dt_cargo.codtipreq ";
			
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->cargo MÉTODO->uf_srh_load_requerimiento_cargo_campos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
		 $num=$this->io_sql->num_rows($rs_data);
           
		  if ($num!=0) {
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_codreq=$row["codreq"];
				$ls_denreq= htmlentities ($row["denreq"]);
				$ls_dentipreq= htmlentities($row["dentipreq"]);
				$ls_codtipreq=$row["codtipreq"];
				
				$ao_object[$ai_totrows][1]="<input name=txtcodtipreq".$ai_totrows." type=text id=txtcodtipreq".$ai_totrows." class=sin-borde size=15 value=".$ls_codtipreq." readonly >";
				$ao_object[$ai_totrows][2]="<input name=txtdentipreq".$ai_totrows." type=text id=txtdentipreq".$ai_totrows." class=sin-borde size=40 value=".$ls_dentipreq." readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtcodreq".$ai_totrows." type=text id=txtcodreq".$ai_totrows." class=sin-borde size=15 value=".$ls_codreq." readonly >";
				$ao_object[$ai_totrows][4]="<input name=txtdenreq".$ai_totrows." type=text id=txtdenreq".$ai_totrows." class=sin-borde size=40 value=".$ls_denreq."  readonly>";
				$ao_object[$ai_totrows][5]="<a href=javascript:catalogo_requerimiento(".$ai_totrows.");    align=center><img src=../../../../shared/imagebank/tools15/buscar.gif alt=Buscar width=15 height=15 border=0 align=center></a>";		
				$ao_object[$ai_totrows][6]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$ao_object[$ai_totrows][7]="<a href=javascript:uf_delete_dt(".$ai_totrows.");    align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";
							
			}
			$this->io_sql->free_result($rs_data);
			}
		else 
		 {
		    $this->io_msg->message("No hay requerimientos asociados a ese cargo.");
	 		$ai_totrows=0;	
			
		
		  }
		  return $lb_valido;
		}
		
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	


//FUNCIONES PARA EL MANEJO DEL DETALLE DE LOS REQUERIMIENTOS DE CARGO


function uf_srh_guardar_requerimiento_cargo ($po_requerimiento,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_requerimiento_cargo																	
		//         access: public (sigesp_srh_d_requerimiento_cargo)
	  	//      Argumento: $po_requerimiento    // arreglo con los datos del requerimientos de cargo								
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica requerimientos en la tabla srh_dt_cargo            
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 20/02/2008							Fecha Última Modificación: 20/02/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  	        //Borramos los registros anteriores 
	$this-> uf_srh_eliminar_dt_cargo($po_requerimiento->codcar, $aa_seguridad);
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_det = 0;
	while (($li_det < count($po_requerimiento->requerimiento)) &&
	       ($lb_guardo))
	{
	  $lb_guardo = $this-> uf_srh_guardar_dt_cargo($po_requerimiento->requerimiento[$li_det], $aa_seguridad);
	  $li_det++;
	}
	
	return $lb_guardo;  
  }
	
	

	
function uf_srh_eliminar_requerimiento_cargo($as_codcar, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_requerimiento_cargo																		
		//        access:  public (sigesp_srh_d_requerimiento_cargo)														
		//      Argumento: $as_codcar        // codigo del cargo								
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina requerimientos de cargo en la tabla srh_dt_cargo      
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 20/02/2008							Fecha Última Modificación: 20/02/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
 	$lb_borro=$this-> uf_srh_eliminar_dt_cargo($as_codcar, $aa_seguridad);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->cargo MÉTODO->eliminar_requerimiento_cargo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	
	return $lb_borro;
  }
	



function uf_srh_guardar_dt_cargo($po_requerimiento, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_dt_cargo															     														
		//      Argumento: $po_requerimiento    // arreglo con los datos de los detalle de lor requerimientos de cargo				
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica un requerimiento de cargo en la tabla srh_dt_cargo           
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 20/02/2008							Fecha Última Modificación: 20/02/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 	 
	  $ls_sql = "INSERT INTO srh_dt_cargo (codcar,codnom,codtipreq,codreq, codemp) ".	  
	            " VALUES ('$po_requerimiento->codcar','$po_requerimiento->codnom','$po_requerimiento->codtipreq','$po_requerimiento->codreq','".$this->ls_codemp."')";
	

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el requerimiento de cargo ".$po_requerimiento->codcar;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->cargo MÉTODO->uf_srh_guardar_dt_cargo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
	
function uf_srh_eliminar_dt_cargo($as_codcar, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_dt_cargo																
		//        access:  public (sigesp_srh_dt_cargo)														
		//      Argumento: $as_codcar        // código del cargo
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																				    
		//    Description: Funcion que elimina un requerimiento de cargo en la tabla srh_dt_cargo                      
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 20/02/2008							Fecha Última Modificación: 20/02/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_dt_cargo ".
	          " WHERE codcar='$as_codcar'  AND codemp='".$this->ls_codemp."'";
			  

	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->cargo MÉTODO->uf_srh_eliminar_dt_cargo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó los requerimientos de cargo ".$as_codcar;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
			
	
	return $lb_borro;
	
  }
  
  
  
  function uf_srh_buscar_requerimiento_cargo($as_codcar,$as_descar,$as_codnom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_requerimiento_cargo
		//         reqess: private
		//      Argumento: $as_ccodcar    // codigo de cargo
		//                 $as_descar     // descccripcion de cargo
		//                 $as_codnom  // codigo del nómina
		//	      Returns: Retorna un Booleano
		//    Description: 
		//	   Creado Por: María Beatriz Unda
 		// Fecha Creación: 19/02/2008							Fecha Última Modificación: 19/02/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$ls_coddestino="txtcodcar";
		$ls_dendestino="txtdescar";
		$ls_codnomdestino="txtcodnom";
		$ls_desnomdestino="txtdesnom";
	
		
		$lb_valido=true;
		$ls_sql=" SELECT DISTINCT (srh_dt_cargo.codcar), sno_cargo.descar, sno_asignacioncargo.codasicar,  ".
		        " sno_asignacioncargo.denasicar, sno_nomina.codnom, sno_nomina.desnom FROM  sno_nomina, srh_dt_cargo ".
				" LEFT JOIN sno_cargo ON (srh_dt_cargo.codcar = sno_cargo.codcar AND srh_dt_cargo.codnom = sno_cargo.codnom ) ".
				" LEFT JOIN sno_asignacioncargo ON (srh_dt_cargo.codcar = sno_asignacioncargo.codasicar AND ".
				" srh_dt_cargo.codnom = sno_asignacioncargo.codnom) ".
				 "WHERE  sno_nomina.codnom= srh_dt_cargo.codnom ".
                 "AND  srh_dt_cargo.codcar like '".$as_codcar."' ".
                // "AND descar like '".$as_descar."' ".
				// "OR  denasicar like '".$as_descar."' ".	
                 "AND srh_dt_cargo.codnom like '".$as_codnom."' ".
                 "ORDER BY srh_dt_cargo.codcar ";
				
				

		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->cargo MÉTODO->uf_srh_buscar_requerimiento_cargo( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			 
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
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
					$ls_codnom=$row["codnom"];
					$ls_desnom=htmlentities($row['desnom']);
					
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codcar']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codcar']." ^javascript:aceptar(\"$ls_codcar\",\"$ls_descar\",\"$ls_codnom\",\"$ls_desnom\",\"$ls_coddestino\",\"$ls_dendestino\",\"$ls_codnomdestino\",\"$ls_desnomdestino\");^_self"));
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_descar));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_desnom));												
					$row_->appendChild($cell);
			
			}
			return $dom->saveXML();

		}
        
	} // end function uf_srh_buscar_cargo
  
//FUNCION PARA BUSCAR CARGOS CON RAC EN ASIGNACIÓN DE CARGOS

function uf_srh_buscar_cargo_rac($as_codcar,$as_descar)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_cargo
		//         reqess: private
		//      Argumento: $as_ccodcar    // codigo de cargo
		//                 $as_descar     // descccripcion de cargo
		//	      Returns: Retorna un Booleano
		//    Description: 
		//	   Creado Por: María Beatriz Unda
 		// Fecha Creación: 19/02/2008							Fecha Última Modificación: 19/02/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$ls_codcardestino="txtcodcar";
		$ls_descardestino="txtdescar";
		$ls_gradodestino="txtgrapro";
		$ls_pasodestino="txtpaspro";
		$ls_suelbasdestino="txtsuelpro";
		$ls_comprodestino="txtcompro";
		$ls_codnomdestino="txtcodnom";
		$ls_desnomdestino="txtdesnom";
		
		
		$lb_valido=true;
		$ls_sql=" SELECT sno_asignacioncargo.*, sno_grado.*, sno_nomina.desnom FROM sno_asignacioncargo, sno_grado, sno_nomina ".
				 " WHERE sno_asignacioncargo.codtab= sno_grado.codtab ".
				 " AND sno_asignacioncargo.codpas= sno_grado.codpas ".
				 " AND sno_asignacioncargo.codgra= sno_grado.codgra ".
			     " AND sno_asignacioncargo.codnom= sno_nomina.codnom ".
                 " AND  codasicar like '".$as_codcar."' ".
                 " AND denasicar like '".$as_descar."' ".
				 " AND codasicar  <> '0000000' ".
				 " AND  numvacasicar  <> '0' ".
                 " ORDER BY codasicar";
				 	
				 
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->cargo MÉTODO->uf_srh_buscar_cargo_rac( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			 
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
					$ls_codcar=$row["codasicar"];
					$ls_descar=htmlentities($row['denasicar']); 			
					$ls_grado=$row["codgra"];
					$ls_paso=$row["codpas"];
					
					$ls_suelbas=$row["monsalgra"];;
					$ls_compro=$row["moncomgra"];
					$ls_codnom=$row["codnom"];
					$ls_desnom= htmlentities ($row["desnom"]);					

					
					
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codasicar']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codasicar']." ^javascript:aceptar(\"$ls_codcar\",\"$ls_descar\",\"$ls_grado\",\"$ls_paso\",\"$ls_suelbas\",\"$ls_compro\",\"$ls_codcardestino\",\"$ls_descardestino\",\"$ls_gradodestino\",\"$ls_pasodestino\",\"$ls_suelbasdestino\",\"$ls_comprodestino\",\"$ls_codnom\",\"$ls_codnomdestino\",\"$ls_desnom\",\"$ls_desnomdestino\");^_self"));
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_descar));												
					$row_->appendChild($cell);
					
						$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_desnom));												
					$row_->appendChild($cell);
					
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($row['numvacasicar']));												
					$row_->appendChild($cell);
					
					
			
			}
			return $dom->saveXML();

		}
        
	} // end function uf_srh_buscar_cargo
	

}// end   class sigesp_srh_c_cargo
?>