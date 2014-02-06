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
   function uf_print($as_codconc, $as_nomcon, $as_tipo)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codconc  // Código del concepto
		//				   as_nomcon  // nombre del concepto
		//				   as_tipo  // Tipo de Llamada del catálogo
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_campo,$io_fun_nomina;

		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		$io_fun_nomina->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);		
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $ls_codnom=$_SESSION["la_nomina"]["codnom"];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60>Código</td>";
		print "<td width=200>Nombre</td>";
		print "<td width=100>Signo</td>";
		print "<td width=140>Fórmula</td>";
		print "</tr>"; 
		$ls_sql="SELECT anocur, codperi, codconc, nomcon, titcon, sigcon, forcon, glocon, acumaxcon, valmincon, valmaxcon, concon, ".
				"		cueprecon, cueconcon, aplisrcon, sueintcon, intprocon, codpro, forpatcon, cueprepatcon, cueconpatcon, ".
				"		titretempcon, titretpatcon, valminpatcon, valmaxpatcon, codprov, cedben, conprenom, sueintvaccon, ".
				"		aplarccon, conprocon,estcla, intingcon, poringcon, spi_cuenta, repacucon, repconsunicon, consunicon, quirepcon, ".
				"		asifidper, asifidpat, frevarcon, persalnor,aplresenc,conperenc, codente,".
				"		(SELECT descripcion_ente ".
				"		   FROM sno_entes ".
				"		  WHERE codigo_ente = codente) as nombre_ente, 	".	
				"		(SELECT denominacion ".
				"		   FROM spi_cuentas ".
				"		  WHERE codemp='".$ls_codemp."'	".
				"		    AND spi_cuentas.spi_cuenta=sno_thconcepto.spi_cuenta) as deningcon, ".
				"		(SELECT nompro ".
				"		   FROM rpc_proveedor ".
				"		  WHERE codemp='".$ls_codemp."'	".
				"		    AND rpc_proveedor.cod_pro=sno_thconcepto.codprov) as proveedor, ".
				"		(SELECT nombene ".
				"		   FROM rpc_beneficiario ".
				"		  WHERE codemp='".$ls_codemp."'	".
				"		    AND rpc_beneficiario.ced_bene=sno_thconcepto.cedben) as beneficiario, ".
				"		(SELECT denominacion ".
				"		   FROM spg_cuentas ".
				"		  WHERE codemp=sno_thconcepto.codemp ".
				"		    AND spg_cuentas.codestpro1=substr(sno_thconcepto.codpro,1,20) ".
				"		    AND spg_cuentas.codestpro2=substr(sno_thconcepto.codpro,21,6) ".
				"		    AND spg_cuentas.codestpro3=substr(sno_thconcepto.codpro,27,3) ".
				"		    AND spg_cuentas.codestpro4=substr(sno_thconcepto.codpro,30,2) ".
				"		    AND spg_cuentas.codestpro5=substr(sno_thconcepto.codpro,32,3) ".
				"		    AND spg_cuentas.estcla=sno_thconcepto.estcla ".
				"		    AND spg_cuentas.spg_cuenta=sno_thconcepto.cueprecon) as denprecon, ".
				"		(SELECT denominacion ".
				"		   FROM scg_cuentas ".
				"		  WHERE codemp=sno_thconcepto.codemp ".
				"		    AND scg_cuentas.sc_cuenta=sno_thconcepto.cueconcon) as dencuecon, ".
				"		(SELECT denestpro1 ".
				"		   FROM spg_ep1 ".
				"		  WHERE spg_ep1.codemp=sno_thconcepto.codemp".
				"		    AND spg_ep1.codestpro1=substr(sno_thconcepto.codpro,0,26)".
				"           AND spg_ep1.estcla = sno_thconcepto.estcla) as denestpro1, ".
				"		(SELECT estcla ".
				"		   FROM spg_ep1 ".
				"		  WHERE spg_ep1.codemp='".$ls_codemp."'	".
				"		    AND spg_ep1.codestpro1=substr(sno_thconcepto.codpro,0,26)".
				"           AND spg_ep1.estcla = sno_thconcepto.estcla) as estcla1, ".
				"		(SELECT denestpro2 ".
				"		   FROM spg_ep2 ".
				"		  WHERE spg_ep2.codemp=sno_thconcepto.codemp".
				"		    AND spg_ep2.codestpro1=substr(sno_thconcepto.codpro,0,26) ".
				"		    AND spg_ep2.codestpro2=substr(sno_thconcepto.codpro,26,25)".
				"           AND spg_ep2.estcla = sno_thconcepto.estcla) as denestpro2, ".
				"		(SELECT estcla ".
				"		   FROM spg_ep2 ".
				"		  WHERE spg_ep2.codemp='".$ls_codemp."'	".
				"		    AND spg_ep2.codestpro1=substr(sno_thconcepto.codpro,0,26) ".
				"		    AND spg_ep2.codestpro2=substr(sno_thconcepto.codpro,26,25)".
				"           AND spg_ep2.estcla = sno_thconcepto.estcla ) as estcla2, ".
				"		(SELECT denestpro3 ".
				"		   FROM spg_ep3 ".
				"		  WHERE spg_ep3.codemp=sno_thconcepto.codemp".
				"		    AND spg_ep3.codestpro1=substr(sno_thconcepto.codpro,0,26) ".
				"		    AND spg_ep3.codestpro2=substr(sno_thconcepto.codpro,26,25) ".
				"		    AND spg_ep3.codestpro3=substr(sno_thconcepto.codpro,51,25)".
				"           AND spg_ep3.estcla = sno_thconcepto.estcla) as denestpro3, ".
				"		(SELECT estcla ".
				"		   FROM spg_ep3 ".
				"		  WHERE spg_ep3.codemp='".$ls_codemp."'	".
				"		    AND spg_ep3.codestpro1=substr(sno_thconcepto.codpro,0,26) ".
				"		    AND spg_ep3.codestpro2=substr(sno_thconcepto.codpro,26,25) ".
				"		    AND spg_ep3.codestpro3=substr(sno_thconcepto.codpro,51,25)".
				"           AND spg_ep3.estcla = sno_thconcepto.estcla) as estcla3, ".
				"		(SELECT denestpro4 ".
				"		   FROM spg_ep4 ".
				"		  WHERE spg_ep4.codemp=sno_thconcepto.codemp".
				"		    AND spg_ep4.codestpro1=substr(sno_thconcepto.codpro,0,26) ".
				"		    AND spg_ep4.codestpro2=substr(sno_thconcepto.codpro,26,25) ".
				"		    AND spg_ep4.codestpro3=substr(sno_thconcepto.codpro,51,25) ".
				"		    AND spg_ep4.codestpro4=substr(sno_thconcepto.codpro,76,25)".
				"           AND spg_ep4.estcla = sno_thconcepto.estcla) as denestpro4, ".
				"		(SELECT estcla ".
				"		   FROM spg_ep4 ".
				"		  WHERE spg_ep4.codemp='".$ls_codemp."'	".
				"		    AND spg_ep4.codestpro1=substr(sno_thconcepto.codpro,0,26) ".
				"		    AND spg_ep4.codestpro2=substr(sno_thconcepto.codpro,26,25) ".
				"		    AND spg_ep4.codestpro3=substr(sno_thconcepto.codpro,51,25) ".
				"		    AND spg_ep4.codestpro4=substr(sno_thconcepto.codpro,76,25)".
				"           AND spg_ep4.estcla = sno_thconcepto.estcla) as estcla4, ".
				"		(SELECT denestpro5 ".
				"		   FROM spg_ep5 ".
				"		  WHERE spg_ep5.codemp=sno_thconcepto.codemp".
				"		    AND spg_ep5.codestpro1=substr(sno_thconcepto.codpro,0,26) ".
				"		    AND spg_ep5.codestpro2=substr(sno_thconcepto.codpro,26,25) ".
				"		    AND spg_ep5.codestpro3=substr(sno_thconcepto.codpro,51,25) ".
				"		    AND spg_ep5.codestpro4=substr(sno_thconcepto.codpro,76,25) ".
				"		    AND spg_ep5.codestpro5=substr(sno_thconcepto.codpro,101,25) ".
				"           AND spg_ep5.estcla = sno_thconcepto.estcla) as denestpro5, ".
				"		(SELECT estcla ".
				"		   FROM spg_ep5 ".
				"		  WHERE spg_ep5.codemp='".$ls_codemp."'	".
				"		    AND spg_ep5.codestpro1=substr(sno_thconcepto.codpro,0,26) ".
				"		    AND spg_ep5.codestpro2=substr(sno_thconcepto.codpro,26,25) ".
				"		    AND spg_ep5.codestpro3=substr(sno_thconcepto.codpro,51,25) ".
				"		    AND spg_ep5.codestpro4=substr(sno_thconcepto.codpro,76,25) ".
				"		    AND spg_ep5.codestpro5=substr(sno_thconcepto.codpro,101,25)".
				"           AND spg_ep5.estcla = sno_thconcepto.estcla) as estcla5 ".
				"  FROM sno_thconcepto ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codnom='".$ls_codnom."'".
				"   AND codconc like '".$as_codconc."' ".
				"	AND nomcon like '".$as_nomcon."'";
				
		if(($as_tipo=="reppredes")||($as_tipo=="repprehas")||($as_tipo=="repdetpredes")||($as_tipo=="repdetprehas"))
		{		
			$ls_sql=$ls_sql."   AND sigcon ='D' ";
		}
		if(($as_tipo=="repcuanomdes")||($as_tipo=="repcuanomhas"))
		{		
			$ls_sql=$ls_sql."   AND sigcon ='A' ";
		}
		if($as_tipo=="repapopat")
		{
			$ls_sql=$ls_sql."   AND sigcon ='P' ";
		}
		$ls_sql=$ls_sql." ORDER BY codconc ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{ 
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codconc=$row["codconc"];
				$ls_nomcon=$row["nomcon"];
				$ls_titcon=$row["titcon"];
				$ls_sigcon=$row["sigcon"];
				$ls_forcon=$row["forcon"];
				$li_glocon=$row["glocon"];
				$li_acumaxcon=$io_fun_nomina->uf_formatonumerico($row["acumaxcon"]);
				$li_valmincon=$io_fun_nomina->uf_formatonumerico($row["valmincon"]);
				$li_valmaxcon=$io_fun_nomina->uf_formatonumerico($row["valmaxcon"]);
				$ls_concon=$row["concon"];
				$ls_cueprecon=$row["cueprecon"];
				$ls_cueconcon=$row["cueconcon"];
				$li_aplisrcon=$row["aplisrcon"];
				$li_aplarccon=$row["aplarccon"];
				$li_sueintcon=$row["sueintcon"];
				$li_intprocon=$row["intprocon"];
				$li_conprocon=$row["conprocon"];
				$ls_codpro=$row["codpro"];
				$ls_codestpro1=substr($ls_codpro,0,25);
				$ls_codestpro2=substr($ls_codpro,25,25);
				$ls_codestpro3=substr($ls_codpro,50,25);
				$ls_codestpro4=substr($ls_codpro,75,25);
				$ls_codestpro5=substr($ls_codpro,100,25);
				$io_fun_nomina->uf_formato_programatica_detallado($li_len1,&$ls_codestpro1);
				$io_fun_nomina->uf_formato_programatica_detallado($li_len2,&$ls_codestpro2);
				$io_fun_nomina->uf_formato_programatica_detallado($li_len3,&$ls_codestpro3);
				$io_fun_nomina->uf_formato_programatica_detallado($li_len4,&$ls_codestpro4);
				$io_fun_nomina->uf_formato_programatica_detallado($li_len5,&$ls_codestpro5);
				$ls_denestpro1=$row["denestpro1"];
				$ls_denestpro2=$row["denestpro2"];
				$ls_denestpro3=$row["denestpro3"];
				$ls_denestpro4=$row["denestpro4"];
				$ls_denestpro5=$row["denestpro5"];
				$ls_estcla1=$row["estcla1"];
				$ls_estcla2=$row["estcla2"];
				$ls_estcla3=$row["estcla3"];
				$ls_estcla4=$row["estcla4"];
				$ls_estcla5=$row["estcla5"];
				$ls_forpatcon=$row["forpatcon"];
				$ls_cueprepatcon=$row["cueprepatcon"];
				$ls_cueconpatcon=$row["cueconpatcon"];
				$ls_titretempcon=$row["titretempcon"];
				$ls_titretpatcon=$row["titretpatcon"];
				$li_valminpatcon=$io_fun_nomina->uf_formatonumerico($row["valminpatcon"]);
				$li_valmaxpatcon=$io_fun_nomina->uf_formatonumerico($row["valmaxpatcon"]);
				$li_sueintvaccon=$row["sueintvaccon"];
				$li_conprenom=$row["conprenom"];
				$ls_denprecon=$row["denprecon"];
				$ls_dencuecon=$row["dencuecon"];
				$ls_dencueprepat="";//=$row["dencueprepat"] -->Luiser Blanco 08/12/2007 lo coloque en vacio porque el query no esta generando este campo;
				$ls_dencueconpat="";//=$row["dencueconpat"] -->Luiser Blanco 08/12/2007 lo coloque en vacio porque el query no esta generando este campo;
				$ls_codpro=$row["codprov"];
				$ls_cedben=$row["cedben"];
				$ls_proveedor=$row["proveedor"];
				$ls_beneficiario=$row["beneficiario"];
				$ls_repacucon=$row["repacucon"];
				$ls_repconsunicon=$row["repconsunicon"];
				$ls_consunicon=$row["consunicon"];
				$ls_descon="";
				$ls_codcon="";
				$ls_nombre="";
				$li_intingcon=$row["intingcon"];
				$li_poringcon=number_format($row["poringcon"],2,",",".");
				$ls_spicuenta=$row["spi_cuenta"];
				$ls_deningcon=$row["deningcon"];
				$ls_quirepcon=$row["quirepcon"];
				$ls_asifidper=$row["asifidper"];
				$ls_asifidpat=$row["asifidpat"];
				$ls_frevarcon=$row["frevarcon"];
				$ls_persalnor=$row["persalnor"];
				$ls_perenc=$row["conperenc"];
				$ls_aplresenc=$row["aplresenc"];
				$ls_codente=$row["codente"];
				$ls_txtente=$row["nombre_ente"];
				if($ls_codpro=="----------")
				{
					$ls_descon="B";
					$ls_codcon=$ls_cedben;
					$ls_nombre=$ls_beneficiario;
				}
				if($ls_cedben=="----------")
				{
					$ls_descon="P";
					$ls_codcon=$ls_codpro;
					$ls_nombre=$ls_proveedor;
				}
				switch ($ls_sigcon)
				{
					case "A":
						$ls_signo="Asignación";
						break;
	
					case "D":
						$ls_signo="Deducción";
						break;
	
					case "P":
						$ls_signo="Aporte Patronal";
						break;
	
					case "R":
						$ls_signo="Reporte";
						break;
	
					case "B":
						$ls_signo="Reintegro Deducción";
						break;
	
					case "E":
						$ls_signo="Reintegro Asignación";
						break;
				}			
				switch ($as_tipo)
				{
					case "":  // el llamado se hace desde sigesp_sno_d_hconcepto.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codconc','$ls_nomcon','$ls_titcon','$ls_sigcon','$ls_forcon',";
						print "'$li_glocon','$li_acumaxcon','$li_valmincon','$li_valmaxcon','$ls_concon','$ls_cueprecon','$ls_cueconcon',";
						print "'$li_aplisrcon','$li_sueintcon','$li_intprocon','$ls_codestpro1','$ls_codestpro2','$ls_codestpro3',";
						print "'$ls_forpatcon','$ls_cueprepatcon','$ls_cueconpatcon','$ls_titretempcon','$ls_titretpatcon','$li_valminpatcon',";
						print "'$li_valmaxpatcon','$li_sueintvaccon','$li_conprenom','$ls_denestpro1','$ls_denestpro2','$ls_denestpro3',";
						print "'$ls_denprecon','$ls_dencuecon','$ls_dencueprepat','$ls_dencueconpat','$ls_descon','$ls_codcon','$ls_nombre',";
						print "'$li_aplarccon','$ls_codestpro4','$ls_codestpro5','$ls_denestpro4','$ls_denestpro5','$li_conprocon',";
						print "'$ls_estcla1','$ls_estcla2','$ls_estcla3','$ls_estcla4','$ls_estcla5','$li_intingcon','$li_poringcon',";
						print "'$ls_spicuenta','$ls_deningcon','$ls_repacucon','$ls_repconsunicon','$ls_consunicon','$ls_quirepcon',";
						print "'$ls_asifidper','$ls_asifidpat','$ls_frevarcon','$ls_persalnor','$ls_perenc',				'$ls_aplresenc','$ls_codente','$ls_txtente');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_signo."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						

					case "MODIFICAR":  // el llamado se hace desde sigesp_sno_p_hmodificarconcepto.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarmodificar('$ls_codconc','$ls_nomcon','$ls_titcon',";
						print "'$li_aplisrcon','$li_sueintcon','$li_intprocon','$ls_codestpro1','$ls_codestpro2','$ls_codestpro3',";
						print "'$ls_denestpro1','$ls_denestpro2','$ls_denestpro3','$ls_codestpro4','$ls_codestpro5','$ls_denestpro4',";
						print "'$ls_denestpro5','$ls_modalidad','$ls_cueprecon','$ls_cueconcon','$ls_cueprepatcon','$ls_cueconpatcon',";
						print "'$ls_denprecon','$ls_dencuecon','$ls_dencueprepat','$ls_dencueconpat','$ls_descon','$ls_codcon',";
						print "'$ls_nombre','$ls_sigcon','$li_sueintvaccon','$li_aplarccon','$li_conprocon',";
						print "'$ls_estcla1','$ls_estcla2','$ls_estcla3','$ls_estcla4','$ls_estcla5','$li_intingcon','$li_poringcon',";
						print "'$ls_spicuenta','$ls_deningcon','$ls_asifidper','$ls_asifidpat');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_signo."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						

					case "replisconcdes": // el llamado se hace desde sigesp_sno_r_hlistadoconcepto.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisconcdes('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_signo."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "replisconchas": // el llamado se hace desde sigesp_sno_r_hlistadoconcepto.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisconchas('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_signo."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
						
					case "repapopat": // el llamado se hace desde sigesp_sno_r_haportepatronal.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepapopat('$ls_codconc','$ls_nomcon');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_signo."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;							

					case "represconcdes": // el llamado se hace desde sigesp_sno_r_hresumenconcepto.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepresconcdes('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_signo."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "represconchas": // el llamado se hace desde sigesp_sno_r_hresumenconcepto.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepresconchas('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_signo."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "represconcunides": // el llamado se hace desde sigesp_sno_r_resumenconceptounidad.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepresconcunides('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_signo."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "represconcunihas": // el llamado se hace desde sigesp_sno_r_resumenconceptounidad.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepresconcunihas('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_signo."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
						
					case "repcuanomdes": // el llamado se hace desde sigesp_sno_r_cuadrenomina.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepcuanomdes('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_signo."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "repcuanomhas": // el llamado se hace desde sigesp_sno_r_cuadrenomina.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepcuanomhas('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_signo."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "reppredes": // el llamado se hace desde sigesp_sno_r_hlistadoprestamo.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreppredes('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "repprehas": // el llamado se hace desde sigesp_sno_r_hlistadoprestamo.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepprehas('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "repdetpredes": // el llamado se hace desde sigesp_sno_r_hlistadoprestamo.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepdetpredes('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "repdetprehas": // el llamado se hace desde sigesp_sno_r_hlistadoprestamo.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepdetprehas('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
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
		unset($ls_codemp);
		unset($ls_codnom);
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Concepto</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Concepto</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="txtcodconc" type="text" id="txtcodconc" size="30" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Descripci&oacute;n</div></td>
        <td><div align="left">
          <input name="txtnomcon" type="text" id="txtnomcon" size="30" maxlength="30" onKeyPress="javascript: ue_mostrar(this,event);">
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
	if($ls_operacion=="BUSCAR")
	{
		$ls_codconc="%".$_POST["txtcodconc"]."%";
		$ls_nomcon="%".$_POST["txtnomcon"]."%";
		uf_print($ls_codconc, $ls_nomcon, $ls_tipo);
	}
	else
	{
		$ls_codconc="%%";
		$ls_nomcon="%%";
		uf_print($ls_codconc, $ls_nomcon, $ls_tipo);
	}
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codconc,nomcon,titcon,sigcon,forcon,glocon,acumaxcon,valmincon,valmaxcon,concon,cueprecon,cueconcon,aplisrcon,
				 sueintcon,intprocon,codestpro1,codestpro2,codestpro3,forpatcon,cueprepatcon,cueconpatcon,titretempcon,titretpatcon,
				 valminpatcon,valmaxpatcon,sueintvaccon,conprenom,denestpro1,denestpro2,denestpro3,denprecon,dencuecon,dencueprepat,
				 dencueconpat,descon,codcon,nombre,aplarccon,codestpro4,codestpro5,denestpro4,denestpro5,conprocon,
				 estcla1,estcla2,estcla3,estcla4,estcla5,intingcon,poringcon,spicuenta,deningcon,repacucon,repconsunicon,consunicon,quirepcon,
				 asifidper,asifidpat,frevarcon,persalnor,perenc,aplresenc,codente,txtente)
{
	opener.document.form1.txtcodconc.value="";
	opener.document.form1.txtnomcon.value="";
	opener.document.form1.txttitcon.value="";
	opener.document.form1.cmbsigcon.value="";
	opener.document.form1.txtforcon.value="";
	opener.document.form1.txtacumaxcon.value="";
	opener.document.form1.txtvalmincon.value="";
	opener.document.form1.txtvalmaxcon.value="";
	opener.document.form1.txtconcon.value="";
	opener.document.form1.txtcuepre.value="";
	opener.document.form1.txtcuecon.value="";
	opener.document.form1.txtcodestpro1.value="";
	opener.document.form1.txtcodestpro2.value="";
	opener.document.form1.txtcodestpro3.value="";
	opener.document.form1.txtcodestpro4.value="";
	opener.document.form1.txtcodestpro5.value="";
	opener.document.form1.txtdenestpro1.value="";
	opener.document.form1.txtdenestpro2.value="";
	opener.document.form1.txtdenestpro3.value="";
	opener.document.form1.txtdenestpro4.value="";
	opener.document.form1.txtdenestpro5.value="";	
	opener.document.form1.txtforpatcon.value="";
	opener.document.form1.txtcueprepat.value="";
	opener.document.form1.txtcueconpat.value="";
	opener.document.form1.txttitretempcon.value="";
	opener.document.form1.txttitretpatcon.value="";
	opener.document.form1.txtvalminpatcon.value="";
	opener.document.form1.txtvalmaxpatcon.value="";
	opener.document.form1.txtdencuepre.value="";
	opener.document.form1.txtdencuecon.value="";
	opener.document.form1.txtdencueprepat.value="";
	opener.document.form1.txtdencueconpat.value="";
	opener.document.form1.txtcueingcon.value="";
	opener.document.form1.txtdencueing.value="";
	opener.document.form1.txtporingcon.value="0,00";
	opener.document.form1.cmbquirepcon.value="-";
	opener.document.form1.chkglocon.checked=false;
	opener.document.form1.chkintingcon.checked=false;
	opener.document.form1.chkaplisrcon.checked=false;
	opener.document.form1.chkaplarccon.checked=false;
	opener.document.form1.chksueintcon.checked=false;
	opener.document.form1.chkintprocon.checked=false;
	opener.document.form1.chkconprocon.checked=false;
	opener.document.form1.chksueintvaccon.checked=false;
	opener.document.form1.chkconprenom.checked=false;
	opener.document.form1.chkasifidper.checked=false;
	opener.document.form1.chkasifidpat.checked=false;
	opener.document.form1.chkfrevarcon.checked=false;
	opener.document.form1.chkpersalnor.checked=false;
	opener.document.form1.chkperenc.checked=false;
	opener.document.form1.chkaplresenc.checked=false;
	opener.document.form1.cmbdescon.value=" ";
	opener.document.form1.txtcodproben.value="";
	opener.document.form1.txtnombre.value="";
	opener.document.form1.chkrepacucon.checked=false;
	opener.document.form1.chkrepconsunicon.checked=false;
	opener.document.form1.txtconsunicon.value="";
	opener.document.form1.txtcodconc.value=codconc;
	opener.document.form1.txtcodconc.readOnly=true;
	opener.document.form1.txtnomcon.value=nomcon;
	opener.document.form1.txtnomcon.readOnly=true;
	opener.document.form1.txttitcon.value=titcon;
	opener.document.form1.txttitcon.readOnly=true;
	opener.document.form1.cmbsigcon.value=sigcon;
	opener.document.form1.cmbsigcon.disabled=true;
	opener.document.form1.txtforcon.value=forcon;
	opener.document.form1.txtforcon.readOnly=true;
	opener.document.form1.txtacumaxcon.value=acumaxcon;
	opener.document.form1.txtacumaxcon.readOnly=true;
	opener.document.form1.txtvalmincon.value=valmincon;
	opener.document.form1.txtvalmincon.readOnly=true;
	opener.document.form1.txtvalmaxcon.value=valmaxcon;
	opener.document.form1.txtvalmaxcon.readOnly=true;
	opener.document.form1.txtconcon.value=concon;
	opener.document.form1.txtconcon.readOnly=true;
	opener.document.form1.txtcuepre.value=cueprecon;
	opener.document.form1.txtcuepre.readOnly=true;
	opener.document.form1.txtcuecon.value=cueconcon;
	opener.document.form1.txtcuecon.readOnly=true;
	opener.document.form1.txtcodestpro1.value=codestpro1;
	opener.document.form1.txtcodestpro1.readOnly=true;
	opener.document.form1.txtcodestpro2.value=codestpro2;
	opener.document.form1.txtcodestpro2.readOnly=true;
	opener.document.form1.txtcodestpro3.value=codestpro3;
	opener.document.form1.txtcodestpro3.readOnly=true;
	opener.document.form1.txtcodestpro4.value=codestpro4;
	opener.document.form1.txtcodestpro4.readOnly=true;
	opener.document.form1.txtcodestpro5.value=codestpro5;
	opener.document.form1.txtcodestpro5.readOnly=true;
	opener.document.form1.txtdenestpro1.value=denestpro1;
	opener.document.form1.txtdenestpro1.readOnly=true;
	opener.document.form1.txtdenestpro2.value=denestpro2;
	opener.document.form1.txtdenestpro2.readOnly=true;
	opener.document.form1.txtdenestpro3.value=denestpro3;
	opener.document.form1.txtdenestpro3.readOnly=true;
	opener.document.form1.txtdenestpro4.value=denestpro4;
	opener.document.form1.txtdenestpro4.readOnly=true;
	opener.document.form1.txtdenestpro5.value=denestpro5;
	opener.document.form1.txtdenestpro5.readOnly=true;
	opener.document.form1.txtforpatcon.value=forpatcon;
	opener.document.form1.txtforpatcon.readOnly=true;
	opener.document.form1.txtcueprepat.value=cueprepatcon;
	opener.document.form1.txtcueprepat.readOnly=true;
	opener.document.form1.txtcueconpat.value=cueconpatcon;
	opener.document.form1.txtcueconpat.readOnly=true;
	opener.document.form1.txttitretempcon.value=titretempcon;
	opener.document.form1.txttitretempcon.readOnly=true;
	opener.document.form1.txttitretpatcon.value=titretpatcon;
	opener.document.form1.txttitretpatcon.readOnly=true;
	opener.document.form1.txtvalminpatcon.value=valminpatcon;
	opener.document.form1.txtvalminpatcon.readOnly=true;
	opener.document.form1.txtvalmaxpatcon.value=valmaxpatcon;
	opener.document.form1.txtvalmaxpatcon.readOnly=true;
	opener.document.form1.txtdencuepre.value=denprecon;
	opener.document.form1.txtdencuepre.readOnly=true;
	opener.document.form1.txtdencuecon.value=dencuecon;
	opener.document.form1.txtdencuecon.readOnly=true;
	opener.document.form1.txtdencueprepat.value=dencueprepat;
	opener.document.form1.txtdencueprepat.readOnly=true;
	opener.document.form1.txtdencueconpat.value=dencueconpat;
	opener.document.form1.txtdencueconpat.readOnly=true;
	opener.document.form1.cmbdescon.value=descon;
	opener.document.form1.cmbdescon.disabled=true;
	opener.document.form1.txtcodproben.value=codcon;
	opener.document.form1.txtcodproben.disabled=true;
	opener.document.form1.txtnombre.value=nombre;
	opener.document.form1.txtnombre.disabled=true;
	opener.document.form1.txtconsunicon.value=consunicon;
	opener.document.form1.txtcueingcon.value=spicuenta;
	opener.document.form1.txtdencueing.value=deningcon;
	opener.document.form1.txtporingcon.value=poringcon;
	opener.document.form1.cmbquirepcon.value=quirepcon;
	opener.document.form1.txt_codente.value=codente;
	opener.document.form1.txt_ente.value=txtente;
	
	if(intingcon=="1")
	{
		opener.document.form1.chkintingcon.checked=true;
	}	
	else
	{
		opener.document.form1.chkintingcon.checked=false;
	}
	if(glocon=="1")
	{
		opener.document.form1.chkglocon.checked=true;
	}
	else
	{
		opener.document.form1.chkglocon.checked=false;
	}
	if(aplisrcon=="1")
	{
		opener.document.form1.chkaplisrcon.checked=true;
	}
	else
	{
		opener.document.form1.chkaplisrcon.checked=false;
	}
	if(sueintcon=="1")
	{
		opener.document.form1.chksueintcon.checked=true;
	}
	else
	{
		opener.document.form1.chksueintcon.checked=false;
	}
	if(intprocon=="1")
	{
		opener.document.form1.chkintprocon.checked=true;
	}
	else
	{
		opener.document.form1.chkintprocon.checked=false;
	}
	if(sueintvaccon=="1")
	{
		opener.document.form1.chksueintvaccon.checked=true;
	}
	else
	{
		opener.document.form1.chksueintvaccon.checked=false;
	}
	if(conprenom=="1")
	{
		opener.document.form1.chkconprenom.checked=true;
	}
	else
	{
		opener.document.form1.chkconprenom.checked=false;
	}
	if(aplarccon=="1")
	{
		opener.document.form1.chkaplarccon.checked=true;
	}
	else
	{
		opener.document.form1.chkaplarccon.checked=false;
	}
	if(conprocon=="1")
	{
		opener.document.form1.chkconprocon.checked=true;
	}
	else
	{
		opener.document.form1.chkconprocon.checked=false;
	}
	if(repacucon=="1")
	{
		opener.document.form1.chkrepacucon.checked=true;
	}
	else
	{
		opener.document.form1.chkrepacucon.checked=false;
	}
	if(repconsunicon=="1")
	{
		opener.document.form1.chkrepconsunicon.checked=true;
	}
	else
	{
		opener.document.form1.chkrepconsunicon.checked=false;
	}
	if(asifidper=="1")
	{
		opener.document.form1.chkasifidper.checked=true;
	}
	else
	{
		opener.document.form1.chkasifidper.checked=false;
	}
	if(asifidpat=="1")
	{
		opener.document.form1.chkasifidpat.checked=true;
	}
	else
	{
		opener.document.form1.chkasifidpat.checked=false;
	}
	if(frevarcon=="1")
	{
		opener.document.form1.chkfrevarcon.checked=true;
	}
	else
	{
		opener.document.form1.chkfrevarcon.checked=false;
	}
	if(persalnor=="1")
	{
		opener.document.form1.chkpersalnor.checked=true;
	}
	else
	{
		opener.document.form1.chkpersalnor.checked=false;
	}
	if(perenc=="1")
	{
		opener.document.form1.chkperenc.checked=true;
	}
	else
	{
		opener.document.form1.chkperenc.checked=false;
	}
	if(aplresenc=="1")
	{
		opener.document.form1.chkaplresenc.checked=true;
	}
	else
	{
		opener.document.form1.chkaplresenc.checked=false;
	}
	close();
}

function aceptarmodificar(codconc,nomcon,titcon,aplisrcon,sueintcon,intprocon,codestpro1,codestpro2,codestpro3,denestpro1,
						  denestpro2,denestpro3,codestpro4,codestpro5,denestpro4,denestpro5,modalidad,cueprecon,cueconcon,
						  cueprepatcon,cueconpatcon,denprecon,dencuecon,dencueprepat,dencueconpat,descon,codcon,nombre,sigcon,
						  sueintvaccon,aplarccon,conprocon,estcla1,estcla2,estcla3,estcla4,estcla5,intingcon,poringcon,spicuenta,
						  deningcon,asifidper,asifidpat)
{
	opener.document.form1.txtcodconc.value="";
	opener.document.form1.txtnomcon.value="";
	opener.document.form1.txttitcon.value="";
	opener.document.form1.txtcodestpro1.value="";
	opener.document.form1.txtcodestpro2.value="";
	opener.document.form1.txtcodestpro3.value="";
	opener.document.form1.txtcodestpro4.value="";
	opener.document.form1.txtcodestpro5.value="";
	opener.document.form1.txtdenestpro1.value="";
	opener.document.form1.txtdenestpro2.value="";
	opener.document.form1.txtdenestpro3.value="";
	opener.document.form1.txtdenestpro4.value="";
	opener.document.form1.txtdenestpro5.value="";
	opener.document.form1.txtcueingcon.value="";
	opener.document.form1.txtdencueing.value="";
	opener.document.form1.txtporingcon.value="0,00";
	opener.document.form1.chkintingcon.checked=false;
	opener.document.form1.chkaplisrcon.checked=false;
	opener.document.form1.chksueintcon.checked=false;
	opener.document.form1.chkintprocon.checked=false;
	opener.document.form1.chksueintvaccon.checked=false;
	opener.document.form1.chkaplarccon.checked=false;
	opener.document.form1.chkaplisrcon.disabled=true;
	opener.document.form1.chkconprocon.checked=false;
	opener.document.form1.chkasifidper.checked=false;
	opener.document.form1.chkasifidpat.checked=false;
	opener.document.images["estpro1"].style.visibility="hidden";
	opener.document.images["estpro2"].style.visibility="hidden";
	opener.document.images["estpro3"].style.visibility="hidden";
	opener.document.images["cuentaingreso"].style.visibility="hidden";
	if(modalidad=="2")
	{
		opener.document.images["estpro4"].style.visibility="hidden";
		opener.document.images["estpro5"].style.visibility="hidden";
	}
	opener.document.form1.txtcodconc.value=codconc;
	opener.document.form1.txtcodconc.readOnly=true;
	opener.document.form1.txtnomcon.value=nomcon;
	opener.document.form1.txtnomcon.readOnly=true;
	opener.document.form1.txttitcon.value=titcon;
	opener.document.form1.txttitcon.readOnly=true;
	opener.document.form1.txtcodestpro1.value=codestpro1;
	opener.document.form1.txtdenestpro1.value=denestpro1;
	opener.document.form1.txtcodestpro1.readOnly=true;
	opener.document.form1.txtestcla1.value=estcla1;
	opener.document.form1.txtcodestpro2.value=codestpro2;
	opener.document.form1.txtcodestpro2.readOnly=true;
	opener.document.form1.txtestcla2.value=estcla2;
	opener.document.form1.txtcodestpro3.value=codestpro3;
	opener.document.form1.txtcodestpro3.readOnly=true;
	opener.document.form1.txtestcla3.value=estcla3;
	opener.document.form1.txtcodestpro4.value=codestpro4;
	opener.document.form1.txtcodestpro4.readOnly=true;
	opener.document.form1.txtestcla4.value=estcla4;
	opener.document.form1.txtcodestpro5.value=codestpro5;
	opener.document.form1.txtcodestpro5.readOnly=true;
	opener.document.form1.txtestcla5.value=estcla5;
	opener.document.form1.txtdenestpro1.value=denestpro1;
	opener.document.form1.txtdenestpro1.readOnly=true;
	opener.document.form1.txtdenestpro2.value=denestpro2;
	opener.document.form1.txtdenestpro2.readOnly=true;
	opener.document.form1.txtdenestpro3.value=denestpro3;
	opener.document.form1.txtdenestpro3.readOnly=true;
	opener.document.form1.txtdenestpro4.value=denestpro4;
	opener.document.form1.txtdenestpro4.readOnly=true;
	opener.document.form1.txtdenestpro5.value=denestpro5;
	opener.document.form1.txtdenestpro5.readOnly=true;
	opener.document.form1.txtcuepre.value=cueprecon;
	opener.document.form1.txtcuepre.readOnly=true;
	opener.document.form1.txtdencuepre.value=denprecon;
	opener.document.form1.txtdencuepre.readOnly=true;
	opener.document.form1.txtcuecon.value=cueconcon;
	opener.document.form1.txtcuecon.readOnly=true;
	opener.document.form1.txtdencuecon.value=dencuecon;
	opener.document.form1.txtdencuecon.readOnly=true;
	opener.document.form1.txtcueprepat.value=cueprepatcon;
	opener.document.form1.txtcueprepat.readOnly=true;
	opener.document.form1.txtdencueprepat.value=dencueprepat;
	opener.document.form1.txtdencueprepat.readOnly=true;
	opener.document.form1.txtcueconpat.value=cueconpatcon;
	opener.document.form1.txtcueconpat.readOnly=true;
	opener.document.form1.txtdencueconpat.value=dencueconpat;
	opener.document.form1.txtdencueconpat.readOnly=true;
	opener.document.form1.cmbdescon.value=descon;
	opener.document.form1.txtcodproben.value=codcon;
	opener.document.form1.txtcodproben.readOnly=true;
	opener.document.form1.txtnombre.value=nombre;
	opener.document.form1.txtnombre.readOnly=true;
	opener.document.form1.txtcueingcon.value=spicuenta;
	opener.document.form1.txtdencueing.value=deningcon;
	opener.document.form1.txtporingcon.value=poringcon;
	if(intingcon=="1")
	{
		opener.document.form1.chkintingcon.checked=true;
	opener.document.images["cuentaingreso"].style.visibility="visible";
	}	
	if(aplisrcon=="1")
	{
		opener.document.form1.chkaplisrcon.checked=true;
	}
	if(sueintcon=="1")
	{
		opener.document.form1.chksueintcon.checked=true;
	}
	if(sueintvaccon=="1")
	{
		opener.document.form1.chksueintvaccon.checked=true;
	}
	if(aplarccon=="1")
	{
		opener.document.form1.chkaplarccon.checked=true;
	}
	if(conprocon=="1")
	{
		opener.document.form1.chkconprocon.checked=true;
	}
	if(intprocon=="1")
	{
		opener.document.form1.chkintprocon.checked=true;
		opener.document.images["estpro1"].style.visibility="visible";
		opener.document.images["estpro2"].style.visibility="visible";
		opener.document.images["estpro3"].style.visibility="visible";
		if(modalidad=="2")
		{
			opener.document.images["estpro4"].style.visibility="visible";
			opener.document.images["estpro5"].style.visibility="visible";
		}
	}
	opener.document.form1.cmbdescon.disabled=true;
	opener.document.form1.txtsigcon.value=sigcon;
	opener.document.images["cuentapresupuesto"].style.visibility="hidden";
	opener.document.images["cuentaabono"].style.visibility="hidden";
	opener.document.images["cuentapresupuestopatron"].style.visibility="hidden";
	opener.document.images["cuentaabonopatron"].style.visibility="hidden";
	if(asifidper=="1")
	{
		opener.document.form1.chkasifidper.checked=true;
	}
	if(asifidpat=="1")
	{
		opener.document.form1.chkasifidpat.checked=true;
	}
	if(sigcon=="A")
	{
		opener.document.images["cuentapresupuesto"].style.visibility="visible";
		opener.document.images["cuentaabono"].style.visibility="hidden";
	}
	if(sigcon=="E")
	{
		opener.document.images["cuentapresupuesto"].style.visibility="visible";
		opener.document.images["cuentaabono"].style.visibility="hidden";
	}
	if(sigcon=="D")
	{
		opener.document.images["cuentapresupuesto"].style.visibility="hidden";
		opener.document.images["cuentaabono"].style.visibility="visible";
	}
	if(sigcon=="P")
	{
		opener.document.images["cuentapresupuesto"].style.visibility="hidden";
		opener.document.images["cuentaabono"].style.visibility="visible";
		opener.document.images["cuentapresupuestopatron"].style.visibility="visible";
		opener.document.images["cuentaabonopatron"].style.visibility="visible";
		opener.document.form1.cmbdescon.disabled=false;
	}
	if(sigcon=="B")
	{
		opener.document.images["cuentapresupuesto"].style.visibility="hidden";
		opener.document.images["cuentaabono"].style.visibility="visible";
	}
	close();
}

function aceptarreplisconcdes(codconc)
{
	opener.document.form1.txtcodconcdes.value=codconc;
	opener.document.form1.txtcodconcdes.readOnly=true;
	opener.document.form1.txtcodconchas.value="";
	close();
}

function aceptarreplisconchas(codconc)
{
	if(opener.document.form1.txtcodconcdes.value<=codconc)
	{
		opener.document.form1.txtcodconchas.value=codconc;
		opener.document.form1.txtcodconchas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del concepto inválido");
	}
}

function aceptarrepapopat(codconc,nomcon)
{
	opener.document.form1.txtcodconc.value=codconc;
	opener.document.form1.txtcodconc.readOnly=true;
	opener.document.form1.txtnomcon.value=nomcon;
	opener.document.form1.txtnomcon.readOnly=true;
	close();
}

function aceptarrepresconcdes(codconc)
{
	opener.document.form1.txtcodconcdes.value=codconc;
	opener.document.form1.txtcodconcdes.readOnly=true;
	opener.document.form1.txtcodconchas.value="";
	close();
}

function aceptarrepresconchas(codconc)
{
	if(opener.document.form1.txtcodconcdes.value<=codconc)
	{
		opener.document.form1.txtcodconchas.value=codconc;
		opener.document.form1.txtcodconchas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del concepto inválido");
	}
}

function aceptarrepresconcunides(codconc)
{
	opener.document.form1.txtcodconcdes.value=codconc;
	opener.document.form1.txtcodconcdes.readOnly=true;
	opener.document.form1.txtcodconchas.value="";
	close();
}

function aceptarrepresconcunihas(codconc)
{
	if(opener.document.form1.txtcodconcdes.value<=codconc)
	{
		opener.document.form1.txtcodconchas.value=codconc;
		opener.document.form1.txtcodconchas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del concepto inválido");
	}
}

function aceptarrepcuanomdes(codconc)
{
	opener.document.form1.txtcodconcdes.value=codconc;
	opener.document.form1.txtcodconcdes.readOnly=true;
	opener.document.form1.txtcodconchas.value="";
	close();
}

function aceptarrepcuanomhas(codconc)
{
	if(opener.document.form1.txtcodconcdes.value<=codconc)
	{
		opener.document.form1.txtcodconchas.value=codconc;
		opener.document.form1.txtcodconchas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del concepto inválido");
	}
}

function aceptarreppredes(codconc)
{
	opener.document.form1.txtcodconcdes.value=codconc;
	opener.document.form1.txtcodconcdes.readOnly=true;
	opener.document.form1.txtcodconchas.value="";
	close();
}

function aceptarrepprehas(codconc)
{
	if(opener.document.form1.txtcodconcdes.value<=codconc)
	{
		opener.document.form1.txtcodconchas.value=codconc;
		opener.document.form1.txtcodconchas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del concepto inválido");
	}
}

function aceptarrepdetpredes(codconc)
{
	opener.document.form1.txtcodconcdes.value=codconc;
	opener.document.form1.txtcodconcdes.readOnly=true;
	opener.document.form1.txtcodconchas.value="";
	close();
}

function aceptarrepdetprehas(codconc)
{
	if(opener.document.form1.txtcodconcdes.value<=codconc)
	{
		opener.document.form1.txtcodconchas.value=codconc;
		opener.document.form1.txtcodconchas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del concepto inválido");
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
  	f.action="sigesp_sno_cat_hconcepto.php?tipo=<?php print $ls_tipo;?>";
  	f.submit();
}
</script>
</html>
