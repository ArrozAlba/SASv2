<?php

class sigesp_srh_c_tipodocumento
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_tipodocumento($path)
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
		//         Access: public (sigesp_srh_d_concurso)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de un tipo de documento
		//    Description: Funcion que genera un código un concurso
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(codtipdoc) AS codigo FROM srh_tipodocumentos  ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_codtipdoc = $la_datos["codigo"][0]+1;
    $ls_codtipdoc = str_pad ($ls_codtipdoc,15,"0","left");
	return $ls_codtipdoc;
  }

	
	function uf_srh_select_tipodocumento($as_codtipdoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_tipodocumento
		//         Access: public (sigesp_srh_d_tipodocumento)
		//      Argumento: $as_codtipdoc    // codigo de tipo de documento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un tipo de documento en la tabla de  srh_tipodocumentos
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_tipodocumentos  ".
				  " WHERE codtipdoc='".trim($as_codtipdoc)."'".
				  " AND codemp='".$this->ls_codemp."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tipodocumento MÉTODO->uf_srh_select_tipodocumento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_tipodocumento

	function  uf_srh_insert_tipodocumento($as_codtipdoc,$as_dentipdoc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_tipodocumento
		//         Access: public (sigesp_srh_d_tipodocumento)
		//      Argumento: $as_codtipdoc   // codigo de tipo de documento
	    //                 $as_dentipart   // denominacion de tipo de documento
	    //                 $as_obstipart   // observacion de tipo de documento
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un tipo de documento en la tabla de srh_tipodocumentos
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO srh_tipodocumentos (codtipdoc, dentipdoc,codemp) ".
				" VALUES('".$as_codtipdoc."','".$as_dentipdoc."','".$this->ls_codemp."')" ;
		
		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->tipodocumento MÉTODO->uf_srh_insert_tipodocumento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Tipo de Documento ".$as_codtipdoc;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_tipodocumento

	function uf_srh_update_tipodocumento($as_codtipdoc,$as_dentipdoc,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_tipodocumento
		//         Access: public (sigesp_srh_d_tipodocumento)
		//      Argumento: $as_codtipdoc   // codigo de tipo de documento
	    //                 $as_dentipart   // denominacion de tipo de documento
	    //                 $as_obstipart   // observacion de tipo de documento
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un tipo de documento en la tabla de srh_tipodocumentos
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE srh_tipodocumentos SET   dentipdoc='". $as_dentipdoc ."'". 
				   " WHERE codtipdoc='" . $as_codtipdoc ."'".
				   " AND codemp='".$this->ls_codemp."'";
	 
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->tipodocumento MÉTODO->uf_srh_update_tipodocumento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el Tipo de Documento".$as_codtipdoc;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_tipodocumento

	function uf_srh_delete_tipodocumento($as_codtipdoc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_tipodocumento
		//         Access: public (sigesp_srh_d_tipodocumento)
		//      Argumento: $as_codtipdoc   // codigo de tipo de documento
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un tipo de documento en la tabla de srh_tipodocumentos 
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe= $this->uf_srh_select_tipo_documento($as_codtipdoc);
		if($lb_existe)
		{
				
			$lb_valido=false;
			
		}
		else
		{
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM srh_tipodocumentos".
						 " WHERE codtipdoc= '".$as_codtipdoc. "'".
						 "AND codemp='".$this->ls_codemp."'"; 
			$li_row=$this->io_sql->execute($ls_sql);
			
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->tipodocumento MÉTODO->uf_srh_delete_tipodocumento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Tipo de Documento ".$as_codtipdoc;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		return array($lb_valido,$lb_existe);
	} // end function uf_srh_delete_tipodocumento
	
	function uf_srh_select_tipo_documento($as_codtipdoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_tipodocumentocategoria
		//         Access: private
		//      Argumento: $as_codtipdoc   // codigo de tipo de documento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existen documentos asociadas a un tipodocumento
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT codtipdoc FROM srh_documentos  ".
				  " WHERE codtipdoc='".$as_codtipdoc."'" ;
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tipodocumento MÉTODO->uf_srh_select_tipo_documento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} 
	
	
	function uf_srh_buscar_tipodocumento($as_codtipdoc,$as_dentipdoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_tipodocumento
		//         Access: private
		//      Argumento: $as_codtipdoc  // codigo de la tipodocumento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca un tipodocumento  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 04/09/2007							Fecha Última Modificación: 04/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_coddestino="txtcodtipdoc";
		$ls_dendestino="txtdentipdoc";
	
		
		$lb_valido=true;
		$ls_sql="SELECT * FROM srh_tipodocumentos".
				" WHERE codtipdoc like '".$as_codtipdoc."' ".
				"   AND dentipdoc like '".$as_dentipdoc."' ".
			   " ORDER BY codtipdoc";

		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tipodocumento MÉTODO->uf_srh_buscar_tipodocumento( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
					$ls_codtipdoc=$row["codtipdoc"];
					$ls_dentipdoc=htmlentities ($row["dentipdoc"]);
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codtipdoc']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codtipdoc']." ^javascript:aceptar(\"$ls_codtipdoc\",\"$ls_dentipdoc\",\"$ls_coddestino\",\"$ls_dendestino\");^_self"));
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_dentipdoc));												
					$row_->appendChild($cell);
					
					
			
			}
			return $dom->saveXML();
			

		}
        
		
	} // end function uf_srh_buscar_tipodocumento(
	

}// end   class sigesp_srh_c_tipodocumento
?>