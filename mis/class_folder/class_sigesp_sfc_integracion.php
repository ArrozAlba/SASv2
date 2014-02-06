<?php
 ////////////////////////////////////////////////////////////////////////////////////////////////////////
 //       Class : class_sigesp_sfc_integracion_php                                                     //    
 // Description : Esta clase tiene todos los metodos necesario para el manejo de la rutina integradora //
 //               del sistema de facturaciòn 														   //
 ////////////////////////////////////////////////////////////////////////////////////////////////////////
class class_sigesp_sfc_integracion
{
	//Instancia de la clase funciones.
    var $is_msg_error;
	var $dts_empresa; 
	var $dts_nomina;
	var $dts_banco;
	var $dts_nomina_aporte;
	var $obj="";
	var $io_sql;
	var $io_siginc;
	var $io_conect;
	var $io_function;	
    var $io_sigesp_int;
	var $io_sigesp_int_spg;
	var $io_sigesp_int_scg;	
	var $io_fecha;
	var $io_msg;
	var $is_codemp="";
	var $is_procede="";
	var $is_mensaje_spi="";	
	var $is_mensaje_spg="";	
	var $is_comprobante;
	var $idt_fecha;
    var	$is_tiponomina;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function class_sigesp_sfc_integracion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: class_sigesp_sfc_integracion
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: 																Fecha Última Modificación : 
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
		$this->is_codemp=$this->dts_empresa["codemp"];		
		$this->dts_banco=new class_datastore();
		$this->io_msg=new class_mensajes();		
		$this->io_sigesp_int_spg=new class_sigesp_int_spg();
		$this->io_sigesp_int_scg=new class_sigesp_int_scg();	
		$this->io_sigesp_spi=new class_sigesp_int_spi();			
		$this->io_seguridad=new sigesp_c_seguridad();		
		$this->as_procede="";
		$this->as_comprobante="";
		$this->ad_fecha="";
		$this->as_codban="";
		$this->as_ctaban="";
	}// end function class_sigesp_sfc_integracion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destroy_objects()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destroy_objects
		//		   Access: public 
		//	  Description: Destructor de los objectos de la Clase
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: 																Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if( is_object($this->io_fecha) ) { unset($this->io_fecha);  }
		if( is_object($this->io_sigesp_int) ) { unset($this->io_sigesp_int);  }
		if( is_object($this->io_function) ) { unset($this->io_function);  }
		if( is_object($this->io_siginc) ) { unset($this->io_siginc);  }
		if( is_object($this->io_connect) ) { unset($this->io_connect);  }
		if( is_object($this->io_sql) ) { unset($this->io_sql);  }	   
		if( is_object($this->obj) ) { unset($this->obj);  }	   
		if( is_object($this->dts_empresa) ) { unset($this->dts_empresa);  }	   
		if( is_object($this->dts_banco) ) { unset($this->dts_banco);  }	   	   
		if( is_object($this->io_msg) ) { unset($this->io_msg);  }	   
		if( is_object($this->io_sigesp_int_spg) ) { unset($this->io_sigesp_int_spg);  }	   
		if( is_object($this->io_sigesp_int_scg) ) { unset($this->io_sigesp_int_scg);  }	   
		if( is_object($this->io_seguridad) ) { unset($this->io_seguridad);  }	   
	}// end function uf_destroy_objects
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_contabilizacion_comprobantes_cxc($as_comprobante,$ad_fecha,$as_procede,$as_descripcion,$ld_fecaprob,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contabilizacion_comprobantes_cxc
		//		   Access: public (sigesp_mis_p_contabiliza_sfc_cxc.php)
		//	    Arguments: as_comprobante  // Código de Comprobante
		//				   ad_fecha  // Fecha de contabilización
		//				   as_procede  // Procede del Documento
		//				   as_descripcion  // Descripción del Documento
		//				   ld_fecaprob  // Fecha de aprobación
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Funcion que procesa la contabilización de las modificaciones presupuestarias de ingreso
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: 																Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
        $ls_codemp=$this->is_codemp;
		$ls_comprobantesigesp=$this->io_sigesp_int->uf_fill_comprobante(trim($as_comprobante));
		$ls_procede=$as_procede;
		$ld_fecha=$ad_fecha;
        $ls_tipo_destino="-";		
		$ldt_fecope=$ld_fecha;
		$ls_descripcion=$as_descripcion;
		$ls_codigo_destino="----------";
		$ldt_fecope=$this->io_function->uf_convertirdatetobd($ldt_fecope);
		$ldt_fecha=$this->io_function->uf_convertirdatetobd($ld_fecaprob);
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		$li_tipo_comp=1; 
		$this->as_procede=$ls_procede;
		$this->as_comprobante=$ls_comprobantesigesp;
		$this->ad_fecha=$ldt_fecha;
		$this->as_codban=$ls_codban;
		$this->as_ctaban=$ls_ctaban;
		$lb_valido=$this->io_sigesp_int->uf_int_init($ls_codemp,$ls_procede,$ls_comprobantesigesp,$ld_fecaprob,$ls_descripcion,
													 $ls_tipo_destino,$ls_codigo_destino,false,$ls_codban,$ls_ctaban,
													 $li_tipo_comp);
		if (!$lb_valido)
		{   
           $this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error); 
		   return false;		   		   
		}
		$this->io_sigesp_int->uf_int_init_transaction_begin(); // inicia transacción SQL
		$lb_valido = $this->uf_procesar_detalles_contable_cxc($ls_codemp,$ls_procede,$as_comprobante,$ldt_fecope);
		if(!$lb_valido)
		{
			$this->io_msg->message("ERROR -> No se pudo procesar los detalles contables.");
		}        
	    if($lb_valido)
		{
			$lb_valido = $this->uf_procesar_detalles_ingreso_cxc($ls_codemp,$ls_procede,$as_comprobante,$ldt_fecope);
			if(!$lb_valido)
			{
				$this->io_msg->message("ERROR -> No se pudo procesar los detalles de ingreso.");
			}
		}
        if($lb_valido)
		{
			$lb_valido = $this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
			if ( $lb_valido===false)
			{		   	 
				$this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error);
			}
		}
	    if($lb_valido)
		{
			$lb_valido = $this->uf_update_estatus_modificacion_cxc($ls_codemp,$ls_procede,$as_comprobante,$ldt_fecope,$ls_descripcion,$ls_tipo_destino,1,$ldt_fecha,$ls_comprobantesigesp);
			if(!$lb_valido)
			{
				$this->io_msg->message("ERROR -> No se pudo actualizar estatus de los Comprobantes");
			}
		}
		if($lb_valido)
		{	

			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Contabilizo las facturas Comprobante <b>".$as_comprobante."</b>, ".
							"Procede <b>".$ls_procede."</b>, Fecha Documento <b>".$ldt_fecope."</b>, ".
							"Fecha de Aprobación <b>".$ldt_fecha."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
    }// end function uf_procesar_contabilizacion_comprobantes_cxc
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_detalles_contable_cxc($as_codemp,$as_procede,$as_comprobante,$ad_fecha)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_detalles_contable_cxc
		//		   Access: private
		//	    Arguments: ls_codemp  // Código de Empresa
		//				   ls_procede  // Procede del Documento
		//				   ls_comprobante  // Número de comprobante
		//				   ad_fecha  // Fecha de Contabilización
		//				   ls_descripcion  // descripción del Comprobante
		//				   ls_tipo_destino  // Destino de la contabilización
		//				   ls_codigo_destino  // Código del Destino
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Método que genera los asientos contables a partir de los movmientos de notas de creditos o débitos
		//	   Creado Por: Ing. Luis Anibal Lang	
		// Modificado Por: 											Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="SELECT sc_cuenta, debhab, monto, documento, descripcion ".
                "  FROM mis_sigesp_cxc ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND comprobante='".$as_comprobante."' ".
				"   AND fecha='".$ad_fecha."' ".
                "   AND procede='".$as_procede."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
			$lb_valido=false;
            $this->io_msg->message("CLASE->Integración SPI MÉTODO->uf_procesar_detalles_contable_cxc ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		}
		else
		{           
			while((!$rs_data->EOF) and ($lb_valido))
			{
				$ls_scg_cuenta=$rs_data->fields["sc_cuenta"];
				$ls_debhab=$rs_data->fields["debhab"];				
				$ldec_monto=number_format($rs_data->fields["monto"],2,".","");				
				$ls_documento=$rs_data->fields["documento"]; 
				$ls_descripcion=$rs_data->fields["descripcion"];
				$lb_valido=$this->io_sigesp_int->uf_scg_insert_datastore($as_codemp,$ls_scg_cuenta,$ls_debhab,$ldec_monto,$ls_documento,$as_procede,$ls_descripcion);								
				if ($lb_valido===false)
				{  
					$this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error);
					break;
				}
				$rs_data->MoveNext();
			} // end while
			$this->io_sql->free_result($rs_data);	 
		}
		return $lb_valido;
    }// end function uf_procesar_detalles_contable_cxc
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_detalles_ingreso_cxc($as_codemp,$as_procede,$as_comprobante,$ad_fecha)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_detalles_ingreso_cxc
		//		   Access: private
		//	    Arguments: ls_codemp  // Código de Empresa
		//				   ls_procede  // Procede del Documento
		//				   ls_comprobante  // Número de comprobante
		//				   ad_fecha  // Fecha de Contabilización
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Método que genera los asientos presupuestarios de ingreso a partirde los movmientos de notas de creditos o débitos
		//	   Creado Por: Ing. Luis Anibal Lang	
		// Modificado Por: 											Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="SELECT spi_cuenta, monto, documento, procede, 'DEV' as operacion, descripcion,  ".
		        "       '-------------------------' as codestpro1, '-------------------------' as codestpro2, '-------------------------' as codestpro3,".
				"       '-------------------------' as codestpro4, '-------------------------' as codestpro5, '-' as estcla   ".
                "  FROM mis_sigesp_cxc ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND comprobante='".$as_comprobante."' ".
				"   AND procede='".$as_procede."' ".
                "   AND fecha='".$ad_fecha."' ".
				"   AND trim(spi_cuenta)<>''";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
            $this->io_msg->message("CLASE->Integración SFC MÉTODO->uf_procesar_detalles_ingreso_cxc ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{           
   	       while((!$rs_data->EOF) and ($lb_valido))
		   {
				$ls_spi_cuenta=$rs_data->fields["spi_cuenta"];
				$ldec_monto=$rs_data->fields["monto"];
				$ls_documento=$rs_data->fields["documento"];
				$ls_procede=$rs_data->fields["procede"];	
				$ls_operacion=$rs_data->fields["operacion"];
				$as_descripcion=$rs_data->fields["descripcion"];
				//------------------------------------------------------------------------------
				$ls_codestpro1=$rs_data->fields["codestpro1"];
				$ls_codestpro2=$rs_data->fields["codestpro2"];
				$ls_codestpro3=$rs_data->fields["codestpro3"];
				$ls_codestpro4=$rs_data->fields["codestpro4"];
				$ls_codestpro5=$rs_data->fields["codestpro5"];
				$ls_estcla    =$rs_data->fields["estcla"];
				//------------------------------------------------------------------------------
				$ls_mensaje=$this->io_sigesp_spi->uf_operacion_codigo_mensaje($rs_data->fields["operacion"]);
				$ls_spi_cuenta=$this->io_sigesp_spi->uf_spi_pad_cuenta($ls_spi_cuenta);
				$lb_valido=$this->io_sigesp_int->uf_spi_insert_datastore($as_codemp,$ls_spi_cuenta,$ls_operacion,
																		 $ldec_monto,$ls_documento,$ls_procede,$as_descripcion,
																		 $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																		 $ls_codestpro4,$ls_codestpro5, $ls_estcla);				
				if ($lb_valido===false)
				{  
					$this->io_msg->message("ERROR ->".$this->io_sigesp_int->is_msg_error);
					break;
				}
				$rs_data->MoveNext();
		   } // end while
		}
		$this->io_sql->free_result($rs_data);	 
		return $lb_valido;
    }// end function uf_procesar_detalles_ingreso_cxc
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_modificacion_cxc($as_codemp,$as_procede,$as_comprobante,$ad_fecha,$as_descripcion,$as_tipo_destino,$li_estcon,$ls_fecope,$as_comprobantesigesp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_modificacion_cxc
		//		   Access: private
		//	    Arguments: as_codemp  // Código de Empresa
		//				   as_procede  // Procede del Documento
		//				   as_comprobante  // Número de comprobante
		//				   ad_fecha  // Fecha de Contabilización
		//				   as_descripcion  // descripción del Comprobante
		//				   as_tipo_destino  // Destino de la contabilización
		//				   li_estcon  // estatus de aprobación
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Método que actualiza la solicitud en estatus contabilizado
		//	   Creado Por: Ing. Yesenia Moreno	
		// Modificado Por: 											Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ad_fecha=$this->io_function->uf_convertirdatetobd($ad_fecha);
		$ls_sql="UPDATE mis_sigesp_cxc ".
		        "   SET estint=".$li_estcon.", ".
		        "       comprobante_sigesp='".$as_comprobantesigesp."', ".
		        "       fechaconta='".$ls_fecope."', ".
		        "       fechaanula='1900-01-01' ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND procede='".$as_procede."' ".
                "   AND comprobante='".$as_comprobante."' ".
				"   AND fecha='".$ad_fecha."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración SPI MÉTODO->uf_update_estatus_modificacion_cxc ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_estatus_modificacion_cxc
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reversar_contabilizacion_comprobantes_cxc($as_comprobante,$ad_fecha,$as_procede,$as_descripcion,$ad_fechaconta,$aa_seguridad)	
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar_contabilizacion_modpre_spi
		//		   Access: public (sigesp_mis_p_reverso_mp_spi.php)
		//	    Arguments: as_comprobante  // Código de Comprobante
		//				   ad_fecha  // Fecha de contabilización
		//				   as_procede  // Procede del Documento
		//				   as_descripcion  // Descripción del Documento
		//				   ad_fechaconta  // Fecha de Contabilización
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Funcion que procesa el reverso de aprobación de las modificaciones presupuestarias de ingreso
		//	   Creado Por: Ing. Yesenia Moreno	
		// Modificado Por: 										Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	    $adt_fecha=$ad_fechaconta;
		$lb_valido=false;
        $ls_codemp=$this->is_codemp;
        $ls_procede=$as_procede; 
		$ad_fecha=$this->io_function->uf_convertirdatetobd($ad_fecha);
        $ls_comprobantesigesp=$this->io_sigesp_int->uf_fill_comprobante(trim($as_comprobante));
        $ls_tipo_destino="-" ;
		$ls_ced_bene="----------"; 
		$ls_cod_pro="----------";	
		$ls_codban="---";
		$ls_ctaban="-------------------------";
	    $lb_valido=$this->io_sigesp_int->uf_obtener_comprobante($ls_codemp,$ls_procede,$ls_comprobantesigesp,$adt_fecha,$ls_codban,
																$ls_ctaban,$ls_tipo_destino,$ls_ced_bene,$ls_cod_pro);
		if ($lb_valido===false) 
		{ 
  			$this->io_msg->message("ERROR-> No existe el comprobante Nº ".$ls_comprobantesigesp."-".$ls_procede.".");	
			return false;
		}
		$lb_check_close=false;
		$lb_valido = $this->io_sigesp_int->uf_init_delete($ls_codemp,$ls_procede,$ls_comprobantesigesp,$adt_fecha,$ls_tipo_destino,
														  $ls_ced_bene,$ls_cod_pro,$lb_check_close,$ls_codban,$ls_ctaban);
		if($lb_valido===false )	
		{ 
 		   $this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
		   return false; 
		}
        $this->io_sigesp_int->uf_int_init_transaction_begin();	
	    if  ($lb_valido)
		{
			$lb_valido = $this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
			if ( $lb_valido===false)
			{
				$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
			}		   
		}
		if ($lb_valido) 
		{ 
			$lb_valido=$this->uf_update_estatus_modificacion_cxc($ls_codemp,$ls_procede,$as_comprobante,$ad_fecha,$as_descripcion,$ls_tipo_destino,0,'1900-01-01','');
		}
		if ($lb_valido===false)
		{
		   $this->io_msg->message("ERROR-> al cambiar estatus de la factura  ".$as_comprobante);
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reversó la Factura Comprobante <b>".$as_comprobante."</b>, ".
							"Procede <b>".$as_procede."</b>, Fecha Documento <b>".$ad_fecha."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
	}// end function uf_reversar_contabilizacion_comprobantes_cxc
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_contabilizacion_comprobantes_nc($as_comprobante,$ad_fecha,$as_procede,$as_descripcion,$ld_fecaprob,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contabilizacion_comprobantes_nc
		//		   Access: public (sigesp_mis_p_contabiliza_sfc_nc.php)
		//	    Arguments: as_comprobante  // Código de Comprobante
		//				   ad_fecha  // Fecha de contabilización
		//				   as_procede  // Procede del Documento
		//				   as_descripcion  // Descripción del Documento
		//				   ld_fecaprob  // Fecha de aprobación
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Funcion que procesa la contabilización de las modificaciones presupuestarias de ingreso
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: 																Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
        $ls_codemp=$this->is_codemp;
		$ls_comprobantesigesp=$this->io_sigesp_int->uf_fill_comprobante(trim($as_comprobante));
		$ls_procede=$as_procede;
		$ld_fecha=$ad_fecha;
        $ls_tipo_destino="-";		
		$ldt_fecope=$ld_fecha;
		$ls_descripcion=$as_descripcion;
		$ls_codigo_destino="----------";
		$ldt_fecope=$this->io_function->uf_convertirdatetobd($ldt_fecope);
		$ldt_fecha=$this->io_function->uf_convertirdatetobd($ld_fecaprob);
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		$li_tipo_comp=1; 
		$this->as_procede=$ls_procede;
		$this->as_comprobante=$ls_comprobantesigesp;
		$this->ad_fecha=$ldt_fecha;
		$this->as_codban=$ls_codban;
		$this->as_ctaban=$ls_ctaban;
		$lb_valido=$this->io_sigesp_int->uf_int_init($ls_codemp,$ls_procede,$ls_comprobantesigesp,$ld_fecaprob,$ls_descripcion,
													 $ls_tipo_destino,$ls_codigo_destino,false,$ls_codban,$ls_ctaban,
													 $li_tipo_comp);
		if (!$lb_valido)
		{   
           $this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error); 
		   return false;		   		   
		}
		$this->io_sigesp_int->uf_int_init_transaction_begin(); // inicia transacción SQL
		$lb_valido = $this->uf_procesar_detalles_contable_nc($ls_codemp,$ls_procede,$as_comprobante,$ldt_fecope);
		if(!$lb_valido)
		{
			$this->io_msg->message("ERROR -> No se pudo procesar los detalles contables.");
		}        
	    if($lb_valido)
		{
			$lb_valido = $this->uf_procesar_detalles_ingreso_nc($ls_codemp,$ls_procede,$as_comprobante,$ldt_fecope);
			if(!$lb_valido)
			{
				$this->io_msg->message("ERROR -> No se pudo procesar los detalles de ingreso.");
			}
		}
        if($lb_valido)
		{
			$lb_valido = $this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
			if ( $lb_valido===false)
			{		   	 
				$this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error);
			}
		}
	    if($lb_valido)
		{
			$lb_valido = $this->uf_update_estatus_modificacion_nc($ls_codemp,$ls_procede,$as_comprobante,$ldt_fecope,$ls_descripcion,$ls_tipo_destino,1,$ldt_fecha,$ls_comprobantesigesp);
			if(!$lb_valido)
			{
				$this->io_msg->message("ERROR -> No se pudo actualizar estatus de los Comprobantes");
			}
		}
		if($lb_valido)
		{	

			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Contabilizo las Notas de Crèdito Comprobante <b>".$as_comprobante."</b>, ".
							"Procede <b>".$ls_procede."</b>, Fecha Documento <b>".$ldt_fecope."</b>, ".
							"Fecha de Aprobación <b>".$ldt_fecha."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
    }// end function uf_procesar_contabilizacion_comprobantes_nc
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_detalles_contable_nc($as_codemp,$as_procede,$as_comprobante,$ad_fecha)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_detalles_contable_nc
		//		   Access: private
		//	    Arguments: ls_codemp  // Código de Empresa
		//				   ls_procede  // Procede del Documento
		//				   ls_comprobante  // Número de comprobante
		//				   ad_fecha  // Fecha de Contabilización
		//				   ls_descripcion  // descripción del Comprobante
		//				   ls_tipo_destino  // Destino de la contabilización
		//				   ls_codigo_destino  // Código del Destino
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Método que genera los asientos contables a partir de los movmientos de notas de creditos o débitos
		//	   Creado Por: Ing. Luis Anibal Lang	
		// Modificado Por: 											Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="SELECT sc_cuenta, debhab, monto, documento, descripcion ".
                "  FROM mis_sigesp_nc ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND comprobante='".$as_comprobante."' ".
				"   AND fecha='".$ad_fecha."' ".
                "   AND procede='".$as_procede."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
			$lb_valido=false;
            $this->io_msg->message("CLASE->Integración SPI MÉTODO->uf_procesar_detalles_contable_nc ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		}
		else
		{           
			while((!$rs_data->EOF) and ($lb_valido))
			{
				$ls_scg_cuenta=$rs_data->fields["sc_cuenta"];
				$ls_debhab=$rs_data->fields["debhab"];				
				$ldec_monto=number_format($rs_data->fields["monto"],2,".","");				
				$ls_documento=$rs_data->fields["documento"]; 
				$ls_descripcion=$rs_data->fields["descripcion"];
				$lb_valido=$this->io_sigesp_int->uf_scg_insert_datastore($as_codemp,$ls_scg_cuenta,$ls_debhab,$ldec_monto,$ls_documento,$as_procede,$ls_descripcion);								
				if ($lb_valido===false)
				{  
					$this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error);
					break;
				}
				$rs_data->MoveNext();
			} // end while
			$this->io_sql->free_result($rs_data);	 
		}
		return $lb_valido;
    }// end function uf_procesar_detalles_contable_nc
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_detalles_ingreso_nc($as_codemp,$as_procede,$as_comprobante,$ad_fecha)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_detalles_ingreso_cxc
		//		   Access: private
		//	    Arguments: ls_codemp  // Código de Empresa
		//				   ls_procede  // Procede del Documento
		//				   ls_comprobante  // Número de comprobante
		//				   ad_fecha  // Fecha de Contabilización
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Método que genera los asientos presupuestarios de ingreso a partirde los movmientos de notas de creditos o débitos
		//	   Creado Por: Ing. Luis Anibal Lang	
		// Modificado Por: 											Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="SELECT spi_cuenta, monto, documento, procede, 'DI' as operacion, descripcion,  ".
		        "       '-------------------------' as codestpro1, '-------------------------' as codestpro2, '-------------------------' as codestpro3,".
				"       '-------------------------' as codestpro4, '-------------------------' as codestpro5, '-' as estcla   ".
                "  FROM mis_sigesp_nc ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND comprobante='".$as_comprobante."' ".
				"   AND procede='".$as_procede."' ".
                "   AND fecha='".$ad_fecha."' ".
				"   AND trim(spi_cuenta)<>''";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
            $this->io_msg->message("CLASE->Integración SFC MÉTODO->uf_procesar_detalles_ingreso_nc ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{           
   	       while((!$rs_data->EOF) and ($lb_valido))
		   {
				$ls_spi_cuenta=$rs_data->fields["spi_cuenta"];
				$ldec_monto=$rs_data->fields["monto"];
				$ls_documento=$rs_data->fields["documento"];
				$ls_procede=$rs_data->fields["procede"];	
				$ls_operacion=$rs_data->fields["operacion"];
				$as_descripcion=$rs_data->fields["descripcion"];
				//------------------------------------------------------------------------------
				$ls_codestpro1=$rs_data->fields["codestpro1"];
				$ls_codestpro2=$rs_data->fields["codestpro2"];
				$ls_codestpro3=$rs_data->fields["codestpro3"];
				$ls_codestpro4=$rs_data->fields["codestpro4"];
				$ls_codestpro5=$rs_data->fields["codestpro5"];
				$ls_estcla    =$rs_data->fields["estcla"];
				//------------------------------------------------------------------------------
				$ls_mensaje=$this->io_sigesp_spi->uf_operacion_codigo_mensaje($rs_data->fields["operacion"]);
				$ls_spi_cuenta=$this->io_sigesp_spi->uf_spi_pad_cuenta($ls_spi_cuenta);
				$lb_valido=$this->io_sigesp_int->uf_spi_insert_datastore($as_codemp,$ls_spi_cuenta,$ls_operacion,
																		 $ldec_monto,$ls_documento,$ls_procede,$as_descripcion,
																		 $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																		 $ls_codestpro4,$ls_codestpro5, $ls_estcla);				
				if ($lb_valido===false)
				{  
					$this->io_msg->message("ERROR ->".$this->io_sigesp_int->is_msg_error);
					break;
				}
				$rs_data->MoveNext();
		   } // end while
		}
		$this->io_sql->free_result($rs_data);	 
		return $lb_valido;
    }// end function uf_procesar_detalles_ingreso_nc
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_modificacion_nc($as_codemp,$as_procede,$as_comprobante,$ad_fecha,$as_descripcion,$as_tipo_destino,$li_estcon,$ls_fecope,$as_comprobantesigesp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_modificacion_nc
		//		   Access: private
		//	    Arguments: as_codemp  // Código de Empresa
		//				   as_procede  // Procede del Documento
		//				   as_comprobante  // Número de comprobante
		//				   ad_fecha  // Fecha de Contabilización
		//				   as_descripcion  // descripción del Comprobante
		//				   as_tipo_destino  // Destino de la contabilización
		//				   li_estcon  // estatus de aprobación
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Método que actualiza la solicitud en estatus contabilizado
		//	   Creado Por: Ing. Yesenia Moreno	
		// Modificado Por: 											Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ad_fecha=$this->io_function->uf_convertirdatetobd($ad_fecha);
		$ls_sql="UPDATE mis_sigesp_nc ".
		        "   SET estint=".$li_estcon.", ".
		        "       comprobante_sigesp='".$as_comprobantesigesp."', ".
		        "       fechaconta='".$ls_fecope."', ".
		        "       fechaanula='1900-01-01' ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND procede='".$as_procede."' ".
                "   AND comprobante='".$as_comprobante."' ".
				"   AND fecha='".$ad_fecha."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración SPI MÉTODO->uf_update_estatus_modificacion_nc ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_estatus_modificacion_nc
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reversar_contabilizacion_comprobantes_nc($as_comprobante,$ad_fecha,$as_procede,$as_descripcion,$ad_fechaconta,$aa_seguridad)	
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar_contabilizacion_comprobantes_nc
		//		   Access: public (sigesp_mis_p_reverso_sfc_nc.php)
		//	    Arguments: as_comprobante  // Código de Comprobante
		//				   ad_fecha  // Fecha de contabilización
		//				   as_procede  // Procede del Documento
		//				   as_descripcion  // Descripción del Documento
		//				   ad_fechaconta  // Fecha de Contabilización
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Funcion que procesa el reverso de aprobación de las modificaciones presupuestarias de ingreso
		//	   Creado Por: Ing. Luis Anbbal Lang	
		// Modificado Por: 										Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	    $adt_fecha=$ad_fechaconta;
		$lb_valido=false;
        $ls_codemp=$this->is_codemp;
        $ls_procede=$as_procede; 
		$ad_fecha=$this->io_function->uf_convertirdatetobd($ad_fecha);
        $ls_comprobantesigesp=$this->io_sigesp_int->uf_fill_comprobante(trim($as_comprobante));
        $ls_tipo_destino="-" ;
		$ls_ced_bene="----------"; 
		$ls_cod_pro="----------";	
		$ls_codban="---";
		$ls_ctaban="-------------------------";
	    $lb_valido=$this->io_sigesp_int->uf_obtener_comprobante($ls_codemp,$ls_procede,$ls_comprobantesigesp,$adt_fecha,$ls_codban,
																$ls_ctaban,$ls_tipo_destino,$ls_ced_bene,$ls_cod_pro);
		if ($lb_valido===false) 
		{ 
  			$this->io_msg->message("ERROR-> No existe el comprobante Nº ".$ls_comprobantesigesp."-".$ls_procede.".");	
			return false;
		}
		$lb_check_close=false;
		$lb_valido = $this->io_sigesp_int->uf_init_delete($ls_codemp,$ls_procede,$ls_comprobantesigesp,$adt_fecha,$ls_tipo_destino,
														  $ls_ced_bene,$ls_cod_pro,$lb_check_close,$ls_codban,$ls_ctaban);
		if($lb_valido===false )	
		{ 
 		   $this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
		   return false; 
		}
        $this->io_sigesp_int->uf_int_init_transaction_begin();	
	    if  ($lb_valido)
		{
			$lb_valido = $this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
			if ( $lb_valido===false)
			{
				$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
			}		   
		}
		if ($lb_valido) 
		{ 
			$lb_valido=$this->uf_update_estatus_modificacion_nc($ls_codemp,$ls_procede,$as_comprobante,$ad_fecha,$as_descripcion,$ls_tipo_destino,0,'1900-01-01','');
		}
		if ($lb_valido===false)
		{
		   $this->io_msg->message("ERROR-> al cambiar estatus de la Nota de crédito  ".$as_comprobante);
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reversó las Notas de crédito Comprobante <b>".$as_comprobante."</b>, ".
							"Procede <b>".$as_procede."</b>, Fecha Documento <b>".$ad_fecha."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
	}// end function uf_reversar_contabilizacion_comprobantes_nc
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_contabilizacion_pagos($as_comprobante,$adt_fecha,$as_procede,$as_codban,$as_ctaban,$as_descripcion,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contabilizacion_pagos
		//		   Access: public (sigesp_mis_p_contabiliza_sfc_pagos.php)
		//	    Arguments: as_comprobante  // Código de Comprobante
		//				   adt_fecha  // Fecha de contabilización
		//				   as_procede  // Procede
		//				   as_codban  // Còdigo de Banco
		//				   as_ctaban  // Cuenta Banco
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Funcion que procesa la contabilización dado un comprobante
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: 																Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sigesp_int->uf_int_init_transaction_begin();
		$ls_comprobante_sigesp = $this->io_sigesp_int->uf_fill_comprobante(trim($as_comprobante));
		$adt_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
		$ls_codope='DP';
		$ls_estmov='N';
		$ls_codestpro='-------------------------';
		$ls_estcla='-';
		$ld_montototal=0;
		$ls_operacion='COB';
	    $lb_valido=true;
		$ls_sql="INSERT INTO scb_movbco(codemp,codban,ctaban,numdoc,codope,estmov,cod_pro,ced_bene,tipo_destino, codconmov,".
		        "                       fecmov, conmov, nomproben, monto, estbpd, estcon, estcobing, esttra, chevau, estimpche, ".
				"                       monobjret, monret, procede, comprobante, fecha, id_mco, emicheproc, emicheced, emichenom, ".
				"                       emichefec, estmovint, codusu, codopeidb, aliidb, feccon, estreglib, numcarord, numpolcon,".
				"                       coduniadmsig,codbansig,fecordpagsig,tipdocressig,  numdocressig,estmodordpag,codfuefin,".
				"                       forpagsig,medpagsig,codestprosig) ".
				" VALUES ('".$this->is_codemp."','".$as_codban."','".$as_ctaban."','".$ls_comprobante_sigesp."','".$ls_codope."','".$ls_estmov."','----------','----------',".
				"         '-','---','".$adt_fecha."','".$as_descripcion."','Ninguno',0,".
  				"         'M',0,1,0,' ',0,0,0,' ',' ','1900-01-01',' ',0,' ',' ','1900-01-01',0,'ninguno',".
 				"         ' ',0,'1900-01-01',0,' ',0,' ',' ','1900-01-01',' ',' ',0,' ',' ',' ',' ')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           $this->io_msg->message("CLASE->Integración SFC 1 MÉTODO->uf_insert_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));		
		   print $this->io_sql->message;
		   $lb_valido=false;
		}
		if($lb_valido)
		{
				$ls_sql="SELECT sc_cuenta, monto, descripcion, numdoc ".
						"  FROM mis_sigesp_banco ".
						" WHERE codemp='".$this->is_codemp."' ".
						"   AND comprobante='".$as_comprobante."' ".
						"   AND procede='".$as_procede."' ".
						"   AND fecdep='".$adt_fecha."' ".
						"   AND codban='".$as_codban."' ".
						"   AND ctaban='".$as_ctaban."' ".
						"   AND modulo='SPI' ".
						" ORDER BY sc_cuenta ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{   
				$this->io_msg->message("CLASE->Integración SFC 2 MÉTODO->uf_insert_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				$lb_valido=false;
			}
			else
			{           
				while((!$rs_data->EOF) && ($lb_valido))
				{
					$ls_cuenta=$rs_data->fields["sc_cuenta"];
					$ldec_monto=number_format($rs_data->fields["monto"],2,".","");
					$ls_descripcion=$rs_data->fields["descripcion"];	
					$ls_numdoc=$rs_data->fields["numdoc"];	
					$ld_montototal=$ld_montototal+$ldec_monto;
					$ls_sql="INSERT INTO scb_movbco_spi (codemp, codban, ctaban, numdoc, codope, estmov, spi_cuenta, documento, operacion, ".
							"                            desmov, procede_doc, monto, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, estcla) ".
							"VALUES ('".$this->is_codemp."','".$as_codban."',"."'".$as_ctaban."','".$ls_comprobante_sigesp."','".$ls_codope."','".$ls_estmov."', ".
							"        '".$ls_cuenta."','".$ls_numdoc."','".$ls_operacion."','".$ls_descripcion."','".$as_procede."',".$ldec_monto.",'".$ls_codestpro."', ".
							"        '".$ls_codestpro."','".$ls_codestpro."','".$ls_codestpro."','".$ls_codestpro."','".$ls_estcla."' )";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$this->io_msg->message("CLASE->Integración SFC 3 MÉTODO->uf_insert_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
						print $this->io_sql->message;
						$lb_valido=false;
						break;
					}
					$rs_data->MoveNext();
				} // end while
			}
		}
		if($lb_valido)
		{
				$ls_sql="SELECT sc_cuenta, debhab, monto,descripcion,numdoc ".
						"  FROM mis_sigesp_banco ".
						" WHERE codemp='".$this->is_codemp."' ".
						"   AND comprobante='".$as_comprobante."' ".
						"   AND procede='".$as_procede."' ".
						"   AND fecdep='".$adt_fecha."' ".
						"   AND codban='".$as_codban."' ".
						"   AND ctaban='".$as_ctaban."' ".
						"   AND modulo='SCB' ".
						" ORDER BY debhab";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{   
				$this->io_msg->message("CLASE->Integración SFC 4 MÉTODO->uf_insert_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));	
				$lb_valido=false;
			}
			else
			{           
				while((!$rs_data->EOF) && ($lb_valido))
				{
					$ls_scg_cuenta = $rs_data->fields["sc_cuenta"];
					$ldec_monto = number_format($rs_data->fields["monto"],2,".","");				
					$ls_debhab = $rs_data->fields["debhab"];				
					$ls_descripcion=$rs_data->fields["descripcion"];				
					$ls_numdoc=$rs_data->fields["numdoc"];	
					$ls_sql="INSERT INTO scb_movbco_scg (codemp, codban, ctaban, numdoc, codope, estmov, scg_cuenta, debhab, codded, ".
							"documento, desmov, procede_doc, monto, monobjret) VALUES ('".$this->is_codemp."','".$as_codban."',".
							"'".$as_ctaban."','".$ls_comprobante_sigesp."','".$ls_codope."','".$ls_estmov."','".$ls_scg_cuenta."','".$ls_debhab."','00000',".
							"'".$ls_numdoc."','".$ls_descripcion."','".$as_procede."',".$ldec_monto.",0)";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$this->io_msg->message("CLASE->Integración SFC 5 MÉTODO->uf_insert_recepcion_documento_contable ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
						$lb_valido=false;
						break;
					}
					$rs_data->MoveNext();
				} // end while
			}
		}
		if($lb_valido)
		{
			$ldec_monto = number_format($ld_montototal,2,".","");				
			$ls_sql="UPDATE scb_movbco ".
					"   SET monto = ".$ld_montototal." ".
					" WHERE codemp='".$this->is_codemp."' ".
					"   AND codban='".$as_codban."' ".
					"   AND ctaban='".$as_ctaban."' ".
					"   AND numdoc='".$as_comprobante."' ".
					"   AND codope='".$ls_codope."' ".
					"   AND estmov='".$ls_estmov."' ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{   
				$this->io_msg->message("CLASE->Integración SFC 6 MÉTODO->uf_insert_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				$lb_valido=false;
			}
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_estatus_pagos($as_comprobante,$adt_fecha,$as_procede,$as_codban,$as_ctaban,1,$ls_comprobante_sigesp,$adt_fecha,'1900-01-01');
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Contabilizó el pago <b>".$as_comprobante."-".$as_codban."-".$as_ctaban."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);		
		return  $lb_valido;
	}  // end function uf_procesar_contabilizacion_pagos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reversar_contabilizacion_pagos($as_comprobante,$adt_fecha,$as_procede,$as_codban,$as_ctaban,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar_contabilizacion_pagos
		//		   Access: public (sigesp_mis_p_contabiliza_sfc_pagos.php)
		//	    Arguments: as_comprobante  // Código de Comprobante
		//				   adt_fecha  // Fecha de contabilización
		//				   as_procede  // Procede
		//				   as_codban  // Còdigo de Banco
		//				   as_ctaban  // Cuenta Banco
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se eliminó correctamente
		//	  Description: Este metodo elimina un registro de movimiento de banco
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: 													Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$this->io_sigesp_int->uf_int_init_transaction_begin();
		$ls_comprobante_sigesp = $this->io_sigesp_int->uf_fill_comprobante(trim($as_comprobante));
		$adt_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
		$ls_codope='DP';
		$ls_estmov='N';
		$ls_sql="DELETE ".
				"  FROM scb_movbco_spi ".
		        " WHERE codemp='".$this->is_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
                "   AND numdoc='".$ls_comprobante_sigesp."' ".
				"   AND codope='".$ls_codope."' ".
				"   AND estmov='".$ls_estmov."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           $this->io_msg->message("CLASE->Integración SFC MÉTODO->uf_delete_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		   $lb_valido=false;
		}
		if($lb_valido)
		{
			$ls_sql="DELETE ".
					"  FROM scb_movbco_scg ".
					" WHERE codemp='".$this->is_codemp."' ".
					"   AND codban='".$as_codban."' ".
					"   AND ctaban='".$as_ctaban."' ".
					"   AND numdoc='".$ls_comprobante_sigesp."' ".
					"   AND codope='".$ls_codope."' ".
					"   AND estmov='".$ls_estmov."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{   
			   $this->io_msg->message("CLASE->Integración SCF MÉTODO->uf_delete_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			   $lb_valido=false;
			}
		}
		if($lb_valido)
		{
			$ls_sql="DELETE ".
					"  FROM scb_movbco ".
					" WHERE codemp='".$this->is_codemp."' ".
					"   AND codban='".$as_codban."' ".
					"   AND ctaban='".$as_ctaban."' ".
					"   AND numdoc='".$ls_comprobante_sigesp."' ".
					"   AND codope='".$ls_codope."' ".
					"   AND estmov='".$ls_estmov."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{   
			   $this->io_msg->message("CLASE->Integración SFC MÉTODO->uf_delete_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			   $lb_valido=false;
			}
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_estatus_pagos($as_comprobante,$adt_fecha,$as_procede,$as_codban,$as_ctaban,0,'','1900-01-01','1900-01-01');
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reverso el pago <b>".$as_comprobante."-".$as_codban."-".$as_ctaban."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);		
		return $lb_valido;
    } // end uf_reversar_contabilizacion_pagos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_pagos($as_comprobante,$adt_fecha,$as_procede,$as_codban,$as_ctaban,$ai_estatus,$as_comprobante_sigesp,$adt_fechaconta,$adt_fechaanula)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_pagos
		//		   Access: private
		//	    Arguments: as_comprobante  // Código de Comprobante
		//				   adt_fecha  // Fecha de contabilización
		//				   as_procede  // Procede
		//				   as_codban  // Còdigo de Banco
		//				   as_ctaban  // Cuenta Banco
		//				   ai_estatus  // estatus si es 0 ó 1
		//	      Returns: lb_valido True si se actualizó correctamente
		//	  Description: Método que actualiza el estatus del pago en contabilizad o no 
		//	   Creado Por: Ing. Luis Lang
		// Modificado Por: 							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;	
		$ls_sql="UPDATE mis_sigesp_banco ".
				"   SET estint=".$ai_estatus.", ".
				"       comprobante_sigesp='".$as_comprobante_sigesp."',".
				"       fechaconta='".$adt_fechaconta."', ".
				"       fechaanula='".$adt_fechaanula."' ".
				" WHERE codemp='".$this->is_codemp."' ".
				"   AND comprobante='".$as_comprobante."' ".
				"   AND procede='".$as_procede."' ".
				"   AND fecdep='".$adt_fecha."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Integración SFC MÉTODO->uf_update_estatus_pagos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_estatus_pagos
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>