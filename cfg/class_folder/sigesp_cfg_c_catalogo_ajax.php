<?php
	//-----------------------------------------------------------------------------------------------------------------------------------
	// Clase donde se cargan todos los catálogos del sistema CFG con la utilización del AJAX
	//-----------------------------------------------------------------------------------------------------------------------------------
    session_start();   
	require_once("class_funciones_cfg.php");
	$io_funciones_cfg = new class_funciones_cfg();
	if (isset($_SESSION["la_empresa"]))
	   {
	     $li_estmodest     = $_SESSION["la_empresa"]["estmodest"];
 		 $li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
		 $li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
		 $li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
		 $li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
		 $li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
	   }
	// Tipo del catalogo que se requiere pintar
	$ls_catalogo=$io_funciones_cfg->uf_obtenervalor("catalogo","");
	switch($ls_catalogo){
	  case "CODESTPRO1":
	    uf_print_codestpro1();
	  break;
	  case "CODESTPRO2":
	    uf_print_codestpro2();
	  break;
	  case "CODESTPRO3":
	    uf_print_codestpro3();
	  break;
	  case "CODESTPRO4":
	    uf_print_codestpro4();
	  break;
	  case "CODESTPRO5":
	    uf_print_codestpro5();
	  break;
	  case "CODFUEFIN":
	    uf_print_fuentes();
	  break;
	}
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_codestpro1()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_codestpro1
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de la Estructura Presupuestaria de Nivel 1.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 22/12/2008. 								Fecha Última Modificación : 22/12/2008.
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");
		global $li_loncodestpro1;
		
		$io_include    = new sigesp_include();
		$io_conexion   = $io_include->uf_conectar();
		$io_sql		   = new class_sql($io_conexion);	
		$io_mensajes   = new class_mensajes();		
		$io_funciones  = new class_funciones();		
		
		$ls_sqlaux 	   = "";
		$ls_codemp	   = $_SESSION["la_empresa"]["codemp"];
		$ls_orden	   = $_POST['orden'];
		$ls_campoorden = $_POST['campoorden'];
		$ls_codestpro1 = $_POST['codestpro1'];
		if (!empty($ls_codestpro1))
		   {
			 $ls_sqlaux = " AND codestpro1 like '%".$ls_codestpro1."%'";
		   }
		$ls_denestpro1 = $_POST['denestpro1'];
		if (!empty($ls_denestpro1))
		   {
		     $ls_sqlaux = $ls_sqlaux." AND UPPER(denestpro1) like '%".strtoupper($ls_denestpro1)."%'";
		   }
		echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		echo "<tr class=titulo-celda>";
		echo "<td width=60  style='cursor:pointer' title='Ordenar por C&oacute;digo'       align='center' onClick=ue_orden('codestpro1')>C&oacute;digo</td>";
		echo "<td width=390 style='cursor:pointer' title='Ordenar por Denominaci&oacute;n' align='center' onClick=ue_orden('denestpro1')>Denominaci&oacute;n</td>";
		echo "<td width=50  style='cursor:pointer' title='Ordenar por Tipo'                align='center' onClick=ue_orden('estcla')>Tipo</td>";
		echo "</tr>";
		
		$ls_sql = "SELECT codestpro1,denestpro1,estcla
		             FROM spg_ep1
				    WHERE codemp = '".$ls_codemp."'					  
					  AND codestpro1 <> '-------------------------' $ls_sqlaux ".
				"     AND codestpro1||estcla IN (SELECT SUBSTR(codintper,1,25)||SUBSTR(codintper,126,1) FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"	UNION ".
				"   SELECT SUBSTR(codintper,1,25)||SUBSTR(codintper,126,1) FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru) 	  
					ORDER BY $ls_campoorden $ls_orden";
		
		$rs_data = $io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
			 $io_mensajes->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
			 $io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		   }
		else
		   {
			 while (!$rs_data->EOF)
			       {
					 $ls_codestpro1 = substr($rs_data->fields["codestpro1"],-$li_loncodestpro1);
					 $ls_denestpro1 = rtrim(utf8_encode($rs_data->fields["denestpro1"]));
					 $ls_estcla     = $rs_data->fields["estcla"];
					 if ($ls_estcla=='P')
					    {
						  $ls_denestcla = "Proyecto";
						}
					 elseif($ls_estcla=='A')
					    {
						  $ls_denestcla = utf8_encode("Acción");
						}
					 echo "<tr class=celdas-blancas>";
					 echo "<td style=text-align:center width=60><a href=\"javascript:uf_aceptar_codestpro1('$ls_codestpro1','$ls_denestpro1','$ls_estcla');\">".$ls_codestpro1."</a></td>";
					 echo "<td style=text-align:left   width=390 title='".$ls_denestpro1."'>".$ls_denestpro1."</td>";
					 echo "<td style=text-align:center width=50>".$ls_denestcla."</td>";
				  	 echo "</tr>";
					 $rs_data->MoveNext();
				   }
		   }
		$io_sql->free_result($rs_data);
		echo "</table>";
		unset($io_include,$io_conexion,$io_sql,$io_mensajes,$io_funciones,$ls_codemp);
	}// end function uf_print_codestpro1.
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_codestpro2()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_codestpro1
		//		   Access: private
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de la Estructura Presupuestaria de Nivel 2.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 23/12/2008. 								Fecha Última Modificación : 23/12/2008.
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");
		global $li_loncodestpro1,$li_loncodestpro2;
		
		$io_include    = new sigesp_include();
		$io_conexion   = $io_include->uf_conectar();
		$io_sql		   = new class_sql($io_conexion);	
		$io_mensajes   = new class_mensajes();		
		$io_funciones  = new class_funciones();		
		
		$ls_sqlaux     = "";
		$ls_codemp	   = $_SESSION["la_empresa"]["codemp"];
		$ls_estcla	   = $_POST['estcla'];
		$ls_orden	   = $_POST['orden'];		
		$ls_campoorden = $_POST['campoorden'];
		$ls_codestpro1 = str_pad($_POST['codestpro1'],25,0,0);
		$ls_codestpro2 = $_POST['codestpro2'];
		$ls_denestpro2 = $_POST['denestpro2'];
		if (!empty($ls_codestpro2))
		   {
			 $ls_sqlaux = " AND spg_ep2.codestpro2 like '%".$ls_codestpro2."%'";
		   }
		$ls_denestpro2 = $_POST['denestpro2'];
		if (!empty($ls_denestpro2))
		   {
		     $ls_sqlaux = $ls_sqlaux." AND UPPER(spg_ep2.denestpro2) like '%".strtoupper($ls_denestpro2)."%'";
		   }
				
		echo "<table width=550 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		echo "<tr class=titulo-celda>";
		echo "<td width=95  style='cursor:pointer' title='Ordenar por Nivel 1'             align='center' onClick=ue_orden('spg_ep2.codestpro1')>Nivel Anterior</td>";
		echo "<td width=55  style='cursor:pointer' title='Ordenar por Nivel 2'             align='center' onClick=ue_orden('spg_ep2.codestpro2')>C&oacute;digo</td>";
		echo "<td width=400 style='cursor:pointer' title='Ordenar por Denominaci&oacute;n' align='center' onClick=ue_orden('spg_ep2.denestpro2')>Denominaci&oacute;n</td>";
		echo "</tr>";
		
		$ls_sql = "SELECT spg_ep2.codestpro2,spg_ep2.denestpro2
		             FROM spg_ep1, spg_ep2
				    WHERE spg_ep2.codemp = '".$ls_codemp."'
					  AND spg_ep2.codestpro1 = '".$ls_codestpro1."'
					  AND spg_ep1.estcla = '".$ls_estcla."' $ls_sqlaux
					  AND spg_ep2.codestpro1 <> '-------------------------'
					  AND spg_ep1.codemp=spg_ep2.codemp
					  AND spg_ep1.estcla=spg_ep2.estcla
					  AND spg_ep1.codestpro1=spg_ep2.codestpro1 ".
				"     AND spg_ep1.codestpro1||spg_ep1.codestpro1||spg_ep2.estcla IN (SELECT SUBSTR(codintper,1,50)||SUBSTR(codintper,126,1) FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"	UNION ".
				"   SELECT SUBSTR(codintper,1,50)||SUBSTR(codintper,126,1) FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)
				 ORDER BY $ls_campoorden $ls_orden";
		
		$rs_data = $io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
			 $io_mensajes->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
			 $io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		   }
		else
		   {
			 $ls_codestpro1 = substr($ls_codestpro1,-$li_loncodestpro1);
			 while (!$rs_data->EOF)
			       {
					 $ls_codestpro2 = substr($rs_data->fields["codestpro2"],-$li_loncodestpro2);
					 $ls_denestpro2 = rtrim(utf8_encode($rs_data->fields["denestpro2"]));
					 echo "<tr class=celdas-blancas>";
					 echo "<td style=text-align:center width=95><a href=\"javascript:uf_aceptar_codestpro2('$ls_codestpro2','$ls_denestpro2');\">".$ls_codestpro1."</a></td>";
				 	 echo "<td style=text-align:center width=55><a href=\"javascript:uf_aceptar_codestpro2('$ls_codestpro2','$ls_denestpro2');\">".$ls_codestpro2."</a></td>";
					 echo "<td style=text-align:left   width=400 title='".$ls_denestpro2."'>".$ls_denestpro2."</td>";
				  	 echo "</tr>";
					 $rs_data->MoveNext();
				   }
		   }
		$io_sql->free_result($rs_data);
		echo "</table>";
		unset($io_include,$io_conexion,$io_sql,$io_mensajes,$io_funciones,$ls_codemp);
	}// end function uf_print_codestpro2.
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_codestpro3()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_codestpro1
		//		   Access: private
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de la Estructura Presupuestaria de Nivel 2.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 23/12/2008. 								Fecha Última Modificación : 23/12/2008.
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");
		global $li_loncodestpro1,$li_loncodestpro2,$li_loncodestpro3;
		
		$io_include    = new sigesp_include();
		$io_conexion   = $io_include->uf_conectar();
		$io_sql		   = new class_sql($io_conexion);	
		$io_mensajes   = new class_mensajes();		
		$io_funciones  = new class_funciones();		
		
		$ls_sqlaux     = $ls_auxsql = "";
		$ls_codemp	   = $_SESSION["la_empresa"]["codemp"];
		$ls_orden	   = $_POST['orden'];
		$ls_campoorden = $_POST['campoorden'];
		$ls_estcla	   = $_POST['estcla'];
		$ls_codestpro1 = $_POST['codestpro1'];
		$ls_codestpro2 = $_POST['codestpro2'];
		$ls_denestpro1 = $_POST['denestpro1'];
		$ls_denestpro2 = $_POST['denestpro2'];
		
		$ls_codestproaux1 = $ls_codestpro1;//Variables que me permitirán saber si debo accesar al resulset para 
		$ls_codestproaux2 = $ls_codestpro2;//obtener el valor de estos campos, o solo tomarlos del POST.
		
		if (!empty($ls_codestpro1) && !empty($ls_codestpro2))
		   {
		     $ls_codestpro1 = str_pad($ls_codestpro1,25,0,0);
		     $ls_codestpro2 = str_pad($ls_codestpro2,25,0,0);
			 $ls_sqlaux     = $ls_sqlaux." AND spg_ep3.codestpro1 = '".$ls_codestpro1."' 
			                               AND spg_ep3.codestpro2 = '".$ls_codestpro2."'
										   AND spg_ep3.estcla     = '".$ls_estcla."'";
		   }
		else
		   {
		     $ls_auxsql = ", spg_ep1.codestpro1, spg_ep1.denestpro1, spg_ep1.estcla, spg_ep2.codestpro2, spg_ep2.denestpro2 ";
		   }
		$ls_codestpro3 = $_POST['codestpro3'];
		if (!empty($ls_codestpro3))
		   {
			 $ls_sqlaux = $ls_sqlaux." AND spg_ep3.codestpro3 like '%".$ls_codestpro3."%'";
		   }
		$ls_denestpro3 = $_POST['denestpro3'];
		if (!empty($ls_denestpro3))
		   {
		     $ls_sqlaux = $ls_sqlaux." AND UPPER(spg_ep3.denestpro3) like '%".strtoupper($ls_denestpro3)."%'";
		   }
		
		echo "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		echo "<tr class=titulo-celda>";
		echo "<td width=145 align='center'>Niveles Anteriores</td>";
		echo "<td width=55  style='cursor:pointer' title='Ordenar por Nivel 3'             align='center' onClick=ue_orden('spg_ep3.codestpro3')>C&oacute;digo</td>";
		echo "<td width=400 style='cursor:pointer' title='Ordenar por Denominaci&oacute;n' align='center' onClick=ue_orden('spg_ep3.denestpro3')>Denominaci&oacute;n</td>";
		if (empty($ls_codestproaux1) && empty($ls_codestproaux2))
		   {
		     echo "<td width=50  style='cursor:pointer' title='Ordenar por Tipo' align='center' onClick=ue_orden('spg_ep3.estcla')>Tipo</td>";
		   }
		echo "</tr>";
		
		$ls_sql = "SELECT spg_ep3.codestpro3,spg_ep3.denestpro3 $ls_auxsql
		             FROM spg_ep1, spg_ep2, spg_ep3
				    WHERE spg_ep3.codemp = '".$ls_codemp."' $ls_sqlaux
					  AND spg_ep3.codestpro1 <> '-------------------------'
					  AND spg_ep3.codemp=spg_ep1.codemp
					  AND spg_ep3.codemp=spg_ep2.codemp
					  AND spg_ep3.codestpro1=spg_ep1.codestpro1
					  AND spg_ep3.codestpro1=spg_ep2.codestpro1
					  AND spg_ep3.codestpro2=spg_ep2.codestpro2
					  AND spg_ep3.estcla=spg_ep1.estcla
					  AND spg_ep3.estcla=spg_ep2.estcla ".
				"     AND spg_ep1.codestpro1||spg_ep2.codestpro2||spg_ep2.estcla IN (SELECT SUBSTR(codintper,1,50)||SUBSTR(codintper,126,1) FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"	UNION ".
				"   SELECT SUBSTR(codintper,1,50)||SUBSTR(codintper,126,1) FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)
					ORDER BY $ls_campoorden $ls_orden";//echo $ls_sql.'<br>';
		//print $ls_sql."<br>";
		$rs_data = $io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
			 $io_mensajes->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
			 $io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		   }
		else
		   {
			 while (!$rs_data->EOF)
			       {
					 if (empty($ls_codestproaux1) && empty($ls_codestproaux2))
					    {
						  $ls_estcla = $rs_data->fields["estcla"];
						  if ($ls_estcla=='P')
							 {
							   $ls_denestcla = "Proyecto";
							 }
						  elseif($ls_estcla=='A')
							 {
							   $ls_denestcla = utf8_encode("Acción");
							 }
						  $ls_codestpro1 = substr($rs_data->fields["codestpro1"],-$li_loncodestpro1);
						  $ls_denestpro1 = rtrim(utf8_encode($rs_data->fields["denestpro1"]));
						  $ls_codestpro2 = substr($rs_data->fields["codestpro2"],-$li_loncodestpro2);
						  $ls_denestpro2 = rtrim(utf8_encode($rs_data->fields["denestpro2"]));
						}					 
					 else
					    {
						  $ls_codestpro1 = substr($ls_codestpro1,-$li_loncodestpro1);
						  $ls_codestpro2 = substr($ls_codestpro2,-$li_loncodestpro2);
						}
					 $ls_codestpro3 = substr($rs_data->fields["codestpro3"],-$li_loncodestpro3);
					 $ls_denestpro3 = rtrim(utf8_encode($rs_data->fields["denestpro3"]));
					 echo "<tr class=celdas-blancas>";
					 echo "<td style=text-align:center width=145><a href=\"javascript:uf_aceptar_codestpro3('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_estcla');\">".$ls_codestpro1.'-'.$ls_codestpro2."</a></td>";
					 echo "<td style=text-align:center width=55><a href=\"javascript:uf_aceptar_codestpro3('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_estcla');\">".$ls_codestpro3."</a></td>";
					 echo "<td style=text-align:left   width=400 title='".$ls_denestpro3."'>".$ls_denestpro3."</td>";
					 if (empty($ls_codestproaux1) && empty($ls_codestproaux2))
					    {
					      echo "<td style=text-align:center   width=50 title='".$ls_denestcla."'>".$ls_denestcla."</td>";
						}
					 echo "</tr>";
					 $rs_data->MoveNext();
				   }
		   }
		$io_sql->free_result($rs_data);
		echo "</table>";
		unset($io_include,$io_conexion,$io_sql,$io_mensajes,$io_funciones,$ls_codemp);
	}// end function uf_print_codestpro3.
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_codestpro4()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_codestpro4
		//		   Access: private
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de la Estructura Presupuestaria de Nivel 2.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 26/12/2008. 								Fecha Última Modificación : 26/12/2008.
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");
		global $li_loncodestpro1,$li_loncodestpro2,$li_loncodestpro3,$li_loncodestpro4;
		
		$io_include    = new sigesp_include();
		$io_conexion   = $io_include->uf_conectar();
		$io_sql		   = new class_sql($io_conexion);	
		$io_mensajes   = new class_mensajes();		
		$io_funciones  = new class_funciones();		
		
		$ls_sqlaux     = $ls_auxsql = "";
		$ls_codemp	   = $_SESSION["la_empresa"]["codemp"];
		$ls_orden	   = $_POST['orden'];
		$ls_campoorden = $_POST['campoorden'];
		$ls_estcla	   = $_POST['estcla'];
		$ls_codestpro1 = $_POST['codestpro1'];
		$ls_codestpro2 = $_POST['codestpro2'];
		$ls_codestpro3 = $_POST['codestpro3'];
		$ls_denestpro1 = $_POST['denestpro1'];
		$ls_denestpro2 = $_POST['denestpro2'];
		$ls_denestpro3 = $_POST['denestpro3'];
		
		if (!empty($ls_codestpro1) && !empty($ls_codestpro2) && !empty($ls_codestpro3))
		   {
		     $ls_codestpre1 = str_pad($ls_codestpro1,25,0,0);
		     $ls_codestpre2 = str_pad($ls_codestpro2,25,0,0);
			 $ls_codestpre3 = str_pad($ls_codestpro3,25,0,0);
			 $ls_sqlaux     = $ls_sqlaux." AND spg_ep4.codestpro1 = '".$ls_codestpre1."' 
			                               AND spg_ep4.codestpro2 = '".$ls_codestpre2."'
										   AND spg_ep4.codestpro3 = '".$ls_codestpre3."'
										   AND spg_ep4.estcla     = '".$ls_estcla."'";
		   }
		$ls_codestpro4 = $_POST['codestpro4'];
		if (!empty($ls_codestpro4))
		   {
			 $ls_sqlaux = $ls_sqlaux." AND spg_ep4.codestpro4 like '%".$ls_codestpro4."%'";
		   }
		$ls_denestpro4 = $_POST['denestpro4'];
		if (!empty($ls_denestpro3))
		   {
		     $ls_sqlaux = $ls_sqlaux." AND UPPER(spg_ep4.denestpro4) like '%".strtoupper($ls_denestpro4)."%'";
		   }
		
		echo "<table width=700 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		echo "<tr class=titulo-celda>";
		echo "<td width=145 align='center'>Niveles Anteriores</td>";
		echo "<td width=55  style='cursor:pointer' title='Ordenar por Nivel 4'             align='center' onClick=ue_orden('spg_ep4.codestpro4')>C&oacute;digo</td>";
		echo "<td width=500 style='cursor:pointer' title='Ordenar por Denominaci&oacute;n' align='center' onClick=ue_orden('spg_ep4.denestpro4')>Denominaci&oacute;n</td>";
		echo "</tr>";
		
		$ls_sql = "SELECT spg_ep4.codestpro4,spg_ep4.denestpro4 $ls_auxsql
		             FROM spg_ep1, spg_ep2, spg_ep3, spg_ep4
				    WHERE spg_ep4.codemp = '".$ls_codemp."' $ls_sqlaux
					  AND spg_ep4.codestpro1 <> '-------------------------'					  
					  AND spg_ep4.codemp=spg_ep1.codemp
					  AND spg_ep4.codemp=spg_ep2.codemp
					  AND spg_ep4.codemp=spg_ep3.codemp					  
					  AND spg_ep4.codestpro1=spg_ep1.codestpro1
					  AND spg_ep4.codestpro1=spg_ep2.codestpro1
					  AND spg_ep4.codestpro1=spg_ep3.codestpro1					  
					  AND spg_ep4.codestpro2=spg_ep2.codestpro2
					  AND spg_ep4.codestpro2=spg_ep3.codestpro2
					  AND spg_ep4.codestpro3=spg_ep3.codestpro3
					  AND spg_ep4.estcla=spg_ep1.estcla
					ORDER BY $ls_campoorden $ls_orden";
		
		$rs_data = $io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
			 $io_mensajes->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
			 $io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		   }
		else
		   {
			 while (!$rs_data->EOF)
			       {
					 $ls_codestpro4 = substr($rs_data->fields["codestpro4"],-$li_loncodestpro4);
					 $ls_denestpro4 = rtrim(utf8_encode($rs_data->fields["denestpro4"]));
					 echo "<tr class=celdas-blancas>";
					 echo "<td style=text-align:center width=145><a href=\"javascript:uf_aceptar_codestpro4('$ls_codestpro4','$ls_denestpro4');\">".$ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3."</a></td>";
					 echo "<td style=text-align:center width=55 ><a href=\"javascript:uf_aceptar_codestpro4('$ls_codestpro4','$ls_denestpro4');\">".$ls_codestpro4."</a></td>";
					 echo "<td style=text-align:left   width=500 title='".$ls_denestpro4."'>".$ls_denestpro4."</td>";
					 echo "</tr>";
					 $rs_data->MoveNext();
				   }
		   }
		$io_sql->free_result($rs_data);
		echo "</table>";
		unset($io_include,$io_conexion,$io_sql,$io_mensajes,$io_funciones,$ls_codemp);
	}// end function uf_print_codestpro4.
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_codestpro5()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_codestpro5
		//		   Access: private
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de la Estructura Presupuestaria de Nivel 5.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 26/12/2008. 								Fecha Última Modificación : 26/12/2008.
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");
		global $li_loncodestpro1,$li_loncodestpro2,$li_loncodestpro3,$li_loncodestpro4,$li_loncodestpro5;
		
		$io_include    = new sigesp_include();
		$io_conexion   = $io_include->uf_conectar();
		$io_sql		   = new class_sql($io_conexion);	
		$io_mensajes   = new class_mensajes();		
		$io_funciones  = new class_funciones();		
		
		$ls_sqlaux     = $ls_auxsql = "";
		$ls_codemp	   = $_SESSION["la_empresa"]["codemp"];
		$ls_orden	   = $_POST['orden'];
		$ls_campoorden = $_POST['campoorden'];
		$ls_estcla	   = $_POST['estcla'];
		$ls_codestpro1 = $_POST['codestpro1'];
		$ls_codestpro2 = $_POST['codestpro2'];
		$ls_codestpro3 = $_POST['codestpro3'];
		$ls_codestpro4 = $_POST['codestpro4'];

		$ls_denestpro1 = $_POST['denestpro1'];
		$ls_denestpro2 = $_POST['denestpro2'];
		$ls_denestpro3 = $_POST['denestpro3'];
		$ls_denestpro4 = $_POST['denestpro4'];		

		$ls_codestproaux1 = $ls_codestpro1;//Variables que me permitirán saber si debo accesar al resulset para 
		$ls_codestproaux2 = $ls_codestpro2;//obtener el valor de estos campos, o solo tomarlos del POST.
		$ls_codestproaux3 = $ls_codestpro3;
		$ls_codestproaux4 = $ls_codestpro4;
		
		if (!empty($ls_codestpro1) && !empty($ls_codestpro2) && !empty($ls_codestpro3) && !empty($ls_codestpro4))
		   {
		     $ls_codestpro1 = str_pad($ls_codestpro1,25,0,0);
		     $ls_codestpro2 = str_pad($ls_codestpro2,25,0,0);
			 $ls_codestpro3 = str_pad($ls_codestpro3,25,0,0);
		     $ls_codestpro4 = str_pad($ls_codestpro4,25,0,0);

			 $ls_sqlaux     = $ls_sqlaux." AND spg_ep5.codestpro1 = '".$ls_codestpro1."' 
			                               AND spg_ep5.codestpro2 = '".$ls_codestpro2."'
										   AND spg_ep5.codestpro3 = '".$ls_codestpro3."'
										   AND spg_ep5.codestpro4 = '".$ls_codestpro4."'
										   AND spg_ep5.estcla     = '".$ls_estcla."'";
		   }
		else
		   {
		     $ls_auxsql = ", spg_ep1.codestpro1, spg_ep1.denestpro1, spg_ep1.estcla, spg_ep2.codestpro2, spg_ep2.denestpro2
			               , spg_ep3.codestpro3, spg_ep3.denestpro3, spg_ep4.codestpro4, spg_ep4.denestpro4 ";
		   }
		$ls_codestpro5 = $_POST['codestpro5'];
		if (!empty($ls_codestpro5))
		   {
			 $ls_sqlaux = $ls_sqlaux." AND spg_ep5.codestpro5 like '%".$ls_codestpro5."%'";
		   }
		$ls_denestpro5 = $_POST['denestpro5'];
		if (!empty($ls_denestpro5))
		   {
		     $ls_sqlaux = $ls_sqlaux." AND UPPER(spg_ep5.denestpro5) like '%".strtoupper($ls_denestpro5)."%'";
		   }
		
		echo "<table width=700 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		echo "<tr class=titulo-celda>";
		echo "<td width=145 align='center'>Niveles Anteriores</td>";
		echo "<td width=55  style='cursor:pointer' title='Ordenar por Nivel 5'             align='center' onClick=ue_orden('spg_ep5.codestpro5')>C&oacute;digo</td>";
		echo "<td width=500 style='cursor:pointer' title='Ordenar por Denominaci&oacute;n' align='center' onClick=ue_orden('spg_ep5.denestpro5')>Denominaci&oacute;n</td>";
		echo "</tr>";
		
		$ls_sql = "SELECT spg_ep5.codestpro5,spg_ep5.denestpro5 $ls_auxsql
		             FROM spg_ep1, spg_ep2, spg_ep3, spg_ep4, spg_ep5
				    WHERE spg_ep5.codemp = '".$ls_codemp."' $ls_sqlaux
					  AND spg_ep5.codestpro1 <> '-------------------------'					 
					  AND spg_ep5.codemp=spg_ep1.codemp
					  AND spg_ep5.codemp=spg_ep2.codemp
					  AND spg_ep5.codemp=spg_ep3.codemp
					  AND spg_ep5.codemp=spg_ep4.codemp
					  AND spg_ep5.codestpro1=spg_ep1.codestpro1
					  AND spg_ep5.codestpro1=spg_ep2.codestpro1
					  AND spg_ep5.codestpro1=spg_ep3.codestpro1
					  AND spg_ep5.codestpro1=spg_ep4.codestpro1
					  AND spg_ep5.codestpro2=spg_ep2.codestpro2
					  AND spg_ep5.codestpro2=spg_ep3.codestpro2
					  AND spg_ep5.codestpro2=spg_ep4.codestpro2
					  AND spg_ep5.codestpro3=spg_ep3.codestpro3
					  AND spg_ep5.codestpro3=spg_ep4.codestpro3
					  AND spg_ep5.codestpro4=spg_ep4.codestpro4
					  AND spg_ep3.codestpro2=spg_ep2.codestpro2					  
					  AND spg_ep5.estcla=spg_ep1.estcla
					ORDER BY $ls_campoorden $ls_orden";//echo $ls_sql.'<br>';
		
		$rs_data = $io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
			 $io_mensajes->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
			 $io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		   }
		else
		   {
			 while (!$rs_data->EOF)
			       {
					 if (empty($ls_codestproaux1) && empty($ls_codestproaux2) && empty($ls_codestproaux3) && empty($ls_codestproaux4))
					    {
						  $ls_estcla     = $rs_data->fields["estcla"];
						  $ls_codestpro1 = substr($rs_data->fields["codestpro1"],-$li_loncodestpro1);
						  $ls_denestpro1 = rtrim(utf8_encode($rs_data->fields["denestpro1"]));
						  $ls_codestpro2 = substr($rs_data->fields["codestpro2"],-$li_loncodestpro2);
						  $ls_denestpro2 = rtrim(utf8_encode($rs_data->fields["denestpro2"]));
						  $ls_codestpro3 = substr($rs_data->fields["codestpro3"],-$li_loncodestpro3);
						  $ls_denestpro3 = rtrim(utf8_encode($rs_data->fields["denestpro3"]));
						  $ls_codestpro4 = substr($rs_data->fields["codestpro4"],-$li_loncodestpro4);
						  $ls_denestpro4 = rtrim(utf8_encode($rs_data->fields["denestpro4"]));
						}					 
					 else
					    {
						  $ls_codestpro1 = substr($ls_codestpro1,-$li_loncodestpro1);
						  $ls_codestpro2 = substr($ls_codestpro2,-$li_loncodestpro2);
						  $ls_codestpro3 = substr($ls_codestpro3,-$li_loncodestpro3);
						  $ls_codestpro4 = substr($ls_codestpro4,-$li_loncodestpro4);
						}
					 $ls_codestpro5 = substr($rs_data->fields["codestpro5"],-$li_loncodestpro5);
					 $ls_denestpro5 = rtrim(utf8_encode($rs_data->fields["denestpro5"]));
					 echo "<tr class=celdas-blancas>";
					 echo "<td style=text-align:center width=145><a href=\"javascript:uf_aceptar_codestpro5('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5','$ls_estcla');\">".$ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3.'-'.$ls_codestpro4."</a></td>";
					 echo "<td style=text-align:center width=55 ><a href=\"javascript:uf_aceptar_codestpro5('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5','$ls_estcla');\">".$ls_codestpro5."</a></td>";
					 echo "<td style=text-align:left   width=500 title='".$ls_denestpro5."'>".$ls_denestpro5."</td>";
					 echo "</tr>";
					 $rs_data->MoveNext();
				   }
		   }
		$io_sql->free_result($rs_data);
		echo "</table>";
		unset($io_include,$io_conexion,$io_sql,$io_mensajes,$io_funciones,$ls_codemp);
	}// end function uf_print_codestpro5.
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_fuentes()
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_fuentes
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de la Estructura Presupuestaria de Nivel 1.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 23/12/2008. 								Fecha Última Modificación : 23/12/2008.
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");
		
		$io_include    = new sigesp_include();
		$io_conexion   = $io_include->uf_conectar();
		$io_sql		   = new class_sql($io_conexion);	
		$io_mensajes   = new class_mensajes();		
		$io_funciones  = new class_funciones();		
		
		$ls_sqlaux 	   = "";
		$ls_codemp	   = $_SESSION["la_empresa"]["codemp"];
		$ls_orden	   = $_POST['orden'];
		$ls_campoorden = $_POST['campoorden'];
		$ls_codfuefin  = $_POST['codfuefin'];
		if (!empty($ls_codfuefin))
		   {
			 $ls_sqlaux = " AND codfuefin like '%".$ls_codfuefin."%'";
		   }
		$ls_denfuefin = $_POST['denfuefin'];
		if (!empty($ls_denfuefin))
		   {
		     $ls_sqlaux = $ls_sqlaux." AND UPPER(denfuefin) like '%".strtoupper($ls_denfuefin)."%'";
		   }
		echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		echo "<tr class=titulo-celda>";
		echo "<td width=100 style='cursor:pointer' title='Ordenar por C&oacute;digo'       align='center' onClick=ue_orden('codfuefin')>C&oacute;digo</td>";
		echo "<td width=400 style='cursor:pointer' title='Ordenar por Denominaci&oacute;n' align='center' onClick=ue_orden('denfuefin')>Denominaci&oacute;n</td>";
		echo "</tr>";
		
		$ls_sql = "SELECT codfuefin,denfuefin
		             FROM sigesp_fuentefinanciamiento
				    WHERE codemp = '".$ls_codemp."'					  
					  AND codfuefin <> '--' $ls_sqlaux
					ORDER BY $ls_campoorden $ls_orden";
		
		$rs_data = $io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
			 $io_mensajes->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
			 $io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		   }
		else
		   {
			 while (!$rs_data->EOF)
			       {
					 $ls_codfuefin = $rs_data->fields["codfuefin"];
					 $ls_denfuefin = utf8_encode($rs_data->fields["denfuefin"]);
					 echo "<tr class=celdas-blancas>";
					 echo "<td style=text-align:center width=100><a href=\"javascript:uf_aceptar_fuente_financiamiento('$ls_codfuefin','$ls_denfuefin');\">".$ls_codfuefin."</a></td>";
					 echo "<td style=text-align:left   width=400 title='".$ls_denfuefin."'>".$ls_denfuefin."</td>";
				  	 echo "</tr>";
					 $rs_data->MoveNext();
				   }
		   }
		$io_sql->free_result($rs_data);
		echo "</table>";
		unset($io_include,$io_conexion,$io_sql,$io_mensajes,$io_funciones,$ls_codemp);
	}// end function uf_print_fuentes.
	//-----------------------------------------------------------------------------------------------------------------------------------

?>