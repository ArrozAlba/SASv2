<?php 
ini_set('precision','15');
 ////////////////////////////////////////////////////////////////////////////////////////////////////
 //           CLASS:  class_sigesp_int_scg
 //	         Access:  public
 //     Programador:  Ing. Nelson Barraez  e Ing. Wilmer Briceño
 //     Description:  Esta clase teiene como objeto el manejo de todos los métodos relacionados a los 
 //                   contables del sistema sigesp, es utilizados para el manejo de cuentas y comprobantes
 //                   financieros. Este útiliza métodos en lineas y tambien estan asociados a niveles de clases
 //                   superiores que manejan información contable en lote.
 //Clases Asociadas : class_sigesp_int,class_sigesp_int_int,class_sigesp_int_spg,class_sigesp_int_spg
 ////////////////////////////////////////////////////////////////////////////////////////////////////
 class class_sigesp_int_scg extends class_sigesp_int
 {
    var $io_function;
	var $io_fecha;
	var $is_fecha;
	var $lds_cuentas;
	var $lds_detalle_cmp;
	var $lds_cmp_cierre;
	var $lds_cmp_cierre_del;
	var $ls_status="";
	var $io_msg;
	var $dat_emp;//Datos empresa.

	//-----------------------------------------------------------------------------------------------------------------------------------
	function class_sigesp_int_scg()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: class_sigesp_int_scg
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 	    $this->io_function=new class_funciones();	
		$this->lds_cuentas=new class_datastore();
		$this->lds_detalle_cmp=new class_datastore();
		$this->lds_cmp_cierre=new class_datastore();
		$this->lds_cmp_cierre_del=new class_datastore();
		$this->io_fecha=new class_fecha();
		$this->io_msg=new class_mensajes();
		$this->dat_emp=$_SESSION["la_empresa"];
		$this->ls_dabatase_target=$_SESSION["ls_data_des"];		
		$this->io_include=new sigesp_include();
		//$this->io_connect=$this->io_include->uf_conectar($this->ls_dabatase_target);
			$this->io_connect=$this->io_include->uf_conectar_otra_bd($_SESSION['sigesp_servidor_apr'],$_SESSION['sigesp_usuario_apr'],
									   $_SESSION['sigesp_clave_apr'], $_SESSION['sigesp_basedatos_apr'], 
									   $_SESSION['sigesp_gestor_apr']);
		$this->io_sql=new class_sql($this->io_connect);
	} // end function class_sigesp_int_scg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_procesar_delete_movimiento($as_codemp,$as_procede,$as_comprobante,$as_fecha,$as_cuenta,$as_procede_doc,
											   $as_documento,$as_operacion,$adec_monto,$as_codban,$as_ctaban)
    {		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_procesar_delete_movimiento
		//		   Access: public 
		//       Argument: as_codemp // Código de empresa
		//       		   as_procede // Procedencia del documento
		//       		   as_comprobante // Número de Comprobante
		//       		   as_fecha // Fecha del Comprobante
		//       		   as_cuenta // cuenta
		//       		   as_procede_doc // Procede del movimiento
		//       		   as_documento // Número del Documento
		//       		   as_operacion // Operación si es debe ó haber
		//       		   adec_monto // Monto del Movimiento
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta de Banco
		//	  Description: Este método elimina un movimiento contable (Método Principal MAIN )
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ldec_monto_movimiento=0;
		$li_orden=0;
		$this->is_codemp=$as_codemp;
		$this->id_fecha=$this->io_function->uf_convertirdatetobd($as_fecha);
		$this->is_procedencia=$as_procede;
		$this->is_comprobante=$as_comprobante;
		$this->as_codban=$as_codban;
		$this->as_ctaban=$as_ctaban;
        if($this->uf_scg_select_movimiento($as_cuenta,$as_procede_doc,$as_documento,$as_operacion,&$ldec_monto_movimiento,
										   &$li_orden))
		{
			$lb_valido = $this->uf_scg_delete_movimiento($as_codemp,$as_procede,$as_comprobante,$as_fecha,$as_cuenta,
														 $as_procede_doc,$as_documento,$as_operacion,$as_codban,$as_ctaban);
			if($lb_valido)  
			{ 
				$lb_valido=$this->uf_scg_procesar_saldos_contables($as_cuenta,$as_operacion,$ldec_monto_movimiento,0); 
			}
		}
		else
		{
			$this->is_msg_error="ERROR-> El movimiento no existe ";
		}
		return $lb_valido;
	} // end function uf_scg_procesar_delete_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_select_movimiento($as_cuenta,$as_procede_doc,$as_documento,$as_debhab,&$adec_monto,&$ai_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_select_movimiento
		//		   Access: public 
		//       Argument: as_cuenta // cuenta
		//       		   as_procede_doc // Procede del movimiento
		//       		   as_documento // Número del Documento
		//       		   as_debhab // Operación si es debe ó haber
		//       		   adec_monto // Monto del Movimiento
		//       		   ai_orden // Orden al Insertar los registros
		//	  Description: Este método verifica si existe o no el movimiento contable
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe=false;
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
	    $ls_sql="SELECT monto, orden ".
		        "  FROM scg_dt_cmp ".
		        " WHERE codemp='".$this->is_codemp."' ".
				"   AND procede='".$this->is_procedencia."' ".
				"   AND comprobante='".$this->is_comprobante."' ".
				"   AND fecha='".$ld_fecha."' ".
				"   AND codban='".$this->as_codban."' ".
				"   AND ctaban='".$this->as_ctaban."' ".
				"   AND procede_doc='".$as_procede_doc."' ".
				"   AND documento ='".$as_documento."' ".
				"   AND sc_cuenta='".$as_cuenta."' ".
				"   AND debhab='".$as_debhab."'";
		$rs_mov=$this->io_sql->select($ls_sql);
		if($rs_mov===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_scg MÉTODO->uf_scg_select_movimiento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_mov))
			{
				$lb_existe=true;
				$adec_monto=$row["monto"];
				$ai_orden=$row["orden"];
			}
			else
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_mov);	
		}
		return $lb_existe;
	} // end function uf_scg_select_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_delete_movimiento($as_codemp,$as_procede,$as_comprobante,$as_fecha,$as_cuenta,$as_procede_doc,$as_documento,
									  $as_operacion,$as_codban,$as_ctaban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_delete_movimiento
		//		   Access: public 
		//       Argument: as_codemp // Código de empresa
		//       		   as_procede // Procedencia del documento
		//       		   as_comprobante // Número de Comprobante
		//       		   as_fecha // Fecha del Comprobante
		//       		   as_cuenta // cuenta
		//       		   as_procede_doc // Procede del movimiento
		//       		   as_documento // Número del Documento
		//       		   as_operacion // Operación si es debe ó haber
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta de Banco
		//	  Description: Este método elimina el movimineto contable
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_fecha=$this->io_function->uf_convertirdatetobd($as_fecha);
		$ls_sql="DELETE FROM scg_dt_cmp ".
				" WHERE codemp='".$as_codemp."' ".
				"   AND procede='".$as_procede."' ".
				"   AND comprobante='".$as_comprobante ."' ".
				"   AND fecha= '".$ls_fecha."' ".
				"   AND codban= '".$as_codban."' ".
				"   AND ctaban= '".$as_ctaban."' ".
				"   AND sc_cuenta= '".$as_cuenta."' ".
				"   AND procede_doc='".$as_procede_doc."' ".
				"   AND documento ='".$as_documento."' ".
				"   AND debhab='".$as_operacion."'";
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$this->is_msg_error = "CLASE->sigesp_int_scg MÉTODO->uf_scg_delete_movimiento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
	    return $lb_valido;
	} // end function uf_scg_delete_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_procesar_insert_movimiento($as_codemp,$as_procede, $as_comprobante, $as_fecha,
                                     	      $as_tipo_destino,$as_cod_prov, $as_ced_bene, $as_cuenta,
										      $as_procede_doc, $as_documento,$as_debhab,$as_descripcion,
										      $adec_monto_anterior, $adec_monto_actual,$as_codban,$as_ctaban)
    {											  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_procesar_insert_movimiento
		//		   Access: public 
		//       Argument: as_codemp // Código de empresa
		//       		   as_procede // Procedencia del documento
		//       		   as_comprobante // Número de Comprobante
		//       		   as_fecha // Fecha del Comprobante
		//       		   as_tipo_destino // Tipo de destino de contabilización proveedor o beneficiario
		//       		   as_cod_prov // Código del Proveedor
		//       		   as_ced_bene // Cédula del Beneficiario
		//       		   as_cuenta // cuenta
		//       		   as_procede_doc // Procede del movimiento
		//       		   as_documento // Número del Documento
		//       		   as_debhab // Operación si es debe ó haber
		//       		   as_descripcion // Descripción del Documento
		//       		   adec_monto_anterior // Monto Anterior del Movimiento
		//       		   adec_monto_actual // Monto Actual del Movimiento
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta de Banco
		//	  Description: Este método registra un movimiento contable (Método Principal MAIN )
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_desproc="";	
		$li_orden=0;
		$this->is_codemp=$as_codemp;
		$this->is_procedencia=$as_procede;
		$this->is_comprobante=$as_comprobante;
		$this->id_fecha=$as_fecha;
		$this->is_cod_prov=$as_cod_prov;
		$this->is_ced_ben=$as_ced_bene;
		$this->is_tipo=$as_tipo_destino;
		$this->as_codban=$as_codban;
		$this->as_ctaban=$as_ctaban;
		if(!($this->uf_valida_procedencia($as_procede,$ls_desproc)))
		{
			return false;
		}	 
		if($this->uf_scg_select_movimiento($as_cuenta,$as_procede_doc,$as_documento,$as_debhab,&$adec_monto_actual,&$li_orden)) 
		{
		   $this->is_msg_error="ERROR-> El movimiento contable ya existe";
		   return false; 	
		}
		$lb_valido=$this->uf_scg_insert_movimiento($as_cuenta,$as_procede_doc,$as_documento,$as_debhab,$as_descripcion,$adec_monto_actual);
		if($lb_valido) 
		{ 
			$lb_valido=$this->uf_scg_procesar_saldos_contables($as_cuenta,$as_debhab,$adec_monto_anterior,$adec_monto_actual);
		}
		return $lb_valido;
	} //end function uf_scg_procesar_insert_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_procesar_update_movimiento($as_codemp,$as_procede, $as_comprobante, $as_fecha,
                                     	       $as_tipo_destino,$as_cod_prov, $as_ced_bene, $as_cuenta,
										       $as_procede_doc, $as_documento,$as_debhab,$as_descripcion,
										       $adec_monto_anterior, $adec_monto_actual,$as_codban,$as_ctaban)
    {											  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_procesar_insert_movimiento
		//		   Access: public 
		//       Argument: as_codemp // Código de empresa
		//       		   as_procede // Procedencia del documento
		//       		   as_comprobante // Número de Comprobante
		//       		   as_fecha // Fecha del Comprobante
		//       		   as_tipo_destino // Tipo de destino de contabilización proveedor o beneficiario
		//       		   as_cod_prov // Código del Proveedor
		//       		   as_ced_bene // Cédula del Beneficiario
		//       		   as_cuenta // cuenta
		//       		   as_procede_doc // Procede del movimiento
		//       		   as_documento // Número del Documento
		//       		   as_debhab // Operación si es debe ó haber
		//       		   as_descripcion // Descripción del Documento
		//       		   adec_monto_anterior // Monto Anterior del Movimiento
		//       		   adec_monto_actual // Monto Actual del Movimiento
		//	  Description: Este método registra un movimiento contable (Método Principal MAIN )
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_desproc="";	
		$li_orden=0;
		$this->is_codemp=$as_codemp;
		$this->is_procedencia=$as_procede;
		$this->is_comprobante=$as_comprobante;
		$this->id_fecha=$as_fecha;
		$this->is_cod_prov=$as_cod_prov;
		$this->is_ced_ben=$as_ced_bene;
		$this->is_tipo=$as_tipo_destino;
		$this->as_codban=$as_codban;
		$this->as_ctaban=$as_ctaban;
		if(!($this->uf_valida_procedencia($as_procede,$ls_desproc)))
		{
			return false;
		}
		$lb_valido = $this->uf_scg_delete_movimiento($as_codemp,$as_procede,$as_comprobante,$as_fecha,$as_cuenta,$as_procede_doc,$as_documento,$as_debhab,$as_codban,$as_ctaban);
		if($lb_valido)  
		{ 
			$lb_valido=$this->uf_scg_procesar_saldos_contables($as_cuenta,$as_debhab,$adec_monto_anterior,0); 
		}	 		
		$lb_valido=$this->uf_scg_insert_movimiento($as_cuenta,$as_procede_doc,$as_documento,$as_debhab,$as_descripcion,$adec_monto_actual);
		if($lb_valido) 
		{ 
			$lb_valido = $this->uf_scg_procesar_saldos_contables($as_cuenta,$as_debhab,0,$adec_monto_actual);
		}
		return $lb_valido;
	} //end function uf_scg_procesar_insert_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_insert_movimiento($as_cuenta,$as_procede_doc,$as_documento,$as_debhab,$as_descripcion,$adec_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_procesar_insert_movimiento
		//		   Access: public 
		//       Argument: as_cuenta // Cuenta Contable
		//       		   as_procede_doc // Procedencia del documento
		//       		   as_documento // Número del Documento
		//       		   as_debhab // Tipo de Operación si es Debe ó Haber
		//       		   as_descripcion // Descripción del Documento
		//       		   adec_monto // Monto del movimiento
		//	  Description: Este método registra un movimiento final contable enla tabla movimiento  (DEPENDE DEL PROCESAR)
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_orden=$this->uf_scg_obtener_orden_movimiento();
		$ls_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql="INSERT INTO scg_dt_cmp (codemp,procede,comprobante,fecha,sc_cuenta,procede_doc,documento,debhab,descripcion,".
				"						 monto,orden,codban,ctaban) " . 
				" VALUES ('".$this->is_codemp."','".$this->is_procedencia."','".$this->is_comprobante."','" .$ls_fecha."',".
				"'".$as_cuenta."', '".$as_procede_doc."','".$as_documento."','".$as_debhab."','".$as_descripcion."',".
				"".$adec_monto.",".$li_orden.",'".$this->as_codban."','".$this->as_ctaban."')" ;
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			if($this->io_sql->errno==1452)
			{   
				$this->is_msg_error="CLASE->sigesp_int_scg MÉTODO->uf_scg_insert_movimiento ERROR->Fallo alguna clave foranea";
			}
			else
			{
				$this->is_msg_error="CLASE->sigesp_int_scg MÉTODO->uf_scg_insert_movimiento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			}
		   	$lb_valido=false;
		}
		return $lb_valido;
	} // end function uf_scg_insert_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_obtener_orden_movimiento()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_obtener_orden_movimiento
		//		   Access: public 
		//       Argument: 
		//	  Description: Este método genera un numero de orden secuencial de los movimiento 
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_orden=0;
		$ls_sql="SELECT count(*) as orden " .
				"  FROM scg_dt_cmp " .
				" WHERE codemp='".$this->is_codemp."' ".
				"   AND procede= '". $this->is_procedencia ."' ".
				"   AND comprobante= '".$this->is_comprobante."' ".
				"   AND fecha='".$this->id_fecha."'".
				"   AND codban='".$this->as_codban."'".
				"   AND ctaban='".$this->as_ctaban."'";
		$rs_saldos=$this->io_sql->select($ls_sql);
		if($rs_saldos===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_scg MÉTODO->uf_scg_obtener_orden_movimiento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_saldos))
			{
				$li_orden=$row["orden"];
			}
		}		 
		return $li_orden;
	} // end function uf_scg_obtener_orden_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_pad_cuenta_plan($as_formplan,$as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_obtener_orden_movimiento
		//		   Access: public 
		//      Arguments: as_formplan  // formato de la estructura del plan contable
		//                 as_cuenta // cuenta contable
		//	  Description: Este método rellena con ceros a la derecha la cuenta contable
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_MaxNivel=0;
		$li_longitud=0;
		$li_len_cadena=0;
		$ls_Cadena="";
		$ls_formato="";
		$ls_formatoaux="";
		$ls_formato=$as_formplan;
		$ls_formatoaux=str_replace("-", " ", $ls_formato );
		$ls_formatoaux=$this->io_function->uf_trim($ls_formatoaux);
		$li_longitud=strlen($ls_formatoaux);
		$ls_Cadena=$this->io_function->uf_trim($as_cuenta);
		$li_len_cadena=strlen($ls_Cadena);
		$ls_Cadena=substr($ls_Cadena,0,$li_longitud);
		$ls_Cadena=$this->io_function->uf_rellenar_der($ls_Cadena,'0',$li_longitud);
		$as_formplan=$ls_formatoaux;
		return $ls_Cadena;
	} // end function uf_pad_cuenta_plan
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_pad_scg_cuenta($as_formcont,$as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_pad_scg_cuenta
		//		   Access: public 
		//      Arguments: as_formcont  // formato de la estructura del plan contable
		//                 as_cuenta // cuenta contable
		//	  Description: Este método rellena con ceros a la derecha la cuenta contable
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_MaxNivel=0;
		$li_longitud=0;
		$li_len_cadena=0;
		$ls_cadena="";
		$ls_formato="";
		$ls_formatoaux="";
		$ls_formato=trim($as_formcont);
		$ls_formatoaux=str_replace( "-", " ",$ls_formato);
		$ls_formatoaux=$this->io_function->uf_trim($ls_formatoaux);
		$li_longitud=strlen($ls_formatoaux);
		$ls_cadena=$this->io_function->uf_trim($as_cuenta);
		$li_len_cadena=strlen($ls_cadena);
		$ls_cadena=substr($ls_cadena,0,$li_longitud);
		$ls_cadena=$this->io_function->uf_cerosderecha($ls_cadena,$li_longitud);
		return $ls_cadena;
	} // end function uf_pad_scg_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_next_cuenta_nivel($as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_next_cuenta_nivel
		//		   Access: public 
		//      Arguments: as_cuenta // cuenta contable
		//	  Description: Este método obtiene el siguiente nivel de la cuenta
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $this->uf_init_niveles();
		$li_MaxNivel=0;
		$li_nivel=0;
		$li_anterior=0;
		$li_longitud=0;
		$li_long=0;
		$ls_cadena="";
		$ls_fill="";
		$li_MaxNivel=count($this->ia_niveles_scg);
		$li_nivel=$this->uf_scg_obtener_nivel($as_cuenta);
		if($li_nivel>1)
		{
			$li_anterior=$this->ia_niveles_scg[ $li_nivel - 1 ]; 	
			$ls_cadena=substr($as_cuenta,0, $li_anterior+1);
			$li_longitud=strlen($ls_cadena);
			$li_long=(($this->ia_niveles_scg[$li_MaxNivel]+1) - $li_longitud);
			$ls_newcadena=$this->io_function->uf_cerosderecha(trim($ls_cadena),$li_long+$li_longitud);
			$ls_cadena=$ls_newcadena;
		}
		return $ls_cadena;
	} // end function uf_scg_next_cuenta_nivel	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_obtener_nivel($as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_obtener_nivel
		//		   Access: public 
		//      Arguments: as_cuenta // cuenta contable
		//	  Description: Este método retorna un valor numerico de la cuenta segun el formato
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->uf_init_niveles();
		$li_nivel=0;
		$li_anterior=0;
		$li_longitud=0;
		$ls_cadena="";
		$li_nivel=count($this->ia_niveles_scg);
		do
		{		
			$li_anterior = $this->ia_niveles_scg[ $li_nivel - 1 ]  + 1;
			$li_longitud = $this->ia_niveles_scg[ $li_nivel ] - $this->ia_niveles_scg[ $li_nivel - 1 ];
			$ls_cadena 	= substr( trim($as_cuenta),$li_anterior ,$li_longitud);
			$li=$ls_cadena;
			if($li>0)
			{
				return $li_nivel;
			}
			$li_nivel = $li_nivel - 1;
		} while( $li_nivel > 1);	
		return $li_nivel;
	} // end function uf_scg_obtener_nivel
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_sin_ceros($as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_sin_ceros
		//		   Access: public 
		//      Arguments: as_cuenta // cuenta contable
		//	  Description: Este método retorna la cuenta sin ceros a la derecha
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->uf_init_niveles();
		$li_nivel=0;
		$li_anterior=0;
		$ls_cadena="";
		$li_nivel=$this->uf_scg_obtener_nivel($as_cuenta);
		$li_anterior=$this->ia_niveles_scg[$li_nivel];
		$ls_cadena=substr($as_cuenta,0,$li_anterior+1);	
		return $ls_cadena;
	} // end function uf_scg_sin_ceros
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_select_cuenta($as_codemp,$as_sc_cuenta,&$as_status,&$as_denominacion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_select_cuenta
		//		   Access: public 
		//      Arguments: as_codemp // Código de Empresa
		//      		   as_sc_cuenta // cuenta contable
		//      		   as_status // Estatus de la Cuenta
		//      		   as_denominacion // Denominación de la Cuenta
		//	  Description: Este método verifica si existe o no la cuenta contable y ademas retorna la denominacion 
		//                 y estatus de la cuenta
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_status="";
		$as_denominacion="";
		$lb_existe=false;
		$ls_sql="SELECT sc_cuenta, status, denominacion ".
				"  FROM scg_cuentas ".
				" WHERE codemp='".$as_codemp."' ".
				"   AND sc_cuenta='".$as_sc_cuenta."'";
		//echo $ls_sql;		
		$rs_cuentas=$this->io_sql->select($ls_sql);
		if ($rs_cuentas===false)
		{
			$this->is_msg_error = "CLASE->sigesp_int_scg MÉTODO->uf_scg_select_cuenta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_cuentas))
			{
				 $as_sc_cuenta=$row["sc_cuenta"];
				 $as_denominacion=$row["denominacion"];
				 $as_status=$row["status"];
				 $lb_existe=true;
			}
			else
			{
				$this->is_msg_error = "ERROR-> La cuenta Contable ".$as_sc_cuenta." no existe";
			}
			$this->io_sql->free_result($rs_cuentas);	
		}	
		//var_dump($lb_existe);	
		return $lb_existe;
	}  // end function uf_scg_select_cuenta()
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_select_cuenta_sin_cero($as_codemp,$as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_select_cuenta_sin_cero
		//		   Access: public 
		//      Arguments: as_codemp // Código de Empresa
		//      		   as_cuenta // cuenta contable
		//	  Description: Este método realiza una consulta para verificar si existe una cantidad mayor de el nivel de la 
		//                 estructura de cuenta para y así validar la cuenta inferior
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_total=0;
		$lb_existe=false;
		$as_cuenta=trim($as_cuenta)."%";
		$ls_sql="SELECT COUNT(sc_cuenta) As ntotal ".
			 	"  FROM scg_cuentas ".
				" WHERE codemp='".$as_codemp."' ".
				"   AND sc_cuenta LIKE  '".$as_cuenta ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->is_msg_error = "CLASE->sigesp_int_scg MÉTODO->uf_scg_select_cuenta_sin_cero ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))  
			{
				$li_total=$row["ntotal"];
			}
			else
			{
				$li_total=2;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $li_total;
	}// end function uf_scg_select_cuenta_sin_cero
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_select_cuenta_movimiento($as_codemp,$as_sc_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_select_cuenta_movimiento
		//		   Access: public 
		//      Arguments: as_codemp // Código de Empresa
		//      		   as_sc_cuenta // cuenta contable
		//	  Description: Este método que verifica si una cuenta contable posee un movimiento
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_sql="SELECT sc_cuenta ".
				"  FROM scg_dt_cmp ".
			   	" WHERE codemp='".$as_codemp."' ".
				"   AND sc_cuenta='".$as_sc_cuenta ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->is_msg_error = "CLASE->sigesp_int_scg MÉTODO->uf_scg_select_cuenta_movimiento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
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
	}// end function uf_scg_select_cuenta_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_plan_unico($as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_plan_unico
		//		   Access: public 
		//      Arguments: as_cuenta // cuenta contable
		//	  Description: Método que verifica si existe o no la cuenta
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=false;
		$ls_sql="SELECT * ".
				"  FROM sigesp_plan_unico ".
				" WHERE sc_cuenta='".$as_cuenta."'";	
		$rs_plan=$this->io_sql->select($ls_sql);
		if($rs_plan===false)
		{
		   $lb_valido=false;
		   $this->is_msg_error="CLASE->sigesp_int_scg MÉTODO->uf_select_plan_unico ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_plan))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_plan);
		}	
		return $lb_valido;
	} // end function uf_select_plan_unico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_valida_cuenta($as_codemp,$as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_valida_cuenta
		//		   Access: public 
		//      Arguments: as_codemp // Código de Empresa
		//      		   as_cuenta // cuenta contable
		//	  Description: Método que verifica si existe o no la cuenta
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_sql="SELECT sc_cuenta ".
				"  FROM scg_cuentas ".
				" WHERE codemp='".$as_codemp."' ".
				"   AND sc_cuenta='".$as_cuenta."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_existe=false; 
			$this->is_msg_error="CLASE->sigesp_int_scg MÉTODO->uf_scg_valida_cuenta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
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
	} // end function uf_scg_valida_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_procesar_saldos_contables($as_cuenta,$as_debhab,$adec_monto_anterior,$adec_monto_actual)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_procesar_saldos_contables
		//		   Access: public 
		//      Arguments: as_cuenta // Cuenta Contable
		//      		   as_debhab // Operación si es de Debe ó Haber
		//      		   adec_monto_anterior // Monto Anterior del movimiento
		//      		   adec_monto_actual // Monto Actual del Movimiento
		//	  Description: Este método actualiza los saldos de cada una de las cuentas asociada por nivel.
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_Disponible=0; 
		$lb_valido=true; 
		$lb_procesado=false;
		$ls_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_nextCuenta=$as_cuenta;
		$li_nivel=$this->uf_scg_obtener_nivel( $ls_nextCuenta );
		do 
		{
			if ($this->uf_scg_select_saldo($ls_nextCuenta,$ls_fecha)) 
			{
				$lb_valido=($this->uf_scg_update_saldo($ls_nextCuenta,$ls_fecha,$adec_monto_anterior,$adec_monto_actual,$as_debhab)); 
			}		
			else
			{
				$lb_valido=($this->uf_scg_insert_saldo($ls_nextCuenta,$ls_fecha,$adec_monto_actual,$as_debhab)); 
			}
			if
			($this->uf_scg_obtener_nivel($ls_nextCuenta)==0)
			{
				break;
			}
			$ls_nextCuenta=$this->uf_scg_next_cuenta_nivel($ls_nextCuenta);
			if($ls_nextCuenta!="")
			{
				$li_nivel=($this->uf_scg_obtener_nivel($ls_nextCuenta));
			}
		}while(($li_nivel>=1)&&($lb_valido)&&($ls_nextCuenta!=""));
		return $lb_valido;
	} // end function uf_scg_procesar_saldos_contables
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_select_saldo($as_cuenta,$as_fecha)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_select_saldo
		//		   Access: public 
		//      Arguments: as_cuenta // Cuenta Contable
		//      		   as_fecha // Fecha del movimiento
		//	  Description: Este método indica si existe o no el saldo de la cuenta a una fecha específica.
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_fecha=$this->io_function->uf_convertirdatetobd($as_fecha);	   
		$ls_sql="SELECT sc_cuenta ".
				"  FROM scg_saldos ".
				" WHERE codemp='".$this->is_codemp."' ".
				"   AND sc_cuenta='".$as_cuenta."' ".
				"   AND fecsal='".$ls_fecha."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			 $this->is_msg_error = "CLASE->sigesp_int_scg MÉTODO->uf_scg_select_saldo ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			 $lb_existe = false;		
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
	} // end function uf_scg_select_saldo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_update_saldo($as_sc_cuenta,$as_fecha,$adec_monto_anterior,$adec_monto_actual,$as_debhab)
    {	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_update_saldo
		//		   Access: public 
		//      Arguments: as_sc_cuenta // Cuenta Contable
		//      		   as_fecha // Fecha del movimiento
		//      		   adec_monto_anterior // Monto Anterior del movimiento
		//      		   adec_monto_actual // Monto Actual del movimiento
		//      		   as_debhab // Operación si es debe ó haber
		//	  Description: Actualiza la información del saldo de la cuenta correspondiente.
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_fecha=$this->io_function->uf_convertirdatetobd($as_fecha); 
		$ldec_monto=$adec_monto_actual - $adec_monto_anterior;
		if($as_debhab=="D")
		{
			$ls_str = " SET debe_mes = debe_mes +".$ldec_monto;
		}
		else
		{
			$ls_str=" SET haber_mes = haber_mes + ".$ldec_monto;
		}
		$ls_sql="UPDATE scg_saldos ".$ls_str.
				" WHERE codemp='".$this->is_codemp."' ".
				"   AND sc_cuenta= '".$as_sc_cuenta."' ".
				"   AND fecsal= '".$ls_fecha."'" ;
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->is_msg_error = "CLASE->sigesp_int_scg MÉTODO->uf_scg_select_saldo ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido = false;		
		}				
		return $lb_valido;
	} // end function uf_scg_update_saldo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_insert_saldo($as_cuenta,$as_fecha,$adec_monto_actual,$as_debhab)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_insert_saldo
		//		   Access: public 
		//      Arguments: as_cuenta // Cuenta Contable
		//      		   as_fecha // Fecha del movimiento
		//      		   adec_monto_actual // Monto Actual del movimiento
		//      		   as_debhab // Operación si es debe ó haber
		//	  Description: inserta la información del saldo de la cuenta correspondiente.
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->uf_init_niveles();
		$ls_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		if($as_debhab=="D")
		{	 
			$ls_sql="INSERT INTO scg_saldos (codemp,sc_cuenta,fecsal,debe_mes,haber_mes) " .
					" VALUES ('".$this->is_codemp."','".$as_cuenta."','".$ls_fecha."',".$adec_monto_actual.",0)";
		}	
		else
		{
			$ls_sql="INSERT INTO scg_saldos (codemp, sc_cuenta, fecsal, debe_mes, haber_mes )".
					" VALUES ('".$this->is_codemp."','".$as_cuenta."',' ".$ls_fecha."',0,".$adec_monto_actual.")";
		}
		$li_exec=$this->io_sql->execute($ls_sql);
		if($li_exec===false)
		{
			$this->is_msg_error = "CLASE->sigesp_int_scg MÉTODO->uf_scg_insert_saldo ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		return $lb_valido;
	} // end function uf_scg_insert_saldo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_plan_unico_cuenta($as_sc_cuenta,$as_denominacion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_plan_unico_cuenta
		//		   Access: public 
		//      Arguments: as_sc_cuenta // Cuenta Contable
		//      		   as_denominacion // Denominación de la cuenta contable
		//	  Description: verifica si existe la cuenta en la tabla del paln unico contable
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_sql="SELECT sc_cuenta,denominacion".
				"  FROM sigesp_plan_unico ".
			    " WHERE sc_cuenta='". $as_sc_cuenta ."'"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error = "CLASE->sigesp_int_scg MÉTODO->uf_select_plan_unico_cuenta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
				$as_denominacion=$row["denominacion"];
			}
			$this->io_sql->free_result($rs_data);		
		}	 
		return $lb_existe;
	} // end function uf_select_plan_unico_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_plan_unico_cuenta_recurso($as_sc_cuenta,$as_denominacion)
    {	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_plan_unico_cuenta_recurso
		//		   Access: public 
		//      Arguments: as_sc_cuenta // Cuenta Contable
		//      		   as_denominacion // Denominación de la cuenta contable
		//	  Description: verifica si existe la cuenta en la tabla del paln unico de recursos
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_sql="SELECT sig_cuenta,denominacion ".
				"  FROM sigesp_plan_unico_re ".
				" WHERE sig_cuenta='". $as_sc_cuenta ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_scg MÉTODO->uf_select_plan_unico_cuenta_recurso ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
				$as_denominacion=$row["denominacion"];
			}
			$this->io_sql->free_result($rs_data);
		}	 
		return $lb_existe;
	} // end function uf_select_plan_unico_cuenta_recurso
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function  uf_scg_insert_cuenta($as_codemp,$as_cuenta,$as_denominacion,$as_status,$ai_nivel,$as_cuenta_ref,$as_mensaje)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_insert_cuenta
		//		   Access: public 
		//      Arguments: as_codemp // Código de Empresa
		//      		   as_cuenta // Cuenta Contable
		//      		   as_denominacion // Denominación de la cuenta contable
		//      		   as_status // Estatus de la cuenta
		//      		   ai_nivel // Nivel de la Cuenta
		//      		   as_cuenta_ref // Cuenta de Referencia
		//      		   as_mensaje //
		//	  Description: inserta una cuenta contable en el plan de cuentas
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "INSERT INTO scg_cuentas ( codemp,sc_cuenta,denominacion,status,asignado,distribuir,".
								  "  enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre, ".
								  "  octubre,noviembre,diciembre,nivel,referencia ) ".
					" VALUES('".$as_codemp."','".$as_cuenta."','".$as_denominacion."','".$as_status."',".strval(0)." ,".
					"".strval(1).",".strval(0).",".strval(0).",".strval(0).",".strval(0).",".strval(0).",".strval(0).",".
					"".strval(0).",".strval(0).",".strval(0).",".strval(0).",".strval(0).",".strval(0).",".$ai_nivel.",".
					"'".$as_cuenta_ref."')" ;
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$this->is_msg_error = "CLASE->sigesp_int_scg MÉTODO->uf_scg_insert_cuenta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		return $lb_valido;
	 } // end function uf_scg_insert_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_cargar_datastore_plan_cuentas($ads_cuentas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargar_datastore_plan_cuentas
		//		   Access: public 
		//      Arguments: ads_cuentas // Datastored de cuentas
		//	  Description: inserta la información del saldo de la cuenta correspondiente.
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_fila=0;
		$i=0;
		$lb_valido=true;
		$ls_sc_cuentas="";
		$ls_denominacion="";
		$ls_status="";
		$ls_cadena="";
		$ls_sql="SELECT sc_cuenta,denominacion,status ". 	
				"  FROM scg_Cuentas " .
				" WHERE codemp='".$this->dat_emp["codemp"]."'".
				" ORDER BY sc_cuenta ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->is_msg_error="CLASE->sigesp_int_scg MÉTODO->uf_cargar_datastore_plan_cuentas ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			$ads_cuentas=new class_datastore();
			while($row=$this->io_sql->fetch_row($rs_data)) 
			{
				$i=$i+1;
				$ls_sc_cuentas=trim($row["sc_cuenta"]);
				$ads_cuentas->insertRow("sc_cuenta",$ls_sc_cuentas);
				$ls_denominacion=trim($row["denominacion"]);
				$ads_cuentas->insertRow("denominacion",$ls_denominacion);
				$ls_status=trim($row["status"]);
				$ads_cuentas->insertRow("status",$ls_status);
			} //fin del while
		}
		$this->io_sql->free_result($rs_data);						
		return $lb_valido;
	} // end function uf_cargar_datastore_plan_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_cargar_comprobante_cierre($as_codemp,$as_procede,$as_comprobante,$as_fecha)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargar_comprobante_cierre
		//		   Access: public 
		//      Arguments: as_codemp // Código de Empresa
		//      		   as_procede // Procede del Comprobante
		//      		   as_comprobante // Número de Comprobante
		//      		   as_fecha // Fecha del Comprobante
		//	  Description: Método que carga la información de cuentas del plan unico de cuentas mediante un cursor de datos.
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ldec_monto_debe=0;
		$ldec_monto_haber=0;
		$ldec_monto=0;
		$ls_fecha=$this->io_function->uf_convertirdatetobd($as_fecha);
		$ls_sql="SELECT C.procede, C.comprobante, C.fecha, D.descripcion, D.sc_cuenta, D.debhab, D.monto, D.documento, CTA.status ". 
				"  FROM sigesp_cmp C,scg_dt_cmp D,scg_cuentas CTA ".
				" WHERE C.codemp='".$as_codemp."' ".
				"	AND C.procede='".$as_procede."' ".
				"   AND C.comprobante='".$as_comprobante."' ".
				"   AND C.fecha='".$ls_fecha."' ".
				"   AND C.codemp=D.codemp ".
				"   AND C.procede=D.procede ".
				"   AND C.comprobante=D.comprobante ". 
				"   AND C.fecha=D.fecha ". 
				"   AND C.codban = D.codban ".
				"   AND C.ctaban = D.ctaban ".
				"   AND D.codemp=CTA.codemp ".
				"   AND D.sc_cuenta=CTA.sc_cuenta ".
				"   AND CTA.status='C'";
		$this->uf_valida_procedencia($as_procede,&$ls_desproc);
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$lb_valido=false;
			$this->is_msg_error="CLASE->sigesp_int_scg MÉTODO->uf_cargar_comprobante_cierre ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{   
				$ls_cuenta=$row["sc_cuenta"];
				$this->lds_cmp_cierre->insertRow("sc_cuenta",$ls_cuenta);
				$ls_descripcion=$row["descripcion"];
				$this->lds_cmp_cierre->insertRow("denominacion",$ls_descripcion);
				$ls_procede=$row["procede"];
				$this->lds_cmp_cierre->insertRow("procede_doc",$ls_procede);				
				$ls_comprobante=$row["documento"];
				$this->lds_cmp_cierre->insertRow("documento",$ls_comprobante);				
				$ls_debhab=$row["debhab"];
				$this->lds_cmp_cierre->insertRow("debhab",$ls_debhab);
				$ldec_monto=$row["monto"];
				$this->lds_cmp_cierre->insertRow("monto",$ldec_monto);
			} //fin del while
			$this->io_sql->free_result($rs_data);
		}	
				
	    return $lb_valido;
	} // end function uf_cargar_comprobante_cierre
	//-----------------------------------------------------------------------------------------------------------------------------------
	 
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_cargar_comprobante_cierre_delete($as_codemp,$as_procede,$as_comprobante,$as_fecha)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargar_comprobante_cierre
		//		   Access: public 
		//      Arguments: as_codemp // Código de Empresa
		//      		   as_procede // Procede del Comprobante
		//      		   as_comprobante // Número de Comprobante
		//      		   as_fecha // Fecha del Comprobante
		//	  Description: Método que carga el detalle del comprobante de cierre para eliminar detalle a detalle con el metodo procesar_delete_movimiento
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ldec_monto_debe=0;$ldec_monto_haber=0;$ldec_monto=0;
		$ls_fecha=$this->io_function->uf_convertirdatetobd($as_fecha);
		$ls_sql="SELECT C.procede,C.comprobante,C.fecha,D.descripcion,D.sc_cuenta,D.debhab,D.monto,D.documento ". 
				"  FROM sigesp_cmp C,scg_dt_cmp D ".
				" WHERE C.codemp='".$as_codemp."' ".
				"   AND C.procede='".$as_procede."' ".
				"   AND C.comprobante='".$as_comprobante."' ". 
				"   AND C.fecha='".$ls_fecha."' ".
				"   AND C.codemp=D.codemp ".
				"   AND C.procede=D.procede ".
				"   AND C.comprobante=D.comprobante ". 
				"   AND C.fecha=D.fecha ".
				"   AND C.codban=D.codban ".
				"   AND C.ctaban=D.ctaban ";
		$this->uf_valida_procedencia($as_procede,&$ls_desproc);
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->is_msg_error="CLASE->sigesp_int_scg MÉTODO->uf_cargar_comprobante_cierre_delete ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{   
				$ls_cuenta=$row["sc_cuenta"];
				$this->lds_cmp_cierre_del->insertRow("sc_cuenta",$ls_cuenta);
				$ls_descripcion=$row["descripcion"];
				$this->lds_cmp_cierre_del->insertRow("denominacion",$ls_descripcion);
				$ls_procede=$row["procede"];
				$this->lds_cmp_cierre_del->insertRow("procede_doc",$ls_procede);				
				$ls_comprobante=$row["documento"];
				$this->lds_cmp_cierre_del->insertRow("documento",$ls_comprobante);				
				$ls_debhab=$row["debhab"];
				$this->lds_cmp_cierre_del->insertRow("debhab",$ls_debhab);
				$ldec_monto=$row["monto"];
				$this->lds_cmp_cierre_del->insertRow("monto",$ldec_monto);
			} //fin del while
			$this->io_sql->free_result($rs_data);	
		}	
		return $lb_valido;
	} // end function uf_cargar_comprobante_cierre_delete
	//-----------------------------------------------------------------------------------------------------------------------------------
	 
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_fecha_cierre($as_fecha,$ai_day)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_fecha_cierre
		//		   Access: public 
		//      Arguments: as_fecha // Fecha del Cierre 
		//      		   ai_day // Día del Cierre
		//	  Description: Método que calcula la fecha del cierre del periodo
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_dia=0;
		$li_fecha=0;
		//Primero verifico si el año es bisiesto o no.
		$fec=intval(substr($as_fecha,0,4));
		if(($fec % 4) ==0 )
		{
			$li_dia = 1;
		}	
		else
		{
			$li_dia = 0;
		} 	
		$li_dia = (365 + $li_dia + $ai_day) ;
		$mk=mktime(9,0,0,intval(substr($as_fecha,5,2)),intval(substr($as_fecha,8,2)),intval(substr($as_fecha,0,4)));
		$arr=getdate($mk+ ($li_dia * 24 * 60 * 60));
		$ls_fecha=	$arr["mday"]."-".$arr["mon"]."-".$arr["year"];
		return $ls_fecha;
	} // end function uf_scg_fecha_cierre
	//-----------------------------------------------------------------------------------------------------------------------------------
	 
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_cargar_datastore_cuentas($ads_cuentas,$as_cuentas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargar_datastore_cuentas
		//		   Access: public 
		//      Arguments: ads_cuentas // Datastored de cuentas
		//      		   as_cuentas // Cuentas contables
		//	  Description: inserta la información del saldo de la cuenta correspondiente.
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_fila=0;
		$li_estsel=0;
		$i=0;
		$lb_valido=true;
		$ls_sql="SELECT sc_cuenta, denominacion " .	
				"  FROM sigesp_plan_unico " .
				" WHERE sc_cuenta LIKE '".$as_cuentas ."'" .
				" ORDER BY sc_cuenta";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->is_msg_error="CLASE->sigesp_int_scg MÉTODO->uf_cargar_datastore_cuentas ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{   
				$i=$i+1;
				$ls_sc_cuentas=$row["sc_cuenta"];
				$ls_denominacion=$row["denominacion"];
				$ads_cuentas->insertRow("estsel",$li_estsel);
				$ads_cuentas->insertRow("sc_cuenta",$ls_sc_cuentas);
				$ads_cuentas->insertRow("denominacion",$ls_denominacion);
				$ads_cuentas->insertRow("status",$ls_status);
			} //fin del while
			$this->io_sql->free_result($rs_data);	
		}								
		return $lb_valido;
	} // end function uf_cargar_datastore_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	 function uf_scg_cargar_detalle_comprobante($as_codemp,$as_procede,$as_comprobante,$as_fecha,$lds_detalle_cmp,$as_codban,
	 											$as_ctaban)
     {	 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_cargar_detalle_comprobante
		//		   Access: public 
		//      Arguments: as_codemp // Código de Empresa
		//      		   as_procede // Procede del Comprobante
		//      		   as_comprobante // Número del comprobante
		//      		   as_fecha // fecha del Comprobante
		//      		   lds_detalle_cmp // Detalles del comprobante
		//      		   as_codban // Código de Banco
		//      		   as_ctaban // Cuentas de Banco
		//	  Description: obtiene la información de un comprobante
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT DISTINCT DT.sc_cuenta as sc_cuenta, C.denominacion as denominacion, DT.procede_doc as procede_doc, ".
				"	    P.desproc as despro, DT.documento as documento, DT.fecha as fecha, DT.debhab as debhab, ".
				" 		DT.descripcion as descripcion, DT.monto as monto, DT.orden as orden " .
				"  FROM scg_dt_cmp DT, scg_cuentas C, sigesp_procedencias P ".
				" WHERE DT.codemp='".$as_codemp."' ".
				"   AND DT.procede='".$as_procede."' ".
				"   AND DT.comprobante='".$as_comprobante."' ".
			    "   AND DT.fecha= '".$as_fecha."' ".
				"   AND DT.codban= '".$as_codban."' ".
				"   AND DT.ctaban= '".$as_ctaban."' ".
				"	AND DT.sc_cuenta=C.sc_cuenta AND DT.procede=P.procede ".
				" ORDER BY DT.orden ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$this->is_msg_error="CLASE->sigesp_int_scg MÉTODO->uf_scg_cargar_detalle_comprobante ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		return $rs_data;
	 }  // end function uf_scg_cargar_detalle_comprobante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_existe_comprobante_cierre()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_existe_comprobante_cierre
		//		   Access: public 
		//      Arguments: 
		//	  Description: funcion que verifica si el comprobante de cierre existe ó no
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe_comprobante=false;
		$li_ok=0;
		$li_year=0;  
		$ls_comprobante="";
		$ls_documento="";
		$ls_procede="";
		$ls_fecha="";
		$ls_fecha_cierre="";
		$ld_f_periodo="";
		$ls_procede = "SCGCIE";
		$ls_ctaresultadod=$this->dat_emp["c_resultad"];
		$ls_ctaresultadon=$this->dat_emp["c_resultan"];
		if(($ls_ctaresultadod==null)||(trim($ls_ctaresultadod)==""))
		{
			$this->is_msg_error="No se definio la cuenta de resultado";
			return false;
		}		
		if(($ls_ctaresultadon==null)||(trim($ls_ctaresultadon)==""))
		{
			$this->is_msg_error="No se definio la cuenta de resultado anterior";
			return false;
		}
		$li_year=intval(substr($this->dat_emp["periodo"],0,4));
		$ls_comprobante="CIERRE-".strval($li_year);
		$ls_comprobante=$this->io_function->uf_cerosizquierda( $ls_comprobante, 15 );
		$ls_fecha_cierre = $this->uf_scg_fecha_cierre($this->dat_emp["periodo"],-1);
		// falta colocar validaciones de fecha de cierre okey
		$lb_valido=$this->io_fecha->uf_valida_fecha_periodo( $ls_fecha_cierre , $this->dat_emp["codemp"]);
		if($lb_valido)
		{
			$this->is_codemp=$this->dat_emp["codemp"];
			$ls_codemp=$this->dat_emp["codemp"];
			$this->is_comprobante=$ls_comprobante;
			$this->is_procedencia=$ls_procede;
			$this->id_fecha=$ls_fecha_cierre;
			$this->as_codban="---";
			$this->as_ctaban="-------------------------";
			$this->is_tipo="-";
			$this->is_cod_prov="----------";
			$this->is_ced_ben="----------";
			$this->is_descripcion="CIERRE DEL EJERCICIO";
			$lb_valido=true; 
			$lb_existe_comprobante=$this->uf_select_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha_cierre,$this->as_codban,$this->as_ctaban);
		}	
		return  $lb_existe_comprobante;
	}  // end function uf_existe_comprobante_cierre
	//-----------------------------------------------------------------------------------------------------------------------------------

 	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_make_cierre()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_make_cierre
		//		   Access: public 
		//      Arguments: 
		//	  Description: funcion que genera los asientos de cierre para un año
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_cuenta="";
		$ls_descripcion="";
		$ls_procede_doc="";
		$ls_documento="";
		$ls_operacion="";
		$ls_tc="";
		$ls_cadena="";
		$ldec_saldo=0;
		$ldec_monto_anterior=0;
		$ldec_monto_actual=0;
		$ldec_saldo_acumulado=0;
		$li_year=0;
		$ls_cuenta=$this->dat_emp["c_resultad"];
		$ls_cuenta=$this->uf_pad_scg_cuenta($this->dat_emp["formcont"],$ls_cuenta);
		$ls_procede_doc=$this->is_procedencia;
		$lb_existe=$this->uf_scg_select_cuenta($_SESSION["la_empresa"]["codemp"],$ls_cuenta,&$ls_status,&$ls_denominacion);
		if($lb_existe)
		{
			if($this->uf_scg_saldo($ls_cuenta,&$ldec_saldo,$this->id_fecha))
			{
				if($ldec_saldo!=0)
				{ 
					$ls_descripcion="TRASLADO DE RESULTADOS";
					$ls_documento="000000000000001";
					$ldec_monto_actual=abs($ldec_saldo);
					if($ldec_saldo>0)
					{
						$ls_operacion="H";
					}   
					else	
					{
						$ls_operacion="D";
					}
					$lb_valido = $this->uf_scg_procesar_insert_movimiento($this->is_codemp,$this->is_procedencia,$this->is_comprobante,$this->id_fecha,$this->is_tipo,$this->is_cod_prov,$this->is_ced_ben,$ls_cuenta,$ls_procede_doc,$ls_documento,$ls_operacion,$ls_descripcion,$ldec_monto_anterior,$ldec_monto_actual);
					if(!($lb_valido))
					{
						return false;
					}	 	
					// TRASLADO DE RESULTADOS ANTERIORES
					$ls_cuenta = $this->dat_emp["c_resultan"];
					$ls_cuenta = $this->uf_pad_scg_cuenta( $this->dat_emp["formcont"],$ls_cuenta);
					$lb_existe=$this->uf_scg_select_cuenta($_SESSION["la_empresa"]["codemp"],$ls_cuenta,&$ls_status,&$ls_denominacion);
					$ls_descripcion = "TRASLADO DE RESULTADOS ANTERIORES";
					$ls_documento   = "000000000000001";
					$ldec_monto_actual = abs($ldec_saldo);
					if($lb_existe)
					{
						if ($ldec_saldo>0)
						{
							$ls_operacion = "D";
						}   
						else
						{
							$ls_operacion = "H";
						} 	
						if($lb_existe)
						{
							$lb_valido = $this->uf_scg_procesar_insert_movimiento($this->is_codemp,$this->is_procedencia,$this->is_comprobante,$this->id_fecha,$this->is_tipo,$this->is_cod_prov,$this->is_ced_ben,$ls_cuenta,$ls_procede_doc,$ls_documento,$ls_operacion,$ls_descripcion,$ldec_monto_anterior,$ldec_monto_actual) ;
						}
						if (!$lb_valido)
						{
							return false;
						}
					}	
					else
					{
						$this->is_msg_error="No existe la Cuenta de Resultados Anteriores en el Plan de Cuentas";	
						return false;  	
					}		
				} 	
			} 
		}
		else
		{
			$this->is_msg_error="No existe la Cuenta de Resultados en el Plan de Cuentas";
			return false;
		}
		// CIERRE DE LAS CUENTAS DE GASTOS
		$ls_cuenta="";
		$ls_tc=trim($this->dat_emp["gasto"])."%";
		$ls_sql="SELECT sc_cuenta ".
				"  FROM scg_cuentas ".
				" WHERE codemp='".$this->is_codemp."' ".
				"   AND sc_cuenta LIKE '".$ls_tc."' ".
				"   AND status='C'".
				" ORDER BY sc_cuenta";	
		$li_year=intval(substr($this->id_fecha,6,4));
		$ls_descripcion=trim(strval($li_year));
		$ls_descripcion="CIERRE DEL EJERCICIO AÑO ".$ls_descripcion;
		$rs_cierre=$this->io_sql->select($ls_sql);
		while ($row=$this->io_sql->fetch_row($rs_cierre))
		{
			$ldec_saldo=0;	
			$ls_cuenta = $row["sc_cuenta"];
			$lb_saldo=$this->uf_scg_saldo($ls_cuenta,&$ldec_saldo,$this->id_fecha);
			if($lb_saldo)
			{
				$ldec_saldo_acumulado = ($ldec_saldo_acumulado + $ldec_saldo);
				$ls_documento = "000000000000002";
				$ldec_monto_actual = abs($ldec_saldo);
				if ($ldec_saldo!=0)
				{ 
					if ($ldec_saldo>0)
					{
						$ls_operacion = "H";
					}   
					else	
					{
						$ls_operacion = "D";
					} 	
					$lb_valido=$this->uf_scg_procesar_insert_movimiento($this->is_codemp,$this->is_procedencia,$this->is_comprobante,$this->id_fecha,$this->is_tipo,$this->is_cod_prov,$this->is_ced_ben,$ls_cuenta,$ls_procede_doc,$ls_documento,$ls_operacion,$ls_descripcion,$ldec_monto_anterior,$ldec_monto_actual,$this->as_codban,$this->as_ctaban);														
					if(!($lb_valido))
					{
						return false;			
					}	
				}
			}
		}
		// ASIENTO DE CUADRE DE LOS GASTOS
		$ls_cuenta=$this->dat_emp["c_resultad"];
		$ls_cuenta=$this->uf_pad_scg_cuenta( $this->dat_emp["formcont"],$ls_cuenta);
		$lb_existe=$this->uf_scg_select_cuenta($_SESSION["la_empresa"]["codemp"],$ls_cuenta,&$ls_status,&$ls_denominacion);
		$ldec_saldo=$ldec_saldo_acumulado;
		$li_year=intval(substr($this->id_fecha,6,4));
		$ls_descripcion=trim(strval($li_year));
		$ls_descripcion="CIERRE DEL EJERCICIO AÑO ". $ls_descripcion;
		if($lb_existe)
		{
			if(($ldec_saldo!=0)&&($lb_existe))
			{
				$ls_documento = "000000000000002";
				$ldec_monto_actual = abs($ldec_saldo);
				if ($ldec_saldo>0)
				{
					$ls_operacion = "D";
				}   
				else
				{
					$ls_operacion = "H";
				}    
				$lb_valido=$this->uf_scg_procesar_insert_movimiento($this->is_codemp,$this->is_procedencia,$this->is_comprobante,$this->id_fecha,$this->is_tipo,$this->is_cod_prov,$this->is_ced_ben,$ls_cuenta,$ls_procede_doc,$ls_documento,$ls_operacion,$ls_descripcion,$ldec_monto_anterior,$ldec_monto_actual,$this->as_codban,$this->as_ctaban);														
				if (!($lb_valido))
				{
					return false;
				}	  	
			}
		}
		else
		{
			$this->is_msg_error="No existe la Cuenta de Resultados en el Plan de Cuentas";
			return false;
		} 
		// CIERRE DE LAS CUENTAS DE INGRESOS
		$ls_tc=trim($this->dat_emp["ingreso"])."%";
		$ls_cuenta="";
		$ls_sql="SELECT sc_cuenta ".
				"  FROM scg_cuentas ".
				" WHERE codemp='".$this->is_codemp."' ".
				"   AND sc_cuenta LIKE '".$ls_tc."' ".
				"   AND status='C'".
				" GROUP BY sc_cuenta ORDER BY sc_cuenta";
		$li_year=intval(substr($this->id_fecha,6,4));
		$ls_descripcion=trim(strval($li_year));
		$ls_descripcion="CIERRE DEL EJERCICIO AÑO ". $ls_descripcion;
		$ldec_saldo_acumulado=0;
		$rs_cierre=$this->io_sql->select($ls_sql);
		if($rs_cierre===false)
		{
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_cierre)) 
			{   
				$ls_cuenta=$row["sc_cuenta"];
				$ldec_saldo=0;	
				if($this->uf_scg_saldo($ls_cuenta,&$ldec_saldo,$this->id_fecha))
				{
					if ($ldec_saldo!=0)
					{	 
						$ldec_saldo_acumulado = ($ldec_saldo_acumulado + $ldec_saldo);
						$ls_documento = "000000000000003";
						$ldec_monto_actual = abs($ldec_saldo);
						if ($ldec_saldo>0)
						{
							$ls_operacion = "H";
						}   
						else
						{
							$ls_operacion = "D";
						}
						$lb_valido=$this->uf_scg_procesar_insert_movimiento($this->is_codemp,$this->is_procedencia,$this->is_comprobante,$this->id_fecha,$this->is_tipo,$this->is_cod_prov,$this->is_ced_ben,$ls_cuenta,$ls_procede_doc,$ls_documento,$ls_operacion,$ls_descripcion,$ldec_monto_anterior,$ldec_monto_actual,$this->as_codban,$this->as_ctaban);														
						if (!($lb_valido))
						{
							return false;			
						}	
					}
				}			
			} //fin del while
		}
		// ASIENTO DE CUADRE DE LOS INGRESOS
		$ls_cuenta=$this->dat_emp["c_resultad"];
		$ls_cuenta=$this->uf_pad_scg_cuenta( $this->dat_emp["formcont"],$ls_cuenta);
		$ldec_saldo=$ldec_saldo_acumulado;
		$lb_existe=$this->uf_scg_select_cuenta($_SESSION["la_empresa"]["codemp"],$ls_cuenta,&$ls_status,&$ls_denominacion);			
		$li_year=intval(substr($this->id_fecha,6,4));
		$ls_descripcion=trim(strval($li_year));
		$ls_descripcion="CIERRE DEL EJERCICIO AÑO ". $ls_descripcion;
		if($lb_existe)
		{
			if(($ldec_saldo!=0))
			{
				$ls_documento  = "000000000000003";
				$ldec_monto_actual = abs($ldec_saldo);
				if ($ldec_saldo>0)
				{
					$ls_operacion = "D";
				}   
				else	
				{
					$ls_operacion = "H";
				}	
				$lb_valido=$this->uf_scg_procesar_insert_movimiento($this->is_codemp,$this->is_procedencia,$this->is_comprobante,$this->id_fecha,$this->is_tipo,$this->is_cod_prov,$this->is_ced_ben,$ls_cuenta,$ls_procede_doc,$ls_documento,$ls_operacion,$ls_descripcion,$ldec_monto_anterior,$ldec_monto_actual,$this->as_codban,$this->as_ctaban);														
				if (!($lb_valido))
				{ 
					return false;
				}		
			} 
		}
		else
		{
			$this->is_msg_error="No existe la Cuenta de Resultados en el Plan de Cuentas";
			return false;
		}
		return $lb_valido;
	 }  // end function uf_scg_make_cierre
 	//-----------------------------------------------------------------------------------------------------------------------------------

 	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_saldo($as_cuenta,&$adec_saldo,$adt_fec_cierre)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_saldo
		//		   Access: public 
		//      Arguments: as_cuenta // Cuenta Contable
		//      		   adec_saldo // Monto del Saldo
		//      		   adt_fec_cierre // Fecha de Cierre
		//	  Description: retorna el saldo de una cuenta
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ldec_total_debe=0;
		$ldec_total_haber=0;
		$ldec_TOTAL=0;
		$ldec_total_debe=0;
		$ldec_total_haber=0;
		$adec_saldo=0; 
		$ls_fecha_ini_cierre=$this->dat_emp["periodo"];
		$ls_fecha_ini_cierre=$this->io_function->uf_convertirdatetobd($ls_fecha_ini_cierre);
		if(!$this->uf_scg_select_cuenta($this->is_codemp,$as_cuenta,&$ls_status,&$ls_denominacion))
		{
			$this->is_msg_error="La cuenta ".$as_cuenta." no existe.";
			return false;
		} 
		if(!$ls_status=="C")
		{
			$this->is_msg_error="La cuenta ".$as_cuenta." no es de movimiento.".$ls_status;
			return false;
		}
		$ls_fecha_fin_cierre= $this->io_function->uf_convertirdatetobd($adt_fec_cierre);
		// consula sql para movimientos del debe
		$ls_sql="SELECT SUM( monto ) As ntotal ".
			    "  FROM scg_dt_cmp".
			    " WHERE codemp='".$this->is_codemp."' ".
				"   AND sc_cuenta='".$as_cuenta."' ".
				"   AND fecha >='".$ls_fecha_ini_cierre."' ".
				"   AND fecha <='".$ls_fecha_fin_cierre."' ".
				"   AND debhab='D'";
		$rs_saldo=$this->io_sql->select($ls_sql);
		if($rs_saldo===false)
		{
			$this->is_msg_error = "CLASE->sigesp_int_scg MÉTODO->uf_scg_saldo ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_saldo))
			{
				$ldec_TOTAL=$row["ntotal"];
			}
		}
		if ($ldec_TOTAL!=0)	
		{   
			$ldec_total_debe = $ldec_TOTAL;	
		} 
		$this->io_sql->free_result($rs_saldo);		
		// consula ls_sql para movimientos del haber
		$ls_sql="SELECT SUM( monto ) As ntotal ".
			    "  FROM scg_dt_cmp".
			    " WHERE codemp='".$this->is_codemp."' ".
				"   AND sc_cuenta='".$as_cuenta."' ".
				"   AND fecha >='".$ls_fecha_ini_cierre."' ".
				"   AND fecha <='".$ls_fecha_fin_cierre."' ".
				"   AND debhab='H'";
		$rs_saldo=$this->io_sql->select($ls_sql);
		if($rs_saldo===false)
		{
			$this->is_msg_error ="CLASE->sigesp_int_scg MÉTODO->uf_scg_saldo ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_saldo))
			{
				$ldec_TOTAL=$row["ntotal"];
			}
	    }
		$this->io_sql->free_result($rs_saldo);		
		if(($ldec_TOTAL!=0)) 
		{	
			$ldec_total_haber = $ldec_TOTAL; 
		} 
		$adec_saldo=$ldec_total_debe - $ldec_total_haber;
		return true;
	} // end function de uf_scg_saldo()
 	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_comprobante_update($li_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_comprobante_update
		//		   Access: public 
		//      Arguments: li_monto // monto del comprobante
		//	  Description: Este método actualiza si existe el comprobante 
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql="UPDATE sigesp_cmp ".
				"   SET total = (total + ".$li_monto.")  ".
				" WHERE codemp = '".$this->is_codemp."' ".
				"   AND procede = '".$this->is_procedencia."' ".
				"   AND comprobante= '".$this->is_comprobante."' ".
				"   AND fecha='".$ld_fecha."' ".
				"   AND codban='".$this->as_codban."' ".
				"   AND ctaban='".$this->as_ctaban."' ";
		$li_exec=$this->io_sql->execute($ls_sql);
		if($li_exec===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_scg MÉTODO->uf_scg_comprobante_update ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}	   
		return $lb_valido;
	}  // end function uf_scg_comprobante_update
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_comprobante_update_cero()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_comprobante_update_cero
		//		   Access: public 
		//      Arguments: li_monto // monto del comprobante
		//	  Description: Este método actualiza si existe el comprobante 
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 14/08/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql="UPDATE sigesp_cmp ".
				"   SET total = 0  ".
				" WHERE codemp = '".$this->is_codemp."' ".
				"   AND procede = '".$this->is_procedencia."' ".
				"   AND comprobante= '".$this->is_comprobante."' ".
				"   AND fecha='".$ld_fecha."' ".
				"   AND codban='".$this->as_codban."' ".
				"   AND ctaban='".$this->as_ctaban."' ";
		$li_exec=$this->io_sql->execute($ls_sql);
		if($li_exec===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_scg MÉTODO->uf_scg_comprobante_update_cero ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}	   
		return $lb_valido;
	}  // end function uf_scg_comprobante_update_cero
	//-----------------------------------------------------------------------------------------------------------------------------------

	////////////////////////////////////////////////// MÉTODOS CON TRANSACCIONES /////////////////////////////////////////////////

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_plan_unico_cuenta($as_cuenta,$as_denominacion,$as_status)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_plan_unico_cuenta
		//		   Access: public 
		//      Arguments: as_cuenta // cuenta Contable
		//    			   as_denominacion // Denominación de la cuenya
		//    			   as_status // estatus de la Cuenta
		//	  Description: Este método inserta la cuenta y la denominacion en la tabla plan unico de cuenta
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($this->uf_select_plan_unico($as_cuenta))
		{
			if($as_status=='C')
			{
				$ls_sql="UPDATE sigesp_plan_unico ".
						"   SET denominacion='".$as_denominacion."'".
						" WHERE sc_cuenta='".trim($as_cuenta)."'";
				$li_exec=$this->io_sql->execute($ls_sql);
				if($li_exec===false)
				{
					$this->is_msg_error="CLASE->sigesp_int_scg MÉTODO->uf_insert_plan_unico_cuenta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
					$this->io_sql->rollback();	
					return false;
				}
				else
				{
					$this->io_sql->commit();
				}
			}
			else
			{
				$this->is_msg_error = "ERROR->Cuenta ya existe introduzca un nuevo codigo";
				return false;
			}
		}
		else
		{
			$ls_sql="INSERT INTO sigesp_plan_unico (sc_cuenta,denominacion) ".
					" VALUES ('".trim($as_cuenta)."','".trim($as_denominacion)."')";
			$li_exec=$this->io_sql->execute($ls_sql);
			if($li_exec===false)
			{
				$this->is_msg_error="CLASE->sigesp_int_scg MÉTODO->uf_insert_plan_unico_cuenta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function uf_insert_plan_unico_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_update_cuenta($as_codemp,$as_sc_cuenta,$as_denominacion)
	{	 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_update_cuenta
		//		   Access: public 
		//      Arguments: as_codemp  // Código de Empresa
		//				   as_cuenta // cuenta Contable
		//    			   as_denominacion // Denominación de la cuenya
		//	  Description: actualiza la informacion de la cuenta
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE scg_cuentas ".
				"   SET denominacion='". $as_denominacion ."'".
				" WHERE codemp='" .$as_codemp ."' ".
				"   AND sc_cuenta=". $as_sc_cuenta ;
		$this->io_sql->begin_transaction();
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$this->is_msg_error = "CLASE->sigesp_int_scg MÉTODO->uf_scg_update_cuenta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();		  			
			return false;
		}
		else 
		{	 
			$this->io_sql->commit();	
		}
		return $lb_valido;
	} // end function uf_scg_update_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_delete_cuenta($as_codemp, $as_cuenta)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_update_cuenta
		//		   Access: public 
		//      Arguments: as_codemp  // Código de Empresa
		//				   as_cuenta // cuenta Contable
		//	  Description: elimina una cuenta del plan de cuentas
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM scg_cuentas".
				" WHERE codemp= '".$as_codemp. "' ".
				"   AND sc_cuenta = '".$as_cuenta."' "; 
		$this->io_sql->begin_transaction();	
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$this->is_msg_error = "CLASE->sigesp_int_scg MÉTODO->uf_scg_delete_cuenta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();					
			$lb_valido = false;
		}
		else
		{
			$this->io_sql->commit();
		}
		return $lb_valido;
	} // end function uf_scg_delete_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_scg_procesar_cierre()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_procesar_cierre
		//		   Access: public 
		//      Arguments: 
		//	  Description: genera el comprobante y movimientos de cierre contable
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fecha;
		$lb_valido=true;
		$li_ok=0;
		$li_year=0;  
		$ls_comprobante="";
		$ls_documento="";
		$ls_procede="";
		$ls_fecha="";
		$ls_fecha_cierre="";
		$ld_f_periodo="";
		$ls_procede = "SCGCIE";
		$ls_ctaresultadod=$this->dat_emp["c_resultad"];
		$ls_ctaresultadon=$this->dat_emp["c_resultan"];
		if(($ls_ctaresultadod==null)||(trim($ls_ctaresultadod)==""))
		{
			$this->is_msg_error="No se definio la cuenta de resultado";
			return false;
		}
		if(($ls_ctaresultadon==null)||(trim($ls_ctaresultadon)==""))
		{
			$this->is_msg_error="No se definio la cuenta de resultado anterior";
			return false;
		}
		$li_year = intval(substr($this->dat_emp["periodo"],0,4));
		$ls_comprobante = "CIERRE-".strval($li_year);
		$ls_comprobante = $this->io_function->uf_cerosizquierda( $ls_comprobante, 15 );
		$ls_fecha_cierre = $this->uf_scg_fecha_cierre($this->dat_emp["periodo"],-1);
		// falta colocar validaciones de fecha de cierre okey
		$lb_valido = $this->io_fecha->uf_valida_fecha_periodo( $ls_fecha_cierre , $this->dat_emp["codemp"]);
		if($lb_valido)
		{
			$this->is_codemp=$this->dat_emp["codemp"];
			$ls_codemp=$this->dat_emp["codemp"];
			$this->is_comprobante=$ls_comprobante;
			$this->is_procedencia=$ls_procede;
			$this->id_fecha=$ls_fecha_cierre;
			$this->is_tipo="-";
			$this->is_cod_prov="----------";
			$this->is_ced_ben="----------";
			$this->is_descripcion="CIERRE DEL EJERCICIO";
			$this->as_codban="---";
			$this->as_ctaban="-------------------------";
			$lb_valido=true; 
			$this->io_sql->begin_transaction();
			if (!($this->uf_select_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha_cierre,$this->as_codban,$this->as_ctaban)))
			{                 
				$lb_valido = $this->uf_sigesp_insert_comprobante($this->is_codemp,$this->is_procedencia,$this->is_comprobante,
																 $this->id_fecha,1,$this->is_descripcion,$this->is_tipo,
																 $this->is_cod_prov,$this->is_ced_ben,$this->as_codban,
																 $this->as_ctaban);
				if($lb_valido)
				{
					$lb_valido = $this->uf_scg_make_cierre();
					if (!($lb_valido))
					{
						return false;
					}
				}		
				else
				{
					return false;
				}
			}		
			else
			{
				$this->is_msg_error="El cierre fue ejecutado con anterioridad.";
				return false;
			}
		}
		else
		{
			$this->is_msg_error=$this->io_fecha->is_msg_error;
		}
		$this->uf_sql_transaction($lb_valido);
		return $lb_valido ;
	 }  // end function uf_scg_procesar_cierre
 	//-----------------------------------------------------------------------------------------------------------------------------------

 	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_procesar_movimiento_cmp($as_codemp,$as_procedencia,$as_comprobante,$ad_fecha,$as_proveedor,$as_beneficiario,
											$as_tipo,$as_tipo_comp,$as_sc_cuenta,$as_procede_doc,$as_documento,$as_operacion,
											$as_descripcion,$adec_monto,$as_codban,$as_ctaban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_procesar_cierre
		//		   Access: public 
		//      Arguments: as_codemp // Código de empresa
		//      		   as_procedencia // Procedencia del Comprobante
		//      		   as_comprobante // Número del Comprobante
		//      		   ad_fecha // Fecha del Comprobante
		//      		   as_proveedor // Código del Proveedor
		//      		   as_beneficiario // Cédula del Beneficiario
		//      		   as_tipo // Tipo 
		//      		   as_tipo_comp // Tipo decomprobante
		//      		   as_sc_cuenta // Cuenta Contable
		//      		   as_procede_doc // Procede del Documento
		//      		   as_documento // Número del Documento
		//      		   as_operacion // Operación si es debe ó haber
		//      		   as_descripcion // descripción del Movimiento
		//      		   adec_monto // Monto del Movimiento
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta de Banco
		//	  Description: genera el comprobante 
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->is_codemp=$as_codemp;
		$this->is_procedencia=$as_procedencia;
		$this->is_comprobante=$as_comprobante;
		$this->id_fecha=$ad_fecha;
		$this->is_cod_prov=$as_proveedor;
		$this->is_ced_ben=$as_beneficiario;
		$this->is_tipo=$as_tipo;
		$this->as_codban=$as_codban;
		$this->as_ctaban=$as_ctaban;
		//--------------------------
		//-- Preparo los datos --
		//--------------------------
		$this->is_comprobante=$this->io_function->uf_cerosizquierda($as_comprobante,15);
		$as_documento=$this->io_function->uf_cerosizquierda($as_documento,15);
		//--------------------------
		//-- verifico los datos --
		//--------------------------
		$lb_valido=true;
		//-- valido que la cuenta exista
		if(!$this->uf_scg_select_cuenta($as_codemp,$as_sc_cuenta,&$ls_status,&$ls_denominacion))
		{
			$this->io_msg->message("La cuenta ".$as_sc_cuenta." no existe");
			return false;
		}
		//- valido que sea una cuenta de movimiento
		if($ls_status!="C")
		{
			$this->io_msg->message("La cuenta ".$as_sc_cuenta." no es de movimiento");
			return false;
		}
		//-- verifico la Procede_Doc
		if(!$this->uf_valida_procedencia($as_procede_doc,&$as_descproc))
		{
			$this->io_msg->message("La procedencia ".$as_procede_doc." no esta registrada");
			return false;
		}
		//-- verifico la Fecha
		if(!$this->io_fecha->uf_valida_fecha_mes($as_codemp,$ad_fecha))
		{
			$this->io_msg->message($this->int_fec->is_msg_error);
			return false;
		}
		if($this->uf_scg_select_movimiento($as_sc_cuenta,$as_procede_doc,$as_documento,$as_operacion, &$adec_monto_anterior,&$ai_orden))
		{	
			$this->io_msg->message("El Movimiento ya existe ");
			return false;
		}
		//Inicio la transacion
		$this->io_sql->begin_transaction();
		if($lb_valido)
		{
			$lb_valido= $this->uf_scg_insert_movimiento($as_sc_cuenta,$as_procede_doc,$as_documento,$as_operacion,$as_descripcion,&$adec_monto);
		}
		if($lb_valido)
		{
			$lb_valido = $this->uf_scg_procesar_saldos_contables($as_sc_cuenta,$as_operacion,$adec_monto_anterior,$adec_monto);
		}
		//-- Realizo transaccion --
		if($lb_valido)
		{
			 $this->io_sql->commit();
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}  // end function uf_scg_procesar_movimiento_cmp
 	//-----------------------------------------------------------------------------------------------------------------------------------

 	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_update_movimiento($as_cuenta, $as_procede_doc, $as_documento, $as_debhab, $as_descripcion, $adec_monto)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////
		// 	 Function:  uf_scg_insert_movimiento
		// 	   Access:  public
		//  Arguments:  $as_cuenta->cuenta contable ,$as_procede_doc->procede documento,$as_documento->nº documento
		//              $as_operacion->operacion debe haber,$adec_monto->mnto movimiento
		//	  Returns:  Boolean
		//Description:  Este método registra un movimiento final contable enla tabla movimiento  (DEPENDE DEL PROCESAR)
		////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_orden=$this->uf_scg_obtener_orden_movimiento();
		$ls_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql="UPDATE scg_dt_cmp SET monto=(codemp,procede,comprobante,fecha,sc_cuenta,procede_doc,documento,debhab,descripcion,monto,orden) " . 
				" VALUES ('".$this->is_codemp."','".$this->is_procedencia."','".$this->is_comprobante."','" .$ls_fecha."',".
				"'".$as_cuenta."', '".$as_procede_doc."','".$as_documento."','".$as_debhab."','".$as_descripcion."',".$adec_monto.",".$li_orden.")" ;
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			if($this->io_sql->errno==1452)
			{   
				$this->is_msg_error="CLASE->sigesp_int_scg MÉTODO->uf_scg_insert_movimiento ERROR->Fallo alguna clave foranea";
			}
			else
			{
				$this->is_msg_error="CLASE->sigesp_int_scg MÉTODO->uf_scg_insert_movimiento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			}
		   	$lb_valido=false;
		}
		return $lb_valido;
	} // end function uf_scg_update_movimiento	
 	//-----------------------------------------------------------------------------------------------------------------------------------
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 }
?>