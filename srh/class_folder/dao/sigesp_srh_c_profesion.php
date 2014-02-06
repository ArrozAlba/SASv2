<?php

class sigesp_srh_c_profesion
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_profesion($path)
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
		//         Access: public (sigesp_srh_d_profesion)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de una profesión 
		//    Description: Funcion que genera un código de una profesión
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(codpro) AS codigo FROM sno_profesion  ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_codpro = $la_datos["codigo"][0]+1;
    $ls_codpro = str_pad ($ls_codpro,3,"0","left");
 
	 
    return $ls_codpro;
  }

	
	function uf_srh_select_profesion($as_codpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_profesion
		//         areaess: public 
		//      Argumento: $as_codpro    // codigo de profesion
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de una profesion en la tabla de  sno_profesion
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 06/11/2007							Fecha Última Modificación: 06/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sno_profesion  ".
				  " WHERE codpro='".trim($as_codpro)."'".
				  " AND codemp='".$this->ls_codemp."'" ;
	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->profesion MÉTODO->uf_srh_select_profesion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_profesion

	function  uf_srh_insert_profesion($as_codpro,$as_despro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_profesion
		//         areaess: public (sigesp_siv_d_tipoarticulo)
		//      Argumento: $as_codpro   // codigo de profesion
	    //                 $as_despro// denominacion de profesion
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta una profesion en la tabla de sno_profesion
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 06/11/2007							Fecha Última Modificación: 06/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO sno_profesion (codpro, despro,codemp) ".
					" VALUES('".$as_codpro."','".$as_despro."','".$this->ls_codemp."')" ;
		
		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->profesion MÉTODO->uf_srh_insert_profesion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la  profesion ".$as_codpro;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_profesion

	function uf_srh_update_profesion($as_codpro,$as_despro,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_profesion
		//         areaess: public (sigesp_siv_d_tipoarticulo)
		//      Argumento: $as_codpro   // codigo de profesion
	    //                 $as_despro   // denominacion de profesion
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica una profesion en la tabla de sno_profesion
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 06/11/2007							Fecha Última Modificación: 06/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE  sno_profesion SET   despro='". $as_despro."'". 
				   " WHERE codpro='" . $as_codpro ."'".
				   " AND codemp='".$this->ls_codemp."'";
		
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->profesion MÉTODO->uf_srh_update_profesion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó la profesion ".$as_codpro;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_profesion

	function uf_srh_delete_profesion($as_codpro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_profesion
		//         areaess: public 
		//      Argumento: $as_codpro   // codigo de profesion
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina una profesion en la tabla de sno_profesion verificando que este no este siendo
		//				   utilizado por ninguna personal.
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 06/11/2007							Fecha Última Modificación: 06/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=false;
	     $lb_existe=true;
		
		if (($this->uf_srh_select_profesion_solicitud ($as_codpro)===false)&&
		     ($this->uf_srh_select_profesion_personal ($as_codpro)===false))
		 {
		    $lb_existe=false;
		
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM sno_profesion".
						 " WHERE codpro= '".$as_codpro. "'".
						 "AND codemp='".$this->ls_codemp."'"; 
		
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->profesion MÉTODO->uf_srh_delete_profesion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la profesion ".$as_codpro;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		return array($lb_valido,$lb_existe);
	} // end function uf_srh_delete_profesion
	
	function uf_srh_select_profesion_solicitud ($as_codpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_profesion_solicitud
		//         areaess: private
		//      Argumento: $as_codpro   // codigo de profesion
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existen soliciutdes de empleo asociadas a un profesion
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 25/04/2008							Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_solicitud_empleo  ".
				  " WHERE codpro='".$as_codpro."'" ;
		
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->profesion MÉTODO->uf_srh_select_profesion_solicitud ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end function uf_srh_select_profesion_solicitud
	
	
	function uf_srh_select_profesion_personal($as_codpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_profesion_personal
		//         areaess: private
		//      Argumento: $as_codpro   // codigo de profesion
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existen personas de empleo asociadas a un profesion
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 25/04/2008							Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM  sno_personal  ".
				  " WHERE codpro='".$as_codpro."'" ;
		
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->profesion MÉTODO->uf_srh_select_profesion_personal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end function uf_srh_select_profesion_personal
	
	
	
	function uf_srh_buscar_profesion($as_codpro,$as_despro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_profesion
		//         Access: private
		//      Argumento: $as_codpro  // codigo de la profesion
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca un profesion  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 06/11/2007							Fecha Última Modificación: 06/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_coddestino="txtcodpro";
		$ls_dendestino="txtdespro";
	
		
		$lb_valido=true;
		$ls_sql="SELECT * FROM sno_profesion".
				" WHERE codpro like '".$as_codpro."' ".
				"   AND despro like '".$as_despro."' ".
			   " ORDER BY codpro";
	 
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->profesion MÉTODO->uf_srh_buscar_profesion( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
			$dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			 	
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     	$ls_codpro=$row["codpro"];
					$ls_despro= htmlentities  ($row["despro"]);
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codpro']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codpro']." ^javascript:aceptar(\"$ls_codpro\",\"$ls_despro\",\"$ls_coddestino\",\"$ls_dendestino\");^_self"));
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_despro));												
					$row_->appendChild($cell);
					
					
			
			}
			return $dom->saveXML();
		
		
		}
      
		
	} // end function uf_srh_buscar_profesion
	

}// end   class sigesp_srh_c_profesion
?>