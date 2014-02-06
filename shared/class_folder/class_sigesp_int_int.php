<?php  
ini_set('precision','15');
class class_sigesp_int_int extends class_sigesp_int
{
	var $ds_scg;  // Matriz de datos de movimiento de contable
	var $ds_spg;  // Matriz de datos de movimiento de gastos
	var $ds_spi;  // Matriz de datos de movimiento de ingresos
	var $int_spg;   
	var $int_scg; 
	var $int_spi;  
	var $io_function;	
	var $io_connect;
	var $io_include;
	var $io_msg;
	var $io_sql;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function class_sigesp_int_int()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: class_sigesp_int_int
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $this->dat_emp=$_SESSION["la_empresa"];
		$this->io_msg=new class_mensajes();
		$this->int_fecha=new class_fecha();
		$this->int_spg=new class_sigesp_int_spg();
		$this->int_scg=new class_sigesp_int_scg();
		$this->int_spi=new class_sigesp_int_spi();
		$this->io_include=new sigesp_include();
		$this->io_connect=$this->io_include->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);
		$this->io_function = new class_funciones();
		$this->io_seguridad=new sigesp_c_seguridad() ;
	}// end function class_sigesp_int_int
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_int_init_transaction_begin()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_int_init_transaction_begin
		//		   Access: public 
		//	  Description: Inicia la transaccion de base de datos para el registro multiples de datos 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sql->begin_transaction();
		$this->is_msg_error = "";
		return true;
	}// end function uf_int_init_transaction_begin
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_sql_transaction($ab_valido)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sql_transaction
		//		   Access: public 
		//       Argument: lb_valido // si el proceso fue valido ó no
		//	  Description: Realiza el commit o rollback de las transacciones de base de datos en lote
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($ab_valido===true)
		{
			$this->io_sql->commit();
		}
		else
		{	
			$this->io_sql->rollback();
		}
	}// end function uf_sql_transaction
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_int_init($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$as_descripcion,$as_tipo,$as_fuente,
						 $ab_spg_enlace_contable,$as_codban,$as_ctaban,$ai_tipo_comp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_int_init
		//		   Access: public 
		//       Argument: as_codemp // Código de empresa
		//       		   as_procedencia // Procedencia del documento
		//       		   as_comprobante // Número de Comprobante
		//       		   as_fecha // Fecha del Comprobante
		//       		   as_descripcion // Descripción del comprobante
		//       		   as_tipo // Tipo de Destino si es proveedor ó  Beneficiario
		//       		   as_fuente // Código de Proveedor ó Benficiario dependiendo del tipo
		//       		   ab_spg_enlace_contable // Si tiene enlace contable
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta de Banco
		//       		   ai_tipo_comp // Tipo de comprobante 1-> si es un comprobante Normal 2-> Si es una modificación Presupuestaria
		//	  Description: Este método inicia la transacción de un nuevo comprobante SPI,SPG,SCG. Creara los datastore para
		//                 guardar los movimientos contables del nucleo y las validaciones necesaria de la transacción
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true; 
		$this->is_msg_error="";
		if(!($this->uf_init_valida_parametros_comprobante($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$as_descripcion,
														  $as_tipo,$as_fuente)))
		{
	   		return false;
		}
		if($this->uf_select_comprobante($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$as_codban,$as_ctaban))
		{
			$this->is_msg_error="El Comprobante ".$as_comprobante." ya existe";
			return false;
		}	
		$this->uf_init_destroy_datastore();
		$this->uf_init_create_datastore();	
		// asigno los valores de parametros a las instancia del objeto integracion
		$this->is_codemp=$as_codemp;
		$this->is_procedencia=$as_procedencia;
		$this->is_comprobante=$as_comprobante;
		$this->id_fecha=$as_fecha;
		$this->as_codban=$as_codban;
		$this->as_ctaban=$as_ctaban;
		$_SESSION["fechacomprobante"]=$as_fecha;
		$this->is_descripcion=$as_descripcion;
		$this->is_tipo=$as_tipo;
		$this->idec_monto=0;
		$this->idec_monto_debe=0;		
		$this->idec_monto_haber=0;
		$this->ii_tipo_comp=$ai_tipo_comp;
		$this->ib_spg_enlace_contable = $ab_spg_enlace_contable;
		if($as_tipo=="B")
		{
			$this->is_ced_ben=$as_fuente;
			$this->is_cod_prov="----------"; 
		}
		if($as_tipo=="P")
		{
			$this->is_ced_ben="----------";
			$this->is_cod_prov=$as_fuente;
		}
		if($as_tipo=="-")
		{
			$this->is_ced_ben="----------";
			$this->is_cod_prov="----------";
		}
		$this->uf_int_config(false,false);		 
		$this->is_modo="C";
		$this->ib_procesando_cmp=true;
		$this->is_log_transacciones="CREACION DE COMPROBANTE " .$this->is_salto. "  Comprobante: ".$this->is_procedencia."/"+$this->is_comprobante." / ".$this->id_fecha." ".$this->is_salto." Descripción: ".$this->is_descripcion." " .$this->is_salto;
		if ($this->is_tipo=="P")  							  
		{
			$this->is_log_transacciones .= " Proveedor: ".$as_fuente;
		}	
		else
		{
			if ($this->is_tipo=="B")
			{
				$this->is_log_transacciones .= " Beneficiario: ".$as_fuente;
			}   
			else
			{ 
				if ($this->is_tipo=="-")
				{
					$this->is_log_transacciones .= " Fuente: N/A";
				}   
			}
		}		 	   
		return $lb_valido;	
	}// end function uf_int_init
	//-----------------------------------------------------------------------------------------------------------------------------------
   
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_init_valida_parametros_comprobante($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$as_descripcion,
												   $as_tipo,$as_fuente)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_init_valida_parametros_comprobante
		//		   Access: public 
		//       Argument: as_codemp // Código de empresa
		//       		   as_procedencia // Procedencia del documento
		//       		   as_comprobante // Número de Comprobante
		//       		   as_fecha // Fecha del Comprobante
		//       		   as_descripcion // Descripción del comprobante
		//       		   as_tipo // Tipo de Destino si es proveedor ó  Beneficiario
		//       		   as_fuente // Código de Proveedor ó Benficiario dependiendo del tipo
		//	  Description: Este método valida la información de cabecera del comprobante en cuanto a los campos que las contiene 
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_desproc="";
		$lb_validar=true;
		$ls_bloanu=$_SESSION["la_empresa"]["bloanu"];
		if($ls_bloanu=="0")
		{
			switch ($as_procedencia)
			{
				case "SEPSPA":
					$lb_validar=false;
				break;
				case "SCBBAC":
					$lb_validar=false;
				break;
				case "SOBRAS":
					$lb_validar=false;
				break;
				case "SOBACO":
					$lb_validar=false;
				break;
				case "CXPARD":
					$lb_validar=false;
				break;
			}
		}
		
		$this->is_msg_error="";
		if((is_null($as_comprobante)) or (empty($as_comprobante)))
		{
			$this->io_msg->message("El N° de Comprobante no puede tener valor nulo o vacío.");			
			return false;	
		}
		if((is_null($as_procedencia)) or (empty($as_procedencia)))
		{
			$this->io_msg->message("La procedencia no puede tener valor nulo o vacio .");
			return false;	
		} 	  
		if((is_null($as_descripcion)) or (empty($as_descripcion)))
		{
			$this->io_msg->message("La descripción no puede tener valor nulo o vacío.");
			return false;
		} 	
		if((is_null($as_tipo)) or (empty($as_tipo)))
		{ 
			$this->io_msg->message("El Tipo (Beneficiario o Proveedor) no puede tener valor nulo o vacío.");
			return false;
		} 	
		if((is_null($as_fuente)) or ($as_fuente==""))
		{
			$this->io_msg->message("El Beneficiario o Proveedor no puede tener valor nulo o vacío.");
			return false;	
		}
		$as_cedben="----------";
		$as_codpro="----------";
		if($as_tipo=="B")
		{
			$as_cedben=$as_fuente;
		}
		if($as_tipo=="P")
		{
			$as_codpro=$as_fuente;
		}
		if($this->uf_select_proveedor($as_codemp,$as_codpro)===false)
		{
			$this->io_msg->message("El Proveedor ".$as_codpro." no Existe en la Ficha de Proveedores.");
			return false;	
		}
		if($this->uf_select_beneficiario($as_codemp,$as_cedben)===false)
		{
			$this->io_msg->message("El Beneficiario ".$as_cedben." no Existe en la Ficha de Beneficiarios.");
			return false;	
		}
		$as_comprobante = $this->uf_fill_comprobante($as_comprobante );
		if(!($this->uf_valida_procedencia( $as_procedencia , $ls_desproc)))
		{ 
			$this->io_msg->message("Error en valida procedencia del Comprobante");
			return false;
		}
		if($lb_validar)
		{
			if (!($this->int_fecha->uf_valida_fecha_mes( $as_codemp , $as_fecha )))
			{
				$this->io_msg->message($this->int_fecha->is_msg_error);
				$this->io_msg->message("Error en valida fecha del Comprobante");			 
				return false;
			}
			if(!$this->int_fecha->uf_valida_fecha_periodo($as_fecha,$as_codemp))
			{
				$this->is_msg_error = "Fecha Invalida."	;
				$this->io_msg->message($this->is_msg_error);			   		  		  
				return false;
			}
		}
		return true;
	}// end function uf_init_valida_parametros_comprobante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_init_destroy_datastore()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_init_destroy_datastore
		//		   Access: public 
		//       Argument: 
		//	  Description: Método que Elimina todos los datastored utilizados por las rutinas para evitar problemas de data
		//	      Returns: Datastore Eliminados
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(is_object($this->ds_scg))
		{
			unset($this->ds_scg);
		}
		if(is_object($this->ds_spg))
		{
			unset($this->ds_spg);
		}
		if(is_object($this->ds_spi))
		{
			unset($this->ds_spi);
		}
	} // end function uf_init_destroy_datastore
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_init_create_datastore()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_init_create_datastore
		//		   Access: public 
		//       Argument: 
		//	  Description: Método que Crea todos los datastored utilizados por las rutinas para almacenar la información de los 
		//				   movimiento de Ingresos, Gastos, Contable 
		//	      Returns: Datastore Creados
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->ds_scg=new class_datastore();
		$this->ds_spg=new class_datastore();
		$this->ds_spi=new class_datastore();
	} // end function uf_init_create_datastore
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_insert_datastore($as_codemp,$as_sc_cuenta,$as_operacion,$adec_monto,$as_documento,$as_procede_doc,
									 $as_descripcion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_insert_datastore
		//		   Access: public 
		//       Argument: as_codemp // Código de empresa
		//       		   as_sc_cuenta // cuenta de la contabilidad general o fiscal
		//       		   as_operacion // representa la operación D=Debe o H=Haber
		//       		   adec_monto // monto del movimiento de la cuenta
		//       		   as_documento // N° del documento del movimiento
		//       		   as_procede_doc // N° del documento asociado a la procedencia del sistema
		//       		   as_descripcion // descripcion del movimiento
		//	  Description: Este método inserta el movimiento contable en el datastore $this->ds_scg
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_mensaje_error="";
		$lb_valido=true;
		if(($as_documento==null) or ($as_documento==""))
		{
			$as_documento=$this->is_comprobante;
		} 	
		$as_documento=$this->uf_fill_documento($as_documento);
		if(($as_procede_doc==null)or($as_procede_doc==""))
		{
			$as_procede_doc=$this->is_procedencia;
		} 	
		if(($as_descripcion==null)or($as_descripcion==""))
		{
			$as_descripcion = trim($this->is_descripcion);
		} 	
		$adec_monto=round($adec_monto,2);
		// Valida que exista la cuenta contable SCG
		if(!$this->int_scg->uf_scg_select_cuenta($as_codemp,$as_sc_cuenta,&$ls_status,&$ls_denominacion))
		{
			if($this->int_scg->is_msg_error!="")
			{ 
				$this->is_msg_error=$this->int_scg->is_msg_error;
			}
			else
			{
			   $this->is_msg_error = "La cuenta contable ".trim($as_sc_cuenta)." no exite en el plan de cuenta.";	    
			} 	
			$this->ib_procesando_cmp = false;
			return false; 
		}
		// verifico si la cuenta contable es o no de movimiento
		if($ls_status!="C")
		{ 
			$this->is_msg_error="La cuenta contable ".trim($as_sc_cuenta)." no es de movimiento.";    	 
			$this->ib_procesando_cmp=false;
			return false;
		} 
		// Si existe el movimiento de la cuenta de la misma operacion y documento entonces busco y 
		// sumo el monto en el registro del datastore si no es así entonces inserto un nuevo registro.
		// en el datastore
		if ($as_operacion=="D")
		{
			$this->idec_monto_debe=$this->idec_monto_debe + $adec_monto ;		
		}
		else  
		{
			$this->idec_monto_haber=$this->idec_monto_haber + $adec_monto ;		
		}
		$ll_tot_row=$this->ds_scg->getRowCount("sc_cuenta");
		//$at_valores arreglo temporal para almacenar valor fila del datastore
		$valores["sc_cuenta"]=$as_sc_cuenta;
		$valores["documento"]=$as_documento;
		$valores["debhab"]=$as_operacion;
		$ll_row_found=$this->ds_scg->findValues($valores,"sc_cuenta") ;
		if($ll_row_found>0)
		{  
			$ldec_monto=0;
			$ldec_monto=$this->ds_scg->getValue("monto",$ll_row_found);
			$ldec_monto=$ldec_monto + $adec_monto;
			$this->ds_scg->updateRow("monto",$ldec_monto,$ll_row_found);	
		}
		else
		{
			$this->ds_scg->insertRow("sc_cuenta",$as_sc_cuenta);
			$this->ds_scg->insertRow("debhab",$as_operacion);
			$this->ds_scg->insertRow("documento",$as_documento);
			$this->ds_scg->insertRow("procede_doc",$as_procede_doc);
			$this->ds_scg->insertRow("descripcion",$as_descripcion);
			$this->ds_scg->insertRow("monto",$adec_monto);
		}	
		return $lb_valido;
	} // end function uf_scg_insert_datastore
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_insert_datastore($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,
								     $as_codestpro4,$as_codestpro5,$as_estcla,$as_spg_cuenta,$as_operacion,
								     $adec_monto,$as_documento,$as_procede_doc,$as_descripcion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_insert_datastore
		//		   Access: public 
		//       Argument: as_codemp // Código de empresa
		//       		   as_codestpro1 // Código de estructura Programatica 1
		//       		   as_codestpro2 // Código de estructura Programatica 2
		//       		   as_codestpro3 // Código de estructura Programatica 3
		//       		   as_codestpro4 // Código de estructura Programatica 4
		//       		   as_codestpro5 // Código de estructura Programatica 5
		//				   as_estcla // Estatus de Clasificación
		//       		   as_spg_cuenta // cuenta de la contabilidad Presupuestaria de Gasto
		//       		   as_operacion // representa el mensaje codigo asociado a la operacion a arealizar
		//       		   adec_monto // monto del movimiento de la cuenta
		//       		   as_documento // N° del documento del movimiento
		//       		   as_procede_doc // N° del documento asociado a la procedencia del sistema
		//       		   as_descripcion // descripcion del movimiento
		//	  Description: Este método inserta el movimiento resupuestario de gasto en el datastore $this->ds_spg
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_status="";
		$ls_denominacion="";
		$ls_sc_cuenta="";
		$lb_valido =true;
		if(($as_documento==null)||($as_documento==""))
		{
			$as_documento=$this->is_comprobante;
		}
		$as_documento=$this->uf_fill_documento($as_documento); 	
		if(($as_procede_doc==null)||($as_procede_doc==""))
		{
			$as_procede_doc=$this->is_procedencia;
		} 	
		if(( $as_descripcion==null)||($as_descripcion=="")) 
		{
			$as_descripcion=trim($this->is_descripcion);
		}
		$adec_monto=round($adec_monto,2);
		$aa_estructura[0]=$as_codestpro1;
		$aa_estructura[1]=$as_codestpro2;
		$aa_estructura[2]=$as_codestpro3;		
		$aa_estructura[3]=$as_codestpro4;
		$aa_estructura[4]=$as_codestpro5;
		$aa_estructura[5]=$as_estcla;
		if(!$this->int_spg->uf_spg_select_cuenta($as_codemp,$aa_estructura,$as_spg_cuenta,&$ls_status,&$ls_denominacion,
												 &$ls_sc_cuenta))
		{
			if($this->int_spg->is_msg_error!="")
			{ 
				$this->is_msg_error=$this->int_spg->is_msg_error;
			}
			else
			{
				$this->is_msg_error="La cuenta presupuestaria de gasto ".trim($as_spg_cuenta)." no existe en el plan de cuenta." ;
			}
			$this->ib_procesando_cmp=false;
			return false;
		}
		if($ls_status!="C")
		{
			$this->is_msg_error = "La cuenta presupuestaria de gasto ".trim($as_spg_cuenta)." no es de movimiento.";	    	 
			$this->ib_procesando_cmp = false;
			return false;
		} 
		if($ls_sc_cuenta=="")
		{
			$this->is_msg_error = "La cuenta contable ".trim($ls_sc_cuenta) ." no tiene asociado su respectiva cuenta contable.";	    	
			$this->ib_procesando_cmp = false;
			return false;	
		}
		if(!$this->int_scg->uf_scg_select_cuenta($as_codemp,$ls_sc_cuenta,$ls_status,$ls_denominacion))
		{
			if ($this->int_scg->is_msg_error!="")
			{ 
				$this->is_msg_error = $this->int_scg->is_msg_error;
			}
			else
			{
				$this->is_msg_error = "La cuenta contable ".trim($ls_sc_cuenta)." no exite en el plan de cuenta.";	    
			} 	
			$this->ib_procesando_cmp = false;
			return false; 
		}
		if($this->ib_AutoConta)
		{
			$pos=strpos($as_operacion,'C');
			if($pos===false)
			{
				$pos=0;
			}
			else
			{
				if($pos>=0) 
				{
					if($adec_monto>=0)
					{
						$ls_operacion="D";
					}   
					else
					{
						$ls_operacion="H";
					}
					$adec_monto=abs($adec_monto);
					if ($this->ib_spg_enlace_contable)
					{
						if(!($this->uf_scg_insert_datastore($as_codemp,$ls_sc_cuenta,$ls_operacion,$adec_monto,$as_documento,$as_procede_doc,$as_descripcion))) 
						{
							$this->ib_procesando_cmp = false;
							return false;
						}
					}
				}
			}
		}
		// Si existe el movimiento de la cuenta de la misma operacion y documento entonces busco y 
		// sumo el monto en el registro del datastore si no es así entonces inserto un nuevo registro.
		// en el datastore
		$ldec_monto=0;
		$ll_tot_row=$this->ds_spg->getRowCount("spg_cuenta");
		$valores["codestpro1"]=$as_codestpro1;
		$valores["codestpro2"]=$as_codestpro2;
		$valores["codestpro3"]=$as_codestpro3;		
		$valores["codestpro4"]=$as_codestpro4;
		$valores["codestpro5"]=$as_codestpro5;				
		$valores["estcla"]=$as_estcla;				
		$valores["spg_cuenta"]=$as_spg_cuenta;
		$valores["documento"]=$as_documento;
		$valores["operacion"]=$as_operacion;
		$ll_row_found = $this->ds_spg->findValues($valores,"codestpro1");
		if($ll_row_found>0)
		{
			$ldec_monto=$this->ds_spg->getValue("monto",$ll_row_found);
			$ldec_monto=$ldec_monto + $adec_monto;
			$this->ds_spg->updateRow("monto",$ldec_monto,$ll_row_found);
		}	
		else
		{
			$this->ds_spg->insertRow("codestpro1", $as_codestpro1);
			$this->ds_spg->insertRow("codestpro2", $as_codestpro2);
			$this->ds_spg->insertRow("codestpro3", $as_codestpro3);						
			$this->ds_spg->insertRow("codestpro4", $as_codestpro4);
			$this->ds_spg->insertRow("codestpro5", $as_codestpro5);						
			$this->ds_spg->insertRow("estcla", $as_estcla);						
			$this->ds_spg->insertRow("spg_cuenta",$as_spg_cuenta);
			$this->ds_spg->insertRow("operacion",$as_operacion);
			$this->ds_spg->insertRow("documento",$as_documento);
			$this->ds_spg->insertRow("procede_doc",$as_procede_doc);
			$this->ds_spg->insertRow("descripcion",$as_descripcion);
			$this->ds_spg->insertRow("sc_cuenta",$ls_sc_cuenta);
			$this->ds_spg->insertRow("monto",$adec_monto);	
		}
		return true;
	} // end function uf_spg_insert_datastore
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_insert_datastore($as_codemp,$as_spi_cuenta,$as_operacion,$adec_monto,$as_documento,$as_procede_doc,
									 $as_descripcion,
									 $ls_codestpro1="-------------------------",
									 $ls_codestpro2="-------------------------",
									 $ls_codestpro3="-------------------------",
									 $ls_codestpro4="-------------------------",
									 $ls_codestpro5="-------------------------",
									 $ls_estcla="-")
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_insert_datastore
		//		   Access: public 
		//       Argument: as_codemp // Código de empresa
		//       		   as_spi_cuenta // cuenta de la contabilidad Presupuestaria de ingresos
		//       		   as_operacion // representa el mensaje codigo asociado a la operacion a realizar
		//       		   adec_monto // monto del movimiento de la cuenta
		//       		   as_documento // N° del documento del movimiento
		//       		   as_procede_doc // N° del documento asociado a la procedencia del sistema
		//       		   as_descripcion // descripcion del movimiento
		//	  Description: Este método inserta el movimiento resupuestario de ingresoen el datastore $this->ds_spi
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true; 
		$ls_status="";
		$ls_denominacion="";
		if(($as_documento==null)||($as_documento==""))
		{
			$as_documento=$this->is_comprobante;
		} 	
		$as_documento = $this->uf_fill_documento($as_documento); 		
		if(($as_procede_doc==null)||($as_procede_doc==""))
		{	
			$as_procede_doc=$this->is_procedencia;
		} 	
		if(($as_descripcion==null)||($as_descripcion==""))  
		{
			$as_descripcion=trim($this->is_descripcion);
		} 	
	    $adec_monto=round($adec_monto,2);
        if(!$this->int_spi->uf_spi_select_cuenta($as_codemp,$as_spi_cuenta,$ls_status,$ls_denominacion,$as_sc_cuenta))
	    {
		    if ($this->int_spi->is_msg_error!="")
		    { 
				$this->is_msg_error=$this->int_spi->is_msg_error;
		    }
		    else
		    {
		       $this->is_msg_error="La cuenta presupuestaria de ingreso ".trim($as_spi_cuenta)." no exite en el plan de cuenta."  ;
		    } 	
		    $this->ib_procesando_cmp=false;
		    return false;
  	    }
		if(!$this->int_scg->uf_scg_select_cuenta($as_codemp,$as_sc_cuenta,$ls_status,$ls_denominacion))
		{
			if ($this->int_scg->is_msg_error!="")
			{ 
				$this->is_msg_error=$this->int_scg->is_msg_error;
			}
			else
			{
				$this->is_msg_error="La cuenta contable ".trim($as_sc_cuenta)." no exite en el plan de cuenta.";	    
			} 	
			$this->ib_procesando_cmp=false;
			return false; 
		}
	    // verifico si la cuenta contable es o no de movimiento
	    if($ls_status!="C")
	    { 
			$this->is_msg_error="La cuenta presupuestaria de ingreso ".trim($as_spi_cuenta)." no es de movimiento.";	    	 
			$this->ib_procesando_cmp=false;
			return false;
	    }       
		// Si existe el movimiento de la cuenta de la misma operacion y documento entonces busco y 
		// sumo el monto en el registro del datastore si no es así entonces inserto un nuevo registro.
		// en el datastore
		$ldec_monto=0 ;
	    $ll_tot_row=$this->ds_spi->getRowCount("spi_cuenta");
		$valores["spi_cuenta"]=$as_spi_cuenta;
		$valores["codestpro1"]=$ls_codestpro1;
		$valores["codestpro2"]=$ls_codestpro2;
		$valores["codestpro3"]=$ls_codestpro3;
		$valores["codestpro4"]=$ls_codestpro4;
		$valores["codestpro5"]=$ls_codestpro5;
		$valores["estcla"]=$ls_estcla;
	    $valores["documento"]=$as_documento;
	    $valores["operacion"]=$as_operacion;
		$ll_row_found = $this->ds_spi->findValues($valores,"operacion");
		if($ll_row_found>=0)
		{ 
			$ldec_monto=$this->ds_spi->getValue("monto",$ll_row_found);
			$ldec_monto=$ldec_monto+$adec_monto;
			$this->ds_spi->updateRow("monto",$ldec_monto,$ll_row_found);
		}	
		else
		{
			$ll_tot_row=$ll_tot_row + 1;
			$this->ds_spi->insertRow("spi_cuenta",$as_spi_cuenta);
			$this->ds_spi->insertRow("operacion",$as_operacion);
			$this->ds_spi->insertRow("documento",$as_documento);
			$this->ds_spi->insertRow("procede_doc",$as_procede_doc);
			$this->ds_spi->insertRow("descripcion",$as_descripcion);
			$this->ds_spi->insertRow("sc_cuenta",$as_sc_cuenta);
			$this->ds_spi->insertRow("monto",$adec_monto);
			$this->ds_spi->insertRow("codestpro1",$ls_codestpro1);
			$this->ds_spi->insertRow("codestpro2",$ls_codestpro2);
			$this->ds_spi->insertRow("codestpro3",$ls_codestpro3);
			$this->ds_spi->insertRow("codestpro4",$ls_codestpro4);
			$this->ds_spi->insertRow("codestpro5",$ls_codestpro5);
			$this->ds_spi->insertRow("estcla",$ls_estcla);
		}	
		return $lb_valido;
	}// end function uf_spi_insert_datastore
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_init_delete($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$as_tipo,$as_ced_bene,$as_cod_pro,
							$ab_check_close,$as_codban,$as_ctaban,$as_tipcomp="1")
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_init_delete
		//		   Access: public 
		//       Argument: as_codemp // Código de empresa
		//       		   as_procedencia // Procedencia del Documento
		//       		   as_comprobante // Número del Comprobante
		//       		   as_fecha // Fecha del comprobante
		//       		   as_tipo // Tipo de Destino si es proveedor ó Beneficiario
		//       		   as_ced_bene // Cédula del Beneficiario
		//       		   as_cod_pro // Código del Proveedor
		//       		   ab_check_close // Si se validan los parámetros del comprobante
		//       		   as_codban // Código del Banco
		//       		   as_ctaban // Cuenta del Banco
		//	  Description: Este método inicia la transacción de un cursor para eliminar los movimientos contable del comprobante. 
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true; 
		$ls_descripcion=""; 
		$ls_fuente=""; 
		$ls_descripcion="--Borrando--";
		$this->is_codemp=$as_codemp;
		$this->is_procedencia=$as_procedencia;
		$this->is_comprobante=$as_comprobante;
		$this->id_fecha=$as_fecha;
		$_SESSION["fechacomprobante"]=$as_fecha;
		$this->is_tipo=$as_tipo;
		$this->ii_tipo_comp=$as_tipcomp;
		$this->is_cod_prov=$as_cod_pro;
		$this->is_ced_ben=$as_ced_bene;
		$this->as_codban=$as_codban;
		$this->as_ctaban=$as_ctaban;
		if($this->is_tipo=="B")
		{
			$ls_fuente = $this->is_ced_ben;
		}	
		else
		{ 
			if($this->is_tipo=="P")
			{
				$ls_fuente = $this->is_cod_prov;
			}	
			else
			{
				$ls_fuente = "----------";
			} 
		}
		if($this->ib_procesando_cmp)
		{
			$this->is_msg_error="Ya se esta procesando un comprobante contable.";
			return false;
		} 
		if(!($ab_check_close))
		{
			if(!($this->uf_init_valida_parametros_comprobante($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,
															  $ls_descripcion,$this->is_tipo,$ls_fuente)))
			{
				$this->io_msg->message("Datos Invalidos ");
				return false;
			} 	
		} 	
		$ls_fecha=$this->io_function->uf_convertirfecmostrar($this->id_fecha);
		if(!($this->uf_select_comprobante($this->is_codemp,$this->is_procedencia,$this->is_comprobante,$ls_fecha,$as_codban,
										  $as_ctaban)))  
		{
			$this->io_msg->message("El comprobante no existe.");
			return false;
		}	
		$this->uf_init_destroy_datastore();
		$this->uf_init_create_datastore();	
		$this->is_modo="D"; 
		$this->ib_procesando_cmp=true; 
		$this->uf_int_config(true,true); 
		$lb_valido=$this->uf_init_load_datastore_integracion();  
		if(!($lb_valido))
		{
			$this->io_msg->message("ERROR-> En método uf_init_load_datastore_integracion ");
			return false;
		}
		$this->is_log_transacciones = "ELIMINACION DE COMPROBANTE ".$this->is_salto."Comprobante: ".$this->is_procedencia." / ".$this->is_comprobante." / ".$this->id_fecha.$this->is_salto;
		return $lb_valido;
	}// end function uf_init_delete
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_init_load_datastore_integracion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_init_load_datastore_integracion
		//		   Access: public 
		//       Argument: 
		//	  Description: Metodo que accede a las tablas detalles spg,spi y scg para vaciar la información en los datastores
		//                 respectivos
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->ib_db_error=false;
		$this->is_msg_error="";
		// Datastore Contable
		$ls_sql="SELECT sc_cuenta, procede_doc, documento, debhab, descripcion, monto, orden ".
				"  FROM scg_dt_cmp ".
				" WHERE codemp='".$this->is_codemp."' ".
				"   AND procede='".$this->is_procedencia."' ".
				"   AND comprobante='".$this->is_comprobante."' ".
				"   AND fecha='".$this->id_fecha."'".
				"   AND codban='".$this->as_codban."'".
				"   AND ctaban='".$this->as_ctaban."'".
				" ORDER BY orden";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_int MÉTODO->uf_init_load_datastore_integracion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_scg->insertRow("sc_cuenta",$row["sc_cuenta"]);
				$this->ds_scg->insertRow("procede_doc",$row["procede_doc"]);
				$this->ds_scg->insertRow("documento",$row["documento"]);
				$this->ds_scg->insertRow("debhab",$row["debhab"]);
				$this->ds_scg->insertRow("descripcion",$row["descripcion"]);
				$this->ds_scg->insertRow("monto",$row["monto"]);
				$this->ds_scg->insertRow("orden",$row["orden"]);
			}
			$this->io_sql->free_result($rs_data);				
		}
		// Datastore Gasto
		$ls_sql="SELECT dt.codestpro1 as codestpro1,dt.codestpro2 as codestpro2,dt.codestpro3 as codestpro3, ".
		        "       dt.codestpro4 as codestpro4,dt.codestpro5 as codestpro5,dt.spg_cuenta as spg_cuenta, ".
				"       dt.procede_doc as procede_doc,dt.documento as documento,dt.operacion as operacion, ".
				"       dt.descripcion as descripcion,dt.monto as monto,dt.orden as orden,c.sc_cuenta as sc_cuenta, ".
				"       dt.estcla as estcla ".
				"  FROM spg_dt_cmp dt, spg_cuentas c ".
				" WHERE dt.codemp='".$this->is_codemp."' ".
				"	AND dt.procede='".$this->is_procedencia."' ".
				"	AND dt.comprobante='".$this->is_comprobante."' ".
				"   AND dt.fecha='".$this->id_fecha."'". 
				"   AND codban='".$this->as_codban."'".
				"   AND ctaban='".$this->as_ctaban."'".
				"	AND dt.codemp=c.codemp ".
				"	AND dt.estcla=c.estcla ".
				"	AND dt.codestpro1=c.codestpro1 ".
				"	AND dt.codestpro2=c.codestpro2 ".
				"	AND dt.codestpro3=c.codestpro3 ".
				"   AND dt.codestpro4=c.codestpro4 ".
				"	AND dt.codestpro5=c.codestpro5 ".
				"	AND dt.spg_cuenta=c.spg_cuenta ".
				"  ORDER BY dt.orden";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_int MÉTODO->uf_init_load_datastore_integracion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_spg->insertRow("codestpro1",$row["codestpro1"]);
				$this->ds_spg->insertRow("codestpro2",$row["codestpro2"]);
				$this->ds_spg->insertRow("codestpro3",$row["codestpro3"]);
				$this->ds_spg->insertRow("codestpro4",$row["codestpro4"]);
				$this->ds_spg->insertRow("codestpro5",$row["codestpro5"]);
				$this->ds_spg->insertRow("estcla",$row["estcla"]);
				$this->ds_spg->insertRow("spg_cuenta",$row["spg_cuenta"]);
				$ls_operacion = $this->int_spg->uf_operacion_codigo_mensaje($row["operacion"]);
				$this->ds_spg->insertRow("operacion",$ls_operacion);
				$this->ds_spg->insertRow("documento",$row["documento"]);
				$this->ds_spg->insertRow("procede_doc",$row["procede_doc"]);
				$this->ds_spg->insertRow("descripcion",$row["descripcion"]);
				$this->ds_spg->insertRow("sc_cuenta",$row["sc_cuenta"]);
				$this->ds_spg->insertRow("monto",$row["monto"]);	
			}
			$this->io_sql->free_result($rs_data);
		}
		// Datastore Ingresos
		$ls_sql="SELECT dt.spi_cuenta as spi_cuenta,dt.procede_doc as procede_doc,dt.documento as documento, ".
		        "       dt.operacion as operacion,".
				"       dt.descripcion as descripcion,dt.monto as monto,dt.orden as orden,c.sc_cuenta as sc_cuenta, ".
				"       dt.estcla as estcla, dt.codestpro1 as codestpro1, dt.codestpro2 as codestpro2, ".
				"       dt.codestpro3 as codestpro3, dt.codestpro4 as codestpro4, dt.codestpro5 as codestpro5 ".
				"  FROM spi_dt_cmp dt,spi_cuentas c ".
				"  WHERE dt.codemp='".$this->is_codemp."' ".
				"    AND dt.procede='".$this->is_procedencia."' ".
				"    AND dt.comprobante='".$this->is_comprobante."' ".
				"	 AND dt.fecha='".$this->id_fecha."'".
				"    AND codban='".$this->as_codban."'".
				"    AND ctaban='".$this->as_ctaban."'".
				"	 AND dt.codemp=c.codemp AND dt.spi_cuenta=c.spi_cuenta ".
				" ORDER BY orden";
		$rs_data = $this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$this->is_msg_error="CLASE->sigesp_int_int MÉTODO->uf_init_load_datastore_integracion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_spi->insertRow("spi_cuenta",$row["spi_cuenta"]);
				$this->ds_spi->insertRow("procede_doc",$row["procede_doc"]);
				$this->ds_spi->insertRow("documento",$row["documento"]);
				$this->ds_spi->insertRow("operacion",$row["operacion"]);
				$this->ds_spi->insertRow("descripcion",$row["descripcion"]);
				$this->ds_spi->insertRow("monto",$row["monto"]);
				$this->ds_spi->insertRow("sc_cuenta",$row["sc_cuenta"]);
				$this->ds_spi->insertRow("codestpro1",$row["codestpro1"]);
			    $this->ds_spi->insertRow("codestpro2",$row["codestpro2"]);
			    $this->ds_spi->insertRow("codestpro3",$row["codestpro3"]);
			    $this->ds_spi->insertRow("codestpro4",$row["codestpro4"]);
			    $this->ds_spi->insertRow("codestpro5",$row["codestpro5"]);
			    $this->ds_spi->insertRow("estcla",$row["estcla"]);
			}
			$this->io_sql->free_result($rs_data);
		} 
		return $lb_valido;
	}// end function uf_init_load_datastore_integracion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_valida_comprobante_cuadre_scg()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_valida_comprobante_cuadre_scg
		//		   Access: public 
		//       Argument: 
		//	  Description: Este método recorre cada registro del datastore instanciado dse scg, y suma los valores o monto del
		//                 debe y el haber verificando que los sean iguales para validar el cuadre del mismo.
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true; 
		$ll_row=0;
		$ll_tot_row=0;
		$ll_row_spg=0;
		$ll_row_spi=0;
		$ldec_monto_debe=0;
		$ldec_monto_haber=0;
		$ls_debhab="";
		$ldec_monto_debe=0;
		$ldec_monto_haber = 0;
		if($this->ds_scg->data!="")
		{
			$ll_tot_row=$this->ds_scg->getRowCount("sc_cuenta");
			$ll_row_spg=$this->ds_spg->getRowCount("spg_cuenta");
			$ll_row_spi=$this->ds_spi->getRowCount("spi_cuenta");
			if($ll_tot_row > 0)
			{
				for($ll_row=1;$ll_row<=$ll_tot_row;$ll_row++)
				{
					$ls_debhab=$this->ds_scg->getValue("debhab",$ll_row);
					$ls_debhab=strtoupper($ls_debhab);
					if ($ls_debhab=="D")
					{
						$ldec_monto_debe = $ldec_monto_debe + $this->ds_scg->getValue("monto",$ll_row) ;
					}   
					else	 
					{
						$ldec_monto_haber = $ldec_monto_haber + $this->ds_scg->getValue("monto",$ll_row);
					}
				}
				$ldec_monto_debe=round($ldec_monto_debe,2);
				$ldec_monto_haber=round($ldec_monto_haber,2);
				if(doubleval(trim($ldec_monto_debe))!=doubleval(trim($ldec_monto_haber)) ) // Valida el cuadre de contabilidad en temporal
				{
					$lb_valido=false;			
					$this->is_msg_error="No Cuadra el Comprobante Contable Debe=[".$ldec_monto_debe."] , Haber[".$ldec_monto_haber."]";
				}
			}		
			else
			{
				if(($ll_row_spg==0)&&($ll_row_spi==0)) 
				{
					$lb_valido=false;
					$this->is_msg_error="No existen movimiento asociado al comprobante.";	
				} 	
			}	
		}
		return $lb_valido;
	}// end function uf_valida_comprobante_cuadre_scg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_init_end_transaccion_integracion($aa_seguridad)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_init_end_transaccion_integracion
		//		   Access: public 
		//       Argument: aa_seguridad // Arreglo de las variables de seguridad
		//	  Description: Este método culmina la transacción de un nuevo comprobante SPI,SPG,SCG.
		//                 a) Inserta mediante los datastores en spi,scg y spg en 
		//                     la base de datos utilizando los metodos de integracion
		//                 b) Si la modalidad es delete borra la informacion de la cabecera y detalle
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true; 
		$lb_Flag_Error_SCG=false;
		$lb_Flag_Error_SPG=false;
		$lb_Flag_Error_SPI=false;
		$this->id_fecha = $this->io_function->uf_convertirdatetobd($this->id_fecha);
		$lb_valido=$this->uf_int_valida_informacion_datastore();
		if(!$lb_valido)
		{
			return false;
		}
		$lb_valido=$this->uf_valida_comprobante_cuadre_scg();
		if(!$lb_valido)
		{
			return false;
		}
		$lb_valido = $this->uf_sigesp_comprobante($this->is_codemp,$this->is_procedencia,$this->is_comprobante,
												  $this->id_fecha,$this->ii_tipo_comp,$this->is_descripcion,
												  $this->is_tipo,$this->is_cod_prov,$this->is_ced_ben,0,
												  $this->as_codban,$this->as_ctaban);
		if($lb_valido)
		{
			if($aa_seguridad["sistema"]!="")
			{
				if($this->ib_new_comprobante)
				{
					$ls_evento="INSERT";
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_descripcion="Inserto el Comprobante <b>".$this->is_comprobante."</b>, Procedencia <b>".$this->is_procedencia."</b>,".
									" Fecha <b>".$this->id_fecha."</b>, Proveedor <b>".$this->is_cod_prov."</b>, Beneficiario <b>".$this->is_ced_ben."</b>,".
									" Banco <b>".$this->as_codban."</b>, Cuenta <b>".$this->as_ctaban."</b>, Descripción <b>".$this->is_descripcion."</b>";
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
			}
		}		
		if($lb_valido)
		{    
			$lb_valido=$this->uf_int_make_spg($aa_seguridad);
			$lb_Flag_Error_SPG=(!($lb_valido));
		}
		if($lb_valido) 
		{
			$lb_valido=$this->uf_int_make_spi($aa_seguridad);
			$lb_Flag_Error_SPI=(!($lb_valido));
		}
		if($lb_valido) 
		{
			$lb_valido=$this->uf_int_make_scg($aa_seguridad);
			$lb_Flag_Error_SCG = (!($lb_valido));
		}
		if ($lb_valido) // Borrar la cabecera del comprobante si es el caso.
		{
			if ($this->is_modo=="D") // modalidad borrar
			{
				$lb_valido = $this->uf_sigesp_delete_comprobante(); 
				if($lb_valido)
				{
					if($aa_seguridad["sistema"]!="")
					{
						if(!$this->ib_new_comprobante)
						{
							$ls_evento="DELETE";
							/////////////////////////////////         SEGURIDAD               /////////////////////////////		
							$ls_descripcion="Elimino el Comprobante <b>".$this->is_comprobante."</b>, Procedencia <b>".$this->is_procedencia."</b>,".
											" Fecha <b>".$this->id_fecha."</b>, Proveedor <b>".$this->is_cod_prov."</b>, Beneficiario <b>".$this->is_ced_ben."</b>,".
											" Descripción <b>".$this->is_descripcion."</b>";
							$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
															$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
															$aa_seguridad["ventanas"],$ls_descripcion);
							/////////////////////////////////         SEGURIDAD               /////////////////////////////
						}
					}
				}		
			}
		}
		if($lb_valido)
		{
			if ($lb_Flag_Error_SCG) 
			{
				$this->is_msg_error = $invo_sigesp_int_scg->is_msg_error;
			}
			if ($lb_Flag_Error_SPG) 
			{   
				$this->is_msg_error = $invo_sigesp_int_spg->is_msg_error;
			}
			if ($lb_Flag_Error_SPI)
			{
				$this->is_msg_error = $invo_sigesp_int_spi->is_msg_error;
			}	
		}
		$this->ib_procesando_cmp=false;
	    return $lb_valido;
	}// end function uf_init_end_transaccion_integracion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_int_valida_informacion_datastore()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_int_valida_informacion_datastore
		//		   Access: public 
		//       Argument: 
		//	  Description: Este método realiza las siguientes validaciones:
		//                 a) Validar el cuadre de la contabilidad en temporal en datastores   
		//                 b) Validar el Monto_Total contra la suma de Movimientos
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($this->ds_spg->getRowCount("spg_cuenta")<=0)
		{
			if($this->ds_scg->getRowCount("sc_cuenta") <= 0)
			{
				if($this->ds_spi->getRowCount("spi_cuenta")<= 0)
				{
					if($this->is_modo!="D") 
					{
						$this->is_msg_error="No se registraron movimientos para el comprobante.";
						$this->ib_procesando_cmp = false;
						return false;
					} 	
				}	
			} 	
		} 
		// Valida el cuadre de contabilidad en temporal
		
		if (round((trim($this->idec_monto_debe)),2)!=round((trim($this->idec_monto_haber)),2)) // Valida el cuadre de contabilidad en temporal
		{
			$this->is_msg_error="No Cuadra el Comprobante Contable Debe=[".$this->idec_monto_debe."] , Haber[".$this->idec_monto_haber."]";
		   	return false;
		}
		return true;
	}// end function uf_int_valida_informacion_datastore
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_int_make_scg($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_int_make_scg
		//		   Access: public 
		//       Argument: aa_seguridad // Arreglo de la variable de seguridad
		//	  Description: Este método recorre todos los registros del datastore de contabilidad y lo inserta uno a uno en la
		//                 tabla de movimiento contable y tambien procede actualizar las tablas de saldos y cuentas.
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($this->ds_scg->data!="")
		{
			$ll_tot_row=$this->ds_scg->getRowCount("sc_cuenta");
			$li_total_spg=$this->ds_spg->getRowCount("spg_cuenta");
			$li_total_spi=$this->ds_spi->getRowCount("spi_cuenta");
			if($ll_tot_row>0)
			{
				$lb_valido=$this->uf_verificar_cierre_scg($ls_estciescg);
				if($ls_estciescg=="1")
				{
					$this->is_msg_error="ESTA PROCESADO EL CIERRE CONTABLE";
					return false;
				}
			}
			for($ll_row=1;($ll_row<=$ll_tot_row)&&($lb_valido);$ll_row++)
			{
				$ls_sc_cuenta=$this->ds_scg->getValue("sc_cuenta",$ll_row);
				$ls_procede_doc=$this->ds_scg->getValue("procede_doc",$ll_row);
				$ls_documento=$this->ds_scg->getValue("documento",$ll_row);
				$ls_debhab=$this->ds_scg->getValue("debhab",$ll_row);
				$ls_descripcion=$this->ds_scg->getValue("descripcion",$ll_row);
				$ldec_monto=$this->ds_scg->getValue("monto",$ll_row);
				if (($this->is_modo=="C")||( $this->is_modo=="A")||($this->is_modo=="G"))
				{
					$lb_valido = $this->int_scg->uf_scg_procesar_insert_movimiento($this->is_codemp,$this->is_procedencia,
																			  	   $this->is_comprobante,$this->id_fecha,
											  									   $this->is_tipo,$this->is_cod_prov,
																				   $this->is_ced_ben,$ls_sc_cuenta,$ls_procede_doc,
											  									   $ls_documento,$ls_debhab,$ls_descripcion,
											  									   0,$ldec_monto,$this->as_codban,$this->as_ctaban);
					$ls_evento="INSERT";
					$ls_descripcion="Inserto ";
					if (!($lb_valido))
					{
						$this->is_msg_error = $this->int_scg->is_msg_error ;
					}   
				} 
				else 
				{
					if (($this->is_modo=="D")||($this->is_modo=="S"))
					{
						$lb_valido = $this->int_scg->uf_scg_procesar_delete_movimiento($this->is_codemp,$this->is_procedencia,
																					   $this->is_comprobante,$this->id_fecha,
																					   $ls_sc_cuenta,$ls_procede_doc,$ls_documento,
																					   $ls_debhab,$ldec_monto,$this->as_codban,
																					   $this->as_ctaban,$this->is_msg_error);
						if (!($lb_valido))
						{
							$this->is_msg_error = $this->int_scg->is_msg_error ;
						}   
						$ls_evento="DELETE";
						$ls_descripcion="Elimino ";
					}
				}
				if(($li_total_spg<=0)&&($li_total_spi<=0)&&($ls_debhab=="D"))
				{
					$lb_valido = $this->int_scg->uf_scg_comprobante_update($ldec_monto);
				}
				if($lb_valido)
				{
					if($aa_seguridad["sistema"]!="")
					{
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_descripcion=$ls_descripcion." el Detalle Contable <b>".$this->is_comprobante."</b>, Procedencia <b>".$this->is_procedencia."</b>,".
										" Fecha <b>".$this->id_fecha."</b>, Cuenta <b>".$ls_sc_cuenta."</b>, Operación <b>".$ls_debhab."</b>, Monto <b>".$ldec_monto."</b>";
						$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
					}
				} 		
				 		
			}
		}
		return $lb_valido;
	}// end function uf_int_make_scg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_int_make_spg($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_int_make_spg
		//		   Access: public 
		//       Argument: aa_seguridad // Arreglo de la variable de seguridad
		//	  Description: Este método recorre todos los registros del datastore de presupuesto de gasto y lo inserta uno a 
		//                 uno en la tabla de movimiento presupuestario de gasto y tambien procede actualizar las tablas de 
		//				   saldos y cuentas.
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if(!empty($this->ds_spg->data))
		{
			$ll_tot_row = $this->ds_spg->getRowCount("codestpro1");
			if($ll_tot_row>0)
			{
				$lb_valido=$this->uf_verificar_cierre_spg($ls_estciespg);
				if($ls_estciespg=="1")
				{
					$this->io_msg->message("Ya fue procesado el Cierre Presupuestario, no puede efectuarse movimientos, contacte al Administrador del Sistema!!!");
					return false;
				}
			}
			for($ll_row=1;($ll_row<=$ll_tot_row)&&($lb_valido);$ll_row++)
			{
				$ls_estcla=$this->ds_spg->getValue("estcla",$ll_row);
				$ls_est1=$this->ds_spg->getValue("codestpro1",$ll_row);
				$ls_est2=$this->ds_spg->getValue("codestpro2",$ll_row);
				$ls_est3=$this->ds_spg->getValue("codestpro3",$ll_row);
				$ls_est4=$this->ds_spg->getValue("codestpro4",$ll_row);
				$ls_est5=$this->ds_spg->getValue("codestpro5",$ll_row);
				$ls_cuenta=$this->ds_spg->getValue("spg_cuenta",$ll_row);	 
				$ls_sc_cuenta=$this->ds_spg->getValue("sc_cuenta",$ll_row);	 
				$ls_procede_doc=$this->ds_spg->getValue("procede_doc",$ll_row);	 
				$ls_descripcion=$this->ds_spg->getValue("descripcion",$ll_row); 
				$ls_documento=$this->ds_spg->getValue("documento",$ll_row);
				$ls_mensaje=$this->ds_spg->getValue("operacion",$ll_row);
				$ldec_monto=$this->ds_spg->getValue("monto",$ll_row);
				$estpro[0]=$ls_est1;
				$estpro[1]=$ls_est2;
				$estpro[2]=$ls_est3;
				$estpro[3]=$ls_est4;
				$estpro[4]=$ls_est5;
				$estpro[5]=$ls_estcla;
				if(($this->is_modo=="C")||($this->is_modo=="A")||($this->is_modo=="G"))
				{ 
					if($this->ii_tipo_comp==1) // si es un comprobante normal
					{
						$lb_valido = $this->int_spg->uf_int_spg_insert_movimiento($this->is_codemp,$this->is_procedencia,
																				  $this->is_comprobante,$this->id_fecha,
																				  $this->is_tipo,$this->is_fuente,
																				  $this->is_cod_prov,$this->is_ced_ben,
																				  $estpro,$ls_cuenta,$ls_procede_doc,
																				  $ls_documento,$ls_descripcion,$ls_mensaje,
																				  $ldec_monto,$ls_sc_cuenta,
																				  $this->ib_spg_enlace_contable,
																				  $this->as_codban,$this->as_ctaban);
					}
					else // Si es una modificación presupuestaria
					{
						$lb_valido = $this->int_spg->uf_int_spg_insert_movimiento_modpre($this->is_codemp,$this->is_procedencia,
																						 $this->is_comprobante,$this->id_fecha,
																						 $this->is_tipo,$this->is_fuente,
																						 $this->is_cod_prov,$this->is_ced_ben,
																						 $estpro,$ls_cuenta,$ls_procede_doc,
																						 $ls_documento,$ls_descripcion,$ls_mensaje,
																						 $ldec_monto,$ls_sc_cuenta,
																						 $this->ib_spg_enlace_contable,
																						 $this->as_codban,$this->as_ctaban);
					}
					$ls_evento="INSERT";
					$ls_descripcion="Inserto ";
				}  
				else
				{
					if (($this->is_modo=="D")||($this->is_modo=="S")) 
					{
						$lb_valido = $this->int_spg->uf_int_spg_delete_movimiento($this->is_codemp,$this->is_procedencia,
																				  $this->is_comprobante,$this->id_fecha,
																				  $this->is_tipo,$this->is_fuente,
																				  $this->is_cod_prov,$this->is_ced_ben,
																				  $estpro,$ls_cuenta,$ls_procede_doc,
																				  $ls_documento,$ls_descripcion,$ls_mensaje,
																				  'C',0,$ldec_monto,$ls_sc_cuenta,
																				  $this->as_codban,$this->as_ctaban,
																				  $this->ii_tipo_comp);
						$ls_evento="DELETE";
						$ls_descripcion="Elimino ";
					}   
				}
				if($lb_valido)
				{
					if($aa_seguridad["sistema"]!="")
					{
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_descripcion=$ls_descripcion." el Detalle Presupuestario de Gasto <b>".$this->is_comprobante."</b>, Procedencia <b>".$this->is_procedencia."</b>,".
										" Fecha <b>".$this->id_fecha."</b>, Presupuesto <b>".$ls_est1.$ls_est2.$ls_est3.$ls_est4.$ls_est5."</b>,".
										" Cuenta <b>".$ls_cuenta."</b>, Monto <b>".$ldec_monto."</b>";
						$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
					}
				} 		
				if(!$lb_valido)
				{
					$this->is_msg_error = $this->int_spg->is_msg_error;
				}
			} // end for			  
		} // end if
		return $lb_valido;
	}// end function uf_int_make_spg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_int_make_spi($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_int_make_spi
		//		   Access: public 
		//       Argument: aa_seguridad // Arreglo de la variable de seguridad
		//	  Description: Este método recorre todos los registros del datastore de presupuesto de ingreso y lo inserta uno a 
		//                 uno en la tabla de movimiento presupuestario de ingreso y tambien procede actualizar las tablas de 
		//				   saldos y cuentas.
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if(!empty($this->ds_spi->data))
		{
			$ll_tot_row = $this->ds_spi->getRowCount("spi_cuenta");
			if($ll_tot_row>0)
			{
				$lb_valido=$this->uf_verificar_cierre_spg($ls_estciespg);
				if($ls_estciespg=="1")
				{
				   $this->io_msg->message("Ya fue procesado el Cierre Presupuestario, no puede efectuarse movimientos, contacte al Administrador del Sistema!!!");
					return false;
				}
			}
			for($ll_row=1;($ll_row<=$ll_tot_row)&&($lb_valido);$ll_row++)
			{
				$ls_spi_cuenta=$this->ds_spi->getValue("spi_cuenta",$ll_row);
				$ls_procede_doc=$this->ds_spi->getValue("procede_doc",$ll_row);
				$ls_documento=$this->ds_spi->getValue("documento",$ll_row);
				$ls_mensaje=$this->int_spi->uf_operacion_codigo_mensaje($this->ds_spi->getValue("operacion",$ll_row));
				$ls_sc_cuenta=$this->ds_spi->getValue("sc_cuenta",$ll_row);	 
				$ls_descripcion=$this->ds_spi->getValue("descripcion",$ll_row);
				$ldec_monto=$this->ds_spi->getValue("monto",$ll_row);
				//-----------información de las estructuras de gastos relacionadas con las cuentas de ingresos---------------
				$ls_codestpro1=$this->ds_spi->getValue("codestpro1",$ll_row);
				$ls_codestpro2=$this->ds_spi->getValue("codestpro2",$ll_row);
				$ls_codestpro3=$this->ds_spi->getValue("codestpro3",$ll_row);
				$ls_codestpro4=$this->ds_spi->getValue("codestpro4",$ll_row);
				$ls_codestpro5=$this->ds_spi->getValue("codestpro5",$ll_row);
				$ls_estcla=$this->ds_spi->getValue("estcla",$ll_row);
				//-----------------------------------------------------------------------------------------------------------
				if(($this->is_modo=="C")||($this->is_modo=="A")||($this->is_modo=="G"))
				{
					$lb_valido = $this->int_spi->uf_int_spi_insert_movimiento($this->is_codemp,$this->is_procedencia,
																			  $this->is_comprobante,$this->id_fecha,$this->is_tipo,
																			  $this->is_fuente,$this->is_cod_prov,$this->is_ced_ben,
																			  $ls_spi_cuenta,$ls_procede_doc,$ls_documento,
																			  $ls_descripcion,$ls_mensaje,$ldec_monto,$ls_sc_cuenta,
																			  $this->ib_spg_enlace_contable,$this->as_codban,
																			  $this->as_ctaban,
																			  $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																			  $ls_codestpro4,$ls_codestpro5,$ls_estcla);
					$ls_evento="INSERT";
					$ls_descripcion="Inserto ";
				}
				else
				{
					if(($this->is_modo=="D")||($this->is_modo=="S"))
					{
						$lb_valido = $this->int_spi->uf_int_spi_delete_movimiento($this->is_codemp,$this->is_procedencia,
																				  $this->is_comprobante,$this->id_fecha,
																				  $this->is_tipo,$this->is_fuente,
																				  $this->is_cod_prov,$this->is_ced_ben,
																				  $ls_spi_cuenta,$ls_procede_doc,$ls_documento,
																				  $ls_descripcion,$ls_mensaje,'C',0,$ldec_monto,
																				  $ls_sc_cuenta,$this->as_codban,$this->as_ctaban,
																				  $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																			  	  $ls_codestpro4,$ls_codestpro5,$ls_estcla);
						$ls_evento="DELETE";
						$ls_descripcion="Elimino ";
					}
				}
				if($lb_valido)
				{
					if($aa_seguridad["sistema"]!="")
					{
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_descripcion=$ls_descripcion." el Detalle Presupuestario de Ingreso <b>".$this->is_comprobante."</b>, Procedencia <b>".$this->is_procedencia."</b>,".
										" Fecha <b>".$this->id_fecha."</b>, Cuenta <b>".$ls_spi_cuenta."</b>, Monto <b>".$ldec_monto."</b>";
						$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
					}
				} 		
				if(!$lb_valido)
				{
					$this->is_msg_error = $this->int_spi->is_msg_error;
				}
			}
		}
		return $lb_valido;
	}// end function uf_int_make_spi
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_int_anular($as_codemp,$as_procedencia,$as_comprobante,$adt_fecha,$as_procede_anulacion,$adt_fecha_anula,
							$as_descripcion,$as_codban,$as_ctaban,$ai_tipo_comp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_int_anular
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//       		   as_procedencia // Procede del Documento Original
		//       		   as_comprobante // Número del Comprobante Original
		//       		   adt_fecha // Fecha del Comprobante Original
		//       		   as_procede_anulacion // Procede del Documento a Anular
		//       		   adt_fecha_anula // Fecha del Documento a Anular
		//       		   as_descripcion // Descripción del Documento
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta del Banco
		//       		   ai_tipo_comp // Tipo de comprobante 1-> si es un comprobante Normal 2-> Si es una modificación Presupuestaria
		//	  Description: Este método prepara los parámetros en lote para la anulación de un comprobante.
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true; 
		$this->is_msg_error="";		
		$ls_tipo_destino="";
		$ls_ced_bene="";
		$ls_cod_pro="";
		$adt_fecha_anula=$this->io_function->uf_convertirdatetobd($adt_fecha_anula);
		$adt_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
		if(!($this->uf_init_valida_parametros_anulacion($as_codemp,$as_procedencia,$as_comprobante,$adt_fecha,
														$as_procede_anulacion,$as_descripcion)))
		{
			return false;
		}	
		if(!$this->int_fecha->uf_comparar_fecha($adt_fecha,$adt_fecha_anula))
		{
	   		$this->io_msg->message("ERROR-> La fecha de anulación ".$adt_fecha_anula." no puede ses menor que la fecha ".$adt_fecha." del comprobante previo ");
			return false;
		}
		$this->uf_obtener_comprobante($as_codemp,$as_procedencia,$as_comprobante,$adt_fecha,$as_codban,$as_ctaban,
									  &$ls_tipo_destino,&$ls_ced_bene,&$ls_cod_pro);
		$this->is_codemp=$as_codemp;
		$this->is_procedencia=$as_procede_anulacion;
		$this->is_comprobante=$as_comprobante;
		$this->id_fecha=$adt_fecha_anula;
		$_SESSION["fechacomprobante"]=$adt_fecha_anula;
		$this->as_codban=$as_codban;
		$this->as_ctaban=$as_ctaban;		
		$this->is_descripcion=$as_descripcion;
		$this->is_tipo=$ls_tipo_destino;
		$this->is_ced_ben=$ls_ced_bene;
		$this->is_cod_prov=$ls_cod_pro; 
		$this->idec_monto=0;
		$this->idec_monto_debe=0;		
		$this->idec_monto_haber=0;
		$this->ii_tipo_comp=$ai_tipo_comp;
		$this->ib_spg_enlace_contable=true;
		$this->is_modo="A";
		$this->ib_procesando_cmp= true;
		$this->uf_int_config(false,false);		 
		$this->uf_init_destroy_datastore();
		$this->uf_init_create_datastore();
		if($this->uf_select_comprobante($as_codemp,$as_procede_anulacion,$as_comprobante,$adt_fecha_anula,$as_codban,$as_ctaban))
		{
			$this->io_msg->message("ERROR-> El comprobante ya existe!");
			return false;
		}		
		if(!$this->uf_init_load_anular_datastore_integracion($as_codemp,$as_procedencia,$as_comprobante,$adt_fecha,$as_codban,$as_ctaban))
		{
		   $this->io_msg->message("ERROR-> en carga datos para la anulación del comprobante ".$as_comprobante);
		   return false;
		}
		return $lb_valido;	
	}// end function uf_int_anular
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_init_valida_parametros_anulacion($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$as_procede_anulacion,$as_descripcion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_init_valida_parametros_anulacion
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//       		   as_procedencia // Procede del Documento Original
		//       		   as_comprobante // Número del Comprobante Original
		//       		   adt_fecha // Fecha del Comprobante Original
		//       		   as_procede_anulacion // Procede del Documento a Anular
		//       		   as_descripcion // Descripción del Documento
		//	  Description: Este método valida los parámetros de anulación.
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_validar=true;
		$ls_bloanu=$_SESSION["la_empresa"]["bloanu"];
		if($ls_bloanu=="0")
		{
			switch ($as_procede_anulacion)
			{
				case "SOCAOS":
					$lb_validar=false;
				break;
				case "SOCAOC":
					$lb_validar=false;
				break;
				case "CXPAOP":
					$lb_validar=false;
				break;
				case "SCBBAC":
					$lb_validar=false;
				break;
				case "SCBBAH":
					$lb_validar=false;
				break;
			}
		}
		if((is_null($as_comprobante))or(empty($as_comprobante)))
		{
			$this->io_msg->message("El N° de Comprobante no puede tener valor nulo o vacío.");			
			return false;	
		}
		if((is_null($as_procedencia))or(empty($as_procedencia)))
		{
			$this->io_msg->message("La procedencia no puede tener valor nulo o vacio .");
			return false;	
		} 	  
		if((is_null($as_descripcion))or(empty($as_descripcion)))
		{
			$this->io_msg->message("La descripción no puede tener valor nulo o vacío.");
			return false;
		} 	
		if((is_null($as_procede_anulacion)) or (empty($as_procede_anulacion)))
		{
			$this->io_msg->message("La procedencia de anulación no puede tener valor nulo.");
			return false;	
		} 	  
		$as_comprobante = $this->uf_fill_comprobante($as_comprobante);		
		if(!($this->uf_valida_procedencia($as_procedencia,$ls_desproc)))
		{ 
			return false;
		}
		$ls_desproc="";
		if(!($this->uf_valida_procedencia($as_procede_anulacion,&$ls_desproc)))
		{
			return false;
		}
		if($lb_validar)
		{
			if(!($this->int_fecha->uf_valida_fecha_mes($as_codemp,$as_fecha)))
			{
				$this->is_msg_error=$this->int_fecha->is_msg_error;
				return false;
			}
		}
		return true;
	}// end function uf_init_valida_parametros_anulacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_init_load_anular_datastore_integracion($as_codemp,$as_procedencia,$as_comprobante,$adt_fecha,$as_codban,$as_ctaban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_init_load_anular_datastore_integracion
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//       		   as_procedencia // Procede del Documento Original
		//       		   as_comprobante // Número del Comprobante Original
		//       		   adt_fecha // Fecha del Comprobante Original
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta de Banco
		//	  Description: Método que llena los datastore con la información del comprobante que desea anular
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		// Datastore Contable
		$ls_sql="SELECT sc_cuenta, debhab, sum(monto) as monto, comprobante as documento, procede as procede_doc, ".
				"		max(descripcion) as descripcion ".
				"  FROM scg_dt_cmp ".
				" WHERE codemp='".$as_codemp."' ".
				"   AND procede='".$as_procedencia."' ".
			    "   AND comprobante='".$as_comprobante."' ".
				"   AND fecha='".$adt_fecha."'".
				"   AND codban='".$as_codban."'".
				"   AND ctaban='".$as_ctaban."'".
				" GROUP BY sc_cuenta,debhab,procede,comprobante".
				" ORDER BY debhab DESC";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_int MÉTODO->uf_init_load_anular_datastore_integracion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_scg->insertRow("sc_cuenta",$row["sc_cuenta"]);
				$this->ds_scg->insertRow("procede_doc",$row["procede_doc"]);
				$this->ds_scg->insertRow("documento",$row["documento"]);
				$ls_debhab = $row["debhab"];
				if($ls_debhab=="D")
				{
					$ls_debhab="H";
				}
				else
				{
					$ls_debhab="D";
				}
				$this->ds_scg->insertRow("debhab",$ls_debhab);
				$this->ds_scg->insertRow("descripcion",$row["descripcion"]);
				$this->ds_scg->insertRow("monto",$row["monto"]);
			}
			$this->io_sql->free_result($rs_data);				
		}
		// Datastore Gasto
		$ls_sql="SELECT dt.codestpro1, dt.codestpro2, dt.codestpro3, dt.codestpro4, dt.codestpro5, dt.spg_cuenta, dt.operacion,".
				"       sum(dt.monto*-1) as monto, dt.comprobante as documento, dt.procede as procede_doc, max(dt.descripcion) as descripcion, ".
				"       c.sc_cuenta as sc_cuenta, dt.estcla as estcla ".
				" FROM spg_dt_cmp dt,spg_cuentas c ".
				" WHERE dt.codemp='".$as_codemp."' ".
				"   AND dt.procede='".$as_procedencia."' ".
				"   AND dt.comprobante='".$as_comprobante."' ".
				"   AND dt.fecha='".$adt_fecha."'". 
				"   AND dt.codban='".$as_codban."' ".
				"   AND dt.ctaban='".$as_ctaban."' ".
				"   AND dt.codemp=c.codemp ".
				"	AND dt.estcla=c.estcla ".
				"	AND dt.codestpro1=c.codestpro1 ".
				"   AND dt.codestpro2=c.codestpro2 ".
				"   AND dt.codestpro3=c.codestpro3 ".
				"   AND dt.codestpro4=c.codestpro4 ".
				"   AND dt.codestpro5=c.codestpro5 ".
				"   AND dt.spg_cuenta=c.spg_cuenta ".
				" GROUP BY dt.estcla,dt.codestpro1, dt.codestpro2, dt.codestpro3, dt.codestpro4, dt.codestpro5, dt.spg_cuenta, c.sc_cuenta, ".
				"		   dt.operacion,dt.procede,dt.comprobante ";
		$rs_data = $this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_int MÉTODO->uf_init_load_anular_datastore_integracion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_spg->insertRow("codestpro1",$row["codestpro1"]);
				$this->ds_spg->insertRow("codestpro2",$row["codestpro2"]);
				$this->ds_spg->insertRow("codestpro3",$row["codestpro3"]);
				$this->ds_spg->insertRow("codestpro4",$row["codestpro4"]);
				$this->ds_spg->insertRow("codestpro5",$row["codestpro5"]);
				$this->ds_spg->insertRow("estcla",$row["estcla"]);
				$this->ds_spg->insertRow("spg_cuenta",$row["spg_cuenta"]);
				$ls_operacion = $this->int_spg->uf_operacion_codigo_mensaje($row["operacion"]);
				$this->ds_spg->insertRow("operacion",$ls_operacion);
				$this->ds_spg->insertRow("documento",$row["documento"]);
				$this->ds_spg->insertRow("procede_doc",$row["procede_doc"]);
				$this->ds_spg->insertRow("descripcion",$row["descripcion"]);
				$this->ds_spg->insertRow("sc_cuenta",$row["sc_cuenta"]);
				$this->ds_spg->insertRow("monto",$row["monto"]);	
			}
			$this->io_sql->free_result($rs_data);
		}
		// Datastore Ingresos
		$ls_sql="SELECT dt.spi_cuenta as spi_cuenta, dt.procede_doc as procede_doc, dt.comprobante as documento,".
		        "       dt.operacion as operacion, ".
				"	    dt.descripcion as descripcion, (dt.monto*-1) as monto, c.sc_cuenta as sc_cuenta, ".
				"       dt.estcla as estcla, dt.codestpro1 as codestpro1, dt.codestpro2 as codestpro2,   ".
				"       dt.codestpro3 as codestpro3, dt.codestpro4 as codestpro4, dt.codestpro5 as codestpro5 ".
				"  FROM spi_dt_cmp dt, spi_cuentas c".
				" WHERE dt.codemp='".$as_codemp."' ".
				"   AND dt.procede='".$as_procedencia."' ".
				"   AND dt.comprobante='".$as_comprobante."' ".
				"   AND dt.fecha='".$adt_fecha."'".
				"   AND dt.codban='".$as_codban."' ".
				"   AND dt.ctaban='".$as_ctaban."' ".
				"   AND dt.codemp=c.codemp ".
				"   AND dt.spi_cuenta=c.spi_cuenta ".
				" ORDER BY orden";
		$rs_data = $this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$this->is_msg_error="CLASE->sigesp_int_int MÉTODO->uf_init_load_anular_datastore_integracion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_spi->insertRow("spi_cuenta",$row["spi_cuenta"]);
				$this->ds_spi->insertRow("procede_doc",$row["procede_doc"]);
				$this->ds_spi->insertRow("documento",$row["documento"]);
				$this->ds_spi->insertRow("operacion",$row["operacion"]);
				$this->ds_spi->insertRow("descripcion",$row["descripcion"]);
				$this->ds_spi->insertRow("monto",$row["monto"]);
				$this->ds_spi->insertRow("sc_cuenta",$row["sc_cuenta"]);	
				$this->ds_spi->insertRow("codestpro1",$row["codestpro1"]);
				$this->ds_spi->insertRow("codestpro2",$row["codestpro2"]);	
				$this->ds_spi->insertRow("codestpro3",$row["codestpro3"]);			
				$this->ds_spi->insertRow("codestpro4",$row["codestpro4"]);
				$this->ds_spi->insertRow("codestpro5",$row["codestpro5"]);
				$this->ds_spi->insertRow("estcla",$row["estcla"]);
			}
			$this->io_sql->free_result($rs_data);
		} 
	  return $lb_valido;
	}// end function uf_init_load_anular_datastore_integracion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_locate_movimiento_scg($as_cuenta,$as_debhab)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_locate_movimiento_scg
		//		   Access: public 
		//       Argument: as_cuenta // Cuenta Contable
		//       		   as_debhab // Operación Debe ó haber
		//	  Description: Método que verifica si una cuenta contable existe en el datastored de contabilidad
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_row_found=0;
		$li_row=$this->ds_scg->getRowCount("sc_cuenta");
		for($li_count=0;$li_count<=$li_row;$li_count++)
		{
			$lscg_cuenta = $this->ds_scg->getValue("sc_cuenta",$li_count);	 
			$ls_debhab = $this->ds_scg->getValue("debhab",$li_count);	 
			if(($lscg_cuenta==$as_cuenta) and ($ls_debhab==$as_debhab))
			{
				$li_row_found = $li_count;
				break;
			}
		}
		return $li_row_found;
	}// end function uf_locate_movimiento_scg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_cierre_spg(&$as_estciespg)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_cierre_spg
		//		   Access: public 
		//	    Arguments: 
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de verificar si esta procesado pesupuesto
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/08/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$lb_valido=true;
		$as_estciespg="";
		$ls_sql="SELECT estciespg ".
				"  FROM sigesp_empresa ".
		  		" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->is_msg_error="CLASE->sigesp_int_int MÉTODO->uf_verificar_cierre_spg ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_estciespg=$row["estciespg"];
			}
			$this->io_sql->free_result($rs_data);
		}	
		return $lb_valido;
	}// end function uf_verificar_cierre_spg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_cierre_scg(&$as_estciescg)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_cierre_scg
		//		   Access: public 
		//	    Arguments: 
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de verificar si esta procesado contabilidad
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/08/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$lb_valido=true;
		$as_estciescg="";
		$ls_sql="SELECT estciescg ".
				"  FROM sigesp_empresa ".
		  		" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->is_msg_error="CLASE->sigesp_int_int MÉTODO->uf_verificar_cierre_scg ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_estciescg=$row["estciescg"];
			}
			$this->io_sql->free_result($rs_data);
		}	
		return $lb_valido;
	}// end function uf_verificar_cierre_spg
	//-----------------------------------------------------------------------------------------------------------------------------------
	
}
?>