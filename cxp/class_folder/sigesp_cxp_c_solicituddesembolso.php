<?php
class sigesp_cxp_c_solicituddesembolso
 {
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_cxp_c_solicituddesembolso($as_path)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_cxp_c_aprobacionsolicitudpago
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 14/07/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once($as_path."shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("sigesp_cxp_c_recepcion.php");
		$this->io_recepcion=new sigesp_cxp_c_recepcion($as_path);		
		require_once($as_path."shared/class_folder/class_funciones_xml.php");
		$this->io_xml=new class_funciones_xml();		
		require_once($as_path."shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once($as_path."shared/class_folder/sigesp_c_generar_consecutivo.php");
		$this->io_keygen= new sigesp_c_generar_consecutivo();
		require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
		require_once($as_path."shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once($as_path."shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_sep_c_aprobacion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sep_p_solicitud.php)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 14/07/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fecha);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_creditos($as_path,$as_codtipdoc,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_creditos
		//		   Access: public
		//		 Argument: as_path // directorio para leer los archivos
		//	  Description: Función que procesa los creditos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 04/07/2008								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$la_archivos=$this->io_xml->uf_load_archivos($as_path);
		$li_totalarchivos=count($la_archivos["filnam"]);
		if($la_archivos=="")
		{
			$li_totalarchivos=0;
		}
		for($li_i=1;$li_i<=$li_totalarchivos;$li_i++)
		{
			$ls_archivo=$la_archivos["filnam"][$li_i];
			$la_data=$this->io_xml->uf_cargar_desembolso($as_path.$ls_archivo);
			$li_total=count($la_data);
			for($i=1;$i<=$li_total;$i++)
			{
				$ls_cedbene=rtrim($la_data[$i]["ced_bene"]);
				$ls_comprobante=rtrim($la_data[$i]["prestamo"]);
				$li_monto=$la_data[$i]["monto"];
				$ls_procede=rtrim($la_data[$i]["procede"]);
				$ld_fecreg=rtrim($la_data[$i]["fecreg"]);
				$ls_codban="---";
				$ls_ctaban="-------------------------";
				$ls_tipodestino="B";
				$ls_codpro="----------";
				$li_montototal=0;
				$li_monto_ajuste=0;
				$li_monto_causado=0;
				$li_monto_anulado=0;
				$li_monto_recepcion=0;
				$li_monto_ordenpago=0;
				$li_monto_cargo=0;
				$li_monto_solicitud=0;
				$li_disponible=0;
				$lb_valido=$this->uf_comprobantes_positivos($ls_comprobante,$ls_procede,$ls_codban,$ls_ctaban,$ld_fecreg,$ls_tipodestino,$ls_codpro,$ls_cedbene,&$li_montototal);
				if($lb_valido)
				{
					$lb_valido=$this->io_recepcion->uf_load_monto_ajustes($ls_comprobante,$ls_procede,$ls_tipodestino,$ls_codpro,$ls_cedbene,&$li_monto_ajuste);
				}
				else
				{
					$this->io_mensajes->message("El comprobante ".$ls_comprobante." El comprobante no existe.");
					$this->io_xml->uf_update_xml_procesado($ls_archivo,substr($as_path,0,strlen($as_path)-1),"Solicitud_Desembolso",$lb_valido,"El comprobante no existe.");
					$lb_valido=false;
				}
				if($lb_valido)
				{
					$lb_valido=$this->io_recepcion->uf_load_monto_causados($ls_comprobante,$ls_procede,$ls_tipodestino,$ls_codpro,$ls_cedbene,&$li_monto_causado);
				}
				if($lb_valido)
				{
					$lb_valido=$this->io_recepcion->uf_load_comprobantes_anulados($ls_comprobante,$ls_tipodestino,$ls_codpro,$ls_cedbene,$ld_fecreg,&$ls_numcomanu);
				}
				if($lb_valido)
				{
					$lb_valido=$this->io_recepcion->uf_load_monto_anulados($ls_numcomanu,$ls_procede,$ls_tipodestino,$ls_codpro,$ls_cedbene,&$li_monto_anulado);
				}
				if($lb_valido)
				{
					$lb_valido=$this->io_recepcion->uf_load_monto_recepciones($ls_comprobante,$ls_procede,&$li_monto_recepcion);
				}
				if($lb_valido)
				{
					$lb_valido=$this->io_recepcion->uf_load_monto_ordenespago_directa($ls_comprobante,$ls_procede,&$li_monto_ordenpago);
				}
				if($lb_valido)
				{
					$li_disponible=($li_montototal+$li_monto_ajuste)-($li_monto_causado+$li_monto_anulado)-$li_monto_recepcion;
					if(round($li_monto,2)==round($li_disponible,2))
					{
							$lb_valido=$this->io_recepcion->uf_load_acumulado_solicitudes($ls_comprobante,$as_codtipdoc,$ls_codpro,
																						  $ls_cedbene,&$li_monto_solicitud);
							if($lb_valido)
							{
								if($li_monto_solicitud>0)
								{
									$this->io_mensajes->message("Ya este crédito tiene Solicitud de pago.");
									$this->io_xml->uf_update_xml_procesado($ls_archivo,substr($as_path,0,strlen($as_path)-1),"Solicitud_Desembolso",$lb_valido,"Ya este credito tiene Solicitud de pago.");
								}
								else
								{
									$lb_valido=$this->uf_generar_recepcion_documento($ls_comprobante,$ls_procede,$ld_fecreg,$as_codtipdoc,$ls_codpro,
																					 $ls_cedbene,$li_monto,$ls_codban,$ls_ctaban,$ls_tipodestino,
																					 $aa_seguridad,$as_path,$ls_archivo);
								}
							}
							else
							{
								$this->io_mensajes->message("Error al Verificar las solicitudes de pago.");
								$this->io_xml->uf_update_xml_procesado($ls_archivo,substr($as_path,0,strlen($as_path)-1),"Solicitud_Desembolso",$lb_valido,"Error al Verificar las solicitudes de pago.");
							}
					}
					else
					{
						$this->io_mensajes->message("El Monto del Disponible ".round($li_disponible,2)." difiere con el del crédito ".round($li_monto,2)." ");
						$this->io_xml->uf_update_xml_procesado($ls_archivo,substr($as_path,0,strlen($as_path)-1),"Solicitud_Desembolso",$lb_valido,"El Monto del Disponible ".round($li_disponible,2)." difiere con el del credito ".round($li_monto,2)." ");
					}
				}
				if($lb_valido===false)
				{
					$this->io_xml->uf_mover_xml($ls_archivo,substr($as_path,0,strlen($as_path)-1),"../scc/III/procesados");
				}
				
			}
		}
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_comprobantes_positivos($as_comprobante,$as_procede,$as_codban,$as_ctaban,$ad_fecha,$as_tipodestino,
									   $as_codpro,$as_cedben,&$ai_total)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_comprobantes_positivos
		//		   Access: private
		//	    Arguments: as_comprobante  // Numero de Comprobante
		//				   as_procede // Procede de comprobante
		//				   as_codban  // Codigo de Banco
		//				   as_ctaban  // Cuenta de Banco
		//				   ad_fecha   // Fecha del Comprobante
		//				   as_tipodestino   // Comprobante o Beneficiario
		//				   as_codpro    // Codigo de Proveedor
		//				   as_cedben   // Cedula de Beneficiario
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: 
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 04/07/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_operacion="CS";
		$ls_sql="SELECT DISTINCT sigesp_cmp.procede, sigesp_cmp.comprobante, sigesp_cmp.fecha, sigesp_cmp.descripcion, ".
				"				 sigesp_cmp.total ".
				"  FROM sigesp_cmp, spg_dt_cmp ".
				" WHERE sigesp_cmp.codemp='".$this->ls_codemp."' ".
				"	AND sigesp_cmp.comprobante='".$as_comprobante."'".
				"	AND sigesp_cmp.procede='".$as_procede."'".
				"   AND sigesp_cmp.codban='".$as_codban."' ".
				"   AND sigesp_cmp.ctaban='".$as_ctaban."' ".
				"   AND sigesp_cmp.fecha= '".$ad_fecha."' ".
				"   AND sigesp_cmp.tipo_destino= '".$as_tipodestino."' ".
				"   AND sigesp_cmp.cod_pro= '".$as_codpro."' ".
				"   AND sigesp_cmp.ced_bene= '".$as_cedben."' ".
				"   AND spg_dt_cmp.monto > 0 ".
				"   AND sigesp_cmp.codemp=spg_dt_cmp.codemp ".
				"	AND sigesp_cmp.procede=spg_dt_cmp.procede ".
				"   AND sigesp_cmp.comprobante=spg_dt_cmp.comprobante ".
				"   AND sigesp_cmp.fecha=spg_dt_cmp.fecha ".
				"   AND sigesp_cmp.codban=spg_dt_cmp.codban ".
				"   AND sigesp_cmp.ctaban=spg_dt_cmp.ctaban ".
				" ORDER BY sigesp_cmp.comprobante ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Generar_Creditos MÉTODO->uf_comprobantes_positivos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_total=$row["total"];
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_comprobantes_positivos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_generar_recepcion_documento($as_numrecdoc,$as_procede,$ad_fecreg,$as_codtipdoc,$as_codpro,$as_cedben,$ai_monto,
											$as_codban,$as_ctaban,$as_tipodestino,$aa_seguridad,$as_path,$as_archivo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_generar_recepcion_documento
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Numero de Recepcion de documento
		//				   as_procede // Procede de comprobante
		//				   ad_fecreg  // Fecha de Registro
		//				   as_codtipdoc  // Tipo de Docuemnto
		//				   as_codpro    // Codigo de Proveedor
		//				   as_cedben   // Cedula de Beneficiario
		//				   ai_monto   // Monto de Recepcion
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: 
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 14/07/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_existe="FALSE";
		$as_codtipdoc=substr($as_codtipdoc,0,5);
		$as_codprovben=$as_cedben;
		$ad_fecregdoc=$ad_fecreg;
		$ad_fecvendoc=$ad_fecreg;
		$ad_fecemidoc=$ad_fecreg;
		$as_codcla='--';
		$as_dencondoc='Recepción Generada por el Integrador de Créditos'.$as_numrecdoc;
		$ai_cargos=0;
		$ai_deducciones=0;
		$ai_totalgeneral=$ai_monto;
		$as_numref=$as_numrecdoc;
		$as_estimpmun=0;
		$as_estlibcom=0;
		$ai_totrowscg=0;
		$ai_totrowspg=0;
		$as_codfuefin='--';
		$ls_sccuentabene="";
		$lb_valido=$this->uf_load_cuenta_beneficiario($as_cedben,$as_path,$as_archivo,&$ls_sccuentabene);
		$as_codrecdoc=$this->io_keygen->uf_generar_numero_nuevo("CXP","cxp_rd","codrecdoc","CXPRCD",15,"","","");
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_recepcion($as_numrecdoc,$as_codtipdoc,$as_cedben,$as_codpro,$as_codcla,$as_dencondoc,$ad_fecemidoc,
												  $ad_fecregdoc,$ad_fecvendoc,$ai_totalgeneral,$ai_deducciones,$ai_cargos,$as_tipodestino,
												  $as_numref,$as_procede,$as_estlibcom,$as_estimpmun,$as_codfuefin,$as_codrecdoc,$ls_sccuentabene,
												  $as_path,$as_archivo,$aa_seguridad);
		}
		return $lb_valido;
	}// end function uf_generar_recepcion_documento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_recepcion($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_codcla,$as_dencondoc,$ad_fecemidoc,$ad_fecregdoc,
								 $ad_fecvendoc,$ai_totalgeneral,$ai_deducciones,$ai_cargos,$as_tipodestino,$as_numref,$as_procede,
								 $as_estlibcom,$as_estimpmun,$as_codfuefin,$as_codrecdoc,$as_sccuentabene,$as_path,$as_archivo,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_recepcion
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de recepción de documentos
		//				   as_codtipdoc  // Tipo de Documento
		//				   as_cedbene  // Cédula del Beneficiario
		//				   as_codpro  // Código de proveedor
		//				   as_codcla  // Código de Clasificación
		//				   as_dencondoc  // Concepto de la recpeción de documentos
		//				   ad_fecemidoc  // Fecha de Emisión del Documento
		//				   ad_fecregdoc  // Fecha de Recepcion de Documentos
		//				   ad_fecvendoc  // Fecha de Vencimiento del Documento
		//				   ai_totalgeneral  // Total General
		//				   ai_deducciones  // Total de Deducciones
		//				   ai_cargos  // Total de Cargos
		//				   as_tipodestino  // Tipo Destino
		//				   as_numref  // Número de Referencia
		//				   as_procede  // Procede de la recepción de documentos
		//				   as_estlibcom  // Estatus de Libro de Orden de compra
		//				   as_estimpmun  // Estatus de Impuesto Municipal
		//				   ai_totrowspg  // Total de Filas de Presupuesto
		//				   as_codfuefin  // Fuente de Financiamiento
		//				   as_codrecdoc  // Código único de Recepción de Documentos
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta la recepción de documentos y sus detalles
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 30/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=$this->io_keygen->uf_verificar_numero_generado("CXP","cxp_rd","codrecdoc","CXPRCD",15,"","","",&$as_codrecdoc);
		$lb_valido=true;
		$ls_sql="INSERT INTO cxp_rd (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, codcla, dencondoc, fecemidoc, fecregdoc, ".
				"fecvendoc, montotdoc, mondeddoc, moncardoc, tipproben, numref, estprodoc, procede, estlibcom, estaprord, ".
				"fecaprord, usuaprord, estimpmun, codfuefin, codrecdoc)  VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."', ".
				"'".$as_cedbene."','".$as_codpro."','".$as_codcla."','".$as_dencondoc."','".$ad_fecemidoc."','".$ad_fecregdoc."', ".
				"'".$ad_fecvendoc."',".$ai_totalgeneral.",".$ai_deducciones.",".$ai_cargos.",'".$as_tipodestino."','".$as_numref."', ".
				"'R','".$as_procede."',".$as_estlibcom.",0,'1900-01-01','',".$as_estimpmun.",'".$as_codfuefin."','".$as_codrecdoc."')";	
		$this->io_sql->begin_transaction();				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Generar_Creditos MÉTODO->uf_insert_recepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_xml->uf_update_xml_procesado($as_archivo,substr($as_path,0,strlen($as_path)-1),"Solicitud_Desembolso",$lb_valido,$this->io_sql->message);
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Recepción de Documentos ".$as_numrecdoc." Tipo ".$as_codtipdoc." Beneficiario ".$as_cedbene.
							 "Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_cuentasspg($as_numrecdoc,$as_procede,'---','-------------------------',$ad_fecregdoc,$as_tipodestino,
													   $as_codpro,$as_cedbene,$as_codtipdoc,$as_sccuentabene,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_historico($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$ad_fecregdoc,"R",$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Recepción de Documentos ".$as_numrecdoc." fue registrada.");
				$this->io_xml->uf_update_xml_procesado($as_archivo,substr($as_path,0,strlen($as_path)-1),"Solicitud_Desembolso",$lb_valido,"Registro Realizado con Exito.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("Ocurrio un Error al Registrar la Recepción de Documentos ".$as_numrecdoc."."); 
				$this->io_xml->uf_update_xml_procesado($as_archivo,substr($as_path,0,strlen($as_path)-1),"Solicitud_Desembolso",$lb_valido,"No se Registro la Recepcion de documentos");
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_recepcion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cuentasspg($as_comprobante,$as_procede,$as_codban,$as_ctaban,$ad_fecha,$as_tipodestino,$as_codpro,$as_cedben,
								  $as_codtipdoc,$as_sccuentabene,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cuentasspg
		//		   Access: private
		//	    Arguments: as_comprobante  // Numero de Comprobante
		//				   as_procede // Procede de comprobante
		//				   as_codban  // Codigo de Banco
		//				   as_ctaban  // Cuenta de Banco
		//				   ad_fecha   // Fecha del Comprobante
		//				   as_tipodestino   // Comprobante o Beneficiario
		//				   as_codpro    // Codigo de Proveedor
		//				   as_cedben   // Cedula de Beneficiario
		//	      Returns: función que busca las cuentas presupuestarias del compromiso y genera las cuentas contables.
		//	  Description: 
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 14/07/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_operacion="CS";
		$ls_sql="SELECT spg_dt_cmp.*, spg_cuentas.sc_cuenta ".
				"  FROM sigesp_cmp, spg_dt_cmp, spg_cuentas ".
				" WHERE sigesp_cmp.codemp='".$this->ls_codemp."' ".
				"	AND sigesp_cmp.comprobante='".$as_comprobante."'".
				"	AND sigesp_cmp.procede='".$as_procede."'".
				"   AND sigesp_cmp.codban='".$as_codban."' ".
				"   AND sigesp_cmp.ctaban='".$as_ctaban."' ".
				"   AND sigesp_cmp.fecha= '".$ad_fecha."' ".
				"   AND sigesp_cmp.tipo_destino= '".$as_tipodestino."' ".
				"   AND sigesp_cmp.cod_pro= '".$as_codpro."' ".
				"   AND sigesp_cmp.ced_bene= '".$as_cedben."' ".
				"   AND spg_dt_cmp.monto > 0 ".
				"   AND sigesp_cmp.codemp=spg_dt_cmp.codemp ".
				"	AND sigesp_cmp.procede=spg_dt_cmp.procede ".
				"   AND sigesp_cmp.comprobante=spg_dt_cmp.comprobante ".
				"   AND sigesp_cmp.fecha=spg_dt_cmp.fecha ".
				"   AND sigesp_cmp.codban=spg_dt_cmp.codban ".
				"   AND sigesp_cmp.ctaban=spg_dt_cmp.ctaban ".
				"   AND spg_dt_cmp.codemp=spg_cuentas.codemp ".
				"	AND spg_dt_cmp.estcla=spg_cuentas.estcla ".
				"   AND spg_dt_cmp.codestpro1=spg_cuentas.codestpro1 ".
				"   AND spg_dt_cmp.codestpro2=spg_cuentas.codestpro2 ".
				"   AND spg_dt_cmp.codestpro3=spg_cuentas.codestpro3 ".
				"   AND spg_dt_cmp.codestpro4=spg_cuentas.codestpro4 ".
				"   AND spg_dt_cmp.codestpro5=spg_cuentas.codestpro5 ".
				"   AND spg_dt_cmp.spg_cuenta=spg_cuentas.spg_cuenta ".
				" ORDER BY sigesp_cmp.comprobante ASC ";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Generar_Creditos MÉTODO->uf_insert_cuentasspg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$li_montobeneficiario=0;
			while(!$rs_data->EOF)
			{
				$ls_nrocomp=$rs_data->fields["comprobante"];
				$ls_programatica=$rs_data->fields["codestpro1"].$rs_data->fields["codestpro2"].$rs_data->fields["codestpro3"].$rs_data->fields["codestpro4"].$rs_data->fields["codestpro5"];
				$ls_estcla=$rs_data->fields["estcla"];
				$ls_cuenta=$rs_data->fields["spg_cuenta"];
				$ls_procede=$rs_data->fields["procede_doc"];
				$ls_sccuenta=$rs_data->fields["sc_cuenta"];
				$li_moncue=$rs_data->fields["monto"];
				$li_montobeneficiario=$li_montobeneficiario+$li_moncue;
				$li_monto_compromiso=0;
				$li_monto_ajuste=0;
				$li_monto_causado=0;
				$li_monto_anulado=0;
				$li_monto_recepcion=0;
				$li_monto_ordenpago=0;
				$li_monto_cargo=0;
				$li_monto_solicitud=0;
				$li_disponible=0;
				$ls_numcomanu="";
				$lb_valido=$this->io_recepcion->uf_load_monto_comprobantes_cuenta($ls_nrocomp,$ls_procede,$as_tipodestino,$as_codpro,$as_cedben,
																	$ad_fecha,$ls_programatica,$ls_estcla,$ls_cuenta,&$li_monto_compromiso);
				if($lb_valido)
				{											   			
					$lb_valido=$this->io_recepcion->uf_load_monto_ajustes_cuenta($ls_nrocomp,$ls_procede,$as_tipodestino,$as_codpro,$as_cedben,
																   $ls_programatica,$ls_estcla,$ls_cuenta,&$li_monto_ajuste);
				}
				if($lb_valido)
				{
					$lb_valido=$this->io_recepcion->uf_load_monto_causados_cuenta($ls_nrocomp,$ls_procede,$as_tipodestino,$as_codpro,$as_cedben,
																	$ls_programatica,$ls_estcla,$ls_cuenta,&$li_monto_causado);
				}
				if($lb_valido)
				{
					$lb_valido=$this->io_recepcion->uf_load_comprobantes_anulados($ls_nrocomp,$as_tipodestino,$as_codpro,$as_cedben,$ad_fecha,
																	&$ls_numcomanu);
				}
				if(($lb_valido) &&($li_monto_causado>0))
				{
					$lb_valido=$this->io_recepcion->uf_load_monto_anulados_cuenta($ls_nrocomp,$ls_procede,$as_tipodestino,$as_codpro,$as_cedben,
																	$ls_programatica,$ls_estcla,$ls_cuenta,&$li_monto_anulado);
				}
				if($lb_valido)
				{
					$lb_valido=$this->io_recepcion->uf_load_monto_recepciones_cuenta($ls_nrocomp,$ls_procede,$ls_programatica,$ls_estcla,$ls_cuenta,
																	   &$li_monto_recepcion);
				}
				if($lb_valido)
				{
					$lb_valido=$this->io_recepcion->uf_load_monto_ordenespago_directa_cuenta($ls_nrocomp,$ls_procede,$ls_programatica,$ls_estcla,$ls_cuenta,
																			   &$li_monto_ordenpago);
				}
				if($lb_valido)
				{
					$li_comprometido=$li_monto_compromiso+(($li_monto_ajuste)-$li_monto_causado+$li_monto_anulado-$li_monto_recepcion);
					if($li_monto_compromiso>0)
					{
						$li_disponible=$li_comprometido-$li_moncue;
						$li_disponible=number_format($li_disponible,2,'.','');
					}
					else
					{
						$li_disponible=0;
					}
					if($li_disponible>=0)
					{
						$ls_sql="INSERT INTO cxp_rd_spg (codemp,numrecdoc,codtipdoc,cod_pro,ced_bene,procede_doc,numdoccom,codestpro,".
								"spg_cuenta,monto,codfuefin,estcla) VALUES ('".$this->ls_codemp."','".$as_comprobante."','".$as_codtipdoc."',".
								"'".$as_codpro."','".$as_cedben."','".$ls_procede."','".$ls_nrocomp."','".$ls_programatica."', ".
								"'".$ls_cuenta."',".$li_moncue.",'--','".$ls_estcla."')";
						$li_row=$this->io_sql->execute($ls_sql);
						if($li_row===false)
						{
							$lb_valido=false;
							$this->io_mensajes->message("CLASE->Generar_Creditos MÉTODO->uf_insert_cuentasspg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
						}
						else
						{
							/////////////////////////////////         SEGURIDAD               /////////////////////////////		
							$ls_evento="INSERT";
							$ls_descripcion="Insertó la cuenta ".$ls_cuenta." Estructura ".$ls_programatica." a la Recepción ".$as_comprobante.
											" Tipo ".$as_codtipdoc." Beneficiario ".$as_cedben."Proveedor ".$as_codpro.
											" Asociado a la empresa ".$this->ls_codemp;
							$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
															$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
															$aa_seguridad["ventanas"],$ls_descripcion);
							/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						}
						if($lb_valido)
						{
							$lb_valido=$this->uf_insert_cuentasscg($as_comprobante,$ls_procede,$as_codpro,$as_cedben,$as_codtipdoc,$ls_sccuenta,
																   $li_moncue,"D",$aa_seguridad);
						}
					}
					else
					{
						$lb_valido=false;
						$this->io_mensajes->message("ERROR-> Se esta causando Mas de lo comprometido en la cuenta ".$ls_cuenta); 
					}
				}
				$rs_data->MoveNext();
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_insert_cuentasscg($as_comprobante,$ls_procede,$as_codpro,$as_cedben,$as_codtipdoc,$as_sccuentabene,
													   $li_montobeneficiario,"H",$aa_seguridad);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_insert_cuentasspg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cuentasscg($as_comprobante,$as_procede,$as_codpro,$as_cedben,$as_codtipdoc,$as_sccuenta,$ai_monto,$as_operacion,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cuentasspg
		//		   Access: private
		//	    Arguments: as_comprobante  // Numero de Comprobante
		//				   as_procede // Procede de comprobante
		//				   as_codban  // Codigo de Banco
		//				   as_ctaban  // Cuenta de Banco
		//				   ad_fecha   // Fecha del Comprobante
		//				   as_tipodestino   // Comprobante o Beneficiario
		//				   as_codpro    // Codigo de Proveedor
		//				   as_cedben   // Cedula de Beneficiario
		//	      Returns: función que busca las cuentas presupuestarias del compromiso y genera las cuentas contables.
		//	  Description: 
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 14/07/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO cxp_rd_scg (codemp,numrecdoc,codtipdoc,cod_pro,ced_bene,procede_doc,numdoccom,debhab,sc_cuenta, ".
				"monto,estgenasi,estasicon) VALUES ('".$this->ls_codemp."','".$as_comprobante."', '".$as_codtipdoc."','".$as_codpro."', ".
				"'".$as_cedben."','".$as_procede."','".$as_comprobante."','".$as_operacion."',".
				"'".$as_sccuenta."',".$ai_monto.",0,'A')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Generar_Creditos MÉTODO->uf_insert_cuentasspg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
				
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion="Insertó la cuenta ".$ai_monto." a la Recepción ".$as_comprobante." Tipo ".$as_codtipdoc.
								" Beneficiario ".$as_cedben."Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_insert_cuentasspg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_historico($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$ad_fecregdoc,$as_estatus,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_historico
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de Recepción ded Documentos
		//				   as_codtipdoc  // Código del Tipo de Documento
		//				   as_cedbene  // Cédula del Beneficiario
		//				   as_codpro  // Código del Proveedor
		//				   ad_fecregdoc  // Fecha de Registro de la Recepción
		//				   as_estatus  // Estatus de la recepción
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta los movimientos históricos de una recepción ded documentos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 01/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO cxp_historico_rd (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, fecha, estprodoc)".
				" VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_cedbene."','".$as_codpro."',".
				"'".$ad_fecregdoc."','".$as_estatus."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_insert_historico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion="Insertó el los históricos a la Recepción ".$as_numrecdoc." Tipo ".$as_codtipdoc.
							" Beneficiario ".$as_cedbene."Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_insert_historico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cuenta_beneficiario($as_cedbene,$as_path,$as_archivo,&$as_sccuenta)
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cuenta_beneficiario
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene la cuenta contable de un beneficiario
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 15/04/2008 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sc_cuenta, sc_cuentarecdoc ".
				"  FROM rpc_beneficiario ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND ced_bene <> '----------' ".
				"   AND ced_bene = '".$as_cedbene."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR-> AL obtener la cuenta del beneficiario."); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_conrecdoc=$_SESSION["la_empresa"]["conrecdoc"];
				switch($ls_conrecdoc)
				{
					case "0":
						$as_sccuenta=trim($row["sc_cuenta"]);
						break;
					
					case "1":
						$as_sccuenta=trim($row["sc_cuentarecdoc"]);
						break;
				}
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("El Beneficario No existe ");
				$this->io_xml->uf_update_xml_procesado($as_archivo,substr($as_path,0,strlen($as_path)-1),"Solicitud_Desembolso",$lb_valido,"El Beneficario no existe. ");
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_cuenta_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

}
?>