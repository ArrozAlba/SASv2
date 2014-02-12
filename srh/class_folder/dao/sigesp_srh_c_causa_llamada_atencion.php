<?php

class sigesp_srh_c_causa_llamada_atencion
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_causa_llamada_atencion($path)
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
		//         Access: public (sigesp_srh_d_causa_llamada_atencion)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de una causa llamada de atención
		//    Description: Funcion que genera un código un código de una causa de llamada de atención
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(codcaullam_aten) AS codigo FROM srh_causa_llamada_atencion ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_codcaullam_aten = $la_datos["codigo"][0]+1;
    $ls_codcaullam_aten = str_pad ($ls_codcaullam_aten,15,"0","left");
    return $ls_codcaullam_aten;
  }
	
  function uf_srh_select_causa_llamada_atencion($as_codcaullam_aten)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_causa_llamada_atencion
		//         Access: public (sigesp_srh_d_causa_llamada_atencion)
		//      Argumento: $as_codcaullam_aten    // codigo de causa de llamada de atencion 
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de una causa llamada atencion en la tabla de  
		//                 srh_causa_llamada_atencion
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 05/09/2007							Fecha Última Modificación: 05/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_causa_llamada_atencion  ".
				  " WHERE codcaullam_aten='".trim($as_codcaullam_aten)."'".
				  " AND codemp='".$this->ls_codemp."'" ;

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->causa_llamada_atencion MÉTODO->uf_srh_select_causa_llamada_atencion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_causa_llamada_atencion


	function  uf_srh_insert_causa_llamada_atencion($as_codcaullam_aten,$as_dencaullam_aten,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_causa_llamada_atencion
		//         Access: public (sigesp_srh_d_causa_llamada_atencion)
		//      Argumento: $as_codcaullam_aten       // codigo de causa de llamada de atencion
	    //                 $as_dencaullam_aten      // denominacion de causa de llamada de atencion 
	    //                 $aa_seguridad    // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un causa de llamada  de atencion en la tabla de srh_causa_llamada_atencion
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 29/08/2007							Fecha Última Modificación: 29/08/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO srh_causa_llamada_atencion (codcaullam_aten, dencaullam_aten,codemp) ".
					" VALUES('".$as_codcaullam_aten."','".$as_dencaullam_aten."','".$this->ls_codemp."')" ;
			
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->causa_llamada_atencion MÉTODO->uf_srh_insert_causa_llamada_atencion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la causa_llamada_atencion ".$as_codcaullam_aten;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_causa_llamada_atencion

	function uf_srh_update_causa_llamada_atencion($as_codcaullam_aten,$as_dencaullam_aten,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_causa_llamada_atencion
		//         Access: public (sigesp_srh_d_causa_llamada_atencion)
		//      Argumento: $as_codcaullam_aten   // codigo de causa_llamada_atencion
	    //                 $as_dencaullam_aten   // denominacion de causa_llamada_atencion 
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un causa de llamada de atención en la tabla de srh_causa_llamada_atencion
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 29/08/2007							Fecha Última Modificación: 29/08/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE srh_causa_llamada_atencion SET   dencaullam_aten='". $as_dencaullam_aten."'". 
				   " WHERE codcaullam_aten='" . $as_codcaullam_aten ."'".
				   " AND codemp='".$this->ls_codemp."'";
        
		$this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->causa_llamada_atencion MÉTODO->uf_srh_update_causa_llamada_atencion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó la causa_llamada_atencion ".$as_codcaullam_aten;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_causa_llamada_atencion
	
	
function uf_select_causa_llamada ($as_codcau)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_causa_llamada
		//		   Access: private
 		//	    Arguments: as_codcau  // código de la causa de llamada de atención
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la causa de llamda de atencion esta asociada a una llamda de atencion
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 25/04/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codcaullam_aten ".
				 "  FROM srh_dt_llamada_atencion".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND codcaullam_aten='".$as_codcau."' ";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->causa_llamada_atencion ->uf_select_causa_llamada  ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	
	

	function uf_srh_delete_causa_llamada_atencion($as_codcaullam_aten,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_causa_llamada_atencion
		//         Access: public (sigesp_srh_d_causa_llamada_atencion)
		//      Argumento: $as_codcaullam_aten  // codigo de causa de llamada de atencion 
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un causa de llamada de atencion  en la tabla de srh_causa_llamada_atencion 
		//                 verificando que este no este siendo utilizado por ninguna Llamada de Atencion.
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 29/08/2007							Fecha Última Modificación: 29/08/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe= $this->uf_select_causa_llamada($as_codcaullam_aten);
		if($lb_existe)
		{
			$lb_valido=false;
		}
		else
		{   $lb_existe=false;
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM srh_causa_llamada_atencion".
						 " WHERE codcaullam_aten= '".$as_codcaullam_aten. "'"; 
				 
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->causa_llamada_atencion MÉTODO->uf_srh_delete_causa_llamada_atencion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la causa_llamada_atencion ".$as_codcaullam_aten;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		return array($lb_valido,$lb_existe);
	} // end function uf_srh_delete_causa_llamada_atencion
	

	
function uf_srh_buscar_causa_llamada_atencion($as_codcaullam_aten,$as_dencaullam_aten)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_causa_llamada_atencion
		//         Access: private
		//      Argumento: $as_codcaullam_aten  // codigo de la causa_llamada_atencion
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca una causa de llamada de atencion  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 04/09/2007							Fecha Última Modificación: 04/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_coddestino="txtcodcaullam_aten";
		$ls_dendestino="txtdencaullam_aten";
	
		
		$lb_valido=true;
		$ls_sql=$ls_sql="SELECT * FROM srh_causa_llamada_atencion".
				" WHERE codcaullam_aten like '".$as_codcaullam_aten."' ".
				"   AND dencaullam_aten like '".$as_dencaullam_aten."' ".
			   " ORDER BY codcaullam_aten";
				
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->causa_llamada_atencion MÉTODO->uf_srh_buscar_causa_llamada_atencion( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		
		{
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
	
						while($row=$this->io_sql->fetch_row($rs_data))
						{
								$ls_codcaullam_aten=$row["codcaullam_aten"];
								$ls_dencaullam_aten=htmlentities  ($row["dencaullam_aten"]);
								
								$row_ = $team->appendChild($dom->createElement('row'));
								$row_->setAttribute("id",$row['codcaullam_aten']);
								
								$cell = $row_->appendChild($dom->createElement('cell'));   
								$cell->appendChild($dom->createTextNode($row['codcaullam_aten']." ^javascript:aceptar(\"$ls_codcaullam_aten\",\"$ls_dencaullam_aten\",\"$ls_coddestino\",\"$ls_dendestino\");^_self"));
								
								$cell = $row_->appendChild($dom->createElement('cell'));
								$cell->appendChild($dom->createTextNode($ls_dencaullam_aten));	
								
								
								$row_->appendChild($cell);
					
					
			
			}
			return $dom->saveXML();
		
		}
		
	} // end function uf_srh_buscar_causa_llamada_atencion
	

}// end   class sigesp_srh_c_causa_llamada_atencion
?>