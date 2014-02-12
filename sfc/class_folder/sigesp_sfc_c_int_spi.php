<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_factura
 // Autor:       - Ing. Gerardo Cordero		
 // Descripcion: - Clase que realiza los procesos de integracion con sigesp administrativo 
 //                crea comprobantes presupuestarios y contables.
 // Fecha:       - 16/02/2007     
 //////////////////////////////////////////////////////////////////////////////////////////

class sigesp_sfc_c_int_spi{
	
	var $io_archivo;    
	var $ls_codemp;
	
	function sigesp_sfc_c_int_spi(){
		require_once("sigesp_sfc_c_intarchivo.php");
        require_once("../shared/class_folder/class_funciones.php");
		
		$datoemp     =  $_SESSION["la_empresa"];
		$this->ls_codemp=$datoemp["codemp"];
		$this->io_funcion = new class_funciones();
   	}
	
	function initproc($ls_rutarc,$as_codcie){
	//print 'paso';
	   $this->io_archivo  =  new sigesp_sfc_c_intarchivo($ls_rutarc);
	}
	
	function uf_crear_comprobantespi($ls_procede,$ls_comprobante,$ld_fecha,$ls_descripcion,$ls_tipo,$ldec_monto,$ls_cod_pro,$ls_ced_bene,$ls_ctaban,$ls_codban,$ai_tipocomp){
		/////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_crear_comprobantespi
		//		   Access: public 
		//	  Description: Este m�todo inserta en el comprobante de  ingreso
		//	      Returns: boolean 
		//	   Creado Por: Ing. Gerardo Cordero                   Fecha �ltima Modificaci�n : 20/11/2007
	    ////////////////////////////////////////////////////////////////////////////////////////////////
		$ld_fecha=$this->io_funcion->uf_convertirdatetobd($ld_fecha);	
		$ls_query = "INSERT INTO sigesp_cmp(
            codemp, procede, comprobante, fecha, codban, ctaban, descripcion, 
            tipo_comp, tipo_destino, cod_pro, ced_bene, total)
            VALUES ('".$this->ls_codemp."', '".$ls_procede."','".$ls_comprobante."','".$ld_fecha."','".$ls_codban."','".$ls_ctaban."','".$ls_descripcion."','".$ai_tipocomp."','".$ls_tipo."','".$ls_cod_pro."','".$ls_ced_bene."', ".$ldec_monto.");";
		
		return $ls_query;
	}
	
	function uf_spi_insert_movimiento_ingreso($ls_procede,$ls_comprobante,$ld_fecha,$as_cuenta,$as_procede_doc,$as_documento,$as_operacion,$as_descripcion,$adec_monto,$ls_ctaban,$ls_codban)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_insert_movimiento_ingreso
		//		   Access: public 
        //	  Description: Este m�todo inserta un movimiento presupuestario en las tablas de detalle comprobante 
		//                 de ingresos 
		//	      Returns: un boolean 
		//	   Creado Por: Ing. Gerardo Cordero                   Fecha �ltima Modificaci�n : 20/11/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////
  	    $ld_fecha=$this->io_funcion->uf_convertirdatetobd($ld_fecha);	
		$ls_query = "INSERT INTO spi_dt_cmp (codemp,procede,comprobante,fecha,spi_cuenta,procede_doc,documento,operacion, descripcion,monto,orden,codban,ctaban)".
				    " VALUES('".$this->ls_codemp."','".$ls_procede."','".$ls_comprobante."',".
					" '".$ld_fecha."','".$as_cuenta."','".$as_procede_doc."','".$as_documento."','".$as_operacion."',".
					" '".$as_descripcion."',".$adec_monto.",1,'".$ls_codban."','".$ls_ctaban."');";
		return $ls_query;
	}
	
	function uf_scg_insert_movimiento($as_cuenta,$as_procede,$as_comprobante,$as_fecha,$as_codban,$as_ctaban,$as_procede_doc,$as_documento,$as_debhab,$as_descripcion,$adec_monto,$li_orden)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_procesar_insert_movimiento
		//		   Access: public 
		//       Argument: as_cuenta // Cuenta Contable
		//       		   as_procede_doc // Procedencia del documento
		//       		   as_documento // Número del Documento
		//       		   as_debhab // Tipo de Operación si es Debe ó Haber
		//       		   as_descripcion // Descripción del Documento
		//       		   adec_monto // Monto del movimiento
		//	  Description: Este método registra un movimiento final contable enla tabla movimiento  
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
        /////////////////////////////////////////////////////////////////////////////////////////////
		$as_fecha=$this->io_funcion->uf_convertirdatetobd($as_fecha);	
		$ls_query="INSERT INTO scg_dt_cmp (codemp,procede,comprobante,fecha,codban,ctaban,sc_cuenta,procede_doc,documento,debhab,descripcion,".
				"						 monto,orden) " . 
				" VALUES ('".$this->ls_codemp."','".$as_procede."','".$as_comprobante."','".$as_fecha."','".$as_codban."','".$as_ctaban."',".
				"'".$as_cuenta."', '".$as_procede_doc."','".$as_documento."','".$as_debhab."','".$as_descripcion."',".
				"".$adec_monto.",".$li_orden.");" ;
		
		return $ls_query;
	} 
	
	
	function uf_update_monto_operacion($ld_monto,$ls_operacion,$ls_spicuenta)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function: uf_update_monto_operacion
		//	Access: public 
		//	Description: Este m�todo actualiza el saldo de la cuenta en el momento correspondiente
		//	Creado Por: Ing. Gerardo Cordero                   Fecha �ltima Modificaci�n : 20/11/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////
  	    $ls_query = " UPDATE spi_cuentas SET ".$ls_operacion."=".$ls_operacion."+".$ld_monto.
		            " WHERE spi_cuenta='".$ls_spicuenta."';";
		return $ls_query;
	}
	
	function uf_insert_mov_banco($ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_fecmov,$ls_conmov,$ld_monto,$ld_monobjret,$ls_codusu)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function: uf_update_monto_operacion
		//	Access: public 
		//	Description: Este m�todo actualiza el saldo de la cuenta en el momento correspondiente
		//	Creado Por: Ing. Gerardo Cordero                   Fecha �ltima Modificaci�n : 20/11/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////
  	    
		$ls_query = "INSERT INTO scb_movbco (codemp, codban, ctaban, numdoc, codope, estmov, cod_pro, ced_bene,tipo_destino, codconmov, fecmov, conmov, nomproben, monto, estbpd, estcon, estcobing, esttra,chevau, estimpche, monobjret , monret, procede, comprobante, fecha, id_mco, emicheproc, emicheced,emichenom, emichefec, estmovint, codusu, codopeidb, aliidb, feccon, estreglib, numcarord, numpolcon,coduniadmsig, codbansig, fecordpagsig, tipdocressig, numdocressig, estmodordpag, codfuefin, forpagsig,medpagsig, codestprosig, nrocontrolop, fechaconta, fechaanula) VALUES ('".$this->ls_codemp."', '".$ls_codban."', '".$ls_ctaban."', '".$ls_numdoc."', '".$ls_codope."','N', '----------', '----------', '-', '---', '".$ls_fecmov."', '".$ls_conmov."', 'Ninguno', ".$ld_monto.",'M', 0, 0, 0, ' ', 0,".$ld_monobjret.", 0, 'SCBBCH', NULL, '1900-01-01', NULL, 0, NULL, NULL, NULL, 0, '".$ls_codusu."',' ', NULL, '1900-01-01', ' ', NULL, 0, ' ', ' ', NULL, ' ', ' ', ' ', NULL, NULL, NULL, NULL, NULL,'1900-01-01', '1900-01-01');";
					 
		return $ls_query;
	}
	
	function uf_insert_mov_bancoSCG($ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_sccuenta,$ls_debhab,$ls_codded,$ls_desmov,$ls_prodoc,$ld_monto,$ld_monobjret)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function: uf_update_monto_operacion
		//	Access: public 
		//	Description: Este m�todo actualiza el saldo de la cuenta en el momento correspondiente
		//	Creado Por: Ing. Gerardo Cordero                   Fecha �ltima Modificaci�n : 20/11/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////
  	    
		$ls_query = "INSERT INTO scb_movbco_scg(codemp, codban, ctaban, numdoc, codope, estmov, scg_cuenta, debhab,codded, documento, desmov, procede_doc, monto, monobjret) VALUES ('".$this->ls_codemp."', '".$ls_codban."', '".$ls_ctaban."', '".$ls_numdoc."', '".$ls_codope."', 'N','".$ls_sccuenta."', '".$ls_debhab."', '".$ls_codded."', '".$ls_numdoc."', '".$ls_desmov."', '".$ls_prodoc."', '".$ld_monto."', '".$ld_monobjret."');";
					 
		return $ls_query;
	}
	
	function uf_insert_mov_bancoSPI($ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_spicuenta,$ls_codoper,$ls_desmov,$ls_prodoc,$ld_monto)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function: uf_update_monto_operacion
		//	Access: public 
		//	Description: Este m�todo actualiza el saldo de la cuenta en el momento correspondiente
		//	Creado Por: Ing. Gerardo Cordero                   Fecha �ltima Modificaci�n : 20/11/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////
  	    
		$ls_query = "INSERT INTO scb_movbco_spi(codemp, codban, ctaban, numdoc, codope, estmov, spi_cuenta,documento,operacion, desmov, procede_doc, monto) VALUES ('".$this->ls_codemp."', '".$ls_codban."', '".$ls_ctaban."', '".$ls_numdoc."', '".$ls_codope."', 'N','".$ls_spicuenta."','".$ls_numdoc."', '".$ls_codoper."', '".$ls_desmov."', '".$ls_prodoc."', '".$ld_monto."');";
		
		return $ls_query;			 
	}
}
?>
