<?PHP


class sigesp_srh_c_personal 
{
	var $io_sql;
	var $io_msg;
	var $io_funcion;
	var $io_seguridad;
	var $io_fun_nomina;
	var $io_personalnomina;
	var $ls_codemp;
	var $ls_mensaje="No hay Datos";

	//-----------------------------------------------------------------------------------------------------------------------------------
	public function sigesp_srh_c_personal($path)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_personal
		//		   Access: public (sigesp_snorh_d_personal)
		//	  Description: Constructor de la Clase
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		
		require_once($path."shared/class_folder/sigesp_include.php");
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
		$this->DS=new class_datastore();

	}// end function sigesp_snorh_c_personal
	

/////////////////////////////// FUNCIONES PARA EL MANEJO DEL REGISTRO DE PERSONAL  ///////////////////////////////	
	
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_upload($as_nomfot,$as_tipfot,$as_tamfot,$as_nomtemfot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_upload
		//		   Access: public (sigesp_snorh_d_personal)
		//	    Arguments: as_nomfot  // Nombre Foto
		//				   as_tipfot  // Tipo Foto
		//				   as_tamfot  // Tamaño Foto
		//				   as_nomtemfot  // Nombre Temporal
		//	      Returns: as_nomfot sale vacia si da un error y con el mismo valor si se subio correctamente
		//	  Description: Funcion que sube una foto al servidor
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 22/09/2008		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_tipfot=strtolower($as_tipfot);
		
		if ($as_nomfot!="")
		{
			if (!((strpos($as_tipfot, "gif") || strpos($as_tipfot, "jpeg") || strpos($as_tipfot, "png")) && ($as_tamfot < 900000))) 
			{ 
				$as_nomfot="";
				$this->io_msg->message("El archivo de la foto no es valido.");
			}
			else
			{ 
				if (!((move_uploaded_file($as_nomtemfot, "../../../../sno/fotospersonal/".$as_nomfot))))
				{
					$as_nomfot="";
		        	$this->io_msg->message("CLASE->Personal MÉTODO->uf_upload ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
			}
		}
		return $as_nomfot;	
    }
	
	
	
//--------------------------------------------------------------------------------------------------------------------------------
function uf_srh_guardarPersonal ($ao_personal,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarpersonal																				//
		//         access: public (sigesp_sno_personal)														                    //
		//      Argumento: $ao_personal    // arreglo con los datos del personal										        //
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              //
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta o modifica un personal en la tabla sno_personal                    			    //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 18/01/2008							Fecha Última Modificación: 18/01/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
		if ($ao_personal->codorg=='')
		{$ao_personal->codorg='----------'; }
		
		if ($ao_personal->codger=='')
		{$ao_personal->codger='----------'; }
		
		if ($ao_personal->edocivper=='null')
		{$ao_personal->edocivper='S'; }
				
		if ($ao_personal->nacper=='null')
		{$ao_personal->nacper=""; }
		
		if ($ao_personal->codpainac=='null')
		{$ao_personal->codpainac='058'; }
		
		if ($ao_personal->codestnac=='null')
		{$ao_personal->codestnac='001'; }		
		
		if ($ao_personal->sexper=='null')
		{$ao_personal->sexper=""; }
		
		if ($ao_personal->nivacaper=='null')
		{$ao_personal->nivacaper=""; }
		
		if ($ao_personal->contraper=='null')
		{$ao_personal->contraper=""; }
		
		if ($ao_personal->cenmedper=='null')
		{$ao_personal->cenmedper=""; }
		
		if ($ao_personal->turper=='null')
		{$ao_personal->turper=""; }
		
		if ($ao_personal->tipvivper=='null')
		{$ao_personal->tipvivper=""; }
		
		if ($ao_personal->codpainac=='null')
		{$ao_personal->codpainac=""; }
		
	    if ($ao_personal->codestnac=='null')
		{$ao_personal->codestnac=""; }
		
 	    if ($ao_personal->enviorec=='null')
		{$ao_personal->enviorec="-"; }
		
		if ($ao_personal->estaper =="" )
		{$ao_personal->estaper = 0;}
		
		if ($ao_personal->pesper =="" )
		{$ao_personal->pesper = 0;}
		
		if ($ao_personal->numhijper =="" )
		{$ao_personal->numhijper = 0;}
		
		if ($ao_personal->monpagvivper =="" )
		{$ao_personal->monpagvivper = 0;}
		
			if ($ao_personal->anoservpreper =="" )
		{$ao_personal->anoservpreper = 0;}
		
		if ( $ao_personal->anoservprecont =="" )
		{ $ao_personal->anoservprecont = 0;}
		
		if ( $ao_personal->anoservprefijo =="" )
		{ $ao_personal->anoservprefijo = 0;}
		
		if ( $ao_personal->porcajahoper =="" )
		{ $ao_personal->porcajahoper = 0;}
		
		if ( $ao_personal->anoperobr=="" )
		{ $ao_personal->anoperobr = 0;}
		
		
	  $ao_personal->fecnacper=$this->io_funcion->uf_convertirdatetobd($ao_personal->fecnacper);
	  $ao_personal->fecingadmpubper=$this->io_funcion->uf_convertirdatetobd($ao_personal->fecingadmpubper);
	  $ao_personal->fecingper=$this->io_funcion->uf_convertirdatetobd($ao_personal->fecingper);
	  $ao_personal->fecjubper=$this->io_funcion->uf_convertirdatetobd($ao_personal->fecjubper);
	  $ao_personal->fecreingper=$this->io_funcion->uf_convertirdatetobd($ao_personal->fecreingper);
	  $ao_personal->fecfevid=$this->io_funcion->uf_convertirdatetobd($ao_personal->fecfevid);
	  $ao_personal->feclossfan=$this->io_funcion->uf_convertirdatetobd($ao_personal->feclossfan);
	  $ao_personal->fecsitu=$this->io_funcion->uf_convertirdatetobd($ao_personal->fecsitu);

	if ($ao_personal->fecnacper =="" )
	{$ao_personal->fecnacper = '1900-01-01';}
	
	if ($ao_personal->fecingadmpubper =="" )
	{$ao_personal->fecingadmpubper= '1900-01-01';}
	
	if ($ao_personal->fecingper =="" )
	{$ao_personal->fecingper = '1900-01-01';}
			
			
	if ($ao_personal->fecjubper =="" )
	{$ao_personal->fecjubper = '1900-01-01';}
	
	if ($ao_personal->fecreingper =="" )
	{$ao_personal->fecreingper = '1900-01-01';}
	
	if ($ao_personal->fecfevid =="" )
	{$ao_personal->fecfevid = '1900-01-01';}
	
	if ($ao_personal->feclossfan =="" )
	{$ao_personal->feclossfan = '1900-01-01';}
	
	if ($ao_personal->fecsitu =="" )
	{$ao_personal->fecsitu = '1900-01-01';}
	
	if ($ao_personal->codmun=="")
	{ $ao_personal->codmun='001';  }
	
	if ($ao_personal->codpar=="")
	{ $ao_personal->codpar='001';  }
	
	if ($ao_personal->tipvivper=="")
	{ $ao_personal->tipvivper='0';  }
	
	if ($ao_personal->talzapper=="")
	{ $ao_personal->talzapper=0;  }
	
		
	$as_codper=$ao_personal->codper;
	
  	if ($as_operacion == "modificar")
	{
	  $this->io_sql->begin_transaction();
	
    $ls_sqlfot="";
	if($ao_personal->fotper!="")
	{
	
		
		$ls_sqlfot=", fotper='$ao_personal->fotper' ";
	}
	 
	  $ls_sql = "UPDATE sno_personal SET ".
		"cedper= '$ao_personal->cedper', ".
		"nomper= '$ao_personal->nomper', ".
		"apeper= '$ao_personal->apeper', ".
		"dirper= '$ao_personal->dirper', ".
		"fecnacper= '$ao_personal->fecnacper', ".
		"edocivper= '$ao_personal->edocivper', ".
		"nacper= '$ao_personal->nacper', ".
		"codpai= '$ao_personal->codpai', ".
		"codest= '$ao_personal->codest', ".
		"codmun= '$ao_personal->codmun', ".
		"codpar= '$ao_personal->codpar', ".
		"telhabper= '$ao_personal->telhabper', ".
		"coreleper= '$ao_personal->coreleper', ".
		"telmovper= '$ao_personal->telmovper', ".
		"sexper= '$ao_personal->sexper', ".
		"estaper= '$ao_personal->estaper', ".
		"pesper= '$ao_personal->pesper', ".
		"codpro= '$ao_personal->codpro', ".
		"nivacaper= '$ao_personal->nivacaper', ".
		"codcom= '$ao_personal->codcom', ".
		"codran= '$ao_personal->codran', ".
		"cedbenper= '$ao_personal->cedbenper', ".
		"numhijper= '$ao_personal->numhijper', ".
		"contraper= '$ao_personal->contraper', ".
		"obsper= '$ao_personal->obsper', ".
		"cenmedper= '$ao_personal->cenmedper', ".
		"turper= '$ao_personal->turper', ".
		"horper= '$ao_personal->horper', ".
		"hcmper= '$ao_personal->hcmper', ".
		"tipsanper= '$ao_personal->tipsanper', ".
		"numexpper= '$ao_personal->numexpper', ".
		"tipvivper= '$ao_personal->tipvivper', ".
  		"tenvivper= '$ao_personal->tenvivper', ".
		"monpagvivper= '$ao_personal->monpagvivper', ".
		"cuecajahoper= '$ao_personal->cuecajahoper', ".
		"cajahoper= '$ao_personal->cajahoper', ".
		"cuelphper= '$ao_personal->cuelphper', ".
		"cuefidper= '$ao_personal->cuefidper', ".
		"fecingadmpubper= '$ao_personal->fecingadmpubper', ".
		"anoservpreper= '$ao_personal->anoservpreper', ".
		"fecingper= '$ao_personal->fecingper', ".		
		"codtippersss = '$ao_personal->codtippersss', ".
		"codpainac = '$ao_personal->codpainac', ".
		"codestnac 	= '$ao_personal->codestnac', ".	
		"codunivipladin = '$ao_personal->codunivi' , ".
		"fecjubper = '$ao_personal->fecjubper' ,".
		"fecreingper = '$ao_personal->fecreingper' , ".
		"fecfevid= '$ao_personal->fecfevid', ".
		"enviorec= '$ao_personal->enviorec', ".
		"fecleypen = '$ao_personal->feclossfan', ".
		"codcausa = '$ao_personal->codcausa', ".
		"situacion = '$ao_personal->situacion', ".
		"fecsitu = '$ao_personal->fecsitu', ".
		"talcamper='$ao_personal->talcamper', ".
		"talpanper='$ao_personal->talpanper', ".
		"talzapper='$ao_personal->talzapper',".
		"anoservprecont='$ao_personal->anoservprecont', ".
		"anoservprefijo='$ao_personal->anoservprefijo', ".
		"codorg='$ao_personal->codorg', ".
		"porcajahoper='$ao_personal->porcajahoper', ".
		"codger='$ao_personal->codger', ".
		"anoperobr='$ao_personal->anoperobr', ".
		"carantper='$ao_personal->carantper', ".
		"obsegrper= '$ao_personal->obsegrper' ".
		$ls_sqlfot.
	    "WHERE codper= '$ao_personal->codper' AND codemp='".$this->ls_codemp."'" ;


			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el expediente de personal ".$as_codper;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	  $ls_sql = "INSERT INTO sno_personal (codper, cedper, nomper, apeper, dirper, fecnacper, edocivper, nacper, codpai, ".
	            "codest, codmun, codpar, telhabper, telmovper, coreleper, sexper, estaper, pesper, codpro, nivacaper, ".
				" codcom, codran, cedbenper, numhijper, contraper, cenmedper ,turper, horper, hcmper, tipsanper, numexpper, ".
				" tipvivper, tenvivper, monpagvivper, cuecajahoper, cajahoper, cuelphper, cuefidper, fecingadmpubper, ".
				" anoservpreper,fecingper, obsegrper,  obsper, estper,   codtippersss, codpainac ,".
				"codestnac, codunivipladin, fecjubper, fecreingper, fecfevid, enviorec, fotper, fecleypen, codcausa, ".
				" situacion, fecsitu,talcamper,talpanper,talzapper,anoservprecont,anoservprefijo,codorg,porcajahoper,  ".
				" codger, anoperobr, carantper,codemp)".	  
	            "VALUES ('$ao_personal->codper', '$ao_personal->cedper', '$ao_personal->nomper', '$ao_personal->apeper', ".
				" '$ao_personal->dirper', '$ao_personal->fecnacper', '$ao_personal->edocivper', '$ao_personal->nacper', ".
				" '$ao_personal->codpai', '$ao_personal->codest', '$ao_personal->codmun', '$ao_personal->codpar', ".
				" '$ao_personal->telhabper', '$ao_personal->telmovper', '$ao_personal->coreleper', '$ao_personal->sexper', ".
				" '$ao_personal->estaper', '$ao_personal->pesper', '$ao_personal->codpro', '$ao_personal->nivacaper', ".
				" '$ao_personal->codcom', '$ao_personal->codran',  '$ao_personal->cedbenper', '$ao_personal->numhijper', ".
				" '$ao_personal->contraper', '$ao_personal->cenmedper' ,'$ao_personal->turper', '$ao_personal->horper', ".
				" '$ao_personal->hcmper', '$ao_personal->tipsanper', '$ao_personal->numexpper', '$ao_personal->tipvivper', ".
				" '$ao_personal->tenvivper', '$ao_personal->monpagvivper', '$ao_personal->cuecajahoper', ".
				" '$ao_personal->cajahoper', '$ao_personal->cuelphper', '$ao_personal->cuefidper', ".
				" '$ao_personal->fecingadmpubper', '$ao_personal->anoservpreper' , '$ao_personal->fecingper', ".
				" '$ao_personal->obsegrper',  ".
				" '$ao_personal->obsper', '1','$ao_personal->codtippersss','$ao_personal->codpainac','$ao_personal->codestnac', ".
				" '$ao_personal->codunivi','$ao_personal->fecjubper','$ao_personal->fecreingper', '$ao_personal->fecfevid', ".
				" '$ao_personal->enviorec', '$ao_personal->fotper', '$ao_personal->feclossfan','$ao_personal->codcausa', ".
				" '$ao_personal->situacion', '$ao_personal->fecsitu', '$ao_personal->talcamper', '$ao_personal->talpanper', ".
				" '$ao_personal->talzapper','$ao_personal->anoservprecont','$ao_personal->anoservprefijo', ".
				" '$ao_personal->codorg',$ao_personal->porcajahoper,'$ao_personal->codger', '$ao_personal->anoperobr', ".
				" '$ao_personal->carantper',  '".$this->ls_codemp."')";
		
			
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el expediente de personal ".$as_codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_guardarpersonal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				$this->io_sql->commit();
		}
		
	
	return $lb_guardo;
  } //end  function uf_srh_guardarPersonal

//-----------------------------------------------------------------------------------------------------------------------------------  
    function uf_update_años_servicio_previo ($as_codper, $as_campo, $as_años)
	{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_años_servicio_previo
		//		   Access: public (sigesp_snorh_d_trabajoanterior)
		//	    Arguments: as_codper // Código de Personal				       
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que actualiza los años de servios previo de una persona
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 09/08/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$lb_valido=true;
		$this->io_sql->begin_transaction();			
		 $ls_sql = "UPDATE sno_personal SET ".	
				   " ".$as_campo."='".$as_años."' ".
			    "WHERE codper= '".$as_codper."' AND codemp='".$this->ls_codemp."'" ;
		$lb_guardo = $this->io_sql->execute($ls_sql);

        if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->personal MÉTODO->uf_update_años_servicio_previo  ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
					$this->io_sql->commit();
		}
		return $lb_valido;
	}

//-----------------------------------------------------------------------------------------------------------------------------------  
    function uf_select_anotrabajoantfijo($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_anotrabajoantfijo
		//		   Access: public (sigesp_snorh_d_trabajoanterior)
		//	    Arguments: as_codper // Código de Personal				       
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que busca los años de trabajo previos como fijo
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="";	
		$anofijo=0;	
		  $ls_sql=" SELECT SUM(anolab) as anolab FROM sno_trabajoanterior ".
				  "	 WHERE codemp='".$this->ls_codemp."'   ". 
				  "	   AND codper='".$as_codper."'         ".
				  "	   AND emppubtraant='1'                ".
				  "	   AND (codded='100' OR codded='200')  ";
				  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->Personal MÉTODO->uf_select_anotrabajoantfijo ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$anofijo=$row["anolab"];
				if ($anofijo=="")
				{
					$anofijo=0; 
				} 
			}
			$this->io_sql->free_result($rs_data);
		}
		return $anofijo;
	}// end function uf_select_trabajoanterior
//------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_select_anotrabajoantcontratado($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_anotrabajoantcontratado
		//		   Access: public (sigesp_snorh_d_trabajoanterior)
		//	    Arguments: as_codper // Código de Personal				       
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que busca los años de trabajo previos como fijo
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="";	
		$anocont=0;	
		  $ls_sql=" SELECT SUM(anolab) as anolab FROM sno_trabajoanterior ".
				  "	 WHERE codemp='".$this->ls_codemp."'   ". 
				  "	   AND codper='".$as_codper."'         ".
				  "	   AND emppubtraant='1'                ".
				  "	   AND (codded='300')  ";
				  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->Personal MÉTODO->uf_select_anotrabajoantfijo ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$anocont=$row["anolab"]; 
				if ($anocont=="")
				{
					$anocont=0; 
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $anocont;
	}//uf_select_anotrabajoantcontratado
//------------------------------------------------------------------------------------------------------------------------------------

	
function uf_srh_buscar_personal($as_codper,$as_cedper,$as_apeper,$as_nomper,$as_tipo)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_personal																		
		//         access: public (sigesp_sno_personal)												                    
		//      Argumento: $as_codper   //  código del personal										                        
		//                 $as_cedper   //  cedula del personal                                                              
		//                 $as_apeper   //  apellido del personal                                                           
		//                 $as_nomper   //  nombre del personal                                                             
		//	      Returns: Retorna un XML  																						
		//    Description: Funcion busca una personal en la tabla sno_personal y crea un XML para mostrar   
		//                  los datos en el catalogo                                                                           
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 21/01/2007							Fecha Última Modificación: 21/01/2007							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
	switch ($as_tipo) {
	
	case '1' :
	 
	    $ls_codperdestino="txtcodper";
		$ls_cedperdestino="txtcedper";
		$ls_nomperdestino="txtnomper";
		$ls_apeperdestino="txtapeper";
		$ls_dirperdestino="txtdirper";
		$ls_fecnacperdestino="txtfecnacper";
		$ls_edocivperdestino="cmbedocivper";
		$ls_nacperdestino="cmbnacper";
		$ls_codpaidestino="cmbcodpai";
		$ls_codestdestino="cmbcodest";
		$ls_codmundestino="cmbcodmun";
		$ls_codpardestino="cmbcodpar";
		$ls_telhabperdestino="txttelhabper";
		$ls_coreleperdestino="txtcoreleper";
		$ls_telmovperdestino="txttelmovper";
		$ls_sexperdestino="cmbsexper";
		$ls_estaperdestino="txtestaper";
		$ls_pesperdestino="txtpesper";
		$ls_codprodestino="txtcodpro";
		$ls_desprodestino="txtdespro";
		$ls_nivacaperdestino="cmbnivacaper";
		$ls_codcomdestino="txtcodcom";
		$ls_codrandestino="txtcodran";
		$ls_cedbenperdestino="txtcedbenper";
		$ls_numhijperdestino="txtnumhijper";
		$ls_contraperdestino="cmbcontraper";
		$ls_obsperdestino="txtobsper";
		$ls_fotperdestino="txtfotper";
		$ls_cenmedperdestino="cmbcenmedper";
		$ls_turperdestino="cmbturper";
		$ls_horperdestino="txthorper";
		$ls_hcmperdestino="chkhcmper";
		$ls_tipsanperdestino="txttipsanper";
		$ls_numexpperdestino="txtnumexpper";
		$ls_tipvivperdestino="cmbtipvivper";
  		$ls_tenvivperdestino="txttenvivper";
		$ls_monpagvivperdestino="txtmonpagvivper";
		$ls_cuecajahoperdestino="txtcuecajahoper";
		$ls_cajahoperdestino="chkcajahoper";
		$ls_cuelphperdestino="txtcuelphper";
		$ls_cuefidperdestino="txtcuefidper";
		$ls_fecingadmpubperdestino="txtfecingadmpub";
		$ls_anoservpreperdestino="txtanoservpreper";
		$ls_fecingperdestino="txtfecingper";
		$ls_fecegrperdestino="txtfecegrper";
		$ls_cauegrperdestino="cmbcauegrper";
		$ls_obsegrperdestino="txtobsegrper";
		$ls_codtippersssdestino= "txtcodtippersss";
		$ls_dentippersssdestino= "txtdestippersss";
		$ls_codpainacdestino= "cmbcodpainac";
		$ls_codestnacdestino= "cmbcodestnac";
		$ls_fecjubperdestino= "txtfecjubper";
		$ls_fecreingperdestino= "txtfecreingper";	
		$ls_codunividestino	="txtcodunivi";
		$ls_denunividestino	="txtdenunivi";
		$ls_fecfeviddestino	="txtfecfevid";
		$ls_enviorecdestino	="cmbenviorec";
		$ls_descomdestino	="txtdescom";
		$ls_desrandestino	="txtdesran";
		$ls_feclossfandestino	="txtfecleypen";
		$ls_codcausadestino	="txtcodcausa";
		$ls_dencausadestino	="txtdencausa";
	    $ls_situaciondestino ="cmbsituacion";
		$ls_fecsitudestino="txtfecsitu";
		$ls_talcamperdestino="txttalcamper";
		$ls_talpanperdestino="txttalpanper";
		$ls_talzapperdestino="txttalzapper";
		$ls_anoservprecontdestino="txtanoservprecont";
		$ls_anoservprefijodestino="txtanoservprefijo";
		$ls_codorgdestino="txtcodorg";
		$ls_desorgdestino="txtdesorg";
		$ls_porcajahoperdestino="txtporcajahoper";	
		$ls_codgerdestino="txtcodger";
		$ls_dengerdestino="txtdenger";
		$ls_anoperobrdestino="txtanoperobr";
		$ls_carantperdestino="txtcarantper";
		
		$lb_valido=true;
		
				
		$ls_sql= " SELECT sno_personal.*, sigesp_pais.despai, sigesp_estados.desest, sigesp_municipio.denmun, ".
				 " sigesp_parroquia.denpar, sno_profesion.despro, dentippersss, denunivipladin, dencausa,  ".
				 " (SELECT desorg FROM srh_organigrama ".
				 " WHERE srh_organigrama.codemp = sno_personal.codemp ".
				 " AND srh_organigrama.codorg = sno_personal.codorg ) AS desorg, ".
				 " (SELECT denger FROM srh_gerencia ".
				 " WHERE srh_gerencia.codemp = sno_personal.codemp ".
				 " AND srh_gerencia.codger = sno_personal.codger ) AS denger, ".
		 		 " (SELECT descom FROM sno_componente ".
				 " WHERE sno_componente.codemp = sno_personal.codemp ".
				 " AND sno_componente.codcom = sno_personal.codcom ) AS descom, ".
				 "(SELECT desran FROM sno_rango ".
				 " WHERE sno_rango.codemp = sno_personal.codemp ".
				 " AND sno_rango.codcom = sno_personal.codcom ".
				 " AND sno_rango.codran = sno_personal.codran) AS desran, ".
				  " (SELECT codpai FROM sigesp_pais ".
				 "	 WHERE sigesp_pais.codpai = sno_personal.codpainac ) AS codpainac, ".
				 "	(SELECT codest FROM sigesp_estados ".
				 "	  WHERE sigesp_estados.codpai = sno_personal.codpainac ".
				 "		AND sigesp_estados.codest = sno_personal.codestnac ) AS codestnac ".
		 		 " FROM sigesp_pais, sigesp_estados, sigesp_municipio, sigesp_parroquia, sno_personal		          
		           LEFT JOIN sno_profesion ON (sno_profesion.codpro = sno_personal.codpro)
				   INNER JOIN sno_tipopersonalsss ON (sno_personal.codtippersss = sno_tipopersonalsss.codtippersss)
				   LEFT JOIN srh_unidadvipladin ON (sno_personal.codunivipladin = srh_unidadvipladin.codunivipladin)
				   LEFT JOIN sno_causales ON (sno_personal.codcausa = sno_causales.codcausa) ".
				 " WHERE codper like '$as_codper' ".
				 " AND cedper like '$as_cedper' ".
			 	 " AND nomper like '$as_nomper' ".
				 " AND apeper like '$as_apeper' ".
				 " AND sigesp_pais.codpai = sno_personal.codpai ".
				"   AND sigesp_estados.codpai = sno_personal.codpai ".
				"   AND sigesp_estados.codest = sno_personal.codest ".
				"   AND sigesp_municipio.codpai = sno_personal.codpai ".
				"   AND sigesp_municipio.codest = sno_personal.codest ".
				"   AND sigesp_municipio.codmun = sno_personal.codmun ".
				"   AND sigesp_parroquia.codpai = sno_personal.codpai ".
				"   AND sigesp_parroquia.codest = sno_personal.codest ".
				"   AND sigesp_parroquia.codmun = sno_personal.codmun ".
				"   AND sigesp_parroquia.codpar = sno_personal.codpar ".
				 " ORDER BY codper  LIMIT 500";

	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_buscar_personal( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while (!$rs_data->EOF) 
			{
			     
					$ls_codper=$rs_data->fields["codper"];
					$ls_cedper=$rs_data->fields["cedper"];
					$ls_nomper=trim (htmlentities ($rs_data->fields["nomper"]));
					$ls_apeper=trim (htmlentities ($rs_data->fields["apeper"]));
					$ls_dirper=htmlentities ($rs_data->fields["dirper"]);
					$ls_fecnacper=$this->io_funcion->uf_formatovalidofecha($rs_data->fields["fecnacper"]);
				    $ls_fecnacper=$this->io_funcion->uf_convertirfecmostrar($ls_fecnacper);
					$ls_edocivper=$rs_data->fields["edocivper"];
					$ls_nacper=$rs_data->fields["nacper"];
					$ls_codpai=$rs_data->fields["codpai"];
					$ls_codest=$rs_data->fields["codest"];
					$ls_codmun=$rs_data->fields["codmun"];
					$ls_codpar=$rs_data->fields["codpar"];
					$ls_telhabper=$rs_data->fields["telhabper"];
					$ls_coreleper=$rs_data->fields["coreleper"];
					$ls_telmovper=$rs_data->fields["telmovper"];
					$ls_sexper=$rs_data->fields["sexper"];
					$ls_estaper=$rs_data->fields["estaper"];
					$ls_pesper=$rs_data->fields["pesper"];
					$ls_codpro=$rs_data->fields["codpro"];
					$ls_despro=htmlentities ($rs_data->fields["despro"]);
					$ls_nivacaper=$rs_data->fields["nivacaper"];
					$ls_codcom=$rs_data->fields["codcom"];
					$ls_codran=$rs_data->fields["codran"];
					$ls_cedbenper=$rs_data->fields["cedbenper"];
					$ls_numhijper=$rs_data->fields["numhijper"];
					$ls_contraper=$rs_data->fields["contraper"];
					$ls_obsper=htmlentities ($rs_data->fields["obsper"]);
					$ls_fotper=$rs_data->fields["fotper"];
					$ls_cenmedper=htmlentities($rs_data->fields["cenmedper"]);
					$ls_turper=$rs_data->fields["turper"];
					$ls_horper=htmlentities($rs_data->fields["horper"]);
					$ls_hcmper=$rs_data->fields["hcmper"];
					$ls_tipsanper=htmlentities($rs_data->fields["tipsanper"]);
					$ls_numexpper=$rs_data->fields["numexpper"];
					$ls_tipvivper=$rs_data->fields["tipvivper"];
					$ls_tenvivper=htmlentities($rs_data->fields["tenvivper"]);
					$ls_monpagvivper=$rs_data->fields["monpagvivper"];
					$ls_cuecajahoper=$rs_data->fields["cuecajahoper"];
					$ls_cajahoper=$rs_data->fields["cajahoper"];
					$ls_cuelphper=$rs_data->fields["cuelphper"];
					$ls_cuefidper=$rs_data->fields["cuefidper"];
					$ls_fecingadmpubper=$this->io_funcion->uf_formatovalidofecha($rs_data->fields["fecingadmpubper"]);
				    $ls_fecingadmpubper=$this->io_funcion->uf_convertirfecmostrar($ls_fecingadmpubper);
					
					$ls_anoservpreper=$rs_data->fields["anoservpreper"];
					$ls_fecingper=$this->io_funcion->uf_formatovalidofecha($rs_data->fields["fecingper"]);
				    $ls_fecingper=$this->io_funcion->uf_convertirfecmostrar($ls_fecingper);
					
					$ls_fecegrper=$this->io_funcion->uf_formatovalidofecha($rs_data->fields["fecegrper"]);
				    $ls_fecegrper=$this->io_funcion->uf_convertirfecmostrar($ls_fecegrper);
					
					$ls_fecjubper=$this->io_funcion->uf_formatovalidofecha($rs_data->fields["fecjubper"]);
				    $ls_fecjubper=$this->io_funcion->uf_convertirfecmostrar($ls_fecjubper);
					
					$ls_fecreingper=$this->io_funcion->uf_formatovalidofecha($rs_data->fields["fecreingper"]);
				    $ls_fecreingper=$this->io_funcion->uf_convertirfecmostrar($ls_fecreingper);
					
					$ls_fecfevid=$this->io_funcion->uf_formatovalidofecha($rs_data->fields["fecfevid"]);
				    $ls_fecfevid=$this->io_funcion->uf_convertirfecmostrar($ls_fecfevid);
					
					$ls_feclossfan=$this->io_funcion->uf_formatovalidofecha($rs_data->fields["fecleypen"]);
				    $ls_feclossfan=$this->io_funcion->uf_convertirfecmostrar($ls_feclossfan);
					
					$ls_cauegrper=$rs_data->fields["cauegrper"];
					$ls_obsegrper=htmlentities  ($rs_data->fields["obsegrper"]);
					
					$ls_codpainac= $rs_data->fields["codpainac"];
					$ls_codestnac= $rs_data->fields["codestnac"];
					$ls_codtippersss= $rs_data->fields["codtippersss"];	
					$ls_dentippersss= htmlentities  ($rs_data->fields["dentippersss"]);
					
					$ls_codunivi=$rs_data->fields["codunivipladin"];
					$ls_denunivi=htmlentities ($rs_data->fields["denunivipladin"]);	
					$ls_enviorec=$rs_data->fields["enviorec"];	
					$ls_descom=htmlentities ($rs_data->fields["descom"]);	
					$ls_desran=htmlentities ($rs_data->fields["desran"]);
					
					$ls_codcausa= $rs_data->fields["codcausa"];	
					$ls_dencausa= htmlentities  ($rs_data->fields["dencausa"]);
					
					$ls_situacion=htmlentities($rs_data->fields["situacion"]);
					$ls_fecsitu=$this->io_funcion->uf_convertirfecmostrar($rs_data->fields["fecsitu"]);
					$ls_talcamper= htmlentities ($rs_data->fields["talcamper"]);
					$ls_talpanper= htmlentities ($rs_data->fields["talpanper"]);
					$ls_talzapper=$rs_data->fields["talzapper"];
					
					$ls_anoservprecont=$rs_data->fields["anoservprecont"];
					$ls_anoservprefijo=$rs_data->fields["anoservprefijo"];		
					
					$ls_codorg=$rs_data->fields["codorg"];			
					$ls_desorg= htmlentities ($rs_data->fields["desorg"]);
					$ls_porcajahoper=$rs_data->fields["porcajahoper"];
					
					$ls_codger=$rs_data->fields["codger"];			
					$ls_denger= htmlentities ($rs_data->fields["denger"]);
					
					$ls_anoperobr= htmlentities ($rs_data->fields["anoperobr"]);
					$ls_carantper= htmlentities ($rs_data->fields["carantper"]);
					
					
					$this->io_funcion->uf_calcular_tiempo($ls_fecingper,$dias,$meses,$anos);

					
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$rs_data->fields['codper']);
					$cell = $row_->appendChild($dom->createElement('cell'));
					   
					
					$cell->appendChild($dom->createTextNode($rs_data->fields['codper']." ^javascript:aceptar_persona1(\"$ls_codper\",			\"$ls_cedper\", \"$ls_nomper\", \"$ls_apeper\",	\"$ls_dirper\",	\"$ls_fecnacper\", 	\"$ls_edocivper\", 	\"$ls_nacper\", \"$ls_codpai\",	\"$ls_codest\",	\"$ls_codmun\", \"$ls_codpar\", \"$ls_telhabper\", 	\"$ls_coreleper\",\"$ls_telmovper\", \"$ls_sexper\", \"$ls_estaper\", \"$ls_pesper\", \"$ls_codpro\",\"$ls_despro\", \"$ls_nivacaper\",	\"$ls_codcom\",	\"$ls_codran\",	\"$ls_cedbenper\",	\"$ls_numhijper\",	\"$ls_contraper\",	\"$ls_obsper\", \"$ls_fotper\", 				\"$ls_cenmedper\", \"$ls_turper\", 	\"$ls_horper\",	\"$ls_hcmper\",	\"$ls_tipsanper\",	\"$ls_numexpper\", \"$ls_tipvivper\", \"$ls_tenvivper\", \"$ls_monpagvivper\", \"$ls_cuecajahoper\", \"$ls_cajahoper\", \"$ls_cuelphper\", \"$ls_cuefidper\",	\"$ls_fecingadmpubper\", \"$ls_anoservpreper\", \"$ls_fecingper\", \"$ls_fecegrper\", \"$ls_cauegrper\", \"$ls_obsegrper\", \"$ls_codperdestino\", \"$ls_cedperdestino\", \"$ls_nomperdestino\", \"$ls_apeperdestino\",	\"$ls_dirperdestino\",	\"$ls_fecnacperdestino\", \"$ls_edocivperdestino\", \"$ls_nacperdestino\", \"$ls_codpaidestino\",	\"$ls_codestdestino\", \"$ls_codmundestino\", \"$ls_codpardestino\", \"$ls_telhabperdestino\", 	\"$ls_coreleperdestino\",	\"$ls_telmovperdestino\", \"$ls_sexperdestino\", \"$ls_estaperdestino\",	\"$ls_pesperdestino\",	\"$ls_codprodestino\", \"$ls_desprodestino\", \"$ls_nivacaperdestino\", \"$ls_codcomdestino\", \"$ls_codrandestino\",	\"$ls_cedbenperdestino\",	\"$ls_numhijperdestino\",	\"$ls_contraperdestino\",	\"$ls_obsperdestino\", \"$ls_fotperdestino\",	\"$ls_cenmedperdestino\", \"$ls_turperdestino\", 	\"$ls_horperdestino\",	\"$ls_hcmperdestino\",	\"$ls_tipsanperdestino\",	\"$ls_numexpperdestino\", \"$ls_tipvivperdestino\", \"$ls_tenvivperdestino\", \"$ls_monpagvivperdestino\",\"$ls_cuecajahoperdestino\", \"$ls_cajahoperdestino\",\"$ls_cuelphperdestino\", \"$ls_cuefidperdestino\",	\"$ls_fecingadmpubperdestino\", \"$ls_anoservpreperdestino\", \"$ls_fecingperdestino\", \"$ls_fecegrperdestino\", \"$ls_cauegrperdestino\", \"$ls_obsegrperdestino\", \"$ls_codpainac\", \"$ls_codestnac\",\"$ls_codtippersss\", \"$ls_dentippersss\", \"$ls_codpainacdestino\", \"$ls_codestnacdestino\",\"$ls_codtippersssdestino\",\"$ls_dentippersssdestino\",
\"$ls_codunivi\",\"$ls_codunividestino\", \"$ls_denunivi\",\"$ls_denunividestino\", \"$ls_fecjubper\",\"$ls_fecjubperdestino\", \"$ls_fecreingper\",\"$ls_fecreingperdestino\", \"$ls_fecfevid\", \"$ls_fecfeviddestino\", \"$ls_enviorec\", \"$ls_enviorecdestino\", \"$ls_descom\", \"$ls_descomdestino\" , \"$ls_desran\",\"$ls_desrandestino\", \"$ls_feclossfan\",\"$ls_feclossfandestino\",\"$ls_codcausa\", \"$ls_codcausadestino\", \"$ls_dencausa\", \"$ls_dencausadestino\",\"$ls_situacion\",\"$ls_fecsitu\",  \"$ls_talcamper\", \"$ls_talpanper\", \"$ls_talzapper\", \"$ls_situaciondestino\", \"$ls_fecsitudestino\",\"$ls_talcamperdestino\", \"$ls_talpanperdestino \", \"$ls_talzapperdestino\",\"$ls_anoservprecont\",\"$ls_anoservprecontdestino\",\"$ls_anoservprefijo\", \"$ls_anoservprefijodestino\",\"$ls_codorg\",\"$ls_codorgdestino\",\"$ls_desorg\",\"$ls_desorgdestino\",\"$ls_porcajahoper\",\"$ls_porcajahoperdestino\",\"$ls_codger\",\"$ls_denger\",\"$ls_codgerdestino\",\"$ls_dengerdestino\",\"$ls_anoperobr\",\"$ls_anoperobrdestino\", \"$ls_carantper\",\"$ls_carantperdestino\", \"$dias\",\"$meses\",\"$anos\");^_self"));
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($rs_data->fields['cedper']));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["apeper"]))));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["nomper"]))));												
					$row_->appendChild($cell);
					
					$rs_data->MoveNext();
			}
			return $dom->saveXML();
		}
   break;
	
   case '2' :
    $ls_codperdestino="txtcodsup";
	$ls_nomperdestino="txtnomsup";
	$ls_carsupdestino="txtcodcarsup";
	$lb_valido=true;
	
	switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_sql=" SELECT DISTINCT (sno_personalnomina.codper),sno_personal.cedper,sno_personal.nomper, ".
				  		" sno_personal.apeper, sno_personal.fecingper, sno_asignacioncargo.denasicar, sno_cargo.descar ".
						" FROM sno_personal, sno_personalnomina   ".
						" LEFT JOIN sno_asignacioncargo on (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar ".
						" AND sno_personalnomina.codnom=sno_asignacioncargo.codnom   ".
						" AND sno_personalnomina.codemp=sno_asignacioncargo.codemp)  ".
						" LEFT JOIN sno_cargo on (sno_personalnomina.codcar=sno_cargo.codcar AND ".
						" sno_personalnomina.codnom=sno_cargo.codnom  AND sno_personalnomina.codemp=sno_cargo.codemp)   ".
						" JOIN sno_nomina ON (sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0') ".
						" WHERE sno_personalnomina.codper=sno_personal.codper   ".
						" AND sno_personalnomina.codemp=sno_personal.codemp     ".
						" AND sno_personalnomina.staper='1'".
						" AND sno_personal.codper like '$as_codper'  ".
						" AND sno_personal.cedper like '$as_cedper'  ".
						" AND sno_personal.nomper like '$as_nomper'  ".
						" AND sno_personal.apeper like '$as_apeper'  ".
						" GROUP BY sno_personalnomina.codper, sno_personal.codper, sno_personal.cedper, sno_personal.nomper, ".
						" sno_personal.apeper,sno_personal.fecingper, sno_asignacioncargo.denasicar, sno_cargo.descar  ".
						" ORDER BY sno_personalnomina.codper LIMIT 500";
				break;
			case "POSTGRES":
				$ls_sql=" SELECT DISTINCT (sno_personalnomina.codper),sno_personal.cedper,sno_personal.nomper, ".
				  		" sno_personal.apeper, sno_personal.fecingper, sno_asignacioncargo.denasicar, sno_cargo.descar ".
						" FROM sno_personal, sno_personalnomina   ".
						" LEFT JOIN sno_asignacioncargo on (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar ".
						" AND sno_personalnomina.codnom=sno_asignacioncargo.codnom   ".
						" AND sno_personalnomina.codemp=sno_asignacioncargo.codemp)  ".
						" LEFT JOIN sno_cargo on (sno_personalnomina.codcar=sno_cargo.codcar AND ".
						" sno_personalnomina.codnom=sno_cargo.codnom  AND sno_personalnomina.codemp=sno_cargo.codemp)   ".
						" JOIN sno_nomina ON (sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0') ".
						" WHERE sno_personalnomina.codper=sno_personal.codper   ".
						" AND sno_personalnomina.codemp=sno_personal.codemp     ".
						" AND sno_personalnomina.staper='1'".
						" AND sno_personal.codper like '$as_codper'  ".
						" AND sno_personal.cedper like '$as_cedper'  ".
						" AND sno_personal.nomper like '$as_nomper'  ".
						" AND sno_personal.apeper like '$as_apeper'  ".
						" GROUP BY sno_personalnomina.codper, sno_personal.codper, sno_personal.cedper, sno_personal.nomper, ".
						" sno_personal.apeper,sno_personal.fecingper, sno_asignacioncargo.denasicar, sno_cargo.descar  ".
						" ORDER BY sno_personalnomina.codper LIMIT 500";
				break;
		}	
	 
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_buscar_personal( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while (!$rs_data->EOF) 
			{
			     
					$ls_codper=$rs_data->fields["codper"];
					$ls_nomper=trim (htmlentities ($rs_data->fields["nomper"]));
					$ls_apeper=trim (htmlentities ($rs_data->fields["apeper"]));
					
					$ls_cargo1=trim (htmlentities ($rs_data->fields["denasicar"]));
					$ls_cargo2=trim (htmlentities  ($rs_data->fields["descar"]));
					
					if ($ls_cargo1!="Sin Asignación de Cargo")
				    {
					 $ls_cargosup=$ls_cargo1;
				    }
				   if ($ls_cargo2!="Sin Cargo")
				    {
					  $ls_cargosup=$ls_cargo2;
				    }			
					
			
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$rs_data->fields['codper']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($rs_data->fields['codper']." ^javascript:aceptar_personal2(\"$ls_codper\", \"$ls_nomper\", \"$ls_apeper\",\"$ls_cargosup\",\"$ls_codperdestino\",  \"$ls_nomperdestino\",\"$ls_carsupdestino\");^_self"));
					
			
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($rs_data->fields['cedper']));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["apeper"]))));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["nomper"]))));												
					$row_->appendChild($cell);
					
					$rs_data->MoveNext();
			}
			return $dom->saveXML();
		}
   break;
	 
   case '3' :
    $ls_codperdestino="txtcodeva";
	$ls_nomperdestino="txtnomeva";
	$ls_carsupdestino="txtcodcareva";
	$lb_valido=true;
		
	switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_sql=" SELECT DISTINCT (sno_personalnomina.codper),sno_personal.cedper,sno_personal.nomper, ".
				  		" sno_personal.apeper, sno_personal.fecingper, sno_asignacioncargo.denasicar, sno_cargo.descar ".
						" FROM sno_personal, sno_personalnomina   ".
						" LEFT JOIN sno_asignacioncargo on (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar ".
						" AND sno_personalnomina.codnom=sno_asignacioncargo.codnom   ".
						" AND sno_personalnomina.codemp=sno_asignacioncargo.codemp)  ".
						" LEFT JOIN sno_cargo on (sno_personalnomina.codcar=sno_cargo.codcar AND ".
						" sno_personalnomina.codnom=sno_cargo.codnom  AND sno_personalnomina.codemp=sno_cargo.codemp)   ".
						" JOIN sno_nomina ON (sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0') ".
						" WHERE sno_personalnomina.codper=sno_personal.codper   ".
						" AND sno_personalnomina.codemp=sno_personal.codemp     ".
						" AND sno_personalnomina.staper='1'".
						" AND sno_personal.codper like '$as_codper'  ".
						" AND sno_personal.cedper like '$as_cedper'  ".
						" AND sno_personal.nomper like '$as_nomper'  ".
						" AND sno_personal.apeper like '$as_apeper'  ".
						" GROUP BY sno_personalnomina.codper, sno_personal.codper, sno_personal.cedper, sno_personal.nomper, ".
						" sno_personal.apeper,sno_personal.fecingper, sno_asignacioncargo.denasicar, sno_cargo.descar  ".
						" ORDER BY sno_personalnomina.codper LIMIT 500";
				break;
			case "POSTGRES":
				$ls_sql=" SELECT DISTINCT (sno_personalnomina.codper),sno_personal.cedper,sno_personal.nomper, ".
				  		" sno_personal.apeper, sno_personal.fecingper, sno_asignacioncargo.denasicar, sno_cargo.descar ".
						" FROM sno_personal, sno_personalnomina   ".
						" LEFT JOIN sno_asignacioncargo on (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar ".
						" AND sno_personalnomina.codnom=sno_asignacioncargo.codnom   ".
						" AND sno_personalnomina.codemp=sno_asignacioncargo.codemp)  ".
						" LEFT JOIN sno_cargo on (sno_personalnomina.codcar=sno_cargo.codcar AND ".
						" sno_personalnomina.codnom=sno_cargo.codnom  AND sno_personalnomina.codemp=sno_cargo.codemp)   ".
						" JOIN sno_nomina ON (sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0') ".
						" WHERE sno_personalnomina.codper=sno_personal.codper   ".
						" AND sno_personalnomina.codemp=sno_personal.codemp     ".
						" AND sno_personalnomina.staper='1'".
						" AND sno_personal.codper like '$as_codper'  ".
						" AND sno_personal.cedper like '$as_cedper'  ".
						" AND sno_personal.nomper like '$as_nomper'  ".
						" AND sno_personal.apeper like '$as_apeper'  ".
						" GROUP BY sno_personalnomina.codper, sno_personal.codper, sno_personal.cedper, sno_personal.nomper, ".
						" sno_personal.apeper,sno_personal.fecingper, sno_asignacioncargo.denasicar, sno_cargo.descar  ".
						" ORDER BY sno_personalnomina.codper LIMIT 500";
						
				break;
		}

	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_buscar_personal( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while (!$rs_data->EOF) 
			{
			     
					$ls_codper=$rs_data->fields["codper"];
					$ls_nomper=trim (htmlentities ($rs_data->fields["nomper"]));
					$ls_apeper=trim (htmlentities ($rs_data->fields["apeper"]));
					
					$ls_cargo1=trim (htmlentities ($rs_data->fields["denasicar"]));
					$ls_cargo2=trim (htmlentities  ($rs_data->fields["descar"]));
					
					if ($ls_cargo1!="Sin Asignación de Cargo")
				    {
					 $ls_cargosup=$ls_cargo1;
				    }
				   if ($ls_cargo2!="Sin Cargo")
				    {
					  $ls_cargosup=$ls_cargo2;
				    }			
					
			
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$rs_data->fields['codper']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($rs_data->fields['codper']." ^javascript:aceptar_personal2(\"$ls_codper\", \"$ls_nomper\", \"$ls_apeper\",\"$ls_cargosup\",\"$ls_codperdestino\",  \"$ls_nomperdestino\",\"$ls_carsupdestino\");^_self"));
					
			
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($rs_data->fields['cedper']));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["apeper"]))));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["nomper"]))));												
					$row_->appendChild($cell);
					
					$rs_data->MoveNext();
			}
			return $dom->saveXML();
		}
   break;
   
   case '4' :
    $ls_codperdestino="txtcodper";
	$ls_nomperdestino="txtnomper";
	$ls_fechadestino="txtfecing";
	$ls_cargodestino="txtcaract";
	$lb_valido=true;				
		
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_sql=" SELECT DISTINCT (sno_personalnomina.codper), sno_personal.cedper, sno_personal.nomper, ".
				        " sno_personal.apeper, sno_personal.fecingper, sno_asignacioncargo.denasicar, sno_cargo.descar ".
 			            " FROM sno_personal, sno_personalnomina  ".
						" LEFT JOIN sno_asignacioncargo on (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar AND ".
						" sno_personalnomina.codnom=sno_asignacioncargo.codnom ".
						" AND sno_personalnomina.codemp = sno_asignacioncargo.codemp)   ".
						" LEFT JOIN sno_cargo on (sno_personalnomina.codcar=sno_cargo.codcar AND ".
						" sno_personalnomina.codnom=sno_cargo.codnom  ".						
						" AND sno_personalnomina.codemp=sno_cargo.codemp)   ".
						" JOIN sno_nomina ON (sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0') ".
						" WHERE sno_personalnomina.codper=sno_personal.codper  ".
						" AND sno_personalnomina.codemp=sno_personal.codemp  ".
						" AND sno_personalnomina.staper='1'".
						" AND sno_personal.codper like '$as_codper'  ".
						" AND sno_personal.cedper like '$as_cedper'  ".
						" AND sno_personal.nomper like '$as_nomper'  ".
						" AND sno_personal.apeper like '$as_apeper'  ".
						" GROUP BY sno_personalnomina.codper, sno_personal.codper, sno_personal.cedper, sno_personal.nomper, ".
						" sno_personal.apeper,sno_personal.fecingper  ".
						" ORDER BY sno_personal.codper LIMIT 500"; 
				break;
			case "POSTGRES":
				$ls_sql=" SELECT DISTINCT (sno_personalnomina.codper),sno_personal.cedper,sno_personal.nomper, ".
				  		" sno_personal.apeper, sno_personal.fecingper, sno_asignacioncargo.denasicar, sno_cargo.descar ".
						" FROM sno_personal, sno_personalnomina   ".
						" LEFT JOIN sno_asignacioncargo on (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar ".
						" AND sno_personalnomina.codnom=sno_asignacioncargo.codnom   ".
						" AND sno_personalnomina.codemp=sno_asignacioncargo.codemp)  ".
						" LEFT JOIN sno_cargo on (sno_personalnomina.codcar=sno_cargo.codcar AND ".
						" sno_personalnomina.codnom=sno_cargo.codnom  AND sno_personalnomina.codemp=sno_cargo.codemp)   ".
						" JOIN sno_nomina ON (sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0') ".
						" WHERE sno_personalnomina.codper=sno_personal.codper   ".
						" AND sno_personalnomina.codemp=sno_personal.codemp     ".
						" AND sno_personalnomina.staper='1'".
						" AND sno_personal.codper like '$as_codper'  ".
						" AND sno_personal.cedper like '$as_cedper'  ".
						" AND sno_personal.nomper like '$as_nomper'  ".
						" AND sno_personal.apeper like '$as_apeper'  ".
						" GROUP BY sno_personalnomina.codper, sno_personal.codper, sno_personal.cedper, sno_personal.nomper, ".
						" sno_personal.apeper,sno_personal.fecingper, sno_asignacioncargo.denasicar, sno_cargo.descar  ".
						" ORDER BY sno_personalnomina.codper LIMIT 500";
				break;
		}
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_buscar_personal( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while (!$rs_data->EOF) 
			{
			     
					$ls_codper=$rs_data->fields["codper"];
					$ls_nomper=trim (htmlentities ($rs_data->fields["nomper"]));
					$ls_apeper=trim (htmlentities ($rs_data->fields["apeper"]));					
					$ls_fechaing=$this->io_funcion->uf_formatovalidofecha($rs_data->fields["fecingper"]);
				    $ls_fechaing=$this->io_funcion->uf_convertirfecmostrar($ls_fechaing);
					
					$ls_cargo1=trim (htmlentities ($rs_data->fields["denasicar"]));
					$ls_cargo2=trim (htmlentities  ($rs_data->fields["descar"]));
					
					if ($ls_cargo1!="Sin Asignación de Cargo")
				    {
					 $ls_cargo=$ls_cargo1;
				    }
				   if ($ls_cargo2!="Sin Cargo")
				    {
					 $ls_cargo=$ls_cargo2;
				    }						
			
					
			
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$rs_data->fields['codper']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($rs_data->fields['codper']." ^javascript:aceptar_personal_ascenso(\"$ls_codper\",\"$ls_nomper\", \"$ls_apeper\",\"$ls_fechaing\", \"$ls_cargo\",\"$ls_codperdestino\", \"$ls_nomperdestino\",\"$ls_fechadestino\",\"$ls_cargodestino\");^_self"));
					
			
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($rs_data->fields['cedper']));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["apeper"]))));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["nomper"]))));												
					$row_->appendChild($cell);
					
					$rs_data->MoveNext();
			}
			return $dom->saveXML();
		}
   break;

   
   case '6' :
    $ls_codperdestino="txtcodperdes";
	$ls_nomperdestino="txtnomperdes";
	$lb_valido=true;
		
	
				
		$ls_sql= "SELECT *  FROM sno_personal ".
				" WHERE codper like '$as_codper' ".
				"   AND cedper like '$as_cedper' ".
				"   AND nomper like '$as_nomper' ".
				"   AND apeper like '$as_apeper' ".
				"   ORDER BY codper LIMIT 500";
			  
	 
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_buscar_personal( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while (!$rs_data->EOF) 
			{
			     
					$ls_codper=$rs_data->fields["codper"];
					
					
			
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$rs_data->fields['codper']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($rs_data->fields['codper']." ^javascript:aceptar_personal3(\"$ls_codper\",\"$ls_codperdestino\");^_self"));
					
			
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($rs_data->fields['cedper']));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["apeper"]))));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["nomper"]))));												
					$row_->appendChild($cell);
					
					$rs_data->MoveNext();
			}
			return $dom->saveXML();
		}
   break;
   
   case '7' :
    $ls_codperdestino="txtcodperhas";
	$ls_nomperdestino="txtnomperhas";
	$lb_valido=true;
		
	
				
		$ls_sql= "SELECT *  FROM sno_personal ".
				" WHERE codper like '$as_codper' ".
				"   AND cedper like '$as_cedper' ".
				"   AND nomper like '$as_nomper' ".
				"   AND apeper like '$as_apeper' ".
				"   ORDER BY codper LIMIT 500";
			  
	 
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_buscar_personal( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while (!$rs_data->EOF) 
			{
			     
					$ls_codper=$rs_data->fields["codper"];
					
					
			
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$rs_data->fields['codper']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($rs_data->fields['codper']." ^javascript:aceptar_personal3(\"$ls_codper\",\"$ls_codperdestino\");^_self"));
					
			
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($rs_data->fields['cedper']));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["apeper"]))));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["nomper"]))));												
					$row_->appendChild($cell);
					
					$rs_data->MoveNext();
			}
			return $dom->saveXML();
		}
   break;
    case '8' :
    $ls_codperdestino="txtcodper";
	$ls_nomperdestino="txtnomper";
	$lb_valido=true;
		
	
				
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_sql=" SELECT DISTINCT (sno_personalnomina.codper), sno_personal.cedper, sno_personal.nomper, ".
				        " sno_personal.apeper ".
 			            " FROM sno_personal, sno_personalnomina  ".
						" WHERE sno_personalnomina.codper=sno_personal.codper  ".
						" AND sno_personalnomina.codemp=sno_personal.codemp  ".
						" AND sno_personalnomina.staper='1'".
						" AND sno_personal.codper like '$as_codper'  ".
						" AND sno_personal.cedper like '$as_cedper'  ".
						" AND sno_personal.nomper like '$as_nomper'  ".
						" AND sno_personal.apeper like '$as_apeper'  ".
						" GROUP BY sno_personalnomina.codper, sno_personal.codper, sno_personal.cedper, sno_personal.nomper, ".
						" sno_personal.apeper  ".
						" ORDER BY sno_personal.codper LIMIT 500"; 
				break;
			case "POSTGRES":
				$ls_sql=" SELECT DISTINCT (sno_personalnomina.codper),sno_personal.cedper,sno_personal.nomper, ".
				  		" sno_personal.apeper ".
						" FROM sno_personal, sno_personalnomina   ".
						" WHERE sno_personalnomina.codper=sno_personal.codper   ".
						" AND sno_personalnomina.codemp=sno_personal.codemp     ".
						" AND sno_personalnomina.staper='1'".
						" AND sno_personal.codper like '$as_codper'  ".
						" AND sno_personal.cedper like '$as_cedper'  ".
						" AND sno_personal.nomper like '$as_nomper'  ".
						" AND sno_personal.apeper like '$as_apeper'  ".
						" GROUP BY sno_personalnomina.codper,  sno_personal.cedper, sno_personal.nomper, ".
						" sno_personal.apeper  ".
						" ORDER BY sno_personalnomina.codper LIMIT 500";
				break;
		}
			  
	 
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_buscar_personal( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while (!$rs_data->EOF) 
			{
			     
					$ls_codper=$rs_data->fields["codper"];
					$ls_nomper=trim (htmlentities ($rs_data->fields["nomper"]));
					$ls_apeper=trim (htmlentities ($rs_data->fields["apeper"]));
					
			
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$rs_data->fields['codper']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($rs_data->fields['codper']." ^javascript:aceptar_tutor(\"$ls_codper\",			 \"$ls_nomper\", \"$ls_apeper\",\"$ls_codperdestino\",  \"$ls_nomperdestino\");^_self"));
					
			
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($rs_data->fields['cedper']));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["apeper"]))));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["nomper"]))));												
					$row_->appendChild($cell);
					
					$rs_data->MoveNext();
			}
			return $dom->saveXML();
		}
   break;
   case '9' :
    $ls_codperdestino="txtcodper";
	$ls_nomperdestino="txtnomper";
	$ls_cargodestino="txtcodcarper";
	$lb_valido=true;
							
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_sql=" SELECT DISTINCT (sno_personalnomina.codper),sno_personal.cedper,sno_personal.nomper, ".
				  		" sno_personal.apeper, sno_personal.fecingper, sno_asignacioncargo.denasicar, sno_cargo.descar ".
						" FROM sno_personal, sno_personalnomina   ".
						" LEFT JOIN sno_asignacioncargo on (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar ".
						" AND sno_personalnomina.codnom=sno_asignacioncargo.codnom   ".
						" AND sno_personalnomina.codemp=sno_asignacioncargo.codemp)  ".
						" LEFT JOIN sno_cargo on (sno_personalnomina.codcar=sno_cargo.codcar AND ".
						" sno_personalnomina.codnom=sno_cargo.codnom  AND sno_personalnomina.codemp=sno_cargo.codemp)   ".
						" JOIN sno_nomina ON (sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0') ".
						" WHERE sno_personalnomina.codper=sno_personal.codper   ".
						" AND sno_personalnomina.codemp=sno_personal.codemp     ".
						" AND sno_personalnomina.staper='1'".
						" AND sno_personal.codper like '$as_codper'  ".
						" AND sno_personal.cedper like '$as_cedper'  ".
						" AND sno_personal.nomper like '$as_nomper'  ".
						" AND sno_personal.apeper like '$as_apeper'  ".
						" GROUP BY sno_personalnomina.codper, sno_personal.codper, sno_personal.cedper, sno_personal.nomper, ".
						" sno_personal.apeper,sno_personal.fecingper, sno_asignacioncargo.denasicar, sno_cargo.descar  ".
						" ORDER BY sno_personalnomina.codper LIMIT 500";
				break;
			case "POSTGRES":
				$ls_sql=" SELECT DISTINCT (sno_personalnomina.codper),sno_personal.cedper,sno_personal.nomper, ".
				  		" sno_personal.apeper, sno_personal.fecingper, sno_asignacioncargo.denasicar, sno_cargo.descar ".
						" FROM sno_personal, sno_personalnomina   ".
						" LEFT JOIN sno_asignacioncargo on (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar ".
						" AND sno_personalnomina.codnom=sno_asignacioncargo.codnom   ".
						" AND sno_personalnomina.codemp=sno_asignacioncargo.codemp)  ".
						" LEFT JOIN sno_cargo on (sno_personalnomina.codcar=sno_cargo.codcar AND ".
						" sno_personalnomina.codnom=sno_cargo.codnom  AND sno_personalnomina.codemp=sno_cargo.codemp)   ".
						" JOIN sno_nomina ON (sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0') ".
						" WHERE sno_personalnomina.codper=sno_personal.codper   ".
						" AND sno_personalnomina.codemp=sno_personal.codemp     ".
						" AND sno_personalnomina.staper='1'".
						" AND sno_personal.codper like '$as_codper'  ".
						" AND sno_personal.cedper like '$as_cedper'  ".
						" AND sno_personal.nomper like '$as_nomper'  ".
						" AND sno_personal.apeper like '$as_apeper'  ".
						" GROUP BY sno_personalnomina.codper, sno_personal.codper, sno_personal.cedper, sno_personal.nomper, ".
						" sno_personal.apeper,sno_personal.fecingper, sno_asignacioncargo.denasicar, sno_cargo.descar  ".
						" ORDER BY sno_personalnomina.codper LIMIT 500";
					
				break;
		}
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_buscar_personal( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while (!$rs_data->EOF) 
			{
			     
					$ls_codper=$rs_data->fields["codper"];
					$ls_nomper=trim (htmlentities ($rs_data->fields["nomper"]));
					$ls_apeper=trim (htmlentities ($rs_data->fields["apeper"]));
					$ls_cargo1=trim (htmlentities ($rs_data->fields["denasicar"]));
					$ls_cargo2=trim (htmlentities  ($rs_data->fields["descar"]));
					
					if ($ls_cargo1!="Sin Asignación de Cargo")
				    {
					 $ls_cargo=$ls_cargo1;
				    }
				   if ($ls_cargo2!="Sin Cargo")
				    {
					 $ls_cargo=$ls_cargo2;
				    }						
			
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$rs_data->fields['codper']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($rs_data->fields['codper']."^javascript:aceptar_persona_cargo(\"$ls_codper\",\"$ls_nomper\", \"$ls_apeper\",\"$ls_cargo\",\"$ls_codperdestino\",\"$ls_nomperdestino\",\"$ls_cargodestino\");^_self"));
					
			
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($rs_data->fields['cedper']));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["apeper"]))));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["nomper"]))));												
					$row_->appendChild($cell);	
					
					$rs_data->MoveNext();	
					
			}
			return $dom->saveXML();
		}
   break;
   
   case '10' :
    $ls_codperdestino="txtcodper";
	$ls_nomperdestino="txtnomper";
	$ls_cargodestino="txtcodcarper";
	$ls_nivacadestino="txtnivacaper";
	$lb_valido=true;
							
		
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_sql=" SELECT DISTINCT (sno_personalnomina.codper),sno_personal.cedper, sno_personal.nivacaper, ".
						" sno_personal.nomper, ".
				        " sno_personal.apeper, sno_personal.fecingper, sno_asignacioncargo.denasicar, sno_cargo.descar ".
 			            " FROM sno_personal, sno_personalnomina  ".
						" LEFT JOIN sno_asignacioncargo on (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar AND ".
						" sno_personalnomina.codnom=sno_asignacioncargo.codnom ".
						" AND sno_personalnomina.codemp = sno_asignacioncargo.codemp)   ".
						" LEFT JOIN sno_cargo on (sno_personalnomina.codcar=sno_cargo.codcar AND ".
						" sno_personalnomina.codnom=sno_cargo.codnom  ".
						" AND sno_personalnomina.codemp=sno_cargo.codemp)   ".
						" LEFT JOIN sno_nomina ON (sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0') ".
						" WHERE sno_personalnomina.codper=sno_personal.codper  ".
						" AND sno_personalnomina.codemp=sno_personal.codemp  ".
						" AND sno_personalnomina.staper='1'".
						" AND sno_personal.codper like '$as_codper'  ".
						" AND sno_personal.cedper like '$as_cedper'  ".
						" AND sno_personal.nomper like '$as_nomper'  ".
						" AND sno_personal.apeper like '$as_apeper'  ".
						" GROUP BY sno_personalnomina.codper, sno_personal.codper, sno_personal.cedper, ".
						" sno_personal.nivacaper, sno_personal.nomper, ".
						" sno_personal.apeper,sno_personal.fecingper  ".
						" ORDER BY sno_personal.codper LIMIT 500"; 
				break;
			case "POSTGRES":
				$ls_sql=" SELECT DISTINCT (sno_personalnomina.codper),sno_personal.cedper, sno_personal.nivacaper,".
				        " sno_personal.nomper, ".
				  		" sno_personal.apeper, sno_personal.fecingper, sno_asignacioncargo.denasicar, sno_cargo.descar ".
						" FROM sno_personal, sno_personalnomina   ".
						" LEFT JOIN sno_asignacioncargo on (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar ".
						" AND sno_personalnomina.codnom=sno_asignacioncargo.codnom   ".
						" AND sno_personalnomina.codemp=sno_asignacioncargo.codemp)  ".
						" LEFT JOIN sno_cargo on (sno_personalnomina.codcar=sno_cargo.codcar AND ".
						" sno_personalnomina.codnom=sno_cargo.codnom  AND sno_personalnomina.codemp=sno_cargo.codemp)   ".
						" LEFT JOIN sno_nomina ON (sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0') ".
						" WHERE sno_personalnomina.codper=sno_personal.codper   ".
						" AND sno_personalnomina.codemp=sno_personal.codemp     ".
						" AND sno_personalnomina.staper='1'".
						" AND sno_personal.codper like '$as_codper'  ".
						" AND sno_personal.cedper like '$as_cedper'  ".
						" AND sno_personal.nomper like '$as_nomper'  ".
						" AND sno_personal.apeper like '$as_apeper'  ".						
					    " AND sno_nomina.codnom = sno_personalnomina.codnom ".
						" AND sno_nomina.espnom='0'".
						" GROUP BY sno_personalnomina.codper, sno_personal.codper, sno_personal.cedper, ".
						" sno_personal.nivacaper, sno_personal.nomper,
						 sno_personal.apeper,sno_personal.fecingper, sno_asignacioncargo.denasicar, sno_cargo.descar ".
						" ORDER BY sno_personalnomina.codper LIMIT 500";
						
				break;
		}
	 
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_buscar_personal( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while (!$rs_data->EOF) 
			{
			     
					$ls_codper=$rs_data->fields["codper"];
					$ls_nomper=trim (htmlentities ($rs_data->fields["nomper"]));
					$ls_apeper=trim (htmlentities ($rs_data->fields["apeper"]));
					
					$ls_nivaca=$rs_data->fields["nivacaper"];
					
					 switch($ls_nivaca)
					{
						
						case "":
							$ls_nivaca="Ninguno";
							break;
						case "0":
							$ls_nivaca="Ninguno";
							break;
						case "1":
							$ls_nivaca="Primaria";
							break;
						case "2":
							$ls_nivaca="Bachiller";
							break;
						case "3":
							$ls_nivaca="Tecnico Superior";
							break;
					   case "4":
							$ls_nivaca="Universitario";
							break;
					   case "5":
							$ls_nivaca="Maestria";
							break;
					  case "6":
							$ls_nivaca="Postgrado";
							break;
					  case "7":
							$ls_nivaca="Doctorado";
							break;
					}
					
					$ls_cargo1=trim (htmlentities ($rs_data->fields["denasicar"]));
					$ls_cargo2=trim (htmlentities  ($rs_data->fields["descar"]));
					
					if ($ls_cargo1!="Sin Asignación de Cargo")
				    {
					 $ls_cargo=$ls_cargo1;
				    }
				   if ($ls_cargo2!="Sin Cargo")
				    {
					 $ls_cargo=$ls_cargo2;
				    }						
			
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$rs_data->fields['codper']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($rs_data->fields['codper']."^javascript:aceptar_persona_nivaca(\"$ls_codper\",\"$ls_nomper\", \"$ls_apeper\",\"$ls_cargo\",\"$ls_codperdestino\",\"$ls_nomperdestino\",\"$ls_cargodestino\",\"$ls_nivaca\",\"$ls_nivacadestino\" );^_self"));
					
			
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($rs_data->fields['cedper']));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["apeper"]))));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["nomper"]))));												
					$row_->appendChild($cell);
					
					$rs_data->MoveNext();	
					
			}
			return $dom->saveXML();
		}
   break;
   
    case '11' :
    $ls_codperdestino="txtcodper";
	$ls_nomperdestino="txtnomper";
	$ls_caractdestino="txtcaract";	
	$ls_uniadmdestino="txtuniadm";
	$ls_sueactdestino="txtsuelact";
	$ls_codcardestino="hidcodcar";
	$ls_coduniadmdestino="hidcoduniadm";
	$ls_codnomdestino="hidcodnom";
	$ls_pasodestino="hidpaso";
	$ls_gradodestino="hidgrado";
	
	$lb_valido=true;
							
		
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_sql=" SELECT DISTINCT (sno_personalnomina.codper), sno_personal.cedper, sno_personal.nomper, ".
				        " sno_personal.apeper, sno_personal.fecingper, sno_asignacioncargo.denasicar, sno_cargo.descar, ". 
						" sno_unidadadmin.desuniadm, sno_personalnomina.sueper, sno_personalnomina.codgra, 
						  sno_personalnomina.codpas, ".
						" sno_asignacioncargo.codasicar, sno_cargo.codcar, sno_personalnomina.codnom,".
						" sno_unidadadmin.minorguniadm, sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, 
						  sno_unidadadmin.depuniadm , sno_unidadadmin.prouniadm ".
 			            " FROM sno_personal, sno_nomina, sno_unidadadmin, sno_personalnomina  ".
						" LEFT JOIN sno_asignacioncargo on (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar AND ".
						" sno_personalnomina.codnom=sno_asignacioncargo.codnom ".
						" AND sno_personalnomina.codemp = sno_asignacioncargo.codemp)   ".
						" LEFT JOIN sno_cargo on (sno_personalnomina.codcar=sno_cargo.codcar AND ".
						" sno_personalnomina.codnom=sno_cargo.codnom  ".
						" AND sno_personalnomina.codemp=sno_cargo.codemp)   ".
						" WHERE sno_personalnomina.codper=sno_personal.codper  ".
						" AND sno_personalnomina.codemp=sno_personal.codemp  ".
						" AND sno_unidadadmin.minorguniadm = sno_unidadadmin.minorguniadm 
						  AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
						  AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
						  AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
						  AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm  ".
						" AND sno_nomina.codemp=sno_personal.codemp     ".
						" AND sno_nomina.codnom = sno_personalnomina.codnom ".
						" AND sno_nomina.espnom='0'".
						" AND sno_personalnomina.staper='1'".
						" AND sno_personal.codper like '$as_codper'  ".
						" AND sno_personal.cedper like '$as_cedper'  ".
						" AND sno_personal.nomper like '$as_nomper'  ".
						" AND sno_personal.apeper like '$as_apeper'  ".						
						" ORDER BY sno_personal.codper LIMIT 500"; 
				break;
				
				 			
			case "POSTGRES":
			
				$ls_sql=" SELECT DISTINCT (sno_personalnomina.codper),sno_personal.cedper,sno_personal.nomper, ".
				  		" sno_personal.apeper, sno_personal.fecingper, sno_asignacioncargo.denasicar, sno_cargo.descar, ".
						" sno_unidadadmin.desuniadm, sno_personalnomina.sueper, sno_personalnomina.codnom, ".
						" sno_asignacioncargo.codasicar, sno_cargo.codcar, sno_personalnomina.codgra, sno_personalnomina.codpas,".
						" sno_unidadadmin.minorguniadm, sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, 
						  sno_unidadadmin.depuniadm , sno_unidadadmin.prouniadm ".
						" FROM sno_personal, sno_nomina, sno_unidadadmin, sno_personalnomina   ".
						" LEFT JOIN sno_asignacioncargo on (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar ".
						" AND sno_personalnomina.codnom=sno_asignacioncargo.codnom   ".
						" AND sno_personalnomina.codemp=sno_asignacioncargo.codemp)  ".
						" LEFT JOIN sno_cargo on (sno_personalnomina.codcar=sno_cargo.codcar AND ".
						" sno_personalnomina.codnom=sno_cargo.codnom  AND sno_personalnomina.codemp=sno_cargo.codemp)   ".
						" WHERE sno_personalnomina.codper=sno_personal.codper   ".
						" AND sno_personalnomina.codemp=sno_personal.codemp     ".
						" AND sno_unidadadmin.codemp=sno_personal.codemp     ".
						" AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
						  AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
						  AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
						  AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
						  AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm  ".
						" AND sno_nomina.codemp=sno_personal.codemp     ".
					    " AND sno_nomina.codnom = sno_personalnomina.codnom ".
						" AND sno_nomina.espnom='0'".
						" AND sno_personalnomina.staper='1'".
						" AND sno_personal.codper like '$as_codper'  ".
						" AND sno_personal.cedper like '$as_cedper'  ".
						" AND sno_personal.nomper like '$as_nomper'  ".
						" AND sno_personal.apeper like '$as_apeper'  ".
						" ORDER BY sno_personalnomina.codper LIMIT 500"; 						
						
				break;
		}
	 
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_buscar_personal( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while (!$rs_data->EOF) 
			{
			     
					$ls_codper=$rs_data->fields["codper"];
					$ls_nomper=trim (htmlentities ($rs_data->fields["nomper"]));
					$ls_apeper=trim (htmlentities ($rs_data->fields["apeper"]));
					
					$ls_paso=trim (htmlentities ($rs_data->fields["codpas"]));
					
					$ls_grado=trim (htmlentities ($rs_data->fields["codgra"]));
									
					$ls_cargo1=trim (htmlentities ($rs_data->fields["denasicar"]));
					$ls_cargo2=trim (htmlentities  ($rs_data->fields["descar"]));
				
					if ($ls_cargo1!="Sin Asignación de Cargo")
				    {
					 $ls_cargo=$ls_cargo1;
					 $ls_codcar = trim ($rs_data->fields["codasicar"]);
				    }
				   if ($ls_cargo2!="Sin Cargo")
				    {
					 $ls_cargo=$ls_cargo2;
					 $ls_codcar = trim ($rs_data->fields["codcar"]);
				    }
					
					$ls_codnom = trim ($rs_data->fields["codnom"]);
					$ls_uniadm=trim (htmlentities ($rs_data->fields["desuniadm"]));
					$ls_coduniadm= ($rs_data->fields["minorguniadm"].'-'.$rs_data->fields["ofiuniadm"].'-'.$rs_data->fields["uniuniadm"].'-'.$rs_data->fields["depuniadm"].'-'.$rs_data->fields["prouniadm"]);
					$ls_sueact=trim ($rs_data->fields["sueper"]);						
			
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$rs_data->fields['codper']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($rs_data->fields['codper']."^javascript:aceptar_persona_movimiento(\"$ls_codper\",\"$ls_nomper\", \"$ls_apeper\",\"$ls_cargo\",\"$ls_uniadm\",\"$ls_sueact\", \"$ls_codperdestino\",\"$ls_nomperdestino\", \"$ls_caractdestino\", \"$ls_uniadmdestino\", \"$ls_sueactdestino\",\"$ls_codcar\",\"$ls_codcardestino\",\"$ls_coduniadm\",\"$ls_coduniadmdestino\",\"$ls_codnom\",\"$ls_codnomdestino\",\"$ls_paso\",\"$ls_pasodestino\",\"$ls_grado\",\"$ls_gradodestino\" );^_self"));
					
			
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($rs_data->fields['cedper']));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["apeper"]))));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["nomper"]))));												
					$row_->appendChild($cell);
					
					$rs_data->MoveNext();		
					
			}
			return $dom->saveXML();
		}
   break;
	case '12' :
    $ls_codperdestino="txtcodper";
	$ls_nomperdestino="txtnomper";
	$ls_apeperdestino="txtapeper";
	$ls_desprodestino="txtdespro";
	$ls_codprodestino="txtcodpro";
	$ls_nacperdestino="cmbnacper";
	$lb_valido=true;
		
	
				
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_sql=" SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, ".
				        " sno_personal.apeper, sno_personal.codpro, sno_profesion.despro, sno_personal.nacper ".
 			            " FROM sno_personal, sno_profesion  ".
						" WHERE  WHERE sno_profesion.codemp= sno_personal.codemp
						  AND sno_profesion.codpro= sno_personal.codpro
						  AND sno_personal.codper like '$as_codper'  ".
						" AND sno_personal.cedper like '$as_cedper'  ".
						" AND sno_personal.nomper like '$as_nomper'  ".
						" AND sno_personal.apeper like '$as_apeper'  ".
						" ORDER BY sno_personal.codper LIMIT 500"; 
				break;
			case "POSTGRES":
				$ls_sql=" SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, ".
				        " sno_personal.apeper,sno_personal.codpro, sno_profesion.despro, sno_personal.nacper ".
 			            " FROM sno_personal, sno_profesion  ".
						" WHERE sno_profesion.codemp= sno_personal.codemp
						  AND sno_profesion.codpro= sno_personal.codpro
						  AND sno_personal.codper like '$as_codper'  ".
						" AND sno_personal.cedper like '$as_cedper'  ".
						" AND sno_personal.nomper like '$as_nomper'  ".
						" AND sno_personal.apeper like '$as_apeper'  ".
						" ORDER BY sno_personal.codper LIMIT 500"; 
				break;
		}
			  
	 
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_buscar_personal( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while (!$rs_data->EOF) 
			{
			     
					$ls_codper=$rs_data->fields["cedper"];
					$ls_nomper=trim (htmlentities ($rs_data->fields["nomper"]));
					$ls_apeper=trim (htmlentities ($rs_data->fields["apeper"]));
					$ls_codpro=trim ($rs_data->fields["codpro"]);
					$ls_despro=trim (htmlentities ($rs_data->fields["despro"]));
					$ls_nacper=trim ($rs_data->fields["nacper"]);
			
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$rs_data->fields['codper']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($rs_data->fields['codper']." ^javascript:aceptar_persona_contrato(\"$ls_codper\",			 \"$ls_nomper\", \"$ls_apeper\",\"$ls_codperdestino\",  \"$ls_nomperdestino\", \"$ls_apeperdestino\",\"$ls_codpro\",\"$ls_codprodestino\",\"$ls_despro\",\"$ls_desprodestino\",\"$ls_nacper\",\"$ls_nacperdestino\");^_self"));
					
			
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($rs_data->fields['cedper']));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["apeper"]))));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["nomper"]))));												
					$row_->appendChild($cell);
					
					$rs_data->MoveNext();
			}
			return $dom->saveXML();
		}
   break; 
    case '13' :
    $ls_codperdestino="txtcodper";
	$ls_nomperdestino="txtnomper";
	$ls_tipperdestino="txttipper";
	$lb_valido=true;
		
	
				
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_sql=" SELECT DISTINCT (sno_personalnomina.codper),sno_personal.cedper,sno_personal.nomper, ".
				  		" sno_personal.apeper, sno_tipopersonalsss.dentippersss ".
						" FROM sno_personal, sno_personalnomina, sno_tipopersonalsss   ".
						" WHERE sno_personalnomina.codper=sno_personal.codper   ".
						" AND sno_personalnomina.codemp=sno_personal.codemp     ".
						" AND sno_personalnomina.staper='1'".
						" AND sno_personal.codper like '$as_codper'  ".
						" AND sno_personal.cedper like '$as_cedper'  ".
						" AND sno_personal.nomper like '$as_nomper'  ".
						" AND sno_personal.apeper like '$as_apeper'  ".
						" AND sno_personal.codtippersss = sno_tipopersonalsss.codtippersss ".
						" GROUP BY sno_personalnomina.codper,  sno_personal.cedper, sno_personal.nomper, ".
						" sno_personal.apeper, sno_tipopersonalsss.dentippersss  ".
						" ORDER BY sno_personalnomina.codper LIMIT 500";
				break;
			case "POSTGRES":
				$ls_sql=" SELECT DISTINCT (sno_personalnomina.codper),sno_personal.cedper,sno_personal.nomper, ".
				  		" sno_personal.apeper, sno_tipopersonalsss.dentippersss ".
						" FROM sno_personal, sno_personalnomina, sno_tipopersonalsss   ".
						" WHERE sno_personalnomina.codper=sno_personal.codper   ".
						" AND sno_personalnomina.codemp=sno_personal.codemp     ".
						" AND sno_personalnomina.staper='1'".
						" AND sno_personal.codper like '$as_codper'  ".
						" AND sno_personal.cedper like '$as_cedper'  ".
						" AND sno_personal.nomper like '$as_nomper'  ".
						" AND sno_personal.apeper like '$as_apeper'  ".
						" AND sno_personal.codtippersss = sno_tipopersonalsss.codtippersss ".
						" GROUP BY sno_personalnomina.codper,  sno_personal.cedper, sno_personal.nomper, ".
						" sno_personal.apeper, sno_tipopersonalsss.dentippersss  ".
						" ORDER BY sno_personalnomina.codper LIMIT 500";
				break;
		}
			  
	 
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_buscar_personal( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while (!$rs_data->EOF) 
			{
			     
					$ls_codper=$rs_data->fields["codper"];
					$ls_nomper=trim (htmlentities ($rs_data->fields["nomper"]));
					$ls_apeper=trim (htmlentities ($rs_data->fields["apeper"]));
					$ls_dentippersss= htmlentities  ($rs_data->fields["dentippersss"]);
					
			
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$rs_data->fields['codper']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($rs_data->fields['codper']." ^javascript:aceptar_bono_merito(\"$ls_codper\",			 \"$ls_nomper\", \"$ls_apeper\",\"$ls_codperdestino\",  \"$ls_nomperdestino\", \"$ls_dentippersss\",\"$ls_tipperdestino\" );^_self"));
					
			
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($rs_data->fields['cedper']));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["apeper"]))));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["nomper"]))));												
					$row_->appendChild($cell);
					
					$rs_data->MoveNext();
			}
			return $dom->saveXML();
		}
   break;
   
   case '14' :
	 
	  
		$ls_cedperdestino="txtcodper";
		$ls_nomperdestino="txtnomper";
		$ls_apeperdestino="txtapeper";
		$ls_dirperdestino="txtdirper";
		$ls_fecnacperdestino="txtfecnacper";
		$ls_edocivperdestino="cmbedocivper";
		$ls_nacperdestino="cmbnacper";
		$ls_codpaidestino="cmbcodpainac";
		$ls_codestdestino="hidcodestnac";
		
		$ls_telhabperdestino="txttelhabper";
		$ls_coreleperdestino="txtcoreleper";
		$ls_telmovperdestino="txttelmovper";
		$ls_sexperdestino="cmbsexper";
		$ls_estaperdestino="txtestaper";
		
		$ls_codprodestino="txtcodpro";
		$ls_desprodestino="txtdespro";
		$ls_nivacaperdestino="cmbnivacaper";
		

		$lb_valido=true;
		
	
				
		$ls_sql= " SELECT sno_personal.*, sigesp_pais.despai, sigesp_estados.desest, ".
				 " sno_profesion.despro ".			
		 		 " FROM sigesp_pais, sigesp_estados,  sno_personal		          
		           LEFT JOIN sno_profesion ON (sno_profesion.codpro = sno_personal.codpro) ".			  
				 " WHERE codper like '$as_codper' ".
				 " AND cedper like '$as_cedper' ".
			 	 " AND nomper like '$as_nomper' ".
				 " AND apeper like '$as_apeper' ".
				 " AND sigesp_pais.codpai = sno_personal.codpai ".
				"   AND sigesp_estados.codpai = sno_personal.codpai ".
				"   AND sigesp_estados.codest = sno_personal.codest ".			
				 " ORDER BY codper  LIMIT 500";

	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_buscar_personal( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while (!$rs_data->EOF) 
			{
			     	$ls_cedper=$rs_data->fields["cedper"];
					$ls_nomper=trim (htmlentities ($rs_data->fields["nomper"]));
					$ls_apeper=trim (htmlentities ($rs_data->fields["apeper"]));
					$ls_dirper=htmlentities ($rs_data->fields["dirper"]);
					$ls_fecnacper=$this->io_funcion->uf_formatovalidofecha($rs_data->fields["fecnacper"]);
				    $ls_fecnacper=$this->io_funcion->uf_convertirfecmostrar($ls_fecnacper);
					$ls_edocivper=$rs_data->fields["edocivper"];
					$ls_nacper=$rs_data->fields["nacper"];
					$ls_codpai=$rs_data->fields["codpai"];
					$ls_codest=$rs_data->fields["codest"];
					
					$ls_telhabper=$rs_data->fields["telhabper"];
					$ls_coreleper=$rs_data->fields["coreleper"];
					$ls_telmovper=$rs_data->fields["telmovper"];
					$ls_sexper=$rs_data->fields["sexper"];
					
					$ls_codpro=$rs_data->fields["codpro"];
					$ls_despro=htmlentities ($rs_data->fields["despro"]);
					$ls_nivacaper=trim ($rs_data->fields["nivacaper"]);
									
					
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$rs_data->fields['codper']);
					$cell = $row_->appendChild($dom->createElement('cell'));
					   
					
					$cell->appendChild($dom->createTextNode($rs_data->fields['codper']." ^javascript:aceptar_personal_concurso(\"$ls_cedper\", \"$ls_nomper\", \"$ls_apeper\",	\"$ls_dirper\",	\"$ls_fecnacper\", 	\"$ls_edocivper\", 	\"$ls_nacper\", \"$ls_codpai\",	\"$ls_codest\",	 \"$ls_telhabper\", 	\"$ls_coreleper\",\"$ls_telmovper\", \"$ls_sexper\",  \"$ls_codpro\",\"$ls_despro\", \"$ls_nivacaper\",	 \"$ls_cedperdestino\", \"$ls_nomperdestino\", \"$ls_apeperdestino\",	\"$ls_dirperdestino\",	\"$ls_fecnacperdestino\", \"$ls_edocivperdestino\", \"$ls_nacperdestino\", \"$ls_codpaidestino\",	\"$ls_codestdestino\", \"$ls_telhabperdestino\", 	\"$ls_coreleperdestino\",	\"$ls_telmovperdestino\", \"$ls_sexperdestino\",\"$ls_codprodestino\", \"$ls_desprodestino\", \"$ls_nivacaperdestino\");^_self"));
					
			
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($rs_data->fields['cedper']));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["apeper"]))));												
					$row_->appendChild($cell);
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode(trim (htmlentities ($rs_data->fields["nomper"]))));												
					$row_->appendChild($cell);
					
					$rs_data->MoveNext();
			}
			return $dom->saveXML();
		}
   break;
   
	 
		} // fin del Case
		
	} // end function buscar_personal
	
//-----------------------------------------------------------------------------------------------------------------------------

public function getCodPersonal($as_codper,&$ao_datos="")
  	  {
	  
	  
	   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: getCodPersonal																                    
		//      Argumento: $as_codper   //  código del personal										                        
		//                 $$ao_datos   //  arreglo con datos del personal                                         
		//	      Returns: Retorna un XML  																						
		//    Description: Funcion busca un personal en la tabla sno_personal  dado el código del personal                    
		//	   Creado Por: Ing. Luiser Blanco																				    						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  
	  
		    $lb_valido=false;
			$ls_sql = " SELECT * FROM sno_personal ".
					  " WHERE codemp='". $this->ls_codemp."'".
					  " AND  codper = '$as_codper'";
			$rs_data=$this->io_sql->select($ls_sql);
			
			if($rs_data===false)
			{
					$this->io_msg->message("CLASE->sigesp_srh_c_personal MÉTODO->getCodPersonal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
					
			}
			else
			{
					
					if(!$row=$this->io_sql->fetch_row($rs_data))
					{
					 
					}
					else
					{
						$lb_valido=true;
					}
					
					$this->io_sql->free_result($rs_data);
			}
			return $lb_valido;
	  }	// end function getPersonal
	  

//------------------------------------------------------------------------------------------------------------------------------
	  
public function getCedPersonal($as_cedper,&$ao_datos="")
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
			$ls_codper="";
			$ls_sql = " SELECT * FROM sno_personal ".
					  " WHERE codemp='". $this->ls_codemp."'".
					  " AND  cedper = '$as_cedper'";
					
				
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
					$this->io_msg->message("CLASE->sigesp_srh_c_personal MÉTODO->getCedPersonal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
				
			}
			else
			{
					
					if($row=$this->io_sql->fetch_row($rs_data))
					{
						$lb_existe=true;
						$ls_codper=$row['codper'];
					}
					
					$this->io_sql->free_result($rs_data);
			}
			return array($lb_existe,$ls_codper);
	  }	// end function getPersonal
	  




/////////////////////////////// FUNCIONES PARA EL MANEJO DE LOS ESTUDIOS DEL PERSONAL  ///////////////////////////////


	  
//----------------------------------------------------------------------------------------------------------------------------
	function uf_srh_getProximoCodigo_estudio($as_codper)
    {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getProximoCodigo_estudio
		//         Access: public (sigesp_srh_d_personal)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de estudio
		//    Description: Funcion que genera un código nuevo de estudios
		//	   Creado Por: Ing. Rivero Jennifer
		// Fecha Creación:17/03/2008							Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  

		$ls_sql = "SELECT MAX(codestrea) AS codigo FROM sno_estudiorealizado WHERE codper = '".$as_codper."'";
		
    	$lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
			if (!$lb_hay)
    		  $ls_codest = $la_datos["codigo"][0];///si no tiene esetudios.......
	 
			if ($lb_hay)
   			  $ls_codest = $la_datos["codigo"][0]+1; 
    	return $ls_codest;
     } 
	 

//----------------------------------------------------------------------------------------------------------------------------

function uf_srh_insert_estudiorealizado($as_codper,$ai_codestrea,$as_tipestrea,$as_insestrea,$as_titestrea,$ai_calestrea,
										$ad_fecgraestrea,$as_escval,$ad_feciniact,$ad_fecfinact,$as_desestrea,$as_aprestrea,
										$as_anoaprestrea,$as_horestrea,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_estudiorealizado
		//		   Access: private
		//	    Arguments: as_codper  // Código del Personal
		//				   ai_codestrea  // Código
		//				   as_tipestrea  // tipo
		//				   as_insestrea  // intituto
		//				   as_titestrea  // titulo obtenido
		//				   ai_calestrea  // calificación
		//				   ad_fecgraestrea  // fecha grado
		//				   as_escval  // escala de valoración del estudio
		//				   ad_feciniact  // fecha de inicio del estudio
		//				   ad_fecfinact  // fecha de finalización del estudio
		//				   as_desestrea  // Descripción de Estudio Realizado
		//				   as_aprestrea  // Aprobación del Estudio Realizado
		//				   as_anoaprestrea  // Años de Aprobación de Estudio Realizado
		//				   as_horestrea  // Horas del Estudio Realizado
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el estudio realizado asociado a un personal
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if ($ai_calestrea=" ")
		{
		 $ai_calestrea='0';
		}
		if ($as_horestrea=" ")
		{
		 $as_horestrea='0';
		}
		$ls_sql="INSERT INTO sno_estudiorealizado".
				"(codemp,codper,codestrea,tipestrea,insestrea,titestrea,calestrea,fecgraestrea,escval,feciniact,fecfinact,".
				" desestrea,aprestrea,anoaprestrea,horestrea)".
				"VALUES('".$this->ls_codemp."','".$as_codper."',".$ai_codestrea.",'".$as_tipestrea."','".$as_insestrea."',".
				"'".$as_titestrea."',".$ai_calestrea.",'".$ad_fecgraestrea."','".$as_escval."','".$ad_feciniact."','".$ad_fecfinact."',".
				"'".$as_desestrea."','".$as_aprestrea."','".$as_anoaprestrea."','".$as_horestrea."')"; 		
				
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_msg->message("CLASE->Personal  MÉTODO->uf_srh_insert_estudiorealizado ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			print ($this->io_sql->message);
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Estudio Realizado ".$ai_codestrea." asociado al personal ".$as_codper;
			$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
  	      		$this->io_msg->message("CLASE->Personal  MÉTODO->uf_srh_insert_estudiorealizado ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end uf_srh_insert_estudiorealizado

//----------------------------------------------------------------------------------------------------------------------------
	function uf_srh_update_estudiorealizado($as_codper,$ai_codestrea,$as_tipestrea,$as_insestrea,$as_titestrea,$ai_calestrea,
										$ad_fecgraestrea,$as_escval,$ad_feciniact,$ad_fecfinact,$as_desestrea,$as_aprestrea,
										$as_anoaprestrea,$as_horestrea,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_estudiorealizado
		//		   Access: private
		//	    Arguments: as_codper  // Código del Personal
		//				   ai_codestrea  // Código
		//				   as_tipestrea  // tipo
		//				   as_insestrea  // intituto
		//				   as_titestrea  // titulo obtenido
		//				   ai_calestrea  // calificación
		//				   ad_fecgraestrea  // fecha grado
		//				   as_escval  // escala de valoración del estudio
		//				   ad_feciniact  // fecha de inicio del estudio
		//				   ad_fecfinact  // fecha de finalización del estudio
		//				   as_desestrea  // Descripción de Estudio Realizado
		//				   as_aprestrea  // Aprobación del Estudio Realizado
		//				   as_anoaprestrea  // Años de Aprobación de Estudio Realizado
		//				   as_horestrea  // Horas del Estudio Realizado
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el estudio realizado asociado a un personal
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_estudiorealizado ".
				"   SET tipestrea='".$as_tipestrea."', ".
				"		insestrea='".$as_insestrea."', ".
				"		desestrea='".$as_desestrea."', ".
				"		titestrea='".$as_titestrea."', ".
				"		calestrea=".$ai_calestrea.", ".
				"		fecgraestrea='".$ad_fecgraestrea."', ".
				"		escval='".$as_escval."', ".
				"		feciniact='".$ad_feciniact."', ".
				"		fecfinact='".$ad_fecfinact."', ".
				"		aprestrea='".$as_aprestrea."' , ".
				"		anoaprestrea='".$as_anoaprestrea."', ".
				"		horestrea='".$as_horestrea."' ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND codestrea=".$ai_codestrea."";
				
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_msg->message("CLASE->Personal  MÉTODO->uf_srh_update_estudiorealizado ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Estudio Realizado ".$ai_codestrea." asociado al personal ".$as_codper;
			$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
     	   		
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_srh_update_estudiorealizado

//----------------------------------------------------------------------------------------------------------------------------
	function uf_srh_select_estudiorealizado($as_codper, $ai_codestrea)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_estudiorealizado
		//		   Access: private
		//	    Arguments: as_codper  // código del personal
		//				   ai_codestrea  // código estudio realizado
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el estudiorealizado está registrado
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codestrea FROM sno_estudiorealizado ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND codestrea='".$ai_codestrea."'";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->Personal  MÉTODO->uf_srh_select_estudiorealizado ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_srh_select_estudiorealizado

//----------------------------------------------------------------------------------------------------------------------------
	function uf_srh_guardar_estudios($ao_estudio,$as_operacion="insertar", $aa_seguridad)
	    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_estudios
		//         Access: public (sigesp_srh_d_personal)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de estudio
		//    Description: Funcion que guarda los estudios del personal
		//	   Creado Por: Ing. Rivero Jennifer
		// Fecha Creación:19/03/2008							Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	{  		    
		$ao_estudio->fecgraestrea=$this->io_funcion->uf_convertirdatetobd($ao_estudio->fecgraestrea);		
		$ao_estudio->feciniact=$this->io_funcion->uf_convertirdatetobd($ao_estudio->feciniact);
		$ao_estudio->fecfinact=$this->io_funcion->uf_convertirdatetobd($ao_estudio->fecfinact);
		$ao_estudio->calestrea=str_replace(".","",$ao_estudio->calestrea);
		$ao_estudio->calestrea=str_replace(",",".",$ao_estudio->calestrea);		
		$lb_valido=false;
		switch ($as_operacion)
		{
			case "insertar":
				if($this->uf_srh_select_estudiorealizado($ao_estudio->codper,$ao_estudio->codestrea)===false)
				{					
					if(!$lb_valido)
					{   
					   $lb_valido=$this->uf_srh_insert_estudiorealizado($ao_estudio->codper,$ao_estudio->codestrea,$ao_estudio->tipestrea,
																	 $ao_estudio->insestrea,$ao_estudio->titestrea,$ao_estudio->calestrea,
																	 $ao_estudio->fecgraestrea,$ao_estudio->escval,$ao_estudio->feciniact,
																	 $ao_estudio->fecfinact,$ao_estudio->desestrea,$ao_estudio->aprestrea,
																	 $ao_estudio->anoaprestrea,$ao_estudio->horestrea,$aa_seguridad);
					}
				}
				else
				{
					$this->io_msg->message("El Estudio Realizado ya existe, no lo puede incluir.");
				}
				break;
							
			case "modificar":
				if(($this->uf_srh_select_estudiorealizado($ao_estudio->codper,$ao_estudio->codestrea)))
				{
				   $lb_valido=$this->uf_srh_update_estudiorealizado($ao_estudio->codper,$ao_estudio->codestrea,$ao_estudio->tipestrea,
																 $ao_estudio->insestrea,$ao_estudio->titestrea,$ao_estudio->calestrea,
																 $ao_estudio->fecgraestrea,$ao_estudio->escval,$ao_estudio->feciniact,
																 $ao_estudio->fecfinact,$ao_estudio->desestrea,$ao_estudio->aprestrea,
																 $ao_estudio->anoaprestrea,$ao_estudio->horestrea,$aa_seguridad);
				}
				else
				{
					$this->io_msg->message("El Estudio Realizado no existe, no lo puede actualizar.");
				}
				break;
		}		
		return $lb_valido;
	}// end function uf_srh_guardar
  	
//-------------------------------------------------------------------------------------------------------------------------------
	
	function uf_srh_buscar_estudios($as_codper)
	{		
	    				/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_estudios
		//		   Access: private
		//	    Arguments: as_codper  // código del personal
		//	  Description: Funcion que buscas los estudios realizados por un Personal dado el código del perosnal y
		//                 crea un XML para mostrarlo en un catalogo
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		
		
		$ls_codestreadestino="txtcodestrea";
		$ls_tipestareadestino="cmbtipestrea";
		$ls_insestreadestino="txtinsestrea";
		$ls_desestreadestino="txtdesestrea";
		$ls_titestreadestino="txttitestrea";
		$ls_calestreadestino="txtcalestrea";
		$ls_escevaldestino="txtescval";
		$ls_aprestareadestino="cmbaprestrea";
		$ls_anoaprestreadestino="txtanoaprestrea";
		$ls_horestreadestino="txthorestrea";
		$ls_feciniactdestino="txtfeciniact";
		$ls_fecfinactdestino="txtfecfinact";
		$ls_fecgraestreadestino="txtfecgraestrea";
		
		$lb_valido=true;
		
		$ls_sql= "select * from sno_estudiorealizado where codper='".$as_codper."'    ORDER BY codestrea "; 
		
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Personal MÉTODO->uf_srh_buscar_estudios( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}		
		 else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);	
			 		
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			    $ls_codestrea=$row["codestrea"];
				$ls_tipestarea=$row["tipestrea"];
				$ls_insestrea=htmlentities ($row["insestrea"]);
				$ls_desestrea=htmlentities ($row["desestrea"]);
				$ls_titestrea=htmlentities ($row["titestrea"]);
				$ls_calestrea=$row["calestrea"];
				$ls_esceval=$row["escval"];
				$ls_aprestarea=$row["aprestrea"];
				$ls_anoaprestrea=$row["anoaprestrea"];
				$ls_horestrea=$row["horestrea"];
				
				$ls_feciniact=$this->io_funcion->uf_formatovalidofecha($row["feciniact"]);
				$ls_feciniact=$this->io_funcion->uf_convertirfecmostrar($ls_feciniact);
				
				$ls_fecfinact=$this->io_funcion->uf_formatovalidofecha($row["fecfinact"]);
				$ls_fecfinact=$this->io_funcion->uf_convertirfecmostrar($ls_fecfinact);
				
				$ls_fecgraestrea=$this->io_funcion->uf_formatovalidofecha($row["fecgraestrea"]);
				$ls_fecgraestrea=$this->io_funcion->uf_convertirfecmostrar($ls_fecgraestrea);
								
				$row_ = $team->appendChild($dom->createElement('row'));
				$row_->setAttribute("id",$row['codestrea']);
				$cell = $row_->appendChild($dom->createElement('cell'));   
				
			
			
				$cell->appendChild($dom->createTextNode($row['codestrea']." ^javascript:aceptar
				( \"$ls_codestrea\",  \"$ls_tipestarea\",  \"$ls_insestrea\",  \"$ls_desestrea\",  \"$ls_titestrea\",  \"$ls_calestrea\", \"$ls_esceval\", \"$ls_aprestarea\", \"$ls_anoaprestrea\", \"$ls_horestrea\",
				  \"$ls_feciniact\", \"$ls_fecfinact\", \"$ls_fecgraestrea\",
				  \"$ls_codestreadestino\",  \"$ls_tipestareadestino\",  \"$ls_insestreadestino\",  \"$ls_desestreadestino\",  \"$ls_titestreadestino\",  \"$ls_calestreadestino\", \"$ls_escevaldestino\", \"$ls_aprestareadestino\", \"$ls_anoaprestreadestino\",
				   \"$ls_horestreadestino\", \"$ls_feciniactdestino\", \"$ls_fecfinactdestino\", \"$ls_fecgraestreadestino\");^_self"));
				
							
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_desestrea));												
				$row_->appendChild($cell);
				
				
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_titestrea));												
				$row_->appendChild($cell);
				
				
			}
			return $dom->saveXML();
		
			
			
		
		}	   
	} 
	
//-------------------------------------------------------------------------------------------------------------------------------
	function uf_srh_eliminar_estudio ($as_codest, $as_codper, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_elimnar_estudio																													
		//      Argumento: $as_codest        //  código del estudio
		//                 $as_codper        // código del personal
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina un estudio realizado en la tabla sno_estudiorealizado                        
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 06/05/2008							Fecha Última Modificación: 						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
	
    $ls_sql = "DELETE FROM sno_estudiorealizado ".
	          "WHERE codestrea = '$as_codest' AND codper = '$as_codper'   AND codemp='".$this->ls_codemp."'";

  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_elimnar_estudio ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el estudio realizado de la persona".$as_codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				
					$this->io_sql->commit();
			}
	
	return $lb_valido;
  }
//-------------------------------------------------------------------------------------------------------------------------------



/////////////////////////////// FUNCIONES PARA EL MANEJO DE LOS TRABAJOS ANTERIORES DEL PERSONAL  ///////////////////////////////

//-------------------------------------------------------------------------------------------------------------------------------
 function uf_srh_getProximoCodigo_trabajo($as_codper)
    {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getProximoCodigo_estudio
		//         Access: public (sigesp_srh_d_personal)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de estudio
		//    Description: Funcion que genera un código nuevo de estudios
		//	   Creado Por: Ing. Rivero Jennifer
		// Fecha Creación:17/03/2008							Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  		$ls_sql = "SELECT MAX(codtraant) AS codigo FROM sno_trabajoanterior WHERE codper = '".$as_codper."'";
		
    	$lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
			if (!$lb_hay)
    		  $ls_codtrab = $la_datos["codigo"][0];///si no tiene esetudios.......
	 
			if ($lb_hay)
   			  $ls_codtrab = $la_datos["codigo"][0]+1; 
    	return $ls_codtrab;
     } 
	 
//-------------------------------------------------------------------------------------------------------------------------------

function uf_srh_insert_trabajoanterior($as_codper,$ai_codtraant,$as_emptraant,$as_ultcartraant,$ai_ultsuetraant,
				 					   $ad_fecingtraant,$ad_fecrettraant,$as_emppubtraant,$as_codded,$ai_anolab,$ai_meslab,
									   $ai_dialab,$aa_seguridad)
	{	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_trabajoanterior
		//		   Access: private
		//	    Arguments: as_codper // Código de Personal
		//			       ai_codtraant  // Código de trabajo anterior
		//			       as_emptraant  // empresa
		//			       as_ultcartraant  // último cargo
		//			       ai_ultsuetraant  // último sueldo
		//			       ad_fecingtraant  // Fecha de ingreso del trabajo
		//			       ad_fecrettraant  // Fecha de Retiro del trabajo
		//			       as_emppubtraant  // Si la empresa fué pública
		//			       as_codded  // Código de Dedicación
		//			       ai_anolab  // Años Laborados
		//			       ai_meslab  // Meses Laborados
		//			       ai_dialab  // Días Laborados
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el trabajo anterior
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_ano1=0;
		$ls_ano2=0;
		$ls_sql="INSERT INTO sno_trabajoanterior".
				"(codemp,codper,codtraant,emptraant,ultcartraant,ultsuetraant,fecingtraant,fecrettraant,emppubtraant,".
				"codded,anolab,meslab,dialab) VALUES ('".$this->ls_codemp."','".$as_codper."',".$ai_codtraant.",'".$as_emptraant."',".
				"'".$as_ultcartraant."',".$ai_ultsuetraant.",'".$ad_fecingtraant."','".$ad_fecrettraant."','".$as_emppubtraant."',".
				"'".$as_codded."',".$ai_anolab.",".$ai_meslab.",".$ai_dialab.")";
				
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_msg->message("CLASE->Trabajo Anterior MÉTODO->uf_srh_insert_trabajoanterior ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Trabajo anterior ".$ai_codtraant." asociada al personal ".$as_codper;
			$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				
				$this->io_sql->commit();			
				if (($as_emppubtraant=='1')&&(($as_codded=='100') || ($as_codded=='200')))
				{
					$ls_ano1=$this->uf_select_anotrabajoantfijo($as_codper);					
					$this->uf_update_años_servicio_previo ($as_codper,'anoservpreper', $ls_ano1);
					
				}
				elseif(($as_emppubtraant=='1')&&($as_codded=='300'))
				{
					$ls_ano2=$this->uf_select_anotrabajoantcontratado($as_codper);
					$this->uf_update_años_servicio_previo ($as_codper, 'anoservprecont', $ls_ano2);				
					
				}
			}
			else
			{
				$lb_valido=false;
        		$this->io_msg->message("CLASE->Trabajo Anterior MÉTODO->uf_srh_insert_trabajoanterior ERROR->"); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_srh_insert_trabajoanterior
	
//-------------------------------------------------------------------------------------------------------------------------------
	function uf_srh_update_trabajoanterior($as_codper,$ai_codtraant,$as_emptraant,$as_ultcartraant,$ai_ultsuetraant,
				   					   $ad_fecingtraant,$ad_fecrettraant,$as_emppubtraant,$as_codded,$ai_anolab,$ai_meslab,
									   $ai_dialab,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_trabajoanterior
		//		   Access: private
		//	    Arguments: as_codper // Código de Personal
		//			       ai_codtraant  // Código de trabajo anterior
		//			       as_emptraant  // empresa
		//			       as_ultcartraant  // último cargo
		//			       ai_ultsuetraant  // último sueldo
		//			       ad_fecingtraant  // Fecha de ingreso del trabajo
		//			       ad_fecrettraant  // Fecha de Retiro del trabajo
		//			       as_emppubtraant  // Si la empresa fué pública
		//			       as_codded  // Código de Dedicación
		//			       ai_anolab  // Años Laborados
		//			       ai_meslab  // Meses Laborados
		//			       ai_dialab  // Días Laborados
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el estudio realizado
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_trabajoanterior ".
				"   SET emptraant='".$as_emptraant."', ".
				"       ultcartraant='".$as_ultcartraant."', ".
				"  		ultsuetraant=".$ai_ultsuetraant.", ".
				"  		fecingtraant='".$ad_fecingtraant."', ".
				"  		fecrettraant='".$ad_fecrettraant."', ".
				"  		emppubtraant='".$as_emppubtraant."', ".
				"  		codded='".$as_codded."', ".
				"  		anolab=".$ai_anolab.", ".
				"  		meslab=".$ai_meslab.", ".
				"  		dialab=".$ai_dialab." ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND codtraant=".$ai_codtraant."";
		
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{

			$lb_valido=false;
        	$this->io_msg->message("CLASE->Trabajo Anterior MÉTODO->uf_srh_update_trabajoanterior ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Trabajo anterior ".$ai_codtraant." asociada al personal ".$as_codper;
			$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				
				$this->io_sql->commit();
				if (($as_emppubtraant=='1')&&(($as_codded=='100') || ($as_codded=='200')))
				{
					$ls_ano1=$this->uf_select_anotrabajoantfijo($as_codper);					
					$this->uf_update_años_servicio_previo ($as_codper,'anoservpreper', $ls_ano1);
					
				}
				elseif(($as_emppubtraant=='1')&&($as_codded=='300'))
				{
					$ls_ano2=$this->uf_select_anotrabajoantcontratado($as_codper);
					$this->uf_update_años_servicio_previo ($as_codper, 'anoservprecont', $ls_ano2);				
					
				}
			}
			else
			{
				$lb_valido=false;
        		$this->io_msg->message("CLASE->Trabajo Anterior MÉTODO->uf_srh_update_trabajoanterior ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_srh_update_trabajoanterior
	
//-------------------------------------------------------------------------------------------------------------------------------
	function uf_srh_select_trabajoanterior($as_codper, $ai_codtraant)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_trabajoanterior
		//		   Access: public (sigesp_snorh_d_trabajoanterior)
		//	    Arguments: as_codper // Código de Personal
		//			       ai_codtraant  // Código de trabajo anterior
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el trabajo anterior está registrado
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codtraant ".
				"  FROM sno_trabajoanterior ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND codtraant='".$ai_codtraant."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->Trabajo Anterior MÉTODO->uf_srh_select_trabajoanterior ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_srh_select_trabajoanterior
//-------------------------------------------------------------------------------------------------------------------------------
	function uf_srh_guardar_trabajo($ao_trabajo,$as_operacion="insertar", $aa_seguridad)
	    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_estudios
		//         Access: public (sigesp_srh_d_personal)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de estudio
		//    Description: Funcion que guarda los estudios del personal
		//	   Creado Por: Ing. Rivero Jennifer
		// Fecha Creación:19/03/2008							Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	{  				
		$ao_trabajo->ultsuetraant=str_replace(".","",$ao_trabajo->ultsuetraant);
		$ao_trabajo->ultsuetraant=str_replace(",",".",$ao_trabajo->ultsuetraant);				
		$ao_trabajo->fecingtraant=$this->io_funcion->uf_convertirdatetobd($ao_trabajo->fecingtraant);
		$ao_trabajo->fecrettraant=$this->io_funcion->uf_convertirdatetobd($ao_trabajo->fecrettraant);	
		
		$lb_valido=false;
		switch ($as_operacion)
		{
			case "insertar":
				if($this->uf_srh_select_trabajoanterior($ao_trabajo->codper,$ao_trabajo->codtraant)===false)
				{					
					if(!$lb_valido)
					{   
					   $lb_valido=$this->uf_srh_insert_trabajoanterior($ao_trabajo->codper,$ao_trabajo->codtraant,
					   											   $ao_trabajo->emptraant,$ao_trabajo->ultcartraant,
					   											   $ao_trabajo->ultsuetraant,$ao_trabajo->fecingtraant,
																   $ao_trabajo->fecrettraant,
																   $ao_trabajo->emppubtraant,$ao_trabajo->codded,
																   $ao_trabajo->anolab,$ao_trabajo->meslab,
																   $ao_trabajo->dialab,$aa_seguridad);
					}
				}
				else
				{
					
				}
				break;
							
			case "modificar":
				if(($this->uf_srh_select_trabajoanterior($ao_trabajo->codper,$ao_trabajo->codtraant)))
				{
				   $lb_valido=$this->uf_srh_update_trabajoanterior($ao_trabajo->codper,$ao_trabajo->codtraant,
				   											   $ao_trabajo->emptraant,$ao_trabajo->ultcartraant,
				   											   $ao_trabajo->ultsuetraant,
															   $ao_trabajo->fecingtraant,$ao_trabajo->fecrettraant,
															   $ao_trabajo->emppubtraant,$ao_trabajo->codded,
															   $ao_trabajo->anolab,$ao_trabajo->meslab,
															   $ao_trabajo->dialab,$aa_seguridad);
				}
				else
				{
					
				}
				break;
		}		
		return $lb_valido;
	}// end function uf_srh_guardar
//-------------------------------------------------------------------------------------------------------------------------------

function uf_srh_buscar_trabajos($as_codper)
	{		
	    
			    				/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_trabajos
		//		   Access: private
		//	    Arguments: as_codper  // código del personal
		//	  Description: Funcion que busca los trabajos anteriores de un Personal dado el código del perosnal y 
		//                 crea un XML para mostrarlo en un catalogo
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_codtraantdestino="txtcodtraant";
		$ls_emptraantdestino="txtemptraant";
		$ls_ultcartraantdestino="txtultcartraant";
		$ls_ultsuetraantdestino="txtultsuetraant";
		$ls_fecingtraantdestino="txtfecingtraant";
		$ls_fecrettraantdestino="txtfecrettraant";
		$ls_emppubtraantdestino="cmbemppubtraant";
		$ls_coddeddestino="txtcodded";
		$ls_desdeddestino="txtdesded";
		$ls_anolabdestino="txtanolab";
		$ls_meslabdestino="txtmeslab";
		$ls_dialabdestino="txtdialab";
		
		
		$lb_valido=true;
		
		$ls_sql= "select * from sno_trabajoanterior  left join sno_dedicacion on (sno_trabajoanterior.codded = sno_dedicacion.codded) where codper='".$as_codper."'
		          order by  codtraant"; 
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Personal MÉTODO->uf_srh_buscar_trabajos( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}		
		 else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);	
			 		
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
				$ls_codtraant=$row["codtraant"];
				$ls_emptraant=htmlentities ($row["emptraant"]);
				$ls_ultcartraant=htmlentities ($row["ultcartraant"]);
				$ls_ultsuetraant=$row["ultsuetraant"];
				$ls_fecingtraant= $this->io_funcion->uf_formatovalidofecha($row["fecingtraant"]);
				$ls_fecingtraant= $this->io_funcion->uf_convertirfecmostrar($ls_fecingtraant);
				$ls_fecrettraant= $this->io_funcion->uf_formatovalidofecha($row["fecrettraant"]);
				$ls_fecrettraant= $this->io_funcion->uf_convertirfecmostrar($ls_fecrettraant);
				$ls_emppubtraant=$row["emppubtraant"];
				$ls_codded=$row["codded"];
				$ls_desded=htmlentities  ($row["desded"]);
				$ls_anolab=$row["anolab"];
				$ls_meslab=$row["meslab"];
				$ls_dialab=$row["dialab"];
										
				$row_ = $team->appendChild($dom->createElement('row'));
				$row_->setAttribute("id",$row['codtraant']);
				$cell = $row_->appendChild($dom->createElement('cell'));   
							
				$cell->appendChild($dom->createTextNode($row['codtraant']." ^javascript:aceptar
				( \"$ls_codtraant\", \"$ls_emptraant\",\"$ls_ultcartraant\",\"$ls_ultsuetraant\",\"$ls_fecingtraant\",\"$ls_fecrettraant\",\"$ls_emppubtraant\",\"$ls_codded\",
			\"$ls_desded\",\"$ls_anolab\",\"$ls_meslab\",\"$ls_dialab\", \"$ls_codtraantdestino\", \"$ls_emptraantdestino\",\"$ls_ultcartraantdestino\",\"$ls_ultsuetraantdestino\",\"$ls_fecingtraantdestino\",\"$ls_fecrettraantdestino\",\"$ls_emppubtraantdestino\",\"$ls_coddeddestino\",
			\"$ls_desdeddestino\",\"$ls_anolabdestino\",\"$ls_meslabdestino\",\"$ls_dialabdestino\");^_self"));
				
							
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_emptraant));												
				$row_->appendChild($cell);
				
				
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_ultcartraant));												
				$row_->appendChild($cell);
				
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_anolab.' años '.$ls_meslab. ' meses '.$ls_dialab.' dias '));												
				$row_->appendChild($cell);
				
				
			}
			return $dom->saveXML();
		
			
			
		
		}	   
	} 
	
//-------------------------------------------------------------------------------------------------------------------------------


function uf_srh_eliminar_trabajo ($as_codtrabant, $as_codper, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_elimnar_trabajo																												
		//      Argumento: $as_codtrabant     //  código del trabajo anterior
		//                 $as_codper        // código del personal
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina un trabajo anterior en la tabla sno_trabajoanterior                       
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 06/05/2008							Fecha Última Modificación: 						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
	
    $ls_sql = "DELETE FROM sno_trabajoanterior ".
	          "WHERE codtraant = '$as_codtrabant' AND codper = '$as_codper'   AND codemp='".$this->ls_codemp."'";

  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_elimnar_trabajo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el trabajo anterior de la persona".$as_codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				
					$this->io_sql->commit();			
					$ls_ano1=$this->uf_select_anotrabajoantfijo($as_codper);					
					$this->uf_update_años_servicio_previo ($as_codper,'anoservpreper', $ls_ano1);
					$ls_ano2=$this->uf_select_anotrabajoantcontratado($as_codper);
					$this->uf_update_años_servicio_previo ($as_codper, 'anoservprecont', $ls_ano2);				
					
				
			}
	
	return $lb_valido;
  }


//-------------------------------------------------------------------------------------------------------------------------------


////////////////////// FUNCIONES PARA EL MANEJO DE LOS FAMILIARES DEL PERSONAL ////////////////////////

function uf_srh_select_familiar($as_codper, $as_cedfam)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_familiar
		//		   Access: private
		//	    Arguments: as_codper  // Código de Personal
		//				   as_cedfam  // Cédula del Familiar
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el familiar está registrado
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$lb_existe=true;
		$ls_sql="SELECT cedfam ".
		        "  FROM sno_familiar ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND cedfam='".$as_cedfam."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->Familiar MÉTODO->uf_srh_select_familiar ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_familiar
	//--------------------------------------------------------------------------------------------------------------------------------

function uf_srh_insert_familiar($as_codper,$as_cedfam,$as_nomfam,$as_apefam,$as_sexfam,$ad_fecnacfam,$as_nexfam,$ai_estfam,
								$ai_hcfam,$ai_hcmfam,$ai_hijesp, $ai_bonjug,$as_cedula,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_familiar
		//		   Access: private
		//	    Arguments: as_codper  // Código del Personal
		//				   as_cedfam  // Cedula
		//				   as_nomfam  // Nombre
		//				   as_apefam  // Apellido
		//				   as_sexfam  // Sexo
		//				   ad_fecnacfam  // Fecha Nacimiento
		//				   as_nexfam  // Nexo 
		//				   ai_estfam  // Estudio del familiar
		//				   ai_hcfam  // si el familiar tiene hc
		//				   ai_hcmfam //  si el personal tiene hcm
		//                 ai_hijesp // indica si es hijo especial
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el familiar
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_familiar".
				"(codemp,codper,cedfam,nomfam,apefam,sexfam,fecnacfam,nexfam,estfam,hcfam,hcmfam,hijesp,estbonjug,cedula)VALUES".
				"('".$this->ls_codemp."','".$as_codper."','".$as_cedfam."','".$as_nomfam."','".$as_apefam."',".
				"'".$as_sexfam."','".$ad_fecnacfam."','".$as_nexfam."','".$ai_estfam."','".$ai_hcfam."','".$ai_hcmfam."','".$ai_hijesp."', '".$ai_bonjug."','".$as_cedula."')";				
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_msg->message("CLASE->Familiar MÉTODO->uf_srh_insert_familiar ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Familiar ".$as_cedfam." asociado al personal ".$as_codper;
			$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_msg->message("CLASE->Familiar MÉTODO->uf_srh_insert_familiar ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_srh_insert_familiar
	//--------------------------------------------------------------------------------------------------------------------------------

function uf_srh_update_familiar($as_codper,$as_cedfam,$as_nomfam,$as_apefam,$as_sexfam,$ad_fecnacfam,$as_nexfam,$ai_estfam,
								$ai_hcfam,$ai_hcmfam,$ai_hijesp,$ai_bonjug,$as_cedula,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_familiar
		//		   Access: private
		//	    Arguments: as_codper  // Código del Personal
		//				   as_cedfam  // Cedula
		//				   as_nomfam  // Nombre
		//				   as_apefam  // Apellido
		//				   as_sexfam  // Sexo
		//				   ad_fecnacfam  // Fecha Nacimiento
		//				   as_nexfam  // Nexo 
		//				   ai_estfam  // Estudio del familiar
		//				   ai_hcfam  // si el familiar tiene hc
		//				   ai_hcmfam //  si el personal tiene hcm
		//                 ai_hijesp // indica si es hijo especial
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el familiar
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_familiar ".
				"   SET nomfam='".$as_nomfam."', ".
				"		apefam='".$as_apefam."', ".
				"		sexfam='".$as_sexfam."', ".
				"		fecnacfam='".$ad_fecnacfam."', ".
				"		nexfam='".$as_nexfam."', ".
				"		estfam='".$ai_estfam."', ".
				"		hcfam='".$ai_hcfam."', ".
				"		hcmfam='".$ai_hcmfam."', ".
				"		hijesp='".$ai_hijesp."', ".
				"       estbonjug='".$ai_bonjug."', ".
				"		cedula='".$as_cedula."' ".				
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND cedfam='".$as_cedfam."'";
		
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_msg->message("CLASE->Familiar MÉTODO->uf_srh_update_familiar ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
			print ($this->io_sql->message);
			die;
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Familiar ".$as_cedfam." asociado al personal ".$as_codper;
			$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
	        	$this->io_msg->message("CLASE->Familiar MÉTODO->uf_srh_update_familiar ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_srh_update_familiar
	//--------------------------------------------------------------------------------------------------------------------------------

function uf_srh_guardar_familiar($ao_familiar,$as_operacion="insertar", $aa_seguridad)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_familiar
		//		   Access: public (sigesp_snorh_d_familiar)
		//	    Arguments: as_codper  // Código del Personal
		//				   as_cedfam  // Cedula
		//				   as_nomfam  // Nombre
		//				   as_apefam  // Apellido
		//				   as_sexfam  // Sexo
		//				   ad_fecnacfam  // Fecha Nacimiento
		//				   as_nexfam  // Nexo 
		//				   ai_estfam // Estudio del familiar 
		//				   ai_hcfam  // si el familiar tiene hc
		//				   ai_hcmfam //  si el personal tiene hcm
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el familiar
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ao_familiar->fecnacfam=$this->io_funcion->uf_convertirdatetobd($ao_familiar->fecnacfam);
		$lb_valido=false;	
		switch ($as_operacion)
		{
			case "insertar":
				if($this->uf_srh_select_familiar($ao_familiar->codper,$ao_familiar->cedfam)===false)
				{
					$lb_valido=$this->uf_srh_insert_familiar($ao_familiar->codper,$ao_familiar->cedfam,
														 $ao_familiar->nomfam,$ao_familiar->apefam,
														 $ao_familiar->sexfam,$ao_familiar->fecnacfam,
														 $ao_familiar->nexfam,$ao_familiar->estfam,
														 $ao_familiar->hcfam,$ao_familiar->hcmfam,$ao_familiar->hijesp,
														 $ao_familiar->bonjug,$ao_familiar->cedula, $aa_seguridad);
				}
				else
				{
					
				}
				break;
							
			case "modificar":
				if(($this->uf_srh_select_familiar($ao_familiar->codper,$ao_familiar->cedfam)))
				{
					$lb_valido=$this->uf_srh_update_familiar($ao_familiar->codper,$ao_familiar->cedfam,$ao_familiar->nomfam,
														 $ao_familiar->apefam,$ao_familiar->sexfam,$ao_familiar->fecnacfam,
														 $ao_familiar->nexfam,$ao_familiar->estfam,$ao_familiar->hcfam,
														 $ao_familiar->hcmfam,$ao_familiar->hijesp,$ao_familiar->bonjug,
														 $ao_familiar->cedula,$aa_seguridad);
				}
				else
				{
					
				}
				break;
		}		
		return $lb_valido;
	}// end function uf_srh_guardar
	
//-------------------------------------------------------------------------------------------------------------------------------
	
function uf_srh_select_familiar_deduccion($as_cedfam)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_familiar_deduccion
		//		   Access: private
		//	    Arguments: as_cedfam  // cédula del familiar
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el familiar esta asociado a alguna deducción0
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT cedfam FROM sno_familiardeduccion ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND cedfam='".$as_cedfam."' ";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->Personal  MÉTODO->uf_srh_select_familiar_deduccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	}// end uf_srh_select_familiar_deduccion

//-------------------------------------------------------------------------------------------------------------------------------

	
	function uf_srh_eliminar_familiar ($as_cedfam, $as_codper, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_elimnar_familiar																												
		//      Argumento: $as_cedfam     //  cédula del familiar
		//                 $as_codper     // código del personal
		//                 $aa_seguridad //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina un familiar en la tabla sno_familiar                       
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 06/05/2008							Fecha Última Modificación: 						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$lb_existe= $this->uf_srh_select_familiar_deduccion($as_cedfam);
		if ($lb_existe)
		{
				
			$lb_valido=false;
			
		}
		else
		{
			 $this->io_sql->begin_transaction();	
	
			$ls_sql = "DELETE FROM  sno_familiar ".
					  "WHERE cedfam = '$as_cedfam' AND codper = '$as_codper'   AND codemp='".$this->ls_codemp."'";
		
		  
			$lb_borro=$this->io_sql->execute($ls_sql);
			if($lb_borro===false)
			 {
				$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_elimnar_familiar ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			 }
			else
			 {
						$lb_valido=true;
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Eliminó el familiar de la persona".$as_codper;
						$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////			
						
							$this->io_sql->commit();
					}
	}
	return array($lb_valido,$lb_existe);
   

  }


//-------------------------------------------------------------------------------------------------------------------------------
function uf_srh_buscar_familiares($as_codper)
	{		
			    				/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_familiares
		//		   Access: private
		//	    Arguments: as_codper  // código del personal
		//	  Description: Funcion que busca los familiares de un Personal dado el código del perosnal y 
		//                 crea un XML para mostrarlo en un catalogo
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   
	    $ls_cedfamdestino="txtcedfam";
		$ls_nomfamdestino="txtnomfam";
		$ls_apefamdestino="txtapefam";
		$ls_sexfamdestino="cmbsexfam";
		$ls_fecnacfamdestino="txtfecnacperfam";
		$ls_nexfamdestino="cmbnexfam";
		$ls_hcfamdestino="chkhcfam";
		$ls_hcmfamdestino="chkhcmfam";
		$ls_estfamdestino="chkestfam";
		$ls_hijespdestino="chkhijesp";
		$ls_bonjugdestino="chkbonjug";
		$ls_ceduladestino="txtcedula";
		
		$lb_valido=true;
		
		$ls_sql= "select * from sno_familiar where codper='".$as_codper."'  order by  cedfam"; 
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Personal MÉTODO->uf_srh_buscar_familiares( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}		
		 else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);	
			 		
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{	
				$ls_cedfam=$row['cedfam'];
				$ls_nomfam=htmlentities ($row['nomfam']);
				$ls_apefam=htmlentities  ($row['apefam']);
				$ls_sexfam=$row['sexfam'];
				$ls_fecnacfam=$this->io_funcion->uf_formatovalidofecha($row["fecnacfam"]);
				$ls_fecnacfam= $this->io_funcion->uf_convertirfecmostrar($ls_fecnacfam);
				$ls_nexfam=$row['nexfam'];
				$ls_hcfam=$row['hcfam'];
				$ls_hcmfam=$row['hcmfam'];
				$ls_estfam=$row['estfam'];
				$ls_hijesp=$row['hijesp'];
				
				$ls_bonjug=$row['estbonjug'];
				$ls_cedula=$row['cedula'];
							
				$row_ = $team->appendChild($dom->createElement('row'));
				$row_->setAttribute("id",$row['cedfam']);
				$cell = $row_->appendChild($dom->createElement('cell'));   
				
				
				
				
							
				$cell->appendChild($dom->createTextNode($row['cedfam']." ^javascript:aceptar
				(\"$ls_cedfam\",\"$ls_nomfam\",\"$ls_apefam\",\"$ls_sexfam\",\"$ls_fecnacfam\",\"$ls_nexfam\",\"$ls_hcfam\",\"$ls_hcmfam\",\"$ls_estfam\", 
				\"$ls_cedfamdestino\",\"$ls_nomfamdestino\",\"$ls_apefamdestino\",\"$ls_sexfamdestino\",\"$ls_fecnacfamdestino\",\"$ls_nexfamdestino\",\"$ls_hcfamdestino\",\"$ls_hcmfamdestino\",\"$ls_estfamdestino\",\"$ls_hijesp\",\"$ls_hijespdestino\",\"$ls_bonjug\",\"$ls_bonjugdestino\",\"$ls_cedula\",\"$ls_ceduladestino\");^_self"));
				
							
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_nomfam.'  '.$ls_apefam));												
				$row_->appendChild($cell);
				
				switch ($ls_nexfam) 
				{
				  case 'C' :
				    $cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode('Conyuge'));												
					$row_->appendChild($cell);
					break;
				  case 'H' :
				    $cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode('Hijo'));												
					$row_->appendChild($cell);
					break;
				  case 'P' :
				    $cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode('Progenitor'));												
					$row_->appendChild($cell);
					break;
				 case 'E' :
				    $cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode('Hermano'));												
					$row_->appendChild($cell);
					break;				
				
				}
				
				
			}
			return $dom->saveXML();
		
			
			
		
		}	   
	} 
//-------------------------------------------------------------------------------------------------------------------------------


//////////////////////////////////  FUNCIONES PARA EL MANEJO DE LOS PERMISOS DEL PERSONAL  //////////////////////////////////


function uf_srh_getProximoCodigo_permiso($as_codper)
    {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getProximoCodigo_permiso
		//         Access: public (sigesp_srh_d_personal)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código permiso
		//    Description: Funcion que genera un código nuevo de permiso
		//	   Creado Por: Ing. Rivero Jennifer
		// Fecha Creación:17/03/2008							Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  		$ls_sql = "SELECT MAX(numper) AS codigo FROM sno_permiso WHERE codper = '".$as_codper."'";
		
    	$lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
			if (!$lb_hay)
    		  $ls_numper = $la_datos["codigo"][0];///si no tiene esetudios.......
	 
			if ($lb_hay)
   			  $ls_numper = $la_datos["codigo"][0]+1; 
    	return $ls_numper;
     } 
//--------------------------------------------------------------------------------------------------------------------------------


function uf_srh_select_permiso($as_codper, $ai_numper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_permiso
		//		   Access: private
		//	    Arguments: as_codper  // Código de Personal
		//				   ai_numper  // número del permiso
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el permiso está registrado
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT numper ".
				"  FROM sno_permiso ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND numper='".$ai_numper."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->Permiso MÉTODO->uf_srh_select_permiso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_srh_select_permiso
	//--------------------------------------------------------------------------------------------------------------------------------	
	
function uf_srh_insert_permiso($as_codper,$ai_numper,$ad_feciniper,$ad_fecfinper,$ai_numdiaper,$ai_afevacper,$ai_tipper,$as_obsper,$as_remper,$as_horper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_permiso
		//		   Access: private
		//	    Arguments: as_codper  // Código de Personal
		//				   ai_numper  // Número del Permiso
		//				   ad_feciniper  // fecha inicio
		//				   ad_fecfinper  // fecha fin
		//				   ai_numdiaper  // número de días
		//				   ai_afevacper  // afecta vacaciones
		//				   ai_tipper  // tipo
		//				   as_obsper  // observación
		//				   as_remper  // Si el permiso es remunerado ó no
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla de permiso
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_permiso".
				"(codemp,codper,numper,feciniper,fecfinper,numdiaper,afevacper,tipper,obsper,remper,tothorper)VALUES".
				"('".$this->ls_codemp."','".$as_codper."',".$ai_numper.",'".$ad_feciniper."','".$ad_fecfinper."',".
				"".$ai_numdiaper.",'".$ai_afevacper."','".$ai_tipper."','".$as_obsper."','".$as_remper."','".$as_horper."')";
    	$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_msg->message("CLASE->Permiso MÉTODO->uf_srh_insert_permiso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Permiso ".$ai_numper." asociado al personal ".$as_codper;
			$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_msg->message("CLASE->Permiso MÉTODO->uf_srh_insert_permiso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_srh_insert_permiso
	//--------------------------------------------------------------------------------------------------------------------------------
	
function uf_srh_update_permiso($as_codper,$ai_numper,$ad_feciniper,$ad_fecfinper,$ai_numdiaper,$ai_afevacper,$ai_tipper,$as_obsper,$as_remper,$as_horper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_permiso
		//		   Access: private
		//	    Arguments: as_codper  // Código de Personal
		//				   ai_numper  // Número del Permiso
		//				   ad_feciniper  // fecha inicio
		//				   ad_fecfinper  // fecha fin
		//				   ai_numdiaper  // número de días
		//				   ai_afevacper  // afecta vacaciones
		//				   ai_tipper  // tipo
		//				   as_obsper  // observación
		//				   as_remper  // Si el permiso es remunerado ó no
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla de permiso
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$lb_valido=true;
		$ls_sql="UPDATE sno_permiso ".
				"	SET feciniper='".$ad_feciniper."', ".
				"		fecfinper='".$ad_fecfinper."', ".
				"		numdiaper=".$ai_numdiaper.", ".
				"		afevacper=".$ai_afevacper.", ".
				"		tipper=".$ai_tipper.", ".
				"		obsper='".$as_obsper."', ".
				"		tothorper='".$as_horper."', ".
				"		remper='".$as_remper."' ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND numper=".$ai_numper."";
		
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_msg->message("CLASE->Permiso MÉTODO->uf_srh_update_permiso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Permiso ".$ai_numper." asociado al personal ".$as_codper;
			$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_msg->message("CLASE->Permiso MÉTODO->uf_srh_update_permiso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_srh_insert_permiso
	//--------------------------------------------------------------------------------------------------------------------------------

function uf_srh_guardar_permiso($ao_permiso,$as_operacion="insertar", $aa_seguridad)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar
		//		   Access: public (sigesp_snorh_d_permiso)
		//	    Arguments: as_codper  // Código de Personal
		//				   ai_numper  // Número del Permiso
		//				   ad_feciniper  // fecha inicio
		//				   ad_fecfinper  // fecha fin
		//				   ai_numdiaper  // número de días
		//				   ai_afevacper  // afecta vacaciones
		//				   ai_tipper  // tipo
		//				   as_obsper  // observación
		//				   as_remper  // Si el permiso es remunerado ó no
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla de permiso
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ao_permiso->feciniper=$this->io_funcion->uf_convertirdatetobd($ao_permiso->feciniper);
		$ao_permiso->fecfinper=$this->io_funcion->uf_convertirdatetobd($ao_permiso->fecfinper);
		
		if ($ao_permiso->numdiaper=="")
		{
		  $ao_permiso->numdiaper=0;
		}
		
		if ($ao_permiso->horper=="")
		{
		  $ao_permiso->horper=0;
		}
		
		$lb_valido=false;		
		switch ($as_operacion)
		{
			case "insertar":
				if($this->uf_srh_select_permiso($ao_permiso->codper,$ao_permiso->numper)===false)
				{	
				if(!$lb_valido)
					{				
					 $lb_valido=$this->uf_srh_insert_permiso($ao_permiso->codper,$ao_permiso->numper,
					 									 $ao_permiso->feciniper,$ao_permiso->fecfinper,
														 $ao_permiso->numdiaper,$ao_permiso->afevacper,
														 $ao_permiso->tipper,$ao_permiso->obsper,
														 $ao_permiso->remper,$ao_permiso->horper,$aa_seguridad);
					}
					
				}
				else
				{
					$this->io_msg->message("El Permiso ya existe, no lo puede incluir.");
				}
				break;
							
			case "modificar":
				if(($this->uf_srh_select_permiso($ao_permiso->codper,$ao_permiso->numper)))
				{
					$lb_valido=$this->uf_srh_update_permiso($ao_permiso->codper,$ao_permiso->numper,
														$ao_permiso->feciniper,$ao_permiso->fecfinper,
														$ao_permiso->numdiaper,$ao_permiso->afevacper,
														$ao_permiso->tipper,$ao_permiso->obsper,
														$ao_permiso->remper,$ao_permiso->horper,$aa_seguridad);
				}
				else
				{
					$this->io_msg->message("El Permiso no existe, no lo puede actualizar.");
				}
				break;
		}		
		return $lb_valido;
	}// end function uf_srh_guardar
	//--------------------------------------------------------------------------------------------------------------------------------
	
function uf_srh_buscar_permisos($as_codper)
	{		
	  
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_permisos
		//		   Access: private
		//	    Arguments: as_codper  // código del personal
		//	  Description: Funcion que busca los permisos de un Personal dado el código del perosnal y 
		//                 crea un XML para mostrarlo en un catalogo
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  
	   	   
	    $ls_numperdestino="txtnumper";
		$ls_feciniperdestino="txtfeciniper";
		$ls_fecfinperdestino="txtfecfinper";
		$ls_numdiaperdestino="txtnumdiaper";
		$ls_afevacperdestino="chkafevacper";
		$ls_tipperdestino="cmbtipper";
		$ls_obsperdestino="txtobsper1";
		$ls_remperdestino="chkremper";
		$ls_horperdestino="txttothorper";
		
		
		$lb_valido=true;
		
		$ls_sql= "select * from sno_permiso where codper='".$as_codper."'   order by  numper";  
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Personal MÉTODO->uf_srh_buscar_permiso( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}		
		 else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		    $team = $dom->createElement('rows');
		    $dom->appendChild($team);	
			$ls_destipper="";	
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{	
							
				$ls_numper=$row['numper'];
				$ls_feciniper=$this->io_funcion->uf_formatovalidofecha($row["feciniper"]);
				$ls_feciniper=$this->io_funcion->uf_convertirfecmostrar($ls_feciniper);
				$ls_fecfinper=$this->io_funcion->uf_formatovalidofecha($row["fecfinper"]);
				$ls_fecfinper=$this->io_funcion->uf_convertirfecmostrar($ls_fecfinper);
				$ls_numdiaper=$row['numdiaper'];
				$ls_afevacper=$row['afevacper'];
				$ls_tipper=trim($row['tipper']);
				$ls_obsper=htmlentities  ($row['obsper']);
				$ls_remper=$row['remper'];
				$ls_horper=$row['tothorper'];
				
				switch ($ls_tipper)
				{
					case '1':
						$ls_destipper='ESTUDIO';
					break;
					case '2':
						$ls_destipper='MEDICO';
					break;
					case '3':
						$ls_destipper='TRAMITES';
					break;
					case '4':
						$ls_destipper='OTRO';
					break;
					case '5':
						$ls_destipper='REPOSO';
					break;
				
				}
				
										
				$row_ = $team->appendChild($dom->createElement('row'));
				$row_->setAttribute("id",$row['numper']);
				$cell = $row_->appendChild($dom->createElement('cell'));   
				
							
				$cell->appendChild($dom->createTextNode($row['numper']." ^javascript:aceptar
				(\"$ls_numper\",\"$ls_feciniper\",\"$ls_fecfinper\",\"$ls_numdiaper\",\"$ls_afevacper\",\"$ls_tipper\",\"$ls_obsper\",\"$ls_remper\",
				 \"$ls_numperdestino\",\"$ls_feciniperdestino\",\"$ls_fecfinperdestino\",\"$ls_numdiaperdestino\",\"$ls_afevacperdestino\",\"$ls_tipperdestino\",\"$ls_obsperdestino\",\"$ls_remperdestino\", \"$ls_horper\",\"$ls_horperdestino\");^_self"));
				
															
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_feciniper));												
				$row_->appendChild($cell);
								
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_fecfinper));												
				$row_->appendChild($cell);
				
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_destipper));												
				$row_->appendChild($cell);
				
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_obsper));												
				$row_->appendChild($cell);
				
				
				
				
			}
			return $dom->saveXML();
		
			
			
		
		}	   
	} 
//-------------------------------------------------------------------------------------------------------------------------------
function uf_srh_eliminar_permiso ($as_numper, $as_codper, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_permiso																												
		//      Argumento: $as_numper        //  número del permiso
		//                 $as_codper        // código del personal
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina un permiso en la tabla sno_permiso                       
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 06/05/2008							Fecha Última Modificación: 						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
	
    $ls_sql = "DELETE FROM sno_permiso ".
	          "WHERE numper = '$as_numper' AND codper = '$as_codper'   AND codemp='".$this->ls_codemp."'";

  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_eliminar_permiso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el permiso de la persona".$as_codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				
					$this->io_sql->commit();
			}
	
	return $lb_valido;
  }
  //-------------------------------------------------------------------------------------------------------------------------------



///////////////////////////// FUNCIONES PARA EL MANEJO DE LOS MOVIMIENTOS DEL PERSONAL ///////////////////////////// 

function uf_srh_getProximoCodigo_Movimiento()
    {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getProximoCodigo_Movimiento
		//         Access: public (sigesp_srh_d_personal)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de estudio
		//    Description: Funcion que genera un código nuevo numero de movimiento de personal
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación:06/05/2008							Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  		$ls_sql = "SELECT MAX(nummov) AS codigo FROM srh_movimiento_personal ";
		
    	$lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
			if (!$lb_hay)
    		  $ls_nummov = $la_datos["codigo"][0];///si no tiene esetudios.......
	 
			if ($lb_hay)
   			  $ls_nummov = $la_datos["codigo"][0]+1; 
			  
	    $ls_nummov = str_pad ($ls_nummov,15,"0","left");  
    	return $ls_nummov;
     } 
	 
function uf_srh_getProximoCodigo_hMovimiento()
    {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getProximoCodigo_hMovimiento
		//         Access: public (sigesp_srh_d_personal)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de estudio
		//    Description: Funcion que genera un código nuevo numero de historico movimiento de personal
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación:06/05/2008							Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  		$ls_sql = "SELECT MAX(codhmov) AS codigo FROM srh_hmovimiento_personal ";
		
    	$lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
			if (!$lb_hay)
    		  $ls_hnummov = $la_datos["codigo"][0];///si no tiene esetudios.......
	 
			if ($lb_hay)
   			  $ls_hnummov = $la_datos["codigo"][0]+1; 
			 
    	return $ls_hnummov;
     } 
	 
//-------------------------------------------------------------------------------------------------------------------------------
	 
function uf_srh_ue_buscar_cargo_actual($as_codper)
    {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_ue_buscar_cargo_actual
		//         Access: public (sigesp_srh_d_personal)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de estudio
		//    Description: Funcion que busca el cargo actual de una persona dado el codigo del personal
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 18/06/2008							Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  		$ls_codcar="";
		$ls_sql = " SELECT  sno_personal.codper,sno_asignacioncargo.denasicar,  sno_cargo.descar FROM sno_personal ".
		          " LEFT JOIN sno_personalnomina  ON  (sno_personal.codper=sno_personalnomina.codper)   ".
			      " LEFT JOIN sno_asignacioncargo ON  (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar AND
				      sno_personalnomina.codnom=sno_asignacioncargo.codnom) ".
				  " JOIN sno_cargo ON (sno_personalnomina.codcar=sno_cargo.codcar AND sno_personalnomina.codnom=sno_cargo.codnom AND sno_personalnomina.codper=sno_personal.codper)
				  WHERE sno_personal.codper = '".$as_codper."'";
				  
				  
		
    	$lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	
		if ($lb_hay)
   		{
		
			$ls_cargo1= trim (htmlentities($la_datos["denasicar"][0]));
			$ls_cargo2= trim (htmlentities($la_datos["descar"][0]));
			
			if ($ls_cargo1!="Sin Asignación de Cargo")
			{
			   $ls_codcar=$ls_cargo1;
			}
			if ($ls_cargo2!="Sin Cargo")
			{
			   $ls_codcar=$ls_cargo2;
			}	
		}
    	return $ls_codcar;
    } 	 
	
//-------------------------------------------------------------------------------------------------------------------------------

function uf_srh_validar_movimiento_nomina($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_validar_movimiento_nomina
		//		   Access: private
		//	    Arguments: as_codper  // código del personal
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el movimiento se puede efectuar
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 23/07/2006 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codper FROM sno_salida ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'   ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->Personal  MÉTODO->uf_srh_validar_movimiento_nomina ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_srh_validar_movimiento_nomina

//-------------------------------------------------------------------------------------------------------------------------------


	
function uf_srh_ue_buscar_sueldo_actual($as_codper)
    {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_ue_buscar_sueldo_actual
		//         Access: public (sigesp_srh_d_personal)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de estudio
		//    Description: Funcion que busca el cargo actual de una persona dado el codigo del personal
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 18/06/2008							Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  		$ls_sueper="";
		$ls_sql = " SELECT  sno_personalnomina.sueper, sno_personal.codper FROM sno_personal".
		          " JOIN sno_personalnomina  ON  (sno_personal.codper=sno_personalnomina.codper)   ".
			      " WHERE sno_personal.codper = '".$as_codper."'";

    	$lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	
		if ($lb_hay)
   		 {
		  $ls_sueper = trim ($la_datos["sueper"][0]);
		 }	
		
		
    	return $ls_sueper;
    } 	 
	
//-------------------------------------------------------------------------------------------------------------------------------


function uf_srh_buscar_uniadm($as_codper)
    {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_uniadm
		//         Access: public (sigesp_srh_d_personal)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de estudio
		//    Description: Funcion que busca la unidad de una persona dado el codigo del personal
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 18/06/2008							Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  		$ls_desuniadm="";
		$ls_sql = " SELECT  sno_personal.codper, sno_unidadadmin.desuniadm FROM sno_personal, sno_unidadadmin ".
		          " JOIN sno_personalnomina  ON  (sno_personalnomina.codper= '".$as_codper."'  ".
			      " AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
					AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
					AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
					AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
					AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm )
				    WHERE sno_personal.codper = '".$as_codper."'";
	
    	$lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	
		if ($lb_hay)
   		{
		
			$ls_desuniadm= trim (htmlentities($la_datos["desuniadm"][0]));
			
		}
    	return $ls_desuniadm;
    } 	 
	
//-------------------------------------------------------------------------------------------------------------------------------
function uf_srh_guarda_historico_movimiento ($ao_movimiento, $aa_seguridad)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_srh_guarda_historico_movimiento
	//         Access: public (sigesp_srh_d_personal)
	//      Argumento: 
	//	      Returns: Retorna el nuevo código de estudio
	//    Description: Funcion que actualiza los datos del movimiento en la tabla sno_personalnomina
	//	   Creado Por: María Beatriz Unda
	// Fecha Creación: 18/06/2008							Fecha Última Modificación:
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	$codhmov = $this->uf_srh_getProximoCodigo_hMovimiento();	
	$as_nummov=$ao_movimiento->nummov;
	$ao_movimiento->sueldoact=str_replace(".","",$ao_movimiento->sueldoact);
	$ao_movimiento->sueldoact=str_replace(",",".",$ao_movimiento->sueldoact);
	$fecreg= date("d/m/y");
    $fecreg=$this->io_funcion->uf_convertirdatetobd($fecreg);
	$minorguniadm = substr($ao_movimiento->hidcoduniadm,0,4);
	$ofiuniadm = substr($ao_movimiento->hidcoduniadm,5,2);
	$uniuniadm = substr($ao_movimiento->hidcoduniadm,8,2);
	$depuniadm = substr($ao_movimiento->hidcoduniadm,11,2);
	$prouniadm = substr($ao_movimiento->hidcoduniadm,14,2);
	
  	 $this->io_sql->begin_transaction();
	
	  $ls_sql = "INSERT INTO srh_hmovimiento_personal (codhmov,nummov, fecreg, codper, codcar, codgra, codpas, suebas, motivo, observacion, minorguniadm, ofiuniadm, uniuniadm, depuniadm , prouniadm, codnom, codemp) ".	  
	            "VALUES ($codhmov,'$ao_movimiento->nummov', '$fecreg', '$ao_movimiento->codper','$ao_movimiento->hidcodcar','$ao_movimiento->hidgrado', '$ao_movimiento->hidpaso','$ao_movimiento->sueldoact',  '$ao_movimiento->motivo', '$ao_movimiento->obs', '$minorguniadm','$ofiuniadm', '$uniuniadm', '$depuniadm', '$prouniadm', '$ao_movimiento->codnom','".$this->ls_codemp."')";
		
		
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el historico movimiento de personal ".$as_nummov;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_guarda_historico_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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

//-------------------------------------------------------------------------------------------------------------------------------
function uf_srh_select_historico_movimiento ($as_nummov, &$ls_codcar, &$ls_codgra, &$ls_codpas, &$ls_suebas, &$ls_codnom , &$ls_minorguniadm, &$ls_ofiuniadm, &$ls_uniuniadm, &$ls_depuniadm, &$ls_prouniadm,&$ls_codper, &$ls_codhmov)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_srh_select_historico_movimiento
	//         Access: public (sigesp_srh_d_personal)
	//      Argumento: 
	//	      Returns: Retorna el nuevo código de estudio
	//    Description: Funcion que buscar los datos del utlimo movimietno de un personal
	//	   Creado Por: María Beatriz Unda
	// Fecha Creación: 18/06/2008							Fecha Última Modificación:
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
	$lb_valido=true;	
	
	$ls_sql= " SELECT MAX(fecreg), codhmov, codper,  codcar,  codgra,  codpas,suebas,  codnom,minorguniadm, 	".
	         " ofiuniadm,uniuniadm,depuniadm,prouniadm  FROM  srh_hmovimiento_personal ".
			 " WHERE nummov = '$as_nummov' ".
			 " GROUP BY fecreg, codper, codcar,  codgra,  codpas,suebas, codnom,minorguniadm, 	".
			 " ofiuniadm,uniuniadm,depuniadm,prouniadm,codhmov "; 

		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Personal MÉTODO->uf_srh_select_historico_movimiento( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}		
		 else
		{
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			  $ls_codper  = trim ($row["codper"]);
			  $ls_codhmov = trim ($row["codhmov"]);
			  $ls_codcar  = trim ($row["codcar"]);	 
			  $ls_codgra  = trim ($row["codgra"]);	  
			  $ls_codpas  = trim ($row["codpas"]);	
			  $ls_suebas  = trim ($row["suebas"]);	
			  $ls_codnom  = trim ($row["codnom"]);	
			  $ls_minorguniadm = trim ($row["minorguniadm"]);	 	
			  $ls_ofiuniadm  = trim ($row["ofiuniadm"]);	
			  $ls_uniuniadm = trim ($row["uniuniadm"]);	
			  $ls_depuniadm  = trim ($row["depuniadm"]);	
			  $ls_prouniadm  = trim ($row["prouniadm"]);
			 }
		}	 
	return $lb_valido;		
}
//---------------------------------------------------------------------------------------------------------------------------

function uf_srh_update_personal_nomina ($codper,$minorguniadm,$ofiuniadm,$uniuniadm,$depuniadm,$prouniadm,$grapro,$paspro,$suelpro,$codcar, $aa_seguridad)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_srh_update_personal_nomina 
	//         Access: public (sigesp_srh_d_personal)
	//      Argumento: 
	//	      Returns: Retorna el nuevo código de estudio
	//    Description: Funcion que busca la unidad de una persona dado el codigo del personal
	//	   Creado Por: María Beatriz Unda
	// Fecha Creación: 18/06/2008							Fecha Última Modificación:
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
	$paspro = trim ($paspro);
	$grapro = trim ($grapro);

	
	if (($grapro!="") && ($paspro!=""))
	{
		
		$codasicar= $codcar;
		$codcar ='0000000000';
		
	}
	else
	{
		$codasicar= '0000000';
		$codcar= $codcar;
	} 
	
  	 $this->io_sql->begin_transaction();
	
	  $ls_sql = "UPDATE sno_personalnomina SET ".	  
	  " minorguniadm='$minorguniadm',  ".
	  " ofiuniadm='$ofiuniadm',  ".
	  " uniuniadm='$uniuniadm',  ".
	  " depuniadm='$depuniadm',  ".
	  " prouniadm='$prouniadm',  ".	  
	  " codgra = '$grapro', ".
	  " codpas = '$paspro', ".	  
	  " sueper = '$suelpro', ".
	  " sueintper = '$suelpro', ".	 
      " codasicar  = '$codasicar', ".
      " codcar  = '$codcar' ".	    
	  "WHERE  codper = '$codper' AND codemp='".$this->ls_codemp."'" ;

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Modifico los datos del personal ".$codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_update_personal_nomina  ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
//-------------------------------------------------------------------------------------------------------------------------------

	 
function uf_srh_guardar_movimiemto ($ao_movimiento,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_movimiemto																			
		//         access: public (sigesp_sno_personal)														                    //
		//      Argumento: $ao_movimiento    // arreglo con los datos del movimiento personal										        //
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              //
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta o modifica un movimiento personal en la tabla srh_movimiento_personal
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 06/05/2008							Fecha Última Modificación:				
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$as_nummov=$ao_movimiento->nummov;
	$ao_movimiento->suelpro=str_replace(".","",$ao_movimiento->suelpro);
	$ao_movimiento->suelpro=str_replace(",",".",$ao_movimiento->suelpro);
	if($ao_movimiento->suelpro=="")
	 {
	 	$ao_movimiento->suelpro=0.00;
	 }
	$ao_movimiento->compro=str_replace(".","",$ao_movimiento->compro);
	$ao_movimiento->compro=str_replace(",",".",$ao_movimiento->compro);
	if($ao_movimiento->compro=="")
	 {
	 	$ao_movimiento->compro=0.00;
	 }
	$ao_movimiento->suetotpro=str_replace(".","",$ao_movimiento->suetotpro);
	$ao_movimiento->suetotpro=str_replace(",",".",$ao_movimiento->suetotpro);
	if($ao_movimiento->suetotpro=="")
	 {
	 	$ao_movimiento->suetotpro=0.00;
	 }
	$minorguniadm = substr($ao_movimiento->uniadm,0,4);
	$ofiuniadm = substr($ao_movimiento->uniadm,5,2);
	$uniuniadm = substr($ao_movimiento->uniadm,8,2);
	$depuniadm = substr($ao_movimiento->uniadm,11,2);
	$prouniadm = substr($ao_movimiento->uniadm,14,2);
	
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	  $ao_movimiento->fecreg=$this->io_funcion->uf_convertirdatetobd($ao_movimiento->fecreg);
	  $ao_movimiento->fecinimov=$this->io_funcion->uf_convertirdatetobd($ao_movimiento->fecinimov);
	
	  $ls_sql = "UPDATE srh_movimiento_personal SET ".	  
	  "fecreg= '$ao_movimiento->fecreg', ".
	  "codcar ='$ao_movimiento->codcar', ".
	  "codnom = '$ao_movimiento->codnom', ".
	  " minorguniadm='$minorguniadm',  ".
	  " ofiuniadm='$ofiuniadm',  ".
	  " uniuniadm='$uniuniadm',  ".
	  " depuniadm='$depuniadm',  ".
	  " prouniadm='$prouniadm',  ". 
	  "fecinimov = '$ao_movimiento->fecinimov', ".
	  "codgra = '$ao_movimiento->grapro', ".
	  "codpas = '$ao_movimiento->paspro', ".	  
	  "suebaspro = '$ao_movimiento->suelpro', ".
   	  "compro  = '$ao_movimiento->compro', ".
      "suetotpro  = '$ao_movimiento->suetotpro', ".
      "grumov  = '$ao_movimiento->codgrumov', ".
      "motivo = '$ao_movimiento->motivo', ".
	  "observacion = '$ao_movimiento->obs' ".
	  "WHERE nummov= '$ao_movimiento->nummov' AND codper = '$ao_movimiento->codper' AND codemp='".$this->ls_codemp."'" ;


			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el movimiento de personal ".$as_nummov;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	  $ao_movimiento->fecreg=$this->io_funcion->uf_convertirdatetobd($ao_movimiento->fecreg);
	  $ao_movimiento->fecinimov=$this->io_funcion->uf_convertirdatetobd($ao_movimiento->fecinimov);
	
	  $ls_sql = "INSERT INTO srh_movimiento_personal (nummov,fecreg,codper,codcar,fecinimov,suebaspro,compro,suetotpro,grumov ,motivo, observacion, minorguniadm,ofiuniadm, uniuniadm, depuniadm, prouniadm, codgra,codpas,codnom,  codemp) ".	  
	            "VALUES ('$ao_movimiento->nummov', '$ao_movimiento->fecreg', '$ao_movimiento->codper','$ao_movimiento->codcar','$ao_movimiento->fecinimov','$ao_movimiento->suelpro','$ao_movimiento->compro', '$ao_movimiento->suetotpro', '$ao_movimiento->codgrumov', '$ao_movimiento->motivo', '$ao_movimiento->obs', '$minorguniadm','$ofiuniadm', '$uniuniadm', '$depuniadm', '$prouniadm', '$ao_movimiento->grapro', '$ao_movimiento->paspro','$ao_movimiento->codnom','".$this->ls_codemp."')";
		
		
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el movimiento de personal ".$as_nummov;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_guardar_movimiemto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				$this->io_sql->commit();
				$this->uf_srh_guarda_historico_movimiento($ao_movimiento, $aa_seguridad);
				$this->uf_srh_update_personal_nomina ($ao_movimiento->codper,$minorguniadm,$ofiuniadm,$uniuniadm,$depuniadm,$prouniadm,$ao_movimiento->grapro,$ao_movimiento->paspro,$ao_movimiento->suelpro,$ao_movimiento->codcar, $aa_seguridad);
		}
		
	
	return $lb_guardo;
  } 

//-------------------------------------------------------------------------------------------------------------------------------
	
	function uf_srh_buscar_movimientos ($as_codper,$as_nomper,$as_apeper,$as_nummov)
	{	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_movimientos
		//		   Access: private
		//	    Arguments: as_codper  // código del personal
		//	  Description: Funcion que busca los movimientos de un Personal dado el código del perosnal y 
		//                 crea un XML para mostrarlo en un catalogo
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		
	    $ls_nummovdestino      ="txtnummov";	
		$ls_codcardestino		="txtcodcar";
		$ls_descardestino		="txtdescar";	
		$ls_fecregdestino		="txtfecreg";
		$ls_fecinimovdestino	="txtfecinimov";
		$ls_coduniadmdestino	="txtcoduniadm";
		$ls_denuniadmdestino	="txtdenuniadm";
		$ls_graprodestino		="txtgrapro";
		$ls_pasprodestino		="txtpaspro";
		$ls_suelprodestino		="txtsuelpro";
	   	$ls_comprodestino		="txtcompro";
		$ls_suetotprodestino	="txtsuetotpro";
		$ls_codgrumovdestino	="txtcodgrumov";
		$ls_dengrumovdestino	="txtdengrumov";
	    $ls_motivodestino		="txtmotivo";
		$ls_obsdestino			="txtobs";
		$ls_codnomdestino		="txtcodnom";
		$ls_codperdestino		="txtcodper";
		$ls_nomperdestino		="txtnomper";
		
		
		
		$lb_valido=true;
		
		$ls_sql= "SELECT srh_movimiento_personal.*, sno_personal.nomper, sno_personal.apeper, 
		          srh_grupomovimientos.dengrumov, sno_unidadadmin.desuniadm, sno_cargo.descar, sno_asignacioncargo.denasicar,
				  sno_cargo.codcar, sno_asignacioncargo.codasicar
				  FROM sno_personal, srh_movimiento_personal 
		          INNER JOIN sno_unidadadmin ON (srh_movimiento_personal .minorguniadm =  sno_unidadadmin.minorguniadm " .
				" AND srh_movimiento_personal.ofiuniadm =  sno_unidadadmin.ofiuniadm ".
				" AND srh_movimiento_personal.uniuniadm =  sno_unidadadmin.uniuniadm ".
				" AND srh_movimiento_personal.depuniadm =  sno_unidadadmin.depuniadm ".
				" AND srh_movimiento_personal.prouniadm =  sno_unidadadmin.prouniadm) ".
				" INNER JOIN srh_grupomovimientos ON (srh_movimiento_personal.grumov = srh_grupomovimientos.codgrumov)
		           LEFT JOIN sno_cargo ON (srh_movimiento_personal.codcar = sno_cargo.codcar AND srh_movimiento_personal.codnom = sno_cargo.codnom )
				   LEFT JOIN sno_asignacioncargo ON (srh_movimiento_personal.codcar = sno_asignacioncargo.codasicar AND srh_movimiento_personal.codnom = sno_asignacioncargo.codnom)
				   WHERE sno_personal.codemp = srh_movimiento_personal.codemp
				   AND sno_personal.codper = srh_movimiento_personal.codper ".
				 " AND sno_personal.codper like '$as_codper'  ".
				 " AND sno_personal.nomper like '$as_nomper'  ".
				 " AND sno_personal.apeper like '$as_apeper'  ".
				 " AND srh_movimiento_personal.nummov like '$as_nummov'  ".
				 " ORDER BY nummov "; 
		

		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Personal MÉTODO->uf_srh_buscar_movimientos( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}		
		 else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);	
			 		
						
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			    
				$ls_nummov      =$row["nummov"];				  
				
				$ls_codnom		=$row["codnom"];				
				$ls_coduniadm= ($row["minorguniadm"].'-'.$row["ofiuniadm"].'-'.$row["uniuniadm"].'-'.$row["depuniadm"].'-'.$row["prouniadm"]);
				$ls_denuniadm= trim (htmlentities ($row["desuniadm"]));
				$ls_grapro		=$row["codgra"];
				$ls_paspro		=$row["codpas"];
				
				if (($ls_grapro=="") &&  ($ls_paspro==""))
				{$ls_descar		=trim (htmlentities  ($row["descar"]));
				 $ls_codcar		=$row["codcar"];	} 
				else
				{$ls_descar		=trim (htmlentities ($row["denasicar"]));
				 $ls_codcar		=$row["codasicar"];
				}
				 
				$ls_suelpro		=$row["suebaspro"];
				$ls_compro		=$row["compro"];
				$ls_suetotpro	=$row["suetotpro"];
				$ls_codgrumov	=$row["grumov"];
				$ls_dengrumov	= trim (htmlentities ($row["dengrumov"]));
				$ls_motivo		= trim (htmlentities ($row["motivo"]));
				$ls_obs			= trim (htmlentities ($row["observacion"]));
				
				$ls_codper= $row["codper"];
				$ls_nomper= (trim (htmlentities  ($row["nomper"])))." ".(trim (htmlentities ($row["apeper"])));
								
				$ls_fecreg=$this->io_funcion->uf_formatovalidofecha($row["fecreg"]);
				$ls_fecreg=$this->io_funcion->uf_convertirfecmostrar($ls_fecreg);
				
				$ls_fecinimov=$this->io_funcion->uf_formatovalidofecha($row["fecinimov"]);
				$ls_fecinimov=$this->io_funcion->uf_convertirfecmostrar($ls_fecinimov);
			
										
				$row_ = $team->appendChild($dom->createElement('row'));
				$row_->setAttribute("id",$row['nummov']);
				$cell = $row_->appendChild($dom->createElement('cell'));   
				
							
				$cell->appendChild($dom->createTextNode($row['nummov']." ^javascript:aceptar
				( \"$ls_nummov\",\"$ls_codcar\",\"$ls_coduniadm\",\"$ls_denuniadm\" ,\"$ls_grapro\",\"$ls_paspro\",\"$ls_suelpro\",\"$ls_compro\",\"$ls_suetotpro\",\"$ls_codgrumov\",\"$ls_dengrumov\",\"$ls_motivo\",\"$ls_obs\",\"$ls_fecreg\",\"$ls_fecinimov\", \"$ls_nummovdestino\",\"$ls_codcardestino\",\"$ls_coduniadmdestino\", \"$ls_denuniadmdestino\",\"$ls_graprodestino\",\"$ls_pasprodestino\",\"$ls_suelprodestino\",\"$ls_comprodestino\",\"$ls_suetotprodestino\",\"$ls_codgrumovdestino\",\"$ls_dengrumovdestino\",
				\"$ls_motivodestino\",\"$ls_obsdestino\",\"$ls_fecregdestino\",\"$ls_fecinimovdestino\",\"$ls_descar\",\"$ls_descardestino\" ,\"$ls_codnom\",\"$ls_codnomdestino\",\"$ls_codper\",\"$ls_nomper\",\"$ls_codperdestino\",\"$ls_nomperdestino\" );^_self"));
			
							
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_codper));												
				$row_->appendChild($cell);
				
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_nomper));												
				$row_->appendChild($cell);
				
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_fecinimov));												
				$row_->appendChild($cell);
				
				
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_dengrumov));												
				$row_->appendChild($cell);
				
			}
			return $dom->saveXML();
		
		}	   
	} 
	//--------------------------------------------------------------------------------------------------------------------------------

function uf_srh_eliminar_movimiento ($as_nummov, $as_codper, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_movimiento																											
		//      Argumento: $as_nummov        //  número del movimiento
		//                 $as_codper        // código del personal
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina un movimiento en la tabla srh_movimiento_personal                       
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 06/05/2008							Fecha Última Modificación: 						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
	
    $ls_sql = "DELETE FROM srh_movimiento_personal ".
	          "WHERE nummov= '$as_nummov' AND codper = '$as_codper'   AND codemp='".$this->ls_codemp."'";


	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_eliminar_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el movimiento de la persona".$as_codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				
				$lb_val= $this->uf_srh_select_historico_movimiento ($as_nummov, $ls_codcar, $ls_codgra, $ls_codpas, $ls_suebas, $ls_codnom , $ls_minorguniadm, $ls_ofiuniadm, $ls_uniuniadm, $ls_depuniadm, $ls_prouniadm, $ls_codper, $ls_codhmov);	
				if ($lb_val)
				{							
				  $lb_val= $this->uf_srh_update_personal_nomina ($ls_codper,$ls_minorguniadm,$ls_ofiuniadm,$ls_uniuniadm,$ls_depuniadm,$ls_prouniadm,$ls_codgra, $ls_codpas,$ls_suebas,$ls_codcar, $aa_seguridad);
				  if ($lb_val)
				  {
					$lb_val= $this->uf_srh_eliminar_historico_movimiento ($ls_codhmov, $aa_seguridad);
					if ($lb_val)
					{
						$this->io_sql->commit();
					}
			    	else
					{
						$lb_valido=false;
					}
  
				  }
				  else
				  {
				  	$lb_valido=false;
				  }
				}
				else 
				{
				  $lb_valido=false;
				}
			}
	
	return $lb_valido;
  }


//------------------------------------------------------------------------------------------------------------------------------- 	
function uf_srh_eliminar_historico_movimiento ($as_codhmov, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_historico_movimiento																										
		//      Argumento: $as_codhmov        //  número del movimiento
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina un movimiento en la tabla srh_movimiento_personal                       
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 06/05/2008							Fecha Última Modificación: 						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
	
    $ls_sql = "DELETE FROM srh_hmovimiento_personal ".
	          "WHERE codhmov= '$as_codhmov' AND codemp='".$this->ls_codemp."'";


	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->personal MÉTODO-> uf_srh_eliminar_historico_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el historico del movimiento de personal".$as_codhmov;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				
			
	}
	
	return $lb_valido;
  }
	//--------------------------------------------------------------------------------------------------------------------------------



//////////////////////// FUNCIONES PARA EL MANEJO DE LAS DEDUCCIONES DEL PERSONAL  ////////////////////////




function uf_srh_select_deduccion_personal_familiar($as_codper,$as_cedfam,$as_codtipded)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_deduccion_personal_familiar
		//      Argumento: $as_codper    // codigo del personal
		//                 $as_codtipded // código de la deduccion
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una deduccion de un personal en la tabla sno_personaldeduccion 
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 18/09/2008							Fecha Última Modificación: 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sno_familiardeduccion  ".
				  " WHERE codper='".trim($as_codper)."'".
				  " AND codtipded='".trim($as_codtipded)."'".
				  " AND cedfam='".trim($as_cedfam)."'".
				  " AND codemp='".$this->ls_codemp."'" ;
	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_select_deduccion_personal_familiar ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_deduccion_personal_familiar


	
function uf_srh_select_deduccion_personal($as_codper,$as_codtipded)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_deduccion_personal
		//      Argumento: $as_codper    // codigo del personal
		//                 $as_codtipded // código de la deduccion
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una deduccion de un personal en la tabla sno_personaldeduccion 
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 18/09/2008							Fecha Última Modificación: 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sno_personaldeduccion  ".
				  " WHERE codper='".trim($as_codper)."'".
				  " AND codtipded='".trim($as_codtipded)."'".
				  " AND codemp='".$this->ls_codemp."'" ;
	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_select_deduccion_personal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_deduccion_personal

//--------------------------------------------------------------------------------------------------------------------------------

function uf_srh_guardar_deducion ($ao_deduccion,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_movimiemto																			
		//         access: public (sigesp_sno_personal)														                    //
		//      Argumento: $ao_deduccion    // arreglo con los datos de la deduccion									        //
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              //
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta o modifica una deduccion en la tabla sno_personaldeduccion                  			
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 06/05/2008							Fecha Última Modificación:					
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	$lb_guardo=true;
	$lb_existe=false;
	 if ($as_operacion=="modificar")
	 {
	 	 $ls_sql = "UPDATE sno_personaldeduccion SET ".	  
				   "coddettipded= '$ao_deduccion->coddettipded' ".
				   "WHERE codtipded= '$ao_deduccion->codtipded' ".
				  " AND codper = '$ao_deduccion->codper' ".
				  " AND codemp='".$this->ls_codemp."'" ;
				 
		$lb_guardo = $this->io_sql->execute($ls_sql);
	
		 if($lb_guardo===false)
			{
				$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_guardar_deducion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
					
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizo la deduccion ".$ao_deduccion->codtipded." del personal  ".$ao_deduccion->codper;
					$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					
					$lb_valido=true;
					$this->io_sql->commit();
			}
	 }
	 if ($lb_existe===false)
	 {
	
		if ($as_operacion != "modificar")
		{ 
			$this->io_sql->begin_transaction();
	
		  $lb_existe = $this->uf_srh_select_deduccion_personal($ao_deduccion->codper,$ao_deduccion->codtipded);
		  if (!$lb_existe)
		  {
				  $ls_sql = "INSERT INTO sno_personaldeduccion (codper,codtipded,coddettipded,  codemp) ".	  
							"VALUES ('$ao_deduccion->codper',  '$ao_deduccion->codtipded','$ao_deduccion->coddettipded','".$this->ls_codemp."')";
					
				$lb_guardo = $this->io_sql->execute($ls_sql);
			
				 if($lb_guardo===false)
					{
						$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_guardar_deducion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
						$lb_valido=false;
						$this->io_sql->rollback();
					}
					else
					{
							
							/////////////////////////////////         SEGURIDAD               /////////////////////////////		
							$ls_evento="INSERT";
							$ls_descripcion ="Insertó la deduccion ".$ao_deduccion->codtipded." del personal  ".$ao_deduccion->codper;
							$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
															$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
															$aa_seguridad["ventanas"],$ls_descripcion);
							/////////////////////////////////         SEGURIDAD               /////////////////////////////		
							
							$lb_valido=true;
							$this->io_sql->commit();
					}
			}
		}
		
	}
	return array($lb_guardo,$lb_existe); 
  } 

//-------------------------------------------------------------------------------------------------------------------------------
	
	function uf_srh_buscar_deducciones ($as_codper)
	{		
	    	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_deducciones
		//		   Access: private
		//	    Arguments: as_codper  // código del personal
		//	  Description: Funcion que busca las deducciones de un Personal dado el código del perosnal y 
		//                 crea un XML para mostrarlo en un catalogo
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 09/05/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		
		
		$ls_codtipdeddestino   ="txtcodtipded";	
		$ls_dentipdeddestino   ="txtdentipded";
		$ls_coddetdestino	   ="txtcoddettipded";
		
		$lb_valido=true;
		
		$ls_sql= "SELECT sno_personaldeduccion.codtipded, sno_personaldeduccion.coddettipded ,srh_tipodeduccion.dentipded FROM sno_personaldeduccion, srh_tipodeduccion   WHERE codper='".$as_codper."' 
		          AND sno_personaldeduccion.codtipded = srh_tipodeduccion.codtipded  ORDER BY sno_personaldeduccion.codtipded "; 
		
		
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Personal MÉTODO->uf_srh_buscar_deducciones( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}		
		 else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);	
			 		
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			    
				 $ls_codtipded  =$row["codtipded"];	
				 $ls_dentipded	=htmlentities ($row["dentipded"]);
				 $ls_coddet     = $row["coddettipded"];
				
				$row_ = $team->appendChild($dom->createElement('row'));
				$row_->setAttribute("id",$row['codtipded']);
				$cell = $row_->appendChild($dom->createElement('cell'));   
				
							
				$cell->appendChild($dom->createTextNode($row['codtipded']." ^javascript:aceptar(\"$ls_codtipded\",\"$ls_dentipded\", \"$ls_codtipdeddestino\",\"$ls_dentipdeddestino\",\"$ls_$ls_coddet\",\"$ls_$ls_coddetdestino\");^_self"));
			
							
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_dentipded));												
				$row_->appendChild($cell);	
				

				
			}
			return $dom->saveXML();
		
		}	   
	} 
	 
//-------------------------------------------------------------------------------------------------------------------------------

function uf_srh_select_deduccion_familiar($as_codper, $as_codtipded)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_deduccion_familiar
		//		   Access: private
		//	    Arguments: as_codper  // código del personal
		//				   ai_codestrea  // código estudio realizado
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el estudiorealizado está registrado
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codper FROM sno_familiardeduccion ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'  AND codtipded='".$as_codtipded."' ";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->Personal  MÉTODO->uf_srh_select_deduccion_familiar ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_estudiorealizado

//-------------------------------------------------------------------------------------------------------------------------------

function uf_srh_eliminar_deduccion ($as_codtipded, $as_codper, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_deduccion 																											
		//      Argumento: $as_codtipded        //  código del tipo de deducción
		//                 $as_codper        // código del personal
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina una deducción en la tabla sno_permiso                       
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 06/05/2008							Fecha Última Modificación: 						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 		$lb_valido=true;
		$lb_existe= $this->uf_srh_select_deduccion_familiar($as_codper, $as_codtipded);
		if ($lb_existe)
		{
				
			$lb_valido=false;
			
		}
		else
		{
			$this->io_sql->begin_transaction();	
			
			$ls_sql = "DELETE FROM sno_personaldeduccion ".
					  "WHERE codtipded = '$as_codtipded' AND codper = '$as_codper'   AND codemp='".$this->ls_codemp."'";
		
		  
			$lb_borro=$this->io_sql->execute($ls_sql);
			if($lb_borro===false)
			 {
				$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_eliminar_deduccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			 }
			else
			 {
						$lb_valido=true;
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Eliminó la deducción  ".$as_codtipded."  de la persona ".$as_codper;
						$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////			
						
							$this->io_sql->commit();
					}
	}
	return array($lb_valido,$lb_existe);
  }
  
 
//--------------------------------------------------------------------------------------------------------------------



function uf_srh_select_deduccion($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_deduccion
		//		   Access: private
		//	    Arguments: as_codper  // código del personal
		//				   ai_codestrea  // código estudio realizado
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el estudiorealizado está registrado
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codper FROM sno_personaldeduccion ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->Personal  MÉTODO->uf_srh_select_deduccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
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
	
	
//--------------------------------------------------------------------------------------------------------------------

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
		               
			  if(date("d",$fecha_nac)-$a < 0)
			  {		  
			     $anos = $anos-1;	        
			  }
		  
			  }else
			  {		  
			     $anos = $anos-1;		          
			  }  
		  return $anos;	 
      }// fin de function calcular_edad($fecha_nac,$fecha_hasta)


function uf_srh_calcular_monto_deduccion ($as_codper, $as_codtipded, $as_coddettipded)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_calcular_monto_deduccion 
		//		   Access: private
		//	    Arguments: as_codper  // código del personal
		//                 as_codtipded // código del tipo de deducción
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el estudiorealizado está registrado
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 09/06/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_sql=" SELECT  srh_dt_tipodeduccion.valprim, srh_dt_tipodeduccion.aporemple,srh_dt_tipodeduccion.edadmin,  ".
				" srh_dt_tipodeduccion.edadmax, srh_dt_tipodeduccion.suelbene,sno_personalnomina.sueper,sno_personal.fecnacper ". 
				" FROM srh_dt_tipodeduccion,  sno_personalnomina, sno_nomina, sno_personal ".
				" WHERE srh_dt_tipodeduccion.codemp='".$this->ls_codemp."'".
				" AND  srh_dt_tipodeduccion.codtipded='".$as_codtipded."'   ".				
				" AND srh_dt_tipodeduccion.coddettipded='".$as_coddettipded."'  ".				
				" AND sno_personalnomina.codemp='".$this->ls_codemp."'  ".
				" AND sno_personalnomina.codper='".$as_codper."' ".
				" AND sno_personalnomina.codemp=sno_nomina.codemp ".
				" AND sno_personalnomina.codnom=sno_nomina.codnom ".
				" AND sno_nomina.espnom='0' ".
				" AND sno_personalnomina.codemp=sno_personal.codemp  ".
				" AND sno_personalnomina.codper=sno_personal.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_buscar_deduccion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ls_valor=0; 	
			while($row=$this->io_sql->fetch_row($rs_data))
			{  
				$ls_sueldo=trim ($row["suelbene"]);
				$ls_fecnacper=$row["fecnacper"];
				$ld_fecact=	date("Y-m-d");
				$ls_edadper=$this->calcular_edad(strtotime($ls_fecnacper),strtotime($ld_fecact));
				$ls_edadmin=$row["edadmin"];
				$ls_edadmax=$row["edadmax"];
				$li_aporemple=$row["aporemple"];
				$ls_sueldoper=$row["sueper"];
				$li_prima=$row["valprim"];				
				 if (($ls_sueldoper >= $ls_sueldo)&&($ls_edadper >= $ls_edadmin)&&($ls_edadper <= $ls_edadmax))
				 {
				
					$ls_valor=  $ls_valor + round ($li_prima * $li_aporemple)/100;
				 }
			
			} // Cierre del While
				
		}
		return $ls_valor;
	}

	
//--------------------------------------------------------------------------------------------------------------------



////////////////////////////  FUNCIONES PARA EL MANEJO DE LAS DEDUCCIONES DE FAMILIARES  //////////////////////////////



function uf_srh_guardar_deducion_familiar ($ao_deduccion,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_movimiemto																			
		//         access: public (sigesp_sno_personal)														                    //
		//      Argumento: $ao_deduccion    // arreglo con los datos de la deduccion									        //
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              //
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta o modifica una deduccion en la tabla sno_personaldeduccion                  			
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 06/05/2008							Fecha Última Modificación:					
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$lb_existe=false;
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	  $ls_sql = "UPDATE sno_familiardeduccion SET ". 
	  "coddettipded '$ao_deduccion->coddettipded' ".	  
	  "WHERE codtipded= '$ao_deduccion->codtipded' AND cedfam= '$ao_deduccion->cedfam' AND codper = '$ao_deduccion->codper' AND codemp='".$this->ls_codemp."'" ;
	  
		$lb_guardo = $this->io_sql->execute($ls_sql);
				
			 if($lb_guardo===false)
				{
					$this->io_msg->message("CLASE->personal MÉTODO-> uf_srh_guardar_deducion_familiar ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;
					$this->io_sql->rollback();
				}
				else
				{
						
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="UPDATE";
						$ls_descripcion ="Modificó la deduccion ".$ao_deduccion->codtipded."  del familiar".$ao_deduccion->cedfam." del personal  ".$ao_deduccion->codper;
						$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////	
						$lb_valido=true;						
						$this->io_sql->commit();
				}

			    
	}
	else
	{ 
		$this->io_sql->begin_transaction();
		 $lb_existe = $this->uf_srh_select_deduccion_personal_familiar($ao_deduccion->codper,$ao_deduccion->cedfam,$ao_deduccion->codtipded);	
		  if (!$lb_existe)
		  {	
			  $ls_sql = "INSERT INTO sno_familiardeduccion (codper,codtipded,cedfam,coddettipded,codemp) ".	  
						"VALUES ('$ao_deduccion->codper','$ao_deduccion->codtipded','$ao_deduccion->cedfam','$ao_deduccion->coddettipded','".$this->ls_codemp."')";
			$lb_guardo = $this->io_sql->execute($ls_sql);
		
			 if($lb_guardo===false)
				{
					$this->io_msg->message("CLASE->personal MÉTODO-> uf_srh_guardar_deducion_familiar ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;
					$this->io_sql->rollback();
				}
				else
				{
						
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="INSERT";
						$ls_descripcion ="Insertó la deduccion ".$ao_deduccion->codtipded."  del familiar".$ao_deduccion->cedfam." del personal  ".$ao_deduccion->codper;
						$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$lb_valido=true;
						$this->io_sql->commit();
				}
			}
		}
	return array($lb_guardo,$lb_existe); 
  } 

//-------------------------------------------------------------------------------------------------------------------------------
	
	function uf_srh_buscar_deducciones_familiar ($as_codper)
	{		
	    	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_deducciones
		//		   Access: private
		//	    Arguments: as_codper  // código del personal
		//	  Description: Funcion que busca las deducciones de un Personal dado el código del perosnal y 
		//                 crea un XML para mostrarlo en un catalogo
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 09/05/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$ls_codtipdeddestino ="txtcodtipded1";	
		$ls_dentipdeddestino ="txtdentipded1";		
		$ls_cedfamdestino   ="txtcedfam1";	
		$ls_nomfamdestino	="txtnomfam1";
		$ls_coddettipdeddestino= "txtcoddettipdedfam";
		$lb_valido=true;
		
		$ls_sql= "SELECT sno_familiardeduccion.codtipded, srh_tipodeduccion.dentipded,sno_familiar.nomfam,sno_familiar.apefam, ".
		         " sno_familiardeduccion.coddettipded, sno_familiar.cedfam, sno_familiar.nexfam".
				 " FROM sno_familiardeduccion, srh_tipodeduccion, sno_familiar".
		         " WHERE sno_familiardeduccion.codper='".$as_codper."' ".
				 " AND sno_familiardeduccion.codtipded = srh_tipodeduccion.codtipded ".
				 " AND sno_familiardeduccion.cedfam = sno_familiar.cedfam ".
				 " AND sno_familiardeduccion.codper = sno_familiar.codper ".
				 " ORDER BY sno_familiardeduccion.codtipded "; 

		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Personal MÉTODO->uf_srh_buscar_deducciones_familiar( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}		
		 else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);	
			 		
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			    
				 $ls_codtipded  =$row["codtipded"];	
				 $ls_dentipded	=htmlentities ($row["dentipded"]); 
				 $ls_nomfam  = htmlentities ($row["nomfam"])." ".htmlentities ($row["apefam"]);
				 $ls_cedfam	 = $row["cedfam"];
				 $ls_coddettipded=$row["coddettipded"];
				$row_ = $team->appendChild($dom->createElement('row'));
				$row_->setAttribute("id",$row['codtipded']);
				$cell = $row_->appendChild($dom->createElement('cell'));   
				
							
				$cell->appendChild($dom->createTextNode($row['codtipded']." ^javascript:aceptar(\"$ls_codtipded\",\"$ls_dentipded\",\"$ls_nomfam\",\"$ls_cedfam\", \"$ls_codtipdeddestino\",\"$ls_dentipdeddestino\",\"$ls_nomfamdestino\",\"$ls_cedfamdestino\", \"$ls_coddettipded\", \"$ls_coddettipdeddestino\");^_self"));
			
							
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_dentipded));												
				$row_->appendChild($cell);	
				
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_cedfam));												
				$row_->appendChild($cell);	
				
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_nomfam));												
				$row_->appendChild($cell);	
				
				switch ($row['nexfam']) 
				{
				  case 'C' :
				    $cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode('Conyuge'));												
					$row_->appendChild($cell);
					break;
				  case 'H' :
				    $cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode('Hijo'));												
					$row_->appendChild($cell);
					break;
				  case 'P' :
				    $cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode('Progenitor'));												
					$row_->appendChild($cell);
					break;
				 case 'E' :
				    $cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode('Hermano'));												
					$row_->appendChild($cell);
					break;				
				
				}
				

				
			}
			return $dom->saveXML();
		
		}	   
	} 

//-------------------------------------------------------------------------------------------------------------------------------


function uf_srh_eliminar_deduccion_familiar ($as_codtipded, $as_codper, $as_cedfam,  $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_deduccion 																											
		//      Argumento: $as_codtipded        //  código del tipo de deducción
		//                 $as_codper        // código del personal
		//                 $as_cedfam        // cédula del familiar del personal
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina una deducción en la tabla sno_permiso                       
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 06/05/2008							Fecha Última Modificación: 						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
	
    $ls_sql = "DELETE FROM sno_familiardeduccion ".
	          "WHERE codtipded = '$as_codtipded' AND codper = '$as_codper' AND cedfam = '$as_cedfam'   AND codemp='".$this->ls_codemp."'";

  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_eliminar_deduccion_familiar ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la deducción  ".$as_codtipded."  de la persona ".$as_codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				
					$this->io_sql->commit();
			}
	
	return $lb_valido;
  }


//--------------------------------------------------------------------------------------------------------------------

function uf_srh_calcular_monto_deduccion_fam ($as_codper, $as_codtipded, $as_cedfam, $as_coddettipded)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_calcular_monto_deduccion_fam
		//		   Access: private
		//	    Arguments: as_codper  // código del personal
		//                 as_codtipded // código del tipo de deducción
		//                 as-cedfam // código del tipo de deducción
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el estudiorealizado está registrado
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 09/06/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		 $ls_valor=0; 
		$ls_sql=" SELECT srh_dt_tipodeduccion.valprim,srh_dt_tipodeduccion.aporemple, sno_personalnomina.sueper,  ".
		        "  srh_dt_tipodeduccion.suelbene, srh_dt_tipodeduccion.edadmin, srh_dt_tipodeduccion.edadmax, ".
				"      (SELECT sno_familiar.fecnacfam from sno_familiar          ".
				"        WHERE sno_familiar.codemp= '".$this->ls_codemp."' ".
				"		   AND sno_familiar.codper='$as_codper'  ".
				"		   AND sno_familiar.cedfam='$as_cedfam') as fecha_nac ".	
				" FROM srh_dt_tipodeduccion,  sno_personalnomina, sno_nomina ". 			
				"  WHERE srh_dt_tipodeduccion.codemp ='".$this->ls_codemp."' ".
				"  AND  srh_dt_tipodeduccion.codtipded='".$as_codtipded."' ".				
				"  AND  srh_dt_tipodeduccion.coddettipded='".$as_coddettipded."' ".
				"  AND   sno_personalnomina.codemp= '".$this->ls_codemp."'  ".
				"  AND   sno_personalnomina.codper='$as_codper'   ". 
				"  AND   sno_personalnomina.codemp=sno_nomina.codemp   ".
				"  AND   sno_personalnomina.codnom=sno_nomina.codnom   ". 
				"  AND   sno_nomina.espnom='0' ".					
				"  ORDER BY coddettipded ";	

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_calcular_monto_deduccion_fam ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
         
			while($row=$this->io_sql->fetch_row($rs_data))
			{ 
				$ls_sueldobene=$row["suelbene"];
				$ls_edadmin=$row["edadmin"];
				$ls_edadmax=$row["edadmax"];
				$ls_valorprima=$row["valprim"];			
				$apor_empleado=$row["aporemple"];								
				$ls_sueldoper=$row["sueper"];
				$fechanac_familiar=$row["fecha_nac"];
				$ld_fecact=	date("Y-m-d");
				$edad_familiar=$this->calcular_edad(strtotime($fechanac_familiar),strtotime($ld_fecact));				
				$ls_sueldoper=$row["sueper"];
				if (($ls_sueldoper>=$ls_sueldobene)&&($edad_familiar>=$ls_edadmin)&&($edad_familiar<=$ls_edadmax))
				{
				    $ls_valor=  round ($ls_valorprima * $apor_empleado)/100;
				}
			}///fin del while
		
	 }//fin del else

	return $ls_valor;
	} // end uf_srh_calcular_monto_deduccion_fam

	
//--------------------------------------------------------------------------------------------------------------------

///////////////////////////// FUNCIONES PARA EL MANEJO DE LOS BENEFICIARIOS DEL PERSONAL ///////////////////////////// 

function uf_srh_getProximoCodigo_Beneficiario($as_codper)
    {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getProximoCodigo_Beneficiario
		//         Access: public (sigesp_srh_d_personal)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de estudio
		//    Description: Funcion que genera un código nuevo numero de movimiento de personal
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación:06/05/2008							Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  		$ls_sql = "SELECT MAX(codben) AS codigo FROM sno_beneficiario WHERE codper = '".$as_codper."'";
		
    	$lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
			if (!$lb_hay)
    		  $ls_codben = $la_datos["codigo"][0];///si no tiene esetudios.......
	 
			if ($lb_hay)
   			  $ls_codben = $la_datos["codigo"][0]+1; 
    	return $ls_codben;
     } 
	 
	 
function uf_srh_guardar_beneficiario ($ao_beneficiario,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_beneficiario																			
		//         access: public (sigesp_sno_personal)														                    //
		//      Argumento: $ao_movimiento    // arreglo con los datos del movimiento personal										        //
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              //
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta o modifica un movimiento personal en la tabla srh_movimiento_personal
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 06/05/2008							Fecha Última Modificación:				
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$as_codben=$ao_beneficiario->codben;
	if ($ao_beneficiario->porpagben=="")
	{
		$ao_beneficiario->porpagben=0;
	}
	
	if ($ao_beneficiario->monpagben=="")
	{
		$ao_beneficiario->monpagben=0;
	}
	
	$ao_beneficiario->porpagben=str_replace(".","",$ao_beneficiario->porpagben);
	$ao_beneficiario->porpagben=str_replace(",",".",$ao_beneficiario->porpagben);
	
	$ao_beneficiario->monpagben=str_replace(".","",$ao_beneficiario->monpagben);
	$ao_beneficiario->monpagben=str_replace(",",".",$ao_beneficiario->monpagben);

	
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 

	
	  $ls_sql = "UPDATE sno_beneficiario SET ".	  
	  "nomben ='$ao_beneficiario->nomben', ".
	  "apeben = '$ao_beneficiario->apeben', ".
	  "nacben = '$ao_beneficiario->nacben', ".
	  "dirben = '$ao_beneficiario->dirben', ".	 
	  "porpagben = '$ao_beneficiario->porpagben', ".	  
	  "monpagben = '$ao_beneficiario->monpagben', ".
   	  "forpagben = '$ao_beneficiario->forpagben', ".
	  "nomcheben = '$ao_beneficiario->nomcheben', ".
      "codban  = '$ao_beneficiario->codban', ".
      "ctaban  = '$ao_beneficiario->ctaban', ".
	  "cedaut  = '$ao_beneficiario->cedaut', ".
	  "nexben  = '$ao_beneficiario->nexben', ".
      "tipcueben = '$ao_beneficiario->tipcueben',  ".
	  "numexpben = '$ao_beneficiario->numexpben'  ".
	  " WHERE codemp='".$this->ls_codemp."' ".
	  "   AND codper = '$ao_beneficiario->codper' ".
	  "   AND codben= '$ao_beneficiario->codben'  ".
	  "   AND tipben= '$ao_beneficiario->tipben'";


			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el beneficiario de personal ".$as_codben;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
	  
	
	  $ls_sql = "INSERT INTO sno_beneficiario (codben,codper,cedben,nomben,apeben,dirben,telben,tipben,nomcheben,porpagben, monpagben, codban, forpagben, ctaban,tipcueben, nacben, nexben, cedaut, numexpben, codemp) ".	  
	            "VALUES ('$ao_beneficiario->codben', '$ao_beneficiario->codper', '$ao_beneficiario->cedben','$ao_beneficiario->nomben','$ao_beneficiario->apeben','$ao_beneficiario->dirben','$ao_beneficiario->telben', '$ao_beneficiario->tipben', '$ao_beneficiario->nomcheben', '$ao_beneficiario->porpagben', '$ao_beneficiario->monpagben', '$ao_beneficiario->codban', '$ao_beneficiario->forpagben', '$ao_beneficiario->ctaban','$ao_beneficiario->tipcueben', '$ao_beneficiario->nacben','$ao_beneficiario->nexben','$ao_beneficiario->cedaut','$ao_beneficiario->numexpben','".$this->ls_codemp."')";
		
	
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el beneficiario de personal ".$as_codben;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_guardar_beneficiario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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

//-------------------------------------------------------------------------------------------------------------------------------
	
	function uf_srh_buscar_beneficiarios ($as_codper)
	{	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_beneficiarios
		//		   Access: private
		//	    Arguments: as_codper  // código del personal
		//	  Description: Funcion que busca los beneficiarios de un Personal dado el código del perosnal y 
		//                 crea un XML para mostrarlo en un catalogo
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		
	      $ls_codbendestino ="txtcodben";
		  $ls_cedbendestino ="txtcedben";
		  $ls_nombendestino ="txtnomben";
		  $ls_apebendestino ="txtapeben";
		  $ls_nacbendestino ="cmbnacben";
		  $ls_dirbendestino ="txtdirben";
		  $ls_telbendestino ="txttelben";
		  $ls_tipbendestino ="cmbtipben";
		  $ls_porpagbendestino ="txtporpagben";
		  $ls_monpagbendestino ="txtmonpagben";
		  $ls_forpagbendestino ="cmbforpagben";
		  $ls_nomchebendestino ="txtnomcheben";
		  $ls_codbandestino ="txtcodban";
		  $ls_nombandestino ="txtnomban";
		  $ls_ctabandestino ="txtctaban";
		  $ls_tipcuebendestino ="cmbtipcueben";
		  $ls_cedautdestino ="txtcedaut";
		  $ls_nexbendestino ="cmbnexben";
		  $ls_numexpbendestino ="txtnumexpben";
		
		
		$lb_valido=true;
		
		$ls_sql= "SELECT sno_beneficiario.*, scb_banco.nomban FROM sno_beneficiario
				  LEFT JOIN scb_banco ON (scb_banco.codban = sno_beneficiario.codban) 
				  WHERE codper='".$as_codper."' 				   
				   ORDER BY codben "; 
		
	
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Personal MÉTODO->uf_srh_buscar_beneficiarios( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}		
		 else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);	
			 		
						
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			    
				$ls_codben      = trim ($row["codben"]);				  				
				$ls_cedben		=$row["cedben"];				
				$ls_nomben	    =htmlentities  ($row["nomben"]);				
				$ls_apeben	    =htmlentities  ($row["apeben"]);
				$ls_dirben	    =htmlentities  ($row["dirben"]);
				$ls_nacben      =htmlentities  ($row["nacben"]);
				$ls_telben=$row["telben"];
			    $ls_tipben= $row["tipben"];
			    $ls_porpagben= $row["porpagben"];
			    $ls_monpagben= $row["monpagben"];
			    $ls_forpagben= $row["forpagben"];
			    $ls_nomcheben= htmlentities($row["nomcheben"]);
			    $ls_codban=$row["codban"];
			    $ls_nomban=htmlentities($row["nomban"]);
			    $ls_ctaban =$row["ctaban"];
			    $ls_tipcueben =$row["tipcueben"];
				$ls_cedaut =$row["cedaut"];
				$ls_nexben =$row["nexben"];
				$ls_numexpben=htmlentities($row["numexpben"]);						
				$row_ = $team->appendChild($dom->createElement('row'));
				$row_->setAttribute("id",$row['codben']);
				$cell = $row_->appendChild($dom->createElement('cell'));   
				
							
				$cell->appendChild($dom->createTextNode($row['codben']." ^javascript:aceptar
				(\"$ls_codben\",\"$ls_cedben\",\"$ls_nomben\",\"$ls_apeben\",\"$ls_nacben\",\"$ls_dirben\",\"$ls_telben\", \"$ls_tipben\",\"$ls_porpagben\",\"$ls_monpagben\",\"$ls_forpagben\",\"$ls_nomcheben\",\"$ls_codban\",\"$ls_nomban\",\" $ls_ctaban\",\"$ls_tipcueben\",\"$ls_codbendestino\" ,\"$ls_cedbendestino\" ,\"$ls_nombendestino\",\"$ls_apebendestino\",\"$ls_nacbendestino\",\"$ls_dirbendestino\",\"$ls_telbendestino\" , \"$ls_tipbendestino\" , \"$ls_porpagbendestino\" , \"$ls_monpagbendestino\" ,\"$ls_forpagbendestino\", \"$ls_nomchebendestino\", \"$ls_codbandestino\", \"$ls_nombandestino\", \"$ls_ctabandestino\", \"$ls_tipcuebendestino\", \"$ls_nexben\", \"$ls_nexbendestino\", \"$ls_cedaut\", \"$ls_cedautdestino\", \"$ls_numexpben\", \"$ls_numexpbendestino\");^_self"));
			
							
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_cedben));												
				$row_->appendChild($cell);
				
				
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_nomben."  ".$ls_apeben));												
				$row_->appendChild($cell);
				
				if ($ls_tipben=='0')
				{ 
				   $cell = $row_->appendChild($dom->createElement('cell'));
				   $cell->appendChild($dom->createTextNode('Pension sobrevivientes'));												
				   $row_->appendChild($cell);
				  }
				 else if ($ls_tipben=='1')
				 {
				    $cell = $row_->appendChild($dom->createElement('cell'));
				    $cell->appendChild($dom->createTextNode('Pension Judicial'));												
				    $row_->appendChild($cell);
				 }
				else 
				{
				    $cell = $row_->appendChild($dom->createElement('cell'));
				    $cell->appendChild($dom->createTextNode('Otro'));												
				    $row_->appendChild($cell);
				 }
			}
			return $dom->saveXML();
		
		}	   
	} 
	
//------------------------------------------------------------------------------------------------------------------------------- 	
function uf_srh_eliminar_beneficiario ($as_codben, $as_codper,$as_tipben, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_beneficiario																											
		//      Argumento: $as_codben        //  código del beneficiario
		//                 $as_codper        // código del personal
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina un movimiento en la tabla srh_movimiento_personal                       
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 13/06/2008							Fecha Última Modificación: 						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
	
    $ls_sql = "DELETE FROM sno_beneficiario ".
	          "WHERE codemp='".$this->ls_codemp."' ".
			  "  AND codper = '$as_codper' ".
			  "  AND codben= '$as_codben' ".
			  "  AND tipben= '$as_tipben'  ";

  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_eliminar_beneficiario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el beneficiario ".$as_codben." de la persona".$as_codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				
					$this->io_sql->commit();
			}
	
	return $lb_valido;
  }
	//--------------------------------------------------------------------------------------------------------------------------------

///////////////////////////// FUNCIONES PARA EL MANEJO DE LAS PREMIACIONES  ///////////////////////////// 

function uf_srh_getProximoCodigo_Premio($as_codper)
    {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getProximoCodigo_Premio
		//         Access: public (sigesp_srh_d_personal)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código de estudio
		//    Description: Funcion que genera un código nuevo numero de una premiacion
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación:09/07/2008							Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  		$ls_sql = "SELECT MAX(numprem) AS codigo FROM srh_premiacion WHERE codper = '".$as_codper."'";
		
    	$lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
			if (!$lb_hay)
    		  $ls_prem = $la_datos["codigo"][0];///si no tiene esetudios.......
	 
			if ($lb_hay)
   			  $ls_prem = $la_datos["codigo"][0]+1; 
    	return $ls_prem;
     } 
	 
//-------------------------------------------------------------------------------------------------------------------------------


	 
function uf_srh_guardar_premio ($ao_premio,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_premio																			
		//         access: public (sigesp_sno_personal)														                    //
		//      Argumento: $ao_premio    // arreglo con los datos de la premiacion 		   									   //
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              //
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               //
		//	      Returns: Retorna un Booleano																					//
		//    Description: Funcion que inserta o modifica una premiacion en la tabla srh_premio
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 06/05/2008							Fecha Última Modificación:				
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	$as_numprem=$ao_premio->numprem;
	$ao_premio->fecprem=$this->io_funcion->uf_convertirdatetobd($ao_premio->fecprem);
	
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	  $ls_sql = "UPDATE srh_premiacion SET ".	  
	  "fecprem    = '$ao_premio->fecprem', ".
	  "denprem    = '$ao_premio->denprem', ".
	  "motivoprem = '$ao_premio->motivoprem' ".
	  "WHERE numprem= '$ao_premio->numprem' AND codper = '$ao_premio->codper' AND codemp='".$this->ls_codemp."'" ;


			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó la premiacion de personal ".$as_numprem;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{
	  $this->io_sql->begin_transaction();	
	
	  $ls_sql = "INSERT INTO srh_premiacion (numprem,codper, fecprem, denprem, motivoprem, codemp) ".	  
	            "VALUES ('$ao_premio->numprem', '$ao_premio->codper', '$ao_premio->fecprem', '$ao_premio->denprem','$ao_premio->motivoprem','".$this->ls_codemp."')";
				
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el movimiento de personal ".$as_nummov;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_guardar_premio ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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

//-------------------------------------------------------------------------------------------------------------------------------
	
	function uf_srh_buscar_premio ($as_codper)
	{	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_premio
		//		   Access: private
		//	    Arguments: as_codper  // código del personal
		//	  Description: Funcion que busca las premiaciones de un Personal dado el código del perosnal y 
		//                 crea un XML para mostrarlo en un catalogo
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 09/07/08								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		
	    $ls_numpremdestino      ="txtnumprem";	
		$ls_fecpremdestino		="txtfecprem";
		$ls_denpremdestino		="txtdenprem";	
		$ls_motivopremdestino	="txtmotivoprem";
		
		
		
		$lb_valido=true;
		
		$ls_sql= "SELECT * FROM srh_premiacion
		          WHERE codper='".$as_codper."' 
				  ORDER BY numprem "; 
		

		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Personal MÉTODO->uf_srh_buscar_premio( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}		
		 else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);	
			 		
						
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			    
				$ls_numprem     = trim ($row["numprem"]);				  
				$ls_denprem     = trim (htmlentities ($row["denprem"]));
				$ls_motivoprem  = trim (htmlentities ($row["motivoprem"]));
						
				$ls_fecprem=$this->io_funcion->uf_formatovalidofecha($row["fecprem"]);
				$ls_fecprem=$this->io_funcion->uf_convertirfecmostrar($ls_fecprem);
										
				$row_ = $team->appendChild($dom->createElement('row'));
				$row_->setAttribute("id",$row['numprem']);
				$cell = $row_->appendChild($dom->createElement('cell'));   
				
							
				$cell->appendChild($dom->createTextNode($row['numprem']." ^javascript:aceptar
				( \"$ls_numprem\", \"$ls_denprem\", \"$ls_fecprem\", \"$ls_motivoprem\", \"$ls_numpremdestino\", \"$ls_denpremdestino\", \"$ls_fecpremdestino\", \"$ls_motivopremdestino\" );^_self"));
			
							
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_fecprem));												
				$row_->appendChild($cell);
				
				
				$cell = $row_->appendChild($dom->createElement('cell'));
				$cell->appendChild($dom->createTextNode($ls_denprem));												
				$row_->appendChild($cell);
				
			}
			return $dom->saveXML();
		
		}	   
	} 
	
//------------------------------------------------------------------------------------------------------------------------------- 	
function uf_srh_eliminar_premio ($as_numprem, $as_codper, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_premio																										
		//      Argumento: $as_numprem       //  número de la premiación
		//                 $as_codper        // código del personal
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina una premiación en la tabla srh_movimiento_personal                       
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 06/05/2008							Fecha Última Modificación: 						
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
	
    $ls_sql = "DELETE FROM srh_premiacion ".
	          "WHERE numprem= '$as_numprem' AND codper = '$as_codper'   AND codemp='".$this->ls_codemp."'";

  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->personal MÉTODO->uf_srh_eliminar_premio ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la premiacion de la persona".$as_codper;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				
					$this->io_sql->commit();
			}
	
	return $lb_valido;
  }
	//--------------------------------------------------------------------------------------------------------------------------------



}
?>