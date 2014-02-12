<?php

class sigesp_srh_c_defcontrato
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_defcontrato($path)
	{   
	    require_once($path."shared/class_folder/class_sql.php");
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
		//         Access: public (sigesp_srh_p_contratos)
		//      Argumento: 
		//	      Returns: Retorna el nuevo número de un registro de una nueva configuración de contrato
		//    Description: Funcion que genera un número un registro de configuración de contrato
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 30/06/2008							Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(codcont) AS numero FROM srh_defcontrato ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_nroreg = $la_datos["numero"][0]+1;
    $ls_nroreg = str_pad ($ls_nroreg,3,"0","left");
    return $ls_nroreg;
  }
	
	
  
  
function uf_srh_guardar_defcontrato ($ao_contrato,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_defcontrato																		    //
		//         access: public (sigesp_srh_defcontrato)														                //
		//      Argumento: $ao_contrato    // arreglo con los datos de la configuración del contrato 					        //
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              //
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta o modifica un registro de configuración de contrato en la tabla srh_defcontrato
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 30/06/2008							Fecha Última Modificación: 30/06/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$as_codcont=$ao_contrato->codcont;	
	$ao_contrato->marsupcont=str_replace(".","",$ao_contrato->marsupcont);
	$ao_contrato->marsupcont=str_replace(",",".",$ao_contrato->marsupcont);
	$ao_contrato->marinfcont=str_replace(".","",$ao_contrato->marinfcont);
	$ao_contrato->marinfcont=str_replace(",",".",$ao_contrato->marinfcont);
	
	
	
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	  $ls_sql = "UPDATE srh_defcontrato SET ".
	            "descont = '$ao_contrato->descont', ".
	            "tamletcont = '$ao_contrato->tamletcont', ".
	            "tamletpiecont = '$ao_contrato->tamletpiecont', ".
				"intlincont= '$ao_contrato->intlincont', ".
				"marsupcont = '$ao_contrato->marsupcont', ".
				"marinfcont = '$ao_contrato->marinfcont', ".
				"titcont = '$ao_contrato->titcont', ".
				"arcrtfcont = '$ao_contrato->nomrtf', ".
				"concont = '$ao_contrato->concont', ". 
				"piepagcont = '$ao_contrato->piepagcont ' ".
	            "WHERE codcont= '$ao_contrato->codcont' AND codemp='".$this->ls_codemp."'" ;
		
				
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el registro de Configuración de Contrato".$as_codcont;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	 $ls_sql = "INSERT INTO srh_defcontrato (codcont, descont, tamletcont, tamletpiecont,intlincont, marsupcont, marinfcont, titcont,  arcrtfcont, concont, piepagcont, codemp) ".	  
	            "VALUES ('$ao_contrato->codcont','$ao_contrato->descont','$ao_contrato->tamletcont','$ao_contrato->tamletpiecont', '$ao_contrato->intlincont','$ao_contrato->marsupcont','$ao_contrato->marinfcont','$ao_contrato->titcont',  '$ao_contrato->nomrtf', '$ao_contrato->concont','$ao_contrato->piepagcont', '".$this->ls_codemp."')";
				
		
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el registro de Configuración de Contrato ".$as_codcont;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->defcontrato MÉTODO->uf_srh_guardar_defcontrato ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				$this->io_sql->commit();
		}
		
		
	return $lb_valido;
  }
	
	
	
	
function uf_srh_eliminar_defcontrato($as_codcont, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarContrato																				//
		//        access:  public (sigesp_srh_defcontrato)															            //
		//      Argumento: $as_codcont       // numero de la configuración del contraro									        //
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que elimina una configurción de contrato de la tabla srh_defcontrato		        		    //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 30/06/2008							Fecha Última Modificación: 30/06/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_defcontrato ".
	          "WHERE codcont = '$as_codcont'   AND codemp='".$this->ls_codemp."'";
			  
			  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->defcontrato MÉTODO->uf_srh_eliminar_defcontrato ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la configuracion del Contrato ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
	return $lb_valido;
  }

	
	
	
function uf_srh_buscar_defcontrato($as_codcont,$as_descont)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_defcontrato																			//
		//         access: public (sigesp_srh_defcontrato)													                    //
		//      Argumento: $as_codcont   //  numero del registro de la Configuración de  Contrato								//
		//                 $as_descont   //  descripción de la Configuración de Contrato
		//	      Returns: Retorna un XML  																						//
		//    Description: Funcion busca una Configuración de  Contrato en la tabla srh_defcontrato y crea un XML para mostrar
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 30/06/2008							Fecha Última Modificación: 30/06/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
			$ls_codcontdestino       ="txtcodcont";
			$ls_descontdestino       ="txtdescont";
			$ls_tamletcontdestino    ="txttamletcont";
			$ls_tamletpiecontdestino ="txttamletpiecont";
			$ls_intlincontdestino    ="cmdintlincont";
			$ls_marsupcontdestino    ="txtmarsupcont";
			$ls_marinfcontdestino    ="txtmarinfcont";
			$ls_titcontdestino 	     ="txttitcont";
			$ls_arcrtfcontdestino    ="txtnomrtf";
			$ls_concontdestino       ="txtconcont";
			$ls_piepagcontdestino    ="txtpiepagcont";
			
		$lb_valido=true;
						
		$ls_sql= "SELECT * FROM srh_defcontrato ".				
			    " WHERE  codcont like '$as_codcont' ".
				" AND    descont like '$as_descont' ".				
			   " ORDER BY codcont";
		
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->defcontrato MÉTODO->uf_srh_buscar_defcontrato( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);	
					
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			         
				$ls_codcont       =$row["codcont"];
				$ls_descont       = trim (htmlentities ($row["descont"]));
				$ls_tamletcont    =$row["tamletcont"];
				$ls_tamletpiecont =$row["tamletpiecont"];
				$ls_intlincont    =$row["intlincont"];
				$ls_marsupcont    =$row["marsupcont"];
				$ls_marinfcont    =$row["marinfcont"];
				$ls_titcont 	  =trim (htmlentities ($row["titcont"]));
				$ls_arcrtfcont    =trim (htmlentities ($row["arcrtfcont"]));
				$ls_concont       =trim (htmlentities ($row["concont"]));
				$ls_piepagcont    =trim (htmlentities ($row["piepagcont"]));
				
				$row_ = $team->appendChild($dom->createElement('row'));
				$row_->setAttribute("id",$row['codcont']);
				$cell = $row_->appendChild($dom->createElement('cell'));   
					
						
				$cell->appendChild($dom->createTextNode($row['codcont']." ^javascript:aceptar( \"$ls_codcont\", \"$ls_descont\", \"$ls_tamletcont\",\"$ls_tamletpiecont\", \"$ls_intlincont\", \"$ls_marsupcont\", \"$ls_marinfcont\", \"$ls_titcont\", \"$ls_arcrtfcont\", \"$ls_concont\", \"$ls_piepagcont\", \"$ls_codcontdestino\", \"$ls_descontdestino\", \"$ls_tamletcontdestino\", \"$ls_tamletpiecontdestino\", \"$ls_intlincontdestino\", \"$ls_marsupcontdestino\", \"$ls_marinfcontdestino\", \"$ls_titcontdestino\", \"$ls_arcrtfcontdestino\", \"$ls_concontdestino\", \"$ls_piepagcontdestino\");^_self"));
					
								
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_descont));												
				$row_->appendChild($cell);
				
				if ($ls_arcrtfcont=="")
				{
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode('No tiene'));												
					$row_->appendChild($cell);
				}
				else
				{
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_arcrtfcont));												
					$row_->appendChild($cell);
				}
			
				
				
			}
			return $dom->saveXML();
		
			
			
		
		}
      
		
	} // end function buscar_contratos
	


}// end   class sigesp_srh_c_defcontrato
?>