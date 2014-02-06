<?php
	session_start(); 
	require_once("../../../shared/class_folder/grid_param.php");
	require_once("../../../shared/class_folder/sigesp_include.php");
	require_once("../../../shared/class_folder/class_sql.php");
	$in         = new sigesp_include();
	$con        = $in->uf_conectar();
	$io_sql     = new class_sql($con);
	$io_grid=new grid_param();
	require_once("../../class_folder/class_funciones_configuracion.php");
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
	$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
	
	$ls_nomestpro1         = $_SESSION["la_empresa"]["nomestpro1"];
	$ls_nomestpro2         = $_SESSION["la_empresa"]["nomestpro2"];
	$ls_nomestpro3         = $_SESSION["la_empresa"]["nomestpro3"];
	$ls_nomestpro4         = $_SESSION["la_empresa"]["nomestpro4"];
	$ls_nomestpro5         = $_SESSION["la_empresa"]["nomestpro5"];
	$io_funciones_configuracion=new class_funciones_configuracion();
	
	// proceso a ejecutar
	$ls_proceso=$io_funciones_configuracion->uf_obtenervalor("proceso","");
	// total de filas de las estructuras 
	$li_totrowestpro=$io_funciones_configuracion->uf_obtenervalor("totrowestpro","1");
	// operacion a ejecutar
	$ls_operacion=$io_funciones_configuracion->uf_obtenervalor("operacion","");
	$ls_titulo="";
	switch($ls_proceso)
	{
		case "LIMPIAR":
			switch($ls_operacion)
			{
				case "imprimir_grid": // Estructuras
					$ls_titulo="Bien o Material";
					uf_print_estructuras($li_totrowestpro,$li_estmodest);
				break;				
			}
		break;
		
		case "AGREGARESTPRO":
			uf_print_estructuras($li_totrowestpro,$li_estmodest);
		break;	
		case "BUSCARDETALLE":
			uf_print_estructuras_detalles($li_estmodest);
			
		break;	
			
			
		
	}
//----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_estructuras($ai_total,$li_estmodest)
	{			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_estructuras
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//                 li_estmodest //Nivel de la Estructura( 3=>1 ó 5=>2)
		//	  Description: Método que imprime el grid de las Estructuras Presupuestarias
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 08/12/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_configuracion;
		global $ls_nomestpro1 , $ls_nomestpro2, $ls_nomestpro3 , $ls_nomestpro4 ,$ls_nomestpro5;
		// Titulos del Grid de Estructuras
		$lo_title[1]=$ls_nomestpro1;
		$lo_title[2]=$ls_nomestpro2;
		$lo_title[3]=$ls_nomestpro3;
		if($li_estmodest=='2')
		{
			$lo_title[4]=$ls_nomestpro4;
			$lo_title[5]=$ls_nomestpro5;
			$lo_title[6]="Denominaci&oacute;n";
			$lo_title[7]="Tipo";
			$lo_title[8]="";
		}else
		{
			$lo_title[4]="Denominaci&oacute;n";
			$lo_title[5]="Tipo";
			$lo_title[6]="";
		}
		// Recorrido de todos las Estructuras del Grid
		for($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		{
			$ls_codestpro1=$io_funciones_configuracion->uf_obtenervalor("txtcodestpro1".$li_fila,"");
			$ls_codestpro2=$io_funciones_configuracion->uf_obtenervalor("txtcodestpro2".$li_fila,"");
			$ls_codestpro3=$io_funciones_configuracion->uf_obtenervalor("txtcodestpro3".$li_fila,"");
			$ls_codestpro4=$io_funciones_configuracion->uf_obtenervalor("txtcodestpro4".$li_fila,"");
			$ls_codestpro5=$io_funciones_configuracion->uf_obtenervalor("txtcodestpro5".$li_fila,"");
			$ls_denominacion=$io_funciones_configuracion->uf_obtenervalor("txtdenominacion".$li_fila,"");
			$ls_estcla=$io_funciones_configuracion->uf_obtenervalor("txtestcla".$li_fila,"");
			if($ls_estcla=='P'){$ls_tipo='PROYECTO';}
			elseif($ls_estcla=='A'){$ls_tipo='ACCION';}
			else{$ls_tipo="";}
			$j=1;
			$lo_object[$li_fila][$j]="<input type=text name=txtcodestpro1".$li_fila."    id=txtcodestpro1".$li_fila." class=sin-borde style=text-align:center size=20 value='".$ls_codestpro1."'    readonly>";
			$j++;
			$lo_object[$li_fila][$j]="<input type=text name=txtcodestpro2".$li_fila."    id=txtcodestpro2".$li_fila." class=sin-borde style=text-align:center size=20 value='".$ls_codestpro2."'    readonly>";
			$j++;
			$lo_object[$li_fila][$j]="<input type=text name=txtcodestpro3".$li_fila."    id=txtcodestpro3".$li_fila." class=sin-borde style=text-align:center size=20 value='".$ls_codestpro3."'    readonly>";
			$j++;
			if($li_estmodest=='2')
			{
			$lo_object[$li_fila][$j]="<input type=text name=txtcodestpro4".$li_fila."    id=txtcodestpro4".$li_fila." class=sin-borde style=text-align:center size=20 value='".$ls_codestpro4."'    readonly>";
			$j++;
			$lo_object[$li_fila][$j]="<input type=text name=txtcodestpro5".$li_fila."    id=txtcodestpro5".$li_fila." class=sin-borde style=text-align:center size=20 value='".$ls_codestpro5."'    readonly>";
			$j++;
			$lo_object[$li_fila][$j]="<input type=text name=txtdenominacion".$li_fila."    class=sin-borde style=text-align:left   size=20 value='".$ls_denominacion."'    readonly>";
			$j++;
			$lo_object[$li_fila][$j]="<input type=text name=txttipo".$li_fila."    class=sin-borde style=text-align:left   size=20 value='".$ls_tipo."'    readonly>" .
			" <input type=hidden name=txtestcla".$li_fila."  value='".$ls_estcla."'> ";
			}else
			{
			$lo_object[$li_fila][$j]="<input type=text name=txtdenominacion".$li_fila."    class=sin-borde style=text-align:left   size=20 value='".$ls_denominacion."'    readonly>" ;
			$j++;
			$lo_object[$li_fila][$j]="<input type=text name=txttipo".$li_fila."    class=sin-borde style=text-align:left   size=20 value='".$ls_tipo."'    readonly>" .
			" <input type=hidden name=txtestcla".$li_fila."  value='".$ls_estcla."'> ";
			}
			if($li_fila==$ai_total)// si es la última fila no pinto el eliminar
			{
				$j++;
				$lo_object[$li_fila][$j]="";
			}
			else
			{
				$j++;
				$lo_object[$li_fila][$j]="<a href=javascript:ue_delete_estructura('".$li_fila."');><img src=../../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
			}
		}
		print "<p>&nbsp;</p>";
		print "  <table width='595' border='0' align='center' cellpadding='0' cellspacing='0'>";
		print "    <tr>";
		print " 	  <td height='22' align='left'><a href='javascript:catalogo_estpro();'><img src='../../shared/imagebank/tools/nuevo.gif' title='Agregar Presupuestaria' width='20' height='20' border='0'>Agregar Estructura Presupuestaria</a></td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($ai_total,$lo_title,$lo_object,595,"","table1");
	}// end function uf_print_estructuras
//----------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_estructuras_detalles($li_estmodest)
	{			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_estructuras
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//                 li_estmodest //Nivel de la Estructura( 3=>1 ó 5=>2)
		//	  Description: Método que imprime el grid de las Estructuras Presupuestarias
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 08/12/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		
		global $io_grid, $io_funciones_configuracion,$io_sql,$li_longestpro1,$li_longestpro2,$li_longestpro3,$li_longestpro4,$li_longestpro5 ;
		global $ls_nomestpro1 , $ls_nomestpro2, $ls_nomestpro3 , $ls_nomestpro4 ,$ls_nomestpro5;
		// Titulos del Grid de Estructuras
		$lo_title[1]=$ls_nomestpro1;
		$lo_title[2]=$ls_nomestpro2;
		$lo_title[3]=$ls_nomestpro3;
		$ls_codunidadm=$io_funciones_configuracion->uf_obtenervalor("txtcodunidadm","");
		if($li_estmodest=='2')
		{
			 $cadena="(SELECT denestpro5 FROM spg_ep5 AS ep5 WHERE spg_dt.codestpro1=ep5.codestpro1
			           AND  spg_dt.codestpro2=ep5.codestpro2 AND spg_dt.codestpro3=ep5.codestpro3 
					   AND  spg_dt.codestpro4=ep5.codestpro4 AND spg_dt.codestpro5=ep5.codestpro5  ) as denominacion";
			$lo_title[4]=$ls_nomestpro4;
			$lo_title[5]=$ls_nomestpro5;
			$lo_title[6]="Denominaci&oacute;n";
			$lo_title[7]="Tipo";
			$lo_title[8]="";
		}else
		{
			$cadena="(SELECT denestpro3 FROM spg_ep3 AS ep3 WHERE spg_dt.codestpro1=ep3.codestpro1
			           AND  spg_dt.codestpro2=ep3.codestpro2 AND spg_dt.codestpro3=ep3.codestpro3 
					   AND spg_dt.estcla=ep3.estcla) as denominacion";
			$lo_title[4]="Denominaci&oacute;n";
			$lo_title[5]="Tipo";
			$lo_title[6]="";
		}
		$ls_sql=" SELECT codemp,coduniadm,
				  		SUBSTR(codestpro1,".$li_longestpro1.",25) AS codestpro1, 
		        		 SUBSTR(codestpro2,".$li_longestpro2.",25) AS codestpro2,
				         SUBSTR(codestpro3,".$li_longestpro3.",25) AS codestpro3,
						 SUBSTR(codestpro4,".$li_longestpro4.",25) AS codestpro4,
						 SUBSTR(codestpro5,".$li_longestpro5.",25) AS codestpro5,
				  estcla, ".
				 $cadena." FROM spg_dt_unidadadministrativa as spg_dt ".
				" WHERE coduniadm='".$ls_codunidadm."' ";	
		// Recorrido de todos las Estructuras del Grid
		$rs_data = $io_sql->select($ls_sql);
		$num_row=$io_sql->num_rows($rs_data);
		$ai_total=$num_row+1;
		$li_fila=1;
			while ($row=$io_sql->fetch_row($rs_data))
			{
		
	
			$ls_codestpro1=$row["codestpro1"];
			$ls_codestpro2=$row["codestpro2"];
			$ls_codestpro3=$row["codestpro3"];
			$ls_codestpro4=$row["codestpro4"];
			$ls_codestpro5=$row["codestpro5"];
			$ls_denominacion=$row["denominacion"];
			$ls_estcla=$row["estcla"];
			if($ls_estcla=='P'){$ls_tipo='PROYECTO';}
			elseif($ls_estcla=='A'){$ls_tipo='ACCION';}
			else{$ls_tipo="";}
			$j=1;
			$lo_object[$li_fila][$j]="<input type=text name=txtcodestpro1".$li_fila."    id=txtcodestpro1".$li_fila." class=sin-borde style=text-align:center size=20 value='".$ls_codestpro1."'    readonly>";
			$j++;
			$lo_object[$li_fila][$j]="<input type=text name=txtcodestpro2".$li_fila."    id=txtcodestpro2".$li_fila." class=sin-borde style=text-align:center size=20 value='".$ls_codestpro2."'    readonly>";
			$j++;
			$lo_object[$li_fila][$j]="<input type=text name=txtcodestpro3".$li_fila."    id=txtcodestpro3".$li_fila." class=sin-borde style=text-align:center size=20 value='".$ls_codestpro3."'    readonly>";
			$j++;
			if($li_estmodest=='2')
			{
			$lo_object[$li_fila][$j]="<input type=text name=txtcodestpro4".$li_fila."    id=txtcodestpro4".$li_fila." class=sin-borde style=text-align:center size=20 value='".$ls_codestpro4."'    readonly>";
			$j++;
			$lo_object[$li_fila][$j]="<input type=text name=txtcodestpro5".$li_fila."    id=txtcodestpro5".$li_fila." class=sin-borde style=text-align:center size=20 value='".$ls_codestpro5."'    readonly>";
			$j++;
			$lo_object[$li_fila][$j]="<input type=text name=txtdenominacion".$li_fila."    class=sin-borde style=text-align:left   size=20 value='".$ls_denominacion."'    readonly>";
			$j++;
			$lo_object[$li_fila][$j]="<input type=text name=txttipo".$li_fila."    class=sin-borde style=text-align:left   size=20 value='".$ls_tipo."'    readonly>" .
			" <input type=hidden name=txtestcla".$li_fila."  value='".$ls_estcla."'> ";
			}else
			{
			$lo_object[$li_fila][$j]="<input type=text name=txtdenominacion".$li_fila."    class=sin-borde style=text-align:left   size=20 value='".$ls_denominacion."'    readonly>" ;
			$j++;
			$lo_object[$li_fila][$j]="<input type=text name=txttipo".$li_fila."    class=sin-borde style=text-align:left   size=20 value='".$ls_tipo."'    readonly>" .
			" <input type=hidden name=txtestcla".$li_fila."  value='".$ls_estcla."'> ";
			}
			if($li_fila==$ai_total)// si es la última fila no pinto el eliminar
			{
				$j++;
				$lo_object[$li_fila][$j]="";
			}
			else
			{
				$j++;
				$lo_object[$li_fila][$j]="<a href=javascript:ue_delete_estructura('".$li_fila."');><img src=../../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
			}
			$li_fila++;
		}// FOR
			$ls_codestpro1="";
			$ls_codestpro2="";
			$ls_codestpro3="";
			$ls_codestpro4="";
			$ls_codestpro5="";
			$ls_denominacion="";
			$ls_estcla="";
			if($ls_estcla=='P'){$ls_tipo='PROYECTO';}
			elseif($ls_estcla=='A'){$ls_tipo='ACCION';}
			else{$ls_tipo="";}
			$j=1;
			$lo_object[$li_fila][$j]="<input type=text name=txtcodestpro1".$li_fila."    id=txtcodestpro1".$li_fila." class=sin-borde style=text-align:center size=20 value='".$ls_codestpro1."'    readonly>";
			$j++;
			$lo_object[$li_fila][$j]="<input type=text name=txtcodestpro2".$li_fila."    id=txtcodestpro2".$li_fila." class=sin-borde style=text-align:center size=20 value='".$ls_codestpro2."'    readonly>";
			$j++;
			$lo_object[$li_fila][$j]="<input type=text name=txtcodestpro3".$li_fila."    id=txtcodestpro3".$li_fila." class=sin-borde style=text-align:center size=20 value='".$ls_codestpro3."'    readonly>";
			$j++;
			if($li_estmodest=='2')
			{
			$lo_object[$li_fila][$j]="<input type=text name=txtcodestpro4".$li_fila."    id=txtcodestpro4".$li_fila." class=sin-borde style=text-align:center size=20 value='".$ls_codestpro4."'    readonly>";
			$j++;
			$lo_object[$li_fila][$j]="<input type=text name=txtcodestpro5".$li_fila."    id=txtcodestpro5".$li_fila." class=sin-borde style=text-align:center size=20 value='".$ls_codestpro5."'    readonly>";
			$j++;
			$lo_object[$li_fila][$j]="<input type=text name=txtdenominacion".$li_fila."    class=sin-borde style=text-align:left   size=20 value='".$ls_denominacion."'    readonly>";
			$j++;
			$lo_object[$li_fila][$j]="<input type=text name=txttipo".$li_fila."    class=sin-borde style=text-align:left   size=20 value='".$ls_tipo."'    readonly>" .
			" <input type=hidden name=txtestcla".$li_fila."  value='".$ls_estcla."'> ";
			}else
			{
			$lo_object[$li_fila][$j]="<input type=text name=txtdenominacion".$li_fila."    class=sin-borde style=text-align:left   size=20 value='".$ls_denominacion."'    readonly>" ;
			$j++;
			$lo_object[$li_fila][$j]="<input type=text name=txttipo".$li_fila."    class=sin-borde style=text-align:left   size=20 value='".$ls_tipo."'    readonly>" .
			" <input type=hidden name=txtestcla".$li_fila."  value='".$ls_estcla."'> ";
			}
			
				$j++;
				$lo_object[$li_fila][$j]="";
			
		$li_fila++;
		
		
		print "<p>&nbsp;</p>";
		print "  <table width='595' border='0' align='center' cellpadding='0' cellspacing='0'>";
		print "    <tr>";
		print " 	  <td height='22' align='left'><a href='javascript:catalogo_estpro();'><img src='../../shared/imagebank/tools/nuevo.gif' title='Agregar Presupuestaria' width='20' height='20' border='0'>Agregar Estructura Presupuestaria</a></td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($ai_total,$lo_title,$lo_object,595,"","table1");
	}// end function uf_print_estructuras
//----------------------------------------------------------------------------------------------------------------------------------
?>