<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}

   //--------------------------------------------------------------
   function uf_print($as_codper, $as_cedper, $as_nomper, $as_apeper, $as_tipo, $ai_subnomina, $as_codnom)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_print
		//	Arguments:    as_codper  // Código de Personal
		//				  as_cedper  // Cédula de Pesonal
		//				  as_nomper  // Nombre de Personal
		//				  as_apeper // Apellido de Personal
		//				  as_tipo  // Tipo de Llamada del catálogo
		//				  ai_subnomina  // si tiene sub nómina=1 ó Nó =0
		//	Description:  Función que obtiene e imprime los resultados de la busqueda
		//////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		require_once("../shared/class_folder/class_fecha.php");
		$io_fecha=new class_fecha();		
		require_once("sigesp_sno.php");
		$io_sno=new sigesp_sno();		
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$li_tipnom=$_SESSION["la_nomina"]["tipnom"];	
		$ls_from=" FROM sno_personalnomina ";
		$ls_criterio="";
		if($as_tipo=="encargado")
		{
			$ls_criterio="   AND sno_personalnomina.codnom = '".$as_codnom."' ";
			$ls_criterio=$ls_criterio."   AND sno_personalnomina.estencper = '0' ";
		}
		elseif($as_tipo=="encargaduria")
		{
			$ls_criterio="   AND sno_personalnomina.codnom = '".$ls_codnom."' ";
			$ls_criterio=$ls_criterio."   AND sno_personalnomina.estencper = '0' ";
		}
		elseif($as_tipo=="catencargaduria2")
		{
			$ls_from=" FROM sno_encargaduria, sno_personalnomina ";
			$ls_criterio=" AND sno_personalnomina.codemp = sno_encargaduria.codemp";
			$ls_criterio=$ls_criterio."   AND sno_personalnomina.codper = sno_encargaduria.codperenc ";
			$ls_criterio=$ls_criterio."   AND sno_personalnomina.codnom = sno_encargaduria.codnomperenc ";
		}
		else
		{
			$ls_criterio="   AND sno_personalnomina.codnom = '".$ls_codnom."' ";
		}

		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60>Código</td>";
		print "<td width=40>Cédula</td>";
		print "<td width=280>Nombre y Apellido</td>";
		print "<td width=60>Estatus</td>";
		print "<td width=60>Culminación Contrato</td>";
		print "</tr>";
		// Sentencia modificada 
		/*$ls_sql="SELECT DISTINCT (sno_personalnomina.codper), sno_personalnomina.codsubnom, sno_personalnomina.codasicar, sno_personalnomina.codtab, ".
				"		sno_personalnomina.codgra, sno_personalnomina.codpas, sno_personalnomina.sueper, sno_personalnomina.horper, ".
				"		sno_personalnomina.minorguniadm, sno_personalnomina.ofiuniadm, sno_personalnomina.uniuniadm, sno_personalnomina.depuniadm, ".
				"		sno_personalnomina.prouniadm, sno_personalnomina.pagbanper, sno_personalnomina.codban, sno_personalnomina.codcueban, ".
				"		sno_personalnomina.tipcuebanper, sno_personalnomina.codcar, sno_personalnomina.fecingper, sno_personalnomina.staper, ".
				"		sno_personalnomina.cueaboper, sno_personalnomina.fecculcontr, sno_personalnomina.codded, sno_personalnomina.codtipper, ".
				"		sno_personalnomina.quivacper, sno_personalnomina.codtabvac, sno_personalnomina.sueintper, sno_personalnomina.pagefeper, ".
				"		sno_personalnomina.sueproper, sno_personalnomina.codage, sno_personalnomina.fecegrper, sno_personalnomina.fecsusper, ".
				"		sno_personalnomina.cauegrper, sno_personalnomina.codescdoc, sno_personalnomina.codcladoc, sno_personalnomina.codubifis, ".
				"		sno_personalnomina.tipcestic, sno_personalnomina.conjub, sno_personalnomina.catjub, sno_personalnomina.codclavia, ".
				"		sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personalnomina.codunirac, sno_personalnomina.pagtaqper, ".
				"		sno_unidadadmin.desuniadm, sno_dedicacion.desded, sno_tipopersonal.destipper, sno_subnomina.dessubnom, sno_personalnomina.grado,".
				"		sno_tablavacacion.dentabvac, sno_escaladocente.desescdoc, sno_clasificaciondocente.descladoc, sno_ubicacionfisica.desubifis, ".
				"		sno_personalnomina.fecascper, sno_personalpension.suebasper, sno_personalpension.priespper, sno_personalpension.pritraper, ".
				"		sno_personalpension.priproper, sno_personalpension.prianoserper, sno_personalpension.pridesper, sno_personalpension.porpenper, sno_personalnomina.descasicar, sno_personalnomina.coddep, ".
				"		sno_personalpension.prinoascper, sno_personalpension.monpenper, sno_personalpension.subtotper, ".
				"		sno_personalpension.tipjub, sno_personalpension.fecvid, sno_personalpension.prirem, sno_personalpension.segrem, sno_personalnomina.salnorper, sno_personalnomina.estencper,  sno_personal.codger, ".
				"       (SELECT srh_departamento.dendep FROM srh_departamento                 ".
				"         WHERE srh_departamento.codemp=sno_personalnomina.codemp             ".
				"           AND srh_departamento.coddep=sno_personalnomina.coddep) AS dendep, ".			
				"		(SELECT descar FROM sno_cargo ".
				"		   WHERE sno_cargo.codemp = sno_personalnomina.codemp ".
				"			 AND sno_cargo.codnom = sno_personalnomina.codnom ".
				"			 AND sno_cargo.codcar = sno_personalnomina.codcar) as descar, ".
				"		(SELECT denasicar FROM sno_asignacioncargo ".
				"		   WHERE sno_asignacioncargo.codemp = sno_personalnomina.codemp ".
				"			 AND sno_asignacioncargo.codnom = sno_personalnomina.codnom ".
				"			 AND sno_asignacioncargo.codasicar = sno_personalnomina.codasicar) as denasicar, ".
				"		(SELECT destab FROM sno_tabulador ".
				"		   WHERE sno_tabulador.codemp = sno_personalnomina.codemp ".
				"			 AND sno_tabulador.codnom = sno_personalnomina.codnom ".
				"			 AND sno_tabulador.codtab = sno_personalnomina.codtab) as destab, ".
				"		(SELECT moncomgra FROM sno_grado ".
				"		  WHERE sno_grado.codemp = sno_personalnomina.codemp ".
				"		    AND sno_grado.codnom = sno_personalnomina.codnom ".
				"		    AND sno_grado.codtab = sno_personalnomina.codtab ".
				"		    AND sno_grado.codpas = sno_personalnomina.codpas ".
				"		    AND sno_grado.codgra = sno_personalnomina.codgra) as compensacion, ".
				"		(SELECT denominacion FROM scg_cuentas ".
				"		   WHERE scg_cuentas.codemp = sno_personalnomina.codemp ".
				"			 AND scg_cuentas.SC_cuenta = sno_personalnomina.cueaboper ".
				"			 AND scg_cuentas.status = 'C') as dencueaboper, ".
				"		(SELECT nomban FROM scb_banco ".
				"		  WHERE scb_banco.codemp = sno_personalnomina.codemp ".
				"			AND scb_banco.codban = sno_personalnomina.codban) as nomban, ".
				"		(SELECT nomage FROM scb_agencias ".
				"		  WHERE scb_agencias.codemp = sno_personalnomina.codemp ".
				"			AND scb_agencias.codban = sno_personalnomina.codban ".
				"			AND scb_agencias.codage = sno_personalnomina.codage) as nomage, ".
				"		(SELECT dencat FROM scv_categorias ".
				"		  WHERE scv_categorias.codemp = sno_personalnomina.codemp ".
				"			AND scv_categorias.codcat = sno_personalnomina.codclavia) as dencat ".$ls_from.
				"  LEFT JOIN sno_personalpension ".
				"	      ON sno_personalnomina.codemp = sno_personalpension.codemp ".
				"        AND sno_personalnomina.codnom = sno_personalpension.codnom ".
				"        AND sno_personalnomina.codper = sno_personalpension.codper,  ".
				"		sno_personal, sno_subnomina, sno_unidadadmin, sno_dedicacion, sno_tipopersonal, ".
				"  		sno_tablavacacion, sno_escaladocente, sno_clasificaciondocente, sno_ubicacionfisica ".
				" WHERE sno_personalnomina.codemp = '".$ls_codemp."'".$ls_criterio.				
				"   AND sno_personal.codper like '".$as_codper."' ".
				"   AND sno_personal.cedper like '".$as_cedper."' ".
				"   AND sno_personal.nomper like '".$as_nomper."' ".
				"   AND sno_personal.apeper like '".$as_apeper."' ".
				"   AND sno_personal.estper = '1' ".     
				"   AND sno_personalnomina.codemp = sno_personal.codemp ".
				"   AND sno_personalnomina.codper = sno_personal.codper ".
				"   AND sno_personalnomina.codemp = sno_subnomina.codemp ".
				"   AND sno_personalnomina.codnom = sno_subnomina.codnom ".
				"	AND sno_personalnomina.codsubnom = sno_subnomina.codsubnom ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND sno_personalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_personalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_personalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_personalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_personalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_personalnomina.codemp = sno_tablavacacion.codemp ".
				"	AND sno_personalnomina.codtabvac = sno_tablavacacion.codtabvac ".
				"   AND sno_personalnomina.codemp = sno_escaladocente.codemp ".
				"	AND sno_personalnomina.codescdoc = sno_escaladocente.codescdoc ".
				"   AND sno_personalnomina.codemp = sno_clasificaciondocente.codemp ".
				"	AND sno_personalnomina.codescdoc = sno_clasificaciondocente.codescdoc ".
				"	AND sno_personalnomina.codcladoc = sno_clasificaciondocente.codcladoc ".
				"   AND sno_personalnomina.codemp = sno_ubicacionfisica.codemp ".
				"	AND sno_personalnomina.codubifis = sno_ubicacionfisica.codubifis "; */

		// Sentencia modificada sin tabulacion, grado, escala docente, subnomina, ni pension 		
		$ls_sql="SELECT DISTINCT (sno_personalnomina.codper), sno_personalnomina.codasicar, ".
				"		sno_personalnomina.sueper, sno_personalnomina.horper, ".
				"		sno_personalnomina.minorguniadm, sno_personalnomina.ofiuniadm, sno_personalnomina.uniuniadm, sno_personalnomina.depuniadm, ".
				"		sno_personalnomina.prouniadm, sno_personalnomina.pagbanper, sno_personalnomina.codban, sno_personalnomina.codcueban, ".
				"		sno_personalnomina.tipcuebanper, sno_personalnomina.codcar, sno_personalnomina.fecingper, sno_personalnomina.staper, ".
				"		sno_personalnomina.cueaboper, sno_personalnomina.fecculcontr, sno_personalnomina.codded, sno_personalnomina.codtipper, ".
				"		sno_personalnomina.quivacper, sno_personalnomina.codtabvac, sno_personalnomina.sueintper, sno_personalnomina.pagefeper, ".
				"		sno_personalnomina.sueproper, sno_personalnomina.codage, sno_personalnomina.fecegrper, sno_personalnomina.fecsusper, ".
				"		sno_personalnomina.cauegrper, sno_personalnomina.codescdoc, sno_personalnomina.codcladoc, sno_personalnomina.codubifis, ".
				"		sno_personalnomina.tipcestic, sno_personalnomina.conjub, sno_personalnomina.catjub, sno_personalnomina.codclavia, ".
				"		sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personalnomina.codunirac, sno_personalnomina.pagtaqper, ".
				"		sno_unidadadmin.desuniadm, sno_dedicacion.desded, sno_tipopersonal.destipper, ".
				"		sno_tablavacacion.dentabvac, sno_ubicacionfisica.desubifis, ".
				"		sno_personalnomina.fecascper, sno_personalnomina.descasicar, sno_personalnomina.coddep, ".
				"		sno_personalnomina.salnorper, sno_personalnomina.estencper,  sno_personal.codger, ".
				"       (SELECT srh_departamento.dendep FROM srh_departamento                 ".
				"         WHERE srh_departamento.codemp=sno_personalnomina.codemp             ".
				"           AND srh_departamento.coddep=sno_personalnomina.coddep) AS dendep, ".			
				"		(SELECT descar FROM sno_cargo ".
				"		   WHERE sno_cargo.codemp = sno_personalnomina.codemp ".
				"			 AND sno_cargo.codnom = sno_personalnomina.codnom ".
				"			 AND sno_cargo.codcar = sno_personalnomina.codcar) as descar, ".
				"		(SELECT denasicar FROM sno_asignacioncargo ".
				"		   WHERE sno_asignacioncargo.codemp = sno_personalnomina.codemp ".
				"			 AND sno_asignacioncargo.codnom = sno_personalnomina.codnom ".
				"			 AND sno_asignacioncargo.codasicar = sno_personalnomina.codasicar) as denasicar, ".
				"		(SELECT denominacion FROM scg_cuentas ".
				"		   WHERE scg_cuentas.codemp = sno_personalnomina.codemp ".
				"			 AND scg_cuentas.SC_cuenta = sno_personalnomina.cueaboper ".
				"			 AND scg_cuentas.status = 'C') as dencueaboper, ".
				"		(SELECT nomban FROM scb_banco ".
				"		  WHERE scb_banco.codemp = sno_personalnomina.codemp ".
				"			AND scb_banco.codban = sno_personalnomina.codban) as nomban, ".
				"		(SELECT nomage FROM scb_agencias ".
				"		  WHERE scb_agencias.codemp = sno_personalnomina.codemp ".
				"			AND scb_agencias.codban = sno_personalnomina.codban ".
				"			AND scb_agencias.codage = sno_personalnomina.codage) as nomage, ".
				"		(SELECT dencat FROM scv_categorias ".
				"		  WHERE scv_categorias.codemp = sno_personalnomina.codemp ".
				"			AND scv_categorias.codcat = sno_personalnomina.codclavia) as dencat ".
				"  FROM  sno_personalnomina, sno_personal, sno_unidadadmin, ".
				"		sno_dedicacion, sno_tipopersonal, sno_tablavacacion, sno_ubicacionfisica ".
				" WHERE sno_personalnomina.codemp = '".$ls_codemp."'".$ls_criterio.				
				"   AND sno_personal.codper like '".$as_codper."' ".
				"   AND sno_personal.cedper like '".$as_cedper."' ".
				"   AND sno_personal.nomper like '".$as_nomper."' ".
				"   AND sno_personal.apeper like '".$as_apeper."' ".
				"   AND sno_personal.estper = '1' ".     
				"   AND sno_personalnomina.codemp = sno_personal.codemp ".
				"   AND sno_personalnomina.codper = sno_personal.codper ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND sno_personalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_personalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_personalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_personalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_personalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_personalnomina.codemp = sno_tablavacacion.codemp ".
				"	AND sno_personalnomina.codtabvac = sno_tablavacacion.codtabvac ".
				"   AND sno_personalnomina.codemp = sno_ubicacionfisica.codemp ".
				"	AND sno_personalnomina.codubifis = sno_ubicacionfisica.codubifis ";
						
		if(($as_tipo=="prestamo")||($as_tipo=="movimientonominas")||($as_tipo=="vacaciondes")||
		   ($as_tipo=="vacacionhas")||($as_tipo=="personaproyecto"))
		{
			// solo para el personal Activo
			$ls_sql=$ls_sql."	AND sno_personalnomina.staper = '1' ";
		}		
		elseif (($as_tipo=="encargaduria")||($as_tipo=="encargado"))
		{
			$ls_sql=$ls_sql."	AND sno_personalnomina.staper <> '3'  ";
		}
		$ls_sql=$ls_sql." ORDER BY sno_personalnomina.codper ";
		//print $ls_sql;
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while(!$rs_data->EOF)
			{
				$ls_codper=$rs_data->fields["codper"];
				$ls_cedper=$rs_data->fields["cedper"];
				$ls_nomper=$rs_data->fields["nomper"]." ".$rs_data->fields["apeper"];
				$ls_estper=$rs_data->fields["staper"];
				$ls_codsubnom=$rs_data->fields["codsubnom"];
				$ls_dessubnom=$rs_data->fields["dessubnom"];
				$ls_codasicar=$rs_data->fields["codasicar"];
				$ls_denasicar=$rs_data->fields["denasicar"];
				$ls_codcar=$rs_data->fields["codcar"];
				$ls_descar=$rs_data->fields["descar"];
				$ls_codtab=$rs_data->fields["codtab"];
				$ls_destab=$rs_data->fields["destab"];
				$ls_codgra=$rs_data->fields["codgra"];
				$ls_codpas=$rs_data->fields["codpas"];
				$li_sueper=$rs_data->fields["sueper"];			
				$li_sueper=$io_fun_nomina->uf_formatonumerico($li_sueper);
				$li_compensacion=$rs_data->fields["compensacion"];			
				$li_compensacion=$io_fun_nomina->uf_formatonumerico($li_compensacion);
				$li_horper=$rs_data->fields["horper"];			
				$li_horper=$io_fun_nomina->uf_formatonumerico($li_horper);
				$li_sueintper=$rs_data->fields["sueintper"];			
				$li_sueintper=$io_fun_nomina->uf_formatonumerico($li_sueintper);				
				$li_salnorper=$rs_data->fields["salnorper"];			
				$li_salnorper=$io_fun_nomina->uf_formatonumerico($li_salnorper);				
				$li_sueproper=$rs_data->fields["sueproper"];			
				$li_sueproper=$io_fun_nomina->uf_formatonumerico($li_sueproper);
				$ld_fecingper=$io_funciones->uf_formatovalidofecha($rs_data->fields["fecingper"]);				
				$ld_fecculcontr=$io_funciones->uf_formatovalidofecha($rs_data->fields["fecculcontr"]);				
				$ld_fecascper=$io_funciones->uf_formatovalidofecha($rs_data->fields["fecascper"]);				
				$ld_fecingper=$io_funciones->uf_convertirfecmostrar($ld_fecingper);				
				$ld_fecculcontr=$io_funciones->uf_convertirfecmostrar($ld_fecculcontr);				
				$ld_fecascper=$io_funciones->uf_convertirfecmostrar($rs_data->fields["fecascper"]);				
				$ls_coduniadm=$rs_data->fields["minorguniadm"]."-".$rs_data->fields["ofiuniadm"]."-".$rs_data->fields["uniuniadm"]."-".$rs_data->fields["depuniadm"]."-".$rs_data->fields["prouniadm"];			
				$ls_desuniadm=$rs_data->fields["desuniadm"];
				$ls_codded=$rs_data->fields["codded"];
				$ls_desded=$rs_data->fields["desded"];
				$ls_codtipper=$rs_data->fields["codtipper"];
				$ls_destipper=$rs_data->fields["destipper"];
				$ls_codtabvac=$rs_data->fields["codtabvac"];
				$ls_dentabvac=$rs_data->fields["dentabvac"];
				$li_pagefeper=$rs_data->fields["pagefeper"];
				$li_pagbanper=$rs_data->fields["pagbanper"];
				$li_pagtaqper=$rs_data->fields["pagtaqper"];
				$ls_codban=$rs_data->fields["codban"];
				$ls_codage=$rs_data->fields["codage"];
				$ls_codcueban=$rs_data->fields["codcueban"];
				$ls_tipcuebanper=$rs_data->fields["tipcuebanper"];
				$ls_tipcestic=$rs_data->fields["tipcestic"];
				$ls_codescdoc=$rs_data->fields["codescdoc"];
				$ls_desescdoc=$rs_data->fields["desescdoc"];
				$ls_codcladoc=$rs_data->fields["codcladoc"];
				$ls_descladoc=$rs_data->fields["descladoc"];
				$ls_codubifis=$rs_data->fields["codubifis"];
				$ls_desubifis=$rs_data->fields["desubifis"];
				$ls_cueaboper=$rs_data->fields["cueaboper"];
				$ls_dencueaboper=$rs_data->fields["dencueaboper"];
				$ls_nomban=$rs_data->fields["nomban"];
				$ls_nomage=$rs_data->fields["nomage"];
				$ls_conjub=$rs_data->fields["conjub"];
				$ls_catjub=$rs_data->fields["catjub"];
				$ls_dencat=$rs_data->fields["dencat"];
				$ls_codclavia=$rs_data->fields["codclavia"];
				$ls_codunirac=$rs_data->fields["codunirac"];
				$ls_grado=$rs_data->fields["grado"];
				$ls_descasicar=$rs_data->fields["descasicar"];
				$li_suebasper=$io_fun_nomina->uf_formatonumerico($rs_data->fields["suebasper"]);
				$li_priespper=$io_fun_nomina->uf_formatonumerico($rs_data->fields["priespper"]);
				$li_pritraper=$io_fun_nomina->uf_formatonumerico($rs_data->fields["pritraper"]);
				$li_priproper=$io_fun_nomina->uf_formatonumerico($rs_data->fields["priproper"]);
				$li_prianoserper=$io_fun_nomina->uf_formatonumerico($rs_data->fields["prianoserper"]);
				$li_pridesper=$io_fun_nomina->uf_formatonumerico($rs_data->fields["pridesper"]);
				$li_porpenper=$io_fun_nomina->uf_formatonumerico($rs_data->fields["porpenper"]);
				$li_prinoascper=$io_fun_nomina->uf_formatonumerico($rs_data->fields["prinoascper"]);
				$li_monpenper=$io_fun_nomina->uf_formatonumerico($rs_data->fields["monpenper"]);
				$li_subtotper=$io_fun_nomina->uf_formatonumerico($rs_data->fields["subtotper"]);
				$ls_coddep=$rs_data->fields["coddep"];
				$ls_dendep=$rs_data->fields["dendep"];
				$ls_tippen=$rs_data->fields["tipjub"];	
				$ls_fecvi=$io_funciones->uf_convertirfecmostrar($rs_data->fields["fecvid"]);
				$ls_prirem=$io_fun_nomina->uf_formatonumerico($rs_data->fields["prirem"]);	
				$ls_segrem=$io_fun_nomina->uf_formatonumerico($rs_data->fields["segrem"]);				
				$ls_estencper=$rs_data->fields["estencper"];
				$ld_fecegrper=$io_funciones->uf_convertirfecmostrar($rs_data->fields["fecegrper"]);
				$ld_fecsusper=$io_funciones->uf_convertirfecmostrar($rs_data->fields["fecsusper"]);
				$ls_obsegrper=trim($rs_data->fields["cauegrper"]);
				$ls_codger=trim($rs_data->fields["codger"]);
				switch ($ls_estper)
				{
					case "0":
						$ls_estper="N/A";
						break;
					
					case "1":
						$ls_estper="Activo";
						break;
					
					case "2":
						$ls_estper="Vacaciones";
						break;
						
					case "3":
						$ls_estper="Egresado";
						break;
	
					case "4":
						$ls_estper="Suspendido";
						break;
				}
				$ls_contrato="";
				$ls_clase="";
				if(substr($rs_data->fields["fecculcontr"],0,10)=="1900-01-01")
				{
					$ls_contrato="NO APLICA";
				}
				else
				{
					$ld_feccontrato=$rs_data->fields["fecculcontr"];
					$ld_fecdesper=$_SESSION["la_nomina"]["fecdesper"];
					$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
					$li_incremento=0;
					switch($_SESSION["la_nomina"]["tippernom"])
					{
						case 0://Semanal
							$li_incremento=7;
							break;
			
						case 1://Quincenal
							$li_incremento=15;
							break;
			
						case 2://Mensual
							$li_incremento=30;
							break;
			
						case 3://Anual
							$li_incremento=365;
							break;
					}
					$ld_fechafinal=$io_sno->uf_suma_fechas($ld_fechasper,$li_incremento);
					if($io_fecha->uf_comparar_fecha($ld_fecdesper,$ld_feccontrato))
					{
						if($io_fecha->uf_comparar_fecha($ld_feccontrato,$ld_fechafinal))
						{
							$ls_contrato=$io_funciones->uf_convertirfecmostrar($rs_data->fields["fecculcontr"]);
							$ls_clase="class=texto-rojo";
						}
						else
						{
							$ld_fechafinal=$io_funciones->uf_convertirfecmostrar($ld_fechafinal);
							$ld_fechafinal=$io_sno->uf_suma_fechas($ld_fechafinal,$li_incremento);
							if($io_fecha->uf_comparar_fecha($ld_feccontrato,$ld_fechafinal))
							{
								$ls_contrato=$io_funciones->uf_convertirfecmostrar($rs_data->fields["fecculcontr"]);
								$ls_clase="class=texto-azul";
							}
							else
							{
								$ls_contrato=$io_funciones->uf_convertirfecmostrar($rs_data->fields["fecculcontr"]);
							}
						}
					}
				}
				switch ($as_tipo)
				{
					case "": // el llamado se hace desde sigesp_sno_d_personalnomina.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codper','$ls_nomper','$ls_estper','$ls_codasicar','$ls_denasicar',";
						print "'$ls_codcar','$ls_descar','$ls_codtab','$ls_destab','$ls_codgra','$ls_codpas',";
						print "'$li_sueper','$li_horper','$li_sueintper','$li_sueproper','$ld_fecingper','$ld_fecculcontr','$ls_coduniadm',";
						print "'$ls_desuniadm','$ls_codded','$ls_desded','$ls_codtipper','$ls_destipper','$ls_codtabvac','$ls_dentabvac',";
						print "'$li_pagefeper','$li_pagbanper','$ls_codsubnom','$ls_dessubnom','$ls_codban','$ls_codage','$ls_codcueban',";
						print "'$ls_tipcuebanper','$ls_tipcestic','$ls_codescdoc','$ls_codcladoc','$ls_codubifis','$ls_cueaboper',";
						print "'$ls_dencueaboper','$ls_nomban','$ls_nomage','$ls_desescdoc','$ls_descladoc','$ls_desubifis',";
						print "'$ai_subnomina','$li_tipnom','$ls_conjub','$ls_catjub','$ls_codclavia','$ls_dencat','$ls_codunirac','$li_pagtaqper',";
						print "'$li_compensacion','$ld_fecascper','$ls_grado','$li_suebasper','$li_priespper','$li_pritraper','$li_priproper',";
						print "'$li_prianoserper','$li_pridesper','$li_porpenper','$li_prinoascper','$li_monpenper','$li_subtotper','$ls_descasicar','$ls_coddep','$ls_dendep','$ls_tippen','$ls_fecvi','$ls_prirem','$ls_segrem','$li_salnorper','$ls_estencper','$ld_fecegrper','$ld_fecsusper','$ls_obsegrper','$ls_codger');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;						
	
					case "nomina": // el llamado se hace desde sigesp_sno_d_personalnomina.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarnomina('$ls_codper','$ls_nomper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "prestamo": // el llamado se hace desde sigesp_sno_p_prestamo.php
						$ld_sueper=$rs_data->fields["sueper"];
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarprestamo('$ls_codper','$ls_nomper','$ld_sueper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "catprestamo": // el llamado se hace desde sigesp_sno_cat_prestamo.php
						$ld_sueper=$rs_data->fields["sueper"];
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarcatprestamo('$ls_codper','$ls_nomper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "reppagnomdes": // el llamado se hace desde sigesp_sno_r_pagonomina.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreppagnomdes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "reppagnomhas": // el llamado se hace desde sigesp_sno_r_pagonomina.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreppagnomhas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "cambioestatus": // el llamado se hace desde sigesp_sno_p_personalcambioestatus.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarcambioestatus('$ls_codper','$ls_nomper','$ls_estper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "prenominades": // el llamado se hace desde sigesp_sno_p_calcularprenomina.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarprenominades('$ls_codper','$ls_nomper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "prenominahas": // el llamado se hace desde sigesp_sno_p_calcularprenomina.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarprenominahas('$ls_codper','$ls_nomper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "movimientonominas": // el llamado se hace desde sigesp_sno_p_movimientonominas.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarmovimientonominas('$ls_codper','$ls_nomper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;

					case "vacaciondes": // el llamado se hace desde sigesp_sno_p_vacacionvencida.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarvacaciondes('$ls_codper','$ls_nomper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "vacacionhas": // el llamado se hace desde sigesp_sno_p_vacacionvencida.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarvacacionhas('$ls_codper','$ls_nomper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;

					case "catvacacion": // el llamado se hace desde sigesp_sno_cat_vacacionprogramar.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarcatvacacion('$ls_codper','$ls_nomper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;	
	
					case "repprenomdes": // el llamado se hace desde sigesp_sno_r_prenomina.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepprenomdes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "repprenomhas": // el llamado se hace desde sigesp_sno_r_prenomina.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepprenomhas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "reprecpagdes": // el llamado se hace desde sigesp_sno_r_recibopago.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreprecpagdes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "reprecpaghas": // el llamado se hace desde sigesp_sno_r_recibopago.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreprecpaghas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "replisfirdes": // el llamado se hace desde sigesp_sno_r_listadofirmas.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisfirdes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "replisfirhas": // el llamado se hace desde sigesp_sno_r_listadofirmas.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisfirhas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "reppredes": // el llamado se hace desde sigesp_sno_r_listadoprestamo.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreppredes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "repprehas": // el llamado se hace desde sigesp_sno_r_listadoprestamo.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepprehas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "repdetpredes": // el llamado se hace desde sigesp_sno_r_listadoprestamo.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepdetpredes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "repdetprehas": // el llamado se hace desde sigesp_sno_r_listadoprestamo.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepdetprehas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "personaproyecto": // el llamado se hace desde sigesp_sno_d_personaproyecto.php
						$ld_sueper=$rs_data->fields["sueper"];
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarpersonaproyecto('$ls_codper','$ls_nomper','$ls_desuniadm');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
					
					case "personalprima": // el llamado se hace desde sigesp_sno_d_personaproyecto.php
						$ld_sueper=$rs_data->fields["sueper"];
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarpersonalprima('$ls_codper','$ls_nomper','$ls_desuniadm');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "replisprodes": // el llamado se hace desde sigesp_sno_r_listadoproyectospersonal.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisprodes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "replisprohas": // el llamado se hace desde sigesp_sno_r_listadoproyectospersonal.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisprohas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "replisbendes": // el llamado se hace desde sigesp_sno_r_listadobeneficiario.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisbendes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
	
					case "replisbenhas": // el llamado se hace desde sigesp_sno_r_listadobeneficiario.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisbenhas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
						
					case "encargaduria": // el llamado se hace desde sigesp_sno_p_registrarencargaduria.php
						print "<tr class=celdas-blancas>";						
						print "<td><a href=\"javascript: aceptarencargaduria('$ls_codper','$ls_nomper','$ls_estper','$ls_codasicar','$ls_denasicar',";
						print "'$ls_codcar','$ls_descar','$ls_codtab','$ls_destab','$ls_codgra','$ls_codpas',";
						print "'$ls_coduniadm',  '$ls_desuniadm','$ls_codsubnom','$li_tipnom','$ls_codunirac', ";
						print "'$ls_grado', '$ls_coddep', '$ls_dendep');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;	
						
						case "encargado": // el llamado se hace desde sigesp_sno_p_registrarencargaduria.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarencargado('$ls_codper','$ls_nomper','$ls_estper','$ls_codasicar','$ls_denasicar',";
						print "'$ls_codcar','$ls_descar','$ls_codtab','$ls_destab','$ls_codgra','$ls_codpas',";
						print "'$ls_coduniadm',  '$ls_desuniadm','$ls_codsubnom','$li_tipnom','$ls_codunirac', ";
						print "'$ls_grado', '$ls_coddep', '$ls_dendep');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;	
						
						case "catencargaduria1": // el llamado se hace desde sigesp_sno_cat_registroencargaduria.php
						$ld_sueper=$rs_data->fields["sueper"];
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarcatencargaduria1('$ls_codper','$ls_nomper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
						
						case "catencargaduria2": // el llamado se hace desde sigesp_sno_cat_registroencargaduria.php
						                         // para el personal encargado
						$ld_sueper=$rs_data->fields["sueper"];
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarcatencargaduria2('$ls_codper','$ls_nomper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_estper."</td>";
						print "<td ".$ls_clase.">".$ls_contrato."</td>";
						print "</tr>";			
						break;
						
				}
				$rs_data->MoveNext();
			}
			$io_sql->free_result($rs_data);
		}
		print "</table>";
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
		unset($ls_codnom);
		unset($io_fecha);
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Personal N&oacute;mina</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style>
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Personal N&oacute;mina </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
            <input name="txtcodper" type="text" id="txtcodper" size="30" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">C&eacute;dula</div></td>
        <td><div align="left">
          <input name="txtcedper" type="text" id="txtcedper" size="30" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td><div align="left">
          <input name="txtnomper" type="text" id="txtnomper" size="30" maxlength="60" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Apellido</div></td>
        <td><div align="left">
            <input name="txtapeper" type="text" id="txtapeper" size="30" maxlength="60" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" title='Buscar' alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
  <br>
<?php
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_operacion =$io_fun_nomina->uf_obteneroperacion();
	$ls_tipo=$io_fun_nomina->uf_obtenertipo();
	$li_subnomina=$io_fun_nomina->uf_obtenervalor_get("subnom","0");
	$ls_codnom=$io_fun_nomina->uf_obtenervalor_get("codnom","");
	if($ls_operacion=="BUSCAR")
	{
		$ls_codper="%".$_POST["txtcodper"]."%";
		$ls_cedper="%".$_POST["txtcedper"]."%";
		$ls_nomper="%".$_POST["txtnomper"]."%";
		$ls_apeper="%".$_POST["txtapeper"]."%";

		uf_print($ls_codper, $ls_cedper, $ls_nomper, $ls_apeper, $ls_tipo, $li_subnomina, $ls_codnom);
	}
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codper,nomper,estper,codasicar,denasicar,codcar,descar,codtab,destab,codgra,codpas,
				 sueper,horper,sueintper,sueproper,fecingper,fecculcontr,coduniadm,desuniadm,codded,desded,codtipper,
				 destipper,codtabvac,dentabvac,pagefeper,pagbanper,codsubnom,dessubnom,codban,codage,codcueban,tipcuebanper,
				 tipcestic,codescdoc,codcladoc,codubifis,cueaboper,dencueaboper,nomban,nomage,desescdoc,descladoc,desubifis,
				 subnomina,tipnom,conjub,catjub,codclavia,dencat,codunirac,pagtaqper,compensacion,fecascper,grado,suebasper,
				 priespper,pritraper,priproper,prianoserper,pridesper,porpenper,prinoascper,monpenper,subtotper,descasicar,
				 coddep, dendep, tippen, fecvid, prirem, segrem,salnorper, estencper,fecegrper,fecsusper,obsegrper, codger)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
	opener.document.images["personal"].style.visibility="hidden";
    opener.document.form1.txtnomper.value=nomper;
    opener.document.form1.txtestper.value=estper;
	opener.document.form1.hidcodger.value=codger;
	if(opener.document.form1.rac.value=="0")
	{
    	opener.document.form1.txtcodcar.value=codcar;
    	opener.document.form1.txtdescar.value=descar;		
		if ((tipnom=="3")||(tipnom=="4"))
		{
			opener.document.form1.txtgrado.value=grado;
		}
	}
	else
	{
		if ((tipnom!="3")&&(tipnom!="4"))
		{
			opener.document.form1.txtcodtab.value=codtab;
			opener.document.form1.txtdestab.value=destab;
			opener.document.form1.txtcodgra.value=codgra;
			opener.document.form1.txtcodpas.value=codpas;
			opener.document.form1.txtcodasicar.value=codasicar;
		    opener.document.form1.txtdenasicar.value=denasicar;
		    opener.document.form1.txtdescasicar.value=descasicar; //denomiancion de la asiganción del cargo
		}
		else
		{
			opener.document.form1.txtgrado.value=grado;
			opener.document.form1.txtcodasicar.value=codasicar;
		    opener.document.form1.txtdenasicar.value=denasicar;
		}
	}
	if(tipnom=="7") // Jubilados
	{
		opener.document.form1.cmbconjub.value=conjub;
		opener.document.form1.cmbcatjub.value=catjub;
	}
	if(tipnom=="12") // Pensionados
	{
		opener.document.form1.txtsuebasper.value=suebasper;
		opener.document.form1.txtpriespper.value=priespper;
		opener.document.form1.txtpritraper.value=pritraper;
		opener.document.form1.txtpriproper.value=priproper;
		opener.document.form1.txtprianoserper.value=prianoserper;
		opener.document.form1.txtpridesper.value=pridesper;
		opener.document.form1.txtporpenper.value=porpenper;
		opener.document.form1.txtprinoascper.value=prinoascper;
		opener.document.form1.txtmonpenper.value=monpenper;
		opener.document.form1.txtsubtotper.value=subtotper;
		
		opener.document.form1.txtprimrem.value=prirem;
		opener.document.form1.txtsegrem.value=segrem;
		opener.document.form1.txtfecvid.value=fecvid;
		opener.document.form1.cmbtippen.value=tippen;

	}
	opener.document.form1.txtsueper.value=sueper;
	opener.document.form1.txtsalnorper.value=salnorper;
	opener.document.form1.txtcompensacion.value=compensacion;
    opener.document.form1.txthorper.value=horper;
    opener.document.form1.txtsueintper.value=sueintper;
    opener.document.form1.txtsueproper.value=sueproper;
    opener.document.form1.txtfecingper.value=fecingper;
    opener.document.form1.txtfecculcontr.value=fecculcontr;
    opener.document.form1.txtcoduniadm.value=coduniadm;
    opener.document.form1.txtdesuniadm.value=desuniadm;
	
	opener.document.form1.txtfecegrper.value=fecegrper;
	opener.document.form1.txtfecsusper.value=fecsusper;
	opener.document.form1.txtobsegrper.value=obsegrper;
	
	opener.document.form1.txtcoddep.value=coddep;
    opener.document.form1.txtdendep.value=dendep;
	
    opener.document.form1.txtcodded.value=codded;
    opener.document.form1.txtdesded.value=desded;
    opener.document.form1.txtcodtipper.value=codtipper;
    opener.document.form1.txtdestipper.value=destipper;
    opener.document.form1.txtcodtabvac.value=codtabvac;
    opener.document.form1.txtdentabvac.value=dentabvac;
    opener.document.form1.txtfecascper.value=fecascper;
	if(subnomina==1)
	{
    	opener.document.form1.txtcodsubnom.value=codsubnom;
    	opener.document.form1.txtdessubnom.value=dessubnom;
	}
    opener.document.form1.txtcodban.value=codban;
    opener.document.form1.txtcodage.value=codage;
    opener.document.form1.txtcodcueban.value=codcueban;
    opener.document.form1.txtcodescdoc.value=codescdoc;
    opener.document.form1.txtdesescdoc.value=desescdoc;
    opener.document.form1.txtcodcladoc.value=codcladoc;
    opener.document.form1.txtdescladoc.value=descladoc;
    opener.document.form1.txtcodubifis.value=codubifis;
    opener.document.form1.txtdesubifis.value=desubifis;
    opener.document.form1.txttipcuebanper.value=tipcuebanper;
    opener.document.form1.cmbtipcuebanper.value=tipcuebanper;
    opener.document.form1.cmbtipcestic.value=tipcestic;
    opener.document.form1.txtcuecon.value=cueaboper;
    opener.document.form1.txtdencuecon.value=dencueaboper;
    opener.document.form1.txtnomban.value=nomban;
	if((opener.document.form1.rac.value=="1")&&(opener.document.form1.codunirac.value=="1"))
	{
	    opener.document.form1.txtcodunirac.value=codunirac;
	}
    opener.document.form1.txtnomage.value=nomage;
	opener.document.form1.txtcodclavia.value=codclavia;
	opener.document.form1.txtcodclavia.readOnly=true;
	opener.document.form1.txtdencat.value=dencat;
	opener.document.form1.txtdencat.readOnly=true;
	opener.document.form1.chkpagefeper.checked=false;
	opener.document.form1.chkpagtaqper.checked=false;
	opener.document.form1.chkpagbanper.checked=false;
	opener.document.images["cuentaabono"].style.visibility="hidden";
	opener.document.form1.cmbtipcuebanper.disabled=true;
	opener.document.form1.txtcodcueban.readOnly=true;
	opener.document.images["banco"].style.visibility="hidden";
	opener.document.images["agencia"].style.visibility="hidden";
	opener.document.images["cuentaabono"].style.visibility="hidden";
	if(pagefeper=="1")
	{
		opener.document.form1.chkpagefeper.checked=true;
		opener.document.images["cuentaabono"].style.visibility="visible";
	}
	if(pagbanper=="1")
	{
		opener.document.form1.chkpagbanper.checked=true;
		opener.document.form1.cmbtipcuebanper.disabled=false;
		opener.document.form1.txtcodcueban.readOnly=false;
		opener.document.images["banco"].style.visibility="visible";
		opener.document.images["agencia"].style.visibility="visible";
		opener.document.images["cuentaabono"].style.visibility="hidden";
	}
	if(pagtaqper=="1")
	{
		opener.document.form1.chkpagtaqper.checked=true;
		opener.document.images["banco"].style.visibility="visible";
	}
	if(estencper=="1")
	{
		opener.document.form1.txtestencper.value="EN ENCARGADURIA";
	}
	else
	{
		opener.document.form1.txtestencper.value="";
	}
	opener.document.form1.existe.value="TRUE";		
	close();
}

function aceptarnomina(codper,nomper)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
    opener.document.form1.txtnomper.value=nomper;
	opener.document.form1.txtnomper.readOnly=true;
	close();
}

function aceptarprestamo(codper,nomper,sueper)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
    opener.document.form1.txtnomper.value=nomper;
	opener.document.form1.txtnomper.readOnly=true;
    opener.document.form1.txtsueper.value=sueper;
	close();
}

function aceptarcatprestamo(codper,nomper)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
    opener.document.form1.txtnomper.value=nomper;
	opener.document.form1.txtnomper.readOnly=true;
	close();
}

function aceptarreppagnomdes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarreppagnomhas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("El rango que esta seleccionando es Inválido");
	}
}

function aceptarcambioestatus(codper,nomper,estper)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
    opener.document.form1.txtnomper.value=nomper;
	opener.document.form1.txtnomper.readOnly=true;
    opener.document.form1.txtestactper.value=estper;
	opener.document.form1.txtestactper.readOnly=true;
	close();
}

function aceptarprenominades(codper,nomper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
    opener.document.form1.txtnomperdes.value=nomper;
	opener.document.form1.txtnomperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
    opener.document.form1.txtnomperhas.value="";
	close();
}

function aceptarprenominahas(codper,nomper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		opener.document.form1.txtnomperhas.value=nomper;
		opener.document.form1.txtnomperhas.readOnly=true;
		opener.document.form1.operacion.value="BUSCAR";
		opener.document.form1.action="sigesp_sno_p_calcularprenomina.php";
		opener.document.form1.submit();
		close();
	}
	else
	{
		alert("El rango que esta seleccionando es Inválido");
	}
}

function aceptarmovimientonominas(codper,nomper)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
    opener.document.form1.txtnomper.value=nomper;
	opener.document.form1.txtnomper.readOnly=true;
	opener.document.form1.operacion.value="BUSCAR";
	opener.document.form1.action="sigesp_sno_p_movimientonominas.php";
	opener.document.form1.submit();
	close();
}

function aceptarvacaciondes(codper,nomper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
    opener.document.form1.txtnomperdes.value=nomper;
	opener.document.form1.txtnomperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
    opener.document.form1.txtnomperhas.value="";
	close();
}

function aceptarvacacionhas(codper,nomper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		opener.document.form1.txtnomperhas.value=nomper;
		opener.document.form1.txtnomperhas.readOnly=true;
		opener.document.form1.operacion.value="BUSCAR";
		opener.document.form1.action="sigesp_sno_p_vacacionvencida.php";
		opener.document.form1.submit();
		close();
	}
	else
	{
		alert("El rango que esta seleccionando es Inválido");
	}
}

function aceptarcatvacacion(codper,nomper)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
    opener.document.form1.txtnomper.value=nomper;
	opener.document.form1.txtnomper.readOnly=true;
	close();
}

function aceptarrepprenomdes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarrepprenomhas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del Personal inválido");
	}
}

function aceptarreprecpagdes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarreprecpaghas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del Personal inválido");
	}
}

function aceptarreplisfirdes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarreplisfirhas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del Personal inválido");
	}
}

function aceptarreppredes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarrepprehas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del Personal inválido");
	}
}

function aceptarrepdetpredes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarrepdetprehas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del Personal inválido");
	}
}

function aceptarpersonaproyecto(codper,nomper,desuniadm)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
    opener.document.form1.txtnomper.value=nomper;
	opener.document.form1.txtnomper.readOnly=true;
    opener.document.form1.txtuniadm.value=desuniadm;
	opener.document.form1.txtuniadm.readOnly=true;
	opener.document.form1.operacion.value="BUSCARDETALLE";
	opener.document.form1.action="sigesp_sno_d_personaproyecto.php";
	opener.document.form1.submit();
	close();
}

function aceptarpersonalprima(codper,nomper,desuniadm)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
    opener.document.form1.txtnomper.value=nomper;
	opener.document.form1.txtnomper.readOnly=true;
    opener.document.form1.txtuniadm.value=desuniadm;
	opener.document.form1.txtuniadm.readOnly=true;
	opener.document.form1.operacion.value="BUSCARDETALLE";
	opener.document.form1.action="sigesp_sno_d_primadocpersonal.php";
	opener.document.form1.submit();
	close();
}

function aceptarreplisprodes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarreplisprohas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del Personal inválido");
	}
}

function aceptarreplisbendes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarreplisbenhas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del Personal inválido");
	}
}

function aceptarencargaduria(codper,nomper,estper,codasicar,denasicar,codcar,descar,codtab,destab,codgra,codpas,
				 coduniadm,desuniadm,subnomina,tipnom,codunirac,grado,coddep, dendep)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
	opener.document.form1.txtnomper.value=nomper;
    opener.document.form1.txtestper.value=estper;	
	if(opener.document.form1.rac.value=="0")
	{
    	opener.document.form1.txtcodcar.value=codcar;
    	opener.document.form1.txtdescar.value=descar;		
		if ((tipnom=="3")||(tipnom=="4"))
		{
			opener.document.form1.txtgrado.value=grado;
		}
	}
	else
	{
		if ((tipnom!="3")&&(tipnom!="4"))
		{
			opener.document.form1.txtcodtab.value=codtab;
			opener.document.form1.txtdestab.value=destab;
			opener.document.form1.txtcodgra.value=codgra;
			opener.document.form1.txtcodpas.value=codpas;
			opener.document.form1.txtcodasicar.value=codasicar;
		    opener.document.form1.txtdenasicar.value=denasicar;
		}
		else
		{
			opener.document.form1.txtgrado.value=grado;
			opener.document.form1.txtcodasicar.value=codasicar;
		    opener.document.form1.txtdenasicar.value=denasicar;
		}
	}	
    opener.document.form1.txtcoduniadm.value=coduniadm;
    opener.document.form1.txtdesuniadm.value=desuniadm;	
	opener.document.form1.txtcoddep.value=coddep;
    opener.document.form1.txtdendep.value=dendep;   
	if(subnomina==1)
	{
    	opener.document.form1.txtcodsubnom.value=codsubnom;
    	opener.document.form1.txtdessubnom.value=dessubnom;
	}
    
	if((opener.document.form1.rac.value=="1")&&(opener.document.form1.codunirac.value=="1"))
	{
	    opener.document.form1.txtcodunirac.value=codunirac;
	}    
	close();
}


function aceptarencargado(codper,nomper,estper,codasicar,denasicar,codcar,descar,codtab,destab,codgra,codpas,
				 coduniadm,desuniadm,subnomina,tipnom,codunirac,grado,coddep, dendep)
{
	opener.document.form1.txtcodperenc.value=codper;
	opener.document.form1.txtcodperenc.readOnly=true;
	opener.document.form1.txtnomperenc.value=nomper;
    opener.document.form1.txtestperenc.value=estper;
	tipnom=opener.document.form1.tipnomenc.value;
	if(opener.document.form1.racenc.value=="0")
	{
    	opener.document.form1.txtcodcarenc.value=codcar;
    	opener.document.form1.txtdescarenc.value=descar;		
		if ((tipnom=="3")||(tipnom=="4"))
		{
			opener.document.form1.txtgradoenc.value=grado;
		}
	}
	else
	{
		if ((tipnom!="3")&&(tipnom!="4"))
		{
			opener.document.form1.txtcodtabenc.value=codtab;
			opener.document.form1.txtdestabenc.value=destab;
			opener.document.form1.txtcodgraenc.value=codgra;
			opener.document.form1.txtcodpasenc.value=codpas;
			opener.document.form1.txtcodasicarenc.value=codasicar;
		    opener.document.form1.txtdenasicarenc.value=denasicar;
		    
		}
		else
		{
			opener.document.form1.txtgradoenc.value=grado;
			opener.document.form1.txtcodasicarenc.value=codasicar;
		    opener.document.form1.txtdenasicarenc.value=denasicar;
		}
	}	
    opener.document.form1.txtcoduniadmenc.value=coduniadm;
    opener.document.form1.txtdesuniadmenc.value=desuniadm;	
	opener.document.form1.txtcoddepenc.value=coddep;
    opener.document.form1.txtdendepenc.value=dendep;   
	if(subnomina==1)
	{
    	opener.document.form1.txtcodsubnomenc.value=codsubnom;
    	opener.document.form1.txtdessubnomenc.value=dessubnom;
	}
    
	if((opener.document.form1.rac.value=="1")&&(opener.document.form1.codunirac.value=="1"))
	{
	    opener.document.form1.txtcoduniracenc.value=codunirac;
	}    
	close();
}


function aceptarcatencargaduria1(codper,nomper)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
    opener.document.form1.txtnomper.value=nomper;
	opener.document.form1.txtnomper.readOnly=true;
	close();
}

function aceptarcatencargaduria2(codper,nomper)
{
	opener.document.form1.txtcodperenc.value=codper;
	opener.document.form1.txtcodperenc.readOnly=true;
    opener.document.form1.txtnomperenc.value=nomper;
	opener.document.form1.txtnomperenc.readOnly=true;
	close();
}

function ue_mostrar(myfield,e)
{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	if (keycode == 13)
	{
		ue_search();
		return false;
	}
	else
		return true
}

function ue_search()
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_sno_cat_personalnomina.php?tipo=<?PHP print $ls_tipo;?>&subnom=<?PHP print $li_subnomina;?>&codnom=<?PHP print $ls_codnom;?>";
  	f.submit();
}
</script>
</html>