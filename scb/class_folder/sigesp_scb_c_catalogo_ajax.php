<?php
    session_start();   
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("../class_funciones_banco.php");
	$io_funciones_scb = new class_funciones_banco("../../");
    // Tipo del catalogo que se requiere pintar
	$ls_origen   = uf_obtenervalor("origen","");
	$ls_catalogo = uf_obtenervalor("catalogo","");
	switch($ls_catalogo)
	{
		case "RETENCIONIVA":
			uf_print_retencioniva();
			break;
		case "PROVEEDOR":
			uf_print_proveedor();
			break;
		case "BENEFICIARIO":
			uf_print_beneficiario();
			break;
		case "SOLICITUDPAGO":
			uf_print_solicitudespago();
			break;
	    case "OTROSCREDITOS":
			uf_print_otroscreditos();
			break;	
		case "DEDUCCIONES":
			uf_print_deducciones();
			break;
		case "ORDENESMINISTERIO":
			uf_print_ordenespago($ls_origen);
			break;
		case "VERIFICAR_NUMORD":
		    $ls_codtipfon    = $io_funciones_scb->uf_obtenervalor("codtipfon","");
			$ls_numordpagmin = $io_funciones_scb->uf_obtenervalor("numordpagmin","");
			$lb_valido 		 = uf_select_numero_orden_pago($ls_numordpagmin,$ls_codtipfon);
			if (!$lb_valido)
			   {
				 print "ERROR->Orden de Pago Ministerio ya existe para el Tipo de Fondo Especificado !!!";
			   }
			break;
	    case "VERIFICAR_MES":
	        $ls_fecmov = $io_funciones_scb->uf_obtenervalor("fecmov","");
			$lb_valido = uf_load_estatus_mes($ls_fecmov);			
			if (!$lb_valido)
			   {
			     print utf8_encode("ERROR->Operación No puede ser procesada, El Més está Cerrado !!!");
			   }
			break;
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_proveedor()
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de proveedores
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_scb;
		
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_codpro="%".$_POST['codpro']."%";
		$ls_nompro="%".$_POST['nompro']."%";
		$ls_dirpro="%".$_POST['dirpro']."%";
		$ls_rifpro="%".$_POST['rifpro']."%";
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
        $ls_sql="SELECT cod_pro, nompro, trim(sc_cuenta) AS sc_cuenta, rifpro, tipconpro, dirpro, trim(sc_cuentarecdoc) AS sc_cuentarecdoc ".
				"  FROM rpc_proveedor  ".
                " WHERE codemp = '".$ls_codemp."' ".
				"   AND cod_pro <> '----------' ".
				"   AND estprov = 0 ".
				"   AND cod_pro like '".$ls_codpro."' ".
				"   AND nompro like '".$ls_nompro."' ".
				"   AND dirpro like '".$ls_dirpro."' ". 
				"   AND rifpro like '".$ls_rifpro."' ". 
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Proveedores","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td style='cursor:pointer' title='Ordenar por Codigo' align='center' onClick=ue_orden('cod_pro')>Código</td>";
			print "<td style='cursor:pointer' title='Ordenar por Nombre' align='left'   onClick=ue_orden('nompro')>Nombre</td>";
			print "<td style='cursor:pointer' title='Ordenar por RIF'    align='center' onClick=ue_orden('rifpro')>Rif</td>";
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codpro=$row["cod_pro"];
				$ls_nompro=$row["nompro"];//utf8_encode($row["nompro"]);
				$ls_sccuenta=trim($row["sc_cuenta"]);
				$ls_tipconpro=$row["tipconpro"];
				$ls_conrecdoc=$_SESSION["la_empresa"]["conrecdoc"];
				$ls_rifpro=$row["rifpro"];
				switch($ls_conrecdoc)
				{
					case "0":
						$ls_sccuenta=$row["sc_cuenta"];
						break;
					
					case "1":
						$ls_sccuenta=$row["sc_cuentarecdoc"];
						break;
				}
				$ls_tipconpro=$row["tipconpro"];
				$ls_dirprov=$row["dirpro"];
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar('$ls_codpro','$ls_nompro','$ls_rifpro','$ls_sccuenta','$ls_tipconpro');\">".$ls_codpro."</a></td>";
						print "<td>".$ls_nompro."</td>";
						print "<td>".$ls_rifpro."</td>";
						print "</tr>";
					break;
					
					case "SOLICITUDPAGO":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar_solicitudpago('$ls_codpro','$ls_nompro');\">".$ls_codpro."</a></td>";
						print "<td>".$ls_nompro."</td>";
						print "<td>".$ls_rifpro."</td>";
						print "</tr>";
					break;
					
					case "REPDES":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar_reportedesde('$ls_codpro');\">".$ls_codpro."</a></td>";
						print "<td>".$ls_nompro."</td>";
						print "<td>".$ls_rifpro."</td>";
						print "</tr>";
					break;
					
					case "REPHAS":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar_reportehasta('$ls_codpro');\">".$ls_codpro."</a></td>";
						print "<td>".$ls_nompro."</td>";
						print "<td>".$ls_rifpro."</td>";
						print "</tr>";
					break;
					
					case "CMPRET":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar_cmpretencion('$ls_codpro');\">".$ls_codpro."</a></td>";
						print "<td>".$ls_nompro."</td>";
						print "<td>".$ls_rifpro."</td>";
						print "</tr>";
					break;

					case "MODCMPRET":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar_modcmpretencion('$ls_codpro','$ls_nompro','$ls_rifpro','$ls_dirprov');\">".$ls_codpro."</a></td>";
						print "<td>".$ls_nompro."</td>";
						print "<td>".$ls_rifpro."</td>";
						print "</tr>";
					break;
				}
			}
			$io_sql->free_result($rs_data);
			print "</table>";
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_proveedor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_beneficiario()
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_beneficiario
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de beneficiarios
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_scb;
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_cedbene="%".$_POST['cedbene']."%";
		$ls_nombene="%".$_POST['nombene']."%";
		$ls_apebene="%".$_POST['apebene']."%";
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		$ls_sql="SELECT ced_bene, nombene, apebene, rifben, sc_cuenta, tipconben, dirbene, sc_cuentarecdoc ".
				"  FROM rpc_beneficiario ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND ced_bene <> '----------' ".
				"   AND ced_bene like '".$ls_cedbene."' ".
				"   AND nombene like '".$ls_nombene."' ".
				"   AND apebene like '".$ls_apebene."' ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Beneficiarios","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td style='cursor:pointer' title='Ordenar por Cedula' align='center' onClick=ue_orden('ced_bene')>Cedula </td>";
			print "<td style='cursor:pointer' title='Ordenar por Nombre' align='center' onClick=ue_orden('nombene')>Nombre</td>";
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_cedbene=$row["ced_bene"];
				$ls_nombene=utf8_encode($row["nombene"]." ".$row["apebene"]);
				$ls_rifben=$row["rifben"];
				$ls_conrecdoc=$_SESSION["la_empresa"]["conrecdoc"];
				switch($ls_conrecdoc)
				{
					case "0":
						$ls_sccuenta=trim($row["sc_cuenta"]);
						break;
					
					case "1":
						$ls_sccuenta=trim($row["sc_cuentarecdoc"]);
						break;
				}
				$ls_tipconben=$row["tipconben"];
				$ls_dirbene=$row["dirbene"];
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_cedbene','$ls_nombene','$ls_rifben','$ls_sccuenta','$ls_tipconben');\">".$ls_cedbene."</a></td>";
						print "<td>".$ls_nombene."</td>";
						print "</tr>";
					break;
					
					case "SOLICITUDPAGO":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar_solicitudpago('$ls_cedbene','$ls_nombene');\">".$ls_cedbene."</a></td>";
						print "<td>".$ls_nombene."</td>";
						print "</tr>";
					break;
					
					case "REPDES":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar_reportedesde('$ls_cedbene');\">".$ls_cedbene."</a></td>";
						print "<td>".$ls_nombene."</td>";
						print "</tr>";
					break;
					
					case "REPHAS":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar_reportehasta('$ls_cedbene');\">".$ls_cedbene."</a></td>";
						print "<td>".$ls_nombene."</td>";
						print "</tr>";
					break;
					
					case "CMPRET":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar_cmpretencion('$ls_cedbene');\">".$ls_cedbene."</a></td>";
						print "<td>".$ls_nombene."</td>";
						print "</tr>";
					break;

					case "MODCMPRET":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar_modcmpretencion('$ls_cedbene','$ls_nombene','$ls_rifben','$ls_dirbene');\">".$ls_cedbene."</a></td>";
						print "<td>".$ls_nombene."</td>";
						print "</tr>";
					break;
				}					
			}
			$io_sql->free_result($rs_data);
			print "</table>";
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_retencioniva()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_retencionesiva
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de retenciones de iva
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 12/07/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid;
		
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];

		$ls_tipproben	 = $_POST['tipproben'];
		$ld_fecdes		 = $_POST['fecdes'];
		$ld_fechas		 = $_POST['fechas'];
		$ls_mes			 = $_POST['mes'];
		$ls_anio		 = $_POST['anio'];
		$ls_numsol		 = $_POST['numsol'];
		$ls_codprobendes = $_POST['codprobendes'];
		$ls_codprobenhas = $_POST['codprobenhas'];
		$ld_fecdes=$io_funciones->uf_convertirdatetobd($ld_fecdes);
		$ld_fechas=$io_funciones->uf_convertirdatetobd($ld_fechas);
		$ls_cedbendes="";
		$ls_cedbenhas="";
		$ls_codprodes="";
		$ls_codprohas="";
		$ls_criterio="";
		$ls_criterio2="";
		switch($ls_tipproben)
		{
			case "P":
				$ls_codprodes=$ls_codprobendes;
				$ls_codprohas=$ls_codprobenhas;
			break;

			case "B":
				$ls_cedbendes=$ls_codprobendes;
				$ls_cedbenhas=$ls_codprobenhas;
			break;
		}
		if($ld_fecdes!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.fecrep >= '".$ld_fecdes."'";
		}
		if($ld_fechas!="")
		{
			$ls_criterio=$ls_criterio."		AND scb_cmp_ret.fecrep <= '".$ld_fechas."'";
		}
		if($ls_codprobendes!="")
		{
			$ls_criterio=$ls_criterio."		AND trim(scb_cmp_ret.codsujret) >= '".trim($ls_codprobendes)."'";
		}
		if($ls_codprobendes!="")
		{
			$ls_criterio=$ls_criterio."		AND trim(scb_cmp_ret.codsujret) <= '".trim($ls_codprobendes)."'";
		}
		$ls_periodofiscal = $ls_anio.$ls_mes;
		$ls_where="";
		if($ls_numsol!="")
		{
			$ls_where=" AND scb_dt_cmp_ret.numsop='".$ls_numsol."'";
		}				
		$ls_sql="SELECT DISTINCT scb_cmp_ret.numcom, scb_cmp_ret.fecrep, scb_cmp_ret.perfiscal,scb_cmp_ret.codsujret,".
				"       scb_cmp_ret.nomsujret, scb_cmp_ret.dirsujret, scb_cmp_ret.rif,scb_dt_cmp_ret.codret,scb_cmp_ret.estcmpret ".
				"  FROM scb_cmp_ret, scb_dt_cmp_ret ".
				" WHERE scb_cmp_ret.codemp = '".$ls_codemp."' ".
				"   AND scb_cmp_ret.codret = '0000000001' ".
				"   AND scb_cmp_ret.perfiscal = '".$ls_periodofiscal."' ".
				$ls_where.
				"	AND scb_cmp_ret.codemp = scb_dt_cmp_ret.codemp  ".
				"   AND scb_cmp_ret.codret = scb_dt_cmp_ret.codret ".
				"   AND scb_cmp_ret.numcom = scb_dt_cmp_ret.numcom ".
				$ls_criterio.
				" ORDER BY scb_cmp_ret.numcom ";
		$rs_data=$io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Retenciones IVA ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td style=text-align:center width=100>Codigo</td>";
			print "<td style=text-align:center width=50>Fecha</td>";
			print "<td style=text-align:center width=450>Nombre</td>";
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_numcom	  = trim($row["numcom"]);
				$ls_perfiscal = $row["perfiscal"];
				$ls_anofiscal = substr($ls_perfiscal,0,4);
				$ls_mesfiscal = substr($ls_perfiscal,4,6);
				$ls_codsujret = $row["codsujret"];
				$ls_nomsujret = $row["nomsujret"];
				$ls_dirsujret = $row["dirsujret"];
				$ls_rifsujret = $row["rif"];
				$ls_codret	  = $row["codret"];
				$ld_fecrep	  = $io_funciones->uf_convertirfecmostrar($row["fecrep"]);
				$ls_estcmpret = $row["estcmpret"];
				print "<tr class=celdas-blancas>";
				print "<td style=text-align:center width=100><a href=\"javascript:ue_aceptar('$ls_numcom','$ls_anofiscal','$ls_mesfiscal','$ls_codsujret','$ls_nomsujret','$ls_dirsujret','$ls_rifsujret','$ls_codret','$ls_estcmpret');\">".$ls_numcom."</a></td>";
				print "<td style=text-align:center width=50>".$ld_fecrep."</td>";
				print "<td style=text-align:left   width=450>".$ls_nomsujret."</td>";
				print "</tr>";
				
			}
			$io_sql->free_result($rs_data);
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_retencionesiva
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_solicitudespago()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_solicitudespago
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de solicitudes de pago
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 29/04/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_numsol=$_POST['numsol'];
		$ld_fecdes=$_POST['fecemides'];
		$ld_fechas=$_POST['fecemihas'];
		$ls_tipdes=$_POST['tipdes'];
		$ls_codproben=$_POST['codproben'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		$ld_fecdes=$io_funciones->uf_convertirdatetobd($ld_fecdes);
		$ld_fechas=$io_funciones->uf_convertirdatetobd($ld_fechas);
		$ls_codpro="";
		$ls_cedben="";
		if($ls_tipo=='NCND')
		{
			$ls_aux=" AND (estprosol='C' OR estprosol='S')";			
		}
		else
		{
			$ls_aux="";
		}
		switch ($ls_tipdes)
		{
			case "P":
				$ls_codpro=$ls_codproben;
				$ls_cedben="----------";
			break;

			case "B":
				$ls_codpro="----------";
				$ls_cedben=$ls_codproben;
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
		$ls_sql="SELECT cxp_solicitudes.numsol, cxp_solicitudes.cod_pro, cxp_solicitudes.ced_bene, cxp_solicitudes.codfuefin,".
				"       cxp_solicitudes.tipproben, cxp_solicitudes.fecemisol, cxp_solicitudes.consol, cxp_solicitudes.estprosol,".
				"       cxp_solicitudes.monsol, cxp_solicitudes.obssol, cxp_solicitudes.estaprosol,".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre, ".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.sc_cuenta ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT rpc_beneficiario.sc_cuenta ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS sc_cuenta, ".
				"       (CASE tipproben WHEN 'P' THEN (SELECT scg_cuentas.denominacion ".
				"                                        FROM rpc_proveedor, scg_cuentas ".
				"                                       WHERE rpc_proveedor.codemp = scg_cuentas.codemp ".
				"										  AND rpc_proveedor.sc_cuenta = scg_cuentas.sc_cuenta ".
				"										  AND rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT scg_cuentas.denominacion ".
				"                                        FROM rpc_beneficiario, scg_cuentas ".
				"                                       WHERE rpc_beneficiario.codemp = scg_cuentas.codemp ".
				"										  AND rpc_beneficiario.sc_cuenta = scg_cuentas.sc_cuenta ".
				"										  AND rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS denscg, ".
				"       (SELECT denfuefin ".
				"		   FROM sigesp_fuentefinanciamiento ".
				"         WHERE sigesp_fuentefinanciamiento.codemp=cxp_solicitudes.codemp ".
				"           AND sigesp_fuentefinanciamiento.codfuefin=cxp_solicitudes.codfuefin) AS denfuefin ".
				"  FROM cxp_solicitudes,cxp_dt_solicitudes,cxp_rd_spg ".	
				" WHERE cxp_solicitudes.codemp='".$ls_codemp."' ".
				"   AND cxp_solicitudes.numsol LIKE '%".$ls_numsol."%' ".
				"   AND cxp_solicitudes.fecemisol >= '".$ld_fecdes."' ".
				"   AND cxp_solicitudes.fecemisol <= '".$ld_fechas."' ".
				"   AND cxp_solicitudes.cod_pro LIKE '%".$ls_codpro."%'".
				"   AND cxp_solicitudes.ced_bene LIKE '%".$ls_cedben."%'".$ls_aux." AND cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol AND cxp_dt_solicitudes.numrecdoc=cxp_rd_spg.numrecdoc AND cxp_dt_solicitudes.codtipdoc=cxp_rd_spg.codemp AND cxp_dt_solicitudes.codtipdoc=cxp_rd_spg.codemp ".
				"	AND cxp_dt_solicitudes.ced_bene=cxp_rd_spg.ced_bene AND cxp_dt_solicitudes.cod_pro=cxp_rd_spg.cod_pro AND (codestpro||estcla) IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' UNION SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)  ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden." ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Solicitudes de Pago ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=520 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td width=60  style='cursor:pointer' title='Ordenar por Numero de Solicitud'       align='center' onClick=ue_orden('numsol')>".utf8_encode("Número")."</td>";
			print "<td width=300 style='cursor:pointer' title='Ordenar por Proveedor/Beneficiario' align='center' onClick=ue_orden('nombre')>Proveedor/Beneficiario</td>";
			print "<td width=80  style='cursor:pointer' title='Ordenar por Fecha de Emision' align='center' onClick=ue_orden('fecemisol')>Fecha</td>";
			print "<td width=80  style='cursor:pointer' title='Ordenar por Monto' align='center' onClick=ue_orden('monsol')>Monto</td>";
			print "</tr>";
			$li_i=0;
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li_i++;
				$ls_numsol=$row["numsol"];
				$ls_codfuefin=$row["codfuefin"];
				$ls_denfuefin=utf8_encode($row["denfuefin"]);
				$ls_tipo_destino=$row["tipproben"];
				$ls_codpro=$row["cod_pro"];
				$ls_cedbene=$row["ced_bene"];
				$ls_sccuenta=$row["sc_cuenta"];
				$ls_denscg=$row["denscg"];
				switch ($ls_tipo_destino)
				{
					case "P":// proveedor
						$ls_codigo=$row["cod_pro"];
						break;	
					case "B":// beneficiario
						$ls_codigo=$row["ced_bene"];
						break;	
					case "-":// Ninguno
						$ls_codigo="----------";
						break;	
				}
				$ls_nombre=utf8_encode($row["nombre"]);
				$ls_consol=utf8_encode($row["consol"]);
				$ls_obssol=utf8_encode($row["obssol"]);
				$ls_estprosol=$row["estprosol"];
				$ls_estaprosol=$row["estaprosol"];
				$ld_fecemisol=date("Y-m-d",strtotime($row["fecemisol"]));
				$li_monsol=number_format($row["monsol"],2,',','.');
				$ld_fecemisol=$io_funciones->uf_convertirfecmostrar($ld_fecemisol);
				$ls_estatus="";
				switch ($ls_estprosol)
				{
					case "R":
						$ls_estatus="REGISTRO";
						break;
						
					case "S":
						$ls_estatus="PROGRAMACION DE PAGO";
						break;
						
					case "P":
						$ls_estatus="CANCELADA";
						break;

					case "A":
						$ls_estatus="ANULADA";
						break;
						
					case "C":
						$ls_estatus="CONTABILIZADA";
						break;
						
					case "E":
						$ls_estatus="EMITIDA";
						break;
						
					case "N":
						$ls_estatus="ANULADA SIN AFECTACION";
						break;
				}
				switch ($ls_tipo)
				{
					case "REPDES":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: aceptarrepdes('$ls_numsol');\">".$ls_numsol."</a></td>";
						print "<td align='left' width=230>".$ls_nombre."</td>";
						print "<td align='center'>".$ld_fecemisol."</td>";
						print "<td align='right'>".$li_monsol."</td>";
						print "</tr>";	
						break;					
					
					/*case "":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: ue_aceptar('$ls_numsol','$ls_codfuefin','$ls_denfuefin',";
						print "'$ls_codigo','$ls_nombre','$li_monsol','$ls_estprosol','$ls_estaprosol','$ld_fecemisol',";
						print "'$ls_estatus','$ls_tipo_destino','$li_i');\">".$ls_numsol."</a></td>";
						print "<td align='left' width=230>".$ls_nombre."</td>";
						print "<td align='left'>".$ld_fecemisol."</td>";
						print "<td align='left'><input name='txtconsol".$li_i."' type='hidden' id='txtconsol".$li_i."' value='".$ls_consol."'>".
							  "<input name='txtobssol".$li_i."' type='hidden' id='txtobssol".$li_i."' value='".$ls_obssol."'>".$li_monsol."</td>";
						print "</tr>";			
						break;
						
					case "NCND":
						if(!uf_chequear_cancelado($ls_numsol))
						{
							print "<tr class=celdas-blancas>";
							print "<td align='center'><a href=\"javascript: aceptarncnd('$ls_numsol','$ls_tipo_destino','$ls_codpro',";
							print "'$ls_cedbene','$ls_nombre','$ls_sccuenta','$ls_denscg');\">".$ls_numsol."</a></td>";
							print "<td align='left' width=230>".$ls_nombre."</td>";
							print "<td align='left'>".$ld_fecemisol."</td>";
							print "<td align='left'>".$li_monsol."</td>";
							print "</tr>";			
						}
						break;
					case "REPHAS":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: aceptarrephas('$ls_numsol');\">".$ls_numsol."</a></td>";
						print "<td align='left' width=230>".$ls_nombre."</td>";
						print "<td align='left'>".$ld_fecemisol."</td>";
						print "<td align='left'>".$li_monsol."</td>";
						print "</tr>";			
						break;
					case "MODCMPRET":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: aceptarmodcmpret('$ls_numsol');\">".$ls_numsol."</a></td>";
						print "<td align='left' width=230>".$ls_nombre."</td>";
						print "<td align='left'>".$ld_fecemisol."</td>";
						print "<td align='left'>".$li_monsol."</td>";
						print "</tr>";			
						break;*/
				}
			}
			$io_sql->free_result($rs_data);
			print "</table>";
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_solicitudespago
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_otroscreditos()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_otroscreditos
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que imprime el resultado de la busqueda de los creditos a aplicar en un compromiso en particular
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 15/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_ds_cargos;
		
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../../shared/class_folder/class_datastore.php");
		$io_ds_cargos=new class_datastore(); //Datastored de cuentas contables.
				
		$ls_compromiso=$_POST['compromiso'];
		$li_baseimponible=$_POST['baseimponible'];
		$ls_procededoc=$_POST['procededoc'];
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		$ls_parcial=$_POST['parcial'];
		$li_fila=0;
		$ls_sql =   "SELECT sigesp_cargos.codcar, sigesp_cargos.dencar, sigesp_cargos.codestpro, sigesp_cargos.spg_cuenta,".
					"       sigesp_cargos.formula, spg_cuentas.sc_cuenta,  sigesp_cargos.porcar ".
					"  FROM sigesp_cargos, spg_cuentas".
					" WHERE sigesp_cargos.codemp='".$ls_codemp."'".
					"   AND sigesp_cargos.codemp=spg_cuentas.codemp".
					"   AND substr(sigesp_cargos.codestpro,1,25) = spg_cuentas.codestpro1 ".
					"   AND substr(sigesp_cargos.codestpro,26,25) = spg_cuentas.codestpro2 ".
					"   AND substr(sigesp_cargos.codestpro,51,25) = spg_cuentas.codestpro3 ".
					"   AND substr(sigesp_cargos.codestpro,76,25) = spg_cuentas.codestpro4 ".
					"   AND substr(sigesp_cargos.codestpro,101,25) = spg_cuentas.codestpro5 ".
					"   AND sigesp_cargos.spg_cuenta=spg_cuentas.spg_cuenta ".
					" ORDER BY sigesp_cargos.codcar";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Otros Créditos ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			$lo_title[1]=" ";
			$lo_title[2]=utf8_encode("Código");
			$lo_title[3]=utf8_encode("Denominación");
			$lo_object[1][1]="";
			$lo_object[1][2]="";
			$lo_object[1][3]="";
			$lo_object[1][4]="";
			$lo_object[1][5]="";
			if ($ls_tipo=='CMPRET')
			   {
				 $lo_title[4]="Porcentaje"; 
				 $lo_title[5]=utf8_encode("Fórmula"); 
			   }
			else
			   {
				 $lo_title[4]="Base Imponible"; 
				 $lo_title[5]="Monto Impuesto"; 
				 $lo_title[6]="Monto Ajuste"; 
			     $lo_object[1][6]="";
			   }
			if(array_key_exists("cargos",$_SESSION))
			{
				$io_ds_cargos->data=$_SESSION["cargos"];
			}
			else
			{
				$lb_valido=uf_load_cargos_compromiso($ls_compromiso,$ls_procededoc,&$io_ds_cargos);
			}
			while($row=$io_sql->fetch_row($rs_data))
			{
				$lb_existe    = true;
				$ls_codcar    = $row["codcar"];
				$ls_dencar    = $row["dencar"];
				$ls_formula   = $row["formula"];
				$ls_codestpro = $row["codestpro"];
				$ls_spgcuenta = trim($row["spg_cuenta"]);
				$ls_scgcuenta = trim($row["sc_cuenta"]);
				$li_porcar    = $row["porcar"];
				$ls_activo    = "";
				$li_basimp    = number_format($li_baseimponible,2,",",".");
				$li_monimp    = "0,00";
				$ls_codfuefin = "--";
				$li_row= $io_ds_cargos->findValues(array('codcar'=>$ls_codcar,'nrocomp'=>$ls_compromiso,'procededoc'=>$ls_procededoc),"codcar");
				if($li_row>0)
				{
					$ls_activo="checked";
					$li_basimp=number_format($io_ds_cargos->getValue("baseimp",$li_row),2,",",".");
					$li_monimp=number_format($io_ds_cargos->getValue("monimp",$li_row),2,",",".");
					$ls_codfuefin=$io_ds_cargos->getValue("codfuefin",$li_row);
				}
				else
				{
					$li_row=$io_ds_cargos->findValues(array('codpro'=>$ls_codestpro,'cuenta'=>$ls_spgcuenta),"codpro");
					if($li_row>0)
					{
						$ls_codfuefin=$io_ds_cargos->getValue("codfuefin",$li_row);
					}
				}
				if($ls_parcial=="1")
				{
					if($ls_confiva=="C")
					{
						$li_row=$io_ds_cargos->findValues(array('cuenta'=>$ls_spgcuenta),"cuenta");
					}
					else
					{
						$li_row=$io_ds_cargos->findValues(array('codpro'=>$ls_codestpro,'cuenta'=>$ls_spgcuenta),"codpro");
					}
					if($li_row==-1)
					{
						$lb_existe=false;
					}
					else
					{
						$ls_codfuefin=$io_ds_cargos->getValue("codfuefin",$li_row);
					}
				}
				  $li_fila++;
				  $lo_object[$li_fila][1]="<input name=radiocargos           type=radio id=radiocargos".$li_fila." class=sin-borde  value='1'>";
				  $lo_object[$li_fila][2]="<input name=txtcodcar".$li_fila." type=text  id=txtcodcar".$li_fila."   class=sin-borde  style=text-align:center size=7  value='".trim($ls_codcar)."' readonly>";
				  $lo_object[$li_fila][3]="<input name=txtdencar".$li_fila." type=text  id=txtdencar".$li_fila."   class=sin-borde  style=text-align:left   size=60 value='".$ls_dencar."'       readonly>";
				  $lo_object[$li_fila][4]="<input name=porcar".$li_fila."    type=text  id=porcar".$li_fila."      class=sin-borde  style=text-align:right  size=7  value='".number_format($li_porcar,2,',','.')."'       readonly>";
				  $lo_object[$li_fila][5]="<input name=formula".$li_fila."   type=text  id=formula".$li_fila."     class=sin-borde  style=text-align:left   size=20 value='".$ls_formula."'      readonly>";
			}
			$io_sql->free_result($rs_data);
			if ($ls_tipo=='CMPRET')
			   {
			     echo"<table width=534 border=0 align=center cellpadding=0 cellspacing=0>";
    			 echo "<tr>";
      			 echo "<td width=532 colspan=6 align=center bordercolor=#FFFFFF>";
        		 echo "<div align=center class=Estilo2>";
          		 echo "<p align=right>&nbsp;&nbsp;&nbsp;<a href='javascript: uf_aceptar_creditos($li_fila);'><img src='../shared/imagebank/tools20/aprobado.gif' alt='Aceptar' width=20 height=20 border=0>Agregar Otros Cr&eacute;dito</a></p>";
      			 echo "</div></td>";
    			 echo "</tr>";
  				 echo "</table>";
			   }
			$io_grid->makegrid($li_fila,$lo_title,$lo_object,580,"","gridcargos");
		}
		unset($io_include,$io_conexion,$io_sql,$io_mensajes,$io_funciones,$ls_codemp);
	}// end function uf_print_otroscreditos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargos_compromiso($as_numero,$as_procede,&$ao_ds_cargos)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargos_compromiso
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_numero  // Número de comprobante
		//				   as_procede  // procede del documento
		//				   ao_ds_cargos  // Datastored de Cargos
		//	      Returns: lb_valido True si se ejecuto el select
		//	  Description: Función que se encarga de buscar las cuentas presupuestarias asociadas a una solicitud de ejecución
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 05/08/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true; 
		$ls_sql="";
		switch($as_procede)
		{
			case "SOCCOC": // Orden de Compra de Bienes
				$ls_sql="SELECT soc_solicitudcargos.codcar, (soc_solicitudcargos.numordcom) AS compromiso, soc_solicitudcargos.codestpro1, ".
						"		soc_solicitudcargos.codestpro2, soc_solicitudcargos.codestpro3, soc_solicitudcargos.codestpro4, ".
						"		soc_solicitudcargos.codestpro5, soc_solicitudcargos.spg_cuenta, soc_solicitudcargos.sc_cuenta, ".
						"		soc_solicitudcargos.formula, soc_solicitudcargos.monobjret, soc_solicitudcargos.monret, sigesp_cargos.porcar, ".
						"		soc_solicitudcargos.estcla ".
						"  FROM soc_solicitudcargos, sigesp_cargos ".
						" WHERE soc_solicitudcargos.codemp='".$this->ls_codemp."' ".
						"   AND soc_solicitudcargos.numordcom='".$as_numero."' ".
						"   AND soc_solicitudcargos.estcondat='B' ".
						"   AND soc_solicitudcargos.codcar=sigesp_cargos.codcar"; 
				break;
			
			case "SOCCOS": // Orden de Compra de Servicios
				$ls_sql="SELECT soc_solicitudcargos.codcar, (soc_solicitudcargos.numordcom) AS compromiso, soc_solicitudcargos.codestpro1, ".
						"		soc_solicitudcargos.codestpro2, soc_solicitudcargos.codestpro3, soc_solicitudcargos.codestpro4, ".
						"		soc_solicitudcargos.codestpro5, soc_solicitudcargos.spg_cuenta, soc_solicitudcargos.sc_cuenta, ".
						"		soc_solicitudcargos.formula, soc_solicitudcargos.monobjret, soc_solicitudcargos.monret, sigesp_cargos.porcar, ".
						"		soc_solicitudcargos.estcla ".
						"  FROM soc_solicitudcargos, sigesp_cargos ".
						" WHERE soc_solicitudcargos.codemp='".$this->ls_codemp."' ".
						"   AND soc_solicitudcargos.numordcom='".$as_numero."' ".
						"   AND soc_solicitudcargos.estcondat='S' ".
						"   AND soc_solicitudcargos.codcar=sigesp_cargos.codcar"; 
				break;

			case "SEPSPC": // Solicitud de Ejecución Presupuestaria
				$ls_sql="SELECT sep_solicitudcargos.codcar, (sep_solicitudcargos.numsol) AS compromiso, sep_solicitudcargos.codestpro1, ".
						"		sep_solicitudcargos.codestpro2, sep_solicitudcargos.codestpro3, sep_solicitudcargos.codestpro4, ".
						"		sep_solicitudcargos.codestpro5, sep_solicitudcargos.spg_cuenta, sep_solicitudcargos.sc_cuenta, ".
						"		sep_solicitudcargos.formula, sep_solicitudcargos.monobjret, sep_solicitudcargos.monto, sigesp_cargos.porcar, ".
						"		sep_solicitudcargos.estcla ".
						"  FROM sep_solicitudcargos, sigesp_cargos ".
						" WHERE sep_solicitudcargos.codemp='".$this->ls_codemp."' ".
						"   AND sep_solicitudcargos.numsol='".$as_numero."' ".
						"   AND sep_solicitudcargos.codcar=sigesp_cargos.codcar"; 
				break;
		}
		if($ls_sql!="")
		{
			$rs_data=$this->io_sql->select($ls_sql);
			if ($rs_data===false)
			{
				$lb_valido=false; 
				$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_cargos_compromiso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
				{
					$lb_existe=true;
					$ls_codcar=$row["codcar"];
					$ls_nrocomp=$row["compromiso"];
					$ls_codpro=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
					$ls_estcla=$row["estcla"];
					$ls_cuenta=$row["spg_cuenta"];
					$li_baseimp=$row["monobjret"];
					$li_monto_anterior=0;
					$lb_valido=$this->uf_load_monto_causado_anterior($ls_nrocomp,$as_procede,$ls_cuenta,$ls_codpro,$ls_estcla,&$li_monto_anterior);
					$ls_codfuefin="--";
					$li_monimp=$row["monret"]-$li_monto_anterior;
					if($lb_valido)
					{
						$ao_ds_cargos->insertRow("codcar",$ls_codcar);			
						$ao_ds_cargos->insertRow("nrocomp",$ls_nrocomp);			
						$ao_ds_cargos->insertRow("codpro",$ls_codpro);			
						$ao_ds_cargos->insertRow("estcla",$ls_estcla);			
						$ao_ds_cargos->insertRow("cuenta",$ls_cuenta);	
						$ao_ds_cargos->insertRow("procededoc",$as_procede);	
						$ao_ds_cargos->insertRow("baseimp",$li_baseimp);	
						$ao_ds_cargos->insertRow("monimp",$li_monimp);	
						$ao_ds_cargos->insertRow("codfuefin",$ls_codfuefin);	
					}		
				}
				$this->io_sql->free_result($rs_data);
			}		
		}
		return $lb_valido;
	}// end function uf_load_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_deducciones()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_deducciones
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que imprime el resultado de la busqueda de las cdeducciones a aplicar en la recepción de documentos
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 22/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid,$io_ds_deducciones;
		
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../../shared/class_folder/class_datastore.php");
		$io_ds_deducciones=new class_datastore(); // Datastored de cuentas contables
				
		$ls_numrecdoc=$_POST['numrecdoc'];
		$li_subtotal=$_POST['subtotal'];
		$li_cargos=$_POST['cargos'];
		$ls_procede=$_POST['procede'];
		$ls_presupuestario=$_POST['presupuestario'];
		$ls_contable=$_POST['contable'];
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_modageret = $_SESSION["la_empresa"]["modageret"];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		$li_fila=0;
		$ls_sql="SELECT codded,dended,formula,porded,sc_cuenta,islr,iva,estretmun
				   FROM sigesp_deducciones
				  WHERE iva=1 
				    AND estretmun=0 
					AND islr=0 
					AND otras=0
				  ORDER BY codded ASC";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Deducciones ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			$lo_title[1]=" ";
			$lo_title[2]=utf8_encode("Código");
			$lo_title[3]=utf8_encode("Denominación");
			if ($ls_tipo=='CMPRETIVA')
			   {
			     $lo_title[4]="Porcentaje";
			     $lo_title[5]=utf8_encode("Fórmula"); 
			   }
			if (array_key_exists("deducciones",$_SESSION))
			   {
				 $io_ds_deducciones->data=$_SESSION["deducciones"];
			   }	
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li_fila++;
			    $ls_codded=$row["codded"];
				$ls_dended=$row["dended"];
				$ls_formula=$row["formula"];
				$ld_porded=$row["porded"];
				$ls_cuenta=$row["sc_cuenta"];				
				$li_iva=$row["iva"]; 
				$li_islr=$row["islr"]; 
				$li_estretmun=$row["estretmun"];
				$ls_activo=""; 
				$li_monobjret=0;
				$li_monret="0,00";
				if(($li_islr=='1')||($li_estretmun=='1'))
				{
					$li_monobjret=number_format($li_subtotal,2,',','.');
					
				}
				else
				{
					$li_monobjret=$li_cargos;
				}
				$li_row=$io_ds_deducciones->findValues(array('codded'=>$ls_codded),"codded");
				if($li_row>0)
				{
					$ls_activo="checked";
					$li_monobjret=$io_ds_deducciones->getValue("monobjret",$li_row);
					$li_monret=$io_ds_deducciones->getValue("monret",$li_row);
				}
				$lo_object[$li_fila][1]="<input name=radiodeduccion        type=radio id=radiodeduccion".$li_fila." class=sin-borde>";
				$lo_object[$li_fila][2]="<input name=txtcodded".$li_fila." type=text  id=txtcodded".$li_fila."      class=sin-borde  style=text-align:center size=7   value='".$ls_codded."'  readonly>";
				$lo_object[$li_fila][3]="<input name=txtdended".$li_fila." type=text  id=txtdended".$li_fila."      class=sin-borde  style=text-align:left   size=40  value='".$ls_dended."'  readonly>";
				$lo_object[$li_fila][4]="<input name=porded".$li_fila."    type=text  id=porded".$li_fila."    	  class=sin-borde  style=text-align:right  size=7   value='".number_format($ld_porded,2,',','.')."'  readonly >";
				$lo_object[$li_fila][5]="<input name=formula".$li_fila."   type=text  id=formula".$li_fila."        class=sin-borde  style=text-align:left   size=50  value='".$ls_formula."' readonly>";
			}
			$io_sql->free_result($rs_data);
			if ($ls_tipo=='CMPRETIVA')
			   {
			     echo"<table width=534 border=0 align=center cellpadding=0 cellspacing=0>";
    			 echo "<tr>";
      			 echo "<td width=532 colspan=6 align=center bordercolor=#FFFFFF>";
        		 echo "<div align=center class=Estilo2>";
          		 echo "<p align=right>&nbsp;&nbsp;&nbsp;<a href='javascript: uf_aceptar_deducciones($li_fila);'><img src='../shared/imagebank/tools20/aprobado.gif' alt='Aceptar' width=20 height=20 border=0>Agregar Deducciones</a></p>";
      			 echo "</div></td>";
    			 echo "</tr>";
  				 echo "</table>";
			   }
			$io_grid->makegrid($li_fila,$lo_title,$lo_object,580,"","griddeduccion");
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_deducciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_ordenespago($as_origen)
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_ordenespago
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que imprime el resultado de la busqueda de las Ordenes de Pago Ministerio.
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 11/02/2009.								Fecha Última Modificación : 11/02/2009.
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../sigesp_c_cuentas_banco.php");
		require_once("../../shared/class_folder/class_sql.php");		
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");

		$io_include   = new sigesp_include();
		$io_mensajes  = new class_mensajes();		
		$io_funciones = new class_funciones();		
		$io_conexion  = $io_include->uf_conectar();
		$io_sql		  = new class_sql($io_conexion);			
		$io_ctaban    = new sigesp_c_cuentas_banco();		
		
		$ls_numordpagmin = $_POST['numordpagmin'];
		$ls_codban       = $_POST['codban'];
		$ls_ctaban       = $_POST['ctaban'];
		$ls_fecmov	 	 = $_POST['fecmov'];
		$ls_sqlaux 		 = "";
		$li_fila=0;
		if (!empty($ls_fecmov))
		   {
		     $ls_fecmov = $io_funciones->uf_convertirdatetobd($ls_fecmov);
			 $ls_sqlaux = "AND scb_movbco.fecmov = '".$ls_fecmov."'";
		   }
		$ls_codope = $_POST['codope'];
		if ($ls_codope!='-')
		   {
		     $ls_sqlaux = $ls_sqlaux."AND scb_movbco.codope='".$ls_codope."'";
		   }
		else
		   {
		     $ls_sqlaux = $ls_sqlaux."AND (scb_movbco.codope = 'DP' OR scb_movbco.codope = 'NC')";
		   }
		$ls_sql="SELECT scb_movbco.numordpagmin, scb_movbco.codban, scb_movbco.ctaban, scb_banco.nomban, scb_ctabanco.dencta, 
		                scb_tipofondo.porrepfon, scb_movbco.fecmov, scb_tipocuenta.codtipcta, scb_tipocuenta.nomtipcta, 
						trim(scb_ctabanco.sc_cuenta) as sc_cuenta, scb_movbco.monto, scb_movbco.codtipfon, scb_tipofondo.dentipfon
				   FROM scb_movbco, scb_banco, scb_ctabanco, scb_tipocuenta, scb_tipofondo
				  WHERE scb_movbco.codemp = '".$_SESSION["la_empresa"]["codemp"]."'
				    AND trim(scb_movbco.numordpagmin) <>''
					AND trim(scb_movbco.numordpagmin) <>'-'	$ls_sqlaux				
					AND scb_movbco.codtipfon<>'----'
					AND scb_movbco.codban like '%".$ls_codban."%'
					AND scb_movbco.ctaban like '%".$ls_ctaban."%'
				    AND scb_movbco.numordpagmin like '%".$ls_numordpagmin."%'
					AND scb_movbco.codemp = scb_banco.codemp
					AND scb_movbco.codban = scb_banco.codban					
					AND scb_movbco.codemp = scb_ctabanco.codemp
					AND scb_movbco.codban = scb_ctabanco.codban
					AND scb_movbco.ctaban = scb_ctabanco.ctaban
					AND scb_ctabanco.codtipcta=scb_tipocuenta.codtipcta
					AND scb_movbco.codemp=scb_tipofondo.codemp
					AND scb_movbco.codtipfon=scb_tipofondo.codtipfon
				  ORDER BY scb_movbco.numordpagmin, scb_movbco.fecmov ASC";//echo $ls_sql.'<br><br>';
		$rs_data=$io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
		     $io_mensajes->uf_mensajes_ajax("Error al Ordenes de Pago Ministerio ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		     print $io_sql->message;
		   }
		else
		   {
			 echo "<table width=760 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			 echo "<tr class=titulo-celda>";
			 echo "<td style='cursor:pointer' title='Ordenar por No. Orden Pago'  style=text-align:center width=100 >No. Orden Pago</td>";
			 echo "<td style='cursor:pointer' title='Ordenar por Banco'           style=text-align:center width=150 >Banco</td>";
			 echo "<td style='cursor:pointer' title='Ordenar por Cuenta Bancaria' style=text-align:center width=250 >Cuenta</td>";
			 echo "<td style='cursor:pointer' style=text-align:center width=50>Monto</td>";
			 echo "<td style='cursor:pointer' style=text-align:center width=50>% Reposici&oacute;n</td>";
			 echo "<td style='cursor:pointer' style=text-align:center width=50>% Consumido</td>";
			 echo "<td style='cursor:pointer' style=text-align:center width=80>Disponible</td>";
			 echo "</tr>";
			 while (!$rs_data->EOF)
			       {
				     $li_fila++;
			         $ls_codban 	  = $rs_data->fields["codban"];
					 $ls_ctaban 	  = $rs_data->fields["ctaban"];
					 $io_ctaban->uf_verificar_saldo($ls_codban,$ls_ctaban,&$ld_mondiscta);
					 $ld_mondiscta    = number_format($ld_mondiscta,2,',','.');
					 $ls_scgcta 	  = $rs_data->fields["sc_cuenta"];					
					 $ls_nomban   	  = $rs_data->fields["nomban"];
					 $ls_fecmov   	  = $rs_data->fields["fecmov"];
					 $ls_denctaban 	  = $rs_data->fields["dencta"];
					 $ls_codtipcta 	  = $rs_data->fields["codtipcta"];
					 $ls_dentipcta	  = $rs_data->fields["nomtipcta"];					 
					 $ls_numordpagmin = $rs_data->fields["numordpagmin"];
					 $ld_monordpagmin = $rs_data->fields["monto"];//Monto Total de la Orden de Pago Ministerio.
					 $ls_codtipfon    = $rs_data->fields["codtipfon"];
					 $ls_dentipfon    = $rs_data->fields["dentipfon"];
					 $ld_porrepfon    = $rs_data->fields["porrepfon"];//Porcentaje de Reposición.
					 $ld_totmoncon    = uf_load_monto_consumido($ls_numordpagmin,$ls_codtipfon);//Monto Consumido del Monto Original.
					 $ld_monmaxmov    = (($ld_monordpagmin*($ld_porrepfon/100))-$ld_totmoncon);
					 $ld_monmaxmov    = number_format($ld_monmaxmov,2,'.','');
					 $ld_totporcon    = (($ld_totmoncon*100)/$ld_monordpagmin);//Porcentaje Consumido.
					 if ($as_origen=='EC' || $as_origen=='CO' || $ld_totporcon<$ld_porrepfon)//Emisión de Cheques ó Carta Orden.
					    {
						  if ($ld_monmaxmov>0)
							 {
							   echo "<tr class=celdas-azules>";						   
							 }
						  else
							 {
							   echo "<tr class=celdas-blancas>"; 
							 }
						  echo "<td style=text-align:center width=100><a href=\"javascript:aceptar_ordenespago('$ls_numordpagmin','$ls_codban','$ls_nomban','$ls_ctaban','$ls_denctaban','$ls_codtipcta','$ls_dentipcta','$ls_scgcta','$ld_mondiscta','$ls_codtipfon','$ls_dentipfon','$ld_monmaxmov');\">".$ls_numordpagmin."</a></td>";
						  echo "<td style=text-align:left   width=100 title='".$ls_nomban."'>".$ls_codban.' - '.$ls_nomban."</td>";
						  echo "<td style=text-align:left   width=300 title='".$ls_denctaban."'>".$ls_ctaban.' - '.$ls_denctaban."</td>";
						  echo "<td style=text-align:right  width=80>".number_format($ld_monordpagmin,2,',','.')."</td>";
						  echo "<td style=text-align:right  width=50>".number_format($ld_porrepfon,2,',','.')."</td>";
						  echo "<td style=text-align:right  width=50>".number_format($ld_totporcon,2,',','.')."</td>";
						  echo "<td style=text-align:right  width=80>".number_format($ld_monmaxmov,2,',','.')."</td>";
						  echo "</tr>";						
						}
					 $rs_data->MoveNext();
			       }
			 $io_sql->free_result($rs_data);
		   }
		unset($io_include,$io_conexion,$io_sql,$io_mensajes,$io_funciones,$ls_codemp);
	}// end function uf_print_ordenespago
    //-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtenervalor($as_valor,$as_valordefecto)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtenervalor
		//		   Access: public
		//	    Arguments: as_valor  // Variable que deseamos obtener
		//				   as_valordefecto  // Valor por defecto de la variable
		//	      Returns: valor contenido de la variable
		//	  Description: Función que obtiene el valor de una variable que viene de un submit y si no trae valor coloca el
		//				   por defecto 
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 01/02/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$valor="";
		if(array_key_exists($as_valor,$_POST))
		{
			$valor=$_POST[$as_valor];
		}
		if(trim($valor)=="")
		{
			$valor=$as_valordefecto;
		}
		return $valor; 
	}// end function uf_obtenervalor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_consumido($as_numordpagmin,$as_codtipfon)
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_consumido
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que imprime el resultado de la busqueda de las Ordenes de Pago Ministerio.
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 11/02/2009.								Fecha Última Modificación : 11/02/2009.
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/class_sql.php");		
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");
		
		$io_include   = new sigesp_include();
		$io_mensajes  = new class_mensajes();		
		$io_funciones = new class_funciones();		
		$io_conexion  = $io_include->uf_conectar();
		$io_sql		  = new class_sql($io_conexion);			
		
		$ld_totmoncon = 0;//Sumatoria de los Consumos de Movimientos asociados a la Orden de Pago Ministerio.
		$ld_moncon = 0;

		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_aux_where=" AND CONCAT(cxp_rd.codemp,cxp_rd.numrecdoc,cxp_rd.codtipdoc,cxp_rd.cod_pro,cxp_rd.ced_bene) ".
							   "	 NOT IN (SELECT CONCAT(cxp_rd.codemp,cxp_rd.numrecdoc,cxp_rd.codtipdoc,cxp_rd.cod_pro,cxp_rd.ced_bene)".
							   "			   FROM cxp_rd,cxp_dt_solicitudes,cxp_sol_banco".
							   "			  WHERE cxp_rd.codemp=cxp_dt_solicitudes.codemp".
							   "				AND cxp_rd.numrecdoc=cxp_dt_solicitudes.numrecdoc".
							   "				AND cxp_rd.codtipdoc=cxp_dt_solicitudes.codtipdoc".
							   "				AND cxp_rd.cod_pro=cxp_dt_solicitudes.cod_pro".
							   "				AND cxp_rd.ced_bene=cxp_dt_solicitudes.ced_bene".
							   "				AND cxp_dt_solicitudes.codemp=cxp_sol_banco.codemp".
							   "				AND cxp_dt_solicitudes.numsol=cxp_sol_banco.numsol) ";
				break;
			case "POSTGRES":
				$ls_aux_where =" AND cxp_rd.codemp||cxp_rd.numrecdoc||cxp_rd.codtipdoc||cxp_rd.cod_pro||cxp_rd.ced_bene".
							   "	 NOT IN (SELECT (cxp_rd.codemp||cxp_rd.numrecdoc||cxp_rd.codtipdoc||cxp_rd.cod_pro||cxp_rd.ced_bene)".
							   "			   FROM cxp_rd,cxp_dt_solicitudes,cxp_sol_banco".
							   "			  WHERE cxp_rd.codemp=cxp_dt_solicitudes.codemp".
							   "				AND cxp_rd.numrecdoc=cxp_dt_solicitudes.numrecdoc".
							   "				AND cxp_rd.codtipdoc=cxp_dt_solicitudes.codtipdoc".
							   "				AND cxp_rd.cod_pro=cxp_dt_solicitudes.cod_pro".
							   "				AND cxp_rd.ced_bene=cxp_dt_solicitudes.ced_bene".
							   "				AND cxp_dt_solicitudes.codemp=cxp_sol_banco.codemp".
							   "				AND cxp_dt_solicitudes.numsol=cxp_sol_banco.numsol) ";
				break;
		}

		$ls_sql = "SELECT SUM(monto) as moncon
					 FROM scb_movbco 
					WHERE numordpagmin<>'-' 
					  AND numordpagmin<>''
					  AND numordpagmin = '".$as_numordpagmin."'
					  AND codtipfon = '".$as_codtipfon."'
					  AND (codope='CH' OR codope='ND')
					GROUP BY numordpagmin,codtipfon
					UNION
				   SELECT SUM(montotdoc) as moncon
				     FROM cxp_rd
					WHERE numordpagmin = '".$as_numordpagmin."'
					  AND codtipfon = '".$as_codtipfon."' $ls_aux_where
					GROUP BY numordpagmin";
		$rs_data=$io_sql->select($ls_sql);//echo $ls_sql.'<br>';
		if ($rs_data===false)
		   {
		     $io_mensajes->uf_mensajes_ajax("Class->sigesp_scb_c_catalogo_ajax.php;Metodo->uf_load_monto_consumido","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		     print $io_sql->message;
		   }
		else
		   {
			 while(!$rs_data->EOF)
			      {
				    $ld_moncon = $rs_data->fields["moncon"];
				    $ld_totmoncon += $ld_moncon;
				    $rs_data->MoveNext();
				  }
			 $io_sql->free_result($rs_data);
		   }
		unset($io_include,$io_conexion,$io_sql,$io_mensajes,$io_funciones,$ls_codemp);
	  return $ld_totmoncon;
	}// end function uf_load_monto_consumido
    //-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_numero_orden_pago($as_numordpagmin,$as_codtipfon)
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_numero_orden_pago
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que imprime el resultado de la busqueda de las Ordenes de Pago Ministerio.
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 11/02/2009.								Fecha Última Modificación : 11/02/2009.
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/class_sql.php");		
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");
		
		$io_include   = new sigesp_include();
		$io_mensajes  = new class_mensajes();		
		$io_funciones = new class_funciones();		
		$io_conexion  = $io_include->uf_conectar();
		$io_sql		  = new class_sql($io_conexion);			
		
		$lb_valido = true;
		$ls_sql = "SELECT codemp
		             FROM scb_movbco
					WHERE codemp = '".$_SESSION["la_empresa"]["codemp"]."'
					  AND numordpagmin = '".$as_numordpagmin."'
					  AND codtipfon = '".$as_codtipfon."'";
		$rs_data=$io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
		     $io_mensajes->uf_mensajes_ajax("Class->sigesp_scb_c_catalogo_ajax.php;Metodo->uf_load_monto_consumido","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		     print $io_sql->message;
		   }
		else
		   {
	  	     if ($row=$io_sql->fetch_row($rs_data))
			    {
				  $lb_valido = false;			  
			    }
		   }
		unset($io_include,$io_conexion,$io_sql,$io_mensajes,$io_funciones);
	  return $lb_valido;
	}// end function uf_select_numero_orden_pago.
    //-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_estatus_mes($as_fecmov)
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_estatus_mes
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que imprime el resultado de la busqueda de las Ordenes de Pago Ministerio.
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 14/04/2009.								Fecha Última Modificación : 11/04/2009.
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_scb;
		
		$lb_valido = true;
		$ls_mesmov = substr($as_fecmov,3,2);		
		if (!empty($ls_mesmov))
		   {
		     $ls_nomcol = "m".str_pad($ls_mesmov,2,0,0);
		   }
		$ls_sql = "SELECT $ls_nomcol as estmes
		             FROM sigesp_empresa
					WHERE codemp = '".$_SESSION["la_empresa"]["codemp"]."'";
		$rs_data = $io_funciones_scb->io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
		     $lb_valido = false;
			 $io_mensajes->uf_mensajes_ajax("Class->sigesp_scb_c_catalogo_ajax.php;Metodo->uf_load_monto_consumido","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		     print $io_funciones_scb->io_sql->message;
		   }
		else
		   {
	  	     if ($row=$io_funciones_scb->io_sql->fetch_row($rs_data))
			    {
				  $li_estmes = $row["estmes"];
				  if ($li_estmes<>1)
				     {
					   $lb_valido = false;
					 }
			    }
		   }
	  return $lb_valido;
	}// end function uf_load_estatus_mes.
    //-----------------------------------------------------------------------------------------------------------------------------------
?>