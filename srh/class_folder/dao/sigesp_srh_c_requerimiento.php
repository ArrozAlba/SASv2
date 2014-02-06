<?php

class sigesp_srh_c_requerimiento
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_requerimiento($path)
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
		//         Access: public (sigesp_srh_d_requerimiento)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de un requerimiento de cargo
		//    Description: Funcion que genera un código un requerimiento de cargo
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(codreq) AS codigo FROM srh_requerimientos  ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_codreq = $la_datos["codigo"][0]+1;
    $ls_codreq = str_pad ($ls_codreq,15,"0","left");
	 
    return $ls_codreq;
  }
	
	function uf_srh_select_requerimiento($as_codreq)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_requerimiento
		//         reqess: public (sigesp_srh_requerimientos)
		//      Argumento: $as_codreq    // codigo de requerimiento de cargo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un requerimiento de cargo en la tabla de  srh_requerimientos
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_requerimientos  ".
				  " WHERE codreq='".$as_codreq."'".
				  " AND codemp='".$this->ls_codemp."'" ;
   
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->requerimiento MÉTODO->uf_srh_select_requerimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_requerimiento

	function  uf_srh_insert_requerimiento($as_codreq,$as_denreq,$as_codtipreq,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_requerimiento
		//         reqess: public (sigesp_srh_requerimientos)
		//      Argumento: $as_codtipart   // codigo de requerimiento de cargo
	    //                 $as_dentipart   // denominacion de requerimiento de cargo
	    //                 $as_obstipart   // observacion de requerimiento de cargo
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un requerimiento de cargo en la tabla de srh_requerimientos
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO srh_requerimientos (codreq, denreq,codtipreq,codemp) ".
					" VALUES('".$as_codreq."','".$as_denreq."','".$as_codtipreq."','".$this->ls_codemp."')" ;
		
	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->requerimiento MÉTODO->uf_srh_insert_requerimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el requerimiento ".$as_codreq;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_requerimiento

	function uf_srh_update_requerimiento($as_codreq,$as_denreq,$as_codtipreq,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_requerimiento
		//         reqess: public (sigesp_srh_requerimientos)
		//      Argumento: $as_codtipart   // codigo de requerimiento de cargo
	    //                 $as_dentipart   // denominacion de requerimiento de cargo
	    //                 $as_obstipart   // observacion de requerimiento de cargo
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un requerimiento de cargo en la tabla de srh_requerimientos
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE srh_requerimientos SET   denreq='". $as_denreq ."', codtipreq='".$as_codtipreq."'". 
				   " WHERE codreq='" . $as_codreq ."'".
				   " AND codemp='".$this->ls_codemp."'";
		
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->requerimiento MÉTODO->uf_srh_update_requerimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el requerimiento ".$as_codreq;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_requerimiento

	function uf_srh_delete_requerimiento($as_codreq,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_requerimiento
		//         reqess: public (sigesp_srh_requerimientos)
		//      Argumento: $as_codtipart   // codigo de requerimiento de cargo
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un requerimiento de cargo en la tabla de srh_requerimientos verificando que este no
		//                 este siendo utilizado por ningun cargo.
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe= $this->uf_srh_select_requerimientocargo($as_codreq);
		if($lb_existe)
		{
				
			$lb_valido=false;
			
		}
		else
		{
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM srh_requerimientos".
						 " WHERE codreq= '".$as_codreq. "'"; 
			$li_row=$this->io_sql->execute($ls_sql);
			
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->requerimiento MÉTODO->uf_srh_delete_requerimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el requerimiento ".$as_codreq;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		return array($lb_valido,$lb_existe);
	} // end function uf_srh_delete_requerimiento
	
	function uf_srh_select_requerimientocargo($as_codreq)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_requerimientocategoria
		//         reqess: private
		//      Argumento: $as_codtipart   // codigo de requerimiento de cargo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existen categorias asociadas a un requerimiento
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_dt_cargo  ".
				  " WHERE codreq='".$as_codreq."'" ;
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->requerimiento MÉTODO->uf_srh_select_requerimientocargo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end function uf_srh_select_requerimientocargo
	

	
	function uf_srh_buscar_requerimiento($as_codreq,$as_denreq,$as_codtipreq)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_requerimiento
		//         reqess: private
		//      Argumento: $as_ccodreq    // codigo de requerimiento
		//                 $as_denreq     // descccripcion de requerimiento
		//                 $as_codtipreq  // codigo del tipo de requerimiento
		//
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existen categorias asociadas a un tiporeqidentes
		//	   Creado Por: María Beatriz Unda
 		// Fecha Creación: 18/10/2007							Fecha Última Modificación: 18/10/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$ls_coddestino="txtcodreq";
		$ls_dendestino="txtdenreq";
		$ls_tipodestino="txtcodtipreq";
		$ls_dentipodestino="txtdentipreq";
	
		
		$lb_valido=true;
		$ls_sql="SELECT codreq , denreq,srh_requerimientos.codtipreq,dentipreq FROM srh_requerimientos, srh_tiporequerimientos ".
				 "WHERE srh_tiporequerimientos.codtipreq= srh_requerimientos.codtipreq ".
                 "AND  codreq like '".$as_codreq."' ".
                 "AND denreq like '".$as_denreq."' ".
                 "AND srh_requerimientos.codtipreq like '".$as_codtipreq."' ".
                 "ORDER BY codreq ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->requerimiento MÉTODO->uf_srh_buscar_requerimiento( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			 
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
					$ls_codreq=$row["codreq"];
					$ls_denreq=htmlentities ($row["denreq"]);
					$ls_codtipreq=$row["codtipreq"];
					$ls_dentipreq=htmlentities  ($row["dentipreq"]);
					
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codreq']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codreq']." ^javascript:aceptar(\"$ls_codreq\",\"$ls_denreq\",\"$ls_codtipreq\",\"$ls_dentipreq\",\"$ls_coddestino\",\"$ls_dendestino\",\"$ls_tipodestino\",\"$ls_dentipodestino\");^_self"));
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_denreq));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_dentipreq));												
					$row_->appendChild($cell);
			
			}
			return $dom->saveXML();

		}
        
	} // end function uf_srh_buscar_requerimiento(
	

}// end   class sigesp_srh_c_requerimiento
?>
