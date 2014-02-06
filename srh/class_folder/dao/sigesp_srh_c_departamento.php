<?php

class sigesp_srh_c_departamento
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;
	var $coddep = null;	
	var $dendep = null;
	var $codemp = null;

	function sigesp_srh_c_departamento($path)
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

	
	function uf_srh_select_departamento($as_coddep)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_departamento
		//         Access: public (sigesp_srh_d_departamento)
		//      Argumento: $as_coddep    // codigo del departamento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un departamento en la tabla de  srh_departamento
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 05/09/2007							Fecha Última Modificación: 05/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_departamento  ".
				  " WHERE coddep like '".$as_coddep."'".
				  " AND codemp='".$this->ls_codemp."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->departamento MÉTODO->uf_srh_select_departamento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_departamento


	function  uf_srh_insert_departamento($as_coddep,$as_dendep,$as_coduniadm,$as_codger,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_departamento
		//         Access: public (sigesp_srh_d_departamento)
		//      Argumento: $as_coddep       // codigo de departamento
	    //                 $as_dendep      // denominacion de departamento
		//                 $as_coduniadm   // código de la unidad administrativa 
	    //                 $aa_seguridad    // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un departamento en la tabla de srh_departamento
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 05/09/2007							Fecha Última Modificación: 05/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$minorguniadm = substr($as_coduniadm,0,4);
		$ofiuniadm = substr($as_coduniadm,5,2);
		$uniuniadm = substr($as_coduniadm,8,2);
		$depuniadm = substr($as_coduniadm,11,2);
		$prouniadm = substr($as_coduniadm,14,2);
		
		if ($as_codger=='')
		{
			$as_codger='----------'; 
		}
		
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO srh_departamento (coddep, dendep,minorguniadm,ofiuniadm, uniuniadm, depuniadm, prouniadm,codemp, codger) ".
					" VALUES('".$as_coddep."','".$as_dendep."', '".$minorguniadm."','".$ofiuniadm."', '".$uniuniadm."', '".$depuniadm."', '".$prouniadm."' ,'".$this->ls_codemp."', '".$as_codger."')" ;
				
			
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->departamento MÉTODO->uf_srh_insert_departamento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó departamento ".$as_coddep;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_departamento

	function uf_srh_update_departamento($as_coddep,$as_dendep, $as_coduniadm,$as_codger,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_departamento
		//         Access: public (sigesp_srh_d_departamento)
		//      Argumento: $as_coddep   // codigo de departamento 
	    //                 $as_dendep   // denominacion de departamento
		//                 $as_coduniadm   // código de la unidad administrativa
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un departamento  en la tabla de srh_departamento
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 05-09-2007							Fecha Última Modificación: 05-09-2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 
		 $minorguniadm = substr($as_coduniadm,0,4);
 		 $ofiuniadm = substr($as_coduniadm,5,2);
		 $uniuniadm = substr($as_coduniadm,8,2);
		 $depuniadm = substr($as_coduniadm,11,2);
		 $prouniadm = substr($as_coduniadm,14,2);
		 
		 if ($as_codger=='')
		{
			$as_codger='----------'; 
		}
		 
		 $ls_sql = "UPDATE srh_departamento SET   dendep='". $as_dendep."', ".
		           " minorguniadm='". $minorguniadm."',  ".
				   " ofiuniadm='". $ofiuniadm."',  ".
				   " uniuniadm='". $uniuniadm."',  ".
				   " depuniadm='". $depuniadm."',  ".
				   " prouniadm='". $prouniadm."',  ". 
				   " codger= '".$as_codger."' ".
				   " WHERE coddep='" . $as_coddep ."'".
				   " AND codemp='".$this->ls_codemp."'";
        
		$this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->departamento MÉTODO->uf_srh_update_departamento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó departamento ".$as_coddep;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_departamento
	
	
 function uf_select_seccion_departamento ($as_coddep)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:uf_select_seccion_departamento
		//		   Access: private
 		//	    Arguments: as_coddep // código del departamento 
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el departamento esta asociada a una seccion
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT coddep ".
				 "  FROM srh_seccion".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND coddep = '".$as_coddep."' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->departamento  MÉTODO->uf_select_seccion_departamento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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

function uf_srh_delete_departamento($as_coddep,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_departamento
		//         Access: public (sigesp_srh_d_departamento)
		//      Argumento: $as_coddep  // codigo de departamento
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un causa de llamada de atencion  en la tabla de srh_departamento 
		//                
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 05-09-2007							Fecha Última Modificación: 05-09-2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe = $this->uf_select_seccion_departamento ($as_coddep);
		
		if($lb_existe)
		{
			$lb_valido=false;
			
		}
		else
		{
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM srh_departamento".
						 " WHERE codemp='".$this->ls_codemp."'  ".
						 " AND coddep= '".$as_coddep."'"; 
				 
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->departamento MÉTODO->uf_srh_delete_departamento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó departamento ".$as_coddep;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		return array($lb_valido,$lb_existe);
	} // end function uf_srh_delete_departamento
	
	
	
	function uf_srh_buscar_departamento($as_coddep,$as_dendep,$as_codger)
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
		
		$ls_coddestino="txtcoddep";
		$ls_dendestino="txtdendep";
		$ls_coduniadmdestino="txtcoduniadm";
		$ls_denuniamddestino="txtdenuniadm";
		$ls_codgerdestino="txtcodger";
		$ls_dengerdestino="txtdenger";
		
		$ls_criterio="";
		if ($as_codger!="")
		{
		   $ls_criterio= " AND srh_departamento.codger='".$as_codger."' ";
		}	
		
		$lb_valido=true;
		$ls_sql="SELECT srh_departamento.*, sno_unidadadmin.desuniadm, srh_gerencia.denger ".
		        " FROM srh_departamento, sno_unidadadmin, srh_gerencia ".
				" WHERE srh_departamento.codemp='".$this->ls_codemp."'  ".
				"   AND srh_departamento.codemp=  sno_unidadadmin.codemp " .
				"   AND srh_departamento.minorguniadm =  sno_unidadadmin.minorguniadm " .
				"   AND srh_departamento.ofiuniadm =  sno_unidadadmin.ofiuniadm ".
				"   AND srh_departamento.uniuniadm =  sno_unidadadmin.uniuniadm ".
				"   AND srh_departamento.depuniadm =  sno_unidadadmin.depuniadm ".
				"   AND srh_departamento.prouniadm =  sno_unidadadmin.prouniadm ".
				"   AND srh_departamento.codemp=  srh_gerencia.codemp " .
				"   AND srh_departamento.codger =  srh_gerencia.codger " .
				"   AND coddep like '".$as_coddep."' ".
				"   AND dendep like '".$as_dendep."' ".$ls_criterio.
			    " ORDER BY coddep"; 
	
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->departamento MÉTODO->uf_srh_buscar_departamento( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
					$ls_coddep=$row["coddep"];
					$ls_dendep=htmlentities($row["dendep"]);
					
					$ls_coduniadm= ($row["minorguniadm"].'-'.$row["ofiuniadm"].'-'.$row["uniuniadm"].'-'.$row["depuniadm"].'-'.$row["prouniadm"]);
				    $ls_denuniadm= htmlentities ($row["desuniadm"]);
					
					$ls_codger = $row["codger"];
					$ls_denger= htmlentities ($row["denger"]);
					
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['coddep']);
					$cell = $row_->appendChild($dom->createElement('cell')); 
					if ($as_coduni=="")  
					{
						$cell->appendChild($dom->createTextNode($row['coddep']." ^javascript:aceptar(\"$ls_coddep\",\"$ls_dendep\",\"$ls_coddestino\",\"$ls_dendestino\", \"$ls_coduniadm\", \"$ls_coduniadmdestino\", \"$ls_denuniadm\", \"$ls_denuniamddestino\",\"$ls_codger\",\"$ls_denger\",\"$ls_codgerdestino\",\"$ls_dengerdestino\");^_self"));
						$cell = $row_->appendChild($dom->createElement('cell'));
					}
					else
					{
						$cell->appendChild($dom->createTextNode($row['coddep']." 	
						^javascript:aceptar_personal(\"$ls_coddep\",\"$ls_dendep\");^_self"));
						$cell = $row_->appendChild($dom->createElement('cell'));
					}
					$cell->appendChild($dom->createTextNode($ls_dendep));												
					$row_->appendChild($cell);
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_denuniadm));												
					$row_->appendChild($cell);
					
			
			}
			return $dom->saveXML();
		
			
			
	
		}
    } // end function uf_srh_buscar_departamento(
	

}// end   class sigesp_srh_c_departamento
?>
