<?php
	//-----------------------------------------------------------------------------------------------------------------------------------
	// Clase donde se cargan todos los catálogos del sistema SEP con la utilización del AJAX
	//-----------------------------------------------------------------------------------------------------------------------------------
    session_start();   
	require_once("class_funciones_sep.php");
	$io_funciones_sep=new class_funciones_sep();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones  = new class_funciones();
	$ls_codestpro1 = $io_funciones_sep->uf_obtenervalor("codestpro1","");
	$ls_codestpro2 = $io_funciones_sep->uf_obtenervalor("codestpro2","");
	$ls_codestpro3 = $io_funciones_sep->uf_obtenervalor("codestpro3","");
	$ls_codestpro4 = $io_funciones_sep->uf_obtenervalor("codestpro4","");
	$ls_codestpro5 = $io_funciones_sep->uf_obtenervalor("codestpro5","");
	$ls_estcla     = $io_funciones_sep->uf_obtenervalor("estcla",""); 
	$ruta = '../../';
	require_once("../../shared/class_folder/sigesp_conexiones.php");
    $io_conexiones=new conexiones();
	$io_conexiones->decodificar_post();
	
	// Tipo del catalogo que se requiere pintar
	$ls_catalogo=$io_funciones_sep->uf_obtenervalor("catalogo",""); 
	$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
	$li_longestpro1= (25-$ls_loncodestpro1)+1;
	$ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
	$li_longestpro2= (25-$ls_loncodestpro2)+1;
	$ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
	$li_longestpro3= (25-$ls_loncodestpro3)+1;
	$ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
	$li_longestpro4= (25-$ls_loncodestpro4)+1;
	$ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
	$li_longestpro5= (25-$ls_loncodestpro5)+1;
	
	switch($ls_catalogo)
	{
		case "BIENES":
			uf_print_bienes();
			break;
		case "UNIDADEJECUTORA":
			uf_print_unidad_ejecutora();
			break;
		case "FUENTEFINANCIAMIENTO":
			uf_print_fuentefinanciamiento($ls_codestpro1, $ls_codestpro2, $ls_codestpro3, $ls_codestpro4, $ls_codestpro5, $ls_estcla);
			break;
		case "PROVEEDOR":
			uf_print_proveedor();
			break;
		case "BENEFICIARIO":
			uf_print_beneficiario();
			break;
		case "CUENTASSPG":
			uf_print_cuentasspg();
			break;
		case "CUENTASCARGOS":
			uf_print_cuentas_cargos();
			break;
		case "SOLICITUD":
			uf_print_solicitud();
			break;
		case "SERVICIOS":
			uf_print_servicios();
			break;
		case "CONCEPTOS":
			uf_print_conceptos();
			break;
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_bienes()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_bienes
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que obtiene e imprime el resultado de la busqueda de los bienes
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sep;
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        $ls_codemp     = $_SESSION['la_empresa']['codemp'];
		$ls_parsindis  = $_SESSION["la_empresa"]["estparsindis"];
		$ls_codart     = "%".$_POST['codart']."%";
		$ls_denart     = "%".$_POST['denart']."%";
		$ls_codtipart  = "%".$_POST['codtipart']."%";
		$ls_codestpro1 = $_POST['codestpro1'];
		$ls_codestp1   = $io_funciones->uf_cerosizquierda($ls_codestpro1,25);
		$ls_codestpro2 = $_POST['codestpro2'];
		$ls_codestp2   = $io_funciones->uf_cerosizquierda($ls_codestpro2,25);
		$ls_codestpro3 = $_POST['codestpro3'];
		$ls_codestp3   = $io_funciones->uf_cerosizquierda($ls_codestpro3,25);
		$ls_codestpro4 = $_POST['codestpro4'];
		$ls_codestp4   = $io_funciones->uf_cerosizquierda($ls_codestpro4,25);
		$ls_codestpro5 = $_POST['codestpro5'];
		$ls_codestp5   = $io_funciones->uf_cerosizquierda($ls_codestpro5,25);
		$ls_orden      = $_POST['orden'];
		$ls_estcla     = $_POST['estcla'];
		$ls_campoorden = $_POST['campoorden'];
		$ls_tipsepbie  = '-';		
		$ls_sqlaux     = "";
		if (array_key_exists("tipsepbie",$_POST)) 
		   {
			 $ls_tipsepbie = $_POST['tipsepbie'];
			 if ($ls_tipsepbie=='M')
			    {
				  $ls_sqlaux = " AND siv_articulo.estact='0'";
				}
		     elseif($ls_tipsepbie=='A')
			    {
				  $ls_sqlaux = " AND siv_articulo.estact='1'";
				}
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
							      $ls_sqlaux = $ls_sqlaux.")";
								}						   
						   }
		               $ls_straux = "";
					   if ($ls_parsindis==1)
						  {
			                $ls_straux = ",(SELECT (spg_cuentas.asignado-(spg_cuentas.comprometido+spg_cuentas.precomprometido)+spg_cuentas.aumento-spg_cuentas.disminucion)
											  FROM spg_cuentas
											 WHERE spg_cuentas.codestpro1 = '".$ls_codestp1."'
											   AND spg_cuentas.codestpro2 = '".$ls_codestp2."'
											   AND spg_cuentas.codestpro3 = '".$ls_codestp3."'
											   AND spg_cuentas.codestpro4 ='".$ls_codestp4."'
											   AND spg_cuentas.codestpro5 = '".$ls_codestp5."'
											   AND spg_cuentas.estcla='".$ls_estcla."'
						    				   AND spg_cuentas.codemp=siv_articulo.codemp
											   AND spg_cuentas.spg_cuenta = siv_articulo.spg_cuenta) AS disponibilidad";
						  }
					   $ls_sql = "SELECT siv_articulo.codart,siv_articulo.denart,siv_articulo.ultcosart,
										 siv_articulo.codunimed,TRIM(siv_articulo.spg_cuenta) AS spg_cuenta,
										 siv_unidadmedida.denunimed, siv_unidadmedida.unidad,
										 (SELECT COUNT(spg_cuentas.spg_cuenta) 
										    FROM spg_cuentas
							  			   WHERE spg_cuentas.codestpro1 = '".$ls_codestp1."'
											 AND spg_cuentas.codestpro2 = '".$ls_codestp2."'
											 AND spg_cuentas.codestpro3 = '".$ls_codestp3."'
											 AND spg_cuentas.codestpro4 = '".$ls_codestp4."'
											 AND spg_cuentas.codestpro5 = '".$ls_codestp5."'
					           				 AND spg_cuentas.estcla = '".$ls_estcla."'
											 AND siv_articulo.codemp = spg_cuentas.codemp
											 AND siv_articulo.spg_cuenta = spg_cuentas.spg_cuenta) AS existecuenta,
					 					 (SELECT COUNT(siv_cargosarticulo.codart)
					   	   					FROM sigesp_cargos, siv_cargosarticulo
					  		  			   WHERE siv_cargosarticulo.codemp = siv_articulo.codemp
						  					 AND siv_cargosarticulo.codart = siv_articulo.codart
					    					 AND sigesp_cargos.codemp = siv_cargosarticulo.codemp
					    					 AND sigesp_cargos.codcar = siv_cargosarticulo.codcar) AS totalcargos $ls_straux
					  				FROM siv_articulo, siv_unidadmedida
								   WHERE siv_articulo.codemp='".$ls_codemp."'
									 AND siv_articulo.codart like '".$ls_codart."'
									 AND siv_articulo.denart like '".$ls_denart."'
									 AND siv_articulo.codtipart like '".$ls_codtipart."' $ls_sqlaux									 
									 AND siv_articulo.codunimed = siv_unidadmedida.codunimed 
								   ORDER BY $ls_campoorden $ls_orden"; 
					   $rs_data=$io_sql->select($ls_sql);
					   if ($rs_data===false)
						  {
						    $io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
						  }
					   else
						  {
						    echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
						    echo "<tr class=titulo-celda>";
						    echo "<td style='cursor:pointer' title='Ordenar por Codigo'       align='center' onClick=ue_orden('siv_articulo.codart')>Codigo</td>";
						    echo "<td style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('siv_articulo.denart')>Denominacion</td>";
						    echo "<td style='cursor:pointer' title='Ordenar por Unidad'       align='center' onClick=ue_orden('siv_unidadmedida.denunimed')>Unidad</td>";
						    echo "<td style='cursor:pointer' title='Ordenar por Cuenta'       align='center' onClick=ue_orden('siv_articulo.spg_cuenta')>Cuenta</td>";
						    echo "<td></td>";
						    echo "</tr>";
						    while(!$rs_data->EOF)
							     {
								   $ls_codart       = $rs_data->fields["codart"];
								   $ls_denart       = $rs_data->fields["denart"];
								   $li_ultcosart    = number_format($rs_data->fields["ultcosart"],2,",",".");
								   $ls_codunimed    = $rs_data->fields["codunimed"];
								   $ls_denunimed    = $rs_data->fields["denunimed"];
								   $li_unidad       = $rs_data->fields["unidad"];
								   $li_totalcargos  = $rs_data->fields["totalcargos"];
								   $ls_spg_cuenta   = $rs_data->fields["spg_cuenta"];
								   $li_existecuenta = $rs_data->fields["existecuenta"];
								   if ($li_existecuenta==0)
									  {
									    $ls_estilo = "celdas-blancas";
									  }
								   else
									  {
									    $ls_estilo = "celdas-azules";
									  }
								   echo "<tr class=".$ls_estilo.">";
								   echo "<td align='center'>".$ls_codart."</td>";
								   echo "<td align='left'>".$ls_denart."</td>";
								   echo "<td align='left'>".$ls_denunimed."</td>";
								   echo "<td align='center'>".$ls_spg_cuenta."</td>";
								   echo "<td style='cursor:pointer'>";
								   if ($ls_parsindis==0)
									  {
									    echo "<a href=\"javascript: ue_aceptar('".$ls_codart."','".$ls_denart."','".$li_unidad."',
																		   '".$ls_spg_cuenta."','".$li_ultcosart."','".$li_totalcargos."',
																		   '".$li_existecuenta."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
									  }
								   else
									  {
									    $li_disponibilidad=$rs_data->fields["disponibilidad"];
									    if ($li_disponibilidad >0)
										   {
										     echo "<a href=\"javascript: ue_aceptar('".$ls_codart."','".$ls_denart."','".$li_unidad."','".$ls_spg_cuenta."',".
											                "'".$li_ultcosart."','".$li_totalcargos."','".$li_existecuenta."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
										   }
									    else
										   {
										     echo "<a href=\"javascript: ue_mensaje();\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
										   }
									  } 
								   echo  "</tr>";
								   $rs_data->MoveNext();
							     }
						    $io_sql->free_result($rs_data);
						    echo "</table>";
						  }
		             }
		        }
		   }
		unset($io_include,$io_conexion,$io_sql,$io_mensajes,$io_funciones,$ls_codemp,$ls_parsindis);
	}// end function uf_print_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_unidadejecutora()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de la unidad ejecutora (Unidad administrativa)
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 26/08/2008
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
		$ls_parsindis=$_SESSION["la_empresa"]["estparsindis"];
		$ls_coduniadm="%".$_POST["coduniadm"]."%";
		$ls_denuniadm="%".$_POST["denuniadm"]."%";
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60  style='cursor:pointer' title='Ordenar por Codigo'       align='center' onClick=ue_orden('coduniadm')>Codigo</td>";
		print "<td width=440 style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('denuniadm')>Denominacion</td>";
		print "</tr>";
		
		$ls_logusr = $_SESSION["la_logusr"];
		$ls_gestor = $_SESSION["ls_gestor"];
		$ls_sql_seguridad = "";
		if (strtoupper($ls_gestor) == "MYSQLT")
		{
		 $ls_sql_seguridad = " AND CONCAT('".$ls_codemp."','SEP','".$ls_logusr."',coduniadm) IN (SELECT CONCAT(codemp,codsis,codusu,codintper) 
		                       FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SEP')";
		}
		else
		{
		 $ls_sql_seguridad = " AND '".$ls_codemp."'||'SEP'||'".$ls_logusr."'||coduniadm IN (SELECT codemp||codsis||codusu||codintper
		                       FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SEP')";
		}
		
		$ls_sql="SELECT coduniadm, denuniadm ".
				"  FROM spg_unidadadministrativa ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND coduniadm <>'----------' ".
				"   AND coduniadm like '".$ls_coduniadm."' ".
				"   AND denuniadm like '".$ls_denuniadm."' ".$ls_sql_seguridad." ".
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
				$ls_coduniadm=$row["coduniadm"];
				$ls_denuniadm=$row["denuniadm"];
				
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: aceptar('$ls_coduniadm','$ls_denuniadm');\">".$ls_coduniadm."</a></td>";
						print "<td align='left'>".$ls_denuniadm."</td>";
						print "</tr>";			
						break;
					case "APROBACION":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar_aprobacion('$ls_coduniadm','$ls_denuniadm');\">".$ls_coduniadm."</a></td>";
						print "<td>".$ls_denuniadm."</td>";
						print "</tr>";			
						break;

					case "REPDES":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar_reportedesde('$ls_coduniadm');\">".$ls_coduniadm."</a></td>";
						print "<td>".$ls_denuniadm."</td>";
						print "</tr>";			
						break;

					case "REPHAS":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar_reportehasta('$ls_coduniadm');\">".$ls_coduniadm."</a></td>";
						print "<td>".$ls_denuniadm."</td>";
						print "</tr>";			
						break;
						
					case "BUSCAR_CAT_SEP":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar_catalogo_sep('$ls_coduniadm','$ls_denuniadm');\">".$ls_coduniadm."</a></td>";
						print "<td>".$ls_denuniadm."</td>";
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
	}// end function uf_print_unidadejecutora
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_fuentefinanciamiento($ls_codestpro1, $ls_codestpro2, $ls_codestpro3, $ls_codestpro4, $ls_codestpro5, $ls_estcla)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
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
	function uf_print_proveedor()
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de proveedores
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sep;
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
		$ls_tipo=$_POST['tipo'];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td style='cursor:pointer' title='Ordenar por Codigo' align='center' onClick=ue_orden('cod_pro')>Codigo</td>";
		print "<td style='cursor:pointer' title='Ordenar por Nombre' align='center' onClick=ue_orden('nompro')>Nombre</td>";
		print "</tr>";
        $ls_sql="SELECT cod_pro,nompro,sc_cuenta,rifpro,tipconpro".
				"  FROM rpc_proveedor  ".
                " WHERE codemp = '".$ls_codemp."' ".
				"   AND cod_pro <> '----------' ".
				"   AND estprov = 0 ".
				"   AND cod_pro like '".$ls_codpro."' ".
				"   AND nompro like '".$ls_nompro."' ".
				"   AND dirpro like '".$ls_dirpro."' ". 
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
				$ls_codpro=$row["cod_pro"];
				$ls_nompro=$row["nompro"];
				$ls_sccuenta=$row["sc_cuenta"];
				$ls_rifpro=$row["rifpro"];
				$ls_tipconpro=$row["tipconpro"];
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar('$ls_codpro','$ls_nompro','$ls_rifpro','$ls_tipconpro');\">".$ls_codpro."</a></td>";
						print "<td>".$ls_nompro."</td>";
						print "</tr>";
					break;
					case "REPDES":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar_reportedesde('$ls_codpro');\">".$ls_codpro."</a></td>";
						print "<td>".$ls_nompro."</td>";
						print "</tr>";
					break;
					case "REPHAS":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar_reportehasta('$ls_codpro');\">".$ls_codpro."</a></td>";
						print "<td>".$ls_nompro."</td>";
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
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sep;
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
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td style='cursor:pointer' title='Ordenar por Cedula' align='center' onClick=ue_orden('ced_bene')>C&eacute;dula</td>";
		print "<td style='cursor:pointer' title='Ordenar por Nombre' align='center' onClick=ue_orden('nombene')>Nombre</td>";
		print "</tr>";
		$ls_sql="SELECT TRIM(ced_bene) as ced_bene, nombene, apebene, rifben ".
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
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_cedbene=$row["ced_bene"];
				$ls_nombene=$row["nombene"]." ".$row["apebene"];
				$ls_rifben=$row["rifben"];
				echo "<tr class=celdas-blancas>";
				switch ($ls_tipo)
				{
					case "":
						echo "<td style=text-align:center><a href=\"javascript: aceptar('$ls_cedbene','$ls_nombene','$ls_rifben');\">".$ls_cedbene."</a></td>";
					break;
					case "REPDES":
						echo "<td style=text-align:center><a href=\"javascript: aceptar_reportedesde('$ls_cedbene');\">".$ls_cedbene."</a></td>";
					break;
					case "REPHAS":
						echo "<td style=text-align:center><a href=\"javascript: aceptar_reportehasta('$ls_cedbene');\">".$ls_cedbene."</a></td>";
					break;
					case "CMPRET":
						echo "<td style=text-align:center><a href=\"javascript: aceptar_cmpretencion('$ls_cedbene');\">".$ls_cedbene."</a></td>";
					break;
				}					
				echo "<td style=text-align:left title='".$ls_nombene."'>".$ls_nombene."</td>";
				echo "</tr>";
			}
			$io_sql->free_result($rs_data);
		}
		echo "</table>";
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentasspg()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentasspg
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que inprime el resultado de la busqueda de las cuentas presupuestarias
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sep;
		global $ls_loncodestpro1,$li_longestpro1,$ls_loncodestpro2,$li_longestpro2,$ls_loncodestpro3,$li_longestpro3;
		global $ls_loncodestpro4,$li_longestpro4,$ls_loncodestpro5,$li_longestpro5;
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$li_estmodest=$_POST["estmodest"]; 
		$ls_spgcuenta=$_POST['spgcuenta']; 
		$ls_dencue="%".$_POST['dencue']."%";
		$ls_codestpro1=$_POST['codestpro1'];
		$ls_codestpro2=$_POST['codestpro2'];
		$ls_codestpro3=$_POST['codestpro3'];
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_codestpro4=$_POST['codestpro4'];
		$ls_codestpro5=$_POST['codestpro5'];
		$ls_estclap=$_POST['estcla'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		$ls_logusr = $_SESSION["la_logusr"];
		$ls_gestor = $_SESSION["ls_gestor"];
		$ls_criterio="";
		if ($li_estmodest=="1")
		{
		    $codespro1=str_pad($ls_codestpro1,25,"0",0);
		    $codespro2=str_pad($ls_codestpro2,25,"0",0);
		    $codespro3=str_pad($ls_codestpro3,25,"0",0);			
            $estcla=$ls_estclap;
			$ls_scg_cuenta=$_POST['scg_cuenta']; 
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
			$codespro1=str_pad($ls_codestpro1,25,"0",0);
		    $codespro2=str_pad($ls_codestpro2,25,"0",0);
		    $codespro3=str_pad($ls_codestpro3,25,"0",0);
			$codespro4=str_pad($ls_codestpro4,25,"0",0);
			$codespro5=str_pad($ls_codestpro5,25,"0",0);
			$ls_scg_cuenta=$_POST['scg_cuenta']; 
            $estcla=$ls_estclap;
		    if (strtoupper($ls_gestor) == "MYSQLT")
			{
				
				 $ls_criterio = " AND CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla,spg_cuenta) 
				                         <> ('$codespro1$codespro2$codespro3$codespro4$codespro5$estcla$ls_scg_cuenta')";
			}
			if (strtoupper($ls_gestor) == "POSTGRES")
			{
				 $ls_criterio = " AND (codestpro1||codestpro2||codestpro3||codestpro34||codestpro5||estcla||spg_cuenta) 
				                   <> ('$codespro1$codespro2$codespro3$codespro4$codespro5$estcla$ls_scg_cuenta')";
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
				$li_len1=20;
				$li_len2=6;
				$li_len3=3;
				$li_len4=2;
				$li_len5=2;
				break;
				
			case "2": // Modalidad por Presupuesto
				$ls_titulo="Estructura Programática ";
				$li_len1=2;
				$li_len2=2;
				$li_len3=2;
				$li_len4=2;
				$li_len5=2;
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
		switch($ls_tipo)
		{
			case "B": // si es de bienes
				$ls_sql="SELECT soc_gastos AS cuenta ".
						"  FROM sigesp_empresa ".
						" WHERE codemp = '".$ls_codemp."' ";
				break;
			case "S": // si es de Servicios
				$ls_sql="SELECT soc_servic AS cuenta ".
						"  FROM sigesp_empresa ".
						" WHERE codemp = '".$ls_codemp."' ";
				break;
		}
	/*	if($ls_tipo!="O")
		{
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
		}*/
		if($ls_tipocuenta=="")
		{
			$ls_tipocuenta=" spg_cuenta like '%%' ";
		}
		$ls_sql="SELECT TRIM(spg_cuenta) AS spg_cuenta , denominacion, codestpro1,codestpro2, codestpro3,codestpro4,codestpro5,status,estcla, ".
				"       (asignado-(comprometido+precomprometido)+aumento-disminucion) as disponible ".
			    "  FROM spg_cuentas ".
				" WHERE codemp = '".$ls_codemp."'  ".
				"   AND (".$ls_tipocuenta.")".
				"	AND spg_cuenta like '".$ls_spgcuenta."%' ".
				"   AND denominacion like '".$ls_dencue."' ".								
				"   AND status ='C'  ".$ls_criterio. $ls_sql_seguridad.
				" ORDER BY ".$ls_campoorden." ".$ls_orden." ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_spg_cuenta=$row["spg_cuenta"];
				$ls_denominacion=$row["denominacion"];
				$ls_codpro1=$row["codestpro1"];
				$ls_codest1=substr($ls_codpro1,$li_longestpro1-1,$ls_loncodestpro1);
				$ls_codpro2=$row["codestpro2"];
				$ls_codest2=substr($ls_codpro2,$li_longestpro2-1,$ls_loncodestpro2);
				$ls_codpro3=$row["codestpro3"];
				$ls_codest3=substr($ls_codpro3,$li_longestpro3-1,$ls_loncodestpro3);
				$ls_codpro4=$row["codestpro4"];
				$ls_codest4=substr($ls_codpro4,$li_longestpro4-1,$ls_loncodestpro4);
				$ls_codpro5=$row["codestpro5"];
				$ls_codest5=substr($ls_codpro5,$li_longestpro5-1,$ls_loncodestpro5);
				$ls_codestpro=$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5.'00';
				$li_disponible=number_format($row["disponible"],2,",",".");
				$ls_estcla=$row["estcla"];
				if(($ls_codestpro==$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5) && ($ls_estcla==$ls_estclap))
				{
					$ls_estilo = "celdas-azules";
				}
				else
				{
					$ls_estilo = "celdas-blancas";
				}				
				$ls_programatica=$ls_codpro1.$ls_codpro2.$ls_codpro3.$ls_codpro4.$ls_codpro5;
				print "<tr class=".$ls_estilo.">";
				print "<td align='center'>".$ls_codestpro."</td>";
				print "<td align='center'>".$ls_spg_cuenta."</td>";
				print "<td align='left'>".$ls_denominacion."</td>";
				print "<td align='right'>".$li_disponible."</td>";
				print "<td style='cursor:pointer'>";
				print "<a href=\"javascript: ue_aceptar('".$ls_programatica."','".$ls_spg_cuenta."','".$ls_denominacion."','".$ls_programatica."00','".$ls_estcla."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
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
	}// end function uf_print_cuentasspg
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
		// Fecha Creación: 20/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sep;
		global $ls_loncodestpro1,$li_longestpro1,$ls_loncodestpro2,$li_longestpro2,$ls_loncodestpro3,$li_longestpro3;
		global $ls_loncodestpro4,$li_longestpro4,$ls_loncodestpro5,$li_longestpro5;
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$ls_codestpro1=$_POST['codestpro1'];
		$ls_codestpro2=$_POST['codestpro2'];
		$ls_codestpro3=$_POST['codestpro3'];
		$ls_codestpro4=$_POST['codestpro4'];
		$ls_codestpro5=$_POST['codestpro5'];
		$ls_estclacar=$_POST["estcla"];
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		if($ls_campoorden=="codpro")
		{
			$ls_campoorden= "spg_cuentas.codestpro1, spg_cuentas.codestpro2, spg_cuentas.codestpro3, spg_cuentas.codestpro4, spg_cuentas.codestpro5 ,spg_cuentas.estcla";
		}
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$ls_titulo="Estructura Presupuestaria ";
				$li_len1=20;
				$li_len2=6;
				$li_len3=3;
				$li_len4=2;
				$li_len5=2;
				break;
				
			case "2": // Modalidad por Presupuesto
				$ls_titulo="Estructura Programática ";
				$li_len1=2;
				$li_len2=2;
				$li_len3=2;
				$li_len4=2;
				$li_len5=2;
				break;
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
			    "       spg_cuentas.codestpro2, spg_cuentas.codestpro3, spg_cuentas.codestpro4, spg_cuentas.codestpro5, MAX(status) AS status, ".
				"       (MAX(spg_cuentas.asignado)-(MAX(spg_cuentas.comprometido)+MAX(spg_cuentas.precomprometido))+MAX(spg_cuentas.aumento)-MAX(spg_cuentas.disminucion)) as disponible,spg_cuentas.estcla ".
			    "  FROM spg_cuentas, sigesp_cargos ".
				" WHERE spg_cuentas.codemp = '".$ls_codemp."'  ".
				"   AND spg_cuentas.status ='C'  ".
				"	AND spg_cuentas.codemp = sigesp_cargos.codemp ".
				"   AND spg_cuentas.codestpro1 = substr(sigesp_cargos.codestpro,1,25) ".
				"   AND spg_cuentas.codestpro2 = substr(sigesp_cargos.codestpro,26,25) ".
				"   AND spg_cuentas.codestpro3 = substr(sigesp_cargos.codestpro,51,25) ".
				"   AND spg_cuentas.codestpro4 = substr(sigesp_cargos.codestpro,76,25) ".
				"   AND spg_cuentas.codestpro5 = substr(sigesp_cargos.codestpro,101,25) ".
				"   AND spg_cuentas.estcla=sigesp_cargos.estcla".
				"   AND spg_cuentas.spg_cuenta = sigesp_cargos.spg_cuenta ".
				" GROUP BY spg_cuentas.codestpro1, spg_cuentas.codestpro2, spg_cuentas.codestpro3, spg_cuentas.codestpro4, ".
				"       spg_cuentas.codestpro5, spg_cuentas.spg_cuenta ,spg_cuentas.estcla ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden." ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_spg_cuenta=$row["spg_cuenta"];
				$ls_denominacion=$row["denominacion"];
				$ls_codest1=$row["codestpro1"];
				$ls_codest1=substr($ls_codest1,$li_longestpro1-1,$ls_loncodestpro1);
				$ls_codest2=$row["codestpro2"];
				$ls_codest2=substr($ls_codest2,$li_longestpro2-1,$ls_loncodestpro2);
				$ls_codest3=$row["codestpro3"];
				$ls_codest3=substr($ls_codest3,$li_longestpro3-1,$ls_loncodestpro3);
				$ls_codest4=$row["codestpro4"];
				$ls_codest4=substr($ls_codest4,$li_longestpro4-1,$ls_loncodestpro4);
				$ls_codest5=$row["codestpro5"];
				$ls_estcla=$row["estcla"];
				$ls_codest5=substr($ls_codest5,$li_longestpro5-1,$ls_loncodestpro5);
				$ls_codestpro=$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5;
				$li_disponible=number_format($row["disponible"],2,",",".");
				if(($ls_codestpro==$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5)&& ($ls_estclacar==$ls_estcla))
				{
					$ls_estilo = "celdas-azules";
				}
				else
				{
					$ls_estilo = "celdas-blancas";
				}
				
				$ls_programatica=$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5;
				print "<tr class=".$ls_estilo.">";
				print "<td align='center'>".$ls_programatica."</td>";
				print "<td align='center'>".$ls_spg_cuenta."</td>";
				print "<td align='left'>".$ls_denominacion."</td>";
				print "<td align='right'>".$li_disponible."</td>";
				print "<td style='cursor:pointer'>";
				print "<a href=\"javascript: ue_aceptar('".$ls_programatica."','".$ls_spg_cuenta."','".$ls_denominacion."','".$ls_codestpro."','".$ls_estcla."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
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
	function uf_print_solicitud()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de la Solicitud de ejecuciòn presupuestaria
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan 
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 12/07/2007
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_loncodestpro1,$li_longestpro1,$ls_loncodestpro2,$li_longestpro2,$ls_loncodestpro3,$li_longestpro3;
		global $ls_loncodestpro4,$li_longestpro4,$ls_loncodestpro5,$li_longestpro5;

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
		$ls_numsol="%".$_POST["numsol"]."%";
		$ls_coduniadm="%".$_POST["coduniadm"]."%";
		$ls_codtipsol=substr($_POST["codtipsol"],0,2);
		if($ls_codtipsol=="-") // no selecciono ninguna
		{
			$ls_codtipsol="";
		}
		$ls_codtipsol="%".$ls_codtipsol."%";
		$ld_fecregdes=$io_funciones->uf_convertirdatetobd($_POST["fecregdes"]);
		$ld_fecreghas=$io_funciones->uf_convertirdatetobd($_POST["fecreghas"]);
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		$ls_codigo=$_POST['codigo'];
		$ls_tipdes=$_POST['tipdes'];
		$ls_consol=$_POST['consol'];
		$ls_denart=$_POST['denart'];
		$ls_logusr = $_SESSION["la_logusr"];
		$ls_criterio='';
		switch ($ls_tipdes)
		{
			case "P":
				$ls_tabla=", rpc_proveedor";
				$ls_cadena_provbene="AND sep_solicitud.cod_pro=rpc_proveedor.cod_pro AND rpc_proveedor.cod_pro='".$ls_codigo."' ";
			break;
			
			case "B":
				$ls_tabla=", rpc_beneficiario";
				$ls_cadena_provbene="AND sep_solicitud.ced_bene=rpc_beneficiario.ced_bene AND rpc_beneficiario.ced_bene='".$ls_codigo."' ";
			break;
			
			case "-":
				$ls_tabla="";
				$ls_cadena_provbene="";
			break;
		}
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				$ilike = '';
				$ls_seguridad="CONCAT(sep_solicitud.codestpro1,sep_solicitud.codestpro2,sep_solicitud.codestpro3,sep_solicitud.codestpro4,sep_solicitud.codestpro5,sep_solicitud.estcla)";
				break;
			case "POSTGRES":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				$ilike = 'I';
				$ls_seguridad="sep_solicitud.codestpro1||sep_solicitud.codestpro2||sep_solicitud.codestpro3||sep_solicitud.codestpro4||sep_solicitud.codestpro5||sep_solicitud.estcla";
				break;
			case "INFORMIX":
			    $ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				$ilike = '';
				$ls_seguridad="sep_solicitud.codestpro1||sep_solicitud.codestpro2||sep_solicitud.codestpro3||sep_solicitud.codestpro4||sep_solicitud.codestpro5||sep_solicitud.estcla";
		}
		$ls_join_art = ", ";
		if($ls_denart)
		{
			$ls_criterio = " AND siv_articulo.denart  ".$ilike."LIKE '%".$ls_denart."%' ";
			$ls_join_art = " LEFT JOIN sep_dt_articulos ".
						   "    ON sep_dt_articulos.codemp = '".$ls_codemp."' ".
						   "   AND sep_dt_articulos.numsol like '".$ls_numsol."' ".
						   "   AND sep_solicitud.coduniadm like '".$ls_coduniadm."' ".
						   "   AND sep_solicitud.codtipsol like '".$ls_codtipsol."' ".
						   "   AND sep_dt_articulos.codemp = sep_solicitud.codemp ".
						   "   AND sep_dt_articulos.numsol = sep_solicitud.numsol ".
						   "  LEFT JOIN siv_articulo ".
						   "    ON sep_dt_articulos.codemp = '".$ls_codemp."' ".
						   "   AND sep_dt_articulos.numsol like '".$ls_numsol."' ".
						   "   AND sep_dt_articulos.codemp = siv_articulo.codemp ".
						   "   AND sep_dt_articulos.codart = siv_articulo.codart, ";

		}
		$ls_sql="SELECT sep_solicitud.numsol, sep_solicitud.codtipsol, sep_solicitud.coduniadm, sep_solicitud.codfuefin, ".
				"		sep_solicitud.fecregsol, sep_solicitud.estsol, sep_solicitud.consol, sep_solicitud.monto, sep_solicitud.tipsepbie,".
				"		sep_solicitud.monbasinm, sep_solicitud.montotcar, sep_solicitud.tipo_destino, sep_solicitud.cod_pro, ".
				"		sep_solicitud.ced_bene, spg_unidadadministrativa.denuniadm, sigesp_fuentefinanciamiento.denfuefin,".
				"       sep_solicitud.estapro, sep_tiposolicitud.estope, sep_tiposolicitud.modsep,sep_tiposolicitud.estayueco,sep_solicitud.nombenalt,".
				"       sep_solicitud.codestpro1,sep_solicitud.codestpro2,sep_solicitud.codestpro3,sep_solicitud.codestpro4,sep_solicitud.codestpro5,sep_solicitud.estcla,".
				"       (CASE tipo_destino WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                          FROM rpc_proveedor ".
				"                                         WHERE rpc_proveedor.codemp=sep_solicitud.codemp ".
				"                                           AND rpc_proveedor.cod_pro=sep_solicitud.cod_pro) ".
				"                         WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                          FROM rpc_beneficiario ".
				"                                         WHERE rpc_beneficiario.codemp=sep_solicitud.codemp ".
				"                                           AND rpc_beneficiario.ced_bene=sep_solicitud.ced_bene) ". 
				"                         ELSE 'NINGUNO' END ) AS nombre, ".
				"       (CASE tipo_destino WHEN 'P' THEN (SELECT rpc_proveedor.rifpro  ".
				"                                          FROM rpc_proveedor          ".
				"                                         WHERE rpc_proveedor.codemp=sep_solicitud.codemp ".
				"                                           AND rpc_proveedor.cod_pro=sep_solicitud.cod_pro) ".
				"                         WHEN 'B' THEN (SELECT rpc_beneficiario.rifben ".
				"                                          FROM rpc_beneficiario ".
				"                                         WHERE rpc_beneficiario.codemp=sep_solicitud.codemp ".
				"                                           AND rpc_beneficiario.ced_bene=sep_solicitud.ced_bene) ". 
				"                         ELSE 'NINGUNO' END ) AS rif ".
				"  FROM sep_solicitud ".$ls_join_art." spg_unidadadministrativa,spg_dt_unidadadministrativa, sigesp_fuentefinanciamiento, sep_tiposolicitud ".$ls_tabla." ".
				" WHERE sep_solicitud.codemp='".$ls_codemp."' ".
				"   AND sep_solicitud.numsol like '".$ls_numsol."' ".
				"   AND sep_solicitud.coduniadm like '".$ls_coduniadm."' ".
				"   AND sep_solicitud.codtipsol like '".$ls_codtipsol."' ".
				"   AND sep_solicitud.fecregsol between '".$ld_fecregdes."' AND '".$ld_fecreghas."' ".
				"   ".$ls_cadena_provbene." ".
				$ls_criterio.
				"   AND sep_solicitud.consol ".$ilike."LIKE '%".$ls_consol."%' ".
				"   AND sep_solicitud.codemp=spg_unidadadministrativa.codemp ".
				"   AND sep_solicitud.coduniadm=spg_unidadadministrativa.coduniadm ".
				"   AND sep_solicitud.coduniadm=spg_unidadadministrativa.coduniadm ".
				"   AND spg_dt_unidadadministrativa.coduniadm=spg_unidadadministrativa.coduniadm ".
				"   AND sep_solicitud.codestpro1=spg_dt_unidadadministrativa.codestpro1 ".
				"   AND sep_solicitud.codestpro2=spg_dt_unidadadministrativa.codestpro2 ".
				"   AND sep_solicitud.codestpro3=spg_dt_unidadadministrativa.codestpro3 ".
				"   AND sep_solicitud.codestpro4=spg_dt_unidadadministrativa.codestpro4 ".
				"   AND sep_solicitud.codestpro5=spg_dt_unidadadministrativa.codestpro5 ".
				"   AND sep_solicitud.estcla=spg_dt_unidadadministrativa.estcla ".
				"   AND sep_solicitud.codemp=sigesp_fuentefinanciamiento.codemp ".
				"   AND sep_solicitud.codfuefin=sigesp_fuentefinanciamiento.codfuefin ".
				"   AND sep_solicitud.codtipsol=sep_tiposolicitud.codtipsol ".
				"   AND ".$ls_seguridad." IN (SELECT codintper FROM sss_permisos_internos".
				"                              WHERE sss_permisos_internos.codemp='".$ls_codemp."'".
				"                                AND codsis='SPG'".
				"                                AND codusu='".$ls_logusr."')".
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 align=center>";
		print "<tr>";
		print "<td align=center class='texto-azul'>Cantidad de Registros ".$io_sql->num_rows($rs_data)."</td>";
		print "</tr>";
		print "</table>";
		print "<table width=630 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=100  style='cursor:pointer' title='Ordenar por Numero de Solicitud' align='center' onClick=ue_orden('sep_solicitud.numsol')>Numero de Solicitud</td>";
		print "<td width=120 style='cursor:pointer' title='Ordenar por Unidad Ejecutora' align='center' onClick=ue_orden('spg_unidadadministrativa.denuniadm')>Unidad Ejecutora</td>";
		print "<td width=70  style='cursor:pointer' title='Ordenar por Fecha de Registro' align='center' onClick=ue_orden('sep_solicitud.fecregsol')>Fecha de Registro</td>";
		print "<td width=120 style='cursor:pointer' title='Ordenar por Proveedor/Beneficiario' align='center' onClick=ue_orden('nombre')>Proveedor / Beneficiario</td>";
		print "<td width=90  style='cursor:pointer' title='Ordenar por Estatus' align='center' onClick=ue_orden('sep_solicitud.estsol')>Estatus</td>";
		print "<td width=100 style='cursor:pointer' title='Ordenar por Monto' align='center' onClick=ue_orden('monto')>Monto</td>";
		print "</tr>";
		if($rs_data===false)
		{
			$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			$li_i=0;
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_numsol=$row["numsol"];
				$ls_codtipsol=$row["codtipsol"];
				$ls_coduniadm=$row["coduniadm"];
				$ls_codfuefin=$row["codfuefin"];
				$ls_estsol=$row["estsol"];
				$ls_consol=$row["consol"];
				$reemplazos = array('\n', '\r');	
				$ls_consol = str_replace($reemplazos,'',$ls_consol);
				$ls_tipo_destino=$row["tipo_destino"];
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
				$ls_rif=$row["rif"];
				$ls_nombre=$row["nombre"];
				$ls_denuniadm=$row["denuniadm"];
				$ls_denfuefin=$row["denfuefin"];
				$ls_estapro=$row["estapro"];
				$ld_fecregsol=$io_funciones->uf_formatovalidofecha($row["fecregsol"]);
				$ld_fecregsol=$io_funciones->uf_convertirfecmostrar($ld_fecregsol);
				$li_monto=number_format($row["monto"],2,",",".");
				$li_monbasinm=number_format($row["monbasinm"],2,",",".");
				$li_montotcar=number_format($row["montotcar"],2,",",".");
				$ls_estope=$row["estope"];
				$ls_modsep=$row["modsep"];
				$ls_codestpro1=$row["codestpro1"];
				$ls_codestpro2=$row["codestpro2"];
				$ls_codestpro3=$row["codestpro3"];
				$ls_codestpro4=$row["codestpro4"];
				$ls_codestpro5=$row["codestpro5"];
				$ls_estcla=$row["estcla"];
				$ls_nombenalt=$row["nombenalt"];
				$ls_estayueco = $row["estayueco"];
				$ls_tipsepbie = $row["tipsepbie"];
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
						$ls_estatus="DESPACHADA";
						break;
					
					case "L":
						$ls_estatus="DESPACHADA PARCIALMENTE";
						break;
				}
				$li_i++;
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: ue_aceptar('$ls_numsol','$ls_codtipsol','$ls_coduniadm','$ls_codfuefin',".
											"'$ls_estsol','$ls_tipo_destino','$ls_codigo','$ls_denuniadm',".
											"'$ls_denfuefin','".$ls_nombre."','$ls_estapro','$ld_fecregsol','$li_monto','$li_monbasinm',".
											"'$li_montotcar','$ls_estatus','$ls_estope','$ls_modsep','$ls_codestpro1','$ls_codestpro2',".
											"'$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_estcla','$ls_consol','$ls_nombenalt',".
											"'$ls_estayueco','$ls_tipsepbie','$ls_rif','$li_i');\">".$ls_numsol."</a>";
						print "<input type='hidden' id='hidconsol".$li_i."' name='hidconsol".$ls_numsol."' value='".$ls_consol."' ></td>";
						print "<td align='left'>".$ls_denuniadm."</td>";
						print "<td align='center'>".$ld_fecregsol."</td>";
						print "<td align='left'>".$ls_nombre."</td>";
						print "<td align='center'>".$ls_estatus."</td>";
						print "<td align='right'>".$li_monto."</td>";
						print "</tr>";			
						break;

					case "REPDES":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: ue_aceptar_reportedesde('$ls_numsol');\">".$ls_numsol."</a></td>";
						print "<td>".$ls_denuniadm."</td>";
						print "<td align='center'>".$ld_fecregsol."</td>";
						print "<td>".$ls_nombre."</td>";
						print "<td align='center'>".$ls_estatus."</td>";
						print "<td align='right'>".$li_monto."</td>";
						print "</tr>";			
						break;

					case "REPHAS":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: ue_aceptar_reportehasta('$ls_numsol');\">".$ls_numsol."</a></td>";
						print "<td>".$ls_denuniadm."</td>";
						print "<td align='center'>".$ld_fecregsol."</td>";
						print "<td>".$ls_nombre."</td>";
						print "<td align='center'>".$ls_estatus."</td>";
						print "<td align='right'>".$li_monto."</td>";
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
	}// end function uf_print_solicitud
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
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sep;
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
		$ls_codser="%".$_POST['codser']."%";
		$ls_denser="%".$_POST['denser']."%";
		$ls_codestpro1=$_POST['codestpro1'];
		$ls_codestpro2=$_POST['codestpro2'];
		$ls_codestpro3=$_POST['codestpro3'];
		$ls_codestpro4=$_POST['codestpro4'];
		$ls_codestpro5=$_POST['codestpro5'];
		$ls_codestpro1=str_pad($ls_codestpro1,25,0,0);
		$ls_codestpro2=str_pad($ls_codestpro2,25,0,0);
		$ls_codestpro3=str_pad($ls_codestpro3,25,0,0);
		$ls_codestpro4=str_pad($ls_codestpro4,25,0,0);
		$ls_codestpro5=str_pad($ls_codestpro5,25,0,0);
		$ls_estcla=$_POST["estcla"];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_parsindis=$_SESSION["la_empresa"]["estparsindis"];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td style='cursor:pointer' title='Ordenar por Codigo'       align='center' onClick=ue_orden('codser')>Codigo</td>";
		print "<td style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('denser')>Denominacion</td>";
		print "<td style='cursor:pointer' title='Ordenar por Precio'       align='center' onClick=ue_orden('preser')>Precio Unitario</td>";
		print "<td style='cursor:pointer' title='Ordenar por Cuenta'       align='center' onClick=ue_orden('spg_cuenta')>Cuenta</td>";
		print "<td></td>";
		print "</tr>";
		if($ls_parsindis=='0')
		{
		$ls_sql="SELECT codser, denser, preser,  TRIM(spg_cuenta) as spg_cuenta , ".
				"		(SELECT COUNT(spg_cuenta) FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codestpro1 = '".$ls_codestpro1."' ".
				"		    AND spg_cuentas.codestpro2 = '".$ls_codestpro2."' ".
				"		    AND spg_cuentas.codestpro3 = '".$ls_codestpro3."' ".
				"		    AND spg_cuentas.codestpro4 = '".$ls_codestpro4."' ".
				"		    AND spg_cuentas.codestpro5 = '".$ls_codestpro5."' ".
				"           AND spg_cuentas.estcla = '".$ls_estcla."'".
				"			AND soc_servicios.codemp = spg_cuentas.codemp ".
				"			AND soc_servicios.spg_cuenta = spg_cuentas.spg_cuenta) AS existecuenta ".
				"  FROM soc_servicios ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND codser like '".$ls_codser."' ".
				"   AND denser like '".$ls_denser."' ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden." ";
		 }
		 else
		 {
		 	$ls_sql="SELECT codser, denser, preser,  TRIM(spg_cuenta) as spg_cuenta , ".
				"		(SELECT COUNT(spg_cuenta) FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codestpro1 = '".$ls_codestpro1."' ".
				"		    AND spg_cuentas.codestpro2 = '".$ls_codestpro2."' ".
				"		    AND spg_cuentas.codestpro3 = '".$ls_codestpro3."' ".
				"		    AND spg_cuentas.codestpro4 = '".$ls_codestpro4."' ".
				"		    AND spg_cuentas.codestpro5 = '".$ls_codestpro5."' ".
				"           AND spg_cuentas.estcla = '".$ls_estcla."'".
				"			AND soc_servicios.codemp = spg_cuentas.codemp ".
				"			AND soc_servicios.spg_cuenta = spg_cuentas.spg_cuenta) AS existecuenta, ".
				"	    (SELECT (asignado-(comprometido+precomprometido)+aumento-disminucion) ".
				"		   FROM spg_cuentas ".
				"		  WHERE  spg_cuentas.codestpro1 = '".$ls_codestpro1."' ".
				"		    AND spg_cuentas.codestpro2 = '".$ls_codestpro2."'".
				"		    AND spg_cuentas.codestpro3 = '".$ls_codestpro3."' ".
				"		    AND spg_cuentas.codestpro4 = '".$ls_codestpro4."' ".
				"		    AND spg_cuentas.codestpro5 = '".$ls_codestpro5."' ".
				"           AND spg_cuentas.estcla='".$ls_estcla."'".
				"			AND spg_cuentas.spg_cuenta = soc_servicios.spg_cuenta) AS disponibilidad ".
				"  FROM soc_servicios ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND codser like '".$ls_codser."' ".
				"   AND denser like '".$ls_denser."' ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden." ";
		 }
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
			  if($ls_parsindis==0)
		      {
					$ls_codser=$row["codser"];
					$ls_denser=$row["denser"];
					$li_preser=number_format($row["preser"],2,",",".");
					$ls_spg_cuenta=$row["spg_cuenta"];
					$li_existecuenta=$row["existecuenta"];
					if($li_existecuenta==0)
					{
						$ls_estilo = "celdas-blancas";
					}
					else
					{
						$ls_estilo = "celdas-azules";
					}
					print "<tr class=".$ls_estilo.">";
					print "<td align='center'>".$ls_codser."</td>";
					print "<td align='left'>".$ls_denser."</td>";
					print "<td align='left'>".$li_preser."</td>";
					print "<td align='center'>".$ls_spg_cuenta."</td>";
					print "<td style='cursor:pointer'>";
					print "<a href=\"javascript: ue_aceptar('".$ls_codser."','".$ls_denser."','".$li_preser."','".$ls_spg_cuenta."','".$li_existecuenta."','".$ls_estcla."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
					print "</tr>";
			   }
			   else
			   {
			        $ls_codser=$row["codser"];
					$ls_denser=$row["denser"];
					$li_preser=number_format($row["preser"],2,",",".");
					$ls_spg_cuenta=$row["spg_cuenta"];
			   		$li_disponibilidad=$row["disponibilidad"];
					$li_existecuenta=$row["existecuenta"];
				    if($li_existecuenta==0)
					{ 
					  $ls_estilo = "celdas-blancas";
					}
					else
					{
					  $ls_estilo = "celdas-azules"; 
					}
					print "<tr class=".$ls_estilo.">";
					print "<td align='center'>".$ls_codser."</td>";
					print "<td align='left'>".$ls_denser."</td>";
					print "<td align='left'>".$li_preser."</td>";
					print "<td align='center'>".$ls_spg_cuenta."</td>";
					print "<td style='cursor:pointer'>";
					
					 if($li_disponibilidad >0)
					  {
					     $ls_estilo = "celdas-azules";
						  print "<a href=\"javascript: ue_aceptar('".$ls_codser."','".$ls_denser."','".$li_preser."','".$ls_spg_cuenta."','".$li_existecuenta."','".$ls_estcla."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
					      print "</tr>";	
					  }
					  else
					  {
					    print "<a href=\"javascript: ue_mensaje();\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
					    print "</tr>";
					  }
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
	}// end function uf_print_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_conceptos()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_conceptos
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que obtiene e imprime el resultado de la busqueda de los conceptos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sep;
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
		$ls_parsindis=$_SESSION["la_empresa"]["estparsindis"];
		$ls_codconsep="%".$_POST['codconsep']."%";
		$ls_denconsep="%".$_POST['denconsep']."%";
		$ls_codestpro1=$_POST['codestpro1'];
		$ls_codestpro2=$_POST['codestpro2'];
		$ls_codestpro3=$_POST['codestpro3'];
		$ls_codestpro4=$_POST['codestpro4'];
		$ls_codestpro5=$_POST['codestpro5'];
		$ls_estcla=$_POST["estcla"];
		$ls_codestpro1 = $io_funciones->uf_cerosizquierda($ls_codestpro1,25);
		$ls_codestpro2 = $io_funciones->uf_cerosizquierda($ls_codestpro2,25);
		$ls_codestpro3 = $io_funciones->uf_cerosizquierda($ls_codestpro3,25);
		$ls_codestpro4 = $io_funciones->uf_cerosizquierda($ls_codestpro4,25);
		$ls_codestpro5 = $io_funciones->uf_cerosizquierda($ls_codestpro5,25);
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td style='cursor:pointer' title='Ordenar por Codigo'       align='center' onClick=ue_orden('codconsep')>Codigo</td>";
		print "<td style='cursor:pointer' title='Ordenar por Denominacion' align='center' onClick=ue_orden('denconsep')>Denominacion</td>";
		print "<td style='cursor:pointer' title='Ordenar por Precio'       align='center' onClick=ue_orden('monconsepe')>Precio Unitario</td>";
		print "<td style='cursor:pointer' title='Ordenar por Cuenta'       align='center' onClick=ue_orden('spg_cuenta')>Cuenta</td>";
		print "<td></td>";
		print "</tr>";
		if($ls_parsindis=='0')
		{
			$ls_sql="SELECT codconsep, denconsep, monconsepe, TRIM(spg_cuenta) as spg_cuenta, ".
					"		(SELECT COUNT(spg_cuenta) FROM spg_cuentas ".
					"		  WHERE spg_cuentas.codestpro1 = '".$ls_codestpro1."' ".
					"		    AND spg_cuentas.codestpro2 = '".$ls_codestpro2."' ".
					"		    AND spg_cuentas.codestpro3 = '".$ls_codestpro3."' ".
					"		    AND spg_cuentas.codestpro4 = '".$ls_codestpro4."' ".
					"		    AND spg_cuentas.codestpro5 = '".$ls_codestpro5."' ".
					"           AND  spg_cuentas.estcla = '".$ls_estcla."'".
					"			AND spg_cuentas.spg_cuenta = sep_conceptos.spg_cuenta) AS existecuenta ".
					"  FROM sep_conceptos ".
					" WHERE codconsep like '".$ls_codconsep."' ".
					"   AND denconsep like '".$ls_denconsep."' ".
					" ORDER BY ".$ls_campoorden." ".$ls_orden." ";
		}
		else
		{
		    $ls_sql="SELECT codconsep, denconsep, monconsepe, TRIM(spg_cuenta) as spg_cuenta, ".
			  		"		(SELECT COUNT(spg_cuenta) FROM spg_cuentas ".
					"		  WHERE spg_cuentas.codestpro1 = '".$ls_codestpro1."' ".
					"		    AND spg_cuentas.codestpro2 = '".$ls_codestpro2."' ".
					"		    AND spg_cuentas.codestpro3 = '".$ls_codestpro3."' ".
					"		    AND spg_cuentas.codestpro4 = '".$ls_codestpro4."' ".
					"		    AND spg_cuentas.codestpro5 = '".$ls_codestpro5."' ".
					"           AND  spg_cuentas.estcla = '".$ls_estcla."' ".
					"			AND spg_cuentas.spg_cuenta = sep_conceptos.spg_cuenta) AS existecuenta, ".
					"	    (SELECT (asignado-(comprometido+precomprometido)+aumento-disminucion) ".
					"		   FROM spg_cuentas ".
					"		  WHERE  spg_cuentas.codestpro1 = '".$ls_codestpro1."' ".
					"		    AND spg_cuentas.codestpro2 = '".$ls_codestpro2."'".
					"		    AND spg_cuentas.codestpro3 = '".$ls_codestpro3."' ".
					"		    AND spg_cuentas.codestpro4 = '".$ls_codestpro4."' ".
					"		    AND spg_cuentas.codestpro5 = '".$ls_codestpro5."' ".
					"           AND spg_cuentas.estcla='".$ls_estcla."'".
					"			AND spg_cuentas.spg_cuenta = sep_conceptos.spg_cuenta) AS disponibilidad ".
					"  FROM sep_conceptos  ".
					"  WHERE codconsep like '".$ls_codconsep."' ".
					"   AND denconsep like '".$ls_denconsep."' ".
					" ORDER BY ".$ls_campoorden." ".$ls_orden." ";
		}			
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
			   if ($ls_parsindis==0)
			   {
					$ls_codconsep=$row["codconsep"];
					$ls_denconsep=$row["denconsep"];
					$li_monconsepe=number_format($row["monconsepe"],2,",",".");
					$ls_spg_cuenta=$row["spg_cuenta"];
					$li_existecuenta=$row["existecuenta"];
					if($li_existecuenta==0)
					{
						$ls_estilo = "celdas-blancas";
					}
					else
					{
						$ls_estilo = "celdas-azules";
					}
					print "<tr class=".$ls_estilo.">";
					print "<td align='center'>".$ls_codconsep."</td>";
					print "<td align='left'>".$ls_denconsep."</td>";
					print "<td align='left'>".$li_monconsepe."</td>";
					print "<td align='center'>".$ls_spg_cuenta."</td>";
					print "<td style='cursor:pointer'>";
					print "<a href=\"javascript: ue_aceptar('".$ls_codconsep."','".$ls_denconsep."','".$li_monconsepe."','".$ls_spg_cuenta."','".$li_existecuenta."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
					print "</tr>";
				}
				else
				{ 
				    $ls_codconsep=$row["codconsep"];
					$ls_denconsep=$row["denconsep"];
					$li_monconsepe=number_format($row["monconsepe"],2,",",".");
					$ls_spg_cuenta=$row["spg_cuenta"];
					$li_existecuenta=$row["existecuenta"];
				    $li_existecuenta=$row["existecuenta"];
					$li_disponibilidad=$row["disponibilidad"];
					 if($li_existecuenta==0)
					{ 
					  $ls_estilo = "celdas-blancas";
					}
					else
					{
					  $ls_estilo = "celdas-azules"; 
					}
					print "<tr class=".$ls_estilo.">";
					print "<td align='center'>".$ls_codconsep."</td>";
					print "<td align='left'>".$ls_denconsep."</td>";
					print "<td align='left'>".$li_monconsepe."</td>";
					print "<td align='center'>".$ls_spg_cuenta."</td>";
					print "<td style='cursor:pointer'>";
					 if($li_disponibilidad >0)
					  {
					     $ls_estilo = "celdas-azules";
             			  print "<a href=\"javascript: ue_aceptar('".$ls_codconsep."','".$ls_denconsep."','".$li_monconsepe."','".$ls_spg_cuenta."','".$li_existecuenta."');\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
					      print "</tr>";	
					  }
					  else
					  {
					    print "<a href=\"javascript: ue_mensaje();\"><img src='../shared/imagebank/tools20/aprobado.gif' title='Agregar' width='15' height='15' border='0'></a></td>";
					    print "</tr>";
					  }				
						
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
	}// end function uf_print_conceptos
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
		$ls_codunieje  = $_POST["coduniadm"];
		$ls_denunieje  = $_POST["denuniadm"];
		$ls_orden      = $_POST['orden'];
		$ls_campoorden = $_POST['campoorden'];
		$ls_tipo       = $_POST['tipo'];		
		$ls_logusr = $_SESSION["la_logusr"];
		$ls_gestor = $_SESSION["ls_gestor"];
		$ls_sql_seguridad = "";
		if (strtoupper($ls_gestor) == "MYSQLT")
		{
		 $ls_sql_seguridad = " AND CONCAT('".$ls_codemp."','SEP','".$ls_logusr."',spg_unidadadministrativa.coduniadm) IN (SELECT CONCAT(codemp,codsis,codusu,codintper) 
		                       FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SEP')";
		}
		else
		{
		 $ls_sql_seguridad = " AND '".$ls_codemp."'||'SEP'||'".$ls_logusr."'||spg_unidadadministrativa.coduniadm IN (SELECT codemp||codsis||codusu||codintper
		                       FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SEP')";
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
		$rs_data=$io_sql->select($ls_sql);
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 align=center>";
		print "<tr>";
		print "<td align=center class='texto-azul'>Cantidad de Registros ".$io_sql->num_rows($rs_data)."</td>";
		print "</tr>";
		print "</table>";
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


?>