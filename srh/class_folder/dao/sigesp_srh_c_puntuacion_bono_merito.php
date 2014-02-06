<?php

class sigesp_srh_c_puntuacion_bono_merito
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_puntuacion_bono_merito($path)
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
		//         Access: public (sigesp_srh_d_puntuacion_bono_merito)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de una puntuación bono mérito
		//    Description: Funcion que genera un código de una puntuación bono mérito
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(codpunt) AS codigo FROM srh_puntuacion_bono_merito  ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_codpunt = $la_datos["codigo"][0]+1;
    $ls_codpunt = str_pad ($ls_codpunt,15,"0","left");
	return $ls_codpunt;
  }
	
	function uf_srh_select_puntuacion_bono_merito($as_codpunt)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_puntuacion_bono_merito
		//         Access: public (sigesp_srh_d_puntuacion_bono_merito)
		//      Argumento: $as_codpunt    // codigo de la puntuacion
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de una puntuacion en la tabla de  srh_puntuacion_bono_merito
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:30/08/2007							Fecha Última Modificación: 30/08/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_puntuacion_bono_merito  ".
				  " WHERE codpunt='".trim($as_codpunt)."'";
				  " AND codemp='".$this->ls_codemp."'" ;
	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->puntuacion_bono_merito MÉTODO->uf_srh_select_puntuacion_bono_merito ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_puntuacion_bono_merito


	function  uf_srh_insert_puntuacion_bono_merito($as_codpunt,$as_nompunt,$as_despunt,$ai_valini,$ai_valfin,$as_codtipper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_puntuacion_bono_merito
		//         Access: public (sigesp_srh_d_puntuacion_bono_merito)
		//      Argumento: $as_codpunt       // codigo de la puntuacion
	    //                 $as_nompunt      // nombre de la puntuacion
	    //                 $as_despunt       // descripcion de la puntuacion
		//				   $ai_valini		//  valini de la puntuacion
		//				   $as_codtipper       // tipo de personal a quien corresponde la puntuacion
	    //                 $aa_seguridad    // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta una puntuacion en la tabla de srh_puntuacion_bono_merito
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 30/08/2007							Fecha Última Modificación: 31/08/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO srh_puntuacion_bono_merito (codpunt, nompunt,despunt,valini,valfin,codtipper,codemp) ".
					" VALUES('".$as_codpunt."','".$as_nompunt."','".$as_despunt."','".$ai_valini."','".$ai_valfin."','".$as_codtipper."','".$this->ls_codemp."')" ;
			  

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->puntuacion_bono_merito MÉTODO->uf_srh_insert_puntuacion_bono_merito ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el puntuacion_bono_merito ".$as_codpunt;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_puntuacion_bono_merito

	function uf_srh_update_puntuacion_bono_merito($as_codpunt,$as_nompunt,$as_despunt,$ai_valini,$ai_valfin,$as_codtipper,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_puntuacion_bono_merito
		//         Access: public (sigesp_srh_d_puntuacion_bono_merito)
		//      Argumento: $as_codpunt       // codigo de la puntuacion
	    //                 $as_nompunt      // nombre de la puntuacion
	    //                 $as_despunt       // descripcion de la puntuacion
		//				   $ai_valini		//  valini de la puntuacion
		//				   $as_codtipper       //  tipo de personal a quien corresponde la puntuacion
	    //                 $aa_seguridad    // arreglo de registro de seguridaduacion
	    //	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica una puntuacion en la tabla de srh_puntuacion_bono_merito
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 30/08/2007							Fecha Última Modificación: 31/08/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE srh_puntuacion_bono_merito SET  nompunt='". $as_nompunt."', despunt='". $as_despunt."', valini='".$ai_valini."',valfin='".$ai_valfin."',codtipper='".$as_codtipper."'".
				   " WHERE codpunt='" . $as_codpunt ."'".
				   " AND codemp='".$this->ls_codemp."'";
        	   
		$this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->puntuacion_bono_merito MÉTODO->uf_srh_update_puntuacion_bono_merito ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el puntuacion_bono_merito ".$as_codpunt;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_puntuacion_bono_merito
	
function uf_select_puntuacion_bono ($as_codpunt)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_puntuacion_bono
		//		   Access: private
 		//	    Arguments: as_codpunt  // código de la puntuación
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la puntuación esta asociada a un bono por mérito
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 25/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codpunt ".
				 "  FROM srh_dt_bono_merito".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND codpunt='".$as_codpunt."' ";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->puntuacion_bono_merito ->uf_select_puntuacion_bono   ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	
		


function uf_srh_delete_puntuacion_bono_merito($as_codpunt,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_puntuacion_bono_merito
		//         Access: public (sigesp_srh_d_puntuacion_bono_merito)
		//      Argumento: $as_codpunt   // codigo de puntuacion_bono_merito de Evaluacion
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina una puntuacion en la tabla de srh_puntuacion_bono_merito verificando que no este 
		//                 siendo utilizado por ningun Bono merito.
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 30/08/2007							Fecha Última Modificación: 30/08/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe= $this->uf_select_puntuacion_bono ($as_codpunt);
		if($lb_existe)
		{
					
			$lb_valido=false;
		}
		else
		{
		    $lb_existe=false;
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM srh_puntuacion_bono_merito".
						 " WHERE codpunt= '".$as_codpunt. "'"; 
				    
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->puntuacion_bono_merito MÉTODO->uf_srh_delete_puntuacion_bono_merito ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el puntuacion_bono_merito ".$as_codpunt;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
 return array($lb_valido,$lb_existe);
	} // end function uf_srh_delete_puntuacion_bono_merito
	

	
function uf_srh_buscar_puntuacion_bono_merito($as_codpunt,$as_nompunt,$as_codtipper)
	{    
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_puntuacion_bono_merito
		//         Access: private
		//      Argumento: $as_codcat  // codigo de la categoria
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca una escala de evalucion desempeño para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 03/09/2007							Fecha Última Modificación: 03/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		 $ls_coddestino="txtcodpunt";
		$ls_nombdestino="txtnombpunt";
		$ls_desdestino="txtdespunt";
		$li_valinidestino="txtvalini";
		$li_valfindestino="txtvalfin";
		$ls_codtipperdestino="txtcodtipper";
		$ls_dentipperdestino="txtdentipper";
		
		$lb_valido=true;
		$ls_sql="SELECT * FROM srh_puntuacion_bono_merito INNER JOIN srh_tipopersonal ON (srh_puntuacion_bono_merito.codtipper = srh_tipopersonal.codtipper)".
				" WHERE codpunt like '".$as_codpunt."' ".
				"   AND nompunt like '".$as_nompunt."' ".
			   	"   AND srh_puntuacion_bono_merito.codtipper like '".$as_codtipper."' ".
			   " ORDER BY codpunt";
			   
	 
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->area MÉTODO->uf_srh_buscar_puntuacion_bono_merito( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
					$ls_codpunt=$row["codpunt"];
					$ls_nompunt=htmlentities   ($row["nompunt"]);
					$ls_despunt=htmlentities  ($row["despunt"]);
   		   			$li_valini=$row["valini"];
					$li_valfin=$row["valfin"];
					$ls_codtipper=$row["codtipper"];
					$ls_dentipper=htmlentities ($row["dentipper"]);
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codpunt']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codpunt']." 					^javascript:aceptar(\"$ls_codpunt\",\"$ls_nompunt\",\"$ls_coddestino\",\"$ls_nombdestino\",\"$ls_despunt\",\"$ls_desdestino\",\"$li_valini\", \"$li_valfin\", \"$li_valinidestino\", \"$li_valfindestino\", \"$ls_codtipper\", \"$ls_dentipper\", \"$ls_codtipperdestino\",  \"$ls_dentipperdestino\");^_self"));
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_nompunt));												
					$row_->appendChild($cell);
						
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_dentipper));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($row['valini']. ' / ' .$row['valfin']));												
					$row_->appendChild($cell);
				
					
			
			}
			return $dom->saveXML();
		
			
			
		
		}
      
		
	} // end function uf_srh_buscar_puntuacion_bono_merito
	
	

}// end   class sigesp_srh_c_puntuacion_bono_merito
?>