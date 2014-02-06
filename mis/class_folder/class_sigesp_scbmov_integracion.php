<?php
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  //       Class : class_sigesp_scb_integracion_php                                                     //    
  // Description : Esta clase tiene todos los metodos necesario para el manejo de la rutina integradora //
  //               de los distintos movimientos de banco                                                //               
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
class class_sigesp_scbmov_integracion
{
	//Instancia de la clase funciones.
    var $is_msg_error;
	var $dts_empresa; 
	var $dts_banco;
	var $dts_proveedor;
	var $dts_beneficiario;
	var $obj="";
	var $io_sql;
	var $io_siginc;
	var $io_conect;
	var $io_function;	
    var $io_sigesp_int;
	var $io_sigesp_int_spg;
	var $io_sigesp_int_scg;	
	var $io_sigesp_int_cxp;	
	var $io_fecha;
	var $idt_date;
	var $io_msg;
	var $is_codemp="";
	var $is_codban="";
	var $is_ctaban="";
	var $is_numdoc="";
	var $is_codope="";
	var $is_estmov="";
	var $is_procede="";
	var $is_procede_doc="";	
	var $is_mensaje_spi="";	
	var $is_mensaje_spg="";	
    var $ii_datasource=0;	
	var $is_comprobante;
	var $idt_fecha;
	var $is_estbpd="";
	var $ii_cobra=0; 
	var $ii_compromiso_previo=false;
	var $is_documento="";
	var $is_spg_cuenta="";
	var $is_estmodordpag="";
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function class_sigesp_scbmov_integracion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: class_sigesp_scbmov_integracion
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_sql.php");  
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/class_sigesp_int.php");
		require_once("../shared/class_folder/class_sigesp_int_int.php");
		require_once("../shared/class_folder/class_sigesp_int_spg.php");
		require_once("../shared/class_folder/class_sigesp_int_scg.php");
		require_once("../shared/class_folder/class_sigesp_int_spi.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("class_sigesp_cxp_integracion.php");  
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_fecha.php");
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
		$this->dts_banco=new class_datastore();
		$this->dts_beneficiario=new class_datastore();
		$this->dts_proveedor=new class_datastore();
		$this->io_msg=new class_mensajes();		
		$this->io_sigesp_int_spg = new class_sigesp_int_spg();
		$this->io_sigesp_int_scg = new class_sigesp_int_scg();		
		$this->io_sigesp_int_spi = new class_sigesp_int_spi();		
		$this->io_sigesp_int_cxp = new class_sigesp_cxp_integracion();				
		$this->io_seguridad=new sigesp_c_seguridad();		
		$this->as_procede="";
		$this->as_comprobante="";
		$this->ad_fecha="";
		$this->as_codban="";
		$this->as_ctaban="";
	}// end function class_sigesp_scbmov_integracion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destroy_objects()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destroy_objects
		//		   Access: public 
		//	  Description: Destructor de los objectos de la Clase
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   // if( is_object($this->io_fecha) ) { unset($this->io_fecha);  }
        if( is_object($this->io_sigesp_int) ) { unset($this->io_sigesp_int);  }
	    if( is_object($this->io_function) ) { unset($this->io_function);  }
	    if( is_object($this->io_siginc) ) { unset($this->io_siginc);  }
	    if( is_object($this->io_connect) ) { unset($this->io_connect);  }
	    if( is_object($this->io_sql) ) { unset($this->io_sql);  }	   
	    if( is_object($this->obj) ) { unset($this->obj);  }	   
	    if( is_object($this->dts_empresa) ) { unset($this->dts_empresa);  }	   
	    if( is_object($this->io_msg) ) { unset($this->io_msg);  }	   
        if( is_object($this->io_sigesp_int_cxp) ) { unset($this->io_sigesp_int_cxp);  }	   	   
        if( is_object($this->io_sigesp_int_spg) ) { unset($this->io_sigesp_int_spg);  }	   	   
        if( is_object($this->io_sigesp_int_scg) ) { unset($this->io_sigesp_int_scg);  }	   	   
        if( is_object($this->io_seguridad) ) { unset($this->io_seguridad);  }	   	   
	}// end function uf_destroy_objects
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_contabilizacion_banco($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$adt_fecha,$ai_feccondep,
											   $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contabilizacion_banco
		//		   Access: public (sigesp_mis_p_contabiliza_scb.php)
		//	    Arguments: as_codban  // Código de Banco
		//				   as_ctaban  // Cuenta Bancaria
		//				   as_numdoc  // Número de Documento
		//				   as_codope  // Código de Operación
		//				   as_estmov  // Estatus del Movimiento
		//				   adt_fecha  // Fecha de contabilización
		//				   ai_feccondep  // Fecha de contabilización de los depósitos
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Funcion que procesa la contabilización dado un comprobante
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_scg_cuenta_banco="";
		$adt_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
		$this->is_codemp=$this->io_codemp;
	    $this->is_codban=$as_codban;
	    $this->is_ctaban=$as_ctaban;
	    $this->is_numdoc=$as_numdoc;
	    $this->is_codope=$as_codope;
	    $this->is_estmov=$as_estmov;
	    $this->is_procede="SCBB".$as_codope;
	    $this->is_procede_doc="SCBB".$as_codope;	
	    $this->is_mensaje_spi="EC";	
	    $this->is_comprobante=trim($this->is_numdoc);		
		$this->ii_compromiso_previo=false;
		$this->is_documento="";
        $this->idt_date=$adt_fecha;
		// Verifico si el comprobante existe y de ser así lo cargo en el datastored
        $lb_valido=$this->uf_obtener_data_movimento_banco();
        if(!$lb_valido)
		{
			return false;
		} 
		// Verifico si la cuenta del banco posee cuenta contable y si la tiene la obtengo
        $lb_valido=$this->uf_obtener_codigo_contable_cuentabanco(&$ls_scg_cuenta_banco);		
        if(!$lb_valido)
		{
			return false;
		} 		
        $this->is_estbpd=$this->dts_banco->getValue("estbpd",1);
		$this->ii_cobra=$this->dts_banco->getValue("estcobing",1);
        $ls_tipo=$this->dts_banco->getValue("tipo_destino",1);   		
		$ls_ced_bene=$this->dts_banco->getValue("ced_bene",1);
		$ls_cod_pro=$this->dts_banco->getValue("cod_pro",1);
		$ls_descripcion=$this->dts_banco->getValue("conmov",1);
		$ldt_fecha=$this->io_function->uf_formatovalidofecha($this->dts_banco->getValue("fecmov",1));
        $this->idt_date=$this->io_function->uf_convertirdatetobd($ldt_fecha);
		$this->is_spg_cuenta=$ls_scg_cuenta_banco;		
        if(($this->is_estbpd=="B")||($this->is_estbpd=="P")) 
		{
			$this->ii_datasource=4;
			$this->is_mensaje_spg="P";	  
		}
        else
        {
			$this->ii_datasource=1;
			$this->is_mensaje_spg="OCP";	  
		}
        if(($this->is_estbpd=="R")||($this->is_estbpd=="O")||($this->is_estbpd=="C")) 
		{
			$this->io_msg->message("El Pago directo con Compromiso/Causado Previo no es soportado!");			
			return false;
        }		
		if(($as_codope=="DP")&&($ai_feccondep==1))
		{
			$ldt_fecha=$adt_fecha;
		}
		if($ls_ced_bene=="----------")
		{
			$ls_fuente=$ls_cod_pro;
		}
		else
		{
			$ls_fuente=$ls_ced_bene;
		}
        $lb_autoconta=true;
        if ($this->ii_datasource==1)  
		{ 
		   $this->io_sigesp_int->uf_int_config(false,false); 
		   $lb_autoconta = false;
		}
		$li_tipo_comp=1; // comprobante Normal
		$this->as_procede=$this->is_procede;
		$this->as_comprobante=$this->is_comprobante;
		$this->ad_fecha=$ldt_fecha;
		$this->as_codban=$as_codban;
		$this->as_ctaban=$as_ctaban;
		// inicia transacción SQL
		$this->io_sigesp_int->uf_int_init_transaction_begin(); 
		// Creo el la cabecera del comprobante
		$lb_valido=$this->io_sigesp_int->uf_int_init($this->is_codemp,$this->is_procede,$this->is_comprobante,$ldt_fecha,
													 $ls_descripcion,$ls_tipo,$ls_fuente,$lb_autoconta,$as_codban,$as_ctaban,
													 $li_tipo_comp);
		if ($lb_valido===false)
		{   
			$this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error); 
			return false;		   		   
		}
		// se buscan los detalles de presupuesto y se insertan en el datastored
		$lb_valido=$this->uf_procesar_gastos($ls_descripcion);
		if (!$lb_valido)
		{   
           $this->io_msg->message("ERROR-> En insertar movimiento de gastos"); 
		   return false;		   		   
		}
		// se buscan los detalles de contabilidad y se insertan en el datastored
        $lb_valido=$this->uf_procesar_contable($ls_descripcion,$this->is_procede);
		if (!$lb_valido)
		{   
           $this->io_msg->message("ERROR-> En insertar movimientos contables"); 
		   return false;		   		   
		}
        $lb_valido = $this->uf_procesar_ingresos($ls_descripcion);
		if (!$lb_valido)
		{   
           $this->io_msg->message("ERROR-> En insertar movimientos de Ingreso"); 
		   return false;		   		   
		}
		if(!$lb_valido)
		{
			return false;
		}
	    if($lb_valido)
	    {
			// Inserta en contabilidad y presupuestos
	        $lb_valido=$this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
	        if($lb_valido===false)
		    {
				$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
				$this->io_sigesp_int->uf_sql_transaction($lb_valido);
				return false;		   		   
		    }		   
		}
		if ($lb_valido)
		{   
			// Procesa la Programación de pagos si viene de cuentas por pagar	
			$lb_valido=$this->uf_procesar_programacion_pagos($ldt_fecha);
		}
		if (!$lb_valido)
		{   
			$this->io_msg->message("ERROR-> En programación de pago"); 
			$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		    return false;		   		   
		}
		// Inserta los nuevos movimientos a banco	
		$lb_valido=$this->uf_create_movimiento_banco($this->io_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,"C",$this->is_procede,$this->is_comprobante,$this->idt_date,$ldt_fecha);
		if (!$lb_valido)
		{   
			$this->io_msg->message("ERROR-> En crear los movimientos a banco"); 
			$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		    return false;		   		   
		}
		// Elimina los movimientos anteriores
		$lb_valido = $this->uf_delete_movimiento_banco($this->io_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov);
		if (!$lb_valido)
		{   
            $this->io_msg->message("ERROR-> En eliminar los movimientos a banco"); 
			$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		    return false;		   		   
		}
	    if($lb_valido)
	    {
			$lb_valido=$this->uf_update_fecha_contabilizado_scbmov($this->io_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,
																   "C",$ldt_fecha,'1900-01-01','');
		}
	    if($lb_valido)
	    {
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Contabilizó el Documento <b>".$as_numdoc."</b>, Banco <b>".$as_codban."</b>, ".
							"Cuenta Banco <b>".$as_ctaban."</b>, Fecha de Contabilización <b>".$ldt_fecha."</b>";
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
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_spidtcmp($this->as_procede,$this->as_comprobante,$this->ad_fecha,
																$this->as_codban,$this->as_ctaban,$aa_seguridad);
		}*/
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
    }// end function uf_procesar_contabilizacion_banco
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_data_movimento_banco()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_data_movimento_banco
		//		   Access: private
		//	    Arguments: 
		//	      Returns: lb_valido True si se encontro el movimiento ó false si no se encontro
		//	  Description: Funcion que obtiene los datos del movimiento de banco
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 02/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe=false;		
		$ls_codemp=$this->is_codemp;
		$ls_sql="SELECT * ".
                "  FROM scb_movbco ".
                " WHERE codemp='".$this->is_codemp."' ".
				"   AND codban='".$this->is_codban."' ".
				"   AND ctaban='".$this->is_ctaban."' ".
				"   AND numdoc='".$this->is_numdoc."' ".
				"   AND codope='".$this->is_codope."' ".
				"   AND estmov='".$this->is_estmov."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_obtener_data_movimento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true; // si existe se procedera a registrar en el datastore.				
                $this->dts_banco->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
            	$this->io_msg->message("ERROR-> El Comprobante ".$this->is_numdoc." no existe.");			
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}// end function uf_obtener_data_movimento_banco
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_codigo_contable_cuentabanco(&$as_scg_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_codigo_contable_cuentabanco
		//		   Access: private
		//	    Arguments: as_scg_cuenta // Número de cuenta contable 
		//	      Returns: lb_valido True si se encontro el movimiento ó false si no se encontro
		//	  Description: Funcion que obtiene el código contable de la cuenta del banco
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 02/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe=false;		
		$lb_valido=true;		
		$ls_codemp=$this->is_codemp;
		$ls_sql="SELECT sc_cuenta ".
                "  FROM scb_ctabanco ".
                " WHERE codemp='".$this->is_codemp."' ".
				"   AND codban='".$this->is_codban."' ".
				"   AND ctaban='".$this->is_ctaban."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_obtener_codigo_contable_cuentabanco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			if($row=$this->io_sql->fetch_row($rs_data))
		    {
				$lb_existe=true;
			    $as_scg_cuenta=$row["sc_cuenta"];
			}
			else
			{
	            $this->io_msg->message("ERROR-> La cuenta ".$this->is_ctaban." del banco".$this->is_codban." no posee código contable.");			
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}// end function uf_obtener_codigo_contable_cuentabanco
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_gastos($as_descripcion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_gastos
		//		   Access: private
		//	    Arguments: as_descripcion // Descripción del comprobante
		//	      Returns: lb_valido True si se encontro el movimiento ó false si no se encontro
		//	  Description: Funcion que obtiene el los movimientos de presupuesto y los agrega al datastored
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 02/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_entro_documento = false;
	    if($this->is_estbpd=="M")
		{
			if($this->ii_cobra==0)
			{
				$ls_mensaje="OCP";
			} 
			else
			{
				$ls_mensaje="P";
			} 
		}
		else
		{
			$ls_mensaje=$this->is_mensaje_spg;
		}			
		if ($this->ii_compromiso_previo)
		{
			$ls_procede=$this->is_procede_doc;
			$ls_documento=$this->is_documento;
        }
		else
 	    { 
			$ls_procede="CXPSOP";
			$lb_entro_documento=true;		   
		}
		if($this->ii_datasource==4)
		{
			$ls_procede="CXPSOP";
        }
		$ls_sql="SELECT * ".
                "  FROM scb_movbco_spg ".
                " WHERE codemp='".$this->is_codemp."' ".
				"   AND codban='".$this->is_codban."' ".
				"   AND ctaban='".$this->is_ctaban."' ".
				"   AND numdoc='".$this->is_numdoc."' ".
				"   AND codope='".$this->is_codope."' ".
				"   AND estmov='".$this->is_estmov."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
            $this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_procesar_gastos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
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
			    $ls_procede=$row["procede_doc"];
 			    $ls_documento=$row["documento"];
				$ls_mensaje=$this->io_sigesp_int_spg->uf_operacion_codigo_mensaje($row["operacion"]);
				$ls_spg_cuenta=$this->io_sigesp_int_spg->uf_spg_pad_cuenta($ls_spg_cuenta);
				$lb_valido=$this->io_sigesp_int->uf_spg_insert_datastore($this->is_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
									                                     $ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,$ls_mensaje,
									                                     $ldec_monto,$ls_documento,$ls_procede,$as_descripcion);
				if ($lb_valido===false)
				{  
		            $this->io_msg->message("ERROR->".$this->io_sigesp_int->is_msg_error);			
				    break;
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
    }// end function uf_procesar_gastos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_contable($as_descripcion,$as_procede)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contable
		//		   Access: private
		//	    Arguments: as_descripcion // Descripción del comprobante
		//	    		   as_procede // Procede del comprobante
		//	      Returns: lb_valido True si se encontro el movimiento ó false si no se encontro
		//	  Description: Funcion que obtiene el los movimientos de contabilidad y los agrega al datastored
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 02/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
	    $lb_entro_documento=false;
		if ($this->ii_compromiso_previo)
		{
			$ls_procede=$this->is_procede_doc;
			$lb_entro_documento=true;
        }
		else
 	    { 
			$ls_procede="CXPSOP";
			$lb_entro_documento=true;		   
		}
		if($this->ii_datasource=4)
		{
			$ls_procede="CXPSOP";
			$lb_entro_documento=true;
        }
		$ls_sql="SELECT * ".
                "  FROM scb_movbco_scg ".
                " WHERE codemp='".$this->is_codemp."' ".
				"   AND codban='".$this->is_codban."' ".
				"   AND ctaban='".$this->is_ctaban."' ".
				"   AND numdoc='".$this->is_numdoc."' ".
				"   AND codope='".$this->is_codope."' ".
				"   AND estmov='".$this->is_estmov."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
            $this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_procesar_contable ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			while($row=$this->io_sql->fetch_row($rs_data) and ($lb_valido))
		    {
				$ls_scg_cuenta=$row["scg_cuenta"];
                $ls_debhab=$row["debhab"];				
				$ldec_monto=$row["monto"];				
				$ls_documento=$row["documento"];
			    $ls_procede=$row["procede_doc"];				
				$ls_scg_cuenta=$this->io_sigesp_int_scg->uf_pad_scg_cuenta( $this->dts_empresa["formcont"],$ls_scg_cuenta);
				$lb_valido=$this->io_sigesp_int->uf_scg_insert_datastore($this->is_codemp,$ls_scg_cuenta,$ls_debhab,$ldec_monto,$ls_documento,$ls_procede,$as_descripcion);
				if($lb_valido===false)
				{  
					$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
					break;
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
    }// end function uf_procesar_contable
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_ingresos($as_descripcion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_ingresos
		//		   Access: private
		//	    Arguments: as_descripcion // Descripción
		//	      Returns: lb_valido True si se encontro el movimiento ó false si no se encontro
		//	  Description: Este metodo ejecuta el nucleo integrador del módulo de banco
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 03/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * ".
                "  FROM scb_movbco_spi ".
                " WHERE codemp='".$this->is_codemp."' ".
				"   AND codban='".$this->is_codban."' ".
				"   AND ctaban='".$this->is_ctaban."' ".
				"   AND numdoc='".$this->is_numdoc."' ".
				"   AND codope='".$this->is_codope."' ".
				"   AND estmov='".$this->is_estmov."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
            $this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_procesar_ingresos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{    
			while($row=$this->io_sql->fetch_row($rs_data) and ($lb_valido))
		    {
				$ls_spi_cuenta=$row["spi_cuenta"];
				$ldec_monto=$row["monto"];
				$ls_documento=$row["documento"];
				$ls_procede=$row["procede_doc"];	
				$ls_operacion=$row["operacion"];
				///---información de la estructuras presupuestarias relacionadas a las cuentas de ingresos
				$ls_codestpro1=$row["codestpro1"];
				$ls_codestpro2=$row["codestpro2"];
				$ls_codestpro3=$row["codestpro3"];
				$ls_codestpro4=$row["codestpro4"];
				$ls_codestpro5=$row["codestpro5"];
				$ls_estcla=$row["estcla"];					
				//------------------------------------------------------------------------------------------------------
				$ls_mensaje=$this->io_sigesp_int_spi->uf_operacion_codigo_mensaje($row["operacion"]);
				$ls_spi_cuenta=$this->io_sigesp_int_spi->uf_spi_pad_cuenta($ls_spi_cuenta);
				$lb_valido=$this->io_sigesp_int->uf_spi_insert_datastore($this->is_codemp,$ls_spi_cuenta,$ls_operacion,
																		 $ldec_monto,$ls_documento,$ls_procede,$as_descripcion,
																		 $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																		 $ls_codestpro4,$ls_codestpro5,$ls_estcla);
				if ($lb_valido===false)
				{  
					$this->io_msg->message("ERROR ->".$this->io_sigesp_int->is_msg_error);
					break;
				}
			}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
    }// end function uf_procesar_ingresos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_programacion_pagos($adt_fecha)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_programacion_pagos
		//		   Access: private
		//	    Arguments: adt_fecha // Fecha para programar los pago
		//	      Returns: lb_valido True si se encontro el movimiento ó false si no se encontro
		//	  Description: Funcion que actualiza la información de las programaciones de pago de una solicitud 
		//                  y verifica su información de pago total o parcial
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 02/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
	    $adt_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
		if(($this->ii_datasource==4)&&(!$this->ii_compromiso_previo))
		{
		    $lo_sql=new class_sql($this->io_connect);
			$ls_sql="SELECT * ".
					" FROM cxp_sol_banco ".
					" WHERE codemp='".$this->is_codemp."' ".
					"   AND codban='".$this->is_codban."' ".
					"   AND ctaban='".$this->is_ctaban."' ".
					"   AND numdoc='".$this->is_numdoc."' ".
					"   AND codope='".$this->is_codope."' ".
					"   AND estmov='".$this->is_estmov."' ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{   
	            $this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_procesar_programacion_pagos 1 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				return false;
			}
			else
			{                 
				while($row=$this->io_sql->fetch_row($rs_data) and ($lb_valido))
				{
				    $ls_numsol=$row["numsol"];
					$ls_sql_tmp="SELECT SUM(b.monto) as monto_bco, 0 as monto_sol, 0 as monto_actual,0 as monto_nc, 0 as monto_nd ".
                                "  FROM cxp_sol_banco b,cxp_solicitudes s ".
                                "  WHERE b.codemp=s.codemp AND b.numsol=s.numsol AND ".
							    "        b.codemp='".$this->is_codemp."' AND b.numsol='".$ls_numsol."' AND b.estmov='C' ".
								" UNION ".
                                "SELECT 0 as monto_bco, s.monsol as monto_sol, 0 as monto_actual,0 as monto_nc, 0 as monto_nd ".
                                "  FROM cxp_sol_banco b,cxp_solicitudes s ".
                                " WHERE b.codemp=s.codemp AND b.numsol=s.numsol AND ".
							    "       b.codemp='".$this->is_codemp."' AND b.numsol='".$ls_numsol."' AND numdoc ='".$this->is_numdoc."' ".
								" UNION ".
                                "SELECT 0 as monto_bco, 0 as monto_sol, b.monto as monto_actual,0 as monto_nc, 0 as monto_nd ".
                                "  FROM cxp_sol_banco b,cxp_solicitudes s ".
                                " WHERE b.codemp=s.codemp AND b.numsol=s.numsol AND ".
							    "       b.codemp='".$this->is_codemp."' AND b.numsol='".$ls_numsol."' AND numdoc ='".$this->is_numdoc."' ".	
								" UNION ".
                                "SELECT 0 as monto_bco, 0 as monto_sol, 0 as monto_actual, SUM(monto) as monto_nc, 0 as monto_nd".
                                "  FROM cxp_sol_dc ".
                                " WHERE cxp_sol_dc.codemp='".$this->is_codemp."' AND cxp_sol_dc.numsol='".$ls_numsol."' AND codope ='NC' ".	
								" UNION ".
                                "SELECT 0 as monto_bco, 0 as monto_sol, 0 as monto_actual,  0 as monto_nc, SUM(monto) as monto_nd".
                                "  FROM cxp_sol_dc ".
                                " WHERE cxp_sol_dc.codemp='".$this->is_codemp."' AND cxp_sol_dc.numsol='".$ls_numsol."' AND codope ='ND' ";	
			        $rs_data_tmp=$lo_sql->select($ls_sql_tmp);
					if($rs_data_tmp===false)
					{   
		            	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_procesar_programacion_pagos 2 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
						return false;
					}
					else
					{    
						$lb_valido=true;  
						$ldec_monto_bco=0; 
						$ldec_monto_actual=0; 
						$ldec_monto_sol=0;         
						$ldec_monto_nc=0;         
						$ldec_monto_nd=0;         
						while(($row_tmp=$lo_sql->fetch_row($rs_data_tmp))&&($lb_valido))
						{
							$ldec_monto_bco=$ldec_monto_bco+number_format($row_tmp["monto_bco"],2,".","");
							$ldec_monto_actual=$ldec_monto_actual+number_format($row_tmp["monto_actual"],2,".","");
							$ldec_monto_sol=$ldec_monto_sol+number_format($row_tmp["monto_sol"],2,".","");
							$ldec_monto_nc=$ldec_monto_nc+number_format($row_tmp["monto_nc"],2,".","");
							$ldec_monto_nd=$ldec_monto_nd+number_format($row_tmp["monto_nd"],2,".","");
								
							$ldec_total=($ldec_monto_bco+$ldec_monto_actual+$ldec_monto_nc-$ldec_monto_nd);
						}
						if($ldec_total==$ldec_monto_sol) 
						{ 
							$lb_valido=$this->uf_update_estatus_solicitud_cancelado($ls_numsol,"P");
							if ($lb_valido)
							{
								$lb_valido=$this->io_sigesp_int_cxp->uf_delete_historico_pagado($this->is_codemp,$ls_numsol);
								if($lb_valido)
								{
									$lb_valido=$this->io_sigesp_int_cxp->uf_insert_historico_contabilizacion($this->is_codemp,$ls_numsol,$adt_fecha,"P"); 
								}
							}
							if($lb_valido)
							{
								$lb_valido = $this->uf_update_estatus_programacion_pago($ls_numsol,"C");
							}
						} 
						else
						{
							$lb_valido = $this->uf_update_estatus_solicitud_cancelado($ls_numsol,"S");
						}
					}		 
					$lo_sql->free_result($rs_data_tmp);
				}
            }		
            unset($lo_sql);			
		    $this->io_sql->free_result($rs_data);			
		}
		return $lb_valido;
    }// end function uf_procesar_programacion_pagos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_solicitud_cancelado($as_numsol,$as_estatus)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_solicitud_cancelado
		//		   Access: private
		//	    Arguments: as_numsol // Número de Solicitud
		//	    		   as_estatus // estatus de la Solicitud
		//	      Returns: lb_valido True si se encontro el movimiento ó false si no se encontro
		//	  Description: Método que actualiza en cancelado la solicitud de pago, cuando la sumatoria de los cheques sean 
		//                  iguales al total de la solicitud
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 02/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql="UPDATE cxp_solicitudes ".
		        "   SET estprosol='".$as_estatus."'".
                " WHERE codemp='".$this->is_codemp."' ".
				"   AND numsol='".$as_numsol."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
           	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_update_estatus_solicitud_cancelado ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_estatus_solicitud_cancelado()
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_programacion_pago($as_numsol,$as_estatus)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_programacion_pago
		//		   Access: private
		//	    Arguments: as_numsol // Número de Solicitud
		//	    		   as_estatus // estatus de la Solicitud
		//	      Returns: lb_valido True si se encontro el movimiento ó false si no se encontro
		//	  Description: Método que actualiza en estatus cancelado la programacion de pago
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 03/11/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql="UPDATE scb_prog_pago ".
		        "   SET estmov='".$as_estatus."'".
                " WHERE codemp='".$this->is_codemp."' ".
				"   AND numsol='".$as_numsol."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
           	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_update_estatus_programacion_pago ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_estatus_programacion_pago
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_movimiento_banco($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$as_estmov_new,
										$as_procede,$as_comprobante,$adt_fecmov,$adt_fecha)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_movimiento_banco
		//		   Access: private
		//	    Arguments: as_codemp // Código de Empresa
		//	    		   as_codban // Código de Banco
		//	    		   as_ctaban // Cuenta Banco
		//	    		   as_numdoc // Número de Documento
		//	    		   as_codope // Código de Operación
		//	    		   as_estmov // estatus del Movimiento
		//	    		   as_estmov_new // Nuevo estatus del Movimiento
		//	    		   as_procede // Procede del documento
		//	    		   as_comprobante // comprobante
		//	    		   adt_fecha // Fecha para contabilizar
		//	      Returns: lb_valido True si se encontro el movimiento ó false si no se encontro
		//	  Description: Funcion que crea un nuevo registro de banco al cambiar el estatus del mismo
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 02/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		// Verifico si el movimiento existe con el estatus anterior
		$lb_existe=$this->uf_select_movimiento_banco($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov);
		if (!$lb_existe)
		{
            $this->io_msg->message("ERROR -> El movimiento no existe Banco=".$as_codban." Cuenta=".$as_ctaban." documento=".$as_numdoc." operación=".$as_codope." estatus=".$as_estmov);		
			return false;
		}
		// Verifico si el movimiento ya existe con el estatus nuevo
		$lb_existe=$this->uf_select_movimiento_banco($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov_new);
		if ($lb_existe)
		{
            $this->io_msg->message("ERROR -> El movimiento ya existe Banco=".$as_codban." Cuenta=".$as_ctaban." documento=".$as_numdoc." operación=".$as_codope." estatus=".$as_estmov_new);		
			return false;			
		}
        // transferencia al nuevo registro de banco
		$ls_sql = "INSERT INTO scb_movbco (codemp,codban,ctaban,numdoc,codope,estmov,cod_pro,ced_bene,".
		          "                        tipo_destino, codconmov, fecmov, conmov, nomproben, monto, ".
				  "                        estbpd, estcon, estcobing, esttra, chevau, estimpche, ".
				  "                        monobjret, monret, procede, comprobante, fecha, id_mco,".
				  "                        emicheproc, emicheced, emichenom, emichefec, estmovint, ".
				  "                        codusu, codopeidb, aliidb, feccon, estreglib, numcarord,".
				  "                        numpolcon,coduniadmsig,codbansig,fecordpagsig,tipdocressig,".
				  "                        numdocressig,estmodordpag,codfuefin,forpagsig,medpagsig,codestprosig,".
				  "						   fechaconta, fechaanula,nrocontrolop, estant,docant,monamo) ".
				  " SELECT codemp,codban,ctaban,numdoc,codope,'".$as_estmov_new."',cod_pro,ced_bene,".
		          "        tipo_destino, codconmov, '".$adt_fecmov."', conmov, nomproben, monto, ".
				  "        estbpd, estcon, estcobing, esttra, chevau, estimpche, ".
				  "        monobjret, monret,'".$as_procede."','".$as_comprobante."','".$adt_fecha."',id_mco,".
				  "        emicheproc, emicheced, emichenom, emichefec, estmovint, ".
				  "        codusu, codopeidb, aliidb, feccon, estreglib, numcarord, ".
				  "        numpolcon,coduniadmsig,codbansig,fecordpagsig,tipdocressig,".
				  "        numdocressig,estmodordpag,codfuefin,forpagsig,medpagsig,codestprosig, ".
				  "        fechaconta,fechaanula,nrocontrolop, estant,docant,monamo ".
				  "  FROM scb_movbco ".
                  " WHERE codemp='".$as_codemp."' ".
				  "	  AND codban='".$as_codban."' ".
				  "   AND ctaban='".$as_ctaban."' ".
				  "   AND numdoc='".$as_numdoc."' ".
				  "   AND codope='".$as_codope."' ".
				  "   AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_create_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		//------------------------se agrega el insert para scb_movbco_anticipo----------------------------------------------
		   $ls_sql=" INSERT INTO scb_movbco_anticipo(codemp, codban, ctaban, numdoc, codope, estmov, codamo, monamo, ".
                   "                                 monsal, montotamo, sc_cuenta)                                   ".
				   " SELECT codemp, codban, ctaban, numdoc, codope, '".$as_estmov_new."', codamo, monamo,            ".
                   "        monsal, montotamo, sc_cuenta                                                             ".
                   "  FROM scb_movbco_anticipo                                                                       ".
				   " WHERE codemp='".$as_codemp."' ".
				   "   AND codban='".$as_codban."' ".
				   "   AND ctaban='".$as_ctaban."' ".
				   "   AND numdoc='".$as_numdoc."' ".
				   "   AND codope='".$as_codope."' ".
				   "   AND estmov='".$as_estmov."'";
		   
		    $li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{   
				$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_create_movimiento_banco 
				                        ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				return false;
			}
		//------------------------------------------------------------------------------------------------------------------
        // transferencia al nuevo registro de banco 
		$ls_sql = "INSERT INTO scb_dt_movbco (codemp, codban, ctaban, numdoc, codope, estmov, cod_pro, ced_bene, numsolpag, ".
				  "							  monsolpag, ctabanbene) ".
				  " SELECT codemp, codban, ctaban, numdoc, codope, '".$as_estmov_new."', cod_pro, ced_bene, numsolpag,  monsolpag, ctabanbene".
				  "  FROM scb_dt_movbco ".
                  " WHERE codemp='".$as_codemp."' ".
				  "   AND codban='".$as_codban."' ".
				  "   AND ctaban='".$as_ctaban."' ".
				  "   AND numdoc='".$as_numdoc."' ".
				  "   AND codope='".$as_codope."' ".
				  "   AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_create_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
        // transferencia al nuevo registro de banco detalle contables
		$ls_sql = "INSERT INTO scb_movbco_scg (codemp, codban, ctaban, numdoc, codope, estmov, scg_cuenta,".
		          "                            debhab, codded, documento, desmov, procede_doc, monto, monobjret) ".
				  " SELECT codemp,codban,ctaban,numdoc,codope,'".$as_estmov_new."',scg_cuenta,".
				  "        debhab, codded, documento, desmov, procede_doc, monto, monobjret".
				  "  FROM scb_movbco_scg ".
                  " WHERE codemp='".$as_codemp."' ".
				  "   AND codban='".$as_codban."' ".
				  "   AND ctaban='".$as_ctaban."' ".
				  "   AND numdoc='".$as_numdoc."' ".
				  "   AND codope='".$as_codope."' ".
				  "   AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_create_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
        // transferencia al nuevo registro de banco detalle de gastos
		$ls_sql = " INSERT INTO scb_movbco_spg (codemp,codban,ctaban,numdoc,codope,estmov,codestpro,".
		          "                             spg_cuenta,operacion,documento,desmov,procede_doc,monto,estcla) ".
				  " SELECT codemp,codban,ctaban,numdoc,codope,'".$as_estmov_new."',codestpro,spg_cuenta,".
				  "        operacion,documento,desmov,procede_doc,monto,estcla ".
				  " FROM scb_movbco_spg ".
                  " WHERE codemp='".$as_codemp."' ".
				  "   AND codban='".$as_codban."' ".
				  "   AND ctaban='".$as_ctaban."' ".
				  "   AND numdoc='".$as_numdoc."' ".
				  "   AND codope='".$as_codope."' ".
				  "   AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_create_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
        // transferencia al nuevo registro de banco detalle de gastos
		$ls_sql = " INSERT INTO scb_movbco_spgop (codemp,codban,ctaban,numdoc,codope,estmov,codestpro,".
		          "                             spg_cuenta,operacion,documento,coduniadm,desmov,procede_doc,monto,baseimp,codcar,estcla) ".
				  "SELECT codemp,codban,ctaban,numdoc,codope,'".$as_estmov_new."',codestpro,spg_cuenta,".
				  "        operacion,documento,coduniadm,desmov,procede_doc,monto,baseimp,codcar,estcla ".
				  "  FROM scb_movbco_spgop ".
                  " WHERE codemp='".$as_codemp."' ".
				  "   AND codban='".$as_codban."' ".
				  "   AND ctaban='".$as_ctaban."' ".
				  "   AND numdoc='".$as_numdoc."' ".
				  "   AND codope='".$as_codope."' ".
				  "   AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_create_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
        // transferencia al nuevo registro de banco detalle de ingresos
		$ls_sql = " INSERT INTO scb_movbco_spi (codemp,codban,ctaban,numdoc,codope,estmov,spi_cuenta,   ".
		          "                             documento,operacion,desmov,procede_doc,monto, estcla,   ".
				  "                             codestpro1,codestpro2,codestpro3,codestpro4,codestpro5) ".
				  " SELECT codemp,codban,ctaban,numdoc,codope,'".$as_estmov_new."',spi_cuenta,".
				  "        documento,operacion,desmov,procede_doc,monto,estcla,    ".
				  "        codestpro1,codestpro2,codestpro3,codestpro4,codestpro5  ".
				  "   FROM scb_movbco_spi ".
                  "  WHERE codemp='".$as_codemp."' ".
				  "    AND codban='".$as_codban."' ".
				  "    AND ctaban='".$as_ctaban."' ".
				  "    AND numdoc='".$as_numdoc."' ".
				  "    AND codope='".$as_codope."' ".
				  "    AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_create_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
        // transferencia al nuevo registro de banco detalle de fuentes de financiamiento
		$ls_sql = " INSERT INTO scb_movbco_fuefinanciamiento (codemp, codban, ctaban, numdoc, codope, estmov, codfuefin) ".
				  " SELECT codemp,codban,ctaban,numdoc,codope,'".$as_estmov_new."',codfuefin ".
				  "   FROM scb_movbco_fuefinanciamiento ".
                  "  WHERE codemp='".$as_codemp."' ".
				  "    AND codban='".$as_codban."' ".
				  "    AND ctaban='".$as_ctaban."' ".
				  "    AND numdoc='".$as_numdoc."' ".
				  "    AND codope='".$as_codope."' ".
				  "    AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_create_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		$ls_sql="UPDATE scb_dt_op ".
				"   SET estmov = '".$as_estmov_new."' ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_create_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		// SI NO ES ANULADO ENTONCES NO SE CREA 
        if(($as_estmov_new!="A")||($as_estmov_new!="O")) 
		{
			// transferencia al nuevo registro de solicitud banco
			$ls_sql = " INSERT INTO cxp_sol_banco (codemp,numsol,codban,ctaban,numdoc,codope,estmov,monto,id) ".
					  " SELECT codemp,numsol,codban,ctaban,numdoc,codope,'".$as_estmov_new."',monto,id".
					  "   FROM cxp_sol_banco ".
					  "  WHERE codemp='".$as_codemp."' ".
					  "	   AND codban='".$as_codban."' ".
					  "    AND ctaban='".$as_ctaban."' ".
					  "    AND numdoc='".$as_numdoc."' ".
					  "    AND codope='".$as_codope."' ".
					  "    AND estmov='".$as_estmov."'";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{   
           		$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_create_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				return false;
			}
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/// PARA LA CONVERSIÓN MONETARIA
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_seguridad="";
		/*if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_scbmovbco($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov_new,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_scbdtmovbco($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov_new,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_scbmovbcoscg($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov_new,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_scbmovbcospg($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov_new,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_scbmovbcospgop($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov_new,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_scbmovbcospi($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov_new,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_cxpsolbanco($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov_new,$aa_seguridad);
		}
*/		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		return $lb_valido;
	}// end function uf_create_movimiento_banco
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_movimiento_banco($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_movimiento_banco
		//		   Access: private
		//	    Arguments: as_codemp // Código de Empresa
		//	    		   as_codban // Código de Banco
		//	    		   as_ctaban // Cuenta Banco
		//	    		   as_numdoc // Número de Documento
		//	    		   as_codope // Código de Operación
		//	    		   as_estmov // estatus del Movimiento
		//	      Returns: lb_valido True si se encontro el movimiento ó false si no se encontro
		//	  Description: Funcion que retorna si exsite o no el movimiento de banco
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 02/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe=false;		
		$ls_codemp=$as_codemp;
		$ls_sql="SELECT * ".
                "  FROM scb_movbco ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
           	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_select_movimiento_banco ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
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
	}// end function uf_select_movimiento_banco
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_movimiento_banco($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_movimiento_banco
		//		   Access: private
		//	    Arguments: as_codemp // Código de Empresa
		//	    		   as_codban // Código de Banco
		//	    		   as_ctaban // Cuenta Banco
		//	    		   as_numdoc // Número de Documento
		//	    		   as_codope // Código de Operación
		//	    		   as_estmov // estatus del Movimiento
		//	      Returns: lb_valido True si se encontro el movimiento ó false si no se encontro
		//	  Description: Método que elimina el movimiento referente al banco en la solicitud de pago banco
		//                  se eliminará el que contiene el antiguo estatus previo a la contabilizacion del movimiento 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 03/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	    $ls_sql="DELETE FROM cxp_sol_banco ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_delete_movimiento_banco 1 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		} 
		$ls_sql="DELETE FROM scb_movbco_spg ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_delete_movimiento_banco 2 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		$ls_sql="DELETE FROM scb_movbco_spgop ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_delete_movimiento_banco 3 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		$ls_sql="DELETE FROM scb_movbco_spi ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_delete_movimiento_banco 4 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
        // transferencia al nuevo registro de banco detalle contables
		$ls_sql="DELETE FROM scb_movbco_scg ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_delete_movimiento_banco 5 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
        // transferencia al nuevo registro de banco detalle
		$ls_sql="DELETE FROM scb_dt_movbco ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_delete_movimiento_banco 6 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
        // transferencia al nuevo registro de banco fuente de financimiento
		$ls_sql="DELETE FROM scb_movbco_fuefinanciamiento ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_delete_movimiento_banco 7 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		//---------------------------------para los movimientos de anticipos----------------------------------------------------
			$ls_sql="DELETE FROM scb_movbco_anticipo ".
					" WHERE codemp='".$as_codemp."' ".
					"   AND codban='".$as_codban."' ".
					"   AND ctaban='".$as_ctaban."' ".
					"   AND numdoc='".$as_numdoc."' ".
					"   AND codope='".$as_codope."' ".
					"   AND estmov='".$as_estmov."'";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{   
				$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_delete_movimiento_banco 8 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				return false;
			}
		//---------------------------------------------------------------------------------------------------------------------
		$ls_sql="DELETE FROM scb_movbco ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_delete_movimiento_banco 8 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		return $lb_valido;
	}// end function uf_delete_movimiento_banco
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reversar_contabilizacion_banco($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$ad_fechaconta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar_contabilizacion_banco
		//		   Access: public (sigesp_mis_reverso_scb)
		//	    Arguments: as_codban // Código de Banco
		//	    		   as_ctaban // Cuenta Banco
		//	    		   as_numdoc // Número de Documento
		//	    		   as_codope // Código de Operación
		//	    		   as_estmov // estatus del Movimiento
		//	    		   ad_fechaconta // fecha en que fue contabilizado el movimiento
		//	    		   aa_seguridad // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se encontro reverso el movimiento ó false si no se reverso
		//	  Description:Proceso que reversa la contabilizacion de movimiento de banco.
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 03/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
        $lb_autoconta=true;		
		$ls_scg_cuenta_banco="";
		$this->is_documento="";		
		$this->is_codemp=$this->io_codemp;
	    $this->is_codban=$as_codban; 
	    $this->is_ctaban=$as_ctaban;
	    $this->is_numdoc=$as_numdoc;
	    $this->is_codope=$as_codope;
	    $this->is_estmov=$as_estmov;
	    $this->is_procede="SCBB".$as_codope;
	    $this->is_procede_doc="SCBB".$as_codope;	
	    $this->is_mensaje_spi="EC";	
	    $this->is_comprobante=trim($this->is_numdoc);		
		$this->ii_compromiso_previo=false;
		$this->idt_fecha=date("Y-m-d");
		// Verifico si el comprobante existe y de ser así lo cargo en el datastored		
        $lb_valido=$this->uf_obtener_data_movimento_banco();
        if(!$lb_valido)
		{
			return false;
		} 
		// Verifico si el movimiento existe
		if(!$this->uf_select_movimiento_banco($this->is_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,"C"))
		{
            $this->io_msg->message("ERROR -> El documento ".$as_numdoc." no existe");		
			return false;			
		}
        $this->is_estbpd=$this->dts_banco->getValue("estbpd",1);
		$this->ii_cobra=$this->dts_banco->getValue("estcobing",1);
        $ls_tipo=$this->dts_banco->getValue("tipo_destino",1);   		
		$ls_ced_bene=$this->dts_banco->getValue("ced_bene",1);
		$ls_cod_pro=$this->dts_banco->getValue("cod_pro",1);
		$ls_descripcion=$this->dts_banco->getValue("conmov",1);
		$ldt_fecha=$this->io_function->uf_formatovalidofecha($this->dts_banco->getValue("fecmov",1));
		$ls_procede_scb=$this->dts_banco->getValue("procede",1);
		$ls_comprobante_scb=$this->dts_banco->getValue("comprobante",1);
		$ldt_fecha_scb=$this->dts_banco->getValue("fecha",1);	
		//----------------VERIFICA SI EL CHEQUE ES DE ANTICIPO------------------------------------------------------
		$ls_docant=$this->dts_banco->getValue("numdoc",1);
		$ls_tipoch=$this->dts_banco->getValue("estant",1);
		$ls_valor=0;
		if ($ls_tipoch=="1")// cheque marcado como anticipo
		{    
		   $ls_valor=$this->uf_buscar_amortizaciones($this->is_codemp,$ls_docant);
		}
		//-----------------------------------------------------------------------------------------------------------	
		if ($ls_valor==0)
		{
			if($ls_ced_bene=="----------")
			{
				$ls_fuente=$ls_cod_pro;
			}
			else
			{
				$ls_fuente=$ls_ced_bene;
			}
			$this->is_spg_cuenta=$ls_scg_cuenta_banco;		
			if(($this->is_estbpd=="B")||($this->is_estbpd=="P")) 
			{
				$this->ii_datasource=4;
				$this->is_mensaje_spg="P";	  
			}
			else
			{
				$this->ii_datasource=1;
				$this->is_mensaje_spg="OCP";	  
			}
			if(($this->is_estbpd=="R")||($this->is_estbpd=="O")||($this->is_estbpd=="C")) 
			{
				$this->io_msg->message("ERROR -> El Pago directo con Compromiso/Causado Previo no es soportado");
				return false;
			}		
			if(is_null($ls_procede_scb))
			{
				$ls_procede_scb=$this->is_procede;
			}
			if(is_null($ls_comprobante_scb))
			{
				$ls_comprobante_scb=$this->is_comprobante;
			} 
			if(is_null($ldt_fecha_scb))
			{
				$ldt_fecha_scb=$ldt_fecha;
			} 
			$lb_check_close=false;
			$lb_valido=$this->io_sigesp_int->uf_init_delete($this->is_codemp,$ls_procede_scb,$ls_comprobante_scb,$ad_fechaconta,
															$ls_tipo,$ls_ced_bene,$ls_cod_pro,$lb_check_close,$as_codban,$as_ctaban);
			if($lb_valido===false )	
			{ 
			   $this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error);
			   return false; 
			}
			// Inicio Transaccion SQL
			$this->io_sigesp_int->uf_int_init_transaction_begin();
			if($lb_valido)
			{
				// Reverso en presupuesto y contabilidad
				$lb_valido=$this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
				if($lb_valido===false)
				{
					$this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error);
				}		   
			}
			if($lb_valido)
			{
				// Inserta el nuevo movimiento de banco
				$lb_valido=$this->uf_create_movimiento_banco($this->is_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,
															 "N","","",$ldt_fecha,"1900-01-01");
				if(!$lb_valido)
				{   
					$this->io_msg->message("ERROR -> En crear nuevo movimiento a banco"); 
					$this->io_sigesp_int->uf_sql_transaction($lb_valido);
					return false;		   		   
				}
				// Vuelvo a colocar la programación de pagos activa
				$lb_valido = $this->uf_restaura_programacion_pago('N');
				if (!$lb_valido)
				{   
					$this->io_msg->message("ERROR -> Al restaurar la programación de Pago"); 
					$this->io_sigesp_int->uf_sql_transaction($lb_valido);
					return false;		   		   
				}
				// Elimino el movimiento a banco
				$lb_valido = $this->uf_delete_movimiento_banco($this->is_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov);
				if (!$lb_valido)
				{   
					$this->io_msg->message("ERROR -> Al eliminar el movimiento a banco"); 
					$this->io_sigesp_int->uf_sql_transaction($lb_valido);
					return false;		   		   
				}
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_update_fecha_contabilizado_scbmov($this->io_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,
																	   "N",'1900-01-01','1900-01-01','');
			}
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion="Reversó la contabilización del Documento <b>".$as_numdoc."</b>, Banco <b>".$as_codban."</b>, ".
								"Cuenta Banco <b>".$as_ctaban."</b>, Fecha del Documento <b>".$ldt_fecha."</b>";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}		
			$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		}// fin del if($ls_valor)
		else
		{
			$this->io_msg->message("No se puede Reversar la contabilización el Docuemnto $as_numdoc, es de tipo anticipo y posee Amortizaciones asociadas ");
			$lb_valido=false;
		}
		return $lb_valido;
    }// end function uf_reversar_contabilizacion_banco
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_restaura_programacion_pago($as_estnue)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_restaura_programacion_pago
		//		   Access: private
		//	    Arguments: as_estnue // Nuevo estatus de la programación de pago
		//	      Returns: lb_valido True si se encontro reverso el movimiento ó false si no se reverso
		//	  Description: Este metodo restaura la programación del pago de un cheque
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 03/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT numsol ".
                "  FROM cxp_sol_banco ".
                " WHERE codemp='".$this->is_codemp."' ".
				"   AND codban='".$this->is_codban."' ".
				"   AND ctaban='".$this->is_ctaban."' ".
				"   AND numdoc='".$this->is_numdoc."' ".
				"   AND codope='".$this->is_codope."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
           	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_restaura_programacion_pago ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			while($row=$this->io_sql->fetch_row($rs_data))
		    {
			    $ls_numsol=$row["numsol"];
				$ls_sql="UPDATE scb_prog_pago ".
						"   SET estmov='".$as_estnue."'".
						" WHERE codemp='".$this->is_codemp."' ".
						"   AND codban='".$this->is_codban."' ".
						"   AND ctaban='".$this->is_ctaban."' ".
						"   AND numsol='".$ls_numsol."'";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{   
					$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_restaura_programacion_pago ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					return false;
				}
				$ls_sql="UPDATE cxp_solicitudes ".
						"   SET estprosol='S'".
						" WHERE codemp='".$this->is_codemp."' ".
						"   AND numsol='".$ls_numsol."'";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{   
					$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_restaura_programacion_pago ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					return false;
				}
				if ($lb_valido)
				{
                   // Elimina historico de solicitudes pagadas
                   $lb_valido = $this->io_sigesp_int_cxp->uf_delete_historico_pagado($this->is_codemp,$ls_numsol);
				}
			}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
    }// end function uf_restaura_programacion_pago
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_anulacion_banco($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$adt_fecha_anula,
										 $ad_fechaconta,$as_conanu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_anulacion_banco
		//		   Access: public (sigesp_mis_anula_scb.php)
		//	    Arguments: as_codban // Código de Banco
		//	    		   as_ctaban // Cuenta Banco
		//	    		   as_numdoc // Número de Documento
		//	    		   as_codope // Código de Operación
		//	    		   as_estmov // estatus del Movimiento
		//	    		   adt_fecha_anula //Fecha de anulación
		//	    		   ad_fechaconta //Fecha de Contabilizacion
		//	    		   as_conanu //concepto de anulacion
		//	    		   aa_seguridad // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se encontro reverso el movimiento ó false si no se reverso
		//	  Description: Proceso de anulación banco en cualquiera de sus operaciones
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 03/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->is_codemp=$this->io_codemp;
	    $this->is_codban=$as_codban;
	    $this->is_ctaban=$as_ctaban;
	    $this->is_numdoc=$as_numdoc;
	    $this->is_codope=$as_codope;
	    $this->is_estmov=$as_estmov;
	    $this->ii_datasource=1;
		$adt_fecha_anula=$this->io_function->uf_convertirdatetobd($adt_fecha_anula);
        $this->idt_date=$adt_fecha_anula;        
   		// Verifico que el comprobante exista y lo cargo en el datastored
		$lb_valido=$this->uf_obtener_data_movimento_banco();
        if(!$lb_valido)
		{
			return false;
		} 
        $this->is_estbpd=$this->dts_banco->getValue("estbpd",1);
		$ls_procede=$this->dts_banco->getValue("procede",1);
		$ls_comprobante=$this->dts_banco->getValue("comprobante",1);
		$ldt_fecha=$this->io_function->uf_formatovalidofecha($this->dts_banco->getValue("fecha",1));
		$ls_descripcion=$this->dts_banco->getValue("conmov",1);
		$ldt_fechamov=$this->io_function->uf_formatovalidofecha($this->dts_banco->getValue("fecmov",1));
		
		//----------------VERIFICA SI EL CHEQUE ES DE ANTICIPO------------------------------------------------------
		$ls_docant=$this->dts_banco->getValue("numdoc",1);
		$ls_tipoch=$this->dts_banco->getValue("estant",1);
		$ls_valor=0;
		if ($ls_tipoch=="1")// cheque marcado como anticipo
		{    
		   $ls_valor=$this->uf_buscar_amortizaciones($this->is_codemp,$ls_docant);
		}
		//-----------------------------------------------------------------------------------------------------------	
		if ($ls_valor==0)
		{
			if(!$this->io_fecha->uf_comparar_fecha($ldt_fecha,$adt_fecha_anula))
			{
			   $this->io_msg->message("ERROR-> La Fecha de Anulación es menor que la fecha del Documento.");
			   return false;
			}
			if(($this->is_estbpd=="P")||($this->is_estbpd=="B"))
			{
				$this->ii_datasource=4;
			}
			$this->uf_configuro_source_class();
			if($this->ii_datasource!=0)
			{
				if(($this->ii_datasource==1)||($this->ii_datasource==4))
				{	
					if(is_null($ls_procede))
					{
						$ls_procede="SCBB".$this->is_codope;
					}
					else
					{
						$ls_procede=$ls_procede;
					}
					if(is_null($ls_comprobante))
					{
						$ls_comprobante=$this->is_numdoc;
					}
					else
					{
						$this->is_comprobante=$ls_comprobante;
					}
					if(is_null($ldt_fecha))
					{
						$ldt_fecha=$adt_fecha_anula;
					}
					else
					{
						$this->idt_fecha=$ldt_fecha;
					}
				}
			}
			$this->idt_fecha = $this->io_function->uf_convertirdatetobd($this->idt_fecha);		
			if($this->ii_datasource==3)
			{
				return false;
			}
			$ls_procede_anula=$this->is_procede."A".substr($this->is_codope,1,1);
			$ls_descripcion_anulado=$ls_descripcion;
			$this->is_procede=$ls_procede;
			$ad_fechaconta=$this->io_function->uf_convertirdatetobd($ad_fechaconta);
			$li_tipo_comp=1; // comprobante Normal				
			$this->as_procede=$ls_procede_anula;
			$this->as_comprobante=$this->is_comprobante;
			$this->ad_fecha=$adt_fecha_anula;
			$lb_valido = $this->io_sigesp_int->uf_int_anular($this->is_codemp,$this->is_procede,$this->is_comprobante,
															 $ad_fechaconta,$ls_procede_anula,$adt_fecha_anula,
															 $ls_descripcion,$as_codban,$as_ctaban,$li_tipo_comp);
			if($lb_valido===false)	
			{ 
				$this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error);
				return false; 
			}
			// Inicio de Transacción SQL
			$this->io_sigesp_int->uf_int_init_transaction_begin();
			if ($lb_valido)
			{
				$lb_valido = $this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
				if ( $lb_valido===false)
				{
					$this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error);
					$this->io_sigesp_int->uf_sql_transaction($lb_valido);
				}		   
			}	
			///----------en caso de que el cheque amortiza-------------------------------------------------------------
			if($lb_valido)
			{   
				$lb_valido=$this->uf_eliminar_amortizacion($this->io_codemp,$as_codban,$as_ctaban,$as_numdoc);			
			}		
			//---------------------------------------------------------------------------------------------------------		
			if($lb_valido)
			{
				if(($this->ii_datasource==1)||($this->ii_datasource==4))
				{
					// Crea de Nuevo el movimiento de banco	
					$lb_valido=$this->uf_create_movimiento_banco($this->is_codemp,$this->is_codban,$this->is_ctaban,$this->is_numdoc,$this->is_codope,$this->is_estmov,"O",$this->is_procede,$this->is_comprobante,$ldt_fechamov,$this->idt_fecha);
					if($lb_valido) 
					{// Crea de Nuevo el movimiento de banco	Anulado 
						$lb_valido=$this->uf_create_movimiento_banco($this->is_codemp,$this->is_codban,$this->is_ctaban,$this->is_numdoc,$this->is_codope,$this->is_estmov,"A",$ls_procede_anula,$this->is_comprobante,$adt_fecha_anula,$adt_fecha_anula); 
					}
				}   
				if ($lb_valido) 
				{	// Elimino el movimiento de banco
					$lb_valido=$this->uf_delete_movimiento_banco($this->is_codemp,$this->is_codban,$this->is_ctaban,$this->is_numdoc,$this->is_codope,$this->is_estmov);
					if (!$lb_valido)
					{   
						$this->io_msg->message("ERROR -> Al Eliminar el movimiento de Banco"); 
						$this->io_sigesp_int->uf_sql_transaction($lb_valido);
						return false;		   		   
					}
				}
				// Método que restaura la programación
				$lb_valido=$this->uf_restaura_programacion_pago('P');
				if (!$lb_valido)
				{
					$this->io_msg->message("ERROR -> Al restaurar la programación de pago"); 
					$this->io_sigesp_int->uf_sql_transaction($lb_valido);
					return false;		   		   
				}
			}
			
			if($lb_valido)
			{
				$lb_valido=$this->uf_update_fecha_contabilizado_scbmov($this->io_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,
																	   "A",'',$adt_fecha_anula,$as_conanu);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_update_fecha_contabilizado_scbmov($this->io_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,
																	   "O",'',$adt_fecha_anula,$as_conanu);
			}
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion="Anulo el Documento <b>".$as_numdoc."</b>, Banco <b>".$as_codban."</b>, ".
								"Cuenta Banco <b>".$as_ctaban."</b>, Fecha de Anulación <b>".$ldt_fecha."</b>";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
			}
			if($lb_valido)
			{
				$lb_valido=$this->io_fun_mis->uf_convertir_spidtcmp($this->as_procede,$this->as_comprobante,$this->ad_fecha,
																	$this->as_codban,$this->as_ctaban,$aa_seguridad);
			}*/		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			$this->io_sigesp_int->uf_sql_transaction($lb_valido);
			return $lb_valido;
		}// fin del if($ls_valor)
		else
		{
			$this->io_msg->message("No se puede Anular el Docuemnto $as_numdoc, es de tipo anticipo y posee Amortizaciones asociadas ");
			$lb_valido=false;
		}
		return $lb_valido;
    }// end function uf_procesar_anulacion_banco
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_configuro_source_class()
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_configuro_source_class
		//		   Access: private
		//	      Returns: 
		//	  Description: Método que configura y establece las propiedades de la clase
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 03/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Fuente de Generación (Banco o Cheque)
		if(($this->ii_datasource==1)||($this->ii_datasource==4))
		{
			$this->is_procede="SCBB";
			$this->is_procede_doc="SCBB";	
			$this->is_mensaje_spi="EC";	
			if($this->ii_datasource==1)
			{ // Si Proviene de Banco (Comprometo Causo y Pago)
				$this->is_mensaje_spg="OCP";
			}
			else
			{ // Si Proviene de PagoDirecto o Beneficiario (Solo Pago)
				$this->is_mensaje_spg="P";
			}
		}
		// Fuente de Generación (Colocaciones)
		if($this->ii_datasource==2)
		{
			$this->is_procede="SCBC";
			$this->is_procede_doc="SCBC";	
			$this->is_mensaje_spi="EC";	
			$this->is_mensaje_spg="OCP";
		}
		// Fuente de Generación (Caja)
		if($this->ii_datasource==3)
		{
			$this->is_procede="SCBJ";
			$this->is_procede_doc="SCBJ";	
			$this->is_mensaje_spi="EC";	
			$this->is_mensaje_spg="OCP";
		}
	}// end function uf_configuro_source_class
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reversar_anulado($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$ad_fechaconta,$ad_fechaanula,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar_anulado
		//		   Access: public (sigesp_mis_reverso_anula_scb.php)
		//	    Arguments: as_codban // Código de Banco
		//	    		   as_ctaban // Cuenta Banco
		//	    		   as_numdoc // Número de Documento
		//	    		   as_codope // Código de Operación
		//	    		   as_estmov // estatus del Movimiento
		//	    		   ad_fechaconta // Fecha de contabilización
		//	    		   ad_fechaanula // Fecha de Anulación
		//	    		   aa_seguridad // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se encontro reverso el movimiento ó false si no se reverso
		//	  Description: Proceso de elimina el la anulación de un cheque, el cual elimina tanto el anulado como el original 
		//                  y coloca el numero de solicitud en esttaus de programación de pago (S)
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 03/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// inicio transacción SQL
        $this->io_sigesp_int->uf_int_init_transaction_begin();

        $lb_valido=$this->uf_eliminar_documento_anulado($as_codban,$as_ctaban,$as_numdoc,$as_codope,"A","A",$ad_fechaconta,
														$ad_fechaanula,$aa_seguridad);
		if($lb_valido)
		{
			$lb_valido=$this->uf_eliminar_documento_anulado($as_codban,$as_ctaban,$as_numdoc,$as_codope,"O","O",$ad_fechaconta,
															$ad_fechaanula,$aa_seguridad);
		}
	    if($lb_valido)
	    {
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reverso del Anulado Documento <b>".$as_numdoc."</b>, Banco <b>".$as_codban."</b>, ".
							"Cuenta Banco <b>".$as_ctaban."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}		
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
    }// end function uf_reversar_anulado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_eliminar_documento_anulado($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$as_modalidad,$ad_fechaconta,
										   $ad_fechaanula,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_eliminar_documento_anulado
		//		   Access: private
		//	    Arguments: as_codban // Código de Banco
		//	    		   as_ctaban // Cuenta Banco
		//	    		   as_numdoc // Número de Documento
		//	    		   as_codope // Código de Operación
		//	    		   as_estmov // estatus del Movimiento
		//	    		   as_modalidad // representa si es documento original o anulado y ejecuta  
		//	    		   ad_fechaconta // Fecha de Cotabilizacion
		//	    		   ad_fechaanula // Fecha de Anulación
		//	      Returns: lb_valido True si se eliminó el movimiento ó false si no se eliminó
		//	  Description: Proceso de elimina el documento con estatus anulado
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 06/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->is_codemp=$this->io_codemp;
	    $this->is_codban=$as_codban;
	    $this->is_ctaban=$as_ctaban;
	    $this->is_numdoc=$as_numdoc;
	    $this->is_codope=$as_codope;
	    $this->is_estmov=$as_estmov;
	    $this->ii_datasource=1;
		// Verifico si el comprobante existe y de ser así lo cargo en el datastored		
        $adt_fecha="";
		$lb_valido=$this->uf_obtener_data_movimento_banco();
        if(!$lb_valido)
		{
			return false;
		} 
        $this->is_estbpd=$this->dts_banco->getValue("estbpd",1);
        if(($this->is_estbpd=="P")||($this->is_estbpd=="B"))
		{
			$this->ii_datasource=4;
		}
		$this->uf_configuro_source_class();
		if($as_modalidad=="A")	
		{  // si es doumento anulado aplica este comando si no aplica lo el siguiente
			$ls_procede=$this->is_procede."A".substr($this->is_codope,1,1); 
			$adt_fecha=$ad_fechaanula;
	    }
		else
		{
			$ls_procede=$this->is_procede.$this->is_codope;
			$adt_fecha=$ad_fechaconta;
		}
		$ls_comprobante=$as_numdoc;
		$ls_tipo_destino="";
		$ls_ced_bene="";
		$ls_cod_pro="";
	    $lb_valido = $this->io_sigesp_int->uf_obtener_comprobante($this->is_codemp,$ls_procede,$ls_comprobante,$adt_fecha,
																  $as_codban,$as_ctaban,&$ls_tipo_destino,&$ls_ced_bene,
																  &$ls_cod_pro);		
		if(!$lb_valido)	
		{ 
 		   $this->io_msg->message("ERROR-> No existe el comprobante Nº ".$ls_comprobante."-".$ls_procede.".");
		   return false; 
		}
        $lb_check_close=false;
		$lb_valido=$this->io_sigesp_int->uf_init_delete($this->is_codemp,$ls_procede,$ls_comprobante,$adt_fecha,$ls_tipo_destino,
														$ls_ced_bene,$ls_cod_pro,$lb_check_close,$as_codban,$as_ctaban);
		if(!$lb_valido)	
		{ 
 		   $this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
		   return false; 
		}
	    if ($lb_valido)
	    {
	        $lb_valido=$this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
	        if($lb_valido===false)
		    {
              $this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error);
		    }		   
		}
		if($lb_valido)
		{
			if(($this->ii_datasource==1)||($this->ii_datasource==4))
			{	
				$lb_valido = $this->uf_delete_movimiento_banco($this->is_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,
															   $as_estmov);
				if (!$lb_valido)
				{   
				   $this->io_msg->message("ERROR -> Al Eliminar el movimiento"); 
				   return false;		   		   
				}
			}
		}
		return $lb_valido;
    }// end function uf_eliminar_documento_anulado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_contabilizacion_orden_pago($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$adt_fecha,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contabilizacion_orden_pago
		//		   Access: public (sigesp_mis_p_contabiliza_scbop.php)
		//	    Arguments: as_codban // Código de Banco
		//	    		   as_ctaban // Cuenta Banco
		//	    		   as_numdoc // Número de Documento
		//	    		   as_codope // Código de Operación
		//	    		   as_estmov // estatus del Movimiento
		//	    		   adt_fecha // Fecha de Contablización
		//	    		   aa_seguridad // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se encontro reverso el movimiento ó false si no se reverso
		//	  Description: Proceso de contabilizacion  de la orden dde pago directo.
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 06/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$adt_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);		
        $lb_autoconta=false;
        $this->idt_date=$adt_fecha;		
		$this->is_codemp=$this->io_codemp;
	    $this->is_codban=$as_codban;
	    $this->is_ctaban=$as_ctaban;
		$this->is_numdoc=$as_numdoc;
	    $this->is_codope=$as_codope;
	    $this->is_estmov=$as_estmov;
	    $this->is_procede="SCB".$as_codope."D";
	    $this->is_procede_doc="SCB".$as_codope."D";	
	    $this->is_comprobante=$this->io_sigesp_int->uf_fill_comprobante(trim($as_numdoc));
		$this->is_documento="";
		// Verifico si el comprobante existe y de ser así lo cargo en el datastored		
        $lb_valido=$this->uf_obtener_data_movimento_banco();
        if(!$lb_valido)
		{
			return false;
		} 
        $ls_tipo=$this->dts_banco->getValue("tipo_destino",1);   		
		$ls_ced_bene=$this->dts_banco->getValue("ced_bene",1);
		$ls_cod_pro=$this->dts_banco->getValue("cod_pro",1);
		$ls_descripcion=$this->dts_banco->getValue("conmov",1);
		$ldt_fecha=$this->dts_banco->getValue("fecmov",1);
		if($ls_ced_bene=="----------")
		{
			$ls_fuente=$ls_cod_pro;
		}
		else
		{
			$ls_fuente=$ls_ced_bene;
		}
	    $this->io_sigesp_int->uf_int_config(false,false); 
		$li_tipo_comp=1; // comprobante Normal
		$this->as_procede=$this->is_procede;
		$this->as_comprobante=$this->is_comprobante;
		$this->ad_fecha=$ldt_fecha;
		$lb_valido = $this->io_sigesp_int->uf_int_init($this->is_codemp,$this->is_procede,$this->is_comprobante,$ldt_fecha,
													   $ls_descripcion,$ls_tipo,$ls_fuente,$lb_autoconta,$as_codban,$as_ctaban,
													   $li_tipo_comp);
		if (!$lb_valido)
		{   
           $this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error); 
		   return false;		   		   
		}
		// Inicio Transaccion SQL
        $this->io_sigesp_int->uf_int_init_transaction_begin();
		// Insertar en el datastored los detalles de presupuesto
		$lb_valido=$this->uf_procesar_gastos_pago_directo($ls_descripcion);
		if(!$lb_valido)
		{
			$this->io_sigesp_int->uf_sql_transaction($lb_valido);
			return false;
		}
		// Insertar en el datastored los detalles de contabilidad
		$lb_valido = $this->uf_procesar_contable_pago_directo($ls_descripcion);
		if(!$lb_valido)
		{
			$this->io_sigesp_int->uf_sql_transaction($lb_valido);
			return false;
		}
	    if($lb_valido)
	    {
	        $lb_valido=$this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
	        if($lb_valido===false)
		    {
				$this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error);
		    }		   
		}
		if($lb_valido)
		{
			// Inserta el nuevo movimiento de banco
			$lb_valido = $this->uf_create_movimiento_banco($this->io_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,
														   $as_estmov,"C",$this->is_procede,$this->is_comprobante,$ldt_fecha,
														   $ldt_fecha);
			if(!$lb_valido)
			{   
			   $this->io_msg->message("ERROR -> Al crear el nuevo movimiento de banco"); 
				$this->io_sigesp_int->uf_sql_transaction($lb_valido);
				return false;
			}
			// Eliminamos el movimiento a banco
			$lb_valido = $this->uf_delete_movimiento_banco($this->io_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov);
			if(!$lb_valido)
			{   
				$this->io_msg->message("ERROR -> Al eliminar movimiento de banco"); 
				$this->io_sigesp_int->uf_sql_transaction($lb_valido);
				return false;
			}
		}
	    if($lb_valido)
	    {
			$lb_valido=$this->uf_update_fecha_contabilizado_scbmov($this->io_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,
																   "C",$ldt_fecha,'1900-01-01','');
		}
	    if($lb_valido)
	    {
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Contabilizó la Orden de Pago Directa Documento <b>".$as_numdoc."</b>, Banco <b>".$as_codban."</b>, ".
							"Cuenta Banco <b>".$as_ctaban."</b>, Fecha de Contabilización <b>".$ldt_fecha."</b>";
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
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_spidtcmp($this->as_procede,$this->as_comprobante,$this->ad_fecha,
																$this->as_codban,$this->as_ctaban,$aa_seguridad);
		}*/
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
	}// end function uf_procesar_contabilizacion_orden_pago
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_gastos_pago_directo($as_descripcion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_gastos_pago_directo
		//		   Access: private
		//	    Arguments: as_descripcion // Descripción del comprobante
		//	      Returns: lb_valido True si se encontro reverso el movimiento ó false si no se reverso
		//	  Description: Este metodo ejecuta el nucleo integrador del módulo de banco
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 06/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * ".
                "  FROM scb_movbco_spgop ".
                " WHERE codemp='".$this->is_codemp."' ".
				"   AND codban='".$this->is_codban."' ".
				"   AND ctaban='".$this->is_ctaban."' ".
				"   AND numdoc='".$this->is_numdoc."' ".
				"   AND codope='".$this->is_codope."' ".
				"   AND estmov='".$this->is_estmov."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
           	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_procesar_gastos_pago_directo ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
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
				$ls_documento=$row["documento"];
				$ls_mensaje=$this->io_sigesp_int_spg->uf_operacion_codigo_mensaje($row["operacion"]);
				$ls_spg_cuenta=$this->io_sigesp_int_spg->uf_spg_pad_cuenta($ls_spg_cuenta);
				$lb_valido=$this->io_sigesp_int->uf_spg_insert_datastore($this->is_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																		 $ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,$ls_mensaje,
																		 $ldec_monto,$ls_documento,$this->is_procede,$as_descripcion);
				if(!$lb_valido)
				{  
					$this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error);
					break;
				}
			}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
    }// end function uf_procesar_gastos_pago_directo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_contable_pago_directo($as_descripcion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contable_pago_directo
		//		   Access: private
		//	    Arguments: as_descripcion // Descripción del comprobante
		//	      Returns: lb_valido True si se encontro reverso el movimiento ó false si no se reverso
		//	  Description: Este metodo ejecuta el nucleo integrador del módulo de banco
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 06/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$ls_sql="SELECT * ".
                "  FROM scb_movbco_scg ".
                " WHERE codemp='".$this->is_codemp."' ".
				"   AND codban='".$this->is_codban."' ".
				"   AND ctaban='".$this->is_ctaban."' ".
				"   AND numdoc='".$this->is_numdoc."' ".
				"   AND codope='".$this->is_codope."' ".
				"   AND estmov='".$this->is_estmov."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
           	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_procesar_contable_pago_directo ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			while($row=$this->io_sql->fetch_row($rs_data) and ($lb_valido))
		    {
				$ls_scg_cuenta=$row["scg_cuenta"];
                $ls_debhab=$row["debhab"];				
				$ldec_monto=$row["monto"];				
				$ls_documento=$row["documento"];
				$ls_scg_cuenta=$this->io_sigesp_int_scg->uf_pad_scg_cuenta( $this->dts_empresa["formcont"],$ls_scg_cuenta);
				if($this->is_spg_cuenta==$ls_scg_cuenta)
				{
					$ls_procede=$as_procede;
				}
				$lb_valido=$this->io_sigesp_int->uf_scg_insert_datastore($this->is_codemp,$ls_scg_cuenta,$ls_debhab,$ldec_monto,
																		 $ls_documento,$this->is_procede,$as_descripcion);
				if ($lb_valido===false)
				{  
				   $this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error);
				   break;
				}
			}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
    }// end function uf_procesar_contable_pago_directo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reversar_contabilizacion_banco_pago_directo($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$ad_fechaconta,
															$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar_contabilizacion_banco_pago_directo
		//		   Access: public (sigesp_mis_p_reverso_scbop.php)
		//	    Arguments: as_codban // Código de Banco
		//	    		   as_ctaban // Cuenta Banco
		//	    		   as_numdoc // Número de Documento
		//	    		   as_codope // Código de Operación
		//	    		   as_estmov // estatus del Movimiento
		//	    		   aa_seguridad // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se encontro reverso el movimiento ó false si no se reverso
		//	  Description: Proceso que reversa la contabilizacion de movimiento de banco de pago directo.
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 06/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
        $lb_autoconta=true;		
		$ls_scg_cuenta_banco="";
		$this->is_documento="";		
		$this->is_codemp=$this->io_codemp;
	    $this->is_codban=$as_codban;
	    $this->is_ctaban=$as_ctaban;
	    $this->is_numdoc=$as_numdoc;
	    $this->is_codope=$as_codope;
	    $this->is_estmov=$as_estmov;
	    $this->is_procede="SCB".$as_codope."D";
	    $this->is_procede_doc="SCB".$as_codope."D";	
	    $this->is_mensaje_spi="EC";	
	    $this->is_comprobante=$this->io_sigesp_int->uf_fill_comprobante(trim($as_numdoc));
		$this->ii_compromiso_previo=false;
		$this->idt_fecha=date("Y-m-d");
		// Verifico si el comprobante existe y de ser así lo cargo en el datastored		
        $lb_valido=$this->uf_obtener_data_movimento_banco();
        if(!$lb_valido)
		{
			return false;
		} 
		if (!$this->uf_select_movimiento_banco($this->is_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,"C"))
		{
            $this->io_msg->message("ERROR -> El documento no existe");		
			return false;			
		}
        $this->is_estbpd=$this->dts_banco->getValue("estbpd",1);
		$this->ii_cobra=$this->dts_banco->getValue("estcobing",1);
        $ls_tipo=$this->dts_banco->getValue("tipo_destino",1);   		
		$ls_ced_bene=$this->dts_banco->getValue("ced_bene",1);
		$ls_cod_pro=$this->dts_banco->getValue("cod_pro",1);
		$ls_descripcion=$this->dts_banco->getValue("conmov",1);
		$ldt_fecha=$this->dts_banco->getValue("fecmov",1);
		$ls_procede_scb=$this->dts_banco->getValue("procede",1);
		$ls_comprobante_scb=$this->dts_banco->getValue("comprobante",1);
		$ldt_fecha_scb=$this->dts_banco->getValue("fecha",1);		
		if($ls_ced_bene=="----------")
		{
			$ls_fuente=$ls_cod_pro;
		}
		else
		{
			$ls_fuente=$ls_ced_bene;
		}
		$this->is_spg_cuenta=$ls_scg_cuenta_banco;		
        if(is_null($ls_procede_scb))
		{
			$ls_procede_scb=$this->is_procede;
		}
        if(is_null($ls_comprobante_scb))
		{
			$ls_comprobante_scb=$this->is_comprobante;
		} 
        if(is_null($ldt_fecha_scb))
		{
			$ldt_fecha_scb=$ldt_fecha;
		} 
		$lb_check_close=false;
		$lb_valido=$this->io_sigesp_int->uf_init_delete($this->is_codemp,$ls_procede_scb,$ls_comprobante_scb,$ad_fechaconta,
														$ls_tipo,$ls_ced_bene,$ls_cod_pro,$lb_check_close,$as_codban,$as_ctaban);
		if(!$lb_valido)	
		{ 
			$this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error);
			return false; 
		}
		// Inicio Transaccion SQL
        $this->io_sigesp_int->uf_int_init_transaction_begin();
        if($lb_valido)
		{
			$lb_valido=$this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
			if($lb_valido===false)
			{
				$this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error);
			}
		}
		if($lb_valido)
		{
			$lb_valido = $this->uf_create_movimiento_banco($this->is_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,"N","","",$ldt_fecha,"1900-01-01");
			if(!$lb_valido)
			{   
				$this->io_msg->message("ERROR -> Al crear el nuevo movimiento "); 
				$this->io_sigesp_int->uf_sql_transaction($lb_valido);
				return false;		   		   
			}
			$lb_valido = $this->uf_delete_movimiento_banco($this->is_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov);
			if (!$lb_valido)
			{   
			   $this->io_msg->message("ERROR -> Al eliminar el movimiento "); 
				$this->io_sigesp_int->uf_sql_transaction($lb_valido);
				return false;		   		   
			}		
		}
	    if($lb_valido)
	    {
			$lb_valido=$this->uf_update_fecha_contabilizado_scbmov($this->io_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,
																   "N",'1900-01-01','1900-01-01','');
		}
	    if($lb_valido)
	    {
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reversó la Orden de Pago Directa Documento <b>".$as_numdoc."</b>, Banco <b>".$as_codban."</b>, ".
							"Cuenta Banco <b>".$as_ctaban."</b> ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}		
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
    }// end function uf_reversar_contabilizacion_banco_pago_directo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_fecha_contabilizado_scbmov($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,
												  $ad_fechaconta,$ad_fechaanula,$as_conanu)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_fecha_contabilizado_scbmov
		//		   Access: private
		//	    Arguments: as_codemp  // Código de empresa
		//                 as_codban  // Código de Banco
		//                 as_ctaban  // Cuenta de Banco
		//                 as_numdoc  // Número de Documento
		//                 as_codope  // Código de Operación 
		//                 as_estmov  // Estatus del Movimiento
		//                 ad_fechaconta  // Fecha de Contabilización
		//                 ad_fechaanula  // Fecha de Anulación
		//                 as_conanu     // concepto de Anulacion
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Método que actualiza las fechas de contabilización y de anulación del documento
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 													Fecha Última Modificación : 
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
			$ls_campo2=$ls_campo2.", conanu='".$as_conanu."' ";
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
		$ls_sql="UPDATE scb_movbco ".
		        "   SET ".$ls_campos.
				" WHERE codemp='".$as_codemp."' ".
				"	AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_update_fecha_contabilizado_scbmov ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_fecha_contabilizado_scbmov
	//-----------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
   function uf_eliminar_amortizacion($ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc)
   {		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_eliminar_amortizacion
		//		   Access: private
		//	    Arguments: 
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Elimina la amortizaciòn que realizo el cheque
		//	   Creado Por: Ing. Jennifer Rivero
		// Modificado Por: 													Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
   		$ls_sql=" SELECT docant, monamo  FROM scb_movbco ".
				"  WHERE codemp='".$ls_codemp."'". 
				"    AND codban='".$ls_codban."' ".
				"    AND ctaban='".$ls_ctaban."' ". 
				"    AND codope='CH' ".
				"    AND numdoc='".$ls_numdoc."' ".
				"    AND (estmov='C')"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->Integración SCB MÉTODO->Error en select scb_movbco ".
			                       $this->io_function->uf_convertirmsg($this->io_sql->message));			
			print $this->io_sql->message."<br>";
		}
		else
		{
		    $li_filas=0;
			$li_filas = $this->io_sql->num_rows($rs_data); 
			$row=$this->io_sql->fetch_row($rs_data);
			if ($li_filas>0)
			{
				for ($i=1;$i<=$li_filas;$i++)
				{
					$ls_docant = $row["docant"];
					$ls_monamo = $row["monamo"];
					if ($ls_docant!='---------------')
					{
						$ls_sql=" UPDATE scb_movbco_anticipo".
								"    SET monamo=monamo-".$ls_monamo.",".
								"        monsal=monsal+".$ls_monamo.
								"  WHERE numdoc='".$ls_docant."'"; 
						$rs_update=$this->io_sql->execute($ls_sql);
						if($rs_update===false)
						{   
							$lb_valido=false;
							print $this->io_sql->message."<br>";
						}						
					}//fin del if
				}//fin del for
			}
		}// fin del else		
		return	$lb_valido;
   }//fin de la funciòn  uf_eliminar_amortizacion //-------------------------------------------------------------------------------------------------------------------------------------
 //---------------------------------------------------------------------------------------------------------------------------------------
    function uf_buscar_amortizaciones($as_codemp,$as_docant)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_movimiento_banco
		//		   Access: private
		//	    Arguments: as_codemp // Código de Empresa		
		//	    		   $as_docant // Número de Documento		
		//	      Returns: 
		//	  Description: Funcion que retorna si exsite o no el movimiento de banco
		//	   Creado Por: Ing. Jennier Rivero		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe=false;
		$ls_valor=0;		
		$ls_codemp=$as_codemp;
		$ls_sql="SELECT count(*) as valor ".
                "  FROM scb_movbco ".
                " WHERE codemp='".$as_codemp."' ".				
				"   AND docant='".$as_docant."' ".				
				"   AND estant='2'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
           	$this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_buscar_amortizaciones ERROR->".
			                       $this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_valor=$row["valor"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $ls_valor;
	}// end function uf_buscar_amortizaciones 
//------------------------------------------------------------------------------------------------------------------------------------ 
}
?>