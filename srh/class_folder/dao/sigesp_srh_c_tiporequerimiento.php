<?php

class sigesp_srh_c_tiporequerimiento
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_tiporequerimiento($path)
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
		//         Access: public (sigesp_srh_d_tiporequerimiento)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de tipo de requerimiento
		//    Description: Funcion que genera un código de tipo de requerimiento
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(codtipreq) AS codigo FROM srh_tiporequerimientos  ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_codtipreq = $la_datos["codigo"][0]+1;
    $ls_codtipreq = str_pad ($ls_codtipreq,15,"0","left");
	return $ls_codtipreq;
  }
	
	function uf_srh_select_tiporequerimiento($as_codtipreq)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_tiporequerimiento
		//      Argumento: $as_codtipreq    // codigo del tipo de requerimiento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un tipo de requerimiento en la tabla de  srh_tiporequerimientos
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 04/09/2007							Fecha Última Modificación: 04/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_tiporequerimientos  ".
				  " WHERE codtipreq='".trim($as_codtipreq)."'".
				  " AND codemp='".$this->ls_codemp."'" ;
	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tiporequerimiento MÉTODO->uf_srh_select_tiporequerimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_tiporequerimiento

	function  uf_srh_insert_tiporequerimiento($as_codtipreq,$as_dentipreq,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_tiporequerimiento
		//         tipreqess: public (sigesp_siv_d_tipoarticulo)
		//      Argumento: $as_codtipreq   // codigo del tipo de requerimiento
	    //                 $as_dentipreq   // denominacion del tipo de requerimiento
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un tipo de requerimiento en la tabla de srh_tiporequerimientos
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 04/09/2007							Fecha Última Modificación: 04/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO srh_tiporequerimientos (codtipreq, dentipreq,codemp) ".
					" VALUES('".$as_codtipreq."','".$as_dentipreq."','".$this->ls_codemp."')" ;
		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->tiporequerimiento MÉTODO->uf_srh_insert_tiporequerimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el tiporequerimiento ".$as_codtipreq;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_tiporequerimiento

	function uf_srh_update_tiporequerimiento($as_codtipreq,$as_dentipreq,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_tiporequerimiento
		//         tipreqess: public (sigesp_siv_d_tipoarticulo)
		//      Argumento: $as_codtipreq   // codigo del tipo de requerimiento
	    //                 $as_dentipreq   // denominacion del tipo de requerimiento
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un tipo de requerimiento en la tabla de srh_tiporequerimientos
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 04/09/2007							Fecha Última Modificación: 04/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE srh_tiporequerimientos SET   dentipreq='". $as_dentipreq ."'". 
				   " WHERE codtipreq='" . $as_codtipreq ."'".
				   " AND codemp='".$this->ls_codemp."'";
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->tiporequerimiento MÉTODO->uf_srh_update_tiporequerimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el tiporequerimiento ".$as_codtipreq;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_tiporequerimiento






function uf_srh_delete_tiporequerimiento($as_codtipreq,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_tiporequerimiento
		//         tipreqess: public (sigesp_siv_d_tipoarticulo)
		//      Argumento: $as_codtipreq   // codigo del tipo de requerimiento
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un tipo de requerimiento en la tabla de srh_tiporequerimientos 
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 04/09/2007							Fecha Última Modificación: 04/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe= $this->uf_srh_select_tiporeq_requerimiento($as_codtipreq);
		if($lb_existe)
		{
			$lb_existe=true;		
			$lb_valido=false;
			
		}
		else
		{
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM srh_tiporequerimientos".
						 " WHERE codtipreq= '".$as_codtipreq. "'"; 
						 
						 
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->tiporequerimiento MÉTODO->uf_srh_delete_tiporequerimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el tiporequerimiento ".$as_codtipreq;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		return array($lb_valido,$lb_existe);
	} // end function uf_srh_delete_tiporequerimiento
	
function uf_srh_select_tiporeq_requerimiento($as_codtipreq)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_tiporeq_requerimiento
		//         tipreqess: private
		//      Argumento: $as_codtipreq   // codigo del tipo de requerimiento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existen categorias asociadas a un tiporequerimiento
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 04/09/2007							Fecha Última Modificación: 04/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_sql = "SELECT * FROM srh_requerimientos  ".
				  " WHERE codtipreq='".$as_codtipreq."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tiporequerimiento MÉTODO->uf_srh_select_tiporeq_requerimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end function uf_srh_select_tiporeq_requerimiento
	
		
function uf_srh_buscar_tiporequerimiento($as_codtipreq,$as_dentipreq)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_tiporequerimiento
		//         Access: private
		//      Argumento: $as_codtipreq  // codigo del  tipo de requerimiento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca un tipo de requerimiento  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 04/09/2007							Fecha Última Modificación: 18/10/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_coddestino="txtcodtipreq";
		$ls_dendestino="txtdentipreq";
	
		
		$lb_valido=true;
		$ls_sql="SELECT * FROM srh_tiporequerimientos".
				" WHERE codtipreq like '".$as_codtipreq."' ".
				"   AND dentipreq like '".$as_dentipreq."' ".
			   " ORDER BY codtipreq";
	  
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tiporequerimiento MÉTODO->uf_srh_buscar_tiporequerimiento( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
					$ls_codtipreq=$row["codtipreq"];
					$ls_dentipreq=htmlentities ($row["dentipreq"]);
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codtipreq']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codtipreq']." ^javascript:aceptar(\"$ls_codtipreq\",\"$ls_dentipreq\",\"$ls_coddestino\",\"$ls_dendestino\");^_self"));
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_dentipreq));												
					$row_->appendChild($cell);
					
					
			
			}
			return $dom->saveXML();

		}
        
	} // end function uf_srh_buscar_tiporequerimiento(
	

}// end   class sigesp_srh_c_tiporequerimiento
?>
