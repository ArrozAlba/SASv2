<?php
class sigesp_ins_c_reprocesar_comprobantes
{
	var $io_sql;
	var $io_message;
	var $io_function;
	var $is_msg_error;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_ins_c_reprocesar_comprobantes()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_fecha.php");
		require_once("../shared/class_folder/class_funciones.php");	
		require_once("../shared/class_folder/class_sigesp_int.php");
		require_once("../shared/class_folder/class_sigesp_int_scg.php");	
		require_once("../shared/class_folder/class_sigesp_int_spg.php");			
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_sigesp_int.php");
		require_once("../shared/class_folder/class_sigesp_int_int.php");
		require_once("../shared/class_folder/class_sigesp_int_spg.php");
		require_once("../shared/class_folder/class_sigesp_int_scg.php");
		require_once("../shared/class_folder/class_sigesp_int_spi.php");
		$io_siginc=new sigesp_include();
		$con=$io_siginc->uf_conectar();
		$this->io_sql=new class_sql($con);
		$this->io_message=new class_mensajes();
		$this->io_function=new class_funciones();
		$this->io_int_spg=new class_sigesp_int_spg();
		$this->io_seguridad=new sigesp_c_seguridad();
        $this->io_sigesp_int=new class_sigesp_int_int();
		$this->io_sigesp_int_spg = new class_sigesp_int_spg();
		$this->io_sigesp_int_scg = new class_sigesp_int_scg();		
		$this->io_sigesp_int_spi = new class_sigesp_int_spi();		
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_ins_c_reprocesar_comprobantes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reprocesar_comprobantes_scb($aa_seguridad)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////
		// 	     Function: uf_reprocesar_comprobantes_scb
		// 	       Access: public
		//      Arguments: $aa_seguridad
		//	      Returns: Boolean
		//    Description: Esta funcion verifica que los comprobantes de banco que están contabilizados tambien
		//				   se encuentren en contabilidad, presupuesto de gasto y presupuesto de ingreso en caso
		//				   de que no se encuentren los genera
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 											Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codban,ctaban,estmov,numdoc,fecmov,conmov,codope,tipo_destino,ced_bene,cod_pro,conmov ".
                "  FROM scb_movbco ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND estmov='C' ".
				"   AND estmodordpag<>'CM'";
		$this->io_sql->begin_transaction();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_message->message("CLASE->Reprocesar Comprobantes MÉTODO->uf_reprocesar_comprobantes_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		else
		{
			$li_i=0;
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$li_i=$li_i+1;
				$ls_codban=$rs_data->fields["codban"];
				$ls_ctaban=$rs_data->fields["ctaban"];
				$ls_estmov=$rs_data->fields["estmov"];
				$ls_numdoc=$rs_data->fields["numdoc"];
				$ls_fecmov=$rs_data->fields["fecmov"];
				$ls_conmov=$rs_data->fields["conmov"];
				$ls_codope=$rs_data->fields["codope"];
				$ls_tipo=$rs_data->fields["tipo_destino"];  		
				$ls_ced_bene=$rs_data->fields["ced_bene"];
				$ls_cod_pro=$rs_data->fields["cod_pro"];
				$ls_descripcion=$rs_data->fields["conmov"];
			    $ls_procede="SCBB".$ls_codope;
				$ls_comprobante=$ls_numdoc;
				$codban="---";
				$ctaban="-------------------------";
			    $lb_existe=$this->io_sigesp_int->uf_obtener_comprobante($this->ls_codemp,$ls_procede,$ls_comprobante,&$ls_fecmov,
																		$codban,$ctaban,&$ls_tipo,&$ls_ced_bene,&$ls_cod_pro);
				if($lb_existe===false)
				{
					$lb_valido=$this->uf_insertar_movimiento_scb($this->ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,
																 $ls_estmov,'N',$ls_procede,$ls_comprobante,$ls_fecmov,'1900-01-01');
					if($lb_valido)
					{
						$lb_valido=$this->uf_delete_movimiento_scb($this->ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,
																   $ls_estmov);
					}
				}
				else
				{
					$lb_valido=$this->uf_verificar_gasto_scb($ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_estmov,$ls_fecmov,$ls_procede,$ls_descripcion);
					if($lb_valido)
					{
						$lb_valido=$this->uf_verificar_contabilidad_scb($ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_estmov,$ls_fecmov,$ls_procede,$ls_descripcion);					
					}
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso los comprobantes descuadrados del sistema de Caja y Banco";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_reprocesar_comprobantes_scb
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_insertar_movimiento_scb($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$as_estmov_new,
										$as_procede,$as_comprobante,$adt_fecmov,$adt_fecha)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insertar_movimiento_scb
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
		$ls_sql = "INSERT INTO scb_movbco (codemp,codban,ctaban,numdoc,codope,estmov,cod_pro,ced_bene,".
		          "                        tipo_destino, codconmov, fecmov, conmov, nomproben, monto, ".
				  "                        estbpd, estcon, estcobing, esttra, chevau, estimpche, ".
				  "                        monobjret, monret, procede, comprobante, fecha, id_mco,".
				  "                        emicheproc, emicheced, emichenom, emichefec, estmovint, ".
				  "                        codusu, codopeidb, aliidb, feccon, estreglib, numcarord,".
				  "                        numpolcon,coduniadmsig,codbansig,fecordpagsig,tipdocressig,".
				  "                        numdocressig,estmodordpag,codfuefin,forpagsig,medpagsig,codestprosig,fechaconta,fechaanula ) ".
				  " SELECT codemp,codban,ctaban,numdoc,codope,'".$as_estmov_new."',cod_pro,ced_bene,".
		          "        tipo_destino, codconmov, '".$adt_fecmov."', conmov, nomproben, monto, ".
				  "        estbpd, estcon, estcobing, esttra, chevau, estimpche, ".
				  "        monobjret, monret,'".$as_procede."','".$as_comprobante."','".$adt_fecha."',id_mco,".
				  "        emicheproc, emicheced, emichenom, emichefec, estmovint, ".
				  "        codusu, codopeidb, aliidb, feccon, estreglib, numcarord, ".
				  "        numpolcon,coduniadmsig,codbansig,fecordpagsig,tipdocressig,".
				  "        numdocressig,estmodordpag,codfuefin,forpagsig,medpagsig,codestprosig,fechaconta,fechaanula	".			  
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
           	$this->io_message->message("CLASE->Instala MÉTODO->uf_insertar_movimiento_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
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
           	$this->io_message->message("CLASE->Instala MÉTODO->uf_insertar_movimiento_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
        // transferencia al nuevo registro de banco detalle de gastos
		$ls_sql = " INSERT INTO scb_movbco_spg (codemp,codban,ctaban,numdoc,codope,estmov,codestpro,estcla,".
		          "                             spg_cuenta,operacion,documento,desmov,procede_doc,monto) ".
				  " SELECT codemp,codban,ctaban,numdoc,codope,'".$as_estmov_new."',codestpro,estcla,".
				  "        spg_cuenta,operacion,documento,desmov,procede_doc,monto ".
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
           	$this->io_message->message("CLASE->Instala MÉTODO->uf_insertar_movimiento_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
        // transferencia al nuevo registro de banco detalle de gastos
		$ls_sql = " INSERT INTO scb_movbco_spgop (codemp,codban,ctaban,numdoc,codope,estmov,codestpro,estcla,".
		          "                             spg_cuenta,operacion,documento,coduniadm,desmov,procede_doc,monto) ".
				  "SELECT codemp,codban,ctaban,numdoc,codope,'".$as_estmov_new."',codestpro,estcla,spg_cuenta,".
				  "        operacion,documento,coduniadm,desmov,procede_doc,monto ".
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
           	$this->io_message->message("CLASE->Instala MÉTODO->uf_insertar_movimiento_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
        // transferencia al nuevo registro de banco detalle de ingresos
		$ls_sql = " INSERT INTO scb_movbco_spi (codemp,codban,ctaban,numdoc,codope,estmov,spi_cuenta,".
		          "                             documento,operacion,desmov,procede_doc,monto) ".
				  " SELECT codemp,codban,ctaban,numdoc,codope,'".$as_estmov_new."',spi_cuenta,".
				  "        documento,operacion,desmov,procede_doc,monto ".
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
           	$this->io_message->message("CLASE->Instala MÉTODO->uf_insertar_movimiento_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
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
           	$this->io_msg->message("CLASE->Instala SCB MÉTODO->uf_insertar_movimiento_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
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
           		$this->io_message->message("CLASE->Instala MÉTODO->uf_insertar_movimiento_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				return false;
			}
		}
		return $lb_valido;
	}// end function uf_insertar_movimiento_scb
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_movimiento_scb($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_movimiento_scb
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
           	$this->io_message->message("CLASE->Instala MÉTODO->uf_delete_movimiento_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
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
           	$this->io_message->message("CLASE->Instala MÉTODO->uf_delete_movimiento_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
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
           	$this->io_message->message("CLASE->Instala MÉTODO->uf_delete_movimiento_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
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
           	$this->io_message->message("CLASE->Instala MÉTODO->uf_delete_movimiento_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
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
           	$this->io_message->message("CLASE->Instala MÉTODO->uf_delete_movimiento_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
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
           	$this->io_message->message("CLASE->Instala MÉTODO->uf_delete_movimiento_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
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
           	$this->io_message->message("CLASE->Instala MÉTODO->uf_delete_movimiento_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		return $lb_valido;
	}// end function uf_delete_movimiento_scb
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_gasto_scb($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$as_fecmov,$as_procede,$as_descripcion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_gasto_scb
		//		   Access: private
		//	    Arguments: as_descripcion // Descripción del comprobante
		//	      Returns: lb_valido True si se encontro el movimiento ó false si no se encontro
		//	  Description: Funcion que obtiene el los movimientos de presupuesto y los agrega al datastored
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 02/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * ".
                "  FROM scb_movbco_spg ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
            $this->io_message->message("CLASE->Instala MÉTODO->uf_verificar_gasto_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{   
			$li_orden=-1;              
			while((!$rs_data->EOF) and ($lb_valido))
		    {
				$li_orden=$li_orden+1;
			    $ls_codestpro=$rs_data->fields["codestpro"];
			    $ls_estcla=$rs_data->fields["estcla"];
				$ls_codestpro1=substr($ls_codestpro,0,25);
				$ls_codestpro2=substr($ls_codestpro,25,25);
				$ls_codestpro3=substr($ls_codestpro,50,25);
				$ls_codestpro4=substr($ls_codestpro,75,25);
				$ls_codestpro5=substr($ls_codestpro,100,25);
				$ls_spg_cuenta=$rs_data->fields["spg_cuenta"];
				$ldec_monto=$rs_data->fields["monto"];
			    $ls_procede_doc=$rs_data->fields["procede_doc"];
 			    $ls_documento=$rs_data->fields["documento"];
				$ls_mensaje=$rs_data->fields["operacion"];
				$ls_spg_cuenta=$this->io_sigesp_int_spg->uf_spg_pad_cuenta($ls_spg_cuenta);
				$ls_sql="SELECT spg_cuenta,monto,orden ".
						"  FROM spg_dt_cmp ".		
						" WHERE codemp = '".$this->ls_codemp."' ".
						"   AND codestpro1 = '".$ls_codestpro1."' ".
						"   AND codestpro2 = '".$ls_codestpro2."' ". 
						"   AND codestpro3 = '".$ls_codestpro3."' ".
						"   AND codestpro4 = '".$ls_codestpro4."' ".
						"   AND codestpro5 = '".$ls_codestpro5."'  ".
						"   AND estcla = '".$ls_estcla."'  ".
						"   AND procede = '".$as_procede."' ".
						"   AND comprobante = '".$as_numdoc."' ".
						"   AND fecha = '".$as_fecmov."' ".
						"   AND documento = '".$ls_documento."' ".
						"   AND spg_cuenta = '".$ls_spg_cuenta."'  ".
						"   AND operacion = '".$ls_mensaje."' "; 
				$rs_data1=$this->io_sql->select($ls_sql);
				if($rs_data1===false)
				{   
					$this->io_message->message("CLASE->Reprocesar Comprobantes MÉTODO->uf_verificar_gasto_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					return false;
				}
				else
				{                 
					if(!($row=$this->io_sql->fetch_row($rs_data1)))
					{
						$ls_sql="INSERT INTO spg_dt_cmp (codemp,procede,comprobante,fecha,codestpro1,codestpro2,codestpro3,codestpro4,".
								"            codestpro5,estcla,spg_cuenta,procede_doc,documento,operacion,descripcion,monto,orden)".
								" VALUES('".$this->ls_codemp."','".$as_procede."','".$as_numdoc."','".$as_fecmov."','".$ls_codestpro1."',".
								"        '".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_estcla."', ".
								"        '".$ls_spg_cuenta."','".$ls_procede_doc."','".$ls_documento."','".$ls_mensaje."','".$as_descripcion."',".
								"        '".$ldec_monto."',".$li_orden.")"; 
						$li_row=$this->io_sql->execute($ls_sql);
						if($li_row===false)
						{   
							$this->io_message->message("CLASE->Reprocesar Comprobantes MÉTODO->uf_verificar_gasto_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
							return false;
						}
					}
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
    }// end function uf_verificar_gasto_scb
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_contabilidad_scb($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$as_fecmov,$as_procede,$as_descripcion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_contabilidad_scb
		//		   Access: private
		//	    Arguments: as_descripcion // Descripción del comprobante
		//	      Returns: lb_valido True si se encontro el movimiento ó false si no se encontro
		//	  Description: Funcion que obtiene el los movimientos de presupuesto y los agrega al datastored
		//	   Creado Por: Ing. Yesenia Moreno	
		// Modificado Por: 												Fecha Última Modificación : 11/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * ".
                "  FROM scb_movbco_scg ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
            $this->io_message->message("CLASE->Instala MÉTODO->uf_verificar_contabilidad_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{   
			$li_orden=-1;              
			while((!$rs_data->EOF) and ($lb_valido))
		    {
				$li_orden=$li_orden+1;
				$ls_scg_cuenta=$rs_data->fields["scg_cuenta"];
                $ls_debhab=$rs_data->fields["debhab"];				
				$ldec_monto=$rs_data->fields["monto"];				
				$ls_documento=$rs_data->fields["documento"];
			    $ls_procede_doc=$rs_data->fields["procede_doc"];				
				$ls_scg_cuenta=$this->io_sigesp_int_scg->uf_pad_scg_cuenta($_SESSION["la_empresa"]["formcont"],$ls_scg_cuenta);
				$ls_sql="SELECT monto,orden".
						"  FROM scg_dt_cmp".
						" WHERE codemp='".$this->ls_codemp."' ".
						"   AND procede='".$as_procede."' ".
						"   AND comprobante='".$as_numdoc."' ".
						"   AND fecha='".$as_fecmov."' ".
						"   AND documento ='".$ls_documento."' ".
						"   AND sc_cuenta='".$ls_scg_cuenta."' ".
						"   AND debhab='".$ls_debhab."'";
				$rs_data1=$this->io_sql->select($ls_sql);
				if($rs_data1===false)
				{   
					$this->io_message->message("CLASE->Reprocesar Comprobantes MÉTODO->uf_verificar_gasto_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					return false;
				}
				else
				{                 
					if(!($row=$this->io_sql->fetch_row($rs_data1)))
					{
						$ls_sql="INSERT INTO scg_dt_cmp (codemp,procede,comprobante,fecha,sc_cuenta,procede_doc,documento,debhab, descripcion,monto,orden) ". 
								" VALUES ('".$this->ls_codemp."','".$as_procede."','".$as_numdoc."','" .$as_fecmov."',".
								"'".$ls_scg_cuenta."', '".$ls_procede_doc."','".$ls_documento."','".$ls_debhab."',".
								"'".$as_descripcion."',".$ldec_monto.",".$li_orden.")" ;
						$li_row=$this->io_sql->execute($ls_sql);
						if($li_row===false)
						{   
							$this->io_message->message("CLASE->Reprocesar Comprobantes MÉTODO->uf_verificar_gasto_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
							return false;
						}
					}
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
    }// end function uf_verificar_contabilidad_scb
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reprocesar_fecha_comprobante_sep($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reprocesar_fecha_comprobante_sep
		//		   Access: public
		//	    Arguments: 
		//	      Returns: lb_valido True si se actualizó sin ningún problema
		//	  Description: Funcion que obtiene el los comprobantes de presupuesto y le actualiza las fechas
		//	   Creado Por: Ing. Yesenia Moreno	
		// Modificado Por: 												Fecha Última Modificación : 19/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// para la fecha de contabilización
		$this->io_sql->begin_transaction();
		$lb_valido=true;
		$ls_sql="SELECT sep_solicitud.numsol, sigesp_cmp.fecha ".
				"  FROM sigesp_cmp, sep_solicitud ".
				" WHERE sigesp_cmp.codemp = '".$this->ls_codemp."' ".
				"   AND sigesp_cmp.procede ='SEPSPC' ".
				"   AND sigesp_cmp.codemp = sep_solicitud.codemp ".
				"   AND sigesp_cmp.comprobante = sep_solicitud.numsol".
				" ORDER BY sep_solicitud.numsol";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_message->message("Problemas al ejecutar actualización");	
			$lb_valido=false;
		}
		else
		{
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ld_fecha=$rs_data->fields["fecha"];
				$ls_numsol=$rs_data->fields["numsol"];
				$ls_sql="UPDATE sep_solicitud ".
						"   SET fechaconta = '".$ld_fecha."' ".
						" WHERE codemp = '".$this->ls_codemp."' ".
						"   AND numsol = '".$ls_numsol."' ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_message->message("Problemas al ejecutar actualización");	
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{ // para la fecha de anulación
			$ls_sql="SELECT sep_solicitud.numsol, sigesp_cmp.fecha ".
					"  FROM sigesp_cmp, sep_solicitud ".
					" WHERE sigesp_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND (sigesp_cmp.procede ='SEPSPA' OR sigesp_cmp.procede='SEPRPC')".
					"   AND sigesp_cmp.codemp = sep_solicitud.codemp ".
					"   AND sigesp_cmp.comprobante = sep_solicitud.numsol".
					" ORDER BY sep_solicitud.numsol";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualización");	
				$lb_valido=false;
			}
			else
			{
				while((!$rs_data->EOF)&&($lb_valido))
				{
					$ld_fecha=$rs_data->fields["fecha"];
					$ls_numsol=$rs_data->fields["numsol"];
					$ls_sql="UPDATE sep_solicitud ".
							"   SET fechaanula = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND numsol = '".$ls_numsol."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualización");		
					}
					$rs_data->MoveNext();
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso la fecha de los comprobantes del sistema de Solicitud de Ejecución Presupuestaria";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_reprocesar_fecha_comprobante_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reprocesar_fecha_comprobante_soc($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reprocesar_fecha_comprobante_soc
		//		   Access: public
		//	    Arguments: 
		//	      Returns: lb_valido True si se actualizó sin ningún problema
		//	  Description: Funcion que obtiene el los comprobantes de presupuesto y le actualiza las fechas
		//	   Creado Por: Ing. Yesenia Moreno	
		// Modificado Por: 												Fecha Última Modificación : 19/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// para la fecha de contabilización
		$this->io_sql->begin_transaction();
		$lb_valido=true;
		$ls_sql="SELECT soc_ordencompra.numordcom, sigesp_cmp.fecha, sigesp_cmp.procede ".
				"  FROM sigesp_cmp, soc_ordencompra ".
				" WHERE sigesp_cmp.codemp = '".$this->ls_codemp."' ".
				"   AND (sigesp_cmp.procede ='SOCCOS' OR sigesp_cmp.procede ='SOCCOC')".
				"   AND sigesp_cmp.codemp = soc_ordencompra.codemp ".
				"   AND sigesp_cmp.comprobante = soc_ordencompra.numordcom ".
				" ORDER BY soc_ordencompra.numordcom, sigesp_cmp.procede ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_message->message("Problemas al ejecutar actualización");	
			$lb_valido=false;
		}
		else
		{
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ld_fecha=$rs_data->fields["fecha"];
				$ls_numordcom=$rs_data->fields["numordcom"];
				$ls_procede=$rs_data->fields["procede"];
				if($ls_procede=="SOCCOS")
				{
					$ls_estcondat="S";
				}
				if($ls_procede=="SOCCOC")
				{
					$ls_estcondat="B";
				}
				$ls_sql="UPDATE soc_ordencompra ".
						"   SET fechaconta = '".$ld_fecha."' ".
						" WHERE codemp = '".$this->ls_codemp."' ".
						"   AND numordcom = '".$ls_numordcom."' ".
						"   AND estcondat = '".$ls_estcondat."' ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_message->message("Problemas al ejecutar actualización");	
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{ // para la fecha de anulación
			$ls_sql="SELECT soc_ordencompra.numordcom, sigesp_cmp.fecha, sigesp_cmp.procede ".
					"  FROM sigesp_cmp, soc_ordencompra ".
					" WHERE sigesp_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND (sigesp_cmp.procede ='SOCAOS' OR sigesp_cmp.procede='SOCAOC')".
					"   AND sigesp_cmp.codemp = soc_ordencompra.codemp ".
					"   AND sigesp_cmp.comprobante = soc_ordencompra.numordcom".
					" ORDER BY soc_ordencompra.numordcom, soc_ordencompra.estcondat";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualización");	
				$lb_valido=false;
			}
			else
			{
				while((!$rs_data->EOF)&&($lb_valido))
				{
					$ld_fecha=$rs_data->fields["fecha"];
					$ls_numordcom=$rs_data->fields["numordcom"];
					$ls_procede=$rs_data->fields["procede"];
					if($ls_procede=="SOCAOS")
					{
						$ls_estcondat="S";
					}
					if($ls_procede=="SOCAOC")
					{
						$ls_estcondat="B";
					}
					$ls_sql="UPDATE soc_ordencompra ".
							"   SET fechaanula = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND numordcom = '".$ls_numordcom."' ".
							"   AND estcondat = '".$ls_estcondat."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualización");		
					}
					$rs_data->MoveNext();
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso la fecha de los comprobantes del sistema de Compras";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_reprocesar_fecha_comprobante_soc
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reprocesar_fecha_comprobante_cxp($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reprocesar_fecha_comprobante_cxp
		//		   Access: public
		//	    Arguments: 
		//	      Returns: lb_valido True si se actualizó sin ningún problema
		//	  Description: Funcion que obtiene el los comprobantes de presupuesto y le actualiza las fechas
		//	   Creado Por: Ing. Yesenia Moreno	
		// Modificado Por: 												Fecha Última Modificación : 19/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// para la fecha de contabilización
		$this->io_sql->begin_transaction();
		$lb_valido=true;
		$ls_sql="SELECT cxp_solicitudes.numsol, sigesp_cmp.fecha, sigesp_cmp.procede ".
				"  FROM sigesp_cmp, cxp_solicitudes ".
				" WHERE sigesp_cmp.codemp = '".$this->ls_codemp."' ".
				"   AND sigesp_cmp.procede ='CXPSOP' ".
				"   AND sigesp_cmp.codemp = cxp_solicitudes.codemp ".
				"   AND sigesp_cmp.comprobante = cxp_solicitudes.numsol ".
				" ORDER BY cxp_solicitudes.numsol ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_message->message("Problemas al ejecutar actualización");	
			$lb_valido=false;
		}
		else
		{
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ld_fecha=$rs_data->fields["fecha"];
				$ls_numsol=$rs_data->fields["numsol"];
				$ls_sql="UPDATE cxp_solicitudes ".
						"   SET fechaconta = '".$ld_fecha."' ".
						" WHERE codemp = '".$this->ls_codemp."' ".
						"   AND numsol = '".$ls_numsol."' ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_message->message("Problemas al ejecutar actualización");	
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{ // para la fecha de anulación
			$ls_sql="SELECT cxp_solicitudes.numsol, sigesp_cmp.fecha, sigesp_cmp.procede ".
					"  FROM sigesp_cmp, cxp_solicitudes ".
					" WHERE sigesp_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND sigesp_cmp.procede ='CXPAOP' ".
					"   AND sigesp_cmp.codemp = cxp_solicitudes.codemp ".
					"   AND sigesp_cmp.comprobante = cxp_solicitudes.numsol ".
					" ORDER BY cxp_solicitudes.numsol ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualización");	
				$lb_valido=false;
			}
			else
			{
				while((!$rs_data->EOF)&&($lb_valido))
				{
					$ld_fecha=$rs_data->fields["fecha"];
					$ls_numsol=$rs_data->fields["numsol"];
					$ls_sql="UPDATE cxp_solicitudes ".
							"   SET fechaanula = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND numsol = '".$ls_numsol."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualización");		
					}
					$rs_data->MoveNext();
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{ // para la fecha de contabilización de las notas de Débito y crédito
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQLT":
					$ls_criterio="   AND sigesp_cmp.comprobante like CONCAT('%',cxp_sol_dc.numdc) ";
					break;
				
				case "POSTGRES":
					$ls_criterio="   AND sigesp_cmp.comprobante like '%'||cxp_sol_dc.numdc ";
					break;
			}
			$ls_sql="SELECT cxp_sol_dc.numdc, sigesp_cmp.fecha, sigesp_cmp.procede ".
					"  FROM sigesp_cmp, cxp_sol_dc ".
					" WHERE sigesp_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND (sigesp_cmp.procede ='CXPNOD' OR sigesp_cmp.procede ='CXPNOC')".
					"   AND sigesp_cmp.codemp = cxp_sol_dc.codemp ".
					$ls_criterio;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualización");	
				$lb_valido=false;
			}
			else
			{
				while(!$rs_data->EOF)
				{
					$ld_fecha=$rs_data->fields["fecha"];
					$ls_numdc=$rs_data->fields["numdc"];
					$ls_procede=$rs_data->fields["procede"];
					if($ls_procede=="CXPNOD")
					{
						$ls_codope="D";
					}
					if($ls_procede=="CXPNOC")
					{
						$ls_codope="C";
					}
					$ls_sql="UPDATE cxp_sol_dc ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND numdc = '".$ls_numdc."' ".
							"   AND codope = '".$ls_codope."'";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualización");	
					}
					$rs_data->MoveNext();
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso la fecha de los comprobantes del sistema de Cuentas por Pagar";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_reprocesar_fecha_comprobante_cxp
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reprocesar_fecha_comprobante_scb($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reprocesar_fecha_comprobante_scb
		//		   Access: public
		//	    Arguments: 
		//	      Returns: lb_valido True si se actualizó sin ningún problema
		//	  Description: Funcion que obtiene el los comprobantes de presupuesto y le actualiza las fechas
		//	   Creado Por: Ing. Yesenia Moreno	
		// Modificado Por: 												Fecha Última Modificación : 20/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// para la fecha de contabilización
		$this->io_sql->begin_transaction();
		$lb_valido=true;
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_criterio="   AND sigesp_cmp.comprobante like CONCAT('%',scb_movbco.numdoc) ";
				break;
			
			case "POSTGRES":
				$ls_criterio="   AND sigesp_cmp.comprobante like '%'||scb_movbco.numdoc ";
				break;
		}
		$ls_sql="SELECT scb_movbco.numdoc, sigesp_cmp.fecha, sigesp_cmp.procede, sigesp_cmp.codban, sigesp_cmp.ctaban ".
				"  FROM sigesp_cmp, scb_movbco ".
				" WHERE sigesp_cmp.codemp = '".$this->ls_codemp."' ".
				"   AND (sigesp_cmp.procede ='SCBBCH' OR sigesp_cmp.procede ='SCBBDP' OR ".
				"		 sigesp_cmp.procede ='SCBBNC' OR sigesp_cmp.procede ='SCBBND' OR ".
				"		 sigesp_cmp.procede ='SCBOPD') ".
				"   AND sigesp_cmp.codemp = scb_movbco.codemp ".
				$ls_criterio;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_message->message("Problemas al ejecutar actualización");	
			$lb_valido=false;
		}
		else
		{
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ld_fecha=$rs_data->fields["fecha"];
				$ls_numdoc=$rs_data->fields["numdoc"];
				$ls_procede=$rs_data->fields["procede"];
				$ls_codban=$rs_data->fields["codban"];
				$ls_ctaban=$rs_data->fields["ctaban"];
				switch($ls_procede)
				{
					case "SCBBCH":
						$ls_codope="CH";
						break;
					case "SCBBDP":
						$ls_codope="DP";
						break;
					case "SCBBNC":
						$ls_codope="NC";
						break;
					case "SCBBND":
						$ls_codope="ND";
						break;
					case "SCBOPD":
						$ls_codope="OP";
						break;
				}
				$ls_sql="UPDATE scb_movbco ".
						"   SET fechaconta = '".$ld_fecha."' ".
						" WHERE codemp = '".$this->ls_codemp."' ".
						"   AND numdoc = '".$ls_numdoc."' ".
						"   AND codban = '".$ls_codban."' ".
						"   AND ctaban = '".$ls_ctaban."' ".
						"   AND codope = '".$ls_codope."'";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_message->message("Problemas al ejecutar actualización");	
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{ // para la fecha de anulación
			$ls_sql="SELECT scb_movbco.numdoc, sigesp_cmp.fecha, sigesp_cmp.procede, sigesp_cmp.codban, sigesp_cmp.ctaban ".
					"  FROM sigesp_cmp, scb_movbco ".
					" WHERE sigesp_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND (sigesp_cmp.procede ='SCBBAH' OR sigesp_cmp.procede ='SCBBAP' OR sigesp_cmp.procede ='SCBBAC' OR sigesp_cmp.procede ='SCBBAD') ".
					"   AND sigesp_cmp.codemp = scb_movbco.codemp ".
					"   AND sigesp_cmp.comprobante = scb_movbco.numdoc ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualización");	
				$lb_valido=false;
			}
			else
			{
				while((!$rs_data->EOF)&&($lb_valido))
				{
					$ld_fecha=$rs_data->fields["fecha"];
					$ls_numdoc=$rs_data->fields["numdoc"];
					$ls_procede=$rs_data->fields["procede"];
					$ls_codban=$rs_data->fields["codban"];
					$ls_ctaban=$rs_data->fields["ctaban"];
					switch($ls_procede)
					{
						case "SCBBAH":
							$ls_codope="CH";
							break;
						case "SCBBAP":
							$ls_codope="DP";
							break;
						case "SCBBAC":
							$ls_codope="NC";
							break;
						case "SCBBAD":
							$ls_codope="ND";
							break;
					}
					$ls_sql="UPDATE scb_movbco ".
							"   SET fechaanula = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND numdoc = '".$ls_numdoc."' ".
							"   AND codban = '".$ls_codban."' ".
							"   AND ctaban = '".$ls_ctaban."' ".
							"   AND codope = '".$ls_codope."'";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualización");		
					}
					$rs_data->MoveNext();
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_criterio="   AND sigesp_cmp.comprobante like CONCAT('%',scb_movcol.numcol) ";
				break;
			
			case "POSTGRES":
				$ls_criterio="   AND sigesp_cmp.comprobante like '%'||scb_movcol.numcol ";
				break;
		}
		if($lb_valido)
		{ // para la fecha de contabilización de las colocaciones
			$ls_sql="SELECT scb_movcol.numcol, sigesp_cmp.fecha, sigesp_cmp.procede, sigesp_cmp.codban, sigesp_cmp.ctaban ".
					"  FROM sigesp_cmp, scb_movcol ".
					" WHERE sigesp_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND (sigesp_cmp.procede ='SCBCNC' OR sigesp_cmp.procede ='SCBCND' OR ".
					"		 sigesp_cmp.procede ='SCBCDP') ".
					"   AND sigesp_cmp.codemp = scb_movcol.codemp ".
					$ls_criterio;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualización");	
				$lb_valido=false;
			}
			else
			{
				while(!$rs_data->EOF)
				{
					$ld_fecha=$rs_data->fields["fecha"];
					$ls_numcol=$rs_data->fields["numcol"];
					$ls_procede=$rs_data->fields["procede"];
					$ls_codban=$rs_data->fields["codban"];
					$ls_ctaban=$rs_data->fields["ctaban"];
					switch($ls_procede)
					{
						case "SCBCNC":
							$ls_codope="NC";
							break;
						case "SCBCND":
							$ls_codope="ND";
							break;
						case "SCBCDP":
							$ls_codope="DP";
							break;
					}
					$ls_sql="UPDATE scb_movcol ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND numcol = '".$ls_numcol."' ".
							"   AND codban = '".$ls_codban."' ".
							"   AND ctaban = '".$ls_ctaban."' ".
							"   AND codope = '".$ls_codope."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualización");	
					}
					$rs_data->MoveNext();
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso la fecha de los comprobantes del sistema de Caja y Banco ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_reprocesar_fecha_comprobante_scb
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reprocesar_fecha_comprobante_sob($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reprocesar_fecha_comprobante_sob
		//		   Access: public
		//	    Arguments: 
		//	      Returns: lb_valido True si se actualizó sin ningún problema
		//	  Description: Funcion que obtiene el los comprobantes de presupuesto y le actualiza las fechas
		//	   Creado Por: Ing. Yesenia Moreno	
		// Modificado Por: 												Fecha Última Modificación : 20/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// para la fecha de contabilización
		$this->io_sql->begin_transaction();
		$lb_valido=true;
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_criterio="   AND sigesp_cmp.comprobante like CONCAT('%',sob_asignacion.codasi) ";
				break;
			
			case "POSTGRES":
				$ls_criterio="   AND sigesp_cmp.comprobante like '%'||sob_asignacion.codasi ";
				break;
		}
		$ls_sql="SELECT sob_asignacion.codasi, sigesp_cmp.fecha, sigesp_cmp.procede ".
				"  FROM sigesp_cmp, sob_asignacion ".
				" WHERE sigesp_cmp.codemp = '".$this->ls_codemp."' ".
				"   AND sigesp_cmp.procede ='SOBASI' ".
				"   AND sigesp_cmp.codemp = sob_asignacion.codemp ".
				$ls_criterio;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_message->message("Problemas al ejecutar actualización");	
			$lb_valido=false;
		}
		else
		{
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ld_fecha=$rs_data->fields["fecha"];
				$ls_codasi=$rs_data->fields["codasi"];
				$ls_sql="UPDATE sob_asignacion ".
						"   SET fechaconta = '".$ld_fecha."' ".
						" WHERE codemp = '".$this->ls_codemp."' ".
						"   AND codasi = '".$ls_codasi."' ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_message->message("Problemas al ejecutar actualización");	
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{ // para la fecha de anulación
			$ls_sql="SELECT sob_asignacion.codasi, sigesp_cmp.fecha, sigesp_cmp.procede ".
					"  FROM sigesp_cmp, sob_asignacion ".
					" WHERE sigesp_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND (sigesp_cmp.procede ='SOBRAS' OR sigesp_cmp.procede='SOBRPC') ".
					"   AND sigesp_cmp.codemp = sob_asignacion.codemp ".
					$ls_criterio;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualización");	
				$lb_valido=false;
			}
			else
			{
				while((!$rs_data->EOF)&&($lb_valido))
				{
					$ld_fecha=$rs_data->fields["fecha"];
					$ls_codasi=$rs_data->fields["codasi"];
					$ls_sql="UPDATE sob_asignacion ".
							"   SET fechaanula = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND codasi = '".$ls_codasi."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualización");		
					}
					$rs_data->MoveNext();
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_criterio="   AND sigesp_cmp.comprobante like CONCAT('%',sob_contrato.codcon) ";
				break;
			
			case "POSTGRES":
				$ls_criterio="   AND sigesp_cmp.comprobante like '%'||sob_contrato.codcon ";
				break;
		}
		if($lb_valido)
		{ // para la fecha de contabilización de los contratos
			$ls_sql="SELECT sob_contrato.codcon, sigesp_cmp.fecha, sigesp_cmp.procede ".
					"  FROM sigesp_cmp, sob_contrato ".
					" WHERE sigesp_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND sigesp_cmp.procede ='SOBCON' ".
					"   AND sigesp_cmp.codemp = sob_contrato.codemp ".
					$ls_criterio;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualización");	
				$lb_valido=false;
			}
			else
			{
				while(!$rs_data->EOF)
				{
					$ld_fecha=$rs_data->fields["fecha"];
					$ls_codcon=$rs_data->fields["codcon"];
					$ls_sql="UPDATE sob_contrato ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND codcon = '".$ls_codcon."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualización");	
					}
					$rs_data->MoveNext();
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{ // para la fecha de anulación de los contratos
			$ls_sql="SELECT sob_contrato.codcon, sigesp_cmp.fecha, sigesp_cmp.procede ".
					"  FROM sigesp_cmp, sob_contrato ".
					" WHERE sigesp_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND sigesp_cmp.procede ='SOBACO' ".
					"   AND sigesp_cmp.codemp = sob_contrato.codemp ".
					$ls_criterio;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualización");	
				$lb_valido=false;
			}
			else
			{
				while(!$rs_data->EOF)
				{
					$ld_fecha=$rs_data->fields["fecha"];
					$ls_codcon=$rs_data->fields["codcon"];
					$ls_sql="UPDATE sob_contrato ".
							"   SET fechaanula = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND codcon = '".$ls_codcon."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualización");	
					}
					$rs_data->MoveNext();
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso la fecha de los comprobantes del sistema de Obras ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_reprocesar_fecha_comprobante_sob
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reprocesar_fecha_comprobante_sno($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reprocesar_fecha_comprobante_sno
		//		   Access: public
		//	    Arguments: 
		//	      Returns: lb_valido True si se actualizó sin ningún problema
		//	  Description: Funcion que obtiene el los comprobantes de presupuesto y le actualiza las fechas
		//	   Creado Por: Ing. Yesenia Moreno	
		// Modificado Por: 												Fecha Última Modificación : 20/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// para la fecha de contabilización
		$this->io_sql->begin_transaction();
		$lb_valido=true;
		$ls_sql="SELECT sno_dt_scg.codcom, sigesp_cmp.fecha, sigesp_cmp.procede ".
				"  FROM sigesp_cmp, sno_dt_scg ".
				" WHERE sigesp_cmp.codemp = '".$this->ls_codemp."' ".
				"   AND sigesp_cmp.procede ='SNOCNO' ".
				"	AND sno_dt_scg.tipnom = 'N' ".
				"   AND sigesp_cmp.codemp = sno_dt_scg.codemp ".
				"	AND sigesp_cmp.comprobante = sno_dt_scg.codcom ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_message->message("Problemas al ejecutar actualización");	
			$lb_valido=false;
		}
		else
		{
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ld_fecha=$rs_data->fields["fecha"];
				$ls_codcom=$rs_data->fields["codcom"];
				$ls_sql="UPDATE sno_dt_scg ".
						"   SET fechaconta = '".$ld_fecha."' ".
						" WHERE codemp = '".$this->ls_codemp."' ".
						"   AND codcom = '".$ls_codcom."' ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_message->message("Problemas al ejecutar actualización");	
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{ // para la fecha de contabilización de los aportes
			$ls_sql="SELECT sno_dt_scg.codcomapo, sigesp_cmp.fecha, sigesp_cmp.procede ".
					"  FROM sigesp_cmp, sno_dt_scg ".
					" WHERE sigesp_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND sigesp_cmp.procede ='SNOCNO' ".
					"	AND sno_dt_scg.tipnom = 'A' ".
					"   AND sigesp_cmp.codemp = sno_dt_scg.codemp ".
					"	AND sigesp_cmp.comprobante = sno_dt_scg.codcomapo ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualización");	
				$lb_valido=false;
			}
			else
			{
				while((!$rs_data->EOF)&&($lb_valido))
				{
					$ld_fecha=$rs_data->fields["fecha"];
					$ls_codcom=$rs_data->fields["codcomapo"];
					$ls_sql="UPDATE sno_dt_scg ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND codcomapo = '".$ls_codcom."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualización");		
					}
					$rs_data->MoveNext();
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{ // para la fecha de contabilización de las nóminas
			$ls_sql="SELECT sno_dt_spg.codcom, sigesp_cmp.fecha, sigesp_cmp.procede ".
					"  FROM sigesp_cmp, sno_dt_spg ".
					" WHERE sigesp_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND sigesp_cmp.procede ='SNOCNO' ".
					"	AND sno_dt_spg.tipnom = 'N' ".
					"   AND sigesp_cmp.codemp = sno_dt_spg.codemp ".
					"	AND sigesp_cmp.comprobante = sno_dt_spg.codcom ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualización");	
				$lb_valido=false;
			}
			else
			{
				while((!$rs_data->EOF)&&($lb_valido))
				{
					$ld_fecha=$rs_data->fields["fecha"];
					$ls_codcom=$rs_data->fields["codcom"];
					$ls_sql="UPDATE sno_dt_spg ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND codcom = '".$ls_codcom."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualización");		
					}
					$rs_data->MoveNext();
				}
				$this->io_sql->free_result($rs_data);
			}
		}		
		if($lb_valido)
		{ // para la fecha de contabilización de los aportes
			$ls_sql="SELECT sno_dt_spg.codcomapo, sigesp_cmp.fecha, sigesp_cmp.procede ".
					"  FROM sigesp_cmp, sno_dt_spg ".
					" WHERE sigesp_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND sigesp_cmp.procede ='SNOCNO' ".
					"	AND sno_dt_spg.tipnom = 'A' ".
					"   AND sigesp_cmp.codemp = sno_dt_spg.codemp ".
					"	AND sigesp_cmp.comprobante = sno_dt_spg.codcomapo ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualización");	
				$lb_valido=false;
			}
			else
			{
				while((!$rs_data->EOF)&&($lb_valido))
				{
					$ld_fecha=$rs_data->fields["fecha"];
					$ls_codcom=$rs_data->fields["codcomapo"];
					$ls_sql="UPDATE sno_dt_spg ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND codcomapo = '".$ls_codcom."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualización");		
					}
					$rs_data->MoveNext();
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso la fecha de los comprobantes del sistema de Nómina ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_reprocesar_fecha_comprobante_sno
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reprocesar_fecha_comprobante_saf($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reprocesar_fecha_comprobante_saf
		//		   Access: public
		//	    Arguments: 
		//	      Returns: lb_valido True si se actualizó sin ningún problema
		//	  Description: Funcion que obtiene el los comprobantes de presupuesto y le actualiza las fechas
		//	   Creado Por: Ing. Yesenia Moreno	
		// Modificado Por: 												Fecha Última Modificación : 20/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// para la fecha de contabilización
		$this->io_sql->begin_transaction();
		$lb_valido=true;
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_criterio="   AND sigesp_cmp.comprobante like CONCAT('%',SUBSTR(saf_depreciacion.fecdep,6,2),SUBSTR(saf_depreciacion.fecdep,1,4))";
				break;
			
			case "POSTGRES":
				$ls_criterio="   AND sigesp_cmp.comprobante like '%'||SUBSTR(saf_depreciacion.fecdep,6,2)||SUBSTR(saf_depreciacion.fecdep,1,4)";
				break;
		}
		$ls_sql="SELECT saf_depreciacion.fecdep, sigesp_cmp.fecha, sigesp_cmp.procede ".
				"  FROM sigesp_cmp, saf_depreciacion ".
				" WHERE sigesp_cmp.codemp = '".$this->ls_codemp."' ".
				"   AND sigesp_cmp.procede ='SAFDPR' ".
				"   AND sigesp_cmp.codemp = saf_depreciacion.codemp ".
				$ls_criterio;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_message->message("Problemas al ejecutar actualización");	
			$lb_valido=false;
		}
		else
		{
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ld_fecha=$rs_data->fields["fecha"];
				$ls_ano=substr($rs_data->fields["fecdep"],0,4);
				$ls_mes=substr($rs_data->fields["fecdep"],5,2);
				$ls_sql="UPDATE saf_depreciacion ".
						"   SET fechaconta = '".$ld_fecha."' ".
						" WHERE codemp = '".$this->ls_codemp."' ".
						"   AND SUBSTR(fecdep,1,4) = '".$ls_ano."' ".
						"   AND SUBSTR(fecdep,6,2) = '".$ls_mes."' ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_message->message("Problemas al ejecutar actualización");	
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso la fecha de los comprobantes del sistema de Activos Fijos ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_reprocesar_fecha_comprobante_saf
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reprocesar_fecha_comprobante_modpre($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reprocesar_fecha_comprobante_modpre
		//		   Access: public
		//	    Arguments: 
		//	      Returns: lb_valido True si se actualizó sin ningún problema
		//	  Description: Funcion que obtiene el los comprobantes de presupuesto y le actualiza las fechas
		//	   Creado Por: Ing. Yesenia Moreno	
		// Modificado Por: 												Fecha Última Modificación : 20/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// para la fecha de contabilización
		$this->io_sql->begin_transaction();
		$lb_valido=true;
		$ls_sql="SELECT sigesp_cmp_md.comprobante, sigesp_cmp.fecha, sigesp_cmp.procede ".
				"  FROM sigesp_cmp, sigesp_cmp_md ".
				" WHERE sigesp_cmp.codemp = '".$this->ls_codemp."' ".
				"   AND sigesp_cmp.tipo_comp = 2 ".
				"   AND sigesp_cmp.codemp = sigesp_cmp_md.codemp ".
				"   AND sigesp_cmp.comprobante = sigesp_cmp_md.comprobante ".
				"   AND sigesp_cmp.procede = sigesp_cmp_md.procede ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_message->message("Problemas al ejecutar actualización");	
			$lb_valido=false;
		}
		else
		{
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ld_fecha=$rs_data->fields["fecha"];
				$ls_comprobante=$rs_data->fields["comprobante"];
				$ls_procede=$rs_data->fields["procede"];
				$ls_sql="UPDATE sigesp_cmp_md ".
						"   SET fechaconta = '".$ld_fecha."' ".
						" WHERE codemp = '".$this->ls_codemp."' ".
						"   AND comprobante = '".$ls_comprobante."' ".
						"   AND procede = '".$ls_procede."' ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_message->message("Problemas al ejecutar actualización");	
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso la fecha de los comprobantes de las Modificaciones Presupuestarias ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_reprocesar_fecha_comprobante_modpre
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reprocesar_comprobantes_contabilizados_scb($aa_seguridad)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////
		// 	     Function: uf_reprocesar_comprobantes_contabilizados_scb
		// 	       Access: public
		//      Arguments: $aa_seguridad
		//	      Returns: Boolean
		//    Description: Esta funcion verifica que los comprobantes de banco que están contabilizados tambien
		//				   se encuentren en contabilidad, presupuesto de gasto y presupuesto de ingreso en caso
		//				   de que no se encuentren los genera
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 											Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codban,ctaban,estmov,numdoc,fecmov,conmov,codope,tipo_destino,ced_bene,cod_pro,conmov,fechaconta,fechaanula ".
                "  FROM scb_movbco ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND estmov='N' ".
				"   AND estmodordpag<>'CM'";
		$this->io_sql->begin_transaction();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_message->message("CLASE->Reprocesar Comprobantes MÉTODO->uf_reprocesar_comprobantes_contabilizados_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		else
		{
			while(!$rs_data->EOF)
			{
				$ls_codban=$rs_data->fields["codban"];
				$ls_ctaban=$rs_data->fields["ctaban"];
				$ls_estmov=$rs_data->fields["estmov"];
				$ls_numdoc=$rs_data->fields["numdoc"];
				$ls_fecmov=$rs_data->fields["fecmov"];
				$ls_fechaconta=$rs_data->fields["fechaconta"];
				$ls_fechaanula=$rs_data->fields["fechaanula"];
				$ls_conmov=$rs_data->fields["conmov"];
				$ls_codope=$rs_data->fields["codope"];
				$ls_tipo=$rs_data->fields["tipo_destino"];  		
				$ls_ced_bene=$rs_data->fields["ced_bene"];
				$ls_cod_pro=$rs_data->fields["cod_pro"];
				$ls_descripcion=$rs_data->fields["conmov"];
			    $ls_procede="SCBBA".substr($ls_codope,1,1);
				$ls_comprobante=$ls_numdoc;
				$ls_estatus="C";
				$codban="---";
				$ctaban="-------------------------";
			    $lb_existe=$this->io_sigesp_int->uf_obtener_comprobante($this->ls_codemp,$ls_procede,$ls_comprobante,&$ls_fecmov,
																		$codban,$ctaban,&$ls_tipo,&$ls_ced_bene,&$ls_cod_pro);
				if($lb_existe)
				{
					$lb_valido=$this->uf_insertar_movimiento_scb($this->ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,
																 $ls_estmov,'O',$ls_procede,$ls_comprobante,$ls_fecmov,$ls_fechaconta);
					if($lb_valido)
					{
						$lb_valido=$this->uf_insertar_movimiento_scb($this->ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,
																	 $ls_estmov,'A',$ls_procede,$ls_comprobante,$ls_fechaanula,$ls_fechaanula);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_delete_movimiento_scb($this->ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,
																   $ls_estmov);
					}
				}
				else
				{
			    	$ls_procede="SCBB".$ls_codope;
					$lb_existe=$this->io_sigesp_int->uf_obtener_comprobante($this->ls_codemp,$ls_procede,$ls_comprobante,&$ls_fecmov,
																			$codban,$ctaban,&$ls_tipo,&$ls_ced_bene,&$ls_cod_pro);
					if($lb_existe)
					{
						$lb_valido=$this->uf_insertar_movimiento_scb($this->ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,
																	 $ls_estmov,'C',$ls_procede,$ls_comprobante,$ls_fecmov,$ls_fechaconta);
						if($lb_valido)
						{
							$lb_valido=$this->uf_delete_movimiento_scb($this->ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,
																	   $ls_estmov);
						}
					}
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso los comprobantes contabilizados del sistema de Caja y Banco";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_reprocesar_comprobantes_contabilizados_scb
	//-----------------------------------------------------------------------------------------------------------------------------------

}
?>