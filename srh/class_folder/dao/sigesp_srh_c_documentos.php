<?php

class sigesp_srh_c_documentos
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_documentos($path)
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
		//         Access: public (sigesp_srh_p_documentos)
		//      Argumento: 
		//	      Returns: Retorna el nuevo número de un registro de un documento legal
		//    Description: Funcion que genera un número un registro un documento legal
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(nrodoc) AS numero FROM srh_documentos ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_nrodoc = $la_datos["numero"][0]+1;
    $ls_nrodoc = str_pad ($ls_nrodoc,10,"0","left");
    return $ls_nrodoc;
  }
	
	
  
function uf_srh_guardarDocumentos ($ao_documento,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarDocumento																			    //
		//         access: public (sigesp_srh_documentos)														                //
		//      Argumento: $ao_documento    // arreglo con los datos del Documento											    //
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              //
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta o modifica un registro de Documento en la tabla srh_documentos                	//
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 09/02/2008							Fecha Última Modificación: 09/02/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$as_nrodoc=$ao_documento->nrodoc;
	
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	 
	  $ls_sql = "UPDATE srh_documentos SET ".
	            "dendoc = '$ao_documento->dendoc', ".
	            "codtipdoc = '$ao_documento->codtipdoc', ".
	            "acceso = '$ao_documento->accdoc', ".
				"direccion = '$ao_documento->dirdoc', ".
				"archivo = '$ao_documento->archdoc' ".
			    "WHERE nrodoc= '$ao_documento->nrodoc' AND codemp='".$this->ls_codemp."'" ;
		
				
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el registro de Documento".$as_nrodoc;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	 

	
	  $ls_sql = "INSERT INTO srh_documentos (nrodoc, dendoc, codtipdoc, acceso, direccion, archivo, codemp) ".	  
	            "VALUES ('$ao_documento->nrodoc','$ao_documento->dendoc','$ao_documento->codtipdoc','$ao_documento->accdoc', '$ao_documento->dirdoc','$ao_documento->archdoc','".$this->ls_codemp."')";
				
		
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el registro del Documento ".$as_nrodoc;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->documentos MÉTODO->uf_srh_guardarDocumento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				$this->io_sql->commit();
		}
		
		
	return $lb_guardo;
  }
	
	
	
	
function uf_srh_eliminarDocumentos($as_nrodoc, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarDocumento																				//
		//        access:  public (sigesp_srh_documentos)															            //
		//      Argumento: $as_nrodoc        // numero del registro del Documento										        //
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                //
		//	      Returns: Retorna un Booleano														
		//    Description: Funcion que elimina un Documento legal en la tabla srh_documentos		        		      
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 09/02/2008							Fecha Última Modificación: 09/02/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_documentos ".
	          "WHERE nrodoc = '$as_nrodoc'   AND codemp='".$this->ls_codemp."'";
			  
			  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->documentos MÉTODO->uf_srh_eliminarDocumento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el registro del Documento ".$as_nrodoc;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
	return $lb_borro;
  }
	

	
	
function uf_srh_buscar_documentos($as_nrodoc,$as_dendoc,$as_codtipdoc)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_documentos																				//
		//         access: public (sigesp_srh_documentos)													                    //
		//      Argumento: $as_nrodoc   //  numero del registro del Documento							                        //
		//                 $as_dendoc   //  denominación del documento
		//                 $as_codtipdoc   //  tipo del documento                                                             //
		//	      Returns: Retorna un XML  																						//
		//    Description: Funcion busca un Documento en la tabla srh_documentos y crea un XML para mostrar       
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 09/02/2008							Fecha Última Modificación: 09/02/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
	
	    $ls_nrodestino="txtnrodoc";
		$ls_dendocdestino="txtdendoc";
		$ls_accdocdestino="cmbaccdoc";
		$ls_archdocdestino="txtnomarch";
		$ls_dirdocdestino="txtdirdoc";
		$ls_codtipdocdestino="txtcodtipdoc";
		$ls_dentipdocdestino="txtdentipdoc";
		
		
		$lb_valido=true;
		
		
				
				
		$ls_sql= "SELECT *  FROM srh_documentos INNER JOIN srh_tipodocumentos ON (srh_tipodocumentos.codtipdoc = srh_documentos.codtipdoc) ".
				" WHERE srh_documentos.nrodoc like '$as_nrodoc' ".
			    "   AND srh_documentos.dendoc like '$as_dendoc' ".
				"   AND srh_documentos.codtipdoc like '$as_codtipdoc' ".
				 " ORDER BY nrodoc";
		
	 
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->documentos MÉTODO->uf_srh_buscar_documentos( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);	
					
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			         
					 			     
					$ls_nrodoc=$row["nrodoc"];
			
					$ls_dendoc=trim (htmlentities ($row["dendoc"]));
					
					$ls_codtipdoc=$row["codtipdoc"];
					$ls_dentipdoc= trim (htmlentities ($row["dentipdoc"]));
				    
				    $ls_accdoc=trim (htmlentities  ($row["acceso"]));
   				    $ls_dirdoc=trim (htmlentities  ($row["direccion"]));
					$ls_archdoc=trim (htmlentities ($row["archivo"]));
						
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['nrodoc']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
				
					$cell->appendChild($dom->createTextNode($row['nrodoc']." ^javascript:aceptar(\"$ls_nrodoc\", \"$ls_dendoc\",   \"$ls_codtipdoc\", \"$ls_dentipdoc\",\"$ls_accdoc\",\"$ls_dirdoc\", \"$ls_archdoc\",\"$ls_nrodestino\", \"$ls_dendocdestino\",  \"$ls_accdocdestino\",  \"$ls_codtipdocdestino\", \"$ls_dentipdocdestino\",  \"$ls_accdocdestino\", \"$ls_dirdocdestino\", \"$ls_archdocdestino\");^_self"));
					
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_dendoc));												
					$row_->appendChild($cell);
					
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_dentipdoc));												
					$row_->appendChild($cell);
				
			}
			return $dom->saveXML();
		
			
			
		
		}
      
		
	} // end function buscar_documentos
	


}// end   class sigesp_srh_c_documentos
?>