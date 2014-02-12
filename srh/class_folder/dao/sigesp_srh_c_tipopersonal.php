<?php

class sigesp_srh_c_tipopersonal
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_tipopersonal($path)
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
		//         Access: public (sigesp_srh_d_tipopersonal)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de un tipo de personal 
		//    Description: Funcion que genera un código de un tipo de personal
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(codtipper) AS codigo FROM srh_tipopersonal  ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_codtipper = $la_datos["codigo"][0]+1;
    $ls_codtipper = str_pad ($ls_codtipper,3,"0","left");
	 
    return $ls_codtipper;
  }


	
	function uf_srh_select_tipopersonal($as_codtipper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_tipopersonal
		//         areaess: public 
		//      Argumento: $as_codtipper    // codigo de profesion
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un tipo de personal en la tabla de  srh_tipopersonal
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 26/11/2007					Fecha Última Modificación: 26/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_tipopersonal  ".
				  " WHERE codtipper='".trim($as_codtipper)."'".
				  " AND codemp='".$this->ls_codemp."'" ;
	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->profesion MÉTODO->uf_srh_select_tipopersonal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_tipopersonal

	function  uf_srh_insert_tipopersonal($as_codtipper,$as_dentipper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_tipopersonal
		//         areaess: public (sigesp_siv_d_tipoarticulo)
		//      Argumento: $as_codtipper   // codigo de profesion
	    //                 $as_dentipper// denominacion de profesion
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un tipo de personal en la tabla de srh_tipopersonal
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 26/11/2007				Fecha Última Modificación: 26/11/2007 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO srh_tipopersonal (codtipper, dentipper,codemp) ".
					" VALUES('".$as_codtipper."','".$as_dentipper."','".$this->ls_codemp."')" ;
		
		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->profesion MÉTODO->uf_srh_insert_tipopersonal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el tipo de personal ".$as_codtipper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_tipopersonal

	function uf_srh_update_tipopersonal($as_codtipper,$as_dentipper,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_tipopersonal
		//         areaess: public (sigesp_siv_d_tipoarticulo)
		//      Argumento: $as_codtipper   // codigo de profesion
	    //                 $as_dentipper   // denominacion de profesion
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un tipo de personal en la tabla de srh_tipopersonal
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 26/11/2007								Fecha Última Modificación: 26/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE  srh_tipopersonal SET   dentipper='". $as_dentipper."'". 
				   " WHERE codtipper='" . $as_codtipper ."'".
				   " AND codemp='".$this->ls_codemp."'";
		
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->profesion MÉTODO->uf_srh_update_tipopersonal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el tipo de personal ".$as_codtipper;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_tipopersonal
	
function uf_select_tipopersonal_bono ($as_codtipper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_tipopersonal_bono
		//		   Access: private
 		//	    Arguments: as_codtipper // código del tipo de personal
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el tipo de personal esta asociado a un bno por merito
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 04/08/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codtipper ".
				 "  FROM srh_bono_merito".
				 "  WHERE codemp  ='".$this->ls_codemp."' ".
				 "    AND codtipper = '".$as_codtipper."' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->tipopersonal  MÉTODO->uf_select_tipopersonal_bono ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	
function uf_select_tipopersonal_tabla ($as_codtipper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_tipopersonal_tabla
		//		   Access: private
 		//	    Arguments: as_codtipper // código del tipo de personal
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el tipo de personal esta asociado a una tabla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 04/08/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codtipper ".
				 "  FROM srh_puntosunitri".
				 "  WHERE codemp  ='".$this->ls_codemp."' ".
				 "    AND codtipper = '".$as_codtipper."' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->tipopersonal  MÉTODO->uf_select_tipopersonal_tabla ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	
function uf_select_tipopersonal_puntuacion ($as_codtipper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:uf_select_seccion_departamento
		//		   Access: private
 		//	    Arguments: as_coddep // código del departamento 
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el departamento esta asociada a una seccion
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codtipper ".
				 "  FROM srh_puntuacion_bono_merito".
				 "  WHERE codemp  ='".$this->ls_codemp."' ".
				 "    AND codtipper = '".$as_codtipper."' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->tipopersonal  MÉTODO->uf_select_tipopersonal_puntuacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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

function uf_srh_delete_tipopersonal($as_codtipper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_tipopersonal
		//         areaess: public 
		//      Argumento: $as_codtipper   // codigo de profesion
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un tipo de personal en la tabla de srh_tipopersonal 
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 26/11/2007						Fecha Última Modificación: 26/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		 $lb_valido=false;
	     $lb_existe=true;
		
		if (($this->uf_select_tipopersonal_puntuacion ($as_codtipper)===false)&&
		     ($this->uf_select_tipopersonal_tabla ($as_codtipper)===false)&&
			 ($this->uf_select_tipopersonal_bono ($as_codtipper)===false))
		 {
			
			$lb_existe=false;
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM srh_tipopersonal".
						 " WHERE codtipper= '".$as_codtipper. "'".
						 "AND codemp='".$this->ls_codemp."'"; 
			
			
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->profesion MÉTODO->uf_srh_delete_tipopersonal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el tipo de personal ".$as_codtipper;
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
	} // end function uf_srh_delete_tipopersonal
	
	

	

 function uf_srh_buscar_tipopersonal($as_codtipper,$as_dentipper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_tipopersonal
		//         Access: private
		//      Argumento: $as_codtipper  // codigo de la profesion
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca un profesion  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 26/11/2007				Fecha Última Modificación: 26/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_coddestino="txtcodtipper";
		$ls_dendestino="txtdentipper";
	
		
		$lb_valido=true;
		$ls_sql="SELECT * FROM srh_tipopersonal".
				" WHERE codtipper like '".$as_codtipper."' ".
				"   AND dentipper like '".$as_dentipper."' ".
			   " ORDER BY codtipper";
	 
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->profesion MÉTODO->uf_srh_buscar_tipopersonal( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
			$dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			 	
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
					
					$ls_codtipper=$row["codtipper"];
					$ls_dentipper=htmlentities ($row["dentipper"]);
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codtipper']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codtipper']." ^javascript:aceptar(\"$ls_codtipper\",\"$ls_dentipper\",\"$ls_coddestino\",\"$ls_dendestino\");^_self"));
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_dentipper));												
					$row_->appendChild($cell);
					
					
			
			}
			return $dom->saveXML();
		
		
		}
      
		
	} // end function uf_srh_buscar_tipopersonal
	

}// end   class sigesp_srh_c_tipopersonal
?>