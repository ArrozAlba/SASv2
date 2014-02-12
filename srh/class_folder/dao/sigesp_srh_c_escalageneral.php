<?php

class sigesp_srh_c_escalageneral
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_escalageneral($path)
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
	
		
function uf_srh_getProximoCodigo()
  {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getProximoCodigo
		//         Access: public (sigesp_srh_d_escalageneral)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de una escala de evaluación
		//    Description: Funcion que genera un código de una escala de evaluación
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(codesc) AS codigo FROM srh_escalageneral ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_codesc = $la_datos["codigo"][0]+1;
    $ls_codesc = str_pad ($ls_codesc,15,"0","left");
	return $ls_codesc;
  }

	
	function uf_srh_select_escalageneral($as_codesc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_escalageneral
		//         Access: public (sigesp_srh_d_escalageneral)
		//      Argumento: $as_codesc    // código de la escala
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de una escala de evaluación en la tabla de  srh_escalageneral
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 07/09/2007							Fecha Última Modificación: 07/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_escalageneral  ".
				  " WHERE codesc='".trim($as_codesc)."'".
				  " AND codemp='".$this->ls_codemp."'" ;
	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->escalageneral MÉTODO->uf_srh_select_escalageneral ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_escalageneral

function uf_srh_guardar_escalageneral ($ao_escala,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_escalageneral																		
		//         access: public (sigesp_srh_escalageneral)
	  	//      Argumento: $ao_escala    // arreglo con los datos de la evaluación de escala de evaluación								
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica escala de evaluación en la tabla srh_escalageneral             
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 07/12/2007							Fecha Última Modificación: 07/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	  $ls_sql = "UPDATE srh_escalageneral SET ".
		  		"denesc = '$ao_escala->denesc' , ".
	            "valini = '$ao_escala->valini' , ".
			    "valfin = '$ao_escala->valfin' ".
	            "WHERE codesc= '$ao_escala->codesc'  AND codemp='".$this->ls_codemp."'" ;
		
	  
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó la escala de evaluación ".$ao_escala->codesc;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	
	  $ls_sql = "INSERT INTO srh_escalageneral (codesc, denesc, valini, valfin, codemp) ".	  
	            "VALUES ('$ao_escala->codesc','$ao_escala->denesc','$ao_escala->valini', '$ao_escala->valfin','".$this->ls_codemp."')";
	
	
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la escala de evaluación ".$ao_escala->codesc;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->uf_srh_escalageneral MÉTODO->guardar_escalageneral ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				$lb_guardo=false;
				if ($lb_valido)
				{
				   //Guardamos el detalle de la escala de evaluación
				    $lb_guardo = $this->guardarDetalles_Escala($ao_escala, $aa_seguridad);
					
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
		
		
	
	return $lb_guardo;
  }
	
	
	
function guardarDetalles_Escala ($ao_escala, $aa_seguridad)
  {
    //Borramos los registros anteriores 
	$this-> uf_srh_eliminar_dt_escalageneral($ao_escala->codesc, $aa_seguridad);
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_det = 0;
	while (($li_det < count($ao_escala->detalle)) &&
	       ($lb_guardo))
	{
	  $lb_guardo = $this-> uf_srh_guardar_dt_escalageneral($ao_escala->detalle[$li_det], $aa_seguridad);
	  $li_det++;
	}
	
	return $lb_guardo;    
  }


function uf_select_escala_tipoevaluacion ($as_codesc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_escala_tipoevaluacion
		//		   Access: private
 		//	    Arguments: $as_codesc // código de la escala 
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la escala de evaluación esta asociada a un tipo de evaluacion
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 25/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codesc ".
				 "  FROM srh_tipoevaluacion".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND codesc='".$as_codesc."' ";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->escalageneral MÉTODO->uf_select_escala_tipoevaluacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_unidadvipladin_cargos_historicos

	
	
function uf_srh_eliminar_escalageneral($as_codesc, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_escalageneral																		
		//        access:  public (sigesp_srh_escalageneral)														
		//      Argumento: $as_codesc        // codigo del personal 
		//                 $as_fecha        //  fecha de la evaluación de requesitos mínimos										
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina una evaluación de escala de evaluación en la tabla srh_escalageneral      
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 07/12/2007							Fecha Última Modificación: 07/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $lb_valido=false;
	$lb_existe=true;
	if (($this->uf_select_escala_tipoevaluacion($as_codesc)===false))
    {
		$lb_existe=false;
		$this->io_sql->begin_transaction();	
		 $this-> uf_srh_eliminar_dt_escalageneral($as_codesc, $aa_seguridad);
		 $ls_sql = "DELETE FROM srh_escalageneral ".
				  "WHERE codesc = '$as_codesc' AND codemp='".$this->ls_codemp."'";
	
		  $lb_borro=$this->io_sql->execute($ls_sql);
		  if($lb_borro===false)
		 {
			$this->io_msg->message("CLASE->uf_srh_escalageneral MÉTODO->eliminar_escalageneral ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		 }
		else
		 {         
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="DELETE";
					$ls_descripcion ="Eliminó la escala de evaluación".$as_codesc;
					$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////			
					
						$this->io_sql->commit();
				}
				
	}
		
	return array($lb_valido,$lb_existe);
  }
	

function uf_srh_buscar_escalageneral($as_codesc,$as_denesc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_escalageneral
		//         Access: private
		//      Argumento: $as_codesc  // codigo de la escalageneral
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca una escalageneral  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 04/09/2007							Fecha Última Modificación: 04/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_coddestino="txtcodesc";
		$ls_dendestino="txtdenesc";
		$ls_valinidestino="txtvalini";
		$ls_valfindestino="txtvalfin";
	
	
		
		$lb_valido=true;
		$ls_sql="SELECT * FROM srh_escalageneral".          
				" WHERE codesc like '".$as_codesc."' ".
				"   AND denesc like '".$as_denesc."' ".
			   " ORDER BY codesc";
	
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->escalageneral MÉTODO->uf_srh_buscar_escalageneral( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);		
			
			 	
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
				
					$ls_codesc=$row["codesc"];
					$ls_denesc= htmlentities ($row["denesc"]);
					$ls_valini=trim ($row["valini"]);
					$ls_valfin=trim ($row["valfin"]);
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codesc']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codesc']." ^javascript:aceptar(\"$ls_codesc\",\"$ls_denesc\",\"$ls_coddestino\",\"$ls_dendestino\",\"$ls_valini\",\"$ls_valfin\", \"$ls_valinidestino\",\"$ls_valfindestino\");^_self"));
				
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_denesc));												
					$row_->appendChild($cell);
						
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($row['valini'].' - '.$row["valfin"]));								
					$row_->appendChild($cell);
			
			}
			return $dom->saveXML();
		

		}
      
		
	} // end function uf_srh_buscar_escalageneral
	
	
function uf_srh_load_escala_campos($as_codesc,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_escala_campos
		//		   Access: public (sigesp_srh_d_escala)
		//	    Arguments: as_codesc  // código de la escala
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de una escala
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codesc, coddetesc, dendetesc, valinidetesc, valfindetesc ".
				"  FROM srh_dt_escalageneral ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codesc='".$as_codesc."'".
				" ORDER BY codesc,coddetesc ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->escala MÉTODO->uf_srh_load_escala_campos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$li_coddetesc=$row["coddetesc"];
				$ls_dendetesc=trim (htmlentities($row["dendetesc"]));
				$li_valinidetesc=trim ($row["valinidetesc"]);
				$li_valfindetesc=trim ($row["valfindetesc"]);
				
				$ao_object[$ai_totrows][1]="<input name=txtcoddetesc".$ai_totrows." type=text id=txtcoddetesc".$ai_totrows." class=sin-borde size=5 maxlength=2 onKeyUp='javascript: ue_validarnumero(this);' value='".$li_coddetesc."'>";
				$ao_object[$ai_totrows][2]="<input name=txtdendetesc".$ai_totrows."  type=text id=txtdendetesc".$ai_totrows." class=sin-borde size=40 onKeyUp='ue_validarcomillas(this);'  value='".$ls_dendetesc."'>";
				$ao_object[$ai_totrows][3]="<input name=txtvalinidetesc".$ai_totrows." type=text id=txtvalinidetesc".$ai_totrows." class=sin-borde size=7 maxlength=5 onKeyPress='return validarreal2(event,this);' value='".$li_valinidetesc."' onChange='javascript:valida_escalaini(this,txtvalini);'>";
				$ao_object[$ai_totrows][4]="<input name=txtvalfindetesc".$ai_totrows." type=text id=txtvalfindetesc".$ai_totrows." class=sin-borde size=7 maxlength=5 onKeyPress='return validarreal2(event,this);' value='".$li_valfindetesc."'  onChange='javascript:valida_escalafin(this,txtvalfin);'>";
				$ao_object[$ai_totrows][5]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$ao_object[$ai_totrows][6]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";			
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_escala
	//-----------------------------------------------------------------------------------------------------------------------------------	

//FUNCIONES PARA EL MANEJO DEL DETALLE DE LA ESCALA DE EVALUACIÓN

function uf_srh_guardar_dt_escalageneral($ao_escala, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_dt_escalageneral															     	
		//         access: public (sigesp_dt_srh_escalageneral)														
		//      Argumento: $ao_escala    // arreglo con los datos de los detalle de la escala de evaluación					
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica escala de evaluación en la tabla srh_dt_escalageneral           
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 07/12/2007							Fecha Última Modificación: 07/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 	 
	  $ls_sql = "INSERT INTO srh_dt_escalageneral (codesc,coddetesc,dendetesc,valinidetesc,valfindetesc, codemp) ".	  
	            " VALUES ('$ao_escala->codesc','$ao_escala->coddetesc','$ao_escala->dendetesc','$ao_escala->valini','$ao_escala->valfin','".$this->ls_codemp."')";
		

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el detalle de la escala de evaluación ".$ao_escala->codesc;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->uf_srh_dt_escalageneral MÉTODO->guardar_dt_escalageneral ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
	
function uf_srh_eliminar_dt_escalageneral($as_codesc, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_dt_escalageneral																
		//        access:  public (sigesp_srh_dt_escalageneral)														
		//      Argumento: $as_codesc        // código de la escala de evaluación
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																				    
		//    Description: Funcion que elimina escala de evaluación en la tabla srh_dt_escalageneral                      
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 07/12/2007							Fecha Última Modificación: 07/12/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_dt_escalageneral ".
	          " WHERE codesc='$as_codesc'  AND codemp='".$this->ls_codemp."'";
			  

	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->uf_srh_dt_escalageneral MÉTODO->eliminar_dt_escalageneral ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la escala de evaluación ".$as_codesc;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
			
	
	return $lb_borro;
	
  }
  
  
  
}
?>
