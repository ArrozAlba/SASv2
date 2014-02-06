<?php
class sigesp_mis_c_contabiliza
{
    var $is_msg_error;
	var $dts_empresa; 
	var $dts_solicitud;
	var $obj="";
	var $io_sql;
	var $io_include;
	var $io_connect;
	var $io_function;	
	var $io_mensajes;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_mis_c_contabiliza()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_mis_c_contabiliza
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/class_sql.php");  
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_fecha.php");
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/class_funciones_xml.php");
		$this->io_function=new class_funciones() ;
		$this->io_include=new sigesp_include();
		$this->io_connect=$this->io_include->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);		
		$this->dts_empresa=$_SESSION["la_empresa"];
		$this->io_mensajes=new class_mensajes();		
		$this->io_fecha=new class_fecha();		
		$this->io_xml=new class_funciones_xml();		
		$this->dts_solicitud=new class_datastore();
	}// end function sigesp_mis_c_contabiliza
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudes_contabilizar($as_numsol,$as_fecreg,$as_fecapr,$as_codigo,$as_tipo,
												&$ao_object,&$ai_totrows,$as_estatus,$as_operacion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_solicitudes_contabilizar
		//		   Access: public (sigesp_mis_p_contibiliza_sep.php)
		//	    Arguments: as_numsol  // Número de Solicitud
		//	   			   as_fecreg  // Fecha de Registro
		//	   			   as_fecapr  // Fecha de aprobación
		//	   			   as_codpro  // código del Proveedor
		//				   ao_object  // Arreglo de objetos
		//				   ai_totrows  // Total de Filas
		//				   ai_estatus  // estatus de los movimientos a consultar
		//	      Returns: lb_valido True si se ejecuto la busqueda correctamente
		//	  Description: Método que obtiene todas aquellas solicitud en estatus emitidas
		//                  para su contabilizacion
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 07/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
        $lb_valido=true;
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_criterio="";
		if(!empty($as_numsol))
		{
			$ls_criterio=$ls_criterio." AND numsol like '%".$as_numsol."%'";
		}
		if(!empty($as_fecreg))
		{
			$as_fecreg=$this->io_function->uf_convertirdatetobd($as_fecreg);
			$ls_criterio=$ls_criterio." AND fecregsol = '".$as_fecreg."'";
		}
		if(!empty($as_fecapr))
		{
			$as_fecapr=$this->io_function->uf_convertirdatetobd($as_fecapr);
			$ls_criterio=$ls_criterio." AND fecaprsep = '".$as_fecapr."'";
		}
		if(!empty($as_tipo))
		{
			$ls_criterio=$ls_criterio." AND tipo_destino = '".$as_tipo."' ";
			if(!empty($as_codigo))
			{
				switch($as_tipo)
				{
					case "P": // es un proveedor
						$ls_criterio=$ls_criterio." AND cod_pro = '".$as_codigo."' ";
						break;
	
					case "B": // es un beneficiario
						$ls_criterio=$ls_criterio." AND ced_bene = '".$as_codigo."' ";
						break;
				}
			}
		}
		switch($as_operacion)
		{
			case "REVERSAR":
				$ls_criterio=$ls_criterio." AND NOT numsol IN (SELECT numsol FROM soc_enlace_sep ".
                              			  "  						        WHERE sep_solicitud.codemp =  soc_enlace_sep.codemp ".
                              			  "  						          AND sep_solicitud.numsol =  soc_enlace_sep.numsol ".
                              			  "  						          AND soc_enlace_sep.estordcom = 3 ".
										  "								    GROUP BY soc_enlace_sep.numsol) ";
				break;
		}
		$ls_sql="SELECT sep_solicitud.numsol, sep_solicitud.fecregsol, sep_solicitud.consol, sep_solicitud.fechaconta, ".
				"		sep_solicitud.fechaanula ".
                "  FROM sep_solicitud, sep_tiposolicitud ".
				" WHERE sep_solicitud.codemp = '".$ls_codemp."' ".
				"   AND sep_solicitud.estsol = '".$as_estatus."' ".
				"   AND sep_solicitud.estapro = 1 ".
				"   AND sep_tiposolicitud.estope <> 'S' ".
				"   AND NOT numsol IN (SELECT numdoccom ".
				"						 FROM cxp_rd_spg, cxp_rd ".
				"  						WHERE sep_solicitud.codemp =  cxp_rd_spg.codemp ".
				"  						  AND sep_solicitud.numsol =  cxp_rd_spg.numdoccom ".
				"  						  AND cxp_rd_spg.procede_doc = 'SEPSPC' ".
				"  						  AND cxp_rd.estprodoc <> 'A' ".
				"						  AND cxp_rd.codemp = cxp_rd_spg.codemp ".
				"						  AND cxp_rd.numrecdoc = cxp_rd_spg.numrecdoc ".
				"						  AND cxp_rd.codtipdoc = cxp_rd_spg.codtipdoc ".
				"						  AND cxp_rd.ced_bene = cxp_rd_spg.ced_bene ".
				"						  AND cxp_rd.cod_pro = cxp_rd_spg.cod_pro ".
				"					    GROUP BY cxp_rd_spg.numdoccom) ".
				$ls_criterio.
				"   AND sep_solicitud.codtipsol = sep_tiposolicitud.codtipsol";
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Contabiliza MÉTODO->uf_select_solicitudes_contabilizar ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_numsol=rtrim($row["numsol"]);
				$ls_fecregsol=$this->io_function->uf_formatovalidofecha($row["fecregsol"]);
				$ls_fecregsol=$this->io_function->uf_convertirfecmostrar($ls_fecregsol);
				$ls_consol=rtrim($row["consol"]);
				$ld_fechaconta=$this->io_function->uf_formatovalidofecha($row["fechaconta"]);
				$ld_fechaanula=$this->io_function->uf_formatovalidofecha($row["fechaanula"]);
				
				$ao_object[$ai_totrows][1]="<input type=checkbox name=chksel".$ai_totrows." id=chksel".$ai_totrows." value=1 style=width:15px;height:15px >";		
				$ao_object[$ai_totrows][2]="<input type=text name=txtnumsol".$ai_totrows." value='".$ls_numsol."' class=sin-borde readonly style=text-align:center size=20 maxlength=15>";
				$ao_object[$ai_totrows][3]="<input type=text name=txtfecregsol".$ai_totrows." value='".$ls_fecregsol."' class=sin-borde readonly style=text-align:center size=12 maxlength=12>";
				$ao_object[$ai_totrows][4]="<input type=text name=txtconsol".$ai_totrows." value='".$ls_consol."' class=sin-borde readonly style=text-align:left size=60 maxlength=60>";												
				$ao_object[$ai_totrows][5]="<div align='center'><a href=javascript:uf_verdetalle('".$ls_numsol."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>".
										   "<input type=hidden name=txtfechaconta".$ai_totrows." id=txtfechaconta".$ai_totrows." value='".$ld_fechaconta."'>".
										   "<input type=hidden name=txtfechaanula".$ai_totrows." id=txtfechaanula".$ai_totrows." value='".$ld_fechaanula."'>";
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_select_solicitudes_contabilizar	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudes_anuladas($as_numsol,$as_fecreg,$as_fecapr,$as_codigo,$as_tipo,
												&$ao_object,&$ai_totrows,$as_estatus,$as_operacion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_solicitudes_contabilizar
		//		   Access: public (sigesp_mis_p_contibiliza_sep.php)
		//	    Arguments: as_numsol  // Número de Solicitud
		//	   			   as_fecreg  // Fecha de Registro
		//	   			   as_fecapr  // Fecha de aprobación
		//	   			   as_codpro  // código del Proveedor
		//				   ao_object  // Arreglo de objetos
		//				   ai_totrows  // Total de Filas
		//				   ai_estatus  // estatus de los movimientos a consultar
		//	      Returns: lb_valido True si se ejecuto la busqueda correctamente
		//	  Description: Método que obtiene todas aquellas solicitud en estatus emitidas
		//                  para su contabilizacion
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 07/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
        $lb_valido=true;
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_criterio="";
		if(!empty($as_numsol))
		{
			$ls_criterio=$ls_criterio." AND numsol like '%".$as_numsol."%'";
		}
		if(!empty($as_fecreg))
		{
			$as_fecreg=$this->io_function->uf_convertirdatetobd($as_fecreg);
			$ls_criterio=$ls_criterio." AND fecregsol = '".$as_fecreg."'";
		}
		if(!empty($as_fecapr))
		{
			$as_fecapr=$this->io_function->uf_convertirdatetobd($as_fecapr);
			$ls_criterio=$ls_criterio." AND fecaprsep = '".$as_fecapr."'";
		}
		if(!empty($as_tipo))
		{
			$ls_criterio=$ls_criterio." AND tipo_destino = '".$as_tipo."' ";
			if(!empty($as_codigo))
			{
				switch($as_tipo)
				{
					case "P": // es un proveedor
						$ls_criterio=$ls_criterio." AND cod_pro = '".$as_codigo."' ";
						break;
	
					case "B": // es un beneficiario
						$ls_criterio=$ls_criterio." AND ced_bene = '".$as_codigo."' ";
						break;
				}
			}
		}
		switch($as_operacion)
		{
			case "REVERSAR":
				$ls_criterio=$ls_criterio." AND NOT numsol IN (SELECT numsol FROM soc_enlace_sep ".
                              			  "  						        WHERE sep_solicitud.codemp =  soc_enlace_sep.codemp ".
                              			  "  						          AND sep_solicitud.numsol =  soc_enlace_sep.numsol ".
                              			  "  						          AND soc_enlace_sep.estordcom = 3 ".
										  "								    GROUP BY soc_enlace_sep.numsol) ";
				break;
		}
		$ls_sql="SELECT sep_solicitud.numsol, sep_solicitud.fecregsol, sep_solicitud.consol, sep_solicitud.fechaconta, ".
				"		sep_solicitud.fechaanula ".
                "  FROM sep_solicitud, sep_tiposolicitud ".
				" WHERE sep_solicitud.codemp = '".$ls_codemp."' ".
				"   AND sep_solicitud.estsol = '".$as_estatus."' ".
				"   AND sep_solicitud.estapro = 1 ".
				"   AND sep_tiposolicitud.estope <> 'S' ".
				"   AND NOT numsol IN (SELECT numdoccom ".
				"						 FROM cxp_rd_spg, cxp_rd ".
				"  						WHERE sep_solicitud.codemp =  cxp_rd_spg.codemp ".
				"  						  AND sep_solicitud.numsol =  cxp_rd_spg.numdoccom ".
				"  						  AND cxp_rd_spg.procede_doc = 'SEPSPC' ".
				"  						  AND cxp_rd.estprodoc <> 'A' ".
				"						  AND cxp_rd.codemp = cxp_rd_spg.codemp ".
				"						  AND cxp_rd.numrecdoc = cxp_rd_spg.numrecdoc ".
				"						  AND cxp_rd.codtipdoc = cxp_rd_spg.codtipdoc ".
				"						  AND cxp_rd.ced_bene = cxp_rd_spg.ced_bene ".
				"						  AND cxp_rd.cod_pro = cxp_rd_spg.cod_pro ".
				"					    GROUP BY cxp_rd_spg.numdoccom) ".
				$ls_criterio.
				"   AND sep_solicitud.codtipsol = sep_tiposolicitud.codtipsol";
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Contabiliza MÉTODO->uf_select_solicitudes_contabilizar ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_numsol=rtrim($row["numsol"]);
				$ls_fecregsol=$this->io_function->uf_formatovalidofecha($row["fecregsol"]);
				$ls_fecregsol=$this->io_function->uf_convertirfecmostrar($ls_fecregsol);
				$ls_consol=rtrim($row["consol"]);
				$ld_fechaconta=$this->io_function->uf_formatovalidofecha($row["fechaconta"]);
				$ld_fechaanula=$this->io_function->uf_formatovalidofecha($row["fechaanula"]);
				
				$ao_object[$ai_totrows][1]="<input type=checkbox name=chksel".$ai_totrows." id=chksel".$ai_totrows." value=1 style=width:15px;height:15px >";		
				$ao_object[$ai_totrows][2]="<input type=text name=txtnumsol".$ai_totrows." value='".$ls_numsol."' class=sin-borde readonly style=text-align:center size=20 maxlength=15>";
				$ao_object[$ai_totrows][3]="<input type=text name=txtfecregsol".$ai_totrows." value='".$ls_fecregsol."' class=sin-borde readonly style=text-align:center size=12 maxlength=12>";
				$ao_object[$ai_totrows][4]="<input type=text name=txtconsol".$ai_totrows." value='".$ls_consol."' class=sin-borde readonly style=text-align:left size=60 maxlength=60>";												
				$ao_object[$ai_totrows][5]="<div align='center'><a href=javascript:uf_verdetalle('".$ls_numsol."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>".
										   "<input type=hidden name=txtfechaconta".$ai_totrows." id=txtfechaconta".$ai_totrows." value='".$ld_fechaconta."'>".
										   "<input type=hidden name=txtfechaanula".$ai_totrows." id=txtfechaanula".$ai_totrows." value='".$ld_fechaanula."'>";
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_select_solicitudes_contabilizar	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_ordencompra_contabilizar($as_operacion,$as_numordcom,$as_codprov,$ad_fecapr,$ai_estatus,&$ao_object,&$ai_totrows,$as_proceso)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	    Function : uf_select_ordencompra_contabilizar
		//        Access : public
		//	   Arguments : as_operacion  // Si es de Bienes, de Servicios ó Ambas
		//				   as_numordcom  // Número de la Orden de Compra
		//				   as_codprov  // Código de Proveedor
		//				   ad_fecapr  // Fecha de Aprobación
		//				   ai_estatus  // Estatus de Conatbilización
		//				   ao_object  // Arreglo de objetos
		//				   ai_totrows  // total del Filas
		//	     Returns : Ordenes de compra contabilizadas
		//	 Description :Método que obtiene todas aquellas ordenes de compra en estatus emitidas para su contabilizacion
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
        $lb_valido=true;
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_criterio="";
		$ls_criterio2="   AND NOT numordcom IN (SELECT numdoccom ".
					  "					   	    FROM cxp_rd_spg, cxp_rd ".
					  "						   WHERE soc_ordencompra.codemp =  cxp_rd_spg.codemp ".
					  "						     AND soc_ordencompra.numordcom =  cxp_rd_spg.numdoccom ".
					  "  						 AND cxp_rd_spg.procede_doc=(CASE soc_ordencompra.estcondat ".
					  "														 WHEN 'S' THEN 'SOCCOS' ".
					  "																  ELSE 'SOCCOC' END) ".
					  "  						 AND cxp_rd.estprodoc <> 'A' ".
					  "						     AND cxp_rd.codemp = cxp_rd_spg.codemp ".
					  "						     AND cxp_rd.numrecdoc = cxp_rd_spg.numrecdoc ".
					  "						     AND cxp_rd.codtipdoc = cxp_rd_spg.codtipdoc ".
					  "						     AND cxp_rd.ced_bene = cxp_rd_spg.ced_bene ".
					  "						     AND cxp_rd.cod_pro = cxp_rd_spg.cod_pro ".
					  "					       GROUP BY cxp_rd_spg.numdoccom) ";
		if($as_numordcom!="")
		{
			$ls_criterio=$ls_criterio." AND numordcom like '%".$as_numordcom."%'";
		}
		if($as_codprov!="")
		{
			$ls_criterio=$ls_criterio." AND cod_pro = '".$as_codprov."'";
		}
		if($ad_fecapr!="")
		{
			$ls_criterio=$ls_criterio." AND fecaprord ='".$this->io_function->uf_convertirdatetobd($ad_fecapr)."'";
		}
		if($as_operacion!="-")
		{
			$ls_criterio=$ls_criterio." AND estcondat ='".$as_operacion."'";
		}
		switch($as_proceso)
		{
			case "REVERSAR":
				$ls_criterio2="   AND NOT numordcom IN (SELECT numdoccom ".
							  "					   	    FROM cxp_rd_spg, cxp_rd ".
							  "						   WHERE soc_ordencompra.codemp =  cxp_rd_spg.codemp ".
							  "						     AND soc_ordencompra.numordcom =  cxp_rd_spg.numdoccom ".							  
							  "                          AND cxp_rd.estprodoc<>'A'".
							  "  						 AND cxp_rd_spg.procede_doc=(CASE soc_ordencompra.estcondat ".
							  "														 WHEN 'S' THEN 'SOCCOS' ".
							  "																  ELSE 'SOCCOC' END) ".
							  "						     AND cxp_rd.codemp = cxp_rd_spg.codemp ".
							  "						     AND cxp_rd.numrecdoc = cxp_rd_spg.numrecdoc ".
							  "						     AND cxp_rd.codtipdoc = cxp_rd_spg.codtipdoc ".
							  "						     AND cxp_rd.ced_bene = cxp_rd_spg.ced_bene ".
							  "						     AND cxp_rd.cod_pro = cxp_rd_spg.cod_pro ".
							  "					       GROUP BY cxp_rd_spg.numdoccom) ";
				break;
			case "REVERSO_ANULA":
				$ls_criterio2="   AND fechaanula<>'1900-01-01' ";
				break;
		}
		$ls_sql="SELECT numordcom, estcondat, fecordcom, obscom, fechaconta, fechaanula ".
                "  FROM soc_ordencompra ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND estcom=".$ai_estatus."  ".
				"   AND estapro=1 ".
				"   AND numordcom<>'000000000000000' ".
				$ls_criterio2.
				$ls_criterio;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_mensajes->message("CLASE->Contabiliza MÉTODO->uf_select_ordencompra_contabilizar ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		}
		else
		{
			$lb_valido=true;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows =$ai_totrows+1;
				$ls_numordcom=rtrim($row["numordcom"]);
				$ls_estcondat=rtrim($row["estcondat"]);
				$ls_estatus="";
				switch($ls_estcondat)
				{
					case "-":
						$ls_estatus="Bienes/Servicios";
						break;
					case "B":
						$ls_estatus="Bienes";
						break;
					case "S":
						$ls_estatus="Servicios";
						break;
				}
				$ls_fecordcom=$this->io_function->uf_formatovalidofecha($row["fecordcom"]);
				$ls_fecordcom = $this->io_function->uf_convertirfecmostrar($ls_fecordcom);
				$ls_obscom=rtrim($row["obscom"]);
				$ld_fechaconta=$this->io_function->uf_formatovalidofecha($row["fechaconta"]);
				$ld_fechaanula=$this->io_function->uf_formatovalidofecha($row["fechaanula"]);
				$ao_object[$ai_totrows][1]="<input type=checkbox name=chksel".$ai_totrows." id=chksel".$ai_totrows." value=1 style=width:15px;height:15px>";		
				$ao_object[$ai_totrows][2]="<input type=text name=txtestatus".$ai_totrows." value='".$ls_estatus."' class=sin-borde readonly style=text-align:center size=16 maxlength=16>";
				$ao_object[$ai_totrows][3]="<input type=text name=txtnumordcom".$ai_totrows." value='".$ls_numordcom."' class=sin-borde readonly style=text-align:center size=20 maxlength=15>";
				$ao_object[$ai_totrows][4]="<input type=text name=txtfecordcom".$ai_totrows." value='".$ls_fecordcom."' class=sin-borde readonly style=text-align:center size=12 maxlength=12>";
				$ao_object[$ai_totrows][5]="<input type=text name=txtobscom".$ai_totrows." value='".$ls_obscom."' class=sin-borde readonly style=text-align:left size=60 maxlength=60>";
				$ao_object[$ai_totrows][6]="<div align='center'><a href=javascript:uf_verdetalle('".$ls_numordcom."','".$ls_estcondat."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>".
										   "<input type=hidden name=txtestcondat".$ai_totrows." value='".$ls_estcondat."'>".
										   "<input type=hidden name=txtfechaconta".$ai_totrows." id=txtfechaconta".$ai_totrows." value='".$ld_fechaconta."'>".
										   "<input type=hidden name=txtfechaanula".$ai_totrows." id=txtfechaanula".$ai_totrows." value='".$ld_fechaanula."'>";
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_select_ordencompra_contabilizar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_recepciones_contabilizar($as_numrecdoc,$as_fecemi,$as_fecapr,$as_codigo,$as_tipo,&$ao_object,&$ai_totrows,
												$as_estatus,$as_proceso)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_recepciones_contabilizar
		//		   Access: public (sigesp_mis_p_contibiliza_rd.php)
		//	    Arguments: as_numrecdoc  // Número de Recepción
		//	   			   as_fecemi  // Fecha de Emision
		//	   			   as_fecapr  // Fecha de aprobación
		//	   			   as_codpro  // código del Proveedor
		//				   ao_object  // Arreglo de objetos
		//				   ai_totrows  // Total de Filas
		//				   ai_estatus  // estatus de los movimientos a consultar
		//	      Returns: lb_valido True si se ejecuto la busqueda correctamente
		//	  Description: Método que obtiene todas aquellas solicitud de ordenes de pago en estatus emitidas 
		//                  para su contabilizacion
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 05/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
        $lb_valido=true;
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_criterio="";
		if(!empty($as_numsol))
		{
			$ls_criterio=$ls_criterio." AND numrecdoc like '%".$as_numrecdoc."%'";
		}
		if(!empty($as_fecemi))
		{
			$as_fecemi=$this->io_function->uf_convertirdatetobd($as_fecemi);
			$ls_criterio=$ls_criterio." AND fecemidoc = '".$as_fecemi."'";
		}
		if(!empty($as_fecapr))
		{
			$as_fecapr=$this->io_function->uf_convertirdatetobd($as_fecapr);
			$ls_criterio=$ls_criterio." AND fecaprord = '".$as_fecapr."'";
		}
		if(!empty($as_tipo))
		{
			$ls_criterio=$ls_criterio." AND tipproben = '".$as_tipo."' ";
			if(!empty($as_codigo))
			{
				switch($as_tipo)
				{
					case "P": // es un proveedor
						$ls_criterio=$ls_criterio." AND cod_pro = '".$as_codigo."' ";
						break;
	
					case "B": // es un beneficiario
						$ls_criterio=$ls_criterio." AND ced_bene = '".$as_codigo."' ";
						break;
				}
			}
		}
		switch($as_proceso)
		{
			case "REVERSAR":
				$ls_criterio="   AND NOT numrecdoc IN (SELECT numrecdoc ".
							 "					   	    FROM cxp_dt_solicitudes ".
							 "						   WHERE cxp_rd.codemp = cxp_dt_solicitudes.codemp ".
							 "						     AND cxp_rd.numrecdoc = cxp_dt_solicitudes.numrecdoc ".
							 "						     AND cxp_rd.codtipdoc = cxp_dt_solicitudes.codtipdoc ".
							 "						     AND cxp_rd.ced_bene = cxp_dt_solicitudes.ced_bene ".
							 "						     AND cxp_rd.cod_pro = cxp_dt_solicitudes.cod_pro) ";
				break;

			case "ANULAR":
				$ls_criterio="   AND ((NOT numrecdoc IN (SELECT numrecdoc ".
							 "					   	    FROM cxp_dt_solicitudes ".
							 "						   WHERE cxp_rd.codemp = cxp_dt_solicitudes.codemp ".
							 "						     AND cxp_rd.numrecdoc = cxp_dt_solicitudes.numrecdoc ".
							 "						     AND cxp_rd.codtipdoc = cxp_dt_solicitudes.codtipdoc ".
							 "						     AND cxp_rd.ced_bene = cxp_dt_solicitudes.ced_bene ".
							 "						     AND cxp_rd.cod_pro = cxp_dt_solicitudes.cod_pro) )".
							 "   OR (numrecdoc IN (SELECT numrecdoc  ".
							 "					   	    FROM cxp_dt_solicitudes, cxp_solicitudes ".
							 "						   WHERE cxp_solicitudes.estprosol = 'A' ".
							 "							 AND cxp_dt_solicitudes.codemp = cxp_solicitudes.codemp  ".
							 "							 AND cxp_dt_solicitudes.numsol = cxp_solicitudes.numsol  ".
							 "							 AND cxp_rd.codemp = cxp_dt_solicitudes.codemp ".
							 "						     AND cxp_rd.numrecdoc = cxp_dt_solicitudes.numrecdoc ".
							 "						     AND cxp_rd.codtipdoc = cxp_dt_solicitudes.codtipdoc ".
							 "						     AND cxp_rd.ced_bene = cxp_dt_solicitudes.ced_bene ".
							 "						     AND cxp_rd.cod_pro = cxp_dt_solicitudes.cod_pro) ) )";
				break;

		}
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRES":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
			case "INFORMIX":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT numrecdoc, codtipdoc, ced_bene, cod_pro, dencondoc, fecemidoc, fechaconta, fechaanula,tipproben, codrecdoc, ".
				"		(CASE tipproben WHEN 'P' THEN (SELECT nompro FROM rpc_proveedor ".
				"									   WHERE rpc_proveedor.codemp = cxp_rd.codemp ".
				"										 AND rpc_proveedor.cod_pro = cxp_rd.cod_pro) ".
				"								 ELSE (SELECT ".$ls_cadena." FROM rpc_beneficiario ".
				"									    WHERE rpc_beneficiario.codemp = cxp_rd.codemp ".
				"									 	  AND rpc_beneficiario.ced_bene = cxp_rd.ced_bene) ".
				"		  END) AS nombre ".
                "  FROM cxp_rd ".
				" WHERE codemp = '".$ls_codemp."' ".
				"   AND estprodoc = '".$as_estatus."'  ".
				"   AND estaprord = 1 ".
				$ls_criterio;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_mensajes->message("CLASE->Contabiliza MÉTODO->uf_select_recepciones_contabilizar ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codrecdoc=rtrim($row["codrecdoc"]);
				$ls_numrecdoc=rtrim($row["numrecdoc"]);
				$ls_codtipdoc=rtrim($row["codtipdoc"]);
				$ls_ced_bene=rtrim($row["ced_bene"]);
				$ls_cod_pro=rtrim($row["cod_pro"]);
				$ls_nomproben=rtrim($row["nombre"]);
				$ld_fecemidoc=$this->io_function->uf_formatovalidofecha($row["fecemidoc"]);
				$ld_fecemidoc= $this->io_function->uf_convertirfecmostrar($ld_fecemidoc);
				$ls_dencondoc=rtrim($row["dencondoc"]);
				//$ls_dencondoc="Número de Recepción ".$ls_numrecdoc." Tipo Documento ".$ls_codtipdoc." Beneficiario ".$ls_ced_bene." Proveedor ".$ls_cod_pro." ".$ls_dencondoc;
				$ld_fechaconta=$this->io_function->uf_formatovalidofecha($row["fechaconta"]);
				$ld_fechaanula=$this->io_function->uf_formatovalidofecha($row["fechaanula"]);
				
				$ao_object[$ai_totrows][1]="<input type=checkbox name=chksel".$ai_totrows." id=chksel".$ai_totrows." value=1 style=width:15px;height:15px >";		
				$ao_object[$ai_totrows][2]="<input type=text   name=txtnumrecdoc".$ai_totrows." value='".$ls_numrecdoc."' class=sin-borde readonly style=text-align:center size=20 maxlength=15>".
										   "<input type=hidden name=txtcodtipdoc".$ai_totrows." value='".$ls_codtipdoc."'>".
										   "<input type=hidden name=txtcodpro".$ai_totrows."    value='".$ls_cod_pro."'>".
										   "<input type=hidden name=txtcedbene".$ai_totrows."   value='".$ls_ced_bene."'> ";
				$ao_object[$ai_totrows][3]="<input type=text   name=txtnomproben".$ai_totrows." value='".$ls_nomproben."' class=sin-borde readonly style=text-align:center size=12 maxlength=12>";
				$ao_object[$ai_totrows][4]="<input type=text   name=txtfecemidoc".$ai_totrows." value='".$ld_fecemidoc."' class=sin-borde readonly style=text-align:center size=12 maxlength=12>";
				$ao_object[$ai_totrows][5]="<input type=text   name=txtdencondoc".$ai_totrows." value='".$ls_dencondoc."' class=sin-borde readonly style=text-align:left size=60 maxlength=60>";												
				$ao_object[$ai_totrows][6]="<div align='center'><a href=javascript:uf_verdetalle('".$ls_codrecdoc."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>".
										   "<input type=hidden name=txtfechaconta".$ai_totrows." id=txtfechaconta".$ai_totrows." value='".$ld_fechaconta."'>".
										   "<input type=hidden name=txtfechaanula".$ai_totrows." id=txtfechaanula".$ai_totrows." value='".$ld_fechaanula."'>";
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_select_recepciones_contabilizar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_orden_pago_contabilizar($as_numsol,$as_fecemi,$as_fecapr,$as_codigo,$as_tipo,&$ao_object,&$ai_totrows,$as_estatus)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_orden_pago_contabilizar
		//		   Access: public (sigesp_mis_p_contibiliza_cxp.php)
		//	    Arguments: as_numsol  // Número de Solicitud
		//	   			   as_fecemi  // Fecha de Emision
		//	   			   as_fecapr  // Fecha de aprobación
		//	   			   as_codpro  // código del Proveedor
		//				   ao_object  // Arreglo de objetos
		//				   ai_totrows  // Total de Filas
		//				   ai_estatus  // estatus de los movimientos a consultar
		//	      Returns: lb_valido True si se ejecuto la busqueda correctamente
		//	  Description: Método que obtiene todas aquellas solicitud de ordenes de pago en estatus emitidas 
		//                  para su contabilizacion
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 05/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
        $lb_valido=true;
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_criterio="";
		if(!empty($as_numsol))
		{
			$ls_criterio=$ls_criterio." AND numsol like '%".$as_numsol."%'";
		}
		if(!empty($as_fecemi))
		{
			$as_fecemi=$this->io_function->uf_convertirdatetobd($as_fecemi);
			$ls_criterio=$ls_criterio." AND fecemisol = '".$as_fecemi."'";
		}
		if(!empty($as_fecapr))
		{
			$as_fecapr=$this->io_function->uf_convertirdatetobd($as_fecapr);
			$ls_criterio=$ls_criterio." AND fecaprosol = '".$as_fecapr."'";
		}
		if(!empty($as_tipo))
		{
			$ls_criterio=$ls_criterio." AND tipproben = '".$as_tipo."' ";
			if(!empty($as_codigo))
			{
				switch($as_tipo)
				{
					case "P": // es un proveedor
						$ls_criterio=$ls_criterio." AND cod_pro = '".$as_codigo."' ";
						break;
	
					case "B": // es un beneficiario
						$ls_criterio=$ls_criterio." AND ced_bene = '".$as_codigo."' ";
						break;
				}
			}
		}
		$ls_sql="SELECT numsol, fecemisol, consol, fechaconta, fechaanula  ".
                "  FROM cxp_solicitudes ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND estprosol='".$as_estatus."'  ".
				"   AND estaprosol = 1 ".
				$ls_criterio;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_mensajes->message("CLASE->Contabiliza MÉTODO->uf_select_orden_pago_contabilizar ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_numsol=rtrim($row["numsol"]);
				$ls_fecemisol=$this->io_function->uf_formatovalidofecha($row["fecemisol"]);
				$ls_fecemisol = $this->io_function->uf_convertirfecmostrar($ls_fecemisol);
				$ls_consol=rtrim($row["consol"]);
				$ld_fechaconta=$this->io_function->uf_formatovalidofecha($row["fechaconta"]);
				$ld_fechaanula=$this->io_function->uf_formatovalidofecha($row["fechaanula"]);
				$ao_object[$ai_totrows][1]="<input type=checkbox name=chksel".$ai_totrows." id=chksel".$ai_totrows." value=1 style=width:15px;height:15px >";		
				$ao_object[$ai_totrows][2]="<input type=text name=txtnumsol".$ai_totrows." value='".$ls_numsol."' class=sin-borde readonly style=text-align:center size=20 maxlength=15>";
				$ao_object[$ai_totrows][3]="<input type=text name=txtfecemisol".$ai_totrows." value='".$ls_fecemisol."' class=sin-borde readonly style=text-align:center size=12 maxlength=12>";
				$ao_object[$ai_totrows][4]="<input type=text name=txtconsol".$ai_totrows." value='".$ls_consol."' class=sin-borde readonly style=text-align:left size=60 maxlength=60>";												
				$ao_object[$ai_totrows][5]="<div align='center'><a href=javascript:uf_verdetalle('".$ls_numsol."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>".
										   "<input type=hidden name=txtfechaconta".$ai_totrows." id=txtfechaconta".$ai_totrows." value='".$ld_fechaconta."'>".
										   "<input type=hidden name=txtfechaanula".$ai_totrows." id=txtfechaanula".$ai_totrows." value='".$ld_fechaanula."'>";
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_select_orden_pago_contabilizar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_notas_contabilizar($as_numsol,$as_numrecdoc,$as_operacion,$as_fecope,$as_fecapr,&$ao_object,&$ai_totrows,$as_estatus)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_notas_contabilizar
		//		   Access: public (sigesp_mis_p_contibiliza_ncd.php)
		//	    Arguments: as_numsol  // Número de Solicitud
		//	   			   as_numrecdoc  // Número de Recepción de Documento
		//	   			   as_operacion  // Operación
		//	   			   as_fecope  // Fecha de Operación
		//	   			   as_fecapr  // Fecha de aprobación
		//				   ao_object  // Arreglo de objetos
		//				   ai_totrows  // Total de Filas
		//				   ai_estatus  // estatus de los movimientos a consultar
		//	      Returns: lb_valido True si se ejecuto la busqueda correctamente
		//	  Description: Método que obtiene todas aquellas Notas de Débito ó Crédito en estatus emitidas 
		//                  para su contabilizacion
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 05/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
        $lb_valido=true;
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_criterio="";
		if(!empty($as_numsol))
		{
			$ls_criterio=$ls_criterio." AND numsol like '%".$as_numsol."%'";
		}
		if(!empty($as_numrecdoc))
		{
			$ls_criterio=$ls_criterio." AND numrecdoc like '%".$as_numrecdoc."%'";
		}
		if(!empty($as_operacion))
		{
			$ls_criterio=$ls_criterio." AND codope='".$as_operacion."' ";
		}
		if(!empty($as_fecope))
		{
			$as_fecope=$this->io_function->uf_convertirdatetobd($as_fecope);
			$ls_criterio=$ls_criterio." AND fecope = '".$as_fecope."'";
		}
		if(!empty($as_fecapr))
		{
			$as_fecapr=$this->io_function->uf_convertirdatetobd($as_fecapr);
			$ls_criterio=$ls_criterio." AND fecaprnc = '".$as_fecapr."'";
		}
		$ls_sql="SELECT numsol, numrecdoc, codtipdoc,ced_bene, cod_pro, codope, numdc, fecope, desope, fechaconta, fechaanula ".
                "  FROM cxp_sol_dc ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND estnotadc='".$as_estatus."' ".
				"   AND estapr=1".
				$ls_criterio;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_mensajes->message("CLASE->Contabiliza MÉTODO->uf_select_notas_contabilizar ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_numsol=rtrim($row["numsol"]);
				$ls_numrecdoc=rtrim($row["numrecdoc"]);
				$ls_codtipdoc=rtrim($row["codtipdoc"]);
				$ls_ced_bene=rtrim($row["ced_bene"]);
				$ls_cod_pro=rtrim($row["cod_pro"]);
				$ls_codope=rtrim($row["codope"]);
				$ls_numdc=rtrim($row["numdc"]);
				$ldt_fecope=$this->io_function->uf_formatovalidofecha($row["fecope"]);
				$ldt_fecope=$this->io_function->uf_convertirfecmostrar($ldt_fecope);
				$ls_desope=rtrim($row["desope"]);
				$ld_fechaconta=$this->io_function->uf_formatovalidofecha($row["fechaconta"]);
				$ld_fechaanula=$this->io_function->uf_formatovalidofecha($row["fechaanula"]);
				$ao_object[$ai_totrows][1]="<input type=checkbox name=chksel".$ai_totrows." id=chksel".$ai_totrows." value=1 style=width:15px;height:15px >";		
				$ao_object[$ai_totrows][2]="<input type=text name=txtnumsol".$ai_totrows." value='".$ls_numsol."' class=sin-borde readonly style=text-align:center size=20 maxlength=20>";
				$ao_object[$ai_totrows][3]="<input type=text name=txtnumrecdoc".$ai_totrows." value='".$ls_numrecdoc."' class=sin-borde readonly style=text-align:center size=15 maxlength=20>";				
				$ao_object[$ai_totrows][4]="<input type=text name=txtnumdc".$ai_totrows." value='".$ls_numdc."' class=sin-borde readonly style=text-align:center size=10 maxlength=20>";
				$ao_object[$ai_totrows][5]="<input type=text name=txtfecope".$ai_totrows." value='".$ldt_fecope."' class=sin-borde readonly style=text-align:center size=15 maxlength=20>";				
				$ao_object[$ai_totrows][6]="<input type=text name=txtdesope".$ai_totrows." value='".$ls_desope."' class=sin-borde readonly style=text-align:left size=35 maxlength=70>".
										   "<input type=hidden name=txtced_bene".$ai_totrows." value='".$ls_ced_bene."'>".
										   "<input type=hidden name=txtcod_pro".$ai_totrows." value='".$ls_cod_pro."'>".
										   "<input type=hidden name=txtcodtipdoc".$ai_totrows." value='".$ls_codtipdoc."'>".										  
										   "<input type=hidden name=txtcodope".$ai_totrows." value='".$ls_codope."'>".
										   "<input type=hidden name=txtfechaconta".$ai_totrows." id=txtfechaconta".$ai_totrows." value='".$ld_fechaconta."'>".
										   "<input type=hidden name=txtfechaanula".$ai_totrows." id=txtfechaanula".$ai_totrows." value='".$ld_fechaanula."'>";
				$ao_object[$ai_totrows][7]="<div align='center'><a href=javascript:uf_verdetalle('".$ls_numsol."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>";
			}
		    $this->io_sql->free_result($rs_data);					
		}
		return $lb_valido;
	}// end function uf_select_notas_contabilizar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_banco_contabilizar($as_numdoc,$as_fecdoc,$as_tipo_operacion,&$ao_object,&$ai_totrows,$as_estatus)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_banco_contabilizar
		//		   Access: public (sigesp_mis_p_contabiliza_scb.php, sigesp_mis_p_reverso_scb.php,
		//					 	   sigesp_mis_p_anula_scb.php, sigesp_mis_p_reverso_anula_scb.php)
		//	    Arguments: as_numdoc  // Número de Documento
		//	   			   as_fecdoc  // Fecha del documento
		//	   			   as_tipo_operacion  // Operación del Movimiento
		//				   ao_object  // Arreglo de objetos
		//				   ai_totrows  // Total de Filas
		//				   ai_estatus  // estatus de los movimientos a consultar
		//	      Returns: lb_valido True si se ejecuto la busqueda correctamente
		//	  Description: Funcion que obtiene todas aquellos movimientos de banco en estatus para su contabilizacion
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 27/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ai_totrows=0;
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_criterio="";
		if(!empty($as_numdoc))
		{
			$ls_criterio=$ls_criterio." AND numdoc like '%".$as_numdoc."%'";
		}
		if(!empty($as_fecdoc))
		{
			$as_fecdoc=$this->io_function->uf_convertirdatetobd($as_fecdoc);
			$ls_criterio=$ls_criterio." AND fecmov = '".$as_fecdoc."'";
		}
		if(!empty($as_tipo_operacion))
		{
			$ls_criterio=$ls_criterio." AND codope = '".$as_tipo_operacion."'";
		}
		$ls_sql="SELECT codban, ctaban, estmov, numdoc, fecmov, conmov, codope, fechaconta, fechaanula ".
                "  FROM scb_movbco ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND estmov='".$as_estatus."' ".
				"   AND estmodordpag <> 'CM' ".
				"   AND estcon = 0 ".
				  $ls_criterio;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_mensajes->message("CLASE->Contabiliza MÉTODO->uf_select_banco_contabilizar ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codban=rtrim($row["codban"]);
				$ls_ctaban=rtrim($row["ctaban"]);
				$ls_estmov=rtrim($row["estmov"]);
				$ls_numdoc=rtrim($row["numdoc"]);
				$ls_fecmov=$this->io_function->uf_formatovalidofecha($row["fecmov"]);
				$ls_fecmov=$this->io_function->uf_convertirfecmostrar($ls_fecmov);
				$ls_conmov=rtrim($row["conmov"]);
				$ls_codope=rtrim($row["codope"]);
				$ld_fechaconta=$this->io_function->uf_formatovalidofecha($row["fechaconta"]);
				$ld_fechaanula=$this->io_function->uf_formatovalidofecha($row["fechaanula"]);
				$ao_object[$ai_totrows][1]="<input type=checkbox name=chksel".$ai_totrows." id=chksel".$ai_totrows." value=1 style=width:15px;height:15px onclick=javascript:ue_cargarconcepto(".$ai_totrows.");>";		
				$ao_object[$ai_totrows][2]="<input type=text name=txtnumdoc".$ai_totrows." value='".$ls_numdoc."' class=sin-borde style=text-align:center size=20 maxlength=15 readonly>";
				$ao_object[$ai_totrows][3]="<input type=text name=txtfecmov".$ai_totrows." value='".$ls_fecmov."' class=sin-borde style=text-align:center size=12 maxlength=12 readonly>";
				$ao_object[$ai_totrows][4]="<input type=text name=txtconmov".$ai_totrows." value='".$ls_conmov."' title='".$ls_conmov."' style=text-align:left class=sin-borde size=90 maxlength=90 readonly>".
     				                       "<input type=hidden name=txtconanu".$ai_totrows."  id=txtconanu".$ai_totrows.">".
     				                       "<input type=hidden name=txtcodban".$ai_totrows." value='".$ls_codban."'>".
	   				                       "<input type=hidden name=txtctaban".$ai_totrows." value='".$ls_ctaban."'>".
	   				                       "<input type=hidden name=txtcodope".$ai_totrows." value='".$ls_codope."'>".
										   "<input type=hidden name=txtestmov".$ai_totrows." value='".$ls_estmov."'>".
										   "<input type=hidden name=txtfechaconta".$ai_totrows." id=txtfechaconta".$ai_totrows." value='".$ld_fechaconta."'>".
										   "<input type=hidden name=txtfechaanula".$ai_totrows." id=txtfechaanula".$ai_totrows." value='".$ld_fechaanula."'>";
				$ao_object[$ai_totrows][5]="<div align='center'><a href=javascript:uf_verdetalle('".$ls_numdoc."','".$ls_codban."','".$ls_ctaban."','".$ls_codope."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>";
			}
		    $this->io_sql->free_result($rs_data);					
		}
		return $lb_valido;
	}// end function uf_select_banco_contabilizar	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_colocacion_contabilizar($as_numdoc,$as_fecdoc,$as_tipo_operacion,&$ao_object,&$ai_totrows,$as_estatus)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_colocacion_contabilizar
		//		   Access: public (sigesp_mis_p_contabiliza_scbcol.php)
		//	    Arguments: as_numdoc  // Número de Documento
		//	   			   as_fecdoc  // Fecha del documento
		//	   			   as_tipo_operacion  // Operación del Movimiento
		//				   ao_object  // Arreglo de objetos
		//				   ai_totrows  // Total de Filas
		//				   ai_estatus  // estatus de los movimientos a consultar
		//	      Returns: lb_valido True si se ejecuto la busqueda correctamente
		//	  Description: Funcion que obtiene todas aquellos movimientos de banco en estatus para su contabilizacion
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 30/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
        $lb_valido=true;
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_criterio="";
		if(!empty($as_numdoc))
		{
			$ls_criterio=$ls_criterio." AND numdoc like '%".$as_numdoc."%'";
		}
		if(!empty($as_fecdoc))
		{
			$as_fecdoc=$this->io_function->uf_convertirdatetobd($as_fecdoc);
			$ls_criterio=$ls_criterio." AND fecmov = '".$as_fecdoc."'";
		}
		if(!empty($as_tipo_operacion))
		{
			$ls_criterio=$ls_criterio." AND codope = '".$as_tipo_operacion."'";
		}
		else
		{
			$ls_criterio=$ls_criterio." AND (codope = 'ND' OR codope ='NC' OR codope ='DP') ";
		}
		
		$ls_sql="SELECT codban, ctaban, estcol, numdoc, numcol, fecmovcol, conmov, codope, fechaconta, fechaanula ".
                "  FROM scb_movcol ".
			    " WHERE codemp='".$ls_codemp."' ".
				"   AND estcol='".$as_estatus."' ".
				$ls_criterio;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Contabiliza MÉTODO->uf_select_colocacion_contabilizar ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codban=rtrim($row["codban"]);
				$ls_ctaban=rtrim($row["ctaban"]);
				$ls_estcol=rtrim($row["estcol"]);
				$ls_numdoc=rtrim($row["numdoc"]);
				$ls_numcol=trim($row["numcol"]);
				$ls_fecmov=$this->io_function->uf_formatovalidofecha($row["fecmovcol"]);
				$ls_fecmov=$this->io_function->uf_convertirfecmostrar($ls_fecmov);
				$ls_conmov=rtrim($row["conmov"]);
				$ls_codope=rtrim($row["codope"]);
				$ld_fechaconta=$this->io_function->uf_formatovalidofecha($row["fechaconta"]);
				$ld_fechaanula=$this->io_function->uf_formatovalidofecha($row["fechaanula"]);
				$ao_object[$ai_totrows][1]="<input type=checkbox name=chksel".$ai_totrows." id=chksel".$ai_totrows." value=1 style=width:15px;height:15px >";
				$ao_object[$ai_totrows][2]="<input type=text name=txtnumdoc".$ai_totrows." value='".$ls_numdoc."' class=sin-borde readonly style=text-align:center size=20 maxlength=15>";
				$ao_object[$ai_totrows][3]="<input type=text name=txtfecmov".$ai_totrows." value='".$ls_fecmov."' class=sin-borde readonly style=text-align:center size=12 maxlength=12>";
				$ao_object[$ai_totrows][4]="<input type=text name=txtconmov".$ai_totrows." value='".$ls_conmov."' class=sin-borde readonly style=text-align:left size=90 maxlength=90>".
     				                       "<input type=hidden name=txtcodban".$ai_totrows." value='".$ls_codban."'>".
	   				                       "<input type=hidden name=txtctaban".$ai_totrows." value='".$ls_ctaban."'>".
	   				                       "<input type=hidden name=txtnumcol".$ai_totrows." value='".$ls_numcol."'>".										  
	   				                       "<input type=hidden name=txtcodope".$ai_totrows." value='".$ls_codope."'>".
										   "<input type=hidden name=txtestcol".$ai_totrows." value='".$ls_estcol."'>".
										   "<input type=hidden name=txtfechaconta".$ai_totrows." id=txtfechaconta".$ai_totrows." value='".$ld_fechaconta."'>".
										   "<input type=hidden name=txtfechaanula".$ai_totrows." id=txtfechaanula".$ai_totrows." value='".$ld_fechaanula."'>";
				$ao_object[$ai_totrows][5]="<div align='center'><a href=javascript:uf_verdetalle('".$ls_numdoc."','".$ls_codban."','".$ls_ctaban."','".$ls_codope."','".$ls_numcol."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>";
			}
		    $this->io_sql->free_result($rs_data);					
		}
		return $lb_valido;
	}// end function uf_select_colocacion_contabilizar	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_banco_contabilizar_op($as_numdoc,$as_fecdoc,$as_tipo_operacion,&$ao_object,&$ai_totrows,$as_estatus)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_colocacion_contabilizar
		//		   Access: public (sigesp_mis_p_contibiliza_scbop.php)
		//	    Arguments: as_numdoc  // Número de Documento
		//	   			   as_fecdoc  // Fecha del documento
		//	   			   as_tipo_operacion  // Operación del Movimiento
		//				   ao_object  // Arreglo de objetos
		//				   ai_totrows  // Total de Filas
		//				   ai_estatus  // estatus de los movimientos a consultar
		//	      Returns: lb_valido True si se ejecuto la busqueda correctamente
		//	  Description: Método que obtiene todas aquellos movimientos de banco en estatus  para su contabilizacion para
		//                 las orden de pago
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 30/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
        $lb_valido=true;
		$ls_criterio="";
		$ls_codemp=$this->dts_empresa["codemp"];
		if(!empty($as_numdoc))
		{
			$ls_criterio=$ls_criterio." AND numdoc like '%".$as_numdoc."%'";
		}
		if(!empty($as_fecdoc))
		{
			$as_fecdoc=$this->io_function->uf_convertirdatetobd($as_fecdoc);
			$ls_criterio=$ls_criterio." AND fecmov = '".$as_fecdoc."'";
		}
		if(!empty($as_tipo_operacion))
		{
			$ls_criterio=$ls_criterio." AND codope = '".$as_tipo_operacion."'";
		}
		$ls_sql="SELECT codban, ctaban, estmov, numdoc, fecmov, conmov, codope, fechaconta, fechaanula  ".
                "  FROM scb_movbco ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND estmov='".$as_estatus."' ".
				$ls_criterio;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Contabiliza MÉTODO->uf_select_colocacion_contabilizar ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codban=rtrim($row["codban"]);
				$ls_ctaban=rtrim($row["ctaban"]);
				$ls_estmov=rtrim($row["estmov"]);
				$ls_numdoc=rtrim($row["numdoc"]);
				$ls_fecmov=$this->io_function->uf_formatovalidofecha($row["fecmov"]);
				$ls_fecmov=$this->io_function->uf_convertirfecmostrar($ls_fecmov);
				$ls_conmov=rtrim($row["conmov"]);
				$ls_codope=rtrim($row["codope"]);
				$ld_fechaconta=$this->io_function->uf_formatovalidofecha($row["fechaconta"]);
				$ld_fechaanula=$this->io_function->uf_formatovalidofecha($row["fechaanula"]);
				$ao_object[$ai_totrows][1]="<input type=checkbox name=chksel".$ai_totrows." id=chksel".$ai_totrows." value=1 style=width:15px;height:15px >";		
				$ao_object[$ai_totrows][2]="<input type=text name=txtnumdoc".$ai_totrows." value='".$ls_numdoc."' class=sin-borde readonly style=text-align:center size=20 maxlength=15>";
				$ao_object[$ai_totrows][3]="<input type=text name=txtfecmov".$ai_totrows." value='".$ls_fecmov."' class=sin-borde readonly style=text-align:center size=12 maxlength=12>";
				$ao_object[$ai_totrows][4]="<input type=text name=txtconmov".$ai_totrows." value='".$ls_conmov."' title ='".$ls_conmov."' class=sin-borde readonly style=text-align:left size=90 maxlength=90>".
     				                       "<input type=hidden name=txtcodban".$ai_totrows." value='".$ls_codban."'>".
	   				                       "<input type=hidden name=txtctaban".$ai_totrows." value='".$ls_ctaban."'>".
	   				                       "<input type=hidden name=txtcodope".$ai_totrows." value='".$ls_codope."'>".
										   "<input type=hidden name=txtestmov".$ai_totrows." value='".$ls_estmov."'>".
										   "<input type=hidden name=txtfechaconta".$ai_totrows." id=txtfechaconta".$ai_totrows." value='".$ld_fechaconta."'>".
										   "<input type=hidden name=txtfechaanula".$ai_totrows." id=txtfechaanula".$ai_totrows." value='".$ld_fechaanula."'>";
				$ao_object[$ai_totrows][5]="<div align='center'><a href=javascript:uf_verdetalle('".$ls_numdoc."','".$ls_codban."','".$ls_ctaban."','".$ls_codope."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>";
										  
			}
		    $this->io_sql->free_result($rs_data);					
		}
		return $lb_valido;
	}// end function uf_select_banco_contabilizar_op	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_sob_asignaciones_contabilizar($as_codasi,$as_codobr,$as_fecasi,$as_codigo,$ai_estatus,&$ao_object,&$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_sob_asignaciones_contabilizar
		//		   Access: public (sigesp_mis_p_contibiliza_asignacion_sob.php)
		//	    Arguments: as_codasi  // Código de Asignación
		//				   as_codobr  // Código de Obra
		//				   as_fecasi  // Fecha de Asignación
		//				   as_codigo  // Código de Proveedor
		//				   ai_estatus  // estatus por el que se quiere filtrar
		//				   ao_object  // Arreglo de objetos
		//				   ai_totrows  // Total de Filas
		//	      Returns: lb_valido True si se ejecuto la busqueda correctamente
		//	  Description: Funcion que obtiene todas aquellas asignaciones emitidas
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 24/04/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
        $lb_valido=true;
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_criterio="";
		if(!empty($as_codasi))
		{
			$ls_criterio=$ls_criterio." AND codasi like '%".$as_codasi."%'";
		}
		if(!empty($as_codobr))
		{
			$ls_criterio=$ls_criterio." AND codobr like '%".$as_codobr."%'";
		}
		if(!empty($as_codigo))
		{
			$ls_criterio=$ls_criterio." AND cod_pro='".$as_codigo."' ";
		}
		if(!empty($as_fecasi))
		{
			$as_fecasi=$this->io_function->uf_convertirdatetobd($as_fecasi);
			$ls_criterio=$ls_criterio." AND fecasi = '".$as_fecasi."'";
		}
		$ls_sql="SELECT codasi, fecasi, obsasi, fechaconta, fechaanula ".
                "  FROM sob_asignacion ".
			    " WHERE codemp='".$ls_codemp."' ".
				"   AND (estasi=1 OR estasi=6 ) ".
				"   AND estapr = 1 ".
				"   AND estspgscg=".$ai_estatus." ".
				$ls_criterio;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_mensajes->message("CLASE->Contabiliza MÉTODO->uf_select_sob_asignaciones_contabilizar ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codasi=rtrim($row["codasi"]);
				$ls_fecasi=$this->io_function->uf_formatovalidofecha($row["fecasi"]);
				$ls_fecasi=$this->io_function->uf_convertirfecmostrar($ls_fecasi);
				$ls_obsasi=rtrim($row["obsasi"]);
				$ld_fechaconta=$this->io_function->uf_formatovalidofecha($row["fechaconta"]);
				$ld_fechaanula=$this->io_function->uf_formatovalidofecha($row["fechaanula"]);
				$ao_object[$ai_totrows][1]="<input type=checkbox name=chksel".$ai_totrows."    id=chksel".$ai_totrows."    value=1 style=width:15px;height:15px >";		
				$ao_object[$ai_totrows][2]="<input type=text     name=txtcodasi".$ai_totrows." id=txtcodasi".$ai_totrows." value='".$ls_codasi."' class=sin-borde readonly style=text-align:center size=20 maxlength=20>";
				$ao_object[$ai_totrows][3]="<input type=text     name=txtfecasi".$ai_totrows." id=txtfecasi".$ai_totrows." value='".$ls_fecasi."' class=sin-borde readonly style=text-align:left size=12 maxlength=12>";
				$ao_object[$ai_totrows][4]="<input type=text     name=txtobsasi".$ai_totrows." id=txtobsasi".$ai_totrows." value='".$ls_obsasi."' class=sin-borde readonly style=text-align:center size=60 maxlength=60>";												
				$ao_object[$ai_totrows][5]="<div align='center'><a href=javascript:uf_verdetalle('".$ls_codasi."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>".
										   "<input type=hidden name=txtfechaconta".$ai_totrows." id=txtfechaconta".$ai_totrows." value='".$ld_fechaconta."'>".
										   "<input type=hidden name=txtfechaanula".$ai_totrows." id=txtfechaanula".$ai_totrows." value='".$ld_fechaanula."'>";
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_select_sob_asignaciones_contabilizar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_sob_contratos_contabilizar($as_codasi,$as_codcon,$as_feccon,$as_fecinicon,$ai_estatus,&$ao_object,&$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_sob_contratos_contabilizar
		//		   Access: public (sigesp_mis_p_contabiliza_contrato_sob.php)
		//	    Arguments: as_codasi  // Código de Asignación
		//				   as_codcon  // Código del contrato
		//				   as_feccon  // Fecha de Contrato
		//				   as_fecinicon  // Fecha de Inicio del contrato
		//				   ai_estatus  // estatus por el que se quiere filtrar
		//				   ao_object  // Arreglo de objetos
		//				   ai_totrows  // Total de Filas
		//	      Returns: lb_valido True si se ejecuto la busqueda correctamente
		//	  Description: Funcion que obtiene todas aquellos contratos emitidos
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 30/04/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
        $lb_valido=true;
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_criterio="";
		if(!empty($as_codasi))
		{
			$ls_criterio=$ls_criterio." AND codasi like '%".$as_codasi."%'";
		}
		if(!empty($as_codobr))
		{
			$ls_criterio=$ls_criterio." AND codcon like '%".$as_codcon."%'";
		}
		if(!empty($as_feccon))
		{
			$as_feccon=$this->io_function->uf_convertirdatetobd($as_feccon);
			$ls_criterio=$ls_criterio." AND feccon = '".$as_feccon."'";
		}
		if(!empty($as_fecinicon))
		{
			$as_fecinicon=$this->io_function->uf_convertirdatetobd($as_fecinicon);
			$ls_criterio=$ls_criterio." AND fecinicon = '".$as_fecinicon."'";
		}
		$ls_sql="SELECT codcon, codasi, feccon, obscon, fechaconta, fechaanula, ".
				"		(SELECT sob_asignacion.fechaconta FROM sob_asignacion ".
				"		  WHERE sob_asignacion.codemp=sob_contrato.codemp ".
				"			AND sob_asignacion.codasi=sob_contrato.codasi) AS  fechacontaasig ".
                "  FROM sob_contrato  ".
                " WHERE codemp='".$ls_codemp."' ".
				"   AND estapr = 1 ".
				"   AND estspgscg=".$ai_estatus." ".
				$ls_criterio;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_mensajes->message("CLASE->Contabiliza MÉTODO->uf_select_sob_contratos_contabilizar ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codcon=rtrim($row["codcon"]);
				$ls_codasi=rtrim($row["codasi"]);
				$ls_feccon=$this->io_function->uf_formatovalidofecha($row["feccon"]);
				$ls_feccon=$this->io_function->uf_convertirfecmostrar($ls_feccon);
				$ls_obscon=rtrim($row["obscon"]);
				$ld_fechacontaasig=$this->io_function->uf_formatovalidofecha($row["fechacontaasig"]);
				$ld_fechaconta=$this->io_function->uf_formatovalidofecha($row["fechaconta"]);
				$ld_fechaanula=$this->io_function->uf_formatovalidofecha($row["fechaanula"]);
				$ao_object[$ai_totrows][1]="<input type=checkbox name=chksel".$ai_totrows."    id=chksel".$ai_totrows." value=1 style=width:15px;height:15px >";		
				$ao_object[$ai_totrows][2]="<input type=text     name=txtcodcon".$ai_totrows." id=txtcodcon".$ai_totrows." value='".$ls_codcon."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";				
				$ao_object[$ai_totrows][3]="<input type=text     name=txtcodasi".$ai_totrows." id=txtcodasi".$ai_totrows." value='".$ls_codasi."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
				$ao_object[$ai_totrows][4]="<input type=text     name=txtfeccon".$ai_totrows." id=txtfeccon".$ai_totrows." value='".$ls_feccon."' class=sin-borde readonly style=text-align:center size=12 maxlength=12>";
				$ao_object[$ai_totrows][5]="<input type=text     name=txtobscon".$ai_totrows." id=txtobscon".$ai_totrows." value='".$ls_obscon."' class=sin-borde readonly style=text-align:left size=60 maxlength=60>";												
				$ao_object[$ai_totrows][6]="<div align='center'><a href=javascript:uf_verdetalle('".$ls_codcon."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>".
										   "<input type=hidden name=txtfechaconta".$ai_totrows." id=txtfechaconta".$ai_totrows." value='".$ld_fechaconta."'>".
										   "<input type=hidden name=txtfechacontaasig".$ai_totrows." id=txtfechacontaasig".$ai_totrows." value='".$ld_fechacontaasig."'>".
										   "<input type=hidden name=txtfechaanula".$ai_totrows." id=txtfechaanula".$ai_totrows." value='".$ld_fechaanula."'>";
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_select_sob_contratos_contabilizar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_sob_anticipos_contabilizar($as_codant,$as_codcon,$as_feccon,$as_fecant,$ai_estatus,&$ao_object,&$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_sob_anticipos_contabilizar
		//		   Access: public (sigesp_mis_p_contabiliza_contrato_sob.php)
		//	    Arguments: as_codant  // Código de Anticipo
		//				   as_codcon  // Código del contrato
		//				   as_feccon  // Fecha de Contrato
		//				   as_fecant  // Fecha de anticipo
		//				   ai_estatus  // estatus por el que se quiere filtrar
		//				   ao_object  // Arreglo de objetos
		//				   ai_totrows  // Total de Filas
		//	      Returns: lb_valido True si se ejecuto la busqueda correctamente
		//	  Description: Funcion que obtiene todas aquellos contratos emitidos
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 30/04/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
        $lb_valido=true;
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_criterio="";
		if(!empty($as_codant))
		{
			$ls_criterio=$ls_criterio." AND sob_anticipo.codant like '%".$as_codant."%'";
		}
		if(!empty($as_codcon))
		{
			$ls_criterio=$ls_criterio." AND sob_anticipo.codcon like '%".$as_codcon."%'";
		}
		if(!empty($as_feccon))
		{
			$as_feccon=$this->io_function->uf_convertirdatetobd($as_feccon);
			$ls_criterio=$ls_criterio." AND sob_contrato.feccon = '".$as_feccon."'";
		}
		if(!empty($as_fecant))
		{
			$as_fecant=$this->io_function->uf_convertirdatetobd($as_fecant);
			$ls_criterio=$ls_criterio." AND sob_anticipo.fecant = '".$as_fecant."'";
		}
		$ls_sql="SELECT sob_anticipo.codcon, sob_anticipo.codant, sob_anticipo.fecant, sob_anticipo.fechaconta, sob_anticipo.fechaanula, ".
				"		sob_anticipo.monto, sob_contrato.fechaconta AS fechacontacontrato, rpc_proveedor.cod_pro".
                "  FROM sob_anticipo, sob_contrato, sob_asignacion, rpc_proveedor  ".
                " WHERE sob_anticipo.codemp='".$ls_codemp."' ".
				"   AND sob_anticipo.estapr = 1 ".
				"   AND sob_anticipo.estspgscg=".$ai_estatus." ".
				"   AND sob_contrato.estspgscg=1 ".
				$ls_criterio.
				"   AND sob_anticipo.codemp = sob_contrato.codemp ".
				"   AND sob_anticipo.codcon = sob_contrato.codcon ".
				"   AND sob_asignacion.codemp=sob_contrato.codemp ".
				"   AND sob_asignacion.codasi=sob_contrato.codasi ".
				"   AND rpc_proveedor.codemp=sob_asignacion.codemp ".
				"   AND rpc_proveedor.cod_pro=sob_asignacion.cod_pro ".
				" ORDER BY sob_anticipo.codcon, sob_anticipo.codant";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_mensajes->message("CLASE->Contabiliza MÉTODO->uf_select_sob_anticipos_contabilizar ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codcon=rtrim($row["codcon"]);
				$ls_codpro=rtrim($row["cod_pro"]);
				$ls_codant=rtrim($row["codant"]);
				$li_monto=number_format($row["monto"],2,",",".");
				$ls_fecant=$this->io_function->uf_formatovalidofecha($row["fecant"]);
				$ls_fecant=$this->io_function->uf_convertirfecmostrar($ls_fecant);
				$ld_fechacontacontrato=$this->io_function->uf_formatovalidofecha($row["fechacontacontrato"]);
				$ld_fechaconta=$this->io_function->uf_formatovalidofecha($row["fechaconta"]);
				$ld_fechaanula=$this->io_function->uf_formatovalidofecha($row["fechaanula"]);
				$ao_object[$ai_totrows][1]="<input type=checkbox name=chksel".$ai_totrows."    id=chksel".$ai_totrows." value=1 style=width:15px;height:15px >";		
				$ao_object[$ai_totrows][2]="<input type=text     name=txtcodant".$ai_totrows." id=txtcodant".$ai_totrows." value='".$ls_codant."' class=sin-borde readonly style=text-align:center size=20 maxlength=20>";
				$ao_object[$ai_totrows][3]="<input type=text     name=txtcodcon".$ai_totrows." id=txtcodcon".$ai_totrows." value='".$ls_codcon."' class=sin-borde readonly style=text-align:center size=20 maxlength=20>";				
				$ao_object[$ai_totrows][4]="<input type=text     name=txtfecant".$ai_totrows." id=txtfecant".$ai_totrows." value='".$ls_fecant."' class=sin-borde readonly style=text-align:center size=20maxlength=20>";
				$ao_object[$ai_totrows][5]="<input type=text     name=txtmonto".$ai_totrows." id=txtmonto".$ai_totrows." value='".$li_monto."' class=sin-borde readonly style=text-align:right size=20 maxlength=20>";												
				$ao_object[$ai_totrows][6]="<div align='center'><a href=javascript:uf_verdetalle('".$ls_codcon."','".$ls_codant."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>".
										   "<input type=hidden name=txtcodpro".$ai_totrows." id=txtcodpro".$ai_totrows." value='".$ls_codpro."'>".
										   "<input type=hidden name=txtfechaconta".$ai_totrows." id=txtfechaconta".$ai_totrows." value='".$ld_fechaconta."'>".
										   "<input type=hidden name=txtfechacontacontrato".$ai_totrows." id=txtfechacontacontrato".$ai_totrows." value='".$ld_fechacontacontrato."'>".
										   "<input type=hidden name=txtfechaanula".$ai_totrows." id=txtfechaanula".$ai_totrows." value='".$ld_fechaanula."'>";
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_select_sob_anticipos_contabilizar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_sob_valuaciones_contabilizar($as_codval,$as_codcon,$as_feccon,$as_fecval,$ai_estatus,&$ao_object,&$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_sob_valuaciones_contabilizar
		//		   Access: public (sigesp_mis_p_contabiliza_contrato_sob.php)
		//	    Arguments: as_codval  // Código de Valuación
		//				   as_codcon  // Código del contrato
		//				   as_feccon  // Fecha de Contrato
		//				   as_fecval  // Fecha de Valuación
		//				   ai_estatus  // estatus por el que se quiere filtrar
		//				   ao_object  // Arreglo de objetos
		//				   ai_totrows  // Total de Filas
		//	      Returns: lb_valido True si se ejecuto la busqueda correctamente
		//	  Description: Funcion que obtiene tas aquellas valuciones
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 30/04/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
        $lb_valido=true;
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_criterio="";
		if(!empty($as_codval))
		{
			$ls_criterio=$ls_criterio." AND sob_valuacion.codval like '%".$as_codval."%'";
		}
		if(!empty($as_codobr))
		{
			$ls_criterio=$ls_criterio." AND sob_valuacion.codcon like '%".$as_codcon."%'";
		}
		if(!empty($as_feccon))
		{
			$as_feccon=$this->io_function->uf_convertirdatetobd($as_feccon);
			$ls_criterio=$ls_criterio." AND sob_contrato.feccon = '".$as_feccon."'";
		}
		if(!empty($as_fecval))
		{
			$as_fecinicon=$this->io_function->uf_convertirdatetobd($as_fecinicon);
			$ls_criterio=$ls_criterio." AND sob_valuacion.fecha = '".$as_fecinicon."'";
		}
		$ls_sql="SELECT sob_valuacion.codcon, sob_valuacion.codval, sob_valuacion.fecha, sob_valuacion.obsval, sob_valuacion.fechaconta, sob_valuacion.fechaanula, ".
				"		sob_contrato.fechaconta as fechacontacontrato ".
                "  FROM sob_valuacion, sob_contrato  ".
                " WHERE sob_valuacion.codemp='".$ls_codemp."' ".
				"   AND sob_valuacion.estapr = 1 ".
				"   AND sob_valuacion.estspgscg=".$ai_estatus." ".
				"   AND sob_contrato.estapr = 1 ".
				"   AND sob_contrato.estspgscg=1 ".
				$ls_criterio.
				"   AND sob_valuacion.codemp = sob_contrato.codemp ".
				"   AND sob_valuacion.codcon = sob_contrato.codcon ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
            $this->io_mensajes->message("CLASE->Contabiliza MÉTODO->uf_select_sob_valuaciones_contabilizar ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codcon=rtrim($row["codcon"]);
				$ls_codval=rtrim($row["codval"]);
				$ls_fecha=$this->io_function->uf_formatovalidofecha($row["fecha"]);
				$ls_fecha=$this->io_function->uf_convertirfecmostrar($ls_fecha);
				$ls_obsval=rtrim($row["obsval"]);
				$ld_fechacontacontrato=$this->io_function->uf_formatovalidofecha($row["fechacontacontrato"]);
				$ld_fechaconta=$this->io_function->uf_formatovalidofecha($row["fechaconta"]);
				$ld_fechaanula=$this->io_function->uf_formatovalidofecha($row["fechaanula"]);
				$ao_object[$ai_totrows][1]="<input type=checkbox name=chksel".$ai_totrows."    id=chksel".$ai_totrows." value=1 style=width:15px;height:15px >";		
				$ao_object[$ai_totrows][2]="<input type=text     name=txtcodcon".$ai_totrows." id=txtcodcon".$ai_totrows." value='".$ls_codcon."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";				
				$ao_object[$ai_totrows][3]="<input type=text     name=txtcodval".$ai_totrows." id=txtcodval".$ai_totrows." value='".$ls_codval."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
				$ao_object[$ai_totrows][4]="<input type=text     name=txtfecha".$ai_totrows." id=txtfecha".$ai_totrows." value='".$ls_fecha."' class=sin-borde readonly style=text-align:center size=12 maxlength=12>";

				$ao_object[$ai_totrows][5]="<input type=text     name=txtobsval".$ai_totrows." id=txtobsval".$ai_totrows." value='".$ls_obsval."' class=sin-borde readonly style=text-align:left size=60 maxlength=60>";												
				$ao_object[$ai_totrows][6]="<div align='center'><a href=javascript:uf_verdetalle('".$ls_codval."','".$ls_codcon."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>".
										   "<input type=hidden name=txtfechaconta".$ai_totrows." id=txtfechaconta".$ai_totrows." value='".$ld_fechaconta."'>".
										   "<input type=hidden name=txtfechacontacontrato".$ai_totrows." id=txtfechacontacontrato".$ai_totrows." value='".$ld_fechacontacontrato."'>".
										   "<input type=hidden name=txtfechaanula".$ai_totrows." id=txtfechaanula".$ai_totrows." value='".$ld_fechaanula."'>";
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_select_sob_valuaciones_contabilizar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_nomina_contabilizar($as_codnom,$as_codperi,$as_codcom,$as_tipcom,&$ao_object,&$ai_totrows,$ai_estatus)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_nomina_contabilizar
		//		   Access: public (sigesp_mis_p_contibiliza_sno.php)
		//	    Arguments: as_codnom  // Código de Nómina por el que se quiere filtrar
		//				   as_codperi  // Código de Período por el que se quiere filtrar
		//				   as_tipcom  // Tipo de Comprobante por el que se quiere filtrar
		//				   ao_object  // Arreglo de objetos
		//				   ai_totrows  // Total de Filas
		//				   ai_estatus  // estatus por el que se quiere filtrar
		//	      Returns: lb_valido True si se ejecuto la busqueda correctamente
		//	  Description: Funcion que obtiene todas aquellos comprobantes de nomina por contabilizar
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
        $lb_valido=true;
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_criterio="";
		if(!empty($as_codnom))
		{
			$ls_criterio=$ls_criterio." AND codnom = '".$as_codnom."'";
		}
		if(!empty($as_codperi))
		{
			$ls_criterio=$ls_criterio." AND codperi = '".$as_codperi."'";
		}
		if(!empty($as_tipcom))
		{
			$ls_criterio=$ls_criterio." AND tipnom = '".$as_tipcom."'";
		}
		if(!empty($as_codcom))
		{
			$ls_criterio=$ls_criterio." AND codcom = '".$as_codcom."'";
		}
		$ls_sql="SELECT DISTINCT codcom, descripcion, MAX(fechaconta) AS fechaconta, MAX(fechaanula) AS fechaanula ".
				"  FROM sno_dt_scg ".
				" WHERE codemp = '".$ls_codemp."' ".
				"   AND estatus = ".$ai_estatus.
				"".$ls_criterio.
				"   AND codcom NOT IN (SELECT codcom FROM sno_dt_spg WHERE codemp = '".$ls_codemp."' )  ".
				"   AND codcom NOT IN (SELECT codcom FROM sno_dt_spi WHERE codemp = '".$ls_codemp."' )  ".
                " GROUP BY codcom, descripcion ".
                " UNION ".
				"SELECT DISTINCT codcom, descripcion, MAX(fechaconta) AS fechaconta, MAX(fechaanula) AS fechaanula ".
				"  FROM sno_dt_spg  ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND estatus=".$ai_estatus.
				"".$ls_criterio.
                " GROUP BY codcom, descripcion ".
                " UNION ".
				"SELECT DISTINCT codcom, descripcion, MAX(fechaconta) AS fechaconta, MAX(fechaanula) AS fechaanula ".
				"  FROM sno_dt_spi  ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND estatus=".$ai_estatus.
				"".$ls_criterio.
                " GROUP BY codcom, descripcion ".
				" ORDER BY codcom ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Contabiliza MÉTODO->uf_select_nomina_contabilizar ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows =$ai_totrows+1;
				$ls_codcom=rtrim($row["codcom"]);
				$ls_descripcion=rtrim($row["descripcion"]);
				$ld_fechaconta=$this->io_function->uf_formatovalidofecha($row["fechaconta"]);
				$ld_fechaanula=$this->io_function->uf_formatovalidofecha($row["fechaanula"]);
				$ao_object[$ai_totrows][1]="<input type=checkbox name=chksel".$ai_totrows." id=chksel".$ai_totrows." value=1 style=width:15px;height:15px >";		
				$ao_object[$ai_totrows][2]="<input type=text name=txtcodcom".$ai_totrows." value='".$ls_codcom."' class=sin-borde style=text-align:center size=20 maxlength=15 readonly>";
				$ao_object[$ai_totrows][3]="<input type=text name=txtdescripcion".$ai_totrows." value='".$ls_descripcion."' title='".$ls_descripcion."' style=text-align:left class=sin-borde size=98 maxlength=95 readonly>";												
				$ao_object[$ai_totrows][4]="<div align='center'><a href=javascript:uf_verdetalle('".$ls_codcom."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>".
										   "<input type=hidden name=txtfechaconta".$ai_totrows." id=txtfechaconta".$ai_totrows." value='".$ld_fechaconta."'>".
										   "<input type=hidden name=txtfechaanula".$ai_totrows." id=txtfechaanula".$ai_totrows." value='".$ld_fechaanula."'>";
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_select_nomina_contabilizar	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_activos_contabilizar($as_mes, $as_ano, $ai_estatus, &$ao_object, &$ai_totrows)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	   Function : uf_select_activos_contabilizar
		//       Access : public
		//   Argumentos : as_mes // Mes de Contabilización
		//  			  as_ano // año de Contabilización
		//				  ao_object  // Arreglo de objetos
		//				  ai_totrows  // Total de Filas
		//				  ai_estatus  // estatus de los movimientos a consultar
		//	    Returns : Arreglo con las depreciaciones sin contabilizar del mes
		//	Description : Método que obtiene las depreciaciones de los activos sin contabilizar
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
        $lb_valido=true;
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_criterio="";
		if(!empty($as_mes))
		{
			if($as_mes!="00")
			{
				$ls_criterio=$ls_criterio." AND SUBSTR(saf_depreciacion.fecdep,6,2) = '".$as_mes."' ";
			}
		}
		if(!empty($as_ano))
		{
			$ls_criterio=$ls_criterio." AND SUBSTR(saf_depreciacion.fecdep,1,4) = '".$as_ano."'";
		}
		$ls_sql="SELECT saf_depreciacion.mondepmen AS monto, SUBSTR(saf_depreciacion.fecdep,1,4) AS anio, ".
				"		SUBSTR(saf_depreciacion.fecdep,6,2) AS mes, saf_dta.estact,  saf_dta.fecdesact,  ".
				"		saf_depreciacion.fechaconta, saf_depreciacion.fechaanula  ".
                " FROM saf_depreciacion, saf_activo, saf_dta ".
                " WHERE saf_depreciacion.codemp = '".$ls_codemp."' ".
				"   AND saf_depreciacion.estcon = ".$ai_estatus.
				"   AND saf_activo.estdepact = 1 ".				
				"  ".$ls_criterio.
				"   AND saf_depreciacion.codemp = saf_activo.codemp ".
				"   AND saf_depreciacion.codact = saf_activo.codact ".
				"   AND saf_depreciacion.codemp = saf_dta.codemp ".
				"   AND saf_depreciacion.codact = saf_dta.codact ".
				"   AND saf_depreciacion.ideact = saf_dta.ideact ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Contabiliza MÉTODO->uf_select_solicitudes_contabilizar ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			$this->dts_solicitud->reset_ds();  
			while($row=$this->io_sql->fetch_row($rs_data))
			{ 
				$ls_mes=rtrim($row["mes"]);
				$ls_ano=rtrim($row["anio"]);
				$li_monto=rtrim($row["monto"]);
				$ls_estact=rtrim($row["estact"]);
				$ls_fecdesact=$this->io_function->uf_formatovalidofecha($row["fecdesact"]);
				$ld_fechaconta=$this->io_function->uf_formatovalidofecha($row["fechaconta"]);
				$ld_fechaanula=$this->io_function->uf_formatovalidofecha($row["fechaanula"]);
				$ls_comprobante=str_pad($ls_mes.$ls_ano,15,"0",0);
				$ls_descripcion="DEPRECIACIÓN DE LOS ACTIVOS FIJOS CORRESPONDIENTES AL AÑO ".$ls_ano." MES DE ".strtoupper($this->io_fecha->uf_load_nombre_mes($ls_mes));
				$li_total=0;
				if($ls_estact=="D")
				{
					if($ls_ano==substr($ls_fecdesact,0,4))
					{
						if($ls_mes==substr($ls_fecdesact,5,2))
						{
							$li_dia=substr($ls_fecdesact,8,2);
							$li_monto=($li_monto/30)*$li_dia;
							$li_total=$li_total+$li_monto;
						}
						if($ls_mes<substr($ls_fecdesact,5,2))
						{
							$li_total=$li_total+$li_monto;
						}
					}
					if($ls_ano<substr($ls_fecdesact,0,4))
					{
						$li_total=$li_total+$li_monto;
					}
				}
				else
				{
					$li_total=$li_total+$li_monto;
				}
				$this->dts_solicitud->insertRow("comprobante",$ls_comprobante);
				$this->dts_solicitud->insertRow("descripcion",$ls_comprobante);
				$this->dts_solicitud->insertRow("monto",$li_total);
				$this->dts_solicitud->insertRow("mes",$ls_mes);
				$this->dts_solicitud->insertRow("anio",$ls_ano);
				$this->dts_solicitud->insertRow("fechaconta",$ld_fechaconta);
				$this->dts_solicitud->insertRow("fechaanula",$ld_fechaanula);
			}
			$this->dts_solicitud->group_by(array('0'=>'comprobante'),array('0'=>'monto'),'monto');
			$li_totrow=$this->dts_solicitud->getRowCount("comprobante");
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ai_totrows=$ai_totrows+1;
				$ls_comprobante = $this->dts_solicitud->data["comprobante"][$li_i];
				$ls_descripcion = $this->dts_solicitud->data["descripcion"][$li_i];
				$li_total = $this->dts_solicitud->data["monto"][$li_i];
				$ls_mes = $this->dts_solicitud->data["mes"][$li_i];
				$ls_ano = $this->dts_solicitud->data["anio"][$li_i];
				$ld_fechaconta = $this->dts_solicitud->data["fechaconta"][$li_i];
				$ld_fechaanula = $this->dts_solicitud->data["fechaanula"][$li_i];
				$li_total=number_format($li_total,2,",",".");
				$ao_object[$ai_totrows][1]="<input type=checkbox name=chksel".$ai_totrows." id=chksel".$ai_totrows." value=1 style=width:15px;height:15px >";		
				$ao_object[$ai_totrows][2]="<input type=text name=txtcomprobante".$ai_totrows." value='".$ls_comprobante."' class=sin-borde readonly style=text-align:left size=20 maxlength=18>";
				$ao_object[$ai_totrows][3]="<input type=text name=txtdescripcion".$ai_totrows." value='".$ls_descripcion."' class=sin-borde readonly style=text-align:left size=80 maxlength=254>";
				$ao_object[$ai_totrows][4]="<input type=text name=txtmonto".$ai_totrows." value='".$li_total."' class=sin-borde readonly style=text-align:right size=20 maxlength=30>";
				$ao_object[$ai_totrows][5]="<div align='center'><a href=javascript:uf_verdetalle('".$ls_mes."','".$ls_ano."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>".
										   "<input type=hidden name=txtmes".$ai_totrows." value='".$ls_mes."'>".
										   "<input type=hidden name=txtano".$ai_totrows." value='".$ls_ano."'>".
										   "<input type=hidden name=txtfechaconta".$ai_totrows." id=txtfechaconta".$ai_totrows." value='".$ld_fechaconta."'>".
										   "<input type=hidden name=txtfechaanula".$ai_totrows." id=txtfechaanula".$ai_totrows." value='".$ld_fechaanula."'>";
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end function uf_select_solicitudes_contabilizar	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_comprobantes_spg($as_comprobante,$as_procede,$ad_fecha,$ai_estatus,&$ao_object,&$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_comprobantes_spg
		//		   Access: public (sigesp_mis_p_contabiliza_mp.php)
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procede  // Procede
		//				   ad_fecha  // Fecha del Comprobante
		//				   ai_estatus  // Estatus de Conatbilización
		//				   ao_object  // Arreglo de objetos
		//				   ai_totrows  // total del Filas
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Funcion que se encarga de retornar los comprobantes de modificaciones 
		//					presupuestarias para su proceso de aprobacion .
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 													Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
		$lb_valido=true;
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_criterio="";
		if($ad_fecha!="")
		{
			$ls_criterio=$ls_criterio." AND fecha ='".$this->io_function->uf_convertirdatetobd($ad_fecha)."'";
		}
		if($as_comprobante!="")
		{
			$ls_criterio=$ls_criterio." AND comprobante like '%".$as_comprobante."%'";
		}
		if($as_procede!="")
		{
			$ls_criterio=$ls_criterio." AND procede like '%SPG".$as_procede."%'";
		}
		else
		{
			$ls_criterio=$ls_criterio." AND procede like '%SPG%'";		
		}
		$ls_sql="SELECT comprobante, fecha, procede, descripcion, fechaconta, fechaanula ". 
				"  FROM sigesp_cmp_md ".
				" WHERE codemp = '".$ls_codemp."' ".
				"   AND tipo_comp = 2 ".
				"   AND estapro = ".$ai_estatus." ".
				$ls_criterio.
			    " ORDER BY fecha, comprobante ";	
		$rs_data = $this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			$lb_valido=false;
            $this->io_mensajes->message("CLASE->Contabiliza MÉTODO->uf_select_comprobantes_spg ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_comprobante=rtrim($row["comprobante"]);
				$ld_fecha=$this->io_function->uf_formatovalidofecha($row["fecha"]);
				$ld_fecha=$this->io_function->uf_convertirfecmostrar($ld_fecha);
				$ls_procede=rtrim($row["procede"]);
				$ls_descripcion=rtrim($row["descripcion"]);				
				$ld_fechaconta=$this->io_function->uf_formatovalidofecha($row["fechaconta"]);
				$ld_fechaanula=$this->io_function->uf_formatovalidofecha($row["fechaanula"]);
				$ao_object[$ai_totrows][1] = "<input type=checkbox name=chksel".$ai_totrows."  id=chksel".$ai_totrows." value=1 >";		
				$ao_object[$ai_totrows][2] = "<input type=text name=txtcomprobante".$ai_totrows." id=txtcomprobante".$ai_totrows."  value='".$ls_comprobante."'      class=sin-borde readonly style=text-align:center size=20 maxlength=15 >";
				$ao_object[$ai_totrows][3] = "<input type=text name=txtfecha".$ai_totrows." id=txtfecha".$ai_totrows."  value='".$ld_fecha."'    class=sin-borde readonly style=text-align:center size=15 maxlength=12 >";
				$ao_object[$ai_totrows][4] = "<input type=text name=txtdescripcion".$ai_totrows." id=txtdescripcion".$ai_totrows." value='".$ls_descripcion."' title='".$ls_descripcion."'    class=sin-borde readonly style=text-align:left size=50 maxlength=250>";			
				$ao_object[$ai_totrows][5] = "<div align='center'><a href=javascript:uf_verdetalle('".$ls_comprobante."','".$ls_procede."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>".
											 "<input name=txtprocede".$ai_totrows." type=hidden id=txtprocede".$ai_totrows." class=sin-borde  value='".$ls_procede."' size=15 maxlength=12>".			
										     "<input type=hidden name=txtfechaconta".$ai_totrows." id=txtfechaconta".$ai_totrows." value='".$ld_fechaconta."'>".
										     "<input type=hidden name=txtfechaanula".$ai_totrows." id=txtfechaanula".$ai_totrows." value='".$ld_fechaanula."'>";
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_comprobantes_spg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_comprobantes_spi($as_comprobante,$as_procede,$ad_fecha,$ai_estatus,&$ao_object,&$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_comprobantes_spi
		//		   Access: public (sigesp_mis_p_contabiliza_mp_spi.php)
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procede  // Procede
		//				   ad_fecha  // Fecha del Comprobante
		//				   ai_estatus  // Estatus de Conatbilización
		//				   ao_object  // Arreglo de objetos
		//				   ai_totrows  // total del Filas
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Funcion que se encarga de retornar los comprobantes de modificaciones 
		//					presupuestarias para su proceso de aprobacion .
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 													Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_criterio="";
		if($ad_fecha!="")
		{
			$ls_criterio=$ls_criterio." AND fecha ='".$this->io_function->uf_convertirdatetobd($ad_fecha)."'";
		}
		if($as_comprobante!="")
		{
			$ls_criterio=$ls_criterio." AND comprobante like '%".$as_comprobante."%'";
		}
		if($as_procede!="")
		{
			$ls_criterio=$ls_criterio." AND procede like '%SPI".$as_procede."%'";
		}
		else
		{
			$ls_criterio=$ls_criterio." AND procede like '%SPI%'";		
		}
		$ls_sql="SELECT comprobante, fecha, procede, descripcion, fechaconta, fechaanula ". 
				"  FROM sigesp_cmp_md ".
				" WHERE codemp = '".$ls_codemp."' ".
				"   AND tipo_comp = 2 ".
				"   AND estapro = ".$ai_estatus." ".
				$ls_criterio.
			    " ORDER BY fecha, comprobante ";	
		$rs_data = $this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			$lb_valido=false;
            $this->io_mensajes->message("CLASE->Contabiliza MÉTODO->uf_select_comprobantes_spi ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_comprobante=rtrim($row["comprobante"]);
				$ld_fecha=$this->io_function->uf_formatovalidofecha($row["fecha"]);
				$ld_fecha=$this->io_function->uf_convertirfecmostrar($ld_fecha);
				$ls_procede=rtrim($row["procede"]);
				$ls_descripcion=rtrim($row["descripcion"]);				
				$ld_fechaconta=$this->io_function->uf_formatovalidofecha($row["fechaconta"]);
				$ld_fechaanula=$this->io_function->uf_formatovalidofecha($row["fechaanula"]);
				$ao_object[$ai_totrows][1] = "<input type=checkbox name=chksel".$ai_totrows."  id=chksel".$ai_totrows." value=1 >";		
				$ao_object[$ai_totrows][2] = "<input type=text name=txtcomprobante".$ai_totrows." id=txtcomprobante".$ai_totrows."  value='".$ls_comprobante."'      class=sin-borde readonly style=text-align:center size=20 maxlength=15 >";
				$ao_object[$ai_totrows][3] = "<input type=text name=txtfecha".$ai_totrows." id=txtfecha".$ai_totrows."  value='".$ld_fecha."'    class=sin-borde readonly style=text-align:center size=15 maxlength=12 >";
				$ao_object[$ai_totrows][4] = "<input type=text name=txtdescripcion".$ai_totrows." id=txtdescripcion".$ai_totrows." value='".$ls_descripcion."' title='".$ls_descripcion."'    class=sin-borde readonly style=text-align:left size=50 maxlength=250>";			
				$ao_object[$ai_totrows][5] = "<div align='center'><a href=javascript:uf_verdetalle('".$ls_comprobante."','".$ls_procede."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>".
											 "<input name=txtprocede".$ai_totrows." type=hidden id=txtprocede".$ai_totrows." class=sin-borde  value='".$ls_procede."' size=15 maxlength=12>".			
										     "<input type=hidden name=txtfechaconta".$ai_totrows." id=txtfechaconta".$ai_totrows." value='".$ld_fechaconta."'>".
										     "<input type=hidden name=txtfechaanula".$ai_totrows." id=txtfechaanula".$ai_totrows." value='".$ld_fechaanula."'>";
			}
			$this->io_sql->free_result($rs_data);
		}		
	}// end function uf_select_comprobantes_spi
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_aprobacioncreditos(&$ao_object,&$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_aprobacioncreditos
		//		   Access: public (sigesp_mis_p_aprobacioncontrolcreditos.php)
		//	    Arguments: ao_object  // Arreglo de objetos
		//				   ai_totrows  // total del Filas
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Funcion que se encarga de retornar los comprobantes de Créditos para su aprobación
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 													Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
		$ls_path="../scc/II/pendientes/";
		$la_archivos=$this->io_xml->uf_load_archivos($ls_path);
		$li_totalarchivos=count($la_archivos["filnam"]);
		if($la_archivos=="")
		{
			$li_totalarchivos=0;
		}
		for($li_i=1;$li_i<=$li_totalarchivos;$li_i++)
		{
			$ls_archivo=$la_archivos["filnam"][$li_i];
			$la_data=$this->io_xml->uf_cargar_sigesp_cmp($ls_path.$ls_archivo);
			$li_total=count($la_data);
			for($i=1;$i<=$li_total;$i++)
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codemp=rtrim($la_data[$i]["codemp"]);
				$ls_procede=rtrim($la_data[$i]["procede"]);
				$ls_comprobante=rtrim($la_data[$i]["comprobante"]);
				$ad_fecha=$this->io_function->uf_formatovalidofecha($la_data[$i]["fecha"]);
				$ld_fecha=$this->io_function->uf_convertirfecmostrar($ad_fecha);
				$ls_codban=rtrim($la_data[$i]["codban"]);
				$ls_ctaban=rtrim($la_data[$i]["ctaban"]);
				$ls_descripcion=rtrim($la_data[$i]["descripcion"]);				
				$ls_tipo_comp=rtrim($la_data[$i]["tipo_comp"]);				
				$ls_tipo_destino=rtrim($la_data[$i]["tipo_destino"]);				
				$ls_cod_pro=rtrim($la_data[$i]["cod_pro"]);				
				$ls_ced_bene=rtrim($la_data[$i]["ced_bene"]);				
	
				$ao_object[$ai_totrows][1] = "<input type=checkbox name=chksel".$ai_totrows."  id=chksel".$ai_totrows." value=1 >";		
				$ao_object[$ai_totrows][2] = "<input type=text name=txtcomprobante".$ai_totrows." id=txtcomprobante".$ai_totrows."  value='".$ls_comprobante."'      class=sin-borde readonly style=text-align:center size=20 maxlength=15 >";
				$ao_object[$ai_totrows][3] = "<input type=text name=txtfecha".$ai_totrows." id=txtfecha".$ai_totrows."  value='".$ld_fecha."'    class=sin-borde readonly style=text-align:center size=15 maxlength=12 >";
				$ao_object[$ai_totrows][4] = "<input type=text name=txtdescripcion".$ai_totrows." id=txtdescripcion".$ai_totrows." value='".$ls_descripcion."' title='".$ls_descripcion."'    class=sin-borde readonly style=text-align:left size=50 maxlength=250>";			
				$ao_object[$ai_totrows][5] = "<div align='center'><a href=javascript:uf_verdetalle('".$ls_path.$ls_archivo."','".$ls_codemp."','".$ls_procede."','".$ls_comprobante."','".$ad_fecha."','".$ls_codban."','".$ls_ctaban."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>".
											 "<input type=hidden name=txtcodemp".$ai_totrows." id=txtcodemp".$ai_totrows." class=sin-borde  value='".$ls_codemp."' size=15 maxlength=12>".			
											 "<input type=hidden name=txtprocede".$ai_totrows." id=txtprocede".$ai_totrows." class=sin-borde  value='".$ls_procede."' size=15 maxlength=12>".			
											 "<input type=hidden name=txtcodban".$ai_totrows." id=txtcodban".$ai_totrows." value='".$ls_codban."'>".
											 "<input type=hidden name=txtctaban".$ai_totrows." id=txtctaban".$ai_totrows." value='".$ls_ctaban."'>".
											 "<input type=hidden name=txtdescripcion".$ai_totrows." id=txtdescripcion".$ai_totrows." value='".$ls_descripcion."'>".
											 "<input type=hidden name=txttipocomp".$ai_totrows." id=txttipocomp".$ai_totrows." value='".$ls_tipo_comp."'>".
											 "<input type=hidden name=txttipodestino".$ai_totrows." id=txttipodestino".$ai_totrows." value='".$ls_tipo_destino."'>".
											 "<input type=hidden name=txtcodpro".$ai_totrows." id=txtcodpro".$ai_totrows." value='".$ls_cod_pro."'>".
											 "<input type=hidden name=txtcedbene".$ai_totrows." id=txtcedbene".$ai_totrows." value='".$ls_ced_bene."'>".
											 "<input type=hidden name=txtarchivo".$ai_totrows." id=txtarchivo".$ai_totrows." value='".$ls_path.$ls_archivo."'>";
			}


		}
	}// end function uf_select_aprobacioncreditos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cuentasporcobrar(&$ao_object,&$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cuentasporcobrar
		//		   Access: public (sigesp_mis_p_cuentasporcobrar.php)
		//	    Arguments: ao_object  // Arreglo de objetos
		//				   ai_totrows  // total del Filas
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Funcion que se encarga de retornar los comprobantes de cuentas por cobrar
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 													Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
		$ls_path="../scc/cxc/I/pendientes/";
		$la_archivos=$this->io_xml->uf_load_archivos($ls_path);
		$li_totalarchivos=count($la_archivos["filnam"]);
		if($la_archivos=="")
		{
			$li_totalarchivos=0;
		}
		for($li_i=1;$li_i<=$li_totalarchivos;$li_i++)
		{
			$ls_archivo=$la_archivos["filnam"][$li_i];
			$la_data=$this->io_xml->uf_cargar_sigesp_cmp($ls_path.$ls_archivo);
			$li_total=count($la_data);
			for($i=1;$i<=$li_total;$i++)
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codemp=rtrim($la_data[$i]["codemp"]);
				$ls_procede=rtrim($la_data[$i]["procede"]);
				$ls_comprobante=rtrim($la_data[$i]["comprobante"]);
				$ad_fecha=$this->io_function->uf_formatovalidofecha($la_data[$i]["fecha"]);
				$ld_fecha=$this->io_function->uf_convertirfecmostrar($ad_fecha);
				$ls_codban=rtrim($la_data[$i]["codban"]);
				$ls_ctaban=rtrim($la_data[$i]["ctaban"]);
				$ls_descripcion=rtrim($la_data[$i]["descripcion"]);				
				$ls_tipo_comp=rtrim($la_data[$i]["tipo_comp"]);				
				$ls_tipo_destino=rtrim($la_data[$i]["tipo_destino"]);				
				$ls_cod_pro=rtrim($la_data[$i]["cod_pro"]);				
				$ls_ced_bene=rtrim($la_data[$i]["ced_bene"]);				
	
				$ao_object[$ai_totrows][1] = "<input type=checkbox name=chksel".$ai_totrows."  id=chksel".$ai_totrows." value=1 >";		
				$ao_object[$ai_totrows][2] = "<input type=text name=txtcomprobante".$ai_totrows." id=txtcomprobante".$ai_totrows."  value='".$ls_comprobante."'      class=sin-borde readonly style=text-align:center size=20 maxlength=15 >";
				$ao_object[$ai_totrows][3] = "<input type=text name=txtfecha".$ai_totrows." id=txtfecha".$ai_totrows."  value='".$ld_fecha."'    class=sin-borde readonly style=text-align:center size=15 maxlength=12 >";
				$ao_object[$ai_totrows][4] = "<input type=text name=txtdescripcion".$ai_totrows." id=txtdescripcion".$ai_totrows." value='".$ls_descripcion."' title='".$ls_descripcion."'    class=sin-borde readonly style=text-align:left size=50 maxlength=250>";			
				$ao_object[$ai_totrows][5] = "<div align='center'><a href=javascript:uf_verdetalle('".$ls_path.$ls_archivo."','".$ls_codemp."','".$ls_procede."','".$ls_comprobante."','".$ad_fecha."','".$ls_codban."','".$ls_ctaban."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>".
											 "<input type=hidden name=txtcodemp".$ai_totrows." id=txtcodemp".$ai_totrows." class=sin-borde  value='".$ls_codemp."' size=15 maxlength=12>".			
											 "<input type=hidden name=txtprocede".$ai_totrows." id=txtprocede".$ai_totrows." class=sin-borde  value='".$ls_procede."' size=15 maxlength=12>".			
											 "<input type=hidden name=txtcodban".$ai_totrows." id=txtcodban".$ai_totrows." value='".$ls_codban."'>".
											 "<input type=hidden name=txtctaban".$ai_totrows." id=txtctaban".$ai_totrows." value='".$ls_ctaban."'>".
											 "<input type=hidden name=txtdescripcion".$ai_totrows." id=txtdescripcion".$ai_totrows." value='".$ls_descripcion."'>".
											 "<input type=hidden name=txttipocomp".$ai_totrows." id=txttipocomp".$ai_totrows." value='".$ls_tipo_comp."'>".
											 "<input type=hidden name=txttipodestino".$ai_totrows." id=txttipodestino".$ai_totrows." value='".$ls_tipo_destino."'>".
											 "<input type=hidden name=txtcodpro".$ai_totrows." id=txtcodpro".$ai_totrows." value='".$ls_cod_pro."'>".
											 "<input type=hidden name=txtcedbene".$ai_totrows." id=txtcedbene".$ai_totrows." value='".$ls_ced_bene."'>".
											 "<input type=hidden name=txtarchivo".$ai_totrows." id=txtarchivo".$ai_totrows." value='".$ls_path.$ls_archivo."'>";
			}


		}
	}// end function uf_select_cuentasporcobrar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_comprobantes_despacho($as_comprobante,$ad_fecha,$ai_estatus,&$ao_object,&$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_comprobantes_despacho
		//		   Access: public
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   ad_fecha  // Fecha del Comprobante
		//				   ao_object  // Arreglo de objetos
		//				   ai_totrows  // total del Filas
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Funcion que se encarga de retornar los comprobantes de despachos a ser contabilizados
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: 													Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_criterio="";
		if($ad_fecha!="")
		{
			$ls_criterio=$ls_criterio." AND siv_despacho.fecdes ='".$this->io_function->uf_convertirdatetobd($ad_fecha)."'";
		}
		if($as_comprobante!="")
		{
			$ls_criterio=$ls_criterio." AND siv_despacho.numorddes like '%".$as_comprobante."%'";
		}
		$ls_sql="SELECT siv_despacho.codemp, numorddes, MAX(obsdes) as obsdes,MAX(fecdes) as fecdes,MAX(siv_dt_scg.fechaconta) as fechaconta,MAX(siv_dt_scg.fechaanula) as fechaanula". 
				"  FROM siv_despacho,siv_dt_scg ".
				" WHERE siv_despacho.codemp = '".$ls_codemp."' ".
				"   AND siv_dt_scg.estint = '".$ai_estatus."' ".
				"   AND siv_despacho.codemp=siv_dt_scg.codemp ".
				"   AND siv_despacho.numorddes=siv_dt_scg.codcmp ".
				$ls_criterio.
				" GROUP BY siv_despacho.codemp, siv_despacho.numorddes".
			    " ORDER BY numorddes  ";
		$rs_data = $this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
            $this->io_mensajes->message("CLASE->Contabiliza MÉTODO->uf_select_comprobantes_despacho ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_comprobante=rtrim($row["numorddes"]);
				$ld_fecha=$this->io_function->uf_convertirfecmostrar($row["fecdes"]);
				$ls_descripcion=rtrim($row["obsdes"]);				
				$ld_fechaconta=rtrim($row["fechaconta"]);
				$ld_fechaanula=rtrim($row["fechaanula"]);
				$ls_procede="SIVCND";
				$ao_object[$ai_totrows][1] = "<input type=checkbox name=chksel".$ai_totrows."  id=chksel".$ai_totrows." value=1 >";		
				$ao_object[$ai_totrows][2] = "<input type=text name=txtcomprobante".$ai_totrows." id=txtcomprobante".$ai_totrows."  value='".$ls_comprobante."'      class=sin-borde readonly style=text-align:center size=20 maxlength=15 >";
				$ao_object[$ai_totrows][3] = "<input type=text name=txtfecha".$ai_totrows." id=txtfecha".$ai_totrows."  value='".$ld_fecha."'    class=sin-borde readonly style=text-align:center size=15 maxlength=12 >";
				$ao_object[$ai_totrows][4] = "<input type=text name=txtdescripcion".$ai_totrows." id=txtdescripcion".$ai_totrows." value='".$ls_descripcion."' title='".$ls_descripcion."'    class=sin-borde readonly style=text-align:left size=50 maxlength=250>";			
				$ao_object[$ai_totrows][5] = "<div align='center'><a href=javascript:uf_verdetalle('".$ls_comprobante."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>".
										     "<input type=hidden name=txtfechaconta".$ai_totrows." id=txtfechaconta".$ai_totrows." value='".$ld_fechaconta."'>".
										     "<input type=hidden name=txtfechaanula".$ai_totrows." id=txtfechaanula".$ai_totrows." value='".$ld_fechaanula."'>";
			}
			$this->io_sql->free_result($rs_data);
		}		
	}// end function uf_select_comprobantes_despacho
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_comprobantes_sfc_cxc($as_comprobante,$as_procede,$ad_fecha,$ai_estatus,&$ao_object,&$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_comprobantes_sfc_cxc
		//		   Access: public (sigesp_mis_p_contabiliza_sfc_cxc.php)
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procede  // Procede
		//				   ad_fecha  // Fecha del Comprobante
		//				   ai_estatus  // Estatus de Conatbilización
		//				   ao_object  // Arreglo de objetos
		//				   ai_totrows  // total del Filas
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Funcion que se encarga de retornar los comprobantes de modificaciones 
		//					presupuestarias para su proceso de aprobacion .
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 													Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_criterio="";
		if($ad_fecha!="")
		{
			$ls_criterio=$ls_criterio." AND fecha ='".$this->io_function->uf_convertirdatetobd($ad_fecha)."'";
		}
		if($as_comprobante!="")
		{
			$ls_criterio=$ls_criterio." AND comprobante like '%".$as_comprobante."%'";
		}
		if($as_procede!="")
		{
			$ls_criterio=$ls_criterio." AND procede like '%".$as_procede."%'";
		}
		else
		{
			$ls_criterio=$ls_criterio." AND procede like '%%'";		
		}
		$ls_sql="SELECT comprobante, fecha, procede, MAX(fechaconta) as fechaconta, MAX(fechaanula) as fechaanula ". 
				"  FROM mis_sigesp_cxc ".
				" WHERE codemp = '".$ls_codemp."' ".
				"   AND estint = ".$ai_estatus." ".
				$ls_criterio.
				" GROUP BY procede, comprobante, fecha".
			    " ORDER BY fecha, comprobante ";
		$rs_data = $this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
            $this->io_mensajes->message("CLASE->Contabiliza MÉTODO->uf_select_comprobantes_sfc_cxc ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_comprobante=rtrim($row["comprobante"]);
				$ld_fecha=$this->io_function->uf_formatovalidofecha($row["fecha"]);
				$ld_fecha=$this->io_function->uf_convertirfecmostrar($ld_fecha);
				$ls_procede=rtrim($row["procede"]);
				switch($ls_procede)
				{
					case "SFCFAC":
						$ls_descripcion="MOVIMIENTO DE CUENTAS POR COBRAR - INTEGRACIÓN MÓDULO FACTURACIÓN Y COBRANZAS ".$ls_comprobante."-".$ld_fecha;						
					break;
					
					case "SFCDED":
						$ls_descripcion="MOVIMIENTO DE DEDUCCIONES - INTEGRACIÓN MÓDULO FACTURACIÓN Y COBRANZAS ".$ls_comprobante."-".$ld_fecha;						
					break;
				}
				$ld_fechaconta=$this->io_function->uf_formatovalidofecha($row["fechaconta"]);
				$ld_fechaanula=$this->io_function->uf_formatovalidofecha($row["fechaanula"]);
				$ao_object[$ai_totrows][1] = "<input type=checkbox name=chksel".$ai_totrows."  id=chksel".$ai_totrows." value=1 >";		
				$ao_object[$ai_totrows][2] = "<input type=text name=txtcomprobante".$ai_totrows." id=txtcomprobante".$ai_totrows."  value='".$ls_comprobante."'      class=sin-borde readonly style=text-align:center size=20 maxlength=15 >";
				$ao_object[$ai_totrows][3] = "<input type=text name=txtfecha".$ai_totrows." id=txtfecha".$ai_totrows."  value='".$ld_fecha."'    class=sin-borde readonly style=text-align:center size=15 maxlength=12 >";
				$ao_object[$ai_totrows][4] = "<input type=text name=txtdescripcion".$ai_totrows." id=txtdescripcion".$ai_totrows." value='".$ls_descripcion."' title='".$ls_descripcion."'    class=sin-borde readonly style=text-align:left size=50 maxlength=250>";			
				$ao_object[$ai_totrows][5] = "<div align='center'><a href=javascript:uf_verdetalle('".$ls_comprobante."','".$ls_procede."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>".
											 "<input name=txtprocede".$ai_totrows." type=hidden id=txtprocede".$ai_totrows." class=sin-borde  value='".$ls_procede."' size=15 maxlength=12>".			
										     "<input type=hidden name=txtfechaconta".$ai_totrows." id=txtfechaconta".$ai_totrows." value='".$ld_fechaconta."'>".
										     "<input type=hidden name=txtfechaanula".$ai_totrows." id=txtfechaanula".$ai_totrows." value='".$ld_fechaanula."'>";
			}
			$this->io_sql->free_result($rs_data);
		}		
	}// end function uf_select_comprobantes_sfc_cxc
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_comprobantes_sfc_nc($as_comprobante,$as_procede,$ad_fecha,$ai_estatus,&$ao_object,&$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_comprobantes_sfc_nc
		//		   Access: public (sigesp_mis_p_contabiliza_sfc_nc.php)
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procede  // Procede
		//				   ad_fecha  // Fecha del Comprobante
		//				   ai_estatus  // Estatus de Conatbilización
		//				   ao_object  // Arreglo de objetos
		//				   ai_totrows  // total del Filas
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Funcion que se encarga de retornar los comprobantes de modificaciones 
		//					presupuestarias para su proceso de aprobacion .
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 													Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_criterio="";
		if($ad_fecha!="")
		{
			$ls_criterio=$ls_criterio." AND fecha ='".$this->io_function->uf_convertirdatetobd($ad_fecha)."'";
		}
		if($as_comprobante!="")
		{
			$ls_criterio=$ls_criterio." AND comprobante like '%".$as_comprobante."%'";
		}
		if($as_procede!="")
		{
			$ls_criterio=$ls_criterio." AND procede like '%".$as_procede."%'";
		}
		else
		{
			$ls_criterio=$ls_criterio." AND procede like '%%'";		
		}
		$ls_sql="SELECT comprobante, fecha, procede, MAX(fechaconta) as fechaconta, MAX(fechaanula) as fechaanula ". 
				"  FROM mis_sigesp_nc ".
				" WHERE codemp = '".$ls_codemp."' ".
				"   AND estint = ".$ai_estatus." ".
				$ls_criterio.
				" GROUP BY procede, comprobante, fecha".
			    " ORDER BY fecha, comprobante ";
		$rs_data = $this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
            $this->io_mensajes->message("CLASE->Contabiliza MÉTODO->uf_select_comprobantes_sfc_nc ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_comprobante=rtrim($row["comprobante"]);
				$ld_fecha=$this->io_function->uf_formatovalidofecha($row["fecha"]);
				$ld_fecha=$this->io_function->uf_convertirfecmostrar($ld_fecha);
				$ls_procede=rtrim($row["procede"]);
				$ls_descripcion="MOVIMIENTO DE NOTAS DE CRÉDITO - INTEGRACIÓN MÓDULO FACTURACIÓN Y COBRANZAS ".$ls_comprobante."-".$ld_fecha;						
				$ld_fechaconta=$this->io_function->uf_formatovalidofecha($row["fechaconta"]);
				$ld_fechaanula=$this->io_function->uf_formatovalidofecha($row["fechaanula"]);
				$ao_object[$ai_totrows][1] = "<input type=checkbox name=chksel".$ai_totrows."  id=chksel".$ai_totrows." value=1 >";		
				$ao_object[$ai_totrows][2] = "<input type=text name=txtcomprobante".$ai_totrows." id=txtcomprobante".$ai_totrows."  value='".$ls_comprobante."'      class=sin-borde readonly style=text-align:center size=20 maxlength=15 >";
				$ao_object[$ai_totrows][3] = "<input type=text name=txtfecha".$ai_totrows." id=txtfecha".$ai_totrows."  value='".$ld_fecha."'    class=sin-borde readonly style=text-align:center size=15 maxlength=12 >";
				$ao_object[$ai_totrows][4] = "<input type=text name=txtdescripcion".$ai_totrows." id=txtdescripcion".$ai_totrows." value='".$ls_descripcion."' title='".$ls_descripcion."'    class=sin-borde readonly style=text-align:left size=50 maxlength=250>";			
				$ao_object[$ai_totrows][5] = "<div align='center'><a href=javascript:uf_verdetalle('".$ls_comprobante."','".$ls_procede."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>".
											 "<input name=txtprocede".$ai_totrows." type=hidden id=txtprocede".$ai_totrows." class=sin-borde  value='".$ls_procede."' size=15 maxlength=12>".			
										     "<input type=hidden name=txtfechaconta".$ai_totrows." id=txtfechaconta".$ai_totrows." value='".$ld_fechaconta."'>".
										     "<input type=hidden name=txtfechaanula".$ai_totrows." id=txtfechaanula".$ai_totrows." value='".$ld_fechaanula."'>";
			}
			$this->io_sql->free_result($rs_data);
		}		
	}// end function uf_select_comprobantes_sfc_nc
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_comprobantes_sfc_pagos($as_comprobante,$as_procede,$ad_fecha,$ai_estatus,&$ao_object,&$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_comprobantes_sfc_pagos
		//		   Access: public (sigesp_mis_p_contabiliza_sfc_pagos.php)
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procede  // Procede
		//				   ad_fecha  // Fecha del Comprobante
		//				   ai_estatus  // Estatus de Conatbilización
		//				   ao_object  // Arreglo de objetos
		//				   ai_totrows  // total del Filas
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Funcion que se encarga de retornar los comprobantes de pago del sistema de facturación 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: 													Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
		$ls_codemp=$this->dts_empresa["codemp"];
		$ls_criterio="";
		if($ad_fecha!="")
		{
			$ls_criterio=$ls_criterio." AND fecha ='".$this->io_function->uf_convertirdatetobd($ad_fecha)."'";
		}
		if($as_comprobante!="")
		{
			$ls_criterio=$ls_criterio." AND comprobante like '%".$as_comprobante."%'";
		}
		$ls_sql="SELECT comprobante, fecdep, procede, codban, ctaban, MAX(descripcion) as descripcion, MAX(fechaconta) as fechaconta, MAX(fechaanula) as fechaanula ". 
				"  FROM mis_sigesp_banco ".
				" WHERE codemp = '".$ls_codemp."' ".
				"   AND procede = '".$as_procede."' ".
				"   AND estint = ".$ai_estatus." ".
				$ls_criterio.
				" GROUP BY comprobante, procede, fecdep, codban, ctaban ".
			    " ORDER BY fecdep, comprobante ";
		$rs_data = $this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
            $this->io_mensajes->message("CLASE->Contabiliza MÉTODO->uf_select_comprobantes_sfc_pagos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		}
		else
		{
			while(!$rs_data->EOF)
			{
				$ai_totrows=$ai_totrows+1;
				$ls_comprobante=rtrim($rs_data->fields["comprobante"]);
				$ld_fechaant=$this->io_function->uf_formatovalidofecha($rs_data->fields["fecdep"]);
				$ld_fecha=$this->io_function->uf_convertirfecmostrar($ld_fechaant);
				$ls_procede=rtrim($rs_data->fields["procede"]);
				$ls_codban=rtrim($rs_data->fields["codban"]);
				$ls_ctaban=rtrim($rs_data->fields["ctaban"]);
				$ls_banco=rtrim($rs_data->fields["codban"]."-".$rs_data->fields["ctaban"]);
				$ls_descripcion="MOVIMIENTO DE PAGO - INTEGRACIÓN MÓDULO FACTURACIÓN Y COBRANZAS ".$ls_comprobante."-".$ld_fecha."-".$ls_codban."-".$ls_ctaban;
				$ld_fechaconta=$this->io_function->uf_formatovalidofecha($rs_data->fields["fechaconta"]);
				$ld_fechaanula=$this->io_function->uf_formatovalidofecha($rs_data->fields["fechaanula"]);
				$ao_object[$ai_totrows][1] = "<input type=checkbox name=chksel".$ai_totrows."  id=chksel".$ai_totrows." value=1 >";		
				$ao_object[$ai_totrows][2] = "<input type=text name=txtcomprobante".$ai_totrows." id=txtcomprobante".$ai_totrows."  value='".$ls_comprobante."'      class=sin-borde readonly style=text-align:center size=17 maxlength=15 >";
				$ao_object[$ai_totrows][3] = "<input type=text name=txtfecha".$ai_totrows." id=txtfecha".$ai_totrows."  value='".$ld_fecha."'    class=sin-borde readonly style=text-align:center size=15 maxlength=10 >";
				$ao_object[$ai_totrows][4] = "<input type=text name=txtbanco".$ai_totrows." id=txtbanco".$ai_totrows."  value='".$ls_banco."'    class=sin-borde readonly style=text-align:center size=25 maxlength=25 >";
				$ao_object[$ai_totrows][5] = "<input type=text name=txtdescripcion".$ai_totrows." id=txtdescripcion".$ai_totrows." value='".$ls_descripcion."' title='".$ls_descripcion."'    class=sin-borde readonly style=text-align:left size=50 maxlength=250>";			
				$ao_object[$ai_totrows][6] = "<div align='center'><a href=javascript:uf_verdetalle('".$ls_comprobante."','".$ls_procede."','".$ld_fechaant."','".$ls_codban."','".$ls_ctaban."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>".
											 "<input name=txtprocede".$ai_totrows." type=hidden id=txtprocede".$ai_totrows." class=sin-borde  value='".$ls_procede."'>".			
											 "<input name=txtcodban".$ai_totrows." type=hidden id=txtcodban".$ai_totrows." class=sin-borde  value='".$ls_codban."'>".			
											 "<input name=txtctaban".$ai_totrows." type=hidden id=txtctaban".$ai_totrows." class=sin-borde  value='".$ls_ctaban."'>".			
										     "<input type=hidden name=txtfechaconta".$ai_totrows." id=txtfechaconta".$ai_totrows." value='".$ld_fechaconta."'>".
										     "<input type=hidden name=txtfechaanula".$ai_totrows." id=txtfechaanula".$ai_totrows." value='".$ld_fechaanula."'>";
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}		
	}// end function uf_select_comprobantes_sfc_pagos
	//-----------------------------------------------------------------------------------------------------------------------------------

}
?>