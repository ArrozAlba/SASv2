<?php
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  //       Class : class_sigesp_scb_integracion_php                                                     //    
  // Description : Esta clase tiene todos los metodos necesario para el manejo de la rutina integradora //
  //               de los distintos movimientos de banco                                                //               
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
class class_sigesp_scbcol_integracion
{
	//Instancia de la clase funciones.
    var $is_msg_error;
	var $dts_empresa; 
	var $dts_colocacion;
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
	var $is_codban="";
	var $is_ctaban="";
	var $is_numcol="";
	var $is_numdoc="";
	var $is_codope="";
	var $is_estcol="";
	var $is_procede="";
	var $is_procede_doc="";	
	var $is_mensaje_spi="";	
	var $is_mensaje_spg="";	
    var $ii_datasource=0;	
	var $is_comprobante;
	var $is_documento="";
	var $is_spg_cuenta="";
	var $is_scg_cuenta="";	
	var $is_descripcion="";		

	//-----------------------------------------------------------------------------------------------------------------------------------
	function class_sigesp_scbcol_integracion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: class_sigesp_scbcol_integracion
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 06/11/2006
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
		require_once("class_folder/class_sigesp_cxp_integracion.php");  
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
		$this->dts_colocacion=new class_datastore();
		$this->dts_beneficiario=new class_datastore();
		$this->dts_proveedor=new class_datastore();
		$this->io_msg=new class_mensajes();		
		$this->io_sigesp_int_spg = new class_sigesp_int_spg();
		$this->io_sigesp_int_scg = new class_sigesp_int_scg();		
		$this->io_sigesp_int_spi = new class_sigesp_int_spi();		
		$this->io_seguridad=new sigesp_c_seguridad() ;
		$this->as_procede="";
		$this->as_comprobante="";
		$this->ad_fecha="";
		$this->as_codban="";
		$this->as_ctaban="";
	}// end function class_sigesp_scbcol_integracion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destroy_objects()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destroy_objects
		//		   Access: public 
		//	  Description: Destructor de los objectos de la Clase
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 06/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if( is_object($this->io_fecha) ) { unset($this->io_fecha);  }
		if( is_object($this->io_sigesp_int) ) { unset($this->io_sigesp_int);  }
		if( is_object($this->io_function) ) { unset($this->io_function);  }
		if( is_object($this->io_siginc) ) { unset($this->io_siginc);  }
		if( is_object($this->io_connect) ) { unset($this->io_connect);  }
		if( is_object($this->io_sql) ) { unset($this->io_sql);  }	   
		if( is_object($this->obj) ) { unset($this->obj);  }	   
		if( is_object($this->dts_empresa) ) { unset($this->dts_empresa);  }	   
		if( is_object($this->dts_colocacion) ) { unset($this->dts_colocacion);  }	   
		if( is_object($this->dts_beneficiario) ) { unset($this->dts_beneficiario);  }	   
		if( is_object($this->dts_proveedor) ) { unset($this->dts_proveedor);  }	   
		if( is_object($this->io_msg) ) { unset($this->io_msg);  }	   
		if( is_object($this->io_sigesp_int_spg) ) { unset($this->io_sigesp_int_spg);  }	   
		if( is_object($this->io_sigesp_int_scg) ) { unset($this->io_sigesp_int_scg);  }	   
	}// end function uf_destroy_objects
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_contabilizacion_colocacion($as_codban,$as_ctaban,$as_numcol,$as_numdoc,$as_codope,$as_estcol,$adt_fecha,
													$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contabilizacion_colocacion
		//		   Access: public (sigesp_mis_p_contabiliza_scbcol.php)
		//	    Arguments: as_codban  // Código de Banco
		//				   as_ctaban  // Cuenta Bancaria
		//				   as_numcol  // Número de colocación
		//				   as_numdoc  // Número de Documento
		//				   as_codope  // Código de Operación
		//				   as_estmov  // Estatus del Movimiento
		//				   adt_fecha  // Fecha de contabilización
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Proceso de contabilizacion  un movimiento de colocación banco.
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 06/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $ls_scg_colocacion="";
		$ls_spi_colocacion="";
		$this->is_codemp=$this->io_codemp;
	    $this->is_codban=$as_codban;
	    $this->is_ctaban=$as_ctaban;
	    $this->is_numcol=$as_numcol;
	    $this->is_numdoc=$as_numdoc;		
	    $this->is_codope=$as_codope;
	    $this->is_estcol=$as_estcol;
	    $this->is_procede="SCBC".$as_codope;
	    $this->is_procede_doc="SCBC".$as_codope;	
	    $this->is_mensaje_spi="EC";	
	    $this->is_mensaje_spg="OCP";			
	    $this->is_comprobante=$this->io_sigesp_int->uf_fill_comprobante(trim($this->is_numcol));		
		$this->ii_compromiso_previo=false;
		$this->is_documento="";
	    $this->ii_datasource=2;
		// Verificamos si la colocación existe y la agregamos al datastored
        if(!$this->uf_obtener_data_movimiento_colocacion())
		{
			return false;
		}
		//Buscamos las cuentas contables y de ingreso
        $lb_valido=$this->uf_obtener_codigo_contable_colocacion( &$ls_scg_colocacion,&$ls_spi_colocacion);
        if(!$lb_valido)
		{
			return false;
		}
        $ls_tipo="-";   		
        $ls_fuente="----------";		
		$this->is_descripcion=$this->dts_colocacion->getValue("conmov",1);
		$ldt_fecha=$this->dts_colocacion->getValue("fecmovcol",1);
		$this->is_scg_cuenta=$ls_scg_colocacion;		
        $lb_autoconta=true;
        if($this->ii_datasource==2)  
		{ 
		   $this->io_sigesp_int->uf_int_config(false,false); 
		   $lb_autoconta = false;
		}
		// Crea la cabecera del comprobante
		$li_tipo_comp=1; // comprobante Normal
		$this->as_procede=$this->is_procede;
		$this->as_comprobante=$this->is_comprobante;
		$this->ad_fecha=$ldt_fecha;
		$lb_valido = $this->io_sigesp_int->uf_int_init($this->is_codemp,$this->is_procede,$this->is_comprobante,$ldt_fecha,
													   $this->is_descripcion,$ls_tipo,$ls_fuente,$lb_autoconta,$as_codban,$as_ctaban,
													   $li_tipo_comp);
		if ($lb_valido===false)
		{   
           $this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error); 
		   return false;		   		   
		}
        $lb_valido = $this->uf_procesar_contable();
		if (!$lb_valido)
		{   
           $this->io_msg->message("ERROR -> Al Insertar los movimiento de Contabilidad."); 
		   return false;		   		   
		}
 		// inicia transacción SQL
 		$this->io_sigesp_int->uf_int_init_transaction_begin();
		// Crea el movimiento de Colocación
		$lb_valido = $this->uf_create_movimiento_colocacion($this->io_codemp,$as_codban,$as_ctaban,$as_numcol,$as_numdoc,
															$as_codope,$as_estcol,"C",$this->is_procede,$this->is_comprobante,
															$ldt_fecha);
		if (!$lb_valido)
		{   
           $this->io_msg->message("ERROR -> Crear un Nuevo Movimiento de Colocación."); 
		   $this->io_sigesp_int->uf_sql_transaction($lb_valido);
		   return false;		   		   
		}
		// Elimina el movimiento de Colocación
		$lb_valido = $this->uf_delete_movimiento_colocacion($this->io_codemp,$as_codban,$as_ctaban,$as_numcol,$as_numdoc,$as_codope,
															$as_estcol);
		if (!$lb_valido)
		{   
           $this->io_msg->message("ERROR -> Eliminar el Movimiento de Colocación."); 
		   $this->io_sigesp_int->uf_sql_transaction($lb_valido);
		   return false;		   		   
		}
	    if ($lb_valido)
	    {
	        $lb_valido = $this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
	        if($lb_valido===false)
		    {
              $this->io_msg->message("ERROR -> ".$this->io_sigesp_int->is_msg_error);
		    }		   
		}
	    if($lb_valido)
	    {
			$lb_valido=$this->uf_update_fecha_contabilizado_scbcol($this->io_codemp,$as_codban,$as_ctaban,$as_numcol,$as_numdoc,
																   $as_codope,"C",$ldt_fecha,'1900-01-01');
		}
	    if($lb_valido)
	    {
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Contabilizó la colocación Documento <b>".$as_numdoc."</b>, Colocación <b>".$as_numcol."</b>, Banco <b>".$as_codban."</b>, ".
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
    }// end function uf_procesar_contabilizacion_colocacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_data_movimiento_colocacion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_data_movimiento_colocacion
		//		   Access: private
		//	    Arguments: 
		//	      Returns: lb_valido True si se encontro el movimiento ó false si no se encontro
		//	  Description: Funcion que obtiene los datos del movimiento de banco de tipo colocación
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 06/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe=false;		
		$ls_codemp=$this->is_codemp;
		$ls_sql="SELECT * ".
                "  FROM scb_movcol ".
                " WHERE codemp='".$this->is_codemp."' ".
				"   AND codban='".$this->is_codban."' ".
				"   AND ctaban='".$this->is_ctaban."' ".
				"   AND numdoc='".$this->is_numdoc."' ".
				"   AND numcol='".$this->is_numcol."' ".
				"   AND codope='".$this->is_codope."' ".
				"   AND estcol='".$this->is_estcol."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
            $this->io_msg->message("CLASE->Integración SCBCOL MÉTODO->uf_obtener_data_movimiento_colocacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true; // si existe se procedera a registrar en el datastore.				
                $this->dts_colocacion->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
            	$this->io_msg->message("ERROR-> La colocación ".$this->is_numcol." no existe.");			
			}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_existe;
	}// end function uf_obtener_data_movimiento_colocacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_codigo_contable_colocacion(&$as_scg_cuenta,&$as_spi_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_codigo_contable_colocacion
		//		   Access: private
		//	    Arguments: as_scg_cuenta // Cuenta Contable de la colocación
		//	    		   as_spi_cuenta // Cuenta contable de Ingreso
		//	      Returns: lb_valido True si se encontro el movimiento ó false si no se encontro
		//	  Description: Funcion que obtiene el código contable y presupuestario de la colocación
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 06/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe=false;		
		$lb_valido=true;		
		$ls_codemp=$this->is_codemp;
		$ls_sql="SELECT col.*,tc.nomtipcol,ban.nomban ".
                "  FROM scb_colocacion col,scb_tipocolocacion tc,scb_banco ban ".
                " WHERE col.codemp='".$this->is_codemp."' ".
				"   AND col.codban='".$this->is_codban."' ".
				"   AND col.ctaban='".$this->is_ctaban."' ".
				"   AND col.codtipcol=tc.codtipcol ".
				"   AND col.codemp=ban.codemp ".
				"   AND col.codban=ban.codban ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración SCBCOL MÉTODO->uf_obtener_codigo_contable_colocacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			while($row=$this->io_sql->fetch_row($rs_data))
		    {
			    $as_scg_cuenta=$row["sc_cuenta"];
			    $as_spi_cuenta=$row["spi_cuenta"];				
				$lb_existe=true;
			}
		}
		if(!$lb_existe)
		{
			$this->io_msg->message("ERROR -> La cuenta ".$this->is_ctaban." del banco".$this->is_codban." no posee código contable.");
		}
		$this->io_sql->free_result($rs_data);
		return $lb_existe;
	}// end function uf_obtener_codigo_contable_colocacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_contable()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contable
		//		   Access: private
		//	    Arguments: 
		//	      Returns: lb_valido True si se encontro el movimiento ó false si no se encontro
		//	  Description: Funcion ejecuta el nucleo integrador del módulo de colocaciones banco
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 06/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$ls_sql="SELECT sc_cuenta,debhab,monto,numdoc ".
                "  FROM scb_movcol_scg ".
                " WHERE codemp='".$this->is_codemp."' ".
				"   AND codban='".$this->is_codban."' ".
				"   AND ctaban='".$this->is_ctaban."' ".
				"   AND numdoc='".$this->is_numdoc."' ".
				"   AND numcol='".$this->is_numcol."' ".
				"   AND codope='".$this->is_codope."' ".
				"   AND estcol='".$this->is_estcol."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
            $this->io_msg->message("CLASE->Integración SCBCOL MÉTODO->uf_procesar_contable ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
		    {
				$ls_scg_cuenta=$row["sc_cuenta"];
                $ls_debhab=$row["debhab"];				
				$ldec_monto=$row["monto"];				
				$ls_documento=$row["numdoc"];
				$ls_scg_cuenta=$this->io_sigesp_int_scg->uf_pad_scg_cuenta($this->dts_empresa["formcont"],$ls_scg_cuenta);
				$lb_valido=$this->io_sigesp_int->uf_scg_insert_datastore($this->is_codemp,$ls_scg_cuenta,$ls_debhab,$ldec_monto,$ls_documento,$this->is_procede,$this->is_descripcion);
				if($lb_valido===false)
				{  
					$this->io_msg->message($this->io_sigesp_int->is_msg_error);
					break;
				}
			}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
    }// end function uf_procesar_contable
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_create_movimiento_colocacion($as_codemp,$as_codban,$as_ctaban,$as_numcol,$as_numdoc,$as_codope,$as_estcol,
										     $as_estcol_new,$as_procede,$as_comprobante,$adt_fecha)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_create_movimiento_colocacion
		//		   Access: private
		//	    Arguments: as_codemp  // Código de Empresa
		//	    		   as_codban  // Código de Banco
		//				   as_ctaban  // Cuenta Bancaria
		//				   as_numcol  // Número de colocación
		//				   as_numdoc  // Número de Documento
		//				   as_codope  // Código de Operación
		//				   as_estcol  // Estatus de colocación
		//				   as_estcol_new  // Estatus de colocación nuevo
		//				   as_procede  // Procede del documento
		//				   as_comprobante  // número del comprobante
		//				   adt_fecha  // Fecha de contabilización
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Método crea un nuevo registro de banco al cambiar el estatus del mismo
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 06/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		// se verifica si existe el documento con su estatus original
		$lb_existe=$this->uf_select_movimiento_colocacion($as_codemp,$as_codban,$as_ctaban,$as_numcol,$as_numdoc,$as_codope,$as_estcol);
		if (!$lb_existe)
		{
            $this->io_msg->message("ERROR -> El movimiento no existe  Banco=".$as_codban." Cuenta=".$as_ctaban." documento=".$as_numdoc." operación=".$as_codope);		
			return false;
		}
		// se verifica si no existe el documento con su estatus nuevo
		$lb_existe=$this->uf_select_movimiento_colocacion($as_codemp,$as_codban,$as_ctaban,$as_numcol,$as_numdoc,$as_codope,$as_estcol_new);
		if ($lb_existe)
		{
            $this->io_msg->message("ERROR -> El movimiento ya existe Banco=".$as_codban." Cuenta=".$as_ctaban." documento=".$as_numdoc." operación=".$as_codope." estatus=".$as_estcol_new);		
			return false;			
		}
        // transferencia al nuevo registro de colocacion
		$ls_sql="INSERT INTO scb_movcol (codemp,codban,ctaban,numcol,numdoc,codope,estcol,fecmovcol,monmovcol,".
		        "                        tasmovcol,conmov,estcob,esttranf,estvalmon,codusu,fechaconta, fechaanula) ".
				" SELECT codemp,codban,ctaban,numcol,numdoc,codope,'".$as_estcol_new."',fecmovcol,monmovcol,".
		        "        tasmovcol,conmov,estcob,esttranf,estvalmon,codusu,fechaconta, fechaanula ".				  
				"   FROM scb_movcol ".
                "  WHERE codemp='".$as_codemp."' ".
				"    AND codban='".$as_codban."' ".
				"    AND ctaban='".$as_ctaban."' ".
				"    AND numcol='".$as_numcol."' ".
				"    AND numdoc='".$as_numdoc."' ".
				"    AND codope='".$as_codope."' ".
				"    AND estcol='".$as_estcol."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
            $this->io_msg->message("CLASE->Integración SCBCOL MÉTODO->uf_create_movimiento_colocacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		if($as_estcol_new=="A")
		{
			// transferencia al nuevo registro de banco detalle contables
			$ls_sql="INSERT INTO scb_movcol_scg (codemp, codban, ctaban, numcol ,numdoc, codope, estcol, scg_cuenta,".
					"                            debhab, codded,desmov,monto) ".
					" SELECT codemp, codban, ctaban, numcol ,numdoc, codope,'".$as_estcol_new."',scg_cuenta,".
					"        debhab, 'D',desmov,monto ".
					"   FROM scb_movcol_scg ".
					"  WHERE codemp='".$as_codemp."' ".
					"    AND codban='".$as_codban."' ".
					"    AND ctaban='".$as_ctaban."' ".
					"    AND numdoc='".$as_numdoc."' ".
					"    AND numcol='".$as_numcol."' ".
					"    AND codope='".$as_codope."' ".
					"    AND estcol='".$as_estcol."' ".
					"    AND debhab='H' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{   
				$this->io_msg->message("CLASE->Integración SCBCOL MÉTODO->uf_create_movimiento_colocacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				return false;
			}
			$ls_sql="INSERT INTO scb_movcol_scg (codemp, codban, ctaban, numcol ,numdoc, codope, estcol, scg_cuenta,".
					"                            debhab, codded,desmov,monto) ".
					" SELECT codemp, codban, ctaban, numcol ,numdoc, codope,'".$as_estcol_new."',scg_cuenta,".
					"        debhab, 'H',desmov,monto ".
					"   FROM scb_movcol_scg ".
					" WHERE codemp='".$as_codemp."' ".
					"	AND codban='".$as_codban."' ".
					"	AND ctaban='".$as_ctaban."' ".
					"   AND numdoc='".$as_numdoc."' ".
					"   AND numcol='".$as_numcol."' ".
					"   AND codope='".$as_codope."' ".
					"   AND estcol='".$as_estcol."' ".
					"   AND debhab='D' ";
		}
		else
		{
			// transferencia al nuevo registro de banco detalle contables
			$ls_sql="INSERT INTO scb_movcol_scg (codemp,codban,ctaban,numcol,numdoc,codope,estcol,sc_cuenta,".
				    "                            debhab, codded,desmov,monto) ".
					" SELECT codemp, codban, ctaban, numcol ,numdoc, codope,'".$as_estcol_new."',sc_cuenta,".
					"        debhab, codded,desmov,monto ".
					"   FROM scb_movcol_scg ".
					"  WHERE codemp='".$as_codemp."' ".
					"    AND codban='".$as_codban."' ".
					"    AND ctaban='".$as_ctaban."' ".
					"    AND numdoc='".$as_numdoc."' ".
					"    AND numcol='".$as_numcol."' ".
					"    AND codope='".$as_codope."' ".
					"    AND estcol='".$as_estcol."' ";
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
			$this->io_msg->message("CLASE->Integración SCBCOL MÉTODO->uf_create_movimiento_colocacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
        // transferencia al nuevo registro de banco detalle de gastos
		$ls_sql="INSERT INTO scb_movcol_spg (codemp,codban,ctaban,numcol,numdoc,codope,estcol,codestpro,".
		        "                             spg_cuenta,operacion,desmov,monto,estcla) ".
				" SELECT codemp,codban,ctaban,numcol,numdoc,codope,'".$as_estcol_new."',codestpro,spg_cuenta,".
				"        operacion,desmov,monto,estcla ".
				"   FROM scb_movcol_spg ".
                "  WHERE codemp='".$as_codemp."' ".
				"    AND codban='".$as_codban."' ".
				"    AND ctaban='".$as_ctaban."' ".
				"    AND numdoc='".$as_numdoc."' ".
				"    AND numcol='".$as_numcol."' ".
				"    AND codope='".$as_codope."' ".
				"    AND estcol='".$as_estcol."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
			$this->io_msg->message("CLASE->Integración SCBCOL MÉTODO->uf_create_movimiento_colocacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
        // transferencia al nuevo registro de banco detalle de ingresos
		$ls_sql="INSERT INTO scb_movcol_spi (codemp,codban,ctaban,numcol,numdoc,codope,estcol,spi_cuenta,".
		        "                             operacion,desmov,monto) ".
				" SELECT codemp,codban,ctaban,numcol,numdoc,codope,'".$as_estcol_new."',spi_cuenta,".
				"        operacion,desmov,monto ".
				"   FROM scb_movcol_spi ".
                "  WHERE codemp='".$as_codemp."' ".
				"    AND codban='".$as_codban."' ".
				"    AND ctaban='".$as_ctaban."' ".
				"    AND numdoc='".$as_numdoc."' ".
				"    AND numcol='".$as_numcol."' ".
				"    AND codope='".$as_codope."' ".
				"    AND estcol='".$as_estcol."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
			$this->io_msg->message("CLASE->Integración SCBCOL MÉTODO->uf_create_movimiento_colocacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/// PARA LA CONVERSIÓN MONETARIA
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_seguridad="";
		/*if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_scbmovcol($as_codban,$as_ctaban,$as_numdoc,$as_numcol,$as_codope,$as_estcol_new,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_scbmovcolscg($as_codban,$as_ctaban,$as_numdoc,$as_numcol,$as_codope,$as_estcol_new,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_scbmovcolspg($as_codban,$as_ctaban,$as_numdoc,$as_numcol,$as_codope,$as_estcol_new,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_fun_mis->uf_convertir_scbmovcolspi($as_codban,$as_ctaban,$as_numdoc,$as_numcol,$as_codope,$as_estcol_new,$aa_seguridad);
		}*/
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		return $lb_valido;
	}// end function uf_create_movimiento_colocacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_movimiento_colocacion($as_codemp,$as_codban,$as_ctaban,$as_numcol,$as_numdoc,$as_codope,$as_estcol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_movimiento_colocacion
		//		   Access: private
		//	    Arguments: as_codemp  // Código de Empresa
		//	    		   as_codban  // Código de Banco
		//				   as_ctaban  // Cuenta Bancaria
		//				   as_numcol  // Número de colocación
		//				   as_numdoc  // Número de Documento
		//				   as_codope  // Código de Operación
		//				   as_estcol  // Estatus de colocación
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Método verifica si existe o no el movimiento de colocacion
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 06/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe=false;		
		$ls_sql="SELECT * ".
                "  FROM scb_movcol ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numcol='".$as_numcol."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estcol='".$as_estcol."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
            $this->io_msg->message("CLASE->Integración SCBCOL MÉTODO->uf_select_movimiento_colocacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
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
	}// end function uf_select_movimiento_colocacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_movimiento_colocacion($as_codemp,$as_codban,$as_ctaban,$as_numcol,$as_numdoc,$as_codope,$as_estcol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_movimiento_colocacion
		//		   Access: private
		//	    Arguments: as_codemp  // Código de Empresa
		//	    		   as_codban  // Código de Banco
		//				   as_ctaban  // Cuenta Bancaria
		//				   as_numcol  // Número de colocación
		//				   as_numdoc  // Número de Documento
		//				   as_codope  // Código de Operación
		//				   as_estcol  // Estatus de colocación
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Método que elimina el movimiento referente a las colocaciones en las tablas hijas y madre
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 06/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM scb_movcol_spg ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numcol='".$as_numcol."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estcol='".$as_estcol."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
            $this->io_msg->message("CLASE->Integración SCBCOL MÉTODO->uf_delete_movimiento_colocacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		$ls_sql="DELETE FROM scb_movcol_spi ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numcol='".$as_numcol."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estcol='".$as_estcol."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
            $this->io_msg->message("CLASE->Integración SCBCOL MÉTODO->uf_delete_movimiento_colocacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
        // transferencia al nuevo registro de banco detalle contables
		$ls_sql="DELETE FROM scb_movcol_scg ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numcol='".$as_numcol."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estcol='".$as_estcol."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
            $this->io_msg->message("CLASE->Integración SCBCOL MÉTODO->uf_delete_movimiento_colocacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		$ls_sql="DELETE FROM scb_movcol ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numcol='".$as_numcol."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estcol='".$as_estcol."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
            $this->io_msg->message("CLASE->Integración SCBCOL MÉTODO->uf_delete_movimiento_colocacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		return $lb_valido;
	} // end function 
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_reverso_colocacion($as_codban,$as_ctaban,$as_numcol,$as_numdoc,$as_codope,$as_estcol,$adt_fecha,
											$ad_fechaconta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_reverso_colocacion
		//		   Access: public (sigesp_mis_p_reverso_scbcol.php)
		//	    Arguments: as_codban  // Código de Banco
		//				   as_ctaban  // Cuenta Bancaria
		//				   as_numcol  // Número de colocación
		//				   as_numdoc  // Número de Documento
		//				   as_codope  // Código de Operación
		//				   as_estmov  // Estatus del Movimiento
		//				   adt_fecha  // Fecha de contabilización
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Proceso de contabilizacion  un movimiento de colocación banco.
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 06/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
   	    $adt_fecha=$ad_fechaconta;
		$this->is_codemp=$this->io_codemp;
	    $this->is_codban=$as_codban;
	    $this->is_ctaban=$as_ctaban;
	    $this->is_numcol=$as_numcol;
	    $this->is_numdoc=$as_numdoc;		
	    $this->is_codope=$as_codope;
	    $this->is_estcol=$as_estcol;
	    $this->is_procede="SCBC".$as_codope;
	    $this->is_procede_doc="SCBC".$as_codope;	
	    $this->is_comprobante=$this->io_sigesp_int->uf_fill_comprobante(trim($this->is_numcol));		
        $ls_tipo_destino="-" ;
		$ls_ced_bene="----------"; 
		$ls_cod_pro="----------";	
	    $lb_valido=$this->io_sigesp_int->uf_obtener_comprobante($this->is_codemp,$this->is_procede,$this->is_comprobante,
																$adt_fecha,$as_codban,$as_ctaban,$ls_tipo_destino,
																$ls_ced_bene,$ls_cod_pro);
		if ($lb_valido===false) 
		{ 
            $this->io_msg->message("ERROR-> No se pudo obtener el comprobante.");			
			return false;
		}
		$lb_check_close=false;
		$lb_valido = $this->io_sigesp_int->uf_init_delete($this->is_codemp,$this->is_procede,$this->is_comprobante,
														  $adt_fecha,$ls_tipo_destino,$ls_ced_bene,$ls_cod_pro,$lb_check_close,
														  $as_codban,$as_ctaban);
		if($lb_valido===false )	
		{ 
 		   $this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
		   return false; 
		}
        $this->io_sigesp_int->uf_int_init_transaction_begin();	
	    if  ($lb_valido)
		{
			$lb_valido = $this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
			if ($lb_valido===false)
			{
				$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
			}		   
		}
		if ($lb_valido) 
		{ 
			$lb_valido = $this->uf_create_movimiento_colocacion($this->io_codemp,$this->is_codban,$this->is_ctaban,$this->is_numcol,
																$this->is_numdoc,$this->is_codope,$this->is_estcol,"N",$this->is_procede,
																$this->is_comprobante,$adt_fecha);
			if (!$lb_valido)
			{   
			   $this->io_msg->message("ERROR -> Crear un Nuevo Movimiento de Colocación."); 
			   $lb_valido=false;	   		   
			}
			else
			{
				$lb_valido = $this->uf_delete_movimiento_colocacion($this->io_codemp,$this->is_codban,$this->is_ctaban,$this->is_numcol,
																	$this->is_numdoc,$this->is_codope,"C");
				if (!$lb_valido)
				{   
				   $this->io_msg->message("ERROR -> Eliminar el Movimiento de Colocación."); 
				   $lb_valido=false;		   		   
				}
			}
		}
	    if($lb_valido)
	    {
			$lb_valido=$this->uf_update_fecha_contabilizado_scbcol($this->io_codemp,$as_codban,$as_ctaban,$as_numcol,$as_numdoc,
																   $as_codope,"N",'1900-01-01','1900-01-01');
		}
	    if($lb_valido)
	    {
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reverso la colocación Documento <b>".$as_numdoc."</b>, Colocación <b>".$as_numcol."</b>, Banco <b>".$as_codban."</b>, ".
							"Cuenta Banco <b>".$as_ctaban."</b>, Fecha de Contabilización <b>".$adt_fecha."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}		
		$this->io_sigesp_int->uf_sql_transaction( $lb_valido );
		return $lb_valido;
    }// end function uf_procesar_reverso_colocacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_fecha_contabilizado_scbcol($as_codemp,$as_codban,$as_ctaban,$as_numcol,$as_numdoc,$as_codope,$as_estcol,
												  $ad_fechaconta,$ad_fechaanula)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_fecha_contabilizado_scbcol
		//		   Access: private
		//	    Arguments: as_codemp  // Código de empresa
		//                 as_codban  // Código de Banco
		//                 as_ctaban  // Cuenta de Banco
		//                 as_numdoc  // Número de Documento
		//                 as_codope  // Código de Operación 
		//                 as_estcol  // Estatus del Movimiento
		//                 ad_fechaconta  // Fecha de Contabilización
		//                 ad_fechaanula  // Fecha de Anulación
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
		$ls_sql="UPDATE scb_movcol ".
		        "   SET ".$ls_campos.
				" WHERE codemp='".$as_codemp."' ".
				"	AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numcol='".$as_numcol."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estcol='".$as_estcol."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración SCB MÉTODO->uf_update_fecha_contabilizado_scbmov ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_fecha_contabilizado_scbcol
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>