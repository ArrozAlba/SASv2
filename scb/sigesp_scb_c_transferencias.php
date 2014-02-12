<?php
class sigesp_scb_c_transferencias
{
	var $io_sql;
	var $fun;
	var $msg;
	var $is_msg_error;	
	var $dat;
	var $io_sql_aux;
	var $la_security;
	function sigesp_scb_c_transferencias($aa_security)
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("sigesp_scb_c_movbanco.php");
		require_once("../shared/class_folder/class_fecha.php");
		$this->io_fecha=new class_fecha();		
		$this->io_class_movbco=new sigesp_scb_c_movbanco($aa_security);
		$sig_inc=new sigesp_include();
		$con=$sig_inc->uf_conectar();
		$this->io_sql=new class_sql($con);
		$this->io_sql_aux=new class_sql($con);
		$this->fun=new class_funciones();
		$this->msg=new class_mensajes();
		$this->dat=$_SESSION["la_empresa"];	
		$this->la_security=$aa_security;
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad=new sigesp_c_seguridad();
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->rs_data ="";			
	}

	function uf_procesar_transferencia($arr_data,$arr_datadestino,$aa_seguridad)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_procesar_tranferencia
	// Access:			public
	//	Returns:		Boolean Retorna si proceso correctamente
	//	Description:	Funcion que se encarga de guardar los detalles de la transferencia
	//////////////////////////////////////////////////////////////////////////////
	
		$ls_codemp=$this->dat["codemp"];
		$li_total=count($arr_data["numtra"]);

		for($li_i=1 ; $li_i<=$li_total ; $li_i++)//for datos de origen
		{
			$ls_numtrans=$arr_data["numtra"][$li_i];
			$ls_codban=$arr_data["Codban"][$li_i];
			$ls_cuenta_banco=$arr_data["Ctaban"][$li_i];
			$ls_numdoc=$arr_data["numdoc"][$li_i];
			$ls_codope=$arr_data["codope"][$li_i];
			$ld_fecha=$arr_data["fecmov"][$li_i];
			$ls_conmov=$arr_data["concepto"][$li_i];
			$ls_cedbene=$arr_data["ced_bene"][$li_i];
			$ls_codpro =$arr_data["cod_prov"][$li_i];
			$ls_debhab =$arr_data["debhab"][$li_i]; 
			$ls_cuenta_scg=$arr_data["scg_cuenta"][$li_i];
			$ls_nomproben=$arr_data["nomproben"][$li_i];
			$ls_estmov=$arr_data["estmov"][$li_i];	
			$ldec_monto=$arr_data["monto"][$li_i];
			$ldec_monobjret=$arr_data["monobjret"][$li_i];
			$ldec_monret=$arr_data["monret"][$li_i];
			$ls_chevau=$arr_data["chevau"][$li_i];
			$ls_estbpd=$arr_data["estbpd"][$li_i];
			$ls_procede=$arr_data["procede_doc"][$li_i];
			$ls_estmovint=$arr_data["estmovint"][$li_i];
			$ls_codded=$arr_data["codded"][$li_i];
			$ld_feccon="1900/01/01";
			if($li_i==1)	
			{
				$lb_existe=$this->uf_select_documento($ls_codemp,$ls_numtrans,$ls_codope);
				if($lb_existe)
				{
					$this->is_msg_error="Numero de Documento ".$ls_numtrans." ya existe, introduzca un nuevo numero";
					return false;
				}
				$lb_valido=$this->io_class_movbco->uf_guardar_automatico($ls_codban,$ls_cuenta_banco,$ls_numtrans,$ls_codope,$ld_fecha,$ls_conmov,'---',$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto,$ldec_monobjret,$ldec_monret,$ls_chevau,$ls_estmov,0,1,$ls_estbpd,$ls_procede,' ','N','-','--','','',0);
					if(!$lb_valido)
					{
						return false ;
					}
			}
			if ($lb_valido)
			   {
			     $lb_valido = true;
				 $ls_sql="INSERT INTO scb_movbco_scg(codemp,codban,ctaban,numdoc,codope,estmov,scg_cuenta,debhab,codded,documento,desmov,procede_doc,monto,monobjret)
							   VALUES('".$ls_codemp."','".$ls_codban."','".$ls_cuenta_banco."','".$ls_numtrans."','".$ls_codope."','".$ls_estmov."','".$ls_cuenta_scg."','".$ls_debhab."','".$ls_codded."','".$ls_numdoc."','".$ls_conmov."','SCBTRA',".$ldec_monto.",".$ldec_monobjret.")";		
				 $li_result=$this->io_sql->execute($ls_sql);
				 if (($li_result===false))//3
					{
					  $lb_valido=false;
					  $this->is_msg_error="Error en insert scb_movbco_scg,".$this->fun->uf_convertirmsg($this->io_sql->message);		
					}
			   }
		}
		if($lb_valido)
		{
			$li_total=count($arr_datadestino["numtra"]);
			
			for($li_i=1 ; $li_i<=$li_total ; $li_i++)//for datos destino
			{
				$ls_numtrans=$arr_datadestino["numtra"][$li_i];
				$ls_codban=$arr_datadestino["Codban"][$li_i];
				$ls_cuenta_banco=$arr_datadestino["Ctaban"][$li_i];
				$ls_numdoc=$arr_datadestino["numdoc"][$li_i];
				$ls_codope=$arr_datadestino["codope"][$li_i];
				$ld_fecha=$arr_datadestino["fecmov"][$li_i];
				$ls_conmov=$arr_datadestino["concepto"][$li_i];
				$ls_cedbene=$arr_datadestino["ced_bene"][$li_i];
				$ls_codpro =$arr_datadestino["cod_prov"][$li_i];
				$ls_nomproben=$arr_datadestino["nomproben"][$li_i];
				$ls_estmov=$arr_datadestino["estmov"][$li_i];	
				$ldec_monto=$arr_datadestino["monto"][$li_i];
				$ldec_monobjret=$arr_datadestino["monobjret"][$li_i];
				$ldec_monret=$arr_datadestino["monret"][$li_i];
				$ls_chevau=$arr_datadestino["chevau"][$li_i];
				$ls_estbpd=$arr_datadestino["estbpd"][$li_i];
				$ls_procede=$arr_datadestino["procede_doc"][$li_i];
				$ls_estmovint=$arr_datadestino["estmovint"][$li_i];
				$ls_codded=$arr_datadestino["codded"][$li_i];
				$ld_feccon="1900/01/01";
				$lb_existe=$this->uf_select_documento($ls_codemp,$ls_numtrans,$ls_codope);
				if($lb_existe)
				{
					$this->is_msg_error="Numero de Documento ".$ls_numtrans." ya existe, introduzca un nuevo numero";
					return false;
				}
				$lb_valido=$this->io_class_movbco->uf_guardar_automatico($ls_codban,$ls_cuenta_banco,$ls_numtrans,$ls_codope,$ld_fecha,$ls_conmov,'---',$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto,$ldec_monobjret,$ldec_monret,$ls_chevau,$ls_estmov,0,1,$ls_estbpd,$ls_procede,' ','N','-','--','','',0);
				if(!$lb_valido)
				{
					$this->is_msg_error = $this->io_class_movbco->is_msg_error;
					return false;
				}
			
				if(!$lb_valido)
				{
					break;
				}
			}
			
		}
		return $lb_valido;
	
	}//Fin de  uf_procesar_emision_chq
	
	function uf_select_ctaauxiliar($ls_cta,$ls_dencta)
	{
		$ls_sql="SELECT TRIM(sc_cuenta) as sc_cuenta, denominacion 
		           FROM scg_cuentas 
				  WHERE sc_cuenta like '1110101%'
				    AND status='C'";
		$rs_cuentas=$this->io_sql->select($ls_sql);
		if(($rs_cuentas===false))
		{
			$this->is_msg_error="Error en consulta.".$this->fun->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_cuentas))
			{
				$ls_cta=$row["sc_cuenta"];
				$ls_dencta=$row["denominacion"];
			}
			else
			{
				$ls_cta="";
				$ls_dencta="";
			}
		}		
	}	
	
	function uf_select_documento($ls_codemp,$ls_numdoc,$ls_codope)
	{
		$ls_sql="SELECT numdoc
				 FROM scb_movbco
				 WHERE codemp='".$ls_codemp."' AND numdoc='".$ls_numdoc."' AND codope='".$ls_codope."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if(($rs_data===false))
		{
			return false;	
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
			}
			else
			{
				$lb_existe=false;				
			}	
			$this->io_sql->free_result($rs_data);
		}	
		return $lb_existe;		
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_declaracionxml($as_mesdes,$as_meshas,$as_year,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_declaracionxml
		//         Access: public  
		//	    Arguments: as_quincena // Quincena del cual se van a generar los txt
		//	    		   as_mes      // Mes del cual se van a generar los txt
		//	    		   as_anio     // Año del cual se van a generar los txt
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que genera los txt de la declaración informativa
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 15/07/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $ls_rifemp=$_SESSION["la_empresa"]["rifemp"];
		$ls_mesdesaux=intval($as_mesdes);
		$ls_meshasaux=intval($as_meshas);
		for($li_i=$ls_mesdesaux;$li_i<=$ls_meshasaux;$li_i++)
		{
			$ls_periodo=str_pad($li_i,2,"0",0);  
			$ld_fechadesde=$as_year."-".$ls_periodo."-01";
			$ld_fechahasta=$as_year."-".$ls_periodo."-".substr($this->io_fecha->uf_last_day($ls_periodo,$as_year),0,2);
			$ls_ruta="declaracion";
			@mkdir($ls_ruta,0755);
			$ls_archivo="declaracion/Declaracion_Salarios_y_otras_R_".$ls_periodo."-".date("Y_m_d_H_i").".xml";
			$ls_archivo2="declaracion/ERROR_Declaracion_Salarios_y_otras_R_".$ls_periodo."-".date("Y_m_d_H_i").".txt";
			$lo_archivo=fopen("$ls_archivo","a+");	
			$lo_archivo2=fopen("$ls_archivo2","a+");	
			$rs_datac=$this->uf_declaracion_xml_cabecera($ld_fechadesde,$ld_fechahasta,$ls_periodo,$as_year);
			$ls_contenido='<?xml version="1.0" encoding="utf-8"?>'; 
			$ls_contenido.='<RelacionRetencionesISLR RifAgente="'.$ls_rifemp.'" Periodo="'.$as_year.$ls_periodo.'">'; 
			$ls_cadena="";			
			while(!$rs_datac->EOF)
			{
				$ls_rifpro=trim($rs_datac->fields["rifpro"]);
				$ls_rifben=trim($rs_datac->fields["rifben"]);
				if($ls_rifpro!="")
				{
					$ls_rif=$ls_rifpro;
				}
				else
				{
					$ls_rif=$ls_rifben;
				}
				$ls_numrecdoc=trim($rs_datac->fields["numrecdoc"]);
				$ls_numrecdoc=substr($ls_numrecdoc,-10);
				$ls_numref=trim($rs_datac->fields["numref"]);
				if($ls_numref=="")
				{
					$ls_numref="NA";
				}
				$li_baseimp=number_format($rs_datac->fields["baseimp"],2,'.','');
				$ls_codconret=trim($rs_datac->fields["codconret"]);
				$ls_codper=trim($rs_datac->fields["codper"]);
				$li_porded=number_format($rs_datac->fields["porded"],2,'.','');
				$ls_procedencia=trim($rs_datac->fields["procedencia"]);
				$correcto=true;
				if ($ls_procedencia=='CXP')
				{
					if ((trim($ls_rif)==""))
					{
						$ls_cadena=$ls_cadena."La factura ".$ls_numrecdoc." no se pudo agregar ya que el proveedor/beneficiario asociado no posee rif. \r\n";
						$correcto=false;
					}
					if ((trim($ls_codconret)==""))
					{
						$ls_cadena=$ls_cadena."La factura ".$ls_numrecdoc." no se pudo agregar ya que la deducción no posee Concepto de Retención asociado. \r\n";
						$correcto=false;
					}
				}
				if ($ls_procedencia=='SNO')
				{
					if ((trim($ls_rif)==""))
					{
						$ls_cadena=$ls_cadena."El personal  ".$ls_codper." no se pudo agregar ya que no posee rif. \r\n";
						$correcto=false;
					}
					if ((trim($ls_codconret)==""))
					{
						$ls_cadena=$ls_cadena."El personal  ".$ls_codper." no se pudo agregar ya que la deducción no posee Concepto de Retención asociado. \r\n";
						$correcto=false;
					}
					if (($li_porded==0))
					{
						$ls_cadena=$ls_cadena."El personal  ".$ls_codper." no se pudo agregar ya que el porcentaje de deducción es cero. \r\n";
						$correcto=false;
					}
				}
				if($correcto)
				{
					$ls_contenido.='<DetalleRetencion>';
					$ls_contenido.='<RifRetenido>'.$ls_rif.'</RifRetenido>';
					$ls_contenido.='<NumeroFactura>'.$ls_numrecdoc.'</NumeroFactura>';
					$ls_contenido.='<NumeroControl>'.$ls_numref.'</NumeroControl>';
					$ls_contenido.='<CodigoConcepto>'.$ls_codconret.'</CodigoConcepto>';
					$ls_contenido.='<MontoOperacion>'.$li_baseimp.'</MontoOperacion>';
					$ls_contenido.='<PorcentajeRetencion>'.$li_porded.'</PorcentajeRetencion>';
					$ls_contenido.='</DetalleRetencion>';
				}
				$rs_datac->MoveNext();
			}
			$ls_contenido.='</RelacionRetencionesISLR>';
			@fwrite($lo_archivo,$ls_contenido);
			@fwrite($lo_archivo2,$ls_cadena);
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Genero el xml de Declaración de sueldos y otras remuneraciones para el periodo ".
								 $as_mesdes." a ".$as_meshas." del año ".$as_year." en el Archivo ".$ls_archivo.
								 " Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////					
			}
		}
		return $lb_valido;
	}// end function uf_declaracioninformativa
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_declaracion_xml_cabecera($as_fecemidocdes,$as_fecemidochas,$as_periodo,$as_year)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_declaracion_xml_cabecera
		//         Access: public 
		//      Argumento: as_fecemidocdes // Parametro de busqueda Fecha Desde
		//				   as_fecemidochas // Parametro de busqueda Fecha Hasta
		//	      Returns: Retorna un Datastored
		//    Description: Funcion que obtiene los datos para la declaracion de salarios y otras remuneraciones
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 05/06/2009									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_criterio="";
		$rs_data="";
		if($as_fecemidocdes!="")
		{
			$ls_criterio=$ls_criterio." AND cxp_rd.fecemidoc>='".$as_fecemidocdes."'";
		}
		if($as_fecemidochas!="")
		{
			$ls_criterio=$ls_criterio." AND cxp_rd.fecemidoc<='".$as_fecemidochas."'";
		}
		$ls_sql="SELECT '' AS codper, rpc_proveedor.rifpro, rpc_beneficiario.rifben,cxp_rd.numrecdoc, cxp_rd.numref,".
				" 		(cxp_rd.montotdoc-cxp_rd.mondeddoc+cxp_rd.moncardoc) as baseimp,".
				"       sigesp_deducciones.codconret ,sigesp_deducciones.porded,sigesp_deducciones.codded, 'CXP' AS procedencia".
				"  FROM cxp_rd, cxp_rd_deducciones, sigesp_deducciones, rpc_proveedor, rpc_beneficiario ".
				" WHERE cxp_rd.codemp = '".$this->ls_codemp."' ".
				"   AND sigesp_deducciones.islr = 1 ".
				"   AND cxp_rd.estprodoc='C'".
				$ls_criterio.
				"   AND cxp_rd.codemp = cxp_rd_deducciones.codemp ".
				"   AND cxp_rd.numrecdoc = cxp_rd_deducciones.numrecdoc ".
				"   AND cxp_rd.codtipdoc = cxp_rd_deducciones.codtipdoc ".
				"   AND cxp_rd.ced_bene = cxp_rd_deducciones.ced_bene ".
				"   AND cxp_rd.cod_pro = cxp_rd_deducciones.cod_pro ".
				"   AND cxp_rd_deducciones.codemp = sigesp_deducciones.codemp ".
				"   AND cxp_rd_deducciones.codded = sigesp_deducciones.codded ".
				"   AND cxp_rd.codemp = rpc_proveedor.codemp ".
				"   AND cxp_rd.cod_pro = rpc_proveedor.cod_pro ".
				"   AND cxp_rd.codemp = rpc_beneficiario.codemp ".
				"   AND cxp_rd.ced_bene = rpc_beneficiario.ced_bene ".
				" UNION ".
				"SELECT sno_personal.codper, MAX(sno_personal.rifper) AS rifpro,'' AS rifben,'0' AS numrecdoc,'' AS numref, SUM(sno_hsalida.valsal), ".
				"	   MAX(sno_personalisr.codconret) AS codconret, MAX(sno_personalisr.porisr) AS porded, sno_personalisr.codisr AS codded, 'SNO' AS procedencia ".
				"  FROM sno_hsalida, sno_personalisr, sno_personal, sno_hperiodo,sno_hconcepto ".
				" WHERE sno_hsalida.codemp = '".$this->ls_codemp."' ".
				"   AND SUBSTR(sno_hperiodo.fecdesper,1,4) = '".$as_year."' ".
				"   AND SUBSTR(sno_hperiodo.fecdesper,6,2) = '".$as_periodo."' ".
				"   AND sno_personalisr.codisr = '".$as_periodo."'  ".
				"   AND sno_hconcepto.aplarccon = 1  ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp  ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur  ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi  ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom  ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc  ".
				"   AND sno_hsalida.codemp = sno_hperiodo.codemp  ".
				"   AND sno_hsalida.anocur = sno_hperiodo.anocur  ".
				"   AND sno_hsalida.codperi = sno_hperiodo.codperi  ".
				"   AND sno_hsalida.codnom = sno_hperiodo.codnom  ".
				"   AND sno_hsalida.codemp = sno_personalisr.codemp  ".
				"   AND sno_hsalida.codper = sno_personalisr.codper  ".
				"   AND sno_personal.codemp = sno_personalisr.codemp  ".
				"   AND sno_personal.codper = sno_personalisr.codper  ".
				" GROUP BY sno_personal.codper, sno_personalisr.codisr ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{		 
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_declaracion_xml_cabecera ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		return $rs_data;		
	}// end function uf_arc_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------
	
}
?>