<?php
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  //       Class : class_sigesp_cxp_integracion_php                                                     //    
  // Description : Esta clase tiene todos los metodos necesario para el manejo de la rutina integradora //
  //               de las cuentas por pagar solicitudes de pago                                         //               
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
class class_sigesp_cxp_integracion
{
	//Instancia de la clase funciones.
    var $is_msg_error;
	var $dts_empresa; 
	var $dts_solicitud;
	var $dts_notas;	
	var $dts_proveedor;
	var $dts_beneficiario;
	var $dts_inter;
	var $obj="";
	var $io_sql;
	var $io_siginc;
	var $io_conect;
	var $io_function;	
    var $io_sigesp_int;
	var $io_fecha;
	var $io_msg;
    var $io_codemp;	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function class_sigesp_cxp_integracion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: class_sigesp_cxp_integracion
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 05/01/2007
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
	    $this->io_fun_mis=new class_funciones_mis();
	    $this->io_fecha=new class_fecha();
        $this->io_sigesp_int=new class_sigesp_int_int();
		$this->io_function=new class_funciones() ;
		$this->io_siginc=new sigesp_include();
		$this->io_connect=$this->io_siginc->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);		
		$this->obj=new class_datastore();
		$this->dts_empresa=$_SESSION["la_empresa"];
		$this->io_codemp=$this->dts_empresa["codemp"];		
		$this->dts_solicitud=new class_datastore();
		$this->dts_notas=new class_datastore();
		$this->dts_beneficiario=new class_datastore();
		$this->dts_proveedor=new class_datastore();
		$this->dts_interd=new class_datastore();
		$this->dts_interh=new class_datastore();
		$this->dts_rd=new class_datastore();
		$this->io_msg=new class_mensajes();		
		$this->io_seguridad=new sigesp_c_seguridad();
		$this->as_procede="";
		$this->as_comprobante="";
		$this->ad_fecha="";
		$this->as_codban="";
		$this->as_ctaban="";
		$this->as_descripcion="";
		$this->ls_estempcon=$_SESSION["la_empresa"]["estempcon"];
		$this->ls_basdatcon=$_SESSION["la_empresa"]["basdatcon"];
	}// end function class_sigesp_cxp_integracion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destroy_objects()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destroy_objects
		//		   Access: public 
		//	  Description: Destructor de los objectos de la Clase
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 08/01/2007
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
	}// end function class_sigesp_sep_integracion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_contabilizacion_recepcion($as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contabilizacion_recepcion
		//		   Access: public (sigesp_mis_p_contabiliza_rd.php)
		//	    Arguments: as_numrecdoc  // Número de Recepción de Documentos
		//				   as_codtipdoc  // Código de tipos de Documentos
		//				   as_codpro     // Código de Proveedor
		//				   as_cedbene    // Cédula de Beneficiario
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Proceso de contabilizacion de la recepción de documentos de cuentas por pagar
		//	   Creado Por: Ing. Luis Anibal Lang
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_codemp=$this->io_codemp;
		$lb_valido=$this->uf_obtener_data_rd($ls_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene);		
		if(!$lb_valido)
		{
			return false;
		}
        $ls_procede="CXPRCD"; 
		$ldt_fecreg=$this->dts_rd->getValue("fecregdoc",1);
		$ldt_feccon=date("Y-m-d");
		$ls_descripcion=$this->dts_rd->getValue("dencondoc",1);
		$ls_codrecdoc=$this->dts_rd->getValue("codrecdoc",1);
		$ls_codpro=$this->dts_rd->getValue("cod_pro",1);
		$ls_cedbene=$this->dts_rd->getValue("ced_bene",1);
		$li_mensaje_spg=$this->dts_rd->getValue("estpre",1); 
		$li_mensaje_scg=$this->dts_rd->getValue("estcon",1); 		
		$ldt_fecreg=$this->io_function->uf_convertirdatetobd($ldt_fecreg);
		$ldt_feccon=$this->io_function->uf_convertirdatetobd($ldt_feccon);
		$ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante(trim($ls_codrecdoc));
		if(!$this->io_fecha->uf_comparar_fecha($ldt_fecreg,$ldt_feccon))
		{
			$this->io_msg->message(" La Fecha de Contabilizacion es menor que la fecha de Registro de la Recepción ".$as_numrecdoc);
			return false;
		}
		$ldt_fecha=$ldt_fecreg;
		switch($ls_codpro)
		{
			case "----------":
				$ls_tipo_destino="B";
				$ls_codigo_destino =$ls_cedbene;
				break;
			default:
				$ls_tipo_destino="P";
				$ls_codigo_destino =$ls_codpro;
				break;
		}
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		$li_tipo_comp=1; // comprobante Normal
		$this->as_procede=$ls_procede;
		$this->as_comprobante=$ls_comprobante;
		$this->ad_fecha=$ldt_fecha;
		$this->as_codban=$ls_codban;
		$this->as_ctaban=$ls_ctaban;
		$this->as_descripcion=$ls_descripcion;
        // inicia transacción SQL
		$this->io_sigesp_int->uf_int_init_transaction_begin(); 
		$lb_valido=$this->io_sigesp_int->uf_int_init($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_descripcion,
													 $ls_tipo_destino,$ls_codigo_destino,false,$ls_codban,$ls_ctaban,
													 $li_tipo_comp);
		if ($lb_valido===false)
		{   
			$this->io_msg->message($this->io_sigesp_int->is_msg_error); 
			return false;		   		   
		}
		$this->io_sigesp_int->uf_int_config(false,false);
        $lb_valido = $this->uf_procesar_contabilizacion_rd($ls_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,
														   $li_mensaje_spg,$li_mensaje_scg,$ls_descripcion);
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_estatus_contabilizado_recepcion($ls_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,"C");
			if ($lb_valido)
			{
				if ($lb_valido)
				{
					$lb_valido = $this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad);
				} 
				if(($lb_valido===false)&&($this->io_sigesp_int->is_msg_error!=""))
				{
					$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
				}		   
				if($lb_valido)
				{
					$lb_valido=$this->uf_insert_historico_contabilizacion_rd($ls_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,$ldt_fecha,"C");
				}
			}
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_rd($ls_codemp,$as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$ldt_fecha,'1900-01-01');
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Contabilizó la Recepcion de Documentos <b>".$as_numrecdoc."</b> - <b>".$as_codtipdoc."</b> - <b>".$as_cedbene."</b> - <b>".$as_codpro."</b>, ".
							"Fecha de Contabilización <b>".$ldt_feccon."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
	}// end function uf_procesar_contabilizacion_recepcion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_contabilizacion_rd($as_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,$ai_mensaje_spg,
											$ai_mensaje_scg,$as_descripcion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contabilizacion_comprobante
		//		   Access: private
		//	    Arguments: as_codemp  // Código de Empresa
		//				   as_numrecdoc  //Número de Recepción 
		//				   as_codtipdoc  // Tipo de Documento
		//				   as_codpro  // Código del proveedor
		//				   as_cedbene  // Código del beneficiario
		//	      Returns: Retorna datos de la solicitud compras mediante un datastrore publico
		//	  Description: Método que genera los asientos presupuestarios y contables a partir 
		//                  de los movmientos de la recepción de documentos
		//	   Creado Por: Ing. Yesenia Moreno	
		// Modificado Por: 													Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$lb_desagregar_iva=false;		
		$ls_mensaje="";
		// Mensaje de operacion de gasto a ser aplicada
		if($ai_mensaje_spg==1)
		{
			$ls_mensaje="C";
		}
		elseif($ai_mensaje_spg==2)
		{
			$ls_mensaje="OC";
		}
		// Mensaje de operacion contable a ser aplicada			  
		if($ai_mensaje_scg==1)
		{
			$ls_debhab="H";
		}
		if($ai_mensaje_spg!=3)
		{
			$lb_valido=$this->uf_procesar_detalles_gastos($as_codemp,$as_codpro,$as_cedbene,$as_codtipdoc,$as_numrecdoc,
														  $as_descripcion,$ls_mensaje,$lb_desagregar_iva);
			if(!$lb_valido) 
			{ 
				$lb_valido=false;
				return false;
			}
		}
		if($ai_mensaje_scg!=2)
		{
			$lb_valido=$this->uf_procesar_detalles_contables($as_codemp,$as_codpro,$as_cedbene,$as_codtipdoc,
															 $as_numrecdoc,$as_descripcion,$lb_desagregar_iva);
			if(!$lb_valido)
			{ 
				$lb_valido=false;
				return false;
			}
		}
		return $lb_valido;
    }// end function uf_procesar_contabilizacion_comprobante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_data_rd($as_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_data_rd
		//		   Access: private
		//	    Arguments: as_codemp  // Código de Empresa
		//	    		   as_numrecdoc  // Número de Recepción de Documentos
		//				   as_codtipdoc  // Código de tipos de Documentos
		//				   as_codpro     // Código de Proveedor
		//				   as_cedbene    // Cédula de Beneficiario
		//	      Returns: Retorna datos de la solicitud compras mediante un datastrore publico
		//	  Description: Este metodo obtiene el registro de la orden de compra y lo guarada en un 
		//                  datastore publico para posteriores operaciones
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 10/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe=false;		
		$ls_sql="SELECT cxp_rd.*, cxp_documento.estcon, cxp_documento.estpre ".
                "  FROM cxp_rd, cxp_documento ".
                " WHERE cxp_rd.codemp='".$as_codemp."' ".
				"   AND cxp_rd.numrecdoc='".$as_numrecdoc."' ".
				"   AND cxp_rd.codtipdoc='".$as_codtipdoc."'".
				"   AND cxp_rd.cod_pro='".$as_codpro."'".
				"   AND cxp_rd.ced_bene='".$as_cedbene."'".
				"   AND cxp_rd.codtipdoc=cxp_documento.codtipdoc ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_obtener_data_rd ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true; // si existe se procedera a registrar en el datastore.				
                $this->dts_rd->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$this->io_msg->message("La Recepción de Documentos no existe en la base de datos.");
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}// end function uf_obtener_data_rd
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_contabilizacion_solicitud_cxp($as_numsol,$adt_fecha,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contabilizacion_solicitud_cxp
		//		   Access: public (sigesp_mis_p_contabiliza_cxp.php)
		//	    Arguments: as_numsol  // Número de Solicitud
		//				   as_fecha  // Fecha de contabilización
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Proceso de contabilizacion de la solicitud de pago de cuentas por pagar
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 08/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_codemp=$this->io_codemp;
		$li_estdesiva=$this->dts_empresa["estdesiva"];
		$lb_valido=$this->uf_obtener_data_solicitudes($ls_codemp,$as_numsol);		
		if(!$lb_valido)
		{
			return false;
		}
		$ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante(trim($as_numsol));
        $ls_procede="CXPSOP"; 
        $ls_tipo_destino=$this->dts_solicitud->getValue("tipproben",1);
		$ls_ced_bene=$this->dts_solicitud->getValue("ced_bene",1);		
		$ls_cod_pro=$this->dts_solicitud->getValue("cod_pro",1);		
		$ldt_fecemisol=$this->dts_solicitud->getValue("fecemisol",1);
		$ls_descripcion=$this->dts_solicitud->getValue("consol",1);
		$ldt_fecemisol=$this->io_function->uf_convertirdatetobd($ldt_fecemisol);
		$ldt_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
		if(!$this->io_fecha->uf_comparar_fecha($ldt_fecemisol,$ldt_fecha))
		{
			$this->io_msg->message(" La Fecha de Contabilizacion es menor que la fecha de Emision de la Solicitud Nº ".$as_numsol);
			return false;
		}
		$ldt_fecha=$ldt_fecemisol;
		if($ls_tipo_destino=="B") 
		{  
			$ls_codigo_destino=$ls_ced_bene;  
			$lb_valido=$this->uf_obtener_datos_beneficiario($ls_codemp,$ls_ced_bene);
			if(!$lb_valido)
			{
				return false;
			}
			$ls_scgcta_proben=$this->dts_beneficiario->getValue("sc_cuenta",1);
		}
		if($ls_tipo_destino=="P") 
		{
			$ls_codigo_destino=$ls_cod_pro;  
			$lb_valido=$this->uf_obtener_datos_proveedor($ls_codemp,$ls_cod_pro);
			if(!$lb_valido)
			{
				return false;
			}			
			$ls_scgcta_proben=$this->dts_proveedor->getValue("sc_cuenta",1);
		}
		if($ls_tipo_destino=="-")
		{
			$ls_codigo_destino = "----------";
		}
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		$li_tipo_comp=1; // comprobante Normal
		$this->as_procede=$ls_procede;
		$this->as_comprobante=$ls_comprobante;
		$this->ad_fecha=$ldt_fecha;
		$this->as_codban=$ls_codban;
		$this->as_ctaban=$ls_ctaban;
		$this->as_descripcion=$ls_descripcion;
		$lb_valido=$this->io_sigesp_int->uf_int_init($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_descripcion,
													 $ls_tipo_destino,$ls_codigo_destino,false,$ls_codban,$ls_ctaban,
													 $li_tipo_comp);
		if ($lb_valido===false)
		{   
			$this->io_msg->message($this->io_sigesp_int->is_msg_error); 
			return false;		   		   
		}
		$this->io_sigesp_int->uf_int_config(false,false);
        // inicia transacción SQL
		$this->io_sigesp_int->uf_int_init_transaction_begin(); 
        $lb_valido = $this->uf_procesar_contabilizacion_comprobante($ls_codemp,$as_numsol,$li_estdesiva,$ls_scgcta_proben);
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_estatus_contabilizado_cxp($ls_codemp,$as_numsol,"C");
			if ($lb_valido)
			{
				if ($lb_valido)
				{
					$lb_valido = $this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad);
				} 
				if(($lb_valido===false)&&($this->io_sigesp_int->is_msg_error!=""))
				{
					$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
				}		   
				if($lb_valido)
				{
					$lb_valido=$this->uf_update_estatus_detalles_solicitud($ls_codemp,$as_numsol,"C",false);
				}
				if($lb_valido)
				{
					$lb_valido=$this->uf_insert_historico_contabilizacion($ls_codemp,$as_numsol,$ldt_fecha,"C");
				}
			}
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_cxp($ls_codemp,$as_numsol,$ldt_fecha,'1900-01-01');
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Contabilizó la Solicitud de Pago <b>".$as_numsol."</b>, ".
							"Fecha de Contabilización <b>".$adt_fecha."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/// PARA LA CONVERSIÓN MONETARIA
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/*if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_sigespcmp($this->as_procede,$this->as_comprobante,$this->ad_fecha,
																 $this->as_codban,$this->as_ctaban,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_spgdtcmp($this->as_procede,$this->as_comprobante,$this->ad_fecha,
																$this->as_codban,$this->as_ctaban,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_scgdtcmp($this->as_procede,$this->as_comprobante,$this->ad_fecha,
																$this->as_codban,$this->as_ctaban,$aa_seguridad);
		}*/
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
	}// end function uf_contabilizar_solicitud_pago
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_contabilizacion_solicitud_rd($as_numsol,$adt_fecha,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contabilizacion_solicitud_rd
		//		   Access: public (sigesp_mis_p_contabiliza_cxp.php)
		//	    Arguments: as_numsol  // Número de Solicitud
		//				   as_fecha  // Fecha de contabilización
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Proceso de contabilizacion de la solicitud de pago de cuentas por pagar
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 															Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_codemp=$this->io_codemp;
		$li_estdesiva=$this->dts_empresa["estdesiva"];
		$lb_valido=$this->uf_obtener_data_solicitudes($ls_codemp,$as_numsol);		
		if(!$lb_valido)
		{
			return false;
		}
		$ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante(trim($as_numsol));
        $ls_procede="CXPSOP"; 
        $ls_tipo_destino=$this->dts_solicitud->getValue("tipproben",1);
		$ls_ced_bene=$this->dts_solicitud->getValue("ced_bene",1);		
		$ls_cod_pro=$this->dts_solicitud->getValue("cod_pro",1);		
		$ldt_fecemisol=$this->dts_solicitud->getValue("fecemisol",1);
		$ls_descripcion=$this->dts_solicitud->getValue("consol",1);
		$ldt_fecemisol=$this->io_function->uf_convertirdatetobd($ldt_fecemisol);
		$ldt_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
		if(!$this->io_fecha->uf_comparar_fecha($ldt_fecemisol,$ldt_fecha))
		{
			$this->io_msg->message(" La Fecha de Contabilizacion es menor que la fecha de Emision de la Solicitud Nº ".$as_numsol);
			return false;
		}
		$ldt_fecha=$ldt_fecemisol;
		if($ls_tipo_destino=="B") 
		{  
			$ls_codigo_destino=$ls_ced_bene;  
			$lb_valido=$this->uf_obtener_datos_beneficiario($ls_codemp,$ls_ced_bene);
			if(!$lb_valido)
			{
				return false;
			}
			$ls_scgcta_proben=$this->dts_beneficiario->getValue("sc_cuenta",1);
		}
		if($ls_tipo_destino=="P") 
		{
			$ls_codigo_destino=$ls_cod_pro;  
			$lb_valido=$this->uf_obtener_datos_proveedor($ls_codemp,$ls_cod_pro);
			if(!$lb_valido)
			{
				return false;
			}			
			$ls_scgcta_proben=$this->dts_proveedor->getValue("sc_cuenta",1);
		}
		if($ls_tipo_destino=="-")
		{
			$ls_codigo_destino = "----------";
		}
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		$li_tipo_comp=1; // comprobante Normal
		$this->as_procede=$ls_procede;
		$this->as_comprobante=$ls_comprobante;
		$this->ad_fecha=$ldt_fecha;
		$this->as_codban=$ls_codban;
		$this->as_ctaban=$ls_ctaban;
		$this->as_descripcion=$ls_descripcion;
		$lb_valido=$this->io_sigesp_int->uf_int_init($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_descripcion,
													 $ls_tipo_destino,$ls_codigo_destino,false,$ls_codban,$ls_ctaban,
													 $li_tipo_comp);
		if ($lb_valido===false)
		{   
			$this->io_msg->message($this->io_sigesp_int->is_msg_error); 
			return false;		   		   
		}
		$this->io_sigesp_int->uf_int_config(false,false);
        // inicia transacción SQL
		$this->io_sigesp_int->uf_int_init_transaction_begin(); 
        $lb_valido = $this->uf_procesar_detalles_contables_solicitud($ls_codemp,$as_numsol,$ls_descripcion);
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_estatus_contabilizado_cxp($ls_codemp,$as_numsol,"C");
			if ($lb_valido)
			{
				if ($lb_valido)
				{
					$lb_valido = $this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad);
				} 
				if(($lb_valido===false)&&($this->io_sigesp_int->is_msg_error!=""))
				{
					$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
				}		   
				if($lb_valido)
				{
					$lb_valido=$this->uf_insert_historico_contabilizacion($ls_codemp,$as_numsol,$ldt_fecha,"C");
				}
			}
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_cxp($ls_codemp,$as_numsol,$ldt_fecha,'1900-01-01');
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Contabilizó la Solicitud de Pago <b>".$as_numsol."</b>, ".
							"Fecha de Contabilización <b>".$adt_fecha."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
	}// end function uf_procesar_contabilizacion_solicitud_rd
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_detalles_contables_solicitud($as_codemp,$as_numsol,$as_descripcion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_detalles_contables_solicitud
		//		   Access: private
		//	    Arguments: as_codemp  // Código de Empresa
		//				   as_cod_pro  //  Código de Proveedor
		//				   as_ced_bene  // Cédula del Beneficiario
		//				   as_codtipdoc  // Código del tipo de documento
		//				   as_numrecdoc  // Número de recepción de documento
		//				   as_descripcion  // Descripción
		//				   ab_desagregar_iva  // si desagrega el iva
		//	      Returns: Retorna un bollean valido
		//	  Description: método que procesa los detalles de movimiento contables
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 10/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="SELECT debhab, sc_cuenta, monto, procede_doc, numdoccom ".
                "  FROM cxp_solicitudes_scg ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND numsol='".$as_numsol."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_procesar_detalles_contables ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			while($row=$this->io_sql->fetch_row($rs_data) and ($lb_valido))
			{
				$ls_scg_cuenta=$row["sc_cuenta"];
				$ls_descripcion=$as_descripcion;				
				$ls_documento=$row["numdoccom"];
				$ls_procede_doc=$row["procede_doc"];
				$ldec_monto=$row["monto"];
				$ls_debhab=$row["debhab"];
				$lb_valido=$this->io_sigesp_int->uf_scg_insert_datastore($as_codemp,$ls_scg_cuenta,$ls_debhab,$ldec_monto,
																		 $ls_documento,$ls_procede_doc,$ls_descripcion);				
				if($lb_valido===false)
				{
					$this->io_msg->message("".$this->io_sigesp_int->is_msg_error);
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	}// end function uf_procesar_detalles_contables_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_data_solicitudes($as_codemp,$as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_data_solicitudes
		//		   Access: private
		//	    Arguments: as_codemp  // Código de Empresa
		//				   as_numsol  // Número de Solicitud
		//	      Returns: Retorna datos de la solicitud compras mediante un datastrore publico
		//	  Description: Este metodo obtiene el registro de la orden de compra y lo guarada en un 
		//                  datastore publico para posteriores operaciones
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 10/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe=false;		
		$ls_sql="SELECT * ".
                "  FROM cxp_solicitudes ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND numsol='".$as_numsol."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_obtener_data_solicitudes ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true; // si existe se procedera a registrar en el datastore.				
                $this->dts_solicitud->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$this->io_msg->message("La Solicitud no existe en la base de datos.");
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}// end function uf_obtener_data_solicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_obtener_datos_beneficiario($as_codemp,$as_ced_bene)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_datos_beneficiario
		//		   Access: private
		//	    Arguments: as_codemp  // Código de Empresa
		//				   as_ced_bene  // Cédula del Beneficiario
		//	      Returns: Retorna un bollean valido
		//	  Description: método que obtiene los datos de un beneficiario específico
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 10/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="SELECT * ".
		        "  FROM rpc_beneficiario ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND ced_bene='".$as_ced_bene."'";				  
		$rs_data=$this->io_sql->select($ls_sql);		
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_obtener_datos_beneficiario ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe = true; // si existe se procedera a registrar en el datastore.				
				$this->dts_beneficiario->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$this->io_msg->message("El Beneficiario no existe en la base de datos.");
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}// end function uf_obtener_datos_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_obtener_datos_proveedor($as_codemp,$as_cod_pro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_datos_proveedores
		//		   Access: private
		//	    Arguments: as_codemp  // Código de Empresa
		//				   as_cod_pro  // Código de Proveedor
		//	      Returns: Retorna un bollean valido
		//	  Description: método que obtiene los datos de un proveedor específico
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 10/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe=true;		
		$ls_sql="SELECT * ".
		        "  FROM rpc_proveedor ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND cod_pro='".$as_cod_pro."'";				  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_obtener_datos_proveedor ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe = true; // si existe se procedera a registrar en el datastore.				
                $this->dts_proveedor->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$this->io_msg->message("El proveedor no existe en la base de datos.");
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}// end function uf_obtener_datos_proveedor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_contabilizacion_comprobante($as_codemp,$as_numsol,$ai_estdesiva,$as_scgcta_proben)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contabilizacion_comprobante
		//		   Access: private
		//	    Arguments: as_codemp  // Código de Empresa
		//				   as_numsol  // Número de solicitud
		//				   ai_estdesiva  // Código de Proveedor
		//				   as_scgcta_proben  // Cuenta del proveedor y beneficiario
		//	      Returns: Retorna datos de la solicitud compras mediante un datastrore publico
		//	  Description: Método que genera los asientos presupuestarios y contables a partir 
		//                  de los movmientos de solicitudes de pagos
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 10/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$lb_desagregar_iva=false;
		$ls_codemp=$as_codemp;
		if($ai_estdesiva==1)
		{
			$lb_desagregar_iva=true;
		}
		$ls_sql="SELECT dt.codemp,dt.numsol,dt.numrecdoc,dt.codtipdoc,dt.ced_bene,dt.cod_pro,dt.monto,td.estcon,td.estpre, ".
                "        s.tipproben,s.consol ".
                "  FROM cxp_solicitudes s, cxp_dt_solicitudes dt, cxp_documento td ".
                " WHERE s.codemp=dt.codemp ".
				"   AND s.numsol=dt.numsol ".
				"   AND dt.codtipdoc=td.codtipdoc ".
                "   AND dt.codemp='".$ls_codemp."' ".
				"   AND dt.numsol='".$as_numsol."' ".
				"   AND estprosol='E' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_procesar_contabilizacion_comprobante ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{           
			while($row=$this->io_sql->fetch_row($rs_data)and($lb_valido))
			{
				$ls_debhab="";
				$ls_cod_pro=$row["cod_pro"];
				$ls_ced_bene=$row["ced_bene"];
				$ls_tipo=$row["tipproben"];
				$ls_codtipdoc=$row["codtipdoc"];
				$li_mensaje_spg=$row["estpre"]; 
				$li_mensaje_scg=$row["estcon"]; 
				$ls_numrecdoc=$row["numrecdoc"];			  
				$ls_descripcion=$row["consol"];			  
				// Mensaje de operacion de gasto a ser aplicada
				if($li_mensaje_spg==1)
				{
					$ls_mensaje="C";
				}
				elseif($li_mensaje_spg==2)
				{
					$ls_mensaje="OC";
				}
				// Mensaje de operacion contable a ser aplicada			  
				if($li_mensaje_scg==1)
				{
					$ls_debhab="H";
				}
				if($li_mensaje_spg!=3)
				{
					$lb_valido=$this->uf_procesar_detalles_gastos($ls_codemp,$ls_cod_pro,$ls_ced_bene,$ls_codtipdoc,$ls_numrecdoc,
																  $ls_descripcion,$ls_mensaje,$lb_desagregar_iva);
					if(!$lb_valido) 
					{ 
						$lb_valido=false;
						return false;
					}
				}
				if($li_mensaje_scg!=2)
				{
					$lb_valido=$this->uf_procesar_detalles_contables($ls_codemp,$ls_cod_pro,$ls_ced_bene,$ls_codtipdoc,
																	 $ls_numrecdoc,$ls_descripcion,$lb_desagregar_iva);
					if(!$lb_valido)
					{ 
						$lb_valido=false;
						return false;
					}
				}
			}
			$this->io_sql->free_result($rs_data);
			if(!empty($this->dts_interd)&&!empty($this->dts_interh))
			{
				$this->dts_interd->group_by(array('0'=>'cuentagasto'),array('0'=>'monto'),'cuentagasto');
				$li_totrow=$this->dts_interd->getRowCount('cuentagasto');
				for($li_i=1;($li_i<=$li_totrow)&&$lb_valido;$li_i++)
				{
					$ls_cuenta=$this->dts_interd->getValue("cuentagasto",$li_i);
					$li_monto=$this->dts_interd->getValue("monto",$li_i);
					$ls_procededoc=$this->dts_interd->getValue("procededoc",$li_i);
					$ls_documento=$this->dts_interd->getValue("documento",$li_i);
					$lb_valido=$this->uf_insert_asiento_inter($ls_codemp,$this->as_procede,$this->as_comprobante,$this->ad_fecha,
															  $this->as_codban,$this->as_ctaban,$this->as_descripcion,
															  $ls_procededoc,$ls_documento,$ls_cuenta,"D",$li_monto);
				}
				if($lb_valido)
				{
					$this->dts_interh->group_by(array('0'=>'cuentainter'),array('0'=>'monto'),'cuentainter');
					$li_totrow=$this->dts_interh->getRowCount('cuentainter');
					for($li_i=1;($li_i<=$li_totrow)&&$lb_valido;$li_i++)
					{
						$ls_cuenta=$this->dts_interh->getValue("cuentainter",$li_i);
						$li_monto=$this->dts_interh->getValue("monto",$li_i);
						$ls_procededoc=$this->dts_interh->getValue("procededoc",$li_i);
						$ls_documento=$this->dts_interh->getValue("documento",$li_i);
						$lb_valido=$this->uf_insert_asiento_inter($ls_codemp,$this->as_procede,$this->as_comprobante,$this->ad_fecha,
																  $this->as_codban,$this->as_ctaban,$this->as_descripcion,
																  $ls_procededoc,$ls_documento,$ls_cuenta,"H",$li_monto);
					}
				}
			}
		}
		return $lb_valido;
    }// end function uf_procesar_contabilizacion_comprobante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_asiento_inter($as_codemp,$as_procede,$as_comprobante,$ad_fecha,$as_codban,$as_ctaban,$as_descripcion,
									 $as_procededoc,$as_documento,$as_cuenta,$as_debhab,$ai_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_asiento_inter
		//		   Access: private
		//	    Arguments: 
		//	      Returns: Retorna un bollean valido
		//	  Description: Metodo que inserta los asientos Intercompañia
		//	   Creado Por: Ing. Luis Anibal Lang
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_sql="INSERT INTO cxp_scg_inter (codemp, procede, comprobante, fecha, codban, ctaban, sc_cuenta, procede_doc, documento,".
				"                              debhab, descripcion, monto, orden)".
				" VALUES ('".$as_codemp."','".$as_procede."','".$as_comprobante."','".$ad_fecha."','".$as_codban."','".$as_ctaban."',".
				"         '".$as_cuenta."','".$as_procededoc."','".$as_documento."','".$as_debhab."','".$as_descripcion."',".
				"         ".$ai_monto.",1)";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ print "<br />".$this->io_sql->message;
			$this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_insert_asiento_inter ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

   	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_detalles_gastos($as_codemp,$as_cod_pro,$as_ced_bene,$as_codtipdoc,$as_numrecdoc,$as_descripcion,$as_mensaje,$ab_desagregar_iva)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_detalles_gastos
		//		   Access: private
		//	    Arguments: as_codemp  // Código de Empresa
		//				   as_cod_pro  //  Código de Proveedor
		//				   as_ced_bene  // Cédula del Beneficiario
		//				   as_codtipdoc  // Código del tipo de documento
		//				   as_numrecdoc  // Número de recepción de documento
		//				   as_mensaje  // Mensaje
		//				   ab_desagregar_iva  // si desagrega el iva
		//	      Returns: Retorna un bollean valido
		//	  Description: método que procesa los detalles de movimiento cargos
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 10/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="SELECT * ".
                "  FROM cxp_rd_spg ".
                " WHERE cxp_rd_spg.codemp='".$as_codemp."' ".
				"   AND cxp_rd_spg.cod_pro='".$as_cod_pro."' ".
				"   AND cxp_rd_spg.ced_bene='".$as_ced_bene."' ".
                "   AND cxp_rd_spg.codtipdoc='".$as_codtipdoc."' ".
				"   AND cxp_rd_spg.numrecdoc='".$as_numrecdoc."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_procesar_detalles_gastos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			while($row=$this->io_sql->fetch_row($rs_data)and($lb_valido))
			{
				$ls_codestpro=$row["codestpro"];
				$ls_codestpro1=substr($ls_codestpro,0,25);
				$ls_codestpro2=substr($ls_codestpro,25,25);
				$ls_codestpro3=substr($ls_codestpro,50,25);
				$ls_codestpro4=substr($ls_codestpro,75,25);
				$ls_codestpro5=substr($ls_codestpro,100,25);
				$ls_estcla=$row["estcla"];
				$ls_spg_cuenta=$row["spg_cuenta"];
				$ls_descripcion=$as_descripcion;				
				$ls_documento=$row["numdoccom"];
				$ls_numrecdoc=$row["numrecdoc"];
				$ls_procede_doc=$row["procede_doc"];
				$ldec_monto=$row["monto"];
				$ls_mensaje=$as_mensaje;
				$lb_ok=$this->uf_asiento_inter($as_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
											   $ls_codestpro5,$ls_estcla,$ls_spg_cuenta,$ldec_monto,$ls_documento,$ls_procede_doc);
				$lb_valido=$this->io_sigesp_int->uf_spg_insert_datastore($as_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
								  										 $ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,$ls_mensaje,
								  										 $ldec_monto,$ls_documento,$ls_procede_doc,$ls_descripcion);
				if ($lb_valido===false)
				{  
					$this->io_msg->message("".$this->io_sigesp_int->is_msg_error);
					break;
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	}// end function uf_procesar_detalles_gastos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_asiento_inter($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
							  $as_estcla,$as_spg_cuenta,$adec_monto,$as_documento,$as_procede_doc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_detalles_contables
		//		   Access: private
		//	    Arguments: as_codemp  // Código de Empresa
		//				   as_codestpro1  //  Código de estructura programatica nivel 1
		//				   as_codestpro2  //  Código de estructura programatica nivel 2
		//				   as_codestpro3  //  Código de estructura programatica nivel 3
		//				   as_codestpro4  //  Código de estructura programatica nivel 4
		//				   as_codestpro5  //  Código de estructura programatica nivel 5
		//				   as_estcla      // 
		//				   as_spg_cuenta  // Cuenta Presupuestaria de Gasto
		//				   adec_monto     // Monto
		//	      Returns: Retorna un bollean valido
		//	  Description: Metodo que recopila la informacion para el asiento intercompañia
		//	   Creado Por: Ing. Luis Anibal Lang
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_valido=$this->uf_load_cuenta_inter($as_codemp,$as_codestpro1,$as_estcla,&$ls_estint,&$ls_cuentaint);
		if(($lb_valido)&&($ls_estint==1)&&($ls_cuentaint!="")&&($this->ls_estempcon!=1)&&($this->ls_basdatcon!=""))
		{
			$lb_valido=$this->uf_load_cuenta_gasto($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
												   $as_codestpro5,$as_estcla,$as_spg_cuenta,&$ls_cuentagasto);
			if(($lb_valido)&&($ls_cuentagasto!=""))
			{
				$this->dts_interh->insertRow("cuentainter",$ls_cuentaint);
				$this->dts_interh->insertRow("monto",$adec_monto);
				$this->dts_interh->insertRow("documento",$as_documento);
				$this->dts_interh->insertRow("procededoc",$as_procede_doc);
				
				$this->dts_interd->insertRow("cuentagasto",$ls_cuentagasto);
				$this->dts_interd->insertRow("monto",$adec_monto);
				$this->dts_interd->insertRow("documento",$as_documento);
				$this->dts_interd->insertRow("procededoc",$as_procede_doc);
			}
		}
		return $lb_valido;	
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cuenta_gasto($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
							 	  $as_estcla,$as_spg_cuenta,&$as_cuentagasto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_detalles_contables
		//		   Access: private
		//	    Arguments: as_codemp  // Código de Empresa
		//				   as_codestpro1  //  Código de estructura programatica nivel 1
		//				   as_estcla      // 
		//				   as_estint      // Estatus de Intercompañia
		//				   as_cuentaint   // Cuenta Intercompañia
		//	      Returns: Retorna un bollean valido
		//	  Description: Metodo que recopila la informacion para el asiento intercompañia
		//	   Creado Por: Ing. Luis Anibal Lang
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$as_cuentaint="";
		$ls_sql="SELECT sc_cuenta".
				"  FROM spg_cuentas".
				" WHERE codemp='".$as_codemp."'".
				"   AND trim(codestpro1)='".trim($as_codestpro1)."'".
				"   AND trim(codestpro2)='".trim($as_codestpro2)."'".
				"   AND trim(codestpro3)='".trim($as_codestpro3)."'".
				"   AND trim(codestpro4)='".trim($as_codestpro4)."'".
				"   AND trim(codestpro5)='".trim($as_codestpro5)."'".
				"   AND trim(spg_cuenta)='".trim($as_spg_cuenta)."'".
				"   AND estcla='".$as_estcla."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_load_cuenta_gasto ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_cuentagasto=$row["sc_cuenta"];
				$lb_valido=true;
			}
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cuenta_inter($as_codemp,$as_codestpro1,$as_estcla,&$as_estint,&$as_cuentaint)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_detalles_contables
		//		   Access: private
		//	    Arguments: as_codemp  // Código de Empresa
		//				   as_codestpro1  //  Código de estructura programatica nivel 1
		//				   as_estcla      // 
		//				   as_estint      // Estatus de Intercompañia
		//				   as_cuentaint   // Cuenta Intercompañia
		//	      Returns: Retorna un bollean valido
		//	  Description: Metodo que recopila la informacion para el asiento intercompañia
		//	   Creado Por: Ing. Luis Anibal Lang
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$as_cuentaint="";
		$ls_sql="SELECT estint, sc_cuenta".
				"  FROM spg_ep1".
				" WHERE codemp='".$as_codemp."'".
				"   AND codestpro1='".$as_codestpro1."'".
				"   AND estcla='".$as_estcla."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_load_cuenta_inter ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_estint=$row["estint"];
				$as_cuentaint=$row["sc_cuenta"];
				$lb_valido=true;
			}
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_detalles_contables($as_codemp,$as_cod_pro,$as_ced_bene,$as_codtipdoc,$as_numrecdoc,$as_descripcion,$ab_desagregar_iva)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_detalles_contables
		//		   Access: private
		//	    Arguments: as_codemp  // Código de Empresa
		//				   as_cod_pro  //  Código de Proveedor
		//				   as_ced_bene  // Cédula del Beneficiario
		//				   as_codtipdoc  // Código del tipo de documento
		//				   as_numrecdoc  // Número de recepción de documento
		//				   as_descripcion  // Descripción
		//				   ab_desagregar_iva  // si desagrega el iva
		//	      Returns: Retorna un bollean valido
		//	  Description: método que procesa los detalles de movimiento contables
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 10/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="SELECT * ".
                "  FROM cxp_rd_scg ".
                " WHERE cxp_rd_scg.codemp='".$as_codemp."' ".
				"   AND cxp_rd_scg.cod_pro='".$as_cod_pro."' ".
				"   AND cxp_rd_scg.ced_bene='".$as_ced_bene."' ".
                "   AND cxp_rd_scg.codtipdoc='".$as_codtipdoc."' ".
				"   AND cxp_rd_scg.numrecdoc='".$as_numrecdoc."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_procesar_detalles_contables ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			while($row=$this->io_sql->fetch_row($rs_data) and ($lb_valido))
			{
				$ls_scg_cuenta=$row["sc_cuenta"];
				$ls_descripcion=$as_descripcion;				
				$ls_documento=$row["numdoccom"];
				$ls_numrecdoc=$row["numrecdoc"];
				$ls_procede_doc=$row["procede_doc"];
				$ldec_monto=$row["monto"];
				$ls_debhab=$row["debhab"];
				$lb_valido=$this->io_sigesp_int->uf_scg_insert_datastore($as_codemp,$ls_scg_cuenta,$ls_debhab,$ldec_monto,
																		 $ls_documento,$ls_procede_doc,$ls_descripcion);				
				if($lb_valido===false)
				{
					$this->io_msg->message("".$this->io_sigesp_int->is_msg_error);
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	}// end function uf_procesar_detalles_contables
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_contabilizado_cxp($as_codemp,$as_numsol,$as_estsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_contabilizado_cxp
		//		   Access: private
		//	    Arguments: as_codemp // Código de Empresa
		//                 as_numsol // numero de la solicitud  
		//                 as_estsol // estatus de la solicitud
		//	      Returns: Retorna un bollean valido
		//	  Description: método que obtiene los datos de un proveedor específico
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 10/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_sql="UPDATE cxp_solicitudes ".
		        "   SET estprosol='".$as_estsol."' ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND numsol='".$as_numsol."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_update_estatus_contabilizado_cxp ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		return $lb_valido;
	}// end function uf_update_estatus_contabilizado_cxp
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_contabilizado_recepcion($as_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,$as_estrecdoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_contabilizado_recepcion
		//		   Access: private
		//	    Arguments: as_codemp // Código de Empresa
		//                 as_numrecdoc // numero de la Recepción de Documentos  
		//                 as_codtipdoc // tipo de documento
		//                 as_codpro // Código de Proveedor  
		//                 as_cedbene // Cédula del Beneficiario 
		//                 as_estrecdoc // estatus de la solicitud
		//	      Returns: Retorna un bollean valido
		//	  Description: método que obtiene los datos de un proveedor específico
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 10/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_sql="UPDATE cxp_rd ".
				"   SET estprodoc='".$as_estrecdoc."' ".
				" WHERE codemp='".$as_codemp."' ".
				"   AND cod_pro='".$as_codpro."' ".
				"   AND ced_bene='".$as_cedbene."' ".
				"   AND codtipdoc='".$as_codtipdoc."' ".
				"   AND numrecdoc='".$as_numrecdoc."'";				  
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_update_estatus_contabilizado_recepcion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		return $lb_valido;
	}// end function uf_update_estatus_contabilizado_recepcion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_detalles_solicitud($as_codemp,$as_numsol,$as_status,$ab_validar)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_detalles_solicitud
		//		   Access: private
		//	    Arguments: as_codemp // Código de Empresa
		//                 as_numsol // numero de la solicitud  
		//                 as_status // estatus de la solicitud
		//                 ab_validar // estatus de validar el estatus de la solicitud
		//	      Returns: Retorna un bollean valido
		//	  Description: Método que localiza las recepciones de documentos asociadas a la solicitud 
		//                  y actualiza el estatus de las recepciones de documento.
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 15/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="SELECT cxp_dt_solicitudes.numrecdoc, cxp_dt_solicitudes.codtipdoc, cxp_dt_solicitudes.ced_bene, ".
				"		cxp_dt_solicitudes.cod_pro, cxp_rd.estprodoc ".
		        "  FROM cxp_dt_solicitudes, cxp_rd ".
                " WHERE cxp_dt_solicitudes.codemp='".$as_codemp."' ".
				"   AND cxp_dt_solicitudes.numsol='".$as_numsol."' ".
				"   AND cxp_dt_solicitudes.codemp = cxp_rd.codemp ".
				"   AND cxp_dt_solicitudes.numrecdoc = cxp_rd.numrecdoc ".
				"   AND cxp_dt_solicitudes.codtipdoc = cxp_rd.codtipdoc ".
				"   AND cxp_dt_solicitudes.ced_bene = cxp_rd.ced_bene ".
				"   AND cxp_dt_solicitudes.cod_pro = cxp_rd.cod_pro ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_update_estatus_detalles_solicitud ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{  
			while($row=$this->io_sql->fetch_row($rs_data) and ($lb_valido))
		    {
				$ls_numrecdoc=$row["numrecdoc"];
				$ls_estprodoc=$row["estprodoc"];
				if((($ls_estprodoc=="R")&&($as_status=="C"))||(!$ab_validar))
				{
					$ls_codtipdoc=$row["codtipdoc"];
					$ls_ced_bene=$row["ced_bene"];
					$ls_cod_pro=$row["cod_pro"];
					$ls_sql="UPDATE cxp_rd ".
							"   SET estprodoc='".$as_status."' ".
							" WHERE codemp='".$as_codemp."' ".
							"   AND cod_pro='".$ls_cod_pro."' ".
							"   AND ced_bene='".$ls_ced_bene."' ".
							"   AND codtipdoc='".$ls_codtipdoc."' ".
							"   AND numrecdoc='".$ls_numrecdoc."'";				  
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_update_estatus_detalles_solicitud ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
						return false;
					}
				}
				else
				{
					$this->io_msg->message("ERROR-> La Recepción de Documentos ".$ls_numrecdoc." Asociada a la solicitud de Pago ".$as_numsol." Esta Tomada por otra solicitud ó fué anulada, El Reverso de Anulación no se puede realizar.");			
					return false;
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	}// end function uf_update_estatus_detalles_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_historico_contabilizacion($as_codemp,$as_numsol,$adt_fecha,$as_status)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_historico_contabilizacion
		//		   Access: private
		//	    Arguments: as_codemp // Código de Empresa
		//                 as_numsol // numero de la solicitud  
		//                 adt_fecha // fecha de la solicitud
		//                 as_status // estatus de la solicitud
		//	      Returns: Retorna un bollean valido
		//	  Description: Método que inserta el registro historico de su contabilización
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 15/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido =true;
		if(!$this->uf_select_historico_contabilizacion($as_codemp,$as_numsol,$adt_fecha,$as_status))
		{
	        $ls_sql="INSERT INTO cxp_historico_solicitud (codemp,numsol,fecha,estprodoc) VALUES ".
					"('".$as_codemp."','".$as_numsol."','".$adt_fecha."','".$as_status."')";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
					$this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_insert_historico_contabilizacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					return false;
			}
		}
		return $lb_valido;
	}// end function uf_insert_historico_contabilizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_historico_contabilizacion($as_codemp,$as_numsol,$adt_fecha,$as_status)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_historico_contabilizacion
		//		   Access: private
		//	    Arguments: as_codemp // Código de Empresa
		//                 as_numsol // numero de la solicitud  
		//                 adt_fecha // fecha de la solicitud
		//                 as_status // estatus de la solicitud
		//	      Returns: Retorna un bollean valido
		//	  Description: Método que inserta el registro historico de su contabilización
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 15/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe=false;		
		$ls_sql="SELECT * ".
                "  FROM cxp_historico_solicitud ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND numsol='".$as_numsol."' ".
				"   AND fecha='".$adt_fecha."' ".
				"   AND estprodoc='".$as_status."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_select_historico_contabilizacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}// end function uf_select_historico_contabilizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_historico_contabilizacion_rd($as_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,$adt_fecha,$as_estrecdoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_historico_contabilizacion_rd
		//		   Access: private
		//	    Arguments: as_codemp // Código de Empresa
		//                 as_numrecdoc // numero de la Recepción de Documentos  
		//                 as_codtipdoc // tipo de documento
		//                 as_codpro // Código de Proveedor  
		//                 as_cedbene // Cédula del Beneficiario 
		//                 as_estrecdoc // estatus de la solicitud
		//	      Returns: Retorna un bollean valido
		//	  Description: Método que inserta el registro historico de su contabilización
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 														Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido =true;
		if(!$this->uf_select_historico_contabilizacion_rd($as_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,$adt_fecha,$as_estrecdoc))
		{
	        $ls_sql="INSERT INTO cxp_historico_rd (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, fecha, estprodoc) VALUES ".
					"('".$as_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_cedbene."','".$as_codpro."','".$adt_fecha."','".$as_estrecdoc."')";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
					$this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_insert_historico_contabilizacion_rd ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					return false;
			}
		}
		return $lb_valido;
	}// end function uf_insert_historico_contabilizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_historico_contabilizacion_rd($as_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,$adt_fecha,$as_estrecdoc)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_historico_contabilizacion
		//		   Access: private
		//	    Arguments: as_codemp // Código de Empresa
		//                 as_numrecdoc // numero de la Recepción de Documentos  
		//                 as_codtipdoc // tipo de documento
		//                 as_codpro // Código de Proveedor  
		//                 as_cedbene // Cédula del Beneficiario 
		//                 as_estrecdoc // estatus de la solicitud
		//	      Returns: Retorna un bollean valido
		//	  Description: Método que inserta el registro historico de su contabilización
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 															Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe=false;		
		$ls_sql="SELECT * ".
                "  FROM cxp_historico_rd ".
				" WHERE codemp='".$as_codemp."' ".
				"   AND cod_pro='".$as_codpro."' ".
				"   AND ced_bene='".$as_cedbene."' ".
				"   AND codtipdoc='".$as_codtipdoc."' ".
				"   AND numrecdoc='".$as_numrecdoc."'".				  
				"   AND fecha='".$adt_fecha."' ".
				"   AND estprodoc='".$as_estrecdoc."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_select_historico_contabilizacion_rd ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}// end function uf_select_historico_contabilizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reversar_contabilizacion_solicitud_cxp($as_numsol,$ad_fechaconta,$aa_seguridad)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar_contabilizacion_solicitud_cxp
		//		   Access: public (sigesp_mis_p_reverso_cxp.php)
		//	    Arguments: as_numsol // numero de la solicitud  
		//                 aa_seguridad // Arreglo de las variables de seguridad
		//	      Returns: Retorna un bollean valido
		//	  Description: Método que reversa la contabilización de una cuenta por pagar
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 15/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	    $adt_fecha=$ad_fechaconta;
		$lb_valido=false;
        $ls_codemp=$this->io_codemp;
        $ls_procede="CXPSOP"; 
        $ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante(trim($as_numsol));
        $this->dts_solicitud->resetds("numsol"); // inicializa el datastore solicitud en 0 registro.
		if(!$this->uf_obtener_data_solicitudes($ls_codemp,$as_numsol))
		{
			$this->io_msg->message(" No existe la Orden de Págo N° ".$as_numsol);
			return false;
		}		
        $ls_tipo_destino=$this->dts_solicitud->getValue("tipproben",1);
		$ls_ced_bene=$this->dts_solicitud->getValue("ced_bene",1); 
		$ls_cod_pro=$this->dts_solicitud->getValue("cod_pro",1);	
		$ls_codban="---";
		$ls_ctaban="-------------------------";
	    $lb_valido = $this->io_sigesp_int->uf_obtener_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$adt_fecha,
																  $ls_codban,$ls_ctaban,$ls_tipo_destino,$ls_ced_bene,
																  $ls_cod_pro);
		if(!$lb_valido) 
		{ 
			$this->io_msg->message("ERROR-> No existe el comprobante Nº ".$ls_comprobante."-".$ls_procede.".");
			return false ;
		}
		$lb_check_close=false;
		$lb_valido = $this->io_sigesp_int->uf_init_delete($ls_codemp,$ls_procede,$ls_comprobante,$adt_fecha,$ls_tipo_destino,
														  $ls_ced_bene,$ls_cod_pro,$lb_check_close,$ls_codban,$ls_ctaban);
		if(!$lb_valido)	
		{ 
			$this->io_msg->message("".$this->io_sigesp_int->is_msg_error);
			return false; 
		}
		//Inicio la transacción SQL.
        $this->io_sigesp_int->uf_int_init_transaction_begin();
		if($lb_valido)
		{
			$lb_valido=$this->uf_liberar_inter($ls_codemp,$ls_procede,$ls_comprobante,$adt_fecha,$ls_codban,$ls_ctaban);
		}		
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_estatus_contabilizado_cxp($ls_codemp,$as_numsol,'E');
		}
    	if(!$lb_valido)
		{
			$this->io_msg->message("Error al cambiar estatus Orden de Págo Nº ".$as_numsol);
		}
	    if($lb_valido)
		{
			$lb_valido=$this->uf_delete_historico_contabilizacion($ls_codemp,$as_numsol,$adt_fecha,"C");
		}
	    if(($lb_valido)&&($_SESSION["la_empresa"]["conrecdoc"]=="0"))
		{
			$lb_valido=$this->uf_update_estatus_detalles_solicitud($ls_codemp,$as_numsol,"E",false);
		}
        if($lb_valido)
		{
		   	$lb_valido=$this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
		   	if(!$lb_valido)
		   	{
		   		$this->io_msg->message("".$this->io_sigesp_int->is_msg_error);
			}
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_cxp($ls_codemp,$as_numsol,'1900-01-01','1900-01-01');
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reverso la Solicitud de Pago <b>".$as_numsol."</b>, Fecha de Emisión <b>".$adt_fecha."</b> ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
	}// end function uf_reversar_contabilizacion_solicitud_cxp
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_liberar_inter($as_codemp,$as_procede,$as_comprobante,$adt_fecha,$as_codban,$as_ctaban)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_liberar_inter
		//		   Access: public 
		//	    Arguments: 
		//	      Returns: Retorna un bolean valido
		//	  Description: Método que verifica si existen asientos intercompañia y los elimina
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: 												Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe=$this->uf_verificar_inter($as_codemp,$as_procede,$as_comprobante,$adt_fecha,$as_codban,$as_ctaban,$ls_esttrfcmp);
		if($lb_existe)
		{
			if($ls_esttrfcmp!=1)
			{
				$lb_valido=$this->uf_delete_inter($as_codemp,$as_procede,$as_comprobante,$adt_fecha,$as_codban,$as_ctaban);
			}
			else
			{
				$this->io_msg->message("EL traspaso del comprobante ya ha sido realizado ");
				return false;
			}
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_inter($as_codemp,$as_procede,$as_comprobante,$adt_fecha,$as_codban,$as_ctaban,&$as_esttrfcmp)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_inter
		//		   Access: public 
		//	    Arguments: 
		//	      Returns: Retorna un bolean valido
		//	  Description: Método que verifica si existen asientos intercompañia y los elimina
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: 												Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_sql="SELECT cxp_scg_inter.comprobante,sigesp_cmp.esttrfcmp".
				"  FROM sigesp_cmp, cxp_scg_inter".
				" WHERE cxp_scg_inter.codemp='".$as_codemp."'".
				"   AND cxp_scg_inter.procede='".$as_procede."'".
				"   AND cxp_scg_inter.comprobante='".$as_comprobante."'".
				"   AND cxp_scg_inter.fecha='".$adt_fecha."'".
				"   AND cxp_scg_inter.codban='".$as_codban."'".
				"   AND cxp_scg_inter.ctaban='".$as_ctaban."'".
				"   AND cxp_scg_inter.codemp=sigesp_cmp.codemp".
				"   AND cxp_scg_inter.procede=sigesp_cmp.procede".
				"   AND cxp_scg_inter.comprobante=sigesp_cmp.comprobante".
				"   AND cxp_scg_inter.fecha=sigesp_cmp.fecha".
				"   AND cxp_scg_inter.codban=sigesp_cmp.codban".
				"   AND cxp_scg_inter.ctaban=sigesp_cmp.ctaban";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_verificar_inter ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe = true;
				$as_esttrfcmp=$row["esttrfcmp"];
			}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_existe;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_inter($as_codemp,$as_procede,$as_comprobante,$adt_fecha,$as_codban,$as_ctaban)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_inter
		//		   Access: public
		//	    Arguments: as_codemp // Código de empresa
		//                 as_numsol // numero de la solicitud  
		//                 adt_fecha // Fecha de la solicitud
		//	      Returns: Retorna un bolean valido
		//	  Description: Método que elimina el registro del asiento intercompañia
		//	   Creado Por: Ing. Luis Anibal Lang
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM cxp_scg_inter ".
				" WHERE codemp='".$as_codemp."' ".
				"   AND procede='".$as_procede."' ".
				"   AND comprobante='".$as_comprobante."' ".
				"   AND fecha='".$adt_fecha."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_delete_inter ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_delete_historico_contabilizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_reverso_recepcion($as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,$ad_fechaconta,$aa_seguridad)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_reverso_recepcion
		//		   Access: public (sigesp_mis_p_reverso_rd.php)
		//	    Arguments: as_numrecdoc  // Número de Recepción de Documentos
		//				   as_codtipdoc  // Código de tipos de Documentos
		//				   as_codpro     // Código de Proveedor
		//				   as_cedbene    // Cédula de Beneficiario
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: Retorna un bollean valido
		//	  Description: Método que reversa la contabilización de una cuenta por pagar
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 												Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	    $adt_fecha=$ad_fechaconta;
		$lb_valido=false;
        $ls_codemp=$this->io_codemp;
   	    $adt_fecha=$ad_fechaconta;
		$lb_valido=$this->uf_obtener_data_rd($ls_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene);		
		if(!$lb_valido)
		{
			$this->io_msg->message(" No existe la Recepcion de Documentos ".$as_numrecdoc);
			return false;
		}
        $ls_procede="CXPRCD"; 
		$ls_codrecdoc=$this->dts_rd->getValue("codrecdoc",1);
		$ls_codpro=$this->dts_rd->getValue("cod_pro",1);
		$ls_cedbene=$this->dts_rd->getValue("ced_bene",1);
		$ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante(trim($ls_codrecdoc));
		switch($ls_codpro)
		{
			case "----------":
				$ls_tipo_destino="B";
				break;
			default:
				$ls_tipo_destino="P";
				break;
		}
		$ls_codban="---";
		$ls_ctaban="-------------------------";
	    $lb_valido = $this->io_sigesp_int->uf_obtener_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$adt_fecha,
																  $ls_codban,$ls_ctaban,$ls_tipo_destino,$ls_cedbene,
																  $ls_codpro);
		if(!$lb_valido) 
		{ 
			$this->io_msg->message("ERROR-> No existe el comprobante Nº ".$ls_comprobante."-".$ls_procede.".");
			return false ;
		}
		$lb_check_close=false;
		$lb_valido = $this->io_sigesp_int->uf_init_delete($ls_codemp,$ls_procede,$ls_comprobante,$adt_fecha,$ls_tipo_destino,
														  $ls_cedbene,$ls_codpro,$lb_check_close,$ls_codban,$ls_ctaban);
		if(!$lb_valido)	
		{ 
			$this->io_msg->message("".$this->io_sigesp_int->is_msg_error);
			return false; 
		}
		//Inicio la transacción SQL.
        $this->io_sigesp_int->uf_int_init_transaction_begin();
		$lb_valido=$this->uf_update_estatus_contabilizado_recepcion($ls_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,"R");;
    	if(!$lb_valido)
		{
			$this->io_msg->message("Error al cambiar estatus Orden de Págo Nº ".$as_numsol);
		}
	    if($lb_valido)
		{
			$lb_valido=$this->uf_delete_historico_rd($ls_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,"C");
		}
        if($lb_valido)
		{
		   	$lb_valido=$this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
		   	if(!$lb_valido)
		   	{
		   		$this->io_msg->message("".$this->io_sigesp_int->is_msg_error);
			}
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_rd($ls_codemp,$as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,'1900-01-01','1900-01-01');
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reverso la Recepción de Documentos <b>".$as_numrecdoc."</b> - <b>".$as_codtipdoc."</b> - <b>".$as_cedbene."</b> - <b>".$as_codpro."</b> ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
	}// end function uf_procesar_reverso_recepcion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_historico_contabilizacion($as_codemp,$as_numsol,$adt_fecha,$as_estatus)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_historico_contabilizacion
		//		   Access: public (sigesp_mis_p_reverso_cxp.php)
		//	    Arguments: as_codemp // Código de empresa
		//                 as_numsol // numero de la solicitud  
		//                 adt_fecha // Fecha de la solicitud
		//	      Returns: Retorna un bollean valido
		//	  Description: Método que elimina el registro historico de su contabilización
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 15/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM cxp_historico_solicitud ".
				"  WHERE codemp='".$as_codemp."' ".
				"    AND numsol='".$as_numsol."' ".
				"    AND estprodoc='".$as_estatus."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_delete_historico_contabilizacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_delete_historico_contabilizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_historico_rd($as_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,$as_estatus)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_historico_rd
		//		   Access: public (sigesp_mis_p_reverso_cxp.php)
		//	    Arguments: as_codemp // Código de Empresa
		//                 as_numrecdoc // numero de la Recepción de Documentos  
		//                 as_codtipdoc // tipo de documento
		//                 as_codpro // Código de Proveedor  
		//                 as_cedbene // Cédula del Beneficiario 
		//                 as_estrecdoc // estatus de la solicitud
		//	      Returns: Retorna un bollean valido
		//	  Description: Método que elimina el registro historico de su contabilización
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 15/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM cxp_historico_rd ".
				" WHERE codemp='".$as_codemp."' ".
				"   AND cod_pro='".$as_codpro."' ".
				"   AND ced_bene='".$as_cedbene."' ".
				"   AND codtipdoc='".$as_codtipdoc."' ".
				"   AND numrecdoc='".$as_numrecdoc."'".				  
				"   AND estprodoc='".$as_estatus."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_delete_historico_contabilizacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_delete_historico_rd
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_anular_solicitud($as_numsol,$adt_fecha,$ad_fechaconta,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_anular_solicitud
		//		   Access: public (sigesp_mis_p_anula_cxp.php)
		//	    Arguments: as_numsol // numero de la solicitud  
		//                 adt_fecha // Fecha de Anulación
		//                 ad_fechaconta // Fecha en que fue contabilizado el comprobante
		//                 aa_seguridad // Arreglo de Seguridad
		//	      Returns: Retorna un bollean valido
		//	  Description: Método que anula la solicitud de pago ya contabilizada
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 15/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_existe=false;
		$ls_codemp=$this->io_codemp;
		$lb_valido=$this->uf_obtener_data_solicitudes($ls_codemp,$as_numsol);		
		if(!$lb_valido)
		{
			return false;
		}
		$ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante(trim($as_numsol));		
        $ls_procede="CXPSOP"; 
		$ls_procede_anula="CXPAOP";
		$ldt_fecemisol=$this->dts_solicitud->getValue("fecemisol",1);
		$ls_descripcion=$this->dts_solicitud->getValue("consol",1);
		$ldt_fecemisol=$this->io_function->uf_convertirdatetobd($ldt_fecemisol);
		$ldt_fecha_anula=$this->io_function->uf_convertirdatetobd($adt_fecha);
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		$li_tipo_comp=1; // comprobante Normal
		$this->as_procede=$ls_procede_anula;
		$this->as_comprobante=$ls_comprobante;
		$this->ad_fecha=$ldt_fecha_anula;
		$this->as_codban=$ls_codban;
		$this->as_ctaban=$ls_ctaban;
		$this->as_descripcion=$ls_descripcion;
		$lb_valido=$this->io_sigesp_int->uf_int_anular($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecemisol,
													   $ls_procede_anula,$ldt_fecha_anula,$ls_descripcion,$ls_codban,
													   $ls_ctaban,$li_tipo_comp);
		if(!$lb_valido)	
		{ 
			$this->io_msg->message($this->io_sigesp_int->int_fecha->is_msg_error);
           //$this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_procesar_anular_solicitud ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		   return false; 
		}
		// inicia transacción SQL
		$this->io_sigesp_int->uf_int_init_transaction_begin();
		$lb_existe=$this->uf_verificar_inter($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecemisol,$ls_codban,$ls_ctaban,&$as_esttrfcmp);
		if($lb_existe)
		{
			if($as_esttrfcmp==1)
			{
				$this->io_msg->message("EL traspaso del comprobante ya ha sido realizado ");
				$lb_valido=false;
			}
		}
		if($lb_valido)
		{
		    $lb_valido=$this->uf_update_estatus_contabilizado_cxp($ls_codemp,$as_numsol,'A');
		}
		if(($lb_valido)&&($_SESSION["la_empresa"]["conrecdoc"]=="0"))
		{
			$lb_valido=$this->uf_update_estatus_detalles_solicitud($ls_codemp,$as_numsol,'R',false);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_historico_contabilizacion($ls_codemp,$as_numsol,$ldt_fecha_anula,"A");
		}
		if ($lb_valido)
		{
			$lb_valido=$this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
			if($lb_valido===false)
			{
				$this->io_msg->message("".$this->io_sigesp_int->is_msg_error);
			}
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_cxp($ls_codemp,$as_numsol,$ldt_fecemisol,$ldt_fecha_anula);
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Anulo la Solicitud de Pago <b>".$as_numsol."</b>, ".
							"Fecha de Anulación <b>".$adt_fecha."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/// PARA LA CONVERSIÓN MONETARIA
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/*if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_sigespcmp($this->as_procede,$this->as_comprobante,$this->ad_fecha,
																 $this->as_codban,$this->as_ctaban,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_spgdtcmp($this->as_procede,$this->as_comprobante,$this->ad_fecha,
																$this->as_codban,$this->as_ctaban,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_scgdtcmp($this->as_procede,$this->as_comprobante,$this->ad_fecha,
																$this->as_codban,$this->as_ctaban,$aa_seguridad);
		}*/
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
	}// end function uf_procesar_anular_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_anula_recepcion($as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,$adt_fecha,$ad_fechaconta,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_anula_recepcion
		//		   Access: public (sigesp_mis_p_anula_cxp.php)
		//	    Arguments: as_numrecdoc  // Número de Recepción de Documentos
		//				   as_codtipdoc  // Código de tipos de Documentos
		//				   as_codpro     // Código de Proveedor
		//				   as_cedbene    // Cédula de Beneficiario
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: Retorna un bollean valido
		//	  Description: Método que anula la solicitud de pago ya contabilizada
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 													Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_existe=false;
		$ls_codemp=$this->io_codemp;
		$lb_valido=$this->uf_obtener_data_rd($ls_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene);		
		if(!$lb_valido)
		{
			return false;
		}
        $ls_procede="CXPRCD"; 
		$ls_codrecdoc=$this->dts_rd->getValue("codrecdoc",1);
		$ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante(trim($ls_codrecdoc));		
		$ls_procede_anula="CXPARD";
		$ls_descripcion=$this->dts_rd->getValue("dencondoc",1);
		$ldt_fecha_anula=$this->io_function->uf_convertirdatetobd($adt_fecha);
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		$li_tipo_comp=1; // comprobante Normal
		$this->as_procede=$ls_procede_anula;
		$this->as_comprobante=$ls_comprobante;
		$this->ad_fecha=$ldt_fecha_anula;
		$this->as_codban=$ls_codban;
		$this->as_ctaban=$ls_ctaban;
		$this->as_descripcion=$ls_descripcion;
		if(!$this->io_fecha->uf_comparar_fecha($ad_fechaconta,$ldt_fecha_anula))
		{
			$this->io_msg->message(" La Fecha de Anulación debe ser menor que la fecha de Contabilización de la Recepción ".$as_numrecdoc);
			return false;
		}
		$lb_valido=$this->io_sigesp_int->uf_int_anular($ls_codemp,$ls_procede,$ls_comprobante,$ad_fechaconta,
													   $ls_procede_anula,$ldt_fecha_anula,$ls_descripcion,$ls_codban,
													   $ls_ctaban,$li_tipo_comp);
		if(!$lb_valido)	
		{ 
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_procesar_anula_recepcion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		   return false; 
		}
		// inicia transacción SQL
		$this->io_sigesp_int->uf_int_init_transaction_begin(); 
	    $lb_valido=$this->uf_update_estatus_contabilizado_recepcion($ls_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,"A");
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_historico_contabilizacion_rd($ls_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,$ldt_fecha_anula,"A");
		}
		if ($lb_valido)
		{
			$lb_valido=$this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
			if($lb_valido===false)
			{
				$this->io_msg->message("".$this->io_sigesp_int->is_msg_error);
			}
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_rd($ls_codemp,$as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$ad_fechaconta,$ldt_fecha_anula);
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Contabilizó la Recepcion de Documentos <b>".$as_numrecdoc."</b> - <b>".$as_codtipdoc."</b> - <b>".$as_cedbene."</b> - <b>".$as_codpro."</b>, ".
							"Fecha de Anulación <b>".$ldt_fecha_anula."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
	}// end function uf_procesar_anula_recepcion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_reversar_anular_solicitud($as_numsol,$ad_fechaanula,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_reversar_anular_solicitud
		//		   Access: public (sigesp_mis_p_reverso_anula_cxp.php)
		//	    Arguments: as_numsol // numero de la solicitud  
		//                 ad_fechaanula // Fecha en que fue anulada una solicitud de pago
		//                 aa_seguridad // Arreglo de Seguridad
		//	      Returns: Retorna un bollean valido
		//	  Description: Método que reversa la anulación de una solicitud de pago
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 15/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->io_codemp;
		$lb_valido=$this->uf_obtener_data_solicitudes($ls_codemp,$as_numsol);		
		if(!$lb_valido)
		{
			return false;
		}
		$ls_comprobante=$as_numsol;		
		$ls_procede="CXPAOP";
		$ls_tipo_destino="";
		$ls_ced_bene="";
		$ls_cod_pro="";
		$ldt_fecha=$ad_fechaanula;
		$ls_codban="---";
		$ls_ctaban="-------------------------";
	    $lb_valido=$this->io_sigesp_int->uf_obtener_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_codban,
																$ls_ctaban,$ls_tipo_destino,&$ls_ced_bene,&$ls_cod_pro);
		if($lb_valido===false) 
		{ 
			$this->io_msg->message("ERROR-> No existe el comprobante Nº ".$ls_comprobante."-".$ls_procede.".");
			return false;
		}
        $lb_check_close=false;
		$lb_valido=$this->io_sigesp_int->uf_init_delete($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_tipo_destino,
														$ls_ced_bene,$ls_cod_pro,$lb_check_close,$ls_codban,$ls_ctaban);
		if($lb_valido===false)	
		{ 
			$this->io_msg->message("Error en método uf_init_delete ".$this->io_sigesp_int->is_msg_error);
			return false; 
		}
		// Inicio de la Transacción
        $this->io_sigesp_int->uf_int_init_transaction_begin();
	    $lb_valido=$this->uf_update_estatus_contabilizado_cxp($ls_codemp,$as_numsol,'C');
		if(($lb_valido)&&($_SESSION["la_empresa"]["conrecdoc"]=="0"))
		{
			$lb_valido=$this->uf_update_estatus_detalles_solicitud($ls_codemp,$as_numsol,"C",true);
		}
	    if($lb_valido)
		{
			$lb_valido=$this->uf_delete_historico_contabilizacion($ls_codemp,$as_numsol,$ldt_fecha,"A");
		}
		/*if($lb_valido)
		{
			$lb_valido=$this->uf_insert_historico_contabilizacion($ls_codemp,$as_numsol,$ldt_fecha,"C");
		}*/
	    if($lb_valido)
	    {
	        $lb_valido=$this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
	        if($lb_valido===false)
			{
				$this->io_msg->message("".$this->io_sigesp_int->is_msg_error);
			}
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_cxp($ls_codemp,$as_numsol,'','1900-01-01');
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reverso la Anulación la Solicitud de Pago <b>".$as_numsol."</b>, ".
							"Fecha de Anulación <b>".$ldt_fecha."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
    }// end function uf_procesar_reversar_anular_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_reversar_anular_recepcion($as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,$ad_fechaanula,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_reversar_anular_recepcion
		//		   Access: public (sigesp_mis_p_reverso_anula_cxp.php)
		//	    Arguments: as_numrecdoc  // Número de Recepción de Documentos
		//				   as_codtipdoc  // Código de tipos de Documentos
		//				   as_codpro     // Código de Proveedor
		//				   as_cedbene    // Cédula de Beneficiario
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: Retorna un bollean valido
		//	  Description: Método que reversa la anulación de una solicitud de pago
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 													Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->io_codemp;
		$lb_valido=$this->uf_obtener_data_rd($ls_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene);		
		if(!$lb_valido)
		{
			return false;
		}
		$ls_codrecdoc=$this->dts_rd->getValue("codrecdoc",1);
		$ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante(trim($ls_codrecdoc));		
		$ls_procede="CXPARD";
		$ls_tipo_destino="";
		$ls_ced_bene="";
		$ls_cod_pro="";
		$ldt_fecha=$ad_fechaanula;
		$ls_codban="---";
		$ls_ctaban="-------------------------";
	    $lb_valido=$this->io_sigesp_int->uf_obtener_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_codban,
																$ls_ctaban,$ls_tipo_destino,&$ls_ced_bene,&$ls_cod_pro);
		if($lb_valido===false) 
		{ 
			$this->io_msg->message("ERROR-> No existe el comprobante Nº ".$ls_comprobante."-".$ls_procede.".");
			return false;
		}
        $lb_check_close=false;
		$lb_valido=$this->io_sigesp_int->uf_init_delete($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_tipo_destino,
														$ls_ced_bene,$ls_cod_pro,$lb_check_close,$ls_codban,$ls_ctaban);
		if($lb_valido===false)	
		{ 
			$this->io_msg->message("Error en método uf_init_delete ".$this->io_sigesp_int->is_msg_error);
			return false; 
		}
		// Inicio de la Transacción
        $this->io_sigesp_int->uf_int_init_transaction_begin();
	    $lb_valido=$this->uf_update_estatus_contabilizado_recepcion($ls_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,"C");
	    if($lb_valido)
		{
			$lb_valido=$this->uf_delete_historico_rd($ls_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,"A");
		}
	    if($lb_valido)
	    {
	        $lb_valido=$this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
	        if($lb_valido===false)
			{
				$this->io_msg->message("".$this->io_sigesp_int->is_msg_error);
			}
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_rd($ls_codemp,$as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,'','1900-01-01');
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reverso la Anulación de la Recepción de Documentos <b>".$as_numrecdoc."</b> - <b>".$as_codtipdoc."</b> - <b>".$as_cedbene."</b> - <b>".$as_codpro."</b> ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
    }// end function uf_procesar_reversar_anular_recepcion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_contabilizacion_creditos_debitos($as_numsol,$as_numrecdoc,$as_codtipdoc,$as_ced_bene,$as_cod_pro,
														  $as_codope,$as_numdc,$adt_fecha,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contabilizacion_creditos_debitos
		//		   Access: public (sigesp_mis_p_contabiliza_ncd.php)
		//     Argumentos: as_numsol // numero de la solicitud 
		//				   as_numrecdoc // numero de la recepción
		//                 as_codtipdoc // codigo del documento
		//				   as_ced_bene // cedula del beneficiario
		//                 as_cod_pro // codigo proveedor 
		//				   as_codope // operacion de la tabla si es debito o credito
		//                 as_numdc // numero de credito o de debito
		//                 adt_fecha // Fecha de Contabilización
		//                 aa_seguridad // Arreglo de Seguridad
		//	      Returns: Retorna un bollean valido
		//	  Description: Proceso de contabilizacion de las notas de creditos o de débitos que afecten a una solicitud
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 15/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
        $ls_codemp=$this->io_codemp;
		switch($as_codope)
		{
			case "ND":
				$ls_procede="CXPNOD";
				$ls_mensaje=" No existe la Nota de Débito N° ";
				break;
			case "NC":
				$ls_procede="CXPNOC";
				$ls_mensaje=" No existe la Nota de Crédito N° ";
				break;
			default:
				$this->io_msg->message(" Tipo de Nota Inválido");
				return false;
				break;
		}
        $ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante(trim($as_numdc));
        $this->dts_solicitud->resetds("numdc"); // inicializa el datastore notas de creditos y debitos en 0 registro.
		if(!$this->uf_obtener_data_notas($ls_codemp,$as_numsol,$as_numrecdoc,$as_codtipdoc,$as_ced_bene,$as_cod_pro,$as_codope,
										 $as_numdc))
		{
			$this->io_msg->message($ls_mensaje.$as_numsol);
			return false;
		}		
		$ls_ced_bene=$as_ced_bene;		
		$ls_cod_pro=$as_cod_pro;		
        $ls_tipo_destino=$this->dts_notas->getValue("tipproben",1);		
		$ldt_fecope=$this->dts_notas->getValue("fecope",1);
		$ls_descripcion=$this->dts_notas->getValue("desope",1);
        if($ls_tipo_destino=="B")
		{
			$ls_codigo_destino=$ls_ced_bene;
		}
		if($ls_tipo_destino=="P")
		{
			$ls_codigo_destino=$ls_cod_pro;
		}
		if($ls_tipo_destino=="-")
		{
			$ls_codigo_destino="----------";
		}
		$ldt_fecope=$this->io_function->uf_convertirdatetobd($ldt_fecope);
		$ldt_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
		if(!$this->io_fecha->uf_comparar_fecha($ldt_fecope,$ldt_fecha))
		{
			$this->io_msg->message(" La fecha de contabilizacion es menor que la fecha de la operación Nº ".$as_numdc);
			return false;
		}		
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		$li_tipo_comp=1; // comprobante Normal
		$this->as_procede=$ls_procede;
		$this->as_comprobante=$ls_comprobante;
		$this->ad_fecha=$ldt_fecha;
		$this->as_codban=$ls_codban;
		$this->as_ctaban=$ls_ctaban;
		$this->as_descripcion=$ls_descripcion;
		$lb_valido = $this->io_sigesp_int->uf_int_init($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_descripcion,
													   $ls_tipo_destino,$ls_codigo_destino,false,$ls_codban,$ls_ctaban,
													   $li_tipo_comp);
		if(!$lb_valido)
		{   
			$this->io_msg->message($this->io_sigesp_int->is_msg_error); 
			return false;		   		   
		}
		// inicia transacción SQL
		$this->io_sigesp_int->uf_int_init_transaction_begin(); 
		$lb_valido=$this->uf_procesar_detalles_contable_notas($ls_codemp,$as_numsol,$as_numrecdoc,$as_codtipdoc,$as_ced_bene,
															  $as_cod_pro,$as_codope,$as_numdc,$ls_descripcion);
		if(!$lb_valido)
		{
			$this->io_msg->message("No se pudo procesar los detalles contables!");
		}
	    if($lb_valido)
		{
			$lb_valido=$this->uf_procesar_detalles_spg_notas($ls_codemp,$as_numsol,$as_numrecdoc,$as_codtipdoc,$as_ced_bene,
															 $as_cod_pro,$as_codope,$as_numdc,$ls_descripcion);
			if(!$lb_valido)
			{
				$this->io_msg->message("No se pudo procesar los detalles de gastos!");
			}
		}
	    if($lb_valido)
		{
			$lb_valido=$this->uf_update_estatus_notas($ls_codemp,$as_numsol,$as_numrecdoc,$as_codtipdoc,$as_ced_bene,$as_cod_pro,
													  $as_codope,$as_numdc,"C");
			if(!$lb_valido)
			{
				$this->io_msg->message("No se pudo actualizar estatus de las notas!");
			}		
		}
        if($lb_valido)
		{
			$lb_valido=$this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
			if($lb_valido===false)
			{
				$this->io_msg->message("".$this->io_sigesp_int->is_msg_error);
			}		   
		}
        if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_nd($ls_codemp,$as_numsol,$as_numrecdoc,$as_codtipdoc,$as_ced_bene,
															   $as_cod_pro,$as_codope,$as_numdc,$ldt_fecha,'1900-01-01');
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Contabilizó la Nota de Crédito/Débito <b>".$as_numsol."</b>, Número de Recepción <b>".$as_numrecdoc."</b>,  ".
							"Fecha de Operación <b>".$adt_fecha."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/// PARA LA CONVERSIÓN MONETARIA
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/*if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_sigespcmp($this->as_procede,$this->as_comprobante,$this->ad_fecha,
																 $this->as_codban,$this->as_ctaban,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_spgdtcmp($this->as_procede,$this->as_comprobante,$this->ad_fecha,
																$this->as_codban,$this->as_ctaban,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_scgdtcmp($this->as_procede,$this->as_comprobante,$this->ad_fecha,
																$this->as_codban,$this->as_ctaban,$aa_seguridad);
		}*/
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
		
    }// end function uf_procesar_contabilizacion_creditos_debitos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_data_notas($as_codemp,$as_numsol,$as_numrecdoc,$as_codtipdoc,$as_ced_bene,$as_cod_pro,$as_codope,$as_numdc)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contabilizacion_creditos_debitos
		//		   Access: public (sigesp_mis_p_contabiliza_ncd.php)
		//     Argumentos: as_numsol // numero de la solicitud 
		//				   as_numrecdoc // numero de la recepción
		//                 as_codtipdoc // codigo del documento
		//				   as_ced_bene // cedula del beneficiario
		//                 as_cod_pro // codigo proveedor 
		//				   as_codope // operacion de la tabla si es debito o credito
		//                 as_numdc // numero de credito o de debito
		//	      Returns: Retorna datos de la solicitud compras mediante un datastrore publico
		//	  Description: Este metodo obtiene la data específica de la nota de credito y debito para su contabilización
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 15/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe=false;		
		$ls_sql="SELECT nc.codemp,nc.numsol,nc.numrecdoc,nc.codtipdoc,nc.ced_bene,nc.cod_pro, ".
                "        nc.codope,nc.numdc,nc.desope,nc.fecope,nc.monto,nc.estnotadc,nc.estapr,sol.tipproben ".
                " FROM cxp_sol_dc nc, cxp_solicitudes sol ".
                " WHERE nc.codemp=sol.codemp ".
				"	AND nc.numsol=sol.numsol ".
				"   AND nc.codemp='".$as_codemp."' ".
				"   AND nc.numsol='".$as_numsol."' ".
                "   AND nc.numrecdoc='".$as_numrecdoc."' ".
				"   AND nc.codtipdoc='".$as_codtipdoc."' ".
				"   AND nc.ced_bene='".$as_ced_bene."' ".
				"   AND nc.cod_pro='".$as_cod_pro."' ".
				"   AND nc.codope='".$as_codope."' ".
				"   AND nc.numdc='".$as_numdc."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_obtener_data_notas ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe = true; // si existe se procedera a registrar en el datastore.				
                $this->dts_notas->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$this->io_msg->message("La Solicitud no existe en la base de datos");
			}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_existe;
	}// end function uf_obtener_data_notas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_detalles_contable_notas($as_codemp,$as_numsol,$as_numrecdoc,$as_codtipdoc,$as_ced_bene,$as_cod_pro,
											     $as_codope,$as_numdc,$as_descripcion)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_detalles_contable_notas
		//		   Access: private
		//     Argumentos: as_numsol // numero de la solicitud 
		//				   as_numrecdoc // numero de la recepción
		//                 as_codtipdoc // codigo del documento
		//				   as_ced_bene // cedula del beneficiario
		//                 as_cod_pro // codigo proveedor 
		//				   as_codope // operacion de la tabla si es debito o credito
		//                 as_numdc // numero de credito o de debito
		//	      Returns: Retorna estatus si contabilizo en contabilidad
		//	  Description: Método que genera los asientos contables a partir 
		//                  de los movmientos de notas de creditos o débitos
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 15/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="SELECT numdc, codope, debhab, sc_cuenta, monto ".
                "  FROM cxp_dc_scg ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND numsol='".$as_numsol."' ".
				"   AND numrecdoc='".$as_numrecdoc."' ".
                "   AND codtipdoc='".$as_codtipdoc."' ".
				"   AND ced_bene='".$as_ced_bene."' ".
                "   AND cod_pro='".$as_cod_pro."' ".
				"   AND codope='".$as_codope."' ".
				"   AND numdc='".$as_numdc."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_procesar_detalles_contable_notas ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{           
			while($row=$this->io_sql->fetch_row($rs_data) and ($lb_valido))
			{
				$ls_scg_cuenta=$row["sc_cuenta"];
				$ls_debhab=$row["debhab"];				
				$ldec_monto=$row["monto"];				
				$ls_documento=$row["numdc"];
				$ls_documento=$this->io_sigesp_int->uf_fill_comprobante(trim($ls_documento));
				switch($row["codope"])
				{
					case "ND":
						$ls_procede="CXPNOD";
						$ls_mensaje=" No existe la Nota de Débito N° ".$as_numdc;
						break;
					case "NC":
						$ls_procede="CXPNOC";
						$ls_mensaje=" No existe la Nota de Crédito N° ".$as_numdc;
						break;
					default:
						$this->io_msg->message(" Tipo de Nota Inválido");
						return false;
						break;
				}
				$lb_valido=$this->io_sigesp_int->uf_scg_insert_datastore($as_codemp,$ls_scg_cuenta,$ls_debhab,$ldec_monto,
																		 $ls_documento,$ls_procede,$as_descripcion);								
				if($lb_valido===false)
				{  
					$this->io_msg->message("".$this->io_sigesp_int->is_msg_error);
					$lb_valido=false;
					break;
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
    }// end function uf_procesar_detalles_contable_notas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_detalles_spg_notas($as_codemp,$as_numsol,$as_numrecdoc,$as_codtipdoc,$as_ced_bene,$as_cod_pro,$as_codope,
											$as_numdc,$as_descripcion)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_detalles_contable_notas
		//		   Access: private
		//     Argumentos: as_numsol // numero de la solicitud 
		//				   as_numrecdoc // numero de la recepción
		//                 as_codtipdoc // codigo del documento
		//				   as_ced_bene // cedula del beneficiario
		//                 as_cod_pro // codigo proveedor 
		//				   as_codope // operacion de la tabla si es debito o credito
		//                 as_numdc // numero de credito o de debito
		//	      Returns: Retorna estatus si contabilizo en gasto y contabilidad
		//	  Description: Método que genera los asientos presupuestarios a partir 
		//                  de los movmientos de notas de creditos o débitos
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 15/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="SELECT cxp_dc_spg.numdc, cxp_dc_spg.codope, cxp_dc_spg.spg_cuenta, cxp_dc_spg.codestpro, cxp_dc_spg.estcla, cxp_dc_spg.monto, ".
				"		cxp_documento.estpre ".
                "  FROM cxp_dc_spg, cxp_documento ".
                " WHERE cxp_dc_spg.codemp='".$as_codemp."' ".
				"   AND cxp_dc_spg.numsol='".$as_numsol."' ".
				"   AND cxp_dc_spg.numrecdoc='".$as_numrecdoc."' ".
                "   AND cxp_dc_spg.codtipdoc='".$as_codtipdoc."' ".
				"   AND cxp_dc_spg.ced_bene='".$as_ced_bene."' ".
                "   AND cod_pro='".$as_cod_pro."' ".
				"   AND cxp_dc_spg.codope='".$as_codope."' ".
				"   AND cxp_dc_spg.numdc='".$as_numdc."'".
				"   AND cxp_dc_spg.codtipdoc=cxp_documento.codtipdoc";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_procesar_detalles_spg_notas ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{           
			while($row=$this->io_sql->fetch_row($rs_data) and ($lb_valido))
			{
				$ls_codestpro=$row["codestpro"];

				$ls_codestpro1=substr($ls_codestpro,0,25);
				$ls_codestpro2=substr($ls_codestpro,25,25);
				$ls_codestpro3=substr($ls_codestpro,50,25);
				$ls_codestpro4=substr($ls_codestpro,75,25);
				$ls_codestpro5=substr($ls_codestpro,100,25);
				$ls_estcla=$row["estcla"];
				$ls_spg_cuenta=$row["spg_cuenta"];
				$ldec_monto=$row["monto"];				
				switch($row["codope"])
				{
					case "ND":
						$ls_procede="CXPNOD";
						$ls_mensaje=" No existe la Nota de Débito N° ".$as_numdc;
						break;
					case "NC":
						$ls_procede="CXPNOC";
						$ls_mensaje=" No existe la Nota de Crédito N° ".$as_numdc;
						$ldec_monto=$ldec_monto*-1;
						break;
					default:
						$this->io_msg->message(" Tipo de Nota Inválido");
						return false;
						break;
				}
				$li_mensaje_spg=$row["estpre"]; 
				$ls_mensaje="";
				// Mensaje de operacion de gasto a ser aplicada
				if($li_mensaje_spg==1)
				{
					$ls_mensaje="C";
				}
				elseif($li_mensaje_spg==2)
				{
					$ls_mensaje="OC";
				}
				$ls_documento=$this->io_sigesp_int->uf_fill_comprobante(trim($as_numdc));
				$lb_valido=$this->io_sigesp_int->uf_spg_insert_datastore($as_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
						  												 $ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,$ls_mensaje,
						  												 $ldec_monto,$ls_documento,$ls_procede,$as_descripcion);
				if ($lb_valido===false)
				{  
					$this->io_msg->message("".$this->io_sigesp_int->is_msg_error);
					break;
				}
			}
			$this->io_sql->free_result($rs_data);	 
		}
		return $lb_valido;
    }// end function uf_procesar_detalles_spg_notas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_notas($as_codemp,$as_numsol,$as_numrecdoc,$as_codtipdoc,$as_ced_bene,$as_cod_pro,$as_codope,
									 $as_numdc,$as_estnotadc)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_notas
		//		   Access: private
		//     Argumentos: as_numsol // numero de la solicitud 
		//				   as_numrecdoc // numero de la recepción
		//                 as_codtipdoc // codigo del documento
		//				   as_ced_bene // cedula del beneficiario
		//                 as_cod_pro // codigo proveedor 
		//				   as_codope // operacion de la tabla si es debito o credito
		//                 as_numdc // numero de credito o de debito
		//                 as_estnotadc // Estatus de la nota
		//	      Returns: Retorna estatus si contabilizo en gasto y contabilidad
		//	  Description: Método que actualiza la solicitud en estatus contabilizado
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 16/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_sql="UPDATE cxp_sol_dc ".
		        "   SET estnotadc='".$as_estnotadc."'".
                " WHERE codemp='".$as_codemp."' ".
				"   AND numsol='".$as_numsol."' ".
                "   AND numrecdoc='".$as_numrecdoc."' ".
				"   AND codtipdoc='".$as_codtipdoc."' ".
				"   AND ced_bene='".$as_ced_bene."' ".
                "   AND cod_pro='".$as_cod_pro."' ".
				"   AND codope='".$as_codope."' ".
				"   AND numdc='".$as_numdc."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_update_estatus_notas ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		return $lb_valido;
	}// end function uf_update_estatus_notas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reversar_contabilizacion_creditos_debitos($as_numsol,$as_numrecdoc,$as_codtipdoc,$as_ced_bene,$as_cod_pro,
														  $as_codope,$as_numdc,$ad_fechaconta,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar_contabilizacion_creditos_debitos
		//		   Access: public
		//     Argumentos: as_numsol // numero de la solicitud 
		//				   as_numrecdoc // numero de la recepción
		//                 as_codtipdoc // codigo del documento
		//				   as_ced_bene // cedula del beneficiario
		//                 as_cod_pro // codigo proveedor 
		//				   as_codope // operacion de la tabla si es debito o credito
		//                 as_numdc // numero de credito o de debito
		//                 ad_fechaconta // Fecha en que fue contabilizada la nota de Debito ó credito
		//                 aa_seguridad // Arreglo de Seguridad
		//	      Returns: retorna un boolean
		//	  Description: Proceso que reversa la contabilización de una mnota de debito o credito
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 16/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
        $ls_codemp=$this->io_codemp;
		switch($as_codope)
		{
			case "ND":
				$ls_procede="CXPNOD";
				$ls_mensaje=" No existe la Nota de Débito N° ";
				break;
			case "NC":
				$ls_procede="CXPNOC";
				$ls_mensaje=" No existe la Nota de Crédito N° ";
				break;
			default:
				$this->io_msg->message(" Tipo de Nota Inválido");
				return false;
				break;
		}
        $ls_comprobante = $this->io_sigesp_int->uf_fill_comprobante(trim($as_numdc));
        $this->dts_notas->resetds("numdc"); // inicializa el datastore notas de creditos y debitos en 0 registro.
		if(!$this->uf_obtener_data_notas($ls_codemp,$as_numsol,$as_numrecdoc,$as_codtipdoc,$as_ced_bene,$as_cod_pro,
										 $as_codope,$as_numdc))
		{
			$this->io_msg->message($ls_mensaje.$as_numsol);
			return false;
		}		

		$ls_ced_bene=$as_ced_bene;		
		$ls_cod_pro=$as_cod_pro;		
        $ls_tipo_destino=$this->dts_notas->getValue("tipproben",1);	
		$adt_fecha=$ad_fechaconta;
		$ls_codban="---";
		$ls_ctaban="-------------------------";
	    $lb_valido=$this->io_sigesp_int->uf_obtener_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$adt_fecha,$ls_codban,
																$ls_ctaban,$ls_tipo_destino,$ls_ced_bene,$ls_cod_pro);
		if(!$lb_valido) 
		{ 
			$this->io_msg->message("ERROR-> No existe el comprobante Nº ".$ls_comprobante."-".$ls_procede.".");
			return false;
		}
		$lb_check_close=false;
		$lb_valido=$this->io_sigesp_int->uf_init_delete($ls_codemp,$ls_procede,$ls_comprobante,$adt_fecha,$ls_tipo_destino,
														$ls_ced_bene,$ls_cod_pro,$lb_check_close,$ls_codban,$ls_ctaban);
		if(!$lb_valido)	
		{ 
			$this->io_msg->message("".$this->io_sigesp_int->is_msg_error);
			return false; 
		}
        $this->io_sigesp_int->uf_int_init_transaction_begin();
	    if($lb_valido)
		{
			$lb_valido = $this->uf_update_estatus_notas($ls_codemp,$as_numsol,$as_numrecdoc,$as_codtipdoc,$as_ced_bene,
														$as_cod_pro,$as_codope,$as_numdc,"E");
			if(!$lb_valido)
			{
				$this->io_msg->message("No se pudo actualizar estatus de las notas!");
			}
		}
        if($lb_valido)
		{
			$lb_valido = $this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
			if ( !$lb_valido )
			{
				$this->io_msg->message("".$this->io_sigesp_int->is_msg_error);
			}		   
		}
        if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_nd($ls_codemp,$as_numsol,$as_numrecdoc,$as_codtipdoc,$as_ced_bene,
															   $as_cod_pro,$as_codope,$as_numdc,'1900-01-01','1900-01-01');
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reversó la Nota de Crédito/Débito <b>".$as_numsol."</b>, Número de Recepción <b>".$as_numrecdoc."</b>,  ".
							"Fecha de Operación <b>".$adt_fecha."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
    }// end function uf_reversar_contabilizacion_creditos_debitos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_fecha_contabilizado_cxp($as_codemp,$as_numsol,$ad_fechaconta,$ad_fechaanula)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_fecha_contabilizado_cxp
		//		   Access: private
		//	    Arguments: as_codemp  // Código
		//                 as_numsol  // numero de la  Solicitud de Pago
		//                 ad_fecha  // Fecha de contabilización ó de Anulación
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Método que actualiza la solicitud en estatus contabilizado
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 													Fecha Última Modificación : 21/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_campo1="";
		$ls_campo2="";
		if($ad_fechaconta!="")
		{
			$ls_campo1=" fechaconta='".$ad_fechaconta."' ";
		}
		if($ad_fechaanula!="")
		{
			$ls_campo2=" fechaanula='".$ad_fechaanula."' ";
		}
		if($ls_campo1!="")
		{
			if($ls_campo2!="")
			{
				$ls_campos=$ls_campo1.", ".$ls_campo2;
			}
			else
			{
				$ls_campos=$ls_campo1;
			}
		}
		else
		{
			$ls_campos=$ls_campo2;
		}
		$ls_sql="UPDATE cxp_solicitudes ".
		        "   SET ".$ls_campos.
                " WHERE codemp='".$as_codemp."' ".
				"   AND numsol='".$as_numsol."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_update_fecha_contabilizado_cxp ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_fecha_contabilizado_cxp
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_fecha_contabilizado_nd($as_codemp,$as_numsol,$as_numrecdoc,$as_codtipdoc,$as_ced_bene,$as_cod_pro,
											  $as_codope,$as_numdc,$ad_fechaconta,$ad_fechaanula)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_fecha_contabilizado_nd
		//		   Access: private
		//	    Arguments: as_codemp  // Código
		//                 as_numsol  // numero de la  Solicitud de Pago
		//                 ad_fecha  // Fecha de contabilización ó de Anulación
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Método que actualiza la solicitud en estatus contabilizado
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 													Fecha Última Modificación : 21/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_campo1="";
		$ls_campo2="";
		if($ad_fechaconta!="")
		{
			$ls_campo1=" fechaconta='".$ad_fechaconta."' ";
		}
		if($ad_fechaanula!="")
		{
			$ls_campo2=" fechaanula='".$ad_fechaanula."' ";
		}
		if($ls_campo1!="")
		{
			if($ls_campo2!="")
			{
				$ls_campos=$ls_campo1.", ".$ls_campo2;
			}
			else
			{
				$ls_campos=$ls_campo1;
			}
		}
		else
		{
			$ls_campos=$ls_campo2;
		}
		$ls_sql="UPDATE cxp_sol_dc ".
		        "   SET ".$ls_campos.
                " WHERE codemp='".$as_codemp."' ".
				"   AND numsol='".$as_numsol."' ".
                "   AND numrecdoc='".$as_numrecdoc."' ".
				"   AND codtipdoc='".$as_codtipdoc."' ".
				"   AND ced_bene='".$as_ced_bene."' ".
                "   AND cod_pro='".$as_cod_pro."' ".
				"   AND codope='".$as_codope."' ".
				"   AND numdc='".$as_numdc."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_update_fecha_contabilizado_nd ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_fecha_contabilizado_nd
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_fecha_contabilizado_rd($as_codemp,$as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,
											  $ad_fechaconta,$ad_fechaanula)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_fecha_contabilizado_rd
		//		   Access: private
		//	    Arguments: as_codemp // Código de Empresa
		//                 as_numrecdoc // numero de la Recepción de Documentos  
		//                 as_codtipdoc // tipo de documento
		//                 as_codpro // Código de Proveedor  
		//                 as_cedbene // Cédula del Beneficiario 
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Método que actualiza la solicitud en estatus contabilizado
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 													Fecha Última Modificación : 21/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_campo1="";
		$ls_campo2="";
		if($ad_fechaconta!="")
		{
			$ls_campo1=" fechaconta='".$ad_fechaconta."' ";
		}
		if($ad_fechaanula!="")
		{
			$ls_campo2=" fechaanula='".$ad_fechaanula."' ";
		}
		if($ls_campo1!="")
		{
			if($ls_campo2!="")
			{
				$ls_campos=$ls_campo1.", ".$ls_campo2;
			}
			else
			{
				$ls_campos=$ls_campo1;
			}
		}
		else
		{
			$ls_campos=$ls_campo2;
		}
		$ls_sql="UPDATE cxp_rd ".
		        "   SET ".$ls_campos.
                " WHERE codemp='".$as_codemp."' ".
                "   AND numrecdoc='".$as_numrecdoc."' ".
				"   AND codtipdoc='".$as_codtipdoc."' ".
				"   AND ced_bene='".$as_cedbene."' ".
                "   AND cod_pro='".$as_codpro."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_update_fecha_contabilizado_nd ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_fecha_contabilizado_rd
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_historico_pagado($as_codemp,$as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_historico_pagado
		//		   Access: private
		//	    Arguments: as_codemp  // Código
		//                 as_numsol  // numero de la  Solicitud de Pago
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Método que actualiza la solicitud en estatus contabilizado
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 													Fecha Última Modificación : 21/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM cxp_historico_solicitud ".
				" WHERE codemp='".$as_codemp."' ".
				"   AND numsol='".$as_numsol."' ".
				"   AND estprodoc='P' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_delete_historico_pagado ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_delete_historico_pagado
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>