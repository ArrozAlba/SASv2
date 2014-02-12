<?php

class sigesp_srh_c_seccion
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;
	var $coddep = null;	
	var $densec = null;
	var $codsec = null;

	function sigesp_srh_c_seccion($path)
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


	function uf_srh_select_seccion($as_codsec)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_seccion
		//         Access: public (sigesp_srh_d_seccion)
		//      Argumento: $as_codsec    // codigo de la seccion
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de una seccion de evaluacion en la tabla de  srh_seccion
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:05/09/2007							Fecha Última Modificación:05/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_seccion  ".
				  " WHERE codsec='".trim($as_codsec)."'";
				  " AND codemp='".$this->ls_codemp."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->seccion MÉTODO->uf_srh_select_seccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_seccion

	function  uf_srh_insert_seccion($as_codsec,$as_densec,$as_coddep,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_seccion
		//         Access: public (sigesp_srh_d_seccion)
		//      Argumento: $as_codsec  // codigo de la seccion
	    //                 $as_densec  // denominacion de la seccion
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta una seccion en la tabla de srh_seccion
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:05/09/2007							Fecha Última Modificación:05/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO srh_seccion (codsec, densec,codemp,coddep) ".
					" VALUES('".$as_codsec."','".$as_densec."','".$this->ls_codemp."', '".$as_coddep."')" ;
		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->seccion MÉTODO->uf_srh_insert_seccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la seccion ".$as_codsec;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],

												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_seccion

	function uf_srh_update_seccion($as_codsec,$as_densec,$as_coddep,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_seccion
		//         Access: public (sigesp_srh_d_seccion)
		//      Argumento: $as_codsec  // codigo de la seccion
	    //                 $as_densec  // denominacion de la seccion
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica una seccion de evaluacion en la tabla de srh_seccion
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:05/09/2007							Fecha Última Modificación:05/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE srh_seccion SET   densec='". $as_densec."', coddep='". $as_coddep."'".
				   " WHERE codsec='" . $as_codsec ."'".
				   " AND codemp='".$this->ls_codemp."'";
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->seccion MÉTODO->uf_srh_update_seccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó la seccion ".$as_codsec;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_seccion

	function uf_srh_delete_seccion($as_codsec,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_seccion
		//         Access: public (sigesp_srh_d_seccion)
		//      Argumento: $as_codsec  // codigo de la seccion
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina una seccion en la tabla de srh_seccion verificando que este no 
		//                 este siendo  utilizado por ningun departamento.
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:05/09/2007							Fecha Última Modificación:05/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$lb_valido=true;
		$lb_existe=false;
		//$this->uf_srh_select_seccion_departamento($as_codsec);
		if($lb_existe)
	    {
		//	$this->io_msg->message("La seccion tiene departamentos asociados");		
		$lb_valido=false;
	     }
		else
		{
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM srh_seccion".
						 " WHERE codsec= '".$as_codsec. "'"; 
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->seccion MÉTODO->uf_srh_delete_seccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la seccion ".$as_codsec;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		return array($lb_valido,$lb_existe);
	} // end function uf_srh_delete_seccion
	
	function uf_srh_select_seccion_departamento($as_codsec)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_factorseccion
		//         Access: private
		//      Argumento: $as_codsec  // codigo de la seccion
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existen seccions asociadas a un departamento
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:05/09/2007							Fecha Última Modificación:05/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_seccion  ".
				  " WHERE codsec='".$as_codsec."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->seccion MÉTODO->uf_srh_select_seccion_departamento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end function uf_srh_select_seccionevaluacion
	

function uf_srh_buscar_seccion($as_codsec,$as_densec,$as_coddep)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_departamento
		//         Access: private
		//      Argumento: $as_coddep  // codigo de la departamento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca un departamento  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 04/09/2007							Fecha Última Modificación: 04/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_coddestino="txtcodsec";
		$ls_dendestino="txtdensec";
		$ls_coddestino1="txtcoddep";
		$ls_dendestino1="txtdendep";
		
		$lb_valido=true;
		$ls_sql="SELECT codsec,densec,srh_seccion.coddep, srh_departamento.dendep FROM srh_seccion, srh_departamento ".
				 "WHERE srh_seccion.coddep= srh_departamento.coddep ".
                 "AND  codsec like '".$as_codsec."' ".
                 "AND densec like '".$as_densec."' ".
                 "AND srh_seccion.coddep like '".$as_coddep."' ";
                 "ORDER BY codsec ";
				 
	
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->seccion MÉTODO->uf_srh_buscar_seccion2( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		     $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
					
					$ls_codsec=$row["codsec"];
					$ls_densec=htmlentities   ($row["densec"]);
					$ls_coddep=$row["coddep"];
					$ls_dendep=htmlentities  ($row["dendep"]);
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codsec']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codsec']." ^javascript:aceptar(\"$ls_codsec\",\"$ls_densec\",\"$ls_coddep\",\"$ls_dendep\",\"$ls_coddestino\",\"$ls_dendestino\",\"$ls_coddestino1\",\"$ls_dendestino1\");^_self"));
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_densec));
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_dendep));												
					$row_->appendChild($cell);
					
					
			
			}
			return $dom->saveXML();

		}
  
	} // end function uf_srh_buscar_seccion2(
	
	

}// end   class sigesp_srh_c_seccion
?>
