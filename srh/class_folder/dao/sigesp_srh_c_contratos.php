<?php

class sigesp_srh_c_contratos
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_contratos($path)
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
		//	      Returns: Retorna el nuevo número de un registro de un contrato de personal
		//    Description: Funcion que genera un número un registro un contrato de personal
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:13/01/2008							Fecha Última Modificación:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(nroreg) AS numero FROM srh_contratos ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_nroreg = $la_datos["numero"][0]+1;
    $ls_nroreg = str_pad ($ls_nroreg,10,"0","left");
    return $ls_nroreg;
  }
	
	
  
  
function uf_srh_guardarContrato ($ao_contrato,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarContrato																			    //
		//         access: public (sigesp_srh_contratos)														                //
		//      Argumento: $ao_contrato    // arreglo con los datos del contrato											    //
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              //
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta o modifica un registro de contrato en la tabla srh_contratos                	//
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 20/11/2007							Fecha Última Modificación: 20/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$as_nroreg=$ao_contrato->nroreg;
	
	$minorguniadm = substr($ao_contrato->coduniadm,0,4);
	$ofiuniadm = substr($ao_contrato->coduniadm,5,2);
	$uniuniadm = substr($ao_contrato->coduniadm,8,2);
	$depuniadm = substr($ao_contrato->coduniadm,11,2);
	$prouniadm = substr($ao_contrato->coduniadm,14,2);
	
	$ao_contrato->monto=str_replace(".","",$ao_contrato->monto);
	$ao_contrato->monto=str_replace(",",".",$ao_contrato->monto);
	
	
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	 $ao_contrato->fecfin=$this->io_funcion->uf_convertirdatetobd($ao_contrato->fecfin);
	 $ao_contrato->fecini=$this->io_funcion->uf_convertirdatetobd($ao_contrato->fecini);
	 
	 
	 
	  $ls_sql = "UPDATE srh_contratos SET ".
	            "codper = '$ao_contrato->codper', ".
				"nomper = '$ao_contrato->nomper', ".
				"apeper = '$ao_contrato->apeper', ".
				"nacper = '$ao_contrato->nacper', ".
				"codpro = '$ao_contrato->codpro', ".
	            "codtipcon = '$ao_contrato->codtipcon', ".
	            "fecini = '$ao_contrato->fecini', ".
				"fecfin = '$ao_contrato->fecfin', ".
				"observacion = '$ao_contrato->obs', ".
				"monto = '$ao_contrato->monto', ".
				"estado = '$ao_contrato->estado', ".
			    "codcar  = '$ao_contrato->codcar', ".    
				"codnom = '$ao_contrato->codnom', ".
				" minorguniadm='$minorguniadm',  ".
			    " ofiuniadm='$ofiuniadm',  ".
				" uniuniadm='$uniuniadm',  ".
				" depuniadm='$depuniadm',  ".
				" prouniadm='$prouniadm',  ". 
				"funcion  = '$ao_contrato->funcion', ".
				"horario = '$ao_contrato->horario', ". 
				"descripcion = '$ao_contrato->des ' ".
	            "WHERE nroreg= '$ao_contrato->nroreg' AND codemp='".$this->ls_codemp."'" ;
		
				
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el registro de Contrato".$as_nroreg;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	 $ao_contrato->fecfin=$this->io_funcion->uf_convertirdatetobd($ao_contrato->fecfin);
	 $ao_contrato->fecini=$this->io_funcion->uf_convertirdatetobd($ao_contrato->fecini);

	 
	  $ls_sql = "INSERT INTO srh_contratos (nroreg, codper, codtipcon, fecini,fecfin, observacion, descripcion, monto, estado, codcar, codnom, minorguniadm,ofiuniadm, uniuniadm, depuniadm, prouniadm, funcion,horario,nomper,apeper, nacper, codpro, codemp) ".	  
	            "VALUES ('$ao_contrato->nroreg','$ao_contrato->codper','$ao_contrato->codtipcon','$ao_contrato->fecini', '$ao_contrato->fecfin','$ao_contrato->obs','$ao_contrato->des','$ao_contrato->monto','$ao_contrato->estado',  '$ao_contrato->codcar',  '$ao_contrato->codnom','$minorguniadm','$ofiuniadm', '$uniuniadm', '$depuniadm', '$prouniadm', '$ao_contrato->funcion','$ao_contrato->horario','$ao_contrato->nomper','$ao_contrato->apeper','$ao_contrato->nacper','$ao_contrato->codpro', '".$this->ls_codemp."')";
				
		
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el registro del Contrato ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->contratos MÉTODO->uf_srh_guardarContrato ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
	
	
	
function uf_srh_eliminarContrato($as_nroreg, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarContrato																				//
		//        access:  public (sigesp_srh_contratos)															            //
		//      Argumento: $as_nroreg        // numero del registro del Contrato										        //
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que elimina un contrato empleo en la tabla srh_contratos		        		                 //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 20/11/2007							Fecha Última Modificación: 20/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_contratos ".
	          "WHERE nroreg = '$as_nroreg'   AND codemp='".$this->ls_codemp."'";
			  
			  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->contratos MÉTODO->uf_srh_eliminarContrato ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el registro del Contrato ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
	return $lb_borro;
  }

	
	
	
function uf_srh_buscar_contratos($as_nroreg,$as_codper,$as_apeper,$as_nomper,$as_codtipcon)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_contratos																				//
		//         access: public (sigesp_srh_contratos)													                    //
		//      Argumento: $as_nroreg   //  numero del registro del Contrato							                        //
		//                 $as_codper   //  codula del trabajador                                                               //
		//                 $as_apeper   //  apellido del trabajador                                                             //
		//                 $as_nomper   //  nombre del trabajador                                                               //
		//	      Returns: Retorna un XML  																						//
		//    Description: Funcion busca un contrato en la tabla srh_contratos y crea un XML para mostrar        //
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 20/11/2007							Fecha Última Modificación: 20/11/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
	
	    $ls_nrodestino="txtnroreg";
		$ls_coddestino="txtcodper";
		$ls_fecfindestino="txtfecfin";
		$ls_apedestino="txtapeper";
		$ls_nomdestino="txtnomper";
		$ls_codtipcondestino="txtcodtipcon";
		$ls_dentipcondestino="txtdentipcon";
		$ls_fecinidestino="txtfecini";
		$ls_desdestino="txtdes";
		$ls_obsdestino="txtobs";
		$ls_desdestino="txtdes";
		$ls_montodestino="txtmontocon";
		$ls_estadodestino="comboestcon";
		$ls_codcardestino="txtcodcar";
		$ls_codnomdestino="txtcodnom";
		$ls_descardestino="txtdescar";
		$ls_coduniadmdestino="txtcoduniadm";
		$ls_denuniadmdestino="txtdenuniadm";
		$ls_funcdestino="txtfunc";
		$ls_hordestino="txthor";
		$ls_nacperdestino="cmbnacper";
		$ls_codprodestino="txtcodpro"; 
		$ls_desprodestino="txtdespro"; 
		
		
		$lb_valido=true;
		
				
		$ls_sql= "SELECT sno_unidadadmin.desuniadm, sno_cargo.descar, sno_asignacioncargo.denasicar, sno_profesion.despro,".
		        " srh_tipocontratos.dentipcon,  srh_contratos.*  ".
		        "  FROM  sno_unidadadmin, srh_contratos ".
				" INNER JOIN srh_tipocontratos ON (srh_tipocontratos.codtipcon = srh_contratos.codtipcon) ". 	
				" LEFT JOIN sno_cargo ON (srh_contratos.codcar = sno_cargo.codcar ".
				" AND srh_contratos.codnom = sno_cargo.codnom ) ".
				" LEFT JOIN sno_asignacioncargo ON (srh_contratos.codcar = sno_asignacioncargo.codasicar ".
				" AND srh_contratos.codnom = sno_asignacioncargo.codnom)".
				" LEFT JOIN sno_profesion ON (srh_contratos.codpro = sno_profesion.codpro AND srh_contratos.codemp= sno_profesion.codemp)".										
			    " WHERE srh_contratos .minorguniadm =  sno_unidadadmin.minorguniadm " .
				" AND srh_contratos.ofiuniadm =  sno_unidadadmin.ofiuniadm ".
				" AND srh_contratos.uniuniadm =  sno_unidadadmin.uniuniadm ".
				" AND srh_contratos.depuniadm =  sno_unidadadmin.depuniadm ".
				" AND srh_contratos.prouniadm =  sno_unidadadmin.prouniadm ".
				" AND srh_contratos.nroreg like '$as_nroreg' ".
			    " AND srh_contratos.codper like '$as_codper' ".
				" AND srh_contratos.nomper like '$as_nomper' ".
				" AND srh_contratos.apeper like '$as_apeper' ".
				" AND srh_contratos.codtipcon like '$as_codtipcon' ".				
			   " ORDER BY nroreg";
		
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->contratos MÉTODO->uf_srh_buscar_contratos( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);	
					
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			         
					$ls_nroreg=$row["nroreg"];
					$ls_fecfin=$this->io_funcion->uf_formatovalidofecha($row["fecfin"]);
				    $ls_fecfin=$this->io_funcion->uf_convertirfecmostrar($ls_fecfin);
					$ls_codper=$row["codper"];
					$ls_apeper= trim (htmlentities ($row["apeper"]));
					$ls_nomper=trim (htmlentities ($row["nomper"]));
					$ls_codtipcon=$row["codtipcon"];
					$ls_dentipcon=htmlentities ($row["dentipcon"]);
				    $ls_fecini=$this->io_funcion->uf_formatovalidofecha($row["fecini"]);
				    $ls_fecini=$this->io_funcion->uf_convertirfecmostrar($ls_fecini);
				    $ls_obs= htmlentities ($row["observacion"]);
   				    $ls_des=htmlentities ($row["descripcion"]);
					$ls_monto=$row["monto"];
					$ls_estado=$row["estado"];
					$ls_descar=trim (htmlentities ($row["denasicar"]));
					
					if ($ls_descar=="")
					{
						$ls_descar		=trim (htmlentities  ($row["descar"]));
						$ls_codcar		=$row["codcar"];
						$ls_codnom		=$row["codnom"];
					} 
					else
					{
						$ls_descar		=trim (htmlentities ($row["denasicar"]));
						$ls_codcar		=$row["codcar"];
						$ls_codnom		=$row["codnom"];
				    }
					
					
					$ls_coduniadm= ($row["minorguniadm"].'-'.$row["ofiuniadm"].'-'.$row["uniuniadm"].'-'.$row["depuniadm"].'-'.$row["prouniadm"]);
					$ls_denuniadm=trim (htmlentities ($row["desuniadm"]));
					$ls_func= trim (htmlentities ($row["funcion"]));
					$ls_hor= trim (htmlentities ($row["horario"])); 
					$ls_despro		=trim (htmlentities  ($row["despro"]));
					$ls_codpro		=$row["codpro"];
					$ls_nacper		=$row["nacper"];
												
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['nroreg']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
				
					$cell->appendChild($dom->createTextNode($row['nroreg']." ^javascript:aceptar(\"$ls_nroreg\", \"$ls_codper\",  \"$ls_apeper\", \"$ls_nomper\", \"$ls_codtipcon\", \"$ls_dentipcon\",\"$ls_fecini\",\"$ls_fecfin\", \"$ls_obs\",\"$ls_des\",  \"$ls_monto\",\"$ls_estado\",\"$ls_nrodestino\", \"$ls_coddestino\",  \"$ls_apedestino\",  \"$ls_nomdestino\", \"	$ls_codtipcondestino\", \"$ls_dentipcondestino\",\"$ls_fecinidestino\",\"$ls_fecfindestino\", \"$ls_obsdestino\", \"$ls_desdestino\",\"$ls_montodestino\", \"$ls_estadodestino\", \"$ls_codcar\", \"$ls_codnom\", \"$ls_descar\", \"$ls_coduniadm\", \"$ls_denuniadm\", \"$ls_func\", \"$ls_hor\", \"$ls_codcardestino\", \"$ls_codnomdestino\", \"$ls_descardestino\", \"$ls_coduniadmdestino\", \"$ls_denuniadmdestino\", \"$ls_funcdestino\", \"$ls_hordestino\",\"$ls_apedestino\",\"$ls_nacperdestino\",\"$ls_nacper\",\"$ls_codprodestino\",\"$ls_codpro\",\"$ls_desprodestino\",\"$ls_despro\");^_self"));
					
								
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($row["fecfin"]));												
					$row_->appendChild($cell);
					
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_codper));												
					$row_->appendChild($cell);
					
					if ($ls_apeper!='0') {
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(($ls_nomper).'  '.($ls_apeper)));												
					$row_->appendChild($cell);
					}
					else {
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_nomper));												
					$row_->appendChild($cell);
					}
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_dentipcon));												
					$row_->appendChild($cell);
				
			}
			return $dom->saveXML();
		
			
			
		
		}
      
		
	} // end function buscar_contratos
	


}// end   class sigesp_srh_c_contratos
?>