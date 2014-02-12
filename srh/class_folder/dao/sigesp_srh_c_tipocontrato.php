<?php

class sigesp_srh_c_tipocontrato
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;
	var $codtipcon = null;	
	var $dentipcon = null;
	var $codemp = null;

	function sigesp_srh_c_tipocontrato($path)
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
		//         Access: public (sigesp_srh_d_tipocontratos)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de un tipo de personal
		//    Description: Funcion que genera un código un tipo de personal
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(codtipcon) AS codigo FROM srh_tipocontratos  ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_codtipcon = $la_datos["codigo"][0]+1;
    $ls_codtipcon = str_pad ($ls_codtipcon,15,"0","left");
	return $ls_codtipcon;
  }

	function uf_srh_select_tipocontrato($as_codtipcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_tipocontratos
		//         Access: public (sigesp_srh_d_tipocontrato)
		//      Argumento: $as_codtipcon    // codigo de tipo contrato
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un tipo contrato en la tabla de  srh_tipocontratos
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_tipocontratos  ".
				  " WHERE codtipcon='".trim($as_codtipcon)."'".
				  " AND codemp='".$this->ls_codemp."'" ;
	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tipocontratos MÉTODO->uf_srh_select_tipocontrato ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_tipocontratos

	function  uf_srh_insert_tipocontrato($as_codtipcon,$as_dentipcon,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_tipocontratos
		//         Access: public (sigesp_srh_d_tipocontrato)
		//      Argumento: $as_codtipcon   // codigo de tipo contrato
	    //                 $as_dentipart   // denominacion de tipo contrato
	    //                 $as_obstipart   // observacion de tipo contrato
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un tipo contrato en la tabla de srh_tipocontratos
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO srh_tipocontratos (codtipcon, dentipcon,codemp) ".
				" VALUES('".$as_codtipcon."','".$as_dentipcon."','".$this->ls_codemp."')" ;
		
		//print_r($ls_sql);
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->tipocontratos MÉTODO->uf_srh_insert_tipocontratos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Tipo de Contrato ".$as_codtipcon;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_tipocontratos

	function uf_srh_update_tipocontrato($as_codtipcon,$as_dentipcon,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_tipocontratos
		//         Access: public (sigesp_srh_d_tipocontrato)
		//      Argumento: $as_codtipcon   // codigo de tipo contrato
	    //                 $as_dentipart   // denominacion de tipo contrato
	    //                 $as_obstipart   // observacion de tipo contrato
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un tipo contrato en la tabla de srh_tipocontratos
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE srh_tipocontratos SET   dentipcon='". $as_dentipcon ."'". 
				   " WHERE codtipcon='" . $as_codtipcon ."'".
				   " AND codemp='".$this->ls_codemp."'";

        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->tipocontratos MÉTODO->uf_srh_update_tipocontratos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el Tipo de Contrato".$as_codtipcon;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_tipocontratos
	

  function uf_srh_delete_tipocontrato($as_codtipcon,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_tipocontratos
		//         Access: public (sigesp_srh_d_tipocontrato)
		//      Argumento: $as_codtipcon   // codigo de tipo contrato
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un tipo contrato en la tabla de srh_tipocontratos
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe= $this->uf_srh_select_tipo_contrato($as_codtipcon);
		if($lb_existe)
		{
			
			$lb_valido=false;
			
		}
		else
		{
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM srh_tipocontratos".
						 " WHERE codtipcon= '".$as_codtipcon. "'".
						 "AND codemp='".$this->ls_codemp."'"; 
		
			$li_row=$this->io_sql->execute($ls_sql);
			
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->tipocontratos MÉTODO->uf_srh_delete_tipocontratos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Tipo de Contrato ".$as_codtipcon;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		return array($lb_valido,$lb_existe);
	} // end function uf_srh_delete_tipocontratos
	
	function uf_srh_select_tipo_contrato($as_codtipcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_tipo_contrato
		//         Access: private
		//      Argumento: $as_codtipcon   // codigo de tipo contrato
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existen contratos asociadas a un tipocontratos
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT codtipcon FROM srh_contratos  ".
				  " WHERE codtipcon='".$as_codtipcon."'" ;
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tipocontratos MÉTODO->uf_srh_select_tipo_contrato ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end function uf_srh_select_tipocontratoscategoria
	

function uf_srh_buscar_tipocontrato($as_codtipcon,$as_dentipcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_tipocontrato
		//         Access: private
		//      Argumento: $as_codtipcon  // codigo de la tipocontrato
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca un tipocontrato  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 04/09/2007							Fecha Última Modificación: 04/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_coddestino="txtcodtipcon";
		$ls_dendestino="txtdentipcon";
	
		
		$lb_valido=true;
		$ls_sql="SELECT * FROM srh_tipocontratos".
				" WHERE codtipcon like '".$as_codtipcon."' ".
				"   AND dentipcon like '".$as_dentipcon."' ".
			   " ORDER BY codtipcon";
	
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tipocontrato MÉTODO->uf_srh_buscar_tipocontrato( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
					$ls_codtipcon=$row["codtipcon"];
					$ls_dentipcon=htmlentities ($row["dentipcon"]);
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codtipcon']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codtipcon']." ^javascript:aceptar(\"$ls_codtipcon\",\"$ls_dentipcon\",\"$ls_coddestino\",\"$ls_dendestino\");^_self"));
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_dentipcon));												
					$row_->appendChild($cell);
					
					
			
			}
			return $dom->saveXML();
		
			
			}
        
	} // end function uf_srh_buscar_tipocontrato(
	

}// end   class sigesp_srh_c_tipocontrato

?>