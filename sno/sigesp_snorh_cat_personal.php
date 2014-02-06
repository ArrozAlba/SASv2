<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print($as_codper, $as_cedper, $as_nomper, $as_apeper, $as_codnom, $as_tipo)
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codper  // Código de Personal
		//				   as_cedper  // Cédula de Pesonal
		//				   as_nomper  // Nombre de Personal
		//				   as_apeper // Apellido de Personal
		//				   as_codnom // código de nómina a la que pertenece
		//				   as_tipo  // Tipo de Llamada del catálogo
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
   		require_once("sigesp_sno.php");
		$io_sno=new sigesp_sno();				
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60>Código</td>";
		print "<td width=40>Cédula</td>";
		print "<td width=340>Nombre y Apellido</td>";
		print "<td width=40>Rif</td>";
		print "<td width=60>Estatus</td>";
		print "</tr>";

	/*	 Se debera registrar el organigrama, las gerencias y las unidades vipladin
	$ls_sql="SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.dirper, ".
				"		sno_personal.fecnacper, sno_personal.edocivper, sno_personal.telhabper, sno_personal.telmovper, ".
				"		sno_personal.sexper, sno_personal.estaper, sno_personal.pesper, sno_personal.codpro, sno_personal.nivacaper, ".
				"		sno_personal.catper, sno_personal.cajahoper, sno_personal.numhijper, sno_personal.contraper, sno_personal.tipvivper, ".
				"		sno_personal.tenvivper, sno_personal.monpagvivper, sno_personal.ingbrumen, sno_personal.cuecajahoper, ".
				"		sno_personal.cuelphper, sno_personal.cuefidper, sno_personal.fecingadmpubper, sno_personal.vacper, sno_personal.porisrper, ".
				"		sno_personal.fecingper, sno_personal.anoservpreper, sno_personal.cedbenper, sno_personal.fecegrper, sno_personal.estper, ".
				"		sno_personal.fotper, sno_personal.codpai, sno_personal.codest, sno_personal.codmun, sno_personal.codpar, ".
				"		sno_personal.obsper, sno_personal.cauegrper, sno_personal.obsegrper, sno_personal.nacper, sno_personal.coreleper, ".
				"		sno_personal.cenmedper, sno_profesion.despro, sigesp_pais.despai, sno_personal.horper, sno_personal.turper, ".
				"       sigesp_estados.desest, sigesp_municipio.denmun, sigesp_parroquia.denpar, sno_personal.hcmper, sno_personal.tipsanper, ".
				"		sno_personal.codcom, sno_personal.codran, sno_personal.numexpper, sno_personal.codpainac, sno_personal.codestnac,sno_personal.codtippersss , sno_personal.enviorec, sno_personal.fecleypen, sno_personal.codcausa, ".
				"       sno_personal.fecreingper, sno_personal.fecjubper, sno_personal.codunivipladin,  ".
				"       sno_personal.situacion, sno_personal.fecsitu, sno_personal.talcamper, sno_personal.talzapper, ".
				"       sno_personal.talpanper,  sno_personal.anoservprecont, sno_personal.anoservprefijo, sno_personal.codorg, ".
				"       sno_personal.porcajahoper, sno_personal.codger, sno_personal.anoperobr,sno_personal.carantper,sno_personal.rifper, ".
				"       (SELECT denger FROM srh_gerencia ".
				"         WHERE srh_gerencia.codemp = sno_personal.codemp ".
				"           AND srh_gerencia.codger = sno_personal.codger ) AS denger, ".	
				"       (SELECT desorg FROM srh_organigrama ".
				"         WHERE srh_organigrama.codemp = sno_personal.codemp ".
				"           AND srh_organigrama.codorg = sno_personal.codorg ) AS desorg, ".
				"		(SELECT denunivipladin FROM srh_unidadvipladin ".
				"		  WHERE srh_unidadvipladin.codemp = sno_personal.codemp ".
				"			AND srh_unidadvipladin.codunivipladin = sno_personal.codunivipladin ) AS denunivipladin, ".
				"		(SELECT dentippersss FROM sno_tipopersonalsss WHERE  sno_tipopersonalsss.codemp= sno_personal.codemp AND sno_tipopersonalsss.codtippersss = sno_personal.codtippersss) as dentippersss,". 
				"		(SELECT descom FROM sno_componente WHERE sno_componente.codemp = sno_personal.codemp AND sno_componente.codcom = sno_personal.codcom ) AS descom, ".
				"		(SELECT desran FROM sno_rango WHERE sno_rango.codemp = sno_personal.codemp AND sno_rango.codcom = sno_personal.codcom AND sno_rango.codran = sno_personal.codran) AS desran, ".
				"		(SELECT despai FROM sigesp_pais WHERE sigesp_pais.codpai = sno_personal.codpainac ) AS despainac, ".
				"		(SELECT desest FROM sigesp_estados WHERE sigesp_estados.codpai = sno_personal.codpainac AND sigesp_estados.codest = sno_personal.codestnac ) AS desestnac ".
				"  FROM sno_personal, sno_profesion, sigesp_pais, sigesp_estados, sigesp_municipio, sigesp_parroquia ".
				" WHERE sno_personal.codemp='".$ls_codemp."'".
				"   AND sno_profesion.codemp = sno_personal.codemp ".
				"   AND sno_profesion.codpro = sno_personal.codpro ".
				"   AND sigesp_pais.codpai = sno_personal.codpai ".
				"   AND sigesp_estados.codpai = sno_personal.codpai ".
				"   AND sigesp_estados.codest = sno_personal.codest ".
				"   AND sigesp_municipio.codpai = sno_personal.codpai ".
				"   AND sigesp_municipio.codest = sno_personal.codest ".
				"   AND sigesp_municipio.codmun = sno_personal.codmun ".
				"   AND sigesp_parroquia.codpai = sno_personal.codpai ".
				"   AND sigesp_parroquia.codest = sno_personal.codest ".
				"   AND sigesp_parroquia.codmun = sno_personal.codmun ".
				"   AND sigesp_parroquia.codpar = sno_personal.codpar ".
				"   AND sno_personal.codper like '".$as_codper."' ".
				"   AND sno_personal.cedper like '".$as_cedper."'".
				"   AND sno_personal.nomper like '".$as_nomper."' ".
				"   AND sno_personal.apeper like '".$as_apeper."'".
				"  AND sno_personal.codper IN (SELECT sno_personal.codper".
				" FROM  sss_permisos_internos,sno_personal".
				" WHERE  sss_permisos_internos.codsis='SNO' AND sss_permisos_internos.codusu='".$_SESSION["la_logusr"]."' ".
				" AND sno_personal.codtippersss=sss_permisos_internos.codintper  )"; */
		
		$ls_sql="SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.dirper, ".
				"		sno_personal.fecnacper, sno_personal.edocivper, sno_personal.telhabper, sno_personal.telmovper, ".
				"		sno_personal.sexper, sno_personal.estaper, sno_personal.pesper, sno_personal.codpro, sno_personal.nivacaper, ".
				"		sno_personal.catper, sno_personal.cajahoper, sno_personal.numhijper, sno_personal.contraper, sno_personal.tipvivper, ".
				"		sno_personal.tenvivper, sno_personal.monpagvivper, sno_personal.ingbrumen, sno_personal.cuecajahoper, ".
				"		sno_personal.cuelphper, sno_personal.cuefidper, sno_personal.fecingadmpubper, sno_personal.vacper, sno_personal.porisrper, ".
				"		sno_personal.fecingper, sno_personal.anoservpreper, sno_personal.cedbenper, sno_personal.fecegrper, sno_personal.estper, ".
				"		sno_personal.fotper, sno_personal.codpai, sno_personal.codest, sno_personal.codmun, sno_personal.codpar, ".
				"		sno_personal.obsper, sno_personal.cauegrper, sno_personal.obsegrper, sno_personal.nacper, sno_personal.coreleper, ".
				"		sno_personal.cenmedper, sno_profesion.despro, sigesp_pais.despai, sno_personal.horper, sno_personal.turper, ".
				"       sigesp_estados.desest, sigesp_municipio.denmun, sigesp_parroquia.denpar, sno_personal.hcmper, sno_personal.tipsanper, ".
				"		sno_personal.codcom, sno_personal.codran, sno_personal.numexpper, sno_personal.codpainac, sno_personal.codestnac,sno_personal.codtippersss , sno_personal.enviorec, sno_personal.fecleypen, sno_personal.codcausa, ".
				"       sno_personal.fecreingper, sno_personal.fecjubper, sno_personal.codunivipladin,  ".
				"       sno_personal.situacion, sno_personal.fecsitu, sno_personal.talcamper, sno_personal.talzapper, ".
				"       sno_personal.talpanper,  sno_personal.anoservprecont, sno_personal.anoservprefijo, sno_personal.codorg, ".
				"       sno_personal.porcajahoper, sno_personal.codger, sno_personal.anoperobr,sno_personal.carantper,sno_personal.rifper, ".
				"		(SELECT despai FROM sigesp_pais WHERE sigesp_pais.codpai = sno_personal.codpainac ) AS despainac, ".
				"		(SELECT desest FROM sigesp_estados WHERE sigesp_estados.codpai = sno_personal.codpainac AND sigesp_estados.codest = sno_personal.codestnac ) AS desestnac ".
				"  FROM sno_personal, sno_profesion, sigesp_pais, sigesp_estados, sigesp_municipio, sigesp_parroquia ".
				" WHERE sno_personal.codemp='".$ls_codemp."'".
				"   AND sno_profesion.codemp = sno_personal.codemp ".
				"   AND sno_profesion.codpro = sno_personal.codpro ".
				"   AND sigesp_pais.codpai = sno_personal.codpai ".
				"   AND sigesp_estados.codpai = sno_personal.codpai ".
				"   AND sigesp_estados.codest = sno_personal.codest ".
				"   AND sigesp_municipio.codpai = sno_personal.codpai ".
				"   AND sigesp_municipio.codest = sno_personal.codest ".
				"   AND sigesp_municipio.codmun = sno_personal.codmun ".
				"   AND sigesp_parroquia.codpai = sno_personal.codpai ".
				"   AND sigesp_parroquia.codest = sno_personal.codest ".
				"   AND sigesp_parroquia.codmun = sno_personal.codmun ".
				"   AND sigesp_parroquia.codpar = sno_personal.codpar ".
				"   AND sno_personal.codper like '".$as_codper."' ".
				"   AND sno_personal.cedper like '".$as_cedper."'".
				"   AND sno_personal.nomper like '".$as_nomper."' ".
				"   AND sno_personal.apeper like '".$as_apeper."'";
		if($as_tipo=="asignacion")
		{
			$ls_codnom=$_SESSION["la_nomina"]["codnom"];
			$ls_repetidos=$io_sno->uf_select_config("SNO","CONFIG","NOPERMITIR_REPETIDOS","1","C");
			$ls_sql=$ls_sql."  AND sno_personal.estper = '1' ";
			$ls_espnom=trim($_SESSION["la_nomina"]["espnom"]);
			switch($ls_espnom)
			{
				case "0": //Nómina Normal
					if($ls_repetidos=="1")
					{
						$ls_sql=$ls_sql."  AND (NOT sno_personal.codper IN (SELECT codper ".
										"									  FROM sno_personalnomina, sno_nomina ".
										"							         WHERE sno_personalnomina.codemp='".$ls_codemp."'".
										"									   AND sno_nomina.espnom='0' ".
										"									   AND sno_personalnomina.staper<>'3' ".
										"									   AND sno_personalnomina.codemp=sno_nomina.codemp ".
										"									   AND sno_personalnomina.codnom=sno_nomina.codnom ))";
					}
					else
					{
						$ls_sql=$ls_sql."  AND (NOT sno_personal.codper IN (SELECT codper ".
										"									  FROM sno_personalnomina, sno_nomina ".
										"							         WHERE sno_personalnomina.codemp='".$ls_codemp."'".
										"							           AND sno_personalnomina.codnom='".$ls_codnom."'".
										"									   AND sno_nomina.espnom='0' ".
										"									   AND sno_personalnomina.codemp=sno_nomina.codemp ".
										"									   AND sno_personalnomina.codnom=sno_nomina.codnom ))";
					}
					break;

				case "1": //Nómina Especial
					$ls_sql=$ls_sql."  AND (NOT sno_personal.codper IN (SELECT codper ".
									"									  FROM sno_personalnomina, sno_nomina ".
									"							         WHERE sno_personalnomina.codemp='".$ls_codemp."'".
									"							           AND sno_personalnomina.codnom='".$ls_codnom."'".
									"									   AND sno_personalnomina.codemp=sno_nomina.codemp ".
									"									   AND sno_personalnomina.codnom=sno_nomina.codnom ))";
					break;
			}
		}
		if(($as_tipo=="repconttrabdes")||($as_tipo=="repconttrabhas"))
		{
			$ls_sql=$ls_sql."  AND (sno_personal.codper IN (SELECT codper FROM sno_personalnomina".
							"							     WHERE (sno_personalnomina.codemp='".$ls_codemp."')".
							"							       AND (sno_personalnomina.codnom='".$as_codnom."')))";
		}		
		if($as_tipo=="ipasme")
		{
			$ls_sql=$ls_sql."  AND (NOT sno_personal.codper IN (SELECT codper FROM sno_ipasme_afiliado ".
							"							     	 WHERE (sno_ipasme_afiliado.codemp='".$ls_codemp."')))";
		}		
		$ls_sql=$ls_sql." ORDER BY sno_personal.codper ";
		//print $ls_sql;
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				$ls_cedper=$row["cedper"];
				$ls_nomper=$row["nomper"];
				$ls_apeper=$row["apeper"];				
				$ls_dirper=$row["dirper"];				
				$ld_fecnacper=$io_funciones->uf_formatovalidofecha($row["fecnacper"]);
				$ld_fecnacper=$io_funciones->uf_convertirfecmostrar($ld_fecnacper);				
				$ls_edocivper=$row["edocivper"];			
				$ls_telhabper=$row["telhabper"];				
				$ls_telmovper=$row["telmovper"];				
				$ls_sexper=$row["sexper"];			
				$li_estaper=$row["estaper"];			
				$li_estaper=$io_fun_nomina->uf_formatonumerico($li_estaper);
				$li_pesper=$row["pesper"];			
				$li_pesper=$io_fun_nomina->uf_formatonumerico($li_pesper);
				$ls_codpro=$row["codpro"];	
				$ls_nivacaper=$row["nivacaper"];
				$ls_codpai=$row["codpai"];	
				$ls_codest=$row["codest"];	
				$ls_codpainac=$row["codpainac"];	
				$ls_codestnac=$row["codestnac"];	
				$ls_codmun=$row["codmun"];	
				$ls_codpar=$row["codpar"];	
				$ls_catper=$row["catper"];	
				$ls_cedbenper=$row["cedbenper"];	
				$ls_numhijper=$row["numhijper"];	
				$ls_obsper=preg_replace("/\s+/"," ",$row["obsper"]);	
				$ls_contraper=$row["contraper"];			
				$ls_tipvivper=$row["tipvivper"];	
				$ls_tenvivper=$row["tenvivper"];	
				$li_monpagvivper=$row["monpagvivper"];	
				$li_monpagvivper=$io_fun_nomina->uf_formatonumerico($li_monpagvivper);				
				$ls_cuecajahoper=$row["cuecajahoper"];	
				$ls_cuelphper=$row["cuelphper"];	
				$ls_cuefidper=$row["cuefidper"];	
				$ls_cajahoper=$row["cajahoper"];
				$ld_fecingadmpubper=$io_funciones->uf_formatovalidofecha($row["fecingadmpubper"]);
				$ld_fecingadmpubper=$io_funciones->uf_convertirfecmostrar($ld_fecingadmpubper);				
				$li_anoservpreper=$row["anoservpreper"];	
				$ld_fecingper=$io_funciones->uf_formatovalidofecha($row["fecingper"]);
				$ld_fecingper=$io_funciones->uf_convertirfecmostrar($ld_fecingper);				
				$ld_fecegrper=$io_funciones->uf_formatovalidofecha($row["fecegrper"]);
				$ld_fecegrper=$io_funciones->uf_convertirfecmostrar($ld_fecegrper);				
				$ls_cauegrper=$row["cauegrper"];			
				$ls_obsegrper=preg_replace("/\s+/"," ",$row["obsegrper"]);	// para reemplazar los \n o ENTER 		
				$ls_estper=$row["estper"];		
				$ls_nacper=$row["nacper"];		
				$ls_hcmper=$row["hcmper"];
				$ls_tipsanper=$row["tipsanper"];
				$ls_rifper=$row["rifper"];
				$ls_tipperrif = substr($ls_rifper,0,1);//Tipo Persona RIF.(J=Juridico,G=Gubernamental,V=Natural Venezolano,E=Natural Extranjero).
				$ls_numpririf = substr($ls_rifper,2,8);//Número Principal del RIF, 8 Dígitos (0-9).
				$ls_numterrif = substr($ls_rifper,11,1);//Número Terminal  del RIF, 1 Dígitos (0-9).
				$ls_situacion=$row["situacion"];
				switch ($ls_estper)
				{
					case "0":
						$ls_estper="Pre-Ingreso";
						$ls_estatus=0;
						break;
					
					case "1":
						$ls_estper="Activo";
						$ls_estatus=1;
						break;
					
					case "2":
						$ls_estper="N/A";
						$ls_estatus=2;
						break;
					
					case "3":
						$ls_estper="Egresado";
						$ls_estatus=3;
						break;
				}
				$ls_despro=$row["despro"];		
				$ls_despai=$row["despai"];		
				$ls_desest=$row["desest"];			
				$ls_desmun=$row["denmun"];		
				$ls_despar=$row["denpar"];		
				$ls_nomfot=$row["fotper"];
				$ls_coreleper=$row["coreleper"];
				$ls_cenmedper=$row["cenmedper"];
				$ls_turper=$row["turper"];
				$ls_horper=$row["horper"];
				$ls_codcom=$row["codcom"];
				$ls_codran=$row["codran"];
				$ls_descom=$row["descom"];
				$ls_desran=$row["desran"];
				$ls_numexpper=$row["numexpper"];
				$ls_despainac=$row["despainac"];		
				$ls_desestnac=$row["desestnac"];
				$ls_codtippersss=$row["codtippersss"];
				$ls_dentippersss=$row["dentippersss"];			
				$ld_fecreingper=$io_funciones->uf_formatovalidofecha($row["fecreingper"]);
				$ld_fecreingper=$io_funciones->uf_convertirfecmostrar($ld_fecreingper);				
				$ld_fecjubper=$io_funciones->uf_formatovalidofecha($row["fecjubper"]);
				$ld_fecjubper=$io_funciones->uf_convertirfecmostrar($ld_fecjubper);				
				$ls_codunivipladin=$row["codunivipladin"];
				$ls_denunivipladin=$row["denunivipladin"];	
				$ls_enviorec=$row["enviorec"];	
				$ls_fecleypen=$io_funciones->uf_formatovalidofecha($row["fecleypen"]);					
				$ls_fecleypen=$io_funciones->uf_convertirfecmostrar($ls_fecleypen);	
				$ls_codcausa=$row["codcausa"];
				$ls_fecsitu=$io_funciones->uf_formatovalidofecha($row["fecsitu"]);
				$ls_fecsitu=$io_funciones->uf_convertirfecmostrar($ls_fecsitu);
				$ls_talcamper=$row["talcamper"];
				$ls_talpanper=$row["talpanper"];
				$ls_talzapper=$row["talzapper"];
				$ls_anoprevcont=$row["anoservprecont"];	
				$ls_anoprevfijo=$row["anoservprefijo"];		
				$ls_codorg=$row["codorg"];			
				$ls_desorg=$row["desorg"];
				$ls_codger=$row["codger"];			
				$ls_denger=$row["denger"];								
				$li_porcajahoper=$row["porcajahoper"];				
				$li_porcajahoper=$io_fun_nomina->uf_formatonumerico($li_porcajahoper);	
				$li_anoperobr=$row["anoperobr"];
				$ls_carantper=$row["carantper"];
				switch ($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codper','$ls_cedper','$ls_nomper','$ls_apeper','$ls_dirper','$ld_fecnacper',";
						print "'$ls_edocivper','$ls_telhabper','$ls_telmovper','$ls_sexper','$li_estaper','$li_pesper','$ls_codpro','$ls_nivacaper',";
						print "'$ls_codpai','$ls_codest','$ls_codmun','$ls_codpar','$ls_catper','$ls_cedbenper','$ls_numhijper','$ls_obsper',";
						print "'$ls_contraper','$ls_tipvivper','$ls_tenvivper','$li_monpagvivper','$ls_cuecajahoper','$ls_cuelphper','$ls_cuefidper',";
						print "'$ls_cajahoper','$ld_fecingadmpubper','$li_anoservpreper','$ld_fecingper','$ld_fecegrper','$ls_cauegrper', ";
						print "'$ls_obsegrper','$ls_despro','$ls_despai','$ls_desest','$ls_desmun','$ls_despar','$ls_estper','$ls_nomfot','$ls_nacper',";
						print "'$ls_coreleper','$ls_cenmedper','$ls_turper','$ls_horper','$ls_hcmper','$ls_tipsanper','$ls_codcom','$ls_codran',";
						print "'$ls_descom','$ls_desran','$ls_numexpper','$ls_codpainac','$ls_codestnac','$ls_despainac','$ls_desestnac',";
						print "'$ls_codtippersss','$ls_dentippersss','$ld_fecreingper','$ld_fecjubper','$ls_codunivipladin','$ls_denunivipladin','$ls_enviorec','$ls_fecleypen','$ls_codcausa','$ls_situacion','$ls_fecsitu','$ls_talcamper','$ls_talpanper','$ls_talzapper','$ls_anoprevcont','$ls_anoprevfijo','$ls_codorg','$ls_desorg','$li_porcajahoper','$ls_codger', '$ls_denger','$li_anoperobr','$ls_carantper','$ls_tipperrif','$ls_numpririf','$ls_numterrif');\">";
						print "".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_tipperrif."-".$ls_numpririf."-".$ls_numterrif."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;
					
					case "egreso":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptaregreso('$ls_codper','$ls_nomper','$ls_apeper','$ld_fecegrper','$ls_cauegrper',";
						print "'$ls_obsegrper','$ls_estper','$ld_fecingper','$ld_fecnacper','$ls_codcausa');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;
	
					case "asignacion":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarasignacion('$ls_codper','$ls_nomper','$ls_apeper','$ls_estper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "cambio":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarcambio('$ls_codper','$ls_nomper','$ls_apeper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "buscar":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarbuscar('$ls_codper','$ls_nomper','$ls_apeper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "replisperdes":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisperdes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "replisperhas":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisperhas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repconttrabdes":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepconttrabdes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repconttrabhas":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepconttrabhas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "ipasme":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptaripasme('$ls_codper','$ls_nomper','$ls_apeper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repivssingdes":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepivssingdes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repivssinghas":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepivssinghas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repivssretdes":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepivssretdes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repivssrethas":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepivssrethas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repivsssaldes":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepivsssaldes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repivsssalhas":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepivsssalhas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repivsscendes":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepivsscendes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repivsscenhas":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepivsscenhas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repivssmoddes":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepivssmoddes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repivssmodhas":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepivssmodhas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repivssrepdes":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepivssrepdes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repivssrephas":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepivssrephas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repivssperdes":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepivssperdes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repivssperhas":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepivssperhas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repvacperdes":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepvacperdes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repvacperhas":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepvacperhas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "replisfamdes":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisfamdes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "replisfamhas":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisfamhas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repconcdes":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepconcdes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repconchas":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepconchas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repliscumdes":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepliscumdes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repliscumhas":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepliscumhas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repficperdes":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepficperdes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "repficperhas":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepficperhas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "replisantdes":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisantdes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "replisanthas":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisanthas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "recpagcondes":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrecpagcondes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;

					case "recpagconhas":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrecpagconhas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;
					
					case "retencionarc":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar_retencion_arc('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;
				    
					case "repconttrabivssdes":
					$ls_nombre=$ls_nomper."  ".$ls_apeper;
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepconttrabivssdes('$ls_codper','$ls_nombre','$ls_estatus','$ld_fecegrper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;
					
				}
			}
			$io_sql->free_result($rs_data);
		}
		print "</table>";
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($io_sno);
		unset($ls_codemp);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Personal</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Personal </td>
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
        <td><div align="right"><a href="javascript: ue_search();">
          <img src="../shared/imagebank/tools20/buscar.gif" title='Buscar' alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
  <br>
<?php
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_operacion =$io_fun_nomina->uf_obteneroperacion();
	$ls_tipo=$io_fun_nomina->uf_obtenertipo();
	$ls_codnom=$io_fun_nomina->uf_obtenervalor_get("codnom","");
	if($ls_operacion=="BUSCAR")
	{
		$ls_codper="%".$_POST["txtcodper"]."%";
		$ls_cedper="%".$_POST["txtcedper"]."%";
		$ls_nomper="%".$_POST["txtnomper"]."%";
		$ls_apeper="%".$_POST["txtapeper"]."%";

		uf_print($ls_codper, $ls_cedper, $ls_nomper, $ls_apeper, $ls_codnom, $ls_tipo);
	}
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codper,cedper,nomper,apeper,dirper,fecnacper,edocivper,telhabper,telmovper,sexper,estaper,pesper,codpro,
				 nivacaper,codpai,codest,codmun,codpar,catper,cedbenper,numhijper,obsper,contraper,tipvivper,tenvivper,
				 monpagvivper,cuecajahoper,cuelphper,cuefidper,cajahoper,fecingadmpubper,anoservpreper,fecingper,fecegrper,
				 cauegrper,obsegrper,despro,despai,desest,desmun,despar,estper,nomfot,nacper,coreleper,cenmedper,turper,horper,
			     hcmper,tipsanper,codcom,codran,descom,desran,numexpper,codpainac,codestnac,despainac,desestnac,codtippersss,
				 dentippersss,fecreingper,fecjubper,codunivipladin,denunivipladin, enviorec, fecleypen, codcausa, situacion,
				 fecsitu, talcamper, talpanper, talzapper, anoprevcont, anoprevfijo,codorg,desorg,porcajahoper, codger, denger,
				 anoperobr, carantper,tipperrif,numpririf,numterrif)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtestper.value=estper;	
	opener.document.form1.txtcodper.readOnly=true;
	opener.document.form1.txtcedper.value=cedper;
	opener.document.images["foto"].src="fotospersonal/"+nomfot;
	opener.document.form1.txtcedper.value=cedper;
	opener.document.form1.txtnomper.value=nomper;
	opener.document.form1.txtapeper.value=apeper;
	opener.document.form1.txtdirper.value=dirper;
	opener.document.form1.txtfecnacper.value=fecnacper;
	opener.document.form1.cmbedocivper.value=edocivper;
	opener.document.form1.cmbnacper.value=nacper;
	opener.document.form1.txttelhabper.value=telhabper;
	opener.document.form1.txttelmovper.value=telmovper;
	opener.document.form1.cmbsexper.value=sexper;
	opener.document.form1.txtestaper.value=estaper;
	opener.document.form1.txtpesper.value=pesper;
	opener.document.form1.txtcodpro.value=codpro;
	opener.document.form1.cmbnivacaper.value=nivacaper;	
	opener.document.form1.txtcodpai.value=codpai;
	opener.document.form1.txtcodest.value=codest;
	opener.document.form1.txtcodmun.value=codmun;
	opener.document.form1.txtcodpar.value=codpar;
	opener.document.form1.txtcatper.value=catper;
	opener.document.form1.txtcedbenper.value=cedbenper;
	opener.document.form1.txtnumhijper.value=numhijper;
	opener.document.form1.txtobsper.value=obsper;
	opener.document.form1.cmbcontraper.value=contraper;
	opener.document.form1.cmbtipvivper.value=tipvivper;
	opener.document.form1.txttenvivper.value=tenvivper;
	opener.document.form1.txtmonpagvivper.value=monpagvivper;
	opener.document.form1.txtcuecajahoper.value=cuecajahoper;
	opener.document.form1.txtcuelphper.value=cuelphper;
	opener.document.form1.txtcuefidper.value=cuefidper;
	opener.document.form1.cmbsituacion.value=situacion;
	opener.document.form1.txtporcajahoper.value= porcajahoper;
	if (situacion!=1)
	{
		opener.document.images["causa"].style.visibility="visible";
	}
	if(cajahoper=="1")
	{
		opener.document.form1.chkcajahoper.checked=true;		 
		opener.document.form1.txtporcajahoper.readOnly=false;
	}
	else
	{
		opener.document.form1.chkcajahoper.checked=false;
		opener.document.form1.txtporcajahoper.readOnly=true;
	}
	if(hcmper=="1")
	{
		opener.document.form1.chkhcmper.checked=true;
	}
	else
	{
		opener.document.form1.chkhcmper.checked=false;
	}
	opener.document.form1.txtfecingadmpubper.value=fecingadmpubper;
	opener.document.form1.txtanoservpreper.value=anoservpreper;
	opener.document.form1.txtfecingper.value=fecingper;
	opener.document.form1.txtfecegrper.value=fecegrper;
	opener.document.form1.txtcauegrper2.value=cauegrper; 
	if (cauegrper=="")
	{
		opener.document.form1.txtcauegrper.value="N/A";
	}
	if (cauegrper=="N")
	{
		opener.document.form1.txtcauegrper.value="Ninguno";
	}
	if (cauegrper=="D")
	{
		opener.document.form1.txtcauegrper.value="Despedido";
	}
	if (cauegrper=="1")
	{
		opener.document.form1.txtcauegrper.value="Despedido 102";
	}
	if (cauegrper=="2")
	{
		opener.document.form1.txtcauegrper.value="Despedido 125";
	}
	if (cauegrper=="P")
	{
		opener.document.form1.txtcauegrper.value="Pensionado";
	}
	if (cauegrper=="R")
	{
		opener.document.form1.txtcauegrper.value="Renuncia";
	}
	if (cauegrper=="T")
	{
		opener.document.form1.txtcauegrper.value="Traslado";
	}
	if (cauegrper=="J")
	{
		opener.document.form1.txtcauegrper.value="Jubilado";
	}
	if (cauegrper=="F")
	{
		opener.document.form1.txtcauegrper.value="Fallecido";
	}
	opener.document.form1.txtobsegrper.value=obsegrper;	
	opener.document.form1.txtdespro.value=despro;	
	opener.document.form1.txtdespai.value=despai;	
	opener.document.form1.txtdesest.value=desest;	
	opener.document.form1.txtdesmun.value=desmun;	
	opener.document.form1.txtdespar.value=despar;	
	opener.document.form1.txtcoreleper.value=coreleper;	
	opener.document.form1.cmbcenmedper.value=cenmedper;	
	opener.document.form1.txthorper.value=horper;	
	opener.document.form1.cmbturper.value=turper;	
	opener.document.form1.txttipsanper.value=tipsanper;	
	opener.document.form1.txtcodcom.value=codcom;	
	opener.document.form1.txtdescom.value=descom;	
	opener.document.form1.txtcodran.value=codran;	
	opener.document.form1.txtdesran.value=desran;	
	opener.document.form1.txtnumexpper.value=numexpper;	
	opener.document.form1.txtcodpainac.value=codpainac;
	opener.document.form1.txtcodestnac.value=codestnac;	
	opener.document.form1.txtdespainac.value=despainac;	
	opener.document.form1.txtdesestnac.value=desestnac;	
	opener.document.form1.txtcodtippersss.value=codtippersss;
	opener.document.form1.txtdestippersss.value=dentippersss;
	opener.document.form1.txtfecreingper.value=fecreingper;
	opener.document.form1.txtfecjubper.value=fecjubper;
	opener.document.form1.txtcodunivipladin.value=codunivipladin;
	opener.document.form1.txtdenunivipladin.value=denunivipladin;
	opener.document.form1.btnfamiliar.disabled=false;
	opener.document.form1.btnestudio.disabled=false;
	opener.document.form1.btntrabajo.disabled=false;
	opener.document.form1.btnimpuesto.disabled=false;
	opener.document.form1.btnpermiso.disabled=false;
	opener.document.form1.btnfideicomiso.disabled=false;
	opener.document.form1.btnvacacion.disabled=false;
	opener.document.form1.btnbeneficiario.disabled=false;
	opener.document.form1.cmbenviorec.value=enviorec;
	opener.document.form1.txtfecleypen.value=fecleypen;
	opener.document.form1.txtcodcausa.value=codcausa;
	opener.document.form1.txtfecsitu.value=fecsitu;
	opener.document.form1.txttalcamper.value= talcamper;
	opener.document.form1.txttalpanper.value= talpanper;
	opener.document.form1.txttalzapper.value= talzapper;
	opener.document.form1.txtanoservprecont.value= anoprevcont;
	opener.document.form1.txtanoservprefijo.value= anoprevfijo;
	opener.document.form1.txtcodorg.value= codorg;
	opener.document.form1.txtdesorg.value= desorg;
	opener.document.form1.txtcodger.value= codger;
	opener.document.form1.txtdenger.value= denger;	
	opener.document.form1.txtanoperobr.value= anoperobr;
	opener.document.form1.txtcarantper.value=carantper;
	opener.document.form1.cmbtipperrif.value=tipperrif;
	opener.document.form1.txtnumpririf.value=numpririf;
	opener.document.form1.txtnumterrif.value=numterrif;
	opener.document.form1.existe.value="TRUE";
	close();
}

function aceptaregreso(codper,nomper,apeper,fecegrper,cauegrper,obsegrper,estper,fecingper,fecnacper,codcausa)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
	opener.document.form1.txtnomper.value=nomper+" "+ apeper;
	opener.document.form1.txtestactper.value=estper;	
	opener.document.form1.txtfecegrper.value=fecegrper;
	opener.document.form1.cmbcauegrper.value=cauegrper;
	opener.document.form1.txtobsegrper.value=obsegrper;	
	opener.document.form1.txtfecingper.value=fecingper;	
	opener.document.form1.txtfecnacper.value=fecnacper;	
	opener.document.form1.txtcodcausa.value=codcausa;	
	close();
}

function aceptarasignacion(codper,nomper,apeper,estper)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
	opener.document.form1.txtnomper.value=nomper+" "+ apeper;
	opener.document.form1.txtnomper.readOnly=true;
	opener.document.form1.txtestper.value=estper;	
	opener.document.form1.txtestper.readOnly=true;
	close();
}

function aceptarcambio(codper,nomper,apeper)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
	opener.document.form1.txtnomper.value=nomper+" "+ apeper;
	opener.document.form1.txtnomper.readOnly=true;
	close();
}

function aceptarbuscar(codper,nomper,apeper)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
	opener.document.form1.txtnomper.value=nomper+" "+ apeper;
	opener.document.form1.txtnomper.readOnly=true;
	close();
}

function aceptarreplisperdes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarreplisperhas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del personal inválido");
	}
}

function aceptarrepconttrabdes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarrepconttrabhas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del personal inválido");
	}
}
function aceptaripasme(codper,nomper,apeper)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
	opener.document.form1.txtnomper.value=nomper+" "+ apeper;
	opener.document.form1.txtnomper.readOnly=true;
	close();
}

function aceptarrepivssingdes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarrepivssinghas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del personal inválido");
	}
}

function aceptarrepivssretdes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarrepivssrethas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del personal inválido");
	}
}

function aceptarrepivsssaldes(codper,fecegr,estatus,nomper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;	
	opener.document.form1.txtestatus.value=estatus;
	close();
}



function aceptarrepivsscendes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarrepivsscenhas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del personal inválido");
	}
}

function aceptarrepivssmoddes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarrepivssmodhas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del personal inválido");
	}
}

function aceptarrepivssrepdes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarrepivssrephas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del personal inválido");
	}
}

function aceptarrepivssperdes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarrepivssperhas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del personal inválido");
	}
}

function aceptarrepvacperdes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarrepvacperhas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del personal inválido");
	}
}

function aceptarreplisfamdes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarreplisfamhas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del personal inválido");
	}
}

function aceptarrepconcdes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarrepconchas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del personal inválido");
	}
}

function aceptarrepliscumdes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarrepliscumhas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del personal inválido");
	}
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
  	f.action="sigesp_snorh_cat_personal.php?codnom=<?php print $ls_codnom;?>&tipo=<?php print $ls_tipo;?>";
  	f.submit();
}

function aceptarrepficperdes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarrepficperhas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del personal inválido");
	}
}

function aceptarreplisantdes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarreplisanthas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del personal inválido");
	}
}

function aceptarrecpagcondes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarrecpagconhas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del personal inválido");
	}
}

function aceptar_retencion_arc(ls_codper)
{
  ls_rango = opener.document.form1.hidrango.value;
  if (ls_rango=='D')
     {
	   opener.document.form1.txtcodperdes.value=ls_codper;
	   opener.document.form1.txtcodperdes.readOnly=true;
	   opener.document.form1.txtcodperhas.value="";
	 }
  else
     {
	   opener.document.form1.txtcodperhas.value=ls_codper;
	   opener.document.form1.txtcodperhas.readOnly=true;
	 }
  close();
}

///-----------------------------------------------------------------------------------------------------------

function aceptarrepconttrabivssdes(codper,nombre,estatus,fecha)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtnomperdes.value=nombre;
	opener.document.form1.txtestatus.value=estatus;
	opener.document.form1.txtfecegr.value=fecha;
	close();
}


//--------------------------------------------------------------------------------------------------------------

</script>
</html>
