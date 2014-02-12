<?php
class sigesp_spg_c_comprobante
{
	var $is_msg_error;
	var $io_sql;
	var $io_include;
	var $io_int_scg;
	var $io_int_spg;
	var $io_msg;
	var $io_function;
 //---------------------------------------------------------------------------------------------------------------------------------

 //---------------------------------------------------------------------------------------------------------------------------------
	function sigesp_spg_c_comprobante()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_fecha.php");	
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_sigesp_int.php");	
		require_once("../shared/class_folder/class_sigesp_int_int.php");	
		require_once("../shared/class_folder/class_sigesp_int_scg.php");
		require_once("../shared/class_folder/class_sigesp_int_spg.php");
		require_once("../shared/class_folder/class_sigesp_int_spi.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
        /*require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");*/
	
		$this->io_function=new class_funciones();	
		$this->sig_int=new class_sigesp_int();
		$this->io_fecha=new class_fecha();
		$this->io_include=new sigesp_include();	
		$this->io_connect=$this->io_include->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);
		$this->io_msg = new class_mensajes();
		$this->io_int_spg=new class_sigesp_int_spg();	
		$this->io_int_scg=new class_sigesp_int_scg();
		$this->io_int_int=new class_sigesp_int_int();
		$this->is_msg_error="";
		/*$this->io_rcbsf= new sigesp_c_reconvertir_monedabsf();*/
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
	}
 //---------------------------------------------------------------------------------------------------------------------------------

 //---------------------------------------------------------------------------------------------------------------------------------
	function uf_generar_num_cmp($as_codemp,$as_procede)
	{
		 // $ls_sql="SELECT max(comprobante) as comprobante FROM sigesp_cmp WHERE codemp='".$as_codemp."' AND procede='".$as_procede."' ORDER BY comprobante DESC";
		  $ls_sql = "SELECT max(comprobante) as comprobante ".
                    "   FROM sigesp_cmp ".
                    "      WHERE      codemp='".$as_codemp."' ".
					"					 AND procede='".$as_procede."' " .
					"					 AND (comprobante not like '%A%' AND comprobante not like '%a%')".
					"					 AND (comprobante not like '%B%' AND comprobante not like '%b%')".
					"					 AND (comprobante not like '%C%' AND comprobante not like '%c%')".
					"					 AND (comprobante not like '%D%' AND comprobante not like '%d%')".
					"					 AND (comprobante not like '%E%' AND comprobante not like '%e%')".
					"					 AND (comprobante not like '%F%' AND comprobante not like '%f%')".
					"					 AND (comprobante not like '%G%' AND comprobante not like '%g%')".
					"					 AND (comprobante not like '%H%' AND comprobante not like '%h%')".
					"					 AND (comprobante not like '%I%' AND comprobante not like '%i%')".
					"					 AND (comprobante not like '%J%' AND comprobante not like '%j%')".
					"					 AND (comprobante not like '%K%' AND comprobante not like '%k%')".
					"					 AND (comprobante not like '%L%' AND comprobante not like '%l%')".
					"					 AND (comprobante not like '%M%' AND comprobante not like '%m%')".
					"					 AND (comprobante not like '%N%' AND comprobante not like '%n%')".
					"					 AND (comprobante not like '%O%' AND comprobante not like '%o%')".
					"					 AND (comprobante not like '%P%' AND comprobante not like '%p%')".
					"					 AND (comprobante not like '%Q%' AND comprobante not like '%q%')".
					"					 AND (comprobante not like '%R%' AND comprobante not like '%r%')".
					"					 AND (comprobante not like '%S%' AND comprobante not like '%s%')".
					"					 AND (comprobante not like '%T%' AND comprobante not like '%t%')".
					"					 AND (comprobante not like '%U%' AND comprobante not like '%u%')".
					"					 AND (comprobante not like '%V%' AND comprobante not like '%v%')".
					"					 AND (comprobante not like '%W%' AND comprobante not like '%w%')".
					"					 AND (comprobante not like '%X%' AND comprobante not like '%x%')".
					"					 AND (comprobante not like '%Y%' AND comprobante not like '%y%')".
					"					 AND (comprobante not like '%Z%' AND comprobante not like '%z%')".
					"					  ORDER BY comprobante DESC";
		  $rs_funciondb=$this->io_sql->select($ls_sql);
		  if ($row=$this->io_sql->fetch_row($rs_funciondb))
		  { 
			  $codigo=$row["comprobante"];
			  settype($codigo,'int');                             // Asigna el tipo a la variable.
			  $codigo = $codigo + 1;                              // Le sumo uno al entero.
			  settype($codigo,'string');                          // Lo convierto a varchar nuevamente.
			  $ls_codigo=$this->io_function->uf_cerosizquierda($codigo,15);
		  }
		  else
		  {
			  $codigo="1";
			  $ls_codigo=$this->io_function->uf_cerosizquierda($codigo,15);
		  }
		return $ls_codigo;
	}
 //---------------------------------------------------------------------------------------------------------------------------------

	function uf_sigesp_insert_comprobante($as_codemp,$as_procede,$as_comprobante,$as_fecha,$ai_tipo_comp,$as_descripcion,$as_tipo,
										  $as_cod_prov,$as_ced_ben,$as_codban,$as_ctaban, $as_rendfon='0',$as_codfuefin='--')
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sigesp_insert_comprobante
		//		   Access: public 
		//       Argument: as_codemp // Código de empresa
		//       		   as_procedencia // Procedencia del documento
		//       		   as_comprobante // Número de Comprobante
		//       		   as_fecha // Fecha del Comprobante
		//       		   ai_tipo_comp // Tipo de Comprobante
		//       		   as_descripcion // Descripción del Comprobante
		//       		   as_tipo // Tipo de Destino si es proveedor ó  Beneficiario
		//       		   as_cod_pro // Código de Proveedor
		//       		   as_ced_bene // Cédula del Beneficiario
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta de Banco
		//                 $as_rendfon
		//	  Description: Este método inserta la cabecera de un comprobante
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Jennifer Rivero	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_fec=$this->io_function->uf_convertirdatetobd($as_fecha);
		$ls_sql="INSERT INTO sigesp_cmp (codemp, procede, comprobante, fecha, descripcion, tipo_comp, tipo_destino, cod_pro, ".
				" ced_bene, total, codban, ctaban, estrenfon, codfuefin) VALUES ('".$as_codemp."', '".$as_procede."', '".$as_comprobante."', '".$ls_fec."',".
				"'".$as_descripcion."', ".$ai_tipo_comp.", '".$as_tipo."', '".$as_cod_prov."', '".$as_ced_ben."', 0, '".$as_codban."',".
				"'".$as_ctaban."','".$as_rendfon."','".$as_codfuefin."')"; 
		$li_numrows=$this->io_sql->execute($ls_sql);
		if($li_numrows===false)
		{
			$lb_valido=false;			
		}
		return $lb_valido;
	} // end function uf_sigesp_insert_comprobante

 //---------------------------------------------------------------------------------------------------------------------------------
 	function uf_sigesp_update_comprobante($as_codemp,$as_procede,$as_comprobante,$as_fecha,$ai_tipo_comp,$as_descripcion,$as_tipo,
										  $as_cod_prov,$as_ced_ben,$as_codban,$as_ctaban)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sigesp_update_comprobante
		//		   Access: public 
		//       Argument: as_codemp // Código de empresa
		//       		   as_procede // Procedencia del documento
		//       		   as_comprobante // Número de Comprobante
		//       		   as_fecha // Fecha del Comprobante
		//       		   ai_tipo_comp // Tipo de Comprobante
		//       		   as_descripcion // Descripción del Comprobante
		//       		   as_tipo // Tipo de Destino si es proveedor ó  Beneficiario
		//       		   as_cod_pro // Código de Proveedor
		//       		   as_ced_bene // Cédula del Beneficiario
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta de Banco
		//	  Description: Este método actualiza la cabecera de un comprobante
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Jennifer Rivero		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_fecha=$this->io_function->uf_convertirdatetobd($as_fecha);
		$ls_sql="UPDATE sigesp_cmp ".
				"   SET descripcion='".$as_descripcion."' ".
		        " WHERE codemp='".$as_codemp."' ".
				"   AND procede='".$as_procede."' ".
				"   AND comprobante='".$as_comprobante."' ".
				"   AND fecha='".$ls_fecha."'".
				"   AND codban='".$as_codban."'".
				"   AND ctaban='".$as_ctaban."'"; 
		$li_numrows=$this->io_sql->execute($ls_sql);
		if($li_numrows===false)
		{
			$this->is_msg_error = "CLASE->sigesp_spg_c_comprobante MÉTODO->uf_sigesp_update_comprobante ERROR->".
			                      $this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		return $lb_valido;
	} // end function uf_sigesp_update_comprobante
 ///-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar_automatico(&$as_comprobante,$ad_fecha,$as_proccomp,$as_desccomp,$as_prov,$as_bene,$as_tipo,
	                               $ai_tipo_comp,$as_codban,$as_ctaban, $as_rendfon='0',$as_codfuefin='--',$as_existe)
	{
		$lb_valido=false;
		$dat=$_SESSION["la_empresa"];
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$_SESSION["fechacomprobante"]=$ad_fecha;		
		if($this->uf_valida_datos_cmp($as_comprobante,$ad_fecha,$as_proccomp,$as_desccomp,&$as_prov,&$as_bene,$as_tipo))
		{	
			  if ($as_existe=="N")
			  {					
					$ls_existecomp=$this->uf_verificar_comprobante($ls_codemp,$as_proccomp,$as_comprobante);
					if ($ls_existecomp)
					{
						$as_comprobante=$this->uf_generar_num_cmp($ls_codemp,$as_proccomp);
						$this->io_msg->message("Se genero el comprobante ".$as_comprobante);
					}
			   			 		  
				    $lb_valido=$this-> uf_sigesp_insert_comprobante($ls_codemp,$as_proccomp,$as_comprobante,$ad_fecha,$ai_tipo_comp,
																   $as_desccomp,$as_tipo, $as_prov,$as_bene,$as_codban,
																   $as_ctaban, $as_rendfon='0',$as_codfuefin);
				   if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
				   {
						$this->io_sql->rollback();
						$as_comprobante=$this->uf_generar_num_cmp($ls_codemp,$as_proccomp);
						$this->io_msg->message("Se genero el comprobante ".$as_comprobante);
						$lb_valido=$this->uf_sigesp_insert_comprobante($ls_codemp,$as_proccomp,$as_comprobante,$ad_fecha,
						                                                $ai_tipo_comp,$as_desccomp,$as_tipo, $as_prov,$as_bene,
																		$as_codban,$as_ctaban, $as_rendfon='0',$as_codfuefin='--');
						
				   }///fin del if 
			   }
			   else
			   {
			   		$lb_valido=$this->uf_sigesp_update_comprobante($ls_codemp,$as_proccomp,$as_comprobante,$ad_fecha,$ai_tipo_comp,
					                                   $as_desccomp,$as_tipo,$as_prov,$as_bene,$as_codban,$as_ctaban);
													   
					if ($lb_valido)
					{
						$lb_valido=$this->uf_eliminar_dt_comprobante($ls_codemp,$as_proccomp,$as_comprobante,$ad_fecha,$as_codban,$as_ctaban);
					}
			   }
			   if (!$lb_valido)
			   {
				  $this->io_msg->message("Error al procesar el comprobante Presupuestario  ".$this->io_int_spg->is_msg_error);
			   } 		   
			   $ib_valido = $lb_valido;
			   
			   if($lb_valido)
			   {
				  $ib_new = $this->io_int_spg->ib_new_comprobante;
			   }	
			   else  
			   {  
			    	$lb_valido=true; 
			   } 	
		}
		else 
		{ 
			$this->io_msg->message("Error en valida datos comprobante");
		}
		return $lb_valido;
	}// fin de uf_guardar_automatico
 //---------------------------------------------------------------------------------------------------------------------------------

 //---------------------------------------------------------------------------------------------------------------------------------
	function uf_eliminar_dt_comprobante($as_codemp,$as_procede,$as_comprobante,$adt_fecha,$as_codban,$as_ctaban)
	{
	  $lb_valido=true; 
	  // Elimino los detalles presupuestarios
	  $rs_data=$this->uf_cargar_dt_comprobante($as_codemp,$as_procede,$as_comprobante,$adt_fecha);
	  if ($rs_data->RecordCount()>0)
	  {
	  	 while ((!$rs_data->EOF) && ($lb_valido))
		 {
			$estprog[0]=$rs_data->fields["codest1"];
			$estprog[1]=$rs_data->fields["codest2"];
			$estprog[2]=$rs_data->fields["codest3"];
			$estprog[3]=$rs_data->fields["codest4"];
			$estprog[4]=$rs_data->fields["codest5"];
			$estprog[5]=$rs_data->fields["estcla"];
			$as_cuenta=$rs_data->fields["spg_cuenta"];
			$as_tipo=$rs_data->fields["tipo_comp"];
			$as_fuente=$rs_data->fields["codfuefin"];
			$as_cod_pro=$rs_data->fields["cod_pro"];
			$as_ced_bene=$rs_data->fields["ced_bene"];
			$as_procede_doc=$rs_data->fields["procede_doc"];
			$as_documento=$rs_data->fields["documento"];
			$as_descripcion=$rs_data->fields["descripcion"];
			$ls_operacionpre=$rs_data->fields["operacion"];
			$as_mensaje= $this->io_int_spg->uf_operacion_codigo_mensaje($ls_operacionpre);
			$as_procede_doc=$rs_data->fields["procede_doc"];
			$as_tipo_comp='C';
			$adec_monto_anterior=0;
			$adec_monto_actual=$rs_data->fields["monto"];
			$as_sc_cuenta=$rs_data->fields["sc_cuenta"];
			$lb_valido=$this->io_int_spg->uf_int_spg_delete_movimiento($as_codemp,$as_procede,$as_comprobante,$adt_fecha,$as_tipo,$as_fuente,
																	   $as_cod_pro,$as_ced_bene,$estprog,$as_cuenta,$as_procede_doc,$as_documento,
																	   $as_descripcion,$as_mensaje,$as_tipo_comp,$adec_monto_anterior,
																	   $adec_monto_actual,$as_sc_cuenta,$as_codban,$as_ctaban);
		 	$rs_data->MoveNext();
		 }
	  }
	  if ($lb_valido)
	  {
	  	$rs_data=$this->uf_cargar_dt_contable_cmp($as_codemp,$as_procede,$as_comprobante,$adt_fecha);
		if ($rs_data->RecordCount()>0)
		{
			 while ((!$rs_data->EOF) && ($lb_valido))
			 {
			 	$as_cuenta=$rs_data->fields["sc_cuenta"];
			 	$as_procede_doc=$rs_data->fields["procede_doc"];
			 	$as_documento=$rs_data->fields["documento"];
			 	$as_operacion=$rs_data->fields["debhab"];
				$lb_valido=$this->io_int_scg->uf_scg_delete_movimiento($as_codemp,$as_procede,$as_comprobante,$adt_fecha,$as_cuenta,$as_procede_doc,
																	   $as_documento,$as_operacion,$as_codban,$as_ctaban);
		 		$rs_data->MoveNext();
			 }
	     }
	  }
	  return $lb_valido;
	}
 //---------------------------------------------------------------------------------------------------------------------------------

 //---------------------------------------------------------------------------------------------------------------------------------
	function uf_cargar_dt_comprobante($as_codemp,$as_procede,$as_comprobante,$adt_fecha)
	{
	  if(array_key_exists("txtcodban",$_POST))
	  {
		$ls_codban  = $_POST["txtcodban"];
		$ls_ctaban  = $_POST["txtctaban"];
	  }
	  else
 	   {
	    $ls_codban  = '---';
		$ls_ctaban  = '-------------------------';
 	   }
		$ld_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
		$ls_sql="SELECT DISTINCT DT.codestpro1 as codest1,".
				"                DT.codestpro2 as codest2,".
				"                DT.codestpro3 as codest3,".
				"                DT.codestpro4 as codest4,".
				"                DT.codestpro5 as codest5,".
				"                DT.estcla as estcla,".
				"                DT.spg_cuenta as spg_cuenta,".
				"                C.denominacion as denominacion, ".
				"          	     DT.procede_doc as procede_doc,  ".
				"                P.desproc as desproc,           ".
				"                DT.documento as documento,      ".
				"                DT.operacion as operacion,".
				"                DT.descripcion as descripcion,".
				"                DT.monto as monto,".
				"                DT.orden as orden, ".
				"                CMP.tipo_comp as tipo_comp, ".
				"                CMP.codfuefin as codfuefin, ".
				"                CMP.cod_pro as cod_pro, ".
				"                CMP.ced_bene as ced_bene, ".
				"                C.sc_cuenta as sc_cuenta, ".
				"                OP.denominacion as denominacion ".
				" FROM sigesp_cmp CMP,spg_dt_cmp DT,spg_cuentas C, sigesp_procedencias P,spg_operaciones OP ".
				"WHERE DT.codemp='".$as_codemp."'  ".
				"  AND DT.procede='".$as_procede."'".
				"  AND DT.comprobante='".$as_comprobante."' ".
				"  AND DT.fecha='".$ld_fecha."' ".
				"  AND DT.codban='".$ls_codban."' ".
				"  AND DT.ctaban='".$ls_ctaban."' ".
				"  AND CMP.codemp=DT.codemp".
				"  AND CMP.procede=DT.procede".
				"  AND CMP.comprobante=DT.comprobante".
				"  AND CMP.fecha=DT.fecha            ".
				"  AND CMP.codban=DT.codban".
				"  AND CMP.ctaban=DT.ctaban".
				"  AND DT.procede=P.procede ".
				"  AND DT.codemp=C.codemp ".
				"  AND DT.spg_cuenta=C.spg_cuenta  ".
				"  AND OP.operacion = DT.operacion ".
				"  AND (DT.codestpro1=C.codestpro1 ".
				"  AND DT.codestpro2=C.codestpro2  ".
				"  AND DT.codestpro3=C.codestpro3  ".
				"  AND DT.codestpro4=C.codestpro4  ".
				"  AND DT.codestpro5=C.codestpro5 ".
				"  AND DT.estcla=C.estcla) ".
				"  ORDER BY DT.orden "; 			
		$rs_dt_cmp=$this->io_sql->select($ls_sql);
		if($rs_dt_cmp===false)
		{
			$this->io_msg->message($this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		return $rs_dt_cmp;
	}
 //---------------------------------------------------------------------------------------------------------------------------------

 //---------------------------------------------------------------------------------------------------------------------------------
	function uf_cargar_dt_contable_cmp($as_codemp,$as_procede,$as_comprobante,$adt_fecha)
	{
	
		 if(array_key_exists("txtcodban",$_POST))
	  {
		$ls_codban  = $_POST["txtcodban"];
		$ls_ctaban  = $_POST["txtctaban"];
	  }
	  else
 	   {
	    $ls_codban  = '---';
		$ls_ctaban  = '-------------------------';
 	   }
		$ld_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
		$rs_dt_scg=$this->io_int_scg->uf_scg_cargar_detalle_comprobante( $as_codemp, $as_procede,$as_comprobante, $ld_fecha,&$lds_detalle_cmp,$ls_codban,$ls_ctaban);
		if($rs_dt_scg===false)
		{
			$this->io_msg->message($this->io_function->uf_convertirmsg($this->io_int_scg->io_sql->message));
		}
		return $rs_dt_scg;
	}
 //---------------------------------------------------------------------------------------------------------------------------------

 //---------------------------------------------------------------------------------------------------------------------------------
	function uf_valida_datos_cmp($as_comprobante,$ad_fecha,$as_procedencia,$as_desccomp,$as_cod_prov,$as_ced_bene,$as_tipo)
	{
		$ls_desproc ="";
		if(!$this->io_int_spg->uf_valida_procedencia($as_procedencia,&$ls_desproc ) )
		{
		   $this->io_msg->message("Procedencia invalida.",$ls_desproc);
		   return false	;
		} 
	
		if(trim($as_comprobante)=="")
		{
			$this->io_msg->message("Debe registrar el comprobante contable.");
			return false;
		}
	
		if(trim($as_comprobante)=="000000000000000")
		{
			$this->io_msg->message("Debe registrar el comprobante contable.");
			return false;
		}
		
		
		if((trim($as_cod_prov)=="----------")&&($as_tipo=="P"))
		{
			$this->io_msg->message("Debe registrar el codigo del proveedor.");
			return false;
		}
		if((trim($as_cod_prov)=="")&&($as_tipo=="P"))
		{
			$this->io_msg->message("Debe registrar el codigo del proveedor.");
			return false;
		}
		
		if((trim($as_cod_prov)!="----------" )&&($as_tipo=="B"))
		{
			$as_cod_prov = "----------";
		}
			
		if((trim($as_ced_bene)=="----------")&&($as_tipo=="B"))
		{
			$this->io_msg->message("Debe registrar la cédula del beneficiario.");
			return false;
		}
		if((trim($as_ced_bene)=="")&&($as_tipo=="B"))
		{
			$this->io_msg->message("Debe registrar la cédula del beneficiario.2");
			return false;	
		}
		
		if((trim($as_ced_bene)!="----------" )&&($as_tipo=="P"))
		{
			$as_ced_bene="----------";
		}
		if($as_tipo=="-")
		{
			$as_ced_bene="----------";
			$as_cod_prov="----------";
		}
	
	  return true;
	}
 //---------------------------------------------------------------------------------------------------------------------------------

 //---------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar_movimientos($arr_cmp,$ls_est1,$ls_est2,$ls_est3,$ls_est4,$ls_est5,$ls_cuenta,$ls_procede_doc, 
	                                $ls_descripcion,$ls_documento,$ls_operacionpre,$ldec_monto_ant,$ldec_monto_act,
									$ls_tipocomp,$as_codban,$as_ctaban,$as_estcla)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_movimientos
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que guarda los movimeintoscontables
		// Modificado por: Ing. Jennifer Rivero
		// Fecha Última Modificación: 05/12/2008										
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$lb_valido=false;
		$estpro[0]=$ls_est1;
		$estpro[1]=$ls_est2;
		$estpro[2]=$ls_est3;
		$estpro[3]=$ls_est4;
		$estpro[4]=$ls_est5;
		$estpro[5]=$as_estcla;
		$ls_mensaje = $this->io_int_spg->uf_operacion_codigo_mensaje($ls_operacionpre);
		if($ls_mensaje!="")
		{
			if(!$this->uf_spg_valida_datos_movimiento($ls_cuenta,$ls_descripcion,$ls_documento,&$ldec_monto))
			{ 
			   $this->io_msg->message($this->is_msg_error);
			   return false;
			}
			$this->io_int_spg->is_codemp=$arr_cmp["codemp"];
			$this->io_int_spg->is_comprobante=$arr_cmp["comprobante"];
			$this->io_int_spg->id_fecha=$arr_cmp["fecha"];
			$this->io_int_spg->is_procedencia=$arr_cmp["procedencia"];
			$this->io_int_spg->is_cod_prov=$arr_cmp["proveedor"];
			$this->io_int_spg->is_ced_bene=$arr_cmp["beneficiario"];
			$this->io_int_spg->is_tipo=$arr_cmp["tipo"];
			$this->io_int_spg->ib_AutoConta=true;
			$this->io_int_spg->as_codban=$as_codban;
			$this->io_int_spg->as_ctaban=$as_ctaban;
			//$lb_valido=$this->io_int_spg->uf_spg_comprobante_actualizar($ldec_monto_ant, $ldec_monto_act, $ls_tipocomp);
			if ($arr_cmp["tipo"]=="B")  
			{ 
			   $ls_fuente = $arr_cmp["beneficiario"]; 
			}	
			else
			{ 
				if ($arr_cmp["tipo"]=="P")
				 {  
					$ls_fuente = $arr_cmp["proveedor"]; 
				 }	
				 else 
				 {  
					$ls_fuente = "----------"; 
				 } 
			}
			$ls_status="";$ls_denominacion="";$ls_sc_cuenta="";
			if(!$this->io_int_spg->uf_spg_select_cuenta($arr_cmp["codemp"],$estpro,$ls_cuenta,&$ls_status,&$ls_denominacion,&$ls_sc_cuenta))
			{ 
			  return false;
			}
			if(!$this->io_int_spg->uf_spg_select_movimiento($estpro,$ls_cuenta,$ls_procede_doc,$ls_documento,$ls_operacionpre,
			                                                $ldec_monto_act,$lo_orden))//verifica si existeel movimiento  
			{
				
			    $lugar=1;
				$lb_valido = $this->io_int_spg->uf_int_spg_insert_movimiento($arr_cmp["codemp"],$arr_cmp["procedencia"],
																			 $arr_cmp["comprobante"],$arr_cmp["fecha"],
																			 $arr_cmp["tipo"],$ls_fuente,$arr_cmp["proveedor"],
																			 $arr_cmp["beneficiario"],$estpro,$ls_cuenta,
																			 $ls_procede_doc,
																			 $ls_documento,$ls_descripcion,$ls_mensaje,
																			 $ldec_monto_act,
																			 $ls_sc_cuenta,true,$as_codban,$as_ctaban,
																			 $lugar);
				if(!$lb_valido)
				{
					$this->io_msg->message("No se registraron los detalles presupuestario".$this->io_int_spg->is_msg_error);
					
				}
			}//fin del if
			else
			{
				$lb_valido=true;
			}
	   }
	   $ldec_monto = 0;
	   return $lb_valido;
	}//fin de la funciòn guardar movimiento
 //---------------------------------------------------------------------------------------------------------------------------------

 //---------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_cuenta_presupuestaria($arr_cmp,$ls_est1,$ls_est2,$ls_est3,$ls_est4,$ls_est5,$ls_cuenta)
	{										  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_cuenta_presupuestaria
		//		   Access: public 
		//       Argument: ls_est1 // Código de Estructura Programatica 1
		//       		   ls_est2 // Código de Estructura Programatica 2
		//       		   ls_est3 // Código de Estructura Programatica 3
		//       		   ls_est4 // Código de Estructura Programatica 4
		//       		   ls_est5 // Código de Estructura Programatica 5
		//       		   ls_cuenta // cuenta Presupuestaria
		//       		   arr_cmp // arreglos con los datos 
		//       		   as_operacion // Operación del movimiento
		//	  Description: Verifica si existe o no la cuenta y retorna informacion de la cuenta
		//	      Returns: Retorna true si la encontro y false en caso contrario
		//	   Creado Por: Ing. Yozelin Barragan
		//         Fecha : 27/07/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$estpro[0]=$ls_est1;
		$estpro[1]=$ls_est2;
		$estpro[2]=$ls_est3;
		$estpro[3]=$ls_est4;
		$estpro[4]=$ls_est5;
		if(!$this->io_int_spg->uf_spg_select_cuenta($arr_cmp["codemp"],$estpro,$ls_cuenta,&$ls_status,&$ls_denominacion,&$ls_sc_cuenta))
		{  
		  $lb_valido=false;
		}
		return  $lb_valido;
	}
 //---------------------------------------------------------------------------------------------------------------------------------

 //---------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_valida_datos_movimiento($as_cuenta,$as_descripcion,$as_documento,$adec_monto)
	{
	
		if (trim($as_cuenta)=="")
		{
			$this->is_msg_error = "Registre la Cuenta Gasto." ;
			return false;	
		}
		if(trim($as_descripcion)=="")
		{
			$this->is_msg_error = "Registre la Descripción del Movimiento." ;
			return false;
		}
	
		if(trim($as_documento) =="") 
		{
			$this->is_msg_error = "Registre el Nº de documento." 	;
			return false;	
		}
		 return true ;
	}
 //---------------------------------------------------------------------------------------------------------------------------------
    function uf_scg_select_movimiento($as_cuenta,$as_procede_doc,$as_documento,$as_debhab)
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
			$this->is_msg_error="CLASE->sigesp_spg_c_comprobante MÉTODO->uf_scg_select_movimiento ERROR->".
			                    $this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_mov))
			{
				$lb_existe=true;				
			}
			else
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_mov);	
		}
		return $lb_existe;
	} // end function uf_scg_select_movimiento
 //---------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar_movimientos_contable($arr_cmp,$as_cuenta,$as_procede_doc,$as_descripcion,$as_documento,
	                                         $as_operacioncon,$adec_monto,$as_codban,$as_ctaban,$as_fecha)
	{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_movimientos_contable
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que guarda los movimeintos contables
		// Modificado por: Ing. Jennifer Rivero
		// Fecha Última Modificación: 05/12/2008										/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
	    
		if(!$this->uf_scg_valida_datos_mov_contable($as_cuenta,$as_descripcion,$as_documento,$adec_monto))
		{ 
			$this->io_msg->message($this->is_msg_error);
		   return false;
		}
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->is_codemp=$ls_codemp;
		$this->id_fecha=$this->io_function->uf_convertirdatetobd($as_fecha);
		$this->is_procedencia=$as_procede_doc;
		$this->is_comprobante=$arr_cmp["comprobante"];
		$this->as_codban=$as_codban;
		$this->as_ctaban=$as_ctaban;
		if(!$this->uf_scg_select_movimiento($as_cuenta,$as_procede_doc,$as_documento,$as_operacioncon))
		{
			
			$lb_valido = $this->io_int_scg->uf_scg_procesar_movimiento_cmp($arr_cmp["codemp"],$arr_cmp["procedencia"],
																		   $arr_cmp["comprobante"],$arr_cmp["fecha"],
																		   $arr_cmp["proveedor"],$arr_cmp["beneficiario"],
																		   $arr_cmp["tipo"],$arr_cmp["tipo_comp"],
																		   $as_cuenta,$as_procede_doc,$as_documento,
																		   $as_operacioncon,$as_descripcion,$adec_monto,
																		   $as_codban,$as_ctaban);
			if(!$lb_valido)
			{
				$this->io_msg->message("Error al registrar movimiento contable");
			}	
		}
		else
		{
			$lb_valido=true;
		}		
		$ldec_monto = 0;
		return $lb_valido;
	}// fin de la funcion uf_guardar_movimientos_contable
 //---------------------------------------------------------------------------------------------------------------------------------

 //---------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_valida_datos_mov_contable($as_cuenta,$as_descripcion,$as_documento,$adec_monto)
	{
		if (trim($as_cuenta)=="")
		{
			$this->is_msg_error = "Registre la Cuenta Gasto." ;
			return false;	
		}
		
		if(trim($as_descripcion)=="")
		{
			$this->is_msg_error = "Registre la Descripción del Movimiento." ;
			return false;
		}
		
		if(trim($as_documento) =="") 
		{
			$this->is_msg_error = "Registre el Nº de documento." 	;
			return false;	
		}
		
		if($adec_monto == 0)
		{
			$this->is_msg_error = "Registre el Monto." ;	
			return false;
		} 
	   return true ;
	}
 //---------------------------------------------------------------------------------------------------------------------------------

 //---------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_comprobante($as_codemp,$as_procedencia,$as_comprobante)
	{//////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	 Function:  uf_verificar_comprobante()
	//	   Access:  public
	//	Arguments:  $as_codemp-> empresa,$as_procedencia->procedencia,$as_comprobante->comprobante,$as_fecha
	//	  Returns:	booleano lb_existe
	//Description:  Método que verifica si existe o no el comprobante
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
       $lb_existe=false;
	   $ls_sql =   " SELECT comprobante ".
	               " FROM   sigesp_cmp ".
				   " WHERE codemp='".$as_codemp."' AND procede='".$as_procedencia."' AND comprobante='".$as_comprobante."' ";
	   $lr_result = $this->io_sql->select($ls_sql);
	   if($lr_result===false)
	   {
		  $this->is_msg_error="Error en delete Comprobante".$this->io_function->uf_convertirmsg($this->io_sql->message);
		  return false;
	   }
	   else  
	   { 
	      if($row=$this->io_sql->fetch_row($lr_result)) 
		  { 
		     $lb_existe=true;
		  }  
	  }
	  return $lb_existe;
	} // end function uf_select_comprobante
 //---------------------------------------------------------------------------------------------------------------------------------

 //---------------------------------------------------------------------------------------------------------------------------------
  function uf_spg_select_disponibilidad($as_spg_cuenta,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
                                        &$ad_disponible,$as_operacionpre,$ad_monto,$as_estcla)
  {	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	       Function:  uf_spg_select_disponibilidad
	 //      Arguments :  $as_spg_cuenta // codigo de la cuenta
	 //                   $as_codestpro1 ..as_codestpro5  // codigo de la estructura programatica 
	 //                   $ad_disponible //   monto de la disponibilidad presupuestaria(referencia)
	 //	    Description:  Método que busca la disponibilidad de una cuenta pasada por parametro. 
	 //     Creado por :  Ing. Yozelin Barragán                                 
	 // Fecha Creación :  09/01/2007     Fecha última Modificacion :         
	 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=false;
	 $ls_codemp=$_SESSION["la_empresa"]["codemp"];
	 $ls_mensaje=$this->io_int_spg->uf_operacion_mensaje_codigo($as_operacionpre);
	 //$ls_estprog=$as_codestpro1.$as_codestpro2.$as_codestpro3.$as_codestpro4.$as_codestpro5;
	 $ls_estprog[0]=$as_codestpro1;
	 $ls_estprog[1]=$as_codestpro2;
	 $ls_estprog[2]=$as_codestpro3;
	 $ls_estprog[3]=$as_codestpro4;
	 $ls_estprog[4]=$as_codestpro5;
	 $ls_estprog[5]=$as_estcla;
	 if(empty($ls_mensaje))
	 {
	 	$lb_valido=false;
	 }
	 if($lb_valido)
	 {
		$lb_valido = $this->uf_spg_saldo_actual($ls_codemp,$ls_estprog,$as_spg_cuenta,$ls_mensaje,0,$ad_monto);
	 }
	 return  $lb_valido;
   }//fin  uf_spg_select_disponibilidad
 //---------------------------------------------------------------------------------------------------------------------------------

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
	//	   Creado Por: Ing. Yozelin Barragan
	// Fecha Creacion: 20/08/2007
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido = true;
	$ab_ignorarerror = false;
	$ls_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha); 
	$ls_nextcuenta=$as_cuenta;
	$li_nivel=$this->io_int_spg->uf_spg_obtener_nivel($ls_nextcuenta);
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
		if ($this->io_int_spg->uf_spg_saldo_select($as_codemp, $estprog, $ls_nextcuenta, &$ls_status, &$ld_asignado, &$ld_aumento, &$ld_disminucion, &$ld_precomprometido, &$ld_comprometido, &$ld_causado, &$ld_pagado))
		{				    
			if (!($this->io_int_spg->uf_spg_saldos_ajusta($estprog, $ls_nextcuenta, $as_mensaje, $ls_status, $adec_monto_anterior, $adec_monto_actual, &$ld_asignado, &$ld_aumento, &$ld_disminucion, &$ld_precomprometido, &$ld_comprometido, &$ld_causado, &$ld_pagado)))
			{
			   $lb_valido=true;
			}
		}
		if($this->io_int_spg->uf_spg_obtener_nivel($ls_nextcuenta)==1)
		{
			break;
		}
		$ls_nextcuenta=$this->io_int_spg->uf_spg_next_cuenta_nivel($ls_nextcuenta);
		$li_nivel=$this->io_int_spg->uf_spg_obtener_nivel($ls_nextcuenta);
	}
	return $lb_valido;
} // end function uf_spg_saldo_actual
//-----------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
function uf_update_bsf_sigespcmp($ad_monto,$as_codemp,$as_procede,$as_comprobante,$ad_fecha,$as_codban,$as_ctaban,$aa_seguridad)
{
     ////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_update_bsf_sigespcmp()                                   
	//	     Arguments:    
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Funcion que se usa para actualizar los monto a bolivar fuerte
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  24/09/2007                 Fecha última Modificacion :        
	/////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	
	/*$this->io_rcbsf->io_ds_datos->insertRow("campo","totalaux");
	$this->io_rcbsf->io_ds_datos->insertRow("monto", $ad_monto);

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codemp);
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

	$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sigesp_cmp",$this->li_candeccon,$this->li_tipconmon,
	                                                 $this->li_redconmon,$aa_seguridad);*/
	return $lb_valido;
}//uf_update_bsf_sigespcmp
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
function uf_update_bsf_spgdtcmp($ad_monto,$as_codemp,$as_procede,$as_comprobante,$ad_fecha,$as_codban,$as_ctaban,$as_codestpro,
                                $as_spg_cuenta,$as_procede_doc,$as_documento,$as_operacion,$aa_seguridad)
{
     ////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_update_bsf_spgdtcmp()                                   
	//	     Arguments:    
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Funcion que se usa para actualizar los monto a bolivar fuerte
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  24/09/2007                 Fecha última Modificacion :        
	/////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;

	$ls_codestpro1=$as_codestpro[0];
	$ls_codestpro2=$as_codestpro[1];
	$ls_codestpro3=$as_codestpro[2];
	$ls_codestpro4=$as_codestpro[3];
	$ls_codestpro5=$as_codestpro[4];
    $ls_estcla=$as_codestpro[5];

	/*$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
	$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_monto);

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codemp);
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
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro1");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro1);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro2");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro2);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro3");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro3);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro4");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro4);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro5");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro5);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estcla");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estcla);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spg_cuenta");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_spg_cuenta);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_procede_doc);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_documento);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","operacion");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_operacion);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("spg_dt_cmp",$this->li_candeccon,$this->li_tipconmon,
	                                                 $this->li_redconmon,$aa_seguridad);*/
	return $lb_valido;
}//uf_update_bsf_spgdtcmp
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
function uf_update_bsf_spgcuentas($ad_monto,$as_operacionpre,$as_codemp,$as_codestpro,$as_spg_cuenta,$adt_fecha,$aa_seguridad)
{
     ////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_update_bsf_spgcuentas()                                   
	//	     Arguments:    
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Funcion que se usa para actualizar los monto a bolivar fuerte
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  24/09/2007                 Fecha última Modificacion :        
	/////////////////////////////////////////////////////////////////////////////////////////
	//$lb_ok=false;
	$lb_valido=true;
	$ls_codestpro1=$as_codestpro[0];
	$ls_codestpro2=$as_codestpro[1];
	$ls_codestpro3=$as_codestpro[2];
	$ls_codestpro4=$as_codestpro[3];
	$ls_codestpro5=$as_codestpro[4];
    $ls_estcla=$as_codestpro[5];
	$ls_mensaje=$this->io_int_spg->uf_operacion_mensaje_codigo($as_operacionpre);
	$ls_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha); 
	$ls_nextcuenta=$as_spg_cuenta;
	$li_nivel=$this->io_int_spg->uf_spg_obtener_nivel($ls_nextcuenta);
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
		if ($this->io_int_spg->uf_spg_saldo_select($as_codemp, $as_codestpro, $ls_nextcuenta, &$ls_status, &$ld_asignado, &$ld_aumento, &$ld_disminucion, &$ld_precomprometido, &$ld_comprometido, &$ld_causado, &$ld_pagado))
		{				    
			if ($this->io_int_spg->uf_spg_saldos_ajusta($as_codestpro, $ls_nextcuenta, $ls_mensaje, $ls_status, 0, $ad_monto, &$ld_asignado, &$ld_aumento, &$ld_disminucion, &$ld_precomprometido, &$ld_comprometido, &$ld_causado, &$ld_pagado))
			{
				/*$this->io_rcbsf->io_ds_datos->insertRow("campo","asignadoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_asignado);
			
				$this->io_rcbsf->io_ds_datos->insertRow("campo","precomprometidoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_precomprometido);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","comprometidoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_comprometido);
			
				$this->io_rcbsf->io_ds_datos->insertRow("campo","causadoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_causado);
			
				$this->io_rcbsf->io_ds_datos->insertRow("campo","pagadoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_pagado);
			
				$this->io_rcbsf->io_ds_datos->insertRow("campo","aumentoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_aumento);
			
				$this->io_rcbsf->io_ds_datos->insertRow("campo","disminucionaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_disminucion);
			
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro1");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro1);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro2");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro2);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro3");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro3);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro4");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro4);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro5");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro5);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estcla");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estcla);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spg_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_nextcuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("spg_cuentas",$this->li_candeccon,$this->li_tipconmon,
																 $this->li_redconmon,$aa_seguridad);*/
			  // $lb_valido=true;
			}
		}
		if($this->io_int_spg->uf_spg_obtener_nivel($ls_nextcuenta)==1)
		{
			break;
		}
		$ls_nextcuenta=$this->io_int_spg->uf_spg_next_cuenta_nivel($ls_nextcuenta);
		$li_nivel=$this->io_int_spg->uf_spg_obtener_nivel($ls_nextcuenta);
	}
	return $lb_valido;
}//uf_update_bsf_spgcuentas
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
function uf_update_bsf_scgdtcmp($ad_monto,$as_codemp,$as_procede,$as_comprobante,$adt_fecha,$as_codban,$as_ctaban,
                                $as_cuenta,$as_procede_doc,$as_documento,$as_debhab,$aa_seguridad,$as_codestpro)
{
     ////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_update_bsf_scgdtcmp()                                   
	//	     Arguments:    
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Funcion que se usa para actualizar los monto a bolivar fuerte
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  24/09/2007                 Fecha última Modificacion :        
	/////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	if($as_codestpro!="")
	{
		$ls_status="";$ls_denominacion="";$ls_sc_cuenta="";
		$lb_valido=$this->uf_spg_select_cuenta($as_codemp,$as_codestpro,$as_cuenta,&$ls_status,&$ls_denominacion,&$ls_sc_cuenta);
	    $as_cuenta=$ls_sc_cuenta;
	}
	/*$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
	$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_monto);
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codemp);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_procede);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","comprobante");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_comprobante);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecha");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$adt_fecha);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","sc_cuenta");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_cuenta);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_procede_doc);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_documento);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","debhab");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_debhab);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scg_dt_cmp",$this->li_candeccon,$this->li_tipconmon,
													 $this->li_redconmon,$aa_seguridad);*/
	if($lb_valido)
	{
	   $lb_valido=$this->uf_convertir_scgsaldos($as_codemp,$aa_seguridad);//$this->uf_scg_procesar_saldos_contables($as_cuenta,$as_debhab,$aa_seguridad,$adt_fecha,$as_codemp,$ad_monto);
	}												 
	return $lb_valido;
}//uf_update_bsf_scgdtcmp
//---------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------------
function uf_spg_select_cuenta($as_codemp,$as_codestpro,$as_spg_cuenta,&$as_status,&$as_denominacion,&$as_scgcuenta)
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
	//	   Creado Por: Ing. Yozelin Barragan
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$ls_cuenta="";
	$ls_denominacion="";
	$ls_status="";
	$ls_scgcuenta="";
	$lb_existe=false;
	$ls_codestpro1=substr($as_codestpro,0,25);
	$ls_codestpro2=substr($as_codestpro,25,25);
	$ls_codestpro3=substr($as_codestpro,50,25);
	$ls_codestpro4=substr($as_codestpro,75,25);
	$ls_codestpro5=substr($as_codestpro,100,25);
	$ls_sql="SELECT spg_cuenta, status, denominacion, sc_cuenta ".
			"  FROM spg_cuentas ".
			" WHERE codemp='".$as_codemp."' ".
			"   AND codestpro1 = '".$ls_codestpro1."' ".
			"   AND codestpro2 = '".$ls_codestpro2."' ".
			"   AND codestpro3 = '".$ls_codestpro3."' ".
			"   AND codestpro4 = '".$ls_codestpro4."' ".
			"   AND codestpro5 = '".$ls_codestpro5."' ".
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

//-----------------------------------------------------------------------------------------------------------------------------
function uf_convertir_scgsaldos($as_codemp,$aa_seguridad)
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_convertir_scgsaldos
	//		   Access: private
	//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
	//	  Description: Funcion que selecciona los campos de la tabla scg_saldos e inserta el valor reconvertido
	//	   Creado Por: Ing. Yesenia Moreno
	// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql="SELECT codemp, sc_cuenta, fecsal, debe_mes, haber_mes ".
			"  FROM scg_saldos ".
			" WHERE codemp='".$as_codemp."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{ 
		$this->io_mensajes->message("CLASE->sigesp_rcm_c_scg MÉTODO->SELECT->uf_convertir_scgsaldos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
	}
	else
	{
		$la_seguridad="";
		while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
		{
			$ls_codemp= $row["codemp"]; 
			$ls_sc_cuenta= $row["sc_cuenta"];
			$ls_fecsal= $row["fecsal"];
			$li_debe_mes= $row["debe_mes"];
			$li_haber_mes= $row["haber_mes"];

			/*$this->io_rcbsf->io_ds_datos->insertRow("campo","debe_mesaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_debe_mes);
			
			$this->io_rcbsf->io_ds_datos->insertRow("campo","haber_mesaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_haber_mes);
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","sc_cuenta");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_sc_cuenta);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecsal");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_fecsal);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scg_saldos",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);*/
		}
	}		
	return $lb_valido;
}// end function uf_convertir_scgsaldos
//-----------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------------
function uf_scg_procesar_saldos_contables($as_cuenta,$as_debhab,$aa_seguridad,$adt_fecsal,$as_codemp,$ad_monto)
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
	//	   Creado Por: Ing. Yozelin Barragan
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$li_Disponible=0; 
	$lb_valido=true; 
	$lb_procesado=false;
	$ls_nextCuenta=$as_cuenta;
	$li_nivel=$this->io_int_scg->uf_scg_obtener_nivel($ls_nextCuenta);
	do 
	{
		if($as_debhab=="D")
		{
			/*$this->io_rcbsf->io_ds_datos->insertRow("campo","debe_mesaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_monto);
			$this->io_rcbsf->io_ds_datos->insertRow("campo","haber_mesaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",0);*/
		}	
		else
		{
			/*$this->io_rcbsf->io_ds_datos->insertRow("campo","debe_mesaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",0);
			$this->io_rcbsf->io_ds_datos->insertRow("campo","haber_mesaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_monto);*/
		}
		// Filtros de los Campos
		/*$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
		$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codemp);
		$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
		$this->io_rcbsf->io_ds_filtro->insertRow("filtro","sc_cuenta");
		$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_nextCuenta);
		$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
		$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecsal");
		$this->io_rcbsf->io_ds_filtro->insertRow("valor",$adt_fecsal);
		$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

		$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scg_saldos",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);*/
		
		if($this->io_int_scg->uf_scg_obtener_nivel($ls_nextCuenta)==0)
		{
			break;
		}
		$ls_nextCuenta=$this->io_int_scg->uf_scg_next_cuenta_nivel($ls_nextCuenta);
		if($ls_nextCuenta!="")
		{
			$li_nivel=($this->io_int_scg->uf_scg_obtener_nivel($ls_nextCuenta));
		}
	}while(($li_nivel>=1)&&($lb_valido)&&($ls_nextCuenta!=""));
	return $lb_valido;
} // end function uf_scg_procesar_saldos_contables
//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_select_comprobantes_spg_int($ad_fechades,$ad_fechahas,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_comprobantes_spg_int
		//		   Access: public
		//	    Arguments:
		//	      Returns: 
		//	  Description: Funcion que se encarga de retornar los comprobantes aprobados para la transferencia intercompañia
		//    Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
		$lb_valido=false;
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_criterio="";
		if(($ad_fechades!="")&&($ad_fechahas != ""))
		{
			$ls_criterio=$ls_criterio." AND sigesp_cmp.fecha BETWEEN '".$this->io_function->uf_convertirdatetobd($ad_fechades)."' AND '".$this->io_function->uf_convertirdatetobd($ad_fechahas)."'";
		}
		
		$ls_sql = "SELECT distinct sigesp_cmp.comprobante AS comprobante, ".
                  "                sigesp_cmp.codemp AS codemp, ".
                  "                sigesp_cmp.procede AS procede, ".
                  "                sigesp_cmp.fecha AS fecha, ".
                  "                sigesp_cmp.ced_bene AS ced_bene, ".
                  "                sigesp_cmp.cod_pro AS cod_pro, ".
                  "                sigesp_cmp.descripcion AS descripcion, " .
                  "                sigesp_cmp.tipo_destino AS tipo_destino, ".
				  "                sigesp_cmp.codban AS codban, ".
                  "                sigesp_cmp.ctaban AS ctaban, ".
                  "                SUM(spg_dt_cmp.monto) as total ".
				  "     FROM  sigesp_cmp, spg_dt_cmp   ".
				  "          WHERE sigesp_cmp.codemp='".$ls_codemp."'  AND ".
				  "                sigesp_cmp.codemp=spg_dt_cmp.codemp AND ".
				  "                sigesp_cmp.comprobante=spg_dt_cmp.comprobante AND ".
				  "                sigesp_cmp.procede=spg_dt_cmp.procede AND        ".
				  "                sigesp_cmp.fecha=spg_dt_cmp.fecha AND ".
				  "                sigesp_cmp.tipo_comp = 1 AND ".
				  "                sigesp_cmp.esttrfcmp = 0 ".$ls_criterio.				  
				  "     GROUP BY sigesp_cmp.procede, ".
				  "	             sigesp_cmp.codemp, ".
				  "	             sigesp_cmp.comprobante, ".
				  "	             sigesp_cmp.fecha, ". 
				  "	             sigesp_cmp.ced_bene, ".
				  "	             sigesp_cmp.cod_pro, ".
				  "	             sigesp_cmp.descripcion, ".
				  "	             sigesp_cmp.tipo_destino, ".
				  "	             sigesp_cmp.codban, ".
				  "	             sigesp_cmp.ctaban ".
				  "    ORDER BY  sigesp_cmp.fecha";		  	  
		$rs_data = $this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Comprobantes MÉTODO->uf_select_comprobantes_spg_int ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		}
		else
		{
		   $li_numrows=$this->io_sql->num_rows($rs_data);
		   if($li_numrows > 0)
		   {
		    $lb_valido = true;
           }
		}		
		return $lb_valido;
	}// end function uf_select_comprobantes_spg_int
	//--------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
    function uf_buscar_documento($as_codemp,$as_comprobante,$as_fecha)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_select_cuenta
		//		   Access: public 
		//       Argument: is_codemp // Código de Empresa
		//       		   as_comprobante // numero de Comprobante
		//       		   as_fecha // fecha del comprobante 
		//	  Description: busca el maximo numero docuemnto de los detalles de un comprobante
		//	      Returns: el numero del maximo docuemnto de los detalles de un comprobante
		//	   Creado Por: Ing. Jennifer Rivero
		//   Fecha de creación: 14/11/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_docum=0;
		$as_fecha=$this->io_function->uf_convertirdatetobd($as_fecha); 
		$ls_sql="	select max(documento) as documento from spg_dt_cmp    ".
				"	 where codemp='".$as_codemp."'           ".
				"      and comprobante='".$as_comprobante."' ".
				"	   and fecha='".$as_fecha."'             ".  
				"	   and procede='SPGCMP'                  ".
				"	   and procede_doc='SPGCMP'              ".
				"	   and codban='---'                      ".
				"	   and ctaban='-------------------------'".
				"	   and operacion='CG'                    " ; 
					   
		$rs_data = $this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;	
			$this->is_msg_error="CLASE->sigesp_spg_c_comprobante MÉTODO->uf_buscar_documento ERROR->".
			                     $this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_docum=$row["documento"];
					 			
			}
			else
			{
				$ls_docum=0;
			}    
			$this->io_sql->free_result($rs_data);
		}
		
		return $ls_docum;
} // end function uf_spg_select_cuenta
//----------------------------------------------------------------------------------------------------------------------------------------

///////--------------------------------------------------------------------------------------------------------------------------------

}// fin de la clase
?>