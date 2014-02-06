<?php
class sigesp_sep_c_aprobacioncreditos
 {
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_id_process;
	var $ls_codemp;
	var $io_dscuentas;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sep_c_aprobacioncreditos($as_path)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sep_c_solicitud
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		// Fecha Creación: 23/07/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once($as_path."shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once($as_path."shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once($as_path."shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once($as_path."shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
	    require_once($as_path."shared/class_folder/class_fecha.php");		
		$this->io_fecha= new class_fecha();
		require_once($as_path."shared/class_folder/sigesp_c_generar_consecutivo.php");
		$this->io_keygen= new sigesp_c_generar_consecutivo();
		require_once($as_path."shared/class_folder/class_funciones_xml.php");
		$this->io_xml=new class_funciones_xml();		
		require_once($as_path."shared/class_folder/class_datastore.php");
		require_once("sigesp_sep_c_solicitud.php");
		$this->io_solicitud= new sigesp_sep_c_solicitud($as_path);
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_sep_c_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sep_p_solicitud.php)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		// Fecha Creación: 23/07/2008							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fecha);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_beneficiarios($as_rutaarchivo,$as_archivo,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_beneficiarios
		//		   Access: private
		//	    Arguments: as_archivo  // Archivo xml
		//	      Returns: lb_valido True si se ejecuto el insert correctamente
		//	  Description: Método que lee el archivo xml e inserta los beneficiarios
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		// Fecha Creación: 23/07/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$la_data=$this->io_xml->uf_cargar_rpc_beneficiario($as_rutaarchivo."/".$as_archivo);
		$li_total=count($la_data);
		for($i=1;$i<=$li_total;$i++)
		{
			$ls_cedbene=$la_data[$i]["ced_bene"];
			$ls_nombene=$la_data[$i]["nombene"];
			$ls_apebene=$la_data[$i]["apebene"];
			$ls_dirbene=$la_data[$i]["dirbene"];
			$ls_telbene=$la_data[$i]["telbene"];
			$ls_celbene=$la_data[$i]["celbene"];
			$ls_email=$la_data[$i]["email"];
			$ls_sccuenta=$la_data[$i]["sc_cuenta"];
			$ls_codpai=$la_data[$i]["codpai"];
			$ls_codest=$la_data[$i]["codest"];
			$ls_codmun=$la_data[$i]["codmun"];			  
			$ls_codpar=$la_data[$i]["codpar"];			  
			$ls_nacben=$la_data[$i]["nacben"];
			$ls_codtipcta=$la_data[$i]["codtipcta"];			
			$ls_rifben=$la_data[$i]["rifben"];
			$ls_codbansig=$la_data[$i]["codbansig"];
			$ls_codban=$la_data[$i]["codban"];
			$ls_ctaban=$la_data[$i]["ctaban"];
			$ls_foto=$la_data[$i]["foto"];
			$ls_fecregben=$la_data[$i]["fecregben"];
			$ls_numpasben=$la_data[$i]["numpasben"];
			$ls_tipconben=$la_data[$i]["tipconben"];
			$lb_valido=$this->io_xml->uf_validar_scgcuenta($this->ls_codemp,$ls_sccuenta);
			if($lb_valido)
			{
				$lb_valido=$this->uf_insert_beneficiario($ls_cedbene,$ls_nombene,$ls_apebene,$ls_dirbene,$ls_telbene,$ls_celbene,
														 $ls_email,$ls_sccuenta,$ls_codpai,$ls_codest,$ls_codmun,$ls_codpar,$ls_nacben,
														 $ls_codtipcta,$ls_rifben,$ls_codbansig,$ls_codban,$ls_ctaban,$ls_foto,
														 $ls_fecregben,$ls_numpasben,$ls_tipconben,$as_rutaarchivo,$as_archivo,$aa_seguridad);
			}
			else
			{
				$this->io_xml->uf_update_xml_procesado($as_archivo,$as_rutaarchivo,"RPC_BENEFICIARIO",$lb_valido,"La cuenta Contable no existe");
			}
	   	} // end if 
	    return $lb_valido;
    }// end function uf_procesar_beneficiarios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_beneficiario($as_cedbene,$as_nombene,$as_apebene,$as_dirbene,$as_telbene,$as_celbene,$as_email,$as_sccuenta,
									$as_codpai,$as_codest,$as_codmun,$as_codpar,$as_nacben,$as_codtipcta,$as_rifben,$as_codbansig,
									$as_codban,$as_ctaban,$as_foto,$as_fecregben,$as_numpasben,$as_tipconben,$as_rutaarchivo,
									$as_archivo,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_beneficiario
		//		   Access: private
		//	    Arguments: as_cedper  // Cédula del personal
		//			  	   as_nomper  // Nombre del Personal
		//			  	   as_apeper  // Apellido del Personal
		//			  	   as_dirper  // Dirección del Personal
		//			  	   as_telhabper  // Teléfono de Habitación del Personal
		//			  	   as_telmovper  // Teléfono Móvil del Personal
		//			  	   as_coreleper  // Correo del Personal
		//			  	   as_cuentacontable  // Cuenta Contable
		//			  	   as_codpai  // Código del País
		//			  	   as_codest  // Código del Estado
		//			  	   as_codmun  // Código del Municipio
		//			  	   as_codpar  // Código del Parroquia
		//			  	   as_nacper  // Naconalidad
		//			  	   aa_seguridad  // Arreglo de las Variables de Seguridad
		//	      Returns: lb_valido True si el select no tuvo errores ó False si hubo error
		//	  Description: Funcion que inserta el personal como beneficiario
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		// Fecha Creación: 23/07/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT ced_bene ".
				"  FROM rpc_beneficiario ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND ced_bene='".$as_cedbene."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_insert_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_xml->uf_update_xml_procesado($as_archivo,$as_rutaarchivo,"RPC_BENEFICIARIO",$lb_valido,$this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->io_xml->uf_update_xml_procesado($as_archivo,$as_rutaarchivo,"RPC_BENEFICIARIO",$lb_valido,"El beneficario ya existe");
			}
			else
			{
				$ls_sql="INSERT INTO rpc_beneficiario(codemp, ced_bene, nombene, apebene, dirbene, telbene, celbene, email, sc_cuenta, ".
						"codpai,codest,codmun,codpar,nacben,tipconben,codbansig,codban,ctaban,fecregben,codtipcta,rifben,numpasben) ".
						"VALUES ('".$this->ls_codemp."', ".
						"'".$as_cedbene."', '".$as_nombene."', '".$as_apebene."', '".$as_dirbene."', '".$as_telbene."', '".$as_celbene."', ".
						"'".$as_email."', '".$as_sccuenta."', '".$as_codpai."', '".$as_codest."', '".$as_codmun."', '".$as_codpar."', ".
						"'".$as_nacben."','".$as_tipconben."','".$as_codbansig."','".$as_codban."','".$as_ctaban."','".$as_fecregben."', ".
						"'".$as_codtipcta."','".$as_rifben."','".$as_numpasben."')";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_insert_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_xml->uf_update_xml_procesado($as_archivo,$as_rutaarchivo,"RPC_BENEFICIARIO",$lb_valido,$this->io_sql->message);
				}
				else
				{
					$this->io_xml->uf_update_xml_procesado($as_archivo,$as_rutaarchivo,"RPC_BENEFICIARIO",$lb_valido,"Registro Incluido.");
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó el Beneficiario ".$as_cedbene." que viene del sistema de crédito";
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////	
					 
				 }	  	
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;	
	}// end function uf_insert_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_credito($as_rutaarchivo,$as_archivo,$as_ced_bene,$as_consol,$ai_monto,$as_codtipsol,$as_coduniadm,$as_estcla,
								 $as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_tipo_destino,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_credito
		//		   Access: private
		//	    Arguments: as_rutaarchivo  // Ruta del Archivo XML
		//	    		   as_archivo  // Archivo xml
		//	    		   as_ced_bene  // Cédula del Beneficiario
		//	    		   as_consol  // Concepto del crédito
		//	    		   ai_monto  // Monto de la solicitud
		//	    		   as_codtipsol  // Tipo de Solicitud
		//	    		   as_coduniadm  // Código de Unidad Administradora
		//	    		   as_estcla  // Estatus de Clasificación
		//	    		   as_codestpro1  // Código de estructura presupuestaria 1
		//	    		   as_codestpro2  // Código de estructura presupuestaria 2
		//	    		   as_codestpro3  // Código de estructura presupuestaria 3
		//	    		   as_codestpro4  // Código de estructura presupuestaria 4
		//	    		   as_codestpro5  // Código de estructura presupuestaria 5
		//	    		   as_tipo_destino  // Tipo Destino
		//	      Returns: lb_valido True si se ejecuto el insert correctamente
		//	  Description: Método que lee el archivo xml e inserta los créditos
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		// Fecha Creación: 23/07/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_cabecera=new class_datastore();
		$this->io_spgcuentas=new class_datastore();
		$this->io_conceptos=new class_datastore();
		$li_montototal=0;
		// Validar Tipo de Solicitud
		$lb_valido=$this->io_xml->uf_validar_tiposolicitud($as_codtipsol);
		if($lb_valido===false)
		{
			$this->io_xml->uf_update_xml_procesado($as_archivo,$as_rutaarchivo,"SEP_SOLICITUD",$lb_valido,"El tipo de Solicitud no existe o no es de Compromiso y conceptos");
			$this->io_xml->uf_mover_xml($as_archivo,$as_rutaarchivo,"../scc/aprobacion/procesados");
		    return $lb_valido;
		}
		// Validar Unidad Administrativa
		$lb_valido=$this->io_xml->uf_validar_unidadadministrativa($this->ls_codemp,$as_coduniadm);
		
		if($lb_valido===false)
		{
			$this->io_xml->uf_update_xml_procesado($as_archivo,$as_rutaarchivo,"SEP_SOLICITUD",$lb_valido,"La Unidad Administrativa no existe.");
			$this->io_xml->uf_mover_xml($as_archivo,$as_rutaarchivo,"../scc/aprobacion/procesados");
		    return $lb_valido;
		}
		// Validar Estructura de la Unidad Administrativa
		$lb_valido=$this->io_xml->uf_validar_estructuraunidad($this->ls_codemp,$as_coduniadm,$as_estcla,$as_codestpro1,$as_codestpro2,
															  $as_codestpro3,$as_codestpro4,$as_codestpro5);
		
		if($lb_valido===false)
		{
			$this->io_xml->uf_update_xml_procesado($as_archivo,$as_rutaarchivo,"SEP_SOLICITUD",$lb_valido,"La Estructura Presupuestaria no existe en la Unidad Administrativa.");
			$this->io_xml->uf_mover_xml($as_archivo,$as_rutaarchivo,"../scc/aprobacion/procesados");
		    return $lb_valido;
		}
		// Lleno el Datastored de la Cabecera
		$this->io_cabecera->insertRow("codtipsol",$as_codtipsol);	
		$this->io_cabecera->insertRow("coduniadm",$as_coduniadm);	
		$this->io_cabecera->insertRow("codestpro1",$as_codestpro1);	
		$this->io_cabecera->insertRow("codestpro2",$as_codestpro2);	
		$this->io_cabecera->insertRow("codestpro3",$as_codestpro3);	
		$this->io_cabecera->insertRow("codestpro4",$as_codestpro4);	
		$this->io_cabecera->insertRow("codestpro5",$as_codestpro5);	
		$this->io_cabecera->insertRow("estcla",$as_estcla);	
		$this->io_cabecera->insertRow("codfuefin",'--');	
		$this->io_cabecera->insertRow("fecregsol",date('Y-m-d'));	
		$this->io_cabecera->insertRow("estsol",'R');	
		$this->io_cabecera->insertRow("consol",$as_consol);	
		$this->io_cabecera->insertRow("monto",$ai_monto);	
		$this->io_cabecera->insertRow("monbasinm",$ai_monto);	
		$this->io_cabecera->insertRow("montotcar",0);	
		$this->io_cabecera->insertRow("tipo_destino",$as_tipo_destino);	
		$this->io_cabecera->insertRow("cod_pro",'----------');	
		$this->io_cabecera->insertRow("ced_bene",$as_ced_bene);	
		// Cargar Detalles del Crédito
		$la_data=$this->io_xml->uf_cargar_set_dt_conceptos($as_rutaarchivo."/".$as_archivo);
		$li_total=count($la_data);
		for($i=1;($i<=$li_total)&&($lb_valido);$i++)
		{

			$ls_codconsep=$la_data[$i]["codconsep"];
			$li_moncon=$la_data[$i]["moncon"];
			$li_montototal=$li_montototal+$li_moncon;
			$ls_spg_cuenta=$la_data[$i]["spg_cuenta"];
			// Validar Conceptos
			$lb_valido=$this->io_xml->uf_validar_conceptosep($ls_codconsep,$ls_spg_cuenta);
			if($lb_valido===false)
			{
				$this->io_xml->uf_update_xml_procesado($as_archivo,$as_rutaarchivo,"SEP_SOLICITUD",$lb_valido,"Error en los detalles del Credito.");
				$this->io_xml->uf_update_xml_procesado($as_archivo,$as_rutaarchivo,"SEP_DT_CONCEPTOS",$lb_valido,"El concepto no existe o las cuentas presupuestarias no coinciden.");
				$this->io_xml->uf_mover_xml($as_archivo,$as_rutaarchivo,"../scc/aprobacion/procesados");
				return $lb_valido;
			}
			// Validar Cuentas Presupuestarias
			$lb_valido=$this->io_xml->uf_validar_cuentaspresupuestarias($this->ls_codemp,$ls_spg_cuenta,$as_estcla,$as_codestpro1,$as_codestpro2,
															  			$as_codestpro3,$as_codestpro4,$as_codestpro5);
			
			if($lb_valido===false)
			{
				$this->io_xml->uf_update_xml_procesado($as_archivo,$as_rutaarchivo,"SEP_SOLICITUD",$lb_valido,"Error en los detalles del Credito.");
				$this->io_xml->uf_update_xml_procesado($as_archivo,$as_rutaarchivo,"SEP_DT_CONCEPTOS",$lb_valido,"La cuenta presupuestaria no existe.");
				$this->io_xml->uf_mover_xml($as_archivo,$as_rutaarchivo,"../scc/aprobacion/procesados");
				return $lb_valido;
			}
			// Lleno el Datastored de los conceptos
			$this->io_conceptos->insertRow("codconsep",$ls_codconsep);	
			$this->io_conceptos->insertRow("cancon",1);	
			$this->io_conceptos->insertRow("monpre",$li_moncon);	
			$this->io_conceptos->insertRow("moncon",$li_moncon);	
			$this->io_conceptos->insertRow("orden",$i);	
			$this->io_conceptos->insertRow("codestpro1",$as_codestpro1);	
			$this->io_conceptos->insertRow("codestpro2",$as_codestpro2);	
			$this->io_conceptos->insertRow("codestpro3",$as_codestpro3);	
			$this->io_conceptos->insertRow("codestpro4",$as_codestpro4);	
			$this->io_conceptos->insertRow("codestpro5",$as_codestpro5);	
			$this->io_conceptos->insertRow("estcla",$as_estcla);	
			$this->io_conceptos->insertRow("spg_cuenta",$ls_spg_cuenta);	
			// Lleno el Datastored de las Cuentas Presupuestarias
			$this->io_spgcuentas->insertRow("codestpro1",$as_codestpro1);	
			$this->io_spgcuentas->insertRow("codestpro2",$as_codestpro2);	
			$this->io_spgcuentas->insertRow("codestpro3",$as_codestpro3);	
			$this->io_spgcuentas->insertRow("codestpro4",$as_codestpro4);	
			$this->io_spgcuentas->insertRow("codestpro5",$as_codestpro5);	
			$this->io_spgcuentas->insertRow("estcla",$as_estcla);	
			$this->io_spgcuentas->insertRow("spg_cuenta",$ls_spg_cuenta);	
			$this->io_spgcuentas->insertRow("monto",$li_moncon);	
	   	}
		// Valido que la suma de los detalles sea igual que el total
		$li_montototal = round($li_montototal,2);
		$ai_monto      = round($ai_monto,2);
		if($li_montototal!=$ai_monto)
		{
			$lb_valido=false;
			$this->io_xml->uf_update_xml_procesado($as_archivo,$as_rutaarchivo,"SEP_SOLICITUD",$lb_valido,"La suma de los detalles no coinciden con el total del Credito.");
			$this->io_xml->uf_mover_xml($as_archivo,$as_rutaarchivo,"../scc/aprobacion/procesados");
			return $lb_valido;
		}
		
		$lb_valido=$this->uf_guardar_sep($as_rutaarchivo,$as_archivo,$aa_seguridad);
		$this->io_xml->uf_mover_xml($as_archivo,$as_rutaarchivo,"../scc/aprobacion/procesados");
	    return $lb_valido;
    }// end function uf_procesar_credito
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar_sep($as_rutaarchivo,$as_archivo,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_sep
		//		   Access: private
		//	    Arguments: as_rutaarchivo  // Ruta del Archivo XML
		//	    		   as_archivo  // Archivo xml
		//	    		   as_ced_bene  // Cédula del Beneficiario
		//	    		   as_consol  // Concepto del crédito
		//	    		   ai_monto  // Monto de la solicitud
		//	    		   as_codtipsol  // Tipo de Solicitud
		//	    		   as_coduniadm  // Código de Unidad Administradora
		//	    		   as_estcla  // Estatus de Clasificación
		//	    		   as_codestpro1  // Código de estructura presupuestaria 1
		//	    		   as_codestpro2  // Código de estructura presupuestaria 2
		//	    		   as_codestpro3  // Código de estructura presupuestaria 3
		//	    		   as_codestpro4  // Código de estructura presupuestaria 4
		//	    		   as_codestpro5  // Código de estructura presupuestaria 5
		//	    		   as_tipo_destino  // Tipo Destino
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que valida y guarda la sep
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		// Fecha Creación: 23/07/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ld_fecregsol=date("Y-m-d");
		$lb_valido=$this->io_solicitud->uf_validar_fecha_sep($ld_fecregsol);
		
		if(!$lb_valido)
		{
			$this->io_mensajes->message("La Fecha de esta Solicitud es menor a la fecha de la Solicitud anterior.");
			$this->io_xml->uf_update_xml_procesado($as_archivo,$as_rutaarchivo,"SEP_SOLICITUD",$lb_valido,"La Fecha de esta Solicitud es menor a la fecha de la Solicitud anterior.");
			return $lb_valido;
		}
		$lb_valido=$this->io_fecha->uf_valida_fecha_periodo($ld_fecregsol,$this->ls_codemp);
		if (!$lb_valido)
		{
			$this->io_mensajes->message($this->io_fecha->is_msg_error);           
			$this->io_xml->uf_update_xml_procesado($as_archivo,$as_rutaarchivo,"SEP_SOLICITUD",$lb_valido,$this->io_fecha->is_msg_error);
			return $lb_valido;
		}                    
		$ls_numsol=$this->io_keygen->uf_generar_numero_nuevo("SEP","sep_solicitud","numsol","SEPSPC",15,"","","");
		$lb_valido=$this->uf_insert_solicitud($as_archivo,$as_rutaarchivo,&$ls_numsol,$aa_seguridad);
		
		if ($lb_valido)
		{
			$this->io_xml->uf_update_xml_solicitud($as_archivo,$as_rutaarchivo,"SEP_SOLICITUD",$ls_numsol);
		}                    
		return $lb_valido;
	}// end function uf_guardar_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_solicitud($as_archivo,$as_rutaarchivo,&$as_numsol,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_solicitud
		//		   Access: private
		//	    Arguments: as_rutaarchivo  // Ruta del Archivo XML
		//	    		   as_archivo  // Archivo xml
		//				   as_numsol  // Número de Solicitud 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta la solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		// Fecha Creación: 28/07/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $ls_numsolaux=$as_numsol; 
	    $lb_valido= $this->io_keygen->uf_verificar_numero_generado("SEP","sep_solicitud","numsol","SEPSPC",15,"","","",&$as_numsol);
		$lb_valido=true;
		if($lb_valido)
		{
			$as_codtipsol=$this->io_cabecera->getValue('codtipsol',1);
			$as_coduniadm=$this->io_cabecera->getValue('coduniadm',1);
			$ad_fecregsol=$this->io_cabecera->getValue('fecregsol',1);
			$as_consol=$this->io_cabecera->getValue('consol',1);
			$ai_total=$this->io_cabecera->getValue('monto',1);
			$ai_subtotal=$this->io_cabecera->getValue('monbasinm',1);
			$ai_cargos=$this->io_cabecera->getValue('montotcar',1);
			$as_codprov=$this->io_cabecera->getValue('cod_pro',1);
			$as_cedben=$this->io_cabecera->getValue('ced_bene',1);
			$as_tipodestino=$this->io_cabecera->getValue('tipo_destino',1);
			$as_codfuefin=$this->io_cabecera->getValue('codfuefin',1);
			$as_codestpro1=$this->io_cabecera->getValue('codestpro1',1);
			$as_codestpro2=$this->io_cabecera->getValue('codestpro2',1);
			$as_codestpro3=$this->io_cabecera->getValue('codestpro3',1);
			$as_codestpro4=$this->io_cabecera->getValue('codestpro4',1);
			$as_codestpro5=$this->io_cabecera->getValue('codestpro5',1);
			$as_estcla=$this->io_cabecera->getValue('estcla',1);
			$ls_sql="INSERT INTO sep_solicitud (codemp,numsol,codtipsol,coduniadm,fecregsol,estsol,consol,monto,".
					" 							monbasinm,montotcar,cod_pro,ced_bene,tipo_destino,codfuefin,estapro,".
					"                           codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$as_codtipsol."','".$as_coduniadm."',".
					" 			  '".$ad_fecregsol."','E','".$as_consol."',".$ai_total.",".$ai_subtotal.",".$ai_cargos.",".
					"			  '".$as_codprov."','".$as_cedben."','".$as_tipodestino."','".$as_codfuefin."',0,'".$as_codestpro1."',".
					"             '".$as_codestpro2."','".$as_codestpro3."','".$as_codestpro4."','".$as_codestpro5."','".$as_estcla."')"; 
			$this->io_sql->begin_transaction();				
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				 $this->io_sql->rollback();
				 if ($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
				 {
					 $this->uf_insert_solicitud($as_archivo,$as_rutaarchivo,&$as_numsol,$aa_seguridad);
				 }
				 else
				 {
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_xml->uf_update_xml_procesado($as_archivo,$as_rutaarchivo,"SEP_SOLICITUD",$lb_valido,$this->io_sql->message);
				 }
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la solicitud ".$as_numsol." Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_conceptos($as_archivo,$as_rutaarchivo,$as_numsol,$aa_seguridad);
				}			
				if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_cuentas($as_archivo,$as_rutaarchivo,$as_numsol,$aa_seguridad);
				}
				if($lb_valido)
				{	
					$lb_valido=true;
					$this->io_sql->commit();
				}			
				else
				{
					$lb_valido=false;
					$this->io_sql->rollback();
				}
			}
		}
		return $lb_valido;
	}// end function uf_insert_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_conceptos($as_archivo,$as_rutaarchivo,$as_numsol,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_conceptos
		//		   Access: private
		//	    Arguments: as_rutaarchivo  // Ruta del Archivo XML
		//	    		   as_archivo  // Archivo xml
		//				   as_numsol  // Número de Solicitud 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta los conceptos de una  Solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		// Fecha Creación: 28/07/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total=$this->io_conceptos->getRowCount('codconsep');
		// Recorremos el data stored de cuentas que se lleno y se agrupo anteriormente
		for($li_i=1;$li_i<=$li_total;$li_i++)
		{
			$ls_codcon=$this->io_conceptos->getValue('codconsep',$li_i);
			$ls_dencon=$this->io_conceptos->getValue('codconsep',$li_i);
			$li_cancon=$this->io_conceptos->getValue('cancon',$li_i);
			$li_precon=$this->io_conceptos->getValue('monpre',$li_i);
			$li_totcon=$this->io_conceptos->getValue('moncon',$li_i);
			$ls_estcla=$this->io_conceptos->getValue('estcla',$li_i);	
			$ls_spgcuenta=$this->io_conceptos->getValue('spg_cuenta',$li_i);	
			$ls_codestpro1=$this->io_conceptos->getValue('codestpro1',$li_i);
			$ls_codestpro2=$this->io_conceptos->getValue('codestpro2',$li_i);
			$ls_codestpro3=$this->io_conceptos->getValue('codestpro3',$li_i);
			$ls_codestpro4=$this->io_conceptos->getValue('codestpro4',$li_i);
			$ls_codestpro5=$this->io_conceptos->getValue('codestpro5',$li_i);
			$ls_sql="INSERT INTO sep_dt_concepto (codemp, numsol, codconsep, cancon, monpre, moncon, orden, codestpro1, codestpro2, ".
					"							  codestpro3, codestpro4, codestpro5, spg_cuenta,estcla)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$ls_codcon."',".$li_cancon.",".
					" 			  ".$li_precon.",".$li_totcon.",".$li_i.",'".$ls_codestpro1."','".$ls_codestpro2."',".
					"			  '".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_spgcuenta."','".$ls_estcla."')";    
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_conceptos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_xml->uf_update_xml_procesado($as_archivo,$as_rutaarchivo,"SEP_SOLICITUD","Error en Detalle.");
				$this->io_xml->uf_update_xml_procesado($as_archivo,$as_rutaarchivo,"SEP_DT_CONCEPTOS",$lb_valido,$this->io_sql->message);
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Concepto ".$ls_codcon." a la SEP ".$as_numsol.
								 " Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}
		}
		return $lb_valido;
	}// end function uf_insert_conceptos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cuentas($as_archivo,$as_rutaarchivo,$as_numsol,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cuentas
		//		   Access: private
		//	    Arguments: as_rutaarchivo  // Ruta del Archivo XML
		//	    		   as_archivo  // Archivo xml
		//				   as_numsol  // Número de Solicitud 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta los conceptos de una  Solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		// Fecha Creación: 28/07/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total=$this->io_spgcuentas->getRowCount('codestpro1');	
		for($li_fila=1;$li_fila<=$li_total;$li_fila++)
		{
			$ls_codestpro1=$this->io_spgcuentas->getValue('codestpro1',$li_fila);
			$ls_codestpro2=$this->io_spgcuentas->getValue('codestpro2',$li_fila);
			$ls_codestpro3=$this->io_spgcuentas->getValue('codestpro3',$li_fila);
			$ls_codestpro4=$this->io_spgcuentas->getValue('codestpro4',$li_fila);
			$ls_codestpro5=$this->io_spgcuentas->getValue('codestpro5',$li_fila);
			$li_moncue=$this->io_spgcuentas->getValue('monto',$li_fila);
			$ls_estcla=$this->io_spgcuentas->getValue('estcla',$li_fila);
			$ls_cuenta=$this->io_spgcuentas->getValue('spg_cuenta',$li_fila);

			$ls_sql="INSERT INTO sep_cuentagasto (codemp, numsol, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, ".
					"							  spg_cuenta, monto,estcla)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$ls_codestpro1."','".$ls_codestpro2."',".
					" 			  '".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_cuenta."',".$li_moncue.",'".$ls_estcla."')";        
			$li_row=$this->io_sql->execute($ls_sql); 
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_cuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_xml->uf_update_xml_procesado($as_archivo,$as_rutaarchivo,"SEP_SOLICITUD",$lb_valido,"Error en Detalle.");
				$this->io_xml->uf_update_xml_procesado($as_archivo,$as_rutaarchivo,"SEP_DT_CONCEPTOS",$lb_valido,$this->io_sql->message);
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la Cuenta ".$ls_cuenta." de programatica ".$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5." a la SEP ".$as_numsol. " Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}
		}
		return $lb_valido;
	}// end function uf_insert_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>