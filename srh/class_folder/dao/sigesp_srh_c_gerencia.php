<?php

class sigesp_srh_c_gerencia
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;
	var $codger = null;	
	var $denger = null;
	var $codemp = null;

	function sigesp_srh_c_gerencia($path)
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
	
	

	
	function uf_srh_select_gerencia($as_codger)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_gerencia
		//         Access: public (sigesp_srh_d_gerencia)
		//      Argumento: $as_codger    // codigo del gerencia
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un gerencia en la tabla de  srh_gerencia
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 03/03/2009							Fecha Última Modificación: 03/03/2009
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_gerencia  ".
				  " WHERE codger='".trim($as_codger)."'".
				  " AND codemp='".$this->ls_codemp."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->gerencia MÉTODO->uf_srh_select_gerencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_gerencia


	function  uf_srh_insert_gerencia($as_codger,$as_denger,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_gerencia
		//         Access: public (sigesp_srh_d_gerencia)
		//      Argumento: $as_codger       // codigo de gerencia
	    //                 $as_denger      // denominacion de gerencia	
	    //                 $aa_seguridad    // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un gerencia en la tabla de srh_gerencia
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 03/03/2009							Fecha Última Modificación: 03/03/2009
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO srh_gerencia (codger, denger,codemp) ".
					" VALUES('".$as_codger."','".$as_denger."', '".$this->ls_codemp."')" ;
				
			
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->gerencia MÉTODO->uf_srh_insert_gerencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó gerencia ".$as_codger;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_gerencia

	function uf_srh_update_gerencia($as_codger,$as_denger,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_gerencia
		//         Access: public (sigesp_srh_d_gerencia)
		//      Argumento: $as_codger   // codigo de gerencia 
	    //                 $as_denger   // denominacion de gerencia
		//                 $as_coduniadm   // código de la unidad administrativa
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un gerencia  en la tabla de srh_gerencia
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 03/03/2009							Fecha Última Modificación: 03/03/2009
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 
		 
		 
		 $ls_sql = "UPDATE srh_gerencia SET   denger='". $as_denger."' ".		          
				   " WHERE codger='" . $as_codger ."'".
				   " AND codemp='".$this->ls_codemp."'";
        
		$this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->gerencia MÉTODO->uf_srh_update_gerencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó gerencia ".$as_codger;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_gerencia
	
	
 function uf_select_departamento_gerencia ($as_codger)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:uf_select_departamento_gerencia
		//		   Access: private
 		//	    Arguments: as_codger // código del gerencia 
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el gerencia esta asociada a una seccion
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codger ".
				 "  FROM srh_departamento ".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND codger = '".$as_codger."' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->gerencia  MÉTODO->uf_select_departamento_gerencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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

function uf_srh_delete_gerencia($as_codger,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_gerencia
		//         Access: public (sigesp_srh_d_gerencia)
		//      Argumento: $as_codger  // codigo de gerencia
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un causa de llamada de atencion  en la tabla de srh_gerencia 
		//                
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 03/03/2009							Fecha Última Modificación: 03/03/2009
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe = $this->uf_select_departamento_gerencia ($as_codger);
		
		if($lb_existe)
		{
			$lb_valido=false;
			
		}
		else
		{
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM srh_gerencia".
						 " WHERE codemp='".$this->ls_codemp."' ".
						 " AND codger= '".$as_codger."'"; 
				 
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->gerencia MÉTODO->uf_srh_delete_gerencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó gerencia ".$as_codger;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		return array($lb_valido,$lb_existe);
	} // end function uf_srh_delete_gerencia
	
	
	
	function uf_srh_buscar_gerencia($as_codger,$as_denger)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_gerencia
		//         Access: private
		//      Argumento: $as_codger  // codigo de la gerencia
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca un gerencia  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 04/09/2007							Fecha Última Modificación: 04/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_coddestino="txtcodger";
		$ls_dendestino="txtdenger";
				
		$lb_valido=true;
		$ls_sql="SELECT srh_gerencia.* FROM srh_gerencia ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codger like '".$as_codger."' ".
				"   AND denger like '".$as_denger."' ".
			    " ORDER BY codger"; 
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->gerencia MÉTODO->uf_srh_buscar_gerencia( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		     $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
					 
						$ls_codger=$row["codger"];
						$ls_denger=htmlentities($row["denger"]);			
						
						$row_ = $team->appendChild($dom->createElement('row'));
						$row_->setAttribute("id",$row['codger']);
						$cell = $row_->appendChild($dom->createElement('cell')); 					
						$cell->appendChild($dom->createTextNode($row['codger']." ^javascript:aceptar(\"$ls_codger\",\"$ls_denger\",\"$ls_coddestino\",\"$ls_dendestino\");^_self"));
						
						$cell = $row_->appendChild($dom->createElement('cell'));
						$cell->appendChild($dom->createTextNode($ls_denger));												
						$row_->appendChild($cell);				
			}
			return $dom->saveXML();
		
			
			
	
		}
    } // end function uf_srh_buscar_gerencia(
	

}// end   class sigesp_srh_c_gerencia
?>
