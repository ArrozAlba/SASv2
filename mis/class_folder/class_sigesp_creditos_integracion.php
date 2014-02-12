<?php
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  //       Class : class_sigesp_creditos_integracion                                                     //    
  // Description : Esta clase tiene todos los metodos necesario para el manejo de la rutina integradora //
  //               con el sistema de presupuesto de  gasto y el sistema de solicitud presupuestaria.    //               
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
class class_sigesp_creditos_integracion
{
	var $sqlca;   
    var $is_msg_error;
	var $dts_empresa; 
	var $dts_solicitud;
	var $obj="";
	var $io_sql;
	var $io_siginc;
	var $io_conect;
	var $io_function;	
    var $io_sigesp_int;
	var $io_fecha;
	var $io_msg;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function class_sigesp_creditos_integracion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: class_sigesp_creditos_integracion
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 												Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/class_sql.php");  
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_sigesp_int.php");
		require_once("../shared/class_folder/class_sigesp_int_int.php");
		require_once("../shared/class_folder/class_sigesp_int_spg.php");
		require_once("../shared/class_folder/class_sigesp_int_scg.php");
		require_once("../shared/class_folder/class_sigesp_int_spi.php");
		require_once("../shared/class_folder/class_fecha.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("class_funciones_mis.php");
		require_once("../shared/class_folder/class_funciones_xml.php");
	    $this->io_fun_mis=new class_funciones_mis();
	    $this->io_fecha=new class_fecha();
        $this->io_sigesp_int=new class_sigesp_int_int();
		$this->io_function=new class_funciones() ;
		$this->io_siginc=new sigesp_include();
		$this->io_connect=$this->io_siginc->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);		
		$this->obj=new class_datastore();
		$this->dts_empresa=$_SESSION["la_empresa"];
		$this->dts_solicitud=new class_datastore();
		$this->io_msg=new class_mensajes();		
		$this->io_seguridad=new sigesp_c_seguridad() ;
		$this->as_procede="";
		$this->as_comprobante="";
		$this->ad_fecha="";
		$this->as_codban="";
		$this->as_ctaban="";
		$this->io_xml=new class_funciones_xml();		
	}// end function class_sigesp_creditos_integracion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destroy_objects()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destroy_objects
		//		   Access: public 
		//	  Description: Destructor de los objectos de la Clase
		//	   Creado Por: Ing. Yesenia Moreno	
		// Modificado Por: 													Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if( is_object($this->io_fecha) ) { unset($this->io_fecha);  }
		if( is_object($this->io_sigesp_int) ) { unset($this->io_sigesp_int);  }
		if( is_object($this->io_function) ) { unset($this->io_function);  }
		if( is_object($this->io_siginc) ) { unset($this->io_siginc);  }
		if( is_object($this->io_connect) ) { unset($this->io_connect);  }
		if( is_object($this->io_sql) ) { unset($this->io_sql);  }	   
		if( is_object($this->obj) ) { unset($this->obj);  }	   
		if( is_object($this->dts_empresa) ) { unset($this->dts_empresa);  }	   
		if( is_object($this->dts_solicitud) ) { unset($this->dts_solicitud);  }	   
		if( is_object($this->io_msg) ) { unset($this->io_msg);  }	   
		if( is_object($this->io_seguridad) ) { unset($this->io_seguridad);  }	   
	}// end function class_sigesp_sep_integracion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_beneficiarios($as_archivo,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_beneficiarios
		//		   Access: private
		//	    Arguments: as_archivo  // Archivo xml
		//	      Returns: lb_valido True si se ejecuto el insert correctamente
		//	  Description: Método que lee el archivo xml e inserta los beneficiarios
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		// Modificado Por: 														Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$la_data=$this->io_xml->uf_cargar_rpc_beneficiario($as_archivo);
		$li_total=count($la_data);
		for($i=1;$i<=$li_total;$i++)
		{
			$ls_codemp=$la_data[$i]["codemp"];
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
			$lb_valido=$this->io_xml->uf_validar_scgcuenta($ls_codemp,$ls_sccuenta);
			if($lb_valido)
			{
				$lb_valido=$this->uf_insert_beneficiario($ls_codemp,$ls_cedbene,$ls_nombene,$ls_apebene,$ls_dirbene,$ls_telbene,$ls_celbene,
														 $ls_email,$ls_sccuenta,$ls_codpai,$ls_codest,$ls_codmun,$ls_codpar,$ls_nacben,
														 $ls_codtipcta,$ls_rifben,$ls_codbansig,$ls_codban,$ls_ctaban,$ls_foto,
														 $ls_fecregben,$ls_numpasben,$ls_tipconben,$as_archivo,$aa_seguridad);
			}
			else
			{
				$this->io_xml->uf_update_xml_procesado(substr($as_archivo,strlen($as_archivo)-15,15),"../scc/II/pendientes","RPC_BENEFICIARIO",$lb_valido,"La cuenta Contable no existe");
			}
	   	} // end if 
	    return $lb_valido;
    }// end function uf_procesar_beneficiarios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_beneficiario($as_codemp,$as_cedbene,$as_nombene,$as_apebene,$as_dirbene,$as_telbene,$as_celbene,$as_email,$as_sccuenta,
									$as_codpai,$as_codest,$as_codmun,$as_codpar,$as_nacben,$as_codtipcta,$as_rifben,$as_codbansig,
									$as_codban,$as_ctaban,$as_foto,$as_fecregben,$as_numpasben,$as_tipconben,$as_archivo,$aa_seguridad)
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
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 02/08/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT ced_bene ".
				"  FROM rpc_beneficiario ".
				" WHERE codemp='".$as_codemp."'".
				"   AND ced_bene='".$as_cedbene."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_insert_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_xml->uf_update_xml_procesado(substr($as_archivo,strlen($as_archivo)-15,15),"../scc/II/pendientes","RPC_BENEFICIARIO",$lb_valido,$this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->io_xml->uf_update_xml_procesado(substr($as_archivo,strlen($as_archivo)-15,15),"../scc/II/pendientes","RPC_BENEFICIARIO",$lb_valido,"El beneficario ya existe");
			}
			else
			{
				$ls_sql="INSERT INTO rpc_beneficiario(codemp, ced_bene, nombene, apebene, dirbene, telbene, celbene, email, sc_cuenta, ".
						"codpai,codest,codmun,codpar,nacben,tipconben,codbansig,codban,ctaban,fecregben,codtipcta,rifben,numpasben) ".
						"VALUES ('".$as_codemp."', ".
						"'".$as_cedbene."', '".$as_nombene."', '".$as_apebene."', '".$as_dirbene."', '".$as_telbene."', '".$as_celbene."', ".
						"'".$as_email."', '".$as_sccuenta."', '".$as_codpai."', '".$as_codest."', '".$as_codmun."', '".$as_codpar."', ".
						"'".$as_nacben."','".$as_tipconben."','".$as_codbansig."','".$as_codban."','".$as_ctaban."','".$as_fecregben."', ".
						"'".$as_codtipcta."','".$as_rifben."','".$as_numpasben."')";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_insert_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_xml->uf_update_xml_procesado(substr($as_archivo,strlen($as_archivo)-15,15),"../scc/II/pendientes","RPC_BENEFICIARIO",$lb_valido,$this->io_sql->message);
				}
				else
				{
					$this->io_xml->uf_update_xml_procesado(substr($as_archivo,strlen($as_archivo)-15,15),"../scc/II/pendientes","RPC_BENEFICIARIO",$lb_valido,"Registro Incluido.");
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
	function uf_procesar_aprobacioncreditos($as_archivo,$as_codemp,$as_comprobante,$as_fecha,$as_procede,$as_codban,$as_ctaban,$as_descripcion,
											$as_tipo_comp,$as_tipo_destino,$as_cod_pro,$as_ced_bene,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_aprobacioncreditos
		//		   Access: public (sigesp_mis_p_contabiliza_sep.php)
		//	    Arguments: as_archivo // archivo xml;
		//				   as_codemp  // código de empresa
		//				   as_comprobante  // Número de Comprobante
		//				   as_fecha  // Fecha de contabilización
		//				   as_procede  // PRocede
		//				   as_codban  // Código de Banco
		//				   as_ctaban  // Cuenta de Banco
		//				   as_descripcion  // Descripción
		//				   as_tipo_comp  // Tipo de Comprobantes
		//				   as_tipo_destino  // Destino de contabilización
		//				   as_cod_pro  // Código de proveedor
		//				   as_ced_bene  // Cédula del Beneficiario
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Este metodo tiene como fin contabilizar en presupuesto el crédito
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 													Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Inicio transacción SQL
        $this->io_sigesp_int->uf_int_init_transaction_begin();
		$as_tipo_comp=1;
        if ($as_tipo_destino=="P")
		{
			$ls_codigo_destino=$as_cod_pro; 
		}
        else
        {
			$ls_codigo_destino=$as_ced_bene; 
        }
		$lb_valido = $this->io_sigesp_int->uf_int_init($as_codemp,$as_procede,$as_comprobante,$as_fecha,$as_descripcion,
													   $as_tipo_destino,$ls_codigo_destino,true,$as_codban,$as_ctaban,
													   $as_tipo_comp);
		
		if($lb_valido)
		{
			$lb_valido=$this->uf_procesar_detalle_aprobacion($as_archivo,$as_codemp,$as_procede,$as_comprobante,$as_fecha,$as_codban,$as_ctaban);
		}
		else
		{
			$this->io_xml->uf_update_xml_procesado(substr($as_archivo,strlen($as_archivo)-15,15),"../scc/II/pendientes","SIGESP_CMP",$lb_valido,$this->io_sigesp_int->is_msg_error);		
		}
		if(!$lb_valido)
		{
			$this->io_sigesp_int->uf_sql_transaction($lb_valido);
			return false;
		}           
		else
		{
			$lb_valido=$this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
			if($lb_valido===false)
			{
				$this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error);
				$this->io_xml->uf_update_xml_procesado(substr($as_archivo,strlen($as_archivo)-15,15),"../scc/II/pendientes","SIGESP_CMP",$lb_valido,"Error en la Integracion");		
				$this->io_xml->uf_update_xml_procesado(substr($as_archivo,strlen($as_archivo)-15,15),"../scc/II/pendientes","SPG_DT_CMP",$lb_valido,"Error en la Integracion");		
			}
			else
			{
				$this->io_xml->uf_update_xml_procesado(substr($as_archivo,strlen($as_archivo)-15,15),"../scc/II/pendientes","SIGESP_CMP",$lb_valido,"Credito Aprobado");		
				$this->io_xml->uf_update_xml_procesado(substr($as_archivo,strlen($as_archivo)-15,15),"../scc/II/pendientes","SPG_DT_CMP",$lb_valido,"Credito Aprobado");		
			}
			
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Aprobó el crédito <b>".$as_comprobante."</b>, ".
							"Fecha de Contabilización <b>".$as_fecha."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		$this->io_xml->uf_mover_xml(substr($as_archivo,strlen($as_archivo)-15,15),$as_archivo,"../scc/II/procesados");
		return  $lb_valido;
	}// end function uf_procesar_aprobacioncreditos
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_detalle_aprobacion($as_archivo,$as_codemp,$as_procede,$as_comprobante,$as_fecha,$as_codban,$as_ctaban)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_detalle_aprobacion
		//		   Access: private
		//	    Arguments: as_codemp  // código de empresa
		//				   as_comprobante  // Número de Comprobante
		//				   as_fecha  // Fecha de contabilización
		//				   as_procede  // PRocede
		//				   as_codban  // Código de Banco
		//				   as_ctaban  // Cuenta de Banco
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Método que procesa todos los registros presupuestario 
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		// Modificado Por: 														Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_fecha=$this->io_fecha->uf_convert_date_to_db($as_fecha);
		$la_data=$this->io_xml->uf_cargar_spg_dt_cmp($as_archivo,$as_codemp,$as_procede,$as_comprobante,$as_fecha,$as_codban,$as_ctaban);
		$li_total=count($la_data);
		for($i=1;$i<=$li_total;$i++)
		{
			$ls_codestpro1=$la_data[$i]["codestpro1"];
			$ls_codestpro2=$la_data[$i]["codestpro2"];
			$ls_codestpro3=$la_data[$i]["codestpro3"];
			$ls_codestpro4=$la_data[$i]["codestpro4"];
			$ls_codestpro5=$la_data[$i]["codestpro5"];			  
			$ls_estcla=$la_data[$i]["estcla"];			  
			$ls_spg_cuenta=$la_data[$i]["spg_cuenta"];			
			$ls_documento=$la_data[$i]["documento"];			
			$ls_procededoc=$la_data[$i]["procede_doc"];			
			$ldec_monto=$la_data[$i]["monto"];
			$ls_descripcion=$la_data[$i]["descripcion"];
			$ls_mensaje="O";//$la_data[$i]["operacion"];
			$lb_valido = $this->io_sigesp_int->uf_spg_insert_datastore($as_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																   	   $ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,$ls_mensaje,
																       $ldec_monto,$ls_documento,$ls_procededoc,$ls_descripcion);
				if (!$lb_valido)
				{  
					$this->io_xml->uf_update_xml_procesado(substr($as_archivo,strlen($as_archivo)-15,15),"../scc/II/pendientes","SPG_DT_CMP",$lb_valido,$this->io_sigesp_int->is_msg_error);		
					$this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error);
					break;
				}
	   	} // end if 
	    return $lb_valido;
    }// end function uf_procesar_detalle_aprobacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_cuentasporcobrar($as_archivo,$as_codemp,$as_comprobante,$as_fecha,$as_procede,$as_codban,$as_ctaban,$as_descripcion,
											$as_tipo_comp,$as_tipo_destino,$as_cod_pro,$as_ced_bene,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_cuentasporcobrar
		//		   Access: public (sigesp_mis_p_cuentasporcobrar.php)
		//	    Arguments: as_archivo // archivo xml;
		//				   as_codemp  // código de empresa
		//				   as_comprobante  // Número de Comprobante
		//				   as_fecha  // Fecha de contabilización
		//				   as_procede  // PRocede
		//				   as_codban  // Código de Banco
		//				   as_ctaban  // Cuenta de Banco
		//				   as_descripcion  // Descripción
		//				   as_tipo_comp  // Tipo de Comprobantes
		//				   as_tipo_destino  // Destino de contabilización
		//				   as_cod_pro  // Código de proveedor
		//				   as_ced_bene  // Cédula del Beneficiario
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Este metodo tiene como fin contabilizar la cuenta por cobrar
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 													Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Inicio transacción SQL
        $this->io_sigesp_int->uf_int_init_transaction_begin();
		$as_tipo_comp=1;
        if ($as_tipo_destino=="P")
		{
			$ls_codigo_destino=$as_cod_pro; 
		}
        else
        {
			$ls_codigo_destino=$as_ced_bene; 
        }
		$lb_valido = $this->io_sigesp_int->uf_int_init($as_codemp,$as_procede,$as_comprobante,$as_fecha,$as_descripcion,
													   $as_tipo_destino,$ls_codigo_destino,true,$as_codban,$as_ctaban,
													   $as_tipo_comp);
		
		if($lb_valido)
		{
			$lb_valido=$this->uf_procesar_detalle_cuentaporcobrar($as_archivo,$as_codemp,$as_procede,$as_comprobante,$as_fecha,$as_codban,$as_ctaban);
		}
		else
		{
			$this->io_xml->uf_update_xml_procesado(substr($as_archivo,strlen($as_archivo)-15,15),"../scc/cxc/I/pendientes","SIGESP_CMP",$lb_valido,$this->io_sigesp_int->is_msg_error);		
		}
		if(!$lb_valido)
		{
			$this->io_sigesp_int->uf_sql_transaction($lb_valido);
			return false;
		}           
		else
		{
			$lb_valido=$this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
			if($lb_valido===false)
			{
				$this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error);
				$this->io_xml->uf_update_xml_procesado(substr($as_archivo,strlen($as_archivo)-15,15),"../scc/cxc/I/pendientes","SIGESP_CMP",$lb_valido,"Error en la Integracion");		
				$this->io_xml->uf_update_xml_procesado(substr($as_archivo,strlen($as_archivo)-15,15),"../scc/cxc/I/pendientes","SPI_DT_CMP",$lb_valido,"Error en la Integracion");		
				$this->io_xml->uf_update_xml_procesado(substr($as_archivo,strlen($as_archivo)-15,15),"../scc/cxc/I/pendientes","SCG_DT_CMP",$lb_valido,"Error en la Integracion");		
			}
			else
			{
				$this->io_xml->uf_update_xml_procesado(substr($as_archivo,strlen($as_archivo)-15,15),"../scc/cxc/I/pendientes","SIGESP_CMP",$lb_valido,"Credito Aprobado");		
				$this->io_xml->uf_update_xml_procesado(substr($as_archivo,strlen($as_archivo)-15,15),"../scc/cxc/I/pendientes","SPI_DT_CMP",$lb_valido,"Credito Aprobado");		
				$this->io_xml->uf_update_xml_procesado(substr($as_archivo,strlen($as_archivo)-15,15),"../scc/cxc/I/pendientes","SCG_DT_CMP",$lb_valido,"Credito Aprobado");		
			}
			
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Contabilizó la Cuenta por Cobrar <b>".$as_comprobante."</b>, ".
							"Fecha de Contabilización <b>".$as_fecha."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		$this->io_xml->uf_mover_xml(substr($as_archivo,strlen($as_archivo)-15,15),$as_archivo,"../scc/cxc/I/procesados");
		return  $lb_valido;
	}// end function uf_procesar_contabilizacion_solicitud_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_detalle_cuentaporcobrar($as_archivo,$as_codemp,$as_procede,$as_comprobante,$as_fecha,$as_codban,$as_ctaban)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_detalle_cuentaporcobrar
		//		   Access: private
		//	    Arguments: as_codemp  // código de empresa
		//				   as_comprobante  // Número de Comprobante
		//				   as_fecha  // Fecha de contabilización
		//				   as_procede  // PRocede
		//				   as_codban  // Código de Banco
		//				   as_ctaban  // Cuenta de Banco
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Método que procesa todos los registros de ingreso y contables
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		// Modificado Por: 														Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_fecha=$this->io_fecha->uf_convert_date_to_db($as_fecha);
		$la_data=$this->io_xml->uf_cargar_scg_dt_cmp($as_archivo,$as_codemp,$as_procede,$as_comprobante,$as_fecha,$as_codban,$as_ctaban);
		$li_total=count($la_data);
		for($i=1;$i<=$li_total;$i++)
		{
			$ls_scg_cuenta=$la_data[$i]["sc_cuenta"];
			$ls_debhab=$la_data[$i]["operacion"];		
			$ldec_monto=$la_data[$i]["monto"];		
			$ls_documento=$la_data[$i]["documento"];
			$ls_descripcion=$la_data[$i]["descripcion"];
			$ls_procededoc=$la_data[$i]["procede_doc"];			
			$lb_valido=$this->io_sigesp_int->uf_scg_insert_datastore($as_codemp,$ls_scg_cuenta,$ls_debhab,$ldec_monto,$ls_documento,$ls_procededoc,
																	 $ls_descripcion);								
			if ($lb_valido===false)
			{  
				$this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error);
				break;
			}
		}
		$la_data=$this->io_xml->uf_cargar_spi_dt_cmp($as_archivo,$as_codemp,$as_procede,$as_comprobante,$as_fecha,$as_codban,$as_ctaban);
		$li_total=count($la_data);
		for($i=1;$i<=$li_total;$i++)
		{
			$ls_spi_cuenta=$la_data[$i]["spi_cuenta"];
			$ldec_monto=$la_data[$i]["monto"];
			$ls_documento=$la_data[$i]["documento"];
			$ls_procede=$la_data[$i]["procede_doc"];
			$ls_operacion=$la_data[$i]["operacion"];
			$ls_descripcion=$la_data[$i]["descripcion"];
			$ls_mensaje=$this->io_sigesp_spi->uf_operacion_codigo_mensaje($ls_operacion);
			$ls_spi_cuenta=$this->io_sigesp_spi->uf_spi_pad_cuenta($ls_spi_cuenta);
			$lb_valido=$this->io_sigesp_int->uf_spi_insert_datastore($as_codemp,$ls_spi_cuenta,$ls_operacion,
																	 $ldec_monto,$ls_documento,$ls_procede,$ls_descripcion);
			if ($lb_valido===false)
			{  
				$this->io_msg->message("ERROR ->".$this->io_sigesp_int->is_msg_error);
				break;
			}
		}
	    return $lb_valido;
    }// end function uf_procesar_detalle_cuentaporcobrar
	//-----------------------------------------------------------------------------------------------------------------------------------

}
?>