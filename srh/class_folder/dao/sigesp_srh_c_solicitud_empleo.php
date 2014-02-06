<?php

class sigesp_srh_c_solicitud_empleo
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function sigesp_srh_c_solicitud_empleo($path)
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
		//         Access: public (sigesp_srh_p_solicitud_empleo)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de una solicitud de empleo
		//    Description: Funcion que genera un código nuevo de una solicutd de empleo
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:16/01/2008							Fecha Última Modificación:16/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(nrosol) AS numero FROM srh_solicitud_empleo ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_nrosol = $la_datos["numero"][0]+1;
    $ls_nrosol = str_pad ($ls_nrosol,10,"0","left");
    return $ls_nrosol;
  } 
	
 function getCedPersonal($as_cedper,&$ao_datos="")
  	  {
		  
		     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: getCodPersonal																                    
		//      Argumento: $as_cedper   //  cédigo del personal										                        
		//                 $$ao_datos   //  arreglo con datos del personal                                         
		//	      Returns: Retorna un XML  																						
		//    Description: Funcion busca un personal en la tabla sno_personal  dado la cédula del personal                    
		//	   Creado Por: Ing. Luiser Blanco																				    						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  
		  
		    $lb_existe=false;
			$ls_numsol="";
			$ls_sql = " SELECT nrosol FROM srh_solicitud_empleo ".
					  " WHERE codemp='". $this->ls_codemp."'".
					  " AND  cedsol = '$as_cedper'";
					
				
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
					$this->io_msg->message("CLASE->solicitud_empleo MÉTODO->getCedPersonal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
				
			}
			else
			{
					
					if($row=$this->io_sql->fetch_row($rs_data))
					{
						$lb_existe=true;
						$ls_numsol=$row['nrosol'];
					}
					
					$this->io_sql->free_result($rs_data);
			}
			return array($lb_existe,$ls_numsol);
	  }	// end function getPersonal
	  
  	
 	
	
  function uf_srh_getsolicitud_empleo($ps_nrosol,&$pa_datos="")
  {  
  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getsolicitud_empleo																			//
		//         access: public (sigesp_srh_solicitud_empleo)														            //
		//      Argumento: $ps_nrosol    // numero de la solicitud de empleo													//
		//                 $pa_datos    //  arreglos donde  se cargaran lo datos de la consulta									//
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que realiza una busqueda de una solicitud de empleo en la tabla srh_solicitud_empleo         //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 26/10/2007							Fecha Última Modificación: 26/10/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  
  
    $ls_sql = "SELECT * FROM srh_solicitud_empleo ".
	          "WHERE nrosol = '$ps_nrosol'";
    $lb_hay = $this->seleccionar($ls_sql, $pa_datos);
    return $lb_hay;
  }
  
  
  
 
  
function uf_srh_guardarsolicitud_empleo($po_solicitud,$ps_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarsolicitud_empleo																		//
		//         access: public (sigesp_srh_solicitud_empleo)														            //
		//      Argumento: $po_solicitud    // arreglo con los datos de la solicitud										    //
		//                 $ps_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              //
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta o modifica una solicitud de empleo en la tabla srh_solicitud_empleo              //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 26/10/2007							Fecha Última Modificación: 26/10/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$as_nrosol=$po_solicitud->nrosol;
	
  	if ($ps_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	 $po_solicitud->fecsol=$this->io_funcion->uf_convertirdatetobd($po_solicitud->fecsol);
	 $po_solicitud->fecnacper=$this->io_funcion->uf_convertirdatetobd($po_solicitud->fecnacper);
	 
	  $ls_sql = "UPDATE srh_solicitud_empleo SET ".
	            "cedsol = '$po_solicitud->cedper', ".
	            "fecsol = '$po_solicitud->fecsol', ".
	            "apesol = '$po_solicitud->apeper', ".
	            "nomsol = '$po_solicitud->nomper', ".
	            "sexsol = '$po_solicitud->sexsol', ".
	            "fecnac = '$po_solicitud->fecnacper', ".
	            "telhab = '$po_solicitud->telhab', ".
	            "email  = '$po_solicitud->email', ".
	            "codpro = '$po_solicitud->codpro', ".
	            "carfam = '$po_solicitud->carfam', ".
	            "codpar = '$po_solicitud->codpar', ".
				"codmun = '$po_solicitud->codmun', ".
				"codest = '$po_solicitud->codest', ".
	            "dirsol = '$po_solicitud->dirper', ".
	            "comsol = '$po_solicitud->comsol', ".
	            "codniv = '$po_solicitud->codniv', ".
	            "telmov = '$po_solicitud->telmov', ".
				"nacsol = '$po_solicitud->nacper', ".
				"codpai = '$po_solicitud->codpai', ".
				"nivacasol = '$po_solicitud->nivaca', ".
				"pessol = '$po_solicitud->pesper', ".
				"estasol = '$po_solicitud->estaper', ".
	            "estciv = '$po_solicitud->estciv' ".
	            "WHERE nrosol= '$po_solicitud->nrosol' AND codemp='".$this->ls_codemp."'" ;
		
				
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó la solicitud de empleo ".$as_nrosol;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	  $po_solicitud->fecsol=$this->io_funcion->uf_convertirdatetobd($po_solicitud->fecsol);
	  $po_solicitud->fecnacper=$this->io_funcion->uf_convertirdatetobd($po_solicitud->fecnacper);
	
	  /*/if (($po_solicitud->codpar=' ')&&($po_solicitud->codmun=' ')&&($po_solicitud->codest=' ')&&($po_solicitud->codpai=' '))
	  {
	  	$po_solicitud->codpar=$po_solicitud->codmun=$po_solicitud->codest=$po_solicitud->codpai='---';
	  }/*/
	  $ls_sql = "INSERT INTO srh_solicitud_empleo (nrosol, cedsol, fecsol, apesol, nomsol, sexsol, fecnac, telhab, email, codpro, carfam, codpar, codmun, codest, dirsol, comsol, codniv, telmov, estciv, codpai, nivacasol, nacsol, estasol, pessol, codemp) ".	  
	            "VALUES ('$po_solicitud->nrosol','$po_solicitud->cedper','$po_solicitud->fecsol','$po_solicitud->apeper','$po_solicitud->nomper','$po_solicitud->sexsol','$po_solicitud->fecnacper','$po_solicitud->telhab','$po_solicitud->email','$po_solicitud->codpro','$po_solicitud->carfam','$po_solicitud->codpar','$po_solicitud->codmun','$po_solicitud->codest','$po_solicitud->dirper','$po_solicitud->comsol','$po_solicitud->codniv','$po_solicitud->telmov','$po_solicitud->estciv','$po_solicitud->codpai', '$po_solicitud->nivaca', '$po_solicitud->nacper', '$po_solicitud->estaper', '$po_solicitud->pesper',  '".$this->ls_codemp."')";
						
	
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la solicitud de empleo ".$as_nrosol;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->solicitud_empleo MÉTODO->uf_srh_guardarsolicitud_empleo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				$this->io_sql->commit();
		}
		
		
	//Guardamos los datos del detalle de la solicitud
	///$lb_guardo = $this->guardarDetalles_Solicitud($po_solicitud, $aa_seguridad);
		
	return $lb_guardo;
  }
	
	
	
function guardarDetalles_Solicitud ($po_solicitud, $aa_seguridad)
  {
    //Borramos los registros anteriores 
	$this-> uf_srh_eliminar_dt_solicitud_empleo($po_solicitud->nrosol, $aa_seguridad);
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_area = 0;
	while (($li_area < count($po_solicitud->area_d)) &&
	       ($lb_guardo))
	{
	  $lb_guardo = $this-> uf_srh_guardar_dt_solicitud_empleo($po_solicitud->area_d[$li_area], $aa_seguridad);
	  $li_area++;
	}
	
	return $lb_guardo;    
  }

	
	
	
function uf_srh_eliminarsolicitud_empleo($ps_nrosol, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarsolicitud_empleo																		//
		//        access:  public (sigesp_srh_solicitud_empleo)														            //
		//      Argumento: $ps_nrosol        // numero de la solicitud de empleo										        //
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que elimina una solicitud de empleo en la tabla srh_solicitud_empleo                         //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 26/10/2007							Fecha Última Modificación: 26/10/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   //  $this-> uf_srh_eliminar_dt_solicitud_empleo($ps_nrosol, $aa_seguridad);
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_solicitud_empleo ".
	          "WHERE nrosol = '$ps_nrosol'   AND codemp='".$this->ls_codemp."'";
			  
			  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->solicitud_empleo MÉTODO->uf_srh_eliminarsolicitud_empleo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el solicitud de empleo ".$ps_nrosol;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
	return $lb_borro;
  }
	
	
	
	
function uf_srh_buscar_solicitud_empleo($as_nrosol,$as_cedper,$as_apeper,$as_nomper,$as_fecsol1, $as_fecsol2,$as_tipo,$as_tipocaja)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_solicitud_empleo																		//
		//         access: public (sigesp_srh_solicitud_empleo)												                    //
		//      Argumento: $as_nrosol   //  numero de la solicitud										                        //
		//                 $as_cedper   //  cedula del solicitante                                                              //
		//                 $as_apeper   //  apellido del solicitante                                                            //
		//                 $as_nomper   //  nombre del solicitente                                                              //
		//                 $as_fecsol   //  fecha de la solicitud                                                               //
		//	      Returns: Retorna un XML  																						//
		//    Description: Funcion busca una solicitud de empleo en la tabla srh_solicitud_empleo y crea un XML para mostrar    //
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 26/10/2007							Fecha Última Modificación: 26/10/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	   
			
		$as_fecsol1=$this->io_funcion->uf_convertirdatetobd($as_fecsol1);
		$as_fecsol2=$this->io_funcion->uf_convertirdatetobd($as_fecsol2);	
		$lb_valido=true;				
		$ls_sql= "SELECT srh_solicitud_empleo.*,srh_nivelseleccion.denniv, sno_profesion.despro, ".
		        "        (SELECT despai FROM sigesp_pais".
				"          WHERE srh_solicitud_empleo.codpai=sigesp_pais.codpai)AS despai, ".
				"        (SELECT desest FROM sigesp_estados ".
				"          WHERE srh_solicitud_empleo.codpai=sigesp_estados.codpai".
				"            AND srh_solicitud_empleo.codest=sigesp_estados.codest)AS desest,  ".
                "        (SELECT denmun FROM sigesp_municipio ".
				"          WHERE srh_solicitud_empleo.codpai=sigesp_municipio.codpai".
				"            AND srh_solicitud_empleo.codest=sigesp_municipio.codest".
				"            AND srh_solicitud_empleo.codmun=sigesp_municipio.codmun) AS denmun,".
				"        (SELECT denpar FROM sigesp_parroquia".
				"          WHERE srh_solicitud_empleo.codpai=sigesp_parroquia.codpai".
				"            AND srh_solicitud_empleo.codest=sigesp_parroquia.codest".
				"            AND srh_solicitud_empleo.codmun=sigesp_parroquia.codmun".
				"            AND srh_solicitud_empleo.codpar=sigesp_parroquia.codpar) AS denpar".
				" FROM srh_solicitud_empleo, srh_nivelseleccion,sno_profesion".
				" WHERE srh_solicitud_empleo.codemp='".$this->ls_codemp."' ".
				"   AND nrosol like '$as_nrosol' ".
			    "   AND fecsol between  '".$as_fecsol1."' AND '".$as_fecsol2."' ".
				"   AND cedsol like '$as_cedper' ".
				"   AND nomsol like '$as_nomper' ".
				"   AND apesol like '$as_apeper' ".	
				"   AND srh_nivelseleccion.codemp = srh_solicitud_empleo.codemp ".
				"   AND srh_nivelseleccion.codniv = srh_solicitud_empleo.codniv ".
				"   AND sno_profesion.codemp = srh_solicitud_empleo.codemp ".
				"   AND sno_profesion.codpro = srh_solicitud_empleo.codpro ".
    		    " ORDER BY nrosol"; 

	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->solicitud_empleo MÉTODO->uf_srh_buscar_solicitud_empleo( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
					$ls_nrosol=$row["nrosol"];
					$ls_cedper=$row["cedsol"];
					$ls_fecsol=$this->io_funcion->uf_formatovalidofecha($row["fecsol"]);
				    $ls_fecsol=$this->io_funcion->uf_convertirfecmostrar($ls_fecsol);
					$ls_apeper =trim (htmlentities  ($row["apesol"]));
					$ls_nomper= trim (htmlentities ($row["nomsol"]));
					$ls_sexsol=trim ($row["sexsol"]);
					$ls_fecnac=$this->io_funcion->uf_formatovalidofecha($row["fecnac"]);
				    $ls_fecnac=$this->io_funcion->uf_convertirfecmostrar($ls_fecnac);
					$ls_telhab=$row["telhab"];
					$ls_email=htmlentities($row["email"]);
					$ls_codpro=$row["codpro"];
					$ls_codpar=trim ($row["codpar"]);
					$ls_denpro= trim (htmlentities   ($row["despro"]));
					$ls_carfam=$row["carfam"];
					$ls_denmun=trim($row["codmun"]);
					$ls_denest=trim ($row["codest"]);
					$ls_dirper= trim (htmlentities  ($row["dirsol"]));
					$ls_comsol=trim (htmlentities ($row["comsol"]));
					$ls_codniv=$row["codniv"];
					$ls_denniv=trim (htmlentities  ($row["denniv"]));
					$ls_telmov=$row["telmov"];
					$ls_estciv=$row["estciv"];
					$ls_codpai=trim ($row["codpai"]);
					$ls_nivaca=$row["nivacasol"];
					$ls_pesper=trim (trim ($row["pessol"]));
					$ls_estaper=$row["estasol"]; 
					$ls_nacper=trim ($row["nacsol"]);
					
		            $row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['nrosol']);
					$cell = $row_->appendChild($dom->createElement('cell')); 
					
					switch ($as_tipo)
				    {
					  case  "M":
					  
					    $ls_nrodestino="txtnrosol";
						$ls_ceddestino="txtcedper";
						$ls_fecsoldestino="txtfecsol";
						$ls_apedestino="txtapeper";
						$ls_fecnacperdestino="txtfecnacper";
						$ls_nomdestino="txtnomper";
						$ls_sexdestino="cmbsexper";
						$ls_telhdestino="txttelhabper";
						$ls_emadestino="txtcoreleper";
						$ls_codprodestino="txtcodpro";
						$ls_denprodestino="txtdespro";
						$ls_cardestino="txtcarfam";
						$ls_codpardestino="cmbcodpar";
						$ls_codmundestino="cmbcodmun";
						$ls_codestdestino="cmbcodest";
						$ls_dirdestino="txtdirper";
						$ls_comdestino="txtcomsol";
						$ls_codnivdestino="txtcodniv";
						$ls_dennivdestino="txtdenniv";
						$ls_telmdestino="txttelmovper";
						$ls_estdestino="cmbedocivper";
						
						$ls_codpaidestino="cmbcodpai";
						$ls_nivacadestino="cmbnivacaper";
						$ls_pesperdestino="txtpesper";
						$ls_estaperdestino="txtestaper";
						$ls_nacperdestino="cmbnacper";
					   $cell->appendChild($dom->createTextNode($row['nrosol']." ^javascript:aceptar(\"$ls_nrosol\", \"$ls_cedper\", \"$ls_fecsol\", \"$ls_apeper\", \"$ls_nomper\",\"$ls_sexsol\", \"$ls_fecnac\",\"$ls_telhab\", \"$ls_email\",\"$ls_codpro\", \"$ls_carfam\", \"$ls_codpar\",\"$ls_dirper\", \"$ls_comsol\", \"$ls_codniv\", \"$ls_telmov\", \"$ls_estciv\",  \"$ls_nrodestino\", \"$ls_ceddestino\", \"$ls_fecsoldestino\", \"$ls_apedestino\", \"$ls_fecnacperdestino\", \"$ls_nomdestino\", \"	$ls_sexdestino\", \"$ls_telhdestino\",\"$ls_emadestino\", \"$ls_codprodestino\", \"$ls_cardestino\", \"$ls_codpardestino\", \"$ls_dirdestino\", \"$ls_comdestino\", \"$ls_codnivdestino\",   \"$ls_telmdestino\", \"$ls_fecsoldestino\", \"$ls_estdestino\", \"$ls_dennivdestino\", \"$ls_denniv\",  \"$ls_codmundestino\", \"$ls_denmun\", \"$ls_codestdestino\", \"$ls_denest\", \"$ls_denprodestino\",  \"$ls_denpro\", \"$ls_codpai\", \"$ls_nivaca\", \"$ls_pesper\", \"$ls_estaper\", \"$ls_nacper\", \"$ls_codpaidestino\", \"$ls_nivacadestino\", \"$ls_pesperdestino\", \"$ls_estaperdestino\", \"$ls_nacperdestino\" );^_self"));
					  	  			  	
					  				 
					  break;
					  
					  case  "C":
					  
					    $ls_nrodestino="txtnrosol";
						$ls_ceddestino="txtcedper";
						$ls_fecsoldestino="txtfecsol";
						$ls_apedestino="txtapeper";
						$ls_fecnacperdestino="txtfecnacper";
						$ls_nomdestino="txtnomper";
						$ls_sexdestino="cmbsexper";
						$ls_telhdestino="txttelhabper";
						$ls_emadestino="txtcoreleper";
						$ls_codprodestino="txtcodpro";
						$ls_denprodestino="txtdespro";
						$ls_cardestino="txtcarfam";
						$ls_codpardestino="cmbcodpar";
						$ls_codmundestino="cmbcodmun";
						$ls_codestdestino="cmbcodest";
						$ls_dirdestino="txtdirper";
						$ls_comdestino="txtcomsol";
						$ls_codnivdestino="txtcodniv";
						$ls_dennivdestino="txtdenniv";
						$ls_telmdestino="txttelmovper";
						$ls_estdestino="cmbedocivper";
						
						$ls_codpaidestino="cmbcodpai";
						$ls_nivacadestino="cmbnivacaper";
						$ls_pesperdestino="txtpesper";
						$ls_estaperdestino="txtestaper";
						$ls_nacperdestino="cmbnacper";
						$ls_codpainac="cmbcodpainac";
						$ls_codestnac="cmbcodestnac";
					   $cell->appendChild($dom->createTextNode($row['nrosol']." ^javascript:aceptar2(\"$ls_nrosol\", \"$ls_cedper\", \"$ls_fecsol\", \"$ls_apeper\", \"$ls_nomper\",\"$ls_sexsol\", \"$ls_fecnac\",\"$ls_telhab\", \"$ls_email\",\"$ls_codpro\", \"$ls_carfam\", \"$ls_codpar\",\"$ls_dirper\", \"$ls_comsol\", \"$ls_codniv\", \"$ls_telmov\", \"$ls_estciv\",  \"$ls_nrodestino\", \"$ls_ceddestino\", \"$ls_fecsoldestino\", \"$ls_apedestino\", \"$ls_fecnacperdestino\", \"$ls_nomdestino\", \"	$ls_sexdestino\", \"$ls_telhdestino\",\"$ls_emadestino\", \"$ls_codprodestino\", \"$ls_cardestino\", \"$ls_codpardestino\", \"$ls_dirdestino\", \"$ls_comdestino\", \"$ls_codnivdestino\",   \"$ls_telmdestino\", \"$ls_fecsoldestino\", \"$ls_estdestino\", \"$ls_dennivdestino\", \"$ls_denniv\",  \"$ls_codmundestino\", \"$ls_denmun\", \"$ls_codestdestino\", \"$ls_denest\", \"$ls_denprodestino\",  \"$ls_denpro\", \"$ls_codpai\", \"$ls_nivaca\", \"$ls_pesper\", \"$ls_estaper\", \"$ls_nacper\", \"$ls_codpaidestino\", \"$ls_nivacadestino\", \"$ls_pesperdestino\", \"$ls_estaperdestino\", \"$ls_nacperdestino\",\"$ls_codpainac\",\"$ls_codestnac\" );^_self"));
					  	  			  	
					  				 
					  break;
					  
					  case "R":
					 
					  if($as_tipocaja=="1")
		         		 {
		        			$ls_nrosoldestino="txtnrosoldes";
		        			$cell->appendChild($dom->createTextNode($row['nrosol']." ^javascript:aceptardesde(\"$ls_nrosol\",\"$ls_nrosoldestino\");^_self"));		        			
		        	     }
		        		elseif($as_tipocaja=="2")
		        		{
		        			$ls_nrosolhasta="txtnrosolhas";
		        			$cell->appendChild($dom->createTextNode($row['nrosol']." ^javascript:aceptarhasta(\"$ls_nrosol\",\"$ls_nrosolhasta\");^_self"));		        			
		        		}					  	
					  				 
					  break;
				    }
		//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------		    
					
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_fecsol));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($row['cedsol']));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(htmlentities  ($row['nomsol']).'  '.htmlentities ($row['apesol'])));												
					$row_->appendChild($cell);			
			
			}
			return $dom->saveXML();
		
			
			
		
		}
      
		
	} // end function buscar_solicitud_empleo
	

//FUNCIONES PARA MANEJAR LOS DETALLES DE LA SOLICITUD DE EMPLEO

function uf_srh_guardar_dt_solicitud_empleo($po_solicitud, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_dt_solicitud_empleo															     	//
		//         access: public (sigesp_srh_dt_solicitud_empleo)														        //
		//      Argumento: $po_solicitud    // arreglo con los datos de los detalle de la solicitud								//
		//                 $ps_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              //
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta el detalle de una solicitud de empleo en la tabla srh_dt_solicitud_empleo 
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 30/10/2007							Fecha Última Modificación: 30/10/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	  $ls_sql = "INSERT INTO srh_dt_solicitudempleo (nrosol,codare, expare, observacion, codemp) ".	  
	            "VALUES ('$po_solicitud->nrosol','$po_solicitud->codare','$po_solicitud->anoexp','$po_solicitud->obs','".$this->ls_codemp."')";
				
				

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el detalle de solicitud de empleo ".$as_nrosol;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->solicitud_empleo MÉTODO->uf_srh_guardar_dt_solicitud_empleo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
	
function uf_srh_eliminar_dt_solicitud_empleo($ps_nrosol, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_dt_solicitud_empleo																	//
		//        access:  public (sigesp_dt_srh_solicitud_empleo)														        //
		//      Argumento: $ps_nrosol         // numero de la solicitud de empleo                                              			 		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                //
		//	      Returns: Retorna un Booleano																				    //
		//    Description: Funcion que elimina una solicitud de empleo en la tabla srh_dt_solicitud_empleo                      //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 30/10/2007							Fecha Última Modificación: 30/10/2007							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_dt_solicitudempleo ".
	          " WHERE nrosol='$ps_nrosol'  AND codemp='".$this->ls_codemp."'"; 
			  
			  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->solicitud_empleo MÉTODO->uf_srh_eliminar_dt_solicitud_empleo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el detalle de solicitud de empleo ".$as_nrosol;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
	return $lb_borro;
	
  }
  
  
   
function uf_srh_load_solicitud_empleo_campos($as_nrosol,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_soliciutd_empleo_campos
		//	    Arguments: $as_nrosol // código de la solicutd de empleo
		//				   $ai_totrows  // total de filas del detalle
		//				   $ao_object  // objetos del detalle
		//	      Returns: $lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de una solictud de empleo
		// Fecha Creación: 27/11/2007							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * ".
				"  FROM srh_dt_solicitudempleo, srh_area ".
				" WHERE srh_dt_solicitudempleo.codemp='".$this->ls_codemp."'".
				"   AND nrosol='".$as_nrosol."'".
				"   AND srh_dt_solicitudempleo.codare = srh_area.codare ".
				" ORDER BY nrosol,srh_dt_solicitudempleo.codare";

			
				 $rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->solicitud_empleo MÉTODO->uf_srh_load_soliciutd_empleo_campos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_codare=$row["codare"];
				$ls_denare=htmlentities ($row["denare"]);
				$ls_anoexp=$row["expare"];
	   			$ls_obs=$row["observacion"];
				
				$ao_object[$ai_totrows][1]="<input name=txtcodare".$ai_totrows."  size=13 id=txtcodare".$ai_totrows." class=sin-borde readonly value=".$ls_codare.">";
				$ao_object[$ai_totrows][2]="<input name=txtdenare".$ai_totrows."  size=35 id=txtdenare".$ai_totrows." class=sin-borde readonly value=".$ls_denare.">";
				$ao_object[$ai_totrows][3]="<input name=txtanoexp".$ai_totrows." size=5 maxlength=3 id=txtanoexp".$ai_totrows." class=sin-borde onKeyUp='javascript: ue_validarnumero(this);' value=".$ls_anoexp.">";
				$ao_object[$ai_totrows][4]="<input name=txtobs".$ai_totrows." size=45 id=txtobs".$ai_totrows." class=sin-borde value=".$ls_obs." >";
				$ao_object[$ai_totrows][5]="<a href=javascript:catalogo_area(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/buscar.gif alt=Buscar width=15 height=15 border=0 align=center></a>";
				$ao_object[$ai_totrows][6]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$ao_object[$ai_totrows][7]="<a href=javascript:uf_delete_dt(".$ai_totrows.");     align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";				
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_evaluacion_pasantia
  
  
}// end   class sigesp_srh_c_solicitud_empleo
?>