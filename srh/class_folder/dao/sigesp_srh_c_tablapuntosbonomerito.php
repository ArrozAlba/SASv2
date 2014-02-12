<?php

class sigesp_srh_c_tablapuntosbonomerito
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_tablapuntosbonomerito($path)
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
		//         Access: public (sigesp_srh_d_tablapuntosbonomerito)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de la puntuación por unidad tributaria
		//    Description: Funcion que genera un código de la puntuación por unidad tributaria
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:01/08/2008							Fecha Última Modificación:01/08/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(codpun) AS codigo FROM srh_puntosunitri  ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_codesc = $la_datos["codigo"][0]+1;
    $ls_codesc = str_pad ($ls_codesc,4,"0","left");
	return $ls_codesc;
  }

	
	
function uf_srh_guardar_tablapuntosbonomerito ($ao_puntos,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_tablapuntosbonomerito																		
		//         access: public (sigesp_srh_puntosunitri )
	  	//      Argumento: $ao_puntos    // arreglo con los datos de la puntuación por unidad tributaria							
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica la puntuación por unidad tributaria en la tabla srh_puntosunitri
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 01/08/2008							Fecha Última Modificación: 01/08/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  		$ao_puntos->valunitri=str_replace(".","",$ao_puntos->valunitri);
		$ao_puntos->valunitri=str_replace(",",".",$ao_puntos->valunitri);
	
	
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	  $ls_sql = "UPDATE srh_puntosunitri  SET ".
		  		"denpun = '$ao_puntos->denpun' , ".
	            "valunitri = '$ao_puntos->valunitri' , ".
				"codtipper = '$ao_puntos->codtipper'  ".
				"WHERE codpun= '$ao_puntos->codpun'  AND codemp='".$this->ls_codemp."'" ;
		
	  
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó la puntuación por unidad tributaria ".$ao_puntos->codpun;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	
	  $ls_sql = "INSERT INTO srh_puntosunitri  (codpun, denpun, codtipper, valunitri, codemp) ".	  
	            "VALUES ('$ao_puntos->codpun','$ao_puntos->denpun','$ao_puntos->codtipper', '$ao_puntos->valunitri','".$this->ls_codemp."')";
	
	
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la puntuación por unidad tributaria ".$ao_puntos->codpun;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->uf_srh_puntosunitri  MÉTODO->guardar_tablapuntosbonomerito ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
				    $lb_guardo = $this->guardarDetalles_Puntos($ao_puntos, $aa_seguridad);
					
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
	
	
	
function guardarDetalles_Puntos ($ao_puntos, $aa_seguridad)
  {
    //Borramos los registros anteriores 
	$this-> uf_srh_eliminar_dt_tablapuntosbonomerito($ao_puntos->codpun, $aa_seguridad);
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_det = 0;
	while (($li_det < count($ao_puntos->detalle)) &&
	       ($lb_guardo))
	{
	  $lb_guardo = $this-> uf_srh_guardar_dt_tablapuntosbonomerito($ao_puntos->detalle[$li_det], $aa_seguridad);
	  $li_det++;
	}
	
	return $lb_guardo;    
  }




function uf_select_tablapuntos_bono ($as_codpun)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_select_tablapuntos_bono ($as_codpun)
		//		   Access: private
 		//	    Arguments: as_codtipper // código del tipo de personal
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el tipo de personal esta asociado a un bno por merito
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 04/08/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codpun ".
				 "  FROM srh_bono_merito".
				 "  WHERE codemp  ='".$this->ls_codemp."' ".
				 "    AND codpun = '".$as_codpun."' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->tipopersonal  MÉTODO-> uf_select_tablapuntos_bono ($as_codpun) ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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



	
function uf_srh_eliminar_tablapuntosbonomerito($as_codpun, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_tablapuntosbonomerito																		
		//        access:  public (sigesp_srh_puntosunitri )														
		//      Argumento: $as_codpun        // codigo del personal 
		//                 $as_fecha        //  fecha de la evaluación de requesitos mínimos										
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina una evaluación de escala de evaluación en la tabla srh_puntosunitri       
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 01/08/2008							Fecha Última Modificación: 01/08/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    	 $lb_valido=true;		 
	     $lb_existe=true;
		
		if (($this->uf_select_tablapuntos_bono ($as_codpun)===false))
		 {
	
			$this->io_sql->begin_transaction();	
			$this-> uf_srh_eliminar_dt_tablapuntosbonomerito($as_codpun, $aa_seguridad);
			
			$ls_sql = "DELETE FROM srh_puntosunitri  ".
					  "WHERE codpun = '$as_codpun' AND codemp='".$this->ls_codemp."'";
		
			  $lb_borro=$this->io_sql->execute($ls_sql);
			  if($lb_borro===false)
			 {
				$this->io_msg->message("CLASE->uf_srh_puntosunitri  MÉTODO->eliminar_tablapuntosbonomerito ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			 }
			else
			 {         
						$lb_valido=true;
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Eliminó la puntuación por unidad tributaria ".$as_codpun;
						$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////			
						
							$this->io_sql->commit();
					}
					
    }
	else
		{
		  $lb_existe=true;
		  $lb_valido=false;
		}
		
	return array($lb_valido,$lb_existe);
  }
	

function uf_srh_buscar_tablapuntosbonomerito($as_codpun,$as_denpun)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_tablapuntosbonomerito
		//         Access: private
		//      Argumento: $as_codpun  // codigo de la tablapuntosbonomerito
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca una tabla puntos por unidad tributaria  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/08/2008							Fecha Última Modificación: 01/08/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_coddestino="txtcodesc";
		$ls_dendestino="txtdenesc";
		$ls_codtipperdestino="txtcodtipper";
		$ls_dentipperdestino="txtdentipper";
		$ls_valunitridestino="txtvalunitri";
		
		$lb_valido=true;
		$ls_sql="SELECT srh_puntosunitri.*, srh_tipopersonal.dentipper FROM srh_puntosunitri, srh_tipopersonal ".          
				" WHERE srh_puntosunitri.codtipper = srh_tipopersonal.codtipper  ".
				" AND codpun like '".$as_codpun."' ".
				" AND denpun like '".$as_denpun."' ".
			   " ORDER BY codpun ";
	
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tablapuntosbonomerito MÉTODO->uf_srh_buscar_tablapuntosbonomerito( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);		
			
			 	
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			    	$ls_codpun=$row["codpun"];
					$ls_denpun= htmlentities ($row["denpun"]);
					$ls_codtipper=trim ($row["codtipper"]);
					$ls_dentipper=trim (htmlentities($row["dentipper"]));
					$ls_valunitri= $row["valunitri"];
				
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codpun']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codpun']." ^javascript:aceptar(\"$ls_codpun\",\"$ls_denpun\",\"$ls_coddestino\",\"$ls_dendestino\",\"$ls_codtipper\",\"$ls_dentipper\",\"$ls_valunitri\", \"$ls_codtipperdestino\",\"$ls_dentipperdestino\",\"$ls_valunitridestino\");^_self"));
				
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_denpun));												
					$row_->appendChild($cell);
						
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_dentipper));								
					$row_->appendChild($cell);
			
			}
			return $dom->saveXML();
		

		}
      
		
	} // end function uf_srh_buscar_tablapuntosbonomerito
	
	
function uf_srh_load_puntos_campos($as_codpun,&$ai_totrows,&$ao_object)
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
		$ls_sql="SELECT srh_dt_puntosunitri.* ".
				"  FROM srh_dt_puntosunitri ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codpun='".$as_codpun."'".
				" ORDER BY codpun, prompun ";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->uf_srh_puntosunitri  MÉTODO->uf_srh_load_puntos_campos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$li_prompun=$this->uf_formatonumerico(trim ($row["prompun"]));
				$li_unitri=$this->uf_formatonumerico(trim ($row["unitri"]));
				$li_monbs=$this->uf_formatonumerico(trim ($row["monbs"]));
				$punto='"."';
				$coma='","';
				
				$ao_object[$ai_totrows][1]="<input name=txtprompun".$ai_totrows." type=text id=txtprompun".$ai_totrows." class=sin-borde size=15 maxlength=8 onKeyPress='return(ue_formatonumero(this,".$punto.", ".$coma.",event))' value=".$li_prompun." >";
				$ao_object[$ai_totrows][2]="<input name=txtunitri".$ai_totrows." type=text id=txtunitri".$ai_totrows." size=15 maxlength=8 onKeyPress='return(ue_formatonumero(this,".$punto.", ".$coma.",event))'  class=sin-borde value=".$li_unitri." >";
				$ao_object[$ai_totrows][3]="<input name=txtmonbs".$ai_totrows." type=text id=txtmonbs".$ai_totrows." class=sin-borde size=10 onFocus='javascript: multiplicar(".$ai_totrows.");'  readonly value= ".$li_monbs.">";
				$ao_object[$ai_totrows][4]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$ao_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt(".$ai_totrows.");    align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";	
						
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_escala
	//-----------------------------------------------------------------------------------------------------------------------------------	

//FUNCIONES PARA EL MANEJO DEL DETALLE DE LA ESCALA DE EVALUACIÓN

function uf_srh_guardar_dt_tablapuntosbonomerito($ao_puntos, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_dt_tablapuntosbonomerito															     	
		//         access: public (sigesp_dt_srh_puntosunitri )														
		//      Argumento: $ao_puntos    // arreglo con los datos de los detalle de la escala de evaluación					
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica escala de evaluación en la tabla srh_dt_tablapuntosbonomerito           
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 01/08/2008							Fecha Última Modificación: 01/08/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  		$ao_puntos->monbs=str_replace(".","",$ao_puntos->monbs);
		$ao_puntos->monbs=str_replace(",",".",$ao_puntos->monbs);
		
		$ao_puntos->prompun=str_replace(".","",$ao_puntos->prompun);
		$ao_puntos->prompun=str_replace(",",".",$ao_puntos->prompun);
		
		$ao_puntos->unitri=str_replace(".","",$ao_puntos->unitri);
		$ao_puntos->unitri=str_replace(",",".",$ao_puntos->unitri);
  
	 $this->io_sql->begin_transaction();
	 	 
	  $ls_sql = "INSERT INTO srh_dt_puntosunitri (codpun,prompun,unitri,monbs, codemp) ".	  
	            " VALUES ('$ao_puntos->codpun','$ao_puntos->prompun','$ao_puntos->unitri','$ao_puntos->monbs','".$this->ls_codemp."')";
		
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el detalla del promedio de puntos por unidad administraitiva ".$ao_puntos->codpun;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->tablapuntosbonomerito MÉTODO->guardar_dt_tablapuntosbonomerito ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
	
function uf_srh_eliminar_dt_tablapuntosbonomerito($as_codpun, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_dt_tablapuntosbonomerito																
		//        access:  public (sigesp_srh_dt_tablapuntosbonomerito)														
		//      Argumento: $as_codpun        // código de la escala de evaluación
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																				    
		//    Description: Funcion que elimina escala de evaluación en la tabla srh_dt_tablapuntosbonomerito                      
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 01/08/2008							Fecha Última Modificación: 01/08/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_dt_puntosunitri ".
	          " WHERE codpun='$as_codpun'  AND codemp='".$this->ls_codemp."'";
			  

	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->tablapuntosbonomerito MÉTODO->eliminar_dt_tablapuntosbonomerito ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el detalla del promedio de puntos por unidad administraitiva ".$as_codpun;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
			
	
	return $lb_borro;
	
  }
  
   	function uf_formatonumerico($as_valor)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_formatonumerico
		//		   Access: public
		//	    Arguments: as_valor  // valor sin formato numérico
		//	      Returns: as_valor valor numérico formateado
		//	  Description: Función que le da formato a los valores numéricos que vienen de la BD
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$as_valor=number_format($as_valor,2,",",".");

		return $as_valor;
	}// end function uf_formatonumerico
	
  
}
?>
