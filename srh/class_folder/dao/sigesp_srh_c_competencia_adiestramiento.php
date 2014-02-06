<?php

class sigesp_srh_c_competencia_adiestramiento
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_competencia_adiestramiento($path)
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
		//         Access: public (sigesp_srh_d_competencia_adiestramiento)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de un competencia de adiestramiento
		//    Description: Funcion que genera un código de un competencia de adiestramiento
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(codcompadi) AS codigo FROM srh_competencias_adiestramiento  ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_codcompadi = $la_datos["codigo"][0]+1;
    $ls_codcompadi = str_pad ($ls_codcompadi,15,"0","left");
	 
    return $ls_codcompadi;
  }
	
	function uf_srh_select_competencia_adiestramiento($as_codcompadi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_competencia_adiestramiento
		//      Argumento: $as_codcompadi    // codigo de la competencia de adiestramiento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de competencia de adiestramiento en la tabla de  
		//                 srh_competencias_adiestramiento
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 13/05/2008							Fecha Última Modificación: 13/05/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_competencias_adiestramiento  ".
				  " WHERE codcompadi='".trim($as_codcompadi)."'".
				  " AND codemp='".$this->ls_codemp."'" ;
	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Competencia Adiestramiento MÉTODO->uf_srh_select_competencia_adiestramiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_competencia_adiestramiento

	function  uf_srh_insert_competencia_adiestramiento($as_codcompadi,$as_dencompadi,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_competencia_adiestramiento
		//         areaess: public 
		//      Argumento: $as_codcompadi   // codigo de competencia de adiestramiento
	    //                 $as_dencompadi   // denominacion de competencia de adiestramiento
	    //		           $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta competencia de adiestramiento en la tabla de srh_competencias_adiestramiento
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 13/05/2008							Fecha Última Modificación: 13/05/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO srh_competencias_adiestramiento (codcompadi, dencompadi,codemp) ".
					" VALUES('".$as_codcompadi."','".$as_dencompadi."','".$this->ls_codemp."')" ;
		
		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Competencia Adiestramiento MÉTODO->uf_srh_insert_competencia_adiestramiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la competencia de adiestramiento ".$as_codcompadi;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_competencia_adiestramiento

	function uf_srh_update_competencia_adiestramiento($as_codcompadi,$as_dencompadi,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_competencia_adiestramiento
		//         areaess: public 
		//      Argumento: $as_codcompadi        // código de competencia de adiestramiento
	    //                 $as_dencompadi       // denominación de competencia de adiestramiento
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica competencia de adiestramiento en la tabla de srh_competencias_adiestramiento
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 13/05/2008							Fecha Última Modificación: 13/05/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE srh_competencias_adiestramiento SET   dencompadi='". $as_dencompadi."'". 
				   " WHERE codcompadi='" . $as_codcompadi ."'".
				   " AND codemp='".$this->ls_codemp."'";
		
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Competencia Adiestramiento MÉTODO->uf_srh_update_competencia_adiestramiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó la competencia de adiestramiento ".$as_codcompadi;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_competencia_adiestramiento
	
	
	function uf_select_competencia_adiestramiento_necesidad ($as_codcompadi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:uf_select_competencia_adiestramiento_necesidad
		//		   Access: private
 		//	    Arguments: as_codcompadi // código de la competencia de adiestramiento
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el área esta asociada a una solicitud de empleo
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 13/05/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codcompadi ".
				 "  FROM srh_dt_competencias_adiestramiento ".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND codcompadi = '".$as_codcompadi."' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->Competencia Adiestramiento  MÉTODO->uf_select_competencia_adiestramiento_necesidad  ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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

	function uf_srh_delete_competencia_adiestramiento($as_codcompadi,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_competencia_adiestramiento
		//         areaess: public 
		//      Argumento: $as_codcompadi       // código de la competencia de adiestramiento
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina competencia de adiestramiento en la tabla de srh_competencias_adiestramiento
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 13/05/2008							Fecha Última Modificación: 13/05/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe=true;
		$lb_existe= $this->uf_select_competencia_adiestramiento_necesidad ($as_codcompadi);
		if($lb_existe)
		{
				
			$lb_valido=false;
			
		}
		else
		{
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM srh_competencias_adiestramiento".
						 " WHERE codcompadi= '".$as_codcompadi. "'".
						 "AND codemp='".$this->ls_codemp."'"; 
			

			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->Competencia Adiestramiento MÉTODO->uf_srh_delete_competencia_adiestramiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la competencia de adiestramiento ".$as_codcompadi;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		return array($lb_valido,$lb_existe);
	} // end function uf_srh_delete_competencia_adiestramiento
	
	
	
function uf_srh_buscar_competencia_adiestramiento($as_codcompadi,$as_dencompadi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_competencia_adiestramiento
		//         Access: private
		//      Argumento: $as_codcompadi  // codigo de la area
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca un area  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 13/05/2008							Fecha Última Modificación: 13/05/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_coddestino="txtcodcompadi";
		$ls_dendestino="txtdencompadi";
	
		
		$lb_valido=true;
		$ls_sql="SELECT * FROM srh_competencias_adiestramiento".
				" WHERE codcompadi like '".$as_codcompadi."' ".
				"   AND dencompadi like '".$as_dencompadi."' ".
			   " ORDER BY codcompadi";
	 
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Competencia Adiestramiento MÉTODO->uf_srh_buscar_competencia_adiestramiento( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
					$ls_codcompadi=$row["codcompadi"];
					$ls_dencompadi=htmlentities  ($row["dencompadi"]);
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codcompadi']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codcompadi']." ^javascript:aceptar(\"$ls_codcompadi\",\"$ls_dencompadi\",\"$ls_coddestino\",\"$ls_dendestino\");^_self"));
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_dencompadi));												
					$row_->appendChild($cell);
					
					
			
			}
			return $dom->saveXML();
		
			
			
		
		}
      
		
	} // end function uf_srh_buscar_competencia_adiestramiento
	

}// end   class sigesp_srh_c_competencia_adiestramiento
?>