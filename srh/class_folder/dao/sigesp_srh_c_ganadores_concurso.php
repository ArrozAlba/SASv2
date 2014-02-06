<?php

class sigesp_srh_c_ganadores_concurso
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_ganadores_concurso($path)
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
	
	function uf_srh_select_ganadoresconcurso($as_codcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_ganadoresconcurso
		//      Argumento: $as_codcon    // codigo del concuros
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un concurso en la tabla de  srh_ganadores_concurso
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_ganadores_concurso  ".
				  " WHERE codcon='".trim($as_codcon)."'".
				  " AND codemp='".$this->ls_codemp."'" ;
	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->area MÉTODO->uf_srh_select_area ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_ganadoresconcurso

	
	
	
  
function uf_srh_guardarganadores_concurso ($po_ganadores,$ps_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarodi																		
		//         access: public (sigesp_srh_ganadores_concurso)
		//      Argumento: $po_ganadores    // arreglo con los datos de los ganadores de concurso     
		//		            $ps_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica un ODI en la tabla srh_ganadores_concurso             
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 20/02/2008							Fecha Última Modificación: 20/02/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$as_codcon=$po_ganadores->codcon;
	$lb_existe = false;
	$lb_guardo=true;
  	if ($ps_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	 $po_ganadores->fecha=$this->io_funcion->uf_convertirdatetobd($po_ganadores->fecha);
   
	 
	  $ls_sql = "UPDATE srh_ganadores_concurso SET ".
		  		"fecha = '$po_ganadores->fecha'  ".				
				"WHERE codcon = '$po_ganadores->codcon' AND codemp='".$this->ls_codemp."'" ;
	
	  if($lb_guardo===false)
		 {
			$this->io_msg->message("CLASE->ganadores_concurso MÉTODO->uf_srh_guardarganadores_concurso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		 }
		else
		{
			$lb_valido=true;
			$this->io_sql->commit();
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó los ganadores de concurso ".$as_codcon;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		
		 if($lb_guardo) 
		 {
			//Guardamos el detalle de los ganadores de concurso
			$lb_guardo = $this->guardarDetalles_ganadores_concurso($po_ganadores, $aa_seguridad);
		 }

			
			    
	}
	else
	{ 
	  $po_ganadores->fecha=$this->io_funcion->uf_convertirdatetobd($po_ganadores->fecha);
	  $lb_existe = $this->uf_srh_select_ganadoresconcurso($po_ganadores->codcon);
	 
	  if ($lb_existe===false)
	  {
		$this->io_sql->begin_transaction();
		
		  $ls_sql = "INSERT INTO srh_ganadores_concurso (codcon, fecha, codemp) ".	  
					"VALUES ('$po_ganadores->codcon','$po_ganadores->fecha',  '".$this->ls_codemp."')";
		
		 $lb_guardo = $this->io_sql->execute($ls_sql);
		 if($lb_guardo===false)
		 {
			$this->io_msg->message("CLASE->ganadores_concurso MÉTODO->uf_srh_guardarganadores_concurso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		 }
		else
		{
			$lb_valido=true;
			$this->io_sql->commit();
		}
		
		 if($lb_guardo) 
		 {
			//Guardamos el detalle de los ganadores de concurso
			$lb_guardo = $this->guardarDetalles_ganadores_concurso($po_ganadores, $aa_seguridad);
		 }
		 
		 
		}
    }
	

    
		return array($lb_guardo,$lb_existe);
  }
	
	
	
function guardarDetalles_ganadores_concurso ($po_ganadores, $aa_seguridad)
  {
    //Borramos los registros anteriores 
	$this-> uf_srh_eliminar_dt_ganadores_concurso($po_ganadores->codcon, $aa_seguridad);
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_obj = 0;
	while (($li_obj < count($po_ganadores->ganadores)) &&
	       ($lb_guardo))
	{
	  $lb_guardo = $this-> uf_srh_guardar_dt_ganadores_concurso($po_ganadores->ganadores[$li_obj], $aa_seguridad);
	  $li_obj++;
	}
	
	return $lb_guardo;    
  }


	

	
function uf_srh_eliminarganadores_concurso($as_codcon, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarodi																		
		//        access:  public (sigesp_srh_ganadores_concurso)														
		//      Argumento: $as_codcon        // codigo del concurso 
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina una revisión de ODI en la tabla srh_ganadores_concurso                         
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 20/02/2008							Fecha Última Modificación: 20/02/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
	$this-> uf_srh_eliminar_dt_ganadores_concurso($as_codcon, $aa_seguridad);
	

    $ls_sql = "DELETE FROM srh_ganadores_concurso ".
	          "WHERE codcon = '$as_codcon' AND codemp='".$this->ls_codemp."'";


	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->ganadores_concurso MÉTODO->uf_srh_eliminarganadores_concurso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó los ganadores de concurso ".$as_codcon;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				
					$this->io_sql->commit();
			}
	
	return $lb_borro;
  }

	
	
	
function uf_srh_buscar_ganadores_concurso($as_codcon,$as_fecha1,$as_fecha2)
	{
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_ganadores_concurso																		//
		//         access: public (sigesp_srh_ganadores_concurso)												
		//      Argumento: $as_codcon   //  codigo del concurso                                                            
		//                 $as_fecha   //   fecha de los ganadores de concurso//	    																						
		//    Description: Funcion busca ganadores de concurso en la tabla srh_ganadores_concurso y crea un XML para mostrar    
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 20/02/2008							Fecha Última Modificación: 20/02/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		
		
		$ls_fechadestino="txtfecha";
		$ls_codcondestino="txtcodcon";
		$ls_descondestino="txtdescon";
		$lb_valido=true;
		
         $as_fecha1=$this->io_funcion->uf_convertirdatetobd($as_fecha1);
		 $as_fecha2=$this->io_funcion->uf_convertirdatetobd($as_fecha2);
		 
		 
				
		$ls_sql= "SELECT  * FROM srh_ganadores_concurso INNER JOIN srh_concurso ON (srh_ganadores_concurso.codcon = srh_concurso.codcon)   ".
				" WHERE srh_ganadores_concurso.fecha BETWEEN '".$as_fecha1."' AND '".$as_fecha2."' ".
				"   AND srh_ganadores_concurso.codcon like '$as_codcon' ".
				" ORDER BY srh_ganadores_concurso.codcon";

	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->ganadores_concurso MÉTODO->uf_srh_buscar_ganadores_concurso( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
			        $ls_codcon=$row["codcon"];
					$ls_descon= trim (htmlentities  ($row["descon"]));
					$ls_fecha=$this->io_funcion->uf_formatovalidofecha($row["fecha"]);
					$ls_fecha=$this->io_funcion->uf_convertirfecmostrar($ls_fecha);
					
					
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codcon']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($row['codcon']." ^javascript:aceptar(\"$ls_codcon\", \"$ls_fecha\", \"$ls_descon\",  \"$ls_codcondestino\",\"$ls_descondestino\", \"$ls_fechadestino\");^_self"));
					
				
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_descon));												
					$row_->appendChild($cell);
							
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_fecha));												
					$row_->appendChild($cell);
			
			
			}
			return $dom->saveXML();
		
		}
      
		
	} // end function buscar_ganadores_concurso
	

//FUNCIONES PARA EL MANEJO DEL DETALLE DE LOS GANADORES DE CONCURSO

function uf_srh_guardar_dt_ganadores_concurso($po_ganadores, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_dt_ganadores_concurso															     	
		//         access: public (sigesp_dt_srh_ganadores_concurso)														
		//      Argumento: $po_ganadores    // arreglo con los datos de los detalle de los ganadores de concurso//     
		//	               $ps_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica un ODI en la tabla srh_dt_ganadores_concurso           
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 20/02/2008							Fecha Última Modificación: 20/02/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 

	 
	  $ls_sql = "INSERT INTO srh_dt_ganadores_concurso (codcon,  codper,total, posicion, codemp) ".	  
	            " VALUES ('$po_ganadores->codcon','$po_ganadores->codper','$po_ganadores->total','$po_ganadores->posicion','".$this->ls_codemp."')";
			

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el detalle de los ganadores de concurso ".$po_ganadores->codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->ganadores_concurso MÉTODO->uf_srh_guardar_dt_ganadores_concurso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
	
function uf_srh_eliminar_dt_ganadores_concurso($as_codcon, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_dt_ganadores_concurso																
		//        access:  public (sigesp_srh_dt_ganadores_concurso)														
		//      Argumento: $ps_codper        //
		//	                $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																				    
		//    Description: Funcion que elimina un ODI en la tabla srh_dt_ganadores_concurso                      
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 20/02/2008							Fecha Última Modificación: 20/02/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_dt_ganadores_concurso ".
	          " WHERE codcon='$as_codcon' AND codemp='".$this->ls_codemp."'";


	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->ganadores_concurso MÉTODO->uf_srh_eliminar_dt_ganadores_concurso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el detalle de los ganadores de concurso ".$as_codcon;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
			
	
	return $lb_borro;
	
  }
  
  
  

function uf_srh_load_ganadores_concurso_campos($as_codcon,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_ganadores_concurso_campos
		//	    Arguments: as_codcon   // número de revisión
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de una revisión de ODI
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$ls_sql="SELECT srh_dt_ganadores_concurso.*, srh_concursante.nomper,srh_concursante.apeper, srh_concursante.tipper ".
		        "  FROM srh_dt_ganadores_concurso, srh_concursante ".
				"  WHERE srh_dt_ganadores_concurso.codemp='".$this->ls_codemp."'".
				"  AND srh_dt_ganadores_concurso.codcon = '$as_codcon' ".
				"  AND trim(srh_dt_ganadores_concurso.codper) = trim(srh_concursante.codper) ".
				" ORDER BY srh_dt_ganadores_concurso.total DESC ";
        
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->ganadores_concurso MÉTODO->uf_srh_load_ganadores_concurso_campos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_codper=$row["codper"];
				$ls_nomper= trim(htmlentities($row['nomper']));
				$ls_apeper= trim(htmlentities($row['apeper']));
				$ls_nombrecompeto= $ls_nomper." ".$ls_apeper;
				 
				 if ($row["tipper"]=='E') {
				   $ls_tipo='Externo';
				 }
				 elseif ($row["tipper"]=='I') {
				   $ls_tipo='Interno';
				 }
				
				
				$ls_total=$row["total"];
				$ls_posi=trim($row["posicion"]);
				
				$ao_object[$ai_totrows][1]="<input name=txtcodper".$ai_totrows." size=10 id=txtcodper".$ai_totrows." class=sin-borde value=".$ls_codper." readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtnomper".$ai_totrows."  size=45 id=txtnomper".$ai_totrows."  value='".$ls_nombrecompeto."' maxlength=250 class=sin-borde readonly >";
				$ao_object[$ai_totrows][3]="<input name=txttipoper".$ai_totrows." size=10 id=txttipoper".$ai_totrows." value=".$ls_tipo." class=sin-borde readonly>";
				$ao_object[$ai_totrows][4]="<input name=txttotal".$ai_totrows." size=5 id=txttotal".$ai_totrows." class=sin-borde value=".$ls_total." >";
				$ao_object[$ai_totrows][5]="<input name=txtposi".$ai_totrows." size=16 maxlength=250 id=txtposi".$ai_totrows." onKeyUp='ue_validarcomillas(this);' class=sin-borde value='".$ls_posi."' >";
			
				
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
function uf_srh_consultar_ganadores_concurso ($as_codcon,&$ai_totrows,&$ao_object)
{
		
		$lb_valido=true;
		
		
		$ls_sql= " SELECT * FROM srh_concursante  ".
		         " INNER JOIN srh_resultados_evaluacion_aspirante ON ".
				 " (srh_concursante.codcon = srh_resultados_evaluacion_aspirante.codcon ".
				 " AND srh_concursante.codper = srh_resultados_evaluacion_aspirante.codper) ".
				 " WHERE srh_concursante.codcon = '$as_codcon' ".
				 " ORDER BY srh_resultados_evaluacion_aspirante.toteva DESC ";
			
	 	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->ganadores_concurso MÉTODO->uf_srh_consultar_ganadores_concurso( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
					
					
					if ($row["tipper"]=='E') {
				  		 $ls_tipo='Externo';
				 	}
				 	elseif ($row["tipper"]=='I') {
				   		$ls_tipo='Interno';
				 	}
					
					$ls_apeper = htmlentities ($row["apeper"]);
					
					  $ls_nomper = htmlentities ($row["nomper"]);
					
					
					$ls_nomper_completo= (trim ($ls_nomper)." ".trim ($ls_apeper));
					
					$ls_total=$row["toteva"];
					
					$ao_object[$ai_totrows][1]="<input name=txtcodper".$ai_totrows." size=10 id=txtcodper".$ai_totrows." class=sin-borde value=".$ls_codper." readonly>";
				    $ao_object[$ai_totrows][2]="<input name=txtnomper".$ai_totrows."  size=45 id=txtnomper".$ai_totrows."  value='".$ls_nomper_completo."' class=sin-borde readonly >";
					$ao_object[$ai_totrows][3]="<input name=txttipoper".$ai_totrows." size=10 id=txttipoper".$ai_totrows." value=".$ls_tipo." class=sin-borde readonly>";
				    $ao_object[$ai_totrows][4]="<input name=txttotal".$ai_totrows." size=5 id=txttotal".$ai_totrows." value=".$ls_total." class=sin-borde readonly>";
				    $ao_object[$ai_totrows][5]="<input name=txtposi".$ai_totrows." size=16 id=txtposi".$ai_totrows." onKeyUp='ue_validarcomillas(this);' class=sin-borde>";
			
					
				}
		
		}
		else 
		  {
		     $this->io_msg->message("No se encontraron Registros con esos datos.");
	 		 $ai_totrows=1;	
			 $ao_object[$ai_totrows][1]="<input name=txtcodper".$ai_totrows." size=10 id=txtcodper".$ai_totrows." class=sin-borde  readonly>";
			 $ao_object[$ai_totrows][2]="<input name=txtnomper".$ai_totrows."  size=45 id=txtnomper".$ai_totrows." class=sin-borde readonly >";
			 $ao_object[$ai_totrows][3]="<input name=txttipoper".$ai_totrows." size=10 id=txttipoper".$ai_totrows."  class=sin-borde readonly>";
			 $ao_object[$ai_totrows][4]="<input name=txttotal".$ai_totrows." size=5 id=txttotal".$ai_totrows." class=sin-borde readonly>";
			 $ao_object[$ai_totrows][5]="<input name=txtposi".$ai_totrows." size=16 onKeyUp='ue_validarcomillas(this);' id=txtposi".$ai_totrows." class=sin-borde >";
				
		  }  
		return $lb_valido;
	
		}
      
		
	} // end function buscar_resultados_evaluacion_aspirante
		
	


}// end   class sigesp_srh_c_ganadores_concurso
?>