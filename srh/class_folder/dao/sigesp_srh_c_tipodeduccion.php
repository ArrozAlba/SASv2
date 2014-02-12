<?php

class sigesp_srh_c_tipodeduccion
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_tipodeduccion($path)
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
		//         Access: public (sigesp_srh_d_concurso)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de un tipo de deducción 
		//    Description: Funcion que genera un código de un tipo de deduccion
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:26/03/08							Fecha Última Modificación:26/03/08
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(codtipded) AS codigo FROM srh_tipodeduccion  ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_codtipded = $la_datos["codigo"][0]+1;
    $ls_codtipded = str_pad ($ls_codtipded,10,"0","left");
	return $ls_codtipded;
  }

	
	function uf_srh_select_tipodeduccion($as_codtipded)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_tipodeduccion
		//      Argumento: $as_codtipded    // codigo de tipo de deducción 
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un tipo de deducción  en la tabla de  
		//                 srh_tipodeduccion
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 26/03/08							Fecha Última Modificación: 26/03/08
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_tipodeduccion  ".
				  " WHERE codtipded='".trim($as_codtipded)."'".
				  " AND codemp='".$this->ls_codemp."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tipodeduccion MÉTODO->uf_srh_select_tipodeduccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_tipodeduccion

 function  uf_srh_insert_tipodeduccion($as_codtipded,$as_dentipded,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_tipodeduccion
		//      Argumento: $as_codtipded   // codigo de tipo de deducción 
	    //                 $as_dentipded   // denominacion de tipo de deducción 	    
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un tipo de deducción  en la tabla de srh_tipodeduccion
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 26/03/08							Fecha Última Modificación: 26/03/08
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO srh_tipodeduccion (codtipded, dentipded,codemp) ".
				" VALUES('".$as_codtipded."','".$as_dentipded."','".$this->ls_codemp."')" ;
		
		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->tipodeduccion MÉTODO->uf_srh_insert_tipodeduccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Tipo de Deduccion de Seguro ".$as_codtipded;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_tipodeduccion

	function uf_srh_update_tipodeduccion($as_codtipded,$as_dentipded,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_tipodeduccion
		//      Argumento: $as_codtipded   // codigo de tipo de deducción 
	    //                 $as_dentipded   // denominacion de tipo de deducción 
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un tipo de deducción  en la tabla de srh_tipodeduccion
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 26/03/08							Fecha Última Modificación: 26/03/08
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE srh_tipodeduccion SET   dentipded='". $as_dentipded ."'". 
				   " WHERE codtipded='" . $as_codtipded ."'".
				   " AND codemp='".$this->ls_codemp."'";
	   
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->tipodeduccion MÉTODO->uf_srh_update_tipodeduccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el Tipo de Deduccion de Seguro".$as_codtipded;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_tipodeduccion
	
	
 function uf_select_tipo_deduccion ($as_codtipded)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:uf_select_tipo_dedccion
		//		   Access: private
 		//	    Arguments: $as_codtipded // código del tipo de deduccion 
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el tipo de deducción esta asociada a una configuracion de tipo de deduccion
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/04/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codtipded ".
				 "  FROM srh_dt_tipodeduccion ".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND codtipded = '".$as_codtipded."' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->tipo deduccion  MÉTODO->uf_select_tipo_deduccion  ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	
	
	
function uf_select_deduccion_personal ($as_codtipded)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:uf_select_deduccion_personal
		//		   Access: private
 		//	    Arguments: $as_codtipded // código del tipo de deduccion 
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el tipo de deducción esta asociada a un personal
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 09/05/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codtipded ".
				 "  FROM sno_personaldeduccion ".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND codtipded = '".$as_codtipded."' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->tipo deduccion  MÉTODO->uf_select_tipo_deduccion  ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	
	
function uf_select_deduccion_familiar ($as_codtipded)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_deduccion_familiar
		//		   Access: private
 		//	    Arguments: $as_codtipded // código del tipo de deduccion 
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el tipo de deducción esta asociada a un familiar de un personal
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 09/05/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codtipded ".
				 "  FROM sno_familiardeduccion ".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND codtipded = '".$as_codtipded."' ";
				
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->tipo deduccion  MÉTODO->uf_select_deduccion_familiar  ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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


function uf_srh_delete_tipodeduccion($as_codtipded,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_tipodeduccion
		//      Argumento: $as_codtipded   // codigo de tipo de deducción 
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un tipo de deducción  en la tabla de srh_tipodeduccion 
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 26/03/08							Fecha Última Modificación: 26/03/08
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=false;
	     $lb_existe=true;
		
		if (($this->uf_select_tipo_deduccion ($as_codtipded)===false)&&
		     ($this->uf_select_deduccion_personal ($as_codtipded)===false)&&
			 ($this->uf_select_deduccion_familiar ($as_codtipded)===false))
		 {
		    $lb_existe=false;
		    $this->uf_srh_eliminar_dt_configuracion_deduccion($as_codtipded,$aa_seguridad);
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM srh_tipodeduccion".
						 " WHERE codtipded= '".$as_codtipded. "'".
						 "AND codemp='".$this->ls_codemp."'"; 
			$li_row=$this->io_sql->execute($ls_sql);
			
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->tipodeduccion MÉTODO->uf_srh_delete_tipodeduccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Tipo de Deduccion de Seguro ".$as_codtipded;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		else
		{
		  $lb_existe=true;
		  $lb_valido=false;
		}
		return array($lb_valido,$lb_existe);
	} // end function uf_srh_delete_tipodeduccion
	
	
	
	
	function uf_srh_buscar_tipodeduccion($as_codtipded,$as_dentipded)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_tipodeduccion
		//      Argumento: $as_codtipded  // codigo de la tipodeduccion
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca un tipodeduccion  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 04/09/2007							Fecha Última Modificación: 04/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_coddestino="txtcodtipded";
		$ls_dendestino="txtdentipded";
	
		
		$lb_valido=true;
		$ls_sql="SELECT * FROM srh_tipodeduccion".
				" WHERE codtipded like '".$as_codtipded."' ".
				"   AND dentipded like '".$as_dentipded."' ".
			   " ORDER BY codtipded";

		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tipodeduccion MÉTODO->uf_srh_buscar_tipodeduccion( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
					$ls_codtipded=$row["codtipded"];
					$ls_dentipded= htmlentities($row["dentipded"]);
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codtipded']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codtipded']." ^javascript:aceptar(\"$ls_codtipded\",\"$ls_dentipded\",\"$ls_coddestino\",\"$ls_dendestino\");^_self"));
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_dentipded));												
					$row_->appendChild($cell);
					
					
			
			}
			return $dom->saveXML();
		
			
			
			

		}
        
		
	} // end function uf_srh_buscar_tipodeduccion(
	
	
	function uf_srh_buscar_configuracion_deduccion($as_codtipded,$as_dentipded)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_configuracion_deduccion
		//      Argumento: $as_codtipded  // codigo de la tipodeduccion
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca un tipodeduccion  para luego mostrarla
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 04/09/2007							Fecha Última Modificación: 04/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_coddestino="txtcodtipded";
		$ls_dendestino="txtdentipded";
	
		
		$lb_valido=true;
		$ls_sql="SELECT DISTINCT (srh_dt_tipodeduccion.codtipded), srh_tipodeduccion.* FROM srh_tipodeduccion, srh_dt_tipodeduccion ".
		        "  WHERE srh_tipodeduccion.codtipded = srh_dt_tipodeduccion.codtipded ".
				" AND srh_tipodeduccion.codtipded like '".$as_codtipded."' ".
				"   AND srh_tipodeduccion.dentipded like '".$as_dentipded."' ".
			   " ORDER BY srh_tipodeduccion.codtipded";

		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tipodeduccion MÉTODO->uf_srh_buscar_configuracion_deduccion( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
					$ls_codtipded=$row["codtipded"];
					$ls_dentipded= htmlentities ($row["dentipded"]);
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codtipded']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codtipded']." ^javascript:aceptar(\"$ls_codtipded\",\"$ls_dentipded\",\"$ls_coddestino\",\"$ls_dendestino\");^_self"));
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_dentipded));												
					$row_->appendChild($cell);
					
					
			
			}
			return $dom->saveXML();
		
			
			
			

		}
        
		
	} // end function uf_srh_buscar_tipodeduccion(
	

//FUNCIONES PARA EL MANEJO DE LOS DETALLES DE LAS DEDUCCIONES DE SEGURO

function uf_srh_load_configuracion_deduccion ($as_codtipded,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_requerimiento_cargo_campos
		//	    Arguments: as_codtipded  // código de la deducción 
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de una deducción 
		// Fecha Creación: 07/04/2007							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_orden ="CONVERT(coddettipded USING smallint) ";
				break;
			case "POSTGRES":
				$ls_orden = " ORDER BY (CAST(coddettipded AS smallint)) ";
				break;					
					
		}
		
		$ls_sql="SELECT * ". 
				"  FROM srh_dt_tipodeduccion ".
				" WHERE srh_dt_tipodeduccion.codemp='".$this->ls_codemp."'".
				"   AND codtipded='".$as_codtipded."'".$ls_orden;
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->tipodeduccion MÉTODO->uf_srh_load_configuracion_deduccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
		 $num=$this->io_sql->num_rows($rs_data);
           
		  if ($num!=0) {
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				
				$punto='"."';
				$coma='","';
				
				$ls_titular=$row["titular"];
				$la_titular[0]="";
				$la_titular[1]="";
				$ls_disable="";
				$ls_sueldo=trim ($row["suelbene"]);
				$ls_edadmin=$row["edadmin"];
				$ls_edadmax=$row["edadmax"];
				$ls_sexo=$row["sexbene"];
				$la_sexo[0]="";
				$la_sexo[1]="";
				$ls_hcm=$row["hcm"];
				$la_hcm[0]="";
				$la_hcm[1]="";
				$ls_nexo=$row["nexfam"];
				$la_nexo[0]="";
				$la_nexo[1]="";
				$la_nexo[2]="";
				$la_nexo[3]="";
				$li_prima=$row["valprim"];
				$li_aporempre=$row["aporempre"];
				$li_aporemple=$row["aporemple"];
						
				 switch($ls_sexo)
				{
					case "F":
						$la_sexo[0]="selected";
						break;
					case "M":
						$la_sexo[1]="selected";
						break;
				}
			   switch($ls_nexo)
				{
					case "C":
						$la_nexo[0]="selected";
						break;
					case "H":
						$la_nexo[1]="selected";
						break;
					case "P":
						$la_nexo[2]="selected";
						break;
					case "E":
						$la_nexo[3]="selected";
						break;
				}
				switch($ls_titular)
				{
					case "S":
						$la_titular[0]="selected";
						$ls_disable="disabled";
						break;
					case "N":
						$la_titular[1]="selected";
						$ls_disable="";
						break;
				}
				switch($ls_hcm)
				{
					case "S":
						$la_hcm[0]="selected";
						break;
					case "N":
						$la_hcm[1]="selected";
						break;
						
					case "1":
						$la_hcm[0]="selected";
						break;
					case "0":
						$la_hcm[1]="selected";
						break;
				}
		
				
				$ao_object[$ai_totrows][1]=" <select name=cmbtitular".$ai_totrows." id=cmbtitular".$ai_totrows." onChange='javascript:chequear_titular(this,".$ai_totrows.");'><option value=''>--Seleccione--</option>
				<option value='S' ".$la_titular[0].">Si</option>
				<option value='N' ".$la_titular[1]." >No</option></select> ";
				$ao_object[$ai_totrows][2]="<input name=txtsueldo".$ai_totrows." type=text id=txtsueldo".$ai_totrows." class=sin-borde size=14 onKeyPress='return(ue_formatonumero(this,".$punto.", ".$coma.",event))' style='text-align:center'  value='".number_format($ls_sueldo,2,",",".")."'>";
				$ao_object[$ai_totrows][3]="<input name=txtedadmin".$ai_totrows." type=text id=txtedadmin".$ai_totrows." class=sin-borde maxlength=2 size=8 onKeyUp='javascript: ue_validarnumero(this);' style='text-align:center' value='".$ls_edadmin."'>";		
				$ao_object[$ai_totrows][4]="<input name=txtedadmax".$ai_totrows." type=text id=txtedadmax".$ai_totrows." class=sin-borde maxlength=3 size=8 onKeyUp='javascript: ue_validarnumero(this);' style='text-align:center' onChange='javascript:valida_edad(this,txtedadmin".$ai_totrows.")';  value='".$ls_edadmax."'>";
				$ao_object[$ai_totrows][5]="<select name=cmbsexper".$ai_totrows." id=cmbsexper".$ai_totrows."><option value=''>--Seleccione--</option>       <option value='F' ".$la_sexo[0]."  > Femenino</option>
		        <option value='M' ".$la_sexo[1]." > Masculino</option></select>";
				$ao_object[$ai_totrows][6]="  <select name=cmbhcm".$ai_totrows." id=cmbhcm".$ai_totrows." >	
				<option value='1' ".$la_hcm[0].">Si</option>
				<option value='0' ".$la_hcm[1]." >No</option></select> ";
				$ao_object[$ai_totrows][7]="<select name=cmbnexfam".$ai_totrows." id=cmbnexfam".$ai_totrows."  ".$ls_disable.">
				  <option value='' selected>--Seleccione--</option>
				  <option value='C' ".$la_nexo[0]." >Conyuge</option>
				  <option value='H' ".$la_nexo[1]."  >Hijo</option>
				  <option value='P' ".$la_nexo[2]."  >Progenitor</option>
				  <option value='E' ".$la_nexo[3]."  >Hermano</option>
				</select>";
			$ao_object[$ai_totrows][8]="<input name=txtprima".$ai_totrows." type=text id=txtprima".$ai_totrows." class=sin-borde size=8 onKeyPress='return(ue_formatonumero(this,".$punto.", ".$coma.",event))'  value='".number_format($li_prima,2,",",".")."'>";
			$ao_object[$ai_totrows][9]="<input name=txtaporempre".$ai_totrows." type=text id=txtaporempre".$ai_totrows." class=sin-borde size=8 onKeyPress='return(ue_formatonumero(this,".$punto.", ".$coma.",event))' value='".number_format($li_aporempre,2,",",".")."' >";
			$ao_object[$ai_totrows][10]="<input name=txtaporemple".$ai_totrows." type=text id=txtaporemple".$ai_totrows." class=sin-borde size=8 onKeyPress='return(ue_formatonumero(this,".$punto.", ".$coma.",event))' value='".number_format($li_aporemple,2,",",".")."'>";
			$ao_object[$ai_totrows][11]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
			$ao_object[$ai_totrows][12]="<a href=javascript:uf_delete_dt(".$ai_totrows.");   align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";				
							
			}
			$this->io_sql->free_result($rs_data);
			}
		else 
		 {
		    $this->io_msg->message("No hay detalles asociados a esa Deducción de Seguro.");
	 		$ai_totrows=0;	
			
		
		  }
		  return $lb_valido;
		}
		
	}
	

//FUNCIONES PARA GUARDAR Y ELIMINAR LAS DEDUCCIONES DE SEGURO

function uf_srh_guardar_configuracion_deduccion($ao_deduccion, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_configuracion_deduccion																	
	  	//      Argumento: $ao_requerimiento    // arreglo con los datos de la deduccion 							
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description:Funcion que inserta o modifica un detalle de las deducciones  la tabla 
		//                 srh_dt_tipodeduccion    
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 08/04/2008							Fecha Última Modificación: 08/04/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  	 //Borramos los registros anteriores 
	$this-> uf_srh_eliminar_dt_configuracion_deduccion($ao_deduccion->codtipded, $aa_seguridad);
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_det = 0;
	while (($li_det < count($ao_deduccion->deduccion)) &&
	       ($lb_guardo))
	{
	  $lb_guardo = $this->uf_srh_guardar_dt_configuracion_deduccion($ao_deduccion->deduccion[$li_det], $aa_seguridad);
	  $li_det++;
	}
	
	return $lb_guardo;  
  }
	
	


function uf_srh_guardar_dt_configuracion_deduccion($ao_deduccion, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_dt_configuracion_deduccion															     														
		//      Argumento: $ao_deduccion    // arreglo con los datos de los detalle de las deducciones 				
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica un detalle de las deducciones  la tabla 
		//                 srh_dt_tipodeduccion           
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 08/04/2008							Fecha Última Modificación: 08/04/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	 $ao_deduccion->sueldo=str_replace(".","",$ao_deduccion->sueldo);
	 $ao_deduccion->sueldo=str_replace(",",".",$ao_deduccion->sueldo);
	 
	 $ao_deduccion->prima=str_replace(".","",$ao_deduccion->prima);
	 $ao_deduccion->prima=str_replace(",",".",$ao_deduccion->prima);
	 
	 $ao_deduccion->aporempre=str_replace(".","",$ao_deduccion->aporempre);
	 $ao_deduccion->aporempre=str_replace(",",".",$ao_deduccion->aporempre);
	 
	 $ao_deduccion->aporemple=str_replace(".","",$ao_deduccion->aporemple);
	 $ao_deduccion->aporemple=str_replace(",",".",$ao_deduccion->aporemple);	
		
	 	 
	  $ls_sql = "INSERT INTO srh_dt_tipodeduccion (codtipded,coddettipded, titular, suelbene,edadmin, edadmax, sexbene, nexfam, hcm, valprim, aporempre, aporemple, codemp) ".	  
	            " VALUES ('$ao_deduccion->codtipded','$ao_deduccion->coddettipded','$ao_deduccion->titular','$ao_deduccion->sueldo','$ao_deduccion->edadmin','$ao_deduccion->edadmax','$ao_deduccion->sexo','$ao_deduccion->nexo','$ao_deduccion->hcm','$ao_deduccion->prima','$ao_deduccion->aporempre','$ao_deduccion->aporemple','".$this->ls_codemp."')";

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
	     		$ls_descripcion ="Insertó el detalle de deducción  ".$ao_deduccion->coddettipded;				
	    		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"], 
	   							$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->tipodeduccion MÉTODO->uf_srh_guardar_dt_configuracion_deduccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
  
  
 
function uf_srh_eliminar_dt_configuracion_deduccion($as_codtipded, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_dt_configuracion_deduccion																
		//        access:  public (sigesp_srh_dt_tipodeduccion)														
		//      Argumento: $as_codtipded        // código del tipo de deducciónm 
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																				    
		//    Description: Funcion que elimina un detalle de deducción  en la tabla srh_dt_tipodeduccion   		//	        // Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 20/02/2008							Fecha Última Modificación: 20/02/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_dt_tipodeduccion ".
	          " WHERE codtipded='$as_codtipded'  AND codemp='".$this->ls_codemp."'";
			  

	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->tipodeduccion MÉTODO->uf_srh_eliminar_dt_configuracion_deduccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el detalles de la deducción ".$as_codtipded;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
			
	
	return $lb_borro;
	
  }

///--------------------------------------------------------------------------------------------------------------------------------
    function uf_srh_buscar_deduccion($as_codper,$as_codtipded,$as_tipo,$as_sueldo, $as_edad, $as_sexo,&$as_valor) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_deduccion
		//	    Arguments: as_codper      // código de la deducción
		//                 as_codtipded  // código de la deducción 
		//				   as_tipo      // 1 si es deduccion del personal y 2 si es aporte patronal
		//				   as_valor    // valor de la deducción
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene el valor de una deducción de personal 
		//     Creado Por: Maria Beatriz Unda		
		// Fecha Creación: 29/05/2008							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT srh_dt_tipodeduccion.suelbene, srh_dt_tipodeduccion.edadmin, srh_dt_tipodeduccion.edadmax,".
				" srh_dt_tipodeduccion.valprim,srh_dt_tipodeduccion.aporempre,srh_dt_tipodeduccion.aporemple ". 
				" FROM sno_personaldeduccion, srh_dt_tipodeduccion ".
				" WHERE sno_personaldeduccion.codemp='".$this->ls_codemp."'".
				" AND sno_personaldeduccion.codper='".$as_codper."'".
				" AND sno_personaldeduccion.codtipded='".$as_codtipded."'  ".
				" AND sno_personaldeduccion.codemp=srh_dt_tipodeduccion.codemp  ".				
				" AND sno_personaldeduccion.codtipded = srh_dt_tipodeduccion.codtipded   ".	
				" AND sno_personaldeduccion.coddettipded = srh_dt_tipodeduccion.coddettipded ".		
			    " ORDER BY sno_personaldeduccion.codtipded "; 
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->tipodeduccion MÉTODO->uf_srh_buscar_deduccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
		 $num=$this->io_sql->num_rows($rs_data); 
          $as_valor=0; 
		  if ($num!=0) 
		  {			
			while($row=$this->io_sql->fetch_row($rs_data))
			{   
			    $ls_sueldo=trim ($row["suelbene"]);
				$ls_edadmin=$row["edadmin"];
				$ls_edadmax=$row["edadmax"];					
				$li_prima=$row["valprim"];
				$li_aporempre=$row["aporempre"];
				$li_aporemple=$row["aporemple"];
				
				 if (($as_sueldo >= $ls_sueldo)&&($as_edad >= $ls_edadmin)&&($as_edad <= $ls_edadmax))
				 {					  
					  switch($as_tipo)
					  {
						case "1":
							 $as_valor=$as_valor+  round ($li_prima * $li_aporemple)/100;
							break;
						case "2":						    
							 $as_valor=$as_valor+  round ($li_prima * $li_aporempre)/100;
							break;
					  }
				}
				 
			
			} // Cierre del While
		}		
	}
	return $lb_valido;
}//fin de function uf_srh_buscar_deduccion
//--------------------------------------------------------------------------------------------------------------------------------
      function calcular_edad($fecha_nac,$fecha_hasta)
	  {  	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: calcular_edad
		//	    Arguments: fecha_nac  // fecha de nacimiento
		//                 fecha_hasta	 fecha hasta 	 
		//	      Returns: anos
		//	  Description: Funcion que obtiene la edad de una persona dada una fecha de nacimiento
		//     Creado Por: Maria Beatriz Unda		
		// Fecha Creación: 29/05/2008							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		  $c = date("Y",$fecha_nac);	   
		  $b = date("m",$fecha_nac);	  
		  $a = date("d",$fecha_nac); 	  
		  $anos = date("Y",$fecha_hasta)-$c; 
	   
			  if(date("m",$fecha_hasta)-$b > 0){
		  
			  }elseif(date("m",$fecha_hasta)-$b == 0){
		 
			  if(date("d",$fecha_nac)-$a <= 0)
			  {		  
			     $anos = $anos-1;	  
			  }
		  
			  }else
			  {		  
			     $anos = $anos-1;		  
			  }  
		  return $anos;	 
      }// fin de function calcular_edad($fecha_nac,$fecha_hasta)
//-------------------------------------------------------------------------------------------------------------------------------
      function uf_srh_buscar_deduccion_familiar($as_codper,$as_codtipded,$as_tipo,$as_sueldo,$as_fecha_has, &$as_valor) 
		{
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//	     Function: uf_srh_buscar_deduccion_familiar
			//	    Arguments: as_codper      // código de la deducción
			//                 as_codtipded  // código de la deducción 
			//				   as_tipo      // 1 si es deduccion del personal y 2 si es aporte patronal
			//                 as_sueldo    // sueldo del empleado que posee nexo con el familiar
			//				   as_valor    // valor de la deducción
			//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
			//	  Description: Funcion que obtiene el valor de una deducción del familiar
			//     Creado Por: Ing. Jennifer Rivero	
			// Fecha Creación: 29/05/2008							Fecha Última Modificación : 
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$lb_valido=true;
			$ls_sql=" SELECT srh_dt_tipodeduccion.suelbene, srh_dt_tipodeduccion.edadmin, srh_dt_tipodeduccion.edadmax,".
					" srh_dt_tipodeduccion.valprim,srh_dt_tipodeduccion.aporempre,srh_dt_tipodeduccion.aporemple, ".
					"      (SELECT sno_familiar.fecnacfam from sno_familiar          ".
					"        WHERE sno_familiar.codemp= sno_familiardeduccion.codemp ".
					"		   AND sno_familiar.codper=sno_familiardeduccion.codper  ".
					"		   AND sno_familiar.cedfam=sno_familiardeduccion.cedfam) as fecha_nac ".					
				    "         FROM sno_familiardeduccion, srh_dt_tipodeduccion        ".
				    "        WHERE sno_familiardeduccion.codemp='".$this->ls_codemp."'".
					"		   AND sno_familiardeduccion.codper='".$as_codper."'".
					"		   AND sno_familiardeduccion.codtipded='".$as_codtipded."'". 
					"		   AND sno_familiardeduccion.codemp=srh_dt_tipodeduccion.codemp ". 				
					"		   AND sno_familiardeduccion.codtipded = srh_dt_tipodeduccion.codtipded ".
					"          AND sno_familiardeduccion.coddettipded = srh_dt_tipodeduccion.coddettipded ".				
					"   	 ORDER BY sno_familiardeduccion.coddettipded ";		
		
		   $rs_data=$this->io_sql->execute($ls_sql);
		   if($rs_data===false)
			{
				$lb_valido=false;
				$this->io_msg->message("CLASE->tipodeduccion MÉTODO->uf_srh_buscar_deduccion_familiar ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			}
			else
			{
			 $nro_filas=$this->io_sql->num_rows($rs_data);
			 if ($nro_filas>0)
			   {
			    
					 while($row=$this->io_sql->fetch_row($rs_data))
					{							        
						$ls_sueldobene=$row["suelbene"];
						$ls_edadmin=$row["edadmin"];
						$ls_edadmax=$row["edadmax"];
						$ls_sexoben=$row["sexbene"];
						$ls_nexo=$row["nexfam"];
						$ls_hcm=$row["hcm"];////de la tabla dt_tipodeduccion
						$ls_valorprima=$row["valprim"];
						$apor_empresa=$row["aporempre"];
						$apor_empleado=$row["aporemple"];						
						$fechanac_familiar=$row["fecha_nac"];
						$edad_familiar=$this->calcular_edad(strtotime($fechanac_familiar),strtotime($as_fecha_has));			
												 
						if (($as_sueldo>=$ls_sueldobene)&&($edad_familiar>=$ls_edadmin)&&($edad_familiar<=$ls_edadmax))
						{
							switch($as_tipo)
							  {
								case "1":
									 $as_valor=$as_valor+  round ($ls_valorprima * $apor_empleado)/100;
									break;
								case "2":
									 $as_valor=$as_valor+  round ($ls_valorprima * $apor_empresa)/100;
									break;
							  }
						 
					   }
											  
					}///fin del while
				}//fin del if ($nro_filas>0)
			 }//fin del else
			 
	 return $lb_valido;			    
	}// fin de  uf_srh_buscar_deduccion_familiar
//---------------------------------------------------------------------------------------------------------------------------------


function uf_srh_buscar_detalles_deducciones($as_codtipded, $as_tipo, $as_nexfam, $as_sexper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_detalles_deducciones
		//	    Arguments: as_codtipded  // código de la deducción 
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de una deducción 
		// Fecha Creación: 07/04/2007							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";	
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_orden ="CONVERT(coddettipded USING smallint) ";
				break;
			case "POSTGRES":
				$ls_orden = " ORDER BY (CAST(coddettipded AS smallint)) ";
				break;					
					
		}
		
		switch($as_tipo)
		{
			case "dedper":
				$ls_criterio =" AND titular ='S' ";
				break;
			case "dedfam":
				$ls_criterio = " AND titular ='N' ";
				break;					
					
		}
		if ($as_sexper!="")
		{
			$ls_criterio =$ls_criterio." AND sexbene ='".trim($as_sexper)."' ";
		}
		if ($as_nexfam!="")
		{
			$ls_criterio =$ls_criterio." AND nexfam ='".trim($as_nexfam)."' ";
		}
		
		$ls_sql="SELECT * ". 
				"  FROM srh_dt_tipodeduccion ".
				" WHERE srh_dt_tipodeduccion.codemp='".$this->ls_codemp."'".
				"   AND codtipded='".$as_codtipded."'".$ls_criterio.$ls_orden;			
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->tipodeduccion MÉTODO->uf_srh_buscar_detalles_deducciones ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
		 
			 $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);	
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				
				$ls_coddettipded=$row["coddettipded"];
				$ls_titular=$row["titular"];				
				$ls_sueldo=number_format(trim($row["suelbene"]),2,',','.');
				$ls_edadmin=$row["edadmin"];
				$ls_edadmax=$row["edadmax"];
				$ls_sexo=$row["sexbene"];				
				$ls_hcm=$row["hcm"];				
				$ls_nexo=$row["nexfam"];				
				$li_prima=number_format($row["valprim"],2,',','.');
				$li_aporempre=number_format($row["aporempre"],2,',','.');
				$li_aporemple=number_format($row["aporemple"],2,',','.');
						
				 switch($ls_sexo)
				{
					case "F":
						$ls_sexo="Femenino";
						break;
					case "M":
						$ls_sexo="Masculino";
						break;
				}
			   switch($ls_nexo)
				{
					case "C":
						$ls_nexo="Conyugue";
						break;
					case "H":
						$ls_nexo="Hijo";
						break;
					case "P":
						$ls_nexo="Padre";
						break;
					case "E":
						$ls_nexo="Hermano";
						break;
					default : 
						$ls_nexo="Titular";
						break;
				}
				switch($ls_titular)
				{
					case "S":
						$ls_titular="Si";
						break;
					case "N":
						$ls_titular="No";						
						break;
				}
				switch($ls_hcm)
				{
					case "1":
						$ls_hcm="Si";
						break;
					case "0":
						$ls_hcm="No";
						break;
				}
				
				
				$row_ = $team->appendChild($dom->createElement('row'));
				$row_->setAttribute("id",$ls_coddettipded);
				$cell = $row_->appendChild($dom->createElement('cell'));   
				$cell->appendChild($dom->createTextNode($ls_coddettipded." ^javascript:aceptar(\"$ls_coddettipded\");^_self"));
				
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_titular));												
				$row_->appendChild($cell);

				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_sueldo));												
				$row_->appendChild($cell);
				
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_edadmin));												
				$row_->appendChild($cell);
		
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_edadmax));												
				$row_->appendChild($cell);
				
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_sexo));												
				$row_->appendChild($cell);
				
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_hcm));												
				$row_->appendChild($cell);
				
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_nexo));												
				$row_->appendChild($cell);
				
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($li_prima));												
				$row_->appendChild($cell);
				
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($li_aporempre));												
				$row_->appendChild($cell);
				
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($li_aporemple));												
				$row_->appendChild($cell);
		
				
							
							
			}
			$this->io_sql->free_result($rs_data);		
		 	return $dom->saveXML();
		}
		
	}// end function uf_srh_buscar_detalles_deducciones



		
			
			


}// end   class sigesp_srh_c_tipodeduccion
?>