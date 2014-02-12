<?php
class sigesp_scf_c_cierre
 {
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_id_process;
	var $ls_codemp;
	var $io_dscuentas;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_scf_c_cierre($as_path)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_scf_c_cierre
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/06/2007 								Fecha Última Modificación : 
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
		require_once($as_path."shared/class_folder/class_sigesp_int.php");
		require_once($as_path."shared/class_folder/class_sigesp_int_int.php");
		require_once($as_path."shared/class_folder/class_sigesp_int_spg.php");
		require_once($as_path."shared/class_folder/class_sigesp_int_scg.php");
		require_once($as_path."shared/class_folder/class_sigesp_int_spi.php");
		$this->io_intint=new class_sigesp_int_int();
		$this->io_intscg=new class_sigesp_int_scg();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_anoperiodo=substr($_SESSION["la_empresa"]["periodo"],0,4);
	}// end function sigesp_scf_c_cierre
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public 
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fecha);
		unset($this->io_intscg);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_generarcomprobantecierremensual($as_mes,$as_procede,$as_codban,$as_ctaban,$as_tipodestino,$as_codprovben,
												&$as_comprobante,&$ad_fecha,&$as_descripcion,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_generarcomprobantecierremensual
		//		   Access: public
		//		 Argument: as_mes	// Mes para el cual se quiere hacer el cierre
		//		 		   as_procede	// Procede del documento de Cierre 
		//		 		   as_codban	// Código de Banco
		//		 		   as_ctaban	// Cuenta de Banco
		//		 		   as_tipodestino	// Tipo destino
		//		 		   as_codprovben	// Código de Proveedor ó Beneficiario
		//		 		   as_comprobante	// Número de comprobante
		//		 		   ad_fecha	// Fecha del Comprobante
		//		 		   as_descripcion	// descripción del Comprobante
		//		 		   aa_seguridad	// Arreglo de Seguridasd
		//	  Description: Función que genera un comprobante de cierre dado un mes
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 29/06/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_saldofinanciera=0;
		$li_saldofiscal=0;	
		$li_saldoactivo=0;
		$li_saldopasivo=0;
		$li_saldoresultado=0;
		$this->io_sql->begin_transaction();				
		$as_comprobante="CIERREMES".$as_mes.$this->ls_anoperiodo;
		$ad_fecha=$this->io_fecha->uf_last_day($as_mes,$this->ls_anoperiodo);
		// VERIFICAMOS QUE EL COMPROBANTE NO EXISTA
		$lb_encontrado=$this->io_intscg->uf_obtener_comprobante($this->ls_codemp,$as_procede,$as_comprobante,$ad_fecha,$as_codban,
																$as_ctaban,$as_tipodestino,$as_codprovben,$as_codprovben);
		if($lb_encontrado)
		{
			$this->io_mensajes->message("El Comprobante de Cierre para el mes ".$as_mes." ya existe, no lo puede volver a procesar.");
			$lb_valido=false;
		}
		// VERIFICAMOS QUE ESTÉN DEFINIDAS LAS CUENTAS DE FINANCIERA Y FISCAL
		if($lb_valido)
		{
			$ls_cfinanciera=trim($_SESSION["la_empresa"]["c_financiera"]);
			$ls_cfiscal=trim($_SESSION["la_empresa"]["c_fiscal"]);
			if(($ls_cfinanciera=="")||($ls_cfiscal==""))
			{
				$this->io_mensajes->message("No se han definido las Cuentas Financiera y Fiscal de la Situación del Tesoro.");
				$lb_valido=false;
			}
		}
		// OBTENEMOS LOS SALDOS DE LA CUENTA FINANCIERA
		if($lb_valido)
		{ 
			$lb_valido=$this->io_intscg->uf_scg_saldo($ls_cfinanciera,&$li_saldofinanciera,$ad_fecha);
			if($lb_valido===false)
			{
				$this->io_mensajes->message("No se pudo calcular el saldo de la Ejecución Financiera.");
			}
		}
		// OBTENEMOS LOS SALDOS DE LA CUENTA FISCAL
		if($lb_valido)
		{
			$lb_valido=$this->io_intscg->uf_scg_saldo($ls_cfiscal,&$li_saldofiscal,$ad_fecha);
			if($lb_valido===false)
			{
				$this->io_mensajes->message("No se pudo calcular el saldo de la Ejecución Fiscal.");
			}
		}
		// VERIFICAMOS QUE ESTÉN DEFINIDAS LAS CUENTAS DE ACTIVO Y PASIVO
		if($lb_valido)
		{
			$ls_cactivo=trim($_SESSION["la_empresa"]["activo"]);
			$ls_cpasivo=trim($_SESSION["la_empresa"]["pasivo"]);
			if(($ls_cactivo=="")||($ls_cpasivo==""))
			{
				$this->io_mensajes->message("No se han definido las Cuentas de Activo y Pasivo.");
				$lb_valido=false;
			}
		}
		// OBTENEMOS LOS SALDOS DE LOS ACTIVOS
		if($lb_valido)
		{    
			$lb_valido=$this->uf_load_saldo_cuentas($ls_cactivo,$ad_fecha,&$li_saldoactivo);			
			if($lb_valido===false)
			{
				$this->io_mensajes->message("No se pudo calcular el saldo de los Activos.");
			}
		}
		// OBTENEMOS LOS SALDOS DE LOS PASIVOS
		if($lb_valido)
		{
			$lb_valido=$this->uf_load_saldo_cuentas($ls_cpasivo,$ad_fecha,&$li_saldopasivo);
			if($lb_valido===false)
			{
				$this->io_mensajes->message("No se pudo calcular el saldo de los Pasivos.");
			}
		}
		// OBTENEMOS EL RESULTADO DEL EJERCICIO
		if($lb_valido)
		{
			$li_saldoresultado=$li_saldoactivo+$li_saldopasivo;
			if($li_saldoresultado==0)
			{
				$this->io_mensajes->message("No hay información para este mes.");
				$lb_valido=false;
			}
		}
		// CREAMOS LA CABECERA DEL COMPROBANTE
		if($lb_valido)
		{
			$as_descripcion="CIERRE MENSUAL AL ".$ad_fecha;
			$li_tipo_comp=1;
			$lb_valido = $this->io_intint->uf_int_init($this->ls_codemp,$as_procede,$as_comprobante,$ad_fecha,$as_descripcion,
													   $as_tipodestino,$as_codprovben,true,$as_codban,$as_ctaban,$li_tipo_comp);
			if(!$lb_valido)
			{   
				$this->io_mensajes->message($this->io_intint->is_msg_error); 
			}
		
		}
		// CREAMOS EL ASIENTO DEL AJUSTE DEL TESORO
		if($lb_valido)
		{
			if($li_saldofinanciera<>0)
			{
				$ls_descripcion="AJUSTES DEL RESULTADO DEL TESORO AL ".$ad_fecha;
				$ls_debhab="D";
				if($li_saldofinanciera>=0)
				{
					$ls_debhab="H";
				}
				$lb_valido=$this->io_intint->uf_scg_insert_datastore($this->ls_codemp,$ls_cfinanciera,$ls_debhab,abs($li_saldofinanciera),
																	 $as_comprobante,$as_procede,$ls_descripcion);
				if($lb_valido===false)
				{  
					$this->io_mensajes->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
					break;
				}
				if($lb_valido)
				{
					$ls_debhab="D";
					if($li_saldofiscal>=0)
					{
						$ls_debhab="H";
					}
					$lb_valido=$this->io_intint->uf_scg_insert_datastore($this->ls_codemp,$ls_cfiscal,$ls_debhab,abs($li_saldofiscal),
																		 $as_comprobante,$as_procede,$ls_descripcion);
					if($lb_valido===false)
					{  
						$this->io_mensajes->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
						break;
					}
				}
			}
		}
		// CREAMOS EL ASIENTO DE SITUACIÓN DEL TESORO
		if($lb_valido)
		{
			$ls_descripcion="SITUACIÓN DEL TESORO AL ".$ad_fecha;
			$ls_debhab="H";
			if($li_saldoresultado>=0)
			{
				$ls_debhab="D";
			}
			$lb_valido=$this->io_intint->uf_scg_insert_datastore($this->ls_codemp,$ls_cfiscal,$ls_debhab,abs($li_saldoresultado),
																 $as_comprobante,$as_procede,$ls_descripcion);			
			if($lb_valido===false)
			{  
				$this->io_mensajes->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);			
				break;
			}
			if($lb_valido)
			{
				$ls_debhab="D";
				if($li_saldoresultado>=0)
				{
					$ls_debhab="H";
				}
				$lb_valido=$this->io_intint->uf_scg_insert_datastore($this->ls_codemp,$ls_cfinanciera,$ls_debhab,abs($li_saldoresultado),
																	 $as_comprobante,$as_procede,$ls_descripcion);
				if($lb_valido===false)
				{  
					$this->io_mensajes->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
					break;
				}
			}
		}
		// GUARDAMOS EL COMPROBANTE
	    if($lb_valido)
	    {
	        $lb_valido=$this->io_intint->uf_init_end_transaccion_integracion($aa_seguridad); 
	        if($lb_valido===false)
		    {
				$this->io_mensajes->message("ERROR-> No se puede guardar el comprobante");//.$this->io_sigesp_int->is_msg_error);
		    }		   
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Generó el Comprobante Contable de cierre ".$as_comprobante." Procede ".$as_procede." Fecha ".$ad_fecha.
							 " Beneficiario ".$as_cedbene." Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
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
			$ld_fecha=$this->io_funciones->uf_convertirdatetobd($ad_fecha);
			$lb_valido=$this->uf_convertir_sigespcmp($as_procede,$as_comprobante,$ld_fecha,$as_codban,$as_ctaban,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_convertir_scgdtcmpcierre($as_procede,$as_comprobante,$ld_fecha,$as_codban,$as_ctaban,$aa_seguridad);
		}*/
	   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		if($lb_valido)
		{	
			$this->io_mensajes->message("El Comprobante Contable de Cierre fue registrado.");
			$this->io_sql->commit();
		}
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("Ocurrio un Error al Registrar el Comprobante Contable de cierre."); 
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_generarcomprobantecierremensual
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_generarcomprobantecierremensual_metodo2($as_mes,$as_procede,$as_codban,$as_ctaban,$as_tipodestino,$as_codprovben,
														&$as_comprobante,&$ad_fecha,&$as_descripcion,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_generarcomprobantecierremensual_metodo2
		//		   Access: public
		//		 Argument: as_mes	// Mes para el cual se quiere hacer el cierre
		//		 		   as_procede	// Procede del documento de Cierre 
		//		 		   as_codban	// Código de Banco
		//		 		   as_ctaban	// Cuenta de Banco
		//		 		   as_tipodestino	// Tipo destino
		//		 		   as_codprovben	// Código de Proveedor ó Beneficiario
		//		 		   as_comprobante	// Número de comprobante
		//		 		   ad_fecha	// Fecha del Comprobante
		//		 		   as_descripcion	// descripción del Comprobante
		//		 		   aa_seguridad	// Arreglo de Seguridasd
		//	  Description: Función que genera un comprobante de cierre dado un mes
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 29/06/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_saldofinanciera=0;
		$li_saldofiscal=0;	
		$li_saldoactivo=0;
		$li_saldopasivo=0;
		$li_saldoresultado=0;
		$this->io_sql->begin_transaction();				
		$as_comprobante="CIERREMES".$as_mes.$this->ls_anoperiodo;
		$ad_fecha=$this->io_fecha->uf_last_day($as_mes,$this->ls_anoperiodo);
		// VERIFICAMOS QUE EL COMPROBANTE NO EXISTA
		$lb_encontrado=$this->io_intscg->uf_obtener_comprobante($this->ls_codemp,$as_procede,$as_comprobante,$ad_fecha,$as_codban,
																$as_ctaban,$as_tipodestino,$as_codprovben,$as_codprovben);
		if($lb_encontrado)
		{
			$this->io_mensajes->message("El Comprobante de Cierre para el mes ".$as_mes." ya existe, no lo puede volver a procesar.");
			$lb_valido=false;
		}
		// VERIFICAMOS QUE ESTÉN DEFINIDAS LAS CUENTAS DE FINANCIERA Y FISCAL
		if($lb_valido)
		{
			$ls_cfinanciera=trim($_SESSION["la_empresa"]["c_financiera"]);
			$ls_cfiscal=trim($_SESSION["la_empresa"]["c_fiscal"]);
			if(($ls_cfinanciera=="")||($ls_cfiscal==""))
			{
				$this->io_mensajes->message("No se han definido las Cuentas Financiera y Fiscal de la Situación del Tesoro.");
				$lb_valido=false;
			}
		}
		// OBTENEMOS LOS SALDOS DE LA CUENTA FINANCIERA
		if($lb_valido)
		{
			$lb_valido=$this->io_intscg->uf_scg_saldo($ls_cfinanciera,&$li_saldofinanciera,$ad_fecha);
			if($lb_valido===false)
			{
				$this->io_mensajes->message("No se pudo calcular el saldo de la Ejecución Financiera.");
			}
		}
		// OBTENEMOS LOS SALDOS DE LA CUENTA FISCAL
		if($lb_valido)
		{
			$lb_valido=$this->io_intscg->uf_scg_saldo($ls_cfiscal,&$li_saldofiscal,$ad_fecha);
			if($lb_valido===false)
			{
				$this->io_mensajes->message("No se pudo calcular el saldo de la Ejecución Fiscal.");
			}
		}
		// VERIFICAMOS QUE ESTÉN DEFINIDAS LAS CUENTAS DE ACTIVO Y PASIVO
		if($lb_valido)
		{
			$ls_cactivo=trim($_SESSION["la_empresa"]["activo"]);
			$ls_cpasivo=trim($_SESSION["la_empresa"]["pasivo"]);
			if(($ls_cactivo=="")||($ls_cpasivo==""))
			{
				$this->io_mensajes->message("No se han definido las Cuentas de Activo y Pasivo.");
				$lb_valido=false;
			}
		}
		// OBTENEMOS LOS SALDOS DE LOS ACTIVOS
		if($lb_valido)
		{
			// Cuentas de los activos
			//110200000000,111000000000,112000000000,112200000000,112600000000,112800000000,113200000000,113003000000
			$li_saldo=0;
			$lb_valido=$this->uf_load_saldo_cuentas("1102",&$li_saldo,$ad_fecha);
			if($lb_valido===false)
			{
				$this->io_mensajes->message("No se pudo calcular el saldo de los Activos.");
			}
			if($lb_valido)
			{
				$li_saldoactivo=$li_saldoactivo+$li_saldo;
				$li_saldo=0;
				$lb_valido=$this->uf_load_saldo_cuentas("111",&$li_saldo,$ad_fecha);
				if($lb_valido===false)
				{
					$this->io_mensajes->message("No se pudo calcular el saldo de los Activos.");
				}
			}
			if($lb_valido)
			{
				$li_saldoactivo=$li_saldoactivo+$li_saldo;
				$li_saldo=0;
				$lb_valido=$this->uf_load_saldo_cuentas("112",&$li_saldo,$ad_fecha);
				if($lb_valido===false)
				{
					$this->io_mensajes->message("No se pudo calcular el saldo de los Activos.");
				}
			}
			if($lb_valido)
			{
				$li_saldoactivo=$li_saldoactivo+$li_saldo;
				$li_saldo=0;
				$lb_valido=$this->uf_load_saldo_cuentas("1122",&$li_saldo,$ad_fecha);
				if($lb_valido===false)
				{
					$this->io_mensajes->message("No se pudo calcular el saldo de los Activos.");
				}
			}
			if($lb_valido)
			{
				$li_saldoactivo=$li_saldoactivo+$li_saldo;
				$li_saldo=0;
				$lb_valido=$this->uf_load_saldo_cuentas("1126",&$li_saldo,$ad_fecha);
				if($lb_valido===false)
				{
					$this->io_mensajes->message("No se pudo calcular el saldo de los Activos.");
				}
			}
			if($lb_valido)
			{
				$li_saldoactivo=$li_saldoactivo+$li_saldo;
				$li_saldo=0;
				$lb_valido=$this->uf_load_saldo_cuentas("1128",&$li_saldo,$ad_fecha);
				if($lb_valido===false)
				{
					$this->io_mensajes->message("No se pudo calcular el saldo de los Activos.");
				}
			}
			if($lb_valido)
			{
				$li_saldoactivo=$li_saldoactivo+$li_saldo;
				$li_saldo=0;
				$lb_valido=$this->uf_load_saldo_cuentas("1132",&$li_saldo,$ad_fecha);
				if($lb_valido===false)
				{
					$this->io_mensajes->message("No se pudo calcular el saldo de los Activos.");
				}
			}
			if($lb_valido)
			{
				$li_saldoactivo=$li_saldoactivo+$li_saldo;
				$li_saldo=0;
				$lb_valido=$this->uf_load_saldo_cuentas("113003",&$li_saldo,$ad_fecha);
				if($lb_valido===false)
				{
					$this->io_mensajes->message("No se pudo calcular el saldo de los Activos.");
				}
			}
			if($lb_valido)
			{
				$li_saldoactivo=$li_saldoactivo+$li_saldo;
			}
		}
		// OBTENEMOS LOS SALDOS DE LOS PASIVOS
		if($lb_valido)
		{
			// Cuentas de los pasivos
			//210100000000,213300000000,219901000000
			$li_saldo=0;
			$lb_valido=$this->uf_load_saldo_cuentas("2101",&$li_saldo,$ad_fecha);
			if($lb_valido===false)
			{
				$this->io_mensajes->message("No se pudo calcular el saldo de los Pasivos.");
			}
			if($lb_valido)
			{
				$li_saldopasivo=$li_saldopasivo+$li_saldo;
				$li_saldo=0;
				$lb_valido=$this->uf_load_saldo_cuentas("2133",&$li_saldo,$ad_fecha);
				if($lb_valido===false)
				{
					$this->io_mensajes->message("No se pudo calcular el saldo de los Pasivos.");
				}
			}
			if($lb_valido)
			{
				$li_saldopasivo=$li_saldopasivo+$li_saldo;
				$li_saldo=0;
				$lb_valido=$this->uf_load_saldo_cuentas("219901",&$li_saldo,$ad_fecha);
				if($lb_valido===false)
				{
					$this->io_mensajes->message("No se pudo calcular el saldo de los Pasivos.");
				}
			}
		}
		// OBTENEMOS EL RESULTADO DEL EJERCICIO
		if($lb_valido)
		{
			$li_saldoresultado=$li_saldoactivo+$li_saldopasivo;
			if($li_saldoresultado==0)
			{
				$this->io_mensajes->message("No hay información para este mes.");
				$lb_valido=false;
			}
		}
		// CREAMOS LA CABECERA DEL COMPROBANTE
		if($lb_valido)
		{
			$as_descripcion="CIERRE MENSUAL AL ".$ad_fecha;
			$li_tipo_comp=1;
			$lb_valido = $this->io_intint->uf_int_init($this->ls_codemp,$as_procede,$as_comprobante,$ad_fecha,$as_descripcion,
													   $as_tipodestino,$as_codprovben,true,$as_codban,$as_ctaban,$li_tipo_comp);
			if(!$lb_valido)
			{   
				$this->io_mensajes->message($this->io_intint->is_msg_error); 
			}
		
		}
		// CREAMOS EL ASIENTO DEL AJUSTE DEL TESORO
		if($lb_valido)
		{
			if($li_saldofinanciera<>0)
			{
				$ls_descripcion="AJUSTES DEL RESULTADO DEL TESORO AL ".$ad_fecha;
				$ls_debhab="D";
				if($li_saldofinanciera>=0)
				{
					$ls_debhab="H";
				}
				$lb_valido=$this->io_intint->uf_scg_insert_datastore($this->is_codemp,$ls_cfinanciera,$ls_debhab,abs($li_saldofinanciera),
																	 $as_comprobante,$as_procede,$ls_descripcion);
				if($lb_valido===false)
				{  
					$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
					break;
				}
				if($lb_valido)
				{
					$ls_debhab="D";
					if($li_saldofiscal>=0)
					{
						$ls_debhab="H";
					}
					$lb_valido=$this->io_intint->uf_scg_insert_datastore($this->is_codemp,$ls_cfiscal,$ls_debhab,abs($li_saldofiscal),
																		 $as_comprobante,$as_procede,$ls_descripcion);
					if($lb_valido===false)
					{  
						$this->io_mensajes->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
						break;
					}
				}
			}
		}
		// CREAMOS EL ASIENTO DE SITUACIÓN DEL TESORO
		if($lb_valido)
		{
			$ls_descripcion="SITUACIÓN DEL TESORO AL ".$ad_fecha;
			$ls_debhab="H";
			if($li_saldoresultado>=0)
			{
				$ls_debhab="D";
			}
			$lb_valido=$this->io_intint->uf_scg_insert_datastore($this->is_codemp,$ls_cfiscal,$ls_debhab,abs($li_saldoresultado),
																 $as_comprobante,$as_procede,$ls_descripcion);
			if($lb_valido===false)
			{  
				$this->io_mensajes->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
				break;
			}
			if($lb_valido)
			{
				$ls_debhab="D";
				if($li_saldoresultado>=0)
				{
					$ls_debhab="H";
				}
				$lb_valido=$this->io_intint->uf_scg_insert_datastore($this->is_codemp,$ls_cfinanciera,$ls_debhab,abs($li_saldoresultado),
																	 $as_comprobante,$as_procede,$ls_descripcion);
				if($lb_valido===false)
				{  
					$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
					break;
				}
			}
		}
		// GUARDAMOS EL COMPROBANTE
	    if($lb_valido)
	    {
	        $lb_valido=$this->io_intint->uf_init_end_transaccion_integracion($aa_seguridad); 
	        if($lb_valido===false)
		    {
				$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
		    }		   
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Generó el Comprobante Contable de cierre ".$as_comprobante." Procede ".$as_procede." Fecha ".$ad_fecha.
							 " Beneficiario ".$as_cedbene." Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/// PARA LA CONVERSIÓN MONETARIA
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($lb_valido)
		{
			$ld_fecha=$this->io_funciones->uf_convertirdatetobd($ad_fecha);
			$lb_valido=$this->uf_convertir_sigespcmp($as_procede,$as_comprobante,$ld_fecha,$as_codban,$as_ctaban,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_convertir_scgdtcmpcierre($as_procede,$as_comprobante,$ld_fecha,$as_codban,$as_ctaban,$aa_seguridad);
		}
	   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		if($lb_valido)
		{	
			$this->io_mensajes->message("El Comprobante Contable de Cierre fue registrado.");
			$this->io_sql->commit();
		}
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("Ocurrio un Error al Registrar el Comprobante Contable de cierre."); 
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_generarcomprobantecierremensual_metodo2
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_generarcomprobantecierreanual($as_procede,$as_codban,$as_ctaban,$as_tipodestino,$as_codprovben,
											  &$as_comprobante,&$ad_fecha,&$as_descripcion,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_generarcomprobantecierreanual
		//		   Access: public
		//		 Argument: as_procede	// Procede del documento de Cierre 
		//		 		   as_codban	// Código de Banco
		//		 		   as_ctaban	// Cuenta de Banco
		//		 		   as_tipodestino	// Tipo destino
		//		 		   as_codprovben	// Código de Proveedor ó Beneficiario
		//		 		   as_comprobante	// Número de comprobante
		//		 		   ad_fecha	// Fecha del Comprobante
		//		 		   as_descripcion	// Descripción del Comprobante
		//		 		   aa_seguridad	// Arreglo de Seguridasd
		//	  Description: Función que genera un comprobante de cierre Anual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/07/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_saldoresultado=0;
		$li_saldogasto=0;
		$li_saldoingreso=0;
		$li_diferencia=0;
		$this->io_sql->begin_transaction();				
		$as_comprobante=$this->io_intscg->uf_fill_comprobante("CIERRE-".$this->ls_anoperiodo);
		$ad_fecha=$this->io_fecha->uf_last_day("12",$this->ls_anoperiodo);
		// VERIFICAMOS QUE EL COMPROBANTE NO EXISTA
		$lb_encontrado=$this->io_intscg->uf_obtener_comprobante($this->ls_codemp,$as_procede,$as_comprobante,$ad_fecha,$as_codban,
																$as_ctaban,$as_tipodestino,$as_codprovben,$as_codprovben);
		if($lb_encontrado)
		{
			$this->io_mensajes->message("El Comprobante de Cierre del Ejercicio ".$this->ls_anoperiodo." ya existe, no lo puede volver a procesar.");
			$lb_valido=false;
		}
		// VERIFICAMOS QUE ESTÉN DEFINIDAS LAS CUENTAS DE RESULTADOS Y RESULTADOS ANTERIOR
		if($lb_valido)
		{
			$ls_cresultado=trim($_SESSION["la_empresa"]["c_resultad"]);
			$ls_cresultadoanterior=trim($_SESSION["la_empresa"]["c_resultan"]);
			if(($ls_cresultado=="")||($ls_cresultadoanterior==""))
			{
				$this->io_mensajes->message("No se han definido las Cuentas de Resultado y Resultado Anterior.");
				$lb_valido=false;
			}
		}
		// OBTENEMOS LOS SALDOS DE LA CUENTA DE RESULTADOS
		if($lb_valido)
		{
			$lb_valido=$this->io_intscg->uf_scg_saldo($ls_cresultado,&$li_saldoresultado,$ad_fecha);
			if($lb_valido===false)
			{
				$this->io_mensajes->message("No se pudo calcular el saldo del Resultado.");
			}
		}
		// VERIFICAMOS QUE ESTÉN DEFINIDAS LAS CUENTAS DE GASTOS E INGRESOS
		if($lb_valido)
		{
			$ls_cgasto=trim($_SESSION["la_empresa"]["gasto"]);
			$ls_cingreso=trim($_SESSION["la_empresa"]["ingreso"]);
			if(($ls_cgasto=="")||($ls_cingreso==""))
			{
				$this->io_mensajes->message("No se han definido las Cuentas de Gasto e Ingreso.");
				$lb_valido=false;
			}
		}
		// CREAMOS LA CABECERA DEL COMPROBANTE
		if($lb_valido)
		{
			$as_descripcion="CIERRE DEL EJERCICIO";
			$li_tipo_comp=1;
			$lb_valido = $this->io_intint->uf_int_init($this->ls_codemp,$as_procede,$as_comprobante,$ad_fecha,$as_descripcion,
													   $as_tipodestino,$as_codprovben,true,$as_codban,$as_ctaban,$li_tipo_comp);
			if(!$lb_valido)
			{   
				$this->io_mensajes->message($this->io_intint->is_msg_error); 
			}
		
		}
		// CREAMOS TRASLADO DE RESULTADOS
		if($lb_valido)
		{
			if($li_saldoresultado<>0)
			{
				$ls_descripcion="TRASLADO DE RESULTADOS";
				$ls_documento="1";
				$ls_debhab="D";
				if($li_saldoresultado>0)
				{
					$ls_debhab="H";
				}
				$lb_valido=$this->io_intint->uf_scg_insert_datastore($this->is_codemp,$ls_cresultado,$ls_debhab,abs($li_saldoresultado),
																	 $ls_documento,$as_procede,$ls_descripcion);
				if($lb_valido===false)
				{  
					$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
				}
				if($lb_valido)
				{
					$ls_descripcion="TRASLADO DE RESULTADOS ANTERIORES";
					$ls_debhab="H";
					if($li_saldoresultado>0)
					{
						$ls_debhab="D";
					}
					$lb_valido=$this->io_intint->uf_scg_insert_datastore($this->is_codemp,$ls_cresultadoanterior,$ls_debhab,
																		 abs($li_saldoresultado),$ls_documento,$as_procede,
																		 $ls_descripcion);
					if($lb_valido===false)
					{  
						$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
					}
				}
			}
		}
		// CIERRE DE LAS CUENTAS DE GASTO
		if($lb_valido)
		{
			$ls_sql="SELECT sc_cuenta ".
					"  FROM scg_cuentas ".
					" WHERE status = 'C' ".
					"   AND sc_cuenta LIKE '%".$ls_cgasto."%' ";	
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Cierre MÉTODO->uf_generarcomprobantecierreanual ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$lb_valido=false;
			}
			else
			{
				while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
				{
					$ls_cuenta=$row["sc_cuenta"];
					$li_saldo=0;
					$lb_valido=$this->io_intscg->uf_scg_saldo($ls_cuenta,&$li_saldo,$ad_fecha);					
					if($lb_valido===false)
					{
						$this->io_mensajes->message("No se pudo calcular el saldo para la cuenta ".$ls_cuenta.".");
					}
					else
					{
						$li_saldogasto=$li_saldogasto+$li_saldo;
						if($li_saldo<>0)
						{
							$ls_documento="2";
							$ls_descripcion="CIERRE DEL EJERCICIO AÑO ".$this->ls_anoperiodo;
							$ls_debhab="D";
							if($li_saldo>0)
							{
								$ls_debhab="H";
							}
							$lb_valido=$this->io_intint->uf_scg_insert_datastore($this->is_codemp,$ls_cuenta,$ls_debhab,abs($li_saldo),
																				 $ls_documento,$as_procede,$ls_descripcion);
							if($lb_valido===false)
							{  
								$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
							}
						}
					}
				}
				$this->io_sql->free_result($rs_data);	
			}
			if($lb_valido)
			{
				if($li_saldogasto<>0)
				{
					$ls_descripcion="CIERRE DEL EJERCICIO AÑO ".$this->ls_anoperiodo;
					$ls_documento="2";
					$ls_debhab="H";
					if($li_saldoresultado>0)
					{
						$ls_debhab="D";
					}
					$lb_valido=$this->io_intint->uf_scg_insert_datastore($this->is_codemp,$ls_cresultado,$ls_debhab,abs($li_saldogasto),
																		 $ls_documento,$as_procede,$ls_descripcion);
					if($lb_valido===false)
					{  
						$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
					}
				}
			}
		}
		// CIERRE DE LAS CUENTAS DE INGRESO
		if($lb_valido)
		{
			$ls_sql="SELECT sc_cuenta ".
					"  FROM scg_cuentas ".
					" WHERE status = 'C' ".
					"   AND sc_cuenta LIKE '%".$ls_cingreso."%' ";	
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Cierre MÉTODO->uf_generarcomprobantecierreanual ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$lb_valido=false;
			}
			else
			{
				while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
				{
					$ls_cuenta=$row["sc_cuenta"];
					$li_saldo=0;
					$lb_valido=$this->io_intscg->uf_scg_saldo($ls_cuenta,&$li_saldo,$ad_fecha);
					if($lb_valido===false)
					{
						$this->io_mensajes->message("No se pudo calcular el saldo para la cuenta ".$ls_cuenta.".");
					}
					else
					{
						$li_saldoingreso=$li_saldoingreso+$li_saldo;
						if($li_saldo<>0)
						{
							$ls_documento="3";
							$ls_descripcion="CIERRE DEL EJERCICIO AÑO ".$this->ls_anoperiodo;
							$ls_debhab="D";
							if($li_saldo>0)
							{
								$ls_debhab="H";
							}
							$lb_valido=$this->io_intint->uf_scg_insert_datastore($this->is_codemp,$ls_cuenta,$ls_debhab,abs($li_saldo),
																				 $ls_documento,$as_procede,$ls_descripcion);
							if($lb_valido===false)
							{  
								$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
							}
						}
					}
				}
				$this->io_sql->free_result($rs_data);	
			}
			if($lb_valido)
			{
				if($li_saldoingreso<>0)
				{
					$ls_documento="3";
					$ls_descripcion="CIERRE DEL EJERCICIO AÑO ".$this->ls_anoperiodo;
					$ls_debhab="H";
					if($li_saldoingreso>0)
					{
						$ls_debhab="D";
					}
					$lb_valido=$this->io_intint->uf_scg_insert_datastore($this->is_codemp,$ls_cresultado,$ls_debhab,abs($li_saldoingreso),
																		 $ls_documento,$as_procede,$ls_descripcion);
					if($lb_valido===false)
					{  
						$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
					}
				}
			}
		}
		// CREAMOS EL ASIENTO DE LA DIFERENCIA ENTRE INGRESO Y GASTO
		if($lb_valido)
		{
			$li_diferencia=$li_saldoingreso-$li_saldogasto;
			if($li_diferencia<>0)
			{
				$ls_documento="4";
				$ls_formcont=$_SESSION["la_empresa"]["formcont"];
				$ls_cuenta=$this->io_intint->uf_pad_scg_cuenta($ls_formcont,"310000000000");
				$ls_descripcion="CIERRE DEL EJERCICIO AÑO ".$this->ls_anoperiodo;
				$ls_debhab="H";
				if($li_diferencia>0)
				{
					$ls_debhab="D";
				}
				$lb_valido=$this->io_intint->uf_scg_insert_datastore($this->is_codemp,$ls_cuenta,$ls_debhab,abs($li_diferencia),
																	 $ls_documento,$as_procede,$ls_descripcion);
				if($lb_valido===false)
				{  
					$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
				}
			}
		}
		// GUARDAMOS EL COMPROBANTE
	    if($lb_valido)
	    {
	        $lb_valido=$this->io_intint->uf_init_end_transaccion_integracion($aa_seguridad); 
	        if($lb_valido===false)
		    {
				$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
		    }		   
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Generó el Comprobante Contable de Cierre del Ejercicio ".$as_comprobante." Procede ".$as_procede." Fecha ".$ad_fecha.
							 " Beneficiario ".$as_cedbene." Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/// PARA LA CONVERSIÓN MONETARIA
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($lb_valido)
		{
			$ld_fecha=$this->io_funciones->uf_convertirdatetobd($ad_fecha);
			$lb_valido=$this->uf_convertir_sigespcmp($as_procede,$as_comprobante,$ld_fecha,$as_codban,$as_ctaban,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_convertir_scgdtcmpcierre($as_procede,$as_comprobante,$ld_fecha,$as_codban,$as_ctaban,$aa_seguridad);
		}
	   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		if($lb_valido)
		{	
			$this->io_mensajes->message("El Comprobante Contable de Cierre del Ejercicio fue registrado.");
			$this->io_sql->commit();
		}
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("Ocurrio un Error al Registrar el Comprobante Contable de Cierre del Ejercicio."); 
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_generarcomprobantecierreanual
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_saldo_cuentas($as_cuenta,$ad_fecha,&$ai_saldo)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_saldo_cuentas
		//		   Access: public
		//		 Argument: as_cuenta // Cuenta por la cual se quiere buscar el saldo
		//				   ad_fecha // Fecha hasta donde se va a calcular el saldo
		//				   ai_saldo // Saldo de todas las cuentas
		//	  Description: Función que busca en la tabla de cuentas los saldos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/06/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_saldo=0;
		$ls_sql="SELECT sc_cuenta ".
				"  FROM scg_cuentas ".
				" WHERE status = 'C' ".
				"   AND sc_cuenta LIKE '".$as_cuenta."%' ";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Cierre MÉTODO->uf_load_saldo_cuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_cuenta=$row["sc_cuenta"];
				$li_saldo=0;
				$lb_valido=$this->io_intscg->uf_scg_saldo($ls_cuenta,&$li_saldo,$ad_fecha);
				if($lb_valido===false)
				{  
					$this->io_mensajes->message("No se pudo calcular el saldo para la cuenta ".$ls_cuenta.".");
				}
				else
				{
					$ai_saldo=$ai_saldo+$li_saldo;
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_load_saldo_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_detallesscg($as_comprobante,$ad_fecha,$as_procede,$as_codpro,$as_cedbene,$as_codban,$as_ctaban,$ai_tipcom,
								   $aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_detallesscg
		//		   Access: public
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   ad_fecha  // Fecha del comprobante
		//				   as_procede  // Procede del comprobante
		//				   as_codpro  // Código proveedor 
		//				   as_cedbene  // Código beneficiario
		//				   as_codban  // código de banco
		//				   as_ctaban  // cuenta de banco
		//				   ai_tipcom  // Tipo de Comprobante
		//				   ai_totrowscg  // total de filas de Contabilidad
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	  Description: Función que busca los detalles de un comprobante y los elimina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/07/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fecha=$this->io_funciones->uf_convertirdatetobd($ad_fecha);
		$ls_sql="SELECT sc_cuenta, procede_doc, documento, debhab, monto ".
				"  FROM scg_dt_cmp ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"	AND procede = '".$as_procede."' ".
				"	AND comprobante = '".$as_comprobante."' ".
				"	AND fecha = '".$ad_fecha."' ".
				"	AND codban = '".$as_codban."' ".
				"	AND ctaban = '".$as_ctaban."' ".
				" ORDER BY orden ";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Cierre MÉTODO->uf_delete_detallesscg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido) )
			{
				$ls_cuenta=$row["sc_cuenta"];
				$ls_procededoc=$row["procede_doc"];
				$ls_documento=$row["documento"];
				$ls_debhab=$row["debhab"];
				$li_monto=$row["monto"];
				$lb_valido=$this->io_intscg->uf_scg_procesar_delete_movimiento($this->ls_codemp,$as_procede,$as_comprobante,$ad_fecha,
																			   $ls_cuenta,$ls_procededoc,$ls_documento,$ls_debhab,
																			   $li_monto,$as_codban,$as_ctaban);
				if($lb_valido)
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="DELETE";
					$ls_descripcion="Elimino la cuenta ".$ls_cuenta." a el Comprobante Contable de Cierre ".$as_comprobante." Procede ".$as_procede.
									" Fecha ".$ad_fecha." Beneficiario ".$as_cedbene." Proveedor ".$as_codpro.
									" Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_delete_detallesscg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete($as_comprobante,$ad_fecha,$as_procede,$as_codprovben,$as_tipodestino,$as_codban,$as_ctaban,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_scf_p_cierre.php)
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   ad_fecha  // Fecha del comprobante
		//				   as_procede  // Procede del comprobante
		//				   as_codprovben  // Código proveedor / beneficiario
		//				   as_tipodestino  // Tipo de Destino
		//				   as_codban  // código de banco
		//				   as_ctaban  // cuenta de banco
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el eliminar ó False si hubo error en el eliminar
		//	  Description: Funcion que elimina el comprobante
		//	   Creado Por: Ing. Yesenia Moreno 
		// Fecha Creación: 03/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();				
		$this->io_intscg->is_codemp=$this->ls_codemp;
		$this->io_intscg->is_procedencia=$as_procede;
		$this->io_intscg->is_comprobante=$as_comprobante;
		$this->io_intscg->id_fecha=$ad_fecha;
		$this->io_intscg->as_codban=$as_codban;
		$this->io_intscg->as_ctaban=$as_ctaban;
		$ls_codpro="----------";
		$ls_cedbene="----------";
		$li_tipcom=1;
		switch($as_tipodestino)
		{
			case "P":
				 $ls_codpro=$as_codprovben;
				 break;
			case "B":
				 $ls_cedbene=$as_codprovben;
				 break;
		}
		if($lb_valido)
		{	// Eliminamos todos los detalles que tiene el comprobante
			$lb_valido=$this->uf_delete_detallesscg($as_comprobante,$ad_fecha,$as_procede,$ls_codpro,$ls_cedbene,$as_codban,
													$as_ctaban,$li_tipcom,$aa_seguridad);
		}					
		if($lb_valido)
		{		
			$lb_valido=$this->io_intscg->uf_sigesp_delete_comprobante();
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Elimino el Comprobante Contable de Cierre ".$as_comprobante." Procede ".$as_procede." Fecha ".$ad_fecha.
							 " Beneficiario ".$ls_cedbene." Proveedor ".$ls_codpro." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		if($lb_valido)
		{	
			$this->io_mensajes->message("El Comprobante Contable de Cierre fue eliminado.");
			$this->io_sql->commit();
		}
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("Ocurrio un Error al Eliminar el Comprobante Contable de Cierre."); 
			$this->io_sql->rollback();
		}

		return $lb_valido;
	}// end function uf_delete
	//-----------------------------------------------------------------------------------------------------------------------------------

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/// PARA LA CONVERSIÓN MONETARIA
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_sigespcmp($as_procede,$as_comprobante,$ad_fecha,$as_codban,$as_ctaban,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sigespcmp
		//		   Access: private
		//	    Arguments: as_procede  // procede del comprobante
		//				   as_comprobante  //  número del comprobante
		//				   ad_fecha  // fecha del comprobante
		//				   as_codban  //  código de banco del comprobante
		//				   as_ctaban  //  cuenta del banco del comprobante
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 13/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();
		require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
		$this->io_rcbsf= new sigesp_c_reconvertir_monedabsf();
		$this->io_rcbsf= new sigesp_c_reconvertir_monedabsf();
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
		$ls_sql="SELECT total ".
				"  FROM sigesp_cmp ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   AND procede = '".$as_procede."'".
				"   AND comprobante = '".$as_comprobante."'".
				"   AND fecha = '".$ad_fecha."'".
				"   AND codban = '".$as_codban."'".
				"   AND ctaban = '".$as_ctaban."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->uf_convertir_sigespcmp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$li_total=$row["total"];      
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","totalaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_total);
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_procede);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","comprobante");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_comprobante);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecha");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ad_fecha);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sigesp_cmp",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			}
		}
		unset($this->io_rcbsf);
		unset($this->io_sql);
		unset($this->io_mensajes);
		unset($this->io_funciones);
		return $lb_valido;
	}// end function uf_convertir_sigespcmp
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scgdtcmpcierre($as_procede,$as_comprobante,$ad_fecha,$as_codban,$as_ctaban,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sigespcmp
		//		   Access: private
		//	    Arguments: as_procede  // procede del comprobante
		//				   as_comprobante  //  número del comprobante
		//				   ad_fecha  // fecha del comprobante
		//				   as_codban  //  código de banco del comprobante
		//				   as_ctaban  //  cuenta del banco del comprobante
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();
		require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
		$this->io_rcbsf= new sigesp_c_reconvertir_monedabsf();
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
		$ls_sql="SELECT sc_cuenta, procede_doc, documento, debhab, monto ".
				"  FROM scg_dt_cmp ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   AND procede = '".$as_procede."'".
				"   AND comprobante = '".$as_comprobante."'".
				"   AND fecha = '".$ad_fecha."'".
				"   AND codban = '".$as_codban."'".
				"   AND ctaban = '".$as_ctaban."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->uf_convertir_scgdtcmpcierre ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_sc_cuenta= $row["sc_cuenta"];
				$ls_procede_doc= $row["procede_doc"];
				$ls_documento= $row["documento"];
				$ls_debhab= $row["debhab"];
				$li_monto= $row["monto"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto);
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_procede);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","comprobante");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_comprobante);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecha");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ad_fecha);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","sc_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_sc_cuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procede_doc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","debhab");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_debhab);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scg_dt_cmp",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			}
		}
		unset($this->io_rcbsf);
		unset($this->io_sql);
		unset($this->io_mensajes);
		unset($this->io_funciones);
		return $lb_valido;
	}// end function uf_convertir_scgdtcmpcierre
	//-----------------------------------------------------------------------------------------------------------------------------------	

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>