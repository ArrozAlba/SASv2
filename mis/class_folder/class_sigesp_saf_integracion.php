<?php
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  //       Class : class_sigesp_saf_integracion_php                                                     //    
  // Description : Esta clase tiene todos los metodos necesario para el manejo de la rutina integradora //
  //               con el sistema de activos fijo movimiento y depreciacion                             //               
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
class class_sigesp_saf_integracion
{
	//Instancia de la clase funciones.
    var $is_msg_error;
	var $dts_empresa; 
	var $dts_saf;
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

	//-----------------------------------------------------------------------------------------------------------------------------------
	function class_sigesp_saf_integracion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: class_sigesp_sno_integracion
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno	
		// Modificado Por: 														Fecha Última Modificación : 25/10/2006
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
		$this->dts_saf=new class_datastore();
		$this->io_msg=new class_mensajes();		
		$this->io_sigesp_int_spg=new class_sigesp_int_spg();
		$this->io_sigesp_int_scg=new class_sigesp_int_scg();		
		$this->io_seguridad=new sigesp_c_seguridad();		
		$this->as_procede="";
		$this->as_comprobante="";
		$this->ad_fecha="";
		$this->as_codban="";
		$this->as_ctaban="";
	}// end function class_sigesp_saf_integracion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destroy_objects()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destroy_objects
		//		   Access: public 
		//	  Description: Destructor de los objectos de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 												Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
       if( is_object($this->io_fecha) ) { unset($this->io_fecha);  }
       if( is_object($this->io_sigesp_int) ) { unset($this->io_sigesp_int);  }
	   if( is_object($this->io_function) ) { unset($this->io_function);  }
	   if( is_object($this->io_siginc) ) { unset($this->io_siginc);  }
	   if( is_object($this->io_connect) ) { unset($this->io_connect);  }
	   if( is_object($this->io_sql) ) { unset($this->io_sql);  }	   
	   if( is_object($this->obj) ) { unset($this->obj);  }	   
	   if( is_object($this->dts_empresa) ) { unset($this->dts_empresa);  }	   
	   if( is_object($this->dts_saf) ) { unset($this->dts_saf);  }	   	   
	   if( is_object($this->io_msg) ) { unset($this->io_msg);  }	   
	   if( is_object($this->io_sigesp_int_spg) ) { unset($this->io_sigesp_int_spg);  }	   
	   if( is_object($this->io_sigesp_int_scg) ) { unset($this->io_sigesp_int_scg);  }	   
	   if( is_object($this->io_seguridad) ) { unset($this->io_seguridad);  }	   
	}// end function uf_destroy_objects
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_contabilizacion_depreciacion($as_comprobante,$as_descripcion,$as_mes,$as_ano,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contabilizacion_depreciacion
		//		   Access: public (sigesp_mis_p_contabiliza_saf.php)
		//	    Arguments: as_comprobante  // comprobante
		//	    		   as_descripcion  // Descripción del comprobante
		//				   as_mes  // Mes de la Depreciación
		//				   as_ano  // Año de la Depreciación
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Funcion que procesa la contabilización de una Depreciacón dado un mes
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 												Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->is_procede="SAFDPR";
		$ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante(trim($as_comprobante));
		$ls_descripcion=strtoupper($as_descripcion);
		$ls_tipo_destino="-";
		$ls_codigo_destino="----------";
		$ld_fecha=$this->io_fecha->uf_last_day($as_mes,$as_ano);
		$adt_fecha=$this->io_function->uf_convertirdatetobd($ld_fecha);
		// Creo la cabecera del Comprobante
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		$li_tipo_comp=1; // comprobante Normal
		$this->as_procede=$this->is_procede;
		$this->as_comprobante=$ls_comprobante;
		$this->ad_fecha=$adt_fecha;
		$this->as_codban=$ls_codban;
		$this->as_ctaban=$ls_ctaban;
		$lb_valido=$this->io_sigesp_int->uf_int_init($this->is_codemp,$this->is_procede,$ls_comprobante,$adt_fecha,
													 $ls_descripcion,$ls_tipo_destino,$ls_codigo_destino,false,$ls_codban,
													 $ls_ctaban,$li_tipo_comp);
		$this->io_sigesp_int->uf_int_config(false,false);
		if (!$lb_valido)
		{   
           $this->io_msg->message($this->io_sigesp_int->is_msg_error); 
		   return false;		   		   
		}
		// inicia transacción SQL
		$this->io_sigesp_int->uf_int_init_transaction_begin();
		if($lb_valido)
		{
			// Se procesan los detalles de presupuesto
			$lb_valido=$this->uf_procesar_detalles_gasto($as_mes,$as_ano,$ls_comprobante,$ls_descripcion);  
		}
		if ($lb_valido)
        {	// Se procesan los detalles de Contabilidad
			$lb_valido = $this->uf_procesar_detalles_contables($as_mes,$as_ano,$ls_comprobante,$ls_descripcion); 
			if ($lb_valido)
			{	// Se inserta el comprobante con sus detalles contables y presupuestarios
				$lb_valido = $this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
				if (!$lb_valido) 
				{ 
					if (!empty($this->io_sigesp_int->is_msg_error))
					{
						$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
					}	
				}
			}
			if($lb_valido)
			{	// Se Actualiza el estatus de la nómina que está contabilizada
				$lb_valido=$this->uf_update_estatus_depreciacion($as_mes,$as_ano,1);
			}
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_saf($this->is_codemp,$as_ano,$as_mes,$adt_fecha,'1900-01-01');
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Contabilizó la Depreciación para el Año <b>".$as_ano."</b>, Mes <b>".$as_mes."</b>, ".
							"Comprobante <b>".$ls_comprobante."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if ($lb_valido)
		   {
		     $lb_valido = $this->uf_procesar_detalles_contables_int($as_mes,$as_ano,$ls_comprobante,$ls_descripcion,
			                                                        $this->as_procede,$this->ad_fecha,$this->as_codban,$this->as_ctaban,$aa_seguridad); 
		   }
		// Se Finaliza la transacción con Commit ó Rollback de acuerdo al $lb_valido
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return  $lb_valido;
	} // end function uf_procesar_contabilizacion_tipo_nomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_detalles_gasto($as_mes,$as_ano,$as_comprobante,$as_descripcion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_detalles_gasto
		//		   Access: private
		//	    Arguments: as_mes  // Mes de la Depreciación
		//	   			   as_ano  // año de la Depreciación
		//	   			   as_comprobante  // Código del comprobante de Activos
		//	    		   as_descripcion  // Descripción del comprobante
		//	      Returns: lb_valido True si se insertaron correctamente los detalles en el datastored
		//	  Description: Método que recorre la tabla generada por Activos en la depreciación de asientos de gastos para ser
		//                  insertado en el datastore para la integración contable presupuestaria.
		//	   Creado Por: Ing. Yesenia Moreno	
		// Modificado Por: 											Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="SELECT saf_depreciacion.mondepmen AS monto, saf_activo.codestpro1, saf_activo.codestpro2, ".
				"		saf_activo.codestpro3, saf_activo.codestpro4, saf_activo.codestpro5, saf_activo.spg_cuenta_dep, ".
				"		saf_dta.estact, saf_dta.fecdesact, saf_activo.estcla ".
				"  FROM saf_depreciacion, saf_activo, saf_dta ".
				" WHERE saf_depreciacion.codemp='".$this->is_codemp."' ".
				"   AND SUBSTR(saf_depreciacion.fecdep,1,4) = '".$as_ano."' ".
				"   AND SUBSTR(saf_depreciacion.fecdep,6,2) = '".$as_mes."' ".
				"   AND (saf_dta.estact = 'I' OR saf_dta.estact = 'M' OR saf_dta.estact = 'D') ".
				"   AND saf_depreciacion.codemp = saf_activo.codemp	".
				"   AND saf_depreciacion.codact = saf_activo.codact ".
				"   AND saf_depreciacion.codemp = saf_dta.codemp ".
				"   AND saf_depreciacion.codact = saf_dta.codact ".
				"   AND saf_depreciacion.ideact = saf_dta.ideact ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
           	$this->io_msg->message("CLASE->Integración SAF MÉTODO->uf_procesar_detalles_gasto ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{     
			$this->dts_saf->reset_ds();      
			while($row=$this->io_sql->fetch_row($rs_data) and ($lb_valido))
			{
				$ls_codestpro1 = $row["codestpro1"];
				$ls_codestpro2 = $row["codestpro2"];
				$ls_codestpro3 = $row["codestpro3"];
				$ls_codestpro4 = $row["codestpro4"];
				$ls_codestpro5 = $row["codestpro5"];
				$ls_estcla=$row["estcla"];			  
				$ls_spg_cuenta = $row["spg_cuenta_dep"];
				$ls_mensaje = "OCP";
				$li_monto = $row["monto"];
				$li_total=0;
				$ls_documento = "1";								 
				$ls_documento = $this->io_sigesp_int->uf_fill_comprobante(trim($ls_documento));
				$ls_estact=$row["estact"];
				$ls_fecdesact=$row["fecdesact"];
				if($ls_estact=="D")
				{
					if($as_ano==substr($ls_fecdesact,0,4))
					{
						if($as_mes==substr($ls_fecdesact,5,2))
						{
							$li_dia=substr($ls_fecdesact,8,2);
							$li_monto=($li_monto/30)*$li_dia;
							$li_total=$li_monto;
						}
						if($as_mes<substr($ls_fecdesact,5,2))
						{
							$li_total=$li_monto;
						}
					}
					if($as_ano<substr($ls_fecdesact,0,4))
					{
						$li_total=$li_monto;
					}
				}
				else
				{
					$li_total=$li_monto;
				}
				$this->dts_saf->insertRow("codestpro1",$ls_codestpro1);
				$this->dts_saf->insertRow("codestpro2",$ls_codestpro2);
				$this->dts_saf->insertRow("codestpro3",$ls_codestpro3);
				$this->dts_saf->insertRow("codestpro4",$ls_codestpro4);
				$this->dts_saf->insertRow("codestpro5",$ls_codestpro5);
				$this->dts_saf->insertRow("estcla",$ls_estcla);
				$this->dts_saf->insertRow("spg_cuenta_dep",$ls_spg_cuenta);
				$this->dts_saf->insertRow("mensaje",$ls_mensaje);
				$this->dts_saf->insertRow("monto",$li_total);
				$this->dts_saf->insertRow("documento",$ls_documento);
			} // end while
			$this->dts_saf->group_by(array('0'=>'codestpro1','1'=>'codestpro2','2'=>'codestpro3','3'=>'codestpro4',
										   '4'=>'codestpro5','5'=>'estcla','6'=>'spg_cuenta_dep'),array('0'=>'monto'),'monto');
			$li_totrow=$this->dts_saf->getRowCount("spg_cuenta_dep");
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_codestpro1 = $this->dts_saf->data["codestpro1"][$li_i];
				$ls_codestpro2 = $this->dts_saf->data["codestpro2"][$li_i];
				$ls_codestpro3 = $this->dts_saf->data["codestpro3"][$li_i];
				$ls_codestpro4 = $this->dts_saf->data["codestpro4"][$li_i];
				$ls_codestpro5 = $this->dts_saf->data["codestpro5"][$li_i];
				$ls_estcla = $this->dts_saf->data["estcla"][$li_i];
				$ls_spg_cuenta = $this->dts_saf->data["spg_cuenta_dep"][$li_i];
				$ls_mensaje = $this->dts_saf->data["mensaje"][$li_i];
				$ldec_monto = $this->dts_saf->data["monto"][$li_i];
				$ls_documento = $this->dts_saf->data["documento"][$li_i];							 
				// Insertar el el datastored los detalles de presupuesto
				$lb_valido = $this->io_sigesp_int->uf_spg_insert_datastore($this->is_codemp,$ls_codestpro1,$ls_codestpro2,
																		  $ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,
																		  $ls_spg_cuenta,$ls_mensaje,$ldec_monto,
																		  $ls_documento,$this->is_procede,$as_descripcion);
				if (!$lb_valido)
				{  
					$this->io_msg->message("ERROR->".$this->io_sigesp_int->is_msg_error);
				}
		   } // end while
		}
		$this->io_sql->free_result($rs_data);	 
		return $lb_valido;
    } //  end function uf_procesar_detalles_gasto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_detalles_contables($as_mes,$as_ano,$as_comprobante,$as_descripcion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_detalles_contables
		//		   Access: private
		//	    Arguments: as_mes  // Mes de la Depreciación
		//	   			   as_ano  // año de la Depreciación
		//	   			   as_comprobante  // Código del comprobante de Activos
		//	    		   as_descripcion  // Descripción del comprobante
		//	      Returns: lb_valido True si se insertaron correctamente los detalles en el datastored
		//	  Description: Método que recorre la tabla generada por Activos en la depreciación de asientos de contabilidad para ser
		//                  insertado en el datastore para la integración contable presupuestaria.
		//	   Creado Por: Ing. Yesenia Moreno	
		// Modificado Por: 												Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="SELECT SUM(saf_depreciacion.mondepmen) AS monto, 'D' AS operacion, spg_cuentas.sc_cuenta ".
				"  FROM saf_depreciacion,saf_activo, spg_cuentas ".
				" WHERE saf_depreciacion.codemp='".$this->is_codemp."' ".
				"   AND SUBSTR(saf_depreciacion.fecdep,1,4) = '".$as_ano."' ".
				"   AND SUBSTR(saf_depreciacion.fecdep,6,2) = '".$as_mes."' ".
				"   AND saf_depreciacion.codemp = saf_activo.codemp ".
				"   AND saf_depreciacion.codact = saf_activo.codact ".
				"   AND saf_activo.codemp = spg_cuentas.codemp ".
				"   AND saf_activo.codestpro1 = spg_cuentas.codestpro1 ".
				"   AND saf_activo.codestpro2 = spg_cuentas.codestpro2 ".
				"   AND saf_activo.codestpro3 = spg_cuentas.codestpro3 ".
				"   AND saf_activo.codestpro4 = spg_cuentas.codestpro4 ".
				"   AND saf_activo.codestpro5 = spg_cuentas.codestpro5 ".
				"   AND saf_activo.estcla = spg_cuentas.estcla ".
				"   AND saf_activo.spg_cuenta_dep = spg_cuentas.spg_cuenta ".
				" GROUP BY spg_cuentas.sc_cuenta ".
				" UNION ".
				"SELECT SUM(saf_depreciacion.mondepmen) AS monto, 'H' AS operacion, saf_activo.sc_cuenta ".
				"  FROM saf_depreciacion,saf_activo, spg_ep1 ".
				" WHERE saf_depreciacion.codemp='".$this->is_codemp."' ".
				"   AND SUBSTR(saf_depreciacion.fecdep,1,4) = '".$as_ano."' ".
				"   AND SUBSTR(saf_depreciacion.fecdep,6,2) = '".$as_mes."' ".
				"   AND spg_ep1.estint=0".
				"   AND saf_depreciacion.codemp = saf_activo.codemp ".
				"   AND saf_depreciacion.codact = saf_activo.codact ".
				"   AND saf_activo.codemp=spg_ep1.codemp".
				"   AND saf_activo.codestpro1=spg_ep1.codestpro1".
				"   AND saf_activo.estcla=spg_ep1.estcla".
				" GROUP BY saf_activo.sc_cuenta".
				" UNION ".
				"SELECT SUM(saf_depreciacion.mondepmen) AS monto, 'H' AS operacion, trim(spg_ep1.sc_cuenta) as sc_cuenta ".
				"  FROM saf_depreciacion,saf_activo, spg_ep1 ".
				" WHERE saf_depreciacion.codemp='".$this->is_codemp."' ".
				"   AND SUBSTR(saf_depreciacion.fecdep,1,4) = '".$as_ano."' ".
				"   AND SUBSTR(saf_depreciacion.fecdep,6,2) = '".$as_mes."' ".
				"   AND spg_ep1.estint=1".
				"   AND saf_depreciacion.codemp = saf_activo.codemp ".
				"   AND saf_depreciacion.codact = saf_activo.codact ".
				"   AND saf_activo.codemp=spg_ep1.codemp".
				"   AND saf_activo.codestpro1=spg_ep1.codestpro1".
				"   AND saf_activo.estcla=spg_ep1.estcla".
				" GROUP BY spg_ep1.sc_cuenta";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
           	$this->io_msg->message("CLASE->Integración SAF MÉTODO->uf_procesar_detalles_contables ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{           
   	       while($row=$this->io_sql->fetch_row($rs_data) and ($lb_valido))
		   {
				$ls_mensaje = $row["operacion"];
				$ls_scg_cuenta = $row["sc_cuenta"];
				/*if(((substr($ls_scg_cuenta,0,3)=="225")&&($ls_mensaje=="H"))||(($ls_mensaje=="D")))
				{ SE QUITA VALIDACION POR EL MANEJO DE INTER COMPAÑIA, EL ACTIVO DEBE VALIDAR LA CUENTA 225*/
					$ldec_monto = $row["monto"];
					if($ls_mensaje=="D")
					{				
						$ls_documento = "1";								
					}
					else
					{
						$ls_documento = "2";								
					}
					$ls_documento = $this->io_sigesp_int->uf_fill_comprobante(trim($ls_documento));
					// Incluimos el detalle de contabilidad en el datastored
					$lb_valido = $this->io_sigesp_int->uf_scg_insert_datastore($this->is_codemp,$ls_scg_cuenta,$ls_mensaje,$ldec_monto,$ls_documento,$this->is_procede,$as_descripcion);				
					if (!$lb_valido)
					{  
					   $this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
					   $lb_valido=false;
					   break;
					}
				/*}
				else
				{
				   $this->io_msg->message("ERROR-> La Cuenta Contable del activo debe Comenzar por 225");
				   $lb_valido=false;
				   break;
				}*/
		   } // end while
		}
		$this->io_sql->free_result($rs_data);	 
		return $lb_valido;
    } // end function uf_procesar_detalles_contables
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_detalles_contables_int($as_mes,$as_ano,$as_comprobante,$as_descripcion,$as_procede,$ad_fecha,$as_codban,$as_ctaban,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_detalles_contables_int
		//		   Access: private
		//	    Arguments: as_mes  // Mes de la Depreciación
		//	   			   as_ano  // año de la Depreciación
		//	   			   as_comprobante  // Código del comprobante de Activos
		//	    		   as_descripcion  // Descripción del comprobante
		//	      Returns: lb_valido True si se insertaron correctamente los detalles en el datastored
		//	  Description: Método que recorre la tabla generada por Activos en la depreciación de asientos de contabilidad para ser
		//                  insertado en el datastore para la integración contable presupuestaria.
		//	   Creado Por: Ing. Néstor Falcón.	
		// Modificado Por: 												Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql = "	SELECT SUM(saf_depreciacion.mondepmen) AS monto, 'D' AS operacion, trim(spg_ep1.sc_cuenta) as sc_cuenta
				      FROM saf_depreciacion,saf_activo, spg_ep1
					 WHERE saf_depreciacion.codemp='".$this->is_codemp."'
					   AND SUBSTR(saf_depreciacion.fecdep,1,4) = '".$as_ano."'
					   AND SUBSTR(saf_depreciacion.fecdep,6,2) = '".$as_mes."'
					   AND spg_ep1.estint=1
					   AND saf_depreciacion.codemp = saf_activo.codemp
					   AND saf_depreciacion.codact = saf_activo.codact
					   AND saf_activo.codemp=spg_ep1.codemp
					   AND saf_activo.codestpro1=spg_ep1.codestpro1
					   AND saf_activo.estcla=spg_ep1.estcla
					 GROUP BY spg_ep1.sc_cuenta
					 UNION
					SELECT SUM(saf_depreciacion.mondepmen) AS monto, 'H' AS operacion, saf_activo.sc_cuenta
					  FROM saf_depreciacion,saf_activo, spg_ep1
					 WHERE saf_depreciacion.codemp='".$this->is_codemp."'
					   AND SUBSTR(saf_depreciacion.fecdep,1,4) = '".$as_ano."'
					   AND SUBSTR(saf_depreciacion.fecdep,6,2) = '".$as_mes."'
					   AND spg_ep1.estint=1
					   AND saf_depreciacion.codemp = saf_activo.codemp
					   AND saf_depreciacion.codact = saf_activo.codact
					   AND saf_activo.codemp=spg_ep1.codemp
					   AND saf_activo.codestpro1=spg_ep1.codestpro1
					   AND saf_activo.estcla=spg_ep1.estcla
					 GROUP BY saf_activo.sc_cuenta";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
           	$this->io_msg->message("CLASE->Integración SAF MÉTODO->uf_procesar_detalles_contables_int();ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{           
		  $li_orden = 0;  
		  while ($row=$this->io_sql->fetch_row($rs_data))
		        {
				  $li_orden++;
				  $ld_monto  = $row["monto"];
				  $ls_debhab = $row["operacion"];
				  $ls_scgcta = trim($row["sc_cuenta"]);
				  if ($ls_debhab=="D")
				     {				
				       $ls_numdoc = "1";								
				     }
				  else
				     {
				       $ls_numdoc = "2";								
				     }
				  $ls_numdoc = $this->io_sigesp_int->uf_fill_comprobante(trim($ls_numdoc));
				  
				  $ls_sql = "INSERT INTO saf_depreciacion_int (codemp,procede,comprobante,fecha,codban,ctaban,sc_cuenta,procede_doc,documento,debhab,descripcion,monto,orden,estrepasi) 
				                  VALUES ('".$this->is_codemp."','".$as_procede."','".$as_comprobante."','".$ad_fecha."',
								          '".$as_codban."','".$as_ctaban."','".$ls_scgcta."','".$as_procede."','".$ls_numdoc."',
										  '".$ls_debhab."','".$as_descripcion."',".$ld_monto.",".$li_orden.",0)";
				  
				  $rs_datos = $this->io_sql->execute($ls_sql);
				  if ($rs_datos===false)
				     {
					   $lb_valido = false;
					   $this->io_msg->message("CLASE->class_sigesp_saf_integracion.php();MÉTODO->uf_procesar_detalles_contables_int();ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					   echo $this->io_sql->message;
					   break;
					 }
				  else
				     {
					   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
					   $ls_evento="PROCESS";
					   $ls_descripcion="Insertó Detalle Contable de Intercompañia con la Cuenta $ls_scgcta - $ls_debhab, para el Año <b>".$as_ano."</b>, Mes <b>".$as_mes."</b>, Comprobante <b>".$as_comprobante."</b>";
					   $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					   /////////////////////////////////         SEGURIDAD               /////////////////////////////					 
					 }
				}
		}
		$this->io_sql->free_result($rs_data);	 
		return $lb_valido;
    } // end function uf_procesar_detalles_contables
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_depreciacion($as_mes,$as_ano,$ai_estatus)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_depreciacion
		//		   Access: private
		//	    Arguments: as_mes  // Mes de la Depreciación
		//	   			   as_ano  // año de la Depreciación
		//				   ai_estatus  // estatus si es 0 ó 1
		//	      Returns: lb_valido True si se actualizó correctamente
		//	  Description: Método que actualiza el estatus de la Depreciaicón de los Activos
		//	   Creado Por: Ing. Yesenia Moreno	
		// Modificado Por: 											Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;	
		$ls_sql="UPDATE saf_depreciacion ".
				"   SET estcon=".$ai_estatus.
				" WHERE codemp='".$this->is_codemp."' ".
				"   AND SUBSTR(fecdep,1,4)='".$as_ano."' ".
				"   AND SUBSTR(fecdep,6,2)='".$as_mes."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
           	$this->io_msg->message("CLASE->Integración SAF MÉTODO->uf_update_estatus_depreciacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_estatus_depreciacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reversar_contabilizacion_depreciacion($as_comprobante,$as_mes,$as_ano,$ad_fechaconta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar_contabilizacion_depreciacion
		//		   Access: public (sigesp_mis_p_reverso_deprecicacion_saf.php)
		//	   	Arguments: as_comprobante  // Código del comprobante de Activos
		//	   			   as_mes  // Mes de la Depreciación
		//	   			   as_ano  // año de la Depreciación
		//	   			   ad_fechaconta  // Fecha de Contabilización del Comprobante
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el reverso correctamente
		//	  Description: Método que reversa la contabilizacion de la nomina
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 												Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
   	    $ldt_fecha=$ad_fechaconta;
        $ls_codemp=$this->is_codemp;
        $ls_procede="SAFDPR";
        $ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante(trim($as_comprobante));
		$ls_tipo_destino="-";
		$ls_ced_bene="----------";
		$ls_cod_pro="----------";
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		// Iniciamos la transacción en la BD
        $this->io_sigesp_int->uf_int_init_transaction_begin();
		$lb_valido = $this->uf_delete_depreciacion_int($as_comprobante);
		// Buscamos el comprobante a reversar						
	    if ($lb_valido)
		   {
		     $lb_valido = $this->io_sigesp_int->uf_obtener_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_codban,
																       $ls_ctaban,$ls_tipo_destino,$ls_ced_bene,$ls_cod_pro);
		   }
		if (!$lb_valido) 
		{ 
		   	$this->io_msg->message("ERROR-> No existe el comprobante Nº ".$ls_comprobante."-".$ls_procede.".");
			return false;
		}
		$lb_check_close=false;
		// Creamos la cabecera del comprobante y validamos la información
		$lb_valido = $this->io_sigesp_int->uf_init_delete($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_tipo_destino,
														  $ls_ced_bene,$ls_cod_pro,$lb_check_close,$ls_codban,$ls_ctaban);
		if(!$lb_valido)	
		{ 
 		   $this->io_msg->message("".$this->io_sigesp_int->is_msg_error);
		   return false; 
		}
		
		if($lb_valido)
		{	// Reversamos los detalles y el comprobante
			$lb_valido = $this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
			if(!$lb_valido)
			{
				$this->io_msg->message(" ERROR-> ".$this->io_sigesp_int->is_msg_error);
			}		   
		}
	    if($lb_valido) 
		{
		   $lb_valido=$this->uf_update_estatus_depreciacion($as_mes,$as_ano,0); 
	    } 
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_saf($ls_codemp,$as_ano,$as_mes,'1900-01-01','1900-01-01');
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reverso la Depreciación de los Activos Año <b>".$as_ano."</b>, Mes <b>".$as_mes."</b> ".
							"Comprobante <b>".$ls_comprobante."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		// Finalizamos la transacción en la base de datos
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return  $lb_valido;
    } // end function uf_reversar_contabilizacion_depreciacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_fecha_contabilizado_saf($as_codemp,$as_ano,$as_mes,$ad_fechaconta,$ad_fechaanula)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_fecha_contabilizado_saf
		//		   Access: private
		//	    Arguments: as_codemp  // Código
		//                 as_ano  // Año de la Depreciación
		//                 as_mes  // MEs de la Depreciación
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
		$ls_sql="UPDATE saf_depreciacion ".
		        "   SET ".$ls_campos.
                " WHERE codemp='".$as_codemp."' ".
				"   AND SUBSTR(fecdep,1,4)='".$as_ano."' ".
				"   AND SUBSTR(fecdep,6,2)='".$as_mes."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración SEP MÉTODO->uf_update_fecha_contabilizado_saf ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_fecha_contabilizado_saf
	//-----------------------------------------------------------------------------------------------------------------------------------

function uf_load_depreciacion_int($as_numcom)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_load_depreciacion_int
	//		   Access: private
	//	    Arguments: $as_numcom = Número del Comprobante a ubicar.
	//	      Returns: $lb_valido = True si no encuentra registro alguno de lo contrario False.
	//	  Description: Método que busca si existen registros en la Tabla de comprobantes intercompañias de SAF.
	//	   Creado Por: Ing. Néstor Falcón.
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;
  $ls_sql = "SELECT estrepasi
               FROM saf_depreciacion_int 
			  WHERE codemp='".$this->is_codemp."' 
			    AND comprobante='".$as_numcom."'
				AND estrepasi = '1'";
  
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido = false;
	   $this->io_msg->message("CLASE->class_sigesp_saf_integracion.php;MÉTODO->uf_load_depreciacion_int;ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
	 }
  else
     {
	   if ($row=$this->io_sql->fetch_row($rs_data))
	      {
		    $lb_valido = false;
			$this->io_msg->message("El Comprobante Nro. ".$as_numcom.", Ya fué Replicado !!!");
		  }
	 }
  return $lb_valido;
}

function uf_delete_depreciacion_int($as_numcom)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_delete_depreciacion_int
	//		   Access: private
	//	    Arguments: $as_numcom = Número del Comprobante a ubicar.
	//	      Returns: $lb_valido = True si no encuentra registro alguno de lo contrario False.
	//	  Description: Método que busca si existen registros en la Tabla de comprobantes intercompañias de SAF.
	//	   Creado Por: Ing. Néstor Falcón.
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;
  $ls_sql = "DELETE FROM saf_depreciacion_int 
			  WHERE codemp='".$this->is_codemp."' 
			    AND comprobante='".$as_numcom."'
				AND estrepasi = '0'";
  
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido = false;
	   $this->io_msg->message("CLASE->class_sigesp_saf_integracion.php;MÉTODO->uf_delete_depreciacion_int;ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
	 }
  return $lb_valido;
}
}
?>