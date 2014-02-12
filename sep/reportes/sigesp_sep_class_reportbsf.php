<?php
class sigesp_sep_class_reportbsf
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sep_class_reportbsf()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sep_class_report
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno /Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan 
		// Fecha Creación: 11/03/2007 			Fecha Modificacion: 14/08/2007 (Reconversion Monetaria)					
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$this->io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($this->io_conexion);	
		$this->DS=new class_datastore();
		$this->ds_detalle=new class_datastore();
		$this->ds_cargos=new class_datastore();
		$this->ds_cuentas=new class_datastore();
		require_once("../../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
		$this->ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
		$this->ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
		$this->ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
		$this->ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
	}// end function sigesp_sep_class_report
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudes($as_numsoldes,$as_numsolhas,$as_tipproben,$as_codprobendes,$as_codprobenhas,$ad_fegregdes,
								   $ad_fegreghas,$as_codunides,$as_codunihas,$as_tipsol,$ai_registrada,$ai_emitida,
								   $ai_contabilizada,$ai_procesada,$ai_anulada,$ai_despachada,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitudes
		//         Access: public (sigesp_sep_rpp_solicitud)  
		//	    Arguments: as_numsoldes     // Numero de solicitud de inicio del parametro de Busqueda
		//	  			   as_numsolhas     // Numero de solicitud de fin del parametro de Busqueda
		//	  			   as_tipproben     // Indica si es proveedor o beneficiario
		//	  			   as_codprobendes  // Código del proveedor/beneficiario de inicio del parametro de Busqueda
		//	  			   as_codprobenhas  // Código del proveedor/beneficiario de fin del parametro de Busqueda
		//	  			   ad_fegregdes     // Fecha de registgro de la solicitud de inicio del parametro de Busqueda
		//	  			   ad_fegregdes     // Fecha de registgro de la solicitud de fin del parametro de Busqueda
		//	  			   as_codunides     // Codigo de unidad ejecutora de inicio del parametro de Busqueda
		//	  			   as_codunihas     // Codigo de unidad ejecutora de fin del parametro de Busqueda
		//	  			   as_tipsol        // Indica el tipo de solicitud (Bienes, Servicios, Conceptos)
		//	  			   ai_registrada    // Indica si se desea filtrar por este estatus de solicitud
		//	  			   ai_emitida       // Indica si se desea filtrar por este estatus de solicitud
		//	  			   ai_registrada    // Indica si se desea filtrar por este estatus de solicitud
		//	  			   ai_contabilizada // Indica si se desea filtrar por este estatus de solicitud
		//	  			   ai_procesada     // Indica si se desea filtrar por este estatus de solicitud
		//	  			   ai_anulada       // Indica si se desea filtrar por este estatus de solicitud
		//	  			   ai_despachada    // Indica si se desea filtrar por este estatus de solicitud
		//	  			   as_orden         // Orden a mostrar en el reporte		  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las solicitudes 
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan 
		// Fecha Creación: 11/03/2007 			Fecha Modificacion: 14/08/2007 (Reconversion Monetaria)					
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_numsoldes))
		{
			$ls_criterio=$ls_criterio. "  AND sep_solicitud.numsol>='".$as_numsoldes."'";
		}
		if(!empty($as_numsolhas))
		{
			$ls_criterio=$ls_criterio. "  AND sep_solicitud.numsol<='".$as_numsolhas."'";
		}
		if(!empty($as_tipproben))
		{
			$ls_criterio= $ls_criterio."   AND sep_solicitud.tipo_destino='".$as_tipproben."'";
		}
		if(!empty($as_codprobendes))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND sep_solicitud.cod_pro>='".$as_codprobendes."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND sep_solicitud.ced_bene>='".$as_codprobendes."'";
			}
		}
		if(!empty($as_codprobenhas))
		{
			if($as_tipproben=="P")
			{
				$ls_criterio= $ls_criterio."   AND sep_solicitud.cod_pro<='".$as_codprobenhas."'";
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND sep_solicitud.ced_bene<='".$as_codprobenhas."'";
			}
		}
		if(!empty($ad_fegregdes))
		{
			$ad_fegregdes=$this->io_funciones->uf_convertirdatetobd($ad_fegregdes);
			$ls_criterio=$ls_criterio. "  AND sep_solicitud.fecregsol>='".$ad_fegregdes."'";
		}
		if(!empty($ad_fegreghas))
		{
			$ad_fegreghas=$this->io_funciones->uf_convertirdatetobd($ad_fegreghas);
			$ls_criterio=$ls_criterio. "  AND sep_solicitud.fecregsol<='".$ad_fegreghas."'";
		}
		if(!empty($as_codunides))
		{
			$ls_criterio=$ls_criterio. "  AND sep_solicitud.coduniadm>='".$as_codunides."'";
		}
		if(!empty($as_codunihas))
		{
			$ls_criterio=$ls_criterio. "  AND sep_solicitud.coduniadm<='".$as_codunihas."'";
		}
		if(!empty($as_tipsol))
		{
			$ls_criterio=$ls_criterio. " AND sep_solicitud.codtipsol=sep_tiposolicitud.codtipsol".
									   " AND sep_tiposolicitud.modsep='".$as_tipsol."'";
		}
		if(($ai_registrada==1)||($ai_emitida==1)||($ai_contabilizada==1)||($ai_procesada==1)||($ai_anulada==1)||($ai_despachada==1))
		{
			$lb_anterior=false;
			if($ai_registrada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (sep_solicitud.estsol='R'";
					$lb_anterior=true;
				}
			}
			if($ai_emitida==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (sep_solicitud.estsol='E'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR sep_solicitud.estsol='E'";
				}
			}
			if($ai_contabilizada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (sep_solicitud.estsol='C'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR sep_solicitud.estsol='C'";
				}
			}
			if($lb_anterior)
			{
				$ls_criterio=$ls_criterio.")";
			}
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRE":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT numsol, sep_solicitud.codtipsol, sep_solicitud.coduniadm, codfuefin, fecregsol, estsol,".
				"       consol, monto, monbasinm, montotcar, tipo_destino, sep_solicitud.cod_pro, sep_solicitud.ced_bene,".
				"       spg_unidadadministrativa.denuniadm AS denuniadm, sep_solicitud.fecregsol, ".
				"       (CASE WHEN sep_solicitud.tipo_destino='B' THEN (SELECT ".$ls_cadena."".
				"                                                      FROM rpc_beneficiario".
				"                                                     WHERE sep_solicitud.codemp=rpc_beneficiario.codemp".
				"                                                       AND sep_solicitud.ced_bene=rpc_beneficiario.ced_bene)".
				"             WHEN sep_solicitud.tipo_destino='P' THEN (SELECT nompro".
				"                                                         FROM rpc_proveedor".
				"                                                        WHERE sep_solicitud.codemp=rpc_proveedor.codemp".
				"                                                          AND sep_solicitud.cod_pro=rpc_proveedor.cod_pro)".
				"                                                  ELSE 'NINGUNO'".
				"         END) AS nombre,".
				"      sep_solicitud.codestpro1, sep_solicitud.codestpro2,sep_solicitud.codestpro3,
         sep_solicitud.codestpro4,sep_solicitud.codestpro5".
				"  FROM sep_solicitud,sep_tiposolicitud,spg_unidadadministrativa,spg_dt_unidadadministrativa".
				" WHERE sep_solicitud.codemp='".$this->ls_codemp."'".
				"   AND sep_solicitud.codemp=spg_unidadadministrativa.codemp AND ".
				
				"   sep_solicitud.coduniadm=spg_unidadadministrativa.coduniadm AND".
				"   sep_solicitud.codestpro1=spg_dt_unidadadministrativa.codestpro1 AND 
			         sep_solicitud.codestpro2=spg_dt_unidadadministrativa.codestpro2 AND
			         sep_solicitud.codestpro3=spg_dt_unidadadministrativa.codestpro3 AND
			         sep_solicitud.codestpro4=spg_dt_unidadadministrativa.codestpro4 AND
			         sep_solicitud.codestpro5=spg_dt_unidadadministrativa.codestpro5 AND
			         sep_solicitud.estcla=spg_dt_unidadadministrativa.estcla ".
				"   ".$ls_criterio." ".
				
				" GROUP BY sep_solicitud.codemp, sep_solicitud.numsol, sep_solicitud.codtipsol, sep_solicitud.coduniadm, codfuefin, fecregsol,".
				"          estsol, consol, monto, monbasinm, montotcar, tipo_destino, sep_solicitud.cod_pro,".
				"          sep_solicitud.ced_bene, spg_unidadadministrativa.denuniadm ,sep_solicitud.codestpro1, sep_solicitud.codestpro2,sep_solicitud.codestpro3,
         sep_solicitud.codestpro4,sep_solicitud.codestpro5 ".
				" ORDER BY ".$as_orden."";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			print $this->io_sql->message;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_solicitudes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_solicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitud($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitud
		//         Access: public (sigesp_sep_p_solicitud)  
		//	    Arguments: as_numsol     // Numero de solicitud 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la una solicitud de ejecucion presupuestaria 
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan 
		// Fecha Creación: 11/03/2007 			Fecha Modificacion: 14/08/2007 (Reconversion Monetaria)					
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRE":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT sep_solicitud.codemp, sep_solicitud.numsol, sep_solicitud.codtipsol, sep_solicitud.coduniadm, ".
				"       sep_solicitud.codfuefin, sep_solicitud.fecregsol,  sep_solicitud.consol, sep_solicitud.montoaux AS monto, ".
				"       sep_solicitud.monbasinmaux AS monbasinm, sep_solicitud.montotcaraux AS montotcar,  ".
				"       sep_solicitud.tipo_destino, sep_solicitud.cod_pro, sep_solicitud.ced_bene, ".
				"       spg_unidadadministrativa.denuniadm, ".
				"       (SELECT dentipsol ".
				"          FROM sep_tiposolicitud ".
				"         WHERE sep_solicitud.codtipsol=sep_tiposolicitud.codtipsol) AS dentipsol, ".
				"       (CASE WHEN sep_solicitud.tipo_destino='B' THEN (SELECT ".$ls_cadena." ".
				"                                                      FROM rpc_beneficiario ".
				"                                                     WHERE sep_solicitud.codemp=rpc_beneficiario.codemp ".
				"                                                       AND sep_solicitud.ced_bene=rpc_beneficiario.ced_bene) ".
				"             WHEN sep_solicitud.tipo_destino='P' THEN (SELECT nompro ".
				"                                                         FROM rpc_proveedor ".
				"                                                        WHERE sep_solicitud.codemp=rpc_proveedor.codemp ".
				"                                                          AND sep_solicitud.cod_pro=rpc_proveedor.cod_pro) ".
				"                                                  ELSE 'NINGUNO' ".
				"         END) AS nombre, ".
				"       (SELECT denfuefin ".
				"          FROM sigesp_fuentefinanciamiento ".
				"         WHERE sigesp_fuentefinanciamiento.codfuefin<>'--' ".
				"		    AND sigesp_fuentefinanciamiento.codemp=sep_solicitud.codemp ".
				"			AND sigesp_fuentefinanciamiento.codfuefin=sep_solicitud.codfuefin) AS denfuefin ".
				"  FROM sep_solicitud,spg_unidadadministrativa ".
				" WHERE sep_solicitud.codemp='".$this->ls_codemp."' ".
				"   AND sep_solicitud.numsol='".$as_numsol."' ".
				"   AND sep_solicitud.codemp=spg_unidadadministrativa.codemp ".
				"   AND sep_solicitud.coduniadm=spg_unidadadministrativa.coduniadm ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_dt_solicitud($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_dt_solicitud
		//         Access: public (sigesp_sep_p_solicitud)  
		//	    Arguments: as_numsoldes     // Numero de solicitud de inicio del parametro de Busqueda
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que obtiene la informacion de detalle (bienes, servicios o conceptos) de una solicitud
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan 
		// Fecha Creación: 11/03/2007 			Fecha Modificacion: 14/08/2007 (Reconversion Monetaria)					
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	   switch($_SESSION["ls_gestor"])
	   {
		case "MYSQL":
		 $ls_cadena="CONVERT('' USING utf8) AS unidad";
		 break;
		case "POSTGRE":
		 $ls_cadena="CAST('' AS char(15)) as cuenta";
		 break;     
	   }
   		$ls_sql="SELECT codart AS codigo, canart AS cantidad, unidad, monpreaux AS monpre, monartaux AS monto, 'B' AS tipo,".
				"       (SELECT denart".
				"          FROM siv_articulo".
				"         WHERE siv_articulo.codemp=sep_dt_articulos.codemp".
				"           AND siv_articulo.codart=sep_dt_articulos.codart) AS denominacion".
				"  FROM sep_dt_articulos".
				" WHERE sep_dt_articulos.codemp='".$this->ls_codemp."'".
				"   AND sep_dt_articulos.numsol='".$as_numsol."'".
				" UNION ".
				"SELECT codser AS codigo, canser AS cantidad, ".$ls_cadena.", monpreaux AS monpre, monseraux AS monto, 'S' AS tipo,".
				"       (SELECT denser".
				"          FROM soc_servicios".
				"         WHERE soc_servicios.codemp=sep_dt_servicio.codemp".
				"           AND soc_servicios.codser=sep_dt_servicio.codser) AS denominacion".
				"  FROM sep_dt_servicio".
				" WHERE sep_dt_servicio.codemp='".$this->ls_codemp."'".
				"   AND sep_dt_servicio.numsol='".$as_numsol."'".
				" UNION ".
				"SELECT codconsep AS codigo, cancon AS cantidad, ".$ls_cadena.", monpreaux AS monpre, monconaux AS monto, 'C' AS tipo,".
				"       (SELECT denconsep".
				"          FROM sep_conceptos".
				"         WHERE sep_conceptos.codconsep=sep_dt_concepto.codconsep) AS denominacion".
				"  FROM sep_dt_concepto".
				" WHERE sep_dt_concepto.codemp='".$this->ls_codemp."'".
				"  AND sep_dt_concepto.numsol='".$as_numsol."'";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_dt_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_dt_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_dt_cargos($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_dt_cargos
		//         Access: public (sigesp_sep_p_solicitud)  
		//	    Arguments: as_numsol     // Numero de solicitud 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que obtiene la informacion de detalle de los cargos (bienes, servicios o conceptos) de una solicitud
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan 
		// Fecha Creación: 11/03/2007 			Fecha Modificacion: 14/08/2007 (Reconversion Monetaria)					
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT MAX(codart) AS codigo, codcar, SUM(monbasimpaux) AS monbasimp, SUM(monimpaux) AS monimp, SUM(montoaux) AS monto, ".
				"	   (SELECT MAX(dencar) ".
				"	      FROM sigesp_cargos ".
				"		 WHERE sigesp_cargos.codemp=sep_dta_cargos.codemp ".
				"		   AND sigesp_cargos.codcar=sep_dta_cargos.codcar".
				"        GROUP BY sigesp_cargos.codcar) AS dencar".
				"  FROM sep_dta_cargos".
				" WHERE sep_dta_cargos.codemp='".$this->ls_codemp."'".
				"   AND sep_dta_cargos.numsol='".$as_numsol."'".
				" GROUP BY sep_dta_cargos.codemp, sep_dta_cargos.numsol,sep_dta_cargos.codcar ".
				"UNION ".
				"SELECT MAX(codser) AS codigo, codcar, SUM(monbasimpaux) AS monbasimp, SUM(monimpaux) AS monimp, SUM(montoaux) AS monto, ".
				"	   (SELECT MAX(dencar) ".
				"	      FROM sigesp_cargos ".
				"		 WHERE sigesp_cargos.codemp=sep_dts_cargos.codemp ".
				"		   AND sigesp_cargos.codcar=sep_dts_cargos.codcar".
				"        GROUP BY sigesp_cargos.codcar) AS dencar".
				"  FROM sep_dts_cargos".
				" WHERE sep_dts_cargos.codemp='".$this->ls_codemp."'".
				"   AND sep_dts_cargos.numsol='".$as_numsol."'".
				" GROUP BY sep_dts_cargos.codemp, sep_dts_cargos.numsol,sep_dts_cargos.codcar ".
				"UNION ".
				"SELECT MAX(codconsep) AS codigo, codcar, SUM(monbasimpaux) AS monbasimp, SUM(monimpaux) AS monimp, SUM(montoaux) AS monto, ".
				"	   (SELECT MAX(dencar) ".
				"	      FROM sigesp_cargos ".
				"		 WHERE sigesp_cargos.codemp=sep_dtc_cargos.codemp ".
				"		   AND sigesp_cargos.codcar=sep_dtc_cargos.codcar".
				"        GROUP BY sigesp_cargos.codcar) AS dencar".
				"  FROM sep_dtc_cargos".
				" WHERE sep_dtc_cargos.codemp='".$this->ls_codemp."'".
				"  AND sep_dtc_cargos.numsol='".$as_numsol."'".
				" GROUP BY sep_dtc_cargos.codemp, sep_dtc_cargos.numsol,sep_dtc_cargos.codcar ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_dt_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_cargos->data=$this->io_sql->obtener_datos($rs_data);		
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_dt_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_dt_spgcuentas($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_dt_spgcuentas
		//         Access: public (sigesp_sep_p_solicitud)  
		//	    Arguments: as_numsol     // Numero de solicitud 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que obtiene la informacion de detalle de los cargos (bienes, servicios o conceptos) de una solicitud
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan 
		// Fecha Creación: 11/03/2007 			Fecha Modificacion: 14/08/2007 (Reconversion Monetaria)					
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta, montoaux AS monto".
				"  FROM sep_cuentagasto".
				" WHERE sep_cuentagasto.codemp='".$this->ls_codemp."'".
				"  AND  sep_cuentagasto.numsol='".$as_numsol."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_dt_spgcuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_cuentas->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_dt_spgcuentas
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_dt_unidad($as_codart)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_dt_unidad
		//         Access: public (sigesp_sep_p_solicitud)  
		//	    Arguments: as_codart     // codigo de articulo
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que obtiene las unidades de medida de un articulo
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 17/03/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql="SELECT siv_unidadmedida.unidad".
				"  FROM siv_unidadmedida,siv_articulo".
				" WHERE siv_articulo.codemp='".$this->ls_codemp."'".
				"   AND siv_articulo.codart='".$as_codart."'".
				"   AND siv_articulo.codunimed=siv_unidadmedida.codunimed";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_dt_unidad ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_unidad=$row["unidad"];
				return $li_unidad;
			}
			else
			{
				return false;
			}
			$this->io_sql->free_result($rs_data);
		}		
	}// end function uf_select_dt_unidad
	//-----------------------------------------------------------------------------------------------------------------------------------
    
	//---------------------------------------------------------------------------------------------------------------------------------	
	function uf_sep_select_unidad_medida($as_codart,&$as_codunimed)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sep_select_unidad_medida
		//		   Access: private 
		//	    Arguments: as_codemp //codigo de la empresa
		//	   			   as_codart // codigo del articulo
		//                 as_codunimed // codigo unidad de medida (referencia)
		//    Description: Function que devuelve el codigo de la unidad de medida que tiene asociada el articulo
		//	   Creado Por: Ing. Yozelin Barragan.
		// Fecha Creación: 10/04/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		 $lb_valido=false;
		 $ls_sql =" SELECT codunimed ".
				  " FROM   siv_articulo ".
				  " WHERE  codemp='".$this->ls_codemp."' AND codart='".$as_codart."' ";
		 $rs=$this->io_sql->select($ls_sql);
		 if ($rs===false)
		 {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_sep_select_unidad_medida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		 }		
		 else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$as_codunimed=$row["codunimed"];     
				$lb_valido=true;
			 }	
		 } 
		 return $lb_valido;    
	}//fin 	uf_sep_select_unidad_medida
    //---------------------------------------------------------------------------------------------------------------------------------	
    
	//---------------------------------------------------------------------------------------------------------------------------------	
	function uf_sep_select_denominacion_unidad_medida($as_codart,$as_codunimed,&$as_denunimed)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sep_select_denominacion_unidad_medida
		//		   Access: private 
		//	    Arguments: as_codemp //codigo de la empresa
		//	   			   as_codart // codigo del articulo
		//                 as_codunimed // codigo unidad de medida (referencia)
		//                 as_denunimed // denominacion de la  unidad de medida (referencia)
		//    Description: Function que devuelve la denominacion de la unidad de medida que tiene asociada el articulo
		//	   Creado Por: Ing. Yozelin Barragan.
		// Fecha Creación: 10/04/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		 $lb_valido=false;
		 $ls_sql =" SELECT * ".
                  " FROM  siv_unidadmedida , siv_articulo ".
                  " WHERE siv_articulo.codemp='".$this->ls_codemp."' AND siv_unidadmedida.codunimed='".$as_codunimed."' AND ".
                  "       siv_articulo.codart='".$as_codart."' ";
		 $rs=$this->io_sql->select($ls_sql);
		 if ($rs===false)
		 {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_sep_select_denominacion_unidad_medida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		 }		
		 else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$as_denunimed=$row["denunimed"];     
				$lb_valido=true;
			 }	
		 } 
		 return $lb_valido;    
	}//fin 	uf_sep_select_denominacion_unidad_medida
   //---------------------------------------------------------------------------------------------------------------------------------	
	
   //---------------------------------------------------------------------------------------------------------------------------------	
	function uf_select_denominacionspg($as_cuenta,&$as_denominacion)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sep_select_denominacion_unidad_medida
		//		   Access: private 
		//	    Arguments: as_cuenta //codigo de la cuenta
		//	   			   as_denominacion // denominacion de la cuenta
		//    Description: Function que devuelve la denominacion de la cuenta presupuestaria
		//	   Creado Por: Ing. Yozelin Barragan.
		// Fecha Creación: 10/04/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		 $lb_valido=false;
		 $ls_sql=" SELECT denominacion ".
				 " FROM   spg_cuentas ".
				 " WHERE  codemp='".$this->ls_codemp."'  AND  spg_cuenta='".$as_cuenta."' ";       
		 $rs=$this->io_sql->select($ls_sql);
		 if ($rs===false)
		 {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_denominacionspg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		 }		
		 else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$as_denominacion=$row["denominacion"];     
				$lb_valido=true;
			 }	
		 } 
		 return $lb_valido;    
	}//fin 	uf_select_denominacionspg
   //---------------------------------------------------------------------------------------------------------------------------------	

   //---------------------------------------------------------------------------------------------------------------------------------	
	function uf_select_disponible($as_spgcuenta,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
	                              &$ad_monto_disponible)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_disponible
		//		   Access: private 
		//	    Arguments: $as_spgcuenta   // cuenta 
		//                 $as_codestpro1  //  codestpro1
		//                 $as_codestpro2  //  codestpro2
		//                 $as_codestpro3  //  codestpro3
		//                 $as_codestpro4  //  codestpro4
		//                 $as_codestpro5  //  codestpro5
		//	   			   as_denominacion // denominacion de la cuenta
		//    Description: Function que devuelve el monto disponible de una cuenta especifica  
		//	   Creado Por: Ing. Yozelin Barragan.
		// Fecha Creación: 10/04/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		 $lb_valido=false;
		$ls_sql =" SELECT *,(asignadoaux-(comprometidoaux+precomprometidoaux)+aumentoaux-disminucionaux) as disponible".
	             " FROM   spg_cuentas ".
		   	   	 " WHERE  codemp = '".$this->ls_codemp."'     AND spg_cuenta = '".$as_spgcuenta."'      AND ".
				 "        codestpro1 = '".$as_codestpro1."'   AND codestpro2 = '".$as_codestpro2."'     AND ".
				 "        codestpro3 = '".$as_codestpro3."'   AND codestpro4 = '".$as_codestpro4."'     AND ".
				 "        codestpro5 = '".$as_codestpro5."'                                                 ".
				 " ORDER BY spg_cuenta ";
		 $rs=$this->io_sql->select($ls_sql);
		 if ($rs===false)
		 {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_disponible ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		 }		
		 else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$ad_monto_disponible=$row["disponible"];     
				$lb_valido=true;
			 }	
		 } 
		 return $lb_valido;    
	}//fin 	uf_select_disponible
   //---------------------------------------------------------------------------------------------------------------------------------	
}
?>