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
	function uf_print($as_codper, $as_cedper, $as_nomper, $as_apeper, $as_codnom, $as_tipo,$as_respced,
	                  $as_codresced,$as_buscartest,$as_codreserec,$as_buscar)
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
		$io_funciones=new class_funciones("../");		
   		require_once("../sno/sigesp_sno.php");
		$io_sno=new sigesp_sno();	
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];		
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60>Código</td>";
		print "<td width=40>Cédula</td>";
		print "<td width=340>Nombre y Apellido</td>";
		print "<td width=60>Estatus</td>";
		print "</tr>";
		switch($as_buscar)
		{
		  case"0":
		  	if ($as_respced!="buscaresced")
		     {
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
					"       sigesp_estados.desest, sigesp_municipio.denmun, sigesp_parroquia.denpar, sno_personal.hcmper, sno_personal.tipsanper ".
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
					"   AND UPPER(sno_personal.nomper) like '".strtoupper($as_nomper)."' ";
		      }
		    else
		      {
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
						"       sigesp_estados.desest, sigesp_municipio.denmun, sigesp_parroquia.denpar, sno_personal.hcmper, sno_personal.tipsanper ".
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
						"   AND UPPER(sno_personal.nomper) like '".strtoupper($as_nomper)."' ".
						"   AND UPPER(sno_personal.apeper) like '".strtoupper($as_apeper)."'".
						"   AND sno_personal.codper <> '".$as_codresced."'"; 
			}
		 break;
		 case "1":
	      if ($as_buscartest="buscartest")	
		  {
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
					"       sigesp_estados.desest, sigesp_municipio.denmun, sigesp_parroquia.denpar, sno_personal.hcmper, sno_personal.tipsanper ".
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
					"   AND UPPER(sno_personal.nomper) like '".strtoupper($as_nomper)."' ".
					"   AND UPPER(sno_personal.apeper) like '".strtoupper($as_apeper)."'".
					"   AND sno_personal.codper <> '".$as_codresced."'".
					"   AND sno_personal.codper <> '".$as_codreserec."'"; 
			//print $ls_sql;
		   }
		  break;
		}// fin del switch
		$ls_repetidos=$io_sno->uf_select_config("SNO","CONFIG","NOPERMITIR_REPETIDOS","1","C");
		$ls_sql=$ls_sql."  AND sno_personal.estper = '1'
		                   AND (NOT sno_personal.codper IN (SELECT codper
															  FROM sno_personalnomina, sno_nomina
													         WHERE sno_personalnomina.codemp='".$ls_codemp."'
															   AND sno_nomina.espnom='1'
															   AND sno_personalnomina.codemp=sno_nomina.codemp
															   AND sno_personalnomina.codnom=sno_nomina.codnom))
				         ORDER BY sno_personal.codper";
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
				$ls_nomper=$rs_data->fields["nomper"];
				$ls_apeper=$rs_data->fields["apeper"];				
				$ls_dirper=$rs_data->fields["dirper"];				
				$ld_fecnacper=$io_funciones->uf_convertirfecmostrar($rs_data->fields["fecnacper"]);				
				$ls_edocivper=$rs_data->fields["edocivper"];			
				$ls_telhabper=$rs_data->fields["telhabper"];				
				$ls_telmovper=$rs_data->fields["telmovper"];				
				$ls_sexper=$rs_data->fields["sexper"];			
				$li_estaper=$rs_data->fields["estaper"];			
				$li_estaper=$io_fun_nomina->uf_formatonumerico($li_estaper);
				$li_pesper=$rs_data->fields["pesper"];			
				$li_pesper=$io_fun_nomina->uf_formatonumerico($li_pesper);
				$ls_codpro=$rs_data->fields["codpro"];	
				$ls_nivacaper=$rs_data->fields["nivacaper"];
				$ls_codpai=$rs_data->fields["codpai"];	
				$ls_codest=$rs_data->fields["codest"];	
				$ls_codmun=$rs_data->fields["codmun"];	
				$ls_codpar=$rs_data->fields["codpar"];	
				$ls_catper=$rs_data->fields["catper"];	
				$ls_cedbenper=$rs_data->fields["cedbenper"];	
				$ls_numhijper=$rs_data->fields["numhijper"];	
				$ls_obsper=$rs_data->fields["obsper"];	
				$ls_contraper=$rs_data->fields["contraper"];			
				$ls_tipvivper=$rs_data->fields["tipvivper"];	
				$ls_tenvivper=$rs_data->fields["tenvivper"];	
				$li_monpagvivper=$rs_data->fields["monpagvivper"];	
				$li_monpagvivper=$io_fun_nomina->uf_formatonumerico($li_monpagvivper);				
				$ls_cuecajahoper=$rs_data->fields["cuecajahoper"];	
				$ls_cuelphper=$rs_data->fields["cuelphper"];	
				$ls_cuefidper=$rs_data->fields["cuefidper"];	
				$ls_cajahoper=$rs_data->fields["cajahoper"];
				$ld_fecingadmpubper=$io_funciones->uf_convertirfecmostrar($rs_data->fields["fecingadmpubper"]);				
				$li_anoservpreper=$rs_data->fields["anoservpreper"];	
				$ld_fecingper=$io_funciones->uf_convertirfecmostrar($rs_data->fields["fecingper"]);				
				$ld_fecegrper=$io_funciones->uf_convertirfecmostrar($rs_data->fields["fecegrper"]);				
				$ls_cauegrper=$rs_data->fields["cauegrper"];			
				$ls_obsegrper=$rs_data->fields["obsegrper"];			
				$ls_estper=$rs_data->fields["estper"];		
				$ls_nacper=$rs_data->fields["nacper"];		
				$ls_hcmper=$rs_data->fields["hcmper"];
				$ls_tipsanper=$rs_data->fields["tipsanper"];
				switch ($ls_estper)
				{
					case "0":
						$ls_estper="Pre-Ingreso";
						break;
					
					case "1":
						$ls_estper="Activo";
						break;
					
					case "2":
						$ls_estper="N/A";
						break;
					
					case "3":
						$ls_estper="Egresado";
						break;
				}
				$ls_despro=$rs_data->fields["despro"];		
				$ls_despai=$rs_data->fields["despai"];		
				$ls_desest=$rs_data->fields["desest"];			
				$ls_desmun=$rs_data->fields["denmun"];		
				$ls_despar=$rs_data->fields["denpar"];		
				$ls_nomfot=$rs_data->fields["fotper"];
				$ls_coreleper=$rs_data->fields["coreleper"];
				$ls_cenmedper=$rs_data->fields["cenmedper"];
				$ls_turper=$rs_data->fields["turper"];
				$ls_horper=$rs_data->fields["horper"];
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
						print "'$ls_coreleper','$ls_cenmedper','$ls_turper','$ls_horper','$ls_hcmper','$ls_tipsanper');\">";
						print "".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;
							
					case "egreso":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptaregreso('$ls_codper','$ls_nomper','$ls_apeper','$ld_fecegrper','$ls_cauegrper',";
						print "'$ls_obsegrper','$ls_estper','$ld_fecingper','$ld_fecnacper');\">".$ls_codper."</a></td>";
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
						
				    case "asignacion2":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarresponsablecedente('$ls_codper','$ls_nomper','$ls_apeper','$ls_estper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_cedper."</td>";
						print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
						print "<td>".$ls_estper."</td>";
						print "</tr>";			
						break;
						
                   case "asignacion3":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptararesponsablereceptora('$ls_codper','$ls_nomper','$ls_apeper','$ls_estper');\">".$ls_codper."</a></td>";
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
				}
			  $rs_data->MoveNext();
			}
		  $io_sql->free_result($rs_data);
		}
		print "</table>";
		unset($io_include,$io_conexion,$io_sql,$io_mensajes,$io_funciones,$io_sno,$ls_codemp);
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
  <br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2">Cat&aacute;logo de Personal </td>
      </tr>
      <tr>
        <td height="15">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
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
          <img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
    <div align="center"><br>
      <?php
	require_once("../sno/class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_operacion =$io_fun_nomina->uf_obteneroperacion();
	$ls_tipo=$io_fun_nomina->uf_obtenertipo();
	$ls_codnom=$io_fun_nomina->uf_obtenervalor_get("codnom","");
	require_once("class_funciones_activos.php");
	$fun_activos=new class_funciones_activos("../");	
	$ls_respced=$fun_activos->uf_obtenervalor_get("buscaresced","");
	$ls_codresced=$fun_activos->uf_obtenervalor_get("ls_codresced",""); 
	$ls_codreserec=$fun_activos->uf_obtenervalor_get("ls_codreserec",""); 
	$ls_buscartest=$fun_activos->uf_obtenervalor_get("buscartest",""); 
	$ls_buscar=$fun_activos->uf_obtenervalor_get("buscar",""); 
	if($ls_operacion=="BUSCAR")
	{
	    
		$ls_codper="%".$_POST["txtcodper"]."%";
		$ls_cedper="%".$_POST["txtcedper"]."%";
		$ls_nomper="%".$_POST["txtnomper"]."%";
		$ls_apeper="%".$_POST["txtapeper"]."%";
		uf_print($ls_codper, $ls_cedper, $ls_nomper, $ls_apeper, $ls_codnom, $ls_tipo,$ls_respced,$ls_codresced,$ls_buscartest,$ls_codreserec,$ls_buscar);
	}
	unset($io_fun_nomina);
?>
      </div>
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
				 hcmper,tipsanper)
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
	if(cajahoper=="1")
	{
		opener.document.form1.chkcajahoper.checked=true;
	}
	else
	{
		opener.document.form1.chkcajahoper.checked=false;
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
	opener.document.form1.cmbcauegrper.value=cauegrper;
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
	opener.document.form1.btnfamiliar.disabled=false;
	opener.document.form1.btnestudio.disabled=false;
	opener.document.form1.btntrabajo.disabled=false;
	opener.document.form1.btnimpuesto.disabled=false;
	opener.document.form1.btnpermiso.disabled=false;
	opener.document.form1.btnfideicomiso.disabled=false;
	opener.document.form1.btnvacacion.disabled=false;
  	opener.document.form1.existe.value="TRUE";
	close();
}

function aceptaregreso(codper,nomper,apeper,fecegrper,cauegrper,obsegrper,estper,fecingper,fecnacper)
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

function aceptarresponsablecedente(codper,nomper,apeper,estper)
{
	opener.document.form1.txtcodresced.value=codper;
	opener.document.form1.txtcodresced.readOnly=true;
	opener.document.form1.txtnomresced.value=nomper+" "+ apeper;
	opener.document.form1.txtnomresced.readOnly=true;
	opener.document.form1.txtestced.value=estper;	
	opener.document.form1.txtestced.readOnly=true;
	close();
}

function aceptararesponsablereceptora(codper,nomper,apeper,estper)
{
	opener.document.form1.txtcodresrece.value=codper;
	opener.document.form1.txtcodresrece.readOnly=true;
	opener.document.form1.txtnomresrec.value=nomper+" "+ apeper;
	opener.document.form1.txtnomresrec.readOnly=true;
	opener.document.form1.txtestres.value=estper;	
	opener.document.form1.txtestres.readOnly=true;
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

function aceptarrepivsssaldes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarrepivsssalhas(codper)
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
  	f.action="sigesp_snorh_cat_personal.php?codnom=<?PHP print $ls_codnom;?>&tipo=<?PHP print $ls_tipo;?>&buscaresced=<?php print $ls_respced; ?>&ls_codresced=<?php print $ls_codresced; ?>&buscartest=<?php print $ls_buscartest; ?>&ls_codreserec=<?php print $ls_codreserec; ?>&buscar=<?php print $ls_buscar; ?>";
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

</script>
</html>
