<?php
class sigesp_sep_class_report
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sep_class_report()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sep_class_report
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno /Ing. Luis Lang
		// Fecha Creación: 11/03/2007 								
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
								   $ai_contabilizada,$ai_procesada,$ai_anulada,$ai_despachada,$as_orden,$as_codusudes,$as_codusuhas,
								   $ai_aprobada,$ai_pagada)
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
		//	  			   as_codusudes    // Indica si se desea filtrar por el código de usuario
		//	  			   as_codusuhas    // Indica si se desea filtrar por el código de usuario
		//                 ai_aprobada		// Indica si se desea filtrar por este estatus de solicitud
		//                 ai_pagada		// Indica si se desea filtrar por este estatus de solicitud
		//	  			   as_orden         // Orden a mostrar en el reporte		  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las solicitudes 
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 11/03/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$ls_criteusu="";
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
		if(($as_codusudes!="")&&($as_codusuhas!=""))
        {
		   $ls_criteusu=$ls_criteusu."  AND sep_solicitud.codaprusu=sss_usuarios.codusu   ".
		                             "  AND sss_usuarios.codusu>='".$as_codusudes."'AND sss_usuarios.codusu<='".$as_codusuhas."' AND";
		}
		else
		{
		  $ls_criteusu=$ls_criteusu."AND ";
		}
		
		if(($ai_registrada==1)or($ai_emitida==1)or($ai_contabilizada==1)or($ai_procesada==1)or($ai_anulada==1)or($ai_despachada==1)or($ai_aprobada==1))
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
					$ls_criterio=$ls_criterio."  AND (sep_solicitud.estsol='E' AND sep_solicitud.estapro='0'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR sep_solicitud.estsol='E' AND sep_solicitud.estapro='0'";
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
			if($ai_procesada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (sep_solicitud.estsol='P'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR sep_solicitud.estsol='P'";
				}
			}
			if($ai_anulada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (sep_solicitud.estsol='A'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR sep_solicitud.estsol='A'";
				}
			}
			if($ai_despachada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (sep_solicitud.estsol='D'";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR sep_solicitud.estsol='D'";
				}
			}
			
			if($ai_aprobada==1)
			{
				if(!$lb_anterior)
				{
					$ls_criterio=$ls_criterio."  AND (sep_solicitud.estsol='E' AND sep_solicitud.estapro='1' ";
					$lb_anterior=true;
				}
				else
				{
					$ls_criterio=$ls_criterio."  OR sep_solicitud.estsol='E' AND sep_solicitud.estapro='1' ";
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
			$ls_sql2="";
		    if ($ai_pagada==1)
			{
				$ls_sql2=" SELECT sep_solicitud.numsol,sep_solicitud.codtipsol,sep_solicitud.coduniadm,
								 sep_solicitud.codfuefin,cast('PA' AS char(2)) as estsol,
							     sep_solicitud.estapro,sep_solicitud.consol, sep_solicitud.monto, 
								 sep_solicitud.monbasinm, sep_solicitud.montotcar, 
							     sep_solicitud.tipo_destino, sep_solicitud.cod_pro, 
								 sep_solicitud.ced_bene,spg_unidadadministrativa.denuniadm AS denuniadm,
							     sep_solicitud.fecregsol, sep_solicitud.codaprusu,
       							(CASE WHEN sep_solicitud.tipo_destino='B' 
								      THEN (SELECT ".$ls_cadena."
				                              FROM rpc_beneficiario
				                             WHERE sep_solicitud.ced_bene=rpc_beneficiario.ced_bene
				                          group by sep_solicitud.ced_bene,  rpc_beneficiario.nombene, rpc_beneficiario.apebene)
	                                  WHEN sep_solicitud.tipo_destino='P' 
									 THEN (SELECT rpc_proveedor.nompro
				                             FROM rpc_proveedor
				                            WHERE sep_solicitud.cod_pro=rpc_proveedor.cod_pro
				                         group by sep_solicitud.cod_pro, rpc_proveedor.nompro) ELSE 'NINGUNO' END) AS nombre,
	                             sep_solicitud.codestpro1, sep_solicitud.codestpro2,sep_solicitud.codestpro3,
								 sep_solicitud.codestpro4,sep_solicitud.codestpro5				                         				
  							from sep_solicitud
  							join spg_unidadadministrativa on (spg_unidadadministrativa.codemp=sep_solicitud.codemp
														and sep_solicitud.coduniadm=spg_unidadadministrativa.coduniadm)
						    join soc_enlace_sep on (soc_enlace_sep.codemp=sep_solicitud.codemp
									  and soc_enlace_sep.numsol=sep_solicitud.numsol)
						    join soc_ordencompra on (soc_ordencompra.codemp=soc_enlace_sep.codemp
											  and  soc_ordencompra.numordcom=soc_enlace_sep.numordcom)
						    join cxp_rd on (cxp_rd.codemp=soc_enlace_sep.codemp
									 and  cxp_rd.cod_pro=soc_ordencompra.cod_pro
									 and  cxp_rd.codtipdoc='00001')
						    join cxp_solicitudes on (cxp_solicitudes.codemp=cxp_rd.codemp
									  and  cxp_solicitudes.cod_pro=cxp_rd.cod_pro
									  and  cxp_solicitudes.ced_bene=cxp_rd.ced_bene)
						    join cxp_sol_banco on (cxp_sol_banco.codemp=cxp_solicitudes.codemp
									and  cxp_sol_banco.numsol=cxp_solicitudes.numsol
									and  cxp_sol_banco.codope='CH')
						    join scb_movbco on (scb_movbco.codemp=cxp_sol_banco.codemp
								  and scb_movbco.numdoc=cxp_sol_banco.numdoc
								  and scb_movbco.estmov='C'
								  and scb_movbco.codope='CH'
								  and scb_movbco.cod_pro=cxp_rd.cod_pro
								  and scb_movbco.ced_bene=cxp_rd.ced_bene)
						group by sep_solicitud.numsol,sep_solicitud.codtipsol,sep_solicitud.coduniadm,
								sep_solicitud.codfuefin, estsol, sep_solicitud.estapro,sep_solicitud.consol, 
								sep_solicitud.monto, sep_solicitud.monbasinm, sep_solicitud.montotcar, 
							    sep_solicitud.tipo_destino, sep_solicitud.cod_pro, 
							    sep_solicitud.ced_bene,spg_unidadadministrativa.denuniadm,
							    sep_solicitud.fecregsol, sep_solicitud.codaprusu,sep_solicitud.codestpro1, 
								sep_solicitud.codestpro2,sep_solicitud.codestpro3, sep_solicitud.codestpro4,
								sep_solicitud.codestpro5	
						having sum(scb_movbco.monto)=sep_solicitud.monto ".
					 " UNION ".
					 " select sep_solicitud.numsol,sep_solicitud.codtipsol,sep_solicitud.coduniadm,sep_solicitud.codfuefin,
					          cast('PA' AS char(2)) as estsol,sep_solicitud.estapro,sep_solicitud.consol, sep_solicitud.monto,
							  sep_solicitud.monbasinm, sep_solicitud.montotcar, sep_solicitud.tipo_destino, sep_solicitud.cod_pro,
							  sep_solicitud.ced_bene,spg_unidadadministrativa.denuniadm AS denuniadm,sep_solicitud.fecregsol, 
							  sep_solicitud.codaprusu,
       						  (CASE WHEN sep_solicitud.tipo_destino='B' 
							        THEN (SELECT ".$ls_cadena."
				                    FROM rpc_beneficiario
				                   WHERE sep_solicitud.ced_bene=rpc_beneficiario.ced_bene
				                group by sep_solicitud.ced_bene,  rpc_beneficiario.nombene, rpc_beneficiario.apebene)
	                                WHEN sep_solicitud.tipo_destino='P' 
									THEN (SELECT rpc_proveedor.nompro
				                            FROM rpc_proveedor
				                           WHERE sep_solicitud.cod_pro=rpc_proveedor.cod_pro
				                         group by sep_solicitud.cod_pro, rpc_proveedor.nompro) ELSE 'NINGUNO' END) AS nombre,
							  sep_solicitud.codestpro1, sep_solicitud.codestpro2,sep_solicitud.codestpro3, 
							  sep_solicitud.codestpro4,sep_solicitud.codestpro5				                         				
  						 from sep_solicitud
					     join spg_unidadadministrativa on (spg_unidadadministrativa.codemp=sep_solicitud.codemp
									and sep_solicitud.coduniadm=spg_unidadadministrativa.coduniadm)
					     join cxp_rd on (cxp_rd.codemp=sep_solicitud.codemp
								 and  cxp_rd.cod_pro=sep_solicitud.cod_pro
								 and  cxp_rd.codtipdoc='00001')
					     join cxp_solicitudes on (cxp_solicitudes.codemp=cxp_rd.codemp
								  and  cxp_solicitudes.cod_pro=cxp_rd.cod_pro
								  and  cxp_solicitudes.ced_bene=cxp_rd.ced_bene)
					     join cxp_sol_banco on (cxp_sol_banco.codemp=cxp_solicitudes.codemp
								and  cxp_sol_banco.numsol=cxp_solicitudes.numsol
								and  cxp_sol_banco.codope='CH')
					     join scb_movbco on (scb_movbco.codemp=cxp_sol_banco.codemp
							  and scb_movbco.numdoc=cxp_sol_banco.numdoc
							  and scb_movbco.estmov='C'
							  and scb_movbco.codope='CH'
							  and scb_movbco.cod_pro=cxp_rd.cod_pro
							  and scb_movbco.ced_bene=cxp_rd.ced_bene)
					  group by sep_solicitud.numsol,sep_solicitud.codtipsol,sep_solicitud.coduniadm,sep_solicitud.codfuefin, 
					  		    estsol, sep_solicitud.estapro,sep_solicitud.consol, sep_solicitud.monto, sep_solicitud.monbasinm, 
								sep_solicitud.montotcar, sep_solicitud.tipo_destino, sep_solicitud.cod_pro, 
								sep_solicitud.ced_bene,spg_unidadadministrativa.denuniadm,
						        sep_solicitud.fecregsol, sep_solicitud.codaprusu,sep_solicitud.codestpro1, 
								sep_solicitud.codestpro2,sep_solicitud.codestpro3, sep_solicitud.codestpro4,
								sep_solicitud.codestpro5	
					having sum(scb_movbco.monto)=sep_solicitud.monto ";		
			}
			
			$ls_sql="SELECT numsol, sep_solicitud.codtipsol, sep_solicitud.coduniadm, codfuefin, estsol, estapro ,".
					"       consol, monto, monbasinm, montotcar, tipo_destino, sep_solicitud.cod_pro, sep_solicitud.ced_bene,".
					"       spg_unidadadministrativa.denuniadm AS denuniadm, sep_solicitud.fecregsol, sep_solicitud.codaprusu, ".
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
					"       sep_solicitud.codestpro1, sep_solicitud.codestpro2,sep_solicitud.codestpro3,
					        sep_solicitud.codestpro4,sep_solicitud.codestpro5 ".
					"  FROM sep_solicitud, sep_tiposolicitud,spg_unidadadministrativa, spg_dt_unidadadministrativa,sss_usuarios".
					" WHERE sep_solicitud.codemp='".$this->ls_codemp."'".
					"   AND sep_solicitud.codemp=spg_unidadadministrativa.codemp AND ".
					"   sep_solicitud.coduniadm=spg_unidadadministrativa.coduniadm AND".
					"   sep_solicitud.codestpro1=spg_dt_unidadadministrativa.codestpro1 AND 
					    sep_solicitud.codestpro2=spg_dt_unidadadministrativa.codestpro2 AND
						sep_solicitud.codestpro3=spg_dt_unidadadministrativa.codestpro3 AND
						sep_solicitud.codestpro4=spg_dt_unidadadministrativa.codestpro4 AND
						sep_solicitud.codestpro5=spg_dt_unidadadministrativa.codestpro5 AND
						sep_solicitud.estcla=spg_dt_unidadadministrativa.estcla ".
					"   ".$ls_criteusu." ".
					"   sep_solicitud.codemp=sss_usuarios.codemp ".
					"   ".$ls_criterio." ".
					" GROUP BY sep_solicitud.codemp, sep_solicitud.numsol, sep_solicitud.codtipsol, sep_solicitud.coduniadm, 
					        codfuefin, fecregsol,".
					"          estsol, estapro, consol, monto, monbasinm, montotcar, tipo_destino, sep_solicitud.cod_pro,".
					"          sep_solicitud.ced_bene, spg_unidadadministrativa.denuniadm ,
					           sep_solicitud.codestpro1,sep_solicitud.codestpro2,sep_solicitud.codestpro3,
							   sep_solicitud.codestpro4,sep_solicitud.codestpro5,sep_solicitud.codaprusu ".
					" ORDER BY ".$as_orden."";
					
			if(($ai_registrada==1)or($ai_emitida==1)or($ai_contabilizada==1)or($ai_procesada==1)or($ai_anulada==1)or($ai_despachada==1)or($ai_aprobada==1)or($ai_pagada==1))
			{//print "entro1";
				if ($ls_sql2!="")
				{
					$ls_sql=$ls_sql2." UNION ".$ls_sql;
				}
			} 	
			elseif(($ai_registrada==1)or($ai_emitida==1)or($ai_contabilizada==1)or($ai_procesada==1)or($ai_anulada==1)or($ai_despachada==1)or($ai_aprobada==1)or($ai_pagada==0))
			{
				$ls_sql=$ls_sql;
			}
			
			if(($ai_registrada==0)&&($ai_emitida==0)&&($ai_contabilizada==0)&&($ai_procesada==0)&&($ai_anulada==0)&&($ai_despachada==0)&&($ai_aprobada==0)&&($ai_pagada==1))
			{//print "entro2";
				$ls_sql="";
				$ls_sql=$ls_sql2." ORDER BY ".$as_orden."";
			}
			
			//print $ls_sql."<br>";
			
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
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
		// Fecha Creación: 12/03/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				$ls_cadena2="CONCAT(sss_usuarios.nomusu,' ',sss_usuarios.apeusu)";
				break;
			case "POSTGRES":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				$ls_cadena2="sss_usuarios.nomusu||' '||sss_usuarios.apeusu";
				break;
			case "INFORMIX":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				$ls_cadena2="sss_usuarios.nomusu||' '||sss_usuarios.apeusu";
				break;
		}
		$ls_sql="SELECT sep_solicitud.codemp, sep_solicitud.numsol, sep_solicitud.codtipsol, sep_solicitud.coduniadm,".
				"       sep_solicitud.codfuefin, sep_solicitud.fecregsol,  sep_solicitud.consol, sep_solicitud.monto,".
				"       sep_solicitud.monbasinm, sep_solicitud.montotcar, sep_solicitud.tipo_destino, sep_solicitud.cod_pro,".
				"       sep_solicitud.ced_bene,spg_unidadadministrativa.denuniadm, sep_solicitud.nombenalt, sep_solicitud.codusu, ".
				"       (SELECT dentipsol".
				"          FROM sep_tiposolicitud".
				"         WHERE sep_solicitud.codtipsol=sep_tiposolicitud.codtipsol) AS dentipsol,".
				"       (CASE WHEN sep_solicitud.tipo_destino='B' THEN (SELECT ".$ls_cadena." ".
				"                                                      FROM rpc_beneficiario".
				"                                                     WHERE sep_solicitud.codemp=rpc_beneficiario.codemp".
				"                                                       AND sep_solicitud.ced_bene=rpc_beneficiario.ced_bene)".
				"             WHEN sep_solicitud.tipo_destino='P' THEN (SELECT nompro".
				"                                                         FROM rpc_proveedor".
				"                                                        WHERE sep_solicitud.codemp=rpc_proveedor.codemp".
				"                                                          AND sep_solicitud.cod_pro=rpc_proveedor.cod_pro)".
				"                                                  ELSE 'NINGUNO'".
				"         END) AS nombre,".
				"       (CASE WHEN sep_solicitud.tipo_destino='B' THEN (SELECT rpc_beneficiario.rifben".
				"                                                      FROM rpc_beneficiario".
				"                                                     WHERE sep_solicitud.codemp=rpc_beneficiario.codemp".
				"                                                       AND sep_solicitud.ced_bene=rpc_beneficiario.ced_bene)".
				"             WHEN sep_solicitud.tipo_destino='P' THEN (SELECT rpc_proveedor.rifpro".
				"                                                         FROM rpc_proveedor".
				"                                                        WHERE sep_solicitud.codemp=rpc_proveedor.codemp".
				"                                                          AND sep_solicitud.cod_pro=rpc_proveedor.cod_pro)".
				"                                                  ELSE 'NINGUNO'".
				"         END) AS rif,".
				"       (SELECT denfuefin".
				"          FROM sigesp_fuentefinanciamiento".
				"         WHERE sigesp_fuentefinanciamiento.codfuefin<>'--'".
				"		    AND sigesp_fuentefinanciamiento.codemp=sep_solicitud.codemp".
				"			AND sigesp_fuentefinanciamiento.codfuefin=sep_solicitud.codfuefin) AS denfuefin,".
				"       (SELECT ".$ls_cadena2.
				"          FROM sss_usuarios".
				"         WHERE sss_usuarios.codemp=sep_solicitud.codemp".
				"			AND sss_usuarios.codusu=sep_solicitud.codusu) AS nomusu".
				"  FROM sep_solicitud,spg_unidadadministrativa ".
				" WHERE sep_solicitud.codemp='".$this->ls_codemp."' ".
				"   AND sep_solicitud.numsol='".$as_numsol."'".
				"   AND sep_solicitud.codemp=spg_unidadadministrativa.codemp".
				"   AND sep_solicitud.coduniadm=spg_unidadadministrativa.coduniadm";	
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
		// Fecha Creación: 17/03/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	   switch($_SESSION["ls_gestor"])
	   {
		case "MYSQLT":
		 $ls_cadena="CONVERT('' USING utf8) AS unidad";
		 break;
		case "POSTGRES":
		 $ls_cadena="CAST('' AS char(15)) as cuenta";
		 break;     
		case "INFORMIX":
		 $ls_cadena="CAST('' AS char(15)) as cuenta";
		 break;
	   }
   		$ls_sql="SELECT sep_dt_articulos.codart AS codigo, sep_dt_articulos.canart AS cantidad, sep_dt_articulos.unidad,monpre, sep_dt_articulos.monart AS monto, 'B' AS tipo,".
				"       (SELECT siv_unidadmedida.denunimed".
         		"		FROM siv_unidadmedida,siv_articulo". 
	       		"		WHERE siv_articulo.codemp=sep_dt_articulos.codemp".
         		"		AND siv_articulo.codart=sep_dt_articulos.codart".
	 			"		AND siv_unidadmedida.codunimed=siv_articulo.codunimed ) AS denunimed,". 
				"		(SELECT denart". 
	 			"		FROM siv_articulo". 
	 			"		WHERE siv_articulo.codemp=sep_dt_articulos.codemp". 
	 			"		AND siv_articulo.codart=sep_dt_articulos.codart) AS denominacion". 
				"  FROM sep_dt_articulos,siv_unidadmedida".
				" WHERE sep_dt_articulos.codemp='".$this->ls_codemp."'".
				"   AND sep_dt_articulos.numsol='".$as_numsol."'".
				" UNION ".
				"SELECT sep_dt_servicio.codser AS codigo, sep_dt_servicio.canser AS cantidad, ".$ls_cadena.", monpre, sep_dt_servicio.monser AS monto, 'S' AS tipo,".
				"		(SELECT siv_unidadmedida.denunimed".
	 			"		FROM siv_unidadmedida,soc_servicios".
				"	 	WHERE siv_unidadmedida.codunimed=soc_servicios.codunimed". 
				"	    AND soc_servicios.codser=sep_dt_servicio.codser) AS denunimed,". 
				"		(SELECT denser". 
				"	 	FROM soc_servicios". 
				"		WHERE soc_servicios.codemp=sep_dt_servicio.codemp". 
				"		AND soc_servicios.codser=sep_dt_servicio.codser) AS denominacion". 
				"  FROM sep_dt_servicio, soc_servicios".
				" WHERE sep_dt_servicio.codemp='".$this->ls_codemp."'".
				"   AND sep_dt_servicio.numsol='".$as_numsol."'".
				" UNION ".
				"SELECT sep_dt_concepto.codconsep AS codigo, sep_dt_concepto.cancon AS cantidad, ".$ls_cadena.", monpre, sep_dt_concepto.moncon AS monto, 'C' AS tipo,".
				"       '' AS denunimed,".
				"       (SELECT denconsep".
				"          FROM sep_conceptos".
				"         WHERE sep_conceptos.codconsep=sep_dt_concepto.codconsep) AS denominacion".
				"  FROM sep_dt_concepto, sep_conceptos".
				" WHERE sep_dt_concepto.codemp='".$this->ls_codemp."'".
				"  AND sep_dt_concepto.numsol='".$as_numsol."'";//print $ls_sql;
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
		// Fecha Creación: 17/03/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT MAX(codart) AS codigo, codcar, SUM(monbasimp) AS monbasimp, SUM(monimp) AS monimp, SUM(monto) AS monto, ".
				"	   (SELECT MAX(dencar) ".
				"	      FROM sigesp_cargos ".
				"		 WHERE sigesp_cargos.codemp=sep_dta_cargos.codemp ".
				"		   AND sigesp_cargos.codcar=sep_dta_cargos.codcar".
				"        GROUP BY sigesp_cargos.codcar) AS dencar, ".
				"	   (SELECT MAX(porcar) ".
				"	      FROM sigesp_cargos ".
				"		 WHERE sigesp_cargos.codemp=sep_dta_cargos.codemp ".
				"		   AND sigesp_cargos.codcar=sep_dta_cargos.codcar".
				"        GROUP BY sigesp_cargos.codcar) AS porcar".
				"  FROM sep_dta_cargos".
				" WHERE sep_dta_cargos.codemp='".$this->ls_codemp."'".
				"   AND sep_dta_cargos.numsol='".$as_numsol."'".
				" GROUP BY sep_dta_cargos.codemp, sep_dta_cargos.numsol,sep_dta_cargos.codcar ".
				"UNION ".
				"SELECT MAX(codser) AS codigo, codcar, SUM(monbasimp) AS monbasimp, SUM(monimp) AS monimp, SUM(monto) AS monto, ".
				"	   (SELECT MAX(dencar) ".
				"	      FROM sigesp_cargos ".
				"		 WHERE sigesp_cargos.codemp=sep_dts_cargos.codemp ".
				"		   AND sigesp_cargos.codcar=sep_dts_cargos.codcar".
				"        GROUP BY sigesp_cargos.codcar) AS dencar, ".
				"	   (SELECT MAX(porcar) ".
				"	      FROM sigesp_cargos ".
				"		 WHERE sigesp_cargos.codemp=sep_dts_cargos.codemp ".
				"		   AND sigesp_cargos.codcar=sep_dts_cargos.codcar".
				"        GROUP BY sigesp_cargos.codcar) AS porcar".
				"  FROM sep_dts_cargos".
				" WHERE sep_dts_cargos.codemp='".$this->ls_codemp."'".
				"   AND sep_dts_cargos.numsol='".$as_numsol."'".
				" GROUP BY sep_dts_cargos.codemp, sep_dts_cargos.numsol,sep_dts_cargos.codcar ".
				"UNION ".
				"SELECT MAX(codconsep) AS codigo, codcar, SUM(monbasimp) AS monbasimp, SUM(monimp) AS monimp, SUM(monto) AS monto, ".
				"	   (SELECT MAX(dencar) ".
				"	      FROM sigesp_cargos ".
				"		 WHERE sigesp_cargos.codemp=sep_dtc_cargos.codemp ".
				"		   AND sigesp_cargos.codcar=sep_dtc_cargos.codcar".
				"        GROUP BY sigesp_cargos.codcar) AS dencar,".
				"	   (SELECT MAX(porcar) ".
				"	      FROM sigesp_cargos ".
				"		 WHERE sigesp_cargos.codemp=sep_dtc_cargos.codemp ".
				"		   AND sigesp_cargos.codcar=sep_dtc_cargos.codcar".
				"        GROUP BY sigesp_cargos.codcar) AS porcar".
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
		// Fecha Creación: 17/03/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_longestpro1= (25-$this->ls_loncodestpro1)+1;
		$li_longestpro2= (25-$this->ls_loncodestpro2)+1;
		$li_longestpro3= (25-$this->ls_loncodestpro3)+1;
		$li_longestpro4= (25-$this->ls_loncodestpro4)+1;
		$li_longestpro5= (25-$this->ls_loncodestpro5)+1;
		$ls_sql="SELECT substr(sep_cuentagasto.codestpro1,'6','18') AS codestpro1, ".
				"       substr(sep_cuentagasto.codestpro2,'20','6') AS codestpro2, ". 
				"       substr(sep_cuentagasto.codestpro3,'23','3') AS codestpro3, ". 
				"       TRIM(spg_ep3.denestpro3),                       ". 
				"sep_cuentagasto.spg_cuenta, sep_cuentagasto.monto,sep_cuentagasto.estcla, spg_ep3.denestpro3, spg_cuentas.denominacion ". 
				"FROM sep_cuentagasto, spg_ep3, spg_cuentas ". 
				"WHERE sep_cuentagasto.codemp='".$this->ls_codemp."' ".
				"AND sep_cuentagasto.numsol='".$as_numsol."' ". 
				"AND sep_cuentagasto.codestpro3 = spg_ep3.codestpro3 ".
				"AND sep_cuentagasto.estcla = spg_ep3.estcla ".  
				"AND sep_cuentagasto.spg_cuenta = spg_cuentas.spg_cuenta ".
				"AND sep_cuentagasto.estcla = spg_cuentas.estcla ".
				"AND spg_ep3.codestpro1 = spg_cuentas.codestpro1 ".
				"AND spg_ep3.codestpro2 = spg_cuentas.codestpro2 ".
				"AND spg_ep3.codestpro3 = spg_cuentas.codestpro3 ".
				"AND sep_cuentagasto.codestpro1 = spg_ep3.codestpro1 ". 
				"AND sep_cuentagasto.codestpro2 = spg_ep3.codestpro2 ".
				"AND sep_cuentagasto.codestpro3 = spg_ep3.codestpro3"; 
				
	    //print $ls_sql."<br>";
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
	function uf_select_disponible($as_spgcuenta,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,
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
		
		$as_codestpro1 = str_pad($as_codestpro1,25,0,0);
		$as_codestpro2 = str_pad($as_codestpro2,25,0,0);
		$as_codestpro3 = str_pad($as_codestpro3,25,0,0);
		$as_codestpro4 = str_pad($as_codestpro4,25,0,0);
		$as_codestpro5 = str_pad($as_codestpro5,25,0,0);
		$ls_sql =" SELECT (asignado-(comprometido+precomprometido)+aumento-disminucion) as disponible".
	             " FROM   spg_cuentas ".
		   	   	 " WHERE  codemp = '".$this->ls_codemp."'     AND trim(spg_cuenta) = '".trim($as_spgcuenta)."'      AND ".
				 "        codestpro1 = '".$as_codestpro1."'   AND codestpro2 = '".$as_codestpro2."'     AND ".
				 "        codestpro3 = '".$as_codestpro3."'   AND codestpro4 = '".$as_codestpro4."'     AND ".
				 "        codestpro5 = '".$as_codestpro5."'   AND estcla = '".$as_estcla."'                 ".
				 " ORDER BY spg_cuenta ";//print $ls_sql;
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
   	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_ubicacionsolicitudes($as_numsoldes,$as_numsolhas,$as_tipproben,$as_codprobendes,$as_codprobenhas,&$lb_valido)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_ubicacionsolicitudes
		//         Access: public (sigesp_sep_p_solicitud)  
		//	    Arguments: as_numsol     // Numero de solicitud 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la una solicitud de ejecucion presupuestaria 
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/03/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$rs_data="";
		$ls_cadena="";
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
		$ls_sql="SELECT sep_tiposolicitud.estope,sep_tiposolicitud.modsep,sep_solicitud.numsol,sep_solicitud.estsol,sep_solicitud.estapro,".
				"       sep_solicitud.cod_pro,sep_solicitud.ced_bene,".
				"       (CASE tipo_destino WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=sep_solicitud.codemp ".
				"                                         AND rpc_proveedor.cod_pro=sep_solicitud.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=sep_solicitud.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=sep_solicitud.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre ".
				"  FROM sep_solicitud,sep_tiposolicitud ".
				" WHERE sep_solicitud.codemp='".$this->ls_codemp."' ".
				$ls_criterio.
				"   AND sep_solicitud.codtipsol=sep_tiposolicitud.codtipsol".
				"   AND sep_solicitud.numsol<>'---------------'".
				" ORDER BY sep_solicitud.numsol";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_ubicacionsolicitudes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $rs_data;
	}// end function uf_select_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------
 	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_sep_ubicacioncompromiso($as_numsol,$as_codpro,$as_cedbene,$as_procede,&$lb_valido)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_cuenta_gasto
		//         Access: public 
		//	    Arguments: as_numordcom    ---> Orden de Compra a imprimir
		//                 $as_estcondat  ---< tipo de la orden de compra bienes o servicios 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca las cuenats de gastos de la  orden de compra para imprimir
		//	   Creado Por:
		// Fecha Creación:          									Fecha Última Modificación :  
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT cxp_rd_spg.numrecdoc AS documento,cxp_rd.estprodoc AS estatus, 'RD' AS origen  ".
				"  FROM cxp_rd_spg, cxp_rd ".
				" WHERE cxp_rd_spg.codemp='".$this->ls_codemp."'".
				"   AND cxp_rd_spg.numdoccom='".$as_numsol."'".
				"   AND cxp_rd_spg.cod_pro='".$as_codpro."'".
				"   AND cxp_rd_spg.procede_doc='".$as_procede."'".
				"   AND cxp_rd_spg.codemp= cxp_rd.codemp".
				"   AND cxp_rd_spg.numrecdoc= cxp_rd.numrecdoc".
				"   AND cxp_rd_spg.codtipdoc= cxp_rd.codtipdoc".
				"   AND cxp_rd_spg.cod_pro= cxp_rd.cod_pro".
				"   AND cxp_rd_spg.ced_bene= cxp_rd.ced_bene".
				" GROUP BY cxp_rd_spg.numrecdoc,cxp_rd.estprodoc ";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_load_ubicacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $rs_data;
	}// end function uf_select_cuenta_gasto
	//-----------------------------------------------------------------------------------------------------------------------------------

 	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_sep_ubicacionprecompromiso($as_numsol,&$lb_valido)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_load_sep_ubicacionprecompromiso
		//         Access: public 
		//	    Arguments: as_numordcom    ---> Orden de Compra a imprimir
		//                 $as_estcondat  ---< tipo de la orden de compra bienes o servicios 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca las cuenats de gastos de la  orden de compra para imprimir
		//	   Creado Por:
		// Fecha Creación:          									Fecha Última Modificación :  
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT numsol,estincite,numdocdes ".
				"  FROM sep_dt_articulos ".
				" WHERE estincite<>'NI'".
				"   AND codemp='".$this->ls_codemp."'".
				"   AND numsol='".$as_numsol."' ".
				" UNION ".
				"SELECT numsol,estincite,numdocdes ".
				"  FROM sep_dt_servicio ".
				" WHERE estincite<>'NI'".
				"   AND codemp='".$this->ls_codemp."'".
				"   AND numsol='".$as_numsol."' ".
				" UNION ".
				"SELECT numsol, 'Despacho' AS estincite,numorddes AS numdocdes ".
				"  FROM siv_despacho ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND numsol='".$as_numsol."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_load_sep_ubicacionprecompromiso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $rs_data;
	}// end function uf_select_cuenta_gasto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------------	
	function uf_sep_select_usuario($as_codusu,&$as_nomusu)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sep_select_unidad_medida
		//		   Access: private 
		//	    Arguments: as_codemp // codigo de la empresa
		//	   			   as_codusu // codigo del articulo
		//                 as_nomusu // codigo unidad de medida (referencia)
		//    Description: Function que devuelve el codigo de la unidad de medida que tiene asociada el articulo
		//	   Creado Por: Ing. Yozelin Barragan.
		// Fecha Creación: 10/04/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		 $lb_valido=false;
		 $ls_sql ="SELECT nomusu,apeusu ".
				  "  FROM sss_usuarios ".
				  " WHERE codemp='".$this->ls_codemp."'".
				  "   AND codusu='".$as_codusu."' ";
		 $rs=$this->io_sql->select($ls_sql);
		 if ($rs===false)
		 {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_sep_select_usuario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		 }		
		 else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$as_nomusu=$row["nomusu"]." ".$row["apeusu"];     
				$lb_valido=true;
			 }	
		 } 
		 return $lb_valido;    
	}//fin 	uf_sep_select_unidad_medida
    //---------------------------------------------------------------------------------------------------------------------------------	

}
?>