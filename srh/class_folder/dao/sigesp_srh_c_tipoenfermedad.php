<?php

class sigesp_srh_c_tipoenfermedad
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_tipoenfermedad($path)
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
		//         Access: public (sigesp_srh_d_tipoenfermedad)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de un tipo de enfermedad
		//    Description: Funcion que genera un código un tipo de enfermedad
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(codenf) AS codigo FROM srh_tipoenfermedad  ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_codenf = $la_datos["codigo"][0]+1;
    $ls_codenf = str_pad ($ls_codenf,15,"0","left");
	return $ls_codenf;
  }

	
	function uf_srh_select_tipoenfermedad($as_codenf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_tipoenfermedad
		//         Access: public (sigesp_srh_d_tipoenfermedad)
		//      Argumento: $as_codenf    // codigo de tipo de enfermedad de Evaluacion
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un tipo enfermedad de Evaluacion en la tabla de  srh_tipoenfermedad
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 06/09/2007							Fecha Última Modificación: 06/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_tipoenfermedad  ".
				  " WHERE codenf='".trim($as_codenf)."'".
				  " AND codemp='".$this->ls_codemp."'" ;

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tipoenfermedad MÉTODO->uf_srh_select_tipoenfermedad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_tipoenfermedad


	function  uf_srh_insert_tipoenfermedad($as_codenf,$as_denenf,$as_riecon,$as_rielet,$as_obsenf,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_tipoenfermedad
		//         Access: public (sigesp_srh_d_tipoenfermedad)
		//      Argumento: $as_codenf       // codigo de tipo de enfermedad 
	    //                 $as_denenf      // denominacion de tipo de enfermedad 
	    //                 $as_riecon      //  riesgo de contagio de la enfermedad
	    //                 $as_rielet      // riesgo letal de la enfermedad
	    //                 $as_obsenf     // observacion de la enfermedad
	    //                 $aa_seguridad    // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un tipo de enfermedad en la tabla de srh_tipoenfermedad
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 06/09/2007							Fecha Última Modificación: 06/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO srh_tipoenfermedad (codenf, denenf,riecon,rielet,obsenf,codemp) ".
					" VALUES('".$as_codenf."','".$as_denenf."','".$as_riecon."', '".$as_rielet."','".$as_obsenf."','".$this->ls_codemp."')" ;
			
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->tipoenfermedad MÉTODO->uf_srh_insert_tipoenfermedad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el tipo enfermedad ".$as_codenf;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_tipoenfermedad

	function uf_srh_update_tipoenfermedad($as_codenf,$as_denenf,$as_riecon,$as_rielet,$as_obsenf,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_tipoenfermedad
		//         Access: public (sigesp_srh_d_tipoenfermedad)
		//      Argumento: $as_codenf   // codigo de tipoenfermedad de Evaluacion
	    //                 $as_denenf   // denominacion de tipoenfermedad de Evaluacion
	    //                  $as_riecon      //  riesgo de contagio de la enfermedad
	    //                 $as_rielet      // riesgo letal de la enfermedad
	    //                 $as_obsenf     // observacion de la enfermedad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un tipo de enfermedad en la tabla de srh_tipoenfermedad
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 06/09/2007							Fecha Última Modificación: 06/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE srh_tipoenfermedad SET   denenf='". $as_denenf."', riecon='". $as_riecon."', rielet='". $as_rielet."',
		 obsenf='". $as_obsenf."'". 
				   " WHERE codenf='" . $as_codenf ."'".
				   " AND codemp='".$this->ls_codemp."'";
        
		$this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->tipoenfermedad MÉTODO->uf_srh_update_tipoenfermedad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el tipo enfermedad ".$as_codenf;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_tipoenfermedad

	function uf_srh_delete_tipoenfermedad($as_codenf,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_tipoenfermedad
		//         Access: public (sigesp_srh_d_tipoenfermedad)
		//      Argumento: $as_codenf   // codigo de tipo enfermedad 
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un tipo enfermedad en la tabla de srh_tipoenfermedad verificando que este no este 
		//                 siendo utilizado por ninguna Enfermedad.
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 06/09/2007							Fecha Última Modificación: 06/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe= $this->uf_srh_select_tipo_enfermedad($as_codenf);
		if($lb_existe)
		{
					
			$lb_valido=false;
		}
		else
		{
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM srh_tipoenfermedad".
						 " WHERE codenf= '".$as_codenf. "'"; 
				 
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->tipoenfermedad MÉTODO->uf_srh_delete_tipoenfermedad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el tipoenfermedad ".$as_codenf;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		return array($lb_valido,$lb_existe);
	} // end function uf_srh_delete_tipoenfermedad
	
	
	
	
 function uf_srh_select_tipo_enfermedad($as_codenf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_tipo_enfermedad
		//         Access: private
		//      Argumento: $as_codenf   // codigo de tipo de enfermedad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existen enfermedades asociadas a un tipo enfermedad
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT codenf FROM srh_enfermedades  ".
				  " WHERE codenf ='".$as_codenf."'" ;
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tipodoenfermedad MÉTODO->uf_srh_select_tipo_enfermedad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
			
function uf_srh_buscar_tipoenfermedad($as_codenf,$as_denenf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_tipoenfermedad
		//         Access: private
		//      Argumento: $as_codenf  // codigo del tipo de enfermedad
  		//				   $as_denenf  // denominación del tipo de enfermedad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca un tipo de enfermedad  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 06/09/2007							Fecha Última Modificación: 06/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_coddestino="txtcodenf";
		$ls_dendestino="txtdenenf";
		$ls_riecondestino="comboriecon";
		$ls_rieletdestino="comborielet";
		$ls_obsenfdestino="txtobsenf";
		
		
		$lb_valido=true;
		$ls_sql=$ls_sql="SELECT * FROM srh_tipoenfermedad".
				" WHERE codenf like '".$as_codenf."' ".
				"   AND denenf like '".$as_denenf."' ".
				" ORDER BY codenf";
				
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tipoenfermedad MÉTODO->uf_srh_buscar_tipoenfermedad( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{			
		     $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while($row=$this->io_sql->fetch_row($rs_data))
			{
			
			
				$ls_codenf=$row["codenf"];
				$ls_denenf=htmlentities  ($row["denenf"]);
                $ls_riecon=htmlentities  (trim ($row["riecon"]));
				$ls_rielet=htmlentities  (trim($row["rielet"]));
				$ls_obsenf=htmlentities  ($row["obsenf"]);		
				
				
						
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codenf']);
					$cell = $row_->appendChild($dom->createElement('cell'));
					  
					$cell->appendChild($dom->createTextNode($row['codenf']." ^javascript:aceptar(\"$ls_codenf\",\"$ls_denenf\",\"$ls_riecon\",\"$ls_rielet\",\"$ls_obsenf\",\"$ls_coddestino\",\"$ls_dendestino\",\"$ls_riecondestino\",\"$ls_rieletdestino\",\"$ls_obsenfdestino\");^_self"));
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_denenf));												
					$row_->appendChild($cell);
					
			
			}
			return $dom->saveXML();
		
			
			
		
		}
      
	} // end function uf_srh_buscar_tipoenfermedad(
	

}// end   class sigesp_srh_c_tipoenfermedad
?>
