<?php

class sigesp_srh_c_organigrama
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;
	var $codorg = null;	
	var $desorg = null;
	var $codemp = null;

	function sigesp_srh_c_organigrama($path)
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
	
	function uf_srh_select_organigrama($as_codorg)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_organigrama
		//         Access: public (sigesp_srh_d_organigrama)
		//      Argumento: $as_codorg    // codigo del organigrama
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un organigrama en la tabla de  srh_organigrama
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 30/09/2008							Fecha Última Modificación: 30/09/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_organigrama  ".
				  " WHERE codorg='".trim($as_codorg)."'".
				  " AND codemp='".$this->ls_codemp."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->organigrama MÉTODO->uf_srh_select_organigrama ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_organigrama


	function  uf_srh_insert_organigrama($as_codorg,$as_desorg,$as_nivorg, $as_padorg,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_organigrama
		//         Access: public (sigesp_srh_d_organigrama)
		//      Argumento: $as_codorg       // codigo de organigrama
	    //                 $as_desorg      // denominacion de organigrama
		//                 $as_nivorg     //   nivel del organigrama
		//                 $as_padorg     // padre del organigrama
	    //                 $aa_seguridad    // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un organigrama en la tabla de srh_organigrama
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 30/09/2008							Fecha Última Modificación: 30/09/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		if ($as_nivorg=="")
		{
			$as_nivorg=0;
		}
		
		if ($as_padorg=="")
		{
			$as_padorg='----------';
		}
		
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO srh_organigrama (codorg, desorg,nivorg,padorg,codemp) ".
					" VALUES('".$as_codorg."','".$as_desorg."', ".$as_nivorg.",'".$as_padorg."','".$this->ls_codemp."')" ;
				
			
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->organigrama MÉTODO->uf_srh_insert_organigrama ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó organigrama ".$as_codorg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_organigrama

	function uf_srh_update_organigrama($as_codorg,$as_desorg,$as_nivorg, $as_padorg,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_organigrama
		//         Access: public (sigesp_srh_d_organigrama)
		//      Argumento: $as_codorg   // codigo de organigrama 
	    //                 $as_desorg   // denominacion de organigrama
		//                 $as_nivorg     //   nivel del organigrama
		//                 $as_padorg     // padre del organigrama
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un organigrama  en la tabla de srh_organigrama
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 30/09/2008							Fecha Última Modificación: 30/09/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 
		if ($as_nivorg=="")
		{
			$as_nivorg=0;
		}
		
		if ($as_padorg=="")
		{
			$as_padorg='----------';
		}
		 
		 $ls_sql = "UPDATE srh_organigrama SET  ".
		 		   " desorg='". $as_desorg."', ".
		           " nivorg=". $as_nivorg.",  ".
				   " padorg='". $as_padorg."'  ".				  
				   " WHERE codorg='" . $as_codorg ."'".
				   " AND codemp='".$this->ls_codemp."'";
        
		$this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->organigrama MÉTODO->uf_srh_update_organigrama ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó organigrama ".$as_codorg;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_organigrama
	
	
 function uf_select_personal_organigrama ($as_codorg)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:uf_select_personal_organigrama
		//		   Access: private
 		//	    Arguments: as_codorg // código del organigrama 
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el organigrama esta asociada a una seccion
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codorg ".
				 "  FROM sno_personal".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND codorg = '".$as_codorg."' ".
				 "    AND codorg <> '----------' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->organigrama  MÉTODO->uf_select_personal_organigrama ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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

 function uf_select_existe_organigrama ($as_codorg)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:uf_select_existe_organigrama
		//		   Access: private
 		//	    Arguments: as_codorg // código del organigrama 
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el organigrama esta asociada a nivel del organigrama como padre
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/04/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codorg ".
				 "  FROM srh_organigrama".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND padorg = '".$as_codorg."' ".
				 "    AND codorg <> '----------' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->organigrama  MÉTODO->uf_select_existe_organigrama ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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

function uf_srh_delete_organigrama($as_codorg,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_organigrama
		//         Access: public (sigesp_srh_d_organigrama)
		//      Argumento: $as_codorg  // codigo de organigrama
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un organigrama  en la tabla de srh_organigrama 
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 30/09/2008							Fecha Última Modificación: 30/09/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		if ( ($this->uf_select_personal_organigrama($as_codorg)) || ($this->uf_select_existe_organigrama($as_codorg)))
		{
			$lb_valido=false;
			$lb_existe = true;
			
		}
		else
		{
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM srh_organigrama".
						 " WHERE codorg= '".$as_codorg."'"; 
				 
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->organigrama MÉTODO->uf_srh_delete_organigrama ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó organigrama ".$as_codorg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		return array($lb_valido,$lb_existe);
	} // end function uf_srh_delete_organigrama
	
	
	
	function uf_srh_buscar_organigrama($as_codorg,$as_desorg, $as_nivorg)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_organigrama
		//         Access: private
		//      Argumento: $as_codorg  // codigo de la organigrama
		//				   $as_desorg  // descripcion del organigrama
		//                 $as_nivorg  // nivel del organigrama
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca un organigrama  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 30/09/2008							Fecha Última Modificación: 30/09/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_coddestino="txtcodorg";
		$ls_dendestino="txtdesorg";
		$ls_nivorgdestino="cmbnivorg";
		$ls_padorgdestino="txtpadorg";
		$ls_nivpaddestino="txtnivpad";
		$ls_criterio="";
		$as_nivorg=rtrim($as_nivorg);
		
		if ((!empty($as_nivorg)) || ($as_nivorg=='0'))
		{
			$ls_criterio=" AND nivorg = ".$as_nivorg." ";
			
		}
		
		$lb_valido=true;
		$ls_sql="SELECT srh_organigrama.* FROM srh_organigrama ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codorg like '".$as_codorg."' ".
				"   AND desorg like '".$as_desorg."' ".$ls_criterio.
				"   AND codorg <> '----------' ".
			    " ORDER BY codorg"; 
				
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->organigrama MÉTODO->uf_srh_buscar_organigrama( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			if ($rs_data->RecordCount() > 0)
			{
			
				while (!$rs_data->EOF) 
				{
			     
					$ls_codorg=$rs_data->fields["codorg"];
					$ls_desorg=htmlentities($rs_data->fields["desorg"]);
					
					$ls_nivorg= $rs_data->fields["nivorg"];
				    $ls_padorg= ($rs_data->fields["padorg"]);
					
					if ($ls_padorg=='----------')
					{
						$ls_padorg="NO TIENE";
						$ls_nivpad="";
					}
					else
					{
						$ls_nivpad=intval($ls_nivorg)-1;
					}
					
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codorg']);
					$cell = $row_->appendChild($dom->createElement('cell')); 
					
					$cell->appendChild($dom->createTextNode($rs_data->fields['codorg']." ^javascript:aceptar(\"$ls_codorg\",\"$ls_desorg\",\"$ls_coddestino\",\"$ls_dendestino\", \"$ls_nivorg\", \"$ls_nivorgdestino\", \"$ls_padorg\", \"$ls_padorgdestino\", \"$ls_nivpad\", \"$ls_nivpaddestino\");^_self"));
					$cell = $row_->appendChild($dom->createElement('cell'));
					
					$cell->appendChild($dom->createTextNode($ls_desorg));												
					$row_->appendChild($cell);
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_nivorg));												
					$row_->appendChild($cell);
					
					$rs_data->MoveNext();
				}
			}
			else
			{
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",'');
					$cell = $row_->appendChild($dom->createElement('cell')); 
					
					$cell->appendChild($dom->createTextNode(''));
					$cell = $row_->appendChild($dom->createElement('cell'));
					
					$cell->appendChild($dom->createTextNode('NO SE ENCONTRARON REGISTROS'));												
					$row_->appendChild($cell);
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(''));												
					$row_->appendChild($cell);
			}
			return $dom->saveXML();
	
		}
    } // end function uf_srh_buscar_organigrama(
	
	

function uf_srh_consultar_organigrama($as_codorg,$as_nivorg,&$ao_object,&$ai_totrow)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_consultar_organigrama
		//         Access: private
		//      Argumento: $ao_object  // objeto con los datos de la estructura del organigrama	
		//                 $as_codorg // código del organigrama 
		//                 $as_nivorg // nivel del organigrama
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca un organigrama  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 15/10/2008							Fecha Última Modificación: 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_criterio="";
		if (($as_codorg!=""))
		{
			$ls_criterio=  " AND sno_personal.codorg IN (SELECT codorg FROM srh_organigrama ".
						   "                              WHERE srh_organigrama.codorg ='".$as_codorg."' ".
						   "                              OR srh_organigrama.padorg ='".$as_codorg."')";
		}
		else
		{
			$ls_criterio="  AND srh_organigrama.nivorg= '".$as_nivorg."' ";
		}
		
		$ao_object="";
		$ai_totrow=0;
		$ls_sql=" SELECT sno_personal.codper, sno_personal.cedper,sno_personal.nomper, ".
		        "  sno_personal.apeper, sno_personal.fotper, srh_organigrama.desorg ".
				"  FROM sno_personal,srh_organigrama ".				
				"  WHERE sno_personal.codemp= '".$this->ls_codemp."' ".
				"  AND sno_personal.codemp=srh_organigrama.codemp ".
				"  AND sno_personal.codorg=srh_organigrama.codorg ".				
				"  AND srh_organigrama.codorg <> '----------' ".$ls_criterio.			
				" ORDER BY srh_organigrama.codorg,sno_personal.codper ";	
			
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->organigrama MÉTODO->uf_srh_consultar_organigrama( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			
		}
		else
		{		
			if ($rs_data->RecordCount() > 0)
			{
			
				while (!$rs_data->EOF) 
				{
						$lb_valido=true;
						$ls_codper=$rs_data->fields["codper"];
						$ls_cedper=$rs_data->fields["cedper"];
						$ls_nomper=$rs_data->fields["apeper"].' '.$rs_data->fields["nomper"];
						$ls_uniadm=trim($rs_data->fields["desorg"]);
						
						$ls_fotper=$rs_data->fields["fotper"];
					
						if (($ls_fotper=="") || ($ls_fotper=="blanco.jpg"))
						{
							$ls_fotper="../../../fotos/silueta.jpg";
						}
						else
						{
							$ls_fotper="../../../../sno/fotospersonal/".$ls_fotper;
						}
						
						$ai_totrow=$ai_totrow+1;
						
						$ao_object[$ai_totrow][1]="<img src='".trim($ls_fotper)."' width='150' height='200'>";
						$ao_object[$ai_totrow][2]="<textarea name=txtcodper".$ai_totrow." type=text id=txtcodper".$ai_totrow." class=sin-borde  cols='12'  style='text-align:center' readonly>".$ls_codper."</textarea>";
						$ao_object[$ai_totrow][3]="<textarea name=txtcedper".$ai_totrow." type=text id=txtcedper".$ai_totrow." class=sin-borde  cols='10'  style='text-align:center'  readonly>".$ls_cedper."</textarea>";
						$ao_object[$ai_totrow][4]="<textarea name=txtapeper".$ai_totrow." type=text id=txtapeper".$ai_totrow." class=sin-borde   cols='32'  style='text-align:left'  readonly>".$ls_nomper."</textarea>";
						$ao_object[$ai_totrow][5]="<textarea name=txtuniadm".$ai_totrow." type=text id=txtuniadm".$ai_totrow." class=sin-borde   cols='60'  style='text-align:left'  readonly>".$ls_uniadm."</textarea>";
											
							
					$rs_data->MoveNext();
				}
			}
			else
			{
				$this->io_msg->message("No se encontraron Registros.");
			}
				
		}
		
	return $lb_valido;
    } // end function uf_srh_buscar_organigrama(
	

}// end   class sigesp_srh_c_organigrama
?>
