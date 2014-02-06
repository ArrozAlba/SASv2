<?php
ini_set('precision','15');
class class_sigesp_int_spg extends class_sigesp_int
{
	var $io_function;
	var $sig_int;
	var $io_int_scg;
	var $io_fecha;
	var $is_msg_error="";
	var $io_sql;
	var $io_connect;
	var $int_spgctas;
	var $io_include;
	var $io_msg;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function class_sigesp_int_spg()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: class_sigesp_int_spg
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_include=new sigesp_include();
		$this->io_function=new class_funciones();	
		$this->sig_int=new class_sigesp_int();
		$this->io_int_scg=new class_sigesp_int_scg();
		$this->io_fecha=new class_fecha();
		$this->ls_dabatase_target=$_SESSION["ls_data_des"];		
		//$this->io_connect=$this->io_include->uf_conectar($this->ls_dabatase_target);
		$this->io_connect=$this->io_include->uf_conectar_otra_bd($_SESSION['sigesp_servidor_apr'],$_SESSION['sigesp_usuario_apr'],
									   $_SESSION['sigesp_clave_apr'], $_SESSION['sigesp_basedatos_apr'], 
									   $_SESSION['sigesp_gestor_apr']);
		$this->io_sql=new class_sql($this->io_connect);
		$this->io_msg = new class_mensajes();
	}  // end function class_sigesp_int_spg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_int_spg_delete_movimiento($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$as_tipo,$as_fuente,$as_cod_pro,$as_ced_bene,
	                                      $estprog,$as_cuenta,$as_procede_doc,$as_documento,$as_descripcion,$as_mensaje,$as_tipo_comp,
										  $adec_monto_anterior,$adec_monto_actual,$as_sc_cuenta,$as_codban,$as_ctaban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_procesar_delete_movimiento
		//		   Access: public 
		//       Argument: as_codemp // Código de empresa
		//       		   as_procede // Procedencia del documento
		//       		   as_comprobante // Número de Comprobante
		//       		   as_fecha // Fecha del Comprobante
		//       		   as_tipo // Tipo
		//       		   as_fuente // Fuente si es proveedor ó Beneficiario
		//       		   as_cod_pro // Código de Proveedor
		//       		   as_ced_bene // Cédula del Beneficiario
		//       		   estprog // Programática
		//       		   as_cuenta // cuenta
		//       		   as_procede_doc // Procede del movimiento
		//       		   as_documento // Número del Documento
		//       		   as_descripcion // Descripción del Documento
		//       		   as_mensaje // Mensaje del Documento
		//       		   as_tipo_comp // Tipo de Comprobante
		//       		   adec_monto_anterior // Monto Anterior
		//       		   adec_monto_actual // Monto Actual
		//       		   as_sc_cuenta // Cuenta Contable
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta de Banco
		//	  Description: Método que elimina un movimiento de gasto por medio de la integracion en lote
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$this->is_codemp=$as_codemp;
		$this->is_procedencia=$as_procedencia;
		$this->is_comprobante=$as_comprobante;
		$this->id_fecha=$as_fecha;
		$this->is_tipo=$as_tipo;
		$this->is_fuente=$as_fuente;
		$this->is_cod_prov=$as_cod_pro;
		$this->is_ced_ben=$as_ced_bene;
		$this->as_codban=$as_codban;
		$this->as_ctaban=$as_ctaban;
		$ls_operacion=$this->uf_operacion_mensaje_codigo($as_mensaje);
		if(empty($ls_operacion))
		{
			return false;
		}
		if(!$this->uf_spg_select_movimiento($estprog,$as_cuenta,$as_procede_doc,$as_documento,$ls_operacion,$lo_monto_movimiento,
											$lo_orden))  
		{
			$this->io_msg->message("El movimiento Presupuestario no existe.");			   		  
			return false; 	
		}
		$lb_valido = $this->uf_valida_integridad_referencial_comprobante($estprog,$as_cuenta,$as_procede_doc,$as_documento,
																		 $ls_operacion,$as_tipo,$as_cod_pro,$as_ced_bene,
																		 $adec_monto_anterior);
		if ($lb_valido)   
		{
			$lb_valido = $this->uf_spg_saldo_actual($as_codemp,$estprog,$as_cuenta,$as_mensaje,$lo_monto_movimiento,0);
			if ($lb_valido)
			{
				$lb_valido = $this->uf_spg_delete_movimiento($estprog,$as_cuenta,$as_procede_doc,$as_documento,$ls_operacion);
				if ($lb_valido)
				{
					$lb_valido = $this->uf_spg_comprobante_actualizar($lo_monto_movimiento,0,"C");
					if ( ($lb_valido) && ($this->ib_AutoConta) )
					{
						$as_mensaje=strtoupper($as_mensaje); // devuelve cadena en MAYUSCULAS
						$li_pos_i=strpos($as_mensaje,"C"); 
						if(!($li_pos_i===false))
						{
							if (!$this->io_int_scg->uf_scg_valida_cuenta($as_codemp,$as_sc_cuenta))
							{
								$this->io_msg->message("La cuenta contable ".$as_sc_cuenta." no existe");			   		  
								$lb_valido=false;
							}
							else
							{
								if ($lo_monto_movimiento>0) 
								{
									$ls_debhab='D';
								}
								else 
								{
									$ls_debhab='H';
								}
								$lb_valido=$this->io_int_scg->uf_scg_delete_movimiento($as_codemp,$as_procedencia,$as_comprobante,
																					   $as_fecha,$as_sc_cuenta,$as_procede_doc,
																					   $as_documento,$ls_debhab,$this->as_codban,
																					   $this->as_ctaban);
							}
						}  
					}
				}  
			}
		}
		return $lb_valido;
    } // end function uf_int_spg_delete_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_delete_movimiento($estprog,$as_cuenta,$as_procede_doc,$as_documento,$as_operacion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_delete_movimiento
		//		   Access: public 
		//       Argument: estprog // Programática
		//       		   as_cuenta // cuenta
		//       		   as_procede_doc // Procede del movimiento
		//       		   as_documento // Número del Documento
		//       		   as_operacion // Operación
		//	  Description: Este método inserta un movimiento presupuestario en las tablas de detalle comprobante spg.
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ldt_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql="DELETE FROM spg_dt_cmp ".
				" WHERE codemp='".$this->is_codemp."' ".
				"   AND procede='".$this->is_procedencia."' ".
				"   AND comprobante='".$this->is_comprobante."' ".
				"   AND fecha='".$ldt_fecha."' ".
				"   AND codban='".$this->as_codban."' ".
				"   AND ctaban='".$this->as_ctaban."' ".
				"   AND codestpro1='".$estprog[0]."' ".
				"   AND codestpro2='".$estprog[1]."' ".
				"   AND codestpro3='".$estprog[2]."' ".
				"   AND codestpro4='".$estprog[3]."' ".
				"   AND codestpro5='".$estprog[4]."' ".
				"   AND spg_cuenta='".$as_cuenta."' ".
				"   AND procede_doc='".$as_procede_doc."' ".
				"   AND documento ='".$as_documento."' ".
				"   AND operacion ='".$as_operacion."'";
		$li_rows=$this->io_sql->execute($ls_sql);
		if($li_rows===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_spg_delete_movimiento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		return $lb_valido;
	} // end function uf_spg_delete_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_valida_integridad_referencial_comprobante($estprog,$as_cuenta,$as_procede_doc,$as_documento,$as_operacion,
	                                                      $as_tipo_destino,$as_cod_pro,$as_ced_bene,$adec_monto_anterior)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_valida_integridad_referencial_comprobante
		//		   Access: public 
		//       Argument: estprog // Programática
		//       		   as_cuenta // cuenta
		//       		   as_procede_doc // Procede del movimiento
		//       		   as_documento // Número del Documento
		//       		   as_operacion // Operación
		//       		   as_tipo_destino // Tipo Destino
		//       		   as_cod_pro // Código del Proveedor
		//       		   as_ced_bene // Cédula del Beneficiario
		//       		   adec_monto_anterior // Monto Anterior del Movimiento
		//	  Description: Método que verifica si el registro esta asociado a otra tabla 
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe_referencia=false;
		$ls_codemp=$this->is_codemp;
		$ls_procedencia=$this->is_procedencia;
		$ls_comprobante=$this->is_comprobante;
		$as_fecha=$this->id_fecha;
		$as_codban=$this->as_codban;
		$as_ctaban=$this->as_ctaban;
		$ls_codestpro1=$estprog[0];
		$ls_codestpro2=$estprog[1];
		$ls_codestpro3=$estprog[2];
		$ls_codestpro4=$estprog[3];
		$ls_codestpro5=$estprog[4];
		if($adec_monto_anterior>0)
		{
			$lb_valido = $this->uf_valida_integridad_comprobante_ajuste($ls_codemp,$ls_comprobante,$ls_procedencia,$as_tipo_destino,$as_cod_pro,$as_ced_bene,
		                                                                $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$as_cuenta,
																	    $as_operacion,$lb_existe_referencia);
			if ($lb_valido)																	   
			{
				if ($lb_existe_referencia)
				{
					$this->io_msg->message("El comprobante es referenciado en otro");			   
					return false; 	
				}
				$lb_valido = $this->uf_valida_integridad_comprobante_otros( $ls_codemp,$ls_comprobante,$ls_procedencia,$as_tipo_destino,$as_cod_pro,$as_ced_bene,
																		 $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$as_cuenta,
																		 $as_operacion,$lb_existe_referencia);
				if ($lb_valido)																	   
				{
					if ($lb_existe_referencia)
					{
						$this->io_msg->message("El comprobante es referenciado en otro");			   
						return false; 	
					}
				} 
			}
		}
		return $lb_valido;
	} // end function uf_valida_integridad_referencial_comprobante
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_valida_integridad_comprobante_ajuste($as_codemp,$as_comprobante,$as_procedencia,$as_tipo_destino,$as_cod_pro,$as_ced_bene,
	                                                 $as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_cuenta,
												     $as_operacion,&$ab_existe_referencia)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_valida_integridad_comprobante_ajuste
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//				   as_comprobante // Número de Comprobante
		//				   as_procedencia // Procedencia del Documento
		//       		   as_tipo_destino // Tipo Destino
		//       		   as_cod_pro // Código del Proveedor
		//       		   as_ced_bene // Cédula del Beneficiario
		//				   as_codestpro1 // Estructura Programática 1
		//				   as_codestpro2 // Estructura Programática 2
		//				   as_codestpro3 // Estructura Programática 3
		//				   as_codestpro4 // Estructura Programática 4
		//				   as_codestpro5 // Estructura Programática 5
		//       		   as_cuenta // cuenta
		//       		   as_operacion // Operación
		//       		   ab_existe_referencia // Verifica si existe referencia
		//	  Description: Método que valida si el movimiento esta asociado con otro.
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
	    $ls_sql="SELECT D.procede As procede,D.comprobante As comprobante,D.fecha as fecha ".
			    "  FROM spg_dt_cmp D,sigesp_cmp C".		
			    " WHERE D.codemp=C.codemp ".
				"   AND D.procede=C.procede ".
				"   AND D.comprobante=C.comprobante ".
				"   AND D.fecha=C.fecha ".
				"   AND D.codban=C.codban ".
				"   AND D.ctaban=C.ctaban ".
				"   AND C.tipo_comp=1 ".
				"   AND C.codemp='".$as_codemp."' ".
				"   AND D.codestpro1 ='".$as_codestpro1."' AND D.codestpro2 ='".$as_codestpro2."' ". 
			    "   AND D.codestpro3 ='".$as_codestpro3."' AND D.codestpro4 = '".$as_codestpro4."' ".
				"   AND D.codestpro5 ='".$as_codestpro5."' AND procede_doc='".$as_procedencia."' ".
			    "   AND D.documento='".$as_comprobante."'  AND tipo_destino='".$as_tipo_destino."' ".
				"   AND D.procede_doc='".$as_procedencia."' AND D.spg_cuenta ='".$as_cuenta."' ".
				"   AND operacion='".$as_operacion."' AND monto<0 ".
				"   AND C.cod_pro='".$as_cod_pro."' AND C.ced_bene='".$as_ced_bene."'"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_valida_integridad_comprobante_ajuste ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
		    return false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ab_existe_referencia=true;
				$this->is_msg_error = $this->is_msg_error."Comprobante: ".$row["procede"].$row["procede"].$row["fecha"];
	            $this->io_msg->message($this->is_msg_error);			   		  		  				
			}			
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}  // end function uf_valida_integridad_comprobante_ajuste
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_valida_integridad_comprobante_otros($as_codemp,$as_comprobante,$as_procedencia,$as_tipo_destino,$as_cod_pro,$as_ced_bene,
	                                                $as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_cuenta,
												    $as_operacion,&$ab_existe_referencia)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_valida_integridad_comprobante_otros
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//				   as_comprobante // Número de Comprobante
		//				   as_procedencia // Procedencia del Documento
		//       		   as_tipo_destino // Tipo Destino
		//       		   as_cod_pro // Código del Proveedor
		//       		   as_ced_bene // Cédula del Beneficiario
		//				   as_codestpro1 // Estructura Programática 1
		//				   as_codestpro2 // Estructura Programática 2
		//				   as_codestpro3 // Estructura Programática 3
		//				   as_codestpro4 // Estructura Programática 4
		//				   as_codestpro5 // Estructura Programática 5
		//       		   as_cuenta // cuenta
		//       		   as_operacion // Operación
		//       		   ab_existe_referencia // Verifica si existe referencia
		//	  Description: Método que valida si el movimiento esta asociado con otro.
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_mensaje=$this->uf_operacion_codigo_mensaje($as_operacion);
	    $ls_mensaje=strtoupper($ls_mensaje); // devuelve cadena en MAYUSCULAS
		// caso exepcional
 	    $li_pos_o=strpos($ls_mensaje,"O");
		$li_pos_c=strpos($ls_mensaje,"C");
		$li_pos_p=strpos($ls_mensaje,"P"); 
        if(!($li_pos_o===false)and!($li_pos_c===false)and!($li_pos_p===false))
		{
			return true;
		}
		$ls_cadena_incluir="";
	    $ls_cadena_excluir="";
		$li_pos_o=strpos($ls_mensaje,"O");
	    if(!($li_pos_o===false))
		{
			$ls_cadena_excluir=$ls_cadena_excluir."O.comprometer=0 AND ";
		}
		$li_pos_c=strpos($ls_mensaje,"C");
	    if(!($li_pos_c===false))
		{
			$ls_cadena_excluir=$ls_cadena_excluir."O.causar=0 AND ";
		}
 		else
		{
			$ls_cadena_incluir=$ls_cadena_incluir."O.causar=1 OR ";
		}
        $li_pos_p=strpos($ls_mensaje,"P"); 
        if(!($li_pos_p===false))
		{
			$ls_cadena_excluir=$ls_cadena_excluir."O.pagar=0 AND ";
		}
 		else
		{
			$ls_cadena_incluir=$ls_cadena_incluir."O.pagar=1 OR ";
		}
        $ls_condicion="";         
        if(!empty($ls_cadena_excluir)) 
		{
		    $ls_cadena_excluir="(".substr($ls_cadena_excluir,0,strlen($ls_cadena_excluir)- 4).")";
            $ls_condicion=$ls_condicion.$ls_cadena_excluir." AND ";
		}
        if(!empty($ls_cadena_incluir)) 
		{
		    $ls_cadena_incluir = "(".substr($ls_cadena_incluir,0,strlen($ls_cadena_incluir)- 3).")";
            $ls_condicion =$ls_condicion.$ls_cadena_incluir." AND ";
		}
	    $ls_sql="SELECT D.procede As procede,D.comprobante As comprobante,D.fecha as fecha ".
			    "  FROM spg_dt_cmp D,sigesp_cmp C,spg_operaciones O ".		
			    " WHERE C.codemp='".$as_codemp."' ".
				"	AND C.tipo_comp=1 ".
				"   AND D.codestpro1 ='".$as_codestpro1."' ".
				"   AND D.codestpro2 ='".$as_codestpro2."' ".
				"   AND D.codestpro3 ='".$as_codestpro3."' ".
				"   AND D.codestpro4 = '".$as_codestpro4."' ".
				"   AND D.codestpro5 ='".$as_codestpro5."' ".
			    "   AND D.documento='".$as_comprobante."'  ".
				"   AND tipo_destino='".$as_tipo_destino."' ".
				"   AND C.cod_pro='".$as_cod_pro."' ".
				"   AND C.ced_bene='".$as_ced_bene."' ". 
			    "   AND D.procede_doc='".$as_procedencia."' ".
				"   AND D.spg_cuenta ='".$as_cuenta."' ".
				"   AND D.operacion='".$as_operacion."' ".
				"   AND ".$ls_condicion." monto>0 ".
				"   AND D.codemp=C.codemp ".
				"   AND D.procede=C.procede ".
				"   AND D.comprobante=C.comprobante ".
				"   AND D.fecha=C.fecha ".
				"   AND D.codban=C.codban ".
				"   AND D.ctaban=C.ctaban ".
				"   AND D.operacion=O.operacion ";
		$rs_data=$this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_valida_integridad_comprobante_otros ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
		    return false;
		}
		else
		{
		    $this->is_msg_error="";
		    while($row=$this->io_sql->fetch_row($rs_data) )
			{
				$ab_existe_referencia=true;
				$this->io_msg->message("Comprobante: ".$row[" procede :"].$row[" Fecha :"].$row["fecha"]);
			}				
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}  // end function uf_valida_integridad_comprobante_ajuste
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_int_spg_insert_movimiento($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$as_tipo,$as_fuente,$as_cod_prov,
										  $as_ced_ben,$estprog,$as_cuenta,$as_procede_doc,$as_documento,$as_descripcion,
										  $as_mensaje,$adec_monto,$as_sc_cuenta,$ab_spg_enlace_contable,$as_codban,$as_ctaban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_int_spg_insert_movimiento
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//				   as_procedencia // Procedencia del Documento
		//				   as_comprobante // Número de Comprobante
		//				   as_fecha  // Fecha del Comprobante
		//				   as_tipo // Tipo
		//       		   as_fuente // Tipo Destino
		//       		   as_cod_prov // Código del Proveedor
		//       		   as_ced_ben // Cédula del Beneficiario
		//				   estprog // Estructura Programática 
		//       		   as_cuenta // cuenta
		//				   as_procede_doc // Procede del Documento
		//				   as_documento // Número del Documento
		//				   as_descripcion // Descripción del Movimiento
		//				   as_mensaje // Mensaje del Movimiento
		//				   adec_monto // Monto del Movimiento
		//				   as_sc_cuenta // Cuenta Contable del Movimiento
		//				   ab_spg_enlace_contable // Enlace Contable
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta de Banco
		//	  Description: Método que inserta un movimiento de gasto por medio de la integracion en lote
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_denproc="";
		$ls_status="";
		$ls_denominacion="";
		$ls_SC_Cuenta="";
		$this->is_codemp=$as_codemp;
		$this->is_procedencia=$as_procedencia;
		$this->is_comprobante=$as_comprobante;
		$this->is_descripcion=$as_descripcion;
		$this->id_fecha=$as_fecha;
		$this->is_tipo=$as_tipo;
		$this->is_fuente=$as_fuente;
		$this->is_cod_prov=$as_cod_prov;
		$this->is_ced_ben=$as_ced_ben;
		$this->ib_spg_enlace_contable=$ab_spg_enlace_contable;
		$this->as_codban=$as_codban;
		$this->as_ctaban=$as_ctaban;
		$ls_comprobante=$this->uf_fill_comprobante($this->is_comprobante);
		$ls_operacion=$this->uf_operacion_mensaje_codigo($as_mensaje);
		if(empty($ls_operacion))
		{
			return false;
		}
		if(!$this->uf_valida_procedencia($this->is_procedencia,$ls_denproc))
		{
			return false;
		}
		if(!$this->io_fecha->uf_valida_fecha_periodo($this->id_fecha,$this->is_codemp))
		{
			$this->is_msg_error = "Fecha Invalida."	;
			$this->io_msg->message($this->is_msg_error);			   		  		  
			return false;
		}
		if($this->uf_spg_select_movimiento($estprog,$as_cuenta,$as_procede_doc,$as_documento,$ls_operacion,$lo_monto_movimiento,
										   $lo_orden))  
		{
			$this->is_msg_error = "El movimiento Presupuestario ya existe.";
			$this->io_msg->message($this->is_msg_error);			   		  		  		  
			return false; 	
		}
		$lb_valido = $this->uf_spg_comprobante_actualizar(0,$adec_monto,"C");
		if ($lb_valido===true)
		{
			$lb_valido = $this->uf_spg_saldo_actual($as_codemp,$estprog,$as_cuenta,$as_mensaje,0,$adec_monto);
			if ($lb_valido===true)
			{
				$lb_valido =$this->uf_spg_insert_movimiento($estprog,$as_cuenta,$as_procede_doc,$as_documento,$ls_operacion,$as_descripcion,$adec_monto);
				if(($lb_valido)) 
				{
					$as_mensaje=strtoupper($as_mensaje); // devuelve cadena en MAYUSCULAS
					$li_pos_i=strpos($as_mensaje,"C"); 
					if (!($li_pos_i===false) and ($this->ib_spg_enlace_contable))
					{			      
						if ($this->ib_AutoConta)
						{
							$lb_valido=$this->uf_spg_integracion_scg($as_codemp,$as_sc_cuenta,$as_procede_doc,$as_documento,$as_descripcion,$adec_monto,$as_codban,$as_ctaban);
						}
					} 
				}
			}
		}
	   return $lb_valido;
	}  // end function uf_int_spg_insert_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_int_spg_insert_movimiento_modpre($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$as_tipo,$as_fuente,
												 $as_cod_prov,$as_ced_ben,$estprog,$as_cuenta,$as_procede_doc,$as_documento,
												 $as_descripcion,$as_mensaje,$adec_monto,$as_sc_cuenta,$ab_spg_enlace_contable,
												 $as_codban,$as_ctaban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_int_spg_insert_movimiento
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//				   as_procedencia // Procedencia del Documento
		//				   as_comprobante // Número de Comprobante
		//				   as_fecha  // Fecha del Comprobante
		//				   as_tipo // Tipo
		//       		   as_fuente // Tipo Destino
		//       		   as_cod_prov // Código del Proveedor
		//       		   as_ced_ben // Cédula del Beneficiario
		//				   estprog // Estructura Programática 
		//       		   as_cuenta // cuenta
		//				   as_procede_doc // Procede del Documento
		//				   as_documento // Número del Documento
		//				   as_descripcion // Descripción del Movimiento
		//				   as_mensaje // Mensaje del Movimiento
		//				   adec_monto // Monto del Movimiento
		//				   as_sc_cuenta // Cuenta Contable del Movimiento
		//				   ab_spg_enlace_contable // Enlace Contable
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta de Banco
		//	  Description: Método que inserta un movimiento de gasto por medio de la integracion en lote
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Nelson Barraez
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_denproc="";
		$ls_status="";
		$ls_denominacion="";
		$ls_SC_Cuenta="";
		$this->is_codemp=$as_codemp;
		$this->is_procedencia=$as_procedencia;
		$this->is_comprobante=$as_comprobante;
		$this->is_descripcion=$as_descripcion;
		$this->id_fecha=$as_fecha;
		$this->is_tipo=$as_tipo;
		$this->is_fuente=$as_fuente;
		$this->is_cod_prov=$as_cod_prov;
		$this->is_ced_ben=$as_ced_ben;
		$this->ib_spg_enlace_contable=$ab_spg_enlace_contable;
		$this->as_codban=$as_codban;
		$this->as_ctaban=$as_ctaban;
		$ls_comprobante=$this->uf_fill_comprobante($this->is_comprobante);
		$ls_operacion=$this->uf_operacion_mensaje_codigo($as_mensaje);
		if(empty($ls_operacion))
		{
			return false;
		}
		if(!$this->uf_valida_procedencia($this->is_procedencia,$ls_denproc))
		{
			return false;
		}
		if(!$this->io_fecha->uf_valida_fecha_mes($this->is_codemp,$this->id_fecha))
		{
			$this->is_msg_error = "Fecha Invalida."	;
			$this->io_msg->message($this->is_msg_error);			   		  		  
			return false;
		}
		if($this->uf_spg_select_movimiento($estprog,$as_cuenta,$as_procede_doc,$as_documento,$ls_operacion,$lo_monto_movimiento,
										   $lo_orden))  
		{
			$this->is_msg_error = "El movimiento Presupuestario ya existe.";
			$this->io_msg->message($this->is_msg_error);			   		  		  		  
			return false; 	
		}
		$lb_valido = $this->uf_spg_comprobante_actualizar(0,$adec_monto,"P");
		if ($lb_valido===true)
		{
			$lb_valido = $this->uf_spg_saldo_actual($as_codemp,$estprog,$as_cuenta,$as_mensaje,0,$adec_monto);
			if ($lb_valido===true)
			{
				$lb_valido =$this->uf_spg_insert_movimiento($estprog,$as_cuenta,$as_procede_doc,$as_documento,$ls_operacion,$as_descripcion,$adec_monto);
				if(($lb_valido)) 
				{
					$as_mensaje=strtoupper($as_mensaje); // devuelve cadena en MAYUSCULAS
					$li_pos_i=strpos($as_mensaje,"C"); 
					if (!($li_pos_i===false) and ($this->ib_spg_enlace_contable))
					{			      
						if ($this->ib_AutoConta)
						{
							$lb_valido=$this->uf_spg_integracion_scg($as_codemp,$as_sc_cuenta,$as_procede_doc,$as_documento,$as_descripcion,$adec_monto,$as_codban,$as_ctaban);
						}
					} 
				}
			}
		}
		return $lb_valido;
	} // end function uf_int_spg_insert_movimiento_modpre
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_select_movimiento($estprog,$as_cuenta,$as_procede_doc,$as_documento,$as_operacion,&$adec_monto,&$ai_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_select_movimiento
		//		   Access: public 
		//       Argument: estprog // Estructura Programática 
		//       		   as_cuenta // cuenta
		//				   as_procede_doc // Procede del Documento
		//				   as_documento // Número del Documento
		//				   as_descripcion // Descripción del Movimiento
		//				   adec_monto // Monto del Movimiento
		//       		   ai_orden // Orden del movimiento
		//	  Description: Este método verifica si el movimiento ya existe o no en la tabla de movimeintos presupuestario de gasto
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Nelson Barraez
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	    $lb_existe=false;
		$ls_cuenta="";$lb_existe=false;$ldec_monto=0;$li_orden=0;
		$ls_codemp=$this->is_codemp ;
		$ls_procedencia=$as_procede_doc;
		$ls_comprobante=$as_documento;
		$ls_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
	    $ls_sql="SELECT spg_cuenta, monto, orden ".
			    "  FROM spg_dt_cmp ".		
			    " WHERE codemp='".$ls_codemp."' ".
				"   AND codestpro1 ='".$estprog[0]."' ".
				"   AND codestpro2 ='".$estprog[1]."' ". 
			    "   AND codestpro3 ='".$estprog[2]."' ".
				"   AND codestpro4 ='".$estprog[3]."' ".
				"   AND codestpro5 ='".$estprog[4]."' ".
				"   AND procede='".$this->is_procedencia."' ".
			    "   AND comprobante='".$this->is_comprobante."' ".
				"   AND fecha='".$ls_fecha."' ".
			    "   AND codban='".$this->as_codban."' ".
				"   AND ctaban='".$this->as_ctaban."' ".
				"   AND procede_doc='".$as_procede_doc."' ".
				"   AND documento ='".$as_documento."' ".
			    "   AND spg_cuenta ='".$as_cuenta."'  ".
				"   AND operacion='".$as_operacion."' "; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_spg_select_movimiento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
		    return false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_cuenta=$row["spg_cuenta"];
				$ldec_monto=$row["monto"];
				$adec_monto=$ldec_monto;
				$li_orden=$row["orden"];
				$ai_orden=$li_orden;
				$lb_existe=true;
			}			
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
	} // end function uf_select_movimientos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_operacion_codigo_mensaje($as_operacion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_operacion_codigo_mensaje
		//		   Access: public 
		//       Argument: as_operacion // Operación del Movimiento
		//	  Description: Este método recibe un codigo de operacion y genra mediante el los codigos de mensajes
		//                 interno de operaciones de cuentas como aumentos,causados, precompromisos etc.
		//	      Returns: retorna un mensaje interno para operaciones 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	    $ls_mensaje="";
		$ls_sql="SELECT asignar, aumento, disminucion, precomprometer, comprometer, causar, pagar ".
				"  FROM spg_operaciones ".
				" WHERE operacion = '".$as_operacion."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_operacion_codigo_mensaje ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return $ls_mensaje;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_asignar=$row["asignar"];
				$li_aumento=$row["aumento"];
				$li_disminucion=$row["disminucion"];
				$li_precomprometer=$row["precomprometer"];
				$li_comprometer=$row["comprometer"];
				$li_causar=$row["causar"];
				$li_pagar=$row["pagar"];
				if($li_asignar==1)
				{
					$ls_mensaje=$ls_mensaje."I";
				}
				if($li_aumento==1)
				{
					$ls_mensaje=$ls_mensaje."A";
				}
				if($li_disminucion==1)
				{
					$ls_mensaje=$ls_mensaje."D";
				}
				if($li_precomprometer==1)
				{
					$ls_mensaje=$ls_mensaje."R";
				}
				if($li_comprometer==1)
				{
					$ls_mensaje=$ls_mensaje."O";
				}
				if($li_causar==1)
				{
					$ls_mensaje=$ls_mensaje."C";
				}
				if($li_pagar==1)
				{
					$ls_mensaje=$ls_mensaje."P";
				}
				$ls_mensaje=trim($ls_mensaje);
			}
			else
			{
				$this->is_msg_error =  "No esta definido el código de operacion ".$as_operacion;
				$this->io_msg->message($this->is_msg_error);			   		  		  			  
			}
			$this->io_sql->free_result($rs_data);		
	    }
	    return $ls_mensaje;
    } // end function uf_operacion_codigo_mensaje
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_operacion_mensaje_codigo($as_mensaje)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_operacion_mensaje_codigo
		//		   Access: public 
		//       Argument: as_mensaje // Mensaje del Movimiento
		//	  Description: Este método mediante la cadena mensaje retorna el codigo operacion asociado
		//	      Returns: retorna un mensaje interno para operaciones 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_asignar=0;
		$li_aumento=0;
		$li_disminucion=0;
		$li_precomprometer=0;
		$li_comprometer=0;
		$li_causar=0;
		$li_pagar=0; 
		$ls_operacion=""; 
		$as_mensaje=strtoupper(trim($as_mensaje)); // devuelve cadena en MAYUSCULAS
		$li_pos_i=strpos($as_mensaje,"I"); 
		if(!($li_pos_i===false))
		{
			$li_asignar=1;
		}
		$li_pos_a=strpos($as_mensaje,"A");
		if(!($li_pos_a===false))
		{
			$li_aumento=1;
		}
		$li_pos_d=strpos($as_mensaje,"D");
		if(!($li_pos_d===false))
		{
			$li_disminucion=1;
		}
		$li_pos_r=strpos($as_mensaje,"R");
		if(!($li_pos_r===false))
		{
			$li_precomprometer=1;
		}
		$li_pos_o=strpos($as_mensaje,"O");
		if(!($li_pos_o===false))
		{
			$li_comprometer=1;
		}
		$li_pos_c=strpos($as_mensaje,"C");
		if(!($li_pos_c===false))
		{
			$li_causar=1;
		}
		$li_pos_p=strpos($as_mensaje,"P"); 
		if(!($li_pos_p===false))
		{
			$li_pagar=1;
		}
		$ls_sql="SELECT operacion ".
				"  FROM spg_operaciones ".
				" WHERE asignar=".$li_asignar ." ".
				"   AND aumento=".$li_aumento." ".
				"   AND disminucion=".$li_disminucion." ".
				"   AND precomprometer=".$li_precomprometer." ".
				"   AND comprometer=".$li_comprometer." ".
				"   AND causar=".$li_causar." ".
				"   AND pagar=".$li_pagar;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_operacion_mensaje_codigo ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return $ls_operacion;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_operacion=$row["operacion"];
			}
			else  
			{  
				$this->is_msg_error =  "No hay operacion asociada al mensaje ".$as_mensaje;  
				$this->io_msg->message($this->is_msg_error);			   		  		  			 
			}		
			$this->io_sql->free_result($rs_data);		
		}
		return $ls_operacion;	
	} // end function uf_operacion_mensaje_codigo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_comprobante_actualizar($ai_montoanterior, $ai_montoactual, $ls_tipocomp)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_comprobante_actualizar
		//		   Access: public 
		//       Argument: ai_montoanterior // Monto Anterior del Movimiento
		//				   ai_montoactual // Monto Actual del Movimiento
		//				   ls_tipocomp // Tipo de Comprobante		
		//	  Description: Este método actualiza  el comprobante SIGESP_cmp
		//	      Returns: retorna valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false; 
		$li_tipocomp=0;
		if($ls_tipocomp=="C")
		{
			$li_tipocomp=1;
		}
		if($ls_tipocomp=="P")
		{
			$li_tipocomp=2;
		}	
		if ($this->uf_spg_comprobante_select())
		{
			$lb_valido = $this->uf_spg_comprobante_update($ai_montoanterior, $ai_montoactual);
		}
		else 
		{ 
			$lb_valido = $this->uf_spg_comprobante_insert($ai_montoactual, $li_tipocomp);  
		}
		return $lb_valido;
    } // end function uf_spg_comprobante_actualizar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_comprobante_select()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_comprobante_select
		//		   Access: public 
		//       Argument: 	
		//	  Description: Este método verifica si existe el comprobante SIGESP_cmp
		//	      Returns: retorna valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql="SELECT * ".
				"  FROM sigesp_cmp ".
				" WHERE procede='".$this->is_procedencia."' ".
				"   AND comprobante='".$this->is_comprobante."' ".
				"   AND fecha='".$ld_fecha."' ".
				"   AND codban='".$this->as_codban."' ".
				"   AND ctaban='".$this->as_ctaban."' ";
		$rs_data = $this->io_sql->select($ls_sql);
	    if($rs_data===false)
	    {
			$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_spg_select_movimiento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
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
	}  // end function uf_spg_comprobante_select
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_comprobante_update($li_montoanterior, $li_montoactual)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_comprobante_update
		//		   Access: public 
		//       Argument: li_montoanterior // Monto anterior
		//				   li_montoactual // Monto Actual
		//	  Description: Este método actualiza el monto si existe el comprobante SIGESP_cmp
		//	      Returns: retorna valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total=(-$li_montoanterior+$li_montoactual);
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql="UPDATE sigesp_cmp ".
				"   SET total = total + '".$li_total."'  ".
				" WHERE procede='".$this->is_procedencia."' ".
				"   AND comprobante= '".$this->is_comprobante."' ".
				"   AND fecha='".$ld_fecha."' ".
				"   AND codban='".$this->as_codban."' ".
				"   AND ctaban='".$this->as_ctaban."' ";
		$li_exec=$this->io_sql->execute($ls_sql);
		if($li_exec===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_spg_comprobante_update ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}	   
		return $lb_valido;
	}  // end function uf_spg_comprobante_update
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_comprobante_insert($ai_monto,$ai_tipocomp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_comprobante_insert
		//		   Access: public 
		//       Argument: ai_monto // Monto
		//				   ai_tipocomp // Tipo de Comprobante
		//	  Description: Este método inserta en el compronate de gasto
		//	      Returns: retorna valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codemp=$this->is_codemp;
		$ls_procede=$this->is_procedencia;
		$ls_comprobante=$this->is_comprobante;
		$ls_descripcion=$this->is_descripcion;
		$ls_tipo=$this->is_tipo;
		$ls_codpro=$this->is_cod_prov;
		$ls_cedbene=$this->is_ced_ben;		
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql="INSERT INTO sigesp_cmp(codemp,procede,comprobante,fecha,descripcion,total,tipo_destino,cod_pro,ced_bene,".
				" tipo_comp,codban,ctaban)  VALUES ('".$ls_codemp."', '".$ls_procede."', '".$ls_comprobante."', '".$ld_fecha."', ".
			    "'".$ls_descripcion."', '".$ai_monto."', '".$ls_tipo."', '".$ls_codpro."', '".$ls_cedbene."', '".$ai_tipocomp."', ".
				"'".$this->as_codban."', '".$this->as_ctaban."' )";
		$li_exec=$this->io_sql->execute($ls_sql);                                                                                                                                                                                          
		if($li_exec===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_spg_select_movimiento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		return $lb_valido;
	}  // end function uf_spg_comprobante_insert
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_insert_movimiento($estprog,$as_cuenta,$as_procede_doc,$as_documento,$as_operacion,$as_descripcion,
									  $ad_monto_actual)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_insert_movimiento
		//		   Access: public 
		//       Argument: estprog // Estructura Programática 
		//       		   as_cuenta // cuenta
		//				   as_procede_doc // Procede del Documento
		//				   as_documento // Número del Documento
		//				   as_operacion // Operación del Movimiento
		//				   as_descripcion // Descripción del Movimiento
		//				   ad_monto_actual // Monto del Movimiento
		//	  Description: Este método inserta un movimiento presupuestario en las tablas de detalle comprobante spg.
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Nelson Barraez
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$li_orden=$this->uf_spg_obtener_orden_movimiento();
		$ls_sql="INSERT INTO spg_dt_cmp (codemp,procede,comprobante,fecha,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,".
				"spg_cuenta,procede_doc,documento,operacion,descripcion,monto,orden,codban,ctaban)".
				" VALUES('".$this->is_codemp."','".$this->is_procedencia."','".$this->is_comprobante."','".$ls_fecha."',".
				"  '".$estprog[0]."','".$estprog[1]."','".$estprog[2]."','".$estprog[3]."','".$estprog[4]."','".$as_cuenta."',".
				"'".$as_procede_doc."','".$as_documento."','".$as_operacion."','".$as_descripcion."','".$ad_monto_actual."',".
				"".$li_orden.",'".$this->as_codban."','".$this->as_ctaban."')"; 
		$li_rows=$this->io_sql->execute($ls_sql);
		if($li_rows===false)
		{
			$lb_valido=false;
			$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_spg_insert_movimiento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		return $lb_valido;
	} // end function uf_spg_insert_movimiento_gasto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_obtener_orden_movimiento()
	{   
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_obtener_orden_movimiento
		//		   Access: public 
		//       Argument: 
		//	  Description: Retorna el número de orden del movimiento de gasto spg
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql="SELECT count(*) as orden  ".
				"  FROM spg_dt_cmp".
				" WHERE codemp='".$this->is_codemp."' ".
				"   AND procede='".$this->is_procedencia."' ".
				"   AND comprobante='".$this->is_comprobante."'".
				"   AND fecha='".$this->id_fecha."' ".
				"   AND codban='".$this->as_codban."' ".
				"   AND ctaban='".$this->as_ctaban."' ";
		$rs_data=$this->io_sql->select($ls_sql);
	    if($rs_data===false)
	    {
			$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_spg_obtener_orden_movimiento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
	    }
	    else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_orden=$row["orden"];
			} 
			$this->io_sql->free_result($rs_data);		
		}  
	   return $li_orden;
    } // end function uf_spg_obtener_orden_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_spg_saldo_actual($as_codemp,$estprog,$as_cuenta,$as_mensaje,$adec_monto_anterior,$adec_monto_actual)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_saldo_actual
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//				   estprog // Estructura Programática
		//				   as_cuenta // Cuenta 
		//				   as_mensaje // Mensaje del Movimiento
		//				   adec_monto_anterior // Monto Anterior del Movimiento
		//				   adec_monto_actual // Monto Actual del Movimiento
		//	  Description: actualiza el monto saldo cuenta de gasto
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	    $lb_valido =true;
		$ab_ignorarerror=false;
		$ls_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha); 
		$ls_nextcuenta=$as_cuenta;
		$li_nivel=$this->uf_spg_obtener_nivel($ls_nextcuenta);
		while(($li_nivel>=1)and($lb_valido)and($ls_nextcuenta!=""))
		{  
			$ls_status="";
			$ld_asignado=0;
			$ld_aumento=0;
			$ld_disminucion=0;
			$ld_precomprometido=0;
			$ld_comprometido=0;
			$ld_causado=0;
			$ld_pagado=0;
			if ($this->uf_spg_saldo_select($as_codemp, $estprog, $ls_nextcuenta, &$ls_status, &$ld_asignado, &$ld_aumento, &$ld_disminucion, &$ld_precomprometido, &$ld_comprometido, &$ld_causado, &$ld_pagado))
			{				    
				if ($this->uf_spg_saldos_ajusta($estprog, $ls_nextcuenta, $as_mensaje, $ls_status, $adec_monto_anterior, $adec_monto_actual, &$ld_asignado, &$ld_aumento, &$ld_disminucion, &$ld_precomprometido, &$ld_comprometido, &$ld_causado, &$ld_pagado))
				{
					if(!($this->uf_spg_saldos_update($as_codemp, $estprog, $ls_nextcuenta, $ld_asignado, $ld_aumento, $ld_disminucion, $ld_precomprometido, $ld_comprometido, $ld_causado, $ld_pagado)))
					{
						$lb_valido=false;
						return false;
					}
				}
				else
				{ 
					$lb_valido=false;
					if($ab_ignorarerror )
					{
						if (!($this->uf_spg_saldos_update($as_codemp, $estprog, $ls_nextcuenta, $ld_asignado, $ld_aumento, $ld_disminucion, $ld_precomprometido, $ld_comprometido, $ld_causado, $ld_pagado))) 
						{
							$lb_valido=false;
							return false;
						}
					} 			  				
					else
					{
						$lb_valido=false;
						return false;
					}
				} 
			}
			if($this->uf_spg_obtener_nivel($ls_nextcuenta)==1)
			{
				break;
			}
			$ls_nextcuenta=$this->uf_spg_next_cuenta_nivel($ls_nextcuenta);
			$li_nivel=$this->uf_spg_obtener_nivel($ls_nextcuenta);
		}
		return $lb_valido;
	} // end function uf_spg_saldo_actual
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_obtener_nivel($as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_obtener_nivel
		//		   Access: public 
		//       Argument: as_cuenta // Cuenta 
		//	  Description: obtiene el nivel de la cuenta
		//	      Returns: nivel de la cuenta
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_nivel=0;
		$li_anterior=0;
		$li_longitud=0;
		$ls_cadena="";
	    $this->uf_init_niveles();
		$li_nivel=count($this->ia_niveles_spg);
		do
		{
			$li_anterior=$this->ia_niveles_spg[ $li_nivel - 1 ]  + 1;
			$li_longitud=$this->ia_niveles_spg[ $li_nivel ] - $this->ia_niveles_spg[ $li_nivel - 1 ];
			$ls_cadena=substr(trim($as_cuenta),$li_anterior,$li_longitud); 
			$li=intval($ls_cadena);
		    if($li>0)
			{
				return $li_nivel;
			}
			$li_nivel=$li_nivel-1;
		}while($li_nivel>1);
		return $li_nivel;
	} // end function uf_spg_obtener_nivel
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_next_cuenta_nivel($as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_next_cuenta_nivel
		//		   Access: public 
		//       Argument: as_cuenta // Cuenta 
		//	  Description: Este método obtiene el siguiente nivel de la cuenta
		//	      Returns: cuenta referencia nivel anterior
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $this->uf_init_niveles();
		$li_MaxNivel=0;
		$li_nivel=0;
		$li_anterior=0;
		$li_longitud=0;
		$ls_cadena="";
		$li_MaxNivel=count($this->ia_niveles_spg);
		$li_nivel=$this->uf_spg_obtener_nivel($as_cuenta);
		if($li_nivel>1)
		{
			$li_anterior=$this->ia_niveles_spg[$li_nivel - 1]; 
			$ls_cadena=substr($as_cuenta,0,$li_anterior+1);  // ojo pilas al hacer  las prueba
			$li_longitud=strlen($ls_cadena);
			$li_long=(($this->ia_niveles_spg[$li_MaxNivel]+1) - $li_longitud);
			$ls_newcadena=$this->io_function->uf_cerosderecha(trim($ls_cadena),$li_long+$li_longitud);
			$ls_cadena=$ls_newcadena;
		} 
		return $ls_cadena;
	} // end function uf_spg_next_cuenta_nivel
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_saldo_select($as_codemp, $estprog, $as_cuenta, &$as_status, &$adec_asignado, &$adec_aumento, &$adec_disminucion,
								 &$adec_precomprometido, &$adec_comprometido,&$adec_causado, &$adec_pagado)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_saldo_select
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//				   estprog //  Estructura Programatica
		//				   as_cuenta // Cuenta 
		//				   as_status // Estatus de la Cuenta
		//				   adec_asignado // Monto del Asignado
		//				   adec_aumento // Monto del Aumento
		//				   adec_disminucion //  Monto de la Disminución
		//				   adec_precomprometido // Monto del Precomprometido
		//				   adec_comprometido // Monto del comprometido
		//				   adec_causado // Monto del Causado
		//				   adec_pagado // Monto del Pagado 
		//	  Description: verifica si existe un saldo a esa cuenta
		//	      Returns: boolean si existe o  no 
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido =true;
		$ls_sql="SELECT status ".
				"  FROM spg_cuentas ".
				" WHERE codemp='".$as_codemp."' ".
				"   AND codestpro1 = '".$estprog[0]."' ".
				"   AND codestpro2 = '".$estprog[1]."' ".
				"   AND codestpro3 = '".$estprog[2]."' ".
				"   AND codestpro4 = '".$estprog[3]."' ".
				"   AND codestpro5 = '".$estprog[4]."' ".
				"   AND spg_cuenta = '".$as_cuenta."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_spg_saldo_select ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{  
				$as_status=$row["status"];
			}
			else
			{
				$this->is_msg_error="La cuenta ".$as_cuenta." No Existe.";
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);		
		}
		if($as_status=="C") // Cuenta de Movimiento
		{
			if($lb_valido)
			{
				$ls_operacion="asignar";
				$adec_asignado=0;
				$lb_valido=$this->uf_calcular_disponible_por_rango($estprog[0],$estprog[1],$estprog[2],$estprog[3],$estprog[4],
																   $as_cuenta,&$adec_asignado,$ls_operacion);
			}
			if($lb_valido)
			{
				$ls_operacion="aumento";
				$adec_aumento=0;
				$lb_valido=$this->uf_calcular_disponible_por_rango($estprog[0],$estprog[1],$estprog[2],$estprog[3],$estprog[4],
																   $as_cuenta,&$adec_aumento,$ls_operacion);
			}
			if($lb_valido)
			{
				$ls_operacion="disminucion";
				$adec_disminucion=0;
				$lb_valido=$this->uf_calcular_disponible_por_rango($estprog[0],$estprog[1],$estprog[2],$estprog[3],$estprog[4],
																   $as_cuenta,&$adec_disminucion,$ls_operacion);
			}
			if($lb_valido)
			{
				$ls_operacion="precomprometer";
				$adec_precomprometido=0;
				$lb_valido=$this->uf_calcular_disponible_por_rango($estprog[0],$estprog[1],$estprog[2],$estprog[3],$estprog[4],
																   $as_cuenta,&$adec_precomprometido,$ls_operacion);
			}
			if($lb_valido)
			{
				$ls_operacion="comprometer";
				$adec_comprometido=0;
				$lb_valido=$this->uf_calcular_disponible_por_rango($estprog[0],$estprog[1],$estprog[2],$estprog[3],$estprog[4],
																   $as_cuenta,&$adec_comprometido,$ls_operacion);
			}
			if($lb_valido)
			{
				$ls_operacion="causar";
				$adec_causado=0;
				$lb_valido=$this->uf_calcular_disponible_por_rango($estprog[0],$estprog[1],$estprog[2],$estprog[3],$estprog[4],
																   $as_cuenta,&$adec_causado,$ls_operacion);
			}
			if($lb_valido)
			{
				$ls_operacion="pagar";
				$adec_pagado=0;
				$lb_valido=$this->uf_calcular_disponible_por_rango($estprog[0],$estprog[1],$estprog[2],$estprog[3],$estprog[4],
																   $as_cuenta,&$adec_pagado,$ls_operacion);
			}
		}
		if($as_status=="S") // Cuenta Madre
		{
			$ls_sql="SELECT status,asignado,aumento,disminucion,precomprometido,comprometido,causado,pagado ".
					"  FROM spg_cuentas ".
					" WHERE codemp='".$as_codemp."' ".
					"   AND codestpro1 = '".$estprog[0]."' ".
					"   AND codestpro2 = '".$estprog[1]."' ".
					"   AND codestpro3 = '".$estprog[2]."' ".
					"   AND codestpro4 = '".$estprog[3]."' ".
					"   AND codestpro5 = '".$estprog[4]."' ".
					"   AND spg_cuenta = '".$as_cuenta."'";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_spg_saldo_select ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
				return false;
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{  
					$as_status=$row["status"];
					$adec_asignado=$row["asignado"];
					$adec_aumento=$row["aumento"];
					$adec_disminucion=$row["disminucion"];
					$adec_precomprometido=$row["precomprometido"];
					$adec_comprometido=$row["comprometido"];
					$adec_causado=$row["causado"];
					$adec_pagado=$row["pagado"];
				}
				$this->io_sql->free_result($rs_data);		
			}
		}
		return $lb_valido;
	} // end function uf_spg_saldo_select
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_saldos_update($as_codemp, $estprog, $as_cuenta, $adec_asignado, $adec_aumento, $adec_disminucion, 
								  $adec_precomprometido, $adec_comprometido, $adec_causado, $adec_pagado )
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_saldos_update
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//				   estprog //  Estructura Programatica
		//				   as_cuenta // Cuenta 
		//				   adec_asignado // Monto del Asignado
		//				   adec_aumento // Monto del Aumento
		//				   adec_disminucion //  Monto de la Disminución
		//				   adec_precomprometido // Monto del Precomprometido
		//				   adec_comprometido // Monto del comprometido
		//				   adec_causado // Monto del Causado
		//				   adec_pagado // Monto del Pagado 
		//	  Description: actualiza el saldo de una cuenta
		//	      Returns: boolean si existe o  no 
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$ls_sql="UPDATE spg_cuentas ".
				"   SET asignado='".$adec_asignado."', ".
				"       aumento='".$adec_aumento."', ".
				"       disminucion='".$adec_disminucion."', ".
			    "       precomprometido='".$adec_precomprometido."', ".
				"       comprometido='".$adec_comprometido."', ".
				"       causado='".$adec_causado."', ".
			    "  		pagado='".$adec_pagado."' ".
				" WHERE codemp='".$as_codemp."' ".
				"   AND codestpro1 ='".$estprog[0]."' ".
			    "   AND codestpro2 ='".$estprog[1]."' ".
				"   AND codestpro3 ='".$estprog[2]."' ".
				"   AND codestpro4 ='".$estprog[3]."' ".
			    "   AND codestpro5 ='".$estprog[4]."' ".
				"   AND spg_cuenta = '".$as_cuenta."' ";
		$li_rows=$this->io_sql->execute($ls_sql);
		if($li_rows===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_spg_saldos_update ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		return $lb_valido;
	} // end function uf_spg_saldos_update
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_show_error_disponible($as_programatica,$as_cuenta,$adec_asignado,$adec_aumento,$adec_disminucion,
									  $adec_precomprometido,$adec_comprometido,$adec_causado,$adec_pagado)
    { 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_show_error_disponible
		//		   Access: public 
		//       Argument: as_programatica //  Estructura Programatica
		//				   as_cuenta // Cuenta 
		//				   adec_asignado // Monto del Asignado
		//				   adec_aumento // Monto del Aumento
		//				   adec_disminucion //  Monto de la Disminución
		//				   adec_precomprometido // Monto del Precomprometido
		//				   adec_comprometido // Monto del comprometido
		//				   adec_causado // Monto del Causado
		//				   adec_pagado // Monto del Pagado 
		//	  Description: Muestra en mensaje el error de disponibilidad presupuiestaria 
		//	      Returns: mensaje
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($this->is_modo!="D")	 
		{
			$ldec_disponible = ($adec_asignado + $adec_aumento) - ($adec_disminucion + $adec_precomprometido + $adec_comprometido);
			$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
			switch($ls_modalidad)
			{
				case "1": // Modalidad por Proyecto
					$ls_programatica=str_replace("-","",$as_programatica);
					$ls_codest1=substr($ls_programatica,0,20);
					$ls_codest1=substr($ls_codest1,(strlen($ls_codest1)-20),20);
					$ls_codest2=substr($ls_programatica,20,6);
					$ls_codest2=substr($ls_codest2,(strlen($ls_codest2)-6),6);
					$ls_codest3=substr($ls_programatica,26,3);
					$ls_codest3=substr($ls_codest3,(strlen($ls_codest3)-3),3);
					$ls_programatica=$ls_codest1."-".$ls_codest2."-".$ls_codest3;

					break;
					
				case "2": // Modalidad por Presupuesto
					$ls_programatica=str_replace("-","",$as_programatica);
					$ls_codest1=substr($ls_programatica,0,20);
					$ls_codest1=substr($ls_codest1,(strlen($ls_codest1)-2),2);
					$ls_codest2=substr($ls_programatica,20,6);
					$ls_codest2=substr($ls_codest2,(strlen($ls_codest2)-2),2);
					$ls_codest3=substr($ls_programatica,26,3);
					$ls_codest3=substr($ls_codest3,(strlen($ls_codest3)-2),2);
					$ls_codest4=substr($ls_programatica,29,2);
					$ls_codest4=substr($ls_codest4,(strlen($ls_codest4)-2),2);
					$ls_codest5=substr($ls_programatica,31,2);
					$ls_codest5=substr($ls_codest5,(strlen($ls_codest5)-2),2);
					$ls_programatica=$ls_codest1."-".$ls_codest2."-".$ls_codest3."-".$ls_codest4."-".$ls_codest5;

					break;
			}
			$this->io_msg->message( '            NO EXISTE DISPONIBILIDAD  \n' .
								    '      Estructura : '.$ls_programatica.'\n'.
								    '          Cuenta : '.$as_cuenta .'\n'.
								    '        Asignado : '.number_format($adec_asignado,2,",",".").'\n'.
								    'Pre-Comprometido : '.number_format($adec_precomprometido,2,",",".").'\n'.
								    '    Comprometido : '.number_format($adec_comprometido,2,",",".").'\n'.
								    '         Causado : '.number_format($adec_causado,2,",","."). '\n'.
								    '          Pagado : '.number_format($adec_pagado,2,",",".").'\n'.
								    '         Aumento : '.number_format($adec_aumento,2,",",".").'\n'.
								    '     Disminución : '.number_format($adec_disminucion,2,",",".").'\n'.
								    '      Disponible : '.number_format($ldec_disponible,2,",",".") );
		}							  
		return true;
	} // end function uf_show_error_disponible
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_saldos_ajusta($estprog,$as_cuenta,$as_mensaje,$as_status,$adec_monto_anterior,$adec_monto_actual,
								  &$adec_asignado,&$adec_aumento,&$adec_disminucion,&$adec_precomprometido,&$adec_comprometido,
								  &$adec_causado,&$adec_pagado)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_show_error_disponible
		//		   Access: public 
		//       Argument: estprog //  Estructura Programatica
		//				   as_cuenta // Cuenta 
		//				   as_mensaje // Mensaje del Movimiento
		//				   as_status // Estatus de la cuenta
		//				   adec_monto_anterior // Monto Anterior
		//				   adec_monto_actual // Monto Actual
		//				   adec_asignado // Monto del Asignado
		//				   adec_aumento // Monto del Aumento
		//				   adec_disminucion //  Monto de la Disminución
		//				   adec_precomprometido // Monto del Precomprometido
		//				   adec_comprometido // Monto del comprometido
		//				   adec_causado // Monto del Causado
		//				   adec_pagado // Monto del Pagado 
		//	  Description: ajusta el saldo de una cuenta
		//	      Returns: boolean si es valido ó no
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_estpro=$estprog[0]."-".$estprog[1]."-".$estprog[2]."-".$estprog[3]."-".$estprog[4];
		$la_empresa=$_SESSION["la_empresa"];
		$ls_vali_nivel=$la_empresa["vali_nivel"];
		if($ls_vali_nivel==5)
		{
			$ls_formpre=str_replace("-","",$la_empresa["formpre"]);
			$ls_vali_nivel=$this->uf_spg_obtener_nivel($ls_formpre);
		}
		$lb_valido=true;
		$ldec_disponible=(($adec_asignado + $adec_aumento) - ( $adec_disminucion + $adec_comprometido + $adec_precomprometido));
		$li_nivel=$this->uf_spg_obtener_nivel($as_cuenta);
		$as_mensaje=trim(strtoupper($as_mensaje));

		/*print ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><br>";
		print "CUENTA>>>".$as_cuenta."<br>";
		print "PROGRAMATICA>>>".$ls_estpro."<br>";
		print "MENSAJE>>>".$as_mensaje."<BR>";
		print "ESTATUS>>>".$as_status."<br>";
		print "ASIGNADO>>>".$adec_asignado."<br>";
		print "AUMENTO>>>".$adec_aumento."<br>";
		print "DISMINUCIÓN>>>".$adec_disminucion."<br>";
		print "COMPROMETIDO>>>".$adec_comprometido."<br>";
		print "PRECOMPROMETIDO>>>".$adec_precomprometido."<br>";
		print "DISPONIBLE>>>".$ldec_disponible."<br>";
		print "CAUSADO>>>".$adec_causado."<br>";
		print "PAGADO>>>".$adec_pagado."<br>";
		print "ANTERIOR>>>".$adec_monto_anterior."<br>";
		print "ACTUAL>>>".$adec_monto_actual."<br>";
		print ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><br>";*/

		$li_pos_i = strpos($as_mensaje,"I"); //I-Asignacion
		if (!($li_pos_i===false))
		{
			$adec_asignado=$adec_asignado-$adec_monto_anterior+$adec_monto_actual;
			$lb_procesado=true;
		}
		$li_pos_a=strpos($as_mensaje,"A"); // A-Aumento 
		if (!($li_pos_a===false))
		{ 
			$li_monto = $ldec_disponible - $adec_monto_anterior+$adec_monto_actual;
			if(round($li_monto,2)>=0)  
			{ 
				$adec_aumento=$adec_aumento-$adec_monto_anterior+$adec_monto_actual;
			}
			else
			{
				$lb_valido = false;
				$this->io_msg->message( "La disminución del Aumento sobregira el presupuesto. ".$ls_estpro." - ".$as_cuenta." ");			
			}
			$lb_procesado=true;
		}
		$li_pos_d = strpos($as_mensaje,"D"); //	D-Disminucion
		if (!($li_pos_d===false))
		{
			$li_monto = $ldec_disponible + $adec_monto_anterior;
			if(round($adec_monto_actual,2) <= round($li_monto,2))  
			{ 
				$adec_disminucion=$adec_disminucion-$adec_monto_anterior+$adec_monto_actual; 
			}
			else
			{
				$lb_valido = false;
				$this->io_msg->message( "El monto a disminuir es mayor que la Disponibilidad. . ".$ls_estpro." - ".$as_cuenta." ");			
			}
			$lb_procesado=true;
		}
		$li_pos_r = strpos($as_mensaje,"R"); //R-PreComprometer
		if (!($li_pos_r===false))
		{
			if ($li_nivel <= $ls_vali_nivel)
			{
				$li_monto = $ldec_disponible + $adec_monto_anterior;
				if(round($adec_monto_actual,2) > round($li_monto,2))
				{
					$lb_valido = false;
					$this->uf_show_error_disponible($ls_estpro,$as_cuenta,$adec_asignado,$adec_aumento,$adec_disminucion,$adec_precomprometido,$adec_comprometido,$adec_causado,$adec_pagado);
				}				
				else
				{
					$adec_precomprometido=$adec_precomprometido-$adec_monto_anterior+$adec_monto_actual;
				}
			} 	
			else
			{
				$adec_precomprometido = $adec_precomprometido - $adec_monto_anterior + $adec_monto_actual;
			}
			$lb_procesado=true;
		}
		$li_pos_o = strpos($as_mensaje,"O"); //	O-Comprometer
		if (!($li_pos_o===false))
		{
			if ($li_nivel <= $ls_vali_nivel) 
			{
				$li_monto = $ldec_disponible + $adec_monto_anterior;
				if(round($adec_monto_actual,2) > round($li_monto,2))
				{
					$lb_valido = false;
					$this->uf_show_error_disponible($ls_estpro,$as_cuenta,$adec_asignado,$adec_aumento,$adec_disminucion,$adec_precomprometido,$adec_comprometido,$adec_causado,$adec_pagado);
				}			
				else
				{
					$adec_comprometido=$adec_comprometido-$adec_monto_anterior+$adec_monto_actual;
				}
			}	
			else
			{
				$adec_comprometido=$adec_comprometido-$adec_monto_anterior+$adec_monto_actual;
			}
			$lb_procesado=true;
		}
		$li_pos_c=strpos($as_mensaje,"C"); 	//	C-Causar
		if (!($li_pos_c===false))
		{
			if(trim($as_status)=="C") // solo valido cuenta de movimiento
			{
				$li_monto = ($adec_causado - $adec_monto_anterior + $adec_monto_actual);
				if( round($li_monto,2) <=  round($adec_comprometido,2) )
				{
					$adec_causado = $adec_causado - $adec_monto_anterior + $adec_monto_actual;
				}
				else
				{		
					$lb_valido = false;
					$this->io_msg->message("Intenta Causar mas que lo Comprometido ".$ls_estpro." - ".$as_cuenta );
				}
			}
			else
			{
				$adec_causado = $adec_causado - $adec_monto_anterior + $adec_monto_actual;
			}
			$lb_procesado = true;
		}
		$li_pos_p=strpos($as_mensaje,"P");  // P-Pagar
		if (!($li_pos_p===false))
		{
			if (trim($as_status)=="C") // solo valido cuenta de movimiento
			{
				$li_monto = ($adec_pagado - $adec_monto_anterior + $adec_monto_actual);
				if (  round($li_monto,2) <= round($adec_causado,2))
				{
					$adec_pagado = $adec_pagado - $adec_monto_anterior + $adec_monto_actual;
				}
				else
				{
					$lb_valido = false;
					$this->io_msg->message(" Intenta Pagar mas que lo Causado ".$ls_estpro." - ".$as_cuenta);
				}
			}	
			else
			{
				$adec_pagado = $adec_pagado - $adec_monto_anterior + $adec_monto_actual;
			}
			$lb_procesado = true;
		}
		if(!$lb_procesado)
		{
			$this->io_msg->message(" El codigo de mensaje es Invalido : ".$as_mensaje);
			$lb_valido = false;
		}
		return $lb_valido;
    } // end function uf_spg_saldos_ajusta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_integracion_scg($as_codemp, $as_scgcuenta, $as_procede_doc, $as_documento, $as_descripcion, $adec_monto_actual,$as_codban,$as_ctaban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_integracion_scg
		//		   Access: public 
		//       Argument: as_codemp //  Código de Empresa
		//				   as_scgcuenta // Cuenta 
		//				   as_procede_doc // Procede del Documento
		//				   as_documento // Número del Documento
		//				   as_descripcion // Descripción del Documento
		//				   adec_monto_actual // Monto Actual
		//	  Description: Este método generar un asiento contable automáticamente cuando se genera un asiento en presupuesto de gasto con operaciones de causar docuemnto.
		//	      Returns: boolean si es valido ó no
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_debhab="";
		$ls_status="";
		$ls_denominacion="";
		$ls_mensaje_error="";
		$ldec_monto=0;$li_orden=0;
		if($adec_monto_actual > 0)
		{
			$ls_debhab = "D";
		}
		else
		{
			$ls_debhab = "H";
		}
		if(!$this->io_int_scg->uf_scg_select_cuenta($as_codemp, $as_scgcuenta, &$ls_status, $ls_denominacion))
		{
			$this->io_msg->message("La cuenta contable [". trim($as_scgcuenta) ."] no existe.");
			return false;
		} 
		if($ls_status!="C")
		{ 
			$this->io_msg->message("La cuenta contable [". trim($as_scgcuenta) ."] no es de movimiento.");
			return false;
		} 
		$this->io_int_scg->is_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$this->io_int_scg->is_codemp=$as_codemp;
		$this->io_int_scg->is_procedencia=$this->is_procedencia;
		$this->io_int_scg->is_comprobante=$this->is_comprobante;
		$this->io_int_scg->as_codban=$as_codban;
		$this->io_int_scg->as_ctaban=$as_ctaban;
		if ($this->io_int_scg->uf_scg_select_movimiento($as_scgcuenta, $as_procede_doc, $as_documento, $ls_debhab, $ldec_monto, $li_orden))
		{
			$ldec_monto = $ldec_monto + $adec_monto_actual;
			$lb_valido = $this->io_int_scg->uf_scg_update_movimiento($as_codemp, $as_scgcuenta, $as_procede_doc, $as_documento, $as_documento, $as_descripcion, $as_descripcion, $ls_debhab, $ls_debhab, $adec_monto_actual, $ldec_monto);
		}					   
		else
		{
			//$lb_valido = $this->io_int_scg->uf_scg_registro_movimiento_int($as_codemp, $as_scgcuenta, $as_procede_doc, $as_documento, $ls_debhab, $as_descripcion, 0, $adec_monto_actual);
			$adec_monto_actual=abs($adec_monto_actual);
			$lb_valido = $this->io_int_scg->uf_scg_procesar_insert_movimiento($as_codemp,$this->is_procedencia,$this->is_comprobante,$this->id_fecha,$this->is_tipo,$this->is_cod_prov,$this->is_ced_ben,$as_scgcuenta, $as_procede_doc, $as_documento, $ls_debhab, $as_descripcion, 0, $adec_monto_actual,$as_codban,$as_ctaban);
		}																	 
		return $lb_valido;
	} // end function uf_spg_integracion_scg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_select_cuenta_movimiento($estprog,$as_spg_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_integracion_scg
		//		   Access: public 
		//       Argument: estprog //  Programática
		//				   as_spg_cuenta // Cuenta 
		//	  Description: Este método verifica si la cuenta posee movimientos asociados
		//	      Returns: boolean si es valido ó no
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$la_empresa=$_SESSION["la_empresa"];
		$ls_codemp=$la_empresa["codemp"];
		$ls_sql="SELECT spg_cuenta, monto, orden ".
			 	"  FROM spg_dt_cmp".		
			 	" WHERE codemp='".$ls_codemp."' ".
				"   AND codestpro1 ='".$estprog[0]."' ".
				"   AND codestpro2 ='".$estprog[1]."' ".
				"   AND codestpro3 ='".$estprog[2]."' ".
				"   AND codestpro4 = '".$estprog[3]."' ".
			 	"   AND codestpro5 = '".$estprog[4]."' ".
				"   AND spg_cuenta='".$as_spg_cuenta."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_spg_select_cuenta_movimiento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	} // end function uf_spg_select_cuenta_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_padcuenta_plan($as_formpre,$as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_padcuenta_plan
		//		   Access: public 
		//       Argument: as_formpre //  Programática
		//				   as_cuenta // Cuenta 
		//	  Description: Este método rellena valores en 0 a la derecha de la cuenta
		//	      Returns: boolean si es valido ó no
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_MaxNivel=0;
		$li_longitud=0;
		$li_len_cadena=0;
		$ls_Cadena="";
		$ls_formato="";
		$ls_formatoaux="";
		$ls_formato=trim($as_formpre);
		$ls_formatoaux=str_replace( "-", "",$ls_formato);
		$ls_formatoaux=$this->io_function->uf_trim($ls_formatoaux);
		$li_longitud=strlen($ls_formatoaux);
		$ls_cadena=$this->io_function->uf_trim($as_cuenta);
		$li_len_cadena=strlen($ls_cadena);
		$ls_cadena=$this->io_function->uf_rellenar_der ( $ls_cadena , 0 , $li_longitud);
		$as_formpre=$ls_formatoaux;
		return $ls_cadena;
	} // end function uf_spg_padcuenta_plan
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_pad_cuenta($as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_pad_cuenta
		//		   Access: public 
		//       Argument: as_cuenta // Cuenta 
		//	  Description: Este método rellena valores en 0 a la derecha de la cuenta
		//	      Returns: boolean si es valido ó no
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->uf_init_niveles();
		$li_MaxNivel=count($this->ia_niveles_spg);
		$ls_cadena=trim($as_cuenta);
		$ls_cadena=$this->io_function->uf_rellenar_der ( $ls_cadena , "0" , $this->ia_niveles_spg[$li_MaxNivel-1] ) ;
		return $ls_cadena;
	} // end function uf_spg_pad_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_cuenta_sin_cero($as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_cuenta_sin_cero
		//		   Access: public 
		//       Argument: as_cuenta // Cuenta 
		//	  Description: Este método retorna la cuenta sin ceros a la derecha
		//	      Returns: boolean si es valido ó no
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->uf_init_niveles();
		$li_nivel=0;
		$li_anterior=0;
		$ls_cadena="";
		$li_nivel=$this->uf_spg_obtener_nivel($as_cuenta);
		$li_anterior=$this->ia_niveles_spg[$li_nivel] ;
		$li_len=strlen($li_anterior);
		$ls_cadena=substr($as_cuenta, 0, $li_anterior+1);
		return $ls_cadena;
	} // end function uf_spg_cuenta_sin_cero
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_cuenta_recortar_next($as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_cuenta_recortar_next
		//		   Access: public 
		//       Argument: as_cuenta // Cuenta 
		//	  Description: Este método retorna la cuenta sin ceros a la derecha
		//	      Returns: string 
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->uf_init_niveles();
		$li_nivel=0;
		$li_anterior=0;
		$ls_cadena="";
		$li_nivel=$this->uf_spg_obtener_nivel( $as_cuenta );
		$li_anterior=$this->ia_niveles_spg[ $li_nivel ] ;
		$li_len=strlen($li_anterior);
		$ls_cadena=substr($as_cuenta, 0, $li_anterior+1);
		return $ls_cadena;
	} // end function uf_spg_cuenta_recortar_next
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_spg_insert_cuenta($as_codest1,$as_codest2,$as_codest3,$as_codest4,$as_codest5,$as_spg_cuenta,$as_denominacion,
								  $as_sc_cuenta,$as_status,$as_nivel,$as_referencia)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_insert_cuenta
		//		   Access: public 
		//       Argument: as_codest1 // Estructura Programatica 1
		//       		   as_codest2 // Estructura Programatica 2
		//       		   as_codest3 // Estructura Programatica 3
		//       		   as_codest4 // Estructura Programatica 4
		//       		   as_codest5 // Estructura Programatica 5
		//       		   as_spg_cuenta // Cuenta 
		//       		   as_denominacion // Denominación de la cuenta
		//       		   as_sc_cuenta // cuenta Contable
		//       		   as_status // estatus de la Cuenta Contable
		//       		   as_nivel // Nivel de la Cuenta
		//       		   as_referencia // Cuenta de referencia
		//	  Description: Este método inserta una cuenta de gasto en la tabla maestra 
		//	      Returns: un boolean 
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
        $data=$_SESSION["la_empresa"];
        $ls_codemp=$data["codemp"];
	    $ls_sql= " INSERT INTO spg_cuentas(codemp,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5, spg_cuenta, denominacion, status, ".
	             " sc_cuenta, asignado, precomprometido, comprometido, causado, pagado, aumento, disminucion, distribuir, enero, ".
	             " febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, noviembre, diciembre, nivel, referencia) ".
	             " values('".$ls_codemp."','".$as_codest1."','".$as_codest2."','".$as_codest3."','".$as_codest4."','".$as_codest5."', ".
				 " '".$as_spg_cuenta."','".$as_denominacion."','".$as_status."','".$as_sc_cuenta."',0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,".
				 " 0,".$as_nivel.",'".$as_referencia."')";
	    $li_rows=$this->io_sql->execute($ls_sql);
        if($li_rows===false)
	    {
		   $lb_valido=false;	
		   $this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_spg_insert_cuenta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
	    }
	    return $lb_valido;
    } // end function uf_spg_insert_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_update_cuenta($as_codest1,$as_codest2,$as_codest3,$as_codest4,$as_codest5,$as_spg_cuenta,$as_denominacion,
								  $as_sc_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_update_cuenta
		//		   Access: public 
		//       Argument: as_codest1 // Estructura Programatica 1
		//       		   as_codest2 // Estructura Programatica 2
		//       		   as_codest3 // Estructura Programatica 3
		//       		   as_codest4 // Estructura Programatica 4
		//       		   as_codest5 // Estructura Programatica 5
		//       		   as_spg_cuenta // Cuenta 
		//       		   as_denominacion // Denominación de la cuenta
		//       		   as_sc_cuenta // cuenta Contable
		//	  Description: Este método actualiza una cuenta de gasto en la tabla maestra 
		//	      Returns: un boolean 
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$data=$_SESSION["la_empresa"];
		$ls_codemp=$data["codemp"];
		$ls_sql="UPDATE spg_cuentas ".
				"   SET denominacion='".$as_denominacion."', ".
				"       sc_cuenta='".$as_sc_cuenta."' ".
		        " WHERE codemp='".$ls_codemp."' ".
				"   AND codestpro1='".$as_codest1."' ".
				"   AND codestpro2='".$as_codest2."' ".
				"   AND codestpro3='".$as_codest3."' ".
				"   AND codestpro4='".$as_codest4."' ".
				"   AND codestpro5='".$as_codest5."' ".
				"   AND spg_cuenta='".$as_spg_cuenta."'";
		$li_numrows=$this->io_sql->execute($ls_sql);
        if($li_numrows===false)
	    {
		   $lb_valido=false;	
		   $this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_spg_update_cuenta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
	    }
		return $lb_valido;
	} // end function uf_spg_update_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_select_cuenta_sin_cero($is_codemp,$as_cuenta_cero,$aa_estpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_select_cuenta_sin_cero
		//		   Access: public 
		//       Argument: is_codemp // Código de Empresa
		//       		   as_cuenta_cero // Cuenta 
		//       		   aa_estpro // Arrelgo de la Estructura Programatica
		//	  Description: Verifica la cantidad existente de la consulta
		//	      Returns: un boolean 
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=false;
	    $li_rows=0;
		$ls_sql="SELECT count(*) as nveces ".
				"  FROM spg_cuentas ".
		        " WHERE codemp='".$is_codemp."' ".
				"   AND spg_cuenta LIKE '".$as_cuenta_cero."%' ".
				"   AND codestpro1='".$aa_estpro[0]."' ".
				"   AND codestpro2='".$aa_estpro[1]."' ".
				"   AND codestpro3='".$aa_estpro[2]."' ".
				"   AND codestpro4='".$aa_estpro[3]."' ".
				"   AND codestpro5='".$aa_estpro[4]."'";
		$rs_data = $this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;	
			$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_spg_select_cuenta_sin_cero ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_rows=$row["nveces"];
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $li_rows;
	 } // end function uf_spg_select_cuenta_sin_cero
	//-----------------------------------------------------------------------------------------------------------------------------------
	 
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_select_cuenta($as_codemp,$aa_estprog,$as_spg_cuenta,&$as_status,&$as_denominacion,&$as_scgcuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_select_cuenta
		//		   Access: public 
		//       Argument: is_codemp // Código de Empresa
		//       		   aa_estpro // Arrelgo de la Estructura Programatica
		//       		   as_spg_cuenta // Cuenta 
		//       		   as_status // Estatus de la Cuenta
		//       		   as_denominacion // denominación de la cuenta
		//       		   as_scgcuenta // Cuenta Contable
		//	  Description: Verifica si existe o no la cuenta y retorna informacion de la cuenta
		//	      Returns: un boolean 
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_cuenta="";
		$ls_denominacion="";
		$ls_status="";
		$ls_scgcuenta="";
		$lb_existe=false;
		$ls_estructura=$aa_estprog[0]."-".$aa_estprog[1]."-".$aa_estprog[2]."-".$aa_estprog[3]."-".$aa_estprog[4];
		$ls_sql="SELECT spg_cuenta, status, denominacion, sc_cuenta ".
				"  FROM spg_cuentas ".
			    " WHERE codemp='".$as_codemp."' ".
				"   AND codestpro1 = '".$aa_estprog[0]."' ".
			    "   AND codestpro2 = '".$aa_estprog[1]."' ".
				"   AND codestpro3 ='".$aa_estprog[2]."' ".
			    "   AND codestpro4 ='".$aa_estprog[3]."' ".
				"   AND codestpro5 ='".$aa_estprog[4]."' ".
			    "   AND rtrim(spg_cuenta) ='".rtrim($as_spg_cuenta)."'" ;
		$rs_data = $this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;	
			$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_spg_select_cuenta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_denominacion=$row["denominacion"];
				$as_denominacion=$ls_denominacion;
				$ls_status=$row["status"];
				$as_status=$ls_status;
				$ls_scgcuenta=$row["sc_cuenta"];
				$as_scgcuenta=$ls_scgcuenta;
				$lb_existe = true;	 			
			}
			else
			{
				$this->is_msg_error = "La cuenta Presupuestaria ".$ls_estructura."::".$as_spg_cuenta." no esta registrada";
			}    
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	} // end function uf_spg_select_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_delete_cuenta($as_codemp, $aa_estprog, $as_spg_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_delete_cuenta
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//       		   aa_estpro // Arrelgo de la Estructura Programatica
		//       		   as_spg_cuenta // Cuenta 
		//	  Description: Borra de la tabla maestra la cuenta de gasto
		//	      Returns: un boolean 
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM spg_cuentas ".
				" WHERE codemp='".$as_codemp."' ".
				"	AND codestpro1 = '".$aa_estprog[0]."' ".
				"   AND codestpro2 = '".$aa_estprog[1]."' ".
				"   AND codestpro3 ='".$aa_estprog[2]."' ".
				"   AND codestpro4 ='".$aa_estprog[3]."' ".
				"   AND codestpro5 ='".$aa_estprog[4]."' ".
				"   AND spg_cuenta ='".$as_spg_cuenta."'" ;
		$li_rows = $this->io_sql->execute($ls_sql);
		if($li_rows===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_spg_delete_cuenta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		return $lb_valido;
	} // end function uf_spg_delete_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_plan_unico_cuenta($as_cuenta,$as_denominacion,$as_status)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_plan_unico_cuenta
		//		   Access: public 
		//       Argument: as_cuenta // Cuenta
		//       		   as_denominacion // Denominación de la cuenta
		//       		   as_status // estatus de la Cuenta 
		//	  Description: Método que inserta cuenta y denominacion en el plan unico de recursos
		//	      Returns: un boolean 
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($this->uf_select_plan_unico_cuenta($as_cuenta,$as_denominacion))
		{
			if($as_status=='C')		   
			{
				$ls_sql="UPDATE sigesp_plan_unico_re ".
						"   SET denominacion='".$as_denominacion."'".
						" WHERE sig_cuenta='".trim($as_cuenta)."'" ;
				$li_rows=$this->io_sql->execute($ls_sql);
				if($li_rows===false)
				{
					$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_insert_plan_unico_cuenta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
					return false;
				}
			}
			else
			{
				$this->is_msg_error="Cuenta ya existe introduzca un nuevo codigo.";
				return false;
			}
		}
		else
		{
			$ls_sql=" INSERT INTO sigesp_plan_unico_re (sig_cuenta,denominacion)".
					" VALUES('".trim($as_cuenta)."' , '".trim($as_denominacion)."')" ;
			$li_rows=$this->io_sql->execute($ls_sql);
			if($li_rows===false)
			{
				$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_insert_plan_unico_cuenta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
				return false;
			}
		}
		return $lb_valido;
	} // end function uf_insert_plan_unico_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function  uf_select_plan_unico_cuenta($as_cuenta,$as_denominacion)
    {	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_plan_unico_cuenta
		//		   Access: public 
		//       Argument: as_cuenta // Cuenta
		//       		   as_denominacion // Denominación de la cuenta
		//	  Description: Verifica si existe o no en la tabla de SIGESP_Plan_Unico
		//	      Returns: un boolean 
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = false;
		$ls_sql="SELECT sig_cuenta, denominacion ".
				"  FROM sigesp_plan_unico_re ".
		 		" WHERE sig_cuenta='". $as_cuenta ."'"; 
		$rs_data = $this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_select_plan_unico_cuenta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
				$is_den_plan_cta=$row["denominacion"];
				$as_denominacion=$row["denominacion"];
			}
			$this->io_sql->free_result($rs_data);	   
		}
		return $lb_existe;
	} // end function uf_select_plan_unico_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_calcular_disponible_por_rango($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
	                                          $as_spg_cuenta,&$adec_monto,$as_operacion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_calcular_disponible_por_rango
		//		   Access: public 
		//       Argument: as_codestpro1 // Código de Estructura Programatica 1
		//       		   as_codestpro2 // Código de Estructura Programatica 2
		//       		   as_codestpro3 // Código de Estructura Programatica 3
		//       		   as_codestpro4 // Código de Estructura Programatica 4
		//       		   as_codestpro5 // Código de Estructura Programatica 5
		//       		   as_spg_cuenta // cuenta Presupuestaria
		//       		   adec_monto // Monto del Movimiento
		//       		   as_operacion // Operación del movimiento
		//	  Description: Método que consulta y suma dependiando de la operacion(aumento,disminucion,precompromiso,compromiso)
		//	      Returns: Retorna monto asignado
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	    $lb_valido=true;
		$ldec_monto=0;
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ld_fecha=$this->io_function->uf_convertirdatetobd($_SESSION["fechacomprobante"]);
		$ld_inicio=$this->io_function->uf_convertirdatetobd($_SESSION["la_empresa"]["periodo"]);
		$ls_sql="SELECT COALESCE(SUM(monto),0) As monto ".
                "  FROM spg_dt_cmp, spg_operaciones  ".
                " WHERE codemp='".$ls_codemp."' ".
                "   AND spg_operaciones.".$as_operacion."=1 ".
				"   AND spg_dt_cmp.spg_cuenta = '".$as_spg_cuenta."' ".
				"   AND fecha >='".$ld_inicio."' AND fecha <='".$ld_fecha."' ".
				"   AND spg_dt_cmp.codestpro1='".$as_codestpro1."' ".
				"   AND spg_dt_cmp.codestpro2='".$as_codestpro2."' ".
			    "   AND spg_dt_cmp.codestpro3='".$as_codestpro3."' ".
				"   AND spg_dt_cmp.codestpro4='".$as_codestpro4."' ".
				"   AND spg_dt_cmp.codestpro5='".$as_codestpro5."' ".
				"   AND spg_dt_cmp.operacion=spg_operaciones.operacion ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
            $this->io_msg->message("Error en uf_calcular_disponible_por_rango ".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
			   $ldec_monto = $row["monto"];
			}
			$this->io_sql->free_result($rs_data);
		}
		$adec_monto = $ldec_monto;
		return $lb_valido;
	} // fin function uf_calcular_disponible_por_rango
	//-----------------------------------------------------------------------------------------------------------------------------------


	////////////////////////////////////////////////// MÉTODOS CON TRANSACCIONES /////////////////////////////////////////////////

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_update_movimiento($as_codemp, $as_procede, $as_comprobante, $as_fecha, $as_cod_prov, $as_ced_bene, 
									  $as_descripcion, $as_tipo, $ai_tipo_comp, $estprog_i, $estprog_f, $as_cuenta_i, 
									  $as_cuenta_f, $as_procede_doc_i, $as_procede_doc_f, $as_documento_i, $as_documento_f, 
									  $as_descripcion_i, $as_descripcion_f, $as_mensaje_i, $as_mensaje_f, $ad_monto_i, $ad_monto_f)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_update_movimiento
		//		   Access: public 
		//       Argument:
		//	  Description: Método que actualiza la información presupuestaria de un movimiento SPG
		//	      Returns: un boolean 
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$this->is_codemp=$as_codemp;
		$this->is_procedencia=$as_procede;
		$this->id_fecha=$as_fecha;
		$this->is_cod_prov=$as_cod_prov;
		$this->is_ced_ben=$as_ced_bene;
		$this->is_descripcion=$as_descripcion;
		$this->is_tipo=$as_tipo;
		$this->ii_tipo_comp=$ai_tipo_comp;
		$this->is_comprobante=$as_comprobante;
		$ls_operacion_i=$this->uf_operacion_mensaje_codigo( $as_mensaje_i );
		$ls_operacion_f=$this->uf_operacion_mensaje_codigo( $as_mensaje_f );
		$ls_programatica_i=$estprog_i[0].$estprog_i[1].$estprog_i[2].$estprog_i[3].$estprog_i[4];
		$ls_programatica_i=$estprog_f[0].$estprog_f[1].$estprog_f[2].$estprog_f[3].$estprog_f[4];
		if(!($this->uf_spg_select_cuenta($as_codemp, $estprog_i, $as_cuenta_i, &$ls_status_i, $ls_denominacion_i, &$as_scgcuenta_i)))
		{   
			$this->io_msg->message("La cuenta [ ".$ls_programatica_i." ".$as_cuenta_i." ] no esta definida en el plan de cuentas presupuestario.");
			return false;
		}
		// valido el estatus de la cuenta
		if($ls_status_i!="C")
		{
			$this->io_msg->message("La cuenta [ ".$ls_programatica_i." ".$as_cuenta_i." ] no es de movimiento.");
			return false;	
		}
		// valido si existe la cuenta f.
		if(!($this->uf_spg_select_cuenta($as_codemp,$estprog_f,$as_cuenta_f,&$ls_status_f,$ls_denominacion_f,&$as_scgcuenta_f)))
		{
			$this->io_msg->message("La cuenta [ ".$ls_programatica_f." ".$as_cuenta_f." ] no esta definida en el plan de cuentas presupuestario.");
			return false;	
		}
		// valido el estatud de la cuenta
		if($ls_status_f!="C")
		{
			$this->io_msg->message("La cuenta [ ".$ls_programatica_f." ".$as_cuenta_f." ] no es de movimiento.");
			return false;
		}
		// valido la fecha del movimiento con respecto al mes si esta abierto
		if (!($this->io_fecha->uf_valida_fecha_mes( $as_codemp, $as_fecha )))
		{
			$is_msg_error = $this->sig_int->$is_msg_error ;
			return false;
		}
		// verifico si existe el movimiento presupuestario 
		if(!($this->uf_spg_select_movimiento($estprog_i, $as_cuenta_i, $as_procede_doc_i, $as_documento_i, $ls_operacion_i, &$ld_monto, &$ld_orden, $as_fecha)))
		{
			$this->io_msg->message("El movimiento no existe.");
			return false;  										  
		}
		if ($ld_monto <> $ad_monto_i)
		{
			$this->io_msg->message("El Monto anterior no coincide SPG.upd_movimiento");
			return false;
		}
		// inicio transacción de data
		$this->io_sql->begin_transaction();
		$lb_valido = $this->uf_spg_saldo_actual($as_codemp,$estprog_i,$as_cuenta_i,$as_mensaje_i,$ad_monto_i,0);
		//$lb_valido = $this->uf_spg_delete_movimiento($estprog_i, $as_cuenta_i, $as_procede_doc_i, $as_documento_i, $ls_operacion_i);
		if($lb_valido)
		{
			$lb_valido = $this->uf_spg_delete_movimiento($estprog_i, $as_cuenta_i, $as_procede_doc_i, $as_documento_i, $ls_operacion_i);
			//$lb_valido = $this->uf_spg_insert_movimiento($estprog_f, $as_cuenta_f, $as_procede_doc_f, $as_documento_f, $ls_operacion_f, $as_descripcion_f, $ad_monto_f);
			if ($lb_valido)
			{
				$lb_valido = $this->uf_spg_comprobante_actualizar($ad_monto_i, 0, $ai_tipo_comp);

				//$lb_valido = $this->uf_spg_saldo_actual($as_codemp,$estprog_i,$as_cuenta_i,$as_mensaje_i,$ad_monto_i,0);
				if ($lb_valido)
				{ 
				    $lb_valido = $this->uf_spg_saldo_actual( $as_codemp, $estprog_f, $as_cuenta_f, $as_mensaje_f, 0, $ad_monto_f);
					if ($lb_valido)
					{
						$lb_valido = $this->uf_spg_insert_movimiento($estprog_f, $as_cuenta_f, $as_procede_doc_f, $as_documento_f, $ls_operacion_f, $as_descripcion_f, $ad_monto_f);
						//$lb_valido = $this->uf_spg_comprobante_actualizar($ad_monto_i, 0, $ai_tipo_comp);
						if ($lb_valido)
						{
							$lb_valido = $this->uf_spg_comprobante_actualizar(0, $ad_monto_f, $ai_tipo_comp);
						}
					}      
					//Integracion con contabilidad
					$as_mensaje_i=strtoupper($as_mensaje_i);
					$li_pos_c=strpos($as_mensaje_i,"C");
					if (($lb_valido)&&($this->ib_AutoConta)&&(!($li_pos_c===false)))
					{
						if (!($this->int_scg->uf_scg_select_cuenta($as_codemp,$as_cuenta_i, &$ls_status_i,&$ls_denominacion_i)))
						{
							$this->io_msg->message(" La cuenta contable " .trim($as_cuenta_i)." no existe  ");
							$lb_valido=false;
						}
						//valido que sea una cuenta de movimiento
						if (($lb_valido) && ($ls_status_i<>"C"))
						{
							$this->io_msg->message(" La cuenta contable " .trim($as_cuenta_i)." no es de movimiento ");
							$lb_valido=false;
						}
						if ($lb_valido)
						{
							if($ld_monto_i>0)
							{
								$ls_debhab = "D";
							}
							else
							{
								$ls_debhab = "H";
							}
							$lb_valido=$this->int_scg->uf_scg_procesar_delete_movimiento($as_codemp,$as_procede,$as_comprobante,$as_fecha,$as_cuenta_i,$as_procede_doc_i,$as_documento_i,$ls_debhab,$ad_monto_i);
						}
					}
					$as_mensaje_f=strtoupper($as_mensaje_f);
					$li_pos_c=strpos($as_mensaje_f,"C");
					if (($lb_valido)&&($this->ib_AutoConta)&&(!($li_pos_c===false))) 
					{
						if (!$this->int_scg->uf_scg_select_cuenta($as_codemp,$as_cuenta_f,&$ls_status_f,&$ls_denominacion_i))
						{
							$this->io_msg->message(" La cuenta contable " .trim($as_cuenta_f)." no existe  ");
							$lb_valido=false;
						}
						//valido que sea una cuenta de movimiento
						if (($lb_valido) && ($ls_status_i<>"C"))
						{
							$this->io_msg->message(" La cuenta contable " .trim($as_cuenta_f)." no es de movimiento ");
							$lb_valido=false;
						}
						if($lb_valido)
						{
							if($ld_monto_i>0)
							{
								$ls_debhab = "D";
							}
							else
							{
								$ls_debhab = "H";
							}
							$lb_valido= $this->int_scg->uf_scg_procesar_insert_movimiento($as_codemp,$as_procede, $as_comprobante, $as_fecha,
														$this->is_tipo,$this->is_cod_prov,$this->is_ced_ben,$as_cuenta_f,
														$as_procede_doc_f, $as_documento_f,$ls_debhab,$as_descripcion_f,
														$adec_monto_anterior, $ad_monto_f );						
						}
					} 
				}
			}  
		}   
		//Realizo la Transacción 
		if($lb_valido)
		{
			$this->io_sql->commit(); 
			$lb_valido = true;   
		}
		else
		{
			$this->io_sql->rollback();
			$lb_valido = false;
		}
		return $lb_valido;
	 } // end function uf_spg_update_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>