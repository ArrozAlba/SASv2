<?php

class sigesp_srh_c_grupomovimiento
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_grupomovimiento($path)
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
		//         Access: public (sigesp_srh_d_grupomovimiento)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de un grupo de movimiento de personal
		//    Description: Funcion que genera un código de un grupo de movimiento de personal
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(codgrumov) AS codigo FROM srh_grupomovimientos ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_codgrumov = $la_datos["codigo"][0]+1;
    $ls_codgrumov = str_pad ($ls_codgrumov,15,"0","left");
    return $ls_codgrumov;
  }

	
	function uf_srh_select_grupomovimiento($as_codgrumov)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_grupomovimientos
		//         Access: public (sigesp_srh_d_grupomovimientos)
		//      Argumento: $as_codgrumov    // codigo de grupo de movimiento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un grupo de movimiento en la tabla de  srh_grupomovimientos
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_grupomovimientos  ".
				  " WHERE codgrumov='".trim($as_codgrumov)."'".
				  " AND codemp='".$this->ls_codemp."'" ;
	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->grupomovimientos MÉTODO->uf_srh_select_grupomovimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_grupomovimientos

	function  uf_srh_insert_grupomovimiento($as_codgrumov,$as_dengrumov,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_grupomovimientos
		//         Access: public (sigesp_siv_d_tipoarticulo)
		//      Argumento: $as_codgrumov   // codigo de grupo de movimiento
	    //                 $as_dentipart   // denominacion de grupo de movimiento
	    //                 $as_obstipart   // observacion de grupo de movimiento
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un grupo de movimiento en la tabla de siv_tipoarticulo
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO srh_grupomovimientos (codgrumov, dengrumov,codemp) ".
				" VALUES('".$as_codgrumov."','".$as_dengrumov."','".$this->ls_codemp."')" ;
		

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->grupomovimientos MÉTODO->uf_srh_insert_grupomovimientos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Nivel de Seleccion ".$as_codgrumov;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_grupomovimientos

	function uf_srh_update_grupomovimiento($as_codgrumov,$as_dengrumov,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_grupomovimientos
		//         Access: public (sigesp_siv_d_tipoarticulo)
		//      Argumento: $as_codgrumov   // codigo de grupo de movimiento
	    //                 $as_dentipart   // denominacion de grupo de movimiento
	    //                 $as_obstipart   // observacion de grupo de movimiento
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un grupo de movimiento en la tabla de siv_tipoarticulo
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE srh_grupomovimientos SET   dengrumov='". $as_dengrumov ."'". 
				   " WHERE codgrumov='" . $as_codgrumov ."'".
				   " AND codemp='".$this->ls_codemp."'";
	   
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->grupomovimientos MÉTODO->uf_srh_update_grupomovimientos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el Nivel de Seleccion".$as_codgrumov;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_grupomovimientos

	function uf_srh_delete_grupomovimiento($as_codgrumov,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_grupomovimientos
		//         Access: public (sigesp_siv_d_tipoarticulo)
		//      Argumento: $as_codgrumov   // codigo de grupo de movimiento
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un grupo de movimiento 
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe=true;
		$lb_existe= $this->uf_srh_select_grupo_movimiento($as_codgrumov);
		if($lb_existe)
		{
			
			$lb_valido=false;
			
		}
		else
		{
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM srh_grupomovimientos".
						 " WHERE codgrumov= '".$as_codgrumov. "'".
						 "AND codemp='".$this->ls_codemp."'"; 
			$li_row=$this->io_sql->execute($ls_sql);
			
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->grupomovimientos MÉTODO->uf_srh_delete_grupomovimientos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Nivel de Seleccion ".$as_codgrumov;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		return array($lb_valido,$lb_existe);
	} // end function uf_srh_delete_grupomovimientos
	
	function uf_srh_select_grupo_movimiento($as_codgrumov)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_grupo_movimiento
		//         Access: private
		//      Argumento: $as_codgrumov   // codigo de grupo de movimiento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existen movimientos de personal asociadas a un grupomovimientos
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT grumov FROM srh_movimiento_personal  ".
				  " WHERE grumov='".$as_codgrumov."'" ;
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->grupomovimientos MÉTODO->uf_srh_select_grupo_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end function uf_srh_select_grupomovimientoscategoria
	

function uf_srh_buscar_grupomovimiento($as_codgrumov,$as_dengrumov)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_grupomovimiento
		//         Access: private
		//      Argumento: $as_codgrumov  // codigo de la grupomovimiento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca un grupomovimiento  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 04/09/2007							Fecha Última Modificación: 04/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_coddestino="txtcodgrumov";
		$ls_dendestino="txtdengrumov";
	
		
		$lb_valido=true;
		$ls_sql="SELECT * FROM srh_grupomovimientos".
				" WHERE codgrumov like '".$as_codgrumov."' ".
				"   AND dengrumov like '".$as_dengrumov."' ".
			   " ORDER BY codgrumov";

		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->grupomovimiento MÉTODO->uf_srh_buscar_grupomovimiento( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     	$ls_codgrumov=$row["codgrumov"];
					$ls_dengrumov=htmlentities ($row["dengrumov"]);
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codgrumov']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codgrumov']." ^javascript:aceptar(\"$ls_codgrumov\",\"$ls_dengrumov\",\"$ls_coddestino\",\"$ls_dendestino\");^_self"));
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_dengrumov));												
					$row_->appendChild($cell);
					
					
			
			}
			return $dom->saveXML();
		
		}
     
	} // end function uf_srh_buscar_grupomovimiento
	

}// end   class sigesp_srh_c_grupomovimiento
?>