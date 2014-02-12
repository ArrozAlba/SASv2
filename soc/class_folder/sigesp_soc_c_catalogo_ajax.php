<?php
	//-----------------------------------------------------------------------------------------------------------------------------------
	// Clase donde se cargan todos los catálogos del sistema SEP con la utilización del AJAX
	//-----------------------------------------------------------------------------------------------------------------------------------
    session_start();   
	require_once("class_funciones_soc.php");
	$io_funciones_soc=new class_funciones_soc();
	$ls_tipo   = $io_funciones_soc->uf_obtenervalor("tipo","");
    $ls_origen = $io_funciones_soc->uf_obtenervalor("origen","");
	$ls_codpro = $io_funciones_soc->uf_obtenervalor("codpro","");
	$ls_codestpro1=$io_funciones_soc->uf_obtenervalor("codestpro1","");
	$ls_codestpro2=$io_funciones_soc->uf_obtenervalor("codestpro2","");
	$ls_codestpro3=$io_funciones_soc->uf_obtenervalor("codestpro3","");
	$ls_codestpro4=$io_funciones_soc->uf_obtenervalor("codestpro4","");
	$ls_codestpro5=$io_funciones_soc->uf_obtenervalor("codestpro5","");
	$ls_estcla=$io_funciones_soc->uf_obtenervalor("estcla",""); 
	$ruta = '../../';
	require_once("../../shared/class_folder/sigesp_conexiones.php");
    $io_conexiones=new conexiones();
	$io_conexiones->decodificar_post();
	
	// Tipo del catalogo que se requiere pintar
	$ls_catalogo=$io_funciones_soc->uf_obtenervalor("catalogo",""); 
	switch($ls_catalogo)
	{
		case "PERSONAL":
			uf_print_personal();
		break;
		
		case "UNIDADEJECUTORA":
			uf_print_unidad_ejecutora();
		break;
		
		case "BIENES":
			uf_print_bienes();
		break;
		
		case "SERVICIOS":
			uf_print_servicios();
		break;
		
		case "PROVEEDOR":
			uf_print_proveedor($ls_tipo);
		break;
		
		case "COTIZACION_ANALISIS":
			uf_print_cotizacion_analisis();
		break;
		
		case "FUENTE-FINANCIAMIENTO":
			uf_print_fuente_financiamiento($ls_codestpro1, $ls_codestpro2, $ls_codestpro3, $ls_codestpro4, $ls_codestpro5, $ls_estcla);
		break;
		
		case "MODALIDAD-CLAUSULAS":
			uf_print_modalidad_clausulas();
		break;
		
		case "MONEDA":
			uf_print_moneda();
		break;
		
		case "COTIZACION_SOLICITUD":
			uf_print_solicitudes_cotizacion($ls_origen,$ls_codpro);
		break;
		
        case "COTIZACION_REGISTRO":
			uf_print_cotizaciones($ls_origen,$ls_tipo);
		break;
		
		case "PRESUPUESTARIA-SOLICITUD":
			uf_print_sep($ls_tipo);
		break;
		
		case "ANALISIS":
			uf_print_analisis();
		break;
		
		case "ORDEN-COMPRA":
		   uf_print_orden_compra();
		break;
		
		case "SOLICITUD-PRESUPUESTARIA":
			uf_print_solicitud_presupuestaria();
		break;
		
		case "CUENTAS-SPG":
		    uf_print_cuentas_spg();
		break;

		case "CUENTAS-CARGOS":
			uf_print_cuentas_cargos();
		break;

		case "CARGOS":
			uf_print_cargos();
		break;
	}
	
       	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_personal()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_conceptos
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que obtiene e imprime el resultado de la busqueda de los conceptos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_soc;
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");
		
		$io_include   = new sigesp_include();
		$io_conexion  = $io_include->uf_conectar();
		$io_sql		  = new class_sql($io_conexion);	
		$io_mensajes  = new class_mensajes();		
		$io_funciones = new class_funciones();		
        
		$ls_codemp	   = $_SESSION['la_empresa']['codemp'];
		$ls_cedper 	   = $_POST['cedper'];
		$ls_nomper 	   = $_POST['nomper'];
		$ls_apeper 	   = $_POST['apeper'];
		$ls_orden      = $_POST['orden'];
		$ls_campoorden = $_POST['campoorden'];
		
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td style='cursor:pointer' title='Ordenar por Cédula'   align='center' onClick=ue_orden('cedper')>C&eacute;dula</td>";
		print "<td style='cursor:pointer' title='Ordenar por Nombre'   align='center' onClick=ue_orden('nomper')>Nombre</td>";
		print "<td style='cursor:pointer' title='Ordenar por Apellido' align='center' onClick=ue_orden('apeper')>Apellido</td>";
		print "</tr>";
		$ls_sql = "SELECT DISTINCT max(CASE sno_nomina.racnom WHEN 1 THEN
                                sno_personalnomina.codcar ELSE sno_cargo.codcar END) AS codcar,
				          (SELECT nomper FROM sno_personal
				            WHERE sno_personal.codper=sno_personalnomina.codper) as nomper,
				          (SELECT apeper FROM sno_personal
				            WHERE sno_personal.codper=sno_personalnomina.codper) as apeper,
				          (SELECT cedper FROM sno_personal
				            WHERE sno_personal.codper=sno_personalnomina.codper) as cedper
				     FROM sno_personalnomina, sno_nomina, sno_cargo,sno_asignacioncargo,sno_personal
				    WHERE sno_personal.cedper LIKE '%".$ls_cedper."%'
				      AND sno_personal.nomper LIKE '%".$ls_nomper."%'
				      AND sno_personal.apeper LIKE '%".$ls_apeper."%'
				      AND sno_nomina.espnom=0
				      AND sno_personalnomina.codemp = sno_nomina.codemp
				      AND sno_personalnomina.codnom = sno_nomina.codnom
				      AND sno_personalnomina.codper = sno_personal.codper
				      AND sno_personalnomina.codemp = sno_cargo.codemp
				      AND sno_personalnomina.codnom = sno_cargo.codnom
				      AND sno_personalnomina.codcar = sno_cargo.codcar
				      AND sno_personalnomina.codemp = sno_asignacioncargo.codemp
				      AND sno_personalnomina.codnom = sno_asignacioncargo.codnom
				      AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar
				    GROUP BY sno_personalnomina.codper,sno_nomina.racnom,sno_asignacioncargo.denasicar,codclavia
			 	    ORDER BY ".$ls_campoorden." ".$ls_orden." ";
		$rs_data=$io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
        	 $io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		   }
		else
		   {
		     while(!$rs_data->EOF)
			      {
				    $ls_cedper    = trim($rs_data->fields["cedper"]);
				    $ls_nomper    = $rs_data->fields["nomper"];
				    $ls_apeper    = $rs_data->fields["apeper"];
				    $ls_codcarper = trim($rs_data->fields["codcar"]);
					echo "<tr class=celdas-blancas>";
					echo "<td align='center'><a href=\"javascript: ue_aceptar('".$ls_cedper."','".$ls_nomper."','".$ls_apeper."','".$ls_codcarper."');\">".$ls_cedper."</a></td>";
					echo "<td align='left'>".$ls_nomper."</td>";
					echo "<td align='left'>".$ls_apeper."</td>";
					echo "</tr>";
					$rs_data->MoveNext();
			      }
			$io_sql->free_result($rs_data);
		}
		print "</table>";
		unset($io_include,$io_conexion,$io_sql,$io_mensajes,$io_funciones,$ls_codemp);
	}// end function uf_print_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_unidad_ejecutora()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de la unidad ejecutora (Unidad administrativa)
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan / Ing. Nestor Falcon 
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 05/05/2007
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones  = new class_funciones();
						
		$ls_codemp     = $_SESSION["la_empresa"]["codemp"];
		$ls_codunieje  = $_POST["codunieje"];
		$ls_denunieje  = $_POST["denunieje"];
		$ls_orden      = $_POST['orden'];
		$ls_campoorden = $_POST['campoorden'];
		$ls_tipo       = $_POST['tipo'];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60  style='cursor:pointer' title='Ordenar por Código'       align='center' onClick=ue_orden('coduniadm')>C&oacute;digo</td>";
		if (empty($ls_tipo))
		   {
		     print "<td width=400 style='cursor:pointer' title='Ordenar por Denominación' align='center' onClick=ue_orden('denuniadm')>Denominaci&oacute;n</td>";
			 print "<td width=40  style='cursor:pointer' title='Seleccionar Estructura Presupuestaria'>Detalle</td>";   
		   }
		else
		   {
		     print "<td width=440 style='cursor:pointer' title='Ordenar por Denominación' align='center' onClick=ue_orden('denuniadm')>Denominaci&oacute;n</td>";
		   }
		print "</tr>";
		
		$ls_logusr = $_SESSION["la_logusr"];
		$ls_gestor = $_SESSION["ls_gestor"];
		$ls_sql_seguridad = "";
		if (strtoupper($ls_gestor) == "MYSQLT")
		{
		 $ls_sql_seguridad = " AND CONCAT('".$ls_codemp."','SOC','".$ls_logusr."',spg_unidadadministrativa.coduniadm) IN (SELECT CONCAT(codemp,codsis,codusu,codintper) 
		                       FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SOC')";
		}
		else
		{
		 $ls_sql_seguridad = " AND '".$ls_codemp."'||'SOC'||'".$ls_logusr."'||spg_unidadadministrativa.coduniadm IN (SELECT codemp||codsis||codusu||codintper
		                       FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SOC' UNION SELECT sss_permisos_internos_grupo.codemp||'SOC'||codusu||codintper
		                       FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu = '".$ls_logusr."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
		}
		
		$ls_sql="SELECT spg_unidadadministrativa.coduniadm, 
		                count(spg_dt_unidadadministrativa.codestpro1)as items,
                        max(spg_unidadadministrativa.denuniadm) as denuniadm,
						max(spg_dt_unidadadministrativa.codestpro1) as codestpro1, 
						max(spg_dt_unidadadministrativa.codestpro2) as codestpro2,  
						max(spg_dt_unidadadministrativa.codestpro3) as codestpro3,  
						max(spg_dt_unidadadministrativa.codestpro4) as codestpro4,  
						max(spg_dt_unidadadministrativa.codestpro5) as codestpro5, 
						max(spg_dt_unidadadministrativa.estcla) as estcla".
				"  FROM spg_unidadadministrativa, spg_dt_unidadadministrativa, spg_ep5 ".
				" WHERE spg_unidadadministrativa.codemp='".$ls_codemp."' ".
				"   AND spg_unidadadministrativa.coduniadm <>'----------' ".
				"   AND spg_unidadadministrativa.coduniadm like '%".$ls_codunieje."%' ".
				"   AND spg_unidadadministrativa.denuniadm like '%".$ls_denunieje."%' ".$ls_sql_seguridad.
				"   AND spg_unidadadministrativa.codemp=spg_dt_unidadadministrativa.codemp ".
				"   AND spg_unidadadministrativa.coduniadm=spg_dt_unidadadministrativa.coduniadm ".
				"   AND spg_dt_unidadadministrativa.codemp=spg_ep5.codemp ".
				"   AND spg_dt_unidadadministrativa.estcla=spg_ep5.estcla ".
				"   AND spg_dt_unidadadministrativa.codestpro1=spg_ep5.codestpro1 ".
				"   AND spg_dt_unidadadministrativa.codestpro2=spg_ep5.codestpro2 ".
				"   AND spg_dt_unidadadministrativa.codestpro3=spg_ep5.codestpro3 ".
				"   AND spg_dt_unidadadministrativa.codestpro4=spg_ep5.codestpro4 ".
				"   AND spg_dt_unidadadministrativa.codestpro5=spg_ep5.codestpro5 ".
				" GROUP BY spg_unidadadministrativa.codemp, spg_unidadadministrativa.coduniadm".
				" ORDER BY ".$ls_campoorden." ".$ls_orden;
		$rs_data=$io_sql->select($ls_sql);//echo $ls_sql;
		if ($rs_data===false)
		   {
		     $io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		   }
		else
		   {
			 $li_fila = 0;
			 while($row=$io_sql->fetch_row($rs_data))
			      {
				    $li_fila++;  
					$li_numitedet  = $row["items"];//Numero de Detalles asociados a la Unidad Ejecutora.
					$ls_codunieje  = str_pad(trim($row["coduniadm"]),10,0,0);
				    $ls_denunieje  = $row["denuniadm"];
				    $ls_estcla     = $row["estcla"];
					$ls_codestpro1 = str_pad(trim($row["codestpro1"]),25,0,0);
					$ls_codestpro2 = str_pad(trim($row["codestpro2"]),25,0,0);
				    $ls_codestpro3 = str_pad(trim($row["codestpro3"]),25,0,0);
				    $ls_codestpro4 = str_pad(trim($row["codestpro4"]),25,0,0);
				    $ls_codestpro5 = str_pad(trim($row["codestpro5"]),25,0,0);
					echo "<tr class=celdas-blancas>";
					switch ($ls_tipo)
					{
						case "":
							if ($li_numitedet==1)
							   {
							     echo "<td style=text-align:center width=60><a href=\"javascript: aceptar('$ls_codunieje','$ls_denunieje','$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_estcla');\">".$ls_codunieje."</a></td>";
							   }
							elseif($li_numitedet>1)
							   {
							     echo "<td style=text-align:center width=60>".$ls_codunieje."</td>";
							   }
							echo "<td style=text-align:left width=400>".$ls_denunieje."</td>";
							if ($li_numitedet>1)
							   {
							     echo "<td style=text-align:center width=40><a href=javascript:uf_catalogo_estructuras('$ls_codunieje');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></td></a>";
							   }
							elseif($li_numitedet<=1)
							   {
							     echo "<td style=text-align:center width=40></td>";
							   }
							break;
						
						case "ESTANDAR":
						    echo "<td style=text-align:center width=60><a href=\"javascript: aceptar_unidad('$ls_codunieje','$ls_denunieje');\">".$ls_codunieje."</a></td>";
                            echo "<td style=text-align:left width=440>".$ls_denunieje."</td>";
						break;
						
						case "REPDES":
							print "<td style=text-align:center width=60><a href=\"javascript:aceptar_reportedesde('$ls_codunieje');\">".$ls_codunieje."</a></td>";
							print "<td style=text-align:left width=440>".$ls_denunieje."</td>";
						break;
						
						case "REPHAS":
							print "<td style=text-align:center width=60><a href=\"javascript:aceptar_reportehasta('$ls_codunieje');\">".$ls_codunieje."</a></td>";
							print "<td style=text-align:left width=440>".$ls_denunieje."</td>";
						break;
					}
			        print "</tr>";
			}
			$io_sql->free_result($rs_data);
		}
		print "</table>";
		unset($io_include,$io_conexion,$io_sql,$io_mensajes,$io_funciones,$ls_codemp);
	}// end function uf_print_unidadejecutora
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_bienes()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_bienes
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que obtiene e imprime el resultado de la busqueda de los bienes
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan / Ing. Nestor Falcon 
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 23/07/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_soc;
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");
		
		$io_include	  = new sigesp_include();
		$io_conexion  = $io_include->uf_conectar();
		$io_sql		  = new class_sql($io_conexion);	
		$io_mensajes  = new class_mensajes();		
		$io_funciones = new class_funciones();		

        $ls_codemp	  = $_SESSION['la_empresa']['codemp'];
		$li_parsindis = $_SESSION["la_empresa"]["estparsindis"];
		$ls_tipo	  = $_POST['tipo'];
		$ls_codart	  = "%".trim($_POST['codart'])."%";
		$ls_denart	  = "%".$_POST['denart']."%";
		$ls_codtipart = "%".$_POST['codtipart']."%";
		$ls_codunieje = "";
		if ($ls_tipo=='SC')
		   {
		     $ls_codunieje = $_POST['codunieje'];  
		   }
		$ls_sqlaux = "";
		if ($ls_tipo=='SC' || $ls_tipo=='OC')
		   {
		     $ls_tipbiesol  = $_POST['tipbiesol'];
			 /*if ($ls_tipbiesol=='M')
			    {
				  $ls_sqlaux = " AND siv_articulo.estact=0";
				}
		     elseif($ls_tipbiesol=='A')
			    {
				  $ls_sqlaux = " AND siv_articulo.estact=1";
				}*/
		   }

		$ls_codestpro1 = trim($_POST['codestpro1']);
		$ls_codestpro2 = trim($_POST['codestpro2']);
		$ls_codestpro3 = trim($_POST['codestpro3']);
		$ls_codestpro4 = trim($_POST['codestpro4']);
		$ls_codestpro5 = trim($_POST['codestpro5']);
		
		$ls_estcla     = $_POST['hidestcla'];		
		$ls_orden	   = $_POST['orden'];
		$ls_campoorden = $_POST['campoorden'];
		$ls_straux 	   = "";
		
		if ((!empty($ls_codunieje) && $ls_codunieje!='----------') || $ls_tipo=='OC')
		   {
		     $ls_straux = ",(SELECT COUNT(spg_cuentas.spg_cuenta) 
			                   FROM spg_cuentas
				              WHERE spg_cuentas.codestpro1 = '".$ls_codestpro1."'
				                AND spg_cuentas.codestpro2 = '".$ls_codestpro2."'
				                AND spg_cuentas.codestpro3 = '".$ls_codestpro3."'
				                AND spg_cuentas.codestpro4 = '".$ls_codestpro4."'
		 	  	                AND spg_cuentas.codestpro5 = '".$ls_codestpro5."'
				                AND spg_cuentas.estcla = '".$ls_estcla."'
						        AND siv_articulo.codemp = spg_cuentas.codemp
				                AND siv_articulo.spg_cuenta = spg_cuentas.spg_cuenta AND codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' UNION SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)) AS existecuenta,
						    (SELECT (spg_cuentas.asignado-(spg_cuentas.comprometido+spg_cuentas.precomprometido)+spg_cuentas.aumento-spg_cuentas.disminucion)
						  	   FROM spg_cuentas
							  WHERE spg_cuentas.codestpro1 = '".$ls_codestpro1."'
								AND spg_cuentas.codestpro2 = '".$ls_codestpro2."'
								AND spg_cuentas.codestpro3 = '".$ls_codestpro3."'
								AND spg_cuentas.codestpro4 = '".$ls_codestpro4."'
								AND spg_cuentas.codestpro5 = '".$ls_codestpro5."'
								AND spg_cuentas.estcla='".$ls_estcla."'
								AND siv_articulo.codemp = spg_cuentas.codemp
								AND spg_cuentas.spg_cuenta = siv_articulo.spg_cuenta) AS disponibilidad,";
		   }

		$ls_sql  = "SELECT soc_gastos FROM sigesp_empresa WHERE codemp = '".$ls_codemp."'";
		$rs_data = $io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
		     $lb_valido = false;
			 $io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message));
		   }
		else
		   {
			 $ls_spgctas = $rs_data->fields["soc_gastos"];
			 if (!empty($ls_spgctas))
			    {
				  $la_spgctas = split(",",$ls_spgctas);
				  if (!empty($la_spgctas))
				     {
					   $li_totrows = count($la_spgctas);
					   for ($li_i=0;$li_i<$li_totrows;$li_i++)
					       {
						     if ($li_i==0)
							    {
								  $ls_sqlaux = $ls_sqlaux." AND (siv_articulo.spg_cuenta like '".$la_spgctas[$li_i]."%'";
								}
							 else
							    {
								  $ls_sqlaux = $ls_sqlaux." OR siv_articulo.spg_cuenta like '".$la_spgctas[$li_i]."%'";
								}
							 if ($li_i==$li_totrows-1)
							    {
							      $ls_sqlaux = $ls_sqlaux.") ORDER BY $ls_campoorden $ls_orden";
								}						   
						   }
					   $ls_sql = "SELECT TRIM(siv_articulo.codart) as codart,siv_articulo.denart,siv_articulo.ultcosart,
							             siv_articulo.codunimed,TRIM(siv_articulo.spg_cuenta) AS spg_cuenta,
									     siv_unidadmedida.denunimed, siv_unidadmedida.unidad $ls_straux
							 		     (SELECT COUNT(siv_cargosarticulo.codart)
							   	   		    FROM sigesp_cargos, siv_cargosarticulo
							  		  	   WHERE siv_cargosarticulo.codemp = siv_articulo.codemp
								  			 AND siv_cargosarticulo.codart = siv_articulo.codart
							    			 AND sigesp_cargos.codemp = siv_cargosarticulo.codemp
							    			 AND sigesp_cargos.codcar = siv_cargosarticulo.codcar ) AS totalcargos
							        FROM siv_articulo, siv_unidadmedida
							       WHERE siv_articulo.codemp='".$ls_codemp."'
							         --AND siv_articulo.codart like '".$ls_codart."'
							         --AND siv_articulo.denart like '".$ls_denart."'
							         --AND siv_articulo.codtipart like '".$ls_codtipart."'
							         AND siv_articulo.codunimed = siv_unidadmedida.codunimed $ls_sqlaux";
	 				   $rs_data=$io_sql->select($ls_sql);
					   if ($rs_data===false)
					      {
						    $io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
					      }
					   else
				 	      {
						    echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
						    echo "<tr class=titulo-celda>";
						    echo "<td style='cursor:pointer' title='Ordenar por Codigo'       align='center' onClick=ue_orden('siv_articulo.codart')>C&oacute;digo</td>";
						    echo "<td style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('siv_articulo.denart')>Denominaci&oacute;n</td>";
						    echo "<td style='cursor:pointer' title='Ordenar por Unidad'       align='center' onClick=ue_orden('siv_unidadmedida.denunimed')>Unidad</td>";
						    echo "<td style='cursor:pointer' title='Ordenar por Cuenta'       align='center' onClick=ue_orden('siv_articulo.spg_cuenta')>Cuenta</td>";
						    echo "<td></td>";
						    echo "</tr>";
						    while(!$rs_data->EOF)
						         {
							       $ls_codart		= $rs_data->fields["codart"];
							       $ls_denart		= $rs_data->fields["denart"];
							       $li_ultcosart	= number_format($rs_data->fields["ultcosart"],2,",",".");
							       $ls_codunimed	= $rs_data->fields["codunimed"];
							       $ls_denunimed    = $rs_data->fields["denunimed"];
							       $li_unidad		= $rs_data->fields["unidad"];
								   $li_totalcargos	= $rs_data->fields["totalcargos"];
								   $ls_spg_cuenta   = $rs_data->fields["spg_cuenta"];
								   $li_existecuenta = $ld_mondiscta = 0;
								   if ((!empty($ls_codunieje) && $ls_codunieje!='----------') || $ls_tipo=='OC')
									  {				   
										$li_existecuenta = $rs_data->fields["existecuenta"];
										$ld_mondiscta    = number_format($rs_data->fields["disponibilidad"],2,".","");//Monto disponible de la Cuenta.
									  }
								   if ($li_existecuenta==0)
									  {
										$ls_estilo = "celdas-blancas";
									  }
								   else
									  {
										$ls_estilo = "celdas-azules";
									  }
								   if ($ls_tipo!='SC')
									  {
									    echo "<tr class=".$ls_estilo.">";
									    echo "<td align='center'>".$ls_codart."</td>";
									    echo "<td align='left' title='".$ls_denart."'>".$ls_denart."</td>";
									    echo "<td align='left'>".$ls_denunimed."</td>";
									    echo "<td align='center'>".$ls_spg_cuenta."</td>";
									    echo "<td style='cursor:pointer'>";  
									  }
								   switch ($ls_tipo){ 
									 case "SC": 
										if ($li_existecuenta==1)
										   {
											 echo "<tr class=".$ls_estilo.">";
											 echo "<td align='center'>".$ls_codart."</td>";
											 echo "<td align='left' title='".$ls_denart."'>".$ls_denart."</td>";
											 echo "<td align='left'>".$ls_denunimed."</td>";
											 echo "<td align='center'>".$ls_spg_cuenta."</td>";
											 echo "<td style='cursor:pointer'>";
											 echo "<a href=\"javascript: ue_aceptar_bienes_solicitud_cotizacion('".$ls_codart."','".$ls_denart."','".$ld_mondiscta."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";												
										   }
									 break;
									 case "OC":
										echo "<a href=\"javascript: ue_aceptar_bienes_orden_compra('".$ls_codart."','".$ls_denart."','".$li_unidad."','".$ls_spg_cuenta."',".
											  "'".$li_ultcosart."','".$li_totalcargos."','".$li_existecuenta."','".$ld_mondiscta."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
									 break;	
									 case "REPDES":
										echo "<a href=\"javascript: ue_aceptar_reportedesde('".$ls_codart."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
									 break;
									 case "REPHAS":
										echo "<a href=\"javascript: ue_aceptar_reportehasta('".$ls_codart."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
									 break;
								   }				
							       echo "</tr>";		
							       $rs_data->MoveNext();								
						         }
						    $io_sql->free_result($rs_data);
						    echo "</table>";
					      }
					 }
				  unset($la_spgctas);
				}
		   }
		unset($io_include,$io_conexion,$io_sql,$io_mensajes,$io_funciones,$ls_codemp,$rs_data);
	}// end function uf_print_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_servicios()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_servicios
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que obtiene e imprime el resultado de la busqueda de los servicios
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan / Ing. Nestor Falcon 
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 23/07/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");
		
		$io_include   = new sigesp_include();
		$io_conexion  = $io_include->uf_conectar();
		$io_sql       = new class_sql($io_conexion);	
		$io_mensajes  = new class_mensajes();		
		$io_funciones = new class_funciones();		
        
		$ls_codemp    = $_SESSION['la_empresa']['codemp'];
		$ls_tipo	  = $_POST['tipo'];
		$ls_codser    = "%".trim($_POST['codser'])."%";
		$ls_denser    = "%".$_POST['denser']."%";
		$ls_codunieje = "";
		if ($ls_tipo=='SC')
		   {
		     $ls_codunieje = $_POST['codunieje'];  
		   }
		$ls_codestpro1 = trim($_POST['codestpro1']);
		$ls_codestpro2 = trim($_POST['codestpro2']);
		$ls_codestpro3 = trim($_POST['codestpro3']);
		$ls_codestpro4 = trim($_POST['codestpro4']);
		$ls_codestpro5 = trim($_POST['codestpro5']);
		$ls_estcla     = $_POST['hidestcla'];
		$ls_orden      = $_POST['orden'];
		$ls_campoorden = $_POST['campoorden'];
		$ls_straux	   = "";
			
		if ((!empty($ls_codunieje) && $ls_codunieje!='----------') || $ls_tipo=='OC')
		   {
		     $ls_straux = ",(SELECT COUNT(spg_cuenta) FROM spg_cuentas ".
				          "   WHERE spg_cuentas.codestpro1 = '".$ls_codestpro1."' ".
				          "     AND spg_cuentas.codestpro2 = '".$ls_codestpro2."' ".
				          "	    AND spg_cuentas.codestpro3 = '".$ls_codestpro3."' ".
			   	          "	    AND spg_cuentas.codestpro4 = '".$ls_codestpro4."' ".
				          "		AND spg_cuentas.codestpro5 = '".$ls_codestpro5."' ".
						  "   	AND spg_cuentas.estcla = '".$ls_estcla."' ".
				          "		AND soc_servicios.codemp = spg_cuentas.codemp ".
				          "		AND soc_servicios.spg_cuenta = spg_cuentas.spg_cuenta) AS existecuenta, ".
						  " (SELECT (asignado-(comprometido+precomprometido)+aumento-disminucion) ".
						  "    FROM spg_cuentas ".
						  "	  WHERE spg_cuentas.codestpro1 = '".$ls_codestpro1."' ".
						  "		AND spg_cuentas.codestpro2 = '".$ls_codestpro2."'".
						  "		AND spg_cuentas.codestpro3 = '".$ls_codestpro3."' ".
						  "		AND spg_cuentas.codestpro4 = '".$ls_codestpro4."' ".
					 	  "		AND spg_cuentas.codestpro5 = '".$ls_codestpro5."' ".
						  "     AND spg_cuentas.estcla='".$ls_estcla."'".
						  "		AND spg_cuentas.spg_cuenta = soc_servicios.spg_cuenta) AS disponibilidad";
		   }

			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td style='cursor:pointer' title='Ordenar por C&oacute;digo'       align='center' onClick=ue_orden('codser')>C&oacute;digo</td>";
			print "<td style='cursor:pointer' title='Ordenar por Denominaci&oacute;n' align='center' onClick=ue_orden('denser')>Denominacion</td>";
			print "<td style='cursor:pointer' title='Ordenar por Precio'              align='center' onClick=ue_orden('preser')>Precio Unitario</td>";
			print "<td style='cursor:pointer' title='Ordenar por Cuenta'              align='center' onClick=ue_orden('spg_cuenta')>Cuenta</td>";
			print "<td></td>";
			print "</tr>";
			$ls_sql="SELECT codser, denser, preser,  TRIM(spg_cuenta) as spg_cuenta $ls_straux".
					"  FROM soc_servicios ".
					" WHERE codemp='".$ls_codemp."' ".
					"   AND codser like '".$ls_codser."' ".
					"   AND denser like '".$ls_denser."' ".
					" ORDER BY ".$ls_campoorden." ".$ls_orden." ";
			$rs_data=$io_sql->select($ls_sql); ///echo $ls_sql.'<br>';
			if($rs_data===false)
			{
				$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
			}
			else
			{
				while(!$rs_data->EOF)
				{
					$ls_codser	   = trim($rs_data->fields["codser"]);
					$ls_denser	   = $rs_data->fields["denser"];
					$li_preser	   = number_format($rs_data->fields["preser"],2,",",".");
					$ls_spg_cuenta = trim($rs_data->fields["spg_cuenta"]);
					$li_existecuenta = $ld_mondiscta = 0;
					if ((!empty($ls_codunieje) && $ls_codunieje!='----------') || $ls_tipo=='OC')
					   {				   
						 $li_existecuenta = $rs_data->fields["existecuenta"];
						 $ld_mondiscta    = number_format($rs_data->fields["disponibilidad"],2,".","");//Monto disponible de la Cuenta.
					   }
					if($li_existecuenta==0)
					{
						$ls_estilo = "celdas-blancas";
					}
					else
					{
						$ls_estilo = "celdas-azules";
					}
					if ($ls_tipo!='SC')
					   {
						 print "<tr class=".$ls_estilo.">";
						 print "<td align='center'>".$ls_codser."</td>";
						 print "<td align='left' title='".$ls_denser."'>".$ls_denser."</td>";
						 print "<td align='right'>".$li_preser."</td>";
						 print "<td align='center'>".$ls_spg_cuenta."</td>";
						 print "<td style='cursor:pointer'>";
					   }
					
					if($ls_tipo=='SC')
					{
						if ($li_existecuenta==1)
						   {
							 print "<tr class=".$ls_estilo.">";
							 print "<td align='center'>".$ls_codser."</td>";
							 print "<td align='left' title='".$ls_denser."'>".$ls_denser."</td>";
							 print "<td align='right'>".$li_preser."</td>";
							 print "<td align='center'>".$ls_spg_cuenta."</td>";
							 print "<td style='cursor:pointer'>";
							 print "<a href=\"javascript: ue_aceptar('".$ls_codser."','".$ls_denser."','".$ld_mondiscta."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";					   
						   }
					}
					if($ls_tipo=='OC')
					{
						print "<a href=\"javascript: ue_aceptar_servicio_orden_compra('".$ls_codser."','".$ls_denser."','".$li_preser."','".$ls_spg_cuenta."','".$li_existecuenta."','".$ld_mondiscta."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
					}
					if($ls_tipo=='REPDES')
					{
						print "<a href=\"javascript: ue_aceptar_reportedesde('".$ls_codser."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
					}
					if($ls_tipo=='REPHAS')
					{
						print "<a href=\"javascript: ue_aceptar_reportehasta('".$ls_codser."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
					}
					print "</tr>";
					$rs_data->MoveNext();			
				}  // fin del while
				$io_sql->free_result($rs_data);
		     } // fin del else
		print "</table>";
		unset($io_include,$io_conexion,$io_sql,$io_mensajes,$io_funciones,$ls_codemp);
	}// end function uf_print_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_proveedor($ls_tipo)
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de proveedores
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan / Ing. Nestor Falcon 
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
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
		$ls_codpro="%".$_POST['codpro']."%";
		$ls_nompro="%".$_POST['nompro']."%";
		$ls_dirpro="%".$_POST['dirpro']."%";
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_rifpro="%".$_POST['rifpro']."%";
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=80  style='cursor:pointer' title='Ordenar por Codigo' align='center' onClick=ue_orden('cod_pro')>Codigo</td>";
		print "<td width=340 style='cursor:pointer' title='Ordenar por Nombre' align='center' onClick=ue_orden('nompro')>Nombre</td>";
		print "<td width=80  style='cursor:pointer' title='Ordenar por Rif'    align='center' onClick=ue_orden('rifpro')>RIF</td>";
		print "<td></td>";
		print "</tr>";
        $ls_sql="SELECT cod_pro,nompro,sc_cuenta,rifpro,dirpro,telpro,tipconpro".
				"  FROM rpc_proveedor  ".
                " WHERE codemp = '".$ls_codemp."' ".
				"   AND cod_pro <> '----------' ".
				"   AND estprov = 0 ".
				"   AND cod_pro like '".$ls_codpro."'  ".
				"   AND nompro  like '".$ls_nompro."'  ".
				"   AND dirpro  like '".$ls_dirpro."'  ". 
				"   AND rifpro  like '".$ls_rifpro."'  ". 
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while(!$rs_data->EOF)
			{
				$ls_codpro    = trim($rs_data->fields["cod_pro"]);
				$ls_nompro    = $rs_data->fields["nompro"];
				$ls_sccuenta  = $rs_data->fields["sc_cuenta"];
				$ls_rifpro    = trim($rs_data->fields["rifpro"]);
				$ls_dirpro    = $rs_data->fields["dirpro"];
				$ls_telpro    = $rs_data->fields["telpro"];
				$ls_tipconpro = $rs_data->fields["tipconpro"];
				echo "<tr class=celdas-blancas>";
				echo "<td style=text-align:center width=80>".$ls_codpro."</td>";
				echo "<td style=text-align:left   width=340 title='".$ls_nompro."'>".$ls_nompro."</td>";
				echo "<td style=text-align:left   width=80>".$ls_rifpro."</td>";
				switch ($ls_tipo)
				{
					case "SC":
				        echo "<td><a href=\"javascript: ue_aceptar_proveedor_solicitud_cotizacion('".$ls_codpro."','".$ls_nompro."','".$ls_dirpro."','".$ls_telpro."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar Proveedor' width='15' height='15' border='0'></a></td>";
					break;
					case "RC":
				        echo "<td><a href=\"javascript: ue_aceptar_proveedor_registro_cotizacion('".$ls_codpro."','".$ls_nompro."','".$ls_tipconpro."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar Proveedor' width='15' height='15' border='0'></a></td>";
					break;
					case "":
				        echo "<td><a href=\"javascript: ue_aceptar('".$ls_codpro."','".$ls_nompro."','".$ls_tipconpro."','".$ls_rifpro."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar Proveedor' width='15' height='15' border='0'></a></td>";
					break;
				  	case "REPDES":
				        echo "<td><a href=\"javascript: aceptar_reportedesde('".$ls_codpro."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar Proveedor' width='15' height='15' border='0'></a></td>";
					break;
					case "REPHAS":
				        echo "<td><a href=\"javascript: aceptar_reportehasta('".$ls_codpro."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar Proveedor' width='15' height='15' border='0'></a></td>";
					break;
				}
			    echo "</tr>";
				$rs_data->MoveNext();
			}
			$io_sql->free_result($rs_data);
		}
		echo "</table>";
		unset($io_include,$io_conexion,$io_sql,$io_mensajes,$io_funciones,$ls_codemp);
	}// end function uf_print_proveedor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cotizacion_analisis()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cotizacion_analisis();
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que obtiene e imprime el las cotizaciones registradas asociadas a su solicitud para el análisis de cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 12/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_soc;		
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_numsolcot="%".$_POST['numsol']."%";
		$ls_numcot="%".$_POST['numcot']."%";
		$ls_codpro="%".$_POST['codpro']."%";
		$ls_fecini=$io_funciones->uf_convertirdatetobd($_POST['fecini']);
		$ls_fecfin=$io_funciones->uf_convertirdatetobd($_POST['fecfin']);
		if ($ls_fecini!="")
			$ls_cadena1=" AND s.feccot>='$ls_fecini'";
		else
			$ls_cadena1="";
		if ($ls_fecfin!="")
			$ls_cadena2=" AND s.feccot<='$ls_fecfin'";
		else
			$ls_cadena2="";	
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_sql="SELECT distinct s.numcot, s.numsolcot, p.nompro, s.feccot,s.poriva,s.montotcot,s.cod_pro, c. tipsolcot
					FROM soc_cotizacion s, rpc_proveedor p, soc_sol_cotizacion c
					WHERE s.codemp='$ls_codemp' 
					AND c.estcot='R'
					AND s.numcot like '$ls_numcot'
					AND s.numsolcot like '$ls_numsolcot'
					AND p.cod_pro like '$ls_codpro'
					AND s.codemp=p.codemp
					AND s.cod_pro=p.cod_pro
					AND c.codemp=s.codemp
					AND s.numsolcot=c.numsolcot		
					$ls_cadena1 $ls_cadena2
					ORDER BY ".$ls_campoorden." ".$ls_orden."";		
			
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
				print "<table width=630 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
				print "<tr class=titulo-celda>";
				print "<td style='cursor:pointer' title='Ordenar por N° de Solicitud'       align='center' onClick=ue_orden('s.numsolcot')>No de Solicitud</td>";
				print "<td style='cursor:pointer' title='Ordenar por N° Cotización'         align='center' onClick=ue_orden('s.numcot')>No de Cotizacion</td>";
				print "<td style='cursor:pointer' title='Ordenar por Proveedor'             align='center' onClick=ue_orden('p.nompro')>Proveedor</td>";
				print "<td style='cursor:pointer' title='Ordenar por Fecha'                   align='center' onClick=ue_orden('s.feccot')   width=70>Fecha</td>";
				print "<td style='cursor:pointer' title='Ordenar por I.V.A.'                   align='center' onClick=ue_orden('s.poriva')>I.V.A.</td>";
				print "<td style='cursor:pointer' title='Ordenar por Monto Total'          align='center' onClick=ue_orden('s.montotcot') width=100>Monto Total</td>";
				print "<td></td>";
				print "</tr>";
				while($row=$io_sql->fetch_row($rs_data))
				{
					$ls_tipsolcot=$row["tipsolcot"];
					$ls_numcot=$row["numcot"];
					$ls_numsolcot=$row["numsolcot"];
					$li_nompro=$row["nompro"];
					$ls_codpro=$row["cod_pro"];
					$ls_feccot=$io_funciones->uf_formatovalidofecha($row["feccot"]);
					$ls_feccot=$io_funciones->uf_convertirfecmostrar($ls_feccot);
					$ls_poriva=number_format($row["poriva"], 2, ',', '.');				
					$li_montotcot=number_format($row["montotcot"], 2, ',', '.');				
					print "<tr class=celdas-blancas>";
					print "<td align='center'>".$ls_numsolcot."</td>";
					print "<td align='left'>".$ls_numcot."</td>";
					print "<td align='left'>".$li_nompro."</td>";
					print "<td align='center'>".$ls_feccot."</td>";
					print "<td align='center'>".$ls_poriva."</td>";
					print "<td align='right'>".$li_montotcot."</td>";
					print "<td style='cursor:pointer'>";
					print "<a href=\"javascript: ue_aceptar('".$ls_numsolcot."','".$ls_numcot."','".$li_nompro."','".$ls_codpro."','".$ls_feccot."',".
						  "'".$ls_poriva."','".$li_montotcot."','".$ls_tipsolcot."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
					print "</tr>";			
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
	}// end function uf_print_cotizacion_analisis
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_fuente_financiamiento($ls_codestpro1, $ls_codestpro2, $ls_codestpro3, $ls_codestpro4, $ls_codestpro5, $ls_estcla)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_fuente_financiamiento
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de fuente de financiamiento
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
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
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60  style='cursor:pointer' title='Ordenar por Codigo'       align='center' onClick=ue_orden('codfuefin')>Codigo</td>";
		print "<td width=440 style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('denfuefin')>Denominacion</td>";
		print "</tr>";
		/*$ls_sql="SELECT codfuefin, denfuefin ".
				"  FROM sigesp_fuentefinanciamiento ".	
				" WHERE codemp='".$ls_codemp."' ".
				"   AND codfuefin <> '--' ".		
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";*/
		$ls_sql=" select a.codfuefin, a.denfuefin ".
				"	  from sigesp_fuentefinanciamiento a, spg_dt_fuentefinanciamiento b ".
				"	  where a.codemp=b.codemp ".
				"		and a. codfuefin=b.codfuefin ".
				"		and b.codestpro1='".$ls_codestpro1."'".
				"		and b.codestpro2='".$ls_codestpro2."'".
				"		and b.codestpro3='".$ls_codestpro3."'".
				"		and b.codestpro4='".$ls_codestpro4."'".
				"		and b.codestpro5='".$ls_codestpro5."'".
				"		and b.estcla='".$ls_estcla."'        ". 
				"     order by a.codfuefin "; 
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
				$ls_codfuefin=$row["codfuefin"];
				$ls_denfuefin=$row["denfuefin"];
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: aceptar('$ls_codfuefin','$ls_denfuefin');\">".$ls_codfuefin."</a></td>";
						print "<td align='left'>".$ls_denfuefin."</td>";
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
	}// end function uf_print_fuentefinanciamiento
	//-----------------------------------------------------------------------------------------------------------------------------------
    
   	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_modalidad_clausulas()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_modalidad_clausulas
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de fuente de financiamiento
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 19/04/2007 								Fecha Última Modificación : 
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
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60  style='cursor:pointer' title='Ordenar por Codigo'       align='center' onClick=ue_orden('codtipmod')>Codigo</td>";
		print "<td width=440 style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('denmodcla')>Denominacion</td>";
		print "</tr>";
		$ls_sql=" SELECT codtipmod, denmodcla       ".
				" FROM soc_modalidadclausulas       ".	
				" WHERE codemp='".$ls_codemp."' AND ".
				"       codtipmod <> '--' ".		
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codtipmod=$row["codtipmod"];
				$ls_denmodcla=$row["denmodcla"];
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: aceptar('$ls_codtipmod','$ls_denmodcla');\">".$ls_codtipmod."</a></td>";
						print "<td align='left'>".$ls_denmodcla."</td>";
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
	}// end function uf_print_modalidad_clausulas
	//-----------------------------------------------------------------------------------------------------------------------------------
   	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_moneda()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_moneda
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de fuente de financiamiento
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 19/04/2007 								Fecha Última Modificación : 
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
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60  style='cursor:pointer' title='Ordenar por Codigo'       align='center' onClick=ue_orden('codmon')>Codigo</td>";
		print "<td width=440 style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('denmon')>Denominacion</td>";
		print "</tr>";
		$ls_sql=" SELECT sigesp_moneda.codmon,sigesp_moneda.denmon,sigesp_moneda.codpai,sigesp_moneda.estmonpri,
                        sigesp_moneda.abrmon,sigesp_dt_moneda.tascam1
                  FROM sigesp_moneda,sigesp_dt_moneda
                  WHERE sigesp_moneda.codmon <> '--' 
                        AND sigesp_dt_moneda.codmon=sigesp_moneda.codmon		
				 ORDER BY ".$ls_campoorden." ".$ls_orden.""; 
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codmon=$row["codmon"];
				$ls_denmon=$row["denmon"];
				$ls_codpai=$row["codpai"];
		    	$ls_tascam=$row["tascam1"];
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: aceptar('$ls_codmon','$ls_denmon',$ls_tascam);\">".$ls_codmon."</a></td>";
						print "<td align='left'>".$ls_denmon."</td>";
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
	}// end function uf_print_moneda
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_solicitudes_cotizacion($as_origen,$as_codpro)
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_solicitudes_cotizacion
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que obtiene todas las Solicitudes de Cotizaciónes segun filtros.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 06/05/2007								Fecha Última Modificación :06/05/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_soc;
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");
		
		$io_include    = new sigesp_include();
		$io_conexion   = $io_include->uf_conectar();
		$io_sql        = new class_sql($io_conexion);	
		$io_mensajes   = new class_mensajes();		
		$io_funciones  = new class_funciones();		
        $ls_codemp     = $_SESSION['la_empresa']['codemp'];
		$ls_numsolcot  = $_POST['numsolcot'];
		$ls_tipsolcot  = $_POST['tipsolcot'];
		$ls_fecdes     = $_POST['fecdes'];
		$ls_fecdes     = $io_funciones->uf_convertirdatetobd($ls_fecdes);
		$ls_fechas     = $_POST['fechas'];
		$ls_fechas     = $io_funciones->uf_convertirdatetobd($ls_fechas);
		$ls_orden      = $_POST['orden'];
		$ls_campoorden = $_POST['campoorden'];
		$ls_filtro     = "";
		$ls_group      = "";
        $ls_tabla      = "";
		$ls_cadaux     = "";

		if (!empty($ls_numsolcot))
		   { 
		     $ls_filtro = " AND soc_sol_cotizacion.numsolcot ='".$ls_numsolcot."'";
		   }
		if (!empty($ls_tipsolcot) && $ls_tipsolcot!='-')
		   {
		     $ls_filtro = $ls_filtro." AND soc_sol_cotizacion.tipsolcot ='".$ls_tipsolcot."'";
		   }
		if (!empty($ls_fecdes) && !empty($ls_fechas))
		   {
		     $ls_filtro = $ls_filtro." AND soc_sol_cotizacion.fecsol BETWEEN '".$ls_fecdes."' AND '".$ls_fechas."'";
		   }
		if ($as_origen=='RC')//Registro de Cotización.
		   {
		     if ($ls_tipsolcot=='B')
			    {
				  $ls_tabla = ', soc_dtsc_bienes';
  				  $ls_campo  = "codart";
				  $ls_straux = 'soc_dtsc_bienes';
				}
			 elseif($ls_tipsolcot=='S')
			    {
				  $ls_tabla  = ', soc_dtsc_servicios';
				  $ls_campo  = "codser";
				  $ls_straux = 'soc_dtsc_servicios';
				}
			 if (!empty($as_codpro))
			    {
				  $ls_filtro = $ls_filtro." AND $ls_straux.cod_pro='".$as_codpro."'";
				}
		     $ls_cadaux = " AND soc_sol_cotizacion.codemp=$ls_straux.codemp AND soc_sol_cotizacion.numsolcot=$ls_straux.numsolcot";
			 $ls_cadaux = $ls_cadaux." AND soc_sol_cotizacion.numsolcot NOT IN (SELECT numsolcot
                                               						              FROM soc_cotizacion
                                                                                 WHERE codemp='".$ls_codemp."'
                                                                                   AND cod_pro='".$as_codpro."'
                                                                                   AND tipcot='".$ls_tipsolcot."')";
		   }
		print "<table width=580 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=100 style='cursor:pointer' title='Ordenar por Número'   align='center' onClick=ue_orden('numsolcot')>Nro. Solicitud</td>";
		print "<td width=400 style='cursor:pointer' title='Ordenar por Concepto' align='center' onClick=ue_orden('consolcot')>Concepto</td>";
		print "<td width=80  style='cursor:pointer' title='Ordenar por Fecha'    align='center' onClick=ue_orden('fecsol')>Fecha</td>";
		print "</tr>";
 		$ls_sql = " SELECT soc_sol_cotizacion.numsolcot,
						   max(COALESCE(soc_sol_cotizacion.tipsolbie,'-')) as tipsolbie,
						   max(soc_sol_cotizacion.fecsol) as fecsol,
						   max(soc_sol_cotizacion.obssol) as obssol,
						   max(soc_sol_cotizacion.consolcot) as consolcot,
						   max(soc_sol_cotizacion.uniejeaso) as uniejeaso,
						   max(soc_sol_cotizacion.estcot) as estcot,
						   max(soc_sol_cotizacion.codusu) as codusu,
						   max(soc_sol_cotizacion.cedper) as cedper,
						   max(soc_sol_cotizacion.codcar) as codcar,
						   max(soc_sol_cotizacion.soltel) as soltel,
						   max(soc_sol_cotizacion.solfax) as solfax,
						   max(soc_sol_cotizacion.coduniadm) as coduniadm,
						   max(spg_unidadadministrativa.denuniadm) as denuniadm,
						   max(soc_sol_cotizacion.tipsolcot) as tipsolcot,
						   max(soc_sol_cotizacion.codestpro1) as codestpro1,
						   max(soc_sol_cotizacion.codestpro2) as codestpro2,
						   max(soc_sol_cotizacion.codestpro3) as codestpro3,
						   max(soc_sol_cotizacion.codestpro4) as codestpro4,
						   max(soc_sol_cotizacion.codestpro5) as codestpro5,
						   max(soc_sol_cotizacion.estcla) as estcla,
						   max(sno_personal.nomper) as nomper,
						   max(sno_personal.apeper) as apeper
					  FROM soc_sol_cotizacion, spg_unidadadministrativa, spg_dt_unidadadministrativa, sno_personal
					        $ls_tabla
					 WHERE soc_sol_cotizacion.codemp='".$ls_codemp."' $ls_filtro $ls_cadaux
					   AND soc_sol_cotizacion.codemp=spg_unidadadministrativa.codemp
					   AND soc_sol_cotizacion.coduniadm=spg_unidadadministrativa.coduniadm 
			 	       AND spg_unidadadministrativa.codemp=spg_dt_unidadadministrativa.codemp
					   AND spg_unidadadministrativa.coduniadm=spg_dt_unidadadministrativa.coduniadm
					   AND soc_sol_cotizacion.codemp=sno_personal.codemp
					   AND soc_sol_cotizacion.cedper=sno_personal.cedper
					 GROUP BY soc_sol_cotizacion.numsolcot
					 ORDER BY soc_sol_cotizacion.".$ls_campoorden." ".$ls_orden;
		$rs_data=$io_sql->select($ls_sql);//echo $ls_sql.'<br>';
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while(!$rs_data->EOF)
			{
				$ls_numsolcot  = trim($rs_data->fields["numsolcot"]);
				$ls_tipsolcot  = $rs_data->fields["tipsolcot"];
				$ls_fecsolcot  = $io_funciones->uf_formatovalidofecha($rs_data->fields["fecsol"]);
				$ls_fecsolcot  = $io_funciones->uf_convertirfecmostrar($ls_fecsolcot);
				$ls_obssolcot  = $rs_data->fields["obssol"];
				$ls_consolcot  = $rs_data->fields["consolcot"];
				$ls_uniejeaso  = $rs_data->fields["uniejeaso"];
				$ls_cedpersol  = $rs_data->fields["cedper"];
				$ls_nompersol  = $rs_data->fields["nomper"];
				$ls_apepersol  = $rs_data->fields["apeper"];				
				if (!empty($ls_apepersol))
				   {
				     $ls_nompersol = $ls_apepersol.", ".$ls_nompersol;
				   }
				$ls_codcarper  = $rs_data->fields["codcar"];
				$ls_soltel     = $rs_data->fields["soltel"];
				$ls_solfax     = $rs_data->fields["solfax"];
				$ls_codunieje  = trim($rs_data->fields["coduniadm"]);
				$ls_denunieje  = $rs_data->fields["denuniadm"];
				$ls_estcla     = $rs_data->fields["estcla"];
				$ls_codestpro1 = str_pad(trim($rs_data->fields["codestpro1"]),25,0,0);
				$ls_codestpro2 = str_pad(trim($rs_data->fields["codestpro2"]),25,0,0);
				$ls_codestpro3 = str_pad(trim($rs_data->fields["codestpro3"]),25,0,0);
				$ls_codestpro4 = str_pad(trim($rs_data->fields["codestpro4"]),25,0,0);
				$ls_codestpro5 = str_pad(trim($rs_data->fields["codestpro5"]),25,0,0);
                $ls_tipbiesol  = $rs_data->fields["tipsolbie"];
				$ls_estsolcot  = "R";
				$ls_strsql     = "SELECT numsolcot FROM soc_cotizacion WHERE codemp='".$ls_codemp."' AND numsolcot='".$ls_numsolcot."'";
				$rs_datos      = $io_sql->select($ls_strsql);
				if ($rs_datos===false)
				   {
				     $lb_valido = false;
                     $io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
				   }
				else
				   {
				     if ($row=$io_sql->fetch_row($rs_datos))
					    {
						  $ls_estsolcot = 'P';
						}
				   }
				echo "<tr class=celdas-blancas>";
				switch ($as_origen){
				  case 'SC':
					echo "<td align='center'><a href=\"javascript: ue_aceptar_solicitud('$ls_numsolcot','$ls_tipsolcot','$ls_fecsolcot','$ls_obssolcot','$ls_consolcot','$ls_uniejeaso',".
						  "'$ls_cedpersol','$ls_nompersol','$ls_codcarper','$ls_soltel','$ls_solfax','$ls_codunieje','$ls_denunieje','$ls_codestpro1','$ls_codestpro2','$ls_codestpro3',".
						  "'$ls_codestpro4','$ls_codestpro5','$ls_estcla','$ls_estsolcot','$ls_tipbiesol');\">".$ls_numsolcot."</a></td>";
				    break;
				  case 'RC': 
				 	  echo "<td align='center'><a href=\"javascript: ue_aceptar_registro('$ls_numsolcot','$as_codpro','$ls_tipsolcot');\">".$ls_numsolcot."</a></td>";
				  break;
				  case "REPDES":
						echo "<td><a href=\"javascript:aceptar_reportedesde('$ls_numsolcot');\">".$ls_numsolcot."</a></td>";
				  break;
				  case "REPHAS":
						echo "<td><a href=\"javascript:aceptar_reportehasta('$ls_numsolcot');\">".$ls_numsolcot."</a></td>";
				  break;
				}
				echo "<td align='left'>".$ls_consolcot."</td>";
				echo "<td align='center'>".$ls_fecsolcot."</td>";
				echo "</tr>";
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
	}// end function uf_print_solicitudes_cotizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cotizaciones($as_origen,$as_tipcot)
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cotizaciones
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que obtiene todas las Solicitudes de Cotizaciónes segun filtros.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 28/05/2007								Fecha Última Modificación :28/05/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_soc;
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");
		
		$io_include    = new sigesp_include();
		$io_conexion   = $io_include->uf_conectar();
		$io_sql        = new class_sql($io_conexion);	
		$io_mensajes   = new class_mensajes();		
		$io_funciones  = new class_funciones();		
        $ls_codemp     = $_SESSION['la_empresa']['codemp'];
		$ls_numcot     = $_POST['numcot'];
		$ls_fecdes     = $_POST['fecdes'];
		$ls_fecdes     = $io_funciones->uf_convertirdatetobd($ls_fecdes);
		$ls_fechas     = $_POST['fechas'];
		$ls_fechas     = $io_funciones->uf_convertirdatetobd($ls_fechas);
		$ls_orden      = $_POST['orden'];
		$ls_campoorden = $_POST['campoorden'];
		$ls_filtro     = "";
        $ls_filtro = " AND soc_cotizacion.numcot like '%".$ls_numcot."%'";
		if (!empty($as_tipcot) && $as_tipcot!='-')
		   {
		     $ls_filtro = $ls_filtro." AND soc_cotizacion.tipcot ='".$as_tipcot."'";
		   }
		if (!empty($ls_fecdes) && !empty($ls_fechas))
		   {
		     $ls_filtro = $ls_filtro." AND soc_cotizacion.feccot BETWEEN '".$ls_fecdes."' AND '".$ls_fechas."'";
		   }
		print "<table width=680 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=120 style='cursor:pointer' title='Ordenar por Número'       align='center' onClick=ue_orden('numcot')>Nro. Cotizaci&oacute;n</td>";
		print "<td width=180 style='cursor:pointer' align='center'>Proveedor</td>";
		print "<td width=260 style='cursor:pointer' title='Ordenar por Observación'  align='center' onClick=ue_orden('obscot')>Observaci&oacute;n</td>";
		print "<td width=60  style='cursor:pointer' title='Ordenar por Fecha'        align='center' onClick=ue_orden('feccot')>Fecha</td>";
		print "<td width=60>Estatus</td>";
		print "</tr>";
        $ls_sql = "SELECT soc_cotizacion.numcot,soc_cotizacion.cod_pro,soc_cotizacion.numsolcot,soc_cotizacion.feccot,
						  soc_cotizacion.obscot,soc_cotizacion.monsubtot,soc_cotizacion.monimpcot,soc_cotizacion.mondes,
						  soc_cotizacion.montotcot,soc_cotizacion.diaentcom,soc_cotizacion.estcot,soc_cotizacion.forpagcom,
						  soc_cotizacion.poriva,soc_cotizacion.estinciva,soc_cotizacion.tipcot,rpc_proveedor.nompro
					 FROM soc_cotizacion, rpc_proveedor
					WHERE soc_cotizacion.codemp='".$ls_codemp."' $ls_filtro
					  AND soc_cotizacion.codemp=rpc_proveedor.codemp
					  AND soc_cotizacion.cod_pro=rpc_proveedor.cod_pro
			     ORDER BY soc_cotizacion.".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_numcot    = $row["numcot"];
				$ls_codpro    = $row["cod_pro"];
				$ls_numsolcot = $row["numsolcot"];
				$ls_fecregcot = $io_funciones->uf_formatovalidofecha($row["feccot"]);
				$ls_fecregcot = $io_funciones->uf_convertirfecmostrar($ls_fecregcot);
				$ls_obscot    = $row["obscot"];
				$ld_monsubcot = number_format($row["monsubtot"],2,',','.');
				$ld_moncrecot = number_format($row["monimpcot"],2,',','.');
				$ld_mondescot = number_format($row["mondes"],2,',','.');
				$ld_montotcot = number_format($row["montotcot"],2,',','.');
				$li_diaent    = $row["diaentcom"];
				$ls_estcot    = $row["estcot"];
				$ls_forpag    = $row["forpagcom"];
				$ld_poriva    = number_format($row["poriva"],2,',','.');
				$li_estinciva = $row["estinciva"];
				$ls_nompro    = $row["nompro"];
				$ls_tipcot    = $row["tipcot"];
				if ($ls_estcot=='0')//R=REGISTRO.
				   {
				     $ls_estcot='REGISTRO';
				   }
				elseif($ls_estcot=='1')//P=PROCESADA.
				   {
				     $ls_estcot='PROCESADA';
				   }

				print "<tr class=celdas-blancas>";
				switch ($as_origen){
				  case 'RC':
				     print "<td align='center' width=120><a href=\"javascript: ue_aceptar('$ls_numcot','$ls_codpro','$ls_numsolcot','$ls_fecregcot','$ls_obscot','$ld_monsubcot','$ld_moncrecot','$ld_montotcot','$li_diaent','$ls_estcot','$ls_forpag','$ld_poriva','$li_estinciva','$ls_nompro','$ls_tipcot');\">".$ls_numcot."</a></td>";
				  break;
				  case 'REPDES':
				     print "<td align='center' width=120><a href=\"javascript: aceptar_reportedesde('$ls_numcot');\">".$ls_numcot."</a></td>";
				  break;
				  case 'REPHAS':
				     print "<td align='center' width=120><a href=\"javascript: aceptar_reportehasta('$ls_numcot');\">".$ls_numcot."</a></td>";
				  break;				  
				}
				print "<td align='left' width=180>".$ls_nompro."</td>";
				print "<td align='left' width=260>".$ls_obscot."</td>";
				print "<td align='center' width=60>".$ls_fecregcot."</td>";
				print "<td align='center' width=60>".$ls_estcot."</td>";
				print "</tr>";			
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
	}// end function uf_print_cotizaciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_sep($as_tipo)
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_sep
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que obtiene todas las Solicitudes de Ejecución Presupuestaria segun filtros.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 06/05/2007								Fecha Última Modificación :06/05/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_soc;
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");
		
		$io_include    = new sigesp_include();
		$io_conexion   = $io_include->uf_conectar();
		$io_sql        = new class_sql($io_conexion);	
		$io_mensajes   = new class_mensajes();		
		$io_funciones  = new class_funciones();		
        $ls_codemp     = $_SESSION['la_empresa']['codemp'];
		$ls_numsol     = $_POST['numsep'];
		$ls_tipsolbie  = $_POST['tipsolbie'];
		$ls_tipsolcot  = $_POST['hidtipsolcot'];
		if ($ls_tipsolcot=='B')
		   {
		     $ls_tabla = "sep_dt_articulos";
		   }
		elseif($ls_tipsolcot=='S')
		   {
		     $ls_tabla = "sep_dt_servicio";
		   }
		$ls_cadena = "";
		$ls_straux = "";
		if (!empty($ls_tabla))
		   {
		     $ls_cadena = ", $ls_tabla ";
		     $ls_straux = " AND $ls_tabla.estincite = 'NI' AND sep_solicitud.codemp=$ls_tabla.codemp AND sep_solicitud.numsol=$ls_tabla.numsol ";
		   } 
		$ls_fecdes     = $_POST['fecregdes'];
		$ls_fecdes     = $io_funciones->uf_convertirdatetobd($ls_fecdes);
		$ls_fechas     = $_POST['fecreghas'];
		$ls_fechas     = $io_funciones->uf_convertirdatetobd($ls_fechas);
		$ls_orden      = $_POST['orden'];
		$ls_campoorden = $_POST['campoorden'];
		$ls_tipdes     = $_POST['tipdes'];
		if ($ls_tipdes=='P')
		   {
		     $ls_straux = $ls_straux." AND sep_solicitud.tipo_destino = 'P'";
		   }
		if ($ls_tipdes=='-')
		   {
		     $ls_straux = $ls_straux." AND sep_solicitud.tipo_destino = '-'";
		   }
		if (!empty($ls_fecdes) && !empty($ls_fechas))
		   {
		     $ls_straux = $ls_straux." AND sep_solicitud.fecregsol BETWEEN '".$ls_fecdes."' AND '".$ls_fechas."'";
		   }
		echo "<table width=750 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		echo "<tr class=titulo-celda>";
		echo "<td width=100 style='cursor:pointer' title='Ordenar por Número'            align='center' onClick=ue_orden('numsol')>Nro. Solicitud</td>";
		echo "<td width=300 align='center'>Concepto</td>";
		echo "<td width=200 align='center'>Proveedor</td>";
		echo "<td width=70 style='cursor:pointer' title='Ordenar por Fecha de Registro' align='center' onClick=ue_orden('fecregsol')>Fecha</td>";
		echo "<td width=80 align='center'>Estatus</td>";
		echo "<td width=110  style='cursor:pointer' title='Ordenar por Monto'             align='center' onClick=ue_orden('monto')>Monto</td>";
		echo "<td></td>";
		echo "</tr>";
 		$ls_sql = "SELECT sep_solicitud.numsol, 
		 				  max(sep_solicitud.consol) as consol, 
						  max(sep_solicitud.fecregsol) as fecregsol, 
						  max(sep_solicitud.estsol) as estsol,
						  max(sep_solicitud.monto) as monto,
						  max(sep_solicitud.coduniadm) as unieje,
						  max(spg_unidadadministrativa.denuniadm) as denuniadm,
						  max(sep_solicitud.codestpro1) as codestpro1,
						  max(sep_solicitud.codestpro2) as codestpro2,
						  max(sep_solicitud.codestpro3) as codestpro3,
						  max(sep_solicitud.codestpro4) as codestpro4,
						  max(sep_solicitud.codestpro5) as codestpro5,
						  max(sep_solicitud.estcla) as estcla,
		                  max(rpc_proveedor.cod_pro) as cod_pro, 
						  max(rpc_proveedor.nompro) as nompro, 
						  max(rpc_proveedor.dirpro) as dirpro, 
						  max(rpc_proveedor.telpro) as telpro   
					 FROM sep_solicitud, spg_unidadadministrativa, spg_dt_unidadadministrativa, rpc_proveedor,
					      spg_ep1, spg_ep2, spg_ep3, spg_ep4, spg_ep5,sep_tiposolicitud $ls_cadena
					WHERE sep_solicitud.codemp='".$ls_codemp."'
					  AND sep_solicitud.numsol like '%".$ls_numsol."%'
                      AND (sep_solicitud.estsol='C' OR sep_solicitud.estsol='P') 
					  AND sep_solicitud.tipsepbie='".$ls_tipsolbie."' $ls_straux
					  AND sep_solicitud.codemp=rpc_proveedor.codemp
					  AND sep_solicitud.cod_pro=rpc_proveedor.cod_pro
					  AND sep_solicitud.codemp=spg_unidadadministrativa.codemp
					  AND sep_solicitud.coduniadm=spg_unidadadministrativa.coduniadm
					  /*
					  AND spg_unidadadministrativa.codemp=spg_dt_unidadadministrativa.codemp
					  AND spg_unidadadministrativa.coduniadm=spg_dt_unidadadministrativa.coduniadm
	 		          AND spg_dt_unidadadministrativa.codemp=spg_ep1.codemp
					  AND spg_dt_unidadadministrativa.codestpro1=spg_ep1.codestpro1
					  AND spg_dt_unidadadministrativa.estcla=spg_ep1.estcla
					  AND spg_dt_unidadadministrativa.codemp=spg_ep2.codemp
					  AND spg_dt_unidadadministrativa.codestpro1=spg_ep2.codestpro1
					  AND spg_dt_unidadadministrativa.codestpro2=spg_ep2.codestpro2
					  AND spg_dt_unidadadministrativa.estcla=spg_ep2.estcla
					  AND spg_dt_unidadadministrativa.codemp=spg_ep3.codemp
					  AND spg_dt_unidadadministrativa.codestpro1=spg_ep3.codestpro1
					  AND spg_dt_unidadadministrativa.codestpro2=spg_ep3.codestpro2
					  AND spg_dt_unidadadministrativa.codestpro3=spg_ep3.codestpro3
					  AND spg_dt_unidadadministrativa.estcla=spg_ep3.estcla
					  AND spg_dt_unidadadministrativa.codemp=spg_ep4.codemp
					  AND spg_dt_unidadadministrativa.codestpro1=spg_ep4.codestpro1
					  AND spg_dt_unidadadministrativa.codestpro2=spg_ep4.codestpro2
					  AND spg_dt_unidadadministrativa.codestpro3=spg_ep4.codestpro3
					  AND spg_dt_unidadadministrativa.codestpro4=spg_ep4.codestpro4
					  AND spg_dt_unidadadministrativa.estcla=spg_ep4.estcla
					  AND spg_dt_unidadadministrativa.codemp=spg_ep5.codemp
					  AND spg_dt_unidadadministrativa.codestpro1=spg_ep5.codestpro1
					  AND spg_dt_unidadadministrativa.codestpro2=spg_ep5.codestpro2
					  AND spg_dt_unidadadministrativa.codestpro3=spg_ep5.codestpro3
					  AND spg_dt_unidadadministrativa.codestpro4=spg_ep5.codestpro4
					  AND spg_dt_unidadadministrativa.codestpro5=spg_ep5.codestpro5
					  AND spg_dt_unidadadministrativa.estcla=spg_ep5.estcla
					  */
					  AND sep_solicitud.codtipsol=sep_tiposolicitud.codtipsol 
					  AND sep_tiposolicitud.estope='R' AND (sep_tiposolicitud.modsep='B' or sep_tiposolicitud.modsep='S') 
				    GROUP BY sep_solicitud.numsol
					ORDER BY sep_solicitud.".$ls_campoorden." ".$ls_orden.""; //print $ls_sql;
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_numsol = trim($row["numsol"]);
				$ls_consol = $row["consol"];
				$ls_nompro = $row["nompro"];
				$ls_fecsol = $io_funciones->uf_convertirfecmostrar($row["fecregsol"]);
				$ls_estsol = $row["estsol"];
				switch ($ls_estsol){
				  case 'R':
				    $ls_estsol = "REGISTRO";
				  break;
                             case 'E':
				    $ls_estsol = "EMITIDA";
				  break;				  
                             case 'P':
				    $ls_estsol = "PROCESADA";
				  break;
				  case 'C':
				    $ls_estsol = "CONTABILIZADA";
				  break;
				  case 'A':
				    $ls_estsol = "ANULADA";
				  break;
				}
				$ld_monsol = number_format($row["monto"],2,',','.');
				$ls_unieje = $row["unieje"];
				$ls_denuni = $row["denuniadm"];
				$ls_codpro = trim($row["cod_pro"]);
				$ls_nompro = $row["nompro"];
				$ls_dirpro = $row["dirpro"];
				$ls_telpro = $row["telpro"]; 

				$ls_codestpro1 = str_pad(trim($row["codestpro1"]),25,0,0);
				$ls_codestpro2 = str_pad(trim($row["codestpro2"]),25,0,0);
				$ls_codestpro3 = str_pad(trim($row["codestpro3"]),25,0,0);
				$ls_codestpro4 = str_pad(trim($row["codestpro4"]),25,0,0);
				$ls_codestpro5 = str_pad(trim($row["codestpro5"]),25,0,0);
				$ls_estcla     = trim($row["estcla"]);
				
				echo "<tr class=celdas-blancas>";
				echo "<td align='center'>".$ls_numsol."</td>";
				echo "<td align='left'>".$ls_consol."</td>";
				echo "<td align='left'>".$ls_nompro."</td>";
				echo "<td align='center'>".$ls_fecsol."</td>";			
				echo "<td align='center'>".$ls_estsol."</td>";			
				echo "<td align='right'>".$ld_monsol."</td>";
				echo "<td style='cursor:pointer'>";
				switch ($as_tipo){
				  case 'SC':
			        print "<a href=\"javascript: ue_aceptar('".$ls_numsol."','".$ls_consol."','".$ld_monsol."','".$ls_unieje."','".$ls_denuni."','".$ls_codpro."','".$ls_nompro."','".$ls_dirpro."','".$ls_telpro."','".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_estcla."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
				  break;
				  case 'REPDES':
				     print "<a href=\"javascript: aceptar_reportedesde('".$ls_numsol."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
				  break;
				  case 'REPHAS':
				     print "<a href=\"javascript: aceptar_reportehasta('".$ls_numsol."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
				  break;
				}  
				print "</tr>";		
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
	}// end function uf_print_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_analisis()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_analisis();
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que obtiene e imprime los analisis de cotizacion previamente registrados
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 09/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_soc;		
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_numsolcot="%".$_POST['numsol']."%";
		$ls_numanacot="%".$_POST['numanacot']."%";
		$ls_fecini=$io_funciones->uf_convertirdatetobd($_POST['fecini']);
		$ls_fecfin=$io_funciones->uf_convertirdatetobd($_POST['fecfin']);
		if ($ls_fecini!="")
			$ls_cadena1=" AND fecanacot>='$ls_fecini'";
		else
			$ls_cadena1="";
		if ($ls_fecfin!="")
			$ls_cadena2=" AND fecanacot<='$ls_fecfin'";
		else
			$ls_cadena2="";	
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_sql="SELECT numanacot,
		                max(fecanacot) as fecanacot,
						max(obsana) as obsana,
						max(numsolcot) as numsolcot,
						max(tipsolcot) as tipsolcot,
						max(estana) as estana
					FROM soc_analisicotizacion
					WHERE codemp='$ls_codemp' 
					AND numanacot like '$ls_numanacot'
					AND numsolcot like '$ls_numsolcot'
					$ls_cadena1 $ls_cadena2
					GROUP BY numanacot
					ORDER BY ".$ls_campoorden." ".$ls_orden."";		
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
				print "<table width=630 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
				print "<tr class=titulo-celda>";
				print "<td style='cursor:pointer' title='Ordenar por N° de Analisis'       align='center' onClick=ue_orden('numanacot')>No de Analisis</td>";
				print "<td style='cursor:pointer' title='Ordenar por N° Solicitud'         align='center' onClick=ue_orden('numsolcot')>No de Solicitud</td>";
				print "<td style='cursor:pointer' title='Ordenar por Observacion'          align='center' onClick=ue_orden('obsana')>Observacion</td>";
				print "<td style='cursor:pointer' title='Ordenar por Fecha'                align='center' onClick=ue_orden('fecanacot')   width=70>Fecha</td>";
				print "<td style='cursor:pointer' title='Ordenar por Tipo'                 align='center' onClick=ue_orden('tipsolcot')>Tipo</td>";
				print "<td style='cursor:pointer' title='Ordenar por Estatus'              align='center' onClick=ue_orden('estana')>Estatus</td>";
				print "</tr>";
				while($row=$io_sql->fetch_row($rs_data))
				{
					if($row["tipsolcot"]=="B")
						$ls_tipsolcot="Bienes";
					else
						$ls_tipsolcot="Servicios";
					$ls_numanacot=$row["numanacot"];
					$ls_numsolcot=$row["numsolcot"];
					$ls_obsana=$row["obsana"];
					$ls_feccot=$io_funciones->uf_formatovalidofecha($row["fecanacot"]);
					$ls_fecanacot=$io_funciones->uf_convertirfecmostrar($ls_feccot);
					if($row["estana"] == 0)
						$ls_estatus="Registro";
					else
						$ls_estatus="Procesada";
					print "<tr class=celdas-blancas>";
					print "<td align='center'><a href=\"javascript:ue_aceptar('$ls_numanacot','$ls_fecanacot','$ls_obsana','$ls_numsolcot','".$row["tipsolcot"]."','$ls_estatus');\">".$ls_numanacot."</a></td>";
					print "<td align='left'>".$ls_numsolcot."</td>";
					print "<td align='left'>".$ls_obsana."</td>";
					print "<td align='center'>".$ls_fecanacot."</td>";
					print "<td align='center'>".$ls_tipsolcot."</td>";
					print "<td align='center'>".$ls_estatus."</td>";
					print "</tr>";			
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
	}// end function uf_print_cotizacion_analisis
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_orden_compra()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_orden_compra
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de las Ordenes de Compra
		//     Creado Por: Ing. Yozelin Barragan.
		// Fecha Creación: 09/05/2007 								Fecha Última Modificación : 
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
		$ls_numordcom="%".$_POST["numordcom"]."%";
		$ls_codpro="%".$_POST["codpro"]."%";
		$ls_tipordcom=$_POST["tipordcom"];
		$ld_fecregdes=$io_funciones->uf_convertirdatetobd($_POST["fecregdes"]);
		$ld_fecreghas=$io_funciones->uf_convertirdatetobd($_POST["fecreghas"]);
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=120 style='cursor:pointer' title='Ordenar por Numero de Orden de Compra' align='center' onClick=ue_orden('soc_ordencompra.numordcom')>N&uacute;mero</td>";
		print "<td width=150 style='cursor:pointer' title='Ordenar por Proveedor' align='center' onClick=ue_orden('nompro')>Proveedor</td>";
		print "<td width=70 style='cursor:pointer'  title='Ordenar por Tipo de Orden de Compra' align='center' onClick=ue_orden('soc_ordencompra.estcondat')>Tipo</td>";
		print "<td width=90  style='cursor:pointer' title='Ordenar por Estatus' align='center' onClick=ue_orden('soc_ordencompra.estcom')>Estatus</td>";
		print "<td width=70 style='cursor:pointer' title='Ordenar por Fecha de Registro' align='center' onClick=ue_orden('soc_ordencompra.fecordcom')>Fecha de Registro</td>";
		print "<td width=100 style='cursor:pointer' title='Ordenar por Monto' align='center' onClick=ue_orden('soc_ordencompra.montot')>Monto</td>";
		print "</tr>";
		if($ls_tipordcom=='-')
		{
		  $ls_cadena="";
		}
		else
		{
		  $ls_cadena="soc_ordencompra.estcondat='".$ls_tipordcom."' AND";	   
		}
		/*$ls_sql=" SELECT soc_ordencompra.codemp, soc_ordencompra.numordcom, soc_ordencompra.estcondat, ".
           		"        soc_ordencompra.fecordcom, soc_ordencompra.estsegcom, soc_ordencompra.porsegcom, ".
				"        soc_ordencompra.monsegcom, soc_ordencompra.forpagcom, soc_ordencompra.estcom, ".
				"        soc_ordencompra.diaplacom, soc_ordencompra.concom, soc_ordencompra.obscom, ".
                "        soc_ordencompra.monsubtot, soc_ordencompra.monbasimp, soc_ordencompra.monimp, ".
				"        soc_ordencompra.mondes, soc_ordencompra.montot, soc_ordencompra.lugentnomdep, ".
				"        soc_ordencompra.lugentdir, soc_ordencompra.monant, soc_ordencompra.estlugcom, ".
				"        soc_ordencompra.tascamordcom, soc_ordencompra.montotdiv, soc_ordencompra.estapro, ".
                "        soc_ordencompra.fecaprord, soc_ordencompra.coduniadm, soc_ordencompra.obsordcom,  ".
				"        soc_ordencompra.cod_pro, soc_ordencompra.codfuefin, spg_unidadadministrativa.denuniadm, ".
				"        soc_ordencompra.codestpro1, soc_ordencompra.codestpro2, soc_ordencompra.tipbieordcom,".
				"        soc_ordencompra.codestpro3, soc_ordencompra.codestpro4, ".
				"        soc_ordencompra.codestpro5,soc_ordencompra.estcla , sigesp_fuentefinanciamiento.denfuefin, ".
				"        soc_ordencompra.codmon, sigesp_moneda.denmon, soc_ordencompra.codtipmod, ".
				"        soc_modalidadclausulas.denmodcla, soc_ordencompra.codpai, sigesp_pais.despai, ".
				"        soc_ordencompra.codest, sigesp_estados.desest, soc_ordencompra.codmun, ".
                "        sigesp_municipio.denmun, soc_ordencompra.codpar, sigesp_parroquia.denpar, ".
				"        soc_ordencompra.numanacot, soc_ordencompra.uniejeaso, ".
				"        soc_ordencompra.fechentdesde, soc_ordencompra.fechenthasta, ".
                "        (SELECT rpc_proveedor.nompro ".
                "         FROM   rpc_proveedor ".
                "         WHERE  rpc_proveedor.codemp=soc_ordencompra.codemp AND ".
                "                rpc_proveedor.cod_pro=soc_ordencompra.cod_pro) AS nompro, ".
				"        (SELECT rpc_proveedor.rifpro ".
                "         FROM   rpc_proveedor ".
                "         WHERE  rpc_proveedor.codemp=soc_ordencompra.codemp AND ".
                "                rpc_proveedor.cod_pro=soc_ordencompra.cod_pro) AS rifpro, ".
                "        (SELECT rpc_proveedor.tipconpro ".
                "         FROM   rpc_proveedor ".
                "         WHERE  rpc_proveedor.codemp=soc_ordencompra.codemp AND ".
                "                rpc_proveedor.cod_pro=soc_ordencompra.cod_pro) AS tipconpro ".
                " FROM  soc_ordencompra,spg_unidadadministrativa,spg_dt_unidadadministrativa,sigesp_fuentefinanciamiento,sigesp_moneda, ".
				"       soc_modalidadclausulas, sigesp_pais,sigesp_estados,sigesp_municipio,sigesp_parroquia ".
                " WHERE soc_ordencompra.codemp = '".$ls_codemp."' AND ".
				"		soc_ordencompra.numordcom like '".$ls_numordcom."' AND ".
				"       ".$ls_cadena." soc_ordencompra.cod_pro like '".$ls_codpro."' AND ".
                "       soc_ordencompra.fecordcom BETWEEN '".$ld_fecregdes."' AND '".$ld_fecreghas."' AND ".
                "       soc_ordencompra.numordcom<>'000000000000000'  AND ".
				"       soc_ordencompra.codemp=spg_unidadadministrativa.codemp AND ".
                "       soc_ordencompra.coduniadm=spg_unidadadministrativa.coduniadm AND ".
				"       soc_ordencompra.codemp=spg_dt_unidadadministrativa.codemp AND ".
				"       soc_ordencompra.coduniadm=spg_dt_unidadadministrativa.coduniadm AND ".
				"       soc_ordencompra.codestpro1=spg_dt_unidadadministrativa.codestpro1 AND ".
				"       soc_ordencompra.codestpro2=spg_dt_unidadadministrativa.codestpro2 AND ".
				"       soc_ordencompra.codestpro3=spg_dt_unidadadministrativa.codestpro3 AND ".
				"       soc_ordencompra.codestpro4=spg_dt_unidadadministrativa.codestpro4 AND ".
				"       soc_ordencompra.codestpro5=spg_dt_unidadadministrativa.codestpro5 AND ".
				"       soc_ordencompra.estcla=spg_dt_unidadadministrativa.estcla AND ".
				"       spg_dt_unidadadministrativa.codemp=spg_unidadadministrativa.codemp AND ".
                "       spg_dt_unidadadministrativa.coduniadm=spg_unidadadministrativa.coduniadm AND ".
				"		soc_ordencompra.codfuefin=sigesp_fuentefinanciamiento.codfuefin AND ".
      			"       soc_ordencompra.codemp=sigesp_fuentefinanciamiento.codemp AND ".
      			"       soc_ordencompra.codmon=sigesp_moneda.codmon AND ".
                "       soc_ordencompra.codemp=soc_modalidadclausulas.codemp AND ".
                "       soc_ordencompra.codtipmod=soc_modalidadclausulas.codtipmod AND ".
                "       soc_ordencompra.codpai=sigesp_pais.codpai AND soc_ordencompra.codest=sigesp_estados.codest AND ".
                "       soc_ordencompra.codmun=sigesp_municipio.codmun AND soc_ordencompra.codpar=sigesp_parroquia.codpar AND ".
				"       soc_ordencompra.codpai=sigesp_estados.codpai   AND soc_ordencompra.codpai=sigesp_municipio.codpai AND ".
                "       soc_ordencompra.codest=sigesp_municipio.codest AND soc_ordencompra.codpai=sigesp_parroquia.codpai AND ".
                "       soc_ordencompra.codest=sigesp_parroquia.codest AND soc_ordencompra.codmun=sigesp_parroquia.codmun ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden;*/
		$ls_sql=" SELECT soc_ordencompra.codemp, soc_ordencompra.numordcom, soc_ordencompra.estcondat, ".
           		"        soc_ordencompra.fecordcom, soc_ordencompra.estsegcom, soc_ordencompra.porsegcom, ".
				"        soc_ordencompra.monsegcom, soc_ordencompra.forpagcom, soc_ordencompra.estcom, ".
				"        soc_ordencompra.diaplacom, soc_ordencompra.concom, soc_ordencompra.obscom, ".
                "        soc_ordencompra.monsubtot, soc_ordencompra.monbasimp, soc_ordencompra.monimp, ".
				"        soc_ordencompra.mondes, soc_ordencompra.montot, soc_ordencompra.lugentnomdep, ".
				"        soc_ordencompra.lugentdir, soc_ordencompra.monant, soc_ordencompra.estlugcom, ".
				"        soc_ordencompra.tascamordcom, soc_ordencompra.montotdiv, soc_ordencompra.estapro, ".
                "        soc_ordencompra.fecaprord, soc_ordencompra.coduniadm, soc_ordencompra.obsordcom,  ".
				"        soc_ordencompra.cod_pro, soc_ordencompra.codfuefin, spg_unidadadministrativa.denuniadm, ".
				"        soc_ordencompra.codestpro1, soc_ordencompra.codestpro2, soc_ordencompra.tipbieordcom,".
				"        soc_ordencompra.codestpro3, soc_ordencompra.codestpro4, ".
				"        soc_ordencompra.codestpro5,soc_ordencompra.estcla , sigesp_fuentefinanciamiento.denfuefin, ".
				"        soc_ordencompra.codmon, soc_ordencompra.codtipmod, ".
				"        soc_ordencompra.codpai,soc_ordencompra.codest,soc_ordencompra.codmun,soc_ordencompra.codpar,".
				"        (SELECT despai FROM sigesp_pais".
				"          WHERE soc_ordencompra.codpai=sigesp_pais.codpai)AS despai, ".
				"        (SELECT desest FROM sigesp_estados ".
				"          WHERE soc_ordencompra.codpai=sigesp_estados.codpai".
				"            AND soc_ordencompra.codest=sigesp_estados.codest)AS desest,  ".
                "        (SELECT denmun FROM sigesp_municipio ".
				"          WHERE soc_ordencompra.codpai=sigesp_municipio.codpai".
				"            AND soc_ordencompra.codest=sigesp_municipio.codest".
				"            AND soc_ordencompra.codmun=sigesp_municipio.codmun) AS denmun,".
				"        (SELECT denpar FROM sigesp_parroquia".
				"          WHERE soc_ordencompra.codpai=sigesp_parroquia.codpai".
				"            AND soc_ordencompra.codest=sigesp_parroquia.codest".
				"            AND soc_ordencompra.codmun=sigesp_parroquia.codmun".
				"            AND soc_ordencompra.codpar=sigesp_parroquia.codpar) AS denpar, ".
				"        (SELECT denmon FROM sigesp_moneda".
				"          WHERE soc_ordencompra.codmon=sigesp_moneda.codmon)AS denmon, ".
				"        soc_ordencompra.numanacot, soc_ordencompra.uniejeaso, ".
				"        soc_ordencompra.fechentdesde, soc_ordencompra.fechenthasta, ".
                "        (SELECT rpc_proveedor.nompro ".
                "         FROM   rpc_proveedor ".
                "         WHERE  rpc_proveedor.codemp=soc_ordencompra.codemp AND ".
                "                rpc_proveedor.cod_pro=soc_ordencompra.cod_pro) AS nompro, ".
				"        (SELECT rpc_proveedor.rifpro ".
                "         FROM   rpc_proveedor ".
                "         WHERE  rpc_proveedor.codemp=soc_ordencompra.codemp AND ".
                "                rpc_proveedor.cod_pro=soc_ordencompra.cod_pro) AS rifpro, ".
                "        (SELECT rpc_proveedor.tipconpro ".
                "         FROM   rpc_proveedor ".
                "         WHERE  rpc_proveedor.codemp=soc_ordencompra.codemp AND ".
                "                rpc_proveedor.cod_pro=soc_ordencompra.cod_pro) AS tipconpro, ".
				"        (SELECT denmodcla FROM soc_modalidadclausulas ".
				"          WHERE soc_ordencompra.codemp=soc_modalidadclausulas.codemp".
				"            AND soc_ordencompra.codtipmod=soc_modalidadclausulas.codtipmod)AS denmodcla  ".
                " FROM  soc_ordencompra,spg_unidadadministrativa,spg_dt_unidadadministrativa,sigesp_fuentefinanciamiento ".
                " WHERE soc_ordencompra.codemp = '".$ls_codemp."' AND ".
				"		soc_ordencompra.numordcom like '".$ls_numordcom."' AND ".
				"       ".$ls_cadena." soc_ordencompra.cod_pro like '".$ls_codpro."' AND ".
                "       soc_ordencompra.fecordcom BETWEEN '".$ld_fecregdes."' AND '".$ld_fecreghas."' AND ".
                "       soc_ordencompra.numordcom<>'000000000000000'  AND ".
				"       soc_ordencompra.codemp=spg_unidadadministrativa.codemp AND ".
                "       soc_ordencompra.coduniadm=spg_unidadadministrativa.coduniadm AND ".
				"       soc_ordencompra.codemp=spg_dt_unidadadministrativa.codemp AND ".
				"       soc_ordencompra.coduniadm=spg_dt_unidadadministrativa.coduniadm AND ".
				"       soc_ordencompra.codestpro1=spg_dt_unidadadministrativa.codestpro1 AND ".
				"       soc_ordencompra.codestpro2=spg_dt_unidadadministrativa.codestpro2 AND ".
				"       soc_ordencompra.codestpro3=spg_dt_unidadadministrativa.codestpro3 AND ".
				"       soc_ordencompra.codestpro4=spg_dt_unidadadministrativa.codestpro4 AND ".
				"       soc_ordencompra.codestpro5=spg_dt_unidadadministrativa.codestpro5 AND ".
				"       soc_ordencompra.estcla=spg_dt_unidadadministrativa.estcla AND ".
				"       spg_dt_unidadadministrativa.codemp=spg_unidadadministrativa.codemp AND ".
                "       spg_dt_unidadadministrativa.coduniadm=spg_unidadadministrativa.coduniadm AND ".
				"		soc_ordencompra.codfuefin=sigesp_fuentefinanciamiento.codfuefin AND ".
      			"       soc_ordencompra.codemp=sigesp_fuentefinanciamiento.codemp  ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden;
		$rs_data=$io_sql->select($ls_sql);//echo $ls_sql.'<br>';
		if($rs_data===false)
		{
			$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			$li_i=0;
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_numordcom=$row["numordcom"];
				$ls_estcondat=$row["estcondat"];
				switch ($ls_estcondat)
				{
				   case "B":
				        $ls_tipo_orden="BIENES";
				   break ;
				   
				   case "S":
				        $ls_tipo_orden="SERVICIOS";
				   break ;
				}
				$ld_fecordcom	 = $io_funciones->uf_formatovalidofecha($row["fecordcom"]);
				$ld_fecordcom	 = $io_funciones->uf_convertirfecmostrar($ld_fecordcom);
				$ls_estsegcom    = $row["estsegcom"];
				$ls_tipconpro    = $row["tipconpro"];
				$ls_tipbieordcom = $row["tipbieordcom"];
				$ls_porsegcom=number_format($row["porsegcom"],2,",",".");
				$ld_monsegcom=number_format($row["monsegcom"],2,",",".");
				$ls_forpagcom=$row["forpagcom"];
				$ls_diaplacom=$row["diaplacom"];
				$ls_concom=caracteres_especiales($row["concom"]);
				$ls_obscom=caracteres_especiales($row["obscom"]);
				$ld_monsubtot=number_format($row["monsubtot"],2,",",".");
				$ld_monbasimp=number_format($row["monbasimp"],2,",",".");
				$ld_monimp=number_format($row["monimp"],2,",",".");
				$ld_mondes=number_format($row["mondes"],2,",",".");
				$ld_montot=number_format($row["montot"],2,",",".");
				$ls_lugentnomdep=$row["lugentnomdep"];
				$ls_lugentdir=$row["lugentdir"];
				$ld_monant=number_format($row["monant"],2,",",".");
				$ls_estlugcom=$row["estlugcom"];
				$ld_tascamordcom=number_format($row["tascamordcom"],2,",",".");
				$ld_montotdiv=number_format($row["montotdiv"],2,",",".");
				$ld_fecaprord=$row["fecaprord"];
				$ls_coduniadm=$row["coduniadm"];
				$ls_denuniadm=$row["denuniadm"];
				$ls_obsordcom=$row["obsordcom"];
				$ls_cod_pro=$row["cod_pro"];
				$ls_nompro=$row["nompro"];
				$ls_codfuefin=$row["codfuefin"];
				$ls_denfuefin=$row["denfuefin"];
				$ls_codestpro1 = trim($row["codestpro1"]);
				$ls_codestpro2 = trim($row["codestpro2"]);
				$ls_codestpro3 = trim($row["codestpro3"]);
				$ls_codestpro4 = trim($row["codestpro4"]);
				$ls_codestpro5 = trim($row["codestpro5"]);
				$ls_estcla     = $row["estcla"];
				$ls_codmon=$row["codmon"];
				$ls_denmon=$row["denmon"];
				$ls_codtipmod=$row["codtipmod"];
				$ls_denmodcla=$row["denmodcla"];
				$ls_codpai=$row["codpai"];
				$ls_despai=$row["despai"];
				$ls_codest=$row["codest"];
				$ls_desest=$row["desest"];
				$ls_codmun=$row["codmun"];
				$ls_denmun=$row["denmun"];
				$ls_codpar=$row["codpar"];
				$ls_denpar=$row["denpar"];
				$ls_estcom=$row["estcom"];
				$ls_estapro=$row["estapro"];
				$ls_numanacot=$row["numanacot"];
				$ls_uniejeaso=$row["uniejeaso"];
				$ls_rifpro=$row["rifpro"]; 
				$ls_estatus="";
				$ld_prentdesde= $io_funciones->uf_convertirfecmostrar($row["fechentdesde"]);
				$ld_prenthasta= $io_funciones->uf_convertirfecmostrar($row["fechenthasta"]);
				switch ($ls_estcom)
				{
					case "0": // Deberian ir en letras(R) como estan en la sep y en cxp 
						$ls_estatus="REGISTRO";
					break;
						
					case "1":  //   Deberia ir  E
						if($ls_estapro==1)
						{
							$ls_estatus="EMITIDA (APROBADA)";
						}
						else
						{
							$ls_estatus="EMITIDA";
						}
					break;
						
					case "2": // DEBERIA IR P
						$ls_estatus="COMPROMETIDA(PROCESADA)";
					break;
						
					case "3": //DEBERIA IR A
						$ls_estatus="ANULADA";
					break;
						
					case "4": //DEBERIA IR ????
						$ls_estatus="ENTRADA COMPRA";
					break;
						
					case "5": //DEBERIA IR ????
						$ls_estatus="PRE-COMPROMETIDA";
					break;
					
					case "6": //DEBERIA IR ????
						$ls_estatus="PRE-COMPROMETIDA ANULADA";
					break;
					
					case "7": //DEBERIA IR ????
						$ls_estatus="SERVICIO RECIBIDO";
					break;

				}
				print "<tr class=celdas-blancas>";
				$li_i++;
			    switch ($ls_tipo)
				{
					case "":
						print "<td align='center'><a href=\"javascript: ue_aceptar('$ls_numordcom','$ls_estcondat','$ld_fecordcom',".
						                    "'$ls_estsegcom','$ls_porsegcom','$ld_monsegcom','$ls_forpagcom','$ls_diaplacom',".
											"'$ls_concom','$ld_monsubtot','$ld_monbasimp','$ld_mondes','$ld_monimp',".
											"'$ld_montot','$ls_lugentnomdep','$ls_lugentdir','$ld_monant','$ls_estlugcom',".
											"'$ld_tascamordcom','$ld_montotdiv','$ls_coduniadm','$ls_denuniadm',".
											"'$ls_cod_pro','$ls_nompro','$ls_codfuefin','$ls_denfuefin','$ls_codestpro1',".
											"'$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_estcla','$ls_codmon',".
											"'$ls_denmon','$ls_codtipmod','$ls_denmodcla','$ls_codpai','$ls_despai','$ls_codest',".
											"'$ls_desest','$ls_codmun','$ls_denmun','$ls_codpar','$ls_denpar','$ls_estcom',".
											"'$ls_estapro','$ls_estatus','$ls_numanacot','$ls_tipconpro','$ls_uniejeaso',
											'$ld_prentdesde','$ld_prenthasta','$ls_tipbieordcom','$ls_rifpro','$li_i');\">".$ls_numordcom."</a></td>";
					break;
					
					case "REPORTE-DESDE":
						print "<td align='center'><a href=\"javascript: ue_aceptar_reporte_desde('$ls_numordcom');\">".$ls_numordcom."</a></td>";
					break;
					
					case "REPORTE-HASTA":
						print "<td align='center'><a href=\"javascript: ue_aceptar_reporte_hasta('$ls_numordcom');\">".$ls_numordcom."</a></td>";
					break;
			   }
			   print "<td><input name='txtobscom".$li_i."' type='hidden' id='txtobscom".$li_i."' value='$ls_obsordcom'><input name='txtconordcom".$li_i."' type='hidden' id='txtconordcom".$li_i."' value='$ls_obscom'>".$ls_nompro."</td>";
			   print "<td align='center'>".$ls_tipo_orden."</td>";
	 		   print "<td align='center'>".$ls_estatus."</td>";
			   print "<td align='left'>".$ld_fecordcom."</td>";
			   print "<td align='right'>".$ld_montot."</td>";
			   print "</tr>";			
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
	}// end function uf_print_orden_compra
	//-----------------------------------------------------------------------------------------------------------------------------------
       function caracteres_especiales($texto) 
		{
			// Tranformamos todo a minusculas
			$s = substr ($texto,2,1); 
			if(ereg("^[A-Z]", $s))
			{  
				$find = array('Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ');
			
			    $repl = array('A', 'E', 'I', 'O', 'U', 'N');
			
			    $texto = str_replace ($find, $repl, $texto);
			}
			else
			{
				$find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
			
				$repl = array('a', 'e', 'i', 'o', 'u', 'n');
				
				$texto = str_replace ($find, $repl, $texto);
			}
			$texto= strtr($texto,"?¿\º","---o");
      		return $texto;
		}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_solicitud_presupuestaria()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_solicitud_presupuestaria
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de la Solicitud de ejecuciòn presupuestaria
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan.
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 28/04/2007
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
		$ls_codemp     = $_SESSION["la_empresa"]["codemp"];
		$ls_numsol     = "%".$_POST["numsol"]."%";
		$ls_codunieje  = "%".$_POST["coduniadm"]."%";
		$ls_tipord     = $_POST["tipord"];
		$ld_fecregdes  = $io_funciones->uf_convertirdatetobd($_POST["fecregdes"]);
		$ld_fecreghas  = $io_funciones->uf_convertirdatetobd($_POST["fecreghas"]);
		$ls_orden      = $_POST['orden'];
		$ls_campoorden = $_POST['campoorden'];
		$ls_tipo       = $_POST['tipo'];
		$ls_tipbieordcom = '-';
		$ls_sqlaux = "";
		$ls_logusr=$_SESSION["la_logusr"];
		if (array_key_exists('tipbieordcom',$_POST))
		   {
		     $ls_tipbieordcom = $_POST["tipbieordcom"];
			 if ($ls_tipbieordcom=='M' || $ls_tipbieordcom=='A')
			    {
				  $ls_sqlaux = "AND sep_solicitud.tipsepbie = '".$ls_tipbieordcom."'";
				}
		   }		
		if($ls_tipord=='B')
		{
		  $ls_tabla="sep_dt_articulos";
		}
		elseif($ls_tipord=='S')
		{
		  $ls_tabla="sep_dt_servicio";
		}
		print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=100 style='cursor:pointer' title='Ordenar por Numero de Solicitud' align='center' onClick=ue_orden('sep_solicitud.numsol')>N&uacute;mero</td>";
		print "<td width=120 style='cursor:pointer' title='Ordenar por Unidad Ejecutora' align='center' onClick=ue_orden('spg_unidadadministrativa.denuniadm')>Unidad Ejecutora</td>";
		print "<td width=70  style='cursor:pointer' title='Ordenar por Fecha de Registro' align='center' onClick=ue_orden('sep_solicitud.fecregsol')>Fecha</td>";
		print "<td width=120 style='cursor:pointer' title='Ordenar por Proveedor/Beneficiario' align='center' onClick=ue_orden('nombre')>Proveedor</td>";
		print "<td width=90  style='cursor:pointer' title='Ordenar por Estatus' align='center' onClick=ue_orden('sep_solicitud.estsol')>Estatus</td>";
		print "<td width=100 style='cursor:pointer' title='Ordenar por Monto' align='center' onClick=ue_orden('monto')>Monto</td>";
		print "<td></td>";
		print "</tr>";
		$ls_sql=" SELECT DISTINCT sep_solicitud.numsol, sep_solicitud.codtipsol, sep_solicitud.coduniadm, sep_solicitud.codfuefin,".
                "        sep_solicitud.fecregsol, sep_solicitud.estsol, sep_solicitud.consol, sep_solicitud.monto, ".
                "        sep_solicitud.monbasinm, sep_solicitud.montotcar, sep_solicitud.tipo_destino, sep_solicitud.cod_pro, ".
                "        sep_solicitud.ced_bene, spg_unidadadministrativa.denuniadm, sigesp_fuentefinanciamiento.denfuefin,   ".
                "        sep_solicitud.estapro, sep_tiposolicitud.estope, sep_tiposolicitud.modsep, ".
                "        sep_solicitud.codestpro1, 
				         sep_solicitud.codestpro2,
                         sep_solicitud.codestpro3, 
				         sep_solicitud.codestpro4,
                         sep_solicitud.codestpro5, 
						 sep_solicitud.estcla, 
				".$ls_tabla.".estincite,              ".
                "        (SELECT rpc_proveedor.nompro ".   
                "         FROM   rpc_proveedor  ".
                "         WHERE  rpc_proveedor.codemp=sep_solicitud.codemp AND ".
                "                rpc_proveedor.cod_pro=sep_solicitud.cod_pro)  AS nompro ".
                " FROM    sep_solicitud, spg_unidadadministrativa, spg_dt_unidadadministrativa, sigesp_fuentefinanciamiento, sep_tiposolicitud, ".
				"         ".$ls_tabla." ".
				" WHERE   sep_solicitud.codemp='".$ls_codemp."' 
				      AND sep_solicitud.numsol like '".$ls_numsol."' 
					  AND sep_solicitud.coduniadm like '".$ls_codunieje."' 
					  AND (sep_solicitud.estsol='C' OR sep_solicitud.estsol='P') 
					  AND sep_solicitud.fecregsol between '".$ld_fecregdes."' AND '".$ld_fecreghas."' $ls_sqlaux
					  AND sep_solicitud.codemp=spg_unidadadministrativa.codemp 
					  AND sep_solicitud.coduniadm=spg_unidadadministrativa.coduniadm
					  AND spg_dt_unidadadministrativa.codemp=spg_unidadadministrativa.codemp 
					  AND spg_dt_unidadadministrativa.coduniadm=spg_unidadadministrativa.coduniadm 
					  AND sep_solicitud.codemp=spg_dt_unidadadministrativa.codemp
					  AND sep_solicitud.coduniadm=spg_dt_unidadadministrativa.coduniadm
					  AND sep_solicitud.codestpro1=spg_dt_unidadadministrativa.codestpro1
					  AND sep_solicitud.codestpro2=spg_dt_unidadadministrativa.codestpro2
					  AND sep_solicitud.codestpro3=spg_dt_unidadadministrativa.codestpro3
					  AND sep_solicitud.codestpro4=spg_dt_unidadadministrativa.codestpro4
					  AND sep_solicitud.codestpro5=spg_dt_unidadadministrativa.codestpro5
					  AND sep_solicitud.estcla=spg_dt_unidadadministrativa.estcla
					  AND sep_solicitud.codemp=sigesp_fuentefinanciamiento.codemp 
					  AND sep_solicitud.codfuefin=sigesp_fuentefinanciamiento.codfuefin 
					  AND sep_solicitud.codtipsol=sep_tiposolicitud.codtipsol
					  AND sep_tiposolicitud.estope='R' AND (sep_tiposolicitud.modsep='B' or sep_tiposolicitud.modsep='S')   
					  AND ".$ls_tabla.".numsol=sep_solicitud.numsol  
					  AND ".$ls_tabla.".estincite='NI' ".
				  "   AND  sep_solicitud.coduniadm IN (SELECT codintper FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SEP' 
				  						UNION 
										SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos 
										WHERE codusu = '".$ls_logusr."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru) ".
				  " ORDER BY ".$ls_campoorden." ".$ls_orden;  
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{ 
			while($row=$io_sql->fetch_row($rs_data))
			{ 
				$ls_numsol       = $row["numsol"]; 
				$ls_codtipsol    = $row["codtipsol"]; 
				$ls_coduniadm    = trim($row["coduniadm"]);
				$ls_codfuefin    = $row["codfuefin"];
				$ls_estsol       = $row["estsol"];
				$ls_consol       = $row["consol"];
				$ls_tipo_destino = $row["tipo_destino"];
				$ls_codpro       = $row["cod_pro"];
				$ls_nompro       = $row["nompro"];
				$ls_denuniadm    = $row["denuniadm"];
				$ls_denfuefin    = $row["denfuefin"];
				$ls_estapro      = $row["estapro"];
				$ld_fecregsol    = $io_funciones->uf_convertirfecmostrar($row["fecregsol"]);
				$li_monto        = number_format($row["monto"],2,",",".");
				$li_monbasinm    = number_format($row["monbasinm"],2,",",".");
				$li_montotcar    = number_format($row["montotcar"],2,",",".");
				$ls_estope       = $row["estope"];
				$ls_modsep       = $row["modsep"];
				$ls_codestpro1   = trim($row["codestpro1"]);
				$ls_codestpro2   = trim($row["codestpro2"]);
				$ls_codestpro3   = trim($row["codestpro3"]);
				$ls_codestpro4   = trim($row["codestpro4"]);
				$ls_codestpro5   = trim($row["codestpro5"]);
				$ls_estcla       = trim($row["estcla"]);
				$ls_estatus="";
				switch ($ls_estsol)
				{
					case "R":
						$ls_estatus="REGISTRO";
					break;
						
					case "E":
						if($ls_estapro==0)
						{
							$ls_estatus="EMITIDA";
						}
						else
						{
							$ls_estatus="EMITIDA (APROBADA)";
						}
					break;
						
					case "A":
						$ls_estatus="ANULADA";
					break;
						
					case "C":
						$ls_estatus="CONTABILIZADA";
					break;
						
					case "P":
						$ls_estatus="PROCESADA";
					break;
						
					case "D":
						$ls_estatus="DESPACHADA COMPLETA";
					break;
					
					case "L":
						$ls_estatus="DESPACHADA PARCIAL";
					break;

				}
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td align='left'>".$ls_numsol."</td>";
						print "<td align='left'>".$ls_denuniadm."</td>";
						print "<td align='center'>".$ld_fecregsol."</td>";
						print "<td align='left'>".$ls_nompro."</td>";
						print "<td align='center'>".$ls_estatus."</td>";
						print "<td align='right'>".$li_monto."</td>";
						print "<td align='center'><a href=\"javascript: ue_aceptar('$ls_numsol','$ls_codtipsol','$ls_coduniadm','$ls_codfuefin',".
											"'$ls_estsol','$ls_consol','$ls_tipo_destino','$ls_codpro','$ls_denuniadm',".
											"'$ls_denfuefin','$ls_nompro','$ls_estapro','$ld_fecregsol','$li_monto','$li_monbasinm',".
											"'$li_montotcar','$ls_estatus','$ls_estope','$ls_modsep','$ls_codestpro1','$ls_codestpro2',".
											"'$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_estcla');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
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
	}// end function uf_print_solicitud_presupuestaria
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentas_spg()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentas_spg
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que inprime el resultado de la busqueda de las cuentas presupuestarias
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 06/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_soc;
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		$ls_spgcuenta=$_POST['spgcuenta'];
		$ls_dencue="%".$_POST['dencue']."%";
		$ls_codestpro1ue=$_POST['codestpro1'];
		$ls_codestpro2ue=$_POST['codestpro2'];
		$ls_codestpro3ue=$_POST['codestpro3'];
		$ls_codestpro4ue=$_POST['codestpro4'];
		$ls_codestpro5ue=$_POST['codestpro5']; 
		$ls_estclaue=$_POST['hidestcla'];
		$ls_codunieje=$_POST['codunieje'];
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_estmodparsoc=$_SESSION['la_empresa']['estmodpartsoc'];
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		$ls_logusr = $_SESSION["la_logusr"];
		$ls_gestor = $_SESSION["ls_gestor"];
		$ls_lugar = $_POST["lugar"];
		$ls_criterio="";
	    if ($ls_modalidad=="1")
		{
		    $codespro1=str_pad($ls_codestpro1ue,25,"0",0);
		    $codespro2=str_pad($ls_codestpro2ue,25,"0",0);
		    $codespro3=str_pad($ls_codestpro3ue,25,"0",0);			
            $estcla=$ls_estclaue;
			$ls_scg_cuenta=$_POST['scg_cuenta'];
			if($ls_spgcuenta=="")
			{
			   $ls_spgcuenta=$ls_scg_cuenta;
			} 
			if(($ls_lugar=="1")||($ls_lugar=="3"))
			{ 
				if (strtoupper($ls_gestor) == "MYSQLT")
				{
					$ls_criterio = " AND CONCAT(codestpro1,codestpro2,codestpro3,estcla,spg_cuenta) <> ('$codespro1$codespro2$codespro3$estcla$ls_scg_cuenta')";
				}
				if (strtoupper($ls_gestor) == "POSTGRES")
				{
					$ls_criterio = " AND (codestpro1||codestpro2||codestpro3||estcla||spg_cuenta) <> ('$codespro1$codespro2$codespro3$estcla$ls_scg_cuenta')";
				}
			}
			else
			{
			   if (strtoupper($ls_gestor) == "MYSQLT")
				{
					$ls_criterio = " AND CONCAT(codestpro1,codestpro2,codestpro3,estcla) <> ('$codespro1$codespro2$codespro3$estcla')";
				}
				if (strtoupper($ls_gestor) == "POSTGRES")
				{
					$ls_criterio = " AND (codestpro1||codestpro2||codestpro3||estcla) <> ('$codespro1$codespro2$codespro3$estcla')";
				}
			}
		
		}	
		else
		{
			$codespro1=str_pad($ls_codestpro1ue,25,"0",0);
		    $codespro2=str_pad($ls_codestpro2ue,25,"0",0);
		    $codespro3=str_pad($ls_codestpro3ue,25,"0",0);
			$codespro4=str_pad($ls_codestpro4ue,25,"0",0);
			$codespro5=str_pad($ls_codestpro5ue,25,"0",0);
			$ls_scg_cuenta=$_POST['scg_cuenta']; 
            $estcla=$ls_estclaue;
			if(($ls_lugar=="1")||($ls_lugar=="3"))
			{ 
				if (strtoupper($ls_gestor) == "MYSQLT")
				{
					
					 $ls_criterio = " AND CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla,spg_cuenta) 
											 <> ('$codespro1$codespro2$codespro3$codespro4$codespro5$estcla$ls_scg_cuenta')";
				}
				if (strtoupper($ls_gestor) == "POSTGRES")
				{
					 $ls_criterio = " AND (codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||estcla||spg_cuenta) 
									   <> ('$codespro1$codespro2$codespro3$codespro4$codespro5$estcla$ls_scg_cuenta')";
				}
			}
			else
			{
			   if (strtoupper($ls_gestor) == "MYSQLT")
				{
					
					 $ls_criterio = " AND CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla) 
											 <> ('$codespro1$codespro2$codespro3$codespro4$codespro5$estcla')";
				}
				if (strtoupper($ls_gestor) == "POSTGRES")
				{
					 $ls_criterio = " AND (codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||estcla) 
									   <> ('$codespro1$codespro2$codespro3$codespro4$codespro5$estcla')";
				}
			}
		}

	    ////-----------se refiere a la seguridad----------------------------------------------------------------
		if (strtoupper($ls_gestor) == "MYSQLT")
		{
			 $ls_sql_seguridad = " AND CONCAT('".$ls_codemp."','SPG','".$ls_logusr."',codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla) IN ".
								 "     (SELECT CONCAT(codemp,codsis,codusu,codintper)     ".
								 "        FROM sss_permisos_internos                      ".
								 "       WHERE codusu = '".$ls_logusr."'                  ".
								 "         AND codsis = 'SPG')                            ";
		}
		else
		{
			 $ls_sql_seguridad = " AND '".$ls_codemp."'||'SPG'||'".$ls_logusr."'||codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||estcla IN        ".
			                     "      (SELECT codemp||codsis||codusu||codintper          ".
								 "         FROM sss_permisos_internos                      ".
								 "        WHERE codusu = '".$ls_logusr."'                  ".
								 "          AND codsis = 'SPG')                            ";
		}
		//-------------------------------------------------------------------------------------------------- 

		if($ls_campoorden=="codpro")
		{
			$ls_campoorden= "codestpro1,codestpro2,codestpro3,codestpro4,codestpro5";
		}
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$ls_titulo="Estructura Presupuestaria ";
				break;
				
			case "2": // Modalidad por Presupuesto
				$ls_titulo="Estructura Programática ";
				break;
		}
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td style='cursor:pointer' title='Ordenar por Programatica' align='center' onClick=ue_orden('codpro')>".$ls_titulo."</td>";
		print "<td style='cursor:pointer' title='Ordenar por Cuenta'       align='center' onClick=ue_orden('spg_cuenta')>Cuenta</td>";
		print "<td style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('denominacion')>Denominacion</td>";
		print "<td style='cursor:pointer' title='Ordenar por Disponible'   align='center' onClick=ue_orden('disponible')>Disponible</td>";
		print "<td></td>";
		print "</tr>";
		$ls_cuentas="";
		$ls_tipocuenta="";
		
    	if($ls_tipo=="B") // si es de bienes
		{
		   $ls_campo_buscar="soc_gastos";
		}
		elseif($ls_tipo=="S") // si es de Servicios
		{
			$ls_campo_buscar="soc_servic";
		}
		$ls_sql=" SELECT ".$ls_campo_buscar." AS cuenta ".
				" FROM   sigesp_empresa ".
				" WHERE  codemp = '".$ls_codemp."' ";

		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_data))
			{
				$ls_cuentas=$row["cuenta"];
			}			
			$la_spg_cuenta=split(",",$ls_cuentas);
			$li_total=count($la_spg_cuenta);
			if(($ls_lugar=="1")||($ls_lugar=="3"))
			{
				for($li_i=0;$li_i<$li_total;$li_i++)
				{
					if($la_spg_cuenta[$li_i]!="")
					{		
						if($li_i==0)
						{
							$ls_tipocuenta=$ls_tipocuenta." SUBSTR(TRIM(spg_cuenta),1,3) = '".trim($la_spg_cuenta[$li_i])."' ";
						}
						else
						{
							$ls_tipocuenta=$ls_tipocuenta."    OR SUBSTR(TRIM(spg_cuenta),1,3) = '".trim($la_spg_cuenta[$li_i])."'";
						}
					}
				}
			}
    	}
		if($ls_tipocuenta=="")
		{
			$ls_tipocuenta=" spg_cuenta like '%%' ";
		}
		
		/*$ls_sql="SELECT TRIM(spg_cuenta) AS spg_cuenta , denominacion, codestpro1,codestpro2, codestpro3,codestpro4,codestpro5,estcla,status, ".
				"       (asignado-(comprometido+precomprometido)+aumento-disminucion) as disponible ".
				"  FROM spg_cuentas ".
				" WHERE codemp = '".$ls_codemp."'  ".
				"   AND (".$ls_tipocuenta.")".
				"	AND spg_cuenta like '".$ls_spgcuenta."%' ".
				"   AND denominacion like '".$ls_dencue."' ".
				"   AND status ='C'  ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden;*/
		
		$ls_sql="SELECT TRIM(spg_cuenta) AS spg_cuenta , denominacion, codestpro1,codestpro2, codestpro3,codestpro4,codestpro5,estcla,status, ".
				"       (asignado-(comprometido+precomprometido)+aumento-disminucion) as disponible ".
				"  FROM spg_cuentas ".
				" WHERE codemp = '".$ls_codemp."'  ".
				"   AND (".$ls_tipocuenta.")".
				"	AND spg_cuenta like '".$ls_spgcuenta."%' ".
				"   AND denominacion like '".$ls_dencue."' ".
				"   AND status ='C'  ".$ls_criterio. $ls_sql_seguridad.
				" ORDER BY ".$ls_campoorden." ".$ls_orden; //print $ls_sql;

		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			$li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
			$li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
			$li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
			$li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
			$li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
			while(!$rs_data->EOF)
			{
				$ls_estcla     = trim($rs_data->fields["estcla"]);
				$ls_spgcta     = trim($rs_data->fields["spg_cuenta"]);
				$ls_denctaspg  = $rs_data->fields["denominacion"];
				$ls_codestpro1 = trim($rs_data->fields["codestpro1"]);
				$ls_codestpro2 = trim($rs_data->fields["codestpro2"]);
				$ls_codestpro3 = trim($rs_data->fields["codestpro3"]);
				$ls_codestpro4 = trim($rs_data->fields["codestpro4"]);
				$ls_codestpro5 = trim($rs_data->fields["codestpro5"]);
				$ls_codestpro  = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
				$ld_mondiscta  = number_format($rs_data->fields["disponible"],2,'.','');
				$ld_disponible = number_format($rs_data->fields["disponible"],2,",",".");
				if(($ls_codestpro1==$ls_codestpro1ue)&&($ls_codestpro2==$ls_codestpro2ue)&&($ls_codestpro3==$ls_codestpro3ue)&&($ls_codestpro4==$ls_codestpro4ue)&&($ls_codestpro5==$ls_codestpro5ue)&&($ls_estcla==$ls_estclaue))
				{
					$ls_estilo = "celdas-azules";
				}
				else
				{
					$ls_estilo = "celdas-blancas";
				}
				$ls_codestpro1   = substr($ls_codestpro1,-$li_loncodestpro1);
				$ls_codestpro2   = substr($ls_codestpro2,-$li_loncodestpro2);
				$ls_codestpro3   = substr($ls_codestpro3,-$li_loncodestpro3);
				$ls_programatica = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
				if ($ls_modalidad==2)
				   {
				     $ls_codestpro4   = substr($ls_codestpro4,-$li_loncodestpro4);
				     $ls_codestpro5   = substr($ls_codestpro5,-$li_loncodestpro5);
				     $ls_programatica = $ls_programatica.'-'.$ls_codestpro4.'-'.$ls_codestpro5;  
				   }
				echo "<tr class=".$ls_estilo.">";
				echo "<td align='center'>".$ls_programatica."</td>";
				echo "<td align='center'>".$ls_spgcta."</td>";
				echo "<td align='left' title='".ltrim($ls_denctaspg)."'>".$ls_denctaspg."</td>";
				echo "<td align='right'>".$ld_disponible."</td>";
				echo "<td style='cursor:pointer'>";
				echo "<a href=\"javascript: ue_aceptar('".$ls_programatica."','".$ls_spgcta."','".$ls_denctaspg."','".$ls_codestpro."','".$ls_estcla."','".$ld_mondiscta."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
				echo "</tr>";
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
	}// end function uf_print_cuentas_spg
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentas_cargos()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentas_cargos
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que inprime el resultado de la busqueda de las cuentas presupuestarias de los cargos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 20/03/2007								Fecha Última Modificación : 06/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_soc,$li_estmodest,$li_loncodestpro1,$li_loncodestpro2,$li_loncodestpro3,$li_loncodestpro4,$li_loncodestpro5;
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$ls_codestpro1 = $_POST['codestpro1'];
		$ls_codestpro2 = $_POST['codestpro2'];
		$ls_codestpro3 = $_POST['codestpro3'];
		$ls_codestpro4 = $_POST['codestpro4'];
		$ls_codestpro5 = $_POST['codestpro5'];
		$ls_estcla     = $_POST['hidestcla'];
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		if($ls_campoorden=="codpro")
		{
			$ls_campoorden= "spg_cuentas.codestpro1, spg_cuentas.codestpro2, spg_cuentas.codestpro3, spg_cuentas.codestpro4, spg_cuentas.codestpro5 ";
		}
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		if ($li_estmodest==1)
		   {
		     $ls_titulo="Estructura Presupuestaria";
		   }
		else
		   {
		     $ls_titulo="Estructura Programática ";
		   }
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td style='cursor:pointer' title='Ordenar por Programatica' align='center' onClick=ue_orden('spg_cuentas.codpro')>".$ls_titulo."</td>";
		print "<td style='cursor:pointer' title='Ordenar por Cuenta'       align='center' onClick=ue_orden('spg_cuentas.spg_cuenta')>Cuenta</td>";
		print "<td style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('spg_cuentas.denominacion')>Denominacion</td>";
		print "<td style='cursor:pointer' title='Ordenar por Disponible'   align='center' onClick=ue_orden('disponible')>Disponible</td>";
		print "<td></td>";
		print "</tr>";
		$ls_sql="SELECT TRIM(spg_cuentas.spg_cuenta) AS spg_cuenta , MAX(spg_cuentas.denominacion) AS denominacion, spg_cuentas.codestpro1, ".
			    "       spg_cuentas.codestpro2, spg_cuentas.codestpro3, spg_cuentas.codestpro4, spg_cuentas.codestpro5, spg_cuentas.estcla, MAX(status) AS status, ".
				"       (MAX(spg_cuentas.asignado)-(MAX(spg_cuentas.comprometido)+MAX(spg_cuentas.precomprometido))+MAX(spg_cuentas.aumento)-MAX(spg_cuentas.disminucion)) as disponible ".
			    "  FROM spg_cuentas, sigesp_cargos ".
				" WHERE spg_cuentas.codemp = '".$ls_codemp."'  ".
				"   AND spg_cuentas.status ='C'  ".
				"	AND spg_cuentas.codemp = sigesp_cargos.codemp ".
				"   AND spg_cuentas.codestpro1 = substr(sigesp_cargos.codestpro,1,25) ".
				"   AND spg_cuentas.codestpro2 = substr(sigesp_cargos.codestpro,26,25) ".
				"   AND spg_cuentas.codestpro3 = substr(sigesp_cargos.codestpro,51,25) ".
				"   AND spg_cuentas.codestpro4 = substr(sigesp_cargos.codestpro,76,25) ".
				"   AND spg_cuentas.codestpro5 = substr(sigesp_cargos.codestpro,101,25) ".
				"   AND spg_cuentas.spg_cuenta = sigesp_cargos.spg_cuenta ".
				" GROUP BY spg_cuentas.codestpro1, spg_cuentas.codestpro2, spg_cuentas.codestpro3, spg_cuentas.codestpro4, ".
				"       spg_cuentas.codestpro5, spg_cuentas.estcla, spg_cuentas.spg_cuenta  ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden;
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_estclacta    = $row["estcla"];
				$ls_spg_cuenta   = trim($row["spg_cuenta"]);
				$ls_denominacion = $row["denominacion"];
				$ls_codestpre1   = trim($row["codestpro1"]);
				$ls_codestpre2   = trim($row["codestpro2"]);
				$ls_codestpre3   = trim($row["codestpro3"]);
				$ls_codestpre4   = trim($row["codestpro4"]);
				$ls_codestpre5   = trim($row["codestpro5"]);
				$ls_codestpro    = $ls_codestpre1.$ls_codestpre2.$ls_codestpre3.$ls_codestpre4.$ls_codestpre5;
				$ld_mondiscta    = $row["disponible"];
				$li_disponible   = number_format($ld_mondiscta,2,",",".");
				if(($ls_codestpro==$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5) && ($ls_estcla==$ls_estclacta))
				{
					$ls_estilo = "celdas-azules";
				}
				else
				{
					$ls_estilo = "celdas-blancas";
				}
                $ls_codestpre1 = substr($ls_codestpre1,-$_SESSION["la_empresa"]["loncodestpro1"]);
				$ls_codestpre2 = substr($ls_codestpre2,-$_SESSION["la_empresa"]["loncodestpro2"]);
				$ls_codestpre3 = substr($ls_codestpre3,-$_SESSION["la_empresa"]["loncodestpro3"]);
				$ls_codestpre  = $ls_codestpre1.'-'.$ls_codestpre2.'-'.$ls_codestpre3;
				if ($li_estmodest==2)
				   {
				     $ls_codestpre4 = substr($ls_codestpre4,-$_SESSION["la_empresa"]["loncodestpro4"]);
				     $ls_codestpre5 = substr($ls_codestpre5,-$_SESSION["la_empresa"]["loncodestpro5"]);
				     $ls_codestpre  = $ls_codestpre.'-'.$ls_codestpre4.'-'.$ls_codestpre5;
				   } 
				print "<tr class=".$ls_estilo.">";
				print "<td align='center'>".$ls_codestpre."</td>";
				print "<td align='center'>".$ls_spg_cuenta."</td>";
				print "<td align='left'>".$ls_denominacion."</td>";
				print "<td align='right'>".$li_disponible."</td>";
				print "<td style='cursor:pointer'>";
				print "<a href=\"javascript: ue_aceptar('".$ls_codestpre."','".$ls_spg_cuenta."','".$ls_denominacion."','".$ls_codestpro."','".$ls_estcla."','".$ld_mondiscta."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
				print "</tr>";			
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
	}// end function uf_print_cuentas_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cargos()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cargos
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de fuente de financiamiento
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 
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
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60  style='cursor:pointer' title='Ordenar por Codigo'       align='center' onClick=ue_orden('codcar')>Codigo</td>";
		print "<td width=200 style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('dencar')>Denominacion</td>";
		print "<td width=140 style='cursor:pointer' title='Ordenar por Codigo Programatico' align='center' onClick=ue_orden('codestpro')>Programatica</td>";
		print "<td width=100 style='cursor:pointer' title='Ordenar por Cuenta' align='center' onClick=ue_orden('spg_cuenta')>Cuenta</td>";
		print "</tr>";
		$ls_sql=" SELECT codcar,dencar,codestpro,spg_cuenta,porcar,estcla ".
				" FROM sigesp_cargos ".	
				" ORDER BY ".$ls_campoorden." ".$ls_orden;
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codcar     = trim($row["codcar"]);
				$ls_estcla     = trim($row["estcla"]);
				$ls_dencar     = $row["dencar"];
				$ls_codestpro  = trim($row["codestpro"]);
				$ls_codestpro1 = substr($ls_codestpro,0,25);
				$ls_codestpro2 = substr($ls_codestpro,25,25);
				$ls_codestpro3 = substr($ls_codestpro,50,25);
				$ls_codestpro4 = substr($ls_codestpro,75,25);
				$ls_codestpro5 = substr($ls_codestpro,100,25); 
				$ls_codestpro1 = substr($ls_codestpro1,-$_SESSION["la_empresa"]["loncodestpro1"]);
				$ls_codestpro2 = substr($ls_codestpro2,-$_SESSION["la_empresa"]["loncodestpro2"]);
				$ls_codestpro3 = substr($ls_codestpro3,-$_SESSION["la_empresa"]["loncodestpro3"]);
				$ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
				if ($_SESSION["la_empresa"]["estmodest"]==2)
				   {
				     $ls_codestpro4 = substr($ls_codestpro4,-$_SESSION["la_empresa"]["loncodestpro4"]);
				     $ls_codestpro5 = substr($ls_codestpro5,-$_SESSION["la_empresa"]["loncodestpro5"]);
					 $ls_codestpro  = $ls_codestpro.'-'.$ls_codestpro4.'-'.$ls_codestpro5;
				   }
				$ls_spg_cuenta = trim($row["spg_cuenta"]);
				$li_porcar     = $row["porcar"];
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: aceptar('$ls_codcar','$ls_dencar');\">".$ls_codcar."</a></td>";
						print "<td align='left'>".$ls_dencar."</td>";
						print "<td align='left'>".$ls_codestpro."</td>";
						print "<td align='left'>".$ls_spg_cuenta."</td>";
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
	}// end function uf_print_moneda
	//-----------------------------------------------------------------------------------------------------------------------------------
?>