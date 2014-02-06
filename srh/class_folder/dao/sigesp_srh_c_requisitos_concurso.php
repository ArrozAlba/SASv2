<?php

class sigesp_srh_c_requisitos_concurso
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_requisitos_concurso($path)
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
	
function uf_srh_select_requisitos_concurso($as_codcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_requisitos_concurso
		//      Argumento: $as_codcon    // codigo del concurso
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un concurso en la tabla de  srh_requisitos_concurso
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 18/09/2008							Fecha Última Modificación: 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_requisitos_concurso  ".
				  " WHERE codcon='".trim($as_codcon)."'".
				  " AND codemp='".$this->ls_codemp."'" ;
	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->requisitos_concurso MÉTODO->uf_srh_select_requisitos_concurso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_requisitos_concurso

	
	
function uf_srh_guardar_requisitos_concurso ($ao_requisitos,$as_operacion="insertar", $aa_seguridad)
 { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_requisitos_concurso																		
		//         access: public (sigesp_srh_requisitos_concurso)
	  	//      Argumento: $ao_requisitos    // arreglo con los datos de los requisitos de un concurso							
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica escala de evaluación en la tabla srh_requisitos_concurso             
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 17/09/2008							Fecha Última Modificación: 17/09/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	 $lb_guardo =true;
	 $lb_existe=false;
	 if ($as_operacion!="modificar")
	 {
	 	$lb_existe = $this->uf_srh_select_requisitos_concurso($ao_requisitos->codcon);
	 }
	 if ($lb_existe===false)
	 {
		$this->io_sql->begin_transaction();
		//Borramos los registros anteriores 
		$this->uf_srh_eliminar_requisitos_concurso($ao_requisitos->codcon, $aa_seguridad,'1');
		  
		//Ahora guardamos
		$lb_guardo = true;
		$li_det = 0;
		while (($li_det < count($ao_requisitos->detalle))&&($lb_guardo))
		{
		  $lb_guardo = $this->uf_srh_guardar_dt_requisitos_concurso($ao_requisitos->detalle[$li_det]);
		  $li_det++;
		}
		
		if ($lb_guardo ==true)
		{
			$this->io_sql->commit();
			
			if ($as_operacion=="modificar")
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="INSERT";
						$ls_descripcion ="Insertó los requisitos del concurso".$ao_requisitos->codcon;
						$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="UPDATE";
						$ls_descripcion ="Actualizo los requisitos del concurso".$ao_requisitos->codcon;
						$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}
		else
		{
			$this->io_sql->rollback();
		}
	}
	
	return array($lb_guardo,$lb_existe);    
  }


function uf_srh_buscar_requisitos_concurso($as_codcon,$as_descon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_requisitos_concurso
		//         Access: private
		//      Argumento: $as_codcon  // codigo del concurso
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca una requisitos_concurso  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 18/09/2008							Fecha Última Modificación: 18/09/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_coddestino="txtcodcon";
		$ls_dendestino="txtdescon";
		$ls_reqindcondestino="chkreqindcon";
						
		$lb_valido=true;
		$ls_sql="  SELECT DISTINCT (srh_requisitos_concurso.codcon), srh_concurso.descon, srh_requisitos_concurso.reqindcon ".
		        " FROM srh_requisitos_concurso, srh_concurso ".          
				" WHERE srh_requisitos_concurso.codcon like '".$as_codcon."' ".
				"   AND descon like '".$as_descon."' ".
				"   AND srh_requisitos_concurso.codcon = srh_concurso.codcon ".
			   " ORDER BY srh_requisitos_concurso.codcon";
		
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->requisitos_concurso MÉTODO->uf_srh_buscar_requisitos_concurso( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
					$ls_descon= htmlentities ($row["descon"]);
					$ls_reqindcon= trim ($row["reqindcon"]);
				
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codcon']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codcon']." ^javascript:aceptar(\"$ls_codcon\", \"$ls_descon\", \"$ls_coddestino\", \"$ls_dendestino\",\"$ls_reqindcon\",\"$ls_reqindcondestino\");^_self"));
				
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_descon));												
					$row_->appendChild($cell);
						
					
			
			}
			return $dom->saveXML();
		

		}
      
		
	} // end function uf_srh_buscar_requisitos_concurso
	
	
function uf_srh_load_requisitos_campos($as_codcon,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_requisitos_campos
		//	    Arguments: as_codcon  // código del oncurso
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de una escala
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
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
			$this->io_msg->message("CLASE->requisitos_concurso MÉTODO->uf_srh_load_requisitos_campos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
				
				$ao_object[$ai_totrows][1]="<input name=txtcodreqcon".$ai_totrows." type=text id=txtcodreqcon".$ai_totrows." class=sin-borde size=5 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);' value='".$li_codreqcon."' onBlur='javascript:ue_generar_codigo(".$ai_totrows.");' style='text-align:center'>";
				$ao_object[$ai_totrows][2]="<input name=txtdesreqcon".$ai_totrows." type=text id=txtdesreqcon".$ai_totrows." class=sin-borde size=85 maxlength=254 onKeyUp='ue_validarcomillas(this);' value='".$ls_desreqcon."'  >";
				$ao_object[$ai_totrows][3]="<input name=txtcanreqcon".$ai_totrows." type=text id=txtcanreqcon".$ai_totrows." class=sin-borde size=5 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);' value='".$li_canreqcon."' >";				
				$ao_object[$ai_totrows][4]="<a href=javascript:uf_agregar_dt(".$ai_totrows."); align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$ao_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt(".$ai_totrows."); align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";
						
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function  uf_srh_load_requisitos_campos	//---------------------------------------------------------------------------------------------------------------------------------

function uf_srh_guardar_dt_requisitos_concurso($ao_requisitos)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_dt_requisitos_concurso															     	
		//         access: public (sigesp_dt_srh_requisitos_concurso)														
		//      Argumento: $ao_requisitos    // arreglo con los datos de los requisitos del concurso				
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta un requisito de un concurso en la tabla  srh_requisitos_concurso      
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 17/09/2008							Fecha Última Modificación: 17/09/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  		
		if (($ao_requisitos->canreqcon==""))
		{
			$ao_requisitos->canreqcon=1;
		}
		
	  $ls_sql = "INSERT INTO srh_requisitos_concurso (codcon,codreqcon,desreqcon,canreqcon,reqindcon, codemp) ".	  
	            " VALUES ('$ao_requisitos->codcon','$ao_requisitos->codreqcon','$ao_requisitos->desreqcon',$ao_requisitos->canreqcon,'$ao_requisitos->reqindcon','".$this->ls_codemp."')";
		
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->requisitos_concurso MÉTODO->guardar_dt_requisitos_concurso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
	
function uf_srh_eliminar_requisitos_concurso($as_codcon, $aa_seguridad, $as_tipo)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_requisitos_concurso																
		//        access:  public (sigesp_srh_requisitos_concurso)														
		//      Argumento: $as_codcon        // código del concurso
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																				    
		//    Description: Funcion que elimina los registros del concurso                      
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 17/09/2008							Fecha Última Modificación: 17/09/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_requisitos_concurso ".
	          " WHERE codcon='$as_codcon'  AND codemp='".$this->ls_codemp."'";
			  

	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->requisitos_concurso MÉTODO->eliminar_dt_requisitos_concurso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				if ($as_tipo=='2')
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="DELETE";
					$ls_descripcion ="Eliminó los requisitos del concurso".$as_codcon;
					$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				}
				$this->io_sql->commit();
			}
			
	
	return $lb_borro;
	
  }
  
  
  
}
?>
