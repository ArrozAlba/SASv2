<?php
	//-----------------------------------------------------------------------------------------------------------------------------------
	// Clase donde se cargan todos los catálogos del sistema APR con la utilización del AJAX
	//-----------------------------------------------------------------------------------------------------------------------------------
    session_start();   
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("class_funciones_apr.php");
	$io_funciones_apr=new class_funciones_apr();
	// Tipo del catalogo que se requiere pintar
	$ls_catalogo=$io_funciones_apr->uf_obtenervalor("catalogo",""); 
	switch($ls_catalogo)
	{
		case "ESTRUCTURA1":
			uf_print_estructura1();
			break;
		case "ESTRUCTURA2":
			uf_print_estructura2();
			break;
		case "ESTRUCTURA3":
			uf_print_estructura3();
			break;
		case "ESTRUCTURA4":
			uf_print_estructura4();
			break;
		case "ESTRUCTURA5":
			uf_print_estructura5();
			break;
		case "CUENTASSPG":
			uf_print_cuentasspg();
			break;
		case "CUENTASSCG":
			uf_print_cuentasscg();
			break;
		case "CUENTASSPGDESTINO":
			uf_print_cuentasspgdestino();
			break;
		case "ESTRUCTURAS":
			uf_print_estructuras();
			break;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_estructura1()
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_estructura1
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de la estructura presupuestaria 1
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 06/04/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_apr;
		
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		$ls_database_destino= $_SESSION["ls_data_des"];
		$io_conexion_destino= $io_include->uf_conectar($ls_database_destino);
		$io_sql_destino= new class_sql($io_conexion_destino);
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_codestpro1="%".$_POST['codestpro1']."%";
		$ls_denestpro1="%".$_POST['denestpro1']."%";
		$ls_tipo=$_POST['tipo'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$io_funciones_apr->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
		$ls_sql="SELECT codestpro1, denestpro1 ".
				"  FROM spg_ep1 ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND codestpro1 like '".$ls_codestpro1."' ".
				"   AND denestpro1 like '".$ls_denestpro1."' ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql_destino->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Estructura ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td style='cursor:pointer' title='Ordenar por Código' align='center' onClick=ue_orden('codestpro1')>".utf8_encode("Código")." </td>";
			print "<td style='cursor:pointer' title='Ordenar por Denominación' align='center' onClick=ue_orden('denestpro1')>".utf8_encode("Denominación")."</td>";
			print "</tr>";
			while($row=$io_sql_destino->fetch_row($rs_data))
			{
				$ls_codestpro1=substr($row["codestpro1"],(strlen($row["codestpro1"])-$li_len1),$li_len1);
				$ls_denestpro1=utf8_encode(rtrim($row["denestpro1"]));
				switch($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro1');\">".$ls_codestpro1."</a></td>";
						print "<td>".$ls_denestpro1."</td>";
						print "</tr>";			
						break;
				}
			}
			$io_sql_destino->free_result($rs_data);
			print "</table>";
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql_destino);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_estructura1
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_estructura2()
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_estructura2
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de la estructura presupuestaria 2
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 06/04/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_apr;
		
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		$ls_database_destino= $_SESSION["ls_data_des"];
		$io_conexion_destino= $io_include->uf_conectar($ls_database_destino);
		$io_sql_destino= new class_sql($io_conexion_destino);
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_codestpro1=$_POST['codestpro1'];
		$ls_codestpro2="%".$_POST['codestpro2']."%";
		$ls_denestpro2="%".$_POST['denestpro2']."%";
		$ls_tipo=$_POST['tipo'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$io_funciones_apr->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
		$ls_sql="SELECT codestpro1, codestpro2, denestpro2 ".
				"  FROM spg_ep2 ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND codestpro1 ='".str_pad($ls_codestpro1,20,"0",0)."' ".
				"   AND codestpro2 like '".$ls_codestpro2."' ".
				"   AND denestpro2 like '".$ls_denestpro2."' ".
				" ORDER BY codestpro1, ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql_destino->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Estructura ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td width=100 align='center'>".utf8_encode($_SESSION["la_empresa"]["nomestpro1"])." </td>";
			print "<td width=150 style='cursor:pointer' title='Ordenar por Código' align='center' onClick=ue_orden('codestpro2')>".utf8_encode("Código")." </td>";
			print "<td width=250 style='cursor:pointer' title='Ordenar por Denominación' align='center' onClick=ue_orden('denestpro2')>".utf8_encode("Denominación")."</td>";
			print "</tr>";
			while($row=$io_sql_destino->fetch_row($rs_data))
			{
				$ls_codestpro1=substr($row["codestpro1"],(strlen($row["codestpro1"])-$li_len1),$li_len1);
				$ls_codestpro2=substr($row["codestpro2"],(strlen($row["codestpro2"])-$li_len2),$li_len2);
				$ls_denestpro2=utf8_encode(rtrim($row["denestpro2"]));
				switch($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro2','$ls_denestpro2');\">".trim($ls_codestpro1)."</td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro2)."</td>";
						print "<td width=130 align=\"left\">".trim($ls_denestpro2)."</td>";
						print "</tr>";
						break;
				}
			}
			$io_sql_destino->free_result($rs_data);
			print "</table>";
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql_destino);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_estructura2
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_estructura3()
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_estructura3
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de la estructura presupuestaria 3
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 06/04/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_apr;
		
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		$ls_database_destino= $_SESSION["ls_data_des"];
		$io_conexion_destino= $io_include->uf_conectar($ls_database_destino);
		$io_sql_destino= new class_sql($io_conexion_destino);
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_codestpro1=$_POST['codestpro1'];
		$ls_codestpro2=$_POST['codestpro2'];
		$ls_criterio="";
		if($ls_codestpro1!="")
		{
			$ls_criterio=$ls_criterio."   AND spg_ep3.codestpro1 ='".str_pad($ls_codestpro1,20,"0",0)."' ";
		}
		if($ls_codestpro2!="")
		{
			$ls_criterio=$ls_criterio."   AND spg_ep3.codestpro2 ='".str_pad($ls_codestpro2,6,"0",0)."' ";
		}
		$ls_codestpro3="%".$_POST['codestpro3']."%";
		$ls_denestpro3="%".$_POST['denestpro3']."%";
		$ls_tipo=$_POST['tipo'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$io_funciones_apr->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
		$ls_sql="SELECT spg_ep3.codestpro1, spg_ep3.codestpro2, spg_ep3.codestpro3, spg_ep3.denestpro3,".
				"       spg_ep1.denestpro1,spg_ep2.denestpro2 ".
				"  FROM spg_ep3,spg_ep2,spg_ep1 ".
				" WHERE spg_ep3.codemp='".$ls_codemp."' ".
				$ls_criterio.
				"   AND spg_ep3.codestpro3 like '".$ls_codestpro3."' ".
				"   AND spg_ep3.denestpro3 like '".$ls_denestpro3."' ".
				"   AND spg_ep1.codemp=spg_ep3.codemp".
				"   AND spg_ep1.codestpro1=spg_ep3.codestpro1".
				"   AND spg_ep2.codemp=spg_ep3.codemp".
				"   AND spg_ep2.codestpro1=spg_ep3.codestpro1".
				"   AND spg_ep2.codestpro2=spg_ep3.codestpro2".
				" ORDER BY spg_ep3.codestpro1, spg_ep3.codestpro2, ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql_destino->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Estructura ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td width=100 align='center'>".utf8_encode($_SESSION["la_empresa"]["nomestpro1"])." </td>";
			print "<td width=100 align='center'>".utf8_encode($_SESSION["la_empresa"]["nomestpro2"])." </td>";
			print "<td width=100 style='cursor:pointer' title='Ordenar por Código' align='center' onClick=ue_orden('spg_ep3.codestpro3')>".utf8_encode("Código")." </td>";
			print "<td width=200 style='cursor:pointer' title='Ordenar por Denominación' align='center' onClick=ue_orden('spg_ep3.denestpro3')>".utf8_encode("Denominación")."</td>";
			print "</tr>";
			while($row=$io_sql_destino->fetch_row($rs_data))
			{
				$ls_codestpro1=substr($row["codestpro1"],(strlen($row["codestpro1"])-$li_len1),$li_len1);
				$ls_codestpro2=substr($row["codestpro2"],(strlen($row["codestpro2"])-$li_len2),$li_len2);
				$ls_codestpro3=substr($row["codestpro3"],(strlen($row["codestpro3"])-$li_len3),$li_len3);
				$ls_denestpro1=utf8_encode(rtrim($row["denestpro1"]));
				$ls_denestpro2=utf8_encode(rtrim($row["denestpro2"]));
				$ls_denestpro3=utf8_encode(rtrim($row["denestpro3"]));
				switch($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_denestpro1','$ls_denestpro2','$ls_denestpro3');\">".trim($ls_codestpro1)."</td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro2)."</td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro3)."</a></td>";
						print "<td width=130 align=\"left\">".$ls_denestpro3."</td>";
						print "</tr>";			
						break;
				}
			}
			$io_sql_destino->free_result($rs_data);
			print "</table>";
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql_destino);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_estructura3
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_estructura4()
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_estructura4
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de la estructura presupuestaria 4
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 07/04/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_apr;
		
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		$ls_database_destino= $_SESSION["ls_data_des"];
		$io_conexion_destino= $io_include->uf_conectar($ls_database_destino);
		$io_sql_destino= new class_sql($io_conexion_destino);
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_codestpro1=$_POST['codestpro1'];
		$ls_codestpro2=$_POST['codestpro2'];
		$ls_codestpro3=$_POST['codestpro3'];
		$ls_codestpro4="%".$_POST['codestpro4']."%";
		$ls_denestpro4="%".$_POST['denestpro4']."%";
		$ls_tipo=$_POST['tipo'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$io_funciones_apr->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
		$ls_sql="SELECT codestpro1,codestpro2,codestpro3,codestpro4,denestpro4 ".
				"  FROM spg_ep4 ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND codestpro1 ='".str_pad($ls_codestpro1,20,"0",0)."' ".
				"   AND codestpro2 ='".str_pad($ls_codestpro2,6,"0",0)."' ".
				"   AND codestpro3 ='".str_pad($ls_codestpro3,3,"0",0)."' ".
				"   AND codestpro4 like '".$ls_codestpro4."' ".
				"   AND denestpro4 like '".$ls_denestpro4."' ".
				" ORDER BY codestpro1,codestpro2,codestpro3, ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql_destino->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Estructura ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td width=80 align='center'>".utf8_encode($_SESSION["la_empresa"]["nomestpro1"])." </td>";
			print "<td width=80 align='center'>".utf8_encode($_SESSION["la_empresa"]["nomestpro2"])." </td>";
			print "<td width=80 align='center'>".utf8_encode($_SESSION["la_empresa"]["nomestpro3"])." </td>";
			print "<td width=80 style='cursor:pointer' title='Ordenar por Código' align='center' onClick=ue_orden('codestpro4')>".utf8_encode("Código")." </td>";
			print "<td width=180 style='cursor:pointer' title='Ordenar por Denominación' align='center' onClick=ue_orden('denestpro4')>".utf8_encode("Denominación")."</td>";
			print "</tr>";
			while($row=$io_sql_destino->fetch_row($rs_data))
			{
				$ls_codestpro1=substr($row["codestpro1"],(strlen($row["codestpro1"])-$li_len1),$li_len1);
				$ls_codestpro2=substr($row["codestpro2"],(strlen($row["codestpro2"])-$li_len2),$li_len2);
				$ls_codestpro3=substr($row["codestpro3"],(strlen($row["codestpro3"])-$li_len3),$li_len3);
				$ls_codestpro4=substr($row["codestpro4"],(strlen($row["codestpro4"])-$li_len4),$li_len4);
				$ls_denestpro4=rtrim(utf8_encode($row["denestpro4"]));
				switch($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro4','$ls_denestpro4');\">".trim($ls_codestpro1)."</td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro2)."</td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro3)."</a></td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro4)."</a></td>";
						print "<td width=130 align=\"left\">".$ls_denestpro4."</td>";
						print "</tr>";			
						break;

				}
			}
			$io_sql_destino->free_result($rs_data);
			print "</table>";
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql_destino);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_estructura4
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_estructura5()
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_estructura5
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de la estructura presupuestaria 5
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 07/04/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_apr;
		
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		$ls_database_destino= $_SESSION["ls_data_des"];
		$io_conexion_destino= $io_include->uf_conectar($ls_database_destino);
		$io_sql_destino= new class_sql($io_conexion_destino);
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_codestpro1=$_POST['codestpro1'];
		$ls_codestpro2=$_POST['codestpro2'];
		$ls_codestpro3=$_POST['codestpro3'];
		$ls_codestpro4=$_POST['codestpro4'];
		$ls_criterio="";
		if($ls_codestpro1!="")
		{
			$ls_criterio=$ls_criterio."   AND spg_ep5.codestpro1 ='".str_pad($ls_codestpro1,20,"0",0)."' ";
		}
		if($ls_codestpro2!="")
		{
			$ls_criterio=$ls_criterio."   AND spg_ep5.codestpro2 ='".str_pad($ls_codestpro2,6,"0",0)."' ";
		}
		if($ls_codestpro3!="")
		{
			$ls_criterio=$ls_criterio."   AND spg_ep5.codestpro3 ='".str_pad($ls_codestpro3,3,"0",0)."' ";
		}
		if($ls_codestpro4!="")
		{
			$ls_criterio=$ls_criterio."   AND spg_ep5.codestpro4 ='".str_pad($ls_codestpro4,2,"0",0)."' ";
		}
		$ls_codestpro5="%".$_POST['codestpro5']."%";
		$ls_denestpro5="%".$_POST['denestpro5']."%";
		$ls_tipo=$_POST['tipo'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$io_funciones_apr->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
		$ls_sql="SELECT spg_ep5.codestpro1, spg_ep5.codestpro2, spg_ep5.codestpro3, spg_ep5.codestpro4, spg_ep5.codestpro5, ".
				"		spg_ep1.denestpro1, spg_ep2.denestpro2, spg_ep3.denestpro3, spg_ep4.denestpro4, spg_ep5.denestpro5 ".
				"  FROM spg_ep1, spg_ep2, spg_ep3, spg_ep4, spg_ep5 ".
				" WHERE spg_ep5.codemp='".$ls_codemp."' ".
				$ls_criterio.
				"   AND spg_ep5.codestpro5 like '".$ls_codestpro5."' ".
				"   AND spg_ep5.denestpro5 like '".$ls_denestpro5."' ".
				"   AND spg_ep5.codemp = spg_ep1.codemp ".
				"   AND spg_ep5.codestpro1 = spg_ep1.codestpro1 ".
				"   AND spg_ep5.codemp = spg_ep2.codemp ".
				"   AND spg_ep5.codestpro1 = spg_ep2.codestpro1 ".
				"   AND spg_ep5.codestpro2 = spg_ep2.codestpro2 ".
				"   AND spg_ep5.codemp = spg_ep3.codemp ".
				"   AND spg_ep5.codestpro1 = spg_ep3.codestpro1 ".
				"   AND spg_ep5.codestpro2 = spg_ep3.codestpro2 ".
				"   AND spg_ep5.codestpro3 = spg_ep3.codestpro3 ".
				"   AND spg_ep5.codemp = spg_ep4.codemp ".
				"   AND spg_ep5.codestpro1 = spg_ep4.codestpro1 ".
				"   AND spg_ep5.codestpro2 = spg_ep4.codestpro2 ".
				"   AND spg_ep5.codestpro3 = spg_ep4.codestpro3 ".
				"   AND spg_ep5.codestpro4 = spg_ep4.codestpro4 ".				
				" ORDER BY spg_ep5.codestpro1, spg_ep5.codestpro2, spg_ep5.codestpro3, spg_ep5.codestpro4, ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql_destino->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Estructura ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td width=70 align='center'>".utf8_encode($_SESSION["la_empresa"]["nomestpro1"])." </td>";
			print "<td width=70 align='center'>".utf8_encode($_SESSION["la_empresa"]["nomestpro2"])." </td>";
			print "<td width=70 align='center'>".utf8_encode($_SESSION["la_empresa"]["nomestpro3"])." </td>";
			print "<td width=70 align='center'>".utf8_encode($_SESSION["la_empresa"]["nomestpro4"])." </td>";
			print "<td width=70 style='cursor:pointer' title='Ordenar por Código' align='center' onClick=ue_orden('spg_ep5.codestpro5')>".utf8_encode("Código")." </td>";
			print "<td width=150 style='cursor:pointer' title='Ordenar por Denominación' align='center' onClick=ue_orden('spg_ep5.denestpro5')>".utf8_encode("Denominación")."</td>";
			print "</tr>";
			while($row=$io_sql_destino->fetch_row($rs_data))
			{
				$ls_codestpro1=substr($row["codestpro1"],(strlen($row["codestpro1"])-$li_len1),$li_len1);
				$ls_codestpro2=substr($row["codestpro2"],(strlen($row["codestpro2"])-$li_len2),$li_len2);
				$ls_codestpro3=substr($row["codestpro3"],(strlen($row["codestpro3"])-$li_len3),$li_len3);
				$ls_codestpro4=substr($row["codestpro4"],(strlen($row["codestpro4"])-$li_len4),$li_len4);
				$ls_codestpro5=substr($row["codestpro5"],(strlen($row["codestpro5"])-$li_len5),$li_len5);
				$ls_denestpro1=rtrim(utf8_encode($row["denestpro1"]));
				$ls_denestpro2=rtrim(utf8_encode($row["denestpro2"]));
				$ls_denestpro3=rtrim(utf8_encode($row["denestpro3"]));
				$ls_denestpro4=rtrim(utf8_encode($row["denestpro4"]));
				$ls_denestpro5=rtrim(utf8_encode($row["denestpro5"]));
				switch($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro1','$ls_codestpro2',";
						print "'$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_denestpro1','$ls_denestpro2','$ls_denestpro3',";
						print "'$ls_denestpro4','$ls_denestpro5');\">".trim($ls_codestpro1)."</td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro2)."</td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro3)."</a></td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro4)."</a></td>";
						print "<td width=30 align=\"center\">".trim($ls_codestpro5)."</a></td>";
						print "<td width=130 align=\"left\">".$ls_denestpro5."</td>";
						print "</tr>";			
						break;
				}
			}
			$io_sql_destino->free_result($rs_data);
			print "</table>";
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql_destino);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_estructura5
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
		// Fecha Creación: 07/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_apr;
		
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		$ls_database_destino= $_SESSION["ls_data_des"];
		$io_conexion_destino= $io_include->uf_conectar($ls_database_destino);
		$io_sql_destino= new class_sql($io_conexion_destino);
		$ls_spgcuenta="%".$_POST['spgcuenta']."%";
		$ls_dencue="%".$_POST['dencue']."%";
		$ls_codestpro1=str_pad($_POST['codestpro1'],20,0,0);
		$ls_codestpro2=str_pad($_POST['codestpro2'],6,0,0);
		$ls_codestpro3=str_pad($_POST['codestpro3'],3,0,0);
		$ls_codestpro4=str_pad($_POST['codestpro4'],2,0,0);
		$ls_codestpro5=str_pad($_POST['codestpro5'],2,0,0);
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		if($ls_campoorden=="codpro")
		{
			$ls_campoorden= "codestpro1,codestpro2,codestpro3,codestpro4,codestpro5";
		}
		$io_funciones_apr->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
		$ls_cuentas="";
		$ls_tipocuenta="";
		$ls_sql="SELECT TRIM(spg_cuenta) AS spg_cuenta , denominacion, codestpro1,codestpro2, codestpro3, codestpro4, codestpro5, status, ".
				"       (asignado-(comprometido+precomprometido)+aumento-disminucion) as disponible, sc_cuenta ".
			    "  FROM spg_cuentas ".
				" WHERE codemp = '".$ls_codemp."'  ".
				"	AND codestpro1 = '".$ls_codestpro1."' ".
				"	AND codestpro2 = '".$ls_codestpro2."' ".
				"	AND codestpro3 = '".$ls_codestpro3."' ".
				"	AND codestpro4 = '".$ls_codestpro4."' ".
				"	AND codestpro5 = '".$ls_codestpro5."' ".
				"	AND spg_cuenta like '411".$ls_spgcuenta."' ".
				"   AND denominacion like '".$ls_dencue."' ".								
				" ORDER BY ".$ls_campoorden." ".$ls_orden." ";
		$rs_data=$io_sql_destino->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Cuentas Presupuestarias ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=580 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td width=100 style='cursor:pointer' title='Ordenar por Programatica'          align='center' onClick=ue_orden('codpro')>".$ls_titulo."</td>";
			print "<td width=100 style='cursor:pointer' title='Ordenar por Cuenta Presupuestaria' align='center' onClick=ue_orden('spg_cuenta')>Presupuestaria</td>";
			print "<td width=100 style='cursor:pointer' title='Ordenar por Cuenta Contable'       align='center' onClick=ue_orden('sc_cuenta')>Contable</td>";
			print "<td width=180 style='cursor:pointer' title='Ordenar por Denominacion'          align='center' onClick=ue_orden('denominacion')>Denominacion</td>";
			print "<td width=100 style='cursor:pointer' title='Ordenar por Disponible'            align='center' onClick=ue_orden('disponible')>Disponible</td>";
			print "</tr>";
			while($row=$io_sql_destino->fetch_row($rs_data))
			{
				$ls_spg_cuenta=trim($row["spg_cuenta"]);
				$ls_sccuenta=trim($row["sc_cuenta"]);
				$ls_status=trim($row["status"]);
				$ls_denominacion=utf8_encode(rtrim($row["denominacion"]));
				$ls_codestpro=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
				$li_disponible=number_format($row["disponible"],2,",",".");
				$ls_programatica="";
				$io_funciones_apr->uf_formatoprogramatica($ls_codestpro,&$ls_programatica);
				if($ls_status=="C")
				{
					print "<tr class=celdas-azules>";
					print "<td align='center'><a href=\"javascript: ue_aceptar('".$ls_spg_cuenta."','".$ls_denominacion."','".$ls_sccuenta."','".$li_disponible."');\">".$ls_programatica."</a></td>";
					print "<td align='center'>".$ls_spg_cuenta."</td>";
					print "<td align='center'>".$ls_sccuenta."</td>";
					print "<td align='left'>".$ls_denominacion."</td>";
					print "<td align='right'>".$li_disponible."</td>";
					print "</tr>";			
				}
				else
				{
					print "<tr class=celdas-blancas>";
					print "<td align='center'>".$ls_programatica."</td>";
					print "<td align='center'>".$ls_spg_cuenta."</td>";
					print "<td align='center'>".$ls_sccuenta."</td>";
					print "<td align='left'>".$ls_denominacion."</td>";
					print "<td align='right'>".$li_disponible."</td>";
					print "</tr>";			
				}
			}
			$io_sql_destino->free_result($rs_data);
			print "</table>";
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql_destino);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_cuentasspg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentasspgdestino()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentasspgdestino
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que inprime el resultado de la busqueda de las cuentas presupuestarias
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 07/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_apr;
		
		$ls_database_target = $_SESSION["ls_data_des"];
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar($ls_database_target);
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		$ls_spgcuenta="%".$_POST['spgcuenta']."%";
		$ls_dencue="%".$_POST['dencue']."%";
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		if($ls_campoorden=="codpro")
		{
			$ls_campoorden= "codestpro1,codestpro2,codestpro3,codestpro4,codestpro5";
		}
		$ls_cuentas="";
		$ls_tipocuenta="";
		$ls_sql="SELECT TRIM(spg_cuenta) AS spg_cuenta , denominacion, status, sc_cuenta ".
			    "  FROM spg_cuentas ".
				" WHERE codemp = '".$ls_codemp."'  ".
				"	AND spg_cuenta like '".$ls_spgcuenta."' ".
				"   AND denominacion like '".$ls_dencue."' ".								
				" ORDER BY ".$ls_campoorden." ".$ls_orden." ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Cuentas Presupuestarias ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=580 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td width=100 style='cursor:pointer' title='Ordenar por Cuenta Presupuestaria' align='center' onClick=ue_orden('spg_cuenta')>Presupuestaria</td>";
			print "<td width=100 style='cursor:pointer' title='Ordenar por Cuenta Contable'       align='center' onClick=ue_orden('sc_cuenta')>Contable</td>";
			print "<td width=180 style='cursor:pointer' title='Ordenar por Denominacion'          align='center' onClick=ue_orden('denominacion')>Denominacion</td>";
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_spg_cuenta=trim($row["spg_cuenta"]);
				$ls_sccuenta=trim($row["sc_cuenta"]);
				$ls_status=trim($row["status"]);
				$ls_denominacion=utf8_encode(rtrim($row["denominacion"]));
				if($ls_status=="C")
				{
					print "<tr class=celdas-azules>";
					print "<td align='center'><a href=\"javascript: ue_aceptar('".$ls_spg_cuenta."');\">".$ls_spg_cuenta."</a></td>";
					print "<td align='center'>".$ls_sccuenta."</td>";
					print "<td align='left'>".$ls_denominacion."</td>";
					print "</tr>";			
				}
				else
				{
					print "<tr class=celdas-blancas>";
					print "<td align='center'>".$ls_spg_cuenta."</td>";
					print "<td align='center'>".$ls_sccuenta."</td>";
					print "<td align='left'>".$ls_denominacion."</td>";
					print "</tr>";			
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
	}// end function uf_print_cuentasspgdestino
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentasscg()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentasscg
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que inprime el resultado de la busqueda de las cuentas contables
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 16/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_apr;
		
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		$ls_scgcuenta="%".$_POST['scgcuenta']."%";
		$ls_dencue="%".$_POST['dencue']."%";
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_sql="SELECT sc_cuenta, denominacion, status ".
			    "  FROM scg_cuentas ".
				" WHERE codemp = '".$ls_codemp."'  ".
				"	AND sc_cuenta like '".$ls_scgcuenta."' ".
				"   AND denominacion like '".$ls_dencue."' ".								
				" ORDER BY ".$ls_campoorden." ".$ls_orden." ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Cuentas Contables ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=580 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td width=100 style='cursor:pointer' title='Ordenar por Cuenta Contable' align='center' onClick=ue_orden('sc_cuenta')>Cuenta Contable</td>";
			print "<td width=400 style='cursor:pointer' title='Ordenar por Denominacion'    align='center' onClick=ue_orden('denominacion')>Denominacion</td>";
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_sccuenta=trim($row["sc_cuenta"]);
				$ls_status=trim($row["status"]);
				$ls_denominacion=utf8_encode(rtrim($row["denominacion"]));
				if($ls_status=="C")
				{
					print "<tr class=celdas-azules>";
					print "<td align='center'><a href=\"javascript: ue_aceptar('".$ls_sccuenta."','".$ls_denominacion."');\">".$ls_sccuenta."</a></td>";
					print "<td align='left'>".$ls_denominacion."</td>";
					print "</tr>";			
				}
				else
				{
					print "<tr class=celdas-blancas>";
					print "<td align='center'>".$ls_sccuenta."</td>";
					print "<td align='left'>".$ls_denominacion."</td>";
					print "</tr>";			
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
	}// end function uf_print_cuentasscg
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_estructuras()
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_estructuras
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de la estructura presupuestaria 5
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 07/04/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_apr;
		
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
		$ls_criterio="";
		$ls_tipo=$_POST['tipo'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$io_funciones_apr->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
		$ls_sql="SELECT spg_ep5.codestpro1, spg_ep5.codestpro2, spg_ep5.codestpro3, spg_ep5.codestpro4, spg_ep5.codestpro5, ".
				"		spg_ep1.denestpro1, spg_ep2.denestpro2, spg_ep3.denestpro3, spg_ep4.denestpro4, spg_ep5.denestpro5 ".
				"  FROM spg_ep1, spg_ep2, spg_ep3, spg_ep4, spg_ep5 ".
				" WHERE spg_ep5.codemp='".$ls_codemp."' ".
				"   AND spg_ep5.codemp = spg_ep1.codemp ".
				"   AND spg_ep5.codestpro1 = spg_ep1.codestpro1 ".
				"   AND spg_ep5.codemp = spg_ep2.codemp ".
				"   AND spg_ep5.codestpro1 = spg_ep2.codestpro1 ".
				"   AND spg_ep5.codestpro2 = spg_ep2.codestpro2 ".
				"   AND spg_ep5.codemp = spg_ep3.codemp ".
				"   AND spg_ep5.codestpro1 = spg_ep3.codestpro1 ".
				"   AND spg_ep5.codestpro2 = spg_ep3.codestpro2 ".
				"   AND spg_ep5.codestpro3 = spg_ep3.codestpro3 ".
				"   AND spg_ep5.codemp = spg_ep4.codemp ".
				"   AND spg_ep5.codestpro1 = spg_ep4.codestpro1 ".
				"   AND spg_ep5.codestpro2 = spg_ep4.codestpro2 ".
				"   AND spg_ep5.codestpro3 = spg_ep4.codestpro3 ".
				"   AND spg_ep5.codestpro4 = spg_ep4.codestpro4 ".				
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Estructura ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=580 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
			switch($ls_modalidad)
			{
				case "1": // Modalidad por Proyecto
					print "<td width=150 style='cursor:pointer' title='Ordenar por Estructura 1' align='center' onClick=ue_orden('spg_ep5.codestpro1')>".utf8_encode("Estructura Nivel 1")." </td>";
					print "<td width=150 style='cursor:pointer' title='Ordenar por Estructura 2' align='center' onClick=ue_orden('spg_ep5.codestpro2')>".utf8_encode("Estructura Nivel 2")." </td>";
					print "<td width=150 style='cursor:pointer' title='Ordenar por Estructura 3' align='center' onClick=ue_orden('spg_ep5.codestpro3')>".utf8_encode("Estructura Nivel 3")." </td>";
					break;
	
				case "2": // Modalidad por Programa
					print "<td width=150 style='cursor:pointer' title='Ordenar por Estructura 1' align='center' onClick=ue_orden('spg_ep5.codestpro1')>".utf8_encode("Estructura Nivel 1")." </td>";
					print "<td width=150 style='cursor:pointer' title='Ordenar por Estructura 2' align='center' onClick=ue_orden('spg_ep5.codestpro2')>".utf8_encode("Estructura Nivel 2")." </td>";
					print "<td width=150 style='cursor:pointer' title='Ordenar por Estructura 3' align='center' onClick=ue_orden('spg_ep5.codestpro3')>".utf8_encode("Estructura Nivel 3")." </td>";
					print "<td width=150 style='cursor:pointer' title='Ordenar por Estructura 4' align='center' onClick=ue_orden('spg_ep5.codestpro4')>".utf8_encode("Estructura Nivel 4")." </td>";
					print "<td width=150 style='cursor:pointer' title='Ordenar por Estructura 5' align='center' onClick=ue_orden('spg_ep5.codestpro5')>".utf8_encode("Estructura Nivel 5")." </td>";
					break;
			}
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codestpro1=substr($row["codestpro1"],(strlen($row["codestpro1"])-$li_len1),$li_len1);
				$ls_codestpro2=substr($row["codestpro2"],(strlen($row["codestpro2"])-$li_len2),$li_len2);
				$ls_codestpro3=substr($row["codestpro3"],(strlen($row["codestpro3"])-$li_len3),$li_len3);
				$ls_codestpro4=substr($row["codestpro4"],(strlen($row["codestpro4"])-$li_len4),$li_len4);
				$ls_codestpro5=substr($row["codestpro5"],(strlen($row["codestpro5"])-$li_len5),$li_len5);
				$ls_denestpro1=rtrim(utf8_encode($row["denestpro1"]));
				$ls_denestpro2=rtrim(utf8_encode($row["denestpro2"]));
				$ls_denestpro3=rtrim(utf8_encode($row["denestpro3"]));
				$ls_denestpro4=rtrim(utf8_encode($row["denestpro4"]));
				$ls_denestpro5=rtrim(utf8_encode($row["denestpro5"]));
				$io_funciones_apr->uf_formatoprogramatica($ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5,&$ls_programatica);
				switch($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td width=150 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro1','$ls_codestpro2',";
						print "'$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_programatica');\">".trim($ls_codestpro1)."</td>";
						switch($ls_modalidad)
						{
							case "1": // Modalidad por Proyecto
								print "<td width=150 align=\"center\">".trim($ls_codestpro2)."</td>";
								print "<td width=150 align=\"center\">".trim($ls_codestpro3)."</a></td>";
								break;
				
							case "2": // Modalidad por Programa
								print "<td width=150 align=\"center\">".trim($ls_codestpro2)."</td>";
								print "<td width=150 align=\"center\">".trim($ls_codestpro3)."</a></td>";
								print "<td width=150 align=\"center\">".trim($ls_codestpro4)."</a></td>";
								print "<td width=150 align=\"center\">".trim($ls_codestpro5)."</a></td>";
								print "</tr>";			
								break;
						}
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
	}// end function uf_print_estructuras
	//-----------------------------------------------------------------------------------------------------------------------------------

?>