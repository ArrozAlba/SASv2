<?php

class sigesp_srh_c_tipoaccidente
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_tipoaccidente($path)
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
		//         Access: public (sigesp_srh_d_tipoaccidente)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de un tipo de accidente
		//    Description: Funcion que genera un código de un tipo de accidente
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(codacc) AS codigo FROM srh_tipoaccidentes";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_codacc = $la_datos["codigo"][0]+1;
	$ls_codacc = str_pad ($ls_codacc,15,"0","left");
	 return $ls_codacc;
  }

	
	function uf_srh_select_tipoaccidente($as_codacc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_tipoaccidentes
		//         Access: public (sigesp_srh_d_tipoaccidente)
		//      Argumento: $as_codacc    // codigo de tipo de accidente
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un tipo de accidente en la tabla de  srh_tipoaccidente
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_tipoaccidentes  ".
				  " WHERE codacc='".trim($as_codacc)."'".
				  " AND codemp='".$this->ls_codemp."'" ;

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tipoaccidentes MÉTODO->uf_srh_select_tipoaccidente ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_tipoaccidentes

	function  uf_srh_insert_tipoaccidente($as_codacc,$as_denacc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_tipoaccidentes
		//         Access: public (sigesp_srh_d_tipoaccidente)
		//      Argumento: $as_codacc   // codigo de tipo de accidente
	    //                 $as_denacc   // denominacion del accidente
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un tipo de accidente en la tabla de srh_tipoaccidente
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO srh_tipoaccidentes (codacc, denacc,codemp) ".
				" VALUES('".$as_codacc."','".$as_denacc."','".$this->ls_codemp."')" ;
		

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->tipoaccidentes MÉTODO->uf_srh_insert_tipoaccidentes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Tipo de Contrato ".$as_codacc;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_tipoaccidentes

	function uf_srh_update_tipoaccidente($as_codacc,$as_denacc,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_tipoaccidentes
		//         Access: public (sigesp_srh_d_tipoaccidente)
		//      Argumento: $as_codacc   // codigo de tipo de accidente
	    //                 $as_denacc  // denominacion de tipo de accidente
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un tipo de accidente en la tabla de srh_tipoaccidente
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE srh_tipoaccidentes SET   denacc='". $as_denacc ."'". 
				   " WHERE codacc='" . $as_codacc ."'".
				   " AND codemp='".$this->ls_codemp."'";

        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->tipoaccidentes MÉTODO->uf_srh_update_tipoaccidentes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el Tipo de Contrato".$as_codacc;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_tipoaccidentes

	function uf_srh_delete_tipoaccidente($as_codacc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_tipoaccidentes
		//         Access: public (sigesp_srh_d_tipoaccidente)
		//      Argumento: $as_codacc   // codigo de tipo de accidente
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un tipo de accidente en la tabla de srh_tipoaccidente 
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe= $this->uf_srh_select_tipo_accidente($as_codacc);
		if($lb_existe)
		{
			$lb_valido=false;
			
		}
		else
		{
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM srh_tipoaccidentes".
						 " WHERE codacc= '".$as_codacc. "'".
						 "AND codemp='".$this->ls_codemp."'"; 

			$li_row=$this->io_sql->execute($ls_sql);
			
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->tipoaccidentes MÉTODO->uf_srh_delete_tipoaccidentes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Tipo de Contrato ".$as_codacc;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		return array($lb_valido,$lb_existe);
	} // end function uf_srh_delete_tipoaccidentes
	
	function uf_srh_select_tipo_accidente($as_codacc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_tipo_accidente
		//         Access: private
		//      Argumento: $as_codacc   // codigo de tipo de accidente
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existen categorias asociadas a un tipoaccidentes
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT codacc FROM srh_accidentes ".
				  " WHERE codacc='".$as_codacc."'" ;
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tipoaccidentes MÉTODO->uf_srh_select_tipo_accidente ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end function uf_srh_select_tipo_accidente
	

		
	function uf_srh_buscar_tipoaccidente($as_codacc,$as_denacc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_tipoaccidente
		//         Access: private
		//      Argumento: $as_codacc  // codigo del tipo de accidente
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca un departamento  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 16/10/2007							Fecha Última Modificación: 16/10/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_coddestino="txtcodacc";
		$ls_dendestino="txtdenacc";
	
		
		$lb_valido=true;
		$ls_sql="SELECT * FROM srh_tipoaccidentes".
				" WHERE codacc like '".$as_codacc."' ".
				"   AND denacc like '".$as_denacc."' ".
			   " ORDER BY codacc";
	
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->departamento MÉTODO->uf_srh_buscar_tipoaccidente( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
					$ls_codacc=$row["codacc"];
					$ls_denacc=htmlentities ($row["denacc"]);
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codacc']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codacc']." ^javascript:aceptar(\"$ls_codacc\",\"$ls_denacc\",\"$ls_coddestino\",\"$ls_dendestino\");^_self"));
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_denacc));												
					$row_->appendChild($cell);
					
					
			
			}
			return $dom->saveXML();
		
			
			
		}	

	} // end function uf_srh_buscar_tipoaccidente
	

}// end   class sigesp_srh_c_tipoaccidente
?>