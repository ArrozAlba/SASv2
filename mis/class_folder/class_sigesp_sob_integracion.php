<?php
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  //       Class : class_sigesp_sob_integracion_php                                                     //    
  // Description : Esta clase tiene todos los metodos necesario para el manejo de la rutina integradora //
  //               con el sistema de presupuesto de  gasto y el sistema de obras.                       //               
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
class class_sigesp_sob_integracion
{
	var $sqlca;   
    var $is_msg_error;
	var $dts_empresa; 
    var $dts_data_contrato;
	var $dts_data;
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
	function class_sigesp_sob_integracion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: class_sigesp_sob_integracion
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/04/2006
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
	    $this->io_fecha= new class_fecha();
        $this->io_sigesp_int=new class_sigesp_int_int();
		$this->io_sigesp_int_spg=new class_sigesp_int_spg();
		$this->io_sigesp_int_scg=new class_sigesp_int_scg();
		$this->io_function= new class_funciones() ;
		$this->io_siginc= new sigesp_include();
		$this->io_connect=$this->io_siginc->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);		
		$this->obj=new class_datastore();
		$this->dts_empresa=$_SESSION["la_empresa"];
		$this->io_codemp=$this->dts_empresa["codemp"];		
		$this->dts_data=new class_datastore();
        $this->dts_data_contrato=new class_datastore();		
		$this->io_msg=new class_mensajes();		
		$this->io_seguridad=new sigesp_c_seguridad();		
		$this->as_procede="";
		$this->as_comprobante="";
		$this->ad_fecha="";
		$this->as_codban="";
		$this->as_ctaban="";
		$this->as_procedeaux="";
		$this->as_comprobanteaux="";
		$this->ad_fechaaux="";
		$this->as_codbanaux="";
		$this->as_ctabanaux="";
	}// end function class_sigesp_sob_integracion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destroy_objects()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destroy_objects
		//		   Access: public 
		//	  Description: Destructor de los objectos de la Clase
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/04/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
       if( is_object($this->io_fecha) ) { unset($this->io_fecha);  }
       if( is_object($this->io_sigesp_int) ) { unset($this->io_sigesp_int);  }
	   if( is_object($this->io_function) ) { unset($this->io_function);  }
	   if( is_object($this->io_siginc) ) { unset($this->io_siginc);  }
	   if( is_object($this->io_connect) ) { unset($this->io_connect);  }
	   if( is_object($this->io_sql) ) { unset($this->io_sql);  }	   
	   if( is_object($this->obj) ) { unset($this->obj);  }	   
	   if( is_object($this->dts_empresa) ) { unset($this->dts_empresa);  }	   
	   if( is_object($this->dts_data) ) { unset($this->dts_data);  }	   
	   if( is_object($this->dts_data_contrato) ) { unset($this->dts_data_contrato);  }	   	   
	   if( is_object($this->io_msg) ) { unset($this->io_msg);  }	   
	}// end function uf_destroy_objects
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_contabilizacion_asignacion($as_codasi,$adt_fecha,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contabilizacion_asignacion
		//		   Access: public (sigesp_mis_p_contabiliza_asignacion_sob.php)
		//	    Arguments: as_codasi  // Código de Asignacióna
		//				   adt_fecha  // Fecha de contabilización
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Este metodo tiene como fin contabilizar en presupuesto la asignacion
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 26/04/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $ls_codemp=$this->dts_empresa["codemp"];
        $ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante($as_codasi);		
        $this->dts_data->resetds("codasi"); // inicializa el datastore en 0 registro.
		if(!$this->uf_select_asignacion($as_codasi))
		{
			$this->io_msg->message(" No existe la Asignación N° ".$as_codasi);
			return false;
		}		
		$ls_fecasi=$this->io_function->uf_convertirfecmostrar($this->dts_data->getValue("fecasi",1));
		$ldt_fecha=$this->io_function->uf_convertirfecmostrar($adt_fecha); 
		$ls_estspgscg=$this->dts_data->getValue("estspgscg",1);
		$ls_estasi=$this->dts_data->getValue("estasi",1);
		$ls_descripcion=$this->dts_data->getValue("desobr",1); 
		$ls_codigo_destino=$this->dts_data->getValue("cod_pro",1);	
        $ls_mensaje=$this->io_sigesp_int_spg->uf_operacion_codigo_mensaje("PC");	
        $ls_tipo_destino="P" ;		
        $ls_procede="SOBASI";
		if(($ls_estasi!=1)&&($ls_estasi!=6))
		{
			$this->io_msg->message(" La Asignación ".$as_codasi." debe estar en estatus EMITIDA ó MODIFICADA para su contabilización.");
			return false;
		}
        if(!$this->io_fecha->uf_comparar_fecha($ls_fecasi,$ldt_fecha))
		{
			$this->io_msg->message(" La Fecha de Contabilizacion es menor que la fecha de Emision de la Asignación Nº ".$as_codasi);
			return false;
		}
        // obtengo el monto de la Asignacion y la comparo con el monto de gasto acumulado		
        $ldec_sum_gasto= round($this->uf_sumar_total_cuentas_gasto_asignacion($as_codasi),2);
		$ldec_monto_asignacion = round($this->dts_data->getValue("montotasi",1),2);		
		if($ldec_monto_asignacion!=$ldec_sum_gasto)
        {
			$this->io_msg->message("La Asignación no esta cuadrado con el resumen presupuestario");
			return false;
        }       
        $this->io_sigesp_int->uf_int_init_transaction_begin();	
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		$li_tipo_comp=1; // comprobante Normal
		$this->as_procede=$ls_procede;
		$this->as_comprobante=$ls_comprobante;
		$this->ad_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
		$this->as_codban=$ls_codban;
		$this->as_ctaban=$ls_ctaban;
		$lb_valido=$this->io_sigesp_int->uf_int_init($ls_codemp,$ls_procede,$ls_comprobante,$adt_fecha,$ls_descripcion,
													 $ls_tipo_destino,$ls_codigo_destino,false,$ls_codban,$ls_ctaban,
													 $li_tipo_comp);
		if(!$lb_valido)
		{   
			$this->io_msg->message($this->io_sigesp_int->is_msg_error);
			$this->io_sigesp_int->uf_sql_transaction($lb_valido);
			return false;		   		   
		}
		$lb_valido=$this->uf_procesar_detalles_gastos_asignacion($as_codasi,$ls_mensaje,$ls_procede,$ls_descripcion,"PC");
		if($lb_valido) 
		{
			if($lb_valido)
			{
				$lb_valido=$this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
				if(!$lb_valido)
				{
					$this->io_msg->message($this->io_sigesp_int->is_msg_error);
				}
			}
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_estatus_contabilizado_asignacion($as_codasi,1);		
		}
		if($lb_valido)
		{
			$adt_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha); 
			$lb_valido=$this->uf_update_fecha_contabilizado_sob_asignacion($ls_codemp,$as_codasi,$adt_fecha,'1900-01-01');
		}
	    if($lb_valido)
	    {
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Contabilizó la Asignación <b>".$as_codasi."</b>, Fecha de Contabilización <b>".$ldt_fecha."</b>";
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
		}*/
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return  $lb_valido;
	}// end function uf_procesar_contabilizacion_asignacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_asignacion($as_codasi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_asignacion
		//		   Access: private
		//	    Arguments: as_codasi  // Código de Asignacióna
		//	      Returns: Retorna estructura de datos datastrore con la data de la asignación
		//	  Description: Este metodo realiza una busqueda de la asignación y la almacewna en un datastore
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/04/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$ls_codemp=$this->io_codemp;
		$ls_sql="SELECT sob_asignacion.*, sob_obra.desobr ".
                "  FROM sob_asignacion, sob_obra ".
                " WHERE sob_asignacion.codemp='".$ls_codemp."' ".
				"   AND sob_asignacion.codasi='".$as_codasi."' ".
				"   AND sob_obra.codobr=sob_asignacion.codobr ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración SOB MÉTODO->uf_select_asignacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		else
		{                 
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
                $this->dts_data->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_select_asignacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_sumar_total_cuentas_gasto_asignacion($as_codasi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sumar_total_cuentas_gasto_asignacion
		//		   Access: private
		//	    Arguments: as_codasi  // Código de Asignacióna
		//	      Returns: Retorna un decimal valor monto
		//	  Description: Este método suma los detalles de gasto ASIGNACION.
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/04/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $ldec_monto=0;
		$ls_sql="SELECT COALESCE(SUM(monto),0) As monto ".
                "  FROM sob_cuentasasignacion ".
                " WHERE codemp='".$this->io_codemp."' ".
				"   AND codasi='".$as_codasi."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración SOB MÉTODO->uf_sumar_total_cuentas_gasto_asignacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
	        $ldec_monto=0;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ldec_monto=$row["monto"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $ldec_monto;
	}// end function uf_sumar_total_cuentas_gasto_asignacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_detalles_gastos_asignacion($as_codasi,$as_mensaje,$as_procede_doc,$as_descripcion,$as_process)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_detalles_gastos_asignacion
		//		   Access: private
		//	    Arguments: as_codasi  // Código de Asignacióna
		//	    		   as_mensaje  // Mensaje del precompromiso
		//	    		   as_procede_doc  // Procede del Documento
		//	    		   as_descripcion  // Descripcioón de la obre
		//	    		   as_process  // proceso si se va a precomprometer o se va a hacer el reverso del precompromiso
		//	      Returns: Retorna un boolean valido
		//	  Description: método que procesa los detalles de gastos de una asignación
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/04/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="SELECT * ".
                "  FROM sob_cuentasasignacion ".
                " WHERE codemp='".$this->io_codemp."' ".
				"   AND codasi='".$as_codasi."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración SOB MÉTODO->uf_procesar_detalles_gastos_asignacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			while($row=$this->io_sql->fetch_row($rs_data) and ($lb_valido))
		    {
				$ls_codestpro1=$row["codestpro1"];
				$ls_codestpro2=$row["codestpro2"];
				$ls_codestpro3=$row["codestpro3"];
				$ls_codestpro4=$row["codestpro4"];
				$ls_codestpro5=$row["codestpro5"];
				$ls_estcla=$row["estcla"];
				$ls_spg_cuenta=$row["spg_cuenta"];
				$ls_documento=$this->io_sigesp_int->uf_fill_comprobante($as_codasi);		
				$ldec_monto=$row["monto"];
                if($as_process=="PC")
				{// Se genera el precompromiso de la asignación	
					$ldec_monto=$ldec_monto;
				}
				else //"CO" Reverso del precompromiso
				{
  	 	 	 	   $ldec_monto=$ldec_monto*(-1);
				}
				$lb_valido = $this->io_sigesp_int->uf_spg_insert_datastore($this->io_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
									                                       $ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,$as_mensaje,
									                                       $ldec_monto,$ls_documento,$as_procede_doc,$as_descripcion);
				if ($lb_valido===false)
				{  
				   $this->io_msg->message($this->io_sigesp_int->is_msg_error);
				   break;
				}
			} 
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	}// end function uf_procesar_detalles_gastos_asignacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_contabilizado_asignacion($as_codasi,$ai_estasi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_detalles_gastos_asignacion
		//		   Access: private
		//	    Arguments: as_codasi  // Código de Asignacióna
		//	    		   ai_estasi  // Estatus de la Asignación
		//	      Returns: Retorna un boolean valido
		//	  Description: método que procesa los detalles de gastos de una asignación
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/04/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_sql="UPDATE sob_asignacion ".
		        "   SET estspgscg=".$ai_estasi.
                " WHERE codemp='".$this->io_codemp."' ".
				"   AND codasi='".$as_codasi."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración SOB MÉTODO->uf_update_estatus_contabilizado_asignacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		return $lb_valido;
	}// end function uf_update_estatus_contabilizado_asignacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_reverso_asignacion($as_codasi,$ad_fechaconta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_reverso_asignacion
		//		   Access: private
		//	    Arguments: as_codasi  // Código de Asignacióna
		//	    		   ad_fechaconta  // Fecha de Contabilización
		//	    		   aa_seguridad  // Arreglo de seguridad
		//	      Returns: Retorna un boolean valido
		//	  Description: Este metodo tiene como fin reversar la contabilizacion en presupuesto la asignacion
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/04/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=false;
		$ldt_fecha=$ad_fechaconta;
	    $ls_procede="SOBASI"; // reverso de asignación.
        $ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante($as_codasi);		
        $this->dts_data->resetds("codasi"); // inicializa el datastore en 0 registro.
		if(!$this->uf_select_asignacion($as_codasi))
		{
			$this->io_msg->message(" No existe la Asignación N° ".$as_codasi);
			return false;
		}		
		$ls_tipo_destino="P";				
		$ls_estspgscg=$this->dts_data->getValue("estspgscg",1);
		$ls_cod_pro=$this->dts_data->getValue("cod_pro",1);	
	    $ls_ced_bene="----------";
		if($ls_estspgscg!=1) 
		{
			$this->io_msg->message(" La Asignación ".$as_codasi." debe estar en estatus CONTABILIZADA para reversarla.");
			return false;
		}        
		$ls_codban="---";
		$ls_ctaban="-------------------------";
	    $lb_valido=$this->io_sigesp_int->uf_obtener_comprobante($this->io_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,
		                                                        $ls_codban,$ls_ctaban,$ls_tipo_destino,$ls_ced_bene,$ls_cod_pro);
		if (!$lb_valido) 
		{ 
			$this->io_msg->message("ERROR-> No existe el comprobante Nº ".$ls_comprobante."-".$ls_procede.".");
			return false;
		}
        $this->io_sigesp_int->uf_int_init_transaction_begin();
		$lb_valido = $this->io_sigesp_int->uf_init_delete($this->io_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,
		                                                  $ls_tipo_destino,$ls_ced_bene,$ls_cod_pro,false,$ls_codban,$ls_ctaban);
		if(!$lb_valido)
		{ 
			$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
			$this->io_sigesp_int->uf_sql_transaction($lb_valido);
			return false; 
		}		
	    if($lb_valido)
		{
			$lb_valido = $this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
			if(!$lb_valido)
			{
				$this->io_msg->message($this->io_sigesp_int->is_msg_error);
			}		   
		}
	    if ($lb_valido)
		{
	        $lb_valido=$this->uf_update_estatus_contabilizado_asignacion($as_codasi,0);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_sob_asignacion($this->io_codemp,$as_codasi,'1900-01-01','1900-01-01');
		}
	    if($lb_valido)
	    {
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reverso la Contabilización de la Asignación <b>".$as_codasi."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}		
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return  $lb_valido;
	}// end function uf_procesar_reverso_asignacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_anulacion_asignacion($as_codasi,$adt_fecha,$ad_fechaconta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_anulacion_asignacion
		//		   Access: public
		//	    Arguments: as_codasi  // Código de Asignacióna
		//	    		   adt_fecha  // Fecha de Anulación
		//	    		   ad_fechaconta  // Fecha de Contabilización
		//	    		   aa_seguridad  // Arreglo de seguridad
		//	      Returns: Retorna un boolean valido
		//	  Description: Este metodo tiene como fin anular una asignación contabilizada	
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 30/04/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $ls_codemp=$this->io_codemp;
        $ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante($as_codasi);		
        $this->dts_data->resetds("codasi"); // inicializa el datastore en 0 registro.
		if(!$this->uf_select_asignacion($as_codasi))
		{
			$this->io_msg->message(" No existe la Asignación N° ".$as_codasi);
			return false;
		}		
		$ldt_fecasi=$this->io_function->uf_convertirfecmostrar($this->dts_data->getValue("fecasi",1));
		$ldt_fecha_anula=$this->io_function->uf_convertirfecmostrar($adt_fecha);
		$ls_descripcion=$this->dts_data->getValue("desobr",1); 
		$ls_codigo_destino=$this->dts_data->getValue("cod_pro",1);	
        $ls_mensaje=$this->io_sigesp_int_spg->uf_operacion_codigo_mensaje("PC");	
        $ls_tipo_destino="P";		
        $ls_procede="SOBASI";
        $ls_procede_anula="SOBRAS";
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		$li_tipo_comp=1; // comprobante Normal
		$this->as_procede=$ls_procede_anula;
		$this->as_comprobante=$ls_comprobante;
		$this->ad_fecha=$this->io_function->uf_convertirdatetobd($ldt_fecha_anula);
		$this->as_codban=$ls_codban;
		$this->as_ctaban=$ls_ctaban;
		$lb_valido=$this->io_sigesp_int->uf_int_anular($ls_codemp,$ls_procede,$ls_comprobante,$ad_fechaconta,$ls_procede_anula,
		                                               $ldt_fecha_anula,$ls_descripcion,$ls_codban,$ls_ctaban,$li_tipo_comp);
		if(!$lb_valido)	
		{ 
			$this->io_msg->message("Error->".$this->io_sigesp_int->is_msg_error);
			return false; 
		}
		 // inicia transacción SQL
		$this->io_sigesp_int->uf_int_init_transaction_begin();
		if($lb_valido)
		{
			if ($lb_valido)
			{
				$lb_valido=$this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
				if(!$lb_valido)
				{
					$this->io_msg->message($this->io_sigesp_int->is_msg_error);
				}
			}
		}
		if($lb_valido)
		{
	        $lb_valido=$this->uf_update_estatus_contabilizado_asignacion($as_codasi,2);
		}
		if($lb_valido)
		{
			$adt_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
			$lb_valido=$this->uf_update_fecha_contabilizado_sob_asignacion($this->io_codemp,$as_codasi,'',$adt_fecha);
		}
	    if($lb_valido)
	    {
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Contabilizó la Anulación de la Asignación <b>".$as_codasi."</b>, Fecha de Anulación <b>".$ldt_fecha_anula."</b>";
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
		}*/
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
    }// end function uf_procesar_anulacion_asignacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_reverso_anulacion_asignacion($as_codasi,$ad_fechaanula,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_reverso_anulacion_asignacion
		//		   Access: public
		//	    Arguments: as_codasi  // Código de Asignacióna
		//	    		   ad_fechaanula  // Fecha de Anulación
		//	    		   aa_seguridad  // Arreglo de seguridad
		//	      Returns: Retorna un boolean valido
		//	  Description: Este metodo tiene como fin reversar al anulacion una asignación contabilizada
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 30/04/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=false;
		$ls_tipo_destino="P";		
		$ls_ced_bene="";
		$ls_cod_pro="";
		$ldt_fecha=$ad_fechaanula;
		$ls_procede="SOBRAS"; // reverso de anulación asignación.		
        $ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante($as_codasi);		
        $this->dts_data->resetds("codasi"); // inicializa el datastore en 0 registro.
		if(!$this->uf_select_asignacion($as_codasi))
		{
			$this->io_msg->message("ERROR-> No existe la Asignación N° ".$as_codasi);
			return false;
		}		
		$ls_codban="---";
		$ls_ctaban="-------------------------";
	    $lb_valido = $this->io_sigesp_int->uf_obtener_comprobante($this->io_codemp,$ls_procede,$ls_comprobante,&$ldt_fecha,
		                                                          $ls_codban,$ls_ctaban,$ls_tipo_destino,&$ls_ced_bene,
																  &$ls_cod_pro);
		if(!$lb_valido) 
		{ 
			$this->io_msg->message("ERROR-> No existe el comprobante Nº ".$ls_comprobante."-".$ls_procede.".");
			return false;
		}
		$lb_valido=$this->io_sigesp_int->uf_init_delete($this->io_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_tipo_destino,
														$ls_ced_bene,$ls_cod_pro,false,$ls_codban,$ls_ctaban);
		if(!$lb_valido)
		{ 
			$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
			return false; 
		}
        $this->io_sigesp_int->uf_int_init_transaction_begin();
	    if ($lb_valido)
		{
			$lb_valido=$this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
			if(!$lb_valido)
			{
				$this->io_msg->message($this->io_sigesp_int->is_msg_error);
			}
		}
		if($lb_valido)
		{
	        $lb_valido=$this->uf_update_estatus_contabilizado_asignacion($as_codasi,1);		
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_sob_asignacion($this->io_codemp,$as_codasi,'','1900-01-01');
		}
	    if($lb_valido)
	    {
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reverso la Anulación de la Asignación <b>".$as_codasi."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}		
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
        return $lb_valido;		
    }// end function uf_procesar_reverso_anulacion_asignacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_contabilizacion_contrato($as_codcon,$as_codasi,$adt_fecha,$ad_fechacontaasig,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contabilizacion_contrato
		//		   Access: public
		//	    Arguments: as_codcon  // Código de Contrato
		//	    		   as_codasi  // Código de Asignación
		//	    		   adt_fecha  // Fecha del Contrato
		//	    		   ad_fechacontaasig  // Fecha de Contabilización de la Asignación
		//	    		   aa_seguridad  // Arreglo de seguridad
		//	      Returns: Retorna un boolean valido
		//	  Description: Este metodo tiene como fin contabilizar en presupuesto el compromiso del contrato
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 30/04/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $ls_codemp= $this->dts_empresa["codemp"];
        $ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante($as_codcon);		
		$ldt_fecha=$this->io_function->uf_convertirfecmostrar($adt_fecha); 		
        $this->dts_data_contrato->resetds("codcon");
		if(!$this->uf_select_contrato($as_codcon,$as_codasi))
		{
			$this->io_msg->message(" No existe el Contrato N° ".$as_codcon);
			return false;
		}		
        // obtengo el monto de la Asignacion y la comparo con el monto de gasto acumulado		
        $ldec_sum_gasto=round($this->uf_sumar_total_cuentas_gasto_asignacion($as_codasi),2);
		$ldec_monto_asignacion=round($this->dts_data_contrato->getValue("montotasi",1),2);
		if($ldec_monto_asignacion!=$ldec_sum_gasto)
        {
			$this->io_msg->message("La Asignación del Contrato no esta cuadrado con el resumen presupuestario");
			return false;
        }       
		$ldt_feccon=$this->io_function->uf_convertirfecmostrar($this->dts_data_contrato->getValue("feccon",1));
		$ls_descripcion=$this->dts_data_contrato->getValue("desobr",1); 
		$ls_codigo_destino=$this->dts_data_contrato->getValue("cod_pro",1);	
        $ls_mensaje="O"; // Compromete
        $ls_tipo_destino="P";		
        $ls_procede="SOBCON"; // Procedencia Contrato Obras
        if(!$this->io_fecha->uf_comparar_fecha($ldt_feccon,$ldt_fecha))
		{
			$this->io_msg->message("La Fecha de Contabilizacion es menor que la fecha de Emision del Contrato Nº ".$as_codcon);
			return false;
		}
        if(!$this->io_fecha->uf_comparar_fecha($ad_fechacontaasig,$ldt_fecha))
		{
			$this->io_msg->message("La Fecha de Contabilizacion del Contrato es Menor que la Fecha de Contabilización de la Asignación ");
			return false;
		}
        $this->io_sigesp_int->uf_int_init_transaction_begin();
        $lb_valido=$this->uf_reversar_precomprometido_asignacion_contrato($as_codcon,$as_codasi,$ldt_fecha,$aa_seguridad);	
		if(!$lb_valido)
		{   
			$this->io_msg->message($this->io_sigesp_int->is_msg_error);
			$this->io_sigesp_int->uf_sql_transaction($lb_valido);
			return false;		   		   
		}
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		$li_tipo_comp=1; // comprobante Normal
		$this->as_procede=$ls_procede;
		$this->as_comprobante=$ls_comprobante;
		$this->ad_fecha=$this->io_function->uf_convertirdatetobd($ldt_fecha);
		$this->as_codban=$ls_codban;
		$this->as_ctaban=$ls_ctaban;
		$lb_valido=$this->io_sigesp_int->uf_int_init($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_descripcion,
													 $ls_tipo_destino,$ls_codigo_destino,false,$ls_codban,$ls_ctaban,
													 $li_tipo_comp);
		if (!$lb_valido)
		{   
			$this->io_msg->message($this->io_sigesp_int->is_msg_error);
			$this->io_sigesp_int->uf_sql_transaction($lb_valido);
			return false;		   		   
		}
		$lb_valido=$this->uf_procesar_detalles_gastos_asignacion($as_codasi,$ls_mensaje,$ls_procede,$ls_descripcion,"PC");
		if($lb_valido) 
		{
			if($lb_valido)
			{
				$lb_valido=$this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
				if(!$lb_valido)
				{
					$this->io_msg->message($this->io_sigesp_int->is_msg_error);
				}
			}
		}
		if($lb_valido) 
		{
			$lb_valido=$this->uf_update_estatus_contabilizado_contrato($as_codcon,1);
		}
		if($lb_valido)
		{
			$adt_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha); 		
			$lb_valido=$this->uf_update_fecha_contabilizado_sob_contrato($ls_codemp,$as_codcon,$as_codasi,$adt_fecha,'1900-01-01');
		}
	    if($lb_valido)
	    {
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Contabilizó el Contrato <b>".$as_codcon."</b>, Asignación <b>".$as_codasi."</b>, Fecha de Contabilización <b>".$adt_fecha."</b>";
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
		}*/
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return  $lb_valido;
	}// end function uf_procesar_contabilizacion_contrato
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_contrato($as_codcon,$as_codasi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_contrato
		//		   Access: public
		//	    Arguments: as_codcon  // Código de Contrato
		//	      Returns: Retorna un boolean valido
		//	  Description: Este metodo realiza una busqueda del contrato y la almacena en un datastore
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 30/04/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codemp=$this->io_codemp;
		$ls_sql="SELECT sob_contrato.*, sob_asignacion.cod_pro, sob_asignacion.montotasi, sob_obra.desobr ".
                "  FROM sob_contrato, sob_asignacion, sob_obra ".
                " WHERE sob_contrato.codemp='".$ls_codemp."' ".
				"   AND sob_contrato.codcon='".$as_codcon."' ".
				"   AND sob_contrato.codasi='".$as_codasi."' ".
				"   AND sob_contrato.codasi=sob_asignacion.codasi ".
				"   AND sob_obra.codobr=sob_asignacion.codobr ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración SOB MÉTODO->uf_select_contrato ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		else
		{                 
			if($row=$this->io_sql->fetch_row($rs_data))
			{
                $this->dts_data_contrato->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_select_contrato
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reversar_precomprometido_asignacion_contrato($as_codcon,$as_codasi,$adt_fecha,$aa_seguridad)	
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar_precomprometido_asignacion_contrato
		//		   Access: public
		//	    Arguments: as_codcon  // Código de Contrato
		//	    		   as_codasi  // Código de Asignación
		//	    		   adt_fecha  // Fecha de Reverso
		//	    		   aa_seguridad  // Arreglo de seguridad
		//	      Returns: Retorna un boolean valido
		//	  Description: Este método se encarga de preparar los datos básicos del comprobante de gasto 
		//                  y los detalles de gastos pero reverso (en negativo )
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 30/04/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $ls_codemp=$this->io_codemp;
		$ls_mensaje="R";
        $ls_tipo_destino="P";		
        $ls_procede="SOBRPC"; // REVERSO DE PRECOMPROMISO
        $ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante($as_codasi);				
		$ldt_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);		
        $this->dts_data->resetds("codasi"); // inicializa el datastore en 0 registro.
		if(!$this->uf_select_asignacion($as_codasi))
		{
			$this->io_msg->message(" No existe la Asignación N° ".$as_codasi. " asociada al contrato Nº ".$as_codcon);
			return false;
		}		
		$ls_descripcion=$this->dts_data->getValue("desobr",1); 
		$ls_codigo_destino = $this->dts_data->getValue("cod_pro",1);	
		if(empty($ls_descripcion))
		{
			$ls_descripcion="ninguno";
		}
		$this->io_sigesp_int->uf_int_config(true,false); 
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		$li_tipo_comp=1; // comprobante Normal
		$this->as_procedeaux=$ls_procede;
		$this->as_comprobanteaux=$ls_comprobante;
		$this->ad_fechaaux=$ldt_fecha;
		$this->as_codbanaux=$ls_codban;
		$this->as_ctabanaux=$ls_ctaban;
		$lb_valido=$this->io_sigesp_int->uf_int_init($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_descripcion,
												     $ls_tipo_destino,$ls_codigo_destino,true,$ls_codban,$ls_ctaban,
													 $li_tipo_comp);
		if(!$lb_valido)
		{   
			$this->io_msg->message($this->io_sigesp_int->is_msg_error); 
			return false;		   		   
		}
		$lb_valido = $this->uf_procesar_detalles_gastos_asignacion($as_codasi,$ls_mensaje,$ls_procede,$ls_descripcion,"CO");        		
	    if($lb_valido)
		{ 
			$lb_valido=$this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad);
  		}
	    if(!$lb_valido)
		{
			$this->io_msg->message("ERROR->".$this->io_sigesp_int->is_msg_error);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_sob_asignacion($this->io_codemp,$as_codasi,'',$ldt_fecha);
		}
	    if($lb_valido)
	    {
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reverso el Precompromiso de la Asignación <b>".$as_codasi."</b>, Fecha de Reverso <b>".$adt_fecha."</b>";
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
			$lb_valido=$this->io_fun_mis->uf_convertir_sigespcmp($this->as_procedeaux,$this->as_comprobanteaux,$this->ad_fechaaux,
																 $this->as_codbanaux,$this->as_ctabanaux,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_spgdtcmp($this->as_procedeaux,$this->as_comprobanteaux,$this->ad_fechaaux,
																$this->as_codbanaux,$this->as_ctabanaux,$aa_seguridad);
		}*/
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		return $lb_valido;
	}// end function uf_reversar_precomprometido_asignacion_contrato
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_contabilizado_contrato($as_codcon,$ai_estspgscg)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_contabilizado_contrato
		//		   Access: private
		//	    Arguments: as_codcon  // Código de Contrato
		//	    		   ai_estspgscg  // Estatus de Contabilización
		//	      Returns: Retorna un boolean valido
		//	  Description: Método que actualiza el estatus de contabilizacion de un contrato
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 30/04/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_sql="UPDATE sob_contrato ".
		        "   SET estspgscg=".$ai_estspgscg.
                " WHERE codemp='".$this->io_codemp."' ".
				"   AND codcon='".$as_codcon."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración SOB MÉTODO->uf_update_estatus_contabilizado_contrato ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_estatus_contabilizado_contrato
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_reverso_contrato($as_codcon,$as_codasi,$ad_fechaconta,$aa_seguridad)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_reverso_contrato
		//		   Access: public
		//	    Arguments: as_codcon  // Código de Contrato
		//	    		   as_codasi  // Código de Asignación
		//	    		   ad_fechaconta  // Fecha de Contabilización
		//	    		   aa_seguridad  // Arreglo de Seguridad
		//	      Returns: Retorna un boolean valido
		//	  Description: Este metodo tiene como fin reversar la contabilizacion del contrato y restaurar el precompromiso de la asignación
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 30/04/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $this->io_sigesp_int->uf_int_init_transaction_begin();
		$lb_valido=$this->uf_reverso_contrato_sob($as_codcon,$as_codasi,$ad_fechaconta,$aa_seguridad);
		if($lb_valido)
		{
			$lb_valido=$this->uf_delete_reverso_asignacion($as_codasi,$ad_fechaconta,$aa_seguridad);
		}
	    if($lb_valido)
	    {
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reverso el Contrato <b>".$as_codcon."</b>, Asignación <b>".$as_codasi."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}		
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
    }// end function uf_procesar_reverso_contrato
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reverso_contrato_sob($as_codcon,$as_codasi,$ad_fechaconta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_reverso_contrato
		//		   Access: public
		//	    Arguments: as_codcon  // Código de Contrato
		//	    		   as_codasi  // Código de Asignación
		//	    		   ad_fechaconta  // Fecha de Contabilización
		//	    		   aa_seguridad  // Arreglo de Seguridad
		//	      Returns: Retorna un boolean valido
		//	  Description: Este metodo tiene como fin reversar la contabilizacion del contrato y restaurar el precompromiso de la asignación
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 30/04/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;				
	    $ls_codemp=$this->dts_empresa["codemp"];
        $ls_procede="SOBCON"; 
		$ls_tipo_destino="P";						
        $ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante($as_codcon);		
		$this->dts_data_contrato->resetds("codcon"); // inicializa el datastore en 0 registro.		
		if(!$this->uf_select_contrato($as_codcon,$as_codasi))
		{
			$this->io_msg->message(" No existe el Contrato N° ".$as_codcon);
			return false;
		}		
		$ls_estspgscg=$this->dts_data_contrato->getValue("estspgscg",1);
		$ls_cod_pro=$this->dts_data_contrato->getValue("cod_pro",1);	
	    $ls_ced_bene="----------";
		if($ls_estspgscg!=1) 
		{
			$this->io_msg->message(" El Contrato ".$as_codcon." debe estar en estatus CONTABILIZADO para reversarlo.");
			return false;
		}
		$ldt_fecha=$ad_fechaconta;
		$ls_codban="---";
		$ls_ctaban="-------------------------";
	    $lb_valido=$this->io_sigesp_int->uf_obtener_comprobante($this->io_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,
																$ls_codban,$ls_ctaban,$ls_tipo_destino,$ls_ced_bene,$ls_cod_pro);
		if(!$lb_valido) 
		{ 
			$this->io_msg->message("ERROR-> No existe el comprobante Nº ".$ls_comprobante."-".$ls_procede.".");
			return false;
		}
		$lb_valido=$this->io_sigesp_int->uf_init_delete($this->io_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_tipo_destino,
														$ls_ced_bene,$ls_cod_pro,false,$ls_codban,$ls_ctaban);
		if(!$lb_valido)	
		{ 
			$this->io_msg->message("".$this->io_sigesp_int->is_msg_error);
			return false; 
		}
	    if($lb_valido)
		{
			$lb_valido=$this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
			if(!$lb_valido)
			{
				$this->io_msg->message($this->io_sigesp_int->is_msg_error);
			}
		}
	    if($lb_valido)
		{
	        $lb_valido=$this->uf_update_estatus_contabilizado_contrato($as_codcon,0);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_sob_contrato($this->io_codemp,$as_codcon,$as_codasi,'1900-01-01','1900-01-01');
		}
		return  $lb_valido;
	}// end function uf_reverso_contrato_sob
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_reverso_asignacion($as_codasi,$ad_fechaconta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_reverso_asignacion
		//		   Access: private
		//	    Arguments: as_codasi  // Código de Asignación
		//	    		   ad_fechaconta  // Fecha de Contabilización
		//	    		   aa_seguridad  // Arreglo de Seguridad
		//	      Returns: Retorna un boolean valido
		//	  Description: Método que elimina el reverso del precompromiso de asignación.
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 30/04/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
        $ls_procede="SOBRPC"; 
		$ls_tipo_destino="P";		
		$ls_ced_bene="";
		$ls_cod_pro="";
		$ldt_fecha=$ad_fechaconta;
		$lb_check_close=false;		
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		$ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante($as_codasi);
		$lb_valido = $this->io_sigesp_int->uf_obtener_comprobante($this->io_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,
																  $ls_codban,$ls_ctaban,$ls_tipo_destino,&$ls_ced_bene,
																  &$ls_cod_pro);
		if(!$lb_valido) 
		{ 
			$this->io_msg->message("ERROR-> No existe el comprobante Nº ".$ls_comprobante."-".$ls_procede.".");
			return false;
		}
		$lb_valido = $this->io_sigesp_int->uf_init_delete($this->io_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_tipo_destino,
														  $ls_ced_bene,$ls_cod_pro,$lb_check_close,$ls_codban,$ls_ctaban);
		
		if(!$lb_valido)	
		{
			$this->io_msg->message("ERROR->".$this->io_sigesp_int->is_msg_error);
			return false; 
		}
	    $lb_valido=$this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
	    if(!$lb_valido)
		{
			$this->io_msg->message("ERROR->".$this->io_sigesp_int->is_msg_error);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_sob_asignacion($this->io_codemp,$as_codasi,'','1900-01-01');
		}
	    if($lb_valido)
	    {
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reverso la Asignación <b>".$as_codasi."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}		
		return $lb_valido;
	}// end function uf_delete_reverso_asignacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_anular_contabilizacion_contrato($as_codcon,$as_codasi,$adt_fecha_anula,$ad_fechaconta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_anular_contabilizacion_contrato
		//		   Access: public
		//	    Arguments: as_codcon  // Código de Contrato
		//	    		   as_codasi  // Código de Asignación
		//	    		   adt_fecha_anula  // Fecha de Anulación
		//	    		   aa_seguridad  // Arreglo de Seguridad
		//	      Returns: Retorna un boolean valido
		//	  Description: Este metodo tiene como fin anular la contabilizacion del contrato
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 30/04/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $ls_codemp=$this->io_codemp;
		$ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante($as_codcon);
		$ls_procede="SOBCON";
		$ls_procede_anula="SOBACO";
        $ldt_fecha_cmp=$ad_fechaconta;
		$ls_tipo_destino="";
		$ls_ced_bene="";
		$ls_cod_pro="";
		$ls_descripcion="Anulación del Nº Contrato :".$as_codcon;
		$ldt_fecha_anula=$this->io_function->uf_convertirdatetobd($adt_fecha_anula);		 				

		$this->dts_data_contrato->resetds("codcon"); // inicializa el datastore en 0 registro.
		if(!$this->uf_select_contrato($as_codcon,$as_codasi))
		{
			$this->io_msg->message(" No existe el Contrato N° ".$as_codcon);
			return false;
		}		
		$ls_estspgscg=$this->dts_data_contrato->getValue("estspgscg",1);
		if($ls_estspgscg!=1) 
		{
			$this->io_msg->message(" El Contrato Nº ".$as_codcon." debe estar en estatus CONTABILIZADO.");
			return false;
		}
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		$li_tipo_comp=1; // comprobante Normal
	    $lb_valido=$this->io_sigesp_int->uf_obtener_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha_cmp,$ls_codban,
																$ls_ctaban,$ls_tipo_destino,&$ls_ced_bene,&$ls_cod_pro);
		if(!$lb_valido) 
		{ 
			$this->io_msg->message("ERROR-> No existe el comprobante Nº ".$ls_comprobante."-".$ls_procede.".");
			return false;
		}
		$this->as_procede=$ls_procede_anula;
		$this->as_comprobante=$ls_comprobante;
		$this->ad_fecha=$this->io_function->uf_convertirdatetobd($ldt_fecha_anula);
		$this->as_codban=$ls_codban;
		$this->as_ctaban=$ls_ctaban;
		$lb_valido = $this->io_sigesp_int->uf_int_anular($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha_cmp,$ls_procede_anula,
														 $ldt_fecha_anula,$ls_descripcion,$ls_codban,$ls_ctaban,$li_tipo_comp);
		if(!$lb_valido)	
		{ 
			$this->io_msg->message("ERROR->".$this->io_sigesp_int->is_msg_error);
			return false; 
		}
        $this->io_sigesp_int->uf_int_init_transaction_begin();
	    if($lb_valido)
	    {
	        $lb_valido = $this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
	        if(!$lb_valido)
		    {
				$this->io_msg->message("".$this->io_sigesp_int->is_msg_error);
		    }		   
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_delete_reverso_asignacion($as_codasi,$ad_fechaconta,$aa_seguridad);
		}
	    if($lb_valido)
		{
	        $lb_valido=$this->uf_update_estatus_contabilizado_contrato($as_codcon,2);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_sob_contrato($this->io_codemp,$as_codcon,$as_codasi,'',$ldt_fecha_anula);
		}
	    if($lb_valido)
	    {
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Anuló el Contrato <b>".$as_codcon."</b>, Asignación <b>".$as_codasi."</b>, Fecha de Anulación <b>".$ldt_fecha_anula."</b>";
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
		}*/
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
	}// end function uf_procesar_anular_contabilizacion_contrato
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_reverso_anulacion_contrato($as_codcon,$as_codasi,$ad_fechaconta,$ad_fechaanula,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_reverso_anulacion_contrato
		//		   Access: public
		//	    Arguments: as_codcon  // Código de Contrato
		//	    		   as_codasi  // Código de Asignación
		//	    		   ad_fechaconta  // Fecha en que fue contabilizado el contrato
		//	    		   ad_fechaanula  // Fecha en que fue anulado el contrato
		//	    		   aa_seguridad  // Arreglo de Seguridad
		//	      Returns: Retorna un boolean valido
		//	  Description: Este metodo tiene como fin anular la contabilizacion del contrato
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 02/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $ls_codemp=$this->io_codemp;
		$ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante($as_codcon);
		$ls_procede="SOBACO";
		$ls_tipo_destino="";
		$ls_ced_bene="";
		$ls_cod_pro="";
        $lb_check_close=false;
		$ldt_fecha=$ad_fechaanula;		 						
		$this->dts_data_contrato->resetds("codcon"); // inicializa el datastore en 0 registro.
		if(!$this->uf_select_contrato($as_codcon,$as_codasi))
		{
			$this->io_msg->message(" No existe el Contrato N° ".$as_codcon);
			return false;
		}		
		$ls_codban="---";
		$ls_ctaban="-------------------------";
	    $lb_valido=$this->io_sigesp_int->uf_obtener_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_codban,
																$ls_ctaban,$ls_tipo_destino,&$ls_ced_bene,&$ls_cod_pro);
		if(!$lb_valido)
		{ 
			$this->io_msg->message("ERROR-> No existe el comprobante Nº ".$ls_comprobante."-".$ls_procede.".");
			return false;
		}
		$lb_valido=$this->io_sigesp_int->uf_init_delete($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_tipo_destino,
														$ls_ced_bene,$ls_cod_pro,$lb_check_close,$ls_codban,$ls_ctaban);
		if(!$lb_valido)	
		{ 
			$this->io_msg->message("ERROR->".$this->io_sigesp_int->is_msg_error);
			return false; 
		}
        $this->io_sigesp_int->uf_int_init_transaction_begin();
	    if($lb_valido)
	    {
			$lb_valido = $this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
			if (!$lb_valido)
			{
				$this->io_msg->message("".$this->io_sigesp_int->is_msg_error);
			}		   
		}
	    if($lb_valido)
	    {
			if($lb_valido)
			{
		        $lb_valido=$this->uf_reversar_precomprometido_asignacion_contrato($as_codcon,$as_codasi,$ad_fechaconta,$aa_seguridad);
			}
		}	
	    if($lb_valido)
		{
	        $lb_valido=$this->uf_update_estatus_contabilizado_contrato($as_codcon,1);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_sob_contrato($ls_codemp,$as_codcon,$as_codasi,'','1900-01-01');
		}
	    if($lb_valido)
	    {
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reverso la Anulación del Contrato <b>".$as_codcon."</b>, Asignación <b>".$as_codasi."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}		
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
	}// end function uf_procesar_reverso_anulacion_contrato
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_fecha_contabilizado_sob_asignacion($as_codemp,$as_codasi,$ad_fechaconta,$ad_fechaanula)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_fecha_contabilizado_sob_asignacion
		//		   Access: private
		//	    Arguments: as_codemp  // Código
		//                 as_codasi  // Código de la Asignación
		//                 ad_fecha  // Fecha de contabilización ó de Anulación
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Método que actualiza la solicitud en estatus contabilizado
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 07/11/2006
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
		$ls_sql="UPDATE sob_asignacion ".
		        "   SET ".$ls_campos.
                " WHERE codemp='".$as_codemp."' ".
				"   AND codasi='".$as_codasi."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración SOB MÉTODO->uf_update_fecha_contabilizado_sob_asignacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_fecha_contabilizado_sob_asignacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_fecha_contabilizado_sob_contrato($as_codemp,$as_codcon,$as_codasi,$ad_fechaconta,$ad_fechaanula)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_fecha_contabilizado_sob_contrato
		//		   Access: private
		//	    Arguments: as_codemp  // Código
		//                 as_codcon  // Código del Contrato
		//                 as_codasi  // Código de la Asignación
		//                 ad_fecha  // Fecha de contabilización ó de Anulación
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Método que actualiza la solicitud en estatus contabilizado
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 07/11/2006
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
		$ls_sql="UPDATE sob_contrato ".
		        "   SET ".$ls_campos.
                " WHERE codemp='".$as_codemp."' ".
				"   AND codasi='".$as_codasi."' ".
				"   AND codcon='".$as_codcon."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración SOB MÉTODO->uf_update_fecha_contabilizado_sob_asignacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_fecha_contabilizado_sob_contrato
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_contabilizacion_anticipo($as_codcon,$as_codant,$adt_fecha,$adt_fechacontacontrato,$as_codtipdoc,$as_codpro,$ai_monto,
												  $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contabilizacion_anticipo
		//		   Access: private
		//	    Arguments: as_codcon  // Código del contrato
		//				   as_codant  // Código del anticipo
		//				   adt_fecha  // Fecha de contabilización
		//				   adt_fechacontacontrato  // Fecha de contabilización del contrato
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se genero la recepción de documento correctamente
		//	  Description: Método que registra la contabilizacion del anticipo
		//	   Creado Por: Ing. Yesenia Moreno	
		// Modificado Por: 																Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;
		$as_comprobante=substr($as_codcon.$as_codant,0,15);
		$as_comprobante=str_pad($as_comprobante,15,"0",0);
		$ls_ced_bene="----------";
		$ls_descripcion="ANTICIPO CONTRATO ".$as_codcon;
		$ls_tipo_destino="P";
		$ai_monto=str_replace(".","",$ai_monto);
		$ai_monto=str_replace(",",".",$ai_monto);
		$adt_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
		// inicia transacción SQL
		$this->io_sigesp_int->uf_int_init_transaction_begin(); 
		// Insertamos la Cabecera
		$ls_sql="INSERT INTO cxp_rd (codemp,numrecdoc,codtipdoc,ced_bene,cod_pro,dencondoc,fecemidoc, fecregdoc, fecvendoc,".
 		        "montotdoc, mondeddoc,moncardoc,tipproben,numref,estprodoc,procede,estlibcom,estaprord,fecaprord,usuaprord,".
				"estimpmun,codcla) VALUES ('".$this->io_codemp."','".$as_comprobante."','".$as_codtipdoc."','".$ls_ced_bene."',".
				"'".$as_codpro."','".$ls_descripcion."','".$adt_fecha."','".$adt_fecha."','".$adt_fecha."',".$ai_monto.
				",0,0,'".$ls_tipo_destino."','".$as_comprobante."','R','SOBANT',0,0,'1900-01-01','',0,'--')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{  
           	$this->io_msg->message("CLASE->Integración SOB MÉTODO->uf_procesar_contabilizacion_anticipo ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		if($lb_valido)
		{	// Insertar los detalles Contables
			$lb_valido=$this->uf_insert_recepcion_documento_contable($as_codcon,$as_codant,$as_comprobante,$as_codtipdoc,$ls_ced_bene,$as_codpro);
		}
	    if($lb_valido)
		{	// Actualizar el estatus en la nómina
			$lb_valido=$this->uf_update_contabilizado_sob_anticipo($as_codcon,$as_codant,$adt_fecha,'1900-01-01',1);
		}		
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Generó la Recepción de Documento  <b>".$as_comprobante."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);		
		return $lb_valido;
	}  // end function uf_procesar_contabilizacion_anticipo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_recepcion_documento_contable($as_codcon,$as_codant,$as_comprobante,$as_codtipdoc,$as_ced_bene,$as_cod_pro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_recepcion_documento_contable
		//		   Access: private
		//	    Arguments: as_codcon  // Código del contrato
		//				   as_codant  // Código del anticipo
		//	               as_comprobante  // Código de Comprobante
		//				   as_codtipdoc  // Tipo de Documento
		//				   as_ced_bene  // Cédula del Beneficiario
		//				   as_cod_pro  // Código del Proveedor
		//				   as_codcomapo  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se inserto los detalles contables en la recepción de documento correctamente
		//	  Description: Método que inserta los movimientos contables en la tabla de detalle de contable de la recepcion de documento
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_procede="SOBANT";
		$ls_sql="SELECT sob_anticipo.sc_cuenta AS cuentaanticipo, rpc_proveedor.sc_cuenta AS cuentaproveedor, sob_anticipo.monto ".
				"  FROM sob_anticipo, sob_contrato, sob_asignacion, rpc_proveedor  ".
				" WHERE sob_anticipo.codemp='".$this->io_codemp."' ".
				"   AND sob_anticipo.codant='".$as_codant."'".
				"   AND sob_anticipo.codcon='".$as_codcon."'".
				"   AND sob_anticipo.codemp=sob_contrato.codemp ".
				"   AND sob_anticipo.codcon=sob_contrato.codcon ".
				"   AND sob_asignacion.codemp=sob_contrato.codemp ".
				"   AND sob_asignacion.codasi=sob_contrato.codasi ".
				"   AND rpc_proveedor.codemp=sob_asignacion.codemp ".
				"   AND rpc_proveedor.cod_pro=sob_asignacion.cod_pro ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
           	$this->io_msg->message("CLASE->Integración SOB MÉTODO->uf_insert_recepcion_documento_contable ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{           
			while($row=$this->io_sql->fetch_row($rs_data) and ($lb_valido))
			{
				$ls_scg_cuenta = $row["cuentaanticipo"];
				$ldec_monto = $row["monto"];				
				$ls_debhab = "D";				
				$ls_documento = $as_comprobante;								 
				$ls_status="";
				$ls_denominacion="";
				if(!$this->io_sigesp_int_scg->uf_scg_select_cuenta($this->io_codemp,$ls_scg_cuenta,$ls_status,$ls_denominacion))
				{
					$this->io_msg->message("La cuenta contable ".trim($ls_scg_cuenta)." no exite en el plan de cuenta.");			
				}
				if($lb_valido)
				{
					$ls_sql="INSERT INTO cxp_rd_scg (codemp,numrecdoc,codtipdoc,ced_bene,cod_pro,procede_doc,numdoccom,debhab,".
							"sc_cuenta,monto) VALUES ('".$this->io_codemp."','".$as_comprobante."','".$as_codtipdoc."','".$as_ced_bene."',".
							"'".$as_cod_pro."','".$ls_procede."','".$ls_documento."','".$ls_debhab."','".$ls_scg_cuenta."',".$ldec_monto.")";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$this->io_msg->message("CLASE->Integración SOB MÉTODO->uf_insert_recepcion_documento_contable ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
						$lb_valido=false;
						break;
					}
				}
				if ($lb_valido)
				{
					$ls_scg_cuenta = $row["cuentaproveedor"];
					$ls_debhab = "H";				
					$ls_status="";
					$ls_denominacion="";
					if(!$this->io_sigesp_int_scg->uf_scg_select_cuenta($this->io_codemp,$ls_scg_cuenta,$ls_status,$ls_denominacion))
					{
						$this->io_msg->message("La cuenta contable ".trim($ls_scg_cuenta)." no exite en el plan de cuenta.");			
					}
					if($lb_valido)
					{
						$ls_sql="INSERT INTO cxp_rd_scg (codemp,numrecdoc,codtipdoc,ced_bene,cod_pro,procede_doc,numdoccom,debhab,".
								"sc_cuenta,monto) VALUES ('".$this->io_codemp."','".$as_comprobante."','".$as_codtipdoc."','".$as_ced_bene."',".
								"'".$as_cod_pro."','".$ls_procede."','".$ls_documento."','".$ls_debhab."','".$ls_scg_cuenta."',".$ldec_monto.")";
						$li_row=$this->io_sql->execute($ls_sql);
						if($li_row===false)
						{
							$this->io_msg->message("CLASE->Integración SOB MÉTODO->uf_insert_recepcion_documento_contable ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
							$lb_valido=false;
							break;
						}
					}
				}
			} // end while
		}
		$this->io_sql->free_result($rs_data);	 
		return $lb_valido;
    } // end function uf_insert_recepcion_documento_contable
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_contabilizado_sob_anticipo($as_codcon,$as_codant,$ad_fechaconta,$ad_fechaanula,$as_estatus)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_fecha_contabilizado_sob_anticipo
		//		   Access: private
		//	    Arguments: as_codcon  // Código del Contrato
		//                 as_codant  // Código del anticipo
		//                 ad_fecha  // Fecha de contabilización ó de Anulación
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Método que actualiza la solicitud en estatus contabilizado
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 07/11/2006
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
		$ls_campos="estspgscg=".$as_estatus.", ".$ls_campos."";
		$ls_sql="UPDATE sob_anticipo ".
		        "   SET ".$ls_campos.
                " WHERE codemp='".$this->io_codemp."' ".
				"   AND codant='".$as_codant."' ".
				"   AND codcon='".$as_codcon."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración SOB MÉTODO->uf_update_fecha_contabilizado_sob_asignacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_fecha_contabilizado_sob_anticipo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_reverso_anticipo($as_codcon,$as_codant,$as_cod_pro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_reverso_anticipo
		//		   Access: private
		//	    Arguments: as_comprobante  // Código de Comprobante
		//				   ad_fechaconta  // Fecha en que fue contabilizado el Documento
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el reverso correctamente
		//	  Description: Este metodo elimina la recepción de documento de una nómina
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$as_comprobante=substr($as_codcon.$as_codant,0,15);
		$as_comprobante=str_pad($as_comprobante,15,"0",0);
		$ls_ced_bene="----------";
		$ls_procede="SOBANT";
        $this->io_sigesp_int->uf_int_init_transaction_begin();
		$ls_sql="SELECT numsol ".
				"  FROM cxp_dt_solicitudes  ".
				" WHERE codemp='".$this->io_codemp."' ".
				"   AND numrecdoc='".$as_comprobante."' ".
				"   AND cod_pro='".$as_cod_pro."' ".
				"   AND ced_bene='".$ls_ced_bene."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
           	$this->io_msg->message("CLASE->Integración SOB MÉTODO->uf_procesar_reverso_anticipo ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			print $this->io_sql->message;			
			$lb_valido=false;
		}
		else
		{        
			if (!$rs_data->EOF)   
			{
				$lb_valido=false;
           		$this->io_msg->message("La recepción ".$as_comprobante." se encuentra en la solicitud de pago ".$rs_data->fields["numsol"]." por lo tanto no puede ser reversada.");			
			}
		}
		if ($lb_valido)
		{
			// Eliminamos los Detalles Contables
			$ls_sql="DELETE ".
					"  FROM cxp_rd_scg ".
					" WHERE codemp='".$this->io_codemp."' ".
					"   AND numrecdoc='".$as_comprobante."' ".
					"   AND cod_pro='".$as_cod_pro."' ".
					"   AND ced_bene='".$ls_ced_bene."'".
					"   AND procede_doc='".$ls_procede."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->Integración SOB MÉTODO->uf_procesar_reverso_anticipo ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				$lb_valido=false;
			}
		}
		if ($lb_valido)
		{
			// Eliminamos los Históricos de La Recepción de Documento
			$ls_sql="DELETE ".
					"  FROM cxp_historico_rd ".
					" WHERE codemp='".$this->io_codemp."' ".
					"   AND numrecdoc='".$as_comprobante."' ".
					"   AND cod_pro='".$as_cod_pro."' ".
					"   AND ced_bene='".$ls_ced_bene."'";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->Integración SOB MÉTODO->uf_procesar_reverso_anticipo ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				$lb_valido=false;
			}
		}
		if ($lb_valido)
		{
			// Eliminamos La Recepción de Documento
			$ls_sql="DELETE ".
					"  FROM cxp_rd ".
					" WHERE codemp='".$this->io_codemp."' ".
					"   AND numrecdoc='".$as_comprobante."' ".
					"   AND cod_pro='".$as_cod_pro."' ".
					"   AND ced_bene='".$ls_ced_bene."'".
					"   AND procede='".$ls_procede."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->Integración SOB MÉTODO->uf_procesar_reverso_anticipo ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				$lb_valido=false;
			}
		}
	    if($lb_valido)
		{	// Actualizar el estatus en la nómina
			$lb_valido=$this->uf_update_contabilizado_sob_anticipo($as_codcon,$as_codant,'1900-01-01','1900-01-01',0);
		}		
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reverso la Recepción de Documento  <b>".$as_comprobante."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
	}  // end function uf_procesar_reverso_anticipo
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>