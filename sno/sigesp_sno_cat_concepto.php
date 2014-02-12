<?php
   session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
	$li_longestpro1= (25-$ls_loncodestpro1);
	$ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
	$li_longestpro2= (25-$ls_loncodestpro2);
	$ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
	$li_longestpro3= (25-$ls_loncodestpro3);
	$ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
	$li_longestpro4= (25-$ls_loncodestpro4);
	$ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
	$li_longestpro5= (25-$ls_loncodestpro5);
	$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
   //--------------------------------------------------------------
   function uf_print($as_codconc, $as_nomcon, $as_codnomdes, $as_codnomhas, $as_tipo)
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
		global $ls_campo;
		global $ls_loncodestpro1,$li_longestpro1,$ls_loncodestpro2,$li_longestpro2,$ls_loncodestpro3,$li_longestpro3;
		global $ls_loncodestpro4,$li_longestpro4,$ls_loncodestpro5,$li_longestpro5,$ls_modalidad;
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
		if(array_key_exists("la_nomina",$_SESSION))
		{
        	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		}
		else
		{
			$ls_codnom="0000";
		}
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60>Código</td>";
		print "<td width=200>Nombre</td>";
		print "<td width=100>Signo</td>";
		print "<td width=140>Fórmula</td>";
		print "</tr>";
		$ls_sql="SELECT codconc,nomcon,sigcon,forcon,forpatcon,intprocon ".
				"  FROM sno_concepto ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codnom='".$ls_codnom."'";
		if($as_tipo=="VACACION")
		{		
			$ls_sql=$ls_sql."   AND (NOT codconc IN (SELECT codconc ".
							"						   FROM sno_conceptovacacion ".
							"						  WHERE (codemp='".$ls_codemp."') ".
							"						    AND (codnom='".$ls_codnom."')))";
		}
		if(($as_tipo=="PRESTAMO")||($as_tipo=="reppredes")||($as_tipo=="repprehas")||
		   ($as_tipo=="repdetpredes")||($as_tipo=="repdetprehas"))
		{		
			$ls_sql=$ls_sql."   AND sigcon ='D' ";
		}
		if(($as_tipo=="AJUSTARAPORTE")||($as_tipo=="repapopat"))
		{		
			$ls_sql=$ls_sql."   AND sigcon ='P' ";
		}
		if(($as_tipo=="repcuanomdes")||($as_tipo=="repcuanomhas"))
		{		
			$ls_sql=$ls_sql."   AND sigcon ='A' ";
		}		
		if($as_tipo=="repapopatcon")
		{
			$ls_sql="SELECT codconc,nomcon,sigcon,forcon,forpatcon,intprocon ".
					"  FROM sno_concepto ".
					" WHERE codemp='".$ls_codemp."'".
					"   AND codnom>='".$as_codnomdes."' ".
					"   AND codnom<='".$as_codnomhas."' ".
					"   AND sigcon ='P' ";
		}
		if(($as_tipo=="repcesticdes")||($as_tipo=="repcestichas")||($as_tipo=="repcondes")||($as_tipo=="repconhas")||($as_tipo=="archtxt")||($as_tipo=="archtxt2")||($as_tipo=="mintra"))
		{
			$ls_sql="SELECT codconc,nomcon,sigcon,forcon,forpatcon,intprocon ".
					"  FROM sno_concepto ".
					" WHERE codemp='".$ls_codemp."'".
					"   AND codnom>='".$as_codnomdes."'".
					"   AND codnom<='".$as_codnomhas."'";				   
		}
		if (($as_tipo=="archtxt")||($as_tipo=="archtxt2"))
		{		
			$ls_sql=$ls_sql."   AND codconc IN ('0000020003','0000020005','0000020007','0000020008','0000020014') ";
		}
		$ls_sql=$ls_sql."   AND codconc like '".$as_codconc."' AND nomcon like '".$as_nomcon."'".
						" GROUP BY codconc,nomcon,sigcon,forcon,forpatcon,intprocon  ".
						" ORDER BY codconc ";
		//---------------------Agregado el 26/08/200/---------------------------------------------
		if(($as_tipo=="cajaahorro")||($as_tipo=="servasi")||($as_tipo=="conhipes")||($as_tipo=="conhipamp")||($as_tipo=="conhipcon")||($as_tipo=="conhiphip")||($as_tipo=="conhiplph")||($as_tipo=="conhipvivi")||($as_tipo=="conper")||($as_tipo=="conturi")||($as_tipo=="conpro")||($as_tipo=="conasi")||($as_tipo=="convehi")||($as_tipo=="concomi")||($as_tipo=="concfpj")||($as_tipo=="conclph")||($as_tipo=="concfpa")||($as_tipo=="concsuelant"))
		{
			$ls_sql="  SELECT codconc, MAX(nomcon) as nomcon,                          ".
					"		  MAX(sigcon) as sigcon, MAX(forcon) as forcon,            ".
					"		  MAX(forpatcon) as forpatcon,MAX(intprocon) as intprocon  ". 
					"	 FROM sno_concepto                                             ".
					"   WHERE codemp='".$ls_codemp."'                                  ".
					"   GROUP BY codconc                                               ".
					"   ORDER BY codconc                                               ";		
		}
		//---------------------------------------------------------------------------------------
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
				$ls_sigcon=$row["sigcon"];
				$ls_intprocon=$row["intprocon"];
				$ls_forcon=$row["forcon"];
				$ls_forpatcon=$row["forpatcon"];
				switch ($ls_sigcon)
				{
					case "A":
						$ls_sigcon="Asignación";
						break;
	
					case "D":
						$ls_sigcon="Deducción";
						break;
	
					case "P":
						$ls_sigcon="Aporte Patronal";
						break;
	
					case "R":
						$ls_sigcon="Reporte";
						break;
	
					case "B":
						$ls_sigcon="Reintegro Deducción";
						break;
	
					case "E":
						$ls_sigcon="Reintegro Asignación";
						break;
				}			
				switch ($as_tipo)
				{
					case "":  // el llamado se hace desde sigesp_sno_d_concepto.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codconc','$ls_intprocon');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";						
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "VACACION": // el llamado se hace desde sigesp_sno_d_vacacionconcepto.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarvacacion('$ls_codconc','$ls_nomcon','$ls_sigcon','$ls_forcon','$ls_forpatcon');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "NOMINA": // el llamado se hace desde sigesp_sno_cat_calcularnomina.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarnomina('$ls_codconc','$ls_nomcon');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "PRIMA": // el llamado se hace desde sigesp_sno_d_primaconcepto.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarprima('$ls_codconc','$ls_nomcon','$ls_sigcon');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "PRESTAMO": // el llamado se hace desde sigesp_sno_p_prestamo.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarprestamo('$ls_codconc','$ls_nomcon');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "replisconcdes": // el llamado se hace desde sigesp_sno_r_listadoconcepto.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisconcdes('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "replisconchas": // el llamado se hace desde sigesp_sno_r_listadoconcepto.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisconchas('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "AJUSTARAPORTE": // el llamado se hace desde sigesp_sno_p_ajustaraporte.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarajustaraporte('$ls_codconc','$ls_nomcon');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "APLICARCONCEPTO": // el llamado se hace desde sigesp_sno_p_aplicarconcepto.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptaraplicarconcepto('$ls_codconc','$ls_nomcon');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "IMPORTAR": // el llamado se hace desde sigesp_sno_p_impexpdato.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarimportar('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						

					case "repapopat": // el llamado se hace desde sigesp_sno_r_aportepatronal.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepapopat('$ls_codconc','$ls_nomcon');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "represconcdes": // el llamado se hace desde sigesp_sno_r_resumenconcepto.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepresconcdes('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "represconchas": // el llamado se hace desde sigesp_sno_r_resumenconcepto.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepresconchas('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "represconcunides": // el llamado se hace desde sigesp_sno_r_resumenconceptounidad.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepresconcunides('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "represconcunihas": // el llamado se hace desde sigesp_sno_r_resumenconceptounidad.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepresconcunihas('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "repcuanomdes": // el llamado se hace desde sigesp_sno_r_cuadrenomina.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepcuanomdes('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "repcuanomhas": // el llamado se hace desde sigesp_sno_r_cuadrenomina.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepcuanomhas('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						

					case "repapopatcon": // el llamado se hace desde sigesp_snorh_r_aportepatronal.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepapopatcon('$ls_codconc','$ls_nomcon');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						

					case "repcesticdes": // el llamado se hace desde sigesp_snorh_r_cestaticket.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepcesticdes('$ls_codconc','$ls_nomcon');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						

					case "repcestichas": // el llamado se hace desde sigesp_snorh_r_cestaticket.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepcestichas('$ls_codconc','$ls_nomcon');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "repcondes": // el llamado se hace desde sigesp_snorh_r_conceptos.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepcondes('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "repconhas": // el llamado se hace desde sigesp_snorh_r_conceptos.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepconhas('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
					
					case "mintra": // el llamado se hace desde sigesp_snorh_r_conceptos.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarmintra('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;
					
					case "reppredes": // el llamado se hace desde sigesp_snorh_r_listadoprestamo.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreppredes('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "repprehas": // el llamado se hace desde sigesp_snorh_r_listadoprestamo.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepprehas('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "repdetpredes": // el llamado se hace desde sigesp_snorh_r_detalleprestamo.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepdetpredes('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;						
	
					case "repdetprehas": // el llamado se hace desde sigesp_snorh_r_detalleprestamo.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepdetprehas('$ls_codconc');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
						print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
						print "</tr>";			
						break;							
				}
				if(($as_tipo=="cajaahorro")||($as_tipo=="servasi")||($as_tipo=="conhipes")||($as_tipo=="conhipamp")||($as_tipo=="conhipcon")||($as_tipo=="conhiphip")||($as_tipo=="conhiplph")||($as_tipo=="conhipvivi")||($as_tipo=="conper")||($as_tipo=="conturi")||($as_tipo=="conpro")||($as_tipo=="conasi")||($as_tipo=="convehi")||($as_tipo=="concomi")||($as_tipo=="concfpj")||($as_tipo=="conclph")||($as_tipo=="concfpa")||($as_tipo=="concsuelant"))
				{
					print "<tr class=celdas-blancas>";
					print "<td><a href=\"javascript: aceptarconfig('$ls_codconc','$as_tipo');\">".$ls_codconc."</a></td>";
					print "<td>".$ls_nomcon."</td>";
					print "<td>".$ls_sigcon."</td>";						
					print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
					print "</tr>";				
				}
				if (($as_tipo=="archtxt")||($as_tipo=="archtxt2"))
				{
					print "<tr class=celdas-blancas>";
					print "<td><a href=\"javascript: aceptararchtxt('$ls_codconc','$as_tipo');\">".$ls_codconc."</a></td>";
					print "<td>".$ls_nomcon."</td>";
					print "<td>".$ls_sigcon."</td>";						
					print "<td><input name='txtforcon' type='text' class='sin-borde' id='txtforcon' size='30' maxlength='30' title='".$ls_forcon."' value='".$ls_forcon."'></td>";
					print "</tr>";		
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
          <input name="txtcodconc" type="text"  id="txtcodconc" size="30" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">
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
	$ls_campo=$io_fun_nomina->uf_obtenervalor_get("campo","");
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	if($ls_operacion=="BUSCAR")
	{
		$ls_codconc="%".$_POST["txtcodconc"]."%";
		$ls_nomcon="%".$_POST["txtnomcon"]."%";
		uf_print($ls_codconc, $ls_nomcon, $ls_codnomdes, $ls_codnomhas, $ls_tipo);
	}
	else
	{
		$ls_codconc="%%";
		$ls_nomcon="%%";
		uf_print($ls_codconc, $ls_nomcon, $ls_codnomdes, $ls_codnomhas, $ls_tipo);
	}
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codconc,intprocon)
{
	opener.document.form1.txtcodconc.value=codconc;
	opener.document.form1.txtcodconc.readOnly=true;
	if(intprocon=="1")
	{
		opener.document.form1.chkintprocon.checked=true;
	}
	opener.document.form1.existe.value="TRUE";		
  	opener.document.form1.operacion.value="BUSCAR";
  	opener.document.form1.action="sigesp_sno_d_concepto.php";
  	opener.document.form1.submit();
	close();
}

function aceptarvacacion(codconc,nomcon,sigcon,forcon,forpatcon)
{
	opener.document.form1.txtcodconc.value=codconc;
	opener.document.form1.txtcodconc.readOnly=true;
	opener.document.form1.txtnomcon.value=nomcon;
	opener.document.form1.txtnomcon.readOnly=true;
	opener.document.form1.txtsigcon.value=sigcon;
	opener.document.form1.txtsigcon.readOnly=true;
	opener.document.form1.txtforsalvac.value=forcon;
	opener.document.form1.txtforreivac.value=forcon;
	opener.document.form1.txtforpatsalvac.value=forpatcon;
	opener.document.form1.txtforpatreivac.value=forpatcon;
	close();
}

function aceptarnomina(codconc,nomcon)
{
	opener.document.form1.txtcodconc.value=codconc;
	opener.document.form1.txtcodconc.readOnly=true;
	opener.document.form1.txtnomcon.value=nomcon;
	opener.document.form1.txtnomcon.readOnly=true;
	close();
}

function aceptararchtxt(codconc,nomcon)
{
	opener.document.form1.txtcodconc.value=codconc;
	opener.document.form1.txtcodconc.readOnly=true;	
	close();
}

function aceptarprima(codconc,nomcon,sigcon)
{
	opener.document.form1.txtcodconc.value=codconc;
	opener.document.form1.txtcodconc.readOnly=true;
	opener.document.form1.txtnomcon.value=nomcon;
	opener.document.form1.txtnomcon.readOnly=true;
	opener.document.form1.txtsigcon.value=sigcon;
	opener.document.form1.txtsigcon.readOnly=true;
	close();
}

function aceptarprestamo(codconc,nomcon)
{
	opener.document.form1.txtcodconc.value=codconc;
	opener.document.form1.txtcodconc.readOnly=true;
	opener.document.form1.txtnomcon.value=nomcon;
	opener.document.form1.txtnomcon.readOnly=true;
	close();
}

function aceptarreplisconcdes(codconc)
{
	opener.document.form1.txtcodconcdes.value=codconc;
	opener.document.form1.txtcodconcdes.readOnly=true;
	opener.document.form1.txtcodconchas.value=codconc;
	opener.document.form1.txtcodconchas.readOnly=true;
	opener.document.form1.operacion.value="VERIFICAR_RANGO";
	opener.document.form1.action="sigesp_sno_r_listadoconcepto.php";
	opener.document.form1.submit();
	close();
}

function aceptarreplisconchas(codconc)
{
	if(opener.document.form1.txtcodconcdes.value<=codconc)
	{
		opener.document.form1.txtcodconchas.value=codconc;
		opener.document.form1.txtcodconchas.readOnly=true;
		opener.document.form1.operacion.value="VERIFICAR_RANGO";
		opener.document.form1.action="sigesp_sno_r_listadoconcepto.php";
		opener.document.form1.submit();
		close();
	}
	else
	{
		alert("Rango del concepto inválido");
	}
}

function aceptarajustaraporte(codconc,nomcon)
{
	opener.document.form1.txtcodconc.value=codconc;
	opener.document.form1.txtcodconc.readOnly=true;
	opener.document.form1.txtnomcon.value=nomcon;
	opener.document.form1.txtnomcon.readOnly=true;
	opener.document.form1.operacion.value="NUEVO";
  	opener.document.form1.totalfilas.value=0;
	opener.document.form1.action="sigesp_sno_p_ajustaraporte.php";
	opener.document.form1.submit();
	close();
}

function aceptaraplicarconcepto(codconc,nomcon)
{
	opener.document.form1.txtcodconc.value=codconc;
	opener.document.form1.txtcodconc.readOnly=true;
	opener.document.form1.txtnomcon.value=nomcon;
	opener.document.form1.txtnomcon.readOnly=true;
	opener.document.form1.txtcodcar.value="";
    opener.document.form1.txtdescar.value="";
	opener.document.form1.txtcodasicar.value="";
    opener.document.form1.txtdenasicar.value="";
	close();
}

function aceptarimportar(codconc)
{
	concepto=eval("opener.document.form1.txtcodconcep"); 
	concepto.value=concepto.value+codconc+',';
	
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

function aceptarrepapopatcon(codconc,nomcon)
{
	opener.document.form1.txtcodconc.value=codconc;
	opener.document.form1.txtcodconc.readOnly=true;
	opener.document.form1.txtnomcon.value=nomcon;
	opener.document.form1.txtnomcon.readOnly=true;
	close();
}

function aceptarrepcesticdes(codconc,nomcon)
{
	opener.document.form1.txtcodconcdes.value=codconc;
	opener.document.form1.txtcodconcdes.readOnly=true;
	opener.document.form1.txtcodconchas.value="";
	close();
}

function aceptarrepcestichas(codconc,nomcon)
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

function aceptarrepcondes(codconc)
{
	opener.document.form1.txtcodconcdes.value=codconc;
	opener.document.form1.txtcodconcdes.readOnly=true;
	opener.document.form1.txtcodconchas.value="";
	close();
}

function aceptarrepconhas(codconc)
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

function aceptarmintra(codconc)
{
	opener.document.form1.txtcodconcmin.value=codconc;
	opener.document.form1.txtcodconcmin.readOnly=true;
	close();
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

function aceptarconfig(codconc,tipos)
{
    if (tipos=="cajaahorro")
	{
		opener.document.form1.txtcodconcahoipas.value=codconc;
	}
	if (tipos=="servasi")
	{
		opener.document.form1.txtcodconcseripas.value=codconc;
	}
	if (tipos=="conhipes")
	{
		opener.document.form1.txtconhipespipas.value=codconc;
	}
	if (tipos=="conhipamp")
	{
		opener.document.form1.txtconhipampipas.value=codconc;
	}
	if (tipos=="conhipcon")
	{
		opener.document.form1.txtconhipconipas.value=codconc;
	}
	if (tipos=="conhiphip")
	{
		opener.document.form1.txtconhiphipipas.value=codconc;
	}
	if (tipos=="conhiplph")
	{
		opener.document.form1.txtconhiplphipas.value=codconc;
	}
	if (tipos=="conhipvivi")
	{
		opener.document.form1.txtconhipvivipas.value=codconc;
	}
	if (tipos=="conper")
	{
		opener.document.form1.txtconperipas.value=codconc;
	}
	if (tipos=="conturi")
	{
		opener.document.form1.txtconturipas.value=codconc;
	}
	if (tipos=="conpro")
	{
		opener.document.form1.txtconproipas.value=codconc;
	}
	if (tipos=="conasi")
	{
		opener.document.form1.txtconasiipas.value=codconc;
	}
	if (tipos=="convehi")
	{
		opener.document.form1.txtconvehipas.value=codconc;
	}
	if (tipos=="concomi")
	{
		opener.document.form1.txtconcomipas.value=codconc;
	}
	if (tipos=="concfpj")
	{
		opener.document.form1.txtcodconcfpj.value=codconc;
	}
	if (tipos=="conclph")
	{
		opener.document.form1.txtcodconclph.value=codconc;
	}
	if (tipos=="concfpa")
	{
		opener.document.form1.txtcodconcfpa.value=codconc;
	}
	if (tipos=="concsuelant")
	{
		opener.document.form1.txtcodconcsuelant.value=codconc;
	}
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
  	f.action="sigesp_sno_cat_concepto.php?tipo=<?php print $ls_tipo;?>&campo=<?php print $ls_campo;?>";
  	f.submit();
}
</script>
</html>
