<?php

class sigesp_srh_c_nivelseleccion
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_nivelseleccion($path)
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
	
	 function getnivelseleccion ($ps_orden="",&$pa_datos="")
  {
    $lb_valido=true;
    $ls_sql = "SELECT * FROM srh_nivelseleccion 
	             ".$ps_orden;
	
	$lb_valido=$this->io_sql->seleccionar($ls_sql, $pa_datos);
   
    return $lb_valido;

  }
	
	
	
	
function uf_srh_getProximoCodigo()
  {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getProximoCodigo
		//         Access: public (sigesp_srh_d_nivelseleccion)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de un nivel de selección
		//    Description: Funcion que genera un código de un nivel de selección
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(codniv) AS codigo FROM srh_nivelseleccion  ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_codniv = $la_datos["codigo"][0]+1;
    $ls_codniv = str_pad ($ls_codniv,15,"0","left");
	return $ls_codniv;
  }

	
	
	function uf_srh_select_nivelseleccion($as_codniv)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_nivelseleccion
		//         Access: public (sigesp_siv_d_tipoarticulo)
		//      Argumento: $as_codniv    // codigo de nivel de selección
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un nivel de selección en la tabla de  siv_tipoarticulo
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_nivelseleccion  ".
				  " WHERE codniv='".trim($as_codniv)."'".
				  " AND codemp='".$this->ls_codemp."'" ;	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->nivelseleccion MÉTODO->uf_srh_select_nivelseleccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_nivelseleccion

	function  uf_srh_insert_nivelseleccion($as_codniv,$as_denniv,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_nivelseleccion
		//         Access: public (sigesp_siv_d_tipoarticulo)
		//      Argumento: $as_codniv// codigo de nivel de selección
	    //                 $as_dentipart   // denominacion de nivel de selección
	    //                 $as_obstipart   // observacion de nivel de selección
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un nivel de selección en la tabla de siv_tipoarticulo
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO srh_nivelseleccion (codniv, denniv,codemp) ".
				" VALUES('".$as_codniv."','".$as_denniv."','".$this->ls_codemp."')" ;
		
		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->nivelseleccion MÉTODO->uf_srh_insert_nivelseleccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Nivel de Seleccion ".$as_codniv;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_nivelseleccion

	function uf_srh_update_nivelseleccion($as_codniv,$as_denniv,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_nivelseleccion
		//         Access: public (sigesp_siv_d_tipoarticulo)
		//      Argumento: $as_codniv// codigo de nivel de selección
	    //                 $as_dentipart   // denominacion de nivel de selección
	    //                 $as_obstipart   // observacion de nivel de selección
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un nivel de selección en la tabla de siv_tipoarticulo
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE srh_nivelseleccion SET   denniv='". $as_denniv ."'". 
				   " WHERE codniv='" . $as_codniv ."'".
				   " AND codemp='".$this->ls_codemp."'";	    
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->nivelseleccion MÉTODO->uf_srh_update_nivelseleccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el Nivel de Seleccion".$as_codniv;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_nivelseleccion

	function uf_srh_delete_nivelseleccion($as_codniv,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_nivelseleccion
		//         Access: public (sigesp_siv_d_tipoarticulo)
		//      Argumento: $as_codniv// codigo de nivel de selección
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un nivel de seleccion verificando que este no este siendo
		//				   utilizado por una solicitud de empleo
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe= $this->uf_srh_select_nivel_solicitud($as_codniv);
		if($lb_existe)
		{
					
			$lb_valido=false;
			
		}
		else
		{
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM srh_nivelseleccion".
						 " WHERE codniv= '".$as_codniv. "'".
						 "AND codemp='".$this->ls_codemp."'"; 
			$li_row=$this->io_sql->execute($ls_sql);
			
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->nivelseleccion MÉTODO->uf_srh_delete_nivelseleccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Nivel de Seleccion ".$as_codniv;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		return array($lb_valido,$lb_existe);
	} // end function uf_srh_delete_nivelseleccion
	
	function uf_srh_select_nivel_solicitud($as_codniv)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_nivel_solicitud
		//         Access: private
		//      Argumento: $as_codniv// codigo de nivel de selección
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existen categorias asociadas a un nivelseleccion
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 01/02/2006							Fecha Última Modificación: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT codniv FROM srh_solicitud_empleo  ".
				  " WHERE codniv='".$as_codniv."'" ;
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->nivelseleccion MÉTODO->uf_srh_select_nivel_solicitud ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end function uf_srh_select_nivel_solicitud
	
	

	
	
	function uf_srh_buscar_nivelseleccion($as_codniv,$as_denniv)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_nivelseleccion
		//         Access: private
		//      Argumento: $as_codniv  // codigo de la nivelseleccion
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca un nivelseleccion  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 04/09/2007							Fecha Última Modificación: 04/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_coddestino="txtcodniv";
		$ls_dendestino="txtdenniv";
	
		
		$lb_valido=true;
		$ls_sql="SELECT * FROM srh_nivelseleccion".
				" WHERE codniv like '".$as_codniv."' ".
				"   AND denniv like '".$as_denniv."' ".
			   " ORDER BY codniv";
	
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->nivelseleccion MÉTODO->uf_srh_buscar_nivelseleccion( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
					$ls_codniv=$row["codniv"];
					$ls_denniv=htmlentities  ($row["denniv"]);
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codniv']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codniv']." ^javascript:aceptar(\"$ls_codniv\",\"$ls_denniv\",\"$ls_coddestino\",\"$ls_dendestino\");^_self"));
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_denniv));												
					$row_->appendChild($cell);
					
					
			
			}
			return $dom->saveXML();
		
			
			
		}
        
	} // end function uf_srh_buscar_nivelseleccion(
	

}// end   class sigesp_srh_c_nivelseleccion
?>