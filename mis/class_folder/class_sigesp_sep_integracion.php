<?php
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  //       Class : class_sigesp_sep_integracion_php                                                     //    
  // Description : Esta clase tiene todos los metodos necesario para el manejo de la rutina integradora //
  //               con el sistema de presupuesto de  gasto y el sistema de solicitud presupuestaria.    //               
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
class class_sigesp_sep_integracion
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
	function class_sigesp_sep_integracion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: class_sigesp_sep_integracion
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 07/11/2006
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
		$this->io_xml=new class_funciones_xml();		
		$this->as_procede="";
		$this->as_comprobante="";
		$this->ad_fecha="";
		$this->as_codban="";
		$this->as_ctaban="";
	}// end function class_sigesp_sep_integracion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destroy_objects()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destroy_objects
		//		   Access: public 
		//	  Description: Destructor de los objectos de la Clase
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 07/11/2006
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
	function uf_procesar_contabilizacion_solicitud_sep($as_numsol,$as_fecha,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contabilizacion_solicitud_sep
		//		   Access: public (sigesp_mis_p_contabiliza_sep.php)
		//	    Arguments: as_numsol  // Número de Solicitud
		//				   as_fecha  // Fecha de contabilización
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Este metodo tiene como fin contabilizar en presupuesto la solicitud de ejecucion presupuestaria. 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 07/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $ls_codtipsol="";  		
	    $ls_estsol=""; // estatus solicitud
        $ldec_sum_gasto=0; // sumatoria de los movimiento de gastos presupuestario
	    $ls_codemp=$this->dts_empresa["codemp"];
		$this->is_msg_error = "";
        $this->dts_solicitud->resetds("numsol"); // inicializa el datastore solicitud en 0 registro.
		// Verificamos que la solicitud existe y de ser asi carga el datastored
		if(!$this->uf_select_solicitud_sep($as_numsol))
		{
			$this->io_msg->message("ERROR -> No existe la solicitud N° ".$as_numsol);
			return false;
		}		
		$ls_estsol=$this->dts_solicitud->getValue("estsol",1);
        $ls_codtipsol=$this->dts_solicitud->getValue("codtipsol",1);
		$ls_consol=$this->dts_solicitud->getValue("consol",1); 
        $ls_tipo_destino=$this->dts_solicitud->getValue("tipo_destino",1) ;
        $ls_ced_bene=$this->dts_solicitud->getValue("ced_bene",1); 
		$ls_cod_pro=$this->dts_solicitud->getValue("cod_pro",1);	
        $ls_mensaje=$this->dts_solicitud->getValue("estope",1);	
		if($ls_estsol!="E") 
		{
			$this->io_msg->message("ERROR -> No  Solicitud ".$as_numsol." debe estar en estatus EMITIDA para su contabilización.");
			return false ;
		}
		// obtengo la fecha de la solicitud del datastore
		$ls_fecsol=$this->dts_solicitud->getValue("fecregsol",1);
		$ls_fecsol=$this->io_function->uf_convertirdatetobd($ls_fecsol);
		$as_fecha=$this->io_function->uf_convertirdatetobd($as_fecha);
        if(!$this->io_fecha->uf_comparar_fecha($ls_fecsol,$as_fecha))
		{
		   $this->io_msg->message("ERROR -> La Fecha de Contabilización es menor que la fecha de Emision de la Solicitud Nº ".$as_numsol);
		   return false ;
		}
        // obtengo el monto de la SEP y la comparo con el monto de gasto acumulado		
        $ldec_sum_gasto=$this->uf_sumar_total_cuentas_gasto_solicitud_sep($as_numsol);
        $ldec_sum_gasto=round($ldec_sum_gasto,2);
		$ldec_monto_solicitud=$this->dts_solicitud->getValue("monto",1);
        $ldec_monto_solicitud=round($ldec_monto_solicitud,2);         
		if(trim($_SESSION["la_empresa"]["confiva"])=="P") // si el iva es presupuestario
		{
			if($ldec_monto_solicitud!=$ldec_sum_gasto)
			{
				$this->io_msg->message("ERROR -> La S.E.P. no esta cuadrado con el resumen presupuestario");
				return false;
			}       
		}
        // Inicio transacción SQL
        $this->io_sigesp_int->uf_int_init_transaction_begin();
		$lb_valido=$this->uf_procesar_comprobante_spg_solicitud($as_numsol,$ls_fecsol,$ls_consol,$ls_tipo_destino,$ls_ced_bene,
																$ls_cod_pro,$as_fecha,$ls_codtipsol,$ls_consol,$ls_mensaje,"C");
		if(!$lb_valido)
		{
			$this->io_sigesp_int->uf_sql_transaction($lb_valido);
			return false;
		}           
		else
		{
			$lb_valido=$this->uf_update_estatus_contabilizado_sep($ls_codemp,$as_numsol,'C');
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
			}		   
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_sep($ls_codemp,$as_numsol,$as_fecha,'1900-01-01');
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Contabilizó la Solicitud de Ejecución Presupuestaria Número de Solicitud <b>".$as_numsol."</b>, ".
							"Fecha de Contabilización <b>".$as_fecha."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if(($lb_valido)&&($this->dts_empresa["estintcred"]=='1'))
		{
			$this->uf_moverxml($as_numsol);
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return  $lb_valido;
	}// end function uf_procesar_contabilizacion_solicitud_sep
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitud_sep($ls_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_solicitud_sep
		//		   Access: private
		//	    Arguments: ls_numsol  // Número de Solicitud
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Este metodo tiene com funcion determinar si existe o no una solicitud de ejecucion presupuestaria y 
		//                  si existe este debera vaciar la informacion del sep o registro en una matriz datastore.
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 07/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_sql="SELECT s.*, ts.estope ".
                "  FROM sep_solicitud s,sep_tiposolicitud ts ".
                " WHERE s.codtipsol=ts.codtipsol ".
				"   AND s.codemp='".$ls_codemp."' ".
				"   AND s.numsol='".$ls_numsol."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración SEP MÉTODO->uf_select_solicitud_sep ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido = true; // si existe se procedera a registrar en el datastore.				
                $this->dts_solicitud->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$lb_valido = false;
				$this->is_msg_error="La solicitud no existe en la base de datos.";
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_select_solicitud_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_sumar_total_cuentas_gasto_solicitud_sep($ls_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sumar_total_cuentas_gasto_solicitud_sep
		//		   Access: private
		//	    Arguments: ls_numsol  // Número de Solicitud
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Este método realiza una consulta sql para obtener la sumatoria de todos los movimiento de gasto
		//                  asociado a la solicitud de ejeciución presupuestaria en la tabla SEP_CuentaGasto
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 07/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $ldec_monto=0;
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_sql="SELECT SUM(CASE WHEN monto IS NULL THEN 0 ELSE monto END) As monto ".
                "  FROM sep_cuentagasto ".
                " WHERE codemp='".$ls_codemp."' ".
				"   AND numsol='".$ls_numsol."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración SEP MÉTODO->uf_sumar_total_cuentas_gasto_solicitud_sep ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
			   $ldec_monto = $row["monto"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $ldec_monto;
	}// end function uf_sumar_total_cuentas_gasto_solicitud_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_comprobante_spg_solicitud($as_numsol,$adt_fecsol,$as_consol,$as_tipo_destino,$as_ced_bene,$as_cod_pro,
												   $adt_fecha,$as_codtipsol,$as_descripcion,$as_mensaje,$as_tipo)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_comprobante_spg_solicitud
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud
        //                 adt_fecsol // fecha de la soliciutd
        //                 as_consol  // concepto solicitud    
        //                 as_tipo_destino // tipo destino B=BENEFICIARIO P=PROVEEDOR '-'=NINGUNO
        //                 as_ced_bene // Cédula del beneficiario
		//                 as_cod_pro // código del proveedor
		//                 adt_fecha // Fecha de Contabilización
		//                 as_codtipsol // tipo de solicitud
		//                 as_descripcion // Descripción del Comprobante
		//                 as_mensaje // Mensaje
		//                 as_tipo // tipo de operacion si es contabilización ó si es anulación
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Método que procesa todos los registros presupuestario mediante un ciclo while
		//                  usando la integracion por lote del sigesp de gasto.
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 07/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_procededoc="SEPSPC";
        $ls_codemp=$this->dts_empresa["codemp"];
		switch($as_tipo)
		{
			case "C": // se va a contabilizar
		        $ls_procede="SEPSPC"; // procedencia u origen del documento a ser enviado al gasto. (INTEGRACION)
				break;
			case "A": // se va a Anular
		        $ls_procede="SEPSPA"; // procedencia u origen del documento a ser enviado al gasto. (INTEGRACION)
				break;
		}		
        $ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante(trim($as_numsol));
        $ls_fecha=$adt_fecsol;
		if(empty($as_descripcion))
		{  
			$as_descripcion = "SOLICTUD Nº ".$as_numsol;
		}
        $ls_codigo_destino="-"; 
        if ($as_tipo_destino=="P")
		{
			$ls_codigo_destino=$as_cod_pro; 
		}
        else
        {
			$ls_codigo_destino=$as_ced_bene; 
        }
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		$li_tipo_comp=1; // comprobante Normal
		$this->as_procede=$ls_procede;
		$this->as_comprobante=$ls_comprobante;
		$this->ad_fecha=$adt_fecha;
		$this->as_codban=$ls_codban;
		$this->as_ctaban=$ls_ctaban;
		$lb_valido = $this->io_sigesp_int->uf_int_init($ls_codemp,$ls_procede,$ls_comprobante,$adt_fecha,$as_descripcion,
													   $as_tipo_destino,$ls_codigo_destino,true,$ls_codban,$ls_ctaban,
													   $li_tipo_comp);
		if (!$lb_valido)
		{   
			$this->is_msg_error=$this->io_sigesp_int->is_msg_error; 
			return false;		   		   
		}
        // Recorro la tabla que contiene los movimientos contables presupuestarios 
		$ls_sql="SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla,spg_cuenta,monto ".
				"  FROM sep_cuentagasto ".
	            " WHERE codemp='".$ls_codemp."' ".
				"   AND numsol='".$as_numsol."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
            $this->io_msg->message("CLASE->Integración SEP MÉTODO->uf_procesar_comprobante_spg_solicitud ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		    return false;		   
		}
		else
		{                 
			while($row=$this->io_sql->fetch_row($rs_data)and($lb_valido))
			{
				$ls_codestpro1=$row["codestpro1"];
				$ls_codestpro2=$row["codestpro2"];
				$ls_codestpro3=$row["codestpro3"];
				$ls_codestpro4=$row["codestpro4"];
				$ls_codestpro5=$row["codestpro5"];			  
				$ls_estcla=$row["estcla"];			  
				$ls_spg_cuenta=$row["spg_cuenta"];			
				switch($as_tipo)
				{
					case "C": // se va a contabilizar
						$ldec_monto=$row["monto"];
						break;
					case "A": // se va a Anular
						$ldec_monto=(-1*$row["monto"]);
						break;
				}		
				$ls_descripcion="";
				$lb_valido = $this->io_sigesp_int->uf_spg_insert_datastore($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																	  	   $ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta,$as_mensaje,
																	  	   $ldec_monto,$ls_comprobante,$ls_procededoc,$ls_descripcion);
				if (!$lb_valido)
				{  
					$this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error);
					break;
				}
			} // end while
	   	} // end if 
	    $this->io_sql->free_result($rs_data);	 
	    return $lb_valido;
    }// end function uf_procesar_comprobante_spg_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_contabilizado_sep($as_codemp,$as_numsol,$as_estsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_contabilizado_sep
		//		   Access: private
		//	    Arguments: as_codemp  // Código
		//                 as_numsol  // numero de la solicitud  
		//                 as_estsol  // Estatus
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Método que actualiza la solicitud en estatus contabilizado
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 07/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_sql="UPDATE sep_solicitud ".
		        "   SET estsol='".$as_estsol."' ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND numsol='".$as_numsol."'";
		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración SEP MÉTODO->uf_update_estatus_contabilizado_sep ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_estatus_contabilizado_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reversar_contabilizacion_solicitud_sep($as_numsol,$adt_fecha,$aa_seguridad)	
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar_contabilizacion_solicitud_sep
		//		   Access: public (sigesp_mis_p_reverso_sep.php)
		//	    Arguments: as_numsol  // Número de Solicitud
		//				   adt_fecha  // Fecha en que fue contabilizado el documento
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Este metodo reversa contablemente una sep ya registrada precomprometida 
		//                  presupuestariamente.
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 07/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
        $ls_codemp=$this->dts_empresa["codemp"];
        $ls_procede="SEPSPC"; // procedencia u origen del documento a ser enviado al gasto. (INTEGRACION)
        $ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante(trim($as_numsol));
        $this->dts_solicitud->resetds("numsol"); // inicializa el datastore solicitud en 0 registro.
		if(!$this->uf_select_solicitud_sep($as_numsol))
		{
			$this->io_msg->message("ERROR -> No existe la solicitud N° ".$as_numsol);
			return false;
		}		
        $ls_tipo_destino=$this->dts_solicitud->getValue("tipo_destino",1);
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
		$lb_valido=$this->io_sigesp_int->uf_init_delete($ls_codemp,$ls_procede,$ls_comprobante,$adt_fecha,$ls_tipo_destino,
													    $ls_ced_bene,$ls_cod_pro,$lb_check_close,$ls_codban,$ls_ctaban);
		if($lb_valido===false)	
		{ 
			$this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error);
			return false; 
		}
        $this->io_sigesp_int->uf_int_init_transaction_begin();
		$lb_valido=$this->uf_update_estatus_contabilizado_sep($ls_codemp,$as_numsol,'E');		
    	if (!$lb_valido)
		{
			$this->io_msg->message("ERROR -> Al cambiar estatus SEP");
			$this->io_sigesp_int->uf_sql_transaction($lb_valido);
			return false;
		}
		else
		{
		   $lb_valido = $this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
		   if ( $lb_valido===false)
		   {
             $this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error);
		   }		   
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_sep($ls_codemp,$as_numsol,'1900-01-01','1900-01-01');
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reversó la Contabilización de la Solicitud de Ejecución Presupuestaria Número de Solicitud <b>".$as_numsol."</b> ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
	} // end function uf_reversar_contabilizacion_solicitud_sep()
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_anulacion_solicitud_sep($as_numsol,$adt_fecha,$as_fecha,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_anulacion_solicitud_sep
		//		   Access: public (sigesp_mis_p_anula_sep.php)
		//	    Arguments: as_numsol  // Número de Solicitud
		//				   adt_fecha  // Fecha de Contabilización
		//				   as_fecha  // Fecha de Anulación
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto la anulación correctamente
		//	  Description: Este metodo tiene como fin anular en presupuesto la solicitud de ejecucion presupuestaria. 
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 													Fecha Última Modificación : 03/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $ls_codtipsol="";  		
	    $ls_estsol=""; // estatus solicitud
        $ldec_sum_gasto=0; // sumatoria de los movimiento de gastos presupuestario
		$this->is_msg_error = "";
		$lb_valido=false;
		$as_fecha=$this->io_function->uf_convertirdatetobd($as_fecha);
        $ls_codemp=$this->dts_empresa["codemp"];
        $ls_procede="SEPSPC"; // procedencia u origen del documento a ser enviado al gasto. (INTEGRACION)
        $ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante(trim($as_numsol));
        $this->dts_solicitud->resetds("numsol"); // inicializa el datastore solicitud en 0 registro.
		if(!$this->uf_select_solicitud_sep($as_numsol))
		{
			$this->io_msg->message("ERROR -> No existe la solicitud N° ".$as_numsol);
			return false;
		}		
        $ls_tipo_destino=$this->dts_solicitud->getValue("tipo_destino",1);
		$ls_ced_bene=$this->dts_solicitud->getValue("ced_bene",1); 
		$ls_cod_pro=$this->dts_solicitud->getValue("cod_pro",1);	
		$ls_codban="---";
		$ls_ctaban="-------------------------";
	    $lb_valido=$this->io_sigesp_int->uf_obtener_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$adt_fecha,
																  $ls_codban,$ls_ctaban,$ls_tipo_destino,$ls_ced_bene,
																  $ls_cod_pro);
		if(!$lb_valido) 
		{ 
			$this->io_msg->message("ERROR-> No existe el comprobante Nº ".$ls_comprobante."-".$ls_procede.".");
			return false;
		}
        if(!$this->io_fecha->uf_comparar_fecha($adt_fecha,$as_fecha))
		{
			$this->io_msg->message("ERROR -> La Fecha de Anulación es menor que la fecha de Contabilización de la Solicitud Nº ".$as_numsol);
			return false;
		}
		$ls_estsol=$this->dts_solicitud->getValue("estsol",1);
        $ls_codtipsol=$this->dts_solicitud->getValue("codtipsol",1);
		$ls_consol=$this->dts_solicitud->getValue("consol",1); 
        $ls_tipo_destino=$this->dts_solicitud->getValue("tipo_destino",1) ;
        $ls_ced_bene=$this->dts_solicitud->getValue("ced_bene",1); 
		$ls_cod_pro=$this->dts_solicitud->getValue("cod_pro",1);	
        $ls_mensaje=$this->dts_solicitud->getValue("estope",1);	
		if($ls_estsol!="C") 
		{
			$this->io_msg->message("ERROR -> No  Solicitud ".$as_numsol." debe estar en estatus CONTABILIZADA para su anulación.");
			return false ;
		}
		// obtengo la fecha de la solicitud del datastore
		$ls_fecsol=$this->dts_solicitud->getValue("fecregsol",1);
		$ls_fecsol=$this->io_function->uf_convertirdatetobd($ls_fecsol);
        if(!$this->io_fecha->uf_comparar_fecha($ls_fecsol,$as_fecha))
		{
		   $this->io_msg->message("ERROR -> La Fecha de Anulación es menor que la fecha de Emision de la Solicitud Nº ".$as_numsol);
		   return false ;
		}
        // obtengo el monto de la SEP y la comparo con el monto de gasto acumulado		
        $ldec_sum_gasto=$this->uf_sumar_total_cuentas_gasto_solicitud_sep($as_numsol);
        $ldec_sum_gasto=round($ldec_sum_gasto,2);
		$ldec_monto_solicitud=$this->dts_solicitud->getValue("monto",1);
        $ldec_monto_solicitud=round($ldec_monto_solicitud,2);           
		if(trim($_SESSION["la_empresa"]["confiva"])=="P") // si el iva es presupuestario
		{
			if($ldec_monto_solicitud!=$ldec_sum_gasto)
			{
				$this->io_msg->message("ERROR -> La S.E.P. no esta cuadrado con el resumen presupuestario");
				return false;
			}       
		} 
        // Inicio transacción SQL
        $this->io_sigesp_int->uf_int_init_transaction_begin();
		$lb_valido=$this->uf_procesar_comprobante_spg_solicitud($as_numsol,$ls_fecsol,$ls_consol,$ls_tipo_destino,$ls_ced_bene,
																$ls_cod_pro,$as_fecha,$ls_codtipsol,$ls_consol,$ls_mensaje,"A");
		if(!$lb_valido)
		{
			$this->io_sigesp_int->uf_sql_transaction($lb_valido);
			return false;
		}           
		else
		{
			$lb_valido=$this->uf_update_estatus_contabilizado_sep($ls_codemp,$as_numsol,'A');
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
			}		   
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_sep($ls_codemp,$as_numsol,$adt_fecha,$as_fecha);
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Anuló la Solicitud de Ejecución Presupuestaria Número de Solicitud <b>".$as_numsol."</b>, ".
							"Fecha de Anulación <b>".$as_fecha."</b>";
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
	}// end function uf_procesar_anulacion_solicitud_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_fecha_contabilizado_sep($as_codemp,$as_numsol,$ad_fechaconta,$ad_fechaanula)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_fecha_contabilizado_sep
		//		   Access: private
		//	    Arguments: as_codemp  // Código
		//                 as_numsol  // numero de la solicitud  
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
		$ls_sql="UPDATE sep_solicitud ".
		        "   SET ".$ls_campos.
                " WHERE codemp='".$as_codemp."' ".
				"   AND numsol='".$as_numsol."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración SEP MÉTODO->uf_update_fecha_contabilizado_sep ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_fecha_contabilizado_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_moverxml($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_moverxml
		//		   Access: private
		//	    Arguments: as_numsol  // numero de la solicitud  
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Método que en caso de existir la ruta y los archivos mueve un XML dado
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		// Modificado Por: 														Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if (is_dir("../scc/aprobacion/pendientes/"))//Verificación del Directorio destino.
		{
			$la_archivos=$this->io_xml->uf_load_archivos("../scc/aprobacion/pendientes/");
			$li_totalarchivos=count($la_archivos["filnam"]);
			if($la_archivos=="")
			{
				$li_totalarchivos=0;
			}
			for($li_i=1;$li_i<=$li_totalarchivos;$li_i++)
			{
				$ls_archivo = $la_archivos["filnam"][$li_i];
				$ls_rutori  = "../scc/aprobacion/pendientes/".$ls_archivo;
				$ls_numsol  = $this->io_xml->uf_obtener_nrosep($ls_rutori);
				if($ls_numsol==$as_numsol)
				{
					$this->io_xml->uf_mover_xml($ls_archivo,$ls_rutori,"../scc/aprobacion/procesados/");
				}
			}
		}
	}// end function uf_moverxml
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_reverso_anulacion($as_numsol,$ad_fechaconta,$ad_fechaanula,$aa_seguridad)
	{
	   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_reverso_anulacion
		//         Access: public
		//     Argumentos: as_numsol      // numero de solicitud
		//                 ad_fechaconta     // Fecha en que fue contabilizado el documento	
		//                 ad_fechaanula     // Fecha en que fue anulado el documento	
		//				   aa_seguridad // Arreglo de las variables de seguridad
		//	      Returns: Retorna un boleano 
		//	  Description: Método que reversa una anulacion. 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 20/12/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $ls_codemp=$this->dts_empresa["codemp"];
		$ls_comprobante=$as_numsol;		
		$lb_valido=true;	
		$ls_procede="SEPSPA";
		$ls_tipo_destino="";
		$ls_ced_bene="";
		$ls_cod_pro="";
		$ldt_fecha=$ad_fechaanula;
	    $lb_valido=$this->uf_select_solicitud_sep($as_numsol);
		if(!$lb_valido)
		{
			return false;
		}
		$ls_estsol=$this->dts_solicitud->getValue("estsol",1);
		if($ls_estsol!="A") 
		{
			$this->io_msg->message("ERROR-> La Solicitud ".$as_numsol." no está ANULADA.");
			return false;
		}
		$ls_codban="---";
		$ls_ctaban="-------------------------";
	    if($lb_valido)
	    {
			$lb_valido=$this->io_sigesp_int->uf_obtener_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_codban,
																	$ls_ctaban,$ls_tipo_destino,&$ls_ced_bene,&$ls_cod_pro);
		}
		if(!$lb_valido) 
		{ 
			$this->io_msg->message("ERROR-> No existe el comprobante Nº ".$ls_comprobante."-".$ls_procede.".");
			return false;
		}
/*	    if($lb_valido)
	    {
			$lb_valido=$this->uf_verificar_sep_ordencompra($ls_codemp,$as_numordcom,$as_estcondat,$ad_fechaconta,$aa_seguridad);
		}
*/        $lb_check_close=false;
	    if($lb_valido)
	    {
			$lb_valido=$this->io_sigesp_int->uf_init_delete($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_tipo_destino,
															$ls_ced_bene,$ls_cod_pro,$lb_check_close,$ls_codban,$ls_ctaban);
		}
		if(!$lb_valido )	
		{ 
			$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
			return false; 
		}
        $this->io_sigesp_int->uf_int_init_transaction_begin();
	    $ls_estsol="C"; // estatus en pre-comprometida
	    if($lb_valido)
	    {
	        $lb_valido=$this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
	        if(!$lb_valido)
		    {
				$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
		    }		   
		}
	    if($lb_valido)
	    {
	        $lb_valido=$this->uf_update_estatus_contabilizado_sep($ls_codemp,$as_numsol,$ls_estsol);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_sep($ls_codemp,$as_numsol,$ad_fechaconta,'1900-01-01');
		}
/*		if($lb_valido)
		{
			// Actualiza el estatus de la sep que esta asociada a la orden de compra
			$lb_valido=$this->uf_update_estatus_sep_ordendecompra($ls_codemp,$as_numordcom,$as_estcondat,"P");
		}
*/		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reverso la Anulación la Solicitud de Ejecución Presupuestaria <b>".$as_numsol."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		// Se Finaliza la transacción con Commit ó Rollback de acuerdo al $lb_valido
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
    } // end function uf_procesar_reverso_anulacion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
}
?>