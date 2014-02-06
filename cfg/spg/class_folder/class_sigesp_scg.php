<?php
require_once("../../shared/class_folder/class_sigesp_int_scg.php");
class class_sigesp_scg extends class_sigesp_int_scg
{
	function class_sigesp_scg()
	{
		$this->int_fecha=new class_fecha();
		$this->fun=new class_funciones();
		$this->msg=new class_mensajes();
		require_once("../../shared/class_folder/sigesp_include.php");
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$this->SQL=new class_sql($this->con);
	}
	
	function uf_scg_procesar_movimiento_cmp($as_codemp,$as_procedencia,$as_comprobante,$ad_fecha,
											$as_proveedor,$as_beneficiario,$as_tipo,$as_tipo_comp,$as_sc_cuenta,
											$as_procede_doc,$as_documento,$as_operacion,$as_descripcion,$adec_monto)
	{
		//-----------------------------------------------------------
		//***************************NOTA****************************
		//-----------------------------------------------------------
		// Esta rutina es para ser usada solo por el sistema de 
		//  contabilidad.
		// Asume que sc_comprobante ya esta registrado.
		//-----------------------------------------------------------
		
/*		cInfLog=;
			[AGREGAR DETALLE A COMPROBANTE]+CRLF+;
			[COMPROBANTE]+CRLF+;
			[Comp: ]+cProcede+[ / ]+cComprobante+[ / ]+dtoc(dFecha)+CRLF+;
			[Desc: ]+alltrim(tcCompDescripcion)+CRLF+;
			iif(tcTipo='P',[Proveedor: ]+cCod_pro,iif(tcTipo='B',[Beneficiario: ]+cCed_bene,[Fuente: N/A]))+CRLF+;
			[MOVIMIENTO]+CRLF+;
			[Cta:  ]+cCuenta+CRLF+;
			[Doc:  ]+cProcedeDoc+[ ]+cDocumento+CRLF+;
			[Ope: ]+cOperacion+CRLF+;
			[Monto: ]+transform(nMontoActual,"999,999,999,999.99")+CRLF+;
			[Desc: ]+alltrim(cDescripcion)+CRLF*/
		
		$this->is_codemp     = $as_codemp;
		$this->is_procedencia= $as_procedencia;
		$this->is_comprobante= $as_comprobante;
		$this->is_fecha		 = $ad_fecha;
		$this->is_cod_prov   = $as_proveedor;
		$this->is_ced_ben    = $as_beneficiario;
		$this->is_tipo       = $as_tipo;
		
		
		//--------------------------
		//-- Preparo los datos --
		//--------------------------
		$this->is_comprobante = $this->fun->uf_cerosizquierda($as_comprobante,15);
		$as_documento		  =	$this->fun->uf_cerosizquierda($as_documento,15);
		
		
		//--------------------------
		//-- verifico los datos --
		//--------------------------
		
		$lb_valido=true;
		
		//-- valido que la cuenta exista
		if(!$this->uf_scg_select_cuenta($as_codemp,$as_sc_cuenta,&$ls_status,&$ls_denominacion))
		{
			$this->msg_message("La cuenta ".$as_sc_cuenta." no existe");
			return false;
		}
		
		//- valido que sea una cuenta de movimiento
		if($ls_status!="C")
		{
			$this->msg->message("La cuenta ".$as_sc_cuenta." no es de movimiento");
			return false;
		}
		
		//-- verifico la Procede_Doc
		if(!$this->uf_valida_procedencia($as_procede_doc,&$as_descproc))
		{
			$this->msg->message("La procedencia ".$as_procede_doc." no esta registrada");
			return false;
		}
		
		//-- verifico la Fecha
		if(!$this->int_fecha->uf_valida_fecha_mes($as_codemp,$ad_fecha))
		{
			$this->msg->message($this->int_fec->is_msg_error);
			return false;
		}
		
		
		
		//--------------------------
		//-- Graba en Movimientos --
		//--------------------------
		
		//--Primero Verifico que no exista
		//local nMonto
		if($this->uf_scg_select_movimiento($as_sc_cuenta,$as_procede_doc,$as_documento,$as_operacion, &$adec_monto_anterior,&$ai_orden))
		{	
			$this->msg->message("El Movimiento ya existe ");
			return false;
		}
		
		
		
		//Inicio la transacion
		$this->SQL->begin_transaction();
			
		
		//--------------------------
		//-- Check for header --
		//--------------------------
		if( $lb_valido)
		{
			$lb_valido=$this->uf_scg_comp_act($adec_monto_anterior, $adec_monto , $as_operacion);
		}
		
		if($lb_valido)
		{
			$lb_valido= $this->uf_scg_insert_movimiento_contable( $as_sc_cuenta, $as_procede_doc, $as_documento, $as_operacion, $as_descripcion, &$adec_monto );
		}
		
		//----------------------
		//-- Actualizo saldos --
		//----------------------
		if($lb_valido)
		{
			$lb_valido = $this->uf_scg_procesar_saldos_contables($as_sc_cuenta,$as_operacion,$adec_monto_anterior, $adec_monto );
		}
		
		
		//--------------------------
		//-- Realizo transaccion --
		//--------------------------
		if($lb_valido)
		{
			 $this->SQL->commit();
		}
		else
		{
			$this->SQL->rollback();
		}
		
		
		/**-Inicio la transacion
		nResult=SQLSETPROP(this.nHandle, 'Transactions', 1)
		
		if !lValido
			cError=this.ERROR_POP()
			this.ERROR_PUSH(cError)
			oApp.oSecurity.booklog_registrar(thisform.name,"ERROR", cInfLog+cError)
		else
			oApp.oSecurity.booklog_registrar(thisform.name,"INCLUIR", cInfLog)
		endif*/
		
		
		return $lb_valido;

	}
}
?>